<?php
	/**
	* $Id: conformes.report.php,v 1.1 2010/04/08 20:36:35 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('ClaseUtil');
  IncludeClass("CrearNotasFacturasProveedores","classes","app","Inv_NotasFacturasProveedor");
  IncludeClass("FacturasDespachoSQL","classes","app","FacturasDespacho");
	class ProveedorDebe_report 
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
	  function ProveedorDebe_report($datos=array())
	  {
			$this->datos=$datos;
      /*print_r($datos);*/
	    return true;
	  }
		/**
    *
    */
		function GetMembrete()
		{
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:10pt\"";
			$titulo .= "<center><b $est >".$this->datos['documento']."</center><br>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$nc = new CrearNotasFacturasProveedores();
			$cl = new ClaseUtil();
			$sql_2 = new FacturasDespachoSQL();
			$parametros_retencion=$sql_2->Parametros_Retencion($this->datos['datos']['empresa_id'],$this->datos['anio_factura']);
			$detl = $nc->DetalleNota($this->datos);
			
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			
			$html .= "<table border=\"1\" width=\"100%\" align=\"center\" rules=\"all\">\n";
			$html .= "	<tr class=\"modulo_list_claro\">\n";
			$html .= "		<td class=\"label\" width=\"15%\" class=\"modulo_list_oscuro\">DOCUMENTO :</td>\n";			
			$html .= "		<td class=\"label\" width=\"15%\">".$this->datos['prefijo']."-".$this->datos['numero']."</td>\n";		
			$html .= "		<td class=\"label\" width=\"15%\">FECHA:</td>\n";			
			$html .= "		<td class=\"label\" width=\"15%\">".$this->datos['fecha_registro']."</td>\n";					
			$html .= "		<td class=\"label\" width=\"15%\">USUARIO:</td>\n";			
			$html .= "		<td class=\"label\" width=\"15%\">".$this->datos['usuario']."</td>\n";			
			$html .= "	</tr>\n";
      
			$html .= "	<tr >\n";
			$html .= "		<td class=\"label\" width=\"15%\">PROVEEDOR :</td>\n";			
			$html .= "		<td class=\"label\" width=\"15%\" style=\"text-indent:10pt;text-align:left\" colspan=\"5\">".$this->datos['tipo_id_tercero']." ".$this->datos['tercero_id']."-".$this->datos['nombre_tercero']."</td>\n";			
			$html .= "	</tr>\n";

			$html .= "	<tr height=\"21\">\n";
			$html .= "		<td class=\"label\" width=\"12%\">VALOR NOTA</td>\n";			
			$html .= "		<td class=\"label\" colspan=\"2\" style=\"text-indent:10pt;text-align:left\">$".FormatoValor($this->datos['valor_nota'],4)."</td>\n";			
			$html .= "		<td class=\"label\" width=\"12%\">#-FACTURA</td>\n";			
			$html .= "		<td class=\"label\" colspan=\"2\" style=\"text-indent:10pt;text-align:left\">".$this->datos['numero_factura']."</td>\n";			
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "	<br>\n";
      if(!empty($detl))
      {
  	$html .= "        <table border=\"1\" width=\"100%\" align=\"center\" rules=\"all\" style=\"text-indent:10pt;text-align:left\">";
    $html .= "        <tr >";
    $html .= "            <td class=\"modulo_table_list_title\" colspan=\"7\" align=\"center\">";
    $html .= "             <b>ITEMS DE LA NOTA</b>";
    $html .= "            </td>";
    $html .= "        </tr>";
    $html .= "        <tr align=\"center\">";
    $html .= "            <td >";
    $html .= "             <b>CODIGO PRODUCTO</b>";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             <b>DESCRIPCION</b>";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             <b>CANTIDAD</b>";
	$html .= "            </td>";
	$html .= "            <td >";
    $html .= "             <b>%IVA</b>";
	$html .= "            </td>";
    $html .= "            <td >";
    $html .= "             <b>VALOR/UNITARIO</b>";
    $html .= "            </td>";
    /*$html .= "            <td >";
    $html .= "             <b>OP</b>";
    $html .= "            </td>";*/
    
    $html .= "        </tr>";
    $i=0;
    foreach($detl as $key=>$dtl)
      {
      $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
      $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
      $porc_iva = 0;
      if($dtl['porc_iva']>0)
      $porc_iva = $dtl['porc_iva']/100;
      
      $valor_iva = ($dtl['valor']*($dtl['cantidad']-$dtl['cantidad_devuelta']))*$porc_iva;
      $valor_Unitario_Iva = $dtl['valor']*$porc_iva;
      
      $acum = $acum + (($dtl['valor']*$dtl['cantidad'])+$valor_iva);
      $html .= "		<tr  align=\"center\" >\n";
      $html .= "			<td >".$dtl['codigo_producto']."</td>\n";
      $html .= "			<td >".$dtl['descripcion']."</td>";
      $html .= "      <td >".FormatoValor($dtl['cantidad']-$dtl['cantidad_devuelta'])."<input type=\"hidden\" name=\"cantidad".$i."\" id=\"cantidad".$i."\" value=\"".($dtl['cantidad']-$dtl['cantidad_devuelta'])."\"></td>\n";
      $html .= "      <td class=\"normal_10AN\">".FormatoValor(($dtl['porc_iva']),2)."%</td>\n";
      $html .= "      <td class=\"normal_10AN\">$".FormatoValor(($dtl['valor_unitario']),4)."</td>\n";
     /* $html .= "      <td rowspan=\"2\"><input disabled=\"true\" ".$dtl['checkbox']." title=\"Seleccionar Item para La Nota\" type=\"checkbox\" name=\"$i\" id=\"$i\" value=\"".$dtl['codigo_producto']."\"class=\"input-checkbox\"></td>\n";*/
      $html .= "			</tr >";
      $html .= "		<tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
      $html .= "			<td colspan=\"5\">";
      
      //Aqui va una Subtabla para Los Items A Glosar
      $html .= "      <table width=\"100%\" class=\"modulo_table_list\">";
      $html .= "          <tr class=\"normal_10AN\">";
      $html .= "              <td >";
      $html .= "             <b>CONCEPTO GENERAL:</b>";
      $html .= "              </td>";
      $html .= "              <td >";
      $html .= "                  ".$dtl['descripcion_concepto_general'];
      $html .= "              </td>";
      $html .= "              <td >";
      $html .= "             <b>VALOR CONCEPTO:</b>";
      $html .= "              </td>";
      $html .= "              <td >";
      $html .= "                  $".FormatoValor($dtl['valor_concepto'],4);
      $html .= "              </td>";
      $html .= "              <td rowspan=\"2\">";
      $html .= "             <b>OBSERVACION:</b>";
      $html .= "              </td>";
      $html .= "              <td rowspan=\"2\">";
      $html .= "             ".$dtl['observacion'];
      $html .= "              </td>";
      $html .= "          </tr>";
      $html .= "          <tr class=\"normal_10AN\">";
      $html .= "              <td>";
      $html .= "                  <b>CONCEPTO ESPECIFICO:</b>";
      $html .= "              </td>";
      $html .= "              <td>";
      $html .= "                  ".$dtl['descripcion_concepto_especifico'];
      $html .= "              </td>";
      $html .= "              <td >";
      $html .= "             <b>VALOR CONCEPTO/UNIDAD:</b>";
      $html .= "              </td>";
      $html .= "              <td >";
      $html .= "                  $".FormatoValor($dtl['valor_concepto']/$dtl['cantidad'],4);
      $html .= "              </td>";
      $html .= "						</tr>";
	  $html .= "			<tr>";
	  $html .= "				<td colspan=\"7\">";
	  /*IMPUESTOS EN EL CONCEPTO*/
	 if($parametros_retencion['sw_rtf']=='2' || $parametros_retencion['sw_rtf']=='3')
					if($this->datos['subtotal'] >= $parametros_retencion['base_rtf'])
					$ret_fuente = $dtl['valor_concepto']*($this->datos['porc_rtf']/100);
					
				if($parametros_retencion['sw_ica']=='2' || $parametros_retencion['sw_ica']=='3')
					if($this->datos['subtotal'] >= $parametros_retencion['base_ica'])
					$ret_ica = $dtl['valor_concepto']*($this->datos['porc_ica']/1000);
					
				if($parametros_retencion['sw_reteiva']=='2' ||$parametros_retencion['sw_reteiva']=='3')
					if($this->datos['subtotal'] >= $parametros_retencion['base_reteiva'])
						$ret_iva = $dtl['iva']*($this->datos['porc_rtiva']/100);
	$total_retfuente += $ret_fuente;					
	$total_retica += $ret_ica;					
	$total_retiva += $ret_iva;					
	$html .= "				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">";
	$html .= "					<tr align=\"center\" class=\"label\">";
	$html .= "						<td width=\"25%\">";
	$html .= "							<u>RET-FTE</u>";
	$html .= "						</td>";
	$html .= "						<td width=\"25%\">";
	$html .= "							<u>RETE-ICA</u>";
	$html .= "						</td>";
	$html .= "						<td width=\"25%\">";
	$html .= "							<u>IVA</u>";
	$html .= "						</td>";
	$html .= "						<td width=\"25%\">";
	$html .= "							<u>RETE-IVA</u>";
	$html .= "						</td>";
	$html .= "				</tr>";
	$html .= "				<tr align=\"center\" >";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($ret_fuente,4);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($ret_ica,4);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($dtl['iva'],2);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($ret_iva,4);
	$html .= "						</td>";
	$html .= "				</tr>";
	$html .= "			</table>";
	  /*FIN IMPUESTOS EN EL CONCEPTO*/
	  $html .= "				</td>";
	  $html .= "          </tr>";
      $html .= "      </table>";
      //Cierre SubTabla
      
      $html .= "      </td>\n";
      $html .= "			</tr >";
      $i++;
      }
    
      
  			$html .= "	</table><br>\n";
	
	
	$html .= "				<table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">";
	$html .= "					<tr align=\"center\" class=\"label\">";
	$html .= "						<td colspan=\"5\">";
	$html .= "							<u>TOTAL NOTA</u>";
	$html .= "						</td>";
	$html .= "					</tr>";
	$html .= "					<tr align=\"center\" class=\"label\">";
	$html .= "						<td width=\"25%\">";
	$html .= "							<u>VALOR BRUTO</u>";
	$html .= "						</td>";
	$html .= "						<td width=\"25%\">";
	$html .= "							<u>RET-FTE</u>";
	$html .= "						</td>";
	$html .= "						<td width=\"25%\">";
	$html .= "							<u>RETE-ICA</u>";
	$html .= "						</td>";
	$html .= "						<td width=\"25%\">";
	$html .= "							<u>RETE-IVA</u>";
	$html .= "						</td>";
	$html .= "						<td width=\"25%\">";
	$html .= "							<u>TOTAL NOTA</u>";
	$html .= "						</td>";
	$html .= "				</tr>";
	$html .= "				<tr align=\"center\" >";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($this->datos['valor_nota'],4);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($total_retfuente,4);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($total_retica,4);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($total_retiva,4);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor(((($this->datos['valor_nota'])-$total_retfuente)-$total_retiva),4);
	$html .= "						</td>";
	$html .= "				</tr>";
	$html .= "			</table><br><br>";
	
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