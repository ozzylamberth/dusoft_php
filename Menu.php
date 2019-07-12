<?php
// menu.php  05/08/2002
// ----------------------------------------------------------------------

// Copyright (C) 2002 Alexander Giraldo
// Emai: alexgiraldo777@yahoo.com

// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: Frame menu cuando StyleFrames=True
// ----------------------------------------------------------------------

$VISTA='HTML';
include 'includes/enviroment.inc.php';
include 'includes/modules.inc.php';

if(!empty($_REQUEST["MENU_SELECCION"]))
{
 SessionSetVar('MENU_SELECCION',$_REQUEST['MENU_SELECCION']);
}


PrintMenu();


?>
