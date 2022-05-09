<?php
/**
 * Template for displaying custom post type 'course'
 */
?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<header class="entry-header has-text-align-center">
		<div class="entry-header-inner section-inner medium">
			<?php
				if ( is_singular() ):
					the_title( '<h1 class="entry-title">', '</h1>' );
				else:
					the_title( '<h2 class="entry-title heading-size-1"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
				endif;
			?>
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
	<?php if (is_single() ) { get_template_part('template-parts/navigation'); }?>
</article>