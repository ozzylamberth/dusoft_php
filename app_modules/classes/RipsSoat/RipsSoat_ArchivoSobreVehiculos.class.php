<?
/**
 * $Id: RipsSoat_ArchivoSobreVehiculos.class.php,v 1.2 2006/09/19 20:38:59 carlos Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS-CLASSES
 * 
 * 1- ARCHIVO SOBRE VEHICULOS
 */

/**
 * Extiende de la superclase Csv para generar el archivo Rips ForeCat resolucion 2056 - 2003
 * 1- ARCHIVO SOBRE VEHICULOS
 *
 * @author    Ehudes Garcia <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.2 $
 * @package   IPSOFT-SIIS-CLASSES
 */

class RipsSoat_ArchivoSobreVehiculos extends RipsSoat
{

	/**
	 * Estado del aseguramiento del vehiculo
	 */
	var $estadoAseguramientoVehiculo;
	
	/**
	 * Constructor
	 */
	function RipsSoat_ArchivoSobreVehiculos($envio_id)
	{
		$this->RipsSoat($nombre,$envio_id);
        $this->setNombre("VH");
		$this->cargarDatos();
		$metodosFormatos = array(
				"formatoColumna1",//CODIGO DEL PRESTADOR DE SERVICIOS (12)
				"formatoColumna2",//TIPO IDENTIFICACION DE LA VICTIMA (2)
				"formatoColumna3",//NUMERO IDENTIFICACION DE LA VICTIMA (20)
				"formatoColumna4",//ESTADO ASEGURAMIENTO DEL VEHICULO (1)
				"formatoColumna5",//MARCA (15)
				"formatoColumna6",//PLACA  (6)
				"formatoColumna7",//CLASE (2)
				"formatoColumna8",//NOMBRE DE LA ASEGURADORA (40)
				"formatoColumna9",//POLIZA SOAT NUMERO  (20)
				"formatoColumna10",//FECHA INICIO VIGENCIA POLIZA (DD/MM/AAAA)(10)
				"formatoColumna11",//FECHA DE FIN DE LA VIGENCIA POLIZA (DD/MM/AAAA)(10)
				"formatoColumna12",//PRIMER APELLIDO PROPIETARIO (30)
				"formatoColumna13",//SEGUNDO APELLIDO PROPIETARIO (30)
				"formatoColumna14",//PRIMER NOMBRE PROPIETARIO (20)
				"formatoColumna15",//SEGUNDO NOMBRE PROPIETARIO (20)
				"formatoColumna16",//TIPO IDENTIFICACION DEL PROPIETARIO (2)
				"formatoColumna17",//NUMERO IDENTIFICACION DEL PROPIETARIO (20)
				"formatoColumna18",//CODIGO DEPARTAMENTO DE RESIDENCIA DEL PROPIETARIO (2)
				"formatoColumna19",//CODIGO MUNICIPIO DE RESIDENCIA DEL PROPIETARIO (3)
				"formatoColumna20",//DIRECCION DEL PROPIETARIO (40)
				"formatoColumna21",//TELEFONO DEL PROPIETARIO (9)
				"formatoColumna22",//PRIMER APELLIDO CONDUCTOR (30)
				"formatoColumna23",//SEGUNDO APELLIDO CONDUCTOR (30)
				"formatoColumna24",//PRIMER NOMBRE CONDUCTOR (20)
				"formatoColumna25",//SEGUNDO NOMBRE CONDUCTOR (20)
				"formatoColumna26",//TIPO IDENTIFICACION DEL CONDUCTOR (2)
				"formatoColumna27",//NUMERO IDENTIFICACION DEL CONDUCTOR (20)
				"formatoColumna28",//CODIGO DEPARTAMENTO DE RESIDENCIA DEL CONDUCTOR (2)
				"formatoColumna29",//CODIGO MUNICIPIO DE RESIDENCIA DEL CONDUCTOR (3)
				"formatoColumna30",//DIRECCION DEL CONDUCTOR (40)
				"formatoColumna31"//TELEFONO DEL CONDUCTOR (9)
			);
		$this->setMetodosFormatos($metodosFormatos);
	}//Fin constructor
	
