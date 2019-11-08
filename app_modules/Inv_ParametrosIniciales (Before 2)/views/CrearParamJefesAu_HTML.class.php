<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */

  /**
  * Clase Vista: CrearParamJefesAu_HTML
  * Clase Contiene Metodos para el Ingreso de Parametros de Bodegas Virtuales
  *
  * @package IPSOFT-SIIS
  * @version $Revision:
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
class CrearParamJefesAu_HTML
{
		/**
		* Constructor de la clase
		*/
		function CrearParamJefesAu_HTML(){}
    
  /**
     * Funcion donde se crea la forma para mostrar los pacientes y seleccionar al que se le va hacer el registro
     * 
     * @param array $action vector que contiene los link de la aplicacion
     * @param array $datos vector que contiene los datos
     * @return string $html retorna la cadena con el codigo html de la pagina
     */ 
  function formaJefesAuto($action,$conteo,$pagina,$datos)
  {
    $html  = ThemeAbrirTabla('PARAMETRIZACION DE JEFE DE BOEGA-CONTROL INTERNO');
    
    $html .= "<form name=\"formBodegasVirtuales\" id=\"formBodegasVirtuales\" method=\"post\" action=\"\">\n";
    $html .= " <table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td  align=\"center\">DOC TMP\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\">TIPO DOC\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\">JEFE DE BODEGA\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\">JEFE DE CONTROL INTERNO\n";
    $html .= "    </td>\n";
    $html .= "  <div class=\"label_error\" id=\"jefebya\"></div>"; 
    //$html .= "<pre>".print_r($datos,true)."</pre>";
    $html .= "  </tr>\n";
    $empresa_id = SessionGetVar("empresa_id");
    $mdl = AutoCarga::factory("ConsultasParamJefesAuto","","app","Inv_ParametrosIniciales");
    $pghtml = AutoCarga::factory('ClaseHTML');
    $usuarios_parametrzados=$mdl->BuscarparamUsuarios();
    //$html .= "<pre>".print_r($datos,true)."</pre>";
    foreach($datos as $key => $valor)
		{ 
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">".$valor['doc_tmp_id']."\n";
      $html .= "    </td>\n";
      
      $html .= "    <td align=\"center\">E013\n";
      $html .= "    </td>\n";
      //$html .= "<pre>".print_r($usuarios_parametrzados,true)."</pre>";
      $jefe=$mdl->BuscarparDoc_Tmp($empresa_id,$valor['doc_tmp_id']);
      
      $contar=count($jefe);
      $j=0;
      $m=0;
     do{
// $html .= "<pre>".print_r($jefe[$j],true)."</pre>";
        if($jefe[$j]['sw_jefebodega']=="")
        $jefe[$j]['sw_jefebodega']=0;
        
        if($jefe[$j]['sw_jefecontroli']=="")
        $jefe[$j]['sw_jefecontroli']=0;
       if(UserGetUID()==$usuarios_parametrzados[$m]['usuario_id'])
       {
          if($jefe[$j]['sw_jefebodega']==1)
          {
            //GuardarJefe($doc_tmp_id,$sw_jefebodega,$sw_jefecontroli,$empresa_id)
            $html .=  "<td align=\"center\"><a href=\"#\" onclick=\"xajax_GuardarJefe('".$valor['doc_tmp_id']."','0','".$jefe[$j]['sw_jefecontroli']."','".$empresa_id."')\">\n";
            $html .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
          {
            $html .=  "<td align=\"center\"><a href=\"#\" onclick=\"xajax_GuardarJefe('".$valor['doc_tmp_id']."','1','".$jefe[$j]['sw_jefecontroli']."','".$empresa_id."')\">\n";
            $html .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
          }
          if($jefe[$j]['sw_jefecontroli']==1)
          {
            $html .=  "<td align=\"center\"><a href=\"#\" onclick=\"xajax_GuardarJefe('".$valor['doc_tmp_id']."','".$jefe[$j]['sw_jefebodega']."','0','".$empresa_id."')\">\n";
            $html .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
          {
            $html .=  "<td align=\"center\"><a href=\"#\" onclick=\"xajax_GuardarJefe('".$valor['doc_tmp_id']."','".$jefe[$j]['sw_jefebodega']."','1','".$empresa_id."')\">\n";
            $html .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
          }
       }
       else
        {
          if($jefe[$j]['sw_jefebodega']==1 and $jefe[$j]['sw_jefecontroli']==1)
          {
            $html .=  "<td align=\"center\">\n";
            $html .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
          {
            $html .=  "<td align=\"center\">\n";
            $html .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
          }
          if($jefe[$j]['sw_jefecontroli']==1)
          {
            $html .=  "<td align=\"center\">\n";
            $html .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
          {
            $html .=  "<td align=\"center\">\n";
            $html .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
          }
           
        }
         $j++;
         }
          while($j<$contar);
      
      //$html .="<pre>".print_r($Bloqueo_Productos,true)."</pre>";      
            
    /*  if($valor['sw_virtual']==1)
      {
        $html .= "<td align=\"center\">
                   <a href=\"#\" onclick=\"xajax_GuardarBodegaVirtual('".$valor['bodega']."','".$valor['departamento']."','0')\">\n";
        $html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
      }
      else
      {
        $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_GuardarBodegaVirtual('".$valor['bodega']."','".$valor['departamento']."','1')\">\n";
        $html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
      }*/
      $html .= "  </tr>\n";
      $i++;
    }
     
    $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    
    $html .= "<table align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    //$html .= " </table><br>\n";
    $html .= "</form>\n";
    
    $html .= ThemeCerrarTabla();    
    return $html;
  }
}   
?>