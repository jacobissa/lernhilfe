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
        <span>Kursname</span>
        <span>Dozent</span>
    </div>
    <ul class="striped-list">
        <?php while ($query->have_posts()) :
            $query->the_post();
            $custom = get_post_custom();
            $teacher_list = get_the_terms( $query->post, 'teacher' );
            $teacher_string = $teacher_list == null ? 'Kein Dozent' : join(', ', wp_list_pluck($teacher_list, 'name'));
            ?>
            <li class="striped-list-item">
                <a class="course-list-anchor" href="<?php the_permalink(); ?>">
                    <span class="list-prof-name"><?php echo the_title() ?></span>
                    <span><?php echo $teacher_string; ?></span>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>