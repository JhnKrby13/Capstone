<?php
require '../connection.php';  // Make sure to include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $bookingId = intval($_GET['delete']);  // Get the booking ID from the GET request

    try {
        // Delete the booking permanently from the archive
        $stmt = $pdo->prepare("DELETE FROM booking_archive WHERE id = :id");
        $stmt->execute(['id' => $bookingId]);

        // Redirect after deleting the booking
        header('Location: manage-bookings.php?success=1');
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect if the request method is not GET or ID is not set
    header('Location: manage-bookings.php');
    exit;
}
?>
