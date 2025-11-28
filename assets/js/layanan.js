const sarprasData = [
    [
{ title: "Foto 1", img: "../assets/img/image 3.png" },
{ title: "Foto 2", img: "../img/img2.png" },
{ title: "Foto 3", img: "../img/img3.png" },
{ title: "Foto 4", img: "../img/img4.png" },
{ title: "Foto 5", img: "../img/img5.png" },
{ title: "Foto 6", img: "../img/img6.png" }
    ],
    [
        { title: "Foto 7", img: "img7.png" },
        { title: "Foto 8", img: "img8.png" },
        { title: "Foto 9", img: "img9.png" },
        { title: "Foto 10", img: "img10.png" },
        { title: "Foto 11", img: "img11.png" },
        { title: "Foto 12", img: "img12.png" }
    ],
    [
        { title: "Foto 13", img: "img13.png" },
        { title: "Foto 14", img: "img14.png" },
        { title: "Foto 15", img: "img15.png" },
        { title: "Foto 16", img: "img16.png" },
        { title: "Foto 17", img: "img17.png" },
        { title: "Foto 18", img: "img18.png" }
    ]
];

export const renderSarpras = (page) => {
    const container = document.querySelector("[data-sarpras-container]");
    if (!container) return;

    const items = sarprasData[page - 1];
    container.innerHTML = "";

    items.forEach(item => {
        container.innerHTML += `
            <div class="Sarpras-card card shadow">
                <div class="Sarana-foto" style="background-image:url('${item.img}')"></div>
                <p class="card-header">${item.title}</p>
            </div>
        `;
    });
};




export const initPagination = (changePage) => {
    const container = document.querySelector("[data-button-container]");
    if (!container) return;

    const buttons = container.querySelectorAll("[data-page-btn]");

    buttons.forEach(btn => {
        btn.addEventListener("click", () => {
            const page = Number(btn.dataset.pageBtn);
            changePage(page);   // Kirim ke modul lain
        });
    });
};
