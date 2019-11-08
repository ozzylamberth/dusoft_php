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
  
  
  
  class ConsultasAsignarDocumentosABodegas extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasAsignarDocumentosABodegas(){}
	
  
  
  
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
	
  
  function BodegasXCentroUtilXEmpresa($EmpresaId,$CentroUtilidad)
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              bodega,
              descripcion
							FROM		
              bodegas
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
  
  
 
 function Buscar_FarmaciaTiempoEntrega($Empresa_Id)
		{
			//$this->debug=true;
    	$sql = "SELECT	
              tiempo_entrega
							From
              inv_farmacias_tiempoentrega_medicamentos
              WHERE		
              empresa_id = '".$Empresa_Id."'
              ;";
						
			
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
   
   
   
   
   
   
   
   function Listar_Documentos($Empresa_Id,$CentroUtilidad,$Bodega,$offset)
  {
  //$this->debug=true;
  $sql="
            select 
                  doc.documento_id,
                  doc.descripcion,
                  doc.tipo_doc_general_id,
                  tdg.descripcion as tipo_documento,
                  doc.prefijo
                  from 
                  documentos doc,
                  tipos_doc_generales tdg
                  where
                  doc.empresa_id = '".$Empresa_Id."'
                  and
                  doc.documento_id Not In
                                          (
                                          select 
                                          bd.documento_id
                                          from
                                          inv_bodegas_documentos bd
                                          where
                                          bd.empresa_id = '".$Empresa_Id."'
                                          and
                                          bd.centro_utilidad = '".$CentroUtilidad."'
                                          and
                                          bd.bodega = '".$Bodega."'
                                          ) 
                                          and 
                                          doc.sw_estado = '1'
                                          and
                                          doc.tipo_doc_general_id = tdg.tipo_doc_general_id
                                          
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

    $sql .= "  order by doc.descripcion ";
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
  
  
   function Listar_DocumentosBuscados($Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
  //$this->debug=true;
  $sql="
            select 
                  doc.documento_id,
                  doc.descripcion,
                  doc.tipo_doc_general_id,
                  tdg.descripcion as tipo_documento,
                  doc.prefijo
                  from 
                  documentos doc,
                  tipos_doc_generales tdg
                  where
                  doc.empresa_id = '".$Empresa_Id."'
                  and
                  doc.descripcion LIKE '%".$Descripcion."%'
                  and
                  doc.prefijo LIKE '%".$Prefijo."%'
                  and
                  doc.documento_id Not In
                                          (
                                          select 
                                          bd.documento_id
                                          from
                                          inv_bodegas_documentos bd
                                          where
                                          bd.empresa_id = '".$Empresa_Id."'
                                          and
                                          bd.centro_utilidad = '".$CentroUtilidad."'
                                          and
                                          bd.bodega = '".$Bodega."'
                                          ) 
                                          and 
                                          doc.sw_estado = '1'
                                          and
                                          doc.tipo_doc_general_id = tdg.tipo_doc_general_id
                                          and
                                          doc.documento_id LIKE '%".$Documento_Id."%'
                                          
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

    $sql .= "  order by doc.descripcion ";
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
  
  
    function Listar_DocumentosAsignadoABodega($Empresa_Id,$CentroUtilidad,$Bodega,$offset)
  {
  //$this->debug=true;
  $sql="
            select 
                  ibd.bodegas_doc_id,
                  ibd.sw_estado,
                  doc.documento_id,
                  doc.descripcion,
                  doc.tipo_doc_general_id,
                  tdg.descripcion as tipo_documento,
                  doc.prefijo
                  from 
                  inv_bodegas_documentos ibd,
                  documentos doc,
                  tipos_doc_generales tdg
                  where
                  ibd.empresa_id = '".$Empresa_Id."'
                  and
                  ibd.centro_utilidad = '".$CentroUtilidad."'
                  and
                  ibd.bodega = '".$Bodega."'
                  and
                  ibd.documento_id = doc.documento_id
                  and
                  doc.sw_estado = '1'
                  and
                  doc.tipo_doc_general_id = tdg.tipo_doc_general_id
                                          
                 ";
 
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "  order by doc.descripcion ";
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
  
  function Listar_DocumentosAsignadoABodegaBuscados($Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
  //$this->debug=true;
  $sql="
            select 
                  ibd.bodegas_doc_id,
                  ibd.sw_estado,
                  doc.documento_id,
                  doc.descripcion,
                  doc.tipo_doc_general_id,
                  tdg.descripcion as tipo_documento,
                  doc.prefijo
                  from 
                  inv_bodegas_documentos ibd,
                  documentos doc,
                  tipos_doc_generales tdg
                  where
                  ibd.empresa_id = '".$Empresa_Id."'
                  and
                  ibd.centro_utilidad = '".$CentroUtilidad."'
                  and
                  ibd.bodega = '".$Bodega."'
                  and
                  ibd.documento_id LIKE '%".$Documento_Id."%'
                  and
                  ibd.documento_id = doc.documento_id
                  and
                  doc.descripcion LIKE '%".$Descripcion."%'
                  and
                  doc.prefijo LIKE '%".$Prefijo."%'
                  and
                  doc.sw_estado = '1'
                  and
                  doc.tipo_doc_general_id = tdg.tipo_doc_general_id
                                          
                 ";
 
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "  order by doc.descripcion ";
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
  
  
  
  
		
	function AsignarDocumentoABodega($Documento_Id,$Empresa_Id,$CentroUtilidad,$Bodega)
	{
	  //$this->debug=true;
    $sql  = "INSERT INTO inv_bodegas_documentos (";
    $sql .= "       documento_id     , ";
    $sql .= "       empresa_id     , ";
    $sql .= "       centro_utilidad     , ";
    $sql .= "       bodega     , ";
	  $sql .= "       sw_estado      ";
	  $sql .= "       ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$Documento_Id."',";
	    $sql .= "        '".$Empresa_Id."',";
      $sql .= "        '".$CentroUtilidad."',";
	    $sql .= "        '".$Bodega."',";
      $sql .= "        '1'";
      $sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    
 function Listar_UsuariosSinDocumentosBodegas($Empresa_Id,$CentroUtilidad,$Bodega,$offset)
		{
				/*$this->debug=true;*/
       
      
$sql ="
       select 
                  U.usuario_id,
                  U.nombre,
                  U.descripcion
                  from 
                  system_usuarios U,
                  system_usuarios_empresas SUE
                  where
                  SUE.empresa_id = '".$Empresa_Id."'
                  and
                  SUE.sw_activo = '1'
                  and
                  SUE.usuario_id = U.usuario_id
                  ";


      /*$sql ="
       select 
                  U.usuario_id,
                  U.nombre,
                  U.descripcion
                  from 
                  system_usuarios U,
                  system_usuarios_empresas SUE
                  where
                  SUE.empresa_id = '".$Empresa_Id."'
                  and
                  SUE.sw_activo = '1'
                  and
                  SUE.usuario_id = U.usuario_id
                  and
                  U.usuario_id Not In
                                          (
                                          SELECT	
                                                  IBU.usuario_id
                                                  FROM		
                                                  inv_bodegas_userpermisos IBU
                                                  WHERE		
                                                  IBU.empresa_id ='".$Empresa_Id."' 
                                                  and
                                                  IBU.centro_utilidad = '".$CentroUtilidad."'
                                                  and
                                                  IBU.bodega = '".$Bodega."'
                                             )
      
                    ";*/
                                          



/*
       $sql = "SELECT	
                      U.usuario_id,
                      U.nombre,
                      U.descripcion
                FROM		
                      inv_bodegas_userpermisos IBU,
                      system_usuarios U
							WHERE		
                      IBU.empresa_id ='".$Empresa_Id."' 
                      and
                      IBU.centro_utilidad = '".$CentroUtilidad."'
                      and
                      IBU.bodega = '".$Bodega."'
                      AND
                      IBU.usuario_id = U.usuario_id
                       
                      ";
      $sql .= " group BY U.usuario_id,U.nombre,U.descripcion ";                */
			
     if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
       
      
      $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";

 


			
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
    
    
    
    function Listar_UsuariosSinDocumentosBodegasBuscados($Empresa_Id,$CentroUtilidad,$Bodega,$Usuario_Id,$Nombre,$Descripcion,$offset)
		{
				/*$this->debug=true;*/
       
       
       $sql = "select 
                  U.usuario_id,
                  U.nombre,
                  U.descripcion
                  from 
                  system_usuarios U,
                  system_usuarios_empresas SUE
                  where
                  SUE.empresa_id = '".$Empresa_Id."'
                  and SUE.sw_activo = '1'
                  and SUE.usuario_id::integer = U.usuario_id
                  and U.usuario_id::VARCHAR ILIKE '%".$Usuario_Id."%'
                  and U.nombre ILIKE '%".$Nombre."%'
                  and U.descripcion ILIKE '%".$Descripcion."%' 
                  ";
       
       
      /* $sql ="
       select 
                  U.usuario_id,
                  U.nombre,
                  U.descripcion
                  from 
                  system_usuarios U,
                  system_usuarios_empresas SUE
                  where
                  SUE.empresa_id = '".$Empresa_Id."'
                  and
                  SUE.sw_activo = '1'
                  and
                  SUE.usuario_id = U.usuario_id
                  and
                  SUE.usuario_id Not In
                                          (
                                          SELECT	
                                                  IBU.usuario_id
                                                  FROM		
                                                  inv_bodegas_userpermisos IBU
                                                  WHERE		
                                                  IBU.empresa_id ='".$Empresa_Id."' 
                                                  and
                                                  IBU.centro_utilidad = '".$CentroUtilidad."'
                                                  and
                                                  IBU.bodega = '".$Bodega."'
                                             )
                  and
                  U.usuario_id LIKE '%".$Usuario_Id."%'
                  and
                  U.nombre LIKE '%".$Nombre_Id."%'
                  and
                  U.descripcion LIKE '%".$Descripcion."%' ";
        */                                  



/*
       $sql = "SELECT	
                      U.usuario_id,
                      U.nombre,
                      U.descripcion
                FROM		
                      inv_bodegas_userpermisos IBU,
                      system_usuarios U
							WHERE		
                      IBU.empresa_id ='".$Empresa_Id."' 
                      and
                      IBU.centro_utilidad = '".$CentroUtilidad."'
                      and
                      IBU.bodega = '".$Bodega."'
                      AND
                      IBU.usuario_id = U.usuario_id
                       
                      ";
      $sql .= " group BY U.usuario_id,U.nombre,U.descripcion ";                */
			
     if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
       
      
      $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";

 


			
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
  
	
	
  
  
  function GuardarDocumentoUsuarioBodega($DocumentoId,$Usuario_id,$Empresa_Id,$CentroUtilidad,$Bodega)
	{
	  //$this->debug=true;
    $sql  = "INSERT INTO inv_bodegas_userpermisos (";
    $sql .= "       documento_id     , ";
    $sql .= "       empresa_id     , ";
    $sql .= "       centro_utilidad     , ";
    $sql .= "       bodega     , ";
	  $sql .= "       usuario_id      ";
	  $sql .= "       ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$DocumentoId."',";
	    $sql .= "        '".$Empresa_Id."',";
      $sql .= "        '".$CentroUtilidad."',";
	    $sql .= "        '".$Bodega."',";
      $sql .= "        '".$Usuario_id."'";
      $sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
  
     function Listar_DocumentosAsignadoABodegaxUsuario($Usuario,$Empresa_Id,$CentroUtilidad,$Bodega,$offset)
  {
  //$this->debug=true;
  $sql="
             select 
                  ibd.bodegas_doc_id,
                  doc.documento_id,
                  doc.descripcion,
                  doc.tipo_doc_general_id,
                  tdg.descripcion as tipo_documento,
                  doc.prefijo
                  from 
                  inv_bodegas_documentos ibd,
                  documentos doc,
                  tipos_doc_generales tdg
                  where
                  ibd.empresa_id = '".$Empresa_Id."'
                  and
                  ibd.centro_utilidad = '".$CentroUtilidad."'
                  and
                  ibd.bodega = '".$Bodega."'
                  and
                  ibd.documento_id Not In
                                          (
                                          SELECT	
                                                  IBU.documento_id
                                                  FROM		
                                                  inv_bodegas_userpermisos IBU
                                                  WHERE		
                                                  IBU.empresa_id ='".$Empresa_Id."' 
                                                  and
                                                  IBU.centro_utilidad = '".$CentroUtilidad."'
                                                  and
                                                  IBU.bodega = '".$Bodega."'
                                                  and
                                                  IBU.usuario_id = '".$Usuario."'
                                             )
                  and
                  ibd.documento_id = doc.documento_id
                  and
                  doc.sw_estado = '1'
                  and
                  doc.tipo_doc_general_id = tdg.tipo_doc_general_id
                                          
                 ";
  
  
  /*$sql="
            select 
                  ibd.bodegas_doc_id,
                  ibd.sw_estado,
                  doc.documento_id,
                  doc.descripcion,
                  doc.tipo_doc_general_id,
                  tdg.descripcion as tipo_documento,
                  doc.prefijo
                  from 
                  inv_bodegas_documentos ibd,
                  documentos doc,
                  tipos_doc_generales tdg
                  where
                  ibd.empresa_id = '".$Empresa_Id."'
                  and
                  ibd.centro_utilidad = '".$CentroUtilidad."'
                  and
                  ibd.bodega = '".$Bodega."'
                  and
                  ibd.documento_id = doc.documento_id
                  and
                  doc.sw_estado = '1'
                  and
                  doc.tipo_doc_general_id = tdg.tipo_doc_general_id
                                          
                 ";*/
 
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "  order by doc.descripcion ";
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
  
  
  
  
  
   function Listar_DocumentosAsignadoABodegaxUsuarioBuscados($UsuarioId,$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
 // $this->debug=true;
  $sql="
             select 
                  ibd.bodegas_doc_id,
                  ibd.sw_estado,
                  doc.documento_id,
                  doc.descripcion,
                  doc.tipo_doc_general_id,
                  tdg.descripcion as tipo_documento,
                  doc.prefijo
                  from 
                  inv_bodegas_documentos ibd,
                  documentos doc,
                  tipos_doc_generales tdg
                  where
                  ibd.empresa_id = '".$Empresa_Id."'
                  and
                  ibd.centro_utilidad = '".$CentroUtilidad."'
                  and
                  ibd.bodega = '".$Bodega."'
                  and
                  ibd.documento_id Not In
                                          (
                                          SELECT	
                                                  IBU.documento_id
                                                  FROM		
                                                  inv_bodegas_userpermisos IBU
                                                  WHERE		
                                                  IBU.empresa_id ='".$Empresa_Id."' 
                                                  and
                                                  IBU.centro_utilidad = '".$CentroUtilidad."'
                                                  and
                                                  IBU.bodega = '".$Bodega."'
                                                  and
                                                  IBU.usuario_id = '".$UsuarioId."'
                                                  )
                  and
                  ibd.documento_id = doc.documento_id
                  and
                  doc.sw_estado = '1'
                  and
                  doc.documento_id ILIKE '%".$Documento_Id."%'
                  and
                  doc.descripcion ILIKE '%".$Descripcion."%'
                  and
                  doc.prefijo ILIKE '%".$Prefijo."%'
                  and
                  doc.tipo_doc_general_id = tdg.tipo_doc_general_id
                                          
                 ";
  
  
  /*$sql="
           select 
                  ibd.bodegas_doc_id,
                  ibd.sw_estado,
                  doc.documento_id,
                  doc.descripcion,
                  doc.tipo_doc_general_id,
                  tdg.descripcion as tipo_documento,
                  doc.prefijo
                  from 
                  inv_bodegas_userpermisos ibu,
                  inv_bodegas_documentos ibd,
                  documentos doc,
                  tipos_doc_generales tdg
                  where
                  ibu.empresa_id = '".$Empresa_Id."'
                  and
                  ibu.centro_utilidad = '".$CentroUtilidad."'
                  and
                  ibu.bodega = '".$Bodega."'
                  and
                  ibu.documento_id LIKE '%".$Documento_Id."%'
                  and
                  ibu.usuario_id = '".$UsuarioId."'
                  and
                  ibu.documento_id = ibd.documento_id
                  and
                  ibd.documento_id = doc.documento_id
                  and
                  doc.descripcion LIKE '%".$Descripcion."%'
                  and
                  doc.prefijo LIKE '%".$Prefijo."%'
                  and
                  doc.sw_estado = '1'
                  and
                  doc.tipo_doc_general_id = tdg.tipo_doc_general_id
                                          
                 
                                          
                 ";*/
 //$this->debug=true;
 
 
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "  order by doc.descripcion ";
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
  
 


function Listar_DocumentosAsignadoUsuario($Usuario,$Empresa_Id,$CentroUtilidad,$Bodega,$offset)
  {
  //$this->debug=true;
  $sql="
                          SELECT	
                                doc.documento_id,
                                doc.descripcion,
                                doc.tipo_doc_general_id,
                                tdg.descripcion as tipo_documento,
                                doc.prefijo,
                                IBU.documento_id
                                FROM		
                                inv_bodegas_userpermisos IBU,
                                inv_bodegas_documentos ibd,
                                documentos doc,
                                tipos_doc_generales tdg
                                WHERE		
                                IBU.empresa_id ='".$Empresa_Id."' 
                                and
                                IBU.centro_utilidad = '".$CentroUtilidad."'
                                and
                                IBU.bodega = '".$Bodega."'
                                and
                                IBU.usuario_id = '".$Usuario."'
                                and
                                IBU.documento_id = ibd.documento_id
                                and
                                ibd.empresa_id ='".$Empresa_Id."' 
                                and
                                ibd.centro_utilidad = '".$CentroUtilidad."'
                                and
                                ibd.bodega = '".$Bodega."'
                                and
                                ibd.documento_id = doc.documento_id
                                and
                                doc.tipo_doc_general_id = tdg.tipo_doc_general_id
             
                                          
                 ";
  
  
  /*$sql="
            select 
                  ibd.bodegas_doc_id,
                  ibd.sw_estado,
                  doc.documento_id,
                  doc.descripcion,
                  doc.tipo_doc_general_id,
                  tdg.descripcion as tipo_documento,
                  doc.prefijo
                  from 
                  inv_bodegas_documentos ibd,
                  documentos doc,
                  tipos_doc_generales tdg
                  where
                  ibd.empresa_id = '".$Empresa_Id."'
                  and
                  ibd.centro_utilidad = '".$CentroUtilidad."'
                  and
                  ibd.bodega = '".$Bodega."'
                  and
                  ibd.documento_id = doc.documento_id
                  and
                  doc.sw_estado = '1'
                  and
                  doc.tipo_doc_general_id = tdg.tipo_doc_general_id
                                          
                 ";*/
 
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "  order by doc.descripcion ";
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
  
 
  
  
  
  
  function Listar_DocumentosAsignadoUsuarioBuscados($UsuarioId,$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
 //$this->debug=true;
  $sql="
             SELECT	
                                doc.documento_id,
                                doc.descripcion,
                                doc.tipo_doc_general_id,
                                tdg.descripcion as tipo_documento,
                                doc.prefijo,
                                IBU.documento_id
                                FROM		
                                inv_bodegas_userpermisos IBU,
                                inv_bodegas_documentos ibd,
                                documentos doc,
                                tipos_doc_generales tdg
                                WHERE		
                                IBU.empresa_id ='".$Empresa_Id."' 
                                and
                                IBU.centro_utilidad = '".$CentroUtilidad."'
                                and
                                IBU.bodega = '".$Bodega."'
                                and
                                IBU.usuario_id = '".$UsuarioId."'
                                and
                                IBU.documento_id = ibd.documento_id
                                and
                                ibd.empresa_id ='".$Empresa_Id."' 
                                and
                                ibd.centro_utilidad = '".$CentroUtilidad."'
                                and
                                ibd.bodega = '".$Bodega."'
                                and
                                ibd.documento_id = doc.documento_id
                                and
                                doc.documento_id LIKE '%".$Documento_Id."%'
                                and
                                doc.descripcion ILIKE '%".$Descripcion."%'
                                and
                                doc.prefijo ILIKE '%".$Prefijo."%'
                                and
                                doc.tipo_doc_general_id = tdg.tipo_doc_general_id
                                 ";
  
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= "  order by doc.descripcion ";
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
  
  
   function Borrar_PermisosDocumentosBodegas($DocumentoId,$Usuario_id,$Empresa_Id,$CentroUtilidad,$Bodega)
	{
	  //$this->debug=true;
    $sql  = "DELETE FROM inv_bodegas_userpermisos ";
      $sql .= "WHERE ";
      $sql .= "empresa_id = '".$Empresa_Id."'";
      $sql .= " and ";
	    $sql .= "centro_utilidad = '".$CentroUtilidad."'";
      $sql .= " and ";
	    $sql .= "bodega = '".$Bodega."'";
      $sql .= " and ";
      $sql .= "usuario_id = '".$Usuario_id."'";
      $sql .= " and ";
      $sql .= "documento_id = '".$DocumentoId."'";
      $sql .= "     ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
  
  
  
	}
	
?>