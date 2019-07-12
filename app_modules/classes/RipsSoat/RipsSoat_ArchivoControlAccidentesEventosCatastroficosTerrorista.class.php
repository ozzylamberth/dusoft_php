<?
/**
 * $Id: RipsSoat_ArchivoControlAccidentesEventosCatastroficosTerrorista.class.php,v 1.3 2006/09/19 20:38:59 carlos Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS-CLASSES
 * 
 * 1- ARCHIVO DE CONTROL DE ACCIDENTES O EVENTOS CATASTROFICOS Y TERRORISTAS.
 */

/**
 * Extiende de la superclase RipsSoat para generar el archivo Rips ForeCat resolucion 2056 - 2003
 * 1- ARCHIVO DE CONTROL DE ACCIDENTES O EVENTOS CATASTROFICOS Y TERRORISTAS.
 * Esta clase se encarga de instanciar las otras clases que generan los otros
 * archivos
 *
 * @author    Ehudes Garcia <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.3 $
 * @package   IPSOFT-SIIS-CLASSES
 */
IncludeClass("RipsSoat");


class RipsSoat_ArchivoControlAccidentesEventosCatastroficosTerrorista extends RipsSoat
{
    /**
     * Arreglo para almacenar los datos de la empresa
     * Array(   "razon_social"=>,
     *          "codigo_sgsss"=>,
     *          "nit"=>
     *     )
     * @var array
     */
    var $datos_empresa;
    
    /**
     * Directorio donde se crean los archivos del rips soat
     *
     * @var string
     */
    var $directorio;
    
	/**
	 * Constructor
	 */
	function RipsSoat_ArchivoControlAccidentesEventosCatastroficosTerrorista($envio_id,$path_rips_soat)
	{
		$this->RipsSoat($nombre,$envio_id);
        $this->setNombre("AC");
        //$this->directorio = $path_rips_soat."/".$this->nombre_directorio;
        $this->directorio = $path_rips_soat;
		$this->cargarDatos();
		$metodoFormatos = array(
				"formatoColumna1",//RAZON SOCIAL MAXIMO CARACTERES (60)
				"formatoColumna2",//CODIGO DEL PRESTADOR DE SERVICIOS (12)
				"formatoColumna3",//NIT  (20)
				"formatoColumna4",//FECHA INICIAL (DD/MM/AAAA)(10)
				"formatoColumna5",//FECHA FINAL (DD/MM/AAAA)(10)
				"formatoColumna6",//FECHA ENVIO (DD/MM/AAAA)(10)
				"formatoColumna7",//NOMBRE DEL ARCHIVO  (8)
				"formatoColumna8",//TOTAL REGISTROS (5)
				);
		$this->setMetodosFormatos($metodosFormatos);
	}//Fin Constructor
	
    /**
     * Carga los datos para armar el archivo de control
     */
    function cargarDatos()
    {
        $sql = "
            SELECT
                em.razon_social,
                em.codigo_sgsss,
                em.id,
                en.fecha_inicial,
                en.fecha_final,
                en.fecha_registro
            FROM
                envios en,
                departamentos d,
                empresas em
            WHERE
                en.departamento = d.departamento
                AND d.empresa_id = em.empresa_id
                AND en.envio_id =".$this->envio_id;
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
        $this->datos_empresa = $resultado->GetRows();
    }//Fin cargarDatos
    
