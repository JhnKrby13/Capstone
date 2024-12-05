<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mhark Photography</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
            <a href="#events">About</a>
            <a href="#photographers">Photographers</a>
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

    <!-- <section class="about">
        <h1 class="heading" id="about">About Us:</h1>
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>

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

            <button class="carousel-control-prev custom-btn" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <i class="fas fa-chevron-left"></i>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next custom-btn" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <i class="fas fa-chevron-right"></i>
                <span class="visually-hidden">Next</span>
            </button>

        </div>
    </section> -->

    <section id="events" class="events section">
        <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper init-swiper">
                <script type="application/json" class="swiper-config">
                    {
                        "loop": true,
                        "speed": 600,
                        "autoplay": {
                            "delay": 5000
                        },
                        "slidesPerView": "auto",
                        "pagination": {
                            "el": ".swiper-pagination",
                            "type": "bullets",
                            "clickable": true
                        },
                        "breakpoints": {
                            "320": {
                                "slidesPerView": 1,
                                "spaceBetween": 40
                            },
                            "1200": {
                                "slidesPerView": 3,
                                "spaceBetween": 1
                            }
                        }
                    }
                </script>
                <div class="swiper-wrapper">

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/wed3.jpg)">
                        <h3>Full Set Wedding Package</h3>
                        <div class="price align-self-start">₱30,000</div>
                        <p class="description">
                            "Capture every magical moment of your special day with our Full Wedding Package – unlimited shots, premium edits, and timeless memories, all tailored just for you."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/wed4.jpg)">
                        <h3>Wedding Event Package</h3>
                        <div class="price align-self-start">₱15,000</div>
                        <p class="description">
                            "Make your wedding day unforgettable with our Wedding Event Package – professional photography, stunning shots, and beautiful memories to cherish forever."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/wed5.jpg)">
                        <h3>Prenuptial Package</h3>
                        <div class="price align-self-start">₱15,000</div>
                        <p class="description">
                            "Capture the excitement before the big day with our Prenuptial Package – creative shoots, stunning locations, and unforgettable moments to celebrate your love."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/pre1.jpg)">
                        <h3>Full Set Debut Package</h3>
                        <div class="price align-self-start">₱20,000</div>
                        <p class="description">
                            "Celebrate your milestone with our Full Set Debut Package – capturing every magical moment, from the grand entrance to the final dance, with elegance and style."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/pre2.jpg)">
                        <h3>Debut Package</h3>
                        <div class="price align-self-start">₱10,000</div>
                        <p class="description">
                            "Make your debut unforgettable with our Debut Package – stunning photography, beautiful moments, and memories that will last a lifetime."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/pre3.jpg)">
                        <h3>Pre-Debut Package</h3>
                        <div class="price align-self-start">₱10,000</div>
                        <p class="description">
                            "Celebrate the journey to your debut with our Pre-Debut Package – capturing the excitement and elegance leading up to your special day."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/bir1.jpg)">
                        <h3>Full Set Birthday Package</h3>
                        <div class="price align-self-start">₱20,000</div>
                        <p class="description">
                            "Make your birthday unforgettable with our Full Set Birthday Package – capturing every joyful moment, from the cake to the dance floor, with creativity and style."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/bir2.jpg)">
                        <h3>Birthday Package</h3>
                        <div class="price align-self-start">₱10,000</div>
                        <p class="description">
                            "Celebrate your special day with our Birthday Package – vibrant photos that capture the fun, laughter, and memories of your unforgettable celebration."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/bir3.jpg)">
                        <h3>Pre-Birthday Package</h3>
                        <div class="price align-self-start">₱10,000</div>
                        <p class="description">
                            "Capture the excitement before your big day with our Pre-Birthday Package – stunning shots that celebrate the anticipation and joy leading up to your celebration."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/bap1.jpg)">
                        <h3>Full Set Baptism Package</h3>
                        <div class="price align-self-start">₱20,000</div>
                        <p class="description">
                            "Commemorate this sacred occasion with our Full Set Baptism Package – beautiful photos capturing every heartfelt moment of your child’s special day."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/bap2.jpg)">
                        <h3>Baptism Package</h3>
                        <div class="price align-self-start">₱10,000</div>
                        <p class="description">
                            "Celebrate your child’s special day with our Baptism Package – timeless photos capturing the joy, love, and sacred moments of this meaningful event."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/bap3.jpg)">
                        <h3>Pre-Baptism Package</h3>
                        <div class="price align-self-start">₱10,000</div>
                        <p class="description">
                            "Capture the anticipation and joy before the big day with our Pre-Baptism Package – heartfelt moments leading up to your child's special baptism celebration."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/pa1.jpg)">
                        <h3>Full Set Pageants Package</h3>
                        <div class="price align-self-start">₱25,000</div>
                        <p class="description">
                            "Shine bright with our Full Set Pageants Package – professional photos capturing every glamorous moment, from your stunning walk to the crowning achievement."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/pa2.jpg)">
                        <h3>Pageants Package</h3>
                        <div class="price align-self-start">₱15,000</div>
                        <p class="description">
                            "Stand out with our Pageants Package – vibrant and professional photos that capture your elegance, grace, and unforgettable moments on stage."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/pa3.jpg)">
                        <h3>Pre-Pageants Package</h3>
                        <div class="price align-self-start">₱10,000</div>
                        <p class="description">
                            "Prepare for the spotlight with our Pre-Pageants Package – stylish and creative shots that showcase your beauty and confidence before the big event."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/gra2.jpg)">
                        <h3>Full Set Graduation Shoot</h3>
                        <div class="price align-self-start">₱18,000</div>
                        <p class="description">
                            "Celebrate your achievement with our Full Set Graduation Package – capturing every proud moment, from the ceremony to the celebrations, with stunning photos to cherish forever."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/gra3.jpg)">
                        <h3>Graduation Shoot</h3>
                        <div class="price align-self-start">₱10,000</div>
                        <p class="description">
                            "Commemorate your milestone with our Graduation Shoot – professional photos capturing the pride, joy, and excitement of your special achievement."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/gra1.jpg)">
                        <h3>Pre-Graduation Shoot</h3>
                        <div class="price align-self-start">₱8,000</div>
                        <p class="description">
                            "Capture the excitement and anticipation before your big day with our Pre-Graduation Shoot – stunning photos that celebrate your journey to success."
                        </p>
                    </div>

                    <div class="swiper-slide event-item d-flex flex-column justify-content-end" style="background-image: url(./landingpage/image/pro1.jpg)">
                        <h3>Prom Night</h3>
                        <div class="price align-self-start">₱10,000</div>
                        <p class="description">
                            "Make your Prom Night unforgettable with our Prom Night Package – elegant photos that capture the magic, style, and memories of this once-in-a-lifetime event."
                        </p>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
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

    <section class="contact" id="contact">
        <h1 class="heading">Contact</h1>
        <div class="box-container">
            <form id="contact-form" class="hidden" method="post">
                <input style="color: black;" type="text" id="name" name="name" placeholder="Your Name" required>
                <input style="color: black;" type="email" id="email" name="email" placeholder="Your Email" required>
                <textarea style="color: black;" id="message" name="message" placeholder="Your Message" required></textarea>
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.min.js"></script>
    <script src="./landingpage/script.js"></script>
</body>

</html>