<HTML>
<BODY>
<FORM ACTION='ins_prueba.php' METHOD='POST' NAME='Comentario'>
<TABLE BORDER=1>
<TR>
<TD>
<TABLE BORDER=0 CELLPADING=2 CELLSPACING=2>
<TR>
<TD>Tarifario:</TD>
<TD><INPUT TYPE="TEXT" NAME="tarifario_id" VALUE=""></TD>
</TR>
<TR>
<TD>Cargo:</TD>
<TD><?php echo"<INPUT TYPE=TEXT SIZE=19 NAME='cargo' VALUE='$cargo'>";?></TD>
</TR>
<TR>  <TD><B><font face='Times New Roman' size='2'>Tarifario : </B></TD></font>
 <TD>
<?php  
include "conexion.php";

print ("
<select name=\"tarifario_id\"  onchange=\"submit();\">
");


print ("<option selected>Seleccione opcion</option>");


$sql="SELECT  *
FROM  tarifarios
";
$res=pg_query($sql);

while($fila=pg_fetch_array($res)){
print("<option value=\"$fila[tarifario_id]\"  ");
if ($fila[tarifario_id] == $tarifario_id) {
print ("selected");
}

print(">$fila[descripcion]</option>\n
");
}
print("</select>");
?>

</td>
  </tr>
  </FORM>
<TR>
<FORM ACTION='ins_prueba1.php' METHOD='POST' >
<?php
echo"
<input type=hidden name='tarifario_id' value='$tarifario_id'>
<input type=hidden name='cargo' value='$cargo'>";
?>
<TD COLSPAN=2 ALIGN=CENTER>
<INPUT TYPE="SUBMIT" VALUE="Entrar">
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
</FORM>
</BODY>
</HTML>