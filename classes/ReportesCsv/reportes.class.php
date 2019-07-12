<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: reportes.class.php,v 1.2 2010/06/03 20:47:12 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: reportes
  * Clase que se encarga de mostar la ventana para hacer la descarga del archivo en 
  * formato csv
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class reportes
  {
    /**
    * @var string $separador
    */
    var $separador = "comas";
    /**
    * Contructor de laclase
    */
    function reportes($datos){ }    
    /**
    * Funcion encargada de manejar la opcion a ejecutar, ya sea mostrar la 
    * ventana o descargar el archivo
    *
    * @param array $datos Arreglo de datos del request
    */
    function imprimir($datos)
    {
      switch($datos['inicio'])
      {
        case '1': 
          $html = $this->PrinterVerReporte($datos); 
          print($html);
        break;        
        case '2': 
          $this->ReporteCsv($datos);
        break;        
        case '3': 
          $this->ReporteXls($datos);
        break;
        case '4': 
          $this->ReporteXml($datos);
        break;        
        case '5': 
          $this->ReporteFPDF($datos);
        break;
      }
    }
    /**
    * Funcion encargada de crear la ventana para hacer la descarga del archivo
    *
    * @param array $datos Arreglo de datos del request
    *
    * @return string
    */
    function PrinterVerReporte($datos)
    {
  		unset($datos['inicio']);
      $url1 = "reportes.class.php?inicio=2";
      $url2 = "reportes.class.php?inicio=3";
      $url3 = "reportes.class.php?inicio=4";
      $url4 = "reportes.class.php?inicio=5";
      
      if(empty($datos['opciones']))
      {
        $datos['opciones']['interface'] = 3; 
        $datos['opciones']['cabecera'] = 1; 
      }
      
      $ruta1 = GetBaseURL().$url1.UrlRequest($datos);
      $ruta2 = GetBaseURL().$url2.UrlRequest($datos);
      $ruta3 = GetBaseURL().$url3.UrlRequest($datos);
      $ruta4 = GetBaseURL().$url4.UrlRequest($datos);
      
      $html  = $this->PrinterGetHead();
  		$html .= "<table width='80%'  bordercolor='#000000' border=5 cellpadding='8' cellspacing='8' align='center'>\n";
  		$html .= "  <tr>\n";
  		$html .= "    <td class='modulo_list_oscuro' align='center' colspan=\"2\">\n";
      $html .= "      <label class='label_mark'>DESCARGAR REPORTE EN FORMATO</label>\n";
      $html .= "    </td>\n";
  		$html .= "  </tr>\n";		
  		$html .= "  <tr>";
      if($datos['opciones']['interface'] == 1 || $datos['opciones']['interface'] == 3)
      {
        $html .= "    <td class='modulo_list_claro' align='center'>\n";
        $html .= "      <form name=\"forma_csv\" action=\"".$ruta1."\" method=\"post\">\n";
        $html .= "        <input type=\"submit\" value=\"CSV\" class=\"input-submit\">\n";
        $html .= "      </form>\n";
        $html .= "    </td>\n";  		
      }
      if($datos['opciones']['interface'] == 2 || $datos['opciones']['interface'] == 3)
      {
        $html .= "    <td class='modulo_list_claro' align='center'>\n";
        $html .= "      <form name=\"forma_csv\" action=\"".$ruta2."\" method=\"post\">\n";
        $html .= "        <input type=\"submit\" value=\"XLS\" class=\"input-submit\">\n";
        $html .= "      </form>\n";
        $html .= "    </td>\n";
  		}      
      if($datos['opciones']['interface'] == 4)
      {
        $html .= "    <td class='modulo_list_claro' align='center'>\n";
        $html .= "      <form name=\"forma_csv\" action=\"".$ruta3."\" method=\"post\">\n";
        $html .= "        <input type=\"submit\" value=\"XML\" class=\"input-submit\">\n";
        $html .= "      </form>\n";
        $html .= "    </td>\n";
  		}      
      if($datos['opciones']['interface'] == 5)
      {
        $html .= "    <td class='modulo_list_claro' align='center'>\n";
        $html .= "      <form name=\"forma_csv\" action=\"".$ruta4."\" method=\"post\" >\n";
        $html .= "        <input type=\"submit\" value=\"PDF\" class=\"input-submit\">\n";
        $html .= "      </form>\n";
        $html .= "    </td>\n";
  		}
      $html .= "  </tr>\n";
  		$html .= "</table><br>\n";
      $html .= $this->PrinterGetHead();
  		
  		return $html;
    }
    /**
    * Funcion encargada de crear la cabecera de la pagina
    *
    * @return string
    */
  	function PrinterGetHead()
  	{
      global $_ROOT;
      global $VISTA;

      if (empty($Theme))  $Theme=GetTheme();
  		
  		$html   = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
  		$html  .= "  <html>\n";
  		$html  .= "    <head>\n";
  		$html  .= "	    <title>Administrador de Reportes</title>\n";
  		$html  .= "      <meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>\n";
      $html  .= "      <link href=\"".$_ROOT."themes/$VISTA/$Theme/style/style.css\" rel=\"stylesheet\" type=\"text/css\">\n";
    	$html  .= "    </head>\n";
  		$html  .= "    <body bgcolor=\"#6DFF5A\">\n";
  		
  		return $html;
  	}
    /**
    * Funcion encargada de crear la parte final de la pagina
    *
    * @return string
    */
    function PrinterGetFooter()
    {
      $html  = "    </body>\n";
      $html .= "  </html>\n";
		
      return $html;
    }
    /**
    * Funcion encargad de crear el archivo en formato csv para su descarga
    *
    * @param array $datos Arreglo de datos del request
    */
    function ReporteCsv($datos)
    {
      if($datos['separador']) $this->separador = $datos['separador'];
      
      global $_ROOT;
      include_once($_ROOT.'classes/AutoCarga/AutoCarga.class.php');
      include_once($_ROOT.'classes/ConexionBD/ConexionBD.class.php');
      include_once($_ROOT.'classes/adodb/toexport.inc.php');
      include_once($_ROOT.'classes/adodb/adodb.inc.php');

      $rpt = AutoCarga::factory($datos['nombre_archivo']."_csv","reports/csv",$datos['tipo_modulo'],$datos['nombre_modulo']);
      $rst = $rpt->GetReporteCsv($datos['parametros']);
      $rst2=$rst;
      //print_r($rst);
       /*
      En caso de que el separador sea diferente a Tabulaciones y Comas
      */
      if($this->separador != "tabs" && $this->separador != "comas")
      {
      
          while(!$rst->EOF)
          {
           $datos_bd[] = $rst->GetRowAssoc($ToUpper = false);
           $rst->MoveNext();
          }
      //obtengo las Columnas
      foreach($datos_bd as $key=>$row)
           {
           }
        $i=0;
       foreach($row as $k=>$r)
           {
           $var_encabezado[$i]=$k;
           $i++;
           }
           //print_r($var_encabezado);
       
       for($j=0;$j<=$i;$j++)
       {
       $datos_archivo .= $var_encabezado[$j];
       if($j==$i)
        $datos_archivo .= PHP_EOL;
        else
          $datos_archivo .= $this->separador;
       }
       //Lleno Mas datos del contenido del archivo.
       for($j=0;$j<($key+1);$j++) //For de los registros totales del la consulta
       {
          for($a=0;$a<($i+1);$a++) //For para las columnas por cada registro de consulta
          {
          $datos_archivo .= $datos_bd[$j][$var_encabezado[$a]];
          if($a==$i)
              $datos_archivo .= PHP_EOL;
              else
              $datos_archivo .= $this->separador;
          }
       }

      }
      
      
      if(!$rst)
      {
        echo "<b> ERROR: ".$rpt->error."</b>";
        exit;
      }
      global $ConfigAplication;
      
      $tmpname = $ConfigAplication['DIR_SIIS']."/tmp/".$datos['nombre_archivo'].".csv";
      if($datos['opciones']['nombre'] && $datos['opciones']['extension'])
        $tmpname = $ConfigAplication['DIR_SIIS']."/tmp/".$datos['opciones']['nombre'].".".$datos['opciones']['extension'];
        
      $fp = fopen($tmpname, "w");
      if ($fp) 
      {
        if($this->separador == "comas")
          rs2csvfile($rst, $fp); # Escribe a un archivo (tambien existe la funcion rs2tabfile)
        else if($this->separador == "tabs")
          rs2tabfile($rst, $fp); # Escribe a un archivo (tambien existe la funcion rs2tabfile)
        else
          $this->rs2sepfile($rst,$fp,$separador);
       
        fclose($fp);
      }
      else
      {
        echo "EL ARCHIVO NO SE PUDO ABRIR PARA LECTURA, NO SE PUEDE CREAR EL ARCHIVO";
        exit;
      }
      
      $info = pathinfo($tmpname);
      $nombre = $info['basename'];
     
     /*
     incluyo la informacion obtenida y armada con separadores diferentes al TAB y Comas en un archivo
     */
      if(!empty($datos_archivo))
      {
                $fp = fopen($tmpname,"w+");
                fwrite($fp, $datos_archivo);
                fclose($fp);
                
                $fp = fopen($tmpname,"r");
                $filedata=fread($fp,filesize($tmpname));
                fclose($fp);
      }
      else
      {
              if($datos['opciones']['cabecera'] == 2)
              {
                $i = 0;
                $datos = "";
                $lines = file($tmpname);
                
                foreach ($lines as $line_num => $line) 
                {
                  if($i != 0) $datos .= $line;
                  
                  $i++;
                }
                //echo $datos;
                $fp = fopen($tmpname,"w+");
                fwrite($fp, $datos);
                fclose($fp);
                
                $fp = fopen($tmpname,"r");
                $filedata=fread($fp,filesize($tmpname));
                fclose($fp);
              }
              else
              {
                $fp = fopen($tmpname,"r");
                $filedata=fread($fp,filesize($tmpname));
                fclose($fp);
              }
      }
      header('Pragma: private');
      header('Cache-control: private, must-revalidate');
      header("Content-type: text/x-ms-iqy");
      header("Content-Disposition: attachment; filename=$nombre");
      
      print $filedata;
    }
    /**
    * Funcion encargad de crear el archivo en formato xls para su descarga
    *
    * @param array $datos Arreglo de datos del request
    */
    function ReporteXls($datos)
    {
      global $_ROOT;
      include_once($_ROOT.'classes/AutoCarga/AutoCarga.class.php');
      include_once($_ROOT.'classes/ConexionBD/ConexionBD.class.php');

      $rpt = AutoCarga::factory($datos['nombre_archivo']."_csv","reports/csv",$datos['tipo_modulo'],$datos['nombre_modulo']);
      $infor = $rpt->GetReporteXls($datos['parametros']);
      
      $template_1 = "templates/template_cabecera.inc";
      $template_2 = "templates/template_pie.inc";

      // Se abre y extrae la cabecera del XML
      if ($gestor = fopen($template_1, "r"))
      {
        $header = fread($gestor, filesize($template_1));
        fclose($gestor);
      }
      
      $rows  = "<Table x:FullColumns=\"1\" x:FullRows=\"1\" ss:DefaultColumnWidth=\"60\"> ";
      foreach($infor as $key => $dtl)
      {
        $rows .= " <Row ss:Index=\"1\">";
        foreach($dtl as $key1 => $dtl1)
        {
          $rows .= " <Cell><Data ss:Type=\"String\">".$key1."</Data></Cell>";
        }
        $rows .= " </Row>";
        break;
      }
      
      $i=2;
      foreach($infor as $key => $dt)
      {
        $rows .= "<Row ss:Index=\"".($i++)."\">\n";
        foreach($dt as $key1 => $dt1)
        {
          $rows .= "<Cell><Data ss:Type=\"String\">".$dt1."</Data></Cell>";
        }
        $rows .= " </Row>";
      }

      // Se abre y extrae el pie del XML
      if ($gestor = fopen($template_2, "r"))
      {
      	$footer = fread($gestor, filesize($template_2));
      	fclose($gestor);
      }

      // Se juntan las partes resultantes
      $content = $header . $rows . $footer;

      // Se envia el archivo al navegador
      header ("Content-type: application/x-msexcel");
      header ("Content-Disposition: attachment; filename=\"archivo_excel.xls\"" ); 
      print $content;
    }
    /**
    * Funcion encargad de crear el archivo en formato xml para su descarga
    *
    * @param array $datos Arreglo de datos del request
    */
    function ReporteXml($datos)
    { 
      global $_ROOT;
      include_once($_ROOT.'classes/AutoCarga/AutoCarga.class.php');
      include_once($_ROOT.'classes/ConexionBD/ConexionBD.class.php');
      include_once($_ROOT.'classes/adodb/toexport.inc.php');
      include_once($_ROOT.'classes/adodb/adodb.inc.php');

      $rpt = AutoCarga::factory($datos['nombre_archivo']."_xml","reports/xml",$datos['tipo_modulo'],$datos['nombre_modulo']);
      $rst = $rpt->GetReporteXml($datos['parametros']);
      
      if(!$rst)
      {
        echo "<b> ERROR: ".$rpt->error."</b>";
        exit;
      }
      global $ConfigAplication;
      
      $tmpname = $ConfigAplication['DIR_SIIS']."/tmp/".$datos['nombre_archivo'].".xml";
        
      //$fp = fopen($tmpname, "w");
      $info = pathinfo($tmpname);
      $nombre = $info['basename'];
     
      //fclose($fp);
      header('Pragma: private');
      header('Cache-control: private, must-revalidate');
      header("Content-type: text/xml");
      header("Content-Disposition: attachment; filename=$nombre");
      $fp = fopen($tmpname,"r");
      $filedata=fread($fp,filesize($tmpname));
      fclose($fp);
      print $filedata;
    }
    /**
    * Funcion encargad de crear el archivo en formato xml para su descarga
    *
    * @param array $datos Arreglo de datos del request
    */
    function ReporteFPDF($datos)
    { 
      global $_ROOT;
      require($_ROOT."classes/fpdf/html_class.php");
      include_once($_ROOT.'classes/AutoCarga/AutoCarga.class.php');
      include_once($_ROOT.'classes/ConexionBD/ConexionBD.class.php');
      
      global $ConfigAplication;
      $tmpname = $ConfigAplication['DIR_SIIS']."/cache/".$datos['nombre_archivo'].".pdf";
      $tmpnam1 = $ConfigAplication['DOMINIO_SIIS']."../../cache/".$datos['nombre_archivo'].".pdf";
      $name = $datos['nombre_archivo'].".pdf";
      $pathImagen = $ConfigAplication['DIR_SIIS']."/images/";

      $rpt = AutoCarga::factory($datos['nombre_archivo']."_fpdf","reports/fpdf",$datos['tipo_modulo'],$datos['nombre_modulo']);
      $rst = $rpt->GetReporteFPDF($datos['parametros'],$tmpname,$pathImagen);
      
      if(!$rst)
      {
        echo "<b> ERROR: ".$rpt->error."</b>";
        exit;
      }
      
      header("Content-type: application/octet-stream");
      header("Content-Disposition: attachment; filename=\"" . $name . "\"\n");
      readfile($tmpname);
  		/*$Salida.="<script>";
      $Salida.="  function VerReportePDF(){\n";
  		$Salida.="    var nombre=\"REPORTE_PDF\";\n";
  		$Salida.="    var str =\"screen.width,screen.height,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
  		$Salida.="    var url ='$tmpnam1';\n";
  		$Salida.="    window.open(url, nombre, str)};\n";
      $Salida .= "  VerReportePDF();";
  		echo $Salida.="</script>";*/
    }
    /**
    * Funcion para adicionar un separador diferente a tabs y comas
    *
    */
    function rs2sepfile(&$rs,$fp,$sepador,$addtitles=true)
    {
      return _adodb_export($rs,$separador,',',false,$addtitles);
    }
  }
  
  $VISTA='HTML';
	$_ROOT='../../';
	include $_ROOT.'includes/enviroment.inc.php';

	$fileName = "includes/vistas/module_theme.php";
	IncludeFile($fileName);

	$bsc = new reportes();
	$bsc->imprimir($_REQUEST);
?>