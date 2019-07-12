<?php
  class Permisos extends ConexionBD
  {
    /**
    * Contructor
    */
    function Permisos(){}
		
    /**************************************************************************************
		* Busca los puntos de admision del modulo a los que tiene permiso el usuario
		* 
		* @return array
		***************************************************************************************/
		function BuscarPermisos()
		{
			$sql = "SELECT 
			a.empresa_id,
			d.razon_social as descripcion1,
			a.centro_utilidad,
			c.descripcion as descripcion2,
			a.bodega,
			b.descripcion as descripcion3,
			a.tipo_usuario
			FROM
			userpermisos_controldespachos AS a
			JOIN bodegas as b ON (a.empresa_id = b.empresa_id)
			AND (a.centro_utilidad = b.centro_utilidad)
			AND (a.bodega = b.bodega)
			AND (b.estado = '1')
			JOIN centros_utilidad as c ON (b.empresa_id = c.empresa_id)
			AND (b.centro_utilidad = c.centro_utilidad)
			JOIN empresas as d ON (c.empresa_id = d.empresa_id)
			AND (sw_activa = '1')
			WHERE
			a.usuario_id = '".UserGetUID()."';";
			/*print_r($sql);*/			
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[$rst->fields[1]][$rst->fields[3]][$rst->fields[5]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    
  }
?>