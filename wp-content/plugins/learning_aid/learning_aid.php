<?php
/**
 * Plugin Name:       Learning Aid
 * Description:       Plugin for GrCMS02.
 * Version:           1.0.0
 * Author:            GrCMS02
 */

/**
 * If this file is not called by WordPress, die
 */
if (!defined('WPINC')) {
    die;
}

/**
 * constants
 */
const DOMAIN = 'learning_aid';
const META_FLASHCARD_COURSE = 'flashcard_course';
define('PLUGIN_LOCATION', dirname(__FILE__));
define('PLUGIN_LOCATION_URL', plugins_url('', __FILE__));
define('PLUGIN_BASE_NAME', dirname(plugin_basename(__FILE__)));

/**
 * Localization
 */
add_action('plugins_loaded', 'grcms02_plugins_loaded_languages');
function grcms02_plugins_loaded_languages()
{
    load_plugin_textdomain(DOMAIN, false, PLUGIN_BASE_NAME . '/languages/');
}

/**
 * Custom templates for custom post types
 */
add_filter('single_template', 'grcms02_custom_template_posts');
add_filter('taxonomy_template', 'grcms02_custom_template_posts');
function grcms02_custom_template_posts($template)
{
    global $post;
    if ($post->post_type == 'course') {
        if (file_exists(PLUGIN_LOCATION . '/php/template-course.php')) {
            return PLUGIN_LOCATION . '/php/template-course.php';
        }
    }
    elseif ($post->post_type == 'flashcard') {
        if (file_exists(PLUGIN_LOCATION . '/php/template-flashcard.php')) {
            return PLUGIN_LOCATION . '/php/template-flashcard.php';
        }
    }
    return $template;
}

require_once 'php/grcms02_cpt_course.php';
require_once 'php/grcms02_cpt_flashcard.php';
require_once 'php/grcms02_gutenberg_blocks.php';
