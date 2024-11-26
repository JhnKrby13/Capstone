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

function getPhotographers($pdo) {
    $sql = "SELECT id, name, email, contact, address FROM photographers";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['add_photographer'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    $insertQuery = $pdo->prepare("INSERT INTO photographers (name, email, contact, address) VALUES (?, ?, ?, ?)");
    $insertQuery->execute([$name, $email, $contact, $address]);

    header("Location: manage-photographer.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $deleteQuery = $pdo->prepare("DELETE FROM photographers WHERE id = ?");
    $deleteQuery->execute([$id]);

    header("Location: manage-photographer.php");
    exit();
}

$photographers = getPhotographers($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Photographer</title>
    <link rel="stylesheet" href="manage-photographer.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="header">
        <div class="sub-header">
            <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
            <img src="image/logo.png" alt="Logo" class="logo">
            <p id="mark">Mhark Photography Photographer</p>
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
            <h1>Manage Photographers</h1>
            <form action="manage-photographer.php" method="post">
                <input type="hidden" name="add_photographer" value="1">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="contact">Contact:</label>
                <input type="text" id="contact" name="contact" required>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
                <input type="submit" value="Add Photographer">
            </form>
            
            <h2>Existing Photographers</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($photographers)): ?>
                        <?php foreach ($photographers as $photographer): ?>
                            <tr>
                                <td><?= htmlspecialchars($photographer['name']) ?></td>
                                <td><?= htmlspecialchars($photographer['email']) ?></td>
                                <td><?= htmlspecialchars($photographer['contact']) ?></td>
                                <td><?= htmlspecialchars($photographer['address']) ?></td>
                                <td>
                                    <a href="edit-photographer.php?id=<?= $photographer['id'] ?>">Edit</a>
                                    <a href="manage-photographer.php?delete=<?= $photographer['id'] ?>" onclick="return confirm('Are you sure you want to delete this photographer?')">Archieve</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No photographers found</td></tr>
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
