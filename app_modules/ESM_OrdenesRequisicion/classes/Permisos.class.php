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
			d.razon_social AS descripcion_empresa,
			d.empresa_id,
			c.descripcion as descripcion_centro,
			c.centro_utilidad,
			b.descripcion as descripcion_bodega,
			b.bodega,
			a.ssiid
			FROM		
			userpermisos_esm_ordenes_requisicion a
			JOIN bodegas as b ON (a.empresa_id = b.empresa_id)
			AND (a.centro_utilidad = b.centro_utilidad)
			JOIN centros_utilidad as c ON (b.empresa_id = c.empresa_id)
			AND (b.centro_utilidad = c.centro_utilidad)
			JOIN empresas as d ON (c.empresa_id = d.empresa_id)
			
			WHERE		
			a.usuario_id =".UserGetUID().";";

			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[$rst->fields[0]][$rst->fields[2]][$rst->fields[4]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    
    
    function BuscarDatosEmpresa($CodigoEmpresa)
		{
		//$this->debug=true;
      $sql = "SELECT	
              EM.tipo_pais_id
							FROM		empresas EM
							WHERE		
              EM.empresa_id = '".$CodigoEmpresa."';";
						
			//$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
		}
	
	
	
  }
?>