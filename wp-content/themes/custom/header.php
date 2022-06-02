<!DOCTYPE html>
<html lang="de">
<head>
    <title>Lernhilfe</title>
    <meta http-equiv="Content-Type"
          content="<?php bloginfo('html_type'); ?>" charset="<?php bloginfo('charset'); ?>"/>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link rel="stylesheet"
          href="<?php bloginfo('stylesheet_url'); ?>"
          type="text/css" media="screen"/>
    <link rel="stylesheet"
          href="<?php echo get_template_directory_uri() . '/styles/index-card.css'; ?>"
          type="text/css" media="screen"/>
    <?php
    if (is_singular()) {
        $post = get_queried_object();
        $postType = get_post_type_object(get_post_type($post));
        $post_type_name = esc_html($postType->name);
        $style_path = 'styles/' . $post_type_name . '.css';
        $style_file_path = get_theme_file_path($style_path);
        if (file_exists($style_file_path)) {
            $style_url = get_template_directory_uri() . '/' . $style_path;
            ?>
            <link rel="stylesheet"
                  href="<?php echo $style_url; ?>"
                  type="text/css" media="screen"/>
            <?php
        }
    }
    if (is_home()) {
        ?>
        <link rel="stylesheet"
              href="<?php echo get_template_directory_uri() . '/styles/index.css'; ?>"
              type="text/css" media="screen"/>
        <?php
    }

    wp_head();
    ?>
</head>
<body>
<header>
    <button class="icon_button" onclick="toggleMenu()">
        <svg fill="#ffffff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M 2 5 L 2 7 L 22 7 L 22 5 L 2 5 z M 2 11 L 2 13 L 22 13 L 22 11 L 2 11 z M 2 17 L 2 19 L 22 19 L 22 17 L 2 17 z"/>
        </svg>
    </button>
    <h1 id="header_text"><b>Lernhilfe</b> - GrCMS Team 2</h1>
</header>
<div id="content_container">