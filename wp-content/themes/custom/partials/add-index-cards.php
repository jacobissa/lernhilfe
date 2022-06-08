<div class="index-card-container">
    <div class="index-card-stack">
        <div class="index-card-lower"></div>
        <form class="index-card-upper" id="index-card-form" onsubmit="addIndexCard(); return false;" action="">
            <label for="index-card-question-input" hidden>Frage</label>
            <input id="index-card-question-input" type="text" name="question" placeholder="Frage"/>
            <hr/>
            <label for="index-card-answer-input" hidden>Antwort</label>
            <textarea id="index-card-answer-input"
                      type="text"
                      name="answer"
                      placeholder="Antwort"></textarea>
        </form>
    </div>
    <div class="index-card-actions">
        <button class="text-action-button" onclick="window.location.href = '?view=index-cards'">
            Zurück
        </button>
        <button class="icon-action-button" onclick="addIndexCard()">
            Speichern
            <img src="<?php echo get_template_directory_uri(); ?>/svg/save.svg" alt="Save">
        </button>
    </div>
</div>