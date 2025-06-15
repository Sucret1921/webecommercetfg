// scripts para DataTables en dashboard (inicio.php)
document.addEventListener('DOMContentLoaded', function() {
    if(document.getElementById('datatablesSimple')) {
        new simpleDatatables.DataTable('#datatablesSimple', {
            labels: {
                placeholder: "Buscar...",
                perPage: "Registros por página",
                noRows: "No hay datos para mostrar",
                info: "Mostrando {start} a {end} de {rows} registros"
            }
        });
    }
    if(document.getElementById('ventasSemanaTable')) {
        new simpleDatatables.DataTable('#ventasSemanaTable', {
            labels: {
                placeholder: "(ej: 15/06/2025)...",
                perPage: "Registros por página",
                noRows: "No hay ventas para mostrar",
                info: "Mostrando {start} a {end} de {rows} ventas"
            }
        });
    }
});
