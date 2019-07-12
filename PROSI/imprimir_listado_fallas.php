<?php
require("includes/config.php");
require("includes/database.php");
require("includes/funciones.php");
require('includes/printffallas.php');

open_database();
$pdf = new informe_fallas("L");
$pdf->db = $dbh;
$pdf->registro_id = $_GET["registro_id"];
$pdf->tipo_falla_id = $_GET["tipo_falla_id"];
$pdf->departamento = $_GET["departamento"];
$pdf->estado = $_GET["estado"];
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
