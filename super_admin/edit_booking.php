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
            'name' => $name,
            'package_type' => $package_type,
            'price' => $price,
            'venue' => $venue,
            'datetime' => $datetime,
            'photographer_id' => $photographer_id,
            'id' => $id
        ]);

        // Redirect after successful update
        header('Location: manage-bookings.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch photographers
$stmt = $pdo->query("SELECT id, name FROM photographers");
$photographers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f4f7fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: bold;
        }

        .form-control {
            margin-bottom: 15px;
        }

        .btn {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control,
        .form-select {
            height: 45px;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Edit Booking</h2>

        <form id="editBookingForm" method="POST" action="edit_booking.php?id=<?php echo $booking['id']; ?>">
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($booking['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="package_type" class="form-label">Package Type</label>
                <input type="text" id="package_type" name="package_type" class="form-control" value="<?php echo htmlspecialchars($booking['package_type']); ?>" required>
            </div>

            <div class="form-group">
                <label for="price" class="form-label">Price</label>
                <input type="number" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($booking['price']); ?>" required>
            </div>

            <div class="form-group">
                <label for="venue" class="form-label">Venue</label>
                <input type="text" id="venue" name="venue" class="form-control" value="<?php echo htmlspecialchars($booking['venue']); ?>" required>
            </div>

            <div class="form-group">
                <label for="datetime" class="form-label">Date & Time</label>
                <input type="datetime-local" id="datetime" name="datetime" class="form-control" value="<?php echo htmlspecialchars($booking['datetime']); ?>" required>
            </div>

            <div class="form-group">
                <label for="photographers_id" class="form-label">Photographer</label>
                <select name="photographer_id" id="photographer_id" class="form-select" required>
                    <option value="">Select Photographer</option>
                    <?php foreach ($photographers as $photographer): ?>
                        <option value="<?php echo $photographer['id']; ?>" <?php echo $photographer['id'] == $booking['photographer_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($photographer['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn">Update Booking</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("editBookingForm").addEventListener("submit", function(e) {
            e.preventDefault(); 

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();

                    Swal.fire({
                        title: "Updated!",
                        text: "Your booking has been updated.",
                        icon: "success"
                    });
                }
            });
        });
    </script>

</body>

</html>
