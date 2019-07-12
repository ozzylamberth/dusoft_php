<?php
/**
  * @package DUANA
  * @version 1.0 $Id: Remotos_pqrs.php
  * @copyright (C) JUN-2012 DUANA & CIA 
  * @author R.O.M.A
  */
 /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
 */
 
  //util
  //$objResponse->script("alert('Nota Anulada, con Exito');");

 
  	/******************************************************************************************
	* Remotos: [Select] listar usuarios de farmacia
	*@param: $empresa_id, $bodega
	******************************************************************************************/
     
    function GetUserFarm($bodega,$empresa)
	{      $objResponse = new xajaxResponse();
      //$objResponse->alert('mensje');
      $sql = AutoCarga::factory("Permisos","classes","app","Pqrs");
      $usuarios =$sql->BuscarUsuarioFarm($empresa,$bodega);
	
	  $html  = " <select name=\"resp_caso\" id=\"resp_caso\" class=\"select\">  " ;
	  $html .= "  <option value=\"0\">---SELECCIONAR---</option>\n";
	  foreach($usuarios as $key=>$valor)
		{
			$html .= " <option value=\"".$valor['usuario_id']."\">".strtoupper($valor['nombre'])."</option>\n";
		}
	  $html .= " </select>\n";
	  
	 $objResponse->assign("resp_farm","innerHTML",$html);
		
      return $objResponse;
	}



	
 
 
	
	

  
?>