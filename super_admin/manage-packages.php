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

$targetDir = '../image/';

if (isset($_POST['add_package'])) {
    try {
        $package_name = htmlspecialchars($_POST['package_name']);
        $package_price = htmlspecialchars($_POST['package_price']);
        $package_description = htmlspecialchars($_POST['package_description']);

        if (isset($_FILES['package_image']) && $_FILES['package_image']['error'] === UPLOAD_ERR_OK) {
            $image_temp_name = $_FILES['package_image']['tmp_name'];
            $image_name = basename($_FILES['package_image']['name']);
            $image_path = $targetDir . $image_name;

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            if (move_uploaded_file($image_temp_name, $image_path)) {
                $stmt = $pdo->prepare("INSERT INTO packages (name, price, description, image_path) VALUES (?, ?, ?, ?)");
                $stmt->execute([$package_name, $package_price, $package_description, $image_path]);

                $message = "Package added successfully!";
            } else {
                $message = "Failed to upload image.";
            }
        } else {
            $message = "No image uploaded.";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

if (isset($_GET['delete'])) {
    try {
        $package_id = intval($_GET['delete']);

        $stmt = $pdo->prepare("DELETE FROM packages WHERE id = ?");
        $stmt->execute([$package_id]);

        $message = "Package deleted successfully!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

try {
    $stmt = $pdo->query("SELECT id, name, price, description, image_path FROM packages");
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Error fetching packages: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Packages</title></title>
    <link rel="stylesheet" href="manage-packages.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="header">
        <div class="sub-header">
            <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
            <img src="image/logo.png" alt="Logo" class="logo">
            <p id="mark">Mhark Photography Packages</p>
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
                <li><a href="recent-history.php"><i class="fas fa-calendar-alt"></i> <span>Recent History</span></a></li>
                <li><a href="recycle-bin.php"><i class="fas fa-trash-alt"></i> <span>Archive</span></a></li>
                <li><a href="system-settings.php"><i class="fas fa-cogs"></i> <span>Settings</span></a></li>
            </ul>
        </div>
        <div class="content">
            <h1>Package List</h1>
            <?php if (isset($message)): ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
            <form action="manage-packages.php" method="post" enctype="multipart/form-data">
                <h2>Add New Package</h2>
                <label for="package_name">Package Name:</label>
                <input type="text" id="package_name" name="package_name" required>

                <label for="package_price">Package Price:</label>
                <input type="number" id="package_price" name="package_price" required>

                <label for="package_description">Description:</label>
                <textarea id="package_description" name="package_description" rows="4" required></textarea>

                <label for="package_image">Package Image:</label>
                <input type="file" id="package_image" name="package_image" required>

                <input type="submit" name="add_package" value="Add Package">
            </form>

            <h2>Existing Packages</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($packages)): ?>
                        <?php foreach ($packages as $package): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($package['id']); ?></td>
                                <td><?php echo htmlspecialchars($package['name']); ?></td>
                                <td><?php echo htmlspecialchars($package['price']); ?></td>
                                <td><?php echo htmlspecialchars($package['description']); ?></td>
                                <td><img src="<?php echo htmlspecialchars($package['image_path']); ?>" alt="Package Image" class="img-thumbnail" style="width: 100px; height: auto;"></td>
                                <td>
                                    <a href="edit-package.php?id=<?php echo htmlspecialchars($package['id']); ?>" class="edit"><i class="fas fa-edit"></i></a>
                                    <a href="manage-packages.php?delete=<?php echo htmlspecialchars($package['id']); ?>" class="delete" onclick="return confirm('Are you sure you want to delete this package?');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No packages found</td>
                        </tr>
                    <?php endif; ?>
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
</body>
</html>

<?php
$pdo = null;
?>
