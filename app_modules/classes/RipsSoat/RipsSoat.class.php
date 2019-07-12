<?php
/**
 * $Id: RipsSoat.class.php,v 1.2 2006/09/19 20:38:59 carlos Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS-CLASSES
 * 
 * Clase para generar los rips de Soat
 */

/**
 * Clase padre para generar los rips de Soat
 * para cada archivo de rips de soat se debe
 * crear una clase que extienda de esta clase
 *
 * @author    Ehudes Garcia <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.2 $
 * @package   IPSOFT-SIIS-CLASSES
 */
class RipsSoat
{
	/**
	 * Separador
	 * 
	 * @var string
	 */
	var $s;
	
	/**
	 * Nueva linea
	 *
	 * @var string
	 */
	var $nl;
	
	/**
	 * Contenido del csv
	 *
	 * @var string
	 */
	var $contenido;
	
	/**
	 * Arreglo con los datos para armar el csv
	 * 
	 * @var array
	 */
	var $arrayContenido;
	
	/**
	 * Nombre del archivo csv
	 *
	 * @var string
	 */
	var $nombre;
	
	/**
	 * Arreglo que contiene la fila actual del proceso que armaContenido
	 *
	 * @var array
	 */
	var $filaActual;
	
	/**
	 * Indice de la fila actual
	 *
	 * @var mixed
	 */
	var $indiceFilaActual;
	
	/**
	 * Inidice de columna actual
	 *
	 * @var mixed
	 */
	var $indiceColumnaActual;
	
	/**
	 * Arreglo que contiene los métodos de la clase que dan formato a las 
	 * columnas del archivo, cuando se arma el contenido del csv cada columna
	 * debe tener referenciado un metodo que le de formato, estos metodos
	 * son propios de cada formato, reporte que extienda de esta clase
	 * Array(
	 * 	0 => formatoColumna1
	 * 	1 => formatoColumna2
	 * 	2 => formatoColumna3
	 * 	3 => formatoColumna4
	 * )
	 * pueda que un metodo sirva de formato para varias columnas, en este caso
	 * se referencia el mismo metodo en la posicion de la columna
	 *
	 * @var array
	 */
	var $metodosFormatos;
	
    /**
     * Envio al cual se le generaran los rips
     *
     * @var int
     */
    var $envio_id;
	
	/**
	 * Constructor
	 *
	 * @param string nombre
	 * @param array arrayContenido
	 * @param array metodosFormatos
	 */
	function RipsSoat($nombre,$envio_id,$arrayContenido,$metodosFormatos = array())
	{
		$this->s = ",";//por defecto el separador es la coma
		$this->nl = "\r\n";//salto de linea
		$this->contenido = "";
		$this->arrayContenido = $arrayContenido;
		$this->nombre = $nombre;
        $this->envio_id = $envio_id;
		$this->metodosFormatos = $metodosFormatos;
	}//Fin constructor
	
	/**
	 * Modifica el atributo metodosFormatos
	 * 
	 * @param array array
	 */
	function setMetodosFormatos($array)
	{
		$this->metodosFormatos =  $array;
	}//Fin setFuncioncesFormatos
	
	/**
	 * Retorna el contenido del atrituvo ln(nueva linea)
	 *
	 * @return string
	 */
	function  getNuevaLinea()
	{
		return $this->ln;//"\r\n";
	}//Fin getNuevaLinea

    /**
     * Fija el nombre del archivo
     */
    function setNombre($prefijo)
    {
        $this->nombre  =  $prefijo.str_pad($this->envio_id,6,"0",STR_PAD_LEFT);
    }
	/**
	 * Retorna el contenido del atributo s(separador)
	 *
	 * @return string
	 */
	function getSeparador()
	{
		return $this->s;
	}//Fin getSeparador
	
	/**
	 * Modifica el atributo s(separador)
	 * 
	 * @param string separador
	 */
	function setSeparador($separador)
	{
		$this->s = $separador;
	}//Fin setSeparador
	
