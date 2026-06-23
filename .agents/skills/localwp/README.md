# LocalWP Skill 💻

This folder contains the **localwp** skill for your AI agent. It allows the agent to interact directly with your LocalWP (Local by Flywheel) WordPress installation on your computer.

It is **zero-config**: no `.env` file, SSH key, or API key is required. The script automatically reads your site configurations and active ports directly from the LocalWP configuration folders.

---

## 📂 Folder Structure

* **`SKILL.md`**: Main instructions instructing the AI agent on how to use the helper script.
* **`scripts/localwp_helper.py`**: Python utility that parses LocalWP settings, configures database TCP routes, and runs commands or reads/writes files locally.

---

## 🛠️ User Setup

1. **Install dependencies:**
   Make sure you have python installed along with the required libraries:
   ```bash
   pip install python-dotenv
   ```
2. **Start your site:**
   Open the **LocalWP** application on your computer and make sure your site (e.g. *Atlas Coffee Tangier*) is **started** (shows a green dot).

---

## 🤖 Interaction Examples

Once your local site is running, simply prompt the agent in your chat:
* *“List my local plugins”*
* *“Install and activate the Astra theme on my local site”*
* *“Read functions.php in my local theme directory”*
* *“Write a custom CSS class to my local theme style.css”*
