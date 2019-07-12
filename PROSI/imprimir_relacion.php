
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

$consulta2 = "SELECT a.relacion_id, a.fecha_registro, b.nombre, a.departamento_entrega, a.departamento_recibe
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
		
		$mensaje_insert = "<table align=CENTER% width=100% border=0 cellspacing=0> <tr>
<td><center><align=CENTER% width=250 height=150>  
	
	</td>
  </tr>
  </table>
  <table align=CENTER% width=100% border=1 cellspacing=0>
  <tr> 
      <td colspan=5 bgcolor=WHITE><div align=left><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>RELACION: ".$row_c[0]." FECHA: ".$row_c[1]." USUARIO: ".$row_c[2]."</font></strong></div></td>
    </tr>
	<tr> 
      <td colspan=3 bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>Departamento Envia: ".$E."</font></strong></div></td>
	  
	  <td colspan=2 bgcolor=WHITE><div align=center><strong><font face=Verdana, Arial, Helvetica, sans-serif color=BLACK size=1>Departamento Recibe: ".$R."</font></strong></div></td>
    </tr>";
    
    	$mensaje_insert .= "<tr> 
    <td width=8%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Cuenta</font></strong></a> 
   </td>
    <td width=8%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Factura Fiscal</font></strong></a> 
   </td>
    <td width=12%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Valor</font></strong></a> 
   </td>
    <td width=33%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Paciente</font></strong></a> 
   </td>
    <td width=33%> 
      <strong><font color=BLACK size=1 face=Verdana, Arial, Helvetica, sans-serif>Plan</font></strong></a> 
   </td>
	</tr>";
			$relacion_detalle = execute_query($dbh, "SELECT cuentas.numerodecuenta, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, planes.plan_descripcion, relacion_cuentas_detalle.relacion_id, relacion_cuentas_detalle.prefijo, relacion_cuentas_detalle.factura_fiscal, relacion_cuentas_detalle.total_cuenta, relacion_cuentas_detalle.rel_det_id
 FROM   (((public.relacion_cuentas_detalle relacion_cuentas_detalle INNER JOIN public.cuentas cuentas ON relacion_cuentas_detalle.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
WHERE  relacion_cuentas_detalle.relacion_id=$row_c[0]
ORDER BY relacion_cuentas_detalle.rel_det_id");
			$total_relacion = 0;
		while ($row_d = fetch_object($relacion_detalle)) {
		 	$total_relacion = $total_relacion +  $row_d->total_cuenta;
		 $mensaje_insert .= "<tr bgcolor=WHITE> 
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$row_d->numerodecuenta</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$row_d->prefijo"." "."$row_d->factura_fiscal</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$row_d->total_cuenta</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$row_d->tipo_id_paciente"." "."$row_d->paciente_id"." "."$row_d->primer_nombre"." "."$row_d->segundo_nombre"." "."$row_d->primer_apellido"." "."$row_d->segundo_apellido</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif>$row_d->plan_descripcion</font></td>
	</tr>";
		 
		 }
		 
	$mensaje_insert .= "<tr bgcolor=WHITE> 
    <td colspan=2><div align=center class=Estilo1><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color><strong><u>Total Relacion:</u></strong></font></div></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif><strong>$". number_format ($total_relacion)."</strong></font></td>
	<td colspan=3><strong><font size=1 face=Verdana, Arial, Helvetica, sans-serif></font></strong></td>
	</tr></table><br>";
	echo $mensaje_insert;

?>
</body>
</html>
