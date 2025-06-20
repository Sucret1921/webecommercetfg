<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bannerinfo.css" rel="stylesheet">
  <link href="css/fondoazul.css" rel="stylesheet">
  <link href="css/sobre_nosotros.css" rel="stylesheet">
</head>
 <?php include 'header.html.php'; ?>
<body class="bg-light">
  <div id="carouselSobreNosotros" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselSobreNosotros" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselSobreNosotros" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselSobreNosotros" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="images/imagenesGlobales/bienvenida.jpg" class="d-block w-100" alt="Equipo Tenda" style="max-height:600px;min-height:400px;object-fit:cover;">
        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
          <h5 class="text-info">Bienvenido a Tendenta Online</h5>
          <p>Tu tienda de confianza en tecnología y hogar.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="images/imagenesGlobales/atencioncliente.jpg" class="d-block w-100" alt="Atención al cliente" style="max-height:600px;min-height:400px;object-fit:cover;">
        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
          <h5 class="text-info">Atención Personalizada</h5>
          <p>Resolvemos tus dudas y te acompañamos en cada compra.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="images/imagenesGlobales/enviosrapidos.jpg" class="d-block w-100" alt="Envíos rápidos" style="max-height:600px;min-height:400px;object-fit:cover;">
        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
          <h5 class="text-info">Envíos rápidos</h5>
          <p>Recibe tus productos en 24-72h en toda España.</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselSobreNosotros" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselSobreNosotros" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Siguiente</span>
    </button>
  </div>

  <div class="container mb-5">
    <div class="row align-items-center">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="card shadow border-0 h-100">
          <div class="card-body">
            <h1 class="display-5 fw-bold text-primary mb-3">Sobre Nosotros</h1>
            <p class="lead text-dark">Somos una tienda online dedicada a ofrecer los mejores productos tecnológicos y de hogar, con atención personalizada y envíos rápidos a toda España. Nuestro equipo está formado por profesionales apasionados por la innovación y el servicio al cliente.</p>
            <hr>
            <h3 class="text-primary">Nuestra Misión</h3>
            <p class="text-dark">Brindar una experiencia de compra sencilla, segura y satisfactoria, con productos de calidad y precios competitivos.</p>
            <h3 class="text-primary">Visión</h3>
            <p class="text-dark">Ser la tienda online de referencia en tecnología y hogar, reconocida por la confianza y satisfacción de nuestros clientes.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card shadow border-0 h-100">
          <div class="card-body">
            <h3 class="text-primary">Valores</h3>
            <ul class="text-dark">
              <li>Compromiso con el cliente</li>
              <li>Innovación constante</li>
              <li>Transparencia y honestidad</li>
              <li>Calidad en el servicio</li>
            </ul>
            <div class="mt-4">
              <img src="images/imagenesGlobales/valores.jpg" class="img-fluid rounded shadow" alt="Equipo Tenda">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container mb-5">
    <h2 class="text-center text-white mb-4">¿Por qué elegirnos?</h2>
    <div class="row justify-content-center g-4 mb-5">
      <div class="col-md-4">
        <div class="card h-100 shadow border-0 text-center">
          <div class="card-body">
            <i class="fas fa-headset fa-3x text-info mb-3"></i>
            <h5 class="card-title">Atención personalizada</h5>
            <p class="card-text">Nuestro equipo te asesora antes, durante y después de tu compra. ¡Siempre estamos para ayudarte!</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow border-0 text-center">
          <div class="card-body">
            <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
            <h5 class="card-title">Garantía y devolución</h5>
            <p class="card-text">Todos nuestros productos cuentan con garantía y un proceso de devolución sencillo y rápido.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow border-0 text-center">
          <div class="card-body">
            <i class="fas fa-shipping-fast fa-3x text-warning mb-3"></i>
            <h5 class="card-title">Envío rápido</h5>
            <p class="card-text">Recibe tus pedidos en 24-72h en toda España, con seguimiento y embalaje seguro.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Sección de equipo con animación -->
  <div class="container mb-5">
    <h2 class="text-center text-white mb-4">Nuestro equipo</h2>
    <div class="row justify-content-center g-4">
      <div class="col-md-4">
        <div class="card h-100 shadow border-0 text-center animate__animated animate__fadeInLeft">
          <img src="images/imagenesGlobales/miembro1.jpg" class="card-img-top" alt="Miembro del equipo 1" style="height:260px;object-fit:cover;">
          <div class="card-body">
            <h5 class="card-title">Manolo Bytes</h5>
            <p class="card-text">Especialista en atención al cliente y ventas. Es el primo lejano de Bill Gates, pero con más tapas y menos código limpio.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow border-0 text-center animate__animated animate__fadeInUp">
          <img src="images/imagenesGlobales/miembro2.jpg" class="card-img-top" alt="Miembro del equipo 2" style="height:260px;object-fit:cover;">
          <div class="card-body">
            <h5 class="card-title">Mariló del Router</h5>
            <p class="card-text">Responsable de logística y envíos. Siempre conectada, aunque sea al wifi del vecino...</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow border-0 text-center animate__animated animate__fadeInRight">
          <img src="images/imagenesGlobales/miembro3.jpg" class="card-img-top" alt="Miembro del equipo 3" style="height:260px;object-fit:cover;">
          <div class="card-body">
            <h5 class="card-title">Puri Variables</h5>
            <p class="card-text">Experta en tecnología y productos. Cambia de opinión más rápido que un let en JavaScript..</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Sección de historia animada -->
  <div class="container mb-5">
    <div class="row align-items-center">
      <div class="col-lg-6 animate__animated animate__fadeInLeft">
        <img src="images/imagenesGlobales/nuestrahistoria.jpg" class="img-fluid rounded shadow" alt="Nuestra historia">
      </div>
      <div class="col-lg-6 animate__animated animate__fadeInRight">
        <h2 class="text-white">Nuestra historia</h2>
        <p class="lead text-on-white">La Tendeta Online nació en 2025... ¡de un lío monumental!
        Todo empezó con un Proyecto Final de Grado en 2º de DAW. Sí, ese momento épico en el que tienes que elegir algo, pero no sabes ni por dónde te da el aire. Más perdidos que un WiFi en el monte.
        Por suerte, entre cafés, desesperación y miradas de auxilio, aparecieron Ximo, Toni, Vicent y Lluís, cual oráculo digital, a decirnos: “Haced esto… o eso… pero haced algo, por Dios”.</p>
        <p class="text-on-white">Y así nació La Tendeta: un proyecto que empezó con más dudas que líneas de código mal indentadas, y acabó siendo una tienda online con tecnología, confort y mucho salero que reparte felicidad por toda España.
        Hoy seguimos currando como el primer día (pero con menos errores de compilación), para que tú tengas la mejor experiencia de compra sin salir del sofá.</p>
      </div>
    </div>
  </div>


  <?php include 'footer.html.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
