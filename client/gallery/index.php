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


if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../../auth/login.php');
    exit;
}

$sql = "SELECT * FROM gallery_photos";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$photos = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="gallery.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="header">
        <div class="sub-header">
            <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
            <img src="../image/logo.png" alt="Logo" class="logo">
            <p id="mark">Mhark Photography</p>
        </div>
        <div class="profile-dropdown">
            <h1 style="color:white; font-size: 24px; margin-right: 15px;">
                <?php echo $_SESSION['firstname']; ?>
            </h1>
            <div class="dropdown">
                <button class="btn btn-secondary rounded-circle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i>
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
                <li><a href="../photographer/"><i class="fas fa-camera"></i> <span>Photographer List</span></a></li>
                <li><a href="./" class="active"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
            </ul>
        </div>

        <div class="content">
            <h1>Gallery</h1>

            <div class="filter-container">
                <select id="categoryFilter">
                    <option value="">Select Category</option>
                    <option value="wedding">Wedding</option>
                    <option value="event">Event</option>
                    <option value="portrait">Portrait</option>
                </select>
            </div>

            <div class="gallery-grid">
                <?php foreach ($photos as $photo) : ?>
                    <div class="gallery-item" data-category="<?php echo $photo['category']; ?>">
                        <img src="../../images/<?php echo $photo['image_path']; ?>" alt="Photographer's Photo" class="gallery-thumbnail" data-bs-toggle="modal" data-bs-target="#imageModal<?php echo $photo['id']; ?>">
                        <div class="overlay">
                            <h4><?php echo $photo['photographer_name']; ?></h4>
                            <p><?php echo ucfirst($photo['category']); ?></p>
                        </div>
                    </div>

                    <div class="modal fade" id="imageModal<?php echo $photo['id']; ?>" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <img src="../../images/<?php echo $photo['image_path']; ?>" alt="Photographer's Photo" class="img-fluid">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('categoryFilter').addEventListener('change', function () {
            const selectedCategory = this.value;
            const items = document.querySelectorAll('.gallery-item');

            items.forEach(item => {
                if (selectedCategory && !item.getAttribute('data-category').includes(selectedCategory)) {
                    item.style.display = 'none';
                } else {
                    item.style.display = 'block';
                }
            });
        });

        document.querySelector('.hamburger').addEventListener('click', () => {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            sidebar.classList.toggle('collapsed');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>