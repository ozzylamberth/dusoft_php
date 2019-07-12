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
<title>DESPACHO DE HISTORIAS CLINICAS</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body background="imagenes/fondo_bloque.gif">

<?php
open_database();
$self = $_SERVER["PHP_SELF"]; 
$usuario_despacha = $_SESSION["usuario_id"];
$administrador = $_SESSION["administrador"];


procesar_entrada("GET", "id", "action", "action1", "departamento_solicita", "departamento_despacha", "cod_menu");


$departamento_solicita = get_value($_GET["departamento_solicita"], "C");
$departamento_despacha = get_value($_GET["departamento_despacha"], "C");
$action1 = get_value($_GET["action1"], "C");

if($_REQUEST["cod_menu"]){
	$cod_menu = $_REQUEST["cod_menu"];
	}
	if($_POST["cod_menu"]){
	$cod_menu = get_value($_POST["cod_menu"]);
	}
	if($_GET["cod_menu"]){
	$cod_menu = get_value($_GET["cod_menu"], "N");
	}

$fecha_registro = date("Y-m-d H:i:s");
$sess = session_id();



switch ($action1) {

  case "despachar":
  
  $permiso_update = PerfilOpcionUsuario($usuario_recibe, $cod_menu, "update", $dbh);
				
				if($permiso_update == ""){
				$mensaje_insert = "<font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>ERROR: Usuario no tiene permiso para ejecutar esta accion.</strong></font>";
				echo $mensaje_insert;
				break;
				
				}
  foreach($_POST["insertarlinea"] as $idtmp) {
  		
	$linea = explode(":", $idtmp);
	
	
	
		$val_insert = execute_query($dbh, "SELECT a.*
	 	FROM   relacion_hc_detalle a
	 	WHERE  a.relacion_hcid=$linea[1]
	 	ORDER BY a.rel_hcdet_id");
		while ($v_insert = fetch_object($val_insert)) {
	 
	 	}
	
	
	
	$estado = '2';
	$sql_despacho_hc = "UPDATE relacion_hc_detalle SET "
		 . "estado = '" . $estado
		 . "' WHERE rel_hcdet_id = " . $linea[0] . "";
		 
		if (execute_query($dbh, $sql_despacho_hc)){
		 $estado_relacion = '2';
				$sql_update_hc = "UPDATE relacion_hc SET "
		 		. "usuario_despacha = " . $usuario_despacha . ""
		 		. ",estado = '" . $estado_relacion . "'"
		 		. ",fecha_despacho = '" . $fecha_registro
		 		. "' WHERE relacion_hcid = " . $linea[1] . "";
		 			
					if (execute_query($dbh, $sql_update_hc)){
					 		$val_insert = execute_query($dbh, "SELECT a.*
	 						FROM   relacion_hc_detalle a
	 						WHERE  a.relacion_hcid=$linea[1] AND a.estado = '1'
	 						ORDER BY a.rel_hcdet_id");
							while ($v_insert = fetch_object($val_insert)) {
	 							
	 									$estado_ND = '3';
										$sql1 = "UPDATE relacion_hc_detalle SET "
		 								. "estado = '" . $estado_ND
		 								. "' WHERE rel_hcdet_id = " . $v_insert->rel_hcdet_id . "";
		 
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
		$departamento_despacha = '';
		$departamento_solicita = '';
  break;
  

}



?>

<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<script language="JavaScript" src="calendar3.js"></script>

<link href="Estilos.css" rel="stylesheet" type="text/css">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form name="tstest1">
<form name="despacho_hc" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
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
      <td  background="imagenes/cellpic1.gif" colspan="7" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">DESPACHO DE HISTORIAS CLINICAS</font></strong></div></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Departamento Solicita</font></td>
      <td bgcolor="#EEEEEE"><?php


print ("
<select name=\"departamento_solicita\"  onchange=\"submit();\">
");


print ("<option value=''>Seleccione opcion</option>");


$result_dpto_solicita = execute_query($dbh, "SELECT * FROM departamentos WHERE departamentos.departamento IN (SELECT departamento FROM departamentos_relacion_cuentas WHERE sw_relacion_hc = '1') ORDER BY 5");

while (($row = fetch_object($result_dpto_solicita))) {
print("<option value=\"$row->departamento\"  ");
if ($row->departamento == $departamento_solicita) {
print ("selected");
}

print(">$row->descripcion</option>\n
");
}
free_result($result_dpto_solicita);
print("</select>"); 
?>

</td>
    
     
<td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Departamento Despacha</font></td>
      <td bgcolor="#EEEEEE"><?php


print ("
<select name=\"departamento_despacha\"  onchange=\"submit();\">
");


print ("<option value=''>Seleccione opcion</option>");


$result_dpto_despacha = execute_query($dbh, "SELECT departamentos.departamento, departamentos.descripcion, system_usuarios.usuario_id
 FROM   (public.system_usuarios_departamentos system_usuarios_departamentos INNER JOIN public.departamentos departamentos ON system_usuarios_departamentos.departamento=departamentos.departamento) INNER JOIN public.system_usuarios system_usuarios ON system_usuarios_departamentos.usuario_id=system_usuarios.usuario_id
 WHERE  system_usuarios.usuario_id=$usuario_despacha AND departamentos.departamento IN (SELECT departamento FROM departamentos_relacion_cuentas WHERE sw_relacion_hc = '1') ORDER BY 2");

while (($row = fetch_object($result_dpto_despacha))) {
print("<option value=\"$row->departamento\"  ");
if ($row->departamento == $departamento_despacha) {
print ("selected");
}

print(">$row->descripcion</option>\n
");
}
free_result($result_dpto_despacha);
print("</select>"); 
?>

</td>
          
    </tr>
    <input type="hidden" name="cod_menu" value="<?php echo $cod_menu;?>">
    </form>
    </table>
    
    <?php 
	if($departamento_solicita <> '' AND $departamento_despacha <> ''){
     
     
     	
     	$result = pg_query($dbh, "SELECT a.relacion_hcid, a.fecha_registro, b.nombre
 		FROM   relacion_hc a, system_usuarios b
 		WHERE  a.departamento_solicita = '$departamento_solicita' AND a.departamento_recibe = '$departamento_despacha'
 		AND a.usuario_solicita = b.usuario_id
 		AND a.estado = '1'
 		ORDER BY a.relacion_hcid");

		$rows = pg_num_rows($result);
     	
     	$relacion_maestro = execute_query($dbh, "SELECT a.relacion_hcid, a.fecha_registro, b.nombre, TO_CHAR(a.fecha_registro,'YYYY-MM-DD') as fecha_relacion
 		FROM   relacion_hc a, system_usuarios b
 		WHERE  a.departamento_solicita = '$departamento_solicita' AND a.departamento_recibe = '$departamento_despacha'
 		AND a.usuario_solicita = b.usuario_id
 		AND a.estado = '1'
 		ORDER BY a.relacion_hcid");
		while ($row_m = fetch_object($relacion_maestro)) {
	 
	 ?>
	 
    <table align="CENTER%" width="100%" border="0" cellspacing="0">
    
	
	
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="8" bgcolor="#EEEEEE"><div align="left"><strong><font color="#003366" size="1">RELACION:<?php echo $row_m->relacion_hcid;?> FECHA:<?php echo $row_m->fecha_registro;?> USUARIO:<?php echo $row_m->nombre;?></font></strong></div></td>
      
    </tr>
    
    

    
    <tr> 
	    <td width="12%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Identificacion</font></strong></a> 
	   </td>
	    <td width="30%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Nombre</font></strong></a> 
	   </td>
	    <td width="10%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Fec Ingreso</font></strong></a> 
	   </td>
	   <td width="18%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Departamento</font></strong></a> 
		</td>
	    <td width="10%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Ingreso</font></strong></a> 
		</td>
		<td width="10%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Dias Prestamo</font></strong></a>
		<td width="10%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Fec Entrega</font></strong></a> 
		</td>
		<td width="5%" background="imagenes/cellpic1.gif">
		</td>
	  </tr>
 <?php
    	$relacion_detalle = execute_query($dbh, "SELECT a.rel_hcdet_id,
    							a.relacion_hcid,
								a.tipo_id_paciente, 
								a.paciente_id,
								a.cant_dias,
								a.estado, 
								b.primer_nombre, 
								b.segundo_nombre, 
								b.primer_apellido, 
								b.segundo_apellido, 
								c.ingreso, 
								TO_CHAR(c.fecha_registro,'YYYY-MM-DD') as fecha_registro, 
								d.descripcion
							 FROM   ((relacion_hc_detalle a LEFT OUTER JOIN ingresos c ON a.ingreso=c.ingreso) 
								LEFT OUTER JOIN pacientes b ON (a.tipo_id_paciente=b.tipo_id_paciente) AND (a.paciente_id=b.paciente_id)) 
								LEFT OUTER JOIN departamentos d ON c.departamento=d.departamento
							WHERE a.relacion_hcid = $row_m->relacion_hcid
							ORDER BY a.rel_hcdet_id");
		
		while ($row_d = fetch_object($relacion_detalle)) {
		
		$fecha_entrega = suma_fechas($row_m->fecha_relacion,$row_d->cant_dias);
		
		if($row_d->ingreso == Null){
			$paciente = "Paciente sin Historia Clinica en SIIS";
			$fec_ingreso = "Desconocido";
			$departamento = "Desconocido";
			$ingreso = "Desconocido";
		}
		else{
			$paciente = $row_d->primer_nombre." ".$row_d->segundo_nombre." ".$row_d->primer_apellido." ".$row_d->segundo_apellido;
			$fec_ingreso = $row_d->fecha_registro;
			$departamento = $row_d->descripcion;
			$ingreso = $row_d->ingreso;
		}
		
		 ?>
 <tr bgcolor="#EEEEEE"> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row_d->tipo_id_paciente." ".$row_d->paciente_id?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $paciente?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $fec_ingreso?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $departamento?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $ingreso?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row_d->cant_dias?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $fecha_entrega?></font></td>
    <td><div align="center">
    	<?php echo "<form method=\"POST\" action=\"despachos_hc.php?action1=despachar&amp;departamento_solicita=$departamento_solicita&amp;departamento_despacha=$departamento_despacha&amp;cod_menu=$cod_menu\">";?>
      <input type="checkbox" name="insertarlinea[]" value="<?php echo $row_d->rel_hcdet_id.":".$row_d->relacion_hcid;?>">
      </div></td>
    </tr>
	
    
 <?php
 }



?>

 </table>   
    
    <?php
    }
     
		?>

</td>
</tr>
<?php 
if($rows > 0){
echo 	"<input type=\"hidden\" name=\"departamento_solicita\" value=\"$departamento_solicita\">";
		"<input type=\"hidden\" name=\"departamento_despacha\" value=\"$departamento_despacha\">";
		"<input type=\"hidden\" name=\"cod_menu\" value=\"$cod_menu\">";
 		echo "<tr bgcolor=#EEEEEE>
 		 
		 <td><div align=right>
		 <input type=\"submit\" name=\"Submit\" value=\"Despachar\" >
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
