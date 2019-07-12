<?
/**
 * $Id: FuRips_CuentaCobro.class.php,v 1.2 2009/05/14 21:40:32 hugo Exp $
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
 * @version   $Revision: 1.2 $
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

		$this->FuRips($nombre,$envio_id);
		$this->directorio = $rutaRips;
		$this->setNombre("FURIPS2".$sgsss.date('dmY'));
		$this->cargarDatos();

		$metodosFormatos = array(
				"formatoColumna1",//N�mero factura  o  N�mero de cuenta de cobro.(20)
				"formatoColumna2",//N�mero consecutivo de la reclamaci�n.(12)
				"formatoColumna3",//Tipo de servicio (1)
				"formatoColumna4",//C�digo servicio (10)
				"formatoColumna5",//Descripcion del insumo. (5)
				"formatoColumna6",//Cantidad servicios. (3)
				"formatoColumna7",//Valor unitario. (15)
				"formatoColumna8",//Valor total facturado(15)
				"formatoColumna9"//Valor total reclamado al Fosyga(15)
				);
		$this->setMetodosFormatos($metodosFormatos);
	}//Fin Constructor
	
	/**
	 * Carga los datos del archivo Csv ejecutando la consulta sql
	 *
	 */
	function cargarDatos()
	{
    $sql  = "SELECT numero_factura, ";
		$sql .= "		    ".$this->envio_id." AS numero_consecutivo,";
		$sql .= "		    tipo_servicio,";
		$sql .= "		    codigo_cargo,";
		$sql .= "		    descripcion,";
		$sql .= "		    SUM(cantidad) AS cantidad,";
		$sql .= "		    SUM(precio) AS precio,";
		$sql .= "		    SUM(valor_cargo) AS valor_cargo,";
		$sql .= "		    SUM(valor_cubierto) AS valor_cubierto ";
    $sql .= "FROM   (";
		$sql .= "		      SELECT  FFC.prefijo||FFC.factura_fiscal AS numero_factura,";
		$sql .= "		              CASE WHEN CD.cargo_cups IS NULL AND CD.tarifario_id = 'SYS' AND (CD.cargo = 'IMD' OR CD.cargo = 'DIMD') AND M.codigo_medicamento IS NOT NULL THEN 1 ";
		$sql .= "			                 WHEN CD.cargo_cups IS NOT NULL THEN 2";
		$sql .= "		                   WHEN CD.cargo_cups IS NULL AND CD.tarifario_id = 'SYS' AND (CD.cargo = 'IMD' OR CD.cargo = 'DIMD') AND M.codigo_medicamento ISNULL THEN 5 ";
    $sql .= "                 END AS tipo_servicio,";
		$sql .= "		              CASE WHEN CD.cargo_cups IS NULL AND CD.tarifario_id = 'SYS' AND CD.cargo = 'IMD' THEN M.codigo_cum ";
    $sql .= "                      WHEN CD.cargo_cups IS NOT NULL THEN CD.cargo ";
    $sql .= "                 END AS codigo_cargo,";
    $sql .= "                 CASE WHEN CD.cargo_cups IS NULL AND CD.tarifario_id = 'SYS' AND (CD.cargo = 'IMD' OR CD.cargo = 'DIMD') AND  M.codigo_medicamento ISNULL THEN I.descripcion ";
    $sql .= "                      ELSE NULL END AS descripcion,";
		$sql .= "		              CASE WHEN CD.cargo = 'DIMD' THEN CD.cantidad*(-1)";
		$sql .= "		                   ELSE CD.cantidad END AS cantidad,";
		$sql .= "		              CD.precio,";
		$sql .= "		              CD.valor_cargo,";
		$sql .= "		              CD.valor_cubierto,";
		$sql .= "		              BD.codigo_producto";
		$sql .= "		      FROM		fac_facturas_cuentas AS FFC,";
		$sql .= "		              envios_detalle AS ED,";
		$sql .= "		              cuentas AS C,";
		$sql .= "		              cuentas_detalle AS CD";
		$sql .= "		              LEFT JOIN bodegas_documentos_d BD";
		$sql .= "		              ON (CD.consecutivo = BD.consecutivo)";
		$sql .= "		              LEFT JOIN medicamentos M";
		$sql .= "		              ON (BD.codigo_producto = M.codigo_medicamento)";
		$sql .= "		              LEFT JOIN inventarios_productos I";
		$sql .= "		              ON (BD.codigo_producto = I.codigo_producto) ";
		$sql .= "         WHERE 	C.numerodecuenta = CD.numerodecuenta ";
		$sql .= "         AND     C.numerodecuenta = FFC.numerodecuenta ";
		$sql .= "         AND     FFC.prefijo = ED.prefijo ";
		$sql .= "         AND     FFC.factura_fiscal = ED.factura_fiscal  ";
		$sql .= "         AND     FFC.empresa_id = ED.empresa_id ";
		$sql .= "         AND     CD.facturado = '1' ";
		$sql .= "         AND     CD.cargo NOT IN ( 'APROVCUOTA', 'DCTOREDON', 'APROVREDON', 'DESCUENTO', 'INC_CITA') ";
		$sql .= "         AND     ED.envio_id = ".$this->envio_id." ";
		$sql .= "		    ) A ";
    $sql .= "GROUP BY 1,2,3,4,5 ";
    $sql .= "HAVING SUM(cantidad) > 0 ";

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
		return $valor;
	}//Fin formatoColumna1
	
	/**
	 * N�mero consecutivo de la reclamaci�n.(12)
	 * @param string str
	 */
	function formatoColumna2($str)
	{
		$valor = $this->_formatoCadena($str,12);
		return $valor;
	}
  /**
	* Tipo de servicio (1)
  *
	* @param string str
	*/
	function formatoColumna3($str)
	{
		$valor = $this->_formatoCadena($str,1);
		return $valor;
	}
	/**
	* Código servicio (10)
	* @param string str
	*/
	function formatoColumna4($str)
	{
		$valor = $this->_formatoCadena($str,10);
		return $valor;
	}	
  /**
	* Descripcion del insumo (40)
	* @param string str
	*/
	function formatoColumna5($str)
	{
		$valor = $this->_formatoCadena($str,40);
		return $valor;
	}
	/**
  * Cantidad servicios. (3)
  * @param string str
  */
	function formatoColumna6($cantidad)
	{
		$valor = $this->_formatoValor($cantidad);
		return $valor;
	}
	/**
  * Valor unitario. (15)
  * @param string str
  */
	function formatoColumna7($str)
	{
		$valor = $this->_formatoNumero($str);
		return $valor;
	}
	/**
  * Valor total facturado(15)
  * @param string str
  */
	function formatoColumna8($str)
	{
		$valor = $this->_formatoNumero($str);
		return $valor;
	}
	/**
  * Valor total reclamado al Fosyga(15)
  * @param string str
  */
	function formatoColumna9($str)
	{
		$valor = $this->_formatoNumero($str);
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