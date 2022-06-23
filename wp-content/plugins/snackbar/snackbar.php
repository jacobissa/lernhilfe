<?php
/**
 * Plugin Name: Snackbar
 * Version: 1.0
 * Description: Adds JS function to display snackbar
 * Author: Manuel Wälzlein
 */

namespace learningaid\snackbar;

if (!defined('WPINC')) {
    die;
}

define('SNACKBAR_PLUGIN_LOCATION_DIR', dirname(__FILE__));
define('SNACKBAR_PLUGIN_LOCATION_URL', plugins_url('', __FILE__));
define('SNACKBAR_HANDLE_PREFIX', 'snackbar_');

/**
 * Configure and load JS and CSS for snackbar.
 */
function enqueue_snackbar_script()
{
    wp_enqueue_script(SNACKBAR_HANDLE_PREFIX . 'snackbar_script', SNACKBAR_PLUGIN_LOCATION_URL . '/js/snackbar.js');
    wp_localize_script(
        SNACKBAR_HANDLE_PREFIX . 'snackbar_script',
        'snackbar_wordpress_vars',
        ['template_dir' => SNACKBAR_PLUGIN_LOCATION_URL]
    );

    wp_register_style(__NAMESPACE__ . '\stylesheet', SNACKBAR_PLUGIN_LOCATION_URL . '/css/style.css');
    wp_enqueue_style(__NAMESPACE__ . '\stylesheet');
}

add_action('wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_snackbar_script');

/**
 * Load snackbar.
 * @Hook wp_footer
 */
function include_snackbar()
{
    include('template/snackbar.php');
}

add_action('wp_footer', __NAMESPACE__ . '\include_snackbar');