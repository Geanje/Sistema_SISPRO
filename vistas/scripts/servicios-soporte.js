var tabla;

//funcion que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();
  costoServicio();

  $("#formulario").on("submit", function (e) {
    guardaryeditar(e);
  })

  $("#consultaSunat").hide();

  $.post("../ajax/servicios-soporte.php?op=selectTipoComprobante", function (r) {
    $('#codigotipo_comprobante').html(r);
  });

  $.post("../ajax/servicios-soporte.php?op=selectCliente", function (r) {
    $("#idcliente").html(r);
    $('#idcliente').selectpicker('refresh');
  });

  $.post("../ajax/servicios-soporte.php?op=selectTecnico", function (r) {
    $("#idtecnico").html(r);
    $('#idtecnico').selectpicker('refresh');
  });



  $("#idcliente").change(rellenarCliente);


  // Aquí puedes agregar el condicional para cambiar el label y el p en Nuevo Servicio///////////////////////////////////
  $("#agregarservicio").click(function () {
    $("#lblDiagnostico").text("Diagnóstico del Equipo");
    $("#pProblema").text("Problema:");
    $("#sSolucion").text("Solución:");
    $("#rRecomendacion").text("Recomendación Técnica:");
    $('#codigotipo_comprobante').val(20);
    mostrarTexts();
  });

  $("#agregars").click(function () {
    // Cambia el label y el p en Nuevo Soporte
    $("#pProblema").text("Requerimientos:");
    $("#lblDiagnostico").text("Descripción del Servicio");
    $("#sSolucion").text("Proceso de Servicio:");
    $("#rRecomendacion").text("Observacion:");
    $('#codigotipo_comprobante').val(21);
    ocultarTexts();
  });




  ///-----------FUNCION PARA OCULTAR Y MOSTRAR CAMPOS DE ACUERDO AL BOTON SELECCIONADO-----------/// 

  $("#agregarservicio").click(function () {
    limpiar();
    mostrarform(true);
    mostrarTexts();
    $(".select-marca").show();
    $(".select-accesorio").show();
    $("#nombre_cliente_span").hide();
    $("#agregarservicio").hide();
    $("#agregars").hide();
    $("#idPrincipal").hide();
    $("#idServGenetal").hide();
    $("#AreaTi").hide();
    $("#ingElectrica").hide();
    $("#oficina").show();
    $("#areaSop").show();
  });

  $("#agregars").click(function () {
    limpiar();
    mostrarform(true);
    ocultarTexts();
    $(".select-marca").hide();
    $(".select-accesorio").hide();
    $("#nombre_cliente_span").show();
    $("#agregarservicio").hide();
    $("#agregars").hide();
    $("#idPrincipal").hide();
    $("#idSoporteTec").hide();
    $("#AreaTi").show();
    $("#ingElectrica").show();
    $("#oficina").hide();
    $("#areaSop").hide();

  });

  document.addEventListener("DOMContentLoaded", function () {
    var areaServicioSelect = document.getElementById("area_servicio");
    var tipoServicioSelect = document.getElementById("tipo_servicio");
    var opcionesPorArea = {
      "Area TI": ["Desarrollo Software", "Facturacion Electronica", "Tecnologia en Seguridad", "Redes & Infraestructura"],
      "Ingieneria Electrica": ["Instalaciones Electricas", "Mantenimiento Electrico", "Mantenimiento Industrial"],
      "Oficina": ["Soporte Técnico", "Soporte Remoto", "Servicio Domicilio"],
      "Area de Soporte": ["Soporte Técnico", "Soporte Remoto", "Servicio Domicilio"]
    };
    areaServicioSelect.addEventListener("change", function () {
      var selectedValue = areaServicioSelect.value;
      tipoServicioSelect.innerHTML = '';
      opcionesPorArea[selectedValue].forEach(function (opcion) {
        var option = document.createElement("option");
        option.value = opcion;
        option.textContent = opcion;
        tipoServicioSelect.appendChild(option);
      });
    });
  });


  function ocultarTexts() {
    $(".text-marca").hide();
    $(".text-accesorio").hide();
    $("#selectrdf").hide();
  }

  function mostrarTexts() {
    $(".text-marca").show();
    $(".text-accesorio").show();
  }

}

function rellenarCliente() {//😀
  var idcliente = $("#idcliente").val();

  var cliente = $("#idsoporte").prop("selected", true);
  if (cliente) {
    $.post("../ajax/cotizacion.php?op=mostrarDatoCliente", { idcliente: idcliente }, function (data) {
      data = JSON.parse(data);

      $("#telefono").val(data.telefono);
      $("#direccioncliente").val(data.direccion);

    });
  }
}

