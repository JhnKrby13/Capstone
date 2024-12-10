<?php
require '../connection.php';  // Make sure to include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['restore'])) {
    $bookingId = intval($_GET['restore']);  // Get the booking ID from the GET request

    try {
        $pdo->beginTransaction();

        // Fetch the booking details from the archive table
        $stmt = $pdo->prepare("SELECT * FROM booking_archive WHERE id = :id");
        $stmt->execute(['id' => $bookingId]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($booking) {
            // Insert the booking back into the main bookings table
            $restoreStmt = $pdo->prepare("
                INSERT INTO booking (id, name, address, package_type, price, venue, datetime, payment_mode, status, photographer_id, client_id, created_at)
                VALUES (:id, :name, :address, :package_type, :price, :venue, :datetime, :payment_mode, :status, :photographer_id, :client_id, :created_at)
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
                'created_at' => $booking['created_at']
            ]);

            // Delete the booking from the booking_archive table
            $deleteStmt = $pdo->prepare("DELETE FROM booking_archive WHERE id = :id");
            $deleteStmt->execute(['id' => $bookingId]);

            $pdo->commit();

            // Redirect after restoring the booking
            header('Location: manage-bookings.php?success=1');
            exit;
        } else {
            // Rollback if the booking is not found in the archive
            $pdo->rollBack();
            echo "Booking not found in the archive.";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect if the request method is not GET or ID is not set
    header('Location: manage-bookings.php');
    exit;
}
?>
