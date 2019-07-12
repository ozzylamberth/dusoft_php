<?php
/**
  * @package IPSOFT-DUSOFT
  * @version $Id: ConsultasAsignarEspecialidadesAMedicamentos.class.php,v 1.1 2010/01/19 13:23:00 mauricio Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-DUSOFT
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 



 class ConsultasAsignarEspecialidadesAMedicamentos extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasAsignarEspecialidadesAMedicamentos(){}

 /*
* Listar Empresas
*/ 
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
				order by empresa_id;  ";

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
   
   
  
  function Buscar_Producto($CodigoProducto)
  {
  
  $sql= "             
                        select 
                              grp.grupo_id || ' ' ||grp.descripcion as Grupo,
                              lab.laboratorio_id || ' ' || lab.descripcion as Clase,
                              mol.molecula_id || ' ' || mol.descripcion || ' ' || mol.concentracion || ' ' || mol.unidad_medida_medicamento_id as Subclase,
                              prod.producto_id,
                              prod.codigo_cum,
                              prod.codigo_alterno,
                              prod.codigo_barras,
                              prod.descripcion,
                              prod.descripcion_abreviada,
                              prod.fabricante_id,
                              prod.sw_pos,
                              prod.cod_acuerdo228_id,
                              prod.cod_forma_farmacologica,
                              prod.unidad_id,
                              prod.contenido_unidad_venta as cantidad,
                              prod.cod_anatofarmacologico,
                              prod.mensaje_id,
                              prod.codigo_mindefensa,
                              prod.codigo_invima,
                              prod.vencimiento_codigo_invima,
                              prod.titular_reginvima_id,
                              prod.porc_iva,
                              prod.sw_generico,
                              prod.sw_venta_directa,
                              prod.tipo_producto_id,
                              prod.tipo_pais_id,
                              fab.descripcion as fabricante,
                              itri.descripcion as titular
                          
                    from
                            inv_grupos_inventarios grp,
                            inv_clases_inventarios cla,
                            inv_laboratorios lab,
                            inv_subclases_inventarios sub,
                            inv_moleculas mol,
                            inventarios_productos prod,
                            inv_fabricantes fab,
                            inv_titulares_reginvima itri
                   where
                          prod.codigo_producto = '".$CodigoProducto."'
                          and
                          prod.fabricante_id = fab.fabricante_id
                          and
                          prod.titular_reginvima_id = itri.titular_reginvima_id
                          and
                          sub.subclase_id = prod.subclase_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.molecula_id = mol.molecula_id
                          and
                          mol.estado = '1'
                          and
                          sub.clase_id = cla.clase_id
                          and
                          cla.grupo_id = prod.grupo_id
                          and
                          cla.laboratorio_id = lab.laboratorio_id
                          and
                          grp.grupo_id = prod.grupo_id;";
  
  
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

  
  
  
  
  function Listar_Medicamentos($offset)
  {
     // $this->debug=true;
      
      $sql="
              Select 
                          grp.descripcion as Grupo,
                          cla.clase_id,
                          sub.descripcion as Subclase,
                          prod.codigo_producto,
                          prod.descripcion,
                          ff.descripcion || ' X ' || med.concentracion_forma_farmacologica as presentacion,
                          prod.estado
                    from
                          inv_grupos_inventarios grp,
                          inv_clases_inventarios cla,
                          inv_subclases_inventarios sub,
                          inventarios_productos prod,
                          medicamentos med,
                          inv_med_cod_forma_farmacologica ff
                   where
                          grp.grupo_id = cla.grupo_id
                          and
                          cla.clase_id = sub.clase_id
                          and
                          sub.grupo_id = grp.grupo_id
                          and
                          sub.subclase_id = prod.subclase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          prod.codigo_producto = med.codigo_medicamento
                          and
                          med.cod_forma_farmacologica = ff.cod_forma_farmacologica
                          and
                          prod.estado = '1'
                     ";
                    
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        
    $sql .= "ORDER BY prod.descripcion ";
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
  
  
  
  function Listar_MedicamentosBuscar($Descripcion,$Molecula,$offset)
  {
      //$this->debug=true;
      
      $sql="
              Select 
                          grp.descripcion as Grupo,
                          cla.clase_id,
                          sub.descripcion as Subclase,
                          prod.codigo_producto,
                          fc_descripcion_producto(prod.codigo_producto) as producto,
                          prod.estado
                    from
                          inv_grupos_inventarios grp,
                          inv_clases_inventarios cla,
                          inv_subclases_inventarios sub,
                          inventarios_productos prod,
                          medicamentos med,
                          inv_med_cod_forma_farmacologica ff
                   where
                          grp.grupo_id = cla.grupo_id
                          and
                          cla.clase_id = sub.clase_id
                          and
                          sub.descripcion ILike '%".$Molecula."%'
                          and
                          sub.grupo_id = grp.grupo_id
                          and
                          sub.subclase_id = prod.subclase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          prod.descripcion ILike '%".$Descripcion."%'
                          and
                          prod.codigo_producto = med.codigo_medicamento
                          and
                          med.cod_forma_farmacologica = ff.cod_forma_farmacologica
                          and
                          prod.estado = '1'
                     ";
                    
  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        
    $sql .= "ORDER BY prod.descripcion ";
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
  
  
  
  
  
  function Listar_EspecialidadesAsignadas($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$CodigoProducto,$offset)
  {
  //$this->debug=true;
  $sql="
            select 
                      espx.especialidad,
					  espx.empresa_id,
					  espx.departamento,
					  espx.codigo_medicamento,
                      esp.descripcion
                  from 
                      especialidades esp,
                      medicamentos_especialidades espx
                  where
                      espx.empresa_id = '".$EmpresaId."'
                      and
                      espx.departamento = '".$Departamento."'
                      and
                      espx.codigo_medicamento = '".$CodigoProducto."'
                      and
                      espx.especialidad = esp.especialidad
                       ";
					  
					  if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
				return false;
      
        
    $sql .= " order by esp.descripcion ";
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
  
  
  
  
  function Listar_Especialidades($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$CodigoMedicamento)
  {
  $sql="
            select 
                  esp.especialidad,
                  esp.descripcion
                  from 
                  especialidades esp
                  where
                  esp.especialidad Not In
                                          (
                                          select 
                                          espx.especialidad
                                          from
                                          medicamentos_especialidades espx
                                          where
                                          espx.empresa_id = '".$EmpresaId."'
                                          and
                                          espx.departamento = '".$Departamento."'
                                          and
                                          espx.codigo_medicamento = '".$CodigoMedicamento."'
                                          ) 
                  order by esp.descripcion;";
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
  
 

function InsertarEspecialidades($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$CodigoMedicamento,$Especialidad)
  {
  //$this->debug=true;
    $sql  = "INSERT INTO medicamentos_especialidades (";
    $sql .= "       empresa_id    , ";
	$sql .= "       departamento    , ";
	$sql .= "       codigo_medicamento    , ";
	$sql .= "       especialidad     ";
	$sql .= "       ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$EmpresaId."',";
	$sql .= "        '".$Departamento."',";
	$sql .= "        '".$CodigoMedicamento."',";
	  $sql .= "        '".$Especialidad."'";
	  $sql .= "       ); ";			
  
  
  
 if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
    
   }
  
   function BorrarEspecialidades($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$CodigoMedicamento,$Especialidad)
  {
  
    //$this->debug=true;
    $sql  = "Delete from medicamentos_especialidades ";
      $sql .= " Where "; 
      $sql .= "empresa_id = '".$EmpresaId."'";
	  $sql .= " and ";
	  $sql .= "departamento = '".$Departamento."'";
	  $sql .= " and ";
	  $sql .= "codigo_medicamento = '".$CodigoMedicamento."'";
	  $sql .= " and ";
	  $sql .= "especialidad = '".$Especialidad."';";
            
  
  
  
 if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
  
  
  
  }

 
  
  
}
?>