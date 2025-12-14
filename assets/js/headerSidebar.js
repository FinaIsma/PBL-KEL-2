fetch("header.html")
    .then(res => res.text())
    .then(html => {
        document.getElementById("header-placeholder").innerHTML = html;

        const navbar = document.querySelector(".navbar");
        if (navbar) navbar.classList.add("navbar-ready");
    });

// Import Sidebar
fetch("sidebar.html")
    .then(res => res.text())
    .then(data => {
        document.getElementById("sidebar").innerHTML = data;
    });
