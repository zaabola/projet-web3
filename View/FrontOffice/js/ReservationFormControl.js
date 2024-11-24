function verifyInputs() {
    // Clear all previous error messages
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(msg => msg.remove());

    // Get form field values
    const lastName = document.getElementById('last-name').value.trim();
    const firstName = document.getElementById('first-name').value.trim();
    const email = document.getElementById('mail').value.trim();
    const phone = document.getElementById('tel').value.trim();
    const destination = document.getElementById('destination').value;

    let isValid = true;

    // Validation for Last Name
    if (!lastName) {
        showError('last-name', 'Remplir champ Nom');
        isValid = false;
    }

    // Validation for First Name
    if (!firstName) {
        showError('first-name', 'Remplir champ Prénom');
        isValid = false;
    }

    // Validation for Email
    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    if (!email) {
        showError('mail', 'Champ Email est vide');
        isValid = false;
    } else if (!emailPattern.test(email)) {
        showError('mail', 'Email doit respecter la forme exemple@exemple.com');
        isValid = false;
    }

    // Validation for Phone Number
    if (!/^\d{8}$/.test(phone)) {
        showError('tel', 'Le numéro doit comporter exactement 8 chiffres');
        isValid = false;
    }

    // Validation for Destination
    if (!destination) {
        showError('destination', 'Choisir une destination');
        isValid = false;
    }

    // Update hidden input for form validation status
    document.getElementById('formValid').value = isValid ? 'true' : 'false';

    return isValid; // Return false to prevent form submission if validation fails
}

function showError(inputId, message) {
    // Select the input field
    const inputField = document.getElementById(inputId);

    // Remove existing error message for this field, if any
    const existingError = inputField.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }

    // Create a new error message
    const errorMessage = document.createElement('div');
    errorMessage.className = 'error-message';
    errorMessage.style.color = 'red';
    errorMessage.innerText = message;

    // Insert the error message above the input field
    inputField.parentNode.insertBefore(errorMessage, inputField);
}