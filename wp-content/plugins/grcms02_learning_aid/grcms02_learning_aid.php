<?php

/**
 * Plugin Name:       GrCMS02 Learning Aid
 * Plugin URI:        http://grcms02.informatik.fh-nuernberg.de
 * Description:       Plugin for GrCMS02 in SS22.
 * Version:           1.2.0
 * Author:            Jacob Issa
 * Author URI:        mailto:issaja73014@th-nuernberg.de
 */

/**
 * If this file is not called by WordPress, abort
 */
if (!defined('WPINC'))
{
    die;
}

/**
 * Define constants
 */
define('LEARNINGAID_DOMAIN', 'grcms02_learning_aid');
define('LEARNINGAID_PLUGIN_LOCATION_DIR', dirname(__FILE__));
define('LEARNINGAID_PLUGIN_LOCATION_URL', plugins_url('', __FILE__));
define('LEARNINGAID_PLUGIN_BASE_NAME', dirname(plugin_basename(__FILE__)));
define('LEARNINGAID_PLUGIN_MAIN_FILE_NAME', __FILE__);
define('LEARNINGAID_META_LESSON_COURSE', 'lesson_course');
define('LEARNINGAID_META_COURSE_SHORT_NAME', 'short_name');


/**
 * Load plugin management (Localization)
 */
require_once(LEARNINGAID_PLUGIN_LOCATION_DIR . '/includes/plugin_management.php');

/**
 * Load the custom post types (course & lesson)
 */
require_once(LEARNINGAID_PLUGIN_LOCATION_DIR . '/includes/course_post_type.php');
require_once(LEARNINGAID_PLUGIN_LOCATION_DIR . '/includes/lesson_post_type.php');

/**
 * Load the shortcode [youtube-embed-url]
 */
require_once(LEARNINGAID_PLUGIN_LOCATION_DIR . '/includes/shortcode.php');

/**
 * Load gutenberg blocks management (ytvideo & textquestion)
 */
require_once(LEARNINGAID_PLUGIN_LOCATION_DIR . '/includes/blocks_management.php');

/**
 * Load templates management
 */
require_once(LEARNINGAID_PLUGIN_LOCATION_DIR . '/includes/templates_management.php');

/**
 * Load scripts (js) & styles (css) management
 */
require_once(LEARNINGAID_PLUGIN_LOCATION_DIR . '/includes/scripts_management.php');
