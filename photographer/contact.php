<!-- <?php
// PHP to handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $facebook = htmlspecialchars($_POST['facebook']);
    $twitter = htmlspecialchars($_POST['twitter']);
    $instagram = htmlspecialchars($_POST['instagram']);
    $message = htmlspecialchars($_POST['message']);

    // Define the recipient
    $to = 'your-email@example.com'; // Replace with your email address

    // Define the subject
    $subject = 'Contact Form Submission from ' . $name;

    // Define the message
    $emailMessage = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; }
            h1 { text-align: center; }
            .form-group { margin-bottom: 15px; }
            .form-group label { display: block; margin-bottom: 5px; }
            .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
            .form-group textarea { height: 150px; resize: vertical; }
            .form-group button { background-color: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
            .form-group button:hover { background-color: #45a049; }
            .social-links { margin-top: 15px; }
            .social-links a { text-decoration: none; color: #333; margin-right: 10px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Contact Form Submission</h1>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Facebook:</strong> <a href='$facebook' target='_blank'>$facebook</a></p>
            <p><strong>Twitter:</strong> <a href='$twitter' target='_blank'>$twitter</a></p>
            <p><strong>Instagram:</strong> <a href='$instagram' target='_blank'>$instagram</a></p>
            <p><strong>Message:</strong></p>
            <p>$message</p>
        </div>
    </body>
    </html>
    ";

    // Set headers for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // Send email
    if (mail($to, $subject, $emailMessage, $headers)) {
        echo "<p style='text-align:center;'>Thank you for contacting us, $name. We will get back to you soon.</p>";
    } else {
        echo "<p style='text-align:center;'>Sorry, there was an error sending your message. Please try again later.</p>";
    }
}
?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" href="contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <form action="contact_form.php" method="post">
            <h2>Contact Me</h2>
            <div class="input-field">
                <input type="text" id="name" name="name" required>
                <label for="name">Enter your name</label>
            </div>
            <div class="input-field">
                <input type="email" id="email" name="email" required>
                <label for="email">Enter your email</label>
            </div>
            <div class="input-field">
                <input type="text" id="contact" name="contact" required>
                <label for="contact">Enter your contact</label>
            </div>
            <div class="input-field">
                <textarea id="message" name="message" required></textarea>
                <label for="message">Enter your message</label>
            </div>
            <button type="submit">Send Message</button>
            <div class="social-icons">
                <a href="https://www.facebook.com/yourpage" target="_blank" class="social-icon"><i class="fab fa-facebook"></i></a>
                <a href="https://x.com/?lang=en" target="_blank" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/yourpage" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
            </div>
        </form>
    </div>
</body>
</html>