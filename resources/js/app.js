import './bootstrap';
import $ from 'jquery';
window.$ = window.jQuery = $;

$(document).ready(function(){

    // Prevenir submit del form y usar AJAX
    $('#filtros-brigadistas').on('submit', function(e){
        e.preventDefault();
        filtrarAsignaciones();
    });

    function filtrarAsignaciones() {
        $.ajax({
            url: "{{ route('admin.asignaciones.filtrar') }}",
            type: "GET",
            data: $('#filtros-brigadistas').serialize(),
            success: function(data){
                $('#body-asignaciones').html(data);
            }
        });
    }

    // Botón Limpiar
    $('#limpiar-filtros').click(function(){
        $('#filtros-brigadistas')[0].reset();
        filtrarAsignaciones();
    });

});
