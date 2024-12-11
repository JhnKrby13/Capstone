<?php
require '../connection.php';
session_start();

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="reports.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="header">
        <div class="sub-header">
            <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
            <img src="image/logo.png" alt="Logo" class="logo">
            <p id="mark">Report</p>
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
            <h1>Reports</h1>
            <div class="dashboard-overview">
                <div class="overview-card">
                    <h3>Total Bookings</h3>
                    <p>
                        <?php
                        $query = "SELECT COUNT(*) FROM booking";
                        $stmt = $pdo->query($query);
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo $row['COUNT(*)'];
                        ?>
                    </p>
                </div>
                <div class="overview-card">
                    <h3>Total Revenue</h3>
                    <p>
                        <?php
                        $query = "SELECT SUM(price) FROM booking";
                        $stmt = $pdo->query($query);
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo number_format($row['SUM(price)'], 2);
                        ?>
                    </p>
                </div>
            </div>

            <!-- Bookings per Photographer -->
            <div class="report-section">
                <h2>Bookings per Photographer</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Photographer Name</th>
                            <th>Total Bookings</th>
                            <th>Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT photographer_id, COUNT(*) AS bookings_count, SUM(price) AS total_revenue FROM booking GROUP BY photographer_id";
                        $stmt = $pdo->query($query);

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Corrected query to fetch the 'name' column
                            $photographerQuery = "SELECT name FROM photographers WHERE id = " . $row['photographer_id'];
                            $photographerResult = $pdo->query($photographerQuery);
                            $photographer = $photographerResult->fetch(PDO::FETCH_ASSOC);

                            echo "<tr>";
                            echo "<td>" . $photographer['name'] . "</td>";  // Use 'name' instead of 'firstname' and 'lastname'
                            echo "<td>" . $row['bookings_count'] . "</td>";
                            echo "<td>" . number_format($row['total_revenue'], 2) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="report-section">
                <h2>Most Popular Packages</h2>
                <canvas id="popularPackagesChart" width="400" height="200"></canvas>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    var ctx = document.getElementById('popularPackagesChart').getContext('2d');
                    var popularPackagesChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Package 1', 'Package 2', 'Package 3'], 
                            datasets: [{
                                label: 'Bookings',
                                data: [12, 19, 3], 
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>
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