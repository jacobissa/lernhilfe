<?php
get_header();
get_sidebar();

if (have_posts()) {
    while (have_posts()) {
        the_post();
        $teacher_list = get_the_terms($post, 'teacher');
        $teacher_string = $teacher_list == null ? 'Kein Dozent' : join(', ', wp_list_pluck($teacher_list, 'name'));
        ?>
        <div class="course_content <?php echo esc_attr(implode(' ', get_post_class())); ?>">
            <div class="course_info">
                <h1 class="course_heading">
                    <?php the_title(); ?>
                </h1>
                <span class="teacher_names"><?php echo $teacher_string; ?></span>
            </div>
            <?php
            $view = $_GET['view'] ?? 'lessons';
            $valid_views = ['summaries', 'index-cards', 'lessons'];
            if (!in_array($view, $valid_views)) {
                $view = 'lessons';
            }
            ?>
            <div class="view_switch">
                <a class="<?php echo $view == 'lessons' ? 'selected' : '' ?>" href="?view=lessons">
                    <span>Lerneinheiten</span>
                </a>
                <a class="<?php echo $view == 'index-cards' ? 'selected' : '' ?>" href="?view=index-cards">
                    <span>Karteikarten</span>
                </a>
                <a class="<?php echo $view == 'summaries' ? 'selected' : '' ?>" href="?view=summaries">
                    <span>Zusammenfassungen</span>
                </a>
            </div>

            <?php
            switch ($view) {
                case 'summaries':
                    include TEMPLATEPATH . '/partials/summaries.php';
                    break;
                case 'index-cards':
                    include TEMPLATEPATH . '/partials/index-cards.php';
                    break;
                case 'lessons':
                    include TEMPLATEPATH . '/partials/lessons.php';
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