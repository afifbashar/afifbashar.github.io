// Initialize AOS
AOS.init({
    duration: 800,
    once: true
});

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Active navigation highlighting
window.addEventListener('scroll', () => {
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.nav-link');
    
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop - 100;
        if (window.scrollY >= sectionTop) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href').substring(1) === current) {
            link.classList.add('active');
        }
    });
});

// Clock and Date function
function updateDateTime() {
    const now = new Date();
    
    // Update clock
    const clock = document.getElementById('clock');
    clock.textContent = now.toLocaleTimeString();
    
    // Update date
    const date = document.getElementById('date');
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    date.textContent = now.toLocaleDateString('en-US', options);
}

// Update time every second
setInterval(updateDateTime, 1000);
// Initial call to avoid delay
updateDateTime();

// Hide preloader when the page is fully loaded
window.addEventListener('load', function() {
    const preloader = document.getElementById('preloader');
    preloader.style.display = 'none';
});


document.addEventListener("DOMContentLoaded", () => {
    const phrases = ["Welcome to My Website", "Expert Medical Care & Compassionate Service"];
    let currentPhraseIndex = 0;
    let currentCharIndex = 0;
    const autoText = document.getElementById("autoText");

    function typeEffect() {
        if (currentCharIndex < phrases[currentPhraseIndex].length) {
            autoText.textContent += phrases[currentPhraseIndex][currentCharIndex];
            currentCharIndex++;
            setTimeout(typeEffect, 100); // Typing speed
        } else {
            setTimeout(eraseEffect, 2000); // Pause before erasing
        }
    }

    function eraseEffect() {
        if (currentCharIndex > 0) {
            autoText.textContent = autoText.textContent.slice(0, -1);
            currentCharIndex--;
            setTimeout(eraseEffect, 50); // Erasing speed
        } else {
            currentPhraseIndex = (currentPhraseIndex + 1) % phrases.length;
            setTimeout(typeEffect, 500); // Pause before typing next phrase
        }
    }

    typeEffect(); // Start the effect
});
