<?php
require '../connection.php'; 

session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

if ($_SESSION["role"] !== "admin") {
    header('Location: ../client/packages'); 
    exit;
}

try {
    $stmt = $pdo->query("SELECT * FROM photographer_archive");
    $archivedPhotographers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if (isset($_GET['restore'])) {
    $id = $_GET['restore'];
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("INSERT INTO photographers (id, name, email, contact, address)
                               SELECT id, name, email, contact, address
                               FROM photographer_archive WHERE id = ?");
        $stmt->execute([$id]);

        // Delete from the photographer_archive table
        $stmt = $pdo->prepare("DELETE FROM photographer_archive WHERE id = ?");
        $stmt->execute([$id]);

        // Commit the transaction
        $pdo->commit();
        header('Location: photographer_archive.php'); // Redirect back to the archive page
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

// Permanently delete photographer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        // Delete photographer permanently from the archive
        $stmt = $pdo->prepare("DELETE FROM photographer_archive WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: photographer_archive.php'); // Redirect back to the archive page
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>