<?php
$wpb_all_query = new WP_Query(
    array(
        'post_type' => 'course',
        'post_status' => 'publish',
        'posts_per_page' => -1
    )
); ?>
<div id="navbar" class="closed">
    <ul class="navbar_list">
        <?php while ($wpb_all_query->have_posts()) :
            $wpb_all_query->the_post();
            ?>
            <li class="navbar_list_item">
                <a class="navbar_list_anchor" href="<?php the_permalink(); ?>">
                    <span><?php echo the_title(); ?></span>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>

    <?php wp_reset_postdata(); ?>
</div>