var tabla;
var tablaCredito;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);
	});
	//Cargamos los items al select proveedor
	$.post("../ajax/prestamo.php?op=selectSucursal", function(r){
	            $("#idsucursal").html(r);
	            $('#idsucursal').selectpicker('refresh');
	});

    $("#idsucursal").change(rellenarCliente);


}



function rellenarCliente(){
	var sucursall=$("#idsucursal").val();

	var idcliente=$("#idsucursal").prop("selected",true);
	if(idcliente){
		// console.log(sucursall);

		$.post("../ajax/prestamo.php?op=mostrarDatoSucursal",{idsucursal : sucursall},function(data){
			data = JSON.parse(data);
			// console.log(data);
			if(data.num_documento !=""){
				$("#numdoc").val(data.num_documento);
			}else{
				$("#numdoc").val("Sin número de documento para mostrar");
			}
			if(data.direccion !=""){
				$("#direccionsucursal").val(data.direccion);
			}else{
				$("#direccionsucursal").val('Sin dirección para mostrar');

			}

		});
		// $("#idcliente").val(data.idcliente);
	}
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




function limpiar(){
	$("#radioContado").prop("checked",true);
	$("#btnaAddVentaCredito").hide();


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
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		$("#btnPDF").hide();
		$("#btnActulizar").hide();
		listarArticulos();

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
		$("#btnPDF").show();
		$("#btnActulizar").show();
	}
}

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

//Función Listar
function listar()
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
					url: '../ajax/prestamo.php?op=listar',
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
					url: '../ajax/prestamo.php?op=listarArticulosVenta',
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
	e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/prestamo.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {
	          // bootbox.alert(datos);
	          // console.log(datos);
	          // $.post('../ajax/venta2.php?op=cancelarCredito',)
	        if(datos !="" || datos !=null || datos !=" "){
		        swal({
				  title: "¡LISTO!",
				  text: "¡"+datos+"!",
				  type:"success",
				  confirmButtonText: "Cerrar",
				  closeOnConfirm: true
				},

				function(isConfirm){

					if(isConfirm){
						// history.back();
						// tablaCredito.ajax.reload();
						mostrarform(false);
		          		listar();
					}
				});

	        }else{
	        	swal({
				  title: "Error!",
				  text: "¡Ocurrio un error, por favor registre nuevamente la venta!",
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

function mostrar(idventa)
{
	$.post("../ajax/prestamo.php?op=mostrar",{idventa : idventa}, function(data, status)
	{
		data = JSON.parse(data);
		// mostrar cliente
 		$.post("../ajax/prestamo.php?op=mostrarDatoSucursal",{idsucursal : data.idcliente},function(data){
			data = JSON.parse(data);

			$("#numdoc").val(data.num_documento);
			$("#direccionsucursal").val(data.direccion);

		});

		$("#idsucursal").val(data.idcliente);
		$("#idsucursal").selectpicker('refresh');
		// $("#codigotipo_comprobante").val(data.codigotipo_comprobante);
		// $("#codigotipo_comprobante").selectpicker('refresh');
		// $("#serie").val(data.serie);
		// $("#correlativo").val(data.correlativo);
		$("#fecha_hora").val(data.fecha);
		// $("#idventa").val(data.idventa);
		// $("#total_venta_gravado").val(addCommas(data.total_venta_gravado));
		// $("#total_importe").val(addCommas(data.total_venta));

		//Ocultar y mostrar los botones
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarArt").hide();
 	});

 	$.post("../ajax/prestamo.php?op=listarDetalle&id="+idventa,function(r){
	        $("#detalles").html(r);
	});

		mostrarform(true);

}

//Función para anular registros
function anular(idventa)
{
	bootbox.confirm("¿Está Seguro de anular este préstamo?", function(result){
		if(result)
        {
        	$.post("../ajax/prestamo.php?op=anular", {idventa : idventa}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});
        }
	})
  limpiar();
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles
var impuesto=18;
var cont=0;
var detalles=0;
//$("#guardar").hide();
$("#btnGuardar").hide();




function agregarDetalle(idarticulo,articulo,unidad_medida,precio_venta)
  {

  	// if(unidad_medida=='otros'){
  	// 	var unidadm = descripcion_otros;
  	// }else{
  	// 	var unidadm = unidad_medida;
  	// }
	  	var cantidad=1;
	    var descuento=0;
	    precio_venta = 0;
		// $("#btnGuardar").show();

    if (idarticulo!="")
    {
    	var subtotal=cantidad*precio_venta;
    	
    	var fila='<tr class="filas" id="fila'+cont+'">'+
    	'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
    	'<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
    	'<td><input type="text" name="serieArticulo[]" style="width:80px"></td>'+
    	'<td>'+unidad_medida+'</td>'+
    	'<td><input type="number" name="cantidad[]" id="cantidad'+cont+'" min="1" value="'+cantidad+'" style="width:70px"></td>'+
    	// 
    	'<td><span>0</span></td>'+
    	
    	'<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
    	
    	'</tr>';
    
    	detalles=detalles+1;
    	$('#detalles').append(fila);
    	cont++;
    	evaluar();
    	
    	
    }
    else
    {
    	alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
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
  	detalles=detalles-1;
  	evaluar()
  }

init();
