<?php
require '../connection.php';
session_start();

if (isset($_GET['code'])) {
    $verification_code = htmlspecialchars($_GET['code']);

    $sql = "SELECT * FROM users WHERE verification_code = :verification_code";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':verification_code', $verification_code);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $sql = "UPDATE users SET is_verified = 1, verification_code = NULL WHERE verification_code = :verification_code";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':verification_code', $verification_code);

        if ($statement->execute()) {
            $_SESSION['message'] = "Your email has been verified. You can now log in.";
            
            header('Location: ../auth/login.php');
            exit;
        } else {
            echo "An error occurred during verification.";
        }
    } else {
        echo "Invalid verification code or email already verified.";
    }
} else {
    echo "Verification code is missing.";
}
?>
