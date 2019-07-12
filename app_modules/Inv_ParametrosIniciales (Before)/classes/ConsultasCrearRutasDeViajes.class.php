<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasCrearRutasDeViajes.class.php,
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
  
  
  
  class ConsultasCrearRutasDeViajes extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasCrearRutasDeViajes(){}
	
  
  
  
  function Listar_Empresas()
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              EM.razon_social AS Empresa,
              EM.direccion,
              EM.telefonos,
              EM.tipo_pais_id,
              EM.tipo_dpto_id,
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
		   
      $sql .= "order by empresa_id;";  
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
    
    function Buscar_Empresa($Empresa_Id)
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              EM.razon_social AS Empresa,
              EM.direccion,
              EM.telefonos,
              EM.tipo_pais_id,
              EM.tipo_dpto_id,
              d.departamento,
              m.municipio,
              p.pais,
              EM.empresa_id
							FROM		
              empresas EM,
              tipo_dptos d,
              tipo_mpios m,
              tipo_pais p
							WHERE		
              EM.empresa_id = '".$Empresa_Id."'
              and
              m.tipo_mpio_id = EM.tipo_mpio_id
              and
              m.tipo_dpto_id = EM.tipo_dpto_id
              and
              m.tipo_pais_id = EM.tipo_pais_id
              and
              m.tipo_dpto_id = d.tipo_dpto_id
              and
              d.tipo_pais_id = p.tipo_pais_id
              and
              EM.sw_activa ='1';
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
    
    
    
    
    
     function Listar_Zonas($Token)
		{
				//$this->debug=true;
        if($Token=="0")
        $sqlad = "where
                  estado ='1' ";
                  else
                  $sqlad ="";
        $sql = "
              SELECT	
              zona_id,
              descripcion,
              estado
              from 
              inv_zonas ".$sqlad.";
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
   
   
   
 function Listar_RutasViajes($offset)
		{
				//$this->debug=true;
        $sql = "
        SELECT	
              rvo.rutaviaje_origen_id,
              rvo.descripcion,
              rvo.empresa_id,
              rvo.estado,
              emp.razon_social,
              emp.direccion,
              d.departamento,
              m.municipio,
              p.pais
                        from 
                        inv_rutasviajes_origen rvo,
                        empresas emp,
                        tipo_dptos d,
                        tipo_mpios m,
                        tipo_pais p
                                      where
                                      rvo.rutaviaje_origen_id = rvo.rutaviaje_origen_id
                                      and
                                      rvo.empresa_id = emp.empresa_id
                                      and
                                      m.tipo_mpio_id = emp.tipo_mpio_id
                                      and
                                      m.tipo_dpto_id = emp.tipo_dpto_id
                                      and
                                      m.tipo_pais_id = emp.tipo_pais_id
                                      and
                                      m.tipo_dpto_id = d.tipo_dpto_id
                                      and
                                      d.tipo_pais_id = p.tipo_pais_id
                                      and
                                      emp.sw_activa ='1' 
                                       ";
if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
       
    $sql .= " order by rvo.rutaviaje_origen_id ";
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
    
    
   function Listar_Departamentos($CodigoPais)
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              tipo_dpto_id,
              departamento
              from 
              tipo_dptos
              where
              tipo_pais_id = '".$CodigoPais."'
              order by departamento;
              ";
		   
        
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
    
    
     function ListarMunicipios($tipo_pais_id,$tipo_dpto_id)
{

          $sql="
            select
            tipo_mpio_id,
            municipio
            From
            tipo_mpios
            where
            tipo_pais_id ='".$tipo_pais_id."'
            and
            tipo_dpto_id ='".$tipo_dpto_id."';";
 
 
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
	
  
  
  function Buscar_RutaViaje($RutaViaje_Origen_Id)
		{
				//$this->debug=true;
        $sql = "
        SELECT	
              rvo.rutaviaje_origen_id,
              rvo.descripcion,
              rvo.empresa_id,
              rvo.estado,
              emp.razon_social,
              emp.direccion,
              d.departamento,
              m.municipio,
              p.pais
                        from 
                        inv_rutasviajes_origen rvo,
                        empresas emp,
                        tipo_dptos d,
                        tipo_mpios m,
                        tipo_pais p
                                      where
                                      rvo.rutaviaje_origen_id = '".$RutaViaje_Origen_Id."'
                                      and
                                      rvo.empresa_id = emp.empresa_id
                                      and
                                      m.tipo_mpio_id = emp.tipo_mpio_id
                                      and
                                      m.tipo_dpto_id = emp.tipo_dpto_id
                                      and
                                      m.tipo_pais_id = emp.tipo_pais_id
                                      and
                                      m.tipo_dpto_id = d.tipo_dpto_id
                                      and
                                      d.tipo_pais_id = p.tipo_pais_id
                                      and
                                      emp.sw_activa ='1';";
		   
        
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
  
  
 function Buscar_Zona($Zona_Id)
		{
			//$this->debug=true;
    	$sql = "SELECT	
              zona_id,
              descripcion
							FROM		
              inv_zonas
              WHERE		
              zona_id = '".$Zona_Id."';";
						
			
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
  SELECT	
              em.razon_social,
              em.empresa_id,
              mun.municipio,
              dpto.departamento,
              zonampio.zona_mpio_id,
              zona.descripcion as zona,
              zonampio.zona_id
                   From
              inv_zonas_mpios zonampio,
              inv_zonas zona,
              tipo_mpios mun,
              empresas em,
              tipo_dptos dpto
              WHERE		
              zonampio.zona_id ='4'
              and
              zonampio.zona_id = zona.zona_id
              and
              zonampio.tipo_dpto_id = em.tipo_dpto_id
              and
              zonampio.tipo_pais_id = em.tipo_pais_id
              and
              zonampio.tipo_mpio_id = em.tipo_mpio_id
              and
              zonampio.tipo_mpio_id = mun.tipo_mpio_id
              and
              zonampio.tipo_dpto_id = mun.tipo_dpto_id
              and
              zonampio.tipo_pais_id = mun.tipo_pais_id
              and
              mun.tipo_dpto_id = dpto.tipo_dpto_id
              and
              mun.tipo_pais_id = dpto.tipo_pais_id
  */
  
  
  
 function Listar_MunicipiosZona($ZonaId,$CodigoPais)
		{
			//$this->debug=true;
    	$sql = "SELECT	
              mun.municipio,
              dpto.departamento,
              zonampio.zona_mpio_id,
              zona.descripcion,
              zonampio.zona_id
							From
              inv_zonas_mpios zonampio,
              inv_zonas zona,
              tipo_mpios mun,
              tipo_dptos dpto
              WHERE		
              zonampio.zona_id ='".$ZonaId."'
              and
              zonampio.zona_id = zona.zona_id
              and
              zonampio.tipo_mpio_id = mun.tipo_mpio_id
              and
              zonampio.tipo_dpto_id = mun.tipo_dpto_id
              and
              zonampio.tipo_pais_id = mun.tipo_pais_id
              and
              mun.tipo_dpto_id = dpto.tipo_dpto_id
              and
              mun.tipo_pais_id = dpto.tipo_pais_id
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
   
   function ModificarZona($datos)
	{
	  
   // $this->debug=true;
    $sql  = "UPDATE inv_zonas ";
    $sql .= "SET ";
    $sql .= "zona_id = '".$datos['zona_id']."',";
    $sql .= "descripcion = '".$datos['descripcion']."'";
	  $sql .= " Where ";
    $sql .= "zona_id ='".$datos['zona_id_old']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
  
  
  
  
  
		
	function InsertarZonas($datos)
	{
	  //$this->debug=true;
    $sql  = "INSERT INTO inv_zonas (";
    $sql .= "       zona_id     , ";
	  $sql .= "       descripcion,      ";
    $sql .= "       estado      ";
	  $sql .= "       ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$datos['zona_id']."',";
      $sql .= "        '".$datos['descripcion']."',";
	    $sql .= "        '".$datos['estado']."'";
	    $sql .= "        ";
      $sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    
    
    
    function InsertarConfigurarZonas($datos)
	{
	 // $this->debug=true;
    $zona_mpio_id=$datos['tipo_pais_id']."".$datos['tipo_dpto_id']."".$datos['tipo_mpio_id'];
    $sql  = "INSERT INTO inv_zonas_mpios (";
    $sql .= "       zona_id     , ";
	  $sql .= "       tipo_pais_id,      ";
    $sql .= "       tipo_dpto_id,      ";
    $sql .= "       tipo_mpio_id,      ";
    $sql .= "       zona_mpio_id      ";
	  $sql .= "       ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$datos['Zonas']."',";
      $sql .= "        '".$datos['tipo_pais_id']."',";
	    $sql .= "        '".$datos['tipo_dpto_id']."',";
      $sql .= "        '".$datos['tipo_mpio_id']."',";
      $sql .= "        '".$zona_mpio_id."'";
	    $sql .= "        ";
      $sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    
function InsertarRutaViaje($datos)
	{
	  //$this->debug=true;
    $sql  = "INSERT INTO inv_rutasviajes_origen (";
    $sql .= "       rutaviaje_origen_id     , ";
	  $sql .= "       empresa_id,      ";
    $sql .= "       descripcion,      ";
    $sql .= "       estado      ";
	  $sql .= "       ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$datos['rutaviaje_origen_id']."',";
      $sql .= "        '".$datos['empresa_id']."',";
      $sql .= "        '".$datos['descripcion']."',";
	    $sql .= "        '".$datos['estado']."'";
	    $sql .= "        ";
      $sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
	
  
  
   function ModificarRutaViaje($datos)
	{
	  
   // $this->debug=true;
    $sql  = "UPDATE inv_rutasviajes_origen ";
    $sql .= "SET ";
    $sql .= "rutaviaje_origen_id = '".$datos['rutaviaje_origen_id']."',";
    $sql .= "descripcion = '".$datos['descripcion']."',";
    $sql .= "empresa_id = '".$datos['empresa_id']."'";
    
	  $sql .= " Where ";
    $sql .= "rutaviaje_origen_id ='".$datos['rutaviaje_origen_id_old']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
	
  function EmpresasXZona($ZonaId)
  {
  //$this->debug=true;  
  $sql ="
  
    SELECT	
              zonampio.zona_mpio_id,
              emp.razon_social,
              emp.empresa_id
							From
              inv_zonas_mpios zonampio,
              empresas emp
              
              WHERE		
              zonampio.zona_id ='".$ZonaId."'
              and
              zonampio.tipo_mpio_id = emp.tipo_mpio_id
              and
              zonampio.tipo_dpto_id = emp.tipo_dpto_id
              and
              zonampio.tipo_pais_id = emp.tipo_pais_id;";
              
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
  
    function InsertarConfigurarRuta($datos)
	{
	 // $this->debug=true;
    $rutaviaje_destinoempresa_id=$datos['empresa_id']."".$datos['rutaviaje_origen_id'];
    $sql  = "INSERT INTO inv_rutasviajes_destinos (";
    $sql .= "       rutaviaje_origen_id     , ";
	  $sql .= "       empresa_id,      ";
    $sql .= "       rutaviaje_destinoempresa_id      ";
    $sql .= "       ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$datos['rutaviaje_origen_id']."',";
      $sql .= "        '".$datos['empresa_id']."',";
	    $sql .= "        '".$rutaviaje_destinoempresa_id."'";
      $sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
 
 
 
 function MostrarInfoRuta($RutaId)
{

          $sql="
            SELECT	
              zona.zona_id,
              zona.descripcion,
              irvd.rutaviaje_destinoempresa_id as codigo,
              emp.razon_social,
              emp.empresa_id
							From
              inv_rutasviajes_destinos irvd,
              empresas emp,
              inv_zonas_mpios zonampio,
              inv_zonas zona
              WHERE		
              irvd.rutaviaje_origen_id ='".$RutaId."'
              and
              irvd.empresa_id = emp.empresa_id
              and
              emp.tipo_mpio_id = zonampio.tipo_mpio_id
              and
              emp.tipo_dpto_id = zonampio.tipo_dpto_id
              and
              emp.tipo_pais_id = zonampio.tipo_pais_id
              and
              zonampio.zona_id = zona.zona_id
              ORDER BY zona.descripcion;";
 
 
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
 
 */ 
  
  
  
  
	}
	
?>