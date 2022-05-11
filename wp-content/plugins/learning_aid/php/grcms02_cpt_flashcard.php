<?php

/**
 * Create custom post type flashcard
 */

add_action('init', 'grcms02_register_post_type_flashcard');
function grcms02_register_post_type_flashcard()
{
 $icon = file_get_contents(PLUGIN_LOCATION . '/assets/icon_flashcard.svg');
 $labels = array(
  'name' => __('Flashcards', DOMAIN),
  'singular_name' => __('Flashcard', DOMAIN),
  'add_new' => __('Add New Flashcard', DOMAIN),
  'add_new_item' => __('Add New Flashcard', DOMAIN),
  'edit_item' => __('Edit Flashcard', DOMAIN),
  'new_item' => __('New Flashcard', DOMAIN),
  'view_item' => __('View Flashcard', DOMAIN),
  'view_items' => __('View Flashcards', DOMAIN),
  'search_items' => __('Search Flashcards', DOMAIN),
  'not_found' => __('No Flashcards found.', DOMAIN),
  'not_found_in_trash' => __('No Flashcards found in Trash.', DOMAIN),
  'all_items' => __('All Flashcards', DOMAIN),
  'archives' => __('Flashcard Archives', DOMAIN),
  'attributes' => __('Flashcard Attributes', DOMAIN),
  'insert_into_item' => __('Insert into Flashcard', DOMAIN),
  'uploaded_to_this_item' => __('Uploaded to this Flashcard', DOMAIN),
  'filter_items_list' => __('Filter Flashcards list', DOMAIN),
  'items_list_navigation' => __('Flashcards list navigation', DOMAIN),
  'items_list' => __('Flashcards list', DOMAIN),
  'item_published' => __('Flashcard published.', DOMAIN),
  'item_published_privately' => __('Flashcard published privately.', DOMAIN),
  'item_reverted_to_draft' => __('Flashcard reverted to draft', DOMAIN),
  'item_scheduled' => __('Flashcard scheduled.', DOMAIN),
  'item_updated' => __('Flashcard updated.', DOMAIN),
 );
 $args = array(
  'labels' => $labels,
  'description' => 'Here are the Flashcards',
  'public' => true,
  'has_archive' => true,
  'show_in_rest' => true,
  'supports' => ['title', 'editor'],
  'rewrite' => array('slug' => 'flashcard'),
  "menu_icon" => 'data:image/svg+xml;base64,' . base64_encode($icon),
 );
 register_post_type('flashcard', $args);

}

add_action('init', 'grcms02_register_post_meta_flashcard');
function grcms02_register_post_meta_flashcard()
{
 register_post_meta('flashcard', META_FLASHCARD_COURSE,
  [
   'description' => 'The course of this flashcard.',
   'show_in_rest' => true,
   'single' => true,
   'type' => 'string',
   'sanitize_callback' => 'sanitize_meta_flashcard_course',
  ]
 );
 /**
  * Sanitize custom field flashcard course.
  */
 function sanitize_meta_flashcard_course($meta_value, $meta_key, $meta_type)
 {
  $args = array(
   'post_type' => 'course',
  );
  $query_course = new \WP_Query($args);

  global $post;
  while ($query_course->have_posts()) {
   $query_course->the_post();
   if ($meta_value === $post->post_name) {
    return $meta_value;
   }

  }
  return '';
 }
}

// Make custom field accessible in admin area.
add_action('admin_init', function () {
 add_meta_box('flashcard_meta_box',
  __('Flashcard details', DOMAIN),
  'print_flashcard_meta_box',
  'flashcard'
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
 while ($query_course->have_posts()): $query_course->the_post();
  if (esc_html($post->post_name) == $slug):
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

function print_flashcard_meta_box($post)
{
 $course_selected = esc_html(get_post_meta($post->ID, META_FLASHCARD_COURSE, true));
 ?>
<table>
	<tr>
		<td style="width: 100%"><?php _e('Course', DOMAIN); ?></td>
		<td>
			<select name="<?php echo META_FLASHCARD_COURSE; ?>"
					id="<?php echo META_FLASHCARD_COURSE; ?>">
					<option value=""></option>
<?php
$args = array(
  'post_type' => 'course',
 );
 $query_course = new WP_Query($args);
 global $post;
 while ($query_course->have_posts()): $query_course->the_post();
  $post_name = esc_html($post->post_name);
  $post_title = esc_html($post->post_title);
  echo '<option value="' . $post_name . '"';
  if ($course_selected == $post_name):
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

add_action('save_post', 'grcms02_save_post_flashcard', 10, 3);
function grcms02_save_post_flashcard($post_id, $post, $update)
{
 $post_type = get_post_type($post_id);
 if ($post_type == 'flashcard') {
  if (isset($_POST[META_FLASHCARD_COURSE])) {
   update_post_meta(
    $post_id,
    META_FLASHCARD_COURSE,
    $_POST[META_FLASHCARD_COURSE]
   );
  }
 }
}

/* --------------------------------------------------
 * Extend sorting based on cpt 'course' in admin area.
 * -------------------------------------------------- */

add_filter('manage_flashcard_posts_columns', function ($columns) {
 unset($columns['date']);
 $columns[META_FLASHCARD_COURSE] = __('Course', DOMAIN);
 $columns['author'] = __('Author', DOMAIN);
 $columns['date'] = __('Date', DOMAIN);
 return $columns;
});

add_action('manage_posts_custom_column', function ($column) {
 if ($column == META_FLASHCARD_COURSE) {
  $course_slug = esc_html(get_post_meta(get_the_ID(), META_FLASHCARD_COURSE, true));
  if (get_course_data_by_slug($course_slug, $course_title, $course_url, $course_id)):
   echo '<a href="' . $course_url . '">' . $course_title . ' ' . '</a>';
  endif;
 }
});

add_filter('manage_edit-flashcard_sortable_columns', 'grcms02_flashcard_sortable_columns');
function grcms02_flashcard_sortable_columns($columns)
{
 $columns[META_FLASHCARD_COURSE] = META_FLASHCARD_COURSE;
 $columns['author'] = 'author';
 return $columns;
}

add_filter('request', function ($vars) {
 if (!is_admin()) {
  return $vars;
 }
 if (isset($vars['orderby']) && META_FLASHCARD_COURSE == $vars['orderby']) {
  $vars = array_merge($vars, array('meta_key' => META_FLASHCARD_COURSE));
 }
 return $vars;
});

add_action('pre_get_posts', function ($query) {
 if (!is_admin() && is_single() && $query->is_main_query()) {
  $post_type = get_query_var('post_type');
  if (!$post_type) {
   if (is_array($post_type)) {
    // Current query specifies post types, add the new ones
    $query->set('post_type', array_merge($post_type, array('flashcard', 'course')));
   } else {
    // Current query does not specify post types, assign the new ones
    $query->set('post_type', array($post_type, 'flashcard', 'course'));
   }
  }
 }
 return $query;
});