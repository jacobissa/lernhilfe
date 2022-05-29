<?php

/**
 * Register custom post type 'exercise'.
 * @Hook init
 */
function learningaid_register_post_type_exercise()
{
  $icon = file_get_contents(LEARNINGAID_PLUGIN_LOCATION_DIR . '/images/icon_exercise.svg');
  $labels = array(
    'name' => __('Exercises', LEARNINGAID_DOMAIN),
    'singular_name' => __('Exercise', LEARNINGAID_DOMAIN),
    'add_new' => __('Add New Exercise', LEARNINGAID_DOMAIN),
    'add_new_item' => __('Add New Exercise', LEARNINGAID_DOMAIN),
    'edit_item' => __('Edit Exercise', LEARNINGAID_DOMAIN),
    'new_item' => __('New Exercise', LEARNINGAID_DOMAIN),
    'view_item' => __('View Exercise', LEARNINGAID_DOMAIN),
    'view_items' => __('View Exercises', LEARNINGAID_DOMAIN),
    'search_items' => __('Search Exercises', LEARNINGAID_DOMAIN),
    'not_found' => __('No Exercises found.', LEARNINGAID_DOMAIN),
    'not_found_in_trash' => __('No Exercises found in Trash.', LEARNINGAID_DOMAIN),
    'all_items' => __('All Exercises', LEARNINGAID_DOMAIN),
    'archives' => __('Exercise Archives', LEARNINGAID_DOMAIN),
    'attributes' => __('Exercise Attributes', LEARNINGAID_DOMAIN),
    'insert_into_item' => __('Insert into Exercise', LEARNINGAID_DOMAIN),
    'uploaded_to_this_item' => __('Uploaded to this Exercise', LEARNINGAID_DOMAIN),
    'filter_items_list' => __('Filter Exercises list', LEARNINGAID_DOMAIN),
    'items_list_navigation' => __('Exercises list navigation', LEARNINGAID_DOMAIN),
    'items_list' => __('Exercises list', LEARNINGAID_DOMAIN),
    'item_published' => __('Exercise published.', LEARNINGAID_DOMAIN),
    'item_published_privately' => __('Exercise published privately.', LEARNINGAID_DOMAIN),
    'item_reverted_to_draft' => __('Exercise reverted to draft', LEARNINGAID_DOMAIN),
    'item_scheduled' => __('Exercise scheduled.', LEARNINGAID_DOMAIN),
    'item_updated' => __('Exercise updated.', LEARNINGAID_DOMAIN),
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Here are the Exercises',
    'public' => true,
    'has_archive' => true,
    'show_in_rest' => true,
    'supports' => ['title', 'editor'],
    'rewrite' => array('slug' => 'exercise'),
    "menu_icon" => 'data:image/svg+xml;base64,' . base64_encode($icon),
  );
  register_post_type('exercise', $args);
}
add_action('init', 'learningaid_register_post_type_exercise');


/**
 * Register the meta 'exercise_course' for the post type 'exercise'.
 * @Hook init
 */
function learningaid_register_post_meta_exercise()
{
  register_post_meta(
    'exercise',
    LEARNINGAID_META_EXERCISE_COURSE,
    [
      'description' => 'The course of this exercise.',
      'show_in_rest' => true,
      'single' => true,
      'type' => 'string',
      'sanitize_callback' => 'learningaid_sanitize_post_meta_exercise',
    ]
  );

  /**
   * Sanitize the meta 'exercise_course' for the post type 'exercise'.
   */
  function learningaid_sanitize_post_meta_exercise($meta_value, $meta_key, $meta_type)
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
    return '';
  }
}
add_action('init', 'learningaid_register_post_meta_exercise');



/**
 * Add a meta box to the post type 'exercise' in the admin area.
 * @Hook admin_init
 */
function learningaid_add_meta_box_exercise()
{
  add_meta_box(
    'exercise_meta_box',
    __('Exercise details', LEARNINGAID_DOMAIN),
    'learningaid_fill_meta_box_content',
    'exercise'
  );

  /**
   * Fill the meta box with dropdown menu to select the post type 'course'
   */
  function learningaid_fill_meta_box_content($post)
  {
    $course_selected = esc_html(get_post_meta($post->ID, LEARNINGAID_META_EXERCISE_COURSE, true));
?>
    <table>
      <tr>
        <td style="width: 100%"><?php _e('Course', LEARNINGAID_DOMAIN); ?></td>
        <td>
          <select name="<?php echo LEARNINGAID_META_EXERCISE_COURSE; ?>" id="<?php echo LEARNINGAID_META_EXERCISE_COURSE; ?>">
            <option value=""></option>
            <?php
            $args = array(
              'post_type' => 'course',
            );
            $query_course = new WP_Query($args);
            global $post;
            while ($query_course->have_posts()) : $query_course->the_post();
              $post_name = esc_html($post->post_name);
              $post_title = esc_html($post->post_title);
              echo '<option value="' . $post_name . '"';
              if ($course_selected == $post_name) :
                echo ' selected';
              endif;
              echo '>' . $post_title . '</option>';
            endwhile;
            wp_reset_query();
            ?>
          </select>
        </td>
      </tr>
    </table>
<?php
  }
}
add_action('admin_init', 'learningaid_add_meta_box_exercise');


