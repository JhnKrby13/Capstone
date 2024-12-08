<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['status'])) {
        $bookingId = $_POST['id'];
        $status = $_POST['status'];

        try {
            // Prepare the query to update the booking status
            $stmt = $pdo->prepare("UPDATE booking SET status = :status WHERE id = :id");
            $stmt->execute(['status' => $status, 'id' => $bookingId]);

            // Return success message
            echo "Booking status updated to " . $status;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid data provided.";
    }
} else {
    echo "Invalid request method.";
}
?>
