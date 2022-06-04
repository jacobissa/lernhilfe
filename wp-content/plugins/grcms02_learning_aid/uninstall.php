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
//wp_die(__('Uninstall stopped before cleaning database for debugging purposes.', 'grcms02_learning_aid'));


/**
 * Clean up database:
 * Delete all taxonomies 'teacher'.
 * !! IMPORTANT !!
 * get_terms() & wp_delete_term() fail in uninstall.php
 * Because the plugin is already deactivated by the time uninstall.php fires.
 * The custom taxonomy that the plugin created is not valid.
 * Since these functions require a valid taxonomy, they do not work.
 * After a lot of experiments and research, the only solution I could barely find 
 * is to re-register the taxonomy in uninstall.php before calling these functions.
 */
register_taxonomy('teacher', 'course', array());
$terms_query = get_terms(array(
    'taxonomy' => 'teacher',
    'hide_empty' => false
));
foreach ($terms_query as $term)
{
    wp_delete_term($term->term_id, 'teacher');
}

/**
 * Clean up database:
 * Delete all post types 'course' & 'lesson' with their post meta.
 */
$posts_query = get_posts(array(
    'numberposts' => -1,
    'post_type' => array('course', 'lesson'),
    'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
));
foreach ($posts_query as $post)
{
    delete_post_meta($post->ID, 'lesson_course');
    delete_post_meta($post->ID, 'short_name');
    wp_delete_post($post->ID, true);
}


/**
 * Just for debugging, stop before deleting files
 */
wp_die(__('Uninstall stopped before deleting files for debugging purposes.', 'grcms02_learning_aid'));
