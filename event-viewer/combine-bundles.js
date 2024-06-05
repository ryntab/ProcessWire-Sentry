import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// Required to get __dirname to work with ES Modules
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const outputDir = path.resolve(__dirname, 'dist');
const outputFile = path.resolve(outputDir, 'bundle.js');

// Read all .bundle.js files in the output directory
const files = fs.readdirSync(outputDir).filter(file => file.endsWith('.bundle.js'));

// Concatenate the content of all bundles into a single file
const combinedContent = files.map(file => fs.readFileSync(path.resolve(outputDir, file), 'utf-8')).join('\n');

// Write the combined content to the final bundle.js
fs.writeFileSync(outputFile, combinedContent, 'utf-8');

console.log(`Combined ${files.length} files into ${outputFile}`);
