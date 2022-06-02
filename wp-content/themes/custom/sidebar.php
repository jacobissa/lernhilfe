<?php
$course_query = new WP_Query(
    array(
        'post_type' => 'course',
        'post_status' => 'publish',
        'posts_per_page' => -1
    )
); ?>
<div id="navbar" class="closed">
    <ul class="navbar_list">
        <?php while ($course_query->have_posts()) :
            $course_query->the_post();
            $custom = get_post_custom($course_query->post->ID);
            ?>
            <li class="navbar_list_item">
                <a class="navbar_list_anchor" tabindex="-1" href="<?php the_permalink(); ?>">
                    <span><?php echo $custom['short_name'][0]; ?></span>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>

    <?php wp_reset_postdata(); ?>
</div>