// Modal eliminar compra
var eliminarCompraModal = document.getElementById('eliminarCompraModal');
if (eliminarCompraModal) {
  eliminarCompraModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-id');
    var transaccion = button.getAttribute('data-transaccion');
    document.getElementById('idCompraEliminar').value = id;
    document.getElementById('transaccionCompraEliminar').textContent = transaccion;
  });
}
// DataTable en español
if(document.getElementById('comprasTable')) {
    new simpleDatatables.DataTable('#comprasTable', {
        labels: {
            placeholder: "Buscar por fecha (ej: 15/06/2025)...",
            perPage: "Registros por página",
            noRows: "No hay compras para mostrar",
            info: "Mostrando {start} a {end} de {rows} compras"
        }
    });
}
