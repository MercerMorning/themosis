<?php

use Themosis\Support\Facades\Action;

Action::add('wp_enqueue_scripts', function () {
//   wp_enqueue_script('app_js', get_template_directory_uri() . '/assets/js/app.js', [], false, true);
   wp_enqueue_style('app_style', get_template_directory_uri() . '/assets/styles/style.css');
});
