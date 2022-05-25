<?php

/**
 * Create custom post type exercise
 */

add_action('init', 'grcms02_register_post_type_exercise');
function grcms02_register_post_type_exercise()
{
  $icon = file_get_contents(LEARNINGAID_PLUGIN_LOCATION . '/images/icon_exercise.svg');
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

add_action('init', 'grcms02_register_post_meta_exercise');
function grcms02_register_post_meta_exercise()
{
  register_post_meta(
    'exercise',
    META_EXERCISE_COURSE,
    [
      'description' => 'The course of this exercise.',
      'show_in_rest' => true,
      'single' => true,
      'type' => 'string',
      'sanitize_callback' => 'sanitize_meta_exercise_course',
    ]
  );
  /**
   * Sanitize custom field exercise course.
   */
  function sanitize_meta_exercise_course($meta_value, $meta_key, $meta_type)
  {
    $args = array(
      'post_type' => 'course',
    );
    $query_course = new \WP_Query($args);

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

// Make custom field accessible in admin area.
add_action('admin_init', function ()
{
  add_meta_box(
    'exercise_meta_box',
    __('Exercise details', LEARNINGAID_DOMAIN),
    'print_exercise_meta_box',
    'exercise'
  );
});

function get_course_data_by_slug(string $slug, &$title, &$url, &$id)
{
  $return_success = false;
  $args = array(
    'post_type' => 'course',
    'name' => $slug,
    "posts_per_page" => 1,
  );
  $query_course = new WP_Query($args);
  global $post;
  while ($query_course->have_posts()) : $query_course->the_post();
    if (esc_html($post->post_name) == $slug) :
      $title = esc_html($post->post_title);
      $url = get_post_permalink($post->ID);
      $id = $post->ID;
      $return_success = true;
      break;
    endif;
  endwhile;
  wp_reset_query();
  return $return_success;
}

function print_exercise_meta_box($post)
{
  $course_selected = esc_html(get_post_meta($post->ID, META_EXERCISE_COURSE, true));
?>
  <table>
    <tr>
      <td style="width: 100%"><?php _e('Course', LEARNINGAID_DOMAIN); ?></td>
      <td>
        <select name="<?php echo META_EXERCISE_COURSE; ?>" id="<?php echo META_EXERCISE_COURSE; ?>">
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

add_action('save_post', 'grcms02_save_post_exercise', 10, 3);
function grcms02_save_post_exercise($post_id, $post, $update)
{
  $post_type = get_post_type($post_id);
  if ($post_type == 'exercise')
  {
    if (isset($_POST[META_EXERCISE_COURSE]))
    {
      update_post_meta(
        $post_id,
        META_EXERCISE_COURSE,
        $_POST[META_EXERCISE_COURSE]
      );
    }
  }
}

/* --------------------------------------------------
 * Extend sorting based on cpt 'course' in admin area.
 * -------------------------------------------------- */

add_filter('manage_exercise_posts_columns', function ($columns)
{
  unset($columns['date']);
  $columns[META_EXERCISE_COURSE] = __('Course', LEARNINGAID_DOMAIN);
  $columns['author'] = __('Author', LEARNINGAID_DOMAIN);
  $columns['date'] = __('Date', LEARNINGAID_DOMAIN);
  return $columns;
});

add_action('manage_posts_custom_column', function ($column)
{
  if ($column == META_EXERCISE_COURSE)
  {
    $course_slug = esc_html(get_post_meta(get_the_ID(), META_EXERCISE_COURSE, true));
    if (get_course_data_by_slug($course_slug, $course_title, $course_url, $course_id)) :
      echo '<a href="' . $course_url . '">' . $course_title . ' ' . '</a>';
    endif;
  }
});

add_filter('manage_edit-exercise_sortable_columns', 'grcms02_exercise_sortable_columns');
function grcms02_exercise_sortable_columns($columns)
{
  $columns[META_EXERCISE_COURSE] = META_EXERCISE_COURSE;
  $columns['author'] = 'author';
  return $columns;
}

add_filter('request', function ($vars)
{
  if (!is_admin())
  {
    return $vars;
  }
  if (isset($vars['orderby']) && META_EXERCISE_COURSE == $vars['orderby'])
  {
    $vars = array_merge($vars, array('meta_key' => META_EXERCISE_COURSE));
  }
  return $vars;
});

add_action('pre_get_posts', function ($query)
{
  if (!is_admin() && is_single() && $query->is_main_query())
  {
    $post_type = get_query_var('post_type');
    if (!$post_type)
    {
      if (is_array($post_type))
      {
        $query->set('post_type', array_merge($post_type, array('exercise', 'course')));
      }
      else
      {
        $query->set('post_type', array($post_type, 'exercise', 'course'));
      }
    }
  }
  return $query;
});
