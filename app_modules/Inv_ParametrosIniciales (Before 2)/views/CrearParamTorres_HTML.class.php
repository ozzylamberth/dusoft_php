<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */

  /**
  * Clase Vista: CrearParamTorres_HTML
  * Clase Contiene Metodos para La parametrizacion de torre de productos
  *
  * @package IPSOFT-SIIS
  * @version $Revision:
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
class CrearParamTorres_HTML
{
		/**
		* Constructor de la clase
		*/
		function CrearParamTorres_HTML(){}
    
  /**
     * Funcion donde se crea la forma para mostrar los productos para ingresar la torrre y el dueño
     * 
     * @param array $action vector que contiene los link de la aplicacion
     * @param array $datos vector que contiene los datos
     * @param array $torre vector que contiene los datos de las torres
     * @return string $html retorna la cadena con el codigo html de la pagina
     */ 
  function formaTorresProd($action,$conteo,$pagina,$datos)
  {
    $html  = ThemeAbrirTabla('PARAMETRIZACION DE TORRES-PRODUCTOS');
    $mdl = AutoCarga::factory("ConsultasParamTorresP","","app","Inv_ParametrosIniciales");
    
    $html .= "<form name=\"formTorresProd\" id=\"formTorresProd\" method=\"post\" action=\"\">\n";
    $html .= " <table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td  align=\"center\"width=\"20%\">CODIGO PRODUCTO\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\"width=\"20%\">DESCRIPCION\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\"width=\"10%\">BODEGA\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\" width=\"15%\">EMPRESA\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\" width=\"5%\">TORRE\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\" width=\"5%\">DUEÑO TORRE\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\" width=\"2%\">\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    
    $pghtml = AutoCarga::factory('ClaseHTML');
    $i=1;
    $k=1;
   
    foreach($datos as $key => $valor)
		{ 
      
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">".$valor['codigo_producto']."\n";
      $html .= "    </td>\n";
      
      $html .= "    <td align=\"center\">".$valor['descripcion_prod']."\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">".$valor['bodega']."\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">".$valor['empresa_id']."\n";
      $html .= "    </td>\n";
      $torres=$mdl->Buscarparamprod($valor['empresa_id'],$valor['codigo_producto']);
       
      if($valor['codigo_producto']==$torres['codigo_producto'])
      { 
       if($torres['torre'])
          $torre=$torres['torre'];
        else
          $torre='';
       if($torres['dueno_torre'])
         $dueno_torre=$torres['dueno_torre'];
        else
         $dueno_torre='';
      }
      else
      {
        $torre='';
        $dueno_torre='';
      }
     
      $html .= "    <td class=\"modulo_list_claro\" width=\"5%\">\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"torre".$i."\" id=\"torre".$i." \"  maxlength=\"10\" value=\"".$torre."\">";
      $html .= "    </td>\n";
    
      $html .= "    <td class=\"modulo_list_claro\" width=\"5%\">\n";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"due_torre".$k."\" id=\"due_torre".$k." \"  maxlength=\"25\" value=\"".$dueno_torre."\">";
      $html .= "    </td>\n";
 
      $html .= "    <td class=\"modulo_list_claro\" width=\"2%\">\n";
      $html .= "      <a href=\"#\" onclick=\"xajax_GuardarTorreProd('".$valor['codigo_producto']."','".$valor['descripcion_prod']."','".$valor['empresa_id']."',document.formTorresProd.torre".$i.".value,document.formTorresProd.due_torre".$k.".value);\" class=\"label_error\">G</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $i++;
      $k++;
      
    }
 
    $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    
    $html .= "<table align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
   
    $html .= "</form>\n";
    
    $html .= ThemeCerrarTabla();    
    return $html;
  }
}   
?>