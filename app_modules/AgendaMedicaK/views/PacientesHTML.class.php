<?php
	/**
	* $Id: PacientesHTML.class.php,v 1.9 2010/03/12 13:34:54 sandra Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.9 $  
	*
	* @autor Hugo F  Manrique
	*/
	class PacientesHTML
	{
		function PacientesHTML(){}
		/**
		* Funcion donde se crea la gui de los datos de la afiliacion
    *
    * @param array $tipos Arreglo de datos con la información de los tipos de afiliado
    * @param array $rangos Arreglo de datos con la información de los rangos
    *
    * @return string
		*/
		function FormaDatosAfiliado($tipos,$rangos, $UltimaCita)
	  { 
    
			$semanas = 0;
			$html .= "<table width=\"75%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"modulo_list_claro\">\n";
			$html .= "		<td class=\"formulacion_table_list\" width=\"20%\">TIPO AFILIADO</td>\n";
			$html .= "		<td class=\"label\" width=\"20%\">\n";
			if(sizeof($tipos) > 1)
			{
				$html .= "			<select name=\"tipoafiliado\" class=\"select\">\n";
				$html .= "				<option value=\"-1\">-- Seleccionar --</option>\n";
				foreach($tipos as $key => $valor)
				{
					$html .= "				<option value=\"".$valor['tipo_afiliado_id']."\" $sel>".$valor['tipo_afiliado_nombre']."</option>\n";
				}
				$html .= "			</select>\n";
			}
			else
			{
				$html .= "			".$tipos[0]['tipo_afiliado_nombre']."\n";
				$html .= "			<input type=\"hidden\" name=\"tipoafiliado\" value=\"".$tipos[0]['tipo_afiliado_id']."\">\n";
			}
			$html .= "		</td>\n";
			$html .= "		<td class=\"formulacion_table_list\">RANGO</td>\n";
			$html .= "		<td class=\"label\">\n";
			if(sizeof($rangos) > 1)
			{
				$html .= "			<select name=\"rango\" class=\"select\">\n";
				$html .= "				<option value=\"-1\">-- Selecc --</option>\n";
				foreach($rangos as $key => $valor)
				{
					$html .= "				<option value=\"".$valor['rango']."\" $sel>".$valor['rango']."</option>\n";
				}
				$html .= "			</select>\n";
			}
			else
			{
				$html .= "			".$rangos[0]['rango']."\n";
				$html .= "			<input type=\"hidden\" name=\"rango\" value=\"".$rangos[0]['rango']."\">\n";
			}

			$html .= "		</td>\n";
			$html .= "		<td class=\"formulacion_table_list\">SEMANAS COTIZADAS</td>\n";
			$html .= "		<td class=\"label\" width=\"7%\" class=\"normal_10AN\">\n";
			$html .= "			<input class=\"input-text\" type=\"text\" name=\"Semanas\" style=\"width:100%\" maxlength=\"8\" onkeypress=\"return acceptNum(event)\" value=\"".$semanas."\">\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			
			if($tipos[0]['eps_punto_atencion_nombre']){
				$html .= "<table width=\"75%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "	<tr class=\"modulo_list_claro\">\n";
				$html .= "		<td class=\"formulacion_table_list\" width=\"20%\">PUNTO ATENCION</td>\n";
				$html .= "		<td class=\"label\">\n";
				$html .= "			".$tipos[0]['eps_punto_atencion_nombre']."\n";
				$html .= "			<input type=\"hidden\" name=\"puntoafiliacion\" value=\"".$tipos[0]['eps_punto_atencion_nombre']."\">\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table>\n";
			}
      
      if($UltimaCita == true)
			{
        $html .= "<br>";
				$html .= "<table width=\"75%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "	<tr align=\"center\" class=\"formulacion_table_list\">\n";
        $html .= "		<td width=\"20%\" align=\"center\">Fecha Ultima Cita</td>\n";
        $html .= "		<td align=\"center\">Profesional</td>\n";
        $html .= "		<td align=\"center\">Cargo</td>\n";
        $html .= "	</tr>\n";
        
        
        foreach($UltimaCita as $key => $cita)
        {
        
          ($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro";
          $html .= "  <tr class=\"".$est."\">\n";
          $html .= "    <td align=\"center\">".$cita['cita']."</td>\n";
          $html .= "    <td >".$cita['nombre']."</td>\n";
          $html .= "    <td >".$cita['descripcion']."</td>\n";
          $html .= "  </tr>\n";
        }
        
        $html .= "</table>";
			}
			
			return $html;
	  }
    /**
    * Funcion donde se crea la gui del buscador y los resultados de la busqueda de las 
    * citas
    *
    * @param array $action Arreglo de datos con la información de los links
    *
    * @return String
    */
    function FormaImpresionCitas($action, $request,$tiposDocumentos,$listado,$conteo, $pagina)
    {
      $stl = "style=\"text-align:left;text-indent:11pt\"";
      $ctl = new ClaseUtil();
      
      $html .= $ctl->LimpiarCampos();
      $html .= $ctl->AcceptDate("/");
      $html .= ThemeAbrirTabla("IMPRIMIR CITAS");
      $html .= "<table width=\"60%\" align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <fieldset class=\"fieldset\">\n";
      $html .= "        <legend class=\"normal_10AN\">BUSCADOR DE CITAS</legend>\n";
      $html .= "        <form name=\"buscador\" action=\"".$action['buscador']."\" method=\"post\">\n";
      $html .= "          <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "		        <tr class=\"modulo_table_list_title\">\n";
			$html .= "			        <td ".$stl." width=\"25%\">TIPO DOCUMENTO: </td>\n";
			$html .= "			        <td width=\"25%\" align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				        <select name=\"tipo_id_paciente\" class=\"select\">\n";
			$html .= "				          <option value=\"-1\">--Seleccionar--</option>\n";
				
			foreach($tiposDocumentos as $key => $dtl)
			{
				($request['tipo_id_paciente'] == $dtl['tipo_id_paciente'])? $sel = "selected":$sel = "";
				$html .= "				          <option value=\"".$dtl['tipo_id_paciente']."\" ".$sel.">".$dtl['descripcion']."</option>\n";  
			}
			$html .= "				        </select>\n";
			$html .= "				      </td>\n";
			$html .= "			        <td ".$stl." width=\"25%\">DOCUMENTO: </td>\n";
			$html .= "			        <td width=\"25%\" align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"paciente_id\" value=\"".$request['paciente_id']."\" style=\"width:100%\" >\n";
			$html .= "			        </td>\n";
			$html .= "		        </tr>\n";
			$html .= "		        <tr class=\"modulo_table_list_title\" >\n";
			$html .= "			        <td style=\"text-align:left;text-indent:11pt\" >NOMBRES: </td>\n";
			$html .= "			        <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"nombres\" value=\"".$request['nombres']."\" style=\"width:100%\" >\n";
			$html .= "			        </td>\n";
			$html .= "			        <td style=\"text-align:left;text-indent:11pt\" >APELLIDOS: </td>\n";
			$html .= "			        <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"apellidos\" value=\"".$request['apellidos']."\" style=\"width:100%\">\n";
			$html .= "			        </td>\n";
			$html .= "		        </tr>\n";
      $html .= "			      <tr class=\"modulo_table_list_title\">\n";
			$html .= "				      <td ".$stl." width=\"25%\">FECHA CITA</td>\n";			
			$html .= "				      <td align=\"right\" class=\"modulo_list_claro\">\n";
			$html .= "					      <input type=\"text\" class=\"input-text\" name=\"fecha_cita\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_cita']."\">\n";
			$html .= "				      </td>\n";
			$html .= "				      <td align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">".ReturnOpenCalendario('buscador','fecha_cita','/')."</td>\n";
			$html .= "			      </tr>\n";
      $html .= "          </table>\n";
      $html .= "          <table width=\"60%\" align=\"center\">\n";
      $html .= "            <tr>\n";
      $html .= "              <td align=\"center\">\n";
      $html .= "                <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
      $html .= "              </td>\n";      
      $html .= "              <td align=\"center\">\n";
      $html .= "                <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onclick=\"LimpiarCampos(document.buscador)\">\n";
      $html .= "              </td>\n";
      $html .= "            </tr>\n";
      $html .= "          </table>\n";
      $html .= "        </form>\n";
      $html .= "      </fieldset>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table><br>\n";
      if(!empty($listado))
      {
      		$rpt = new GetReports();
		

        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td width=\"5%\" >CITA Nº</td>\n";
        $html .= "    <td width=\"10%\" >FECHA</td>\n";
        $html .= "    <td width=\"30%\">PACIENTE</td>\n";
        $html .= "    <td width=\"20%\">CENTRO ATENCIÓN</td>\n";
        $html .= "    <td width=\"20%\">PROFESIONAL</td>\n";
        $html .= "    <td>OPCIONES</td>\n";
        $html .= "  </tr>\n";
        
        $est = "modulo_list_claro";
        foreach($listado as $key => $dtl)
        {
          ($est == "modulo_list_claro")? $est = "modulo_list_oscuro":$est = "modulo_list_claro";
          
          $dtl['departamentoUbicacion'] = $dtl['departamentoubicacion'];
          $dtl['responsable'] = $dtl['Responsable'];
          $dtl['UsuarioId'] = $dtl['usuario_id'];
          
          $html .= "  <tr class=\"".$est."\">\n";
          $html .= "    <td >".$dtl['idcita']."</td>\n";
          $html .= "    <td align=\"center\">".$dtl['fecha_turno']." ".$dtl['hora']."</td>\n";
          $html .= "    <td >".$dtl['identificacion']." - ".$dtl['paciente']."</td>\n";
          $html .= "    <td >".$dtl['departamento']."</td>\n";
          $html .= "    <td >".$dtl['profesional']."</td>\n";
          $html .= "    <td >\n";
          $html .= $rpt->GetJavaReport('app','AgendaMedica','Recibo',$dtl,array());
          $html .= "      <a href=\"javascript:".$rpt->GetJavaFunction().";\" class=\"label_error\">\n";
          $html .= "        <img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">Imprimir PDF\n";
          $html .= "      </a>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "</table>\n";
        $chtml = AutoCarga::factory("ClaseHTML");
        $html .= "		".$chtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    		$html .= "		<br>\n";
      }
      else if($request['buscar'])
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">LA BUSQUEDA NO ARROJO NINGUN RESULTADO</label>\n";
        $html .= "</center>\n";
      }
      
      $html .= "<center>\n";
      $html .= "  <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "    <input type=\"submit\" name=\"volver\" value=\"Volver\" class=\"input-submit\">\n";
      $html .= "  </form>\n";
      $html .= "</center>\n";
      $html .= ThemeCerrarTabla();
      return $html;
    }
	}
?>