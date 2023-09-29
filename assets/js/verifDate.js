$(document).ready(function () {
    // Fonction déclenchée lorsque l'utilisateur clique sur le bouton avec l'ID "soumission"
    $('#soumission').on('click', function (event) {
        event.preventDefault();
        // Obtenez les valeurs des champs de date
        var dateDebut = $('#dateDeb').val();
        var dateFin = $('#dateFin').val();

        // Vérifiez si les deux champs de date sont remplis
        if (dateDebut !== '' && dateFin !== '') {
            // Effectuez la requête Ajax
            $.ajax({
                url: 'chevauchement.php', // Remplacez par le chemin de votre script PHP
                type: 'POST',
                data: { dateDebut: dateDebut, dateFin: dateFin },
                success: function (result) {
                    if (result === 'afficher') {
                        // Affichez le div si le script PHP renvoie 'afficher'
                        $('#Rem').show();
                    } else {
                        // Masquez le div sinon
                        $('#Rem').hide();
                    }
                }
            });
        } else {
            // Si l'un des champs est vide, masquez le div
            $('#Rem').hide();
        }
    });
});
