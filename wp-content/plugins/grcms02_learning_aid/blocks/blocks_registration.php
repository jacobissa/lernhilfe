<?php

/* --------------------------------------------------
 * Create custom Gutenberg blocks.
 * -------------------------------------------------- */

/**
 * custom Gutenberg blocks: ytvideo
 */
add_action('init', 'grcms02_register_block_type_ytvideo');
function grcms02_register_block_type_ytvideo()
{
    wp_register_script('block-ytvideo-js', LEARNINGAID_PLUGIN_LOCATION_URL . '/blocks/ytvideo/block-ytvideo.js', array('wp-blocks', 'wp-editor'));
    wp_register_style('block-ytvideo-css', LEARNINGAID_PLUGIN_LOCATION_URL . '/blocks/ytvideo/block-ytvideo.css');
    $args = array(
        'editor_script' => 'block-ytvideo-js',
        'style' => 'block-ytvideo-css',
    );
    register_block_type('learning-aid/block-ytvideo', $args);
}


/**
 * custom Gutenberg blocks: textquestion
 */
add_action('init', 'grcms02_register_block_type_textquestion');
function grcms02_register_block_type_textquestion()
{
    wp_register_script('block-textquestion-js', LEARNINGAID_PLUGIN_LOCATION_URL . '/blocks/textquestion/block-textquestion.js', array('wp-blocks', 'wp-editor'));
    wp_register_style('block-textquestion-css', LEARNINGAID_PLUGIN_LOCATION_URL . '/blocks/textquestion/block-textquestion.css');
    $args = array(
        'editor_script' => 'block-textquestion-js',
        'style' => 'block-textquestion-css',
    );
    register_block_type('learning-aid/block-textquestion', $args);
}

/**
 * Allowed Gutenberg block types for the new custom post types
 */
add_action('allowed_block_types', 'grcms02_allowed_block_types', 10, 2);
function grcms02_allowed_block_types($allowed_block_types, $post)
{
    if ($post->post_type == 'course') :
        return array('learning-aid/block-ytvideo');
    elseif ($post->post_type == 'exercise') :
        return array('learning-aid/block-textquestion');
    else :
        return $allowed_block_types;
    endif;
}
