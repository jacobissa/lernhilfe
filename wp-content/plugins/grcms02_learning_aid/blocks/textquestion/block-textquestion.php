<?php

/**
 * Register gutenberg block type 'textquestion'.
 * @Hook init
 */
function learningaid_register_block_type_textquestion()
{
    wp_register_script('block-textquestion-js', LEARNINGAID_PLUGIN_LOCATION_URL . '/blocks/textquestion/block-textquestion.js', array('wp-blocks', 'wp-editor'));
    wp_register_style('block-textquestion-css', LEARNINGAID_PLUGIN_LOCATION_URL . '/blocks/textquestion/block-textquestion.css');
    $args = array(
        'editor_script' => 'block-textquestion-js',
        'style' => 'block-textquestion-css',
    );
    register_block_type('learning-aid/block-textquestion', $args);
}
add_action('init', 'learningaid_register_block_type_textquestion');
