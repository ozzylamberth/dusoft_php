
<?php

/**
* Modulo de Paragrafos (PHP).
*
* Modulo para el mantenimiento de los cargos del tarifario cups y de
* los cargos de la interface con datalab, asi como sus equivalencias
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* system_Paragrafos_userclasses_HTML.php
*
**/

class system_Paragrafos_userclasses_HTML extends system_Paragrafos_user
{
	function system_Paragrafos_user_HTML()
	{
		$this->system_Paragrafos_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos del Tarifario
	function PrincipalParagrafos()//Llama a todas las opciones posibles
	{
		UNSET($_SESSION['paragr']);
		$this->salida  = ThemeAbrirTabla('TIPOS DE PARAGRAFADOS - OPCIONES');
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CLASIFICACIÓN DE LOS PARAGRAFADOS DE INSUMOS Y MEDICAMENTOS</legend>";
		$this->salida .= "      <table border=\"1\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$accion=ModuloGetURL('system','Paragrafos','user','ValidarNuevosTiposParagr');
		$this->salida .= "      <form name=\"tarifarios1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <td class=\"label\" width=\"40%\">NUEVO GRUPO CLASIFICACIÓN DE PARAGRAFADOS</td>";
		$this->salida .= "      <td class=\"label\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descritipo\" value=\"".$_REQUEST['descritipo']."\" maxlength=\"100\" size=\"30\">";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\" width=\"20%\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$accion=ModuloGetURL('system','Paragrafos','user','ProductosClasificacionParagr');
		$this->salida .= "      <form name=\"tarifarios1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <td class=\"label\" width=\"40%\">MODIFICAR PRODUCTOS DE UNA CLASIFICACIÓN</td>";
		$this->salida .= "      <td class=\"label\" width=\"40%\" align=\"center\">";
		$tipos=$this->BuscarTiposParagr();
		$this->salida .= "      <select name=\"tipomodipa\" class=\"select\">";
		$this->salida .= "      <option value=\"-1\">-- SELECCIONE --</option>";
		$ciclo=sizeof($tipos);
		for($i=0;$i<$ciclo;$i++)
		{
			if($tipos[$i]['tipo_para_imd']==$_POST['tipomodipa'])
			{
				$this->salida .="<option value=\"".$tipos[$i]['tipo_para_imd']."".','."".$tipos[$i]['descripcion']."\" selected>".$tipos[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$tipos[$i]['tipo_para_imd']."".','."".$tipos[$i]['descripcion']."\">".$tipos[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\" width=\"20%\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"modificar\" value=\"MODIFICAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
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
	function ProductosClasificacionParagr()//
	{
		if($_REQUEST['tipomodipa']==-1 AND $_SESSION['paragr']['tipoparagr']==NULL)
		{
			$this->frmError["MensajeError"]="SELECCIONE UNA CLASIFICACIÓN";
			$this->uno=1;
			$this->PrincipalParagrafos();
			return true;
		}
		if($_SESSION['paragr']['tipoparagr']==NULL)
		{
			$var=explode(',',$_REQUEST['tipomodipa']);
			$_SESSION['paragr']['tipoparagr']=$var[0];
			$_SESSION['paragr']['detiparagr']=$var[1];
		}
		UNSET($_SESSION['paragr']['servicimdp']);
		UNSET($_SESSION['paragr']['dserviimdp']);
		UNSET($_SESSION['paragr']['departimdp']);
		UNSET($_SESSION['paragr']['ddeparimdp']);
		UNSET($_SESSION['paragr']['dempreimdp']);
		UNSET($_SESSION['paragr']['servdepimd']);
		UNSET($_SESSION['paragr']['codigosimd']);
		$this->salida  = ThemeAbrirTabla('TIPOS DE PARAGRAFADOS - SERVICIOS Y DEPARTAMENTOS');
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','Paragrafos','user','PrincipalParagrafos') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">SERVICIOS Y DEPARTAMENTOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"100%\" colspan=\"2\">SERVICIOS ASISTENCIALES CONTRATADOS</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"100%\" colspan=\"2\">MENÚ - INSUMOS Y MEDICAMENTOS</td>";
		$this->salida .= "      </tr>";
		$_SESSION['paragr']['servdepimd']=$this->MostrarServicios();
		$ciclo=sizeof($_SESSION['paragr']['servdepimd']);
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
			$this->salida .= "  <td align=\"center\" width=\"40%\">";
			$this->salida .= "".$_SESSION['paragr']['servdepimd'][$i]['descripcion']."";
			$this->salida .= "  <br><label class=\"label_mark\">".$_SESSION['paragr']['servdepimd'][$i]['razon_social']."</label>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\" width=\"60%\">";
			$k=$i;
			while($_SESSION['paragr']['servdepimd'][$i]['servicio']==$_SESSION['paragr']['servdepimd'][$k]['servicio'])
			{
				$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" $color>";
				$this->salida .= "  <tr>";
				$this->salida .= "  <td align=\"center\" width=\"50%\">";
				$this->salida .= "  ".$_SESSION['paragr']['servdepimd'][$k]['descdept']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"center\" width=\"25%\">";
				$this->salida .= "<a href=\"". ModuloGetURL('system','Paragrafos','user','ConsultarProductosParagr',
				array('servdepati'=>$k)) ."\"><img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a>";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"center\" width=\"25%\">";
				$this->salida .= "<a href=\"". ModuloGetURL('system','Paragrafos','user','ModificarProductosParagr',
				array('servdepati'=>$k)) ."\"><img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$this->salida .= "  </table>";
				$k++;
			}
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$i=$k;
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$accion=ModuloGetURL('system','Paragrafos','user','PrincipalParagrafos');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER AL MENÚ\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//
	function ModificarProductosParagr()//
	{
		if($_SESSION['paragr']['servicimdp']==NULL)
		{
			$_SESSION['paragr']['servicimdp']=$_SESSION['paragr']['servdepimd'][$_REQUEST['servdepati']]['servicio'];
			$_SESSION['paragr']['dserviimdp']=$_SESSION['paragr']['servdepimd'][$_REQUEST['servdepati']]['descripcion'];
			$_SESSION['paragr']['departimdp']=$_SESSION['paragr']['servdepimd'][$_REQUEST['servdepati']]['departamento'];
			$_SESSION['paragr']['ddeparimdp']=$_SESSION['paragr']['servdepimd'][$_REQUEST['servdepati']]['descdept'];
			$_SESSION['paragr']['empresimdp']=$_SESSION['paragr']['servdepimd'][$_REQUEST['servdepati']]['empresa_id'];
			$_SESSION['paragr']['dempreimdp']=$_SESSION['paragr']['servdepimd'][$_REQUEST['servdepati']]['razon_social'];
			UNSET($_SESSION['paragr']['servdepimd']);
		}
		UNSET($_SESSION['paragr']['codigosimd']);
		$this->salida  = ThemeAbrirTabla('TIPOS DE PARAGRAFADOS - SERVICIOS Y DEPARTAMENTOS - MODIFICAR');
		$accion=ModuloGetURL('system','Paragrafos','user','ValidarModificarProductosParagr',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigotipa'=>$_REQUEST['codigotipa'],'descritipa'=>$_REQUEST['descritipa']));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','Paragrafos','user','ProductosClasificacionParagr') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PARAGRAFADOS INSUMOS Y MEDICAMENTOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['paragr']['dempreimdp']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['paragr']['dserviimdp']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:</td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['paragr']['ddeparimdp']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">TIPO DE PARAGRAFADO:</td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['paragr']['detiparagr']."";
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
		$this->salida .= "      <td colspan=\"2\">INSUMOS Y MEDICAMENTOS PARAGRAFADOS INCLUIDOS EN EL SERVICIO</td>";
		$this->salida .= "      </tr>";
		$paragraimd=$this->BuscarContarProductosParagr($_SESSION['paragr']['tipoparagr'],$_SESSION['paragr']['servicimdp'],$_SESSION['paragr']['departimdp']);
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"40%\">NÚMERO DE PRODUCTOS EN ESTA CLASIFICACIÓN</td>";
		$this->salida .= "      <td width=\"60%\">".$paragraimd."</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">ADIC/ELIM</td>";
		$this->salida .= "      <td width=\"10%\">CÓDIGO</td>";
		$this->salida .= "      <td width=\"80%\">DESCRIPCIÓN</td>";
		$this->salida .= "      </tr>";
		$_SESSION['paragr']['codigosimd']=$this->BuscarModificarProductosParagr($_SESSION['paragr']['empresimdp'],
		$_SESSION['paragr']['tipoparagr'],$_SESSION['paragr']['servicimdp'],$_SESSION['paragr']['departimdp']);
		$j=0;
		$ciclo=sizeof($_SESSION['paragr']['codigosimd']);
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
			$this->salida .= "<tr $color>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['paragr']['codigosimd'][$i]['tipo_para_imd']<>NULL)
			{
				$this->salida .= "<input type=\"checkbox\" name=\"grabarimd".$i."\" value=\"1\" checked>";
			}
			else
			{
				$this->salida .= "<input type=\"checkbox\" name=\"grabarimd".$i."\" value=\"1\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['paragr']['codigosimd'][$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['paragr']['codigosimd'][$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['paragr']['codigosimd']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"3\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN INSUMO Y/O MEDICAMENTO'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR PARAGRAFADOS\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('system','Paragrafos','user','ProductosClasificacionParagr');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER A LOS SERVICIOS\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var=$this->RetornarBarraTipoParaImdp();
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
		$accion=ModuloGetURL('system','Paragrafos','user','ModificarProductosParagr',
		array('codigotipa'=>$_REQUEST['codigotipa'],'descritipa'=>$_REQUEST['descritipa']));
		$this->salida .= "  <form name=\"forma3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigotipa\" value=\"".$_REQUEST['codigotipa']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descritipa\" value=\"".$_REQUEST['descritipa']."\" maxlength=\"50\" size=\"35\">";
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
		$accion=ModuloGetURL('system','Paragrafos','user','ModificarProductosParagr');
		$this->salida .= "  <form name=\"forma4\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraTipoParaImdp()//Barra paginadora de los Incumplimientos de los cargos
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
		$accion=ModuloGetURL('system','Paragrafos','user','ModificarProductosParagr',array('conteo'=>$this->conteo,
		'codigotipa'=>$_REQUEST['codigotipa'],'descritipa'=>$_REQUEST['descritipa']));
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
	function ConsultarProductosParagr()//
	{
		if($_SESSION['paragr']['servicimdp']==NULL)
		{
			$_SESSION['paragr']['servicimdp']=$_SESSION['paragr']['servdepimd'][$_REQUEST['servdepati']]['servicio'];
			$_SESSION['paragr']['dserviimdp']=$_SESSION['paragr']['servdepimd'][$_REQUEST['servdepati']]['descripcion'];
			$_SESSION['paragr']['departimdp']=$_SESSION['paragr']['servdepimd'][$_REQUEST['servdepati']]['departamento'];
			$_SESSION['paragr']['ddeparimdp']=$_SESSION['paragr']['servdepimd'][$_REQUEST['servdepati']]['descdept'];
			$_SESSION['paragr']['empresimdp']=$_SESSION['paragr']['servdepimd'][$_REQUEST['servdepati']]['empresa_id'];
			$_SESSION['paragr']['dempreimdp']=$_SESSION['paragr']['servdepimd'][$_REQUEST['servdepati']]['razon_social'];
			UNSET($_SESSION['paragr']['servdepimd']);
		}
		$this->salida  = ThemeAbrirTabla('TIPOS DE PARAGRAFADOS - SERVICIOS Y DEPARTAMENTOS - CONSULTAR');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('system','Paragrafos','user','ProductosClasificacionParagr') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PARAGRAFADOS INSUMOS Y MEDICAMENTOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['paragr']['dempreimdp']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">SERVICIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['paragr']['dserviimdp']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:</td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['paragr']['ddeparimdp']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">TIPO DE PARAGRAFADO:</td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['paragr']['detiparagr']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"2\">INSUMOS Y MEDICAMENTOS PARAGRAFADOS INCLUIDOS EN EL SERVICIO</td>";
		$this->salida .= "      </tr>";
		$paragraimd=$this->BuscarContarProductosParagr($_SESSION['paragr']['tipoparagr'],$_SESSION['paragr']['servicimdp'],$_SESSION['paragr']['departimdp']);
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"40%\">NÚMERO DE PRODUCTOS EN ESTA CLASIFICACIÓN</td>";
		$this->salida .= "      <td width=\"60%\">".$paragraimd."</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">CÓDIGO</td>";
		$this->salida .= "      <td width=\"90%\">DESCRIPCIÓN</td>";
		$this->salida .= "      </tr>";
		$paragraimd=$this->BuscarConsultarProductosParagr($_SESSION['paragr']['tipoparagr'],$_SESSION['paragr']['servicimdp'],$_SESSION['paragr']['departimdp']);
		$j=0;
		$ciclo=sizeof($paragraimd);
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
			$this->salida .= "<tr $color>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$paragraimd[$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$paragraimd[$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($paragraimd))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"2\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN INSUMO Y/O MEDICAMENTO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\">";
		$accion=ModuloGetURL('system','Paragrafos','user','ProductosClasificacionParagr');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\"  value=\"VOLVER A LOS SERVICIOS\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

}//fin de la clase
?>
