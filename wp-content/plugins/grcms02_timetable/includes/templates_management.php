<?php

/**
 * Load custom template for the post type 'timetable'.
 * @Hook {$type}_template
 */
function template_custom_template_single($template)
{
    global $post;
    if ($post != null && $post->post_type != null && $post->post_type != '')
    {
        if ($post->post_type == 'timetable')
        {
            if (!is_user_logged_in())
            {
                // Require the user to login in order to access this page
                auth_redirect();
            }
            elseif (file_exists(TIMETABLE_PLUGIN_LOCATION_DIR . '/templates/single-timetable.php'))
            {
                return TIMETABLE_PLUGIN_LOCATION_DIR . '/templates/single-timetable.php';
            }
        }
    }
    return $template;
}
add_filter('single_template', 'template_custom_template_single');


/**
 * Load custom templates for the pages 'my-timetable', 'new-timetable' & all-timetable.
 * @Hook {$type}_template
 */
function template_custom_template_page($template)
{
    global $post;
    if (is_page(TIMETABLE_PAGE_SLUG_NEW))
    {
        if (!is_user_logged_in())
        {
            // Require the user to login in order to access this page
            auth_redirect();
        }
        elseif (file_exists(TIMETABLE_PLUGIN_LOCATION_DIR . '/templates/page-new-timetable.php'))
        {
            return TIMETABLE_PLUGIN_LOCATION_DIR . '/templates/page-new-timetable.php';
        }
    }
    else if (is_page(TIMETABLE_PAGE_SLUG_MY))
    {
        if (!is_user_logged_in())
        {
            // Require the user to login in order to access this page
            auth_redirect();
        }
        elseif (file_exists(TIMETABLE_PLUGIN_LOCATION_DIR . '/templates/page-my-timetable.php'))
        {
            return TIMETABLE_PLUGIN_LOCATION_DIR . '/templates/page-my-timetable.php';
        }
    }
    else if (is_page(TIMETABLE_PAGE_SLUG_ALL))
    {
        if (!is_user_logged_in())
        {
            // Require the user to login in order to access this page
            auth_redirect();
        }
        elseif (file_exists(TIMETABLE_PLUGIN_LOCATION_DIR . '/templates/page-all-timetable.php'))
        {
            return TIMETABLE_PLUGIN_LOCATION_DIR . '/templates/page-all-timetable.php';
        }
    }
    return $template;
}
add_filter('page_template', 'template_custom_template_page');


function timetable_wp_footer()
{
    if (is_user_logged_in())
    {
        echo '<div id="myTimetable">';
        echo '<a href="' . get_permalink(get_page_by_path(TIMETABLE_PAGE_SLUG_MY)) . '">';
        echo '<img src= "' . TIMETABLE_PLUGIN_LOCATION_URL . '/images/timetable-floating-button.svg' . '" class="timetable-floating-button">';
        echo '</a></div>';
    }
}
add_action('wp_footer', 'timetable_wp_footer', 10);
