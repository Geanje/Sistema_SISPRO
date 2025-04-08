var tabla;
function init(){

listar();

};


function listar(){
	tabla=$("#tbllistado").dataTable({
		"scrollY":        "600px",
        "scrollCollapse": true,
        "paging":         false,
		"aProcessing": true, //Activamos el procesamiento de datatables
      	"aServerSide": true, //Paginacion y filtrado realizados por el servidor
        dom: 'Bfrtip', //Definimos los elementos del control de tabla
        buttons:[
          'copyHtml5',
          'excelHtml5',
          // 'csvHtml5',
          'pdf',
      	],

		"ajax":{
			url: "../ajax/consultas.php?op=reportekardex",
			type:"get",
			dataType:"json"
		},

		"language": 
		// "http://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
			{

		    "sProcessing":     "Procesando...",
		    "sLengthMenu":     "Mostrar _MENU_ registros",
		    "sZeroRecords":    "No se encontraron resultados",
		    "sEmptyTable":     "Ningún dato disponible en esta tabla",
		    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
		    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
		    "sInfoPostFix":    "",
		    "sSearch":         "Buscar:",
		    "sUrl":            "",
		    "sInfoThousands":  ",",
		    "sLoadingRecords": "Cargando, espere por favor...",
		    "oPaginate": {
		        "sFirst":    "Primero",
		        "sLast":     "Último",
		        "sNext":     "Siguiente",
		        "sPrevious": "Anterior"
		    },
		    "oAria": {
		        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
		        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
		    }
        },
		"bDestroy":true,
      	"iDisplayLength" :20, //Paginacion
      	"order":[[0,"desc"]] //Ordenar (columna,orden)
	}).DataTable();
}

function reiniciar(){

	swal({
		  title: "BIEN!",
		  text: "¡Se ha actualizado el kardex!",
		  type:"success",
		  confirmButtonText: "Cerrar",
		  closeOnConfirm: false
		},

		function(isConfirm){

			if(isConfirm){
				// history.back();

				$.ajax({
					url:"../ajax/consultas.php?op=reiniciarkardex",
					type:"POST",
					success:function(){
						// $('#loading').html("");
						tabla.ajax.reload();
						$(location).attr("href","kardex.php");
					}
				});
			}
	});


	// var MsjEnviando='<img src="../files/loading.gif">';

	// 			$.ajax({
	// 				url:"../ajax/consultas.php?op=reiniciarkardex",
	// 				type:"POST",
	// 				beforeSend:function(){
	// 					$('#loading').html(MsjEnviando);
	// 				},
	// 				success:function(){
	// 					$('#loading').html("");
	// 					tabla.ajax.reload();
	// 				}
	// 			});
}

init();