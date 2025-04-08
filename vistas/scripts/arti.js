var tabla;

// Función para mostrar información del archivo seleccionado
function mostrarArchivo() {
  const archivoInput = document.getElementById('archivoInput');
  const archivo = archivoInput.files[0];

  if (archivo) {
    document.getElementById('archivoPreview').style.display = 'flex';
    document.getElementById('fileName').textContent = archivo.name;
    document.getElementById('fileSize').textContent = `Tamaño: ${(
      archivo.size / 1024
    ).toFixed(2)} KB - Tipo: ${archivo.name.split('.').pop().toUpperCase()}`;

    // Mostrar contenido en tabla
    leerExcel(archivo);
  }
}

// Funcion para abrir el modal de IMPORTAR
function mostrarModal() {
  document.getElementById('modalImportar').style.display = 'block';
}

// Funcion para cerrar el modal de IMPORTAR
function cerrarModal() {
  document.getElementById('modalImportar').style.display = 'none';
  eliminarArchivo();
}

//Funcion para eliminar el archivo del modal
function eliminarArchivo() {
  document.getElementById('archivoPreview').style.display = 'none';
  document.getElementById('archivoInput').value = '';

  // Limpiar la tabla
  document.getElementById('tabla').innerHTML = '';
  document.getElementById('tabla').style.display = 'none';
}

// Función para leer y mostrar el contenido del archivo Excel
function leerExcel(archivo) {
  function ajustarZIndex() {
    setTimeout(function () {
      $('.bootbox.modal').css('z-index', '9999');
      $('.modal-backdrop').css('z-index', '9998');
    }, 100);
  }

  var archivo = event.target.files[0];
  if (!archivo) return;

  var lector = new FileReader();
  lector.readAsArrayBuffer(archivo);

  lector.onload = function (e) {
    var datos = new Uint8Array(e.target.result);
    var workbook = XLSX.read(datos, { type: 'array' });
    var hoja = workbook.Sheets[workbook.SheetNames[0]];
    var datosTabla = XLSX.utils.sheet_to_json(hoja, { header: 1, defval: '' });

    var encabezadosOriginales = datosTabla[0].map(
      (h) => h?.toString().trim() || ''
    );
    var encabezados = encabezadosOriginales.map((h) => h.toLowerCase());

    const columnasObligatorias = {
      nombre: 'Nombre',
      categoría: 'Categoría',
      código: 'Código',
      'unidad medida': 'Unidad Medida',
      estado: 'Estado',
    };

    for (const [key, value] of Object.entries(columnasObligatorias)) {
      if (!encabezados.includes(key)) {
        Swal.fire({
          title: 'Error',
          text: `El archivo debe contener la columna: ${value}`,
          icon: 'error',
          timer: 4000,
          showConfirmButton: false,
        });
        ajustarZIndex();
        return;
      }
    }

    const indices = {
      nombre: encabezados.indexOf('nombre'),
      categoria: encabezados.indexOf('categoría'),
      codigo: encabezados.indexOf('código'),
      unidadMedida: encabezados.indexOf('unidad medida'),
      estado: encabezados.indexOf('estado'),
    };

    for (const [key, index] of Object.entries(indices)) {
      if (index === -1) {
        Swal.fire({
          title: 'Error',
          text: `No se encontró la columna '${key}' en el archivo Excel.`,
          icon: 'error',
          timer: 4000,
          showConfirmButton: false,
        });
        ajustarZIndex();
        return;
      }
    }

    var productos = [];
    var hayError = false;
    var codigosUnicos = new Set();
    var nombresVistos = new Set();

    for (var i = 1; i < datosTabla.length; i++) {
      var nombre = datosTabla[i][indices.nombre];
      var codigo = datosTabla[i][indices.codigo];

      if (!nombre) continue;
      if (!codigo || isNaN(codigo)) {
        Swal.fire({
          title: 'Error',
          text: `Error en la fila ${i + 1}: El código debe ser un número`,
          icon: 'error',
          timer: 4000,
          showConfirmButton: false,
        });
        hayError = true;
        ajustarZIndex();
        break;
      }

      if (nombresVistos.has(nombre)) {
        Swal.fire({
          title: 'Error',
          text: `Error en la fila ${
            i + 1
          }: El nombre "${nombre}" está duplicado en el Excel`,
          icon: 'error',
          timer: 4000,
          showConfirmButton: false,
        });
        hayError = true;
        ajustarZIndex();
        break;
      }
      nombresVistos.add(nombre);

      if (codigosUnicos.has(codigo)) {
        Swal.fire({
          title: 'Error',
          text: `Error en la fila ${
            i + 1
          }: El código ${codigo} ya existe (debe ser único)`,
          icon: 'error',
          timer: 4000,
        });
        hayError = true;
        ajustarZIndex();
        break;
      }
      codigosUnicos.add(codigo);

      var unidadMedida = datosTabla[i][indices.unidadMedida]?.toUpperCase();
      const unidadesValidas = ['NIU', 'KGM', 'LBR', 'GRM', 'LTR', 'MTQ', 'MTR'];

      if (!unidadesValidas.includes(unidadMedida)) {
        Swal.fire({
          title: 'Error',
          text: `Error en la fila ${
            i + 1
          }: Unidad de medida no válida. Valores permitidos: ${unidadesValidas.join(
            ', '
          )}`,
          icon: 'error',
          timer: 4000,
          showConfirmButton: false,
        });
        hayError = true;
        ajustarZIndex();
        break;
      }

      var estado = datosTabla[i][indices.estado]
        ?.toString()
        .trim()
        .toLowerCase();
      if (estado !== 'activado' && estado !== 'desactivado') {
        Swal.fire({
          title: 'Error',
          text: `Error en la fila ${
            i + 1
          }: El estado debe ser 'Activado' o 'Desactivado'`,
          icon: 'error',
          timer: 5000,
          showConfirmButton: false,
        });
        hayError = true;
        ajustarZIndex();
        break;
      }

      productos.push({
        nombre: nombre,
        categoria: datosTabla[i][indices.categoria] || '',
        codigo: codigo,
        unidadMedida: unidadMedida,
        estado: estado,
      });
    }

    if (!hayError) {
      if (productos.length > 0) {
        mostrarTabla(productos);
      } else {
        Swal.fire({
          title: 'Error',
          text: 'El archivo no contiene datos válidos',
          icon: 'error',
        });
        ajustarZIndex();
      }
    }
  };
}

