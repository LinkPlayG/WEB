document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la suppression des offres
    const removeButtons = document.querySelectorAll('.btn-remove');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.wishlist-card');
            const offerId = card.dataset.offerId;
            
            // Ajouter la classe pour l'animation
            card.classList.add('removing');
            
            // Attendre la fin de l'animation avant de supprimer
            setTimeout(() => {
                // Envoyer la requête de suppression
                fetch(`/wishlist/remove/${offerId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Supprimer la carte du DOM
                        card.remove();
                        
                        // Vérifier s'il reste des offres
                        const remainingCards = document.querySelectorAll('.wishlist-card');
                        if (remainingCards.length === 0) {
                            // Afficher le message "wishlist vide"
                            const wishlistGrid = document.querySelector('.wishlist-grid');
                            wishlistGrid.innerHTML = `
                                <div class="empty-wishlist">
                                    <h2>Votre wishlist est vide</h2>
                                    <p>Vous n'avez pas encore sauvegardé d'offres de stage.</p>
                                    <a href="{{ path('app_annonces') }}" class="browse-offers-btn">Parcourir les offres</a>
                                </div>
                            `;
                        }
                    } else {
                        alert('Une erreur est survenue lors de la suppression de l\'offre.');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la suppression de l\'offre.');
                });
            }, 300); // Attendre la fin de l'animation (300ms)
        });
    });
}); 