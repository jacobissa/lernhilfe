<?php
$course_query = new WP_Query(
    array(
        'post_type' => 'course',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'meta_value',
        'meta_key' => 'short_name',
        'order' => 'ASC'
    )
); ?>
<div id="navbar" class="closed">
    <ul class="navbar_list">
        <?php while ($course_query->have_posts()) :
            $course_query->the_post();
            ?>
            <li class="navbar-list-item">
                <a class="navbar-list-anchor" tabindex="-1" href="<?php the_permalink(); ?>">
                    <span><?php echo get_post_custom_values('short_name')[0]; ?></span>
                </a>
            </li>
        <?php endwhile;
        wp_meta(); ?>
    </ul>

    <?php wp_reset_postdata(); ?>
</div>