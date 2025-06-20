// mensajes.js - JS para el modal de eliminación de mensajes en mensajes.php

document.addEventListener('DOMContentLoaded', function() {
  var eliminarModal = document.getElementById('eliminarModal');
  if (eliminarModal) {
    eliminarModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var id = button.getAttribute('data-id');
      document.getElementById('idMensajeEliminar').value = id;
    });
  }
});
