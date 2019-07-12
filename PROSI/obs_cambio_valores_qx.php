<?php
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");


$self = $_SERVER["PHP_SELF"];

procesar_entrada("GET", "transaccion", "justificacion", "action");
$transaccion = get_value($_GET["transaccion"], "C");
$justificacion = get_value($_GET["justificacion"], "C");
$action = get_value($_GET["action"], "C");

$justificacion = $justificacion." "."(PROSI)";
open_database();

switch ($action) {

  case "grabar":
  
  	$sql_delete_just = "DELETE FROM prosi.tmp_cambio_valores WHERE transaccion = '" . $transaccion . "'";
	execute_query($dbh, $sql_delete_just);
  	
  	$inserta_justificacion = "INSERT INTO prosi.tmp_cambio_valores(transaccion"
				 		 		. ",justificacion"
						 		. ") VALUES ("
		 						. "" . $transaccion . ""
						 		. ",'" . $justificacion
						 		. "')";
								
		 
		 
		if (execute_query($dbh, $inserta_justificacion)){
			echo "<script language='Javascript'> self.close(); </script>";
			exit;
		} else {
            $mensaje = "ERROR: No se pudo ejecutar la consulta.";
			
		}
  
  break;
  
  default:

$consulta_justificacion = "SELECT a.transaccion, a.justificacion
 				FROM   prosi.tmp_cambio_valores a
 				WHERE  a.transaccion = $transaccion";
    			$resultado_justificacion = execute_query($dbh, $consulta_justificacion);
       			$row_just = pg_fetch_row($resultado_justificacion);
       			$justificacion = $row_just[1];
				free_result($resultado_justificacion); 
				
				$self = $self . "?transaccion=" . $transaccion;
				$next_action = "grabar";
				
 }				
?>				
				
<html>
<head>
<title>Observacion</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">

</head>

<body background="imagenes/cellpic1.gif">
<link href="Estilos.css" rel="stylesheet" type="text/css">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<form name="justificacion" action="<?=$self?>" method="GET">
<?php
echo"
<input type=hidden name=transaccion value=$transaccion>";
?>
<table width="100%" border="0" cellspacing="0">

     <TR>
	 <td width="10%"><div align="left"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Observacion</font></strong></div></td>  
           <td width="89%" background="imagenes/cellpic1.gif" bgcolor="#EEEEEE">
             <textarea name="justificacion" cols="50" rows="5" id="justificacion"><?php echo $justificacion?></textarea>
           </td>
      <td width="1%"></td>     
     </TR>
     <TR>
     <td width="1%"></td>
   <td width="100%"><input name="aceptar" type="submit" id="aceptar" value="Aceptar" onClick="javascript: document.getElementById('action').value = '<?=$next_action?>';">
    <input type="button" name="Submit2" value="Cancelar" onClick="javascript: self.close();"></td>
   </TR>
   </TR>
</TABLE>
<input type="hidden" name="action" id="action" value="<?=$next_action ?>">
</FORM>
</BODY>
</HTML>



