<?php

/**
 * $Id: api.inc.php,v 1.2 2009/11/09 17:55:24 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: API de la Aplicacion
 */

// api.inc.php  05/08/2002
function GetTheme()
{
    global $VISTA;
    global $_ROOT;
    static $theme;
    if(!empty($theme)){
        return $theme;
    }

    if(!UserLoggedIn()){
        $theme = GetVarConfigAplication('DefaultTheme');
        if(empty($theme)){
            $theme='default';
        }elseif(!is_dir($_ROOT."themes/$VISTA/$theme")){
            $theme='default';
        }
    }else{
        $UserVars= UserGetVars(UserGetUID());
        if(!empty($UserVars['Tema'])){
            $theme=$UserVars['Tema'];
            if(!is_dir($_ROOT."themes/$VISTA/$theme")){
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
    global $_ROOT;
  $themePath = $_ROOT."themes/$VISTA/" . GetTheme();
  return $themePath;
}


function MsgOut($msg,$detalle='',$file='',$line='',$modulo=array(),$entorno=array(),$PropiedadesModulo=array())
{

    if (function_exists('vistaMsgOut') and function_exists('ThemeAbrirTabla')) {
        return vistaMsgOut($msg,$detalle,$file,$line,$modulo,$entorno,$PropiedadesModulo);
    }else{
        $Salida ="<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">\n<title>" . _SIIS_APLICATION_TITLE . "</title>\n</head>\n";
        $Salida.="<body>\n<center>\n";
        $Salida.="<h1><b>" . _SIIS_APLICATION_TITLE . "</b></h1>\n<br />\n";
        $Salida.="<h2><b><font color='red'>$msg</font></b></h2>\n<br />\n";
        $Salida.="$detalle\n<br />\n";
        $Salida.="<h5>Esta aplicación es desarrollada por:</h5><br />" . _SIIS_DEVELOPER_LINK . "<br />";
        $Salida.="</center>\n</body>\n</html>";
    }
    return $Salida;
}

function GetLimitBrowser()
{
    
    static $limit;
    if(!empty($limit)){
        return $limit;
    }

$limit2=UserGetVar(UserGetUID(),'LimitRowsBrowser');
 if($limit2>0)
 {
  $limit=$limit2;
 }else
 {
        $limit2=ModuloGetVar('','','LimitRowsBrowser');
        if($limit2>0)
        {
                    $limit=$limit2;
                }else
                {
                    $limit= 20;
                }
  }
return $limit;
}


function GetVarConfigAplication($var)
{
    global $ConfigAplication;

    if(!isset($ConfigAplication[$var])){
        return '';
    }else{
        return $ConfigAplication[$var];
    }
}



function GetBaseURI()
{
    static $path;
    if(!empty($path)){
        return $path;
    }

    if (isset($_SERVER['REQUEST_URI'])) {
        $path = $_SERVER['REQUEST_URI'];
    } else {
        $path = getenv('REQUEST_URI');
    }
    if ((empty($path)) ||(substr($path, -1, 1) == '/')) {
        $path = getenv('PATH_INFO');
        if (empty($path)) {
            if (isset($_SERVER['SCRIPT_NAME'])) {
                $path = $_SERVER['SCRIPT_NAME'];
            } else {
                $path = getenv('SCRIPT_NAME');
            }
        }
    }

    $path = preg_replace('/[#\?].*/', '', $path);
    $path = dirname($path);

    if (preg_match('!^[/\\\]*$!', $path)) {
        $path = '';
    }

    return $path;
}

function GetBaseHost()
{
    if (empty($_SERVER['HTTP_HOST'])) {
        $host = getenv('HTTP_HOST');
    } else {
        $host = $_SERVER['HTTP_HOST'];
    }

    if (empty($host)) {
        return false;
    }

    return $host;

}

function GetBaseURL()
{
    static $baseURL;
    if(!empty($baseURL)){
        return $baseURL;
    }

    $servidor = GetBaseHost();

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        $protocolo = 'https://';
    } else {
        $protocolo = 'http://';
    }

    $path = GetBaseURI();

    $baseURL = "$protocolo$servidor$path/";
    return $baseURL;
}


function GetIPAddress()
{
    static $ipaddr;
    if(!empty($ipaddr)){
        return $ipaddr;
    }

    $ipaddr = $_SERVER['REMOTE_ADDR'];
    if (empty($ipaddr)) {
        $ipaddr = getenv('REMOTE_ADDR');
    }
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddr = $_SERVER['HTTP_CLIENT_IP'];
    }
    $tmpipaddr = getenv('HTTP_CLIENT_IP');
    if (!empty($tmpipaddr)) {
        $ipaddr = $tmpipaddr;
    }
    if  (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddr = preg_replace('/,.*/', '', $_SERVER['HTTP_X_FORWARDED_FOR']);
    }
    $tmpipaddr = getenv('HTTP_X_FORWARDED_FOR');
    if  (!empty($tmpipaddr)) {
        $ipaddr = preg_replace('/,.*/', '', $tmpipaddr);
    }

    return $ipaddr;

}

function GetHostAccess()
{
  list($dbconn) = GetDBconn();
  $iphost=GetIPAddress();

  $query = "SELECT sw_bloqueo,hostname
            FROM system_host
            WHERE ip = '$iphost'";
  $result = $dbconn->Execute($query);

  $hostname = gethostbyaddr($iphost);

  if ($result->EOF) {
    $queryInsert = "INSERT INTO system_host (ip,hostname)
                    VALUES ('$iphost','$hostname')";
    $dbconn->Execute($queryInsert);
    return true;
  }else{
    list($bloqueo,$host) = $result->fields;

    if($host != $hostname){
      $query = "UPDATE system_host SET hostname = '$hostname' WHERE ip = '$iphost'";
      $dbconn->Execute($query);
      if(!$dbconn->ErrorNo() != 0) {
        InsertLogHost($iphost,5,"Nombre de Host anterior ($host), nombre nuevo ($hostname)");
      }
    }
    if($bloqueo == 0 ){
      return true;
    }
  }
  return false;
}

function GetStyleFrames()
{
  // Cancelar el estilo de Frames - regularmente cuando el browser no soporta Frames
  if($_REQUEST['CancelFramesIndex']){
    SessionSetVar('StyleFrames', 0);

    list($dbconn) = GetDBconn();

    $query = "UPDATE system_host
        SET styleframes = 0
        WHERE ip = '".GetIPAddress()."'";
    $dbconn->Execute($query);
  }

  // Si esta iniciando sesion configura el estilo Frames/NoFrames
  if(!SessionIsSetVar('StyleFrames')){

    if(!GetVarConfigAplication('IPStyleFrames')){

      if(GetVarConfigAplication('StyleFrames')){
        $StyleFrames=1;
      } else {
        $StyleFrames=0;
      }

      SessionSetVar('StyleFrames', $StyleFrames);

    } else {

      list($dbconn) = GetDBconn();

      $query = "SELECT styleframes
            FROM system_host
            WHERE ip = '".GetIPAddress()."'";
      $result = $dbconn->Execute($query);

      if ($result->EOF) {
        $result->Close();
        $query = " INSERT INTO system_host
              (
                ip,
                hostname
              )
              VALUES
              (
                '" . GetIPAddress() . "',
                '" . gethostbyaddr(GetIPAddress()) . "'
              )";
        $dbconn->Execute($query);

        if($dbconn->ErrorNo() != 0) {
          die(MsgOut('ERROR AL INICIAR LA INTERFACE','No se pudo registrar el host.'));
        }

        if(GetVarConfigAplication('StyleFrames')){
          $StyleFrames=1;
        } else {
          $StyleFrames=0;
        }

        SessionSetVar('StyleFrames', $StyleFrames);

      } else {

              list($StyleFrames) = $result->fields;

              if($StyleFrames != 0 && $StyleFrames != 1){
          if(GetVarConfigAplication('StyleFrames')){
            $StyleFrames=1;
          } else {
            $StyleFrames=0;
          }
        }

              SessionSetVar('StyleFrames', $StyleFrames);

      }
    }
    return true;
  }
}


function SIIS_sfrtime($fecha,$tipo='M',$abreviado=false)
{
    if(!empty($fecha))
    {
        $a=explode("-",$fecha);
        $b=explode(" ",$a[2]);
        $c=explode(":",$b[1]);
        if($tipo=='M')
        {
            if($abreviado==false)
            {
                return strftime("%A %d de %B de %Y a las %H:%M",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]));
            }
            else
            {
                return strftime("%a %d de %b de %Y a las %H:%M",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]));
            }
        }
        else
        {
            if($c[0]>=0 and $c[0]<12)
            {
                $p='am';
            }
            else
            {
                $p='pm';
            }
            if($abreviado==false)
            {
                return strftime("%A %d de %B de %Y a las %I:%M $p",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]));
            }
            else
            {
                return strftime("%a %d de %b de %Y a las %I:%M $p",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]));
            }
        }
    }
    return false;
}


  function GetDBconn()
  {
      global $dbconn;
      return array($dbconn);
  }

  function IncludeFile($file, $once=true)
  {
      global $_ROOT;
      $fileName="$_ROOT$file";

      if(file_exists($fileName)){
          if($once){
              include_once  "$fileName";
          }else{
              include  "$fileName";
          }
      }else{
          return false;
      }
      return true;
  }
  /**
  *
  */
  function IncludeFileModulo($file,$directorio, $contenedor, $modulo)
  { 
    $fileName = "";
    switch($contenedor)
    {
      case 'hc':
      case 'app':
      case 'system':
        $ruta = $contenedor."_modules/".$modulo."/".$directorio."/";
				$fileName = $ruta.$carpeta.$file.".php";
      break;
    }
    IncludeFile($fileName);
    return true;
  }


