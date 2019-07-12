<?php

if (!session_id()){
  session_start();
}


require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

?>
<html>

<head>
<title>RECEPCION DE CUENTAS SIIS</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body background="imagenes/fondo_bloque.gif">

<?php
open_database();
$self = $_SERVER["PHP_SELF"]; 
$usuario_recibe = $_SESSION["usuario_id"];
$administrador = $_SESSION["administrador"];


procesar_entrada("GET", "id", "action", "action1", "departamento_entrega", "departamento_recibe", "cod_menu");
procesar_entrada("POST", "cod_menu");

$departamento_entrega = get_value($_GET["departamento_entrega"], "C");
$departamento_recibe = get_value($_GET["departamento_recibe"], "C");
$action1 = get_value($_GET["action1"], "C");

	if($_REQUEST["cod_menu"]){
	$cod_menu = $_REQUEST["cod_menu"];
	}
	if($_POST["cod_menu"]){
	$cod_menu = get_value($_POST["cod_menu"], "N");
	}
	if($_GET["cod_menu"]){
	$cod_menu = get_value($_GET["cod_menu"], "N");
	}

$fecha_registro = date("Y-m-d H:i:s");
$sess = session_id();



switch ($action1) {

  case "recepcionar":
  
  $permiso_update = PerfilOpcionUsuario($usuario_recibe, $cod_menu, "update", $dbh);
				
				if($permiso_update == ""){
				$mensaje_insert = "<font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>ERROR: Usuario no tiene permiso para ejecutar esta accion.</strong></font>";
				echo $mensaje_insert;
				break;
				
				}
  foreach($_POST["insertarlinea"] as $idtmp) {
  		
	$linea = explode(":", $idtmp);
	
	
		$val_insert = execute_query($dbh, "SELECT a.*
	 	FROM   relacion_cuentas_detalle a
	 	WHERE  a.relacion_id=$linea[1]
	 	ORDER BY a.rel_det_id");
		while ($v_insert = fetch_object($val_insert)) {
	 
	 	}
	
	
	
	$estado = 'E';
	$sql_recepcion_cuentas = "UPDATE relacion_cuentas_detalle SET "
		 . "estado = '" . $estado
		 . "' WHERE rel_det_id = " . $linea[0] . "";
		 
		if (execute_query($dbh, $sql_recepcion_cuentas)){
		 $estado_relacion = 'E';
				$sql_update_cuenta = "UPDATE relacion_cuentas SET "
		 		. "departamento_recibe = '" . $departamento_recibe . "'"
		 		. ",usuario_recibe = " . $usuario_recibe . ""
		 		. ",estado = '" . $estado_relacion . "'"
		 		. ",fecha_recibe = '" . $fecha_registro
		 		. "' WHERE relacion_id = " . $linea[1] . "";
		 			
					if (execute_query($dbh, $sql_update_cuenta)){
					 		$val_insert = execute_query($dbh, "SELECT a.*
	 						FROM   relacion_cuentas_detalle a
	 						WHERE  a.relacion_id=$linea[1] AND a.estado = 'R'
	 						ORDER BY a.rel_det_id");
							while ($v_insert = fetch_object($val_insert)) {
	 							
	 									$estado_N = 'N';
										$sql1 = "UPDATE relacion_cuentas_detalle SET "
		 								. "estado = '" . $estado_N
		 								. "' WHERE rel_det_id = " . $v_insert->rel_det_id . "";
		 
										if (execute_query($dbh, $sql1)){
										
										} 
										else {
            							
										}
		
	 						}
						
					} 			
					else {
            			
					}
		 
		 
		} 
		else {
            
		}
  }
		$departamento_recibe = '';
		$departamento_entrega = '';
  break;
  

}



?>

<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<script language="JavaScript" src="calendar3.js"></script>

<link href="Estilos.css" rel="stylesheet" type="text/css">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form name="tstest1">
<form name="recepcion_cuentas" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<table align="CENTER%" width="100%" border="1" bordercolor="blue">
<tr>
<td><center><img src="imagenes/logo_clinica.bmp" align="CENTER%" width="250" height="150">  
	
	</td>
	</tr>
	<?php
	
      $permiso_select = PerfilOpcionUsuario($usuario_recibe, $cod_menu, "select", $dbh);
		
		if($permiso_select <> ""){	?>
<tr>
<td>
  <table align="CENTER%" width="100%" border="0" cellspacing="0">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="4" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">RECEPCION DE RELACION DE CUENTAS</font></strong></div></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Departamento Envia</font></td>
      <td bgcolor="#EEEEEE"><?php


print ("
<select name=\"departamento_entrega\"  onchange=\"submit();\">
");


print ("<option value=''>Seleccione opcion</option>");


$result_dpto_entrega = execute_query($dbh, "SELECT * FROM departamentos WHERE departamentos.departamento IN (SELECT departamento FROM departamentos_relacion_cuentas WHERE sw_relacion_cuenta = '1') ORDER BY 5");

while (($row = fetch_object($result_dpto_entrega))) {
print("<option value=\"$row->departamento\"  ");
if ($row->departamento == $departamento_entrega) {
print ("selected");
}

print(">$row->descripcion</option>\n
");
}
free_result($result_dpto_entrega);
print("</select>"); 
?>

</td>
    
     
<td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Departamento Recibe</font></td>
      <td bgcolor="#EEEEEE"><?php


print ("
<select name=\"departamento_recibe\"  onchange=\"submit();\">
");


print ("<option value=''>Seleccione opcion</option>");


$result_dpto_recibe = execute_query($dbh, "SELECT departamentos.departamento, departamentos.descripcion, system_usuarios.usuario_id
 FROM   (public.system_usuarios_departamentos system_usuarios_departamentos INNER JOIN public.departamentos departamentos ON system_usuarios_departamentos.departamento=departamentos.departamento) INNER JOIN public.system_usuarios system_usuarios ON system_usuarios_departamentos.usuario_id=system_usuarios.usuario_id
 WHERE  system_usuarios.usuario_id=$usuario_recibe AND departamentos.departamento IN (SELECT departamento FROM departamentos_relacion_cuentas WHERE sw_relacion_cuenta = '1') ORDER BY 2");

while (($row = fetch_object($result_dpto_recibe))) {
print("<option value=\"$row->departamento\"  ");
if ($row->departamento == $departamento_recibe) {
print ("selected");
}

print(">$row->descripcion</option>\n
");
}
free_result($result_dpto_recibe);
print("</select>"); 
?>

</td>
          
    </tr>
    <input type="hidden" name="cod_menu" value="<?php echo $cod_menu;?>">
    </form>
    </table>
    
    <?php 
	if($departamento_entrega <> '' AND $departamento_recibe <> ''){
     
     
     	
     	$result = pg_query($dbh, "SELECT a.relacion_id, a.fecha_registro, b.nombre
 		FROM   relacion_cuentas a, system_usuarios b
 		WHERE  a.departamento_entrega = '$departamento_entrega' AND a.departamento_recibe = '$departamento_recibe'
 		AND a.usuario_entrega = b.usuario_id
 		AND a.estado = 'R'
 		ORDER BY a.relacion_id");

		$rows = pg_num_rows($result);
     	
     	$relacion_maestro = execute_query($dbh, "SELECT a.relacion_id, a.fecha_registro, b.nombre
 		FROM   relacion_cuentas a, system_usuarios b
 		WHERE  a.departamento_entrega = '$departamento_entrega' AND a.departamento_recibe = '$departamento_recibe'
 		AND a.usuario_entrega = b.usuario_id
 		AND a.estado = 'R'
 		ORDER BY a.relacion_id");
		while ($row_m = fetch_object($relacion_maestro)) {
	 
	 ?>
	 
    <table align="CENTER%" width="100%" border="0" cellspacing="0">
    
	
	
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="5" bgcolor="#EEEEEE"><div align="left"><strong><font color="#003366" size="1">RELACION:<?php echo $row_m->relacion_id;?> FECHA:<?php echo $row_m->fecha_registro;?> USUARIO:<?php echo $row_m->nombre;?></font></strong></div></td>
      <td background="imagenes/cellpic1.gif" bgcolor="#EEEEEE"><div align="center">
      <input type="checkbox" name="head" onClick="disable(this)">
      </div></td>
    </tr>
    
    

    
    <tr> 
    <td width="8%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Cuenta</font></strong></a> 
   </td>
    <td width="8%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Factura Fiscal</font></strong></a> 
   </td>
    <td width="12%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Valor</font></strong></a> 
   </td>
    <td width="33%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Paciente</font></strong></a> 
   </td>
    <td width="33%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Plan</font></strong></a> 
   </td>
    <td width="6%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Opcion</font></strong></a> 
   </td>
 </tr>
 <?php
    	$relacion_detalle = execute_query($dbh, "SELECT cuentas.numerodecuenta, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, planes.plan_descripcion, relacion_cuentas_detalle.relacion_id, relacion_cuentas_detalle.prefijo, relacion_cuentas_detalle.factura_fiscal, relacion_cuentas_detalle.total_cuenta, relacion_cuentas_detalle.rel_det_id
 FROM   (((public.relacion_cuentas_detalle relacion_cuentas_detalle INNER JOIN public.cuentas cuentas ON relacion_cuentas_detalle.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
WHERE  relacion_cuentas_detalle.relacion_id=$row_m->relacion_id
ORDER BY relacion_cuentas_detalle.rel_det_id");
		$total_relacion = 0;
		while ($row_d = fetch_object($relacion_detalle)) {
		$total_relacion = $total_relacion +  $row_d->total_cuenta;
		 ?>
 <tr bgcolor="#EEEEEE"> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "black"><?php echo $row_d->numerodecuenta?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "black"><?php echo $row_d->prefijo." ".$row_d->factura_fiscal?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "black"><?php echo $row_d->total_cuenta?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "black"><?php echo $row_d->tipo_id_paciente." ".$row_d->paciente_id." ".$row_d->primer_nombre." ".$row_d->segundo_nombre." ".$row_d->primer_apellido. " ".$row_d->segundo_apellido?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "black"><?php echo $row_d->plan_descripcion?></font></td>
    <td><div align="center">
    	<?php echo "<form method=\"POST\" action=\"recepciones.php?action1=recepcionar&amp;departamento_entrega=$departamento_entrega&amp;departamento_recibe=$departamento_recibe&amp;cod_menu=$cod_menu\">";?>
      <input type="checkbox" name="insertarlinea[]" value="<?php echo $row_d->rel_det_id.":".$row_d->relacion_id;?>">
      </div></td>
    </tr>
	
    
 <?php
 }



?>
<tr bgcolor="#EEEEEE"> 
    <td colspan="2"><div align="center"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "black"><u>Total Relacion:</u></font></strong></div></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "black"><strong>$<?php echo number_format ($total_relacion)?></strong></font></td>
	<td colspan="3"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "black"></font></strong></td>
	</tr>
 </table>   
    
    <?php
    }
     
		?>

</td>
</tr>
<?php 
if($rows > 0){
echo 	"<input type=\"hidden\" name=\"departamento_entrega\" value=\"$departamento_entrega\">";
		"<input type=\"hidden\" name=\"departamento_recibe\" value=\"$departamento_recibe\">";
		"<input type=\"hidden\" name=\"cod_menu\" value=\"$cod_menu\">";
 		echo "<tr bgcolor=#EEEEEE>
 		 
		 <td><div align=right>
		 <input type=\"submit\" name=\"Submit\" value=\"Recepcionar\" >
        </form></div>
		</td>
		</tr>";
}
else{
	
}		
}
?>        
<?php 
		}
	else{
			require("noacceso.html");
    		exit;
		}
		?>
</body>
</html>
