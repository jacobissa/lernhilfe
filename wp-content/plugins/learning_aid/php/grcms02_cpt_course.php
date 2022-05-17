<?php

/**
 * Create custom post type course
 */

add_action('init', 'grcms02_register_post_type_course');
function grcms02_register_post_type_course()
{
  $icon = file_get_contents(COURSE_PLUGIN_LOCATION . '/assets/icon_course.svg');
  $labels = array(
    'name' => __('Courses', COURSE_DOMAIN),
    'singular_name' => __('Course', COURSE_DOMAIN),
    'add_new' => __('Add New Course', COURSE_DOMAIN),
    'add_new_item' => __('Add New Course', COURSE_DOMAIN),
    'edit_item' => __('Edit Course', COURSE_DOMAIN),
    'new_item' => __('New Course', COURSE_DOMAIN),
    'view_item' => __('View Course', COURSE_DOMAIN),
    'view_items' => __('View Courses', COURSE_DOMAIN),
    'search_items' => __('Search Courses', COURSE_DOMAIN),
    'not_found' => __('No courses found.', COURSE_DOMAIN),
    'not_found_in_trash' => __('No courses found in Trash.', COURSE_DOMAIN),
    'all_items' => __('All Courses', COURSE_DOMAIN),
    'archives' => __('Course Archives', COURSE_DOMAIN),
    'attributes' => __('Course Attributes', COURSE_DOMAIN),
    'insert_into_item' => __('Insert into course', COURSE_DOMAIN),
    'uploaded_to_this_item' => __('Uploaded to this course', COURSE_DOMAIN),
    'filter_items_list' => __('Filter courses list', COURSE_DOMAIN),
    'items_list_navigation' => __('Courses list navigation', COURSE_DOMAIN),
    'items_list' => __('Courses list', COURSE_DOMAIN),
    'item_published' => __('Course published.', COURSE_DOMAIN),
    'item_published_privately' => __('Course published privately.', COURSE_DOMAIN),
    'item_reverted_to_draft' => __('Course reverted to draft', COURSE_DOMAIN),
    'item_scheduled' => __('Course scheduled.', COURSE_DOMAIN),
    'item_updated' => __('Course updated.', COURSE_DOMAIN),
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
    'name' => __('Teachers', COURSE_DOMAIN),
    'singular_name' => __('Teacher', COURSE_DOMAIN),
    'search_items' => __('Search Teachers', COURSE_DOMAIN),
    'popular_items' => __('Popular Teachers', COURSE_DOMAIN),
    'all_items' => __('All Teachers', COURSE_DOMAIN),
    'name_field_description' => __('Type the name of the teacher', COURSE_DOMAIN),
    'slug_field_description' => __('Type the slug of the teacher (optional)', COURSE_DOMAIN),
    'desc_field_description' => __('Type description about the teacher (optional)', COURSE_DOMAIN),
    'edit_item' => __('Edit Teacher', COURSE_DOMAIN),
    'view_item' => __('View Teacher', COURSE_DOMAIN),
    'update_item' => __('Update Teacher', COURSE_DOMAIN),
    'add_new_item' => __('Add New Teacher', COURSE_DOMAIN),
    'new_item_name' => __('New Teacher Name', COURSE_DOMAIN),
    'separate_items_with_commas' => __('Separate teachers with commas', COURSE_DOMAIN),
    'add_or_remove_items' => __('Add or remove teachers', COURSE_DOMAIN),
    'choose_from_most_used' => __('Choose from the most used teachers', COURSE_DOMAIN),
    'not_found' => __('No teachers found.', COURSE_DOMAIN),
    'no_terms' => __('No teachers', COURSE_DOMAIN),
    'filter_by_item' => __('Filter by teacher', COURSE_DOMAIN),
    'item_link' => __('Teacher Link', COURSE_DOMAIN),
    'item_link_description' => __('A link to a teacher', COURSE_DOMAIN),
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

  if ('teacher' == $screen->taxonomy) :
    $css = ".term-parent-wrap
  			{display:none;}";
  elseif ('course' == $screen->post_type || 'flashcard' == $screen->post_type) :
    $css = ".editor-post-taxonomies__hierarchical-terms-input+.components-base-control,
  			.block-editor-block-inspector__advanced
  			{display:none;}";
  else :
    return;
  endif;

  if (!empty($css))
  {
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
add_filter('manage_course_posts_columns', function ($columns)
{
  unset($columns['date']);
  $columns['author'] = __('Author', COURSE_DOMAIN);
  $columns['date'] = __('Date', COURSE_DOMAIN);
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
add_action('restrict_manage_posts', function ()
{
  global $typenow;
  if ($typenow == 'course')
  {
    wp_dropdown_categories(array(
      'show_option_all' => __("All Teachers", COURSE_DOMAIN),
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
