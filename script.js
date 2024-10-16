// Function to update the clock and date
function updateClock() {
    const now = new Date();
    
    // Get time components
    let hours = now.getHours();
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    
    // Convert 24-hour to 12-hour format
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    
    const timeString = `${hours}:${minutes}:${seconds} ${ampm}`;
    
    // Get date components
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const dateString = now.toLocaleDateString(undefined, options);
    
    // Update HTML elements
    document.getElementById('clock').textContent = timeString;
    document.getElementById('date').textContent = dateString;
}

// Update the clock every second
setInterval(updateClock, 1000);
updateClock(); // Initial call to display the time immediately

// Scrolling notice (if required for notices)
document.addEventListener("DOMContentLoaded", function () {
    const marquee = document.createElement('marquee');
    marquee.textContent = 'Stay tuned for upcoming health tips and workshops!';
    document.body.insertBefore(marquee, document.body.firstChild);
});
