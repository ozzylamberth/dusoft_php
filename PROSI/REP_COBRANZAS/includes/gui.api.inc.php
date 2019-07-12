<?php

/**
 * $Id: gui.api.inc.php,v 1.3 2005/06/07 19:03:54 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

// gui.api.inc.php  27/09/2002

function GetMenuList($uid)
{
	list($dbconn) = GetDBconn();

	$query = "SELECT *
			  FROM system_menus
			  WHERE activo = 't' ";
	$result = $dbconn->Execute($query);
	if ($result->EOF) {
		return false;
	}

	$menus = $result->GetRowAssoc(false);
	$result->Close();


    return true;
}

function GetTheme()
{
  global $VISTA;
	static $theme;
	if(!empty($theme)){
		return $theme;
	}

	if(!UserLoggedIn()){
		$theme = GetVarConfigAplication('DefaultTheme');
		if(empty($theme)){
			$theme='default';
		}elseif(!is_dir("themes/$VISTA/$theme")){
			$theme='default';
		}
	}else{
		$UserVars= UserGetVars(SessionGetVar('UID'));
		if(!empty($UserVars['Tema'])){
			$theme=$UserVars['Tema'];
			if(!is_dir("themes/$VISTA/$theme")){
			$theme='default';
			}
		}else{
			$theme = GetVarConfigAplication('DefaultTheme');
			if(empty($theme)){
				$theme='default';
			}
		}
	}
	return $theme;
}

function GetThemePath()
{
	static $themePath;
	if(!empty($themePath)){
		return $themePath;
	}

  global $VISTA;
  $themePath = "themes/$VISTA/" . GetTheme();

  return $themePath;
}

function ReturnCabecera()
{
  return ThemeReturnCabecera();
}

function ReturnModulo()
{

  if (!IncludeFile("classes/modules/classmodules.class.php")) {
		die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","El Archivo 'classes/modules/classmodules.class.php' NO SE ENCUENTRA"));
	}

	if(!class_exists('classModules')){
		die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","LA CLASE 'classModules' NO EXISTE"));
	}

	if($_REQUEST['contenedor'] == 'hc'){

    if (!IncludeFile("classes/modules/hc_modules.class.php")) {
      die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","El Archivo 'classes/modules/hc_modules.class.php' NO SE ENCUENTRA"));
    }

    if(!class_exists('ManejadorDeHC')){
      die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","LA CLASE 'ManejadorDeHC' NO EXISTE"));
    }

    $Modulo = new ManejadorDeHC();

	}else{

    if (!IncludeFile("classes/modules/modules.class.php")) {
      die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","El Archivo 'classes/modules/modules.class.php' NO SE ENCUENTRA"));
    }

    if(!class_exists('ManejadorDeModulos')){
      die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","LA CLASE 'ManejadorDeModulos' NO EXISTE"));
    }

    if (!IncludeFile("classes/modules/classmodulo.class.php")) {
      die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","El Archivo 'classes/modules/classmodulo.class.php' NO SE ENCUENTRA"));
    }

    if(!class_exists('classModulo')){
      die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","LA CLASE 'classModulo' NO EXISTE"));
    }

    $Modulo = new ManejadorDeModulos();

  }


  if(!$Modulo->Inicializar()){
    $mensajeError = $Modulo->ErrMsg();
    die(MsgOut($Modulo->Err(),$mensajeError));
  }

  $returnModulo['GetSalida']=$Modulo->GetSalida();
  $returnModulo['GetJavaScripts']=$Modulo->GetJavaScripts();

  return $returnModulo;

}


function ReturnMenu()
{
  if (!IncludeFile("classes/modules/classmodules.class.php")) {
		die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","El Archivo 'classes/modules/classmodules.class.php' NO SE ENCUENTRA"));
	}

	if(!class_exists('classModules')){
		die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","LA CLASE 'classModules' NO EXISTE"));
	}

  if (!IncludeFile("classes/gui/classMenu.class.php")) {
    die(MsgOut("NO SE CARGO EL MANEJADOR DE MENUS","El Archivo 'classes/gui/block_menu.class.php' NO SE ENCUENTRA"));
  }

  if(!class_exists('classMenu')){
    die(MsgOut("NO SE CARGO EL MANEJADOR DE MENUS","LA CLASE 'classMenu' NO EXISTE"));
  }

  $Menu = new classMenu();

  if(!$Menu->Inicializar()){
    $mensajeError = $Menu->ErrMsg() . " - Error Ocurrido al inicializar classMenu";
    die(MsgOut($Menu->Err(),$mensajeError));
  }

  $returnMenu['GetSalida']=$Menu->GetSalida();
  $returnMenu['GetJavaScripts']=$Menu->GetJavaScripts();

  return $returnMenu;
}

function ReturnOpenCalendario($forma,$campo,$sep)
{
		global $_ROOT;
		global $VISTA;
		$RUTA = $_ROOT ."classes/calendariopropio/Calendario.php?forma=$forma&campo=$campo&separador=$sep";
		$Salida='<script language="javascript">'."\n".'function LlamarCalendario'.$campo.'()'."\n"."{"."\n"."window.open('".$RUTA."','CALENDARIO SIIS','width=450,height=250,resizable=no,status=no,scrollbars=yes');"."\n".'}'."\n".'</script>'."\n";
    $imagen = "themes/$VISTA/" . GetTheme() . "/images/calendario/calendario.png";
		$Salida.="<a href=\"javascript:LlamarCalendario$campo()\"> <img src=\"$imagen\" border=0 alt=\"Ver Calendario\"></a> [dd/mm/aaaa]";
    return $Salida;
}

function PrintModulo()
{
  $Modulo=array();
  $Modulo=ReturnModulo();
  echo ReturnHeader('',$Modulo['GetJavaScripts']);
  global $aaa;
  echo $aaa;
  echo ReturnBody($_ENV['THEME_VARS']['modulesBody']);
  echo $Modulo['GetSalida'];
  echo ReturnFooter();

  return true;
}

function PrintMenu()
{
  $Menu=array();
  $Menu=ReturnMenu();
  echo ReturnHeader('',$Menu['GetJavaScripts']);
  echo ReturnBody($_ENV['THEME_VARS']['menuBody']);
  echo $Menu['GetSalida'];
  echo ReturnFooter();

  return true;
}



function PrintIndexNoFrames()
{
	if (!function_exists("ReturnIndexNoFrames")) {
		die(MsgOut("Archivo del 'Theme' incorrecto","La funcion PrintIndexNoFrames() no existe en el archivo <i>$THEME_PATH/theme.php</i>"));
	}
  global $LAYER_BGCOLOR;
	echo ReturnHeader();
  echo ReturnBody($LAYER_BGCOLOR);
	echo ReturnIndexNoFrames();
	echo ReturnFooter();
}



function PrintPieDePagina()
{
echo "Este es el pie de pagina.";
}






function ReturnHeader($Titulo = '', $Scripts = '', $Theme ='')
{
  global $VISTA;
	global $_ROOT;

	if (empty($Theme)){
		$Theme=GetTheme();
	}

	$ThemeStyle = $_ROOT."themes/$VISTA/$Theme/style/style.css";

	if (!file_exists($ThemeStyle)) {
		$ThemeStyle = "xxx";
	}else{
		$ThemeStyle = "<link href=\"$ThemeStyle\" rel=\"stylesheet\" type=\"text/css\">\n";
	}

	if(empty($Titulo)){
		$Titulo = _SIIS_APLICATION_TITLE;
	}

	$Salida  = "<html>\n";
	$Salida .= "<head>\n";
	$Salida .= "  <title>$Titulo</title>\n";
	$Salida .= "  <meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>\n";
	$Salida .= "  $ThemeStyle";
	$Salida .= "  $Scripts\n";
	$Salida .= "</head>\n";

	echo $Salida;
}

function ReturnBody($BackGround='')
{
  return "<body $BackGround>\n";
}

function ReturnFooter()
{
	return "\n</body>\n</html>\n";
}


function RefrescarMenu()
{
  if(SessionGetVar('StyleFrames')){
    return "\n\n<script language=\"javascript\">parent.frames.Menu.location.reload();</script>\n\n";
  }
  return "";
}

function RefrescarCabecera()
{
  if(SessionGetVar('StyleFrames')){
    return "\n\n<script language=\"javascript\">parent.frames.Cabecera.location.reload();</script>\n\n";
  }
  return "";
}

function RefrescarPieDePagina()
{
  if(SessionGetVar('StyleFrames')){
	  return "\n\n<script language=\"javascript\">parent.frames.PieDePagina.location.reload();</script>\n\n";
  }
  return "";
}

function RefrescarContenidoHomePage()
{
  if(SessionGetVar('StyleFrames')){
    return "\n\n<script language=\"javascript\">parent.frames.Contenido.location='Contenido.php';</script>\n\n";
  }
  return "xxxx";
}

function RefrescarTodo()
{
  if(SessionGetVar('StyleFrames')){
  	return "\n\n<script language=\"javascript\">setTimeout('top.location.reload()',1000);</script>\n\n";
  }else{
    return "\n\n<script language=\"javascript\">setTimeout('reload()',1000);</script>\n\n";
  }
}



function ReturnClassBuscador($tipo,$sql,$key,$forma,$pfj){
//exit;
 global $_ROOT;
 if (empty($key))
 {
  $RUTA = $_ROOT ."classes/classbuscador/buscador.php?tipo=$tipo&sql=$sql&forma=$forma&pfj=$pfj";
 }
 else
 {
 $RUTA = $_ROOT ."classes/classbuscador/buscador.php?tipo=$tipo&sql=$sql&key=$key&forma=$forma&pfj=$pfj";
 }

 $mostrar ="\n<script language='javascript'>\n";
 $mostrar.="var rem=\"\";\n";
 $mostrar.="  function abrirVentana(){\n";
 $mostrar.="    var nombre=\"\"\n";
 $mostrar.="    var url2=\"\"\n";
 $mostrar.="    var str=\"\"\n";
 $mostrar.="    var ALTO=screen.height\n";
 $mostrar.="    var ANCHO=screen.width\n";
 $mostrar.="    var nombre=\"buscador_General\";\n";
 $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
 $mostrar.="    var url2 ='$RUTA';\n";
 $mostrar.="    rem = window.open(url2, nombre, str)};\n";
 //$mostrar.="</script>\n";

 return $mostrar;
}
?>
