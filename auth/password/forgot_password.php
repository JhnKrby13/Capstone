<?php
require '../../vendor/autoload.php'; // Ensure PHPMailer is autoloaded
require '../../connection.php';
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $token = bin2hex(random_bytes(50)); // Generate a unique token

    // Prepare and execute statement to check if the email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        // Store the token and expiry in the database
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
        $stmt->execute([$token, $email]);

        // Create a reset link
        $reset_link = "http://localhost/FinalWebsite/auth/password/reset_password.php?token=" . $token;

        // Send email using PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'haojohnkirby@gmail.com'; // SMTP username
        $mail->Password = 'vvxc rjwe eveb bqbn'; // SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );


        $mail->setFrom('haojohnkirby@gmail.com', 'Mhark Photography');
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = "Click on the link to reset your password: " . $reset_link;
        $mail->isHTML(true);

        if ($mail->send()) {
            echo "A password reset link has been sent to your email.";
        } else {
            echo "Failed to send the password reset link.";
        }
    } else {
        echo "No user found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="forgot_password.css">
</head>

<body>
    <div class="wrapper">
        <form action="forgot_password.php" method="POST">
            <h2>Forgot Password</h2>
            <div class="input-field">
                <input type="email" id="email" name="email" required>
                <label for="email">Enter your Email Address</label>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>

</html>