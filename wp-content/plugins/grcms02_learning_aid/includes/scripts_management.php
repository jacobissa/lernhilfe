<?php

/**
 * Add script (js) and style (css) files to the public area.
 * @Hook wp_enqueue_scripts
 */
function learningaid_enqueue_scripts_public()
{
    wp_deregister_script('learningaid-public-script');
    wp_register_script('learningaid-public-script', LEARNINGAID_PLUGIN_LOCATION_URL . '/scripts/public-script.js');
    wp_enqueue_script('learningaid-public-script');

    wp_deregister_style('learningaid-public-style');
    wp_register_style('learningaid-public-style', LEARNINGAID_PLUGIN_LOCATION_URL . '/styles/public-style.css');
    wp_enqueue_style('learningaid-public-style');
}
add_action('wp_enqueue_scripts', 'learningaid_enqueue_scripts_public');

/**
 * Hide unwanted elements in the admin area:
 * - Hide "Parent" dropdown for taxonomy 'teacher'
 * - Hide "additional css classes" from the gutenberg blocks in the post types (course & exercise).
 * @Hook admin_head
 */
function learningaid_hide_unwanted_elements_in_admin_head()
{
    $screen = get_current_screen();
    if ('teacher' == $screen->taxonomy) :
        $css = ".term-parent-wrap
  			{display:none;}";
    elseif ('course' == $screen->post_type || 'exercise' == $screen->post_type) :
        $css = ".editor-post-taxonomies__hierarchical-terms-input+.components-base-control,
  			.block-editor-block-inspector__advanced
  			{display:none;}";
    else :
        return;
    endif;

    if (!empty($css))
    {
        echo '<style type="text/css">' . $css . '</style>';
    }
}
add_action('admin_head', 'learningaid_hide_unwanted_elements_in_admin_head');
