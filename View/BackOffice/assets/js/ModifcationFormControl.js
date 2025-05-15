function verifyInputs() {
    // Clear all previous error messages
    const errorBanner = document.getElementById('error-banner');
    if (errorBanner) errorBanner.remove();

    // Get form field values
    const lastName = document.getElementById('last-name').value.trim();
    const firstName = document.getElementById('first-name').value.trim();
    const email = document.getElementById('mail').value.trim();
    const phone = document.getElementById('tel').value.trim();
    const destination = document.getElementById('destination').value;

    let isValid = true;
    let errorMessages = [];

    // Validation for Last Name
    if (!lastName) {
        errorMessages.push('Remplir champ Nom');
        isValid = false;
    }

    // Validation for First Name
    if (!firstName) {
        errorMessages.push('Remplir champ Prénom');
        isValid = false;
    }

    // Validation for Email
    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    if (!email) {
        errorMessages.push('Remplir champ Email');
        isValid = false;
    } else if (!emailPattern.test(email)) {
        errorMessages.push('Email doit respecter la forme exemple@exemple.com');
        isValid = false;
    }

    // Validation for Phone Number
    if (!/^\d{8}$/.test(phone)) {
        errorMessages.push('Le numéro doit comporter exactement 8 chiffres');
        isValid = false;
    }

    // Validation for Destination
    if (!destination) {
        errorMessages.push('Choisir une destination');
        isValid = false;
    }

    // Display errors in a red banner if any
    if (!isValid) {
        const banner = document.createElement('div');
        banner.id = 'error-banner';
        banner.className = 'alert alert-danger';
        banner.style.marginBottom = '20px';
        banner.innerHTML = errorMessages.join('<br>');
        const form = document.querySelector('form');
        form.parentNode.insertBefore(banner, form);
    }

    return isValid; // Prevent form submission if validation fails
}
