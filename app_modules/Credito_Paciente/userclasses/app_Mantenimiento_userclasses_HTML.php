<?php

/**
 * $Id: app_Mantenimiento_userclasses_HTML.php,v 1.2 2005/06/02 19:06:55 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Mantenimiento_userclasses_HTML extends app_Mantenimiento_user
{
	function app_Mantenimiento_user_HTML()
	{
		$this->app_Mantenimiento_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	function PrincipalMantenimiento2()//Llama a todas las opciones posibles
	{
		UNSET($_SESSION['mantenimiento']);
		UNSET($_SESSION['crpada']);
		if($this->UsuariosMantenimiento()==false)
		{
			return false;
		}
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos del PAGARË
	function PrincipalMantenimiento()//Llama a todas las opciones posibles
	{
		if(empty($_REQUEST['permisomantenimiento']['empresa_id']) AND empty($_SESSION['mantenimiento']['empresa']))
		{
			$this->frmError["MensajeError"]="SELECCIONE UNA EMPRESA";
			$this->uno=1;
			$this->PrincipalMantenimiento2();
			return true;
		}

		if(empty($_SESSION['mantenimiento']['empresa']))
		{
			$_SESSION['mantenimiento']['empresa']=$_REQUEST['permisomantenimiento']['empresa_id'];
			$_SESSION['mantenimiento']['razonso']=$_REQUEST['permisomantenimiento']['descripcion1'];
			$_SESSION['mantenimiento']['centroutil']=$_REQUEST['permisomantenimiento']['centro_utilidad'];
			$_SESSION['mantenimiento']['descentro']=$_REQUEST['permisomantenimiento']['descripcion2'];
		}

		$this->salida  = ThemeAbrirTabla('TABLAS MANTENIMIENTO','60%');

		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}

    $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"40%\">";
		$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"100%\" colspan=\"1\">";
		$this->salida .= "      MENÚ";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\"  align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Mantenimiento','user','RegeneracionTablas')."\">REGENARACIÓN DE TABLAS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
    $this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Mantenimiento','user','llamaFormaMantenimiento') ."\">ADMIN DE TABLAS PARA MANTENIMIENTO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Mantenimiento','user','LlamaFormaListadoTablasMantenimiento') ."\">MANTENIMIENTO DE TABLAS</a>";
		//$this->salida .= "      <a href=\"". ModuloGetURL('app','Mantenimiento','user','LlamaFormaBusquedaTablas')."\">TABLAS DE LA APLICACIÓN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
/*		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\"label\"  align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Mantenimiento','user','LlamaFormaConectarBD')."\">TABLAS DE LA APLICACIÓN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";*/
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\"label\"  align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Mantenimiento','user','LlamaFormaSecuencias')."\">SECUENCIAS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$accion=ModuloGetURL('app','Mantenimiento','user','PrincipalMantenimiento2');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

  function FormaBusquedaTablas($databases)
  {
		$this->salida = ThemeAbrirTabla('BASES DE DATOS DEL SISTEMA','90%');
		$accion=ModuloGetURL('app','Mantenimiento','user','');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['mantenimiento']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"1%\" >No.</td>";
		$this->salida .= "      <td width=\"1%\">NOMBRE DB</td>";
		$this->salida .= "      <td width=\"3%\">PROPIETARIO</td>";
		$this->salida .= "      <td width=\"3%\" >CODIFICACIÓN</td>";
		$this->salida .= "      <td width=\"3%\">COMENTARIO</td>";
		$this->salida .= "      </tr>";
		$planempr=$databases;
		$i=0;
		$j=0;
		$ciclo=sizeof($planempr);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$j=0;
			}
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
   	  $funcion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaConectarBD',array('datname'=>$planempr[$i]['datname']));
			$this->salida .= "<center><a href =$funcion>".$planempr[$i]['datname']."</a></center>";//plan_id
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$planempr[$i]['datowner']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$planempr[$i]['datencoding']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$planempr[$i]['datcomment']."";
			$this->salida .= "</td>";
/*  		$this->salida .= "<td>";
			$this->salida .= "<center><a href =$funcion>EDITAR</a></center>";
			$this->salida .= "</td>";*/
	    $this->salida .= "</tr>";
			$i++;
		}
		if(empty($planempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"9\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN ENVIO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
 		$this->salida .= "      </table>";
    //$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"center\">";
    //$this->salida .= "      <tr>";
    //$this->salida .= "      <td align=\"center\"><br>";
    //$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO EMPRESA\">";
    //$this->salida .= "      </td>";
    //$this->salida .= "      </tr>";
    //$this->salida .= "      </table>";
		//$this->salida .= "  </fieldset>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Mantenimiento','user','Principalmantenimiento');
		$this->salida .= "  <form name=\"contratacion1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"VOLVER\">";
		$this->salida .= "  </form>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  </form><br>";
		//$var=$this->RetornarBarraClientes();
		if(!empty($var))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','Mantenimiento','user','',
		array('codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"ctradescri\" value=\"".$_REQUEST['ctradescri']."\" maxlength=\"60\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','Cartera','user','');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
    return true;
  }

  function FormaConectarBD()
  {
    $action=ModuloGetURL('app','Mantenimiento','user','LlamaFormaVerTablas',array('HOST'=>$_REQUEST['HOST'],'UserBD'=>$_REQUEST['UserBD'],'Passwd'=>$_REQUEST['Passwd'],'BD'=>$_REQUEST['BD']));
    $this->salida .= ThemeAbrirTabla('MANTENIMIENTO BD ACTUAL','90%');
    $this->salida .= "<table width=\"70%\" border=1 cellpadding=4 align=\"center\">\n";
    $this->salida .= "<tr>\n";
    $this->salida .= "<td align=\"center\"><br>\n";
    $this->salida .= "\n";
    $DatosConexionActual=$this->GetDatosConexionActual();
		$_SESSION['mantenimiento']['db']=$DatosConexionActual['dbname'];
    $this->salida .= "<form action=\"$action\" method=\"POST\" name=\"FrmConn\">\n";
    $this->salida .= "    <table width=\"80%\" cellspacing=1 border=0 cellpadding=2 align=\"center\">\n";
    $this->salida .= "        <tr>\n";
    $this->salida .= "        <td>Host</td>\n";
    $this->salida .= "        <td><input type=\"text\" name=\"HOST\"  value=".$DatosConexionActual['dbhost']." size=20 maxlength=20 readonly=\"true\"></td>\n";
    $this->salida .= "        </tr>\n";
    $this->salida .= "        <tr>\n";
    $this->salida .= "        <td>Base de Datos</td>\n";
    $this->salida .= "        <td><input type=\"text\" name=\"BD\"  value='".$DatosConexionActual['dbname']."' size=20 maxlength=20 readonly=\"true\"></td>\n";
    $this->salida .= "        </tr>\n";
    $this->salida .= "        <tr>\n";
    $this->salida .= "        <td>Usuario</td>\n";
    $this->salida .= "        <td><input type=\"text\" name=\"UserBD\"  value='".$DatosConexionActual['dbuser']."' size=20 maxlength=20 readonly=\"true\"></td>\n";
    $this->salida .= "        </tr>\n";
    $this->salida .= "        <tr>\n";
    $this->salida .= "        <td>Contraseña</td>\n";
    $this->salida .= "        <td><input type=\"password\" name=\"Passwd\"  value='".$DatosConexionActual['dbpass']."' size=20 maxlength=20 readonly=\"true\"></td>\n";
    $this->salida .= "        </tr>\n";
    $this->salida .= "        <tr>\n";
    $this->salida .= "        <td>&nbsp;</td>\n";
    $this->salida .= "        <td><input type=\"submit\" value=\"VER TABLAS\"></td>\n";
    $this->salida .= "        <td>&nbsp;</td>\n";
    $this->salida .= "        </tr>\n";
    $this->salida .= "    </table>\n";
    $this->salida .= "</form>\n";
    $this->salida .= "\n";
	  $this->salida .= "  <tr>";
		//$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaBusquedaTablas');
		$accion=ModuloGetURL('app','Mantenimiento','user','Principalmantenimiento');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
    $this->salida .= "\n";
    $this->salida .= "</td>\n";
    $this->salida .= "</tr>\n";
    $this->salida .= "</table>\n";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }

 //FormaVerTablas
 function FormaVerTablas($tables,$db,$motor,$version)
 {
 		$_SESSION['mantenimiento']['db']=$db;
 		$_SESSION['mantenimiento']['motor']=$motor;
		$this->salida = ThemeAbrirTabla('TABLAS DE LA BD: '.$_SESSION['mantenimiento']['db'].' - MOTOR: '.$_SESSION['mantenimiento']['motor'].' - VERSIÓN: '.$_SESSION['mantenimiento']['version1'],'90%');
		$accion=ModuloGetURL('app','Mantenimiento','user','');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['mantenimiento']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"1%\" >No.</td>";
		$this->salida .= "      <td width=\"1%\">NOMBRE TABLA</td>";
		$this->salida .= "      <td width=\"1%\">PROPIETARIO</td>";
		$this->salida .= "      <td width=\"1%\" >ESQUEMA</td>";
		$this->salida .= "      <td width=\"1%\"># CAMPOS</td>";
		$this->salida .= "      <td width=\"1%\" ># FILAS APROX.</td>";
		$this->salida .= "      <td width=\"3%\">COMENTARIO</td>";
		$this->salida .= "      </tr>";
		$planempr=$tables;
		$i=0;
		$j=0;
		$ciclo=sizeof($planempr);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$j=0;
			}
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"left\">";
   	  $funcion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaVerCampos',array("schemaname"=>$planempr[$i]['schema'],"tablename"=>$planempr[$i]['tabla'],"tableowner"=>$planempr[$i]['propietario']));
			$this->salida .= "<a href =$funcion>".$planempr[$i]['tabla']."</a>";//plan_id
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$planempr[$i]['propietario']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$planempr[$i]['schema']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$planempr[$i]['numcampos']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$planempr[$i]['numfilasaprox']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$planempr[$i]['comentario']."";
			$this->salida .= "</td>";
	    $this->salida .= "</tr>";
			$i++;
		}
		if(empty($planempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"9\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ REGISTRO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
 		$this->salida .= "      </table>";
    //$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"center\">";
    //$this->salida .= "      <tr>";
    //$this->salida .= "      <td align=\"center\"><br>";
    //$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO EMPRESA\">";
    //$this->salida .= "      </td>";
    //$this->salida .= "      </tr>";
    //$this->salida .= "      </table>";
		//$this->salida .= "  </fieldset>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Mantenimiento','user','Principalmantenimiento');
		$this->salida .= "  <form name=\"contratacion1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"VOLVER\">";
		$this->salida .= "  </form>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  </form><br>";
		//$var=$this->RetornarBarraClientes();
		if(!empty($var))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
/*		$accion=ModuloGetURL('app','Cartera','user','',
		array('codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"ctradescri\" value=\"".$_REQUEST['ctradescri']."\" maxlength=\"60\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','Mantenimiento','user','');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";*/
		$this->salida .= ThemeCerrarTabla();
    return true;
 }

 function FormaVerCampos($campos,$pk,$forkey,$refforkey,$schema,$tabla,$campos1)//Muestra los campos de una tabla
	{
		global $ConfigDB;
		$this->salida  = ThemeAbrirTabla('MANTENIMIENTO '._SIIS_APLICATION_TITLE .'  (SERVIDOR : '.$ConfigDB['dbhost'].' -  DATABASE : '.$ConfigDB['dbname'].')');
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td width=\"100%\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td><fieldset><legend class=\"field\">INFORMACIÓN DE LA BASE DE DATOS</legend>";
		$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">ESQUEMA</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$this->salida .= "      ".$campos1[0][schema]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">NOMBRE DE LA TABLA</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$this->salida .= "".$tabla."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">PROPIETARIO</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$this->salida .= "      ".$campos1[0][propietario]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">COMENTARIO</td>";// width=\"100%\"
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$this->salida .= "      ".$campos1[0][comentario]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\" width=\"20%\">NOMBRE DEL CAMPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"18%\">TIPO DEL CAMPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"8%\" >NO NULO</td>";
		$this->salida .= "      <td align=\"center\" width=\"10%\">DEFAULT</td>";
		$this->salida .= "      <td align=\"center\" width=\"44%\">COMENTARIO</td>";
		$this->salida .= "      </tr>";
		$i=0;
		$j=0;
		$ciclo=sizeof($campos);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$j=0;
			}
			$this->salida .= "<td>";
//   	  $funcion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaVerFk',array("schemaname"=>$schema,"tablename"=>$campos[$i]['nombre_campo']));
//			$this->salida .= "<a href=$funcion>".$campos[$i]['nombre_campo']."</a>";
			$this->salida .= "".$campos[$i]['nombre_campo']."";
      $this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i]['tipo_campo']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i]['no_nulo']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i]['default']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i]['comentario']."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		$this->salida .= "      </table><br>";
		if(!empty($pk))
		{
			$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=modulo_table_list_title>";
			$this->salida .= "      <td align=\"center\" width=\"100%\">LLAVE PRIMARIA</td>";
			$this->salida .= "      </tr>";
			$i=0;
			$j=0;
			$ciclo=sizeof($pk);
			while($i<$ciclo)
			{
				if($j==0)
				{
					$this->salida .= "<tr class=\"modulo_list_claro\">";
					$j=1;
				}
				else
				{
					$this->salida .= "<tr class=\"modulo_list_oscuro\">";
					$j=0;
				}
				$this->salida .= "<td>";
				$this->salida .= "".$pk[$i]['consrc']."";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$i++;
			}
			$this->salida .= "      </table><br>";
		}

		if(!empty($forkey))
		{
			$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=modulo_table_list_title>";
			$this->salida .= "      <td align=\"center\" width=\"100%\">LLAVES FORANEAS</td>";
			$this->salida .= "      </tr>";
			$i=0;
			$j=0;
			$ciclo=sizeof($forkey);
			while($i<$ciclo)
			{
				if($j==0)
				{
					$this->salida .= "<tr class=\"modulo_list_claro\">";
					$j=1;
				}
				else
				{
					$this->salida .= "<tr class=\"modulo_list_oscuro\">";
					$j=0;
				}
				$this->salida .= "<td>";
				$this->salida .= "".$forkey[$i]['consrc']."";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$i++;
			}
			$this->salida .= "      </table><br>";
		}
		if(!empty($refforkey))
		{
			$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=modulo_table_list_title>";
			$this->salida .= "     <center><td align=\"center\" width=\"100%\">TABLAS REFERENCIADAS</td></center>";
			$this->salida .= "      </tr>";
			$i=0;
			$j=0;
			$ciclo=sizeof($refforkey);
			while($i<$ciclo)
			{
				if($j==0)
				{
					$this->salida .= "<tr class=\"modulo_list_claro\">";
					$j=1;
				}
				else
				{
					$this->salida .= "<tr class=\"modulo_list_oscuro\">";
					$j=0;
				}
				$this->salida .= "<td>";
   	    $funcion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaVerCamposReferenciados',array("tablename"=>$refforkey[$i]['table_ref'],"schemaname"=>$schema));
				$this->salida .= "<a href=$funcion>".$refforkey[$i]['table_ref']."</a>";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$i++;
			}
			$this->salida .= "      </table>";
		}
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		//$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaVerTablas');
    $accion=ModuloGetURL('app','Mantenimiento','user','FormaListadoTablasMantenimiento');
		$this->salida .= "  <form name=\"menu\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LAS TABLAS\"><br>";
