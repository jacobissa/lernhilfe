<?php

/**
 * Plugin Name:  Multiple Choice Questions
 * Version: 1.0
 * Description: Adds Shortcodes for Multiple Choice Questions. Usage: [multiple_choice_question title="" answers=";" correct="" (optional)hint=""]
 * Author: Louis Meglitsch
 */

/** Prevent direct access to the file */
if (!defined('WPINC')) {
    die;
}

/** Register the shortcodes after WordPress has finished loading */
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
    $a = shortcode_atts(array(
        'title' => 'Title',
        'answers' => 'A;B;C;D',
        'correct' => 'A',
        'hint' => ''
    ), $atts);

    $title = esc_attr($a['title']);
    $correct = esc_attr($a['correct']);
    $hint = esc_attr($a['hint']);
    $answers = explode(';', esc_attr($a['answers']));
    $alphabet = range('A', 'Z');
    $name = str_replace(' ', '_', htmlspecialchars($title));
    $mc_id = uniqid();

    $output = '<div class="mc_container">
    <label class="mc_title">' . $title . '</label><div class="mc_content"><div class="mc_input_container">';

    for ($i = 0; $i < count($answers) && $i < count($alphabet); $i++) {
        $output .= '<div class="mc_input"><input type="radio" class="mc_radio" id="' . $name . $alphabet[$i] . '" name="mc_a_' . $mc_id . '" value="' . $answers[$i] . '">
            <label class="mc_label" for="' . $name . $alphabet[$i] . '"> <span class="mc_span">' . $alphabet[$i] . '</span>' . $answers[$i] . '  </label></div>';
    }

    // Add hidden inputs to check the answer later
    $output .= '<input type="hidden" name="mc_q_' . $mc_id . '" value="' . $title . '">';
    $output .= '<input type="hidden" name="mc_s_' . $mc_id . '" value="' . $correct . '">';
    $output .= '<input type="hidden" name="mc_h_' . $mc_id . '" value="' . $hint . '">';

    $output .= '</div></div></div>';
    return $output;
}

/** Enqueuing the Stylesheet and Script for Multiple Choice Questions */
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

function register_block_type_multiple_choice()
{
    wp_register_script('block-multiple-choice-js', plugins_url('', __FILE__) . '/js/mc-script.js', array('wp-blocks', 'wp-editor'));
    wp_register_style('block-multiple-choice-css', plugins_url('', __FILE__) . '/css/mc-style.css');
    $args = array(
        'editor_script' => 'block-multiple-choice-js',
        'style' => 'block-multiple-choice-css',
    );
    register_block_type('learning-aid/block-multiple-choice', $args);
}

add_action('init', 'register_block_type_multiple_choice');