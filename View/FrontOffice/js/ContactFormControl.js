document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.custom-form');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const messageInput = document.getElementById('message');

    form.addEventListener('submit', (event) => {
        // Remove previous error messages
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(msg => msg.remove());

        let isValid = true;

        // Name Validation
        if (!nameInput.value.trim()) {
            showError('name', 'Remplir champ Nom.');
            isValid = false;
        }

        // Email Validation
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailInput.value.trim()) {
            showError('email', 'champ Email est vide.');
            isValid = false;
        } else if (!emailPattern.test(emailInput.value.trim())) {
            showError('email', 'Email doit respecter la forme exemple@exemple.com');
            isValid = false;
        }

        // Message Validation
        if (!messageInput.value.trim()) {
            showError('message', 'Remplir champ Message.');
            isValid = false;
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });

    function showError(inputId, message) {
        const inputField = document.getElementById(inputId);
        const errorMessage = document.createElement('div');
        errorMessage.className = 'error-message';
        errorMessage.style.color = 'red';
        errorMessage.innerText = message;
        inputField.parentNode.insertBefore(errorMessage, inputField.nextSibling);
    }
});
