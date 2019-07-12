<?php

/**
 * $Id: WebService.php,v 1.2 2005/07/25 18:20:42 ehudes Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 * Publicación de web services
 */

require('../../classes/WebService/enviroment_siis.inc.php');
require('../../classes/nusoap/lib/nusoap.php');
/**
 * 
 * Clase para publicación de web services
 *
 * @author    Ehudes Fernán García Gil <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.2 $
 * @package   IPSOFT-SIIS
 */
class WebService
{
	/**
	 * Nombre del webservice
	 *
	 * @var string nombre
	 * @access private
	 */
	var $Nombre;
	
	/**
	 * Name space del web service
	 * 
	 * @var string nameSpace
	 * @access private
	 */
	var $NameSpace;
	
	/**
	 * End point del web service
	 *
	 * @var string endPoint
	 * @access private
	 */
	var $EndPoint;
	
	/**
	 * Arreglo de los metodos del web service
	 * 
	 * @var array arrayMetodos
	 * @access public
	 */
	var $ArrayMetodos;
	
	/**
	 * Variable nusoap
	 *
	 * @var object soap_server
	 * @access private
	 */
	var $soap_server;
	
	/**
	 * Constructor
	 *
	 * @param string Nombre
	 * @param string NameSpace
	 * @param string EndPoint
	 * @access public
	 */
	function WebService($Nombre,$NameSpace,$EndPoint)
	{
		$this->Nombre=$Nombre;
		$this->NameSpace=$NameSpace;
		$this->EndPoint=$EndPoint;
		$this->soap_server = new soap_server();
		$this->soap_server->configureWSDL($this->Nombre,$this->NameSpace,$this->EndPoint);
		$this->soap_server->wsdl->schemaTargetNamespace=$this->NameSpace;
	}//Fin del constructor
	
	/**
	 * Registra los metodos del webservice para esto lee
	 * el atributo ArrayMetodos donde cada posicion(metodo) del arreglo debe contener
	 * Nombre=>Nombre del metodo
	 * Parametros=>Parametros del metodo
	 * Salida=>Salida del metodo
	 * SoapAction=>Nombre del metodo en el webservice
	 *
	 * @access public
	 */
	function RegistrarMetodos()
	{
		if(sizeof($this->ArrayMetodos)>0)
		{
			foreach($this->ArrayMetodos as $Metodo)
			{
				$this->soap_server->register($Metodo['Nombre'],$Metodo['Parametros'],$Metodo['Salida'],$this->NameSpace, $this->EndPoint."/".$Metodo['SoapAction']);
			}
		}
	}//Fin del método RegistrarMetodos 
	
	/**
	 * Publica el webservice
	 * 
	 * @access public
	 */
	function Publicar()
	{
		global $HTTP_RAW_POST_DATA;
		$this->soap_server->service($HTTP_RAW_POST_DATA);
	}//fin del Método Publicar
}
?>