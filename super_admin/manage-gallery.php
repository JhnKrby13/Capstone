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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <img src="image/logo.png" alt="Logo" class="logo">
        <h1>Mhark Photography Manage Gallery</h1>
    </div>
    <div class="dashboard">
        <div class="sidebar">
            <ul>
            <li><a href="super_admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="manage-bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
                <li><a href="manage-packages.php"><i class="fas fa-box"></i> Packages</a></li>
                <li><a href="manage-photographer.php"><i class="fas fa-camera"></i> Photographers</a></li>
                <li><a href="manage-users.php"><i class="fas fa-users"></i> Clients</a></li>
                <li><a href="manage-gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                <li><a href="recent-history.php"><i class="fas fa-history"></i> Recent History</a></li>
                <li><a href="recycle-bin.php"><i class="fas fa-trash-alt"></i> Archive</a></li>
                <li><a href="system-settings.php"><i class="fas fa-cogs"></i> Settings</a></li>
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
    </script>
</body>
</html>