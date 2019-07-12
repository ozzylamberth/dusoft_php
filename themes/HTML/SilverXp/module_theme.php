<?php

function ThemeAbrirTabla($titulo,$porcentaje,$align){
	$salida.="<center>\n";
	switch ($align){
		case "L":  $clase="titulo_tabla_l";  break;
		case "R":  $clase="titulo_tabla_r";  break;
		default:  $clase="titulo_tabla";  break;
	}
	if (!$porcentaje){
		$salida .= "<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"tabla\">\n";
	}
	else{
		$salida .= "<TABLE width=\"$porcentaje\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"tabla\">\n";
	}
		$salida .= "	<TR>\n";
		$salida .= "		<TD>\n";
		$salida .= "			<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"22\">\n";
		$salida .= "				<TR>\n";
		$salida .= "					<TD width=\"6\" height=\"30\"><IMG src=\"". GetThemePath() ."/images/curva_izq_tabla.png\" width=\"6\" height=\"30\"></TD>\n";
		$salida .= "					<TD background=\"". GetThemePath() ."/images/franja_tabla.png\" width=\"100%\" height=\"30\" rowspan=\"2\" class='$clase'>$titulo</TD>\n";
		$salida .= "					<TD width=\"8\" height=\"30\"><IMG src=\"". GetThemePath() ."/images/curva_der_tabla.png\" width=\"8\" height=\"30\"></TD>\n";
		$salida .= "				</TR>\n";
		$salida .= "			</TABLE>\n";
		$salida .= "		</TD>\n";
		$salida .= "	</TR>\n";
		$salida .= "	<TR>\n";
		$salida .= "		<TD>\n";
		$salida .= "			<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		$salida .= "				<TR>\n";
		$salida .= "					<TD width=\"4\" bgcolor=\"#636B70\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></TD>\n";
		$salida .= "					<TD align=\"center\">\n";
		$salida .= "						<TABLE width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		$salida .= "							<TR>\n";
		$salida .= "								<TD align=\"center\" valign=\"middle\" width=\"10\"></TD>\n";
		$salida .= "								<TD valign=\"top\" height=\"50\" class=\"Contenido\"><br><br>\n";
	return $salida;
}


function ThemeCerrarTabla(){
	$salida .= "								<br><br></TD>\n";
	$salida .= "								<TD align=\"center\" valign=\"middle\" width=\"10\"></TD>\n";
	$salida .= "							</TR>\n";
	$salida .= "							<TR>\n";
	$salida .= "								<TD background=\"". GetThemePath() ."/images/punto.png\" colspan=\"2\" height=\"1\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></TD>\n";
	$salida .= "							</TR>\n";
	$salida .= "						</TABLE>\n";
	$salida .= "					</TD>\n";
	$salida .= "					<TD width=\"4\" bgcolor=\"#636B70\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></TD>\n";
	$salida .= "				</TR>\n";
	$salida .= "			</TABLE>\n";
	$salida .= "		</TD>\n";
	$salida .= "	</TR>\n";
	$salida .= "	<TR>\n";
	$salida .= "		<TD>\n";
	$salida .= "			<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	$salida .= "				<TR>\n";
	$salida .= "					<TD width=\"100%\" height=\"4\" bgcolor=\"#636B70\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></TD>\n";
	$salida .= "				</TR>\n";
	$salida .= "			</TABLE>\n";
	$salida .= "		</TD>\n";
	$salida .= "	</TR>\n";
	$salida .= "</TABLE>\n";
	$salida .="</center>\n";

	return $salida;
}

