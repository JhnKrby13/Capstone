<?php
require '../connection.php';

session_start();

// Redirect if not logged in or not an admin
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION["role"] !== "admin") {
    header('Location: ../../auth/login.php');
    exit;
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userId = $_GET['id'];

    // Fetch user details from the database
    $query = "SELECT firstname, lastname, email FROM users WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();   

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Assign values to the variables
        $firstname = $user['firstname'];
        $lastname = $user['lastname'];
        $email = $user['email'];
    } else {
        echo "User not found!";
        exit;
    }
} else {
    echo "User ID is missing!";
    exit;
}

    if (!$user) {
        // User not found
        header("Location: manage-users.php");
        exit();
    }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Update user data
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateQuery = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, password = ? WHERE id = ?");
            $updateQuery->execute([$firstname, $lastname, $email, $hashedPassword, $user_id]);
        } else {
            $updateQuery = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?");
            $updateQuery->execute([$firstname, $lastname, $email, $user_id]);
        }

        // Redirect after updating
        header("Location: manage-users.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="edit-user.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="page-title">Edit User</h2>
        <form id="editForm" action="update-user.php" method="POST">
            <input type="hidden" name="user_id" value="<?= $userId ?>">

            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" value="<?= htmlspecialchars($firstname) ?>" required>

            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" value="<?= htmlspecialchars($lastname) ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

            <label for="password">Password:</label>
            <input type="password" name="password">

            <button type="submit">Save Changes</button>
        </form>
    </div>
    <script>
    document.getElementById('editForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Show SweetAlert confirmation before submitting
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to save the changes?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with form submission if confirmed
                this.submit(); // Submit the form
            }
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>