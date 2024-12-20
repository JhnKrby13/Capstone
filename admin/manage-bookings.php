<?php
require '../connection.php'; 
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

if ($_SESSION["role"] === "admin" || $_SESSION["role"] === "photographer") {
    
} else {
    if (!empty($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        header('Location: ../client/packages');
        exit();
    }
}

try {
    $stmt = $pdo->query("SELECT b.id, b.name, b.package_type, b.datetime, b.venue, b.price, b.payment_mode, p.name AS photographer_name, b.status, b.photographer_id
                         FROM booking b 
                         LEFT JOIN photographers p ON b.photographer_id = p.id");
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$user_email = $_SESSION['email'];
$totalBookingsCountQuery = "SELECT id FROM photographers WHERE email = :user_email";
$stmt = $pdo->prepare($totalBookingsCountQuery);
$stmt->bindValue(':user_email', $user_email, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$photographer_id = $result['id'] ?? null;

$bookingsDetails = [];
if ($photographer_id) {
    $totalBookingsQuery = "
        SELECT 
            booking.*, 
            photographers.name AS photographer_name 
        FROM 
            booking
        JOIN 
            photographers 
        ON 
            booking.photographer_id = photographers.id
        WHERE 
            booking.photographer_id = :photographer_id";
    $stmt = $pdo->prepare($totalBookingsQuery);
    $stmt->bindValue(':photographer_id', $photographer_id, PDO::PARAM_INT);
    $stmt->execute();
    $bookingsDetails = $stmt->fetchAll(PDO::FETCH_ASSOC); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="manage-bookings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="header">
        <div class="sub-header">
            <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
            <img src="image/logo.png" alt="Logo" class="logo">
            <p id="mark">Booking</p>
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
        <div class="sidebar">
            <ul>
                <li><a href="admin_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="manage-bookings.php"><i class="fas fa-calendar-check"></i> <span>Bookings</span></a></li>
                <li><a href="manage-gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
                <!-- <li><a href="recent-history.php"><i class="fas fa-history"></i> <span>Recent History</span></a></li> -->
                <li><a href="reports.php"><i class="fas fa-chart-line"></i> <span>Reports</span></a></li>
            </ul>
        </div>
        <div class="content">
            <h2>All Bookings</h2>
            <div class="search-filter">
                <input type="text" id="search" placeholder="Search bookings...">
                <button id="filter-btn">Search</button>
            </div>
            <table id="bookings-table" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Package</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Cost</th>
                        <th>Photographer</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookingsDetails as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['package_type']); ?></td>
                            <td><?php echo htmlspecialchars($booking['datetime']); ?></td>
                            <td><?php echo htmlspecialchars($booking['venue']); ?></td>
                            <td><?php echo htmlspecialchars($booking['price']); ?></td>
                            <td><?php echo htmlspecialchars($booking['photographer_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#bookings-table').DataTable({
                "paging": true,
                "ordering": true,
                "searching": true,
                "lengthChange": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search bookings..."
                }
            });
        });

        document.querySelector('.hamburger').addEventListener('click', () => {
                const sidebar = document.querySelector('.sidebar');
                const content = document.querySelector('.content');
                sidebar.classList.toggle('collapsed');
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
