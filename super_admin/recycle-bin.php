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


try {
    $stmt = $pdo->query("SELECT * FROM booking_archive");
    $archivedBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

try {
    $stmt = $pdo->query("SELECT * FROM photographer_archive");
    $archivedPhotographers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

try {
    $stmt = $pdo->query("SELECT * FROM package_archive");
    $archivedPackages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

try {
    $stmt = $pdo->query("SELECT * FROM user_archive");
    $archivedUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="header">
        <div class="sub-header">
            <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
            <img src="image/logo.png" alt="Logo" class="logo">
            <p id="mark">Mhark Photography Photographer</p>
        </div>
        <div class="profile-dropdown">
            <h1 style="color:white; font-size: 24px; margin-right: 15px;">
                <?php
                echo $_SESSION['firstname'];
                ?>
            </h1>
            <div class="dropdown">
                <button class="btn btn-secondary rounded-circle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle "></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <a class="dropdown-item" href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </ul>
            </div>
        </div>
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
                <!-- <li><a href="recent-history.php"><i class="fas fa-calendar-alt"></i> <span>Recent History</span></a></li> -->
                <li><a href="recycle-bin.php"><i class="fas fa-trash-alt"></i> <span>Archive</span></a></li>
                <!-- <li><a href="system-settings.php"><i class="fas fa-cogs"></i> <span>Settings</span></a></li> -->
            </ul>
        </div>

        <div class="content">
            <h1>Archive</h1>

            <div class="col">
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
                                        <th>Package Type</th>
                                        <th>Date/Time</th>
                                        <th>Venue</th>
                                        <th>Price</th>
                                        <th>Payment Mode</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($archivedBookings as $booking): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($booking['id']) ?></td>
                                            <td><?= htmlspecialchars($booking['name']) ?></td>
                                            <td><?= htmlspecialchars($booking['package_type']) ?></td>
                                            <td><?= htmlspecialchars($booking['datetime']) ?></td>
                                            <td><?= htmlspecialchars($booking['venue']) ?></td>
                                            <td><?= htmlspecialchars($booking['price']) ?></td>
                                            <td><?= htmlspecialchars($booking['payment_mode']) ?></td>
                                            <td><?= htmlspecialchars($booking['status']) ?></td>
                                            <td>
                                                <button class="btn btn-success btn-sm">Restore</button>
                                                <button class="btn btn-danger btn-sm">Delete </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

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
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Address</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT * FROM photographer_archive");
                                    $photographers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    if ($photographers) {
                                        foreach ($photographers as $photographer) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($photographer['id']) . "</td>";
                                            echo "<td>" . htmlspecialchars($photographer['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($photographer['email']) . "</td>";
                                            echo "<td>" . htmlspecialchars($photographer['contact']) . "</td>";
                                            echo "<td>" . htmlspecialchars($photographer['address']) . "</td>";
                                            echo "<td>";
                                            echo '<a href="restore_photographer.php?restore=' . $photographer['id'] . '" class="btn btn-success btn-sm">Restore</a> ';
                                            echo '<a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="deletePhotographer(' . $photographer['id'] . ')">Delete Permanently</a>';
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No photographers found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


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
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($archivedPackages as $packages): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($package['id']) ?></td>
                                            <td><?= htmlspecialchars($package['Name']) ?></td>
                                            <td><?= htmlspecialchars($package['Price']) ?></td>
                                            <td><?= htmlspecialchars($package['Description']) ?></td>
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
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Address</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($archivedUsers as $user): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($client['id']) ?></td>
                                            <td><?= htmlspecialchars($client['firstname']) ?></td>
                                            <td><?= htmlspecialchars($client['lastname']) ?></td>
                                            <td><?= htmlspecialchars($client['address']) ?></td>
                                            <td><?= htmlspecialchars($client['contact']) ?></td>
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
         function restorePhotographer(photographerId) {
            $.ajax({
                url: 'restore_photographer.php',
                type: 'GET',
                data: { restore: photographerId },
                success: function(response) {
                    const data = JSON.parse(response); // Parse the JSON response
                    if (data.status === 'success') {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Optionally reload the page or update the UI
                            location.reload();
                        });
                    } else {
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message,
                            showConfirmButton: true
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An unexpected error occurred.',
                        showConfirmButton: true
                    });
                }
            });
        }

        // Function to permanently delete a photographer
        function deletePhotographer(photographerId) {
            $.ajax({
                url: 'delete_photographer.php',
                type: 'GET',
                data: { delete: photographerId },
                success: function(response) {
                    const data = JSON.parse(response); // Parse the JSON response
                    if (data.status === 'success') {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Optionally reload the page or update the UI
                            location.reload();
                        });
                    } else {
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message,
                            showConfirmButton: true
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An unexpected error occurred.',
                        showConfirmButton: true
                    });
                }
            });
        }

        document.querySelector('.hamburger').addEventListener('click', () => {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            sidebar.classList.toggle('collapsed');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>