<?php

include 'header.html.php'; ?>
<link href="css/fondopaginas.css" rel="stylesheet">
<div class="min-vh-100 d-flex flex-column">
  <div class="container py-5 flex-grow-1">
    <div class="card shadow-lg border-0">
      <div class="card-body">
        <h1 class="display-5 fw-bold text-primary mb-3">Contacto</h1>
        <form id="formContacto" method="post">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="mensaje" class="form-label">Mensaje</label>
            <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="privacidad" required>
            <label class="form-check-label" for="privacidad">
              He leído y acepto la <a href="privacidad.html" target="_blank">política de privacidad</a>.
            </label>
          </div>
          <div class="mb-3">
            <label class="form-label" for="captcha">¿Cuánto es <span id="captcha-num1"></span> + <span id="captcha-num2"></span>?</label>
            <input type="number" class="form-control" id="captcha" required>
          </div>
          <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
        <div id="contactoMsg" class="mt-3"></div>
        <hr>
        <p class="mt-3 text-dark">También puedes escribirnos a <a href="mailto:info@tendaonline.com" class="text-primary">info@tendaonline.com</a></p>
      </div>
    </div>
  </div>
  <?php include 'footer.html.php'; ?>
</div>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/contacto.js"></script>
<script>
// Captcha simple de suma
let num1 = Math.floor(Math.random() * 10) + 1;
let num2 = Math.floor(Math.random() * 10) + 1;
document.getElementById('captcha-num1').textContent = num1;
document.getElementById('captcha-num2').textContent = num2;

// Validación extra en el JS de contacto
const form = document.getElementById('formContacto');
if(form) {
  form.addEventListener('submit', function(e) {
    const captchaInput = document.getElementById('captcha');
    if (parseInt(captchaInput.value, 10) !== num1 + num2) {
      e.preventDefault();
      document.getElementById('contactoMsg').innerHTML = '<div class="alert alert-danger">El resultado del captcha es incorrecto.</div>';
      captchaInput.focus();
      return false;
    }
  });
}
</script>
