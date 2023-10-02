$(document).ready(function () {
    $('#dateDeb, #dateFin').on('change', function() {
        // Lorsque l'utilisateur modifie les champs de date
        $('#soumission').val('Soumettre');
        $('#soumission').prop('type', 'button');
    });
    // Fonction déclenchée lorsque l'utilisateur clique sur le bouton avec l'ID "soumission"
    $('#soumission').on('click', function (event) {
        // event.preventDefault();
        // Obtenez les valeurs des champs de date
        var dateDebut = $('#dateDeb').val();
        var dateFin = $('#dateFin').val();
        var motif = $('#motif').val();

        // Vérifiez si les deux champs de date sont remplis
        if (dateDebut !== '' && dateFin !== '' && motif !== '') {
            // Effectuez la requête Ajax
            $.ajax({
                url: 'php/chevauchement.php', // Remplacez par le chemin de votre script PHP
                type: 'POST',
                data: { dateDebut: dateDebut, dateFin: dateFin },
                success: function (result) {
                    if (result === 'afficher') {
                        // Affichez le div si le script PHP renvoie 'afficher'
                        $('#Rem').show();
                        $('#soumission').val('Soumettre quand même');
                        $('#soumission').prop('type', 'submit');
                    } else {
                        // Masquez le div sinon
                        $('#Rem').hide();
                        $('#soumission').prop('type', 'submit');
                    }
                }
            });
        } else {
            // Si l'un des champs est vide, masquez le div
            $('#Rem').hide();
        }
    });
});
