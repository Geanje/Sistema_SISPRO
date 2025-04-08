var tabla;
var tipo_cambio_dolar = 0.00
//Función que se ejecuta al inicio
function init() {
	// --
	// 	get_exchange_rate();
	mostrarform(false);
	listar();
	// $('#impuesto').prop('disabled',true);
	//document.getElementById('impuesto').readOnly=true;

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});
	//Cargamos los items al select proveedor
	$.post("../ajax/ticket.php?op=selectCliente", function (r) {
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
	});


	$.post("../ajax/ticket.php?op=selectTipoComprobante", function (r) {
		$('#codigotipo_comprobante').html(r);
		$('#codigotipo_comprobante').selectpicker('refresh');

	});

	$.post("../ajax/ticket.php?op=selectMoneda", function (r) {
		$('#moneda').html(r);
		$('#moneda').selectpicker('refresh');

	});

	$.post("../ajax/ticket.php?op=selecTipoIGV", function (r) {
		$('#impuesto').html(r);
		$('#impuesto').selectpicker('refresh');

	});


	$("#idcliente").change(rellenarCliente);

}

// -- 
function get_exchange_rate() {
	// --
	/*$.ajax({
		url: "../ajax/ticket.php?op=get_exchange_rate",
		type: "POST",
		cache: false,
		dataType: 'json',
		success: function(data) {
			// --
			if (data.status === 'OK') {
			   // --
			   tipo_cambio_dolar = data.data
			}
		}
	});*/
}

function rellenarCliente() {
	var clientee = $("#idcliente").val();

	var idcliente = $("#idcliente").prop("selected", true);
	if (idcliente) {
		// console.log(clientee);

		$.post("../ajax/ticket.php?op=mostrarDatoCliente", { idcliente: clientee }, function (data) {
			data = JSON.parse(data);

			$("#numdireccion").val(data.num_documento);
			$("#direccioncliente").val(data.direccion);

		});
		// $("#idcliente").val(data.idcliente);
	}
}

function LPad(ContentToSize, PadLength, PadChar) {
	var PaddedString = ContentToSize.toString();
	for (var i = ContentToSize.length + 1; i <= PadLength; i++) {
		PaddedString = PadChar + PaddedString;
	}
	return PaddedString;
}




function limpiar() {
	$("#idcliente").val("");
	$("#cliente").val("");
	$("#serie").val("");
	$("#correlativo").val("");
	// 	$("#impuesto").val("");


	$("#total_venta_gravado").val("");
	$("#totalg").html("0.00");
	$("#total_venta_exonerado").val("");
	// $("#totale").html("0.00");
	$("#total_venta_inafectas").val("");
	// $("#totali").html("0.00");
	$("#total_venta_gratuitas").val("");
	// $("#totalgt").html("0.00");
	$("#total_descuentos").val("");
	// $("#totald").html("0.00");
	$("#isc").val("");
	// $("#totalisc").html("0.00");

	$("#tipo_cambio").val("");
	$("#tipo_cambio").html("0.00");
	$("#total_igv").val("");
	$("#totaligv").html("0.00");

	$("#total_importe").val("");
	$(".filas").remove();
	$("#totalimp").html("0.00");

	$("#numdireccion").val("");
	$("#direccioncliente").val("");
	//Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear() + "-" + (month) + "-" + (day);
	$('#fecha_hora').val(today);

	//Marcamos el primer tipo_documento
	$("#tipo_comprobante").val("Boleta");
	$("#tipo_comprobante").selectpicker('refresh');
}

//Función mostrar formulario
function mostrarform(flag) {
	// 	br = document.getElementById("impuesto").value;
	// 	const igv_asig=document.getElementById("igv_asig");
	// 	igv_asig.value=br;

	limpiar();
	if (flag) {
		// 		impuesto=br;//😀

		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		$("#btncliente").hide();
		$("#btnPDF").hide();
		listarArticulos();

		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").show();
		detalles = 0;
	}
	else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
		$("#btncliente").show();
		$("#btnPDF").show();
	}
}

