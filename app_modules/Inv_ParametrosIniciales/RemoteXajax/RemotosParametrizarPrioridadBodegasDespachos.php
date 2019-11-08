<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosParametrizarPrioridadBodegasDespachos.php
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
    
  function EmpresasT()
  {
  $objResponse = new xajaxResponse();
  //Empresas
    $sql=AutoCarga::factory("ConsultasParametrizarPrioridadBodegasDespachos", "", "app","Inv_ParametrosIniciales");
		$Empresas=$sql->Listar_Empresas(); 
    
  
      $html .= "<fieldset class=\"fieldset\" style=\"width:80%\">\n";
      $html .= "  <legend class=\"normal_10AN\">EMPRESAS DE DESPACHO DE PRODUCTOS</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"7%\">POS.</td>\n";
      $html .= "      <td width=\"7%\">ID</td>\n";
      $html .= "      <td width=\"25%\">NOMBRE</td>\n";
      $html .= "      <td width=\"20%\">DIRECCION</td>\n";
      $html .= "      <td width=\"20%\">TELEFONO</td>\n";
      $html .= "      <td width=\"20%\">DEPARTAMENTO</td>\n";
      $html .= "      <td width=\"20%\">MUNICIPIO</td>\n";
      $html .= "      <td width=\"20%\">PRIORIDAD</td>\n";
      $html .= "      </tr>";
          $selected = "";
          $k=1;
          foreach($Empresas as $key => $EM)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
            
            
            /*$select = "<select class=\"select\" name=\"posicion\" onchange=\"onclick=xajax_DefinirPrioridad('empresas','sw_prioridad_despacho',this.value,'".$EM['empresa_id']."','empresa_id')\">";
                for($i=1;$i<=5;$i++)
                {
                    if($i==$EM['sw_prioridad_despacho'])
                    $selected = " selected ";
                        else
                             $selected = "";
                          
                          
            $select .= "<option value='".$i."' ".$selected.">".$i."</option>";
                }
            $select .= "</select>";*/
            
            if($i==1)
            {
            $class ="class=\"label_error\"";
            }
            else
                {
                $class = "";
                }
            
            $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\");  onmouseover=mOvr(this,'#FFFFFE');  >\n";
            
            $html .= "      <td>";
            $html .=  $k;
            $html .= "      </td>";
            
            
            $html .= "      <td>";
            $html .= "      ".$EM['empresa_id'];
            $html .= "      </td>";
            
            
            $html .= "      <td>";
            $html .= "      ".$EM['empresa'];
            $html .= "      </td>";
            
            $html .= "      <td>";
            $html .= "      ".$EM['direccion'];
            $html .= "      </td>";
            
            $html .= "      <td>";
            $html .= "      ".$EM['telefonos'];
            $html .= "      </td>";
            
            $html .= "      <td>";
            $html .= "      ".$EM['departamento'];
            $html .= "      </td>";
            
            $html .= "      <td>";
            $html .= "      ".$EM['municipio'];
            $html .= "      </td>";
            
            $html .= "      <td>";
            $html .= "      <div id=\"centros_utilidad\"></div>";
            $html .= "      </td>\n";
            $html .= "   </tr>";
            $k++;
          }
          
          $html .= "    </table>\n";
          $html .= "</fieldset>\n";
          
          $objResponse->assign("listado_empresas","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
  function Centros_Utilidad($Empresa_Id)
  {
  $objResponse = new xajaxResponse();
  //Empresas
    $sql=AutoCarga::factory("ConsultasParametrizarPrioridadBodegasDespachos", "", "app","Inv_ParametrosIniciales");
		$CentrosUtilidad=$sql->CentroUtilidadXEmpresa($Empresa_Id); 
    
  
      $html .= "<fieldset class=\"fieldset\" style=\"width:80%\">\n";
  
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
      
          $selected = "";
          $k=1;
          foreach($CentrosUtilidad as $key => $CU)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
            
            $html .= "    <tr>\n";
            
            
            $html .= "      <td>";
            $html .= "      ".$CU['centro_utilidad'];
            $html .= "      </td>";
            
          }
          
          $html .= "    </table>\n";
          $html .= "</fieldset>\n";
          
          $objResponse->assign("centros_utilidad","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
  
  /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function DefinirPrioridad($tabla,$campo,$valor,$id,$campo_id)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->script("xajax_EmpresasT()");
    return $objResponse;	
	}
 

 
?>