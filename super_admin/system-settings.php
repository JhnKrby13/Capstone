<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $bookingId = intval($_POST['booking_id']);

    try {
        // Fetch the booking record
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = :id");
        $stmt->execute(['id' => $bookingId]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($booking) {
            // Insert into the archive table
            $archiveStmt = $pdo->prepare("
                INSERT INTO bookings_archive (id, name, package_type, price, venue, datetime, deleted_at)
                VALUES (:id, :name, :package_type, :price, :venue, :datetime, NOW())
            ");
            $archiveStmt->execute([
                'id' => $booking['id'],
                'name' => $booking['name'],
                'package_type' => $booking['package_type'],
                'price' => $booking['price'],
                'venue' => $booking['venue'],
                'datetime' => $booking['datetime']
            ]);

            // Delete the booking from the original table
            $deleteStmt = $pdo->prepare("DELETE FROM bookings WHERE id = :id");
            $deleteStmt->execute(['id' => $bookingId]);

            header('Location: manage-bookings.php');
        } else {
            echo "Booking not found.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: manage-bookings.php');
}
?>
