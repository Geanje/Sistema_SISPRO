var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();
    listarComprobantes();

    $.post("../ajax/NCServicio.php?op=selectTipoNotaCredito", function (r) {
        $('#tipoNotaC').html(r);
        $('#tipoNotaC').selectpicker('refresh');
    });

    $("#detalleConcepto").on("submit", function (e) {
        guardaryeditar(e);
    });
}

function limpiar() {
    $("#idcliente").val("");
    $("#cliente").val("");
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hor').val(today);

    $("#tipo_comprobante").val("Boleta");
    $("#tipo_comprobante").selectpicker('refresh');
}

function mostrarform(flag) {
    const display = flag ? 'show' : 'hide';
    const reverseDisplay = flag ? 'hide' : 'show';
    limpiar();
    $("#listadoregistros, #btnagregar, #btnaescoger")[reverseDisplay]();
    $("#formularioregistros, #btnGuardar, #btnCancelar, #btnAgregarArt")[display]();
    if (!flag) {
        $("#listaCompro1").text("");
    }
}

function cancelarform() {
    new Promise(resolve => {
        mostrarform(false);
        resolve();
    }).then(() => location.reload(true));
}

function anular(id_factura) {
    $.post("../ajax/notaCredito.php?op=anular", { id_factura: id_factura }, function (e) {
    });
}

function enviarDatosASunat(nombre_archivo, id_factura) {
    $.ajax({
        type: "POST",
        url: "../ajax/xml_sunat_Factura.php",
        data: {
            nombre_archivo: nombre_archivo,
            id_factura: id_factura
        },
        success: function (response) {
            swal({
                title: "BIEN!",
                text: response,
                type: "success",
                confirmButtonText: "Cerrar",

            });
        },
        error: function (error) {
            swal({
                title: "Error!",
                text: error,
                type: "warning",
                confirmButtonText: "Cerrar",
            });
        }
    });
}

function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#detalleConcepto")[0]);
    $.ajax({
        url: "../ajax/NCServicio.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            if (datos == "Venta registrada" || datos == "No se pudieron registrar todos los datos de la venta") {
                swal({
                    title: 'Error!',
                    text: datos,
                    type: 'error',
                })
            } else {
                swal({
                    title: 'Registrado!',
                    text: 'Comprobante Anulado!',
                    type: 'success',
                })
            }
            mostrarform(false);
            listar();
        }
    });
}

function mostrarDocRel(id_factura, idfacturarelacionado) {
    $.post("../ajax/NCServicio.php?op=mostrarDocRel", { id_factura, idfacturarelacionado }, function (data, status) {
        const { fecha, cliente, usuario, serie, correlativo, tipoNotaV, sustento } = JSON.parse(data);
        $("#fechar").html(fecha);
        $("#clienter").html(cliente);
        $("#usuarior").html(usuario);
        $("#documentor").html(`${serie}-${correlativo}`);
        $("#tiponcr").html(tipoNotaV);
        $("#sustentor").html(sustento);
    });
}


function listar() {
    $('#tbllistado').DataTable({
        processing: true,
        serverSide: true,
        lengthChange: false,
        ajax: {
            url: '../ajax/NCServicio.php?op=listar',
            type: 'GET',
            dataType: 'json',
            error: function (e) {
                console.log(e.responseText);
            }
        },
        destroy: true,
        pageLength: 9,
        order: [[0, 'desc']]
    });
}

