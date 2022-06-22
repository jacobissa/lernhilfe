<?php

/** Prevent direct access to the file */
if (!defined('WPINC')) {
    die;
}
?>
<div id="summaries">
    <div id="summaries-list-header">
        <span><?php _e('Summary', THEME_DOMAIN) ?></span>
        <span><?php _e('Date', THEME_DOMAIN) ?></span>
        <span><?php _e('User', THEME_DOMAIN) ?></span>
    </div>
    <ul class="striped-list" id="summaries_list">
        <?php
        // Create a new WP_Query to get all the summaries attached to the current course
        $query = new WP_Query(array(
            'post_type' => 'attachment',
            'post_mime_type' => 'application/pdf',
            'post_parent' => get_the_ID(),
            'post_status' => 'inherit',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'ASC'
        ));

        // Loop through the attachments/posts and display the title, date and author in the summaries list
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                echo('<li class="striped-list-item">
                        <a class="summaries-list-anchor" id="wp-block-file-pdf" target="_blank" href="' . wp_get_attachment_url() . '">
                        <span class="list-file-name">' . get_the_title() . '</span>
                        <span>' . get_the_date('d.m.Y H:i') . '</span>
                        <span>' . get_the_author() . '</span>
                        </a></li>');
            }
        } else {
            echo('<li class="striped-list-item"><a class="summaries-list-anchor"><span>' . __('No Summaries found', THEME_DOMAIN) . '<span></a></li>');
        }

        wp_reset_postdata();
        ?>
    </ul>
    <div id="add_summary_container">
        <h4><?php _e('Add new summary', THEME_DOMAIN) ?></h4>
        <form id="add_summary_form" action="" onsubmit="addSummary(); return false;" enctype="multipart/form-data">
            <input type="hidden" name="course_slug" value=" <?php echo(basename(get_the_permalink())) ?> ">
            <input type="hidden" name="course_id" value=" <?php echo(get_the_ID()) ?> ">
            <input type="file" name="summary_to_upload" id="summary_to_upload" accept="application/pdf">
            <button type="submit" name="add_summary" id="add_summary" class="action-button" disabled="disabled">
                <?php _e('Add', THEME_DOMAIN) ?>
            </button>
        </form>
    </div>
</div>

