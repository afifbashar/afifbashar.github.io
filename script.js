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

// Function to toggle input fields based on medication type selection
function toggleInputs() {
    const syrupStrengthContainer = document.getElementById('syrup-strength-container');
    const medicationType = document.getElementById('medication-type').value;
    
    // Show syrup strength input if syrup is selected; otherwise, hide it
    syrupStrengthContainer.style.display = medicationType === 'syrup' ? 'block' : 'none';
}

function calculateDose() {
    // Get input values
    const weight = parseFloat(document.getElementById('weight').value);
    const dosePerKg = parseFloat(document.getElementById('dose').value);
    const medicationType = document.getElementById('medication-type').value;
    let totalDoseMg = 0;

    // Validate inputs
    if (isNaN(weight) || isNaN(dosePerKg)) {
        document.getElementById('result').innerHTML = "Please enter valid numbers for weight and dose.";
        return;
    }

    // Calculate total dose in mg
    totalDoseMg = weight * dosePerKg;

    if (medicationType === 'syrup') {
        const syrupStrength = parseFloat(document.getElementById('strength').value); // mg per 5 ml

        // Validate syrup strength input
        if (isNaN(syrupStrength) || syrupStrength <= 0) {
            document.getElementById('result').innerHTML = "Please enter a valid syrup strength.";
            return;
        }

        // Calculate how many ml of syrup needed
        const mlNeeded = (totalDoseMg / syrupStrength) * 5; // Calculate ml needed based on strength

        // Convert ml to tsf (1 tsf = 5 ml)
        const teaspoons = mlNeeded / 5;

        // Display result for syrup
        document.getElementById('result').innerHTML = `
            Total dose: <strong>${totalDoseMg.toFixed(2)} mg</strong><br>
            Amount of syrup: <strong>${mlNeeded.toFixed(2)} ml (${teaspoons.toFixed(2)} tsf)</strong>
        `;
    } else {
        // Display result for tablets
        document.getElementById('result').innerHTML = `
            Total dose: <strong>${totalDoseMg.toFixed(2)} mg</strong>
        `;
    }
}
