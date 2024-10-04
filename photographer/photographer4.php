<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photographer Profile</title>
    <link rel="stylesheet" href="photographer4.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="profile-container">
        <img src="image/logo.png" alt="Logo" class="logo">
        <h1>Photographer</h1>
        <div class="profile">
            <div class="profile-image">
                <img src="image/photo1.jpg" alt="Profile Picture">
            </div>
            <div class="profile-details">s
                <p><strong>Name:</strong> Mark Anthony Maghanoy</p>
                <p><strong>Address:</strong> 78A Pnr Comp Purok Bago Masagana Cotta Lucena City</p>
                <p><strong>Contact:</strong> 09153016400</p>
                <h2>About Me</h2>
                <p>A bio gives your audience an idea of who you are, your education, experience, and your inspiration and/or motivation for being a photographer. A bio should describe your specialties and an overall aesthetic of your work while including a list of current and previous clients.</p>
                <button onclick="contactMe()" id="contact"><a href="contact.php">Contact Me</a></button>
                <h2>Experiences:</h2>
                <div class="carousel">
                    <button class="carousel-button left" onclick="moveCarousel(-1)">&#10094;</button>
                    <div class="carousel-images">
                        <img src="image/pic8.jpg" alt="Experience 8"> <!-- Clone of the last image -->
                        <img src="image/pic1.jpg" alt="Experience 1">
                        <img src="image/pic2.jpg" alt="Experience 2">
                        <img src="image/pic3.jpg" alt="Experience 3">
                        <img src="image/pic4.jpg" alt="Experience 4">
                        <img src="image/pic5.jpg" alt="Experience 5">
                        <img src="image/pic6.jpg" alt="Experience 6">
                        <img src="image/pic7.jpg" alt="Experience 7">
                        <img src="image/pic8.jpg" alt="Experience 8">
                        <img src="image/pic1.jpg" alt="Experience 1"> <!-- Clone of the first image -->
                    </div>
                    <button class="carousel-button right" onclick="moveCarousel(1)">&#10095;</button>
                    <div class="carousel-indicators">
                        <span class="dot" onclick="currentSlide(1)"></span>
                        <span class="dot" onclick="currentSlide(2)"></span>
                        <span class="dot" onclick="currentSlide(3)"></span>
                        <span class="dot" onclick="currentSlide(4)"></span>
                        <span class="dot" onclick="currentSlide(5)"></span>
                        <span class="dot" onclick="currentSlide(6)"></span>
                        <span class="dot" onclick="currentSlide(7)"></span>
                        <span class="dot" onclick="currentSlide(8)"></span>
                    </div>
                </div>
            </div>
        </div>
        <div id="lightbox" class="lightbox" onclick="closeLightbox()">
            <img id="lightbox-img" src="" alt="Enlarged Experience">
        </div>
    </div>
    <script src="photographer.js"></script>
</body>
</html>
