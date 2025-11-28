// Load Header
fetch("../header.html")
    .then(res => res.text())
    .then(html => {
        document.getElementById("header-placeholder").innerHTML = html;

        const navbar = document.querySelector(".navbar");
        if (navbar) navbar.classList.add("navbar-ready");
    });

// Load Sidebar
fetch("../sidebar.html")
    .then(res => res.text())
    .then(html => {
        document.getElementById("sidebar-placeholder").innerHTML = html;

        // Initialize sidebar toggle after loading
        initSidebarToggle();
    });

// Sidebar Toggle Function
const initSidebarToggle = () => {
    // Create toggle button
    const toggleBtn = document.createElement("button");
    toggleBtn.className = "sidebar-toggle";
    toggleBtn.setAttribute("data-sidebar-toggle", "");
    toggleBtn.setAttribute("aria-label", "Toggle Sidebar");
    document.body.appendChild(toggleBtn);

    // Create overlay
    const overlay = document.createElement("div");
    overlay.className = "sidebar-overlay";
    overlay.setAttribute("data-sidebar-overlay", "");
    document.body.appendChild(overlay);

    // Get sidebar element
    const sidebar = document.querySelector(".sidebar");

    // Toggle function
    const toggleSidebar = () => {
        sidebar.classList.toggle("active");
        toggleBtn.classList.toggle("active");
        overlay.classList.toggle("active");
        
        // Prevent body scroll when sidebar open on mobile
        if (window.innerWidth <= 768) {
            document.body.style.overflow = sidebar.classList.contains("active") ? "hidden" : "";
        }
    };

    // Toggle button click
    toggleBtn.addEventListener("click", toggleSidebar);

    // Overlay click to close
    overlay.addEventListener("click", toggleSidebar);

    // Close sidebar when clicking outside on mobile
    document.addEventListener("click", (e) => {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && 
                !toggleBtn.contains(e.target) && 
                sidebar.classList.contains("active")) {
                toggleSidebar();
            }
        }
    });

    // Handle window resize
    let resizeTimer;
    window.addEventListener("resize", () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            // Close sidebar and reset on desktop view
            if (window.innerWidth > 768) {
                sidebar.classList.remove("active");
                toggleBtn.classList.remove("active");
                overlay.classList.remove("active");
                document.body.style.overflow = "";
            }
        }, 250);
    });

    // Set active menu
    const currentPage = window.location.pathname.split("/").pop();
    const menuLinks = document.querySelectorAll(".nav-link");

    menuLinks.forEach(link => {
        const href = link.getAttribute("href");
        if (href === currentPage) {
            link.classList.add("active");
        }

        // Close sidebar on mobile when menu clicked
        link.addEventListener("click", () => {
            if (window.innerWidth <= 768 && sidebar.classList.contains("active")) {
                toggleSidebar();
            }
        });
    });
};fetch("header.html")
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
