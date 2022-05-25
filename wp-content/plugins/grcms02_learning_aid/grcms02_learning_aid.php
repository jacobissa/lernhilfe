<?php

/**
 * Plugin Name:       Learning Aid
 * Plugin URI:        http://grcms02.informatik.fh-nuernberg.de
 * Description:       Plugin for GrCMS02 in SS22.
 * Version:           1.0.2
 * Author:            Jacob Issa
 * Author URI:        mailto:issaja73014@th-nuernberg.de
 */

/**
 * If this file is not called by WordPress, die
 */
if (!defined('WPINC'))
{
    die;
}

/**
 * define constants
 */
define('LEARNINGAID_DOMAIN', 'grcms02_learning_aid');
define('LEARNINGAID_PLUGIN_LOCATION', dirname(__FILE__));
define('LEARNINGAID_PLUGIN_LOCATION_URL', plugins_url('', __FILE__));
define('LEARNINGAID_PLUGIN_BASE_NAME', dirname(plugin_basename(__FILE__)));
define('META_EXERCISE_COURSE', 'exercise_course');

/**
 * Localization
 */
add_action('plugins_loaded', 'grcms02_plugins_loaded_languages');
function grcms02_plugins_loaded_languages()
{
    load_plugin_textdomain(LEARNINGAID_DOMAIN, false, LEARNINGAID_PLUGIN_BASE_NAME . '/languages');
}


/**
 * shortcode [youtube-embed-url]
 */
add_shortcode('youtube-embed-url', 'grcms02_youtube_embed_url_handler');
function grcms02_youtube_embed_url_handler($atts, $content, $tag)
{
    $width = '100%';
    $height = '100%';
    $element = 'img';
    $src = LEARNINGAID_PLUGIN_LOCATION_URL . '/images/invalid_ytvideo.svg';
    if ($content != null && $content != '')
    {
        parse_str(parse_url($content, PHP_URL_QUERY), $youtube_link_vars);
        if (isset($youtube_link_vars['v']))
        {
            $youtube_id =  $youtube_link_vars['v'];
            if ($youtube_id != null && $youtube_id != '')
            {
                $element = 'iframe';
                $src = "https://www.youtube.com/embed/" . $youtube_id;
                $width = (isset($atts['width'])) ? $atts['width'] : $width;
                $height = (isset($atts['height'])) ? $atts['height'] : $height;
            }
        }
    }
    ob_start();
?>
    <<?php echo $element; ?> width="<?php echo $width; ?>" height="<?php echo $height; ?>" class="grcms02-block-ytvideo-save-iframe" src="<?php echo $src; ?>" allow="fullscreen;" frameBorder="0">
    </<?php echo $element; ?>>
<?php
    return ob_get_clean();
}


/**
 * Custom templates for custom post types
 */
add_filter('single_template', 'grcms02_custom_template_posts');
add_filter('taxonomy_template', 'grcms02_custom_template_posts');
function grcms02_custom_template_posts($template)
{
    global $post;
    if ($post != null && $post->post_type != null && $post->post_type != '')
    {
        if ($post->post_type == 'course')
        {
            if (file_exists(LEARNINGAID_PLUGIN_LOCATION . '/templates/template-course.php'))
            {
                return LEARNINGAID_PLUGIN_LOCATION . '/templates/template-course.php';
            }
        }
        elseif ($post->post_type == 'exercise')
        {
            if (file_exists(LEARNINGAID_PLUGIN_LOCATION . '/templates/template-exercise.php'))
            {
                return LEARNINGAID_PLUGIN_LOCATION . '/templates/template-exercise.php';
            }
        }
    }
    return $template;
}

/**
 * add js and css files
 */
add_action('wp_enqueue_scripts', 'grcms02_wp_enqueue_scripts');
function grcms02_wp_enqueue_scripts()
{
    wp_deregister_script('post-script');
    wp_register_script('post-script', LEARNINGAID_PLUGIN_LOCATION_URL . '/templates/post-script.js');
    wp_enqueue_script('post-script');

    wp_deregister_style('post-style');
    wp_register_style('post-style', LEARNINGAID_PLUGIN_LOCATION_URL . '/templates/post-style.css');
    wp_enqueue_style('post-style');
}

require_once 'includes/course_post_type.php';
require_once 'includes/exercise_post_type.php';
require_once 'blocks/blocks_registration.php';
