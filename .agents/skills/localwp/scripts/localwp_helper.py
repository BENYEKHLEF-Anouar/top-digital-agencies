#!/usr/bin/env python3
"""
LocalWP Skill Helper
--------------------
CLI tool used by the AI agent to interact with a LocalWP site on Windows.
It auto-detects site configuration, active DB ports, PHP versions, and executes
WP-CLI or file edits locally without requiring any SSH or FTP connections.

Usage:
  python localwp_helper.py wp-cli "plugin list"
  python localwp_helper.py read "wp-content/themes/twentytwentyfour/functions.php"
  python localwp_helper.py write "wp-content/themes/twentytwentyfour/custom.css" --content "body { background: #000; }"
  python localwp_helper.py shell "dir"
"""

import os
import sys
import json
import socket
import argparse
import subprocess
import tempfile

def get_localwp_sites():
    appdata_path = os.environ.get("APPDATA")
    sites_json_path = os.path.join(appdata_path, "Local", "sites.json")
    if not os.path.exists(sites_json_path):
        print(f"Error: LocalWP configuration not found at {sites_json_path}", file=sys.stderr)
        sys.exit(1)
    
    with open(sites_json_path, "r", encoding="utf-8") as f:
        return json.load(f)

def find_active_site(sites, target_site_name=None):
    # 1. If a specific site name was requested via CLI argument:
    if target_site_name:
        for site in sites.values():
            if target_site_name.lower() in site["name"].lower() or target_site_name.lower() in site["id"].lower():
                return site
        print(f"Error: Requested site '{target_site_name}' not found in LocalWP sites.", file=sys.stderr)
        sys.exit(1)
        
    # 2. Check if a site is configured in the environment (.env or process env)
    env_site = os.environ.get("LOCALWP_SITE")
    if env_site:
        for site in sites.values():
            if env_site.lower() in site["name"].lower() or env_site.lower() in site["id"].lower():
                return site
                
    # 3. Check if the current workspace directory name matches a site name
    cwd = os.getcwd()
    for site in sites.values():
        site_name_safe = site["name"].lower().replace(" ", "-")
        if site_name_safe in cwd.lower() or site["id"].lower() in cwd.lower():
            return site
            
    # 4. Fallback prioritizations
    # Prioritize the new training-acf site
    for site in sites.values():
        if "training-acf" in site["name"].lower():
            return site
            
    # Prioritize Atlas Coffee Tangier
    for site in sites.values():
        if "atlas-coffee-tangier" in site["name"].lower() or "atlas" in site["name"].lower():
            return site
            
    # Fallback to the first site in the list
    return list(sites.values())[0]

def is_db_running(port):
    try:
        with socket.create_connection(("127.0.0.1", port), timeout=1.5):
            return True
    except OSError:
        return False

