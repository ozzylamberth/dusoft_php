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
			$sql = "SELECT	EM.razon_social AS Empresa,
							EM.empresa_id
							FROM		userpermisos_parametros_iniciales_inventarios UPPI,
							        empresas EM
							WHERE		UPPI.usuario_id =".UserGetUID()."
							AND 		EM.empresa_id = UPPI.empresa_id;";
						
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
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