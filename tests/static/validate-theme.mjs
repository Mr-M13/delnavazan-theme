import fs from 'node:fs';
import path from 'node:path';
import process from 'node:process';

const root = path.resolve(import.meta.dirname, '../..');
const theme = path.join(root, 'theme');

const requiredFiles = [
  'style.css',
  'theme.json',
  'functions.php',
  'header.php',
  'footer.php',
  'front-page.php',
  'page.php',
  'single.php',
  'index.php',
  'assets/css/theme.css',
  'assets/css/editor.css',
  'assets/js/navigation.js',
];

for (const relative of requiredFiles) {
  const file = path.join(theme, relative);
  if (!fs.existsSync(file) || fs.statSync(file).size === 0) {
    throw new Error(`Missing or empty required file: ${relative}`);
  }
}

const style = fs.readFileSync(path.join(theme, 'style.css'), 'utf8');
if (!/^Version:\s*0\.2\.0$/m.test(style)) {
  throw new Error('Theme version must remain 0.2.0 for this foundation.');
}

const themeJson = JSON.parse(fs.readFileSync(path.join(theme, 'theme.json'), 'utf8'));
const palette = new Map(themeJson.settings.color.palette.map(({ slug, color }) => [slug, color.toLowerCase()]));
const expectedRoles = [
  'background', 'surface', 'surface-elevated', 'text-primary', 'text-secondary',
  'text-muted', 'action-primary', 'action-primary-hover', 'action-secondary',
  'border', 'accent', 'highlight', 'success', 'warning', 'error',
  'information', 'focus', 'on-action',
];

for (const role of expectedRoles) {
  if (!palette.has(role)) {
    throw new Error(`Missing semantic palette role: ${role}`);
  }
}

const css = fs.readFileSync(path.join(theme, 'assets/css/theme.css'), 'utf8');
const cssTokens = new Map(
  [...css.matchAll(/--dzn-color-([a-z-]+):\s*(#[0-9a-f]{3,8});/gi)]
    .map(([, slug, color]) => [slug, color.toLowerCase()]),
);

for (const [slug, color] of palette) {
  if (cssTokens.get(slug) !== color) {
    throw new Error(`Token mismatch for ${slug}: theme.json=${color}, CSS=${cssTokens.get(slug) ?? 'missing'}`);
  }
}

const runtimeExtensions = new Set(['.php', '.js', '.css']);
const forbidden = /amelia|delnavazan-platform|booking-requests|\$wpdb|wp_remote_(?:get|post)|register_rest_route/i;

function walk(directory) {
  return fs.readdirSync(directory, { withFileTypes: true }).flatMap((entry) => {
    const file = path.join(directory, entry.name);
    return entry.isDirectory() ? walk(file) : [file];
  });
}

for (const file of walk(theme)) {
  if (!runtimeExtensions.has(path.extname(file))) continue;
  const content = fs.readFileSync(file, 'utf8');
  if (forbidden.test(content)) {
    throw new Error(`Forbidden business/domain coupling in ${path.relative(root, file)}`);
  }
}

let braces = 0;
for (const character of css.replace(/\/\*[\s\S]*?\*\//g, '')) {
  if (character === '{') braces += 1;
  if (character === '}') braces -= 1;
  if (braces < 0) throw new Error('CSS closes a block before it opens.');
}
if (braces !== 0) throw new Error(`CSS brace imbalance: ${braces}`);

console.log('Static theme validation passed.');
