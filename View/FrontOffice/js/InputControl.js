function verifyInputs() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(msg => msg.remove());

    const lastName = document.getElementById('last-name').value;
    const firstName = document.getElementById('first-name').value;
    const email = document.getElementById('mail').value;
    const phone = document.getElementById('tel').value;
    const destination = document.getElementById('destination').value;
    let isValid = true;

    if (!lastName) {
        showError('last-name', 'Remplir champ Nom.');
        isValid = false; 
    }

    if (!firstName) {
        showError('first-name', 'Remplir champ Prenom.');
        isValid = false; 
    }

    if (!email) {
        showError('mail', 'Remplir champ email.');
        isValid = false; 
    }

    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/; 
    if (!emailPattern.test(email)) {
        showError('mail', 'Email est invalide');
        isValid = false; 
    }

    if (phone.length !== 8) {
        showError('tel', 'Le numéro doit comporter exactement 8 chiffres.');
        isValid = false; 
    }

    if (!destination) {
        showError('destination', "Choisir une destination");
        isValid = false;
    }

    return isValid; // Return false to prevent form submission if validation fails
}

function showError(inputId, message) {
    const inputField = document.getElementById(inputId);
    const errorMessage = document.createElement('div');
    errorMessage.className = 'error-message';
    errorMessage.style.color = 'red'; 
    errorMessage.innerText = message;
    inputField.parentNode.insertBefore(errorMessage, inputField);
}
