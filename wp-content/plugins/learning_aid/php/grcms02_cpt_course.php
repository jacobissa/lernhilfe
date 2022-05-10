<?php

/**
 * Create custom post type course
 */

add_action('init', 'grcms02_register_post_type_course');
function grcms02_register_post_type_course()
{
  $icon = file_get_contents(PLUGIN_LOCATION . '/assets/icon_book.svg');
  $labels = array(
    'name' => __('Courses', DOMAIN),
    'singular_name' => __('Course', DOMAIN),
    'add_new' => __('Add New Course', DOMAIN),
    'add_new_item' => __('Add New Course', DOMAIN),
    'edit_item' => __('Edit Course', DOMAIN),
    'new_item' => __('New Course', DOMAIN),
    'view_item' => __('View Course', DOMAIN),
    'view_items' => __('View Courses', DOMAIN),
    'search_items' => __('Search Courses', DOMAIN),
    'not_found' => __('No courses found.', DOMAIN),
    'not_found_in_trash' => __('No courses found in Trash.', DOMAIN),
    'all_items' => __('All Courses', DOMAIN),
    'archives' => __('Course Archives', DOMAIN),
    'attributes' => __('Course Attributes', DOMAIN),
    'insert_into_item' => __('Insert into course', DOMAIN),
    'uploaded_to_this_item' => __('Uploaded to this course', DOMAIN),
    'filter_items_list' => __('Filter courses list', DOMAIN),
    'items_list_navigation' => __('Courses list navigation', DOMAIN),
    'items_list' => __('Courses list', DOMAIN),
    'item_published' => __('Course published.', DOMAIN),
    'item_published_privately' => __('Course published privately.', DOMAIN),
    'item_reverted_to_draft' => __('Course reverted to draft', DOMAIN),
    'item_scheduled' => __('Course scheduled.', DOMAIN),
    'item_updated' => __('Course updated.', DOMAIN),
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Here are the courses',
    'public' => true,
    'has_archive' => true,
    'show_in_rest' => true,
    'supports' => ['title', 'editor'],
    'rewrite' => array('slug' => 'course'),
    "menu_icon" => 'data:image/svg+xml;base64,' . base64_encode($icon),
  );
  register_post_type('course', $args);
}

/**
 * Create custom taxonomy teachers
 */
add_action('init', 'grcms02_register_taxonomy_teacher');
function grcms02_register_taxonomy_teacher()
{
  $labels = array(
    'name' => __('Teachers', DOMAIN),
    'singular_name' => __('Teacher', DOMAIN),
    'search_items' => __('Search Teachers', DOMAIN),
    'popular_items' => __('Popular Teachers', DOMAIN),
    'all_items' => __('All Teachers', DOMAIN),
    'name_field_description' => __('Type the name of the teacher', DOMAIN),
    'slug_field_description' => __('Type the slug of the teacher (optional)', DOMAIN),
    'desc_field_description' => __('Type description about the teacher (optional)', DOMAIN),
    'edit_item' => __('Edit Teacher', DOMAIN),
    'view_item' => __('View Teacher', DOMAIN),
    'update_item' => __('Update Teacher', DOMAIN),
    'add_new_item' => __('Add New Teacher', DOMAIN),
    'new_item_name' => __('New Teacher Name', DOMAIN),
    'separate_items_with_commas' => __('Separate teachers with commas', DOMAIN),
    'add_or_remove_items' => __('Add or remove teachers', DOMAIN),
    'choose_from_most_used' => __('Choose from the most used teachers', DOMAIN),
    'not_found' => __('No teachers found.', DOMAIN),
    'no_terms' => __('No teachers', DOMAIN),
    'filter_by_item' => __('Filter by teacher', DOMAIN),
    'item_link' => __('Teacher Link', DOMAIN),
    'item_link_description' => __('A link to a teacher', DOMAIN),
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Here are the teachers',
    'public' => true,
    'show_in_rest' => true,
    'hierarchical' => true,
    'show_admin_column' => true,
    'meta_box_cb' => 'post_categories_meta_box',
    'rewrite' => ['slug' => 'teacher'],
  );
  register_taxonomy('teacher', 'course', $args);
  register_taxonomy_for_object_type('teacher', 'course');
}

/**
 * Remove "Parent" dropdown for Taxonomy teacher
 * Remove additional css classes from course
 */
add_action('admin_head-edit-tags.php', 'grcms02_remove_unwanted_elements');
add_action('admin_head-term.php', 'grcms02_remove_unwanted_elements');
add_action('admin_head-post.php', 'grcms02_remove_unwanted_elements');
add_action('admin_head-post-new.php', 'grcms02_remove_unwanted_elements');
function grcms02_remove_unwanted_elements()
{
  $screen = get_current_screen();

  if ('teacher' == $screen->taxonomy) {
    $css = ".term-parent-wrap
  			{display:none;}";
  } elseif ('course' == $screen->post_type) {
    $css = ".editor-post-taxonomies__hierarchical-terms-input+.components-base-control,
  			.block-editor-block-inspector__advanced
  			{display:none;}";
  } else {
    return;
  }

  if (!empty($css)) {
    echo '<style type="text/css">';
    echo $css;
    echo '</style>';
  }
}



/* --------------------------------------------------
 * Extend sorting based on texonomy teacher and author in admin area.
 * -------------------------------------------------- */

/*
 * Introduce a new column 'Author'
 */
add_filter('manage_course_posts_columns', function ($columns) {
	unset($columns['date']);
	$columns['author'] = __('Author', DOMAIN);
	$columns['date'] = __('Date', DOMAIN);
	return $columns;
  });
  
  /*
   * Mark the new column 'Author' as sortable.
   */
  add_filter('manage_edit-course_sortable_columns', 'grcms02_course_sortable_columns');
  function grcms02_course_sortable_columns($columns)
  {
	$columns['author'] = 'author';
	return $columns;
  }
  
  /**
   * Add Filter for the column 'Teachers'
   */
  add_action('restrict_manage_posts', function () {
	global $typenow;
	if ($typenow == 'course') {
	  wp_dropdown_categories(array(
		'show_option_all' => __("All Teachers", DOMAIN),
		'taxonomy' => 'teacher',
		'name' => 'teacher',
		'orderby' => 'name',
		'value_field' => 'slug',
		'selected' => 0,
		'hierarchical' => false,
		'depth' => 1,
		'show_count' => true,
		'hide_empty' => true,
	  ));
	}
  });
  

  