<?php
  /******************************************************************************
  * $Id: MovimientosSQL.class.php,v 1.13 2008/03/28 23:03:20 cahenao Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.13 $ 
	* 
	* @autor Jaime Gomez
  ********************************************************************************/
	IncludeClass("ContabilizarDocumento","ContabilizacionDeDocumentos");
  IncludeClass("ContabilizacionDeDocumentos","ContabilizacionDeDocumentos");
  class MovimientosSQL
	{
		
    var $MOVIMIENTO;
      
    function MovimientosSQL()
    {
      $this->MOVIMIENTO="cg_mov_".SessionGetVar("EMPRESA").".cg_mov_contable_".SessionGetVar("EMPRESA")."";
    }
	
/************************************************************************************
*
*Funcion que lista cuentas
*
*************************************************************************************/    
 function BuscarCuentasStip($tip_bus,$elemento,$offset,$empresa)
 {  
     
       if($tip_bus==0)
       {    
           $sql1="select  count(*) 
            from 
            cg_conf.cg_plan_de_cuentas where empresa_id='".$empresa."'";
            $this->ProcesarSqlConteo($sql1,10,$offset);      
            
              $sql=" select * from cg_conf.cg_plan_de_cuentas  
            where empresa_id='".$empresa."' order by cuenta 
            limit ".$this->limit." OFFSET ".$this->offset."" ;
        }  
       
    if($tip_bus==1)
     {  
         $sql1="select count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where 
      cuenta LIKE '".strtoupper ($elemento)."%' and empresa_id='".$empresa."' ";
      $this->ProcesarSqlConteo($sql1,10,$offset);      
       
         $sql=" select * from cg_conf.cg_plan_de_cuentas where cuenta LIKE '".strtoupper ($elemento)."%' 
       and empresa_id='".$empresa."' order by cuenta
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     }
      
     if($tip_bus==2)
     {  
         $sql1="select count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where 
      descripcion LIKE '%".strtoupper ($elemento)."%' and empresa_id='".$empresa."' ";
      $this->ProcesarSqlConteo($sql1,10,$offset);      
       
         $sql=" select * from cg_conf.cg_plan_de_cuentas where descripcion LIKE '%".strtoupper ($elemento)."%' 
       and empresa_id='".$empresa."' order by cuenta
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     } 
       
   
     if($tip_bus==3)
     { 
        
      list($elemento1,$elemento2) = explode("-", $elemento);  
        $sql1="select  count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where cuenta >= '".$elemento1."' and cuenta <= '".$elemento2."' and empresa_id='".$empresa."'";
      $this->ProcesarSqlConteo($sql1,10,$offset);      
       
         $sql=" select  * from cg_conf.cg_plan_de_cuentas 
       where cuenta >= '".$elemento1."' and cuenta <= '".$elemento2."'
        and empresa_id='".$empresa."' order by cuenta 
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     }
      
      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
            
            
    
    }
 
    
/************************************************************************************
*
*Funcion que cuenta cuentas
*
*************************************************************************************/    
 function ContarCuentasStip($tip_bus,$elemento,$empresa)
 { 
     
      if($tip_bus==0)
      {    
         $sql1="select count(*) from 
         cg_conf.cg_plan_de_cuentas 
         where 
         empresa_id='".$empresa."'";
      }  
       
    if($tip_bus==1)
     {  
      $sql1="select count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where 
      cuenta LIKE '".strtoupper ($elemento)."%' and empresa_id='".$empresa."' ";
        
     }
     
    if($tip_bus==2)
     {  
      $sql1="select count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where 
      descripcion LIKE '%".strtoupper ($elemento)."%' and empresa_id='".$empresa."' ";
     
     }
       
       
    
     if($tip_bus==3)
     { 
        
        list($elemento1,$elemento2) = explode("-", $elemento);  
        $sql1="select  count(*) 
        from 
        cg_conf.cg_plan_de_cuentas 
        where cuenta >= '".$elemento1."' and cuenta <= '".$elemento2."' and empresa_id='".$empresa."'";
   
     }
      
            
          
       if(!$resultado = $this->ConexionBaseDatos($sql1))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
            
            
    
    }
 

/************************************************************************************
*
*Funcion que consulta tipos de documentos.
*
*************************************************************************************/    
function Prefixo1()
{ 
       $sql=" select prefijo,documento_id,descripcion from documentos 
              where empresa_id='".SessionGetVar("EMPRESA")."'
              order by prefijo"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

  
  
            
/************************************************************************************
*
*Funcion que consulta tipos de documentos.
*
*************************************************************************************/    
function NombreCuenta($cuenta)
{ 
       
       $sql=" select descripcion from cg_conf.cg_plan_de_cuentas  
               where cuenta='".$cuenta."' and 
               empresa_id='".SessionGetVar("EMPRESA")."' 
               order by cuenta"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}
            
  
  /************************************************************************************
*
*Funcion que lista los tipos documentos segun el tipo de empresa.
*
*************************************************************************************/    
    function ListarTiposDocumentos()
    { 
     
      $sql="select * from tipos_doc_generales where sw_doc_sistema='0' order by tipo_doc_general_id"; //
       
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        $resultado->fields[1] = strtoupper($resultado->fields[1]);
        $resultado->fields[1] = ereg_replace("???", "E", $resultado->fields[1]); 
        $documentos[$resultado->fields[1]] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      
      return $documentos;
     }

   
/************************************************************************************
*
*Funcion que lista los tipos documentos segun el tipo de empresa.
*
*************************************************************************************/    
    function ListarEmpresas() 
    { 
     
      $sql=" select * from empresas
      order by empresa_id"; //
       
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        $resultado->fields[3] = strtoupper($resultado->fields[3]);
        $resultado->fields[3] = ereg_replace("???", "E", $resultado->fields[3]); 
        $documentos[$resultado->fields[3]] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
     }
   
   
   
  
/************************************************************************************
*
*Funcion que saca el nuevo id
*
*************************************************************************************/    
 function tmp_id()
 { 
  $sql1="select nextval('cg_conf.tmp_cg_mov_contable_tmp_id_seq'::regclass)";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
 }

/************************************************************************************
*
*Funcion que saca el CUENTAS
*
*************************************************************************************/    
 function ConsultaCuentas($cuenta)    
 { 
    $sql1="select * from cg_conf.cg_plan_de_cuentas
    where cuenta='".$cuenta."' and empresa_id='".SessionGetVar("EMPRESA")."'
    ";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $cuentas;
 }    
      

/************************************************************************************
*
*Funcion que saca el nuevo id
*
*************************************************************************************/    
 function GranLapso($empresa)
 { 
    $sql1="select * from cg_conf.cg_lapsos_contables
    where empresa_id='".SessionGetVar("EMPRESA")."' and sw_estado='1'";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
 }    



/************************************************************************************
*
*Funcion que saca el nuevo id
*
*************************************************************************************/    
 function ExisteLapso($lapso)    
 { 
    $sql1="select * from cg_conf.cg_lapsos_contables
    where lapso='".$lapso."' and empresa_id='".SessionGetVar("EMPRESA")."' ";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
 }    
 
/************************************************************************************
*
*Funcion que saca los departamentos
*
*************************************************************************************/    
 function Departamentos()    
 { 
    $sql1="select centro_de_costo_id,descripcion from cg_conf.centros_de_costo
           ORDER BY descripcion";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
 }   

/************************************************************************************
*
*Funcion que saca los departamentos
*
*************************************************************************************/    
 function Departamentos_d($depto)    
 { 
    $sql1="select descripcion from cg_conf.centros_de_costo
           where centro_de_costo_id='".$depto."' ORDER BY descripcion";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  //return $sql1;
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $cuentas;
 }   
 
 /************************************************************************************
*
*Funcion que saca los departamentos
*
*************************************************************************************/    
 function Sacartmp_CgMovcontable_d($tmp_id) 
 { 
    $sql1="select * from cg_conf.tmp_cg_mov_contable_d
           where tmp_id='".$tmp_id."'
           ORDER BY tmp_movimiento_id";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  //return $sql1;
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $cuentas;
 }   
 
 
 function Sacarprefi($dc)    
 { 
           $sql1="select numero, prefijo from ".$this->MOVIMIENTO."
           where documento_contable_id='".$dc."'
           ORDER BY numero";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;    
  
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $cuentas;
 } 
 function Consultampio($pais,$depto,$Municipio)
 { 
           $sql1="select tipo_mpio_id from tipo_mpios
           where 
            tipo_pais_id='".$pais."' AND
            tipo_dpto_id='".$depto."' AND
            municipio='".strtoupper($Municipio)."'";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  {
   //return $sql1;
   return false;
  }
  else
  {     
    $munis=array();
    while(!$resultado->EOF)
      {
        $munis[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $munis;
  }    
 }
 
 function Consultadpto($departamentox)
 { 
   $sql1="select tipo_dpto_id from tipo_dptos
   where departamento='".strtoupper($departamentox)."'";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  {
   return false;    
  } 
  else
  {
    $deptos=array();
    while(!$resultado->EOF)
      {
        $deptos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $deptos;
   }   
 }
/************************************************************************************
*
*Funcion que saca los TERCEROS($pagina,$criterio1,$criterio2,$criterio);
*
*************************************************************************************/    
  function Terceros($pagina,$tipo_id,$id,$nombre)    
  { 
       if( $tipo_id=="0" && $id=="0" && $nombre=="0")
       {
          $sql1="select count(*) from terceros";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          $sql="select * from terceros ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }
       elseif($tipo_id!="0" && $id!="0" && $nombre!="0")
       {
          $sql1="select count(*) 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          and tercero_id='".$id."'
          or nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          and tercero_id='".$id."'
          or nombre_tercero LIKE '%".strtoupper ($nombre)."%'
          ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       } 
      elseif($tipo_id=="0" && $id!="0" && $nombre!="0")
       {
          $sql1="select count(*) 
          from terceros
          where tercero_id='".$id."'
          or nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where tercero_id='".$id."'
          or nombre_tercero LIKE '%".strtoupper ($nombre)."%'
          ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       } 
       elseif($tipo_id!="0" && $id!="0" && $nombre=="0")
       {
          $sql1="select count(*) 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          and tercero_id='".$id."'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          and tercero_id='".$id."'         
          ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }
       elseif($tipo_id!="0" && $id=="0" && $nombre!="0")
       {
          $sql1="select count(*) 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          and nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          and nombre_tercero LIKE '%".strtoupper ($nombre)."%'
          ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       } 
       elseif($tipo_id!="0" && $id=="0" && $nombre=="0")
       {
          $sql1="select count(*) 
          from terceros
          where tipo_id_tercero='".$tipo_id."'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }
       elseif($tipo_id=="0" && $id!="0" && $nombre=="0")
       {
          $sql1="select count(*) 
          from terceros
          where tercero_id='".$id."'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where
          tercero_id='".$id."'
          ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }
       elseif($tipo_id=="0" && $id=="0" && $nombre!="0")
       {
          $sql1="select count(*) 
          from terceros
          where nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where nombre_tercero LIKE '%".strtoupper ($nombre)."%'
          ORDER BY nombre_tercero
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }  
  if(!$resultado = $this->ConexionBaseDatos($sql))
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql;
      return $cuentas;
 }   
 
 /********************************************************************************
*FUNCION QUE CUENTA TERCEROS SEGUN TIPO DE BUSQUEDA
*********************************************************************************/

  function ContarTercerosStip($tipo_id,$id,$nombre)
  { 
    
    if($tipo_id=="0" && $id=="0" && $nombre=="0")
       {
          $sql1="select count(*) from terceros";
       }
      elseif($tipo_id!="0" && $id!="0" && $nombre!="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."' 
        and tercero_id='".$id."'
        or nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
      }
      elseif($tipo_id!="0" && $id!="0" && $nombre=="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."' 
        and tercero_id='".$id."'";
      }
      elseif($tipo_id=="0" && $id=="0" && $nombre!="0")
      {
        $sql1="select count(*) 
        from terceros
        where nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
      }
      elseif($tipo_id!="0" && $id=="0" && $nombre=="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."'";
      }
      elseif($tipo_id!="0" && $id=="0" && $nombre!="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."' 
        and nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
      }
      elseif($tipo_id!="0" && $id!="0" && $nombre!="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."' 
        and tercero_id='".$id."'
        or nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
      }
      
      elseif($tipo_id=="0" && $id!="0" && $nombre=="0")
      {
        $sql1="select count(*) 
        from terceros
        where tercero_id='".$id."'
        ";
      } 
    ///
    
      
         
     
       if(!$resultado = $this->ConexionBaseDatos($sql1))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
            
            
    
    }   
/************************************************************************************
*
*Funcion que saca los TERCEROS
*
*************************************************************************************/    
 function DC($pagina,$tip_bus,$criterio)    
 { 
    if($tip_bus==6 || $tip_bus==0)
    {     
      $sql1="select count(*) 
      from ".$this->MOVIMIENTO."";
      $this->ProcesarSqlConteo($sql1,10,$pagina);     
      
       $sql=" select *
       from ".$this->MOVIMIENTO." ORDER BY lapso
       limit ".$this->limit." OFFSET ".$this->offset.""; 
    }  
       
    if($tip_bus==1)
     {  
       $sql1="select count(*) from ".$this->MOVIMIENTO."
      where documento_contable_id ='".$criterio."'";
      $this->ProcesarSqlConteo($sql1,10,$pagina);      
       
       $sql=" select * ".$this->MOVIMIENTO."
      where documento_contable_id ='".$criterio."' ORDER BY lapso
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     }
       
       
    if($tip_bus==2)
     {  
         $sql1="select count(*) 
        from 
        ".$this->MOVIMIENTO."
        where lapso = '".$criterio."'";
        $this->ProcesarSqlConteo($sql1,10,$pagina);      
        
        $sql=" select * 
        from 
        ".$this->MOVIMIENTO."
        where lapso = '".$criterio."'
        ORDER BY lapso
        limit ".$this->limit." OFFSET ".$this->offset."" ;
     }
    
      if($tip_bus==3)
      { 
       list($prefijo,$numero) = explode("-", $criterio);     
       $sql1="select  count(*) 
       from 
       ".$this->MOVIMIENTO."
       where prefijo = '".strtoupper($prefijo)."' and  numero=".$numero."";
       $this->ProcesarSqlConteo($sql1,10,$offset);      
       
       list($prefijo,$numero) = explode("-", $criterio);     
       $sql=" select * 
       from 
       ".$this->MOVIMIENTO."
       where prefijo = '".strtoupper($prefijo)."' and  numero=".$numero."
       order by lapso
       limit ".$this->limit." OFFSET ".$this->offset."" ;
      }
       
      if($tip_bus==4)
      { 
         
       $sql1="select  count(*) 
       from 
       ".$this->MOVIMIENTO."
       where numero = '".$criterio."'";
       $this->ProcesarSqlConteo($sql1,10,$offset);      
       
        $sql=" select *
        from 
        ".$this->MOVIMIENTO."
        where numero = '".$criterio."'
        order by lapso
        limit ".$this->limit." OFFSET ".$this->offset."" ;
      }
      if($tip_bus==5)
      { 
        list($criterio1,$criterio2) = explode("-", $criterio);   
       $sql1="select  count(*) 
       from 
       ".$this->MOVIMIENTO."
       where tipo_id_tercero = '".$criterio1."' and tercero_id='".$criterio2."'";
       $this->ProcesarSqlConteo($sql1,10,$offset);      
      
        $sql=" select * 
        from 
        ".$this->MOVIMIENTO."
        where tipo_id_tercero = '".$criterio1."' and tercero_id='".$criterio2."'
        order by lapso
        limit ".$this->limit." OFFSET ".$this->offset."" ;
      }
      
     
      
     
    
    
  if(!$resultado = $this->ConexionBaseDatos($sql))
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      
      return $cuentas;
 }   
 
 /********************************************************************************
*FUNCION QUE CUENTA TERCEROS SEGUN TIPO DE BUSQUEDA
*********************************************************************************/

  function ContarDCStip($tip_bus,$criterio)
  { 
    
    ///
    if($tip_bus==6 || $tip_bus==0)
    {     
      $sql1="select count(*) 
      from ".$this->MOVIMIENTO."";
    }  
       
    if($tip_bus==1)
     {  
       $sql1="select count(*) 
       from 
       ".$this->MOVIMIENTO."
       where documento_contable_id ='".$criterio."'";
     }
            
    if($tip_bus==2)
     {  
        $sql1="select count(*) 
        from 
        ".$this->MOVIMIENTO."
        where lapso = '".$criterio."'";
     }
    
      if($tip_bus==3)
      { 
       $sql1="select  count(*) 
       from 
       ".$this->MOVIMIENTO."
       where prefijo = '".$criterio."'";
      }
       
      if($tip_bus==4)
      { 
         
       $sql1="select  count(*) 
       from 
       ".$this->MOVIMIENTO."
       where numero = '".$criterio."'";
     
       
     
      }
      if($tip_bus==5)
      { 
         list($criterio1,$criterio2) = explode("-", $criterio);  
       $sql1="select  count(*) 
       from 
       ".$this->MOVIMIENTO."
       where tipo_id_tercero = '".$criterio1."' and tercero_id='".$criterio2."'";
         
     
      }
      
     
      
    
    ///
    
      
         
     
       if(!$resultado = $this->ConexionBaseDatos($sql1))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
            
            
    
    }   
 
     
/************************************************************************************
*
*Funcion que saca toda la info de tmp_cg_mov_contable
*
*************************************************************************************/    
 function SacarCgMovcontable($tip_doc)    
 { 
    $sql1="select a.*,b.tipo_doc_general_id from cg_conf.tmp_cg_mov_contable as a,documentos as b
           where b.tipo_doc_general_id='".$tip_doc."' 
           and a.prefijo=b.prefijo 
           and a.documento_id=b.documento_id
           and a.usuario_id='".UserGetUID()."' order by tmp_id";
  //return $sql1; 
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
 }        
    
    /**********************************************************************************
    * Funcion que inserta  en la tabla cg_parametros_documentos, 
    * 
    * @return mensaje de confirmacion
    ***********************************************************************************/
  function  GuardarDocumentoBD($tmp_id,$lapso,$empresa_id,$prefijox,$documento_id,$total_d,$total_c,$tip_ter_id,$ter_id,$usuario,$fecha)
   { 
      
      $sql="insert into  cg_conf.tmp_cg_mov_contable
      values(".$tmp_id.",'".$lapso."','".$empresa_id."','".$prefijox."',
             ".$documento_id.",".$total_d.",".$total_c.",'".$tip_ter_id."','".$ter_id."',".$usuario.",'".$fecha."');";
                   
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la inserci???";
         //return $cad;
         return $sql;
       }
      else
       {      
         $cad="Documento Agregado Satisfactoriamente";
         $rst->Close();
         return $cad;
       }
    
    }
     
 /**********************************************************************************
  * Funcion que inserta  departamentos, 
  * 
  * @return vector
    ***********************************************************************************/
  function GXD($id_pais,$departamentox)
   { 
      
      $sql="select max(tipo_dpto_id) from tipo_dptos
      where tipo_pais_id='".$id_pais."'"; 
      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
      $documentos=Array();
      while(!$resultado->EOF)
      {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
      }
      $resultado->Close();
      if(!empty($documentos))
      {
        $codigo_dep=$documentos[0]['max']+1;
      }
      else
      {
       $codigo_dep=1;
      }
      $sql="insert into tipo_dptos values('".$codigo_dep."','".$id_pais."','".strtoupper($departamentox)."');";
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la inserci???";
         //return $cad;
         return $sql;
       }
      else
       {      
         $cad=$codigo_dep;
         $rst->Close();
         return $cad;
       }
    
    }
    
/**********************************************************************************
* Funcion que inserta  municipios, 
* 
* @return vector
***********************************************************************************/
  function GXM($id_pais,$id_dept,$Municipio)
   { 
    
      $sql="select max(tipo_mpio_id) from tipo_mpios
      where tipo_pais_id='".$id_pais."' and  tipo_dpto_id='".$id_dept."'"; 
      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
      $documentos=Array();
      while(!$resultado->EOF)
      {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
      }
      $resultado->Close();
      if(!empty($documentos))
      {
        $codigo_mun=$documentos[0]['max']+1;
      }
      else
      {
        $codigo_mun=1;
      }                                        
      $sql="insert into tipo_mpios values('".$id_pais."','".$id_dept."','".$codigo_mun."','".strtoupper($Municipio)."');";
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la inserci???";
         //return $cad;
         return $sql;
       }
      else
       {      
         $cad=$codigo_mun;
         $rst->Close();
         return $cad;
       }
    
    }
    

/**********************************************************************************
* Funcion que inserta  en la tabla cg_parametros_documentos, 
* 
* @return mensaje de confirmacion
***********************************************************************************/
 function  GuardarPersonas( $tipo_identificacion,
                            $id_tercero,
                            $nombre,
                            $pais,
                            $departamento,
                            $municipio,
                            $direccion,
                            $telefono,
                            $faz,
                            $email,
                            $celular,
                            $perjur)
 { 
      //var_dump($direccion);
      if($direccion=="")
      {
         $direccion="NULL";
      }
      else
      {
         $direccion="'".$direccion."'";
      }     
      if($telefono==0)
      {
         $telefono="NULL";
      }
      else
      {
         $telefono="'".$telefono."'";
      }     
      if($faz==0)
      {
         $faz="NULL";
      }
      else
      {
        $faz="'".$faz."'";
      }
      if($email==0)
      {
         $email="NULL";
      }
      else
      {
        $email="'".$email."'";
      }
      if($celular==0)
      {
         $celular="NULL";
      } 
      else
      {
        $celular="'".$celular."'";
      }
          
      $sql="insert into  terceros
      values('".$tipo_identificacion."','".$id_tercero."','".$pais."',
             '".$departamento."','".$municipio."',".$direccion.",".$telefono.",
             ".$faz.",".$email.",".$celular.",'".$perjur."','0',".UserGetUID().",now(),NULL,'".$nombre."');";
//           tipo_id_tercero   tercero_id  tipo_pais_id  
//           tipo_dpto_id  tipo_mpio_id  direccion
//          telefono  fax   email   celular   
//          sw_persona_juridica   cal_cli   usuario_id  
//          fecha_registro  busca_persona   nombre_tercero           
   if(!$rst = $this->ConexionBaseDatos($sql)) 
   {  $cad="no se hizo la inserci???";
      //return $cad;
     return $sql;
   }
   else
   {      
     //$cad=$sql;
     $cad="EXITO";
     $rst->Close();
     return $cad;
   }
    
}
    /*******************************************************************************
    *up cg_mov_contable
    ********************************************************************************/
    function UpDocumentosCgMov($tmp_id,$debicredi,$valor)
    {

      if($debicredi=="D")
      {   
         // $credi=0; 
       // tmp_id  lapso   empresa_id  prefijo   documento_id  total_debitos   total_creditos  tipo_id_tercero   tercero_id  usuario_id
      
          $sql="Update cg_conf.tmp_cg_mov_contable 
          SET total_debitos=total_debitos+".$valor."
           where tmp_id=".$tmp_id."";
      }
      elseif($debicredi=="C")
      {
        $sql="Update cg_conf.tmp_cg_mov_contable 
        SET total_creditos=total_creditos+".$valor."
        where tmp_id=".$tmp_id.""; 
      
      }
                       
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="OPERACION INVALIDA";
         return $sql;
         //return $cad;
       }
      else
       {      
         $cad="DOCUMENTO ACTUALIZADO SATISFACTORIAMENTE";
         $rst->Close();
         return $cad;
       }
    
    }
     /*******************************************************************************
    *up cg_mov_contable
    ********************************************************************************/
    function RestarDescuento($tmp_id,$descuento_D,$descuento_C)
    {

      if($descuento_D>0)
      {   
         // $credi=0; 
       // tmp_id  lapso   empresa_id  prefijo   documento_id  total_debitos   total_creditos  tipo_id_tercero   tercero_id  usuario_id
      
          $sql="Update cg_conf.tmp_cg_mov_contable 
          SET total_debitos=total_debitos-".$descuento_D."
           where tmp_id=".$tmp_id."";
      }
      elseif($descuento_C>0)
      {
        $sql="Update cg_conf.tmp_cg_mov_contable 
        SET total_creditos=total_creditos-".$descuento_C."
        where tmp_id=".$tmp_id.""; 
      
      }
                       
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="OPERACION INVALIDA";
         return $sql;
         //return $cad;
       }
      else
       {      
         $cad="DOCUMENTO ACTUALIZADO SATISFACTORIAMENTE";
         $rst->Close();
         return $cad;
       }
    
    }
    
    
    
