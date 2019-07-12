<?
/**
 * $Id: RipsSoat_ArchivoSobreAtencionVictima.class.php,v 1.4 2006/09/19 20:38:59 carlos Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS-CLASSES
 *
 * 1- ARCHIVO SOBRE LA ATENCION DE LA VICTIMA.
 */

/**
 * 1- ARCHIVO SOBRE LA ATENCION DE LA VICTIMA.
 *
 * @author    Ehudes Garcia <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.4 $
 * @package   IPSOFT-SIIS-CLASSES
 */
class RipsSoat_ArchivoSobreAtencionVictima extends RipsSoat
{

	/**
	 * Indica si se aplica o no procedimientos
	 *
	 * @var bool
	 */
	var $procedimiento;
	
	/**
	 * Indica si se aplica o no medicamentos
	 *
	 * @var bool
	 */
	var $medicamento;
	
	/**
	 * Tipo de medicamento 1=POS , 2 = NO POS
	 * 
	 * @var int 
	 */
	var $tipoMedicamento;
	
	/**
	 * Indica si hubo suministro de tipo servicio
	 *
	 * @var bool
	 */
	var $tipoServicio;
	
	/**
	 * Constructor
	 */
	function RipsSoat_ArchivoSobreAtencionVictima($envio_id)
	{
		$this->RipsSoat($nombre,$envio_id);
		$this->setNombre("AV");
        $this->cargarDatos();
		$metodoFormatos = array(
				"formatoColumna1",//CODIGO DEL PRESTADOR DE SERVICIOS (12)
				"formatoColumna2",//TIPO IDENTIFICACION DE LA VICTIMA (2)
				"formatoColumna3",//NUMERO IDENTIFICACION DE LA VICTIMA (20)
				"formatoColumna4",//FECHA DE LA ATENCION (DD/MM/AAAA) (10)
				"formatoColumna5",//CODIGO DE LA CONSULTA  (8)
				"formatoColumna6",//DIEGNOSTICO PRINCIPAL DE INGRESO (4)
				"formatoColumna7",//DIEGNOSTICO PRINCIPAL DE EGRESO (4)
				"formatoColumna8",//VALOR DE LA CONSULTA  (15)
				"formatoColumna9",//CODIGO DEL PROCEDIMIENTO (8)
				"formatoColumna10",//CANTIDAD DE PROCEDIMIENTOS (3)
				"formatoColumna11",//VALOR DE LOS PROCEDIMIENTOS (15)
				"formatoColumna12",//TIPO DE MEDICAMENTO (1)
				"formatoColumna13",//CODIGO DEL MEDICAMENTO POS  (20)
				"formatoColumna14",//NOMBRE GENERICO DEL MEDICAMENTO (30)
				"formatoColumna15",//FORMA FARMACEUTICA (20)
				"formatoColumna16",//CONCENTRACION DEL MEDICAMENTO (20)
				"formatoColumna17",//UNIDAD DE MEDIDA DEL MEDICAMENTO (20)
				"formatoColumna18",//CANTIDAD DE MEDICAMENTOS (3)
				"formatoColumna19",//VALOR TOTAL DE LOS MEDICAMENTOS (15)
				"formatoColumna20",//TIPO DE SERVICIO (1)
				"formatoColumna21",//CANTIDAD  (3)
				"formatoColumna22",//VALOR TOTAL DE LOS SERVICIOS (15)
				"formatoColumna23",//NUMERO DE LA FACTURA (20)
				"formatoColumna24",//FECHA DE FACTURA (DD/MM/AAAA) (10)
				"formatoColumna25"//VALOR TOTAL DE LA FACTURA  (15)
		);
		$this->setMetodosFormatos($metodosFormatos);
	}//Fin constructor
	
