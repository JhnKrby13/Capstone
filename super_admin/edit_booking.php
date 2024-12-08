<?php
require '../connection.php';
session_start();

if ($_SESSION["role"] !== "admin") {
    header('Location: ../auth/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM booking WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            echo "Booking not found.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle form submission for editing the booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $package_type = $_POST['package_type'];
    $price = $_POST['price'];
    $venue = $_POST['venue'];
    $datetime = $_POST['datetime'];
    $photographer_id = $_POST['photographer_id'];

    try {
        $stmt = $pdo->prepare("UPDATE booking SET name = :name, package_type = :package_type, price = :price, venue = :venue, datetime = :datetime, photographer_id = :photographer_id WHERE id = :id");
        $stmt->execute([
            'name' => $name, 'package_type' => $package_type, 'price' => $price, 
            'venue' => $venue, 'datetime' => $datetime, 'photographer_id' => $photographer_id, 'id' => $id
        ]);

        header('Location: manage-bookings.php'); // Redirect back to the bookings page
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- Edit booking form -->
<form method="POST" action="edit_booking.php?id=<?php echo $booking['id']; ?>">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($booking['name']); ?>" required>

    <label for="package_type">Package Type</label>
    <input type="text" id="package_type" name="package_type" value="<?php echo htmlspecialchars($booking['package_type']); ?>" required>

    <label for="price">Price</label>
    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($booking['price']); ?>" required>

    <label for="venue">Venue</label>
    <input type="text" id="venue" name="venue" value="<?php echo htmlspecialchars($booking['venue']); ?>" required>

    <label for="datetime">Date & Time</label>
    <input type="datetime-local" id="datetime" name="datetime" value="<?php echo htmlspecialchars($booking['datetime']); ?>" required>

    <label for="photographer_id">Photographer</label>
    <select name="photographer_id" id="photographer_id" required>
        <!-- Fetch photographers and list in the dropdown -->
    </select>

    <button type="submit">Update</button>
</form>
