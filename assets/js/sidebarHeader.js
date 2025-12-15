fetch("header.php")
    .then(res => res.text())
    .then(html => {
        document.getElementById("header").innerHTML = html;

        const navbar = document.querySelector(".navbar");
        if (navbar) navbar.classList.add("navbar-ready");
    });

// Import Header
fetch("header.php")
    .then(res => res.text())
    .then(data => {
        document.getElementById("header").innerHTML = data;

    });

// Import Sidebar
fetch("sidebar.html")
    .then(res => res.text())
    .then(data => {
        document.getElementById("sidebar").innerHTML = data;
    });