// Función para mostrar solo las columnas especificadas
function mostrarTabla(productos) {
  // Definir las columnas a mostrar y sus títulos
  const columnasMostrar = [
    { key: 'nombre', title: 'Nombre' },
    { key: 'categoria', title: 'Categoría' },
    { key: 'codigo', title: 'Código' },
    { key: 'unidadMedida', title: 'Unidad Medida' },
    { key: 'estado', title: 'Estado' },
  ];

  var tablaHTML =
    "<table class='table table-bordered table-striped'><thead><tr>";

  // Encabezados de la tabla
  columnasMostrar.forEach(function (columna) {
    tablaHTML += `<th>${columna.title}</th>`;
  });

  tablaHTML += '</tr></thead><tbody>';

  // Llenar la tabla con los datos
  productos.forEach(function (producto) {
    tablaHTML += '<tr>';

    columnasMostrar.forEach(function (columna) {
      tablaHTML += `<td>${producto[columna.key]}</td>`;
    });

    tablaHTML += '</tr>';
  });

  tablaHTML += '</tbody></table>';
  document.getElementById('tabla').innerHTML = tablaHTML;
}

document
  .querySelector("input[type='file']")
  .addEventListener('change', leerExcel);

// Función para procesar el archivo Excel
function procesarExcel() {
  console.log('Iniciando el procesamiento de Excel...');
  const tabla = document.querySelector('#tabla table');

  // Comprobamos si la tabla existe
  if (!tabla) {
    Swal.fire({
      title: 'Error',
      text: 'Por favor, importe un archivo Excel primero',
      icon: 'warning',
      timer: 3000,
      showConfirmButton: false,
    });
    return;
  }

  const tipoImportacion = document.querySelector(
    'input[name="importar"]:checked'
  );
  if (!tipoImportacion) {
    Swal.fire({
      title: 'Error',
      text: 'Seleccione un tipo de importación',
      icon: 'warning',
      timer: 3000,
      showConfirmButton: false,
    });
    return;
  }

  if (tipoImportacion.value === 'añadir') {
    Swal.fire({
      title: 'Confirmación',
      text: '¿Está seguro de que desea añadir estos datos?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí',
      cancelButtonText: 'No',
      customClass: {
        confirmButton: 'swal-confirm-button',
      },
    }).then((result) => {
      if (result.isConfirmed) {
        const filas = tabla.querySelectorAll('tbody tr');
        const datos = Array.from(filas).map((fila) => {
          const celdas = fila.querySelectorAll('td');
          return {
            nombre: celdas[0].textContent.trim(),
            categoria: celdas[1].textContent.trim(),
            codigo: celdas[2].textContent.trim(),
            unidad_medida: celdas[3].textContent.trim().toUpperCase(),
            condicion:
              celdas[4].textContent.trim().toLowerCase() === 'activado' ? 1 : 0,
          };
        });

        const batchSize = 1000;
        for (let i = 0; i < datos.length; i += batchSize) {
          const batch = datos.slice(i, i + batchSize);
          enviarDatos(batch);
        }
      }
    });
  }
}

