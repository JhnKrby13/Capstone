<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $bookingId = intval($_POST['id']); // Sanitize the input

    try {
        $pdo->beginTransaction(); // Start transaction

        $stmt = $pdo->prepare("SELECT * FROM booking WHERE id = :id");
        $stmt->execute(['id' => $bookingId]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($booking) {
            // Insert into booking_archive table
            $archiveStmt = $pdo->prepare("
                INSERT INTO booking_archive (id, name, address, package_type, price, venue, datetime, payment_mode, status, photographer_id, client_id)
                VALUES (:id, :name, :address, :package_type, :price, :venue, :datetime, :payment_mode, :status, :photographer_id, :client_id)
            ");
            $archiveStmt->execute([
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
                'client_id' => $booking['client_id']
            ]);

            // Delete the booking from the original table
            $deleteStmt = $pdo->prepare("DELETE FROM booking WHERE id = :id");
            $deleteStmt->execute(['id' => $bookingId]);

            $pdo->commit(); // Commit transaction

            // Redirect back to manage bookings
            header('Location: manage-bookings.php?success=1');
            exit;
        } else {
            $pdo->rollBack(); // Rollback transaction
            echo "Booking not found.";
        }
    } catch (Exception $e) {
        $pdo->rollBack(); // Rollback on error
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: manage-bookings.php');
    exit;
}
?>
