<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: RadicacionManual.class.php,v 1.3 2008/10/23 22:09:23 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : RadicacionManual
  * Clase donde se hace el manejo del registro manual de la radicacion
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class RadicacionManual extends ConexionBD
  {
    /**
    * Contructor de la clase
    */
    function RadicacionManual(){}
    /**
    * Funcion para hacer el registro de la radicacion
    * 
    * @param array $datos Vector con los datos de la radicacion
    * @param string $empresa referencia al identificador de la empresa
    *
    * @return mixed
    */
    function IngresarRadicacion($datos,$empresa)
    {
      $sql = "SELECT 	NEXTVAL('cxp_radicacion_cxp_radicacion_id_seq') AS cxp_radicacion_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $radicacion_id = array();
      if(!$rst->EOF)
      {
        $radicacion_id = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      if($datos['tipo_especialidad'] == "-1" || !$datos['tipo_especialidad'])
        $datos['tipo_especialidad'] = "NULL";
      else
        $datos['tipo_especialidad'] = "'".$datos['tipo_especialidad']."'";
      
      if($datos['tipo_servicio'] == "-1" || !$datos['tipo_servicio'])
        $datos['tipo_servicio'] = "NULL";
      else
        $datos['tipo_servicio'] = "'".$datos['tipo_servicio']."'";
      
      $sw_rips = "0";
      if($datos['tipo_ingreso'] == '2') $sw_rips = "1";
      if(!$datos['numero_digitos']) $datos['numero_digitos'] = "NULL";
      
      $sql  = "INSERT INTO cxp_radicacion ";
      $sql .= " ( cxp_radicacion_id ,";
      $sql .= "   proveedor_id,";
      $sql .= "   observacion,";
      $sql .= "   fecha_radicacion ,";
      $sql .= "   numero_cuentas ,";
      $sql .= "   empresa_id,";
      $sql .= "   cxp_medio_pago_id,";
      $sql .= "   cxp_tipo_servicio_id,";
      $sql .= "   cxp_especialidad_id,";
      $sql .= "   sw_rips,";
      $sql .= "   digitos_prefijo,";
      $sql .= "   tipo_cxp,";
      $sql .= "   usuario_registro";
      $sql .= " )";
      $sql .= "VALUES";
      $sql .= " (";
      $sql .= "    ".$radicacion_id['cxp_radicacion_id'].",";
      $sql .= "    ".$datos['proveedor'].", ";
      $sql .= "   '".$datos['observacion']."', ";
      $sql .= "   '".$this->DividirFecha($datos['fecha_radicacion'])."'::date, ";
      $sql .= "    0, ";
      $sql .= "    '".$empresa."', ";
      $sql .= "   '".$datos['medio_pago']."', ";
      $sql .= "    ".$datos['tipo_servicio'].", ";
      $sql .= "    ".$datos['tipo_especialidad'].", ";
      $sql .= "   '".$sw_rips."',";
      $sql .= "    ".$datos['numero_digitos'].", ";
      $sql .= "   '".$datos['tipo_cuenta']."', ";
      $sql .= "    ".UserGetUID()." ";
      $sql .= " ); ";
      
      if($datos['auditor_medico'] == "-1") $datos['auditor_medico'] = "NULL";
      
      $sql .= "INSERT INTO cxp_auditores_facturas  ";
      $sql .= "(  ";
      $sql .= "   cxp_radicacion_id , ";
      $sql .= "   cxp_auditor_medico , "; 
      $sql .= "   cxp_auditor_administrativo ";
      $sql .= ") ";
      $sql .= "VALUES ( ";
      $sql .= "    ".$radicacion_id['cxp_radicacion_id'].",";
      $sql .= "    ".$datos['auditor_medico'].",";
      $sql .= "    ".$datos['auditor']." ";
      $sql .= "); ";
      
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return $radicacion_id['cxp_radicacion_id'];
    }
    /**
    * Funcion donde se registra la informacion de la factura
    * 
    * @param array $datos Array con los datos de la factura 
    * @param string $empresa identificador de la empresa
    * @param int $documento identificador del documento
    * @param int $dias_gracia Dias de gracia
    *
    * @return boolean
    */
    function IngresarFactura($datos,$empresa,$documento,$dias_gracia)
    {
      $sql  = "SELECT dias_de_gracia ";
      $sql .= "FROM   cxp_proveedores_vencimiento ";
      $sql .= "WHERE  codigo_proveedor_id = ".$datos['proveedor']." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $dias = array();
      if(!$rst->EOF)
      {
        $dias = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      if(empty($dias)) $dias['dias_de_gracia'] = $dias_gracia;
      
      $sql  = "SELECT  tipo_id_tercero,";
      $sql .= "        tercero_id ";
      $sql .= "FROM    terceros_proveedores ";
      $sql .= "WHERE   codigo_proveedor_id = ".$datos['proveedor']." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $tercero = array();
      if(!$rst->EOF)
      {
        $tercero = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      if(empty($tercero))
      {
        $tercero['tipo_id_tercero'] = "NULL";
        $tercero['tercero_id'] = "NULL";
      }
      else
      {
        $tercero['tipo_id_tercero'] = "'".$tercero['tipo_id_tercero']."'";
        $tercero['tercero_id'] = "'".$tercero['tercero_id']."'";
      }
      
      $this->ConexionTransaccion();
      $sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE ";//Bloqueo de tabla 
			if(!$rst = $this->ConexionTransaccion($sql)) return false;

			$sql  = "SELECT prefijo,numeracion FROM documentos ";
			$sql .= "WHERE documento_id = ".$documento." AND empresa_id = '".$empresa."' ";
					
			if(!$rst = $this->ConexionTransaccion($sql)) return false;
			
			$numero = array();
			if(!$rst->EOF)
      {
      	$numero = $rst->GetRowAssoc($ToUpper = false);				
      	$rst->MoveNext();
      }
      
      $vg = 0;
      if(!$datos['valor_iva']) $datos['valor_iva'] = 0;
      if($datos['valor_gravamen']) $vg = $datos['valor_gravamen'];
      if($datos['numero_contrato'] == '-1') $datos['numero_contrato'] = "";
      
      $f = explode("/",$datos['fecha_radicacion']);
 			$fecha_venc = date("Y-m-d", mktime(0, 0, 0,$f[1],intval($f[0]+$dias['dias_de_gracia']),$f[2]));
      
      $f = explode("/",$datos['fecha_factura']);
      if(sizeof($f) == 3)
        $datos['fecha_factura'] = $f[2]."-".$f[1]."-".$f[0];
      
      $sql  = "INSERT INTO cxp_facturas ";
      $sql .= "( ";
      $sql .= "    empresa_id , ";
      $sql .= "    prefijo , ";
      $sql .= "    numero , ";
      $sql .= "    documento_id, ";
      $sql .= "    cxp_radicacion_id , ";
      $sql .= "    tipo_id_tercero , ";
      $sql .= "    tercero_id , ";
      $sql .= "    prefijo_factura , ";
      $sql .= "    numero_factura , ";
      $sql .= "    numero_contrato , ";
      $sql .= "    fecha_documento, ";
      $sql .= "    fecha_vencimiento, ";
      $sql .= "    cxp_estado, ";
      $sql .= "    valor_total, ";
      $sql .= "    valor_iva, ";
      $sql .= "    valor_gravamen, ";
      $sql .= "    saldo, ";
      $sql .= "    tipo_cxp, ";
      $sql .= "    usuario_registro ";
      $sql .= ")";
      $sql .= "VALUES (";
      $sql .= "   '".$empresa."',";
      $sql .= "   '".$numero['prefijo']."',";
      $sql .= "    ".$numero['numeracion'].",";
      $sql .= "    ".$documento.",";
      $sql .= "    ".$datos['radicacion_id'].",";
      $sql .= "    ".$tercero['tipo_id_tercero'].",";
      $sql .= "    ".$tercero['tercero_id'].",";
      $sql .= "   '".$datos['prefijo_factura']."',";
      $sql .= "   '".$datos['numero_factura']."',";
      $sql .= "   '".$datos['numero_contrato']."',";
      $sql .= "   '".$datos['fecha_factura']."'::date,";
      $sql .= "   '".$fecha_venc."'::date,";
      $sql .= "     'R',";
      $sql .= "    ".$datos['valor_total'].",";
      $sql .= "    ".$datos['valor_iva'].",";
      $sql .= "    ".$vg.",";
      $sql .= "    ".$datos['valor_total'].",";
      $sql .= "   '".$datos['tipo_cuenta']."',";
      $sql .= "    ".UserGetUID()." ";
      $sql .= ")";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      if($datos['afiliado_tipo_id'] != "-1" && trim($datos['afiliado_id']) != "")
      {
        $sql  = "INSERT INTO cxp_pacientes_facturas ";
        $sql .= "( ";
        $sql .= "   empresa_id, ";
        $sql .= "   prefijo, ";
        $sql .= "   numero, ";
        $sql .= "   tipo_id_paciente, ";
        $sql .= "   paciente_id ";
        $sql .= ")";
        $sql .= "VALUES (";
        $sql .= "   '".$empresa."',";
        $sql .= "   '".$numero['prefijo']."',";
        $sql .= "    ".$numero['numeracion'].",";
        $sql .= "   '".$datos['afiliado_tipo_id']."',";
        $sql .= "   '".$datos['afiliado_id']."' ";
        $sql .= ")";
        
        if(!$rst = $this->ConexionTransaccion($sql)) return false;
      }
      
      $f = explode("-",$datos['fecha_factura']);
      $factura = date("Y-m-d",strtotime($f[2]."/".$f[1]."/".$f[0]));
      
      if(!$datos['fecha_inicial'])
      {
        $datos['fecha_inicial'] = $factura;
        $datos['fecha_final'] = $factura;
      }
      
      if($datos['fecha_inicial'] > $factura )  $datos['fecha_inicial'] = $factura;
      if($datos['fecha_final'] < $factura )  $datos['fecha_final'] = $factura;
      
      $sql  = "UPDATE cxp_radicacion ";
      $sql .= "SET    numero_cuentas =  numero_cuentas+1, ";
      $sql .= "       fecha_inicial = '".$datos['fecha_inicial']."'::date ,";
      $sql .= "       fecha_final = '".$datos['fecha_final']."'::date ";
      $sql .= "WHERE  cxp_radicacion_id = ".$datos['radicacion_id']."; ";
      
      $sql .= "UPDATE documentos ";
			$sql .= "SET 	  numeracion = numeracion + 1 ";
			$sql .= "WHERE  documento_id = ".$documento." ";
      $sql .= "AND    empresa_id = '".$empresa."' ";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      $this->Commit();
      return array("fecha_inicial"=>$datos['fecha_inicial'],"fecha_final"=>$datos['fecha_final']);
    }
  }
?>