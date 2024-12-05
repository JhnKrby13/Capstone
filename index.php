<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mhark Photography</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="./landingpage/style.css">
</head>

<body>
    <header class="header">
        <div class="logo">
            <div class="logo-container">

                <img src="./landingpage/image/logo.png" alt="Logo" style="width: 50px; height: auto;">
                <span class="name">Mhark Photography</span>
            </div>
        </div>
        <nav class="navbar">
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="#photographers">Photographers</a>
            <!-- <a href="#services">Services</a> -->
            <a href="#contact">Contact</a>

        </nav>
        <div class="login-buttons">
            <button onclick="location.href='./auth/login.php'">Log In</button>
        </div>
    </header>

    <section class="home" id="home">
        <div class="circle-container">
            <img src="./landingpage/image/1234.jpg" alt="Profile Picture" class="profile-picture">
        </div>
        <div class="content">
            <h3>Mhark Photography</h3>
            <p>Mark's dedication to his craft and commitment to capturing timeless memories has made Mhark Photography a trusted name in the industry.</p>
            <a href="#photographers" class="btn">Explore Our Photographers</a>
        </div>
    </section>

    <section class="about">
        <h1 class="heading" id="about">About Us:</h1>
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <!-- Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>

            <!-- Carousel Items -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="./landingpage/image/jennie.JPG" class="d-block w-100" alt="Jennie">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Jennie</h5>
                        <p>Explore the elegance of Jennie with our photography.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="./landingpage/image/jiso.jpg" class="d-block w-100" alt="Jisoo">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Jisoo</h5>
                        <p>Timeless captures that speak for themselves.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="./landingpage/image/lisa.jpg" class="d-block w-100" alt="Lisa">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Lisa</h5>
                        <p>Artistic photography that highlights beauty.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="./landingpage/image/rose.jpg" class="d-block w-100" alt="Rose">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Rose</h5>
                        <p>Moments captured, memories cherished.</p>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>

        </div>
    </section>


    <section class="photographers">
        <h1 class="heading" id="photographers">Photographers:</h1>
        <div class="swiper project-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide box">
                    <img src="./landingpage/image/photo1.jpg" alt="Photographer 1">
                    <h3>Mark Anthony Maghanoy</h3>
                    <a href="./photographer/photographer.php" class="btn">Details</a>
                </div>
                <div class="swiper-slide box">
                    <img src="./landingpage/image/peter.jpg" alt="Photographer 2">
                    <h3>Peter Ventocilla</h3>
                    <a href="./photographer/photographer2.php" class="btn">Details</a>
                </div>
                <div class="swiper-slide box">
                    <img src="./landingpage/image/lovely.jpg" alt="Photographer 3">
                    <h3>Lovely Ann Joyce Sotomayor</h3>
                    <a href="./photographer/photographer3.php" class="btn">Details</a>
                </div>
            </div>
        </div>
        <div class="swiper project-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide box">
                    <img src="./landingpage/image/jashiee.jpg" alt="Project 1">
                    <h3>Jash Jimenez</h3>
                    <a href="./photographer/photographer4.php" class="btn">Details</a>
                </div>
                <div class="swiper-slide box">
                    <img src="./landingpage/image/manuel.jpg" alt="Project 2">
                    <h3>Manuel Andrade</h3>
                    <a href="./photographer/photographer5.php" class="btn">Details</a>
                </div>
                <div class="swiper-slide box">
                    <img src="./landingpage/image/mark.jpg" alt="Project 3">
                    <h3>Mark Angelo Magalona</h3>
                    <a href="./photographer/photographer6.php" class="btn">Details</a>
                </div>
            </div>
        </div>
    </section>

    <!-- <section class="services">
        <h1 class="heading" id="services">Services</h1>
        <div class="pack-heading">
        <h2>Packages</h2>
        </div>
        <div class="pre-birthday">
            <a href="#home" class="links"> <i class="fas fa-arrow-right"></i> Pre-Birthday</a>
            <a href="#home" class="links"> <i class="fas fa-arrow-right"></i> Wedding</a>
            <a href="#home" class="links"> <i class="fas fa-arrow-right"></i> Debut</a>
            <a href="#home" class="links"> <i class="fas fa-arrow-right"></i> Baptismal</a>
            <a href="#home" class="links"> <i class="fas fa-arrow-right"></i> Pageants</a>
            <a href="#home" class="links"> <i class="fas fa-arrow-right"></i> Photoshoot</a>
        </div>
        <div class="container">
      <div class="wrapper">
        <header>
          <p class="current-date"></p>
          <div class="icons">
            <span id="prev" class="material-symbols-rounded">chevron_left</span>
            <span id="next" class="material-symbols-rounded">chevron_right</span>
          </div>
        </header>
        <div class="calendar">
          <ul class="weeks">
            <li>Sun</li>
            <li>Mon</li>
            <li>Tue</li>
            <li>Wed</li>
            <li>Thu</li>
            <li>Fri</li>
            <li>Sat</li>
          </ul>
          <ul class="days"></ul>
        </div>
        <div class="time-picker">
          <label for="time">Select Time:</label>
          <input type="time" id="time" name="time">
        </div>
        <button class="book-button"><a href="../auth/login.php">Book Appointment</a></button>
      </div>
    </div>
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <img id="lightbox-img" src="" alt="Enlarged Experience">
    </div>
    </section> -->

    <section class="contact" id="contact">
        <h1 class="heading">Contact</h1>
        <div class="box-container">
            <form id="contact-form" class="hidden" method="post">
                <input type="text" id="name" name="name" placeholder="Your Name" required>
                <input type="email" id="email" name="email" placeholder="Your Email" required>
                <textarea id="message" name="message" placeholder="Your Message" required></textarea>
                <button type="submit">Send Message</button>
                <div id="success-message" class="success-message">Message sent successfully!</div>
            </form>
        </div>
    </section>

    <section class="footer">
        <div class="box-container">
            <div class="box">
                <a href="#" class="logo">
                    <div class="logo-container">
                        <img src="./landingpage/image/logo.png" alt="Logo" style="width: 60px; height: auto;">
                        <h3>Mhark Photography</h3>
                    </div>
                </a>
                <div class="share">
                    <a href="https://www.facebook.com/mharkphotographycallmenow" class="fab fa-facebook-f"></a>
                    <a href="https://x.com/MharkPhoto" class="fab fa-twitter"></a>
                    <a href="https://www.instagram.com/mharkphotography" class="fab fa-instagram"></a>
                </div>
            </div>

            <div class="box">
                <h3>Contact Info</h3>
                <a href="#contact" class="links"> <i class="fas fa-phone"></i> SMART: 0930 144 8881 / GLOBE: 0915 301 6400</a>
                <a href="https://www.google.com/gmail/about/" class="links"> <i class="fas fa-envelope"></i>mharkphotography01@gmail.com</a>
                <a href="https://www.google.com.ph/maps/@13.9268908,121.6121004,3a,75y,218.67h,95.43t/data=!3m7!1e1!3m5!1sPE6gwktjDJ_2MQTqtEpfEA!2e0!6shttps:%2F%2Fstreetviewpixels-pa.googleapis.com%2Fv1%2Fthumbnail%3Fpanoid%3DPE6gwktjDJ_2MQTqtEpfEA%26cb_client%3Dsearch.gws-prod%26w%3D211%26h%3D120%26yaw%3D71.48622%26pitch%3D0%26thumbfov%3D100!7i16384!8i8192" class="links"> <i class="fas fa-map-marker-alt"></i> Zone 4 Poblacion Tiaong Quezon Province</a>
            </div>

            <div class="box">
                <h3>Quick Links</h3>
                <a href="#home" class="links"> <i class="fas fa-arrow-right"></i> Home</a>
                <a href="#about" class="links"> <i class="fas fa-arrow-right"></i> About</a>
                <a href="#photographers" class="links"> <i class="fas fa-arrow-right"></i> Photographers</a>
                <!-- <a href="#services" class="links"> <i class="fas fa-arrow-right"></i> Services</a> -->
            </div>
        </div>
        <div class="credit">© 2024 Mhark Photography. All Rights Reserved.</div>
        </div>
    </section>

    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./landingpage/script.js"></script>
</body>

</html>