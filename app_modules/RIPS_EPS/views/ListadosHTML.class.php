<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ListadosHTML.class.php,v 1.3 2009/02/16 21:15:09 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  */
  /**
  * Clase Vista: ReintegrosHTML
  * Clase en la que se crean las formas para el modulo de RIPS de EPS
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Giraldo
  */
  class ListadosHTML
  {
  	/**
  	* Constructosr de la clase
  	*/
    function ListadosHTML(){}
  	/**
  	* Funcion donde se crea la forma que muestra el buscador y la lista de los afiliados
  	*
  	* @return String $html
  	*/
    function FormaListadoRadicaciones($action,$tiposdocumentos,$request,$lista,$pagina,$conteo,$msgError)
    {
  		$ctl = AutoCarga::factory("ClaseUtil"); 
  		$html  = $ctl->AcceptNum();
  		$html .= $ctl->AcceptDate("/");
  		$html .= $ctl->LimpiarCampos();
  		$html .= $ctl->IsNumeric();
  		$html .= $ctl->RollOverFilas();
  		$html .= $ctl->TrimScript();
  		
  		$html .= ThemeAbrirTabla('GENERACION DE RIPS (ASEGURADORA)');		
  		$html .= "	<center>\n";
  		$html .= "		<fieldset style=\"width:80%\" class=\"fieldset\"><legend class=\"label\">SELECCION DE ARCHIVOS RIPS A GENERAR</legend>\n";
  		$html .= "    <form name=\"formabuscar\" id=\"formabuscar\" action=\"".$action['buscar']."\" method=\"post\">\n";
  		$html .= "    	<table border=\"0\" width=\"80%\" align=\"center\">\n";
  		$html .= "        <tr>\n";
  		$html .= "         	<td colspan = '2' align=\"center\" >&nbsp</td>\n";
  		$html .= "        </tr>\n";
  		$html .= "        <tr>\n";		
  		$html .= "        	<td class=\"normal_10AN\">FECHA INICIAL DE RADICACION</td>\n";
  		$html .= "          <td >\n";
  		$html .= "            <input size=\"12\" type=\"text\" name=\"fecha_inicial\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicial']."\">\n";
  		$html .= "            ".ReturnOpenCalendario('formabuscar','fecha_inicial','/')."\n";
  		$html .= "          </td>\n";
  		$html .= "        </tr>\n";
  		$html .= "        <tr>\n";
  		$html .= "        	<td class=\"normal_10AN\">FECHA FINAL DE RADICACION</td>\n";
  		$html .= "          <td >\n";
  		$html .= "            <input size=\"12\" type=\"text\" name=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_final']."\" >\n";
  		$html .= "            ".ReturnOpenCalendario('formabuscar','fecha_final','/')."\n";
  		$html .= "          </td>\n";		
  		$html .= "        </tr>\n";
  		$html .= "        <tr>\n";
  		$html .= "         	<td colspan = '2' align=\"center\" >&nbsp</td>\n";
  		$html .= "        </tr>\n";
  		$html .= "        <tr>\n";
  		$html .= "         	<td colspan = '2' align=\"center\" >\n";
  		$html .= "		      <fieldset style=\"width:100%\" class=\"fieldset\"><legend class=\"label\">SELECCIONE LOS ESTADOS QUE QUIERE FILTRAR</legend>\n";
  		$html .= "    	      <table border=\"0\" width=\"100%\" align=\"center\">\n";
  		$html .= "              <tr>\n";
  		$html .= "           	  <td colspan = '2' align=\"center\" >&nbsp</td>\n";
  		$html .= "              </tr>\n";		
  		
  		foreach($tiposdocumentos as $key => $ids)
  		{	
  			$clave = array_search($ids['cxp_estado'], $request['cxp_estado']);

  			$check = '';
  			if( is_numeric($clave))
  				$check = 'checked';

  			$html .= "            <tr>\n";
  			$html .= "           	 <td class=\"normal_10AN\" width=\"30\" align=\"left\" ><input type=\"checkbox\" name=\"cxp_estado[]\" value=\"".$ids['cxp_estado']."\" $check ></td>\n";
  			$html .= "           	 <td class=\"normal_10AN\" align=\"left\" >".$ids['cxp_estado_descripcion']."</td>\n";
  			$html .= "            </tr>\n";		
  		}	
  		
  		$html .= "            <tr>\n";
  		$html .= "           	 <td colspan = '2' align=\"center\" >&nbsp</td>\n";
  		$html .= "            </tr>\n";	
  		$html .= "    	    </table>\n";
  		$html .= "	    	</fieldset>\n";
  		$html .= "          </td>\n";
  		$html .= "        </tr>\n";

      $slt= "";
  		if($request['fac_sin_rips'])
  			$slt= "checked";

  		$html .= "        <tr>\n";
  		$html .= "          <td colspan = '2' align=\"center\" >&nbsp</td>\n";
  		$html .= "        </tr>\n";	
  		$html .= "        <tr>\n";
  		$html .= "          <td colspan = '2' class=\"normal_10AN\" width=\"30\" align=\"left\" ><input type=\"checkbox\" name=\"fac_sin_rips\" value=\"OK\" $slt >&nbsp;&nbsp;&nbsp;&nbsp;FILTAR SOLO LAS FACTURAS QUE NO TIENEN RIPS DE LA IPS.</td>\n";
  		$html .= "        </tr>\n";	
  		$html .= "        <tr>\n";
  		$html .= "         	<td colspan = '2' align=\"center\" >&nbsp</td>\n";
  		$html .= "        </tr>\n";
  		$html .= "        <tr>\n";
  		$html .= "         	<td colspan = '2' align=\"center\" >\n";
  		$html .= "          	<table width=\"70%\">\n";
  		$html .= "             	  <tr align=\"center\">\n";
  		$html .= "               	<td >\n";
  		$html .= "                 	  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"Buscar\">\n";
  		$html .= "                  </td>\n";
  		
  		if(!empty($lista))
  		{  
  			$html .= "               	<td >\n";
  			$html .= "                 	  <input class=\"input-submit\" type=\"submit\" name=\"crear\" value=\"Crear\">\n";
  			$html .= "                  </td>\n";
  		}	
  		
  		$html .= "                  <td>\n";
  		$html .= "                 	  <input class=\"input-submit\" type=\"button\" onclick=\"LimpiarCampos(document.formabuscar)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
  		$html .= "                  </td>\n";
  		$html .= "            	  </tr>\n";
  		$html .= "           	</table>\n";
  		$html .= "          </td>\n";
  		$html .= "        </tr>\n";				
  		$html .= "    	</table>\n";
  		$html .= "      <div class=\"label_error\">".$msgError."</div>\n";
  		$html .= "		  </form>\n";
  		$html .= "		</fieldset>\n";
  		$html .= "	</center><br>\n";		
      
  		if(!empty($lista))
  		{        
  			$pghtml = AutoCarga::factory("ClaseHTML");

  			$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
  			$html .= "	  <tr class=\"formulacion_table_list\">\n";
  			$html .= "			<td width=\"9%\">Nº RADIC.</td>\n";
  			$html .= "			<td width=\"9%\">F. RADIC.</td>\n";
  			$html .= "			<td width=\"9%\">DOCUMENTO</td>\n";
  			$html .= "		    <td width=\"%\" colspan=\"2\">TERCERO</td>\n";
  			$html .= "			<td width=\"5%\">ESTADO</td>\n";
  			$html .= "			<td width=\"5%\">ID. RIPS</td>\n";
  			$html .= "		</tr>\n";

  			$est = "modulo_list_oscuro";
  			$bck = "#CCCCCC";
  			foreach($lista as $key => $dtl)
  			{
  				($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
  				($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
  	  
  				$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
  				$html .= "		  <td >".$dtl['cxp_radicacion_id']."</td>\n";
  				$html .= "		  <td align=\"center\">".$dtl['fecha_radicacion']."</td>\n";
  				$html .= "		  <td align=\"center\">".$dtl['prefijo']."-".$dtl['numero']."</td>\n";
  				$html .= "		  <td >".$dtl['tipo_id_tercero']." ".$dtl['tercero_id']."&nbsp;</td>\n";
  				$html .= "		  <td >".$dtl['nombre_tercero']."&nbsp;</td>\n";
  				$html .= "		  <td align=\"center\">".$dtl['cxp_estado']."</td>\n";
  				$html .= "		  <td align=\"center\">&nbsp;".$dtl['rips_control_id']."</td>\n";
  				$html .= "		</tr>\n";
  			}
  			$html .= "		</table>\n";
  	
  			$html .= "		".$pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
  			$html .= "		<br>\n";
  		}
  		else if($request['buscar'])
  		{
  			$html .= "		<center>\n";
  			$html .= "		  <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
  			$html .= "		</center>\n";
  		}
  		$html .= "<form name=\"form_volver\" action=\"".$action['volver']."\" method=\"post\">\n";
  		$html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
  		$html .= "	  <tr>\n";
  		$html .= "		  <td align=\"center\"><br>\n";
  		$html .= "				<input class=\"input-submit\" type=\"submit\"  name=\"volver\" value=\"Volver\">\n";
  		$html .= "		  </td>\n";
  		$html .= "	  </tr>\n";
  		$html .= "  </table>\n";
  		$html .= "</form>\n";
  		$html .= ThemeCerrarTabla();	
  		
  		return $html;
    }
  	/**
  	* Funcion donde se crea la forma que muestra el buscador y la lista de los afiliados
  	*
  	* @return String $html
  	*/
    function FormaDeArchivosCreados($action,$tiposdocumentos,$request,$msgError,$id)
    {
  		$ctl = AutoCarga::factory("ClaseUtil"); 
  		
  		$html  = $ctl->AcceptNum();
  		$html .= $ctl->AcceptDate("/");
  		$html .= $ctl->LimpiarCampos();
  		$html .= $ctl->IsNumeric();
  		$html .= $ctl->RollOverFilas();
  		$html .= $ctl->TrimScript();
 
  		$html .= ThemeAbrirTabla('GENERACION DE RIPS (ASEGURADORA)');		
  		$html .= "	<center>\n";
  		$html .= "		<fieldset style=\"width:80%\" class=\"fieldset\"><legend class=\"label\">SELECCION DE ARCHIVOS RIPS A GENERAR</legend>\n";
  		$html .= "    <form name=\"formabuscar\" id=\"formabuscar\" action=\"".$action['buscar']."\" method=\"post\">\n";
  		$html .= "    	<table border=\"0\" width=\"80%\" align=\"center\">\n";
  		$html .= "        <tr>\n";
  		$html .= "         	<td colspan = '2' align=\"center\" >&nbsp</td>\n";
  		$html .= "        </tr>\n";
  		$html .= "        <tr>\n";		
  		$html .= "        	<td class=\"normal_10AN\">FECHA INICIAL DE RADICACION</td>\n";
  		$html .= "          <td >\n";
  		$html .= "            <input size=\"12\" type=\"text\" name=\"fecha_inicial\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicial']."\">\n";
  		$html .= "            ".ReturnOpenCalendario('formabuscar','fecha_inicial','/')."\n";
  		$html .= "          </td>\n";
  		$html .= "        </tr>\n";
  		$html .= "        <tr>\n";
  		$html .= "        	<td class=\"normal_10AN\">FECHA FINAL DE RADICACION</td>\n";
  		$html .= "          <td >\n";
  		$html .= "            <input size=\"12\" type=\"text\" name=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_final']."\" >\n";
  		$html .= "            ".ReturnOpenCalendario('formabuscar','fecha_final','/')."\n";
  		$html .= "          </td>\n";		
  		$html .= "        </tr>\n";
  		$html .= "        <tr>\n";
  		$html .= "         	<td colspan = '2' align=\"center\" >&nbsp</td>\n";
  		$html .= "        </tr>\n";
  		$html .= "        <tr>\n";
  		$html .= "         	<td colspan = '2' align=\"center\" >\n";
  		$html .= "		      <fieldset style=\"width:100%\" class=\"fieldset\"><legend class=\"label\">SELECCIONE LOS ESTADOS QUE QUIERE FILTRAR</legend>\n";
  		$html .= "    	      <table border=\"0\" width=\"100%\" align=\"center\">\n";
  		$html .= "              <tr>\n";
  		$html .= "           	  <td colspan = '2' align=\"center\" >&nbsp</td>\n";
  		$html .= "              </tr>\n";		
  		
  		foreach($tiposdocumentos as $key => $ids)
  		{	
  			$clave = array_search($ids['cxp_estado'], $request['cxp_estado']);

  			$check = '';
  			if( is_numeric($clave)   )
  				$check = 'checked';

  			$html .= "            <tr>\n";
  			$html .= "           	 <td class=\"normal_10AN\" width=\"30\" align=\"left\" ><input type=\"checkbox\" name=\"cxp_estado[]\" value=\"".$ids['cxp_estado']."\" $check ></td>\n";
  			$html .= "           	 <td class=\"normal_10AN\" align=\"left\" >".$ids['cxp_estado_descripcion']."</td>\n";
  			$html .= "            </tr>\n";		
  		}	
  		
  		$html .= "            <tr>\n";
  		$html .= "           	 <td colspan = '2' align=\"center\" >&nbsp</td>\n";
  		$html .= "            </tr>\n";	
  		$html .= "    	    </table>\n";
  		$html .= "	    	</fieldset>\n";
  		$html .= "          </td>\n";
  		$html .= "        </tr>\n";

  		$slt= "";
  		if($request['fac_sin_rips'])
  			$slt= "checked";

  		$html .= "        <tr>\n";
  		$html .= "          <td colspan = '2' align=\"center\" >&nbsp</td>\n";
  		$html .= "        </tr>\n";	
  		$html .= "        <tr>\n";
  		$html .= "          <td colspan = '2' class=\"normal_10AN\" width=\"30\" align=\"left\" ><input type=\"checkbox\" name=\"fac_sin_rips\" value=\"OK\" $slt >&nbsp;&nbsp;&nbsp;&nbsp;FILTAR SOLO LAS FACTURAS QUE NO TIENEN RIPS DE LA IPS.</td>\n";
  		$html .= "        </tr>\n";	
  		$html .= "        <tr>\n";
  		$html .= "         	<td colspan = '2' align=\"center\" >&nbsp</td>\n";
  		$html .= "        </tr>\n";
  		$html .= "        <tr>\n";
  		$html .= "         	<td colspan = '2' align=\"center\" >\n";
  		$html .= "          	<table width=\"70%\">\n";
  		$html .= "             	  <tr align=\"center\">\n";
  		$html .= "               	<td >\n";
  		$html .= "                 	  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"Buscar\">\n";
  		$html .= "                  </td>\n";
  		
  		if(!empty($lista))
  		{  
  			$html .= "               	<td >\n";
  			$html .= "                 	  <input class=\"input-submit\" type=\"submit\" name=\"crear\" value=\"Crear\">\n";
  			$html .= "                  </td>\n";
  		}	
  		
  		$html .= "                  <td>\n";
  		$html .= "                 	  <input class=\"input-submit\" type=\"button\" onclick=\"LimpiarCampos(document.formabuscar)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
  		$html .= "                  </td>\n";
  		$html .= "            	  </tr>\n";
  		$html .= "           	</table>\n";
  		$html .= "          </td>\n";
  		$html .= "        </tr>\n";				
  		$html .= "    	</table>\n";
  		$html .= "      <div class=\"label_error\">".$msgError."</div>\n";
  		$html .= "		  </form>\n";
  		$html .= "		</fieldset>\n";
  		$html .= "	</center><br>\n";

      $urlDwn = "classes/zipArchive/zipArchiveDownload.php?id=".$id;      
  		$html .= "	<center>\n";
  		$html .= "	  <label class=\"normal_10AN\">\n";
      $html .= "      ARCHIVOS DE RIPS CREADOS<br>";
      $html .= "      <a href=\"".$urlDwn."\" class=\"label_error\" >\n";
      $html .= "        <img src=\"".GetThemePath()."/images/abajo.png\" border='0'>DESCARGAR ARCHIVO\n";
			$html .= "      </a>\n";
      $html .= "    </label>\n";
  		$html .= "	</center>\n";
  		$html .= "<form name=\"form_volver\" action=\"".$action['volver']."\" method=\"post\">\n";
  		$html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
  		$html .= "	  <tr>\n";
  		$html .= "		  <td align=\"center\"><br>\n";
  		$html .= "				<input class=\"input-submit\" type=\"submit\"  name=\"volver\" value=\"Volver\">\n";
  		$html .= "		  </td>\n";
  		$html .= "	  </tr>\n";
  		$html .= "  </table>\n";
  		$html .= "</form>\n";
  		$html .= ThemeCerrarTabla();	
  		
  		return $html;
    }	
  }  
?>