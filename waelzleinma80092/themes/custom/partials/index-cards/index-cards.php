<?php
$mode = $_GET['mode'] ?? 'view';
$create_mode = $mode == 'create';

if (!is_user_logged_in()) {
    get_template_part('partials/index-cards/unauthorized-index-cards');
} else if ($create_mode) {
    get_template_part('partials/index-cards/add-index-cards');
} else {
    get_template_part('partials/index-cards/view-index-cards');
}