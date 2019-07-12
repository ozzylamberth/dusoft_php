<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ReportesLogAuditoriaHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ReportesLogAuditoriaHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class ReportesLogAuditoriaHTML	
	{
    /**
    * Constructor de la clase
    */
    function ReportesLogAuditoriaHTML(){}
    /**
    *
    * @return string
    */
    function Forma($action,$request,$lista,$conteo, $pagina)
    {
		$ctl = AutoCarga::factory("ClaseUtil");

		$html  = $ctl->LimpiarCampos();
		$html .= $ctl->RollOverFilas();
		$html .= $ctl->AcceptDate('/');
		$html .= ThemeAbrirTabla('LOG DE AUDITORIA/SOBRE TABLAS PARAMETRIZADAS');
		$html .= "<form name=\"productos\" action=\"".$action['buscar']."\" method=\"post\">\n";
		$html .= "  <table width=\"60%\" align=\"center\">\n";
		$html .= "    <tr>\n";
		$html .= "      <td>\n";
		$html .= "	      <fieldset class=\"fieldset\">\n";
		$html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
		$html .= "		      <table width=\"100%\">\n";
		$html .= "            <tr>\n";
		$html .= "              <td class=\"normal_10AN\">FECHA INICIAL</td>\n";
		$html .= "              <td>\n";
		$html .= "                <input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\">\n";
		$html .= "              </td>\n";
		$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_inicio','/',1)."</td>\n";
		$html .= "            </tr>\n";

		$html .= "            <tr>\n";
		$html .= "              <td class=\"normal_10AN\">FECHA FINAL</td>\n";
		$html .= "              <td>\n";
		$html .= "                <input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_final']."\">\n";
		$html .= "              </td>\n";
		$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_final','/',1)."</td>\n";
		$html .= "            </tr>\n";
		$html .= "			      <tr>\n";

		$html .= "            <tr>\n";
		$html .= "              <td class=\"normal_10AN\">TABLA LOG</td>\n";
		$html .= "              <td colspan=\"2\">\n";
		$html .= "                <input type=\"text\" name=\"buscador[nombre_tabla]\" id=\"nombre_tabla\" class=\"input-text\" value=\"".$request['nombre_tabla']."\" style=\"width:100%\">\n";
		$html .= "              </td>\n";
		$html .= "            </tr>\n";
		$html .= "			      <tr>\n";
		
		$html .= "			      <tr>\n";
		$html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
		$html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
		$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.productos)\">\n";
		$html .= "				      </td>\n";
		$html .= "			      </tr>\n";
		$html .= "		      </table>\n";
		$html .= "	      </fieldset>\n";
		$html .= "	    </td>\n";
		$html .= "	  </tr>\n";
		$html .= "	</table>\n";
		$html .= "</form>\n";
      
      if(!empty($lista))
      {
        $rpt  = new GetReports();
        $html .= $rpt->GetJavaReport('app','ReportesInventariosGral','reporte_general_auditoria',$request,
                                  array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $fnc  = $rpt->GetJavaFunction();
        $html .= "<center>\n";
        $html .= "	<a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
        $html .= "	  <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">IMPRIMIR REPORTE BUSQUEDA\n";
        $html .= "  </a>\n";
        $html .= "</center>\n";
        $html .= "<br>\n";
        
        $html .= "	<table align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
  			$html .= "			<td width=\"10%\">ACCION REALIZADA</td>\n";
  			$html .= "			<td width=\"30%\">NOVEDAD INDICADOR</td>\n";
  			$html .= "			<td width=\"15%\">NOMBRE DE LA TABLA</td>\n";
  			$html .= "			<td width=\"15%\">FECHA DE REGISTRO</td>\n";
  			$html .= "			<td width=\"5%\" >USUARIO</td>\n";
        $html .= "			<td width=\"5%\" >IMPR/DET</td>\n";
  			$html .= "		</tr>\n";

        foreach($lista as $k1 => $dtl)
        {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";

          $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
  				$html .= "			<td >".$dtl['descripcion']."</td>\n";
  				$html .= "			<td >".$dtl['indicador']."</td>\n";
  				$html .= "			<td >".$dtl['table_name']."</td>\n";
  				$html .= "			<td >".$dtl['fecha_registro']."</td>\n";
  				$html .= "			<td >".$dtl['nombre']."</td>\n";
          $html .= "			<td >";
          
          $html .= $rpt->GetJavaReport('app','ReportesInventariosGral','reporte_detalle_auditoria',$dtl,
                                  array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $fnc  = $rpt->GetJavaFunction();
        $html .= "<center>\n";
        $html .= "	<a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
        $html .= "	  <image title=\"Imprimir Detalle De La Auditoria\" src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
        $html .= "  </a>\n";
        $html .= "</center>\n";
          
          $html .=  "     </td>\n";
  			  $html .= "		</tr>\n";
        }
        $html .= "		</table>\n";
				$html .= "		<br>\n";
        $pgn = AutoCarga::factory("ClaseHTML");
				$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
      else if(!empty($request))
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
        $html .= "</center>\n";
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