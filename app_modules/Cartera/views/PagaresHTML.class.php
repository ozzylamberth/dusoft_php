<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: PagaresHTML.class.php,v 1.1 2009/02/12 20:14:13 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: PagaresHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class PagaresHTML
	{
		/**
    * Contructor de clase
    */
    function PagaresHTML(){}
    /**
    *
    */
    function FormaListaPagares($action,$tipos,$request,$lista,$pagina,$conteo,$ErrMsg)
    {
      $ctl = AutoCarga::factory('ClaseUtil');
      $html .= $ctl->LimpiarCampos();
      $html .= $ctl->AcceptNum(false);
      $html .= $ctl->RollOverFilas();
      
      $html .= ThemeAbrirTabla('BUSCAR PAGARES');
			$html .= "<form name=\"formabuscar\" action=\"".$action['buscar']."\" method=\"post\">\n";
			$html .= "  <table border=\"0\" width=\"81%\" align=\"center\">\n";
			$html .= "    <tr>\n";
			$html .= "      <td>\n";
			$html .= "      	<fieldset class=\"fieldset\"><legend class=\"normal_11N\">CRITERIOS DE BUSQUEDA</legend>\n";
			$html .= "        	<table border=\"0\" width=\"95%\" align=\"center\">\n";
			$html .= "            <tr>\n";
			$html .= "             	<td class=\"normal_10AN\" width=\"18%\">TIPO DOCUMENTO: </td>\n";
			$html .= "              <td width=\"32%\">\n";
			$html .= "               	<select name=\"buscador[tipo_id_paciente]\" class=\"select\">\n";
			$html .= "                	<option value=\"\">-------SELECCIONE-------</option>";
      $sel = "";
      foreach($tipos as $key => $dtl)
      {
        ($dtl['tipo_id_paciente'] == $request['tipo_id_paciente'])? $sel = "selected": $sel = "";
   			$html .= "                	<option value=\"".$dtl['tipo_id_paciente']."\" ".$sel.">".$dtl['descripcion']."</option>\n";
      }

			$html .= "               	</select>\n";
			$html .= "              </td>\n";
			$html .= "              <td width=\"18%\" class=\"normal_10AN\">DOCUMENTO: </td>\n";
			$html .= "              <td>\n";
			$html .= "               	<input type=\"text\" class=\"input-text\" name=\"buscador[paciente_id]\" maxlength=\"32\" value=\"".$request['paciente_id']."\">\n";
			$html .= "              </td>\n";
			$html .= "            </tr>\n";
			$html .= "            <tr>\n";
			$html .= "             	<td class=\"normal_10AN\">NOMBRES:</td>\n";
			$html .= "              <td>\n";
			$html .= "                 <input type=\"text\" class=\"input-text\" name=\"buscador[nombres]\" style=\"width:94%\" maxlength=\"64\" value=\"".$request['nombres']."\">\n";
			$html .= "              </td>\n";
			$html .= "              <td class=\"normal_10AN\">APELLIDOS:</td>\n";
			$html .= "              <td>\n";
			$html .= "                	<input type=\"text\" class=\"input-text\" name=\"buscador[apellidos]\" style=\"width:94%\" maxlength=\"64\" value=\"".$request['apellidos']."\">\n";
			$html .= "              </td>\n";
			$html .= "            </tr>\n";
      $html .= "            <tr>\n";
			$html .= "        	    <td class=\"normal_10AN\">FECHA INICIO</td>\n";
      $html .= "              <td >\n";
      $html .= "                <input size=\"12\" type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\">\n";
      $html .= "                ".ReturnOpenCalendario('formabuscar','fecha_inicio','/',1)."\n";
      $html .= "              </td>\n";			
      $html .= "        	    <td class=\"normal_10AN\">FECHA FIN</td>\n";
      $html .= "              <td >\n";
      $html .= "                <input size=\"12\" type=\"text\" name=\"buscador[fecha_fin]\" id=\"fecha_fin\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_fin']."\" >\n";
      $html .= "                ".ReturnOpenCalendario('formabuscar','fecha_fin','/',1)."\n";
      $html .= "              </td>\n";
			$html .= "            <tr>\n";
			$html .= "          </table><br>\n";
      $html .= "          <table width=\"60%\" align=\"center\">\n";
			$html .= "            <tr align=\"center\">\n";
			$html .= "              <td>\n";
			$html .= "                <input class=\"input-submit\" type=\"submit\" name=\"buscador[buscar]\" value=\"Buscar\">\n";
			$html .= "              </td>\n";
			$html .= "              <td>\n";
			$html .= "                <input class=\"input-submit\" type=\"button\" onclick=\"LimpiarCampos(document.formabuscar)\" name=\"buscador[limpiar]\" value=\"Limpiar Campos\">\n";
			$html .= "              </td>\n";
			$html .= "            </tr>\n";
			$html .= "          </table>\n";
			$html .= "        </fieldset>\n";
			$html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
			$html .= "</form>\n";
      
      if(!empty($lista))
			{
				$rpt = new GetReports();
				$mst = $rpt->GetJavaReport('app','Cartera','pagares',$request,array('rpt_name'=>'pagares','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$fnc = $rpt->GetJavaFunction();
					
        $html .= "	".$mst."\n";
        $html .= " 	<center>\n";
        $html .= " 		<a href=\"javascript:".$fnc."\" class=\"label_error\">\n";
        $html .= "			<img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
        $html .= " 			<b>REPORTE DE PAGARES</b>\n";
        $html .= "		</a>\n";
        $html .= "	</center>\n";
				
				$html .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "  <tr height=\"21\" class=\"modulo_table_list_title\">\n";
				$html .= "	  <td width=\"7%\" >Nº DOC</td>\n";
				$html .= "		<td width=\"8%\" >FECHA</td>\n";
				$html .= "		<td width=\"8%\" >VENCIMIENTO</td>\n";
				$html .= "		<td width=\"9%\" >VALOR</td>\n";
				$html .= "		<td width=\"25%\">FORMA PAGO</td>\n";
				$html .= "		<td width=\"%\" colspan=\"2\">CLIENTE</td>\n";
				$html .= "	</tr>\n";

				$bck = "#CCCCCC";
				$est = 'modulo_list_oscuro'; 
				foreach($lista as $key => $dtl )
				{
					($est == "modulo_list_oscuro")? $est = 'modulo_list_claro': $est = 'modulo_list_oscuro';
					($bck == "#DDDDDD")? $bck = "#CCCCCC": $bck = "#DDDDDD";
          
					$html .= "	<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "	  <td align=\"left\"  >".$dtl['prefijo']." ".$dtl['numero']."</td>\n";
					$html .= "		<td align=\"center\">".$dtl['fecha_registro']."</td>\n";
					$html .= "		<td align=\"center\">".$dtl['vencimiento']."</td>\n";
					$html .= "		<td align=\"right\" >$".FormatoValor($dtl['valor'])."</td>\n";
					$html .= "		<td >".$dtl['formapago']."</td>\n";
					$html .= "		<td width=\"15%\">".$dtl['tipo_id_paciente']." ".$dtl['paciente_id']."</td>\n";
					$html .= "		<td align=\"justify\">".$dtl['primer_apellido']." ".$dtl['segundo_apellido']." ".$dtl['primer_nombre']." ".$dtl['segundo_nombre']."</td>\n";
					$html .= "	</tr>\n";
				}
				$html .= "</table>\n";
        $clh = AutoCarga::factory('ClaseHTML');
        $html .= "		".$clh->ObtenerPaginado($conteo,$pagina,$action['paginador']);
        $html .= "		<br>\n";
			}
			else
			{
				$html .= "<center>\n";
        if($ErrMsg)
          $html .= "  <b class=\"label_error\">".$ErrMsg."</b>\n";
        else
          $html .= "  <b class=\"label_error\">NO SE ENCONTRARON PAGARES PARA MOSTRAR</b>\n";
        $html .= "</center>\n";
			}
      
      $html .= "<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
			$html .= "    <tr align=\"center\">\n";
			$html .= "      <td>\n";
			$html .= "        <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\">\n";
			$html .= "      </td>\n";
			$html .= "    </tr>\n";
			$html .= "  </table>\n";
			$html .= "</form>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
	}
?>