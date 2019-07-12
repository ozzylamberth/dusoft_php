<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: FacturasHTML.class.php,v 1.1 2010/04/08 20:36:35 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: FacturasHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class FacturasHTML	
	{
    /**
    * Constructor de la clase
    */
    function FacturasHTML(){}
    /**
    * Funcion donde se crea el $html de las factuars
    *
    * @return string
    */
    function FormaFacturas($action,$request,$prefijos,$terceros,$facturas,$conteo, $pagina)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      
 			$html  = $ctl->LimpiarCampos();
      $html .= ThemeAbrirTabla('BUSCAR FACTURAS - CREACIÓN DE NOTAS');
      $html .= "<form name=\"facturas\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		      <table width=\"100%\">\n";
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\">FACTURA</td>\n";
			$html .= "			        <td>\n";
			$html .= "				        <select name=\"buscador[prefijo]\" class=\"select\">\n";
			$html .= "						      <option value='-1'>---</option>\n";
			foreach($prefijos as $k => $dtl)
			{
				$sel = ($request['prefijo'] == $dtl['prefijo'])?  "selected":"";
				$html .= "				<option value='".$dtl['prefijo']."' ".$sel.">".$dtl['prefijo']."</option>\n";
			}
			$html .= "				        </select>\n";
			$html .= "				      </td>\n";
			$html .= "			        <td>\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[factura_fiscal]\" size=\"25\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" value=\"".$request['factura_fiscal']."\">\n";
			$html .= "			        </td>\n";
			$html .= "			      </tr>\n";
			$html .= "			      <tr>\n";
			$html .= "				      <td class=\"label\" width=\"25%\">TIPO DOCUMENTO</td>\n";
			$html .= "				      <td colspan=\"2\">\n";
			$html .= "					      <select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
			$html .= "						      <option value='-1'>---SELECCIONAR---</option>\n";
			
			foreach($terceros as $key => $dtl)
			{
				$sel = ($request['tipo_id_tercero'] == $dtl['tipo_id_tercero'])? "selected": "";
				$html .= "						    <option value='".$dtl['tipo_id_tercero']."' ".$sel.">".ucwords(strtolower($dtl['descripcion']))."</option>\n";
			}
			$html .= "					      </select>\n";
			$html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "			      <tr>\n";
			$html .= "				      <td class=\"label\">DOCUMENTO</td>\n";
			$html .= "				      <td colspan=\"2\">\n";
			$html .= "					      <input type=\"text\" class=\"input-text\" name=\"buscador[documento]\" size=\"25\" maxlength=\"32\" onkeypress=\"return acceptNum(event)\" value=\"".$request['documento']."\">\n";
			$html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "			      <tr>\n";
			$html .= "				      <td class=\"label\">FECHA</td>\n";
			$html .= "				      <td width=\"25%\">\n";
			$html .= "					      <input type=\"text\" class=\"input-text\" id=\"fecha_factura\" name=\"buscador[fecha_factura]\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_factura']."\">\n";
			$html .= "				      </td>\n";
			$html .= "				      <td class=\"label\">\n";
			$html .= "					      ".ReturnOpenCalendario('facturas','fecha_factura','/',1)."\n";
			$html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "			      <tr>\n";
			$html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
			$html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.facturas)\">\n";
			$html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "		      </table>\n";
			$html .= "	      </fieldset>\n";
			$html .= "	    </td>\n";
			$html .= "	  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
      
      if($facturas)
      {
        $html .= "	<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"modulo_table_list_title\" >\n";
        $html .= "			<td width=\"15%\" >FACTURA</td>\n";
        $html .= "			<td width=\"15%\" >F. FACTURA</td>\n";
        $html .= "			<td width=\"31%\" >TERCERO</td>\n";
        $html .= "			<td width=\"15%\" >SALDO</td>\n";
        $html .= "			<td width=\"15%\" >VALOR</td>\n";
        $html .= "			<td width=\"9%\" colspan=\"2\" >&nbsp;</td>\n";
        $html .= "		</tr>\n";
              
        foreach($facturas as $k1 => $dtl)
        {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
          
          $saldo = ($dtl['saldo'] > 0)? $dtl['saldo']: $dtl['total_factura'];
          
          $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
          $html .= "			<td>".$dtl['prefijo']." ".$dtl['factura_fiscal']."</td>\n";
          $html .= "			<td align=\"center\">".$dtl['fecha_registro']."</td>\n";
          $html .= "			<td >".$dtl['nombre_tercero']."</td>\n";
          $html .= "			<td align=\"right\"	>$".formatoValor($saldo)."</td>\n";
          $html .= "			<td align=\"right\" >$".formatoValor($dtl['total_factura'])."</td>\n";
          $dtl['tipo_nota'] = "C";
          $html .= "			<td align=\"center\" >\n";
          $html .= "			  <a title=\"CREAR NOTA CREDITO\" class=\"label_error\" href=\"".$action['notas'].URLRequest($dtl)."\">\n";
          $html .= "			    <image src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">NC\n";
          $html .= "			  </a>\n";
          $html .= "      </td>\n";          
          $dtl['tipo_nota'] = "D";
          $html .= "			<td align=\"center\" >\n";
          $html .= "			  <a title=\"CREAR NOTA DEBITO\" class=\"label_error\" href=\"".$action['notas'].URLRequest($dtl)."\">\n";
          $html .= "			    <image src=\"".GetThemePath()."/images/pinactivo.png\" border=\"0\">ND\n";
          $html .= "			  </a>\n";
          $html .= "      </td>\n";
          $html .= "		</tr>\n";
        }
        $html .= "		</table>\n";
				$html .= "		<br>\n";
        $pgn = AutoCarga::factory("ClaseHTML");
				$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
      $html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr><td align=\"center\">\n";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
      $html .= ThemeCerrarTabla();

      return $html;
    }
    /**
    *
    * @return string
    */
    function FormaCuerpoNota($action, $tiponota,$factura,$conceptos,$departamentos,$auditores)
		{
			$es = "class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-indent: 8pt\" height=\"19\"";
			
      $ctl= AutoCarga::factory("ClaseUtil");
      $html  = $ctl->AcceptNum(true);

			$html .= ThemeAbrirTabla("FACTURA ".$factura['prefijo']." ".$factura['factura_fiscal']." - CREACION NOTA ".(($tiponota == "C")? "CREDITO":"DEBITO"));
			$html .= "	<script>\n";
			$html .= "		function Alerta()\n";
			$html .= "		{\n";
			$html .= "			var saldo = ".$factura['saldo']." ;\n";
			$html .= "			var total = 0;\n";
			$html .= "			try\n";
      $html .= "      {\n";
			$html .= "				total = xGetElementById('total_nota').value*1;\n";
			$html .= "			}catch(error){}\n";
 			$html .= "			try\n";
      $html .= "      {\n";
			$html .= "			  total += document.adicionarconcepto.valor_concepto.value*1\n";
			$html .= "			}catch(error){}\n";
			$html .= "		  if(total > saldo )\n";
			$html .= "			{\n";
			$html .= "				xGetElementById('mensaje').innerHTML = 'EL VALOR TOTAL DE LA NOTA ($'+ total+') NO DEBE SER MAYOR AL SALDO DE LA FACTURA ($'+saldo+')';\n";
			$html .= "				IniciarA();\n";
			$html .= "				MostrarSpan('Alerta');\n";
			$html .= "				return false;\n";
			$html .= "			}\n";
			$html .= "			return true;\n";
			$html .= "		}\n";
			$html .= "		function AdicionarConcepto()\n";
			$html .= "		{\n";
			$html .= "		  rst = Alerta()\n";
			$html .= "		  if(rst == true)\n";
			$html .= "		    xajax_AdicionarConcepto(xajax.getFormValues('adicionarconcepto'))\n";
			$html .= "		}\n";			
      $html .= "		function EliminarConceptos(concepto)\n";
			$html .= "		{\n";
			$html .= "		  xajax_EliminarConcepto(xajax.getFormValues('adicionarconcepto'),concepto);\n";
			$html .= "		}\n";      
      $html .= "		function ActualizarInformacion()\n";
			$html .= "		{\n";
			$html .= "		  xajax_InformacionNota(xajax.getFormValues('oculta'));\n";
			$html .= "		}\n";
			$html .= "		function BuscarTercero()\n";
			$html .= "		{\n";
			$html .= "			var url=\"".$action['terceros']."\"\n";
			$html .= "			window.open(url,'','width=750,height=650,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no');\n";
			$html .= "		}\n";
      $html .= "    function DescartarNota()\n";
      $html .= "    {\n";
      $html .= "      document.crear_nota.action=\"".$action['descartar']."\";\n";
      $html .= "      document.crear_nota.submit();\n";
      $html .= "    }\n";      
      $html .= "    function CrearNota()\n";
      $html .= "    {\n";
      $html .= "      document.crear_nota.action=\"".$action['crear']."\";\n";
      $html .= "      document.crear_nota.submit();\n";
      $html .= "    }\n";
			$html .= "	</script>\n";
			
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"modulo_list_claro\">\n";
			$html .= "		<td  colspan=\"2\" class=\"formulacion_table_list\">TERCERO</td>\n";
			$html .= "	</tr>\n";			
      $html .= "	<tr class=\"modulo_list_claro\">\n";
			$html .= "		<td width=\"30%\" class=\"normal_10AN\" >".$factura['tipo_id_tercero']." ".$factura['tercero_id']."</td>\n";
 			$html .= "		<td class=\"normal_10AN\" >".$factura['nombre_tercero']."</td>\n";
			$html .= "	</tr>\n";			
      $html .= "	<tr class=\"modulo_list_claro\">\n";
			$html .= "		<td class=\"formulacion_table_list\">SALDO FACTURA</td>\n";
			$html .= "		<td class=\"normal_10AN\" >".formatoValor($factura['saldo'])."</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";
      
			if(!empty($conceptos))
			{
				$html .= "<form name=\"adicionarconcepto\" id=\"adicionarconcepto\" action=\"javascript:AdicionarConcepto()\" method=\"post\">\n";
				$html .= "	<table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "		<tr class=\"modulo_table_list_title\">\n";
				$html .= "			<td align=\"left\" width=\"10%\"><b>CONCEPTO</b></td>\n";
				$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
				$html .= "			  <input type=\"hidden\" name=\"tipo_nota\" value=\"".$tiponota."\">\n";
				$html .= "			  <input type=\"hidden\" name=\"tmp_nota_id\" id=\"tmp_nota_id_a\" value=\"".$factura['tmp_nota_contado_id']."\">\n";
				$html .= "			  <input type=\"hidden\" name=\"empresa_id\" value=\"".$factura['empresa_id']."\">\n";
				$html .= "			  <input type=\"hidden\" name=\"factura_fiscal\" value=\"".$factura['factura_fiscal']."\">\n";
				$html .= "			  <input type=\"hidden\" name=\"prefijo\" value=\"".$factura['prefijo']."\">\n";
				$html .= "			  <input type=\"hidden\" name=\"saldo\" value=\"".$factura['saldo']."\">\n";
				$html .= "				<select name=\"concepto\" id=\"concepto\" class=\"select\" onChange=\"xajax_ActivarSeleccion(xajax.getFormValues('adicionarconcepto'))\">\n";
				$html .= "					<option value='-1'>-----SELECCIONAR-----</option>\n";
				foreach($conceptos as $key => $dtl)
					$html .= "					<option value='".$dtl['nota_contado_concepto_id']."'>".$dtl['descripcion']."</option>\n";

				$html .= "				</select>\n";
				$html .= "			</td>\n";
				$html .= "			<td align=\"left\" class=\"modulo_list_claro\" align=\"center\" width=\"20%\">\n";
				$html .= "				<b>$</b><input type=\"text\" name=\"valor_concepto\" class=\"input-text\" size=\"14\" maxlength=\"15\" style=\"width:95%\" value=\"".$this->ValorConcepto."\" onKeypress=\"return acceptNum(event);\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				
				if(!empty($departamentos))
				{
					$html .= "		<tr class=\"modulo_table_list_title\">\n";
					$html .= "			<td align=\"left\" width=\"10%\"><b>DEPARTAMENTO</b></td>\n";
					$html .= "			<td align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">\n";
					$html .= "				<select name=\"departamento\" id=\"departamento\" class=\"select\" disabled>\n";
					$html .= "					<option value=\"-1\">-------SELECCIONAR-------</option>\n";
					foreach($departamentos as $key => $dtl)
						$html .= "					<option value='".$dtl['departamento']."' >".$dtl['descripcion']."</option>\n";
					
					$html .= "				</select>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "		<tr class=\"modulo_table_list_title\">\n";
					$html .= "			<td align=\"left\" width=\"10%\"><b>TERCERO</b></td>\n";
					$html .= "			<td align=\"left\" class=\"modulo_list_claro\" >\n";
					$html .= "				<input type=\"text\" name=\"nombre_tercero\" id=\"nombre_tercero\" class=\"input-text\" style=\"width:100%\"  readonly>\n";
					$html .= "				<input type=\"hidden\" name=\"tipo_id_tercero\" id=\"tipo_id_tercero\"  >\n";					
					$html .= "				<input type=\"hidden\" name=\"tercero_id\" id=\"tercero_id\">\n";					
					$html .= "			</td>\n";
					$html .= "			<td class=\"modulo_list_claro\">\n";
					$html .= "				<input type=\"button\" class=\"input-submit\" value=\"Buscar Tercero\" name=\"boton_tercero\" id=\"boton_tercero\" disabled onclick=\"BuscarTercero()\">\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";					
				}
				$html .= "		<tr>\n";
				$html .= "			<td class=\"modulo_list_claro\" align=\"center\" width=\"15%\" colspan=\"3\">\n";
				$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Agregar Concepto\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
  			$html .= "<div id=\"error\" style=\"text-align:center\"></div>\n";
        $html .= "</form>\n";
			}
      $html .= "	<table width=\"50%\" align=\"center\" >\n";		
      $html .= "		<tr>\n";
      $html .= "			<td align=\"center\">\n";
      $html .= "				<a class=\"label_error\" href=\"javascript:Iniciar();MostrarSpan('Contenedor')\" title=\"OBSERVACIÓN NOTA AJUSTE\">\n";
      $html .= "					<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"><b>MODIFICAR INFORMACIÓN NOTA</b>";
      $html .= "				</a>\n";
      $html .= "			</td>\n";
      $html .= "		</tr>\n";
      $html .= "	</table><br>\n";
      
      $html .= "<form name=\"crear_nota\" id=\"crear_nota\" action=\"\" method=\"post\">\n";
			$html .= "	<input type=\"hidden\" name=\"tipo_nota\" value=\"".$tiponota."\">\n";
      $html .= "	<input type=\"hidden\" name=\"tmp_nota_id\" id=\"tmp_nota_id_b\" value=\"".$factura['tmp_nota_contado_id']."\">\n";
			$html .= "	<input type=\"hidden\" name=\"factura_fiscal\" value=\"".$factura['factura_fiscal']."\">\n";
			$html .= "	<input type=\"hidden\" name=\"prefijo\" value=\"".$factura['prefijo']."\">\n";
      $html .= "  <div id=\"lista_conceptos\">\n";
      $html .= "  </div>\n";
      $html .= "</form>\n";
      
			$html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr><td align=\"center\">\n";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
			
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 2;\n";
			$html .= "	function OcultarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";
			$html .= "	function Iniciar()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Contenedor';\n";
			$html .= "		titulo = 'titulo';\n";			
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,350, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+100);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,330, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele, 330, 0);\n";
			$html .= "	}\n";
			
			$html .= "	function IniciarA()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Alerta';\n";
			$html .= "		titulo = 'tituloa';\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,350, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+100);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,330, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrara');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele, 330, 0);\n";
			$html .= "	}\n";
			
			$html .= "	function myOnDragStart(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	  window.status = '';\n";
			$html .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "	  else xZIndex(ele, hiZ++);\n";
			$html .= "	  ele.myTotalMX = 0;\n";
			$html .= "	  ele.myTotalMY = 0;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag(ele, mdx, mdy)\n";
			$html .= "	{\n";
			$html .= "	  if (ele.id == titulo) {\n";
			$html .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "	  }\n";
			$html .= "	  else {\n";
			$html .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "	  }  \n";
			$html .= "	  ele.myTotalMX += mdx;\n";
			$html .= "	  ele.myTotalMY += mdy;\n";
			$html .= "	}\n";
			$html .= "	function myOnDragEnd(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">OBSERVACIÓN</div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content' style=\"background:#EFEFEF\">\n";
			$html .= "		<form name=\"oculta\" id=\"oculta\" action=\"javascript:ActualizarInformacion()\" method=\"post\">\n";
			$html .= "			<input type=\"hidden\" name=\"empresa_id\" value=\"".$factura['empresa_id']."\">\n";
			$html .= "			<input type=\"hidden\" name=\"factura_fiscal\" value=\"".$factura['factura_fiscal']."\">\n";
			$html .= "			<input type=\"hidden\" name=\"prefijo\" value=\"".$factura['prefijo']."\">\n";
      $html .= "			<input type=\"hidden\" name=\"tmp_nota_id\" id=\"tmp_nota_id_c\" value=\"".$factura['tmp_nota_contado_id']."\">\n";
			$html .= "			<input type=\"hidden\" name=\"tipo_nota\" value=\"".$tiponota."\">\n";
      $html .= "			<table width=\"100%\" align=\"center\">\n";
			$html .= "				<tr class=\"modulo_table_list_title\">\n";
			$html .= "					<td>AUDITOR</td>\n";
			$html .= "					<td class=\"modulo_list_claro\">\n";
      $html .= "					  <select name=\"auditor_id\" class=\"select\">\n";
			$html .= "						  <option value=\"-1\">-----SELECCIONAR-----</option>\n";
			
			foreach($auditores as $key => $auditor)
      {
				$sel = ($key == $nota['auditor_id'])? "selected": ""; 
        $html .= "									<option value='".$key."' ".$sel.">".ucwords(strtolower($auditor['nombre']))."</option>\n";
			}
			$html .= "								</select>\n";
      $html .= "          </td>\n";
			$html .= "				</tr>\n";			
      $html .= "				<tr class=\"modulo_table_list_title\">\n";
			$html .= "					<td colspan=\"2\">OBSERVACIÓN</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr class=\"modulo_table_list_title\">\n";
			$html .= "					<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
			$html .= "						<textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"observa\" >".$factura['observacion']."</textarea>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr>\n";
			$html .= "					<td colspan=\"3\" align=\"center\">\n";
			$html .= "						<input type=\"submit\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\">&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$html .= "						<input type=\"button\" class=\"input-submit\"name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan('Contenedor')\">\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "			</table>\n";
			$html .= "		</form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			
			$html .= "<div id='Alerta' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='tituloa' class='draggable' style=\"	text-transform: uppercase;text-align:center\">OBSERVACIÓN</div>\n";
			$html .= "	<div id='cerrara' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Alerta')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenidoa' class='d2Content' style=\"background:#FFFFFF\">\n";
			$html .= "		<table width=\"100%\" align=\"center\">\n";
			$html .= "			<tr class=\"label_error\">\n";
			$html .= "				<td id=\"mensaje\" align=\"center\"></td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr>\n";
			$html .= "				<td colspan=\"3\" align=\"center\">\n";
			$html .= "					<input type=\"button\" class=\"input-submit\"name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan('Alerta')\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			$html .= "<script>\n";
 			$html .= "  xajax_ListarConceptos(xajax.getFormValues('adicionarconcepto'));\n";
			$html .= "</script>\n";
			$html .= ThemeCerrarTabla();
			
			return $html;
		}
    /**
    * Funcion donde se crea la forma del buscador de terceros.
    *
    * @return string
    */
    function FormaBuscarTerceros($action,$request,$tipos_terceros,$terceros,$conteo,$pagina)
		{
			$ctl= AutoCarga::factory("ClaseUtil");
      $html  = $ctl->RollOverFilas();
      $html .= $ctl->LimpiarCampos();
      $html .= ThemeAbrirTabla("TERCEROS");
			$html .= "	<script>\n";
			$html .= "		function Guardar(tipo_id,identificacion,nombre)\n";
			$html .= "		{\n";
			$html .= "			window.opener.document.adicionarconcepto.tipo_id_tercero.value = tipo_id;\n";
			$html .= "			window.opener.document.adicionarconcepto.tercero_id.value = identificacion;\n";
			$html .= "			window.opener.document.adicionarconcepto.nombre_tercero.value = nombre;\n";
			$html .= "			Cerrar();\n";
			$html .= "		}\n";
			$html .= "		function Cerrar()\n";
			$html .= "		{\n";
			$html .= "			window.close();\n";
			$html .= "		}\n";
			$html .= "	</script>\n";
			$html .= "	<table width=\"70%\" align=\"center\" >\n";		
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
      $html .= "<form name=\"buscador\" action=\"".$action['buscar']."\" method=\"post\">\n";
			$html .= "	<fieldset class=\"fieldset\">\n";
      $html .= "    <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		<table>\n";
			$html .= "			<tr><td class=\"label\">TIPO DOCUMENTO CLIENTE</td>\n";
			$html .= "				<td>\n";
			$html .= "					<select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
			$html .= "						<option value='-1'>-----SELECCIONAR-----</option>\n";
			
			foreach($tipos_terceros as $k => $dtl)
			{
				$sel = ($request['tipo_id_tercero'] == $dtl['tipo_id_tercero'])?  "selected": "";
				$html .= "						<option value='".$dtl['tipo_id_tercero']."' ".$sel.">".$dtl['descripcion']."</option>\n";
			}
			
			$html .= "					</select>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";	
			$html .= "			<tr>\n";
			$html .= "				<td class=\"label\">DOCUMENTO</td>\n";
			$html .= "				<td>\n";
			$html .= "					<input type=\"text\" class=\"input-text\" name=\"buscador[tercero_id]\" style=\"width:80%\" value=\"".$request['tercero_id']."\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr>\n";
			$html .= "				<td class=\"label\">NOMBRE</td>\n";
			$html .= "				<td>\n";
			$html .= "					<input type=\"text\" class=\"input-text\" name=\"buscador[nombre_tercero]\" style=\"width:80%\" value=\"".$request['nombre_tercero']."\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr>\n";
			$html .= "				<td class=\"label\" align=\"center\" colspan=\"2\"><br>\n";
			$html .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscador[buscar]\" value=\"Buscar\">\n";
			$html .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
			$html .= "	</fieldset>\n";
			$html .= "</form>\n";			
      $html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>\n";
			
			if(!empty($terceros))
			{
				$html .= "	<table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "			<tr class=\"modulo_table_list_title\" height=\"19\">\n";
				$html .= "				<td width=\"22%\"><b>DOCUMENTO</b></td>\n";
				$html .= "				<td width=\"75%\"><b>NOMBRE CLIENTE</b></td>\n";
				$html .= "				<td width=\"3%\" ><b>OPCIONES</b></td>\n";
				$html .= "			</tr>";
				foreach($terceros as $k1 => $dtl )
				{
				  $est = ($est == 'modulo_list_oscuro')? "modulo_list_claro":"modulo_list_oscuro";
          $bck = ($bck == "#CCCCCC")? "#DDDDDD" : "#CCCCCC";

					
					$opcion  = "	<a class=\"label_error\" href=\"javascript:Guardar('".$dtl['tipo_id_tercero']."','".$dtl['tercero_id']."','".$dtl['nombre_tercero']."')\" title=\"SELECCIONAR\">\n";
					$opcion .= "	<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a>\n";
					
					$html .= "			<tr class=\"".$est."\" height=\"21\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$html .= "				<td align=\"left\"   >".$dtl['tipo_id_tercero']." ".$dtl['tercero_id']."</td>\n";
					$html .= "				<td align=\"justify\">".$dtl['nombre_tercero']."</td>\n";
					$html .= "				<td align=\"center\" >$opcion</td>\n";						
					$html .= "			</tr>\n";
				}
				$html .= "	</table><br>\n";
									
				$pgn = AutoCarga::factory('ClaseHTML');
				$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
				$html .= "		<br>\n";
			}
		
			$html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"button\" class=\"input-submit\" value=\"Cerrar\" onclick=\"Cerrar()\" >\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
    /**
    * Funcion donde se crea la lista de conceptos adicionados
    *
    * @param array $conceptos Arreglo de datos con la informcion de los conceptos adicionados
    *
    * @return string
    */
    function CrearListaConceptos($conceptos)
		{
			$html = "";
      if(!empty($conceptos))
			{	
				$html  = "	<table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$html .= "		<tr class=\"formulacion_table_list\">\n";
				$html .= "			<td align=\"center\" width=\"2%\"><b>X</b></td>\n";
				$html .= "			<td align=\"center\" width=\"45%\"><b>CONCEPTO</b></td>\n";
				$html .= "			<td align=\"center\" width=\"%\"><b>DEPARTAMENTO / TERCERO</b></td>\n";
				$html .= "			<td align=\"center\" width=\"8%\"><b>VALOR</b></td>\n";
				$html .= "		</tr>\n";
				
				$suma = 0;
				foreach($conceptos as $key => $Concep)
				{					
					
					$html .= "		<tr class=\"modulo_list_claro\">\n";
					$html .= "			<td align=\"center\">\n";
					$html .= "	      <a href=\"javascript:EliminarConceptos('".$key."')\" >\n";
					$html .= "		      <img src=\"".GetThemePath()."/images/checkS.gif\" title=\"ELIMINAR CONCEPTO\" border=\"0\">";
					$html .= "	      </a>\n";  
          $html .= "      </td>\n";
					$html .= "			<td class=\"normal_10AN\" >".$Concep['descripcion']."</td>\n";
					$html .= "			<td class=\"normal_10AN\" >".$Concep['departamento']."/".$Concep['nombre_tercero']."</td>\n";
					$html .= "			<td align=\"right\"><b>$".formatoValor($Concep['valor'])."</b></td>\n";
					$html .= "		</tr>\n";
					
					$suma += $Concep['valor']; 
				}
				
				$html .= "		<tr class=\"formulacion_table_list\">\n";
				$html .= "			<td colspan=\"3\">TOTAL CONCEPTOS</td>\n";
				$html .= "			<td class=\"modulo_list_claro\" style=\"text-align:right\">\n";
        $html .= "        $".formatoValor($suma)."\n";
				$html .= "	      <input type=\"hidden\" name=\"total_nota\" id=\"total_nota\" value =\"".$suma."\">\n";
        $html .= "      </td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
				$html .= "	<br>\n";
        $html .= "	<table width=\"50%\" align=\"center\">\n";
  			$html .= "	  <tr align=\"center\">\n";
  			$html .= "		  <td width=\"50%\">\n";
  			$html .= "			  <input type=\"button\" class=\"input-submit\" name=\"crear\" value=\"Crear Nota\" onclick=\"CrearNota()\">\n";
  			$html .= "			</td>\n";  			
        $html .= "		  <td width=\"50%\">\n";
  			$html .= "			  <input type=\"button\" class=\"input-submit\" name=\"eliminar\" value=\"Descartar Nota\" onclick=\"DescartarNota()\">\n";
  			$html .= "			</td>\n";
  			$html .= "		</tr>\n";
  			$html .= "	</table>\n";
			}
			return $html;
		}
  }
?>