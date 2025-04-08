var tabla;


function init(){
	listar();
	$("#fecha_inicio").change(listar);
	$("#fecha_fin").change(listar);
}

function listar(){
    var fecha_inicio=$("#fecha_inicio").val();
	var fecha_fin=$("#fecha_fin").val();

	tabla=$("#tbllistado").dataTable(
	{
		"aProcessing": true, //Activamos el procesamiento de datatables
      	"aServerSide": true, //Paginacion y filtrado realizados por el servidor
      	dom: 'Bfrtip', //Definimos los elementos del control de tabla
      	buttons:[
          'copyHtml5',
          'excelHtml5',
          'csvHtml5',
          'pdf'

      	],
      	"ajax":
      	{
        url:'../ajax/consultas.php?op=consultaCotizacion',
        data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin},
        type:"get",
        dataType:"json",
        error:function(e){
          	console.log(e.responseText);
        	}
    	},
    	 "bDestroy":true,
      "iDisplayLength" :5, //Paginacion
      "order":[[0,"desc"]] //Ordenar (columna,orden)
	}
	).DataTable();
}

init();