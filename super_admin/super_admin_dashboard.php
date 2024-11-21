<?php
require '../connection.php';

session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
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

function fetchData($pdo)
{
    $totalBookingsQuery = $pdo->query("SELECT COUNT(*) as total_bookings FROM booking");
    $totalBookings = $totalBookingsQuery->fetch(PDO::FETCH_ASSOC)['total_bookings'];

    $totalRevenueQuery = $pdo->query("SELECT SUM(price) as total_revenue FROM booking");
    $totalRevenue = $totalRevenueQuery->fetch(PDO::FETCH_ASSOC)['total_revenue'];

    $revenueDataQuery = $pdo->query("
        SELECT DATE_FORMAT(datetime, '%Y-%m') as month, SUM(price) as revenue 
        FROM booking 
        GROUP BY month 
        ORDER BY month ASC
    ");
    $revenueData = $revenueDataQuery->fetchAll(PDO::FETCH_ASSOC);

    $packageDataQuery = $pdo->query("
        SELECT package_type, COUNT(*) as count 
        FROM booking 
        GROUP BY package_type
    ");
    $packageData = $packageDataQuery->fetchAll(PDO::FETCH_ASSOC);

    $totalPackagesQuery = $pdo->query("SELECT COUNT(*) as total_packages FROM packages");
    $totalPackages = $totalPackagesQuery->fetch(PDO::FETCH_ASSOC)['total_packages'];

    $totalPhotographersQuery = $pdo->query("SELECT COUNT(*) as total_photographers FROM photographers");
    $totalPhotographers = $totalPhotographersQuery->fetch(PDO::FETCH_ASSOC)['total_photographers'];

    $totalUsersQuery = $pdo->query("SELECT COUNT(*) as total_users FROM users");
    $totalUsers = $totalUsersQuery->fetch(PDO::FETCH_ASSOC)['total_users'];

    $bookingDataQuery = $pdo->query("
        SELECT DATE_FORMAT(datetime, '%Y-%m') as month, COUNT(*) as count 
        FROM booking 
        GROUP BY month 
        ORDER BY month ASC
    ");
    $bookingData = $bookingDataQuery->fetchAll(PDO::FETCH_ASSOC);

    return [
        'totalBookings' => $totalBookings,
        'totalRevenue' => $totalRevenue,
        'revenueData' => $revenueData,
        'packageData' => $packageData,
        'totalPackages' => $totalPackages,
        'totalPhotographers' => $totalPhotographers,
        'totalUsers' => $totalUsers,
        'bookingData' => $bookingData
    ];
}

$data = fetchData($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <link rel="stylesheet" href="super_admin_dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="header">
        <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
        <img src="image/logo.png" alt="Logo" class="logo">
        <h1>Mhark Photography Super Admin Dashboard</h1>
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
            <div class="widgets">
                <div class="widget">
                    <h2>Total Bookings</h2>
                    <p id="total-bookings"><?php echo $data['totalBookings']; ?></p>
                </div>
                <div class="widget">
                    <h2>Total Revenue</h2>
                    <p id="total-revenue">₱<?php echo number_format($data['totalRevenue'], 2); ?></p>
                </div>
                <div class="widget">
                    <h2>Total Packages</h2>
                    <p id="total-packages"><?php echo $data['totalPackages']; ?></p>
                </div>
                <div class="widget">
                    <h2>Total Photographers</h2>
                    <p id="total-photographers"><?php echo $data['totalPhotographers']; ?></p>
                </div>
                <div class="widget">
                    <h2>Total Clients</h2>
                    <p id="total-users"><?php echo $data['totalUsers']; ?></p>
                </div>
            </div>

            <div class="py-2">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Time Period
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#" id="daily-option">Daily</a></li>
                        <li><a class="dropdown-item" href="#" id="weekly-option">Weekly</a></li>
                        <li><a class="dropdown-item" href="#" id="monthly-option">Monthly</a></li>
                        <li><a class="dropdown-item" href="#" id="quarterly-option">Quarterly</a></li>
                        <li><a class="dropdown-item" href="#" id="yearly-option">Yearly</a></li>
                        <li><a class="dropdown-item" href="#" id="cumulative-option">Cumulative (Overall)</a></li>
                    </ul>
                </div>
            </div>

            <div class="py-2">
                <div class="chart">
                    <h2>Revenue Trend</h2>
                    <canvas id="revenue-chart"></canvas>
                </div>
            </div>
            <div class="py-2">
                <div class="chart">
                    <h2>Package Distribution</h2>
                    <canvas id="package-chart"></canvas>
                </div>
            </div>
            <div class="py-2">
                <div class="chart">
                    <h2>Total Bookings Trend</h2>
                    <canvas id="bookings-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const totalBookings = <?php echo $data['totalBookings']; ?>;
            const totalRevenue = <?php echo $data['totalRevenue']; ?>;
            const revenueData = <?php echo json_encode($data['revenueData']); ?>;
            const packageData = <?php echo json_encode($data['packageData']); ?>;
            const totalPackages = <?php echo $data['totalPackages']; ?>;
            const totalPhotographers = <?php echo $data['totalPhotographers']; ?>;
            const totalClients = <?php echo $data['totalUsers']; ?>;
            const bookingData = <?php echo json_encode($data['bookingData']); ?>;

            document.getElementById('total-bookings').textContent = totalBookings;
            document.getElementById('total-revenue').textContent = '₱' + totalRevenue.toFixed(2);
            document.getElementById('total-packages').textContent = totalPackages;
            document.getElementById('total-photographers').textContent = totalPhotographers;
            document.getElementById('total-users').textContent = totalClients;

            const ctxRevenue = document.getElementById('revenue-chart').getContext('2d');
            const revenueChart = new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: revenueData.map(item => item.month),
                    datasets: [{
                        label: 'Revenue',
                        data: revenueData.map(item => item.revenue),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function(value) {
                                    return '₱' + value.toFixed(2);
                                }
                            }
                        }]
                    }
                }
            });

            const ctxPackage = document.getElementById('package-chart').getContext('2d');
            const packageChart = new Chart(ctxPackage, {
                type: 'bar',
                data: {
                    labels: packageData.map(item => item.package_type),
                    datasets: [{
                        label: 'Package Distribution',
                        data: packageData.map(item => item.count),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

            const ctxBookings = document.getElementById('bookings-chart').getContext('2d');
            const bookingsChart = new Chart(ctxBookings, {
                type: 'line',
                data: {
                    labels: bookingData.map(item => item.date),
                    datasets: [{
                        label: 'Total Bookings',
                        data: bookingData.map(item => item.bookings),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

            function updateCharts(timePeriod) {
                const updatedRevenueData = getFilteredData(revenueData, timePeriod);
                revenueChart.data.labels = updatedRevenueData.map(item => item.month);
                revenueChart.data.datasets[0].data = updatedRevenueData.map(item => item.revenue);
                revenueChart.update();

                const updatedPackageData = getFilteredData(packageData, timePeriod);
                packageChart.data.labels = updatedPackageData.map(item => item.package_type);
                packageChart.data.datasets[0].data = updatedPackageData.map(item => item.count);
                packageChart.update();

                const updatedBookingData = getFilteredData(bookingData, timePeriod);
                bookingsChart.data.labels = updatedBookingData.map(item => item.date);
                bookingsChart.data.datasets[0].data = updatedBookingData.map(item => item.bookings);
                bookingsChart.update();
            }

            function getFilteredData(data, timePeriod) {

                return data;
            }

            document.getElementById('daily-option').addEventListener('click', function() {
                updateCharts('daily');
            });
            document.getElementById('weekly-option').addEventListener('click', function() {
                updateCharts('weekly');
            });
            document.getElementById('monthly-option').addEventListener('click', function() {
                updateCharts('monthly');
            });
            document.getElementById('quarterly-option').addEventListener('click', function() {
                updateCharts('quarterly');
            });
            document.getElementById('yearly-option').addEventListener('click', function() {
                updateCharts('yearly');
            });
            document.getElementById('cumulative-option').addEventListener('click', function() {
                updateCharts('cumulative');
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