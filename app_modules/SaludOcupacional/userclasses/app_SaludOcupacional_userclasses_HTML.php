
<?php

/**
* Modulo de Salud Ocupacional (PHP).
*
* Modulo para relacionar las enfermedades provocadas con profesiones laborales
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_SaludOcupacional_userclasses_HTML.php
*
* Clase para establecer y mostrar las relaciones que existen entre algunas
* enfermedades provocadas por la profesión o por el alto riesgo de adquirirla
**/

class app_SaludOcupacional_userclasses_HTML extends app_SaludOcupacional_user
{
	function app_SaludOcupacional_user_HTML()
	{
		$this->app_SaludOcupacional_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Determina las empresas, en las cuales el usuario tiene permisos
	function PrincipalSalud2()//Selecciona las empresas disponibles
	{
		UNSET($_SESSION['salude']);
		UNSET($_SESSION['salud']);
		if($this->UsuariosSalud()==false)
		{
			return false;
		}
		return true;
	}

	//Función principal que da las opciones para tener acceso a SALUD OCUPACIONAL
	function PrincipalSalud()//Llama a todas las opciones posibles
	{
		if($_SESSION['salude']['empresa']==NULL)
		{
			$_SESSION['salude']['empresa']=$_REQUEST['permisosalud']['empresa_id'];
			$_SESSION['salude']['razonso']=$_REQUEST['permisosalud']['descripcion1'];
		}
		UNSET($_SESSION['salud']);
		$this->salida  = ThemeAbrirTabla('SALUD OCUPACIONAL - OPCIONES');
		$this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">SALUD OCUPACIONAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['salude']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      MENÚ";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','SaludOcupacional','user','CambiarEquivalencia1Salud') ."\">ENFERMEDADES PROFESIONALES</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$accion=ModuloGetURL('app','SaludOcupacional','user','PrincipalSalud2');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"EMPRESAS\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$accion=ModuloGetURL('app','SaludOcupacional','user','BorrarSalud');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"PARAMETROS DE HISTORIA CLINICA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*PRIMERA PARTE*/

	//Función que establece la manera de visualizar las relaciones
	function CambiarEquivalencia1Salud()//Cambia de diagnósticos y ocupaciones a ocupaciones y diagnósticos; y viceversa
	{
		if($_SESSION['salud']['vistaseleg']==1)
		{
			$_SESSION['salud']['vistaseleg']=2;
			$this->EquivalenciaSalud2();
		}
		else if($_SESSION['salud']['vistaseleg']==2)
		{
			$_SESSION['salud']['vistaseleg']=1;
			$this->EquivalenciaSalud1();
		}
		else if($_SESSION['salud']['vistaseleg']==NULL)
		{
			$_SESSION['salud']['vistaseleg']=1;
			$this->EquivalenciaSalud1();
		}
		return true;
	}

	//Función que busca por enfermedades
	function EquivalenciaSalud1()//
	{
		if($_SESSION['salud']['vistaseleg']==NULL)
		{
			$this->PrincipalSalud();
			return true;
		}
		UNSET($_SESSION['salud']['diagnosti1']);
		UNSET($_SESSION['salud']['indicedieq']);
		UNSET($_SESSION['salud']['profesion1']);
		$this->salida  = ThemeAbrirTabla('SALUD OCUPACIONAL - ENFERMEDADES');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"left\" valign=\"top\">";
		$this->salida .= "  <fieldset><legend class=\"field\">ENFERMEDADES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['salude']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"50%\" align=\"right\" class=\"label\">CAMBIAR ENFERMEDADES POR PROFESIONES: </td>";
		$this->salida .= "      <td width=\"50%\" align=\"left\">";
		$this->salida .= "<a href=\"".ModuloGetURL('app','SaludOcupacional','user','CambiarEquivalencia1Salud')."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/uf.png\" border=\"0\"></a></td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$_SESSION['salud']['diagnosti1']=$this->BuscarEnfermedadSalud1();
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"2%\" ></td>";
		$this->salida .= "      <td width=\"6%\" >DIAG.</td>";
		$this->salida .= "      <td width=\"42%\">DESCRIPCIÓN DEL DIAGNÓSTICO</td>";
		$this->salida .= "      <td width=\"6%\" >PROF.</td>";
		$this->salida .= "      <td width=\"44%\">DESCRIPCIÓN DE LA PROFESIÓN</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($_SESSION['salud']['diagnosti1']);
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
			$this->salida .= "<a href=\"".ModuloGetURL('app','SaludOcupacional','user','CrearEquivalenciaSalud1',
			array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigosalu'=>$_REQUEST['codigosalu'],
			'descrisalu'=>$_REQUEST['descrisalu'],'indicedieq'=>$i))."\">
			<img src=\"".GetThemePath()."/images/equivalencia.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['salud']['diagnosti1'][$i]['diagnostico_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['salud']['diagnosti1'][$i]['diagnostico_nombre']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\" colspan=\"2\">";
			if($_SESSION['salud']['diagnosti1'][$i]['num']==0)
			{
				$this->salida .= "NO HAY RELACIONES PARA ESTE DIAGNÓSTICO";
			}
			else
			{
				$muestra=$this->BuscarMostrarEquiSalud1($_SESSION['salud']['diagnosti1'][$i]['diagnostico_id']);
				$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
				$ciclo1=sizeof($muestra);
				for($l=0;$l<$ciclo1;$l++)
				{
					$this->salida .= "  <tr>";
					$this->salida .= "  <td width=\"12%\">";
					$this->salida .= "".$muestra[$l]['ocupacion_id']."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td width=\"88%\">";
					$this->salida .= "".$muestra[$l]['ocupacion_descripcion']."";
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
				}
				$this->salida .= "  </table>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['salud']['diagnosti1']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARÓN ENFERMEDADES'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraSalud1();
		if(!empty($var1))
		{
			$this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var1;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','SaludOcupacional','user','PrincipalSalud');
		$this->salida .= "      <form name=\"salud\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"SALUD OCUPACIONAL - OPCIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','SaludOcupacional','user','EquivalenciaSalud1',
		array('codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu']));
		$this->salida .= "      <form name=\"salud1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"left\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">DIAGNÓSTICO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigosalu\" value=\"".$_REQUEST['codigosalu']."\" maxlength=\"6\" size=\"6\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descrisalu\" value=\"".$_REQUEST['descrisalu']."\" maxlength=\"60\" size=\"40\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','SaludOcupacional','user','EquivalenciaSalud1');
		$this->salida .= "      <form name=\"salud2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function CrearEquivalenciaSalud1()//
	{
		if($_SESSION['salud']['indicedieq']==NULL)
		{
			$_SESSION['salud']['indicedieq']=$_REQUEST['indicedieq'];
		}
		UNSET($_SESSION['salud']['profesion1']);
		$this->salida  = ThemeAbrirTabla('SALUD OCUPACIONAL - RELACIONAR ENFERMEDADES');
		$accion=ModuloGetURL('app','SaludOcupacional','user','ValidarEquivalenciaSalud1',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu'],
		'Of1'=>$_REQUEST['Of1'],'paso1'=>$_REQUEST['paso1'],
		'codigocrea'=>$_REQUEST['codigocrea'],'descricrea'=>$_REQUEST['descricrea']));
		$this->salida .= "  <form name=\"salud1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"left\" valign=\"top\">";
		$this->salida .= "  <fieldset><legend class=\"field\">ENFERMEDADES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['salude']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DIAGNÓSTICO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['salud']['diagnosti1'][$_SESSION['salud']['indicedieq']]['diagnostico_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">DESCRIPCIÓN DEL DIAGNÓSTICO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['salud']['diagnosti1'][$_SESSION['salud']['indicedieq']]['diagnostico_nombre']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$muestra=$this->BuscarMostrarEquiSalud1($_SESSION['salud']['diagnosti1'][$_SESSION['salud']['indicedieq']]['diagnostico_id']);
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"2\">PROFESIONES RELACIONADAS CON EL DIAGNÓSTICO</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"6%\" >PROF.</td>";
		$this->salida .= "      <td width=\"94%\">DESCRIPCIÓN DE LA PROFESIÓN</td>";
		$this->salida .= "      </tr>";
		$ciclo=sizeof($muestra);
		for($l=0;$l<$ciclo;$l++)
		{
			$this->salida .= "  <tr class=\"modulo_list_claro\">";
			$this->salida .= "  <td width=\"6%\" >";
			$this->salida .= "".$muestra[$l]['ocupacion_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"94%\">";
			$this->salida .= "".$muestra[$l]['ocupacion_descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($muestra))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"2\" align=\"center\">";
			$this->salida .= "'NO HAY RELACIONES PARA ESTE DIAGNÓSTICO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table  border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"3\">PROFESIONES PARA RELACIONAR CON EL DIAGNÓSTICO</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"6%\" >PROF.</td>";
		$this->salida .= "      <td width=\"88%\">DESCRIPCIÓN DE LA PROFESIÓN</td>";
		$this->salida .= "      <td width=\"6%\" >EQUIVALE</td>";
		$this->salida .= "      </tr>";
 		$_SESSION['salud']['profesion1']=$this->BuscarOcupacionesSalud1($_SESSION['salud']['diagnosti1'][$_SESSION['salud']['indicedieq']]['diagnostico_id']);
		$j=0;
		$ciclo=sizeof($_SESSION['salud']['profesion1']);
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
			$this->salida .= "  <td width=\"8%\">";
			$this->salida .= "".$_SESSION['salud']['profesion1'][$i]['ocupacion_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"86%\">";
			$this->salida .= "".$_SESSION['salud']['profesion1'][$i]['ocupacion_descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"6%\" align=\"center\">";
			if($_POST['equivasalu'.$i]==1 OR $_SESSION['salud']['profesion1'][$i]['num']<>0)
			{
				$this->salida .= "<input type=\"checkbox\" name=\"equivasalu".$i."\" value=1 checked>";
			}
			else
			{
				$this->salida .= "<input type=\"checkbox\" name=\"equivasalu".$i."\" value=1>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['salud']['profesion1']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARÓN PROFESIONES'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td><br>";
		$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\" width=\"50%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR RELACIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <td align=\"center\" width=\"50%\">";
		$accion=ModuloGetURL('app','SaludOcupacional','user','EquivalenciaSalud1',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu']));
		$this->salida .= "      <form name=\"salud2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A ENFERMEDADES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraSalu2();
		if(!empty($var1))
		{
			$this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var1;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','SaludOcupacional','user','CrearEquivalenciaSalud1',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu'],
		'codigocrea'=>$_REQUEST['codigocrea'],'descricrea'=>$_REQUEST['descricrea']));
		$this->salida .= "      <form name=\"salud3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">PROFESIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocrea\" value=\"".$_REQUEST['codigocrea']."\" maxlength=\"4\" size=\"4\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descricrea\" value=\"".$_REQUEST['descricrea']."\" maxlength=\"60\" size=\"40\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','SaludOcupacional','user','CrearEquivalenciaSalud1',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu']));
		$this->salida .= "      <form name=\"salud4\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraSalud1()//Barra paginadora de las consultas
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
		$accion=ModuloGetURL('app','SaludOcupacional','user','EquivalenciaSalud1',array('conteo'=>$this->conteo,
		'codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu']));
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

	function RetornarBarraSalu2()//Barra paginadora de la creación de equivalencias
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','SaludOcupacional','user','CrearEquivalenciaSalud1',
		array('conteo'=>$_REQUEST['conteo'],'paso'=>$_REQUEST['paso'],
		'codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu'],
		'conteo1'=>$this->conteo,'codigocrea'=>$_REQUEST['codigocrea'],'descricrea'=>$_REQUEST['descricrea']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset(1)."&paso1=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($paso-1)."&paso1=".($paso-1)."'>&lt;&lt;</a></td>";
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
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($i)."&paso1=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($paso+1)."&paso1=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($numpasos)."&paso1=$numpasos'>&gt;</a></td>";
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
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($i)."&paso1=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($paso+1)."&paso1=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($numpasos)."&paso1=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of1'])==0 OR ($paso==$numpasos))
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

	/*SEGUNDA PARTE*/

	//
	function EquivalenciaSalud2()//Función que busca por enfermedades
	{
		if($_SESSION['salud']['vistaseleg']==NULL)
		{
			$this->PrincipalSalud();
			return true;
		}
		UNSET($_SESSION['salud']['profesion2']);
		UNSET($_SESSION['salud']['indiceoceq']);
		UNSET($_SESSION['salud']['diagnosti2']);
		$this->salida  = ThemeAbrirTabla('SALUD OCUPACIONAL - PROFESIONES');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"left\" valign=\"top\">";
		$this->salida .= "  <fieldset><legend class=\"field\">PROFESIONES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['salude']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"50%\" align=\"right\" class=\"label\">CAMBIAR PROFESIONES POR ENFERMEDADES: </td>";
		$this->salida .= "      <td width=\"50%\" align=\"left\">";
		$this->salida .= "<a href=\"".ModuloGetURL('app','SaludOcupacional','user','CambiarEquivalencia1Salud')."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/uf.png\" border=\"0\"></a></td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$_SESSION['salud']['profesion2']=$this->BuscarOcupacionesSalud2();
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"2%\" ></td>";
		$this->salida .= "      <td width=\"6%\" >PROF.</td>";
		$this->salida .= "      <td width=\"42%\">DESCRIPCIÓN DE LA PROFESIÓN</td>";
		$this->salida .= "      <td width=\"6%\" >DIAG.</td>";
		$this->salida .= "      <td width=\"44%\">DESCRIPCIÓN DEL DIAGNÓSTICO</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($_SESSION['salud']['profesion2']);
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
			$this->salida .= "<a href=\"".ModuloGetURL('app','SaludOcupacional','user','CrearEquivalenciaSalud2',
			array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigosalu'=>$_REQUEST['codigosalu'],
			'descrisalu'=>$_REQUEST['descrisalu'],'indiceoceq'=>$i))."\">
			<img src=\"".GetThemePath()."/images/equivalencia.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['salud']['profesion2'][$i]['ocupacion_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['salud']['profesion2'][$i]['ocupacion_descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\" colspan=\"2\">";
			if($_SESSION['salud']['profesion2'][$i]['num']==0)
			{
				$this->salida .= "NO HAY RELACIONES PARA ESTA PROFESIÓN";
			}
			else
			{
				$muestra=$this->BuscarMostrarEquiSalud2($_SESSION['salud']['profesion2'][$i]['ocupacion_id']);
				$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
				$ciclo1=sizeof($muestra);
				for($l=0;$l<$ciclo1;$l++)
				{
					$this->salida .= "  <tr>";
					$this->salida .= "  <td width=\"12%\">";
					$this->salida .= "".$muestra[$l]['diagnostico_id']."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td width=\"88%\">";
					$this->salida .= "".$muestra[$l]['diagnostico_nombre']."";
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
				}
				$this->salida .= "  </table>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['salud']['profesion2']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARÓN PROFESIONES'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraSalud3();
		if(!empty($var1))
		{
			$this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var1;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','SaludOcupacional','user','PrincipalSalud');
		$this->salida .= "      <form name=\"salud\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"SALUD OCUPACIONAL - OPCIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','SaludOcupacional','user','EquivalenciaSalud2',
		array('codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu']));
		$this->salida .= "      <form name=\"salud1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"left\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">PROFESIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigosalu\" value=\"".$_REQUEST['codigosalu']."\" maxlength=\"6\" size=\"6\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descrisalu\" value=\"".$_REQUEST['descrisalu']."\" maxlength=\"60\" size=\"40\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','SaludOcupacional','user','EquivalenciaSalud2');
		$this->salida .= "      <form name=\"salud2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function CrearEquivalenciaSalud2()//
	{
		if($_SESSION['salud']['indiceoceq']==NULL)
		{
			$_SESSION['salud']['indiceoceq']=$_REQUEST['indiceoceq'];
		}
		UNSET($_SESSION['salud']['diagnosti2']);
		$this->salida  = ThemeAbrirTabla('SALUD OCUPACIONAL - RELACIONAR PROFESIONES');
		$accion=ModuloGetURL('app','SaludOcupacional','user','ValidarEquivalenciaSalud2',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu'],
		'Of1'=>$_REQUEST['Of1'],'paso1'=>$_REQUEST['paso1'],
		'codigocrea'=>$_REQUEST['codigocrea'],'descricrea'=>$_REQUEST['descricrea']));
		$this->salida .= "  <form name=\"salud1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"left\" valign=\"top\">";
		$this->salida .= "  <fieldset><legend class=\"field\">PROFESIONES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['salude']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PROFESIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['salud']['profesion2'][$_SESSION['salud']['indiceoceq']]['ocupacion_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">DESCRIPCIÓN DE LA PROFESIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['salud']['profesion2'][$_SESSION['salud']['indiceoceq']]['ocupacion_descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$muestra=$this->BuscarMostrarEquiSalud2($_SESSION['salud']['profesion2'][$_SESSION['salud']['indiceoceq']]['ocupacion_id']);
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"2\">ENFERMEDADES RELACIONADAS CON LA PROFESIÓN</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"6%\" >DIAG.</td>";
		$this->salida .= "      <td width=\"94%\">DESCRIPCIÓN DEL DIAGNÓSTICO</td>";
		$this->salida .= "      </tr>";
		$ciclo=sizeof($muestra);
		for($l=0;$l<$ciclo;$l++)
		{
			$this->salida .= "  <tr class=\"modulo_list_claro\">";
			$this->salida .= "  <td width=\"6%\" >";
			$this->salida .= "".$muestra[$l]['diagnostico_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"94%\">";
			$this->salida .= "".$muestra[$l]['diagnostico_nombre']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($muestra))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"2\" align=\"center\">";
			$this->salida .= "'NO HAY RELACIONES PARA ESTA PROFESIÓN'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table  border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"3\">DIAGNÓSTICO PARA RELACIONAR CON LA PROFESIÓN</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"6%\" >DIAG.</td>";
		$this->salida .= "      <td width=\"88%\">DESCRIPCIÓN DEL DIAGNÓSTICO</td>";
		$this->salida .= "      <td width=\"6%\" >EQUIVALE</td>";
		$this->salida .= "      </tr>";
 		$_SESSION['salud']['diagnosti2']=$this->BuscarEnfermedadSalud2(
		$_SESSION['salud']['profesion2'][$_SESSION['salud']['indiceoceq']]['ocupacion_id']);
		$j=0;
		$ciclo=sizeof($_SESSION['salud']['diagnosti2']);
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
			$this->salida .= "  <td width=\"8%\">";
			$this->salida .= "".$_SESSION['salud']['diagnosti2'][$i]['diagnostico_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"86%\">";
			$this->salida .= "".$_SESSION['salud']['diagnosti2'][$i]['diagnostico_nombre']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"6%\" align=\"center\">";
			if($_POST['equivasalu'.$i]==1 OR $_SESSION['salud']['diagnosti2'][$i]['num']<>0)
			{
				$this->salida .= "<input type=\"checkbox\" name=\"equivasalu".$i."\" value=1 checked>";
			}
			else
			{
				$this->salida .= "<input type=\"checkbox\" name=\"equivasalu".$i."\" value=1>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['salud']['diagnosti2']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARÓN ENFERMEDADES'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td><br>";
		$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\" width=\"50%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR RELACIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <td align=\"center\" width=\"50%\">";
		$accion=ModuloGetURL('app','SaludOcupacional','user','EquivalenciaSalud2',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu']));
		$this->salida .= "      <form name=\"salud2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A PROFESIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraSalu4();
		if(!empty($var1))
		{
			$this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var1;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','SaludOcupacional','user','CrearEquivalenciaSalud2',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu'],
		'codigocrea'=>$_REQUEST['codigocrea'],'descricrea'=>$_REQUEST['descricrea']));
		$this->salida .= "      <form name=\"salud3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">DIAGNÓSTICO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocrea\" value=\"".$_REQUEST['codigocrea']."\" maxlength=\"4\" size=\"4\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descricrea\" value=\"".$_REQUEST['descricrea']."\" maxlength=\"60\" size=\"40\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','SaludOcupacional','user','CrearEquivalenciaSalud2',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu']));
		$this->salida .= "      <form name=\"salud4\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraSalud3()//Barra paginadora de las consultas
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
		$accion=ModuloGetURL('app','SaludOcupacional','user','EquivalenciaSalud2',array('conteo'=>$this->conteo,
		'codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu']));
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

	function RetornarBarraSalu4()//Barra paginadora de la creación de equivalencias
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','SaludOcupacional','user','CrearEquivalenciaSalud2',
		array('conteo'=>$_REQUEST['conteo'],'paso'=>$_REQUEST['paso'],
		'codigosalu'=>$_REQUEST['codigosalu'],'descrisalu'=>$_REQUEST['descrisalu'],
		'conteo1'=>$this->conteo,'codigocrea'=>$_REQUEST['codigocrea'],'descricrea'=>$_REQUEST['descricrea']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset(1)."&paso1=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($paso-1)."&paso1=".($paso-1)."'>&lt;&lt;</a></td>";
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
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($i)."&paso1=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($paso+1)."&paso1=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($numpasos)."&paso1=$numpasos'>&gt;</a></td>";
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
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($i)."&paso1=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($paso+1)."&paso1=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of1=".$this->CalcularOffset($numpasos)."&paso1=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of1'])==0 OR ($paso==$numpasos))
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

}//fin de la clase
?>
