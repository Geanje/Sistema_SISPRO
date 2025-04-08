var tabla;

//funcion que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();

  $("#formulario").on("submit",function(e)
  {
    guardaryeditar(e);
  }

  )


}

//Funcion limpiar
function limpiar() {
  $("#nombre").val("");
  $("#descripcion").val("");

}

//Funcion mostrar formulario
function mostrarform(flag)
{
  limpiar();
  if(flag)
  {
    $('#listadoregistros').hide();
    $('#formularioregistros').show();
    $('#btnGuardar').prop("disabled",false);
    $("#agregarCateg").hide();


  }
  else {
    $('#listadoregistros').show();
    $('#formularioregistros').hide();
    $("#agregarCateg").show();

  }

}

//funcion cancelarform
function cancelarform()
{
  limpiar();
  mostrarform(false);


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
          'csvHtml5',
          'pdf'

      ],
      "ajax":
      {
        url:'../ajax/categoria.php?op=listar',
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

}

function guardaryeditar(e)
{
  e.preventDefault();// No se activara la accion predeterminada del evento
  $("#btnGuardar").prop("disabled",true);
  var formData=new FormData($("#formulario")[0]);

  $.ajax(
    {
      url:"../ajax/categoria.php?op=guardaryeditar",
      type:"POST",
      data:formData,
      contentType:false,
      processData:false,

      success:function(datos)
      {
          alert(datos);
          mostrarform(false);
          tabla.ajax.reload();

      }

    }
  );
  limpiar();
}

function mostrar(idcategoria)
{
 $.post("../ajax/categoria.php?op=mostrar",{idcategoria:idcategoria}, function(data,status)
{
    data=JSON.parse(data);
    mostrarform(true);

    $("#nombre").val(data.nombre);
    $("#descripcion").val(data.descripcion);
    $("#idcategoria").val(data.idcategoria);
})
}
//funcion desactivar
function desactivar(idcategoria)
{
  bootbox.confirm("¿Esta seguro de desactivar la categoria?",function(result)
{
  if(result)
  {
    $.post("../ajax/categoria.php?op=desactivar",{idcategoria:idcategoria},function(e){
      bootbox.alert(e);
        tabla.ajax.reload();
    });
  }
})
}

function activar(idcategoria)
{
  bootbox.confirm("¿Esta seguro de activar la categoria?",function(result)
{
  if(result)
  {
    $.post("../ajax/categoria.php?op=activar",{idcategoria:idcategoria},function(e){
        bootbox.alert(e);
        tabla.ajax.reload();

    });

  }

}
)

}




init();
