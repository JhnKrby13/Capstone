<?php
require '../connection.php';

session_start();

// Redirect if not logged in or not an admin
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION["role"] !== "admin") {
    header('Location: ../../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $user_id = $_POST['user_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Update the user data in the database
        if (!empty($password)) {
            // Hash the new password if provided
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateQuery = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, password = ? WHERE id = ?");
            $updateQuery->execute([$firstname, $lastname, $email, $hashedPassword, $user_id]);
        } else {
            // If no password is provided, don't update the password
            $updateQuery = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?");
            $updateQuery->execute([$firstname, $lastname, $email, $user_id]);
        }

        // Redirect back to the manage-users page
        header("Location: manage-users.php");
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// update-user.php
if ($updateSuccess) {
    echo "<script>
        Swal.fire({
            title: 'Success',
            text: 'User details updated!',
            icon: 'success',
            confirmButtonText: 'Ok'
        }).then(() => {
            window.location.href = 'manage-users.php'; // Redirect to the user list page after successful update
        });
    </script>";
} else {
    echo "<script>
        Swal.fire({
            title: 'Error',
            text: 'Failed to update user details. Please try again.',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
    </script>";
}

?>
