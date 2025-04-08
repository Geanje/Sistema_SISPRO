var tabla;

//funcion que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();

  $("#formulario").on("submit",function(e){
    guardaryeditar(e);
  })

  $("#consultaSunat").hide();
}

//Funcion limpiar
function limpiar() {
  $("#nombre").val("");
  $("#nombre").val("");
  $("#num_documento").val("");
  $("#direccion").val("");
  $("#telefono").val("");
  $("#email").val("");
  $("#idpersona").val("");
  $("#razon_social").val("");
  $("#marca").val("");
  $("#placa").val("");
  $("#licencia_conducir").val("");

}

//Funcion mostrar formulario
function mostrarform(flag)
{
  limpiar();
  if(flag)
  {
    $('#listadoregistros').hide();
    $('#formularioregistros').show();
    $('#btnGuardar').prop("disabled",false);
    $("#consultaSunat").show();
    $("#agregarTransportista").hide();

  }
  else {
    $('#listadoregistros').show();
    $('#formularioregistros').hide();
    $("#consultaSunat").hide();
    $("#agregarTransportista").show();
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
        url:'../ajax/persona.php?op=listart',
        type:"get",
        dataType:"json",
        error:function(e)
        {
          console.log(e.responseText);
        }

      },
      "bDestroy":true,
      "iDisplayLength" :5, //Paginacion
      "order":[[0,"desc"]] //Ordenar (columna,orden)

    }
  ).DataTable();

}

function guardaryeditar(e)
{
  e.preventDefault();// No se activara la accion predeterminada del evento
//   $("#btnGuardar").prop("disabled",true);
  var formData=new FormData($("#formulario")[0]);

  // -- Validación DNI/RUC
  let tipoDocumento = $("#tipo_documento").val()
  let numDocumento = $("#num_documento").val()
  // --
  if (tipoDocumento === "RUC") {
    // --
    if (numDocumento.length === 11) {
       // --
       $.ajax(
        {
          url:"../ajax/persona.php?op=guardaryeditar",
          type:"POST",
          data:formData,
          contentType:false,
          processData:false,
        success : function(datos){
            alert(datos);
            console.log(datos)
          }
        });
      // --
      limpiar();

    } else {
      // --
      alert("El RUC ingresado debe tener 11 dígitos")
    }

  } else if (tipoDocumento === "DNI") {
    // --
    if (numDocumento.length === 8) {
      // --
      $.ajax(
        {
          url:"../ajax/persona.php?op=guardaryeditar",
          type:"POST",
          data:formData,
          contentType:false,
          processData:false,
          success:function(datos) {
            alert(datos);
            mostrarform(false);
            tabla.ajax.reload();
          }
        }
      );
      // --
      limpiar();

    } else {
      // --
      alert("El DNI ingresado debe tener 8 dígitos")
    }
  }

}


