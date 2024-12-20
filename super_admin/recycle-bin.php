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

            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white">
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
                                $stmt = $pdo->query("SELECT * FROM booking_archive");
                                $archivedBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if ($archivedBookings) {
                                    foreach ($archivedBookings as $booking) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($booking['id']) . "</td>";
                                        echo "<td>" . htmlspecialchars($booking['name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($booking['package_type']) . "</td>";
                                        echo "<td>" . htmlspecialchars($booking['datetime']) . "</td>";
                                        echo "<td>" . htmlspecialchars($booking['venue']) . "</td>";
                                        echo "<td>" . htmlspecialchars($booking['price']) . "</td>";
                                        echo "<td>" . htmlspecialchars($booking['payment_mode']) . "</td>";
                                        echo "<td>" . htmlspecialchars($booking['status']) . "</td>";
                                        echo "<td>";
                                        echo '<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="restoreBooking(' . $booking['id'] . ')">Restore</a> ';
                                        echo '<a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="deleteBooking(' . $booking['id'] . ')">Delete Permanently</a>';
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9'>No archived bookings found.</td></tr>";
                                }
                                ?>
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
                                        echo '<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="restorePhotographer(' . $photographer['id'] . ')">Restore</a> ';
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
                    <div class="card-header bg-secondary text-white">
                        <h5><i class="fas fa-box"></i> Archived Packages</h5>
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
                                $stmt = $pdo->query("SELECT * FROM package_archive");
                                $archivedPackages = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($archivedPackages) {
                                    foreach ($archivedPackages as $package) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($package['id']) . "</td>";
                                        echo "<td>" . htmlspecialchars($package['name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($package['price']) . "</td>";
                                        echo "<td>" . htmlspecialchars($package['description']) . "</td>";
                                        echo "<td><span class='badge bg-danger'>" . htmlspecialchars($package['deleted_at']) . "</span></td>";
                                        echo "<td>";
                                        echo '<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="restorePackage(' . $package['id'] . ')">Restore</a> ';
                                        echo '<a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="deletePackage(' . $package['id'] . ')">Delete Permanently</a>';
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>No archived packages found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5><i class="fas fa-user"></i> Archived Clients</h5>
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
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->query("SELECT * FROM user_archive");
                                $archivedUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($archivedUsers) {
                                    foreach ($archivedUsers as $user) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                                        echo "<td>" . htmlspecialchars($user['firstname']) . "</td>";
                                        echo "<td>" . htmlspecialchars($user['lastname']) . "</td>";
                                        echo "<td>" . htmlspecialchars($user['address']) . "</td>";
                                        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                                        echo "<td>" . htmlspecialchars($user['contact']) . "</td>";
                                        echo "<td><span class='badge bg-danger'>" . htmlspecialchars($client['deleted_at']) . "</span></td>";
                                        echo "<td>";
                                        echo '<a href="javascript:void(0);" class="btn btn-success btn-sm" onclick="restoreClient(' . $client['id'] . ')">Restore</a> ';
                                        echo '<a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="deleteClient(' . $client['id'] . ')">Delete Permanently</a>';
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No archived users found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
    </div>
    <script>
        function restoreBooking(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This action will restore the booking.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, restore it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'restore_booking.php',
                        type: 'GET',
                        data: {
                            restore: id
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: "Restored!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: response.message,
                                    icon: "error"
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX error:", error);
                            Swal.fire("Error", "Failed to restore booking.", "error");
                        }
                    });
                }
            });
        }

        function deleteBooking(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This action will permanently delete the booking.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete_booking.php',
                        type: 'GET',
                        data: {
                            delete: id
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: response.message,
                                    icon: "error"
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX error:", error);
                            Swal.fire("Error", "Failed to delete booking.", "error");
                        }
                    });
                }
            });
        }

        function restorePhotographer(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This action will restore the photographer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, restore it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'restore_photographer.php',
                        type: 'GET',
                        data: {
                            restore: id
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: "Restored!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: response.message,
                                    icon: "error"
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: "Error",
                                text: "Failed to restore photographer.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        }

        function deletePhotographer(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This action will permanently delete the photographer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete_photographer.php',
                        type: 'GET',
                        data: {
                            delete: id
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire("Deleted!", response.message, "success").then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire("Error", response.message, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Error", "Failed to delete photographer.", "error");
                        }
                    });
                }
            });
        }

        function restorePackage(packageId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to restore this package?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, restore it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'restore_package.php',
                        type: 'GET',
                        data: {
                            restore: packageId
                        },
                        success: function(response) {
                            if (response === 'success') {
                                Swal.fire('Restored!', 'The package has been restored.', 'success');
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                Swal.fire('Failed!', 'There was an issue restoring the package.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'An error occurred. Please try again later.', 'error');
                        }
                    });
                }
            });
        }

        function deletePackage(packageId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the package!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete_package.php',
                        type: 'GET',
                        data: {
                            delete: packageId
                        },
                        success: function(response) {
                            if (response === 'success') {
                                Swal.fire('Deleted!', 'The package has been permanently deleted.', 'success');
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                Swal.fire('Failed!', 'There was an issue deleting the package.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'An error occurred. Please try again later.', 'error');
                        }
                    });
                }
            });
        }

        function restoreClient(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to restore this client?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, restore it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('restore_user.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                id: userId
                            })
                        })
                        .then(response => response.text())
                        .then(data => {
                            if (data.trim() === 'Client restored successfully!') {
                                Swal.fire('Restored!', 'The client has been restored.', 'success')
                                    .then(() => {
                                        location.reload();
                                    });
                            } else {
                                Swal.fire('Error!', data, 'error');
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.fire('Error!', 'There was a problem restoring the client.', 'error');
                        });
                }
            });
        }

        function deleteClient(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('delete_user.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: new URLSearchParams({
                                id: userId
                            })
                        })
                        .then(response => response.text())
                        .then(data => {
                            if (data.trim() === 'Client deleted successfully!') {
                                Swal.fire('Deleted!', 'The client has been permanently deleted.', 'success')
                                    .then(() => {
                                        location.reload();
                                    });
                            } else {
                                Swal.fire('Error!', data, 'error');
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.fire('Error!', 'There was a problem deleting the client.', 'error');
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