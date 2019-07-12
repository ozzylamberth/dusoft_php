<?php

/**
 * $Id: WS_Autenticacion.php,v 1.3 2005/08/23 22:27:46 ehudes Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 * Web service para autenticación de usuarios de SIIS
 */

require('../../classes/WebService/WebService.php');

/**
 * 
 * Web service para la autenticación de usuarios de SIIS
 * desde otras aplicaciones externas a SIIS como por ejemplo
 * el MIGE-RAS
 *
 * @author    Ehudes Fernán García Gil <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.3 $
 * @package   IPSOFT-SIIS
 */
class WS_Autenticacion extends WebService
{
	/**
	 * Constructor
	 * 
	 * @access public
	 */
	function WS_Autenticacion()
	{
		$this->ArrayMetodos=array(
		0=>array(
			'Nombre'=>'AutenticarUsuario',
			'Parametros'=>array('usuario' => 'xsd:string','password'=>'xsd:string'),
			'Salida'=>array('return' => 'xsd:string'),
			'SoapAction'=>'AutenticarUsuario'
			),
		1=>array(
			'Nombre'=>'AutenticarSession',
			'Parametros'=>array('UsuarioId'=>'xsd:string','SessionId'=>'xsd:string'),
			'Salida'=>array('return' => 'xsd:string'),
			'SoapAction'=>'AutenticarSession'
			)
		);
		$this->WebService('Autenticacion',GetBaseURL(),GetBaseURL()."WS_Autenticacion.php");
		$this->RegistrarMetodos();
		$this->Publicar();
	}//Fin del método WS_Autenticacion
}

/**
 * Metodo del Web Service para autenticar un usuario de SIIS, sí el usuario es valido
 * retorna 1 de lo contrario retorna 0
 *
 * @param string usuario
 * @param string password
 * @return xsd:string
 * @access public
 */
function AutenticarUsuario($usuario,$password)
{
	IncludeLib('users');
	$valido=UserValidarPasswd($usuario,$password);
	if($valido)
		return new soapval('return','xsd:string','1');
	else
		return new soapval('return','xsd:string','0');
}//Fin del método AutenticarUsuario

/**
 * Método del web service para verificar por UsuarioId y SessionId de SIIS, sí existe un 
 * usuario con ese Id y si el usuario está logueado en con un SessionId
 *
 * @param string UsuarioId
 * @param string SessionId
 * @return xsd:string
 */
function AutenticarSession($UsuarioId,$SessionId)
{
	list($dbconn)=GetDBConn();
	if ($dbconn->ErrorNo() != 0)
	{
		return new soap_fault('Client','','Error'); 
	}
	$sql="SELECT usuario_id FROM system_session WHERE session_id = '$SessionId' AND usuario_id=$UsuarioId";
	GLOBAL $ADODB_FETCH_MODE;
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$rs=$dbconn->Execute($sql);
	if(!$rs->EOF)
		return new soapval('return','xsd:string','1');
	else
		return new soapval('return','xsd:string','0');
}
$WS_Autenticacion=new WS_Autenticacion();
?>