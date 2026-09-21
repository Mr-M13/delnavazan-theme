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
  'inc/content-page.php',
  'page-templates/content-policy.php',
  'template-parts/content/content-document.php',
  'template-parts/content/table-of-contents.php',
  'template-parts/content/related-content.php',
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
if (!/^Version:\s*0\.7\.0$/m.test(style)) {
	throw new Error('Single Content Page V1 candidate must identify as Theme 0.7.0.');
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
const documentCss = css.slice(css.indexOf('@layer document'), css.indexOf('@media print'));

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

// ---------------------------------------------------------------------------
// Single Content Page V1 — Article / Policy / General document system
// ---------------------------------------------------------------------------
const contentPage = fs.readFileSync(path.join(theme, 'inc/content-page.php'), 'utf8');
const documentPartial = fs.readFileSync(path.join(theme, 'template-parts/content/content-document.php'), 'utf8');
const tocPartial = fs.readFileSync(path.join(theme, 'template-parts/content/table-of-contents.php'), 'utf8');
const relatedPartial = fs.readFileSync(path.join(theme, 'template-parts/content/related-content.php'), 'utf8');
const policyTemplate = fs.readFileSync(path.join(theme, 'page-templates/content-policy.php'), 'utf8');
const singleTemplate = fs.readFileSync(path.join(theme, 'single.php'), 'utf8');
const pageTemplate = fs.readFileSync(path.join(theme, 'page.php'), 'utf8');

for (const mode of ['article', 'policy', 'general']) {
	if (!contentPage.includes(`'${mode}'`)) throw new Error(`Missing content-page mode: ${mode}`);
}
for (const fn of [
	'dzn_theme_content_page_mode',
	'dzn_theme_content_page_anchor_content',
	'dzn_theme_content_page_unique_anchor',
	'dzn_theme_content_page_reading_minutes',
	'dzn_theme_content_page_presentation',
	'dzn_theme_content_page_data',
]) {
	if (!contentPage.includes(`function ${fn}(`)) throw new Error(`Missing content-page function: ${fn}`);
}
if (/add_filter\(\s*'the_content'/.test(contentPage)) {
	throw new Error('Anchoring must not be registered globally on the_content.');
}
if (!contentPage.includes("apply_filters( 'the_content', $post->post_content )")) {
	throw new Error('The document contract must read the canonical content pipeline.');
}
for (const helper of ['dzn_theme_content_page_tag_end', 'dzn_theme_content_page_attribute', 'dzn_theme_content_page_ids', 'dzn_theme_content_page_reserved_ids']) {
	if (!contentPage.includes(`function ${helper}(`)) throw new Error(`Missing quote-aware document helper: ${helper}`);
}
if (!contentPage.includes('(?=[\\s\\/>])')) {
	throw new Error('The heading scan must exclude non-heading tags such as <hr>.');
}
if (contentPage.includes('#<(h[1-6])\\b([^>]*)>')) {
	throw new Error('Brittle opening-tag parsing must not return.');
}
if (!contentPage.includes('function dzn_theme_content_page_reserved_ids( $post = null )')) {
	throw new Error('The template-owned id contract must be one explicit function.');
}
if (!contentPage.includes("'main-content'") || !contentPage.includes("'post-' . (int) $post->ID")) {
	throw new Error('The reserved contract must cover the document wrapper ids, including the dynamic post wrapper.');
}
if (!contentPage.includes('dzn_theme_content_page_anchor_content( $content, dzn_theme_content_page_reserved_ids( $post ) )')) {
	throw new Error('Anchoring must consume the template-owned id contract.');
}
if (!contentPage.includes('function dzn_theme_content_page_is_document_response( $post = null )')) {
	throw new Error('One shared document-response predicate is required.');
}
if (!contentPage.includes('if ( ! dzn_theme_content_page_is_document_response() ) {')) {
	throw new Error('The print marker must be driven by the shared predicate.');
}
if (/is_singular\(|is_front_page\(/.test(contentPage.replace(contentPage.slice(contentPage.indexOf('function dzn_theme_content_page_is_document_response'), contentPage.indexOf('function dzn_theme_content_page_body_class')), ''))) {
	throw new Error('Document-response guards must live only in the shared predicate.');
}
if (contentPage.includes('substr_count( $span')) {
	throw new Error('Global quote-count validation must not return.');
}
if (!contentPage.includes("'<' === $character && $index > (int) $start")) {
	throw new Error('The scanner must fail safe when a tag never closes.');
}
if (!contentPage.includes('$clusters[] = $cluster;') || !contentPage.includes('1 === count( $candidate_cluster )')) {
	throw new Error('Ambiguous nested/overlapping heading ranges must be excluded.');
}
if (!documentPartial.includes('$dzn_document_data[\'content\']') || !documentPartial.includes('the_content();')) {
	throw new Error('The document body must render the anchored pipeline output and keep the paginated path.');
}
if (!documentPartial.includes('wp_link_pages(') || !documentPartial.includes('aria-label=')) {
	throw new Error('Paginated documents must expose accessible reader navigation.');
}
if (!documentPartial.includes('صفحهٔ بعد') || !documentPartial.includes('صفحهٔ قبل')) {
	throw new Error('Pagination labels must be localised.');
}
if (!contentPage.includes("add_filter( 'body_class', 'dzn_theme_content_page_body_class' )") || !contentPage.includes('dzn-document-body')) {
	throw new Error('Document print scoping class must be registered.');
}
if (!contentPage.includes('is_front_page()') || !contentPage.includes('dzn_theme_is_portal_template') || !contentPage.includes('dzn_theme_is_teacher_portal_template')) {
	throw new Error('The document body class must exclude the front page and both portals.');
}
if (!contentPage.includes('return array( 2, 3 );') || contentPage.includes("'h1'")) {
	throw new Error('Only H2/H3 may join the document outline.');
}
if (!contentPage.includes('function dzn_theme_content_page_toc_minimum()')) {
	throw new Error('The table-of-contents gate must be explicit.');
}
if (!contentPage.includes("return 3;")) {
	throw new Error('The table of contents must require three outline sections.');
}
if (!contentPage.includes("'page-templates/content-policy.php'")) {
	throw new Error('Policy mode must be selected through the standard page template.');
}
if (!contentPage.includes('<!--nextpage-->')) {
	throw new Error('Paginated posts must be left to core.');
}
for (const [name, source] of [['single.php', singleTemplate], ['page.php', pageTemplate], ['page-templates/content-policy.php', policyTemplate]]) {
	if (!source.includes("'template-parts/content/content'") || !source.includes("'document'") || !source.includes("'mode' =>")) {
		throw new Error(`${name} must render the shared document template with an explicit mode.`);
	}
}
if (!singleTemplate.includes("'mode' => 'article'")) throw new Error('Single posts must render Article mode.');
if (!policyTemplate.includes("'mode' => 'policy'")) throw new Error('The Policy template must render Policy mode.');
if (!pageTemplate.includes('dzn_theme_content_page_mode()')) throw new Error('Pages must resolve their presentation mode.');
if (!documentPartial.includes("'variant' => 'desktop'") || !documentPartial.includes("'variant' => 'mobile'")) {
	throw new Error('The document must render both outline variants.');
}
if (!documentPartial.includes('requires_toc')) {
	throw new Error('The document outline must be gated by the outline size.');
}
if (!documentPartial.includes('the_content()')) {
	throw new Error('The document body must render through the_content().');
}
if (!documentPartial.includes("'show_print_hint'")) {
	throw new Error('Policy print support must be driven by the presentation contract.');
}
if (!tocPartial.includes('<details') || !tocPartial.includes('<summary')) {
	throw new Error('The mobile outline must be a native disclosure.');
}
if (!tocPartial.includes('dzn-toc--desktop') || !tocPartial.includes('aria-labelledby')) {
	throw new Error('The desktop outline must be a labelled sticky navigation.');
}
if (/aria-modal|role="dialog"/.test(tocPartial)) {
	throw new Error('The outline must not pretend to be a modal dialog.');
}
if (/fetch\s*\(|XMLHttpRequest|\.submit\s*\(/.test(tocPartial + documentPartial)) {
	throw new Error('The document system must stay JavaScript-free.');
}
if (!relatedPartial.includes('wp_get_post_categories') || !relatedPartial.includes('WP_Query')) {
	throw new Error('Related content must reuse existing categories and standard WP queries.');
}
if (!relatedPartial.includes('wp_reset_postdata()')) {
	throw new Error('Related content must restore the main query.');
}
if (/amelia|delnavazan-platform|booking-requests|\$wpdb|wp_remote_|register_rest_route|get_post_meta\(\s*[^,]+, '\$/i.test(contentPage + documentPartial + tocPartial + relatedPartial + policyTemplate)) {
	throw new Error('The document system must stay presentation-only.');
}
if (!css.includes('--dzn-measure-document: 43rem')) {
	throw new Error('The document measure must be the 43rem document column.');
}
if (!documentCss.includes('.dzn-document__body')) {
	throw new Error('Document layout rules are missing.');
}
if (!documentCss.includes('position:\n        sticky')) {
	throw new Error('The desktop outline must be sticky.');
}
if (!documentCss.includes('@media (min-width: 64rem)')) {
	throw new Error('The document system must define its desktop breakpoint.');
}
const printBlock = css.split('@media print')[1] ?? '';
if (!/dzn-toc/.test(printBlock)) {
	throw new Error('Print support must remove the outline and site chrome.');
}
if (css.split('@media print').length > 2) {
	throw new Error('There must be exactly one print block.');
}
for (const chunk of printBlock.split('{').slice(0, -1)) {
	const selectorText = chunk.slice(Math.max(chunk.lastIndexOf('}'), chunk.lastIndexOf(';')) + 1).trim();
	if ('' === selectorText) continue;
	if (!selectorText.startsWith('body.dzn-document-body')) {
		throw new Error(`Print selectors must be scoped to document pages: ${selectorText.split('\n')[0]}`);
	}
}
if ((printBlock.match(/body\.dzn-document-body/g) ?? []).length < 6) {
	throw new Error('Document print rules must all be scoped through the document body class.');
}
if (!documentCss.includes('direction:\n      inherit') || !documentCss.includes('table[dir="ltr"]') || !documentCss.includes('.dzn-table--ltr')) {
	throw new Error('Document tables must default to RTL with an explicit LTR opt-in.');
}
if (!documentCss.includes('overflow-x') || !documentCss.includes('.dzn-document__pagination')) {
	throw new Error('Table overflow and pagination styles are missing.');
}
for (const preserved of ['.screen-reader-text', '.screen-reader-text:focus', '[hidden]', '.site-branding__description', '.menu-toggle__label', '.dzn-owned-media-slot']) {
	if (!css.includes(preserved)) {
		throw new Error(`A shared theme utility or compatibility rule was removed: ${preserved}`);
	}
}
if ((css.match(/--dzn-color-([a-z-]+):/g) ?? []).length < 18) {
	throw new Error('The token layer must keep every semantic colour token.');
}
const portalCssForDocument = fs.readFileSync(path.join(theme, 'assets/css/portal.css'), 'utf8');
if (/dzn-document/.test(portalCssForDocument)) {
	throw new Error('Student and Teacher Portal CSS must remain independent of the document system.');
}
for (const portalFile of ['page-templates/student-portal-home.php', 'page-templates/student-portal-account.php', 'page-templates/teacher-portal-home.php']) {
	const portalSource = fs.readFileSync(path.join(theme, portalFile), 'utf8');
	if (/content-document|table-of-contents|related-content/.test(portalSource)) {
		throw new Error(`Portal template ${portalFile} must not consume the document system.`);
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
for (const state of ['upcoming','starting_soon','student_absence','replacement','intro','flexible']) {
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
