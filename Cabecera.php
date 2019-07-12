<?php
// Cabecera.php  15/09/2002
// ----------------------------------------------------------------------

// Copyright (C) 2002 Alexander Giraldo
// Emai: alexgiraldo777@yahoo.com

// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: Pagina Inicial - Cabecera
// ----------------------------------------------------------------------
    $VISTA='HTML';
    include 'includes/enviroment.inc.php';

    PrintCabecera();
 
    if($ConfigAplication['ActivarDepuracionSQL'])
    {
        list($dbconn) = GetDBconn();
        $dbconn->LogSQL(false);
    }
?>
