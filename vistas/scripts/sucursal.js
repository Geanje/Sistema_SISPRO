var tabla;

//funcion que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();

  $("#formulario").on("submit",function(e){
    guardaryeditar(e);
  })

  $("#consultaSunat").hide();
}

//Funcion limpiar
function limpiar() {
  $("#nombre").val("");
  $("#num_documento").val("-");
  $("#direccion").val("");
  $("#telefono").val("");
  $("#email").val("");
  $("#idpersona").val("");
  $("#razon_social").val("");

}

//Funcion mostrar formulario
function mostrarform(flag)
{
  // limpiar();
  if(flag)
  {
    $('#listadoregistros').hide();
    $('#formularioregistros').show();
    $('#btnGuardar').prop("disabled",false);
    $("#consultaSunat").show();
    $("#agregarSucursal").hide();
    $('#btnPDF').hide();

  }
  else {
    $('#listadoregistros').show();
    $('#formularioregistros').hide();
    $("#consultaSunat").hide();
    $("#agregarSucursal").show();
    $('#btnPDF').show();
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
      ],
      "ajax":
      {
        url:'../ajax/persona.php?op=listars',
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
      url:"../ajax/persona.php?op=guardaryeditar",
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



function mostrar(idpersona)
{
 $.post("../ajax/persona.php?op=mostrar",{idpersona:idpersona}, function(data,status)
{
    data=JSON.parse(data);
    mostrarform(true);

    $("#nombre").val(data.nombre);
    $("#tipo_documento").val(data.tipo_documento);
    $("#tipo_documento").selectpicker('refresh');
    $("#num_documento").val(data.num_documento);
    $("#direccion").val(data.direccion);
    $("#telefono").val(data.telefono);
    $("#email").val(data.email);
    $("#idpersona").val(data.idpersona);
    $("#razon_social").val(data.razon_social);
})
}
//funcion desactivar
function eliminar(idpersona)
{
  bootbox.confirm("¿Esta seguro eliminar el cliente",function(result)
{
  if(result)
  {
    $.post("../ajax/persona.php?op=eliminar",{idpersona:idpersona},function(e){
      bootbox.alert(e);
        tabla.ajax.reload();
    });
  }
})
}





init();
