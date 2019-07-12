<?php

	function ThemeAbrirTabla($titulo,$porcentaje=null,$align=null)
	{
		$salida ="<center>\n";
    switch ($align)
    {
      case "L":  $clase="titulo_tabla_l";  break;
      case "R":  $clase="titulo_tabla_r";  break;
      default:  $clase="titulo_tabla";  break;
    }
    if (!$porcentaje)
    {
      $salida .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"tabla\">\n";
    }
    else
    {
      $salida .= "<table width=\"$porcentaje\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"tabla\">\n";
    }
		$salida .= "	<tr>\n";
		$salida .= "		<td>\n";
		$salida .= "			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"22\">\n";
		$salida .= "				<tr>\n";
		$salida .= "					<td width=\"6\" height=\"30\"><IMG src=\"". GetThemePath() ."/images/curva_izq_tabla.png\" width=\"6\" height=\"30\"></td>\n";
		$salida .= "					<td background=\"". GetThemePath() ."/images/franja_tabla.png\" width=\"100%\" height=\"29\" rowspan=\"2\" class='$clase'>$titulo</td>\n";
		$salida .= "					<td width=\"6\" height=\"30\"><IMG src=\"". GetThemePath() ."/images/curva_der_tabla.png\" width=\"6\" height=\"30\"></td>\n";
		$salida .= "				</tr>\n";
		$salida .= "			</table>\n";
		$salida .= "		</td>\n";
		$salida .= "	</tr>\n";
		$salida .= "	<tr>\n";
		$salida .= "		<td>\n";
		$salida .= "			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		$salida .= "				<tr>\n";
		$salida .= "					<td width=\"4\" bgcolor=\"#C20000\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></td>\n";
		$salida .= "					<td align=\"center\">\n";
		$salida .= "						<table width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		$salida .= "							<tr>\n";
		$salida .= "								<td align=\"center\" valign=\"middle\" width=\"10\"></td>\n";
		$salida .= "								<td valign=\"top\" height=\"50\" class=\"Contenido\"><br><br>\n";
    return $salida;
	}


	function ThemeCerrarTabla()
	{
	
    $salida  = "									<br><br>\n";
		$salida .= "								</td>\n";
    $salida .= "								<td align=\"center\" valign=\"middle\" width=\"10\"></td>\n";
    $salida .= "							</tr>\n";
    $salida .= "							<tr>\n";
    $salida .= "								<td background=\"". GetThemePath() ."/images/punto.png\" colspan=\"2\" height=\"1\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></td>\n";
    $salida .= "							</tr>\n";
    $salida .= "						</table>\n";
    $salida .= "					</td>\n";
    $salida .= "					<td width=\"4\" bgcolor=\"#C20000\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></td>\n";
    $salida .= "				</tr>\n";
    $salida .= "			</table>\n";
    $salida .= "		</td>\n";
    $salida .= "	</tr>\n";
    $salida .= "	<tr>\n";
    $salida .= "		<td>\n";
    $salida .= "			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    $salida .= "				<tr>\n";
    $salida .= "					<td width=\"100%\" height=\"4\" bgcolor=\"#C20000\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></td>\n";
    $salida .= "				</tr>\n";
    $salida .= "			</table>\n";
    $salida .= "		</td>\n";
    $salida .= "	</tr>\n";
    $salida .= "</table>\n";
    $salida .= "</center>\n";

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
        $salida .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    }
    else{
        $salida .= "<table width=\"$porcentaje\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\n";
    }
        $salida .= "    <tr>\n";
        $salida .= "        <td>\n";
        $salida .= "            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"22\">\n";
        $salida .= "                <tr>\n";
        $salida .= "                    <td width=\"6\" height=\"30\"><IMG src=\"". GetThemePath() ."/images/curva_izq_tabla.png\" width=\"6\" height=\"30\"></td>\n";
        $salida .= "                    <td background=\"". GetThemePath() ."/images/franja_tabla.png\" width=\"100%\" height=\"29\" rowspan=\"2\" class='$clase'>$titulo</td>\n";
        $salida .= "                    <td width=\"6\" height=\"30\"><IMG src=\"". GetThemePath() ."/images/curva_der_tabla.png\" width=\"6\" height=\"30\"></td>\n";
        $salida .= "                </tr>\n";
        $salida .= "            </table>\n";
        $salida .= "        </td>\n";
        $salida .= "    </tr>\n";
        $salida .= "		<tr>\n";
        $salida .= "			<td>\n";
        $salida .= "				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class='tabla_menu'>\n";
        $salida .= "					<tr>\n";
        $salida .= "						<td width=\"4\" bgcolor=\"#C20000\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></td>\n";
        $salida .= "						<td align=\"center\">\n";
        $salida .= "							<table width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class='tabla_menu'>\n";
        $salida .= "								<tr>\n";
        $salida .= "									<td align=\"center\" valign=\"middle\" width=\"10\"></td>\n";
        $salida .= "										<td valign=\"top\" height=\"50\" class=\"Contenido\"><br>\n";
    return $salida;
}


