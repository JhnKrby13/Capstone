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

    $user_email = $_SESSION['email'];
    $totalBookingsCountQuery = "SELECT id FROM photographers WHERE email = :user_email";
    $stmt = $pdo->prepare($totalBookingsCountQuery);
    $stmt->bindValue(':user_email', $user_email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $photographer_id = $result['id'] ?? null;
    $totalBookingsQuery = "SELECT COUNT(*) AS total FROM booking WHERE photographer_id = :photographer_id";
    $stmt = $pdo->prepare($totalBookingsQuery);
    $stmt->bindValue(':photographer_id', $photographer_id, PDO::PARAM_STR);
    $stmt->execute();
    $totalBookings = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    $upcomingShootsQuery = "SELECT COUNT(*) AS upcoming FROM booking WHERE photographer_id = :photographer_id AND status = 'pending'";
    $stmt = $pdo->prepare($upcomingShootsQuery);
    $stmt->execute(['photographer_id' => $photographer_id]);
    $upcomingShoots = $stmt->fetch(PDO::FETCH_ASSOC)['upcoming'] ?? 0;

    $recentBookingsQuery = "SELECT client_id, datetime, status FROM booking WHERE photographer_id = :photographer_id ORDER BY datetime DESC LIMIT 5";
    $stmt = $pdo->prepare($recentBookingsQuery);
    $stmt->execute(['photographer_id' => $photographer_id]);
    $recentBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $photographerName = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Photographer';
    $monthlyBookingsQuery = "SELECT MONTH(datetime) AS month, COUNT(*) AS total_bookings 
                         FROM booking 
                         WHERE photographer_id = :photographer_id 
                         GROUP BY MONTH(datetime)";
    $stmt = $pdo->prepare($monthlyBookingsQuery);
    $stmt->bindValue(':photographer_id', $photographer_id, PDO::PARAM_INT);
    $stmt->execute();
    $monthlyBookingsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $monthlyBookings = array_fill(1, 12, 0);
    foreach ($monthlyBookingsData as $row) {
        $monthlyBookings[(int)$row['month']] = (int)$row['total_bookings'];
    }

    $monthlyEarningsQuery = "SELECT MONTH(datetime) AS month, SUM(price) AS total_earnings 
                         FROM booking 
                         WHERE photographer_id = :photographer_id 
                         GROUP BY MONTH(datetime)";
    $stmt = $pdo->prepare($monthlyEarningsQuery);
    $stmt->bindValue(':photographer_id', $photographer_id, PDO::PARAM_INT);
    $stmt->execute();
    $monthlyEarningsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $monthlyEarnings = array_fill(1, 12, 0); 
    foreach ($monthlyEarningsData as $row) {
        $monthlyEarnings[(int)$row['month']] = (float)$row['total_earnings'];
    }
    ?>

 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Photographer Dashboard</title>
     <link rel="stylesheet" href="admin_dashboard.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 </head>

 <body>
     <div class="header">
         <div class="sub-header">
             <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
             <img src="image/logo.png" alt="Logo" class="logo">
             <p id="mark">Photographer Dashboard</p>
         </div>
         <div class="profile-dropdown">
             <h1 style="color:white; font-size: 24px; margin-right: 15px;">
                 <?php echo $photographerName; ?>
             </h1>
             <div class="dropdown">
                 <button class="btn btn-secondary rounded-circle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                     <i class="fas fa-user-circle"></i>
                 </button>
                 <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                     <a class="dropdown-item" href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                 </ul>
             </div>
         </div>
     </div>
     <div class="dashboard d-flex">
         <div class="sidebar">
             <ul>
                 <li><a href="photographer_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                 <li><a href="manage-bookings.php"><i class="fas fa-calendar-check"></i> <span>Bookings</span></a></li>
                 <li><a href="manage-gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
                 <!-- <li><a href="recent-history.php"><i class="fas fa-history"></i> <span>Recent History</span></a></li> -->
                 <li><a href="reports.php"><i class="fas fa-chart-line"></i> <span>Reports</a></li>
             </ul>
         </div>


         <div class="content">
             <h1>Welcome, <?php echo $photographerName; ?>!</h1>

             <div class="widgets row">
                 <div class="widget col-md-4">
                     <h2>Total Bookings</h2>
                     <p><?php echo $totalBookings; ?></p>
                 </div>
                 <div class="widget col-md-4">
                     <h2>Upcoming Shoots</h2>
                     <p><?php echo $upcomingShoots; ?></p>
                 </div>
             </div>
             <div class="charts row mt-4">
                 <div class="chart-container col-md-6">
                     <h2>Monthly Bookings</h2>
                     <div id="bookingsChart"></div>
                 </div>
                 <div class="chart-container col-md-6">
                     <h2>Earnings Overview</h2>
                     <div id="earningsChart"></div>
                 </div>
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
                                 <td><?php echo htmlspecialchars($booking['client_id']); ?></td>
                                 <td><?php echo htmlspecialchars($booking['datetime']); ?></td>
                                 <td><?php echo htmlspecialchars($booking['status']); ?></td>
                             </tr>
                         <?php endforeach; ?>
                     </tbody>
                 </table>
             </div>
         </div>
     </div>

     <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
     <script>
         const monthlyBookings = <?php echo json_encode(array_values($monthlyBookings)); ?>;
         const monthlyEarnings = <?php echo json_encode(array_values($monthlyEarnings)); ?>;
         document.addEventListener('DOMContentLoaded', () => {
             // Bookings Chart
             var bookingsOptions = {
                 chart: {
                     type: 'bar',
                     height: 350
                 },
                 series: [{
                     name: 'Bookings',
                     data: monthlyBookings
                 }],
                 xaxis: {
                     categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                 }
             };
             var bookingsChart = new ApexCharts(document.querySelector("#bookingsChart"), bookingsOptions);
             bookingsChart.render();

             // Earnings Chart
             var earningsOptions = {
                 chart: {
                     type: 'line',
                     height: 350
                 },
                 series: [{
                     name: 'Earnings',
                     data: monthlyEarnings
                 }],
                 xaxis: {
                     categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                 }
             };
             var earningsChart = new ApexCharts(document.querySelector("#earningsChart"), earningsOptions);
             earningsChart.render();
         });


         document.querySelector('.hamburger').addEventListener('click', () => {
             const sidebar = document.querySelector('.sidebar');
             sidebar.classList.toggle('collapsed');
         });
     </script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
 </body>

 </html>