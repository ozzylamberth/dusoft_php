<?

	function gui_theme_menu_acceso($titulo,$campos,$datos,$url,$volver='')
	{
		$salida="";
		$salida.="<center>\n";
		if (!empty($datos)){
				switch (sizeof($campos))
				{
					case 5:
									$salida.=ThemeMenuAbrirTabla($titulo,"50%");
									foreach ($datos as $key => $value)
									{
										$salida.="	<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"12\">";
										$salida.="		<tr>";
										$salida.="			<td>\n";
										$salida.="				<table border='0' width='0' cellspacing=\"0\" cellpadding=\"0\">\n";
										$salida.="					<tr>\n";
										$salida.="						<td class='normal_10N'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;$campos[0]&nbsp;-&nbsp;</td>\n";
										$salida.="						<td class='normal_10_menu'>[&nbsp;$key&nbsp;]</td>\n";
										$salida.="					</tr>";
										$salida.="				</table>";
										foreach ($value as $key1 => $valor)
										{
											$salida.="	<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"12\">";
											$salida.="		<tr>";
											$salida.="			<td>\n";
											$salida.="				<table border='0' width='0' cellspacing=\"0\" cellpadding=\"0\">\n";
											$salida.="					<tr>\n";
											$salida.="						<td class='normal_10N'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;$campos[1]&nbsp;-&nbsp;</td>\n";
											$salida.="						<td class='normal_10_menu'>[&nbsp;$key1&nbsp;]</td>\n";
											$salida.="					</tr>";
											$salida.="				</table>";

											foreach ($valor as $key2 => $valor1)
											{
												$salida.="	<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"12\">";
												$salida.="		<tr>";
												$salida.="			<td>\n";
												$salida.="				<table border='0' width='0' cellspacing=\"0\" cellpadding=\"0\">\n";
												$salida.="					<tr>\n";
												$salida.="						<td class='normal_10N'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;$campos[2]&nbsp;-&nbsp;</td>\n";
												$salida.="						<td class='normal_10_menu'>[&nbsp;$key2&nbsp;]</td>\n";
												$salida.="					</tr>";
												$salida.="				</table>";

												foreach ($valor1 as $key3 => $valor2)
												{
													$salida.="	<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"12\">";
													$salida.="		<tr>";
													$salida.="			<td>\n";
													$salida.="				<table border='0' width='0' cellspacing=\"0\" cellpadding=\"0\">\n";
													$salida.="					<tr>\n";
													$salida.="						<td class='normal_10N'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;$campos[3]&nbsp;-&nbsp;</td>\n";
													$salida.="						<td class='normal_10_menu'>[&nbsp;$key3&nbsp;]</td>\n";
													$salida.="					</tr>";
													$salida.="				</table>";
													foreach ($valor2 as $key4 => $valor3)
													{
														$href = ModuloGetURL($url[0],$url[1],$url[2],$url[3],array($url[4]=>$valor3));
														$salida.=ThemeSubMenuTabla("<a href=\"".$href."\">$key4</a>","100%");
													}
													$salida.="			</td>";
													$salida.="		</tr>";
													$salida.="	</table>";
												}
												$salida.="			</td>";
												$salida.="		</tr>";
												$salida.="	</table>";
											}
											$salida.="			</td>";
											$salida.="		</tr>";
											$salida.="	</table>";
										}
										$salida.="			</td>";
										$salida.="		</tr>";
										$salida.="	</table>";
									}//fin foreach
									if(!empty($volver))
									{
										$salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">";
										$salida.="  <tr>";
										$salida.="  <td align='center' class=\"label_error\">\n";
										$salida.="  <a href='$volver'>VOLVER</a>";
										$salida.="  </td>";
										$salida.="  </tr>";
										$salida.="  </table>";
									}
									$salida.=ThemeMenuCerrarTabla();
					break;
					case 4:
									$salida.=ThemeMenuAbrirTabla($titulo,"50%");
									foreach ($datos as $key => $value)
									{
										$salida.="	<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"12\">";
										$salida.="		<tr>";
										$salida.="			<td>\n";
										$salida.="				<table border='0' width='0' cellspacing=\"0\" cellpadding=\"0\">\n";
										$salida.="					<tr>\n";
										$salida.="						<td class='normal_10N'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;$campos[0]&nbsp;-&nbsp;</td>\n";
										$salida.="						<td class='normal_10_menu'>[&nbsp;$key&nbsp;]</td>\n";
										$salida.="					</tr>";
										$salida.="				</table>";
										foreach ($value as $key1 => $valor)
										{
											$salida.="	<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"12\">";
											$salida.="		<tr>";
											$salida.="			<td>\n";
											$salida.="				<table border='0' width='0' cellspacing=\"0\" cellpadding=\"0\">\n";
											$salida.="					<tr>\n";
											$salida.="						<td class='normal_10N'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;$campos[1]&nbsp;-&nbsp;</td>\n";
											$salida.="						<td class='normal_10_menu'>[&nbsp;$key1&nbsp;]</td>\n";
											$salida.="					</tr>";
											$salida.="				</table>";
											foreach ($valor as $key2 => $valor1)
											{
												$salida.="	<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"12\">";
												$salida.="		<tr>";
												$salida.="			<td>\n";
												$salida.="				<table border='0' width='0' cellspacing=\"0\" cellpadding=\"0\">\n";
												$salida.="					<tr>\n";
												$salida.="						<td class='normal_10N'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;$campos[2]&nbsp;-&nbsp;</td>\n";
												$salida.="						<td class='normal_10_menu'>[&nbsp;$key2&nbsp;]</td>\n";
												$salida.="					</tr>";
												$salida.="				</table>";
												foreach ($valor1 as $key3 => $valor2)
												{
													$href = ModuloGetURL($url[0],$url[1],$url[2],$url[3],array($url[4]=>$valor2));
													$salida.=ThemeSubMenuTabla("<a href=\"".$href."\">$key3</a>","100%");
												}
												$salida.="			</td>";
												$salida.="		</tr>";
												$salida.="	</table>";
											}
											$salida.="			</td>";
											$salida.="		</tr>";
											$salida.="	</table>";
										}
										$salida.="			</td>";
										$salida.="		</tr>";
										$salida.="	</table>";
									}//fin foreach
									if(!empty($volver))
									{
										$salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">";
										$salida.="  <tr>";
										$salida.="  <td align='center' class=\"label_error\">\n";
										$salida.="  <a href='$volver'>VOLVER</a>";
										$salida.="  </td>";
										$salida.="  </tr>";
										$salida.="  </table>";
									}
									$salida.=ThemeMenuCerrarTabla();
					break;
					case 3:
									$salida.=ThemeMenuAbrirTabla($titulo,"50%");
									foreach ($datos as $key => $value)
									{
										$salida.="	<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"12\">";
										$salida.="		<tr>";
										$salida.="			<td>\n";
										$salida.="				<table border='0' width='0' cellspacing=\"0\" cellpadding=\"0\">\n";
										$salida.="					<tr>\n";
										$salida.="						<td class='normal_10N'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;$campos[0]&nbsp;-&nbsp;</td>\n";
										$salida.="						<td class='normal_10_menu'>[&nbsp;$key&nbsp;]</td>\n";
										$salida.="					</tr>";
										$salida.="				</table>";
										foreach ($value as $key1 => $valor)
										{
											$salida.="	<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"12\">";
											$salida.="		<tr>";
											$salida.="			<td>\n";
											$salida.="				<table border='0' width='0' cellspacing=\"0\" cellpadding=\"0\">\n";
											$salida.="					<tr>\n";
											$salida.="						<td class='normal_10N'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;$campos[1]&nbsp;-&nbsp;</td>\n";
											$salida.="						<td class='normal_10_menu'>[&nbsp;$key1&nbsp;]</td>\n";
											$salida.="					</tr>";
											$salida.="				</table>";
											foreach ($valor as $key2 => $valor1)
											{
												$href = ModuloGetURL($url[0],$url[1],$url[2],$url[3],array($url[4]=>$valor1));
												$salida.=ThemeSubMenuTabla("<a href=\"".$href."\">$key2</a>","100%");
											}
											$salida.="			</td>";
											$salida.="		</tr>";
											$salida.="	</table>";
										}
										$salida.="			</td>";
										$salida.="		</tr>";
										$salida.="	</table>";
									}//fin foreach
									if(!empty($volver))
									{
										$salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">";
										$salida.="  <tr>";
										$salida.="  <td align='center' class=\"label_error\">\n";
										$salida.="  <a href='$volver'>VOLVER</a>";
										$salida.="  </td>";
										$salida.="  </tr>";
										$salida.="  </table>";
									}
									$salida.=ThemeMenuCerrarTabla();
					break;
					case 2:
									$salida.=ThemeMenuAbrirTabla($titulo,"50%");
									foreach ($datos as $key => $value)
									{
										$salida.="	<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"12\">";
										$salida.="		<tr>";
										$salida.="			<td>\n";
										$salida.="				<table border='0' width='0' cellspacing=\"0\" cellpadding=\"0\">\n";
										$salida.="					<tr>\n";
										$salida.="						<td class='normal_10N'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;$campos[0]&nbsp;-&nbsp;</td>\n";
										$salida.="						<td class='normal_10_menu'>[&nbsp;$key&nbsp;]</td>\n";
										$salida.="					</tr>";
										$salida.="				</table>";
										foreach ($value as $key1 => $valor)
										{
											$href = ModuloGetURL($url[0],$url[1],$url[2],$url[3],array($url[4]=>$valor));
											$salida.=ThemeSubMenuTabla("<a href=\"".$href."\">$key1</a>","100%");
										}
										$salida.="			</td>";
										$salida.="		</tr>";
										$salida.="	</table>";
									}//fin foreach
									if(!empty($volver))
									{
										$salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">";
										$salida.="  <tr>";
										$salida.="  <td align='center' class=\"label_error\">\n";
										$salida.="  <a href='$volver'>VOLVER</a>";
										$salida.="  </td>";
										$salida.="  </tr>";
										$salida.="  </table>";
									}
									$salida.=ThemeMenuCerrarTabla();
					break;
					case 1:
									$salida.=ThemeMenuAbrirTabla($titulo,"50%");
									$salida.="	<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"12\">";
									$salida.="		<tr>";
									$salida.="			<td>\n";
									$salida.="				<table border='0' width='0' cellspacing=\"0\" cellpadding=\"0\">\n";
									$salida.="					<tr>\n";
									$salida.="						<td class='normal_10N'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;$campos[0]</td>\n";
									$salida.="					</tr>";
									$salida.="				</table>";
									foreach ($datos as $key => $value)
									{
										$href = ModuloGetURL($url[0],$url[1],$url[2],$url[3],array($url[4]=>$value));
										$salida.=ThemeSubMenuTabla("<a href=\"".$href."\">$key</a>","100%");
									}//fin foreach
									$salida.="			</td>";
									$salida.="		</tr>";
									$salida.="	</table>";
									if(!empty($volver))
									{
										$salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">";
										$salida.="  <tr>";
										$salida.="  <td align='center' class=\"label_error\">\n";
										$salida.="  <a href='$volver'>VOLVER</a>";
										$salida.="  </td>";
										$salida.="  </tr>";
										$salida.="  </table>";
									}
									$salida.=ThemeMenuCerrarTabla();
					break;
				}
			$salida.="</center>\n";
			return $salida;
		}
		else {
			$salida .= themeAbrirTabla($titulo);
			$salida .= "<div class='titulo3' align='center'><br><br><b>EL USUARIO NO TIENE PERMISOS EN ESTE MODULO.</b><br><br></div>";
			$salida .= themeCerrarTabla();
			return $salida;
		}
	}