function IncludeLib($lib)
{
    $file="includes/$lib" .".inc.php";
    return IncludeFile($file);

}


/**
* Funcion para incluir classes
*
* Las clases a incluir pueden estar en las siguientes ubicaciones:
*
* 1. Directorio  classes/classNombre/classNombre.class.php
*    En este caso solo requiere el argumento $class='classNombre'
*    EJ: IncludeClass('classNombre');
*
* 2. Directorio  classes/directorio/classNombre.class.php
*    En este caso solo requiere los argumento $class='classNombre' y $directorio
*    EJ: IncludeClass('classNombre','directorio');
*
* 3. En submodulos
*      a. Directorio  hc_modules/submoduloNombre/classes/hc_submoduloNombre_clasenombre.class.php
*         Requiere los argumentos $class='classNombre', $contenedor='hc' y $modulo='submoduloNombre'
*         EJ: IncludeClass('classNombre',null,'hc','submoduloNombre');
*
*      b. En una carpeta dentro del directorio classes del submodulo
*         Directorio  hc_modules/submoduloNombre/classes/directorio/hc_submoduloNombre_clasenombre.class.php
*         Requiere adicionalmente el argumento $directorio
*         EJ: IncludeClass('classNombre','directorio','hc','submoduloNombre');
*
* 4. En modulos (app y system)
*    Es igual al ejemplo anterior en submodulos solo que tambien puede requerir el argumento $tipo con uno de los siguientes valores('user','admin')
*
*    a. En el directorio contenedor_modules/modulo/tipo_classes/contenedor_modulo_clasenombre.class.php
*       EJ: Para el directorio app_modules/modulo/classes/app_modulo_clasenombre.class.php
*           Utilizar IncludeClass('clasenombre',null,'app','modulo');
*       EJ: Para el directorio app_modules/modulo/userclasses/app_modulo_clasenombre.class.php
*           Utilizar IncludeClass('classNombre',null,'app','modulo','user');
*       EJ: Para el directorio app_modules/modulo/adminclasses/app_modulo_clasenombre.class.php
*           Utilizar IncludeClass('clasenombre',null,'app','modulo','admin');
*
*    b. En caso de emplear subdirectorios incluir el argumento $directorio
*       EJ: Para el directorio app_modules/modulo/classes/directorio/app_modulo_clasenombre.class.php
*           Utilizar IncludeClass('clasenombre','directorio','app','modulo');
*
*
* @param string $class Nombre de la clase sin extenciones
* @param string $directorio opcional nombre del directorio donde se encuentra la clase
* @param string $contenedor (app,system,hc si son modulos null si es en el directorio classes)
* @param string $modulo null si es en el directorio classes de lo contrario es obligatorio con el nombre del modulo o submodulo
* @param string $tipo opcional si es en un modulo indica el sufijo de la carpeta ej user=>userclasses, admin=>adminclasses ''=>classes
* @return boolean true si se incluye y false si falla
* @access private
*/
function IncludeClass($class, $directorio='', $contenedor=null, $modulo=null, $tipo='')
{
    if(empty($class)) return false;

    switch($contenedor)
    {
        case 'hc':
            if(empty($modulo)) return false;
            if(empty($directorio))
            {
                $directorio = "/classes/hc_";
            }
            else
            {
                $directorio = "/classes/$directorio/hc_";
            }
            $file = "hc_modules/$modulo$directorio$modulo"."_".$class.".class.php";
        break;
        case 'hc1':
						$carpeta = "";
						if(empty($modulo)) return false;
            if(empty($directorio))
            {
              $carpeta = "classes/";
              $directorio = "hc_modules/$modulo/";
            }
            else
            {   
							$directorio = "hc_modules/$modulo/$directorio/";
            }
						            
						//$file = $contenedor.$directorio.$tipo."classes/".$class.".class.php";
						$file = $directorio.$tipo.$carpeta.$class.".class.php";
            break;
        case 'app':
        case 'system':
            $carpeta = "classes/";
						
						if(empty($modulo)) return false;
            if(empty($directorio))
            {
                $directorio = "_modules/$modulo/";
            }
            else
            {
								if(strtolower($directorio) != "userclasses") $carpeta = "";
                
								$directorio = "_modules/$modulo/$directorio/";
            }
						            
						//$file = $contenedor.$directorio.$tipo."classes/".$class.".class.php";
						$file = $contenedor.$directorio.$tipo.$carpeta.$class.".class.php";
            break;

        default:

            if(empty($directorio))
            {
                $directorio = $class;
            }
            $file="classes/$directorio/$class".".class.php";
    }
    return IncludeFile($file);
}

