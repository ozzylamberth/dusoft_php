<?php
	/**
	* $Id: reporte_pendientes_despacho.report.php,v 1.1 2010/04/09 19:50:04 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('AutoCarga');
	class reporte_selectivo_report 
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
	  function reporte_selectivo_report($datos=array())
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
			$titulo .= "<b $est >SELECTIVO - PRODUCTOS<br>";
			$titulo .= "<b $est >".$this->datos['info_empresa']['nombre_empresa']."<br>";
			$titulo .= "<b $est >".$this->datos['info_empresa']['nombre_centro_utilidad']."-".$this->datos['info_empresa']['nombre_bodega']."<br>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$sql = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");
			$cl = AutoCarga::factory('ClaseUtil');
      
    if($this->datos['selectivo']=='1' && $this->datos['forma']['cantidad_conteo']>0)
	{
	$datos=$sql->ObtenerListadoSelectivo($this->datos['info_empresa'],$this->datos['forma']);
	}
	/*Genero los Productos Segun los parametros que selecciona el Producto para incluirlos en un IN()*/
	foreach($datos as $key=>$valor)
	{
	$producto .= "'".$valor['codigo_producto']."',";
	}
	$producto .= " '' ";
	
	$lotes= $sql->ObtenerLotesSelectivo($this->datos['info_empresa'],$producto);
	$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
	
	/*print_r($lotes);*/
	if(!empty($datos))
	{
	$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
	$html .= "		<tr class=\"formulacion_table_list\" >\n";
	$html .= "			<td width=\"20%\" class=\"label\" align=\"center\">COD PROD</td>\n";
	$html .= "			<td width=\"70%\" class=\"label\" align=\"center\">PRODUCTO</td>\n";
	$html .= "			<td width=\"10%\" class=\"label\" align=\"center\"></td>\n";
	$html .= "		</tr>\n";
	foreach($datos as $k => $dtl)
		{
		$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
		$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";

		$html .= "		<tr ".$clase." onmouseout=mOut(this,\"".$bck."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
		$html .= "			<td class=\"label\" align=\"center\">".$dtl['codigo_producto']."</td>\n";
		$html .= "			<td class=\"label\" align=\"left\" >".$dtl['producto']."</td>\n";
		$html .= "			<td class=\"label\" align=\"center\" >TOTAL: ".FormatoValor($dtl['existencia'])."</td>\n";
		$html .= "		</tr>\n";
		$html .= "		<tr ".$clase." onmouseout=mOut(this,\"".$bck."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" border=\"2\">\n";
		$html .= "			<td colspan=\"3\">";
		$html .= "				<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">";
		$html .= "					<tr>";
		$html .= "							<td width=\"60%\"  class=\"label\" align=\"center\"></td>\n";
		$html .= "							<td width=\"10%\"  class=\"label\" align=\"center\">LOTE</td>\n";
		$html .= "							<td width=\"10%\" class=\"label\" align=\"center\">F/VTO</td>\n";
		$html .= "							<td width=\"10%\" class=\"label\" align=\"center\">EXIST</td>\n";
		$html .= "							<td width=\"10%\" class=\"label\" align=\"center\">CONTEO</td>\n";
		$html .= "					</tr>";
		for($i=0;$i<count($lotes[$dtl['codigo_producto']]);$i++)
			{
		$html .= "					<tr>";
		$html .= "							<td ></td>\n";
		$html .= "							<td >".$lotes[$dtl['codigo_producto']][$i]['lote']."</td>\n";
		$html .= "							<td >".$lotes[$dtl['codigo_producto']][$i]['fecha_vencimiento']."</td>\n";
		$html .= "							<td align=\"center\" class=\"label\">".FormatoValor($lotes[$dtl['codigo_producto']][$i]['existencia_actual'])."</td>\n";
		$html .= "							<td ></td>\n";
		$html .= "					<tr>";
			}
		for($i=0;$i<4;$i++)
			{
		$html .= "					<tr>";
		$html .= "							<td >&nbsp</td>\n";
		$html .= "							<td >&nbsp</td>\n";
		$html .= "							<td >&nbsp</td>\n";
		$html .= "							<td >&nbsp</td>\n";
		$html .= "							<td >&nbsp</td>\n";
		$html .= "					<tr>";
			}
		$html .= "				</table>";
		$html .= "			</td>";
		$html .= "		</tr>\n";
		$html .= "			<td colspan=\"3\">";
		$html .= "			</td>";
		$html .= "		<tr>\n";
		$html .= "		</tr>\n";
		
		}
	$html .= "	</table><br>\n";
	}
	else
		{
	$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
	$html .= "		<tr class=\"formulacion_table_list\" >\n";
	$html .= "			<td class=\"label_error\" align=\"center\">";
	$html .= "				NO HAY INFORMACION, PARA MOSTRAR. REVISAR PARAMETROS PARA EL REPORTE";
	$html .= "			</td>";
	$html .= "		</tr>";
	$html .= "	</table>";
		}
	$usuario = $sql->ObtenerInformacionUsuario(UserGetUID());
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