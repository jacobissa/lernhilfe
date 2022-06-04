<?php

/**
 * Plugin Name:       GrCMS02 Timetable
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
 * Define constants.
 */
define('TIMETABLE_DOMAIN', 'grcms02_timetable');
define('TIMETABLE_PLUGIN_LOCATION_DIR', dirname(__FILE__));
define('TIMETABLE_PLUGIN_LOCATION_URL', plugins_url('', __FILE__));
define('TIMETABLE_PLUGIN_BASE_NAME', dirname(plugin_basename(__FILE__)));
define('TIMETABLE_PLUGIN_MAIN_FILE_NAME', __FILE__);
define('TIMETABLE_META_COURSE', 'timetable_course');
define('TIMETABLE_PAGE_SLUG_NEW', 'new-timetable');
define('TIMETABLE_PAGE_SLUG_ALL', 'all-timetable');
define('TIMETABLE_PAGE_SLUG_MY', 'my-timetable');
define('TIMETABLE_OPTION_TIMESLOT', 'timetable_timeslots');

/**
 * Load plugin management (Localization, activation hook & deactivation hook)
 */
require_once(TIMETABLE_PLUGIN_LOCATION_DIR . '/includes/plugin_management.php');

/**
 * Load the custom post type (timetable)
 */
require_once(TIMETABLE_PLUGIN_LOCATION_DIR . '/includes/timetable_post_type.php');

/**
 * Load templates management
 */
require_once(TIMETABLE_PLUGIN_LOCATION_DIR . '/includes/templates_management.php');

/**
 * Load scripts (js) & styles (css) management
 */
require_once(TIMETABLE_PLUGIN_LOCATION_DIR . '/includes/scripts_management.php');

/**
 * Load the admin submenu pages (export timetable, import timetable & adjust timtable)
 */
require_once(TIMETABLE_PLUGIN_LOCATION_DIR . '/includes/menu_export.php');
require_once(TIMETABLE_PLUGIN_LOCATION_DIR . '/includes/menu_import.php');
require_once(TIMETABLE_PLUGIN_LOCATION_DIR . '/includes/menu_adjust.php');
