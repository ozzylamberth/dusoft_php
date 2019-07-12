<?php
	/**
	* $Id: reporte_pedido_cliente.report.php,v 1.1 2010/04/09 19:50:04 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('AutoCarga');
	class Reporte_ProductosPendientes_report 
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
	  function Reporte_ProductosPendientes_report($datos=array())
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
			$titulo .= "<b $est >REPORTE DE PRODUCTOS PENDIENTES POR DESPACHAR ".$this->datos['pedido_cliente_id_tmp']."<br>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
		$sql = AutoCarga::factory("ContratacionProductosClienteSQL","classes","app","ContratacionProductosCliente");
		$cl = AutoCarga::factory('ClaseUtil');
		$detl = $sql->Listado_ProductosPendientes($this->datos['empresa']);
		$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
		$html .= $cl->RollOverFilas();
      if(!empty($detl))
      {
		$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
		$html .= "		<tr class=\"formulacion_table_list\" align=\"center\">\n";
		$html .= "			<td class=\"label\">#PEDIDO</td>\n";
		$html .= "			<td class=\"label\">CODIGO PRODUCTO</td>\n";
		$html .= "			<td class=\"label\">PRODUCTO</td>\n";
		$html .= "			<td class=\"label\">CANTIDAD SOLICITADA</td>\n";
		$html .= "			<td class=\"label\">CANTIDAD DESPACHADA</td>\n";
		$html .= "			<td class=\"label\">CANTIDAD PENDIENTE</td>\n";
		$html .= "		</tr>\n";

		foreach($detl as $k1 => $dtl)
		{
		$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
		$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";

		$html .= "          <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
		$html .= "			<td align=\"center\">".$dtl['pedido_cliente_id']."</td>\n";
		$html .= "			<td >".$dtl['codigo_producto']."</td>\n";
		$html .= "			<td >".$dtl['descripcion']."</td>\n";
		$html .= "			<td align=\"center\">".FormatoValor($dtl['numero_unidades'],0)."</td>\n";
		$html .= "			<td align=\"center\">".FormatoValor($dtl['cantidad_despachada'],0)."</td>\n";
		$html .= "			<td align=\"center\">".FormatoValor($dtl['cantidad_pendiente'],0)."</td>\n";
		}
		$html .= "	</table><br>\n";
		
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