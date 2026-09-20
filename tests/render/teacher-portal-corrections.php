<?php
/** Behavioral render regressions for Teacher Portal correction round 1. */
define( 'ABSPATH', __DIR__ );
define( 'DZN_TEST_THEME', dirname( __DIR__, 2 ) . '/theme/' );
$filter_model = null;
function esc_html( $v ) { return htmlspecialchars( (string) $v, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $v ) { return esc_html( $v ); }
function esc_url( $v ) { return esc_attr( $v ); }
function esc_html_e( $v ) { echo esc_html( $v ); }
function add_action() {}
function sanitize_key( $v ) { return preg_replace( '/[^a-z0-9_-]/', '', strtolower( (string) $v ) ); }
function get_queried_object_id() { return 1; }
function apply_filters() { global $filter_model; return $filter_model; }
function add_query_arg( $key, $value, $url ) { return $url . '?' . http_build_query( array( $key => $value ) ); }
function selected( $a, $b ) { if ( $a === $b ) { echo ' selected="selected"'; } }
function get_template_part( $slug, $name = null, $args = array() ) { $file = DZN_TEST_THEME . $slug . '.php'; if ( is_file( $file ) ) { require $file; } }
require DZN_TEST_THEME . 'inc/teacher-portal.php';

function render_shell( $input, $screen = 'home' ) {
	global $filter_model; $filter_model = $input;
	$model = dzn_theme_teacher_portal_view_model( $screen );
	ob_start(); dzn_theme_render_teacher_portal( $screen, $model ); return ob_get_clean();
}
function reject_privileged( $html, $label ) {
	foreach ( array( 'شروع کلاس', 'انتخاب زمان', 'برنامه درست است', 'چیدن تاریخ‌ها', 'بررسی درخواست' ) as $action ) {
		if ( false !== strpos( $html, $action ) ) { throw new RuntimeException( "{$label} exposed {$action}" ); }
	}
}
foreach ( array( null, false, array(), array( 'available' => false ), array( 'available' => true, 'screen' => 'home' ), array( 'available' => true, 'screen' => 'account', 'teacher' => array( 'first_name' => 'x' ), 'navigation' => array(), 'attention' => array(), 'classes' => array(), 'calendar' => array() ) ) as $index => $bad ) {
	$html = render_shell( $bad );
	if ( false === strpos( $html, 'اطلاعات مدرس در دسترس نیست' ) ) { throw new RuntimeException( "Malformed top-level model {$index} did not fail closed" ); }
	reject_privileged( $html, "Malformed top-level model {$index}" );
}

function render_component( $component, $key, $items ) { ob_start(); dzn_theme_teacher_portal_component( $component, array( $key => $items ) ); return ob_get_clean(); }
foreach ( array(
	array( 'state' => 'replacement' ),
	array( 'state' => 'intro_request', 'ref' => 'A', 'action_available' => true ),
	array( 'state' => 'paid_term_review', 'ref' => 'A', 'title' => 'x', 'context' => 'x', 'message' => 'x', 'due' => 'x', 'primary' => 'برنامه درست است', 'action_available' => true ),
	array( 'state' => 'flexible_term_dates', 'ref' => 'A', 'title' => 'x', 'context' => 'x', 'message' => 'x', 'due' => 'x', 'primary' => 'چیدن تاریخ‌ها', 'action_available' => true ),
) as $index => $bad ) {
	$html = render_component( 'attention', 'items', array( $bad ) ); reject_privileged( $html, "Malformed attention {$index}" );
	if ( false === strpos( $html, 'این مورد در دسترس نیست' ) ) { throw new RuntimeException( 'Malformed attention did not share unavailable path' ); }
}
foreach ( array( array( 'state' => 'upcoming' ), array( 'state' => 'upcoming', 'ref' => 'C', 'course' => 'سه‌تار', 'time' => '۱۰:۰۰' ) ) as $index => $bad ) {
	$html = render_component( 'classes', 'items', array( $bad ) ); reject_privileged( $html, "Malformed class {$index}" );
	if ( false !== strpos( $html, '>جزئیات<' ) || false === strpos( $html, 'کلاس در دسترس نیست' ) ) { throw new RuntimeException( 'Malformed class exposed details or missed unavailable path' ); }
}
$valid = dzn_theme_teacher_portal_demo_model( 'home', 'https://preview.example.invalid/teacher' );
if ( ! dzn_theme_teacher_portal_validate_model( $valid, 'home' ) ) { throw new RuntimeException( 'Valid fixture failed top-level validation' ); }
$attention = render_component( 'attention', 'items', $valid['attention'] );
$classes = render_component( 'classes', 'items', $valid['classes'] );
foreach ( array( array( $attention, 'بررسی درخواست' ), array( $attention, 'برنامه درست است' ), array( $classes, 'شروع کلاس' ), array( $classes, '>جزئیات<' ) ) as $expect ) {
	if ( false === strpos( $expect[0], $expect[1] ) ) { throw new RuntimeException( "Valid fixture omitted {$expect[1]}" ); }
}

// Reviewer Home adversarial case: valid envelope with malformed collection contents.
$bad_home = array( 'available' => true, 'screen' => 'home', 'teacher' => $valid['teacher'], 'navigation' => $valid['navigation'], 'attention' => array( array( 'state' => 'replacement' ) ), 'classes' => array( array( 'state' => 'upcoming' ) ), 'calendar' => array( array( 'state' => 'unknown' ) ) );
if ( dzn_theme_teacher_portal_validate_model( $bad_home, 'home' ) ) { throw new RuntimeException( 'Reviewer malformed Home adversarial case passed validation' ); }
$bad_home_html = render_shell( $bad_home ); reject_privileged( $bad_home_html, 'Reviewer malformed Home' );
if ( false === strpos( $bad_home_html, 'اطلاعات مدرس در دسترس نیست' ) ) { throw new RuntimeException( 'Reviewer malformed Home did not render unavailable' ); }

// The canonical Student-absence identifier renders useful context/details, never Start Class.
$absence_item = array_values( array_filter( $valid['classes'], static fn( $item ) => 'student_absence' === $item['state'] ) )[0] ?? null;
$absence_html = render_component( 'classes', 'items', array( $absence_item ) );
foreach ( array( 'مهتاب', 'سنتور', 'غیبت اطلاع داده شده', '>جزئیات<' ) as $text ) { if ( false === strpos( $absence_html, $text ) ) { throw new RuntimeException( "Valid absence class omitted {$text}" ); } }
if ( false !== strpos( $absence_html, 'شروع کلاس' ) ) { throw new RuntimeException( 'Valid Student-absence class exposed Start Class' ); }

// Complete Account renders; all shallow, malformed and reviewer adversarial variants fail closed.
$account = dzn_theme_teacher_portal_demo_model( 'account', 'https://preview.example.invalid/teacher' );
if ( ! dzn_theme_teacher_portal_validate_model( $account, 'account' ) ) { throw new RuntimeException( 'Valid Account fixture failed validation' ); }
$account_html = render_shell( $account, 'account' );
foreach ( array( 'اطلاعات شخصی', 'نیازمند توجه', 'زمان‌های در دسترس', 'آمار تدریس' ) as $text ) { if ( false === strpos( $account_html, $text ) ) { throw new RuntimeException( "Valid Account omitted {$text}" ); } }
$bad_accounts = array();
foreach ( array( 'profile', 'availability', 'statistics', 'navigation' ) as $field ) { $bad = $account; $bad[ $field ] = array(); $bad_accounts[] = $bad; }
$bad = $account; unset( $bad['profile']['mobile'] ); $bad_accounts[] = $bad;
$bad = $account; $bad['availability'][0]['blocks'] = 'not-an-array'; $bad_accounts[] = $bad;
$bad = $account; $bad['statistics']['upcoming'] = array(); $bad_accounts[] = $bad;
$bad = $account; $bad['profile'] = 'wrong-type'; $bad_accounts[] = $bad;
$bad_accounts[] = array( 'available'=>true, 'screen'=>'account', 'teacher'=>array( 'first_name'=>'سارا' ), 'navigation'=>array(), 'profile'=>array(), 'availability'=>array(), 'statistics'=>array(), 'google_state'=>'connected', 'payment_state'=>'paid' );
foreach ( $bad_accounts as $index => $bad ) {
	if ( dzn_theme_teacher_portal_validate_model( $bad, 'account' ) ) { throw new RuntimeException( "Malformed Account {$index} passed validation" ); }
	$html = render_shell( $bad, 'account' );
	if ( false === strpos( $html, 'اطلاعات مدرس در دسترس نیست' ) ) { throw new RuntimeException( "Malformed Account {$index} did not render unavailable" ); }
	foreach ( array( 'اتصال Google', 'متصل', 'پرداخت‌شده', 'زمان‌های در دسترس', 'ذخیره در آینده' ) as $trusted ) { if ( false !== strpos( $html, $trusted ) ) { throw new RuntimeException( "Malformed Account {$index} exposed {$trusted}" ); } }
}

// IANA timezone validation: canonical PHP identifiers pass; malformed values fail closed.
foreach ( array( 'Australia/Brisbane', 'Asia/Tehran' ) as $timezone ) { $candidate = $account; $candidate['profile']['timezone'] = $timezone; if ( ! dzn_theme_teacher_portal_validate_model( $candidate, 'account' ) ) { throw new RuntimeException( "Valid timezone {$timezone} failed" ); } }
foreach ( array( 'definitely/not-a-timezone', '', array( 'Asia/Tehran' ) ) as $index => $timezone ) {
	$candidate = $account; $candidate['profile']['timezone'] = $timezone;
	if ( dzn_theme_teacher_portal_validate_model( $candidate, 'account' ) ) { throw new RuntimeException( "Invalid timezone {$index} passed" ); }
	$html = render_shell( $candidate, 'account' );
	if ( false === strpos( $html, 'اطلاعات مدرس در دسترس نیست' ) ) { throw new RuntimeException( "Invalid timezone {$index} did not render unavailable" ); }
	foreach ( array( 'اتصال Google', 'پرداخت‌شده', 'زمان‌های در دسترس', 'ذخیره در آینده' ) as $trusted ) { if ( false !== strpos( $html, $trusted ) ) { throw new RuntimeException( "Invalid timezone {$index} exposed {$trusted}" ); } }
}

// Navigation must contain one stable entry per screen and current must match requested screen.
foreach ( array( 'home' => $valid, 'account' => $account ) as $screen => $candidate ) { if ( ! dzn_theme_teacher_portal_validate_model( $candidate, $screen ) ) { throw new RuntimeException( "Valid {$screen} navigation failed" ); } }
$nav_cases = array();
$bad = $account; foreach ( $bad['navigation'] as &$item ) { $item['current'] = false; } unset( $item ); $nav_cases[] = $bad;
$bad = $account; foreach ( $bad['navigation'] as &$item ) { $item['current'] = true; } unset( $item ); $nav_cases[] = $bad;
$bad = $account; foreach ( $bad['navigation'] as &$item ) { $item['current'] = 'home' === $item['screen']; } unset( $item ); $nav_cases[] = $bad;
$bad = $valid; foreach ( $bad['navigation'] as &$item ) { $item['current'] = 'account' === $item['screen']; } unset( $item ); $nav_cases[] = $bad;
$bad = $account; unset( $bad['navigation'][0]['screen'] ); $nav_cases[] = $bad;
foreach ( $nav_cases as $index => $candidate ) { $screen = 3 === $index ? 'home' : 'account'; if ( dzn_theme_teacher_portal_validate_model( $candidate, $screen ) ) { throw new RuntimeException( "Invalid navigation {$index} passed" ); } }

// Complete Onboarding renders; bad step, identity, navigation and explicit unavailable do not.
$onboarding = dzn_theme_teacher_portal_demo_model( 'onboarding', 'https://preview.example.invalid/teacher' );
if ( ! dzn_theme_teacher_portal_validate_model( $onboarding, 'onboarding' ) || false === strpos( render_shell( $onboarding, 'onboarding' ), 'هفت گام آمادگی' ) ) { throw new RuntimeException( 'Valid Onboarding did not render' ); }
$bad_onboarding = array();
foreach ( array( 0, 8, '4' ) as $step ) { $bad = $onboarding; $bad['current_step'] = $step; $bad_onboarding[] = $bad; }
$bad = $onboarding; $bad['teacher'] = array(); $bad_onboarding[] = $bad;
$bad = $onboarding; $bad['navigation'] = array(); $bad_onboarding[] = $bad;
$bad = $onboarding; $bad['available'] = false; $bad_onboarding[] = $bad;
foreach ( $bad_onboarding as $index => $bad ) {
	$html = render_shell( $bad, 'onboarding' );
	if ( false === strpos( $html, 'اطلاعات مدرس در دسترس نیست' ) || false !== strpos( $html, 'ادامه در آینده' ) ) { throw new RuntimeException( "Malformed Onboarding {$index} did not fail closed" ); }
}
echo "Teacher Portal correction render tests passed.\n";
