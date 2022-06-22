<?php

/**
 * Register custom post type 'course'.
 * @Hook init
 */
function learningaid_register_post_type_course()
{
    $icon = file_get_contents(LEARNINGAID_PLUGIN_LOCATION_DIR . '/images/icon_course.svg');
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
    $post_args = array(
        'labels' => $labels,
        'description' => 'Here are the Courses',
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => ['title'],
        'rewrite' => array('slug' => 'course'),
        "menu_icon" => 'data:image/svg+xml;base64,' . base64_encode($icon),
    );
    register_post_type('course', $post_args);
}

add_action('init', 'learningaid_register_post_type_course');


/**
 * Register taxonomy 'teacher' for the post type 'course'.
 * @Hook init
 */
function learningaid_register_taxonomy_teacher()
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
        'show_in_rest' => false,
        'hierarchical' => true,
        'show_admin_column' => true,
        'meta_box_cb' => 'post_categories_meta_box',
        'rewrite' => ['slug' => 'teacher'],
    );
    register_taxonomy('teacher', 'course', $args);
    register_taxonomy_for_object_type('teacher', 'course');
}
add_action('init', 'learningaid_register_taxonomy_teacher');


/*
 * Add a new columns 'short_name' & 'author' in the posts list table for the post type 'course' in the admin area.
 * @Hook manage_{$post_type}_posts_columns
 */
function learningaid_manage_columns_course($post_columns)
{
    unset($post_columns['date']);
    $post_columns[LEARNINGAID_META_COURSE_SHORT_NAME] = __('Short name', LEARNINGAID_DOMAIN);
    $post_columns['author'] = __('Author', LEARNINGAID_DOMAIN);
    $post_columns['date'] = __('Date', LEARNINGAID_DOMAIN);
    return $post_columns;
}
add_filter('manage_course_posts_columns', 'learningaid_manage_columns_course');

/**
 * Fill the new custom column 'short_name'
 * in the posts list table for the post type 'course' in the admin area.
 * @Hook manage_{$post->post_type}_posts_custom_column
 */
function learningaid_manage_custom_column_course($column_name, $post_id)
{
    if ($column_name == LEARNINGAID_META_COURSE_SHORT_NAME)
    {
        $short_name = get_post_meta($post_id, LEARNINGAID_META_COURSE_SHORT_NAME, true);
        echo '<a href="' . get_post_permalink($post_id) . '">' . esc_html($short_name) . ' ' . '</a>';
    }
}
add_action('manage_course_posts_custom_column', 'learningaid_manage_custom_column_course', 10, 2);


/**
 * Make the new columns 'short_name' & 'author' sortable
 * in the posts list table for the post type 'course' in the admin area.
 * @Hook manage_edit-{$post_type}_sortable_columns
 */
function learningaid_manage_columns_sortable_course($post_columns)
{
    $post_columns[LEARNINGAID_META_COURSE_SHORT_NAME] = LEARNINGAID_META_COURSE_SHORT_NAME;
    $post_columns['author'] = 'author';
    return $post_columns;
}
add_filter('manage_edit-course_sortable_columns', 'learningaid_manage_columns_sortable_course');

/**
 * Assign incoming sorting requests to the meta key.
 * @Hook request
 */
function learningaid_request_order_course_by_meta_key($query_vars)
{
    if (is_admin())
    {
        if (isset($query_vars['orderby']) && LEARNINGAID_META_COURSE_SHORT_NAME == $query_vars['orderby'])
        {
            $query_vars = array_merge($query_vars, array('meta_key' => LEARNINGAID_META_COURSE_SHORT_NAME));
        }
    }
    return $query_vars;
}
add_filter('request', 'learningaid_request_order_course_by_meta_key');


/**
 * Add a custom dropdown filter for the taxonomy 'teacher'
 * above the posts list table for the post type 'course' in the admin area.
 * @Hook restrict_manage_posts
 */
function learningaid_restrict_manage_posts_course()
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
}
add_action('restrict_manage_posts', 'learningaid_restrict_manage_posts_course');


/**
 * Add a meta box 'short_name' to the post type 'course' in the admin area.
 * @Hook add_meta_boxes_{$post-type}
 */
function learningaid_add_meta_box_course()
{
    add_meta_box('course_meta_box', __('Additional Info', LEARNINGAID_DOMAIN), 'learningaid_fill_meta_box_course_content', 'course', 'normal');

    /**
     * Fill the meta box with input field to write the 'short_code'
     */
    function learningaid_fill_meta_box_course_content($post)
    {
        wp_nonce_field(LEARNINGAID_PLUGIN_MAIN_FILE_NAME, 'learningaid_course_meta_box_nonce');

        $current_short_name = get_post_meta($post->ID, LEARNINGAID_META_COURSE_SHORT_NAME, true); ?>
        <div class='inside'>
            <h3><?php _e('Short name', LEARNINGAID_DOMAIN); ?></h3>
            <p>
                <input type="text" name="<?php echo LEARNINGAID_META_COURSE_SHORT_NAME; ?>" value="<?php echo $current_short_name; ?>" />
            </p>
        </div>
<?php
    }
}
add_action('add_meta_boxes_course', 'learningaid_add_meta_box_course');


/**
 * Update the meta data once a post type 'course' has been saved.
 * @Hook save_post_{$post_type}
 */
function learningaid_save_post_course($post_id)
{
    if (
        !isset($_POST['learningaid_course_meta_box_nonce'])
        ||  !wp_verify_nonce($_POST['learningaid_course_meta_box_nonce'], LEARNINGAID_PLUGIN_MAIN_FILE_NAME)
        || !current_user_can('edit_post', $post_id)
    )
    {
        return;
    }

    if (isset($_REQUEST[LEARNINGAID_META_COURSE_SHORT_NAME]))
    {
        $short_name = $_REQUEST[LEARNINGAID_META_COURSE_SHORT_NAME];
        if ($short_name === '')
        {
            $short_name = get_the_title($post_id);
        }
        update_post_meta($post_id, LEARNINGAID_META_COURSE_SHORT_NAME, sanitize_text_field($short_name));
    }
}
add_action('save_post_course', 'learningaid_save_post_course');
