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

  

  $.post("../ajax/registro_servicios.php?op=selectCliente", function(r){
    $("#idcliente").html(r);
    $('#idcliente').selectpicker('refresh');
});


$.post("../ajax/registro_servicios.php?op=listarServicio", function(r){
  $("#idservicio").html(r);
  $('#idservicio').selectpicker('refresh');
});


$("#idcliente").change(rellenarCliente);

$("#idservicio").change(rellenarServicio);




}

function rellenarCliente(){//😀
	var idcliente=$("#idcliente").val();

	var cliente=$("#id_r_servicio").prop("selected",true);
	if(cliente){
		$.post("../ajax/registro_servicios.php?op=mostrarDatoCliente",{idcliente : idcliente},function(data){
			data = JSON.parse(data);

      $("#telefono").val(data.telefono);
			$("#direccion").val(data.direccion);
			$("#num_documento").val(data.num_documento);
		});
	}
}





//Funcion limpiar
function limpiar() {
  $("#id_r_servicio").val("");
  $("#idcliente").val("");
  $("#telefono").val("");
  $("#direccion").val("");
   $("#fecha_inicio").val("");
   $("#concepto").val("");
   $("#monto_pago").val("");   
}
// funcion para habilitar los campos de los datos del cliente
function offdisabled(){
    $("#telefono").prop("readonly", false);
    $("#direccion").prop("readonly", false);
    const myDiv = document.getElementById('select');
    myDiv.hidden = false;
    $("#nombre_cliente_span").hide();
}


var i=0;
var detalles=0;

function mostrarVentana() {
  $('#ventanaEmergente').show();
  console.log("Hola mostrarVentana")
  
}


function cerrarVentanaEmergente() {
  document.getElementById('ventanaEmergente').style.display = 'none';
}

//Variable para almacenar el ultimo saldo
var ultimoSaldo = 0;
//aqui
function mostrarPagos(idsoporte) {
  console.log("Hola mostrarPagos")
  $.ajax({
    url: "../ajax/servicios-soporte.php?op=mostrarPagos&idsoporte=" + idsoporte,
    type: "POST",
    dataType: "json",
    success: function(data) {
   
      $('#tblpagos').empty(); // limpiar la tabla antes de agregar nuevas filas

      var filaCabecera = '<tr>' +
        '<th>Fecha de pago</th>' +
        '<th>Monto pagado</th>' +
        '<th>Saldo Restante</th>' +
        '<th>Tipo de pago  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="button" onclick="mostrarVentana()" value="+" id="btnagregar"></th>' +
        '</tr>';
      $('#tblpagos').append(filaCabecera);

      for (var i = 0; i < data.aaData.length; i++) {
       // console.log(data.aaData[i]);
        var fila = '<tr class="filas">' +
          '<td>' + data.aaData[i][0] + '</td>' +
          '<td>' + data.aaData[i][1] + '</td>' +
          '<td>' + data.aaData[i][2] + '</td>' +
          '<td>' + data.aaData[i][3] + '</td>' +
          '</tr>';
        $('#tblpagos').append(fila);

        // Actualiza el valor del último saldo en cada iteración
        ultimoSaldo = parseFloat(data.aaData[i][2]);
        //aqui
      }

     // console.log("Último saldo:", ultimoSaldo);
      
      // Realiza operaciones adicionales con el último saldo aquí

    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log("Error en la petición AJAX: " + textStatus + " - " + errorThrown);
      console.log(jqXHR.responseText);
    }
  });
    //  console.log("Último saldos:", ultimoSaldo)
}

