// subir_fotos.js - JS para la gestión de imágenes de productos en subir_fotos.php

document.addEventListener('DOMContentLoaded', function() {
    var eliminarImgModal = document.getElementById('eliminarImgModal');
    var eliminarBtn = document.getElementById('confirmDeleteBtn');
    var img = '', pid = '';
    if (eliminarImgModal && eliminarBtn) {
        eliminarImgModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            img = button.getAttribute('data-img');
            pid = button.getAttribute('data-pid');
        });
        eliminarBtn.addEventListener('click', function() {
            var url = new URL(window.location.href);
            url.searchParams.set('eliminar_img', img);
            url.searchParams.set('pid', pid);
            window.location.href = url.toString();
        });
    }
});