function ThemeMsgOut($msg,$detalle='')
{
	$Salida ="<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">\n<title>" . _SIIS_APLICATION_TITLE . "</title>\n";
	$Salida.="<link href=\"themes/HTML/AzulXp/style/style.css\" rel=\"stylesheet\" type=\"text/css\"></head>\n";
	$Salida.="<body bgcolor=\"#f5f5ff\">\n<center><br><br>\n";
	$Salida.=ThemeAbrirTabla("MENSAJE DEL SISTEMA","50%");
	$Salida.="<div align=\"center\" class='normal_11N'>";
	$Salida.="<h1><b>" . _SIIS_APLICATION_TITLE . "</b></h1>\n";
	$Salida.="<h2><b><font color='red'>$msg</font></b></h2>\n";
	$Salida.="$detalle\n<br>\n";
	$Salida.="<h5>Esta aplicación es desarrollada por:</h5><br />"._SIIS_DEVELOPER_LINK."<br />";
	$Salida.="</div>";
	$Salida.=ThemeCerrarTabla();
	$Salida.="<br><br></center>\n</body>\n</html>";
	return $Salida;
}

function ConfirmarAccion($titulo='', $mensaje='',$boton1='',$boton2='',$accion1=array(), $accion2=array())
{
  list($contenedor,$modulo,$tipo,$metodo,$argumentos)=$accion1;
  $accion1 = ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
  list($contenedor,$modulo,$tipo,$metodo,$argumentos)=$accion2;
  $accion2 = ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
	$salida.=ThemeAbrirTabla($titulo,"90%");
  $salida .= "	<table width=\"60%\" align=\"center\">";
  $salida .= "	  <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
  $salida .= "		<tr>";
  $salida .= "     <form name=\"formabuscar\" action=\"$accion1\" method=\"post\">";
  $salida .= "		 <td align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Boton1\" value=\"$boton1\"></form></td>";
  $salida .= "     <form name=\"formabuscar\" action=\"$accion2\" method=\"post\">";
  $salida .= "		 <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Boton2\" value=\"$boton2\"></form></td>";
  $salida .= "		<tr>";
  $salida .= "	</table>";
	$salida.=ThemeCerrarTabla();
	$salida.="<br><br>";
  return $salida;
}

?>
