<?php

/**
 * Default response for users who are not logged in or unauthorized
 * @Hook wp_ajax_nopriv_...
 */
function respond_unauthorized()
{
    wp_send_json_error('Authentication required', 401);
}

/**
 * Enqueues the summaries-script as well as its localization and translation
 * @Hook wp_enqueue_scripts
 */
function enqueue_summaries_script()
{
    wp_register_script('summaries-script', THEME_DIR_URI . '/js/summaries-script.js');
    wp_enqueue_script('summaries-script');
    wp_localize_script('summaries-script', 'summaries_args',
        array('post_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('summary_nonce'),
            'text_domain' => THEME_DOMAIN));
    wp_set_script_translations('summaries-script', THEME_DOMAIN, THEME_DIR . '/languages');
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_summaries_script');

/**
 * Backend function for adding a summary to a course
 * @Hook wp_ajax_add_summary
 */
function add_summary()
{
    if (!check_admin_referer('summary_nonce', 'nonce'))
        wp_send_json_error("No access from this host", 403);

    if (!isset($_POST['course_slug']) || !isset($_POST['course_id']) || !isset($_FILES['summary_to_upload']))
        wp_send_json_error('Invalid data', 400);

    if (!function_exists('move_uploaded_file'))
        require_once(ABSPATH . 'wp-admin/includes/file.php');

    $folder_name = trim(wp_strip_all_tags($_POST['course_slug']));
    $upload_dir = wp_upload_dir();
    $upload_path = sprintf('%s/%s/', $upload_dir['basedir'], $folder_name);

    $file_name = trim(wp_strip_all_tags($_FILES['summary_to_upload']['name']));
    $file_name = str_replace(' ', '_', $file_name);
    $new_full_path = $upload_path . $file_name;
    $file_type = wp_check_filetype($file_name);

    $parent_id = trim(wp_strip_all_tags($_POST['course_id']));
    $temp_name = $_FILES['summary_to_upload']['tmp_name'];

    if ($file_type['ext'] != 'pdf')
        wp_send_json_error('File not supported', 415);

    if (file_exists($new_full_path))
        wp_send_json_error('The file already exist', 409);

    if (!file_exists($upload_path))
        mkdir($upload_path);

    if (!move_uploaded_file($temp_name, $new_full_path))
        wp_send_json_error('The file could not be uploaded', 500);

    $attachment_args = array(
        'guid' => $new_full_path,
        'post_mime_type' => $file_type['type'],
        'post_title' => $file_name,
        'post_content' => '',
        'post_status' => 'inherit',
        'post_parent' => $parent_id
    );
    $attachment_id = wp_insert_attachment($attachment_args, $new_full_path);

    if ($attachment_id == 0)
        wp_send_json_error('The file was uploaded but could not be inserted into the attachments', 500);

    $response_data = array(
        'name' => $file_name,
        'fullUrl' => wp_get_attachment_url($attachment_id),
        'date' => get_the_date('d.m.Y H:i', $attachment_id),
        'user' => get_the_author_meta('display_name', get_post_field('post_author', $attachment_id))
    );
    wp_send_json_success($response_data, 200);
}

add_action('wp_ajax_add_summary', __NAMESPACE__ . '\add_summary');
add_action('wp_ajax_nopriv_add_summary', __NAMESPACE__ . '\respond_unauthorized');