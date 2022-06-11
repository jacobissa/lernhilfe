<?php get_header();
get_sidebar(); ?>
<?php
$query = new WP_Query(
    array(
        'post_type' => 'course',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    )
); ?>
    <div id="course-list-header">
        <span><?php _e('Course name', THEME_DOMAIN); ?></span>
        <span><?php _e('Teacher', THEME_DOMAIN); ?></span>
    </div>
    <ul class="striped-list">
        <?php while ($query->have_posts()) {
            $query->the_post();
            $teacher_list = get_the_terms($query->post, 'teacher');
            $has_teachers = !($teacher_list == null || is_wp_error($teacher_list) || count($teacher_list) == 0);
            ?>
            <li class="striped-list-item">
                <a class="course-list-anchor" href="<?php the_permalink(); ?>">
                    <span class="list-prof-name"><?php echo the_title() ?></span>
                    <span>
                        <?php echo $has_teachers ?
                            join(', ', wp_list_pluck($teacher_list, 'name')) :
                            __('No teacher', THEME_DOMAIN); ?>
                    </span>
                </a>
            </li>
        <?php } ?>
    </ul>
<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>