
 <?php

/**
* Modulo de Solicitudes Frecuentes (PHP).
*
* Modulo que se establece los apoyos diadnósticos, los medicamentos
* y los procedimientos quirurgicos más solicitados o utilizados
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_SolicitudesFrecuentes_userclasses_HTML.php
*
* Clase que establece los diversos métodos para establecer los apoyos diagnósticos,
* los medicamentos y los procedimientos quirurgicos utilizados frecuentemente,
* según la especialidad o el departamento a donde pertenezcan
* Modulo que está inmerso en el modulo de Parametros de Historia Clinica,
* es decir que puede ser accesado desde el anterior o desde el mismo.
**/

class app_SolicitudesFrecuentes_userclasses_HTML extends app_SolicitudesFrecuentes_user
{
	function app_SolicitudesFrecuentes_userclasses_HTML()
	{
		$this->app_SolicitudesFrecuentes_user();//Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Determina las empresas, en las cuales el usuario tiene permisos
	function PrincipalSolfre2()//Selecciona las empresas disponibles
	{
		UNSET($_SESSION['solfre']);
		UNSET($_SESSION['solfr']);
		if($this->UsuariosSolfre()==false)
		{
			return false;
		}
		return true;
	}

	//Función principal que da las opciones para tener acceso a SOLICITUDES FRECUENTES
	function PrincipalSolfre()//Llama a todas las opciones posibles
	{
		if($_SESSION['solfre']['empresa']==NULL)
		{
			$_SESSION['solfre']['empresa']=$_REQUEST['permisosolfre']['empresa_id'];
			$_SESSION['solfre']['razonso']=$_REQUEST['permisosolfre']['descripcion1'];
		}
		UNSET($_SESSION['solfr']);
		$this->salida  = ThemeAbrirTabla('SOLICITUDES FRECUENTES - OPCIONES');
		$this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">SOLICITUDES FRECUENTES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['solfre']['razonso']."";
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
		$this->salida .= "      <a href=\"". ModuloGetURL('app','SolicitudesFrecuentes','user','ApoyosdiSolfre') ."\">APOYOS DIAGNÓSTICOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','SolicitudesFrecuentes','user','MedicamentosSolfre') ."\">MEDICAMENTOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";//FALTA PROCEDIMIENTOS
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','PrincipalSolfre2');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"EMPRESAS\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','BorrarSolfre');
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

	/********************FUNCIONES DE ESPECIALIDADES********************/

	//Función que permite seleccionar o cambiar la especialidad a relacionar, funciona para todos
	function ElegirEspecialidadSolfre()//Valida que el usuario seleccione una especialidad
	{
		$this->salida  = ThemeAbrirTabla('SOLICITUDES FRECUENTES - ESPECIALIDADES');
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ValidarEspecialidadSolfre',array('indice'=>$_REQUEST['indice']));
		$this->salida .= "  <form name=\"solfrecu1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">ESPECIALIDADES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['solfre']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$especialidad=$this->BuscarEspecialidadSolfre();
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">ESPECIALIDAD</td>";
		$this->salida .= "      <td width=\"82%\">DESCRIPCIÓN DE LA ESPECIALIDAD</td>";
		$this->salida .= "      <td width=\"8%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"center\" colspan=\"2\">BORRAR LA ESPECIALIDAD<br>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      <input type='radio' name='seleccion' value=\"BORRAR".','."BORRAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($especialidad);
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
			$this->salida .= "".$especialidad[$i]['especialidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$especialidad[$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($especialidad[$i]['especialidad']==$_SESSION['solfr']['especieleg'])
			{
				$this->salida .= "<input type='radio' name='seleccion' value=\"".$especialidad[$i]['especialidad']."".','."".$especialidad[$i]['descripcion']."\" checked>";
			}
			else
			{
				$this->salida .= "<input type='radio' name='seleccion' value=\"".$especialidad[$i]['especialidad']."".','."".$especialidad[$i]['descripcion']."\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraEs();
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
		$this->salida .= "      <td align=\"center\" width=\"50%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"ACEPTAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"50%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ElegirEspecialidadSolfre',array(
		'indice'=>$_REQUEST['indice'],'codigosolf'=>$_REQUEST['codigosolf'],'descrisolf'=>$_REQUEST['descrisolf']));
		$this->salida .= "      <form name=\"solfrecu2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">ESPECIALIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigosolf\" value=\"".$_REQUEST['codigosolf']."\" maxlength=\"4\" size=\"6\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descrisolf\" value=\"".$_REQUEST['descrisolf']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ElegirEspecialidadSolfre',array('indice'=>$_REQUEST['indice']));
		$this->salida .= "      <form name=\"solfrecu3\" action=\"$accion\" method=\"post\">";
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

	function RetornarBarraEs()//Barra paginadora de las especialidades
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
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ElegirEspecialidadSolfre',array('conteo'=>$this->conteo,
		'indice'=>$_REQUEST['indice'],'codigosolf'=>$_REQUEST['codigosolf'],'descrisolf'=>$_REQUEST['descrisolf']));
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