function PrepararCadenaParaSQL()
{
    $Salida = array();
    foreach (func_get_args() as $cadena) {

        // Prepararar variable
        if (!get_magic_quotes_runtime()) {
            $cadena = addslashes($cadena);
        }

        // Agregar al array
        array_push($Salida, $cadena);
    }

    // Retornar Cadena(s) Formateadas
    if (func_num_args() == 1) {
        return $Salida[0];
    } else {
        return $Salida;
    }
}


function LimpiarCadenaInput()
{
    $search = array('|</?\s*SCRIPT.*?>|si',
                    '|</?\s*FRAME.*?>|si',
                    '|</?\s*OBJECT.*?>|si',
                    '|</?\s*META.*?>|si',
                    '|</?\s*APPLET.*?>|si',
                    '|</?\s*LINK.*?>|si',
                    '|</?\s*IFRAME.*?>|si',
                    '|STYLE\s*=\s*"[^"]*"|si');

    $replace = array('');

    $Salida = array();
    foreach (func_get_args() as $var) {
        global $$var;
        if (empty($var)) {
            return;
        }
        $cadena = $$var;

        if (!isset($cadena)) {
            array_push($Salida, NULL);
            continue;
        }
        if (empty($cadena)) {
            array_push($Salida, $cadena);
            continue;
        }

        if (get_magic_quotes_gpc()) {
            AgregarStripSlashes($cadena);
        }

        array_push($Salida, $cadena);
    }

    if (func_num_args() == 1) {
        return $Salida[0];
    } else {
        return $Salida;
    }
}


