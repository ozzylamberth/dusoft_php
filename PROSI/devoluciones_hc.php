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
<script>
function abrirpopup(nombre,ancho,alto) {
 Xpos=(screen.width/2)-300;
 Ypos=(screen.height/2)-200; 

dat = 'width=' + ancho + ',height=' + alto + ',left='+Xpos+',top = '+Ypos+',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,titlebar=no';
window.open(nombre,'',dat)

function Verificar()
{
if(document.buscarcuenta.numerodecuenta.value==""){alert("Por favor indica el Nombre de la Empresa");
document.buscarcuenta.numerodecuenta.focus();return false;}
}

}

function aumenta() {
  
  var hoy;
  var cant_dias; 
  var fecha_entrega;
  var ano;
  var mes;
  var dia;
  var nueva_fecha;
  hoy=new Date();
  cant_dias = document.getElementById("cant_dias[]").value;
  ano=hoy.getFullYear();
  mes=hoy.getMonth()+1;
  dia=hoy.getDate();
  
  // pasaremos la fecha a formato mm/dd/yyyy 
  //hoy=hoy.split('/'); 
  nueva_fecha=mes+'/'+dia+'/'+ano; 
  // 
  nueva_fecha=new Date(nueva_fecha); 
  nueva_fecha.setTime(nueva_fecha.getTime()+cant_dias*24*60*60*1000); 
   
  if(nueva_fecha.getMonth()+1<9) 
  mes='0'+nueva_fecha.getMonth()+1; 
  fecha_entrega=nueva_fecha.getDate()+'/'+mes+'/'+nueva_fecha.getFullYear(); 
  document.getElementById("fecha_entrega[]").value = fecha_entrega;   
} 

</script>
<title>DEVOLUCION DE HISTORIAS CLINICAS</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
-->
</style>
</head>

<body background="imagenes/fondo_bloque.gif">


<?php

	


open_database();
$self = $_SERVER["PHP_SELF"]; 
$usuario_id = $_SESSION["usuario_id"];
$administrador = $_SESSION["administrador"];


procesar_entrada("GET", "id", "action", "action1", "departamento_recibe", "departamento_devuelve", "usuario_devuelve", "ingreso","paciente_id","tipo_id_paciente","proceso", "cod_menu");

$departamento_recibe = get_value($_GET["departamento_recibe"], "C");
$departamento_devuelve = get_value($_GET["departamento_devuelve"], "C");
$usuario_devuelve = get_value($_GET["usuario_devuelve"], "N");
$ingreso = get_value($_GET["ingreso"], "N");
$tipo_id_paciente = get_value($_GET["tipo_id_paciente"], "C");
$paciente_id = get_value($_GET["paciente_id"], "C");
$cod_menu = get_value($_GET["cod_menu"], "C");
	


$action = get_value($_GET["action"], "C");
$action1 = get_value($_GET["action1"], "C");
$proceso = get_value($_GET["proceso"], "C");

$fecha_registro = date("Y-m-d H:i:s");

$sess = session_id();

