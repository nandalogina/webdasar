<?php
require_once 'includes/auth.php';

if (is_logged_in()) {
    header("Location: pages/dashboard.php");
    exit();
} else {
    header("Location: pages/login.php");
    exit();
}
?>
