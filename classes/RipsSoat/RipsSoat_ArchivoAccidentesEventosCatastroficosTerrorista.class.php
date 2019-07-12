<?
/**
 * $Id: RipsSoat_ArchivoAccidentesEventosCatastroficosTerrorista.class.php,v 1.1 2006/03/21 14:19:13 ehudes Exp $
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
 * @version   $Revision: 1.1 $
 * @package   IPSOFT-SIIS-CLASSES
 */

class RipsSoat_ArchivoAccidentesEventosCatastroficosTerrorista extends RipsSoat
{
    /**
     * Naturaleza cel evento del accidente o evento catastrofio/terrorista
     *
     * @var string
     */
	var $naturalezaEvento;
    
	/**
	 * Constructor
	 */
	function RipsSoat_ArchivoAccidentesEventosCatastroficosTerrorista($envio_id)
	{
        $this->RipsSoat($nombre,$envio_id);
        $this->setNombre("AA");
		$this->cargarDatos();
		$metodosFormatos = array(
				"formatoColumna1",//CODIGO DEL PRESTADOR DE SERVICIOS (12)
				"formatoColumna2",//TIPO IDENTIFICACION DE LA VICTIMA (2)
				"formatoColumna3",//NUMERO IDENTIFICACION DE LA VICTIMA (20)
				"formatoColumna4",//PRIMER APELLIDO VICTIMA (30)
				"formatoColumna5",//SEGUNDO APELLIDO VICTIMA (30)
				"formatoColumna6",//PRIMER NOMBRE DE LA VICTIMA (20)
				"formatoColumna7",//SEGUNDO NOMBRE DE LA VICTIMA (20)
				"formatoColumna8",//EDAD (3)
				"formatoColumna9",//UNIDAD DE EDAD (1)
				"formatoColumna10",//SEXO (1)
				"formatoColumna11",//DIRECCION RESIDENCIA DE LA VICTIMA (40)
				"formatoColumna12",//CODIGO DEPARTAMENTO DE RESIDENCIA DE LA VICTIMA (2)
				"formatoColumna13",//CODIGO MUNICIPIO DE RESIDENCIA DE LA VICTIMA (3)
				"formatoColumna14",//TELEFONO RESIDENCIA (9)
				"formatoColumna15",//NATURALEZA DEL EVENTO (2)
				"formatoColumna16",//SITIO O DIRECCION DE ACURRENCIA DEL EVENTO (60)
				"formatoColumna17",//FECHA DE ACURRENCIA DEL EVENTO (DD/MM/AAAA)(10)
				"formatoColumna18",//HORA DEL EVENTO  (HH:MM) (5)
				"formatoColumna19",//CODIGO DEPARTAMENTO DONDE OCURRIO EL EVENTO (2)
				"formatoColumna20",//CODIGO MUNICIPIO DONDE OCURRIO EL EVENTO (3)
				"formatoColumna21",//ZONA (1 )
				"formatoColumna22"//INFORME DEL EVENTO (255)
			);
		$this->setMetodosFormatos($metodosFormatos);
	}//Fin Constructor
	
	/**
	 * Carga los datos del archivo Csv ejecutando la consulta sql
	 *
	 */
	function cargarDatos()
	{
		$sql = "
			SELECT 
				E.codigo_sgsss,
				P.tipo_id_paciente,
				P.paciente_id,
				P.primer_apellido, 
				P.segundo_apellido,
				P.primer_nombre,
				P.segundo_nombre, 
				age(AC.fecha_registro,P.fecha_nacimiento) AS edad, 
				1 AS unidad_edad,
				P.sexo_id,
				P.residencia_direccion,
				P.tipo_dpto_id,
				P.tipo_mpio_id,
				P.residencia_telefono,
				'01' AS evento,
				AC.sitio_accidente,
				AC.fecha_accidente,
				AC.fecha_accidente as hora_accidente,
				AC.tipo_dpto_id AS dpto_accidente, 
				AC.tipo_mpio_id AS mpio_accidente,
				AC.zona,
				AC.informe_accidente
			FROM 
				ingresos_soat AS ISO,
				ingresos AS I,
				pacientes AS P,
				soat_eventos SE,
				empresas AS E,
				soat_accidente AS AC,
                
                cuentas AS C,
                fac_facturas_cuentas AS FFC,
                envios_detalle AS ED
			WHERE 
				ISO.ingreso = I.ingreso
				AND I.paciente_id = P.paciente_id
				AND I.tipo_id_paciente = P.tipo_id_paciente
				AND ISO.evento=SE.evento
				AND SE.empresa_id=E.empresa_id
				AND AC.accidente_id = SE.accidente_id
                
                AND ISO.ingreso = C.ingreso
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
			$this->setErrorRegla("EL CAMPO \"Código del prestador de servicios de salud\" ES OBLIGATORIO");
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
	 * PRIMER APELLIDO VICTIMA (30)
	 * @param string str
	 */
	function formatoColumna4($str)
	{
		$valor = $this->_formatoCadena($str,30);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"PRIMER APELLIDO VICTIMA\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna4
	
	/**
	 * SEGUNDO APELLIDO VICTIMA (30)
	 * @param string str
	 */
	function formatoColumna5($str)
	{
		$valor = $this->_formatoCadena($str,30);
		return $valor;
	}//Fin formatoColumna5
	
	/**
	 * PRIMER NOMBRE DE LA VICTIMA (20)
	 * @param string str
	 */
	function formatoColumna6($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"PRIMER NOMBRE DE LA VICTIMA\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna6
	
	/**
	 * SEGUNDO NOMBRE DE LA VICTIMA (20)
	 * @param string str
	 */
	function formatoColumna7($str)
	{
		$valor = $this->_formatoCadena($str,20);
		return $valor;
	}//Fin formatoColumna7
	
	/**
	 * EDAD (3)
	 * @param string edad
	 */
	function formatoColumna8($edad)
	{
		$edad1 = explode(" ",$edad);
		$valor = $this->_formatoCadena($edad1[0],3);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EDAD");
			return false;
		}
		return $valor;
	}//Fin formatoColumna8
	
	/**
	 * UNIDAD DE EDAD (1)
	 * @param string str
	 */
	function formatoColumna9($str)
	{
		$valor = $this->_formatoCadena($str,1);
		if(!$this->regla3($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Unidad Edad\" TIENE UN VALOR DIFERENTE DE (1,2,3)");
			return false;
		}
		return $valor;
	}//Fin formatoColumna9
	
	/**
	 * SEXO (1)
	 * @param string str
	 */
	function formatoColumna10($str)
	{
		$valor = $this->_formatoCadena($str,1);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Sexo\" ES OBLIGATORIO");
			return false;
		}
		if(!$this->regla4($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Sexo\" TIENE UN VALOR DIFERENTE DE (M,F)");
			return false;
		}
		return $valor;
	}//Fin formatoColumna10
	
