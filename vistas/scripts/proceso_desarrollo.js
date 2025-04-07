function init(){
	// agregaryeditar();


	// mostrar();
	$("#formulario").on("submit",function(e){
		agregaryeditar(e);
	})

  $.post("../ajax/desarrollo-soporte.php?op=selectProyectos", function(r){
    $("#iddesarrollo").html(r);
    $('#iddesarrollo').selectpicker('refresh');
});

}

//funcion cancelarform
function cancelarform()
{
  window.location.href = "desarrollo-soporte.php";
}

window.onload = function () {
  calcularDiferencia();
};


function calcularDiferencia() {
  calcularDiferenciaParaElemento(
    'AN_fecha_inicio',
    'AN_fecha_termino',
    'AN_progreso_dia',
    'AN_fecha_restante',
    'AN_estado'
  );
  calcularDiferenciaParaElemento(
    'DI_fecha_inicio',
    'DI_fecha_termino',
    'DI_progreso_dia',
    'DI_fecha_restante',
    'DI_estado'
  );
  calcularDiferenciaParaElemento(
    'DE_fecha_inicio',
    'DE_fecha_termino',
    'DE_progreso_dia',
    'DE_fecha_restante',
    'DE_estado'
  );
  calcularDiferenciaParaElemento(
    'IM_fecha_inicio',
    'IM_fecha_termino',
    'IM_progreso_dia',
    'IM_fecha_restante',
    'IM_estado'
    
  );
  calcularDiferenciaParaElemento(
    'MAN_fecha_inicio',
    'MAN_fecha_termino',
    'MAN_progreso_dia',
    'MAN_fecha_restante',
    'MAN_estado'
  );
}

  function calcularDiferenciaParaElemento(
    fechaInicioId,
    fechaTerminoId,
    progresoDiaId,
    fechaRestanteId,
    estadoId
  ) {
    var fechaInicio = new Date(document.getElementById(fechaInicioId).value);
    var fechaTermino = document.getElementById(fechaTerminoId).value;
    var fechaActual = new Date(Date.now());

    if (fechaTermino && fechaInicio) {
      var tiempoInicio = fechaInicio.getTime();
      var tiempoTermino = new Date(fechaTermino).getTime();
      var tiempoActual = fechaActual.getTime();

      var diferenciaTotal = Math.ceil(
        (tiempoTermino - tiempoInicio) / (1000 * 60 * 60 * 24)
      );
      var diferenciaActual = Math.ceil(
        (tiempoActual - tiempoInicio) / (1000 * 60 * 60 * 24)
      );

      if (tiempoTermino < tiempoInicio) {
        diferenciaTotal = 0;
        diferenciaActual = 0;
      } else if (tiempoActual > tiempoTermino) {
        diferenciaActual = diferenciaTotal;
      }

      var diaActual =
        Math.ceil((tiempoActual - tiempoInicio) / (1000 * 60 * 60 * 24)) ;

      var progresoDiaElement = document.getElementById(progresoDiaId);
      progresoDiaElement.value = diferenciaActual;
      progresoDiaElement.max = diferenciaTotal;
      progresoDiaElement.setAttribute('data-dia-actual', diaActual);

   // Actualizar etiqueta de días
   var diasElement = document.getElementById('dias-element-' + progresoDiaId);
   if (diasElement) {
     var diasTexto = '';
     if (diferenciaTotal > 12) {
       diasTexto = '1' + '*'.repeat(25) + ' ' + diferenciaTotal;
     } else {
       for (var i = 1; i <= diferenciaTotal; i++) {
         if (i === diaActual) {
           diasTexto += '<strong>' + i + '</strong> ';
         } else {
           diasTexto += i + ' ';
         }
       }
     }
     diasElement.innerHTML = diasTexto;
   }


      // Calcular la fecha restante
      var tiempoRestante = Math.ceil(
        (new Date(fechaTermino).getTime() - new Date(fechaInicio).getTime()) /
          (1000 * 60 * 60 * 24) 
      );
      if (tiempoRestante < 0) {
        tiempoRestante = 0;
      }
      document.getElementById(fechaRestanteId).value = tiempoRestante;

      // Cambiar el estado y establecer los colores de la barra de progreso
      var estadoElement = document.getElementById(estadoId);

      if (tiempoActual < tiempoInicio) {
        estadoElement.value = 'Pendiente';
        progresoDiaElement.style.backgroundColor = 'teal';
        progresoDiaElement.style.backgroundImage = 'none';
      } else if (tiempoActual >= tiempoInicio && tiempoActual <= tiempoTermino) {
        estadoElement.value = 'Proceso';
        progresoDiaElement.style.backgroundColor = 'transparent';
        progresoDiaElement.style.backgroundImage = `linear-gradient(to right, #147ca9 ${((diferenciaTotal - diaActual + 1) / diferenciaTotal) * 100}%, lightgray 0%)`;

      } else {
        estadoElement.value = 'Terminado';
        progresoDiaElement.style.backgroundColor = 'red';
        progresoDiaElement.style.backgroundImage = 'none';
      }
    }
  }
  function agregaryeditar(e){
		e.preventDefault();
		
		var formData = new FormData($("#formulario")[0]);
		$.ajax({
			url:'../ajax/proceso_desarrollo.php?op=editar',
			type:"POST",
			data:formData,
			contentType: false,
            processData: false,
			success:function(data){
				bootbox.alert(data);
        
			}
			
		});
    
}


init();