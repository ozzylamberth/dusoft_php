<html>
<script type="text/javascript"><!--
// IE4+, Firefox, Netscape 6+, Opera 6+
function disable(cbx) {
  if(!cbx) return;
  var frm = cbx.form;
  for(var i=0;i<frm.elements.length;i++) {
    if(frm.elements[i].type && (frm.elements[i] != cbx)) {
      if(frm.elements[i].type.toLowerCase() == 'checkbox') {
       frm.elements[i].checked = true;
      }
    }
  }
}
// -->
</script>


<?php




procesar_entrada("GET", "id", "action", "action1", "departamento_entrega", "departamento_recibe", "headitem");


$departamento_entrega = get_value($_GET["departamento_entrega"], "C");
$departamento_recibe = get_value($_GET["departamento_recibe"], "C");
$headitem = get_value($_GET["headitem"], "C");
$action1 = get_value($_GET["action1"], "C");

ECHO $headitem;

echo $action1;

/*$relacion_maestro = execute_query($dbh, "SELECT a.relacion_id, a.fecha_registro, b.nombre
 		FROM   relacion_cuentas a, system_usuarios b
 		WHERE  a.departamento_entrega = '$departamento_entrega' AND a.departamento_recibe = '$departamento_recibe'
 		AND a.usuario_entrega = b.usuario_id
 		ORDER BY a.relacion_id");
		while ($row_m = fetch_object($relacion_maestro)) {
	 
	 ?>
	 
    <table align="CENTER%" width="100%" border="0" cellspacing="0">
    


    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="5" bgcolor="#EEEEEE"><div align="left"><strong><font color="#003366" size="1">RELACION:<?php echo $row_m->relacion_id;?> FECHA:<?php echo $row_m->fecha_registro;?> USUARIO:<?php echo $row_m->nombre;?></font></strong></div></td>
      <td background="imagenes/cellpic1.gif" bgcolor="#EEEEEE"><div align="center">
      <input type="checkbox" name="head" onclick="disable(this)">
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
    	$relacion_detalle = execute_query($dbh, "SELECT cuentas.numerodecuenta, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_nombre, pacientes.segundo_nombre, pacientes.primer_apellido, pacientes.segundo_apellido, planes.plan_descripcion, relacion_cuentas_detalle.relacion_id, relacion_cuentas_detalle.prefijo, relacion_cuentas_detalle.factura_fiscal, relacion_cuentas_detalle.total_cuenta
 FROM   (((public.relacion_cuentas_detalle relacion_cuentas_detalle INNER JOIN public.cuentas cuentas ON relacion_cuentas_detalle.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)
WHERE  relacion_cuentas_detalle.relacion_id=$row_m->relacion_id
ORDER BY relacion_cuentas_detalle.rel_det_id");
		while ($row_d = fetch_object($relacion_detalle)) {
		 ?>
 <tr bgcolor="#EEEEEE"> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row_d->numerodecuenta?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row_d->prefijo." ".$row_d->factura_fiscal?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row_d->total_cuenta?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row_d->tipo_id_paciente." ".$row_d->paciente_id." ".$row_d->primer_nombre." ".$row_d->segundo_nombre." ".$row_d->primer_apellido. " ".$row_d->segundo_apellido?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row_d->plan_descripcion?></font></td>
    <td><div align="center">
    	<?php echo "<form method=\"POST\" action=\"formulario.php?action1=add&amp;departamento_entrega=$departamento_entrega&amp;departamento_recibe=$departamento_recibe\">";?>
      <input type="checkbox" name="headitem[]" value="<?php echo $row_d->rel_det_id.":".$row_d->relacion_id;?>">
      </div></td>
    </tr>
    
 <?php
 }

echo 	"<input type=\"hidden\" name=\"departamento_entrega\" value=\"$departamento_entrega\">";
		"<input type=\"hidden\" name=\"departamento_recibe\" value=\"$departamento_recibe\">";
 		echo "<tr bgcolor=#EEEEEE>
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

?>
</form>
 </table>   
    
    <?php
    }*/
?>
