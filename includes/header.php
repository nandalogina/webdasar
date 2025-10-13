<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Dasar - PHP Authentication & CRUD</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <h1>Web Dasar</h1>
                <ul>
                    <?php if (is_logged_in()): ?>
                        <li><a href="../pages/dashboard.php">Dashboard</a></li>
                        <li><a href="../pages/users.php">Users</a></li>
                        <li><a href="../pages/profile.php">Profile</a></li>
                        <li><a href="../pages/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="../pages/login.php">Login</a></li>
                        <li><a href="../pages/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main class="container">
