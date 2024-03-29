<?php
$lesson_query = new WP_Query(
    [
        'post_type' => 'lesson',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => 'lesson_course',
                'value' => $post->post_name
            ],
        ],
        'orderby' => 'id',
        'order' => 'ASC'
    ]
); ?>
<div class="lessons-grid">
    <?php while ($lesson_query->have_posts()) {
        $lesson_query->the_post();
        ?>
        <a class="lesson-container" href="<?php the_permalink($lesson_query->post); ?>">
            <span class="lesson-list-lesson-name"><?php echo $lesson_query->post->post_title ?></span>
        </a>
        <?php
    }

    wp_reset_postdata(); ?>
</div>