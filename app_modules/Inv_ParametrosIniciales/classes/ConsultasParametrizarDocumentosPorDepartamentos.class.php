<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasAsignarDocumentosABodegas.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 
  
  
  
  class ConsultasParametrizarDocumentosPorDepartamentos extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasParametrizarDocumentosPorDepartamentos(){}
	
  
  
  
  function Listar_Empresas()
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              EM.razon_social AS Empresa,
              EM.direccion,
              EM.telefonos,
              d.departamento,
              m.municipio,
              EM.empresa_id
							FROM		
              empresas EM,
              tipo_dptos d,
              tipo_mpios m
							WHERE		
              EM.empresa_id = EM.empresa_id
              and
              m.tipo_mpio_id = EM.tipo_mpio_id
              and
              m.tipo_dpto_id = EM.tipo_dpto_id
              and
              m.tipo_pais_id = EM.tipo_pais_id
              and
              m.tipo_dpto_id = d.tipo_dpto_id
              and
              EM.sw_activa ='1'
              ";
		   
       
       
        
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
    
    function CentroUtilidadXEmpresa($EmpresaId)
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              centro_utilidad,
              descripcion
							FROM		
              centros_utilidad
              WHERE		
              empresa_id = '".$EmpresaId."';
              ";
		   
        
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
	
  
  function UnidadesFuncionalesXCentroUtilXEmpresa($EmpresaId,$CentroUtilidad)
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              unidad_funcional,
              descripcion,
              ubicacion
							FROM		
              unidades_funcionales
              WHERE		
              empresa_id = '".$EmpresaId."'
              and
              centro_utilidad = '".$CentroUtilidad."';
             
              ";
		   
        
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
  
 
   
 function Listar_Departamentos($Empresa_Id,$CentroUtilidad,$UnidadesFuncionales,$offset)
  {
  //$this->debug=true;
  $sql="
            select 
                  depto.departamento,
                  depto.descripcion,
                  depto.ubicacion
                    from 
                    departamentos depto
                          where
                          depto.empresa_id = '".$Empresa_Id."'
                          and
                          depto.centro_utilidad = '".$CentroUtilidad."'
                          and
                          depto.unidad_funcional = '".$UnidadesFuncionales."'
           
                 ";
 //$this->debug=true;
 
 
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "  order by depto.departamento ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
       
       
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
   
   
   
   function Listar_DepartamentosBuscados($Empresa_Id,$CentroUtilidad,$UnidadesFuncionales,$Departamento_Id,$Descripcion,$offset)
  {
  //$this->debug=true;
  $sql="
            select 
                  depto.departamento,
                  depto.descripcion,
                  depto.ubicacion
                    from 
                    departamentos depto
                          where
                          depto.empresa_id = '".$Empresa_Id."'
                          and
                          depto.centro_utilidad = '".$CentroUtilidad."'
                          and
                          depto.unidad_funcional = '".$UnidadesFuncionales."'
                          and
                          depto.departamento LIKE '%".$Departamento_Id."%'
                          and
                          depto.descripcion LIKE '%".$Descripcion."%'
        ";
 //$this->debug=true;
 
 
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "  order by depto.departamento ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
       
       
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
   
   
   function Listar_TiposDocumentosSinAsignar($Departamento,$offset)
  {
  //$this->debug=true;
  $sql="
            select 
                  tdoc.tipo_doc_general_id,
                  tdoc.descripcion,
                  tdoc.inv_tipo_movimiento
                      from 
                  tipos_doc_generales tdoc
                  where
                  tdoc.tipo_doc_general_id Not In
                  
                                          (
                                          select 
                                          dtdoc.tipo_doc_general_id
                                          from
                                          departamentos_tipos_doc_generales dtdoc
                                          where
                                          dtdoc.departamento = '".$Departamento."'
                                          )
                  and
                  tdoc.inv_tipo_movimiento IS NOT NULL
                 
                 ";
 //$this->debug=true;
 
 
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "  order by tdoc.descripcion ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
       
       
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
  
  
  
    function Listar_TiposDocumentosSinAsignarBuscados($Departamento,$TDocumentoId,$Descripcion,$TMovimiento,$offset)
  {
  //$this->debug=true;
  $sql="
            select 
                  tdoc.tipo_doc_general_id,
                  tdoc.descripcion,
                  tdoc.inv_tipo_movimiento
                      from 
                  tipos_doc_generales tdoc
                  where
                  tdoc.tipo_doc_general_id Not In
                  
                                          (
                                          select 
                                          dtdoc.tipo_doc_general_id
                                          from
                                          departamentos_tipos_doc_generales dtdoc
                                          where
                                          dtdoc.departamento = '".$Departamento."'
                                          )
                  and
                  tdoc.tipo_doc_general_id LIKE '%".$TDocumentoId."%'
                  and
                  tdoc.descripcion LIKE '%".$Descripcion."%'
                  and
                  tdoc.inv_tipo_movimiento LIKE '%".$TMovimiento."%' 
                  and
                  tdoc.inv_tipo_movimiento IS NOT NULL
                 
                 ";
 //$this->debug=true;
 
 
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "  order by tdoc.descripcion ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
       
       
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
  
  
  
  function AsignarTipoDocumentoADepartamentos($TipoDocGeneralId,$Departamento)
	{
	  $departamento_tipo_doc_general_id=$Departamento."".$TipoDocGeneralId;
    //$this->debug=true;
    $sql  = "INSERT INTO departamentos_tipos_doc_generales (";
    $sql .= "       departamento     , ";
    $sql .= "       tipo_doc_general_id     , ";
    $sql .= "       departamento_tipo_doc_general_id,";
    $sql .= "       estado";
    $sql .= "       ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$Departamento."',";
	    $sql .= "        '".$TipoDocGeneralId."',";
      $sql .= "        '".$departamento_tipo_doc_general_id."',";
      $sql .= "        '1'";
	    $sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
 


  function Listar_TiposDocumentosAsignados($Departamento,$offset)
  {
  //$this->debug=true;
  $sql="
            select 
                  tdocd.departamento_tipo_doc_general_id as codigo,
                  tdoc.tipo_doc_general_id,
                  tdoc.descripcion,
                  tdoc.inv_tipo_movimiento,
                  tdocd.estado
                      from 
                  tipos_doc_generales tdoc,
                  departamentos_tipos_doc_generales tdocd
                  where
                  tdocd.departamento = '".$Departamento."'
                  and
                  tdocd.tipo_doc_general_id = tdoc.tipo_doc_general_id
          ";
 //$this->debug=true;
 
 
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "  order by tdoc.descripcion ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
       
       
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
  
  
  
  
  function Listar_TiposDocumentosAsignadosBuscados($Departamento,$TDocumentoId,$Descripcion,$TMovimiento,$offset)
  {
  //$this->debug=true;
  $sql="
            select 
                  tdocd.departamento_tipo_doc_general_id as codigo,
                  tdoc.tipo_doc_general_id,
                  tdoc.descripcion,
                  tdoc.inv_tipo_movimiento,
                  tdocd.estado
                      from 
                  tipos_doc_generales tdoc,
                  departamentos_tipos_doc_generales tdocd
                  where
                  tdocd.departamento = '".$Departamento."'
                  and
                  tdocd.tipo_doc_general_id = tdoc.tipo_doc_general_id
                  and
                  tdoc.tipo_doc_general_id LIKE '%".$TDocumentoId."%'
                  and
                  tdoc.descripcion LIKE '%".$Descripcion."%'
                  and
                  tdoc.inv_tipo_movimiento LIKE '%".$TMovimiento."%' 
          ";
 //$this->debug=true;
 
 
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "  order by tdoc.descripcion ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
       
       
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