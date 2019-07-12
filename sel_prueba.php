<?php
$VISTA='HTML';
include 'includes/enviroment.inc.php';
include('conexion.php'); 
    if (!$dbconn) {
    echo "No hay Conexion.\n";
    exit;
}

$result=pg_exec($dbconn, "SELECT * FROM tmp_colsanitas2007_equivalencias"); 

if (!$result) {
    echo "Error en Consulta.\n";
    exit;
}

echo "<table border= '1' CELLSPACING='2' CELLPADDING='3' BORDERCOLOR='#FFFFFF' BGCOLOR='#01D359' align=center width='995'>
  <TR align='center'> 
    <TD width='79'><B><font size='5' face='Arial, Helvetica, sans-serif'>TARIFARIO</font></B></TD>
    <TD width='288'><B><font size='5' face='Arial, Helvetica, sans-serif'>CARGO</font></B></TD>
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
  </tr>";
}
echo "</table>";

?>