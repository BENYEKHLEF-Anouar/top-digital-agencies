#!/usr/bin/env python3
"""
WordPress SSH Skill Helper
--------------------------
Non-interactive CLI tool used by the AI agent to communicate with a remote
WordPress server via SSH and SFTP using credentials in the workspace .env file.

Usage:
  python wp_ssh_helper.py wp-cli "plugin list"
  python wp_ssh_helper.py read "wp-content/themes/twentytwentyfour/functions.php"
  python wp_ssh_helper.py write "wp-content/themes/twentytwentyfour/custom.css" --content "body { background: #000; }"
  python wp_ssh_helper.py shell "ls -la"
"""

import os
import sys
import argparse
import paramiko
from dotenv import load_dotenv

# Load environment variables from the current working directory (.env)
load_dotenv()

class SSHManager:
    def __init__(self, host, port, user, password=None, key_path=None, wp_path=None):
        self.host = host
        self.port = int(port) if port else 22
        self.user = user
        self.password = password
        self.key_path = key_path
        self.wp_path = wp_path or "."
        self.client = None
        self.sftp = None

    def connect(self):
        self.client = paramiko.SSHClient()
        self.client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        
        try:
            if self.key_path:
                key_path = os.path.expanduser(self.key_path)
                try:
                    key = paramiko.RSAKey.from_private_key_file(key_path)
                except paramiko.ssh_exception.PasswordRequiredException:
                    key = paramiko.RSAKey.from_private_key_file(key_path, password=self.password)
                self.client.connect(self.host, port=self.port, username=self.user, pkey=key)
            else:
                self.client.connect(self.host, port=self.port, username=self.user, password=self.password)
            
            self.sftp = self.client.open_sftp()
        except Exception as e:
            print(f"Connection failed: {str(e)}", file=sys.stderr)
            sys.exit(1)

    def execute_command(self, cmd):
        full_cmd = f"cd {self.wp_path} && {cmd}"
        stdin, stdout, stderr = self.client.exec_command(full_cmd)
        out = stdout.read().decode('utf-8', errors='ignore')
        err = stderr.read().decode('utf-8', errors='ignore')
        return out, err

    def read_file(self, path):
        full_path = path if os.path.isabs(path) else os.path.join(self.wp_path, path)
        full_path = full_path.replace("\\", "/")
        with self.sftp.open(full_path, "r") as f:
            return f.read().decode('utf-8', errors='ignore')

    def write_file(self, path, content):
        full_path = path if os.path.isabs(path) else os.path.join(self.wp_path, path)
        full_path = full_path.replace("\\", "/")
        
        # Ensure directories exist (simple remote directory creation)
        dir_path = os.path.dirname(full_path)
        parts = dir_path.split('/')
        current = ""
        for part in parts:
            if not part:
                if dir_path.startswith('/'):
                    current = "/"
                continue
            current = os.path.join(current, part).replace("\\", "/")
            try:
                self.sftp.mkdir(current)
            except IOError:
                pass # Already exists or permission error
                
        with self.sftp.open(full_path, "w") as f:
            f.write(content.encode('utf-8'))
        return f"File successfully written to: {path}"

    def close(self):
        if self.sftp:
            self.sftp.close()
        if self.client:
            self.client.close()

def main():
    parser = argparse.ArgumentParser(description="WordPress Remote SSH helper script for AI Agents")
    parser.add_argument("action", choices=["wp-cli", "read", "write", "shell"], help="Action to execute")
    parser.add_argument("target", help="WP-CLI command, remote file path, or shell command to run")
    parser.add_argument("--content", help="Text content to write (for 'write' action)")
    parser.add_argument("--content-file", help="Local file path containing content to write (for 'write' action)")
    
    args = parser.parse_args()
    
    required_keys = ["SSH_HOST", "SSH_USER"]
    missing = [k for k in required_keys if not os.environ.get(k)]
    if missing:
        print(f"Error: Missing environment variables: {', '.join(missing)}", file=sys.stderr)
        sys.exit(1)
        
    ssh = SSHManager(
        host=os.environ.get("SSH_HOST"),
        port=os.environ.get("SSH_PORT", 22),
        user=os.environ.get("SSH_USER"),
        password=os.environ.get("SSH_PASSWORD"),
        key_path=os.environ.get("SSH_KEY_PATH"),
        wp_path=os.environ.get("WP_PATH")
    )
    
    ssh.connect()
    
    try:
        if args.action == "wp-cli":
            clean_cmd = args.target.strip()
            if clean_cmd.startswith("wp "):
                clean_cmd = clean_cmd[3:]
            out, err = ssh.execute_command(f"wp {clean_cmd}")
            if out:
                print(out)
            if err:
                print(err, file=sys.stderr)
                
        elif args.action == "read":
            content = ssh.read_file(args.target)
            print(content, end="")
            
        elif args.action == "write":
            content_to_write = ""
            if args.content:
                content_to_write = args.content
            elif args.content_file:
                with open(args.content_file, "r", encoding="utf-8") as f:
                    content_to_write = f.read()
            else:
                print("Error: Either --content or --content-file must be provided for 'write' action.", file=sys.stderr)
                sys.exit(1)
                
            res = ssh.write_file(args.target, content_to_write)
            print(res)
            
        elif args.action == "shell":
            out, err = ssh.execute_command(args.target)
            if out:
                print(out)
            if err:
                print(err, file=sys.stderr)
                
    except Exception as e:
        print(f"Error during execution: {str(e)}", file=sys.stderr)
        ssh.close()
        sys.exit(1)
        
    ssh.close()

if __name__ == "__main__":
    main()
