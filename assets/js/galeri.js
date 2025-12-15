document.addEventListener('DOMContentLoaded', () => {

    /* ===== AGENDA SCROLL ===== */
    const agendaContainer = document.querySelector(".agenda-container");
    const btnLeft = document.querySelector(".scroll-btn.left");
    const btnRight = document.querySelector(".scroll-btn.right");

    if (agendaContainer && btnLeft && btnRight) {
        btnLeft.addEventListener("click", () => {
            agendaContainer.scrollBy({ left: -300, behavior: "smooth" });
        });

        btnRight.addEventListener("click", () => {
            agendaContainer.scrollBy({ left: 300, behavior: "smooth" });
        });
    }

    /* ===== PAGINATION ACTIVE STATE ===== */
    const paginationButtons = document.querySelectorAll('.pagination-btn');
    paginationButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            paginationButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });

});
