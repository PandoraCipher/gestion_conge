$(document).ready(function () {
  $(".btnShow").on("click", function (e) {
    e.preventDefault();
    var groupId = $(this).data("group-id");
    $.ajax({
      url: "php/listeGroupe.php",
      type: "post",
      data: { groupId: groupId },
      success: function (result) {
        $("#data" + groupId).html(result);
      },
    });
  });

  $(document).on("click", ".btnDel", function (e) {
    e.preventDefault();
    var groupId = $(this).data("group-id");
    var agentId = $(this).data("agent-id");

    $.ajax({
      url: "php/SupprAgentGroupe.php",
      type: "post",
      data: { groupId: groupId, agentId: agentId },
      success: function (result) {
        console.log(result);
        if (result !== "0" && result !== "") {
          $("#" + result).fadeOut();
          console.log("réussite");
          $.ajax({
            url: "php/listeGroupe.php",
            type: "post",
            data: { groupId: groupId },
            success: function (result) {
              $("#data" + groupId).html(result);
            },
          });
        } else {
          console.log("echec");
        }
      },
    });
  });

  $(".btnAdd").on("click", function (e) {
    e.preventDefault();
    var groupId = $(this).data("group-id");
    var selectedValue = $("#selectAgent" + groupId).val();

    $.ajax({
      url: "php/AjoutAgentGroupe.php",
      type: "post",
      data: { groupId: groupId, selectedValue: selectedValue },
      success: function (result) {
        if (result == 1) {
          $.ajax({
            url: "php/listeGroupe.php",
            type: "post",
            data: { groupId: groupId },
            success: function (result) {
              $("#data" + groupId).html(result);
            },
          });
        } else {
          alert(result);
        }
      },
    });
  });
});
