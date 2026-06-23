---
name: figma
description: "Connect to Figma, fetch design tokens/assets, or export local/remote HTML websites into exact Figma mockups using Puppeteer and @builder.io/html-to-figma."
version: 1.0.0
---

# Figma Integration & Conversion Skill

A skill for connecting your agent to your Figma account, fetching styles and assets from Figma, and converting your HTML/CSS websites into exact-match Figma mockups.

## Capabilities

1. **Website to Figma Mockup (`figma to-figma`)**: Converts a local HTML page or live URL into a Figma layers JSON structure. You can import this JSON file into Figma using the standard **HTML to Figma** community plugin to generate an exact-match Figma mockup.
2. **Figma Account Connection (`figma connect`)**: Stores your Figma Personal Access Token (PAT) securely in a `.env` file (`FIGMA_PAT`) so the agent can fetch pages, frames, components, and assets.
3. **Asset Downloader (`figma download-assets`)**: Downloads SVG/PNG assets from specific frames or nodes in your Figma file directly into your local codebase.
4. **Style Sync (`figma sync-styles`)**: Exports Figma variables, typography, and color styles as CSS custom properties/design tokens.

---

## Commands

| Command | Description |
| --- | --- |
| `figma connect` | Prompts you to input your Figma Personal Access Token and saves it to `.env`. |
| `figma to-figma <path_or_url> [--output <json_file>]` | Converts an HTML website (local or remote) to Figma JSON nodes. |
| `figma sync-styles <figma_file_key>` | Fetches variables, typography, and colors from a Figma file and saves them to `tokens.css` or `styles.css`. |
| `figma download-assets <figma_file_key> --nodes <node_ids>` | Downloads specific assets (SVGs/PNGs) from Figma into your project directory. |

---

## Detailed Guides

### 1. Website to Figma Mockup (`figma to-figma`)

This command uses a Puppeteer-based scraper to render your webpage (handling all layouts, flexbox, grid, and fonts) and translates the active DOM elements into Figma JSON nodes.

**Setup**:
The conversion script is located inside this skill's scripts folder:
[`scripts/html_to_figma.js`](file:///c:/Users/anoua/OneDrive/Desktop/top-digital-agencies/.agents/skills/figma/scripts/html_to_figma.js).
It requires `puppeteer` and `@builder.io/html-to-figma` to be installed inside the skill's directory.

**Execution Flow**:
1. Run the script with Node.js:
   ```bash
   node .agents/skills/figma/scripts/html_to_figma.js --url ./index.html --output figma-import.json
   ```
2. The script outputs a `figma-import.json` file in the workspace root.
3. Open Figma, go to Plugins -> Search for **HTML to Figma** (by Builder.io).
4. Run the plugin, select **Upload File / Drag & Drop**, and choose `figma-import.json`.
5. Figma will reconstruct your web page as editable Auto Layout frames, exact to the original design!

### 2. Figma Account Connection

To use API-dependent commands like fetching styles or downloading assets:
1. Go to your Figma Account Settings.
2. Under **Personal access tokens**, click **Create a new personal access token**.
3. Copy the token.
4. Run `figma connect` or add `FIGMA_PAT=your_token` to your local `.env` file:
   ```env
   FIGMA_PAT=fig_xxxxxx...
   ```

### 3. Fetching Figma Variables & Styles (`figma sync-styles`)

When you run `figma sync-styles <figma_file_key>`, the agent makes a request to the Figma API:
`https://api.figma.com/v1/files/<file_key>/variables/local` and `https://api.figma.com/v1/files/<file_key>`
to extract:
- **Color Variables**: Converts color variables (e.g., primary, background, surface) to CSS custom variables (e.g., `--color-primary`, `--color-bg`).
- **Typography Styles**: Generates a standard font token system.

It then writes these tokens to your `tokens.css` or wires them directly into your existing CSS.

### 4. Downloading Assets (`figma download-assets`)

Downloads design assets (icons, illustrations, high-res images) from Figma into your project.
Usage:
```bash
node .agents/skills/figma/scripts/download_assets.js --file <file_key> --nodes <node_id_1>,<node_id_2> --output ./assets/
```
The agent handles querying the Figma image render endpoint (`https://api.figma.com/v1/images/<file_key>`) and downloading the rendered images/SVGs to your project directory.
