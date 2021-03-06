<?php
/******************************************************************************
* $Id: MovBodegasAdminSQL.class.php,v 1.2 2011/05/19 22:19:10 hugo Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @package IPSOFT-SIIS
*
    * $Revision: 1.2 $
    *
    * @autor Jaime Gomez
********************************************************************************/
	
if (!IncludeClass('BodegasDocumentos'))
{
    die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
}
/**
 Clase que contiene todas las consultas hacia la base de datos 
**/
class MovBodegasAdminSQL
{

    /**
    * constructor
    **/
    function MovBodegasAdminSQL() {}
 

    /**
    * Funcion que consulta lapsos contables.
    * @return listado con todos los LAPSOS CONTABLES CREADOS para la empresa.
    **/    
    function BuscarLapsos()
    { 
        $sql=" select * from cg_conf.cg_lapsos_contables WHERE empresa_id='".SessionGetVar("EMPRESA")."'  AND sw_estado = 1 order by lapso DESC"; 
                
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
    
     

    /**
    * metodo para obtener un listado de todos los subgrupos de los productos
    * @param string $Grupo_id de productos
    * @param string $clase de productos
    * @return listado con todos los grupos de proudctos que contiene el inventario
    **/  
    function SacarSubClases($Grupo_id,$clase)
    {
  
        $sql="SELECT *
                    
              FROM
              inv_subclases_inventarios
              WHERE
              grupo_id='$Grupo_id'
              AND clase_id='$clase'";
  
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return $this->frmError['MensajeError'];
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
     
    /**
    * metodo para obtener los datos de todos los grupos de productos
    * @param string $codigo del producto
    * @return listado con todos los grupos de proudctos que contiene el inventario
    **/  
    function SacarClases($Grupo_id)
    {
  
        $sql="SELECT *
                    
                FROM
                inv_clases_inventarios
                
                WHERE 
                grupo_id='$Grupo_id'";
  
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return $this->frmError['MensajeError'];
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
  
    /**
    * metodo para obtener los datos de todos los grupos de productos
    * @param string $codigo del producto
    * @return listado con todos los grupos de proudctos que contiene el inventario
    **/      
    function GetGrupos()
    {
  
        $sql="SELECT *
                    
                FROM
                inv_grupos_inventarios
                
                ";
  
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return $this->frmError['MensajeError'];
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

    /**
    * metodo para obtener la descripcion de la unidad de los medicamentos
    * @param string $codigo del producto
    * @return listado de centros de utilidad filtrados por el codigo de la empresa
    **/   
  
    function Get_Descripcion_Unidad($codigo)
    {
  
        $sql="SELECT
                    a.descripcion,
                a.descripcion_abreviada,
                a.unidad_id,
                b. descripcion as unidad_producto
        
                FROM
                inventarios_productos as a,
                unidades as b
                WHERE
                
                a.codigo_producto='$codigo'
                AND a.unidad_id=b.unidad_id";
  
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return $this->frmError['MensajeError'];
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

    /**
    * metodo para obtener los prefijos
    * @param string $empresa_id
    * @return listado de centros de utilidad filtrados por el codigo de la empresa
    **/

    function Get_Prefijos($empresa,$centro,$bodega)
    {
        $sql="SELECT
                a.tipo_doc_general_id,
                a.prefijo,
                a.descripcion as descripcion_documento
                FROM
                documentos as a,
                tipos_doc_generales as b,
                inv_bodegas_documentos as c,
                bodegas as d
                WHERE
                a.empresa_id='$empresa'
                AND a.tipo_doc_general_id=b.tipo_doc_general_id
                AND b.sw_doc_sistema='1'
                AND b.inv_tipo_movimiento IS NOT NULL
                AND c.empresa_id='$empresa'
                AND c.centro_utilidad='$centro'
                AND c.bodega='$bodega'
                AND c.empresa_id=d.empresa_id
                AND c.centro_utilidad=d.centro_utilidad
                AND c.bodega=d.bodega
                AND a.empresa_id=c.empresa_id
                AND a.documento_id=c.documento_id
                AND a.empresa_id=c.empresa_id
                ORDER BY 1";
                
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

    /**
    * centros de utility
    * @param string $empresa_id
    * @return listado de centros de utilidad filtrados por el codigo de la empresa
    **/
    function GetCentros_de_Utility($empresa_id)
    { 
        $sql="SELECT 
              centro_utilidad, 
              descripcion
              
              FROM
              centros_utilidad
              
              WHERE
              empresa_id='$empresa_id'";
             
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

    /**
    * Funcion donde se obtienen las bodegas y los centros de utilidad a los cuales tiene
    * permisos
    *
    * @param string $empresa_id Identificador de la empresa
    * @param integer $usuario Identificador del usuario
    *
    * @return mixed
    **/
    function GetCentros_de_Utility1($empresa_id,$usuario)
    { 
      $sql  = "SELECT BG.descripcion AS bodega_descripcion, ";
      $sql .= "       BG.bodega, ";
      $sql .= "       BG.centro_utilidad, ";
      $sql .= "       BG.lapso_cerrar, ";
      $sql .= "       CU.descripcion AS centro_descripcion, ";
      $sql .= "       PR.sw_cierre ";
      $sql .= "FROM   inv_bodegas_userpermisos_admin PR, ";
      $sql .= "       bodegas BG, ";
      $sql .= "       centros_utilidad CU ";
      $sql .= "WHERE  PR.usuario_id = ".$usuario." ";
      $sql .= "AND    PR.empresa_id = '".$empresa_id."'  ";
      $sql .= "AND    PR.empresa_id = BG.empresa_id  ";
      $sql .= "AND    PR.centro_utilidad = BG.centro_utilidad ";
      $sql .= "AND    PR.bodega = BG.bodega ";
      $sql .= "AND    BG.empresa_id = CU.empresa_id ";
      $sql .= "AND    BG.centro_utilidad = CU.centro_utilidad ";
                   
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[3]][$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * metodo para obtener las bodegas filtrados por el codigo de la empresa y del centro de utilidad
    * @param string $empresa_id
    * @param string $centro
    * @return listado de bodegas filtrados por el codigo de la empresa y el centro de utilidaden 
    **/
    function GetBodegas1($empresa_id,$centro)
    { 
      
      $sql="SELECT
              a.descripcion,
              a.bodega,
              b.sw_cierre
                          
              FROM
              bodegas as a,
              inv_bodegas_userpermisos_admin as b
              
              WHERE
              a.empresa_id='$empresa_id'
              AND a.centro_utilidad='$centro' 
              AND a.empresa_id=b.empresa_id
              AND a.centro_utilidad=b.centro_utilidad
              AND a.bodega=b.bodega
              AND b.usuario_id='".UserGetUID()."'
              ORDER BY 1";
         //print_r($sql);    
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

        $documentos=Array();
        while(!$resultado->EOF)
        {
            $resultado->fields[0] = strtoupper($resultado->fields[0]);
            $resultado->fields[0] = ereg_replace("???", "E", $resultado->fields[0]); 
            $documentos[$resultado->fields[0]] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        
        $resultado->Close();
        return $documentos;
    }

    /**
    * metodo para obtener las bodegas filtrados por el codigo de la empresa y del centro de utilidad
    * @param string $empresa_id
    * @param string $centro
    * @return listado de bodegas filtrados por el codigo de la empresa y el centro de utilidaden 
    **/
    function GetBodegas($empresa_id,$centro)
    { 
        $sql="SELECT
              descripcion,
              bodega
              
              FROM
              bodegas
              
              WHERE
              empresa_id='$empresa_id'
              AND centro_utilidad='$centro'";
             
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
 
 
    function BuscarPBodegas($empresa_id,$centro,$offset)
    { 
        $sql="SELECT b.*,a.descripcion as bodega_desc,c.descripcion as nombrepro,d.descripcion as unidades

              FROM  bodegas as a, existencias_bodegas as b, inventarios_productos as c, unidades as d

              WHERE b.empresa_id='".$empresa_id."'

              AND   b.centro_utilidad='".$centro."'

              AND   b.empresa_id=a.empresa_id

              AND   b.centro_utilidad=a.centro_utilidad 

              AND   a.bodega=b.bodega
              AND   c.codigo_producto=b.codigo_producto
              AND   c.unidad_id=d.unidad_id              ";
  
  
  
if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",10,$offset))
       return false;
     
         
     $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";



  
  //      $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",12,$offset); 
              
    //          $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset."";
          // print_r($sql);    
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
	

	
	
	
	//2012 05 10 kardex de productos sin lote Juli
	
	    function Listado_Documentos_por_Producto($empresa_id,$centro_id,$bodega,$codigo_producto,$LapsoInicial,$LapsoFinal,$tipo_movimiento,$tipo_doc_general_id)
    { 
	
	if(empty($LapsoInicial)||empty($LapsoFinal)){
		$filtro ="";
		$filtro1 ="";
		
	}
	
	 if($LapsoInicial!="--")
           {
            $filtro = " AND cast(a.fecha_registro as date) >= '".$LapsoInicial."' ";
            $filtro1 = " AND cast(b.fecha_registro as date ) >= '".$LapsoInicial."' ";
          }
            
            if($LapsoFinal!="--")
           {
		   
            $filtro .= " AND cast(a.fecha_registro as date) <= '".$LapsoFinal."' ";
            $filtro1 .= " AND cast(b.fecha_registro as date) <= '".$LapsoFinal."' ";
           }
                     

        if($tipo_movimiento!="")
        {
        $filtro2  ="  AND d.inv_tipo_movimiento = '".$tipo_movimiento."' ";
        $filtro3  ="  AND c.tipo_movimiento = '".$tipo_movimiento."' ";
        }
        
        if($tipo_doc_general_id!="")
        {
        $filtro2 .="   AND d.tipo_doc_general_id = '".$tipo_doc_general_id."' ";
        //$filtro3 .="  AND c.v_tipo_movimiento = '".$tipo_movimiento."' ";
        }
	
	
	
	GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
	
        $sql="SELECT 

DATOS.tipo,
      DATOS.tipo_movimiento,
            DATOS.fecha,
                        DATOS.fecha_registro,
                        DATOS.prefijo,
                        DATOS.numero,
                       SUM(DATOS.cantidad) AS cantidad, 
                        DATOS.costo,
                        DATOS.usuario,
                        DATOS.nombre,
                        DATOS.bodegas_doc_id,
                        DATOS.observacion,
                        DATOS.codigo_producto,
 						fc_descripcion_producto(DATOS.codigo_producto) as nombre,
                        DATOS.tipo_doc_bodega_id


            FROM
            (
                (
                    SELECT
                        CASE WHEN d.inv_tipo_movimiento IN ('E','T')  THEN 'EGRESO'  ELSE 'INGRESO' END as tipo,
                        d.inv_tipo_movimiento as tipo_movimiento,
                        to_char(a.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        a.fecha_registro,
                        a.prefijo,
                        a.numero,
                       (b.cantidad),
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        NULL as bodegas_doc_id,
                        a.observacion,
                        b.codigo_producto,
                        d.tipo_doc_general_id as tipo_doc_bodega_id

                    FROM
                        inv_bodegas_documentos as e,
                        inv_bodegas_movimiento as a,
                        inv_bodegas_movimiento_d as b,
                        documentos as c,
                        tipos_doc_generales as d,
                        system_usuarios as f
                    WHERE
                        e.empresa_id = '$empresa_id'
                        AND e.centro_utilidad = '$centro_id'
                        AND e.bodega = '$bodega'
                        AND a.documento_id = e.documento_id
                        AND a.empresa_id = e.empresa_id
                        AND a.centro_utilidad = e.centro_utilidad
                        AND a.bodega = e.bodega
                       	".$filtro."			
                        AND b.empresa_id = a.empresa_id
                        AND b.prefijo = a.prefijo
                        AND b.numero = a.numero
                        AND b.codigo_producto = '$codigo_producto'
                        AND c.documento_id = a.documento_id
                        AND c.empresa_id = a.empresa_id
                        AND d.tipo_doc_general_id = c.tipo_doc_general_id
                        AND f.usuario_id = a.usuario_id
						".$filtro2."
						--and cast(a.fecha_registro as date) between '2012-02-15' and '2012-02-15'
             
                )
                UNION ALL
                (
                    SELECT
                        CASE WHEN d.cargo = 'IMD'  THEN 'EGRESO' WHEN d.cargo = 'DIMD' THEN 'INGRESO' ELSE '?' END as tipo,
                        CASE WHEN d.cargo = 'IMD'  THEN 'C' WHEN d.cargo = 'DIMD' THEN 'D' ELSE '?' END as tipo_movimiento,
                        to_char(b.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        b.fecha_registro,
                        c.prefijo,
                        b.numeracion as numero,
                        (a.cantidad),     
                        a.total_costo as costo,
                        f.usuario,
                        f.nombre,
                        b.bodegas_doc_id,
                        'Cuenta No.' || d.numerodecuenta as observacion,
                        a.codigo_producto,
                        '' as tipo_doc_bodega_id

                    FROM
                        bodegas_documentos_d as a,
                        bodegas_documentos as b,
                        bodegas_doc_numeraciones as c,
                        cuentas_detalle as d,
                        system_usuarios as f

                    WHERE
                        c.empresa_id = '$empresa_id'
                        AND c.centro_utilidad = '$centro_id'
                        AND c.bodega = '$bodega'
                        AND b.bodegas_doc_id = c.bodegas_doc_id
                        ".$filtro1."
                        AND a.bodegas_doc_id = b.bodegas_doc_id
                        AND a.numeracion = b.numeracion
                        AND a.codigo_producto = '$codigo_producto'
                        AND d.consecutivo = a.consecutivo
                        AND f.usuario_id = b.usuario_id
						".$filtro3."
						--and cast(b.fecha_registro as date) between '2012-02-15' and '2012-02-15'
                     
                       
                )
                UNION ALL
                (
                    SELECT
                        'INGRESO' as tipo,
                        d.inv_tipo_movimiento as tipo_movimiento,
                        to_char(a.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha,
                        a.fecha_registro,
                        a.prefijo,
                        a.numero,                       
                        (b.cantidad),                   
                        round(b.total_costo/b.cantidad) as costo,
                        f.usuario,
                        f.nombre,
                        NULL as bodegas_doc_id,
                        'BODEGA ORIGEN ['||t.centro_utilidad_destino ||']['|| t.bodega_destino ||'] ' || a.observacion as observacion,
                        b.codigo_producto,
                        d.tipo_doc_general_id as tipo_doc_bodega_id

                    FROM
                        inv_bodegas_movimiento_traslados as t,
                        inv_bodegas_movimiento as a,
                        inv_bodegas_movimiento_d as b,
                        documentos as c,
                        tipos_doc_generales as d,
                        system_usuarios as f
                    WHERE
                        t.empresa_id = '$empresa_id'
                        AND t.centro_utilidad_destino = '$centro_id'
                        AND t.bodega_destino = '$bodega'
                        AND a.empresa_id = t.empresa_id
                        AND a.prefijo = t.prefijo
                        AND a.numero = t.numero
                        ".$filtro."
                        AND b.empresa_id = a.empresa_id
                        AND b.prefijo = a.prefijo
                        AND b.numero = a.numero
                        AND b.codigo_producto = '$codigo_producto'
                        AND c.documento_id = a.documento_id
                        AND c.empresa_id = a.empresa_id
                        AND d.tipo_doc_general_id = c.tipo_doc_general_id
                        AND f.usuario_id = a.usuario_id
						".$filtro2."
                        --and cast(a.fecha_registro as date) between '2012-02-15' and '2012-02-15'

                )
                UNION ALL
                (
                    SELECT 
                        CASE WHEN c.tipo_movimiento IN ('E','T')  THEN 'EGRESO'  ELSE 'INGRESO' END as tipo,
                        c.tipo_movimiento,
                        to_char(b.fecha_registro,'YYYY-MM-DD HH24:MI') as fecha, 
                        b.fecha_registro,
                        c.prefijo, 
                        b.numeracion as numero,
                       (a.cantidad),
                        a.total_costo as costo, 
                        f.usuario, 
                        f.nombre, 
                        b.bodegas_doc_id,
                        b.observacion,
                        a.codigo_producto,
						'' as tipo_doc_bodega_id
                        
                                FROM 
                                bodegas_documentos_d as a, 
                                bodegas_documentos as b, 
                                bodegas_doc_numeraciones as c, 
                                system_usuarios as f 
                                            WHERE 
                                            c.empresa_id = '$empresa_id' 
                                            AND c.centro_utilidad = '$centro_id'
                                            AND c.bodega = '$bodega' 
                                            AND b.bodegas_doc_id = c.bodegas_doc_id 
                                            ".$filtro1."
                                            AND a.bodegas_doc_id = b.bodegas_doc_id 
                                            AND a.numeracion = b.numeracion 
                                            AND a.codigo_producto = '$codigo_producto' 
                                            AND  f.usuario_id = b.usuario_id 
                                            ".$filtro3."
											--and cast(b.fecha_registro as date) between '2012-02-15' and '2012-02-15'
                                            AND a.consecutivo NOT IN
                                                                   (
                                                                   select 
                                                                   consecutivo
                                                                   from
                                                                   cuentas_detalle
                                                                   where
                                                                   empresa_id ='$empresa_id' 
                                                                   AND centro_utilidad = '$centro_id'
																   AND bodega = '$bodega' 
																   AND consecutivo IS NOT NULL
																   AND a.consecutivo = consecutivo
                                                                    )

                )
            ) AS DATOS
            
            --where cast(DATOS.fecha_registro as date) between '2012-02-15' and '2012-02-15'
group by 1,2,3,4,5,6,8,9,10,11,12,13,14,15
            ORDER BY DATOS.fecha
		";
  
  
  /*
if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",10,$offset))
       return false;
     
         
     $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";
*/


  
  //      $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",12,$offset); 
              
    //          $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset."";
          // print_r($sql);    
       

/*
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
		
		*/
		
		 $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        //$dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

      if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        //$retorno = array();

        while($lista = $result->FetchRow())
        {
            $fila['KARDEX'][] = $lista;
        }
        $result->Close();

        return $fila;
		
    }
 

	
	
 
    /**
    * Metodo para obtener el tipo de departamento id de un departamento especifico
    * @param string  $departamentox codigo de la empresa
    * @return $documentos con el tipo departamento id
    **/
    
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

    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/

    function SacarDocumento($empresa_id,$prefijo,$numero)
    {
        $ClassDOC= new BodegasDocumentos();
        $datosDoc=$ClassDOC->GetDoc($empresa_id,$prefijo,$numero,$detalle = true);
        return $datosDoc;
    }
 
    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/
    function SacarDocumento1($bodegas_doc_id,$numero,$codigo_producto)
    {
        //echo $bodegas_doc_id.$numero;
        $ClassDOC= new BodegasDocumentos();
        $datosDoc=$ClassDOC->GetDoc_ModeloAnterior($bodegas_doc_id,$numero,$detalle=true,$codigo_producto);
        return $datosDoc;
    }

    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/
    function ObtenerDocumentosFinal($oset,$empresa_id, $centro_utilidad, $bodega, $usuario_id, $tipo_movimiento, $tipo_doc_bodega_id)
    {
        $oset=$oset-1;
        $ClassDOC= new BodegasDocumentos();
        $contador=$ClassDOC->GetDocumentos_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id=null, $count=true, $limit=null, $offset=null, $tipo_movimiento, $tipo_doc_bodega_id);
        $limit=20;
        $oset=$limit*$oset;
        $datos=$ClassDOC->GetDocumentos_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id=null, $count=false, $limit, $oset, $tipo_movimiento, $tipo_doc_bodega_id);
        $vector['datos']=$datos;
        $vector['contador']=$contador;
        return $vector;
    }

    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/
    function ObtenerTiposDocumentos($empresa_id, $centro_utilidad, $bodega, $tipo_movimiento, $usuario)
    {
        $ClassDOC= new BodegasDocumentos();
        $tipos_doc=$ClassDOC->GetTiposDocumentos_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $tipo_movimiento, $usuario_id=null);
        return $tipos_doc;
    }

    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/
    function ObtenerClasesDocumentos($empresa_id, $centro_utilidad, $bodega, $usuario_id=null)
    {
        $ClassDOC= new BodegasDocumentos(); 
        $clases=$ClassDOC->GetTiposMovimiento_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id=null);
        return $clases; 
    }

    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/
    function ObtenerDocsTmpUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id)
    {
        $ClassDOC= new BodegasDocumentos();
        $datos=$ClassDOC->GetDocumentosTMP_BodegaUsuario($empresa_id,$centro_utilidad,$bodega,$usuario_id);
        //var_dump($datos);
        return $datos;
    }


    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/
    function  ColocarDptos($CENTRO)
    { 
        $sql=" select departamento,  descripcion
               from departamentos
               where
               centro_utilidad='".$CENTRO."'
               AND empresa_id='".SessionGetVar("EMPRESA")."'";
             
     
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

   
    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/
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


    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/

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


   
    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/
    
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

   
    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/
    
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



    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/

    function PonerDocumentosBodega($usuario,$empresa,$cent_utility,$bodega)
    {
        $retorno = BodegasDocumentos::GetTipoDocumentosUsuario($empresa,$cent_utility,$bodega,$usuario);
        return $retorno;
    }

    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/
    function ColocarBodegas($usuario,$empresa)
    {
        $documentos=BodegasDocumentos::GetBodegasUsuario($empresa,$usuario);
        return $documentos;
    }

    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/
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

    /**
    * Metodo para obtener la razon social de la empresa
    * @param string  $empresa codigo de la empresa
    * @return $documentos vector con el nombre de los documentos
    **/
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


    /**
    * Metodo para obtener los documentos que no son de tipo sistema
    * @return $documentos vector con el nombre de los documentos
    **/
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

   
    /**
    * Metodo para obtener el nombre o descripcion de todas las empresas del sistema
    * @return $documentos vector con el nombre del departamento
    **/
    function ListarEmpresas($usuario)
    { 
      $sql  = "SELECT DISTINCT EM.*,UP.priv ";
      $sql .= "FROM   empresas EM,";
      $sql .= "       inv_bodegas_userpermisos_admin UP ";
      $sql .= "WHERE  EM.empresa_id = UP.empresa_id ";
      $sql .= "AND    UP.usuario_id = ".$usuario." ";
      $sql .= "AND    EM.sw_activa = '1' ";
      $sql .= "ORDER BY EM.empresa_id "; 
      //print_r($sql);
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos = array();
      
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
   
   
   
    /**
    * Metodo para obtener una lista de todos los centros de costo
    * @return $documentos vector con el nombre de los centros de costo
    **/
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

    /**
    * Metodo para obtener el nombre o descripcion de un departamento
    * @param string $depto
    * @return $documentos vector con el nombre del departamento
    **/
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
 
    /**
    * Metodo para obtener todos LOS DOCUMENTOS GENERALES DEL SISTEMA
    * @return $documentos vector con los datos del tercero
    **/
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

    /**
    * Metodo para obtener todos los datos de un usuaRIO DEL SISTEMA
    * @param string $usuario_id
    * @return $documentos vector con los datos del tercero
    **/
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

    /**
    * Metodo para obtener todos los datos de un tercero
    * @param string $tipo_id
    * @param string $tercero_id
    * @return $documentos vector con los datos del tercero
    **/
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

    /**
    * Metodo para obtener todos los datos de un tercero
    * @param string $tipo_id
    * @param string $tercero_id
    * @return $documentos vector con los datos del tercero
    **/
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
    
    function GetDatosPaciente($numerodecuenta)
    {
        $sql=" select p.tipo_id_paciente||' '||p.paciente_id||' '||p.primer_nombre||' '||p.segundo_nombre||' '||p.primer_apellido||' '||p.segundo_apellido as datos_paciente
            from cuentas c, ingresos i, pacientes p
            where numerodecuenta = ".$numerodecuenta."
            and c.ingreso=i.ingreso
	    and i.tipo_id_paciente = p.tipo_id_paciente
	    and i.paciente_id = p.paciente_id"; 
                
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
            
        return $resultado->fields[0];
    }
   
    /**
    * Metodo para obtener los centros de utilidad de una empresa previamente seleccionada (En el Login)
    * @param string $empresa_id
    * @return $datos vector con los datos de los centros de utilidad
    **/
    function CentrosUtilidad_Empresa($empresa_id)
    {
        $sql="SELECT
		centro_utilidad,
		descripcion
		FROM 
		centros_utilidad
		WHERE
		empresa_id = '".trim($empresa_id)."'
		ORDER BY descripcion;"; 
                
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
            
        $datos=Array();
        while(!$resultado->EOF)
        {
            $datos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        
        $resultado->Close();
        // return $sql;
        return $datos;
    }

   
    /**
    * Metodo para obtener bodegas de los centros de utilidad de una empresa previamente seleccionada (En el Login)
    * @param string $empresa_id
    * @param string $centro_utilidad
    * @return $datos vector con los datos de los centros de utilidad
    **/
    function Bodegas_EmpresaCentro($empresa_id,$centro_utilidad)
    {
        $sql="SELECT
		bodega,
		descripcion
		FROM 
		bodegas
		WHERE
		empresa_id = '".trim($empresa_id)."'
		AND centro_utilidad = '".trim($centro_utilidad)."'
		AND estado = '1'
		ORDER BY descripcion;"; 
                
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
            
        $datos=Array();
        while(!$resultado->EOF)
        {
            $datos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        
        $resultado->Close();
        // return $sql;
        return $datos;
    }

   
    /**
    * Metodo para obtener bodegas de los centros de utilidad de una empresa previamente seleccionada (En el Login)
    * @param string $empresa_id
    * @param string $centro_utilidad
    * @return $datos vector con los datos de los centros de utilidad
    **/
    function Listado_Moleculas()
    {
        $sql="
		SELECT
		molecula_id,
		descripcion,
		sw_medicamento
		FROM
		inv_moleculas
		WHERE TRUE
		AND estado = '1'
		ORDER BY molecula_id ASC;"; 
                
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
            
        $datos=Array();
        while(!$resultado->EOF)
        {
            $datos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        
        $resultado->Close();
        // return $sql;
        return $datos;
    }

   
    
     /**
    * Metodo para obtener los documentos por tipo de movimiento sea  Ingreso, Egreso o Traslados
    * @return $documentos vector con el nombre de los documentos
    **/
    function ListarDocGenerales($inv_tipo_movimiento)
    { 
     
        $sql="select * from tipos_doc_generales where inv_tipo_movimiento='".$inv_tipo_movimiento."' order by tipo_doc_general_id"; //
       
        
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
    
    
    
    /**
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		**/
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

		/**
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
		* @return rst 
		**/
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
		
			
	//2012 05 11 detalle de terceros
	
	function GetDocDatosAdicionales_mod($empresa_id,$prefijo,$numero,$tipo_doc_bodega_id)
    {
        if(empty($empresa_id) || empty($prefijo) || empty($numero) || empty($tipo_doc_bodega_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "PARAMETROS REQUERIDOS [empresa_id,prefijo,numero,tipo_doc_bodega_id].";
            return false;
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

		
        switch($tipo_doc_bodega_id)
        {
            case 'I001':

                $sql = "SELECT
                            a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| b.nombre_tercero as datos
                           -- a.documento_compra as \"FACTURA DE COMPRA No.\",
                           -- a.fecha_doc_compra as \"FECHA DE COMPRA\"
                        FROM
                            inv_bodegas_movimiento_compras_directas as a,
                            terceros as b
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.tipo_id_tercero = a.tipo_id_tercero
                            AND b.tercero_id = a.tercero_id
                            group by 1
                            ;
                ";
            break;

            case 'I002':

                $sql = "SELECT
                           -- a.orden_pedido_id as \"ORDEN DE PEDIDO No.\" 
                            d.tipo_id_tercero || ' ' || d.tercero_id || ' : '|| d.nombre_tercero as datos

                        FROM
                            inv_bodegas_movimiento_ordenes_compra as a,
                            compras_ordenes_pedidos as b,
                            terceros_proveedores as c,
                            terceros as d

                        WHERE
                            a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.orden_pedido_id = a.orden_pedido_id
                            AND c.codigo_proveedor_id = b.codigo_proveedor_id
                            AND d.tipo_id_tercero = c.tipo_id_tercero
                            AND d.tercero_id = c.tercero_id;
                ";
            break;

			            case 'I003':

                $sql = "SELECT
                            --a.fecha_selectivo as \"<b>FECHA SELECTIVO</b>\",
                            --a.coordinador_auxiliar as \"<b>COORDINADOR O AUXILIAR ESTABLECIMIENTO</b>\",
                            c.descripcion||'-'||d.descripcion as datos
                            --a.control_interno as \"<b>AUDITOR GESTION CONTROL INTERNO</b>\"
                        FROM
                            --inv_bodegas_movimiento_ajustes as a
--                            JOIN inv_bodegas_movimiento as b ON (a.empresa_id = b.empresa_id)
							inv_bodegas_movimiento as b 
							JOIN bodegas as c ON (b.empresa_id = c.empresa_id)
							AND (b.centro_utilidad = c.centro_utilidad)
							AND (b.bodega = c.bodega)
							JOIN centros_utilidad as d ON (c.empresa_id = d.empresa_id)
							AND (c.centro_utilidad = d.centro_utilidad)
                        WHERE
                                b.empresa_id = '$empresa_id'
                            AND b.prefijo = '$prefijo'
                            AND b.numero = $numero;
                ";
            //print_r($sql);
            break;
            
			
            case 'I005':

                $sql = "SELECT '(' || b.tipo_aprovechamiento_id || ') ' || b.descripcion as datos
                        FROM
                            inv_bodegas_movimiento_aprovechamientos as a,
                            inv_bodegas_tipos_aprovechamiento as b
                        WHERE
                            a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND a.tipo_aprovechamiento_id = b.tipo_aprovechamiento_id;
                ";
            break;

            case 'I006':

                $sql = "SELECT
                            a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| b.nombre_tercero as datos
                            --a.prefijo_prestamo || '-' || a.numero_prestamo as \"DOCUMENTO DE PRESTAMO\"
                        FROM
                            inv_bodegas_movimiento_ing_dev_prestamos as a,
                            terceros as b
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.tipo_id_tercero = a.tipo_id_tercero
                            AND b.tercero_id = a.tercero_id ";
            
            break;
            
            case 'I007':

                $sql = "SELECT
                            a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| b.nombre_tercero as datos
                           -- '(' || c.tipo_prestamo_id || ') ' || c.descripcion as \"MOTIVO DEL PRESTAMO\"
                        FROM
                            inv_bodegas_movimiento_prestamos as a,
                            terceros as b
                            --inv_bodegas_tipos_prestamos as c
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.tipo_id_tercero = a.tipo_id_tercero
                            AND b.tercero_id = a.tercero_id;
--                            AND c.tipo_prestamo_id = a.tipo_prestamo_id;
                ";
            break;
			
            case 'I008':

                $sql = "SELECT
                            c.razon_social as datos
                           -- b.prefijo||'-'||b.numero as \"DOCUMENTO DE DESPACHO\",
                            --b.solicitud_prod_a_bod_ppal_id as \"PEDIDO\"
                            --d.observacion as \"OBSERVACION DOCUMENTO\"
                        FROM
                            inv_bodegas_movimiento_ingresosdespachos_farmacias as a
                            JOIN inv_bodegas_movimiento_despachos_farmacias as b ON (a.empresa_despacho = b.empresa_id)
							AND (a.prefijo_despacho = b.prefijo)
							AND (a.numero_despacho = b.numero)
							JOIN inv_bodegas_movimiento as d ON (b.empresa_id = d.empresa_id)
							AND (b.prefijo = d.prefijo)
							AND (b.numero = d.numero)
							JOIN empresas as c ON (b.empresa_id = c.empresa_id)
                            
                            WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero;  "; 
            break;
            
            case 'I011':

                $sql = "SELECT
                            emp.empresa_id || ' - ' || emp.razon_social as \"FARMACIA\"
                            --' ' || a.prefijo_doc_farmacia || '-' || a.numero_doc_farmacia as datos
                        FROM
                            inv_bodegas_movimiento_devolucion_farmacia as a,
                            empresas as emp
                            WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND a.farmacia_id = emp.empresa_id
                            
                ";
            break;
            
             case 'I012':

                $sql = "SELECT
                            b.tipo_id_tercero || ' ' || b.tercero_id || ' : '|| b.nombre_tercero as datos
                            --a.prefijo_doc_cliente || '-' || a.numero_doc_cliente as \"NUMERO DE FACTURA\"
                        FROM
                            inv_bodegas_movimiento_devolucion_cliente as a,
                            terceros as b
                           
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND a.tipo_id_tercero = b.tipo_id_tercero
                            AND a.tercero_id = b.tercero_id
                            
                ";
            //print_r($sql);
            break;
			
			 case 'I013':

                $sql = "SELECT
                            pac.primer_nombre|| ' ' ||pac.segundo_nombre|| ' ' ||pac.primer_apellido|| ' ' ||pac.segundo_apellido || ' : ' as datos
                            --'(' || a.formula_papel || ') ' as \"NUMERO DE FORMULA\"
                        FROM
                            inv_bodegas_movimiento_devoluciones_formula_medica as a,
                            esm_formula_externa as b,
                            pacientes as pac
                           
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND a.formula_id = b.formula_id
                            AND b.tipo_id_paciente = pac.tipo_id_paciente
                            AND b.paciente_id = pac.paciente_id
                            
                ";
            //print_r($sql);
            break;
			 case 'I015':

                $sql = "SELECT
                            e.razon_social||'-'||d.descripcion||'-'||c.descripcion as datos
                            --'(' || a.prefijo_doc_farmacia || '-' || a.numero_doc_farmacia || ') ' as \"DOCUMENTO DE TRASLADO\"
                        FROM
                            --inv_bodegas_movimiento_ingresos_traslados_farmacia as a
                            inv_bodegas_movimiento as b 
							JOIN bodegas as c ON (b.empresa_id = c.empresa_id)
							AND (b.centro_utilidad = c.centro_utilidad)
							AND (b.bodega = c.bodega)
							JOIN centros_utilidad as d ON (c.empresa_id = d.empresa_id)
							AND (c.centro_utilidad = d.centro_utilidad)
							JOIN empresas as e ON (d.empresa_id = e.empresa_id)
                            WHERE
                                b.empresa_id = '$empresa_id'
                            AND b.prefijo = '$prefijo'
                            AND b.numero = $numero
                            
                ";
            /*print_r($sql);*/
            break;

            case 'T001':

                $sql = "SELECT
                        ('[' || b.empresa_id || ']' || '[' || b.centro_utilidad || ']' || '[' || b.bodega || '] : ' || b.descripcion) as datos
                       -- (CASE WHEN a.usuario_id IS NULL THEN 'SIN CONFIRMAR' ELSE c.nombre || ' [' || to_char(a.fecha_confirmacion, 'YYYY-MM-DD HH24:MI:SS') || ']' END) as \"CONFIRMACION\"

                        FROM
                            inv_bodegas_movimiento_traslados as a
                            LEFT JOIN system_usuarios as c
                            ON (a.usuario_id = c.usuario_id),
                            bodegas as b


                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.empresa_id = a.empresa_id
                            AND b.centro_utilidad = a.centro_utilidad_destino
                            AND b.bodega = a.bodega_destino;
                ";
            break;

            case 'T003':

                $sql = "SELECT
                        ('[' || b.empresa_id || ']' || '[' || b.centro_utilidad || ']' || '[' || b.bodega || '] : ' || b.descripcion) as datos
                       -- c.nombre  as \"USUARIO QUE DEVUELVE\"

                        FROM
                            inv_bodegas_movimiento_traslados_esm_devoluciones as a,
                            --LEFT JOIN system_usuarios as c
                            --ON (a.usuario_id = c.usuario_id),
                            bodegas as b


                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.empresa_id = a.empresa_id
                            AND b.centro_utilidad = a.centro_utilidad_destino
                            AND b.bodega = a.bodega_destino;
                ";
            break;
			
			case 'T004':

                $sql = "SELECT
                        ('[' || b.empresa_id || ']' || '[' || b.centro_utilidad || ']' || '[' || b.bodega || '] : ' || b.descripcion) as datos
                        --a.prefijo_documento_devolucion || ' ' ||a.numero_documento_devolucion  as \"DOCUMENTO DE DEVOLUCION\"

                        FROM
                            inv_bodegas_movimiento_traslados_esm_despacho_devolucion as a,
                            bodegas as b


                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.empresa_id = a.empresa_id
                            AND b.centro_utilidad = a.centro_utilidad_destino
                            AND b.bodega = a.bodega_destino;
                ";
            break;

			case 'T002':

                $sql = "SELECT
                        ('[' || b.empresa_id || ']' || '[' || b.centro_utilidad || ']' || '[' || b.bodega || '] : ' || b.descripcion) as datos
                        --(CASE WHEN a.usuario_id IS NULL THEN 'SIN CONFIRMAR' ELSE c.nombre || ' [' || to_char(a.fecha_confirmacion, 'YYYY-MM-DD HH24:MI:SS') || ']' END) as \"CONFIRMACION\",
						--d.orden_requisicion_id as \"ORDEN REQUISICION\",
						--f.tipo_id_tercero || ' ' || f.tercero_id || '-' || f.nombre_tercero as \"ESM\",
             --g.descripcion as \"FUERZA\",
            --CASE WHEN d.sw_bodegamindefensa = '1' 
            --THEN 'PRODUCTOS DE MINDEFENSA'
            --ELSE
            --'PRODUCTOS DE OPERADOR LOGISTICO' END as \"BODEGA\",
            --d.sw_bodegamindefensa,
            --d.sw_entregado_off
           

                        FROM
                            inv_bodegas_movimiento_traslados as a
                            LEFT JOIN system_usuarios as c
                            ON (a.usuario_id = c.usuario_id),
                            bodegas as b,
                            inv_bodegas_movimiento_traslados_esm d,
                            esm_orden_requisicion e,
                            terceros f,
                            esm_tipos_fuerzas g
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.empresa_id = a.empresa_id
                            AND b.centro_utilidad = a.centro_utilidad_destino
                            AND b.bodega = a.bodega_destino
                            AND a.empresa_id = d.empresa_id
                            AND a.prefijo = d.prefijo
                            AND a.numero = d.numero 
                            AND d.orden_requisicion_id = e.orden_requisicion_id 
                            AND e.tercero_id = f.tercero_id 
                            AND e.tipo_id_tercero = f.tipo_id_tercero
                            AND e.tipo_fuerza_id = g.tipo_fuerza_id
							";
							//print_r($sql);
            break;

            case 'E001':

                $sql = "SELECT '(' || b.tipo_perdida_id || ') ' || b.descripcion as datos
                        FROM
                            inv_bodegas_movimiento_perdidas as a,
                            inv_bodegas_tipos_perdidas as b
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND a.tipo_perdida_id = b.tipo_perdida_id;
                ";

            break;

            case 'E002':

                $sql = "SELECT
                            a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| b.nombre_tercero as datos
                          --  '(' || c.tipo_prestamo_id || ') ' || c.descripcion as \"MOTIVO DEL PRESTAMO\"
                        FROM
                            inv_bodegas_movimiento_prestamos as a,
                            terceros as b
                            --inv_bodegas_tipos_prestamos as c
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.tipo_id_tercero = a.tipo_id_tercero
                            AND b.tercero_id = a.tercero_id
                            --AND c.tipo_prestamo_id = a.tipo_prestamo_id;
                ";
				
            case 'E003':

                $sql = "SELECT
                           -- a.fecha_selectivo as \"<b>FECHA SELECTIVO</b>\",
                            --a.coordinador_auxiliar as \"<b>COORDINADOR O AUXILIAR ESTABLECIMIENTO</b>\",
                            c.descripcion||'-'||d.descripcion as datos
                            --a.control_interno as \"<b>AUDITOR GESTION CONTROL INTERNO</b>\"
                        FROM
                            --inv_bodegas_movimiento_ajustes as a
                            inv_bodegas_movimiento as b 
							JOIN bodegas as c ON (b.empresa_id = c.empresa_id)
							AND (b.centro_utilidad = c.centro_utilidad)
							AND (b.bodega = c.bodega)
							JOIN centros_utilidad as d ON (c.empresa_id = d.empresa_id)
							AND (c.centro_utilidad = d.centro_utilidad)
                        WHERE
                                b.empresa_id = '$empresa_id'
                            AND b.prefijo = '$prefijo'
                            AND b.numero = $numero;
                ";
            //print_r($sql);
            break;
            
            case 'E004':

                $sql = "SELECT
                            a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| b.nombre_tercero as datos
                            --a.prefijo_prestamo || '-' || a.numero_prestamo as \"DOCUMENTO DE PRESTAMO\"
                        FROM
                            inv_bodegas_movimiento_eg_dev_prestamos as a,
                            terceros as b
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.tipo_id_tercero = a.tipo_id_tercero
                            AND b.tercero_id = a.tercero_id ";
            //print_r($sql);
            break;

            case 'E006':

                $sql = "SELECT 
				--b.departamento || ' : ' || b.descripcion as \"DEPARTAMENTO\"
				b.departamento || ' : ' || b.descripcion as datos
                        FROM inv_bodegas_movimiento_consumo as a,
                             departamentos as b
                        WHERE
                            a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND b.departamento = a.departamento;
                ";

            break;


            case 'E007':

                $sql = "SELECT
                           -- b.departamento || ' : ' || b.descripcion as \"DEPARTAMENTO\",
                            --a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| d.nombre_tercero as \"TERCERO\"
                            a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| d.nombre_tercero as datos
                           -- '(' || d.tipo_id_tercero || ') ' || c.descripcion as \"CONCEPTO DEL EGRESO\"


                        FROM inv_bodegas_movimiento_conceptos_egresos as a,
                             --departamentos as b,
                             inv_bodegas_conceptos_egresos as c,
                             terceros as d

                        WHERE
                            a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            --AND b.departamento = a.departamento
                            AND c.concepto_egreso_id = a.concepto_egreso_id
                            AND d.tipo_id_tercero = a.tipo_id_tercero
                            AND d.tercero_id = a.tercero_id;
                ";

            break;
            
            
            case 'E008':

                              
                $sql = "
                select * From
                (
                  (   
                    SELECT  
					--'CLIENTES'  as \"TIPO DE DESPACHO :\",
							--a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| b.nombre_tercero as \"FARMACIA/CLIENTE :\"
							a.tipo_id_tercero || ' ' || a.tercero_id || ' : '|| b.nombre_tercero as datos
                            --a.pedido_cliente_id AS \"NUMERO PEDIDO: \"
--                            b.direccion AS \"DIRECCION: \",
                            --b.telefono AS \"TELEFONO: \"
                    FROM    inv_bodegas_movimiento_despachos_clientes as a,
                            terceros as b
                    WHERE   a.empresa_id = '$empresa_id'
                    AND a.prefijo = '$prefijo'
                    AND a.numero = $numero
                    AND b.tipo_id_tercero = a.tipo_id_tercero
                    AND b.tercero_id = a.tercero_id
                  ) ";
                
                $sql .= "
                
                UNION ALL
                  (    
                    SELECT  
					--'FARMACIAS'  as \"TIPO DE DESPACHO :\",
							--e.empresa_id || ' - '|| e.razon_social ||' ::: '||c.descripcion as \"FARMACIA/CLIENTE :\"
							e.empresa_id || ' - '|| e.razon_social ||' ::: '||c.descripcion as datos
                            --a.solicitud_prod_a_bod_ppal_id AS \"NUMERO PEDIDO: \"
                            --e.direccion AS \"DIRECCION: \",
                           --e.telefonos AS \"TELEFONO: \"
                    FROM    inv_bodegas_movimiento_despachos_farmacias as a
							JOIN solicitud_productos_a_bodega_principal as b ON (a.solicitud_prod_a_bod_ppal_id = b.solicitud_prod_a_bod_ppal_id)
							JOIN bodegas as c ON (b.farmacia_id = c.empresa_id)
							AND (b.centro_utilidad = c.centro_utilidad)
							AND (b.bodega = c.bodega)
							--JOIN centros_utilidad as d ON (c.centro_utilidad = d.centro_utilidad)
--							AND (c.empresa_id = d.empresa_id)
                            JOIN empresas as e ON (c.empresa_id = e.empresa_id)
                    WHERE   a.empresa_id = '$empresa_id'
                    AND a.prefijo = '$prefijo'
                    AND a.numero = $numero
                  )        
                )as x
                ";
            /*print_r($sql);*/
            break;
            case 'E012':
			$sql = "SELECT 
			b.tipo_id_tercero || ' ' || b.tercero_id || ' : '|| b.nombre_tercero as datos
			--a.codigo_proveedor_id as \"CODIGO DEL PROVEEDOR\",
			--a.numero_factura as \"NUMERO DE FACTURA\"
			FROM inv_bodegas_movimiento_devolucion_proveedor as a
			JOIN terceros_proveedores as c ON (a.codigo_proveedor_id = c.codigo_proveedor_id) 
			JOIN terceros as b ON (c.tipo_id_tercero = b.tipo_id_tercero)
			AND (c.tercero_id = b.tercero_id) 
			WHERE 
			a.empresa_id = '$empresa_id'
			AND a.prefijo = '$prefijo'
			AND a.numero = $numero; ";
            break;
            
            case 'E009':
            $sql = "SELECT 
                        --c.tipo_doc_bodega_id
						--c.prefijo,
                        --b.bodegas_doc_id,
						--b.numeracion,
                        b.observacion datos
                        
                                FROM 
                               
                                bodegas_documentos as b, 
                                bodegas_doc_numeraciones as c 
                                
                                            WHERE 
                                            c.empresa_id = '$empresa_id' 
                                            AND b.bodegas_doc_id = c.bodegas_doc_id                                             
                                            AND c.prefijo = '$prefijo'
                                            AND a.numeracion = $numero;
                                            ";
                                            
            break;
            
            
            case 'E016':

                $sql = "SELECT
                            --b.tipo_id_tercero || ' ' || b.tercero_id || ' : '|| b.nombre_tercero as \"ESM\"
                            b.tipo_id_tercero || ' ' || b.tercero_id || ' : '|| b.nombre_tercero as datos
                          --  '(' || a.orden_requisicion_id || ') ' as \"NUMERO DE REQUISICION\",
                            --'' || c.descripcion || ' ' as \"TIPO DE FUERZA\",
                            --'' || a.direccion || ' ' as \"DIRECCION\",
                            --'' || a.empresa_transportadora || ' ' as \"EMPRESA TRANSPORTADORA\",
                            --'' || a.numero_guia || ' ' as \"NUMERO GUIA\",
                            --CASE WHEN sw_bodegamindefensa = '1' 
                            --THEN 'PRODUCTOS DE MINDEFENSA'
                            --ELSE
                            --'PRODUCTOS DE OPERADOR LOGISTICO' END as \"BODEGA\",
                            --a.sw_bodegamindefensa,
                            --a.sw_entregado_off
                            

                        FROM
                            inv_bodegas_movimiento_despacho_campania as a,
                            terceros as b,
                            esm_tipos_fuerzas as c
                            
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero
                            AND a.tercero_id = b.tercero_id
                            AND a.tipo_id_tercero = b.tipo_id_tercero
                            AND a.tipo_fuerza_id = c.tipo_fuerza_id
                            
                            
                ";
            //print_r($sql);
            break;
            
            case 'E017':

                $sql = "SELECT
                            --d.razon_social||'-'||c.descripcion||'-'||b.descripcion as \"FARMACIA DESTINO\"
                            d.razon_social||'-'||c.descripcion||'-'||b.descripcion as datos
                           -- CASE WHEN a.sw_estado = '1' 
                            --THEN 'PENDIENTE POR RECIBIR TOTALMENTE'
                            --ELSE
                            --'RECIBIDO TOTALMENTE' END as \"ESTADO DOCUMENTO\"
                        FROM
                            inv_bodegas_movimiento_traslados_farmacia as a
                            LEFT JOIN bodegas as b ON (a.farmacia_id = b.empresa_id)
							AND (a.centro_utilidad = b.centro_utilidad)
							AND (a.bodega = b.bodega)
							LEFT JOIN centros_utilidad as c ON (b.empresa_id = c.empresa_id)
							AND (b.centro_utilidad = c.centro_utilidad)
							LEFT JOIN empresas as d ON (c.empresa_id = d.empresa_id)
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero;
                ";
           /*print_r($sql);*/
            break;

            case 'E018':

                $sql = "SELECT
                            -- b.plan_descripcion as \"PLAN\",
                             b.plan_descripcion as datos,
							-- c.descripcion_tipo_formula as \"TIPO DISPENSACION\",
							 --a.requisicion AS \"COD. SOLICITUD\"
                            
                        FROM
                            inv_bodegas_movimiento_distribucion as a
                            JOIN planes as b ON(a.plan_id = b.plan_id)
							JOIN esm_tipos_formulas as c ON (a.tipo_formula_id = c.tipo_formula_id)
                        WHERE
                                a.empresa_id = '$empresa_id'
                            AND a.prefijo = '$prefijo'
                            AND a.numero = $numero;
                ";
           /*print_r($sql);*/
            break;

            default:

                return null;
        }
	


/*	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        //$dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

      if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        //$retorno = array();

        while($lista = $result->FetchRow())
        {
            $fila['DATOS_ADICIONALES'][] = $lista;
        }
        $result->Close();
		*/

        //return $fila;
        //return $fila;

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($result->EOF)
        {
            return null;
        }

        $retorno = $result->FetchRow();
        $result->Close();

        return  $retorno;
		
		
		
		
		}
		
		
		function  nom_producto($codigo_producto)
    { 
        $sql=" select fc_descripcion_producto('".$codigo_producto."') as producto_"; 
             
     
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
        $documentos=Array();
        while(!$resultado->EOF)
        {
            $documentos= $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
      
        $resultado->Close();
        return $documentos;
     }
	 
	 function ObtenerProductosPendientesCompras_k($empresa_id,$centro_id,$bodega,$codigo_producto)
    {
      $ctl = new ClaseUtil();
        list( $anio, $mes) = split( '[/.-]', $LapsoFinal ); 
        //echo ($ctl->ObtenerDiasDelMes($mes,$anio));
        $NumDias= $ctl->ObtenerDiasDelMes($mes,$anio);
       

        $sql = "

            SELECT 
            ((copd.numero_unidades)-COALESCE(numero_unidades_recibidas,0))as cantidad,
            cop.orden_pedido_id,
            cop.fecha_registro,
            t.nombre_tercero,
            t.tipo_id_tercero,
            t.tercero_id,
            u.usuario
            FROM
            compras_ordenes_pedidos cop,
            compras_ordenes_pedidos_detalle copd,
            terceros_proveedores tp,
            terceros t,
            system_usuarios u
            where
                  copd.codigo_producto = '".$codigo_producto."'
            AND   copd.numero_unidades<> COALESCE(numero_unidades_recibidas,0)
            AND   copd.orden_pedido_id = cop.orden_pedido_id
            AND   cop.empresa_id = '".$empresa_id."'
            AND   cop.estado = '1'
            AND   cop.codigo_proveedor_id = tp.codigo_proveedor_id
            AND   tp.tipo_id_tercero = t.tipo_id_tercero
            AND   tp.tercero_id = t.tercero_id
            AND   cop.usuario_id = u.usuario_id
                         
            ORDER BY cop.orden_pedido_id;
        ";
       // print_r($sql);
       GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = array();

        while (!$result->EOF)
        {
       $retorno[] = $result->GetRowAssoc($ToUpper = false);
       $result->MoveNext();
        }
        $result->Close();
        
        return $retorno;
    }
		
		
		
	}
?>