<?php
require '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $photographerId = intval($_GET['delete']);

    try {
        $stmt = $pdo->prepare("DELETE FROM photographer_archive WHERE id = :id");
        $stmt->execute(['id' => $photographerId]);

        echo json_encode(['status' => 'success', 'message' => 'Photographer deleted permanently.']);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}
?>
