<?php

if (!defined('WPINC')) {
    die;
}

if (!function_exists('move_uploaded_file')) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
}
?>
<div id="summaries">
    <div id="summaries-list-header">
        <span>Zusammenfassung</span>
        <span>Datum</span>
    </div>
    <ul class="striped-list" id="summaries_list">
        <?php
        $upload_dir = wp_upload_dir();
        $sub_dir = basename(get_the_permalink());
        // Get all .pdf files in the course upload directory
        $pdf_files = glob($upload_dir['basedir'] . "/" . $sub_dir . "/*.pdf");
        // Sort by date
        usort($pdf_files, function ($a, $b) {
            return filemtime($a) - filemtime($b);
        });
        foreach ($pdf_files as $file) {
            $sub_url = explode('wp-content/uploads', $file)[1];
            $full_url = $upload_dir['baseurl'] . $sub_url;
            echo('<li class="striped-list-item">
                        <a class="summaries-list-anchor" id="wp-block-file-pdf" target="_blank" href="' . $full_url . '">
                        <span class="list-file-name">' . basename($full_url) . '</span>
                        <span>' . date("d.m.Y H:i", filemtime($file)) . '</span>
                        </a></li>');
        }
        ?>
    </ul>
    <div id="add_summary_container">
        <h4>Neue Zusammenfassung hinzufügen</h4>
        <form id="add_summary_form" action="" onsubmit="addSummary(); return false;" enctype="multipart/form-data">
            <input type="hidden" name="course_slug" value=" <?php echo(basename(get_the_permalink())) ?> ">
            <input type="file" name="summary_to_upload" id="summary_to_upload" accept="application/pdf">
            <input type="submit" name="add_summary" id="add_summary" disabled="disabled" value="Hinzufügen">
        </form>
    </div>
</div>

