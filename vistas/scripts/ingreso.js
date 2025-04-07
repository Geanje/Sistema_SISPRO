var tabla;
var tablaCuota;
var contFechas = 0;

//Función que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();

  $('#listadoCuotas').hide();
  $('#mostrarCuotas').on('click', function () {});

  // listarCuotas();

  $('#formulario').on('submit', function (e) {
    guardaryeditar(e);
  });

  $('#formularioListadoCuotas').on('submit', function (e) {
    guardaryeditarListaCuota(e);
  });

  //cargamos los items al select proveedor
  $.post('../ajax/ingreso.php?op=selectProveedor', function (r) {
    $('#idproveedor').html(r);
    $('#idproveedor').selectpicker('refresh');
  });
  $('#cuota').change(calcularcuotas);
  $('#cuota').keyup(calcularcuotas);

  $('#diapago').hide();
  $('#seleccionarFecha').hide();

  $('#tipocredito').change(calcularcuotas);

  $('#escredito').hide();
  $('#credito').change(function () {
    if ($('#credito').val() == 'si') {
      $('#escredito').show();
    } else {
      $('#escredito').hide();
    }
  });

  $('#btnGenerarFechas').on('click', () => {
    var cantCuotas = $('#cuota').val();
    var valorCuotas = $('#valorcuota').val();

    agregarFechas(cantCuotas, valorCuotas);
    // console.log(cantCuotas);
  });

  $('#listadoFechas').hide();
}

function mostrarCuotas() {
  e.preventDefault();
}

function calcularcuotas() {
  let cuota = $('#cuota').val();
  let total_compra = $('#total_compra').val();

  if (cuota == '0' || cuota == 0) {
    $('#valorcuota').val('');
  } else if (cuota != '' || cuota != null || cuota != '0') {
    $('#valorcuota').val((total_compra / cuota).toFixed(2));
  }

  if ($('#tipocredito').val() == 'Mensual') {
    $('#diapago').show();
    $('#fechainicio').hide();
    $('#seleccionarFecha').hide();
    $('#listadoFechas').hide();

    // $("#tblListadoFechas").append("");
    $('.filasFechas').remove();
    contFechas = 0;
  } else if ($('#tipocredito').val() == 'Diario') {
    $('#diapago').hide();
    $('#fechainicio').show();
    $('#seleccionarFecha').hide();
    $('#listadoFechas').hide();
    // $("#tblListadoFechas").append("");
    $('.filasFechas').remove();
    contFechas = 0;
  } else if ($('#tipocredito').val() == 'Seleccionar') {
    $('#seleccionarFecha').show();
    $('#diapago').hide();
    $('#fechainicio').hide();
    $('#listadoFechas').show();
  }
}

function agregarFechas(cantCuotas, valorCuotas) {
  var ahora = new Date();
  var dia = ('0' + ahora.getDate()).slice(-2);
  var mes = ('0' + (ahora.getMonth() + 1)).slice(-2);
  var hoy = ahora.getFullYear() + '-' + mes + '-' + dia;

  // $("#tblListadoFechas").append();
  $('.filasFechas').remove();
  contFechas = 0;

  while (contFechas < cantCuotas) {
    var filaFechas =
      '<tr class="filasFechas" id="filasFechas' +
      contFechas +
      '">' +
      '<td>' +
      (contFechas + 1) +
      '</td>' +
      '<td>' +
      valorCuotas +
      '</td>' +
      '<td><input type="date" name="fechasElegidas[]" value="' +
      hoy +
      '"class="form-control" required="required"></td>' +
      '</tr>';
    $('#tblListadoFechas').append(filaFechas);
    contFechas++;
  }
}

//Función limpiar
function limpiar() {
  $('#idproveedor').val('');
  $('#proveedor').val('');
  $('#serie_comprobante').val('');
  $('#num_comprobante').val('');
  $('#fecha_hora').val('');
  $('#impuesto').val('0');

  $('#total_compra').val('');
  $('.filas').remove();
  $('#total').html('0');

  //Obtenemos la fecha actual
  var now = new Date();
  var day = ('0' + now.getDate()).slice(-2);
  var month = ('0' + (now.getMonth() + 1)).slice(-2);
  var today = now.getFullYear() + '-' + month + '-' + day;
  $('#fecha_hora').val(today);

  //Marcamos el primer tipo_documento
  $('#tipo_comprobante').val('Boleta');
  $('#tipo_comprobante').selectpicker('refresh');
}

