// Function to update the clock and date
function updateClock() {
    const now = new Date();

    // Get time components
    let hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';

    // Convert to 12-hour format
    hours = hours % 12 || 12; // the hour '0' should be '12'

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
    const medicationType = document.getElementById("medication-type").value;
    const syrupInputs = document.getElementById("syrup-inputs");
    const tabletInputs = document.getElementById("tablet-inputs");

    // Toggle visibility based on medication type
    syrupInputs.style.display = medicationType === "syrup" ? "block" : "none";
    tabletInputs.style.display = medicationType === "tablet" ? "block" : "none";
}

// Function to calculate the dose
function calculateDose() {
    const weight = parseFloat(document.getElementById("weight").value);
    const dose = parseFloat(document.getElementById("dose").value);
    const medicationType = document.getElementById("medication-type").value;
    let result = '';

    if (!weight || !dose) {
        result = 'Please enter valid weight and dose values.';
    } else if (medicationType === "syrup") {
        const strength = parseFloat(document.getElementById("strength").value);
        if (strength > 0) {
            const totalDose = weight * dose;
            const volume = (totalDose / strength) * 5; // Assuming strength is in mg/5ml
            result = `Total dose: ${totalDose.toFixed(2)} mg, Volume of syrup to administer: ${volume.toFixed(2)} ml`;
        } else {
            result = 'Please enter a valid syrup strength.';
        }
    } else if (medicationType === "tablet") {
        const tabletDose = parseFloat(document.getElementById("tablet-dose").value);
        if (tabletDose > 0) {
            const totalDose = weight * dose;
            const numberOfTablets = Math.ceil(totalDose / tabletDose);
            result = `Total dose: ${totalDose.toFixed(2)} mg, Number of tablets to administer: ${numberOfTablets}`;
        } else {
            result = 'Please enter a valid tablet dose.';
        }
    } else {
        result = 'Please select a medication type.';
    }

    document.getElementById("result").innerText = result;
}

// Ensure to connect the functions to appropriate HTML events
document.getElementById("medication-type").addEventListener("change", toggleInputs);
document.getElementById("calculate-btn").addEventListener("click", calculateDose);
