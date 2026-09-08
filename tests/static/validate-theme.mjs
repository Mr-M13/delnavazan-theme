import fs from 'node:fs';
import path from 'node:path';

const root = path.resolve(import.meta.dirname, '../..');
const theme = path.join(root, 'theme');

const requiredFiles = [
  'style.css', 'theme.json', 'functions.php', 'header.php', 'footer.php',
  'front-page.php', 'page.php', 'single.php', 'index.php',
  'assets/css/theme.css', 'assets/css/editor.css',
  'assets/fonts/Vazirmatn-Variable.woff2', 'assets/fonts/OFL.txt',
  'assets/images/hero-strings.svg', 'assets/js/navigation.js',
  'assets/js/pricing-region.js', 'inc/patterns.php', 'inc/pricing.php',
  'patterns/homepage-editorial.php',
];

for (const relative of requiredFiles) {
  const file = path.join(theme, relative);
  if (!fs.existsSync(file) || fs.statSync(file).size === 0) throw new Error('Missing or empty required file: ' + relative);
}

const style = fs.readFileSync(path.join(theme, 'style.css'), 'utf8');
if (!/^Version:\s*0\.4\.2$/m.test(style)) throw new Error('Theme version must be 0.4.2 for this increment.');

const themeJson = JSON.parse(fs.readFileSync(path.join(theme, 'theme.json'), 'utf8'));
const palette = new Map(themeJson.settings.color.palette.map(({ slug, color }) => [slug, color.toLowerCase()]));
const roles = ['background', 'surface', 'surface-elevated', 'text-primary', 'text-secondary', 'text-muted', 'action-primary', 'action-primary-hover', 'action-secondary', 'border', 'accent', 'highlight', 'success', 'warning', 'error', 'information', 'focus', 'on-action'];
for (const role of roles) if (!palette.has(role)) throw new Error('Missing semantic palette role: ' + role);

