<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: NotasHTML.class.php,v 1.1 2010/03/09 13:40:54 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: NotasHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class NotasHTML	
	{
    /**
    * Constructor de la clase
    */
    function NotasHTML(){}
    /**
    * Funcion donde se crea el $html de las factuars
    *
    * @return string
    */
    function FormaBuscarNotas($action,$request,$prefijos,$notas,$conteo, $pagina)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      
      $tipos_notas = array();
      $tipos_notas['credito'] = "CREDITO";
      $tipos_notas['debito'] = "DEBITO";
      
 			$html  = $ctl->LimpiarCampos();
      $html .= ThemeAbrirTabla('BUSCAR NOTAS');
      $html .= "<form name=\"facturas\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		      <table width=\"100%\">\n";
      
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\">NOTA</td>\n";
			$html .= "			        <td colspan=\"2\">\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[numero]\" size=\"25\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" value=\"".$request['numero']."\">\n";
			$html .= "			        </td>\n";
			$html .= "			      </tr>\n";
      
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\">FACTURA</td>\n";
			$html .= "			        <td>\n";
			$html .= "				        <select name=\"buscador[prefijo_factura]\" class=\"select\">\n";
			$html .= "						      <option value='-1'>---</option>\n";
			foreach($prefijos as $k => $dtl)
			{
				$sel = ($request['prefijo_factura'] == $dtl['prefijo_factura'])?  "selected":"";
				$html .= "				<option value='".$dtl['prefijo_factura']."' ".$sel.">".$dtl['prefijo_factura']."</option>\n";
			}
			$html .= "				        </select>\n";
			$html .= "				      </td>\n";
			$html .= "			        <td>\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[factura_fiscal]\" size=\"25\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" value=\"".$request['factura_fiscal']."\">\n";
			$html .= "			        </td>\n";
			$html .= "			      </tr>\n";
			$html .= "			      <tr>\n";
			$html .= "				      <td class=\"label\" width=\"25%\">TIPO NOTA</td>\n";
			$html .= "				      <td colspan=\"2\">\n";
			$html .= "					      <select name=\"buscador[tipo_nota]\" class=\"select\">\n";
			
			foreach($tipos_notas as $key => $dtl)
			{
				$sel = ($request['tipo_nota'] == $key)? "selected": "";
				$html .= "						    <option value='".$key."' ".$sel.">".$dtl."</option>\n";
			}
			$html .= "					      </select>\n";
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
      
      if(!empty($notas))
      {
        $html .= "	<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"modulo_table_list_title\" >\n";
        $html .= "			<td width=\"12%\" >NOTA</td>\n";
        $html .= "			<td width=\"12%\" >FACTURA</td>\n";
        $html .= "			<td width=\"12%\" >F. NOTA</td>\n";
        $html .= "			<td width=\"26%\" >TERCERO</td>\n";
        $html .= "			<td width=\"12%\" >VALOR NOTA</td>\n";
        $html .= "			<td width=\"12%\" >T FACTURA</td>\n";
        $html .= "			<td width=\"12%\" >SALDO</td>\n";
        $html .= "			<td width=\"%\" >OP</td>\n";
        $html .= "		</tr>\n";
        
        $rpt  = new GetReports();
        foreach($notas as $k1 => $dtl)
        {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
          
          $dtl['tabla'] = $request['tipo_nota'];
          $dtl['usuario_id'] = $request['usuario_id'];
          $dtl['empresa_id'] = $request['empresa_id'];
          $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
          $html .= "			<td>".$dtl['prefijo']." ".$dtl['numero']."</td>\n";
          $html .= "			<td>".$dtl['prefijo_factura']." ".$dtl['factura_fiscal']."</td>\n";
          $html .= "			<td align=\"center\">".$dtl['fecha_registro']."</td>\n";
          $html .= "			<td >".$dtl['nombre_tercero']."</td>\n";
          $html .= "			<td align=\"right\"	>$".formatoValor($dtl['valor_nota'])."</td>\n";
          $html .= "			<td align=\"right\" >$".formatoValor($dtl['total_factura'])."</td>\n";
          $html .= "			<td align=\"right\" >$".formatoValor($dtl['saldo'])."</td>\n";
          $html .= "			<td align=\"center\" >\n";
     			$html .= $rpt->GetJavaReport('app','NotasFacturasContado','notascredito',$dtl,
  																	array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $fnc  = $rpt->GetJavaFunction();

          $html .= "			  <a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
          $html .= "			    <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
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
  }
?>