function AgregarStripSlashes ($cadena)
{
    if(!is_array($cadena)) {
        $cadena = stripslashes($cadena);
    } else {
        array_walk($cadena,'AgregarStripSlashes');
    }
}


function PrepararCadenaParaMostrar()
{
    static $search = array('/(.)@(.)/se');

    static $replace = array('"&#" .
                            sprintf("%03d", ord("\\1")) .
                            ";&#064;&#" .
                            sprintf("%03d", ord("\\2")) . ";";');

    $Salida = array();
    foreach (func_get_args() as $ourvar) {

        $ourvar = htmlspecialchars($ourvar);

        $ourvar = preg_replace($search, $replace, $ourvar);

        array_push($Salida, $ourvar);
    }

    if (func_num_args() == 1) {
        return $Salida[0];
    } else {
        return $Salida;
    }
}


function SessionGetVar($name)
{
    if (!empty($_SESSION[$name])) {
        return $_SESSION[$name];
    }
    return;
}


function SessionSetVar($name, $value)
{
    $_SESSION[$name] = $value;
    return true;
}


function SessionIsSetVar($name)
{
    if (!isset($_SESSION[$name])){
        return false;
    }
    return true;
}


function SessionDelVar($name)
{
    unset($_SESSION[$name]);
    return true;
}

function ImplodeArrayAssoc($array=array())
{
  if(empty($array)){
    return false;
  }

  $newArray=array();

  foreach($array as $k=>$v){
    $k=str_replace(':',' ',$k);
    $k=str_replace('|',' ',$k);
    $v=str_replace(':',' ',$v);
    $v=str_replace('|',' ',$v);
    $newArray[]="$k:$v";
  }
  return implode("|",$newArray);

 }

 function ExplodeArrayAssoc($cadena)
 {
   if(empty($cadena)){
    return false;
    }
    $newArray=array();

    $array=explode("|",$cadena);

    foreach($array as $k=>$v){
      $fila=explode(":",$v);
      if(is_array($fila) && !empty($fila)){
        $newArray[$fila[0]]=$fila[1];
      }
    }

    return $newArray;
 }

function InsertLogUser($usuario_id=0,$tipo_log=0,$detalle='')
{
  list($dbconn) = GetDBconn();

  $query = "INSERT INTO system_usuarios_log (usuario_id,tipo_log,detalle,ip_address)
            VALUES($usuario_id,$tipo_log,'$detalle','".GetIPAddress()."')";
  $dbconn->Execute($query);

  if($dbconn->ErrorNo() != 0) {
      return false;
  }
  return true;
}

function InsertLogHost($host,$tipo_log=0,$detalle='')
{
  if(empty($host)){
    return false;
  }
  list($dbconn) = GetDBconn();

  $query = "INSERT INTO system_host_log (host,tipo_log,detalle)
            VALUES('$host',$tipo_log,'$detalle')";
  $dbconn->Execute($query);

  if($dbconn->ErrorNo() != 0) {
      return false;
  }
  return true;
}

//esta funcion de asignar numero de documento es de entregar una numeracion
//segun el tipo de doc(caja,etc..) en factura o recibos de caja.
function AsignarNumeroDocumento($Tiponumeracion)
{
        list($dbconn) = GetDBconn();

        if((!empty($Tiponumeracion)))
            {
                    $sql="BEGIN WORK;  LOCK TABLE documentos IN ROW EXCLUSIVE MODE";
                    $result = $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0) {
                        die(MsgOut("Error al iniciar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
                        return false;
                    }


                                         $sql="SELECT numero_digitos FROM documentos WHERE documento_id=$Tiponumeracion";
                    $result = $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0) {
                        die(MsgOut("Error al traer los numeros de redondeo de la tabla documentos","Error DB : " . $dbconn->ErrorMsg()));
                        return false;
                    }

                                        $digitos=$result->fields[0];//numero_digitos es los numeros que le vamos a colocar al lado derecho de la secuencia
                                        //lpad(nextval('asignarPiezavirtual_seq'),3,0)



                    $sql="UPDATE documentos set numeracion=numeracion + 1
                                WHERE  documento_id= $Tiponumeracion";
                    $result = $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0) {
                        die(MsgOut("Error al actualizar numeracion ","Error DB : " . $dbconn->ErrorMsg()));
                        return false;
                    }
                    if($dbconn->Affected_Rows() == 0){
                        die(MsgOut("Error al actualizar numeracion de documentos","El tipo de numeracion '$Tiponumeracion' no existe."));
                        return false;
                    }

                    $sql="SELECT numeracion as numero,prefijo FROM documentos WHERE documento_id=$Tiponumeracion";
                    $result = $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0) {
                        die(MsgOut("Error al actualizar numeracion","Error DB : " . $dbconn->ErrorMsg()));
                        return false;
                    }
                    if ($result->EOF) {
                        die(MsgOut("Error al actualizar numeracion","El tipo de numeracion '$Tiponumeracion' no existe."));
                        return false;
                    }
                    list($numerodoc['numero'],$numerodoc['prefijo'])=$result->fetchRow();
                    return $numerodoc;
            }

            die(MsgOut("Error al actualizar numeracion","El tipo de numeracion '$Tiponumeracion' esta vacio."));
            return false;
}

