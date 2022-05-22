<?php

function add_script()
{
    wp_enqueue_script('script', get_template_directory_uri() . '/js/script.js');
}

add_action("wp_enqueue_scripts", 'add_script');

function my_filter_head()
{
    remove_action('wp_head', '_admin_bar_bump_cb');
}

add_action('get_header', 'my_filter_head');

function init_add_index_card()
{
    function add_index_card()
    {
        check_ajax_referer('add_index_card_nonce', 'nonce');

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

    add_action('admin_post_add_index_card', 'add_index_card');
    add_action('admin_post_nopriv_add_index_card', 'add_index_card');

    function enqueue_add_index_card_script()
    {
        wp_enqueue_script('addIndexCard', get_template_directory_uri() . '/js/addIndexCard.js');
        wp_localize_script(
            'addIndexCard',
            'add_index_card_vars',
            array(
                'post_url' => admin_url('admin-post.php'),
                'nonce' => wp_create_nonce('add_index_card_nonce')
            )
        );
    }

    add_action("wp_enqueue_scripts", 'enqueue_add_index_card_script');
}

add_action("init", "init_add_index_card");


function enqueue_snackbar_script()
{
    wp_enqueue_script('snackbar', get_template_directory_uri() . '/js/snackbar.js');
    wp_localize_script(
        'snackbar',
        'snackbar_vars',
        array(
            'template_dir' => get_template_directory_uri()
        )
    );
}

add_action("wp_enqueue_scripts", 'enqueue_snackbar_script');