/* --- FAVORITES MANAGEMENT --- */

const FAVORITES_COOKIE_NAME = 'plusflix_favorites';
const COOKIE_MAX_AGE = 365 * 24 * 60 * 60; // 1 year in seconds

function getFavoritesList() {
    const cookie = document.cookie
        .split('; ')
        .find(row => row.startsWith(FAVORITES_COOKIE_NAME + '='));
    
    if (!cookie) return [];
    
    try {
        const favorites = JSON.parse(decodeURIComponent(cookie.split('=')[1]));
        return Array.isArray(favorites) ? favorites : [];
    } catch (e) {
        return [];
    }
}

function saveFavoritesToCookie(favorites) {
    const serialized = encodeURIComponent(JSON.stringify(favorites));
    const date = new Date();
    date.setTime(date.getTime() + COOKIE_MAX_AGE * 1000);
    const expires = 'expires=' + date.toUTCString();
    document.cookie = `${FAVORITES_COOKIE_NAME}=${serialized}; ${expires}; path=/`;
}

function toggleFavorite(showId) {
    let favorites = getFavoritesList();
    const index = favorites.indexOf(showId);
    
    if (index > -1) {
        favorites.splice(index, 1);
    } else {
        favorites.push(showId);
    }
    
    saveFavoritesToCookie(favorites);
    updateFavoriteButton(showId);
}

function updateFavoriteButton(showId) {
    const buttons = document.querySelectorAll(`.movie-like[data-show-id="${showId}"]`);
    if (buttons.length === 0) return;
    
    const favorites = getFavoritesList();
    const isFavorite = favorites.includes(showId);
    
    buttons.forEach(button => {
        if (isFavorite) {
            button.classList.add('active');
            button.textContent = '♥';
        } else {
            button.classList.remove('active');
            button.textContent = '♡';
        }
    });
}

function initFavorites() {
    const favorites = getFavoritesList();
    
    // Update all buttons on page load
    document.querySelectorAll('.movie-like').forEach(button => {
        const showId = parseInt(button.dataset.showId);
        updateFavoriteButton(showId);
        
        // Prevent default link behavior when clicking button
        button.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleFavorite(showId);
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initFavorites();
});