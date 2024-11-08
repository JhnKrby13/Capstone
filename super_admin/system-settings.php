<?php
require '../connection.php'; 
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION["role"] !== "admin") {
    header('Location: ../../auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="system-settings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="header">  
        <img src="image/logo.png" alt="Logo" class="logo">
        <h1>Mhark Photography Settings</h1>
    </div>
    <div class="dashboard">
        <div class="sidebar">
            <ul>
                <li><a href="super_admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="manage-bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
                <li><a href="manage-packages.php"><i class="fas fa-box"></i> Packages</a></li>
                <li><a href="manage-photographer.php"><i class="fas fa-camera"></i> Photographers</a></li>
                <li><a href="manage-users.php"><i class="fas fa-users"></i> Clients</a></li>
                <li><a href="manage-gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                <li><a href="recent-history.php"><i class="fas fa-history"></i> Recent History</a></li>
                <li><a href="recycle-bin.php"><i class="fas fa-trash-alt"></i> Archive</a></li>
                <li><a href="system-settings.php"><i class="fas fa-cogs"></i> Settings</a></li>
            </ul>
        </div>

        <div class="content">
            <h1>Settings</h1>
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="card-title">Account Settings</h2>
                </div>
                <div class="card-body">
                    <form action="update_account.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm-password" class="form-label">Confirm Password</label>
                            <input type="password" id="confirm-password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Account Settings</button>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="card-title">Contact Information</h2>
                </div>
                <div class="card-body">
                    <form action="update_contact_info.php" method="POST">
                        <div class="mb-3">
                            <label for="contact-email" class="form-label">Contact Email</label>
                            <input type="email" id="contact-email" name="contact_email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" id="address" name="address" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Contact Information</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
