var tabla;

//funcion que se ejecuta al inicio
function init() {
	mostrarform(false);
	listar_precios();
  
	$("#formulario").on("submit",function(e)
	{
	  guardaryeditar(e);
	}
  
	)
  
  
  }

function listar_precios(){
	tabla = $("#tbllistado").dataTable(
	{
		"aProcessing":true,
		"aServerSide":true,
	 //    dom: 'Bfrtip',//Definimos los elementos del control de tabla
		// buttons: [
		//             'copyHtml5',
		//             'excelHtml5',
		//             'pdf'
		//         ],
		"ajax": {
			url: "../ajax/mant-producto.php?op=listar_productos",
			type: "get",
			dataType: "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy": true,
		// "iDisplayLength": 5,
	    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
		
	}).DataTable();
}

/// --
function mostrarPrecioVenta(descripcion, id_detalle_ingreso, precio_venta) {
	mostrarform(true)
	$("#id_detalle_ingreso").val(id_detalle_ingreso)
	$("#descripcion").val(descripcion);
	$("#precio_venta").val(precio_venta);
}

//Funcion mostrar formulario
function mostrarform(flag)
{
  // -- limpiar();
  if(flag)
  {
    $('#listadoregistros').hide();
    $('#formularioregistros').show();
    $('#btnGuardar').prop("disabled",false);

  }
  else {
    $('#listadoregistros').show();
    $('#formularioregistros').hide();
  }

}

//funcion cancelarform
function cancelarform()
{
  limpiar();
  mostrarform(false);
}

//Funcion limpiar
function limpiar() {
	$("#descripcion").val("");
	$("#precio_venta").val("");
}

// --
function guardaryeditar(e)
{
  e.preventDefault();// No se activara la accion predeterminada del evento
  $("#btnGuardar").prop("disabled",true);
  var formData=new FormData($("#formulario")[0]);

  $.ajax(
    {
      url:"../ajax/mant-producto.php?op=guardaryeditar",
      type:"POST",
      data:formData,
      contentType:false,
      processData:false,

      success:function(datos)
      {
          alert(datos);
          mostrarform(false);
          tabla.ajax.reload();

      }

    }
  );
  limpiar();
}

init();