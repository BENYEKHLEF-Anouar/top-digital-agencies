# Connecting Claude to Your WordPress Project on Windows

> Your site is on a remote host (SiteGround, Hostinger, etc.)
> This guide shows you how to let Claude work with your WordPress files.

---

## The Big Picture

Claude works with **files on your computer**. Since your WordPress site is on a remote server, the workflow is:

```
Remote Server (your host)
        ↕  FTP/SFTP
Your Computer (Windows)
        ↕  VS Code + Claude
Claude reads & edits the files
        ↕  FTP/SFTP
Upload changes back to server
```

Or, with SSH (more advanced), Claude works directly on the server — no downloading needed.

---

## Method 1 — FTP + VS Code (Recommended for Beginners)

This is the simplest approach. You download your WordPress files to your PC, open them in VS Code, Claude helps you edit, then you upload back.

---

### Step 1 — Get your FTP credentials

Log into your hosting dashboard (cPanel, SiteGround, Hostinger, etc.) and find:
- **Host** (e.g. `ftp.yoursite.com` or your server IP)
- **Username**
- **Password**
- **Port** (usually `21` for FTP, `22` for SFTP)

These are usually under: **Files > FTP Accounts** or **Advanced > FTP**.

---

### Step 2 — Install FileZilla (free FTP client)

Download from: **filezilla-project.org** → FileZilla Client

Connect to your server:
1. Open FileZilla
2. Top bar: enter your Host, Username, Password, Port → click **Quickconnect**
3. On the right panel you'll see your server files
4. Navigate to `public_html/wp-content/themes/your-theme/`

---

### Step 3 — Download your theme folder to your PC

- On the right (server): find your theme folder (e.g. `public_html/wp-content/themes/my-theme/`)
- On the left (your PC): navigate to where you want to save it (e.g. `C:\Users\YourName\Documents\wordpress-projects\`)
- Drag the theme folder from right → left to download it

---

### Step 4 — Open the folder in VS Code

1. Open **VS Code**
2. **File > Open Folder** → select the theme folder you just downloaded
3. You now see all your theme files in the sidebar

Claude can now read and edit all files in that folder — `style.css`, `functions.php`, template files, etc.

---

### Step 5 — Upload changes back to server

After Claude helps you make changes:
1. Go back to FileZilla
2. Find the edited file on the left (your PC)
3. Right-click → **Upload** to push it to the server

> **Tip:** Right-click the folder → **Upload** to sync the entire folder at once.

---

### VS Code FTP Extension (optional — makes it automatic)

Install the extension **SFTP** by Natizyskunk in VS Code:
- It auto-uploads files to your server every time you save
- No need to go back to FileZilla manually

Setup: Press `Ctrl+Shift+P` → type `SFTP: Config` → fill in your host, user, password, and remote path.

```json
{
    "name": "My WordPress Site",
    "host": "ftp.yoursite.com",
    "protocol": "sftp",
    "port": 22,
    "username": "your-ftp-user",
    "password": "your-ftp-password",
    "remotePath": "/public_html/wp-content/themes/my-theme/",
    "uploadOnSave": true
}
```

With `"uploadOnSave": true`, every `Ctrl+S` instantly pushes the file to your server.

---

## Method 2 — SSH + VS Code Remote (More Advanced)

This lets you edit files **directly on the server** — no downloading. Claude works on the live files in real time.

### Requirements
- Your hosting must support **SSH access** (most plans do — check your cPanel)
- VS Code extension: **Remote - SSH** by Microsoft

### Steps

1. Install **Remote - SSH** extension in VS Code
2. Press `Ctrl+Shift+P` → `Remote-SSH: Connect to Host`
3. Enter: `your-username@yoursite.com`
4. Enter your SSH password when prompted
5. VS Code reconnects — now you're inside your server
6. **File > Open Folder** → navigate to `/public_html/wp-content/themes/my-theme/`

Claude now reads and edits files directly on the server. No upload step needed.

> **Get SSH credentials:** cPanel → Advanced → SSH Access → Manage SSH Keys

---

## Method 3 — WordPress REST API (for content, not code)

The two methods above are for **editing code** (theme files, functions.php, etc.).

If you want Claude to **manage WordPress content** (create posts, update pages, change settings) without touching files — you can use the **WordPress REST API**.

WordPress has a built-in REST API at: `yoursite.com/wp-json/wp/v2/`

You can ask Claude to write scripts or WP-CLI commands that talk to this API. This is more advanced and useful for automating content, not for building themes.

---

## What Claude Can Do Once Connected

Once your WordPress project is open in VS Code (via any method above), Claude can:

| Task | Example |
|---|---|
| Write PHP for you | "Add a custom function to functions.php that limits the excerpt to 20 words" |
| Explain existing code | "What does this loop in my template file do?" |
| Fix errors | "I'm getting a white screen — here's my functions.php, what's wrong?" |
| Build ACF templates | "Write the PHP loop to display my Flexible Content layouts" |
| Create template parts | "Create a template-part for a portfolio card" |
| Write CSS | "Add responsive styles to my hero section" |
| Suggest improvements | "Review my functions.php and suggest improvements" |

---

## Recommended Setup for Beginners

```
1. FileZilla           → download/upload WordPress files
2. VS Code             → code editor
3. SFTP extension      → auto-upload on save (optional but convenient)
4. Claude Code         → AI help inside VS Code
```

This gives you a clean, simple workflow without needing advanced server knowledge.

---

## Important: Always Back Up First

Before letting Claude (or anyone) edit live WordPress files:

1. **Backup your site** — most hosts have a 1-click backup in the dashboard
2. **Or use a plugin** like UpdraftPlus to create a backup
3. Download a copy of any file before editing it (FileZilla → right-click → Download)

If something breaks, you can restore from backup.

---

*Last updated: June 2026*
