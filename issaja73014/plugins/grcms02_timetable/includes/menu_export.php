<?php

/**
 * Add submenu page 'Export Timatable' in the admin area.
 * @Hook admin_menu
 */
function timetable_add_submenu_page_export()
{
	add_submenu_page(
		'edit.php?post_type=timetable',
		__('Export Timetable to XML/JSON/CSV', TIMETABLE_DOMAIN),
		__('Export Timetable', TIMETABLE_DOMAIN),
		'activate_plugins',
		'timetable_export',
		'timetable_fill_submenu_page_content_export'
	);

	/**
	 * Fill the submenu page 'Export Timatable'.
	 */
	function timetable_fill_submenu_page_content_export()
	{
		$button_style = 'style="text-align: center;min-width: 30%; margin-top: 1em;"';
?>
		<h1><?php echo get_admin_page_title(); ?></h1>
		<div class="wrap" style="display: grid; grid-template-columns: 1fr 1fr; grid-gap: 2em;">
			<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
				<h3><?php _e('Select a timetable:', TIMETABLE_DOMAIN) ?></h3>
				<p><?php _e('Allowed statuses are ( publish , private )', TIMETABLE_DOMAIN) ?></p>
				<select name="export_timetable" id="id_export_timetable" style="min-width:30%;max-width:100%;" required>
					<option hidden disabled selected value></option>
					<?php
					$args = array(
						'post_type' => 'timetable',
						'post_status' => array('publish', 'private'),
						'orderby' => 'post_title',
						'order' => 'ASC',
					);
					$query_timetable = new WP_Query($args);
					global $post;
					while ($query_timetable->have_posts()) : $query_timetable->the_post();
						$timetable_slug = esc_html($post->post_name);
						$timetable_title = esc_html($post->post_title);
						echo '<option value="' . $timetable_slug . '"';
						if (isset($_POST['export_timetable'])) :
							if ($timetable_slug == $_POST['export_timetable']) :
								echo ' selected ';
							endif;
						endif;
						echo '>' . $timetable_title . '</option>';
					endwhile;
					wp_reset_query();
					?>
				</select>
				<h3><?php _e('Choose export format:', TIMETABLE_DOMAIN) ?></h3>
				<label>
					<input type="radio" name="export_format" value="xml" <?php
																			if (isset($_POST['export_format'])) :
																				if ("xml" == $_POST['export_format']) :
																					echo ' checked ';
																				endif;
																			endif;
																			?> required>XML</input>
				</label><br>
				<label>
					<input type="radio" name="export_format" value="json" <?php
																			if (isset($_POST['export_format'])) :
																				if ("json" == $_POST['export_format']) :
																					echo ' checked ';
																				endif;
																			endif;
																			?> required>JSON</input>
				</label><br>
				<label>
					<input type="radio" name="export_format" value="csv" <?php
																			if (isset($_POST['export_format'])) :
																				if ("csv" == $_POST['export_format']) :
																					echo ' checked ';
																				endif;
																			endif;
																			?> required>CSV</input>
				</label><br>
				<?php submit_button(__('Export Timetable', TIMETABLE_DOMAIN), 'primary', 'doExportTimetable', true, $button_style); ?>
			</form>
	<?php

		if (isset($_POST['doExportTimetable']) && isset($_POST['export_timetable']) && isset($_POST['export_format']))
		{
			$export_timetable = $_POST['export_timetable'];
			$export_format = $_POST['export_format'];
			$timetable_meta;
			$timetable_slug;
			$args = array(
				'post_type' => 'timetable',
				'name' => $export_timetable,
				"posts_per_page" => 1,
			);
			$query_timetable = new WP_Query($args);
			global $post;
			while ($query_timetable->have_posts()) : $query_timetable->the_post();
				$timetable_slug = esc_html($post->post_name);
				$timetable_title = esc_html($post->post_title);
				if ($timetable_slug == $export_timetable) :
					$timetable_meta = get_post_meta($post->ID, TIMETABLE_META_COURSE, true);
				endif;
			endwhile;
			wp_reset_query();

			$export_content = '';

			if ($export_format == 'xml') :
				$xml = new SimpleXMLElement('<timetable/>');
				function array_to_xml(SimpleXMLElement $element, array $data)
				{
					foreach ($data as $key => $value)
					{
						if (is_array($value))
						{
							$new_element = $element->addChild($key);
							array_to_xml($new_element, $value);
						}
						else
						{
							$element->addChild($key, $value);
						}
					}
				}
				array_to_xml($xml, $timetable_meta);
				$dom = new DOMDocument();
				$dom->preserveWhiteSpace = true;
				$dom->formatOutput = true;
				$dom->loadXML($xml->asXML());
				$export_content = $dom->saveXML();
			elseif ($export_format == 'json') :
				$export_content = json_encode($timetable_meta, JSON_PRETTY_PRINT);
			elseif ($export_format == 'csv') :
				$array_day = array('day1', 'day2', 'day3', 'day4', 'day5');
				$array_time = array('time1', 'time2', 'time3', 'time4', 'time5', 'time6');
				$export_content .= '"timetable","' . implode('","', $array_time) . '"' . PHP_EOL;
				foreach ($timetable_meta as $key => $value) :
					$export_content .= '"' . $key . '","' . implode('","', $value) . '"' . PHP_EOL;
				endforeach;
			endif;

			$export_file_name = 'timetable_' . $export_timetable . '.' . $export_format;

			$upload_folder = wp_get_upload_dir();
			$export_folder_name = "exported_timetables";
			$export_folder_location = $upload_folder['basedir'] . '/' . $export_folder_name;

			if (!file_exists($export_folder_location))
			{
				mkdir($export_folder_location, 0777, true);
			}

			$export_file_location = $export_folder_location . '/' . $export_file_name;
			$export_file_url = $upload_folder['baseurl'] . '/' . $export_folder_name . '/' . $export_file_name;
			file_put_contents($export_file_location, stripslashes($export_content));

			echo '<div>';
			echo '<textarea name="export_content" cols="80" rows="20" style="display:block;width:80%;" readonly>' . $export_content . '</textarea>';
			echo '<a href="' . $export_file_url . '" class="button button-primary" ' . $button_style . ' download>Download File</a>';
			echo '</div>';
		}
		echo '</div>';
	}
}
add_action('admin_menu', 'timetable_add_submenu_page_export');
