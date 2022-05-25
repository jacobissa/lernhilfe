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
endif;
