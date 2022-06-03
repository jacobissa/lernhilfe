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
    $output = '<div class="mc_container">
    <h5>' . $title . '</h5>';

    $answers = explode(';', esc_attr($a['answers']));
    $alphabet = range('A', 'Z');
    $name = str_replace(' ', '_', $title);

    for ($i = 0; $i < count($answers) && $i < count($alphabet); $i++) {
        $output .= '<input type="radio" id="' . $name . $alphabet[$i] . '" name="' . $name . ' " value="' . $answers[$i] . '">
            <label for="' . $name . $alphabet[$i] . '"> <span class="mc_label">' . $alphabet[$i] . '</span> ' . $answers[$i] . '  </label><br>';
    }

    $answer_id = uniqid();
    $output .= '<div class="reveal_answer" id="button_' . $answer_id . '" title="Antwort einblenden" onclick="showAnswer(\'content_' . $answer_id . '\')">Antwort</div>
        <div class="answer_content" id="content_' . $answer_id . '">
        <div class="correct_answer"><b class="correct_checkmark">' . $a['correct'] . '</b></div>';

    if (!empty($a['hint'])) {
        $output .= '<div class="answer_hint">' . $a['hint'] . '</div>';
    }

    $output .= '</div></div>';
    return $output;
}

/** Enqueuing the Stylesheet and Script for Multiple Choice Questions */
function mc_enqueue_scripts()
{
    global $post;
    $has_shortcode = has_shortcode($post->post_content, 'multiple_choice_question');
    if (is_a($post, 'WP_Post') && $has_shortcode) {
        wp_register_style('mc-style', plugin_dir_url(__FILE__) . 'css/mc-style.css');
        wp_enqueue_style('mc-style');

        wp_register_script('mc-script', plugin_dir_url(__FILE__) . 'js/mc-script.js');
        wp_enqueue_script('mc-script');
    }
}

add_action('wp_enqueue_scripts', 'mc_enqueue_scripts');