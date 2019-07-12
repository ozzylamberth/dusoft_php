<?php
  /**
  * @package -SIIS
  * @version $Id$
  * @copyright 
  * @author  Ronald Marin  -A
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package -SIIS
  * @version $Revision: 1 $
  * @copyright 
  * @author 
  */
  
  
  /***********************************************************************************************************
    * Funcion que hace la busqueda de un cliente de farmacia Duana en la base de datos
    * para indicar si pude hacerse una afiliacion o no
    * @param array $datos Vector con los datos de la identificacion del afiliado
    * @return object
    ************************************************************************************************************/
  function BuscarCliente($datos)
  {
    $objResponse = new xajaxResponse();
    //$objResponse->alert(print_r($datos,true));
    $cli = AutoCarga::factory("InterfacesPlanesDuana","classes","app","Clientes_Farmacias_Duana");
    
    $registros = $cli->ObtenerDatosClientes($datos);
    $html = "";
    if(!empty($registros))
    {
      $html = "NO SE PUEDE REALIZAR UN NUEVO REGISTRO DEBIDO A QUE YA EXISTE UNA AFILIACION CON ESTE DOCUMENTO";
      $objResponse->assign("error","innerHTML",$html);    
    }
    else
    {
      $objResponse->call("continuarAfiliacion");
    }
    return $objResponse;
  } 
  
  /***********************************************************************************************************
    * Funcion xajax buscar municipios para la vista "FormaRegistrarCliente"
    * @param array $datos Vector con los datos del cliente
    * @return object
    ************************************************************************************************************/
  function BuscarMunicipios($datos)
  {
    $objResponse = new xajaxResponse();
    //$objResponse->alert(print_r($datos,true));
    $cli = AutoCarga::factory("InterfacesPlanesDuana","classes","app","Clientes_Farmacias_Duana");
    
    $registros = array();
    if($datos['dpto'] != '-1')
      $registros = $cli->ObtenerMunicipios($datos);
    
    $html  = "<select name=\"municipio\" class=\"select\" onchange=\"#\">\n";
    $html .= "  <option value=\"-1\">-SELECCIONAR-</option>\n"; 
    foreach($registros as $key => $value)
     $html .= " <option value=\"".$value['tipo_mpio_id']."\" >".$value['municipio']."</option>\n";
	
    $html .= "</select>\n";

    $objResponse->assign("capa_mpios","innerHTML",$html);
    
    return $objResponse;
  }

  
 
 
?>