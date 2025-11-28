export function initPengelolaSlider() {
    const slider = document.querySelector(".pengelolaLab-card");
    const btnLeft = document.querySelector("[data-arrow='left']");
    const btnRight = document.querySelector("[data-arrow='right']");

    let cards = Array.from(slider.querySelectorAll(".card-name"));

    function render() {
        slider.innerHTML = "";
        cards.forEach(card => slider.appendChild(card));
    }

    // ROTASI KANAN
    btnRight.addEventListener("click", () => {
        const first = cards.shift(); // ambil kartu pertama
        cards.push(first);           // taruh di akhir
        render();                     // tampilkan ulang
    });

    // ROTASI KIRI
    btnLeft.addEventListener("click", () => {
        const last = cards.pop();    // ambil kartu terakhir
        cards.unshift(last);         // taruh di awal
        render();                     // tampilkan ulang
    });
}
