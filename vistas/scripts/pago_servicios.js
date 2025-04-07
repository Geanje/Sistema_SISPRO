var tabla;

//funcion que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();
  costoServicio();
  
  // $("#formulario").on("submit",function(e){
  //   guardaryeditar(e);
  // })
 
  $("#consultaSunat").hide();

  

  $.post("../ajax/servicios-soporte.php?op=selectCliente", function(r){
    $("#idcliente").html(r);
    $('#idcliente').selectpicker('refresh');
});

$("#idcliente").change(rellenarCliente);



}
var clie;
function rellenarCliente(){//😀
	var clientee=$("#idcliente").val();
	clie=clientee;
//console.log(clientee);
	var idcliente=$("#idcliente").prop("selected",true);
	if(idcliente){
		// console.log(clientee);

		$.post("../ajax/guia.php?op=mostrarDatoCliente",{idcliente : clientee},function(data){
			data = JSON.parse(data);

			$("#telefono").val(data.num_documento);
			$("#direccioncliente").val(data.direccion);

		});
		// $("#idcliente").val(data.idcliente);
	}
	//Aqui llamar a la funcion que actualice la lista de comprobantes
    listarComprobantes();
}

//Funcion limpiar
function limpiar() {
  $("#idtecnico").val("");
  $("#idsoporte").val("");
  $("#idcliente").val("");
  $("#telefono").val("");
  $("#direccioncliente").val("");
   $("#fecha_ingreso").val("");
   $("#fecha_salida").val("");
   $("#nombre_cliente").val("");
   $("#tipo_equipo").val("");
   $("#marca").val("");
   $("#problema").val("");
   $("#solucion").val("");
   //$("#tecnico_respon").val("");
   $("#codigo_soporte").val("");
   $("#estado_servicio").val("");
   $("#estado_pago").val("");
   $("#total").val("");
   $("#estado_entrega").val("");
   $("#direccion").val("");
   $("#accesorio").val("");
   $("#recomendacion").val("");
   $("#garantia").val("");
   
}
// funcion para habilitar los campos de los datos del cliente
function offdisabled(){
    $("#telefono").prop("readonly", false);
    $("#direccioncliente").prop("readonly", false);
    const myDiv = document.getElementById('select');
    myDiv.hidden = false;
    $("#nombre_cliente_span").hide();
}


var i=0;
var detalles=0;

function mostrarVentana() {
  $('#ventanaEmergente').show();
}


function cerrarVentanaEmergente() {
  document.getElementById('ventanaEmergente').style.display = 'none';
}

