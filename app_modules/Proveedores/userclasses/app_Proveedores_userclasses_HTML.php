
<?php

/**
* Modulo de Proveedores (PHP).
*
* Modulo para el manejo de la contratación de los proveedores
* (determinar las características de los planes)
* Esta contratación es por prestación de servicios
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Proveedores_userclasses_HTML.php
*
* Clase que establece los métodos de acceso y búsqueda de información con
* las opciones de los detalles de la contratación de los proveedores
**/

class app_Proveedores_userclasses_HTML extends app_Proveedores_user
{
	function app_Proveedores_user_HTML()
	{
		$this->app_Proveedores_user();//Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Determina las empresas, en las cuales el usuario tiene permisos
	function PrincipalProvee2()//Selecciona las empresas disponibles
	{
		UNSET($_SESSION['provee']);
		UNSET($_SESSION['propla']);
		UNSET($_SESSION['propl1']);
		if($this->UsuariosProvee()==false)
		{
			return false;
		}
		return true;
	}

	//Función que muestra la información y da las opciones sobre los planes de proveedores
	function ProvedorProvee()//LLama a todas las opciones posibles
	{
		if($_SESSION['provee']['empresa']==NULL)
		{
			$_SESSION['provee']['empresa']=$_REQUEST['permisosprovee']['empresa_id'];
			$_SESSION['provee']['razonso']=$_REQUEST['permisosprovee']['descripcion1'];
		}
		UNSET($_SESSION['propla']);
		UNSET($_SESSION['propl1']);
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - PLANES DE LOS PROVEEDORES RELACIONADOS CON LA EMPRESA');
		$accion=ModuloGetURL('app','Proveedores','user','IngresaProvProvee');
		$this->salida .= "  <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CONTRATACIÓN - PROVEEDORES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"3%\" >No.</td>";
		$this->salida .= "      <td width=\"12%\">No. - CONTRATO</td>";
		$this->salida .= "      <td width=\"30%\">DESCRIPCIÓN DEL CONTRATO</td>";
		$this->salida .= "      <td width=\"30%\">CLIENTE</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"4%\" >MENÚ</td>";
		$this->salida .= "      <td width=\"8%\" >TARIFARIOS</td>";
		$this->salida .= "      <td width=\"8%\" >SERVICIOS</td>";
		$this->salida .= "      </tr>";
		$planempr=$this->BuscarProvedorPlanes($_SESSION['provee']['empresa']);
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
			$this->salida .= "".$planempr[$i]['num_contrato']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$planempr[$i]['plan_descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$planempr[$i]['nombre_tercero']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($planempr[$i]['estado'] == 1)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','CambiarEstadoProvProvee',
				array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'ctradescri'=>$_REQUEST['ctradescri'],
				'codigoctra'=>$_REQUEST['codigoctra'],'planelegc'=>$planempr[$i]['plan_proveedor_id'],
				'estado'=>$planempr[$i]['estado'])) ."\"><img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','CambiarEstadoProvProvee',
				array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'ctradescri'=>$_REQUEST['ctradescri'],
				'codigoctra'=>$_REQUEST['codigoctra'],'planelegc'=>$planempr[$i]['plan_proveedor_id'],
				'estado'=>$planempr[$i]['estado'])) ."\"><img src=\"".GetThemePath()."/images/pinactivo.png\" border=\"0\"></a>";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','ProvedorPlanProvee',
			array('planelegc'=>$planempr[$i]['plan_proveedor_id'],'descelegc'=>$planempr[$i]['plan_descripcion'],
			'nombelegc'=>$planempr[$i]['nombre_tercero'],'numeelegc'=>$planempr[$i]['num_contrato'])) ."\">
			<img src=\"".GetThemePath()."/images/pplan.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','TarifarioProvProvee',
			array('planelegc'=>$planempr[$i]['plan_proveedor_id'],'descelegc'=>$planempr[$i]['plan_descripcion'],
			'nombelegc'=>$planempr[$i]['nombre_tercero'],'numeelegc'=>$planempr[$i]['num_contrato'])) ."\">
			<img src=\"".GetThemePath()."/images/ptarifario.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','ServiciosProvProvee',
			array('planelegc'=>$planempr[$i]['plan_proveedor_id'],'descelegc'=>$planempr[$i]['plan_descripcion'],
			'nombelegc'=>$planempr[$i]['nombre_tercero'],'numeelegc'=>$planempr[$i]['num_contrato'])) ."\">
			<img src=\"".GetThemePath()."/images/servicios.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		if(empty($planempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"8\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PLAN RELACIONADO A LA EMPRESA'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"50%\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO  PLAN\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Proveedores','user','TercerServicProvee');
		$this->salida .= "      <form name=\"proveedor1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"prove\" value=\"PROVEEDORES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "      <br><table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Proveedores','user','DepartamentosProvee');
		$this->salida .= "      <form name=\"proveedor2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"cargos\" value=\"CARGOS INTERNOS\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Proveedores','user','PrincipalProvee2');
		$this->salida .= "      <form name=\"proveedor3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"EMPRESAS\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraProvee();
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
		$accion=ModuloGetURL('app','Proveedores','user','ProvedorProvee',
		array('codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri']));
		$this->salida .= "  <form name=\"proveedor4\" action=\"$accion\" method=\"post\">";
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
		$accion=ModuloGetURL('app','Proveedores','user','ProvedorProvee');
		$this->salida .= "  <form name=\"proveedor5\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraProvee()//Barra paginadora de los planes clientes
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
		$accion=ModuloGetURL('app','Proveedores','user','ProvedorProvee',array('conteo'=>$this->conteo,
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

	//Función que permite establecer cuales son los cargos que presta mi empresa
	function DepartamentosProvee()//Válida los departamentos de la empresa para establecer los cargos
	{
		UNSET($_SESSION['propla']['departeleg']);
		UNSET($_SESSION['propla']['desdepeleg']);
		UNSET($_SESSION['propla']['grucarinpr']);//grupos
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - CARGOS POR DEPARTAMENTO');
		$this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DEPARTAMENTOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','Proveedores','user','GruposCargosInternosProvee');
		$this->salida .= "      <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"50%\">DEPARTAMENTOS A RELACIONAR<br>CON LOS CARGOS</td>";
		$this->salida .= "      <td width=\"50%\">";
		$departamento=$this->BuscarDepartamentosProvee($_SESSION['provee']['empresa']);
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
		$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"consultar\" value=\"RELACIONAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
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
		$accion=ModuloGetURL('app','Proveedores','user','ProvedorProvee');
		$this->salida .= "  <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LOS PLANES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que muestra los grupos del cups por departamento
	function GruposCargosInternosProvee()//Informa los cargos y los grupo del cups
	{
		if($_POST['departam']==-1 AND $_SESSION['propla']['departeleg']==NULL)
		{
			$this->frmError["MensajeError"]="SELECCIONE UN DEPARTAMENTO";
			$this->uno=1;
			$this->DepartamentosProvee();
			return true;
		}
		if($_SESSION['propla']['departeleg']==NULL)
		{
			$var=explode(',',$_REQUEST['departam']);
			$_SESSION['propla']['departeleg']=$var[0];
			$_SESSION['propla']['desdepeleg']=$var[1];
		}
		UNSET($_SESSION['propla']['grucarinpr']);//grupos
		UNSET($_SESSION['propla']['grcaineleg']);//indice
		UNSET($_SESSION['propla']['cargosinpr']);//cargos
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - CARGOS POR DEPARTAMENTO');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">GRUPOS TIPOS CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['desdepeleg']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"32%\">GRUPOS TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"41%\">TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"10%\">No. CARGOS<br>TIPO CARGO</td>";
		$this->salida .= "      <td width=\"10%\">No. CARGOS<br>PRESTADOS</td>";
		$this->salida .= "      <td width=\"7%\" >DETALLES</td>";
		$this->salida .= "      </tr>";
		$_SESSION['propla']['grucarinpr']=$this->BuscarGruposCargosInternosProvee($_SESSION['propla']['departeleg']);
		$ciclo=sizeof($_SESSION['propla']['grucarinpr']);
		$j=0;
		for($i=0;$i<$ciclo;)
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
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['propla']['grucarinpr'][$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td colspan=\"4\">";
			$this->salida .= "      <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['propla']['grucarinpr'][$i]['grupo_tipo_cargo']==$_SESSION['propla']['grucarinpr'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr $color>";
				$this->salida .= "  <td width=\"60%\">";
				$this->salida .= "  ".$_SESSION['propla']['grucarinpr'][$k]['des2']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td width=\"15%\" align=\"center\">";
				$this->salida .= "  ".$_SESSION['propla']['grucarinpr'][$k]['cantidad']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td width=\"15%\" align=\"center\">";
				$this->salida .= "  ".$_SESSION['propla']['grucarinpr'][$k]['grupoesta']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td width=\"10%\" align=\"center\">";
				if($_SESSION['propla']['grucarinpr'][$k]['cantidad']<>0)
				{
					$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','CargosInternosProvee',
					array('indicecain'=>$k)) ."\"><img src=\"".GetThemePath()."/images/pexcepcion.png\" border=\"0\"></a>";
				}
				else
				{
					$this->salida .= "<img src=\"".GetThemePath()."/images/pinexcepcion.png\" border=\"0\">";
				}
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$i=$k;
		}
		if(empty($_SESSION['propla']['grucarinpr']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"3\">";
			$this->salida .= "'NO SE ENCONTRARÓN GRUPOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Proveedores','user','DepartamentosProvee');
		$this->salida .= "  <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A PROVEEDORES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que trae todos los cargos del cups contra los que presto por departamento
	function CargosInternosProvee()//Muestro los cargos del cups y los guardados por departamento
	{
		if($_SESSION['propla']['grcaineleg']==NULL)
		{
			$_SESSION['propla']['grcaineleg']=$_REQUEST['indicecain'];
		}
		UNSET($_SESSION['propla']['cargosinpr']);
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - CARGOS POR DEPARTAMENTO');
		$accion=ModuloGetURL('app','Proveedores','user','ValidarCargosInternosProvee',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
		$this->salida .= "  <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CARGOS DEL CUPS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['desdepeleg']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['grucarinpr'][$_SESSION['propla']['grcaineleg']]['des1']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['propla']['grucarinpr'][$_SESSION['propla']['grcaineleg']]['des2']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">CARGO</td>";
		$this->salida .= "      <td width=\"80%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['propla']['cargosinpr']=$this->BuscarCargosInternosProvee(
		$_SESSION['propla']['grucarinpr'][$_SESSION['propla']['grcaineleg']]['grupo_tipo_cargo'],
		$_SESSION['propla']['grucarinpr'][$_SESSION['propla']['grcaineleg']]['tipo_cargo'],$_SESSION['propla']['departeleg']);
		$ciclo=sizeof($_SESSION['propla']['cargosinpr']);
		$j=0;
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
			$this->salida .= "".$_SESSION['propla']['cargosinpr'][$i]['cargocups']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['propla']['cargosinpr'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['propla']['cargosinpr'][$i]['cargoproveedor']<>NULL)
			{
				$this->salida .= "<input type=\"checkbox\" name=\"cargcupspr".$i."\" value=1 checked>";
			}
			else
			{
				$this->salida .= "<input type=\"checkbox\" name=\"cargcupspr".$i."\" value=1>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['propla']['cargosinpr']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"3\">";
			$this->salida .= "'NO SE ENCONTRARÓN CARGOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"2\">AYUDAS</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"left\" width=\"80%\">GUARDAR TODOS LOS CARGOS DEL GRUPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"20%\">";
		$this->salida .= "      <input type=\"radio\" name=\"ayuda\" value=1>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"left\" width=\"80%\">ELIMINAR TODOS LOS CARGOS DEL GRUPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"20%\">";
		$this->salida .= "      <input type=\"radio\" name=\"ayuda\" value=2>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"left\" width=\"80%\">GUARDAR TODOS LOS CARGOS DE LA PÁGINA ACTUAL</td>";
		$this->salida .= "      <td align=\"center\" width=\"20%\">";
		$this->salida .= "      <input type=\"radio\" name=\"ayuda\" value=3>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"left\" width=\"80%\">ELIMINAR TODOS LOS CARGOS DE LA PÁGINA ACTUAL</td>";
		$this->salida .= "      <td align=\"center\" width=\"20%\">";
		$this->salida .= "      <input type=\"radio\" name=\"ayuda\" value=4>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"50%\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR CARGOS\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Proveedores','user','GruposCargosInternosProvee');
		$this->salida .= "      <form name=\"proveedor1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A GRUPOS\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraProCarInt();
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
		$accion=ModuloGetURL('app','Proveedores','user','CargosInternosProvee',
		array('codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
		$this->salida .= "  <form name=\"proveedor2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"32\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Proveedores','user','CargosInternosProvee');
		$this->salida .= "  <form name=\"proveedor3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que confirma el borrado de los cargos internos de un grupo
	function ConfirmarCargosInternosProvee()//Confirma que el usuario este seguro de borrar todos los cargos internos
	{
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - CARGOS POR DEPARTAMENTO');
		$this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table width=\"100%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td colspan=\"2\" class=\"titulo1\" align=\"center\">CONFIRMAR OPERACIÓN";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td colspan=\"2\" class=\"label\" align=\"center\">";
		$this->salida .= "      <br>DESEA BORRAR TODOS LOS CARGOS DEL<br>
		GRUPO: <label class=\"label_error\">".$_SESSION['propla']['grucarinpr'][$_SESSION['propla']['grcaineleg']]['des1']."</label><br>
		Y TIPO CARGO: <label class=\"label_error\">".$_SESSION['propla']['grucarinpr'][$_SESSION['propla']['grcaineleg']]['des2']."</label><br><br>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$accion1=ModuloGetURL('app','Proveedores','user','BorrarCargosInternosProvee',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
		$this->salida .= "      <form name=\"formabuscar\" action=\"$accion1\" method=\"post\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Boton1\" value=\"ELIMINAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <td align=\"center\">";
		$accion2=ModuloGetURL('app','Proveedores','user','CargosInternosProvee',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
		$this->salida .= "      <form name=\"formabuscar\" action=\"$accion2\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Boton2\" value=\"CANCELAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite realizar mantenimiento sobre el plan elegido
	function ProvedorPlanProvee()//Opciones del plan
	{
		if(empty($_SESSION['propla']['planelpr']))
		{
			$_SESSION['propla']['planelpr']=$_REQUEST['planelegc'];
			$_SESSION['propla']['descelpr']=$_REQUEST['descelegc'];
			$_SESSION['propla']['numeelpr']=$_REQUEST['numeelegc'];
			$_SESSION['propla']['nombelpr']=$_REQUEST['nombelegc'];//nombre del cliente - tercero
		}
		UNSET($_SESSION['propla']['cancelo']);
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - PLAN PROVEEDOR');
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">OPCIONES DEL PLAN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['numeelpr']."".' --- '."".$_SESSION['propla']['descelpr']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['propla']['nombelpr']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" class=\"modulo_table_list_title\">";
		$this->salida .= "MENÚ";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"70%\" align=\"center\" class=\"label\">";
		$this->salida .= "CONSULTAR INFORMACIÓN DEL CONTRATO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','MostrarProvProvee') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"70%\" align=\"center\" class=\"label\">";
		$this->salida .= "MODIFICAR INFORMACIÓN DEL CONTRATO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','ModificarProvProvee') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','Proveedores','user','ProvedorProvee');
		$this->salida .= "  <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LOS PLANES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que captura la información de los proveedores
	function IngresaProvProvee()//Válida los datos del plan de contratación para proveedores
	{
		if($_REQUEST['tipoTerceroId']<>NULL AND $_REQUEST['codigo']<>NULL AND $_REQUEST['nombre']<>NULL)
		{
			$_POST['tipoTerceroId']=$_REQUEST['tipoTerceroId'];
			$_POST['codigo']=$_REQUEST['codigo'];
			$_POST['nombre']=$_REQUEST['nombre'];
		}
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - DATOS DEL PLAN PROVEEDOR');
		$mostrar=ReturnClassBuscador('servicios','','','contratacion','');
		$this->salida .=$mostrar;
		$this->salida .="</script>\n";
		$accion=ModuloGetURL('app','Proveedores','user','ValidarEleccionProvee');//GuardarProvPlanProvee
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL PLAN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
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
		$this->salida .= "      <td width=\"13%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("nombre")."\">CLIENTE:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"62%\">";
		$this->salida .= "      <input type=\"text\" name=\"nombre\" size=\"48\" class=\"input-text\" value=\"".$_POST['nombre']."\" READONLY>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td align=\"center\" colspan=\"2\">";
		$this->salida .= "      <input type=\"button\" name=\"proveedor\" value=\"CLIENTE\" onclick=abrirVentana() class=\"input-submit\">";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("codigo")."\">CÓDIGO:</label>";//&nbsp&nbsp&nbsp;
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" name=\"codigo\" size=\"33\" class=\"input-text\" value=\"".$_POST['codigo']."\" READONLY>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td align=\"center\" colspan=\"4\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"EDITAR PROVEEDOR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("descrictra")."\">DESCRIPCIÓN DEL CONTRATO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_POST['descrictra']."\" maxlength=\"60\" size=\"48\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("contactoctra")."\">CONTACTO(S)<br>(NOMBRE COMPLETO Y TELEFÓNOS):</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <textarea class=\"input-text\" name=\"contactoctra\" cols=\"45\" rows=\"4\">".$_POST['contactoctra']."</textarea>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("usuariosctra")."\">ENCARGADO:</label>";
		$this->salida .= "      </td>";
		$usuarios=$this->BuscarEncargadosProvee($_SESSION['provee']['empresa']);
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"usuariosctra\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($usuarios);
		for($i=0;$i<$ciclo;$i++)
		{
			if($usuarios[$i]['usuario_id']==$_POST['usuariosctra'])
			{
				$this->salida .="<option value=\"".$usuarios[$i]['usuario_id']."\" selected>".$usuarios[$i]['nombre']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$usuarios[$i]['usuario_id']."\">".$usuarios[$i]['nombre']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("numeroctra")."\">NÚMERO DEL CONTRATO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"numeroctra\" value=\"".$_POST['numeroctra']."\" maxlength=\"20\" size=\"22\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("valorctra")."\">VALOR DEL CONTRATO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"valorctra\" value=\"".$_POST['valorctra']."\" maxlength=\"17\" size=\"22\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("feinictra")."\">FECHA INICIAL:</label>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_POST['feinictra']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      ".ReturnOpenCalendario('contratacion','feinictra','/')."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("fefinctra")."\">FECHA FINAL:</label>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_POST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      ".ReturnOpenCalendario('contratacion','fefinctra','/')."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("telefono1")."\">LÍNEAS DE ATENCIÓN -- AUTORIZACIONES:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <textarea class=\"input-text\" name=\"telefono1\" cols=\"45\" rows=\"4\">".$_POST['telefono1']."</textarea>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("observacion")."\">OBSERVACIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <textarea class=\"input-text\" name=\"observacion\" cols=\"45\" rows=\"4\">".$_POST['observacion']."</textarea>";
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
		$accion=ModuloGetURL('app','Proveedores','user','ProvedorProvee');
		$this->salida .= "  <form name=\"contratacion1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que establece si se van a guardar los datos o se va a editar o agregar un nuevo proveedor
	function ValidarEleccionProvee()//Válida que operación debe hacer
	{
		if($_POST['guardar']=="GUARDAR")
		{
			$this->GuardarProvPlanProvee();
		}
		else if($_POST['guardar']=="EDITAR PROVEEDOR")
		{
			$this->GuardarTerceroProvee();//1
		}
		return true;
	}

	//Función que permite guardar el tercero como un proveedor de servicios de salud
	function IngresaTecerServicProvee($destino)//Válida los datos como prestador de servicios
	{
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - PROVEEDOR DE SERVICIOS DE SALUD');
		$accion=ModuloGetURL('app','Proveedores','user','ValidarIngresaTercerServicProvee',
		array('tipoTerceroId'=>$_POST['tipoTerceroId'],'codigo'=>$_POST['codigo'],
		'nombre'=>$_POST['nombre'],'destino'=>$destino));
		$this->salida .= "  <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL PROVEEDOR (TERCERO)</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_POST['tipoTerceroId']."".' - '."".$_POST['codigo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROVEEDOR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_POST['nombre']."";
		$this->salida .= "      <input type=\"hidden\" name=\"tipoTerceroId\" value=\"".$_POST['tipoTerceroId']."\">";
		$this->salida .= "      <input type=\"hidden\" name=\"codigo\" value=\"".$_POST['codigo']."\">";
		$this->salida .= "      <input type=\"hidden\" name=\"nombre\" value=\"".$_POST['nombre']."\">";
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
		$this->salida .= "      <td width=\"30%\" class=\"".$this->SetStyle("estado")."\">ESTADO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\" class=\"label\">ACTIVO";
		if($_POST['estado']==1)
		{
			$this->salida .= "      <input type='radio' name=\"estado\" value=1 checked>";
		}
		else
		{
			$this->salida .= "      <input type='radio' name=\"estado\" value=1>";
		}
		$this->salida .= "  INACTIVO";
		if($_POST['estado']==2)
		{
			$this->salida .= "      <input type='radio' name=\"estado\" value=2 checked>";
		}
		else
		{
			$this->salida .= "      <input type='radio' name=\"estado\" value=2>";
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
		if($destino==1)
		{
			$accion=ModuloGetURL('app','Proveedores','user','IngresaProvProvee',
			array('tipoTerceroId'=>$_POST['tipoTerceroId'],'codigo'=>$_POST['codigo'],
			'nombre'=>$_POST['nombre'],'destino'=>1));
		}
		else if($destino==2)
		{
			$accion=ModuloGetURL('app','Proveedores','user','ModificarProvProvee',
			array('tipoTerceroId'=>$_POST['tipoTerceroId'],'codigo'=>$_POST['codigo'],
			'nombre'=>$_POST['nombre'],'destino'=>2));
		}
		$this->salida .= "  <form name=\"proveedor1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que lista los terceros que son proveedores de servicios de salud
	function TercerServicProvee()//Muestra información básica sobre los proveedores
	{
		UNSET($_SESSION['propla']['tipoidtpro']);
		UNSET($_SESSION['propla']['terceropro']);
		UNSET($_SESSION['propla']['nombretpro']);
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - PROVEEDOR DE SERVICIOS DE SALUD');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','ProvedorProvee') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS DEL PROVEEDOR (TERCERO)</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"20%\">TIPO</td>";
		$this->salida .= "      <td width=\"14%\">DOCUMENTO</td>";
		$this->salida .= "      <td width=\"46%\">NOMBRE</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"10%\">TELÉFONO</td>";
		$this->salida .= "      <td width=\"5%\" >PLANES</td>";
		$this->salida .= "      </tr>";
		$tercempr=$this->BuscarTercerServicProvee($_SESSION['provee']['empresa']);
		$terceros=$this->TercerosProvee();
		$j=0;
		$ciclo=sizeof($tercempr);
		$ciclo1=sizeof($terceros);
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
			$this->salida .= "  <td>";
			for($k=0;$k<$ciclo1;$k++)
			{
				if($tercempr[$i]['tipo_id_tercero']==$terceros[$k]['tipo_id_tercero'])
				{
					$this->salida .= "".$terceros[$k]['descripcion']."";
				}
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$tercempr[$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$tercempr[$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($tercempr[$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\">";
			}
			else if($tercempr[$i]['estado']==0)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$tercempr[$i]['telefono']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','PlanesProvee',//GruposCargosProvee
			array('tipoidt'=>$tercempr[$i]['tipo_id_tercero'],'terceroid'=>$tercempr[$i]['tercero_id'],
			'nombreter'=>$tercempr[$i]['nombre_tercero'])) ."\"><img src=\"".GetThemePath()."/images/pplan.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($tercempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"5\">";
			$this->salida .= "'NO SE ENCONTRARÓN PROVEEDORES'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"center\">";
		$accion=ModuloGetURL('app','Proveedores','user','ProvedorProvee');
		$this->salida .= "      <form name=\"proveedor1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"prove\" value=\"VOLVER A PLANES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraProSer();
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
		$accion=ModuloGetURL('app','Proveedores','user','TercerServicProvee',
		array('tipodoctra'=>$_REQUEST['tipodoctra'],'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
		$this->salida .= "  <form name=\"proveedor2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">TIPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <select name=\"tipodoctra\" class=\"select\">";
		$this->salida .= "  <option value=\"\">----</option>";
		for($k=0;$k<$ciclo1;$k++)
		{
			if($terceros[$k]['tipo_id_tercero']==$_REQUEST['tipodoctra'])
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\" selected>".$terceros[$k]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$terceros[$k]['tipo_id_tercero']."\">".$terceros[$k]['descripcion']."</option>";
			}
		}
		$this->salida .= "  </select>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">DOCUMENTO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"32\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Proveedores','user','TercerServicProvee');
		$this->salida .= "  <form name=\"proveedor3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function PlanesProvee()//
	{
		if($_SESSION['propla']['tipoidtpro']==NULL)
		{
			$_SESSION['propla']['tipoidtpro']=$_REQUEST['tipoidt'];
			$_SESSION['propla']['terceropro']=$_REQUEST['terceroid'];
			$_SESSION['propla']['nombretpro']=$_REQUEST['nombreter'];
		}
		UNSET($_SESSION['propla']['planpreleg']);
		UNSET($_SESSION['propla']['numeroplpr']);
		UNSET($_SESSION['propla']['planprdesc']);
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - PLAN(ES) DEL PROVEEDOR DE SERVICIOS DE SALUD');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','TercerServicProvee') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS DEL PLAN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['tipoidtpro']."".' -- '."".$_SESSION['propla']['terceropro']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['propla']['nombretpro']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"45%\">DESCRIPCIÓN DEL PLAN</td>";
		$this->salida .= "      <td width=\"17%\">NÚMERO DE CONTRATO</td>";
		$this->salida .= "      <td width=\"12%\">FECHA FINAL</td>";
		$this->salida .= "      <td width=\"12%\">FECHA INICIAL</td>";
		$this->salida .= "      <td width=\"7%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"7%\" >CARGOS</td>";
		$this->salida .= "      </tr>";
		$tercempr=$this->BuscarPlanesProvee($_SESSION['provee']['empresa'],
		$_SESSION['propla']['tipoidtpro'],$_SESSION['propla']['terceropro']);
		$j=0;
		$ciclo=sizeof($tercempr);
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
			$this->salida .= "  <td>";
			$this->salida .= "".$tercempr[$i]['plan_descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$tercempr[$i]['num_contrato']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$fecha=explode('-',$tercempr[$i]['fecha_inicio']);
			$this->salida .= "".$fecha[2].'/'.$fecha[1].'/'.$fecha[0]."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$fecha=explode('-',$tercempr[$i]['fecha_final']);
			$this->salida .= "".$fecha[2].'/'.$fecha[1].'/'.$fecha[0]."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($tercempr[$i]['estado']==1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/pactivo.png\" border=\"0\">";
			}
			else if($tercempr[$i]['estado']==0)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/pinactivo.png\" border=\"0\">";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','GruposCargosProvee',array(
			'planelegpr'=>$tercempr[$i]['plan_proveedor_id'],'numcontrpr'=>$tercempr[$i]['num_contrato'],
			'plandescpr'=>$tercempr[$i]['plan_descripcion'])) ."\"><img src=\"".GetThemePath()."/images/pcargos.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($tercempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"6\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PLAN PARA ESTE PROVEEDOR'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Proveedores','user','TercerServicProvee');
		$this->salida .= "  <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A PROVEEDORES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que selecciona por los grupos y subgrupos, los cargos que son prestados por el proveedor
	function GruposCargosProvee()//Válida por grupo y subgrupo, los cargos a contratar
	{
		if($_SESSION['propla']['planpreleg']==NULL)
		{
			$_SESSION['propla']['planpreleg']=$_REQUEST['planelegpr'];
			$_SESSION['propla']['numeroplpr']=$_REQUEST['numcontrpr'];
			$_SESSION['propla']['planprdesc']=$_REQUEST['plandescpr'];
		}
		UNSET($_SESSION['propla']['grupcargpr']);//grupos
		UNSET($_SESSION['propla']['datogrupro']);//datos
		UNSET($_SESSION['propla']['cargcupspr']);//cargos
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - PROVEEDOR DE SERVICIOS DE SALUD');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','PlanesProvee') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">GRUPOS TIPOS CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['tipoidtpro']."".' -- '."".$_SESSION['propla']['terceropro']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['propla']['nombretpro']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['numeroplpr']."".' -- '."".$_SESSION['propla']['planprdesc']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"32%\">GRUPOS TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"61%\">TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"7%\" >DETALLES</td>";
		$this->salida .= "      </tr>";
		$_SESSION['propla']['grupcargpr']=$this->BuscarGruposCargosProvee($_SESSION['provee']['empresa'],
		$_SESSION['propla']['tipoidtpro'],$_SESSION['propla']['terceropro'],$_SESSION['propla']['planpreleg']);
		$ciclo=sizeof($_SESSION['propla']['grupcargpr']);
		$j=0;
		for($i=0;$i<$ciclo;)
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
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['propla']['grupcargpr'][$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td colspan=\"2\">";
			$this->salida .= "      <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['propla']['grupcargpr'][$i]['grupo_tipo_cargo']==$_SESSION['propla']['grupcargpr'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr $color>";
				$this->salida .= "  <td width=\"90%\">";
				$this->salida .= "  ".$_SESSION['propla']['grupcargpr'][$k]['des2']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td width=\"10%\" align=\"center\">";
				$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','CargosProvee',
				array('indicegrca'=>$k)) ."\"><img src=\"".GetThemePath()."/images/cargos.png\" border=\"0\"></a>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$i=$k;
		}
		if(empty($_SESSION['propla']['grupcargpr']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"3\">";
			$this->salida .= "'NO SE ENCONTRARÓN GRUPOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Proveedores','user','PlanesProvee');
		$this->salida .= "  <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A PLANES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite mostrar los cargos prestados por el proveedor de servicios de salud
	function CargosProvee()//Válida los cargos del cups y los que están contratados
	{
		if($_SESSION['propla']['datogrupro']['gruposcarp']==NULL)
		{
			$_SESSION['propla']['datogrupro']['gruposcarp']=$_SESSION['propla']['grupcargpr'][$_REQUEST['indicegrca']]['grupo_tipo_cargo'];
			$_SESSION['propla']['datogrupro']['desgrucarp']=$_SESSION['propla']['grupcargpr'][$_REQUEST['indicegrca']]['des1'];
			$_SESSION['propla']['datogrupro']['subgrucarp']=$_SESSION['propla']['grupcargpr'][$_REQUEST['indicegrca']]['tipo_cargo'];
			$_SESSION['propla']['datogrupro']['dessubcarp']=$_SESSION['propla']['grupcargpr'][$_REQUEST['indicegrca']]['des2'];
			UNSET($_SESSION['propla']['grupcargpr']);
		}
		UNSET($_SESSION['propla']['cargcupspr']);
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - PROVEEDOR DE SERVICIOS DE SALUD');
		$accion=ModuloGetURL('app','Proveedores','user','ValidarCargosProvee',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
		$this->salida .= "  <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','GruposCargosProvee') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CARGOS DEL CUPS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['tipoidtpro']."".' -- '."".$_SESSION['propla']['terceropro']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['propla']['nombretpro']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['numeroplpr']."".' -- '."".$_SESSION['propla']['planprdesc']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['datogrupro']['desgrucarp']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['propla']['datogrupro']['dessubcarp']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"6%\" >CARGO<br>CUPS</td>";
		$this->salida .= "      <td width=\"64%\">DESCRIPCIÓN DEL CARGO TARIFARIO<br>DESCRIPCIÓN DEL CARGO CUPS EQUIVALENTE</td>";
		$this->salida .= "      <td width=\"14%\">TARIFARIO</td>";
		$this->salida .= "      <td width=\"5%\" >EQUI.</td>";
		$this->salida .= "      <td width=\"5%\" >CONT.</td>";
		$this->salida .= "      <td width=\"6%\" >ELEGIR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['propla']['cargcupspr']=$this->BuscarCargosProvee($_SESSION['provee']['empresa'],
		$_SESSION['propla']['tipoidtpro'],$_SESSION['propla']['terceropro'],$_SESSION['propla']['planpreleg'],
		$_SESSION['propla']['datogrupro']['gruposcarp'],$_SESSION['propla']['datogrupro']['subgrucarp']);
		$ciclo=sizeof($_SESSION['propla']['cargcupspr']);
		$j=0;
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
			$this->salida .= "".$_SESSION['propla']['cargcupspr'][$i]['cargocontr']."";
			if($_SESSION['propla']['cargcupspr'][$i]['cargo_base']<>NULL)
			{
				$this->salida .= "<br><label class=\"label_mark\">".$_SESSION['propla']['cargcupspr'][$i]['cargo_base']."</label>";
			}
			else
			{
				$this->salida .= "<br><label class=\"label_mark\">**</label>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['propla']['cargcupspr'][$i]['des1']."";
			if($_SESSION['propla']['cargcupspr'][$i]['cargo_base']<>NULL)
			{
				$this->salida .= "<br><label class=\"label_mark\">".$_SESSION['propla']['cargcupspr'][$i]['des3']."</label>";
			}
			else
			{
				$this->salida .= "<br><label class=\"label_mark\">SIN EQUIVALENCIA</label>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$_SESSION['propla']['cargcupspr'][$i]['des2']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['propla']['cargcupspr'][$i]['cargo_base']==NULL)
			{
				$this->salida .= "NO";
			}
			else
			{
				$this->salida .= "SI";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['propla']['cargcupspr'][$i]['cargoexcep']<>NULL
			AND $_SESSION['propla']['cargcupspr'][$i]['sw_no_contratado']==1)
			{
				$this->salida .= "NO";
			}
			else
			{
				$this->salida .= "SI";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['propla']['cargcupspr'][$i]['cargoprove']<>NULL)
			{
				$this->salida .= "<input type=\"checkbox\" name=\"cargcupspr".$i."\" value=1 checked>";
			}
			else
			{
				$this->salida .= "<input type=\"checkbox\" name=\"cargcupspr".$i."\" value=1>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['propla']['cargcupspr']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"3\">";
			$this->salida .= "'NO SE ENCONTRARÓN CARGOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"2\">AYUDAS</td>";
		$this->salida .= "      </tr>";
		/*$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"left\" width=\"80%\">GUARDAR TODOS LOS CARGOS DEL GRUPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"20%\">";
		$this->salida .= "      <input type=\"radio\" name=\"ayuda\" value=1>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"left\" width=\"80%\">ELIMINAR TODOS LOS CARGOS DEL GRUPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"20%\">";
		$this->salida .= "      <input type=\"radio\" name=\"ayuda\" value=2>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";*/
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"left\" width=\"80%\">GUARDAR TODOS LOS CARGOS DE LA PÁGINA ACTUAL</td>";
		$this->salida .= "      <td align=\"center\" width=\"20%\">";
		$this->salida .= "      <input type=\"radio\" name=\"ayuda\" value=3>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"left\" width=\"80%\">ELIMINAR TODOS LOS CARGOS DE LA PÁGINA ACTUAL</td>";
		$this->salida .= "      <td align=\"center\" width=\"20%\">";
		$this->salida .= "      <input type=\"radio\" name=\"ayuda\" value=4>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"50%\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR CARGOS\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Proveedores','user','GruposCargosProvee');
		$this->salida .= "      <form name=\"proveedor1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A GRUPOS\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraProCar();
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
		$accion=ModuloGetURL('app','Proveedores','user','CargosProvee',
		array('codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
		$this->salida .= "  <form name=\"proveedor2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"32\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Proveedores','user','CargosProvee');
		$this->salida .= "  <form name=\"proveedor3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que confirma el borrado de los cargos de un grupo
	function ConfirmarCargosProvee()//Confirma que el usuario este seguro de borrar todos los cargos
	{
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - PROVEEDOR DE SERVICIOS DE SALUD');
		$this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table width=\"100%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td colspan=\"2\" class=\"titulo1\" align=\"center\">CONFIRMAR OPERACIÓN";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td colspan=\"2\" class=\"label\" align=\"center\">";
		$this->salida .= "      <br>DESEA BORRAR TODOS LOS CARGOS DEL<br>
		GRUPO: <label class=\"label_error\">".$_SESSION['propla']['grupcargpr'][$_SESSION['propla']['grucareleg']]['des1']."</label><br>
		Y TIPO CARGO: <label class=\"label_error\">".$_SESSION['propla']['grupcargpr'][$_SESSION['propla']['grucareleg']]['des2']."</label><br><br>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$accion1=ModuloGetURL('app','Proveedores','user','BorrarCargosProvee',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      <form name=\"formabuscar\" action=\"$accion1\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Boton1\" value=\"ELIMINAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$accion2=ModuloGetURL('app','Proveedores','user','CargosProvee',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      <form name=\"formabuscar\" action=\"$accion2\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Boton2\" value=\"CANCELAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que modifica los datos del plan proveedor
	function ModificarProvProvee()//Llama a validar plan proveedor y vuelve al menu principal
	{
		if(!($this->uno == 1) OR $_SESSION['propla']['cancelo']==1)
		{
			$planeleg=$this->MostrarProvedorPlanes($_SESSION['propla']['planelpr']);
			$_POST['descrictraM']=$planeleg['plan_descripcion'];
			if($_REQUEST['destino']==2)
			{
				$_POST['tipoTerceroId']=$_REQUEST['tipoTerceroId'];
				$_POST['nombre']=$_REQUEST['nombre'];
				$_POST['codigo']=$_REQUEST['codigo'];
			}
			else
			{
				$_POST['tipoTerceroId']=$planeleg['tipo_id_tercero'];
				$_POST['nombre']=$_SESSION['propla']['nombelpr'];
				$_POST['codigo']=$planeleg['tercero_id'];
			}
			$_POST['numeroctraM']=$planeleg['num_contrato'];
			$_POST['valorctraM']=$planeleg['monto_contrato'];
			$fecha=explode('-',$planeleg['fecha_inicio']);
			$_POST['feinictraM']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
			$fecha=explode('-',$planeleg['fecha_final']);
			$_POST['fefinctraM']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
			$_POST['observacionM']=$planeleg['observacion'];
			$_POST['contactoctraM']=$planeleg['contacto'];
			$_POST['telefono1M']=$planeleg['lineas_contacto'];
			$_POST['usuariosctraM']=$planeleg['usuario_id'];
			UNSET($_SESSION['propla']['cancelo']);
		}
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - DATOS DEL PLAN PROVEEDOR - MODIFICAR');
		$mostrar=ReturnClassBuscador('servicios','','','contratacion','');
		$this->salida .=$mostrar;
		$this->salida .="</script>\n";
		$accion=ModuloGetURL('app','Proveedores','user','ValidarMEleccionProvee');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL PLAN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
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
		$this->salida .= "      <td width=\"13%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("nombre")."\">CLIENTE:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"62%\">";
		$this->salida .= "      <input type=\"text\" name=\"nombre\" size=\"48\" class=\"input-text\" value=\"".$_POST['nombre']."\" READONLY>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td align=\"center\" colspan=\"2\">";
		$this->salida .= "      <input type=\"button\" name=\"proveedor\" value=\"CLIENTE\" onclick=abrirVentana() class=\"input-submit\">";
		$this->salida .= "      </td>";
		$this->salida .= "      <td><label class=\"".$this->SetStyle("codigo")."\">CÓDIGO:</label>";//&nbsp&nbsp&nbsp;
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" name=\"codigo\" size=\"33\" class=\"input-text\" value=\"".$_POST['codigo']."\" READONLY>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td align=\"center\" colspan=\"4\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"EDITAR PROVEEDOR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("descrictraM")."\">DESCRIPCIÓN DEL CONTRATO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descrictraM\" value=\"".$_POST['descrictraM']."\" maxlength=\"60\" size=\"48\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("contactoctraM")."\">CONTACTO(S)<br>(NOMBRE COMPLETO Y TELEFÓNOS):</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <textarea class=\"input-text\" name=\"contactoctraM\" cols=\"45\" rows=\"4\">".$_POST['contactoctraM']."</textarea>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("usuariosctraM")."\">ENCARGADO:</label>";
		$this->salida .= "      </td>";
		$usuarios=$this->BuscarEncargadosProvee($_SESSION['provee']['empresa']);
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"usuariosctraM\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($usuarios);
		for($i=0;$i<$ciclo;$i++)
		{
			if($usuarios[$i]['usuario_id']==$_POST['usuariosctraM'])
			{
				$this->salida .="<option value=\"".$usuarios[$i]['usuario_id']."\" selected>".$usuarios[$i]['nombre']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$usuarios[$i]['usuario_id']."\">".$usuarios[$i]['nombre']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("numeroctraM")."\">NÚMERO DEL CONTRATO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"numeroctraM\" value=\"".$_POST['numeroctraM']."\" maxlength=\"20\" size=\"22\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("valorctraM")."\">VALOR DEL CONTRATO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"valorctraM\" value=\"".$_POST['valorctraM']."\" maxlength=\"17\" size=\"22\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"50%\"><label class=\"".$this->SetStyle("feinictraM")."\">FECHA INICIAL:</label>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictraM\" value=\"".$_POST['feinictraM']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      ".ReturnOpenCalendario('proveedor','feinictraM','/')."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"50%\"><label class=\"".$this->SetStyle("fefinctraM")."\">FECHA FINAL:</label>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctraM\" value=\"".$_POST['fefinctraM']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      ".ReturnOpenCalendario('proveedor','fefinctraM','/')."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("telefono1M")."\">LÍNEAS DE ATENCIÓN -- AUTORIZACIONES:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <textarea class=\"input-text\" name=\"telefono1M\" cols=\"45\" rows=\"4\">".$_POST['telefono1M']."</textarea>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("observacionM")."\">OBSERVACIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <textarea class=\"input-text\" name=\"observacionM\" cols=\"45\" rows=\"4\">".$_POST['observacionM']."</textarea>";
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
		$accion=ModuloGetURL('app','Proveedores','user','ProvedorPlanProvee');
		$this->salida .= "  <form name=\"contratacion1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que establece si se van a guardar los datos o se va a editar o modificar un proveedor
	function ValidarMEleccionProvee()//Válida que operación debe hacer
	{
		if($_POST['guardar']=="GUARDAR")
		{
			$this->ModificarProvPlanProvee();
		}
		else if($_POST['guardar']=="EDITAR PROVEEDOR")
		{
			$this->ModificarTerceroProvee();//1
		}
		return true;
	}

	//Muestra toda la información relacionada al plan proveedor
	function MostrarProvProvee()//Vuelve a la función de donde fue llamada
	{
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - DATOS DEL PLAN PROVEEDOR');
		$accion=ModuloGetURL('app','Proveedores','user','ProvedorPlanProvee');
		$this->salida .= "  <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL PLAN</legend>";
		$planeleg=$this->MostrarProvedorPlanes($_SESSION['propla']['planelpr']);
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro><br>";
		$this->salida .= "      <td width=\"45%\">NOMBRE DE LA EMPRESA";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"55%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>NÚMERO DEL CONTRATO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$planeleg['num_contrato']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>DESCRIPCIÓN DEL CONTRATO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$planeleg['plan_descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>CLIENTE";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['propla']['nombelpr']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>TIPO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$planeleg['tipo_id_tercero']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>CÓDIGO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$planeleg['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>CONTACTO(S)";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$planeleg['contacto']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>ENCARGADO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$planeleg['nombre']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>VALOR DEL CONTRATO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$planeleg['monto_contrato']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>SALDO DEL CONTRATO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$planeleg['saldo_contrato']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>FECHA INICIAL (dd/mm/aaaa)";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$fecini=explode('-',$planeleg['fecha_inicio']);
		$this->salida .= "      ".$fecini[2]."".'/'."".$fecini[1]."".'/'."".$fecini[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td >FECHA FINAL (dd/mm/aaaa)";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$fecini=explode('-',$planeleg['fecha_final']);
		$this->salida .= "      ".$fecini[2]."".'/'."".$fecini[1]."".'/'."".$fecini[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>LÍNEAS DE ATENCIÓN -- AUTORIZACIONES";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$planeleg['lineas_contacto']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>OBSERVACIÓN";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if(!empty($planeleg['observacion']))
		{
			$this->salida .= "".$planeleg['observacion']."";
		}
		else
		{
			$this->salida .= "'NO SE ENCONTRÓ INFORMACIÓN'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"><br>";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraProvedor()//Barra paginadora de los planes proveedores
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
		$accion=ModuloGetURL('app','Proveedores','user','ProvedorProvee',array('conteo'=>$this->conteo,
		'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
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

	function RetornarBarraTaPro()//Barra paginadora de Tarifario Proveedores
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
		$accion=ModuloGetURL('app','Proveedores','user','TariExceProvProvee',array('conteo'=>$this->conteo,
		'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
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

	function RetornarBarraProSer()//Barra paginadora de Proveedores de Servicios
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
		$accion=ModuloGetURL('app','Proveedores','user','TercerServicProvee',array('conteo'=>$this->conteo,
		'tipodoctra'=>$_REQUEST['tipodoctra'],'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
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

	function RetornarBarraProCar()//Barra paginadora de Cargos del Proveedor
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
		$accion=ModuloGetURL('app','Proveedores','user','CargosProvee',array('conteo'=>$this->conteo,
		'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
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

	function RetornarBarraProCarInt()//Barra paginadora de Cargos Internos de la Empresa
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
		$accion=ModuloGetURL('app','Proveedores','user','CargosInternosProvee',array('conteo'=>$this->conteo,
		'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
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

	function RetornarBarraTaCaCo()//Barra paginadora de los cargos del grupo y subgrupo
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
		$accion=ModuloGetURL('app','Proveedores','user','ConsulCargosTarifarioProvee',array('conteo'=>$this->conteo,
		'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra'],'tarifactra'=>$_REQUEST['tarifactra']));
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

	//Función que muestra la tarifa de un plan proveedores
	function TarifarioProvProvee()//Valida los cambios y determina si se debe insertar o modificar
	{
		if(empty($_SESSION['propla']['planelpr']))
		{
			$_SESSION['propla']['planelpr']=$_REQUEST['planelegc'];
			$_SESSION['propla']['descelpr']=$_REQUEST['descelegc'];
			$_SESSION['propla']['numeelpr']=$_REQUEST['numeelegc'];
			$_SESSION['propla']['nombelpr']=$_REQUEST['nombelegc'];//nombre del cliente - tercero
		}
		UNSET($_SESSION['propl1']['grutaprovc']);
		UNSET($_SESSION['propl1']['dattarprov']);
		UNSET($_SESSION['propl1']['cargotaric']);
		UNSET($_SESSION['propl1']['dacacoprov']);
		UNSET($_SESSION['propl1']['carconprov']);
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - PLAN TARIFARIO PROVEEDOR');
		$accion=ModuloGetURL('app','Proveedores','user','ValidarTarifarioProvProvee');
		$this->salida .= "  <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','ProvedorProvee') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">TARIFARIO POR GRUPOS Y SUBGRUPOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['numeelpr']."".' --- '."".$_SESSION['propla']['descelpr']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['propla']['nombelpr']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"25%\">GRUPOS TARIFARIOS</td>";
		$this->salida .= "      <td width=\"39%\">SUBGRUPOS TARIFARIOS</td>";
		$this->salida .= "      <td width=\"18%\">TARIFARIO</td>";
		$this->salida .= "      <td width=\"10%\">PORCE.</td>";
		$this->salida .= "      <td width=\"8%\" >DETALLES</td>";
		$this->salida .= "      </tr>";
		$_SESSION['propl1']['grutaprovc']=$this->BuscarGruposProvProvee($_SESSION['propla']['planelpr']);
		$tarifa=$this->BuscarTarifariosProvee();//combos
		$j=0;
		$ciclo=sizeof($_SESSION['propl1']['grutaprovc']);
		for($i=0;$i<$ciclo;)
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
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['propl1']['grutaprovc'][$i]['grupo_tarifario_descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "          <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			$c=0;
			while($_SESSION['propl1']['grutaprovc'][$i]['grupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$k]['grupo_tarifario_id'])
			{
				$l=$k;
				$a=$l;
				while($_SESSION['propl1']['grutaprovc'][$k]['grupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$l]['grupo_tarifario_id']
				AND $_SESSION['propl1']['grutaprovc'][$k]['subgrupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$l]['subgrupo_tarifario_id'])
				{
					if($_SESSION['propl1']['grutaprovc'][$l]['porcentaje']<>NULL)
					{
						$a=$l;
					}
					$c++;
					$l++;
				}
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				$this->salida .= "".$_SESSION['propl1']['grutaprovc'][$a]['subgrupo_tarifario_descripcion']."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k=$l;
			}
			$this->salida .= "          </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['propl1']['grutaprovc'][$i]['grupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$k]['grupo_tarifario_id'])
			{
				$l=$k;
				$a=$l;
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\">";
				$grabar=0;
				while($_SESSION['propl1']['grutaprovc'][$k]['grupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$l]['grupo_tarifario_id']
				AND $_SESSION['propl1']['grutaprovc'][$k]['subgrupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$l]['subgrupo_tarifario_id'])
				{
					if($_SESSION['propl1']['grutaprovc'][$l]['porcentaje']<>NULL)
					{
						$a=$l;
					}
					$l++;
				}
				$l=$k;
				$this->salida .= "  <select name=\"tarifprovc".$a."\" class=\"select\">";
				$this->salida .= "  <option value=\"\">----</option>";
				while($_SESSION['propl1']['grutaprovc'][$k]['grupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$l]['grupo_tarifario_id']
				AND $_SESSION['propl1']['grutaprovc'][$k]['subgrupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$l]['subgrupo_tarifario_id'])
				{
					$m=0;
					while($tarifa[$m]['tarifario_id']<>$_SESSION['propl1']['grutaprovc'][$l]['tarifario_id'])
					{
						$m++;
					}
					if($_SESSION['propl1']['grutaprovc'][$l]['porcentaje']<>NULL OR $_POST['taritodoct']==$tarifa[$m]['tarifario_id'])
					{
						$this->salida .="<option value=\"".$tarifa[$m]['tarifario_id']."\" selected>".$tarifa[$m]['descripcion']."</option>";
					}
					else if($_SESSION['propl1']['grutaplanc'][$l]['porcentaje']==NULL OR $_POST['taritodoct']==$tarifa[$m]['tarifario_id'])
					{
						$this->salida .="<option value=\"".$tarifa[$m]['tarifario_id']."\">".$tarifa[$m]['descripcion']."</option>";
					}
					$l++;
				}
				$this->salida .= "  </select>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k=$l;
			}
			$this->salida .= "  </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['propl1']['grutaprovc'][$i]['grupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$k]['grupo_tarifario_id'])
			{
				$l=$k;
				$a=$l;
				while($_SESSION['propl1']['grutaprovc'][$k]['grupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$l]['grupo_tarifario_id']
				AND $_SESSION['propl1']['grutaprovc'][$k]['subgrupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$l]['subgrupo_tarifario_id'])
				{
					if($_SESSION['propl1']['grutaprovc'][$l]['porcentaje']<>NULL)
					{
						$a=$l;
					}
					$l++;
				}
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\">";
				$_POST['porceprovc'.$a]=$_SESSION['propl1']['grutaprovc'][$a]['porcentaje'];
				if(!empty($_POST['porctodoct']))
				{
					$_POST['porceprovc'.$a]=$_POST['porctodoct'];
				}
				if($_POST['porceprovc'.$a]==NULL)
				{
					$_POST['porceprovc'.$a]='0.0000';
				}
				$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porceprovc".$a."\" value=\"".$_POST['porceprovc'.$a]."\" maxlength=\"8\" size=\"8\">";
				$this->salida .= "%";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k=$l;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['propl1']['grutaprovc'][$i]['grupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$k]['grupo_tarifario_id'])
			{
				$l=$k;
				$a=$l;
				while($_SESSION['propl1']['grutaprovc'][$k]['grupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$l]['grupo_tarifario_id']
				AND $_SESSION['propl1']['grutaprovc'][$k]['subgrupo_tarifario_id']==$_SESSION['propl1']['grutaprovc'][$l]['subgrupo_tarifario_id'])
				{
					if($_SESSION['propl1']['grutaprovc'][$l]['porcentaje']<>NULL)
					{
						$a=$l;
					}
					$l++;
				}
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"30\" align=\"center\" width=\"50%\">";
				if($_SESSION['propl1']['grutaprovc'][$a]['porcentaje']<>NULL)
				{
					$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','TariExceProvProvee',
					array('indicetaripr'=>$a)) ."\"><img src=\"".GetThemePath()."/images/pexcepcion.png\" border=\"0\"></a>";
				}
				else
				{
					$this->salida .= "<img src=\"".GetThemePath()."/images/pinexcepcion.png\" border=\"0\">";
				}
				$this->salida .= "  </td>";
				$this->salida .= "  <td height=\"30\" align=\"center\" width=\"50%\">";
				$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','ConsulCargosTarifarioProvee',
				array('indiconcar'=>$a)) ."\"><img src=\"".GetThemePath()."/images/pcargoscon.png\" border=\"0\"></a>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k=$l;
			}
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$i=$i+$c;
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR TARIFARIOS\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('app','Proveedores','user','ProvedorProvee');
		$this->salida .= "  <form name=\"proveedor1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER A LOS PLANES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\">";
		$this->salida .= "  <fieldset><legend class=\"field\">AYUDA PARA EL TARIFARIO</legend>";
		$accion=ModuloGetURL('app','Proveedores','user','TarifarioProvProvee');
		$this->salida .= "      <form name=\"proveedor2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"50%\">TARIFARIO</td>";
		$this->salida .= "      <td width=\"25%\">PORCE.</td>";
		$this->salida .= "      <td width=\"25%\"> </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"50%\" align=\"center\">";
		$this->salida .= "      <select name=\"taritodoct\" class=\"select\">";
		$this->salida .= "      <option value=\"\">----</option>";
		$ciclo=sizeof($tarifa);
		for($l=0;$l<$ciclo;$l++)
		{
			if($_POST['taritodoct'] == $tarifa[$l]['tarifario_id'])
			{
				$this->salida .="<option value=\"".$tarifa[$l]['tarifario_id']."\" selected>".$tarifa[$l]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$tarifa[$l]['tarifario_id']."\">".$tarifa[$l]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"25%\" align=\"center\">";
		if(empty($_POST['porctodoct']))
		{
			$_POST['porctodoct']='0';
		}
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"porctodoct\" value=\"".$_POST['porctodoct']."\" maxlength=\"8\" size=\"8\">";
		$this->salida .= "%";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"25%\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"aplicar\" value=\"APLICAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  <br>";
		$accion=ModuloGetURL('app','Proveedores','user','ValidarCopiarTarifarioProvProvee');//cambiar
		$this->salida .= "  <form name=\"contratari\" action=\"$accion\" method=\"post\">";
		$ru='app_modules/Proveedores/selectortariserv.js';
		$rus='app_modules/Proveedores/selectortariserv.php';
		$this->salida .= "  <script languaje='javascript' src=\"$ru\"></script>";
		$this->salida .= "  <fieldset><legend class=\"field\">AYUDA PARA CONTRATOS ANTERIORES (COPIAR)</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" colspan=\"3\">";
		$this->salida .= "      OPCIONES DE BÚSQUEDA";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\" width=\"30%\">NÚMERO DE CONTRATO</td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" colspan=\"2\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"tarifario1\" value=\"".$_POST['tarifario1']."\" maxlength=\"20\" size=\"20\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"tarifario2\" value=\"".$_POST['tarifario2']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\" width=\"30%\">EMPRESAS</td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" colspan=\"2\">";
		$empresas=$this->BuscarEmpresasProvee();
		$this->salida .= "      <select name=\"empresacon\" class=\"select\">";
		$this->salida .= "      <option value=\"-1\">TODAS</option>";
		$ciclo=sizeof($empresas);
		for($i=0;$i<$ciclo;$i++)
		{
			if($empresas[$i]['empresa_id']==$_POST['empresacon'])
			{
				$this->salida .="<option value=\"".$empresas[$i]['empresa_id']."\" selected>".$empresas[$i]['razon_social']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$empresas[$i]['empresa_id']."\">".$empresas[$i]['razon_social']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\" width=\"30%\">ESTADO DEL PLAN</td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" colspan=\"2\">";
		$this->salida .= "      <select name=\"estadocont\" class=\"select\">";
		$this->salida .= "      <option value=\"1\">TODOS</option>";
		if($_POST['estadocont']==2)
		{
			$this->salida .= "<option value=\"2\" selected>ACTIVOS</option>";
		}
		else
		{
			$this->salida .= "<option value=\"2\">ACTIVOS</option>";
		}
		if($_POST['estadocont']==3)
		{
			$this->salida .= "<option value=\"3\" selected>INACTIVOS</option>";
		}
		else
		{
			$this->salida .= "<option value=\"3\">INACTIVOS</option>";
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"center\" colspan=\"3\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"BUSCAR TARIFARIO\" onclick=\"abrirVentana('Buscador_Tarifario','$rus',this.form)\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" colspan=\"3\">";
		$this->salida .= "      OPCIONES PARA GUARDAR POR CARGOS";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"60%\">OPCIONES</td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">GRUPOS</td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">EXCEPCIONES</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"center\">TARIFARIO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		if($_POST['copiartari']==1)
		{
			$this->salida .= "<input type=\"checkbox\" name=\"copiartari\" value=1 checked>";
		}
		else
		{
			$this->salida .= "<input type=\"checkbox\" name=\"copiartari\" value=1>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		if($_POST['copiartariex']==1)
		{
			$this->salida .= "<input type=\"checkbox\" name=\"copiartariex\" value=1 checked>";
		}
		else
		{
			$this->salida .= "<input type=\"checkbox\" name=\"copiartariex\" value=1>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td colspan=\"3\" align=\"center\" class=\"label_error\">ADVERTENCIA: ESTA OPCIÓN MODIFICA TODO EL TARIFARIO</td>";// Y LOS SERVICIOS DEL CONTRATO
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR OPCIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function ConsulCargosTarifarioProvee()//
	{
		if($_SESSION['propl1']['dacacoprov']['grupo_tarifario_id']==NULL)
		{
			$_SESSION['propl1']['dacacoprov']['grupo_tarifario_id']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indiconcar']]['grupo_tarifario_id'];
			$_SESSION['propl1']['dacacoprov']['grupo_tarifario_descripcion']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indiconcar']]['grupo_tarifario_descripcion'];
			$_SESSION['propl1']['dacacoprov']['subgrupo_tarifario_id']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indiconcar']]['subgrupo_tarifario_id'];
			$_SESSION['propl1']['dacacoprov']['subgrupo_tarifario_descripcion']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indiconcar']]['subgrupo_tarifario_descripcion'];
			$_SESSION['propl1']['dacacoprov']['tarifario_id']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indiconcar']]['tarifario_id'];
			$_SESSION['propl1']['dacacoprov']['descripcion']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indiconcar']]['descripcion'];
			$_SESSION['propl1']['dacacoprov']['porcentaje']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indiconcar']]['porcentaje'];
			UNSET($_SESSION['propl1']['grutaprovc']);
		}
		UNSET($_SESSION['propl1']['carconprov']);
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - CONSULTAR CARGOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','TarifarioProvProvee') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CARGOS POR GRUPOS Y SUBGRUPOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['numeelpr']."".' --- '."".$_SESSION['propla']['descelpr']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['propla']['nombeleg']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">GRUPO TARIFARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"25%\">";
		$this->salida .= "      ".$_SESSION['propl1']['dacacoprov']['grupo_tarifario_descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">SUBGRUPO TARIFARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"25%\">";
		$this->salida .= "      ".$_SESSION['propl1']['dacacoprov']['subgrupo_tarifario_descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">TARIFARIO CONTRATADO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"20%\">";
		if($_SESSION['propl1']['dacacoprov']['porcentaje']<>NULL)
		{
			$this->salida .= "".$_SESSION['propl1']['dacacoprov']['descripcion']."";
		}
		else
		{
			$this->salida .= "NO TIENE UN TARIFARIO CONTRATADO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CARGO</td>";
		$this->salida .= "      <td width=\"70%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"16%\">TARIFARIO</td>";
		$this->salida .= "      <td width=\"6%\" >CONTRA.</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$_SESSION['propl1']['carconprov']=$this->BuscarConsulCargosTarifarioProvee($_SESSION['propla']['planelpr'],
		$_SESSION['propl1']['dacacoprov']['grupo_tarifario_id'],$_SESSION['propl1']['dacacoprov']['subgrupo_tarifario_id']);
		$ciclo=sizeof($_SESSION['propl1']['carconprov']);
		for($i=0;($i<$ciclo);$i++)
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
			$this->salida .= "<tr $color>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['propl1']['carconprov'][$i]['cargo']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['propl1']['carconprov'][$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['propl1']['carconprov'][$i]['destarifario']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['propl1']['carconprov'][$i]['tarifario_id']<>NULL)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['propl1']['carconprov']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"4\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO EN EL TARIFARIO PARA ESTE GRUPO Y SUBGRUPO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\">";
		$accion=ModuloGetURL('app','Proveedores','user','TarifarioProvProvee');
		$this->salida .= "  <form name=\"contrata1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER   AL   TARIFARIO\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraTaCaCo();
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
		$accion=ModuloGetURL('app','Proveedores','user','ConsulCargosTarifarioProvee',array(
		'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra'],'tarifactra'=>$_REQUEST['tarifactra']));
		$this->salida .= "  <form name=\"contrata2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"50\" size=\"35\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$tarifa=$this->BuscarTarifariosProvee();//combos
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"30%\" class=\"label\">TARIFARIO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <select name=\"tarifactra\" class=\"select\">";
		$this->salida .= "  <option value=\"\">----</option>";
		$ciclo=sizeof($tarifa);
		for($l=0;$l<$ciclo;$l++)
		{
			if($_REQUEST['tarifactra'] == $tarifa[$l]['tarifario_id'])
			{
				$this->salida .="<option value=\"".$tarifa[$l]['tarifario_id']."\" selected>".$tarifa[$l]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$tarifa[$l]['tarifario_id']."\">".$tarifa[$l]['descripcion']."</option>";
			}
		}
		$this->salida .= "  </select>";
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
		$accion=ModuloGetURL('app','Proveedores','user','ConsulCargosTarifarioProvee');
		$this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que muestra los cargos y excepciones del plan tarifario proveedores
	function TariExceProvProvee()//Válida los cambios, elimina, guarda o modifica
	{
		if($_SESSION['propl1']['dattarprov']['grupo_tarifario_id']==NULL)
		{
			$_SESSION['propl1']['dattarprov']['grupo_tarifario_id']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indicetaripr']]['grupo_tarifario_id'];
			$_SESSION['propl1']['dattarprov']['grupo_tarifario_descripcion']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indicetaripr']]['grupo_tarifario_descripcion'];
			$_SESSION['propl1']['dattarprov']['subgrupo_tarifario_id']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indicetaripr']]['subgrupo_tarifario_id'];
			$_SESSION['propl1']['dattarprov']['subgrupo_tarifario_descripcion']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indicetaripr']]['subgrupo_tarifario_descripcion'];
			$_SESSION['propl1']['dattarprov']['tarifario_id']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indicetaripr']]['tarifario_id'];
			$_SESSION['propl1']['dattarprov']['descripcion']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indicetaripr']]['descripcion'];
			$_SESSION['propl1']['dattarprov']['porcentaje']=$_SESSION['propl1']['grutaprovc'][$_REQUEST['indicetaripr']]['porcentaje'];
			UNSET($_SESSION['propl1']['grutaprovc']);
		}
		UNSET($_SESSION['propl1']['cargotaric']);//borra los indices de los cargos
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - PLAN TARIFARIO PROVEEDOR - EXCEPCIONES');
		$accion=ModuloGetURL('app','Proveedores','user','ValidarExceTariProvProvee',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
		$this->salida .= "  <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Proveedores','user','TarifarioProvProvee') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">EXCEPCIONES POR CARGOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['numeelpr']."".' --- '."".$_SESSION['propla']['descelpr']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['propla']['nombelpr']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"15%\">GRUPO TARIFARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"35%\">";
		$this->salida .= "      ".$_SESSION['propl1']['dattarprov']['grupo_tarifario_descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"15%\">SUBGRUPO TARIFARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"35%\">";
		$this->salida .= "      ".$_SESSION['propl1']['dattarprov']['subgrupo_tarifario_descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">TARIFARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"20%\">";
		$this->salida .= "      ".$_SESSION['propl1']['dattarprov']['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">PORCENTAJE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"25%\">";
		$this->salida .= "      ".$_SESSION['propl1']['dattarprov']['porcentaje'].' '.'%'."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"6%\" >CARGO</td>";
		$this->salida .= "      <td width=\"60%\">DESCRIPCIÓN</td>";//56
		$this->salida .= "      <td width=\"4%\" >N.</td>";//56
		$this->salida .= "      <td width=\"12%\">PRECIO</td>";
		$this->salida .= "      <td width=\"10%\">PORCEN.</td>";
		$this->salida .= "      <td width=\"8%\" >NO CONT.</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$_SESSION['propl1']['cargotaric']=$this->BuscarCarTarProvProvee($_SESSION['propla']['planelpr'],
		$_SESSION['propl1']['dattarprov']['grupo_tarifario_id'],$_SESSION['propl1']['dattarprov']['subgrupo_tarifario_id']);
		$ciclo=sizeof($_SESSION['propl1']['cargotaric']);
		for($i=0;($i<$ciclo);$i++)
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
			$this->salida .= "<tr $color>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['propl1']['cargotaric'][$i]['cargo']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['propl1']['cargotaric'][$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['propl1']['cargotaric'][$i]['nivel']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			if($_SESSION['propl1']['cargotaric'][$i]['sw_uvrs']=='1')
			{
				$this->salida .= "".$_SESSION['propl1']['cargotaric'][$i]['precio']."".' UVR'."";
			}
			else
			{
				$this->salida .= "".'$ '."".$_SESSION['propl1']['cargotaric'][$i]['precio']."";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['propl1']['cargotaric'][$i]['excepcion']==1 AND
			$_SESSION['propl1']['cargotaric'][$i]['sw_no_contratado']==0)
			{
				$_POST['porexctra'.$i]=$_SESSION['propl1']['cargotaric'][$i]['porcentaje'];
			}
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"porexctra".$i."\" value=\"".$_POST['porexctra'.$i]."\" maxlength=\"8\" size=\"8\">";
			$this->salida .= "%";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['propl1']['cargotaric'][$i]['sw_no_contratado']==1)
			{
				$this->salida .= "<input type=\"checkbox\" name=\"contratado".$i."\" value=1 checked>";
			}
			else
			{
				$this->salida .= "<input type=\"checkbox\" name=\"contratado".$i."\" value=1>";
			}
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR EXCEPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Proveedores','user','TarifarioProvProvee');
		$this->salida .= "  <form name=\"proveedor1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER   AL   TARIFARIO\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraTaPro();
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
		$accion=ModuloGetURL('app','Proveedores','user','TariExceProvProvee',
		array('codigoctra'=>$_REQUEST['codigoctra'],'descrictra'=>$_REQUEST['descrictra']));
		$this->salida .= "  <form name=\"proveedor2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"8\" size=\"8\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descrictra\" value=\"".$_REQUEST['descrictra']."\" maxlength=\"50\" size=\"35\">";
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
		$accion=ModuloGetURL('app','Proveedores','user','TariExceProvProvee');
		$this->salida .= "  <form name=\"proveedor3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que establece los servicios que el proveedor prestará
	function ServiciosProvProvee()//Válida según los servicios a contratar con el proveedor
	{
		if(empty($_SESSION['propla']['planelpr']))
		{
			$_SESSION['propla']['planelpr']=$_REQUEST['planelegc'];
			$_SESSION['propla']['descelpr']=$_REQUEST['descelegc'];
			$_SESSION['propla']['numeelpr']=$_REQUEST['numeelegc'];
			$_SESSION['propla']['nombelpr']=$_REQUEST['nombelegc'];//nombre del cliente - tercero
		}
		UNSET($_SESSION['propl1']['serviprov']);
		UNSET($_SESSION['propl1']['nivelprov']);
		$this->salida  = ThemeAbrirTabla('PROVEEDORES - PLAN DE SERVICIOS PROVEEDOR');
		$accion=ModuloGetURL('app','Proveedores','user','ValidarServiciosProvProvee');
		$this->salida .= "  <form name=\"proveedor\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">SERVICIOS POR NIVELES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['provee']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PLAN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['propla']['numeelpr']."".' --- '."".$_SESSION['propla']['descelpr']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CLIENTE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['propla']['nombelpr']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"100%\">SERVICIOS</td>";
		$this->salida .= "      </tr>";
		$_SESSION['propl1']['serviprov']=$this->BuscarServiciosProvee();
		$_SESSION['propl1']['nivelprov']=$this->BuscarNivelesAteProvee();
		$matrizp=$this->BuscarServicioProvProvee($_SESSION['propla']['planelpr']);
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td><br>";
		$this->salida .= "          <table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\" cellpadding=\"1\">";
		$this->salida .= "              <tr class=\"modulo_table_list_title\">";
		$this->salida .= "              <td>TIPO</td>";
		$ciclo=sizeof($_SESSION['propl1']['nivelprov']);
		for($m=0;$m<$ciclo;$m++)
		{
			$this->salida .= "          <td>";
			$this->salida .= "".$_SESSION['propl1']['nivelprov'][$m]['descripcion_corta']."";
			$this->salida .= "          </td>";
		}
		$this->salida .= "              </tr>";
		$ciclo1=sizeof($_SESSION['propl1']['serviprov']);
		for($l=0;$l<$ciclo1;$l++)
		{
			$this->salida .= "          <tr align=\"center\" class=\"modulo_list_claro\">";
			$this->salida .= "          <td>";
			$this->salida .= "".$_SESSION['propl1']['serviprov'][$l]['descripcion']."";
			$this->salida .= "          </td>";
			for($m=0;$m<$ciclo;$m++)//sizeof($_SESSION['propl1']['nivelprov'])
			{
				$this->salida .= "      <td>";
				if(!empty($matrizp[$_SESSION['propl1']['serviprov'][$l]['servicio']][$m+1]))
				{
					$this->salida .= "<input type=\"checkbox\" name=\"nivelprovctra".$l.$m."\" value=".$_SESSION['propl1']['serviprov'][$l]['servicio']." checked>";
				}
				else
				{
					$this->salida .= "<input type=\"checkbox\" name=\"nivelprovctra".$l.$m."\" value=".$_SESSION['propl1']['serviprov'][$l]['servicio'].">";
				}
				$this->salida .= "      </td>";
			}
			$this->salida .= "          </tr>";
		}
		$this->salida .= "          </table>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR TARIFARIOS\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('app','Proveedores','user','ProvedorProvee');
		$this->salida .= "  <form name=\"proveedor1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER A LOS PLANES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

}//fin de la clase
?>
