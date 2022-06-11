<?php

namespace learningaid\main;

if (!defined('WPINC')) {
    die;
}

function add_script()
{
    wp_enqueue_script('script', get_template_directory_uri() . '/js/script.js');
}

add_action("wp_enqueue_scripts", __NAMESPACE__ . '\add_script');

function filter_head()
{
    remove_action('wp_head', '_admin_bar_bump_cb');
}

add_action('get_header', __NAMESPACE__ . '\filter_head');

function init_index_card()
{
    function add_index_card()
    {
        // make sure sender is allowed to access API
        if (!check_admin_referer('index_card_nonce', 'nonce'))
            wp_send_json_error("No access from this host", 403);

        // load index card data
        $username = "testuser";
        $question = wp_strip_all_tags($_POST["question"]);
        $answer = wp_strip_all_tags($_POST["answer"]);
        $course_id = wp_strip_all_tags($_POST["course_id"]);

        // validate data
        if ($question == null || $answer == null || $course_id == null)
            wp_send_json_error("Missing data for Index Card", 400);

        // save index card
        $new_index_card = array(
            'post_title' => $username . ' - ' . $question,
            'post_status' => 'publish',
            'post_type' => 'index_card'
        );

        $inserted_id = wp_insert_post($new_index_card);

        if ($inserted_id !== 0 &&
            add_post_meta($inserted_id, 'question', $question) &&
            add_post_meta($inserted_id, 'answer', $answer) &&
            add_post_meta($inserted_id, 'username', $username) &&
            add_post_meta($inserted_id, 'course_id', $course_id)) {
            wp_send_json_success();
        }

        wp_send_json_error("Could not create Index Card", 500);
    }

    add_action('wp_ajax_add_index_card', __NAMESPACE__ . '\add_index_card');
    add_action('wp_ajax_nopriv_add_index_card', __NAMESPACE__ . '\add_index_card');

    function delete_index_card()
    {
        if (!check_admin_referer('index_card_nonce', 'nonce'))
            wp_send_json_error("No access from this host", 403);

        $username = "testuser";
        $index_card_id = wp_strip_all_tags($_POST["index_card_id"]);

        if ($index_card_id == null)
            wp_send_json_error("Missing Index Card id", 400);

        if (!is_numeric($index_card_id))
            wp_send_json_error("Invalid Index Card format", 400);

        $post_username = get_post_meta($index_card_id, 'username')[0];

        if ($post_username != $username)
            wp_send_json_error("Not Index Card owner" . $index_card_id, 401);

        $result = wp_delete_post($index_card_id);

        if ($result == false || $result == null)
            wp_send_json_error("Could not delete Index Card", 500);

        wp_send_json_success();
    }

    add_action('wp_ajax_delete_index_card', __NAMESPACE__ . '\delete_index_card');
    add_action('wp_ajax_nopriv_delete_index_card', __NAMESPACE__ . '\delete_index_card');

    function enqueue_index_card_script()
    {
        wp_enqueue_script('indexCard', get_template_directory_uri() . '/js/indexCard.js');
        wp_localize_script(
            'indexCard',
            'index_card_vars',
            array(
                'add_action' => 'add_index_card',
                'delete_action' => 'delete_index_card',
                'post_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('index_card_nonce')
            )
        );
    }

    add_action("wp_enqueue_scripts", __NAMESPACE__ . '\enqueue_index_card_script');
}

add_action("init", __NAMESPACE__ . '\init_index_card');


add_filter('show_admin_bar', '__return_false');


function enqueue_summaries_script()
{
    wp_register_script('summaries-script', get_template_directory_uri() . '/js/summaries-script.js');
    wp_enqueue_script('summaries-script');
    wp_localize_script('summaries-script', 'summaries_args',
        array('post_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('summary_nonce')));
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_summaries_script');

function add_summary()
{
    if (!check_admin_referer('summary_nonce', 'nonce')) {
        wp_send_json_error("No access from this host", 403);
    }

    if (!function_exists('move_uploaded_file')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    if (isset($_POST['course_slug']) && isset($_FILES['summary_to_upload'])) {
        $folder_name = htmlspecialchars($_POST['course_slug']);
        $folder_name = trim($folder_name);
        $upload_dir = wp_upload_dir();
        $upload_path = sprintf('%s/%s/', $upload_dir['basedir'], $folder_name);

        if (!file_exists($upload_path)) {
            mkdir($upload_path);
        }

        $file_name = htmlspecialchars($_FILES['summary_to_upload']['name']);
        $file_name = str_replace(' ', '_', $file_name);
        $temp_name = $_FILES['summary_to_upload']['tmp_name'];
        $file_type = wp_check_filetype($file_name);
        $new_full_path = $upload_path . $file_name;

        if ($file_type['ext'] != 'pdf') {
            wp_send_json_error('File not supported', 415);
        } else {
            if (file_exists($new_full_path)) {
                wp_send_json_error('The file already exist', 409);
            } else {
                if (move_uploaded_file($temp_name, $new_full_path)) {
                    $attachment_args = array(
                        'guid' => $new_full_path,
                        'post_mime_type' => $file_type['type'],
                        'post_title' => $file_name,
                        'post_content' => '',
                        'post_status' => 'inherit',
                        'post_excerpt' => $folder_name
                    );
                    $attach_id = wp_insert_attachment($attachment_args, $new_full_path);
                    if ($attach_id != 0) {
                        $full_url = sprintf('%s/%s/%s', $upload_dir['baseurl'], $folder_name, $file_name);
                        $response_data = array(
                            'fullUrl' => $full_url,
                            'name' => $file_name,
                            'date' => date("d.m.Y H:i", filemtime($new_full_path))
                        );
                        wp_send_json_success($response_data, 200);
                    }
                } else {
                    wp_send_json_error('The file was not uploaded', 500);
                }
            }
        }
    }
    wp_send_json_error('Invalid data', 400);
}

function nopriv_add_summary()
{
    wp_send_json_error('Authentication required', 401);
}

add_action('wp_ajax_add_summary', __NAMESPACE__ . '\add_summary');
add_action('wp_ajax_nopriv_add_summary', __NAMESPACE__ . '\nopriv_add_summary');