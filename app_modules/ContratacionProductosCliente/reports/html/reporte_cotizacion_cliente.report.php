<?php
	/**
	* $Id: reporte_pedido_cliente.report.php,v 1.1 2010/04/09 19:50:04 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('AutoCarga');
	class reporte_cotizacion_cliente_report 
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
	  function reporte_cotizacion_cliente_report($datos=array())
	  {
			$this->datos=$datos;			
	    return true;
	  }
		/**
    *
    */
		function GetMembrete()
		{
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:8pt\"";
			$html  ="	<table $est width=\"100%\" align=\"left\" >\n";
			$html .="		<tr>";
			$html .="			<td align=\"center\"  >".$this->datos['razon_social'].": ".$this->datos['tipo_id_empresa']." ".$this->datos['id']."-".$this->datos['digito_verificacion']."</td>";
			$html .="		</tr>";
			$html .="		<tr>";
			$html .="			<td align=\"center\"  >".$this->datos['direccion_empresa']." - ".$this->datos['telefonos']."</td>";
			$html .="		</tr>";
			$html .="		<tr>";
			$html .="			<td align=\"center\" >".$this->datos['ubicacion_empresa']."</td>";
			$html .="		</tr>";
			$html .="		<tr>";
			$html .="			<td align=\"center\" >COTIZACION # ".$this->datos['pedido_cliente_id_tmp']."</td>";
			$html .="		</tr>";
			$html .="	</table>";
			
			/*$html .= "<b $est > ".."<br>";*/
			$titulo .= $html;
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
		$detl = $sql->Consulta_PedidoTemporal_d($this->datos['pedido_cliente_id_tmp']);
		$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
		$html .= $cl->RollOverFilas();
      if(!empty($detl))
      {
		$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			<td width=\"10%\" class=\"label\" colspan=\"2\" align=\"center\">GESTION COMERCIAL</td>\n";
		$html .= "		</tr>\n";
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			<td width=\"10%\" class=\"label\" colspan=\"2\" align=\"center\">REQUISICION DE MEDICAMENTOS Y DISPOSITIVOS MEDICOS</td>\n";
		$html .= "		</tr>\n";
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			<td width=\"10%\" class=\"label\">FECHA: ".$this->datos['fecha_registro']." </td>\n";
		$html .= "			<td width=\"10%\" class=\"label\">CLIENTE: ".$this->datos['nombre_tercero']." ".$this->datos['tipo_id_tercero']."-".$this->datos['tercero_id']." </td>\n";
		$html .= "		</tr>\n";
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			<td width=\"10%\" class=\"label\">DIRECCION: ".$this->datos['direccion']." : ".$this->datos['ubicacion']." </td>\n";
		$html .= "			<td width=\"10%\" class=\"label\">TELEFONO: ".$this->datos['telefono']." ".$this->datos['tipo_id_tercero']."-".$this->datos['tercero_id']." </td>\n";
		$html .= "		</tr>\n";
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
		/*$html .= "			<td width=\"10%\" class=\"label\">FECHA DE DESPACHO: ".$this->datos['fecha_envio']." </td>\n";*/
		$html .= "			<td width=\"10%\" class=\"label\">VENDEDOR: ".$this->datos['tipo_id_vendedor']."-".$this->datos['vendedor_d'].": ".$this->datos['nombre']."  </td>\n";
		$html .= "		</tr>\n";
		$html .= "</table>";
		$html .= "<br><hr>";


		$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
		$html .= "		<tr class=\"formulacion_table_list\" align=\"center\">\n";
		$html .= "			<td class=\"label\">CODIGO PRODUCTO</td>\n";
		$html .= "			<td class=\"label\">PRODUCTO</td>\n";
		$html .= "			<td class=\"label\">CANTIDAD</td>\n";
		$html .= "			<td class=\"label\">%IVA</td>\n";
		$html .= "			<td class=\"label\">VR.UNITARIO</td>\n";
		$html .= "			<td class=\"label\">VR.T(SIN IVA)</td>\n";
		$html .= "			<td class=\"label\">VR.T(CON IVA)</td>\n";
		
		$html .= "		</tr>\n";

		foreach($detl as $k1 => $dtl)
		{
		$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
		$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";

		$html .= "                  <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
		$html .= "			<td >".$dtl['codigo_producto']."</td>\n";
		$html .= "			<td >".$dtl['descripcion']."</td>\n";
		$html .= "			<td >".FormatoValor($dtl['numero_unidades'],0)."</td>\n";
		$html .= "			<td >".FormatoValor($dtl['porc_iva'],2)."</td>\n";
		$html .= "			<td >$".FormatoValor($dtl['valor_unitario'],2)."</td>\n";
		$valor_total_producto = $dtl['numero_unidades']*$dtl['valor_unitario'];
		$valor_total_producto_iva = $dtl['numero_unidades']*$dtl['valor_unitario_iva'];
		$iva_total = $iva_total + $dtl['iva'];
		$subtotal = $subtotal + $valor_total_producto;
		$html .= "						<td>$".FormatoValor($valor_total_producto,2)."</b></td>";
		$html .= "						<td>$".FormatoValor($valor_total_producto_iva,2)."</b></td>";
		$html .= "		</tr>\n";
		}
		$html .= "	</table><br>\n";

		$html .= "	<table width=\"20%\" align=\"center\" rules=\"all\">\n";		
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			<td width=\"10%\" class=\"label\" align=\"center\">CREDITO :</td>\n";
		$html .= "			<td width=\"10%\" class=\"label\" align=\"center\"><input type=\"checkbox\" class=\"input-checkbox\"></td>\n";
		$html .= "		</tr>\n";
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			<td width=\"10%\" class=\"label\" align=\"center\">CONTADO :</td>\n";
		$html .= "			<td width=\"10%\" class=\"label\" align=\"center\"><input type=\"checkbox\" class=\"input-checkbox\"></td>\n";
		$html .= "		</tr>\n";
		$html .= "</table>";
		$html .= "<br>";
		$html .= "	<table width=\"50%\" align=\"center\" rules=\"all\">\n";		
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			<td width=\"10%\" class=\"label\" align=\"center\">SUBTOTAL:</td>\n";
		$html .= "			<td width=\"10%\" class=\"label\" align=\"center\">$".FormatoValor($subtotal,2)."</td>\n";
		$html .= "			<td width=\"10%\" class=\"label\" align=\"center\">IVA :</td>\n";
		$html .= "			<td width=\"10%\" class=\"label\" align=\"center\">$".FormatoValor($iva_total,2)."</td>\n";
		$html .= "		</tr>\n";
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			<td width=\"10%\" class=\"label\" align=\"center\">TOTAl :</td>\n";
		$html .= "			<td width=\"10%\" class=\"label\" align=\"center\">$".FormatoValor(($subtotal+$iva_total),2)."</td>\n";
		$html .= "			<td width=\"10%\" class=\"label\" align=\"center\">-</td>\n";
		$html .= "			<td width=\"10%\" class=\"label\" align=\"center\">-</td>\n";
		$html .= "		</tr>\n";
		$html .= "</table>";
		$html .= "<br><br><hr>";
		$html .= "<center>SOLICITADO POR</center>";
		$html .= "<br><br><br><br><br>";
		$html .= "	<table width=\"50%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			<td width=\"10%\" class=\"label\"  align=\"center\">JEFE DE CARTERA</td>\n";
		$html .= "			<td width=\"10%\" class=\"label\"  align=\"center\">GESTION COMERCIAL</td>\n";
		$html .= "		</tr>\n";
		$html .= "</table>";
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