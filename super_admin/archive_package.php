<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $packageId = intval($_GET['id']);

    try {
        // Update the package status to "archived"
        $stmt = $pdo->prepare("UPDATE packages SET status = 'archived' WHERE id = ?");
        $stmt->execute([$packageId]);

        // Check if the update affected any rows
        if ($stmt->rowCount() > 0) {
            echo "Package archived successfully!";
        } else {
            echo "Error: Package not found or already archived.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Error: Invalid request."; // Handle invalid requests
}
?>
