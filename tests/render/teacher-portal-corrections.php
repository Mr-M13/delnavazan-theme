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

function render_shell( $input ) {
	global $filter_model; $filter_model = $input;
	$model = dzn_theme_teacher_portal_view_model( 'home' );
	ob_start(); dzn_theme_render_teacher_portal( 'home', $model ); return ob_get_clean();
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
echo "Teacher Portal correction render tests passed.\n";
