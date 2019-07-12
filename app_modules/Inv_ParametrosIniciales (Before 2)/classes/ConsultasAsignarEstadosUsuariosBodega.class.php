<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasEstadosDocumentos.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 
  
  
  
  class ConsultasEstadosDocumentos extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasEstadosDocumentos(){}
	
  
    /**********************************************************************************
		* Insertar una molécula en la base de datos. Datos enviados desde formulario de Moleculas
		* 
		* @return token
		************************************************************************************/
		
	function InsertarEstadoDocumento($datos)
	{
	  $sql  = "INSERT INTO inv_estados_documentos (";
    $sql .= "       abreviatura     , ";
	  $sql .= "       descripcion     , ";
	  $sql .= "       estado   ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$datos['abreviatura']."',";
	    $sql .= "        '".$datos['descripcion']."',";
	    $sql .= "        '".$datos['estado']."'";
      $sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
	
	
/**************************************************************************************
		* Busca si existe una Molécula con el código enviado desde formulario usuario
		* 
		* @return array
		***************************************************************************************/
		function Buscar_EstadoDocumento($Abreviatura)
		{
			//$this->debug=true;
    	$sql = "SELECT	
                    abreviatura,
                    descripcion
              FROM		
                  inv_estados_documentos
              WHERE		
                  abreviatura ='".$Abreviatura."';";
						
			
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}

	
      function ModificarEstadoDocumento($datos)
	{
	  
    //$this->debug=true;
    $sql  = "UPDATE inv_estados_documentos ";
    $sql .= "SET abreviatura = '".$datos['abreviatura']."',";
	  $sql .= "       descripcion   = '".$datos['descripcion']."'";
	  $sql .= " Where ";
    $sql .= "abreviatura ='".$datos['abreviatura_old']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
  
/**********************************************************************************
		* Insertar un Cambio de estado
		* 
		* @return token
		************************************************************************************/
		
	function InsertarCambioXEstadoDocumento($Abreviatura,$EstadoHijo)
	{
    $temp = $Abreviatura."".$EstadoHijo;
	  $sql  = "INSERT INTO inv_cambio_estados_documentos (";
    $sql .= "       cambio_estado_documento_id     , ";
	  $sql .= "       abreviatura     , ";
	  $sql .= "       cambio_a   ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$temp."',";
	  $sql .= "        '".$Abreviatura."',";
	  $sql .= "        '".$EstadoHijo."'";
      $sql .= "       ); ";			
		
    //$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    





	
	/*
	*	Funcion de Consulta SQL, que se encarga de buscar los diferentes estados no asignados
	*	Existentes en el sistema, segun un estado seleccionado.
	*/
		
  function Listar_CambiosNoAsignadosXEstado($Abreviatura)
  {
			$sql = "
              select 
                  ed.abreviatura,
                  ed.descripcion
                  from 
                  inv_estados_documentos ed
                  where
                  ed.abreviatura <>'".$Abreviatura."'
                  and
                  ed.abreviatura Not In
                                          (
                                          select 
                                          ced.cambio_a
                                          from
                                          inv_cambio_estados_documentos ced
                                          where
                                          ced.abreviatura = '".$Abreviatura."'
                                          ) 
                  and
                  ed.estado ='1'
                  order by ed.descripcion;";
						/* '".$Abreviatura."' NOT IN(
                             select
                                        cambio_a
                                        from
                                        inv_cambio_estados_documentos
                                        where
                                        abreviatura = '".Abreviatura."')*/
			
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
  
  
  
  /*
	*	Funcion de Consulta SQL, que se encarga de buscar los diferentes estados asignados
	*	Existentes en el sistema, segun un estado seleccionado.
	*/
		
  function Listar_CambiosAsignadosXEstado($Abreviatura)
  {
			$sql = "
               SELECT 
                    ced.cambio_estado_documento_id as codigo,
                    ed.abreviatura,
                    ed.descripcion,
                    ed.estado
                    FROM 
                        inv_estados_documentos ed,
                        inv_cambio_estados_documentos ced
                    where
                    '".$Abreviatura."' = ced.abreviatura
                    and
                    ced.cambio_a = ed.abreviatura
                    and
                    ed.estado = '1'; 
      
               ";
						
			
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
  
  
  
  
  
  
  
  /*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
		function Listar_EstadosDocumentos($offset)
		{
			$sql = "SELECT 
                    abreviatura,
                    descripcion,
                    estado
                    FROM 
                    inv_estados_documentos ";
		
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
       
    $sql .= " ORDER BY estado DESC,abreviatura ";
      $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";

    
		if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    
    
    function Listar_EstadosDocumentos_()
		{
			$sql = "SELECT 
                    abreviatura,
                    descripcion,
                    estado
                    FROM 
                    inv_estados_documentos
                    where
                    estado='1'";
		                $sql .= "ORDER BY abreviatura; ";
       
		if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	
  
  function Listar_Empresas()
		{
				$sql = "SELECT	EM.razon_social AS Empresa,
							EM.empresa_id
							FROM		empresas EM
							WHERE		EM.sw_activa ='1';";
						
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
	
	
	}
	
?>