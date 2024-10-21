<?php
// Require the database connection
require '../connection.php'; 

// Start the session
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

// Role-based redirection
switch ($_SESSION["role"]) {
    case "admin":
        // Admin specific actions can be added here if needed.
        break;
    
    case "client":
        // Redirect client to their previous page or packages
        redirectUser('../client/packages');
        break;

    case "photographer":
        // Redirect photographer to their previous page or admin dashboard
        redirectUser('../admin/admin_dashboard.php');
        break;
}

// Function to redirect the user based on HTTP referer
function redirectUser($defaultLocation) {
    if (!empty($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header('Location: ' . $defaultLocation);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive</title>
    <link rel="stylesheet" href="recycle-bin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <img src="image/logo.png" alt="Logo" class="logo">
        <h1>Mhark Photography Archive</h1>
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
            <h1>Archive</h1>
            
            <!-- Archived Bookings -->
            <h2>Archived Bookings</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Package</th>
                        <th>Date</th>
                        <th>Deleted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM bookings_archive");
                    $archivedBookings = $stmt->fetchAll();
                    foreach ($archivedBookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['name']) ?></td>
                        <td><?= htmlspecialchars($booking['package_type']) ?></td>
                        <td><?= htmlspecialchars($booking['datetime']) ?></td>
                        <td><?= htmlspecialchars($booking['deleted_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Archived Photographers -->
            <h2>Archived Photographers</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Deleted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM photographers_archive");
                    $archivedPhotographers = $stmt->fetchAll();
                    foreach ($archivedPhotographers as $photographer): ?>
                    <tr>
                        <td><?= htmlspecialchars($photographer['name']) ?></td>
                        <td><?= htmlspecialchars($photographer['deleted_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Archived Packages -->
            <h2>Archived Packages</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Package Name</th>
                        <th>Deleted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM packages_archive");
                    $archivedPackages = $stmt->fetchAll();
                    foreach ($archivedPackages as $package): ?>
                    <tr>
                        <td><?= htmlspecialchars($package['package_name']) ?></td>
                        <td><?= htmlspecialchars($package['deleted_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Archived Clients -->
            <h2>Archived Clients</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Deleted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM clients_archive");
                    $archivedClients = $stmt->fetchAll();
                    foreach ($archivedClients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['name']) ?></td>
                        <td><?= htmlspecialchars($client['deleted_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
