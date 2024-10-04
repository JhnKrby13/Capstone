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

try {
    $stmt = $pdo->query("SELECT name, price, description, image_path FROM packages");
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
    <title>Our Packages</title>
    <link rel="stylesheet" href="packages.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="header">
        <div class="sub-header">
            <img src="../image/logo.png" alt="Logo" class="logo">
            <p id="mark">Mhark Photography</p>
        </div>
        <div class="profile-dropdown">
            <h1 style="color:white; font-size: 24px; margin-right: 15px; ">
            <?php
            echo $_SESSION['firstname'].$_SESSION['lastname'];
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
                <li><a href="../recent-history/"><i class="fas fa-history"></i> Recent History</a></li>
                <li><a href="./" class="active"><i class="fas fa-box"></i> Our Packages</a></li>
                <li><a href="../booking/"><i class="fas fa-calendar-check"></i> Booking</a></li>
                <li><a href="../photographer/"><i class="fas fa-camera"></i> Photographer List</a></li>
                <li><a href="../gallery/"><i class="fas fa-images"></i> Gallery</a></li>
            </ul>
        </div>
        <div class="content">
            <h1>Our Packages</h1>
            <div class="packages">
                <h2>Packages</h2>
                <?php if (!empty($packages)): ?>
                    <?php foreach ($packages as $package): ?>
                        <div class="package" onclick="location.href='../booking/index.php?package_type=<?php echo urlencode($package['name']); ?>&price=<?php echo urlencode($package['price']); ?>';">
                            <img src="<?php echo htmlspecialchars($package['image_path']); ?>" alt="<?php echo htmlspecialchars($package['name']); ?>" class="package-img">
                            <h3>• <?php echo htmlspecialchars($package['name']); ?></h3>
                            <p>Price: <?php echo htmlspecialchars($package['price']); ?></p>
                            <p><?php echo nl2br(htmlspecialchars($package['description'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No packages found</p>s
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>

<?php
$pdo = null;
?>
