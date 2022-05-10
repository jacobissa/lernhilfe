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
  wp_register_script('grcms02-block-ytvideo-js', PLUGIN_LOCATION_URL . '/block/grcms02-block-ytvideo.js', array('wp-blocks', 'wp-editor'));
  wp_register_style('grcms02-block-ytvideo-css', PLUGIN_LOCATION_URL . '/block/grcms02-block-ytvideo.css');
  $args = array(
    'editor_script' => 'grcms02-block-ytvideo-js',
    'style' => 'grcms02-block-ytvideo-css',
  );
  register_block_type('learning-aid/grcms02-block-ytvideo', $args);
}

/**
 * custom Gutenberg blocks: flashcard
 */
add_action('init', 'grcms02_register_block_type_flashcard');
function grcms02_register_block_type_flashcard()
{
  wp_register_script('grcms02-block-flashcard-js', PLUGIN_LOCATION_URL . '/block/grcms02-block-flashcard.js', array('wp-blocks', 'wp-editor'));
  wp_register_style('grcms02-block-flashcard-css', PLUGIN_LOCATION_URL . '/block/grcms02-block-flashcard.css');
  $args = array(
    'editor_script' => 'grcms02-block-flashcard-js',
    'style' => 'grcms02-block-flashcard-css',
  );
  register_block_type( 'learning-aid/grcms02-block-flashcard', $args);
}

/**
 * Allowed Gutenberg block types for the new custom post types
 */
add_action('allowed_block_types', 'grcms02_allowed_block_types', 10, 2);
function grcms02_allowed_block_types($allowed_block_types, $post)
{
  if ($post->post_type == 'course') {
    return array(
      'learning-aid/grcms02-block-ytvideo',
      'learning-aid/grcms02-block-flashcard',
    );
  } else {
    return $allowed_block_types;
  }
}