	/**
	 * Función que escapa un string para que on tenga comas
	 *
	 * @param strin p_str
	 * @return string
	 */
	function escaparString( $p_str )
	{
		if ( strpos( $p_str, $this->s ) !== false )
		{
			$p_str = '"' . str_replace( '"', '""', $p_str ) . '"';
		}
		return $p_str;
	}//Fin escaparString
	
	/**
	 * Modifica el atributo arrayContenido
	 *
	 * @param array array
	 */
	function setArreglo($array)
	{
		$this->arrayContenido = $array;
	}//Fin setArreglo

	/**
	 * Arma el contenido del archivo csv con base en arrayContenido
	 */
	function armarContenido()
	{
		$primeraFila = true;
		$this->registroActual = 0;
		foreach($this->arrayContenido as $key=>$row)
		{
			$this->registroActual++;
			$this->indiceFilaActual = $key;
			//$this->filaActual = $row;
			if(!$primeraFila)
				$this->contenido .= $this->nl;
			$primeraFila = false;
			$primeraColumna = true;
			$i = 0;
			$this->filaActual = "";
			foreach($row as $columna=>$valor)
			{
				$this->indiceColumnaActual = $columna;
				if(!$primeraColumna)
					$this->filaActual .= $this->s;
				$primeraColumna = false;
				if(isset($this->metodosFormatos[$i]))
				{
					$metodoFormato = $this->metodosFormatos[$i];
				}
				else
				{
					$metodoFormato = "formatoDefault";
				}
				$valorConFormato = $this->$metodoFormato($valor);
				if($valorConFormato === FALSE)
					return false;
				//$this->contenido .= $valorConFormato;
				$this->filaActual .= $valorConFormato;
				$i++;
			}
			$this->contenido .= $this->filaActual;
		}
		return true;
	}//Fin armarContenido
	
	/**
	 * Llama a armarContenido
	 */
	function ejecutar()
	{
		return $this->armarContenido();
	}//Fin ejecutar
	
	/**
	 * Metodo default de formato, si no hay un metodo para determinada(s)
	 * columna(s) este metodoo es el encargado de formatearla.
	 * Este metodo puede(o debe) ser reescrito en la clase que extienda de esta
	 *
	 * @param string val
	 * @return string
	 */
	function formatoDefault($val)
	{
		return $this->escaparString($val);
	}//Fin formatoDefault
	
	/**
	 * Retorna el contenido del csv
	 *
	 * @return string
	 */
	function getContenido()
	{
		if(empty($this->contenido))
			$this->armarContenido();
		return $this->contenido;
	}//Fin getContenido
	
	/**
	 * Retorna el arreglo que arma el contendio de la fila Actual del proceso del armado de 
	 * archivo en el metodo "armarContenido"
	 *
	 * @return array
	 */
	function getFilaActual()
	{
		return $this->filaActual;
	}//Fin getFilaActual
	
	/**
	 * Retorna indice del arreglo que arma el contendio de la fila Actual del proceso del armado de 
	 * archivo en el metodo "armarContenido"
	 */
	function getIndiceFilaActual()
	{
		return $this->inidiceFilaActual;
	}//Fin getIndiceFilaActual
	
	/**
	 * Guarda el archivo en la ruta path
	 * 
	 * @param string path(ruta donde se guarda el archivo)
	 * @param string nombre(nombre del archivo)
	 * @param string ext(extension del archivo)
	 */
	function guardar($path,$nombre=null,$ext="txt")
	{
		if(empty($this->contenido))
		{
			$this->mensajeDeError = "No hay contenido que guardar";
			return false;
		}
		if($nombre)
			$nombre_archivo = $path."/$nombre".".$ext";
		else
			$nombre_archivo = $path.'/'.$this->nombre.".$ext";

		if (!$gestor = fopen($nombre_archivo, 'a'))
		{
			$this->error = "Error al guardar el archivo";
            $this->mensajeDeError = "No se puede abrir el archivo ($nombre_archivo)";
			echo $this->mensajeDeError;
			return false;
		}
		if (fwrite($gestor, $this->contenido) === FALSE) {
			$this->error = "Error al guardar el archivo";
            $this->mensajeDeError = "No se puede escribir en el archivo ($nombre_archivo)";
			echo $this->mensajeDeError;
			return false;
		}
		fclose($gestor);
		return true;
	}//Fin guardar
	
