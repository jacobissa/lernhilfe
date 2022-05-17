<?php

/**
 * if uninstall.php is not called by WordPress, die
 */
if (!defined('WP_UNINSTALL_PLUGIN'))
{
    die;
}

// Clean up database
$term_query = new WP_Term_Query(array(
    'taxonomy'               => 'teacher',
    'hide_empty'             => false,
));
foreach ($term_query->get_terms() as $term)
{
    wp_delete_term($term->term_id, 'teacher');
}

$post_query = get_posts(array(
    'numberposts' => -1,
    'post_type' => array('course', 'exercise'),
    'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
));
foreach ($post_query as $post)
{
    wp_delete_post($post->ID, true);
}

// Just for debugging, stop before deleting files
wp_die(__('Uninstall stopped for debugging purposes.'));
