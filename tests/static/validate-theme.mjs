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
  'assets/css/teacher-portal.css',
  'assets/js/teacher-portal.js',
  'inc/portal.php',
  'inc/teacher-portal.php',
  'page-templates/student-portal-home.php',
  'page-templates/student-portal-account.php',
  'page-templates/student-portal-preview.php',
  'page-templates/teacher-portal-home.php',
  'page-templates/teacher-portal-account.php',
  'page-templates/teacher-portal-onboarding.php',
  'page-templates/teacher-portal-preview.php',
];

for (const relative of requiredFiles) {
  const file = path.join(theme, relative);
  if (!fs.existsSync(file) || fs.statSync(file).size === 0) {
    throw new Error(`Missing or empty required file: ${relative}`);
  }
}

const style = fs.readFileSync(path.join(theme, 'style.css'), 'utf8');
if (!/^Version:\s*0\.6\.0$/m.test(style)) {
	throw new Error('Teacher Portal V1 candidate must identify as Theme 0.6.0.');
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

const teacherRuntime = fs.readFileSync(path.join(theme, 'inc/teacher-portal.php'), 'utf8');
const teacherAttention = fs.readFileSync(path.join(theme, 'template-parts/teacher-portal/attention.php'), 'utf8');
const teacherClasses = fs.readFileSync(path.join(theme, 'template-parts/teacher-portal/classes.php'), 'utf8');
const teacherAccount = fs.readFileSync(path.join(theme, 'template-parts/teacher-portal/account.php'), 'utf8');
const teacherJs = fs.readFileSync(path.join(theme, 'assets/js/teacher-portal.js'), 'utf8');
for (const state of ['intro_request','student_absence','teacher_disruption','replacement','paid_term_review','flexible_term_dates','google_problem','availability_conflict','admin_request']) {
  if (!teacherRuntime.includes(`'state' => '${state}'`) || !teacherRuntime.includes(`'${state}'`)) throw new Error(`Missing Teacher attention state: ${state}`);
}
for (const state of ['upcoming','starting_soon','student_absent','replacement','intro','flexible']) {
  if (!teacherRuntime.includes(`'state' => '${state}'`) || !teacherRuntime.includes(`'${state}'`)) throw new Error(`Missing Teacher class state: ${state}`);
}
if (!teacherAttention.includes("'unknown'") || !teacherClasses.includes("'unknown'") || !teacherAttention.includes('dzn_theme_teacher_portal_attention_valid') || !teacherClasses.includes('dzn_theme_teacher_portal_class_valid')) throw new Error('Unknown and malformed Teacher states must render fail-closed.');
if (!teacherClasses.includes('dzn-tp-private-note') || !teacherClasses.includes('dzn-tp-practice')) throw new Error('Private Teacher notes and Student practice must remain structurally distinct.');
if (!teacherAccount.includes("array( 'not_connected', 'connected', 'needs_attention' )")) throw new Error('Google presentation states are incomplete.');
if (!teacherRuntime.includes("'production' !== wp_get_environment_type()") || !teacherRuntime.includes("current_user_can( 'edit_theme_options' )")) throw new Error('Teacher fixtures must be gated outside production.');
if (!teacherRuntime.includes("apply_filters( 'dzn_theme_teacher_portal_view_model'")) throw new Error('Teacher display-model adapter seam is missing.');
if (!teacherRuntime.includes('if ( ! dzn_theme_is_teacher_portal_template() )')) throw new Error('Teacher assets must remain template-isolated.');
if (/fetch\s*\(|XMLHttpRequest|\.submit\s*\(/.test(teacherJs)) throw new Error('Teacher presentation JavaScript must not make remote requests.');
if (!teacherJs.includes("dznTpFallback = 'disclosure'") || teacherJs.includes("setAttribute('aria-modal'")) throw new Error('Teacher dialog fallback must be explicitly non-modal.');
for (const destination of [/wa\.me/i,/meet\.google\.com/i,/https?:\/\/delnavazan/i,/@[a-z0-9.-]+\.(?:com|ir)\b/i]) {
  if (destination.test(teacherRuntime)) throw new Error(`Teacher synthetic fixture contains a live-looking destination: ${destination}`);
}
let teacherBraces = 0;
for (const character of fs.readFileSync(path.join(theme, 'assets/css/teacher-portal.css'), 'utf8').replace(/\/\*[\s\S]*?\*\//g, '')) {
  if (character === '{') teacherBraces += 1;
  if (character === '}') teacherBraces -= 1;
  if (teacherBraces < 0) throw new Error('Teacher CSS closes a block before it opens.');
}
if (teacherBraces !== 0) throw new Error(`Teacher CSS brace imbalance: ${teacherBraces}`);

console.log('Static theme validation passed.');
