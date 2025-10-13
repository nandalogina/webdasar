<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_login();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $role = $_POST['role'];

        if (create_user($username, $email, $password, $role)) {
            $message = "User added successfully.";
        } else {
            $message = "Failed to add user.";
        }
    } elseif (isset($_POST['update_user'])) {
        $id = $_POST['id'];
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $role = $_POST['role'];

        if (update_user($id, $username, $email, $role)) {
            $message = "User updated successfully.";
        } else {
            $message = "Failed to update user.";
        }
    } elseif (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        if (delete_user($id)) {
            $message = "User deleted successfully.";
        } else {
            $message = "Failed to delete user.";
        }
    }
}

$users = get_users();
$edit_user = null;
if (isset($_GET['edit'])) {
    $edit_user = get_user($_GET['edit']);
}
?>

<?php include '../includes/header.php'; ?>

<h2>Users Management</h2>

<?php if ($message): ?>
    <div class="message success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<?php if ($edit_user): ?>
    <h3>Edit User</h3>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $edit_user['id']; ?>">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($edit_user['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($edit_user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user" <?php echo $edit_user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo $edit_user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        <button type="submit" name="update_user">Update User</button>
        <a href="users.php">Cancel</a>
    </form>
<?php else: ?>
    <h3>Add New User</h3>
    <form method="post" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" name="add_user">Add User</button>
    </form>
<?php endif; ?>

<h3>All Users</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                <td>
                    <a href="?edit=<?php echo $user['id']; ?>">Edit</a> |
                    <a href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>
