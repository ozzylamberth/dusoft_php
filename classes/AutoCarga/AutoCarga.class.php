<?php
	/** 
	* $Id: Autocarga.class.php,v 1.1 2007/10/08 13:01:14 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.1 $ 
	* 
	* Clase Factory , encargada de la creacion de objetos de las clases 
	* usuadas en la aplicacion
	*
	* @autor Hugo F  Manrique 
	*/
	class Autocarga
	{
	  /**
		* Hace el include del archivo donde se encuentra la clase y crea una instancia de 
		* la clase asociada
		* @param $clase String Nombre del archivo y de la clase que se desea instanciar 
		* @param $directorio String Nombre del directorio donde se encuentra el archivo 
		* @param $contenedor String Tipo de contenedor (app o system) donde se encuentra el archivo, si lo tiene
		* @param $modulo String Nombre del modulo donde se encuentra el archivo
		* @param $tipo Tipo de modulo (user, admin o controller)
		*
		* @return Object Instancia de la clase que se incluye
		*/
		function &factory($clase, $directorio='', $contenedor=null, $modulo=null, $tipo='')
		{
			if (IncludeClass($clase,$directorio,$contenedor,$modulo,$tipo)) 
			{
				$classname = $clase;
				return new $classname;
			} 
			else 
			{
				echo 'Driver no encontrado '.$clase;
			}
		}
	}
?>
