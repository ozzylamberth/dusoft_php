<?php

/**
 * $Id: HTML.php,v 1.6 2005/07/25 20:29:33 ehudes Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

function vistaDepuracionEnviroment($Request,$PropiedadesModulo)
{
    $Salida.="\n<br><br>\n";
    $Salida.=ThemeAbrirTabla("******  VENTANA DE DEPURACION DE MODULOS   ******","100%");
    $Salida.="\n<br>\n";
    $Salida.="<TABLE width='90%' cellspacing='0' cellpadding='2' align='center' border='1' >\n";
    $Salida.="<tr><td class='modulo_table_list_title' colspan='2'>VALORES DEL VECTOR ".'$_REQUEST'."</td></tr>\n";
    
    foreach($_REQUEST as $k=>$v)
    {
        if(is_array($v))
        {
            $v=print_r($v,true);
        }
        if(empty($v))
        {
            $v='&nbsp;';
        }
        $Salida.="<tr><td width='30%' class='modulo_table_list_title'>$k</td><td width='70%' class='modulo_table_list'>$v</td></tr>\n";        
    }
    
    $Salida.="</TABLE>\n";    

    if(!empty($Request))
    {
        if($Request != $_REQUEST)
        {
            $Salida.="\n<br>\n";
            $Salida.="<TABLE width='90%' cellspacing='0' cellpadding='2' align='center' border='1' >\n";
            $Salida.="<tr><td class='modulo_table_list_title' colspan='2'>VALORES DEL VECTOR ".'$_REQUEST'." EN UNA POSIBLE LLAMADA A UN METODO EXTERNO</td></tr>\n";
            
            foreach($Request as $k=>$v)
            {
                if(is_array($v))
                {
                    $v=print_r($v,true);
                }
                if(empty($v))
                {
                    $v='&nbsp;';
                }                    
                $Salida.="<tr><td width='30%' class='modulo_table_list_title'>$k</td><td width='70%' class='modulo_table_list'>$v</td></tr>\n";        
            }
            
            $Salida.="</TABLE>\n";
        }

    }    
    
    $Salida.="\n<br>\n";
    $Salida.="<TABLE width='90%' cellspacing='0' cellpadding='2' align='center' border='1' >\n";
    $Salida.="<tr><td class='modulo_table_list_title' colspan='2'>VALORES DEL VECTOR ".'$_SESSION'."</td></tr>\n";
    
    foreach($_SESSION as $k=>$v)
    {
        if(is_array($v))
        {
            $v=print_r($v,true);
        }
        if(empty($v))
        {
            $v='&nbsp;';
        }                
        $Salida.="<tr><td width='30%' class='modulo_table_list_title'>$k</td><td width='70%' class='modulo_table_list'>$v</td></tr>\n";        
    }
    
    $Salida.="</TABLE>\n";
    
    $Salida.="\n<br>\n";
    $Salida.="<TABLE width='90%' cellspacing='0' cellpadding='2' align='center' border='1' >\n";
    $Salida.="<tr><td class='modulo_table_list_title' colspan='2'>VALORES DE LAS PROPIEDADES DEL MODULO</td></tr>\n";
    
    if(!empty($PropiedadesModulo))
    {        
        foreach($PropiedadesModulo as $k=>$v)
        {
            if($k != 'salida')
            {
                if(is_array($v))
                {
                    $v=print_r($v,true);
                }
                if(empty($v))
                {
                    $v='&nbsp;';
                }        
                $Salida.="<tr><td width='30%' class='modulo_table_list_title'>$k</td><td width='70%' class='modulo_table_list'>$v</td></tr>\n";        
            }
        }
        
        $Salida.="</TABLE>\n";
    }    
    $Salida.=ThemeCerrarTabla();
    
    return $Salida;
}

function vistaMsgOut($msg,$detalle='',$file='',$line='',$modulo=array(),$entorno=array(),$PropiedadesModulo=array())
{
    $Salida ="<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">\n<title>" . _SIIS_APLICATION_TITLE . "</title>\n";
    $Salida.="<link href=\"themes/HTML/AzulXp/style/style.css\" rel=\"stylesheet\" type=\"text/css\"></head>\n";
    $Salida.="<body bgcolor=\"#f5f5ff\">\n<center><br><br>\n";
    if(GetVarConfigAplication('MostrarInfoEnviromentErrores'))
    {
        $size="90%";
    }
    else
    {
        $size="50%";
    }
    $Salida.=ThemeAbrirTabla("MENSAJE DEL SISTEMA",$size);
    $Salida.="<div align=\"center\" class='normal_11N'>";
    $Salida.="<h1><b>" . _SIIS_APLICATION_TITLE . "</b></h1>\n";
    $Salida.="<h2><b><font color='red'>$msg</font></b></h2>\n";
    $Salida.="$detalle\n<br>\n";

    if(!empty($modulo))
    {
        $Salida.="\n<br>\n";
        $Salida.="<TABLE width='90%' cellspacing='0' cellpadding='2' align='center' border='1' >\n";
        $Salida.="<tr><td class='modulo_table_list_title' colspan='2'>MODULO EN EJECUCION</td></tr>\n";
        
        $Salida.="<tr><td width='30%' class='modulo_table_list_title'>Contenedor</td><td width='70%' class='modulo_table_list'>$modulo[Contenedor]</td></tr>\n";
        $Salida.="<tr><td class='modulo_table_list_title'>Modulo</td><td class='modulo_table_list'>$modulo[Modulo]</td></tr>\n";
        $Salida.="<tr><td class='modulo_table_list_title'>Tipo</td><td class='modulo_table_list'>$modulo[Tipo]</td></tr>\n";
        $Salida.="<tr><td class='modulo_table_list_title'>Metodo</td><td class='modulo_table_list'>$modulo[Metodo]</td></tr>\n";
        
        $Salida.="</TABLE>\n";

    }    
    
    if(GetVarConfigAplication('MostrarInfoFileLineErrores'))
    {
        if(!empty($file))
        {
            $file = str_replace (GetVarConfigAplication('DIR_SIIS'), "", $file);
            $Salida.="\n<br>\n";
            $Salida.="<TABLE width='90%' cellspacing='0' cellpadding='2' align='center' border='1' >\n";
            $Salida.="<tr><td class='modulo_table_list_title' colspan='2'>ARCHIVO QUE GENERO ESTE MENSAJE</td></tr>\n";
            $Salida.="<tr><td width='30%' class='modulo_table_list_title'>FILE</td><td width='70%' class='modulo_table_list'>$file</td></tr>\n";
        
            if(!empty($line))
            {
                $Salida.="<tr><td class='modulo_table_list_title'>LINE</td><td class='modulo_table_list'>$line</td></tr>\n";
            }
            
            $Salida.="</TABLE>\n";
                
        }
    }
        
    if(GetVarConfigAplication('MostrarInfoEnviromentErrores'))
    {
        $Salida.="\n<br>\n";
        $Salida.="<TABLE width='90%' cellspacing='0' cellpadding='2' align='center' border='1' >\n";
        $Salida.="<tr><td class='modulo_table_list_title' colspan='2'>VALORES DEL VECTOR ".'$_REQUEST'."</td></tr>\n";
        
        foreach($_REQUEST as $k=>$v)
        {
            if(is_array($v))
            {
                $v=print_r($v,true);
            }
            if(empty($v))
            {
                $v='&nbsp;';
            }
            $Salida.="<tr><td width='30%' class='modulo_table_list_title'>$k</td><td width='70%' class='modulo_table_list'>$v</td></tr>\n";        
        }
        
        $Salida.="</TABLE>\n";    

        if(!empty($entorno))
        {
            if($entorno != $_REQUEST)
            {
                $Salida.="\n<br>\n";
                $Salida.="<TABLE width='90%' cellspacing='0' cellpadding='2' align='center' border='1' >\n";
                $Salida.="<tr><td class='modulo_table_list_title' colspan='2'>VALORES DEL VECTOR ".'$_REQUEST'." EN UNA POSIBLE LLAMADA A UN METODO EXTERNO</td></tr>\n";
                
                foreach($entorno as $k=>$v)
                {
                    if(is_array($v))
                    {
                        $v=print_r($v,true);
                    }
                    if(empty($v))
                    {
                        $v='&nbsp;';
                    }
                    $Salida.="<tr><td width='30%' class='modulo_table_list_title'>$k</td><td width='70%' class='modulo_table_list'>$v</td></tr>\n";        
                }
                
                $Salida.="</TABLE>\n";
            }

        }    
        
        $Salida.="\n<br>\n";
        $Salida.="<TABLE width='90%' cellspacing='0' cellpadding='2' align='center' border='1' >\n";
        $Salida.="<tr><td class='modulo_table_list_title' colspan='2'>VALORES DEL VECTOR ".'$_SESSION'."</td></tr>\n";
        
        foreach($_SESSION as $k=>$v)
        {
            if(is_array($v))
            {
                $v=print_r($v,true);
            }    
            if(empty($v))
            {
                $v='&nbsp;';
            }    
            $Salida.="<tr><td width='30%' class='modulo_table_list_title'>$k</td><td width='70%' class='modulo_table_list'>$v</td></tr>\n";        
        }
        
        $Salida.="</TABLE>\n";

        if(!empty($PropiedadesModulo))
        {        
            $Salida.="\n<br>\n";
            $Salida.="<TABLE width='90%' cellspacing='0' cellpadding='2' align='center' border='1' >\n";
            $Salida.="<tr><td class='modulo_table_list_title' colspan='2'>VALORES DE LAS PROPIEDADES DEL MODULO</td></tr>\n";
            
            foreach($PropiedadesModulo as $k=>$v)
            {
                $excepciones[]='salida';
                $excepciones[]='error';
                $excepciones[]='mensajeDeError';
                $excepciones[]='fileError';
                $excepciones[]='lineError';
                $excepciones[]='moduloError';
                $excepciones[]='errorPropiedadesModulo';
                $excepciones[]='envError';

                if(array_search ($k,$excepciones)===false)
                {
                    if(is_array($v))
                    {
                        $v=print_r($v,true);
                    }
                    if(empty($v))
                    {
                        $v='&nbsp;';
                    }        
                    $Salida.="<tr><td width='30%' class='modulo_table_list_title'>$k</td><td width='70%' class='modulo_table_list'>$v</td></tr>\n";        
                }
            }
            
            $Salida.="</TABLE>\n";        
        }
    }
        
    $Salida.="<h5>Esta aplicaci?n es desarrollada por:</h5><br />"._SIIS_DEVELOPER_LINK."<br />";
    $Salida.="</div>";
    $Salida.=ThemeCerrarTabla();
    $Salida.="<br><br></center>\n</body>\n</html>";
    return $Salida;
}


function ReturnModulo()
{
    
    if (!IncludeFile("classes/modules/classmodules.class.php")) {
        die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","El Archivo 'classes/modules/classmodules.class.php' NO SE ENCUENTRA"));
    }

    if(!class_exists('classModules')){
        die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","LA CLASE 'classModules' NO EXISTE"));
    }

    if($_REQUEST['contenedor'] == 'hc' && !UserGetVar(UserGetUID(),'sw_admin')){

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
        Die(MsgOut("NO SE CARGO EL MANEJADOR DE MODULOS","LA CLASE 'ManejadorDeModulos' NO EXISTE"));
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
        die(MsgOut($Modulo->Err(),$Modulo->ErrMsg(),$Modulo->ErrFile(),$Modulo->ErrLine(),$Modulo->ErrModulo(),$Modulo->ErrEnv(),$Modulo->ErrPropiedadesModulo()));
    }
    
    $returnModulo['GetSalida']=$Modulo->GetSalida();
    $returnModulo['GetJavaScripts']=$Modulo->GetJavaScripts();
    
    return $returnModulo;
    
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
    $Salida  = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
    $Salida .= "<html>\n";
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


function ReturnOpenCalendario($forma,$campo,$sep)
{
    global $_ROOT;
    global $VISTA;
    $RUTA = $_ROOT ."classes/calendariopropio/Calendario.php?forma=$forma&campo=$campo&separador=$sep";
    $Salida='<script language="javascript">'."\n".'function LlamarCalendario'.$campo.'()'."\n"."{"."\n"."window.open('".$RUTA."','CALENDARIO_SIIS','width=450,height=250,resizable=no,status=no,scrollbars=yes');"."\n".'}'."\n".'</script>'."\n";
    $imagen = "themes/$VISTA/" . GetTheme() . "/images/calendario/calendario.png";
    $Salida.="<a href=\"javascript:LlamarCalendario$campo()\"><img onMouseOver=\"window.status='Calendario';return true;\" onMouseOut=\"window.status=''; return true;\" src=\"$imagen\" border=0 alt=\"Ver Calendario\"></a> [dd".$sep."mm".$sep."aaaa]";
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

function RefrescarContenidoHomePage()
{
  if(SessionGetVar('StyleFrames')){
    return "\n\n<script language=\"javascript\">parent.frames.Contenido.location='Contenido.php';</script>\n\n";
  }
  return "";
}

function RefrescarTodo()
{
  if(SessionGetVar('StyleFrames')){
      return "\n\n<script language=\"javascript\">setTimeout('top.location.reload()',1000);</script>\n\n";
  }else{
    return "\n\n<script language=\"javascript\">setTimeout('reload()',1000);</script>\n\n";
  }
}

function RetornarWinOpenDatosPaciente($TipoId,$PacienteId,$Nombre,$class)
{
    if(!empty($class))
    {
        $salida1 ="<a href=javascript:DatosPaciente('$TipoId','$PacienteId') class=\"$class\">$Nombre</a>";
    }
    else
    {
        $salida1 ="<a href=javascript:DatosPaciente('$TipoId','$PacienteId')>$Nombre</a>";
    }
    return $salida1;
}

function RetornarWinOpenPagos($cuenta,$Nombre,$class)
{
    if(!empty($class))
    {
        $salida1 ="<a href=javascript:PagosPaciente($cuenta) class=\"$class\">$Nombre</a>";
    }
    else
    {
        $salida1 ="<a href=javascript:PagosPaciente($cuenta) class=\"label\">$Nombre</a>";
    }
    return $salida1;
}

function RetornarWinOpenDescuentosPaciente($cuenta,$Nombre,$class)
{
    if(!empty($class))
    {
        $salida1 ="<a href=javascript:DescuentosPaciente($cuenta) class=\"$class\">$Nombre</a>";
    }
    else
    {
        $salida1 ="<a href=javascript:DescuentosPaciente($cuenta) class=\"label\">$Nombre</a>";
    }
    return $salida1;
}

function RetornarWinOpenDescuentosEmpresa($cuenta,$Nombre,$class)
{
    if(!empty($class))
    {
        $salida1 ="<a href=javascript:DescuentosEmpresa($cuenta) class=\"$class\">$Nombre</a>";
    }
    else
    {
        $salida1 ="<a href=javascript:DescuentosEmpresa($cuenta) class=\"label\">$Nombre</a>";
    }
    return $salida1;
}

function RetornarWinOpenTotalPaciente($cuenta,$Nombre,$class)
{
    if(!empty($class))
    {
        $salida1 ="<a href=javascript:TotalPaciente($cuenta) class=\"$class\">$Nombre</a>";
    }
    else
    {
        $salida1 ="<a href=javascript:TotalPaciente($cuenta) class=\"label\">$Nombre</a>";
    }
    return $salida1;
}


function RetornarWinOpenDetalleCamas($ingreso,$Nombre,$class)
{
    if(!empty($class))
    {
        $salida1 ="<a href=javascript:DetalleCamas($ingreso) class=\"$class\">$Nombre</a>";
    }
    else
    {
        $salida1 ="<a href=javascript:DetalleCamas($ingreso) class=\"label\">$Nombre</a>";
    }
    return $salida1;
}

function RetornarWinOpenDatosProfesional($TipoId,$TerceroId,$Nombre,$class)
{
    if(!empty($class))
    {
        $salida1 ="<a href=javascript:DatosProfesional('$TipoId','$TerceroId') class=\"$class\">$Nombre</a>";
    }
    else
    {
        $salida1 ="<a href=javascript:DatosProfesional('$TipoId','$TerceroId')>$Nombre</a>";
    }
    return $salida1;
}

function RetornarWinOpenDatosEvolucionInactiva($evolucion,$nombre,$class)
{
    if(!empty($class))
    {
        if(!empty($nombre))
        {
            $salida1 ="<a href=javascript:DatosEvolucionInactiva('$evolucion') class=\"$class\">$nombre</a>";
        }
        else
        {
            $salida1 ="<a href=javascript:DatosEvolucionInactiva('$evolucion') class=\"$class\">Consulta No.: $evolucion</a>";
        }
    }
    else
    {
        if(!empty($nombre))
        {
            $salida1 ="<a href=javascript:DatosEvolucionInactiva('$evolucion')>$nombre</a>";
        }
        else
        {
            $salida1 ="<a href=javascript:DatosEvolucionInactiva('$evolucion')>Consulta No.: $evolucion</a>";
        }
    }
    return $salida1;
}


function RetornarWinOpenDatosAutorizacion($int,$ext,$nombre)
{
        if(!empty($nombre))
        {
            $salida1 ="<a href=javascript:DatosAutorizacion1('$int','$ext')>$nombre</a>";
        }
        else
        {
            $salida1 ="<a href=javascript:DatosAutorizacion1('$int','$ext')>Autorizaci?n</a>";
        }
    return $salida1;
}


function RetornarWinOpenDatosBD($tipoid,$paciente,$plan)
{
            $salida1 ="<a href=javascript:DatosBD('$tipoid','$paciente',$plan)>DATOS PACIENTE EN BD</a>";
            return $salida1;
}


function RetornarWinOpenDatosBDAnteriores($tipoid,$paciente,$plan,$cantidad)
{
            $salida1 ="<a href=javascript:DatosBDAnteriores('$tipoid','$paciente',$plan,$cantidad)>DATOS BD ULTIMOS PERIODOS</a>";
            return $salida1;
}

function RetornarWinOpenDatosBuscadorBD($departamento,$forma)
{
            $salida1 ="<a href=javascript:BuscadorBD('$departamento','$forma')>Busqueda en Base de Datos</a>";
            return $salida1;
}

function RetornarWinOpenBuscadorOcupaciones($forma,$prefijo,$defecto,$valor)
{
            $salida1 ="<input type=\"hidden\" name=\"ocupacion_id".$prefijo."\" value=\"$valor\">";
            $salida1.="<textarea name=\"descripcion_ocupacion".$prefijo."\" READONLY cols=\"50\" rows=\"2\">$defecto</textarea>";
            $salida1.="<input type=\"button\" name=\"ocupacion\" value=\"OCUPACI?N\" class=\"input-submit\" onclick=javascript:Ocupaciones('".$forma."','".$prefijo."')>";
            return $salida1;
}

/**
 * Retorna un link para consultar un reporte del Mige
 *
 * @param string reporte
 * @param array params
 * @param string link
 * @return salida
 * @access public
 */
function RetornarWinOpenConsultarReporteMige($reporte,$params,$link) 
{
	if(!isset($link) || $link=='')
		$link="Consultar reporte";
	$parametros='';
	if(is_array($params))
	{
		$i=0;
		foreach($params as $param)
		{
			$parametros .= "&params[$i]=$param";
			$i++;
		}
	}
	$salida = "<a href=\"javascript:ConsultarReporteMige('$reporte','$parametros')\">$link</a>";
	return $salida;
}
?>
