var tablal;
var tabla;

//Función que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar();
	listarComprobantes();


	$.post("../ajax/notaCredito.php?op=selectTipoNotaCredito", function (r) {
		$('#tipoNotaC').html(r);
		$('#tipoNotaC').selectpicker('refresh');
	});

	$("#detalleConcepto").on("submit", function (e) {
		guardaryeditar(e);
	});



}

function enviarDatosASunat(nombre_archivo, idventa) {
	$.ajax({
		type: "POST",
		url: "../ajax/xml_sunat.php",
		data: {
			nombre_archivo: nombre_archivo,
			idventa: idventa
		},
		success: function (response) {

			swal({
				title: "BIEN!",
				text: "¡" + response + "!",
				type: "success",
				confirmButtonText: "Cerrar",
				closeOnConfirm: true
			});
		},
		error: function (error) {
			swal({
				title: "Error!",
				text: "¡" + error + "!",
				type: "warning",
				confirmButtonText: "Cerrar",
				closeOnConfirm: true
			});
		}
	});
}




function limpiar() {
	$("#idcliente").val("");
	$("#cliente").val("");
	// $("#serie").val("");
	// $("#correlativo").val("");
	// $("#impuesto").val("18");


	// $("#total_venta_gravado").val("");
	// $("#totalg").html("0.00");
	// $("#total_venta_exonerado").val("");
	// $("#totale").html("0.00");
	// $("#total_venta_inafectas").val("");
	// $("#totali").html("0.00");
	// $("#total_venta_gratuitas").val("");
	// $("#totalgt").html("0.00");
	// $("#total_descuentos").val("");
	// $("#totald").html("0.00");
	// $("#isc").val("");
	// $("#totalisc").html("0.00");
	// $("#total_igv").val("");
	// $("#totaligv").html("0.00");

	// $("#total_importe").val("");
	// $(".filas").remove();
	// $("#totalimp").html("0.00");


	//Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear() + "-" + (month) + "-" + (day);
	$('#fecha_hor').val(today);

	//Marcamos el primer tipo_documento
	$("#tipo_comprobante").val("Boleta");
	$("#tipo_comprobante").selectpicker('refresh');
}

//Función mostrar formulario
function mostrarform(flag) {
	limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		$("#btnaescoger").hide();
		// listarArticulos();

		$("#btnGuardar").show();
		$("#btnCancelar").show();
		$("#btnAgregarArt").show();

	}
	else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
		$("#btnaescoger").show();
		$("#listaCompro1").text("");


	}
}

//Función cancelarform
function cancelarform() {
	// limpiar();
	mostrarform(false);
	// $.post("../ajax/notaCredito.php?op=unsetsession",function(data){
	//    		console.log(data);
	//    	});
	location.reload(true);


}

function anular(idventa) {

	bootbox.confirm("¿Está Seguro de anular la nota de crédito?", function (result) {
		if (result) {
			$.post("../ajax/notaCredito.php?op=anular", { idventa: idventa }, function (e) {
				bootbox.alert(e);
				tablal.ajax.reload();
			});
		}
	})
	// limpiar();
}



