<?php
	/**
	* $Id: reporte_pendientes_despacho.report.php,v 1.1 2010/04/09 19:50:04 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('AutoCarga');
	class reporte_pendientes_despacho_report 
	{ 
		//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
		var $datos;
		
		//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
		//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
		var $title       = '';
		var $author      = '';
		var $sizepage    = 'leter';
		var $Orientation = '';
		var $grayScale   = false;
		var $headers     = array();
		var $footers     = array();
		
		//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	  function reporte_pendientes_despacho_report($datos=array())
	  {
			$this->datos=$datos;			
	    return true;
	  }
		/**
    *
    */
		function GetMembrete()
		{
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:10pt\"";
			$titulo .= "<b $est >PRODUCTOS PENDIENTES POR DESPACHAR A LA FARMACIA<br>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$nc = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");
			$cl = AutoCarga::factory('ClaseUtil');
      
    // print_r($this->datos);
           
  			$detl = $nc->ObtenerDespachosPendientes($this->datos['empresa_'],$this->datos['farmacia'],$this->datos['buscador']);
        
     //   print_r($detl);
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";

      if(!empty($detl))
      {
		$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td width=\"42.5%\" >FARMACIA</td>\n";
        $html .= "			<td width=\"42.5%\" >PRODUCTO</td>\n";
        $html .= "			<td width=\"5%\" >#PED</td>\n";
		$html .= "			<td width=\"5%\" class=\"label\">CANT. SOL.</td>\n";
		$html .= "			<td width=\"5%\" class=\"label\">CANT. PEND.</td>\n";

		$html .= "		</tr>\n";

		foreach($detl as $k1 => $dtl)
		{
		$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
		$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";

		$html .= "		<tr ".$clase." onmouseout=mOut(this,\"".$bck."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
		$html .= "			<td >".$dtl['razon_social']."</td>\n";
		$html .= "			<td >".$dtl['producto']."</td>\n";
		$html .= "			<td >".$dtl['solicitud_prod_a_bod_ppal_id']."</td>\n";
		$html .= "			<td >".FormatoValor($dtl['cantidad_solicitada'])."</td>\n";
        $html .= "			<td >".FormatoValor($dtl['cantidad_pendiente'])."</td>\n";
		$html .= "		</tr>\n";
		}
		$html .= "	</table><br><br><br>\n";
		}
		$usuario = $nc->ObtenerInformacionUsuario(UserGetUID());
		$html .= "	<br><table border='0' width=\"100%\">\n";
		$html .= "		<tr>\n";
		$html .= "			<td align=\"justify\" width=\"50%\">\n";
		$html .= "				<font size='1' face='arial'>\n";
		$html .= "					Imprimió:&nbsp;".$usuario['nombre']."\n";
		$html .= "				</font>\n";
		$html .= "			</td>\n";
		$html .= "			<td align=\"right\" width=\"50%\">\n";
		$html .= "				<font size='1' face='arial'>\n";
		$html .= "					Fecha Impresión :&nbsp;&nbsp;".date("d/m/Y - h:i a")."\n";
		$html .= "				</font>\n";
		$html .= "			</td>\n";
		$html .= "		</tr>\n";
		$html .= "	</table>\n";
		return $html;
		}
	}
?>