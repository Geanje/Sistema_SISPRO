var tabla;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();
	// $('#impuesto').prop('disabled',true);

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);
	});
	//Cargamos los items al select proveedor
	//Listamos a los clientes en el select 
	$.post("../ajax/proforma.php?op=selectCliente", function(r){
	            $("#idcliente").html(r);
	            $('#idcliente').selectpicker('refresh');
	});


	/*$.post("../ajax/proforma.php?op=selectTipoComprobante",function(r){
				$('#codigotipo_comprobante').html(r);
	            $('#codigotipo_comprobante').selectpicker('refresh');

	});*/

	//Hace que aparezca el tipo de moneda
	$.post("../ajax/proforma.php?op=selectMoneda",function(r){
			$('#moneda').html(r);
			$('#moneda').selectpicker('refresh');

	});

	$.post("../ajax/proforma.php?op=selecTipoIGV",function(r){
		$('#impuesto').html(r);
		$('#impuesto').selectpicker('refresh');
	
	});

	// $('#gravado').click(function(){
	// 	var pp=$('#tipoGravado').prop("checked",false);
		// var pp=document.getElementById("tipoGravado").prop("checked",true);
	// })
}
/*function rellenarCliente(){ Útil para cotizacion al parecer
	var idcliente=$("#idcliente").val();

	var cliente=$("#idproforma").prop("selected",true);
	if(cliente){
		$.post("../ajax/proforma.php?op=mostrarDatoCliente",{idcliente : idcliente},function(data){
			data = JSON.parse(data);

			$("#numdireccion").val(data.num_documento);
			$("#direccioncliente").val(data.direccion);

		});
	}
}*/

//No se sabe para que se utilizaba 
// function LPad(ContentToSize,PadLength,PadChar)
//   {
//      var PaddedString=ContentToSize.toString();
//      for(var i=ContentToSize.length+1;i<=PadLength;i++)
//      {
//          PaddedString=PadChar+PaddedString;
//      }
//      return PaddedString;
//   }



function limpiar()
{
	$("#idcliente").val("");
	$("#idcliente").selectpicker('refresh');
	$("#correlativo").val("");
	$("#validez").val("");
	$("#referencia").val("");
	//$("#impuesto").val("");//
	$("#tiempoEntrega").val("");// agregado para que al momento de cancelar proforma se quede en blanco este campo
	$("#igv_total").val("");//
	




	$("#numdireccion").val("");
	$("#direccioncliente").val("");
	//$("#tipo_proforma").val("");

	$("#subtotal").html("0.00");
	$("#ssubtotal").val("");

	$("#totaligv").html("0.00");
	//$("#igv_total").val("");

	$("#totalimp").html("0.00");
	$("#total_venta").val("");


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
	// $("#totalisc").html("0.00");
	// $("#total_igv").val("");
	// $("#totaligv").html("0.00");

	// $("#total_importe").val("");
	$(".filas").remove();
	// $("#totalimp").html("0.00");
	

	//Obtenemos la fecha actual
	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#fecha_hora').val(today);


}

//Función mostrar formulario
function mostrarform(flag)
{
	//console.log("Llamando a formulario")//
	//const br=document.getElementById('impuesto').value;
	//console.log(br);
	//const igv_asig=document.getElementById("igv_asig");
	//igv_asig.value=br;
	//console.log(igv_asig.value);
	limpiar();
	if (flag)
	{
		//impuesto=br;		

		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		$("#btncliente").hide();
		$("#btnPDF").hide();

		listarArticulos();
//me muestra la lista de articulo pero lo agrega cómo en cotizacion 

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
		$("#btncliente").show();
		$("#btnPDF").show();
	}
}



//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

//Función Listar, sin detectar que realiza 
function listar()//al parecer lista las proformas guardadas
{
	console.log("llamando a la funcion listar de proforma");
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
					url: '../ajax/proforma.php?op=listar',
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


//Función ListarArticulos
function listarArticulos()
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
					url: '../ajax/proforma.php?op=listarArticulosVenta',
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
}

//Función para guardar o editar

function guardaryeditar(e)
{
	//pe=docuemnte.getElementById("idproforma").value;
	//console.log(pe);
	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

		// Validar que Debes insertar una Validez de la Oferta
		var validez = $('#validez').val() || $('#validez').data('validez');
    
		if (!validez) {
			swal({
				title: 'Error!',
				text: 'Debes insertar una Validez de Oferta',
				type: 'error',
				timer: 1000, // 1 segundo
				showConfirmButton: false
			});
			return false;
		}
		//---------------------------------------------------------------------
	
	$.ajax({
		url: "../ajax/proforma.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {
	          //bootbox.alert(datos);
	          //mostrarform(false);
	          //listar();
			  if(datos !="" || datos !=null){
		        swal({
				  title: "BIEN!",
				  text: "¡"+datos+"!",
				  type:"success",
				  timer: 1000, // 1 segundo
				  showConfirmButton: false // No mostrar botón porque se cerrará solo
			  });
	  
			  setTimeout(function () {
				  mostrarform(false);
				  listarCuotas(id_ingreso);
				  $("#modalListadoCuotas").modal('hide');
			  }, 1000); // Espera el mismo tiempo que el timer para ejecutar

	        }else{
	        	swal({
				  title: "Error!",
				  text: "¡Ocurrio un error, por favor registre nuevamente la proforma!",
				  type:"warning",
				  confirmButtonText: "Cerrar",
				  closeOnConfirm: true
				},

				function(isConfirm){

					if(isConfirm){
						location.reload(true);
					}
				});
	        }
	    }

	});
	limpiar();
}

