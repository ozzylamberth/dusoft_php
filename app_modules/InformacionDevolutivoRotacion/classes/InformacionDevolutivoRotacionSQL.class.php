<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: InformacionDevolutivoRotacionSQL.class.php,v 1.0
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/

	class InformacionDevolutivoRotacionSQL extends ConexionBD
	{
	/*
	* Constructor de la clase
	*/
	function InformacionDevolutivoRotacionSQL(){}

	/**
	* Funcion donde se Consultan los permisos
	* @return array $datos vector que contiene la informacion de la consulta de los Tipos 
	* de Identificacion
	*/

		function ObtenerPermisos()
		{
			//$this->debug = true;
			$sql  = " SELECT   	a.farmacia_id, ";
			$sql .= "           b.razon_social ";
			$sql .= "FROM 	    Userpermisos_Devolutivo_RotacionFarmacia AS a, ";
			$sql .= "           empresas AS b ";
			$sql .= "WHERE      a.usuario_id= ".UserGetUID()."  ";
			$sql .= "           AND 	a.farmacia_id=b.empresa_id ";
			$sql .= "           AND 	a.sw_activo='1' AND  b.sw_tipo_empresa='1' ";

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
		* Funcion donde se Consulta la informacion de lo solicitado para que sea devuelto
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	
		function ConsultarInformacionDevolucion($farmacia)
		{
		
			$sql = " select d.devolucionrf_id,
							d.codigo_producto,
							fc_descripcion_producto(d.codigo_producto) as producto,
							d.cantidad_dev,
							d.cantidad
			
					FROM 	devolucion_rotacion_farmacia as d
					
					WHERE   d.empresa_id='".$farmacia."'  
					AND 	d.sw_devuelto = '0' 
					ORDER BY producto ";

						if(!$rst = $this->ConexionBaseDatos($sql))	return false;
						$datos = array();
						while (!$rst->EOF)
						{
						 
						  $medicamentos[]  = $rst->GetRowAssoc($ToUpper = false);
						  $rst->MoveNext();
						}
						$rst->Close();
						return $medicamentos;
						
		}
	/**
	* Funcion donde se actualiza si ya se realizo el devolutivo solocitado
	* @return array $datos vector que contiene la informacion de la consulta de los Tipos 
	* de Identificacion
	*/
		function ActualizarDatos($form,$num,$farmacia)
		{
		
		  for($i=0;$i<$num;$i++)
			   {
			      if($form['chec'][$i]==1)
					{
				    	
					
					$this->ConexionTransaccion();
					$sql  .= "UPDATE   devolucion_rotacion_farmacia 
							    set      sw_devuelto ='1',	usuario_devuelve=".UserGetUID()."
			     	WHERE 	empresa_id = '".$farmacia."'  and codigo_producto='".$form['medicamento'][$i]."' ;";
					}
					}
				if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
				return $nucontrato;
		
		}
		
	}
 ?>
