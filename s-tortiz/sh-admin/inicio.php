<?php
// Protección de acceso: debe ir antes de cualquier salida o include
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Administrador') {
    header('Location: index.php');
    exit;
}

include 'header.php'; 
require '../includes/config.php';
require '../includes/basededatos.php';

$db = new Database();
$con = $db->conectar();

// Consultas de datos
$totalProductos = $con->query('SELECT COUNT(*) FROM productos')->fetchColumn();
$totalUsuarios = $con->query('SELECT COUNT(*) FROM clientes')->fetchColumn();
$usuariosActivos = $con->query('SELECT COUNT(*) FROM clientes WHERE estatus=1')->fetchColumn();
$productosActivos = $con->query('SELECT COUNT(*) FROM productos WHERE activo=1')->fetchColumn();
$totalCompras = $con->query('SELECT COUNT(*) FROM compra')->fetchColumn();
$totalVentas = $con->query('SELECT IFNULL(SUM(total),0) FROM compra')->fetchColumn();
$ultimosUsuarios = $con->query('SELECT nombres, apellidos, email, telefono FROM clientes ORDER BY id DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);
$ultimosProductos = $con->query('SELECT nombre, precio FROM productos ORDER BY id DESC LIMIT 3')->fetchAll(PDO::FETCH_ASSOC);
// Ventas de la semana
$ventasSemana = $con->prepare('SELECT id_transaccion, fecha, total, status, email FROM compra WHERE fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY) ORDER BY fecha DESC');
$ventasSemana->execute();
$ventasSemana = $ventasSemana->fetchAll(PDO::FETCH_ASSOC);


?>
    </div>   
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1 class="mt-4 dashboard-anim-marquee"><span class="dashboard-anim">✨ S-LectorTiz V.1.9 🚀</span></h1>
                <ol class="breadcrumb mb-4">
                </ol>
                <div class="row mb-4">
                    <div class="col-md-6 col-xl-4">
                        <div class="card border-primary shadow mb-4">
                            <div class="card-body text-center">
                                <i class="fas fa-box fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Gestión de Productos</h5>
                                <p class="card-text">Añade, edita o elimina productos del catálogo.</p>
                                <a href="productos.php" class="btn btn-outline-primary btn-sm">Ir a productos</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card border-info shadow mb-4">
                            <div class="card-body text-center">
                                <i class="fas fa-images fa-3x text-info mb-3"></i>
                                <h5 class="card-title">Gestión de Fotos</h5>
                                <p class="card-text">Gestiona las imágenes principales y del carrusel de los productos.</p>
                                <a href="subir_fotos.php" class="btn btn-outline-info btn-sm"><i class="fas fa-camera"></i> Ir a fotos</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card border-success shadow mb-4">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Gestión de Usuarios</h5>
                                <p class="card-text">Da de alta o baja a los usuarios registrados.</p>
                                <a href="usuarios.php" class="btn btn-outline-success btn-sm">Ir a usuarios</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card border-warning shadow mb-4">
                            <div class="card-body text-center">
                                <i class="fas fa-shopping-cart fa-3x text-warning mb-3"></i>
                                <h5 class="card-title">Gestión de Compras</h5>
                                <p class="card-text">Consulta, filtra y gestiona todas las compras realizadas.</p>
                                <a href="compras.php" class="btn btn-outline-warning btn-sm">Ir a compras</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card border-secondary shadow mb-4">
                            <div class="card-body text-center">
                                <i class="fas fa-envelope fa-3x text-secondary mb-3"></i>
                                <h5 class="card-title">Gestión de Mensajes</h5>
                                <p class="card-text">Gestiona los mensajes recibidos desde el formulario de contacto.</p>
                                <a href="mensajes.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-envelope-open-text"></i> Ir a mensajes</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card border-info shadow mb-4">
                            <div class="card-body text-center">
                                <i class="fas fa-tags fa-3x text-info mb-3"></i>
                                <h5 class="card-title">Gestión de Categorías</h5>
                                <p class="card-text">Crea, edita o elimina categorías para los productos.</p>
                                <a href="categorias.php" class="btn btn-outline-info btn-sm"><i class="fas fa-tags"></i> Ir a categorías</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="card border-success shadow mb-4">
                            <div class="card-body text-center">
                                <i class="fas fa-user-check fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Validar Usuarios</h5>
                                <p class="card-text">Valida usuarios pendientes de activación en la plataforma.</p>
                                <a href="validar_usuarios.php" class="btn btn-outline-success btn-sm"><i class="fas fa-user-check"></i> Validar usuarios</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fs-2 fw-bold"><?php echo $totalProductos; ?></span>
                                    <span>Total Productos</span>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="productos.php">Ver productos</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fs-2 fw-bold"><?php echo $productosActivos; ?></span>
                                    <span>Productos Activos</span>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="productos.php">Ver activos</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-warning text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fs-2 fw-bold"><?php echo $totalUsuarios; ?></span>
                                    <span>Total Usuarios</span>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="usuarios.php">Ver usuarios</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-danger text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fs-2 fw-bold"><?php echo $usuariosActivos; ?></span>
                                    <span>Usuarios Activos</span>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="usuarios.php">Ver activos</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-info text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fs-2 fw-bold"><?php echo $totalCompras; ?></span>
                                    <span>Total Compras</span>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <span class="small text-white">&nbsp;</span>
                                <div class="small text-white"><i class="fas fa-shopping-cart"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-secondary text-white mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fs-2 fw-bold">€<?php echo number_format($totalVentas,2,',','.'); ?></span>
                                    <span>Total Ventas</span>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <span class="small text-white">&nbsp;</span>
                                <div class="small text-white"><i class="fas fa-euro-sign"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fw-bold">Últimos usuarios</span>
                                    <ul class="list-unstyled mt-2 mb-0">
                                        <?php foreach($ultimosUsuarios as $u) {
                                            echo '<li><i class="fas fa-user me-1"></i> '.htmlspecialchars($u['nombres'].' '.$u['apellidos']).' <span class="text-muted">('.htmlspecialchars($u['email']).')</span></li>';
                                        } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="fw-bold">Últimos productos</span>
                                    <ul class="list-unstyled mt-2 mb-0">
                                        <?php foreach($ultimosProductos as $p) {
                                            echo '<li><i class="fas fa-box me-1"></i> '.htmlspecialchars($p['nombre']).' <span class="text-muted">('.MONEDA.number_format($p['precio'],2,',','.').')</span></li>';
                                        } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DataTable de últimos usuarios -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Últimos usuarios registrados
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($ultimosUsuarios as $u) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($u['nombres']); ?></td>
                                    <td><?php echo htmlspecialchars($u['apellidos']); ?></td>
                                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                                    <td><?php echo htmlspecialchars($u['telefono']); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- DataTable de ventas de la semana -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Ventas de la semana
                    </div>
                    <div class="card-body">
                        <table id="ventasSemanaTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Transacción</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($ventasSemana as $v) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($v['id_transaccion']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($v['fecha'])); ?></td>
                                    <td><?php echo MONEDA . number_format($v['total'], 2, '.', ','); ?></td>
                                    <td><?php echo htmlspecialchars($v['status']); ?></td>
                                    <td><?php echo htmlspecialchars($v['email']); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card border-info shadow mb-4">
                            <div class="card-body">
                                <h4 class="mb-3"><i class="fas fa-percent"></i> Panel de Descuentos <span class="badge bg-warning text-dark ms-2"><i class="fas fa-sun"></i> Rebajas de verano</span></h4>
                                <form method="post" action="descuentos.php" class="d-inline">
                                    <button name="descuento_masivo" value="50" class="btn btn-danger mb-2"><i class="fas fa-fire"></i> Rebajar todo al 50%</button>
                                </form>
                                <form method="post" action="descuentos.php" class="d-inline ms-2">
                                    <button name="descuento_masivo" value="25" class="btn btn-warning mb-2"><i class="fas fa-bolt"></i> Rebajar todo al 25%</button>
                                </form>
                                <form method="post" action="descuentos.php" class="d-inline ms-2">
                                    <button name="descuento_masivo" value="10" class="btn btn-info mb-2"><i class="fas fa-tag"></i> Rebajar todo al 10%</button>
                                </form>
                                <form method="post" action="descuentos.php" class="d-inline ms-2">
                                    <button name="descuento_masivo" value="5" class="btn btn-success mb-2"><i class="fas fa-leaf"></i> Rebajar todo al 5%</button>
                                </form>
                                <a href="descuentos.php" class="btn btn-outline-info ms-3 mb-2"><i class="fas fa-cogs"></i> Gestión avanzada de descuentos</a>
                                <div class="mt-3">
                                    <span class="text-muted"><i class="fas fa-info-circle"></i> Accede a la gestión avanzada para modificar descuentos individuales.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>
</div>

<!-- DataTables scripts (SB Admin) -->
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2" crossorigin="anonymous"></script>
<script src="js/inicio.js"></script>
<link rel="stylesheet" href="css/dashboard-animacion.css">
