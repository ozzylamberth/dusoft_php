<?php

/**
*MODULO Administrativo para el Manejo de Usuarios del Sistema
*
* @author Lorena Aragon & Jairo Duvan Diaz Martinez
* ultima actualizacion: Jairo Duvan Diaz Martinez -->lunes 1 de marzo 2004
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Email: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos para realizar la administracion de usuarios
*/

class system_Menu_admin extends classModulo
{
		var $limit;
		var $conteo;

	function system_Menu_admin()
	{
		$this->limit=GetLimitBrowser();
  	return true;
	}



/**
* Funcion donde se llama la funcion Menu
* @return boolean
*/

	function main(){

    if(!$this->Menu()){
        return false;
    }
		return true;
  }

}//fin clase user

?>