// function validarRUC(){
function mostrarInput(bolean){
  if(bolean){
    $("#numRUCSunat").prop("disabled",false);
    $("#numDNISunat").prop("disabled",true);
    $("#numDNISunat").val("");
  }else{
    $("#numRUCSunat").prop("disabled",true);
    $("#numDNISunat").prop("disabled",false);
    $("#numRUCSunat").val("");

  }
}

  $("#numRUCSunat").keyup(validarDocRUC);
  $("#numDNISunat").keyup(validarDocDNI);

  let validado = true;
  function validarDocRUC(){
    var expresion = /^[0-9]*$/;
    
     if($("#numRUCSunat").val().length == 11){
        $(".alertaDoc").html("");
        validado = true;

        if(!expresion.test($("#numRUCSunat").val())){
          $(".alertaDoc").html('<div class="alert alert-warning">Solo debe contener números.</div>');
          // $(".alertaDoc").delay(2000).fadeOut(2000);
          validado = false;
        }
     }else{
        validado = false;
        $(".alertaDoc").html('<div class="alert alert-warning">El número del documento tiene que ser 11 digitos.</div>');
     }

  }

  function validarDocDNI(){
    var expresion = /^[0-9]*$/;
        validado = true;

     if($("#numDNISunat").val().length == 8){
        $(".alertaDoc").html("");
        validado = true;

        if(!expresion.test($("#numDNISunat").val())){
          $(".alertaDoc").html('<div class="alert alert-warning">Solo debe contener números.</div>');
          // $(".alertaDoc").delay(2000).fadeOut(2000);
          validado = false;
        }

     }else{
        validado = false;
        $(".alertaDoc").html('<div class="alert alert-warning">El número del documento tiene que ser 8 digitos.</div>');
     }

  }

 $("#btnEnviarConsulta").click(function(e){
    e.preventDefault();
    // --
    limpiar() // -- Tener limpia la plantita :D 
    // --
    const numRUCSunat = $("#numRUCSunat").val();
    const numDNISunat = $("#numDNISunat").val();
    var msjEnviando='<img src="../files/loading.gif" style="position: absolute; left: 40%; top: 90%;  width: 50%;">';

    // --
    if (numDNISunat != "") {
      // --
      $.ajax({
        url: 'https://apiperu.dev/api/dni/' + numDNISunat,
        type: 'GET',
        dataType: 'json',
        headers: {
            'Authorization':'Bearer 71cd9475deacfcee706366aead4f02dd0df7a5d3eb6b42ad3e033baf03187196',
            'Content-Type':'application/json'
        },
        cache: false,
        success: function(data) {
            // --
            $("#cargandoSunat").html("");
            // --
            if (data.success === true) {
              // --
              var info = data.data
              // --
              $("#nombre").val(info.nombre_completo);
              $("#tipo_documento").val("DNI");
              $("#tipo_documento").selectpicker('refresh');
              $("#num_documento").val(info.numero);
              $("#razon_social").val(info.nombre_completo);
              $("#marca").val(info.marca);
              $("#placa").val(info.placa);
              $("#licencia_conducir").val(info.licencia_conducir);
              // --
              swal({
                title: "¡PERFECTO!",
                type:"success",
                confirmButtonText: "Cerrar",
                closeOnConfirm: true
              })

            } else {
                swal({
                  title: "ERROR",
                  text: data.message,
                  type:"warning",
                  confirmButtonText: "Cerrar",
                  closeOnConfirm: true
                })
            }
        },
        beforeSend:function(){
          $("#cargandoSunat").html(msjEnviando);
        }
      })
    }

    // --
    if (numRUCSunat !="") {
      // --
      $.ajax({
        url: 'https://apiperu.dev/api/ruc/' + numRUCSunat,
        type: 'GET',
        dataType: 'json',
        headers: {
            'Authorization':'Bearer 71cd9475deacfcee706366aead4f02dd0df7a5d3eb6b42ad3e033baf03187196',
            'Content-Type':'application/json'
        },
        cache: false,
        success: function(data) {
            // --
            $("#cargandoSunat").html("");
            // --
            if (data.success === true) {
              // --
              var info = data.data
              // --
              $("#nombre").val(info.nombre_o_razon_social);
              $("#tipo_documento").val("RUC");
              $("#tipo_documento").selectpicker('refresh');
              $("#num_documento").val(info.ruc);
              $("#razon_social").val(info.nombre_o_razon_social);
              $("#marca").val(info.marca);
              $("#placa").val(info.placa);
              $("#licencia_conducir").val(info.licencia_conducir);
              // --
              if (info.direccion != undefined) {
                $("#direccion").val(info.direccion);
              } else {
                $("#direccion").val("-");
              }
              // --
              swal({
                title: "¡PERFECTO!",
                type:"success",
                confirmButtonText: "Cerrar",
                closeOnConfirm: true
              })

            } else {
              swal({
                title: "ERROR",
                text: data.message,
                type:"warning",
                confirmButtonText: "Cerrar",
                closeOnConfirm: true
              })
            }
        },
        beforeSend:function(){
          $("#cargandoSunat").html(msjEnviando);
        }
      })
    }

    // if(validado){
    //   $.ajax({
    //     url:"../ajax/persona.php?op=consultaSunat",
    //     type:"POST",
    //     data:{numRUCSunat:numRUCSunat,numDNISunat:numDNISunat},
    //     success:function(result){
    //         $("#cargandoSunat").html("");
    //         if(result !== "error") {
    //             // -- Aquí es una cosa de locos :v
    //             let stringData = JSON.parse(result);
    //             let data = JSON.parse(stringData);
    //             // --
    //             if (numRUCSunat != "") {
    //                 $("#nombre").val(data.razon_social);
    //                 $("#tipo_documento").val("RUC");
    //                 $("#tipo_documento").selectpicker('refresh');
    //                 $("#num_documento").val(data.ruc);
    //                 $("#direccion").val(data.domicilio_fiscal);
    //                 $("#razon_social").val(data.razon_social);
    //             }
                
    //             if (numDNISunat != "") {
    //                 $("#nombre").val(data.apellido_paterno + " " + data.apellido_materno + " " + data.nombres);
    //                 $("#tipo_documento").val("DNI");
    //                 $("#tipo_documento").selectpicker('refresh');
    //                 $("#num_documento").val(data.dni);
    //                 $("#direccion").val("");
    //                 $("#razon_social").val(data.apellido_paterno + " " + data.apellido_materno + " " + data.nombres);
    //             }
                
    //             swal({
    //               title: "¡PERFECTO!",
    //               // text: "¡La consulta fue todo un éxito!",
    //               type:"success",
    //               confirmButtonText: "Cerrar",
    //               closeOnConfirm: true
    //             })
              
    //         }else{
    //           swal({
    //               title: "ERROR",
    //               text: "¡El número del documento ingresado no es válido!",
    //               type:"warning",
    //               confirmButtonText: "Cerrar",
    //               closeOnConfirm: true
    //             })
    //         }

    //     },
    //     beforeSend:function(){
    //       $("#cargandoSunat").html(msjEnviando);
    //     }

    //   })
    // }else{
    //   swal({
    //           title: "ERROR",
    //           text: "¡Por favor ingrese un número válido!",
    //           type:"error",
    //           confirmButtonText: "Cerrar",
    //           closeOnConfirm: true
    //         })

    // }
    
    // // -- Clear
    // $("#numRUCSunat").val("");
    // $("#numDNISunat").val("");
 })

function mostrar(idpersona)
{
 $.post("../ajax/persona.php?op=mostrar",{idpersona:idpersona}, function(data,status)
{
    data=JSON.parse(data);
    mostrarform(true);

    $("#nombre").val(data.nombre);
    $("#tipo_documento").val(data.tipo_documento);
    $("#tipo_documento").selectpicker('refresh');
    $("#num_documento").val(data.num_documento);
    $("#marca").val(data.marca);
    $("#placa").val(data.placa);
    $("#licencia_conducir").val(data.licencia_conducir);
    $("#idpersona").val(data.idpersona);
    $("#razon_social").val(data.razon_social);
})
}
//funcion desactivar
function eliminar(idpersona)
{
  bootbox.confirm("¿Esta seguro eliminar el Transportista",function(result)
{
  if(result)
  {
    $.post("../ajax/persona.php?op=eliminar",{idpersona:idpersona},function(e){
      bootbox.alert(e);
        tabla.ajax.reload();
    });
  }
})
}





init();
