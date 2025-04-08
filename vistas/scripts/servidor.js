
function init() {


	$("#perfil").on("submit", (e) => {
		editar(e);
	});
}

function editar(e) {
	e.preventDefault();

	var formdata = new FormData($("#perfil")[0]);
	$.ajax({
		url: "../ajax/configuracion.php?op=editar_sunat",
		type: "POST",
		data: formdata,
		contentType: false,
		processData: false,
		success: function (data) {
			// bootbox.alert(data);
			$("#resultados_ajax").html('<div class="alert alert-success">' +
				'<button class="close" data-dismiss="alert">&times;</button>' +
				'<strong>¡Bien hecho! </strong>' +
				data +
				'</div>');

		}
	})
}

// En tu archivo JavaScript (archivo.js)
document.addEventListener('DOMContentLoaded', function () {
    // Selecciona el elemento de contraseña
    var passwordInput = document.getElementById('u_secundario_password');

    // Añade un evento para cambiar el tipo de entrada cuando se carga la página
    passwordInput.addEventListener('input', function () {
        ocultarContrasena();
    });

    function ocultarContrasena() {
        // Cambia el tipo de entrada a "password"
        passwordInput.type = 'password';
    }
});


init()