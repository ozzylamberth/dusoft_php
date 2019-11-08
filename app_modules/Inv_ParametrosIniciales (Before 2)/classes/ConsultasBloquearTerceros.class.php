<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasTiposDispensacion.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 



 class ConsultasBloquearTerceros extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasBloquearTerceros(){}

  



      /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function BuscarTipoBloqueo($CodigoBloqueo)
{

          $sql="
            select
            tipo_bloqueo_id as codigo,
            descripcion,
            estado
            From
            inv_tipos_bloqueos
            where
            tipo_bloqueo_id = '".$CodigoBloqueo."';";
 
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
  
  




    /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function ListadoTerceros($offset)
{

          $sql="
            SELECT
                t.tipo_id_tercero,
                t.tercero_id,
                p.pais,
                d.departamento,
                m.municipio,
                t.direccion,
                t.telefono,
                t.fax,
                t.email,
                t.celular,
                t.sw_persona_juridica,
                t.nombre_tercero,
                t.tipo_bloqueo_id,
                tb.descripcion as bloqueo
                FROM
                terceros as t,
                tipo_pais as p,
                tipo_dptos as d,
                tipo_mpios as m,
                inv_tipos_bloqueos tb
                
                where
                m.tipo_pais_id = t.tipo_pais_id 
                and
                m.tipo_dpto_id = t.tipo_dpto_id
                and
                m.tipo_mpio_id = t.tipo_mpio_id
                and
                m.tipo_dpto_id = d.tipo_dpto_id
                and
                d.tipo_pais_id = t.tipo_pais_id
                and
                d.tipo_pais_id = p.tipo_pais_id
                and
                t.tipo_bloqueo_id = tb.tipo_bloqueo_id
                        
            ";
 
 
             if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= " ORDER BY t.nombre_tercero ASC,t.tipo_bloqueo_id DESC ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
 
 
 
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
  
    
  
  /**********************************************************************************
		* Insertar una SubClase a Una Clase
		* 
		* @return token
		************************************************************************************/
		
	function InsertarTipoBloqueo($Datos)
	{
	  
    //$this->debug=true;
    $sql  = "INSERT INTO inv_tipos_bloqueos(";
    $sql .= "       tipo_bloqueo_id     , ";
	  $sql .= "       descripcion     , ";
	  $sql .= "       estado     ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$Datos['tipo_bloqueo_id']."',";
	  $sql .= "        '".$Datos['descripcion']."',";
	  $sql .= "        '".$Datos['estado']."'";
	  $sql .= "       ); ";			
		
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  

/*
*Funcion para Modificar Grupos
*/

    function ModificarTercero($TerceroId,$TipoBloqueoId)
	{
	  //$this->debug=true;
    $sql  = "UPDATE terceros ";
    $sql .= "SET ";
    $sql .= "tipo_bloqueo_id = '".$TipoBloqueoId."'";
    $sql .= " Where ";
    $sql .= "tercero_id ='".$TerceroId."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
	
  
  
  
  /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function TiposIdTerceros()
{

          $sql="
            SELECT
                        tipo_id_tercero,
                          descripcion
                      FROM
                          tipo_id_terceros
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
  
  
  
  
  /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function BuscarTercero($tipo_id,$tercero_id,$dv,$nombre_tercero,$offset)
{
if($dv=="0")
    $sq="t.dv is null
        and";
 else
    $sq="t.dv ILIKE '%".$dv."%'
        and";
		
 if($tipo_id != "NIT")
	$sq="";
    //$this->debug=true;
     $sql="
            select
                t.tipo_id_tercero,
                t.tercero_id,
                p.pais,
                d.departamento,
                m.municipio,
                t.direccion,
                t.telefono,
                t.fax,
                t.email,
                t.celular,
                t.sw_persona_juridica,
                t.nombre_tercero,
                t.tipo_bloqueo_id,
                tb.descripcion as bloqueo
                FROM
                terceros t,
                tipo_pais p,
                tipo_dptos d,
                tipo_mpios m,
                inv_tipos_bloqueos tb
                                where
                
                t.tercero_id ILIKE '%".$tercero_id."%'
                and
                ".$sq."
                t.tipo_id_tercero ILIKE '%".$tipo_id."%'
                AND
                t.nombre_tercero ILIKE '%".$nombre_tercero."%'
                and
                t.tipo_mpio_id=m.tipo_mpio_id
                and
                t.tipo_dpto_id=m.tipo_dpto_id
                and
                t.tipo_pais_id = m.tipo_pais_id
                and
                m.tipo_dpto_id = d.tipo_dpto_id
                and
                d.tipo_pais_id = p.tipo_pais_id
                and
                t.tipo_bloqueo_id = tb.tipo_bloqueo_id ";
            
 
 
 
             if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

      $sql .= " ORDER BY t.nombre_tercero ASC,t.tipo_bloqueo_id DESC ";
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
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function TiposIdPacientes()
{

          $sql="
            SELECT
                        tipo_id_paciente,
                          descripcion
                      FROM
                          tipos_id_pacientes
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
  
  
     /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function ListarPacientes($offset)
{
//$this->debug=true;
          $sql="
            SELECT
                p.paciente_id,
                p.tipo_id_paciente,
                p.primer_nombre,
                p.segundo_nombre,
                p.primer_apellido,
                p.segundo_apellido,
                p.sexo_id,
                tp.pais,
                td.departamento,
                tm.municipio,
                p.tipo_bloqueo_id,
                tb.descripcion as bloqueo
                FROM
                pacientes as p 
                LEFT JOIN tipo_mpios as tm ON (tm.tipo_pais_id = p.tipo_pais_id)
                and (tm.tipo_dpto_id = p.tipo_dpto_id) and (tm.tipo_mpio_id = p.tipo_mpio_id)
                LEFT JOIN tipo_dptos as td ON (tm.tipo_dpto_id = td.tipo_dpto_id) and (td.tipo_pais_id = p.tipo_pais_id)
                LEFT JOIN tipo_pais as tp ON (td.tipo_pais_id = tp.tipo_pais_id)
                LEFT JOIN inv_tipos_bloqueos tb ON (p.tipo_bloqueo_id = tb.tipo_bloqueo_id)
                
            ";
 
 
             if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= " ORDER BY p.tipo_bloqueo_id DESC,p.paciente_id ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
 
 
 
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
  
  
  
  
  /*
*Funcion para Modificar Grupos
*/

    function ModificarPaciente($PacienteId,$TipoBloqueoId)
	{
	  //$this->debug=true;
    $sql  = "UPDATE pacientes ";
    $sql .= "SET ";
    $sql .= "tipo_bloqueo_id = '".$TipoBloqueoId."'";
    $sql .= " Where ";
    $sql .= "paciente_id ='".$PacienteId."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
  
  
  
  
  
  /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function BuscarPaciente($tipo_id,$paciente_id,$primer_nombre,$primer_apellido,$offset)
{

    //$this->debug=true;
     $sql="
                SELECT
                p.paciente_id,
                p.tipo_id_paciente,
                p.primer_nombre,
                p.segundo_nombre,
                p.primer_apellido,
                p.segundo_apellido,
                p.sexo_id,
                tp.pais,
                td.departamento,
                tm.municipio,
                p.tipo_bloqueo_id,
                tb.descripcion as bloqueo
                FROM
                pacientes as p 
                LEFT JOIN tipo_mpios as tm ON (tm.tipo_pais_id = p.tipo_pais_id)
                and (tm.tipo_dpto_id = p.tipo_dpto_id) and (tm.tipo_mpio_id = p.tipo_mpio_id)
                LEFT JOIN tipo_dptos as td ON (tm.tipo_dpto_id = td.tipo_dpto_id) and (td.tipo_pais_id = p.tipo_pais_id)
                LEFT JOIN tipo_pais as tp ON (td.tipo_pais_id = tp.tipo_pais_id)
                LEFT JOIN inv_tipos_bloqueos tb ON (p.tipo_bloqueo_id = tb.tipo_bloqueo_id)
                where
                p.paciente_id ILIKE '%".$paciente_id."%'
                and
                p.tipo_id_paciente ILIKE '%".$tipo_id."%'
                AND
                p.primer_nombre||' '||p.segundo_nombre ILIKE '%".$primer_nombre."%'
                AND
                p.primer_apellido|| ' ' ||p.segundo_apellido ILIKE '%".$primer_apellido."%'
                ";
            
 
 
 
             if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
        /*
        * 3) Paso Implementar paginador... Incluir paramento offset
        *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
        *  Organizar la Busqueda
        *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
        */   

    $sql .= " ORDER BY p.tipo_bloqueo_id DESC,p.paciente_id ";
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