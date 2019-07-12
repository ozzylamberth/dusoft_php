
function listarDocumento(){
    var desde = document.getElementById('FechaI').value; 
    var hasta = document.getElementById('FechaF').value; 
    xajax_listarDocumento(desde,hasta); 
}