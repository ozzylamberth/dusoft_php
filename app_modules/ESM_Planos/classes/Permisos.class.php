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
      $sql  = "SELECT	E.empresa_id AS empresa, ";
			$sql .= "				E.razon_social AS razon_social, ";
			$sql .= "				E.tipo_id_tercero,  ";
			$sql .= "				E.id  ";
			$sql .= "FROM	  userpermisos_reportes_gral G,";
      $sql .= "       empresas E ";
			$sql .= "WHERE	G.usuario_id = ".UserGetUID()." ";
			$sql .= "AND	  G.empresa_id = E.empresa_id";

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