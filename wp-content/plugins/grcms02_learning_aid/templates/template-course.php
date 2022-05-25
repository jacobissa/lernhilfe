<?php get_header();
get_sidebar(); ?>

<main id="site-content">
	<?php
	if (have_posts()) :
		$i = 0;
		while (have_posts()) : the_post();
			$i++;
			if ($i > 1) : ?>
				<hr class="post-separator" />
			<?php
			endif; ?>
			<article <?php post_class(); ?>>
				<header class="post-header">
					<h2 class="post-title">
						<?php the_title('<a href="' . esc_url(get_permalink()) . '">', '</a>'); ?>
					</h2>
					<div class="post-categories">
						<?php
						$teacher_list = wp_get_post_terms($post->ID, 'teacher');
						foreach ($teacher_list as $teacher) :
							$teacher_link = get_term_link($teacher);
							echo '<a href="' . $teacher_link . '">' . $teacher->name . '</a>';
						endforeach; ?>
					</div>
				</header>
				<div>
					<?php
					$exercise_query = new WP_Query(array(
						'posts_per_page' => -1,
						'post_type' => 'exercise',
						'meta_key' => META_EXERCISE_COURSE,
						'meta_value' => basename(get_permalink(get_the_ID())),
					));
					$exercise_list = $exercise_query->posts;
					if ($exercise_list != null)
					{
						echo '<div class="exercise-container">';
						foreach ($exercise_list as $exerecise) :
							echo '<a href="' . esc_url(get_permalink($exerecise->ID)) . '">' . $exerecise->post_title . '</a>';
						endforeach;
						echo '</div>';
					}
					wp_reset_query(); ?>
				</div>
				<div class="post-content"><?php the_content(); ?></div>
			</article>
	<?php
		endwhile;
	endif; ?>
</main>

<?php get_footer(); ?>