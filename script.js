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
document.getElementById('calculator-form').addEventListener('submit', function(e) {
  e.preventDefault();

  const weight = parseFloat(document.getElementById('weight').value);
  const dosePerKg = parseFloat(document.getElementById('dose-per-kg').value);
  const format = document.getElementById('format').value;

  if (isNaN(weight) || isNaN(dosePerKg) || weight <= 0 || dosePerKg <= 0) {
    document.getElementById('result').innerText = 'Please enter valid numbers.';
    return;
  }

  // Calculate the total dose in mg
  const doseInMg = weight * dosePerKg;

  // Calculate based on the format selected
  let resultText = '';
  if (format === 'tablet') {
    resultText = `The calculated dose is ${doseInMg.toFixed(2)} mg.`;
  } else if (format === 'syrup') {
    // Assume 1 teaspoon (tsp) = 5 mL, convert mg to mL
    const doseInMl = doseInMg / 5;
    resultText = `The calculated dose is ${doseInMg.toFixed(2)} mg, which is approximately ${doseInMl.toFixed(2)} mL (${(doseInMl / 5).toFixed(2)} teaspoons).`;
  }

  document.getElementById('result').innerText = resultText;
});
