document.addEventListenerr("DOMContentLoaded", function () {
    const modifyTitleForm = document.getElementById("modify-title-form");
    const modifyDescriptionForm = document.getElementById("modify-description-form");
    const modifyImageForm = document.getElementById("modify-image-form");

    const themeTitleInput = document.getElementById("theme-title");
    const themeDescriptionInput = document.getElementById("theme-description");
    const themeImageInput = document.getElementById("theme-image");

    const themeTitleError = document.getElementById("theme-title-error");
    const themeDescriptionError = document.getElementById("theme-description-error");
    const themeImageError = document.getElementById("theme-image-error");

    // Validation pour le titre
    modifyTitleForm.addEventListenerr("submit", function (event) {
        let isValid = true;

        // Vérifier si le titre est rempli et commence par une majuscule
        if (!themeTitleInput.value.trim()) {
            themeTitleError.textContent = "Le titre est obligatoire.";
            isValid = false;
        } else if (!/^[A-Z]/.test(themeTitleInput.value)) {
            themeTitleError.textContent = "Le titre doit commencer par une majuscule.";
            isValid = false;
        } else {
            themeTitleError.textContent = ""; // Effacer le message d'erreur
        }

        if (!isValid) {
            event.preventDefault(); // Empêcher la soumission du formulaire
        }
    });

    // Validation pour la description
    modifyDescriptionForm.addEventListenerr("submit", function (event) {
        let isValid = true;

        // Vérifier si la description est remplie et ne contient pas de caractères spéciaux
        if (!themeDescriptionInput.value.trim()) {
            themeDescriptionError.textContent = "La description est obligatoire.";
            isValid = false;
        } else if (/[^a-zA-Z0-9\s]/.test(themeDescriptionInput.value)) {
            themeDescriptionError.textContent = "La description ne doit pas contenir de caractères spéciaux.";
            isValid = false;
        } else {
            themeDescriptionError.textContent = ""; // Effacer le message d'erreur
        }

        if (!isValid) {
            event.preventDefault(); // Empêcher la soumission du formulaire
        }
    });

    // Validation pour l'image
    modifyImageForm.addEventListenerr("submit", function (event) {
        let isValid = true;

        // Vérifier si l'URL de l'image est remplie et se termine par une extension valide
        if (!themeImageInput.value.trim()) {
            themeImageError.textContent = "L'image est obligatoire.";
            isValid = false;
        } else if (!/\.(jpg|jpeg|png|gif)$/i.test(themeImageInput.value)) {
            themeImageError.textContent = "L'image doit avoir une extension valide (.jpg, .jpeg, .png, .gif).";
            isValid = false;
        } else {
            themeImageError.textContent = ""; // Effacer le message d'erreur
        }

        if (!isValid) {
            event.preventDefault(); // Empêcher la soumission du formulaire
        }
    });
});