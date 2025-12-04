export const initSarprasPagination = (allCards, itemsPerPage = 6) => {
    const container = document.querySelector("[data-sarpras-container]");
    const buttonContainer = document.querySelector("[data-button-container]");
    if (!container || !buttonContainer) return;

    const totalPages = Math.ceil(allCards.length / itemsPerPage);
    let currentPage = 1;

    const renderPage = (page) => {
        container.innerHTML = "";
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const pageItems = allCards.slice(start, end);
        pageItems.forEach(card => container.appendChild(card));
    };

    const renderButtons = () => {
        buttonContainer.innerHTML = "";
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement("button");
            btn.classList.add("btn");
            btn.dataset.pageBtn = i;
            btn.textContent = i;
            btn.addEventListener("click", () => {
                currentPage = i;
                renderPage(currentPage);
            });
            buttonContainer.appendChild(btn);
        }
    };

    renderButtons();
    renderPage(currentPage);
};
