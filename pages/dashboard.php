<?php
require_once '../includes/auth.php';
require_login();

$user = get_logged_in_user();
?>

<?php include '../includes/header.php'; ?>

<h2>Welcome to Dashboard, <?php echo htmlspecialchars($user['username']); ?>!</h2>

<div class="dashboard-info">
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
    <p><strong>Member since:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
</div>

<div class="dashboard-actions">
    <h3>Quick Actions</h3>
    <ul>
        <li><a href="users.php">Manage Users</a></li>
        <li><a href="profile.php">Edit Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<?php include '../includes/footer.php'; ?>
