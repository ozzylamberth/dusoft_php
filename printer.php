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
// print_r($_SESSION['printer_cmd']);
// print_r($_REQUEST['printer']);
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
		
		
		if(strlen($_REQUEST['ruta'])<1)
		{
			PrinterGetHead('','#DDDDDD','El Reporte fue enviado a la cola de impresi�n : ' . $_REQUEST['printer']);
			PrinterVerReporte($report_html,$report_pdf);
			PrinterImprimirReporte($report_html,$report_pdf);
			PrinterGetFooter();
		}
		else
		{
			//si entra aca es fpdf
			PrinterGetHead('','#DDDDDD','El Reporte fue enviado a la cola de impresi�n : ' . $_REQUEST['printer']);
			PrinterVerReporte('',$_REQUEST['ruta'],1);
			PrinterImprimirReporte('',$_REQUEST['ruta'],1);
			PrinterGetFooter();
		}
		exit;	
	}

	
	//colocaremos una variable de mas para detrminar que la ventana la llamamos de fpdf
//si existe $_REQUEST['ruta'] es por q la llamamos de fpdf.
	if(strlen($_REQUEST['ruta'])<1)
	{
				if(empty($_REQUEST['reporte'])){
					PrinterReturnMensaje("ERROR AL INTENTAR INSTANCIAR UN REPORTE","No se paso como argumento el nombre del reporte.");
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
			
				$report_html = $reporte->GetReportHTML($_REQUEST['tipo'],$_REQUEST['modulo'],$_REQUEST['reporte'],$_REQUEST['datos'],$_REQUEST['opciones']);
				
				if(!$report_html){
					PrinterReturnMensaje("ERROR AL INTENTAR INSTANCIAR UN REPORTE",$reporte->GetError()." : ".$reporte->MensajeDeError());      		
				}
				
			/*	$report_pdf = $reporte->GetReportPDF();
				
				if(!$report_pdf){
					PrinterReturnMensaje("ERROR AL INTENTAR INSTANCIAR UN REPORTE",$reporte->GetError()." : ".$reporte->MensajeDeError());      		
				}		*/
			
			
				PrinterGetHead('','#DDDDDD','');
				PrinterVerReporte($report_html,$report_pdf);
				PrinterImprimirReporte($report_html,$report_pdf);
				PrinterGetFooter();
				exit;
	}
	else
	{
		//si entra aca es fpdf
		PrinterGetHead('','#DDDDDD','');
		PrinterVerReporte('',$_REQUEST['ruta'],1);
		PrinterImprimirReporte('',$_REQUEST['ruta'],1);
		PrinterGetFooter();
		exit;
	}

	//esta funcion  la llamamos de html_to_pdf y fpdf
	//la var $sw si esta en 1 es por q es llamada desde fpdf
	function PrinterVerReporte($report_html,$report_pdf,$sw='')
	{
           	$Salida ="\n\n";
		$Salida.="<script language='javascript'>\n";
		$Salida.="  function VerReporteHTML(){\n";
		$Salida.="    var nombre=\"REPORTE_HTML\";\n";
		$Salida.="    var str =\"screen.width,screen.height,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes\";\n";
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
		
		
		$Salida.="<br><TABLE width='80%'  bordercolor='#000000' border=5 cellpadding='8' cellspacing='8' align='center'>\n";
		$Salida.="<TR>";
		if($sw !=1)
		{	$cols='2';}elseif($sw==1){$cols='1';}
		$Salida.="<TD class='modulo_list_oscuro' colspan='$cols' align='center'><label class='label_mark'>VISUALIZAR REPORTE EN FORMATO</label></TD>\n";
		$Salida.="</TR>\n";		
		$Salida.="<TR>";
		if($sw !=1)
		{
			$Salida.="<TD class='modulo_list_claro' align='center'><input type=\"button\" value=\"HTML\" onclick=\"javascript:VerReporteHTML()\"></TD>\n";
		}
		$Salida.="<TD class='modulo_list_claro' align='center'><input type=\"button\" value=\"PDF\" onclick=\"javascript:VerReportePDF()\"></TD>\n";
		$Salida.="</TR>\n";
		$Salida.="</TABLE><BR>\n";
		
		print($Salida);
	}		
// 		$Salida.="<TD align='center'><A HREF=\"$report_html\">HTML</A></TD>\n";
// 		$Salida.="<TD align='center'><A HREF=\"$report_pdf\">PDF</A></TD>\n";		
	
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
				AND b.sw_pos='0'
				AND a.ip='".GetIPAddress()."'";
				
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
		if ($dbconn->ErrorNo() != 0) {
			PrinterReturnMensaje("Error en el SQL ",$dbconn->ErrorMsg()." ".$sql);
		}
		
		if($resultado->EOF){
			return true;
		}
		
		$Salida ="<br><TABLE width='80%' bordercolor='#000000' border=5 cellpadding='8' cellspacing='8' align='center'>\n";
		$Salida.="<TR>";
		$Salida.="<TD class='modulo_list_oscuro' align='center'><label class='label_mark'>IMPRIMIR REPORTE EN :</label></TD>\n";
		$Salida.="</TR>\n";	

		$RUTA = GetBaseURL() . 'printer.php?';

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
		$Salida  = "</BODY>\n";
		$Salida .= "</HTML>\n";
		
		print($Salida);
	}

	
	function PrinterReturnMensaje($titulo,$mensaje)
	{
		$Salida  = PrinterGetHead('','#FF9E9E');
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


