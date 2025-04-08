$("#frmAcceso").on("submit", function (e) {
  e.preventDefault();
  var checkbox = document.getElementsByClassName("g-recaptcha-response")[0];
  if (checkbox.value.length > 0) {
    logina = $("#logina").val();
    clavea = $("#clavea").val();

    $.post(
      "../ajax/usuario.php?op=verificar",
      { logina: logina, clavea: clavea },
      function (data) {
        // console.log(data);
        try {
          var response = JSON.parse(data);

          if (response.success) {
            $(location).attr("href", "escritorio.php");
          } else {
            swal({
              title: "Advertencia",
              text: response.message,
              icon: "error",
              button: "OK",
            });
          }
        } catch (error) {
          console.error("Error al procesar la respuesta JSON:", error);
        }
      }
    );
  } else {
    var errorMessage = document.getElementById("error-message");
    errorMessage.style.display = "block";
    return false;
  }
});
