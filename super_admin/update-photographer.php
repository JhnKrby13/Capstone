<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    $updateQuery = $pdo->prepare("UPDATE photographers SET name = ?, email = ?, contact = ?, address = ? WHERE id = ?");
    $updateQuery->execute([$name, $email, $contact, $address, $id]);

    header("Location: manage-photographer.php"); // Redirect back to photographer management page
    exit();
}
?>
