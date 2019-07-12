<?php

/**
 * $Id: app_Auditores_userclasses_HTML.php,v 1.3 2005/10/14 13:57:58 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Clase que permite establecer las funciones administrativas de los usuarios
 */

class app_Auditores_userclasses_HTML extends app_Auditores_user
{
	function app_Auditores_user_HTML()
	{
		$this->app_Auditores_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Determina las empresas, en las cuales el usuario tiene permisos
	function PrincipalAudito2()//Selecciona las empresas disponibles
	{
		UNSET($_SESSION['audito']);
		if($this->UsuariosAudito()==false)
		{
			return false;
		}
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de Compras
	function PrincipalAudito()//Llama a todas las opciones posibles
	{
		if(empty($_SESSION['audito']['empresa']))
		{
			$_SESSION['audito']['empresa']=$_REQUEST['permisosaudito']['empresa_id'];
			$_SESSION['audito']['razonso']=$_REQUEST['permisosaudito']['descripcion1'];
		}
		UNSET($_SESSION['audit1']);
		$this->salida  = ThemeAbrirTabla('AUDITORES - OPCIONES');
		$this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      MENÚ";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Auditores','user','FuncionesAudito') ."\">FUNCIONES</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Auditores','user','AuditoresInternosAudito') ."\">AUDITORES INTERNOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Auditores','user','AuditoresExternosAudito') ."\">AUDITORES EXTERNOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$accion=ModuloGetURL('app','Auditores','user','PrincipalAudito2');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"EMPRESAS\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function FuncionesAudito()//
	{
		UNSET($_SESSION['audit1']['auditores']);
		$this->salida  = ThemeAbrirTabla('AUDITORES - FUNCIONES');
		$accion=ModuloGetURL('app','Auditores','user','ValidarFuncionesAudito');
		$this->salida .= "  <form name=\"nuevo\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">AUDITORES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['audito']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"3%\" >No.</td>";
		$this->salida .= "      <td width=\"12%\">USUARIO</td>";
		$this->salida .= "      <td width=\"36%\">NOMBRE</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"44%\">FUNCIÓN</td>";
		$this->salida .= "      </tr>";
		$_SESSION['audit1']['auditores']=$this->BuscarFuncionesAudito($_SESSION['audito']['empresa']);
		$j=0;
		$ciclo=sizeof($_SESSION['audit1']['auditores']);
		for($i=0;$i<$ciclo;$i++)
		{
			if($j==0)
			{
				$color="class=\"modulo_list_claro\"";
				$j=1;
			}
			else
			{
				$color="class=\"modulo_list_oscuro\"";
				$j=0;
			}
			$this->salida .= "  <tr $color>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['audit1']['auditores'][$i]['usuario']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['audit1']['auditores'][$i]['nombre']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "  <img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td width=\"25%\" align=\"center\">";
			$this->salida .= "  ENCARGADO";
			if($_SESSION['audit1']['auditores'][$i]['sw_tipo_funcion']==1)
			{
				$this->salida .= "  <input type=\"radio\" name=\"funcion".$i."\" value=\"1\" checked>";
			}
			else
			{
				$this->salida .= "  <input type=\"radio\" name=\"funcion".$i."\" value=\"1\">";
			}
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"25%\" align=\"center\">";
			$this->salida .= "  AUDITOR";
			if($_SESSION['audit1']['auditores'][$i]['sw_tipo_funcion']==2)
			{
				$this->salida .= "  <input type=\"radio\" name=\"funcion".$i."\" value=\"2\" checked>";
			}
			else
			{
				$this->salida .= "  <input type=\"radio\" name=\"funcion".$i."\" value=\"2\">";
			}
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"25%\" align=\"center\">";
			$this->salida .= "  AMBAS";
			if($_SESSION['audit1']['auditores'][$i]['sw_tipo_funcion']==9)
			{
				$this->salida .= "  <input type=\"radio\" name=\"funcion".$i."\" value=\"9\" checked>";
			}
			else
			{
				$this->salida .= "  <input type=\"radio\" name=\"funcion".$i."\" value=\"9\">";
			}
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"25%\" align=\"center\">";
			$this->salida .= "  BORRAR";
			if($_SESSION['audit1']['auditores'][$i]['sw_tipo_funcion']==-1)
			{
				$this->salida .= "  <input type=\"radio\" name=\"funcion".$i."\" value=\"-1\" checked>";
			}
			else
			{
				$this->salida .= "  <input type=\"radio\" name=\"funcion".$i."\" value=\"-1\">";
			}
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['audit1']['auditores']))
		{
			$this->salida .= "  <tr class=\"modulo_list_claro\">";
			$this->salida .= "  <td colspan=\"6\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN USUARIO'";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR FUNCIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','Auditores','user','PrincipalAudito');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"AUDITORES - OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraAuditor();
		if(!empty($var))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td align=\"center\" width=\"100%\">";
			$this->salida .=$var;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','Auditores','user','FuncionesAudito',
		array('codigoaudi'=>$_REQUEST['codigoaudi'],'descriaudi'=>$_REQUEST['descriaudi']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">USUARIO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoaudi\" value=\"".$_REQUEST['codigoaudi']."\" maxlength=\"25\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descriaudi\" value=\"".$_REQUEST['descriaudi']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Auditores','user','FuncionesAudito');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraAuditor()//Barra paginadora de los usuarios
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
		$accion=ModuloGetURL('app','Auditores','user','FuncionesAudito',array('conteo'=>$this->conteo,
		'codigoaudi'=>$_REQUEST['codigoaudi'],'descriaudi'=>$_REQUEST['descriaudi']));
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

	//Función de mantenimiento de los auditores internos de un plan
	function AuditoresInternosAudito()//Válida los cambios, elimina, guarda o modifica
	{
		$this->salida  = ThemeAbrirTabla('AUDITORES - AUDITORES INTERNOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">AUDITORES INTERNOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['audito']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"45%\">USUARIO</td>";
		$this->salida .= "      <td width=\"10%\">EXTENSIÓN</td>";
		$this->salida .= "      <td width=\"15%\">CELULAR</td>";
		$this->salida .= "      <td width=\"15%\" >TIPO AUDITOR.</td>";
		$this->salida .= "      <td width=\"5%\" >ESTA.</td>";
		$this->salida .= "      <td width=\"5%\" >MODI.</td>";
		$this->salida .= "      <td width=\"5%\" >ELIM.</td>";
		$this->salida .= "      </tr>";
		$auditores=$this->BuscarAuditoresInternosAudito();
		$j=0;
		$ciclo=sizeof($auditores);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$auditores[$i]['nombre']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$auditores[$i]['extension']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$auditores[$i]['celular']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$auditores[$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($auditores[$i]['estado']==1)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Auditores','user','AuditorInActivo',array('estado'=>0,
				'usuario'=>$auditores[$i]['usuario_id'])) ."\"><img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\"></a>";
			}
			else if($auditores[$i]['estado']==0)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Auditores','user','AuditorInActivo',array('estado'=>1,
				'usuario'=>$auditores[$i]['usuario_id'])) ."\"><img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\"></a>";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Auditores','user','IngresaAuditorInAudito',
			array('inguarmodi'=>2,'usuario'=>$auditores[$i]['usuario_id'],'nombre'=>$auditores[$i]['nombre'])) ."\">
			<img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Auditores','user','EliminarAuditorInAudito',
			array('usuario'=>$auditores[$i]['usuario_id'])) ."\">
			<img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($auditores))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"6\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN AUDITOR'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "      <table border=\"0\" width=\"10%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Auditores','user','IngresaAuditorInAudito',array('inguarmodi'=>1));
		$this->salida .= "      <form name=\"nuevo\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO AUDITOR INTERNO\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','Auditores','user','PrincipalAudito');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"AUDITORES - OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
//IngresaAuditorInAudito
	//Función que captura los datos de un auditor interno nuevo de un plan
	function IngresaAuditorInAudito()//Captura y válida los datos
	{
		if($_REQUEST['inguarmodi']==1)
		{
			$this->salida  = ThemeAbrirTabla('AUDITORES - DATOS DEL AUDITOR');
			$accion=ModuloGetURL('app','Auditores','user','ValidarAuditorInAudito',array('inguarmodi'=>1));
			$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
		}
		if($_REQUEST['inguarmodi']==2)
		{
			$this->salida  = ThemeAbrirTabla('AUDITORES - DATOS DEL AUDITOR - MODIFICAR');
			$accion=ModuloGetURL('app','Auditores','user','ValidarAuditorInAudito',array('inguarmodi'=>2,'nombre'=>$_REQUEST['nombre']));
			$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
			if(!empty($_REQUEST['usuario']))
			{
				$auditor=$this->ModificaAuditorInAudito($_REQUEST['usuario']);
				$_POST['nombreaudi']=$_REQUEST['nombre'];
				$_POST['auditorctra']=$_REQUEST['usuario'];
				$_POST['telefono']=$auditor['extension'];
				$_POST['celular']=$auditor['celular'];
				$_POST['estadoin']=$auditor['estado'];
				$_POST['tipoauditoria']=$auditor['tipo_auditoria_id'];
				if($_POST['estadoin']==0)
				{
					$_POST['estadoin']=2;
				}
			}
		}
		$this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL AUDITOR INTERNO</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['audito']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"40%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("auditorctra")."\">AUDITOR:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"60%\">";
		if($_REQUEST['inguarmodi']==1)
		{
			$usuarios=$this->BuscarAuditorInAudito();
			$this->salida .= "      <select name=\"auditorctra\" class=\"select\">";
			$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
			$ciclo=sizeof($usuarios);
			for($i=0;$i<$ciclo;$i++)
			{
				if($usuarios[$i]['usuario_id']==$_POST['auditorctra'])
				{
					$this->salida .="<option value=\"".$usuarios[$i]['usuario_id']."\" selected>".$usuarios[$i]['nombre']."</option>";
				}
				else
				{
					$this->salida .="<option value=\"".$usuarios[$i]['usuario_id']."\">".$usuarios[$i]['nombre']."</option>";
				}
			}
			$this->salida .= "      </select>";
		}
		else if($_REQUEST['inguarmodi']==2)
		{
			$this->salida .= "<input type=\"text\" name=\"nombreaudi\" size=\"40\" class=\"input-text\" value=\"".$_POST['nombreaudi']."\" READONLY>";
			$this->salida .= "<input type=\"hidden\" name=\"auditorctra\" value=\"".$_POST['auditorctra']."\" class=\"input-text\" >";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"label\">EXTENSIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"telefono\" value=\"".$_POST['telefono']."\" maxlength=\"4\" size=\"30\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"label\">CELULAR:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"celular\" value=\"".$_POST['celular']."\" maxlength=\"30\" size=\"30\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("estadoin")."\">ESTADO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>ACTIVO";
		if($_POST['estadoin']==1)
		{
			$this->salida .= "      <input type='radio' name='estadoin' value=1 checked>";
		}
		else
		{
			$this->salida .= "      <input type='radio' name='estadoin' value=1>";
		}
		$this->salida .= "  INACTIVO";
		if($_POST['estadoin']==2)
		{
			$this->salida .= "      <input type='radio' name='estadoin' value=2 checked>";
		}
		else
		{
			$this->salida .= "      <input type='radio' name='estadoin' value=2>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
//TIPO DE AUDITOR
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("auditores")."\">TIPO AUDITOR:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$datos=$this->TraerAuditorias();
		$this->salida .= "      <select name=\"tipoauditoria\" class=\"select\">";
		$this->salida .= "      <option value=\"-1\">--  SELECCIONE  --</option>";
		for($i=0;$i<sizeof($datos);$i++)
		{
			if($_POST['tipoauditoria']==$datos[$i][tipo_auditoria_id])
			{
					$this->salida .="<option value=\"".$datos[$i][tipo_auditoria_id]."\" selected>".$datos[$i][descripcion]."</option>";
			}
			else
			{
					$this->salida .="<option value=\"".$datos[$i][tipo_auditoria_id]."\">".$datos[$i][descripcion]."</option>";
			}
		}
		$this->salida .= " 			 </select>";
		$this->salida .= "      </td>";
		$this->salida .= "  </tr>";
//FIN TIPO AUDITOR
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('app','Auditores','user','AuditoresInternosAudito');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función de mantenimiento de los auditores externos de un plan
	function AuditoresExternosAudito()//Válida los cambios, elimina, guarda o modifica
	{
		$this->salida  = ThemeAbrirTabla('AUDITORES - AUDITORES EXTERNOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">AUDITORES EXTERNOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['audito']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"26%\">USUARIO</td>";
		$this->salida .= "      <td width=\"12%\">TELEFÓNOS</td>";
		$this->salida .= "      <td width=\"12%\">CELULAR</td>";
		$this->salida .= "      <td width=\"35%\">CLIENTE</td>";
		$this->salida .= "      <td width=\"5%\" >ESTA.</td>";
		$this->salida .= "      <td width=\"5%\" >MODI.</td>";
		$this->salida .= "      <td width=\"5%\" >ELIM.</td>";
		$this->salida .= "      </tr>";
		$auditores=$this->BuscarAuditoresExternosAudito();
		$j=0;
		$ciclo=sizeof($auditores);
		for($i=0;$i<$ciclo;$i++)
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
			$this->salida .= "".$auditores[$i]['nombre']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$auditores[$i]['telefonos']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$auditores[$i]['celular']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$auditores[$i]['tipo_id_tercero']."".' - '."".$auditores[$i]['tercero_id']."".' - '."".$auditores[$i]['nombre_tercero']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($auditores[$i]['estado']==1)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Auditores','user','AuditorExActivo',
				array('estado'=>0,'usuario'=>$auditores[$i]['usuario_id'])) ."\">
				<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\"></a>";
			}
			else if($auditores[$i]['estado']==0)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Auditores','user','AuditorExActivo',
				array('estado'=>1,'usuario'=>$auditores[$i]['usuario_id'])) ."\">
				<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\"></a>";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Auditores','user','IngresaAuditorExAudito',
			array('exguarmodi'=>2,'usuario'=>$auditores[$i]['usuario_id'],'nombre'=>$auditores[$i]['nombre'])) ."\">
			<img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Auditores','user','EliminarAuditorExAudito',
			array('usuario'=>$auditores[$i]['usuario_id'],'tipoterid'=>$auditores[$i]['tipo_id_tercero'],'terceroid'=>$auditores[$i]['tercero_id'])) ."\">
			<img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($auditores))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"7\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN AUDITOR PARA ESTE CLIENTE'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "      <table border=\"0\" width=\"10%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Auditores','user','IngresaAuditorExAudito',array('exguarmodi'=>1));
		$this->salida .= "      <form name=\"nuevo\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO AUDITOR EXTERNO\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','Auditores','user','PrincipalAudito');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"AUDITORES - OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que captura los datos de un auditor externo nuevo de un plan
	function IngresaAuditorExAudito()//Captura y válida los datos
	{
		if($_REQUEST['exguarmodi']==1)
		{
			$this->salida  = ThemeAbrirTabla('AUDITORES - DATOS DEL AUDITOR');
			$accion=ModuloGetURL('app','Auditores','user','ValidarAuditorExAudito',array('exguarmodi'=>1));
		}
		if($_REQUEST['exguarmodi']==2)
		{
			$this->salida  = ThemeAbrirTabla('AUDITORES - DATOS DEL AUDITOR - MODIFICAR');
			if(!empty($_REQUEST['usuario']))
			{
				$auditor=$this->ModificaAuditorExAudito($_REQUEST['usuario']);
				$cambiar=$this->CambiarAuditorExterno($_REQUEST['usuario']);
				$_POST['nombreaudi']=$_REQUEST['nombre'];//nombre del usuario
				$_POST['auditorctra']=$_REQUEST['usuario'];//identificación el usuario
				$_POST['tipoTerceroId']=$auditor['tipo_id_tercero'];
				$_POST['tipocambio']=$auditor['tipo_id_tercero'];
				$_POST['codigo']=$auditor['tercero_id'];
				$_POST['terccambio']=$auditor['tercero_id'];
				$_POST['nombre']=$auditor['nombre_tercero'];
				$_POST['telefono']=$auditor['telefonos'];
				$_POST['celular']=$auditor['celular'];
				$_POST['estadoex']=$auditor['estado'];
				if($_POST['estadoex']==0)
				{
					$_POST['estadoex']=2;
				}
				if(!empty($cambiar))
				{
					$this->frmError["MensajeError"]="SI VA A MODIFICAR EL CLIENTE<br>ELIMINE LA ASIGNACIÓN AL PLAN ".$cambiar['plan_id']."";
					$this->uno=1;
					$_REQUEST['cambio']=1;
				}
				else
				{
					$_REQUEST['cambio']=2;
				}
			}
			$accion=ModuloGetURL('app','Auditores','user','ValidarAuditorExAudito',array('exguarmodi'=>2,'nombre'=>$_REQUEST['nombre'],'cambio'=>$_REQUEST['cambio']));
		}
		$mostrar=ReturnClassBuscador('proveedores','','','contratacion','');
		$this->salida .=$mostrar;
		$this->salida .= "</script>\n";
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input type=\"hidden\" name=\"tipocambio\" value=\"".$_POST['tipocambio']."\" class=\"input-text\" >";
		$this->salida .= "  <input type=\"hidden\" name=\"terccambio\" value=\"".$_POST['terccambio']."\" class=\"input-text\" >";
		$this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL AUDITOR EXTERNO</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['audito']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"10%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("tipoTerceroId")."\">TIPO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"15%\">";
		$this->salida .= "      <input type=\"text\" name=\"tipoTerceroId\" size=\"4\" class=\"input-text\" value=\"".$_POST['tipoTerceroId']."\" READONLY>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"15%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("nombre")."\">CLIENTE:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"60%\">";
		$this->salida .= "      <input type=\"text\" name=\"nombre\" size=\"48\" class=\"input-text\" value=\"".$_POST['nombre']."\" READONLY>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td align=\"center\" colspan=\"2\">";
		if($_REQUEST['cambio']==1)
		{
			$this->salida .= "      <input disabled=\"true\" type=\"button\" name=\"proveedor\" value=\"CLIENTE\" class=\"input-submit\">";
		}
		else
		{
			$this->salida .= "      <input type=\"button\" name=\"proveedor\" value=\"CLIENTE\" onclick=abrirVentana() class=\"input-submit\">";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      <td><label class=\"".$this->SetStyle("codigo")."\">CÓDIGO:</label>";//&nbsp&nbsp&nbsp;
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" name=\"codigo\" size=\"30\" class=\"input-text\" value=\"".$_POST['codigo']."\" READONLY>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"40%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("auditorctra")."\">AUDITOR:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"60%\">";
		if($_REQUEST['exguarmodi']==1)
		{
			$usuarios=$this->BuscarAuditorExAudito();//$_SESSION['audit1']['tipoidterc'],$_SESSION['audit1']['terceroidc']
			$this->salida .= "      <select name=\"auditorctra\" class=\"select\">";
			$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
			$ciclo=sizeof($usuarios);
			for($i=0;$i<$ciclo;$i++)
			{
				if($usuarios[$i]['usuario_id']==$_POST['auditorctra'])
				{
					$this->salida .= "<option value=\"".$usuarios[$i]['usuario_id']."\" selected>".$usuarios[$i]['nombre']."</option>";
				}
				else
				{
					$this->salida .= "<option value=\"".$usuarios[$i]['usuario_id']."\">".$usuarios[$i]['nombre']."</option>";
				}
			}
			$this->salida .= "      </select>";
		}
		else if($_REQUEST['exguarmodi']==2)
		{
			$this->salida .="<input type=\"text\" name=\"nombreaudi\" size=\"40\" class=\"input-text\" value=\"".$_POST['nombreaudi']."\" READONLY>";
			$this->salida .="<input type=\"hidden\" name=\"auditorctra\" value=\"".$_POST['auditorctra']."\" class=\"input-text\" >";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("telefono")."\">TELEFÓNOS:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"telefono\" value=\"".$_POST['telefono']."\" maxlength=\"30\" size=\"30\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"label\">CELULAR:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"celular\" value=\"".$_POST['celular']."\" maxlength=\"30\" size=\"30\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("estadoex")."\">ESTADO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>ACTIVO";
		if($_POST['estadoex']==1)
		{
			$this->salida .= "      <input type='radio' name='estadoex' value=1 checked>";
		}
		else
		{
			$this->salida .= "      <input type='radio' name='estadoex' value=1>";
		}
		$this->salida .= "  INACTIVO";
		if($_POST['estadoex']==2)
		{
			$this->salida .= "      <input type='radio' name='estadoex' value=2 checked>";
		}
		else
		{
			$this->salida .= "      <input type='radio' name='estadoex' value=2>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('app','Auditores','user','AuditoresExternosAudito');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

}//fin de la clase
?>
