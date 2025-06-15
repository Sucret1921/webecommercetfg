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
