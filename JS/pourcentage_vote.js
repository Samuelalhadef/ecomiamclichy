document.addEventListener('DOMContentLoaded', function() {
    function fetchVoteData() {
        fetch('PHP_Donnes_graph.php')
            .then(response => response.json())
            .then(data => {
                const likeBar = document.getElementById('like-bar');
                const mediumBar = document.getElementById('medium-bar');
                const dislikeBar = document.getElementById('dislike-bar');
                const likePercentage = document.getElementById('like-percentage');
                const mediumPercentage = document.getElementById('medium-percentage');
                const dislikePercentage = document.getElementById('dislike-percentage');
                const elementTitre = document.getElementById('element-titre');
                const container = document.getElementById('graph-container');
                const containerHeight = container.clientHeight - 50;

                // Calcul des totaux
                const totalVotes = data.aime + data.aime_moyen + data.aime_pas;

                // Mise à jour du titre de l'élément
                elementTitre.textContent = `Votes pour : ${data.element}`;

                // Calcul des pourcentages
                const likePercent = totalVotes > 0 ? (data.aime / totalVotes * 100).toFixed(1) : 0;
                const mediumPercent = totalVotes > 0 ? (data.aime_moyen / totalVotes * 100).toFixed(1) : 0;
                const dislikePercent = totalVotes > 0 ? (data.aime_pas / totalVotes * 100).toFixed(1) : 0;

                // Mise à jour des barres avec les pourcentages
                likeBar.style.height = totalVotes > 0 
                    ? `${(data.aime / Math.max(data.aime, data.aime_moyen, data.aime_pas)) * containerHeight}px` 
                    : '0px';
                likeBar.innerHTML = `<span class="bar-percentage">${likePercent}%</span>`;
                
                mediumBar.style.height = totalVotes > 0 
                    ? `${(data.aime_moyen / Math.max(data.aime, data.aime_moyen, data.aime_pas)) * containerHeight}px` 
                    : '0px';
                mediumBar.innerHTML = `<span class="bar-percentage">${mediumPercent}%</span>`;
                
                dislikeBar.style.height = totalVotes > 0 
                    ? `${(data.aime_pas / Math.max(data.aime, data.aime_moyen, data.aime_pas)) * containerHeight}px` 
                    : '0px';
                dislikeBar.innerHTML = `<span class="bar-percentage">${dislikePercent}%</span>`;

                // Mise à jour des pourcentages à côté des barres
                likePercentage.textContent = `${likePercent}%`;
                mediumPercentage.textContent = `${mediumPercent}%`;
                dislikePercentage.textContent = `${dislikePercent}%`;
            })
            .catch(error => console.error('Erreur de chargement:', error));
    }

    // Charger les données au chargement
    fetchVoteData();

    // Actualiser les données toutes les 10 secondes
    setInterval(fetchVoteData, 10000);
});