var tabla;

//funcion que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();
//   costoServicio();
  
  $("#formulario").on("submit",function(e){
    guardaryeditar(e);
  })
 
//   $("#consultaSunat").hide();

  



// $("#idcliente").change(rellenarCliente);



}

// function rellenarCliente(){//😀
// 	var idcliente=$("#idcliente").val();

// 	var cliente=$("#id_r_servicio").prop("selected",true);
// 	if(cliente){
// 		$.post("../ajax/registro_servicios.php?op=mostrarDatoCliente",{idcliente : idcliente},function(data){
// 			data = JSON.parse(data);

//       $("#telefono").val(data.telefono);
// 			$("#direccion").val(data.direccion);
// 			$("#num_documento").val(data.num_documento);
// 		});
// 	}
// }

//Funcion limpiar
function limpiar() {
  $("#idservicio").val("");
//   $("#idcliente").val("");
//   $("#telefono").val("");
//   $("#direccion").val("");
//    $("#fecha_inicio").val("");
   $("#nombre").val("");
   $("#costo").val("");   
}
// funcion para habilitar los campos de los datos del cliente
// function offdisabled(){
//     $("#telefono").prop("readonly", false);
//     $("#direccion").prop("readonly", false);
//     const myDiv = document.getElementById('select');
//     myDiv.hidden = false;
//     $("#nombre_cliente_span").hide();
// }


var i=0;
var detalles=0;

// function mostrarVentana() {
//   $('#ventanaEmergente').show();
//   console.log("Hola mostrarVentana")
  
// }


// function cerrarVentanaEmergente() {
//   document.getElementById('ventanaEmergente').style.display = 'none';
// }

//Variable para almacenar el ultimo saldo
var ultimoSaldo = 0;
//aqui
// function mostrarPagos(idsoporte) {
//   console.log("Hola mostrarPagos")
//   $.ajax({
//     url: "../ajax/servicios-soporte.php?op=mostrarPagos&idsoporte=" + idsoporte,
//     type: "POST",
//     dataType: "json",
//     success: function(data) {
   
//       $('#tblpagos').empty(); // limpiar la tabla antes de agregar nuevas filas

//       var filaCabecera = '<tr>' +
//         '<th>Fecha de pago</th>' +
//         '<th>Monto pagado</th>' +
//         '<th>Saldo Restante</th>' +
//         '<th>Tipo de pago  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="button" onclick="mostrarVentana()" value="+" id="btnagregar"></th>' +
//         '</tr>';
//       $('#tblpagos').append(filaCabecera);

//       for (var i = 0; i < data.aaData.length; i++) {
//        // console.log(data.aaData[i]);
//         var fila = '<tr class="filas">' +
//           '<td>' + data.aaData[i][0] + '</td>' +
//           '<td>' + data.aaData[i][1] + '</td>' +
//           '<td>' + data.aaData[i][2] + '</td>' +
//           '<td>' + data.aaData[i][3] + '</td>' +
//           '</tr>';
//         $('#tblpagos').append(fila);

//         // Actualiza el valor del último saldo en cada iteración
//         ultimoSaldo = parseFloat(data.aaData[i][2]);
//         //aqui
//       }

//      // console.log("Último saldo:", ultimoSaldo);
      
//       // Realiza operaciones adicionales con el último saldo aquí

//     },
//     error: function(jqXHR, textStatus, errorThrown) {
//       console.log("Error en la petición AJAX: " + textStatus + " - " + errorThrown);
//       console.log(jqXHR.responseText);
//     }
//   });
//     //  console.log("Último saldos:", ultimoSaldo)
// }

// function guardarPagos() {
//   // Obtener los valores de los campos de entrada dentro de la ventana emergente
//   var fechaPago = document.getElementById('fecha_pago').value;
//   var cuotas = parseFloat(document.getElementById('cuotas').value);
//   var saldos = parseFloat(document.getElementById('saldos').value);
//   var tipoPago = document.getElementById('tipo_pago').value;
//   var idcliente = document.getElementById('idcliente').value;
//   var idsoporte = document.getElementById('idsoporte').value;

//   // Enviar los datos al servidor mediante AJAX
//   $.ajax({
//     url: '../ajax/servicios-soporte.php?op=insertarPago',
//     method: 'POST',
//     data: {
//       fecha_pago: fechaPago,
//       cuotas: cuotas,
//       saldos: saldos,
//       tipo_pago: tipoPago,
//       idcliente: idcliente,
//       idsoporte: idsoporte,
//     },
//     success: function(datos)
// 	    {
// 			  // bootbox.alert(datos);
			
