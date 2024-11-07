<?php
require '../connection.php';
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

if ($_SESSION["role"] !== "photographer") {
    if (!empty($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        header('Location: ../client/packages');
        exit();
    }
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../../auth/login.php');
    exit;
}

$totalBookings = 10;
$upcomingShoots = 3;
$unreadMessages = 2;
$photographerName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Photographer';
$recentActivities = [
    "Booking #1234 confirmed for Jan 20",
    "Photo shoot for Client A on Feb 2",
    "New message from Client B"
];
$recentBookings = [
    ["client" => "Client A", "date" => "2024-11-10", "status" => "Confirmed"],
    ["client" => "Client B", "date" => "2024-11-12", "status" => "Pending"]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
</head>
<body>
    <div class="header">
        <img src="image/logo.png" alt="Logo" class="logo">
        <h1>Photographer Dashboard</h1>
        <form method="POST" style="display: inline;">
        </form>
    </div>

    <div class="dashboard d-flex">
        <div class="sidebar photographer-sidebar">
            <ul>
                <li><a href="photographer_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage-bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
                <li><a href="manage-gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                <li><a href="recent-history.php"><i class="fas fa-history"></i> Recent History</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a></li>
            </ul>
        </div>

        <div class="content photographer-content">
            <h1>Welcome, <?php echo $photographerName; ?>!</h1>

            <div class="widgets row">
                <div class="widget col-md-3">
                    <h2>Total Bookings</h2>
                    <p><?php echo $totalBookings; ?></p>
                </div>
                <div class="widget col-md-3">
                    <h2>Upcoming Shoots</h2>
                    <p><?php echo $upcomingShoots; ?></p>
                </div>
                <div class="widget col-md-3">
                    <h2>Unread Messages</h2>
                    <p><?php echo $unreadMessages; ?></p>
                </div>
            </div>

            <div class="charts row mt-4">
                <div class="chart-container col-md-6">
                    <h2>Monthly Bookings</h2>
                    <canvas id="bookingsChart"></canvas>
                </div>
                <div class="chart-container col-md-6">
                    <h2>Earnings Overview</h2>
                    <canvas id="earningsChart"></canvas>
                </div>
            </div>

            <div class="activity-feed mt-4">
                <h2>Recent Activity</h2>
                <ul>
                    <?php foreach ($recentActivities as $activity): ?>
                        <li><?php echo htmlspecialchars($activity); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="bookings-list mt-4">
                <h2>Recent Bookings</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentBookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['client']); ?></td>
                                <td><?php echo htmlspecialchars($booking['date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="gallery-overview mt-4">
                <h2>Gallery Overview</h2>
                <div class="gallery">
                    <a href="image/sample1.jpg" data-lightbox="gallery"><img src="image/sample1.jpg" alt="Sample Photo"></a>
                    <a href="image/sample2.jpg" data-lightbox="gallery"><img src="image/sample2.jpg" alt="Sample Photo"></a>
                    <a href="image/sample3.jpg" data-lightbox="gallery"><img src="image/sample3.jpg" alt="Sample Photo"></a>
                    <a href="image/sample4.jpg" data-lightbox="gallery"><img src="image/sample4.jpg" alt="Sample Photo"></a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script>
        const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
        new Chart(bookingsCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Bookings',
                    data: [3, 2, 5, 7, 4, 6, 5, 8, 6, 4, 9, 7],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        const earningsCtx = document.getElementById('earningsChart').getContext('2d');
        new Chart(earningsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Earnings',
                    data: [1200, 1700, 1500, 1900, 1600, 2100, 2000, 2400, 2300, 2200, 2600, 2500],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2
                }]
            }
        });
    </script>
</body>
</html>
