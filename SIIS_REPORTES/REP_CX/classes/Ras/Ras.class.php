<?php
/**
 * $Id: Ras.class.php,v 1.1 2005/07/25 20:27:19 ehudes Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 * Conexión con el RAS(Report Aplication Server)
 */

/**
 * 
 * Clase para conexión con el RAS(Report Aplication Server)
 *
 * @author    Ehudes Fernán García Gil <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.1 $
 * @package   IPSOFT-SIIS
 */
class Ras
{
	/**
	 * Ip del servidor donde se encuentra el RAS
	 *
	 * @var string servidor
	 * @access private
	 */
	var $servidor;

	/**
	 * Puerto del servidor RAS
	 *
	 * @var string puerto
	 * @access private
	 */
	var $puerto;
	
	/**
	 * Url del servidor RAS
	 *
	 * @var string url
	 * @access private
	 */
	var $url;
	
	/**
	 * Usuario de conexión con el RAS
	 *
	 * @var string user
	 * @acces private
	 */
	var $user;
	
	/**
	 * Password del usuario de conexión con el RAS
	 *
	 * @var string passwd
	 * @acces private
	 */
	var $passwd;
	
	/**
	 * Variable que almacena si hay o no hay conexion
	 *
	 * @var boolean conexion
	 * @access private
	 */
	var $conexion;
	
	/**
	 * Constructor
	 * 
	 * @access private
	 */
	function Ras($servidor,$puerto)
	{
		$this->servidor=$servidor;
		$this->puerto=$puerto;
		$this->conexion=true;
		$this->user='';
		$this->passwd='';
		$this->url="http://".$this->servidor.":".$this->puerto;
		
		GLOBAL $MIGE;
		GLOBAL $MIGE_FRONT_CONTROLLER;
		$MIGE='SIISreports1';
		$MIGE_FRONT_CONTROLLER='ControlSIIS';
		
	}//Fin del constructor
	
	/**
	 * Abre la conexión con el RAS
	 *
	 * @access private
	 */
	function Conexion($user,$passwd)
	{
		$this->conexion=true;
	}//Fin del método conexion
	
	/**
	 * Solicita un reporte Reporte al RAS
	 * 
	 * @param string reporte
	 * @param string usuario_siis
	 * @param string password_siis
	 * @param string wsdl_parametros
	 * @param string volver
	 */
	function SolicitarReporte($reporte,$params,$usuario_id,$session_id)
	{
		GLOBAL $MIGE;
		GLOBAL $MIGE_FRONT_CONTROLLER;
		$MIGE_COMANDO='cmd=SRReportesSIIS';
		if($this->conexion)
		{
			$solicitud=$this->url."/".$MIGE."/".$MIGE_FRONT_CONTROLLER."?$MIGE_COMANDO&reporte=$reporte";
			$i=0;
			foreach($params as $valor)
			{
				$solicitud .= "&params_$i=$valor";
				$i++;
			}
			$solicitud .= "&usuario_id=$usuario_id&session_id=$session_id";
			if (!headers_sent()) {
				header("Location: $solicitud");
			}
			else
			{
				echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \"http://www.w3.org/TR/html4/frameset.dtd\">
						<html>
						<head>
							<title></title>	
							<script language=\"javascript\">
								window.location='$solicitud';
							</script>
						</head>
						<body>
						</body>
						</html>";
			}
			return true;
		}
		else
		{
			$this->error=true;
			$this->mensajeError="No hay una conexión disponible con el RAS";
			return false;
		}
	}
	
	/**
	 * Método que abre el MIGE desde SIIS
	 * ya sea en un pop-up en el frame de SIIS o en un iframe
	 *
	 * @param string UsuarioId
	 * @param string SessionId
	 * @param string Medio
	 * @access public
	 */
	function AbrirMige($UsuarioId,$SessionId,$Medio)
	{
		GLOBAL $MIGE;
		GLOBAL $MIGE_FRONT_CONTROLLER;
		$MIGE_COMANDO='cmd=SRValidarUsuario';
		if($this->conexion)
		{
			$solicitud=$this->url."/".$MIGE."/".$MIGE_FRONT_CONTROLLER."?$MIGE_COMANDO&usuario_id=$UsuarioId&session_id=$SessionId";
			if (!headers_sent()) {
				header("Location: $solicitud");
			}
			else
			{
				echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \"http://www.w3.org/TR/html4/frameset.dtd\">
						<html>
						<head>
							<title></title>	
							<script language=\"javascript\">
								window.location='$solicitud';
							</script>
						</head>
						<body>
						</body>
						</html>";
			}
			return true;
		}
		else
		{
			$this->error=true;
			$this->mensajeError="No hay una conexión disponible con el RAS";
			return false;
		}
	}
}
?>