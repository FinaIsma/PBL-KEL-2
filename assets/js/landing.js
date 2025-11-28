const track = document.getElementById("carousel-track");
let items = Array.from(track.children);

const itemWidth = 260;
const gap = 35;
const step = itemWidth + gap;

let index = 0; // index item paling kiri dari 3 tampilan

function updateCarousel() {
    track.style.transform = `translateX(${-index * step}px)`;

    items.forEach(item => item.classList.remove("center"));

    const centerIndex = index + 1; // item kedua dari 3 tampilan
    if (items[centerIndex]) {
        items[centerIndex].classList.add("center");
    }
}

document.querySelector(".next").onclick = () => {
    if (index < items.length - 3) index++;
    updateCarousel();
};

document.querySelector(".prev").onclick = () => {
    if (index > 0) index--;
    updateCarousel();
};

updateCarousel();