function GuardarNumeroDocumento($commit=true)
{
        list($dbconn) = GetDBconn();
        if($commit)
        {
            $sql="COMMIT";
        }
        else
        {
            $sql="ROLLBACK";
        }

        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            die(MsgOut("Error al terminar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
            return false;
        }
        return true;
}



function RetornarDatosRegistro($UsuarioID='',$Fecha='')
{
    if(empty($UsuarioID))
    {
        return '';
    }
    global $_ROOT;
    $RUTA = $_ROOT ."classes/classbuscador/buscador.php?Usuario=$UsuarioID&fecha=$Fecha";
    return '<a href="javascrip:window.open('.$RUTA.',"","")">';
}

  function FormatoFecha($estilo)
  {
    $mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
    
    switch ($estilo)
    {
      case 1:  
        $fecha = $dias[(date("w"))].",".date("d")." de ".$mes[date("n")-1]." de ".date("Y");
        return $fecha;  
      break;
      case 2:  return strftime("%B %d de %Y");  break;
      case 3:  return date("d/m/Y");  break;
      case 4:  return date("Y/m/d");  break;
      case 5:  return date("d-m-Y");  break;
      case 6:  return date("Y-m-d");  break;
    }
  }

function GetSalarioMinimo($anno,$dia=true)
{
    static $SMLV;

    if ($dia)
    {
        $opcion="dia";
    }
    else
    {
        $opcion="mes";
    }

    if(!$SMLV[$anno][$opcion])
    {
        list($dbconn)=GetDBConn();

        $sql="SELECT salario_dia,salario_mes FROM salario_minimo_ano WHERE ano='$anno'";
        $result = $dbconn->Execute($sql);

        if($dbconn->ErrorNo() != 0) return false;
        if($result->EOF) return false;
        list($SMLV[$anno]['dia'],$SMLV[$anno]['mes'])=$result->FetchRow();
        $result->Close();
    }
    return $SMLV[$anno][$opcion];
}


  function FormatoValor($valor,$digitos)
  {
    if(!$digitos) $digitos = GetDigitosRedondeo();
    
    return (number_format($valor,$digitos,',','.'));
  }


function GetDigitosRedondeo()
{
    static $numdigitos;

    if(!empty($numdigitos) || $numdigitos===0){
        return $numdigitos;
    }

    list($dbconn)=GetDBConn();
    $sql="SELECT get_digitos_redondeo();";
    $result=$dbconn->Execute($sql);
    if ($dbconn->ErrorNo() != 0) {
        $numdigitos = 0;
        return 0;
    }
    if ($result->EOF) {
        $numdigitos = 0;
        return 0;
    }
    list($numdigitos)=$result->fetchRow();
    $result->Close();

    if(!is_numeric($numdigitos)){
        $numdigitos = 0;
    }

    return $numdigitos;
}


function RedondearValores($valor)
{
    return round($valor,GetDigitosRedondeo());
}

/**
 * Retorna un objeto de tipo Smarty
 *
 * @param directorio_templates string
 */
function getSmarty($dir)
{
    if (!IncludeClass('Smarty','Smarty/libs'))
        return false;
    global $_ROOT;
    $smarty = new Smarty;
    $smarty->template_dir = $dir.'/templates';
    $smarty->compile_dir = $_ROOT.'classes/Smarty/templates_c';
    $smarty->cache_dir = $_ROOT.'classes/Smarty/cache';
    return $smarty;
}

/**
 * Retorna un objeto de tipo Smarty
 *
 * @param directorio_templates string
 */
function getXajax()
{
		global $xajax;		
		
    if(!is_object($xajax))
		{
			global $_ROOT;
			include $_ROOT.'classes/xajax/xajax_core/xajax.inc.php';
			//include $_ROOT.'classes/xajax/xajax_plugins/response/comet/comet.inc.php';
			//include $_ROOT.'classes/xajax/xajax.inc.php';
		  $xajax = new xajax();
		}

		return array($xajax);
}

/**
 * Retorna el valor en letras de un numero
 *
 * @param $valor numeric
 */
function ValorEnLetras($valor,$tipo=1,$UnidadEntera=null,$UnidadDecimal=null)
{
    static $conversor;

    if(!$conversor || !is_object($conversor))
    {
        if(!class_exists(NumeroEnLetras))
        {
            if (!IncludeClass('NumeroEnLetras'))
            {
                return false;
            }
        }
        $conversor= new NumeroEnLetras($valor);

    }
    else
    {
       $conversor-> SetValorNumerico($valor);
    }
     if($conversor->error!=0)
     {
        return false;
     }
    if($UnidadEntera)
    {
        $conversor->SetUnidadEntera($UnidadEntera);
    }
    else
    {
        $conversor->SetUnidadEntera('pesos');
    }
    if($UnidadDecimal)
    {
        $conversor->SetUnidadDecimal($UnidadDecimal);
    }
    else
    {
        $conversor->SetUnidadDecimal('centavos');
    }

    $valorEnLetras = $conversor->GetValorEnLetras($tipo);

    return $valorEnLetras;
}

/**
    *Retorna el nombre del mes
    *
    *@param $mes numeric
    */
function GetMes($mes)
{
        if(empty($mes))
        {
                return false;
        }

        $mes = abs($mes);

        $meses[1]='Enero';
        $meses[2]='Febrero';
        $meses[3]='Marzo';
        $meses[4]='Abril';
        $meses[5]='Mayo';
        $meses[6]='Junio';
        $meses[7]='Julio';
        $meses[8]='Agosto';
        $meses[9]='Septiembre';
        $meses[10]='Octubre';
        $meses[11]='Noviembre';
                $meses[12]='Diciembre';

        return $meses[$mes];
}

/**
 * Retorna el string html para crear un editor FCK
 *
 * @param string Nombre
 * @param string Height
 * @param string Width
 * @param string Valor
 */
function getFckeditor($Nombre,$Height="200",$Width="100%",$Valor='',$ToolBar='siis')
{
    global $_ROOT;
    include_once($_ROOT."classes/FCKeditor/fckeditor.php");
    $oFCKeditor = new FCKeditor($Nombre);
    $oFCKeditor->Value      = $Valor;
    $oFCKeditor->Width  = $Width ;
    $oFCKeditor->Height = $Height ;
    $oFCKeditor->ToolbarSet = $ToolBar;
    //$oFCKeditor->BasePath   = GetBaseURI()."/classes/FCKeditor/" ;
    $URI=split("/classes",GetBaseURI());
		$oFCKeditor->BasePath   = $URI[0]."/classes/FCKeditor/" ;
		$oFCKeditor->Config['SkinPath'] = $oFCKeditor->BasePath.'editor/skins/office2003/';
    return $oFCKeditor->CreateHtml() ;
}

/**
 * Funcion para descargar archivos
 *
 * @param string archivo(archivo a descargar)
 * @param string nombre(nombre del link/boton) para descargar
 * @param bool link(si es verdadero muestra un link de lo contrario muestra el boton)
 * @param bool comprimir(si es verdadero la descarga comprimira el archivo, de lo contrario lo descarga talcual)
 * @param bool boton(si es verdadero muestra el boton de lo contrario el download se hara automatico)
 * @return string(retorna un link, o un formulario(boton))
 */
function download($archivo,$nombre="Descargar",$link=false,$comprimir=false,$boton=true)
{
    static $i;//variable estatica para numerar los formularios y evitar que se solapen los nombres de los formularios
    $accion= "download.php?archivo=$archivo";
    if($comprimir)
        $accion .=  "&comprimir=1";
    if($link)
        $salida .= "<a href=\"$accion\" >$nombre</a>\n";
    else
    {
        $salida .= "<form name=\"frmDownload$i\" action=\"$accion\" method=\"post\" >\n";
        if($boton)
        {
            $salida .= "<input type=\"submit\" class=\"input-submit\" value=\"$nombre\">\n";
        }
        else
        {
            $js .= "<script language=\"javascript\">\n";
            $js .= "    document.frmDownload$i.submit();\n";
            $js .= "</script>\n";
        }
        $salida .= "</form>\n";
        $salida .= $js;
        $i++;
    }
    return $salida;
}//Fin download
?>