/************************************************************************************
*
*Funcion que consulta lapsos contables.
*
*************************************************************************************/    
    function BuscarLapsos()
    { 
       $sql=" select * from cg_conf.cg_lapsos_contables WHERE empresa_id='".SessionGetVar("EMPRESA")."' order by lapso DESC"; 
             
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql;
      return $documentos;
     }
    
     
//   functi
//   $sql1="select count(*) 
//           from terceros
//           where tipo_id_tercero='".$tipo_id."' 
//           and tercero_id='".$id."'
//           or nombre_tercero LIKE '%".strtoupper ($nombre)."%'";   
/************************************************************************************
*
*Funcion que consulta lapsos contables.
*
*************************************************************************************/    
    function Buscardcs($prefijo,$numero)
    { 
       $sql=" select * 
       from 
       ".$this->MOVIMIENTO."
       where prefijo = '".strtoupper($prefijo)."' and  numero=".$numero."
       order by lapso"; 
             
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
     }
    
    
    
/************************************************************************************
*
*Funcion que consulta lapsos contables.
*
*************************************************************************************/    
    function  ColocarEmpresa($empresa)
    { 
       $sql=" select razon_social 
       from empresas
       where empresa_id = '".strtoupper($empresa)."'"; 
             
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
     }
    
    
 
 

