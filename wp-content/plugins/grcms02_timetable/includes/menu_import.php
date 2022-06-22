<?php

/**
 * Add submenu page 'Import Timatable' in the admin area.
 * @Hook admin_menu
 */
function timetable_add_submenu_page_import()
{
	add_submenu_page(
		'edit.php?post_type=timetable',
		__('Import Timetable from XML/JSON/CSV', TIMETABLE_DOMAIN),
		__('Import Timetable', TIMETABLE_DOMAIN),
		'activate_plugins',
		'timetable_import',
		'timetable_fill_submenu_page_content_import'
	);

	/**
	 * Fill the submenu page 'Import Timetable'.
	 */
	function timetable_fill_submenu_page_content_import()
	{
		$button_style = 'style="text-align: center;min-width: 15%; margin-top: 1em;"';
?>
		<h1><?php echo get_admin_page_title(); ?></h1>
		<div class="wrap">
			<form method="post" enctype="multipart/form-data" class="wp-upload-form" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
				<h3><?php _e('Select a file:', TIMETABLE_DOMAIN) ?></h3>
				<p><?php _e('Allowed file types are ( xml , json , csv )', TIMETABLE_DOMAIN) ?></p>
				<input type="file" name="import_file" accept="application/xml, application/json, text/csv" style="display: block;" class="browser button button-hero">
				<h3><?php _e('Select the status:', TIMETABLE_DOMAIN) ?></h3>
				<select name="import_status" style="display: block; width:15%;" required>
					<option value="publish" selected><?php _e('Publish', TIMETABLE_DOMAIN); ?></option>
					<option value="draft"><?php _e('Draft', TIMETABLE_DOMAIN); ?></option>
					<option value="pending"><?php _e('Pending', TIMETABLE_DOMAIN); ?></option>
					<option value="private"><?php _e('Private', TIMETABLE_DOMAIN); ?></option>
				</select>
				<?php submit_button(__('Import Timetable', TIMETABLE_DOMAIN), 'primary', 'doImportTimetable', true, $button_style); ?>
			</form>
	<?php

		if (isset($_POST['doImportTimetable']) && isset($_POST['import_status']) && is_uploaded_file($_FILES['import_file']['tmp_name']) && $_FILES['import_file']['error'] == 0)
		{
			$import_status = $_POST['import_status'];
			$file_name = $_FILES['import_file']['name'];
			$file_type = $_FILES['import_file']['type'];
			$file_location = $_FILES['import_file']['tmp_name'];
			$file_content = file_get_contents($file_location);
			$meta_value = '';
			switch ($file_type)
			{
				case 'text/xml':
					{
						$xml_content = simplexml_load_string($file_content, "SimpleXMLElement", LIBXML_NOCDATA);
						if ($xml_content !== false)
						{
							$json_content = json_encode($xml_content);
							if ($json_content !== false)
							{
								$meta_value = json_decode($json_content, true);
							}
						}
					}
					break;
				case 'application/json':
					{
						$meta_value = json_decode($file_content, true);
					}
					break;
				case 'text/csv':
					{
						$fp = fopen($file_location, 'r');
						$csv_array = array();
						while ($row = fgetcsv($fp))
						{
							// shift the first element in each row and save it as key: (day1, day2, day3, day4, day5).
							$id = array_shift($row);
							$csv_array[$id] = $row;
						}
						fclose($fp);
						// shift the first row which contains the timeslots: (time1, time2, time3, time4, time5, time6).
						$timeslots = array_shift($csv_array);
						foreach ($csv_array as $day_key => $day_array)
						{
							foreach ($day_array as $time_key => $time_value)
							{
								if (isset($timeslots[$time_key]))
								{
									$timeslot = $timeslots[$time_key];
									$csv_array[$day_key][$timeslot] = $time_value;
									unset($csv_array[$day_key][$time_key]);
								}
							}
						}
						$meta_value = $csv_array;
					}
					break;
			}

			if (is_array($meta_value) && !empty($meta_value))
			{
				$new_post = array(
					'post_title' => $file_name,
					'post_status' => $import_status,
					'post_type' => 'timetable',
				);
				$post_id = wp_insert_post($new_post);
				$success = update_post_meta($post_id, TIMETABLE_META_COURSE, $meta_value);

				if ($success && $post_id)
				{
					echo '<p class="status-message-admin">' . __('The timetable has been successfully imported.', TIMETABLE_DOMAIN) . '</p>';
				}
				else
				{
					echo '<p class="status-message-admin">' . __('The timetable has not been imported.') . '</p>';
				}
			}
			else
			{
				echo '<p class="status-message-admin">' . __('The timetable has not been imported.') . '</p>';
			}
		}
		echo '</div>';
	}
}
add_action('admin_menu', 'timetable_add_submenu_page_import');
