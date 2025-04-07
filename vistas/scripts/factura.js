var tabla;

//Función que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();

  // $('#impuesto').prop('disabled',true);

  $("#formulario").on("submit", function (e) {
    guardaryeditar(e);
  });
  //Cargamos los items al select proveedor
  $.post("../ajax/factura.php?op=selectCliente", function (r) {
    $("#idcliente").html(r);
    $("#idcliente").selectpicker("refresh");
  });

  $.post("../ajax/factura.php?op=selectTipoPago", function (r) {
    $("#codigotipo_pago").html(r);
    $("#codigotipo_pago").selectpicker("refresh");
  });

  $.post("../ajax/factura.php?op=selectTipoComprobante", function (r) {
    $("#codigotipo_comprobante").html(r);
    $("#codigotipo_comprobante").selectpicker("refresh");
  });

  $.post("../ajax/factura.php?op=selecTipoIGV", function (r) {
    $("#impuesto").html(r);
    $("#impuesto").selectpicker("refresh");
  });

  $.post("../ajax/factura.php?op=selectMoneda", function (r) {
    $("#moneda").html(r);
    $("#moneda").selectpicker("refresh");
  });

  $("#idcliente").change(rellenarCliente);
}

function rellenarCliente() {
  var clientee = $("#idcliente").val();

  var idcliente = $("#idcliente").prop("selected", true);
  if (idcliente) {
    // console.log(clientee);

    $.post(
      "../ajax/factura.php?op=mostrarDatoCliente",
      { idcliente: clientee },
      function (data) {
        data = JSON.parse(data);

        $("#tipo_documento").val(data.tipo_documento);
        $("#num_documento").val(data.num_documento);
        $("#direccioncliente").val(data.direccion);
      }
    );
  }
}

function LPad(ContentToSize, PadLength, PadChar) {
  var PaddedString = ContentToSize.toString();
  for (var i = ContentToSize.length + 1; i <= PadLength; i++) {
    PaddedString = PadChar + PaddedString;
  }
  return PaddedString;
}

function limpiar() {
  $("#idcliente").val("");
  $("#idcliente").selectpicker("refresh");
  $("#cliente").val("");
  $("#serie").val("");
  $("#correlativo").val("");
  $("#tipo_documento").val("");
  $("#num_documento").val("");
  $("#direccioncliente").val("");

  $("#op_gravadas").val("");
  $("#totalg").html("0.00");
  $("#igv_total").val("");
  $("#totaligv").html("0.00");

  $("#total_venta").val("");
  $(".filas").remove();
  $("#totalventa").html("0.00");

  $("#codigotipo_pago").val("Efectivo");
  $("#codigotipo_pago").selectpicker("refresh");

  //Marcamos el primer tipo_documento
  $("#codigotipo_comprobante").val("Boleta");
  $("#codigotipo_comprobante").selectpicker("refresh");

  //Obtenemos la fecha actual
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);
  var today = now.getFullYear() + "-" + month + "-" + day;
  $("#fecha_ven").val(today);

  //Obtenemos la fecha actual
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);
  var today = now.getFullYear() + "-" + month + "-" + day;
  $("#fecha_hora").val(today);

  //Marcamos el primer tipo_documento
}
//Función mostrar formulario
function mostrarform(flag) {
  limpiar();
  if (flag) {
    $("#listadoregistros").hide();
    $("#formularioregistros").show();
    $("#btnagregar").hide();
    $("#btnGuardar").hide();
    $("#btnCancelar").show();
    $("#btnAgregarArt").show();
    detalles = 0;
  } else {
    $("#listadoregistros").show();
    $("#formularioregistros").hide();
    $("#btnagregar").show();
  }
}

//Función cancelarform
function cancelarform() {
  limpiar();
  mostrarform(false);
  location.reload();
}

//Función Listar
function listar() {
  var listadoValue = $('#listado').val();

  tabla = $("#tbllistado")
    .dataTable({
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: "Bfrtip", //Definimos los elementos del control de tabla
      buttons: ["copyHtml5", "excelHtml5"],
      ajax: {
        url: "../ajax/factura.php?op=listar&listado=" + listadoValue,
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        },
      },
      bDestroy: true,
      iDisplayLength: 9, //Paginación
      order: [[0, "desc"]], //Ordenar (columna,orden)
    })
    .DataTable();
}
function guardaryeditar(e) {
  e.preventDefault();
  var formData = new FormData($("#formulario")[0]);

  $.ajax({
    url: "../ajax/factura.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (datos) {
      var title = datos ? "BIEN!" : "Error!";
      var text = datos
        ? "¡" + datos + "!"
        : "¡Ocurrió un error, por favor registre nuevamente la venta!";
      var type = datos ? "success" : "warning";

      swal(
        {
          title: title,
          text: text,
          type: type,
          confirmButtonText: "Cerrar",
          closeOnConfirm: true,
        },
        function (isConfirm) {
          if (isConfirm) {
            mostrarform(false);
            listar();
          }
        }
      );
    },
  });

  limpiar();
}

