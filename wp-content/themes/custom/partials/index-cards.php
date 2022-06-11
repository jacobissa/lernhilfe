<?php
$mode = $_GET['mode'] ?? 'view';
$create_mode = $mode == 'create';

if ($create_mode) {
    get_template_part('partials/add-index-cards');
} else {
    get_template_part('partials/view-index-cards');
}