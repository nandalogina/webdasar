 
<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
    redirect('login.php');
}

$db = new Database();
$conn = $db->getConnection();
$message = '';

// Update profile
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $fullname = sanitize($_POST['fullname']);
    $email = sanitize($_POST['email']);
    
    $query = "UPDATE users SET fullname = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    if($stmt->execute([$fullname, $email, $_SESSION['user_id']])) {
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email'] = $email;
        $message = displayMessage('Profile updated successfully!');
    }
}

// Change password
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($new_password !== $confirm_password) {
        $message = displayMessage('New passwords do not match!', 'error');
    } else {
        // Verify current password
        $query = "SELECT password FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(password_verify($current_password, $user['password'])) {
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            if($stmt->execute([$new_hashed_password, $_SESSION['user_id']])) {
                $message = displayMessage('Password changed successfully!');
            }
        } else {
            $message = displayMessage('Current password is incorrect!', 'error');
        }
    }
}

// Get current user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-8">
        <h2><i class="fas fa-user-circle"></i> My Profile</h2>
        <?php echo $message; ?>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-edit"></i> Update Profile</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="fullname" 
                                   value="<?php echo $user['fullname']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?php echo $user['username']; ?>" readonly>
                            <small class="text-muted">Username cannot be changed</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" 
                               value="<?php echo $user['email']; ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-key"></i> Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-warning">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Account Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-user fa-2x text-white"></i>
                    </div>
                </div>
                <p><strong>User ID:</strong> <?php echo $user['id']; ?></p>
                <p><strong>Role:</strong> <span class="badge bg-secondary"><?php echo $user['role']; ?></span></p>
                <p><strong>Member Since:</strong> <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                <p><strong>Last Login:</strong> <?php echo date('F j, Y g:i A'); ?></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>