<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ReporteEstadosPacientesHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ReporteEstadosPacientesHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class ReporteEstadosPacientesHTML	
	{
    /**
    * Constructor de la clase
    */
    function ReporteEstadosPacientesHTML(){}
    /**
    * Funcion donde se crea el $html de las factuars
    *
    * @return string
    */
    function FormaBuscarProveedor($action,$request,$TiposBloqueo,$lista,$conteo, $pagina)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
          
 			$html  = $ctl->LimpiarCampos();
      $html .= ThemeAbrirTabla('BUSCAR PACIENTES');
      $html .= "<form name=\"facturas\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		      <table width=\"100%\">\n";
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\">SELECCIONE EL(los) TIPO(s) DE BLOQUEO(s)</td>\n";
			$html .= "			      </tr>\n";
      
      
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\">\n";
      $html .= "                      <table width=\"100%\" class=\"modulo_table_list\">";
      $i=0;
       
                    foreach ($TiposBloqueo as $k=>$valor)
                                    {
                                    if($i==0)
                                    $html .= "                          <tr>";
                                    $i++;
                                    $html .= "                          <td>";
                                          if($_REQUEST['TipoBloqueo'][$valor['tipo_bloqueo_id']]==$valor['tipo_bloqueo_id'])
                                              $checked=" checked ";
                                              else
                                              $checked = "";
                                    $html .= "				                  <input ".$checked." type=\"checkbox\" class=\"input-checkbox\" name=\"TipoBloqueo[".$valor['tipo_bloqueo_id']."]\" value=\"".$valor['tipo_bloqueo_id']."\">\n";
                              			$html .= "                          </td>";
                                    $html .= "                          <td><b>";
                                    $html .=  				        $request['TipoBloqueo'][$valor['tipo_bloqueo_id']]."".$valor['descripcion'];
                              			$html .= "                          </b></td>";
                                   //print_r($_REQUEST['TipoBloqueo']);
                                    if($i==3)
                                    {
                                    $html .= "                          </tr>";
                                    $i=0;
                                    }
      
			
                                    }
      $html .= "                        </table>";
      $html .= "			        </td>\n";      
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
      
      if(!empty($lista))
      { 
        $rpt  = new GetReports();
        $html .= $rpt->GetJavaReport('app','ReportesInventariosGral','reporte_estados_usuarios',$_REQUEST['TipoBloqueo'],
  																	array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $fnc  = $rpt->GetJavaFunction();

        $html .= "			  <center class=\"label\">IMPRIMIR: <a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
        $html .= "			    <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
        $html .= "			  </a></center>\n";
        $html .= "	<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td width=\"10%\" >TIPO ID</td>\n";
        $html .= "			<td width=\"15%\" >ID</td>\n";
        $html .= "			<td width=\"30%\" >NOMBRES</td>\n";
        $html .= "			<td width=\"30%\" >APELLIDOS</td>\n";
        $html .= "			<td width=\"20%\" >DIRECCION</td>\n";
        $html .= "			<td width=\"10%\" >TELEFONO</td>\n";
        $html .= "			<td width=\"10%\" >ESTADO</td>\n";
        $html .= "		</tr>\n";
        
       
        foreach($lista as $k1 => $dtl)
        {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
         
          $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
          $html .= "			<td >".$dtl['tipo_id_paciente']."</td>\n";
          $html .= "			<td >".$dtl['paciente_id']."</td>\n";
          $html .= "			<td >".$dtl['primer_nombre']." ".$dtl['segundo_nombre']."</td>\n";
          $html .= "			<td >".$dtl['primer_apellido']." ".$dtl['segundo_apellido']."</td>\n";
          $html .= "			<td >".$dtl['residencia_direccion']."</td>\n";
          $html .= "			<td >".$dtl['residencia_telefono']."</td>\n";
          $html .= "			<td >".$dtl['descripcion']."</td>\n";
          $html .= "		</tr>\n";
        }
       
        $html .= "		</table>\n";
        $pgn = AutoCarga::factory("ClaseHTML");
		    $html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
        //print_r($action);
				$html .= "		<br>\n";
       
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