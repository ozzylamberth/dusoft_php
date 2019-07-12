<?php
$VISTA='HTML';
include 'includes/enviroment.inc.php';
include('conexion.php'); 
    if (!$dbconn) {
    echo "No hay Conexion.\n";
    exit;
}

$result=pg_exec($dbconn, "select distinct(t.tercero_id), t.nombre_tercero, count(*)
from cuentas_liquidaciones_qx a, terceros t, cuentas c, planes p
where 
a.numerodecuenta = c.numerodecuenta
and c.estado not in ('4','5')
and c.plan_id = p.plan_id
and (p.tipo_tercero_id = t.tipo_id_tercero and p.tercero_id = t.tercero_id)
and a.fecha_cirugia>= '2007-06-01 00:00:00' AND a.fecha_cirugia<'2007-07-01 00:00:00' AND 

a.estado<>'3'
and a.departamento = '020301'
group by 1,2
ORDER BY 3 Desc
"); 

if (!$result) {
    echo "Error en Consulta.\n";
    exit;
}

echo "<table border= '1' CELLSPACING='2' CELLPADDING='3' BORDERCOLOR='#FFFFFF' BGCOLOR='#01D359' align=center width='995'>
  <TR align='center'> 
    <TD width='79'><B><font size='5' face='Arial, Helvetica, sans-serif'>N. IDENTIFICACION</font></B></TD>
    <TD width='288'><B><font size='5' face='Arial, Helvetica, sans-serif'>NOMBRE</font></B></TD>
    <TD width='288'><B><font size='5' face='Arial, Helvetica, sans-serif'>CANTIDAD</font></B></TD>
  </TR>";

while($fetch = pg_fetch_row($result)) {

   if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="SKYBLUE";
       $colorfila=0;
    }

    echo "

  <tr align='center'> 
    <td bgcolor='#F0F0F0' width='79'><font face='Arial, Helvetica, sans-serif' size='5'>".$fetch[0]."</font></td>
    <td bgcolor='#F0F0F0' width='288'><font face='Arial, Helvetica, sans-serif' size='5'>".$fetch[1]."</font></td>
    <td bgcolor='#F0F0F0' width='288'><font face='Arial, Helvetica, sans-serif' size='5'>".$fetch[2]."</font></td>
  </tr>";
}
echo "</table>";

?>