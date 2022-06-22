<?php

namespace learningaid\main;

if (!defined('WPINC')) {
    die;
}

define('THEME_DOMAIN', 'grcms02_theme');
define('THEME_DIR_URI', get_template_directory_uri());
define('THEME_DIR', get_template_directory());

/**
 * Loads translation files for theme.
 * @Hook after_setup_theme
 */
function load_theme_localization()
{
    load_theme_textdomain(THEME_DOMAIN, THEME_DIR . '/languages');
}

add_action('after_setup_theme', __NAMESPACE__ . '\load_theme_localization');

/**
 * Loads main JS file
 * @Hook wp_enqueue_scripts
 */
function add_script()
{
    wp_register_script('script', THEME_DIR_URI . '/js/script.js', ['wp-i18n']);
    wp_enqueue_script('script');
}

add_action("wp_enqueue_scripts", __NAMESPACE__ . '\add_script');

// Remove admin bar
add_filter('show_admin_bar', '__return_false');

function respond_unauthorized()
{
    wp_send_json_error('Authentication required', 401);
}

/**
 * Initializes index card feature.
 * @Hook init
 */
function init_index_card()
{
    /**
     * Action to save new index card.
     * @Hook wp_ajax_{$action_name}, wp_ajax_nopriv_{$action_name}
     */
    function add_index_card()
    {
        if (!check_admin_referer('index_card_nonce', 'nonce'))
            wp_send_json_error("No access from this host", 403);

        $username = wp_get_current_user()->user_login;
        $question = wp_strip_all_tags($_POST["question"]);
        $answer = wp_strip_all_tags($_POST["answer"]);
        $course_id = wp_strip_all_tags($_POST["course_id"]);

        if ($question == null || $answer == null || $course_id == null)
            wp_send_json_error("Missing data for Index Card", 400);

        $new_index_card = array(
            'post_title' => $username . ' - ' . $question,
            'post_status' => 'publish',
            'post_type' => 'index_card'
        );

        $inserted_id = wp_insert_post($new_index_card);

        if ($inserted_id !== 0 &&
            add_post_meta($inserted_id, 'question', $question) &&
            add_post_meta($inserted_id, 'answer', $answer) &&
            add_post_meta($inserted_id, 'course_id', $course_id)) {
            wp_send_json_success();
        }

        wp_send_json_error("Could not create Index Card", 500);
    }

    add_action('wp_ajax_add_index_card', __NAMESPACE__ . '\add_index_card');
    add_action('wp_ajax_nopriv_add_index_card', __NAMESPACE__ . '\respond_unauthorized');

    /**
     * Action to delete index card.
     * @Hook wp_ajax_{$action_name}, wp_ajax_nopriv_{$action_name}
     */
    function delete_index_card()
    {
        if (!check_admin_referer('index_card_nonce', 'nonce'))
            wp_send_json_error("No access from this host", 403);

        $index_card_id = wp_strip_all_tags($_POST["index_card_id"]);

        if ($index_card_id == null)
            wp_send_json_error("Missing Index Card id", 400);

        if (!is_numeric($index_card_id))
            wp_send_json_error("Invalid Index Card format", 400);

        $post_user_id = get_post($index_card_id)->post_author;

        if ($post_user_id != wp_get_current_user()->ID)
            wp_send_json_error("Not Index Card owner" . $index_card_id, 401);

        $result = wp_delete_post($index_card_id);

        if ($result == null || $result == false)
            wp_send_json_error("Could not delete Index Card", 500);

        wp_send_json_success();
    }

    add_action('wp_ajax_delete_index_card', __NAMESPACE__ . '\delete_index_card');
    add_action('wp_ajax_nopriv_delete_index_card', __NAMESPACE__ . '\respond_unauthorized');

    /**
     * Enqueue and configure JS script for index cards.
     * @Hook wp_enqueue_scripts
     */
    function enqueue_index_card_script()
    {
        wp_enqueue_script('index_card_script', THEME_DIR_URI . '/js/indexCard.js');
        wp_localize_script(
            'index_card_script',
            'indexcards_wordpress_vars',
            array(
                'add_action' => 'add_index_card',
                'delete_action' => 'delete_index_card',
                'post_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('index_card_nonce'),
                'domain' => THEME_DOMAIN
            )
        );
        wp_set_script_translations('index_card_script', THEME_DOMAIN, THEME_DIR . '/languages');
    }

    add_action("wp_enqueue_scripts", __NAMESPACE__ . '\enqueue_index_card_script');
}

add_action("init", __NAMESPACE__ . '\init_index_card');

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