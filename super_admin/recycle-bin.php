<?php
require '../connection.php'; 

session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

switch ($_SESSION["role"]) {
    case "admin":
        break;
    
    case "client":
        redirectUser('../client/packages');
        break;

    case "photographer":
        redirectUser('../admin/admin_dashboard.php');
        break;
}

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

            <!-- Archive Cards -->
            <div class="row">
                
                <!-- Archived Bookings Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Archived Bookings</h5>
                        </div>
                        <div class="card-body">
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
                        </div>
                    </div>
                </div>

                <!-- Archived Photographers Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Archived Photographers</h5>
                        </div>
                        <div class="card-body">
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
                        </div>
                    </div>
                </div>

                <!-- Archived Packages Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Archived Packages</h5>
                        </div>
                        <div class="card-body">
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
                        </div>
                    </div>
                </div>

                <!-- Archived Clients Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Archived Clients</h5>
                        </div>
                        <div class="card-body">
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
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
