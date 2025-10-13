<?php
function redirect($url) {
    header("Location: " . $url);
    exit;
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function displayMessage($message, $type = 'success') {
    $class = ($type == 'error') ? 'alert-danger' : 'alert-success';
    return '<div class="alert ' . $class . '">' . $message . '</div>';
}
?>