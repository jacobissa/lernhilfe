<?php

/**
 * Add style (css) file to the public area.
 * @Hook wp_enqueue_scripts
 */
function timetable_enqueue_scripts_public()
{
    wp_deregister_style('timetable-public-style');
    wp_register_style('timetable-public-style', TIMETABLE_PLUGIN_LOCATION_URL . '/styles/public-style.css');
    wp_enqueue_style('timetable-public-style');
}
add_action('wp_enqueue_scripts', 'timetable_enqueue_scripts_public');

/**
 * Add style (css) file to the admin area.
 * @Hook wp_enqueue_scripts
 */
function timetable_enqueue_scripts_admin()
{
    wp_deregister_style('timetable-admin-style');
    wp_register_style('timetable-admin-style', TIMETABLE_PLUGIN_LOCATION_URL . '/styles/admin-style.css');
    wp_enqueue_style('timetable-admin-style');
}
add_action('admin_enqueue_scripts', 'timetable_enqueue_scripts_admin');
