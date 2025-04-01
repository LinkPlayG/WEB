// public/js/search.js
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchOverlay = document.getElementById('search-overlay');
    const closeOverlay = document.getElementById('close-overlay');
    const searchButton = document.getElementById('search-button');
    const quoiField = document.getElementById('quoi');
    const ouField = document.getElementById('ou');
    const optionButtons = document.querySelectorAll('.option-btn');

    // Ajout d'un bouton pour effacer le champ de recherche
    const searchInputWrapper = document.querySelector('.search-input-wrapper');
    const clearSearch = document.createElement('button');
    clearSearch.id = 'clear-search';
    clearSearch.innerHTML = '&times;';
    clearSearch.style.position = 'absolute';
    clearSearch.style.right = '30px';
    clearSearch.style.background = 'none';
    clearSearch.style.border = 'none';
    clearSearch.style.fontSize = '18px';
    clearSearch.style.cursor = 'pointer';
    clearSearch.style.display = 'none';
    searchInputWrapper.appendChild(clearSearch);

    // Afficher le bouton clearSearch quand le champ contient du texte
    searchInput.addEventListener('input', function() {
        clearSearch.style.display = searchInput.value ? 'block' : 'none';
        
        // Mettre à jour également le champ QUOI dans l'overlay pour plus de cohérence
        quoiField.value = searchInput.value;
        
        // Appliquer la recherche en temps réel (si les marqueurs sont définis)
        if (typeof markers !== 'undefined') {
            applySearch();
        }
    });

    // Ouvrir l'overlay lors du click dans la barre de recherche
    searchInput.addEventListener('click', function() {
        searchOverlay.classList.remove('hidden');
        searchOverlay.style.display = 'flex';
    });

    // Fermer l'overlay
    closeOverlay.addEventListener('click', function() {
        searchOverlay.classList.add('hidden');
        searchOverlay.style.display = 'none';
    });

    // Effacer le champ de recherche
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        quoiField.value = '';
        clearSearch.style.display = 'none';
        
        // Réinitialiser les résultats (si les marqueurs sont définis)
        if (typeof markers !== 'undefined') {
            resetSearch();
        }
    });

    // Bouton de recherche avancée
    searchButton.addEventListener('click', function() {
        // Synchroniser l'input principal avec le champ QUOI
        searchInput.value = quoiField.value;
        clearSearch.style.display = searchInput.value ? 'block' : 'none';
        
        // Appliquer la recherche avancée
        if (typeof markers !== 'undefined') {
            applyAdvancedSearch();
        }
        
        // Fermer l'overlay
        searchOverlay.classList.add('hidden');
        searchOverlay.style.display = 'none';
    });

    // Gestion des boutons de filtre (Default, A-Z, List view)
    optionButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Enlever la classe active de tous les boutons
            optionButtons.forEach(btn => btn.classList.remove('active'));
            // Ajouter la classe active au bouton cliqué
            this.classList.add('active');
            
            // Appliquer le tri correspondant
            applySorting(this.textContent.trim());
        });
    });

    // Fonction pour appliquer la recherche simple
    function applySearch() {
        const searchText = searchInput.value.toLowerCase();
        
        markers.forEach(function(item) {
            const title = item.card.querySelector('.offer-title').textContent.toLowerCase();
            const company = item.card.querySelector('.offer-company').textContent.toLowerCase();
            
            if (title.includes(searchText) || company.includes(searchText)) {
                item.card.style.display = '';
                item.marker.setOpacity(1);
            } else {
                item.card.style.display = 'none';
                item.marker.setOpacity(0.2);
            }
        });
    }

    // Fonction pour appliquer la recherche avancée
    function applyAdvancedSearch() {
        const quoi = quoiField.value.toLowerCase();
        const ou = ouField.value.toLowerCase();
        
        markers.forEach(function(item) {
            const title = item.card.querySelector('.offer-title').textContent.toLowerCase();
            const company = item.card.querySelector('.offer-company').textContent.toLowerCase();
            const address = item.card.dataset.address ? item.card.dataset.address.toLowerCase() : '';
            let matched = true;
            
            // Vérifier le critère QUOI
            if (quoi && !(title.includes(quoi) || company.includes(quoi))) {
                matched = false;
            }
            
            // Vérifier le critère OÙ
            if (matched && ou) {
                // Recherche dans l'adresse de l'entreprise
                if (!address.includes(ou)) {
                    matched = false;
                }
            }
            
            if (matched) {
                item.card.style.display = '';
                item.marker.setOpacity(1);
                // Centrer la carte sur le premier résultat trouvé
                if (ou && item.marker) {
                    map.setView(item.marker.getLatLng(), 13);
                }
            } else {
                item.card.style.display = 'none';
                item.marker.setOpacity(0.2);
            }
        });
    }

    // Fonction pour réinitialiser la recherche
    function resetSearch() {
        markers.forEach(function(item) {
            item.card.style.display = '';
            item.marker.setOpacity(1);
        });
    }

    // Fonction pour appliquer le tri
    function applySorting(sortType) {
        const cardsList = document.querySelector('.offers-list');
        const cards = Array.from(cardsList.querySelectorAll('.offer-card'));
        
        switch(sortType) {
            case 'A-Z':
                // Trier par titre alphabétiquement
                cards.sort((a, b) => {
                    const titleA = a.querySelector('.offer-title').textContent;
                    const titleB = b.querySelector('.offer-title').textContent;
                    return titleA.localeCompare(titleB);
                });
                break;
            case 'List view':
                // Format liste (on peut ajuster les styles ici)
                cards.forEach(card => {
                    card.style.padding = '10px';
                    card.style.marginBottom = '5px';
                });
                break;
            default: // Default
                // Retour à l'affichage par défaut (tri par date récente)
                cards.sort((a, b) => {
                    const dateA = a.querySelector('.offer-detail:nth-child(2)').textContent.trim();
                    const dateB = b.querySelector('.offer-detail:nth-child(2)').textContent.trim();
                    // Inverser la comparaison pour avoir les plus récentes en premier
                    return dateB.localeCompare(dateA);
                });
                cards.forEach(card => {
                    card.style.padding = '15px';
                    card.style.marginBottom = '15px';
                });
                break;
        }
        
        // Réorganiser les cartes dans le DOM
        cards.forEach(card => cardsList.appendChild(card));
    }
});
