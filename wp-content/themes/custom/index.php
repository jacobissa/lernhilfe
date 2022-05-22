<?php get_header();
get_sidebar(); ?>
<?php
$wpb_all_query = new WP_Query(
    array(
        'post_type' => 'course',
        'post_status' => 'publish',
        'posts_per_page' => -1
    )
); ?>
    <div id="course_list_header">
        <span>Kursname</span>
        <span>Dozent</span>
    </div>
    <ul class="striped_list">
        <?php while ($wpb_all_query->have_posts()) :
            $wpb_all_query->the_post();
            $custom = get_post_custom();
            ?>
            <li class="striped_list_item">
                <a class="striped_list_anchor" href="<?php the_permalink(); ?>">
                    <span class="list_prof_name"><?php echo $custom['full_name'][0]; ?></span>
                    <span><?php echo $custom['prof'][0]; ?></span>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>