//Función mostrar formulario
function mostrarform(flag) {
  limpiar();
  if (flag) {
    $('#listadoregistros').hide();
    $('#formularioregistros').show();
    //$("#btnGuardar").prop("disabled",false);
    $('#btnagregar').hide();
    $('#btnreporte').hide();

    listarArticulos();

    $('#btnGuardar').hide();
    $('#btnCancelar').show();
    detalles = 0;
    $('#btnAgregarArt').show();
  } else {
    $('#listadoregistros').show();
    $('#formularioregistros').hide();
    $('#btnagregar').show();
    $('#btnreporte').show();
    $('#listadoCuotas').hide();
    $('#tituloModulo').html(
      'Ingreso de Productos &nbsp; &nbsp;' +
        '<button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>&nbsp; Agregar</button>'
    );

    $('#credito').val('no');
    $('#escredito').hide();
  }
}

//Función cancelarform
function cancelarform() {
  limpiar();
  mostrarform(false);
}

//Función Listar
function listar() {
  tabla = $('#tbllistado')
    .dataTable({
      bProcessing: true, //Activamos el procesamiento del datatables
      serverSide: true, //Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip', //Definimos los elementos del control de tabla
      buttons: ['copyHtml5', 'excelHtml5'],
      ajax: {
        url: '../ajax/ingreso.php?op=listar',
        type: 'get',
        dataType: 'json',
        error: function (e) {
          console.log(e.responseText);
        },
      },
      language:
        // "http://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
        {
          sProcessing: 'Procesando...',
          sLengthMenu: 'Mostrar _MENU_ registros',
          sZeroRecords: 'No se encontraron resultados',
          sEmptyTable: 'Ningún dato disponible en esta tabla',
          sInfo:
            'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
          sInfoEmpty:
            'Mostrando registros del 0 al 0 de un total de 0 registros',
          sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
          sInfoPostFix: '',
          sSearch: 'Buscar:',
          sUrl: '',
          sInfoThousands: ',',
          sLoadingRecords: 'Cargando, espere por favor...',
          oPaginate: {
            sFirst: 'Primero',
            sLast: 'Último',
            sNext: 'Siguiente',
            sPrevious: 'Anterior',
          },
          oAria: {
            sSortAscending:
              ': Activar para ordenar la columna de manera ascendente',
            sSortDescending:
              ': Activar para ordenar la columna de manera descendente',
          },
        },

      bDestroy: true,
      iDisplayLength: 10, //Paginación
      order: [[0, 'desc']], //Ordenar (columna,orden)
    })
    .DataTable();
}

