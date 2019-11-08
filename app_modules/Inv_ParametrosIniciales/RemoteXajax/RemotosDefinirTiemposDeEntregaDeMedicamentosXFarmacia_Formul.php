<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosEstadosDocumentos.php
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
    
  function BuscarTiempoEntregaMedicamentos($empresa_id)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasDefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica","classes","app","Inv_ParametrosIniciales");
  
  $FarmaciaTiempoEntrega=$sql->Buscar_FarmaciaTiempoEntrega($empresa_id);
  
  if($FarmaciaTiempoEntrega[0]['tiempo_entrega']=="" || empty($FarmaciaTiempoEntrega))
  {
  $html = "<a href=\"#\" onclick=\"xajax_IngresoTiempo('".$empresa_id."','".$FarmaciaTiempoEntrega[0]['tiempo_entrega']."')\">No tiene asignado un Tiempo de entrega</a>";
  }
  else
      $html = "<a href=\"#\" onclick=\"xajax_IngresoTiempo('".$empresa_id."','".$FarmaciaTiempoEntrega[0]['tiempo_entrega']."')\">En ".$FarmaciaTiempoEntrega[0]['tiempo_entrega']." Dias</a>";
  
  
  $objResponse->assign("tiempo_entrega","innerHTML",$objResponse->setTildes($html));
  return $objResponse;
          
  }
  
  
  


  function IngresoTiempo($Empresa_Id,$Tiempo_Entrega)
  {
  $objResponse = new xajaxResponse();
  
  $select = "<select class=\"select\" name=\"tiempo\" id=\"tiempo\" size=\"1\">";
        for($i=0;$i<=60;$i++)
        {
        if($Tiempo_Entrega == $i)
        $selected ="selected";
        else
           $selected ="";
        
        $select .="<option value='".$i."' ".$selected.">";
        $select .=$i;
        $select .="</option>";
        }
  $select .= "</select>";  

    
		$html .= "  <form name=\"Formulario\" id=\"Formulario\" method=\"post\">";
		$html .= $select;
    
    $html .="   <input class=\"modulo_table_list\" type=\"button\" name=\"Guardar\" value='Guardar' onclick=\"xajax_GuardarDaticos(document.getElementById('tiempo').value,'".$Empresa_Id."')\">";
		$html .= "		</form>";
		$html .= "      </table>";
  
    $objResponse->assign("tiempo_entrega","innerHTML",$objResponse->setTildes($html));
    return $objResponse;
  }
  
  function GuardarDaticos($Tiempo,$Empresa_Id)
  {
  $objResponse = new xajaxResponse();
  $Formulario['tiempo_entrega']=$Tiempo;
  $Formulario['empresa_id']=$Empresa_Id;
  $sql=AutoCarga::factory("ConsultasDefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica", "classes", "app","Inv_ParametrosIniciales");
  $FarmaciaTiempoEntrega=$sql->Buscar_FarmaciaTiempoEntrega($Empresa_Id);
  
  if(empty($FarmaciaTiempoEntrega))
      $sql->GuardarDaticos($Formulario); 
      else
          $sql->ModificarDaticos($Formulario); 
  
  $objResponse->script("xajax_BuscarTiempoEntregaMedicamentos('".$Empresa_Id."');");  
  return $objResponse;
  }
  
  
  function BorrarDaticos($tabla,$id,$campo_id,$TerceroId,$FuncionXajax)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Borrar_Registro($tabla,$id,$campo_id);
    
    
    $objResponse->script("xajax_".$FuncionXajax."('".$TerceroId."');");  
    return $objResponse;
  
  }
 ?>