// 	        if(datos !="" || datos !=null){
//             if (saldos === 0) {
//               swal({
//                 title: "El servicio ha sido cancelado",
//                 text: "Â¡" + datos + "!",
//                 type: "success",
//                 confirmButtonText: "Cerrar",
//                 closeOnConfirm: true
//               });
//             } else {
//               swal({
//                 title: "El pago se ha realizado con Ã©xito",
//                 text: "Â¡" + datos + "!",
//                 type: "success",
//                 confirmButtonText: "Cerrar",
//                 closeOnConfirm: true
//               });
//             }
// 	        }else{
// 	        	swal({
// 				  title: "Error!",
// 				  text: "Â¡Ocurrio un error, por favor registre otra vez el pago",
// 				  type:"warning",
// 				  confirmButtonText: "Cerrar",
// 				  closeOnConfirm: true
// 				},
// 				);
// 	        }
// 	        mostrarPagos(idsoporte);
// 	    },
//     error: function(xhr, status, error) {
//       // Manejar los errores de la solicitud AJAX aquÃ­
//       console.log(xhr.responseText);
//       console.log(status);
//       console.log(error);
//       alert("Hubo un error en la solicitud AJAX. Consulta la consola para mÃ¡s detalles.");
//     }
//   });

//   // Cerrar la ventana emergente despuÃ©s de guardar los datos
//   cerrarVentanaEmergente();
  
//   // Limpiar los campos dentro de la ventana emergente
//   document.getElementById('fecha_pago').value = '';
//   document.getElementById('cuotas').value = '';
//   document.getElementById('saldos').value = '';
//   document.getElementById('tipo_pago').value = '';

//  // return mostrarPagos(idsoporte);
// }

function mostrarform(flag)
{
//   offdisabled()
    $("#cuotasdepago").hide();
  limpiar();
  if(flag)
  {
    $('#listadoregistros').hide();
    $('#formularioregistros').show();
    $('#btnGuardar').prop("disabled",false);   

  }
  else {
    $('#listadoregistros').show();
    $('#formularioregistros').hide();
    $("#agregarservicios").show();
    $("#agregarservicios").hide();
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
        url:'../ajax/reg_servicio.php?op=listar',
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



function guardaryeditar(e)
{

  e.preventDefault();// No se activara la accion predeterminada del evento
  $("#btnGuardar").prop("disabled",true);
  var formData=new FormData($("#formulario")[0]);

  $.ajax(
    {
      url:"../ajax/reg_servicio.php?op=guardaryeditar",
      type:"POST",
      data:formData,
      contentType:false,
      processData:false,

      success:function(datos)
      {
          alert(datos);
         // console.log(datos);
          mostrarform(false);
          tabla.ajax.reload();
      }
    }
  );
  limpiar();
}
//variable para almacenar el valor del total
var total=0;
function mostrar(idservicio)
{
  //console.log(id_r_servicio);
 $.post("../ajax/reg_servicio.php?op=mostrar",{idservicio:idservicio}, function(data,status)
{
    data=JSON.parse(data);
    mostrarform(true);
    $("#idservicio").val(data.idservicio);
    $("#costo").val(data.costo);
    $("#nombre").val(data.nombre);
    $("#costo_dia").val(data.costo_dia);
    $("#costo_dia_31").val(data.costo_dia_31);
    $("#costo_dia_28").val(data.costo_dia_28);
    $("#costo_dia_29").val(data.costo_dia_29);
})
//mostrarPagos(idsoporte);
}
function desactivar(idservicio)
{
console.log(id_r_servicio);
  bootbox.confirm("Esta seguro desactivar el servicio",function(data)
{
  if(data)
  {
    $.post("../ajax/registro_servicios.php?op=desactivar",{idservicio:idservicio},function(e){
      bootbox.alert(e);
      console.log(e);
        tabla.ajax.reload();
    });
  }
})
}

function activar(idservicio)
{
console.log(id_r_servicio);
  bootbox.confirm("Desea activar el servicio",function(data)
{
  if(data)
  {
    $.post("../ajax/registro_servicios.php?op=activar",{idservicio:idservicio},function(e){
      bootbox.alert(e);
      console.log(e);
        tabla.ajax.reload();
    });
  }
})
}

$(function () {
 
  let cuotas = $("#cuotas");

  cuotas.keyup(function () {
    costoServicio();
  });
});

  // let costo_dia = $("$costo_dia");
  // let costo = $("costo");
  // let costo_dia = document .getElementById("costo_dia").value;

  // costo.keyup(function() {
  //   promedioPorDia();
  // })
  $(function() {
    let costo = $("#costo");
  
    costo.keyup(function() {
      promedioPorDia();
    });
  });
  
  function promedioPorDia() {
    let costo = parseFloat($("#costo").val()); // Obtener el valor del campo de costo y convertirlo a un número
    let costo_dia = (costo / 30).toFixed(2);
    let costo_dia_31 = (costo / 31).toFixed(2);
    let costo_dia_28 = (costo / 28).toFixed(2);
    let costo_dia_29 = (costo / 29).toFixed(2);
    $("#costo_dia").val(costo_dia);
    $("#costo_dia_31").val(costo_dia_31);
    $("#costo_dia_28").val(costo_dia_28);
    $("#costo_dia_29").val(costo_dia_29);
  }

function costoServicio(){  
  let cuotas = $("#cuotas").val();
  if (ultimoSaldo>0) {
    let saldos = ultimoSaldo - cuotas;
  $("#saldos").val(saldos);
  
  }else{
    let saldos = total - cuotas;
    $("#saldos").val(saldos);
  }
  
}


init();
