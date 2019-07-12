<?php
// index.php  15/09/2002
// ----------------------------------------------------------------------

// Copyright (C) 2002 Alexander Giraldo
// Emai: alexgiraldo777@yahoo.com

// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: Pagina Inicial
// ----------------------------------------------------------------------


$VISTA='HTML';
$_ROOT = '';


// Cargar el entorno (Variables,Definiciones,BD,API,Session etc..)
include $_ROOT . 'includes/enviroment.inc.php';
// Levantar el entorno de StyleFrames
GetStyleFrames();
// Generar la pantalla o los frames
if(SessionGetVar('StyleFrames')){
    PrintIndexFrames();
}else{
  PrintIndexNoFrames();
}

if($ConfigAplication['ActivarDepuracionSQL'])
{
    list($dbconn) = GetDBconn();
    $dbconn->LogSQL(false);
}

?>
