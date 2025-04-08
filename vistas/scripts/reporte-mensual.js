var tabla;

//Función que se ejecuta al inicio
function init(){
	listarProductosMasVendidos();

	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear() + "-" + (month) + "-" + (day);
	$('#mes').val(month);
	$('#anno').val(now.getFullYear());
	
}

function listarProductosMasVendidos()
{
	var mes=$("#mes").val();
	var anno=$("#anno").val();


    tabla=$("#tbllistado").dataTable(
		{
			 "aProcessing": true, //Activamos el procesamiento de datatables
		      "aServerSide": true, //Paginacion y filtrado realizados por el servidor
		      dom: 'Bfrtip', //Definimos los elementos del control de tabla
		      buttons:[
		          'copyHtml5',
		          'excelHtml5',
		          'pdf'

		      ],
      		"ajax":{
      			url:"../ajax/reporte-mensual.php?op=listarProductos",
      			data:{mes:mes,anno:anno},
      			type:"get",
      			dataType:"json",
      			error:function(e)
			        {
			          console.log(e.responseText);
			        }
      		},
      		 "bDestroy":true,
		      "iDisplayLength" :10, //Paginacion
		      "order":[[0,"desc"]] //Ordenar (columna,orden)
				}
		).DataTable();


	
}

init();