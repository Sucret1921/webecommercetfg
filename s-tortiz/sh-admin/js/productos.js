// Modal eliminar producto
var eliminarModal = document.getElementById('eliminarModal');
if (eliminarModal) {
  eliminarModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-id');
    var nombre = button.getAttribute('data-nombre');
    document.getElementById('idProductoEliminar').value = id;
    document.getElementById('nombreProductoEliminar').textContent = nombre;
  });
}

// Modal editar producto
var editarModal = document.getElementById('editarProductoModal');
if (editarModal) {
  editarModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-id');
    var nombre = button.getAttribute('data-nombre');
    var descripcion = button.getAttribute('data-descripcion');
    var precio = button.getAttribute('data-precio');
    var activo = button.getAttribute('data-activo');
    // Asegura que los campos existen antes de asignar
    var idField = document.getElementById('editar-id');
    var nombreField = document.getElementById('editar-nombre');
    var descripcionField = document.getElementById('editar-descripcion');
    var precioField = document.getElementById('editar-precio');
    var activoField = document.getElementById('editar-activo');
    if (idField) idField.value = id;
    if (nombreField) nombreField.value = nombre;
    if (descripcionField) descripcionField.value = descripcion ? descripcion.replace(/<br\s*\/?/gi, "\n") : '';
    if (precioField) precioField.value = precio;
    if (activoField) activoField.checked = activo == '1';

    // Asignar categoría
    var categoriaId = button.getAttribute('data-categoria_id');
    var select = document.getElementById('editar-categoria');
    if (select && categoriaId) {
      select.value = categoriaId;
    } else if (select) {
      select.value = '';
    }
  });
}
