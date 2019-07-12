<?php
// GetReports.class.php  30/11/2004
// ----------------------------------------------------------------------

// Copyright (C) 2002 Alexander Giraldo
// Emai: alexgiraldo777@yahoo.com

// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: Clase para obtener los links de impresion
// ----------------------------------------------------------------------

class GetReports
{	
	var $secuencia;
	var $nameJavaFunction;
	
	function GetReports()
	{
		$this->secuencia=0;
		return true;
	}

	function GetSecuencia()
	{
		$this->secuencia++;
		$var = str_pad($this->secuencia, 4, "0", STR_PAD_LEFT);;
		return $var;
	}

	function GetJavaFunction()
	{
		return $this->nameJavaFunction;
	}



	/*--------------------------------------------*/

	function GetJavaReport_HistoriaClinica($evolucion,$opciones=array())
	{

		global $_ROOT;

		if (empty($evolucion))
		{
			$this->error = "No Existe la Evolucion";
			$this->mensajeDeError = "No puede ser creado el reporte.";
			return false;
		}

		$url = "reporteHC.php?evolucion=$evolucion";

		foreach ($opciones as $k=>$v) {
			if (is_array($v)) {
				foreach($v as $k2=>$v2) {
				if (is_array($v2)) {
					foreach($v2 as $k3=>$v3) {
					if (is_array($v3)) {
						foreach($v3 as $k4=>$v4) {
						$url .= "&opciones[$k]" . "[$k2][$k3][$k4]=".urlencode($v4);
						}
					}else{
						$url .= "&opciones[$k]" . "[$k2][$k3]=".urlencode($v3);
					}
					}
				}else{
					$url .= "&opciones[$k]" . "[$k2]=".urlencode($v2);
				}
				}
			} else {
				$url .= "&opciones[$k]=".urlencode($v);
			}
		}

		$RUTA = GetBaseURL() . $url;
		$this->nameJavaFunction = "WindowPrinter" . $this->GetSecuencia() . "()";
		$java ="\n\n<script language='javascript'>\n";
		$java.="  function " . $this->nameJavaFunction . "{\n";
		$java.="    var nombre=\"\"\n";
		$java.="    var width=\"400\"\n";
		$java.="    var height=\"300\"\n";
		$java.="    var winX=Math.round(screen.width/2)-(width/2);\n";
		$java.="    var winY=Math.round(screen.height/2)-(height/2);\n";
		$java.="    var nombre=\"Printer_Mananger\";\n";
		$java.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",location=yes,resizable=no,status=no,scrollbars=yes\";\n";
		$java.="    var url ='$RUTA';\n";
		$java.="    window.open(url, nombre, str)};\n";
		$java.="</script>\n\n";

		return $java;
	}

		/*--------------------------------------------*/

	function GetJavaReport_HC($ingreso,$opciones=array())
	{

		global $_ROOT;

		if (empty($ingreso))
		{
			$this->error = "No Existe el Ingreso";
			$this->mensajeDeError = "No puede ser creado el reporte.";
			return false;
		}

		$url = "reporteEpicrisis.php?ingreso=$ingreso";

		foreach ($opciones as $k=>$v) {
			if (is_array($v)) {
				foreach($v as $k2=>$v2) {
				if (is_array($v2)) {
					foreach($v2 as $k3=>$v3) {
					if (is_array($v3)) {
						foreach($v3 as $k4=>$v4) {
						$url .= "&opciones[$k]" . "[$k2][$k3][$k4]=".urlencode($v4);
						}
					}else{
						$url .= "&opciones[$k]" . "[$k2][$k3]=".urlencode($v3);
					}
					}
				}else{
					$url .= "&opciones[$k]" . "[$k2]=".urlencode($v2);
				}
				}
			} else {
				$url .= "&opciones[$k]=".urlencode($v);
			}
		}

		$RUTA = GetBaseURL() . $url;
		$this->nameJavaFunction = "WindowPrinter" . $this->GetSecuencia() . "()";
		$java ="\n\n<script language='javascript'>\n";
		$java.="  function " . $this->nameJavaFunction . "{\n";
		$java.="    var nombre=\"\"\n";
		$java.="    var width=\"400\"\n";
		$java.="    var height=\"300\"\n";
		$java.="    var winX=Math.round(screen.width/2)-(width/2);\n";
		$java.="    var winY=Math.round(screen.height/2)-(height/2);\n";
		$java.="    var nombre=\"Printer_Mananger\";\n";
		$java.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",location=yes,resizable=no,status=no,scrollbars=yes\";\n";
		$java.="    var url ='$RUTA';\n";
		$java.="    window.open(url, nombre, str)};\n";
		$java.="</script>\n\n";

		return $java;
	}
	
     
     //Reporte Epicrisis
     function GetJavaReport_Epicrisis($ingreso,$opciones=array())
	{

		global $_ROOT;

		if (empty($ingreso))
		{
			$this->error = "No Existe el Ingreso";
			$this->mensajeDeError = "No puede ser creado el reporte.";
			return false;
		}

		$url = "ImpresionEpicrisis.php?ingreso=$ingreso";

		foreach ($opciones as $k=>$v) {
			if (is_array($v)) {
				foreach($v as $k2=>$v2) {
				if (is_array($v2)) {
					foreach($v2 as $k3=>$v3) {
					if (is_array($v3)) {
						foreach($v3 as $k4=>$v4) {
						$url .= "&opciones[$k]" . "[$k2][$k3][$k4]=".urlencode($v4);
						}
					}else{
						$url .= "&opciones[$k]" . "[$k2][$k3]=".urlencode($v3);
					}
					}
				}else{
					$url .= "&opciones[$k]" . "[$k2]=".urlencode($v2);
				}
				}
			} else {
				$url .= "&opciones[$k]=".urlencode($v);
			}
		}

		$RUTA = GetBaseURL() . $url;
		$this->nameJavaFunction = "WindowPrinter" . $this->GetSecuencia() . "()";
		$java ="\n\n<script language='javascript'>\n";
		$java.="  function " . $this->nameJavaFunction . "{\n";
		$java.="    var nombre=\"\"\n";
		$java.="    var width=\"400\"\n";
		$java.="    var height=\"300\"\n";
		$java.="    var winX=Math.round(screen.width/2)-(width/2);\n";
		$java.="    var winY=Math.round(screen.height/2)-(height/2);\n";
		$java.="    var nombre=\"Printer_Mananger\";\n";
		$java.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",location=yes,resizable=no,status=no,scrollbars=yes\";\n";
		$java.="    var url ='$RUTA';\n";
		$java.="    window.open(url, nombre, str)};\n";
		$java.="</script>\n\n";

		return $java;
	}


