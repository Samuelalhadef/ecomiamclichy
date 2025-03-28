document.addEventListener('DOMContentLoaded', function() {
    fetch('get_excel_data.php')
        .then(response => response.json())
        .then(data => {
            // Masquer le message de chargement
            document.getElementById('loading').style.display = 'none';
            
            // Créer un tableau HTML
            const tableElement = document.createElement('table');
            tableElement.id = 'excel-table';
            
            // En-têtes
            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');
            data[0].forEach(header => {
                const th = document.createElement('th');
                th.textContent = header;
                headerRow.appendChild(th);
            });
            thead.appendChild(headerRow);
            tableElement.appendChild(thead);
            
            // Données
            const tbody = document.createElement('tbody');
            for (let i = 1; i < data.length; i++) {
                const tr = document.createElement('tr');
                data[i].forEach(cell => {
                    const td = document.createElement('td');
                    td.textContent = cell;
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            }
            tableElement.appendChild(tbody);
            
            // Afficher le tableau
            const outputDiv = document.getElementById('excel-output');
            outputDiv.appendChild(tableElement);
        })
        .catch(error => {
            // Gestion des erreurs
            document.getElementById('loading').textContent = 'Erreur de chargement des données';
            console.error('Erreur:', error);
        });
});