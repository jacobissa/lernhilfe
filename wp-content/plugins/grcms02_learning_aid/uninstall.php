<?php

/**
 * if uninstall.php is not called by WordPress, abort
 */
if (!defined('WP_UNINSTALL_PLUGIN'))
{
    die;
}


/**
 * Just for debugging, stopp before cleaning database
 */
wp_die(__('Uninstall stopped before cleaning database for debugging purposes.', 'grcms02_learning_aid'));


/**
 * Clean up database.
 * Delete all taxonomies 'teacher'.
 * Delete all post types 'course' & 'lesson'.
 */
$terms_query = new WP_Term_Query(array(
    'taxonomy' => 'teacher',
    'hide_empty' => false
));
foreach ($terms_query->terms as $term)
{
    wp_delete_term($term->term_id, 'teacher');
}

$posts_query = get_posts(array(
    'numberposts' => -1,
    'post_type' => array('course', 'lesson'),
    'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
));
foreach ($posts_query as $post)
{
    wp_delete_post($post->ID, true);
}


/**
 * Just for debugging, stop before deleting files
 */
wp_die(__('Uninstall stopped before deleting files for debugging purposes.', 'grcms02_learning_aid'));
