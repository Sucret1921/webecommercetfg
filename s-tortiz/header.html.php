<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($num_cart)) {
    $num_cart = isset($_SESSION['carrito']['productos']) ? count($_SESSION['carrito']['productos']) : 0;
}
require_once __DIR__ . '/includes/es_admin.php';
$is_admin = isset($_SESSION['usuario']) ? es_admin($_SESSION['usuario']) : false;
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tenda online</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="css/productoestilo.css" rel="stylesheet">
  <link rel="stylesheet" href="css/header.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="images/ImagenesGlobales/logoprincipal.png">
</head>

<body>


  <header>
    <div class="bg-primary text-white text-center py-1 small fw-semibold position-relative overflow-hidden"
      style="height:2.2em;">
      <div class="marquee-content">
        🏖️ ¡Rebajas de verano! Hasta un 50% de descuento en productos seleccionados. ¡Aprovecha ahora! 🏄‍♂️🌞
      </div>
    </div>

  
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
      <symbol id="aperture" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
        stroke-width="2" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" />
        <path
          d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94" />
      </symbol>
      <symbol id="cart" viewBox="0 0 16 16">
        <path
          d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
      </symbol>
      <symbol id="chevron-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd"
          d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
      </symbol>
    </svg>

    
    
    <nav class="navbar navbar-expand-md bg-dark sticky-top border-bottom" data-bs-theme="dark">
      <div class="container">
        
        <a class="navbar-brand d-md-none" href="index.php"> La tendenta </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas"
          aria-controls="offcanvas" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasLabel">La tendeta</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>

          

          <div class="offcanvas-body d-flex align-items-center justify-content-between flex-wrap">
            <a href="index.php" class="d-flex align-items-center me-4">
              <img src="images/ImagenesGlobales/logoprincipal.png" alt="Logo Tenda" style="height:120px;width:auto;" class="rounded ms-0 align-self-start">
            </a>
            <ul class="navbar-nav flex-row align-items-center gap-3 mb-0">
              <li class="nav-item"><a class="nav-link active text-white" href="index.php">Catálogo</a></li>
              <li class="nav-item"><a class="nav-link text-white" href="sobre_nosotros.php">Sobre Nosotros</a></li>
              <li class="nav-item"><a class="nav-link text-white" href="contacto.php">Contacto</a></li>
              <li class="nav-item"><a class="nav-link text-white" href="faq.php">FAQ</a></li>
              <li class="nav-item"><a class="nav-link text-white" href="checkout.php">
                <span id="num_cart" class="badge bg-primary"> <?php echo $num_cart ?></span>
                <svg class="bi" width="24" height="24" style="color:#fff;fill:#fff;"><use xlink:href="#cart" /></svg>
              </a></li>
            </ul>
            <?php if (isset($_SESSION['usuario'])): ?>
              <div class="navbar-text text-white ms-3">
                <div class="dropdown">
                  <button class="btn btn-outline-primary dropdown-toggle user-welcome-btn" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="text-white user-welcome-text"><i class="fas fa-user-circle me-2"></i>Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong></span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <?php if ($is_admin): ?>
                      <li><a class="dropdown-item text-info" href="sh-admin/inicio.php"><i class="fas fa-toolbox me-2"></i>Panel administrador</a></li>
                      <li><hr class="dropdown-divider"></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a></li>
                  </ul>
                </div>
              </div>
            <?php else: ?>
              <a href="login.php" class="btn btn-outline-light ms-3"><i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión</a>
            <?php endif; ?>
            
          </div>
        </div>
      </div>
    </nav>
  </header>