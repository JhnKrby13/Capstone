<?php
require '../../connection.php';
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

if ($_SESSION["role"] === "admin" || $_SESSION["role"] === "client") {
} else {
    if (!empty($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        header('Location: ../../admin/admin_dashboard.php');
        exit();
    }
}

function getPhotographers($pdo)
{
    $sql = "SELECT photographers.id, photographers.name, photographers.email, photographers.contact, photographers.address, 
                   booking.datetime AS booking_date 
            FROM photographers 
            LEFT JOIN booking ON photographers.id = booking.photographer_id
            ORDER BY photographers.id, booking.datetime"; // Ordering by photographer ID and booking date
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$photographers = getPhotographers($pdo);
$groupedPhotographers = [];


foreach ($photographers as $photographer) {
    $groupedPhotographers[$photographer['id']]['name'] = $photographer['name'];
    $groupedPhotographers[$photographer['id']]['email'] = $photographer['email'];
    $groupedPhotographers[$photographer['id']]['contact'] = $photographer['contact'];
    $groupedPhotographers[$photographer['id']]['address'] = $photographer['address'];

    if ($photographer['booking_date']) {
        $groupedPhotographers[$photographer['id']]['booking_dates'][] = $photographer['booking_date'];
    }
}

$photographers = getPhotographers($pdo);

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();

    header('Location: ../../auth/login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer</title>
    <link rel="stylesheet" href="photographer.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <div class="header">
        <div class="sub-header">
            <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
            <img src="../image/logo.png" alt="Logo" class="logo">
            <p id="mark">Mhark Photography</p>
        </div>
        <div class="profile-dropdown">
            <h1 style="color:white; font-size: 24px; margin-right: 15px; ">
                <?php
                echo $_SESSION['firstname'];
                ?>
            </h1>
            <div class="dropdown">
                <button class="btn btn-secondary rounded-circle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle "></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <a class="dropdown-item" href="../../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </ul>
            </div>
        </div>
    </div>
    <div class="dashboard">
        <div class="sidebar">
            <ul>
                <li><a href="../packages/"><i class="fas fa-box"></i> <span>Packages</span></a></li>
                <li><a href="../booking/"><i class="fas fa-calendar-check"></i> <span>Booking</span></a></li>
                <li><a href="./" class="active"><i class="fas fa-camera"></i> <span>Photographer List</span></a></li>
                <!-- <li><a href="../gallery/"><i class="fas fa-images"></i> <span>Gallery</span></a></li> -->
            </ul>
        </div>
        <div class="content">
            <h1>Photographer List</h1>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Booking Dates</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($groupedPhotographers as $photographerId => $photographer): ?>
                        <tr>
                            <td><?= htmlspecialchars($photographer['name']) ?></td>
                            <td><?= htmlspecialchars($photographer['email']) ?></td>
                            <td><?= htmlspecialchars($photographer['contact']) ?></td>
                            <td><?= htmlspecialchars($photographer['address']) ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?= $photographerId ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?php
                                        if (isset($photographer['booking_dates']) && !empty($photographer['booking_dates'])) {
                                            echo "View Bookings";
                                        } else {
                                            echo "Not Booked";
                                        }
                                        ?>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?= $photographerId ?>">
                                        <?php if (isset($photographer['booking_dates']) && !empty($photographer['booking_dates'])): ?>
                                            <?php foreach ($photographer['booking_dates'] as $bookingDate): ?>
                                                <li><a class="dropdown-item" href="#">Booked on: <?= htmlspecialchars($bookingDate) ?></a></li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li><a class="dropdown-item" href="#">Not Booked</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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

</html>