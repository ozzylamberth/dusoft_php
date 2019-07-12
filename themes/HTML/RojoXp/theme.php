<?php


function ThemeVars()
{
  $vars['CitaExistente']='bgcolor="#304E8E"';
  return $vars;
}


function PrintMenu()
{
    return true;
}

function PrintModulo()
{
  $Modulo=array();
  $Modulo=ReturnModulo();

  $salida .=  ReturnHeader('',$Modulo['GetJavaScripts']);
  $salida .=  "<br>\n";
  $salida .=  ReturnBody($_ENV['THEME_VARS']['modulesBody']);
  $salida .=  "<table width='100%' cellspacing=10  border=0 cellpadding=2 align='center'>\n";
  $salida .=  "	<tr>\n";
  $salida .=  "		<td>\n";
  $salida .=  $Modulo['GetSalida'];
  $salida .=  "		</td>\n";
  $salida .=  "	</tr>\n";
  $salida .=  "</table>\n";
  $salida .=  ReturnFooter();

  return $salida;
}

function PrintCabecera()
{
    $datos=UserGetVar(UserGetUID(),'nombre');
		
		static $target;
		if(!isset($target))
		{
			$target = "";
			if(SessionGetVar('StyleFrames'))
				$target=" target=\"Contenido\"";
		}
		
    $salida  = "<table width='100%' align='top' cellspacing=0 border=0 cellpadding=0 align='center'  >\n";
    $salida .= "	<tr heigh=\"54\">\n"; 
		$salida .= "		<td width=\"348\"><img src=\"". GetThemePath() ."/images/logotipo1.png\"></td>\n";
		$salida .= "		<td width=\"%\"><img src=\"". GetThemePath() ."/images/medio.jpg\"></td>\n";
		$salida .= "		<td width=\"411\"><img src=\"". GetThemePath() ."/images/grupo-caminando.jpg\"></td>\n";
		$salida .= "	</tr>\n";
		$salida .= "	<tr >\n";
		$salida .= "		<td colspan=\"3\">\n";
    $salida .= "			<table width='100%' align=\"right\" cellspacing=0 border=0 cellpadding=0 align='center'>\n";
    $salida .= "				<tr valign='middle'>\n";
		$salida .= "					<td width=\"%\" style=\"text-indent:15px\" align=\"left\" class=\"label\" background=\"". GetThemePath() ."/images/franjita.png\">SISTEMA INTEGRAL DE INFORMACIÓN EN SALUD &nbsp;&nbsp;&nbsp;IPSOFT-SIIS</td>\n";
		
		if(UserLoggedIn())
    {

			//$salida .= "					<td width=\"%\" style=\"text-indent:15px\" align=\"left\" class=\"label\" background=\"". GetThemePath() ."/images/franjita.png\">SISTEMA INTEGRAL DE INFORMACIÓN EN SALUD &nbsp;&nbsp;&nbsp;IPSOFT-SIIS</td>\n";
			$salida .= "					<td width=\"365\">\n";
			$salida .= "						<map name=miMapa>\n";
			$salida .= "							<area href=\"".ModuloGetURL()."\" shape='rect' coords='187,2,239,15' title='Página de Inicio' $target alt='Página de Inicio' >\n";
			$salida .= "							<area href=\"".ModuloGetURL('system','Menu','user','main')."\" shape='rect' coords='243,2,301,14' title='Menu del Usuario' $target alt='Menu del Usuario' >\n";
			$salida .= "							<area href=\"".ModuloGetURL('system','log','user','logout')."\" shape='rect' coords='304,2,364,14' title='Cerrar Sesion' $target alt='Cerrar Sesion' >\n";
			$salida .= "						</map>\n";			
			$salida .= "						<img border=\"0\" src=\"". GetThemePath() ."/images/franja_menu.png\" usemap=#miMapa>\n";
			$salida .= "					</td>\n";
    }
    else
    {
			//$salida .= "					<td width=\"%\" background=\"". GetThemePath() ."/images/franjita.png\">SISTEMA INTEGRAL DE INFORMACIÓN EN SALUD &nbsp;&nbsp;&nbsp;IPSOFT-SIIS</td>\n";
			$salida .= "					<td width=\"365\">\n";
			$salida .= "						<map name=miMapa>\n";
			$salida .= "							<area href=\"". ModuloGetURL('system','log','user','main')."\" $target shape='rect' coords='260,2,310,14' title='Iniciar Sesion' alt='Iniciar Sesion' >\n";
			$salida .= "							<area href=\"". ModuloGetURL()."\" $target shape='rect' coords='315,2,361,14' title='Página de Inicio' alt='Página de Inicio' >\n";
			$salida .= "						</map>\n";			
			$salida .= " 						<img border=\"0\" src=\"". GetThemePath() ."/images/franja_inicio.png\" usemap=#miMapa>\n";
			$salida .= "					</td>\n";
		}

    $salida .= "				</tr>\n";
    $salida .= "			</table>\n";
    $salida .= "		</td>\n";
    $salida .= "	</tr>\n";
    $salida .= "</table>\n";

    $Titulo = _SIIS_APLICATION_TITLE . " - CABECERA";

		print(ReturnHeader($Titulo));
    print(ReturnBody(false));
    print($salida);
    print(ReturnFooter());

  return true;
}


function PrintIndexNoFrames()
{
    $salida .= "";

    print(ReturnHeader());
		print(ReturnBody());
    print($salida);
    print(ReturnFooter());
}


function PrintIndexFrames()
{
    $Titulo = _SIIS_APLICATION_TITLE;
    $salida .= "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \"http://www.w3.org/TR/html4/frameset.dtd\">\n";
    $salida .= "<html>\n";
    $salida .= "<head>\n";
    $salida .= "<title>$Titulo</title>\n";
    $salida .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
    $salida .= "   <link rel=\"shortcut icon\" href=\"images/favicon.ico\" >\n";
    $salida .= "   <link rel=\"icon\" href=\"images/animated_favicon1.gif\" type=\"image/gif\" >\n";
		$salida .= "</head>\n";
    $salida .= "\n";
    $salida .= "<frameset rows=\"71,*\" frameborder=\"NO\" border=\"0\" framespacing=\"0\">\n";
    $salida .= "  <frame src=\"Cabecera.php\" name=\"Cabecera\" scrolling=\"NO\" noresize marginwidth=\"0\" marginheight=\"0\" margintop=\"0\">\n";
    $salida .= "  <frame src=\"Contenido.php\" name=\"Contenido\" marginwidth=\"0\" marginheight=\"0\" margintop=\"0\">\n";
    $salida .= "</frameset>\n";
    $salida .= "<noframes><body>\n";
    $salida .= "<script language='javascript'>\n";
    $salida .= "   <!--\n";
    $salida .= "         location.href='index.php?CancelFramesIndex=true'\n";
    $salida .= "   -->\n";
    $salida .= " </script>\n";
    $salida .= "</body></noframes>\n";
    $salida .= "</html>\n";

    print($salida);
}
?>
