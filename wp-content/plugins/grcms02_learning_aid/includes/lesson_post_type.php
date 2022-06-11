<?php

/**
 * Register custom post type 'lesson'.
 * @Hook init
 */
function learningaid_register_post_type_lesson()
{
  $icon = file_get_contents(LEARNINGAID_PLUGIN_LOCATION_DIR . '/images/icon_lesson.svg');
  $labels = array(
    'name' => __('Lessons', LEARNINGAID_DOMAIN),
    'singular_name' => __('Lesson', LEARNINGAID_DOMAIN),
    'add_new' => __('Add New Lesson', LEARNINGAID_DOMAIN),
    'add_new_item' => __('Add New Lesson', LEARNINGAID_DOMAIN),
    'edit_item' => __('Edit Lesson', LEARNINGAID_DOMAIN),
    'new_item' => __('New Lesson', LEARNINGAID_DOMAIN),
    'view_item' => __('View Lesson', LEARNINGAID_DOMAIN),
    'view_items' => __('View Lessons', LEARNINGAID_DOMAIN),
    'search_items' => __('Search Lessons', LEARNINGAID_DOMAIN),
    'not_found' => __('No Lessons found.', LEARNINGAID_DOMAIN),
    'not_found_in_trash' => __('No Lessons found in Trash.', LEARNINGAID_DOMAIN),
    'all_items' => __('All Lessons', LEARNINGAID_DOMAIN),
    'archives' => __('Lesson Archives', LEARNINGAID_DOMAIN),
    'attributes' => __('Lesson Attributes', LEARNINGAID_DOMAIN),
    'insert_into_item' => __('Insert into Lesson', LEARNINGAID_DOMAIN),
    'uploaded_to_this_item' => __('Uploaded to this Lesson', LEARNINGAID_DOMAIN),
    'filter_items_list' => __('Filter Lessons list', LEARNINGAID_DOMAIN),
    'items_list_navigation' => __('Lessons list navigation', LEARNINGAID_DOMAIN),
    'items_list' => __('Lessons list', LEARNINGAID_DOMAIN),
    'item_published' => __('Lesson published.', LEARNINGAID_DOMAIN),
    'item_published_privately' => __('Lesson published privately.', LEARNINGAID_DOMAIN),
    'item_reverted_to_draft' => __('Lesson reverted to draft', LEARNINGAID_DOMAIN),
    'item_scheduled' => __('Lesson scheduled.', LEARNINGAID_DOMAIN),
    'item_updated' => __('Lesson updated.', LEARNINGAID_DOMAIN),
  );
  $args = array(
    'labels' => $labels,
    'description' => 'Here are the Lessons',
    'public' => true,
    'has_archive' => true,
    'show_in_rest' => true,
    'supports' => ['title', 'editor'],
    'rewrite' => array('slug' => 'lesson'),
    "menu_icon" => 'data:image/svg+xml;base64,' . base64_encode($icon),
  );
  register_post_type('lesson', $args);
}
add_action('init', 'learningaid_register_post_type_lesson');


/**
 * Register the meta 'lesson_course' for the post type 'lesson'.
 * @Hook init
 */
