<?php

/**
 * Plugin Name:  Multiple Choice Questions
 * Version: 1.0
 * Description: Adds Shortcodes for Multiple Choice Questions. Usage: [multiple_choice_question title="" answers=";" correct="" (optional)hint=""]
 * Author: Louis Meglitsch
 * Text Domain: multiple-choice
 * Domain Path: /languages/
 */

/** Prevent direct access to the file */
if (!defined('WPINC')) {
    die;
}
/** Define some constants */
define('MC_DOMAIN', 'multiple-choice');
define('MC_DIR', dirname(__FILE__));

/**
 * Register the shortcodes after WordPress has finished loading
 * @Hook init
 */
function multiple_choice_init()
{
    add_shortcode('multiple_choice_question', 'mc_question');
}

add_action('init', 'multiple_choice_init');

/**
 * [mc_question] Returns HTML code for a multiple choice questions and it's answer
 * @return string The HTML Code
 */
function mc_question($atts): string
{
    $filtered_atts = shortcode_atts(array(
        'title' => 'Title',
        'answers' => 'A;B;C;D',
        'correct' => 'A',
        'hint' => ''
    ), $atts);

    $title = esc_attr($filtered_atts['title']);
    $answers = explode(';', esc_attr($filtered_atts['answers']));
    $correct = esc_attr($filtered_atts['correct']);
    $hint = esc_attr($filtered_atts['hint']);

    // Create alphabet array from A to Z and a name without spaces as well as a unique ID for the html inputs
    $alphabet = range('A', 'Z');
    $name = str_replace(' ', '_', htmlspecialchars($title));
    $mc_id = uniqid();

    $output = '<div class="mc_container">
    <label class="mc_title">' . $title . '</label><div class="mc_content"><div class="mc_input_container">';

    // Add radio inputs with custom label and span
    for ($i = 0; $i < count($answers) && $i < count($alphabet); $i++) {
        if ($answers[$i] != '') {
            $output .= '<div class="mc_input"><input type="radio" class="mc_radio" id="' . $name . $alphabet[$i] . '" name="mc_a_' . $mc_id . '" value="' . $answers[$i] . '">
            <label class="mc_label" for="' . $name . $alphabet[$i] . '"> <span class="mc_span">' . $alphabet[$i] . '</span>' . $answers[$i] . '  </label></div>';
        }
    }

    // Add hidden inputs to check the answer later
    $output .= '<input type="hidden" name="mc_q_' . $mc_id . '" value="' . $title . '">';
    $output .= '<input type="hidden" name="mc_s_' . $mc_id . '" value="' . $correct . '">';
    $output .= '<input type="hidden" name="mc_h_' . $mc_id . '" value="' . $hint . '">';

    $output .= '</div></div></div>';
    return $output;
}

/**
 * Enqueuing the Stylesheet for Multiple Choice Questions
 * @Hook wp_enqueue_scripts
 */
function mc_enqueue_scripts()
{
    global $post;
    if (is_a($post, 'WP_Post')) {
        $has_shortcode = has_shortcode($post->post_content, 'multiple_choice_question');
        if ($has_shortcode) {
            wp_register_style('mc-style', plugin_dir_url(__FILE__) . '/css/mc-style.css');
            wp_enqueue_style('mc-style');
        }
    }
}

add_action('wp_enqueue_scripts', 'mc_enqueue_scripts');

/**
 * Register a custom block for multiple choice questions which can be used more easily than the plain shortcode
 * @Hook init
 */
function register_block_type_multiple_choice()
{
    wp_register_script('block-multiple-choice-script', plugins_url('', __FILE__) . '/js/mc-script.js', array('wp-blocks', 'wp-editor', 'wp-i18n'));
    wp_set_script_translations('block-multiple-choice-script', MC_DOMAIN, MC_DIR . '/languages');
    wp_register_style('block-multiple-choice-css', plugins_url('', __FILE__) . '/css/mc-style.css');
    $args = array(
        'editor_script' => 'block-multiple-choice-script',
        'style' => 'block-multiple-choice-css',
    );
    register_block_type('learning-aid/block-multiple-choice', $args);
}

add_action('init', 'register_block_type_multiple_choice');