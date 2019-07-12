<?php
// block_menu.php  05/08/2003
// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: Generar el contenido del menu de la aplicacion
// ----------------------------------------------------------------------

class classMenu  extends classModules
{

  function classMenu()
	{
    $this->classModules();
    return true;
	}

 	  function AsignarUrl($dato)
		{

     if(empty($dato))
		 {
				$var1[]=array('','');
				return $var1;
		 }

		 list($dbconn) = GetDBconn();
		 $sqls="select menu_id ,titulo,modulo_tipo,modulo,tipo,metodo,
							descripcion,indice_de_orden from system_menus_items where menu_id=$dato
							order by indice_de_orden asc";

			$resulta = $dbconn->Execute($sqls);

			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if($resulta->EOF)
			{
				$var1[]=array('','');
				return $var1;
			}

    while(!$resulta->EOF)
		{

						$nombre=$resulta->fields[1];
					  $tipo_modulo=$resulta->fields[2];
						$modulo=$resulta->fields[3];
						$tipo=$resulta->fields[4];
						$metodo=$resulta->fields[5];

     if($tipo_modulo)
		 {
				$var1[]=array("$nombre",ModuloGetURL("$tipo_modulo","$modulo","$tipo","$metodo"),'');
  			$var1[]=array('','');
     }
     else
		 {
			$var1[]=array('','');
     }
      $resulta->MoveNext();
		}
     return $var1;

		}


	function Inicializar()
  {
		if(!IncludeFile(GetThemePath()."/menu_theme.php")){
			$this->error = "Error al Cargar el Manejador de Menus";
			$this->mensajeDeError = "El archivo '".GetThemePath()."/menu_theme.php' no existe.";
			return false;
		}

    if (!function_exists("ThemeReturnMenu")) {
      $this->error = "Error al Cargar el Manejador de Menus";
      $this->mensajeDeError = "La funcion ThemeReturnMenu no existe en el archivo '".GetThemePath()."/menu_theme.php' ";
      return false;
    }

		if(!UserLoggedIn()){
			$this->salida = ThemeReturnMenu(array());
			return true;
		}

    if(UserGetVar(UserGetUID(),'sw_admin')){
      $this->salida .= ThemeReturnMenu(array(array('Administrador',ModuloGetURL(),'')));
			return true;
    }

		list($dbconn) = GetDBconn();
		$sql="select menu_id,menu_nombre from system_menus order by menu_id asc";
		$resulta = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		if($resulta->EOF)
		{
			$this->salida .= ThemeReturnMenu(array());
		}

		$dato = SessionGetVar('MENU_SELECCION');

		if(empty($dato))
		{
			$dato=UserGetVar(UserGetUID(),'UltimoMenuUser');

		}else{
			UserSetVar(UserGetUID(),'UltimoMenuUser',$dato);
		}


		$this->ComboMenu($resulta,$dato);
		$this->salida .= ThemeReturnMenu($this->AsignarUrl($dato));
		$this->salida .=RefrescarContenidoHomePage();
		return true;

  }

	function ComboMenu($resulta,$dato)
	{
			$this->salida .="<form action='Menu.php'>";
			$this->salida .="<br>";
			$this->salida .="<table align=\"center\" >";
			$this->salida .="<tr>";
      $this->salida .="<td align=\"center\" class=\"label\">Seleccione Menu</td>";
      $this->salida .="</tr>";
			$this->salida .="<tr>";
			$this->salida .="<td align=\"center\"><select name=\"MENU_SELECCION\" class=\"select\" onchange=\"javascript:submit();\">";
			if(empty($dato)){
				$seleccione='selected';
			}
			$this->salida .="<option value='0' $seleccione>----Seleccione----</option>";
			while(!$resulta->EOF)
			{
				$value=$resulta->fields[0];
				$nombre=$resulta->fields[1];
				if($value==$dato)
				{$selected='selected';}else{$selected='';}
				$this->salida .="<option value='$value' $selected>$nombre</option>";
				$resulta->MoveNext();
			}
			$this->salida .="	</select></td>";
			$this->salida .="</tr>";
			$this->salida .="</table>";
			$this->salida .="</form>";
			return true;
	}
}//fin clase

?>

