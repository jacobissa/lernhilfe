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
				<div class="my_course"> <?php
					$course_slug = esc_html(get_post_meta(get_the_ID(), META_FLASHCARD_COURSE, true));
					if (get_course_data_by_slug($course_slug, $course_title, $course_url, $course_id)):
						echo '<a href="' . $course_url . '">' . $course_title . ' ' . '</a>';
					endif; ?>
				</div>
				<div class="my_article_content"><?php the_content(); ?></div>
			</article> <?php 
		endwhile;
	else: ?>
 		<div class="my_empty_page"/> <?php 
	endif; ?>
</main>

<?php get_footer(); ?>
