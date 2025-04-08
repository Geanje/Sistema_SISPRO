var tabla;

function init(){
	 listar_precios();
}

function listar_precios() {
    tabla = $("#tbllistado").dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: '<"top"lBfrtip>',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            
        ],
        "ajax": {
            url: "../ajax/articulo.php?op=listar_precios",
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "order": [[ 0, "asc" ]]
    }).DataTable();

    // Agregar estilos CSS para el "Show entries"
    $(".dataTables_length").css("padding-right", "50px");
}
init();