//Función cancelarform
function cancelarform() {
	limpiar();
	mostrarform(false);
}

//Función Listar
function listar() {
	tabla = $('#tbllistado').dataTable(
		{
			"aProcessing": true,//Activamos el procesamiento del datatables
			"aServerSide": true,//Paginación y filtrado realizados por el servidor
			dom: 'Bfrtip',//Definimos los elementos del control de tabla
			buttons: [
				'copyHtml5',
				'excelHtml5',
			],
			"ajax":
			{
				url: '../ajax/ticket.php?op=listar',
				type: "get",
				dataType: "json",
				error: function (e) {
					console.log(e.responseText);
				}
			},
			"bDestroy": true,
			"iDisplayLength": 9,//Paginación
			"order": [[0, "desc"]]//Ordenar (columna,orden)
		}).DataTable();
}


//Función ListarArticulos
function listarArticulos() {
	tabla = $('#tblarticulos').dataTable(
		{
			"aProcessing": true,//Activamos el procesamiento del datatables
			"aServerSide": true,//Paginación y filtrado realizados por el servidor
			dom: 'Bfrtip',//Definimos los elementos del control de tabla
			buttons: [

			],
			"ajax":
			{
				url: '../ajax/ticket.php?op=listarArticulosVenta',
				type: "get",
				dataType: "json",
				error: function (e) {
					console.log(e.responseText);
				}
			},
			"bDestroy": true,
			"iDisplayLength": 5,//Paginación
			"order": [[0, "desc"]]//Ordenar (columna,orden)
		}).DataTable();
	// --
	$("#tipo_cambio").text(tipo_cambio_dolar)
	$("input[name=tipo_cambio]").val(tipo_cambio_dolar)
}




//Función para guardar o editar


function guardaryeditar(e) {
	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/ticket.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (datos) {
			// bootbox.alert(datos);
			if (datos != "" || datos != null) {
				swal({
					title: "BIEN!",
					text: "¡" + datos + "!",
					type: "success",
					confirmButtonText: "Cerrar",
					closeOnConfirm: true
				},

					function (isConfirm) {

						if (isConfirm) {
							// history.back();
							mostrarform(false);
							listar();
						}
					});

			} else {
				swal({
					title: "Error!",
					text: "¡Ocurrio un error, por favor registre nuevamente la venta!",
					type: "warning",
					confirmButtonText: "Cerrar",
					closeOnConfirm: true
				},

					function (isConfirm) {

						if (isConfirm) {
							location.reload(true);
						}
					});
			}



		}

	});
	limpiar();
}

function mostrar(idventa) {
	$.post("../ajax/ticket.php?op=mostrar", { idventa: idventa }, function (data, status) {
		data = JSON.parse(data);
		// mostrar cliente
		$.post("../ajax/ticket.php?op=mostrarDatoCliente", { idcliente: data.idcliente }, function (data) {
			data = JSON.parse(data);

			$("#numdireccion").val(data.num_documento);
			$("#direccioncliente").val(data.direccion);

		});

		$("#idcliente").val(data.idcliente);
		$("#idcliente").selectpicker('refresh');
		$("#codigotipo_comprobante").val(data.codigotipo_comprobante);
		$("#codigotipo_comprobante").selectpicker('refresh');
		$("#serie").val(data.serie);
		$("#correlativo").val(data.correlativo);
		$("#fecha_hora").val(data.fecha);
		$("#impuesto").val(data.impuesto);
		$("#moneda").val(data.moneda);
		$("#idventa").val(data.idventa);
		$("#tipo_cambio").val(data.tipo_cambio);
		$("#total_venta_gravado").val(addCommas(data.total_venta_gravado));
		$("#total_venta_exonerado").val(addCommas(data.total_venta_exonerado));
		$("#total_venta_inafectas").val(addCommas(data.total_venta_inafectas));
		$("#total_venta_gratuitas").val(addCommas(data.total_venta_gratuitas));
		$("#isc").val(addCommas(data.isc));
		$("#total_importe").val(addCommas(data.total_venta));
		$("#moneda").val(data.idmoneda);
		$("#moneda").selectpicker('refresh');
		// 		$("#igv_asig").val(data.igv_asig);



		//Ocultar y mostrar los botones
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
	});

	$.post("../ajax/ticket.php?op=listarDetalle&id=" + idventa, function (r) {
		$("#detalles").html(r);
	});

	mostrarform(true);


}

