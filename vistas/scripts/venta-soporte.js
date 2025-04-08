var tabla;
var tipo_cambio_dolar = 0.00

function init() {

    mostrarform(false);

    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    });

    $.post("../ajax/venta2.php?op=selectTipoComprobanteSalida", function (r) {
        $('#codigotipo_comprobante').html(r);
        $('#codigotipo_comprobante').selectpicker('refresh');
    });

    $.post("../ajax/venta2.php?op=selectTipoPago", function (r) {
        $('#codigotipo_pago').html(r);
        $('#codigotipo_pago').selectpicker('refresh');
    });

    $.post("../ajax/venta2.php?op=selectMoneda", function (r) {
        $('#moneda').html(r);
        $('#moneda').selectpicker('refresh');
    });

    $.post("../ajax/venta2.php?op=selecTipoIGV", function (r) {
        $('#impuesto').html(r);
        $('#impuesto').selectpicker('refresh');
    });

    $("#idcliente").change(rellenarCliente);
}
$.post("../ajax/venta2.php?op=selectCliente", function (r) {
    $("#idcliente").html(r);
    var idcliente = obtenerParametroGet('idcliente');
    $("#idcliente").val(idcliente);
});

function obtenerParametroGet(nombre) {
    var url = window.location.search.substring(1);
    var parametros = url.split('&');
    for (var i = 0; i < parametros.length; i++) {
        var parametro = parametros[i].split('=');
        if (parametro[0] === nombre) {
            return decodeURIComponent(parametro[1]);
        }
    }
    return null; 
}
$(document).ready(function() {
    var url = new URL(window.location.href);
    var idsoporte = url.searchParams.get("idsoporte");
    console.log("Valor de idsoporte:", idsoporte);
    $('#idsoporte').val(idsoporte);
    $.post("../ajax/servicios-soporte.php?op=mostrar", { idsoporte: idsoporte }, function (data, status) {
        data = JSON.parse(data);
        $("#idcliente").val(data.nombre_cliente);
        $.post("../ajax/venta2.php?op=mostrarDatoCliente",{idcliente : data.nombre_cliente},function(data){
			data = JSON.parse(data);
			$("#numdireccion").val(data.num_documento);
			$("#direccioncliente").val(data.direccion);
		});
    });
});


function get_exchange_rate() {
    $.ajax({
        url: "../ajax/venta2.php?op=get_exchange_rate",
        type: "POST",
        cache: false,
        dataType: 'json',
        success: function (data) {
            if (data.status === 'OK') {
                tipo_cambio_dolar = data.data
            }
        }
    });
}

function rellenarCliente() {
    var clientee = $("#idcliente").val();
    if (clientee) {
        $.post("../ajax/venta2.php?op=mostrarDatoCliente", { idcliente: clientee }, function (data) {
            data = JSON.parse(data);
            $("#numdireccion").val(data.num_documento);
            $("#direccioncliente").val(data.direccion);
        });
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
    $("#cliente").val("");
    $("#serie").val("");
    $("#correlativo").val("");



    $("#total_venta_gravado").val("");
    $("#totalg").html("0.00");
    $("#total_venta_exonerado").val("");
    $("#total_venta_inafectas").val("");
    $("#total_venta_gratuitas").val("");
    $("#total_descuentos").val("");
    $("#isc").val("");

    $("#tipo_cambio").val("");
    $("#tipo_cambio").html("0.00");
    $("#total_igv").val("");
    $("#totaligv").html("0.00");

    $("#total_importe").val("");
    $(".filas").remove();
    $("#totalimp").html("0.00");

    $("#numdireccion").val("");
    $("#direccioncliente").val("");

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora').val(today);
    $('#fecha_ven').val(today)

    $("#tipo_comprobante").val("Boleta");
    $("#tipo_comprobante").selectpicker('refresh');

    $("#codigotipo_pago").val("Efectivo");
    $("#codigotipo_pago").selectpicker('refresh');
}



function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#formularioregistros").hide();
        detalles = 0;
    }
    else {
        $("#formularioregistros").show();
        listarArticulos();
    }
}


function cancelarform() {
    window.close();
}


function listar() {
    tabla = $('#tbllistado').dataTable(
        {
            "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            buttons: [
            ],
            "ajax":
            {
                url: '../ajax/venta2.php?op=listar',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 9,
            "order": [[0, "desc"]]
        }).DataTable();

}


