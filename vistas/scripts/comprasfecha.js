var tabla;

//funcion que se ejecuta al inicio
function init() {

  listar();
  $("#fecha_inicio").change(listar);
$("#fecha_fin").change(listar);



}



//function listar
function listar()
{

    var fecha_inicio=$("#fecha_inicio").val();
    var fecha_fin=$("#fecha_fin").val();


  tabla=$('#tbllistado').dataTable(
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
        url:'../ajax/consultas.php?op=comprasfecha',
        data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin},
        type:"get",
        dataType:"json",
        error:function(e)
        {
          console.log(e.responseText);
        }

      },
      "bDestroy":true,
      "iDisplayLength" :5, //Paginacion
      "order":[[0,"desc"]] //Ordenar (columna,orden)

    }
  ).DataTable();
  $.post("../ajax/consultas.php?op=sumComprasFecha",{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin},function(data,status){
    data=JSON.parse(data);
    
    $("#sumcompras").text(addCommas(data.sumatotalcompras));

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