	function GetJavaReport($tipo,$modulo,$reporte,$datos,$opciones)
	{

		global $_ROOT;

		if(empty($reporte)){
		return '';
		}

		if (!empty($tipo)) {
			$urlargs[] = "tipo=$tipo";
		}

		if (!empty($modulo)) {
			$urlargs[] = "modulo=$modulo";
		}

		$urlargs[] = "reporte=$reporte";
		$urlargs = join('&', $urlargs);
		$url = "printer.php?$urlargs";

		if(is_array($datos))
		{
			foreach ($datos as $k=>$v) {
				if (is_array($v)) {
					foreach($v as $k2=>$v2) {
					if (is_array($v2)) {
						foreach($v2 as $k3=>$v3) {
						if (is_array($v3)) {
							foreach($v3 as $k4=>$v4) {
							$url .= "&datos[$k]" . "[$k2][$k3][$k4]=".urlencode($v4);
							}
						}else{
							$url .= "&datos[$k]" . "[$k2][$k3]=".urlencode($v3);
						}
						}
					}else{
						$url .= "&datos[$k]" . "[$k2]=".urlencode($v2);
					}
					}
				} else {
					$url .= "&datos[$k]=".urlencode($v);
				}
			}
		}

		if(is_array($opciones))
		{
			foreach ($opciones as $k=>$v) {
				if (is_array($v)) {
					foreach($v as $k2=>$v2) {
					if (is_array($v2)) {
						foreach($v2 as $k3=>$v3) {
						if (is_array($v3)) {
							foreach($v3 as $k4=>$v4) {
							$url .= "&opciones[$k]" . "[$k2][$k3][$k4]=".urlencode($v4);
							}
						}else{
							$url .= "&opciones[$k]" . "[$k2][$k3]=".urlencode($v3);
						}
						}
					}else{
						$url .= "&opciones[$k]" . "[$k2]=".urlencode($v2);
					}
					}
				} else {
					$url .= "&opciones[$k]=".urlencode($v);
				}
			}
		}

		$RUTA = GetBaseURL() . $url;
		$this->nameJavaFunction = "WindowPrinter" . $this->GetSecuencia() . "()";
		$java ="\n\n<script language='javascript'>\n";
		$java.="  function " . $this->nameJavaFunction . "{\n";
		$java.="    var nombre=\"\"\n";
		$java.="    var width=\"400\"\n";
		$java.="    var height=\"300\"\n";
		$java.="    var winX=Math.round(screen.width/2)-(width/2);\n";
		$java.="    var winY=Math.round(screen.height/2)-(height/2);\n";
		$java.="    var nombre=\"Printer_Mananger\";\n";
		$java.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
		$java.="    var url ='$RUTA';\n";
		$java.="    window.open(url, nombre, str).focus();}\n";
		$java.="</script>\n\n";

		return $java;
	}

}

?>
