document.addEventListener('DOMContentLoaded', function() {
    // Obtenir la date actuelle
    const today = new Date();

    // Tableaux des jours de la semaine et des mois
    const daysOfWeek = ['DIMANCHE', 'LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI', 'SAMEDI'];
    const monthsOfYear = ['JANVIER', 'FÉVRIER', 'MARS', 'AVRIL', 'MAI', 'JUIN', 'JUILLET', 'AOÛT', 'SEPTEMBRE', 'OCTOBRE', 'NOVEMBRE', 'DÉCEMBRE'];

    // Obtenir le jour de la semaine et le mois
    const dayOfWeek = daysOfWeek[today.getDay()];
    const day = today.getDate();
    const month = monthsOfYear[today.getMonth()];
    const year = today.getFullYear();

    const formattedDate = `${dayOfWeek} ${day} ${month} ${year}`;

    // Sélectionner tous les éléments avec l'ID 'date'
    const dateElements = document.querySelectorAll('#date');
    
    // Mettre à jour chaque élément trouvé
    dateElements.forEach(element => {
        element.textContent = formattedDate;
    });
});

const hamMenu = document.querySelector('.ham-menu');
const offScreenMenu = document.querySelector('.off-screen-menu');

hamMenu.addEventListener("click", function () {
    hamMenu.classList.toggle("active");
    offScreenMenu.classList.toggle("active");
});

document.getElementById("open-calendar").addEventListener("click", function() {
    document.getElementById("date-picker").showPicker();
});

document.getElementById("date-picker").addEventListener("change", function() {
    document.getElementById("selected-date").innerText = "Date sélectionnée : " + this.value;
});
