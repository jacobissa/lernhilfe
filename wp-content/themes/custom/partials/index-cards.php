<?php
$mode = $_GET['mode'] ?? 'view';
$create_mode = $mode == 'create';

if ($create_mode) {
    include TEMPLATEPATH . '/partials/add-index-cards.php';
} else {
    include TEMPLATEPATH . '/partials/view-index-cards.php';
}