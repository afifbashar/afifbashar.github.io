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

// Function to create a scrolling notice
function createScrollingNotice() {
    const noticeText = 'Stay tuned for our upcoming health tips and workshops!';
    const noticeContainer = document.createElement('div');
    noticeContainer.classList.add('notice'); // Add a class for styling

    const marquee = document.createElement('marquee');
    marquee.textContent = noticeText;

    noticeContainer.appendChild(marquee);
    document.getElementById('notice').appendChild(noticeContainer);
}

// Function to calculate pediatric syrup dosage
function calculateDose() {
    // Get input values
    const weight = parseFloat(document.getElementById('weight').value);
    const dosePerKg = parseFloat(document.getElementById('dose').value);
    const syrupStrength = parseFloat(document.getElementById('strength').value); // mg per 5 ml

    // Validate inputs
    if (isNaN(weight) || isNaN(dosePerKg) || isNaN(syrupStrength)) {
        document.getElementById('result').innerHTML = "Please enter valid numbers.";
        return;
    }

    // Ensure the inputs are positive numbers
    if (weight <= 0 || dosePerKg <= 0 || syrupStrength <= 0) {
        document.getElementById('result').innerHTML = "Please enter positive values.";
        return;
    }

    // Calculate total dose in mg
    const totalDoseMg = weight * dosePerKg;

    // Calculate how many ml of syrup are needed
    const mlNeeded = (totalDoseMg / syrupStrength) * 5;

    // Convert ml to teaspoons (1 tsf = 5 ml)
    const teaspoons = mlNeeded / 5;

    // Display result with proper formatting
    document.getElementById('result').innerHTML = `
        Total dose: <strong>${totalDoseMg.toFixed(2)} mg</strong><br>
        Amount of syrup: <strong>${mlNeeded.toFixed(2)} ml (${teaspoons.toFixed(2)} tsf)</strong>
    `;
}