//Funcion limpiar
function limpiar() {
  $("#idtecnico").val("");
  $("#idsoporte").val("");
  $("#idcliente").val("");
  $("#codigo_servicio").val("");
  $("#area_servicio").val("");
  $("#telefono").val("");
  $("#direccioncliente").val("");
  $("#fecha_ingreso").val("");
  $("#fecha_salida").val("");
  $("#nombre_cliente").val("");
  $("#tipo_servicio").val("");
  $("#marca").val("");
  $("#problema").val("");
  $("#solucion").val("");
  //$("#tecnico_respon").val("");
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
function offdisabled() {
  $("#telefono").prop("readonly", false);
  $("#direccioncliente").prop("readonly", false);
  const myDiv = document.getElementById('select');
  myDiv.hidden = false;
  $("#nombre_cliente_span").hide();
}


var i = 0;
var detalles = 0;

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
    success: function (data) {

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
    error: function (jqXHR, textStatus, errorThrown) {
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

  console.log("Valor de cuotas:", cuotas);
  console.log("Valor de saldos:", saldos);
  
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
    success: function (datos) {
      // bootbox.alert(datos);

      if (datos != "" || datos != null) {
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
            title: "El pago se ha realizado con Exito",
            text: "¡" + datos + "!",
            type: "success",
            confirmButtonText: "Cerrar",
            closeOnConfirm: true
          });
        }
      } else {
        swal({
          title: "Error!",
          text: "¡Ocurrio un error, por favor registre otra vez el pago",
          type: "warning",
          confirmButtonText: "Cerrar",
          closeOnConfirm: true
        },
        );
      }
      mostrarPagos(idsoporte);
    },
    error: function (xhr, status, error) {
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

function ventanaEmergente2(idsoporte) {
  $('#ventanaEmergente2').show();
  console.log(idsoporte);
}

function cerrarVentanaEmergente2() {
  document.getElementById('ventanaEmergente2').style.display = 'none';

}

function mostrarIntegrantes(idsoporte) {

  $.ajax({
    url: "../ajax/servicios-soporte.php?op=ListarIntegrante&idsoporte=" + idsoporte,
    type: "POST",
    dataType: "json",
    success: function (data) {
      $('#listarIntegrantes').empty();
      var filaCabecera = '<tr>' +
        '<th style="width: 300px;">Lista  de los  Integrantes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="ventanaEmergente2()" value="+" id="btneditintegrante"></th>' +
        '</tr>';
      $('#listarIntegrantes').append(filaCabecera);
      for (var i = 0; i < data.aaData.length; i++) {
        var fila = '<tr class="filas">' +
          '<td>' + data.aaData[i][0] + '</td>' +
          '</tr>';
        $('#listarIntegrantes').append(fila);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("Error en la petición AJAX: " + textStatus + " - " + errorThrown);
      console.log(jqXHR.responseText);
    }
  });
}

function guardarIntegrantes() {
  var nombreintegrantes = document.getElementById('nombre_integrante').value;
  var idsoporte = document.getElementById('idsoporte').value;

  $.ajax({
    url: '../ajax/servicios-soporte.php?op=insertarIntegrantes',
    method: 'POST',
    data: {
      nombre_integrantes: nombreintegrantes,
      idsoporte: idsoporte,
    },
    success: function (integrante) {
      if (integrante != "" || integrante != null) {
        swal({
          title: "Registro Exitoso",
          text: "¡" + integrante + "!",
          type: "success",
          confirmButtonText: "Cerrar",
          closeOnConfirm: true
        });
      } else {
        swal({
          title: "Error!",
          text: "¡Ocurrió un error, por favor registre otra vez el integrante",
          type: "warning",
          confirmButtonText: "Cerrar",
          closeOnConfirm: true
        });
      }
      mostrarIntegrantes(idsoporte);
    },
    error: function (xhr, status, error) {
      console.log(xhr.responseText);
      console.log(status);
      console.log(error);
      alert("Hubo un error en la solicitud AJAX. Consulta la consola para más detalles.");
    }
  });
  cerrarVentanaEmergente2();
  document.getElementById('nombre_integrante').value = '';
}



function mostrarform(flag) {
  offdisabled()
  $("#cuotasdepago").hide();
  $("#totalIntegrantes").hide();
  limpiar();
  if (flag) {
    $('#listadoregistros').hide();
    $('#formularioregistros').show();
    $('#btnGuardar').prop("disabled", false);
    $('#btnPDF').hide();
    $("#idServGenetal").show();
    $("#idSoporteTec").show();
    $("#codigotipo_comprobante").show();
    $("#agregars").hide();
    $("#agregarservicio").hide();
    $("#btnSalida").hide();


  }
  else {
    $('#listadoregistros').show();
    $('#formularioregistros').hide();
    $("#agregarservicios").show();
    $("#agregarservicios").hide();
    $('#btnPDF').show();
    $("#idPrincipal").show();
    $("#idServGenetal").hide();
    $("#idSoporteTec").hide();
    $("#agregars").show();
    $("#agregarservicio").show();
    $("#btnSalida").show();




  }
}

//funcion cancelarform
function cancelarform() {
  limpiar();
  mostrarform(false);

  // Mostrar los botones nuevamente después de guardar
  $("#agregarservicio").show();
  $("#agregars").show();

  location.reload();
}

//function listar
function listar() {
  limpiar();
  tabla = $('#tbllistado').dataTable(
    {
      "aProcessing": true, //Activamos el procesamiento de datatables
      "aServerSide": true, //Paginacion y filtrado realizados por el servidor
      dom: 'Bfrtip', //Definimos los elementos del control de tabla
      buttons: [
        'copyHtml5',
        'excelHtml5',

      ],
      "ajax":
      {
        url: '../ajax/servicios-soporte.php?op=listar',
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        }

      },
      "bDestroy": true,
      "iDisplayLength": 15, //Paginacion
      "order": [[0, "desc"]] //Ordenar (columna,orden)

    }
  ).DataTable();

}



function guardaryeditar(e) {

  e.preventDefault();// No se activara la accion predeterminada del evento
  $("#btnGuardar").prop("disabled", true);
  var formData = new FormData($("#formulario")[0]);

  $.ajax(
    {
      url: "../ajax/servicios-soporte.php?op=guardaryeditar",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function (datos) {
        alert(datos);
        // console.log(datos);
        mostrarform(false);
        tabla.ajax.reload();

        // Mostrar los botones nuevamente después de guardar

        $("#agregarservicio").show();
        $("#agregars").show();


      }
    }
  );
  limpiar();
}
//variable para almacenar el valor del total
var total = 0;
function mostrar(idsoporte) {
  console.log(idsoporte)
  $.post("../ajax/servicios-soporte.php?op=mostrar", { idsoporte: idsoporte }, function (data, status) {
    data = JSON.parse(data);
    mostrarform(true);
    //console.log("funcion mostrar");
    $("#idcliente").show();
    $("#idcliente").val(data.nombre_cliente);//
    $("#codigo_servicio").val(data.codigo_servicio);
    $("#codigo_servicio").prop("readonly", true);//para que no se pueda editar
    $("#area_servicio").val(data.area_servicio);
    $("#area_servicio").selectpicker('refresh');//😂
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
    $("#tipo_servicio").val(data.tipo_servicio);
    $("#codigotipo_comprobante").val(data.codigotipo_comprobante);
    if (data.codigotipo_comprobante == 20) {
      $("#marca").val(data.marca);
      $("#marca").show();
      $("#accesorio").val(data.accesorio);
      $("#accesorio").show();
      $("#pProblema").text("Problema:");
      $("#id_integrante_servicio").val(data.id_integrante_servicio);
      $("#id_integrante_servicio").hide();
      $("#totalIntegrantes").hide();

    } else if (data.codigotipo_comprobante == 21) {
      $("#marca").val(data.marca);
      $("#marca").hide();
      $("#accesorio").val(data.accesorio);
      $("#accesorio").hide();
      $("#ti_Marca").hide();
      $("#ti_Accesorio").hide();
      $("#direccioncliente").val(data.direccion);//😂
      $("#direccioncliente").hide(); 
      $("#pProblema").text("Requerimientos:");
      $("#lblDiagnostico").text("Descripción del Servicio");
      $("#sSolucion").text("Proceso de Servicio:");
      $("#rRecomendacion").text("Observacion:");
      $("#selectrdf").hide();
      $("#totalIntegrantes").show();


    }
    $("#direccioncliente").show(); 
    $("#problema").val(data.problema);
    $("#solucion").val(data.solucion);
    $("#idsoporte").val(data.idsoporte);
    $("#estado_servicio").val(data.estado_servicio);
    $("#estado_pago").val(data.estado_pago);
    $("#estado_entrega").val(data.estado_entrega);
    //aqui
    $("#total").val(data.total);
    total = $("#total").val();
    $("#recomendacion").val(data.recomendacion);
    $("#garantia").val(data.garantia);
    $("#cuotasdepago").show();
    $("#fecha_pago").val(data.fecha_pago);
    $("#idsoportepago").val(data.idsoportepago);
    $("#idServGenetal").hide();
    $("#idSoporteTec").hide();
    //$("#idtecnico").val(data.nombre);

    var nombreCliente = $("#idcliente option:selected").text();
    $("#nombre_cliente_span").text(nombreCliente);
    $("#nombre_cliente_span").show();
    const myDiv = document.getElementById('select');
    myDiv.hidden = true;

  })
  mostrarPagos(idsoporte);
  mostrarIntegrantes(idsoporte);
}

function eliminar(idsoporte) {
  bootbox.confirm("¿Está seguro de eliminar el soporte?", function (result) {
    if (result) {
      $.post("../ajax/servicios-soporte.php?op=eliminar", { idsoporte: idsoporte }, function (e) {
        bootbox.alert(e);
        tabla.ajax.reload();
      });
    }
  });
}

$(function () {
  let cuotas = $("#cuotas");
  cuotas.keyup(function () {
    costoServicio();
  });
});

function costoServicio() {
  let cuotas = parseFloat($("#cuotas").val());
  let saldos = 0; 
  $(".filas").each(function () {
    let montoPagado = parseFloat($(this).find("td:eq(1)").text());
    if (!isNaN(montoPagado)) {
      saldos += montoPagado;
    }
  });
  let costoOriginal = parseFloat($("#total").val());
  saldos = isNaN(saldos) ? 0 : saldos;
  saldos = costoOriginal - saldos - cuotas;
  $("#saldos").val(saldos);
  total = costoOriginal - saldos;
}


init();