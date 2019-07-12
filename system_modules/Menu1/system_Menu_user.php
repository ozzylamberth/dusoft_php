<?php

/**
*MODULO para el Manejo de Usuarios del Sistema
*
* @author Jairo Duvan Diaz Martinez
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

class system_Menu_user extends classModulo
{

	function system_Menu_user()
	{
		return true;
	}

/**
* Funcion donde se llama la funcion FormaInsertarUsuarioSistema
* @return boolean
*/

	function main(){

  	if(!$this->Menus()){
        return false;
    }
		return true;
  }




 /**
* Funcion que trae el usuario,nombre,password de la tabla system_usuarios
* @return array
*/
function BuscarMenuUsuario()
{

		list($dbconn) = GetDBconn();
		$query="select a.menu_id,a.menu_nombre,a.descripcion,b.usuario_id,
					c.nombre,c.descripcion as desc,c.usuario
					from  system_menus a,system_usuarios_menus b,system_usuarios c
					where b.usuario_id=".UserGetUID()."
					and a.menu_id=b.menu_id and c.usuario_id=b.usuario_id";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar los menus de usuario";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
		$i=0;
				while(!$resulta->EOF)
							{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$i++;
									$resulta->MoveNext();
							}
		}
			return $var;
}

function BuscarSubMenuUsuario($menu)
{

		list($dbconn) = GetDBconn();
		$query="select titulo from system_menus_items where menu_id=".$menu."";

		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar los submenus de usuario";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
		$i=0;
				while(!$resulta->EOF)
							{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$i++;
									$resulta->MoveNext();
							}
		}
			return $var;
}





}//fin clase user

?>

