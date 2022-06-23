<?php

namespace learningaid\main;

if (!defined('WPINC')) {
    die;
}

define('THEME_DOMAIN', 'grcms02_theme');
define('THEME_DIR_URI', get_template_directory_uri());
define('THEME_DIR', get_template_directory());
define('MAIN_HANDLE_PREFIX', "learningaid_");

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
    wp_enqueue_script(MAIN_HANDLE_PREFIX . 'script', THEME_DIR_URI . '/js/script.js', ['wp-i18n']);
}

add_action("wp_enqueue_scripts", __NAMESPACE__ . '\add_script');

// Remove admin bar
add_filter('show_admin_bar', '__return_false');

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
        wp_enqueue_script(MAIN_HANDLE_PREFIX . 'index_card_script', THEME_DIR_URI . '/js/indexCard.js');
        wp_localize_script(
            MAIN_HANDLE_PREFIX . 'index_card_script',
            'indexcards_wordpress_vars',
            array(
                'add_action' => 'add_index_card',
                'delete_action' => 'delete_index_card',
                'post_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('index_card_nonce'),
                'domain' => THEME_DOMAIN
            )
        );
        wp_set_script_translations(MAIN_HANDLE_PREFIX . 'index_card_script', THEME_DOMAIN, THEME_DIR . '/languages');
    }

    add_action("wp_enqueue_scripts", __NAMESPACE__ . '\enqueue_index_card_script');
}

add_action("init", __NAMESPACE__ . '\init_index_card');
