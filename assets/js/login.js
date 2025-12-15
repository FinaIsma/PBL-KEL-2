document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("loginForm");
    const username = form.querySelector("input[name='username']");
    const password = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    const icon = togglePassword.querySelector("i");

    // VALIDASI FORM
    form.addEventListener("submit", function (e) {
        if (username.value.trim() === "" || password.value.trim() === "") {
            e.preventDefault();
            alert("Username dan password wajib diisi.");
        }
    });

    // SHOW / HIDE PASSWORD
    togglePassword.addEventListener("click", function () {
        const show = password.type === "password";
        password.type = show ? "text" : "password";
        icon.className = show
            ? "fa-solid fa-eye-slash"
            : "fa-solid fa-eye";
    });

});
