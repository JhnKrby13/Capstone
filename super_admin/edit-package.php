<?php
require '../connection.php';

session_start();
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

if ($_SESSION["role"] !== "admin") {
    header('Location: ../unauthorized.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Package ID is missing!";
    exit;
}

$packageId = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->execute([$packageId]);
$package = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$package) {
    echo "Package not found!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $package_name = htmlspecialchars($_POST['package_name']);
        $package_price = htmlspecialchars($_POST['package_price']);
        $package_description = htmlspecialchars($_POST['package_description']);

        $stmt = $pdo->prepare("UPDATE packages SET name = ?, price = ?, description = ? WHERE id = ?");
        $stmt->execute([$package_name, $package_price, $package_description, $packageId]);

        $_SESSION['package_updated'] = true;
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Package</title>
    <link rel="stylesheet" href="edit-package.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Edit Package</h1>

        <?php if (isset($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <form id="editPackageForm" method="POST">
            <label for="package_name">Package Name:</label>
            <input type="text" id="package_name" name="package_name" value="<?php echo htmlspecialchars($package['name']); ?>" required>

            <label for="package_price">Package Price:</label>
            <input type="number" id="package_price" name="package_price" value="<?php echo htmlspecialchars($package['price']); ?>" required>

            <label for="package_description">Description:</label>
            <textarea id="package_description" name="package_description" required><?php echo htmlspecialchars($package['description']); ?></textarea>

            <input type="button" value="Save Changes" onclick="confirmUpdate()">
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.min.js"></script>

        <script>
            function confirmUpdate() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to save the changes to this package?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('editPackageForm').submit();
                    }
                });
            }

            <?php if (isset($_SESSION['package_updated']) && $_SESSION['package_updated']): ?>
                Swal.fire({
                    title: 'Saved!',
                    text: 'Your changes have been saved.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'manage-packages.php'; 
                });
                <?php unset($_SESSION['package_updated']); ?> 
            <?php endif; ?>
        </script>
</body>

</html>