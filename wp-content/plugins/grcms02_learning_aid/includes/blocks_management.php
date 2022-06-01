<?php


/**
 * Load gutenberg blocks (ytvideo & textquestion)
 */
require_once(LEARNINGAID_PLUGIN_LOCATION_DIR . '/blocks/ytvideo/block-ytvideo.php');
require_once(LEARNINGAID_PLUGIN_LOCATION_DIR . '/blocks/textquestion/block-textquestion.php');


/**
 * Allow only the gutenberg blocks ('ytvideo', 'testquestion', 'shortcode') in the post type 'lesson';
 * all other blocks will be removed in this post type.
 * @Hook 'allowed_block_types'
 */
function learningaid_allowed_block_types($allowed_block_types, $post)
{
    if ($post->post_type === 'lesson') :
        return array('learning-aid/block-textquestion', 'learning-aid/block-ytvideo', 'core/shortcode');
    else :
        return $allowed_block_types;
    endif;
}
add_action('allowed_block_types', 'learningaid_allowed_block_types', 10, 2);
