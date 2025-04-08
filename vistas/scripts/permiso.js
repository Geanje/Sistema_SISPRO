var tabla;

//funcion que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();



}



//Funcion mostrar formulario
function mostrarform(flag)
{

  if(flag)
  {
    $('#listadoregistros').hide();
    $('#formularioregistros').show();
    $('#btnGuardar').prop("disabled",false);
      $('#btnagregar').hide();

  }
  else {
    $('#listadoregistros').show();
    $('#formularioregistros').hide();
    $('#btnagregar').hide();
  }

}



//function listar
function listar()
{
  tabla=$('#tbllistado').dataTable(
    {
      "aProcessing": true, //Activamos el procesamiento de datatables
      "aServerSide": true, //Paginacion y filtrado realizados por el servidor
      dom: 'Bfrtip', //Definimos los elementos del control de tabla
      buttons:[
          'copyHtml5',
          'excelHtml5',
  
      ],
      "ajax":
      {
        url:'../ajax/permiso.php?op=listar',
        type:"get",
        dataType:"json",
        error:function(e)
        {
          console.log(e.responseText);
        }

      },
      "bDestroy":true,
      "iDisplayLength" :15, //Paginacion
      "order":[[0,"desc"]] //Ordenar (columna,orden)

    }
  ).DataTable();

}







init();
