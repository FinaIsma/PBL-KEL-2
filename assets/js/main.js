import { initPengelolaSlider, initSearchProfil } from "./profil.js";
import { initSarprasPagination } from "./layanan.js";

document.addEventListener("DOMContentLoaded", () => {

    // === Slider Profil ===
    const sliderEl = document.querySelector("[data-pengelola-slider]");
    if (sliderEl) {
        const visibleCount = window.userRole === 'admin' ? 3 : 4;
        initPengelolaSlider("[data-pengelola-slider]", visibleCount);
    }

    // === Search Profil ===
    const searchInput = document.querySelector("[data-search]");
    if (searchInput) {
        initSearchProfil();
    }

    // === Pagination Sarpras ===
    const sarprasContainer = document.querySelector("[data-sarpras-container]");
    if (sarprasContainer) {
        const allCards = Array.from(sarprasContainer.querySelectorAll(".Sarpras-card"));
        initSarprasPagination(allCards, 6); // 6 kartu per halaman
    }

});
