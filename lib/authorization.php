<?php
// Start the session if not already started
session_start();


// Role-based access control
if ($_SESSION["role"] === "client") {
    // Client should not access admin or super admin pages
    if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false || strpos($_SERVER['REQUEST_URI'], '/super_admin/') !== false) {
        header('Location: ./client/packages/');
        exit();
    }
} else if ($_SESSION["role"] === "admin") {
    // Admin should not access super admin pages
    if (strpos($_SERVER['REQUEST_URI'], '/super_admin/') !== false) {
        header('Location: ../admin/admin_dashboard.php');
        exit();
    }
} else if ($_SESSION["role"] === "super_admin") {
    // Super admin access granted to all sections
    // No redirection needed for super admin
} else {
    // If the role is invalid or not recognized, redirect to login
    header('Location: ../auth/login.php');
    exit();
}
?>
