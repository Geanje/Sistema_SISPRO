
let tabla;

function init(){
    listar()
    $("#serie").keyup(listar);
    $("#producto").keyup(listar);
    $(".dt-buttons").css({"margin-left":"10px"});
}

function listar(){
    var fecha_inicio=$("#fecha_inicio").val();
    var fecha_fin=$("#fecha_fin").val();
    var serie=$("#serie").val();
    var producto=$("#producto").val();
    
    tabla = $("#tbllistado").dataTable({
        "aProcessing":true,
        "aServerSide":true,
        dom: 'lBfrtip',
        buttons:[
            'copyHtml5',
            'excelHtml5',
            'pdf'

        ],
        // dom: 'lBfrtip',
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        // buttons: [
        //     'copy', 'excel','pdf'
        // ],

        "ajax":{
            url:"../ajax/consulta-ventas-general.php?operacion=consultaxSerieVendida",
            type:"get",
            dataType:"json",
            data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,producto:producto,serie:serie},

            error: function(e){
                console.log(e.responseText);
            }
        },
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros  -",
            // "zeroRecords": "Nothing found - sorry",
            // "info": "Showing page _PAGE_ of _PAGES_",
            // "infoEmpty": "No records available",
            // "infoFiltered": "(filtered from _MAX_ total records)"
        },
        "bDestroy":true,
        // "iDisplayLength" :5, //Paginacion
        "order":[[0,"desc"]] //Ordenar (columna,orden)

    }).DataTable();
}

init()