function guardarPagos() {
  // Obtener los valores de los campos de entrada dentro de la ventana emergente
  var fechaPago = document.getElementById('fecha_pago').value;
  var cuotas = parseFloat(document.getElementById('cuotas').value);
  var saldos = parseFloat(document.getElementById('saldos').value);
  var tipoPago = document.getElementById('tipo_pago').value;
  var idcliente = document.getElementById('idcliente').value;
  var idsoporte = document.getElementById('idsoporte').value;

  // Enviar los datos al servidor mediante AJAX
  $.ajax({
    url: '../ajax/servicios-soporte.php?op=insertarPago',
    method: 'POST',
    data: {
      fecha_pago: fechaPago,
      cuotas: cuotas,
      saldos: saldos,
      tipo_pago: tipoPago,
      idcliente: idcliente,
      idsoporte: idsoporte,
    },
    success: function(datos)
	    {
			  // bootbox.alert(datos);
			
	        if(datos !="" || datos !=null){
            if (saldos === 0) {
              swal({
                title: "El servicio ha sido cancelado",
                text: "Â¡" + datos + "!",
                type: "success",
                confirmButtonText: "Cerrar",
                closeOnConfirm: true
              });
            } else {
              swal({
                title: "El pago se ha realizado con Ã©xito",
                text: "Â¡" + datos + "!",
                type: "success",
                confirmButtonText: "Cerrar",
                closeOnConfirm: true
              });
            }
	        }else{
	        	swal({
				  title: "Error!",
				  text: "Â¡Ocurrio un error, por favor registre otra vez el pago",
				  type:"warning",
				  confirmButtonText: "Cerrar",
				  closeOnConfirm: true
				},
				);
	        }
	        mostrarPagos(idsoporte);
	    },
    error: function(xhr, status, error) {
      // Manejar los errores de la solicitud AJAX aquÃ­
      console.log(xhr.responseText);
      console.log(status);
      console.log(error);
      alert("Hubo un error en la solicitud AJAX. Consulta la consola para mÃ¡s detalles.");
    }
  });

  // Cerrar la ventana emergente despuÃ©s de guardar los datos
  cerrarVentanaEmergente();
  
  // Limpiar los campos dentro de la ventana emergente
  document.getElementById('fecha_pago').value = '';
  document.getElementById('cuotas').value = '';
  document.getElementById('saldos').value = '';
  document.getElementById('tipo_pago').value = '';

 // return mostrarPagos(idsoporte);
}

