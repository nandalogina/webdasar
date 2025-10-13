 
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
    return '<div class="alert ' . $class . ' alert-dismissible fade show">' . 
           $message . 
           '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

function requireLogin() {
    if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        redirect('login.php');
    }
}
?>