<?php
require '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $packageId = intval($_GET['delete']);

    try {
        $stmt = $pdo->prepare("DELETE FROM package_archive WHERE id = :id");
        $stmt->execute(['id' => $packageId]);

        echo json_encode(['status' => 'success', 'message' => 'Package deleted permanently.']);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete package.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}
?>
