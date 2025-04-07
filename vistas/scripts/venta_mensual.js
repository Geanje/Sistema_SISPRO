var tabla;
function init() {

	listar();
	$.post("../ajax/venta_mensual.php?op=selectUsuario", function (r) {
		$("#idusuario").html(r);
		$('#idusuario').selectpicker('refresh');

	});
}

function listar() {
	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
	var idusuario = $("#idusuario").val();
	var modulo = $("#modulo").val();

	tabla = $("#tbllistado").dataTable(
		{
			"aProcessing": true, //Activamos el procesamiento de datatables
			"aServerSide": true, //Paginacion y filtrado realizados por el servidor
			dom: 'Bfrtip', //Definimos los elementos del control de tabla
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdf'

			],
			"ajax": {
				url: "../ajax/consultas2.php?op=ventasFechaUsuario",
				data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idusuario: idusuario, modulo: modulo},
				type: "get",
				dataType: "json",
				error: function (e) {
					console.log(e.responseText);
				}
			},
			"bDestroy": true,
			"iDisplayLength": 10, //Paginacion
			"order": [[0, "desc"]] //Ordenar (columna,orden)
		}
	).DataTable();
	$.post("../ajax/consultas2.php?op=sumVentasFechaUsuario", { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idusuario: idusuario, modulo:modulo }, function (data, status) {
		data = JSON.parse(data);
		$("#usuari").text(data.usuario);
		$("#sumventa").text(addCommas(data.sumatotalusuario));

	});
}

function addCommas(nStr) {
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

init();