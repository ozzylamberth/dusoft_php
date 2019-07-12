<?php
class app_DescargaVentaPublico_controller extends classModulo {

    function app_DescargaVentaPublico_controller() {
        return true;
    }

    function main() {
        $this->IncludeJS('classes');
	$this->IncludeJS('classes/script.js', $contenedor='app', $modulo='DescargaVentaPublico');        
        $this->SetXajax(array("listarDocumento","DescargaVentaPublico"), "app_modules/DescargaVentaPublico/RemoteXajax/Funciones.php");
        $objHtml = AutoCarga::factory("Agregar_Actual_HTML", "views", "app", "DescargaVentaPublico");
        $salida .= $objHtml->listarDocumentos();
        $this->salida = $salida;
        return true;
    }
}
?>