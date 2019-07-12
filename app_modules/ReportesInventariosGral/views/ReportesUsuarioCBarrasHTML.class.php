<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ReportesUsuarioCBarrasHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ReportesUsuarioCBarrasHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class ReportesUsuarioCBarrasHTML	
	{
    /**
    * Constructor de la clase
    */
    function ReportesUsuarioCBarrasHTML(){}
    /**
    * Funcion donde se crea el $html de las factuars
    *
    * @return string
    */
    
    /**
    *
    * @return string
    */
    function FormaReporte($action,$request,$lista,$conteo, $pagina,$dias_vence,$colores)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      
 			$html  = $ctl->LimpiarCampos();
 			$html .= $ctl->RollOverFilas();
 			$html .= $ctl->AcceptDate('/');
      $html .= ThemeAbrirTabla('REPORTE DE USUARIOS QUE <u>NO</u> USA EL CODIGO DE BARRAS COMO METODO DE BUSQUEDA DE PRODUCTOS');
      $html .= "<form name=\"productos\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "		      <table width=\"100%\">\n";
      $html .= "            <tr>\n";
      $html .= "              <td align=\"center\">\n";
      $html .= "                <fieldset class=\"fieldset\" style=\"width:98%\">\n";
      $html .= "                  <legend class=\"normal_10AN\">\n";
      $html .= "                    <img src=\"".GetThemePath()."/images/informacion.png\">NOTA\n";
      $html .= "                  </legend>\n";
      $html .= "                  <center>\n";
      $html .= "                    <label class=\"normal_10AN\">Imprimir Reporte De Aquellos Usuarios Que <u>NO</u> Usan Codigos de Barras</label>\n";
      $html .= "                  </center>\n";
      $html .= "                </fieldset><br>\n";
 			
      $html .= "              </td>\n";
      $html .= "            </tr>\n";
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
			$html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
			$html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
   		$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.productos)\">\n";
      $html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "		      </table>\n";
			//$html .= "	      </fieldset>\n";
			$html .= "	    </td>\n";
			$html .= "	  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
      
      if(!empty($lista))
      {
        $rpt  = new GetReports();
        $html .= $rpt->GetJavaReport('app','ReportesInventariosGral','usuarios_cod_barras',$request,
                                  array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $fnc  = $rpt->GetJavaFunction();
        $html .= "<center>\n";
        $html .= "	<a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
        $html .= "	  <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">IMPRIMIR REPORTE\n";
        $html .= "  </a>\n";
        $html .= "</center>\n";
        $html .= "<br>\n";
        
        $html .= "	<table align=\"center\" border=\"0\" width=\"60%\" class=\"modulo_table_list\">\n";
        $html .= "  </table>\n";
  			$html .= "  <br>\n";
        
        $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
        //print_r($request);
        if($request['fecha_inicio'] != "" && $request['fecha_final']!="")
        {
        $html .= "		<tr class=\"formulacion_table_list\" aling=\"center\">\n";
  			$html .= "			<td width=\"20%\" colspan=\"5\">Entre ".$request['fecha_inicio']." Y ".$request['fecha_final']."</td>\n";
        $html .= "		</tr>\n";
        }
        
       $html .= "		<tr class=\"formulacion_table_list\" >\n";
  			$html .= "			<td width=\"20%\">USUARIO</td>\n";
  			$html .= "			<td width=\"30%\">NOMBRE USUARIO</td>\n";
  			$html .= "			<td width=\"30%\">EMPRESA</td>\n";
          			  			
  			$html .= "		</tr>\n";
        
        foreach($lista as $k1 => $dtl)
        {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
         
          $html .= "		<tr ".$clase." onmouseout=mOut(this,\"".$bck."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
  				$html .= "			<td >".$dtl['usuario']."</td>\n";
  				$html .= "			<td >".$dtl['nombre']."</td>\n";
  				$html .= "			<td >".$dtl['razon_social']."</td>\n";
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