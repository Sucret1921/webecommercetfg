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

// Modal editar usuario
var editarModal = document.getElementById('editarUsuarioModal');
if (editarModal) {
  editarModal.addEventListener('show.bs.modal', function (event) {
    var btn = event.relatedTarget;
    document.getElementById('editUsuarioId').value   = btn.getAttribute('data-id');
    document.getElementById('editNombres').value      = btn.getAttribute('data-nombres');
    document.getElementById('editApellidos').value    = btn.getAttribute('data-apellidos');
    document.getElementById('editDni').value          = btn.getAttribute('data-dni');
    document.getElementById('editEmail').value        = btn.getAttribute('data-email');
    document.getElementById('editTelefono').value     = btn.getAttribute('data-telefono');
    document.getElementById('editEstatus').checked    = btn.getAttribute('data-estatus') === '1';
  });
}