/************************************************************************************
*
*Funcion que consulta EL monto del mivimiento.
*
*************************************************************************************/    
    function DescuentoMov($id)
    { 
       $sql=" select * from cg_conf.tmp_cg_mov_contable_d
              where tmp_movimiento_id=".$id.""; 
             
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
     }     

/************************************************************************************
*
*Funcion que consulta documentos de tipos_doc_generales.
*
*************************************************************************************/    
    function MostrarDocumentos()
    { 
       $sql=" select * from cg_conf.cg_lapsos_contables"; 
             
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
     }      
/************************************************************************************
*
*Funcion que consulta tipos de documentos.
*
*************************************************************************************/    
    function TiposDocumento()
    { 
       $sql=" select * from tipos_doc_generales Order by descripcion"; 
             
     
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
     }

/************************************************************************************
*
*Funcion que consulta tipos de documentos.
*
*************************************************************************************/    
function PrefijoWTip_doc($tip_doc)
{ 
       $sql=" select prefijo,documento_id,descripcion from documentos 
              where tipo_doc_general_id='".$tip_doc."' and empresa_id='".SessionGetVar("EMPRESA")."'
              order by descripcion"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

/************************************************************************************
*
*Funcion que consulta tipos de documentos ORGANIZA PREFIJO.
*
*************************************************************************************/    
function PrefijoWTip_docP($tip_doc)
{ 
       $sql=" select prefijo,documento_id,descripcion from documentos 
              where tipo_doc_general_id='".$tip_doc."' and empresa_id='".SessionGetVar("EMPRESA")."'
              order by prefijo"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

/************************************************************************************
*
*Funcion que consulta tipos de documentos.
*
*************************************************************************************/    
function SacarDescripcionDocumento($prefijo)
{ 
       $sql=" select descripcion from documentos 
              where prefijo='".$prefijo."' and empresa_id='".SessionGetVar("EMPRESA")."'"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      //return $sql;
      return $documentos;
}

/************************************************************************************
*
*Funcion que consulta tipos de id terceros.
*
*************************************************************************************/    
function Terceros_id()
{ 
       $sql=" select * from tipo_id_terceros order by indice_de_orden"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

/************************************************************************************
*
*Funcion que consulta paises.
*
*************************************************************************************/    
function Paises()
{ 
       $sql=" select * from tipo_pais order by pais"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

/************************************************************************************
*
*Funcion que consulta paises.
*
*************************************************************************************/    
function DePX($id_pais)
{ 
       $sql="select * 
       from tipo_dptos
       where tipo_pais_id='".$id_pais."'    
       order by departamento"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}
  
  /************************************************************************************
*
*Funcion que consulta paises.
*
*************************************************************************************/    
function DeMX($id_pais,$id_dpto)
{ 
       $sql="select * 
       from tipo_mpios
       where tipo_pais_id='".$id_pais."'    
       and tipo_dpto_id='".$id_dpto."'
       order by municipio"; 
       
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}
/************************************************************************************
*
*Funcion que consulta tipos de documentos2.
*
*************************************************************************************/    
function ConsultarXPrefijo($pre,$lapso)
{ 
       $sql=" select prefijo,numero,documento_id 
              from ".$this->MOVIMIENTO."
              where prefijo='".$pre."' and lapso='".$lapso."' "; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

/************************************************************************************
*
*Funcion que consulta movimientos.
*
*************************************************************************************/    
function ConsultarXLapso($lap,$empresa_id)
{ 
       $sql=" select *
              from 
              ".$this->MOVIMIENTO."
              where lapso='".$lap."' and empresa_id='".$empresa_id."'"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}
/***************************************************************************
* Consultar por lapso con documento 
****************************************************************************/
function ConsultarXLapsoWdoc($lap,$empresa_id,$tip_doc)
{ 
       $sql="SELECT distinct b.prefijo,b.documento_contable_id,b.numero 
            from (select prefijo from documentos where tipo_doc_general_id='".$tip_doc."') as a 
            JOIN 
            (select * from 
            ".$this->MOVIMIENTO." where lapso='".$lap."' and empresa_id='".$empresa_id."') as b 
            on 
            (a.prefijo=b.prefijo)";
       
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

/***************************************************************************
* Consultar por lapso con documento 
****************************************************************************/
function ConsultarXLapsoWdocPre($lap,$empresa_id,$tip_doc)
{ 
       $sql="SELECT distinct b.prefijo 
            from (select prefijo from documentos where tipo_doc_general_id='".$tip_doc."') as a 
            JOIN 
            (select * from 
            ".$this->MOVIMIENTO." where lapso='".$lap."' and empresa_id='".$empresa_id."') as b 
            on 
            (a.prefijo=b.prefijo)";
       
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}
/**************************************************************************************
*
*************************************************************************************/
function PrefijoWTip_docWlapso($tip_doc,$lapso)
{
    $sql=" select prefijo,documento_id from documentos 
    where 
    tipo_doc_general_id='".$tip_doc."' and 
    empresa_id='".SessionGetVar("EMPRESA")."'"; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      return $documentos;


}
/**************************************************************************************
*busca la descripcion de la tabla tipo bloqueo
*************************************************************************************/
function descrip($tip_blo)
{
    $sql=" select tipo_bloqueo_id,descripcion 
    from cg_tipo_bloqueo_movimientos
    where 
    tipo_bloqueo_id='".$tip_blo."'"; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      return $documentos;


}
/**************************************************************************************
*seleccion de movimiento detalle
*************************************************************************************/
function ConsultarMovDet($doc,$lapso)
{
    $sql="select distinct a.*,b.descripcion 
    from cg_mov_".SessionGetVar("EMPRESA").".cg_mov_contable_".SessionGetVar("EMPRESA")."_".$lapso." as a,cg_conf.cg_plan_de_cuentas as b
    where 
    documento_contable_id='".$doc."' and a.cuenta=b.cuenta"; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     //return $sql;
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      //return $sql;
      return $documentos;


}
/**************************************************************************************
*seleccion del proximo tmp_movimiento_id
*************************************************************************************/
function tmp_movimiento_id()
{
    $sql=" select nextval('cg_conf.tmp_cg_mov_contable_d_tmp_movimiento_id_seq'::regclass)"; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      return $documentos;


}


/**************************************************************************************
*seleccion del proximo tmp_movimiento_id
*************************************************************************************/
function doc_contable_id()
{
    $sql ="Select nextval('cg_mov_".SessionGetVar("EMPRESA").".cg_mov_contable_".SessionGetVar("EMPRESA")."_documento_contable_id_seq'::regclass);"; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      //return $sql;
      return $documentos;


}

/*************************************************************************************
*sacar nuevo id para cg_mov_contable
***********************************************************************************/
function nue_mov_contable($lapso)
{
    $sql ="select   nextval('cg_mov_".SessionGetVar("EMPRESA").".cg_mov_contable_".SessionGetVar("EMPRESA")."_".$lapso."_movimiento_contable_id_seq'::regclass)"; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      //return $sql;
      return $documentos;


}

/**********************************************************************************
*sacar datos
***********************************************************************************/
function SacarDatosMovimientoTmp($tmp_id)
{
   GLOBAL $ADODB_FETCH_MODE;
   $sql ="Select * from cg_conf.tmp_cg_mov_contable where tmp_id='".$tmp_id."'"; 
             
     //if(!$resultado = $this->ConexionBaseDatos($sql))
     //return false;
        
     list($dbconn) = GetDBconn();
     $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
     $result = $dbconn->Execute($sql);
     $ADODB_FETCH_MODE = ADODB_FETCH_NUM;      
     $documentos=Array();
      while($datos=$result->FetchRow())
      {
         $documentos=$datos;
       
       //$documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       //$resultado->MoveNext();
     }
  $result->Close();   
     
     $sql ="Select tipo_doc_general_id from documentos where documento_id=".$documentos['documento_id']."";  
     //var_dump($sql);
     $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
     $result1 = $dbconn->Execute($sql);
     $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    //var_dump($result1);
       
     while($datos1=$result1->FetchRow())
      {//var_dump($datos1);
        $documentos['tipo_doc_general_id']=$datos1['tipo_doc_general_id'];  
     }
     $result1->Close();
       
      
      $documentos=str_replace(" ", "",$documentos);
      //var_dump($documentos);
      return $documentos;
}



/**********************************************************************************
*sacar datos tmp_d
***********************************************************************************/
function SacarDatosMovimientos_tmp_d($tmp_id)
{
   GLOBAL $ADODB_FETCH_MODE;
   $sql ="Select * from cg_conf.tmp_cg_mov_contable_d where tmp_id='".$tmp_id."'"; 
             
     list($dbconn) = GetDBconn();
     $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
     $result = $dbconn->Execute($sql);
     $ADODB_FETCH_MODE = ADODB_FETCH_NUM;      
        
     $movimientos=Array();
     while($datos=$result->FetchRow())
      {
        $movimientos[]=$datos;
     }
    
      $result->Close();
      $movimientos=str_replace(" ", "",$movimientos);
      //var_dump($movimientos);
      return $movimientos;
}

/**************************************************************************************
*seleccion del proximo tmp_movimiento_id
*************************************************************************************/
function Sacartmp_Cg_Mov_deb_cre($tmp_id)
{
    $sql=" select total_creditos,total_debitos
            from cg_conf.tmp_cg_mov_contable
            where tmp_id=".$tmp_id.""; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      //return $sql;
      return $documentos;


}
/************************************************************************************
*funcion q guarda en tmp_cg_mov_contable_d
*************************************************************************************/
function Guardar_Mov_db($tmp_movimiento_id,$tmp_id,$dcruce,$empresa,
                        $cuenta_mov,$tipo_id_tercero,$tercero_id,
                        $debito,$credito,$detalle_mov,
                        $centro_de_costo,$base_rtf,$porcentaje_rtf)
{

              $sql="insert into cg_conf.tmp_cg_mov_contable_d 
              values(".$tmp_movimiento_id.",".$tmp_id.",$dcruce,'".$empresa."',
               '".$cuenta_mov."',$tipo_id_tercero,$tercero_id,
               ".$debito.",".$credito.",'".$detalle_mov."',
               ".$centro_de_costo.",".$base_rtf.",".$porcentaje_rtf.")";

     if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="OPERACION INVALIDA";
          return $sql;
          return $cad;
        } 
        else 
         {
           $cad="MOVIMIENTO CREADO SATISFACTORIAMENTE";  
           return $cad;
         }   




}                        

function GetClassDocumentos($tip_doc)
{
  $sql = "SELECT b.tipo_doc_general_id
          FROM tipos_doc_generales as b
          WHERE b.tipo_doc_general_id ='".$tip_doc."' 
          and b.sw_doc_sistema = '1' ";
  
  if(!$resultado = $this->ConexionBaseDatos($sql))
  return $sql;
    
  $documentos=Array();
  while(!$resultado->EOF)
  {    
    $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
   
  $resultado->Close();
   
  if(empty($documentos))
  {
    //echo "jj".$this->MOVIMIENTO."ja";
    return $this->MOVIMIENTO;
  }
  else
  {
    $salida ="cg_mov_".SessionGetVar("EMPRESA"). '."' . "cg_mov_contable_".SessionGetVar("EMPRESA") . "_" . $documentos[0]['tipo_doc_general_id'] . '"';
    return $salida;
  }  
  
}

/***********************************************************************
*Realiza consulta dependiendo de los parametros
************************************************************************/
function SacarMovPN($offset,$prefijo,$numero)
{ 
  if($prefijo=='-1' || $numero=='')
  { 
        $cad="DATOS INCOMPLETOS";
        return $cad;
  
  }
  
  $sqlx="select tipo_doc_general_id 
         from documentos 
         where prefijo='".$prefijo."'";
  
  if(!$resultado = $this->ConexionBaseDatos($sqlx))
  return false;
    
  $tip_doc=Array();
  while(!$resultado->EOF)
  {    
    $tip_doc[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc[0]['tipo_doc_general_id']);
  
 
  $sql1=" select count(*) 
  from ".$MOVIMIENTO."
  where prefijo='".$prefijo."'
  and numero=".$numero." 
  and empresa_id='".SessionGetVar("EMPRESA")."' ";
  
  $this->ProcesarSqlConteo($sql1,10,$offset);     
  
  $sql=" select *
  from ".$MOVIMIENTO."
  where prefijo='".$prefijo."'
  and numero=".$numero." 
  and empresa_id='".SessionGetVar("EMPRESA")."'
  order by fecha_documento,numero   
  limit ".$this->limit." OFFSET ".$this->offset.""; 
  
  if(!$resultado = $this->ConexionBaseDatos($sql))
  {
   //return $sql;
   return false;
  } 
    
  $documentos=Array();
  while(!$resultado->EOF)
  {    
    $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  //return $sql;
  return $documentos;
}

/***********************************************************************
*Realiza consulta dependiendo de los parametros
************************************************************************/
function SacarMov($offset,$lapso,$dia1,$dia2,$tip_doc,$prefijo,$numero)
{ 
  if($lapso=='0' || $tip_doc=='-1' || $prefijo=='1')
  { 
        $cad="DATOS INCOMPLETOS";
        return $cad;
  
  }
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc);
  
  $filtrodia="";
  
  if(!empty($dia1))
  {
      $fecha1=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia1;
      $filtrodia=" and fecha_documento = '".$fecha1."' ";
      if(!empty($dia2))
      {
        if( $dia2 < $dia1) 
        {
          $cad="EL DIA FINAL DEBE SER MAYOR AL DIA INICIAL".$dia1."**".$dia2; 
          return $cad; 
        }
        $fecha1=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia1;
        $fecha2=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia2;
        $filtrodia=" and fecha_documento >= '".$fecha1."' and fecha_documento <= '".$fecha2."' ";    
      
      }
      
    
    
  }
  
  $sql1=" select count(*) 
  from ".$MOVIMIENTO."
  where lapso='$lapso' 
  and prefijo='$prefijo' 
  $filtrodia
  and empresa_id='".SessionGetVar("EMPRESA")."' ";
  
  $this->ProcesarSqlConteo($sql1,10,$offset);     
  
  $sql=" select *
  from ".$MOVIMIENTO."
  where lapso='$lapso' 
  and prefijo='$prefijo' 
  $filtrodia
  and empresa_id='".SessionGetVar("EMPRESA")."'
  order by numero,fecha_documento   
  limit ".$this->limit." OFFSET ".$this->offset.""; 
  
  if(!$resultado = $this->ConexionBaseDatos($sql))
  //return $MOVIMIENTO;
  //return $sql;
  //return false;
    
  $documentos=Array();
  while(!$resultado->EOF)
  {
    $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  //return $MOVIMIENTO;
  //return $sql;
  return $documentos;
}
/***********************************************************************
*Realiza consulta dependiendo de los parametros
************************************************************************/
function SacarMov2($offset,$lapso,$dia1,$dia2,$tip_doc,$prefijo,$numero)
{ 
  if($lapso=='0' || $tip_doc=='-1' || $prefijo=='1')
  { 
        $cad="DATOS INCOMPLETOS";
        return $cad;
  
  }
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc);
  
  $filtrodia="";
  
  if(!empty($dia1))
  {
      $fecha1=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia1;
      $filtrodia=" and fecha_documento = '".$fecha1."' ";
      if(!empty($dia2))
      {
        if( $dia2 < $dia1) 
        {
          $cad="EL DIA FINAL DEBE SER MAYOR AL DIA INICIAL".$dia1."**".$dia2; 
          return $cad; 
        }
        $fecha1=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia1;
        $fecha2=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia2;
        $filtrodia=" and fecha_documento >= '".$fecha1."' and fecha_documento <= '".$fecha2."' ";    
      
      }
      
    
    
  }
  
  $sql1=" select count(*) 
  from ".$MOVIMIENTO."
  where lapso='$lapso' 
  and prefijo='$prefijo' 
  $filtrodia
  and empresa_id='".SessionGetVar("EMPRESA")."' ";
  
  $this->ProcesarSqlConteo($sql1,50,$offset);     
  
  $sql=" select *
  from ".$MOVIMIENTO."
  where lapso='$lapso' 
  and prefijo='$prefijo' 
  $filtrodia
  and empresa_id='".SessionGetVar("EMPRESA")."'
  order by fecha_documento,numero   
  limit ".$this->limit." OFFSET ".$this->offset.""; 
  
  if(!$resultado = $this->ConexionBaseDatos($sql))
  return false;
    
  $documentos=Array();
  while(!$resultado->EOF)
  {    
    $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  return $documentos;
}
/************************************************************************
**para encontrar prefijo y nuero de un doc cruz
**************************************************************************/
function num($dc)
{
$sql=" select prefijo,numero
            from ".$this->MOVIMIENTO."
            where documento_contable_id=".$dc.""; 
             
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
/*******************************
*nom terceros
*********************************/

function Nombre($tercero_id)
{
 $sql=" select *
        from terceros
        where tercero_id='".trim($tercero_id)."'"; 
             
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
function Nombres($tipo_id,$tercero_id)
{
 $sql=" select *
        from terceros
        where tercero_id='".trim($tercero_id)."'
        and tipo_id_tercero='".$tipo_id."'"; 
             
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
/***********************************************************************
*Realiza consulta dependiendo de los parametros
************************************************************************/
         
function ContarSacarMov($lapso,$dia1,$dia2,$tip_doc,$prefijo,$numero)
{ 
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc);
  $filtrodia="";
  
  if(!empty($dia1))
  {
      $fecha1=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia1;
      $filtrodia=" and fecha_documento = '".$fecha1."' ";
      if(!empty($dia2))
      {
        if( $dia2 < $dia1) 
        {
          $cad="EL DIA FINAL DEBE SER MAYOR AL DIA INICIAL".$dia1."**".$dia2; 
          return $cad; 
        }
        $fecha1=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia1;
        $fecha2=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia2;
        $filtrodia=" and fecha_documento >= '".$fecha1."' and fecha_documento <= '".$fecha2."' ";    
      
      }
  }
  
  $sql="select count(*) 
  from ".$MOVIMIENTO."
  where lapso='$lapso' 
  and prefijo='$prefijo' 
  $filtrodia
  and empresa_id='".SessionGetVar("EMPRESA")."' ";


       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      //return $sql;
      return $documentos;
}


/***********************************************************************
*comtar pendientes contablizar
************************************************************************/
         
function ContarPenDocs($lapso,$prefijo,$tip_doc)
{ 
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc);
  $sql="select count(*) 
  from ".$MOVIMIENTO."
  where lapso='$lapso' 
  and prefijo ='$prefijo' 
  and empresa_id='".SessionGetVar("EMPRESA")."' 
  and documento_contable_id IS NULL";


      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      list($documentos)=$resultado->FetchRow();

      $resultado->Close();
      return $documentos;
}
/***********************************************************************
**descripcion del tipo e doc general
***********************************************************************/
function TipoDocumento($tip_doc)
{ 
  
  $sqlx="select descripcion
         from tipos_doc_generales
         where tipo_doc_general_id='".$tip_doc."'";
  
  if(!$resultado = $this->ConexionBaseDatos($sqlx))
  return false;
    
  $tip_dos=Array();
  while(!$resultado->EOF)
  {    
    $tip_dos[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  return $tip_dos;
}
/***********************************************************************
**descripcion del tipo e doc general
***********************************************************************/
function Documentus($prefijo)
{ 
  
  $sqlx="select descripcion
         from documentos
         where prefijo='".$prefijo."'";
  
  if(!$resultado = $this->ConexionBaseDatos($sqlx))
  return false;
    
  $tip_dos=Array();
  while(!$resultado->EOF)
  {    
    $tip_dos[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  return $tip_dos;
}
/***********************************************************************
**contar comn prefijoi y numero
***********************************************************************/
function ContarPenDocs1($prefijo,$numero)
{ 
  
  $sqlx="select tipo_doc_general_id 
         from documentos 
         where prefijo='".$prefijo."'";
  
  if(!$resultado = $this->ConexionBaseDatos($sqlx))
  return false;
    
  $tip_doc=Array();
  while(!$resultado->EOF)
  {    
    $tip_doc[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc[0]['tipo_doc_general_id']);
   $sql="select count(*) 
  from ".$MOVIMIENTO."
  where prefijo ='$prefijo' 
  and numero=".$numero." 
  and empresa_id='".SessionGetVar("EMPRESA")."' 
  and documento_contable_id IS NULL";
  
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;
    
  $tip_doc=Array();
  while(!$resultado->EOF)
  {    
    $tip_doc[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  return $tip_doc;
 
  
}
/***********************************************************************
**contar comn prefijoi y numero
***********************************************************************/
function ContarSacarMovPN($prefijo,$numero)
{ 
  
  $sqlx="select tipo_doc_general_id 
         from documentos 
         where prefijo='".$prefijo."'";
  
  if(!$resultado = $this->ConexionBaseDatos($sqlx))
  return false;
    
  $tip_doc=Array();
  while(!$resultado->EOF)
  {    
    $tip_doc[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc[0]['tipo_doc_general_id']);
  
 
  $sql1=" select count(*) 
  from ".$MOVIMIENTO."
  where prefijo='".$prefijo."'
  and numero=".$numero." 
  and empresa_id='".SessionGetVar("EMPRESA")."'";
  
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;
    
  $tip_doc=Array();
  while(!$resultado->EOF)
  {    
    $tip_doc[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  return $tip_doc;
}
/************************************************************************************
*
*Funcion borrara parametros
*
*************************************************************************************/    
    function EliminarMovDet($id)
    { 
       
      $sql="delete from cg_conf.tmp_cg_mov_contable_d
          where tmp_movimiento_id=".$id."";
      
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida al borrar datos";
          return $cad;
        } 
        else 
         {
           $cad="Movimiento Eliminado Correctamente";  
           return $cad;
         }   
     }
         
/************************************************************************************
*
*Funcion borrara documentos
*
*************************************************************************************/    
    function EliminarDocs($tmp_id)
    { 
       
      $sql="delete from cg_conf.tmp_cg_mov_contable
          where tmp_id=".$tmp_id."";
      
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida al borrar datos";
          return $sql;
          //return $cad;
        } 
        else 
         {
           $cad="DOCUMENTO ELIMINADO CORRECTAMENTE";  
           return $cad;
         }   
     }
  
         
/************************************************************************************
*
*Funcion borrara documentos
*
*************************************************************************************/    
    function EliminarDocsMov($tmp_id)
    { 
       
      $sql="delete from cg_conf.tmp_cg_mov_contable_d
          where tmp_id=".$tmp_id."";
      
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida al borrar datos";
          return $sql;          
          //return $cad;
        } 
        else 
         {
           $cad="MOVIMIENTOS ELIMINADOS CORRECTAMENTE";  
           return $cad;
         }   
     }    
  

 
    /********************************************************************************
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*********************************************************************************/
		function ProcesarSqlConteo($consulta,$limite=null,$offset=null)
		{ 
			$this->offset = 0;
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
			}
			else
			{
				$this->limit = $limite;
			}
			
			if($offset)
			{
				$this->paginaActual = intval($offset);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		

			if(!$result = $this->ConexionBaseDatos($consulta))
				return false;

			if(!$result->EOF)
			{
				$this->conteo = $result->fields[0];
				$result->MoveNext();
			}
			$result->Close();
      
      
			return true;
		  
    }

 function BorrarTemporalMovimiento($tmp_id)
 {
   $sql="delete from cg_conf.tmp_cg_mov_contable where tmp_id=".$tmp_id."";
      if(!$resultado = $this->ConexionBaseDatos($sql))
      {
        $cad="Operacion Invalida al borrar datos";
        return $sql;          
      return $cad;
      }
      else $cad="ok1";
      return $cad;
 }
 
 function BorrarTemporalMovimientoDetalle($tmp_id)
 {
   $sql="delete from cg_conf.tmp_cg_mov_contable_d where tmp_id=".$tmp_id."";
      if(!$resultado = $this->ConexionBaseDatos($sql))
      {
        $cad="Operacion Invalida al borrar datos";
        return $sql;          
      return $cad;
      }
     else $cad="ok2";
     return $cad; 
       
 }
 
  function copiar($datos_movimiento,$datos_movimiento_d)
   {
    //var_dump($datos_movimiento);
     $contabilizador= new ContabilizarDocumento();
     if($contabilizador->SetDocumento($datos_movimiento,true)===false)
     {
       $CAD="TA MAL POR MIVIMINETO"; 
       RETURN $contabilizador->mensajeDeError;
     }
     
     foreach($datos_movimiento_d as $k=>$v)
     {
       if($contabilizador->AddMOV($v)===false)
       {
         $cad="detalles".$contabilizador->ErrMsg();
         RETURN $cad; 
       }
     }
     //$contabilizador->SetDocumento($datos_movimiento,true);

     
     //$contabilizador->AddMOV($datos_movimiento_d);     
     
     if($contabilizador->GenerarDocumentoContable()===false)
     {
       $CAD="mezcla".$contabilizador->ErrMsg();
       //$CAD="mezcla".$contabilizador->GenerarDocumentoContable();
       RETURN $CAD;
     }
     //$contabilizador->GenerarDocumentoContable();   
     if($contabilizador->RetornarDocumentoContable()===false)
     {
       $cad=$contabilizador->ErrMsg();
       return $cad;
     }
     if(is_array($contabilizador->RetornarDocumentoContable()))
     {
       $resultadito=$contabilizador->RetornarDocumentoContable();
       
        $total=$resultadito['RESULTADO_D']." CON PREFIJO  ".$resultadito['prefijo']."  Y CON NUMERO  ".$resultadito['numero'];
        return $total;
     }
     
   }
 
/************************************************************************
*contabilizar un solo doc
**************************************************************************/

function ContabilizarDocx($datos)
   {
     $contabilizador= new ContabilizacionDeDocumentos();
    
    for($i=0;$i<count($datos);$i++)
    {   
        list($empresa_id,$prefijo,$numero,$actualizar) = explode("@", $datos[$i]);  
        
        if($actualizar=="")
        $actualizar=false;
        if($actualizar=="1")
        $actualizar=true;
        
        
        $resultad=$contabilizador->ContabilizarDocumento($empresa_id,$prefijo,$numero,$actualizar);
        if($resultad ===false)
        {
          $CAD="problemas &nbsp;".$contabilizador->Err().$contabilizador->ErrMsg();
          RETURN $CAD;
        }
        else
        {
         $vector[]=$resultad;
        
        }
    } 
     
     return $vector;
     
   }
/**************************************************************************************
*funcion para contabilizar por lapso
****************************************************************************************/   
  function Contabilizarlapso($empresa_id,$prefijo,$lapso,$actualizar=false)
   {
    $cad="";
    $contabilizador= new ContabilizacionDeDocumentos();
     
    $resultado=$contabilizador->ContabilizarLapsoDocumento($empresa_id,$prefijo,$lapso,$actualizar);
    if($resultado === false)
     {
        $cad= "ERRORES : " . $a->Err() . "<br>" . $a->ErrMsg() . "<br>";
        return $cad;
     }
     
    $RETORNOS = $contabilizador->GetRetornoLoteContabilizacion();
    if(is_array($RETORNOS))
    {
        $cad="LAPSO : $lapso   NUMERO DE DOCUMENTOS CONTABILIZADOS : " . count($RETORNOS) . "<br>";
        $cad.= "---------------------------------------------------------------------------<br><br>";

        foreach($RETORNOS as $numero => $detalle)
        {
            if($detalle['RESULTADO'])
            {
                $cad.= "$prefijo $numero : " . $detalle['DETALLE'] . "<br>";
            }
            else
            {
                $cad.= "$prefijo $numero : " . $detalle['TITULO'] . "<br>";
                $cad.= $detalle['DETALLE'] . "<br><br>";
            }
        }
    }
    else
    {
        $cad.= "NO SE CONTABILIZARON DOCUMENTOS<BR><BR>";
        
    }
     
    
    return $cad; 
     
     
   }
   
/**************************************************************************************
*funcion para obtener documentos por lapso
****************************************************************************************/   
  function RevisionLapso($empresa_id,$lapso)
   {
    $cad="";
    $contabilizador= new ContabilizacionDeDocumentos();
     
    $resultado=$contabilizador->GetInformacionDocumentosLapso($empresa_id,$lapso);
    if($resultado === false)
     {
        $cad= "ERRORES : " . $contabilizador->Err() . "<br>" . $contabilizador->ErrMsg() . "<br>";
        return $cad;
     }
    
    if(is_array($resultado))
    {
      return $resultado;  
    }
    else
    {
        $cad.= "NO SE CONTABILIZARON DOCUMENTOS<BR><BR>";
        
        return $cad; 
    }
     
    
    
     
     
   }      


		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
		* @return rst 
		************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				 "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
		/**********************************************************************************
		* Funcion que permite crear una transaccion 
		* @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
		* @param char $num Numero correspondiente a la sentecia sql - por defect es 1
		*
		* @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
		*								 se devuelve nada
		***********************************************************************************/
    //    $sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE ";//Bloqueo de tabla 
//           list($dbconn) = GetDBConn();
//            $dbconn->BeginTrans();
//            $result = $dbconn->Execute($sql);
//       if ($dbconn->ErrorNo() != 0) 
//       {
//         die(MsgOut("Error al iniciar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
//         $dbconn->RollbackTrans();
//         $cad="mal1";http://www.google.com/firefox?client=firefox-a&rls=org.mozilla:en-US:official
//         return $cad;
//         //return false;
//       }
//       else
//       {
//         $sql = "Select  numeracion from documentos ";
//         $sql .= "WHERE  documento_id = ".$datos_movimiento[0]['documento_id']." AND empresa_id = '".$datos_movimiento[0]['empresa_id']."'; ";       
//         $rst = $dbconn->Execute($sql);
//           if (!$rst) 
//           {
//             $this->frmError['MensajeError'] = "ERROR DB : TRANSACCION 1 " . $dbconn->ErrorMsg()."<br> $sql";
//             $dbconn->RollbackTrans();
//             $cad="mal2";
//             return $cad;
//             //return false;
//           }     
//           else
//           {             
//              $numero=Array();
//               while(!$rst->EOF)
//               {
//                 $numero[] = $rst->GetRowAssoc($ToUpper = false);
//                 $rst->MoveNext();
//               }
//           
//           }
//           //--select setval('cg_mov_contable_documento_contable_id_seq', 8000);
//           $sql="Insert into ".$this->MOVIMIENTO."
//                 values(".$nue_doc_id.",'".$datos_movimiento[0]['lapso']."',
//                        '".$datos_movimiento[0]['fecha_documento']."',
//                        '".$datos_movimiento[0]['empresa_id']."',
//                        '".$datos_movimiento[0]['prefijo']."',
//                        '".$numero[0]['numeracion']."',
//                        '".$datos_movimiento[0]['documento_id']."',
//                        '0',
//                        '00',
//                        '".$datos_movimiento[0]['total_debitos']."',
//                        '".$datos_movimiento[0]['total_debitos']."',
//                        '".$datos_movimiento[0]['tipo_id_tercero']."',
//                        '".$datos_movimiento[0]['tercero_id']."',
//                        NOW(),'".UserGetUID()."')";
//           $rst = $dbconn->Execute($sql);
//           if (!$rst) 
//           {
//             $this->frmError['MensajeError'] = "ERROR DB : TRANSACCION 1 " . $dbconn->ErrorMsg()."<br> $sql";
//             $dbconn->RollbackTrans();
//             $cad="mal3";
//             return $sql;
//             //return false;
//           }
//           else
//           {
//                 $sql ="UPDATE documentos";
//                 $sql .=" SET  numeracion = numeracion + 1 ";
//                 $sql .="WHERE documento_id = ".$datos_movimiento[0]['documento_id']." AND empresa_id ='".$datos_movimiento[0]['empresa_id']."'; ";
//                 
//                 
//                 $rst = $dbconn->Execute($sql);
//                 if (!$rst) 
//                 {
//                   $this->frmError['MensajeError'] = "ERROR DB : TRANSACCION 1 " . $dbconn->ErrorMsg()."<br> $sql";
//                   $dbconn->RollbackTrans();
//                   $cad="mal4";
//                   return $sql;
//                   //return false;
//                 }   
//                 $dbconn->CommitTrans(); 
//                 $cad="todo ok";
//                 $sql="";
//                   for($i=0;$i<count($datos_movimiento_d);$i++) 
//                   {     
//                         
//                         if($datos_movimiento_d[$i]['documento_cruce_id']=="")
//                         { 
//                           $datos_movimiento_d[$i]['documento_cruce_id']="NULL";
//                         }
//                         if($datos_movimiento_d[$i]['tipo_id_tercero']=="")
//                         {
//                           $datos_movimiento_d[$i]['tipo_id_tercero']="NULL";
//                         }
//                         else
//                         {
//                           $datos_movimiento_d[$i]['tipo_id_tercero']="'".$datos_movimiento_d[$i]['tipo_id_tercero']."'";
//                         }
//                         if($datos_movimiento_d[$i]['tercero_id']=="")
//                         {
//                           $datos_movimiento_d[$i]['tercero_id']="NULL";
//                           
//                         }
//                         else
//                         {
//                           $datos_movimiento_d[$i]['tercero_id']="'".$datos_movimiento_d[$i]['tercero_id']."'";//$datos_movimiento_d[$i]['tmp_id']
//                         }
//                         //return $datos_movimiento_d[$i]['departamento']; 
//                         if($datos_movimiento_d[$i]['departamento']=='NULL')
//                         {
//                           $datos_movimiento_d[$i]['departamento']='NULL';
//                         }
//                         else
//                         {
//                           $datos_movimiento_d[$i]['departamento']="'".$datos_movimiento_d[$i]['departamento']."'";//
//                         }
//                          
//                         $lapso=substr($datos_movimiento[0]['fecha_documento'],0,4).substr($datos_movimiento[0]['fecha_documento'],5,2);
//                         $nue_mov_cont=$this->nue_mov_contable($lapso);
//                         $sql.="insert into cg_mov_".SessionGetVar("EMPRESA").".cg_mov_contable_".SessionGetVar("EMPRESA")."_".$lapso." values(".$nue_mov_cont[0]['nextval'].",
//                         ".$nue_doc_id.",".$datos_movimiento_d[$i]['documento_cruce_id'].",
//                         '".$datos_movimiento_d[$i]['empresa_id']."','".$datos_movimiento_d[$i]['cuenta']."',
//                         ".$datos_movimiento_d[$i]['tipo_id_tercero'].",".$datos_movimiento_d[$i]['tercero_id'].",
//                         ".$datos_movimiento_d[$i]['debito'].",".$datos_movimiento_d[$i]['credito'].",
//                         '".$datos_movimiento_d[$i]['detalle']."',".$datos_movimiento_d[$i]['departamento'].",
//                         ".$datos_movimiento_d[$i]['base_rtf'].",".$datos_movimiento_d[$i]['porcentaje_rtf'].");";
//                   }  
//                     
//                     if(!$resultado = $this->ConexionBaseDatos($sql))
//                     {
//                       $cad="Operacion Invalida al borrar datos";
//                       return $sql;          
//                       //return $cad;
//                     } 
//                     else
//                     {
//                             $sql="delete from cg_conf.tmp_cg_mov_contable_d where tmp_id=".$datos_movimiento[0]['tmp_id']."";
//                             if(!$resultado = $this->ConexionBaseDatos($sql))
//                             {
//                               $cad="Operacion Invalida al borrar datos";
//                               return $sql;          
//                               //return $cad;
//                             }
//                             else
//                             {
//                                 $sql="delete from cg_conf.tmp_cg_mov_contable where tmp_id=".$datos_movimiento[0]['tmp_id']."";    
//                                 if(!$resultado = $this->ConexionBaseDatos($sql))
//                                   {
//                                     $cad="Operacion Invalida al borrar datos";
//                                     return $sql;          
//                                     //return $cad;
//                                   }  
//                                   else
//                                   {
//                                     $cad="Movimiento Cerrado Satisfactoriamente con Prefijo ".$datos_movimiento[0]['prefijo']."-".$numero[0]['numeracion']."";
//                                     return $cad;  
//                                   }
//                             }
//                     
//                     }
//              
//             }
//            
//            }       
//                    
//                        
//               
	}
?>