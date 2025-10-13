<?php
require_once '../config/database.php';

// Function to create a new user
function create_user($username, $email, $password, $role = 'user') {
    global $pdo;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password, $role]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

// Function to get all users
function get_users() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

// Function to get a single user by ID
function get_user($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, username, email, role, created_at FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Function to update a user
function update_user($id, $username, $email, $role) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$username, $email, $role, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Function to delete a user
function delete_user($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Function to update user profile (without password)
function update_profile($id, $username, $email) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Function to change password
function change_password($id, $new_password) {
    global $pdo;
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed_password, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
?>