//Variable para almacenar el ultimo saldo
var ultimoSaldo = 0;
//aqui
function mostrarPagos(idsoporte) {
  //console.log("Hola mostrarPagos")
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
        url:'../ajax/pago_servicios.php?op=listar',
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

//😎24.08.2023 toda la funcion borrarPDF
function borrarPDF(e) {
  e.preventDefault(); // Evita la redirección normal del enlace
        // Realiza la petición al archivo PHP utilizando AJAX
        $.ajax({
            url: "../ajax/pago_servicios.php?op=borrarPDF",//😎25.08.2023 se cambió el url
            type: 'GET', // O 'POST' si es necesario
            success: function (datos) {
              alert(datos);
              // console.log(datos);
              // mostrarform(false);
              // tabla.ajax.reload();
            },
            error: function (xhr, status, error) {
              console.log("Error en la llamada AJAX:", error);
            }
          });
}

function pdfindividual(idcomprobante) {//😎25.08.2023 toda la función
  $.ajax({
    url: "../reportes/pdf_individual_ps.php?id=" + idcomprobante,
    type: 'GET',
    success: function (datos) {
      alert(datos);
    },
    error: function (xhr, status, error) {
      console.log("Error en la llamada AJAX:", error);
    }
  });
}

function eliminarDetalle(indice) {
  // Remover la fila correspondiente al comprobante
  $("#fila" + indice).remove();

  // Actualizar la cantidad de detalles
  detalles = detalles - 1;

  // Actualizar el arreglo de comprobantes seleccionados
  comprobantesSeleccionados.splice(indice, 1);

  // Recalcular la suma de los valores de los inputs de cantidad
  var totalPagar = 0;

  // Iterar por cada fila de comprobante
  $('#detalles tr.filas').each(function() {
    var cantidad = $(this).find('input[name="cantidad[]"]').val();
    totalPagar += parseFloat(cantidad);
  });

  // Mostrar la suma actualizada en el elemento <span> con el ID "total_pagar"
  $("#total_pagar").text(totalPagar.toFixed(2));

  // Llamar a la función de evaluación si es necesario
  evaluar();
}

function evaluar(){
  if (detalles>0)
  {
    $("#btnGuardar").show();
  }
  else
  {
    $("#btnGuardar").show();
    cont=0;
  }
}
function generar(e)
{
//console.log("aquiiiiii");
e.preventDefault();
  bootbox.confirm("Desea generar los comprobantes",function(data)
{
  if(data)
  {
    $.post("../ajax/pago_servicios.php?op=guardaryeditar",function(e){
      bootbox.alert(e);
      //console.log(e);
        tabla.ajax.reload();
    });
  }
})
}

function generarIndividual(e) {
  e.preventDefault();
    tabla=$('#tblContratos').dataTable(
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
          url:'../ajax/pago_servicios.php?op=listarContrato',
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
  //$("#btnGuardar").prop("disabled",true);
  //var formData=new FormData($("#formulario")[0]);

  $.ajax(
    {
      url:"../ajax/pago_servicios.php?op=guardaryeditar",
      //type:"POST",
      //data:formData,
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
  //limpiar();
  listar();
}
//variable para almacenar el valor del total
var total=0;
function mostrar(idsoporte)
{
  console.log(idsoporte)
 $.post("../ajax/servicios-soporte.php?op=mostrar",{idsoporte:idsoporte}, function(data,status)
{
    data=JSON.parse(data);
    mostrarform(true);
    //console.log("funcion mostrar");
    $("#idcliente").hide();
    $("#idcliente").val(data.nombre_cliente);//😂
    //$("#idtecnico").hide();
    $("#idtecnico").val(data.tecnico_respon);//😂
    $("#idtecnico").selectpicker('refresh');//😂
    //console.log(iddeltecnico)
    $("#fecha_ingreso").val(data.fecha_ingreso);
    $("#fecha_salida").val(data.fecha_salida);
    $("#telefono").val(data.telefono);//😂
    $("#telefono").prop("readonly", true);//para que no se pueda editar
    $("#direccioncliente").val(data.direccion);//😂
    $("#direccioncliente").prop("readonly", true);//para que no se pueda editar
    $("#tipo_equipo").val(data.tipo_equipo);
    $("#marca").val(data.marca);
    $("#problema").val(data.problema);
    $("#solucion").val(data.solucion);
    $("#codigo_soporte").val(data.codigo_soporte);
    $("#idsoporte").val(data.idsoporte);
    $("#estado_servicio").val(data.estado_servicio);
    $("#estado_pago").val(data.estado_pago);
    $("#estado_entrega").val(data.estado_entrega);
    //aqui
    $("#total").val(data.total);
    total=$("#total").val();
    $("#accesorio").val(data.accesorio);
    $("#recomendacion").val(data.recomendacion);
    $("#garantia").val(data.garantia);
    $("#cuotasdepago").show();
    $("#fecha_pago").val(data.fecha_pago);
    $("#idsoportepago").val(data.idsoportepago);
    //$("#idtecnico").val(data.nombre);

    var nombreCliente = $("#idcliente option:selected").text();
    $("#nombre_cliente_span").text(nombreCliente);
    $("#nombre_cliente_span").show();
    const myDiv = document.getElementById('select');
    myDiv.hidden = true;

})
mostrarPagos(idsoporte);
}

function pruebapdf(idcomprobante) {
  $.post("../reportes/Comprobante_PDF_Pago_Servicios.php?id=" + idcomprobante, function(r) {
    console.log("Contenido del PDF:", r); // Muestra el contenido en la consola del navegador
    // var pdfLink = document.getElementById("pdfLink");
    // pdfLink.innerHTML = '<button class="btn btn-success"><i class="fa fa-check"></i> PDF Generado</button>';
  });
}




let idcomprob = 0;
function editarFechaCorte(idcomprobante) {
  $.post("../ajax/pago_servicios.php?op=mostrar",{idcomprobante:idcomprobante}, function(data,status)
  {
    data=JSON.parse(data);
    $("#fecha_corte").val(data.fecha_corte);
    $("#idcomprobante").val(data.idcomprobante);
    // idcomprob = $("#idcomprobante").val(data.idcomprobante);
    // idcomprob = idcomprobante;
  })
    idcomprob = idcomprobante;
  console.log(idcomprob);
}




function guardarFechaCorte(e) {
  e.preventDefault(); // Evita el envío del formulario por defecto

  var formData = new FormData($("#formFecha")[0]);
  formData.append('idcomprobante', idcomprob); // Agrega el valor del campo oculto
    
    // console.log(document.getElementById('fecha_corte').value);
    // console.log(document.getElementById('idcomprobante').value);
  $.ajax({
    url: "../ajax/pago_servicios.php?op=guardarFechaCorte",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      alert(datos);
      // console.log(datos);
      // mostrarform(false);
      tabla.ajax.reload();
    },
    error: function (xhr, status, error) {
      console.log("Error en la llamada AJAX:", error);
    }
  });

  $("#editComprobante").modal("hide");

  // listar();
}


function eliminar(codigo_soporte)
{

  bootbox.confirm("Esta seguro eliminar el cliente",function(result)
{
  if(result)
  {
    $.post("../ajax/servicios-soporte.php?op=eliminar",{codigo_soporte:codigo_soporte},function(e){
      bootbox.alert(e);
        tabla.ajax.reload();
    });
  }
})
}
//let total = $("#total").val();
$(function () {
  //let total = $("#total");
  let cuotas = $("#cuotas");
  //console.log("El total:",total);
  /*total.keyup(function () {
    costoServicio();
  });*/

  cuotas.keyup(function () {
    costoServicio();
  });
});
//var saldo = mostrarPagos(idsoporte);
//console.log("Saldo fuera de la función:", ultimoSaldo);



function costoServicio(){
  //console.log("total fuera de la función:", total);
  
  let cuotas = $("#cuotas").val();
  if (ultimoSaldo>0) {
    let saldos = ultimoSaldo - cuotas;
  $("#saldos").val(saldos);
  
  }else{
    let saldos = total - cuotas;
    $("#saldos").val(saldos);
  }
  
 
  //let saldos = total - cuotas;
  //$("#saldos").val(saldos);
}

function listarComprobantes()
{
	//var clie = document.getElementById(clientee).value;
	console.log(clie);
	tabla=$('#tblcomprobantes').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [

				],
		"ajax":
				{
					url: '../ajax/pago_servicios.php?op=listarComprobantes',
					type : "get",
					dataType : "json",
					data: {idcliente:clie},
					error: function(e){
						console.log(e.responseText);
					}
				},
		"bDestroy": true,
		"iDisplayLength": 9,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}
var comprobantesSeleccionados = [];
function mostrarDetalle(idcomprobante) {
  // Verificar si el comprobante ya está en la lista
  if (comprobantesSeleccionados.includes(idcomprobante)) {
    alert("El comprobante ya ha sido seleccionado.");
    return;
  }

  // Agregar el comprobante a la lista
  comprobantesSeleccionados.push(idcomprobante);

  // Lógica para mostrar los detalles del comprobante
  $.post("../ajax/pago_servicios.php?op=mostrarDetalle&id=" + idcomprobante, function(r) {
    detalles = detalles + 1;

    // Eliminar la cabecera existente antes de agregar los detalles
    $('#detalles').find('thead').remove();

    $('#detalles').append(r);
    $("#listacomprobante").val(idcomprobante);

    // Calcular la suma de los valores de los inputs de cantidad
    var totalPagar = 0;

    // Iterar por cada fila de comprobante
    $('#detalles tr.filas').each(function() {
      var cantidad = $(this).find('input[name="cantidad[]"]').val();
      totalPagar += parseFloat(cantidad);
    });

    // Mostrar la suma en el elemento <span> con el ID "total_pagar"
    $("#total_pagar").text(totalPagar.toFixed(2));
  });
}

function guardar() {
  // e.preventDefault();
  // Realizar la solicitud AJAX al backend
  console.log(comprobantesSeleccionados);
  $.ajax({
    url: '../ajax/pago_servicios.php?op=guardar',
    method: 'POST',
    data: {
      comprobantesSeleccionados: comprobantesSeleccionados
    },
    success: function(response) {
      alert(response);// Lógica adicional después de la actualización
    },
    error: function(xhr, status, error) {
      // Manejo de errores
    }
  });

  console.log("aquí estoy");
}
// var contratosSeleccionados = [];
function guardarParaContratosSeleccionados(e) {
  e.preventDefault();
  var checkboxList = document.querySelectorAll('input[name="check_list[]"]:checked');
  var contratosSeleccionados = [];
 
  var fechasSeleccionadas = {};
 
  checkboxList.forEach(function(checkbox) {
    var id_r_servicio = checkbox.value;
    var dateInput = document.querySelector('input[name="fecha_' + id_r_servicio + '"]');
    var dateInputEmision = document.querySelector('input[name="ftermino_' + id_r_servicio + '"]');
    if (dateInput) {
        contratosSeleccionados.push(id_r_servicio);
        fechasSeleccionadas[id_r_servicio] = {
            fechaInicio: dateInput.value,
            fechaEmision: dateInputEmision.value
        };
    }
});
  console.log(contratosSeleccionados);
  console.log(fechasSeleccionadas);
 
  $.ajax({
      url: '../ajax/pago_servicios.php?op=guardarParaContratos',
      method: 'POST',
      data: {
          contratosSeleccionados: contratosSeleccionados,
          fechasSeleccionadas: fechasSeleccionadas
      },
      success: function(response) {
          alert(response); // Lógica adicional después de la actualización
          listar();
      },
      error: function(xhr, status, error) {
          // Manejo de errores
      }
  });
}

// function guardaryeditar(e)
// {

//   e.preventDefault();// No se activara la accion predeterminada del evento
//   //$("#btnGuardar").prop("disabled",true);
//   //var formData=new FormData($("#formulario")[0]);

//   $.ajax(
//     {
//       url:"../ajax/pago_servicios.php?op=guardaryeditar",
//       //type:"POST",
//       //data:formData,
//       contentType:false,
//       processData:false,

//       success:function(datos)
//       {
//           alert(datos);
//          // console.log(datos);
//           mostrarform(false);
//           tabla.ajax.reload();
//       }
//     }
//   );
//   //limpiar();
//   listar();
// }














init();
