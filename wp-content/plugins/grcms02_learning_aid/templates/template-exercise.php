<?php get_header();
get_sidebar(); ?>

<main id="site-content">
	<?php
	if (have_posts()) :
		$i = 0;
		while (have_posts()) : the_post();
			$i++;
			if ($i > 1) : ?>
				<div class="post-separator"></div>
			<?php
			endif; ?>
			<article <?php post_class(); ?>>
				<header class="post-header">
					<h2 class="post-title">
						<?php the_title('<a href="' . esc_url(get_permalink()) . '">', '</a>'); ?>
					</h2>
					<div class="post-categories">
						<?php
						$course_slug = esc_html(get_post_meta(get_the_ID(), META_EXERCISE_COURSE, true));
						if (get_course_data_by_slug($course_slug, $course_title, $course_url, $course_id)) :
							echo '<a href="' . $course_url . '">' . $course_title . '</a>';
						endif; ?>
					</div>
				</header>
				<form method="post" target="_self" id="form-question" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
					<div class=" post-content">
						<?php the_content(); ?>
					</div>
					<input type="submit" name="doSubmitAnswers" id="doSubmitAnswers" class="submit-button" value="<?php _e('Submit Answers', LEARNINGAID_DOMAIN); ?>" style="text-align: center;min-width: 30%; margin-top: 1em;">
				</form>
			</article>
			<div id='correction-container'>
				<form method="post" target="_self" id="form-correction" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
					<input type="submit" name="doCloseCorrection" id="doCloseCorrection" class="closeCorrection" value="&times;">
				</form>
				<div name="iframe-correction" id="iframe-correction">
					<?php require("exercise-correction.php"); ?>
				</div>
			</div>
	<?php
		endwhile;
	endif; ?>
</main>

<?php get_footer(); ?>