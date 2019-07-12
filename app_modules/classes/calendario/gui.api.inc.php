<?php
// gui.api.inc.php  27/09/2003
// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: API para la GUI de la aplicacion
// ----------------------------------------------------------------------



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
		if(!empty($UserVars['theme'])){
			$theme=$UserVars['theme'];
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

function includeScript($script){


}

function ReturnModulo()
{
  if (!IncludeFile("classes/modules/modules.class.php")) {
    die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","El Archivo 'classes/modules/modules.class.php' NO EXISTE"));
  }

  if(!class_exists('ManejadorDeModulos')){
    die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","LA CLASE 'ManejadorDeModulos' NO EXISTE"));
  }

  $Modulo = new ManejadorDeModulos;

  if(!$Modulo->Inicializar()){
    die(MsgOut($Modulo->Err(),$Modulo->ErrMsg()));
  }

  return $Modulo->GetSalida();

}


function PrintModulo()
{
  if (!IncludeFile("classes/modules/modules.class.php")) {
    die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","El Archivo 'classes/modules/modules.class.php' NO EXISTE"));
  }

  if(!class_exists('ManejadorDeModulos')){
    die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","LA CLASE 'ManejadorDeModulos' NO EXISTE"));
  }

  $Modulo = new ManejadorDeModulos;

  if(!$Modulo->Inicializar()){
    die(MsgOut($Modulo->Err(),$Modulo->ErrMsg()));
  }
  echo ReturnHeader('',$Modulo->GetJavaScripts);
  echo ReturnBody();
  echo $Modulo->GetSalida();
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

function PrintCabecera()
{
echo "Este es la cabecera";
}

function PrintPieDePagina()
{
echo "Este es el pie de pagina.";
}


function PrintMenu()
{
echo "Este es el menu";
}



function ReturnHeader($Titulo = '', $Scripts = '', $Theme ='')
{
  global $VISTA;

	if (empty($Theme)){
		$Theme=GetTheme();
	}

	$ThemeStyle = "themes/$VISTA/$Theme/style/style.css";

	if (!file_exists($ThemeStyle)) {
		$ThemeStyle = "";
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

function ConfirmarAccion($msg, $modulo, $type='user', $funcion='main', $metodo='URL', $args=array())
{
	if(!isset($msg) || !isset($modulo)){
	$a='zzz';
	}
	$url=ModuloURLFunc($modulo, $type, $funcion, $args);
	$Salida.="<body>\n<center>\n";
	$Salida.="<h1><b>Confirmar Acción</b></h1><br />\n";
	$Salida.="$msg\n<br /><br />\n";
	$Salida.="<input type='button' value='{$a}SI' onclick='$url&ConfirmarAccion=SI'>&nbsp;<input type='button' value='No' onclick='Location:$url&ConfirmarAccion=NO'>&nbsp;<input type='button' value='Cancelar' onclick='$url&ConfirmarAccion=CANCELAR'>";
	return $Salida;
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

function RefrescarTodo()
{
  if(SessionGetVar('StyleFrames')){
  	return "\n\n<script language=\"javascript\">top.location.reload();</script>\n\n";
  }
  return "";
}

function ReturnJScalendario($retornarLinkHTML=false)
{
  if($retornarLinkHTML){
    $imagen = $THEME_PATH = "themes/$VISTA/" . GetTheme() . "/image/calendario/calendario.gif";
    $Salida = "<a href=\"javascript:show_Calendario('calform.datebox');\"><image type=image src=\"$imagen\" border=0 alt=\"Ver Calendario\" ></a>";                                                                                                                                                                                                          
  }else{
    $Salida  = "\n";
    $Salida .= "<script language=\"javascript\" src=\"checkdate.js\"></script>\n";
    $Salida .= "<script language=\"javascript\" src=\"setdatetime.js\"></script>\n";
    $Salida .= "<script language=\"JavaScript\" src=\"calendario.js\"></script>\n";
  }
  return $Salida;
}

function ReturnClassBuscador($tipo,$width='450',$height='250')
{

 global $_ROOT;
 $RUTA = $_ROOT ."classes/classbuscador/buscador.php?tipo=$tipo";

 $mostrar ="\n<script language='javascript'>;\n";
 //$mostrar.="  function abrirVentana(){";
 $mostrar.="    var nombre=\"\"\n";
 $mostrar.="    var url2=\"\"\n";
 $mostrar.="    var str=\"\"\n";
 $mostrar.="    var nombre=\"buscador General\";\n";
 $mostrar.="    var str =\"width=$width,height=$height,resizable=no,status=no,scrollbars=yes\";\n";
 $mostrar.="    var url2 ='$RUTA';\n";
 $mostrar.="    var rem = window.open(url2, nombre, str);\n";
 //$mostrar.="}";
 $mostrar.="</script>\n";

 return $mostrar;
}

?>
