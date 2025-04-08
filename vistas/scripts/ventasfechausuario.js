var tabla;
function init() {

	listar();
	$.post("../ajax/venta2.php?op=selectUsuario", function (r) {
		$("#idusuario").html(r);
		$('#idusuario').selectpicker('refresh');

	});

	$.post("../ajax/consultas.php?op=selectTipoPago", function (r) {
		$('#codigotipo_pago').html(r);
		$('#codigotipo_pago').selectpicker('refresh');

	});
	$.post("../ajax/venta2.php?op=selectTipoComprobanteReporteU", function (r) {
		$('#codigotipo_comprobante').html(r);
		$('#codigotipo_comprobante').selectpicker('refresh');

	});
	$("#fecha_inicio").change(listar);
	$("#fecha_fin").change(listar);
	$('#idusuario').change(listar);
	$('#codigotipo_pago').change(listar);
	$('#codigotipo_comprobante').change(listar);
	var horai = $("#hora_inicio").val();
	var horaf = $("#hora_fin").val();
	if (horai != null && horaf != null) {
		$("#hora_inicio").change(listar);
		$("#hora_fin").change(listar);
	}
}

function listar() {
	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
	var hora_inicio = $("#hora_inicio").val();
	var hora_fin = $("#hora_fin").val();
	var idusuario = $("#idusuario").val();
	var codigotipo_comprobante = $("#codigotipo_comprobante").val();
	var codigotipo_pago = $("#codigotipo_pago").val();


	tabla = $("#tbllistado").dataTable(
		{
			"aProcessing": true, //Activamos el procesamiento de datatables
			"aServerSide": true, //Paginacion y filtrado realizados por el servidor
			dom: 'Bfrtip', //Definimos los elementos del control de tabla
			buttons: [
				/*'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdf'*/
				{
					extend: 'copyHtml5',
					exportOptions: {
						columns: [0, ':visible']
					}
				},
				{
					extend: 'excelHtml5',
					exportOptions: {
						columns: ':visible'
					}
				},
				{
					extend: 'pdfHtml5',
					exportOptions: {
						columns: ':visible'
					}
				},
				'colvis'

			],
			"ajax": {
				url: "../ajax/consultas.php?op=ventasFechaUsuario",
				data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, hora_inicio: hora_inicio, hora_fin: hora_fin, idusuario: idusuario, codigotipo_pago: codigotipo_pago, codigotipo_comprobante: codigotipo_comprobante },
				type: "get",
				dataType: "json",
				error: function (e) {
					console.log(e.responseText);
				}
			},
			"languaje": {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sSearch": "Buscar:",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			},
			"bDestroy": true,
			"iDisplayLength": 10, //Paginacion
			"order": [[0, "desc"]] //Ordenar (columna,orden)
		}
	).DataTable();
	$.post("../ajax/consultas.php?op=sumVentasFechaUsuario", { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, hora_inicio: hora_inicio, hora_fin: hora_fin, idusuario: idusuario, codigotipo_pago: codigotipo_pago, codigotipo_comprobante: codigotipo_comprobante }, function (data, status) {
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