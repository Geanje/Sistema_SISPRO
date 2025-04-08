var tabla;

//Función que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar();
	// $('#impuesto').prop('disabled',true);

	$("#formulario").on("submit", function (e) {
		guardaryeditar(e);
	});
	//Cargamos los items al select proveedor
	$.post("../ajax/cotizacion.php?op=selectCliente", function (r) {
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
	});


	$.post("../ajax/cotizacion.php?op=selectTipoComprobante", function (r) {
		$('#codigotipo_comprobante').html(r);
		$('#codigotipo_comprobante').selectpicker('refresh');

	});

	$.post("../ajax/cotizacion.php?op=selecTipoIGV", function (r) {
		$('#impuesto').html(r);
		$('#impuesto').selectpicker('refresh');

	});

	$("#idcliente").change(rellenarCliente);//😀
	/*$.post("../ajax/cotizacion.php?op=selectMoneda",function(r){
			$('#moneda').html(r);
			$('#moneda').selectpicker('refresh');

	});
*/
	// $('#gravado').click(function(){
	// 	var pp=$('#tipoGravado').prop("checked",false);
	// var pp=document.getElementById("tipoGravado").prop("checked",true);
	// })
}

function rellenarCliente() {//😀
	var idcliente = $("#idcliente").val();

	var cliente = $("#idcotizacion").prop("selected", true);
	if (cliente) {
		$.post("../ajax/cotizacion.php?op=mostrarDatoCliente", { idcliente: idcliente }, function (data) {
			data = JSON.parse(data);

			$("#numdireccion").val(data.num_documento);
			$("#direccioncliente").val(data.direccion);

		});
	}
}
//Función limpiar
function limpiar() {
	$("#idcliente").val("");
	$("#cliente").val("");
	$("#serie").val("");
	$("#correlativo").val("");
	//$("#impuesto").val("");


	$("#precio_venta").val("");
	$("#totalg").html("0.00");
	$("#total_venta_exonerado").val("");
	$("#totale").html("0.00");
	$("#total_venta_inafectas").val("");
	$("#totali").html("0.00");
	$("#total_venta_gratuitas").val("");
	$("#totalgt").html("0.00");
	$("#total_descuentos").val("");
	$("#totald").html("0.00");
	$("#isc").val("");
	$("#totalisc").html("0.00");
	$("#igv_total").val("");//total_igv
	$("#totaligv").html("0.00");

	$("#numdireccion").val("");//😀
	$("#direccioncliente").val("");

	$("#total_importe").val("");
	$(".filas").remove();
	$("#totalimp").html("0.00");

	$("#referencia").val("");
	$("#validez").val("");




	//Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear() + "-" + (month) + "-" + (day);
	$('#fecha_hora').val(today);

	//Marcamos el primer tipo_documento
	// $("#tipo_comprobante").val("Boleta");😀
	//$("#tipo_comprobante").selectpicker('refresh');
}

