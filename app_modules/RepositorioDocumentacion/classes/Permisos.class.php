<?php
  class Permisos extends ConexionBD
  {
    /************************
    * Constructor
    ************************/
    function Permisos(){}
		
    /**************************************************************************************
	* Busca las empresas a las que tiene permiso el usuario
	* @return array
	***************************************************************************************/
		function BuscarPermisos($usuario)
		{
			$sql = "SELECT	e.razon_social AS Empresa, e.empresa_id
				FROM  userpermisos_repositorio AS ur, empresas AS e
                                WHERE ur.usuario_id ={$usuario} AND e.empresa_id = ur.empresa_id 
                                ORDER BY e.razon_social ASC;";
						
                        /*var_dump($sql);
                        exit();*/
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
	* Permiso de descarga de documentos
	* @return array
	***************************************************************************************/
		function DownloadRule()
		{
			$sql = "SELECT	
			                       ur.download
						FROM  userpermisos_repositorio ur
					  WHERE ur.usuario_id =".UserGetUID()."
						   AND ur.download = '1' GROUP BY 1;";		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
             
			$per = array();
			while(!$rst->EOF)
			{
				$per = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $per;
		}
		
    /**************************************************************************************
	* Buscar bodegas de la empresa
	* 
	* @return array
	***************************************************************************************/
    function BuscarBodegas($empresa)
	{
	 $sql  = "SELECT b.descripcion AS descripcion, b.bodega AS bodega FROM bodegas b, userpermisos_repositorio ur ";
	 $sql .= " WHERE b.empresa_id ='".$empresa."' ";
	 $sql .= "      AND ur.bodega = b.bodega ";
	 $sql .= "      AND ur.usuario_id = ".UserGetUID()." ";
	
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
	* Listado de empresas con filtro
	* @return array
	***************************************************************************************/
    function ListarEmpresas($empresa)
	{
	//$user = UserGetUID();
	//$subQUery = "(SELECT distinct(e.empresa_id) FROM  userpermisos_repositorio AS ur, empresas AS e WHERE ur.usuario_id =".$user." AND e.empresa_id = ur.empresa_id )";
	 $sql  = "SELECT razon_social, empresa_id FROM empresas ";
	 $sql .= "WHERE  empresa_id = '".$empresa."' AND sw_activa = '1' AND (sw_tipo_empresa ='0' OR sw_tipo_empresa = '1') ";
	 $sql .= " ORDER BY 1";
	/* $sql  = "SELECT razon_social, empresa_id FROM empresas ";
	 $sql .= "WHERE  empresa_id IN ".$subQUery."AND sw_activa = '1' AND (sw_tipo_empresa ='0' OR sw_tipo_empresa = '1') ";
	 $sql .= " ORDER BY 1";*/

	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $emp = array();
     while(!$rst->EOF)
     {
	  $emp[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     
     $rst->Close();
	 
	 return $emp;
	}    

    /**************************************************************************************
	* Listado de empresas sin filtro
	* @return array
	***************************************************************************************/
    function AllEmpresas()
	{
	 $sql  = "SELECT razon_social, empresa_id FROM empresas ";
	 $sql .= "WHERE sw_activa = '1' AND (sw_tipo_empresa ='0' OR sw_tipo_empresa = '1') ";
	 $sql .= " ORDER BY 1";

	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $emp = array();
     while(!$rst->EOF)
     {
	  $emp[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     
     $rst->Close();
	 
	 return $emp;
	} 
	
	
    /**************************************************************************************
	* Listado de centros de utilidad
	*@param: $empresa
	* @return array
	***************************************************************************************/
    function Listar_CU($empresa)
	{
	 $sql  = "SELECT descripcion, centro_utilidad FROM centros_utilidad WHERE empresa_id ='".$empresa."' ORDER BY 1";

	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $cu = array();
     while(!$rst->EOF)
     {
	  $cu[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     
     $rst->Close();
	 
	 return $cu;
	}    


    /**************************************************************************************
	* Listado departamentos empresa -filtro empresa
	*@param: $empresa
	* @return array
	***************************************************************************************/
    function ListarDptos($empresa)
	{
	 $sql  = "SELECT descripcion, departamento FROM departamentos WHERE empresa_id ='".$empresa."' ORDER BY 1";
     
	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $dpto = array();
     while(!$rst->EOF)
     {
	  $dpto[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     
     $rst->Close();
	 
	 return $dpto;
	}    

    /**************************************************************************************
	* Listado bodegas empresa - filtro empresa, centro utilidad
	*@param: $empresa
	* @return array
	***************************************************************************************/
    function ListarBodegas($cu,$empresa,$bodega)
	{
	 $sql  = "SELECT descripcion,bodega FROM bodegas WHERE empresa_id ='".$empresa."' ";
	 $sql .= " AND centro_utilidad ='".$cu."' AND bodega ='".$bodega."' ORDER BY 1";
     
	 
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
	* Listado bodegas empresa - filtro empresa, bodega
	*@param: $empresa
	* @return array
	***************************************************************************************/
    function ListarBodegasAll($empresa,$bodega)
	{
	 $sql  = "SELECT descripcion,bodega FROM bodegas WHERE empresa_id ='".$empresa."' ";
	 $sql .= "      AND bodega ='".$bodega."'  ORDER BY 1 ";
     
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
	* Listado todas las bodegas empresa - filtro empresa
	*@param: $empresa
	* @return array
	***************************************************************************************/
    function ListarTodasBodegas($empresa)
	{
	 $sql  = "SELECT descripcion,bodega FROM bodegas WHERE empresa_id ='".$empresa."'  ";
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
	* Listado tipos identificacion
	*@param: 
	* @return array
	***************************************************************************************/
    function GetTipoId()
	{
	 $sql  = "SELECT descripcion,tipo_id_paciente FROM tipos_id_pacientes ";
	 $sql .= " ORDER BY 1 ";
     
	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $ids = array();
     while(!$rst->EOF)
     {
	  $ids[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     
     $rst->Close();
	 
	 return $ids;
	}  	
	
	
    /**************************************************************************************
	*Obtener nombre del tipo de documento a subir al repositorio
	*@param: $tipoDoc 
	*@return array
	***************************************************************************************/
    function GetTipoArch_upload($tipoDoc)
	{
	 $sql  = "SELECT tipo_nombre FROM tipo_archivos_repositorio WHERE tipo_archivo_id =".$tipoDoc;
	      
	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $tdoc = array();
     while(!$rst->EOF)
     {
	  $tdoc = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     $rst->Close();
	 return $tdoc;
	}   


    /**************************************************************************************
	*Obtener tipos de documentos para los que se efectua facturacion
	*@param: 
	*@return array
	***************************************************************************************/
    function GetTipoFac($cod_tipo)
	{
	
	  if($cod_tipo == 5)
	  {
	   $sql  = "SELECT tipo_archivo_id,tipo_nombre FROM tipo_archivos_repositorio WHERE sw_facturar = '1'  ORDER BY 1";//AND tipo_nombre NOT IN ('SELECTIVO')
	  }
	  
	  else 
	   {
	    $sql  = "SELECT tipo_archivo_id,tipo_nombre FROM tipo_archivos_repositorio ";
		$sql .= " WHERE tipo_archivo_id IN ('3','4','5','7','10','11','12','13','14') ORDER BY 1"; //para subir informes q no sean de este tipo
	   }
	   
	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $tdoc = array();
     while(!$rst->EOF)
     {
	  $tdoc[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     $rst->Close();
	 return $tdoc;
	}   

	
	/**************************************************************************************
	*Obtener tipos de informe
	*@param: 
	*@return array
	@fecha 08/01/2016
	***************************************************************************************/
    function GetTipoInforme($cod_tipo)
	{
	
	  if($cod_tipo == 5)
	  {
	   $sql  = "SELECT tipo_archivo_id,tipo_nombre FROM tipo_archivos_repositorio WHERE sw_facturar = '1'  ORDER BY 1";//AND tipo_nombre NOT IN ('SELECTIVO')
	  }
	  
	  else 
	   {
	    $sql  = "SELECT tipo_archivo_id,tipo_nombre FROM tipo_archivos_repositorio ";
		$sql .= " WHERE tipo_archivo_id IN ('3','5','11','12','13','14') ORDER BY 1"; //para subir informes q no sean de este tipo
	   }
	   
	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $tdoc = array();
     while(!$rst->EOF)
     {
	  $tdoc[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     $rst->Close();
	 return $tdoc;
	}   
	
	
    /**************************************************************************************
	* Obtener los campos del tipo de documento a subir en el repositorio
	*@param: $tipoDoc [campos para inputs del form ppal]
	* @return array
	***************************************************************************************/
    function GetFields_tipo($tipoArch)
	{
	 $sql  = "SELECT campo FROM tipo_campos_tbl_repositorio WHERE tipo_archivo =".$tipoArch;
	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $cam = array();
     while(!$rst->EOF)
     {
	  $cam[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     $rst->Close();
	 return $cam;
	}   
	

	 /**************************************************************************************
	*+Descripcion: Obtener nombre del tipo de producto
	*@return array
	*fecha 14/12/2015
	*@author Cristian Ardila
	***************************************************************************************/
    function GetTipoProducto()
	{
	
	   $sql  = "SELECT tipo_producto_id, descripcion FROM inv_tipo_producto";

	 if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
		
	 $tdoc = array();
     while(!$rst->EOF)
     {
	  $tdoc[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }	 
     $rst->Close();
	 return $tdoc;
	}   

	
  }
?>