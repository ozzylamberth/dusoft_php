<?php
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");


$self = $_SERVER["PHP_SELF"];

procesar_entrada("GET", "rel_det_id", "observacion", "action");
$rel_hcdet_id = get_value($_GET["rel_hcdet_id"], "C");
$observacion = get_value($_GET["observacion"], "C");
$action = get_value($_GET["action"], "C");


open_database();

switch ($action) {

  case "grabar":
  	
  	$sql = "UPDATE tmp_relacion_hc_detalle SET "
		  . " observacion = '" . $observacion
		 . "' WHERE rel_hcdet_id = " . $rel_hcdet_id . "";
		 
		 
		if (execute_query($dbh, $sql)){
			$next_action = "2";
			$self = $self . "?rel_hcdet_id=" . $rel_hcdet_id;
			echo "<script language='Javascript'> self.close(); </script>";
			exit;
		} else {
            $mensaje = "ERROR: No se pudo ejecutar la consulta.";
			$next_action = $action;
		}
  
  break;
  
  default:

$consulta2 = "SELECT a.rel_hcdet_id, a.observacion
 				FROM   tmp_relacion_hc_detalle a
 				WHERE  a.rel_hcdet_id = $rel_hcdet_id";
    			$resultado2 = execute_query($dbh, $consulta2);
       			$row_c = pg_fetch_row($resultado2);
       			$rel_hcdet_id = $row_c[0];
       			$observacion = $row_c[1];
				free_result($resultado2); 
				
				$self = $self . "?rel_hcdet_id=" . $rel_hcdet_id;
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

<form name="obs_hc" action="<?=$self?>" method="GET" onSubmit="javascript: return validar();">
<?php
echo"
<input type=hidden name='rel_hcdet_id' value='$rel_hcdet_id'>";
?>
<table width="100%" border="0" cellspacing="0">

     <TR>
	 <td width="10%"><div align="left"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Observacion</font></strong></div></td>  
           <td width="89%" background="imagenes/cellpic1.gif" bgcolor="#EEEEEE">
             <textarea name="observacion" cols="50" rows="5" id="observacion"><?php echo $observacion?></textarea>
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



