<?php
require '../connection.php';  // Ensure to include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $packageId = intval($_POST['id']);  // Get the package ID from the POST request

    try {
        $pdo->beginTransaction();

        // Move the package to the archive
        $stmt = $pdo->prepare("INSERT INTO package_archive (id, name, price, description, image_path)
                               SELECT id, name, price, description, image_path
                               FROM packages WHERE id = :id");
        $stmt->execute(['id' => $packageId]);

        // Delete the package from the packages table
        $stmt = $pdo->prepare("DELETE FROM packages WHERE id = :id");
        $stmt->execute(['id' => $packageId]);

        $pdo->commit();
        
        header('Location: manage-packages.php?success=1'); // Redirect after successful archive
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: manage-packages.php');
    exit;
}
?>
