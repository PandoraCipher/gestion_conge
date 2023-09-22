document.getElementById('form-dem').addEventListener('submit', function(event) {
    var dD = document.getElementById('dateDeb');
    var dF = document.getElementById('dateFin');
    var dateDebut = new Date(document.getElementById('dateDeb').value);
    var dateFin = new Date(document.getElementById('dateFin').value);
    var dateActuelle = new Date();

    if (dateDebut > dateActuelle) {
        dD.setCustomValidity('');
        console.log('valide.');
    } else {
        dD.setCustomValidity("La date de début ne peut pas être antérieure à la date actuelle.");
        event.preventDefault(); // Empêche la soumission du formulaire
        console.log('non valide. ' + dateDebut);
    }

    if (dateFin > dateDebut) {
        dF.setCustomValidity('');
        console.log('valide.');
    } else {
        dF.setCustomValidity("La date de fin ne peut pas être antérieure à la date de début.");
        event.preventDefault(); // Empêche la soumission du formulaire
        console.log('non valide. ' + dateFin);
    }
});