const css = fs.readFileSync(path.join(theme, 'assets/css/theme.css'), 'utf8');
const cssTokens = new Map([...css.matchAll(/--dzn-color-([a-z-]+):\s*(#[0-9a-f]{3,8});/gi)].map(([, slug, color]) => [slug, color.toLowerCase()]));
for (const [slug, color] of palette) if (cssTokens.get(slug) !== color) throw new Error('Token mismatch for ' + slug);

const extensions = new Set(['.php', '.js', '.css']);
const forbidden = /amelia|delnavazan-platform|booking-requests|\$wpdb|register_rest_route|stripe|payment_intent/i;
function walk(directory) {
  return fs.readdirSync(directory, { withFileTypes: true }).flatMap((entry) => {
    const file = path.join(directory, entry.name);
    return entry.isDirectory() ? walk(file) : [file];
  });
}
for (const file of walk(theme)) {
  if (!extensions.has(path.extname(file))) continue;
  if (forbidden.test(fs.readFileSync(file, 'utf8'))) throw new Error('Forbidden business/domain coupling in ' + path.relative(root, file));
}

let braces = 0;
for (const character of css.replace(/\/\*[\s\S]*?\*\//g, '')) {
  if (character === '{') braces += 1;
  if (character === '}') braces -= 1;
  if (braces < 0) throw new Error('CSS closes a block before it opens.');
}
if (braces !== 0) throw new Error('CSS brace imbalance: ' + braces);
if (/linear-gradient\s*\(/i.test(css)) throw new Error('Theme must not introduce gradients.');
if (/(?:100vw|50vw)/i.test(css)) throw new Error('Viewport-width breakout techniques are prohibited.');
if (/\.dzn-home\s+\.entry-content\s*\{[^}]*overflow\s*:\s*(?:clip|hidden)/is.test(css)) throw new Error('Homepage overflow must not be concealed.');
if (!/@font-face[\s\S]*Vazirmatn-Variable\.woff2[\s\S]*font-display:\s*swap/i.test(css)) throw new Error('Local Vazirmatn loading with font-display: swap is required.');
if (/https?:\/\/[^)'"]+\.(?:woff2?|ttf|otf)/i.test(css)) throw new Error('External font dependencies are prohibited.');
if (/^\s*(?:input|select|textarea)(?:\s*,|\s*\{)/m.test(css)) throw new Error('Form primitives must remain scoped to Theme-owned form roots.');

const pattern = fs.readFileSync(path.join(theme, 'patterns/homepage-editorial.php'), 'utf8');
const sections = ['dzn-home-hero', 'dzn-facts', 'dzn-courses', 'dzn-process', 'dzn-pricing', 'dzn-faq', 'dzn-contact', 'dzn-editorial'];
for (const section of sections) if (!pattern.includes(section)) throw new Error('Homepage pattern is missing section: ' + section);
if (pattern.includes('dzn-trust') || pattern.includes('dzn-course-index') || pattern.includes('wp:post-date')) throw new Error('Homepage retains removed trust, course-index, or article-date presentation.');
const positions = sections.map((section) => pattern.indexOf(section));
for (let index = 1; index < positions.length; index += 1) if (positions[index] <= positions[index - 1]) throw new Error('Homepage narrative order is invalid.');
if ((pattern.match(/<h1\b/gi) ?? []).length !== 1) throw new Error('Homepage pattern must contain exactly one H1.');
if (/carousel|slider|spopm_PM/i.test(pattern)) throw new Error('Homepage pattern must not inherit slider or legacy payment-shortcode presentation.');
if (/پرداخت پس از نخستین جلسهٔ آموزشی|۳ ماه/.test(pattern)) throw new Error('Homepage pattern retains obsolete commercial wording.');
for (const copy of ['هزینهٔ ترم پیش از آغاز ۱۲ جلسهٔ آموزشی پرداخت می‌شود', 'جلسهٔ آموزشی ۱ از ۱۲', 'یک ترم', '۱۲ جلسهٔ خصوصی']) if (!pattern.includes(copy)) throw new Error('Homepage is missing canonical commercial copy: ' + copy);
for (const faq of ['کلاس‌ها برای چه کشورهایی برگزار می‌شود؟', 'اگر هنوز ساز ندارم چه کنم؟', 'آیا می‌توانم از سطح کاملاً مبتدی شروع کنم؟', 'اگر زمان یک جلسه مناسب نباشد چه می‌شود؟', 'هزینهٔ ترم چه زمانی پرداخت می‌شود؟', 'آیا داخل ایران هم می‌توان ثبت‌نام کرد؟']) if (!pattern.includes(faq)) throw new Error('Approved FAQ is missing: ' + faq);
if (!pattern.includes('0413 413 004') || !pattern.includes('delnavazan@mail.com') || !pattern.includes('@insta.delnavazan')) throw new Error('Homepage contact routes are incomplete.');
if (pattern.includes('+61 431 364 200')) throw new Error('Notification number must not appear on the homepage.');

const header = fs.readFileSync(path.join(theme, 'header.php'), 'utf8');
if (!header.includes('<svg class="menu-toggle__icon"') || (header.match(/menu-toggle__line--/g) ?? []).length !== 3) throw new Error('Header must use the controlled three-stroke SVG hamburger.');
if (header.includes('site-branding__description')) throw new Error('Header must not render a redundant tagline.');
const footer = fs.readFileSync(path.join(theme, 'footer.php'), 'utf8');
if (!footer.includes('has_custom_logo()') || !footer.includes('0413 413 004') || footer.includes('+61 431 364 200')) throw new Error('Footer logo/contact contract failed.');

const pricing = fs.readFileSync(path.join(theme, 'inc/pricing.php'), 'utf8');
for (const price of ['A$250', 'NZ$250', 'US$250', 'C$250', '€150', '£150']) if (!pricing.includes(price)) throw new Error('Missing active regional price: ' + price);
for (const code of ["'AU' => 'AU'", "'NZ' => 'NZ'", "'US' => 'US'", "'CA' => 'CA'", "'GB' => 'GB'", "'DE' => 'EU'", "'LT' => 'EU'"]) if (!pricing.includes(code)) throw new Error('Missing country mapping: ' + code);
if (/AED|KWD|TRY/.test(pricing)) throw new Error('Inactive pricing regions must not be exposed.');
const pricingJs = fs.readFileSync(path.join(theme, 'assets/js/pricing-region.js'), 'utf8');
if (!pricing.includes('ipwho.is')) throw new Error('Pricing presentation config must declare the isolated suggestion endpoint.');
for (const required of ['localStorage', "credentials: 'omit'", 'countryToRegion', 'manuallySelected', 'showNeutral']) if (!pricingJs.includes(required)) throw new Error('Pricing UI is missing: ' + required);
if (/default(?:ed)?\s*(?:to|=)\s*['"]?US/i.test(pricingJs)) throw new Error('Unsupported location must not silently default to United States.');

console.log('Static theme validation passed.');