async function enviarDatos(batch) {
  const spinner = document.createElement('div');
  spinner.className = 'spinner';
  spinner.innerHTML = 'Importando datos...';
  document.body.appendChild(spinner);

  try {
    console.log('Enviando lote de datos:', batch);

    // Realizamos la solicitud AJAX
    const respuesta = await $.ajax({
      url: '../ajax/articulo.php?op=importar',
      type: 'POST',
      data: { datos: JSON.stringify(batch) },
      dataType: 'json', // Aseguramos que la respuesta se procese como JSON
      timeout: 0,
    });

    console.log('Respuesta del servidor:', respuesta);

    // Verificamos si la respuesta tiene un estado exitoso
    if (respuesta.status) {
      let mensajeExito = `Datos importados correctamente. Total insertados: ${respuesta.insertados}`;

      // Verificar si hubo duplicados
      if (
        Array.isArray(respuesta.duplicados) &&
        respuesta.duplicados.length > 0
      ) {
        mensajeExito += `\n\nCódigos duplicados en Excel: ${respuesta.duplicados.join(
          ', '
        )}`;
      }
      if (respuesta.duplicados_bd > 0) {
        mensajeExito += `\n\nCódigos duplicados en BD: ${respuesta.duplicados_bd}`;
      }

      Swal.fire({
        title: 'Éxito',
        text: mensajeExito,
        icon: 'success',
        timer: 5000,
        showConfirmButton: false,
      });

      // Recargamos la tabla si la importación fue exitosa
      $('#tbllistado').DataTable().ajax.reload();
    } else {
      let mensajeError = respuesta.mensaje || 'Ocurrió un error desconocido.';

      // Verificar si hubo duplicados y mostrarlos
      if (
        Array.isArray(respuesta.duplicados) &&
        respuesta.duplicados.length > 0
      ) {
        mensajeError += `\n\nCódigos duplicados en Excel: ${respuesta.duplicados.join(
          ', '
        )}`;
      }
      if (respuesta.duplicados_bd > 0) {
        mensajeError += `\n\nCódigos duplicados en BD: ${respuesta.duplicados_bd}`;
      }

      Swal.fire({
        title: 'Error',
        text: mensajeError,
        icon: 'error',
        timer: 10000,
        showConfirmButton: false,
      });
    }
  } catch (error) {
    console.error('Error en la solicitud AJAX:', error);

    Swal.fire({
      title: 'Error',
      text: `No se pudo importar los datos. Verifica la consola para más detalles. Error: ${error.message}`,
      icon: 'error',
      timer: 5000,
      showConfirmButton: false,
    });
  } finally {
    // Eliminar el spinner después de completar la solicitud
    spinner.remove();
  }
}

