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
  'assets/css/portal.css',
  'assets/css/editor.css',
  'assets/js/navigation.js',
  'assets/js/portal.js',
  'inc/portal.php',
  'page-templates/student-portal-home.php',
  'page-templates/student-portal-account.php',
  'page-templates/student-portal-preview.php',
];

for (const relative of requiredFiles) {
  const file = path.join(theme, relative);
  if (!fs.existsSync(file) || fs.statSync(file).size === 0) {
    throw new Error(`Missing or empty required file: ${relative}`);
  }
}

const style = fs.readFileSync(path.join(theme, 'style.css'), 'utf8');
if (!/^Version:\s*0\.5\.0$/m.test(style)) {
	throw new Error('Student Portal V1 candidate must identify as Theme 0.5.0.');
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

const portalRuntime = fs.readFileSync(path.join(theme, 'inc/portal.php'), 'utf8');
const portalShell = fs.readFileSync(path.join(theme, 'template-parts/portal/shell.php'), 'utf8');
const portalPreview = fs.readFileSync(path.join(theme, 'page-templates/student-portal-preview.php'), 'utf8');
const lessonState = fs.readFileSync(path.join(theme, 'template-parts/portal/lesson-state.php'), 'utf8');
const portalJs = fs.readFileSync(path.join(theme, 'assets/js/portal.js'), 'utf8');

for (const liveDestination of [/wa\.me/i, /instagram\.com/i, /delnavazan@mail/i, /61413413004/]) {
  if (liveDestination.test(portalRuntime)) {
    throw new Error(`Synthetic Portal fixture contains a live contact destination: ${liveDestination}`);
  }
}
for (const syntheticDestination of [
  'https://contact.example.invalid/whatsapp',
  'mailto:student-portal@example.invalid',
  'https://social.example.invalid/instagram',
]) {
  if (!portalRuntime.includes(syntheticDestination)) {
    throw new Error(`Synthetic Portal fixture destination is not allowlisted: ${syntheticDestination}`);
  }
}

if (!portalRuntime.includes("'production' !== wp_get_environment_type()")
  || !portalRuntime.includes("current_user_can( 'edit_theme_options' )")) {
  throw new Error('Synthetic Portal fixtures must remain admin-only and unavailable in production.');
}

if (!portalRuntime.includes("apply_filters(\n\t\t'dzn_theme_student_portal_view_model'")) {
  throw new Error('Portal view-model integration seam is missing.');
}

if (!portalRuntime.includes('if ( ! dzn_theme_is_portal_template() )')) {
  throw new Error('Portal assets must remain isolated from public Theme pages.');
}

if (!portalRuntime.includes("array( 'home', 'account' )") || !portalShell.includes("'account' === $screen")) {
  throw new Error('Portal shell must preserve the locked Home + Account architecture.');
}

if (!portalPreview.includes('dzn_theme_student_portal_preview_allowed()')) {
  throw new Error('Portal preview template must enforce the synthetic-fixture gate.');
}

for (const state of ['upcoming', 'starting_soon', 'absence_notified', 'time_changed', 'academy_cancelled', 'awaiting_reschedule', 'none']) {
  if (!lessonState.includes(`'${state}'`)) {
    throw new Error(`Missing Upcoming Lesson presentation state: ${state}`);
  }
}

if (!portalJs.includes("dznDialogFallback = 'disclosure'")
  || portalJs.includes("setAttribute('aria-modal'")) {
  throw new Error('Dialog fallback must be an explicit non-modal disclosure.');
}

for (const stateAxis of ['lifecycle_state', 'schedule_state', 'attendance_state', 'entitlement_state']) {
  if (!portalRuntime.includes(`'${stateAxis}'`)) {
    throw new Error(`Portal fixture collapses required Lesson state axis: ${stateAxis}`);
  }
}

if (/fetch\s*\(|XMLHttpRequest|\.submit\s*\(/.test(portalJs)) {
  throw new Error('Portal presentation JavaScript must not perform writes or remote requests.');
}

const portalPhp = walk(path.join(theme, 'template-parts/portal'))
  .map((file) => fs.readFileSync(file, 'utf8'))
  .join('\n');

if (/<form\b/i.test(portalPhp)) {
  throw new Error('Presentation-only Portal controls must not silently create submittable forms.');
}

const portalNav = fs.readFileSync(path.join(theme, 'template-parts/portal/navigation.php'), 'utf8');
for (const prohibitedDestination of ['جلسات', 'پیام‌ها', 'سفارش‌ها']) {
  if (portalNav.includes(prohibitedDestination)) {
    throw new Error(`Prohibited Portal top-level destination: ${prohibitedDestination}`);
  }
}

let braces = 0;
const portalCss = fs.readFileSync(path.join(theme, 'assets/css/portal.css'), 'utf8');
for (const character of `${css}\n${portalCss}`.replace(/\/\*[\s\S]*?\*\//g, '')) {
  if (character === '{') braces += 1;
  if (character === '}') braces -= 1;
  if (braces < 0) throw new Error('CSS closes a block before it opens.');
}
if (braces !== 0) throw new Error(`CSS brace imbalance: ${braces}`);

console.log('Static theme validation passed.');
