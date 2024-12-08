<?php
require '../connection.php';

session_start();

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../../auth/login.php');
    exit;
}

if ($_SESSION["role"] === "admin") {
} else {
    if ($_SESSION["role"] === "client") {
        if (!empty($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: ../client/packages');
            exit();
        }
    } else if ($_SESSION["role"] === "photographer") {
        if (!empty($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: ../admin/admin_dashboard.php');
            exit();
        }
    }
}

$stmt = $pdo->query("SELECT * FROM booking");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

try {
    $stmt = $pdo->query("SELECT b.id, b.name, b.package_type, b.datetime, b.venue, b.price, b.payment_mode, p.name AS photographer_name, b.status, b.photographer_id
                         FROM booking b 
                         LEFT JOIN photographers p ON b.photographer_id = p.id");
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="manage-bookings.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="header">
        <div class="sub-header">
            <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
            <img src="image/logo.png" alt="Logo" class="logo">
            <p id="mark">Mhark Photography Booking</p>
        </div>
        <div class="profile-dropdown">
            <h1 style="color:white; font-size: 24px; margin-right: 15px;">
                <?php echo $_SESSION['firstname']; ?>
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
                <li><a href="recent-history.php"><i class="fas fa-calendar-alt"></i> <span>Recent History</span></a></li>
                <li><a href="recycle-bin.php"><i class="fas fa-trash-alt"></i> <span>Archive</span></a></li>
                <li><a href="system-settings.php"><i class="fas fa-cogs"></i> <span>Settings</span></a></li>
            </ul>
        </div>
        <div class="content">
            <h2>All Bookings</h2>
            <div class="search-filter">
                <input type="text" id="search" placeholder="Search bookings...">
                <button id="filter-btn">Search</button>
            </div>
            <table id="bookings-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Package</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Cost</th>
                        <th>Photographer</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['package_type']); ?></td>
                            <td><?php echo htmlspecialchars($booking['datetime']); ?></td>
                            <td><?php echo htmlspecialchars($booking['venue']); ?></td>
                            <td><?php echo htmlspecialchars($booking['price']); ?></td>
                            <td><?php echo htmlspecialchars($booking['photographer_name']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="accept" onclick="updateStatus(<?php echo $booking['id']; ?>, 'Accepted')">Accept</button>
                                    <button class="decline" onclick="updateStatus(<?php echo $booking['id']; ?>, 'Declined')">Decline</button>
                                    <button class="edit" onclick="editBooking(<?php echo $booking['id']; ?>)">Edit</button>
                                    <button class="archive" onclick="archiveBooking(<?php echo $booking['id']; ?>)">Archive</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.querySelector('.hamburger').addEventListener('click', () => {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            sidebar.classList.toggle('collapsed');
        });

        function updateStatus(id, status) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, proceed!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'update_booking_status.php',
                        type: 'POST',
                        data: {
                            id: id,
                            status: status
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Success!",
                                text: `Booking status updated to ${status}.`,
                                icon: "success"
                            });
                            var row = $('tr').filter(function() {
                                return $(this).find('td').first().text() == id;
                            });

                            if (status == 'Accepted') {
                                row.find('.accept').text('Accepted').prop('disabled', true).addClass('btn-success').removeClass('accept');
                                row.find('.decline').prop('disabled', true).addClass('btn-secondary').removeClass('decline');
                            } else if (status == 'Declined') {
                                row.find('.decline').text('Declined').prop('disabled', true).addClass('btn-danger').removeClass('decline');
                                row.find('.accept').prop('disabled', true).addClass('btn-secondary').removeClass('accept');
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: "Failed",
                                text: "Failed to update status.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        }

        function archiveBooking(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This action will archive the booking.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, archive it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'archive_booking.php',
                        type: 'POST',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Archived!",
                                text: "The booking has been archived.",
                                icon: "success"
                            });
                            location.reload(); 
                        },
                        error: function() {
                            Swal.fire({
                                title: "Failed",
                                text: "Failed to archive booking.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        }

        function editBooking(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to edit this booking?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, edit it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `edit_booking.php?id=${id}`;
                }
            });
        }

        $(document).ready(function() {
            $('#bookings-table').DataTable({
                "searching": true,
                "paging": true,
                "ordering": true
            });

            $('#search').on('keyup', function() {
                $('#bookings-table').DataTable().search(this.value).draw();
            });
        });

        $(document).ready(function() {
            $('#search').on('keyup', function() {
                $('#bookings-table').DataTable().search(this.value).draw();
            });
        });
        
    </script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>