function learningaid_register_post_meta_lesson()
{
  register_post_meta(
    'lesson',
    LEARNINGAID_META_LESSON_COURSE,
    [
      'description' => 'The course of this lesson.',
      'show_in_rest' => true,
      'single' => true,
      'type' => 'string',
      'sanitize_callback' => 'learningaid_sanitize_post_meta_lesson',
    ]
  );

  /**
   * Sanitize the meta 'lesson_course' for the post type 'lesson'.
   */
  function learningaid_sanitize_post_meta_lesson($meta_value, $meta_key, $meta_type)
  {
    $query_course = new WP_Query(array(
      'post_type' => 'course',
      'order' => 'ASC',
      'orderby' => 'title',
    ));

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
add_action('init', 'learningaid_register_post_meta_lesson');



/**
 * Add a meta box 'course' to the post type 'lesson' in the admin area.
 * @Hook admin_init
 */
function learningaid_add_meta_box_lesson()
{
  add_meta_box(
    'lesson_meta_box',
    __('Lesson details', LEARNINGAID_DOMAIN),
    'learningaid_fill_meta_box_lesson_content',
    'lesson',
  );

  /**
   * Fill the meta box with dropdown menu to select the post type 'course'
   */
  function learningaid_fill_meta_box_lesson_content($post)
  {
    $course_selected = esc_html(get_post_meta($post->ID, LEARNINGAID_META_LESSON_COURSE, true));
?>
    <table>
      <tr>
        <td style="width: 100%"><?php _e('Course', LEARNINGAID_DOMAIN); ?></td>
        <td>
          <select name="<?php echo LEARNINGAID_META_LESSON_COURSE; ?>" id="<?php echo LEARNINGAID_META_LESSON_COURSE; ?>">
            <option value=""></option>
            <?php
            $query_course = new WP_Query(array(
              'post_type' => 'course',
              'order' => 'ASC',
              'orderby' => 'title',
            ));
            global $post;
            while ($query_course->have_posts()) : $query_course->the_post();
              $post_name = esc_html($post->post_name);
              $post_title = esc_html($post->post_title);
              $post_short_name = esc_html(get_post_meta($post->ID, LEARNINGAID_META_COURSE_SHORT_NAME, true));
              echo '<option value="' . $post_name . '"';
              if ($course_selected == $post_name) :
                echo ' selected';
              endif;
              echo '>' . $post_short_name . '</option>';
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
add_action('admin_init', 'learningaid_add_meta_box_lesson');


/**
 * Update the meta data once a post type 'lesson' has been saved.
 * @Hook save_post
 */
function learningaid_save_post_lesson($post_id)
{
  $post_type = get_post_type($post_id);
  if ($post_type == 'lesson')
  {
    if (isset($_POST[LEARNINGAID_META_LESSON_COURSE]))
    {
      update_post_meta(
        $post_id,
        LEARNINGAID_META_LESSON_COURSE,
        $_POST[LEARNINGAID_META_LESSON_COURSE]
      );
    }
  }
}
add_action('save_post', 'learningaid_save_post_lesson', 10, 1);


/*
 * Add a new column 'course'
 * in the posts list table for the post type 'lesson' in the admin area.
 * @Hook manage_{$post_type}_posts_columns
 */
function learningaid_manage_columns_lesson($post_columns)
{
  unset($post_columns['date']);
  $post_columns[LEARNINGAID_META_LESSON_COURSE] = __('Course', LEARNINGAID_DOMAIN);
  $post_columns['date'] = __('Date', LEARNINGAID_DOMAIN);
  return $post_columns;
}
add_filter('manage_lesson_posts_columns', 'learningaid_manage_columns_lesson');


/**
 * Fill the new custom column 'course'
 * in the posts list table for the post type 'lesson' in the admin area.
 * @Hook manage_{$post->post_type}_posts_custom_column
 */
function learningaid_manage_custom_column_lesson($column_name, $post_id)
{
  if ($column_name == LEARNINGAID_META_LESSON_COURSE)
  {
    $course_slug = esc_html(get_post_meta($post_id, LEARNINGAID_META_LESSON_COURSE, true));
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
add_action('manage_lesson_posts_custom_column', 'learningaid_manage_custom_column_lesson', 10, 2);


/**
 * Make the new column 'course' sortable
 * in the posts list table for the post type 'lesson' in the admin area.
 * @Hook manage_edit-{$post_type}_sortable_columns
 */
function learningaid_manage_columns_sortable_lesson($post_columns)
{
  $post_columns[LEARNINGAID_META_LESSON_COURSE] = LEARNINGAID_META_LESSON_COURSE;
  return $post_columns;
}
add_filter('manage_edit-lesson_sortable_columns', 'learningaid_manage_columns_sortable_lesson');


/**
 * Assign incoming sorting requests to the meta key.
 * @Hook request
 */
function learningaid_request_order_lesson_by_meta_key($query_vars)
{
  if (is_admin())
  {
    if (isset($query_vars['orderby']) && LEARNINGAID_META_LESSON_COURSE == $query_vars['orderby'])
    {
      $query_vars = array_merge($query_vars, array('meta_key' => LEARNINGAID_META_LESSON_COURSE));
    }
  }
  return $query_vars;
}
add_filter('request', 'learningaid_request_order_lesson_by_meta_key');


/**
 * Modify query to include the new post types 'course' & 'lesson'.
 * @Hook pre_get_posts
 */
function learningaid_get_posts_course_lesson($query)
{
  if (!is_admin() && is_single() && $query->is_main_query())
  {
    $post_type = get_query_var('post_type');
    if (!$post_type)
    {
      if (is_array($post_type))
      {
        $query->set('post_type', array_merge($post_type, array('course', 'lesson')));
      }
      else
      {
        $query->set('post_type', array($post_type, 'course', 'lesson'));
      }
    }
  }
  return $query;
}
add_action('pre_get_posts', 'learningaid_get_posts_course_lesson');
