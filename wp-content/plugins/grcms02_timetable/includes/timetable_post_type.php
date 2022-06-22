<?php

/**
 * Register custom post type 'timetable'.
 * @Hook init
 */
function timetable_register_post_type_timetable()
{
	$icon = file_get_contents(TIMETABLE_PLUGIN_LOCATION_DIR . '/images/icon_timetable.svg');
	$labels = array(
		'name' => __('Timetables', TIMETABLE_DOMAIN),
		'singular_name' => __('Timetable', TIMETABLE_DOMAIN),
		'add_new' => __('Add New Timetable', TIMETABLE_DOMAIN),
		'add_new_item' => __('Add New Timetable', TIMETABLE_DOMAIN),
		'edit_item' => __('Edit Timetable', TIMETABLE_DOMAIN),
		'new_item' => __('New Timetable', TIMETABLE_DOMAIN),
		'view_item' => __('View Timetable', TIMETABLE_DOMAIN),
		'view_items' => __('View Timetables', TIMETABLE_DOMAIN),
		'search_items' => __('Search Timetables', TIMETABLE_DOMAIN),
		'not_found' => __('No Timetables found.', TIMETABLE_DOMAIN),
		'not_found_in_trash' => __('No Timetables found in Trash.', TIMETABLE_DOMAIN),
		'all_items' => __('All Timetables', TIMETABLE_DOMAIN),
		'archives' => __('Timetable Archives', TIMETABLE_DOMAIN),
		'attributes' => __('Timetable Attributes', TIMETABLE_DOMAIN),
		'insert_into_item' => __('Insert into Timetable', TIMETABLE_DOMAIN),
		'uploaded_to_this_item' => __('Uploaded to this Timetable', TIMETABLE_DOMAIN),
		'filter_items_list' => __('Filter Timetables list', TIMETABLE_DOMAIN),
		'items_list_navigation' => __('Timetables list navigation', TIMETABLE_DOMAIN),
		'items_list' => __('Timetables list', TIMETABLE_DOMAIN),
		'item_published' => __('Timetable published.', TIMETABLE_DOMAIN),
		'item_published_privately' => __('Timetable published privately.', TIMETABLE_DOMAIN),
		'item_reverted_to_draft' => __('Timetable reverted to draft', TIMETABLE_DOMAIN),
		'item_scheduled' => __('Timetable scheduled.', TIMETABLE_DOMAIN),
		'item_updated' => __('Timetable updated.', TIMETABLE_DOMAIN),
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Here are the Timetables',
		'public' => true,
		'has_archive' => true,
		'show_in_rest' => false,
		'supports' => ['title'],
		'rewrite' => array('slug' => 'timetable'),
		"menu_icon" => 'data:image/svg+xml;base64,' . base64_encode($icon),
	);
	register_post_type('timetable', $args);
}
add_action('init', 'timetable_register_post_type_timetable');


/**
 * Register the meta 'timetable_course' for the post type 'timetable'.
 * @Hook init
 */
function timetable_register_post_meta_timetable()
{
	register_post_meta(
		'timetable',
		TIMETABLE_META_COURSE,
		[
			'description' => 'The course of this time slot.',
			'single' => true,
			'type' => 'array',
			'show_in_rest' => array(
				'schema' => array(
					'day1' => array(
						'time1' => 'string',
						'time2' => 'string',
						'time3' => 'string',
						'time4' => 'string',
						'time5' => 'string',
						'time6' => 'string',
					),
					'day2' => array(
						'time1' => 'string',
						'time2' => 'string',
						'time3' => 'string',
						'time4' => 'string',
						'time5' => 'string',
						'time6' => 'string',
					),
					'day3' => array(
						'time1' => 'string',
						'time2' => 'string',
						'time3' => 'string',
						'time4' => 'string',
						'time5' => 'string',
						'time6' => 'string',
					),
					'day4' => array(
						'time1' => 'string',
						'time2' => 'string',
						'time3' => 'string',
						'time4' => 'string',
						'time5' => 'string',
						'time6' => 'string',
					),
					'day5' => array(
						'time1' => 'string',
						'time2' => 'string',
						'time3' => 'string',
						'time4' => 'string',
						'time5' => 'string',
						'time6' => 'string',
					),
				),
			),
			'sanitize_callback' => 'timetable_sanitize_post_meta_timetable',
		]
	);

	/**
	 * Sanitize the meta 'timetable_course' for the post type 'timetable'.
	 */
	function timetable_sanitize_post_meta_timetable($meta_value, $meta_key, $meta_type)
	{
		$args = array(
			'post_type' => 'course',
		);
		$query_course = new WP_Query($args);
		global $post;
		while ($query_course->have_posts())
		{
			$query_course->the_post();
			if ($meta_value === $post->post_name)
			{
				return $meta_value;
			}
		}
		wp_reset_query();
		return '';
	}
}
add_action('init', 'timetable_register_post_meta_timetable');


/**
 * Add a meta box to the post type 'timetable' in the admin area.
 * @Hook admin_init
 */