//Función para anular registros
function anular(id_factura) {
  bootbox.confirm("¿Está Seguro de anular la factura?", function (result) {
    if (result) {
      $.post(
        "../ajax/factura.php?op=anular",
        { id_factura: id_factura },
        function (e) {
          bootbox.alert(e);
          tabla.ajax.reload();
        }
      );
    }
  });
  limpiar();
}

function eliminar(id_factura) {
  bootbox.confirm("¿Esta seguro eliminar la factura", function (result) {
    if (result) {
      $.post(
        "../ajax/factura.php?op=eliminar",
        { id_factura: id_factura },
        function (e) {
          bootbox.alert(e);
          tabla.ajax.reload();
        }
      );
    }
  });
  // limpiar();
}

function mostrar(id_factura) {
  mostrarform(true);
  $.post(
    "../ajax/factura.php?op=mostrar",
    { id_factura: id_factura },
    function (data) {
      data = JSON.parse(data);

      var clientee = $("#idcliente").val(data.idcliente);
      $("#idcliente").selectpicker("refresh");

      $("#tipo_documento").val(data.tipo_documento);
      $("#num_documento").val(data.num_documento);
      $("#direccioncliente").val(data.direccion);

      // $("#codigotipo_comprobante").on("checked",true);
      $("#codigotipo_pago").val(data.codigotipo_pago);
      $("#codigotipo_pago").selectpicker("refresh");
      $("#codigotipo_comprobante").val(data.codigotipo_comprobante);
      $("#codigotipo_comprobante").selectpicker("refresh");
      $("#fecha_ven").val(fecha_ven);

      $("#serie").val(data.serie);
      $("#correlativo").val(data.correlativo);
      $("#fecha_hora").val(data.fecha);

      $("#op_gravadas").val(data.op_gravadas);
      $("#totalg").html(data.op_gravadas);
      $("#igv_total").val(data.igv_total);
      $("#totaligv").html(data.igv_total);

      $("#total_venta").val(data.total_venta);
      $(".filas").remove();
      $("#totalventa").html(data.total_venta);

      $.post(
        "../ajax/factura.php?op=listarDetalle&id=" + id_factura,
        function (r) {
          $("#detalles").html(r);
        }
      );
    }
  );
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles

// var impuesto=18;
var cont = 0;
var detalles = 0;
//$("#guardar").hide();
$("#btnGuardar").hide();
//var cantidad = 1;

var codigo_prod = 0;

$.post("../ajax/factura.php?op=selectcodigo", function (r) {
  codigo_prod = parseInt(r.substring(1));
});

function agregarDetalle() {

  codigo_prod++;
  var newCodigoProd = "S" + String(codigo_prod).padStart(4, '0');


  var impuesto = document.getElementById("impuesto").value;
  var cantidad = 1;
  var precio_venta = 1;
  // var descuento=0;
  var igv_asig = document.getElementById("igv_asig");
  igv_asig.value = impuesto;
  // var subtotal=cantidad*precio_venta/1.18;
  var subtotal = (cantidad * precio_venta) / (1 + impuesto / 100);

  var igvv = subtotal * (impuesto / 100);
  igvv = igvv.toFixed(2);
  subtotal = subtotal.toFixed(2);
  var importe = cantidad * precio_venta;

  var fila =
    '<tr class="filas" id="fila' +
    cont +
    '">' +
    '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' +
    cont +
    ')">X</button></td>' +
    '<td style="width:12%"><input type="text" name="codigo_prod[]" style="width:90px;"></td>' +
    '<td style="width:30%"><input type="text" name="descripcion_prod[]" style="width:400px;" placeholder="Escriba la descripción del Servicio" required></td>' +
    '<td style="width:10%"><input type="text" name="unidad_medida[]" value="NIU" id="unidad_medida' +
    cont +
    '"  style="width:120px;" required readonly></td>' +
    '<td style="width:7%"><input type="number" min="0" step="0.01" name="precio_venta[]" id="precio_v' +
    cont +
    '" value="' +
    precio_venta +
    '" style="width:60px;"></td>' +
    '<td style="width:7%"><input type="number" min="0" name="cantidad[]" id="cantidad' +
    cont +
    '" value="' +
    cantidad +
    '" style="width:60px;"></td>' +
    '<td style="width:12%"><span name="subtotal" id="subtotal' +
    cont +
    '">' +
    subtotal +
    "</span></td>" +
    '<td style="width:12%"><span name="igv" id="igv' +
    cont +
    '">' +
    igvv +
    "</span></td>" +
    '<td style="width:12%"><span name="importe" id="importe' +
    cont +
    '">' +
    importe +
    "</span></td>" +
    "</tr>";
  detalles = detalles + 1;
  $("#detalles").append(fila);
  $("#precio_v" + cont).keyup(modificarSubtotales);
  $("#cantidad" + cont).keyup(function () {
    modificarSubtotales();
  });
  $("#cantidad" + cont).change(function () {
    modificarSubtotales();
  });
  $("#precio_v" + cont).change(function () {
    modificarSubtotales();
  });
  //$("#cantidad"+cont).change(modificarSubtotales);
  cont++;

  modificarSubtotales();
}

function modificarSubtotales() {
  var cant = document.getElementsByName("cantidad[]");
  var prec = document.getElementsByName("precio_venta[]");
  var sub = document.getElementsByName("importe");
  var subt = 0.0;
  var newvparcial = 0;
  var igvt = 0;

  for (var i = 0; i < cant.length; i++) {
    var inpC = cant[i];
    var inpP = prec[i];
    var inpS = sub[i];
    //console.log(inpC);
    var igv = document.getElementById("impuesto").value;
    var selectIVG = document.getElementById("ivg").value;

    inpS.value = inpP.value * inpC.value;
    igv = igv * (1 / 100);

    if (selectIVG == 1) {
      var st = inpS.value / (1 + igv);
      var ig = st * igv;
      newvparcial += parseFloat(inpS.value);
      subt = newvparcial / (1 + igv);
    } else {
      var st = inpS.value;
      var ig = st * igv;
      newvparcial += parseFloat(inpS.value * (1 + igv));
      subt = newvparcial / 1.18;
    }

    igvt = subt * igv;

    document.getElementsByName("subtotal")[i].innerHTML = addCommas(
      st.toFixed(2)
    );
    document.getElementsByName("igv")[i].innerHTML = addCommas(ig.toFixed(2));
    document.getElementsByName("importe")[i].innerHTML = addCommas(
      inpS.value.toFixed(2)
    );
  }

  $("#totalg").html("S/. " + addCommas(subt.toFixed(2)));
  $("#op_gravadas").val(subt.toFixed(2));

  $("#totaligv").html("S/. " + addCommas(igvt.toFixed(2)));
  $("#igv_total").val(igvt.toFixed(2));

  $("#totalventa").html("S/. " + addCommas(newvparcial.toFixed(2)));
  $("#total_venta").val(newvparcial.toFixed(2));
  evaluar();
  // calcularTotales();
}

function enviarDatosASunat(nombre_archivo, id_factura) {
  $.ajax({
    type: "POST",
    url: "../ajax/xml_sunat_Factura.php",
    data: {
      nombre_archivo,
      id_factura,
    },
    success: function (response) {
      swal({
        title: "BIEN!",
        text: "¡" + response + "!",
        type: "success",
        confirmButtonText: "Cerrar",
        closeOnConfirm: true,
      });
    },
    error: function (error) {
      swal({
        title: "Error!",
        text: "¡" + error + "!",
        type: "warning",
        confirmButtonText: "Cerrar",
        closeOnConfirm: true,
      });
    },
  });
}

function evaluar() {
  if (detalles > 0) {
    $("#btnGuardar").show();
  } else {
    $("#btnGuardar").hide();
    cont = 0;
  }
}

function eliminarDetalle(indice) {
  $("#fila" + indice).remove();
  modificarSubtotales();
  detalles = detalles - 1;
  evaluar();
}
function addCommas(nStr) {
  nStr += "";
  x = nStr.split(".");
  x1 = x[0];
  x2 = x.length > 1 ? "." + x[1] : "";
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, "$1" + "," + "$2");
  }
  return x1 + x2;
}


