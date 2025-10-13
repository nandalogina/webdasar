<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_login();

$user = get_logged_in_user();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);

        if (update_profile($user['id'], $username, $email)) {
            $message = "Profile updated successfully.";
            $user = get_logged_in_user(); // Refresh user data
        } else {
            $message = "Failed to update profile.";
        }
    } elseif (isset($_POST['change_password'])) {
        $current_password = trim($_POST['current_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        if (!password_verify($current_password, $user['password'])) {
            $message = "Current password is incorrect.";
        } elseif ($new_password !== $confirm_password) {
            $message = "New passwords do not match.";
        } elseif (strlen($new_password) < 6) {
            $message = "New password must be at least 6 characters long.";
        } else {
            if (change_password($user['id'], $new_password)) {
                $message = "Password changed successfully.";
            } else {
                $message = "Failed to change password.";
            }
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Edit Profile</h2>

<?php if ($message): ?>
    <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<h3>Update Profile Information</h3>
<form method="post" action="">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>

    <button type="submit" name="update_profile">Update Profile</button>
</form>

<h3>Change Password</h3>
<form method="post" action="">
    <div class="form-group">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required>
    </div>

    <div class="form-group">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
    </div>

    <div class="form-group">
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>

    <button type="submit" name="change_password">Change Password</button>
</form>

<?php include '../includes/footer.php'; ?>
