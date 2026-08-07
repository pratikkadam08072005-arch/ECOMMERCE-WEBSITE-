// Slider Functionality
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    let currentIndex = 0;
    let interval;

    // Function to change slide
    function changeSlide(index) {
        // Remove active class from all slides
        slides.forEach(slide => {
            slide.classList.remove('active');
        });
        
        // Remove active class from all dots
        dots.forEach(dot => {
            dot.classList.remove('active');
        });
        
        // Add active class to current slide and dot
        currentIndex = index;
        slides[currentIndex].classList.add('active');
        dots[currentIndex].classList.add('active');
    }
    
    // Auto slide change
    function startAutoSlide() {
        interval = setInterval(function() {
            let newIndex = currentIndex + 1;
            if (newIndex >= slides.length) {
                newIndex = 0;
            }
            changeSlide(newIndex);
        }, 5000); // Change slide every 5 seconds
    }
    
    // Initialize auto slide
    startAutoSlide();
    
    // Previous button click
    prevBtn.addEventListener('click', function() {
        clearInterval(interval);
        let newIndex = currentIndex - 1;
        if (newIndex < 0) {
            newIndex = slides.length - 1;
        }
        changeSlide(newIndex);
        startAutoSlide();
    });
    
    // Next button click
    nextBtn.addEventListener('click', function() {
        clearInterval(interval);
        let newIndex = currentIndex + 1;
        if (newIndex >= slides.length) {
            newIndex = 0;
        }
        changeSlide(newIndex);
        startAutoSlide();
    });
    
    // Dot click
    dots.forEach((dot, index) => {
        dot.addEventListener('click', function() {
            clearInterval(interval);
            changeSlide(index);
            startAutoSlide();
        });
    });
    
    // Pause auto slide on hover
    const sliderContainer = document.querySelector('.slider-container');
    sliderContainer.addEventListener('mouseenter', function() {
        clearInterval(interval);
    });
    
    sliderContainer.addEventListener('mouseleave', function() {
        startAutoSlide();
    });
    
    // Handle message alert auto-close
    const messageAlert = document.querySelector('.message-alert');
    if (messageAlert) {
        setTimeout(function() {
            messageAlert.style.opacity = '0';
            messageAlert.style.transition = 'opacity 0.5s ease';
            setTimeout(function() {
                messageAlert.style.display = 'none';
            }, 500);
        }, 5000);
    }
});