var tabla;

//funcion que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();
  $.post("../ajax/confidencial.php?op=mostrarp",(data)=>{
        $("#selectproveedor").html(data);
        $('#selectproveedor').selectpicker('refresh');
        // console.log(data);
  });

  $("#formAgregarCarga").on("submit",function(e)
  {
    guardaryeditar(e);
  }

  )
  // calcular();
  $("#ajusteValores").change(calcular);
  $("#botiquinQuimico").change(calcular);
  $("#impuestosSUNAT").change(calcular);
  $("#flete").change(calcular);
  $("#almacen").change(calcular);
  $("#agenteADUANA").change(calcular);
  $("#cargador").change(calcular);
  $("#pato").change(calcular);
  $("#comisionista").change(calcular);
  $("#compraProductos").change(calcular);

   $("#ajusteValores").keyup(calcular);
  $("#botiquinQuimico").keyup(calcular);
  $("#impuestosSUNAT").keyup(calcular);
  $("#flete").keyup(calcular);
  $("#almacen").keyup(calcular);
  $("#agenteADUANA").keyup(calcular);
  $("#cargador").keyup(calcular);
  $("#pato").keyup(calcular);
  $("#comisionista").keyup(calcular);
  $("#compraProductos").keyup(calcular);

}

//Funcion limpiar
function limpiar() {

  $("#idconfidencial").val("");
  $("#idpersona").val("");
  $("#ajusteValores").val("0");
  $("#botiquinQuimico").val("0");
  $("#impuestosSUNAT").val("0");
  $("#flete").val("0");
  $("#almacen").val("0");
  $("#agenteADUANA").val("0");
  $("#cargador").val("0");
  $("#pato").val("0");
  $("#comisionista").val("0");
  $("#compraProductos").val("");

  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);
  var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
  $('#fecha').val(today);

  $("#gastos_totalesHTML").html("0");
  $("#gastos_totales").val("0");
  $("#compraProductosCopia").html("0");
  $("#total").html("0");
  $("#porcentaje").html("0");
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
    $("#btnagregar").hide();

    var ahora = new Date();
    var mes = ("0" + (ahora.getMonth() + 1)).slice(-2);
    var dia = ("0" + ahora.getDate()).slice(-2);
    var hoy = ahora.getFullYear() + "-" +mes+"-"+dia;
    $("#fecha").val(hoy)

  }
  else {
    $('#listadoregistros').show();
    $('#formularioregistros').hide();
    $("#btnagregar").show();
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
          // 'csvHtml5',
          'pdf'

      ],
      "ajax":
      {
        url:'../ajax/confidencial.php?op=listar',
        type:"get",
        dataType:"json",
        error:function(e){
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

  let porcentajeTotal = $("#porcentaje").text();
  if(isNaN(porcentajeTotal) || porcentajeTotal<1 ){
      // alert(datos);
        swal({
          title: "¡Error!",
          text: "¡Ocurrio un error, por favor registrelo nuevamente!",
          type:"warning",
          confirmButtonText: "Cerrar",
          closeOnConfirm: true
        },

        function(isConfirm){

          if(isConfirm){
            location.reload(true);
          }
        });
  }else{

      var formData=new FormData($("#formAgregarCarga")[0]);

      $.ajax(
        {
          url:"../ajax/confidencial.php?op=guardaryeditar",
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
}

// function mostrar(idconfidencial)
// {
//    $.post("../ajax/confidencial.php?op=mostrar",{idconfidencial:idconfidencial}, function(data,status)
//   {
//       data=JSON.parse(data);
//       mostrarform(true);

//       $("#nombre").val(data.nombre);
//       $("#descripcion").val(data.descripcion);
//       $("#idcategoria").val(data.idcategoria);
//   })
// }

function calcular(){
  let ajusteValores = parseFloat($("#ajusteValores").val());
  let botiquinQuimico = parseFloat($("#botiquinQuimico").val());
  let impuestosSUNAT = parseFloat($("#impuestosSUNAT").val());
  let flete = parseFloat($("#flete").val());
  let almacen = parseFloat($("#almacen").val());
  let agenteADUANA = parseFloat($("#agenteADUANA").val());
  let cargador = parseFloat($("#cargador").val());
  let pato = parseFloat($("#pato").val());
  let comisionista = parseFloat($("#comisionista").val());

  let gastosTotales = ajusteValores+botiquinQuimico+impuestosSUNAT+flete+almacen+agenteADUANA+cargador+pato+comisionista;
  $("#gastos_totalesHTML").html(gastosTotales.toFixed(2));
  $("#gastos_totales").val(gastosTotales.toFixed(2));

  let compraProductos = $("#compraProductos").val();
  $("#compraProductosCopia").html(compraProductos);


  let total = gastosTotales + parseFloat(compraProductos);
  $("#total").html(total);
  let porcentaje = total/compraProductos;

  if(porcentaje =="Infinity"){
    porcentaje = "Resultado indefinido"
    $("#porcentaje").html(porcentaje);

  }else{
    $("#porcentaje").html(porcentaje.toFixed(2));

  }
  // console.log(porcentaje);
}

//funcion desactivar
function desactivar(idconfidencial)
{
  bootbox.confirm("¿Esta seguro de desactivar la categoria?",function(result)
{
  if(result)
  {
    $.post("../ajax/categoria.php?op=desactivar",{idconfidencial:idconfidencial},function(e){
      bootbox.alert(e);
        tabla.ajax.reload();
    });
  }
})
}

function activar(idconfidencial)
{
  bootbox.confirm("¿Esta seguro de activar la categoria?",function(result)
{
  if(result)
  {
    $.post("../ajax/categoria.php?op=activar",{idconfidencial:idconfidencial},function(e){
        bootbox.alert(e);
        tabla.ajax.reload();

    });

  }

}
)

}




init();
