<?php

/**
 * Template name: View my private timetable
 * Post type: page
 * Post slug: my-timetable
 */

get_header();
get_sidebar(); ?>

<main class="site-content">
    <?php
    $current_user = wp_get_current_user();
    $timetable_all = new WP_Query(
        array(
            'author' =>  $current_user->ID,
            'post_type' => 'timetable',
            'post_status' => 'private',
            'posts_per_page' => 1
        )
    );
    $i = 0;
    global $post;
    if ($timetable_all->have_posts()) : $timetable_all->the_post(); ?>
        <article <?php post_class(); ?>>
            <header class="post-header">
                <h2 class="post-title">
                    <?php the_title('<a href="' . esc_url(get_permalink()) . '">', '</a>'); ?>
                </h2>
            </header>
            <div class="change-container">
                <?php echo '<a href="' . get_permalink(get_page_by_path(TIMETABLE_PAGE_SLUG_NEW)) . '">' . __('Replace my private timetable', TIMETABLE_DOMAIN) . '</a>' ?>
                <?php echo '<a href="' . get_permalink(get_page_by_path(TIMETABLE_PAGE_SLUG_ALL)) . '">' . __('Show all published timetables', TIMETABLE_DOMAIN) . '</a>' ?>
            </div>
            <div class=" timetable-container">
                <table class="table-timetable">
                    <?php
                    $query_course = new WP_Query(array('post_type' => 'course'));
                    $meta_value = get_post_meta($post->ID, TIMETABLE_META_COURSE, true);
                    $array_day = array('day1', 'day2', 'day3', 'day4', 'day5');
                    $array_time = array('time1', 'time2', 'time3', 'time4', 'time5', 'time6');
                    $option_time = get_option(TIMETABLE_OPTION_TIMESLOT, $array_time); ?>
                    <tr>
                        <th></th>
                        <th><?php echo $option_time['time1']; ?></th>
                        <th><?php echo $option_time['time2']; ?></th>
                        <th><?php echo $option_time['time3']; ?></th>
                        <th><?php echo $option_time['time4']; ?></th>
                        <th><?php echo $option_time['time5']; ?></th>
                        <th><?php echo $option_time['time6']; ?></th>
                    </tr>
                    <?php
                    foreach ($array_day as $day) :
                        echo '<tr>';
                        echo '<td class="weekday-cell">' . timetable_get_weekday_name($day) . '</td>';
                        foreach ($array_time as $time) :
                            echo '<td>';
                            global $post;
                            while ($query_course->have_posts()) : $query_course->the_post();
                                $course_slug = esc_html($post->post_name);
                                $course_title = esc_html($post->post_title);
                                $post_short_name = esc_html(get_post_meta($post->ID, LEARNINGAID_META_COURSE_SHORT_NAME, true));
                                if (is_array($meta_value)) :
                                    if ($meta_value[$day][$time] == $course_slug) :
                                        echo $post_short_name;
                                    endif;
                                endif;
                            endwhile;
                            echo '</td>';
                        endforeach;
                        echo '</tr>';
                    endforeach;
                    echo '</table>';
                    wp_reset_query(); ?>
            </div>
        </article>
    <?php
    else : ?>
        <header class="post-header">
            <h2 class="post-title">
                <?php echo '<a href="' . esc_url(get_permalink()) . '">' . __('You have no private timetable yet', TIMETABLE_DOMAIN) . '</a>' ?>
            </h2>
        </header>
        <div class="change-container">
            <?php echo '<a href="' . get_permalink(get_page_by_path(TIMETABLE_PAGE_SLUG_NEW)) . '">' . __('Change my private timetable', TIMETABLE_DOMAIN) . '</a>' ?>
            <?php echo '<a href="' . get_permalink(get_page_by_path(TIMETABLE_PAGE_SLUG_ALL)) . '">' . __('Show all published timetables', TIMETABLE_DOMAIN) . '</a>' ?>
        </div>
    <?php
    endif;
    wp_reset_query(); ?>
</main>

<?php get_footer(); ?>