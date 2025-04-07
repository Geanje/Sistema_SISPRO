var tabla;

//funcion que se ejecuta al inicio
function init() {

  listar();
  //Cargamos los items al select proveedor
  $.post("../ajax/venta2.php?op=selectCliente", function(r){
              $("#idcliente").html(r);
              $('#idcliente').selectpicker('refresh');
  });



}



//function listar
function listar()
{

    var fecha_inicio=$("#fecha_inicio").val();
    var fecha_fin=$("#fecha_fin").val();
    var idcliente=$("#idcliente").val();
    var tipoventa=$("#tipoventa").val();


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
        url:'../ajax/consultas.php?op=ventasfechacliente',
        data:{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,idcliente:idcliente,tipoventa:tipoventa},
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
    $.post("../ajax/consultas.php?op=sumVentasFechaCliente",{fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,idcliente:idcliente,tipoventa:tipoventa},function(data,status){
      data=JSON.parse(data);
      $("#client").text(data.cliente);
      $("#sumventacliente").text(addCommas(data.sumatotalcliente));

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