function ThemeMenuCerrarTabla(){
    $salida .= "                                <br><br></td>\n";
    $salida .= "                                <td align=\"center\" valign=\"middle\" width=\"10\"></td>\n";
    $salida .= "                            </tr>\n";
    $salida .= "                            <tr>\n";
    $salida .= "                                <td background=\"". GetThemePath() ."/images/punto.png\" colspan=\"2\" height=\"1\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></td>\n";
    $salida .= "                            </tr>\n";
    $salida .= "                        </table>\n";
    $salida .= "                    </td>\n";
    $salida .= "                    <td width=\"4\" bgcolor=\"#C20000\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></td>\n";
    $salida .= "                </tr>\n";
    $salida .= "            </table>\n";
    $salida .= "        </td>\n";
    $salida .= "    </tr>\n";
    $salida .= "    <tr>\n";
    $salida .= "        <td>\n";
    $salida .= "            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    $salida .= "                <tr>\n";
    $salida .= "                    <td width=\"100%\" height=\"4\" bgcolor=\"#C20000\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></td>\n";
    $salida .= "                </tr>\n";
    $salida .= "            </table>\n";
    $salida .= "        </td>\n";
    $salida .= "    </tr>\n";
    $salida .= "</table>\n";
    $salida .="</center>\n";

    return $salida;
}