//Función ListarArticulos
function listarArticulos() {

    tabla = $('#tblarticulos').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [

            ],
            "ajax":
            {
                url: '../ajax/venta2.php?op=listarArticulosVenta',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5,//Paginación
            "order": [[0, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
    // --
    $("#tipo_cambio").text(tipo_cambio_dolar)
    $("input[name=tipo_cambio]").val(tipo_cambio_dolar)

}







function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/venta2.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            if (datos != "" || datos != null) {
                swal({
                  title: "BIEN!",
                  text: "¡" + datos + "!",
                  type: "success",
                  confirmButtonText: "Cerrar",
                  closeOnConfirm: false
                },
                function (isConfirm) {
                  if (isConfirm) {
                    window.close();
                  }
                });
              } else {
                swal({
                  title: "Error!",
                  text: "¡Ocurrio un error, por favor registre nuevamente la venta!",
                  type: "warning",
                  confirmButtonText: "Cerrar",
                  closeOnConfirm: true
                },
                function (isConfirm) {
                  if (isConfirm) {
                    location.reload(true);
                  }
                });
              }
        }
    });
    limpiar();
    
}


function detectaTipoPago(e) {
    var tipoComprobante = $("#codigotipo_comprobante").val();
    var tipoPago = $("#codigotipo_pago").val();
    var fechaVencimiento = $("#fecha_ven");
    //console.log(tipoComprobante + "y" + tipoPago)
    if ((tipoComprobante == 1) && (tipoPago == 1)) {
        fechaVencimiento.prop("readonly", true)
    } else {
        fechaVencimiento.prop("readonly", false)
    }
}


var cont = 0;
var detalles = 0;
$("#btnGuardar").hide();




function marcarImpuesto() {
    var tipo_comprobante = $("#codigotipo_comprobante").val();
    if (tipo_comprobante != '1') {
        $("#impuesto").val("0");
    }
    else {
        $("#impuesto").val(impuesto);
    }
}


