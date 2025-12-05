fetch("header.html")
    .then(res => res.text())
    .then(html => {
        document.getElementById("header-placeholder").innerHTML = html;

        const navbar = document.querySelector(".navbar");
        if (navbar) navbar.classList.add("navbar-ready");
    });

fetch("sidebar.html")
    .then(res => res.text())
    .then(html => {
        document.getElementById("sidebar-placeholder").innerHTML = html;
    });

document.addEventListener("DOMContentLoaded", () => {
    const currentPage = window.location.pathname.split("/").pop();

    document.querySelectorAll(".nav-link").forEach(link => {
        const href = link.getAttribute("href");

        if (href === currentPage) {
            link.classList.add("active");
        }
    });
});