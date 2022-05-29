<?php


/**
 * Load gutenberg blocks (ytvideo & textquestion)
 */
require_once(LEARNINGAID_PLUGIN_LOCATION_DIR . '/blocks/ytvideo/block-ytvideo.php');
require_once(LEARNINGAID_PLUGIN_LOCATION_DIR . '/blocks/textquestion/block-textquestion.php');


/**
 * Allow only the gutenberg block 'ytvideo' in the post type 'course';
 * and only the block 'testquestion' in the post type 'exercise';
 * all other blocks will be removed in these two post types.
 * @Hook 'allowed_block_types'
 */
function learningaid_allowed_block_types($allowed_block_types, $post)
{
    if ($post->post_type == 'course') :
        return array('learning-aid/block-ytvideo');
    elseif ($post->post_type == 'exercise') :
        return array('learning-aid/block-textquestion');
    else :
        return $allowed_block_types;
    endif;
}
add_action('allowed_block_types', 'learningaid_allowed_block_types', 10, 2);
