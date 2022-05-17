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
if (!defined('WPINC'))
{
    die;
}

/**
 * constants
 */
define('COURSE_DOMAIN', 'learning_aid');
define('META_EXERCISE_COURSE', 'exercise_course');
define('COURSE_PLUGIN_LOCATION', dirname(__FILE__));
define('COURSE_PLUGIN_LOCATION_URL', plugins_url('', __FILE__));
define('COURSE_PLUGIN_BASE_NAME', dirname(plugin_basename(__FILE__)));

/**
 * Localization
 */
add_action('plugins_loaded', 'grcms02_plugins_loaded_languages');
function grcms02_plugins_loaded_languages()
{
    load_plugin_textdomain(COURSE_DOMAIN, false, COURSE_PLUGIN_BASE_NAME . '/languages/');
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
    $src = COURSE_PLUGIN_LOCATION_URL . '/assets/invalid_ytvideo.svg';
    if ($content != null && $content != '')
    {
        parse_str(parse_url($content, PHP_URL_QUERY), $youtube_link_vars);
        if (isset($youtube_link_vars['v']))
        {
            $youtube_id =  $youtube_link_vars['v'];
            if ($youtube_id != null && $youtube_id != '')
            {
                $element = 'iframe';
                $src = "https://www.youtube-nocookie.com/embed/" . $youtube_id;
                $width = (isset($atts['width'])) ? $atts['width'] : '560';
                $height = (isset($atts['height'])) ? $atts['height'] : '315';
            }
        }
    }
    ob_start();
?>
    <<?php echo $element; ?> width="<?php echo $width; ?>" height="<?php echo $height; ?>" class="grcms02-block-ytvideo-save-iframe" src="<?php echo $src; ?>">
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
    if ($post->post_type == 'course')
    {
        if (file_exists(COURSE_PLUGIN_LOCATION . '/php/template-course.php'))
        {
            return COURSE_PLUGIN_LOCATION . '/php/template-course.php';
        }
    }
    elseif ($post->post_type == 'exercise')
    {
        if (file_exists(COURSE_PLUGIN_LOCATION . '/php/template-exercise.php'))
        {
            return COURSE_PLUGIN_LOCATION . '/php/template-exercise.php';
        }
    }
    return $template;
}

require_once 'php/grcms02_cpt_course.php';
require_once 'php/grcms02_cpt_exercise.php';
require_once 'php/grcms02_gutenberg_blocks.php';
