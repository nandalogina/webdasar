<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database connection for stats
require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Get user statistics for admin
if($_SESSION['role'] === 'admin') {
    try {
        $stats_query = "
            SELECT 
                COUNT(*) as total_users,
                SUM(role = 'admin') as admin_count,
                SUM(role = 'user') as user_count,
                SUM(role = 'moderator') as moderator_count
            FROM users
        ";
        $stats_stmt = $conn->prepare($stats_query);
        $stats_stmt->execute();
        $user_stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $user_stats = [
            'total_users' => 1,
            'admin_count' => 1,
            'user_count' => 0,
            'moderator_count' => 0
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Auth System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?> 

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <!-- Welcome Banner -->
                <div class="welcome-banner shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2>Welcome back, <?php echo $_SESSION['fullname']; ?>!</h2>
                            <p class="mb-0">You have successfully logged into the authentication system.</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="user-avatar">
                                <?php echo strtoupper(substr($_SESSION['fullname'], 0, 1)); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-primary">Last Login</h5>
                            <p class="mb-0"><?php echo date('M j, Y g:i A'); ?></p>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card success">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-success">Account Status</h5>
                            <p class="mb-0">Active</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card info">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-info">User Role</h5>
                            <p class="mb-0"><?php echo ucfirst($_SESSION['role']); ?></p>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-user-tag"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php if($_SESSION['role'] === 'admin'): ?>
            <div class="col-md-3">
                <div class="stats-card warning">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-warning">Total Users</h5>
                            <p class="mb-0"><?php echo $user_stats['total_users']; ?> Users</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <!-- User Information -->
            <div class="col-lg-8 mb-4">
                <div class="card main-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>User Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0">Full Name</h6>
                                        <p class="mb-0 text-muted"><?php echo $_SESSION['fullname']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon">
                                        <i class="fas fa-at"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0">Username</h6>
                                        <p class="mb-0 text-muted"><?php echo $_SESSION['username']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0">Email Address</h6>
                                        <p class="mb-0 text-muted"><?php echo $_SESSION['email']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="info-icon">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-0">Role</h6>
                                        <p class="mb-0 text-muted"><?php echo ucfirst($_SESSION['role']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Admin Quick Stats -->
                <?php if($_SESSION['role'] === 'admin'): ?>
                <div class="card main-card mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>User Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="stat-circle primary">
                                    <h3><?php echo $user_stats['total_users']; ?></h3>
                                    <small>Total Users</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="stat-circle danger">
                                    <h3><?php echo $user_stats['admin_count']; ?></h3>
                                    <small>Admins</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="stat-circle warning">
                                    <h3><?php echo $user_stats['moderator_count']; ?></h3>
                                    <small>Moderators</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="stat-circle secondary">
                                    <h3><?php echo $user_stats['user_count']; ?></h3>
                                    <small>Users</small>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="users.php" class="btn btn-primary">
                                <i class="fas fa-users-cog me-2"></i>Manage All Users
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Recent Activity -->
                <div class="card main-card mt-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-feed">
                            <div class="activity-item">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">Successful Login</h6>
                                    <small><?php echo date('M j, g:i A'); ?></small>
                                </div>
                                <p class="mb-0 text-muted">You have successfully logged into the system.</p>
                            </div>
                            <div class="activity-item success">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">Profile Updated</h6>
                                    <small><?php echo date('M j, g:i A', strtotime('-1 day')); ?></small>
                                </div>
                                <p class="mb-0 text-muted">Your profile information was updated.</p>
                            </div>
                            <div class="activity-item info">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">Password Changed</h6>
                                    <small><?php echo date('M j, g:i A', strtotime('-3 days')); ?></small>
                                </div>
                                <p class="mb-0 text-muted">Your password was successfully changed.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card main-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <a href="profile.php" class="btn btn-primary action-btn">
                                <i class="fas fa-user-edit me-2"></i>Edit Profile
                            </a>
                            <a href="settings.php" class="btn btn-secondary action-btn">
                                <i class="fas fa-cog me-2"></i>Account Settings
                            </a>
                            <?php if($_SESSION['role'] === 'admin'): ?>
                            <a href="users.php" class="btn btn-info action-btn">
                                <i class="fas fa-users-cog me-2"></i>Manage Users
                            </a>
                            <a href="analytics.php" class="btn btn-warning action-btn">
                                <i class="fas fa-chart-bar me-2"></i>View Analytics
                            </a>
                            <?php endif; ?>
                            <a href="logout.php" class="btn btn-danger action-btn">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- System Status -->
                <div class="card main-card mt-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Security Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Password Strength</span>
                                <span class="badge bg-success">Strong</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 85%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Two-Factor Auth</span>
                                <span class="badge bg-warning">Not Enabled</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 30%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Security Alerts</span>
                                <span class="badge bg-danger">3 Unread</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 60%"></div>
                            </div>
                        </div>
                        
                    </div>
                </div>

              
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/dashboard.js"></script>
</body>
</html>