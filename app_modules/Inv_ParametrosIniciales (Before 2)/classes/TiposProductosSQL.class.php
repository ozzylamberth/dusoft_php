<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasNovedadesDevolucion.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
  /**
  * Clase: TiposProductosSQl
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */ 
  class TiposProductosSQl extends ConexionBD
  {
    /**
    * Contructor
    */
    function TiposProductosSQl(){}
    /**
    * Funcion donde se obtienen los tipos de productos y sus parametrizaciones
    *
    * @param string $empresa_id Identificador de la empresa
    *
    * @return mixed
    */
    function ObtenerTiposProductos($empresa_id)
    {
      $sql  = "SELECT TP.tipo_producto_id,";
      $sql .= " 	    TP.descripcion,";
      $sql .= "       ED.sw_lunes,";
      $sql .= "       ED.sw_martes,";
      $sql .= "       ED.sw_miercoles,";
      $sql .= "       ED.sw_jueves,";
      $sql .= "       ED.sw_viernes,";
      $sql .= "       ED.sw_sabado,";
      $sql .= "       ED.sw_domingo ";
      $sql .= "FROM   inv_tipo_producto TP ";
      $sql .= "       LEFT JOIN inv_dias_envio_tipos_productos ED ";
      $sql .= "       ON (ED.tipo_producto_id = TP.tipo_producto_id AND ";
      $sql .= "           ED.empresa_id = '".$empresa_id."' ) ";
      $sql .= "ORDER BY  TP.descripcion ";
 
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
    /**
    * Funcion donde se ingresan las parametrizaciones de los tipos de prodcutos
    * 
    * @param array $form Arreglo de datos con la informacion a ingresar
    *
    * @return boolean
    */
    function IngresarDiasEnvio($form)
    {
      $this->ConexionTransaccion();
      $sql  = "DELETE FROM inv_dias_envio_tipos_productos ";
      $sql .= "WHERE  empresa_id = '".$form['empresa_id']."' ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
      
      foreach($form['producto'] as $key => $dtl)
      {
        $sql  = "INSERT INTO inv_dias_envio_tipos_productos ";
        $sql .= "   (";
        $sql .= "     empresa_id ,";
        $sql .= "     tipo_producto_id ,";
        $sql .= "     sw_lunes ,";
        $sql .= "     sw_martes ,";
        $sql .= "     sw_miercoles ,";
        $sql .= "     sw_jueves ,";
        $sql .= "     sw_viernes ,";
        $sql .= "     sw_sabado ,";
        $sql .= "     sw_domingo ,";
        $sql .= "     usuario_id  ,";
        $sql .= "     fecha_registro";
        $sql .= "   )";
        $sql .= "VALUES ";
        $sql .= "   (";
        $sql .= "     '".$form['empresa_id']."',";
        $sql .= "     '".$key."',";
        $sql .= "     '".(($dtl['sw_lunes'] == '1')? "1":"0")."',";
        $sql .= "     '".(($dtl['sw_martes'] == '1')? "1":"0")."',";
        $sql .= "     '".(($dtl['sw_miercoles'] == '1')? "1":"0")."',";
        $sql .= "     '".(($dtl['sw_jueves'] == '1')? "1":"0")."',";
        $sql .= "     '".(($dtl['sw_viernes'] == '1')? "1":"0")."',";
        $sql .= "     '".(($dtl['sw_sabado'] == '1')? "1":"0")."',";
        $sql .= "     '".(($dtl['sw_domingo'] == '1')? "1":"0")."',";
        $sql .= "     ".$form['usuario_id'].",";
        $sql .= "     NOW() ";
        $sql .= "   )";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
      }
      
      $this->Commit();
      return true;
    }
  }
?>