document.addEventListener('DOMContentLoaded', () => {
});

const agendaPrev = document.querySelector('.agenda-prev');
const agendaNext = document.querySelector('.agenda-next');
const agendaContainer = document.querySelector('.agenda-container');

if (agendaPrev && agendaNext && agendaContainer) {
    agendaPrev.addEventListener('click', () => {
        agendaContainer.scrollBy({ left: -330, behavior: 'smooth' });
    });

    agendaNext.addEventListener('click', () => {
        agendaContainer.scrollBy({ left: 330, behavior: 'smooth' });
    });
}

const paginationButtons = document.querySelectorAll('.pagination-btn');
paginationButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        paginationButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        console.log(`Pindah ke halaman ${btn.textContent}`);
    });
});