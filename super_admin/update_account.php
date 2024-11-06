<?php
require '../connection.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($username) || empty($password) || $password !== $confirmPassword) {
        echo "Please complete all fields and ensure passwords match.";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET username = :username, password = :password WHERE id = :id");
    $stmt->execute(['username' => $username, 'password' => $hashedPassword, 'id' => $userId]);

    echo "Account settings updated successfully.";
} else {
    header("Location: system-settings.php");
}
?>
