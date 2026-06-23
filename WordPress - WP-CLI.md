# WP-CLI

> Related: [[WordPress - Custom Post Types (CPT)]] · [[WordPress - ACF Flexible Content]]

---

## What is WP-CLI?

**WP-CLI** (WordPress Command Line Interface) is a tool that lets you control WordPress from your **terminal** instead of clicking around the admin dashboard.

You type a command → WordPress does it instantly.

It's like a remote control for your WordPress site — much faster for repetitive tasks, migrations, and database operations.

| Task | Admin Dashboard | WP-CLI |
|---|---|---|
| Install + activate a plugin | Login → Plugins → Add New → Search → Install → Activate | `wp plugin install akismet --activate` |
| Update all plugins | Click Update on each one | `wp plugin update --all` |
| Reset a password | Users → Edit → Change Password → Save | `wp user update 1 --user_pass=newpass` |
| Export database | phpMyAdmin → Export → Download file | `wp db export backup.sql` |
| Move site to new domain | Plugin or manual SQL find/replace | `wp search-replace 'old.com' 'new.com'` |

---

## Installing WP-CLI on Windows

WP-CLI is a Linux/Mac tool by nature. On Windows, use one of these approaches:

### Option 1 — Local by Flywheel (Easiest — Recommended)

If you develop WordPress locally with **Local by Flywheel**, WP-CLI is already installed.

1. Open Local
2. Right-click your site → **Open Site Shell**
3. A terminal opens directly inside your WordPress folder
4. Type any `wp` command — it works immediately

---

### Option 2 — SSH into Your Remote Server

Since your site is on a remote host, you can run WP-CLI directly on the server via SSH.

**Step 1 — Get SSH access from your host**
- Log into your hosting dashboard (cPanel, SiteGround, Hostinger, etc.)
- Find: **Advanced > SSH Access** or **Security > SSH/Shell Access**
- Note your: Host, Username, Port (usually 22)

**Step 2 — Open a terminal on Windows**
Use any of these:
- **PowerShell** (built into Windows)
- **Windows Terminal** (from Microsoft Store — recommended)
- **Git Bash** (if you have Git installed)

**Step 3 — Connect via SSH**
```bash
ssh your-username@yoursite.com
```
Or with a custom port:
```bash
ssh -p 22 your-username@yoursite.com
```
Enter your password when prompted.

**Step 4 — Check if WP-CLI is available**
```bash
wp --info
```
Most managed hosts (SiteGround, WP Engine, Kinsta) have WP-CLI pre-installed. If not, ask your host or check their docs.

**Step 5 — Navigate to your WordPress folder**
```bash
cd public_html
wp plugin list
```

---

### Option 3 — VS Code Integrated Terminal via Remote SSH

This is the best workflow if you're already using VS Code to edit files on your server.

1. Install the **Remote - SSH** extension in VS Code
2. Connect to your server: `Ctrl+Shift+P` → `Remote-SSH: Connect to Host` → enter `username@yoursite.com`
3. Open your WordPress folder in VS Code
4. Open the terminal: `Ctrl+` `` ` ``
5. You're now in a terminal on your server — type WP-CLI commands directly

This means you can edit theme files AND run WP-CLI commands in the same VS Code window.

---

## Connecting WP-CLI to Claude

Claude can't run WP-CLI commands directly on your server. Instead, Claude **writes the commands for you** and you run them in your terminal.

### The Workflow

```
You describe what you want to do
         ↓
Claude writes the exact WP-CLI command
         ↓
You copy the command
         ↓
You run it in your SSH terminal or VS Code terminal
         ↓
WordPress does the task
```

### Example Prompts You Can Give Claude

| What you want | What to tell Claude |
|---|---|
| Move site to new domain | "Write a WP-CLI command to replace all instances of staging.mysite.com with mysite.com in the database" |
| Install required plugins | "Write WP-CLI commands to install and activate ACF Pro, Elementor, and WooCommerce" |
| Create test content | "Write a WP-CLI command to generate 10 dummy posts for the portfolio CPT" |
| Export the database before changes | "Give me the WP-CLI command to backup the database before I make changes" |
| Clean up old posts | "Write a WP-CLI command to delete all posts with the status 'draft' in the portfolio CPT" |

### Example Claude Session

> **You:** Write me a WP-CLI command to search and replace my old staging URL with my live URL. Old: `http://staging.mysite.com`, New: `https://mysite.com`

> **Claude:**
> ```bash
> # First, run a dry run to preview changes (nothing is changed yet)
> wp search-replace 'http://staging.mysite.com' 'https://mysite.com' --dry-run
>
> # If the preview looks correct, run it for real
> wp search-replace 'http://staging.mysite.com' 'https://mysite.com'
>
> # Flush the cache after
> wp cache flush
> ```

You copy each command, paste it into your SSH terminal, and run it.

---

### Best Setup for Using WP-CLI + Claude Together

```
VS Code
  ├── Remote-SSH connected to your server
  ├── File Explorer → editing theme files (Claude helps write PHP)
  └── Integrated Terminal → running WP-CLI commands (Claude writes them)
```