/*		if ($_SESSION['swbutton']!=false)
    {
      $accion=ModuloGetURL('app','Mantenimiento','user','');
      $this->salida .= "  <form name=\"menu\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"verdatos\" value=\"VER DATOS\"><br>";
    }*/
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "</td></tr></table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

 //ver campos referencia
 function FormaVerCamposReferenciados($campos,$pk,$forkey,$schema,$tabla,$propietario,$llaves,$tablasRef)//Muestra los campos de una tabla
	{
  	$_SESSION['mantenimiento']['schema']=$schema;
  	$_SESSION['mantenimiento']['tabla']=$tabla;
		global $ConfigDB;
		$this->salida  = ThemeAbrirTabla('MANTENIMIENTO '._SIIS_APLICATION_TITLE .'  (SERVIDOR : '.$ConfigDB['dbhost'].' -  DATABASE : '.$ConfigDB['dbname'].')');
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td width=\"100%\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td><fieldset><legend class=\"field\">INFORMACIÓN DE LA BASE DE DATOS</legend>";
		$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">ESQUEMA</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$this->salida .= "      ".strtoupper($schema)."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">NOMBRE DE LA TABLA</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$this->salida .= "".$tabla."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">PROPIETARIO</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$this->salida .= "      ".strtoupper($propietario)."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">COMENTARIO</td>";// width=\"100%\"
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$this->salida .= "      ".strtoupper($tabla[0][2])."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\" width=\"20%\">NOMBRE DEL CAMPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"18%\">TIPO DEL CAMPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"8%\" >NO NULO</td>";
		$this->salida .= "      <td align=\"center\" width=\"10%\">DEFAULT</td>";
		$this->salida .= "      <td align=\"center\" width=\"44%\">COMENTARIO</td>";
		$this->salida .= "      </tr>";
		$i=0;
		$j=0;
		$ciclo=sizeof($campos);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$j=0;
			}
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i]['nombre_campo']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i]['tipo_campo']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i]['no_nulo']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i]['default']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i]['comentario']."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		$this->salida .= "      </table><br>";

    if(!empty($pk))
		{
			$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=modulo_table_list_title>";
			$this->salida .= "      <td align=\"center\" width=\"100%\">LLAVE PRIMARIA</td>";
			$this->salida .= "      </tr>";
			$i=0;
			$j=0;
			$ciclo=sizeof($pk);
			while($i<$ciclo)
			{
				if($j==0)
				{
					$this->salida .= "<tr class=\"modulo_list_claro\">";
					$j=1;
				}
				else
				{
					$this->salida .= "<tr class=\"modulo_list_oscuro\">";
					$j=0;
				}
				$this->salida .= "<td>";
				$this->salida .= "".$pk[$i]['consrc']."";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$i++;
			}
			$this->salida .= "      </table><br>";
		}

		if(!empty($forkey))
		{
			$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=modulo_table_list_title>";
			$this->salida .= "      <td align=\"center\" width=\"100%\">LLAVES FORANEAS</td>";
			$this->salida .= "      </tr>";
			$i=0;
			$j=0;
			$ciclo=sizeof($forkey);
			while($i<$ciclo)
			{
				if($j==0)
				{
					$this->salida .= "<tr class=\"modulo_list_claro\">";
					$j=1;
				}
				else
				{
					$this->salida .= "<tr class=\"modulo_list_oscuro\">";
					$j=0;
				}
				$this->salida .= "<td>";
				$this->salida .= "".$forkey[$i]['consrc']."";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$i++;
			}
			$this->salida .= "      </table><br>";
		}
