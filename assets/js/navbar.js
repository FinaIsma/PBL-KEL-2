document.addEventListener("DOMContentLoaded", () => {
    fetch("../navbar.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("navbar-placeholder").innerHTML = data;

            const currentPage = window.location.pathname.split("/").pop();
            const links = document.querySelectorAll(".nav-menu a");

            links.forEach(link => {
                if (link.getAttribute("href") === currentPage) {
                    link.classList.add("active");
                }
            });
        });
});