//Función para anular registros
function anular(idventa) {
	bootbox.confirm("¿Está Seguro de anular la venta?", function (result) {
		if (result) {
			$.post("../ajax/ticket.php?op=anular", { idventa: idventa }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
	limpiar();
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
//var impuesto=18;
var cont = 0;
var detalles = 0;
//$("#guardar").hide();
$("#btnGuardar").hide();



$("#codigotipo_comprobante").change(marcarImpuesto);

function marcarImpuesto() {
	var tipo_comprobante = $("#codigotipo_comprobante").val();
	if (tipo_comprobante != '1') {
		$("#impuesto").val("0");
		// document.getElementById('impuesto').readOnly=true;
		// $("#impuesto").attr("readOnly","readonly");
	}
	else {
		$("#impuesto").val(impuesto);
		// document.getElementById('impuesto').readOnly=true;

		// $("#impuesto").attr("readOnly","readonly");
	}
}

function agregarDetalle(idarticulo, articulo, unidad_medida, precio_venta, afectacion, codigo) {

	// if(unidad_medida=='otros'){
	// 	var unidadm = descripcion_otros;
	// }else{
	// 	var unidadm = unidad_medida;
	// }
	var cantidad = 1;
	var descuento = 0;
	if (idarticulo != "") {



		if (afectacion == 'Exonerado') {
			// var subtotal=cantidad*precio_venta;
			var valorVentaU = precio_venta;
			var valorVentaT = precio_venta * cantidad;
			var igv = 0;
		} else if (afectacion == 'Gravado') {
			var valorVentaU = precio_venta / (1 + (impuesto / 100));
			var valorVentaT = valorVentaU * cantidad - descuento;
			var precioSinIgv = subtotal / (1 + (impuesto / 100));
			var igv = precioSinIgv * (impuesto / 100);
		}
		var subtotal = cantidad * precio_venta;

		var fila = '<tr class="filas" id="fila' + cont + '">' +
			'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
			'<td><input type="hidden" name="codigo[]" id="codigo' + cont + '" value="' + codigo + '" class="form-control">' + codigo + '</td>' +
			'<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
			'<td><input type="hidden" name="afectacio[]" value="' + afectacion + '"><input type="text" name="serieArticulo[]" id="serieArticulo" onkeypress="return Especial(event)" onpaste="stopPegado(event)"></td>' +
			'<td><input type="hidden" name="unidad_medida[]" value="">' + unidad_medida + '</td>' +
			'<td><input type="number" name="cantidad[]" id="cantidad' + cont + '" min="0" value="' + cantidad + '" style="width:50px"></td>' +
			// 
			'<td><span name="valor_venta_u" id="valor_venta_u' + cont + '" >' + valorVentaU.toFixed(2) + '</span><input type="hidden" name="descuento[]" id="descuento' + cont + '" step="0.01" value="' + descuento + '"></td>' +
			// 
			'<td><span name="impuest" id="impuest' + cont + '" >' + igv.toFixed(2) + '</span></td>' +
			// '<td><input type="hidden" name="impuestoo[]" value="'+igv.toFixed(2)+'"><span name="impuest" id="impuest'+cont+'" >'+igv.toFixed(2)+'</span></td>'+
			// EDITAR PRECIO DE VENTA 
			//'<td><input type="number" name="precio_venta[]" step="0.01" min="0" id="precio_venta'+cont+'" value="'+precio_venta+'" style="width:90px; text-align: right;" readonly></td>'+
			'<td><input type="number" name="precio_venta[]" step="0.01" min="0" id="precio_venta' + cont + '" value="' + precio_venta + '" style="width:90px; text-align: right;"></td>' +
			// 
			'<td style="width:90px; text-align: right;"><span name="valor_venta_t" id="valor_venta_t' + cont + '" >' + valorVentaT.toFixed(2) + '</span></td>' +
			// '<td><input type="hidden" name="valor_venta_total[]" value="'+valorVentaT.toFixed(2)+'"><span name="valor_venta_t" id="valor_venta_t'+cont+'" >'+valorVentaT.toFixed(2)+'</span></td>'+

			'<td style="width:115px; text-align: right;"><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +

			'</tr>';

		detalles = detalles + 1;
		$('#detalles').append(fila);

		$("#cantidad" + cont).keyup(function () {
			modificarSubtotales();
		});
		$("#cantidad" + cont).change(function () {
			modificarSubtotales();
		});

		$("#descuento" + cont).keyup(modificarSubtotales);
		$("#descuento" + cont).change(function () {
			modificarSubtotales();
		});

		$("#precio_venta" + cont).keyup(modificarSubtotales);
		$("#precio_venta" + cont).change(function () {
			modificarSubtotales();
		});
		cont++;
		modificarSubtotales();
		// '<td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+


	}
	else {
		alert("Error al ingresar el detalle, revisar los datos del artículo");
	}
	limiteArticulo();
}

function limiteArticulo() {
	if (cont == 27) {

		$(".dataTables_wrapper").hide();
		$("#btnAgregarArt").hide();
		swal({
			title: "Ha alcanzado el limite de articulos.\n�0�3Por favor realice otro Comprobante!",
			//text: "�0�3"+datos+"!",
			type: "warning",
			confirmButtonText: "Cerrar",
			closeOnConfirm: true

		});

	}
}

function Especial(e) {
	//alert("Hola");
	//stopPegado(event);
	key = e.keyCode || e.which;
	tecla = String.fromCharCode(key).toString();
	letraespecial = "QWERTYUIOPASDFGHJKLZXCVBNMÑqw¨¨ertyuiopasdfghjklñzxcvbnm1234567890@#,;$_&-+(.)/*¿':;!?~`\•√π÷×¶∆£¢€¥^°={}%©®™✓[]";
	especiales = [8, 13, 32, 34];
	tecla_especial = false;
	for (var i in especiales) {
		if (key == especiales[i]) {
			tecla_especial = true;
			//alert(tecla_especial)
			//detectorPegado();
			break;

		}
	}

	//detectorPegado();
	if (letraespecial.indexOf(tecla) == -1 && !tecla_especial) {
		//document.getElementById("serieArticulo").value="";
		//alert ("Obedece TILIN");
		alert("Ese Caracter no está permitido")
		//document.getElementById("serieArticulo").value="";
		return false;
	}

}

function stopPegado(e) {
	//console.log('Aqui no esta permitido pegar 😣',e);
	e.preventDefault();
	//e.stopPropapagation();
}
function modificarSubtotales() {
	var cant = document.getElementsByName("cantidad[]");
	var desc = document.getElementsByName("descuento[]");
	var imp = document.getElementsByName("impuest[]");
	var prec = document.getElementsByName("precio_venta[]");
	var sub = document.getElementsByName("subtotal");
	var afec = document.getElementsByName("afectacio[]");

	// var valorventau = document.getElementsByName("valor_venta_unitario[]");
	// var impues = document.getElementsByName("impuestoo[]");
	// var valorventat = document.getElementsByName("valor_venta_total[]");



	var total = 0.0;
	var totaligv = 0.0;
	var totaldesc = 0.0;
	var totalgravad = 0.0;
	var totalexoner = 0.0;
	var newigv = 0;
	for (var i = 0; i < cant.length; i++) {
		var inpC = cant[i];
		var inpD = desc[i];
		var inpI = imp[i];
		var inpP = prec[i];
		var inpS = sub[i];
		var inpA = afec[i];

		var impuesto = document.getElementById("impuesto").value;
		var igv_asig = document.getElementById("igv_asig");
		igv_asig.value = impuesto;

		// var inVentaU =valorventau[i];
		// var inImp =impues[i];
		// var inVentaT =valorventat[i];
		// console.log(inpA.value);

		if (inpA.value == 'Exonerado') {
			var newValorU = inpP.value;
			var newValorT = inpP.value * inpC.value;
			newigv = 0;
		} else if (inpA.value == 'Gravado') {
			var newValoU = inpP.value / (1 + (impuesto / 100));
			var newValorU = newValoU.toFixed(2);
			var newValorT = (inpP.value / (1 + (impuesto / 100))) * inpC.value - inpD.value;
			newigv = (inpC.value * inpP.value / (1 + (impuesto / 100)) - inpD.value) * (impuesto / 100);
		}

		// console.log(inpA);
		inpS.value = (inpC.value * inpP.value) - inpD.value;
		// document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
		// var newigv= (inpC.value*inpP.value)*(1+(impuesto/100))*(impuesto/100);

		// var rr = (inpC.value*inpP.value);
		document.getElementsByName("impuest")[i].innerHTML = newigv.toFixed(2);
		document.getElementsByName("valor_venta_t")[i].innerHTML = newValorT.toFixed(2);
		document.getElementsByName("valor_venta_u")[i].innerHTML = newValorU;
		document.getElementsByName("subtotal")[i].innerHTML = addCommas((inpS.value).toFixed(2));


		// inVentaU.value(newValorU);
		// inImp.value(newigv.toFixed(2));
		// inVentaT.value(newValorT.toFixed(2));


		if (inpA.value == 'Exonerado') {
			totalexoner += parseFloat(newValorT.toFixed(2) - inpD.value);
		} else {
			totalgravad += parseFloat(newValorT.toFixed(2));
		}
		// totalgravad += parseFloat(newValorT.toFixed(2));



		totaldesc += parseFloat(inpD.value);
		totaligv += parseFloat(newigv.toFixed(2));
		total += inpS.value;


	}

	//$('#totalg').html("S/ " + addCommas(totalgravad.toFixed(2)));
	$('#totalg').html(" " + addCommas(totalgravad.toFixed(2)));
	$('#total_venta_gravado').val(totalgravad.toFixed(2));

	// $('#totale').html("S/. " + addCommas(totalexoner));
	$('#total_venta_exonerado').val(totalexoner);

	// $('#totald').html("S/. " + addCommas(totaldesc));
	$('#total_descuentos').val(totaldesc);

	// $('#totaligv').html("S/" + addCommas(totaligv.toFixed(2)));
	$('#totaligv').html(" " + addCommas(totaligv.toFixed(2)));
	$('#total_igv').val(totaligv.toFixed(2));

	//$("#totalimp").html("S/ " + addCommas(total.toFixed(2)));
	$("#totalimp").html(" " + addCommas(total.toFixed(2)));
	$("#total_importe").val(total.toFixed(2));

	// --


	//    // --
	if (tipo_cambio_dolar != 0.00) {
		// --
		$("#total_soles").html("S/. " + (total * tipo_cambio_dolar).toFixed(2))

		$("input[name=total_soles]").val("S/. " + (total * tipo_cambio_dolar).toFixed(2))
	}


	evaluar();

	// calcularTotales();
}

// --


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


function calcularTotales() {
	/*var sub = document.getElementsByName("subtotal");
	var impues =document.getElementsByName("valor_venta_u");
	var impuestoTotal=0.0;
	var total = 0.0;

	for (var i = 0; i <sub.length; i++) {
	total += document.getElementsByName("subtotal")[i].value;
}
$('#totaligv').html(impuestoTotal);
$("#totalimp").html("S/. " + total);
$("#total_importe").val(impuestoTotal);*/
	evaluar();
}

function evaluar() {
	if (detalles > 0) {
		$("#btnGuardar").show();
	}
	else {
		$("#btnGuardar").hide();
		cont = 0;
	}
}

function eliminarDetalle(indice) {
	$("#fila" + indice).remove();
	modificarSubtotales();
	detalles = detalles - 1;
	evaluar()
}

init();
