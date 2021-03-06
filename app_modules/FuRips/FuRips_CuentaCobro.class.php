<?
/**
 * $Id: $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS-CLASSES
 * 
 * 1- ARCHIVO DE ACCIDENTES O EVENTOS CATASTROFICOS Y TERRORISTAS.
 */

/**
 * Extiende de la superclase Csv para generar el archivo Rips ForeCat resolucion 2056 - 2003
 * 1- ARCHIVO DE ACCIDENTES O EVENTOS CATASTROFICOS Y TERRORISTAS.
 *
 * @author    Ehudes Garcia <efgarcia@ipsoft-sa.com>
 * @version   $Revision: $
 * @package   IPSOFT-SIIS-CLASSES
 */

class FuRips_CuentaCobro extends FuRips
{
    /**
     * Naturaleza cel evento del accidente o evento catastrofio/terrorista
     *
     * @var string
     */
	var $naturalezaEvento;
    
    /**
     * Directorio donde se crean los archivos del rips soat
     *
     * @var string
     */
    var $directorio;
	
	/**
	 * Constructor
	 */
	function FuRips_CuentaCobro($envio_id,$rutaRips,$sgsss)
	{
		//$this->RipsSoat($nombre,$envio_id);
		$this->FuRips($nombre,$envio_id);
		$this->directorio = $rutaRips;
		$this->setNombre("FURIPS2".$sgsss.date('dmY'));
		$this->cargarDatos();
		//print_r($this->arrayContenido2); exit;
		$metodosFormatos = array(
				"formatoColumna1",//N�mero factura  o  N�mero de cuenta de cobro.(20)
				"formatoColumna2",//N�mero consecutivo de la reclamaci�n.(12)
				"formatoColumna3",//C�digo servicio (10)
				"formatoColumna4",//Cantidad servicios. (3)
				"formatoColumna5",//Valor unitario. (15)
				"formatoColumna6",//Valor total facturado(15)
				"formatoColumna7"//Valor total reclamado al Fosyga(15)
				);
		$this->setMetodosFormatos($metodosFormatos);
	}//Fin Constructor
	
	/**
	 * Carga los datos del archivo Csv ejecutando la consulta sql
	 *
	 */
	function cargarDatos()
	{
	//echo "<pre>";
		//Cuentas
/*		$sql = "
		SELECT 
			FFC.prefijo||FFC.factura_fiscal AS numero_factura,
			".$this->envio_id." AS numero_consecutivo,
			CD.cargo_cups,
			CD.cantidad,
			CD.precio,
			CD.valor_cargo,
			CD.valor_cubierto
			
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
			envios_detalle AS ED,
			hc_evoluciones AS HC,
			profesionales_usuarios AS PU,
			profesionales AS P,
			ingresos AS I
		
		WHERE 
			ISO.ingreso = C.ingreso
			AND C.numerodecuenta = CD.numerodecuenta
			AND CD.cargo_cups = CU.cargo
			AND CU.grupo_tipo_cargo = RTC.grupo_tipo_cargo
			AND CU.tipo_cargo = RTC.tipo_cargo
			--AND RTC.tipos_rips_soat_id = 'PRO'
			AND ISO.evento = SE.evento
			AND SE.empresa_id = E.empresa_id
			AND ISO.ingreso = I.ingreso
			AND FFC.prefijo = FF.prefijo
			AND FFC.factura_fiscal = FF.factura_fiscal
			AND FFC.empresa_id = FF.empresa_id
			AND ISO.ingreso = C.ingreso
			AND C.numerodecuenta = FFC.numerodecuenta
			AND FFC.prefijo = ED.prefijo
			AND FFC.factura_fiscal = ED.factura_fiscal
			AND FFC.empresa_id = ED.empresa_id
			AND HC.ingreso = ISO.ingreso
			AND HC.usuario_id=PU.usuario_id
			AND PU.tipo_tercero_id = P.tipo_id_tercero 
			AND PU.tercero_id = P.tercero_id
			AND ED.envio_id = ".$this->envio_id." 
			--LIMIT 1
			";*/
		$sql = "SELECT 
				FFC.prefijo||FFC.factura_fiscal AS numero_factura,
				".$this->envio_id." AS numero_consecutivo,
				CD.cargo_cups,
				CD.cantidad,
				CD.precio,
				CD.valor_cargo,
				CD.valor_cubierto
				
			FROM 
	
				cuentas AS C,
				cuentas_detalle AS CD,
				cups AS CU,
				fac_facturas_cuentas AS FFC,
				envios_detalle AS ED
			
			WHERE 
				C.numerodecuenta = CD.numerodecuenta
				AND CD.cargo_cups = CU.cargo
	
				AND C.numerodecuenta = FFC.numerodecuenta
				AND FFC.prefijo = ED.prefijo
				AND FFC.factura_fiscal = ED.factura_fiscal
				AND FFC.empresa_id = ED.empresa_id
				AND ED.envio_id = ".$this->envio_id."";
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error en la consulta";
			$this->mensajeDeError = $sql." ".$dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;                                
			return false;
		}
		$this->arrayContenido2 = $resultado->GetRows();
		//FIN CUENTAS
		
