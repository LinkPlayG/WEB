// public/js/search.js
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchOverlay = document.getElementById('search-overlay');
    const closeOverlay = document.getElementById('close-overlay');
    const clearSearch = document.getElementById('clear-search');
    const searchButton = document.getElementById('search-button');

    searchInput.addEventListener('click', function() {
        searchOverlay.style.display = 'flex';
    });

    closeOverlay.addEventListener('click', function() {
        searchOverlay.style.display = 'none';
    });

    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
    });

    searchButton.addEventListener('click', function() {
        // Logique de recherche à implémenter
        searchOverlay.style.display = 'none';
    });
});