	/********************FUNCIONES DE APOYOS DIAGNÓSTICOS********************/

	//Función que selecciona el departamento contra los apoyos diagnósticos
	function ApoyosdiSolfre()//Muestra los tipos de relaciones que se pueden hacer contra departamentos
	{
		UNSET($_SESSION['solfr']);
		$this->salida  = ThemeAbrirTabla('SOLICITUDES FRECUENTES - APOYOS DIAGNÓSTICOS');
		$this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DEPARTAMENTOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['solfre']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','OpcionApoyosdiSolfre');
		$this->salida .= "      <form name=\"solfrecu1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"50%\">DEPARTAMENTOS A RELACIONAR<br>CON APOYOS DIAGNÓSTICOS</td>";
		$this->salida .= "      <td width=\"50%\">";
		$departamento=$this->BuscarDepartamentosSolfre($_SESSION['solfre']['empresa']);
		$this->salida .= "      <select name=\"departam\" class=\"select\">";
		$this->salida .= "      <option value=\"-1\">-- SELECCIONE --</option>";
		$ciclo=sizeof($departamento);
		for($i=0;$i<$ciclo;$i++)
		{
			if($departamento[$i]['departamento']==$_POST['departam'])
			{
				$this->salida .="<option value=\"".$departamento[$i]['departamento']."".','."".$departamento[$i]['descripcion']."\" selected>".$departamento[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$departamento[$i]['departamento']."".','."".$departamento[$i]['descripcion']."\">".$departamento[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "          <tr>";
		$this->salida .= "          <td width=\"50%\" align=\"center\">";
		$this->salida .= "          <input class=\"input-submit\" type=\"submit\" name=\"consultar\" value=\"CONSULTAR\">";
		$this->salida .= "          </td>";
		$this->salida .= "          <td width=\"50%\" align=\"center\">";
		$this->salida .= "          <input class=\"input-submit\" type=\"submit\" name=\"consultar\" value=\"RELACIONAR\">";
		$this->salida .= "          </td>";
		$this->salida .= "          </form>";
		$this->salida .= "          </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','PrincipalSolfre');
		$this->salida .= "  <form name=\"solfrecu3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"SOLICITUDES FRECUENTES - OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que establece la opción seleccionada por el usuario
	function OpcionApoyosdiSolfre()//Válida según el valor del botón seleccionado
	{
		if($_REQUEST['consultar']=='CONSULTAR')
		{
			$this->ConsultarApoyosdiSolfre();
		}
		else if($_REQUEST['consultar']=='RELACIONAR')
		{
			$this->RelacionarApoyosdiSolfre();
		}
		return true;
	}

	//Función que relaciona las especialidades con los departamentos y los apoyos diagnósticos
	function RelacionarApoyosdiSolfre()//Válida las especialidades (opcional) según el departamento y los cargos (apoyos)
	{
		if($_POST['departam']==-1 AND $_SESSION['solfr']['departeleg']==NULL)
		{
			$this->frmError["MensajeError"]="SELECCIONE UN DEPARTAMENTO";
			$this->uno=1;
			$this->ApoyosdiSolfre();
			return true;
		}
		if($_SESSION['solfr']['departeleg']==NULL)
		{
			$var=explode(',',$_REQUEST['departam']);
			$_SESSION['solfr']['departeleg']=$var[0];
			$_SESSION['solfr']['desdepeleg']=$var[1];
		}
		UNSET($_SESSION['solfr']['cargosfrec']);
		$this->salida  = ThemeAbrirTabla('SOLICITUDES FRECUENTES - APOYOS DIAGNÓSTICOS');
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ValidarRelacionarApoyosdiSolfre',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigosolf'=>$_REQUEST['codigosolf'],'descrisolf'=>$_REQUEST['descrisolf']));
		$this->salida .= "  <form name=\"solfrecu1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DEPARTAMENTOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['solfre']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"60%\">";
		$this->salida .= "      ".$_SESSION['solfr']['desdepeleg']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"10%\">";
		$this->salida .= "      (OPCIONAL)";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">ESPECIALIDAD:";
		$this->salida .= "      </td>";
		if($_SESSION['solfr']['especieleg']==NULL)
		{
			$this->salida .= "  <td align=\"center\" width=\"60%\">";
			$this->salida .= "NO DEFINIDA";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\" width=\"10%\">";
			$this->salida .= "".' '."";
			$this->salida .= "<a href=\"".ModuloGetURL('app','SolicitudesFrecuentes','user','ElegirEspecialidadSolfre',
			array('indice'=>1))."\"><img src=\"".GetThemePath()."/images/especialidadin.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
		}
		else
		{
			$this->salida .= "  <td align=\"center\" width=\"60%\">";
			$this->salida .= "".$_SESSION['solfr']['desespeleg']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\" width=\"10%\">";
			$this->salida .= "".' '."";
			$this->salida .= "<a href=\"".ModuloGetURL('app','SolicitudesFrecuentes','user','ElegirEspecialidadSolfre',
			array('indice'=>1))."\"><img src=\"".GetThemePath()."/images/especialidad.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
		}
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$_SESSION['solfr']['cargosfrec']=$this->BuscarRelacionarApoyosdiSolfre(
		$_SESSION['solfr']['departeleg'],$_SESSION['solfr']['especieleg']);
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">CARGO</td>";
		$this->salida .= "      <td width=\"82%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"8%\" >FRECUENTE</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($_SESSION['solfr']['cargosfrec']);
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
			$this->salida .= "".$_SESSION['solfr']['cargosfrec'][$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['solfr']['cargosfrec'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['solfr']['cargosfrec'][$i]['departamento']<>NULL)
			{
				$this->salida .= "<input type=\"checkbox\" name=\"frecuente".$i."\" value=1 checked>";
			}
			else
			{
				$this->salida .= "<input type=\"checkbox\" name=\"frecuente".$i."\" value=1>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['solfr']['cargosfrec']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARÓN CARGOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ApoyosdiSolfre');
		$this->salida .= "  <form name=\"solfrecu1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var1=$this->RetornarBarraCa();
		if(!empty($var1))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var1;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','RelacionarApoyosdiSolfre',
		array('codigosolf'=>$_REQUEST['codigosolf'],'descrisolf'=>$_REQUEST['descrisolf']));
		$this->salida .= "  <form name=\"solfrecu2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigosolf\" value=\"".$_REQUEST['codigosolf']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrisolf\" value=\"".$_REQUEST['descrisolf']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','RelacionarApoyosdiSolfre');
		$this->salida .= "  <form name=\"solfrecu3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que consulta los departamentos relacionados con alguna especialidad o examen médico
	function ConsultarApoyosdiSolfre()//Muestra las relaciones por departamento
	{
		if($_POST['departam']==-1 AND $_SESSION['solfr']['departeleg']==NULL)
		{
			$this->frmError["MensajeError"]="SELECCIONE UN DEPARTAMENTO";
			$this->uno=1;
			$this->ApoyosdiSolfre();
			return true;
		}
		if($_SESSION['solfr']['departeleg']==NULL)
		{
			$var=explode(',',$_REQUEST['departam']);
			$_SESSION['solfr']['departeleg']=$var[0];
			$_SESSION['solfr']['desdepeleg']=$var[1];
		}
		$this->salida  = ThemeAbrirTabla('SOLICITUDES FRECUENTES - APOYOS DIAGNÓSTICOS - CONSULTA');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DEPARTAMENTOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['solfre']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"60%\">";
		$this->salida .= "      ".$_SESSION['solfr']['desdepeleg']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"10%\">";
		$this->salida .= "      (OPCIONAL)";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">ESPECIALIDAD:";
		$this->salida .= "      </td>";
		if($_SESSION['solfr']['especieleg']==NULL)
		{
			$this->salida .= "  <td align=\"center\" width=\"60%\">";
			$this->salida .= "NO DEFINIDA";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\" width=\"10%\">";
			$this->salida .= "".' '."";
			$this->salida .= "<a href=\"".ModuloGetURL('app','SolicitudesFrecuentes','user','ElegirEspecialidadSolfre',
			array('indice'=>3))."\"><img src=\"".GetThemePath()."/images/especialidadin.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
		}
		else
		{
			$this->salida .= "  <td align=\"center\" width=\"60%\">";
			$this->salida .= "".$_SESSION['solfr']['desespeleg']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\" width=\"10%\">";
			$this->salida .= "".' '."";
			$this->salida .= "<a href=\"".ModuloGetURL('app','SolicitudesFrecuentes','user','ElegirEspecialidadSolfre',
			array('indice'=>3))."\"><img src=\"".GetThemePath()."/images/especialidad.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
		}
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$cargosfre=$this->BuscarConsultarApoyosdiSolfre(
		$_SESSION['solfr']['departeleg'],$_SESSION['solfr']['especieleg']);
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CARGO</td>";
		$this->salida .= "      <td width=\"60%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"32%\">ESPECIALIDAD</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($cargosfre);
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
			$this->salida .= "".$cargosfre[$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$cargosfre[$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			if($cargosfre[$i]['des1']<>NULL)
			{
				$this->salida .= "".$cargosfre[$i]['des1']."";
			}
			else
			{
				$this->salida .= "NO DEFINIDA";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($cargosfre))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARÓN CARGOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ApoyosdiSolfre');
		$this->salida .= "  <form name=\"solfrecu1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"APOYOS DIAGNÓSTICOS\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var1=$this->RetornarBarraCaCo();
		if(!empty($var1))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var1;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ConsultarApoyosdiSolfre',
		array('codigosolf'=>$_REQUEST['codigosolf'],'descrisolf'=>$_REQUEST['descrisolf']));
		$this->salida .= "  <form name=\"solfrecu2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigosolf\" value=\"".$_REQUEST['codigosolf']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrisolf\" value=\"".$_REQUEST['descrisolf']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ConsultarApoyosdiSolfre');
		$this->salida .= "  <form name=\"solfrecu3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraCa()//Barra paginadora de los apoyos diagnósticos
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
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','RelacionarApoyosdiSolfre',array('conteo'=>$this->conteo,
		'codigosolf'=>$_REQUEST['codigosolf'],'descrisolf'=>$_REQUEST['descrisolf']));
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

	function RetornarBarraCaCo()//Barra paginadora de los apoyos diagnósticos consulta
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
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ConsultarApoyosdiSolfre',array('conteo'=>$this->conteo,
		'codigosolf'=>$_REQUEST['codigosolf'],'descrisolf'=>$_REQUEST['descrisolf']));
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

	/********************FUNCIONES DE MEDICAMENTOS********************/

	//Función que selecciona el departamento contra los medicamentos
	function MedicamentosSolfre()//Muestra los tipos de relaciones que se pueden hacer contra departamentos
	{
		UNSET($_SESSION['solfr']);
		$this->salida  = ThemeAbrirTabla('SOLICITUDES FRECUENTES - MEDICAMENTOS');
		$this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DEPARTAMENTOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['solfre']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','OpcionMedicamentosSolfre');
		$this->salida .= "      <form name=\"solfrecu1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"50%\">DEPARTAMENTOS A RELACIONAR<br>CON MEDICAMENTOS</td>";
		$this->salida .= "      <td width=\"50%\">";
		$departamento=$this->BuscarDepartamentosSolfre($_SESSION['solfre']['empresa']);
		$this->salida .= "      <select name=\"departam\" class=\"select\">";
		$this->salida .= "      <option value=\"-1\">-- SELECCIONE --</option>";
		$ciclo=sizeof($departamento);
		for($i=0;$i<$ciclo;$i++)
		{
			if($departamento[$i]['departamento']==$_POST['departam'])
			{
				$this->salida .="<option value=\"".$departamento[$i]['departamento']."".','."".$departamento[$i]['descripcion']."\" selected>".$departamento[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$departamento[$i]['departamento']."".','."".$departamento[$i]['descripcion']."\">".$departamento[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "          <tr>";
		$this->salida .= "          <td width=\"50%\" align=\"center\">";
		$this->salida .= "          <input class=\"input-submit\" type=\"submit\" name=\"consultar\" value=\"CONSULTAR\">";
		$this->salida .= "          </td>";
		$this->salida .= "          <td width=\"50%\" align=\"center\">";
		$this->salida .= "          <input class=\"input-submit\" type=\"submit\" name=\"consultar\" value=\"RELACIONAR\">";
		$this->salida .= "          </td>";
		$this->salida .= "          </form>";
		$this->salida .= "          </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','PrincipalSolfre');
		$this->salida .= "  <form name=\"solfrecu3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"SOLICITUDES FRECUENTES - OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que establece la opción seleccionada por el usuario
	function OpcionMedicamentosSolfre()//Válida según el valor del botón seleccionado
	{
		if($_REQUEST['consultar']=='CONSULTAR')
		{
			$this->ConsultarMedicamentosSolfre();
		}
		else if($_REQUEST['consultar']=='RELACIONAR')
		{
			$this->RelacionarMedicamentosSolfre();
		}
		return true;
	}

	//Función que relaciona las especialidades con los departamentos
	function RelacionarMedicamentosSolfre()//Válida las especialidades (opcional) según el departamento y los medicamentos
	{
		if($_POST['departam']==-1 AND $_SESSION['solfr']['departeleg']==NULL)
		{
			$this->frmError["MensajeError"]="SELECCIONE UN DEPARTAMENTO";
			$this->uno=1;
			$this->MedicamentosSolfre();
			return true;
		}
		if($_SESSION['solfr']['departeleg']==NULL)
		{
			$var=explode(',',$_REQUEST['departam']);
			$_SESSION['solfr']['departeleg']=$var[0];
			$_SESSION['solfr']['desdepeleg']=$var[1];
		}
		UNSET($_SESSION['solfr']['medicafrec']);
		$this->salida  = ThemeAbrirTabla('SOLICITUDES FRECUENTES - MEDICAMENTOS');
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ValidarRelacionarMedicamentosSolfre',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigosolf'=>$_REQUEST['codigosolf'],'descrisolf'=>$_REQUEST['descrisolf']));
		$this->salida .= "  <form name=\"solfrecu1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DEPARTAMENTOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['solfre']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"60%\">";
		$this->salida .= "      ".$_SESSION['solfr']['desdepeleg']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"10%\">";
		$this->salida .= "      (OPCIONAL)";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">ESPECIALIDAD:";
		$this->salida .= "      </td>";
		if($_SESSION['solfr']['especieleg']==NULL)
		{
			$this->salida .= "  <td align=\"center\" width=\"60%\">";
			$this->salida .= "NO DEFINIDA";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\" width=\"10%\">";
			$this->salida .= "".' '."";
			$this->salida .= "<a href=\"".ModuloGetURL('app','SolicitudesFrecuentes','user','ElegirEspecialidadSolfre',
			array('indice'=>2))."\"><img src=\"".GetThemePath()."/images/especialidadin.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
		}
		else
		{
			$this->salida .= "  <td align=\"center\" width=\"60%\">";
			$this->salida .= "".$_SESSION['solfr']['desespeleg']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\" width=\"10%\">";
			$this->salida .= "".' '."";
			$this->salida .= "<a href=\"".ModuloGetURL('app','SolicitudesFrecuentes','user','ElegirEspecialidadSolfre',
			array('indice'=>2))."\"><img src=\"".GetThemePath()."/images/especialidad.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
		}
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$_SESSION['solfr']['medicafrec']=$this->BuscarRelacionarMedicamentosSolfre(
		$_SESSION['solfr']['departeleg'],$_SESSION['solfr']['especieleg']);
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">MEDICAMENTO</td>";
		$this->salida .= "      <td width=\"82%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"8%\" >FRECUENTE</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($_SESSION['solfr']['medicafrec']);
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
			$this->salida .= "".$_SESSION['solfr']['medicafrec'][$i]['codigo_producto']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['solfr']['medicafrec'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['solfr']['medicafrec'][$i]['departamento']<>NULL)
			{
				$this->salida .= "<input type=\"checkbox\" name=\"frecuente".$i."\" value=1 checked>";
			}
			else
			{
				$this->salida .= "<input type=\"checkbox\" name=\"frecuente".$i."\" value=1>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['solfr']['medicafrec']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARÓN MEDICAMENTOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\" width=\"50%\">";
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','MedicamentosSolfre');
		$this->salida .= "  <form name=\"solfrecu1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var1=$this->RetornarBarraMe();
		if(!empty($var1))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var1;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','RelacionarMedicamentosSolfre',
		array('codigosolf'=>$_REQUEST['codigosolf'],'descrisolf'=>$_REQUEST['descrisolf']));
		$this->salida .= "  <form name=\"solfrecu2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">MEDICAMENTO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigosolf\" value=\"".$_REQUEST['codigosolf']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrisolf\" value=\"".$_REQUEST['descrisolf']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','RelacionarMedicamentosSolfre');
		$this->salida .= "  <form name=\"solfrecu3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que consulta los departamentos relacionados con alguna especialidad o medicamento
	function ConsultarMedicamentosSolfre()//Muestra las relaciones por departamento
	{
		if($_POST['departam']==-1 AND $_SESSION['solfr']['departeleg']==NULL)
		{
			$this->frmError["MensajeError"]="SELECCIONE UN DEPARTAMENTO";
			$this->uno=1;
			$this->MedicamentosSolfre();
			return true;
		}
		if($_SESSION['solfr']['departeleg']==NULL)
		{
			$var=explode(',',$_REQUEST['departam']);
			$_SESSION['solfr']['departeleg']=$var[0];
			$_SESSION['solfr']['desdepeleg']=$var[1];
		}
		$this->salida  = ThemeAbrirTabla('SOLICITUDES FRECUENTES - MEDICAMENTOS - CONSULTAR');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DEPARTAMENTOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['solfre']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"60%\">";
		$this->salida .= "      ".$_SESSION['solfr']['desdepeleg']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"10%\">";
		$this->salida .= "      (OPCIONAL)";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">ESPECIALIDAD:";
		$this->salida .= "      </td>";
		if($_SESSION['solfr']['especieleg']==NULL)
		{
			$this->salida .= "  <td align=\"center\" width=\"60%\">";
			$this->salida .= "NO DEFINIDA";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\" width=\"10%\">";
			$this->salida .= "".' '."";
			$this->salida .= "<a href=\"".ModuloGetURL('app','SolicitudesFrecuentes','user','ElegirEspecialidadSolfre',
			array('indice'=>4))."\"><img src=\"".GetThemePath()."/images/especialidadin.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
		}
		else
		{
			$this->salida .= "  <td align=\"center\" width=\"60%\">";
			$this->salida .= "".$_SESSION['solfr']['desespeleg']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\" width=\"10%\">";
			$this->salida .= "".' '."";
			$this->salida .= "<a href=\"".ModuloGetURL('app','SolicitudesFrecuentes','user','ElegirEspecialidadSolfre',
			array('indice'=>4))."\"><img src=\"".GetThemePath()."/images/especialidad.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
		}
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$medicafre=$this->BuscarConsultarMedicamentosSolfre(
		$_SESSION['solfr']['departeleg'],$_SESSION['solfr']['especieleg']);
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >MEDICAMENTO</td>";
		$this->salida .= "      <td width=\"60%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"32%\">ESPECIALIDAD</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($medicafre);
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
			$this->salida .= "".$medicafre[$i]['codigo_producto']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$medicafre[$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($medicafre[$i]['des1']<>NULL)
			{
				$this->salida .= "".$medicafre[$i]['des1']."";
			}
			else
			{
				$this->salida .= "NO DEFINIDA";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($medicafre))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARÓN MEDICAMENTOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','MedicamentosSolfre');
		$this->salida .= "  <form name=\"solfrecu1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var1=$this->RetornarBarraMeCo();
		if(!empty($var1))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var1;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ConsultarMedicamentosSolfre',
		array('codigosolf'=>$_REQUEST['codigosolf'],'descrisolf'=>$_REQUEST['descrisolf']));
		$this->salida .= "  <form name=\"solfrecu2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">MEDICAMENTO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigosolf\" value=\"".$_REQUEST['codigosolf']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrisolf\" value=\"".$_REQUEST['descrisolf']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ConsultarMedicamentosSolfre');
		$this->salida .= "  <form name=\"solfrecu3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraMe()//Barra paginadora de los medicamentos
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
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','RelacionarMedicamentosSolfre',array('conteo'=>$this->conteo,
		'codigosolf'=>$_REQUEST['codigosolf'],'descrisolf'=>$_REQUEST['descrisolf']));
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

	function RetornarBarraMeCo()//Barra paginadora de los medicamentos
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
		$accion=ModuloGetURL('app','SolicitudesFrecuentes','user','ConsultarMedicamentosSolfre',array('conteo'=>$this->conteo,
		'codigosolf'=>$_REQUEST['codigosolf'],'descrisolf'=>$_REQUEST['descrisolf']));
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

}//fin clase
?>
