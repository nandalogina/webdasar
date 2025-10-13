 
<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
    redirect('login.php');
}

require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Handle user actions
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $fullname = sanitize($_POST['fullname']);
    $role = sanitize($_POST['role']);
    
    $query = "INSERT INTO users (username, email, password, fullname, role, created_at) 
              VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    if($stmt->execute([$username, $email, $password, $fullname, $role])) {
        $message = displayMessage('User added successfully!');
    } else {
        $message = displayMessage('Error adding user!', 'error');
    }
}

if(isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    if($id != $_SESSION['user_id']) {
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        if($stmt->execute([$id])) {
            $message = displayMessage('User deleted successfully!');
        } else {
            $message = displayMessage('Error deleting user!', 'error');
        }
    } else {
        $message = displayMessage('You cannot delete your own account!', 'error');
    }
}

// Fetch all users
$query = "SELECT * FROM users ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-users"></i> Manage Users</h2>
        
        <?php if(isset($message)) echo $message; ?>
        
        <!-- Add User Form -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-user-plus"></i> Add New User</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="fullname" placeholder="Full Name" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="username" placeholder="Username" required>
                        </div>
                        <div class="col-md-2">
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>
                        <div class="col-md-2">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" name="add_user" class="btn btn-success w-100">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-list"></i> Users List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['fullname']; ?></td>
                                <td><?php echo $user['username']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : 'secondary'; ?>">
                                        <?php echo $user['role']; ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <?php if($user['id'] != $_SESSION['user_id']): ?>
                                    <a href="users.php?delete_id=<?php echo $user['id']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php else: ?>
                                    <span class="text-muted">Current User</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>