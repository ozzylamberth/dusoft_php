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
	
	$report_pdf = $reporte->GetReportPDF();
	
	if(!$report_pdf){
		PrinterReturnMensaje("ERROR AL INTENTAR INSTANCIAR UN REPORTE",$reporte->GetError()." : ".$reporte->MensajeDeError());      		
	}		
	list($dbconn) = GetDBconn();
	
	$sql="SELECT a.impresora, a.sw_predeterminada FROM
			system_printers_host as a, system_printers as b
			WHERE a.impresora=b.impresora
			AND b.sw_pos=0
			AND a.ip='".GetIPAddress()."'";
	
	$resultado = $dbconn->Execute($sql);
	if ($dbconn->ErrorNo() != 0) {
		PrinterReturnMensaje("Error en el SQL",$dbconn->ErrorMsg());
	}
	
	$impresoras=$resultado->GetRows();
	$resultado->Close();	
	

	//print(PrinterGetHead('','#00B7FF'));
	print(PrinterGetHead('','#FFFFFF'));
	print(PrinterVerReporte($report_html,$report_pdf));
	print(PrinterGetFooter());
	exit;

	
	function PrinterVerReporte($report_html,$report_pdf)
	{
		$Salida ="<TABLE width='80%' border=5 cellpadding='4' cellspacing='4' align='center'>\n";
		$Salida.="<TR>";
		$Salida.="<TD colspan='2' align='center'><b>VER REPORTE</b></TD>\n";
		$Salida.="</TR>\n";
		$Salida.="<TR>";
		$Salida.="<TD align='center'><A HREF=\"$report_html\">HTML</A></TD>\n";
		$Salida.="<TD align='center'><A HREF=\"$report_pdf\">PDF</A></TD>\n";
		$Salida.="</TR>\n";
		$Salida.="</TABLE><BR>\n";
		
		return $Salida;
	}
	
	function PrinterGetHead($titulo,$bgcolor)
	{
		if(empty($bgcolor)){
			$bgcolor= " bgcolor=\"#6DFF5A\"";
		}else{
			$bgcolor= " bgcolor=\"$bgcolor\"";
		}
		
		if(empty($titulo)){
			$titulo= "Administrador de Impresi&#243;n";
		}
		
		$Salida  = "<HTML>\n";
		$Salida .= "	<HEAD>\n";
		$Salida .= "		<TITLE>$titulo</TITLE>\n";
		$Salida .= "	</HEAD>\n";
		$Salida .= "	<BODY$bgcolor>\n";
		return $Salida;
	}
	
	function PrinterGetFooter()
	{
		$Salida  = "	</BODY>\n";
		$Salida .= "</HTML>\n";
		return $Salida;
	}

	function PrinterReturnMensaje($titulo,$mensaje)
	{
		//$Salida  = PrinterGetHead('','#FF9E9E');
		$Salida  = PrinterGetHead('','#FFFFFF');
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


