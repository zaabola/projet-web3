document.addEventListener("DOMContentLoaded", function () {
    const themeForm = document.getElementById("theme-form");
    const themeNameInput = document.getElementById("theme-name");
    const themeDescriptionInput = document.getElementById("theme-description");
    const themeImageInput = document.getElementById("theme-image");

    const themeNameError = document.getElementById("theme-name-error");
    const themeDescriptionError = document.getElementById("theme-description-error");
    const themeImageError = document.getElementById("theme-image-error");

    // Créer un message au-dessus du champ "Nom du Thème"
    const message = document.createElement('div');
    message.style.color = 'blue'; // Couleur du message
    message.style.marginBottom = '5px'; // Espacement sous le message

    // Insérer le message juste au-dessus du champ
    themeNameInput.parentNode.insertBefore(message, themeNameInput);

    themeForm.addEventListener("submit", function (event) {
        let isValid = true;

        // Vérifier si le nom du thème est rempli et commence par une majuscule
        if (!themeNameInput.value.trim()) {
            themeNameError.textContent = "Le nom du thème est obligatoire.";
            isValid = false;
        } else if (!/^[A-Z]/.test(themeNameInput.value)) {
            themeNameError.textContent = "Le titre doit commencer par une majuscule.";
            isValid = false;
        } else {
            themeNameError.textContent = "";
        }

        // Vérifier si la description est remplie et ne contient pas de caractères spéciaux
        if (!themeDescriptionInput.value.trim()) {
            themeDescriptionError.textContent = "La description est obligatoire.";
            isValid = false;
        } else if (/[^a-zA-Z0-9\s]/.test(themeDescriptionInput.value)) {
            themeDescriptionError.textContent = "La description ne doit pas contenir de caractères spéciaux.";
            isValid = false;
        } else {
            themeDescriptionError.textContent = "";
        }

        // Vérifier si l'URL de l'image est remplie et se termine par une extension valide
        if (!themeImageInput.value.trim()) {
            themeImageError.textContent = "L'image est obligatoire.";
            isValid = false;
        } else if (!/\.(jpg|jpeg|png|gif)$/i.test(themeImageInput.value)) {
            themeImageError.textContent = "L'image doit avoir une extension valide (.jpg, .jpeg, .png, .gif).";
            isValid = false;
        } else {
            themeImageError.textContent = "";
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
});
