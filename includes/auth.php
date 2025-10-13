<?php
session_start();

require_once '../config/database.php';

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to get current user
function get_logged_in_user() {
    if (!is_logged_in()) {
        return null;
    }
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Function to login user
function login($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        return true;
    }
    return false;
}

// Function to register user
function register($username, $email, $password, $role = 'user') {
    global $pdo;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password, $role]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Function to logout user
function logout() {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// Function to require login
function require_login() {
    if (!is_logged_in()) {
        header("Location: ../pages/login.php");
        exit();
    }
}
?>
