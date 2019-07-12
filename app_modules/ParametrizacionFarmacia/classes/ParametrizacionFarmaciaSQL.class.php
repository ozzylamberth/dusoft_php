<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: ParametrizacionFarmaciaSQL.class.php,v 1.0  2010/01/26 22:40:38 sandra 
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	class ParametrizacionFarmaciaSQL extends ConexionBD
	{
	/*
	* Constructor de la clase
	*/
	function ParametrizacionFarmaciaSQL(){}

	/**
	* Funcion donde se Consultan los Permisos 
	* @return array $datos vector que contiene la informacion */
		function ObtenerPermisos()
		{
			//$this->debug = true;
			$sql  = "	SELECT   	a.empresa_id, ";
			$sql .= "   	        b.razon_social AS descripcion1, ";
			$sql .= "       		b.sw_activa, ";
			$sql .= "           	a.usuario_id ";
			$sql .= "	FROM 	    userpermisos_ParametrizacionFarmacia AS a, ";
			$sql .= "           	empresas AS b ";
			$sql .= "	WHERE      	a.usuario_id= ".UserGetUID()."  ";
			$sql .= "        AND 	a.empresa_id=b.empresa_id ";
			$sql .= "     	AND 	b.sw_activa='1' AND  b.sw_tipo_empresa='1' ";

			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	
	/*
		* Funcion donde se Consultan el tipo id de la empresa que ha generado el documento de despacho a la farmacia.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ConsultarInformacionFarmacia($farmacia)
		{
		 //$this->debug=true;
			$sql = " SELECT tipo_id_tercero,
							id,
							razon_social,
							representante_legal,
							direccion,
							telefonos,
							tipo_atencion
					FROM    empresas
					WHERE   empresa_id= '".$farmacia."' ";
					
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	
	/*
		* Funcion donde se Actualiza los tipos de atencion de la farmacia.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ActualizarTipoAtencion($valor,$farmacia,$actual)  
		{
		//$this->debug=true;
			$sql .= " UPDATE   empresas ";
			$sql .= " set     tipo_atencion='".$valor."' ";
			$sql .= " WHERE   empresa_id='".$farmacia."' and  tipo_atencion='".$actual."'; ";
			
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	}
 ?>