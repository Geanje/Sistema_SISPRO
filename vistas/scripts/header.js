alert('Tu sesión ha caducado. Haz clic en "Aceptar" para cerrar la sesión.');

// Realizar una solicitud AJAX para cerrar la sesión del usuario cuando se haga clic en "Aceptar"
$(document).on('click', function() {
    // Realizar la solicitud AJAX
    $.post("../ajax/usuario.php?op=salir", function (response) {
        // Mostrar un mensaje en la consola si lo deseas
        console.log('La sesión se ha cerrado exitosamente.');
    });
});