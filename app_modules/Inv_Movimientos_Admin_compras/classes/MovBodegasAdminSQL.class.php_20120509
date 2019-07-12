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
            $resultado->fields[0] = ereg_replace("�", "E", $resultado->fields[0]); 
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
            $resultado->fields[1] = ereg_replace("�", "E", $resultado->fields[1]);
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
        $resultado->fields[3] = ereg_replace("�", "E", $resultado->fields[3]); 
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
            $resultado->fields[1] = ereg_replace("�", "E", $resultado->fields[1]);
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
	}
?>