	/**
	 * Carga los datos necesarios para generar el archivo
	 * esta función se conecta directamente a la base de datos
	 *
	 */
	function cargarDatos()
	{   
        $procedimientos = $this->GetProcedimientos();
        if(is_array($procedimientos))
        {
            $this->arrayContenido = $procedimientos;//procedimientos
            unset($procedimeintos);
        }
        $consultas = $this->GetDatosConsulta();
        if(is_array($consultas))
        {
            $this->arrayContenido = array_merge($this->arrayContenido,$consultas);
            unset($consultas);
        }
        $traslados = $this->GetTraslados();//traslasdos o servicios de ambulancia
        if(is_array($traslados))
        {
            $this->arrayContenido = array_merge($this->arrayContenido,$traslados);
            unset($traslados);
        }
	}//Fin cargarDatos
	
    
    /**
     * 
     */
    function GetProcedimientos()
    {
        $sql = "
            SELECT 
                E.codigo_sgsss,
                SE.tipo_id_paciente, 
                SE.paciente_id,
                SE.fecha_registro, 
                NULL AS codigo_consulta,
                (SELECT tipo_diagnostico_id FROM hc_diagnosticos_ingreso WHERE sw_principal = '1' 
                 AND evolucion_id = (SELECT MIN(evolucion_id) FROM hc_evoluciones WHERE ingreso=ISO.ingreso         AND estado = '0')) 
                 AS dx_ingreso,
                (SELECT tipo_diagnostico_id FROM hc_diagnosticos_egreso WHERE sw_principal = '1' 
                 AND evolucion_id = (SELECT MIN(evolucion_id) FROM hc_evoluciones WHERE ingreso=ISO.ingreso AND estado = '0')) 
                 AS dx_egreso,
                NULL AS valor_consulta,
                CD.cargo,
                CD.cantidad,
                CD.precio AS precio_cuentas,
                NULL AS tipo_medicamento,
                NULL AS codigo_pos,
                NULL AS nombre_medicamento,
                NULL AS forma_farmaceutica,
                NULL AS concentracion,
                NULL AS unidad_medida,
                NULL AS cantidad_medicamento,
                NULL AS valor_total_medicamento,
                4 AS tipo_servicio,
                1 AS cantidad_servicio,
                CD.precio AS valor_total_servicio,
                FF.prefijo|| ' '|| FF.factura_fiscal AS factura,
                FF.fecha_registro,
                FF.total_factura        
            FROM 
                ingresos_soat AS ISO,
                cuentas AS C,
                cuentas_detalle AS CD,
                cups AS CU,
                rips_tipos_cargos AS RTC,
                soat_eventos SE,
                empresas AS E,
                fac_facturas_cuentas AS FFC,
                fac_facturas AS FF,
                envios_detalle AS ED
        
            WHERE 
                ISO.ingreso = C.ingreso
                AND C.numerodecuenta = CD.numerodecuenta
                AND CD.cargo_cups = CU.cargo
                AND CU.grupo_tipo_cargo = RTC.grupo_tipo_cargo
                AND CU.tipo_cargo = RTC.tipo_cargo
                AND RTC.tipos_rips_soat_id = 'PRO'
                AND ISO.evento = SE.evento
                AND SE.empresa_id = E.empresa_id
                
                AND FFC.prefijo = FF.prefijo
                AND FFC.factura_fiscal = FF.factura_fiscal
                AND FFC.empresa_id = FF.empresa_id
                AND ISO.ingreso = C.ingreso
                AND C.numerodecuenta = FFC.numerodecuenta
                AND FFC.prefijo = ED.prefijo
                AND FFC.factura_fiscal = ED.factura_fiscal
                AND FFC.empresa_id = ED.empresa_id
                AND ED.envio_id = ".$this->envio_id;
        list($dbconn) = GetDBconn();
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error en la consulta";
            $this->mensajeDeError = $sql." ".$dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;                                
            return false;
        }
        return $resultado->GetRows();
    }//Fin
    
    /**
    *
    */    
    function GetDatosConsulta()
    {
        $sql ="
            SELECT 
                E.codigo_sgsss,
                SE.tipo_id_paciente, 
                SE.paciente_id,
                SE.fecha_registro, 
                CD.cargo AS codigo_consulta,
                (SELECT tipo_diagnostico_id FROM hc_diagnosticos_ingreso WHERE sw_principal = '1' 
                 AND evolucion_id = (SELECT MIN(evolucion_id) FROM hc_evoluciones WHERE ingreso=ISO.ingreso         AND estado = '0')) 
                 AS dx_ingreso,
                (SELECT tipo_diagnostico_id FROM hc_diagnosticos_egreso WHERE sw_principal = '1' 
                 AND evolucion_id = (SELECT MIN(evolucion_id) FROM hc_evoluciones WHERE ingreso=ISO.ingreso AND estado = '0')) 
                 AS dx_egreso,             
                CD.precio AS precio_cuentas,
                NULL AS codigo_procedimiento,
                NULL AS cantidad_procedimientos,
                NULL AS valor_procedimiento,
                NULL AS tipo_medicamento,
                NULL AS codigo_pos,
                NULL AS nombre_medicamento,
                NULL AS forma_farmaceutica,
                NULL AS concentracion,
                NULL AS unidad_medida,
                NULL AS cantidad_medicamento,
                NULL AS valor_total_medicamento,
                4 AS tipo_servicio,
                1 AS cantidad_servicio,
                CD.precio AS valor_total_servicio,
                FF.prefijo|| ' '|| FF.factura_fiscal AS factura,
                FF.fecha_registro,
                FF.total_factura
            FROM 
                ingresos_soat AS ISO,
                cuentas AS C,
                cuentas_detalle AS CD,
                cups AS CU,
                rips_tipos_cargos AS RTC,
                soat_eventos SE,
                empresas AS E,
                fac_facturas_cuentas AS FFC,
                fac_facturas AS FF,
                envios_detalle AS ED
        
            WHERE 
                ISO.ingreso = C.ingreso
                AND C.numerodecuenta = CD.numerodecuenta
                AND CD.cargo_cups = CU.cargo
                AND CU.grupo_tipo_cargo = RTC.grupo_tipo_cargo
                AND CU.tipo_cargo = RTC.tipo_cargo
                AND RTC.tipos_rips_soat_id = 'CM'
                AND ISO.evento = SE.evento
                AND SE.empresa_id = E.empresa_id
                
                AND FFC.prefijo = FF.prefijo
                AND FFC.factura_fiscal = FF.factura_fiscal
                AND FFC.empresa_id = FF.empresa_id
                AND ISO.ingreso = C.ingreso
                AND C.numerodecuenta = FFC.numerodecuenta
                AND FFC.prefijo = ED.prefijo
                AND FFC.factura_fiscal = ED.factura_fiscal
                AND FFC.empresa_id = ED.empresa_id
                AND ED.envio_id = ".$this->envio_id;
        list($dbconn) = GetDBconn();
        //$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($sql);
        //$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error en la consulta";
            $this->mensajeDeError = $sql." ".$dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;                                
            return false;
        }
        return $resultado->GetRows();
    }
    
    /**
     *
     */
    function GetMedicamentosPorIngresoId()
    {
        $sql = "
               SELECT 
                    E.codigo_sgsss,
                    SE.tipo_id_paciente, 
                    SE.paciente_id,
                    SE.fecha_registro, 
                    NULL as codigo_consulta,
                    tabla.dx_ingreso,
                    tabla.dx_egreso,
                    0 as valor_consulta,
                    NULL as codigo_procedimiento,
                    NULL as cantidad_procedimiento,
                    NULL as valor_procedimiento,
                    CASE WHEN K.sw_pos = '1' THEN '1'
                        WHEN K.sw_pos IS NULL THEN '1'
                        WHEN K.sw_pos = '0' THEN '2'
                    END as tipo_medicamento, 
                    BD.codigo_producto,
                    H.descripcion,
                    INV.descripcion AS forma_farmacologica,
                    K.concentracion_forma_farmacologica || ' ' || K.cod_concentracion AS concentracion,
                    UN.descripcion AS unidad_medida,
                    tabla.cantidad,
                    tabla.valor_cargo AS valor_medicamento,
                    1 AS tipo_servicio,
                    1 AS cantidad_servicio,
                    tabla.valor_cargo AS valor_servicio,
                    tabla.factura AS factura,
                    tabla.fecha_registro,
                    tabla.total_factura
               FROM
               
                    (SELECT 
                         FF.prefijo|| ' '|| FF.factura_fiscal AS factura,
                         FF.fecha_registro,
                         FF.total_factura,
                         FFC.numerodecuenta,
                         CD.empresa_id,
                         CD.cargo,
                         CD.valor_cargo,
                         CD.precio,
                         CD.cantidad,
                         CD.consecutivo,
                         C.ingreso,
                         (SELECT tipo_diagnostico_id FROM hc_diagnosticos_ingreso WHERE sw_principal = '1' 
                         AND evolucion_id = (SELECT MIN(evolucion_id) FROM hc_evoluciones WHERE ingreso = C.ingreso AND estado = '0')) 
                         AS dx_ingreso,
                         (SELECT tipo_diagnostico_id FROM hc_diagnosticos_egreso WHERE sw_principal = '1' 
                         AND evolucion_id = (SELECT MIN(evolucion_id) FROM hc_evoluciones WHERE ingreso = C.ingreso AND estado = '0')) 
                         AS dx_egreso
                    
                    FROM envios_detalle AS ED,
                         fac_facturas AS FF,
                         fac_facturas_cuentas AS FFC,
                         cuentas_detalle AS CD,
                         cuentas AS C
                         
                    WHERE ED.envio_id = ".$this->envio_id."
                    AND FF.prefijo = ED.prefijo
                    AND FF.factura_fiscal = ED.factura_fiscal
                    AND FF.empresa_id = ED.empresa_id
                    AND FFC.prefijo = ED.prefijo
                    AND FFC.factura_fiscal = ED.factura_fiscal
                    AND FFC.empresa_id = ED.empresa_id
                    AND FFC.numerodecuenta = CD.numerodecuenta
                    AND CD.cargo = 'IMD'
                    AND C.numerodecuenta = CD.numerodecuenta) AS tabla 
               
                    RIGHT JOIN ingresos_soat AS ISO ON (ISO.ingreso = tabla.ingreso),
                    bodegas_documentos_d AS BD,
                    inventarios_productos AS H
                    LEFT JOIN medicamentos AS K ON (H.codigo_producto = K.codigo_medicamento)
                    LEFT JOIN inv_med_cod_forma_farmacologica AS INV ON (K.cod_forma_farmacologica = INV.cod_forma_farmacologica)
                    LEFT JOIN inv_unidades_medida_medicamentos AS UN ON (K.unidad_medida_medicamento_id = UN.unidad_medida_medicamento_id),
                    soat_eventos AS SE,
                    empresas AS E
                    
               WHERE BD.consecutivo = tabla.consecutivo
               AND BD.codigo_producto = H.codigo_producto
               AND SE.evento = ISO.evento
               AND E.empresa_id = tabla.empresa_id;";
          list($dbconn) = GetDBconn();
          //$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($sql);
          //$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = $sql." ".$dbconn->ErrorMsg();
               $this->fileError = __FILE__;
               $this->lineError = __LINE__;                                
               return false;
          }
          return $resultado->GetRows();
    }

       
    /**
     * Retorna 
     * 
     */
    function GetTraslados()
    {
        $sql = "
            SELECT 
                E.codigo_sgsss,
                SE.tipo_id_paciente, 
                SE.paciente_id,
                SE.fecha_registro, 
                NULL AS codigo_consulta,
                (SELECT tipo_diagnostico_id FROM hc_diagnosticos_ingreso WHERE sw_principal = '1' 
                 AND evolucion_id = (SELECT MIN(evolucion_id) FROM hc_evoluciones WHERE ingreso=ISO.ingreso         AND estado = '0')) 
                 AS dx_ingreso,
                (SELECT tipo_diagnostico_id FROM hc_diagnosticos_egreso WHERE sw_principal = '1' 
                 AND evolucion_id = (SELECT MIN(evolucion_id) FROM hc_evoluciones WHERE ingreso=ISO.ingreso AND estado = '0')) 
                 AS dx_egreso,
                NULL AS valor_consulta,
                CD.cargo,
                CD.cantidad,
                CD.precio AS precio_cuentas,
                NULL AS tipo_medicamento,
                NULL AS codigo_pos,
                NULL AS nombre_medicamento,
                NULL AS forma_farmaceutica,
                NULL AS concentracion,
                NULL AS unidad_medida,
                NULL AS cantidad_medicamento,
                NULL AS valor_total_medicamento,
                4 AS tipo_servicio,
                1 AS cantidad_servicio,
                CD.precio AS valor_total_servicio,
                FF.prefijo|| ' '|| FF.factura_fiscal AS factura,
                FF.fecha_registro,
                FF.total_factura        
            FROM 
                ingresos_soat AS ISO,
                cuentas AS C,
                cuentas_detalle AS CD,
                cups AS CU,
                rips_tipos_cargos AS RTC,
                soat_eventos SE,
                empresas AS E,
                fac_facturas_cuentas AS FFC,
                fac_facturas AS FF,
                envios_detalle AS ED
        
            WHERE 
                ISO.ingreso = C.ingreso
                AND C.numerodecuenta = CD.numerodecuenta
                AND CD.cargo_cups = CU.cargo
                AND CU.grupo_tipo_cargo = RTC.grupo_tipo_cargo
                AND CU.tipo_cargo = RTC.tipo_cargo
                AND RTC.tipos_rips_soat_id = 'TR'
                AND ISO.evento = SE.evento
                AND SE.empresa_id = E.empresa_id
                
                AND FFC.prefijo = FF.prefijo
                AND FFC.factura_fiscal = FF.factura_fiscal
                AND FFC.empresa_id = FF.empresa_id
                AND ISO.ingreso = C.ingreso
                AND C.numerodecuenta = FFC.numerodecuenta
                AND FFC.prefijo = ED.prefijo
                AND FFC.factura_fiscal = ED.factura_fiscal
                AND FFC.empresa_id = ED.empresa_id
                AND ED.envio_id = ".$this->envio_id;

        list($dbconn) = GetDBconn();
        //$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($sql);
        //$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error en la consulta";
            $this->mensajeDeError = $sql." ".$dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;                                
            return false;
        }
        return $resultado->GetRows();
    }//Fin
	
     /**
     * CODIGO DEL PRESTADOR DE SERVICIOS (12)
     * @param string str
     */
	function formatoColumna1($str)
	{
		$valor = $this->_formatoCadena($str,12);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"CODIGO DEL PRESTADOR DE SERVICIOS\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna1
	
	/**
	 * TIPO IDENTIFICACION DE LA VICTIMA (2)
	 * @param string str
	 */
	function formatoColumna2($str)
	{
		$valor = $this->_formatoCadena($str,2);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"TIPO IDENTIFICACION DE LA VICTIMA\" ES OBLIGATORIO");
			return false;
		}
		if(!$this->regla2($valor))
		{
			$this->setErrorRegla("EL CAMPO \"TIPO IDENTIFICACION DE LA VICTIMA\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna2

	/**
	 * NUMERO IDENTIFICACION DE LA VICTIMA (20)
	 * @param string str
	 */
	function formatoColumna3($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"NUMERO IDENTIFICACION DE LA VICTIMA\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna3
	
	/**
	 * FECHA DE LA ATENCION (DD/MM/AAAA) (10)
	 * @param date fecha
	 */
	function formatoColumna4($fecha)
	{
		$valor = $this->_formatoFecha($fecha);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"FECHA DE LA ATENCION\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna4
	
	/**
	 * CODIGO DE LA CONSULTA  (8)
	 * @param string str
	 */
	function formatoColumna5($str)
	{
		$valor = $this->_formatoCadena($str,8);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"CODIGO DE LA CONSULTA\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna5
	
	/**
	 * DIAGNOSTICO PRINCIPAL DE INGRESO (4)
	 * @param string str
	 */
	function formatoColumna6($str)
	{
		$valor = $this->_formatoCadena($str,4);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"DIAGNOSTICO PRINCIPAL DE INGRESO\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna6
	
	/**
	 * DIAGNOSTICO PRINCIPAL DE EGRESO (4)
	 * @param string str
	 */
	function formatoColumna7($str)
	{
		$valor = $this->_formatoCadena($str,4);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"DIAGNOSTICO PRINCIPAL DE EGRESO\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna7
	
	/**
	 * VALOR DE LA CONSULTA  (15)
	 * @param int val
	 */
	function formatoColumna8($val)
	{
		$valor = $this->_formatoValor($val);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"VALOR DE LA CONSULTA\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna8
	
	/**
	 * CODIGO DEL PROCEDIMIENTO (8)
	 * @param string str
	 */
	function formatoColumna9($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!empty($valor))
			$this->procedimiento = true;
		else
			$this->procedimiento = false;
		return $valor;
	}//Fin formatoColumna9
	
	/**
	 * CANTIDAD DE PROCEDIMIENTOS (3)
	 * @param int val
	 */
	function formatoColumna10($val)
	{
		$valor = $this->_formatoValor($val,2);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"CANTIDAD DE PROCEDIMIENTOS\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna10
	
	/**
	 * VALOR DE LOS PROCEDIMIENTOS (15)
	 * @param int val
	 */
	function formatoColumna11($val)
	{
		$valor = $this->_formatoValor($val);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"VALOR DE LOS PROCEDIMIENTOS\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna11
	
	/**
	 * TIPO DE MEDICAMENTO (1)
	 * @param string str
	 */
	function formatoColumna12($str)
	{
		$valor = $this->_formatoCadena($str,1);
// 		if(!$this->regla3($valor))
// 		{
// 			$this->setErrorRegla("EL CAMPO \"TIPO DE MEDICAMENTO\" ES OBLIGATORIO");
// 			return false;
// 		}
		if(!empty($valor))
		{
			$this->tipoMedicamento = $valor;
			$this->medicamento = true;
		}
		else
		{
			$this->tipoMedicamento = 0;
			$this->medicamento = false;
		}
		return $valor;
	}//Fin formatoColumna12
	
	/**
	 * CODIGO DEL MEDICAMENTO NO POS  (20)
	 * @param string str
	 */
	function formatoColumna13($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!$this->regla5($valor))
		{
			$this->setErrorRegla("EL CAMPO \"CODIGO DEL MEDICAMENTO POS\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna13
	
	/**
	 * NOMBRE GENERICO DEL MEDICAMENTO (30)
	 * @param string str
	 */
	function formatoColumna14($str)
	{
		$valor = $this->_formatoCadena($str,30);
		if(!$this->regla6($valor))
		{
			$this->setErrorRegla("EL CAMPO \"NOMBRE GENERICO DEL MEDICAMENTO\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna14
	
	/**
	 * FORMA FARMACEUTICA (20)
	 * @param string str
	 */
	function formatoColumna15($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!$this->regla6($valor))
		{
			$this->setErrorRegla("EL CAMPO \"FORMA FARMACEUTICA\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna15
	
	/**
	 * CONCENTRACION DEL MEDICAMENTO (20)
	 * @param string str
	 */
	function formatoColumna16($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!$this->regla6($valor))
		{
			$this->setErrorRegla("EL CAMPO \"CONCENTRACION DEL MEDICAMENTO\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna16
	
	/**
	 * UNIDAD DE MEDIDA DEL MEDICAMENTO (20)
	 * @param string str
	 */
	function formatoColumna17($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!$this->regla6($valor))
		{
			$this->setErrorRegla("EL CAMPO \"UNIDAD DE MEDIDA DEL MEDICAMENTO\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna17
	
	/**
	 * CANTIDAD DE MEDICAMENTOS (3)
	 * @param int val
	 */
	function formatoColumna18($val)
	{
		$valor = $this->_formatoValor($val);
		if(!$this->regla5($valor))
		{
			$this->setErrorRegla("EL CAMPO \"CANTIDAD DE MEDICAMENTOS\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna18
	
	/**
	 * VALOR TOTAL DE LOS MEDICAMENTOS (15)
	 * @param int val
	 */
	function formatoColumna19($val)
	{
		$valor = $this->_formatoValor($val);
		if(!$this->regla5($valor))
		{
			$this->setErrorRegla("EL CAMPO \"VALOR TOTAL DE LOS MEDICAMENTOS\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna19
	
	/**
	 * TIPO DE SERVICIO (1)
	 * @param string str
	 */
	function formatoColumna20($str)
	{
		$valor = $this->_formatoCadena($str,1);
		if(!empty($valor))
		{
			if(!$this->regla7($valor))
			{
				$this->setErrorRegla("EL CAMPO \"TIPO DE SERVICIO\" TIENE UN VALO DIFERENTE DE LOS PERMITIDOS (1,2,3,4)");
				return false;
			}
			else
			{
				$this->tipoServicio = true;
			}
		}
		else
		{
			$this->tipoServicio = false;
		}
		return $valor;
	}//Fin formatoColumna20
	
	/**
	 * CANTIDAD  (3)
	 * @param int val
	 */
	function formatoColumna21($val)
	{
		$valor = $this->_formatoValor($val);
		if(!$this->regla8($valor))
		{
			$this->setErrorRegla("EL CAMPO \"CANTIDAD\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna21
	
	/**
	 * VALOR TOTAL DE LOS SERVICIOS (15)
	 * @param int val
	 */
	function formatoColumna22($val)
	{
		$valor = $this->_formatoValor($val);
		if(!$this->regla8($valor))
		{
			$this->setErrorRegla("EL CAMPO \"VALOR TOTAL DE LOS SERVICIOS\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * NUMERO DE LA FACTURA (20)
	 * @param string str
	 */
	function formatoColumna23($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"NUMERO DE LA FACTURA\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna23
	
	/**
	 * FECHA DE FACTURA (DD/MM/AAAA) (10)
	 * @param date fecha
	 */
	function formatoColumna24($fecha)
	{
		$valor = $this->_formatoFecha($fecha);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"FECHA DE FACTURA\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna24
	
	/**
	 * VALOR TOTAL DE LA FACTURA  (15)
	 * @param int val
	 */
	function formatoColumna25($val)
	{
		$valor = $this->_formatoValor($val);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"VALOR TOTAL DE LA FACTURA\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna25
	
	/**
	 * Regla 1
	 * campo obligatorio
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla1($val)
	{
		return  $this->_reglaCampoObligatorio($val);
	}//regla1

	/**
	 * Regla 2
	 * Campo Obligatorio
	 * CC = Cédula de ciudadanía
	 * CE= cédual de extrabjeria PA=Pasaporte
	 * RC=registro Civil
	 * TI=Tarjeta de identidad
	 * AS= Adulto sin identificar
	 * MS=Menor sin identificar 
	 * UN= Número único de identificación 
	 * Cuando la victima tenga como identificación el número de la historia clínica 
	 * se debe registrar como tipo de identificación AS o MS
	 *
	 * @param string val
	 * @return bool
	 */
	function regla2($val)
	{
		$valoresValidos = array("CC","PA","RC","TI","AS","MS","UN");
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}
	
	/**
	 * Regla 3
	 * 1 = POS
	 * 2 = NO POS
	 *
	 * @param string val
	 * @return bool
	 */
	function regla3($val)
	{
		$valoresValidos = array(1,2);
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}//Fin regla 3
	
	/**
	 * Regla 4
	 * Campo obligatorio si se aplica procedimiento
	 *
	 * @param string val
	 * @return bool
	 */
	function regla4($val)
	{
		if($this->procedimiento)
		{
			return $this->regla1($val);
		}
		return true;
	}//Fin regla4
	
	/**
	 * Regla 5
	 * Campo obligatorio si se suministra medicamento
	 *
	 * @param string val
	 * @return bool
	 */
	function regla5($val)
	{
		if($this->medicamento)
		{
			return $this->regla1($val);
		}
		return true;
	}//Fin regla5
	
	/**
	 * Regla 6
	 * Campo obligatorio para medicamento NO POS = 2
	 *
	 * @param string val
	 * @return bool
	 */
	function regla6($val)
	{
		if($this->tipoMedicamento == 2)
		{
			return $this->regla1($val);
		}
		return true;
	}//Fin regla6
	
	/**
	 * Regla 7
	 * 1 = Materiales e insumos
	 * 2 = Traslados
	 * 3 = Estancias
	 * 4 = Honorarios
	 *
	 * @param string val
	 * @return bool
	 */
	function regla7($val)
	{
		$valoresValidos = array(1,2,3,4);
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}//Fin regla7
	
	/**
	 * Campo obligatorio si hubo suministro de tipo de servicio
	 *
	 * @param string val
	 * @return bool
	 */
	function regla8($val)
	{
		if($this->tipoServicio)
		{
			$this->regla1($val);
		}
		return true;
	}//Fin regla8
}//Fin clase
?> 
