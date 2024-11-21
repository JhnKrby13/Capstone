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

function redirectUser($defaultLocation)
{
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
    <title>Achive</title>
    <link rel="stylesheet" href="recycle-bin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="header">
        <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
        <img src="image/logo.png" alt="Logo" class="logo">
        <h1>Mhark Photography Achive</h1>
    </div>
    <div class="dashboard">
        <div class="sidebar" id="sidebar">
            <ul>
                <li><a href="super_admin_dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="manage-bookings.php"><i class="fas fa-calendar-check"></i> <span>Bookings</span></a></li>
                <li><a href="manage-packages.php"><i class="fas fa-box"></i> <span>Packages</span></a></li>
                <li><a href="manage-photographer.php"><i class="fas fa-camera"></i> <span>Photographers</span></a></li>
                <li><a href="manage-users.php"><i class="fas fa-users"></i> <span>Clients</span></a></li>
                <li><a href="manage-gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
                <li><a href="recent-history.php"><i class="fas fa-calendar-alt"></i> <span>Recent History</span></a></li>
                <li><a href="recycle-bin.php"><i class="fas fa-trash-alt"></i> <span>Archive</span></a></li>
                <li><a href="system-settings.php"><i class="fas fa-cogs"></i> <span>Settings</span></a></li>
            </ul>
        </div>

        <div class="content">
            <h1>Archive</h1>

            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5><i class="fas fa-calendar-alt"></i> Archived Bookings</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Package</th>
                                        <th>Date</th>
                                        <th>Deleted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT * FROM bookings_archive");
                                    foreach ($stmt->fetchAll() as $booking): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($booking['id']) ?></td>
                                            <td><?= htmlspecialchars($booking['name']) ?></td>
                                            <td><?= htmlspecialchars($booking['package_type']) ?></td>
                                            <td><?= htmlspecialchars($booking['datetime']) ?></td>
                                            <td><span class="badge bg-danger"><?= htmlspecialchars($booking['deleted_at']) ?></span></td>
                                            <td>
                                                <button class="btn btn-success btn-sm">Restore</button>
                                                <button class="btn btn-danger btn-sm">Delete Permanently</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Archived Photographers Card -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-white">
                            <h5><i class="fas fa-camera"></i> Archived Photographers</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Deleted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT * FROM photographers_archive");
                                    foreach ($stmt->fetchAll() as $photographer): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($photographer['id']) ?></td>
                                            <td><?= htmlspecialchars($photographer['name']) ?></td>
                                            <td><span class="badge bg-danger"><?= htmlspecialchars($photographer['deleted_at']) ?></span></td>
                                            <td>
                                                <button class="btn btn-success btn-sm">Restore</button>
                                                <button class="btn btn-danger btn-sm">Delete Permanently</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Archived Packages Card -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5><i class="fas fa-box-open"></i> Archived Packages</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Package Name</th>
                                        <th>Deleted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT * FROM packages_archive");
                                    foreach ($stmt->fetchAll() as $package): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($package['id']) ?></td>
                                            <td><?= htmlspecialchars($package['package_name']) ?></td>
                                            <td><span class="badge bg-danger"><?= htmlspecialchars($package['deleted_at']) ?></span></td>
                                            <td>
                                                <button class="btn btn-success btn-sm">Restore</button>
                                                <button class="btn btn-danger btn-sm">Delete Permanently</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Archived Clients Card -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            <h5><i class="fas fa-user-slash"></i> Archived Clients</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Client Name</th>
                                        <th>Deleted At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT * FROM clients_archive");
                                    foreach ($stmt->fetchAll() as $client): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($client['id']) ?></td>
                                            <td><?= htmlspecialchars($client['name']) ?></td>
                                            <td><span class="badge bg-danger"><?= htmlspecialchars($client['deleted_at']) ?></span></td>
                                            <td>
                                                <button class="btn btn-success btn-sm">Restore</button>
                                                <button class="btn btn-danger btn-sm">Delete Permanently</button>
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
    </div>
    <script>        

    document.querySelector('.hamburger').addEventListener('click', () => {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            sidebar.classList.toggle('collapsed');
        });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>