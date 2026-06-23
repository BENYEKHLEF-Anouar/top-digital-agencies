#!/usr/bin/env python3
"""
WordPress SSH AI Agent
----------------------
A secure, local AI agent that connects to a remote WordPress server via SSH/SFTP
and lets you configure, develop, and manage your WordPress project using natural language.

Requires:
  pip install paramiko google-generativeai python-dotenv
"""

import os
import sys
import paramiko
from dotenv import load_dotenv
import google.generativeai as genai

# Load local environment variables
load_dotenv()

# Check for API Key
if not os.environ.get("GEMINI_API_KEY"):
    print("Error: GEMINI_API_KEY is not set in the environment or .env file.")
    sys.exit(1)

# Configure Gemini
genai.configure(api_key=os.environ["GEMINI_API_KEY"])

class SSHManager:
    """Manages SSH connections and executes commands/SFTP actions on the remote server."""
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
        print(f"Connecting to {self.host}:{self.port} as user '{self.user}'...")
        self.client = paramiko.SSHClient()
        self.client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        
        try:
            if self.key_path:
                key_path = os.path.expanduser(self.key_path)
                try:
                    # Try reading without passphrase
                    key = paramiko.RSAKey.from_private_key_file(key_path)
                except paramiko.ssh_exception.PasswordRequiredException:
                    # Key requires a passphrase/password
                    key = paramiko.RSAKey.from_private_key_file(key_path, password=self.password)
                self.client.connect(self.host, port=self.port, username=self.user, pkey=key)
            else:
                self.client.connect(self.host, port=self.port, username=self.user, password=self.password)
            
            self.sftp = self.client.open_sftp()
            print("Successfully established SSH & SFTP connections.")
        except Exception as e:
            print(f"Connection failed: {str(e)}")
            sys.exit(1)

    def execute_command(self, cmd):
        """Execute a command in the remote WordPress root folder."""
        # Navigate to WordPress path before running commands
        full_cmd = f"cd {self.wp_path} && {cmd}"
        stdin, stdout, stderr = self.client.exec_command(full_cmd)
        
        # Read outputs
        out = stdout.read().decode('utf-8', errors='ignore')
        err = stderr.read().decode('utf-8', errors='ignore')
        return out, err

    def read_file(self, path):
        """Read remote file content using SFTP."""
        full_path = path if os.path.isabs(path) else os.path.join(self.wp_path, path)
        full_path = full_path.replace("\\", "/") # Normalize path for Unix
        
        with self.sftp.open(full_path, "r") as f:
            return f.read().decode('utf-8', errors='ignore')

    def write_file(self, path, content):
        """Write content to remote file using SFTP."""
        full_path = path if os.path.isabs(path) else os.path.join(self.wp_path, path)
        full_path = full_path.replace("\\", "/") # Normalize path for Unix
        
        with self.sftp.open(full_path, "w") as f:
            f.write(content.encode('utf-8'))
        return f"File successfully written to: {path}"

    def close(self):
        if self.sftp:
            self.sftp.close()
        if self.client:
            self.client.close()
        print("SSH/SFTP connections closed.")

# Initialize the global SSH manager using credentials from environment
ssh = SSHManager(
    host=os.environ.get("SSH_HOST"),
    port=os.environ.get("SSH_PORT", 22),
    user=os.environ.get("SSH_USER"),
    password=os.environ.get("SSH_PASSWORD"),
    key_path=os.environ.get("SSH_KEY_PATH"),
    wp_path=os.environ.get("WP_PATH")
)

# ----------------------------------------------------
# AGENT TOOL DEFINITIONS
# ----------------------------------------------------

def run_wp_cli(command: str) -> str:
    """Run a WP-CLI command inside the remote WordPress root folder.
    
    Args:
        command: The wp-cli subcommand (e.g., 'plugin list', 'theme install twentytwentyfour --activate', or 'user list'). DO NOT include 'wp' prefix.
    """
    clean_cmd = command.strip()
    if clean_cmd.startswith("wp "):
        clean_cmd = clean_cmd[3:]
    
    print(f"   [Executing WP-CLI] wp {clean_cmd}")
    out, err = ssh.execute_command(f"wp {clean_cmd}")
    if err and not out:
        return f"Error: {err}"
    return out + (f"\nWarning/Stderr: {err}" if err else "")

def read_code_file(remote_path: str) -> str:
    """Read the content of a remote file in the WordPress directory (like functions.php, style.css, or template files).
    
    Args:
        remote_path: Path to the file, relative to WordPress root (e.g. 'wp-content/themes/my-theme/functions.php').
    """
    print(f"   [Reading Remote File] {remote_path}")
    try:
        return ssh.read_file(remote_path)
    except Exception as e:
        return f"Error reading file: {str(e)}"

def write_code_file(remote_path: str, content: str) -> str:
    """Write/replace content of a remote file inside the WordPress directory.
    
    Args:
        remote_path: Path to the file relative to WordPress root (e.g. 'wp-content/themes/my-theme/custom-style.css').
        content: Complete new file content to write.
    """
    print(f"   [Writing Remote File] {remote_path}")
    try:
        return ssh.write_file(remote_path, content)
    except Exception as e:
        return f"Error writing file: {str(e)}"

