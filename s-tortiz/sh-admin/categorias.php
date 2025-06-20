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
require 'clases/categoria.php';

$db = new Database();
$con = $db->conectar();
$categoria = new Categoria($con);

// Acciones: add, edit, delete
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $categoria->add($_POST['nombre'], $_POST['descripcion'], $_POST['icono'], $_POST['color']);
        $mensaje = 'Categoría añadida correctamente.';
    } elseif (isset($_POST['edit'])) {
        $categoria->update($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['icono'], $_POST['color']);
        $mensaje = 'Categoría actualizada correctamente.';
    } elseif (isset($_POST['delete'])) {
        $categoria->delete($_POST['id']);
        $mensaje = 'Categoría eliminada correctamente.';
    }
}
$categorias = $categoria->getAll();
?>
</div>
<div class="container mt-4">
    <h2> Gestión de Cateorías</h2>
    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    <form method="post" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3"><input type="text" name="nombre" class="form-control" placeholder="Nombre" required></div>
            <div class="col-md-3"><input type="text" name="descripcion" class="form-control" placeholder="Descripción"></div>
            <div class="col-md-2"><input type="text" name="icono" class="form-control" placeholder="Icono (fa-tag)"></div>
            <div class="col-md-2"><input type="color" name="color" class="form-control" value="#0d6efd"></div>
            <div class="col-md-2"><button type="submit" name="add" class="btn btn-primary w-100"><i class="fas fa-plus"></i> Añadir</button></div>
        </div>
    </form>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Icono</th>
                <th>Color</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $cat): ?>
            <tr>
                <form method="post">
                    <td><?php echo $cat['id']; ?><input type="hidden" name="id" value="<?php echo $cat['id']; ?>"></td>
                    <td><input type="text" name="nombre" value="<?php echo htmlspecialchars($cat['nombre']); ?>" class="form-control"></td>
                    <td><input type="text" name="descripcion" value="<?php echo htmlspecialchars($cat['descripcion']); ?>" class="form-control"></td>
                    <td><input type="text" name="icono" value="<?php echo htmlspecialchars($cat['icono']); ?>" class="form-control"></td>
                    <td><input type="color" name="color" value="<?php echo htmlspecialchars($cat['color']); ?>" class="form-control form-control-color"></td>
                    <td>
                        <button type="submit" name="edit" class="btn btn-success btn-sm"><i class="fas fa-save"></i></button>
                        <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar categoría?')"><i class="fas fa-trash"></i></button>
                    </td>
                </form>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>
