export function initPengelolaSlider() {
    const slider = document.querySelector(".pengelolaLab-card");
    const btnLeft = document.querySelector("[data-arrow='left']");
    const btnRight = document.querySelector("[data-arrow='right']");

    let cards = Array.from(slider.querySelectorAll(".card-name"));
    const cardWidth = 250 + 53; // width + gap

    slider.style.display = "flex";
    slider.style.transition = "transform 0.5s ease";

    function render() {
        slider.innerHTML = "";
        cards.forEach(card => slider.appendChild(card));
    }

    // ROTASI KANAN (geser ke depan)
    btnRight.addEventListener("click", () => {
        // geser dulu dengan animasi
        slider.style.transform = `translateX(-${cardWidth}px)`;

        // setelah animasi selesai
        slider.addEventListener("transitionend", function handler() {
            slider.style.transition = "none"; // matikan transisi sementara
            const first = cards.shift();
            cards.push(first);
            render();
            slider.style.transform = `translateX(0)`; // reset posisi
            // biar animasi jalan lagi
            setTimeout(() => {
                slider.style.transition = "transform 0.5s ease";
            });
            slider.removeEventListener("transitionend", handler);
        });
    });

    // ROTASI KIRI (geser ke belakang)
    btnLeft.addEventListener("click", () => {
        // taruh last di depan dulu tanpa animasi
        slider.style.transition = "none";
        const last = cards.pop();
        cards.unshift(last);
        render();
        slider.style.transform = `translateX(-${cardWidth}px)`;

        // animasi geser ke 0
        setTimeout(() => {
            slider.style.transition = "transform 0.5s ease";
            slider.style.transform = `translateX(0)`;
        });
    });
}