function listarCuotas(idingreso) {
  // tablaCuota = $("")
  // idingreso = '45';
  mostrarform(true);

  $('#listadoCuotas').show();
  $('#formularioregistros').hide();
  $('#tituloModulo').html(
    'Calendario de pagos &nbsp;' +
      "<button class='btn btn-success' onclick='mostrarform(false)'>Regresar</button>"
  );
  $('#modalNuevaCuota').html(
    '<button id="btnAgregarArt" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalListadoCuotas" onclick="mostrarIdIngreso(' +
      idingreso +
      ')"><span class="fa fa-plus"></span>&nbsp;&nbsp;Agregar nueva cuota</button>'
  );

  tablaCuota = $('#tblCuotas')
    .dataTable({
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip', //Definimos los elementos del control de tabla
      buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf'],
      ajax: {
        url: '../ajax/ingreso.php?op=listarCuotas',
        data: { idingreso: idingreso },
        type: 'get',
        dataType: 'json',
        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
      iDisplayLength: 10, //Paginación
      order: [[0, 'desc']], //Ordenar (columna,orden)
    })
    .DataTable();

  $.post(
    '../ajax/ingreso.php?op=mostrarSaldoAFavor',
    { id_ingreso: idingreso },
    (dato) => {
      dato = JSON.parse(dato);
      // console.log(dato);
      $('#valorapagar').html(parseFloat(dato.total_compra).toFixed(2));
      $('#cuotaspagadas').html(dato.saldoFavor.toFixed(2));
      $('#saldoDiferencial').html(dato.saldoDiferencial.toFixed(2));
    }
  );
}

function mostrarIdIngreso(idingreso) {
  $('#id_ingreso').val(idingreso);
  $('#idpago').val('');
  $('#valor_cuota').val('');

  var now = new Date();
  var day = ('0' + now.getDate()).slice(-2);
  var month = ('0' + (now.getMonth() + 1)).slice(-2);
  var today = now.getFullYear() + '-' + month + '-' + day;
  $('#fecha_cuota').val(today);
}

function cancelar(idingreso, idpago) {
  bootbox.confirm('¿Está seguro de cancelar esta cuota?', function (result) {
    if (result) {
      $.post(
        '../ajax/ingreso.php?op=cancelarLetra',
        { idpago: idpago },
        function (e) {
          bootbox.alert(e);
          // tablaCuota.ajax.reload();
          listarCuotas(idingreso);
        }
      );
    }
  });
}

function mostrarListaCuota(idpago) {
  $.post(
    '../ajax/ingreso.php?op=mostrarListaCuota',
    { idpago: idpago },
    (data) => {
      data = JSON.parse(data);
      $('#idpago').val(data.idpago);
      $('#id_ingreso').val(data.idingreso);
      $('#valor_cuota').val(data.valor_cuota);
      $('#fecha_cuota').val(data.fecha_pagoLista);
    }
  );
}

//Función Listar
function listarArticulos() {
  tabla = $('#tblarticulos')
    .dataTable({
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip', //Definimos los elementos del control de tabla
      buttons: [],
      ajax: {
        url: '../ajax/ingreso.php?op=listarArticulos',
        type: 'get',
        dataType: 'json',
        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
      iDisplayLength: 5, //Paginación
      order: [[0, 'desc']], //Ordenar (columna,orden)
    })
    .DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento
  //$("#btnGuardar").prop("disabled",true);
  var formData = new FormData($('#formulario')[0]);

  $.ajax({
    url: '../ajax/ingreso.php?op=guardaryeditar',
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      if (datos != '' || datos != null || datos != ' ') {
        swal(
          {
            title: 'BIEN!',
            text: '¡' + datos + '!',
            type: 'success',
            confirmButtonText: 'Cerrar',
            closeOnConfirm: true,
          },

          function (isConfirm) {
            if (isConfirm) {
              mostrarform(false);
              listar();
            }
          }
        );
      } else {
        swal(
          {
            title: 'Error!',
            text: '¡Ocurrio un error, por favor registre nuevamente la compra!',
            type: 'warning',
            confirmButtonText: 'Cerrar',
            closeOnConfirm: true,
          },

          function (isConfirm) {
            if (isConfirm) {
              location.reload(true);
            }
          }
        );
      }
    },
  });
  limpiar();
}

function guardaryeditarListaCuota(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento
  var id_ingreso = $('#id_ingreso').val();
  var formudata = new FormData($('#formularioListadoCuotas')[0]);

  $.ajax({
    url: '../ajax/ingreso.php?op=guardaryeditarListadoCuotas',
    type: 'POST',
    data: formudata,
    contentType: false,
    processData: false,
    success: function (datos) {
      if (datos != '' || datos != null || datos != ' ') {
        swal(
          {
            title: 'BIEN!',
            text: '¡' + datos + '!',
            type: 'success',
            confirmButtonText: 'Cerrar',
            closeOnConfirm: true,
          },

          function (isConfirm) {
            if (isConfirm) {
              mostrarform(false);
              listarCuotas(id_ingreso);
              $('#modalListadoCuotas').modal('hide');
            }
          }
        );
      } else {
        swal(
          {
            title: 'Error!',
            text: '¡Ocurrio un error, por favor registre nuevamente!',
            type: 'warning',
            confirmButtonText: 'Cerrar',
            closeOnConfirm: true,
          },

          function (isConfirm) {
            if (isConfirm) {
              location.reload(true);
            }
          }
        );
      }
    },
    error: function (ee) {
      console.log(ee);
    },
  });
}

function mostrar(idingreso) {
  $.post(
    '../ajax/ingreso.php?op=mostrar',
    { idingreso: idingreso },
    function (data, status) {
      data = JSON.parse(data);
      mostrarform(true);
      if (data.estado == 'Aceptado') {
        $('#credito').val('no');
      } else if (data.estado == 'Pendiente') {
        $('#credito').val('si');
      }
      $('#idproveedor').val(data.idproveedor);
      $('#idproveedor').selectpicker('refresh');
      $('#tipo_comprobante').val(data.tipo_comprobante);
      $('#tipo_comprobante').selectpicker('refresh');
      $('#serie_comprobante').val(data.serie_comprobante);
      $('#num_comprobante').val(data.num_comprobante);
      $('#fecha_hora').val(data.fecha);
      $('#impuesto').val(data.impuesto);
      $('#idingreso').val(data.idingreso);

      //Ocultar y mostrar los botones
      $('#btnGuardar').hide();
      $('#btnCancelar').show();

      $('#btnAgregarArt').hide();
    }
  );

  $.post('../ajax/ingreso.php?op=listarDetalle&id=' + idingreso, function (r) {
    $('#detalles').html(r);
  });
}

//Función para desactivar registros
function anular(idingreso) {
  bootbox.confirm('¿Está seguro de anular el ingreso?', function (result) {
    if (result) {
      $.post(
        '../ajax/ingreso.php?op=anular',
        { idingreso: idingreso },
        function (e) {
          bootbox.alert(e);
          tabla.ajax.reload();
        }
      );
    }
  });
}

function anularCompraCredito(idingreso) {
  let datoAnular = 0;
  bootbox.confirm('¿Está seguro de pagar la cuota?', function (result) {
    if (result) {
      $.post(
        '../ajax/ingreso.php?op=virificarPago',
        { idingreso: idingreso },
        (data) => {
          datoVerificado = JSON.parse(data);
          datoAnular += parseFloat(datoVerificado.cantidadRegistros);
          console.log(datoVerificado.cantidadRegistros);
          if (datoAnular > 0) {
            swal({
              title: '¡Error!',
              text: '¡Aún no paga todas las cuotas de esta compra, por lo tanto no puede anularlo!',
              type: 'warning',
              confirmButtonText: 'Cerrar',
              closeOnConfirm: true,
            });

            // function(isConfirm){

            // if(isConfirm){
            // location.reload(true);
            // }
            // }
          } else {
            $.post(
              '../ajax/ingreso.php?op=anularCompraCredito',
              { idingreso: idingreso },
              function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
                listar();
              }
            );
          }
        }
      );
    }
  });
}

