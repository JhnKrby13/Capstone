<?php
require '../../connection.php'; // Ensure database connection is correctly established
session_start();

// Check if token is present in the URL
if (!isset($_GET['token']) || empty($_GET['token'])) {
    echo "Invalid or missing token!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = htmlspecialchars($_POST['token']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    // Validate passwords
    if ($password != $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute statement to verify the token and reset the password
    $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->execute([$hashed_password, $token]);

    if ($stmt->rowCount() > 0) {
        echo "Password has been reset successfully.";
    } else {
        echo "Invalid or expired token!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="reset_password.css">
</head>
<body>
    <div class="wrapper">
        <form action="reset_password.php" method="post">
            <h2>Reset Password</h2>
            <div class="input-field">
                <input type="password" id="password" name="password" required>
                <label for="password">New Password</label>
            </div>
            <div class="input-field">
                <input type="password" id="confirm_password" name="confirm_password" required>
                <label for="confirm_password">Confirm New Password</label>
            </div>
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <button type="submit">Reset Password</button>
            <div class="login-link">
                <p>Already have an account? <a href="login.php" class="login-btn">Login now</a></p>
            </div>
        </form>
    </div>
</body>
</html>
