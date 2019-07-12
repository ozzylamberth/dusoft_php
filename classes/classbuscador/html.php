<?php
function abre_html(){

echo "<TITLE>Buscador General</TITLE>";
echo "<HEAD>";
echo "</HEAD>";
echo ("<BODY bgcolor=\"#FFFFFF\">
  <div align=\"center\"><br>
    <br>
    <table width=\"100%\" border=\"0\">
      <tr>
				<form name=\"datos1\" method=\"post\" action=\"buscador.php\">
        <td width=\"20%\"><div align=\"center\"><strong>Codigo </strong></div></td>
        <td width=\"70%\" width=\"212\" height=\"36\"> <input type=\"text\" align=\"center\" size =\"40\" name=\"buscar\"></td>
        <td width=\"10%\" width=\"61\" height=\"36\"> <input type=\"submit\" name=\"buton\"  value=\"Buscar\"></td>
      </tr>
			</form>
      <tr>
				<form name=\"datos\" method=\"post\" action=\"buscador.php\">
        <td width=\"20%\"><div align=\"center\"><strong>Nombre </strong></div></td>
        <td width=\"70%\" height=\"36\"><input type=\"text\" align=\"center\" size =\"40\" name=\"buscar2\"></td>
        <td width=\"10%\" height=\"36\"><input type=\"submit\" name=\"buton2\"  value=\"Buscar\"></td>
      </tr>
    </table>");
    }

function abre_html1()
{
$vector=$bodegas=array();
$bodegas=$_SESSION['bodegas'];

echo "<TITLE>Buscador General</TITLE>";
echo "<HEAD>";
echo "</HEAD>";
echo "<BODY bgcolor=\"#FFFFFF\">
  <div align=\"center\"><br>
    <br>
		<form name=\"datos\" method=\"post\" action=\"buscador.php\">";
echo "<table width=\"100%\" border=\"0\" align='center'>\n
				<tr><td colspan='3'>\n";

	if ($bodegas)
	{
		if (sizeof($bodegas)>1)
		{

			echo "<table width=\"100%\" border=\"0\" align='center'>
						<tr>
						<td colspan='3' align='center'>
							<table width='50%' border='1'>
								<tr>
									<td colspan='2' align='center'>BODEGAS</td>";
			for ($i=0;$i<sizeof($bodegas);$i++)
			{
				echo "	<tr><td width='95%'>".$bodegas[$i]['descripcion']."</td>\n";
				if (!$i)
					echo "		<td width='5%'><input type='radio' name='BBodega' value='".$bodegas[$i]['bodega']."' checked='true'></td>\n</tr>\n";
				else
					echo "		<td width='5%'><input type='radio' name='BBodega' value='".$bodegas[$i]['bodega']."'></td>\n</tr>\n";
			}
			echo "			</td>
								</tr>
							</table>
						</td>
						</tr>
						</table>";
		}
		else
		{
				echo "<input type='hidden' name='BBodega' value='".$bodegas[0]['bodega']."' ></td>\n</tr>\n";
		}
	}
echo "</td></tr><tr>
				<td width=\"20%\"><div align=\"center\"><strong>Codigo </strong></div></td>
				<td width=\"70%\" height=\"36\"> <input type=\"text\" align=\"center\" size =\"40\" name=\"buscar\" onchange=\"control(this.name)\"></td>
				<td width=\"10%\" height=\"36\"> <input type=\"submit\" name=\"buton\"  value=\"Buscar\"></td>
		</tr>
		<tr>
				<td width=\"20%\"><div align=\"center\"><strong>Nombre </strong></div></td>
				<td width=\"70%\" height=\"36\"> <input type=\"text\" align=\"center\" size =\"40\" name=\"buscar2\" onchange=\"control(this.name)\"></td>
				<td width=\"10%\" height=\"36\"> <input type=\"submit\" name=\"buton\"  value=\"Buscar\"></td>
		</tr>
		</table>";
}


function cierra_html(){

echo ("</div>
</form>");
}

?>
