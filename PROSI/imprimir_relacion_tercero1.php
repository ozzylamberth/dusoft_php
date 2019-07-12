
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
procesar_entrada("GET", "relacion_id");
$relacion_id = get_value($_GET["relacion_id"], "C");

open_database(); 

$consulta2 = "SELECT a.relacion_id, a.fecha_registro, b.nombre, a.departamento_entrega, a.departamento_recibe, a.usuario_recibe, a.fecha_recibe
 				FROM   relacion_cuentas a, system_usuarios b
 				WHERE  a.relacion_id = $relacion_id
 				AND a.usuario_entrega = b.usuario_id
 				ORDER BY a.relacion_id";
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
				
				$usuario_R = "SELECT nombre FROM system_usuarios 
	 								WHERE usuario_id = $row_c[5]";
	 											
    			$resultado_UR = execute_query($dbh, $usuario_R);
       			$usuario_R = pg_fetch_row($resultado_UR);
       			$rows = pg_num_rows($resultado_UR);
       			
       			if ($rows > 0){
					$usuario_recibe = $usuario_R[0];
				}
				else{
					$usuario_recibe = "Aún no la reciben";
				}
				
				free_result($resultado_UR);
		
		
		
		$mensaje_insert = "<table align=CENTER width=100% border=1 cellspacing=0> 
		<tr>
		<td>";
		$mensaje_insert .= "<table align=CENTER width=90% border=0 cellspacing=0> <tr>
