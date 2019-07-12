<?php
/**
 * $Id: WS_Vitros.php,v 1.6 2005/09/27 12:38:35 mauricio Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 * Web service para interface con Vitros
 * @author    Ehudes Fernán García Gil <efgarcia@ipsoft-sa.com>
 * @author    Mauricio Bejarano L. <maurobej@hotmail.com>
 * @version   $Revision: 1.6 $
 */

require('../../classes/WebService/WebService.php');

class WS_Vitros extends WebService
{
	/**
	 * Constructor
	 * 
	 * @access public
	 */
	function WS_Vitros()
	{
		$this->ArrayMetodos=array(
		0=>array(
			'Nombre'		=>'ErrorVitros',
			'Parametros'=>array('error' => 'xsd:string','file'=>'xsd:string'),
			'Salida'		=>array('return'=>'xsd:string'),
			'SoapAction'=>'ErrorVitros'
			),
		1=>array(
			'Nombre'		=>'ResultadoVitros',
			'Parametros'=>array('file'=>'xsd:string','cadena'=>'xsd:string'),
			'Salida'		=>array('return'=>'xsd:string'),
			'SoapAction'=>'ResultadoVitros'
		),
		2=>array(
			'Nombre'		=>'ErrorGenerado',
			'Parametros'=>array('error'=>'xsd:string','file'=>'xsd:string'),
			'Salida'		=>array('return'=>'xsd:string'),
			'SoapAction'=>'ErrorGenerado'
		)
		);
		
		//Función para obtener la url del equipo donde se ejecuta el webservice????
		//$this->WebService('Vitros',GetBaseURL().'SIIS/webservices/Vitros',GetBaseURL().'SIIS/webservices/Vitros/WS_Vitros.php');
		$this->WebService('Vitros',GetBaseURL(),GetBaseURL().'WS_Vitros.php');
		//$this->WebService('Vitros','http://192.1.1.31/~mauricio/SIIS/webservices/Vitros','http://192.1.1.31/~mauricio/SIIS/webservices/Vitros/WS_Vitros.php');
		$this->RegistrarMetodos();
		$this->Publicar();
	}//Fin del método WS_Autenticacion
}

/**
 * Metodo del Web Service para obtener un error generado por la Vitros
 * @param string error Error generado por la Vitros
 * @return string
 * @access public
 */
	function ErrorVitros($error,$file){
	
		//return new soapval('return','xsd:string','entro');
		if(!IncludeClass('Vitros','Vitros')){
			return new soapval('return','xsd:string','0');
		}
		if(class_exists('Vitros'))
			{
				$vitros = new Vitros();
				$res=$vitros->ProcesaErrorVitros($error,$file);
				if($res=='ok'){return new soapval('return','xsd:string','aceptado');}
				else{return new soapval('return','xsd:string','rechazado2');}
			}
			else{return new soapval('return','xsd:string','0');}
	}//Fin del método AutenticarUsuario
	
	/**
	* Recibe el resultado de las pruebas realizadas por la vitros y crea un archivo de respuestas
	*	@param string file:		Es el nombre del archivo creado por la maquina vitros
	*	@param string Contiene el resultado de la evaluacion de la prueba por la vitros
	*	@return string
	*	@access public
	*/
	function ResultadoVitros($file,$cadena){
	
		if(!IncludeClass('Vitros','Vitros')){
			return new soapval('return','xsd:string','0');
		}
		
		if(class_exists('Vitros'))
			{
				$vitros = new Vitros();
				$res=$vitros->ProcesaResultadoVitros($file,$cadena);
				if($res==true){return new soapval('return','xsd:string','Archivo_aceptado');}
				else{return new soapval('return','xsd:string','Archivo_Rechazado');}
			}
			else{return new soapval('return','xsd:string','Error_ResultadoVitros');}

	}//fin function ResultadoVitros
	
	/**
 * Metodo del Web Service para obtener un error generado por la Vitros
 * @param string error Error generado por la Vitros
 * @return string
 * @access public
 */
	function ErrorGenerado($error,$file){
	
		return new soapval('return','xsd:string','2');
// 		if(!IncludeClass('Vitros','Vitros')){
// 			return new soapval('return','xsd:string','0');
// 		}
// 		if(class_exists('Vitros'))
// 			{
// 				$vitros = new Vitros();
// 				$res=$vitros->ProcesaErrorGenerado($error,$file);
// 				if($res==true){return new soapval('return','xsd:string','xxx');}
// 				else{return new soapval('return','xsd:string','yyy');}
// 			}
// 			else{return new soapval('return','xsd:string','zzz');}
			
	}//Fin del método AutenticarUsuario
	$WS_Vitros=new WS_Vitros();
?>