    /**
     * Método para instanciar y ejecutar las otras clases de rips de SOAT
     *
     */
    function ejecutarClasesRips()
    {
        global $_ROOT;
        $clases_rips = array
        (
            "RipsSoat_ArchivoAccidentesEventosCatastroficosTerrorista",
            "RipsSoat_ArchivoSobreAtencionVictima",
            "RipsSoat_ArchivoSobreVehiculos"
        );
        foreach($clases_rips as $key=>$clase_rips)
        {
            include_once $_ROOT."classes/RipsSoat/".$RipsSoat[$key].".class.php";
            $RipsSoat[$key] = new $clase_rips($this->envio_id);
            if(!empty($RipsSoat[$key]->error))
            {
                $this->error = $RipsSoat[$key]->error;
                $this->mensajeDeError = $RipsSoat[$key]->mensajeDeError;
                $this->fileError = $RipsSoat[$key]->fileError;
                $this->lineError = $RipsSoat[$key]->lineError;
                return false;
            }
            if(!$RipsSoat[$key]->ejecutar())
            {
                $this->error = "Error Construyendo el Archivo Accidentes Eventos Catastroficos Terrorista";
                $this->mensajeDeError = $RipsSoat[$key]->getMensajeError();
                return false;
            }
            if(!empty($RipsSoat[$key]->contenido))
            {
                $finicial=explode(' ',$this->datos_empresa[0][3]);
                $finicial1=explode('-',$finicial[0]);
                $fechainicial=$finicial1[2].'/'.$finicial1[1].'/'.$finicial1[0];

                $ffinal=explode(' ',$this->datos_empresa[0][4]);
                $ffinal1=explode('-',$ffinal[0]);
                $fechafinal=$ffinal1[2].'/'.$ffinal1[1].'/'.$ffinal1[0];

                $fenvio=explode(' ',$this->datos_empresa[0][5]);
                $fenvio1=explode('-',$fenvio[0]);
                $fechaenvio=$fenvio1[2].'/'.$fenvio1[1].'/'.$fenvio1[0];

                $this->arrayContenido[$key]['razon_social']  = $this->datos_empresa[0][0];
                $this->arrayContenido[$key]['sgsss']  = $this->datos_empresa[0][1];
                $this->arrayContenido[$key]['nit']  = $this->datos_empresa[0][2];
                $this->arrayContenido[$key]['fecha_inicial']  = $fechainicial;
                $this->arrayContenido[$key]['fecha_final']  = $fechafinal;
                $this->arrayContenido[$key]['fecha_envio'] = $fechaenvio;
                $this->arrayContenido[$key]['nombre_archivo'] = $RipsSoat[$key]->nombre;
                $this->arrayContenido[$key]['total_registros'] = sizeof($RipsSoat[$key]->arrayContenido);
            }
            else
            {
                unset($RipsSoat[$key]);
            }
        }
        if(!$this->ejecutar())
        {
            $this->error = "Error Construyendo el Archivo Control Accidentes Eventos Catastroficos Terrorista";
            $this->mensajeDeError = $this->getMensajeError();
            return false;
        }
        //if(!$directorio=$this->crearDirectorio())
        if(!$this->crearDirectorio())
        {
            $this->error = "Error Construyendo el Archivo Control Accidentes Eventos Catastroficos Terrorista";
            $this->mensajeDeError = "No se pudo crear el directorio ".$this->directorio;
            return false;
        }
        //if(!$this->guardar($directorio,$RipsSoat[$key]->nombre))
        if(!$this->guardar($this->directorio))
        {
            $this->mensajeDeError = "No hay contenido para guardar ";
            return false;
        }
        foreach( $RipsSoat as $RipSoat)
        {
            if(!$RipSoat->guardar($this->directorio))
            {
                $this->error = $RipSoat->error;
                $this->mensajeDeError = $RipSoat->mensajeDeError;
                return false;
            }
        }
        return true;
    }//Fin ejecutarClasesRips
    
    /**
     * Crear el directrio para guardar los rips soat del envio
     */
    function crearDirectorio()
    {
        if(!is_dir($this->directorio))
           return mkdir($this->directorio);
        return true;
    }//Fin crearDirectorio
    
	/**
	 * RAZON SOCIAL MAXIMO CARACTERES (60)
	 * @param string str
	 */
	function formatoColumna1($str)
	{
		$valor = $this->_formatoCadena($str,60);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"RAZON SOCIAL MAXIMO CARACTERES\" ES OBLIGATORIO");
			return false;
		}
	}//Fin formatoColumna1
	
	/**
	 * CODIGO DEL PRESTADOR DE SERVICIOS (12)
	 * @param string str
	 */
	function formatoColumna2($str)
	{
		$valor = $this->_formatoCadena($str,12);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"CODIGO DEL PRESTADOR DE SERVICIOS\" ES OBLIGATORIO");
			return false;
		}
	}//Fin formatoColumna2

	/**
	 * NIT  (20)
	 * @param string str
	 */
	function formatoColumna3($str)
	{
		$valor = $this->_formatoCadena($str,20);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"NIT\" ES OBLIGATORIO");
			return false;
		}
		return $valor;
	}//Fin formatoColumna3
	
	/**
	 * FECHA INICIAL (DD/MM/AAAA)(10)
	 * @param date fecha
	 */
	function formatoColumna4($fecha)
	{
		$valor = $this->_formatoFecha($fecha);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"FECHA INICIAL\" ES OBLIGATORIO");
			return false;
		}
	}//Fin formatoColumna4
	
	/**
	 * FECHA FINAL (DD/MM/AAAA)(10)
	 * @param string str
	 */
	function formatoColumna5($fecha)
	{
		$valor = $this->_formatoFecha($fecha);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"FECHA FINAL\" ES OBLIGATORIO");
			return false;
		}
	}//Fin formatoColumna5
	
	/**
	 * FECHA ENVIO (DD/MM/AAAA)(10)
	 * @param string str
	 */
	function formatoColumna6($fecha)
	{
		$valor = $this->_formatoFecha($fecha);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"FECHA ENVIO\" ES OBLIGATORIO");
			return false;
		}
	}//Fin formatoColumna6
	
	/**
	 * NOMBRE DEL ARCHIVO  (8)
	 * @param string str
	 */
	function formatoColumna7($str)
	{
		$valor = $this->_formatoCadena($str,8);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"NOMBRE DEL ARCHIVO\" ES OBLIGATORIO");
			return false;
		}
	}//Fin formatoColumna7
	
	/**
	 * TOTAL REGISTROS (5)
	 * @param int val
	 */
	function formatoColumna8($val)
	{
		$valor = $this->_formatoValor($val);
		if(!$this->regla1($valor))
		{
			$this->setErrorRegla("EL CAMPO \"TOTAL REGISTROS\" ES OBLIGATORIO");
			return false;
		}
	}//Fin formatoColumna8
	
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
}
?>