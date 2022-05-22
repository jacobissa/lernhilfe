<?php wp_enqueue_script( 'script', get_template_directory_uri() . '/script.js');

add_action('get_header', 'my_filter_head');

function my_filter_head() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}