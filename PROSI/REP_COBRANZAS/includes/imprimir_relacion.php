<html>
<head>
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<?php 

$consulta2 = "SELECT a.relacion_id, a.fecha_registro, b.nombre
 				FROM   relacion_cuentas a, system_usuarios b
 				WHERE  a.relacion_id = $row->nextval
 				AND a.usuario_entrega = b.usuario_id
 				ORDER BY a.relacion_id";
    			$resultado2 = execute_query($dbh, $consulta2);
       			$row_c = pg_fetch_row($resultado2);
       			
      			free_result($resultado2);
		
		$mensaje_insert = "<table align=CENTER% width=100% border=0 cellspacing=0> <tr> 
      <td  background=imagenes/cellpic1.gif colspan=5 bgcolor=#EEEEEE><div align=left><strong><font color=#003366 size=1>RELACION: ".$row_c[0]." FECHA: ".$row_c[1]." USUARIO: ".$row_c[2]."</font></strong></div></td>
    </tr>";
    
    	$mensaje_insert .= "<tr> 
    <td width=8% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Cuenta</font></strong></a> 
   </td>
    <td width=8% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Factura Fiscal</font></strong></a> 
   </td>
    <td width=12% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Valor</font></strong></a> 
   </td>
    <td width=33% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Paciente</font></strong></a> 
   </td>
    <td width=33% background=imagenes/cellpic1.gif> 
      <strong><font color=#003366 size=1 face=Verdana, Arial, Helvetica, sans-serif>Plan</font></strong></a> 
   </td>
	</tr>";
			$relacion_detalle = execute_query($dbh, "SELECT cuentas.numerodecuenta, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, planes.plan_descripcion, relacion_cuentas_detalle.relacion_id, relacion_cuentas_detalle.prefijo, relacion_cuentas_detalle.factura_fiscal, relacion_cuentas_detalle.total_cuenta, relacion_cuentas_detalle.rel_det_id
 FROM   (((public.relacion_cuentas_detalle relacion_cuentas_detalle INNER JOIN public.cuentas cuentas ON relacion_cuentas_detalle.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
WHERE  relacion_cuentas_detalle.relacion_id=$row_c[0]
ORDER BY relacion_cuentas_detalle.rel_det_id");
		while ($row_d = fetch_object($relacion_detalle)) {
		 
		 $mensaje_insert .= "<tr bgcolor=#EEEEEE> 
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->numerodecuenta</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->prefijo"." "."$row_d->factura_fiscal</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->total_cuenta</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->tipo_id_paciente"." "."$row_d->paciente_id"." "."$row_d->primer_nombre"." "."$row_d->segundo_nombre"." "."$row_d->primer_apellido"." "."$row_d->segundo_apellido</font></td>
    <td><font size=1 face=Verdana, Arial, Helvetica, sans-serif font color = $color>$row_d->plan_descripcion</font></td>
	</tr>";
		 
		 }
		 
	$mensaje_insert .= "</table><br>";


?>
</body>
</html>
