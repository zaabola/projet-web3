function login() {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    if (username === "" || password === "") {
        alert("Please fill in both fields.");
        return;
    }

    // This is where you'd send the login request to the server
    alert("Login Successful");
}