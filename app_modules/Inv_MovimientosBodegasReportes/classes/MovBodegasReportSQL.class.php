<?php
  /******************************************************************************
  * $Id: MovBodegasReportSQL.class.php,v 1.1 2007/07/17 22:24:14 jgomez Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.1 $ 
	* 
	* @autor Jaime Gomez
  ********************************************************************************/
	
if (!IncludeClass('BodegasDocumentos'))
{
    die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
}
class MovBodegasReportSQL
{
/***********************
* constructora
*************************/
function MovBodegasReportSQL() {}

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
       {  $cad="no se hizo la insercion";
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
       {  $cad="no se hizo la inserci�";
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
function Perdidas()
{
   $sql=" select
          tipo_perdida_id,
          descripcion
          from
          inv_bodegas_tipos_perdidas
          where
          sw_estado='1'";

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
   {  $cad=$this->frmError['MensajeError'];
      $cad="HAY PROBLEMAS CON LA INSERCION ES POSIBLE QUE ESTE TERCERO YA EXISTE";
     return $cad;
   }
   else
   {      
     //$cad=$sql;
     $cad="EXITO";
     $rst->Close();
     return $cad;
   }
    
}  
/**************************************
* listar aprovechamineto
**************************************/
function Prestamos()
{
  $sql="  select
          tipo_prestamo_id,
          descripcion
          from
          inv_bodegas_tipos_prestamos
          where
          sw_estado='1'";

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

/**************************************
* listar aprovechamineto
**************************************/
function Aprovechar()
{
  $sql="  select
          tipo_aprovechamiento_id,
          descripcion
          from
          inv_bodegas_tipos_aprovechamiento
          where
          sw_estado='1'";

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
/********************************************************
*function para obtener los datos de un documento
*********************************************************/
 function SacarDocumento($empresa_id,$prefijo,$numero)
 {
   $ClassDOC= new BodegasDocumentos();
   $datosDoc=$ClassDOC->GetDoc($empresa_id,$prefijo,$numero,$detalle = true);
   return $datosDoc;
 }
/********************************************************
* function extrae los documentos de bodega
********************************************************/
function ObtenerDocumentosFinal($oset,$empresa_id, $centro_utilidad, $bodega, $usuario_id, $tipo_movimiento, $tipo_doc_bodega_id)
{ $oset=$oset-1;
  $ClassDOC= new BodegasDocumentos();
  $contador=$ClassDOC->GetDocumentos_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id=null, $count=true, $limit=null, $offset=null, $tipo_movimiento, $tipo_doc_bodega_id);
  $limit=20;
  $oset=$limit*$oset;
  $datos=$ClassDOC->GetDocumentos_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id=null, $count=false, $limit, $oset, $tipo_movimiento, $tipo_doc_bodega_id);
  $vector['datos']=$datos;
  $vector['contador']=$contador;
  return $vector;
}
/***********************************************
* funcion para consultar documentos
***************************************************/
function ObtenerTiposDocumentos($empresa_id, $centro_utilidad, $bodega, $tipo_movimiento, $usuario)
{
 $ClassDOC= new BodegasDocumentos();
 $tipos_doc=$ClassDOC->GetTiposDocumentos_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $tipo_movimiento, $usuario_id=null);
 return $tipos_doc;
}

function ObtenerClasesDocumentos($empresa_id, $centro_utilidad, $bodega, $usuario_id=null)
{
   $ClassDOC= new BodegasDocumentos(); 
   $clases=$ClassDOC->GetTiposMovimiento_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id=null);
   return $clases; 
}
/***********************************************************************************
*
**********************************************************************************/

function CrearDocumentoOriginal($bodegas_doc_id,$doc_tmp_id,$usuario_id)
{
    $ClassDOC= new BodegasDocumentos();
    $OBJETO=$ClassDOC->GetOBJ($bodegas_doc_id);
    $resultado=$OBJETO->CrearDocumento($doc_tmp_id,$usuario_id);
    ECHO $OBJETO->Err().$OBJETO->ErrMsg();
    //VAR_DUMP($resultado);
    return $resultado;//$resultado
}


function EliminarDocTemporal($bodegas_doc_id,$doc_tmp_id,$usuario_id)
{
    $ClassDOC= new BodegasDocumentos();
    $OBJETO=$ClassDOC->GetOBJ($bodegas_doc_id);
    $resultado=$OBJETO->DelDocTemporal($doc_tmp_id,$usuario_id);
    return $resultado;
}

function ObtenerDocsTmpUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id)
   {
    $ClassDOC= new BodegasDocumentos();
    $datos=$ClassDOC->GetDocumentosTMP_BodegaUsuario($empresa_id,$centro_utilidad,$bodega,$usuario_id);
    //var_dump($datos);
    return $datos;

   }


