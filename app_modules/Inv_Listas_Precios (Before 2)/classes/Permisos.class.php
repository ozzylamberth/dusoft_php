<?php
  class Permisos extends ConexionBD
  {
    /**
    * Constructor
    */
    function Permisos(){}
		
    /**************************************************************************************
	* Busca los puntos de admision del modulo a los que tiene permiso el usuario
	* 
	* @return array
	***************************************************************************************/
    function BuscarBodegasyCentros($empresa){
        $sql = "
             SELECT 
                  centro_utilidad,
                  bodega
             FROM
                  bodegas
             WHERE
                  empresa_id = '$empresa'                                
		";
        //echo $sql;
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();

        while(!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

      function verificarCantidadBodegasyCentros($empresa)
      {
          $sql = "
             SELECT 
                (SELECT 
                    count(DISTINCT centro_utilidad)
                FROM
                    bodegas
                WHERE
                    empresa_id = '$empresa')
                AS cantidad_centros,
                
                (SELECT 
                    count(DISTINCT bodega)
                FROM
                    bodegas
                WHERE
                    empresa_id = '$empresa')
                AS cantidad_bodegas                
		  ";
          //echo $sql;
          if(!$rst = $this->ConexionBaseDatos($sql))
              return false;
          $datos = array();
          while(!$rst->EOF)
          {
              $datos = $rst->GetRowAssoc($ToUpper = false);
              $rst->MoveNext();
          }
          $rst->Close();

          $datos['cantidad_centros'] = (int)$datos['cantidad_centros'];
          $datos['cantidad_bodegas'] = (int)$datos['cantidad_bodegas'];
          return $datos;
      }

		function BuscarPermisos()
		{
			$sql = "
              SELECT 
                EM.razon_social AS Empresa,
				EM.empresa_id
			  FROM
			    userpermisos_listas_precios lp,
				empresas EM
			  WHERE
			    lp.usuario_id =".UserGetUID()."
			  AND  EM.empresa_id = lp.empresa_id;";
						
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