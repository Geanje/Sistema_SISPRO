var tabla;
var tipo_cambio_dolar = 0.00
//Función que se ejecuta al inicio
function init(){
	// --
// 	get_exchange_rate();
	mostrarform(false);
	listarguiaprincipal();
	//listarComprobantes();
	
	
	// $('#impuesto').prop('disabled',true);
    document.getElementById('impuesto').readOnly=true;

	// $("#formulario").on("submit",function(e)
	// {
	// 	guardaryeditar(e);
	// });

	//Cargamos los items al select proveedor
	$.post("../ajax/guia.php?op=selectCliente", function(r){
	            $("#idcliente").html(r);
	            $('#idcliente').selectpicker('refresh');
	});

	$.post("../ajax/guia.php?op=selectTransportista", function(r){
		$("#sltransportista").html(r);
		$('#sltransportista').selectpicker('refresh');
});


	$.post("../ajax/guia.php?op=selectTipoComprobante",function(r){
				$('#codigotipo_comprobante').html(r);
	            $('#codigotipo_comprobante').selectpicker('refresh');

	});

	$.post("../ajax/guia.php?op=selectMotivoTraslado",function(r){
		$('#codigo_traslado').html(r);
		$('#codigo_traslado').selectpicker('refresh');

	});

	$.post("../ajax/guia.php?op=selectPuntoPartida",function(r){
		$('#idpartida').html(r);
		$('#idpartida').selectpicker('refresh');

	});

	$.post("../ajax/guia.php?op=selectMoneda",function(r){
			$('#moneda').html(r);
			$('#moneda').selectpicker('refresh');

	});

	$('#sltransportista').change(function() {
		console.log($(this).val());
		let idtransportista=$(this).val()
		$.post("../ajax/guia.php?op=mostrarDatoTransportista",{idtransportista : idtransportista},function(data){
			data = JSON.parse(data);
			console.log(data)
			$('#txtnumdoc').val(data.num_documento)	
			$('#txtmarca').val(data.marca)
			$('#txtplaca').val(data.placa)	
			$('#licencia').val(data.licencia_conducir)	

			

			//$("#numdireccion").val(data.num_documento);
			//$("#direccioncliente").val(data.direccion);

		});
	});



	      $("#idcliente").change(rellenarCliente);

}