/************************************************************************************
*
*Funcion que consulta nombre de la empresa
*
*************************************************************************************/    
    function  ColocarDptos($CENTRO)
    { 
       $sql=" select departamento,  descripcion
       from departamentos
       
       where
        centro_utilidad='".$CENTRO."'
        AND    
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

   
/************************************************************************************
*
*Funcion que consulta nombre de la empresa
*
*************************************************************************************/    
    function  ColocarCentro2()
    { 
       $sql=" select descripcion,centro_utilidad
       from centros_utilidad
       where empresa_id='".SessionGetVar("EMPRESA")."'";
             
     
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
*Funcion que consulta nombre de la empresa
*
*************************************************************************************/    
    function  ColocarCentro1($centro)
    { 
       $sql=" select descripcion,centro_utilidad
       from centros_utilidad
       where  centro_utilidad = '".strtoupper($centro)."'
       and empresa_id='".SessionGetVar("EMPRESA")."'";
             
     
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


   
/*******************
* bodegas name
********************/
function bodegasname1($bodegax,$centro)
{ 
        $sql=" select descripcion,bodega
              from bodegas
              where centro_utilidad='".$centro."'
              AND bodega <> '$bodegax'";
             
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

   
/*******************
* bodegas name
********************/
function bodegasname($bodegax)
{ 
       $sql=" select descripcion  from bodegas
              where bodega='$bodegax'";
             
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

   

/****************************************************************
*funcion para sacar los documentos de la bodega
*******************************************************************/
function PonerDocumentosBodega($usuario,$empresa,$cent_utility,$bodega)
{
    $retorno = BodegasDocumentos::GetTipoDocumentosUsuario($empresa,$cent_utility,$bodega,$usuario);
    return $retorno;
}

/***************************************************************
* fUNCION PARA COLOCAR LAS BODEGAS 
******************************************************************/
function ColocarBodegas($usuario,$empresa)
{
    $documentos=BodegasDocumentos::GetBodegasUsuario($empresa,$usuario);
    return $documentos;
}



/******************************************************************************
*ELIMINAR PRODUCTO CUADRADO
********************************************************************************/
function EliminarAjuste($toma_fisica_id,$etiqueta)
{
 
$sql="DELETE FROM inv_toma_fisica_update
      WHERE
       toma_fisica_id=".$toma_fisica_id."
       AND etiqueta=".$etiqueta;
      
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida al borrar datos";
          return $cad;
        } 
        else 
         {
           $cad="Ajuste Eliminada Correctamente";
           return $cad;
         }   
}




/*************************************************************************/
function AddCuadrar($toma_fisica_id,
            $etiqueta,
            $num_conteo,
            $sw_manual,
            $empresa_id,
            $centro_utilidad,
            $bodega,
            $codigo_producto,
            $existencia,
            $nueva_existencia)
{

    $sql="INSERT INTO inv_toma_fisica_update(
    toma_fisica_id,
    etiqueta,
    num_conteo,
    sw_manual,
    empresa_id,
    centro_utilidad,
    bodega,
    codigo_producto,
    existencia,
    nueva_existencia)
    VALUES (".$toma_fisica_id.",
            ".$etiqueta.",
            ".$num_conteo.",
            '".$sw_manual."',
            '".$empresa_id."',
            '".$centro_utilidad."',
            '".$bodega."',
            '".$codigo_producto."',
            ".$existencia.",
            ".$nueva_existencia.")";


if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la insercion";
         return $cad;
         
       }
      else
       {      
         $cad="Producto Cuadrado Satisfactoriamente";
         $rst->Close();
         return $cad;
       }

}
function GetNoCuadre($toma,$etiqueta)
{
  $sql="SELECT
          x.*,
          y.conteo as conteo_1,
          CASE WHEN y.usuario_valido IS NULL THEN '0' ELSE '1' END as validacion_conteo_1,
          (x.existencia - y.conteo) as diferencia_1,
          z.conteo as conteo_2,
          CASE WHEN z.usuario_valido IS NULL THEN '0' ELSE '1' END as validacion_conteo_2,
          (x.existencia - z.conteo) as diferencia_2,
          w.conteo as conteo_3,
          CASE WHEN w.usuario_valido IS NULL THEN '0' ELSE '1' END as validacion_conteo_3,
          (x.existencia - w.conteo) as diferencia_3
      FROM
      (
          SELECT
              a.toma_fisica_id,
              a.etiqueta,
              a.centro_utilidad,
              a.bodega,
              a.empresa_id,
              b.codigo_producto,
              b.descripcion,
              b.unidad_id,
              c.descripcion as descripcion_unidad,
              e.existencia
          FROM
              inv_toma_fisica_d as a,
              inventarios_productos as b,
              unidades as c,
              existencias_bodegas as e
      
          WHERE
              a.toma_fisica_id = ".$toma."
              AND a.etiqueta = ".$etiqueta."
              AND b.codigo_producto = a.codigo_producto
              AND c.unidad_id = b.unidad_id
              AND e.empresa_id = a.empresa_id
              AND e.centro_utilidad  = a.centro_utilidad
              AND e.bodega = a.bodega
              AND e.codigo_producto = a.codigo_producto
      
      ) AS x LEFT JOIN inv_toma_fisica_conteos AS y
      ON(
          y.toma_fisica_id = x.toma_fisica_id
          AND y.etiqueta = x.etiqueta
          AND y.num_conteo = 1
      )
      LEFT JOIN inv_toma_fisica_conteos AS z
      ON(
          z.toma_fisica_id = x.toma_fisica_id
          AND z.etiqueta = x.etiqueta
          AND z.num_conteo = 2
      )
      LEFT JOIN inv_toma_fisica_conteos AS w
      ON(
          w.toma_fisica_id = x.toma_fisica_id
          AND w.etiqueta = x.etiqueta
          AND w.num_conteo = 3
      )";

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

function GetValidacionCont($toma_fisica_id,$etiqueta,$num_conteo)
{
  $sql="
      SELECT
      CASE WHEN usuario_valido IS NULL THEN '0' ELSE '1' END as validado
      FROM inv_toma_fisica_conteos
      WHERE toma_fisica_id = $toma_fisica_id
      AND etiqueta = $etiqueta
      AND num_conteo = $num_conteo
   ";
  
  if(!$resultado = $this->ConexionBaseDatos($sql)) return false;
  list($salida)=$resultado->FetchRow();
  $resultado->Close();
  return $salida;
}

function SacarNoCuadroC3($toma)
{
    $sql="SELECT
    etiqueta,
    codigo_producto,
    descripcion,
    costo,
    existencia,
    descripcion_unidad,
    conteo_1,
    conteo_2,
    conteo_3,
    diferencia_3,
    diferencia_1con3,
    diferencia_2con3,
    validacion_conteo_3

FROM tomas_fisicas
WHERE toma_fisica_id=".$toma."
AND nueva_existencia IS NULL
AND conteo_3 IS NOT NULL";
    
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
*Funcion que consulta nombre de la empresa
*
*************************************************************************************/    
    function  ColocarCentro($centro)
    { 
       $sql=" select descripcion
       from centros_utilidad
       where  centro_utilidad = '".strtoupper($centro)."'
       and empresa_id='".SessionGetVar("EMPRESA")."'";
             
     
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
*Funcion que consulta nombre de la empresa
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


/*********************************************
*actualizacion para la validacion de listas
**********************************************/
function ValidarActualizarConteo($datos)
{
  
   $sql="";
 for($i=0;$i<count($datos);$i++)
 {
  
   list($toma_fisica_id,$etiqueta,$num_conteo,$cantidad) = explode("@", $datos[$i]);
   $sql.=" UPDATE inv_toma_fisica_conteos
          SET conteo=".$cantidad.",
              usuario_valido=".UserGetUID().",
              fecha_validacion=now()
          WHERE
          toma_fisica_id=".$toma_fisica_id."
          AND etiqueta=".$etiqueta."
          AND num_conteo=".$num_conteo.";";
          
 }
   //return $sql;
      if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida";
          return $this->frmError['MensajeError'];//$cad;
        } 
        else 
         {
           $cad="Productos Validados Exitosamente";
           return $cad;
         }   
 

}

 function GetNumeroLista($toma_fisica_id)
 {
    $sql  = "LOCK TABLE inv_toma_fisica_numeros_listas IN ROW EXCLUSIVE MODE;";
    $sql .= "SELECT numero_lista FROM inv_toma_fisica_numeros_listas WHERE toma_fisica_id = $toma_fisica_id;";

    if(!$resultado = $this->ConexionBaseDatos($sql)) return false;

    $val_retorno=1;
    
    if($resultado->EOF)
    {
        $sql = "INSERT INTO inv_toma_fisica_numeros_listas(toma_fisica_id,numero_lista) VALUES ($toma_fisica_id,2);";
        if(!$this->ConexionBaseDatos($sql)) return false;
    }
    else
    {
        list($val_retorno)=$resultado->FetchRow();
        $resultado->Close();
        
        $sql = "UPDATE inv_toma_fisica_numeros_listas SET numero_lista = numero_lista + 1 WHERE toma_fisica_id = $toma_fisica_id;";
        if(!$this->ConexionBaseDatos($sql)) return false;
    }
    return $val_retorno;
 }


/************************************************************************************
*Funcion que lista cuentas
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
 function ContarCuentasStip($toma_fisica,$aumento)
 { 
     
          $sql1="select count(*)
          FROM
          inv_toma_fisica_d as b,
          inventarios_productos as c,
          unidades as d
          WHERE
          b.toma_fisica_id = ".$toma_fisica."
          ".$aumento."
          AND c.codigo_producto = b.codigo_producto
          AND d.unidad_id = c.unidad_id";
       
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
        $resultado->fields[1] = ereg_replace("�", "E", $resultado->fields[1]); 
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
      WHERE
      sw_activa = '1'
      order by empresa_id"; //
       
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        $resultado->fields[3] = strtoupper($resultado->fields[3]);
        $resultado->fields[3] = ereg_replace("�", "E", $resultado->fields[3]); 
        $documentos[$resultado->fields[3]] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
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
function NombreUsu($usuario_id)
{
 $sql=" select nombre
        from system_usuarios
        where usuario_id='".trim($usuario_id)."'";
             
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


	}
?>