	/**
	 * Carga los datos del archivo Csv ejecutando la consulta sql
	 *
	 */
	function cargarDatos()
	{
		$sql = "
			SELECT 
				EM.codigo_sgsss,
				G.tipo_id_paciente,
				G.paciente_id,
				G.asegurado,
				H.marca_vehiculo,
				H.placa_vehiculo,
				H.tipo_vehiculo,
				I.nombre_tercero,
				H.poliza,
				H.vigencia_desde,
				H.vigencia_hasta,
				
				J.apellidos_propietario as primer_apellido_propietario,
				J.apellidos_propietario as segundo_apellido_propietario,
				J.nombres_propietario as primer_nombre_propietario,
				J.nombres_propietario as segundo_nombre_propietario,
				J.tipo_id_propietario,
				J.propietario_id,
				J.tipo_dpto_id AS dpto_propietario,
				J.tipo_mpio_id AS mpio_propietario,
				J.direccion_propietario,
				J.telefono_propietario,
				
				K.apellidos_conductor as primer_apellido_conductor,
				K.apellidos_conductor as segundo_apellido_conductor,
				K.nombres_conductor as primer_nombre_conductor,
				K.nombres_conductor as segundo_nombre_conductor,
				K.tipo_id_conductor,
				K.conductor_id,
				K.tipo_dpto_id AS dpto_conductor,
				K.tipo_mpio_id AS mpio_conductor,
				K.direccion_conductor,
				K.telefono_conductor
			FROM 
				ingresos_soat AS A,
				ingresos AS B,
				soat_eventos AS G
				LEFT JOIN soat_vehiculo_propietario AS J ON (G.evento=J.evento)
				LEFT JOIN soat_vehiculo_conductor AS K ON (G.evento=K.evento),
				soat_polizas AS H,
				terceros AS I,
				empresas AS EM,
                
                cuentas AS C,
                fac_facturas_cuentas AS FFC,
                envios_detalle AS ED
                
			WHERE 
				G.empresa_id=EM.empresa_id
				AND A.ingreso=B.ingreso
				AND A.evento=G.evento
				AND G.poliza=H.poliza
				AND H.tipo_id_tercero=I.tipo_id_tercero
				AND H.tercero_id=I.tercero_id
                
                AND A.ingreso = C.ingreso
                AND C.numerodecuenta = FFC.numerodecuenta
                AND FFC.prefijo = ED.prefijo
                AND FFC.factura_fiscal = ED.factura_fiscal
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
		$this->arrayContenido = $resultado->GetRows();
	}//Fin cargarDatos
	 
