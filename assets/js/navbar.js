document.addEventListener("DOMContentLoaded", () => {
    fetch("navbar.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("navbar-placeholder").innerHTML = data;

            const currentPage = window.location.pathname.split("/").pop();
            document.querySelectorAll(".nav-menu a").forEach(link => {
                if (link.getAttribute("href") === currentPage) {
                    link.classList.add("active");
                }
            });

            const navToggle = document.getElementById("navToggle");
            const navMenu = document.querySelector(".nav-menu");

            navToggle.addEventListener("click", () => {
                navMenu.classList.toggle("active");
            });

            document.querySelectorAll(".dropdown-btn").forEach(btn => {
                btn.addEventListener("click", () => {
                    btn.parentElement.classList.toggle("active");
                });
            });

            const navbar = document.querySelector(".navbar");
            window.addEventListener("scroll", () => {
                if (window.scrollY > 10) {
                    navbar.classList.add("scrolled");
                } else {
                    navbar.classList.remove("scrolled");
                }
            });
        });
});
