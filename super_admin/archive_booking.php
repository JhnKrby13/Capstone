<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $bookingId = intval($_POST['booking_id']);

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = :id");
        $stmt->execute(['id' => $bookingId]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($booking) {
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

            $deleteStmt = $pdo->prepare("DELETE FROM bookings WHERE id = :id");
            $deleteStmt->execute(['id' => $bookingId]);

            $pdo->commit();

            header('Location: manage-bookings.php');
            exit;
        } else {
            $pdo->rollBack();
            echo "Booking not found.";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: manage-bookings.php');
    exit;
}
?>
