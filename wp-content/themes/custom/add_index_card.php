<?php /* Template Name: Add Index Card */ ?>
<?php get_header();
get_sidebar(); ?>
    <div class="index_card_container">
        <div class="index_card_lower"></div>
        <form class="index_card_upper" id="index_card_form" onsubmit="addIndexCard(); return false;" action="">
            <label for="index_card_question_input" hidden>Frage</label>
            <input id="index_card_question_input" type="text" name="question" placeholder="Frage"/>
            <hr/>
            <label for="index_card_answer_input" hidden>Antwort</label>
            <textarea id="index_card_answer_input" type="text" name="answer" placeholder="Antwort"></textarea>
            <button id="index_card_submit" type="submit" form="index_card_form">Speichern<img src="<?php echo get_template_directory_uri();?>/svg/save.svg" alt="Save"></button>
        </form>
    </div>
<?php get_footer(); ?>