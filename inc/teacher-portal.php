<?php
/** Teacher Portal presentation boundary. @package DelnavazanTheme */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function dzn_theme_teacher_portal_templates() {
	return array( 'page-templates/teacher-portal-home.php', 'page-templates/teacher-portal-account.php', 'page-templates/teacher-portal-onboarding.php', 'page-templates/teacher-portal-preview.php' );
}
function dzn_theme_is_teacher_portal_template() {
	foreach ( dzn_theme_teacher_portal_templates() as $template ) { if ( is_page_template( $template ) ) { return true; } }
	return false;
}
function dzn_theme_enqueue_teacher_portal_assets() {
	if ( ! dzn_theme_is_teacher_portal_template() ) { return; }
	$css = get_theme_file_path( 'assets/css/teacher-portal.css' );
	$js  = get_theme_file_path( 'assets/js/teacher-portal.js' );
	wp_enqueue_style( 'delnavazan-teacher-portal', get_theme_file_uri( 'assets/css/teacher-portal.css' ), array( 'delnavazan-theme' ), file_exists( $css ) ? (string) filemtime( $css ) : wp_get_theme()->get( 'Version' ) );
	wp_enqueue_script( 'delnavazan-teacher-portal', get_theme_file_uri( 'assets/js/teacher-portal.js' ), array(), file_exists( $js ) ? (string) filemtime( $js ) : wp_get_theme()->get( 'Version' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
}
add_action( 'wp_enqueue_scripts', 'dzn_theme_enqueue_teacher_portal_assets', 20 );

function dzn_theme_teacher_portal_view_model( $screen ) {
	$allowed = array( 'home', 'account', 'onboarding' );
	$screen = in_array( $screen, $allowed, true ) ? $screen : 'home';
	$model = apply_filters( 'dzn_theme_teacher_portal_view_model', null, $screen, get_queried_object_id() );
	return is_array( $model ) ? array_merge( $model, array( 'available' => true, 'screen' => $screen ) ) : array( 'available' => false, 'screen' => $screen );
}
function dzn_theme_teacher_portal_preview_allowed() {
	return 'production' !== wp_get_environment_type() && is_user_logged_in() && current_user_can( 'edit_theme_options' );
}
function dzn_theme_teacher_portal_demo_model( $screen, $url ) {
	$nav = array(
		array( 'label' => 'خانهٔ مدرس', 'url' => add_query_arg( 'teacher-view', 'home', $url ), 'current' => 'home' === $screen ),
		array( 'label' => 'حساب کاربری', 'url' => add_query_arg( 'teacher-view', 'account', $url ), 'current' => 'account' === $screen ),
		array( 'label' => 'شروع همکاری', 'url' => add_query_arg( 'teacher-view', 'onboarding', $url ), 'current' => 'onboarding' === $screen ),
	);
	$base = array( 'is_demo' => true, 'teacher' => array( 'first_name' => 'سارا', 'full_name' => 'سارا نمونه' ), 'navigation' => $nav );
	if ( 'account' === $screen ) {
		return array_merge( $base, array(
			'profile' => array( 'name' => 'سارا نمونه', 'email' => 'teacher@example.invalid', 'mobile' => '+00 000 000 000', 'timezone' => 'Asia/Tehran', 'timezone_label' => 'زمان تهران', 'calendar' => 'persian' ),
			'google_state' => 'needs_attention',
			'availability' => array(
				array( 'day' => 'شنبه', 'blocks' => array( '۱۶:۰۰–۱۸:۰۰', '۱۹:۰۰–۲۱:۰۰' ), 'booked' => '۱۸:۳۰–۱۹:۱۵' ),
				array( 'day' => 'دوشنبه', 'blocks' => array( '۱۷:۰۰–۲۱:۰۰' ), 'booked' => '۱۹:۰۰–۱۹:۴۵' ),
				array( 'day' => 'چهارشنبه', 'blocks' => array( '۱۵:۰۰–۱۸:۰۰' ), 'booked' => '' ),
			),
			'exceptions' => array( '۲ مهر — در دسترس نیست', '۱۲ مهر — ۱۸:۰۰ تا ۲۱:۰۰' ),
			'payment_state' => 'pending_verification',
			'statistics' => array( 'active_students' => '۸', 'lessons_month' => '۲۶', 'hours_month' => '۱۹٫۵', 'upcoming' => '۱۲', 'year_total' => '۱۹۴' ),
		) );
	}
	if ( 'onboarding' === $screen ) { return array_merge( $base, array( 'current_step' => 4 ) ); }
	$attention = array(
		array( 'state' => 'intro_request', 'title' => 'درخواست تازهٔ کلاس آشنایی', 'context' => 'نیلا · سه‌تار', 'message' => 'شنبه ۲۸ شهریور، ۱۸:۳۰ زمان شما · ۱۱:۰۰ زمان هنرجو', 'due' => 'پاسخ تا ۱۸ ساعت دیگر', 'primary' => 'بررسی درخواست', 'secondary' => 'زمان‌های جایگزین' ),
		array( 'state' => 'student_absent', 'title' => 'هنرجو غیبت را اطلاع داده', 'context' => 'آرین · پیانو · امروز ۱۷:۰۰', 'message' => 'این اطلاع به‌تنهایی وضعیت حضور یا جایگزینی را تعیین نمی‌کند.', 'due' => 'اطلاع تازه', 'primary' => 'تأیید مشاهده' ),
		array( 'state' => 'teacher_issue', 'title' => 'نمی‌توانید در کلاس حاضر شوید؟', 'context' => 'مهسا · سنتور · فردا ۱۹:۰۰', 'message' => 'مشکل را گزارش کنید؛ این پیش‌نمایش کلاس را لغو نمی‌کند.', 'due' => 'پیش از شروع کلاس', 'primary' => 'گزارش مشکل' ),
		array( 'state' => 'replacement', 'title' => 'جلسهٔ جبرانی مجاز نیاز به زمان دارد', 'context' => 'کیان · تار', 'message' => 'مجوز جایگزینی از مدل نمایشی دریافت شده؛ پوسته آن را ایجاد نمی‌کند.', 'due' => 'تا ۵ مهر', 'primary' => 'انتخاب زمان' ),
		array( 'state' => 'term_review', 'title' => 'برنامهٔ ترم پرداخت‌شده آمادهٔ بازبینی است', 'context' => 'رها · کمانچه · ۱۲ جلسه', 'message' => 'بازبینی ایمنی برنامه است، نه پذیرش دوبارهٔ هنرجو.', 'due' => 'شروع: ۳ مهر', 'primary' => 'برنامه درست است', 'secondary' => 'اصلاح برنامه' ),
		array( 'state' => 'flexible_term', 'title' => '۱۲ جلسه مجاز؛ تاریخ‌ها هنوز تعیین نشده‌اند', 'context' => 'سام · تنبک', 'message' => 'جلسه‌های ترم انعطاف‌پذیر به هماهنگی تاریخ نیاز دارند.', 'due' => 'پیش از شروع ترم', 'primary' => 'چیدن تاریخ‌ها' ),
		array( 'state' => 'google_problem', 'title' => 'اتصال Google نیاز به توجه دارد', 'context' => 'حساب نمایشی', 'message' => 'هیچ اتصال واقعی در این پیش‌نمایش انجام نمی‌شود.', 'due' => 'مشکل فنی', 'primary' => 'مشاهدهٔ راهنما' ),
		array( 'state' => 'availability_conflict', 'title' => 'تداخل با زمان در دسترس', 'context' => 'پنجشنبه ۱۸:۰۰', 'message' => 'یک کلاس نمایشی خارج از بازهٔ ترجیحی دیده می‌شود.', 'due' => 'بررسی تا فردا', 'primary' => 'بررسی تداخل' ),
		array( 'state' => 'admin_request', 'title' => 'درخواست مدیر آموزشگاه', 'context' => 'تکمیل اطلاعات حساب', 'message' => 'منطقهٔ زمانی خود را بازبینی کنید.', 'due' => 'تا ۲ مهر', 'primary' => 'باز کردن حساب' ),
	);
	$classes = array(
		array( 'state' => 'upcoming', 'time' => '۱۰:۰۰', 'student' => 'هلیا', 'course' => 'سه‌تار', 'progress' => 'ترم ۱ · جلسهٔ ۲/۱۲', 'status' => 'پیش رو' ),
		array( 'state' => 'starting_soon', 'time' => '۱۱:۳۰', 'student' => 'پارسا', 'course' => 'پیانو', 'progress' => 'ترم ۲ · جلسهٔ ۷/۱۲', 'status' => 'به‌زودی شروع می‌شود' ),
		array( 'state' => 'student_absent', 'time' => '۱۴:۰۰', 'student' => 'مهتاب', 'course' => 'سنتور', 'progress' => 'ترم ۱ · جلسهٔ ۵/۱۲', 'status' => 'غیبت اطلاع داده شده' ),
		array( 'state' => 'replacement', 'time' => '۱۶:۳۰', 'student' => 'نوید', 'course' => 'تار', 'progress' => 'جلسهٔ جایگزین مجاز', 'status' => 'جایگزین' ),
		array( 'state' => 'intro', 'time' => '۱۸:۰۰', 'student' => 'ساغر', 'course' => 'کمانچه', 'progress' => 'کلاس آشنایی', 'status' => 'آشنایی' ),
		array( 'state' => 'flexible', 'time' => '۲۰:۰۰', 'student' => 'آوا', 'course' => 'دف', 'progress' => 'ترم انعطاف‌پذیر', 'status' => 'زمان هماهنگ‌شده' ),
	);
	return array_merge( $base, array(
		'announcement' => array( 'state' => 'technical', 'title' => 'یادآوری فنی', 'message' => 'پیش از نخستین کلاس، صدا و تنظیمات موسیقی را بررسی کنید.' ),
		'attention' => $attention, 'classes' => $classes,
		'calendar' => array( array( 'day' => 'فردا', 'time' => '۱۷:۰۰', 'label' => 'کلاس معمول · سه‌تار', 'state' => 'regular' ), array( 'day' => 'دوشنبه', 'time' => '۱۸:۳۰', 'label' => 'کلاس آشنایی · پیانو', 'state' => 'intro' ), array( 'day' => 'سه‌شنبه', 'time' => '۲۰:۰۰', 'label' => 'جلسهٔ جایگزین · تار', 'state' => 'replacement' ), array( 'day' => 'چهارشنبه', 'time' => '۱۶:۰۰', 'label' => 'غیبت اطلاع‌داده‌شده · سنتور', 'state' => 'student_absent' ) ),
	) );
}
function dzn_theme_render_teacher_portal( $screen, array $model ) { get_template_part( 'template-parts/teacher-portal/shell', null, array( 'screen' => $screen, 'model' => $model ) ); }
function dzn_theme_teacher_portal_component( $component, array $args = array() ) { get_template_part( 'template-parts/teacher-portal/' . sanitize_key( $component ), null, $args ); }
