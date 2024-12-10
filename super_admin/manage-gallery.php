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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Handle Folder Creation
    if (isset($_POST['create_folder'])) {
        $photographerName = $_POST['photographer_name'];
        $baseDir = 'gallery/' . $photographerName;

        if (!is_dir('gallery')) {
            echo "Gallery directory does not exist!";
        }

        $eventName = $_POST['event_name'];
        $eventDir = $baseDir . '/' . $eventName;

        if (!is_dir($eventDir)) {
            mkdir($eventDir, 0777, true); 
        }
    }

    // Handle Folder Renaming
    if (isset($_POST['rename_folder'])) {
        $oldFolderName = $_POST['old_folder_name'];
        $newFolderName = $_POST['new_folder_name'];

        $oldPath = 'gallery/' . $_POST['photographer_name'] . '/' . $oldFolderName;
        $newPath = 'gallery/' . $_POST['photographer_name'] . '/' . $newFolderName;

        if (rename($oldPath, $newPath)) {
            echo "Folder renamed successfully!";
        } else {
            echo "Failed to rename folder!";
        }
    }

    if (isset($_FILES['photo'])) {
        $photographerName = $_POST['photographer_name'];
        $eventName = $_POST['event_name'];
        $photo = $_FILES['photo'];

        $uploadDir = 'gallery/' . $photographerName . '/' . $eventName . '/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadPath = $uploadDir . basename($photo['name']);

        if (move_uploaded_file($photo['tmp_name'], $uploadPath)) {
            echo "File uploaded successfully!";
        } else {
            echo "Failed to upload file!";
        }
    }

    // Handle Folder Deletion
    if (isset($_POST['delete_folder'])) {
        $photographerName = $_POST['photographer_name_delete'];
        $eventName = $_POST['event_name_delete'];
        $folderPath = 'gallery/' . $photographerName . '/' . $eventName;

        if (is_dir($folderPath)) {
            function deleteFolder($folderPath)
            {
                $files = array_diff(scandir($folderPath), array('.', '..'));
                foreach ($files as $file) {
                    $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
                    (is_dir($filePath)) ? deleteFolder($filePath) : unlink($filePath);
                }
                return rmdir($folderPath);
            }

            if (deleteFolder($folderPath)) {
                echo "Folder deleted successfully!";
            } else {
                echo "Failed to delete folder!";
            }
        } else {
            echo "The folder does not exist!";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <a class="dropdown-item" href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
                <!-- <li><a href="recent-history.php"><i class="fas fa-calendar-alt"></i> <span>Recent History</span></a></li> -->
                <li><a href="recycle-bin.php"><i class="fas fa-trash-alt"></i> <span>Archive</span></a></li>
                <!-- <li><a href="system-settings.php"><i class="fas fa-cogs"></i> <span>Settings</span></a></li> -->
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

            <div id="folderManagementPopup" class="folder-management-popup" style="display: none;">
                <div class="popup-content">
                    <button id="closePopup" class="close-popup">×</button>
                    <h3>Manage Folders for <span id="photographerName"></span></h3>

                    <div class="form-container">
                        <h4>Create a New Folder</h4>
                        <form action="manage-gallery.php" method="POST">
                            <label for="event_name">Event Name:</label>
                            <input type="text" name="event_name" required>

                            <input type="hidden" name="photographer_name" id="photographerNameInput">

                            <button type="submit" name="create_folder">Create Folder</button>
                        </form>

                        <div class="form-container">
                            <h4>Rename a Folder</h4>
                            <form action="manage-gallery.php" method="POST">
                            <label for="old_folder_name">Old Folder Name:</label>
                            <input type="text" name="old_folder_name" required>

                            <label for="new_folder_name">New Folder Name:</label>
                            <input type="text" name="new_folder_name" required>

                            <!-- Hidden input to pass photographer name -->
                            <input type="hidden" name="photographer_name" id="photographerNameInput">

                            <button type="submit" name="rename_folder">Rename Folder</button>
                        </form>

                        <div class="form-container">
                            <h4>Upload Photo</h4>
                            <form action="manage-gallery.php" method="POST" enctype="multipart/form-data">
                                <label for="photo">Select a Photo to Upload:</label>
                                <input type="file" name="photo" accept="image/*" required>

                                <button type="submit">Upload Photo</button>
                            </form>
                        </div>

                        <div class="form-container">
                            <h4>Delete Folder</h4>
                            <form action="manage-gallery.php" method="POST">


                                <label for="event_name_delete">Event Name:</label>
                                <input type="text" name="event_name_delete" required>

                                <button type="submit" name="delete_folder">Delete Folder</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            $(document).ready(function() {
            $('.photographer-link').click(function(e) {
                e.preventDefault();

                var photographerName = $(this).text();  // Get the photographer name
                var photographerId = $(this).data('id');

                // Set photographer name in the hidden input field
                $('#photographerNameInput').val(photographerName);

                $('#folderManagementPopup').show();
                $('#photographerName').text(photographerName);
            });
        });

            document.querySelectorAll('.photographer-link').forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent default link behavior
                    var photographerId = event.target.getAttribute('data-id');
                    var photographerName = event.target.textContent;

                    // Set photographer name in the folder management header
                    document.getElementById('photographerName').textContent = photographerName;

                    // Show the folder management pop-up
                    var popup = document.getElementById('folderManagementPopup');
                    popup.style.display = 'block';
                    setTimeout(function() {
                        popup.style.opacity = '1';
                    }, 10);

                    document.querySelector('.dashboard').classList.add('popup-active');
                });
            });

            document.getElementById('closePopup').addEventListener('click', function() {
                var popup = document.getElementById('folderManagementPopup');
                popup.style.opacity = '0';
                setTimeout(function() {
                    popup.style.display = 'none';
                }, 300);

                document.querySelector('.dashboard').classList.remove('popup-active');
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