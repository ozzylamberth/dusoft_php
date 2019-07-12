<?php

/**
*MODULO para el Manejo de Usuarios del Sistema
*
* @Jairo Duvan Diaz Martinez
* ultima actualizacion: Jairo Duvan Diaz Martinez -->lunes 1 de marzo 2004
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos para realizar la administracion de usuarios
*/

class system_Usuarios_user extends classModulo
{
		var $limit;
		var $conteo;

	function system_Usuarios_user()
	{
		//$this->limit=GetLimitBrowser();
	//	$this->limit=5;
		return true;
	}

/**
* Funcion donde se llama la funcion FormaInsertarUsuarioSistema
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