function ThemeSubMenuTabla($titulo,$porcentaje){

    if (!$porcentaje){
        $salida .= "<table width=\"100%\"  height=\"25\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    }
    else{
        $salida .= "<table width=\"$porcentaje\"  height=\"25\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >\n";
    }
    $salida .= "    <tr>\n";
    $salida .= "        <td>\n";
    $salida .= "            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"100%\">\n";
    $salida .= "                <tr>\n";
    $salida .= "                    <td><IMG src=\"". GetThemePath() ."/images/submenu/borde_izq.png\" width=\"14\" height=\"26\"></td>\n";
    $salida .= "                    <td height=\"25\" background=\"". GetThemePath() ."/images/submenu/franja.png\" width=\"100%\" height=\"26\" class='titulo_tabla_submenu'>$titulo</td>\n";
    $salida .= "                    <td><IMG src=\"". GetThemePath() ."/images/submenu/borde_der.png\" width=\"14\" height=\"26\"></td>\n";
    $salida .= "                </tr>\n";
    $salida .= "            </table>\n";
    $salida .= "        </td>\n";
    $salida .= "    </tr>\n";
    $salida .= "    <tr>\n";
    $salida .= "        <td><IMG src=\"". GetThemePath() ."/images/submenu/punto.png\" width=\"1\" height=\"3\"></td>\n";
    $salida .= "    </tr>\n";
    $salida .= "</table>\n";

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
        $salida .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
        $salida .= "    <tr>\n";
        $salida .= "        <td>\n";
        $salida .= "            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"22\" >\n";
        $salida .= "                <tr>\n";
                
        if(!empty($acciones[3]) AND $evolucion==1)
        {
               //primera pestaña.
               $salida .= "                    <td width=\"5\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen\"  align=\"right\" valign=\"top\" nowrap><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_izq_sup.gif\" width=\"7\" height=\"7\"></td>\n";
          
               $salida .= "                    <td background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen\" width=\"160\" height=\"30\" class='$clase' nowrap><a href=\"$acciones[0]\" class=\"$claselink\">";
               if($evolucion==1)
               {
                    $salida.="HISTORIA ACTUAL";
               }
               else
               {
                    $salida.="ATENCION CONSULTADA";
               }
               $salida.="</a></td>\n";
          
               $salida .= "                    <td  height=\"30\" width=\"2\" background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_der_sup.gif\" width=\"7\" height=\"7\"></td>\n";
          
               //espacion entre pestañas No.1.
               $salida .= "                    <td  height=\"30\" width=\"2\" nowrap></td>\n";
          
               //Segunda pestaña.
               $salida .= "<td width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen2\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_izq_sup.gif\" width=\"7\" height=\"7\"></td>\n";
          
               $salida .= "<td background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen2\" width=\"160\" height=\"30\" rowspan=\"0\" class='$clase' nowrap><a href=\"$acciones[1]\" class=\"$claselink1\">HISTORIAL</a></td>\n";
          
               $salida .= "<td width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen2\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_der_sup.gif\" width=\"7\" height=\"7\"></td>\n";
          
               //espacion entre pestañas  No.2.
               $salida .= "<td  height=\"30\" width=\"2\" nowrap></td>\n";
          
          //tercera pestaña.
               $salida .= "<td width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen3\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_izq_sup.gif\" width=\"7\" height=\"7\"></td>\n";
               
               $salida .= "<td background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen3\" width=\"160\" height=\"30\" rowspan=\"0\" class='$clase' nowrap><a href=\"$acciones[4]\" class=\"$claselink2\">APOYOS DIAGNOSTICOS</a></td>\n";
          
               $salida .= "<td width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/$imagen3\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_der_sup.gif\" width=\"7\" height=\"7\"></td>\n";
        }
        //Espacio Blanco.

        $salida .= "<td  width=\"100%\"></td>\n";
        if(!empty($acciones[3]))
        {
            $salida .= "<td width=\"2\" height=\"30\"align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_der_sup.gif\" width=\"7\" height=\"7\"></td>\n";
            $salida .= "<td  height=\"30\" width=\"2\" nowrap></td>\n";
            $salida .= "<td width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/azulclaro.png\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_izq_sup.gif\" width=\"7\" height=\"7\"></td>\n";

            $salida .= "<td background=\"". GetThemePath() ."/images/HistoriaClinica/azulclaro.png\" width=\"30\" height=\"30\" align=\"center\" nowrap><a href=\"$acciones[3]\"><img src='".$imagen4."boton.png' width='22' height='22' align='middle' border='0'></a></td>\n";

            $salida .= "<td width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/azulclaro.png\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_der_sup.gif\" width=\"7\" height=\"7\"></td>\n";
        }
        if($evolucion==1)
        {
            $salida .= "<td  height=\"30\" width=\"2\" nowrap></td>\n";
            $salida .= "<td width=\"2\" height=\"30\" background=\"". GetThemePath() ."/images/HistoriaClinica/azulclaro.png\" align=\"left\" valign=\"top\"><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_izq_sup.gif\" width=\"7\" height=\"7\"></td>\n";

            $salida .= "<td background=\"". GetThemePath() ."/images/HistoriaClinica/azulclaro.png\" width=\"30\" height=\"30\" align=\"center\" nowrap><a href=\"$acciones[2]\"><img src='".$imagen4."cerrar.png' width='22' height='22' align='middle' border='0'></a></td>\n";

            $salida .= "<td width=\"5\" background=\"". GetThemePath() ."/images/HistoriaClinica/azulclaro.png\" height=\"30\"  align=\"right\" valign=\"top\" nowrap><IMG src=\"". GetThemePath() ."/images/HistoriaClinica/angulo_der_sup.gif\" width=\"7\" height=\"7\"></td>\n";
        }
        $salida .= "</tr>\n";
        $salida .= "<tr>\n";
        $salida .= "<td background=\"". GetThemePath() ."/images/HistoriaClinica/azul.png\" height=\"30\" width=\"100%\" align=\"center\" colspan=\"21\">";
        $salida .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"22\">\n";
        $salida .= "<tr>\n";
        $salida .= "<td width=\"40%\" align=\"center\">";
        if($evolucion==1)
        {
            $salida.=$inforpaciente[0];
        }
        $salida.="</td>\n";
        $salida .= "<td width=\"30%\" align=\"center\" nowrap class=\"hc_normal_10\">";
        if($evolucion==1)
        {
            $salida.="<b>Edad:</b> $inforpaciente[1]";
        }
        $salida.="</td>\n";
        $salida .= "<td width=\"30%\" align=\"center\"  class=\"hc_normal_10\">";
        if($evolucion==1 AND $inforpaciente[2]!=' - ')
        {
            $salida.="<b>Responsable:</b> $inforpaciente[2]";
        }
        $salida.="</td>\n";
        $salida .= "</tr>\n";
        $salida .= "</table>\n";
        $salida .= "</td>\n";
        $salida .= "                </tr>\n";
        $salida .= "            </table>\n";
        $salida .= "        </td>\n";
        $salida .= "    </tr>\n";
        $salida .= "    <tr>\n";
        $salida .= "        <td>\n";
        $salida .= "            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
        $salida .= "                <tr>\n";
        $salida .= "                    <td width=\"4\" bgcolor=\"#2B3DD1\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></td>\n";
        $salida .= "                    <td align=\"center\">\n";
        $salida .= "                        <table width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
        $salida .= "                            <tr>\n";
        $salida .= "                                <td align=\"center\" valign=\"middle\" width=\"10\"></td>\n";
        $salida .= "                                <td valign=\"top\" height=\"50\" class=\"Contenido\">\n";
    return $salida;
}


function ThemeCerrarTablaHistoriaClinica(){
    $salida .= "                                </td>\n";
    $salida .= "                                <td align=\"center\" valign=\"middle\" width=\"10\"></td>\n";
    $salida .= "                            </tr>\n";
    $salida .= "                        </table>\n";
    $salida .= "                    </td>\n";
    $salida .= "                    <td width=\"4\" bgcolor=\"#2B3DD1\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></td>\n";
    $salida .= "                </tr>\n";
    $salida .= "            </table>\n";
    $salida .= "        </td>\n";
    $salida .= "    </tr>\n";
    $salida .= "    <tr>\n";
    $salida .= "        <td>\n";
    $salida .= "            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    $salida .= "                <tr>\n";
    $salida .= "                    <td width=\"100%\" height=\"4\" bgcolor=\"#2B3DD1\"><IMG src=\"". GetThemePath() ."/images/punto.png\" width=\"1\" height=\"1\"></td>\n";
    $salida .= "                </tr>\n";
    $salida .= "            </table>\n";
    $salida .= "        </td>\n";
    $salida .= "    </tr>\n";
    $salida .= "</table>\n";
    $salida .="</center>\n";

    return $salida;
}

?>
