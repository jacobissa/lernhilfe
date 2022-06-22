<?php


/**
 * Load custom template for the post type 'lesson'.
 * @Hook {$type}_template
 */
add_filter('single_template', 'learningaid_custom_template_single');
function learningaid_custom_template_single($template)
{
    global $post;
    if ($post != null && $post->post_type != null && $post->post_type != '')
    {
        if ($post->post_type == 'lesson')
        {
            if (file_exists(LEARNINGAID_PLUGIN_LOCATION_DIR . '/templates/single-lesson.php'))
            {
                return LEARNINGAID_PLUGIN_LOCATION_DIR . '/templates/single-lesson.php';
            }
        }
    }
    return $template;
}


/**
 * Load custom template for the taxonomy 'teacher' which is in the post type 'course'.
 * @Hook {$type}_template
 */
add_filter('taxonomy_template', 'learningaid_custom_template_taxonomy');
function learningaid_custom_template_taxonomy($template)
{
    global $post;
    if ($post != null && $post->post_type != null && $post->post_type != '')
    {
        if ($post->post_type == 'course')
        {
            if (file_exists(LEARNINGAID_PLUGIN_LOCATION_DIR . '/templates/taxonomy-teacher.php'))
            {
                return LEARNINGAID_PLUGIN_LOCATION_DIR . '/templates/taxonomy-teacher.php';
            }
        }
    }
    return $template;
}