//Función mostrar formulario
function mostrarform(flag) {
	const br = document.getElementById("impuesto").value;
	const igv_asig = document.getElementById("igv_asig");
	igv_asig.value = br;
	console.log(igv_asig.value);
	limpiar();
	if (flag) {
		impuesto = br;
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		//listarArticulos();😀

		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").show();
		detalles = 0;
	}
	else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
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
				url: '../ajax/cotizacion.php?op=listar',
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
/*function listarArticulos()//😀
{
	tabla=$('#tblarticulos').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
		"aServerSide": true,//Paginación y filtrado realizados por el servidor
		dom: 'Bfrtip',//Definimos los elementos del control de tabla
		buttons: [

				],
		"ajax":
				{
					url: '../ajax/cotizacion.php?op=listarArticulosVenta',
					type : "get",
					dataType : "json",
					error: function(e){
						console.log(e.responseText);
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
		"order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}*/


//Función para guardar o editar

function guardaryeditar(e) {

	//window.onload("hey");
	//pr=document.getElementById("total_venta").value;
	//console.log(pr);
	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/cotizacion.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,


		success: function (datos) {
			// bootbox.alert(datos);
			// mostrarform(false);
			// listar();

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
					text: "¡Ocurrio un error, por favor registre nuevamente la proforma!",
					type: "warning",
					confirmButtonText: "Cerrar",
					closeOnConfirm: true

				},
					//alert("funcion guardar y editar"),

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

/*function mostrar(idcotizacion)
{
	console.log("Hola funcion mostrar de cotizacion")
	$.post("../ajax/cotizacion.php?op=mostrar",{idcotizacion : idcotizacion}, function(data, status)
	{
		data = JSON.parse(data);
		mostrarform(true);

		$("#idcliente").val(data.idcliente);
		$("#idcliente").selectpicker('refresh');
		$("#codigotipo_comprobante").val(data.codigotipo_comprobante);
		$("#codigotipo_comprobante").selectpicker('refresh');
		$("#serie").val(data.serie);
		//$("#correlativo").val(data.correlativo);
		$("#fecha_hora").val(data.fecha);
		$("#impuesto").val(data.impuesto);
		$("#moneda").val(data.moneda);
		$("#cotizacion").val(data.cotizacion);//😐
		$("#precio_venta").val(data.precio_venta);
		$("#total_venta_exonerado").val(data.total_venta_exonerado);
		$("#total_venta_inafectas").val(data.total_venta_inafectas);
		$("#total_venta_gratuitas").val(data.total_venta_gratuitas);
		$("#validez").val(data.validez);
		$("#igv_asig").val(data.igv_asig);//😀


		$("#isc").val(data.isc);
		//$("#precio_venta").val(data.precio_venta);//😀
		$("#moneda").val(data.idmoneda);
		$("#moneda").selectpicker('refresh');

		//Ocultar y mostrar los botones
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
	  });

	  $.post("../ajax/cotizacion.php?op=listarDetalle&id="+idcotizacion,function(r){
			$("#detalles").html(r);
	});
}*/

//Función para anular registros
function anular(idcotizacion) {
	bootbox.confirm("¿Está Seguro de anular la cotización?", function (result) {
		if (result) {
			$.post("../ajax/cotizacion.php?op=anular", { idcotizacion: idcotizacion }, function (e) {
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
	limpiar();
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
//var impuesto;
var cont = 0;
var detalles = 0;
//$("#guardar").hide();
$("#btnGuardar").hide();



$("#codigotipo_comprobante").change(marcarImpuesto);

function marcarImpuesto() {
	var tipo_comprobante = $("#codigotipo_comprobante").val();
	if (tipo_comprobante != '1') {
		$("#impuesto").val("0");
	}
	else {
		$("#impuesto").val(impuesto);
	}
}

/*function agregarDetalle(idarticulo,articulo,unidad_medida,precio_venta,afectacion)
  {
	console.log("Hola agregarDetalle de cotizacion")
		// if(unidad_medida=='otros'){
		// 	var unidadm = descripcion_otros;
		// }else{
		// 	var unidadm = unidad_medida;
		// }
			var cantidad=1;
		var descuento=0;
	if (idarticulo!="")
	{

			

		if(afectacion=='Exonerado'){
			// var subtotal=cantidad*precio_venta;
			var valorVentaU=precio_venta;
			var valorVentaT=precio_venta*cantidad;
			var igv = 0;
		}else if(afectacion=='Gravado'){
			var valorVentaU=precio_venta/(1+(impuesto/100));
			var valorVentaT=valorVentaU*cantidad - descuento;
			var precioSinIgv= subtotal/(1+(impuesto/100));
			var igv = precioSinIgv*(impuesto/100);
		}
		var subtotal=cantidad*precio_venta;
		
		var fila='<tr class="filas" id="fila'+cont+'">'+
		'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
		'<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
		'<td><input type="text" name="serieCotizacion[]" style="width:100px"></td>'+
		'<td><input type="hidden" name="afectacio[]" value="'+afectacion+'"><input type="hidden" name="unidad_medida[]" value="">'+unidad_medida+'</td>'+
    
		'<td><input type="number" name="cantidad[]" min="1" id="cantidad'+cont+'" value="'+cantidad+'" style="width:50px"></td>'+
		// '<td><input type="number" name="valor_venta_u[]" value="'+valorVentaU.toFixed(2)+'" style="width:70px"></td>'+
		'<td><input type="hidden" name="descuento[]" value="'+descuento+'" style="width:60px"><span name="valor_venta_u" id="valor_venta_u'+cont+'" >'+valorVentaU.toFixed(2)+'</span></td>'+

    
		// '<td><input type="number" name="impuest[]" value="'+igv+'"></td>'+
		'<td><span name="impuest" id="impuest'+cont+'" >'+igv.toFixed(2)+'</span></td>'+
		'<td><input type="number" step="0.01" name="precio_venta[]" id="precio_venta'+cont+'" value="'+precio_venta+'" style="width:100px"></td>'+
		'<td><span name="valor_venta_t" id="valor_venta_t'+cont+'" >'+valorVentaT.toFixed(2)+'</span></td>'+
		
		'<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
		
		'</tr>';
    
		detalles=detalles+1;
		$('#detalles').append(fila);
		$("#cantidad"+cont).keyup(function(){
			modificarSubtotales();
		});
		$("#cantidad"+cont).change(function(){
			modificarSubtotales();
			});

		
		$("#precio_venta"+cont).keyup(modificarSubtotales);
			$("#precio_venta"+cont).change(function(){
			modificarSubtotales();
			});
		cont++;
		modificarSubtotales();
		
		
		
	}
	else
	{
		alert("Error al ingresar el detalle, revisar los datos del artículo");
	}
  }*/
function agregarDetalle() {


	var cantidad = 1;
	var precio_venta = 1;
	// var descuento=0;

	var subtotal = cantidad * precio_venta;

	var fila = '<tr class="filas" id="fila' + cont + '">' +
		'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
		'<td style="width:40%"><input type="text" name="descripcion[]" id="descripcion[]" style="width:350px;"></td>' +
		'<td style="width:15%"><input type="number" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '" ></td>' +

		'<td style="width:15%"><input type="number" step="0.01" name="precio[]" id="precio' + cont + '" value="' + precio_venta + '" ></td>' +
		// '<td><input type="number" name="impuest[]" value="'+igv+'"></td>'+

		'<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +

		'</tr>';


	detalles = detalles + 1;
	$('#detalles').append(fila);

	$("#cantidad" + cont).change(function () {
		modificarSubtotales();
	});
	$("#precio" + cont).change(function () {
		modificarSubtotales();
	});
	cont++;
	modificarSubtotales();




}

/*function modificarSubtotales()
{
	  var cant = document.getElementsByName("cantidad[]");
  var desc = document.getElementsByName("descuento[]");
  var imp = document.getElementsByName("impuest[]");
  var prec = document.getElementsByName("precio_venta[]");
  var sub = document.getElementsByName("subtotal");
  var afec = document.getElementsByName("afectacio[]");
	  var total = 0.0;
	  var totaligv = 0.0;
	  var totaldesc=0.0;
	  var totalgravad=0.0;
	  var totalexoner=0.0;
	  var newigv=0;
  for (var i = 0; i <cant.length; i++) {
	  var inpC=cant[i];
	  var inpD=desc[i];
	  var inpI=imp[i];
	  var inpP=prec[i];
	  var inpS=sub[i];
	  var inpA=afec[i];
	  // console.log(inpA.value);

	  if(inpA.value=='Exonerado'){
		  var newValorU=inpP.value;
		  var newValorT=inpP.value*inpC.value;
		  newigv=  0;
	  }else if(inpA.value=='Gravado'){
		  var newValoU=inpP.value/(1+(impuesto/100));
		  var newValorU=newValoU.toFixed(2);
		  var newValorT=(inpP.value/(1+(impuesto/100)))*inpC.value - inpD.value;
		  newigv=  (inpC.value*inpP.value/(1+(impuesto/100))-inpD.value)*(impuesto/100);
	  }
  	
	  // console.log(inpA);
	  inpS.value=(inpC.value * inpP.value)-inpD.value;
	  // document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
	  // var newigv= (inpC.value*inpP.value)*(1+(impuesto/100))*(impuesto/100);
  	
	  // var rr = (inpC.value*inpP.value);
	  document.getElementsByName("impuest")[i].innerHTML = newigv.toFixed(2);
	  document.getElementsByName("valor_venta_t")[i].innerHTML = newValorT.toFixed(2);
	  document.getElementsByName("valor_venta_u")[i].innerHTML = newValorU;
	  document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
  	
	  if(inpA.value=='Exonerado'){
		  totalexoner+=parseFloat(newValorT.toFixed(2)-inpD.value);
	  }else{
		  totalgravad+=parseFloat(newValorT.toFixed(2));
	  }



	  totaldesc += parseFloat(inpD.value);
	  totaligv+=parseFloat(newigv.toFixed(2));
	  total+=inpS.value;
  }


  $('#totalg').html("S/. " + addCommas(totalgravad.toFixed(2)));
  $('#total_venta_gravado').val(totalgravad.toFixed(2));

  $('#totale').html("S/. " + totalexoner);
  $('#total_venta_exonerado').val(totalexoner);
	
  $('#totald').html("S/. " + totaldesc);
  $('#total_descuentos').val(totaldesc);

  $('#totaligv').html("S/. " + addCommas(totaligv.toFixed(2)));
  $('#total_igv').val(totaligv.toFixed(2));

  $("#totalimp").html("S/. " + addCommas(total.toFixed(2)));
  $("#total_importe").val(total.toFixed(2));
  evaluar();

}*/

function modificarSubtotales() {
	var cant = document.getElementsByName("cantidad[]");
	var prec = document.getElementsByName("precio[]");
	var sub = document.getElementsByName("subtotal");
	var tipo_impuesto = document.getElementById('tipo_impuesto').value;
	var total = 0.0;
	var totaligv = 0.0;
	var newvparcial = 0;
	var igvt = 0;

	for (var i = 0; i < cant.length; i++) {
		var inpC = cant[i];
		var inpP = prec[i];
		var inpS = sub[i];

		inpS.value = inpP.value * inpC.value;
		document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(2);

		if (tipo_impuesto == 1) {
			newvparcial += parseFloat(inpS.value);
			igvt = newvparcial * impuesto / 100;
			total = newvparcial + igvt;
			$('#subtotal').html("S/. " + newvparcial.toFixed(2));
			$('#ssubtotal').val(newvparcial.toFixed(2));
		
			$('#totaligv').html("S/. " + igvt.toFixed(2));
			$('#igv_total').val(igvt.toFixed(2));
		
			$("#totalimp").html("S/. " + total.toFixed(2));
			$("#total_venta").val(total.toFixed(2));
			evaluar();
		} else {
			newvparcial += parseFloat(inpS.value);
			igvt = newvparcial / (1 + (impuesto / 100));
			totaligv = newvparcial - igvt;
			total = newvparcial;

			$('#subtotal').html("S/. " + igvt.toFixed(2));
			$('#ssubtotal').val(igvt.toFixed(2));
		
			$('#totaligv').html("S/. " + totaligv.toFixed(2));
			$('#igv_total').val(totaligv.toFixed(2));
		
			$("#totalimp").html("S/. " + total.toFixed(2));
			$("#total_venta").val(total.toFixed(2));
			evaluar();

		}
	}
	// calcularTotales();
}
document.getElementById('tipo_impuesto').addEventListener('change', modificarSubtotales);


function calcularTotales() {

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