function timetable_add_meta_box_timetable()
{
	add_meta_box(
		'timetable_meta_box',
		__('Timetable details', TIMETABLE_DOMAIN),
		'timetable_fill_meta_box_content',
		'timetable'
	);
}

/**
 * Fill the meta box with a table of dropdown menus to select the post types 'course'
 */
function timetable_fill_meta_box_content($post)
{
	$meta_value = get_post_meta($post->ID, TIMETABLE_META_COURSE, true);
	$array_day = array('day1', 'day2', 'day3', 'day4', 'day5');
	$array_time = array('time1', 'time2', 'time3', 'time4', 'time5', 'time6');
	$option_time = get_option(TIMETABLE_OPTION_TIMESLOT, $array_time);
	echo '<table class="table-timetable">';
	echo '<tr>';
	echo '<th></th>';
	echo '<th>' . $option_time['time1'] . '</th>';
	echo '<th>' . $option_time['time2'] . '</th>';
	echo '<th>' . $option_time['time3'] . '</th>';
	echo '<th>' . $option_time['time4'] . '</th>';
	echo '<th>' . $option_time['time5'] . '</th>';
	echo '<th>' . $option_time['time6'] . '</th>';
	echo '</tr>';
	foreach ($array_day as $day) :
		echo '<tr>';
		echo '<td class="weekday-cell">' . timetable_get_weekday_name($day) . '</td>';
		foreach ($array_time as $time) :
			$meta_key = TIMETABLE_META_COURSE . '[' . $day . '][' . $time . ']';
			echo '<td>';
			echo '<select name="' . $meta_key . '" ';
			echo 'id="' . $meta_key . '">';
			echo '<option value/>';
			$query_course = new WP_Query(array(
				'post_type' => 'course',
				'order' => 'ASC',
				'orderby' => 'title',
			));
			global $post;
			while ($query_course->have_posts()) : $query_course->the_post();
				$course_slug = esc_html($post->post_name);
				$course_title = esc_html($post->post_title);
				$post_short_name = esc_html(get_post_meta($post->ID, LEARNINGAID_META_COURSE_SHORT_NAME, true));
				echo '<option value="' . $course_slug . '"';
				if (is_array($meta_value)) :
					if ($meta_value[$day][$time] == $course_slug) :
						echo ' selected';
					endif;
				endif;
				echo '>' . $post_short_name . '</option>';
			endwhile;
			wp_reset_query();
			echo '</select>';
			echo '</td>';
		endforeach;
		echo '</tr>';
	endforeach;
	echo '</table>';
}
add_action('admin_init', 'timetable_add_meta_box_timetable');


/**
 * Update the meta data once a post type 'timetable' has been saved.
 * @Hook save_post
 */
function timetable_save_post_timetable($post_id)
{
	if (get_post_type($post_id) == 'timetable')
	{
		if (isset($_POST[TIMETABLE_META_COURSE]))
		{
			update_post_meta(
				$post_id,
				TIMETABLE_META_COURSE,
				$_POST[TIMETABLE_META_COURSE]
			);
		}
	}
}
add_action('save_post', 'timetable_save_post_timetable', 10, 1);

/**
 * Add a new column 'author'
 * in the posts list table for the post type 'timetable' in the admin area.
 * @Hook manage_{$post_type}_posts_columns
 */
function timetable_manage_columns_timetable($post_columns)
{
	unset($post_columns['date']);
	$post_columns['author'] = __('Author', TIMETABLE_DOMAIN);
	$post_columns['date'] = __('Date', TIMETABLE_DOMAIN);
	return $post_columns;
}
add_filter('manage_timetable_posts_columns', 'timetable_manage_columns_timetable');


/**
 * Make the new columns 'author' sortable
 * in the posts list table for the post type 'timetable' in the admin area.
 * @Hook manage_edit-{$post_type}_sortable_columns
 */
function timetable_manage_columns_sortable_timetable($post_columns)
{
	$post_columns['author'] = 'author';
	return $post_columns;
}
add_filter('manage_edit-timetable_sortable_columns', 'timetable_manage_columns_sortable_timetable');


/**
 * Assign incoming sorting requests to the meta key.
 * @Hook request
 */
function timetable_request_order_timetable_by_meta_key($query_vars)
{
	if (!is_admin())
	{
		return $query_vars;
	}
	if (isset($query_vars['orderby']) && TIMETABLE_META_COURSE == $query_vars['orderby'])
	{
		$query_vars = array_merge($query_vars, array('meta_key' => TIMETABLE_META_COURSE));
	}
	return $query_vars;
}
add_filter('request', 'timetable_request_order_timetable_by_meta_key');


/**
 * Modify query to include the new post type 'timetable'.
 * @Hook pre_get_posts
 */
function timetable_get_posts_timetable($query)
{
	if (!is_admin() && is_single() && $query->is_main_query())
	{
		$post_type = get_query_var('post_type');
		if (!$post_type)
		{
			if (is_array($post_type))
			{
				$query->set('post_type', array_merge($post_type, array('timetable')));
			}
			else
			{
				$query->set('post_type', array($post_type, 'timetable'));
			}
		}
	}
	return $query;
}
add_action('pre_get_posts', 'timetable_get_posts_timetable');
