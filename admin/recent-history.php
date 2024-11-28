<?php
require '../connection.php'; 
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

if ($_SESSION["role"] === "admin") {
    $query = "SELECT * FROM booking ORDER BY datetime DESC LIMIT 5"; 
} else {
    $photographer_id = $_SESSION['user_id']; 
    $query = "SELECT * FROM booking WHERE photographer_id = :photographer_id ORDER BY datetime DESC LIMIT 5";
}

$stmt = $pdo->prepare($query);
if ($_SESSION["role"] !== "admin") {
    $stmt->execute(['photographer_id' => $photographer_id]);
} else {
    $stmt->execute();
}

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recent History</title>
    <link rel="stylesheet" href="recent-history.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="header">
        <div class="sub-header">
            <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
            <img src="image/logo.png" alt="Logo" class="logo">
            <p id="mark">Recent History</p>
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
                <li><a href="recent-history.php"><i class="fas fa-history"></i> <span>Recent History</span></a></li>
                <li><a href="reports.php"><i class="fas fa-chart-line"></i> <span>Reports</span></a></li>
            </ul>
        </div>
        <div class="content">
            <h1>Recent History</h1>
            <div class="dashboard-overview">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Package</th>
                            <th>Price</th>
                            <th>Venue</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['name']) ?></td>
                                <td><?= htmlspecialchars($booking['package_type']) ?></td>
                                <td><?= htmlspecialchars($booking['price']) ?></td>
                                <td><?= htmlspecialchars($booking['venue']) ?></td>
                                <td><?= htmlspecialchars($booking['datetime']) ?></td>
                                <td><?= htmlspecialchars($booking['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-nrmZdZjUu4cL2RhHk8TgdwsM03w1IpoB3IC7jJh18Bss1Pp1b5kpzqq23fZyGStx" crossorigin="anonymous"></script>
</body>
</html>