	/**
	 * Formatea una cadena y la deja sin caractares especiales 
	 * si los tiene, y si long es mayor que cero retorna la
	 * cadena con la cantidad de caracteres que indica long
	 * 
	 * @param string str
	 * @param int long (longitud maxima de la cadena)
	 * @return string
	 */
	function _formatoCadena($str,$long = 0)
	{
		$str = trim($str);
		if($str == "" || $str == null)
			return "";
		$reemplazar = array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ä","ë","ï","ö","ü","Ä","Ë","Ï","Ö","Ü");
		$por = array("a","e","i","o","u","A","E","I","O","U","a","e","i","o","u","A","E","I","O","U");
		//caracteres especiales
		$quitar = array('"',"/","º","ª","!","|","@","#","\$","%","&",
						"(",")","=","?","'","[","]","{","}","-","_","<",">","*",
						"-","+",',',".",":","^","\n","\r","\t");
		$str = str_replace($reemplazar, $por, $str);
		$str = str_replace($quitar,"",$str);
		if($long>0 && strlen($str)>0)
			$str = substr($str,0,$long);
		return $str;
	}//Fin _formatoCadena
	
	/**
	 * Formatea una fecha
	 * 
	 * @param date fecha
	 * @return date 
	 */
	function _formatoFecha($fecha)
	{
		return date('d/m/Y',strtotime($fecha));
	}//Fin _formatoFecha
	
	/**
	 * Formatea un valor numerico.
	 * si el parametro decimal es mayor que cero formatea el valor 
	 * con la cantidad  de decimales que indica "decimal" con "." como separador
	 *
	 * @param int val
	 * @param int decimal
	 * @return int
	 */
	function _formatoValor($val,$decimal = 0)
	{
		if($decimal > 0)
			return number_format($val, $decimal, '.', '');
		else
			return  number_format($val,0,'','');
	}//Fin _formatoValor
	
	/**
	 * Formatea una fecha a hora a HH:MM  
	 * HH(Horas) MM(Minutos)
	 * 
	 * @param date fecha
	 * @return date 
	 */
	function _formatoHora($fecha)
	{
		list($fecha,$hora) = explode(" ",$fecha);
		list($horas,$minuntos) = explode(":",$hora);
		return $horas.":".$minuntos;
		return date('H:i',strtotime($fecha));
	}//Fin _formatoFecha
	
	/**
	 * Verifica que $val no sea nulo, ni vacio, ni false, ni 0
	 *
	 * @param mixed val
	 * @return bool
	 */
	function _reglaCampoObligatorio($val)
	{
		if(empty($val))
			return false;
		return true;
		//return !empty($val);
	}//Fin _reglaCampoObligatorio
	
	/**
	 * Verifica que $val se encuentre en $array
	 *
	 * @param mixed val
	 * @param array array
	 * @return bool
	 */
	function _reglaValorEnArreglo($val,$valoresValidos)
	{
		return in_array($val,$valoresValidos);
	}//Fin _reglaValorEnArreglo
	
	/**
	 * Retorna el error ocurrido cuando se estaba construyendo el archivo 
	 */
	function getMensajeError()
	{
		$msj = "Error Registro No. ".$this->registroActual.": <br>";
		//$msj .= str_replace(",","|",$this->filaActual);
        $msj .= $this->filaActual;
		$msj .= "<br><b>".$this->errorRegla."</b>";
		return $msj;
	}
	
	/**
	 * Cuando una regla de validación de una columna se ejecuta
	 * retorna true o false, se acuerdo a esto de donde se llame
	 * la regla debe usar esta función para indicar el error que ocurrió
	 *
	 * @param strin errror
	 */
	function setErrorRegla($error)
	{
		$this->errorRegla = $error;
	}//Fin setErrorRegla
}//Fin clase
?>
