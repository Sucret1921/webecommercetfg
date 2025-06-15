// Modal eliminar usuario
var eliminarUsuarioModal = document.getElementById('eliminarUsuarioModal');
if (eliminarUsuarioModal) {
  eliminarUsuarioModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-id');
    var nombre = button.getAttribute('data-nombre');
    document.getElementById('idUsuarioEliminar').value = id;
    document.getElementById('nombreUsuarioEliminar').textContent = nombre;
  });
}
