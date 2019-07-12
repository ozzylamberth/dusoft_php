<?

/**
 * $Id: GeneradorHtmlPdf.inc.php,v 1.2 2005/06/07 18:40:57 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Generador de paginas html y pdf para reportes.
 */

 function GetDatos_A_Generar_Html_a_Pdf($html,$file='')
 {

 		IncludeFile("classes/HTML_ToPDF-3.2/HTML_ToPDF.php");
 		// creacion de un archivo temporal PDF
		//echo $html;exit;
		if($file=='')
		{
			$linkToPDFFull = $linkToPDF = tempnam("cache/", 'PDF-');
		}
		else
		{
			$linkToPDFFull = $linkToPDF = "$file";
		}
		unlink($linkToPDFFull);
		//extension
 		$linkToPDFFull .= '.pdf';
		$linkToPDF .= '.pdf';
		$linkToPDF = basename($linkToPDF);
 		$htmlFile = str_replace('.pdf', '.htm', $linkToPDFFull);
		$defaultDomain = '192.168.1.2/SIIS';


		// buffer de la pagina web creada para poderla sobreescribir.
 		ob_start();

		$fp = fopen($htmlFile, 'w');
		fwrite($fp, $html);
		fclose($fp);
 		ob_end_flush();

		$pdf =& new HTML_ToPDF($htmlFile, $defaultDomain);
		//$pdf->setHeader('logocliente.png', '$D');
		//$pdf->setHeader('color', 'blue');
		$pdf->setFooter('left', 'SIIS');
		$result = $pdf->convert();

		// En  vez de matar la aplicacion generamos el error en el pdf
		if (PEAR::isError($result) OR empty($html))
		{
		  //$mensaje=$result->getMessage();
			//die($result->getMessage());
			$html=Menssage_Error($mensaje);
			ob_start();
    	$fp = fopen($htmlFile, 'w');
		  fwrite($fp, $html);
		  fclose($fp);
 		  ob_end_flush();

			$pdf =& new HTML_ToPDF($htmlFile, $defaultDomain);
			$pdf->setFooter('left', 'SIIS');
			$result = $pdf->convert();
		}

			// move the generated PDF to the web accessible file
   		copy($result, $linkToPDFFull);
			unlink($result);
			unlink($htmlFile);

			return $linkToPDF;
 }


 /*
 * funcion que trae la cabecera
 * que nosotros colocaremos en el reporte
 *
 */
 function Get_Header($CABECERA,$align,$logo)
 {

 		$logo=GetThemePath() ."/images/logocliente.png";
		$HEADER ="<TABLE width='100%' border=0 bordercolor='white'>";
		$HEADER.="  <TR>";

		if(file_exists($logo))
		//if($logo==='1')
		{
			if(strtolower($align)=='left')
			{
				$HEADER.="<TD WIDTH='70'>	<img src='$logo' align='left' border=0></TD>";
				$HEADER.="<TD WIDTH='70'>$CABECERA";
				$HEADER.="</TD>";
			}
			elseif(strtolower($align)=='rigth')
			{
				$HEADER.="<TD WIDTH='70'>$CABECERA";
				$HEADER.="</TD>";
				$HEADER.="<TD WIDTH='70'>	<img src='$logo' align='left' border=0></TD>";
			}
			elseif(strtolower($align)=='center')
			{
				$HEADER.="<TD colspan='1' WIDTH='50' align='CENTER'><img src='$logo'  align='left' border=0></TD>";
				$HEADER.="  </TR>";
				$HEADER.="  <TR>";
				$HEADER.="<TD WIDTH='50'>$CABECERA";
				$HEADER.="</TD>";
			}
			else
			{
				$HEADER.="<TD WIDTH='70'>	<img src='$logo' align='left' border=0></TD>";
				$HEADER.="<TD WIDTH='70'>$CABECERA";
				$HEADER.="</TD>";
			}
		}
		else
		{
			if(strtolower($align)=='left')
			{
				$HEADER.="<TD WIDTH='70'>	<img src='$logo' align='left' border=0></TD>";
				$HEADER.="<TD WIDTH='70'>$CABECERA";
				$HEADER.="</TD>";
			}
			elseif(strtolower($align)=='rigth')
			{
				$HEADER.="<TD WIDTH='70'>$CABECERA";
				$HEADER.="</TD>";
				$HEADER.="<TD WIDTH='70'>	<img src='$logo' align='left' border=0></TD>";
			}
			elseif(strtolower($align)=='center')
			{
				$HEADER.="<TD colspan='1' WIDTH='70' align='CENTER'><img src='$logo'  align='left' border=0></TD>";
				$HEADER.="  </TR>";
				$HEADER.="  <TR>";
				$HEADER.="<TD WIDTH='70'>$CABECERA";
				$HEADER.="</TD>";
			}
			else
			{
				$HEADER.="<TD WIDTH='70'>	<img src='".GetThemePath()."/images/logocliente.png' width='10' height='10' align='left' border=0></TD>";
				$HEADER.="<TD WIDTH='70'>$CABECERA";
				$HEADER.="</TD>";
			}
		}
		$HEADER.="  </TR>";
		$HEADER.="</TABLE><br>";
		return $HEADER;
 }

 /*
 * funcion que inicia los tags de html
 * para hacer la pagina web
 */
 function Open_Tags_Html($title)
 {
    if(empty($title))
		{
			$title="&nbsp;";
		}

		$HTML="<HTML>";
		$HTML.="<HEAD>";
		$HTML.="<TITLE>$title</TITLE>";
		$HTML .="</HEAD>";
		$HTML.="<BODY>";

		return $HTML;
 }


 /*
 * funcion que cierra los tags de html
 * para terminar la pagina web
 */
 function Close_Tags_Html()
 {
		$HTML="</BODY>";
		$HTML.="</html>";
		return $HTML;
 }


 function Menssage_Error($mensaje)
 {

 		$MSGBOX=Open_Tags_Html('Error de creacion!');
 		if(empty($mensaje))
		{
			$mensaje="OCURRIO UN ERROR EN EL PLUGIN O EN LA CREACION DE ARCHIVOS PDF A FALTA DE PERMISOS!";
		}
 		$MSGBOX.="<TABLE width='100%' border=4>";
   	$MSGBOX.="  <TR>";
		$MSGBOX.="<TD WIDTH='70'><FONT SIZE='2' color='RED'>ALERTA DEL SISTEMA</FONT></TD>";
		$MSGBOX.="  </TR>";
		$MSGBOX.="  <TR>";
		$MSGBOX.="<TD WIDTH='70'><FONT SIZE='2' color='BLUE'>$mensaje";
		$MSGBOX.="</FONT></TD>";
		$MSGBOX.="  </TR>";
		$MSGBOX.="</TABLE><br>";
		$MSGBOX.=Close_Tags_Html();

		//echo $MSGBOX;
		return $MSGBOX;
 }

?>
