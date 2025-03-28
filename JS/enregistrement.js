document.addEventListener('DOMContentLoaded', function() {
    const voteButtons = document.querySelectorAll('.vote-button');
    const submitVoteButton = document.getElementById('submitVote');
    const selectedVoteDisplay = document.getElementById('selectedVote');
    const voteStatusDisplay = document.getElementById('voteStatus');
    
    let selectedType = null;
    let selectedValue = null;
    
    // Gestion de la sélection du vote
    voteButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Réinitialiser tous les boutons
            voteButtons.forEach(btn => btn.classList.remove('selected'));
            
            // Marquer ce bouton comme sélectionné
            this.classList.add('selected');
            
            // Stocker les données du vote
            selectedType = this.getAttribute('data-type');
            selectedValue = this.getAttribute('data-value');
            
            // Afficher la sélection
            selectedVoteDisplay.textContent = `Vous avez sélectionné: ${selectedType} - ${selectedValue}`;
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        fetch('../../Mairie/Menu/get_vote_du_jour.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll('.vote-button').forEach(button => {
                        if (button.getAttribute('data-type') !== data.type || button.getAttribute('data-value') !== data.valeur) {
                            button.disabled = true; // Désactiver les autres votes
                        }
                    });
                }
            });
    });
    
    // Soumission du vote
    submitVoteButton.addEventListener('click', function() {
        if (!selectedType || !selectedValue) {
            voteStatusDisplay.textContent = 'Erreur: Veuillez sélectionner un élément du menu.';
            return;
        }
        
        // Récupérer l'ID du menu
        const urlParams = new URLSearchParams(window.location.search);
        const menuId = urlParams.get('id');
        
        // Envoyer le vote à l'API
        fetch('../../Mairie/Menu/save_vote.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                menu_id: menuId,
                type: selectedType,
                valeur: selectedValue,
                date: new Date().toISOString().split('T')[0] // Date actuelle au format YYYY-MM-DD
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                voteStatusDisplay.textContent = 'Vote enregistré avec succès!';
                voteStatusDisplay.style.color = 'green';
            } else {
                voteStatusDisplay.textContent = 'Erreur: ' + data.message;
                voteStatusDisplay.style.color = 'red';
            }
        })
        .catch(error => {
            voteStatusDisplay.textContent = 'Erreur de connexion, veuillez réessayer.';
            voteStatusDisplay.style.color = 'red';
            console.error('Erreur:', error);
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const popup = document.getElementById('votePopup');
    const openPopupBtn = document.getElementById('openVotePopup');
    const closePopup = document.querySelector('.close-popup');

    // Ouvrir la popup
    openPopupBtn.addEventListener('click', function () {
        popup.style.display = 'block';
    });

    // Fermer la popup
    closePopup.addEventListener('click', function () {
        popup.style.display = 'none';
    });

    // Fermer si on clique en dehors de la popup
    window.addEventListener('click', function (e) {
        if (e.target === popup) {
            popup.style.display = 'none';
        }
    });
});