def run_shell_command(cmd: str) -> str:
    """Run a generic bash shell command in the remote WordPress directory.
    
    Args:
        cmd: The shell command to run (e.g., 'ls -la', 'mkdir wp-content/themes/custom-theme', or 'tail -n 50 wp-content/debug.log').
    """
    print(f"   [Executing Shell] {cmd}")
    out, err = ssh.execute_command(cmd)
    if err and not out:
        return f"Error: {err}"
    return out + (f"\nWarning/Stderr: {err}" if err else "")

# Tool mapper helper
def execute_tool(name, args):
    if name == "run_wp_cli":
        return run_wp_cli(**args)
    elif name == "read_code_file":
        return read_code_file(**args)
    elif name == "write_code_file":
        return write_code_file(**args)
    elif name == "run_shell_command":
        return run_shell_command(**args)
    else:
        raise ValueError(f"Unknown tool name: {name}")

# System instructions
SYSTEM_INSTRUCTION = """
You are an expert WordPress Developer and Systems Administrator AI Agent.
You assist the user in building, developing, and managing their remote WordPress website using SSH and WP-CLI.

You have access to tools to:
1. Run WP-CLI commands (`run_wp_cli`).
2. Read remote files (`read_code_file`).
3. Write/edit remote files (`write_code_file`).
4. Run general shell commands (`run_shell_command`).

Rules of operation:
- First run a quick check of the environment if you don't know the themes or plugins.
- If you need to edit a file (e.g., functions.php), read it first using `read_code_file` before making modifications.
- Call tools when required to fulfill the user's prompt.
- Do not construct mock outputs. Present actual results returned from the server.
- Focus on clean, modular developer practices.
"""

def main():
    # Verify environment values
    required_keys = ["SSH_HOST", "SSH_USER"]
    missing = [k for k in required_keys if not os.environ.get(k)]
    if missing:
        print(f"Error: Missing environment variables: {', '.join(missing)}")
        print("Please configure your .env file before running.")
        sys.exit(1)

    # Establish remote connection
    ssh.connect()

    # Configure Gemini Agent
    model = genai.GenerativeModel(
        model_name="gemini-2.5-flash",
        tools=[run_wp_cli, read_code_file, write_code_file, run_shell_command],
        system_instruction=SYSTEM_INSTRUCTION
    )

    # Initialize a multi-turn chat session with manual function calling for safety
    chat = model.start_chat(enable_automatic_function_calling=False)

    print("\n==================================================")
    # Perform a quick site check automatically to prime the agent context
    print("Priming agent context (detecting active themes and plugins)...")
    prime_prompt = "Run basic WP-CLI plugins and theme listings to find what is installed and active on this site. Summarize briefly."
    
    # Process initial priming
    response = chat.send_message(prime_prompt)
    process_response(chat, response)
    
    print("\nWordPress SSH Agent ready. Type 'exit' or 'quit' to close.")
    print("==================================================")

    while True:
        try:
            user_input = input("\nYou > ").strip()
            if not user_input:
                continue
            if user_input.lower() in ["exit", "quit"]:
                break
                
            # Send message to agent
            response = chat.send_message(user_input)
            process_response(chat, response)
            
        except KeyboardInterrupt:
            print("\nExiting...")
            break
        except Exception as e:
            print(f"\nAn error occurred: {str(e)}")

    ssh.close()

def process_response(chat, response):
    """Processes chat turn, handles tool execution requests, prompts for user approval, and loops back."""
    while True:
        has_calls = False
        function_responses = []
        
        # Check the last message in history to inspect function calls
        last_message = chat.history[-1]
        if last_message.role == "model":
            for part in last_message.parts:
                if part.function_call:
                    has_calls = True
                    call = part.function_call
                    
                    # Highlight the proposed action for safety approval
                    print(f"\n[AI PROPOSAL] Function: {call.name}")
                    for arg_name, arg_val in call.args.items():
                        # Truncate content preview for legibility if it is file contents
                        val_str = str(arg_val)
                        if len(val_str) > 120:
                            val_str = val_str[:120] + "... [truncated]"
                        print(f"  {arg_name}: {val_str}")
                    
                    # Prompt user for explicit approval
                    approval = input("Execute remote action? [y/N]: ").strip().lower()
                    if approval in ["y", "yes"]:
                        try:
                            result = execute_tool(call.name, call.args)
                        except Exception as e:
                            result = f"Execution failed: {str(e)}"
                    else:
                        result = "Action rejected by user."
                        print(f"[STATUS] {result}")
                    
                    # Add response part
                    function_responses.append(
                        genai.protos.Part(
                            function_response=genai.protos.FunctionResponse(
                                name=call.name,
                                response={"result": result}
                            )
                        )
                    )
        
        # If there were tool execution requests, send responses back to the model for next turn
        if has_calls:
            response = chat.send_message(genai.protos.Content(parts=function_responses))
        else:
            # Print the final text output of the agent
            if response.text:
                print(f"\nAgent > {response.text}")
            break

if __name__ == "__main__":
    main()
