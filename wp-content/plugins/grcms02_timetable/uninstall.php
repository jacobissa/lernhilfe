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
wp_die(__('Uninstall stopped before cleaning database for debugging purposes.', 'grcms02_timetable'));


/**
 * Clean up database.
 * Delete Option 'timetable_timeslots'.
 * Delete all post type 'timetable' with its post meta
 */
delete_option('timetable_timeslots');

$post_query = get_posts(array(
	'numberposts' => -1,
	'post_type' => 'timetable',
	'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'),
));
foreach ($post_query as $post)
{
	delete_post_meta($post->ID, 'timetable_course');
	wp_delete_post($post->ID, true);
}

/**
 * Just for debugging, stop before deleting files
 */
wp_die(__('Uninstall stopped before deleting files for debugging purposes.', 'grcms02_timetable'));
