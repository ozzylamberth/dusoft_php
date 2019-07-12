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
			c.razon_social AS empresa,
			b.empresa_id,
			b.descripcion as centro,
			b.centro_utilidad,
			a.documento_id as ssiid,
			a.sw_auditoria,
			c.tipo_id_tercero,
			c.id,
			c.codigo_sgsss
			FROM	userpermisos_Formulacion_Externa_Facturacion as a
			JOIN centros_utilidad as b ON (a.empresa_id = b.empresa_id)
			AND (a.centro_utilidad = b.centro_utilidad)
			JOIN empresas as c ON (b.empresa_id = c.empresa_id)
			WHERE	TRUE
			AND	a.usuario_id =".UserGetUID()."
			AND	a.sw_activo = '1' ";
						
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[$rst->fields[0]][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
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