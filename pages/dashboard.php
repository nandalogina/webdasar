<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
    redirect('login.php');
}
?>

<?php include '../includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-tachometer-alt"></i> Dashboard</h4>
            </div>
            <div class="card-body text-center">
                <h5>Welcome, <?php echo $_SESSION['fullname']; ?>!</h5>
                <p>You are logged in as <strong><?php echo $_SESSION['role']; ?></strong>.</p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
