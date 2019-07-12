<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ParametrizacionDocumentosBodeSQL.class.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  /**
  * Clase : ParametrizacionDocumentosBodeSQL
  * 
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  
  class ParametrizacionDocumentosBodeSQL extends ConexionBD
  {
    /**
          * Constructor de la clase
          */
    function ParametrizacionDocumentosBodeSQL(){}
    
    /**
            * Funcion donde se verifica el permiso del usuario para el ingreso al modulo
            *
            * @return array $datos vector que contiene la informacion de la consulta del codigo de
            * la empresa y la razon social
            */
    function ObtenerPermisos()
    {
      //$this->debug = true;
      $sql  = "SELECT   EM.empresa_id AS empresa, ";
      $sql .= "         EM.razon_social AS razon_social ";
      $sql .= "FROM     userpermisos_parametrizadocumentosbode CP, empresas EM ";
      $sql .= "WHERE    CP.usuario_id = ".UserGetUID()." ";
      $sql .= "         AND CP.empresa_id = EM.empresa_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      } 
      $rst->Close();
      return $datos;
    }
    
   /**
         * Funcion donde se consultan los permisos de un usuario
         *
         * @return array $datos vector que contiene la informacion de la consulta de los permisos de usuario
         * @param array $filtros vector con los datos del request donde se encuentran los
         *  parametos de busqueda
         *  @param string $pg_siguiente
         *  @param var $empresa donde se encuentra la empersa id
         * @return array $datos vector que contiene la informacion de los usuarios
         */
   function ConsultarpermisosUsuarios($filtros,$pg_siguiente,$empresa)
   {
     //$this->debug = true;
     $sql  = "SELECT  DISTINCT b.usuario,a.usuario_id as id ";
     $whr  = "FROM    inv_bodegas_userpermisos as a, ";
     $whr .= "        system_usuarios b ";
     $whr .= "WHERE   a.usuario_id=b.usuario_id ";
     $whr .= "AND     a.empresa_id='".$empresa."' ";
     
     if($filtros['id'])
        {
        $whr.=" and a.usuario_id= ".$filtros['id']." ";
        }
        if($filtros['usuario'] != "")
        $whr .= "AND     b.usuario ILIKE '%".$filtros['usuario']."%' ";
        
     $l = " DISTINCT b.usuario";
     if(!$this->ProcesarSqlConteo("SELECT COUNT($l) $whr",$pg_siguiente,null,50))
      return false;
     
     $whr1  = "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
     
     if(!$rst = $this->ConexionBaseDatos($sql.$whr.$whr1))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
   }
 
   /**
        * Funcion donde se consultan los parametros de la bodega
        *
        *  @param var $usuario_id donde se encuentra la usuario id
        *  @param var $empresa donde se encuentra la empersa id
        * @return array $datos vector que contiene la informacion de la consulta de los parametros de la bodega
        */
   function Consultarparametrosbod($usuario_id,$empresa)
   {
     //$this->debug = true;
     $sql  = "SELECT  DISTINCT  a.usuario_bodega, ";
     $sql .= "        a.sw_codigoproducto, ";
     $sql .= "        a.sw_nombreproducto, ";
     $sql .= "        a.sw_codigobarras, ";
     $sql .= "        a.sw_nombremolecula, ";
     $sql .= "        a.sw_codigomolecula, ";
     $sql .= "        a.sw_nombrelaboratorio, ";
     $sql .= "        a.sw_codigolaboratorio, ";
     $sql .= "        a.parametro_id ";
     $sql .= "FROM    parametros_busqueda_bodegas as a, ";
     $sql .= "        inv_bodegas_userpermisos as  b ";
     $sql .= "WHERE   a.usuario_bodega=b.usuario_id ";
     $sql .= "AND     a.usuario_bodega=".$usuario_id." ";
     $sql .= "AND     a.empresa_id='".$empresa."'";
     if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
   }
   
   /**
        * Funcion donde se almacena la informacion de los parametros de la bodega 
       *
       * @param array $request vector con la informacion del request
       * @param var  $empresa variable con la informacion de la empresa
       * @return booleano
       */
   function IngresarParameBusquedaBode($request,$empresa)
   {
      //$this->debug=true;
      $this->ConexionTransaccion();
     
      $cant = count($request['Prioridad']);
      
      if(empty($request['codigo_producto']))
        $cod_producto=0;
      else
        $cod_producto=$request['codigo_producto'];
      if(empty($request['nombre_producto']))
        $nom_producto=0;
      else
        $nom_producto=$request['nombre_producto'];
      if(empty($request['codigo_barras']))
        $cod_barras=0;
      else
        $cod_barras=$request['codigo_barras'];
      if(empty($request['nombre_molecula']))
        $nom_molecula=0;
      else
        $nom_molecula=$request['nombre_molecula'];
      if(empty($request['codigo_molecula']))
        $cod_molecula=0;
      else
        $cod_molecula=$request['codigo_molecula'];
      if(empty($request['nombre_laboratorio']))
        $nom_laboratorio=0;
      else
        $nom_laboratorio=$request['nombre_laboratorio'];
      if(empty($request['codigo_laboratorio']))
        $cod_laboratorio=0;
      else
        $cod_laboratorio=$request['codigo_laboratorio'];
      
       if(empty($request['usuario_id']) OR empty($request['parametro_id']))
       {
          $sql  = "INSERT INTO parametros_busqueda_bodegas( ";
          $sql .= "            parametro_id, ";
          $sql .= "            usuario_bodega, ";
          $sql .= "            sw_codigoproducto, ";
          $sql .= "            sw_nombreproducto, ";
          $sql .= "            sw_codigobarras, ";
          $sql .= "            sw_nombremolecula, ";
          $sql .= "            sw_codigomolecula, ";
          $sql .= "            sw_nombrelaboratorio, ";
          $sql .= "            sw_codigolaboratorio, ";
          $sql .= "            usuario_id, ";
          $sql .= "            fecha_registro, ";
          $sql .= "            empresa_id ";
          $sql .= ")VALUES    (";
          $sql .= "           default, ";
          $sql .= "           ".$request['usuario_id'].", ";
          $sql .= "           '".$cod_producto."', ";
          $sql .= "           '".$nom_producto."', ";
          $sql .= "           '".$cod_barras."', ";
          $sql .= "           '".$nom_molecula."', ";
          $sql .= "           '".$cod_molecula."', ";
          $sql .= "           '".$nom_laboratorio."', ";
          $sql .= "           '".$cod_laboratorio."', ";
          $sql .= "           ".UserGetUID().", ";
          $sql .= "           NOW(), ";
          $sql .= "           '".$empresa."' )";
        }
        else
        {
          $sql  = "UPDATE parametros_busqueda_bodegas ";
          $sql .= "SET    sw_codigoproducto = '".$cod_producto."' , ";
          $sql .= "       sw_nombreproducto = '".$nom_producto."' , ";
          $sql .= "       sw_codigobarras = '".$cod_barras."' , ";
          $sql .= "       sw_nombremolecula = '".$nom_molecula."' , ";
          $sql .= "       sw_codigomolecula = '".$cod_molecula."' , ";
          $sql .= "       sw_nombrelaboratorio = '".$nom_laboratorio."' , ";
          $sql .= "       sw_codigolaboratorio = '".$cod_laboratorio."' ";
          $sql .= "WHERE  parametro_id = ".$request['parametro_id']." ";
          $sql .= "AND    usuario_bodega = ".$request['usuario_id']." ";
          $sql .= "AND    empresa_id = '".$empresa."' ";
        }
        if(!$rst = $this->ConexionTransaccion($sql))
        {
           echo $this->mensajeDeError;
           return false;
        }
     $this->Commit();
     return true;
   }
  }
?>    