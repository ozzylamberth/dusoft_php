
<html>
<head>
<title>Documento Relacionado</title>
</head>

<body>
<?php 

require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");




procesar_entrada("GET", "relacion_hcid");
$relacion_hcid = get_value($_GET["relacion_hcid"], "C");

open_database(); 

$consulta2 = "SELECT a.relacion_hcid, a.fecha_registro, b.nombre, a.departamento_solicita, a.departamento_recibe, a.estado, a.usuario_recibe, a.usuario_despacha, TO_CHAR(a.fecha_registro,'YYYY-MM-DD') as fecha_relacion
 				FROM   relacion_hc a, system_usuarios b
 				WHERE  a.relacion_hcid = $relacion_hcid
 				AND a.usuario_solicita = b.usuario_id
 				ORDER BY a.relacion_hcid";
    			$resultado2 = execute_query($dbh, $consulta2);
       			$row_c = pg_fetch_row($resultado2);
       			
      			free_result($resultado2);
				
				$departamento_E = "SELECT descripcion FROM departamentos 
	 								WHERE departamento = '$row_c[3]'";
	 											
    			$resultado_E = execute_query($dbh, $departamento_E);
       			$dpto_E = pg_fetch_row($resultado_E);
				$E = $dpto_E[0];
				free_result($resultado_E);
									      		
				$departamento_R = "SELECT descripcion FROM departamentos 
	 								WHERE departamento = '$row_c[4]'";
	 											
    			$resultado_R = execute_query($dbh, $departamento_R);
       			$dpto_R = pg_fetch_row($resultado_R);
				$R = $dpto_R[0];
				free_result($resultado_E);
		
		$mensaje_insert = "<table align=CENTER% width=100% border=0 cellspacing=0> <tr>
<td align=center><img src=img/logo_clinica.bmp WIDTH=200 HEIGHT=140>
</td>
  </tr>
  </table>
  <table align=CENTER% width=100% border=1 cellspacing=0>
  <tr> 
      <td colspan=8 bgcolor=WHITE><div align=left><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>RELACION: ".$row_c[0]." FECHA: ".$row_c[1]." USUARIO: ".$row_c[2]."</font></strong></div></td>
    </tr>
	<tr> 
      <td colspan=4 bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>Departamento Solicita: ".$E."</font></strong></div></td>
	  
	  <td colspan=4 bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>Departamento Despacha: ".$R."</font></strong></div></td></tr>";
	  
	  	if($row_c[5] <> '1'){
		
			if($row_c[5] == '2'){
			 
			 	$usuario_despacha = "SELECT nombre FROM system_usuarios 
	 								WHERE usuario_id = '$row_c[7]'";
	 											
    			$usuario_despacha_D = execute_query($dbh, $usuario_despacha);
       			$usuario_D = pg_fetch_row($usuario_despacha_D);
				$nombre_despacha = $usuario_D[0];
				free_result($usuario_despacha_D);
				
				$mensaje_insert .="<tr> 
      <td colspan=4 bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1></font></strong></div></td>
	  
	  <td colspan=4 bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>Usuario despacha: ".$nombre_despacha."</font></strong></div></td></tr>";
			}
		else if($row_c[5] == '3'){
		 
		 		
				 $usuario_recibe = "SELECT nombre FROM system_usuarios 
	 								WHERE usuario_id = '$row_c[6]'";
	 											
    			$usuario_R = execute_query($dbh, $usuario_recibe);
       			$usuario_RE = pg_fetch_row($usuario_R);
				$nombre_recibe = $usuario_RE[0];
				free_result($usuario_R);
				 
				$usuario_despacha = "SELECT nombre FROM system_usuarios 
	 								WHERE usuario_id = '$row_c[7]'";
	 											
    			$usuario_D = execute_query($dbh, $usuario_despacha);
       			$usuario_DE = pg_fetch_row($usuario_D);
				$nombre_despacha = $usuario_DE[0];
				free_result($usuario_D);
		 
		 		$mensaje_insert .="<tr> 
      <td colspan=4 bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>Usuario Recibe: ".$nombre_recibe."</font></strong></div></td>
	  
	  <td colspan=4 bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>Usuario despacha: ".$nombre_despacha."</font></strong></div></td></tr>";
			
		}
	  } 
    
    
    	$mensaje_insert .= "<tr> 
    <td width=10%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Identificacion</font></strong></a> 
   </td>
    <td width=30%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Paciente</font></strong></a> 
   </td>
    <td width=10%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Fec Ingreso</font></strong></a> 
   </td>
    <td width=15%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Departamento</font></strong></a> 
   </td>
    <td width=10%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Ingreso</font></strong></a> 
   </td>
   <td width=5%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Dias Prestamo</font></strong></a> 
   </td>
   <td width=10%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Fec Entrega</font></strong></a> 
   </td>
   <td width=10%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Estado</font></strong></a> 
   </td>
	</tr>";
			$relacion_detalle = execute_query($dbh, "SELECT a.rel_hcdet_id,
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
							WHERE a.relacion_hcid = $row_c[0]
							ORDER BY a.rel_hcdet_id");
			$fecha_entrega = 0;
		while ($row_d = fetch_object($relacion_detalle)) {
		 	
		 	$fecha_entrega = suma_fechas($row_c[8],$row_d->cant_dias);
		 	
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
			
			if($row_d->estado == '1'){
				$estado = 'Relacionada';
			}
			else if($row_d->estado == '2'){
				$estado = 'Despachada';
			}
			else if($row_d->estado == '3'){
				$estado = 'No despachada';
			}
			else if($row_d->estado == '4'){
				$estado = 'Entregada';
			}
			else if($row_d->estado == '5'){
				$estado = 'No recibida';
			}
			else{}
			
			
		 	
		 $mensaje_insert .= "<tr bgcolor=WHITE> 
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$row_d->tipo_id_paciente"." "."$row_d->paciente_id</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$paciente</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$fec_ingreso</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$departamento</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$ingreso</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$row_d->cant_dias</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$fecha_entrega</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$estado</font></td>
	</tr>";
		 
		 }
		 
	
	echo $mensaje_insert;

?>
</body>
</html>
