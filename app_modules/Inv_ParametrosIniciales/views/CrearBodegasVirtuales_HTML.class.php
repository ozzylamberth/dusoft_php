<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */

  /**
  * Clase Vista: CrearBodegasVirtuales_HTML
  * Clase Contiene Metodos para el Ingreso de Parametros de Bodegas Virtuales
  *
  * @package IPSOFT-SIIS
  * @version $Revision:
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
class CrearBodegasVirtuales_HTML
{
		/**
		* Constructor de la clase
		*/
		function CrearBodegasVirtuales_HTML(){}
    
  /**
     * Funcion donde se crea la forma para mostrar los pacientes y seleccionar al que se le va hacer el registro
     * 
     * @param array $action vector que contiene los link de la aplicacion
     * @param array $datos vector que contiene los datos
     * @return string $html retorna la cadena con el codigo html de la pagina
     */ 
  function formaBodegasVirtuales($action,$conteo,$pagina,$datos)
  {
    $html  = ThemeAbrirTabla('PARAMETRIZACION DE BODEGAS VIRTUALES');
    
    $html .= "<form name=\"formBodegasVirtuales\" id=\"formBodegasVirtuales\" method=\"post\" action=\"\">\n";
    $html .= " <table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td  align=\"center\">CODIGO BODEGA\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\">NOMBRE BODEGA\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\">DEPARTAMENTO\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\">VIRTUAL\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    
    $mdl = AutoCarga::factory("ConsultasParamFarmacovigilancia","","app","Inv_ParametrosIniciales");
    $pghtml = AutoCarga::factory('ClaseHTML');
    //$html .= "<pre>".print_r($datos,true)."</pre>";
    foreach($datos as $key => $valor)
		{ 
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">".$valor['bodega']."\n";
      $html .= "    </td>\n";
      
      $html .= "    <td align=\"center\">".$valor['descripcion']."\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">".$valor['descrip_bod']."\n";
      $html .= "    </td>\n";
      
      //$html .="<pre>".print_r($Bloqueo_Productos,true)."</pre>";      
            
      if($valor['sw_virtual']==1)
      {
        $html .= "<td align=\"center\">
                   <a href=\"#\" onclick=\"xajax_GuardarBodegaVirtual('".$valor['bodega']."','".$valor['departamento']."','0')\">\n";
        $html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
      }
      else
      {
        $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_GuardarBodegaVirtual('".$valor['bodega']."','".$valor['departamento']."','1')\">\n";
        $html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
      }
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