/*		if(!empty($refforkey))
		{
			$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=modulo_table_list_title>";
			$this->salida .= "     <center><td align=\"center\" width=\"100%\">TABLAS REFERENCIADAS</td></center>";
			$this->salida .= "      </tr>";
			$i=0;
			$j=0;
			$ciclo=sizeof($refforkey);
			while($i<$ciclo)
			{
				if($j==0)
				{
					$this->salida .= "<tr class=\"modulo_list_claro\">";
					$j=1;
				}
				else
				{
					$this->salida .= "<tr class=\"modulo_list_oscuro\">";
					$j=0;
				}
				$this->salida .= "<td>";
   	    $funcion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaVerCampos',array("tablename"=>$refforkey[$i]['table_ref'],"schemaname"=>$schema));
				$this->salida .= "<a href=$funcion>".$refforkey[$i]['table_ref']."</a>";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$i++;
			}
			$this->salida .= "      </table>";
		}*/
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaVerCampos');
		$this->salida .= "  <form name=\"menu\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A TABLAS REFERENCIA\"><br>";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaVerDatos',array("campos"=>$llaves,"tablas"=>$tablasRef));
		$this->salida .= "  <form name=\"menu\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"verdatos\" value=\"VER DATOS\"><br>";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "</td></tr></table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

  //función forma ver datos
  function FormaVerDatos($tabla,$atributos,$datos,$pk,$fk1,$fk2,$fktabla,$camposrefarray)
  {
/*  echo CAMPOS1;
		print_r($pk);
  echo CAMPOS1_FIN;
  echo CAMPOS2;
		print_r($fk2);
  echo CAMPOS2_FIN;
  echo FKTABLAS;
		print_r($fktabla);
  echo FKTABLAS_FIN;*/
    if (empty($tabla) || empty($atributos) || empty($datos))
    {
			$tabla=$_SESSION['mantenimiento']['tabla'];
      $atributos=$_SESSION['mantenimiento']['atributos'];
      $datos=$_SESSION['mantenimiento']['datos'];
    }
  	if (empty($_SESSION['mantenimiento']['db']))
    {
			$conn=$this->GetDatosConexionActual();
			$_SESSION['mantenimiento']['db']=$conn['dbname'];
    }
		$this->salida = ThemeAbrirTabla('BASE DE DATOS: '.$_SESSION['mantenimiento']['db'].' / TABLA: '.$tabla,'90%');
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaInsertarDatos',array("tabla"=>$tabla,"campos"=>$atributos,"fk2"=>$fk2,"camposrefarray"=>$camposrefarray,"fk1"=>$fk1,"tablaref"=>$fktabla));
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		if(sizeof($atributos)<5)
			$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		else
			$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['mantenimiento']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		if(sizeof($atributos)<5)
			$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
    else
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
	  $this->salida .= "      <td width=\"4%\" colspan=\"2\">ACCIONES</td>";
    $this->salida .= "      <td width=\"1%\" >No.</td>";
		$i=0;
		$ciclo=sizeof($atributos);
		while($i<$ciclo)
		{
		  $this->salida .= "      <td width=\"1%\">".$atributos[$i][nombre_campo]."</td>";
      $i++;
    }
		$this->salida .= "      </tr>";
		$planempr=$datos;
		$i=0;
		$j=0;
		$ciclo=sizeof($planempr);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\" >";
				$j=0;
			}
			$this->salida .= "<td align=\"center\" width=\"4%\">";
      if (sizeof($pk[0])==1)
  		{
      	$campo=trim($pk[0][0]);
        $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaEditar',array("tabla"=>$tabla,"atributo1"=>$campo,"pk1"=>$planempr[$i][$campo],"nro_pk"=>'1',"atributos"=>$atributos,"fk1"=>$fk1,"fk2"=>$fk2,"fktabla"=>$fktabla,"camposrefarray"=>$camposrefarray));
      }
      if (sizeof($pk[0])==2)
      {
      	$campo=trim($pk[0][0]);
      	$campo1=trim($pk[0][1]);
        $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaEditar',array("tabla"=>$tabla,"atributo1"=>$campo,"atributo2"=>$campo1,"pk1"=>$planempr[$i][$campo],"pk2"=>$planempr[$i][$campo1],"nro_pk"=>2,"atributos"=>$atributos,"fk1"=>$fk1,"fk2"=>$fk2,"fktabla"=>$fktabla,"camposrefarray"=>$camposrefarray));
      }
      if (sizeof($pk[0])==3)
      {
      	$campo=trim($pk[0][0]);
      	$campo1=trim($pk[0][1]);
      	$campo2=trim($pk[0][2]);
        $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaEditar',array("tabla"=>$tabla,"atributo1"=>$campo,"atributo2"=>$campo1,"atributo3"=>$campo2,"pk1"=>$planempr[$i][$campo],"pk2"=>$planempr[$i][$campo1],"pk3"=>$planempr[$i][$campo2],"nro_pk"=>3,"atributos"=>$atributos,"fk1"=>$fk1,"fk2"=>$fk2,"fktabla"=>$fktabla,"camposrefarray"=>$camposrefarray));
      }
      if (sizeof($pk[0])==4)
      {
      	$campo=trim($pk[0][0]);
      	$campo1=trim($pk[0][1]);
      	$campo2=trim($pk[0][2]);
      	$campo3=trim($pk[0][3]);
        $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaEditar',array("tabla"=>$tabla,"atributo1"=>$campo,"atributo2"=>$campo1,"atributo3"=>$campo2,"atributo4"=>$campo3,"pk1"=>$planempr[$i][$campo],"pk2"=>$planempr[$i][$campo1],"pk3"=>$planempr[$i][$campo2],"pk4"=>$planempr[$i][$campo3],"nro_pk"=>4,"atributos"=>$atributos,"fk1"=>$fk1,"fk2"=>$fk2,"fktabla"=>$fktabla,"camposrefarray"=>$camposrefarray));
      }
      if (sizeof($pk[0])==5)
      {
      	$campo=trim($pk[0][0]);
      	$campo1=trim($pk[0][1]);
      	$campo2=trim($pk[0][2]);
      	$campo3=trim($pk[0][3]);
      	$campo4=trim($pk[0][4]);
        $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaEditar',array("tabla"=>$tabla,"atributo1"=>$campo,"atributo2"=>$campo1,"atributo3"=>$campo2,"atributo4"=>$campo3,"atributo5"=>$campo4,"pk1"=>$planempr[$i][$campo],"pk2"=>$planempr[$i][$campo1],"pk3"=>$planempr[$i][$campo2],"pk4"=>$planempr[$i][$campo3],"pk5"=>$planempr[$i][$campo4],"nro_pk"=>5,"atributos"=>$atributos,"fk1"=>$fk1,"fk2"=>$fk2,"fktabla"=>$fktabla,"camposrefarray"=>$camposrefarray));
      }
      if (sizeof($pk[0])==6)
      {
      	$campo=trim($pk[0][0]);
      	$campo1=trim($pk[0][1]);
      	$campo2=trim($pk[0][2]);
      	$campo3=trim($pk[0][3]);
      	$campo4=trim($pk[0][4]);
      	$campo5=trim($pk[0][5]);
        $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaEditar',array("tabla"=>$tabla,"atributo1"=>$campo,"atributo2"=>$campo1,"atributo3"=>$campo2,"atributo4"=>$campo3,"atributo5"=>$campo4,"atributo6"=>$campo5,"pk1"=>$planempr[$i][$campo],"pk2"=>$planempr[$i][$campo1],"pk3"=>$planempr[$i][$campo2],"pk4"=>$planempr[$i][$campo3],"pk5"=>$planempr[$i][$campo4],"pk6"=>$planempr[$i][$campo5],"nro_pk"=>6,"atributos"=>$atributos,"fk1"=>$fk1,"fk2"=>$fk2,"fktabla"=>$fktabla,"camposrefarray"=>$camposrefarray));
      }
			$this->salida .= "<a href =\"$acc\"><img title=\"EDITAR\" src=\"".GetThemePath()."/images/mantenimiento/modificar.gif\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\" width=\"4%\">";
      if (sizeof($pk[0])==1)
  		{
      	$campo=trim($pk[0][0]);
        $acc1=ModuloGetURL('app','Mantenimiento','user','FormaConfirmarBorrar',array("tabla"=>$tabla,"atributo1"=>$campo,"pk1"=>$planempr[$i][$campo],"nro_pk"=>'1',"atributos"=>$atributos));
      }
      if (sizeof($pk[0])==2)
      {
      	$campo=trim($pk[0][0]);
      	$campo1=trim($pk[0][1]);
        $acc1=ModuloGetURL('app','Mantenimiento','user','FormaConfirmarBorrar',array("tabla"=>$tabla,"atributo1"=>$campo,"atributo2"=>$campo1,"pk1"=>$planempr[$i][$campo],"pk2"=>$planempr[$i][$campo1],"nro_pk"=>2,"atributos"=>$atributos));
      }
      if (sizeof($pk[0])==3)
      {
      	$campo=trim($pk[0][0]);
      	$campo1=trim($pk[0][1]);
      	$campo2=trim($pk[0][2]);
        $acc1=ModuloGetURL('app','Mantenimiento','user','FormaConfirmarBorrar',array("tabla"=>$tabla,"atributo1"=>$campo,"atributo2"=>$campo1,"atributo3"=>$campo2,"pk1"=>$planempr[$i][$campo],"pk2"=>$planempr[$i][$campo1],"pk3"=>$planempr[$i][$campo2],"nro_pk"=>3,"atributos"=>$atributos));
      }
      if (sizeof($pk[0])==4)
      {
      	$campo=trim($pk[0][0]);
      	$campo1=trim($pk[0][1]);
      	$campo2=trim($pk[0][2]);
      	$campo3=trim($pk[0][3]);
        $acc1=ModuloGetURL('app','Mantenimiento','user','FormaConfirmarBorrar',array("tabla"=>$tabla,"atributo1"=>$campo,"atributo2"=>$campo1,"atributo3"=>$campo2,"atributo4"=>$campo3,"pk1"=>$planempr[$i][$campo],"pk2"=>$planempr[$i][$campo1],"pk3"=>$planempr[$i][$campo2],"pk4"=>$planempr[$i][$campo3],"nro_pk"=>4,"atributos"=>$atributos));
      }
      if (sizeof($pk[0])==5)
      {
      	$campo=trim($pk[0][0]);
      	$campo1=trim($pk[0][1]);
      	$campo2=trim($pk[0][2]);
      	$campo3=trim($pk[0][3]);
      	$campo4=trim($pk[0][4]);
        $acc1=ModuloGetURL('app','Mantenimiento','user','FormaConfirmarBorrar',array("tabla"=>$tabla,"atributo1"=>$campo,"atributo2"=>$campo1,"atributo3"=>$campo2,"atributo4"=>$campo3,"atributo5"=>$campo4,"pk1"=>$planempr[$i][$campo],"pk2"=>$planempr[$i][$campo1],"pk3"=>$planempr[$i][$campo2],"pk4"=>$planempr[$i][$campo3],"pk5"=>$planempr[$i][$campo4],"nro_pk"=>5,"atributos"=>$atributos));
      }
      if (sizeof($pk[0])==6)
      {
      	$campo=trim($pk[0][0]);
      	$campo1=trim($pk[0][1]);
      	$campo2=trim($pk[0][2]);
      	$campo3=trim($pk[0][3]);
      	$campo4=trim($pk[0][4]);
      	$campo5=trim($pk[0][5]);
        $acc1=ModuloGetURL('app','Mantenimiento','user','FormaConfirmarBorrar',array("tabla"=>$tabla,"atributo1"=>$campo,"atributo2"=>$campo1,"atributo3"=>$campo2,"atributo4"=>$campo3,"atributo5"=>$campo4,"atributo6"=>$campo5,"pk1"=>$planempr[$i][$campo],"pk2"=>$planempr[$i][$campo1],"pk3"=>$planempr[$i][$campo2],"pk4"=>$planempr[$i][$campo3],"pk5"=>$planempr[$i][$campo4],"pk6"=>$planempr[$i][$campo5],"nro_pk"=>6,"atributos"=>$atributos));
      }
			$this->salida .= "<a href =\"$acc1\"><img title=\"BORRAR\" src=\"".GetThemePath()."/images/mantenimiento/borrar.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "</td>";
      $ciclo1=sizeof($atributos);
			$k=0;
     	$k2=0;
      while($k<$ciclo1)
      {
				$this->salida .= "<td align=\"center\">";
        $this->salida .= "".$planempr[$i][$atributos[$k][nombre_campo]]."";
        $this->salida .= "</td>";
        $k++;
      }
