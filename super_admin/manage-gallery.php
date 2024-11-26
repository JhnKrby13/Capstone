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
        
    } else if ($_SESSION["role"] === "photographer"){
        if (!empty($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: ../admin/admin_dashboard.php');
            exit();
        }
    }
} 

$query = $pdo->prepare("SELECT id, name FROM photographers");
$query->execute();
$photographers = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery</title>
    <link rel="stylesheet" href="manage-gallery.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="header">
        <div class="sub-header">
            <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
            <img src="image/logo.png" alt="Logo" class="logo">
            <p id="mark">Mhark Photography Gallery</p>
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
                    <a class="dropdown-item" href="auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
            <h1>Manage Gallery</h1>
            <div class="photographers-list">
                <h2>Select a Photographer</h2>
                <ul>
                    <?php foreach ($photographers as $photographer): ?>
                        <li>
                            <a href="#" class="photographer-link" data-id="<?= $photographer['id'] ?>">
                                <?= htmlspecialchars($photographer['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div id="photos-gallery" class="photos-gallery">
                <h2>Photos</h2>
                <div id="photos-container"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.photographer-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const photographerId = this.getAttribute('data-id');
                
                fetchPhotos(photographerId);
            });
        });

        function fetchPhotos(photographerId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `fetch_photos.php?photographer_id=${photographerId}`, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('photos-container').innerHTML = this.responseText;
                }
            };
            xhr.send();
        }
        document.querySelector('.hamburger').addEventListener('click', () => {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            sidebar.classList.toggle('collapsed');
        });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>