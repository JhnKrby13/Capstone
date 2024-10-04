let currentIndex = 1; // Start at the first real image

function moveCarousel(direction) {
    const carouselImages = document.querySelector('.carousel-images');
    const totalImages = carouselImages.querySelectorAll('img').length;
    currentIndex += direction;

    // Ensure the carousel wraps around
    if (currentIndex < 1) {
        currentIndex = totalImages - 2; // Last real image
    } else if (currentIndex > totalImages - 2) {
        currentIndex = 1; // First real image
    }

    // Move to the correct slide
    const imageWidthPercentage = 100 / (totalImages - 2); // Exclude cloned images
    const translateX = -currentIndex * imageWidthPercentage;
    carouselImages.style.transform = `translateX(${translateX}%)`;

    // Update dot indicators
    updateDots();

    // Handle the seamless loop using a transition end event
    carouselImages.addEventListener('transitionend', transitionHandler, { once: true });
}

function transitionHandler() {
    const carouselImages = document.querySelector('.carousel-images');
    const totalImages = carouselImages.querySelectorAll('img').length;

    if (currentIndex === 0) {
        carouselImages.style.transition = 'none';
        currentIndex = totalImages - 3; // Set to last real image
        const translateX = -currentIndex * (100 / (totalImages - 2));
        carouselImages.style.transform = `translateX(${translateX}%)`;
    } else if (currentIndex === totalImages - 1) {
        carouselImages.style.transition = 'none';
        currentIndex = 1; // Set to first real image
        const translateX = -currentIndex * (100 / (totalImages - 2));
        carouselImages.style.transform = `translateX(${translateX}%)`;
    }

    // Re-enable the transition
    setTimeout(() => {
        carouselImages.style.transition = 'transform 0.5s ease-in-out';
    }, 0);
}

function updateDots() {
    const dots = document.querySelectorAll('.dot');
    dots.forEach((dot, index) => {
        if (index === currentIndex - 1) { // Adjust for zero-based index
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
}

function enlargeImage(event) {
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    lightboxImg.src = event.target.src;
    lightbox.classList.add('show');
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.remove('show');
}
s
// Add event listeners to carousel images
document.addEventListener('DOMContentLoaded', () => {
    const carouselImages = document.querySelector('.carousel-images');
    const totalImages = carouselImages.querySelectorAll('img').length;
    const imageWidthPercentage = 100 / (totalImages - 2); // Exclude cloned images
    const translateX = -currentIndex * imageWidthPercentage;
    carouselImages.style.transform = `translateX(${translateX}%)`;

    const images = carouselImages.querySelectorAll('img');
    images.forEach((image, index) => {
        image.addEventListener('click', enlargeImage);
    });

    // Initialize dot indicators
    updateDots();
});

function currentSlide(slideIndex) {
    currentIndex = slideIndex;
    const carouselImages = document.querySelector('.carousel-images');
    const imageWidthPercentage = 100 / (carouselImages.querySelectorAll('img').length - 2); // Exclude cloned images
    const translateX = -currentIndex * imageWidthPercentage;
    carouselImages.style.transform = `translateX(${translateX}%)`;

    updateDots();
}
