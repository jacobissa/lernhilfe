<?php

/**
 * Template name: Change my private timetable
 * Post type: page
 * Post slug: new-timetable
 */

get_header();
get_sidebar(); ?>

<main class="site-content">
    <header class="post-header">
        <h2 class="post-title">
            <a href="<?php esc_url(get_permalink()); ?>"><?php _e('Create Timetable', TIMETABLE_DOMAIN); ?></a>
        </h2>
    </header>
    <div>
        <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
            <div class='title_status'>
                <h3><?php _e('Insert a Title:', TIMETABLE_DOMAIN) ?></h3>
                <input type="text" name="timetable_title" autocomplete="off" required>
            </div>
            <div class='timetable-container'>
                <table class="table-timetable">
                    <?php
                    $query_course = new WP_Query(array(
                        'post_type' => 'course',
                        'order' => 'ASC',
                        'orderby' => 'title',
                    ));
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
                            $meta_key = TIMETABLE_META_COURSE . '[' . $day . '][' . $time . ']';
                            echo '<td>';
                            echo '<select name="' . $meta_key . '" ';
                            echo 'id="' . $meta_key . '">';
                            echo '<option value/>';
                            global $post;
                            while ($query_course->have_posts()) : $query_course->the_post();
                                $course_slug = esc_html($post->post_name);
                                $course_title = esc_html($post->post_title);
                                $post_short_name = esc_html(get_post_meta($post->ID, LEARNINGAID_META_COURSE_SHORT_NAME, true));
                                echo '<option value="' . $course_slug . '"';
                                echo '>' . $post_short_name . '</option>';
                            endwhile;
                            echo '</select>';
                            echo '</td>';
                        endforeach;
                        echo '</tr>';
                    endforeach;
                    wp_reset_query(); ?>
                </table>
            </div>
            <input type="submit" name="doCreateTimetable" id="doCreateTimetable" class="submit-button" value="<?php _e('Create Timetable', TIMETABLE_DOMAIN); ?>">
        </form>

        <?php

        if (isset($_POST['doCreateTimetable']) && isset($_POST['timetable_title']) && isset($_POST[TIMETABLE_META_COURSE]))
        {
            // delete all private timetable
            $current_user = wp_get_current_user();
            $private_posts = get_posts(array(
                'author' =>  $current_user->ID,
                'numberposts' => -1,
                'post_type' => 'timetable',
                'post_status' => 'private',
            ));
            foreach ($private_posts as $private_post)
            {
                wp_delete_post($private_post->ID, true);
            }

            // insert new private timetable
            $timetable_title = $_POST['timetable_title'];
            $meta_value = $_POST[TIMETABLE_META_COURSE];
            $new_post = array(
                'post_title' => $timetable_title,
                'post_status' => 'private',
                'post_type' => 'timetable',
                'meta_input' => array(
                    TIMETABLE_META_COURSE => $meta_value,
                ),
            );
            $post_id = wp_insert_post($new_post);
            if ($post_id)
            {
                echo '<a class="status-message" href="' . get_permalink(get_page_by_path(TIMETABLE_PAGE_SLUG_MY)) . '">';
                _e('The timetable has been successfully created.', TIMETABLE_DOMAIN);
                echo ' ';
                _e('Click here to open it.', TIMETABLE_DOMAIN);
                echo '</a>';
            }
            else
            {
                echo '<p class="status-message">' . __('The timetable could not be created.', TIMETABLE_DOMAIN) . '</p>';
            }
        } ?>
    </div>
</main>

<?php get_footer(); ?>