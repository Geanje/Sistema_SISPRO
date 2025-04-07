function checkDNI(dni) {
    const dniError = document.getElementById('dni-error');
    if (dni === '') {
      dniError.style.display = 'none';
      return;
    }
    
    if (dni.length === 8) {
      const xhr = new XMLHttpRequest();
      xhr.open('GET', `check_dni.php?dni=${dni}`, true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          if (response.exists) {
            dniError.style.display = 'block';
          } else {
            dniError.style.display = 'none';
          }
        }
      };
      xhr.send();
    }
  }

  function validarFormulario() {
    const dniInput = document.getElementById('dni');
    const dniError = document.getElementById('dni-error');
  
    if (dniError.style.display === 'block') {
      dniInput.focus();
      return;
    }
  
    const requiredInputs = document.querySelectorAll('.form-contact-input[required]');
  
    requiredInputs.forEach(input => {
      if (input.value.trim() === '') {
        input.classList.add('campo-incompleto');
      } else {
        input.classList.remove('campo-incompleto');
      }
    });
  
    const camposIncompletos = document.querySelectorAll('.campo-incompleto');
  
    if (camposIncompletos.length > 0) {
      camposIncompletos[0].focus();
      return;
    }
  
    document.querySelector('form.form-contact').submit();
  }
  
  function checkDNIAndSubmit() {
  const dniInput = document.getElementById('dni');
  const dniError = document.getElementById('dni-error');

  if (dniError.style.display === 'block') {
    dniInput.focus();
    return;
  }

  validarFormulario();
}