/*  		$this->salida .= "<td>";
			$this->salida .= "<center><a href =$funcion>EDITAR</a></center>";
			$this->salida .= "</td>";*/
	    $this->salida .= "</tr>";
			$i++;
		}
		if(empty($planempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"9\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ REGISTRO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
 		$this->salida .= "      </table>";
    $this->salida .= "			<br>";
		$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\">";
    $this->salida .= "      <tr>";
    $this->salida .= "      <td align=\"center\">";
    $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"insertar\" value=\"INSERTAR\">";
    $this->salida .= "      </td>";
    //$this->salida .= "      </tr>";
		$this->salida .= "  </form>";
		//$this->salida .= "  </td></tr>";
		//$this->salida .= "  </table>";
		//$this->salida .= "  <tr>";
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaListadoTablasMantenimiento');
		$this->salida .= "  <form name=\"contratacion1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </form>";

		//$this->salida .= "  </td></tr>";
		$this->salida .= "  	</table>";
		$this->salida .= "  <br>";
		$var=$this->RetornarBarraDatos();
		if(!empty($var))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','Mantenimiento','user','',
		array('codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"ctradescri\" value=\"".$_REQUEST['ctradescri']."\" maxlength=\"60\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','Mantenimiento','user','');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
    return true;
  }

	//Forma Confirmar
  function FormaConfirmar($acc,$rows)
  {
		$this->salida .= "<BR><center>SE HAN ".$acc." EN LA TABLA DE MANTENIMIENTO ".$rows." REGISTROS.</center>";
		$this->salida .= "<BR><center>";
		$this->salida .= "  <table>";
		$this->salida .= "  <tr>";
		$accion=ModuloGetURL('app','Mantenimiento','user','PrincipalMantenimiento');
		$this->salida .= "  <form name=\"mantenimiento1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td>";
		$this->salida .= " <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"ACEPTAR\">";
    $this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= "</center>";
		return true;
  }

  //forma para la confirmación del borrado de un registro
  function FormaConfirmarBorrar()
  {
		$this->salida .= "<BR><center>SE ELIMINARÁ UN REGISTRO DE LA TABLA ".$_REQUEST['tabla'].".</center>";
		$this->salida .= "<BR><center>";
		$this->salida .= "  <table>";
		$this->salida .= "  <tr>";
  	if ($_REQUEST['nro_pk']==1)
    {
      $atributo1=$_REQUEST['atributo1'];
      $pk1=$_REQUEST['pk1'];
      $nro_pk=$_REQUEST['nro_pk'];
      $tabla=$_REQUEST['tabla'];
			$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaBorrar',array("nro_pk"=>$nro_pk,"tabla"=>$tabla,"atributo1"=>$atributo1,"pk1"=>$pk1));
    }
		else
  	if ($_REQUEST['nro_pk']==2)
    {
      $atributo1=$_REQUEST['atributo1'];
      $atributo2=$_REQUEST['atributo2'];
      $pk1=$_REQUEST['pk1'];
      $pk2=$_REQUEST['pk2'];
      $nro_pk=$_REQUEST['nro_pk'];
      $tabla=$_REQUEST['tabla'];
			$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaBorrar',array("nro_pk"=>$nro_pk,"tabla"=>$tabla,"atributo1"=>$atributo1,"atributo2"=>$atributo2,"pk1"=>$pk1,"pk2"=>$pk2));
    }
		else
  	if ($_REQUEST['nro_pk']==3)
    {
      $atributo1=$_REQUEST['atributo1'];
      $atributo2=$_REQUEST['atributo2'];
      $atributo3=$_REQUEST['atributo3'];
      $pk1=$_REQUEST['pk1'];
      $pk2=$_REQUEST['pk2'];
      $pk3=$_REQUEST['pk3'];
      $nro_pk=$_REQUEST['nro_pk'];
      $tabla=$_REQUEST['tabla'];
			$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaBorrar',array("nro_pk"=>$nro_pk,"tabla"=>$tabla,"atributo1"=>$atributo1,"atributo2"=>$atributo2,"atributo3"=>$atributo3,"pk1"=>$pk1,"pk2"=>$pk2,"pk3"=>$pk3));
    }
		else
  	if ($_REQUEST['nro_pk']==4)
    {
      $atributo1=$_REQUEST['atributo1'];
      $atributo2=$_REQUEST['atributo2'];
      $atributo3=$_REQUEST['atributo3'];
      $atributo4=$_REQUEST['atributo4'];
      $pk1=$_REQUEST['pk1'];
      $pk2=$_REQUEST['pk2'];
      $pk3=$_REQUEST['pk3'];
      $pk4=$_REQUEST['pk4'];
      $nro_pk=$_REQUEST['nro_pk'];
      $tabla=$_REQUEST['tabla'];
			$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaBorrar',array("nro_pk"=>$nro_pk,"tabla"=>$tabla,"atributo1"=>$atributo1,"atributo2"=>$atributo2,"atributo3"=>$atributo3,"atributo4"=>$atributo4,"pk1"=>$pk1,"pk2"=>$pk2,"pk3"=>$pk3,"pk4"=>$pk4));
    }
		else
  	if ($_REQUEST['nro_pk']==5)
    {
      $atributo1=$_REQUEST['atributo1'];
      $atributo2=$_REQUEST['atributo2'];
      $atributo3=$_REQUEST['atributo3'];
      $atributo4=$_REQUEST['atributo4'];
      $atributo5=$_REQUEST['atributo5'];
      $pk1=$_REQUEST['pk1'];
      $pk2=$_REQUEST['pk2'];
      $pk3=$_REQUEST['pk3'];
      $pk4=$_REQUEST['pk4'];
      $pk5=$_REQUEST['pk5'];
      $nro_pk=$_REQUEST['nro_pk'];
      $tabla=$_REQUEST['tabla'];
			$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaBorrar',array("nro_pk"=>$nro_pk,"tabla"=>$tabla,"atributo1"=>$atributo1,"atributo2"=>$atributo2,"atributo3"=>$atributo3,"atributo4"=>$atributo4,"atributo5"=>$atributo5,"pk1"=>$pk1,"pk2"=>$pk2,"pk3"=>$pk3,"pk4"=>$pk4,"pk5"=>$pk5));
    }
		else
  	if ($_REQUEST['nro_pk']==6)
    {
      $atributo1=$_REQUEST['atributo1'];
      $atributo2=$_REQUEST['atributo2'];
      $atributo3=$_REQUEST['atributo3'];
      $atributo4=$_REQUEST['atributo4'];
      $atributo5=$_REQUEST['atributo5'];
      $atributo6=$_REQUEST['atributo6'];
      $pk1=$_REQUEST['pk1'];
      $pk2=$_REQUEST['pk2'];
      $pk3=$_REQUEST['pk3'];
      $pk4=$_REQUEST['pk4'];
      $pk5=$_REQUEST['pk5'];
      $pk6=$_REQUEST['pk6'];
      $nro_pk=$_REQUEST['nro_pk'];
      $tabla=$_REQUEST['tabla'];
			$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaBorrar',array("nro_pk"=>$nro_pk,"tabla"=>$tabla,"atributo1"=>$atributo1,"atributo2"=>$atributo2,"atributo3"=>$atributo3,"atributo4"=>$atributo4,"atributo5"=>$atributo5,"atributo6"=>$atributo6,"pk1"=>$pk1,"pk2"=>$pk2,"pk3"=>$pk3,"pk4"=>$pk4,"pk5"=>$pk5,"pk6"=>$pk6));
    }
		$this->salida .= "  <form name=\"mantenimiento1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td>";
		$this->salida .= " <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"ACEPTAR\">";
    $this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$accion1=ModuloGetURL('app','Mantenimiento','user','LlamaFormaDatosBrowser');
		$this->salida .= "  <form name=\"mantenimiento1\" action=\"$accion1\" method=\"post\">";
		$this->salida .= "  <td>";
		$this->salida .= " <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
    $this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= "</center>";
		return true;
  }

  function FormaMantenimiento($nombretabla)
  {
		$this->salida = ThemeAbrirTabla('ESTADO DE LAS TABLAS EN MANTENIMIENTO','90%');
		$accion=ModuloGetURL('app','Mantenimiento','user','');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['mantenimiento']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"1%\" >No.</td>";
		$this->salida .= "      <td width=\"1%\">NOMBRE TABLA</td>";
		$this->salida .= "      <td width=\"1%\">ESTADO MANT.</td>";
/*    if ($this->vercolumna=='y')
			$this->salida .= "      <td width=\"1%\">N/B</td>";*/
		$this->salida .= "      </tr>";
		$planempr=$this->TraerDatosMantenimiento($nombretabla);
		$i=0;
		$j=0;
		$ciclo=sizeof($planempr);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$j=0;
			}
			$this->salida .= "<td align=\"center\" width=\"1%\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"left\" width=\"93%\">";
   	  $funcion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaVerCamposTabla',array("tablename"=>$planempr[$i]['tablename'],"comentario"=>$planempr[$i]['observaciones']));
			//$this->salida .= "<img title=\"".$planempr[$i]['observaciones']."\" src=\"".GetThemePath()."/images/mantenimiento/comentario.png\" border=\"0\">&nbsp;&nbsp;<a href =$funcion title=\"".$planempr[$i]['observaciones']."\">".$planempr[$i]['tablename']."</a>";//tablename
			$this->salida .= "<img title=\"".$planempr[$i]['observaciones']."\" src=\"".GetThemePath()."/images/mantenimiento/comentario.png\" border=\"0\">&nbsp;&nbsp;".$planempr[$i]['tablename']."";//tablename			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\" width=\"2%\">";
			if($planempr[$i]['sw_tipo_mantenimiento'] == 0)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Mantenimiento','user','CambiarEstadoMantenimiento',
				array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'ctradescri'=>$_REQUEST['ctradescri'],
				'codigoctra'=>$_REQUEST['codigoctra'],'table'=>$planempr[$i]['tablename'],'estado'=>$planempr[$i]['sw_tipo_mantenimiento'])) ."\">
				<img title=\"TABLA SIN MANTENIMIENTO\" src=\"".GetThemePath()."/images/mantenimiento/tabla_sin_mantenimiento.gif\" border=\"0\"></a>";
			}
			else
        if($planempr[$i]['sw_tipo_mantenimiento'] == 1)
        {
          $this->salida .= "<a href=\"". ModuloGetURL('app','Mantenimiento','user','CambiarEstadoMantenimiento',
          array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'ctradescri'=>$_REQUEST['ctradescri'],
          'codigoctra'=>$_REQUEST['codigoctra'],'table'=>$planempr[$i]['tablename'],'estado'=>$planempr[$i]['sw_tipo_mantenimiento'])) ."\">
          <img title=\"TABLA CON MANTENIMIENTO\" src=\"".GetThemePath()."/images/mantenimiento/tabla_con_mantenimiento.png\" border=\"0\"></a>";
        }
        else
          if ($planempr[$i]['sw_tipo_mantenimiento']==2)
            $this->salida .="<img title=\"NUEVA SIN DEFINIR MANTENIMIENTO\" src=\"".GetThemePath()."/images/mantenimiento/tabla_nueva_sin_definir_tipo_mantenimiento.png\" border=0>";
          else
            if ($planempr[$i]['sw_tipo_mantenimiento']==3)
              $this->salida .="<img title=\"TABLA BORRADA EN EL SISTEMA\" src=\"".GetThemePath()."/images/mantenimiento/tabla_borrada_del_sistema.gif\" border=0>";
			$this->salida .= "</td>";
	    $this->salida .= "</tr>";
			$i++;
		}
		if(empty($planempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"9\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ REGISTRO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
 		$this->salida .= "      </table>";
    //$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"center\">";
    //$this->salida .= "      <tr>";
    //$this->salida .= "      <td align=\"center\"><br>";
    //$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO EMPRESA\">";
    //$this->salida .= "      </td>";
    //$this->salida .= "      </tr>";
    //$this->salida .= "      </table>";
		//$this->salida .= "  </fieldset>";
		$this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <br><td align=\"center\">";
		$accion=ModuloGetURL('app','Mantenimiento','user','Principalmantenimiento');
		$this->salida .= "  <form name=\"mantenimiento1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"VOLVER\">";
		$this->salida .= "  </form>";
    $this->salida .= "  </td>";
    //
    $this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('app','Mantenimiento','user','RegeneracionTablas2');
		$this->salida .= "  <form name=\"mantenimiento2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"REGENERAR\">";
		$this->salida .= "  </form>";
		$this->salida .= "  </td>";
    $this->salida .= "  </tr>";
		$this->salida .= "  </table>";
    //
		$this->salida .= "  </tr>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  </form><br>";
		$var=$this->RetornarBarraClientes();
		if(!empty($var))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaMantenimiento',
		array('nombretabla'=>$_REQUEST['nombretabla']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NOMBRE TABLA:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"nombretabla\" value=\"".$_REQUEST['nombretabla']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaMantenimiento');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
    return true;
 }

	//FormaListadoTablasMantenimiento()
	function FormaListadoTablasMantenimiento($nombretabla)
  {
		$this->salida = ThemeAbrirTabla('LISTADO DE TABLAS MARCADAS PARA MANTENIMIENTO.','90%');
		$accion=ModuloGetURL('app','Mantenimiento','user','');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['mantenimiento']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"1%\" >No.</td>";
		$this->salida .= "      <td width=\"1%\">NOMBRE TABLA</td>";
		$this->salida .= "      <td width=\"1%\">BROWSER</td>";
		$this->salida .= "      <td width=\"1%\">MANTENIMIENTO</td>";
		$this->salida .= "      </tr>";
		$planempr=$this->TraerDatosTablasMantenimiento($nombretabla);
		$i=0;
		$j=0;
		$ciclo=sizeof($planempr);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$j=0;
			}
			$this->salida .= "<td align=\"center\" width=\"1%\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"left\" width=\"93%\">";
			$this->salida .= "<img title=\"".$planempr[$i]['observaciones']."\" src=\"".GetThemePath()."/images/mantenimiento/comentario.png\" border=\"0\">&nbsp;&nbsp;<a href =\"".ModuloGetURL('app','Mantenimiento','user','LlamaFormaVerCampos',array("tablename"=>$planempr[$i]['tablename'],"schemaname"=>public))."\" title=\"".$planempr[$i]['observaciones']."\">".$planempr[$i]['tablename']."</a>";//tablename
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\" width=\"2%\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Mantenimiento','user','LlamaFormaDatosBrowser',
				array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'ctradescri'=>$_REQUEST['ctradescri'],
				'codigoctra'=>$_REQUEST['codigoctra'],'table'=>$planempr[$i]['tablename'])) ."\">
				<img title=\"VER DATOS DE LA TABLA\" src=\"".GetThemePath()."/images/mantenimiento/browser.png\" border=\"0\"></a>";

			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\" width=\"2%\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Mantenimiento','user','LlamaFormaRealizarMantenimiento',
				array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'tablename'=>$planempr[$i]['tablename'],'schemaname'=>'public')) ."\">
				<img title=\"REALIZAR MANTENIMIENTO\" src=\"".GetThemePath()."/images/mantenimiento/mantenimiento.gif\" border=\"0\"></a>";

			$this->salida .= "</td>";
	    $this->salida .= "</tr>";
			$i++;
		}
		if(empty($planempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"9\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ REGISTRO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
 		$this->salida .= "      </table>";
    //$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"center\">";
    //$this->salida .= "      <tr>";
    //$this->salida .= "      <td align=\"center\"><br>";
    //$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO EMPRESA\">";
    //$this->salida .= "      </td>";
    //$this->salida .= "      </tr>";
    //$this->salida .= "      </table>";
		//$this->salida .= "  </fieldset>";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <br><td align=\"center\">";
		$accion=ModuloGetURL('app','Mantenimiento','user','Principalmantenimiento');
		$this->salida .= "  <form name=\"mantenimiento1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"VOLVER\">";
		$this->salida .= "  </form>";
    $this->salida .= "  </td>";
    //
    $this->salida .= "  </tr>";
		$this->salida .= "  </table>";
    //
		$this->salida .= "  </tr>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  </form><br>";
		$var=$this->RetornarBarraClientes();
		if(!empty($var))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaListadoTablasMantenimiento',
		array('nombretabla'=>$_REQUEST['nombretabla']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NONMBRE TABLA:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"nombretabla\" value=\"".$_REQUEST['nombretabla']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','Mantenimiento','user','');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
    return true;
  }

	//forma insertar datos
	function FormaInsertarDatos($tabla,$atributos,$fk1,$fk2,$camposrefarray,$tablaref,$nombre_campo1,$dato_1,$nombre_campo2,$dato_2,$nombre_campo3,$dato_3,$nombre_campo4,$dato_4,$nombre_campo5,$dato_5)
  {
  echo $dato_1.'AAA'.$dato_2.'AAA'.$dato_3.'AAA'.$dato_4.'AAA'.$dato_5;
		//echo $tabla;
    $accion=ModuloGetURL('app','Mantenimiento','user','InsertarDatos', array('tabla'=>$tabla,"campos"=>$atributos));
    $this->salida .= ThemeAbrirTabla('MANTENIMIENTO - INGRESAR REGISTRO');
    $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
    $this->salida .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
    $this->salida .= "<tr class=\"modulo_table_list_title\">";
    $this->salida .= "<td align = center >INGRESO DE DATOS DE LA TABLA: $tabla</td>";
    $this->salida .= "</tr>";
    $this->salida .= "<tr class=\"modulo_list_claro\" >";
    $this->salida .= "<td width=\"40%\" >";
    $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
    $this->salida .= "<tr><td>";
    $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
		for($i=0; $i<sizeof($fk2);$i++)
    {
      if (trim($fk2[$i])==trim($nombre_campo1))
        $this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaInsertarFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value=\"".$dato_1."\" size=\"35\" maxlength=\"55\"></td></tr>";
      else
        if (trim($fk2[$i])==trim($nombre_campo2))
          $this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaInsertarFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value=\"".$dato_2."\"  size=\"35\" maxlength=\"55\"></td></tr>";
        else
        if (trim($fk2[$i])==trim($nombre_campo3))
          $this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaInsertarFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value=\"".$dato_3."\" size=\"35\" maxlength=\"55\"></td></tr>";
        else
          if (trim($fk2[$i])==trim($nombre_campo4))
            $this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaInsertarFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value=\"".$dato_4."\" size=\"35\" maxlength=\"55\"></td></tr>";
          else
            if (trim($fk2[$i])==trim($nombre_campo5))
              $this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaInsertarFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value=\"".$dato_5."\" size=\"35\" maxlength=\"55\"></td></tr>";
            else
              $this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaInsertarFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" size=\"35\" maxlength=\"55\"></td></tr>";
    }

		for($i=0; $i<sizeof($atributos);$i++)
		{
			$n=0;
			while($n<sizeof($fk2))
			{
					if ($fk2[$n]!=$atributos[$i]['nombre_campo'])
					{
							$x=false;
							$f=0;
							while($f<sizeof($fk2))
							{
									if ($fk2[$f]==$atributos[$i]['nombre_campo'])
									{
                    $x=true;
                  }
									$f++;
							}
							if($x==false)
							{
								$this->salida .= "<tr><td class=\"".$this->SetStyle("".$atributos[$i]['nombre_campo']."")."\">".$atributos[$i]['nombre_campo'].": </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$datos[0][$atributos[$i]['nombre_campo']]."' size=\"35\" maxlength=\"55\"></td></tr>";
								$n=sizeof($fk2);
							}
					}
					$n++;
			}
		}
    $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
    $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\"></td>";
    $this->salida .= "</form>";
    $actionM=ModuloGetURL('app','Mantenimiento','user','LlamaFormaDatosBrowser');
    $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
    $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
    $this->salida .= "</tr>";
    $this->salida .= "</table></td></tr>";
    $this->salida .= "</td></tr></table>";
    $this->salida .= "</td>";
    $this->salida .= "</table>";
    $this->salida .= "</td>";
    $this->salida .= "</tr>";
    $this->salida .= "  </table>";
    $this->salida .= "            </form>";
    //mensaje//
    $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
    $this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= "  </table>";
    $this->salida .= ThemeCerrarTabla();
		return true;
  }

  function FormaEditar($tabla,$datos,$atributos,$fk1,$fk2,$tablaref,$camposrefarray,$dato_1,$nombre_campo1,$dato_2,$nombre_campo2,$dato_3,$nombre_campo3,$dato_4,$nombre_campo4,$dato_5,$nombre_campo5)
  {

// 		print_r($fk2);
//     echo '<br><br>ATRIBUTOS'.$tablaref.'TTT'.$nombre_campo1.$dato_1;
// 		print_r($atributos);

    $accion=ModuloGetURL('app','Mantenimiento','user','EditarDatos', array("tabla"=>$tabla,"datos"=>$datos,"atributos"=>$atributos,"fk1"=>$fk1,"fk2"=>$fk2,"tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"dato1"=>$dato_1,"nombre_campo1"=>$nombre_campo1,"dato2"=>$dato_2,"nombre_campo2"=>$nombre_campo2,"dato3"=>$dato_3,"nombre_campo3"=>$nombre_campo3,"dato4"=>$dato_4,"nombre_campo4"=>$nombre_campo4,"dato5"=>$dato_5,"nombre_campo5"=>$nombre_campo5));
    $this->salida .= ThemeAbrirTabla('MANTENIMIENTO - EDICIÓN DE REGISTROS');
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
    $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
    $this->salida .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
    $this->salida .= "<tr class=\"modulo_table_list_title\">";
    $this->salida .= "<td align = left >INGRESO DE DATOS DE LA TABLA: $tabla</td>";
    $this->salida .= "</tr>";
    $this->salida .= "<tr class=\"modulo_list_claro\" >";
    $this->salida .= "<td width=\"40%\" >";
    $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
    $this->salida .= "<tr><td>";
    $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";

		for($i=0; $i<sizeof($fk2);$i++)
    {
			if (sizeof($fk2)>0)
			{
			 if (trim($fk2[$i])==trim($nombre_campo1))
				$this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$dato_1."' size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
       else
				if (trim($fk2[$i])==trim($nombre_campo2))
					$this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$dato_2."' size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
				else
				if (trim($fk2[$i])==trim($nombre_campo3))
					$this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$dato_3."' size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
				else
					if (trim($fk2[$i])==trim($nombre_campo4))
						$this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$dato_4."' size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
					else
						if (trim($fk2[$i])==trim($nombre_campo5))
							$this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$dato_5."' size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
						else
						  $this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$datos[0][$fk2[$i]]."' size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
				$val=true;
			}
			else
				if (sizeof($fk2)<=0)
						$this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\">".$fk2[$i].": </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$datos[0][$fk2[$i]]."' size=\"35\" maxlength=\"55\"></td></tr>";
    }
		$l=$i+1;
    $m=0;
		for($i=$l; $i<sizeof($atributos)+$l; $i++)
		{
			$n=0;
			while($n<sizeof($fk2))
			{
					if ($fk2[$n]!=$atributos[$m]['nombre_campo'])
					{
							$x=false;
							$f=0;
							while($f<sizeof($fk2))
							{
									if ($fk2[$f]==$atributos[$m]['nombre_campo'])
									{
                   	$x=true;
                  }
									$f++;
							}
							if($x==false)
							{
								$this->salida .= "<tr><td class=\"".$this->SetStyle("".$atributos[$m]['nombre_campo']."")."\">".$atributos[$m]['nombre_campo'].": </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$datos[0][$atributos[$m]['nombre_campo']]."' size=\"35\" maxlength=\"55\"></td></tr>";
								$n=sizeof($fk2);
							}
					}
					$n++;
			}
      $m++;
		}

    $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
    $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"ACTUALIZAR\"></td>";
    $this->salida .= "</form>";
    $actionM=ModuloGetURL('app','Mantenimiento','user','LlamaFormaDatosBrowser');
    $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
    $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
    $this->salida .= "</tr>";
    $this->salida .= "</table></td></tr>";
    $this->salida .= "</td></tr></table>";
    $this->salida .= "</td>";
    $this->salida .= "</table>";
    $this->salida .= "</td>";
    $this->salida .= "</tr>";
    $this->salida .= "  </table>";
    $this->salida .= "            </form>";
    //mensaje//
/*    $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
    $this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= "  </table>";*/
    $this->salida .= ThemeCerrarTabla();
		return true;
  }

	//FormaRealizarMantenimiento()
  function FormaRealizarMantenimiento($tabla,$atributos,$fk1,$fk2,$camposrefarray,$pk,$tablasref)
 	{
    //tablas referencia con el alias para constriur la consulta
  	//print_r($tablasref); exit;
    //tablas referenciadas
		$reftablas=$_SESSION['mantenimiento']['tablasref'];
    //campos referenciados con distinto nombre en otra tabla
    $refdisnombre=$_SESSION['mantenimiento']['refdisnombre1'];
    $refdisnombre2=$_SESSION['mantenimiento']['refdisnombre2'];
    //print_r($refdisnombre);

    $accion=ModuloGetURL('app','Mantenimiento','user','InsertarDatos', array("tabla"=>$tabla,"campos"=>$campos));
    $this->salida .= ThemeAbrirTabla('MANTENIMIENTO - INSERTAR REGISTRODDD');
    $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
    $this->salida .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
    $this->salida .= "<tr class=\"modulo_table_list_title\">";
    $this->salida .= "<td align = left >INGRESO DE DATOS DE LA TABLA: $tabla</td>";
    $this->salida .= "</tr>";
    $this->salida .= "<tr class=\"modulo_list_claro\" >";
    $this->salida .= "<td width=\"40%\" >";
    $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
    $this->salida .= "<tr><td>";
    $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";

		//
		for($i=0; $i<sizeof($fk2);$i++)
    {
		 if (trim($fk2[$i])==trim($nombre_campo1))
			  $this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$dato_1."' size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
       else
				if (trim($fk2[$i])==trim($nombre_campo2))
					$this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$dato_2."' size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
  				else
	  			if (trim($fk2[$i])==trim($nombre_campo3))
		  			$this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$dato_3."' size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
			  	else
				  	if (trim($fk2[$i])==trim($nombre_campo4))
					  	$this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$dato_4."' size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
					  else
					  	if (trim($fk2[$i])==trim($nombre_campo5))
						  	$this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$dato_5."' size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
						  else
						    $this->salida .= "<tr><td class=\"".$this->SetStyle("".$fk2[$i]."")."\"><a href=\"".ModuloGetURL('app','Mantenimiento','user','llamaFormaFk',array("tablaref"=>$tablaref,"camposrefarray"=>$camposrefarray,"nombre_campo1"=>$fk2[$i]))."\">".$fk2[$i]."</a>: </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$datos[0][$fk2[$i]]."' size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
    }

		for($i=0; $i<sizeof($atributos);$i++)
		{
			$n=0;
			while($n<sizeof($fk2))
			{
					if ($fk2[$n]!=$atributos[$i]['nombre_campo'])
					{
							$x=false;
							$f=0;
							while($f<sizeof($fk2))
							{
									if ($fk2[$f]==$atributos[$i]['nombre_campo'])
									{ 	$x=true;		}
									$f++;
							}
							if($x==false)
							{
								$this->salida .= "<tr><td class=\"".$this->SetStyle("".$atributos[$i]['nombre_campo']."")."\">".$atributos[$i]['nombre_campo'].": </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$datos[0][$atributos[$i]['nombre_campo']]."' size=\"35\" maxlength=\"55\"></td></tr>";
								$n=sizeof($fk2);
							}
					}
				$n++;
			}
	  }
		//
		//for($i=0; $i<sizeof($campos);$i++)
    //{
//       for ($i=0; $i<sizeof($campos); $i++)
//       {
// 				$enlace=false;
//         for ($k=0; $k<sizeof($campos); $k++)
//         {
//           for ($j=0; $j<sizeof($pk[$i]); $j++)
//           {
//             $tmp=strcmp(trim($pk[$i][$j]),trim($campos[$k][nombre_campo]));
//             if ($tmp==0)
//             {
//               $enlace=true;
//     					$acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaTraerDatosRef',array("campo"=>$campos[$k][nombre_campo],"reftablas"=>$reftablas,"refcampos"=>$refdisnombre2));
// 							$this->salida .= "<tr><td class=\"".$this->SetStyle("".$campos[$k][nombre_campo]."")."\"><a href=\"$acc\">".$campos[$k][nombre_campo]."</a>&nbsp&nbsp;".$campos[$k][tipo_campo]."&nbsp&nbsp;".$campos[$k][no_nulo].": </td><td> <input type=\"text\" class=\"input-text\" name=\"campo".$k."\" size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
//               $c++;
//             }
// /*            else
//             {
//               echo sizeof($refdisnombre);
//               for($l=0; $l<sizeof($refdisnombre); $l++)
//               {
//                 $tmp=strcmp(trim($campos[$k][nombre_campo]),trim($refdisnombre[$l]));
//                 if ($tmp==0)
//                   {
//                     $enlace=true;
//                     $this->salida .= "<tr><td class=\"".$this->SetStyle("".$campos[$k][nombre_campo]."")."\"><a href=\"$acc\">".$campos[$k][nombre_campo]."</a>&nbsp&nbsp;".$campos[$k][tipo_campo]."&nbsp&nbsp;".$campos[$k][no_nulo].": </td><td> <input type=\"text\" class=\"input-text\" name=\"campo".$k."\" size=\"35\" maxlength=\"55\" readonly=\"true\"></td></tr>";
//                   }
//                }
//             }*/
//           }
//         }//for de $k
// /*        if(!$enlace)*/
//           //$this->salida .= "<tr><td class=\"".$this->SetStyle("".$campos[$i][nombre_campo]."")."\">".$campos[$i][nombre_campo]."&nbsp&nbsp;".$campos[$i][tipo_campo]."&nbsp&nbsp;".$campos[$i][no_nulo].": </td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" size=\"35\" maxlength=\"55\"></td></tr>";
//       }

    //}
    $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
    $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\"></td>";
    $this->salida .= "</form>";
    $actionM=ModuloGetURL('app','Mantenimiento','user','FormaListadoTablasMantenimiento');
    $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
    $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
    $this->salida .= "</tr>";
    $this->salida .= "</table></td></tr>";
    $this->salida .= "</td></tr></table>";
    $this->salida .= "</td>";
    $this->salida .= "</table>";
    $this->salida .= "</td>";
    $this->salida .= "</tr>";
    $this->salida .= "  </table>";
    $this->salida .= "            </form>";
    //mensaje//
    $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
    $this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= "  </table>";
    $this->salida .= ThemeCerrarTabla();
		unset($_SESSION['mantenimiento']['refdisnombre']);
		return true;
  }

	//FormaTraerDatosref()
  function FormaTraerDatosref($atributos,$datos,$tabla,$selcampo)
  {
		$this->salida = ThemeAbrirTabla('BASE DE DATOS: '.$_SESSION['mantenimiento']['db'].' / TABLA: '.$tabla,'90%');
		//$acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaRealizarMantenimiento',array("datos"=>$planempr[$i][$atributos[$k][nombre_campo]],"campos"=>$atributos));
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		if(sizeof($atributos)<5)
			$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		else
			$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['mantenimiento']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		if(sizeof($atributos)<5)
			$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
    else
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
    $this->salida .= "      <td width=\"1%\" >No.</td>";
		$i=0;
		$ciclo=sizeof($atributos);
		while($i<$ciclo)
		{
    	if (strcmp($atributos[$i][nombre_campo],$selcampo)==0)
			  $this->salida .= "      <td width=\"1%\" class=\"nivel1_oscuro\">".$atributos[$i][nombre_campo]."</td>";
			else
			  $this->salida .= "      <td width=\"1%\">".$atributos[$i][nombre_campo]."</td>";
      $i++;
    }
    $this->salida .= "      <td width=\"1%\" >INCLUIR</td>";
		$this->salida .= "      </tr>";
		$planempr=$datos;
		$i=0;
		$j=0;
		$ciclo=sizeof($planempr);
		while($i<$ciclo)
		{
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "</td>";
      $ciclo1=sizeof($atributos);
			$k=0;
     	$k2=0;
      while($k<$ciclo1)
      {
   		 if ($atributos[$k][nombre_campo]==$selcampo)
       	{
				$this->salida .= "<td align=\"center\" class=\"nivel1_claro\">";
        $this->salida .= "".$planempr[$i][$atributos[$k][nombre_campo]]."";
				$this->salida.= "<input type=\"hidden\" name=\"campo".$atributos[$k][nombre_campo]."\" size=\"60\" class=\"input-text\" value=\"".$planempr[$i][$atributos[$k][nombre_campo]]."\">";
        $this->salida .= "</td>";
				}
			else
 	    	{
				$this->salida .= "<td align=\"center\">";
        $this->salida .= "".$planempr[$i][$atributos[$k][nombre_campo]]."";
        $this->salida.= "<input type=\"hidden\" name=\"campo".$atributos[$k][nombre_campo]."\" size=\"60\" class=\"input-text\" value=\"".$planempr[$i][$atributos[$k][nombre_campo]]."\">";
				$this->salida .= "</td>";
  	    }
       $k++;
      }
  		//$this->salida .= "<td class=\"nivel1_oscuro\">";
			$this->salida .= "<td>";
			$this->salida .= "<center>";
			$this->salida .= "<a href =\"".ModuloGetURL('app','Mantenimiento','user','LlamaFormaRealizarMantenimiento')."\"><img title=\"ADICIONAR\" src=\"".GetThemePath()."/images/mantenimiento/incluir.png\" border=\"0\"></a>";
			//$this->salida .= "<input type=\"radio\" name=\"seleccion\" align=\"center\" value=\"0\">";
			$this->salida .= "</center>";
			$this->salida .= "</td>";
	    $this->salida .= "</tr>";
			$i++;
		}
		if(empty($planempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"9\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ REGISTRO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
 		$this->salida .= "      </table>";
    $this->salida .= "			<br>";
		$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
    $this->salida .= "      <tr>";
    $this->salida .= "      <td align=\"center\">";
    $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"insertar\" value=\"INCLUIR\">";
    $this->salida .= "      </td>";
    //$this->salida .= "      </tr>";
		$this->salida .= "  </form>";
		//$this->salida .= "  </td></tr>";
		//$this->salida .= "  </table>";
		//$this->salida .= "  <tr>";
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaRealizarMantenimiento');
		$this->salida .= "  <form name=\"contratacion1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </form>";

		//$this->salida .= "  </td></tr>";
		$this->salida .= "  	</table>";
		$this->salida .= "  <br>";
		$var=$this->RetornarBarraDatosIncluir();
		if(!empty($var))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','Mantenimiento','user','',
		array('codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"ctradescri\" value=\"".$_REQUEST['ctradescri']."\" maxlength=\"60\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','Mantenimiento','user','');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
    return true;
  }

  function FormaSecuencias($datos)
  {
  	$DatosDB=$this->GetDatosConexionActual();
    if (empty($_SESSION['mantenimiento']['db']))
			$_SESSION['mantenimiento']['db']=$DatosDB['dbname'];
		$this->salida = ThemeAbrirTabla('LISTADO DE SECUENCIAS DE LA BASE DE DATOS: '.$DatosDB['dbname'],'90%');
		$accion=ModuloGetURL('app','Mantenimiento','user','');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['mantenimiento']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"1%\" >No.</td>";
		$this->salida .= "      <td width=\"1%\">NOMBRE SECUENCIA</td>";
		$this->salida .= "      <td width=\"1%\">PROPIETARIO</td>";
		$this->salida .= "      <td width=\"1%\">COMENTARIO</td>";
		$this->salida .= "      </tr>";
		//$planempr=$this->TraerDatosTablasMantenimiento();
		$planempr=$datos;
		$i=0;
		$j=0;
		$ciclo=sizeof($planempr);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$j=0;
			}
			$this->salida .= "<td align=\"center\" width=\"1%\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "</td>";
			$this->salida .= "<td width=\"80%\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Mantenimiento','user','LlamaFormaDatosSecuencia', array('nombre_secuencia'=>$planempr[$i]['seqname'])) ."\">
				<img title=\"VER DATOS SECUENCIA\" src=\"".GetThemePath()."/images/mantenimiento/secuencias.png\" border=\"0\">&nbsp&nbsp;".$planempr[$i]['seqname']."</a>";
			$this->salida .= "</td>";
			$this->salida .= "<td width=\"10%\">";
			$this->salida .= "".$planempr[$i]['seqowner']."";
			$this->salida .= "</td>";
			$this->salida .= "<td  width=\"10%\">";
			$this->salida .= "".$planempr[$i]['seqcomment']."";
			$this->salida .= "</td>";
	    $this->salida .= "</tr>";
			$i++;
		}
		if(empty($planempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"9\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ REGISTRO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
 		$this->salida .= "      </table>";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <br><td align=\"center\">";
		$accion=ModuloGetURL('app','Mantenimiento','user','Principalmantenimiento');
		$this->salida .= "  <form name=\"mantenimiento1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"VOLVER\">";
		$this->salida .= "  </form>";
    $this->salida .= "  </td>";
    //
    $this->salida .= "  </tr>";
		$this->salida .= "  </table>";
    //
		$this->salida .= "  </tr>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  </form><br>";
		$var=$this->RetornarBarraSecuencias();
		if(!empty($var))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaSecuencias',
		array('nombre'=>$_REQUEST['nombre']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"nombre\" value=\"".$_REQUEST['nombre']."\" maxlength=\"30\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','Mantenimiento','user','');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

	//FormaFk
	function FormaFk($datos,$atributos,$tabla,$nombre_campo1,$nombre_campo2,$nombre_campo3,$nombre_campo4,$nombre_campo5)
	{
		//print_r($datos);
		//echo "<br><br>";
		//print_r($atributos); exit;
    if (empty($tabla) || empty($atributos) || empty($datos))
    {
			$tabla=$_SESSION['mantenimiento']['tabla'];
      $atributos=$_SESSION['mantenimiento']['atributos'];
      $datos=$_SESSION['mantenimiento']['datos'];
    }
		else
		{
			$_SESSION['mantenimiento']['tabla']=$tabla;
      $_SESSION['mantenimiento']['atributos_']=$atributos;
      //$_SESSION['mantenimiento']['datos']=$datos;
		}
  	if (empty($_SESSION['mantenimiento']['db']))
    {
			$conn=$this->GetDatosConexionActual();
			$_SESSION['mantenimiento']['db']=$conn['dbname'];
    }
		$this->salida = ThemeAbrirTabla('BASE DE DATOS: '.$_SESSION['mantenimiento']['db'].' / TABLA: '.$tabla,'90%');
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaInsertarDatos',array("tabla"=>$tabla,"campos"=>$atributos));
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		if(sizeof($atributos)<5)
			$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		else
			$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['mantenimiento']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		if(sizeof($atributos)<5)
			$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
    else
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
	  $this->salida .= "      <td width=\"4%\">No.</td>";
    $this->salida .= "      <td width=\"1%\">SELECCIÓN</td>";
		$i=0;
		$ciclo=sizeof($atributos);
		while($i<$ciclo)
		{
		  $this->salida .= "      <td width=\"1%\">".$atributos[$i][nombre_campo]."</td>";
      $i++;
    }
		$this->salida .= "      </tr>";
		$planempr=$datos;
		$i=0;
		$j=0;
		$ciclo=sizeof($planempr);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\" >";
				$j=0;
			}
			$this->salida .= "<td align=\"center\" width=\"1%\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "</td>";

			$this->salida .= "<td align=\"center\" width=\"1%\">";
      //$acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaEditar',array("tabla"=>$tabla,"atributo1"=>$campo,"pk1"=>$planempr[$i][$campo],"nro_pk"=>'1',"atributos"=>$atributos,"fk1"=>$fk1,"fk2"=>$fk2,"fktabla"=>$fktabla,"camposrefarray"=>$camposrefarray));
			if (empty($nombre_campo2) and empty($nombre_campo3) and empty($nombre_campo4) and empty($nombre_campo5))
         $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaEditar',array("dato1"=>$planempr[$i][$nombre_campo1],"nombre_campo1"=>$nombre_campo1));
			else
  			if (empty($nombre_campo3) and empty($nombre_campo4) and empty($nombre_campo5))
         $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaEditar',array("dato1"=>$planempr[$i][$nombre_campo1],"dato2"=>$planempr[$i][$nombre_campo2],"nombre_campo1"=>$nombre_campo1,"nombre_campo2"=>$nombre_campo2));
  			else
	  			if (empty($nombre_campo4) and empty($nombre_campo5))
		    		$acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaEditar',array("dato1"=>$planempr[$i][$nombre_campo1],"dato2"=>$planempr[$i][$nombre_campo2],"dato3"=>$planempr[$i][$nombre_campo3],"nombre_campo1"=>$nombre_campo1,"nombre_campo2"=>$nombre_campo2,"nombre_campo3"=>$nombre_campo3));
    			else
	    			if (empty($nombre_campo5))
		  		   $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaEditar',array("dato1"=>$planempr[$i][$nombre_campo1],"dato2"=>$planempr[$i][$nombre_campo2],"dato3"=>$planempr[$i][$nombre_campo3],"dato4"=>$planempr[$i][$nombre_campo4],"nombre_campo1"=>$nombre_campo1,"nombre_campo2"=>$nombre_campo2,"nombre_campo3"=>$nombre_campo3,"nombre_campo4"=>$nombre_campo4));
      			else
    	  		   $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaEditar',array("dato1"=>$planempr[$i][$nombre_campo1],"dato2"=>$planempr[$i][$nombre_campo2],"dato3"=>$planempr[$i][$nombre_campo3],"dato4"=>$planempr[$i][$nombre_campo4],"dato5"=>$planempr[$i][$nombre_campo5],"nombre_campo1"=>$nombre_campo1,"nombre_campo2"=>$nombre_campo2,"nombre_campo3"=>$nombre_campo3,"nombre_campo4"=>$nombre_campo4,"nombre_campo5"=>$nombre_campo5));

			$this->salida .= "<a href =\"$acc\"><img title=\"SELECCIONAR\" src=\"".GetThemePath()."/images/mantenimiento/modificar.gif\" border=\"0\"></a>";
			$this->salida .= "</td>";
      $ciclo1=sizeof($atributos);
			$k=0;
      while($k<$ciclo1)
      {
				$this->salida .= "<td align=\"center\">";
        $this->salida .= "".$planempr[$i][$atributos[$k][nombre_campo]]."";
				$this->salida .= "<input type=\"hidden\" name=\"campo".$k."\" size=\"60\" class=\"input-text\" value=\"".$planempr[$i][$atributos[$k][nombre_campo]]."\">";
        $this->salida .= "</td>";
        $k++;
      }
      /*
    	$this->salida .= "<td>";
			$this->salida .= "<center><a href =$funcion>EDITAR</a></center>";
			$this->salida .= "</td>";
			*/
	    $this->salida .= "</tr>";
			$i++;
		}
		if(empty($planempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"9\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ REGISTRO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
 		$this->salida .= "      </table>";

    $this->salida .= "			<br>";
		//$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"left\" class=\"modulo_table_list\">";
    $this->salida .= "      <tr>";
    $this->salida .= "      <td align=\"left\">";
    //$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"insertar\" value=\"INSERTAR\">";
    $this->salida .= "      </td>";
    //$this->salida .= "      </tr>";
		$this->salida .= "  </form>";
		//$this->salida .= "  </td></tr>";
		//$this->salida .= "  </table>";
		//$this->salida .= "  <tr>";
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaEditar');
		$this->salida .= "  <form name=\"contratacion1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"left\">";
		//$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </form>";

		//$this->salida .= "  </td></tr>";
		//$this->salida .= "  	</table>";
		$this->salida .= "  <br>";
		$var=$this->RetornarBarraDatosFK($tabla);
		if(!empty($var))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"left\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"left\">";
			$this->salida .=$var;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
/*		$accion=ModuloGetURL('app','Mantenimiento','user','',
		array('codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"left\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"ctradescri\" value=\"".$_REQUEST['ctradescri']."\" maxlength=\"60\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','Mantenimiento','user','');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";*/
		$this->salida .= ThemeCerrarTabla();
    return true;
	}

	//FormaInsertarFk
	function FormaInsertarFk($datos,$atributos,$tabla,$nombre_campo1,$nombre_campo2,$nombre_campo3,$nombre_campo4,$nombre_campo5,$nombre_campo1_sel)
	{
		//print_r($datos);
		//echo "<br><br>";
		//print_r($atributos); exit;
    echo $nombre_campo1.'DFFF'.$nombre_campo2;
    if (empty($tabla) || empty($atributos) || empty($datos))
    {
			$tabla=$_SESSION['mantenimiento']['tabla'];
      $atributos=$_SESSION['mantenimiento']['atributos'];
      $datos=$_SESSION['mantenimiento']['datos'];
    }
		else
		{
			$_SESSION['mantenimiento']['tabla']=$tabla;
      $_SESSION['mantenimiento']['atributos_']=$atributos;
      $_SESSION['mantenimiento']['datos']=$datos;
		}
  	if (empty($_SESSION['mantenimiento']['db']))
    {
			$conn=$this->GetDatosConexionActual();
			$_SESSION['mantenimiento']['db']=$conn['dbname'];
    }
		$this->salida = ThemeAbrirTabla('BASE DE DATOS: '.$_SESSION['mantenimiento']['db'].' / TABLA: '.$tabla,'90%');
		//$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaInsertarDatos',array("tabla"=>$tabla,"campos"=>$atributos));
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		if(sizeof($atributos)<5)
			$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		else
			$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['mantenimiento']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		if(sizeof($atributos)<5)
			$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
    else
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
	  $this->salida .= "      <td width=\"4%\">No.</td>";
    $this->salida .= "      <td width=\"1%\">SELECCIÓN</td>";
		$i=0;
		$ciclo=sizeof($atributos);
		while($i<$ciclo)
		{ echo $nombre_campo1_sel;
    if (trim($atributos[$i][nombre_campo])==trim($nombre_campo1) || trim($atributos[$i][nombre_campo])==trim($nombre_campo2) || trim($atributos[$i][nombre_campo])==trim($nombre_campo3) || trim($atributos[$i][nombre_campo])==trim($nombre_campo4) || trim($atributos[$i][nombre_campo])==trim($nombre_campo5))
    	$this->salida .= "      <td width=\"1%\" bgcolor=\"navy\">".$atributos[$i][nombre_campo]."</td>";
    else
		  $this->salida .= "      <td width=\"1%\">".$atributos[$i][nombre_campo]."</td>";
      $i++;
    }
		$this->salida .= "      </tr>";
		$planempr=$datos;
		$i=0;
		$j=0;
		$ciclo=sizeof($planempr);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\" >";
				$j=0;
			}
			$this->salida .= "<td align=\"center\" width=\"1%\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "</td>";

			$this->salida .= "<td align=\"center\" width=\"1%\">";
			if (empty($nombre_campo2) and empty($nombre_campo3) and empty($nombre_campo4) and empty($nombre_campo5))
         $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaInsertarDatos',array("dato1"=>$planempr[$i][$nombre_campo1],"nombre_campo1"=>$nombre_campo1));
			else
  			if (empty($nombre_campo3) and empty($nombre_campo4) and empty($nombre_campo5))
         $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaInsertarDatos',array("dato1"=>$planempr[$i][$nombre_campo1],"dato2"=>$planempr[$i][$nombre_campo2],"nombre_campo1"=>$nombre_campo1,"nombre_campo2"=>$nombre_campo2));
  			else
	  			if (empty($nombre_campo4) and empty($nombre_campo5))
		    		$acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaInsertarDatos',array("dato1"=>$planempr[$i][$nombre_campo1],"dato2"=>$planempr[$i][$nombre_campo2],"dato3"=>$planempr[$i][$nombre_campo3],"nombre_campo1"=>$nombre_campo1,"nombre_campo2"=>$nombre_campo2,"nombre_campo3"=>$nombre_campo3));
    			else
	    			if (empty($nombre_campo5))
		  		   $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaInsertarDatos',array("dato1"=>$planempr[$i][$nombre_campo1],"dato2"=>$planempr[$i][$nombre_campo2],"dato3"=>$planempr[$i][$nombre_campo3],"dato4"=>$planempr[$i][$nombre_campo4],"nombre_campo1"=>$nombre_campo1,"nombre_campo2"=>$nombre_campo2,"nombre_campo3"=>$nombre_campo3,"nombre_campo4"=>$nombre_campo4));
      			else
    	  		   $acc=ModuloGetURL('app','Mantenimiento','user','LlamaFormaInsertarDatos',array("dato1"=>$planempr[$i][$nombre_campo1],"dato2"=>$planempr[$i][$nombre_campo2],"dato3"=>$planempr[$i][$nombre_campo3],"dato4"=>$planempr[$i][$nombre_campo4],"dato5"=>$planempr[$i][$nombre_campo5],"nombre_campo1"=>$nombre_campo1,"nombre_campo2"=>$nombre_campo2,"nombre_campo3"=>$nombre_campo3,"nombre_campo4"=>$nombre_campo4,"nombre_campo5"=>$nombre_campo5));

			$this->salida .= "<a href =\"$acc\"><img title=\"SELECCIONAR\" src=\"".GetThemePath()."/images/mantenimiento/modificar.gif\" border=\"0\"></a>";
			$this->salida .= "</td>";
      $ciclo1=sizeof($atributos);
			$k=0;
      while($k<$ciclo1)
      {
        if (trim($atributos[$k][nombre_campo])==trim($nombre_campo1) || trim($atributos[$k][nombre_campo])==trim($nombre_campo2) || trim($atributos[$k][nombre_campo])==trim($nombre_campo3) || trim($atributos[$k][nombre_campo])==trim($nombre_campo4) || trim($atributos[$k][nombre_campo])==trim($nombre_campo5))
				 {
          $this->salida .= "<td align=\"center\" bgcolor=\"#c6deff\">";
          $this->salida .= "".$planempr[$i][$atributos[$k][nombre_campo]]."";
          $this->salida .= "<input type=\"hidden\" name=\"campo".$k."\" size=\"60\" class=\"input-text\" value=\"".$planempr[$i][$atributos[$k][nombre_campo]]."\">";
          $this->salida .= "</td>";
         }
        else
        {
          $this->salida .= "<td align=\"center\">";
          $this->salida .= "".$planempr[$i][$atributos[$k][nombre_campo]]."";
          $this->salida .= "<input type=\"hidden\" name=\"campo".$k."\" size=\"60\" class=\"input-text\" value=\"".$planempr[$i][$atributos[$k][nombre_campo]]."\">";
          $this->salida .= "</td>";
        }
        $k++;
      }
      /*
    	$this->salida .= "<td>";
			$this->salida .= "<center><a href =$funcion>EDITAR</a></center>";
			$this->salida .= "</td>";
			*/
	    $this->salida .= "</tr>";
			$i++;
		}
		if(empty($planempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"9\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ REGISTRO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
 		$this->salida .= "      </table>";
    $this->salida .= "			<br>";
		$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\">";
    $this->salida .= "      <tr>";
    $this->salida .= "      <td align=\"center\">";
    $this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"insertar\" value=\"INSERTAR\">";
    $this->salida .= "      </td>";
    //$this->salida .= "      </tr>";
		$this->salida .= "  </form>";
		//$this->salida .= "  </td></tr>";
		//$this->salida .= "  </table>";
		//$this->salida .= "  <tr>";
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaInsertarFk');
		$this->salida .= "  <form name=\"contratacion1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </form>";

		//$this->salida .= "  </td></tr>";
		$this->salida .= "  	</table>";
		$this->salida .= "  <br>";
		$var=$this->RetornarBarraInsertarFk($tabla);
		if(!empty($var))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','Mantenimiento','user','',
		array('codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"ctradescri\" value=\"".$_REQUEST['ctradescri']."\" maxlength=\"60\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','Mantenimiento','user','');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
    return true;
	}

  //FormaDatosSecuencia($datos)
  function FormaDatosSecuencia($datos,$ver)
  {
   	//print_r($datos);
  	$DatosDB=$this->GetDatosConexionActual();
    if (empty($_SESSION['mantenimiento']['db']))
			$_SESSION['mantenimiento']['db']=$DatosDB['dbname'];
    $accion=ModuloGetURL('app','Mantenimiento','user','ReiniciarSecuencia',array("datos"=>$datos,"nombreseq"=>$_POST['nombreseq'],"minimovalor"=>$_POST['minimovalor'],"valor"=>$_POST['valor'],"comentario"=>$_POST['comentario']));
		$this->salida = ThemeAbrirTabla('DETALLE DE LA SECUENCIA: '.$datos[0][seqname],'90%');
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
    $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
    $this->salida .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
    $this->salida .= "<tr class=\"modulo_table_list_title\">";
    $this->salida .= "<td align = center >DETALLE DE LA SUCUENCIA</td>";
    $this->salida .= "</tr>";
    $this->salida .= "<tr class=\"modulo_list_claro\" >";
    $this->salida .= "<td width=\"40%\" >";
    $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
    $this->salida .= "<tr><td>";
    $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";

		$this->salida .= "<tr><td class=\"modulo_list_claro\">NOMBRE SECUENCIA: </td><td><input type=\"text\" class=\"input-text\" name=\"nombreseq\" value='".$datos[0][sequence_name]."' size=\"65\" maxlength=\"65\" readonly=\"true\"></td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">ULTIMO VALOR: </td><td><input type=\"text\" class=\"input-text\" name=\"ultimovalor\" value='".$datos[0][last_value]."' size=\"7\" maxlength=\"7\" readonly=\"true\"></td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">INCREMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"incremento\" value='".$datos[0][increment_by]."' size=\"5\" maxlength=\"5\" readonly=\"true\"></td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">MAXIMO VALOR: </td><td><input type=\"text\" class=\"input-text\" name=\"maximovalor\" value='".$datos[0][max_value]."' size=\"20\" maxlength=\"20\" readonly=\"true\"></td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">MINIMO VALOR: </td><td><input type=\"text\" class=\"input-text\" name=\"minimovalor\" value='".$datos[0][min_value]."' size=\"5\" maxlength=\"5\" readonly=\"true\"></td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">VALOR CACHE: </td><td><input type=\"text\" class=\"input-text\" name=\"cachevalue\" value='".$datos[0][cache_value]."' size=\"10\" maxlength=\"10\" readonly=\"true\"></td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">LOG_CNT: </td><td><input type=\"text\" class=\"input-text\" name=\"log_cnt\" value='".$datos[0][log_cnt]."' size=\"15\" maxlength=\"15\" readonly=\"true\"></td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">CICLICO: </td><td><input type=\"text\" class=\"input-text\" name=\"cycled\" value='".$datos[0][is_cycled]."' size=\"20\" maxlength=\"20\" readonly=\"true\"></td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">IS_CALLED: </td><td><input type=\"text\" class=\"input-text\" name=\"called\" value='".$datos[0][is_called]."' size=\"25\" maxlength=\"25\" readonly=\"true\"></td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">COMENTARIO: </td><td><input type=\"text\" class=\"input-text\" name=\"comentario\" value='".$datos[0][seqcomment]."' size=\"50\" maxlength=\"70\" ></td></tr>";
		if ($ver==true)
			$this->salida .= "<tr><td class=\"modulo_list_claro\">VALOR: </td><td><input type=\"text\" class=\"input-text\" name=\"valor\" size=\"15\" maxlength=\"15\" ></td></tr>";

    $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
    $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"REINICIAR\"></td>";
    $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"asignarvalor\" value=\"ASIGNAR VALOR\"><br></td>";
    $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"siguientevalor\" value=\"SIGUIENTE VALOR\"><br></td></form>";

    $this->salida .= "</form>";
    $actionM=ModuloGetURL('app','Mantenimiento','user','LlamaFormaSecuencias');
    $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
    $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
	  $this->salida .= "</tr>";
    $this->salida .= "</table></td></tr>";
    $this->salida .= "</td></tr></table>";
    $this->salida .= "</td>";
    $this->salida .= "</table>";
    $this->salida .= "</td>";
    $this->salida .= "</tr>";
    $this->salida .= "  </table>";
    $this->salida .= " </form>";
    //mensaje//
/*    $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
    $this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= "  </table>";*/
    $this->salida .= ThemeCerrarTabla();
		return true;
  }

	//BARRA RetornarBarraInsertarFk
 	function RetornarBarraInsertarFk($tabla)//Barra paginadora de las tablas en mantenimiento
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaInsertarFk',array('conteo'=>$this->conteo,
		'tabla'=>$tabla));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

  //RETORNAR BARRA CLIENTES
 	function RetornarBarraClientes()//Barra paginadora de las tablas en mantenimiento
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Mantenimiento','user','FormaMantenimiento',array('conteo'=>$this->conteo,
		'codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	//RETORNA BARRA DATOS FK
	function RetornarBarraDatosFk($tabla)
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaFk',array('conteo'=>$this->conteo,
		'tabla'=>$tabla));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

  //BARRA PARA EL BROWSER DE LOS DATOS
 	function RetornarBarraDatos()//Barra paginadora de las tablas en mantenimiento
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		//$accion=ModuloGetURL('app','Mantenimiento','user','FormaVerDatos',array('conteo'=>$this->conteo,
		//'codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri']));
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaDatosBrowser');
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	//
 	function RetornarBarraDatosIncluir()//Barra paginadora de las tablas en mantenimiento
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		//$accion=ModuloGetURL('app','Mantenimiento','user','FormaVerDatos',array('conteo'=>$this->conteo,
		//'codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri']));
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaTraerDatosRef');
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

  //BARRA DE NAVEGACIÓN O PAGINADORA PARA LAS SECUENCIAS
 	function RetornarBarraSecuencias()
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','Mantenimiento','user','LlamaFormaSecuencias');
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
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
}//fin de la clase
?>
