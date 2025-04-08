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
  $("#idpracticante").val("");
  $("#nombres_apellidos").val("");
  $("#dni").val("");
  $("#institucion").val("");
  $("#sede").val("");
  $("#especialidad").val("");
  $("#modalidad").val("");
  $("#correo").val("");
  $("#numero").val("");
  $("#fecha_inicio").val("");
  $("#fecha_termino").val("");
  $("#estado").val("");
  $("#grupo").val("");
  $("#tarea").val("");

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
    $("#estadoc").hide();
    $("#agregarSucursal").hide();
    $("#btnPDF").hide();
    /*$("#consultaSunat").show();
    $("#agregarSucursal").hide();*/

  }
  else {
    $('#listadoregistros').show();
    $('#formularioregistros').hide();
    $("#agregarSucursal").show();
    $("#btnPDF").show();
    /*$("#consultaSunat").hide();
    $("#agregarSucursal").show();*/
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
        url:'../ajax/practicantes.php?op=listar',
        type:"get",
        dataType:"json",
        error:function(e)
        {
          console.log(e.responseText);
        }

      },
      "bDestroy":true,
      "iDisplayLength" :20, //Paginacion
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
      url:"../ajax/practicantes.php?op=guardaryeditar",
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



function mostrar(idpracticante)
{
 $.post("../ajax/practicantes.php?op=mostrar",{idpracticante:idpracticante}, function(data)
{
    data=JSON.parse(data);
    mostrarform(true);

    $("#idpracticante").val(data.idpracticante);
    $("#nombres_apellidos").val(data.nombres_apellidos);
    $("#dni").val(data.dni);
    $("#insitucion").val(data.institucion);
    $("#sede").val(data.sede);
    $("#especialidad").val(data.especialidad);
    $("#modalidad").val(data.modalidad);
    $("#correo").val(data.correo);
    $("#numero").val(data.numero);
    $("#fecha_inicio").val(data.fecha_inicio);
    $("#fecha_termino").val(data.fecha_termino);
    $("#estado").val(data.estado);
    $("#estadoc").show();
    $("#grupo").val(data.grupo);
    $("#tarea").val(data.tarea);
    
})
}
//funcion desactivar
function eliminar(idpracticante)
{
  bootbox.confirm("¿Esta seguro eliminar al practicante",function(result)
{
  if(result)
  {
    $.post("../ajax/practicantes.php?op=eliminar",{idpracticante:idpracticante},function(e){
      bootbox.alert(e);
        tabla.ajax.reload();
    });
  }
})
}







init();
