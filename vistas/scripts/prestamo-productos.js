var tabla;
function init(){

	$.post("../ajax/prestamo-productos.php?op=listarSucursales",function(r){
		// console.log(r);
		$("#idsucursal").html(r);
        $('#idsucursal').selectpicker('refresh');
	});
	listar();
}

function listar(){
	var fecha_inicio=$("#fecha_inicio").val();
	var fecha_fin=$("#fecha_fin").val();
	var idsucursal=$("#idsucursal").val();

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
      		"ajax":{
      			url:"../ajax/prestamo-productos.php?op=listaPrestamoProductos",
      			data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,idsucursal:idsucursal},
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

		$.post("../ajax/prestamo-productos.php?op=cantProductosPrestadosxSucursal",{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,idsucursal:idsucursal},function(data,status){
			data=JSON.parse(data);
			// console.log(data);
			$("#sucursa").text(data.sucursal);
			$("#cantsucursal").text(addCommas(data.canttotalsucursal));

		});
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