//declaracion de variables necesarios para trabajar con las compras y sus detalles
var impuesto = 18;
var cont = 0;
var detalles = 0;

//$("#guardar").hide();
$('#btnGuardar').hide();
$('#tipo_comprobante').change(marcarImpuesto);

function marcarImpuesto() {
  var tipo_comprobante = $('#tipo_comprobante option:selected').text();
  if (tipo_comprobante == 'Factura') {
    $('#impuesto').val(impuesto);
  } else {
    $('#impuesto').val('0');
  }
}

function agregarDetalle(idarticulo, articulo, codigo) {
  var cantidad = 1;
  var precio_compra = 1;
  var precio_venta = 1;

  // Obtener el valor de la serie del comprobante del formulario principal
  var serie_comprobante = $('#serie_comprobante').val();
	
	$(document).ready(function() {
		// Actualizar series cuando cambie el campo principal
		$('#serie_comprobante').change(function() {
			var nuevaSerie = $(this).val();
			
			// Actualizar todos los inputs hidden
			$('.serie-input').val(nuevaSerie);
			
			// Actualizar todos los textos mostrados
			$('.serie-text').text(nuevaSerie);
		});
	});

  if (idarticulo != '') {
    var subtotal = cantidad * precio_compra;
    var fila='<tr class="filas" id="fila' + cont + '">'+
		'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>'+
		'<td><input type="hidden" name="codigod[]" id="codigod' + cont + '" value="'+codigo+'" class="form-control">'+codigo+'</td>'+
		'<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
		// Campo serieIngreso con el valor de serie_comprobante
		'<td><input type="hidden" name="serieIngreso[]" class="serie-input" value="' + serie_comprobante + '">' + '<span class="serie-text">' + serie_comprobante + '</span></td>' +
		'<td><input type="number" name="cantidad[]" id="cantidad' + cont + '" value="' + cantidad + '" min=1 class="form-control"></td>'+
		'<td><input type="number" step="0.1" name="precio_compra[]" id="precio_compra' + cont + '" min=0.1 value="' + precio_compra + '" class="form-control"></td>'+
		'<td>'+
      '<select name="porcentajePrecio[]" id="porcentajePrecio' + cont + '" class="form-control">'+
        //'<option value="0">0%</option>'+
        //'<option value="1">1%</option>'+
        //'<option value="2">2%</option>'+
        //'<option value="3">3%</option>'+
        //'<option value="4">4%</option>'+
        //'<option value="5">5%</option>'+
        '<option value="10">10%</option>' +
        '<option value="15">15%</option>' +
        '<option value="20">20%</option>' +
        '<option value="25">25%</option>' +
        '<option value="30">30%</option>' +
        // '<option value="35">35%</option>'+
        '<option value="40">40%</option>' +
        // '<option value="45">45%</option>'+
        '<option value="50">50%</option>' +
        // '<option value="55">55%</option>'+
        // '<option value="60">60%</option>'+
        // '<option value="65">65%</option>'+
        // '<option value="70">70%</option>'+
        // '<option value="75">75%</option>'+
        // '<option value="80">80%</option>'+
        // '<option value="85">85%</option>'+
        // '<option value="90">90%</option>'+
        // '<option value="95">95%</option>'+
        // '<option value="100">100%</option>'+
      '</select>' +
    '</td>' +
    '<td><input type="number" step="0.1" name="precio_venta[]" value="'+precio_venta+'" min=0.1 class="form-control" ></td>'+
		'<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
		'</tr>';

    detalles = detalles + 1;
    $('#detalles').append(fila);

    $('#cantidad' + cont).keyup(modificarSubtotales);
    $('#cantidad' + cont).change(modificarSubtotales);
    $('#porcentajePrecio' + cont).change(modificarSubtotales);
    $('#precio_compra' + cont).keyup(modificarSubtotales);
    $('#precio_compra' + cont).change(modificarSubtotales);

    cont++;
    modificarSubtotales();

    // $("#cantidad"+cont).keyup(()=>{
    // $("#credito").prop("selected");
    // console.log($("#credito").prop("selected"));

    // });
    // $("#cantidad"+cont).change(actualizarCuotas);
    //   	$("#porcentajePrecio"+cont).change(calcularcuotas);
    // $("#precio_compra"+cont).keyup(calcularcuotas);
    //   	$("#precio_compra"+cont).change(calcularcuotas);
  } else {
    alert('Error al ingresar el detalle, revisar los datos del artículo');
  }
}

