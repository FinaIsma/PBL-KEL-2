export function initPengelolaSlider(sliderSelector = ".pengelolaLab-card", visibleCount = 3) {
    const slider = document.querySelector(sliderSelector);
    const btnLeft = document.querySelector("[data-arrow='left']");
    const btnRight = document.querySelector("[data-arrow='right']");

    if (!slider) return;

    const cards = Array.from(slider.querySelectorAll(".card-name"));
    let startIndex = 0;

    slider.style.display = "flex";
    slider.style.overflow = "hidden";
    slider.style.justifyContent = "center";

    function render() {
        slider.innerHTML = "";
        for (let i = 0; i < visibleCount; i++) {
            const index = (startIndex + i) % cards.length; 
            slider.appendChild(cards[index]);
        }
    }

    btnRight.addEventListener("click", () => {
        startIndex = (startIndex + 1) % cards.length;
        render();
    });

    btnLeft.addEventListener("click", () => {
        startIndex = (startIndex - 1 + cards.length) % cards.length;
        render();
    });

    render();
}
