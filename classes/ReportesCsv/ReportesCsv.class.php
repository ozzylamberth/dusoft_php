<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ReportesCsv.class.php,v 1.2 2010/06/03 20:47:12 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: ReportesCsv
  * Clase encarga de crear los javascripts para hacer la descarga de los archivos
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ReportesCsv
  {
    /**
    * Variable encargada de guardar el nombre de la funcion javascript q se ejecutara
    *
    * @var String $nameJavaFunction
    */
    var $nameJavaFunction = "";
    var $i = 0;
    /**
    * constructor de la clase
    */
    function ReportesCsv(){}
    /**
    * Funcion que devuelve el nombre de la funcion javascript
    *
    * @return string
    */
    function GetJavaFunction()
    {
      return $this->nameJavaFunction;
    }
    /**
    * Funcion encargada de crear el javascript para permitir
    * visualizar la ventana, desde donde se descarga el archivo csv
    *
    * @param string $tipo Tipo de modlo
    * @param string $modulo Nombre del modulo
    * @param string $nombre_archivo Nombre de la clase que se va ainstanciar para sacar el reporte
    * @param array $parametros Arreglo de parametros necesarios para el reporte
    * @param string $separador Separdor (comas o tabs)
    *
    * @return string
    */
    function GetJavacriptReporte($tipo,$modulo,$nombre_archivo,$parametros,$separador,$opciones = array())
    {
    	$VISTA = 'HTML';
      $_ROOT = '../../';
      include $_ROOT.'includes/enviroment.inc.php';
      
      $url = "classes/ReportesCsv/reportes.class.php?inicio=1";
      $datos = array("tipo_modulo" => $tipo,
                     "nombre_modulo" => $modulo,
                     "nombre_archivo" => $nombre_archivo,
                     "parametros" => $parametros,
                     "separador" => $separador,
                     "opciones" => $opciones);
      
      $ruta = GetBaseURL().$url.UrlRequest($datos);
      
      $this->nameJavaFunction = " GetCsv_".($this->i++)."()";
  		
      $html  = "<script language='javascript'>\n";
  		$html .= "  function ".$this->nameJavaFunction."\n";
      $html .= "  {\n";
  		$html .= "    var width=\"400\"\n";
  		$html .= "    var height=\"300\"\n";
  		$html .= "    var winX=Math.round(screen.width/2)-(width/2);\n";
  		$html .= "    var winY=Math.round(screen.height/2)-(height/2);\n";
  		$html .= "    var nombre=\"Printer_Mananger\";\n";
  		$html .= "    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",location=yes,resizable=no,status=no,scrollbars=yes\";\n";
  		$html .= "    var url = '".$ruta."';\n";
  		$html .= "    window.open(url, nombre, str).focus();\n";
      $html .= "  }\n";
  		$html .= "</script>\n";
      
      return $html;
    }
    /**
    * Funcion encargada de crear el javascript para permitir
    * visualizar la ventana, desde donde se descarga el archivo xml
    *
    * @param string $tipo Tipo de modlo
    * @param string $modulo Nombre del modulo
    * @param string $nombre_archivo Nombre de la clase que se va ainstanciar para sacar el reporte
    * @param array $parametros Arreglo de parametros necesarios para el reporte
    *
    * @return string
    */
    function GetJavacriptReporteXml($tipo,$modulo,$nombre_archivo,$parametros,$opciones)
    {
    	$VISTA = 'HTML';
      $_ROOT = '../../';
      include $_ROOT.'includes/enviroment.inc.php';
      
      $url = "classes/ReportesCsv/reportes.class.php?inicio=1";
      $datos = array("tipo_modulo" => $tipo,
                     "nombre_modulo" => $modulo,
                     "nombre_archivo" => $nombre_archivo,
                     "parametros" => $parametros,
                     "opciones"=>$opciones);
      
      $ruta = GetBaseURL().$url.UrlRequest($datos);
      
      $this->nameJavaFunction = " GetCsv_".($this->i++)."()";
  		
      $html  = "<script language='javascript'>\n";
  		$html .= "  function ".$this->nameJavaFunction."\n";
      $html .= "  {\n";
  		$html .= "    var width=\"400\"\n";
  		$html .= "    var height=\"300\"\n";
  		$html .= "    var winX=Math.round(screen.width/2)-(width/2);\n";
  		$html .= "    var winY=Math.round(screen.height/2)-(height/2);\n";
  		$html .= "    var nombre=\"Printer_Mananger\";\n";
  		$html .= "    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",location=yes,resizable=no,status=no,scrollbars=yes\";\n";
  		$html .= "    var url = '".$ruta."';\n";
  		$html .= "    window.open(url, nombre, str).focus();\n";
      $html .= "  }\n";
  		$html .= "</script>\n";
      
      return $html;
    }
    /**
    * Funcion encargada de crear el javascript para permitir
    * visualizar la ventana, desde donde se descarga el archivo pdf
    *
    * @param string $tipo Tipo de modlo
    * @param string $modulo Nombre del modulo
    * @param string $nombre_archivo Nombre de la clase que se va ainstanciar para sacar el reporte
    * @param array $parametros Arreglo de parametros necesarios para el reporte
    *
    * @return string
    */
    function GetJavacriptReporteFPDF($tipo,$modulo,$nombre_archivo,$parametros,$opciones)
    {
    	$VISTA = 'HTML';
      $_ROOT = '../../';
      include $_ROOT.'includes/enviroment.inc.php';
      
      $url = "classes/ReportesCsv/reportes.class.php?inicio=1";
      $datos = array("tipo_modulo" => $tipo,
                     "nombre_modulo" => $modulo,
                     "nombre_archivo" => $nombre_archivo,
                     "parametros" => $parametros,
                     "opciones"=>$opciones);
      
      $ruta = GetBaseURL().$url.UrlRequest($datos);
      
      $this->nameJavaFunction = " GetFDF_".($this->i++)."()";
  		
      $html  = "<script language='javascript'>\n";
  		$html .= "  function ".$this->nameJavaFunction."\n";
      $html .= "  {\n";
  		$html .= "    var width=\"400\"\n";
  		$html .= "    var height=\"300\"\n";
  		$html .= "    var winX=Math.round(screen.width/2)-(width/2);\n";
  		$html .= "    var winY=Math.round(screen.height/2)-(height/2);\n";
  		$html .= "    var nombre=\"Printer_Mananger\";\n";
  		$html .= "    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",location=yes,resizable=no,status=no,scrollbars=yes\";\n";
  		$html .= "    var url = '".$ruta."';\n";
  		$html .= "    window.open(url, nombre, str).focus();\n";
      $html .= "  }\n";
  		$html .= "</script>\n";
      
      return $html;
    }
  }
 ?>