<td align=center><img src=img/logo_clinica.bmp WIDTH=200 HEIGHT=140>
</td>
</tr>
  </table>
  <table align=CENTER width=90% border=1 cellspacing=0>
  <tr> 
      <td width=50% bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>RELACION: ".$row_c[0]." FECHA: ".$row_c[1]."</font></strong></div></td>
      <td width=50% bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>FECHA RECIBE: ".$row_c[6]."</font></strong></div></td>
    </tr>
	<tr> 
      <td width=50% bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>Departamento Envia: ".$E."</font></strong></div></td>
	  
	  <td width=50% bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>Departamento Recibe: ".$R."</font></strong></div></td>
	</tr>
	
	<tr>
	<td width=50% bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1> Usuario: ".$row_c[2]."</font></strong></div></td>
	<td width=50% bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1> Usuario: ".$usuario_recibe."</font></strong></div></td>
	</tr></table>";
    
    	$relacion_tercero = execute_query($dbh, "SELECT terceros.tipo_id_tercero, terceros.tercero_id, terceros.nombre_tercero, SUM(relacion_cuentas_detalle.total_cuenta) AS total_x_tercero
 FROM  ((((((public.relacion_cuentas_detalle relacion_cuentas_detalle INNER JOIN public.relacion_cuentas relacion_cuentas ON relacion_cuentas_detalle.relacion_id=relacion_cuentas.relacion_id) INNER JOIN public.cuentas cuentas ON relacion_cuentas_detalle.numerodecuenta=cuentas.numerodecuenta) LEFT OUTER JOIN public.fac_facturas fac_facturas ON ((relacion_cuentas_detalle.empresa_id=fac_facturas.empresa_id) AND (relacion_cuentas_detalle.prefijo=fac_facturas.prefijo)) AND (relacion_cuentas_detalle.factura_fiscal=fac_facturas.factura_fiscal)) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)) INNER JOIN public.terceros terceros ON (planes.tipo_tercero_id=terceros.tipo_id_tercero) AND (planes.tercero_id=terceros.tercero_id)
 WHERE  relacion_cuentas.relacion_id=$relacion_id
GROUP BY terceros.tipo_id_tercero, terceros.tercero_id, terceros.nombre_tercero
ORDER BY terceros.nombre_tercero");
		$cantidad_cuentas_cargadas = 0;
		$total_relacion = 0;	
		while ($row_t = fetch_object($relacion_tercero)) {
		 	
		 $mensaje_insert .= "<br><table align=CENTER width=90% border=1 cellspacing=0><tr> 
      <td width=50% bgcolor=WHITE><div align=left><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>Id Tercero: $row_t->tipo_id_tercero"." "."$row_t->tercero_id</font></strong></div></td>
	  
	  <td width=50% bgcolor=WHITE><div align=left><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>Nombre Tercero: $row_t->nombre_tercero</font></strong></div></td>
    </tr></table>";
    
    $mensaje_insert .= "<table align=CENTER width=90% border=1 cellspacing=0>
	<tr> 
    <td width=6%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Cuenta</font></strong></a> 
   </td>
    <td width=10%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Factura Fiscal</font></strong></a> 
   </td>
    <td width=38%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Paciente</font></strong></a> 
   </td>
    <td width=11%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Valor</font></strong></a> 
   </td>
   <td width=8%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Estado</font></strong></a> 
   </td>
   <td width=27%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Observacion</font></strong></a> 
   </td>
    </tr>";
	
			$relacion_detalle = execute_query($dbh, "SELECT cuentas.numerodecuenta, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, planes.plan_descripcion, relacion_cuentas_detalle.relacion_id, relacion_cuentas_detalle.prefijo, relacion_cuentas_detalle.factura_fiscal, relacion_cuentas_detalle.total_cuenta, relacion_cuentas_detalle.rel_det_id, relacion_cuentas_detalle.estado, relacion_cuentas_detalle.observacion
 FROM   (((public.relacion_cuentas_detalle relacion_cuentas_detalle INNER JOIN public.cuentas cuentas ON relacion_cuentas_detalle.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
WHERE  relacion_cuentas_detalle.relacion_id=$relacion_id
AND (planes.tipo_tercero_id = '$row_t->tipo_id_tercero' AND planes.tercero_id = '$row_t->tercero_id')
ORDER BY relacion_cuentas_detalle.rel_det_id");


			$total_relacion_t = 0;
			
		while ($row_d = fetch_object($relacion_detalle)) {
		 
		 	$total_relacion_t = $total_relacion_t +  $row_d->total_cuenta;
		 	
		 	if($row_d->estado == "R"){
				$estado_relacion = "Relacionada";
			}
			else if($row_d->estado == "E"){
				$estado_relacion = "Recibida";
			}
			else if($row_d->estado == "N"){
				$estado_relacion = "Rechazada";
			}
			else{
				
			}
		 $mensaje_insert .= "<tr bgcolor=WHITE> 
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$row_d->numerodecuenta</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$row_d->prefijo"." "."$row_d->factura_fiscal</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$row_d->tipo_id_paciente"." "."$row_d->paciente_id"." "."$row_d->primer_nombre"." "."$row_d->segundo_nombre"." "."$row_d->primer_apellido"." "."$row_d->segundo_apellido</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$".number_format ($row_d->total_cuenta)."</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$estado_relacion</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$row_d->observacion</font></td>
    
    
	</tr>";
	$cantidad_cuentas_cargadas= $cantidad_cuentas_cargadas+1;
		 
		 		}
	
		 		
	$mensaje_insert .= "<tr bgcolor=WHITE> 
    <td colspan=3><div align=right class=Estilo1><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = black><strong><u>Total Tercero:</u></strong></font></div></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif><strong>$". number_format ($total_relacion_t)."</strong></font></td>
	</tr></table>";
	
	$total_relacion = $total_relacion + $total_relacion_t;
		 
		 }
		 
		 
	$mensaje_insert .= "<BR><table align=CENTER width=90% border=1 cellspacing=0>
	<tr bgcolor=WHITE> 
    <td width=54%><div align=right class=Estilo1><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = black><strong><u>Total Relacion:</u></strong></font></div></td>
    <td width=11%><font size=1 face=Verdana, Arial, Helvetica, sans-serif><strong>$". number_format ($total_relacion)."</strong></font></td>
    <td width=35%></td>
	</tr>
	<tr bgcolor=WHITE> 
    <td width=54%><div align=right class=Estilo1><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = black><strong><u>Cantidad Cuentas:</u></strong></font></div></td>
    <td width=11%><font size=1 face=Verdana, Arial, Helvetica, sans-serif><strong>". $cantidad_cuentas_cargadas."</strong></font></td>
    <td width=35%></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>";
	echo $mensaje_insert;

?>
</body>
</html>
