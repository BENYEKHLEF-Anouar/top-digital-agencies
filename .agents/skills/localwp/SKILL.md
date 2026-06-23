---
name: localwp
description: "LocalWP Agent skill that interacts directly with a local WordPress site running in Local by Flywheel on Windows. Allows executing WP-CLI commands, reading/writing local files, and executing shell commands in the site directory without SSH."
version: 1.0.0
---

# LocalWP Agent Skill

This skill allows your AI assistant to manage and build your LocalWP (Local by Flywheel) WordPress website directly on your Windows PC from the chat. It interfaces with the local database, PHP runtime, and files natively, bypassing any SSH or SFTP overhead.

---

## 🛠️ How to Use This Skill (Agent Guidelines)

The agent communicates with the LocalWP installation by executing the local Python script `localwp_helper.py` via the `run_command` tool.

### 🔍 Auto-Detection
The helper automatically parses `%APPDATA%/Local/sites.json` to find:
- The path to your site's files (e.g. `C:/Users/anoua/Local Sites/atlas-coffee-tangier/app/public`)
- The active MySQL port (e.g. `10005`)
- The PHP version configuration

It then writes a temporary patch to route database queries via TCP/IP on localhost, resolving standard Windows `localhost` named pipe connection limits.

---

## 🔌 Commands & Invocation Patterns

The python script is located at:
[localwp_helper.py](file:///.agents/skills/localwp/scripts/localwp_helper.py)

### 1. Run a WP-CLI Command
To execute a standard WP-CLI command locally:
```bash
python .agents/skills/localwp/scripts/localwp_helper.py wp-cli "<command>"
```
*Example (list active plugins):*
```bash
python .agents/skills/localwp/scripts/localwp_helper.py wp-cli "plugin list --status=active"
```
*Note:* If the site is stopped in the LocalWP application, the database will be offline, and this command will exit with an instruction asking you to start the site in LocalWP.

### 2. Read a Local Site File
To read a file relative to the WordPress public folder (e.g., inside the active theme folder):
```bash
python .agents/skills/localwp/scripts/localwp_helper.py read "<relative-path>"
```
*Example (read active theme functions.php):*
```bash
python .agents/skills/localwp/scripts/localwp_helper.py read "wp-content/themes/twentytwentyfour/functions.php"
```

### 3. Write/Edit a Local Site File (Recommended Robust Pattern)
When writing code or text containing quotes, dollar signs, backticks, or special characters, **never** pass it directly in the `--content` parameter. This avoids shell syntax errors or execution failures.

Instead, follow this two-step process:
1. Write the new code content to a temporary local file in the workspace.
2. Execute the helper using the `--content-file` parameter:
```bash
python .agents/skills/localwp/scripts/localwp_helper.py write "<relative-path>" --content-file "<local-temp-file-path>"
```
3. Delete the local temporary file after the write is successful.

*Example (updating CSS styles):*
- Local temp file created at `c:/Users/anoua/OneDrive/Desktop/top-digital-agencies/.agents/skills/localwp/scripts/temp_style.css`
- Run command:
  ```bash
  python .agents/skills/localwp/scripts/localwp_helper.py write "wp-content/themes/twentytwentyfour/style.css" --content-file ".agents/skills/localwp/scripts/temp_style.css"
  ```
- Delete `.agents/skills/localwp/scripts/temp_style.css`.

### 4. Execute a Local Site Shell Command
To execute administrative commands inside the local WordPress directory:
```bash
python .agents/skills/localwp/scripts/localwp_helper.py shell "<command>"
```
*Example (list the public directory contents):*
```bash
python .agents/skills/localwp/scripts/localwp_helper.py shell "dir"
```

---

## 🛡️ Security & Approval Guidelines

* **Transparency:** Always explain to the user exactly what command or file edit is about to be executed before invoking `run_command`.
* **State Check:** If a connection fails, check whether the site is running in the LocalWP dashboard UI.
