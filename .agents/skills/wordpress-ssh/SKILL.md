---
name: wordpress-ssh
description: "WordPress SSH Agent skill that connects to a remote WordPress server via SSH and SFTP. Allows running WP-CLI commands, reading/writing theme and system files, and executing shell commands on the remote server using a local environment (.env) configuration."
version: 1.0.0
---

# WordPress SSH Agent Skill

This skill allows your AI assistant to manage and develop a remote WordPress website directly using natural language prompts in the chat. It operates using standard SSH, SFTP, and WP-CLI. No WordPress plugins or extensions are required on the remote site.

---

## 🛠️ How to Use This Skill (Agent Guidelines)

The agent communicates with the remote server by executing the local Python script `wp_ssh_helper.py` via the `run_command` tool.

### 🔑 Configuration Setup
Before running any helper commands, the agent must check that a `.env` file exists in the workspace root. If it is missing, guide the user to copy `.env.example` to `.env` and fill in their SSH credentials. 

*Note: The `GEMINI_API_KEY` is entirely optional and NOT required when the user prompts their assistant directly in the IDE chat (since the IDE agent handles communication with the model). It is only needed if running the standalone script `wp_ssh_agent.py` in a separate terminal.*

---


## 🔌 Commands & Invocation Patterns

The python script is located at:
[wp_ssh_helper.py](file:///.agents/skills/wordpress-ssh/scripts/wp_ssh_helper.py)

### 1. Run a WP-CLI Command
To execute a standard WP-CLI command, run:
```bash
python .agents/skills/wordpress-ssh/scripts/wp_ssh_helper.py wp-cli "<command>"
```
*Example (list active plugins):*
```bash
python .agents/skills/wordpress-ssh/scripts/wp_ssh_helper.py wp-cli "plugin list --status=active"
```
*Note:* Do not include the `wp` prefix inside the quotes; the helper handles prepending it and navigating to the WordPress root path automatically.

### 2. Read a Remote File
To read a file relative to the remote WordPress root (e.g., inside the active theme folder):
```bash
python .agents/skills/wordpress-ssh/scripts/wp_ssh_helper.py read "<relative-path>"
```
*Example (read active theme functions.php):*
```bash
python .agents/skills/wordpress-ssh/scripts/wp_ssh_helper.py read "wp-content/themes/my-theme/functions.php"
```

### 3. Write/Edit a Remote File (Recommended Robust Pattern)
When writing code or text containing quotes, dollar signs, backticks, or special characters, **never** pass it directly in the `--content` parameter. This avoids shell syntax errors or execution failures.

Instead, follow this two-step process:
1. Write the new code content to a temporary local file (e.g., in a temporary file in the workspace).
2. Execute the helper using the `--content-file` parameter:
```bash
python .agents/skills/wordpress-ssh/scripts/wp_ssh_helper.py write "<relative-path>" --content-file "<local-temp-file-path>"
```
3. Delete the local temporary file after the write is successful.

*Example (updating CSS styles):*
- Local temp file created at `c:/Users/anoua/OneDrive/Desktop/top-digital-agencies/.agents/skills/wordpress-ssh/scripts/temp_style.css`
- Run command:
  ```bash
  python .agents/skills/wordpress-ssh/scripts/wp_ssh_helper.py write "wp-content/themes/my-theme/style.css" --content-file ".agents/skills/wordpress-ssh/scripts/temp_style.css"
  ```
- Delete `.agents/skills/wordpress-ssh/scripts/temp_style.css`.

### 4. Execute a Generic Shell Command
To execute administrative bash commands inside the remote WordPress directory:
```bash
python .agents/skills/wordpress-ssh/scripts/wp_ssh_helper.py shell "<command>"
```
*Example (view the latest error logs):*
```bash
python .agents/skills/wordpress-ssh/scripts/wp_ssh_helper.py shell "tail -n 20 wp-content/debug.log"
```

---

## 🛡️ Security & Approval Guidelines

* **Transparency:** Always explain to the user exactly what command or file edit is about to be executed before invoking `run_command`.
* **Verification:** After writing or editing any remote file, run a corresponding WP-CLI lint check (if available) or view the remote file to confirm it was uploaded successfully.
* **Error Handling:** If the helper outputs `Connection failed`, inspect the `.env` configuration file and alert the user to double-check their credentials.
