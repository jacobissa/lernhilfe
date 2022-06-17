<?php
get_header();
get_sidebar();

if (have_posts()) {
    while (have_posts()) {
        the_post();
        $teacher_list = get_the_terms($post, 'teacher');
        $has_teachers = !($teacher_list == null || is_wp_error($teacher_list) || count($teacher_list) == 0);

        ?>
        <div class="course-content <?php echo esc_attr(implode(' ', get_post_class())); ?>">
            <div class="course-info">
                <h1 class="course-heading">
                    <?php the_title(); ?>
                </h1>
                <span class="teacher-names">
                    <?php echo $has_teachers ?
                        join(', ', array_map(
                            function (WP_Term $teacher): string {
                                return '<a href="' . get_term_link($teacher) . '">' . $teacher->name . '</a>';
                            },
                            $teacher_list)) :
                        __('No teacher', THEME_DOMAIN); ?>
                </span>
            </div>
            <?php
            $view = $_GET['view'] ?? 'lessons';
            $valid_views = ['summaries', 'index-cards', 'lessons'];
            if (!in_array($view, $valid_views)) {
                $view = 'lessons';
            }
            ?>
            <div class="view-switch">
                <a class="<?php echo $view == 'lessons' ? 'selected' : '' ?>" href="?view=lessons">
                    <span><?php _e('Lessons', THEME_DOMAIN); ?></span>
                </a>
                <a class="<?php echo $view == 'index-cards' ? 'selected' : '' ?>" href="?view=index-cards">
                    <span><?php _e('Index cards', THEME_DOMAIN); ?></span>
                </a>
                <a class="<?php echo $view == 'summaries' ? 'selected' : '' ?>" href="?view=summaries">
                    <span><?php _e('Summaries', THEME_DOMAIN); ?></span>
                </a>
            </div>

            <?php
            switch ($view) {
                case 'summaries':
                    get_template_part('partials/summaries');
                    break;
                case 'index-cards':
                    get_template_part('partials/index-cards/index-cards');
                    break;
                case 'lessons':
                    get_template_part('partials/lessons');
                    break;
                default:
                    die('unknown view "' . $view . '"');
            }
            ?>
        </div>
        <?php
    }
} ?>
<?php get_footer(); ?>