<?php
require '../connection.php';  // Ensure to include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = intval($_POST['id']);  // Get the user ID from the POST request

    try {
        $pdo->beginTransaction();

        // Move the user to the archive
        $stmt = $pdo->prepare("INSERT INTO user_archive (id, firstname, lastname, address, email, contact, password, role, verification_code, is_verified, reset_token, reset_expires)
                               SELECT id, firstname, lastname, address, email, contact, password, role, verification_code, is_verified, reset_token, reset_expires
                               FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);

        // Delete the user from the users table
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);

        $pdo->commit();
        
        header('Location: manage-users.php?success=1'); // Redirect after successful archive
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: manage-users.php');
    exit;
}
?>
