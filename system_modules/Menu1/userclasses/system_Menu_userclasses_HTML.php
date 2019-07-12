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
*Contiene los metodos visuales para realizar la administracion de usuarios
*/


class system_Menu_userclasses_HTML extends system_Menu_user
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function system_Menu_user_HTML()
	{
		$this->salida='';
		$this->system_Menu_user();
		return true;
	}



	 /**
* Funcion donde se visualiza el menu de usuario.
* @return boolean
*/
	function Menus()
 {
	$dats=$this->BuscarMenuUsuario();
	$this->salida .= ThemeAbrirTabla('MENUS DEL USUARIO');
	$this->salida .= "<br>";
	$this->salida .= "<br><table border=\"1\" width=\"88%\" align=\"center\" class=\"modulo_table\">";
	$this->salida.="<tr><td class=\"modulo_table_title\">Menus";
	$this->salida.="</td></tr>";
	if($dats)
				{

							$this->salida .= "            <tr><td>";
							$this->salida .= "              <table class=\"modulo_table\" cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"99%\" align=\"center\">";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">LOGIN<noBR>&nbsp&nbsp;USUARIO: </td><td class=\"modulo_list_claro\" align=\"left\">".$dats[0][usuario]."</td></tr>";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">NOMBRE: </td><td class=\"modulo_list_claro\" align=\"left\">".$dats[0][nombre]."</td></tr>";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">DESCRIPCION: </td><td class=\"modulo_list_oscuro\" align=\"left\">".$dats[0][desc]."</td></tr>";
							$this->salida .= "			         </table>";

							$this->salida.="<tr><td><br>";
							$this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"85%\">";
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
      				$this->salida.="  <td>Menu</td>";
							$this->salida.="  <td>descripcion</td>";
							$this->salida.="  <td>SubMenus</td>";
							$this->salida.="</tr>";
							for($i=0;$i<sizeof($dats);$i++)
							{
                  $id=$dats[$i][menu_id];
									$menu=$dats[$i][menu_nombre];
									$desc=$dats[$i][descripcion];
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\" align=\"center\">";
                  $this->salida.="  <td>$menu</td>";
									$this->salida.="  <td>$desc</td>";
									$dato=$this->BuscarSubMenuUsuario($id);
									$this->salida.="<td><table  align=\"center\" width=\"100%\" border=\"1\"  class=\"modulo_table_list\" >";
 									for($e=0;$e<sizeof($dato);$e++)
 									{
                     $title=$dato[$e][titulo];
 									  if( $e % 2){ $estilo='modulo_list_claro';}
 										else {$estilo='modulo_list_oscuro';}
 										$this->salida.=" <tr><td class=\"$estilo\">$title</td></tr>";
 									}
									$this->salida.=" </td></table>";

         					$this->salida.="</tr>";
							}

							$this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
							$this->salida.="</table>";
							$this->salida.="</td></tr>";
				}
				else
				{
						$this->salida.="<tr>";
						$this->salida.="<td align=\"center\" class=\"label_error\">No existen menus para este usuario.</td>";
						$this->salida.="</tr>";
						$this->salida.="</td></tr>";
						$this->salida.="</table><br>";
						$this->salida.="<br><table align=\"center\" border=\"0\" width=\"85%\">";
				/*		$this->salida.="<tr>";
						$this->salida.="  <td align=\"center\">";
						$this->salida .='<form name="forma" action="'.ModuloGetURL('app','CajaGeneral','user','BuscarDetalleC',array('Cajaid'=>$Cajaid,'arx'=>$valores)).'" method="post">';
						$this->salida .="<input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Adicionar Conceptos\" class=\"input-submit\"></form></td>";
						$this->salida.="</td>";
						$this->salida.="</tr>";*/
				}
				$this->salida.="</table>";
				$this->salida .= ThemeCerrarTabla();
  return true;
 }


}//fin clase user
?>

