var tabla;

//Función que se ejecuta al inicio
function init(){
	// rentabilidad();
	$("#btnMostrar").on("click",function(e){
		e.preventDefault();
		let porcent = $("#porcen").val();
		if(porcent =="" || porcent == null){
			$("#cuerpotabla").text("Por favor ingrese un porcentaje para evaluar correctamente la rentabilidad.")

		}else{
			rentabilidad();
			$("#cuerpotabla").text("");
		}
	})

	var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear() + "-" + (month) + "-" + (day);
	$('#mes').val(month);
	$('#anno').val(now.getFullYear());
}

function rentabilidad(){
	let mes=$("#mes").val();
	let anno=$("#anno").val();
	let porcentaje=$("#porcen").val();


	$.post("../ajax/rentabilidad.php?op=listarRentabilidad",{mes:mes,anno:anno,porcentaje:porcentaje},function(data,status){
		let dato = JSON.parse(data);
		let venta1 = parseFloat(dato.dato2.ventas)
		let compra1 = parseFloat(dato.dato1.compras)
		let porcentaje1 = parseFloat(dato.dato2.porcentaje)
		let total = venta1 - (compra1 + porcentaje1)

		$("#cantventa").text(addCommas(venta1.toFixed(2)));
		$("#cantcompra").text(addCommas(compra1.toFixed(2)));
		$("#cantporcentaje").text(addCommas(porcentaje1.toFixed(2)));
		$("#canttotal").text(addCommas(total.toFixed(2)));
	});
  //   tabla=$("#tbllistado").dataTable(
		// {
		// 	  "aProcessing": true, //Activamos el procesamiento de datatables
		//       "aServerSide": true, //Paginacion y filtrado realizados por el servidor
		//       dom: 'Bfrtip', //Definimos los elementos del control de tabla
		//       buttons:[
		//           'copyHtml5',
		//           'excelHtml5',
		//           'pdf'

		//       ],
  //     		"ajax":{
  //     			url:"../ajax/rentabilidad.php?op=listarRentabilidad",
  //     			data:{mes:mes,anno:anno,porcentaje:porcentaje},
  //     			type:"get",
  //     			dataType:"json",
  //     			error:function(e)
		// 	        {
		// 	          console.log(e.responseText);
		// 	        }
  //     		},
  //     		 "bDestroy":true,
		//       "iDisplayLength" :10, //Paginacion
		//       "order":[[0,"desc"]] //Ordenar (columna,orden)
		// 		}
		// ).DataTable();


	
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