// -- 
function get_exchange_rate() {
	// --
	/*$.ajax({
		url: "../ajax/guia.php?op=get_exchange_rate",
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
var clie;
function rellenarCliente(){
	var clientee=$("#idcliente").val();
	clie=clientee;
//console.log(clientee);
	var idcliente=$("#idcliente").prop("selected",true);
	if(idcliente){
		// console.log(clientee);

		$.post("../ajax/guia.php?op=mostrarDatoCliente",{idcliente : clientee},function(data){
			data = JSON.parse(data);

			$("#numdireccion").val(data.num_documento);
			$("#direccioncliente").val(data.direccion);

		});
		// $("#idcliente").val(data.idcliente);
	}
	//Aqui llamar a la funcion que actualice la lista de comprobantes
	listarComprobantes();
}

function LPad(ContentToSize,PadLength,PadChar)
  {
     var PaddedString=ContentToSize.toString();
     for(var i=ContentToSize.length+1;i<=PadLength;i++)
     {
         PaddedString=PadChar+PaddedString;
     }
     return PaddedString;
  }




function limpiar()
{
	$("#idcliente").val("");
	$("#cliente").val("");
	$("#serie").val("");
	$("#correlativo").val("");
	$("#impuesto").val("18");


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
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora').val(today);

    //Marcamos el primer tipo_documento
    $("#tipo_comprobante").val("Boleta");
	$("#tipo_comprobante").selectpicker('refresh');
}

//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnagregar").hide();
		$("#btndestinatario").hide();

		
		
		

		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").show();
		detalles=0;
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
		$("#btndestinatario").show();

	}
}

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

//Función listarguiaprincipal
function listarguiaprincipal()
{
	tabla=$('#tbllistado').dataTable(
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
					url: '../ajax/guia.php?op=listarguiaprincipal',
					type : "get",
					dataType : "json",
					error: function(e){
						console.log(e.responseText);
					}
				},
		"bDestroy": true,
		"iDisplayLength": 9,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}

//Agregar Detalle De listar Comprobante

function agregarDetalle(idguia){
	$.post("../ajax/guia.php?op=listarDetalleComprobantes&id="+idguia,function(r){

	detalles=detalles+1;
	
	$('#detalles').html(r);

	$("#listacomprobante").val(idguia);

	});

	modificarSubtotales();
	

}

//Funcion para al presionar boton "Seleccionar Comprobante"

function listarComprobantes()
{
	//var clie = document.getElementById(clientee).value;
	//console.log(clie);
	tabla=$('#tblcomprobantes').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [

				],
		"ajax":
				{
					url: '../ajax/guia.php?op=listarComprobantes',
					type : "get",
					dataType : "json",
					data: {idcliente:clie},
					error: function(e){
						console.log(e.responseText);
					}
				},
		"bDestroy": true,
		"iDisplayLength": 9,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}

// Funcion del Boton de FA-FA-PLUS 

function mostrar(idguia)
{
	$.post("../ajax/guia.php?op=mostrar",{idguia : idguia}, function(data, status)
	{
		
		data = JSON.parse(data);
		// mostrar cliente
 		$.post("../ajax/guia.php?op=mostrarDatoCliente",{idcliente : data.idcliente},function(data){
			data = JSON.parse(data);

			$("#numdireccion").val(data.num_documento);
			$("#direccioncliente").val(data.direccion);

		});
		

		$("#idcliente").val(data.idcliente);
		$("#idcliente").selectpicker('refresh');
		$("#codigotipo_comprobante").val(data.codigotipo_comprobante);
		$("#codigotipo_comprobante").selectpicker('refresh');
		$("#codigotipo_pago").val(data.codigotipo_pago);
		$("#codigotipo_pago").selectpicker('refresh');
		$("#serie").val(data.serie);
		$("#correlativo").val(data.correlativo);
		$("#fecha_ven").val(fecha_ven);
		$("#fecha_hora").val(data.fecha);
		$("#impuesto").val(data.impuesto);
		$("#moneda").val(data.moneda);
		$("#idguia").val(data.idguia);
		$("#").val(data.idcomprobante);
		$("#tipo_cambio").val(data.tipo_cambio);
		$("#total_venta_gravado").val(addCommas(data.total_venta_gravado));
		$("#total_venta_exonerado").val(addCommas(data.total_venta_exonerado));
		$("#total_venta_inafectas").val(addCommas(data.total_venta_inafectas));
		$("#total_venta_gratuitas").val(addCommas(data.total_venta_gratuitas));
		$("#isc").val(addCommas(data.isc));
		$("#total_importe").val(addCommas(data.total_venta));
		$("#moneda").val(data.idmoneda);
		$("#moneda").selectpicker('refresh');
		//Ocultar y mostrar los botones
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
 	});

 	$.post("../ajax/guia.php?op=listarDetalleComprobantes&id="+idguia,function(r){

	    $("#detalles").html(r);

	});

		mostrarform(true);

 
}

//Funcion Eliminar Consulta

function eliminarDetalle(indice){
	$("#fila" + indice).remove();
	modificarSubtotales();
	detalles=detalles-1;
	evaluar()
}

//Función para guardar o editar
// function guardaryeditar(e)
// {
// 	e.preventDefault(); //No se activará la acción predeterminada del evento
// 	//$("#btnGuardar").prop("disabled",true);
// 	var formData = new FormData($("#formulario")[0]);

// 	$.ajax({
// 		url: "../ajax/guia.php?op=guardaryeditar",
// 	    type: "POST",
// 	    data: formData,
// 	    contentType: false,
// 	    processData: false,

// 	    success: function(datos)
// 	    {
// 	          // bootbox.alert(datos);
// 	        if(datos !="" || datos !=null){
// 		        swal({
// 				  title: "BIEN!",
// 				  text: "¡"+datos+"!",
// 				  type:"success",
// 				  confirmButtonText: "Cerrar",
// 				  closeOnConfirm: true
// 				},

// 				function(isConfirm){

// 					if(isConfirm){
// 						// history.back();
// 						mostrarform(false);
// 		          		listar();
// 					}
// 				});

// 	        }else{
// 	        	swal({
// 				  title: "Error!",
// 				  text: "¡Ocurrio un error, por favor registre nuevamente la venta!",
// 				  type:"warning",
// 				  confirmButtonText: "Cerrar",
// 				  closeOnConfirm: true
// 				},

// 				function(isConfirm){

// 					if(isConfirm){
// 						location.reload(true);
// 					}
// 				});
// 	        }

	       

// 	    }

// 	});
// 	limpiar();
// }


//Función para anular registros
function anular(idguia)
{
	bootbox.confirm("¿Está Seguro de anular la venta?", function(result){
		if(result)
        {
        	$.post("../ajax/guia.php?op=anular", {idguia : idguia}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});
        }
	})
  limpiar();
}

//Funcion para solo letras

function SoloLetras(e)
{
	var regex = new RegExp("^[a-zA-Z ]+$");
	var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
	if (!regex.test(key)) {
	  event.preventDefault();
	  return false;
	  }
}

//Funcion para solo numeros 

function SoloNumeros()
{
	var regex = new RegExp("^[0-9]+$");
	var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
	if (!regex.test(key)) {
	  event.preventDefault();
	  return false;
	}
}

//Funcion para Letras y Numeros

function LetrasyNum()
{
	var regex = new RegExp("^[a-zA-Z0-9]+$");
	var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
	if (!regex.test(key)) {
	  event.preventDefault();
	  return false;
	}
}


//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto=18;
var cont=0;
var detalles=0;
//$("#guardar").hide();
$("#btnGuardar").hide();



$("#codigotipo_comprobante").change(marcarImpuesto);

function marcarImpuesto()
  {
  	var tipo_comprobante=$("#codigotipo_comprobante").val();
  	if (tipo_comprobante!='1')
    {
        $("#impuesto").val("0");
        // document.getElementById('impuesto').readOnly=true;
        // $("#impuesto").attr("readOnly","readonly");
    }
    else
    {
        $("#impuesto").val(impuesto);
        // document.getElementById('impuesto').readOnly=true;

        // $("#impuesto").attr("readOnly","readonly");
    }
  }
   	

  function modificarSubtotales()
  {
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

    	// var inVentaU =valorventau[i];
    	// var inImp =impues[i];
    	// var inVentaT =valorventat[i];
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
    	document.getElementsByName("subtotal")[i].innerHTML = addCommas((inpS.value).toFixed(2));
    		

    	// inVentaU.value(newValorU);
    	// inImp.value(newigv.toFixed(2));
    	// inVentaT.value(newValorT.toFixed(2));


    	if(inpA.value=='Exonerado'){
    		totalexoner+=parseFloat(newValorT.toFixed(2)-inpD.value);
    	}else{
    		totalgravad+=parseFloat(newValorT.toFixed(2));
    	}
    	// totalgravad += parseFloat(newValorT.toFixed(2));



    	totaldesc += parseFloat(inpD.value);
    	totaligv+=parseFloat(newigv.toFixed(2));
    	total+=inpS.value;


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
		$("#total_soles").html("S/. " +(total * tipo_cambio_dolar).toFixed(2))

		$("input[name=total_soles]").val("S/. " +(total * tipo_cambio_dolar).toFixed(2))
	}
	

    evaluar();

    // calcularTotales();
  }

  // --


  function addCommas(nStr)
	{
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
	

  function calcularTotales(){
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

  function evaluar(){
  	if (detalles>0)
    {
      $("#btnGuardar").show();
    }
    else
    {
      $("#btnGuardar").show();
      cont=0;
    }
  }

	

init();