function agregarDetalle(idarticulo, articulo, unidad_medida, precio_venta, afectacion, codigo) {

    var cantidad = 1;
    var descuento = 0;
    if (idarticulo != "") {



        if (afectacion == 'Exonerado') {
            var valorVentaU = precio_venta;
            var valorVentaT = precio_venta * cantidad;
            var igv = 0;
        } else if (afectacion == 'Gravado') {
            var valorVentaU = precio_venta / (1 + (impuesto / 100));
            var valorVentaT = valorVentaU * cantidad - descuento;
            var precioSinIgv = subtotal / (1 + (impuesto / 100));
            var igv = precioSinIgv * (impuesto / 100);
        }
        var subtotal = cantidad * precio_venta;

        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="codigo[]" id="codigo' + cont + '" value="' + codigo + '" class="form-control">' + codigo + '</td>' +
            '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
            '<td><input type="hidden" name="afectacio[]" value="' + afectacion + '"><input type="text" name="serieArticulo[]" id="serieArticulo" onkeypress="return Especial(event)"></td>' +
            '<td><input type="hidden" name="unidad_medida[]" value="">' + unidad_medida + '</td>' +
            '<td><input type="number" name="cantidad[]" id="cantidad' + cont + '" min="0" value="' + cantidad + '" style="width:50px"></td>' +
            '<td><span name="valor_venta_u" id="valor_venta_u' + cont + '" >' + valorVentaU.toFixed(2) + '</span><input type="hidden" name="descuento[]" id="descuento' + cont + '" step="0.01" value="' + descuento + '"></td>' +
            '<td><span name="impuest" id="impuest' + cont + '" >' + igv.toFixed(2) + '</span></td>' +
            '<td><input type="number" name="precio_venta[]" step="0.01" min="0" id="precio_venta' + cont + '" value="' + precio_venta + '" style="width:90px; text-align: right;"></td>' +
            '<td style="width:90px; text-align: right;"><span name="valor_venta_t" id="valor_venta_t' + cont + '" >' + valorVentaT.toFixed(2) + '</span></td>' +
            '<td style="width:115px; text-align: right;"><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '</tr>';

        detalles = detalles + 1;
        $('#detalles').append(fila);

        $("#cantidad" + cont).keyup(function () {
            modificarSubtotales();
        });
        $("#cantidad" + cont).change(function () {
            modificarSubtotales();
        });

        $("#descuento" + cont).keyup(modificarSubtotales);
        $("#descuento" + cont).change(function () {
            modificarSubtotales();
        });

        $("#precio_venta" + cont).keyup(modificarSubtotales);
        $("#precio_venta" + cont).change(function () {
            modificarSubtotales();
        });
        cont++;
        modificarSubtotales();
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
    limiteArticulo();
}

function limiteArticulo() {
    if (cont == 27) {

        $(".dataTables_wrapper").hide();
        $("#btnAgregarArt").hide();
        swal({
            title: "Ha alcanzado el limite de articulos.\n¡Por favor realice otro Comprobante!",
            type: "warning",
            confirmButtonText: "Cerrar",
            closeOnConfirm: true

        });

    }
}


function modificarSubtotales() {
    var cant = document.getElementsByName("cantidad[]");
    var desc = document.getElementsByName("descuento[]");
    var imp = document.getElementsByName("impuest[]");
    var prec = document.getElementsByName("precio_venta[]");
    var sub = document.getElementsByName("subtotal");
    var afec = document.getElementsByName("afectacio[]");

    var total = 0.0;
    var totaligv = 0.0;
    var totaldesc = 0.0;
    var totalgravad = 0.0;
    var totalexoner = 0.0;
    var newigv = 0;
    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpD = desc[i];
        var inpI = imp[i];
        var inpP = prec[i];
        var inpS = sub[i];
        var inpA = afec[i];

        var impuesto = document.getElementById("impuesto").value;
        var igv_asig = document.getElementById("igv_asig");
        igv_asig.value = impuesto;
        if (inpA.value == 'Exonerado') {
            var newValorU = inpP.value;
            var newValorT = inpP.value * inpC.value;
            newigv = 0;
        } else if (inpA.value == 'Gravado') {
            var newValoU = inpP.value / (1 + (impuesto / 100));
            var newValorU = newValoU.toFixed(2);
            var newValorT = (inpP.value / (1 + (impuesto / 100))) * inpC.value - inpD.value;
            newigv = (inpC.value * inpP.value / (1 + (impuesto / 100)) - inpD.value) * (impuesto / 100);
        }

        inpS.value = (inpC.value * inpP.value) - inpD.value;
        document.getElementsByName("impuest")[i].innerHTML = newigv.toFixed(2);
        document.getElementsByName("valor_venta_t")[i].innerHTML = newValorT.toFixed(2);
        document.getElementsByName("valor_venta_u")[i].innerHTML = newValorU;
        document.getElementsByName("subtotal")[i].innerHTML = addCommas((inpS.value).toFixed(2));

        if (inpA.value == 'Exonerado') {
            totalexoner += parseFloat(newValorT.toFixed(2) - inpD.value);
        } else {
            totalgravad += parseFloat(newValorT.toFixed(2));
        }

        totaldesc += parseFloat(inpD.value);
        totaligv += parseFloat(newigv.toFixed(2));
        total += inpS.value;

    }

    $('#totalg').html(" " + addCommas(totalgravad.toFixed(2)));
    $('#total_venta_gravado').val(totalgravad.toFixed(2));

    $('#total_venta_exonerado').val(totalexoner);

    $('#total_descuentos').val(totaldesc);

    $('#totaligv').html(" " + addCommas(totaligv.toFixed(2)));
    $('#total_igv').val(totaligv.toFixed(2));

    $("#totalimp").html(" " + addCommas(total.toFixed(2)));
    $("#total_importe").val(total.toFixed(2));

    if (tipo_cambio_dolar != 0.00) {
        $("#total_soles").html("S/. " + (total * tipo_cambio_dolar).toFixed(2))
        $("input[name=total_soles]").val("S/. " + (total * tipo_cambio_dolar).toFixed(2))
    }


    evaluar();
}

function Especial(e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toString();
    letraespecial = "QWERTYUIOPASDFGHJKLZXCVBNMÑqw¨¨ertyuiopasdfghjklñzxcvbnm1234567890@#,;$_&-+(.)/*¿':;!?~`\•√π÷×¶∆£¢€¥^°={}%©®™✓[]";
    especiales = [8, 13, 32, 34];
    tecla_especial = false;
    for (var i in especiales) {
        if (key == especiales[i]) {
            tecla_especial = true;
            break;

        }
    }

    if (letraespecial.indexOf(tecla) == -1 && !tecla_especial) {
        alert("Ese Caracter no está permitido")
        return false;
    }

}
// --

function stopPegado(e) {
    console.log('Aqui no esta permitido pegar 😣', e);
    e.preventDefault();

}

function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}


function calcularTotales() {
    
    evaluar();
}

function evaluar() {
    if (detalles > 0) {
        $("#btnGuardar").show();
    }
    else {
        $("#btnGuardar").hide();
        cont = 0;
    }
}

function eliminarDetalle(indice) {

    //alert ("Eliminando");
    $("#fila" + indice).remove();
    modificarSubtotales();
    detalles = detalles - 1;
    evaluar()
    //limiteArticulo();
}



  
  
init();
