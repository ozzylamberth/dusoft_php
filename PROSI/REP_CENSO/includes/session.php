<?php
/**
 * *** Inicializa las session y comprueba que el usuario está logeado ***
 */
session_start();

if (!isset($_SESSION["usuario_id"])) {
    require("noacceso.html");
    exit;
} 
?>
