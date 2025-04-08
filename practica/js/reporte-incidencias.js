function crearSelect() {
  var identificacion = document.getElementById("identificacion");
  var producto = document.getElementById("producto");
  var servicio = document.getElementById("servicio");

  if (identificacion.value == "Producto") {
    producto.style.display = "block";
    servicio.style.display = "none";
  } else if (identificacion.value == "Servicio") {
    producto.style.display = "none";
    servicio.style.display = "block";
  } else {
    producto.style.display = "none";
    servicio.style.display = "none";
  }
}

function validarSoloLetras(input) {
  var regex = /^[A-Za-z\s]+$/;
  var inputValue = input.value;

  if (!regex.test(inputValue)) {
    input.value = inputValue.slice(0, -1); // Elimina el último carácter ingresado
  }
}