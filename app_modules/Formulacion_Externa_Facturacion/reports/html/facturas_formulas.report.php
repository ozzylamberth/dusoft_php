<?php
	/**
	* $Id: reporte_detalle_auditoria.report.php,v 1.1 2010/04/08 20:36:35 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('ClaseUtil');
  IncludeClass("Consultas_ESM_Cortes","classes","app","Formulacion_Externa_Facturacion");
	class facturas_formulas_report
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
	  function facturas_formulas_report($datos=array())
	  {
			$this->datos=$datos;
      return true;
	  }
		/**
    *
    */
		function GetMembrete()
		{
	  $nc = new Consultas_ESM_Cortes();
      
      $parametro['tipo_id_tercero']='-1';
      
      $est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:20pt\"";
		/*$html = "	<table width=\"70%\" align=\"center\" $est rules=\"all\">\n";		
		$html .= "		<tr class=\"label\">\n";
		$html .= "			<td align=\"center\">".$this->datos['razon_social']."   ".$this->datos['tipo_id_empresa'].": ".$this->datos['id']."-".$this->datos['digito_verificacion']."</td>\n";
		$html .= "		</tr>\n";		
		$html .= "		<tr class=\"label\">\n";
		$html .= "			<td align=\"center\">".$this->datos['texto2']."11</td>\n";
		$html .= "		</tr>\n";
		$html .= "		<tr class=\"label\">\n";
		$html .= "			<td align=\"center\">".$this->datos['municipio_empresa']."</td>\n";
		$html .= "		</tr>\n";
		$html .= "</table>";*/
      //$titulo .= "<b $est >REPORTE DE AUDITORIA SELECCIONADA<br>";
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
			$nc = new Consultas_ESM_Cortes();
			$cl = new ClaseUtil();
      
      $Factura_Detalle=$nc->Detalle_Factura($this->datos['empresa_id'],$this->datos['prefijo'],$this->datos['factura_fiscal']);
      /*$Factura_Detalle=$nc->Detalle_Factura($this->datos['empresa_id'],$this->datos['prefijo'],$this->datos['factura_fiscal']);*/
	  /*$Parametros_Retencion=$nc->Parametros_Retencion($this->datos['empresa_id'],$this->datos['anio_factura']);*/
	 	  
		$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
     
		$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" >\n";		
		$html .= "		<tr class=\"label\" align=\"right\">\n";
		$html .= "			<td class=\"label\" ><h2>FACTURA DE VENTA ".$this->datos['prefijo']."... ".$this->datos['factura_fiscal']."</h2></td>\n";
		$html .= "		</tr>\n";	
		$html .= "		<tr class=\"label\" align=\"left\">\n";
		$html .= "			<td class=\"label\" >".$this->datos['tipo_id_tercero_empresa']." ".$this->datos['id']." - ".$this->datos['digito_verificacion']." &nbsp&nbsp&nbsp&nbsp IVA REGIMEN COMUN</td>\n";
		$html .= "		</tr>\n";	
		$html .= "		<tr class=\"label\" align=\"center\">\n";
		$html .= "			<td class=\"label\" >";
		$html .= "				<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;font-size:8.5px\" >\n";
		$html .= "					<tr  align=\"justify\">\n";
		$html .= "						<td>";
		$html .= "							".$this->datos['texto1'];
		/*$html .= "							<b>DIRECCION:</b> ".$this->datos['direccion_empresa'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<b>TELEFONOS:</b> ".$this->datos['telefono_empresa'];
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<b>UBICACION:</b> ".$this->datos['pais_empresa']."-".$this->datos['departamento_empresa']."-".$this->datos['municipio_empresa'];*/
		$html .= "						</td>";
		$html .= "					</tr>";
		/*$html .= "					<tr align=\"center\">\n";
		$html .= "						<td colspan=\"3\">";
		$html .= "						<b>".$this->datos['texto3']."</b>";
		$html .= "						</td>";
		$html .= "					</tr>";*/
		$html .= "				</table>";
		$html .= "			</td>\n";
		$html .= "		</tr>\n";	
		$html .= "		<tr  align=\"center\">\n";
		$html .= "			<td >";
		$html .= "				<table width=\"100%\" align=\"left\" style=\"border:1px solid #000000;font-size:8.5px\" >\n";
		$html .= "					<tr align=\"left\">\n";
		$html .= "						<td width=\"20%\">";
		$html .= "							<b>CLIENTE :</b>";
		$html .= "						</td>";
		$html .= "						<td  width=\"40%\">";
		$html .= "							".$this->datos['nombre_tercero'];
		$html .= "						</td>";
		$html .= "						<td width=\"20%\" align=\"center\">";
		$html .= "							<B>FECHA FACTURA</B>";
		$html .= "						</td>";
		$html .= "						<td width=\"20%\" align=\"center\">";
		$html .= "							<B>FECHA VENCIMIENTO</B>";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr align=\"left\">\n";
		$html .= "						<td >";
		$html .= "							<b>".$this->datos['tipo_id_tercero']." :</b>";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$this->datos['tercero_id']." - ".$this->datos['digito_verificacion'];
		$html .= "						</td>";
		$html .= "						<td align=\"center\">";
		$html .= "							<b>".$this->datos['fecha_registro']."</b>";
		$html .= "						</td>";
		$html .= "						<td align=\"center\">";
		$html .= "							<b>".$this->datos['fecha_registro']."</b>";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr align=\"left\">\n";
		$html .= "						<td >";
		$html .= "							<b>DIRECCION :</b>";
		$html .= "						</td>";
		$html .= "						<td colspan=\"3\">";
		$html .= "							".$this->datos['direccion'];
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr align=\"left\">\n";
		$html .= "						<td >";
		$html .= "							<b>FECHA CORTE :</b>";
		$html .= "						</td>";
		$html .= "						<td colspan=\"3\">";
		$html .= "							".$this->datos['fecha_inicial']." AL ".$this->datos['fecha_final'];
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "				</table>";
		$html .= "			</td>\n";
		$html .= "		</tr>\n";
			
		/*$html .= "		<tr align=\"center\">\n";
		$html .= "			<td >";
		$html .= "				<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;font-size:8px\" >\n";
		$html .= "					<tr align=\"center\">\n";
		$html .= "						<td>";
		$html .= "							<b>PEDIDO No</b>";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<b>FECHA FACTURA</b>";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<b>FECHA VENCIMIENTO</b>";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<b>CONDICIONES</b>";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr align=\"center\">\n";
		$html .= "						<td >";
		$html .= "						".$this->datos['pedido_cliente_id'];
		$html .= "						</td>";
		$html .= "						<td >";
		$html .= "						".$this->datos['fecha_registro'];
		$html .= "						</td>";
		$html .= "						<td >";
		$html .= "						".$this->datos['fecha_vencimiento_factura'];
		$html .= "						</td>";
		$html .= "						<td >";
		$html .= "						".$this->datos['observaciones'];
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "				</table>";
		$html .= "			</td>\n";
		$html .= "		</tr>\n";	*/
		$html .= "	</table>";
		$html .= "  <br>";

		$html .= "  <br>";
		//style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"
		$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;font-size:8px\" rules=\"all\">\n";		
		$html .= "		<tr class=\"label\">\n";
		$html .= "			<td align=\"center\" colspan=\"7\">PRODUCTOS</td>\n";
		$html .= "		</tr>\n";	
		$html .= "		<tr class=\"label\" align=\"center\">\n";
		$html .= "			<td >CODIGO</td><td >PRODUCTO</td><td >CANTIDAD</td><td >VALOR UNIT</td><td >VALOR TOTAL</td><td >%IVA</td>\n";
		$html .= "		</tr>\n";
		$total =0;
		$iva =0;
        foreach($Factura_Detalle as $key=>$valor)
        {
		$html .= "		<tr>\n";
        $html .= "        <td>".$valor['codigo_producto']."</td>";
        $html .= "        <td>".$valor['descripcion']."</td>";
		$html .= "        <td>".FormatoValor($valor['cantidad'])."</td>";
        $html .= "        <td>$".FormatoValor($valor['precio'],4)."</td>";
        $html .= "        <td>$".FormatoValor(($valor['valor_total']),2)."</td>";
        $html .= "        <td>%".FormatoValor($valor['porcentaje_gravamen'],2)."</td>";
        $html .= "		</tr>\n";
		$total = $total+$valor['valor_total'];
		$iva = $iva+$valor['iva'];
        }
        $html .= "	</table><br>\n";
		
		$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;font-size:8px\" >\n";		
		/*$html .= "		<tr align=\"left\">\n";
		$html .= "			<td >\n";
		$html .= "						<B>".$this->datos['texto1']."</B>";
		$html .= "			</td>";
		$html .= "		</tr>\n";*/	
		
		$html .= "		<tr class=\"label\" align=\"center\">\n";
		$html .= "			<td class=\"label\" >";
		$html .= "				<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;font-size:8px\" >\n";
		$html .= "					<tr align=\"center\">\n";
		$html .= "						<td>";
		$html .= "							<B>SUBTOTAL</B>";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<B>IVA</B>";
		$html .= "						</td>";
		/*$html .= "						<td>";
		$html .= "							<B>RET-FTE</B>";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<B>RETE-ICA</B>";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<B>RETE-IVA</B>";
		$html .= "						</td>";*/
		$html .= "						<td>";
		$html .= "							<B>VALOR TOTAL</B>";
		$html .= "						</td>";
		$html .= "					</tr>";
		
		/*if($subtotal >= $Parametros_Retencion['base_rtf'])
		{
		$retencion_fuente = $subtotal*($this->datos['porcentaje_rtf']/100);
		}
		
		if($subtotal >= $Parametros_Retencion['base_ica'])
		{
		$retencion_ica = $subtotal*($this->datos['porcentaje_ica']/1000);
		}
		
		if($subtotal >= $Parametros_Retencion['base_reteiva'])
		{
		$retencion_iva = $IvaTotal*($this->datos['porcentaje_reteiva']/100);
		}
		$TotalFactura = (((($IvaTotal+$subtotal)-$retencion_fuente)-$retencion_ica)-$retencion_iva);*/
		$html .= "					<tr align=\"center\">\n";
		$html .= "						<td>";
		$html .= "							<B>$".FormatoValor(($total-$iva),2)."</B>";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<B>$".FormatoValor(($iva),2)."</B>";
		$html .= "						</td>";
		/*
		$html .= "						<td>";
		$html .= "							<B>$".FormatoValor(($retencion_fuente),2)."</B>";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<B>$".FormatoValor(($retencion_ica),2)."</B>";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<B>$".FormatoValor(($retencion_iva),2)."</B>";
		$html .= "						</td>";*/
		$html .= "						<td>";
		$html .= "							<B>$".FormatoValor(($total),2)."</B>";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr align=\"left\">\n";
		$html .= "						<td colspan=\"6\">";
		
		$arreglo=explode(".",$total);
		$html .= "SON: ".strtoupper($cl->num2letras($arreglo[0],false,true))." PESOS ";
		/*if($arreglo[1]>0^*/
		$html .= " CON ".$arreglo[1]." CENTAVOS.";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "				</table>";
		$html .= "			</td>\n";
		$html .= "		</tr>\n";
		/*$html .= "		<tr align=\"left\">\n";
		$html .= "			<td >\n";
		$html .= "						<B>".$this->datos['mensaje']."</B>";
		$html .= "			</td>";
		$html .= "		</tr>\n";	*/		
		$html .= "	</table>";
		/*$html .= "  <br>";
		$html .= "	<table width=\"100%\" align=\"center\" style=\"border:0px solid #000000;font-size:7px\"\" >\n";		
		$html .= "		<tr>\n";
		$html .= "			<td WIDTH=\"31.3%\">";
		
		$html .= "				<table align=\"center\" width=\"100%\" class=\"modulo_list_claro\" style=\"border:0px solid #000000;font-size:7px\"\">\n";
		$html .= "  				<tr>\n";
		$html .= "    					<td align=\"left\">\n";
		$html .= "     						ATENTAMENTE :";
		$html .= "    					</td>\n";
		$html .= "  				</tr>\n";
		$html .= "  				<tr>\n";
  		$html .= "    					<td align=\"center\">\n";
		$html .= "      					<hr width=\"100%\">";
		$html .= "    					</td>\n";
		$html .= "  				</tr>\n";
		$html .= "				</table>\n";
		
		$html .= "			</td>";
		
		$html .= "			<td WIDTH=\"31.3%\">";
		
		$html .= "				<table align=\"center\" width=\"100%\" class=\"modulo_list_claro\" style=\"border:0px solid #000000;font-size:7px\"\">\n";
		$html .= "  				<tr>\n";
		$html .= "    					<td align=\"left\">\n";
		$html .= "     						FIRMA Y SELLO DEL CLIENTE :";
		$html .= "    					</td>\n";
		$html .= "  				</tr>\n";
		$html .= "  				<tr>\n";
  		$html .= "    					<td align=\"center\">\n";
		$html .= "      					<hr width=\"100%\">";
		$html .= "    					</td>\n";
		$html .= "  				</tr>\n";
		$html .= "				</table>\n";
		
		$html .= "			<td WIDTH=\"37.3%\">";
		
		$html .= "				<table align=\"center\" width=\"100%\" class=\"modulo_list_claro\" style=\"border:0px solid #000000;font-size:7px\"\">\n";
		$html .= "  				<tr>\n";
		$html .= "    					<td align=\"left\">\n";
		$html .= "							<table align=\"center\" width=\"100%\" class=\"modulo_list_claro\" style=\"border:0px solid #000000;font-size:7px\"\">\n";
		$html .= "								<tr>";
		$html .= "									<td>";
		$html .= "     									NOMBRE QUIEN RECIBE :";
		$html .= "    								</td>\n";
		$html .= "  							</tr>\n";
		$html .= "  							<tr>\n";
  		$html .= "    								<td align=\"center\">\n";
		$html .= "      								<hr width=\"100%\">";
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "								<tr>";
		$html .= "									<td>";
		$html .= "     									NUMERO IDENTIFICACION :";
		$html .= "    								</td>\n";
		$html .= "  							</tr>\n";
		$html .= "  							<tr>\n";
  		$html .= "    								<td align=\"center\">\n";
		$html .= "      								<hr width=\"100%\">";
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "								<tr>";
		$html .= "									<td>";
		$html .= "     									FECHA - RECIBIDO :";
		$html .= "    								</td>\n";
		$html .= "  							</tr>\n";
		$html .= "  							<tr>\n";
  		$html .= "    								<td align=\"center\">\n";
		$html .= "      								<hr width=\"100%\">";
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "							</table>";
		$html .= "    					</td>\n";
		$html .= "  				</tr>\n";
		$html .= "				</table>\n";
		
		$html .= "			</td>";
		
		$html .= "			</td>";
		$html .= "		</tr>\n";					
		$html .= "	</table>";*/
		
		/*
		$html .= "     						NOMBRE QUIEN RECIBE :";
		$html .= "    					</td>\n";
		$html .= "  				</tr>\n";
		$html .= "  				<tr>\n";
  		$html .= "    					<td align=\"center\">\n";
		$html .= "      					<hr width=\"100%\">";
		*/
		
		
		
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