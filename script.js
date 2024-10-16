// Clock
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString();
    document.getElementById('clock').textContent = timeString;
}

// Update clock every second
setInterval(updateClock, 1000);
updateClock(); // Initial call

// Scrolling notice (if required for notices)
document.addEventListener("DOMContentLoaded", function () {
    const marquee = document.createElement('marquee');
    marquee.textContent = 'Stay tuned for upcoming health tips and workshops!';
    document.body.insertBefore(marquee, document.body.firstChild);
});
