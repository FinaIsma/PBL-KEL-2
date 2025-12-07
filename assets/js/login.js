document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const user = document.getElementById("username").value.trim();
    const pass = document.getElementById("password").value.trim();

    if (user === "" || pass === "") {
        alert("Masukkan username dan password.");
        return;
    }

    // Contoh redirect
    window.location.href = "../../index.html";
});

// SHOW / HIDE PASSWORD
const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

togglePassword.addEventListener("click", () => {
    const type = passwordInput.type === "password" ? "text" : "password";
    passwordInput.type = type;

    togglePassword.textContent = 
        type === "password" ? "👁" : "🙈";
});

