import fs from 'node:fs';
import path from 'node:path';
import { createHash } from 'node:crypto';

const root = path.resolve(import.meta.dirname, '../..');
const theme = path.join(root, 'theme');

const requiredFiles = [
  'style.css', 'theme.json', 'functions.php', 'header.php', 'footer.php',
  'front-page.php', 'page.php', 'single.php', 'index.php',
  'assets/css/theme.css', 'assets/css/editor.css',
  'assets/fonts/Vazirmatn-Variable.woff2', 'assets/fonts/OFL.txt',
  'assets/js/navigation.js',
  'assets/js/pricing-region.js', 'inc/patterns.php', 'inc/pricing.php',
  'patterns/homepage-editorial.php',
  'assets/images/home-hero.png',
  'assets/images/instrument-tar.webp', 'assets/images/instrument-setar.webp',
  'assets/images/instrument-santur.webp', 'assets/images/instrument-kamancheh.webp',
  'assets/images/instrument-tombak.webp', 'assets/images/instrument-piano.webp',
  'assets/images/instrument-daf.webp', 'assets/images/contact-email.webp',
  'assets/images/contact-instagram.webp',
];

for (const relative of requiredFiles) {
  const file = path.join(theme, relative);
  if (!fs.existsSync(file) || fs.statSync(file).size === 0) throw new Error('Missing or empty required file: ' + relative);
}

