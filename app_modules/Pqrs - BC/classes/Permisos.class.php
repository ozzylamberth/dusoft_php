<?php
  class Permisos extends ConexionBD
  {
    /************************
    *     Constructor
    ************************/
    function Permisos(){}
		
    /**************************************************************************************
	* Busca las empresas a las que tiene permiso el usuario
	* @return array
	***************************************************************************************/
	function BuscarPermisos()
	{
		$sql = "SELECT	
							   e.razon_social AS Empresa,
							   e.empresa_id
					FROM  userpermisos_pqrs up,
							   empresas e
				  WHERE up.usuario_id =".UserGetUID()."
					   AND e.empresa_id = up.empresa_id ORDER BY e.razon_social ASC;";
					
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

    /**************************************************************************************
	* Buscar bodegas de la empresa
	* @param: $empresa
	* @return array
	***************************************************************************************/
    function BuscarBodegas($empresa)
	{
	 $sql  = "SELECT descripcion, bodega FROM bodegas ";
	 $sql .= " WHERE empresa_id ='".$empresa."' ";
	 $sql .= "ORDER BY 1 ";
	
	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $bod = array();
     while(!$rst->EOF)
     {
	  $bod[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     
     $rst->Close();
	 
	 return $bod;
	}
   
    /**************************************************************************************
	* Listado usuarios empresa
	* @param: $empresa
	* @return array
	***************************************************************************************/
    function BuscarUsuarioFarm($empresa,$bodega)
	{
	 $sql  = "SELECT usuario_id,nombre,descripcion FROM system_usuarios_farmacias ";
	 $sql .= " WHERE empresa_id ='".$empresa."' ";
	 $sql .= " AND       bodega ='".$bodega."' ";
	 $sql .= "ORDER BY 1 ";
	
	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $usr = array();
     while(!$rst->EOF)
     {
	  $usr[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     
     $rst->Close();
	 
	 return $usr;
	}

    /**************************************************************************************
	* Listado de empresas sin filtros
	* @return array
	***************************************************************************************/
    function ListarEmpresas($empresa)
	{
	 $sql  = "SELECT razon_social FROM empresas WHERE sw_tipo_empresa ='0' ";
	 $sql .= " AND empresa_id = '".$empresa."' ";

	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $emp = array();
     while(!$rst->EOF)
     {
	  $emp = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     
     $rst->Close();
	 
	 return $emp;
	}    

    /**************************************************************************************
	* Listado de las categorias de los casos reportados PRQS
	* @return array
	***************************************************************************************/
    function ListarCategorias()
	{
	 $sql  = "SELECT categoria_id, tipo_categoria,descripcion FROM pqrs_categoria_casos ORDER BY 1 ";

	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $cat = array();
     while(!$rst->EOF)
     {
	  $cat[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     
     $rst->Close();
	 
	 return $cat;
	}    

    /**************************************************************************************
	* Listado de las categorias de los casos reportados PRQS
	* @return array
	***************************************************************************************/
    function EstadoCasos()
	{
	 $sql  = "SELECT estado_caso_id, estado,descripcion FROM pqrs_estado_casos ORDER BY 1 ";

	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $estado = array();
     while(!$rst->EOF)
     {
	  $estado[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     
     $rst->Close();
	 
	 return $estado;
	}    
	
    /**************************************************************************************
	* Listado de los tipos de fuerza
	* @return array
	***************************************************************************************/
    function ListadoFuerzas()
	{
	 $sql  = "SELECT codigo_fuerza,descripcion FROM esm_tipos_fuerzas ORDER BY 1 ";

	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $fuerza = array();
     while(!$rst->EOF)
     {
	  $fuerza[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     
     $rst->Close();
	 
	 return $fuerza;
	}    	
	
	
    /**************************************************************************************
	* Obtener numero temporal de consecutivo de nuevo caso Pqrs
	* @return array
	***************************************************************************************/
    function SerialCaso()
	{
	 $sql  = "SELECT last_value AS consecutivo FROM esm_registro_pqrs_registro_pqrs_id_seq ";
     
	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $cons = array();
     while(!$rst->EOF)
     {
	  $cons = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     
     $rst->Close();
	 
	 return $cons;
	}    		

    /**************************************************************************************
	* Nombre empresa 
	* @return array
	***************************************************************************************/
    function ListarEmpresa($empresa)
	{
	 $sql  = "SELECT razon_social FROM empresas WHERE sw_tipo_empresa ='0' AND empresa_id = '".$empresa."' ";

	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $emp = array();
     while(!$rst->EOF)
     {
	  $emp = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     
     $rst->Close();
	 
	 return $emp;
	}  

	
	
  }
?>