# WordPress SSH Skill 🔌

This folder contains the **wordpress-ssh** skill for your AI agent. It allows the agent to interact directly with your remote WordPress installation via standard SSH, SFTP, and WP-CLI commands using a local `.env` configuration.

---

## 📂 Folder Structure

* **`SKILL.md`**: Main instructions instructing the AI agent on how to use the helper script.
* **`scripts/wp_ssh_helper.py`**: Non-interactive Python utility that connects to the remote server and processes single commands or read/write requests from the agent.
* **`scripts/wp_ssh_agent.py`**: Independent console application that runs an interactive prompt loop if you wish to talk to the agent directly in your command line.
* **`resources/.env.example`**: Example template configuration file.

---

## 🛠️ User Setup

To enable this skill:

1. **Install dependencies:**
   Ensure `paramiko`, `google-generativeai`, and `python-dotenv` are installed locally:
   ```bash
   pip install paramiko google-generativeai python-dotenv
   ```
2. **Configure Credentials:**
   Copy the `.env.example` file to `.env` in your workspace root, and configure your remote server SSH details:
   - `SSH_HOST` (IP or server name)
   - `SSH_USER` (SSH username)
   - `SSH_PASSWORD` (Password or private key passphrase)
   - `SSH_KEY_PATH` (Path to SSH Private Key, highly recommended)
   - `WP_PATH` (Remote path where WordPress is installed, e.g. `/var/www/html`)
   
   > [!TIP]
   > You **do not** need to provide `GEMINI_API_KEY` if you are chatting with your AI agent directly in the IDE chat. It is only required if you run the standalone `wp_ssh_agent.py` script.

---

## 🤖 Interaction Examples

Once your `.env` is configured, just prompt the agent in your chat:
* *“Check my active plugins and themes.”*
* *“Install and activate the Astra theme.”*
* *“Add a custom PHP function to functions.php to limit excerpt length.”*