switch ($action1) {
 
 case "search":
 				 $num_records = 0;
				 $busca_ingreso_paciente = "SELECT TO_CHAR(a.fecha_registro,'YYYY-MM-DD') as fecha_registro, a.ingreso, b.tipo_id_paciente, b.paciente_id, b.primer_nombre, b.segundo_nombre, b.primer_apellido, b.segundo_apellido, a.paciente_id, c.descripcion
 FROM   (ingresos a INNER JOIN pacientes b ON (a.paciente_id=b.paciente_id) AND (a.tipo_id_paciente=b.tipo_id_paciente)) INNER JOIN departamentos c ON a.departamento=c.departamento AND a.estado NOT IN ('5')";
 				$ingresos_paciente = "SELECT COUNT(*) AS numreg 
FROM   (ingresos a INNER JOIN pacientes b ON (a.paciente_id=b.paciente_id) AND (a.tipo_id_paciente=b.tipo_id_paciente)) INNER JOIN departamentos c ON a.departamento=c.departamento AND a.estado NOT IN ('5')";

$where = build_where(	"a.ingreso", $ingreso, "N",
    					"b.tipo_id_paciente", $tipo_id_paciente, "C",
    					"b.paciente_id", $paciente_id, "N");
    					
    	$order = "1";				
    					
    	if ($where) {
		    $busca_ingreso_paciente .= " WHERE " . $where;
		    $ingresos_paciente .= " WHERE " . $where;
		}				
    	$busca_ingreso_paciente .= " ORDER BY " . $order;
		$result = execute_query($dbh, $ingresos_paciente);
		$row = fetch_object($result);
		$num_records = $row->numreg;
		free_result($result);
		$result_ingreso = execute_query($dbh, $busca_ingreso_paciente);
		
		   if($num_records>0){
		    $j=0;
			while ($row_ingreso = fetch_object($result_ingreso)) {
			
			 $ingreso_encontrado .= "
			 <form method=\"POST\" action=\"relaciones_hc.php?action1=add&amp;departamento_recibe=$departamento_recibe&amp;departamento_devuelve=$departamento_devuelve&amp;proceso=$proceso&amp;cod_menu=$cod_menu\">
			  <tr bgcolor='#EEEEEE'>
			  	<td></td> 
			    <td><font size='1' face='Verdana, Arial, Helvetica, sans-serif' font color = '$color;'>$row_ingreso->tipo_id_paciente"." "."$row_ingreso->paciente_id</font></td>
			    <td><font size='1' face='Verdana, Arial, Helvetica, sans-serif' font color = '$color;'>$row_ingreso->primer_nombre"." "."$row_ingreso->segundo_nombre"." "."$row_ingreso->primer_apellido"." "."$row_ingreso->segundo_apellido</font></td>
			    <td><font size='1' face='Verdana, Arial, Helvetica, sans-serif' font color = '$color;'>$row_ingreso->fecha_registro</font></td>
			    <td><font size='1' face='Verdana, Arial, Helvetica, sans-serif' font color = '$color;'>$row_ingreso->descripcion</font></td>
			    <td><font size='1' face='Verdana, Arial, Helvetica, sans-serif' font color = '$color;'>$row_ingreso->ingreso</font></td>
			    			    
			  <td>
				<input type='checkbox' name='insertarlinea[]' value=$row_ingreso->tipo_id_paciente"."AFOZ"."$row_ingreso->paciente_id"."AFOZ"."$row_ingreso->ingreso>
			  </td>  
			    
				
			  </tr>";
			  $j++;
			  }
			}
			else{
				$ingreso_encontrado .= "
				<form method=\"POST\" action=\"relaciones_hc.php?action1=add&amp;departamento_recibe=$departamento_recibe&amp;departamento_devuelve=$departamento_devuelve&amp;proceso=$proceso&amp;cod_menu=$cod_menu\">
			  <tr bgcolor='#EEEEEE'>
			  <td></td>  
			    <td><font size='1' face='Verdana, Arial, Helvetica, sans-serif' font color = '$color;'>$tipo_id_paciente"." "."$paciente_id</font></td>
			    <td><font size='1' face='Verdana, Arial, Helvetica, sans-serif' font color = '$color;'>Paciente sin Historia Clinica en SIIS</font></td>
			    <td><font size='1' face='Verdana, Arial, Helvetica, sans-serif' font color = '$color;'>Desconocido</font></td>
			    <td><font size='1' face='Verdana, Arial, Helvetica, sans-serif' font color = '$color;'>Desconocido</font></td>
			    <td><font size='1' face='Verdana, Arial, Helvetica, sans-serif' font color = '$color;'>Desconocido</font></td>
			    
			  <td>
			  
				<input type='checkbox' name='insertarlinea[]' value=$tipo_id_paciente"."AFOZ"."$paciente_id"."AFOZ"."0>
			  </td>  
			    
				
			  </tr>";
			}
			 $ingreso_encontrado .= "<input type=\"hidden\" name=\"departamento_recibe\" value=\"$departamento_recibe\">";
		$ingreso_encontrado .= "<input type=\"hidden\" name=\"departamento_devuelve\" value=\"$departamento_devuelve\">";
		$ingreso_encontrado .= "<input type=\"hidden\" name=\"usuario_devuelve\" value=\"$usuario_devuelve\">";
		$ingreso_encontrado .= "<input type=\"hidden\" name=\"cod_menu\" value=\"$cod_menu\">";
		$ingreso_encontrado .= "<input type=\"hidden\" name=\"proceso\" value=\"$proceso\">";
 		$ingreso_encontrado .= "<tr bgcolor=#EEEEEE>
 		 <td></td>
 		 <td></td>
 		 <td></td>
 		 <td></td>
 		 <td></td>
 		 <td></td>
		 <td>
		 <input type=\"submit\" name=\"Submit\" value=\"Adicionar\" >
        </form>
		</td>
		</tr>";
			  
			    				
  break;  					

  case "add":
  			$i = 0;
			foreach($_POST["insertarlinea"] as $hablame) {
  				
				$linea = explode("AFOZ", $hablame);
				$cant_dias = $_POST['cant_dias'];
				$cant_dias = $cant_dias[$linea[3]];
				if($linea[2] == 0){
					
					$result_temporal = pg_query($dbh, "	SELECT ingreso FROM tmp_relacion_hc_detalle 
				 							WHERE session_id = '$sess'
											 AND tipo_id_paciente = '$linea[0]'
											 AND paciente_id = '$linea[1]'");
											 
					$rows_temporal = pg_num_rows($result_temporal);
					
					if($rows_temporal > 0){
						
					}
					else{
						$sql = "INSERT INTO tmp_relacion_hc_detalle(session_id"
								 . ",tipo_id_paciente"
								 . ",paciente_id"
								 . ",cant_dias"
								 . ",usuario_id"
								 . ") VALUES ("
								 . "'" . $sess . "'"
								 . ",'" . $linea[0] . "'"
								 . ",'" . $linea[1] . "'"
								 . "," . $cant_dias . ""
								 . "," . $usuario_id
								 . ")";
							if (execute_query($dbh, $sql)){
							
							} else {
				            $mensaje = "ERROR: No se pudo ejecutar la consulta.";
							
							}
							
						}						 
				}
				
				else{
				
					$result_temporal = pg_query($dbh, "	SELECT ingreso FROM tmp_relacion_hc_detalle 
				 							WHERE ingreso = $linea[2] 
											 AND session_id = '$sess'
											 AND tipo_id_paciente = '$linea[0]'
											 AND paciente_id = '$linea[1]'");
											 
					$rows_temporal = pg_num_rows($result_temporal);
					
					if($rows_temporal > 0){
						
					}
					else{
						$sql = "INSERT INTO tmp_relacion_hc_detalle(session_id"
						 		 . ",ingreso"
								 . ",tipo_id_paciente"
								 . ",paciente_id"
								 . ",cant_dias"
								 . ",usuario_id"
								 . ") VALUES ("
								 . "'" . $sess . "'"
								 . "," . $linea[2] . ""
								 . ",'" . $linea[0] . "'"
								 . ",'" . $linea[1] . "'"
								 . "," . $cant_dias . ""
								 . "," . $usuario_id
								 . ")";
							if (execute_query($dbh, $sql)){
							
							} else {
				            $mensaje = "ERROR: No se pudo ejecutar la consulta.";
							
							}
							
						}						 
				}
				
			$i++;
  			}
  break;
  
/*case "delete":

 foreach($_POST["borrarlinea"] as $idtemp) {
 
  	$sql = "DELETE FROM tmp_relacion_hc_detalle WHERE rel_hcdet_id = $idtemp";
	execute_query($dbh, $sql);
  }
  break;
  
case "grabar":

	$permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "insert", $dbh);
		if($permiso == ""){
			$mensaje_insert = "<font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>ERROR: Usuario no tiene permiso para ejecutar esta accion.</strong></font>";
			break;
		}

$numero_control = 0;

$message = "
<html>
<body>
<center><table align=CENTER% width=80% border=0 cellspacing=0>
<tr> 
      <td  background=imagenes/cellpic1.gif  bgcolor=#EEEEEE><div align=center><strong><font color=#003366 size=2>Historias Clinicas que no pueden ser relacionadas:</font></strong></div></td>
</tr><br><br>";



$pre_insert = execute_query($dbh, "SELECT a.*
 FROM   tmp_relacion_hc_detalle a
 WHERE  a.usuario_id=$usuario_id
 ORDER BY a.rel_hcdet_id");
 $rows = pg_num_rows($pre_insert);
 if($rows > 0){
while ($p_insert = fetch_object($pre_insert)) {
 
 	if($p_insert->ingreso == Null){
		$query = "Select rel_hcdet_id, relacion_hcid, estado 
	 			FROM relacion_hc_detalle 
				WHERE rel_hcdet_id = (Select max(rel_hcdet_id) from relacion_hc_detalle 
										AND tipo_id_paciente = '$p_insert->tipo_id_paciente'
										AND paciente_id = '$p_insert->paciente_id'
										AND estado NOT IN ('3','5'))";
	}
	else{
		$query = "Select rel_hcdet_id, relacion_hcid, estado 
	 			FROM relacion_hc_detalle 
				WHERE rel_hcdet_id = (Select max(rel_hcdet_id) from relacion_hc_detalle 
										WHERE ingreso = $p_insert->ingreso 
										AND tipo_id_paciente = '$p_insert->tipo_id_paciente'
										AND paciente_id = '$p_insert->paciente_id'
										AND estado NOT IN ('3','5'))";
	}
 	
    $result = execute_query($dbh, $query);
    $comprobar = pg_fetch_row($result);

    $maximo = $comprobar[0];
    
    $relacion_hcid_encontrada = $comprobar[1];
    $estado = $comprobar[2];
    
    free_result($result);
	
	$query1 = "SELECT departamento_solicita, departamento_recibe FROM relacion_hc 
	 WHERE relacion_hcid = $relacion_hcid_encontrada";
    $result1 = execute_query($dbh, $query1);
    
    $comprobar1 = pg_fetch_row($result1);
    
    $departamento_o = $comprobar1[0];
    $departamento_d = $comprobar1[1];
    
    free_result($result1);
    
    	if($estado == '4' AND $departamento_o <> $departamento_recibe){
    	 
    	 	$consulta1 = "SELECT descripcion FROM departamentos 
	 		WHERE departamento = '$departamento_o'";
    		$resultado1 = execute_query($dbh, $consulta1);
       		$dpto1 = pg_fetch_row($resultado1);
       		$departamento = $dpto1[0];
      		free_result($resultado1);
    	 
    	 	$numero_control = $numero_control + 1;
    	 	
    	 	$message .= 
			 "<tr><td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>La HC ".$p_insert->tipo_id_paciente." ".$p_insert->paciente_id." ya fue recibida por el departamento de ".$departamento."</strong></font></td></tr>";
      
    	 	
    	 	}
    	 	
    	 	else if($estado == '2'){
					$numero_control = $numero_control + 1;
					
					$message .= "<tr><td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>La HC ".$p_insert->tipo_id_paciente." ".$p_insert->paciente_id." esta pendiente por recibir. Relacion ".$relacion_hcid_encontrada." "."</strong></font></td></tr>";
					}
					
					else if($estado == '1'){
					$numero_control = $numero_control + 1;
					
					
					
					$message .= "<tr><td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>La HC ".$p_insert->tipo_id_paciente." ".$p_insert->paciente_id." ya se encuentra relacionada"."</strong></font></td></tr>";
					}
				
		
		else{
			
		}		
		
 
 }
 
		if($numero_control > 0){
		echo $message;	
		}
		else{
			
$val = execute_query($dbh, "SELECT nextval('relacion_hc_relacion_hcid_seq')");
$row = fetch_object($val);

$estado_relacion = '1';
$sql_insertar_relacion = "INSERT INTO relacion_hc(relacion_hcid"
		 . ",fecha_registro"	
		 . ",usuario_solicita"
		 . ",estado"
		 . ",departamento_solicita"
		 . ",departamento_recibe"
		 . ") VALUES ("
		 . "" . $row->nextval . ""
		 . ",'" . $fecha_registro . "'"
		 . "," . $usuario_id . ""
		 . ",'" . $estado_relacion . "'"
		 . ",'" . $departamento_solicita . "'"
		 . ",'" . $departamento_recibe
		 . "')";
		 
		if (execute_query($dbh, $sql_insertar_relacion)){
			
				$estado = '1';
				$result3 = execute_query($dbh, "SELECT a.rel_hcdet_id,
								a.tipo_id_paciente, 
								a.paciente_id,
								a.observacion,
								a.cant_dias, 
								b.primer_nombre, 
								b.segundo_nombre, 
								b.primer_apellido, 
								b.segundo_apellido, 
								c.ingreso, 
								TO_CHAR(c.fecha_registro,'YYYY-MM-DD') as fecha_registro, 
								d.descripcion
							 FROM   ((tmp_relacion_hc_detalle a LEFT OUTER JOIN ingresos c ON a.ingreso=c.ingreso) 
								LEFT OUTER JOIN pacientes b ON (a.tipo_id_paciente=b.tipo_id_paciente) AND (a.paciente_id=b.paciente_id)) 
								LEFT OUTER JOIN departamentos d ON c.departamento=d.departamento
							WHERE a.usuario_id = $usuario_id
							ORDER BY a.rel_hcdet_id DESC");
 												
 												while ($row3 = fetch_object($result3)) {
 												 
 												 
 												 
 												 	if($row3->ingreso == Null){
														$sql1 = "INSERT INTO relacion_hc_detalle(relacion_hcid"
		 															. ",paciente_id"
		 															. ",tipo_id_paciente"
		 															. ",estado"
		 															. ",observacion"
		 															. ",cant_dias"
		 															. ") VALUES ("
		 															. "" . $row->nextval . ""
																	. ",'" . $row3->paciente_id . "'"
																	. ",'" . $row3->tipo_id_paciente . "'"
																	. ",'" . $estado . "'"
																	. ",'" . $row3->observacion . "'"
																	. "," . $row3->cant_dias
																	. ")";
																	
																	if (execute_query($dbh, $sql1)){
																	 
																	 $sql2 = "DELETE FROM tmp_relacion_hc_detalle 	WHERE rel_hcdet_id = $row3->rel_hcdet_id";
	execute_query($dbh, $sql2);
																	 
																	 }
																	 else{
																		$mensaje = "ERROR: No se pudo ejecutar la consulta.";
																	}
													}
													else{
														$sql1 = "INSERT INTO relacion_hc_detalle(relacion_hcid"
																	. ",ingreso"
		 															. ",paciente_id"
		 															. ",tipo_id_paciente"
		 															. ",estado"
		 															. ",observacion"
		 															. ",cant_dias"
		 															. ") VALUES ("
		 															. "" . $row->nextval . ""
		 															. "," . $row3->ingreso . ""
																	. ",'" . $row3->paciente_id . "'"
																	. ",'" . $row3->tipo_id_paciente . "'"
																	. ",'" . $estado . "'"
																	. ",'" . $row3->observacion . "'"
																	. "," . $row3->cant_dias
																	. ")";
																	
																	if (execute_query($dbh, $sql1)){
																	 
																	 $sql2 = "DELETE FROM tmp_relacion_hc_detalle 	WHERE rel_hcdet_id = $row3->rel_hcdet_id";
	execute_query($dbh, $sql2);
																	 
																	 }
																	 else{
																		$mensaje = "ERROR: No se pudo ejecutar la consulta.";
																	}
													}
													  
													  
 												 
 												 }
		} else {
            $mensaje = "ERROR: No se pudo ejecutar el evento.";
			
		}
		$departamento_recibe = '';
		$departamento_solicita = '';
		$proceso = 'grabar';
		
				$consulta2 = "SELECT a.relacion_hcid, a.fecha_registro, b.nombre, TO_CHAR(a.fecha_registro,'YYYY-MM-DD') as fecha_relacion
 				FROM   relacion_hc a, system_usuarios b
 				WHERE  a.relacion_hcid = $row->nextval
 				AND a.usuario_solicita = b.usuario_id
 				ORDER BY a.relacion_hcid";
    			$resultado2 = execute_query($dbh, $consulta2);
       			$row_c = pg_fetch_row($resultado2);
       			
      			free_result($resultado2);
		
		$mensaje_insert = "<table align=CENTER% width=100% border=0 cellspacing=0> <tr> 
      <td  background=imagenes/cellpic1.gif colspan=7 bgcolor=#EEEEEE><div align=left><strong><font color=#003366 size=1>RELACION: ".$row_c[0]." FECHA: ".$row_c[1]." USUARIO: ".$row_c[2]."</font></strong></div></td>
    </tr>";
    
    	$mensaje_insert .= "<tr> 
    <td width=10% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Identificacion</font></strong></a> 
   </td>
    <td width=30% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Paciente</font></strong></a> 
   </td>
    <td width=10% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Fec Ingreso</font></strong></a> 
   </td>
    <td width=20% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Departamento</font></strong></a> 
   </td>
    <td width=10% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Ingreso</font></strong></a> 
   </td>
   <td width=10% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Dias Prestamo</font></strong></a> 
   </td>
   <td width=10% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Fec Entrega</font></strong></a> 
   </td>
	</tr>";
			$relacion_detalle = execute_query($dbh, "SELECT a.rel_hcdet_id,
								a.tipo_id_paciente, 
								a.paciente_id,
								a.cant_dias, 
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
							WHERE a.relacion_hcid = $row_c[0]
							ORDER BY a.rel_hcdet_id");
			
		while ($row_d = fetch_object($relacion_detalle)) {
		 $fecha_entrega = suma_fechas($row_c[3],$row_d->cant_dias);
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
		 
		 $mensaje_insert .= "<tr bgcolor=#EEEEEE> 
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->tipo_id_paciente"." "."$row_d->paciente_id</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$paciente</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$fec_ingreso</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$departamento</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$ingreso</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->cant_dias</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$fecha_entrega</font></td>
	</tr>";
		 
		 }
		 
	$mensaje_insert .= "<tr bgcolor=#EEEEEE>
	<td colspan=3><a href=imprimir_relacion_hc.php?relacion_hcid=$row->nextval target=_blank>Imprimir Relacion</a>
	</td>
	<td colspan=3><a href=xls_relacionhc.php?relacion_hcid=$row->nextval>Exportar a Excel</a>
	</td>
	<td></td>
	
	</tr></table><br>";
	
	$mensaje_insert .= $imprimir;
	
			$departamento_recibe = '';
			$departamento_solicita = '';
			$proceso = 'grabar';
			$relacion_hcid = "";
  
		}
	}
	else {
		$mensaje_insert = "ERROR: No se pudo ejecutar el evento.";
	}	
	break;
	
	case "actualizar":
	$permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "update", $dbh);
		if($permiso == ""){
			$mensaje_insert = "<font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>ERROR: Usuario no tiene permiso para ejecutar esta accion.</strong></font>";
			break;
		}
		$numero_control = 0;

$message = "
<html>
<body>
<center><table align=CENTER% width=80% border=0 cellspacing=0>
<tr> 
      <td  background=imagenes/cellpic1.gif  bgcolor=#EEEEEE><div align=center><strong><font color=#003366 size=2>Historias Clinicas que no pueden ser relacionadas:</font></strong></div></td>
</tr><br><br>";



$pre_insert = execute_query($dbh, "SELECT a.*
 FROM   tmp_relacion_hc_detalle a
 WHERE  a.usuario_id=$usuario_id
 ORDER BY a.rel_hcdet_id");
 $rows = pg_num_rows($pre_insert);
 if($rows > 0){
	while ($p_insert = fetch_object($pre_insert)) {
 
 		if($p_insert->ingreso == Null){
			
		}
		else{
			$query = "Select rel_hcdet_id, relacion_hcid, estado 
			FROM relacion_hc_detalle 
			WHERE rel_hcdet_id = (Select max(rel_hcdet_id) 
									from relacion_hc_detalle 
									WHERE (ingreso = $p_insert->ingreso 
									AND tipo_id_paciente = '$p_insert->tipo_id_paciente'
									AND paciente_id = '$paciente_id')  
									AND relacion_hcid <> $relacion_hcid
									AND estado NOT IN ('3','5'))";
		}
 	
 	
    $result = execute_query($dbh, $query);
    $comprobar = pg_fetch_row($result);

    $maximo = $comprobar[0];
    
    $relacion_hcid_vieja = $comprobar[1];
    $estado = $comprobar[2];
    
    free_result($result);
	
	$query1 = "SELECT departamento_solicita, departamento_recibe FROM relacion_cuentas 
	 WHERE relacion_id = $relacion_hcid_vieja";
    $result1 = execute_query($dbh, $query1);
    
    $comprobar1 = pg_fetch_row($result1);
    
    $departamento_o = $comprobar1[0];
    $departamento_d = $comprobar1[1];
    
    free_result($result1);
    
    	if($estado == '4' AND $departamento_o <> $departamento_recibe){
    	 
    	 	$consulta1 = "SELECT descripcion FROM departamentos 
	 		WHERE departamento = '$departamento_o'";
    		$resultado1 = execute_query($dbh, $consulta1);
       		$dpto1 = pg_fetch_row($resultado1);
       		$departamento = $dpto1[0];
      		free_result($resultado1);
    	 
    	 	$numero_control = $numero_control + 1;
    	 	
    	 	$message .= 
			 "<tr><td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>La HC ".$p_insert->tipo_id_paciente." ".$p_insert->paciente_id." ya fue recibida por el departamento de ".$departamento."</strong></font></td></tr>";
      
    	 	
    	 	}
    	 	
    	 	else if($estado == '2'){
					$numero_control = $numero_control + 1;
					
					$message .= "<tr><td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>La HC ".$p_insert->tipo_id_paciente." ".$p_insert->paciente_id." esta pendiente por recibir. Relacion ".$relacion_hcid_vieja." "."</strong></font></td></tr>";
					}
					
					else if($estado == '1'){
					$numero_control = $numero_control + 1;
					
					
					
					$message .= "<tr><td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>La HC ".$p_insert->tipo_id_paciente." ".$p_insert->paciente_id." ya se encuentra relacionada"."</strong></font></td></tr>";
					}
				
		
		else{
			
		}			

 	}

	if($numero_control > 0){
		echo $message;	
		}
	else{
	 		$sql = "UPDATE relacion_hc SET "
		 . "departamento_solicita = '" . $departamento_solicita . "'"
		 . ",departamento_recibe = '" . $departamento_recibe
		 . "' WHERE relacion_hcid = " . $relacion_hcid . "";
		 
		if (execute_query($dbh, $sql)){
		 
		 				$sql = "DELETE FROM relacion_hc_detalle WHERE relacion_hcid = " . $relacion_hcid;
						execute_query($dbh, $sql);
		 			
					 	$estado = '1';
						$result3 = execute_query($dbh, "SELECT a.rel_hcdet_id,
								a.tipo_id_paciente, 
								a.paciente_id,
								a.observacion,
								a.cant_dias, 
								b.primer_nombre, 
								b.segundo_nombre, 
								b.primer_apellido, 
								b.segundo_apellido, 
								c.ingreso, 
								TO_CHAR(c.fecha_registro,'YYYY-MM-DD') as fecha_registro, 
								d.descripcion
							 FROM   ((tmp_relacion_hc_detalle a LEFT OUTER JOIN ingresos c ON a.ingreso=c.ingreso) 
								LEFT OUTER JOIN pacientes b ON (a.tipo_id_paciente=b.tipo_id_paciente) AND (a.paciente_id=b.paciente_id)) 
								LEFT OUTER JOIN departamentos d ON c.departamento=d.departamento
							WHERE a.usuario_id = $usuario_id
							ORDER BY a.rel_hcdet_id DESC");
 												
 												while ($row3 = fetch_object($result3)) {
 												 
 												 
 												 
 												 	if($row3->ingreso == Null){
														$sql1 = "INSERT INTO relacion_hc_detalle(relacion_hcid"
		 															. ",paciente_id"
		 															. ",tipo_id_paciente"
		 															. ",estado"
		 															. ",observacion"
		 															. ",cant_dias"
		 															. ") VALUES ("
		 															. "" . $relacion_hcid . ""
																	. ",'" . $row3->paciente_id . "'"
																	. ",'" . $row3->tipo_id_paciente . "'"
																	. ",'" . $estado . "'"
																	. ",'" . $row3->observacion . "'"
																	. "," . $row3->cant_dias
																	. ")";
																	
																	if (execute_query($dbh, $sql1)){
																	 
																	 $sql2 = "DELETE FROM tmp_relacion_hc_detalle 	WHERE rel_hcdet_id = $row3->rel_hcdet_id";
	execute_query($dbh, $sql2);
																	 
																	 }
																	 else{
																		$mensaje = "ERROR: No se pudo ejecutar la consulta.";
																	}
													}
													else{
														$sql1 = "INSERT INTO relacion_hc_detalle(relacion_hcid"
																	. ",ingreso"
		 															. ",paciente_id"
		 															. ",tipo_id_paciente"
		 															. ",estado"
		 															. ",observacion"
		 															. ",cant_dias"
		 															. ") VALUES ("
		 															. "" . $relacion_hcid . ""
		 															. "," . $row3->ingreso . ""
																	. ",'" . $row3->paciente_id . "'"
																	. ",'" . $row3->tipo_id_paciente . "'"
																	. ",'" . $estado . "'"
																	. ",'" . $row3->observacion . "'"
																	. "," . $row3->cant_dias
																	. ")";
																	
																	if (execute_query($dbh, $sql1)){
																	 
																	 $sql2 = "DELETE FROM tmp_relacion_hc_detalle 	WHERE rel_hcdet_id = $row3->rel_hcdet_id";
	execute_query($dbh, $sql2);
																	 
																	 }
																	 else{
																		$mensaje = "ERROR: No se pudo ejecutar la consulta.";
																	}
													}
													  
													  
 												 
 												 }
		 
			
		
				$consulta2 = "SELECT a.relacion_hcid, a.fecha_registro, b.nombre, TO_CHAR(a.fecha_registro,'YYYY-MM-DD') as fecha_relacion
 				FROM   relacion_hc a, system_usuarios b
 				WHERE  a.relacion_hcid = $relacion_hcid
 				AND a.usuario_solicita = b.usuario_id
 				ORDER BY a.relacion_hcid";
    			$resultado2 = execute_query($dbh, $consulta2);
       			$row_c = pg_fetch_row($resultado2);
       			
      			free_result($resultado2);
		
		$mensaje_insert = "<table align=CENTER% width=100% border=0 cellspacing=0> <tr> 
      <td  background=imagenes/cellpic1.gif colspan=7 bgcolor=#EEEEEE><div align=left><strong><font color=#003366 size=1>RELACION: ".$row_c[0]." FECHA: ".$row_c[1]." USUARIO: ".$row_c[2]."</font></strong></div></td>
    </tr>";
    
    	$mensaje_insert .= "<tr> 
    <td width=10% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Identificacion</font></strong></a> 
   </td>
    <td width=30% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Paciente</font></strong></a> 
   </td>
    <td width=10% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Fec Ingreso</font></strong></a> 
   </td>
    <td width=20% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Departamento</font></strong></a> 
   </td>
    <td width=10% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Ingreso</font></strong></a> 
   </td>
   <td width=10% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Dias Prestamo</font></strong></a> 
   </td>
   <td width=10% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Fec Entrega</font></strong></a> 
   </td>
	</tr>";
			$relacion_detalle = execute_query($dbh, "SELECT a.rel_hcdet_id,
								a.tipo_id_paciente, 
								a.paciente_id,
								a.cant_dias, 
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
							WHERE a.relacion_hcid = $row_c[0]
							ORDER BY a.rel_hcdet_id");
			
		while ($row_d = fetch_object($relacion_detalle)) {
		 $fecha_entrega = suma_fechas($row_c[3],$row_d->cant_dias);
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
		 
		 $mensaje_insert .= "<tr bgcolor=#EEEEEE> 
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->tipo_id_paciente"." "."$row_d->paciente_id</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$paciente</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$fec_ingreso</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$departamento</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$ingreso</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->cant_dias</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$fecha_entrega</font></td>
	</tr>";
		 
		 }
		 
	$mensaje_insert .= "<tr bgcolor=#EEEEEE>
	<td colspan=3><a href=imprimir_relacion_hc.php?relacion_hcid=$relacion_hcid target=_blank>Imprimir Relacion</a>
	</td>
	<td colspan=3><a href=xls_relacionhc.php?relacion_hcid=$relacion_hcid>Exportar a Excel</a>
	</td>
	<td></td>
	
	</tr></table><br>";
	
	$mensaje_insert .= $imprimir;
		} 
		
		else {
            $mensaje = "ERROR: No se pudo ejecutar la consulta.";
			}
			
			
			$departamento_recibe = '';
			$departamento_solicita = '';
			$proceso = 'grabar';
			$relacion_id = "";
	}
}
		 break;
		 
	case "confirmar":
	
	$permiso_update = PerfilOpcionUsuario($usuario_id, $cod_menu, "update", $dbh);
				
				if($permiso_update == ""){
				$mensaje_insert = "<font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>ERROR: Usuario no tiene permiso para ejecutar esta accion.</strong></font>";
				echo $mensaje_insert;
				break;
				
				}
  foreach($_POST["hc_recibidas"] as $linea_recibida) {
  		
	
	
	echo $linea_recibida;
	
		
	
	
	$estado = '4';
	$sql_recibe_hc = "UPDATE relacion_hc_detalle SET "
		 . "estado = '" . $estado
		 . "' WHERE rel_hcdet_id = " . $linea_recibida . "";
		 
		if (execute_query($dbh, $sql_recibe_hc)){
		 $estado_relacion = '3';
				$sql_update_hc = "UPDATE relacion_hc SET "
		 		. "usuario_recibe = " . $usuario_id . ""
		 		. ",estado = '" . $estado_relacion . "'"
		 		. ",fecha_recibe = '" . $fecha_registro
		 		. "' WHERE relacion_hcid = " . $relacion_hcid . "";
		 		
					if (execute_query($dbh, $sql_update_hc)){
					 		$val_insert = execute_query($dbh, "SELECT a.*
	 						FROM   relacion_hc_detalle a
	 						WHERE  a.relacion_hcid=$relacion_hcid AND a.estado = '2'
	 						ORDER BY a.rel_hcdet_id");
							while ($v_insert = fetch_object($val_insert)) {
	 							
	 									$estado_ND = '5';
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
		echo "<script language='Javascript'> self.close(); </script>";
		exit;
  break;	 
	
	default:
	
	
	
	
	
	if ($relacion_hcid != ""){
	 
	 
	 if ($proceso != "")
	 	{
			
		}
	else
		{
			execute_query($dbh, "DELETE FROM tmp_relacion_hc_detalle WHERE usuario_id = " . $usuario_id);
	 
	 		$result = execute_query($dbh, "SELECT a.* FROM relacion_hc a WHERE a.relacion_hcid = " . $relacion_hcid);
	 		$row = fetch_object($result);
	 		$relacion_hcid = $row->relacion_hcid;
			$departamento_solicita = $row->departamento_solicita;
	 		$departamento_recibe = $row->departamento_recibe;
	 		$estado_relacion = $row->estado;
	 		free_result($result);
	 
	 		if($estado_relacion == '1'){
	 
	 		$result = execute_query($dbh, "SELECT * FROM relacion_hc_detalle WHERE relacion_hcid = " . $relacion_hcid);
	 		while (($row = fetch_object($result))) {
	  
	  			if($row->ingreso==Null){
			  
			  		$sql = "INSERT INTO tmp_relacion_hc_detalle(session_id"
								 . ",tipo_id_paciente"
								 . ",paciente_id"
								 . ",cant_dias"
								 . ",observacion"
								 . ",usuario_id"
								 . ") VALUES ("
								 . "'" . $sess . "'"
								 . ",'" . $row->tipo_id_paciente . "'"
								 . ",'" . $row->paciente_id . "'"
								 . "," . $row->cant_dias . ""
								 . ",'" . $row->observacion . "'"
								 . "," . $usuario_id
								 . ")";
					execute_query($dbh, $sql);
				}
				else{
					$sql = "INSERT INTO tmp_relacion_hc_detalle(session_id"
						 		 . ",ingreso"
								 . ",tipo_id_paciente"
								 . ",paciente_id"
								 . ",cant_dias"
								 . ",observacion"
								 . ",usuario_id"
								 . ") VALUES ("
								 . "'" . $sess . "'"
								 . "," . $row->ingreso . ""
								 . ",'" . $row->tipo_id_paciente . "'"
								 . ",'" . $row->paciente_id . "'"
								 . "," . $row->cant_dias . ""
								 . ",'" . $row->observacion . "'"
								 . "," . $usuario_id
								 . ")";
					execute_query($dbh, $sql);
				}
			}
	 		$proceso="actualizar";
		}
		else if($estado = '2'){
			$proceso="confirmar";
		}
		else{}
		
	  }	
	}
	else{
		if ($proceso != "")
	 	{
			
		}
		
		else
		{
			execute_query($dbh, "DELETE FROM tmp_relacion_hc_detalle WHERE usuario_id = " . $usuario_id ." ");
		}
				
		$proceso="grabar";
	}*/
}



?>

<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<script language="JavaScript" src="calendar3.js"></script>

<link href="Estilos.css" rel="stylesheet" type="text/css">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<table align="CENTER%" width="100%" border="1" bordercolor="blue">
<tr>
<td><center><img src="imagenes/logo_clinica.bmp" align="CENTER%" width="250" height="150">  
	
	</td>
  </tr>
<?php
  	if($estado == '2'){
  	 
  	 		echo relacion_despachada($relacion_hcid, $dbh);
  	 
  	 }
  	 
  	 else{
	?>

<form name="tstest">
<form name="devolver_hc" action="<?=$_SERVER['PHP_SELF']?>" method="POST" onSubmit="return Verificar()">
<input name="cod_menu" type="hidden" id="cod_menu" value="<?php echo $cod_menu;?>">
<input name="proceso" type="hidden" id="proceso" value="<?php echo $proceso;?>">

  
<tr>
<td>
  <table align="CENTER%" width="100%" border="0" cellspacing="0">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="4" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">DEVOLUCION DE HISTORIAS CLINICAS</font></strong></div></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Departamento Recibe</font></td>
      <td bgcolor="#EEEEEE"><?php


print ("
<select name=\"departamento_recibe\"  onchange=\"submit();\">
");


print ("<option value=''>Seleccione opcion</option>");


$result_dpto_recibe = execute_query($dbh, "SELECT departamentos.departamento, departamentos.descripcion, system_usuarios.usuario_id
 FROM   (public.system_usuarios_departamentos system_usuarios_departamentos INNER JOIN public.departamentos departamentos ON system_usuarios_departamentos.departamento=departamentos.departamento) INNER JOIN public.system_usuarios system_usuarios ON system_usuarios_departamentos.usuario_id=system_usuarios.usuario_id
 WHERE  system_usuarios.usuario_id=$usuario_id AND departamentos.departamento IN (SELECT departamento FROM departamentos_relacion_cuentas WHERE sw_relacion_hc = '1' AND sw_retorna = '1') ORDER BY 2");

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
    
     
<td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Departamento Devuelve</font></td>
      <td bgcolor="#EEEEEE"><?php


print ("
<select name=\"departamento_devuelve\"  onchange=\"submit();\">
");


print ("<option value=''>Seleccione opcion</option>");


$result_dpto_devuelve = execute_query($dbh, "SELECT * FROM departamentos WHERE departamentos.departamento IN (SELECT departamento FROM departamentos_relacion_cuentas WHERE sw_relacion_hc = '1') ORDER BY 5");

while (($row = fetch_object($result_dpto_devuelve))) {
print("<option value=\"$row->departamento\"  ");
if ($row->departamento == $departamento_devuelve) {
print ("selected");
}

print(">$row->descripcion</option>\n
");
}
free_result($result_dpto_devuelve);
print("</select>"); 
?>

</td>

    </tr>
    <tr>
    	<td bgcolor="#EEEEEE"></td>
		<td bgcolor="#EEEEEE"></td>
		
		<td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Usuario Devuelve</font></td>
      <td bgcolor="#EEEEEE"><?php


print ("
<select name=\"usuario_devuelve\"  onchange=\"submit();\">
");


print ("<option value=''>Seleccione opcion</option>");


$result_user_devuelve = execute_query($dbh, "SELECT a.usuario_id, a.nombre 
											FROM system_usuarios a, system_usuarios_departamentos b
											WHERE a.usuario_id = b.usuario_id
											AND b.departamento = '$departamento_devuelve' 
											ORDER BY 2");

while (($row = fetch_object($result_user_devuelve))) {
print("<option value=\"$row->usuario_id\"  ");
if ($row->usuario_id == $usuario_devuelve) {
print ("selected");
}

print(">$row->nombre</option>\n
");
}
free_result($result_user_devuelve);
print("</select>"); 
?>

</td>
    </tr>      
</form>

<?php   echo $mensaje_insert; ?>

<?php echo "<form method=\"GET\" action=\"devoluciones_hc.php\">";?>    
    <?php if($departamento_recibe <> '' AND $departamento_devuelve <> '' AND $usuario_devuelve <> ''){
	 
	 ?>
    <tr> 
    
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Ingreso</font></td>
      <td bgcolor="#EEEEEE"><input name="ingreso" type="text" id="ingreso" maxlength="10" class="textbox"></td>
      <td bgcolor="#EEEEEE"></td>
      <td bgcolor="#EEEEEE"></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Tipo Documento</font></td>
      <td bgcolor="#EEEEEE"><select name="tipo_id_paciente" id="tipo_id_paciente">
          
<?php
$result_tipo_id_paciente = execute_query($dbh, "SELECT tipo_id_paciente, descripcion FROM tipos_id_pacientes ORDER BY 	indice_de_orden");
while (($row = fetch_object($result_tipo_id_paciente))) {
    echo "<option value='" . $row->tipo_id_paciente . "'";
    if ($row->tipo_id_paciente == $tipo_id_paciente) echo " selected ";
    echo ">" . $row->descripcion . "</option>";
} 
free_result($result_tipo_id_paciente);

?>
        </select></td>
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Documento</font></td>
      <td bgcolor="#EEEEEE"><input name="paciente_id" type="text" id="paciente_id" maxlength="32" class="textbox"></td>
    </tr>
    
    <?php echo 	"<input type=\"hidden\" name=\"departamento_recibe\" value=\"$departamento_recibe\">";
			  echo	"<input type=\"hidden\" name=\"departamento_devuelve\" value=\"$departamento_devuelve\">";
			  echo	"<input type=\"hidden\" name=\"usuario_devuelve\" value=\"$usuario_devuelve\">";
			  echo	"<input type=\"hidden\" name=\"cod_menu\" value=\"$cod_menu\">";
			  echo	"<input type=\"hidden\" name=\"proceso\" value=\"$proceso\">";
			  echo	"<input type=\"hidden\" name=\"action1\" value=\"search\">";
 		echo "<tr bgcolor=#EEEEEE>
 		 <td>
		 <input type=\"submit\" name=\"Submit\" value=\"Buscar\" >
        </td>
        <td></td>
        <td></td>
        <td></td>
    	</tr>";?>
	</form> 
    <?php
    }
?>
    
    
    
   <?php
   
if($action1 == "search"){
		?>
  
	<br>
	<table width="100%" border="0" cellspacing="0" align="center">
	  <tr>
	  	<td width="10%" background="imagenes/cellpic1.gif">
		 </td>  
	    <td width="10%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Identificacion</font></strong></a> 
	   </td>
	    <td width="30%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Nombre</font></strong></a> 
	   </td>
	    <td width="10%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Fec Ingreso</font></strong></a> 
	   </td>
	   <td width="20%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Departamento</font></strong></a> 
		</td>
	    <td width="10%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Ingreso</font></strong></a> 
		</td>
		
		<td width="10%" background="imagenes/cellpic1.gif">
		 </td>
	  </tr>
	<?php
	 echo $ingreso_encontrado;
	 ?>
	 </table>
	 <?php
	}
  }
if($departamento_solicita <> '' AND $departamento_recibe <> ''){
 
 		$resultado_b = pg_query($dbh, " SELECT a.rel_hcdet_id,
		 								a.tipo_id_paciente, 
										a.paciente_id,
										a.cant_dias, 
										b.primer_nombre, 
										b.segundo_nombre, 
										b.primer_apellido, 
										b.segundo_apellido, 
										c.ingreso, 
										c.fecha_registro, 
										d.descripcion
									 FROM   ((tmp_relacion_hc_detalle a LEFT OUTER JOIN ingresos c ON a.ingreso=c.ingreso) 
										LEFT OUTER JOIN pacientes b ON (a.tipo_id_paciente=b.tipo_id_paciente) AND (a.paciente_id=b.paciente_id)) 
										LEFT OUTER JOIN departamentos d ON c.departamento=d.departamento
									WHERE a.usuario_id = $usuario_id
									ORDER BY a.rel_hcdet_id DESC");

		$rows = pg_num_rows($resultado_b);
if($rows > 0){		
		
$result2 = execute_query($dbh, " SELECT a.rel_hcdet_id,
								a.tipo_id_paciente, 
								a.paciente_id,
								a.cant_dias, 
								b.primer_nombre, 
								b.segundo_nombre, 
								b.primer_apellido, 
								b.segundo_apellido, 
								c.ingreso, 
								TO_CHAR(c.fecha_registro,'YYYY-MM-DD') as fecha_registro, 
								d.descripcion
							 FROM   ((tmp_relacion_hc_detalle a LEFT OUTER JOIN ingresos c ON a.ingreso=c.ingreso) 
								LEFT OUTER JOIN pacientes b ON (a.tipo_id_paciente=b.tipo_id_paciente) AND (a.paciente_id=b.paciente_id)) 
								LEFT OUTER JOIN departamentos d ON c.departamento=d.departamento
							WHERE a.usuario_id = $usuario_id
							ORDER BY a.rel_hcdet_id DESC");



?>
  
<br>
	<table width="100%" border="0" cellspacing="0" align="center">
	  <tr> 
	    <td width="10%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Identificacion</font></strong></a> 
	   </td>
	    <td width="30%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Nombre</font></strong></a> 
	   </td>
	    <td width="10%" background="imagenes/cellpic1.gif"> 
	      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Fec Ingreso</font></strong></a> 
	   </td>
	   <td width="20%" background="imagenes/cellpic1.gif"> 
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

while ($row2 = fetch_object($result2)) {
 
 	$fecha_entrega = suma_fechas($fecha_registro,$row2->cant_dias);
 
 	if($row2->ingreso == Null){
		$paciente = "Paciente sin Historia Clinica en SIIS";
		$fec_ingreso = "Desconocido";
		$departamento = "Desconocido";
		$ingreso = "Desconocido";
	}
	else{
		$paciente = $row2->primer_nombre." ".$row2->segundo_nombre." ".$row2->primer_apellido." ".$row2->segundo_apellido;
		$fec_ingreso = $row2->fecha_registro;
		$departamento = $row2->descripcion;
		$ingreso = $row2->ingreso;
	}
?>
  <tr bgcolor="#EEEEEE"> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>">
	<?php echo $row2->tipo_id_paciente." ".$row2->paciente_id;?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>">
	<?php echo $paciente;?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>">
	<?php echo $fec_ingreso;?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>">
	<?php echo $departamento;?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>">
	<?php echo $ingreso;?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>">
	<?php echo $row2->cant_dias;?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>">
	<?php echo $fecha_entrega;?></font></td>
	<td>
    <?php echo "<form method=\"POST\" action=\"relaciones_hc.php?action1=delete&amp;departamento_solicita=$departamento_solicita&amp;departamento_recibe=$departamento_recibe&amp;relacion_hcid=$relacion_hcid&amp;proceso=$proceso&amp;cod_menu=$cod_menu\">";?>
	<input type="checkbox" name="borrarlinea[]" value="<?php echo $row2->rel_hcdet_id;?>">
	<a href="#" onClick="abrirpopup('obs_hc_relacion.php?rel_hcdet_id=<?php echo $row2->rel_hcdet_id?>',500,150);">Obs </a>
	</td>
	
	
  </tr>
  <?php
    
}
echo 	"<input type=\"hidden\" name=\"departamento_solicita\" value=\"$departamento_solicita\">";
		"<input type=\"hidden\" name=\"departamento_recibe\" value=\"$departamento_recibe\">";
		"<input type=\"hidden\" name=\"relacion_id\" value=\"$relacion_hcid\">";
		"<input type=\"hidden\" name=\"cod_menu\" value=\"$cod_menu\">";
		"<input type=\"hidden\" name=\"proceso\" value=\"$proceso\">";
 		echo "<tr bgcolor=#EEEEEE>
 		 <td></td>
 		 <td></td>
 		 <td></td>
 		 <td></td>
 		 <td></td>
 		 <td></td>
 		 <td></td>
		 <td>
		 <input type=\"submit\" name=\"Submit\" value=\"Borrar\" >
        </form>
		</td>
		</tr>";
		?>
	</table>

<table width="100%" border="0" cellspacing="0">
  <tr> <td width="8%" background="imagenes/cellpic1.gif" align="center"><?php
    echo "<form method=\"POST\" action=\"relaciones_hc.php?action1=$proceso&amp;departamento_solicita=$departamento_solicita&amp;departamento_recibe=$departamento_recibe&amp;relacion_hcid=$relacion_hcid&amp;proceso=$proceso&amp;cod_menu=$cod_menu\">
		<input type=\"submit\" name=\"Submit\" value=\"Confirmar\" >
        </form>";
        ?>
  </tr>
</table>   
<?php
}
}
?>


</td>
</tr>

</body>
</html>
