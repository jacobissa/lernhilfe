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