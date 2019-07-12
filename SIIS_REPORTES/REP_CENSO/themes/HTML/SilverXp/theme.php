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

  echo ReturnHeader('',$Modulo['GetJavaScripts']);
  echo "<br>\n";
  echo ReturnBody($_ENV['THEME_VARS']['modulesBody']);
  echo "<table width='100%' cellspacing=10  border=0 cellpadding=2 align='center'>\n";
  echo "<tr>\n";
  echo "<td>\n";
  echo $Modulo['GetSalida'];
  echo "</td>\n";
  echo "</tr>\n";
  echo "</table>\n";
  echo ReturnFooter();

  return true;
}

function PrintCabecera()
{
    $datos=UserGetVar(UserGetUID(),'nombre');
    $salida = "<table width='100%' cellspacing=0 border=0 cellpadding=0 align='center' bgcolor='#FFFFFF'>\n";
    $salida .= "<tr>\n";
    $salida .= "<td align='center' valign='middle'><img src=\"". GetThemePath() ."/images/logotipo1.png\" width=121 height=70 border=0></td>\n";
    $salida .= "<td align='center' valign='center'><img src=\"". GetThemePath() ."/images/curva.png\" width=65 height=100 border=0></td>\n";
    $salida .= "<td background=\"". GetThemePath() ."/images/franja.png\" width='70%' align='center' valign='middle' class='Cliente'>".GetVarConfigAplication('Cliente')."</td>\n";
    $salida .= "<td background=\"". GetThemePath() ."/images/franja.png\" width='30%'>\n";
    $salida .= "\n";
    $salida .= "    <table width='100%' cellspacing=2 border=0 cellpadding=2 align='center'>\n";
    $salida .= "    <tr>\n";
    $salida .= "    <td align='center' valign='middle' class=\"label\"><font color='white'>".FormatoFecha(1)."</font></td>\n";
    $salida .= "    </tr>\n";
    $salida .= "    <tr>\n";
    $salida .= "    <td align='center'>\n";
    $salida .= "        <table  cellspacing=2 border=0 cellpadding=2 align='center'>\n";
    $salida .= "        <tr align='right' valign='middle'>\n";

    static $target;
        if(!isset($target)){
            if(SessionGetVar('StyleFrames')){
                $target=" target=\"Contenido\"";
            }else{
                $target='';
            }
        }

         if(!empty($alt)){
    $alt = " alt=\"menu\"";
  }


  if(UserLoggedIn())
    {
        $salida .= "        <td align='justify'><a href=\"". ModuloGetURL()."\"$target$alt><img src=\"". GetThemePath() ."/images/casa.png\" title=\"Página Inicial\" width=29 height=29 border=0></a></td>\n";
        $salida .= "        <td align='justify'><a href=\"". ModuloGetURL('system','Menu','user','main')."\"$target$alt><img src=\"". GetThemePath() ."/images/menu.png\" title=\"Menú del Usuario\" width=29 height=29 border=0></a></td>\n";
        //$salida .= "      <td align='justify'><a href=\"". ModuloGetURL()."\"$target$alt><img src=\"". GetThemePath() ."/images/candado.png\" width=29 height=29 border=0></a></td>\n";
        $salida .= "        <td align='justify'><a href=\"". ModuloGetURL('system','log','user','logout')."\"$target$alt><img src=\"". GetThemePath() ."/images/logout.png\" title=\"Cerrar Sesión\" width=29 height=29 border=0></a></td>\n";
    }
    else
    {
        $salida .= "        <td align='justify'><a href=\"". ModuloGetURL()."\"$target$alt><img src=\"". GetThemePath() ."/images/casa.png\" title=\"Página Inicial\" width=29 height=29 border=0></a></td>\n";
        $salida .= "        <td align='justify'><a href=\"". ModuloGetURL('system','log','user','main')."\"$target$alt><img src=\"". GetThemePath() ."/images/llave.png\" title=\"Iniciar Sesión\" width=29 height=29 border=0></a></td>\n";

    }

    $salida .= "        </tr>\n";
    $salida .= "                </table>\n";
    $salida .= "        </td>\n";
    $salida .= "        </tr>\n";
    $salida .= "        </table>\n";

    $salida .= "                        <table  cellspacing=2 border=0 cellpadding=2 align='center'>\n";
    $salida .= "                  <tr>\n";
    if(!empty($datos))
    {
    $salida .= "                        <td align='center' class='usuario'>".$datos."</td>\n";
    }
    else
    {
        $salida .= "                        <td></td>\n";
    }
    $salida .= "              </tr>\n";
    $salida .= "                        </table>\n";



    $salida .= "    </td>\n";
    $salida .= "    </tr>\n";
    $salida .= "    </table>\n";
    $salida .= "\n";
    $salida .= "</td>\n";
    $salida .= "</tr>\n";
    $salida .= "</table>\n";


    $Titulo = _SIIS_APLICATION_TITLE . " - CABECERA";

  print(ReturnHeader($Titulo));
    print(ReturnBody());
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
    $salida .= "<frameset rows=\"100,*\" frameborder=\"NO\" border=\"0\" framespacing=\"0\">\n";
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
