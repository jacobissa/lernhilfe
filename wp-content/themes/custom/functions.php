<?php
namespace learningaid\main;

if ( ! defined( 'WPINC' ) ) {
    die;
}

function add_script()
{
    wp_enqueue_script('script', get_template_directory_uri() . '/js/script.js');
}

add_action("wp_enqueue_scripts", __NAMESPACE__ . '\add_script');

function my_filter_head()
{
    remove_action('wp_head', '_admin_bar_bump_cb');
}

add_action('get_header', __NAMESPACE__ . '\my_filter_head');

function init_add_index_card()
{
    function add_index_card()
    {
        if (!check_admin_referer('add_index_card_nonce', 'nonce'))
            wp_send_json_error("No access from this host", 403);

        //TODO security and user profile https://www.phpkida.com/how-to-add-post-from-frontend-in-wordpress-without-plugin/

        $username = "testuser";
        $question = wp_strip_all_tags($_POST["question"]);
        $answer = wp_strip_all_tags($_POST["answer"]);

        if ($question == null || $answer == null)
            wp_send_json_error("Missing data for Index Card", 400);

        $new_index_card = array(
            'post_title' => $username . ' - ' . $question,
            'post_status' => 'publish',
            'post_type' => 'index_card'
        );

        $inserted_id = wp_insert_post($new_index_card);

        if ($inserted_id == 0)
            wp_send_json_error("Could not create Index Card", 500);

        add_post_meta($inserted_id, 'question', $question);
        add_post_meta($inserted_id, 'answer', $answer);
        add_post_meta($inserted_id, 'username', $username);

        wp_send_json_success();
    }

    add_action('admin_post_add_index_card', __NAMESPACE__ . '\add_index_card');
    add_action('admin_post_nopriv_add_index_card', __NAMESPACE__ . '\add_index_card');

    function enqueue_add_index_card_script()
    {
        wp_enqueue_script('addIndexCard', get_template_directory_uri() . '/js/addIndexCard.js');
        wp_localize_script(
            'addIndexCard',
            'add_index_card_vars',
            array(
                'action' => 'add_index_card',
                'post_url' => admin_url('admin-post.php'),
                'nonce' => wp_create_nonce('add_index_card_nonce')
            )
        );
    }

    add_action("wp_enqueue_scripts", __NAMESPACE__ . '\enqueue_add_index_card_script');
}

add_action("init", __NAMESPACE__ . '\init_add_index_card');


add_filter( 'show_admin_bar', '__return_false' );