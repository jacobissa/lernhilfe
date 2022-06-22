<?php

/**
 * Register gutenberg block type 'ytvideo'.
 * @Hook init
 */
function learningaid_register_block_type_ytvideo()
{
    wp_register_script('block-ytvideo-js', LEARNINGAID_PLUGIN_LOCATION_URL . '/blocks/ytvideo/block-ytvideo.js', array('wp-blocks', 'wp-editor'));
    wp_register_style('block-ytvideo-css', LEARNINGAID_PLUGIN_LOCATION_URL . '/blocks/ytvideo/block-ytvideo.css');
    $args = array(
        'editor_script' => 'block-ytvideo-js',
        'style' => 'block-ytvideo-css',
    );
    register_block_type('learning-aid/block-ytvideo', $args);
}
add_action('init', 'learningaid_register_block_type_ytvideo');
