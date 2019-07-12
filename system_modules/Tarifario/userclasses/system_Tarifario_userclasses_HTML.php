
<?php

/**
* Modulo de Tarifarios (PHP).
*
* Modulo para el mantenimiento de los cargos del tarifario cups y de
* los demás tarifarios, así como las equivalencias entre los mismos
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* system_Tarifario_userclasses_HTML.php
*
* Modulo que permite realizar un mantenimineto a todos los cargos del cups
* en cuanto al código, la descripción, la clasificación y demás datos,
* igualmente me permite realizar el mantenimiento a los cargos de los tarifarios
* que están en la aplicación, y establecer la equivalencia entre los cargos
**/

class system_Tarifario_userclasses_HTML extends system_Tarifario_user
{
	function system_Tarifario_user_HTML()
	{
		$this->system_Tarifario_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos del Tarifario
	function PrincipalTarifa()//Llama a todas las opciones posibles
	{
		UNSET($_SESSION['tarifa']);
		UNSET($_SESSION['tarif1']);
		UNSET($_SESSION['taricu']);
		UNSET($_SESSION['taric1']);
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - OPCIONES');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CARGOS DEL TARIFARIO CUPS</legend>";
		$this->salida .= "      <table border=\"1\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"70%\">PERIODOS DE TRAMITES POR CARGOS</td>";
		$this->salida .= "      <td class=\"label\" width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','Tarifario','user','GruposCargosTarifa') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/pcargos.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"70%\">MANTENIMIENTO DE LOS CARGOS</td>";
		$this->salida .= "      <td class=\"label\" width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','Tarifario','user','MantenimientoTarifa') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/cargosmanten.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <br><fieldset><legend class=\"field\">TARIFARIOS</legend>";
		$this->salida .= "      <table border=\"1\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$accion=ModuloGetURL('system','Tarifario','user','ConsultarTarifa');
		$this->salida .= "      <form name=\"tarifarios1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\" class=\"label_mark\" colspan=\"2\"><br>EL TARIFARIO BASE SELECCIONADO ES EL CUPS<br><br>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\" width=\"50%\"><label class=\"label\">CONSULTAR TARIFARIO</label></td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" width=\"50%\">";
		$tarifarios=$this->BuscarTarifariosTari();
		$this->salida .= "      <select name=\"tarifacons\" class=\"select\">";
		$this->salida .= "      <option value=\"-1\">-- SELECCIONE --</option>";
		$ciclo=sizeof($tarifarios);
		for($i=0;$i<$ciclo;$i++)
		{
			if($tarifarios[$i]['tarifario_id']==$_POST['tarifacons'])
			{
				$this->salida .="<option value=\"".$tarifarios[$i]['tarifario_id']."".','."".$tarifarios[$i]['descripcion']."\" selected>".$tarifarios[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$tarifarios[$i]['tarifario_id']."".','."".$tarifarios[$i]['descripcion']."\">".$tarifarios[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"consultar\" value=\"CONSULTAR\"><br><br>";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$accion=ModuloGetURL('system','Tarifario','user','EquivalenciaTarifa');
		$this->salida .= "      <form name=\"tarifarios2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\" width=\"50%\"><label class=\"label\">EQUIVALENCIAS DEL TARIFARIO</label></td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" width=\"50%\">";
		$this->salida .= "      <select name=\"tarifaequi\" class=\"select\">";
		$this->salida .= "      <option value=\"-1\">-- SELECCIONE --</option>";
		for($i=0;$i<$ciclo;$i++)
		{
			if($tarifarios[$i]['tarifario_id']==$_POST['tarifaequi'])
			{
				$this->salida .="<option value=\"".$tarifarios[$i]['tarifario_id']."".','."".$tarifarios[$i]['descripcion']."\" selected>".$tarifarios[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$tarifarios[$i]['tarifario_id']."".','."".$tarifarios[$i]['descripcion']."\">".$tarifarios[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"equivale\" value=\"RELACIONAR\"><br><br>";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$accion=ModuloGetURL('system','Tarifario','user','MenuMantenTarifa');
		$this->salida .= "      <form name=\"tarifarios3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\" width=\"50%\"><label class=\"label\">MANTENIMIENTO DEL TARIFARIO</label></td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" width=\"50%\">";
		$this->salida .= "      <select name=\"tarifaequi\" class=\"select\">";
		$this->salida .= "      <option value=\"-1\">-- SELECCIONE --</option>";
		for($i=0;$i<$ciclo;$i++)
		{
			if($tarifarios[$i]['tarifario_id']==$_POST['tarifaequi'])
			{
				$this->salida .="<option value=\"".$tarifarios[$i]['tarifario_id']."".','."".$tarifarios[$i]['descripcion']."\" selected>".$tarifarios[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$tarifarios[$i]['tarifario_id']."".','."".$tarifarios[$i]['descripcion']."\">".$tarifarios[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"equivale\" value=\"MANTENIMIENTO\"><br><br>";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td align=\"center\">";
		$accion=ModuloGetURL('system','Menu');
		$this->salida .= "  <form name=\"tarifarios1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <br><input class=\"input-submit\" type=\"submit\" name=\"menu\" value=\"MENÚ\"><br>";
		$this->salida .= "  </form>";
		$this->salida .= "  </td></tr>";
		if($this->uno == 1)
		{
			$this->salida .= "  <tr><td>";
			$this->salida .= "  <br><table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "  </table><br>";
			$this->salida .= "  </td></tr>";
		}
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function CambiarConsultaTarifa()//
	{
		if($_SESSION['tarifa']['vistaseleg']==1)
		{
			$_SESSION['tarifa']['vistaseleg']=2;
		}
		else if($_SESSION['tarifa']['vistaseleg']==2)
		{
			$_SESSION['tarifa']['vistaseleg']=1;
		}
		$this->ConsultarTarifa();
		return true;
	}

	//
	function ConsultarTarifa()//
	{
		if($_REQUEST['tarifacons']==-1 AND $_SESSION['tarifa']['tarifaeleg']==NULL)
		{
			$this->frmError["MensajeError"]="SELECCIONE UN TARIFARIO";
			$this->uno=1;
			$this->PrincipalTarifa();
			return true;
		}
		if($_SESSION['tarifa']['tarifaeleg']==NULL)
		{
			$var=explode(',',$_REQUEST['tarifacons']);
			$_SESSION['tarifa']['tarifaeleg']=$var[0];
			$_SESSION['tarifa']['descrieleg']=$var[1];
			if($_SESSION['tarifa']['vistaseleg']==NULL)
			{
				$_SESSION['tarifa']['vistaseleg']=1;
			}
		}
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - EQUIVALENCIAS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"left\" valign=\"top\"><label class=\"field\">";
		$this->salida .= "  ".$_SESSION['tarifa']['descrieleg']." - EL TARIFARIO BASE PARA EQUIVALENCIAS ES EL CUPS</label>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"50%\" align=\"right\" class=\"label\">INVERTIR POSICIÓN DE LOS TARIFARIOS: </td>";
		$this->salida .= "      <td width=\"50%\" align=\"left\">";
		$this->salida .= "      <a href=\"".ModuloGetURL('system','Tarifario','user','CambiarConsultaTarifa',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigotari'=>$_REQUEST['codigotari'],
		'descritari'=>$_REQUEST['descritari']))."\"><img src=\"".GetThemePath()."/images/uf.png\" border=\"0\"></a></td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		if($_SESSION['tarifa']['vistaseleg']==1)
		{
			$this->salida .= "      <td colspan=\"2\">".$_SESSION['tarifa']['descrieleg']."</td>";
			$this->salida .= "      <td colspan=\"2\">TARIFARIO BASE:".' '."CUPS</td>";
			$cargos1=$this->BuscarCargosElegTarifa1($_SESSION['tarifa']['tarifaeleg']);
		}
		else if($_SESSION['tarifa']['vistaseleg']==2)
		{
			$this->salida .= "      <td colspan=\"2\">TARIFARIO BASE:".' '."CUPS</td>";
			$this->salida .= "      <td colspan=\"2\">".$_SESSION['tarifa']['descrieleg']."</td>";
			$cargos1=$this->BuscarCargosElegTarifa2($_SESSION['tarifa']['tarifaeleg']);
		}
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CARGOS</td>";
		$this->salida .= "      <td width=\"42%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"8%\" >CARGOS</td>";
		$this->salida .= "      <td width=\"42%\">DESCRIPCIÓN</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($cargos1);
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
			$this->salida .= "  <td width=\"8%\">";
			$this->salida .= "".$cargos1[$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"42%\">";
			$this->salida .= "".$cargos1[$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td colspan=\"2\">";
			$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($cargos1[$i]['cargo']==$cargos1[$k]['cargo'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td width=\"16%\">";
				$this->salida .= "".$cargos1[$k]['algo']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td width=\"84%\">";
				$this->salida .= "".$cargos1[$k]['algoo']."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "  </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$i=$k;
		}
		if(empty($cargos1))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"4\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARÓN CARGOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\">";
		$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('system','Tarifario','user','PrincipalTarifa');
		$this->salida .= "      <form name=\"tarifarios\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"TARIFARIOS - OPCIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\">";
		$var1=$this->RetornarBarraTari1();
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
		$accion=ModuloGetURL('system','Tarifario','user','ConsultarTarifa',
		array('codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari']));
		$this->salida .= "      <form name=\"tari1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"left\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigotari\" value=\"".$_REQUEST['codigotari']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descritari\" value=\"".$_REQUEST['descritari']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('system','Tarifario','user','ConsultarTarifa');
		$this->salida .= "      <form name=\"tari2\" action=\"$accion\" method=\"post\">";
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
	function EquivalenciaTarifa()//
	{
		if($_REQUEST['tarifaequi']==-1 AND $_SESSION['tarifa']['tarifaeleg']==NULL)
		{
			$this->frmError["MensajeError"]="SELECCIONE UN TARIFARIO";
			$this->uno=1;
			$this->PrincipalTarifa();
			return true;
		}
		if($_SESSION['tarifa']['tarifaeleg']==NULL)
		{
			$var=explode(',',$_REQUEST['tarifaequi']);
			$_SESSION['tarifa']['tarifaeleg']=$var[0];
			$_SESSION['tarifa']['descrieleg']=$var[1];
		}
		UNSET($_SESSION['tarifa']['cargosequi']);
		UNSET($_SESSION['tarifa']['indicarequ']);
		UNSET($_SESSION['tarifa']['cargosbase']);
		UNSET($_SESSION['tarifa']['cargosbaeq']);
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - EQUIVALENCIAS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"left\" valign=\"top\">";
		$this->salida .= "  <label class=\"field\">".$_SESSION['tarifa']['descrieleg']."
		 - EL TARIFARIO BASE PARA LAS EQUIVALENCIAS ES EL CUPS</label>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"2%\" ></td>";
		$this->salida .= "      <td colspan=\"2\">".$_SESSION['tarifa']['descrieleg']."</td>";
		$this->salida .= "      <td width=\"50%\">TARIFARIO BASE:".' '."CUPS</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"2%\" ></td>";
		$this->salida .= "      <td width=\"8%\" >CARGOS</td>";
		$this->salida .= "      <td width=\"40%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"50%\">DETALLES</td>";
		$this->salida .= "      </tr>";
		$_SESSION['tarifa']['cargosequi']=$this->BuscarCargosEquiTarifa($_SESSION['tarifa']['tarifaeleg']);
		$j=0;
		$ciclo=sizeof($_SESSION['tarifa']['cargosequi']);
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
			$this->salida .= "<a href=\"".ModuloGetURL('system','Tarifario','user','CrearEquivalenciaTarifa',
			array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigotari'=>$_REQUEST['codigotari'],
			'descritari'=>$_REQUEST['descritari'],'buscartari'=>$_REQUEST['buscartari'],'indicecaeq'=>$i))."\">
			<img src=\"".GetThemePath()."/images/equivalencia.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['tarifa']['cargosequi'][$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['tarifa']['cargosequi'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['tarifa']['cargosequi'][$i]['num']==0)
			{
				$this->salida .= "NO HAY RELACIONES PARA ESTE CARGO";
			}
			else
			{
				$muestra=$this->BuscarMostrarEquiTarifa($_SESSION['tarifa']['tarifaeleg'],
				$_SESSION['tarifa']['cargosequi'][$i]['cargo']);
				$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
				$ciclo1=sizeof($muestra);
				for($l=0;$l<$ciclo1;$l++)
				{
					$this->salida .= "  <tr>";
					$this->salida .= "  <td width=\"18%\">";
					$this->salida .= "".$muestra[$l]['cargo_base']."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td width=\"82%\">";
					$this->salida .= "".$muestra[$l]['descripcion']."";
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
				}
				$this->salida .= "  </table>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['tarifa']['cargosequi']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"4\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARÓN CARGOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "  </table><br>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\">";
		$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('system','Tarifario','user','PrincipalTarifa');
		$this->salida .= "      <form name=\"tarifarios\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"TARIFARIOS - OPCIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\">";
		$var1=$this->RetornarBarraTari2();
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
		$accion=ModuloGetURL('system','Tarifario','user','EquivalenciaTarifa',array('codigotari'=>$_REQUEST['codigotari'],
		'descritari'=>$_REQUEST['descritari'],'buscartari'=>$_REQUEST['buscartari']));
		$this->salida .= "      <form name=\"tari1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td align=\"center\">";
		if($_REQUEST['buscartari']<>NULL)
		{
			$this->salida .= "<input type=\"checkbox\" name=\"buscartari\" value=1 checked>";
		}
		else
		{
			$this->salida .= "<input type=\"checkbox\" name=\"buscartari\" value=1>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\">BUSCAR SÓLO LOS CÓDIGOS NO RELACIONADOS";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigotari\" value=\"".$_REQUEST['codigotari']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descritari\" value=\"".$_REQUEST['descritari']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('system','Tarifario','user','EquivalenciaTarifa');
		$this->salida .= "      <form name=\"tari2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table  border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		if($this->PermitirAyudaTarifa($_SESSION['tarifa']['tarifaeleg'])==0)
		{
			$accion=ModuloGetURL('system','Tarifario','user','AyudaCopiar1Tarifa');
			$this->salida .= "      <form name=\"tari3\" action=\"$accion\" method=\"post\">";
			$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=\"modulo_table_list_title\">";
			$this->salida .= "      <td width=\"100%\">AYUDA DEL TARIFARIO #1";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_table_list_title\">";
			$this->salida .= "      <td width=\"100%\">COPIAR AL TARIFARIO";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td width=\"100%\" align=\"center\">";
			$tarifarios=$this->BuscarAyudaTarifariosTari($_SESSION['tarifa']['tarifaeleg']);
			$this->salida .= "      <select name=\"tarifacopi\" class=\"select\">";
			$this->salida .= "      <option value=\"-1\">-- SELECCIONE --</option>";
			$ciclo=sizeof($tarifarios);
			for($j=0;$j<$ciclo;$j++)
			{
				if($tarifarios[$j]['tarifario_id']==$_POST['tarifacopi'])
				{
					$this->salida .="<option value=\"".$tarifarios[$j]['tarifario_id']."\" selected>".$tarifarios[$j]['descripcion']."</option>";
				}
				else
				{
					$this->salida .="<option value=\"".$tarifarios[$j]['tarifario_id']."\">".$tarifarios[$j]['descripcion']."</option>";
				}
			}
			$this->salida .= "      </select>";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td width=\"100%\" align=\"center\">";
			$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"buscarcopi\" value=\"GUARDAR COPIA\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </form>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table>";
		}
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function CrearEquivalenciaTarifa()//
	{
		if($_SESSION['tarifa']['indicarequ']==NULL)
		{
			$_SESSION['tarifa']['indicarequ']=$_REQUEST['indicecaeq'];
		}
		UNSET($_SESSION['tarifa']['cargosbase']);
		UNSET($_SESSION['tarifa']['cargosbaeq']);
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - EQUIVALENCIAS');
		$accion=ModuloGetURL('system','Tarifario','user','ValidarEquivalenciaTarifa',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigotari'=>$_REQUEST['codigotari'],
		'descritari'=>$_REQUEST['descritari'],'buscartari'=>$_REQUEST['buscartari'],
		'Of1'=>$_REQUEST['Of1'],'paso1'=>$_REQUEST['paso1'],
		'codigocrea'=>$_REQUEST['codigocrea'],'descricrea'=>$_REQUEST['descricrea']));
		$this->salida .= "  <form name=\"tarifarios1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"left\" valign=\"top\"><label class=\"field\">
		".$_SESSION['tarifa']['descrieleg']." - EL TARIFARIO BASE PARA EQUIVALENCIAS ES EL CUPS</label>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">CARGO DEL ".$_SESSION['tarifa']['descrieleg'].":";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['tarifa']['cargosequi'][$_SESSION['tarifa']['indicarequ']]['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['tarifa']['cargosequi'][$_SESSION['tarifa']['indicarequ']]['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$muestra=$this->BuscarMostrarEquiTarifa($_SESSION['tarifa']['tarifaeleg'],
		$_SESSION['tarifa']['cargosequi'][$_SESSION['tarifa']['indicarequ']]['cargo']);
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"2\">CARGOS RELACIONADOS DEL TARIFARIO BASE:".' '."CUPS</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CARGOS</td>";
		$this->salida .= "      <td width=\"92%\">DESCRIPCIÓN</td>";
		$this->salida .= "      </tr>";
		$ciclo=sizeof($muestra);
		for($l=0;$l<$ciclo;$l++)
		{
			$this->salida .= "  <tr class=\"modulo_list_claro\">";
			$this->salida .= "  <td width=\"8%\" >";
			$this->salida .= "".$muestra[$l]['cargo_base']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"92%\">";
			$this->salida .= "".$muestra[$l]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($muestra))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"2\" align=\"center\">";
			$this->salida .= "'NO HAY RELACIONES PARA ESTE CARGO'";
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
		$this->salida .= "      <td colspan=\"3\">CARGOS PARA RELACIONAR DEL TARIFARIO BASE:".' '."CUPS</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CARGOS</td>";
		$this->salida .= "      <td width=\"86%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"6%\" >EQUIVALE</td>";
		$this->salida .= "      </tr>";
		$_SESSION['tarifa']['cargosbase']=$this->BuscarCargosBaseTarifa();
		$_SESSION['tarifa']['cargosbaeq']=$this->BuscarEquivalenciaTarifa($_SESSION['tarifa']['tarifaeleg'],
		$_SESSION['tarifa']['cargosequi'][$_SESSION['tarifa']['indicarequ']]['cargo']);
		$j=0;
		$ciclo=sizeof($_SESSION['tarifa']['cargosbase']);
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
			$this->salida .= "".$_SESSION['tarifa']['cargosbase'][$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"86%\">";
			$this->salida .= "".$_SESSION['tarifa']['cargosbase'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"6%\" align=\"center\">";
			if($_POST['equivatari'.$i]==1 OR $_SESSION['tarifa']['cargosbaeq'][$_SESSION['tarifa']['cargosbase'][$i]['cargo']]==1)
			{
				$this->salida .= "<input type=\"checkbox\" name=\"equivatari".$i."\" value=1 checked>";
			}
			else
			{
				$this->salida .= "<input type=\"checkbox\" name=\"equivatari".$i."\" value=1>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['tarifa']['cargosbase']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"3\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARÓN CARGOS'";
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
		$accion=ModuloGetURL('system','Tarifario','user','EquivalenciaTarifa',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigotari'=>$_REQUEST['codigotari'],
		'descritari'=>$_REQUEST['descritari'],'buscartari'=>$_REQUEST['buscartari']));
		$this->salida .= "      <form name=\"tarifarios2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LOS CARGOS\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraTari3();
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
		$accion=ModuloGetURL('system','Tarifario','user','CrearEquivalenciaTarifa',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigotari'=>$_REQUEST['codigotari'],
		'descritari'=>$_REQUEST['descritari'],'buscartari'=>$_REQUEST['buscartari'],
		'codigocrea'=>$_REQUEST['codigocrea'],'descricrea'=>$_REQUEST['descricrea']));
		$this->salida .= "      <form name=\"tarifarios3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocrea\" value=\"".$_REQUEST['codigocrea']."\" maxlength=\"10\" size=\"10\">";
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
		$accion=ModuloGetURL('system','Tarifario','user','CrearEquivalenciaTarifa',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigotari'=>$_REQUEST['codigotari'],
		'descritari'=>$_REQUEST['descritari'],'buscartari'=>$_REQUEST['buscartari']));
		$this->salida .= "      <form name=\"tarifarios4\" action=\"$accion\" method=\"post\">";
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

	function RetornarBarraTari1()//Barra paginadora de las consultas
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
		$accion=ModuloGetURL('system','Tarifario','user','ConsultarTarifa',array('conteo'=>$this->conteo,
		'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari']));
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

	function RetornarBarraTari2()//Barra paginadora de las equivalencias
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
		$accion=ModuloGetURL('system','Tarifario','user','EquivalenciaTarifa',array('conteo'=>$this->conteo,
		'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari'],'buscartari'=>$_REQUEST['buscartari']));
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
			if($diferencia<=0)/*CAMBIAR ESTO: ERROR GRAVE DEL PROGRAMADOR*/
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

	function RetornarBarraTari3()//Barra paginadora de la creación de equivalencias
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
		$accion=ModuloGetURL('system','Tarifario','user','CrearEquivalenciaTarifa',
		array('conteo'=>$_REQUEST['conteo'],'paso'=>$_REQUEST['paso'],'codigotari'=>$_REQUEST['codigotari'],
		'descritari'=>$_REQUEST['descritari'],'buscartari'=>$_REQUEST['buscartari'],
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

	//
	function MenuMantenTarifa()//
	{
		if($_REQUEST['tarifaequi']==-1 AND $_SESSION['tarifa']['tarifaeleg']==NULL)
		{
			$this->frmError["MensajeError"]="SELECCIONE UN TARIFARIO";
			$this->uno=1;
			$this->PrincipalTarifa();
			return true;
		}
		if($_SESSION['tarifa']['tarifaeleg']==NULL)
		{
			$var=explode(',',$_REQUEST['tarifaequi']);
			$_SESSION['tarifa']['tarifaeleg']=$var[0];
			$_SESSION['tarifa']['descrieleg']=$var[1];
		}
		UNSET($_SESSION['tarifa']['cargosmant']);
		UNSET($_SESSION['tarifa']['datocargma']);
		UNSET($_SESSION['taricu']);
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - MANTENIMIENTO');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CARGOS DEL TARIFARIO: '".$_SESSION['tarifa']['descrieleg']."'</legend>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('system','Tarifario','user','TariPedirNuevoCargoTarifa') ."\">NUEVO CARGO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('system','Tarifario','user','TariModificarCargoTarifa') ."\">MODIFICAR CARGOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		/*$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('system','Tarifario','user','TariConsultarCargoTarifa') ."\">CONSULTAR CARGOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";*/
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('system','Tarifario','user','PrincipalTarifa');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"TARIFARIOS - OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function TariModificarCargoTarifa()//
	{
		UNSET($_SESSION['tarifa']['cargosmant']);
		UNSET($_SESSION['tarifa']['datocargma']);
		if($_SESSION['taricu']['grupo']<>NULL OR $_SESSION['taricu']['NomGrupo']<>NULL
		OR $_SESSION['taricu']['clasePr']<>NULL OR $_SESSION['taricu']['NomClase']<>NULL)
		{
			$_REQUEST['grupo']=$_SESSION['taricu']['grupo'];
			$_REQUEST['NomGrupo']=$_SESSION['taricu']['NomGrupo'];
			$_REQUEST['clasePr']=$_SESSION['taricu']['clasePr'];
			$_REQUEST['NomClase']=$_SESSION['taricu']['NomClase'];
			UNSET($_SESSION['taricu']);
		}
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - MODIFICAR CARGOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table>";
		}
		$this->salida .= "  <fieldset><legend class=\"field\">CARGOS DEL TARIFARIO: '".$_SESSION['tarifa']['descrieleg']."'</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CARGO</td>";
		$this->salida .= "      <td width=\"57%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"5%\">UNIDAD</td>";
		$this->salida .= "      <td width=\"5%\">TIPO UNIDAD</td>";
		$this->salida .= "      <td width=\"25%\">DESCRIPCIÓN UNIDAD</td>";
/*		$this->salida .= "      <td width=\"8%\" >MODIFICAR</td>";*/
		$_SESSION['tarifa']['cargosmant']=$this->BuscarTariModificarCargoTarifa($_SESSION['tarifa']['tarifaeleg']);
		$ciclo=sizeof($_SESSION['tarifa']['cargosmant']);
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
			$this->salida .= "<a href=\"". ModuloGetURL('system','Tarifario','user','TariModificar1CargosTarifa',
			array('indicargma'=>$i,'Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
			'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari'],
			'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
			'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'])) ."\">".$_SESSION['tarifa']['cargosmant'][$i]['cargo']."</a>";
			//$this->salida .= "".$_SESSION['tarifa']['cargosmant'][$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['tarifa']['cargosmant'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['tarifa']['cargosmant'][$i]['precio']."";
			$this->salida .= "  </td>";
			$tipounidad=$_SESSION['tarifa']['cargosmant'][$i]['tipo_unidad_id'];
			$desunidad=$this->BuscarDesUnidad($tipounidad);
			$this->salida .= "  <td>";
			$this->salida .= "".$desunidad[0]['descripcion_corta']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$desunidad[0]['descripcion']."";
			$this->salida .= "  </td>";
/*			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "  <a href=\"". ModuloGetURL('system','Tarifario','user','TariModificar1CargosTarifa',
			array('indicargma'=>$i,'Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
			'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari'],
			'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
			'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'])) ."\">CARGO</a>";
			$this->salida .= "  </td>";*/
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['tarifa']['cargosmant']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"3\">";
			$this->salida .= "'NO SE ENCONTRARÓN CARGOS PARA ESTE TARIFARIO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "      <br><table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"center\">";
		$accion=ModuloGetURL('system','Tarifario','user','MenuMantenTarifa');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER AL MANTENIMIENTO\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraTarifaMoCa();
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
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm){\n";
		$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=2;";
		$this->salida .= "var url2 = url+'?bandera='+ban;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('system','Tarifario','user','TariModificarCargoTarifa',array(
		'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"45%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigotari\" value=\"".$_REQUEST['codigotari']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descritari\" value=\"".$_REQUEST['descritari']."\" maxlength=\"40\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">GRUPO CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomGrupo\" value=\"".$_REQUEST['NomGrupo']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"grupo\" value=\"".$_REQUEST['grupo']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">TIPO CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomClase\" value=\"".$_REQUEST['NomClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"clasePr\" value=\"".$_REQUEST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$ruta='system_modules/Tarifario/ClasificacionGrupos.php';
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\">";
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
		$accion=ModuloGetURL('system','Tarifario','user','TariModificarCargoTarifa');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraTarifaMoCa()//Barra paginadora de la creación de equivalencias
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
		$accion=ModuloGetURL('system','Tarifario','user','TariModificarCargoTarifa',array('conteo'=>$this->conteo,
		'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr']));
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
	function TariModificar1CargosTarifa()//
	{
		if($_SESSION['tarifa']['datocargma']['cargo']==NULL)
		{
			$_SESSION['tarifa']['datocargma']=$this->BuscarTariModificar1CargosTarifa(
			$_SESSION['tarifa']['tarifaeleg'],$_SESSION['tarifa']['cargosmant'][$_REQUEST['indicargma']]['cargo']);
			UNSET($_SESSION['tarifa']['cargosmant']);
		}
		if(($_REQUEST['grupo']<>NULL OR $_REQUEST['NomGrupo']<>NULL
		OR $_REQUEST['clasePr']<>NULL OR $_REQUEST['NomClase']<>NULL)
		AND $this->uno <> 1)
		{
			$_SESSION['taricu']['grupo']=$_REQUEST['grupo'];
			$_SESSION['taricu']['NomGrupo']=$_REQUEST['NomGrupo'];
			$_SESSION['taricu']['clasePr']=$_REQUEST['clasePr'];
			$_SESSION['taricu']['NomClase']=$_REQUEST['NomClase'];
		}
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - MODIFICAR UN CARGO');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass1(nombre, url, ancho, altura, x, frm, gruta, subta){\n";
		$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=2;";
		$this->salida .= "var url2 = url+'?bandera='+ban+'&grupo='+gruta+'&clasePr='+subta;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, gruta, subta){\n";
		$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=2;";
		$this->salida .= "var url2 = url+'?bandera='+ban+'&grupotarif='+gruta+'&subgrtarif='+subta;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('system','Tarifario','user','ValidarTariModificar1CargosTarifa',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		if($this->uno == 1)
		{
			$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "  </table>";
		}
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL CARGO</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"label\">CARGO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$this->salida .= "".$_SESSION['tarifa']['datocargma']['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"label\">TARIFARIO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$this->salida .= "".$_SESSION['tarifa']['descrieleg']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("descripcit")."\">DESCRIPCIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descripcit\" value=\"".$_SESSION['tarifa']['datocargma']['descripcion']."\" maxlength=\"600\" size=\"100\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\" class=\"".$this->SetStyle("grupo")."\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$_POST['NomGrupo']=$_SESSION['tarifa']['datocargma']['des1'];
		$_POST['grupo']=$_SESSION['tarifa']['datocargma']['grupo_tipo_cargo'];
		$this->salida .= "      <input type=\"text\" name=\"NomGrupo\" value=\"".$_POST['NomGrupo']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"grupo\" value=\"".$_POST['grupo']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\" class=\"".$this->SetStyle("clasePr")."\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$_POST['NomClase']=$_SESSION['tarifa']['datocargma']['des2'];
		$_POST['clasePr']=$_SESSION['tarifa']['datocargma']['tipo_cargo'];
		$this->salida .= "      <input type=\"text\" name=\"NomClase\" value=\"".$_POST['NomClase']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"clasePr\" value=\"".$_POST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$rut='system_modules/Tarifario/ClasificacionGrupos.php';
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass1('PARAMETROS','$rut',450,200,0,this.form,'".$_SESSION['tarifa']['datocargma']['grupo_tipo_cargo']."','".$_SESSION['tarifa']['datocargma']['tipo_cargo']."')\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td class=\"label\">GRUPO TARIFARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$_POST['nomgrupota']=$_SESSION['tarifa']['datocargma']['des3'];
		$_POST['grupotarif']=$_SESSION['tarifa']['datocargma']['grupo_tarifario_id'];
		$this->salida .= "      <input type=\"text\" name=\"nomgrupota\" value=\"".$_POST['nomgrupota']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"grupotarif\" value=\"".$_POST['grupotarif']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">SUBGRUPO TARIFARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$_POST['nomsubgrta']=$_SESSION['tarifa']['datocargma']['des4'];
		$_POST['subgrtarif']=$_SESSION['tarifa']['datocargma']['subgrupo_tarifario_id'];
		$this->salida .= "      <input type=\"text\" name=\"nomsubgrta\" value=\"".$_POST['nomsubgrta']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"subgrtarif\" value=\"".$_POST['subgrtarif']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$ruta='system_modules/Tarifario/ClasificacionTarifarios.php';
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'".$_SESSION['tarifa']['datocargma']['grupo_tarifario_id']."','".$_SESSION['tarifa']['datocargma']['subgrupo_tarifario_id']."')\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarConceptosRipsTarifa();
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("conceptort")."\">CONCEPTO RIPS:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"conceptort\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_SESSION['tarifa']['datocargma']['concepto_rips']==$grupotarif[$i]['concepto_rips'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['concepto_rips']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['concepto_rips']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarNivelesTarifa();
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("nivelatent")."\">NIVEL DE ATENCIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"nivelatent\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_SESSION['tarifa']['datocargma']['nivel']==$grupotarif[$i]['nivel'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['nivel']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['nivel']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("tipounidad")."\">UNIDAD:</label>";
		$this->salida .= "      </td>";
		$tiposunidades=$this->BuscarTiposUnidades();
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"unidad\" value=\"".$_SESSION['tarifa']['datocargma']['precio']."\" maxlength=\"13\" size=\"20\">";
		$this->salida .= "      <select name=\"tipounidad\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($tiposunidades);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_SESSION['tarifa']['datocargma']['tipo_unidad_id']==$tiposunidades[$i]['tipo_unidad_id'])
			{
				$this->salida .="<option value=\"".$tiposunidades[$i]['tipo_unidad_id']."\" selected>".$tiposunidades[$i]['descripcion_corta']."-".$tiposunidades[$i]['descripcion_corta']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$tiposunidades[$i]['tipo_unidad_id']."\">".$tiposunidades[$i]['descripcion_corta']." -- ".$tiposunidades[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";

/*		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"preciocart\" value=\"".$_SESSION['tarifa']['datocargma']['precio']."\" maxlength=\"13\" size=\"20\">";
		$this->salida .= "      </td>";*/
		$this->salida .= "      </tr>";
/*		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("swuvrspret")."\">PRECIO EN UVR'S:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['tarifa']['datocargma']['sw_uvrs']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swuvrspret\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swuvrspret\" value=1>";
		}
		if($_SESSION['tarifa']['datocargma']['sw_uvrs']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swuvrspret\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swuvrspret\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";*/
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("gravamenct")."\">GRAVAMEN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"gravamenct\" value=\"".$_SESSION['tarifa']['datocargma']['gravamen']."\" maxlength=\"3\" size=\"3\">";
		$this->salida .= "%";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("honorariot")."\">HONORARIOS:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['tarifa']['datocargma']['sw_honorarios']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"honorariot\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"honorariot\" value=1>";
		}
		if($_SESSION['tarifa']['datocargma']['sw_honorarios']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"honorariot\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"honorariot\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("swcantidat")."\">EXIGE CANTIDAD:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['tarifa']['datocargma']['sw_cantidad']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swcantidat\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swcantidat\" value=1>";
		}
		if($_SESSION['tarifa']['datocargma']['sw_cantidad']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swcantidat\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swcantidat\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarGruposMapiposTarifa();
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("gruposmapt")."\">GRUPOS MAPIPOS:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"gruposmapt\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_SESSION['tarifa']['datocargma']['grupos_mapipos']==$grupotarif[$i]['grupos_mapipos'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['grupos_mapipos']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['grupos_mapipos']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('system','Tarifario','user','TariModificarCargoTarifa',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari']));
		$this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function TariPedirNuevoCargoTarifa()//
	{
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - GUARDAR UN NUEVO CARGO');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass1(nombre, url, ancho, altura, x, frm, gruta, subta){\n";
		$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=2;";
		$this->salida .= "var url2 = url+'?bandera='+ban+'&grupo='+gruta+'&clasePr='+subta;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, gruta, subta){\n";
		$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=2;";
		$this->salida .= "var url2 = url+'?bandera='+ban+'&grupotarif='+gruta+'&subgrtarif='+subta;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('system','Tarifario','user','ValidarTariPedirNuevoCargoTarifa');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		if($this->uno == 1)
		{
			$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "  </table>";
		}
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL CARGO</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("codigocart")."\">CARGO (10):</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocart\" value=\"".$_POST['codigocart']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"label\">TARIFARIO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$this->salida .= "".$_SESSION['tarifa']['descrieleg']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("descripcit")."\">DESCRIPCIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descripcit\" value=\"".$_POST['descripcit']."\" maxlength=\"600\" size=\"100\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\" class=\"".$this->SetStyle("grupo")."\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$this->salida .= "      <input type=\"text\" name=\"NomGrupo\" value=\"".$_POST['NomGrupo']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"grupo\" value=\"".$_POST['grupo']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\" class=\"".$this->SetStyle("clasePr")."\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$this->salida .= "      <input type=\"text\" name=\"NomClase\" value=\"".$_POST['NomClase']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"clasePr\" value=\"".$_POST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$rut='system_modules/Tarifario/ClasificacionGrupos.php';
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass1('PARAMETROS','$rut',450,200,0,this.form,'".$_POST['grupo']."','".$_POST['clasePr']."')\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("grupotarif")."\">GRUPO TARIFARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" name=\"nomgrupota\" value=\"".$_POST['nomgrupota']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"grupotarif\" value=\"".$_POST['grupotarif']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("subgrtarif")."\">SUBGRUPO TARIFARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" name=\"nomsubgrta\" value=\"".$_POST['nomsubgrta']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"subgrtarif\" value=\"".$_POST['subgrtarif']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$ruta='system_modules/Tarifario/ClasificacionTarifarios.php';
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'".$_POST['grupotarif']."','".$_POST['subgrtarif']."')\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarConceptosRipsTarifa();
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("conceptort")."\">CONCEPTO RIPS:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"conceptort\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_POST['conceptort']==$grupotarif[$i]['concepto_rips'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['concepto_rips']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['concepto_rips']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarNivelesTarifa();
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("nivelatent")."\">NIVEL DE ATENCIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"nivelatent\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_POST['nivelatent']==$grupotarif[$i]['nivel'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['nivel']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['nivel']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("tipounidad")."\">UNIDAD:</label>";
		$this->salida .= "      </td>";
		$tiposunidades=$this->BuscarTiposUnidades();
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"unidad\" value=\"".$_POST['unidad']."\" maxlength=\"13\" size=\"20\">";
		$this->salida .= "      <select name=\"tipounidad\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($tiposunidades);
		for($i=0;$i<$ciclo;$i++)
		{
/*			if($_POST['nivelatent']==$tiposunidades[$i]['tipo_unidad_id'])
			{
				$this->salida .="<option value=\"".$tiposunidades[$i]['tipo_unidad_id']."\" selected>".$tiposunidades[$i]['descripcion_corta']."-".$tiposunidades[$i]['descripcion_corta']."</option>";
			}
			else
			{*/
				$this->salida .="<option value=\"".$tiposunidades[$i]['tipo_unidad_id']."\">".$tiposunidades[$i]['descripcion_corta']." -- ".$tiposunidades[$i]['descripcion']."</option>";
			//}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
/*		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"preciocart\" value=\"".$_POST['preciocart']."\" maxlength=\"13\" size=\"20\">";
		$this->salida .= "      </td>";*/
		$this->salida .= "      </tr>";
/*		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("swuvrspret")."\">PRECIO EN UVR'S:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_POST['swuvrspret']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swuvrspret\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swuvrspret\" value=1>";
		}
		if($_POST['swuvrspret']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swuvrspret\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swuvrspret\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";*/
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("gravamenct")."\">GRAVAMEN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"gravamenct\" value=\"".$_POST['gravamenct']."\" maxlength=\"3\" size=\"3\">";
		$this->salida .= "%";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("honorariot")."\">HONORARIOS:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_POST['honorariot']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"honorariot\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"honorariot\" value=1>";
		}
		if($_POST['honorariot']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"honorariot\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"honorariot\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("swcantidat")."\">EXIGE CANTIDAD:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_POST['swcantidat']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swcantidat\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swcantidat\" value=1>";
		}
		if($_POST['swcantidat']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swcantidat\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swcantidat\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarGruposMapiposTarifa();
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("gruposmapt")."\">GRUPOS MAPIPOS:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"gruposmapt\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_POST['gruposmapt']==$grupotarif[$i]['grupos_mapipos'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['grupos_mapipos']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['grupos_mapipos']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('system','Tarifario','user','MenuMantenTarifa');
		$this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que selecciona por los grupos y subgrupos, los cargos con tramites por defecto
	function GruposCargosTarifa()//Válida por grupo y subgrupo, los cargos para definir el periodo de tramite
	{
		UNSET($_SESSION['tarifa']['grupcargpr']);//grupos
		UNSET($_SESSION['tarifa']['grucareleg']);//indice
		UNSET($_SESSION['tarifa']['cargcupspr']);//cargos
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - PERIODOS DE TRAMITE POR CARGOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">GRUPOS TIPOS CARGOS</legend>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"36%\">GRUPOS TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"45%\">TIPOS CARGOS</td>";
		$this->salida .= "      <td width=\"10%\">No. CARGOS<br>TIPO CARGO</td>";
		$this->salida .= "      <td width=\"7%\">DETALLES</td>";
		$this->salida .= "      </tr>";
		$_SESSION['tarifa']['grupcargpr']=$this->BuscarGruposCargosTarifa();
		$ciclo=sizeof($_SESSION['tarifa']['grupcargpr']);
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
			$this->salida .= "".$_SESSION['tarifa']['grupcargpr'][$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td colspan=\"3\">";
			$this->salida .= "      <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['tarifa']['grupcargpr'][$i]['grupo_tipo_cargo']==$_SESSION['tarifa']['grupcargpr'][$k]['grupo_tipo_cargo'])
			{
				$this->salida .= "  <tr $color>";
				$this->salida .= "  <td width=\"73%\">";
				$this->salida .= "  ".$_SESSION['tarifa']['grupcargpr'][$k]['des2']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td width=\"16%\" align=\"center\">";
				$this->salida .= "  ".$_SESSION['tarifa']['grupcargpr'][$k]['cantidad']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td width=\"11%\" align=\"center\">";
				if($_SESSION['tarifa']['grupcargpr'][$k]['cantidad']<>0)
				{
					$this->salida .= "<a href=\"". ModuloGetURL('system','Tarifario','user','CargosTarifa',
					array('indicegrca'=>$k)) ."\"><img src=\"".GetThemePath()."/images/cargos.png\" border=\"0\"></a>";
				}
				else
				{
					$this->salida .= "<img src=\"".GetThemePath()."/images/cargosin.png\" border=\"0\">";
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
		if(empty($_SESSION['tarifa']['grupcargpr']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"4\">";
			$this->salida .= "'NO SE ENCONTRARÓN GRUPOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('system','Tarifario','user','PrincipalTarifa');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"TARIFARIOS - OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite mostrar los cargos prestados por el proveedor de servicios de salud
	function CargosTarifa()//Válida los cargos del cups y los que están contratados
	{
		if($_SESSION['tarifa']['grucareleg']==NULL)
		{
			$_SESSION['tarifa']['grucareleg']=$_REQUEST['indicegrca'];
		}
		UNSET($_SESSION['tarifa']['cargcupspr']);
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - PERIODOS DE TRAMITE POR CARGOS');
		$accion=ModuloGetURL('system','Tarifario','user','ValidarCargosTarifa',array('Of'=>$_REQUEST['Of'],
		'paso'=>$_REQUEST['paso'],'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CARGOS DEL CUPS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['tarifa']['grupcargpr'][$_SESSION['tarifa']['grucareleg']]['des1']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['tarifa']['grupcargpr'][$_SESSION['tarifa']['grucareleg']]['des2']."";
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
		$this->salida .= "      <td width=\"62%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"9%\" >DIAS DE<br>VIGENCIA</td>";
		$this->salida .= "      <td width=\"9%\" >DIAS PARA<br>REFRENDAR</td>";
		$this->salida .= "      <td width=\"9%\" >DIAS PARA<br>ORDEN DE<br>SERVICIO</td>";
		$this->salida .= "      <td width=\"3%\" class=\"modulo_list_claro\"><img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\"></td>";
		$this->salida .= "      </tr>";
		$_SESSION['tarifa']['cargcupspr']=$this->BuscarCargosTarifa(
		$_SESSION['tarifa']['grupcargpr'][$_SESSION['tarifa']['grucareleg']]['grupo_tipo_cargo'],
		$_SESSION['tarifa']['grupcargpr'][$_SESSION['tarifa']['grucareleg']]['tipo_cargo']);
		$ciclo=sizeof($_SESSION['tarifa']['cargcupspr']);
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
			$this->salida .= "".$_SESSION['tarifa']['cargcupspr'][$i]['cargocups']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['tarifa']['cargcupspr'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			if($_SESSION['tarifa']['cargcupspr'][$i]['cargotramite']<>NULL)
			{
				$_POST['vigencia'.$i]=$_SESSION['tarifa']['cargcupspr'][$i]['dias_vigencia'];
				$_POST['refrenda'.$i]=$_SESSION['tarifa']['cargcupspr'][$i]['dias_refrendar'];
				$_POST['ordenser'.$i]=$_SESSION['tarifa']['cargcupspr'][$i]['dias_tramite_os'];
			}
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"vigencia".$i."\" value=\"".$_POST['vigencia'.$i]."\" maxlength=\"5\" size=\"10\">";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"refrenda".$i."\" value=\"".$_POST['refrenda'.$i]."\" maxlength=\"5\" size=\"10\">";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"ordenser".$i."\" value=\"".$_POST['ordenser'.$i]."\" maxlength=\"5\" size=\"10\">";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "  <input type=\"checkbox\" name=\"eliminar".$i."\" value=1>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['tarifa']['cargcupspr']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"6\">";
			$this->salida .= "'NO SE ENCONTRARÓN CARGOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"50%\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR CARGOS\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('system','Tarifario','user','GruposCargosTarifa');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A GRUPOS\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraCargos();
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
		$accion=ModuloGetURL('system','Tarifario','user','CargosTarifa',
		array('codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigotari\" value=\"".$_REQUEST['codigotari']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descritari\" value=\"".$_REQUEST['descritari']."\" maxlength=\"40\" size=\"40\">";
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
		$accion=ModuloGetURL('system','Tarifario','user','CargosTarifa');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraCargos()//Barra paginadora de la creación de equivalencias
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
		$accion=ModuloGetURL('system','Tarifario','user','CargosTarifa',array('conteo'=>$this->conteo,
		'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari']));
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
	function MantenimientoTarifa()//
	{
		UNSET($_SESSION['tarif1']);
		UNSET($_SESSION['taric1']);
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - MANTENIMIENTO');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CARGOS DEL TARIFARIO CUPS</legend>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('system','Tarifario','user','PedirIngresarCargoTarifa') ."\">NUEVO CARGO</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('system','Tarifario','user','ModificarCargoTarifa') ."\">MODIFICAR CARGOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('system','Tarifario','user','ConsultarCargoTarifa') ."\">CONSULTAR CARGOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('system','Tarifario','user','PrincipalTarifa');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"TARIFARIOS - OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function ConsultarCargoTarifa()//
	{
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - CONSULTAR CARGOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CARGOS DEL CUPS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"6%\" >CARGO</td>";
		$this->salida .= "      <td width=\"50%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"20%\">GRUPO TIPO CARGO</td>";
		$this->salida .= "      <td width=\"20%\">TIPO CARGO</td>";
		$this->salida .= "      <td width=\"4%\">NIVEL</td>";
		$cargos=$this->BuscarConsultarCargoTarifa();
		$ciclo=sizeof($cargos);
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
			$this->salida .= "".$cargos[$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$cargos[$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$cargos[$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$cargos[$i]['des2']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$cargos[$i]['nivel']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($cargos))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"5\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"center\">";
		$accion=ModuloGetURL('system','Tarifario','user','MantenimientoTarifa');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER AL MANTENIMIENTO\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraCoCa();
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
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=2;";
		$this->salida .= "var url2 = url+'?bandera='+ban;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('system','Tarifario','user','ConsultarCargoTarifa',array(
		'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"45%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigotari\" value=\"".$_REQUEST['codigotari']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descritari\" value=\"".$_REQUEST['descritari']."\" maxlength=\"40\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">GRUPO CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomGrupo\" value=\"".$_REQUEST['NomGrupo']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"grupo\" value=\"".$_REQUEST['grupo']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">TIPO CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomClase\" value=\"".$_REQUEST['NomClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"clasePr\" value=\"".$_REQUEST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$ruta='system_modules/Tarifario/ClasificacionGrupos.php';
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\">";
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
		$accion=ModuloGetURL('system','Tarifario','user','ConsultarCargoTarifa');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraCoCa()//Barra paginadora de la creación de equivalencias
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
		$accion=ModuloGetURL('system','Tarifario','user','ConsultarCargoTarifa',array('conteo'=>$this->conteo,
		'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr']));
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
	function ModificarCargoTarifa()//
	{
		UNSET($_SESSION['tarif1']['datoscargo']);
		UNSET($_SESSION['tarif1']['cargos']);
		if($_SESSION['taric1']['grupo']<>NULL OR $_SESSION['taric1']['NomGrupo']<>NULL
		OR $_SESSION['taric1']['clasePr']<>NULL OR $_SESSION['taric1']['NomClase']<>NULL)
		{
			$_REQUEST['grupo']=$_SESSION['taric1']['grupo'];
			$_REQUEST['NomGrupo']=$_SESSION['taric1']['NomGrupo'];
			$_REQUEST['clasePr']=$_SESSION['taric1']['clasePr'];
			$_REQUEST['NomClase']=$_SESSION['taric1']['NomClase'];
			UNSET($_SESSION['taric1']);
		}
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - MODIFICAR CARGOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table>";
		}
		$this->salida .= "  <fieldset><legend class=\"field\">CARGOS DEL CUPS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CARGO</td>";
		$this->salida .= "      <td width=\"84%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"8%\" >MODIFICAR</td>";
		$_SESSION['tarif1']['cargos']=$this->BuscarModificarCargoTarifa();
		$ciclo=sizeof($_SESSION['tarif1']['cargos']);
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
			$this->salida .= "".$_SESSION['tarif1']['cargos'][$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['tarif1']['cargos'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "  <a href=\"". ModuloGetURL('system','Tarifario','user','Modificar1CargosTarifa',
			array('indicecarg'=>$i,'Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
			'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari'],
			'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
			'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'])) ."\">CARGO</a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['tarif1']['cargos']))
		{
			$this->salida .= "  <tr class=\"modulo_list_claro\">";
			$this->salida .= "  <td align=\"center\" colspan=\"3\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN CARGO'";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"center\">";
		$accion=ModuloGetURL('system','Tarifario','user','MantenimientoTarifa');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER AL MANTENIMIENTO\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraMoCa();
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
		$this->salida .= "<SCRIPT>";//$this->salida .= "f=frm;\n";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm){\n";
		$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=2;";
		$this->salida .= "var url2 = url+'?bandera='+ban;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('system','Tarifario','user','ModificarCargoTarifa',array(
		'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"45%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigotari\" value=\"".$_REQUEST['codigotari']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descritari\" value=\"".$_REQUEST['descritari']."\" maxlength=\"40\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">GRUPO CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomGrupo\" value=\"".$_REQUEST['NomGrupo']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"grupo\" value=\"".$_REQUEST['grupo']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">TIPO CARGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomClase\" value=\"".$_REQUEST['NomClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"clasePr\" value=\"".$_REQUEST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$ruta='system_modules/Tarifario/ClasificacionGrupos.php';
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\">";
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
		$accion=ModuloGetURL('system','Tarifario','user','ModificarCargoTarifa');
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraMoCa()//Barra paginadora de la creación de equivalencias
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
		$accion=ModuloGetURL('system','Tarifario','user','ModificarCargoTarifa',array('conteo'=>$this->conteo,
		'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr']));
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
	function Modificar1CargosTarifa()//
	{
		if($_SESSION['tarif1']['datoscargo']['cargo']==NULL)
		{
			$_SESSION['tarif1']['datoscargo']=$this->BuscarCargoModificarTarifa($_SESSION['tarif1']['cargos'][$_REQUEST['indicecarg']]['cargo']);
			UNSET($_SESSION['tarif1']['cargos']);
		}
		if(($_REQUEST['grupo']<>NULL OR $_REQUEST['NomGrupo']<>NULL
		OR $_REQUEST['clasePr']<>NULL OR $_REQUEST['NomClase']<>NULL)
		AND $this->uno <> 1)
		{
			$_SESSION['taric1']['grupo']=$_REQUEST['grupo'];
			$_SESSION['taric1']['NomGrupo']=$_REQUEST['NomGrupo'];
			$_SESSION['taric1']['clasePr']=$_REQUEST['clasePr'];
			$_SESSION['taric1']['NomClase']=$_REQUEST['NomClase'];
		}
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - MODIFICAR UN CARGO DEL CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass1(nombre, url, ancho, altura, x, frm, gruta, subta){\n";
		$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=2;";
		$this->salida .= "var url2 = url+'?bandera='+ban+'&grupo='+gruta+'&clasePr='+subta;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, gruta, subta){\n";
		$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=2;";
		$this->salida .= "var url2 = url+'?bandera='+ban+'&grupotarif='+gruta+'&subgrtarif='+subta;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('system','Tarifario','user','ValidarModificar1CargosTarifa',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table>";
		}
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL CARGO</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"label\">CARGO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$this->salida .= "".$_SESSION['tarif1']['datoscargo']['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("descripcit")."\">DESCRIPCIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descripcit\" value=\"".$_SESSION['tarif1']['datoscargo']['descripcion']."\" maxlength=\"600\" size=\"100\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\" class=\"".$this->SetStyle("grupo")."\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$_POST['NomGrupo']=$_SESSION['tarif1']['datoscargo']['des1'];
		$_POST['grupo']=$_SESSION['tarif1']['datoscargo']['grupo_tipo_cargo'];
		$this->salida .= "      <input type=\"text\" name=\"NomGrupo\" value=\"".$_POST['NomGrupo']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"grupo\" value=\"".$_POST['grupo']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\" class=\"".$this->SetStyle("clasePr")."\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$_POST['NomClase']=$_SESSION['tarif1']['datoscargo']['des2'];
		$_POST['clasePr']=$_SESSION['tarif1']['datoscargo']['tipo_cargo'];
		$this->salida .= "      <input type=\"text\" name=\"NomClase\" value=\"".$_POST['NomClase']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"clasePr\" value=\"".$_POST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$rut='system_modules/Tarifario/ClasificacionGrupos.php';
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass1('PARAMETROS','$rut',450,200,0,this.form,'".$_SESSION['tarif1']['datoscargo']['grupo_tipo_cargo']."','".$_SESSION['tarif1']['datoscargo']['tipo_cargo']."')\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">GRUPO TARIFARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$_POST['nomgrupota']=$_SESSION['tarif1']['datoscargo']['des3'];
		$_POST['grupotarif']=$_SESSION['tarif1']['datoscargo']['grupo_tarifario_id'];
		$this->salida .= "      <input type=\"text\" name=\"nomgrupota\" value=\"".$_POST['nomgrupota']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"grupotarif\" value=\"".$_POST['grupotarif']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td class=\"label\">SUBGRUPO TARIFARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$_POST['nomsubgrta']=$_SESSION['tarif1']['datoscargo']['des4'];
		$_POST['subgrtarif']=$_SESSION['tarif1']['datoscargo']['subgrupo_tarifario_id'];
		$this->salida .= "      <input type=\"text\" name=\"nomsubgrta\" value=\"".$_POST['nomsubgrta']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"subgrtarif\" value=\"".$_POST['subgrtarif']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$ruta='system_modules/Tarifario/ClasificacionTarifarios.php';
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'".$_SESSION['tarif1']['datoscargo']['grupo_tarifario_id']."','".$_SESSION['tarif1']['datoscargo']['subgrupo_tarifario_id']."')\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarNivelesTarifa();
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("nivelatent")."\">NIVEL DE ATENCIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"nivelatent\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_SESSION['tarif1']['datoscargo']['nivel']==$grupotarif[$i]['nivel'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['nivel']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['nivel']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarConceptosRipsTarifa();
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("conceptort")."\">CONCEPTO RIPS:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"conceptort\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_SESSION['tarif1']['datoscargo']['concepto_rips']==$grupotarif[$i]['concepto_rips'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['concepto_rips']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['concepto_rips']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarNivelAutorizadorTarifa();
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("nivelautot")."\">NIVEL AUTORIZADOR:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"nivelautot\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_SESSION['tarif1']['datoscargo']['nivel_autorizador_id']==$grupotarif[$i]['nivel_autorizador_id'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['nivel_autorizador_id']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['nivel_autorizador_id']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("preciocart")."\">PRECIO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"preciocart\" value=\"".$_SESSION['tarif1']['datoscargo']['precio']."\" maxlength=\"13\" size=\"20\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("swuvrspret")."\">PRECIO EN UVR'S:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['tarif1']['datoscargo']['sw_uvrs']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swuvrspret\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swuvrspret\" value=1>";
		}
		if($_SESSION['tarif1']['datoscargo']['sw_uvrs']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swuvrspret\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swuvrspret\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("gravamenct")."\">GRAVAMEN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"gravamenct\" value=\"".$_SESSION['tarif1']['datoscargo']['gravamen']."\" maxlength=\"3\" size=\"3\">";
		$this->salida .= "%";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("honorariot")."\">HONORARIOS:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['tarif1']['datoscargo']['sw_honorarios']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"honorariot\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"honorariot\" value=1>";
		}
		if($_SESSION['tarif1']['datoscargo']['sw_honorarios']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"honorariot\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"honorariot\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("swposcargt")."\">P.O.S.:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['tarif1']['datoscargo']['sw_pos']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swposcargt\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swposcargt\" value=1>";
		}
		if($_SESSION['tarif1']['datoscargo']['sw_pos']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swposcargt\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swposcargt\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("swcantidat")."\">EXIGE CANTIDAD:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['tarif1']['datoscargo']['sw_cantidad']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swcantidat\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swcantidat\" value=1>";
		}
		if($_SESSION['tarif1']['datoscargo']['sw_cantidad']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swcantidat\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swcantidat\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarGruposMapiposTarifa();
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("gruposmapt")."\">GRUPOS MAPIPOS:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"gruposmapt\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_SESSION['tarif1']['datoscargo']['grupos_mapipos']==$grupotarif[$i]['grupos_mapipos'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['grupos_mapipos']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['grupos_mapipos']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('system','Tarifario','user','ModificarCargoTarifa',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigotari'=>$_REQUEST['codigotari'],'descritari'=>$_REQUEST['descritari']));
		$this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function PedirIngresarCargoTarifa()//
	{
		UNSET($_SESSION['tarif1']['datocarnut']);
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - GUARDAR UN NUEVO CARGO DEL CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, gruta, subta){\n";
		$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=2;";
		$this->salida .= "var url2 = url+'?bandera='+ban+'&grupo='+gruta+'&clasePr='+subta;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('system','Tarifario','user','ValidarPedirIngresarCargoTarifa');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL CARGO</legend>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\" class=\"".$this->SetStyle("numerocart")."\">CARGO (10):";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"numerocart\" value=\"".$_POST['numerocart']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\" class=\"".$this->SetStyle("grupo")."\">GRUPO TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$this->salida .= "      <input type=\"text\" name=\"NomGrupo\" value=\"".$_POST['NomGrupo']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"grupo\" value=\"".$_POST['grupo']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\" class=\"".$this->SetStyle("clasePr")."\">TIPO CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$this->salida .= "      <input type=\"text\" name=\"NomClase\" value=\"".$_POST['NomClase']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"clasePr\" value=\"".$_POST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$ruta='system_modules/Tarifario/ClasificacionGrupos.php';
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'".$_POST['grupo']."','".$_POST['clasePr']."')\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('system','Tarifario','user','MantenimientoTarifa');
		$this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function IngresarCargoTarifa()//
	{
		$this->salida  = ThemeAbrirTabla('TARIFARIOS - GUARDAR UN NUEVO CARGO DEL CUPS');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x, frm, gruta, subta){\n";
		$this->salida .= "var str = 'width=600,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=2;";
		$this->salida .= "var url2 = url+'?bandera='+ban+'&grupotarif='+gruta+'&subgrtarif='+subta;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('system','Tarifario','user','ValidarIngresarCargoTarifa');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL CARGO</legend>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"label\">CARGO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"80%\">";
		$this->salida .= "".$_SESSION['tarif1']['datocarnut']['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("descripcit")."\">DESCRIPCIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descripcit\" value=\"".$_POST['descripcit']."\" maxlength=\"600\" size=\"100\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"label\">GRUPO TIPO CARGO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "".$_SESSION['tarif1']['datocarnut']['nomgrtipoc']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"label\">TIPO CARGO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "".$_SESSION['tarif1']['datocarnut']['nomsutipoc']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">GRUPO TARIFARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" name=\"nomgrupota\" value=\"".$_POST['nomgrupota']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"grupotarif\" value=\"".$_POST['grupotarif']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td class=\"label\">SUBGRUPO TARIFARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" name=\"nomsubgrta\" value=\"".$_POST['nomsubgrta']."\" size=\"100\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"subgrtarif\" value=\"".$_POST['subgrtarif']."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$ruta='system_modules/Tarifario/ClasificacionTarifarios.php';
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form,'".$_POST['grupotarif']."','".$_POST['subgrtarif']."')\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarNivelesTarifa();
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("nivelatent")."\">NIVEL DE ATENCIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"nivelatent\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_POST['nivelatent']==$grupotarif[$i]['nivel'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['nivel']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['nivel']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarConceptosRipsTarifa();
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("conceptort")."\">CONCEPTO RIPS:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"conceptort\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_POST['conceptort']==$grupotarif[$i]['concepto_rips'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['concepto_rips']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['concepto_rips']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarNivelAutorizadorTarifa();
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("nivelautot")."\">NIVEL AUTORIZADOR:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"nivelautot\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_POST['nivelautot']==$grupotarif[$i]['nivel_autorizador_id'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['nivel_autorizador_id']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['nivel_autorizador_id']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("preciocart")."\">PRECIO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"preciocart\" value=\"".$_POST['preciocart']."\" maxlength=\"13\" size=\"20\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("swuvrspret")."\">PRECIO EN UVR'S:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_POST['swuvrspret']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swuvrspret\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swuvrspret\" value=1>";
		}
		if($_POST['swuvrspret']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swuvrspret\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swuvrspret\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("gravamenct")."\">GRAVAMEN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"gravamenct\" value=\"".$_POST['gravamenct']."\" maxlength=\"3\" size=\"3\">";
		$this->salida .= "%";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("honorariot")."\">HONORARIOS:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_POST['honorariot']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"honorariot\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"honorariot\" value=1>";
		}
		if($_POST['honorariot']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"honorariot\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"honorariot\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("swposcargt")."\">P.O.S.:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_POST['swposcargt']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swposcargt\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swposcargt\" value=1>";
		}
		if($_POST['swposcargt']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swposcargt\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swposcargt\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("swcantidat")."\">EXIGE CANTIDAD:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_POST['swcantidat']==1)
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swcantidat\" value=1 checked>";
		}
		else
		{
			$this->salida .= "SI    <input type=\"radio\" name=\"swcantidat\" value=1>";
		}
		if($_POST['swcantidat']==0)
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swcantidat\" value=0 checked>";
		}
		else
		{
			$this->salida .= "    NO    <input type=\"radio\" name=\"swcantidat\" value=0>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$grupotarif=$this->BuscarGruposMapiposTarifa();
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>";
		$this->salida .= "      <label class=\"".$this->SetStyle("gruposmapt")."\">GRUPOS MAPIPOS:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"gruposmapt\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($grupotarif);
		for($i=0;$i<$ciclo;$i++)
		{
			if($_POST['gruposmapt']==$grupotarif[$i]['grupos_mapipos'])
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['grupos_mapipos']."\" selected>".$grupotarif[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$grupotarif[$i]['grupos_mapipos']."\">".$grupotarif[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('system','Tarifario','user','MantenimientoTarifa');
		$this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
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