function listarComprobantes() {
    tabla = $('#tblcomprobantes').dataTable({
        "aServerSide": true,
        "ajax": {
            url: '../ajax/NCServicio.php?op=listarComprobantes',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 9,
        "order": [[0, "desc"]]
    });
}

function agregarTipoNota3(cont, articulo) {
    $("#cont").val(cont);
    $("#dice").val(articulo);
}

function limpiarAgregarTipoNota3() {
    var acumu = $("#cont").val();
    var dicese = $("#dice").val();
    var debedecir = $("#debedecir").val();

    $("#articuloo" + acumu).text("Dice: " + dicese + "-Debe decir: " + debedecir);
    $("#tiponc3" + acumu).val("Dice: " + dicese + "-Debe decir: " + debedecir);
    $("#debedecir").val("");

}


function agregarDocumento(idfacturarelacionado, codigonota, idcliente, cliente, num_documento, serie, correlativo, idmoneda, descripcionmoneda, descripcion_tipo_comprobante, fecha, op_gravadas, op_exoneradas, venta_total, impuesto, ultimo_correlativo, serie_correlativo) {
    var tiponotacred;
    if (codigonota == '1') {
        tiponotacred = 'Anulacion de la operación';
    } else if (codigonota == '2') {
        tiponotacred = 'Anulación por error en el RUC';
    } else if (codigonota == '6') {
        tiponotacred = 'Devolución total'
    } else if (codigonota == '7') {
        tiponotacred = 'Devolución parcial';
    }
    var conceptos =
        '<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">' +


        '</div>' +

        '<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
        '<div class="alert alert-success" align="center">' +
        '<span style="text-align:center; font-size:large;font-weight: 500;"  >' + tiponotacred + '</span>' +
        '<input type="hidden" name="tiponotacred" value="' + tiponotacred + '">' +
        '</div>' +
        '</div>' +
        '<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">' +


        '</div>' +

        '<div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">' +
        '<h4>Documento que modifica: ' + descripcion_tipo_comprobante + '</h4>' +
        '<input type="hidden" name="id_factura" id="id_factura" >' +
        '<input type="hidden" name="idtiponotacredito" id="idtiponotacredito" value="' + codigonota + '">' +
        '<input type="hidden" name="idfacturarelacionado" id="idfacturarelacionado" value="' + idfacturarelacionado + '">' +
        '<input type="hidden" name="impuesto" id="impuesto" value="' + impuesto + '">' +
        '</div>' +
        //--- COLOCAR LA SERIES Y CORRELATIVOS AUTOMATICAMENTE LUEGO DE AJAX Y MODELO ----//
        '<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
        '<input type="text" class="form-control" maxlength="4" name="serie" id="serie" value="' + serie_correlativo + '" placeholder="serie"  readonly="readonly" required >' +
        '</div>' +
        '<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">' +
        '<div >' +
        '<input type="text" class="form-control" maxlength="8" name="correlativo" value="' + ultimo_correlativo + '"  id="correlativo" placeholder="correlativo" readonly="readonly" required>' +
        '</div>' +
        '</div>' +



        '<div class="form-group col-lg-9 col-md-9 col-sm-9 col-xs-12">' +
        '<label>Cliente(*):</label>' +
        '<input type="hidden" class="form-control" name="idcliente" id="idclientee" value="' + idcliente + '" placeholder="cliente" >' +
        '<input type="text" class="form-control" name="" value="' + cliente + '" placeholder="cliente" readonly="readonly">' +
        '</div>' +
        '<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
        '<label>Fecha(*):</label>' +
        '<input type="date"  class="form-control" name="fecha_hora" id="" value="' + fecha + '" readonly="readonly required="">' +
        '</div>' +
        '<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">' +
        '<label>Número de documento:</label>' +
        '<input type="text" class="form-control" name="doc" id="doc" value="' + num_documento + '" readonly="readonly placeholder="Número de documento">' +
        '</div>' +

        '<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">' +
        '<label>Serie:</label>' +
        '<input type="text" class="form-control" name="" id="" value="' + serie + '" readonly="readonly required="">' +
        '</div>' +
        '<div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">' +
        '<label>Correlativo:</label>' +
        '<input type="text" class="form-control" name="" id="" value="' + correlativo + '" readonly="readonly required="">' +
        '</div>' +
        '<div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
        '<label>Moneda:</label>' +
        '<input type="hidden" name="idmoneda" id="idmoneda" value="' + idmoneda + '">' +
        '<input  type="text" class="form-control" name="descripcionmoneda"  value="' + descripcionmoneda + '" readonly="readonly placeholder="S/.">' +
        '</div>' +
        '<div class="form-group col-lg-9 col-md-9 col-sm-12 col-xs-12">' +
        '<label>Sustento:</label>' +
        '<input  type="text" class="form-control" name="sustento"  id="sustento" placeholder="Motivo o sustento" required="">' +
        '</div>'
        ;

    $('#listaCompro1').append(conceptos);

    $.post("../ajax/NCServicio.php?op=listarDetalleComprobantes&id=" + idfacturarelacionado, function (r) {
        $("#listaCompro2").html(r);
    });
    modificarSubtotales();

}


function modificarSubtotales() {
    var impuesto = 18;
    var cant = document.getElementsByName("cantidadd[]");
    var desc = document.getElementsByName("descuentoo[]");
    var prec = document.getElementsByName("precio_ventaa[]");
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
        var inpP = prec[i];
        var inpS = sub[i];
        var inpA = afec[i];

        if (inpA.value == 'Exonerado') {
            var newValorU = inpP.value;
            var newValorT = inpP.value * inpC.value;
            newigv = 0;
        } else {
            var newValoU = inpP.value / (1 + (impuesto / 100));
            var newValorU = newValoU.toFixed(2);
            var newValorT = (inpP.value / (1 + (impuesto / 100))) * inpC.value - inpD.value;
            newigv = (inpC.value * inpP.value / (1 + (impuesto / 100)) - inpD.value) * (impuesto / 100);
        }

        inpS.value = (inpC.value * inpP.value) - inpD.value;
        document.getElementsByName("impuest")[i].innerHTML = newigv.toFixed(2);
        document.getElementsByName("valor_venta_t")[i].innerHTML = newValorT.toFixed(2);
        document.getElementsByName("valor_venta_u")[i].innerHTML = newValorU;
        document.getElementsByName("subtotal")[i].innerHTML = (inpS.value).toFixed(2);

        if (inpA.value == 'Exonerado') {
            totalexoner += parseFloat(newValorT.toFixed(2) - inpD.value);
        } else {
            totalgravad += parseFloat(newValorT.toFixed(2));
        }

        totaldesc += parseFloat(inpD.value);
        totaligv += parseFloat(newigv.toFixed(2));
        total += inpS.value;
    }


    $('#totalg').html("$. " + totalgravad.toFixed(2));
    $('#total_venta_gravado').val(totalgravad.toFixed(2));

    $('#totale').html("$. " + totalexoner);
    $('#total_venta_exonerado').val(totalexoner);

    $('#totald').html("$. " + totaldesc);
    $('#total_descuentos').val(totaldesc);

    $('#totaligv').html("$. " + totaligv.toFixed(2));
    $('#total_igv').val(totaligv.toFixed(2));

    $("#totalimp").html("$. " + total.toFixed(2));
    $("#total_importe").val(total.toFixed(2));


}

function eliminarDetallee(indice) {
    $(".fila" + indice).remove();
    modificarSubtotales();
}


function eliminarDetalle3(indice) {
    $(".fila" + indice).remove();
}

function limpiarAlCerrar() {
    $("#listaCompro1").append("");
}

function isession() {
    var isession = $("#tipoNotaC").val();
    $.post("../ajax/NCServicio.php?op=isession", { isession: isession })
        .done(function () {
            location.reload(true);
        })
        .fail(function () {
            console.error("Error occurred while posting data");
        });
}

init();