		//echo 1; print_r($this->arrayContenido2); echo 2;exit;

	}//Fin cargarDatos
	
	/**
	 * N�mero factura  o  N�mero de cuenta de cobro.(20)
	 * @param string str
	 */
	function formatoColumna1($str)
	{
		$valor = $this->_formatoCadena($str,20);
		//if(!$this->regla1($valor))
		//{
		//	$this->setErrorRegla("EL CAMPO \"Código del prestador de servicios de salud\" ES OBLIGATORIO");
		//	return false;
		//}
		return $valor;
	}//Fin formatoColumna1
	
	/**
	 * N�mero consecutivo de la reclamaci�n.(12)
	 * @param string str
	 */
	function formatoColumna2($str)
	{
		$valor = $this->_formatoCadena($str,12);
		//if(!$this->regla1($valor))
		//{
		//	$this->setErrorRegla("EL CAMPO \"Tipo Identificaci�n de la v�ctima\" ES OBLIGATORIO");
		//	return false;
		//}
		//if(!$this->regla2($valor))
		//{
		//	$this->setErrorRegla("EL CAMPO \"Tipo Identificaci�n de la v�ctima\" TIENE UN VALOR DIFERENTE DE (CC,PA,RC,TI,AS,MS,UN)");
		//	return false;
		//}
		return $valor;
	}//Fin formatoColumna2
	
	/**
	 * C�digo servicio (10)
	 * @param string str
	 */
	function formatoColumna3($str)
	{
		$valor = $this->_formatoCadena($str,10);
/*		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"Número factura  o  Número de cuenta de cobro.\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna3
	
	/**
	 * Cantidad servicios. (3)
	 * @param string str
	 */
	function formatoColumna4($cantidad)
	{
		$valor = $this->_formatoValor($cantidad);
/*		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"Número consecutivo de la reclamación\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna4
	
	/**
	 * Valor unitario. (15)
	 * @param string str
	 */
	function formatoColumna5($str)
	{
		$valor = $this->_formatoCadena($str,15);
		return $valor;
	}//Fin formatoColumna5
	
	/**
	 * Valor total facturado(15)
	 * @param string str
	 */
	function formatoColumna6($str)
	{
		$valor = $this->_formatoCadena($str,15);
/*		if(!$this->regla1($valor))
		{
			echo $this->setErrorRegla("EL CAMPO \"PRIMER APELLIDO DE LA VICTIMA\" ES OBLIGATORIO");
			return false;
		}*/
		return $valor;
	}//Fin formatoColumna6
	
	/**
	 * Valor total reclamado al Fosyga(15)
	 * @param string str
	 */
	function formatoColumna7($str)
	{
		$valor = $this->_formatoCadena($str,15);
		return $valor;
	}//Fin formatoColumna7
	
	
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
	 * CC = C�dula de ciudadan�a
	 * CE= c�dual de extrabjeria PA=Pasaporte
	 * RC=registro Civil
	 * TI=Tarjeta de identidad
	 * AS= Adulto sin identificar
	 * MS=Menor sin identificar 
	 * UN= N�mero �nico de identificaci�n 
	 * Cuando la victima tenga como identificaci�n el n�mero de la historia cl�nica 
	 * se debe registrar como tipo de identificaci�n AS o MS
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
	 * Regla3
	 * 1= a�os
	 * 2= Meses
	 * 3= d�as.
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla3($val)
	{
		$valoresValidos = array(1,2,3);
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}//Fin regla3
	
	/**
	 * Regla4
	 * Campo obligatorio
	 * M= Masculino
	 * F= Femenino
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla4($val)
	{
		$valoresValidos = array('M','F');
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}//Fin regla3
	
	/**
	 * Campo Obligatorio.
	 * 01= Accidente de tr�nsito
	 * 02= Sismo
	 * 03= Maremoto 
	 * 04= Erupci�n volcanica
	 * 05= Deslizamiento Tierra
	 * 06= Inundaci�n
	 * 07= Avalancha
	 * 08= Incendio Natural
	 * 09= Explosi�n terrorista
	 * 10= Incendio Terrorista
	 * 11= combate
	 * 12= Toma Guerrillera
	 * 13= Masacre
	 * 14= Desplazados.
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla5($val)
	{
		$valoresValidos = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14");
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}//Fin regla5
	
	/**
	 * Campo Obligatorio.
	 * R = Rural
	 * U= Urbano
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla6($val)
	{
		$valoresValidos = array('R','U');
		return $this->_reglaValorEnArreglo($val,$valoresValidos);
	}//Fin regla6
	
	/**
	 * Obligatorio en caso de accidente de transito.
	 *
	 * @param mixed val
	 * @return bool
	 */
	function regla7($val)
	{
		if($this->naturalezaEvento == "01")//01=>Accidente de tr�nsito
		{
			return $this->regla1($val);
		}
		return true;
	}//Fin regla7
}//Fin clase
?>