With this setup, you never leave VS Code. Ask Claude to write code or commands, apply them in the same window.

---

## All Essential Commands

All commands must be run from inside your WordPress root folder (where `wp-config.php` lives).

---

### Plugin Management

```bash
# List all installed plugins and their status
wp plugin list

# Install a plugin (slug from wordpress.org)
wp plugin install advanced-custom-fields

# Install AND activate in one step
wp plugin install elementor --activate

# Activate an already-installed plugin
wp plugin activate advanced-custom-fields

# Deactivate a plugin
wp plugin deactivate advanced-custom-fields

# Delete a plugin completely
wp plugin delete advanced-custom-fields

# Update a specific plugin
wp plugin update elementor

# Update ALL plugins at once
wp plugin update --all
```

---

### Theme Management

```bash
# List all installed themes
wp theme list

# Install a theme
wp theme install astra

# Install and activate
wp theme install astra --activate

# Activate an already-installed theme
wp theme activate astra

# Update a theme
wp theme update astra

# Update all themes
wp theme update --all

# Delete a theme
wp theme delete twentytwentyone
```

---

### User Management

```bash
# List all users
wp user list

# Create a new user
wp user create john john@example.com --role=editor --user_pass=password123

# Update a user's password
wp user update 1 --user_pass=newpassword

# Change a user's role
wp user update 2 --role=administrator

# Delete a user
wp user delete 2

# List user roles available
wp role list
```

---

### Database

```bash
# Export the full database to a .sql file
wp db export backup.sql

# Export to a gzipped file (smaller)
wp db export - | gzip > backup.sql.gz

# Import a .sql file
wp db import backup.sql

# Search and replace a string in the database
# (handles serialized data correctly — better than raw SQL)
wp search-replace 'http://old-domain.com' 'https://new-domain.com'

# Always dry-run first to preview what would change
wp search-replace 'http://old-domain.com' 'https://new-domain.com' --dry-run

# Repair the database
wp db repair

# Optimize the database (removes overhead from deleted rows)
wp db optimize
```

---

### Posts & Content

```bash
# List all published posts
wp post list

# List posts of a specific CPT
wp post list --post_type=portfolio

# List posts with a specific status
wp post list --post_status=draft

# Delete a single post (by ID)
wp post delete 42

# Delete permanently (skip trash)
wp post delete 42 --force

# Generate dummy posts for testing (great for testing layouts)
wp post generate --count=10 --post_type=portfolio

# Generate posts with specific content
wp post generate --count=5 --post_type=post --post_title="Test Post"
```

---

### Core WordPress

```bash
# Check the current WordPress version
wp core version

# Check if an update is available
wp core check-update

# Update WordPress core
wp core update

# Update WordPress core + database tables
wp core update && wp core update-db

# Download WordPress (for a fresh install)
wp core download
```

---

### Cache & Performance

```bash
# Flush the object cache
wp cache flush

# Regenerate all image thumbnail sizes
wp media regenerate --yes

# Regenerate thumbnails for a specific image (by attachment ID)
wp media regenerate 42

# Import images from URLs into the media library
wp media import https://example.com/image.jpg
```

---

### Options (Site Settings)

```bash
# Get a site option value
wp option get siteurl
wp option get blogname

# Update a site option
wp option update blogname "My New Site Name"

# Update the site URL (use search-replace for full migrations)
wp option update siteurl 'https://mysite.com'
wp option update home 'https://mysite.com'
```

---

### WP-CLI + ACF

```bash
# Export ACF field groups as JSON (useful before migrations)
wp acf export --output-file=acf-export.json

# Import ACF field groups from a JSON file
wp acf import --input=acf-export.json

# Generate dummy posts with ACF field data (via a custom PHP script)
wp eval-file seed-posts.php
```

---

## The `--dry-run` Flag

Many commands that change data accept `--dry-run`. This shows you exactly what *would* happen — without making any changes.

**Always use dry-run before:**
- `search-replace` — preview how many values will change
- Bulk deletes — preview which posts/users would be deleted

```bash
# Preview changes before running for real
wp search-replace 'staging.mysite.com' 'mysite.com' --dry-run

# If the output looks right, run without --dry-run
wp search-replace 'staging.mysite.com' 'mysite.com'
```

---

## Quick Reference Cheat Sheet

| Task | Command |
|---|---|
| Install + activate plugin | `wp plugin install SLUG --activate` |
| Update all plugins | `wp plugin update --all` |
| Backup database | `wp db export backup.sql` |
| Replace URL across site | `wp search-replace 'old.com' 'new.com'` |
| Preview replace (safe) | `wp search-replace 'old.com' 'new.com' --dry-run` |
| Generate test posts | `wp post generate --count=10 --post_type=portfolio` |
| Reset a password | `wp user update 1 --user_pass=newpass` |
| Flush cache | `wp cache flush` |
| Update WordPress | `wp core update` |
| Regenerate thumbnails | `wp media regenerate --yes` |
| Check WordPress version | `wp core version` |

---

*Last updated: June 2026*
