<?
// printer.php  29/11/2004
// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2004 Ipsoft s.a
// ----------------------------------------------------------------------
// Autor: Alexander Giraldo Salas
// email; alexgiraldo@ipsoft-sa.com
// Proposito del Archivo: Impresion de Reportes
// ----------------------------------------------------------------------

	$VISTA='HTML';
	$_ROOT = '';

	include $_ROOT . 'includes/enviroment.inc.php';

	
	if(!empty($_REQUEST['printer']))
	{ 
		if(empty($_SESSION['printer_cmd']['printer'][$_REQUEST['printer']]))
		{
	 		PrinterReturnMensaje("ERROR AL INTENTAR IMPRIMIR UN REPORTE","No llego el comando de impresion.");
		}
		
		exec(EscapeShellCmd($_SESSION['printer_cmd']['printer'][$_REQUEST['printer']]['cmd']),$exec_resultado,$exec_codigo);
		
		$report_html = $_SESSION['printer_cmd']['report_html'];
		$report_pdf = $_SESSION['printer_cmd']['report_pdf'];
		
		unset($_SESSION['printer_cmd']);
		
		
		print(PrinterGetHead('','#00B7FF'));//Color Ventana
		print(PrinterVerReporte($report_html,$report_pdf));
		PrinterImprimirReporte($report_html,$report_pdf);
		print(PrinterGetFooter());
		exit;	
	}
	

	$file="classes/reports/html/html.class.php";

	if (!IncludeFile($file)) {
  	PrinterReturnMensaje("ERROR AL INTENTAR INSTANCIAR UN REPORTE","No existe el archivo de clase $file");
	}

	$clase = "html_reports_class";
	if(!class_exists($clase)){
		PrinterReturnMensaje("ERROR AL INTENTAR INSTANCIAR UN REPORTE","No existe la clase $clase");
	}

	$reporte = new $clase;
	if(!is_object($reporte)){
		PrinterReturnMensaje("ERROR AL INTENTAR INSTANCIAR UN REPORTE","La instancia de la clase no retorno un objecto.");
	}

	$report_html = $reporte->GetReportHTML_Epicrisis($_REQUEST['ingreso'],$_REQUEST['opciones']);
	if(!$report_html){
		PrinterReturnMensaje("ERROR AL INTENTAR INSTANCIAR UN REPORTE",$reporte->GetError()." : ".$reporte->MensajeDeError());
	}

	$report_pdf = $reporte->GetReportPDF();

	if(!$report_pdf){
		PrinterReturnMensaje("ERROR AL INTENTAR INSTANCIAR UN REPORTE",$reporte->GetError()." : ".$reporte->MensajeDeError());
	}
	
     list($dbconn) = GetDBconn();

	print(PrinterGetHead('','#00B7FF'));//Color Ventana
	print(PrinterVerReporte($report_html,$report_pdf));
	PrinterImprimirReporte($report_html,$report_pdf);
	print(PrinterGetFooter());
	exit;


	function PrinterVerReporte($report_html,$report_pdf)
	{
		$Salida ="\n\n";
		$Salida.="<script language='javascript'>\n";
		$Salida.="  function VerReporteHTML(){\n";
		$Salida.="    var nombre=\"REPORTE_HTML\";\n";
		$Salida.="    var str =\"screen.width,screen.height,resizable=no,location=yes, status=no,scrollbars=yes,toolbar=1\";\n";
		$Salida.="    var url ='$report_html';\n";
		$Salida.="    window.open(url, nombre, str)};\n";
		$Salida.="\n\n";
		$Salida.="  function VerReportePDF(){\n";
		$Salida.="    var nombre=\"REPORTE_PDF\";\n";
		$Salida.="    var str =\"screen.width,screen.height,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
		$Salida.="    var url ='$report_pdf';\n";
		$Salida.="    window.open(url, nombre, str)};\n";
		$Salida.="</script>";
		$Salida.="\n\n";

		$Salida.="<TABLE width='80%'  bordercolor='#000000' border=5 cellpadding='8' cellspacing='8' align='center'>\n";
		$Salida.="<TR>";
		$Salida.="<TD class='modulo_list_oscuro' colspan='2' align='center'>VER REPORTE</TD>\n";
		$Salida.="</TR>\n";
		$Salida.="<TR>";
		$Salida.="<TD class='modulo_list_claro' align='center'><input type=\"button\" value=\"HTML\" onclick=\"javascript:VerReporteHTML()\"></TD>\n";
		$Salida.="<TD class='modulo_list_claro' align='center'><input type=\"button\" value=\"PDF\" onclick=\"javascript:VerReportePDF()\"></TD>\n";
		$Salida.="</TR>\n";
		$Salida.="</TABLE><BR>\n";

		return $Salida;
	}
	
	
     function PrinterImprimirReporte($report_html,$report_pdf,$sw='')
	{
		if($sw==1)
		{
			$_SESSION['printer_cmd']['report_pdf']=$report_pdf;
		}
		else
		{
				unset($_SESSION['printer_cmd']);
				$_SESSION['printer_cmd']['report_html']=$report_html;
				$_SESSION['printer_cmd']['report_pdf']=$report_pdf;
		}		
		global $ADODB_FETCH_MODE;
		
		list($dbconn) = GetDBconn();
		
		$sql="SELECT b.* FROM
				system_printers_host as a, system_printers as b
				WHERE a.impresora=b.impresora
				AND b.sw_pos=0
				AND a.ip='".GetIPAddress()."'";
				
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
		if ($dbconn->ErrorNo() != 0) {
			PrinterReturnMensaje("Error en el SQL",$dbconn->ErrorMsg());
		}
		
		if($resultado->EOF){
			return true;
		}
		
		$Salida ="<br><TABLE width='80%' bordercolor='#000000' border=5 cellpadding='8' cellspacing='8' align='center'>\n";
		$Salida.="<TR>";
		$Salida.="<TD class='modulo_list_oscuro' align='center'><label class='label_mark'>IMPRIMIR REPORTE EN :</label></TD>\n";
		$Salida.="</TR>\n";	

		$RUTA = GetBaseURL() . 'reporteEpicrisis.php?';

		while($printer = $resultado->FetchRow())
		{
			$ruta_printer .= $RUTA . "printer=" . $printer['impresora']."&ruta=".$_REQUEST['ruta'];
			
			if(empty($printer['descripcion']))
			{
				$printer['descripcion']=$printer['impresora'];
			}
			//lo nuevo ojo
			$cmd = $printer['comando'] ."  ". GetVarConfigAplication('DirPrinter')." $report_pdf";
			$cmd = str_replace ("%PRINTER",$printer['impresora'],$cmd);
			$_SESSION['printer_cmd']['printer'][$printer['impresora']]['cmd']=$cmd;
			
			$Salida.="<TR>";
			$Salida.="<TD class='modulo_list_claro' align='center'><A HREF='$ruta_printer'><b>".strtoupper($printer[descripcion])."</b></A>&nbsp;<img src=\"". GetThemePath() ."/images/imprimir.png\" width='15' heigth='15'  border='0'></TD>\n";
			$Salida.="</TR>\n";		
		}
		
		$resultado->Close();
		
		$Salida.="</TABLE><BR>\n";

		print($Salida);
	}	
	
	
	function PrinterGetHead($titulo,$bgcolor,$msg)
	{
		if(empty($bgcolor)){
			$bgcolor= " bgcolor=\"#6DFF5A\"";
		}else{
			$bgcolor= " bgcolor=\"$bgcolor\"";
		}
		
		if(empty($titulo)){
			$titulo= "Administrador de Impresion";
		}
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

		
		
		$Salida  = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
		$Salida  = "<HTML>\n";
		$Salida .= "<HEAD>\n";
		$Salida .= "	<TITLE>$titulo</TITLE>\n";
		//$Salida .= "    <link href=".GetBaseURL() ."classes/reports/html/style/style.css  rel=stylesheet type=text/css>\n";
		$Salida .= "  <meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>\n";
          $Salida .= "  $ThemeStyle";
          $Salida .= "  $Scripts\n";
          $Salida .= "</HEAD>\n";
		$Salida .= "<BODY$bgcolor>\n";
		
		if(!empty($msg))
		{
			$Salida .= "<DIV align=\"center\" class=\"alerta\" ><label class='label_mark'>$msg</label></DIV><BR>\n";
		}
		
		print($Salida);
	}
	
	
	
	
	

	function PrinterGetFooter()
	{
		$Salida  = "	</BODY>\n";
		$Salida .= "</HTML>\n";
		return $Salida;
	}

	function PrinterReturnMensaje($titulo,$mensaje)
	{
		$Salida  = PrinterGetHead('','#00B7FF');//Color Ventana
		$Salida .= "		<br><br>\n";
		$Salida .= "		<table border='0' cellpadding='4' cellspacing='2' width='80%' align='center'>\n";
		$Salida .= "			<tr>\n";
		$Salida .= "			<td align='center'><FONT color='red' size='+1'>$titulo</FONT></td>\n";
		$Salida .= "			</tr>\n";
		$Salida .= "			<tr>\n";
		$Salida .= "			<td align='center'>$mensaje</td>\n";
		$Salida .= "			</tr>\n";
		$Salida .= "			<tr>\n";
		$Salida .= "			<td align='center'>\n";
		$Salida .= "			<FORM>\n";
		$Salida .= "     			<INPUT type='button' value='CERRAR' onclick=\"window.close()\">\n";
		$Salida .= "   			</FORM>\n";
		$Salida .= "			</td>\n";	
		$Salida .= "			</tr>\n";	
		$Salida .= "		</table>\n";	
		$Salida .= PrinterGetFooter();
		print($Salida);
		exit;
	}
?>


