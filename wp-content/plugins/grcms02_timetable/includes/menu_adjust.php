<?php

/**
 * Add submenu page 'Adjust Timatable' in the admin area.
 * @Hook admin_menu
 */
function timetable_add_submenu_page_adjust()
{
    add_submenu_page(
        'edit.php?post_type=timetable',
        __('Adjust Timeslots', TIMETABLE_DOMAIN),
        __('Adjust Timeslots', TIMETABLE_DOMAIN),
        'activate_plugins',
        'timetable_adjust',
        'timetable_fill_submenu_page_adjust_adjust'
    );

    /**
     * Fill the submenu page 'Adjust Timetable'.
     */
    function timetable_fill_submenu_page_adjust_adjust()
    {
        $button_style = 'style="text-align: center;min-width: 15%; margin-top: 1em;"';
        $all_possible_option_values = array('06:00', '06:15', '06:30', '06:45', '07:00', '07:15', '07:30', '07:45', '08:00', '08:15', '08:30', '08:45', '09:00', '09:15', '09:30', '09:45', '10:00', '10:15', '10:30', '10:45', '11:00', '11:15', '11:30', '11:45', '12:00', '12:15', '12:30', '12:45', '13:00', '13:15', '13:30', '13:45', '14:00', '14:15', '14:30', '14:45', '15:00', '15:15', '15:30', '15:45', '16:00', '16:15', '16:30', '16:45', '17:00', '17:15', '17:30', '17:45', '18:00', '18:15', '18:30', '18:45', '19:00', '19:15', '19:30', '19:45', '20:00');
        $array_key = array('time1', 'time2', 'time3', 'time4', 'time5', 'time6');
        $option_time = get_option(TIMETABLE_OPTION_TIMESLOT, $array_key);
        foreach ($option_time as $key => $value)
        {
            $option_time[$key] = explode(" - ", $value);
        }
?>
        <h1><?php echo get_admin_page_title(); ?></h1>
        <div class="wrap">
            <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                <h3><?php _e('Here you can adjust the timeslots of the timetables', TIMETABLE_DOMAIN) ?></h3>
                <?php
                foreach ($option_time as $key => $value)
                {
                    $selected_from = (isset($_POST[TIMETABLE_OPTION_TIMESLOT])) ? $_POST[TIMETABLE_OPTION_TIMESLOT][$key][0] : $value[0];
                    $selected_to = (isset($_POST[TIMETABLE_OPTION_TIMESLOT])) ? $_POST[TIMETABLE_OPTION_TIMESLOT][$key][1] : $value[1]; ?>
                    <div style="width: 50%;display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; grid-template-rows: 1fr; gap: 1rem; margin: 1rem;">
                        <p style="font-weight: 600;">
                            <?php
                            switch ($key)
                            {
                                case 'time1':
                                    {
                                        _e('1st Timeslot', TIMETABLE_DOMAIN);
                                    }
                                    break;
                                case 'time2':
                                    {
                                        _e('2nd Timeslot', TIMETABLE_DOMAIN);
                                    }
                                    break;
                                case 'time3':
                                    {
                                        _e('3rd Timeslot', TIMETABLE_DOMAIN);
                                    }
                                    break;
                                case 'time4':
                                    {
                                        _e('4th Timeslot', TIMETABLE_DOMAIN);
                                    }
                                    break;
                                case 'time5':
                                    {
                                        _e('5th Timeslot', TIMETABLE_DOMAIN);
                                    }
                                    break;
                                case 'time6':
                                    {
                                        _e('6th Timeslot', TIMETABLE_DOMAIN);
                                    }
                                    break;
                                default:
                                    {
                                        echo $key;
                                    }
                            }
                            ?>
                        </p>
                        <select name="<?php echo TIMETABLE_OPTION_TIMESLOT . '[' . $key . '][0]'; ?>" required>
                            <?php
                            foreach ($all_possible_option_values as $option_value)
                            {
                                echo '<option value="' . $option_value . '"';
                                if ($option_value == $selected_from)
                                {
                                    echo ' selected';
                                }
                                echo '>' . $option_value . '</option>';
                            } ?>
                        </select>
                        <p style="text-align: center; font-weight: 600;">&#10132;</p>
                        <select name="<?php echo TIMETABLE_OPTION_TIMESLOT . '[' . $key . '][1]'; ?>" required>
                            <?php
                            foreach ($all_possible_option_values as $option_value)
                            {
                                echo '<option value="' . $option_value . '"';
                                if ($option_value == $selected_to)
                                {
                                    echo ' selected';
                                }
                                echo '>' . $option_value . '</option>';
                            } ?>
                        </select>
                    </div>
                <?php
                }
                ?>
                <?php submit_button(__('Save Changes', TIMETABLE_DOMAIN), 'primary', 'doAdjustTimetable', true, $button_style); ?>
            </form>
            <?php
            if (isset($_POST['doAdjustTimetable']) && isset($_POST[TIMETABLE_OPTION_TIMESLOT]))
            {
                foreach ($_POST[TIMETABLE_OPTION_TIMESLOT] as $key => $value)
                {
                    $option_time[$key] = $value[0] . " - " . $value[1];
                }
                $success = update_option(TIMETABLE_OPTION_TIMESLOT, $option_time);
                if ($success)
                {
                    echo '<p class="status-message-admin">' . __('The timeslots of the timetables has been successfully adjusted', TIMETABLE_DOMAIN) . '</p>';
                }
            } ?>
        </div>
<?php
    }
}
add_action('admin_menu', 'timetable_add_submenu_page_adjust');
