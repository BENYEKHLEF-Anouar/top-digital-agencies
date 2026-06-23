const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

async function run() {
  const args = process.argv.slice(2);
  const urlArgIndex = args.indexOf('--url');
  const outArgIndex = args.indexOf('--output');
  
  if (urlArgIndex === -1) {
    console.error('Error: Please specify target URL or HTML file path using --url');
    console.error('Usage: node html_to_figma.js --url <url_or_filepath> [--output <output_json_path>]');
    process.exit(1);
  }
  
  let targetUrl = args[urlArgIndex + 1];
  let outputPath = outArgIndex !== -1 ? args[outArgIndex + 1] : 'figma-import.json';
  
  // Resolve local files to file:// URLs
  if (!targetUrl.startsWith('http://') && !targetUrl.startsWith('https://')) {
    const absolutePath = path.resolve(targetUrl);
    if (!fs.existsSync(absolutePath)) {
      console.error(`Error: File not found at ${absolutePath}`);
      process.exit(1);
    }
    targetUrl = `file://${absolutePath}`;
  }
  
  console.log(`Launching Puppeteer browser...`);
  const browser = await puppeteer.launch({
    headless: 'new',
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });
  
  try {
    const page = await browser.newPage();
    console.log(`Navigating to ${targetUrl}...`);
    await page.goto(targetUrl, { waitUntil: 'networkidle2' });
    
    // Attempt to inject local or CDN package
    let libPath;
    try {
      libPath = require.resolve('@builder.io/html-to-figma/dist/browser.js');
    } catch (e) {
      try {
        libPath = path.resolve(__dirname, '../node_modules/@builder.io/html-to-figma/dist/browser.js');
      } catch (e2) {
        // Handled below
      }
    }
    
    if (libPath && fs.existsSync(libPath)) {
      console.log(`Injecting local html-to-figma bundle: ${libPath}`);
      await page.addScriptTag({ path: libPath });
    } else {
      console.log('Injecting CDN html-to-figma bundle...');
      await page.addScriptTag({ url: 'https://cdn.jsdelivr.net/npm/@builder.io/html-to-figma/dist/browser.js' });
    }
    
    console.log('Evaluating htmlToFigma in browser context...');
    const figmaData = await page.evaluate(async () => {
      // The library exposes window.htmlToFigma, but depending on the export format
      // it might be a function or an object with a .htmlToFigma function.
      let converter = window.htmlToFigma || window.builderIoHtmlToFigma;
      if (converter && typeof converter === 'object' && typeof converter.htmlToFigma === 'function') {
        converter = converter.htmlToFigma;
      }
      
      if (typeof converter !== 'function') {
        throw new Error('htmlToFigma function not found in browser context. Injected script did not define it globally.');
      }
      return converter(document.body);
    });
    
    fs.writeFileSync(outputPath, JSON.stringify(figmaData, null, 2), 'utf-8');
    console.log(`\nSuccess! Figma nodes exported to: ${path.resolve(outputPath)}`);
    console.log(`Import this JSON file in Figma using the 'HTML to Figma' plugin.\n`);
  } catch (err) {
    console.error('Error during conversion:', err);
    process.exit(1);
  } finally {
    await browser.close();
  }
}

run();
