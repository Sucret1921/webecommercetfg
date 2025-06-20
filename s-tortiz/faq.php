<?php include 'header.html.php'; ?>
<link href="css/fondopaginas.css" rel="stylesheet">
<div class="min-vh-100 d-flex flex-column">
  <div class="container py-5 flex-grow-1">
    <div class="card shadow-lg border-0">
      <div class="card-body">
        <h1 class="display-5 fw-bold text-primary mb-3">Preguntas Frecuentes</h1>
        <div class="accordion" id="faqAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                ¿Cómo puedo realizar una compra?
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Selecciona los productos que deseas, añádelos al carrito y sigue el proceso de pago seguro.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                ¿Cuánto tarda el envío?
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                El envío suele tardar entre 24 y 72 horas laborables, dependiendo de la zona.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                ¿Qué métodos de pago aceptan?
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Aceptamos tarjetas de crédito/débito y PayPal.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'footer.html.php'; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
