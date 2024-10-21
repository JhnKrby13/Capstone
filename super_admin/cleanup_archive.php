<?php
function cleanupOldArchiveRecords() {
    global $pdo;

    // Delete old bookings
    $stmt = $pdo->prepare("DELETE FROM bookings_archive WHERE deleted_at < NOW() - INTERVAL 30 DAY");
    $stmt->execute();

    // Delete old photographers
    $stmt = $pdo->prepare("DELETE FROM photographers_archive WHERE deleted_at < NOW() - INTERVAL 30 DAY");
    $stmt->execute();

    // Delete old packages
    $stmt = $pdo->prepare("DELETE FROM packages_archive WHERE deleted_at < NOW() - INTERVAL 30 DAY");
    $stmt->execute();

    // Delete old clients
    $stmt = $pdo->prepare("DELETE FROM clients_archive WHERE deleted_at < NOW() - INTERVAL 30 DAY");
    $stmt->execute();
}

// Call the function (you can schedule this script to run daily)
cleanupOldArchiveRecords();

?>