
function init() {


	$("#perfil").on("submit", (e) => {
		editar(e);
	});

	$("#imagen").change(function () {
		var imagen = this.files[0];

		if (imagen["type"] == "image/jpeg" || imagen['type'] == "image/png") {
			if (Number(imagen["size"]) > 3000000) {
				$("#imagen").val("");
				swal({
					title: "¡Error al subir la imagen!",
					text: "¡La imagen no debe pesar más de 3 MB!",
					type: "error",
					confirmButtonText: "Cerrar",
					closeOnConfirm: false

				})

			} else {
				var datosImagen = new FileReader();
				datosImagen.readAsDataURL(imagen);
				$(datosImagen).on("load", function (e) {
					$("#previsualizar").attr("src", e.target.result);
				})
			}

		} else {
			$("#imagen").val("");

			swal({
				title: "¡Error al subir la imagen!",
				text: "¡La imagen debe estar en formato JPG o PNG!",
				confirmButtonText: "Cerrar",
				type: "error"

			})

		}

	});



}




function editar(e) {
	e.preventDefault();

	var formdata = new FormData($("#perfil")[0]);
	$.ajax({
		url: "../ajax/configuracion.php?op=editar",
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

init()