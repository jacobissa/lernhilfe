<?php

/**
 * Load plugin's translated strings (Localization).
 * @Hook plugins_loaded
 */
function timetable_load_plugin_textdomain_localization()
{
    load_plugin_textdomain(TIMETABLE_DOMAIN, false, TIMETABLE_PLUGIN_BASE_NAME . '/languages');
}
add_action('plugins_loaded', 'timetable_load_plugin_textdomain_localization');



/**
 * Call this function when the plugin is deactivated.
 * @Hook register_activation_hook
 */
function timetable_plugin_activation()
{
    /**
     * Require parent plugin (GrCMS02 Learning Aid)
     * If it is not installed, stop activation this plugin and show error.
     * If it is installed, but not activated, then activate it!
     */
    $installed_plugins = get_plugins();
    $learning_aid_plugin = 'grcms02_learning_aid/grcms02_learning_aid.php';
    if (array_key_exists($learning_aid_plugin, $installed_plugins))
    {
        if (!is_plugin_active($learning_aid_plugin))
        {
            activate_plugin($learning_aid_plugin);
        }
    }
    else
    {
        wp_die('Sorry, but this plugin requires the Plugin (GrCMS02 Learning Aid) to be installed and active. <br><a href="' . admin_url('plugins.php') . '">&laquo; Return to Plugins</a>');
    }


    /**
     * Add a new option 'timetable_timeslots' which will be use for adjusting the timeslots.
     * This option does still exists, if the user deactivate and then reactivate the plugn. In this case, no new option will be inserted.
     */
    if (false === get_option(TIMETABLE_OPTION_TIMESLOT))
    {
        add_option(TIMETABLE_OPTION_TIMESLOT, array(
            'time1' => '08:00 - 09:30',
            'time2' => '09:45 - 11:15',
            'time3' => '11:30 - 13:00',
            'time4' => '14:00 - 15:30',
            'time5' => '15:45 - 17:15',
            'time6' => '17:30 - 19:00',
        ));
    }

    /**
     * First check if the required pages are existing, if yes delete them.
     * Then insert the required pages.
     */
    timetable_delete_required_pages();
    timetable_insert_required_pages();
}
register_activation_hook(TIMETABLE_PLUGIN_MAIN_FILE_NAME, 'timetable_plugin_activation');


/**
 * Call this function when the plugin is deactivated.
 * @Hook register_deactivation_hook
 */
function timetable_plugin_deactivation()
{
    // Delete the required pages.
    timetable_delete_required_pages();

    // Delete the directory 'exported_timetables', which exists in the upload directory and used to export the timetables.
    $export_directory = wp_get_upload_dir()['basedir'] . '/exported_timetables';
    timetable_remove_directory_with_content($export_directory);
}
register_deactivation_hook(TIMETABLE_PLUGIN_MAIN_FILE_NAME, 'timetable_plugin_deactivation');


/**
 * Detect if the user tries to deactivate the parent plugin (GrCMS02 Learning Aid) while using this plugin. If yes, prevent it!
 * @Hook deactivated_plugin
 */
function timetable_detect_deactivated_parent_plugin($plugin)
{
    $learning_aid_plugin = 'grcms02_learning_aid/grcms02_learning_aid.php';
    if ($plugin === $learning_aid_plugin)
    {
        wp_die('Sorry, but this plugin is required to be active for the Plugin (GrCMS02 Timetable). <br><a href="' . admin_url('plugins.php') . '">&laquo; Return to Plugins</a>');
    }
}
add_action('deactivated_plugin', 'timetable_detect_deactivated_parent_plugin', 10, 1);


/**
 * Get the name of the weekday from the localization.
 * @param string $day The day as saved in meta data (day1, day2, day3, day4, day5).
 * @return string The real name of the weekday from the localization.
 */
function timetable_get_weekday_name(string $day)
{
    switch ($day)
    {
        case 'day1':
            {
                return __('Monday', TIMETABLE_DOMAIN);
            }
            break;
        case 'day2':
            {
                return __('Tuesday', TIMETABLE_DOMAIN);
            }
            break;
        case 'day3':
            {
                return __('Wednesday', TIMETABLE_DOMAIN);
            }
            break;
        case 'day4':
            {
                return __('Thursday', TIMETABLE_DOMAIN);
            }
            break;
        case 'day5':
            {
                return __('Friday', TIMETABLE_DOMAIN);
            }
            break;
        default:
            {
                return $day;
            }
    }
}

/**
 * Delete the pages 'my-timetable', 'new-timetable' & 'all-timetable' which are required for this plugin. 
 * The function checks if the pages are existing before deleting them.
 * So, it can also be used, if it is unknown if the pages are existing.
 * Please use this function only during the deactivation of the plugin, 
 * or during the uninstalling, 
 * or if you will reinsert the pages after it again.
 */
function timetable_delete_required_pages()
{
    $timetable_page_new = get_page_by_path(TIMETABLE_PAGE_SLUG_NEW, OBJECT, 'page');
    if ($timetable_page_new !== null)
    {
        wp_delete_post($timetable_page_new->ID, true);
    }

    $timetable_page_all = get_page_by_path(TIMETABLE_PAGE_SLUG_ALL, OBJECT, 'page');
    if ($timetable_page_all !== null)
    {
        wp_delete_post($timetable_page_all->ID, true);
    }

    $timetable_page_my = get_page_by_path(TIMETABLE_PAGE_SLUG_MY, OBJECT, 'page');
    if ($timetable_page_my !== null)
    {
        wp_delete_post($timetable_page_my->ID, true);
    }
}

/**
 * Insert the pages 'my-timetable', 'new-timetable' & 'all-timetable' which are required for this plugin. 
 * The function does no check if the pages are already existing before inserting them.
 * So, please use this function only if you are sure that pages are not existing.
 */
function timetable_insert_required_pages()
{
    $timetable_page_new = array(
        'post_title' => __('New Timetable', TIMETABLE_DOMAIN),
        'post_name' => TIMETABLE_PAGE_SLUG_NEW,
        'post_status' => 'publish',
        'post_type' => 'page',
    );
    $page_id = wp_insert_post($timetable_page_new);

    $timetable_page_all = array(
        'post_title' => __('All Timetables', TIMETABLE_DOMAIN),
        'post_name' => TIMETABLE_PAGE_SLUG_ALL,
        'post_status' => 'publish',
        'post_type' => 'page',
    );
    $page_id = wp_insert_post($timetable_page_all);

    $timetable_page_my = array(
        'post_title' => __('My Timetable', TIMETABLE_DOMAIN),
        'post_name' => TIMETABLE_PAGE_SLUG_MY,
        'post_status' => 'publish',
        'post_type' => 'page',
    );
    $page_id = wp_insert_post($timetable_page_my);
}

/**
 * Remove directory with all its content (files & subfolders).
 * @param string $directory Path to the directory.
 */
function timetable_remove_directory_with_content(string $directory)
{
    if (is_dir($directory))
    {
        $objects = scandir($directory);
        foreach ($objects as $object)
        {
            if ($object != "." && $object != "..")
            {
                if (filetype($directory . "/" . $object) == "dir") timetable_remove_directory_with_content($directory . "/" . $object);
                else unlink($directory . "/" . $object);
            }
        }
        reset($objects);
        rmdir($directory);
    }
}