function mostrarDocRel(idventa, idventarelacionado) {
	$.post("../ajax/notaCredito.php?op=mostrarDocRel", { idventa: idventa, idventarelacionado: idventarelacionado }, function (data, status) {
		data = JSON.parse(data);
		$("#fechar").html(data.fecha);
		$("#clienter").html(data.cliente);
		$("#usuarior").html(data.usuario);
		$("#documentor").html(data.serie + '-' + data.correlativo);
		$("#tiponcr").html(data.tipoNotaV);
		$("#sustentor").html(data.sustento);


	});

}
//Función Listar
function listar() {
	tablal = $('#tbllistado').dataTable(
		{
			"aProcessing": true,//Activamos el procesamiento del datatables
			"aServerSide": true,//Paginación y filtrado realizados por el servidor
			dom: 'Bfrtip',//Definimos los elementos del control de tabla
			buttons: [
				// 'copyHtml5',
				// tbllistado'excelHtml5',
				// 'csvHtml5',
				// 'pdf'
			],
			"ajax":
			{
				url: '../ajax/notaCredito.php?op=listar',
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

function listarComprobantes() {
	tabla = $('#tblcomprobantes').dataTable(
		{
			"aProcessing": true,//Activamos el procesamiento del datatables
			"aServerSide": true,//Paginación y filtrado realizados por el servidor
			dom: 'Bfrtip',//Definimos los elementos del control de tablaa
			buttons: [
				// 'copyHtml5',
				// 'excelHtml5',
				// 'csvHtml5',
				// 'pdf'
			],
			"ajax":
			{
				url: '../ajax/notaCredito.php?op=listarComprobantes',
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



function guardaryeditar(e) {
	e.preventDefault();
	// var idventa= $("#idventa").val();
	// var idcliente= $("#idclientee").val();
	// var serie= $("#serie").val();
	// var correlativo= $("#correlativo").val();
	// var impuesto= $("#impuesto").val();
	// var total_venta_gravado= $("#total_venta_gravado").val();
	// var total_venta_inafectas= $("#total_venta_inafectas").val();
	// var total_venta_exonerado= $("#total_venta_exonerado").val();
	// var total_venta_gratuitas= $("#total_venta_gratuitas").val();
	// var isc= $("#isc").val();
	// var total_importe= $("#total_importe").val();
	// var idmoneda= $("#idmoneda").val();
	// var idtiponotacredito= $("#idtiponotacredito").val();
	// var sustento= $("#sustento").val();
	// var idventarelacionado= $("#idventarelacionado").val();

	//    idarticulo = document.getElementsByName("idarticulo[]");
	//  cantidad = document.getElementsByName("cantidad[]");
	//    precio_venta = document.getElementsByName("precio_venta[]");
	//  descuento = document.getElementsByName("descuento[]");


	var formData = new FormData($("#detalleConcepto")[0]);

	// var cade=$("#detalleConcepto").serialize()
	// var cadena = "idventa="+idventa+"&idcliente="+idcliente+"&serie="+serie+"&correlativo="+correlativo+"&impuesto="+impuesto+"&total_venta_gravado="+total_venta_gravado+"&total_venta_inafectas="+total_venta_inafectas+"&total_venta_exonerado="+total_venta_exonerado+"&total_venta_gratuitas="+total_venta_gratuitas+"&isc="+isc+"&total_importe="+total_importe+"&idmoneda="+idmoneda+"&idtiponotacredito="+idtiponotacredito+"&sustento="+sustento+"&idventarelacionado="+idventarelacionado+"&idarticulo="+idarticulo+"&cantidad="+cantidad+"&precio_venta="+precio_venta+"&descuento="+descuento;
	// var cadena = "idventa="+idventa+"&idcliente="+idcliente+"&serie="+serie+"&correlativo="+correlativo+"&impuesto="+impuesto+"&total_venta_gravado="+total_venta_gravado+"&total_venta_inafectas="+total_venta_inafectas+"&total_venta_exonerado="+total_venta_exonerado+"&total_venta_gratuitas="+total_venta_gratuitas+"&isc="+isc+"&total_importe="+total_importe+"&idmoneda="+idmoneda+"&idtiponotacredito="+idtiponotacredito+"&sustento="+sustento+"&idventarelacionado="+idventarelacionado;
	// console.log(formData);
	$.ajax({
		url: "../ajax/notaCredito.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,


		success: function (datos) {
			console.log(datos);
			// bootbox.alert(datos);
			if (datos == "Venta registrada" || datos == "No se pudieron registrar todos los datos de la venta") {
				bootbox.alert(datos);
			} else {
				bootbox.alert("Venta registra");
			}
			mostrarform(false);
			listar();
		}


	});
	// limpiar();

}




function agregarTipoNota3(cont, articulo) {
	$("#cont").val(cont);
	$("#dice").val(articulo);
}

function limpiarAgregarTipoNota3() {

	var acumu = $("#cont").val();
	var dicese = $("#dice").val();
	var debedecir = $("#debedecir").val();


	$("#articuloo" + acumu).text("Dice: " + dicese + "-Debe decir: " + debedecir);
	$("#tiponc3" + acumu).val("Dice: " + dicese + "-Debe decir: " + debedecir);
	$("#debedecir").val("");



}


function agregarDocumento(idventarelacionado, codigonota, idcliente, cliente, num_documento, serie, correlativo, idmoneda, descripcionmoneda, descripcion_tipo_comprobante, fecha, op_gravadas, op_exoneradas, venta_total, impuesto, ultimo_correlativo, serie_correlativo) {
	var tiponotacred;
	if (codigonota == '1') {
		tiponotacred = 'Anulacion de la operación';
	} else if (codigonota == '2') {
		tiponotacred = 'Anulación por error en el RUC';
	} else if (codigonota == '3') {
		tiponotacred = 'Corrección por error en la descripción';
	} else if (codigonota == '4') {
		tiponotacred = 'Descuento global';
	} else if (codigonota == '5') {
		tiponotacred = 'Descuento por Item';
	} else if (codigonota == '6') {
		tiponotacred = 'Devolución total'
	} else if (codigonota == '7') {
		tiponotacred = 'Devolución parcial';
	} else if (codigonota == '8') {
		tiponotacred = 'Bonificación';
	} else if (codigonota == '9') {
		tiponotacred = 'Disminución en el valor';
	}

	// var fechaC=fecha.split("-");
	// var fechaD=fechaC[2]+" / "+fechaC[1]+" / "+fechaC[0];
	var conceptos =
		'<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">' +


		'</div>' +

		'<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
		'<div class="alert alert-success" align="center">' +
		'<span style="text-align:center; font-size:large;font-weight: 500;"  >' + tiponotacred + '</span>' +
		'<input type="hidden" name="tiponotacred" value="' + tiponotacred + '">' +
		'</div>' +
		'</div>' +
		'<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">' +


		'</div>' +

		'<div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">' +
		'<h4>Documento que modifica: ' + descripcion_tipo_comprobante + '</h4>' +
		// '<input type="hidden" name="codigotipo_comprobante" value="'+codigotipo_comprobante+'">'+
		'<input type="hidden" name="idventa" id="idventa" >' +
		'<input type="hidden" name="idtiponotacredito" id="idtiponotacredito" value="' + codigonota + '">' +
		'<input type="hidden" name="idventarelacionado" id="idventarelacionado" value="' + idventarelacionado + '">' +
		'<input type="hidden" name="impuesto" id="impuesto" value="' + impuesto + '">' +
		'</div>' +
		//--- COLOCAR LA SERIES Y CORRELATIVOS AUTOMATICAMENTE LUEGO DE AJAX Y MODELO ----//
		'<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
		'<input type="text" class="form-control" maxlength="4" name="serie" id="serie" value="' + serie_correlativo + '" placeholder="serie"  readonly="readonly" required >' +
		'</div>' +
		'<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
		'<div >' +
		'<input type="text" class="form-control" maxlength="8" name="correlativo" value="' + ultimo_correlativo + '"  id="correlativo" placeholder="correlativo" readonly="readonly" required>' +
		'</div>' +
		'</div>' +



		'<div class="form-group col-lg-9 col-md-9 col-sm-9 col-xs-12">' +
		'<label>Cliente(*):</label>' +
		// '<input type="text" class="form-control" name="" id="idcliente" value="'+idcliente+'">'+
		'<input type="hidden" class="form-control" name="idcliente" id="idclientee" value="' + idcliente + '" placeholder="cliente" >' +
		'<input type="text" class="form-control" name="" value="' + cliente + '" placeholder="cliente" readonly="readonly">' +
		'</div>' +
		'<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
		'<label>Fecha(*):</label>' +
		'<input type="date"  class="form-control" name="fecha_hora" id="" value="' + fecha + '" readonly="readonly required="">' +
		'</div>' +
		'<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">' +
		'<label>Número de documento:</label>' +
		'<input type="text" class="form-control" name="doc" id="doc" value="' + num_documento + '" readonly="readonly placeholder="Número de documento">' +
		'</div>' +

		'<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">' +
		'<label>Serie:</label>' +
		'<input type="text" class="form-control" name="" id="" value="' + serie + '" readonly="readonly required="">' +
		'</div>' +
		'<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">' +
		'<label>Correlativo:</label>' +
		'<input type="text" class="form-control" name="" id="" value="' + correlativo + '" readonly="readonly required="">' +
		'</div>' +
		'<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
		'<label>Moneda:</label>' +
		'<input type="hidden" name="idmoneda" id="idmoneda" value="' + idmoneda + '">' +
		'<input  type="text" class="form-control" name="descripcionmoneda"  value="' + descripcionmoneda + '" readonly="readonly placeholder="S/.">' +
		'</div>' +
		'<div class="form-group col-lg-9 col-md-9 col-sm-12 col-xs-12">' +
		'<label>Sustento:</label>' +
		'<input  type="text" class="form-control" name="sustento"  id="sustento" placeholder="Motivo o sustento" required="">' +
		'</div>'
		;
	// console.log(fecha);




	$('#listaCompro1').append(conceptos);
	// $('#formulario').append(conceptos);

	$.post("../ajax/notaCredito.php?op=listarDetalleComprobantes&id=" + idventarelacionado, function (r) {
		$("#listaCompro2").html(r);
	});

	// $.post("../ajax/notaCredito.php?op=unsetsession",function(data){
	//     		console.log(data);
	//     	});

	modificarSubtotales();
}


function modificarSubtotales() {
	var impuesto = 18;
	var cant = document.getElementsByName("cantidadd[]");
	var desc = document.getElementsByName("descuentoo[]");
	// var imp = document.getElementsByName("impuest");
	var prec = document.getElementsByName("precio_ventaa[]");
	var sub = document.getElementsByName("subtotal");
	var afec = document.getElementsByName("afectacio[]");
	var total = 0.0;
	var totaligv = 0.0;
	var totaldesc = 0.0;
	var totalgravad = 0.0;
	var totalexoner = 0.0;
	var newigv = 0;
	for (var i = 0; i < cant.length; i++) {
		var inpC = cant[i];
		var inpD = desc[i];
		// var inpI=imp;
		var inpP = prec[i];
		var inpS = sub[i];
		var inpA = afec[i];
		// console.log(inpA.value);

		if (inpA.value == 'Exonerado') {
			var newValorU = inpP.value;
			var newValorT = inpP.value * inpC.value;
			newigv = 0;
			// }else if(inpA.value=='Gravado'){
		} else {
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
		document.getElementsByName("subtotal")[i].innerHTML = (inpS.value).toFixed(2);

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


	$('#totalg').html("$. " + totalgravad.toFixed(2));
	$('#total_venta_gravado').val(totalgravad.toFixed(2));

	$('#totale').html("$. " + totalexoner);
	$('#total_venta_exonerado').val(totalexoner);

	$('#totald').html("$. " + totaldesc);
	$('#total_descuentos').val(totaldesc);

	$('#totaligv').html("$. " + totaligv.toFixed(2));
	$('#total_igv').val(totaligv.toFixed(2));

	$("#totalimp").html("$. " + total.toFixed(2));
	$("#total_importe").val(total.toFixed(2));
	// evaluar();

}


// function evaluar(){
// 	if (detalles>0)
//   {
//     $("#btnGuardar").show();
//   }
//   else
//   {
//     $("#btnGuardar").hide();
//     cont=0;
//   }
// }

function eliminarDetallee(indice) {
	$(".fila" + indice).remove();
	// document.getElementById("fila"+indice).deleteRow(fila+indice);
	// $("#fila" + indice).attr("disabled",true);
	modificarSubtotales();
	// detalles=detalles-1;
	// evaluar()
	// console.log("yes");
}
function eliminarDetalle3(indice) {
	$(".fila" + indice).remove();
}
function limpiarAlCerrar() {

	$("#listaCompro1").append("");
}

function isession() {
	var isession = $("#tipoNotaC").val();


	$.post("../ajax/notaCredito.php?op=isession", { isession: isession }, function (data) {


	});
	location.reload(true);

	// mostrarform(true);
	// location.href="nota.php";
	// window.location="notaCredito.php";

}

init();
