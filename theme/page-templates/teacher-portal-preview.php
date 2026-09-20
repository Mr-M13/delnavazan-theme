<?php
/** Template Name: Teacher Portal — Development Preview */
if ( ! dzn_theme_teacher_portal_preview_allowed() ) { status_header( 404 ); nocache_headers(); include get_query_template( '404' ); return; }
$screen = isset( $_GET['teacher-view'] ) ? sanitize_key( wp_unslash( $_GET['teacher-view'] ) ) : 'home';
if ( ! in_array( $screen, array( 'home', 'account', 'onboarding' ), true ) ) { $screen = 'home'; }
get_header(); while ( have_posts() ) { the_post(); dzn_theme_render_teacher_portal( $screen, dzn_theme_teacher_portal_demo_model( $screen, get_permalink() ) ); } get_footer();
