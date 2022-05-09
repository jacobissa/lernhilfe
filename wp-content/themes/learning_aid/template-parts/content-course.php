<?php
/**
 * Template for displaying custom post type 'course'
 */
?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<header class="entry-header has-text-align-center">
		<div class="entry-header-inner section-inner medium">
			<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
			<div class="entry-categories">
				<div class="entry-categories-inner">
					<?php
						$teacher_list = wp_get_post_terms($post->ID, 'teacher');
						foreach ($teacher_list as $teacher):
							$teacher_link = get_term_link( $teacher );
							echo "<a href=\"" . $teacher_link . "\">" . $teacher->name.' ' . "</a>";
						endforeach;
					?>
				</div>
			</div>
		</div>
	</header>
	<div class="post-inner">
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</div>
	<?php get_template_part('template-parts/navigation'); ?>
</article>