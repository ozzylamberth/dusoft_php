<?php
/**
 * $Id: RipsEPS.class.php,v 1.1 2008/12/04 22:30:14 hugo Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS-CLASSES
 * 
 * Clase para generar los rips de EPS's
 */

/**
 * Clase padre para generar los rips de EPS's
 * para cada archivo de rips se debe
 * crear una clase que extienda de esta clase
 *
 * @author    Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
 * @version   $Revision: 1.1 $
 * @package   IPSOFT-SIIS-CLASSES
 */
class RipsEPS
{
	/**
	 * Codigo de error
	 *
	 * @var string
	 * @access private
	 */
	var $error;

	/**
	 * Mensaje de error
	 *
	 * @var string
	 * @access private
	 */
    var $mensajeDeError;
	
	var $codigo_sgsss;
	var $fecha_inicial;
	var $fecha_final;
	var $cxc_estado;
	var $proveedor_id;
	var $FiltrosRango;

	
	function RipsEPS()
	{
	
	}
	
	
	/*
	* Metodo para validar si el usuario actual tiene permisos de generar RIPS de EPS
	*
	* @return boolean
	* @access private
	*/
	function ValidarPermisos()
	{
		$usuario_id = UserGetUID();
		
		if(empty($usuario_id))
		{
            $this->error = "CLASS RipsEPS - ValidarPermisos - ERROR 01";
            $this->mensajeDeError = "Usuario no logueado.";	
			return false;
		}
		
	    list($dbconn) = GetDBconn();

	    $query = "SELECT count(*) FROM userpermisos_eps_rips WHERE usuario_id = $usuario_id AND sw_activo = '1'; ";

	    $result = $dbconn->Execute($query);

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "CLASS RipsEPS - ValidarPermisos - ERROR 02";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }
		
		list($cantida) = $result->FetchRow();
		
		if($cantidad != 1)
		{
			return false;
		}
		else
		{
			return true;
		}		
	}
	
	
	/*
	* Metodo para generar RIPS de EPS
	*
	* @param date $fecha_inicial
	* @param date $fecha_final
	* @param array opcional $cxc_estado
	* @param integer opcional $proveedor_id
	* @return 
	* @access public
	*/
	function GetRipsEPS($fecha_inicial,$fecha_final,$cxc_estado,$proveedor_id)
	{
		if(!$this->ValidarPermisos())
		{
			if(empty($this->error))
			{
	            $this->error = "CLASS RipsEPS - GetRipsEPS - ERROR 01";
	            $this->mensajeDeError = "Usuario sin permisos para generar RIPS de EPS";
			}
            return false;		
		}
		
        if(!IncludeClass("RipsEPS_GestionArchivos","RipsEPS"))
        {
            $this->error = "CLASS  RipsEPS - GetRipsEPS - ERROR 02";
            $this->mensajeDeError = "No se pudo incluir el archivo : RipsEPS_GestionArchivos";
            return false;
        }

        $class_name = 'RipsEPS_GestionArchivos';
		
        if(!class_exists($class_name))
        {
            $this->error = "CLASS RipsEPS - GetRipsEPS - ERROR 03";
            $this->mensajeDeError = "No existe la clase : $class_name";
            return false;
        }
		
		if(empty($codigo_sgsss) || empty($fecha_inicial) || empty($fecha_final))
		{
            $this->error = "CLASS RipsEPS - SetRango - ERROR 04";
            $this->mensajeDeError = "Codigo SGSSS, Fecha Incial ó Fecha Final NULOS";
            return false;		
		}
		
		$this->codigo_sgsss  = $codigo_sgsss;
		$this->fecha_inicial = $fecha_inicial;
		$this->fecha_final   = $fecha_final;
		$this->cxc_estado    = $cxc_estado;
		$this->proveedor_id  = $proveedor_id;
		
		$this->FiltrosRango  = "";	
		
		$sql = "
		
			SELECT * 
			FROM
				cxp_facturas as a,
				cxp_radicacion as b,
				terceros_proveedores as c,
				rips_arch_control as d,
				rips_arch_usuarios_ss as US
				
				
			WHERE
				b.cxp_radicacion_id = a.cxp_radicacion_id AND
				c.codigo_proveedor_id = b.proveedor_id AND
				d.rips_control_id = b.rips_control_id AND
				US.rips_control_id = d.rips_control_id
				". $this->FiltrosRango ."	
		
		";		
		

		

		if(!$this->GenerarArchivoCT())
		{
			if(empty($this->error))
			{
	            $this->error = "CLASS RipsEPS - GetRipsEPS - ERROR 05";
	            $this->mensajeDeError = "No se pudo generar el archivo CT de RIPS de EPS";
			}
            return false;			
		}
		

	}//END OF FUNCTION
	
	function GenerarArchivoCT()
	{
	
	}

}//END OF CLASS