function actualizarCuotas() {
  // console.log(cont);
  $('#credito').val('no');
  if ($('#credito').val() == 'si') {
    $('#escredito').show();
  } else {
    $('#escredito').hide();
  }
}

function modificarSubtotales() {
  // console.log("eiii");
  // actualizarCuotas();

  var cant = document.getElementsByName('cantidad[]');
  var prec = document.getElementsByName('precio_compra[]');
  var porcenPrecio = document.getElementsByName('porcentajePrecio[]');
  var precioVenta = document.getElementsByName('precio_venta[]');
  var sub = document.getElementsByName('subtotal');

  for (var i = 0; i < cant.length; i++) {
    var inpC = cant[i];
    var inpP = prec[i];
    var inpS = sub[i];
    var inpPorcentajeP = porcenPrecio[i];
    // console.log(inpC);
    // console.log(inpP);
    // console.log((1+inpPorcentajeP.value/100) * inpP.value);
    inpS.value = inpC.value * inpP.value;
    // console.log(precioVenta[i]);

    // precioVenta[i].value = Math.ceil(((1+inpPorcentajeP.value/100) * inpP.value).toFixed(2));
    precioVenta[i].value = (
      (1 + inpPorcentajeP.value / 100) *
      inpP.value
    ).toFixed(2);

    //console.log(((1+inpPorcentajeP.value/100) * inpP.value).toFixed(2));
    // document.getElementsByName("precio_venta")[i].value= inpPorcentajeP.value * inpP.value;
    document.getElementsByName('subtotal')[i].innerHTML = inpS.value.toFixed(2);
  }

  calcularTotales();
  calcularcuotas();
}

function calcularTotales() {
  var sub = document.getElementsByName('subtotal');
  var total = 0.0;
  for (var i = 0; i < sub.length; i++) {
    total += document.getElementsByName('subtotal')[i].value;
  }

  $('#total').html('S/. ' + total.toFixed(2));
  $('#total_compra').val(total.toFixed(2));
  evaluar();
}

function evaluar() {
  if (detalles > 0) {
    $('#btnGuardar').show();
  } else {
    $('#btnGuardar').hide();
    cont = 0;
  }
}

function eliminarDetalle(indice) {
  $('#fila' + indice).remove();
  calcularTotales();
  detalles = detalles - 1;
}

init();
