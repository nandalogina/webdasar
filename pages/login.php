<?php
require_once '../includes/auth.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (login($username, $password)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Invalid username or password.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Login</h2>

<?php if ($message): ?>
    <div class="message error"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<form method="post" action="">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>

    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>

<?php include '../includes/footer.php'; ?>
