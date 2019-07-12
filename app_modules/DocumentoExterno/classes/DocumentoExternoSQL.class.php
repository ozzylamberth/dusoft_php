<?php
  class DocumentoExternoSQL extends ConexionBD
  {
    /*********************************
    * Constructor
    *********************************/
    function DocumentoExternoSQL(){}


    /**
    * Funcion donde se otienen las farmacis que han hecho pedidos
    *
    * @param string $empresa_id Identificador de la empresa a la que se hacen pedidos
    *
    * @return mixed
    */
    function ObtenerFarmacias($empresa_id)
    {
      $sql = "SELECT EM.empresa_id, 
                BD.centro_utilidad, 
                BD.bodega, 
                EM.razon_social||' ::: '||BD.descripcion AS razon_social ";
      $sql .= "FROM bodegas BD ";
      $sql .= "     JOIN centros_utilidad AS CU ON (BD.empresa_id = CU.empresa_id)";
      $sql .= "     AND (BD.centro_utilidad = CU.centro_utilidad)";
      $sql .= "     JOIN empresas AS EM ON (CU.empresa_id = EM.empresa_id) ";
      $sql .= "ORDER BY razon_social ";

      //echo $sql;
        
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }    
      $rst->Close();

      return $datos;
    }


    function GuardarDocumentoExterno($datos)
    {
      $sql = " INSERT INTO documentos_externos(
      empresa_id,
      centro_utilidad,
      bodega,
      prefijo,
      documento,
      cantidad_cajas,
      cantidad_neveras,
      temperatura_neveras,
      observacion,
      usuario_id)
      VALUES     (
        '".trim($datos['empresa_id'])."',
        '".trim($datos['centro_utilidad'])."',
        '".trim($datos['bodega'])."',
        '".trim($datos['prefijo'])."',
        '".trim($datos['documento'])."',
        '".trim($datos['cantidad_cajas'])."',
        '".trim($datos['cantidad_neveras'])."',
        '".trim($datos['temperatura_neveras'])."',
        '".trim($datos['observacion'])."',
        ".UserGetUID()."
      )";
      //echo $sql;
      if(!$resultado = $this->ConexionBaseDatos($sql))
      {
      $cad="Operacion Invalida";
      return false;//$cad;
      } 
      $resultado->Close();
      return true;
    }

  }
?>