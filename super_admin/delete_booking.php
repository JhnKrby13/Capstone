<?php
require '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $bookingId = intval($_GET['delete']);

    try {
        // Delete booking from the archive (or directly from the main table if no archive exists)
        $stmt = $pdo->prepare("DELETE FROM booking_archive WHERE id = :id");
        $stmt->execute(['id' => $bookingId]);

        echo json_encode(['status' => 'success', 'message' => 'Booking deleted permanently.']);
        exit;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete booking.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}
?>
