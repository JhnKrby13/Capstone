<?php
require '../connection.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $contactEmail = filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL);
    $phone = trim($_POST['phone']);
    $address = htmlspecialchars(trim($_POST['address']));

    if (!$contactEmail || empty($phone) || empty($address)) {
        echo "Please provide valid contact information.";
        exit;
    }

    $stmt = $pdo->prepare("UPDATE users SET contact_email = :contact_email, phone = :phone, address = :address WHERE id = :id");
    $stmt->execute(['contact_email' => $contactEmail, 'phone' => $phone, 'address' => $address, 'id' => $userId]);

    echo "Contact information updated successfully.";
} else {
    header("Location: system-settings.php");
}
?>
