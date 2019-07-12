<?php
	/**
	* $Id: reporte_pendientes_despacho.report.php,v 1.1 2010/04/09 19:50:04 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('AutoCarga');
	class reporte_despachos_ingresos_report 
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
	  function reporte_despachos_ingresos_report($datos=array())
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
			$titulo .= "<b $est >DOCUMENTOS DESPACHOS - INGRESOS: FARMACIAS<br>";
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
	/*print_r($this->datos);*/
	$datos = $sql ->ObtenerDespachosIngresos($this->datos['info_empresa'],$this->datos['buscador'],'1','0');
      
	$html .= "				<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\" >";
	$html .= "					<tr class=\"formulacion_table_list\">";
	$html .= "						<td width=\"5%\" class=\"label\" align=\"center\">";
	$html .= "							#PED";
	$html .= "						</td>";
	$html .= "						<td  width=\"45%\" class=\"label\" align=\"center\">";
	$html .= "							FARMACIA";
	$html .= "						</td>";
	$html .= "						<td  width=\"50%\">";
	$html .= "							<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">";
	$html .= "								<tr class=\"modulo_table_list_title\">";
	$html .= "									<td  width=\"30%\" rowspan=\"2\" class=\"label\" align=\"center\">";
	$html .= "										DESPACHO";
	$html .= "									</td>";
	$html .= "									<td  width=\"70%\" class=\"label\" align=\"center\">";
	$html .= "										INGRESO";
	$html .= "									</td>";
	$html .= "								</tr>";
	$html .= "								<tr class=\"modulo_table_list_title\">";
	/*$html .= "									<td class=\"formulacion_table_list\">";
	$html .= "									</td>";*/
	$html .= "									<td>";
	$html .= "										<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\>";
	$html .= "											<tr class=\"formulacion_table_list\">";
	$html .= "												<td width=\"33.33%\" class=\"label\" align=\"center\">";
	$html .= "														DOC.";
	$html .= "												</td>";
	$html .= "												<td width=\"33.33%\" class=\"label\" align=\"center\">";
	$html .= "														FECHA";
	$html .= "												</td>";
	$html .= "												<td width=\"33.33%\" class=\"label\" align=\"center\">";
	$html .= "														USUARIO";
	$html .= "												</td>";
	$html .= "											</tr>";
	$html .= "										</table>";
	$html .= "									</td>";
	$html .= "								</tr>";
	$html .= "							</table>";
	$html .= "						</td>";
	$html .= "					</tr>";
	foreach($datos as $key => $dtl)
	{    
	$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
	$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
	$html .= "  				<tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
	$html .= "						<td>";
	$html .= "								".$key;
	$html .= "						</td>";
	foreach($dtl as $k => $d)
	{
	$html .= "						<td>";
	$html .= "								".$k;
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\" border=\"1\">";
	foreach($d as $k1 => $d1)
	{
	$html .= "								<tr>";
	$html .= "									<td width=\"30%\">";
	$html .= "										".$k1;
	$html .= "									</td>";
	$html .= "									<td  width=\"70%\">";
	$html .= "										<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">";
	foreach($d1 as $k2 => $d2)
	{
	$html .= "											<tr>";
	$html .= "												<td>";
	$html .= "													<table table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">";
	$html .= "														<tr>";
	$html .= "															<td width=\"33.33%\">";
	$html .= "																".$d2['documento_ingreso'];
	$html .= "															</td>";
	$html .= "															<td width=\"33.33%\">";
	$html .= "																".$d2['fecha_ingreso'];
	$html .= "															</td>";
	$html .= "															<td width=\"33.33%\">";
	$html .= "																	".$d2['usuario_id']."-".$d2['nombre'];
	$html .= "															</td>";
	$html .= "														</tr>";
	$html .= "													</table>";
	$html .= "												</td>";
	$html .= "											</tr>";
	}
	$html .= "										</table>";
	$html .= "									</td>";
	$html .= "								</tr>";
	}
	$html .= "							</table>";
	$html .= "						</td>";
	}
	$html .= "					</tr>";
	}

	$html .= "				</table>";
	
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