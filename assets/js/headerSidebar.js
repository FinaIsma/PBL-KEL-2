// Import Header
fetch("header.html")
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
