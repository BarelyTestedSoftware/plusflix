/* --- SLIDER --- */

// =========================================================================
// KOMENTARZ DLA BACKEND DEVELOPEÓW:
//
// Obecnie tablica 'heroSlides' jest zahardkodowana w JavaScript (dane statyczne).
// Docelowo, proszę wygenerować tę zmienną dynamicznie w widoku PHP, pobierając
// np. 5 polecanych filmów z bazy danych.
//
// Przykład implementacji w PHP (wewnątrz tagu <script> w pliku widoku):
// const heroSlides = <?= json_encode($polecaneFilmy) ?>;
//
// Wymagana struktura JSON obiektu:
// {
//    title: "Tytuł Filmu",
//    desc: "Krótki opis...",
//    bg: "url_do_obrazka.jpg",
//    category: "Film" lub "Serial",
//    year: "2023",
//    duration: "120 min"
// }
// =========================================================================



const heroSlides = [
    {
        title: "Stranger Things",
        desc: "Grupa przyjaciół odkrywa mroczne sekrety rządowego laboratorium i portalu do innego wymiaru.",
        // bg: z bazy danych zdjęcie do tła,,
        category: "Serial",
        year: "2016",
        duration: "4 sezony",
        stars: "★★★★★"
    },
    {
        title: "Interstellar",
        desc: "Byt ludzkości na Ziemi dobiega końca, grupa odkrywców wyrusza na najważniejszą misję w historii.",
        // bg: z bazy danych zdjęcie do tła,,
        category: "Film",
        year: "2014",
        duration: "169 min",
        stars: "★★★★★"
    },
    {
        title: "The Dark Knight",
        desc: "Batman stawia czoła Jokerowi, który pogrąża Gotham City w anarchii i zmusza bohatera do walki z samym sobą.",
        // bg: z bazy danych zdjęcie do tła,
        category: "Film",
        year: "2008",
        duration: "152 min",
        stars: "★★★★★"
    },
    {
        title: "The Last of Us",
        desc: "Dwadzieścia lat po upadku cywilizacji, Joel zostaje wynajęty do przemycenia 14-letniej Ellie ze strefy kwarantanny.",
        // bg: z bazy danych zdjęcie do tła,,
        category: "Serial",
        year: "2023",
        duration: "1 sezon",
        stars: "★★★★☆"
    }
];

let currentSlide = 0;
const slideIntervalTime = 5000;
let slideInterval;


const domElements = {
    banner: document.getElementById('hero-banner'),
    title: document.getElementById('hero-title'),
    desc: document.getElementById('hero-desc'),
    category: document.getElementById('hero-category'),
    year: document.getElementById('hero-year'),
    duration: document.getElementById('hero-duration'),
    stars: document.getElementById('hero-stars'),
    dotsContainer: document.getElementById('slider-dots')
};

function initSlider() {
    if (!domElements.banner) return;
    renderDots();
    startAutoSlide();
}

function renderDots() {
    domElements.dotsContainer.innerHTML = '';
    heroSlides.forEach((_, index) => {
        const dot = document.createElement('span');
        if (index === 0) dot.classList.add('active');
        dot.onclick = () => manualChangeSlide(index);
        domElements.dotsContainer.appendChild(dot);
    });
}

function updateHero(index) {
    const slide = heroSlides[index];


    domElements.banner.style.backgroundImage = `url('${slide.bg}')`;

    //Aktualizacja tekstów
    domElements.title.innerText = slide.title;
    domElements.desc.innerText = slide.desc;
    domElements.category.innerText = slide.category;
    domElements.year.innerText = slide.year;
    domElements.duration.innerText = slide.duration;
    domElements.stars.innerText = slide.stars;


    const dots = domElements.dotsContainer.querySelectorAll('span');
    dots.forEach(d => d.classList.remove('active'));
    if(dots[index]) dots[index].classList.add('active');
}

function manualChangeSlide(index) {
    currentSlide = index;
    updateHero(currentSlide);
    resetTimer();
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % heroSlides.length;
    updateHero(currentSlide);
}

function startAutoSlide() {
    slideInterval = setInterval(nextSlide, slideIntervalTime);
}

function resetTimer() {
    clearInterval(slideInterval);
    startAutoSlide();
}

document.addEventListener('DOMContentLoaded', initSlider);