function mostrar(idproforma)
{
	$.post("../ajax/proforma.php?op=mostrar",{idproforma : idproforma}, function(data, status)
	{
		data = JSON.parse(data);
		mostrarform(true);

		$("#idcliente").val(data.idcliente);
		$("#idcliente").selectpicker('refresh');
		$("#codigotipo_comprobante").val(data.codigotipo_comprobante);
		// $("#correlativo").val(data.correlativo);
		$("#fecha_hora").val(data.fecha);
		$("#idproforma").val(data.idproforma);
		//$("#tipo_proforma").val(data.tipo_proforma);
		//$("#tipo_proforma").selectpicker('refresh');
		
		$("#serie").val(data.serie);
		$("#impuesto").val(data.impuesto);
		//$("#moneda").val(data.moneda);
		$("#idventa").val(data.idventa);
		$("#total_venta_gravado").val(data.total_venta_gravado);
		$("#total_importe").val(data.total_venta);
		$("#moneda").val(data.idmoneda);
		$("#moneda").selectpicker('refresh');
		//$("#igv_total").val(data.igv_total);
		$("#validez").val(data.validez);
		$("#tiempoEntrega").val(data.tiempoEntrega);
		$("#igv_asig").val(data.igv_asig);

		
		






		// var subt=data.total_venta/1.18;
		// var igv=data.total_venta*0.18;

		$("#totalimp").html(data.total_venta);
		$("#total_venta").val(data.total_venta);
		//rellenarCliente();

		$("#subtotal").html(data.total_venta-data.igv_total);
		$("#ssubtotal").val(data.total_venta-data.igv_total);

		$("#igv_total").html(data.igv_total);
		$("#igv_total").val(data.igv_total);




		//Ocultar y mostrar los botones
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
 	});

 	$.post("../ajax/proforma.php?op=listarDetalle&id="+idproforma,function(r){
	        $("#detalles").html(r);
	});
}

//Función para anular registros
function anular(idproforma)
{
	bootbox.confirm("¿Está Seguro de anular la venta?", function(result){
		if(result)
        {
        	$.post("../ajax/proforma.php?op=anular", {idproforma : idproforma}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});
        }
	})
  limpiar();
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
//var impuesto=11;
var cont=0;
var detalles=0;
//$("#guardar").hide();
$("#btnGuardar").hide();




function agregarDetalle(idarticulo,articulo,unidad_medida,precio_venta,afectacion)
  {
	console.log('Llamando a la funcion agregarDetalle');

  	// if(unidad_medida=='otros'){
  	// 	var unidadm = descripcion_otros;
  	// }else{
  	// 	var unidadm = unidad_medida;
  	// }
	  	var cantidad=1;
	    var descuento=0;
		//agregado aqui
		var existe = false;
    if (idarticulo!="")
    {
		//agregado 
		$('input[name="idarticulo[]"]').each(function(){
            if ($(this).val() == idarticulo){
              existe = true;
            }
          });
        
          if (existe) {
            swal({
              title: '¡Error!',
              text: '¡El articulo ya fue agregado!',
              type: 'warning',
              timer: 1000, // 1 segundo
              showConfirmButton: false
            });
            return;
          }
        

    		

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
    	'<td><input type="hidden" id="idarticulo[]" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
    	'<td><input type="text" name="serieProforma[]" id="serieProforma[]" required style="width:100px"></td>'+
    	'<td><input type="hidden" name="afectacio[]" value="'+afectacion+'"><input type="hidden" name="unidad_medida[]" value="">'+unidad_medida+'</td>'+
    
    	'<td><input type="number" name="cantidad[]" min="1" id="cantidad'+cont+'" value="'+cantidad+'" style="width:50px"></td>'+
    	// '<td><input type="number" name="valor_venta_u[]" value="'+valorVentaU.toFixed(2)+'" style="width:70px"></td>'+
    	'<td><input type="hidden" id="descuento" name="descuento[]" value="'+descuento+'" style="width:60px"><span name="valor_venta_u" id="valor_venta_u'+cont+'" >'+valorVentaU.toFixed(2)+'</span></td>'+

    
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
  }

function modificarSubtotales()
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
  	var igv_total=0;
    for (var i = 0; i <cant.length; i++) { 
    	var inpC=cant[i];
    	var inpD=desc[i];
    	var inpI=imp[i];
    	var inpP=prec[i];
    	var inpS=sub[i];
    	var inpA=afec[i];
		// console.log(inpA.value);
		var impuesto = document.getElementById("impuesto").value;
		var igv_asig = document.getElementById("igv_asig");
		igv_asig.value = impuesto;
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
    	// var newigv= (inpC.value*inpP.value)(1+(impuesto/100))(impuesto/100);
    	
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
    $('#igv_total').val(totaligv.toFixed(2));

    $("#totalimp").html("S/. " + addCommas(total.toFixed(2)));
    $("#total_venta").val(total.toFixed(2));
    evaluar();

  }
  function calcularTotales(){
    evaluar();
  }

  function evaluar(){
  	if (detalles>0)
    {
      $("#btnGuardar").show();
    }
    else
    {
      $("#btnGuardar").hide();
      cont=0;
    }
  }

  function eliminarDetalle(indice){
  	$("#fila" + indice).remove();
  	modificarSubtotales();
  	detalles=detalles-1;
  	evaluar()
  }

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

init();
