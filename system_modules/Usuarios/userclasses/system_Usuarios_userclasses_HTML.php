<?php
/**
*MODULO para el Manejo de Usuarios del Sistema
*
* @author Lorena Aragon - Jairo Duvan Diaz Martinez
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


class system_Usuarios_userclasses_HTML extends system_Usuarios_user
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function system_Usuarios_user_HTML()
	{
		$this->salida='';
		$this->system_Usuarios_user();
		return true;
	}

/**
* Funcion donde se visualiza la forma de la configuración del usuario
* @return boolean
*/

	function FormaConfigUsuarioSistema($action){
    $dats=$this->TraerUsuario();
		$this->salida  = ThemeAbrirTabla('CONFIGURACION DE USUARIO:&nbsp; '.$dats[0][usuario].'');
		$this->salida .= "			      <br><br>";
		$this->salida .= "           <form name=\"formaUsuarios\" action=\"$action\" method=\"post\">";
		$this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"60%\" align=\"center\">";
    $this->salida .= "              <tr><td class=\"modulo_table_title\" width=\"23%\">Usuario :</td><td class=\"modulo_list_claro\">".$dats[0][nombre]."</td></tr>";
		$this->salida .= "  <tr><td class=\"modulo_table_title\" width=\"20%\">Login :</td><td class=\"modulo_list_claro\">".$dats[0][usuario]."</td></tr></table>";
    $this->salida .= "			      <table width=\"60%\" border=\"0\" align=\"center\">";
    $this->salida .= "            <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= "            </td></tr>";
		$this->salida .= "            <tr><td>";
		$this->salida .= "              <fieldset><legend class=\"field\">CONFIGURACION</legend>";
		$this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\" border=\"0\" width=\"60%\" align=\"center\">";
    $this->salida.= "               <input type=\"hidden\" name=\"action\" value=\"$action\">";
    $archivos=$this->listarDirectorios();
    $uid=UserGetUID();
		$tema=$this->RevisarTema($uid);
		$this->salida .= "				       <tr><td class=\"".$this->SetStyle("tema")."\">TEMA: </td><td class=\"modulo_list_oscuro\"><select name=\"tema\" class=\"select\">";

		   if(empty($tm))
			 {
			  		$this->salida .=" <option value=\"-1\">Default</option>";
						for($i=0;$i<sizeof($archivos);$i++)
						{
								if($archivos[$i]==$tema){
									$this->salida .=" <option value=\"$archivos[$i]\" selected>$archivos[$i]</option>";
								}else{
									$this->salida .=" <option value=\"$archivos[$i]\">$archivos[$i]</option>";
								}
						}
				}
		$this->salida .= "       </select></td></tr>";
    $this->salida .= "				       <tr><td class=\"".$this->SetStyle("descripcion")."\">NUMERO DE REGISTROS X CONSULTA: </td><td class=\"modulo_list_oscuro\"><select name=\"barra\" class=\"select\">";
    $numero=$this->TraerBarra();
		for($i=1;$i<101;$i++)
		{
		  if($i!=$numero)
			{
				 $this->salida .= "<option value=\"$i\">$i</option>";
			}
			else
			{
				$this->salida .= "<option value=\"$i\" selected>$i</option>";
			}
		}
		$i=1;
		$this->salida .= "</select></td></tr>";
		$this->salida .= "			         </table>";
		$this->salida .= "		           </fieldset></td></tr>";

		$this->salida .= "  <table width=\"40%\" align=\"center\">";
		$this->salida .= "              <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Aceptar\" type=\"submit\" value=\"Insertar\"><br></td>";
		$this->salida .= "			      </form>";
    $action3=ModuloGetURL('system','Usuarios','user','main',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
		$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
		$this->salida .= "<td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Cancelar\"></td></tr>";
    $this->salida .= "			      </form>";
		$this->salida .= "            </table><BR><BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
				}
			return ("label");
	}



 /**
* Funcion donde se visualiza la forma que pide datos para modificar
* el password de un usuario.
* @return boolean
*/

  function FormaModificarPasswd($action){
			$dats=$this->TraerUsuario();
			$this->salida  = ThemeAbrirTabla('CAMBIO CONTRASEÑA :  '.$dats[0][usuario].'');
			$this->salida .= "			      <br><br>";
			$this->salida .= "           <form name=\"formaContraseña\" action=\"$action\" method=\"post\">";
			$this->salida .= "			      <table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida .= "            <tr><td>";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "            </td></tr>";
			$this->salida .= "            <tr><td>";
			$this->salida .= "              <fieldset><legend class=\"field\">DATOS CONTRASEÑA</legend>";
			$this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "              <tr><td class=\"modulo_table_title\" width=\"23%\">Usuario :</td><td class=\"modulo_list_claro\">".$dats[0][nombre]."</td></tr>";
			$this->salida .= "  <tr><td class=\"modulo_table_title\" width=\"20%\">Login :</td><td class=\"modulo_list_claro\">".$dats[0][usuario]."</td></tr>";
    if(empty($_SESSION['PWD']))
		{
				$nom='verificar';
				$dat='Verificar';
				$this->salida .= "				       <tr  class=\"modulo_table_title\"><td width=\"30%\" class=\"".$this->SetStyle("password")."\">Repita su Password: </td><td align=\"left\" class=\"modulo_list_oscuro\" colspan=\"1\"><input type=\"password\" class=\"input-text\" name=\"viejopass\" maxlength=\"40\"></td></tr>";
		}
		else
		{
		  $nom='aceptar';
       $dat='Cambiar';
			$this->salida .= "				       <tr  class=\"modulo_table_title\"><td width=\"30%\" class=\"".$this->SetStyle("password")."\">Nuevo Password: </td><td align=\"left\" class=\"modulo_list_oscuro\" colspan=\"1\"><input type=\"password\" class=\"input-text\" name=\"password\" maxlength=\"40\" value=\"$password\"></td></tr>";
  		$this->salida .= "				       <tr class=\"modulo_table_title\"><td width=\"30%\" class=\"".$this->SetStyle("passwordReal")."\">Repita Password: </td><td align=\"left\" class=\"modulo_list_oscuro\" colspan=\"1\"><input  type=\"password\" class=\"input-text\" name=\"passwordReal\" maxlength=\"40\" value=\"$passwordReal\"></td></tr>";

		}
			$this->salida .= "	  	          <input type=\"hidden\" name=\"usuario\" value=\"".$dats[0][usuario]."\">";
			$this->salida .= "	  	          <input type=\"hidden\" name=\"nombre\" value=\"".$dats[0][nombre]."\">";
			$this->salida .= "	  	          <input type=\"hidden\" name=\"viejo\" value=\"".$dats[0][passwd]."\">";
			$this->salida .= "			         </table>";
			$this->salida .= "		           </fieldset></td></tr>";
			$this->salida .= "			      <table width=\"40%\"  border=\"0\" align=\"center\">";
			$this->salida .= "              <tr><td align=\"center\"><br><input class=\"input-submit\" name=\"$nom\" type=\"submit\" value=\"$dat\"></td>";
			$this->salida .= "			      </form>";

			$action3=ModuloGetURL('system','Usuarios','user','Menu',array("uid"=>$uid));
			$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
			$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Cancelar\"></td></tr>";
			//$this->salida .= "            </table>";
			$this->salida .= "            </table><BR>";
			$this->salida .= "			      </form>";
			$this->salida .= ThemeCerrarTabla();
			return true;
	}




	/*refrescando pantalla, esta funcion es simplemente experimental.............*/
 function RefrescarPantalla($mensaje='')
  {
    $this->salida  = "<div align=\"center\" class='titulo1'>\n";
    $this->salida .= "Un momento por favor<br>$mensaje\n";
    $this->salida .= "</div><br><br>\n";

    if(SessionGetVar('StyleFrames')){
      $this->salida .=  "\n\n<script language=\"javascript\">setTimeout('top.location.reload()',1500);</script>\n\n";
    }else{
      $this->salida .=  "\n\n<script language=\"javascript\">setTimeout('reload()',1500);</script>\n\n";
    }
		//$this->main();
    return true;
  }
