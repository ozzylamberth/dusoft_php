<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: CierreBodegas.class.php,v 1.2 2011/05/19 22:19:10 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : CierreBodegas
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class CierreBodegas extends ConexionBD
  {
    /**
    * Contructor de la clase
    */
    function CierreBodegas(){}
    /**
    * Funcion para hacer el cierre de costos por lapso
    * 
    * @param string $empresa Identificador de la empresa
    * @param integer $documento Numero de documento
    * @param integer $usuario Identificador del usuario que realiza el cierre
    *
    * @return array
    */
    function CostosXLapsos($empresa,$documento,$usuario)
    {
      $bodegas = $this->ObtenerBodegas($empresa,$usuario);

      foreach($bodegas[$empresa] as $key => $centro)
      {
        foreach($centro as $k1 => $bodega)
        {
          $mensaje = "CIERRE DE COSTOS DEL LAPSO ".$bodega['lapso_cerrar']." SE REALIZO SATISFACTORIAMENTE";
          $retorno = true;
          
          $sql = "SELECT generar_inv_bodegas_movimiento_costo_por_lapso('".trim($empresa)."','".trim($bodega['lapso_cerrar'])."',".trim($documento).",".trim($usuario).",'".trim($bodega['centro_utilidad'])."','".trim($k1)."')";
            
          if(!$rst = $this->ConexionBaseDatos($sql))
          {
            $mensaje = $this->ErrMsg();
            $retorno = false;
          }  
          $bodegas[$empresa][$key][$k1]['mensaje'] = $mensaje;
          $bodegas[$empresa][$key][$k1]['retorno'] = $retorno;
        }
      }
      return $bodegas;
    }    
    /**
    * Funcion para hacer el cierre de bodegas
    *
    * @param string $empresa Identificador de la empresa
    * @param integer $usuario Identificador del usuario que realiza el cierre
    *
    * @return array
    */
    function CierreBodegasLapsos($empresa,$usuario)
    {
      $bodegas = $this->ObtenerBodegas($empresa,$usuario);
      foreach($bodegas[$empresa] as $key => $centro)
      {
        foreach($centro as $k1 => $bodega)
        {
          $mensaje = "CIERRE DE LA BODEGA EN EL LAPSO ".trim($bodega['lapso_cerrar'])." SE REALIZO SATISFACTORIAMENTE";
          $retorno = true;
          
          $sql = "SELECT bodega_cierre('".trim($empresa)."','".trim($bodega['lapso_cerrar'])."','".trim($bodega['centro_utilidad'])."','".trim($k1)."')";
          if(!$rst = $this->ConexionBaseDatos($sql))
          {
            $mensaje = $this->ErrMsg();
            $retorno = false;
          }  
          $bodegas[$empresa][$key][$k1]['mensaje'] = $mensaje;
          $bodegas[$empresa][$key][$k1]['retorno'] = $retorno;
        }
      }  
      return $bodegas;
    }    
    /**
    * Funcion para hacer la actualizacion de las existencias
    *
    * @param string $empresa Identificador de la empresa
    * @param integer $usuario Identificador del usuario que realiza el cierre
    *
    * @return array
    */
    function CierreExistencias($empresa,$usuario)
    {
      $bodegas = $this->ObtenerBodegas(trim($empresa),trim($usuario));
      foreach($bodegas[$empresa] as $key => $centro)
      {
        foreach($centro as $k1 => $bodega)
        {
          $mensaje = "CIERRE DE EXISTENCIAS EN EL LAPSO ".trim($bodega['lapso_cerrar'])." SE REALIZO SATISFACTORIAMENTE";
          $retorno = true;

          $sql = "SELECT bodega_cierre_existencias('".trim($empresa)."','".trim($bodega['lapso_cerrar'])."','".$k1."')";
          if(!$rst = $this->ConexionBaseDatos($sql))
          {
            $mensaje = $this->ErrMsg();
            $retorno = false;
          }  
          $bodegas[$empresa][$key][$k1]['mensaje'] = $mensaje;
          $bodegas[$empresa][$key][$k1]['retorno'] = $retorno;
        }
      }  
      return $bodegas;
    }    
    /**
    * Funcion para hacer la actualizacion de las existencias
    *
    * @param string $empresa Identificador de la empresa
    * @param integer $usuario Identificador del usuario que realiza el cierre
    *
    * @return array
    */
    function CierreExistenciasMovimientos($empresa,$usuario)
    {
      $bodegas = $this->ObtenerBodegas(trim($empresa),trim($usuario));
      foreach($bodegas[$empresa] as $key => $centro)
      {
        foreach($centro as $k1 => $bodega)
        {
          $mensaje = "EL MOVIMEINTO DE LAS EXISTENCIAS EN EL LAPSO ".trim($bodega['lapso_cerrar'])." SE REALIZO SATISFACTORIAMENTE";
          $retorno = true;

          $sql = "SELECT bodega_cierre_movimiento('".trim($empresa)."','".trim($bodega['lapso_cerrar'])."','".$k1."')";
          if(!$rst = $this->ConexionBaseDatos($sql))
          {
            $mensaje = $this->ErrMsg();
            $retorno = false;
          }  
          $bodegas[$empresa][$key][$k1]['mensaje'] = $mensaje;
          $bodegas[$empresa][$key][$k1]['retorno'] = $retorno;
        }
      }  
      return $bodegas;
    }
    /**
    * Metodo donde se actualiza el lapso del cierre de la bodegas
    *
    * @param string $empresa Identificador de la empresa
    * @param integer $usuario Identificador del usuario que realiza el cierre
    *
    * @return boolean
    */
    function IncrementarLapso($empresa,$usuario)
    {
      $bodegas = $this->ObtenerBodegas($empresa,$usuario);
      $this->ConexionTransaccion();
      foreach($bodegas[$empresa] as $key => $centro)
      {
        foreach($centro as $k1 => $bodega)
        {
          if($bodega['lapso_cerrar'] < date("Ym"))
          {
            $anyo = substr($bodega['lapso_cerrar'],0,4);
            $mes = substr($bodega['lapso_cerrar'],4,5);
            $nuevo = date('Ym',mktime(0, 0, 0, $mes + 1, 1, $anyo));
        
            $sql  = "UPDATE bodegas ";
            $sql .= "SET    lapso_cerrar = '".trim($nuevo)."', ";
            $sql .= "       lapso_cerrado = '".trim($bodega['lapso_cerrar'])."' ";
            $sql .= "WHERE  empresa_id = '".trim($empresa)."' ";
            $sql .= "AND    centro_utilidad = '".trim($bodega['centro_utilidad'])."' ";
            $sql .= "AND    bodega = '".trim($bodega['bodega'])."' ";
            
            if(!$rst = $this->ConexionTransaccion($sql))
              return false;
          }
        }
      }
      
      $this->Commit();
      
      return true;
    }
    /**
    * Funcion donde se obtienen las bodegas que usuario tiene permiso de cerrar
    *
    * @param string $empresa_id Identificador de la empresa
    * @param integer $usuario Identificador del usuario
    *
    * @return mixed
    **/
    function ObtenerBodegas($empresa_id,$usuario,$estado = '0')
    { 
      $sql  = "SELECT BG.descripcion AS bodega_descripcion, ";
      $sql .= "       BG.empresa_id, ";
      $sql .= "       BG.centro_utilidad, ";
      $sql .= "       BG.bodega, ";
      $sql .= "       BG.lapso_cerrar, ";
      $sql .= "       BG.lapso_cerrado, ";
      $sql .= "       PR.sw_cierre ";
      $sql .= "FROM   inv_bodegas_userpermisos_admin PR, ";
      $sql .= "       bodegas BG ";
      $sql .= "WHERE  PR.usuario_id = ".$usuario." ";
      $sql .= "AND    PR.empresa_id = '".$empresa_id."'  ";
      $sql .= "AND    PR.empresa_id = BG.empresa_id  ";
      $sql .= "AND    PR.centro_utilidad = BG.centro_utilidad ";
      $sql .= "AND    PR.bodega = BG.bodega ";
      $sql .= "AND    PR.sw_cierre = '1' "; 
	  if($estado == '1')
		$sql .= "AND    BG.lapso_cerrado < ".date("Ym")." "; 
      else
		$sql .= "AND    BG.lapso_cerrar < ".date("Ym")." "; 
                   
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[1]][$rst->fields[2]][$rst->fields[3]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Metdo donde se obtiene la informacion del cierre de un lapso, para un documento
    *
    * @param array $empresa Arreglo de datos con la informacion de la empresa
    * @param string $lapso Lapso a evaluar
    * @param integer $usuario Identificador del usuario que realiza el cierre
    *
    * @return boolean
    */
    function ObtenerInformacionCierreDocumentos($empresa,$usuario)
    {
      $bodegas = $this->ObtenerBodegas($empresa,$usuario,"1");
      /*print_r($bodegas);*/
	  $datos = array();
		
      foreach($bodegas[$empresa] as $key => $centros)
      {
        foreach($centros as $k => $bodega)
        {
          $sql  = "SELECT IM.prefijo, ";
          $sql .= " 	  IM.numero, ";
          $sql .= " 	  IM.total_costo,";
          $sql .= "       BG.lapso_cerrado, ";
          $sql .= "       BG.descripcion AS bodega_descripcion, ";
          $sql .= "       DC.descripcion ";
          $sql .= "FROM   inv_bodegas_movimiento IM,";
          $sql .= " 	  inv_bodegas_documentos IB,";
          $sql .= " 	  documentos DC,";
          $sql .= " 	  bodegas BG, ";
          $sql .= "       inv_bodegas_userpermisos_admin IA ";
          $sql .= "WHERE  IM.empresa_id = '".$empresa."' ";
          $sql .= "AND    TO_CHAR(IM.fecha_registro, 'YYYYMM') = '".$bodega['lapso_cerrado']."' ";
          $sql .= "AND    IM.bodega = '".$k."' ";
          $sql .= "AND    IM.empresa_id = IB.empresa_id ";
          $sql .= "AND    IM.centro_utilidad = IB.centro_utilidad ";
          $sql .= "AND    IM.bodega = IB.bodega ";
          $sql .= "AND    IM.documento_id = IB.documento_id ";
          $sql .= "AND    IB.empresa_id = DC.empresa_id ";
          $sql .= "AND    IB.documento_id = DC.documento_id ";
          $sql .= "AND    IB.empresa_id = BG.empresa_id ";
          $sql .= "AND    IB.centro_utilidad = BG.centro_utilidad ";
          $sql .= "AND    IB.bodega = BG.bodega ";
          $sql .= "AND    BG.bodega = IA.bodega ";
          $sql .= "AND    IA.sw_cierre = '1' ";
          $sql .= "AND    IA.usuario_id = ".$usuario." ";
          /*print_r($sql);*/
          if(!$rst = $this->ConexionBaseDatos($sql))
            return false;
            
          while(!$rst->EOF)
          {
            $datos[$k][] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
          }
          $rst->Close();
        }
      }
      
      return $datos;
    }
  }
?>