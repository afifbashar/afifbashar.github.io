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
    const syrupInputs = document.getElementById('syrup-inputs');
    const tabletInputs = document.getElementById('tablet-inputs');
    
    // Show syrup inputs if syrup is selected; otherwise, show tablet inputs
    if (document.getElementById('syrup').checked) {
        syrupInputs.style.display = 'block';
        tabletInputs.style.display = 'none';
    } else {
        syrupInputs.style.display = 'none';
        tabletInputs.style.display = 'block';
    }
}

function calculateDose() {
    // Get input values
    const weight = parseFloat(document.getElementById('weight').value);
    const medicationType = document.querySelector('input[name="medication-type"]:checked').value;

    let totalDoseMg = 0;
    let mlNeeded = 0;

    if (medicationType === 'syrup') {
        const dosePerKg = parseFloat(document.getElementById('dose').value);
        const syrupStrength = document.getElementById('strength').value; // e.g., "120 mg/5 ml"
        
        // Extract mg and ml from strength input
        const [mgPart, mlPart] = syrupStrength.split('/');
        const strengthMg = parseFloat(mgPart); // e.g., 120
        const strengthMl = parseFloat(mlPart); // e.g., 5

        // Calculate total dose in mg
        totalDoseMg = weight * dosePerKg;

        // Calculate how many ml of syrup needed
        mlNeeded = (totalDoseMg / strengthMg) * strengthMl;

        // Display result for syrup
        document.getElementById('result').innerHTML = `
            Total dose: <strong>${totalDoseMg.toFixed(2)} mg</strong><br>
            Amount of syrup: <strong>${mlNeeded.toFixed(2)} ml</strong>
        `;
        
    } else {
        const tabletDose = parseFloat(document.getElementById('tablet-dose').value);
        
        // Total dose is directly the tablet dose
        totalDoseMg = tabletDose;

        // Display result for tablets
        document.getElementById('result').innerHTML = `
            Total dose: <strong>${totalDoseMg.toFixed(2)} mg</strong>
        `;
    }
}