/**
 * Update the meta data once a post type 'exercise' has been saved.
 * @Hook save_post
 */
function learningaid_save_post_exercise($post_id)
{
  $post_type = get_post_type($post_id);
  if ($post_type == 'exercise')
  {
    if (isset($_POST[LEARNINGAID_META_EXERCISE_COURSE]))
    {
      update_post_meta(
        $post_id,
        LEARNINGAID_META_EXERCISE_COURSE,
        $_POST[LEARNINGAID_META_EXERCISE_COURSE]
      );
    }
  }
}
add_action('save_post', 'learningaid_save_post_exercise', 10, 1);


/*
 * Add a new columns 'course' & 'author'
 * in the posts list table for the post type 'exercise' in the admin area.
 * @Hook manage_{$post_type}_posts_columns
 */
function learningaid_manage_columns_exercise($post_columns)
{
  unset($post_columns['date']);
  $post_columns[LEARNINGAID_META_EXERCISE_COURSE] = __('Course', LEARNINGAID_DOMAIN);
  $post_columns['author'] = __('Author', LEARNINGAID_DOMAIN);
  $post_columns['date'] = __('Date', LEARNINGAID_DOMAIN);
  return $post_columns;
}
add_filter('manage_exercise_posts_columns', 'learningaid_manage_columns_exercise');


/**
 * Fill the new custom column 'course'
 * in the posts list table for the post type 'exercise' in the admin area.
 * @Hook manage_{$post->post_type}_posts_custom_column
 */
function learningaid_manage_custom_column_exercise($column_name, $post_id)
{
  if ($column_name == LEARNINGAID_META_EXERCISE_COURSE)
  {
    $course_slug = esc_html(get_post_meta($post_id, LEARNINGAID_META_EXERCISE_COURSE, true));
    $query_course = new WP_Query(array(
      'post_type' => 'course',
      'name' => $course_slug,
      "posts_per_page" => 1,
    ));
    global $post;
    while ($query_course->have_posts()) : $query_course->the_post();
      if (esc_html($post->post_name) === $course_slug) :
        echo '<a href="' . get_post_permalink($post->ID) . '">' . esc_html($post->post_title) . ' ' . '</a>';
        break;
      endif;
    endwhile;
    wp_reset_query();
  }
}
add_action('manage_exercise_posts_custom_column', 'learningaid_manage_custom_column_exercise', 10, 2);


/**
 * Make the new columns 'course' & 'author' sortable
 * in the posts list table for the post type 'exercise' in the admin area.
 * @Hook manage_edit-{$post_type}_sortable_columns
 */
function learningaid_manage_columns_sortable_exercise($post_columns)
{
  $post_columns[LEARNINGAID_META_EXERCISE_COURSE] = LEARNINGAID_META_EXERCISE_COURSE;
  $post_columns['author'] = 'author';
  return $post_columns;
}
add_filter('manage_edit-exercise_sortable_columns', 'learningaid_manage_columns_sortable_exercise');


/**
 * Assign incoming sorting requests to the meta key.
 * @Hook request
 */
function learningaid_request_order_exercise_by_meta_key($query_vars)
{
  if (is_admin())
  {
    if (isset($query_vars['orderby']) && LEARNINGAID_META_EXERCISE_COURSE == $query_vars['orderby'])
    {
      $query_vars = array_merge($query_vars, array('meta_key' => LEARNINGAID_META_EXERCISE_COURSE));
    }
  }
  return $query_vars;
}
add_filter('request', 'learningaid_request_order_exercise_by_meta_key');


/**
 * Modify query to include the new post types 'course' & 'exercise'.
 * @Hook pre_get_posts
 */
function learningaid_get_posts_course_exercise($query)
{
  if (!is_admin() && is_single() && $query->is_main_query())
  {
    $post_type = get_query_var('post_type');
    if (!$post_type)
    {
      if (is_array($post_type))
      {
        // Current query specifies post types, add the new ones
        $query->set('post_type', array_merge($post_type, array('course', 'exercise')));
      }
      else
      {
        // Current query does not specify post types, assign the new ones
        $query->set('post_type', array($post_type, 'course', 'exercise'));
      }
    }
  }
  return $query;
}
add_action('pre_get_posts', 'learningaid_get_posts_course_exercise');
