<?php
/**
 * *** Inicializa las session y comprueba que el usuario est� logeado ***
 */
if (!session_id()){
  session_start();
}

if (!isset($_SESSION["usuario_id"])) {
    require("noacceso.html");
    exit;
}
 
?>
