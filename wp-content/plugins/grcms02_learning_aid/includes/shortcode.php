<?php

/**
 * Add the shortcode [youtube-embed-url]
 * requires a content, which should contains the url of the youtube video;
 * and it accept the optional attributes width and height;
 * the youtube video url format should contain the video ID in the parameter v to be recognized;
 * If no width & height attributes are given, the values 100% will be used for them.
 * @Example-1 [youtube-embed-url width="560" height="315"]youtube.com/watch?v=abcdefghijk[/youtube-embed-url]
 * @Example-2 [youtube-embed-url]youtube.com/watch?v=abcdefghijk[/youtube-embed-url]
 * @return HTML-Element 'iframe' contains the embed link of the youtube video with the given width and height values of attributes (or with the values 100% if they are not given). If the content doesn't have a valid youtube video url or the format is not accepted (link doesn't have the v parameter), then the HTML-Element 'img' with a photo states that the link is invalid will be returned.
 */
function learningaid_shortcode_youtube_embed_url($atts, $content)
{
    $width = '100%';
    $height = '100%';
    $element = 'img';
    $src = LEARNINGAID_PLUGIN_LOCATION_URL . '/images/invalid_ytvideo.svg';
    if ($content != null && $content != '')
    {
        parse_str(parse_url($content, PHP_URL_QUERY), $youtube_link_vars);
        if (isset($youtube_link_vars['v']))
        {
            $youtube_id =  $youtube_link_vars['v'];
            if ($youtube_id != null && $youtube_id != '')
            {
                // The video ID has been found in the v parameter.
                $element = 'iframe';
                $src = "https://www.youtube-nocookie.com/embed/" . $youtube_id;
                $width = (isset($atts['width'])) ? $atts['width'] : $width;
                $height = (isset($atts['height'])) ? $atts['height'] : $height;
            }
        }
    }
    ob_start();
?>
    <<?php echo $element; ?> width="<?php echo $width; ?>" height="<?php echo $height; ?>" class="grcms02-block-ytvideo-save-iframe" src="<?php echo $src; ?>" allow="fullscreen;" frameBorder="0">
    </<?php echo $element; ?>>
<?php
    return ob_get_clean();
}
add_shortcode('youtube-embed-url', 'learningaid_shortcode_youtube_embed_url');
