<?php
  class Permisos extends ConexionBD
  {
    /**
    * Contructor
    */
    function Permisos(){}
		
    /**************************************************************************************
	* Busca las empresas a las que tiene permiso el usuario
	* 
	* @return array
	***************************************************************************************/
		function BuscarPermisos()
		{
			$sql = "SELECT	
			                       E.razon_social AS Empresa,
							       E.empresa_id
						FROM  userpermisos_actastecnicas upat,
							       empresas E
					  WHERE upat.usuario_id =".UserGetUID()."
						   AND E.empresa_id = upat.empresa_id;";
						
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array();
			
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
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