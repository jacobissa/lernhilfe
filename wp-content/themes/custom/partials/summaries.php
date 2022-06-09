<?php

if (!defined('WPINC')) {
    die;
}

if (!function_exists('move_uploaded_file')) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
}

$upload_dir = wp_upload_dir();

if (isset($_POST['add_summary']) && isset($_POST['course_slug']) && isset($_FILES['summary_to_upload'])) {
    $folder_name = htmlspecialchars($_POST['course_slug']);
    $folder_name = trim($folder_name);
    $upload_path = sprintf('%s/%s/', $upload_dir['basedir'], $folder_name);

    if (!file_exists($upload_path)) {
        mkdir($upload_path);
    }

    $file_name = htmlspecialchars($_FILES['summary_to_upload']['name']);
    $file_name = str_replace(' ', '_', $file_name);
    $temp_name = $_FILES['summary_to_upload']['tmp_name'];
    $file_type = wp_check_filetype($file_name);

    if ($file_type['ext'] != 'pdf') {
        echo 'File not supported';
    } else {
        if (file_exists($upload_path . $file_name)) {
            echo 'The file already exists';
        } else {
            if (move_uploaded_file($temp_name, $upload_path . $file_name)) {
                echo 'File was successfully uploaded';
            } else {
                echo 'The file was not uploaded';
            }
        }
    }
}
?>
<div id="summaries">
    <div id="summaries-list-header">
        <span>Zusammenfassung</span>
        <span>Datum</span>
    </div>
    <ul class="striped-list">
        <?php
        $sub_dir = basename(get_the_permalink());
        $pdf_files = glob($upload_dir['basedir'] . "/" . $sub_dir . "/*.pdf");
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
        <form id="add_summary_form" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="course_slug" value=" <?php echo(basename(get_the_permalink())) ?> ">
            <input type="file" name="summary_to_upload" id="summary_to_upload" accept="application/pdf">
            <input type="submit" name="add_summary" id="add_summary" value="Hinzufügen">
        </form>
    </div>
</div>

