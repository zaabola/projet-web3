// Function to validate the form
function validateForm(event) {
    event.preventDefault(); // Prevent form submission to check validity

    let valid = true;
    let errorMessage = "";
    
    // Get form values
    const name = document.querySelector('input[name="booking-form-name"]').value;
    const email = document.querySelector('input[name="booking-form-email"]').value;
    const amount = document.querySelector('input[name="booking-form-number"]').value;
    const message = document.querySelector('textarea[name="booking-form-message"]').value;

    // Clear previous error messages
    document.querySelectorAll('.validation-message').forEach(messageElement => {
        messageElement.textContent = "";
    });

    // Validate name (should not be empty)
    if (!name) {
        errorMessage = "Le nom est requis.";
        document.querySelector('input[name="booking-form-name"]').nextElementSibling.textContent = errorMessage;
        valid = false;
    }

    // Validate email (should be a valid email format)
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email || !emailPattern.test(email)) {
        errorMessage = "Veuillez entrer un email valide.";
        document.querySelector('input[name="booking-form-email"]').nextElementSibling.textContent = errorMessage;
        valid = false;
    }

    // Validate donation amount (should be a positive number)
    if (!amount || isNaN(amount) || amount <= 0) {
        errorMessage = "Le montant doit être un nombre positif.";
        document.querySelector('input[name="booking-form-number"]').nextElementSibling.textContent = errorMessage;
        valid = false;
    }

    // If the form is valid, proceed with the submission
    if (valid) {
        // Show success message
        document.getElementById('success-message').textContent = "Votre donation a été enregistrée avec succès !";
        document.getElementById('error-message').textContent = "";
        // Simulate form submission to the server (you can replace this with an AJAX request)
        setTimeout(() => {
            alert('Form submitted successfully!');
            // Uncomment the line below to actually submit the form
            // document.querySelector('form').submit();
        }, 1000);
    }
}