/**************************************esta es una funcion experimental**************/


	 /**
* Funcion donde se visualiza el menu de usuario.
* @return boolean
*/
	function Menu()
 {   //echo  mail("192.168.1.16", "jaja", "eres \n un \n patron");
      unset($_SESSION['PWD']);
			$this->salida.= ThemeAbrirTabla('MENU DE USUARIO SIIS V1.0 ALFA');
     	$this->salida.="<br><table border=\"0\"  class=\"modulo_table_list\"  align=\"center\"   width=\"80%\" >";
			$this->salida.="<tr>";
	    $this->salida .= "<td colspan=\"2\"   align=\"center\" class=\"modulo_table_title\" >EVENTOS DE USUARIOS</td>";
			$this->salida.="</tr>";
			$ac=ModuloGetURL('system','Usuarios','user','LlamaConfigUsuarioSistema');
			$ax=ModuloGetURL('system','Usuarios','user','LlamaFormaModificarPasswd');

      $this->salida.="<tr>";
			$this->salida .= "<td  colspan=\"2\"  class=\"modulo_list_oscuro\"  align=\"center\"><a href=\"$ax\">CAMBIAR CONTRASEÑA DE USUARIO</a>&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/pass.png\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida .= "<td   colspan=\"2\"  class=\"modulo_list_claro\"  align=\"center\"><a href=\"$ac\">OTRAS CONFIGURACIONES DE USUARIO</a>&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/configuracion.gif\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.= ThemeCerrarTabla();
			return true;
 }

}//fin clase user
?>

