<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ReportePendientesDespacharHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ReportePendientesDespacharHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class ReportePendientesDespacharHTML	
	{
    /**
    * Constructor de la clase
    */
    function ReportePendientesDespacharHTML(){}
    /**
    * Funcion donde se crea el $html de las factuars
    *
    * @return string
    */
    function Forma($action,$request,$Farmacias,$Prefijos,$lista,$conteo, $pagina)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      
     // print_r($_REQUEST);
      
      $SelectFarmacias  = "<select name=\"farmacia\" class=\"select\">";
      $SelectFarmacias .= "<option value=\"\">Todas</option>";
          foreach($Farmacias as $k=>$valor)
          {
                  if($_REQUEST['farmacia']==$valor['empresa_id'])
                  $selected = " selected ";
                  else
                      $selected = "";
          $SelectFarmacias .= "<option ".$selected." value=\"".$valor['empresa_id']."\">".$valor['razon_social']."</option>";
          }
      $SelectFarmacias .= "</select>";
 			
      $SelectPrefijos  = "<select name=\"prefijo\" class=\"select\">";
      $SelectPrefijos .= "<option value=\"\">Todos</option>";
          foreach($Prefijos as $k=>$v)
          {
          
                  if($_REQUEST['prefijo']==$v['prefijo'])
                  $selected = " selected ";
                  else
                      $selected = "";
          
          
          $SelectPrefijos .= "<option ".$selected." value=\"".$v['prefijo']."\">".$v['prefijo']."</option>";
          }
      $SelectPrefijos .= "</select>";
      
      $html  = $ctl->acceptNum();
      $html .= ThemeAbrirTabla('REPORTE DE PRODUCTOS PENDIENTES POR DESPACHAR A LAS FARMACIAS');
      $html .= "<form name=\"facturas\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		      <table width=\"100%\">\n";
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\" align=\"center\" colspan=\"2\"><u>PARAMETROS DE BUSQUEDA</u></td>\n";
			$html .= "			      </tr>\n";
      
      
      
      
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\">\n";
      $html .= "               SELECCIONE LA FARMACIA";
      $html .= "			        </td>\n";      
      $html .= "				      <td class=\"label\">\n";
      $html .= "               ".$SelectFarmacias;
      $html .= "			        </td>\n";      
      $html .= "			      </tr>\n";
      
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\">\n";
      $html .= "               DOCUMENTO -  PREFIJO";
      $html .= "			        </td>\n"; 
      $html .= "				      <td class=\"label\">\n";
      $html .= "               ".$SelectPrefijos;
      $html .= "			        # Doc.<input type=\"text\" name=\"numero\" class=\"input-text\" onkeypress=\"return acceptNum(event)\" value=\"".$_REQUEST['numero']."\"></td>\n";       
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
        $html .= $rpt->GetJavaReport('app','ReportesInventariosGral','reporte_pendientes_despacho',$_REQUEST,
  																	array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $fnc  = $rpt->GetJavaFunction();

        $html .= "			  <center class=\"label\">IMPRIMIR: <a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
        $html .= "			    <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
        $html .= "			  </a></center>\n";
        $html .= "	<table align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td width=\"10%\" >FARMACIA</td>\n";
        $html .= "			<td width=\"10%\" >DOC. PREFIJO</td>\n";
        $html .= "			<td width=\"10%\" >DOC. NUMERO</td>\n";
        $html .= "			<td width=\"40%\" >PRODUCTO</td>\n";
        $html .= "			<td width=\"15%\" >CANTIDAD SOLICITADA</td>\n";
        $html .= "			<td width=\"15%\" >CANTIDAD PENDIENTE</td>\n";
        $html .= "		</tr>\n";
        
       
        foreach($lista as $k1 => $dtl)
        {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
         
          $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
          $html .= "			<td >".$dtl['razon_social']."</td>\n";
          $html .= "			<td >".$dtl['prefijo']."</td>\n";
          $html .= "			<td >".$dtl['numero']."</td>\n";
          $html .= "			<td >".$dtl['producto']."</td>\n";
          $html .= "			<td >".$dtl['cantidad_solicitad']."</td>\n";
          $html .= "			<td >".$dtl['cantidad_pendiente']."</td>\n";
          $html .= "		</tr>\n";
        }
        $html .= "		</table>\n";
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