<?php
require '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['restore'])) {
    $bookingId = intval($_GET['restore']);

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT * FROM booking_archive WHERE id = :id");
        $stmt->execute(['id' => $bookingId]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($booking) {
            $restoreStmt = $pdo->prepare("
                INSERT INTO booking (id, name, address, package_type, price, venue, datetime, payment_mode, status, photographer_id, client_id, created_a)
                VALUES (:id, :name, :address, :package_type, :price, :venue, :datetime, :payment_mode, :status, :photographer_id, :client_id, :created_a)
            ");
            $restoreStmt->execute([
                'id' => $booking['id'],
                'name' => $booking['name'],
                'address' => $booking['address'],
                'package_type' => $booking['package_type'],
                'price' => $booking['price'],
                'venue' => $booking['venue'],
                'datetime' => $booking['datetime'],
                'payment_mode' => $booking['payment_mode'],
                'status' => $booking['status'],
                'photographer_id' => $booking['photographer_id'],
                'client_id' => $booking['client_id'],
                'created_a' => $booking['created_a']
            ]);

            $deleteStmt = $pdo->prepare("DELETE FROM booking_archive WHERE id = :id");
            $deleteStmt->execute(['id' => $bookingId]);

            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'Booking restored successfully.']);
            exit;
        } else {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Booking not found in the archive.']);
            exit;
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error restoring booking: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}
?>
