<?php
/** Dependency-free render regression checks for correction round 1. */

define( 'ABSPATH', __DIR__ );
define( 'DZN_TEST_THEME', dirname( __DIR__, 2 ) . '/theme/' );

function esc_html( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $value ) { return esc_html( $value ); }
function esc_url( $value ) { return esc_attr( $value ); }
function esc_html_e( $value ) { echo esc_html( $value ); }
function esc_attr_e( $value ) { echo esc_attr( $value ); }
function add_action() {}
function sanitize_key( $value ) { return preg_replace( '/[^a-z0-9_-]/', '', strtolower( (string) $value ) ); }
function add_query_arg( $args, $url ) { return $url . '?' . http_build_query( $args ); }
function get_template_part( $slug, $name = null, $args = array() ) {
	$file = DZN_TEST_THEME . $slug . '.php';
	if ( is_file( $file ) ) { require $file; }
}

require DZN_TEST_THEME . 'inc/portal.php';

$trusted_url = 'https://meet.example.invalid/trusted-action';
ob_start();
dzn_theme_portal_component(
	'upcoming-lesson',
	array(
		'lesson' => array(
			'presentation_state' => 'future_typo',
			'course' => 'نباید نمایش داده شود',
			'join_url' => $trusted_url,
			'notice' => 'unsafe-state-notice',
			'owed_session_label' => 'unsafe-state-entitlement',
		)
	)
);
$rendered = ob_get_clean();

foreach ( array( 'کلاس پیشِ رو', 'ورود به کلاس', 'اطلاع غیبت', 'افزودن به تقویم', $trusted_url, 'unsafe-state-notice', 'unsafe-state-entitlement' ) as $unsafe ) {
	if ( false !== strpos( $rendered, $unsafe ) ) {
		throw new RuntimeException( "Unknown state exposed unsafe output: {$unsafe}" );
	}
}
if ( false === strpos( $rendered, 'اطلاعات کلاس در دسترس نیست' ) ) {
	throw new RuntimeException( 'Unknown state did not render the explicit unavailable presentation.' );
}

$model = dzn_theme_student_portal_demo_model( 'home', 'https://preview.example.invalid/portal' );
$expected_contacts = array(
	'whatsapp_url' => 'https://contact.example.invalid/whatsapp',
	'email_url' => 'mailto:student-portal@example.invalid',
	'instagram_url' => 'https://social.example.invalid/instagram',
);
if ( $expected_contacts !== $model['contact'] ) {
	throw new RuntimeException( 'Preview contacts are not the explicitly allowed synthetic destinations.' );
}
$contacts = implode( ' ', $model['contact'] );
if ( preg_match( '~(?:wa\.me|instagram\.com|delnavazan@mail|61413413004)~i', $contacts ) ) {
	throw new RuntimeException( 'Preview contacts contain a live provider destination.' );
}

echo "Portal correction render tests passed.\n";
