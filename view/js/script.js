function validateName() {
    const name = document.getElementById("name").value;
    const nameMessage = document.getElementById("nameMessage");
    if (name.length >= 5) {
        nameMessage.textContent = "Valid name.";
        nameMessage.classList.add("valid");
        nameMessage.classList.remove("invalid");
    } else {
        nameMessage.textContent = "Please enter a valid name.";
        nameMessage.classList.add("invalid");
        nameMessage.classList.remove("valid");
    }
}

function validateLastName() {
    const lastName = document.getElementById("lastname").value;
    const lastNameMessage = document.getElementById("lastNameMessage");
    if (lastName.length >= 5) {
        lastNameMessage.textContent = "Valid last name.";
        lastNameMessage.classList.add("valid");
        lastNameMessage.classList.remove("invalid");
    } else {
        lastNameMessage.textContent = "Please enter a valid last name.";
        lastNameMessage.classList.add("invalid");
        lastNameMessage.classList.remove("valid");
    }
}

function validateEmail() {
    const email = document.getElementById("email").value;
    const emailMessage = document.getElementById("emailMessage");
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailRegex.test(email)) {
        emailMessage.textContent = "Valid email.";
        emailMessage.classList.add("valid");
        emailMessage.classList.remove("invalid");
    } else {
        emailMessage.textContent = "Please enter a valid email.";
        emailMessage.classList.add("invalid");
        emailMessage.classList.remove("valid");
    }
}

function validatePassword() {
    const password = document.getElementById("password").value;
    const passwordMessage = document.getElementById("passwordMessage");
    if (password.length >= 8) {
        passwordMessage.textContent = "Strong password.";
        passwordMessage.classList.add("valid");
        passwordMessage.classList.remove("invalid");
    } else {
        passwordMessage.textContent = "Password must be at least 8 characters long.";
        passwordMessage.classList.add("invalid");
        passwordMessage.classList.remove("valid");
    }
}

function validateConfirmPassword() {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const confirmPasswordMessage = document.getElementById("confirmPasswordMessage");
    if (confirmPassword === password && confirmPassword.length >= 8) {
        confirmPasswordMessage.textContent = "Passwords match.";
        confirmPasswordMessage.classList.add("valid");
        confirmPasswordMessage.classList.remove("invalid");
    } else {
        confirmPasswordMessage.textContent = "Passwords must match.";
        confirmPasswordMessage.classList.add("invalid");
        confirmPasswordMessage.classList.remove("valid");
    }
}

function validatePhone() {
    const phone = document.getElementById("phone").value;
    const phoneMessage = document.getElementById("phoneMessage");
    const phoneRegex = /^\d{3}-\d{3}-\d{4}$/;
    if (phoneRegex.test(phone)) {
        phoneMessage.textContent = "Valid phone number.";
        phoneMessage.classList.add("valid");
        phoneMessage.classList.remove("invalid");
    } else {
        phoneMessage.textContent = "Please enter a valid phone number (e.g., 123-456-7890).";
        phoneMessage.classList.add("invalid");
        phoneMessage.classList.remove("valid");
    }
}

function signup() {
    validateName();
    validateLastName();
    validateEmail();
    validatePassword();
    validateConfirmPassword();
    validatePhone();

    const allValid = document.querySelectorAll(".validation-message.valid").length === 6;
    if (allValid) {
        alert("Sign Up Successful");
    } else {
        alert("Please fill in all fields correctly.");
    }
}