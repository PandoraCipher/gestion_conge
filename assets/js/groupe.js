$(document).ready(function () {

    function showData(groupeId){
        var groupId = $(this).data("group-id");
        $.ajax({
            url: 'listeGroupe.php',
            type: 'post',
            data: {groupId: groupId},
            success: function (result){
                $('#data'+groupId).html(result);
            }
        });
    }
    showData()

    $(".btnAdd").on("click", function (e) {
        e.preventDefault();
        var groupId = $(this).data("group-id");
        var selectedValue = $("#selectAgent" + groupId).val();

        $.ajax({
            url: 'AjoutAgentGroupe.php',
            type: 'post',
            data: { groupId: groupId, selectedValue: selectedValue },
            success: function (result) {
                if (result == 1) {
                    alert('Success');
                    showData(groupId)
                } else {
                    alert(result);
                }
            }
        });
    });
});
