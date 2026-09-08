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
  'assets/fonts/Vazirmatn-Variable.woff2',
  'assets/fonts/OFL.txt',
  'assets/images/hero-strings.svg',
  'assets/js/navigation.js',
  'inc/patterns.php',
  'patterns/homepage-editorial.php',
];

for (const relative of requiredFiles) {
  const file = path.join(theme, relative);
  if (!fs.existsSync(file) || fs.statSync(file).size === 0) {
    throw new Error(`Missing or empty required file: ${relative}`);
  }
}

const style = fs.readFileSync(path.join(theme, 'style.css'), 'utf8');
if (!/^Version:\s*0\.4\.1$/m.test(style)) {
  throw new Error('Theme version must be 0.4.1 for this increment.');
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

if (/linear-gradient\s*\(/i.test(css)) {
  throw new Error('Increment 0.4.1 must not introduce CSS gradients.');
}

if (/(?:100vw|50vw)/i.test(css)) {
  throw new Error('Viewport-width breakout techniques are prohibited in Increment 0.4.1.');
}

if (/\.dzn-home\s+\.entry-content\s*\{[^}]*overflow\s*:\s*(?:clip|hidden)/is.test(css)) {
  throw new Error('Homepage overflow must not be concealed.');
}

if (!/@font-face[\s\S]*Vazirmatn-Variable\.woff2[\s\S]*font-display:\s*swap/i.test(css)) {
  throw new Error('Local Vazirmatn loading with font-display: swap is required.');
}

if (/https?:\/\/[^)'"]+\.(?:woff2?|ttf|otf)/i.test(css)) {
  throw new Error('External font dependencies are prohibited.');
}

if (/^\s*(?:input|select|textarea)(?:\s*,|\s*\{)/m.test(css)) {
  throw new Error('Form primitives must remain scoped to Theme-owned form roots.');
}

const homepagePattern = fs.readFileSync(path.join(theme, 'patterns/homepage-editorial.php'), 'utf8');
const requiredHomepageSections = [
  'dzn-home-hero',
  'dzn-facts',
  'dzn-home-why',
  'dzn-courses',
  'dzn-pricing',
  'dzn-process',
  'dzn-trust',
  'dzn-editorial',
  'dzn-faq',
  'dzn-final-cta',
];

for (const section of requiredHomepageSections) {
  if (!homepagePattern.includes(section)) {
    throw new Error(`Homepage pattern is missing section: ${section}`);
  }
}

if ((homepagePattern.match(/<h1\b/gi) ?? []).length !== 1) {
  throw new Error('Homepage pattern must contain exactly one H1.');
}

if (/carousel|slider|spopm_PM/i.test(homepagePattern)) {
  throw new Error('Homepage pattern must not inherit slider or legacy payment-shortcode presentation.');
}

if (/پرداخت پس از نخستین جلسهٔ آموزشی|۳ ماه/.test(homepagePattern)) {
  throw new Error('Homepage pattern retains obsolete commercial wording.');
}

for (const requiredCopy of [
  'هزینهٔ ترم پیش از آغاز ۱۲ جلسهٔ آموزشی پرداخت می‌شود',
  'جلسهٔ آموزشی ۱ از ۱۲',
  'یک ترم',
  '۱۲ جلسهٔ خصوصی',
]) {
  if (!homepagePattern.includes(requiredCopy)) {
    throw new Error(`Homepage pattern is missing canonical commercial copy: ${requiredCopy}`);
  }
}

if (/class="wp-block-group dzn-trust__art"\s+aria-hidden=/i.test(homepagePattern)) {
  throw new Error('Core Group output must not carry an unsupported aria-hidden attribute.');
}

if (!homepagePattern.includes('<!-- wp:group {"className":"dzn-section-heading__copy"')) {
  throw new Error('The editorial heading row must use a registered inner Group block, not raw wrapper markup.');
}

const artwork = fs.readFileSync(path.join(theme, 'assets/images/hero-strings.svg'), 'utf8');
const artworkColors = new Set([...artwork.matchAll(/#[0-9a-f]{6}/gi)].map(([color]) => color.toLowerCase()));
for (const color of artworkColors) {
  if (![...palette.values()].includes(color)) {
    throw new Error(`Hero artwork uses a colour outside the semantic palette: ${color}`);
  }
}

console.log('Static theme validation passed.');
