var tabla;
function init() {

	listar();
	$.post("../ajax/reporte_notacredito.php?op=selectUsuario", function (r) {
		$("#idusuario").html(r);
		$('#idusuario').selectpicker('refresh');

	});
}

function listar() {
	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
	var idusuario = $("#idusuario").val();
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
				url: "../ajax/reporte_notacredito.php?op=ventasFechaUsuarioNC",
				data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idusuario: idusuario },
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
	$.post("../ajax/reporte_notacredito.php?op=sumVentasFechaUsuarioNC", { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idusuario: idusuario }, function (data, status) {
		//console.log("suma");
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