def main():
    parser = argparse.ArgumentParser(description="LocalWP Site Controller for AI Agents")
    parser.add_argument("action", choices=["wp-cli", "read", "write", "shell"], help="Action to perform")
    parser.add_argument("target", help="WP-CLI command, file path, or shell command")
    parser.add_argument("--content", help="Content to write (for 'write' action)")
    parser.add_argument("--content-file", help="Local file path containing content to write (for 'write' action)")
    parser.add_argument("--site", help="Target LocalWP site name or ID")
    
    args = parser.parse_args()
    
    sites = get_localwp_sites()
    if not sites:
        print("Error: No LocalWP sites found.", file=sys.stderr)
        sys.exit(1)
        
    site = find_active_site(sites, target_site_name=args.site)
    
    # Resolve home directory in site path (e.g. ~\\Local Sites\\site)
    raw_path = site["path"]
    if raw_path.startswith("~"):
        home = os.path.expanduser("~")
        site_path = raw_path.replace("~", home)
    else:
        site_path = raw_path
    
    site_path = os.path.abspath(site_path)
    public_path = os.path.join(site_path, "app", "public")
    
    # Check database status
    mysql_port = site["services"]["mysql"]["ports"]["MYSQL"][0]
    db_active = is_db_running(mysql_port)
    
    # Extract service versions
    php_ver = site["services"]["php"]["version"]
    
    if args.action == "wp-cli" and not db_active:
        print(f"Error: The local site '{site['name']}' database (Port {mysql_port}) is not running.", file=sys.stderr)
        print("Please start the site inside the LocalWP application before running commands.", file=sys.stderr)
        sys.exit(1)
        
    if args.action == "wp-cli":
        # Resolve binaries
        appdata_path = os.environ.get("APPDATA")
        php_exe = os.path.join(appdata_path, "Local", "lightning-services", f"php-{php_ver}+0", "bin", "win64", "php.exe")
        php_ini = os.path.join(appdata_path, "Local", "run", site["id"], "conf", "php", "php.ini")
        wp_cli_phar = "C:\\Program Files (x86)\\Local\\resources\\extraResources\\bin\\wp-cli\\wp-cli.phar"
        
        # Verify paths exist
        if not os.path.exists(php_exe):
            # Fallback to standard path if +0 directory name differs
            php_exe = os.path.join(appdata_path, "Local", "lightning-services", f"php-{php_ver}", "bin", "win64", "php.exe")
            if not os.path.exists(php_exe):
                print(f"Error: PHP binary not found at {php_exe}", file=sys.stderr)
                sys.exit(1)
                
        if not os.path.exists(wp_cli_phar):
            print(f"Error: WP-CLI phar file not found at {wp_cli_phar}", file=sys.stderr)
            sys.exit(1)
            
        # Create a temporary DB Host patch file to override "localhost" to "127.0.0.1:port"
        # This solves the Windows named pipes issue when DB_HOST is localhost
        with tempfile.NamedTemporaryFile(mode="w", delete=False, suffix=".php") as temp_patch:
            temp_patch.write(f"<?php define('DB_HOST', '127.0.0.1:{mysql_port}'); ?>")
            temp_patch_path = temp_patch.name
            
        try:
            # Build and run execution command
            wp_cmd = args.target.strip()
            if wp_cmd.startswith("wp "):
                wp_cmd = wp_cmd[3:]
                
            cmd = [
                php_exe,
                "-d", f"auto_prepend_file={temp_patch_path}",
                "-c", php_ini,
                wp_cli_phar,
                f"--path={public_path}"
            ] + wp_cmd.split()
            
            result = subprocess.run(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True, shell=True)
            
            if result.stdout:
                print(result.stdout)
            if result.stderr:
                print(result.stderr, file=sys.stderr)
                
        finally:
            # Clean up the patch file
            if os.path.exists(temp_patch_path):
                os.remove(temp_patch_path)
                
    elif args.action == "read":
        # Resolve target path relative to WordPress public root
        full_path = os.path.join(public_path, args.target)
        full_path = os.path.abspath(full_path)
        
        # Security check: Ensure we stay inside the public folder
        if not full_path.startswith(public_path):
            print("Error: Target path must be inside the WordPress public folder.", file=sys.stderr)
            sys.exit(1)
            
        try:
            with open(full_path, "r", encoding="utf-8", errors="ignore") as f:
                print(f.read(), end="")
        except Exception as e:
            print(f"Error reading file: {str(e)}", file=sys.stderr)
            sys.exit(1)
            
    elif args.action == "write":
        full_path = os.path.join(public_path, args.target)
        full_path = os.path.abspath(full_path)
        
        if not full_path.startswith(public_path):
            print("Error: Target path must be inside the WordPress public folder.", file=sys.stderr)
            sys.exit(1)
            
        content_to_write = ""
        if args.content:
            content_to_write = args.content
        elif args.content_file:
            with open(args.content_file, "r", encoding="utf-8") as f:
                content_to_write = f.read()
        else:
            print("Error: Either --content or --content-file must be provided for 'write' action.", file=sys.stderr)
            sys.exit(1)
            
        try:
            os.makedirs(os.path.dirname(full_path), exist_ok=True)
            with open(full_path, "w", encoding="utf-8") as f:
                f.write(content_to_write)
            print(f"File successfully written locally: {args.target}")
        except Exception as e:
            print(f"Error writing file: {str(e)}", file=sys.stderr)
            sys.exit(1)
            
    elif args.action == "shell":
        # Run local shell command inside WordPress root
        try:
            result = subprocess.run(args.target, cwd=public_path, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True, shell=True)
            if result.stdout:
                print(result.stdout)
            if result.stderr:
                print(result.stderr, file=sys.stderr)
        except Exception as e:
            print(f"Error executing command: {str(e)}", file=sys.stderr)
            sys.exit(1)

if __name__ == "__main__":
    main()
