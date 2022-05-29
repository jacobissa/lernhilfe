<?php

/**
 * Template name: View single exercise
 * Post type: exercise
 */

get_header();
get_sidebar(); ?>

<main class="site-content">
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
						$course_slug = esc_html(get_post_meta(get_the_ID(), LEARNINGAID_META_EXERCISE_COURSE, true));
						$query_course = new WP_Query(array(
							'post_type' => 'course',
							'name' => $course_slug,
							"posts_per_page" => 1,
						));
						global $post;
						while ($query_course->have_posts()) : $query_course->the_post();
							if (esc_html($post->post_name) === $course_slug) :
								echo '<a href="' . get_post_permalink($post->ID) . '">' . esc_html($post->post_title) . ' ' . '</a>';
								break;
							endif;
						endwhile;
						wp_reset_query(); ?>
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
					<?php
					if (isset($_POST['doCloseCorrection'])) :
						echo "<script>hideCorrection();</script>";
					elseif (isset($_POST['doSubmitAnswers'])) :
						$all_entries = array();
						foreach ($_POST as $key => $value) :
							if (str_contains($key, 'question_')) :
								$blockid = strtr($key, array('question_' => ''));
								$question = $value;
								$solution = (isset($_POST['solution_' . $blockid])) ? $_POST['solution_' . $blockid] : '';
								$answer = (isset($_POST['answer_' . $blockid])) ? $_POST['answer_' . $blockid] : '';

								$entry = array(
									'question' => $question,
									'solution' => explode(';', $solution),
									'answer' => $answer,
								);
								array_push(
									$all_entries,
									$entry
								);
							endif;
						endforeach; ?>
						<script>
							showCorrection();
						</script>
						<main class="correction-content">
							<h3 class="correction-title"><?php _e('Thank you for answers! Here is the correction', LEARNINGAID_DOMAIN); ?></h3>
							<?php
							foreach ($all_entries as $entry) :
								$question = $entry['question'];
								$solution_array = $entry['solution'];
								$answer = $entry['answer'];
								$mark_per_keyword = 1 / count($solution_array);
								$collected_marks = 0;
								$founded_keywords = array();
								$missed_keywords = array();
								foreach ($solution_array as $solution) :
									if (stripos($answer, $solution) !== false) :
										array_push($founded_keywords, $solution);
										$collected_marks += $mark_per_keyword;
									else :
										array_push($missed_keywords, $solution);
									endif;
								endforeach; ?>
								<div class="block-correction-container">
									<table>
										<tr>
											<td><?php _e('Question:', LEARNINGAID_DOMAIN); ?></td>
											<td><?php echo $question; ?></td>
										</tr>
										<tr>
											<td><?php _e('Your Answer:', LEARNINGAID_DOMAIN); ?></td>
											<td><?php echo $answer; ?></td>
										</tr>
										<tr>
											<td><?php _e('Correct Words:', LEARNINGAID_DOMAIN); ?></td>
											<td><?php echo implode(" ; ", $founded_keywords); ?></td>
										</tr>
										<tr>
											<td><?php _e('Missed Words:', LEARNINGAID_DOMAIN); ?></td>
											<td><?php echo implode(" ; ", $missed_keywords); ?></td>
										</tr>
										<tr>
											<td><?php _e('Mark:', LEARNINGAID_DOMAIN); ?></td>
											<td><?php echo ceil($collected_marks * 100) . '%'; ?></td>
										</tr>
									</table>
								</div>
							<?php
							endforeach; ?>
						</main>
					<?php
					endif; ?>
				</div>
			</div>
	<?php
		endwhile;
	endif; ?>
</main>

<?php get_footer(); ?>