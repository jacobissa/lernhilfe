<div class="index-card-container">
    <div class="index-card-stack">
        <div class="index-card-lower"></div>
        <form class="index-card-upper"
              id="index-card-form"
              onsubmit="addIndexCard(<?php echo $post->ID ?>); return false;"
              action="">
            <label for="index-card-question-input" hidden><?php _e('Question', THEME_DOMAIN); ?></label>
            <input id="index-card-question-input" type="text" name="question" placeholder="<?php _e('Question', THEME_DOMAIN); ?>"/>
            <hr/>
            <label for="index-card-answer-input" hidden><?php _e('Answer', THEME_DOMAIN); ?></label>
            <textarea id="index-card-answer-input"
                      type="text"
                      name="answer"
                      placeholder="<?php _e('Answer', THEME_DOMAIN); ?>"></textarea>
        </form>
    </div>
    <div class="index-card-actions">
        <button class="text-action-button" onclick="window.location.href = '?view=index-cards'">
            Zurück
        </button>
        <button class="icon-action-button" id="add-index-card-button" onclick="document.querySelector('#index-card-form').onsubmit()">
            Speichern
            <img src="<?php echo get_template_directory_uri(); ?>/svg/save.svg" alt="<?php _e("Save", THEME_DOMAIN); ?>">
        </button>
    </div>
</div>