// Agregar el estilo para el botón de confirmación
const style = document.createElement('style');
style.innerHTML = `
  .swal-confirm-button {
    background-color: #147CA9 !important;
    border-color: #147CA9 !important;
  }
  .spinner {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(255, 255, 255, 0.8);
    padding: 20px;
    border-radius: 5px;
    z-index: 1000;
    font-size: 20px;
    text-align: center;
  }
`;
document.head.appendChild(style);

//Función que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();
  otros();
  $('.afectacionArticulo').hide();

  $('#formulario').on('submit', function (e) {
    guardaryeditar(e);
  });

  //Cargamos los items al select categoria
  $.post('../ajax/articulo.php?op=selectCategoria', function (r) {
    $('#idcategoria').html(r);
    $('#idcategoria').selectpicker('refresh');
  });
  $('#imagenmuestra').hide();
  // $("#detalleunidad").prop('disabled',true);
  // document.getElementById("detalleunidad").val("12323");
  // if($('#unidadmedida').checked()){
  // 	$('#detalleunidad').hide();
  // };

  // if(document.getElementById("unidadmedida").checked){
  //       document.getElementById("detalleunidad").disabled=true;}

  // otros();

  $('#btnAgregarStock').click(function () {
    var idarti = $('#idarti').val();
    var stockanti = $('#stockanti').val();
    stockanti = parseInt(stockanti);
    var stocknew = $('#astock').val();
    stocknew = parseInt(stocknew);

    agregarStock(idarti, stockanti, stocknew);
  });

  // $('#stock').prop("disabled",false);
  // disabledStock();
}

function otros() {
  // if(document.getElementById("unidadmedida").checked){
  //       document.getElementById("detalleunidad").val("12323");
  //   };

  if ($('#unidadmedida').val() == 'otros') {
    $('#detalleunidad').prop('readonly', false);
  } else {
    $('#detalleunidad').prop('readonly', true);
    $('#detalleunidad').val('');
  }
}

function desactivarStock() {
  $('#detalleunidad').prop('readonly', true);
  $('#stock').prop('readonly', false);
}

//Función limpiar
function limpiar() {
  $('#codigo').val('');
  $('#nombre').val('');
  $('#descripcion').val('');
  $('#stock').val('');
  $('#imagenmuestra').attr('src', '');
  $('#imagenactual').val('');
  $('#print').hide();
  $('#idarticulo').val('');
  $('#gravado').prop('checked', true);
}

// function otros(){

// 	if(document.getElementById("unidadmedida").checked){
//        document.getElementById("detalleunidad").val("12323");
//    };
// }

