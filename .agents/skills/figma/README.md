# Figma Integration & HTML to Figma Converter Skill

This directory contains a custom skill for your agent to connect with Figma and convert HTML web pages into editable Figma mockups.

## Directory Structure

*   `SKILL.md` — The main instruction document loaded by the AI agent context, describing its capabilities and how it integrates.
*   `scripts/html_to_figma.js` — The Puppeteer conversion utility script.
*   `package.json` — Declares Puppeteer and `@builder.io/html-to-figma` dependencies for the conversion script.

---

## Commands & Usage

### 1. Convert HTML to Figma JSON
To convert any HTML file (local path) or live website URL into a Figma-compatible JSON structure, run the converter script:

```bash
node scripts/html_to_figma.js --url <url_or_file_path> [--output <output_json_path>]
```

#### Examples:
*   Convert local file:
    ```bash
    node scripts/html_to_figma.js --url ./index.html --output figma-import.json
    ```
*   Convert live website:
    ```bash
    node scripts/html_to_figma.js --url https://example.com --output example-figma.json
    ```

### 2. Import JSON into Figma
1. Open **Figma**.
2. Open the Figma plugins search menu and find the **HTML to Figma** community plugin (by Builder.io).
3. Run the plugin.
4. Select the **Upload File / Drag & Drop** option.
5. Upload the generated `.json` file.
6. Figma will rebuild the HTML elements as fully editable Figma layers with auto layouts.

### 3. Connect Figma API (Optional)
If you want the agent to download SVG/PNG assets or sync styles from your Figma design files:
1. Generate a Personal Access Token (PAT) in your Figma Account Settings.
2. Store the token in a `.env` file at the root of your project:
   ```env
   FIGMA_PAT=fig_xxxxxx...
   ```