	/**
	 * CODIGO DEL PRESTADOR DE SERVICIOS (12)
	 * @param string str
	 */
	function formatoColumna1($str)
	{
		$valor = $this->_formatoCadena($str,12);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código del prestador de servicios\" ES OBLIGATORIO");
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
			$this->setErrorRegla("EL CAMPO \"Tipo Identificación de la víctima\" ES OBLIGATORIO");
			return false;
		}
		if(!$this->regla2($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Tipo Identificación de la víctima\" TIENE UN VALOR DIFERENTE DE (CC,PA,RC,TI,AS,MS,UN)");
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
			$this->setErrorRegla("EL CAMPO \"Número Identificación de la víctima\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna3
	
	/**
	 * ESTADO ASEGURAMIENTO DEL VEHICULO (1)
	 * @param string str
	 */
	function formatoColumna4($str)
	{
		$valor = $this->_formatoCadena($str,1);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Estado Aseguramiento del Vehículo\" ES OBLIGATORIO");
			return false;
		}
		if(!$this->regla3($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Estado Aseguramiento del Vehículo\" TIENE UN VALOR DIFERENTE DE (1,2,3,4,5)");
			return false;
		}
		return $valor;
	}//Fin formatoColumna4
	
	/**
	 * MARCA (15)
	 * @param string str
	 */
	function formatoColumna5($str)
	{
		$valor = $this->_formatoCadena($str,15);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Marca\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna5
	
	/**
	 * PLACA  (6)
	 * @param string str
	 */
	function formatoColumna6($str)
	{
		$valor = $this->_formatoCadena($str,6);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Placa\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna6
	
	/**
	 * CLASE (2)
	 * @param string str
	 */
	function formatoColumna7($str)
	{
		$valor = $this->_formatoCadena($str,2);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Clase\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna7
	
	/**
	 * NOMBRE DE LA ASEGURADORA (40)
	 * @param string str
	 */
	function formatoColumna8($str)
	{
		$valor = $this->_formatoCadena($str,40);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Nombre de la aseguradora\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna8
	
	/**
	 * POLIZA SOAT NUMERO  (20)
	 * @param string str
	 */
	function formatoColumna9($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Póliza SOAT número\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna9
	
	/**
	 * FECHA INICIO VIGENCIA POLIZA (DD/MM/AAAA)(10)
	 * @param date fecha
	 */
	function formatoColumna10($fecha)
	{
		$valor = $this->_formatoFecha($fecha);
		if(!$this->regla6($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Fecha de Inicio vigencia poliza\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1");
			return false;
		}
		return $valor;
	}//Fin formatoColumna10
	
	/**
	 * FECHA DE FIN DE LA VIGENCIA POLIZA (DD/MM/AAAA)(10)
	 * @param date fecha
	 */
	function formatoColumna11($fecha)
	{
		$valor = $this->_formatoFecha($fecha);
		if(!$this->regla6($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Fecha de fin de la vigencia poliza\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1");
			return false;
		}
		return $valor;
	}//Fin formatoColumna11
	
	/**
	 * PRIMER APELLIDO PROPIETARIO (30)
	 * En la base de datos hay un solo campo apellidos_propietario
	 * por lo cual se divide y la primera posición se considera el primer apellido
	 * @param string str
	 */
	function formatoColumna12($str)
	{
		list($primerApellido) = explode(" ",$str);
		$valor = $this->_formatoCadena($primerApellido,30);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"PRIMER APELLIDO PROPIETARIO\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna12
	
	/**
	 * SEGUNDO APELLIDO PROPIETARIO (30)
	 * En la base de datos hay un solo campo apellidos_propietario
	 * por lo cual se divide y la segunda posición se considera el primer apellido
	 * @param string str
	 */
	function formatoColumna13($str)
	{
		list($a, $segundoApellido) = explode(" ",$str);
		$valor = $this->_formatoCadena($segundoApellido,30);
		return $valor;
	}//Fin formatoColumna13
	
	/**
	 * PRIMER NOMBRE PROPIETARIO (20)
	 * En la base de datos hay un solo campo nombres_propietario
	 * por lo cual se divide y la primea posición se considera el primer nombre
	 * @param string str
	 */
	function formatoColumna14($str)
	{
		list($primerNombre) = explode(" ",$str);
		$valor = $this->_formatoCadena($primerNombre,20);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"PRIMER NOMBRE DEL PROPIETARIO\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna14
	
	/**
	 * SEGUNDO NOMBRE PROPIETARIO (20)
	 * En la base de datos hay un solo campo nombres_propietario
	 * por lo cual se divide y la segunda posición se considera el segundo nombre
	 * @param string str
	 */
	function formatoColumna15($str)
	{
		list($a, $segundoNombre) = explode(" ",$str);
		$valor = $this->_formatoCadena($segundoNombre,20);
		return $valor;
	}//Fin formatoColumna15
	
	/**
	 * TIPO IDENTIFICACION DEL PROPIETARIO (2)
	 * @param string str
	 */
	function formatoColumna16($str)
	{
		$valor = $this->_formatoCadena($str,2);
		if(!$this->regla5($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Tipo documento de Identificación del propietario\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna16
	
	/**
	 * NUMERO IDENTIFICACION DEL PROPIETARIO (20)
	 * @param string str
	 */
	function formatoColumna17($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Número documento de Identificación del propietario\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna17
	
	/**
	 * CODIGO DEPARTAMENTO DE RESIDENCIA DEL PROPIETARIO (2)
	 * @param string str
	 */
	function formatoColumna18($str)
	{
		$valor = $this->_formatoCadena($str,2);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento de residencia del propietario\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna18
	
	/**
	 * CODIGO MUNICIPIO DE RESIDENCIA DEL PROPIETARIO (3)
	 * @param string str
	 */
	function formatoColumna19($str)
	{
		$valor = $this->_formatoCadena($str,3);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Municipio de residencia del propietario\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna19
	
	/**
	 * DIRECCION DEL PROPIETARIO (40)
	 * @param string str
	 */
	function formatoColumna20($str)
	{
		$valor = $this->_formatoCadena($str,40);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Dirección del propietario\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna20
	
	/**
	 * TELEFONO DEL PROPIETARIO (9)
	 * @param string str
	 */
	function formatoColumna21($str)
	{
		$valor = $this->_formatoCadena($str,9);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Teléfono del propietario\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna21
	
	/**
	 * PRIMER APELLIDO CONDUCTOR (30)
	 * En la base de datos hay un solo campo apellidos_conductor
	 * por lo cual se divide y la primera posición se considera el primer apellido
	 * @param string str
	 */
	function formatoColumna22($str)
	{
		list($primerApellido) = explode(" ",$str);
		$valor = $this->_formatoCadena($primerApellido,30);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"PRIMER APELLIDO CONDUCTOR\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna22
	
	/**
	 * SEGUNDO APELLIDO CONDUCTOR (30)
	 * En la base de datos hay un solo campo apellidos_conductor
	 * por lo cual se divide y la segunda posición se considera el segundo apellido
	 * @param string str
	 */
	function formatoColumna23($str)
	{
		list($a,$segundoApellido) = explode(" ",$str);
		$valor = $this->_formatoCadena($segundoApellido,30);
		return $valor;
	}//Fin formatoColumna23
	
	/**
	 * PRIMER NOMBRE CONDUCTOR (20)
	 * En la base de datos hay un solo campo nombres_conductor
	 * por lo cual se divide y la primera posición se considera el primer nombre
	 * @param string str
	 */
	function formatoColumna24($str)
	{
		list($primerNombre) = explode(" ",$str);
		$valor = $this->_formatoCadena($primerNombre,20);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"PRIMER NOMBRE DE LA CONDUCTOR\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna24
	
	/**
	 * SEGUNDO NOMBRE CONDUCTOR (20)
	 * En la base de datos hay un solo campo nombres_conductor
	 * por lo cual se divide y la segunda posición se considera el segundo nombre
	 * @param string str
	 */
	function formatoColumna25($str)
	{
		list($a,$segundoNombre) = explode(" ",$str);
		$valor = $this->_formatoCadena($segundoNombre,20);
		return $valor;
	}//Fin formatoColumna25
	
	/**
	 * TIPO IDENTIFICACION DEL CONDUCTOR (2)
	 * @param string str
	 */
	function formatoColumna26($str)
	{
		$valor = $this->_formatoCadena($str,2);
		if(!$this->regla5($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Tipo documento de Identificación del CONDUCTOR\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna26
	
	/**
	 * NUMERO IDENTIFICACION DEL CONDUCTOR (20)
	 * @param string str
	 */
	function formatoColumna27($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Número documento de Identificación del CONDUCTOR\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna27
	
	/**
	 * CODIGO DEPARTAMENTO DE RESIDENCIA DEL CONDUCTOR (2)
	 * @param string str
	 */
	function formatoColumna28($str)
	{
		$valor = $this->_formatoCadena($str,2);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento de residencia del CONDUCTOR\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna28
	
	/**
	 * CODIGO MUNICIPIO DE RESIDENCIA DEL CONDUCTOR (3)
	 * @param string str
	 */
	function formatoColumna29($str)
	{
		$valor = $this->_formatoCadena($str,3);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Municipio de residencia del CONDUCTOR\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna29
	
	/**
	 * DIRECCION DEL CONDUCTOR (40)
	 * @param date fecha
	 */
	function formatoColumna30($str)
	{
		$valor = $this->_formatoCadena($str,40);
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Dirección del CONDUCTOR\" ES OBLIGATORIO POR QUE EL CAMPO \"Estado Aseguramiento del vehiculo\" ESTÁ EN 1 Ó 2");
			return false;
		}
		return $valor;
	}//Fin formatoColumna30
	
	/**
	 * TELEFONO DEL CONDUCTOR (9)
	 * @param int val
	 */
	function formatoColumna31($str)
	{
		$valor = $this->_formatoCadena($str,9);
		return $valor;
	}//Fin formatoColumna31
	
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
	}//Fin regla1

	/**
	 * Regla 2
	 * Campo Obligatorio
	 * CC = Cédula de ciudadanía
	 * CE = Cédula de extrabjeria PA=Pasaporte
	 * RC = Registro Civil
	 * TI = Tarjeta de identidad
	 * AS = Adulto sin identificar
	 * MS = Menor sin identificar 
	 * UN = Número único de identificación 
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
	}//Fin regla2
	
	/**
	 * Regla 3
	 * Campo Obligatorio
	 * 1 = Asegurado
	 * 2 = No Asegurado
	 * 3 = Carro Fantasma
	 * 4 = Poliza falsa
	 * 5 = Póliza Vencida.
	 *
	 * @param string val
	 * @return bool
	 */
	function regla3($val)
	{
		$valoresValidos = array(1,2,3,4,5);
		$this->estadoAseguramientoVehiculo = $val;
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}//Fin regla3
	
	/**
	 * Regla 4
	 * Campo obligatorio si el estado del aseguramiento es 1 o 2
	 *
	 * @param string val
	 * @return bool
	 */
	function regla4($val)
	{
		//verifica que el estado del aseguramiento sea 1 o 2
		if(in_array($this->estadoAseguramientoVehiculo,array(1,2)))
			return $this->_reglaCampoObligatorio($val);
		return true;
	}//Fin regla4
	
	/**
	 * Regla 5
	 * Campo Obligatorio si el estado de aseguramiento del vehiculo es 1 o 2
	 * CC = Cédula de ciudadanía
	 * CE= cédual de extrabjeria PA=Pasaporte
	 * TI=Tarjeta de identidad
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla5($val)
	{
		//verifica que el estado del aseguramiento sea 1 o 2
		if(in_array($this->estadoAseguramientoVehiculo,array(1,2)))
		{
			$valoresValidos = array("CC","CE","PA","TI","RC");
			return $this->_reglaValorEnArreglo($val,$valoresValidos);
		}
		return true;
	}//Fin regla5
	
	/**
	 * Regla 6
	 * Campo obligatorio si el estado del aseguramiento es 1
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla6($val)
	{
		//verifica que el estado del aseguramiento sea 1 o 2
		if($this->estadoAseguramientoVehiculo == 1)
			return $this->_reglaCampoObligatorio($val);
		return true;
	}//Fin regla6
}//Fin clase
?>