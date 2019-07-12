<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: Reintegros.class.php,v 1.1 2009/01/14 22:22:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : 
  * Clase encargada de hacer las consultas de las ordenes de servicios
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Reintegros extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function Reintegros(){}
    /**
    * Funcion para obtener la lista de afiliados existenters en el sistema
    *
    * @param array $datos vector con los parametros de busqueda
    * @param integer $pg_siguiente indica el numero de la pagina que se esta visualizando
    * @param integer $op Indica si la consulta se hara completa o no
    *
    * @return mixed 
    */
    function ObtenerListaAfiliados($datos,$pg_siguiente=0,$op=false)
    {
      $sql  = "SELECT   EA.eps_afiliacion_id, ";
      $sql .= "         EA.afiliado_tipo_id, ";
      $sql .= "         EA.afiliado_id, ";
      $sql .= "         EA.eps_tipo_afiliado_id, ";
      $sql .= "         EA.estado_afiliado_id, ";
      $sql .= "         TO_CHAR(EA.fecha_afiliacion,'DD/MM/YYY') AS fecha_afiliacion, ";
      $sql .= "         ED.primer_apellido || ' ' || ED.segundo_apellido AS apellidos_afiliado,  ";
      $sql .= "         ED.primer_nombre  || ' ' || ED.segundo_nombre AS nombres_afiliado, ";
      $sql .= "         EP.descripcion_eps_tipo_afiliado, ";
      $sql .= "         EE.descripcion_estamento, ";
      $sql .= "         EE.estamento_id, ";
      $sql .= "         EE.codigo_dependencia_id, ";
      $sql .= "         CG.ciuo_88_gran_grupo, ";
      $sql .= "         CG.ciuo_88_subgrupo_principal, ";
      $sql .= "         CG.ciuo_88_subgrupo, ";
      $sql .= "         CG.ciuo_88_grupo_primario, ";
      $sql .= "         CG.descripcion_ciuo_88_grupo_primario, ";
      $sql .= "         AU.descripcion_subestado, ";
      $sql .= "         AE.descripcion_estado ";
      $whr  = "FROM     eps_afiliados EA ";
      $whr .= "         LEFT JOIN (  SELECT  EE.descripcion_estamento, ";
      $whr .= "                   EE.estamento_id, ";
      $whr .= "                   EC.codigo_dependencia_id, ";
      $whr .= "                   EC.eps_afiliacion_id, ";
      $whr .= "                   EC.afiliado_tipo_id, ";
      $whr .= "                   EC.afiliado_id ";
      $whr .= "           FROM    eps_afiliados_cotizantes EC,  ";
      $whr .= "                   eps_estamentos EE ";
      $whr .= "           WHERE   EE.estamento_id = EC.estamento_id) AS EE  ";
      $whr .= "         ON (  EE.eps_afiliacion_id = EA.eps_afiliacion_id AND ";
      $whr .= "               EE.afiliado_tipo_id = EA.afiliado_tipo_id AND ";
      $whr .= "               EE.afiliado_id = EA.afiliado_id ), ";
      $whr .= "         eps_afiliados_datos ED ";
      $whr .= "         LEFT JOIN ciuo_88_grupos_primarios CG";
      $whr .= "         ON (ED.ciuo_88_grupo_primario = CG.ciuo_88_grupo_primario), ";
      $whr .= "         eps_tipos_afiliados EP, ";
      $whr .= "         eps_afiliados_estados AE,";
      $whr .= "         eps_afiliados_subestados AU ";
      $whr .= "WHERE    ED.afiliado_tipo_id = EA.afiliado_tipo_id ";
      $whr .= "AND      ED.afiliado_id = EA.afiliado_id ";
      $whr .= "AND      EP.eps_tipo_afiliado_id = EA.eps_tipo_afiliado_id ";
      $whr .= "AND      AU.estado_afiliado_id = EA.estado_afiliado_id ";
      $whr .= "AND      AU.subestado_afiliado_id = EA.subestado_afiliado_id ";
      $whr .= "AND      AU.estado_afiliado_id = AE.estado_afiliado_id ";
      
      if($datos['Documento'])
      {
        $whr .= "AND      ED.afiliado_id = '".$datos['Documento']."' ";
        
        if($datos['TipoDocumento'] != "-1")
          $whr .= "AND      ED.afiliado_tipo_id = '".$datos['TipoDocumento']."' ";
      }
      
      if($datos['eps_afiliacion_id'])
        $whr .= "AND    EA.eps_afiliacion_id = '".$datos['eps_afiliacion_id']."' ";
      
      if($datos['Nombres'] || $datos['Apellidos'])
      {
        $util = AutoCarga::factory('ClaseUtil');
        $whr .= "AND      ".$util->FiltrarNombres($datos['Nombres'],$datos['Apellidos'],"ED");
      }
      
      if($op)
      {
        if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente))
         return false;
        
        $whr .= "ORDER BY apellidos_afiliado ";
        $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      }
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;

      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Consulta de las dependencias de UV registrados
    *
    * @return array
    */
    function ObtenerDependenciasUV()
    {
        $sql  = "SELECT codigo_dependencia_id, descripcion_dependencia ";
        $sql .= "FROM uv_dependencias ";
        $sql .= "ORDER BY descripcion_dependencia ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();
        while(!$rst->EOF)
        {
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }    
    /**
    * Consulta de los conceptos de reintegro
    * registrados
    *
    * @return array
    */
    function ObtenerConceptosReintegro()
    {
        $sql  = "SELECT cxp_concepto_reintegro_id, ";
        $sql .= "       descripcion_concepto ";
        $sql .= "FROM   cxp_conceptos_reintegros ";
        $sql .= "WHERE  sw_activo = '1' ";
        $sql .= "AND    sw_mostrar = '1' ";
        $sql .= "ORDER BY descripcion_concepto ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se consultan los datos de loa benficiarios que estan asociados
    * a un contizante
    *
    * @param array $datos Vector con los datos de la identificacion del cotizante
    *
    * @return array
    */
    function ObtenerBeneficiariosCotizante($datos)
    {
      $sql  = "SELECT AD.afiliado_tipo_id   , ";
      $sql .= "       AD.afiliado_id, ";
      $sql .= "       AD.primer_apellido    , ";
      $sql .= "       AD.segundo_apellido   , ";
      $sql .= "       AD.primer_nombre  , ";
      $sql .= "       AD.segundo_nombre     , ";
      $sql .= "       AF.eps_afiliacion_id  , ";
      $sql .= "       PB.descripcion_parentesco , ";
      $sql .= "       PB.parentesco_id ";
      $sql .= "FROM   eps_afiliados_datos AD,";
      $sql .= "       eps_afiliados AF,";
      $sql .= "       eps_afiliados_beneficiarios AB,";
      $sql .= "       eps_parentescos_beneficiarios PB ";
      $sql .= "WHERE  AB.eps_afiliacion_id = '".$datos['eps_afiliacion_id']."' ";
      
      if($op == "C")
      {
        $sql .= "AND    AB.cotizante_id = '".$datos['afiliado_id']."' ";
        $sql .= "AND    AB.cotizante_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      }
      else if($op == "B")
      {
        $sql .= "AND    AB.afiliado_id = '".$datos['afiliado_id']."' ";
        $sql .= "AND    AB.afiliado_tipo_id = '".$datos['afiliado_tipo_id']."' ";
      }
      
      $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
      $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
      $sql .= "AND    AB.eps_afiliacion_id = AF.eps_afiliacion_id ";
      $sql .= "AND    AB.afiliado_tipo_id = AF.afiliado_tipo_id ";
      $sql .= "AND    AB.afiliado_id = AF.afiliado_id ";
      $sql .= "AND    AB.parentesco_id = PB.parentesco_id ";
      $sql .= "ORDER BY AF.eps_afiliacion_id ASC ";
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion para hacer el registro de la radicacion
    * 
    * @param array $datos Vector con los datos de la radicacion
    * @param integer $dias_gracia Dias de gracia para el vencimiento de la factura
    * @param integer $documento Identificador del documento
    * @param string $tipo_cuenta Identificador del tipo de cuenta
    * @param string $empresa Identificador de la empresa
    *
    * @return mixed
    */
    function IngresarRadicacion($datos,$dias_gracia,$documento,$tipo_cuenta,$empresa)
    {
      $dias = array();
      $dias['dias_de_gracia'] = 0;

      $datos['tipo_id_tercero'] = "NULL";
      $datos['tercero_id'] = "NULL";      
      
      if($datos['codigo_proveedor'])
      {
        $sql  = "SELECT  tipo_id_tercero,";
        $sql .= "        tercero_id ";
        $sql .= "FROM    terceros_proveedores ";
        $sql .= "WHERE   codigo_proveedor_id = ".$datos['codigo_proveedor']." ";
      
        if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
        $tercero = array();
        if(!$rst->EOF)
        {
          $tercero = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
      
        if(!empty($tercero))
        {
          $tercero['tipo_id_tercero'] = "'".$tercero['tipo_id_tercero']."'";
          $tercero['tercero_id'] = "'".$tercero['tercero_id']."'";
        }
      }
      else
        $datos['codigo_proveedor'] = "NULL";
      
      $this->ConexionTransaccion();
      $sql = "SELECT 	NEXTVAL('cxp_radicacion_cxp_radicacion_id_seq') AS cxp_radicacion_id ";
      
      if(!$rst = $this->ConexionTransaccion($sql)) return false;
      
      $radicacion_id = array();
      if(!$rst->EOF)
      {
        $radicacion_id = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      $nv = $radicacion_id['cxp_radicacion_id'];
      if($nv > 1) $nv = $nv -1;
        
      $sqle = "SELECT SETVAL('cxp_radicacion_cxp_radicacion_id_seq',".($nv)."); ";

      $sql  = "INSERT INTO cxp_radicacion ";
      $sql .= " (   cxp_radicacion_id ,";
      $sql .= "     empresa_id,";
      $sql .= "     observacion,";
      $sql .= "     fecha_radicacion ,";
      $sql .= "     fecha_inicial ,";
      $sql .= "     fecha_final ,";
      $sql .= "     numero_cuentas ,";
      $sql .= "     proveedor_id , ";
      $sql .= "     descripcion_tercero_asociado , ";
      $sql .= "     usuario_registro";
      $sql .= " )";
      $sql .= "VALUES";
      $sql .= " (";
      $sql .= "     ".$radicacion_id['cxp_radicacion_id'].",";
      $sql .= "    '".$empresa."', ";
      $sql .= "    '".$datos['observacion']."', ";
      $sql .= "    '".$datos['fecha_solicitud']."'::date, ";
      $sql .= "    '".$datos['fecha_factura']."'::date,";
      $sql .= "    '".$datos['fecha_factura']."'::date,";
      $sql .= "    1, ";
      $sql .= "     ".$datos['codigo_proveedor'].", ";
      $sql .= "    '".$datos['prestador_servicio']."', ";
      $sql .= "    ".UserGetUID()." ";
      $sql .= " ) ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      { 
        $this->ConexionBaseDatos($sqle);
        return false;
      }
      
      $sql  = "INSERT INTO cxp_auditores_facturas  ";
      $sql .= "( ";
      $sql .= "   cxp_radicacion_id, ";
      $sql .= "   cxp_auditor_administrativo ";
      $sql .= ") ";
      $sql .= "VALUES ( ";
      $sql .= "    ".$radicacion_id['cxp_radicacion_id'].",";
      $sql .= "    ".$datos['auditor']." ";
      $sql .= "); ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      { 
        $this->ConexionBaseDatos($sqle);
        return false;
      }
      
      $sql = "SELECT 	NEXTVAL('cxp_reintegros_cxp_reintegro_id_seq') AS cxp_reintegro_id ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      { 
        $this->ConexionBaseDatos($sqle);
        return false;
      }
      
      $reintegro_id = array();
      if(!$rst->EOF)
      {
        $reintegro_id = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      $nv = $reintegro_id['cxp_reintegro_id'];
      if($nv > 1) $nv = $nv -1;
        
      $sqle .= "SELECT SETVAL('cxp_reintegros_cxp_reintegro_id_seq',".($nv)."); ";
      
      $estamento_id = $dependencia_id = $ciu88_grupos_primarios = "NULL";
      if($datos['estamento_id']) $estamento_id = "'".$datos['estamento_id']."'";
      if($datos['dependencia_laboral']) $dependencia_id = "'".$datos['dependencia_laboral']."'";
      if($datos['ciu88_grupos_primarios']) $ciu88_grupos_primarios = "'".$datos['ciu88_grupos_primarios']."'";
      if($datos['familiar_cx'] == "1")
      {
        $datos['familiar_tipo_id'] = "'".$datos['familiar_tipo_id']."'";
        $datos['familiar_id'] = "'".$datos['familiar_id']."'";
        $datos['parentesco_id'] = "'".$datos['parentesco_id']."'";
      }
      else
      {
        $datos['familiar_tipo_id'] = "NULL";
        $datos['familiar_id'] = "NULL";
        $datos['parentesco_id'] = "NULL";
      }
      
      $sql  = "INSERT INTO cxp_reintegros( ";
      $sql .= "     cxp_reintegro_id, ";
      $sql .= "     cxp_radicacion_id, ";
      $sql .= "     fecha_solicitud, ";
      $sql .= "     afiliado_tipo_id, ";
      $sql .= "     afiliado_id, ";

      $sql .= "     lugar_expedicion_documento, ";
      $sql .= "     estamento_id, ";
      $sql .= "     codigo_dependencia_id, ";
      $sql .= "     ciuo_88_grupo_primario, ";
      $sql .= "     cxp_concepto_reintegro_id, ";
      $sql .= "     descripcion_otro_concepto, ";
      $sql .= "     valor_solicitado, ";
      $sql .= "     descricion_excepcion, ";
      $sql .= "     observacion_reintegro, ";
      $sql .= "     familiar_tipo_id, ";
      $sql .= "     familiar_id, ";
      $sql .= "     parentesco_id, ";
      $sql .= "     usuario_registro ";
      $sql .= " )";
      $sql .= "VALUES";
      $sql .= "(";
      $sql .= "     ".$reintegro_id['cxp_reintegro_id'].",";
      $sql .= "     ".$radicacion_id['cxp_radicacion_id'].",";
      $sql .= "    '".$datos['fecha_solicitud']."'::date, ";
      $sql .= "    '".$datos['afiliado_tipo_id']."', ";
      $sql .= "    '".$datos['afiliado_id']."', ";

      $sql .= "    '".strtoupper($datos['lugar_expedicion_documento'])."', ";
      $sql .= "     ".$estamento_id.", ";
      $sql .= "     ".$dependencia_id.", ";
      $sql .= "     ".$ciu88_grupos_primarios.", ";
      $sql .= "    '".$datos['concepto_reintegro']."', ";
      $sql .= "    '".$datos['otro_concepto']."', ";
      $sql .= "     ".$datos['valor_solicitado'].", ";
      $sql .= "    '".$datos['explicacion']."', ";
      $sql .= "    '".$datos['observacion']."', ";
      $sql .= "     ".$datos['familiar_tipo_id'].", ";
      $sql .= "     ".$datos['familiar_id'].", ";
      $sql .= "     ".$datos['parentesco_id'].", ";
      $sql .= "     ".UserGetUID()." ";
      $sql .= " );";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      { 
        $this->ConexionBaseDatos($sqle);
        return false;
      }
      
      $sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE ";//Bloqueo de tabla 
			if(!$rst = $this->ConexionTransaccion($sql)) return false;

			$sql  = "SELECT prefijo,numeracion FROM documentos ";
			$sql .= "WHERE documento_id = ".$documento." AND empresa_id = '".$empresa."' ";
					
			if(!$rst = $this->ConexionTransaccion($sql))
      { 
        $this->ConexionBaseDatos($sqle);
        return false;
      }
      
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
      
      $f = explode("/",$datos['fecha_solicitud']);
 			$fecha_venc = date("Y-m-d", mktime(0, 0, 0,$f[1],intval($f[0]+$dias['dias_de_gracia']),$f[2]));
      
      $f = explode("/",$datos['fecha_factura']);
      if(sizeof($f) == 3)
        $datos['fecha_factura'] = $f[2]."-".$f[1]."-".$f[0];
        
      $sql  = "INSERT INTO cxp_facturas ";
      $sql .= "( ";
      $sql .= "     empresa_id , ";
      $sql .= "     prefijo , ";
      $sql .= "     numero , ";
      $sql .= "     documento_id, ";
      $sql .= "     cxp_radicacion_id , ";
      $sql .= "     prefijo_factura , ";
      $sql .= "     numero_factura , ";
      $sql .= "     fecha_documento, ";
      $sql .= "     fecha_vencimiento, ";
      $sql .= "     cxp_estado, ";
      $sql .= "     valor_total, ";
      $sql .= "     valor_iva, ";
      $sql .= "     valor_gravamen, ";
      $sql .= "     saldo, ";
      $sql .= "     tipo_cxp, ";
      $sql .= "     tipo_id_tercero , ";
      $sql .= "     tercero_id , ";
      $sql .= "     usuario_registro ";
      $sql .= ")";
      $sql .= "VALUES (";
      $sql .= "   '".$empresa."',";
      $sql .= "   '".$numero['prefijo']."',";
      $sql .= "    ".$numero['numeracion'].",";
      $sql .= "    ".$documento.",";
      $sql .= "    ".$radicacion_id['cxp_radicacion_id'].",";
      $sql .= "   '".$datos['prefijo_factura']."',";
      $sql .= "   '".$datos['numero_factura']."',";
      $sql .= "   '".$datos['fecha_factura']."'::date,";
      $sql .= "   '".$fecha_venc."'::date,";
      $sql .= "     'R',";
      $sql .= "    ".$datos['valor_total'].",";
      $sql .= "    ".$datos['valor_iva'].",";
      $sql .= "    ".$vg.",";
      $sql .= "    ".$datos['valor_total'].",";
      $sql .= "   '".$tipo_cuenta."',";
      $sql .= "     ".$datos['tipo_id_tercero'].", ";
      $sql .= "     ".$datos['tercero_id'].", ";  
      $sql .= "    ".UserGetUID()." ";
      $sql .= "); ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      { 
        $this->ConexionBaseDatos($sqle);
        return false;
      }
      
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
      if($datos['familiar_cx'] == "1")
      {
        $sql .= "     ".$datos['familiar_tipo_id'].", ";
        $sql .= "     ".$datos['familiar_id']." ";
      }
      else
      {
        $sql .= "   '".$datos['afiliado_tipo_id']."',";
        $sql .= "   '".$datos['afiliado_id']."' ";
      }
      $sql .= ");";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      { 
        $this->ConexionBaseDatos($sqle);
        return false;
      }
      
      $sql  = "UPDATE documentos ";
			$sql .= "SET 	  numeracion = numeracion + 1 ";
			$sql .= "WHERE  documento_id = ".$documento." ";
      $sql .= "AND    empresa_id = '".$empresa."' ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
      { 
        $this->ConexionBaseDatos($sqle);
        return false;
      }
      
      $this->Commit();
      $numero['cxp_radicacion_id'] = $radicacion_id['cxp_radicacion_id'];
      $numero['cxp_reintegro_id'] = $reintegro_id['cxp_reintegro_id'];
      $numero['codigo_proveedor'] = $datos['codigo_proveedor'];
      if(!empty($tercero))
      {
        $numero['tipo_id_tercero'] = $tercero['tipo_id_tercero'];
        $numero['tercero_id'] = $tercero['tercero_id'];
      }
      return $numero;
    }
    /**
    * Funcion donde se obtienen los proveedores
    *
    * @param array $datos Arreglo con los datos de los filtros de la consulta
    * @param integer $pg_siguiente Referencia a la pagina
    *
    * @return mixed
    */
    function ObtenerProveedores($datos,$pg_siguiente)
    {      
      $sql  = "SELECT TP.codigo_proveedor_id, ";
      $sql .= "       TR.tipo_id_tercero,  ";
      $sql .= "       TR.nombre_tercero,  ";
      $sql .= "       TR.telefono,  ";
      $sql .= "       TR.direccion,  ";
      $sql .= "       TR.tercero_id, ";
      $sql .= "       TR.tipo_id_tercero ";
      $whr  = "FROM   terceros_proveedores TP ,";
      $whr .= "       terceros TR ";
      $whr .= "WHERE  TR.tercero_id = TP.tercero_id ";
      $whr .= "AND    TR.tipo_id_tercero = TP.tipo_id_tercero ";
      if($datos['tipo_id_tercero'] != '-1')
        $whr .= "AND    TR.tipo_id_tercero = '".$datos['tipo_id_tercero']."' ";
      
      if($datos['tercero_id'])
        $whr .= "AND    TR.tercero_id = '".$datos['tercero_id']."' ";
      
      if($datos['nombre_tercero'])
        $whr .= "AND    TR.nombre_tercero ILIKE '".$datos['nombre_tercero']."' ";
     
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente))
        return false;
        
      $whr .= "ORDER BY TR.nombre_tercero ";
      $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;

      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
  }
?>