const style = fs.readFileSync(path.join(theme, 'style.css'), 'utf8');
if (!/^Version:\s*0\.4\.6$/m.test(style)) throw new Error('Theme version must be 0.4.6 for this increment.');

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
const gradients = css.match(/linear-gradient\s*\(/gi) ?? [];
if (gradients.length !== 2 || /(?:radial|repeating)-(?:linear-)?gradient\s*\(/i.test(css)) throw new Error('Only the two restrained hero ivory fades are permitted.');
if (/(?:100vw|50vw)/i.test(css)) throw new Error('Viewport-width breakout techniques are prohibited.');
if (/\.dzn-home\s+\.entry-content\s*\{[^}]*overflow\s*:\s*(?:clip|hidden)/is.test(css)) throw new Error('Homepage overflow must not be concealed.');
if (!/@font-face[\s\S]*Vazirmatn-Variable\.woff2[\s\S]*font-display:\s*swap/i.test(css)) throw new Error('Local Vazirmatn loading with font-display: swap is required.');
if (/https?:\/\/[^)'"]+\.(?:woff2?|ttf|otf)/i.test(css)) throw new Error('External font dependencies are prohibited.');
if (/^\s*(?:input|select|textarea)(?:\s*,|\s*\{)/m.test(css)) throw new Error('Form primitives must remain scoped to Theme-owned form roots.');

const pattern = fs.readFileSync(path.join(theme, 'patterns/homepage-editorial.php'), 'utf8');
const sections = ['dzn-home-hero', 'dzn-facts', 'dzn-courses', 'dzn-process', 'dzn-pricing', 'dzn-hamnavaz', 'dzn-faq', 'dzn-editorial'];
for (const section of sections) if (!pattern.includes(section)) throw new Error('Homepage pattern is missing section: ' + section);
if (pattern.includes('dzn-trust') || pattern.includes('dzn-course-index') || pattern.includes('dzn-contact') || pattern.includes('wp:post-date')) throw new Error('Homepage retains removed trust, course-index, contact, or article-date presentation.');
if (pattern.includes('hero-strings.svg')) throw new Error('Homepage must not use the Delnavazan logo as hero artwork.');
for (const slot of ['dzn-owned-media-slot--hero', 'dzn-instrument-tile__media', 'dzn-owned-media-slot--hamnavaz']) if (!pattern.includes(slot)) throw new Error('Homepage is missing replaceable media architecture: ' + slot);
if (/class="[^"]*dzn-owned-media-slot[^"]*"[^>]*aria-hidden/i.test(pattern)) throw new Error('Reusable media containers must not hide future meaningful media from assistive technology.');
if (css.includes('.dzn-trust')) throw new Error('Obsolete standalone trust-section CSS must be removed.');
const positions = sections.map((section) => pattern.indexOf(section));
for (let index = 1; index < positions.length; index += 1) if (positions[index] <= positions[index - 1]) throw new Error('Homepage narrative order is invalid.');
if ((pattern.match(/<h1\b/gi) ?? []).length !== 1) throw new Error('Homepage pattern must contain exactly one H1.');
if (/carousel|slider|spopm_PM/i.test(pattern)) throw new Error('Homepage pattern must not inherit slider or legacy payment-shortcode presentation.');
if (/پرداخت پس از نخستین جلسهٔ آموزشی|۳ ماه|سه ماه|3 months/i.test(pattern)) throw new Error('Homepage pattern retains obsolete commercial wording.');
for (const copy of ['هر ترم پرداخت‌شده: ۱۲ جلسهٔ خصوصی، هفته‌ای یک جلسهٔ ۳۰ دقیقه‌ای. جلسهٔ معارفه رایگان و جدا از ترم است.', 'هزینهٔ ترم پیش از آغاز ۱۲ جلسهٔ آموزشی پرداخت می‌شود', 'آغاز ترم پرداخت‌شدهٔ ۱۲ جلسه‌ای']) if (!pattern.includes(copy)) throw new Error('Homepage is missing canonical commercial copy: ' + copy);
const processSection = pattern.slice(pattern.indexOf('dzn-process'), pattern.indexOf('dzn-pricing'));
if ((processSection.match(/<li>/g) ?? []).length !== 4) throw new Error('How It Works must contain exactly four conceptual steps.');
for (const step of ['ثبت‌نام / درخواست', 'جلسهٔ معارفهٔ رایگان', 'تصمیم برای ادامه و پرداخت هزینهٔ ترم', 'آغاز ترم پرداخت‌شدهٔ ۱۲ جلسه‌ای']) if (!processSection.includes(step)) throw new Error('How It Works is missing: ' + step);
if (pattern.includes('dzn-price-ledger__row') || pattern.includes('dzn-price-ledger__note')) throw new Error('Pricing must not repeat commercial mechanics.');
const folioSection = pattern.slice(pattern.indexOf('dzn-instrument-folio'), pattern.indexOf('dzn-process'));
const instruments = new Set([...folioSection.matchAll(/dzn-instrument-tile--([a-z]+)/g)].map(([, instrument]) => instrument));
const requiredInstruments = ['tar', 'setar', 'santur', 'kamancheh', 'tombak', 'piano', 'daf'];
if (instruments.size !== requiredInstruments.length || requiredInstruments.some((instrument) => !instruments.has(instrument))) throw new Error('Instrument folio must contain exactly the approved seven instruments.');
const folioLinks = [...folioSection.matchAll(/<a\b[^>]*href="([^"]+)"/gi)].map(([, href]) => href);
if (folioLinks.length !== 1 || !folioLinks[0].includes("home_url( '/enrol/' )") || /[?&](?:instrument|course|service)=/i.test(folioLinks[0])) throw new Error('Folio may expose only the verified general enrolment route, without speculative preselection.');
if (/dzn-instrument-tile[^<]*<a\b/is.test(folioSection)) throw new Error('Specific instrument tiles must remain non-links until a verified preselection contract exists.');
const hamnavazSection = pattern.slice(pattern.indexOf('dzn-hamnavaz'), pattern.indexOf('dzn-faq'));
if (!hamnavazSection.includes('<h2') || /<(?:a|button)\b/i.test(hamnavazSection)) throw new Error('Hamnavaz must be structurally present, claim-neutral, and secondary to enrolment.');
const faqSection = pattern.slice(pattern.indexOf('dzn-faq'), pattern.indexOf('dzn-editorial'));
if ((faqSection.match(/<details\b/g) ?? []).length !== 6) throw new Error('Homepage FAQ must contain exactly six native disclosures.');
for (const faq of ['کلاس‌ها برای چه کشورهایی برگزار می‌شود؟', 'اگر هنوز ساز ندارم چه کنم؟', 'آیا می‌توانم از سطح کاملاً مبتدی شروع کنم؟', 'اگر زمان یک جلسه مناسب نباشد چه می‌شود؟', 'هزینهٔ ترم چه زمانی پرداخت می‌شود؟', 'آیا داخل ایران هم می‌توان ثبت‌نام کرد؟']) if (!pattern.includes(faq)) throw new Error('Approved FAQ is missing: ' + faq);
const publicTextExtensions = new Set(['.php', '.css', '.js', '.json', '.svg', '.txt', '.md']);
const notificationNumber = /(?:\+?61[\s().-]*431[\s.-]*364[\s.-]*200|0431[\s.-]*364[\s.-]*200)/;
const threeMonths = /(?:3\s*months|۳[\s\u200c]*ماه|سه[\s\u200c]+ماه)/iu;
for (const file of walk(theme)) {
  if (!publicTextExtensions.has(path.extname(file))) continue;
  const source = fs.readFileSync(file, 'utf8');
  if (notificationNumber.test(source)) throw new Error('Notification number must not appear in public Theme source: ' + path.relative(root, file));
  if (threeMonths.test(source)) throw new Error('Theme must not present a three-month canonical term: ' + path.relative(root, file));
  if (source.includes('hero-strings.svg')) throw new Error('Retired hero artwork must be wholly unreferenced: ' + path.relative(root, file));
}

const blockMarkers = pattern.match(/<!--\s*\/?wp:/g) ?? [];
const blockPattern = /<!--\s*(\/?)wp:([a-z0-9-]+(?:\/[a-z0-9-]+)?)([\s\S]*?)-->/gi;
const stack = [];
let parsedMarkers = 0;
for (const match of pattern.matchAll(blockPattern)) {
  parsedMarkers += 1;
  const [, closing, name, payload] = match;
  const trimmed = payload.trim();
  const selfClosing = !closing && trimmed.endsWith('/');
  const attributes = selfClosing ? trimmed.slice(0, -1).trim() : trimmed;
  if (attributes) JSON.parse(attributes);
  if (closing) {
    const opened = stack.pop();
    if (opened !== name) throw new Error(`Gutenberg block nesting mismatch: expected ${opened ?? 'none'}, closed ${name}.`);
  } else if (!selfClosing) {
    stack.push(name);
  }
}
if (parsedMarkers !== blockMarkers.length || stack.length !== 0) throw new Error('Homepage Gutenberg block comments are incomplete or unbalanced.');

const header = fs.readFileSync(path.join(theme, 'header.php'), 'utf8');
if (!header.includes('<svg class="menu-toggle__icon"') || (header.match(/menu-toggle__line--/g) ?? []).length !== 3) throw new Error('Header must use the controlled three-stroke SVG hamburger.');
if (header.includes('site-branding__description')) throw new Error('Header must not render a redundant tagline.');
if (!header.includes('has_custom_logo()')) throw new Error('Header must retain Custom Logo as the primary identity.');
const setup = fs.readFileSync(path.join(theme, 'inc/setup.php'), 'utf8');
if (!setup.includes('dzn-nav-anchor') || !setup.includes('dzn-nav-page')) throw new Error('Primary menu must distinguish same-page anchors from page links.');
for (const selector of ['a.dzn-nav-anchor', 'a.dzn-nav-page:not(.dzn-nav-action)', 'a:focus-visible', 'a.dzn-nav-action']) if (!css.includes(selector)) throw new Error('Navigation state treatment is incomplete: ' + selector);
if (!/\.current-menu-item\s*>\s*a\.dzn-nav-anchor:not\(\.dzn-nav-action\)\s*\{[^}]*background:\s*transparent/is.test(css)) throw new Error('Same-page anchors must not inherit the current-page background.');
if (!css.includes('clamp(1.9rem, 1.72rem + 1.8vw, 4.35rem)')) throw new Error('Homepage H1 reduction is missing.');
for (const architecture of [
  '.dzn-home-hero__media {',
  'order: 1;',
  'grid-template-columns: minmax(0, 44fr) minmax(0, 56fr)',
  'grid-template-columns: repeat(2, minmax(0, 1fr))',
  'grid-template-columns: repeat(4, minmax(0, 1fr))',
  '@media (max-width: 23.375rem)',
  '@media (max-width: 26.875rem)',
  '@media (max-width: 20rem)',
  '@media (prefers-reduced-motion: reduce)',
  'scroll-behavior: auto',
  '.current-menu-item > a.dzn-nav-anchor:not(.dzn-nav-action)',
  '.site-footer :where(a, a:visited)',
]) if (!css.includes(architecture)) throw new Error('Responsive/accessibility architecture is missing: ' + architecture);
const footer = fs.readFileSync(path.join(theme, 'footer.php'), 'utf8');
if (!footer.includes('has_custom_logo()') || !footer.includes('0413 413 004') || !footer.includes('contact-email.webp') || !footer.includes('contact-instagram.webp') || footer.includes('contact-whatsapp') || footer.includes('+61 431 364 200')) throw new Error('Footer logo/contact contract failed.');
const frontPage = fs.readFileSync(path.join(theme, 'front-page.php'), 'utf8');
if (!frontPage.includes('the_content()') || frontPage.indexOf('the_content()') > frontPage.indexOf('get_footer()')) throw new Error('Front page must preserve authored Gutenberg content before the Theme footer.');
if (!setup.includes(`lang="fa-IR" dir="rtl"`)) throw new Error('Public Persian language and RTL semantics are missing.');
if (!footer.includes('<bdi dir="ltr">0413 413 004</bdi>') || !footer.includes('<bdi dir="ltr">delnavazan@mail.com</bdi>')) throw new Error('Public LTR contact fragments must remain isolated.');

const pricing = fs.readFileSync(path.join(theme, 'inc/pricing.php'), 'utf8');
for (const price of ['A$250', 'NZ$250', 'US$250', 'C$250', '€150', '£150']) if (!pricing.includes(price)) throw new Error('Missing active regional price: ' + price);
for (const price of ['۲۵۰ دلار استرالیا', '۲۵۰ دلار نیوزیلند', '۲۵۰ دلار آمریکا', '۲۵۰ دلار کانادا', '۱۵۰ یورو', '۱۵۰ پوند بریتانیا']) if (!pricing.includes(price)) throw new Error('Missing Persian regional price presentation: ' + price);
for (const code of ["'AU' => 'AU'", "'NZ' => 'NZ'", "'US' => 'US'", "'CA' => 'CA'", "'GB' => 'GB'", "'DE' => 'EU'", "'LT' => 'EU'"]) if (!pricing.includes(code)) throw new Error('Missing country mapping: ' + code);
if (/AED|KWD|TRY/.test(pricing)) throw new Error('Inactive pricing regions must not be exposed.');
const pricingJs = fs.readFileSync(path.join(theme, 'assets/js/pricing-region.js'), 'utf8');
const sha256 = (source) => createHash('sha256').update(source).digest('hex');
if (!pricingJs.includes('selected.displayPersian || selected.display') || !pricingJs.includes("region.textContent = 'برای یک ترم'")) throw new Error('Persian pricing hierarchy is missing.');
if (!pricing.includes('ipwho.is')) throw new Error('Pricing presentation config must declare the isolated suggestion endpoint.');
for (const required of ['localStorage', "credentials: 'omit'", 'countryToRegion', 'manuallySelected', 'showNeutral']) if (!pricingJs.includes(required)) throw new Error('Pricing UI is missing: ' + required);
if (/default(?:ed)?\s*(?:to|=)\s*['"]?US/i.test(pricingJs)) throw new Error('Unsupported location must not silently default to United States.');

const approvedAssetHashes = new Map([
  ['assets/images/home-hero.png', '02f766c201b0be520b3ec1197030619a55c68b0f1371d464dfe8175504c9f627'],
  ['assets/images/instrument-tar.webp', '19d5ce41acac7b2b37c162c2c52517c270e84abb7f1b068dcd48cc92cceb53c5'],
  ['assets/images/instrument-setar.webp', 'a194aa0a03f41f209acf42010add098c21cacd8d8ecb0ad84aa613cd9745cec1'],
  ['assets/images/instrument-santur.webp', '5e1f7014b60a18f0f3ff56b9cc575cffbf750fd4f9df77ee95eec4c37df7c33b'],
  ['assets/images/instrument-kamancheh.webp', '30b4fb6f702718c50a94bc2321c01ba6659b793aba2ec37fe23dc17c76554e56'],
  ['assets/images/instrument-tombak.webp', 'ec9e5804a720c56e31876e913daacd2e771771bcfb6630b46c6f45c336898945'],
  ['assets/images/instrument-piano.webp', '5ab960f10721fb05247ba7d906ea164a19eb2654107104a2c6fbcc32ee07b6c9'],
  ['assets/images/instrument-daf.webp', '43ebe826e21d3d3135d91ab82c05634c01f0e850e055c2ddf435658b6d510bfa'],
  ['assets/images/contact-email.webp', '180ee80b596af55c68fc0ea57f5c7537ed2d6260e31595ae41e9cff9b3032f97'],
  ['assets/images/contact-instagram.webp', 'a8d7d04800c2ecb318ea82f323412c3bac66ec7bb501087c3bb43bf77820c3ce'],
]);
for (const [relative, expected] of approvedAssetHashes) {
  const actual = sha256(fs.readFileSync(path.join(theme, relative)));
  if (actual !== expected) throw new Error('Approved media identity mismatch: ' + relative);
}

console.log('Static theme validation passed.');
