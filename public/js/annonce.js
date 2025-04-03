document.addEventListener('DOMContentLoaded', function() {
    // Initialisation de la carte
    var map = L.map('map').setView([46.603354, 1.888334], 6); // Centre sur la France
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var markers = [];
    var modal = document.getElementById('offerModal');
    var currentOfferId = null;
    
    // Fonction pour enregistrer l'offre
    window.saveOffer = function() {
        const currentCard = document.querySelector('.offer-card[data-id="' + currentOfferId + '"]');
        if (currentCard) {
            // TODO: Implémenter la logique de sauvegarde
            alert('Offre enregistrée !');
        }
    }

    // Fonction pour candidater
    window.applyOffer = function() {
        const currentCard = document.querySelector('.offer-card[data-id="' + currentOfferId + '"]');
        if (currentCard) {
            fetch('/candidature/new/' + currentOfferId, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Fermer la modal
                    closeModal();
                    // Afficher un message de succès
                    alert('Votre candidature a été enregistrée avec succès !');
                    // Rediriger vers la page des candidatures
                    window.location.href = '/candidatures';
                } else {
                    alert(data.error || 'Une erreur est survenue lors de l\'envoi de votre candidature.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de l\'envoi de votre candidature.');
            });
        }
    }

    // Fonction pour ouvrir la modal avec les détails de l'offre
    function openModal(card) {
        currentOfferId = card.dataset.id;
        document.getElementById('modalTitle').textContent = card.dataset.title;
        document.getElementById('modalCompany').textContent = card.dataset.company;
        document.getElementById('modalDescription').textContent = card.dataset.description;
        document.getElementById('modalSkills').textContent = card.dataset.skills;
        
        // Formatage des détails du stage
        var details = `
            Durée : ${card.dataset.duration} mois<br>
            Date de début : ${card.dataset.startDate}<br>
            Date de fin : ${card.dataset.endDate}<br>
            Salaire : ${card.dataset.salary}€ par mois
        `;
        document.getElementById('modalDetails').innerHTML = details;
        
        // Détails de l'entreprise
        document.getElementById('modalCompanyDetails').innerHTML = `
            ${card.dataset.company}<br>
            Adresse : ${card.dataset.address}
        `;
        
        modal.style.display = 'block';
    }
    
    // Fonction pour fermer la modal
    window.closeModal = function() {
        modal.style.display = 'none';
    }
    
    // Fermer la modal en cliquant sur la croix
    document.querySelector('.close-modal').onclick = closeModal;
    
    // Fermer la modal en cliquant en dehors
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
    
    // Ajout des marqueurs pour chaque offre
    document.querySelectorAll('.offer-card').forEach(function(card) {
        var lat = parseFloat(card.dataset.lat);
        var lng = parseFloat(card.dataset.lng);
        
        if (lat && lng) {
            var marker = L.marker([lat, lng])
                .bindPopup(card.querySelector('.offer-title').textContent)
                .addTo(map);
            
            markers.push({
                marker: marker,
                card: card
            });

            // Lien entre le marqueur et la carte
            marker.on('click', function() {
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                card.style.backgroundColor = '#f0f8ff';
                setTimeout(() => card.style.backgroundColor = '', 2000);
            });

            // Clic sur la carte pour centrer sur le marqueur
            card.addEventListener('click', function(e) {
                // Ne pas déclencher si on clique sur le bouton de vue
                if (!e.target.closest('.view-btn')) {
                    map.setView([lat, lng], 13);
                    marker.openPopup();
                }
            });

            // Bouton pour voir les détails
            const viewBtn = card.querySelector('.view-btn');
            viewBtn.addEventListener('click', function(e) {
                e.stopPropagation(); // Empêcher la propagation du clic
                openModal(card);
            });
        }
    });

    // Gestion de la wishlist
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Empêcher la propagation du clic
            const offerId = this.dataset.offerId;
            
            // Toggle de la classe active
            this.classList.toggle('active');
            
            // Mise à jour du texte du bouton
            if (this.classList.contains('active')) {
                this.innerHTML = '❤️';
                // Appel AJAX pour ajouter à la wishlist
                fetch('/wishlist/add/' + offerId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Animation de succès si besoin
                    }
                });
            } else {
                this.innerHTML = '🤍';
                // Appel AJAX pour retirer de la wishlist
                fetch('/wishlist/remove/' + offerId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Animation de succès si besoin
                    }
                });
            }
        });

        // Vérifier si l'offre est déjà dans la wishlist au chargement
        const offerId = button.dataset.offerId;
        fetch('/wishlist/check/' + offerId)
            .then(response => response.json())
            .then(data => {
                if (data.inWishlist) {
                    button.classList.add('active');
                    button.innerHTML = '❤️';
                } else {
                    button.innerHTML = '🤍';
                }
            });
    });
}); 