function validarTipoComprobanteYDocumento(tipoComprobante, numDocumento) {
  $("#codigotipo_comprobante").val(tipoComprobante).selectpicker("refresh");
  $("#num_documento").val(numDocumento);
  if (tipoComprobante == 1 && numDocumento.length < 10) {
    swal({
      title: "Advertencia",
      text: "¡El cliente debe contar con número de RUC!",
      type: "warning",
      confirmButtonText: "Cerrar",
      closeOnConfirm: true
    });
  }
}

function limpiarTipoComprobante() {
  $("#codigotipo_comprobante").val("").selectpicker("refresh");
}
$(document).ready(function () {
  $("#idcliente").change(function () {
    limpiarTipoComprobante();
  });
  $("#codigotipo_comprobante").change(function () {
    var tipoComprobanteSeleccionado = $(this).val();
    var numDocumento = $("#num_documento").val();
    validarTipoComprobanteYDocumento(tipoComprobanteSeleccionado, numDocumento);
  });
  $(document).on("click", ".sa-confirm-button-container", function () {
    $("#codigotipo_comprobante").val(3).selectpicker("refresh");
  });
});

function informe() {
  swal({
    title: "Advertencia",
    text: "¡Los comprobantes se facturaran con IGV!",
    type: "info",
    confirmButtonText: "Aceptar",
    cancelButtonText: "Cancelar",
    closeOnConfirm: true,
    showCancelButton: true,
  }, function () {
    mostrarform(true);
  });
}

function informe1() {
  swal({
    title: "Advertencia",
    text: "¡Los comprobantes se facturaran sin IGV!",
    type: "info",
    confirmButtonText: "Aceptar",
    cancelButtonText: "Cancelar",
    closeOnConfirm: true,
    showCancelButton: true,
  }, function () {
    mostrarform(true);
  });
}
init();