function ThemeMenuAbrirTabla($titulo,$porcentaje,$align){
	$salida .="<center>\n";
	switch ($align){
		case "C":  $clase="titulo_tabla_menu";  break;
		case "R":  $clase="titulo_tabla_menu_r";  break;
		default:  $clase="titulo_tabla_menu_l";  break;
	}
	if (!$porcentaje){
		$salida .= "<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	}
	else{
		$salida .= "<TABLE width=\"$porcentaje\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\n";
	}
		$salida .= "	<TR>\n";
		$salida .= "		<TD>\n";
		$salida .= "			<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"22\">\n";
		$salida .= "				<TR>\n";
		$salida .= "					<TD width=\"6\" height=\"30\"><IMG src=\"". GetThemePath() ."/images/curva_izq_tabla.png\" width=\"6\" height=\"30\"></TD>\n";
		$salida .= "					<TD background=\"". GetThemePath() ."/images/franja_tabla.png\" width=\"100%\" height=\"30\" rowspan=\"2\" class='$clase'>$titulo</TD>\n";
		$salida .= "					<TD width=\"8\" height=\"30\"><IMG src=\"". GetThemePath() ."/images/curva_der_tabla.png\" width=\"8\" height=\"30\"></TD>\n";
		$salida .= "				</TR>\n";
		$salida .= "			</TABLE>\n";
		$salida .= "		</TD>\n";
		$salida .= "	</TR>\n";
		$salida .= "	<TR>\n";
		$salida .= "		<TD>\n";
		$salida .= "			<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class='tabla_menu'>\n";
		$salida .= "				<TR>\n";
		$salida .= "					<TD width=\"4\" bgcolor=\"#636B70\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></TD>\n";
		$salida .= "					<TD align=\"center\">\n";
		$salida .= "						<TABLE width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class='tabla_menu'>\n";
		$salida .= "							<TR>\n";
		$salida .= "								<TD align=\"center\" valign=\"middle\" width=\"10\"></TD>\n";
		$salida .= "								<TD valign=\"top\" height=\"50\" class=\"Contenido\"><br>\n";
	return $salida;
}


function ThemeMenuCerrarTabla(){
	$salida .= "								<br><br></TD>\n";
	$salida .= "								<TD align=\"center\" valign=\"middle\" width=\"10\"></TD>\n";
	$salida .= "							</TR>\n";
	$salida .= "							<TR>\n";
	$salida .= "								<TD background=\"". GetThemePath() ."/images/punto.png\" colspan=\"2\" height=\"1\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></TD>\n";
	$salida .= "							</TR>\n";
	$salida .= "						</TABLE>\n";
	$salida .= "					</TD>\n";
	$salida .= "					<TD width=\"4\" bgcolor=\"#636B70\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></TD>\n";
	$salida .= "				</TR>\n";
	$salida .= "			</TABLE>\n";
	$salida .= "		</TD>\n";
	$salida .= "	</TR>\n";
	$salida .= "	<TR>\n";
	$salida .= "		<TD>\n";
	$salida .= "			<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	$salida .= "				<TR>\n";
	$salida .= "					<TD width=\"100%\" height=\"4\" bgcolor=\"#636B70\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></TD>\n";
	$salida .= "				</TR>\n";
	$salida .= "			</TABLE>\n";
	$salida .= "		</TD>\n";
	$salida .= "	</TR>\n";
	$salida .= "</TABLE>\n";
	$salida .="</center>\n";

	return $salida;
}


function ThemeSubMenuTabla($titulo,$porcentaje){

	if (!$porcentaje){
		$salida .= "<TABLE width=\"100%\"  height=\"25\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	}
	else{
		$salida .= "<TABLE width=\"$porcentaje\"  height=\"25\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\n";
	}
	$salida .= "	<TR>\n";
	$salida .= "		<TD>\n";
	$salida .= "			<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"100%\">\n";
	$salida .= "				<TR>\n";
	$salida .= "					<TD><IMG src=\"". GetThemePath() ."/images/submenu/borde_izq.png\" width=\"14\" height=\"25\"></TD>\n";
	$salida .= "					<TD height=\"25\" background=\"". GetThemePath() ."/images/submenu/franja.png\" width=\"100%\" height=\"25\" class='titulo_tabla_submenu'>$titulo</TD>\n";
	$salida .= "					<TD><IMG src=\"". GetThemePath() ."/images/submenu/borde_der.png\" width=\"14\" height=\"25\"></TD>\n";
	$salida .= "				</TR>\n";
	$salida .= "			</TABLE>\n";
	$salida .= "		</TD>\n";
	$salida .= "	</TR>\n";
	$salida .= "	<TR>\n";
	$salida .= "		<TD><IMG src=\"". GetThemePath() ."/images/submenu/punto.png\" width=\"1\" height=\"3\"></TD>\n";
	$salida .= "	</TR>\n";
	$salida .= "</TABLE>\n";

	return $salida;
}

function ThemeAbrirTablaSubModulo($titulo){
	return ThemeAbrirTabla($titulo);
}


function ThemeCerrarTablaSubModulo(){
	return ThemeCerrarTabla();
}

function ThemeAbrirTablaHistoriaClinica($paso,$acciones,$inforpaciente,$evolucion){
	switch ($align){
		case "L":  $clase="titulo_tabla_l";  break;
		case "R":  $clase="titulo_tabla_r";  break;
		default:  $clase="titulo_tabla";  break;
	}
	if($paso!="-2")
	{
		if($paso=="-3")
		{
			$imagen="azulclaro.png";
			$imagen2="azulclaro.png";
			$imagen3="azul.png";
			$claselink='hcLinkClaro';
			$claselink1='hcLinkClaro';
			$claselink2='hclink';
		}
		else
		{
			$imagen="azul.png";
			$imagen2="azulclaro.png";
			$imagen3="azulclaro.png";
			$claselink='hclink';
			$claselink1='hcLinkClaro';
			$claselink2='hcLinkClaro';
		}
	}
	else
	{
		$imagen="azulclaro.png";
		$imagen2="azul.png";
		$imagen3="azulclaro.png";
		$claselink='hcLinkClaro';
		$claselink1='hclink';
		$claselink2='hcLinkClaro';
	}
		$imagen4=GetThemePath().'/images/HistoriaClinica/';
		$salida .= "<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		$salida .= "	<TR>\n";
		$salida .= "		<TD>\n";
		$salida .= "			<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"22\" >\n";
		$salida .= "				<TR>\n";
		
          if(!empty($acciones[3]) AND $evolucion==1)
     	{
               //primera pestaña.
               $salida .= "					<TD width=\"5\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen\"  align=\"right\" valign=\"top\" nowrap><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_izq_sup.gif\" width=\"7\" height=\"7\"></TD>\n";
     
               $salida .= "					<TD background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen\" width=\"160\" height=\"30\" class='$clase' nowrap><a href=\"$acciones[0]\" class=\"$claselink\">";
               if($evolucion==1)
               {
                    $salida.="HISTORIA ACTUAL";
               }
               else
               {
                    $salida.="ATENCIÓN CONSULTADA";
               }
               $salida.="</a></TD>\n";
     
               $salida .= "					<TD  height=\"30\" width=\"2\" background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_der_sup.gif\" width=\"7\" height=\"7\"></TD>\n";
     
               //espacion entre pestañas No.1.
               $salida .= "					<TD  height=\"30\" width=\"2\" nowrap></TD>\n";
     
               //Segunda pestaña.
               $salida .= "<TD width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen2\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_izq_sup.gif\" width=\"7\" height=\"7\"></TD>\n";
     
               $salida .= "<TD background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen2\" width=\"160\" height=\"30\" rowspan=\"0\" class='$clase' nowrap><a href=\"$acciones[1]\" class=\"$claselink1\">HISTORIAL</a></TD>\n";
     
               $salida .= "<TD width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen2\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_der_sup.gif\" width=\"7\" height=\"7\"></TD>\n";
     
               //espacion entre pestañas  No.2.
               $salida .= "<TD  height=\"30\" width=\"2\" nowrap></TD>\n";
     
               //tercera pestaña.
               $salida .= "<TD width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen3\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_izq_sup.gif\" width=\"7\" height=\"7\"></TD>\n";
     
               $salida .= "<TD background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen3\" width=\"160\" height=\"30\" rowspan=\"0\" class='$clase' nowrap><a href=\"$acciones[4]\" class=\"$claselink2\">APOYOS DIAGNOSTICOS</a></TD>\n";
     
               $salida .= "<TD width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen3\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_der_sup.gif\" width=\"7\" height=\"7\"></TD>\n";
		}
		//Espacio Blanco.

		$salida .= "<TD  width=\"100%\"></TD>\n";
          if(!empty($acciones[3]))
          {
               $salida .= "<TD width=\"2\" height=\"30\"align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_der_sup.gif\" width=\"7\" height=\"7\"></TD>\n";
               $salida .= "<TD  height=\"30\" width=\"2\" nowrap></TD>\n";
               $salida .= "<TD width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/azulclaro.png\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_izq_sup.gif\" width=\"7\" height=\"7\"></TD>\n";
     
               $salida .= "<TD background=\"". GetThemePath() ."/images/HistoriaClinica/azulclaro.png\" width=\"30\" height=\"30\" align=\"center\" nowrap><a href=\"$acciones[3]\"><img src='".$imagen4."boton.png' width='22' height='22' align='middle' border='0'></a></TD>\n";
     
               $salida .= "<TD width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/azulclaro.png\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_der_sup.gif\" width=\"7\" height=\"7\"></TD>\n";
          }
		if($evolucion==1)
		{
			$salida .= "<TD  height=\"30\" width=\"2\" nowrap></TD>\n";
			$salida .= "<TD width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/azulclaro.png\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_izq_sup.gif\" width=\"7\" height=\"7\"></TD>\n";

			$salida .= "<TD background=\"". GetThemePath() ."/images/HistoriaClinica/azulclaro.png\" width=\"30\" height=\"30\" align=\"center\" nowrap><a href=\"$acciones[2]\"><img src='".$imagen4."cerrar.png' width='22' height='22' align='middle' border='0'></a></TD>\n";

			$salida .= "<TD width=\"5\" background=\"". GetThemePath() ."/images/HistoriaClinica/azulclaro.png\" height=\"30\"  align=\"right\" valign=\"top\" nowrap><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_der_sup.gif\" width=\"7\" height=\"7\"></TD>\n";
		}
		$salida .= "</TR>\n";
		$salida .= "<TR>\n";
		$salida .= "<TD background=\"". GetThemePath() ."/images/HistoriaClinica/azul.png\" height=\"30\" width=\"100%\" align=\"center\" colspan=\"21\">";
		$salida .= "<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"22\">\n";
		$salida .= "<TR>\n";
		$salida .= "<TD width=\"40%\" valign=\"bottom\" align=\"center\">";
		if($evolucion==1)
		{
			$salida.=$inforpaciente[0];
		}
		$salida.="</TD>\n";
		$salida .= "<TD width=\"30%\" align=\"center\" nowrap class=\"hc_normal_10\">";
		if($evolucion==1)
		{
			$salida.="<b>Edad:</b> $inforpaciente[1]";
		}
		$salida.="</TD>\n";
		$salida .= "<TD width=\"30%\" align=\"center\"  class=\"hc_normal_10\">";
		if($evolucion==1 AND $inforpaciente[2]!=' - ')
		{
			$salida.="<b>Responsable:</b> $inforpaciente[2]";
		}
		$salida.="</TD>\n";
		$salida .= "</TR>\n";
		$salida .= "</TABLE>\n";
		$salida .= "</TD>\n";
		$salida .= "				</TR>\n";
		$salida .= "			</TABLE>\n";
		$salida .= "		</TD>\n";
		$salida .= "	</TR>\n";
		$salida .= "	<TR>\n";
		$salida .= "		<TD>\n";
		$salida .= "			<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		$salida .= "				<TR>\n";
		$salida .= "					<TD width=\"4\" bgcolor=\"#636B70\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></TD>\n";
		$salida .= "					<TD align=\"center\">\n";
		$salida .= "						<TABLE width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		$salida .= "							<TR>\n";
		$salida .= "								<TD align=\"center\" valign=\"middle\" width=\"10\"></TD>\n";
		$salida .= "								<TD valign=\"top\" height=\"50\" class=\"Contenido\">\n";
	return $salida;
}


function ThemeCerrarTablaHistoriaClinica(){
	$salida .= "								</TD>\n";
	$salida .= "								<TD align=\"center\" valign=\"middle\" width=\"10\"></TD>\n";
	$salida .= "							</TR>\n";
	$salida .= "						</TABLE>\n";
	$salida .= "					</TD>\n";
	$salida .= "					<TD width=\"4\" bgcolor=\"#636B70\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></TD>\n";
	$salida .= "				</TR>\n";
	$salida .= "			</TABLE>\n";
	$salida .= "		</TD>\n";
	$salida .= "	</TR>\n";
	$salida .= "	<TR>\n";
	$salida .= "		<TD>\n";
	$salida .= "			<TABLE width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	$salida .= "				<TR>\n";
	$salida .= "					<TD width=\"100%\" height=\"4\" bgcolor=\"#636B70\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></TD>\n";
	$salida .= "				</TR>\n";
	$salida .= "			</TABLE>\n";
	$salida .= "		</TD>\n";
	$salida .= "	</TR>\n";
	$salida .= "</TABLE>\n";
	$salida .="</center>\n";

	return $salida;
}

?>
