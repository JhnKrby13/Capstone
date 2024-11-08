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

$baseDir = 'uploads/';
$currentFolder = isset($_GET['folder']) ? $_GET['folder'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photos'])) {
    $folderName = $_POST['folder_name'];
    $targetDir = $baseDir . $currentFolder . '/' . $folderName . '/';

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $uploadCount = count($_FILES['photos']['name']);
    if ($uploadCount > 5000) {
        echo "You can upload up to 5,000 photos only.";
    } else {
        for ($i = 0; $i < $uploadCount; $i++) {
            $targetFile = $targetDir . basename($_FILES['photos']['name'][$i]);
            move_uploaded_file($_FILES['photos']['tmp_name'][$i], $targetFile);
        }
        echo "Files uploaded successfully!";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_folder_name'])) {
    $newFolderName = $_POST['new_folder_name'];
    $newFolderPath = $baseDir . $currentFolder . '/' . $newFolderName . '/';

    if (!file_exists($newFolderPath)) {
        mkdir($newFolderPath, 0777, true);
        echo "Folder created successfully!";
    } else {
        echo "Folder already exists!";
    }
}

$targetDir = $baseDir . $currentFolder . '/';
$folders = array_filter(glob($targetDir . '*'), 'is_dir');
$files = array_filter(glob($targetDir . '*'), 'is_file');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery</title>
    <link rel="stylesheet" href="manage-gallery.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="header">
        <img src="image/logo.png" alt="Logo" class="logo">
        <h1>Gallery</h1>
    </div>
    <div class="dashboard">
        <div class="sidebar">
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage-bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a></li>
                <li><a href="manage-gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                <li><a href="recent-history.php"><i class="fas fa-history"></i> Recent History</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a></li>
            </ul>
        </div>
        <div class="content">
            <h1>Upload Photos</h1>
            <form action="manage-gallery.php?folder=<?php echo $currentFolder; ?>" method="post" enctype="multipart/form-data">
                <label for="folder_name">Folder Name:</label>
                <input type="text" id="folder_name" name="folder_name" required>
                <label for="photos">Select Photos:</label>
                <input type="file" id="photos" name="photos[]" multiple required>
                <input type="submit" value="Upload Photos">
            </form>
            <div class="create-folder">
                <h2>Create Folder</h2>
                <form action="manage-gallery.php?folder=<?php echo $currentFolder; ?>" method="post">
                    <label for="new_folder_name">New Folder Name:</label>
                    <input type="text" id="new_folder_name" name="new_folder_name" required>
                    <input type="submit" value="Create Folder">
                </form>
            </div>
            <div class="gallery">
                <h2>Existing Folders</h2>
                <?php if (!empty($folders)): ?>
                    <ul class="folders">
                        <?php foreach ($folders as $folder): ?>
                            <li>
                                <a href="manage-gallery.php?folder=<?php echo $currentFolder . '/' . basename($folder); ?>">
                                    <img src="image/folder.png" alt="Folder Icon">
                                    <strong><?php echo basename($folder); ?></strong>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No folders found</p>
                <?php endif; ?>
            </div>
            <div class="gallery">
                <h2>Files in Current Folder</h2>
                <?php if (!empty($files)): ?>
                    <table>
                        <tr>
                            <?php foreach ($files as $file): ?>
                                <td>
                                    <a href="#lightbox-<?php echo basename($file); ?>">
                                        <img src="<?php echo htmlspecialchars($file); ?>" alt="File Image" class="img-thumbnail">
                                    </a>
                                    <div id="lightbox-<?php echo basename($file); ?>" class="lightbox">
                                        <img src="<?php echo htmlspecialchars($file); ?>" alt="File Image">
                                    </div>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </table>
                <?php else: ?>
                    <p>No files found</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>                
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lightboxElements = document.querySelectorAll('.lightbox');

            lightboxElements.forEach(lightbox => {
                lightbox.addEventListener('click', function() {
                    lightbox.classList.remove('show');
                });
            });

            const lightboxLinks = document.querySelectorAll('.img-thumbnail');

            lightboxLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const lightboxId = this.closest('a').getAttribute('href');
                    document.querySelector(lightboxId).classList.add('show');
                });
            });
        });
    </script>
</body>
</html>