	/**
	 * DIRECCION RESIDENCIA DE LA VICTIMA (40)
	 * @param string str
	 */
	function formatoColumna11($str)
	{
		$valor = $this->_formatoCadena($str,40);
		return $valor;
	}//Fin formatoColumna11
	
	/**
	 * CODIGO DEPARTAMENTO DE RESIDENCIA DE LA VICTIMA (2)
	 * @param string str
	 */
	function formatoColumna12($str)
	{
		$valor = $this->_formatoCadena($str,2);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento de residencia de la víctima\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna12
	
	/**
	 * CODIGO MUNICIPIO DE RESIDENCIA DE LA VICTIMA (3)
	 * @param string str
	 */
	function formatoColumna13($str)
	{
		$valor = $this->_formatoCadena($str,3);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código municipio de residencia de la víctima\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna13
	
	/**
	 * TELEFONO RESIDENCIA (9)
	 * @param string str
	 */
	function formatoColumna14($str)
	{
		$valor = $this->_formatoCadena($str,9);
		return $valor;
	}//Fin formatoColumna14
	
	/**
	 * NATURALEZA DEL EVENTO (2)
	 * @param string str
	 */
	function formatoColumna15($str)
	{
		$valor = $this->_formatoCadena($str,2);
		$this->naturalezaEvento = $valor;
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Naturaleza del Evento\" ES OBLIGATORIO");
			return false;
		}
		if(!$this->regla5($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Naturaleza del Evento\" DEBE TENER UN VALOR ENTRE 01 Y 14");
			return false;
		}
		return $valor;
	}//Fin formatoColumna15
	
	/**
	 * SITIO O DIRECCION DE ACURRENCIA DEL EVENTO (60)
	 * @param string str
	 */
	function formatoColumna16($str)
	{
		$valor = $this->_formatoCadena($str,60);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Sitio o dirección de acurrencia del evento\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna16
	
	/**
	 * FECHA DE ACURRENCIA DEL EVENTO (DD/MM/AAAA)(10)
	 *  @param date fecha
	 */
	function formatoColumna17($fecha)
	{
		$valor = $this->_formatoFecha($fecha);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Fecha de ocurrencia del evento\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna17
	
	/**
	 * HORA DEL EVENTO  (HH:MM) (5)
	 * @param date fecha
	 */
	function formatoColumna18($fecha)
	{
		$valor = $this->_formatoHora($fecha);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Hora del evento\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna18
	
	/**
	 * CODIGO DEPARTAMENTO DONDE OCURRIO EL EVENTO (2)
	 * @param string str
	 */
	function formatoColumna19($str)
	{
		$valor = $this->_formatoCadena($str,2);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código departamento donde ocurrio el evento\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna19
	
	/**
	 * CODIGO MUNICIPIO DONDE OCURRIO EL EVENTO (3)
	 * @param string str
	 */
	function formatoColumna20($str)
	{
		$valor = $this->_formatoCadena($str,3);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Código del municipio donde ocurrio el evento\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna20
	
	/**
	 * ZONA (1 )
	 *  @param string str
	 */
	function formatoColumna21($str)
	{
		$valor = $this->_formatoCadena($str,1);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Zona\" ES OBLIGATORIO");
			return false;
		}
		if(!$this->regla6($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Zona\" TIENE UN VALOR DIFERENTE DE (R y U)");
			return false;
		}
		return $valor;
	}//Fin formatoColumna21
	
	/**
	 * INFORME DEL EVENTO (255)
	 *  @param string str
	 */
	function formatoColumna22($str)
	{
		$valor = $this->_formatoCadena($str,255);
		if(!$this->regla7($valor))
		{
			$this->setErrorRegla("EL CAMPO \"Informe del evento\" YA QUE LA NATURALEZA DEL EVENTO ES UN ACCIDENTE DE TRANSITO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna22
	
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
	}//Fin regla2
	
	/**
	 * Regla3
	 * 1= años
	 * 2= Meses
	 * 3= días.
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
	 * 01= Accidente de tránsito
	 * 02= Sismo
	 * 03= Maremoto 
	 * 04= Erupción volcanica
	 * 05= Deslizamiento Tierra
	 * 06= Inundación
	 * 07= Avalancha
	 * 08= Incendio Natural
	 * 09= Explosión terrorista
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
		if($this->naturalezaEvento == "01")//01=>Accidente de tránsito
		{
			return $this->regla1($val);
		}
		return true;
	}//Fin regla7
}//Fin clase
?>