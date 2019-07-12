<?php
/**
 * $Id:  $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS-CLASSES
 * 
 * Clase para generar los furips
 */

/**
 * Clase padre para generar los rips de Soat
 * para cada archivo de rips de soat se debe
 * crear una clase que extienda de esta clase
 *
 * @author    Ehudes Garcia <efgarcia@ipsoft-sa.com>
 * @version   $Revision: $
 * @package   IPSOFT-SIIS-CLASSES
 */
class FuRips
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
	
	var $arrayContenido2;
	/**
	 * Arreglo que contiene los m�todos de la clase que dan formato a las 
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
	var $metodosFormatos2;
	
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
	function FuRips($nombre,$envio_id,$arrayContenido,$metodosFormatos = array())
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
        //$this->nombre  =  $prefijo.str_pad($this->envio_id,6,"0",STR_PAD_LEFT);
	$this->nombre  =  $prefijo;
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
	 * Funci�n que escapa un string para que on tenga comas
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
		//print_r($this->metodosFormatos); exit;
		
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
				{
				//echo $valorConFormato.'--'.$metodoFormato.'--'.$valor; exit;
					return false;
				}
				//$this->contenido .= $valorConFormato;
				$this->filaActual .= $valorConFormato;
				$i++;
			}
			$this->contenido .= $this->filaActual;
		}		
	//echo $this->contenido."<BR><BR><BR>";
		return true;
	}//Fin armarContenido
	
	/**
	 * Arma el contenido del archivo csv con base en arrayContenido
	 */
	function armarContenido2()
	{
		$primeraFila = true;
		$this->registroActual = 0;
//print_r($this->metodosFormatos); exit;
//echo 1;print_r($this->arrayContenido2); echo 2; exit;
		foreach($this->arrayContenido2 as $key=>$row)
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
				{
				//echo $valorConFormato.'--'.$metodoFormato.'--'.$valor; exit;
					return false;
				}
				//$this->contenido .= $valorConFormato;
				$this->filaActual .= $valorConFormato;
				$i++;
			}
			$this->contenido .= $this->filaActual;
		}		
	//echo $this->contenido."<BR><BR><BR>";
	
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
	 * Llama a armarContenido
	 */
	function ejecutar2()
	{
		return $this->armarContenido2();
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
	echo $this->nombre.".".$ext."<br>";
		if(empty($this->contenido))
		{
			$this->mensajeDeError = "No hay contenido que guardar";
			return false;
		}
		if($nombre)
			$nombre_archivo = $path."/$nombre".".$ext";
		else
			$nombre_archivo = $path.'/'.$this->nombre.".$ext";

		if (!$gestor = fopen($nombre_archivo, 'w+'))//'a'
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
		$reemplazar = array("�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�");
		$por = array("a","e","i","o","u","A","E","I","O","U","a","e","i","o","u","A","E","I","O","U");
		//caracteres especiales
		$quitar = array('"',"/","�","�","!","|","@","#","\$","%","&",
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
	 * Cuando una regla de validaci�n de una columna se ejecuta
	 * retorna true o false, se acuerdo a esto de donde se llame
	 * la regla debe usar esta funci�n para indicar el error que ocurri�
	 *
	 * @param strin errror
	 */
	function setErrorRegla($error)
	{
		$this->errorRegla = $error;
	}//Fin setErrorRegla

    /**
     * M�todo para instanciar y ejecutar las otras clases de rips de SOAT
     *
     */
    function ejecutarClasesRips()
    {
        global $_ROOT;
        $clases_rips = array
        (
            "FuRips_Eventos","FuRips_CuentaCobro"
        );
      /*  foreach($clases_rips as $key=>$clase_rips)
        {
            $RipsSoat[$key] = new $clase_rips($this->envio_id);
            include_once $_ROOT."classes/FuRips/".$RipsSoat[$key].".class.php";
	    //echo $clase_rips; exit;
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
                $this->error = "Error Construyendo el Archivo FURIPS";
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
        }*/
        if(!$this->ejecutar())
        {
            $this->error = "Error Construyendo el Archivo FIRIPS1";
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
     * M�todo para instanciar y ejecutar las otras clases de rips de SOAT
     *
     */
    function ejecutarClasesRipsCuentaCobro()
    {
        global $_ROOT;
       
        if(!$this->ejecutar2())
        {
            $this->error = "Error Construyendo el Archivo FURIPS2";
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

}//Fin clase
?>