//Función mostrar formulario
function mostrarform(flag) {
  limpiar();
  if (flag) {
    $('#listadoregistros').hide();
    $('#formularioregistros').show();
    $('#btnGuardar').prop('disabled', false);
    $('#btnagregar').hide();
    $('#detunidad').hide();
    $('#btnprecio').hide();
    $('#btnreset').hide();
    $('#btnreporte').hide();
  } else {
    $('#listadoregistros').show();
    $('#formularioregistros').hide();
    $('#btnagregar').show();
    $('#btnprecio').show();
    $('#btnreset').show();
    $('#btnreporte').show();
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
      aProcessing: true, //Activamos el procesamiento del datatables
      aServerSide: true, //Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip', //Definimos los elementos del control de tabla
      buttons: ['copyHtml5', 'excelHtml5'],
      ajax: {
        url: '../ajax/articulo.php?op=listar',
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
  $('#btnGuardar').prop('disabled', true);
  var formData = new FormData($('#formulario')[0]);

  $.ajax({
    url: '../ajax/articulo.php?op=guardaryeditar',
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      bootbox.alert(datos);
      mostrarform(false);
      tabla.ajax.reload();
    },
  });
  limpiar();
}

/*function disabledStock(){
	   var st= $('#stock').val();

	 if(st!=''){
			   $('#stock').prop("disabled",true);
		   }else{
			   $('#stock').prop("disabled",false);

		   }
   }*/
function mostrar(idarticulo) {
  //disabledStock()
  //alert("Hola")
  $.post(
    '../ajax/articulo.php?op=mostrar',
    { idarticulo: idarticulo },
    function (data, status) {
      //alert("Hola")
      data = JSON.parse(data);
      mostrarform(true);
      //val(data.stock);
      $('#idcategoria').val(data.idcategoria);
      $('#idcategoria').selectpicker('refresh');
      $('#codigo').val(data.codigo);
      $('#nombre').val(data.nombre);
      $('#stock').val(data.stock);
      $('#descripcion').val(data.descripcion);
      $('#imagenmuestra').show();
      $('#imagenmuestra').attr('src', '../files/articulos/' + data.imagen);
      $('#imagenactual').val(data.imagen);
      $('#idarticulo').val(data.idarticulo);
      $('#unidadmedida').val(data.unidad_medida);
      $('#detalleunidad').val(data.descripcion_otros);
      if (data.afectacion == 'Gravado') {
        $('#gravado').prop('checked', true);
        $('#exonerado').prop('checked', false);
      } else {
        $('#gravado').prop('checked', false);
        $('#exonerado').prop('checked', true);
      }

      generarbarcode();
    }
  );
}

function mostrarCodigoBarra(idarticulo) {
  $.post(
    '../ajax/articulo.php?op=mostrarCodigoBarra',
    { idarticulo: idarticulo },
    function (data, status) {
      data = JSON.parse(data);
      $('#codigob').val(data.codigo);
      codigo = $('#codigob').val();
      JsBarcode('#barcodeb', codigo, {
        width: 4,
        height: 100,
        // lineColor: "blue"
        // displayValue: false
      });
    }
  );
}

function mostrarStock(idarticulo) {
  $.post(
    '../ajax/articulo.php?op=mostrarStock',
    { idarticulo: idarticulo },
    function (data, status) {
      data = JSON.parse(data);
      $('#art').val(data.nombre);
      $('#idarti').val(data.idarticulo);
      $('#stockanti').val(data.stock);
    }
  );
}

function agregarStock(idarti, stockanti, stocknew) {
  // $.post("../ajax/articulo.php?op=agregarStock",{idarticulo:idarticulo,stock:stock},function(e){
  // 	// data = JSON.parse(data);

  // })
  var MsjEnviando = '<img src="../files/loading.gif">';

  cadena =
    'idarti=' + idarti + '&stockanti=' + stockanti + '&stocknew=' + stocknew;

  $.ajax({
    type: 'POST',
    url: '../ajax/articulo.php?op=agregarStockk',
    data: cadena,

    beforeSend: function () {
      $('.msjRespuesta').html(MsjEnviando);
    },
    // error: function() {
    //         $('.msjRespuesta').html(MsjError);
    //     },

    success: function (r) {
      if (r) {
        $('.msjRespuesta').html('');
        listar();
        $('#astock').val('');
      } else {
        alert('No se pudo actualizar :(');
        // alertify.error('No se pudo actualizar :)');
      }
    },
  });
}

//Función para desactivar registros
function desactivar(idarticulo) {
  bootbox.confirm('¿Está Seguro de desactivar el artículo?', function (result) {
    if (result) {
      $.post(
        '../ajax/articulo.php?op=desactivar',
        { idarticulo: idarticulo },
        function (e) {
          bootbox.alert(e);
          tabla.ajax.reload();
        }
      );
    }
  });
}

//Función para activar registros
function activar(idarticulo) {
  bootbox.confirm('¿Está Seguro de activar el Artículo?', function (result) {
    if (result) {
      $.post(
        '../ajax/articulo.php?op=activar',
        { idarticulo: idarticulo },
        function (e) {
          bootbox.alert(e);
          tabla.ajax.reload();
        }
      );
    }
  });
}

//función para generar el código de barras
function generarbarcode() {
  codigo = $('#codigo').val();
  JsBarcode('#barcode', codigo);
  $('#print').show();
}

//Función para imprimir el Código de barras
function imprimir() {
  $('#print').printArea();
}
function imprimirb() {
  $('#printb').printArea();
}
function resetearstock(idarticulo) {
  bootbox.confirm(
    '¿Está Seguro de Reiniciar el total de sus Stock de todos sus articulos?',
    function (result) {
      if (result) {
        $.post(
          '../ajax/articulo.php?op=resetearstock',
          { idarticulo: idarticulo },
          function (e) {
            tabla.ajax.reload();
          }
        );
      }
    }
  );
}
init();