function mostrarform(flag)
{
  offdisabled()
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
        url:'../ajax/registro_servicios.php?op=listar',
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
      url:"../ajax/registro_servicios.php?op=guardaryeditar",
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
function mostrar(id_r_servicio)
{
  console.log(id_r_servicio);
 $.post("../ajax/registro_servicios.php?op=mostrar",{id_r_servicio:id_r_servicio}, function(data,status)
{
    data=JSON.parse(data);
    mostrarform(true);
    //console.log("funcion mostrar");
    $("#id_r_servicio").val(data.id_r_servicio);
    $("#idcliente").hide();
    $("#idservicio").val(data.idservicio);//😂
    $("#idservicio").selectpicker('refresh');//😂
    $("#idcliente").val(data.idcliente);//😂
    //$("#idservicio").val(data.nombre);//😂
   // $("#idservicio").hide();
    $("#fecha_inicio").val(data.fecha_inicio);
    $("#fecha_termino").val(data.fecha_termino);
    $("#telefono").val(data.telefono);//😂
    $("#telefono").prop("readonly", true);//para que no se pueda editar
    $("#direccion").val(data.direccion);//😂
    $("#direccion").prop("readonly", true);//para que no se pueda editar
    $("#monto_pago").val(data.costo);
    // $("#concepto").val(data.concepto);
    //$("#estado").val(data.estado);
    $("#num_documento").val(data.num_documento);
    //console.log(id_p_servicio);
    var nombreCliente = $("#idcliente option:selected").text();
    $("#nombre_cliente_span").text(nombreCliente);
    $("#nombre_cliente_span").show();
    const myDiv = document.getElementById('select');
    myDiv.hidden = true;

})
//mostrarPagos(idsoporte);
}
function desactivar(id_r_servicio)
{
console.log(id_r_servicio);
  bootbox.confirm("Esta seguro desactivar el servicio",function(data)
{
  if(data)
  {
    $.post("../ajax/registro_servicios.php?op=desactivar",{id_r_servicio:id_r_servicio},function(e){
      bootbox.alert(e);
      console.log(e);
        tabla.ajax.reload();
    });
  }
})
}

function activar(id_r_servicio)
{
console.log(id_r_servicio);
  bootbox.confirm("Desea activar el servicio",function(data)
{
  if(data)
  {
    $.post("../ajax/registro_servicios.php?op=activar",{id_r_servicio:id_r_servicio},function(e){
      bootbox.alert(e);
      console.log(e);
        tabla.ajax.reload();
    });
  }
})
}

var daysDifference;
var nombreMes;
function rellenarServicio(){
	var idservicio=$("#idservicio").val();
//console.log(nombreMes);
	var cliente=$("#id_r_servicio").prop("selected",true);
	if(cliente){
		$.post("../ajax/registro_servicios.php?op=mostrarDatoServicio",{idservicio : idservicio},function(data){
			data = JSON.parse(data);
      var costo_dia = data.costo_dia;
      var costo_dia_31 = data.costo_dia_31;
      var costo_dia_28 = data.costo_dia_28;
      var costo_dia_29 = data.costo_dia_29;
      var monto_servicio;
      daysDifference = daysDifference + 1;
      // console.log(costo_dia_31);
      var endDateObj = new Date(endDate);
      var dayOfMonth = endDateObj.getUTCDate();
      //console.log(dayOfMonth);
      if (dayOfMonth === 30) {
        //console.log('entro al de dia 30');
        if (daysDifference == 30) {
          monto_servicio = data.costo;
        } else {
          monto_servicio = (costo_dia * daysDifference).toFixed(2);
        }
      } else if (nombreMes == 'Febrero') {
        //console.log('entro al febrero');
        if (dayOfMonth == 28) {
          //console.log('entro al feb 28');
          if (daysDifference == 28) {
            monto_servicio = data.costo;
          } else {
            monto_servicio = (costo_dia_28 * daysDifference).toFixed(2);
          }
        } else if (dayOfMonth == 29) {
          //console.log('entro al feb 29');
          if (daysDifference == 29) {
            monto_servicio = data.costo;
          } else {
            monto_servicio = (costo_dia_29 * daysDifference).toFixed(2);
          }
        }
        // monto_servicio = data.costo;
      } else if (dayOfMonth === 31) {
        //console.log('entro al de dia 31');
        if (daysDifference == 31) {
          monto_servicio = data.costo;
        } else {
          monto_servicio = (costo_dia_31 * daysDifference).toFixed(2);
        }
      } else {
        // monto_servicio = (costo_dia * daysDifference).toFixed(2);
        console.log('algo salio mal');
      }
      $("#monto_pago").val(monto_servicio);
		});
	}
}
var endDate;

function calcularDias() {
  var startDate = document.getElementById("fecha_inicio").value;
   endDate = document.getElementById("fecha_termino").value;
  //console.log(startDate);
  //console.log(endDate);
  if (startDate) {
    var fechaSeleccionada = new Date(endDate);
    var diaSeleccionado = fechaSeleccionada.getDate().toString();
    
    // if (diaSeleccionado === '1') {
    //   //console.log("Has seleccionado el día 1");
    //   // Realiza acciones adicionales si el día seleccionado es 1
    // }
  } else {
    console.log("No funciona :C");
  }

  var meses = [
    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
  ];
  nombreMes = meses[fechaSeleccionada.getMonth()]; // Obtiene el nombre del mes
  if (startDate && endDate) {
    var startDateObj = new Date(startDate);
    var endDateObj = new Date(endDate);

    // Calcula la diferencia en milisegundos entre las dos fechas
    var timeDifference = endDateObj.getTime() - startDateObj.getTime();
    
    // Convierte los milisegundos a días
    daysDifference = Math.ceil(timeDifference / (1000 * 3600 * 24));
    //console.log("se llama el geenrerar calcular");
    var idservicio = $("#idservicio").val();
    if (idservicio) {
      rellenarServicio(); // Llama a la función rellenarServicio() solo si el select está seleccionado
    }
    // var startDate = document.getElementById("fecha_inicio").value;
    
  }
}


$(function () {
 
  let cuotas = $("#cuotas");

  cuotas.keyup(function () {
    costoServicio();
  });
});


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
