<?php
require '../connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = intval($_POST['id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM client_archive WHERE id = :id");
        $stmt->execute(['id' => $userId]);

        if ($stmt->rowCount()) {
            echo json_encode(['status' => 'success', 'message' => 'Client deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Client not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
