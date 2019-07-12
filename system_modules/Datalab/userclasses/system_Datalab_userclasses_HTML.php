
<?php

/**
* Modulo de Datalab (PHP).
*
* Modulo para el mantenimiento de los cargos del tarifario cups y de
* los cargos de la interface con datalab, asi como sus equivalencias
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* system_Datalab_userclasses_HTML.php
*
**/

class system_Datalab_userclasses_HTML extends system_Datalab_user
{
	function system_Datalab_user_HTML()
	{
		$this->system_Datalab_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos del Tarifario
	function PrincipalDatalab()//Llama a todas las opciones posibles
	{
		UNSET($_SESSION['datala']);
		$this->salida  = ThemeAbrirTabla('DATALAB - OPCIONES');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CARGOS DE CUPS - CARGOS DE DATALAB</legend>";
		$this->salida .= "      <table border=\"1\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"70%\">RELACIONAR CÓDIGOS</td>";
		$this->salida .= "      <td class=\"label\" width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','Datalab','user','RelacionarCargosDatala') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/equivalencia.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"70%\">CONSULTAR RELACIONES</td>";
		$this->salida .= "      <td class=\"label\" width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','Datalab','user','ConsultarCargosDatala') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td align=\"center\">";
		$accion=ModuloGetURL('system','Menu');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
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
	function RelacionarCargosDatala()//
	{
		UNSET($_SESSION['datala']);
		$this->salida  = ThemeAbrirTabla('DATALAB - RELACIONAR CÓDIGOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"left\" valign=\"top\">";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "  <fieldset><legend class=\"field\">CARGOS DE CUPS - CARGOS DE DATALAB</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"2\">CUPS</td>";
		$this->salida .= "      <td colspan=\"3\">DATALAB</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CARGOS</td>";
		$this->salida .= "      <td width=\"42%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"34%\">DETALLES</td>";
		$this->salida .= "      <td width=\"8%\" >PERFIL</td>";
		$this->salida .= "      <td width=\"8%\" >ELIMINAR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['datala']['cargosequi']=$this->BuscarRelacionarCargosDatala();
		$j=0;
		$ciclo=sizeof($_SESSION['datala']['cargosequi']);
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
			$this->salida .= "".$_SESSION['datala']['cargosequi'][$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['datala']['cargosequi'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\" colspan=\"3\">";
			if($_SESSION['datala']['cargosequi'][$i]['contar']==0)
			{
				$this->salida .= "NO HAY RELACIONES PARA ESTE CARGO";
			}
			else
			{
				$_SESSION['datala']['cargosdata']=$this->BuscarRelacionarCargosDetalleDatala($_SESSION['datala']['cargosequi'][$i]['cargo']);
				$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
				$ciclo1=sizeof($_SESSION['datala']['cargosdata']);
				for($l=0;$l<$ciclo1;$l++)
				{
					$this->salida .= "  <tr>";
					$this->salida .= "  <td width=\"68%\">";
					$this->salida .= "".$_SESSION['datala']['cargosdata'][$l]['codigo_datalab']."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td width=\"16%\">";
					$this->salida .= "".$_SESSION['datala']['cargosdata'][$l]['sw_perfil']."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\" width=\"16%\">";
					$this->salida .= "<a href=\"".ModuloGetURL('system','Datalab','user','EliminarCargosDatala',
					array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigodata'=>$_REQUEST['codigodata'],
					'descridata'=>$_REQUEST['descridata'],'buscardata'=>$_REQUEST['buscardata'],'indcargocup'=>$i,
					'indcargodat'=>$_SESSION['datala']['cargosdata'][$l]['codigo_datalab']))."\">
					<img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\"></a>";
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
				}
				$this->salida .= "  <tr>";
				$this->salida .= "  <td align=\"center\" colspan=\"3\">";
				$this->salida .= "<a href=\"".ModuloGetURL('system','Datalab','user','CrearCargosDatala',
				array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigodata'=>$_REQUEST['codigodata'],
				'descridata'=>$_REQUEST['descridata'],'buscardata'=>$_REQUEST['buscardata'],'indcargocup'=>$i))."\">
				<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"></a>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$this->salida .= "  </table>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['datala']['cargosequi']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARÓN CARGOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "  </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\">";
		$accion=ModuloGetURL('system','Datalab','user','PrincipalDatalab');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"DATALAB - OPCIONES\">";
		$this->salida .= "  </form>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\">";
		$var1=$this->RetornarBarraCupsData();
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
		$accion=ModuloGetURL('system','Datalab','user','RelacionarCargosDatala',
		array('codigodata'=>$_REQUEST['codigodata'],'descridata'=>$_REQUEST['descridata'],
		'buscardata'=>$_REQUEST['buscardata']));
		$this->salida .= "      <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td align=\"center\">";
		if($_REQUEST['buscardata']<>NULL)
		{
			$this->salida .= "<input type=\"checkbox\" name=\"buscardata\" value=1 checked>";
		}
		else
		{
			$this->salida .= "<input type=\"checkbox\" name=\"buscardata\" value=1>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\">BUSCAR SÓLO LOS CÓDIGOS RELACIONADOS";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigodata\" value=\"".$_REQUEST['codigodata']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descridata\" value=\"".$_REQUEST['descridata']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('system','Datalab','user','RelacionarCargosDatala');
		$this->salida .= "      <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "      </form>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraCupsData()//Barra paginadora de las equivalencias
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
		$accion=ModuloGetURL('system','Datalab','user','RelacionarCargosDatala',array('conteo'=>$this->conteo,
		'codigodata'=>$_REQUEST['codigodata'],'descridata'=>$_REQUEST['descridata'],'buscardata'=>$_REQUEST['buscardata']));
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

	//
	function CrearCargosDatala()//
	{
		if($_SESSION['datala']['indcargocup']==NULL)
		{
			$_SESSION['datala']['indcargocup']=$_REQUEST['indcargocup'];//identificador del cargo elegido
		}
		$this->salida  = ThemeAbrirTabla('CONTRATACIÓN - AUTORIZACIÓN INTERNA - EXCEPCIONES');
		$accion=ModuloGetURL('system','Datalab','user','ValidarCargosDatala',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigodata'=>$_REQUEST['codigodata'],
		'descridata'=>$_REQUEST['descridata'],'buscardata'=>$_REQUEST['buscardata']));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CODIGO CUPS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">CARGO</td>";
		$this->salida .= "      <td width=\"90%\">DESCRIPCIÓN</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "".$_SESSION['datala']['cargosequi'][$_SESSION['datala']['indcargocup']]['cargo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "".$_SESSION['datala']['cargosequi'][$_SESSION['datala']['indcargocup']]['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CODIGO DATALAB</legend>";
		$this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"50%\">DIGITE EL CODIGO</td>";
		$this->salida .= "      <td width=\"50%\">PERFIL</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cudacodigo\" value=\"".$_POST['cudacodigo']."\" maxlength=\"8\" size=\"8\">";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "1:    ";
		if($_POST['perfil']==1)
		{
			$this->salida .= "<input type=\"radio\" name=\"perfil\" value=1 checked>";
		}
		else
		{
			$this->salida .= "<input type=\"radio\" name=\"perfil\" value=1>";
		}
		$this->salida .= "    0:    ";
		if($_POST['perfil']==2 OR $_POST['perfil']==NULL)
		{
			$this->salida .= "<input type=\"radio\" name=\"perfil\" value=2 checked>";
		}
		else
		{
			$this->salida .= "<input type=\"radio\" name=\"perfil\" value=2>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"33%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  <td align=\"center\" width=\"34%\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR Y REPETIR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\" width=\"33%\">";
		$accion=ModuloGetURL('system','Datalab','user','RelacionarCargosDatala',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'codigodata'=>$_REQUEST['codigodata'],
		'descridata'=>$_REQUEST['descridata'],'buscardata'=>$_REQUEST['buscardata']));
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function ConsultarCargosDatala()//
	{
		$this->salida  = ThemeAbrirTabla('DATALAB - CONSULTAR RELACIONES');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"left\" valign=\"top\">";
		$this->salida .= "  <fieldset><legend class=\"field\">CARGOS DE CUPS - CARGOS DE DATALAB</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"2\">CUPS</td>";
		$this->salida .= "      <td colspan=\"2\">DATALAB</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CARGOS</td>";
		$this->salida .= "      <td width=\"42%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"42%\">DETALLES</td>";
		$this->salida .= "      <td width=\"8%\" >PERFIL</td>";
		$this->salida .= "      </tr>";
		$cargoscons=$this->BuscarConsultarCargosDatala();
		$j=0;
		$ciclo=sizeof($cargoscons);
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
			$this->salida .= "".$cargoscons[$i]['cargo']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$cargoscons[$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\" colspan=\"2\">";
			if($cargoscons[$i]['contar']==0)
			{
				$this->salida .= "NO HAY RELACIONES PARA ESTE CARGO";
			}
			else
			{
				$cargos=$this->BuscarRelacionarCargosDetalleDatala($cargoscons[$i]['cargo']);
				$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
				$ciclo1=sizeof($cargos);
				for($l=0;$l<$ciclo1;$l++)
				{
					$this->salida .= "  <tr>";
					$this->salida .= "  <td width=\"84%\">";
					$this->salida .= "".$cargos[$l]['codigo_datalab']."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td width=\"16%\">";
					$this->salida .= "".$cargos[$l]['sw_perfil']."";
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
				}
				$this->salida .= "  </table>";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($cargoscons))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"4\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARÓN CARGOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "  </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\">";
		$accion=ModuloGetURL('system','Datalab','user','PrincipalDatalab');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"DATALAB - OPCIONES\">";
		$this->salida .= "  </form>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\">";
		$var1=$this->RetornarBarraConsCuDa();
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
		$accion=ModuloGetURL('system','Datalab','user','ConsultarCargosDatala',
		array('codigodata'=>$_REQUEST['codigodata'],'descridata'=>$_REQUEST['descridata']));
		$this->salida .= "      <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">CARGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigodata\" value=\"".$_REQUEST['codigodata']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descridata\" value=\"".$_REQUEST['descridata']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('system','Datalab','user','ConsultarCargosDatala');
		$this->salida .= "      <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "      </form>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraConsCuDa()//Barra paginadora de las equivalencias
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
		$accion=ModuloGetURL('system','Datalab','user','ConsultarCargosDatala',array('conteo'=>$this->conteo,
		'codigodata'=>$_REQUEST['codigodata'],'descridata'=>$_REQUEST['descridata']));
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

}//fin de la clase
?>
