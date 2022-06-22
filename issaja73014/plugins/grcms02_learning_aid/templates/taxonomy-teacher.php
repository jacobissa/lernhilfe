<?php

/**
 * Template name: View taxonomy teacher
 * Post type: course
 * Taxonomy slug: teacher
 */

get_header();
get_sidebar(); ?>
<main class="site-content">
	<header class="post-header">
		<h2 class="post-title">
			<a href="<?php echo esc_url(get_permalink(get_queried_object()->term_id)); ?>"><?php echo get_queried_object()->name; ?></a>
		</h2>
	</header>
	<div class=" teacher-description">
		<p><?php echo get_queried_object()->description; ?></p>
	</div>
	<div class="post-content">
		<?php
		if (have_posts()) :
			while (have_posts()) : the_post(); ?>
				<div class="post-card-container">
					<h2 class="post-card-title">
						<?php the_title('<a href="' . esc_url(get_permalink()) . '">', '</a>'); ?>
					</h2>
					<div class="post-card-description">
						<?php
						$teacher_list = wp_get_post_terms($post->ID, 'teacher');
						foreach ($teacher_list as $teacher) :
							$teacher_link = get_term_link($teacher);
							echo '<a href="' . $teacher_link . '">' . $teacher->name . '</a>';
						endforeach; ?>
					</div>
				</div>
		<?php
			endwhile;
		endif; ?>
	</div>
</main>

<?php get_footer(); ?>