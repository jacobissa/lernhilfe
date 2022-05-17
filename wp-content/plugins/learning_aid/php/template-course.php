<?php get_header(); ?>

<main id="site-content">
	<?php
	if (have_posts()) :
		$i = 0;
		while (have_posts()) : the_post();
			$i++;
			if ($i > 1) : ?>
				<hr class="post-separator" /> <?php
											endif; ?>
			<article <?php post_class(); ?>>
				<header class="entry-header has-text-align-center">
					<h2 class="entry-title"> <?php
												if (is_singular()) :
													the_title();
												else :
													the_title('<a href="' . esc_url(get_permalink()) . '">', '</a>');
												endif; ?>
					</h2>
					<div class="entry-categories"> <?php
													$teacher_list = wp_get_post_terms($post->ID, 'teacher');
													foreach ($teacher_list as $teacher) :
														$teacher_link = get_term_link($teacher);
														echo '<a href="' . $teacher_link . '">' . $teacher->name . ' ' . '</a>';
													endforeach; ?>
					</div>
				</header>
				<div class="entry-content"><?php the_content(); ?></div>
			</article> <?php
					endwhile;
				else : ?>
		<div class="my_empty_page" /> <?php
									endif; ?>
</main>

<?php get_footer(); ?>