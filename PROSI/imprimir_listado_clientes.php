<?php
require("includes/config.php");
require("includes/database.php");
require("includes/funciones.php");
require('includes/printfclientes.php');

open_database();
$pdf = new informe_clientes("L");
$pdf->db = $dbh;
$pdf->cliecodigo = $_GET["cliecodigo"];
$pdf->clienombre = $_GET["clienombre"];
$pdf->clienomcom = $_GET["clienomcom"];
$pdf->telefono1 = $_GET["clietel1"];
$pdf->movil = $_GET["cliemovil"];
$pdf->desdefecha = $_GET["desdefecha"];
$pdf->hastafecha = $_GET["hastafecha"];
$pdf->orientation = $_GET["orientation"];
$pdf->order = $_GET["order"];
$pdf->Open();
$pdf->AddPage();
$pdf->Detalles();
$pdf->Output();

?> 


?> 
