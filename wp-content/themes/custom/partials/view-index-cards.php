<?php
$index_card_query = new WP_Query([
    'post_type' => 'index_card',
    'meta_query' => [
        [
            'key' => 'username',
            'value' => 'testuser'
        ],
    ],
    'orderby' => 'rand',
    'posts_per_page' => '1'
]);
$have_posts = $index_card_query->have_posts();
?>
    <div class="index-card-container">
        <?php
        if ($have_posts) {
            while ($index_card_query->have_posts()) {
                $index_card_query->the_post();

                $question = get_post_meta($post->ID, 'question')[0];
                $answer = get_post_meta($post->ID, 'answer')[0];

                ?>
                <div class="index-card-stack">
                    <div class="index-card-lower"></div>
                    <div class="flip-card">
                        <div class="flip-card-inner">
                            <button class="index-card-upper displayed-index-card flip-card-front"
                                    onclick="document.querySelector('.flip-card-inner').classList.add('flip-card-flipped')">
                                <span class="displayed-index-card-question"><?php echo $question; ?></span>
                            </button>
                            <button class="index-card-upper displayed-index-card flip-card-back"
                                    onclick="window.location.reload()">
                                <span class="displayed-index-card-question"><?php echo $question; ?></span>
                                <span class="displayed-index-card-anwser"><?php echo $answer; ?></span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <span>Keine Karteikarten vorhanden</span>
            <?php
        }
        ?>

        <div class="index-card-actions">
            <button class="icon-action-button" onclick="window.location.href = '?view=index-cards&mode=create'">
                Erstellen
                <img src="<?php echo get_template_directory_uri(); ?>/svg/add.svg" alt="Create">
            </button>
            <button class="icon-action-button"
                <?php if (!$have_posts) echo 'disabled' ?>
                    onclick="deleteIndexCard(<?php the_ID(); ?>)">
                Löschen
                <img src="<?php echo get_template_directory_uri(); ?>/svg/delete.svg" alt="Delete">
            </button>
            <button class="icon-action-button"
                <?php if (!$have_posts) echo 'disabled' ?>
                    onclick="window.location.reload()">
                Nächste
                <img src="<?php echo get_template_directory_uri(); ?>/svg/next.svg" alt="Next">
            </button>
        </div>
    </div>
<?php
wp_reset_postdata();
