<?php

/**
 * Create custom post type course
 */

add_action('init', 'grcms02_register_post_type_course');
function grcms02_register_post_type_course()
{
  $icon = file_get_contents(LEARNINGAID_PLUGIN_LOCATION . '/images/icon_course.svg');
  $labels = array(
    'name' => __('Courses', LEARNINGAID_DOMAIN),
    'singular_name' => __('Course', LEARNINGAID_DOMAIN),
    'add_new' => __('Add New Course', LEARNINGAID_DOMAIN),
    'add_new_item' => __('Add New Course', LEARNINGAID_DOMAIN),
    'edit_item' => __('Edit Course', LEARNINGAID_DOMAIN),
    'new_item' => __('New Course', LEARNINGAID_DOMAIN),
    'view_item' => __('View Course', LEARNINGAID_DOMAIN),
    'view_items' => __('View Courses', LEARNINGAID_DOMAIN),
    'search_items' => __('Search Courses', LEARNINGAID_DOMAIN),
    'not_found' => __('No Courses found.', LEARNINGAID_DOMAIN),
    'not_found_in_trash' => __('No Courses found in Trash.', LEARNINGAID_DOMAIN),
    'all_items' => __('All Courses', LEARNINGAID_DOMAIN),
    'archives' => __('Course Archives', LEARNINGAID_DOMAIN),
    'attributes' => __('Course Attributes', LEARNINGAID_DOMAIN),
    'insert_into_item' => __('Insert into Course', LEARNINGAID_DOMAIN),
    'uploaded_to_this_item' => __('Uploaded to this Course', LEARNINGAID_DOMAIN),
    'filter_items_list' => __('Filter Courses list', LEARNINGAID_DOMAIN),
    'items_list_navigation' => __('Courses list navigation', LEARNINGAID_DOMAIN),
    'items_list' => __('Courses list', LEARNINGAID_DOMAIN),
    'item_published' => __('Course published.', LEARNINGAID_DOMAIN),
    'item_published_privately' => __('Course published privately.', LEARNINGAID_DOMAIN),
    'item_reverted_to_draft' => __('Course reverted to draft', LEARNINGAID_DOMAIN),
    'item_scheduled' => __('Course scheduled.', LEARNINGAID_DOMAIN),
    'item_updated' => __('Course updated.', LEARNINGAID_DOMAIN),
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Here are the Courses',
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
    'name' => __('Teachers', LEARNINGAID_DOMAIN),
    'singular_name' => __('Teacher', LEARNINGAID_DOMAIN),
    'search_items' => __('Search Teachers', LEARNINGAID_DOMAIN),
    'popular_items' => __('Popular Teachers', LEARNINGAID_DOMAIN),
    'all_items' => __('All Teachers', LEARNINGAID_DOMAIN),
    'name_field_description' => __('Type the name of the Teacher', LEARNINGAID_DOMAIN),
    'slug_field_description' => __('Type the slug of the Teacher (optional)', LEARNINGAID_DOMAIN),
    'desc_field_description' => __('Type description about the Teacher (optional)', LEARNINGAID_DOMAIN),
    'edit_item' => __('Edit Teacher', LEARNINGAID_DOMAIN),
    'view_item' => __('View Teacher', LEARNINGAID_DOMAIN),
    'update_item' => __('Update Teacher', LEARNINGAID_DOMAIN),
    'add_new_item' => __('Add New Teacher', LEARNINGAID_DOMAIN),
    'new_item_name' => __('New Teacher Name', LEARNINGAID_DOMAIN),
    'separate_items_with_commas' => __('Separate Teachers with commas', LEARNINGAID_DOMAIN),
    'add_or_remove_items' => __('Add or remove Teachers', LEARNINGAID_DOMAIN),
    'choose_from_most_used' => __('Choose from the most used Teachers', LEARNINGAID_DOMAIN),
    'not_found' => __('No Teachers found.', LEARNINGAID_DOMAIN),
    'no_terms' => __('No Teachers', LEARNINGAID_DOMAIN),
    'filter_by_item' => __('Filter by Teacher', LEARNINGAID_DOMAIN),
    'item_link' => __('Teacher Link', LEARNINGAID_DOMAIN),
    'item_link_description' => __('A link to a Teacher', LEARNINGAID_DOMAIN),
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Here are the Teachers',
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
  elseif ('course' == $screen->post_type || 'exercise' == $screen->post_type) :
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
  $columns['author'] = __('Author', LEARNINGAID_DOMAIN);
  $columns['date'] = __('Date', LEARNINGAID_DOMAIN);
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
      'show_option_all' => __("All Teachers", LEARNINGAID_DOMAIN),
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
