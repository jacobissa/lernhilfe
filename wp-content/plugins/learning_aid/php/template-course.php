<?php get_header(); ?>

<main id="site-content"> <?php
	if (have_posts()):
		$i = 0;
		while (have_posts()): the_post();
			$i++;
			if ($i > 1): ?>
				<hr class="my-separator"/> <?php 
			endif; ?>
			<article class="my_post">
				<h2 class="my_title"> <?php
					if ( is_singular()):
						the_title();
					else:
						the_title( '<a href="' . esc_url( get_permalink() ) . '">', '</a>' );
					endif; ?>
				</h2>
				<div class="my_teachers"> <?php
					$teacher_list = wp_get_post_terms($post->ID, 'teacher');
					foreach ($teacher_list as $teacher):
						$teacher_link = get_term_link($teacher);
						echo '<a href="' . $teacher_link . '">' . $teacher->name . ' ' . '</a>';
					endforeach; ?>
				</div>
				<div class="my_article_content"><?php the_content(); ?></div>
			</article> <?php 
		endwhile;
	else: ?>
 		<div class="my_empty_page"/> <?php 
	endif; ?>
</main>

<?php get_footer(); ?>
