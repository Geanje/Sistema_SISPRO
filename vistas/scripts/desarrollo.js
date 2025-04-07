var tabla;

//funcion que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();
  costoServicio();

  $("#formulario").on("submit",function(e){
    guardaryeditar(e);
  })

  $("#consultaSunat").hide();

}

//Funcion limpiar
function limpiar() {
   $("#fecha_ingreso").val("");
   $("#fecha_salida").val("");
   $("#nombre_cliente").val("");
   $("#tipo_equipo").val("");
   $("#marca").val("");
   $("#problema").val("");
   $("#solucion").val("");
   $("#telefono").val("");
   $("#tecnico_respon").val("");
   $("#codigo_soporte").val("");
   $("#idsoporte").val("");
   $("#estado_servicio").val("");
   $("#estado_pago").val("");
   $("#total").val("");
   $("#cuota").val("");
   $("#saldo").val("");
   $("#direccion").val("");
   $("#accesorio").val("");
   $("#recomendacion").val("");
   $("#garantia").val("");
   

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
   
    $("#agregarservicios").hide();

  }
  else {
    $('#listadoregistros').show();
    $('#formularioregistros').hide();
 
    $("#agregarservicios").show();
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
        url:'../ajax/desarrollo.php?op=listar',
        type:"get",
        dataType:"json",
        error:function(e)
        {
          console.log(e.responseText);
        }

      },
      "bDestroy":true,
      "iDisplayLength" :10, //Paginacion
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
      url:"../ajax/desarrollo.php?op=guardaryeditar",
      type:"POST",
      data:formData,
      contentType:false,
      processData:false,

      success:function(datos)
      {
          alert(datos);
          console.log(datos);
          mostrarform(false);
          tabla.ajax.reload();

      }

    }
  );
  limpiar();
}


function mostrar(idsoporte)
{
 $.post("../ajax/desarrollo.php?op=mostrar",{idsoporte:idsoporte}, function(data,status)
{
    data=JSON.parse(data);
    mostrarform(true);
    //console.log(data);

    $("#fecha_ingreso").val(data.fecha_ingreso);
    $("#fecha_salida").val(data.fecha_salida);
    $("#nombre_cliente").val(data.nombre_cliente);
    $("#tipo_equipo").val(data.tipo_equipo);
    $("#marca").val(data.marca);
    $("#problema").val(data.problema);
    $("#solucion").val(data.solucion);
    $("#telefono").val(data.telefono);
    $("#tecnico_respon").val(data.tecnico_respon);
    $("#codigo_soporte").val(data.codigo_soporte);
    $("#idsoporte").val(data.idsoporte);
    $("#estado_servicio").val(data.estado_servicio);
    $("#estado_pago").val(data.estado_pago);
    $("#total").val(data.total);
    $("#cuota").val(data.cuota);
    $("#saldo").val(data.saldo);
    $("#direccion").val(data.direccion);
    $("#accesorio").val(data.accesorio);
    $("#recomendacion").val(data.recomendacion);
    $("#garantia").val(data.garantia);

})
}
//funcion desactivar
function eliminar(codigo_soporte)
{

  bootbox.confirm("¿Esta seguro eliminar el cliente",function(result)
{
  if(result)
  {
    $.post("../ajax/desarrollo.php?op=eliminar",{codigo_soporte:codigo_soporte},function(e){
      bootbox.alert(e);
        tabla.ajax.reload();
    });
  }
})
}

$(function () {
  let total = $("#total");
  let cuota = $("#cuota");

  total.keyup(function () {
    costoServicio();
  });

  cuota.keyup(function () {
    costoServicio();
  });
});


function costoServicio(){
  let total = $("#total").val();
  let cuota = $("#cuota").val();

  let saldo = total - cuota;
  $("#saldo").val(saldo);
}




$(function () {
  let fecha_ingreso = $("#fecha_ingreso");
  let fecha_salida = $("#fecha_salida");

  fecha_ingreso.keyup(function () {
    fechaRestante();
  });

  fecha_salida.keyup(function () {
    fechaRestante();
  });
});

function fechaRestante(){

  console.log('Hay no se restan los días');
 

}





init();
