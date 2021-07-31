<?php

use Themosis\Support\Facades\Action;
use Themosis\Support\Facades\Filter;

Action::add('wp_enqueue_scripts', function () {
   wp_enqueue_script('app_js', get_template_directory_uri() . '/assets/js/app.js', [], false, true);
   wp_enqueue_style('app_style', get_template_directory_uri() . '/assets/styles/style.css');
});

///**
// * Adds custom classes to the array of body classes.
// *
// * @param array $classes Classes for the body element.
// *
// * @return array
// */
//Filter::add('body_class', function ($classes) {
//    // Adds a class of hfeed to non-singular pages.
//    if (! is_singular()) {
//        $classes[] = 'hfeed';
//    }
//
//    // Adds a class of no-sidebar when there is no sidebar present.
//    if (! is_active_sidebar('sidebar-1')) {
//        $classes[] = 'no-sidebar';
//    }
//
//    return $classes;
//});
//
///**
// * Add a pingback url auto-discovery header for single posts, pages, or attachments.
// */
//Action::add('wp_head', function () {
//    if (is_singular() && pings_open()) {
//        echo '<link rel="pingback" href="'.esc_url(get_bloginfo('pingback_url')).'">';
//    }
//});
//
///**
// * Set the content width in pixels, based on the theme's design and stylesheet.
// */
//Action::add('after_setup_theme', function () {
//    $GLOBALS['content_width'] = 640;
//}, 0);
