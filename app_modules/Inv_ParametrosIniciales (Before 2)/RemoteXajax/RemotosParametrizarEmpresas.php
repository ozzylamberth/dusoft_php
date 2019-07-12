<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosParametrizarEmpresas.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  
  
  
  
  
 
  
  
  /*
  * Funcion Que Refrescará el listado de Estados de Documentos a desplegar en la pagina.
  */  
    
  function ListadoEmpresas($empresa_id,$razon_social,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasParametrizarEmpresas","classes","app","Inv_ParametrosIniciales");
  
  $Empresas=$sql->Listar_Empresas($empresa_id,$razon_social,$offset);
  
  $action['paginador'] = "paginador('".$empresa_id."','".$razon_social."'";
        
        
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
    
    $html .= "<fieldset class=\"fieldset\">\n";
      $html .= "  <legend class=\"normal_10AN\">EMPRESAS</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"25%\">NOMBRE</td>\n";
      $html .= "      <td width=\"25%\">DIRECCION</td>\n";
      $html .= "      <td width=\"7%\">VENDE</td>\n";
      $html .= "      <td width=\"7%\">ESTADOS</td>\n";
      $html .= "      <td width=\"7%\">TIPO EMPRESA</td>\n";
      $html .= "    </tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($Empresas as $key => $ED)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "      <td >".$ED['razon_social']."</td><td>".$ED['pais']."-".$ED['departamento']."-".$ED['municipio'].": ".$ED['direccion']."</td>\n";
             
        if($ED['sw_vende']==1)
          {
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstado('empresas','sw_vende','0','".$ED['empresa_id']."','empresa_id')\">\n";
          $html .="<img title=\"VENDE DIRECTO AL PUBLICO\" src=\"".GetThemePath()."/images/si.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstado('empresas','sw_vende','1','".$ED['empresa_id']."','empresa_id')\">\n";
            $html .="<img title=\"NO VENDE DIRECTO AL PUBLICO\" src=\"".GetThemePath()."/images/no.png\" border=\"0\"></a></td>\n";
            }

             
        if($ED['sw_estados']==1)
          {
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstado('empresas','sw_estados','0','".$ED['empresa_id']."','empresa_id')\">\n";
          $html .="<img title=\"MANEJA ESTADOS\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstado('empresas','sw_estados','1','".$ED['empresa_id']."','empresa_id')\">\n";
            $html .="<img title=\"NO MANEJA ESTADOS\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
                      
           if($ED['sw_tipo_empresa']==1)
          {
          $html .= "<td align=\"center\">
                    <a href=\"#\" onclick=\"xajax_CambioEstado('empresas','sw_tipo_empresa','0','".$ED['empresa_id']."','empresa_id')\">\n";
          $html .="<img title=\"ES FARMACIA\" src=\"".GetThemePath()."/images/inactivoemp.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstado('empresas','sw_tipo_empresa','1','".$ED['empresa_id']."','empresa_id')\">\n";
            $html .="<img title=\"ES EMPRESA\" src=\"".GetThemePath()."/images/activoemp.png\" border=\"0\"></a></td>\n";
            }
 
          }
          
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          
          $objResponse->assign("listado_empresas","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }

 
  /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function CambioEstado($tabla,$campo,$valor,$id,$campo_id)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->script("xajax_ListadoEmpresas('','','1');");
    return $objResponse;	
	}
  
 
  
?>
