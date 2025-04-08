var tabla;

//funcion que se ejecuta al inic io
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
  $("#dni").val("");
  //$("#direccion").val("");
  $("#telefono").val("");
  $("#area").val("");
  $("#idtecnico").val("");
  $("#cargo").val("");

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
    $("#agregarTecnico").hide();

  }
  else {
    $('#listadoregistros').show();
    $('#formularioregistros').hide();
    $("#consultaSunat").hide();
    $("#agregarTecnico").show();
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
      ],
      "ajax":
      {
        url:'../ajax/registro_tecnico.php?op=listarTecnico',
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
  $("#btnGuardar").prop("disabled",true);
  var formData=new FormData($("#formulario")[0]);

  $.ajax({
		url: "../ajax/registro_tecnico.php?op=guardar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {
			  // bootbox.alert(datos);
			
	        if(datos !="" || datos !=null){
		        swal({
				  title: "BIEN!",
				  text: "¡"+datos+"!",
				  type:"success",
				  confirmButtonText: "Cerrar",
				  closeOnConfirm: true
				},

				function(isConfirm){

					if(isConfirm){
						// history.back();
						mostrarform(false);
		          		listar();
					}
				});
	        }else{
	        	swal({
				  title: "Error!",
				  text: "¡Ocurrio un error, por favor registre nuevamente al tecnico",
				  type:"warning",
				  confirmButtonText: "Cerrar",
				  closeOnConfirm: true
				},

				function(isConfirm){

					if(isConfirm){
						location.reload(true);
					}
				});
	        }
	    }
	});
	limpiar();
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

   
 })

function mostrar(idtecnico)
{
 $.post("../ajax/registro_tecnico.php?op=mostrar",{idtecnico:idtecnico}, function(data,status)
{
    data=JSON.parse(data);
    mostrarform(true);

    $("#nombre").val(data.nombre);
    $("#area").val(data.area);
    $("#cargo").val(data.cargo);
    $("#dni").val(data.dni);
    $("#telefono").val(data.telefono);
    $("#idtecnico").val(data.idtecnico);
})
}
//funcion desactivar
function eliminar(idtecnico)
{
  bootbox.confirm("¿Esta seguro eliminar el tecnico",function(result)
{
  if(result)
  {
    $.post("../ajax/registro_tecnico.php?op=eliminar",{idtecnico:idtecnico},function(e){
      bootbox.alert(e);
        tabla.ajax.reload();
    });
  }
})
}





init();
