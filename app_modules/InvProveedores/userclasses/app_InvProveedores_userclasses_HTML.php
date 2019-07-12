
<?php

/**
* Modulo de InvProveedores (PHP).
*
* Modulo para la administración de los proveedores de insumos y medicamentos,
* teniendo presente los parametros de evaluación, su información básica,
* modelos de pago, acuerdos y cualquier tipo de datos, que permita la
* valoración y los recursos para la negociación directa con los mismos
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_InvProveedores_userclasses_HTML.php
*
* Clase que permite el acceso a los datos de los proveedores de mi inventario,
* así mismo permite realizar los procesos administrativos sobre los mismos
**/

class app_InvProveedores_userclasses_HTML extends app_InvProveedores_user
{
	function app_InvProveedores_user_HTML()
	{
		$this->app_InvProveedores_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de INVENTARIOS PROVEEDORES
	function PrincipalInvPro2()//Llama a todas las opciones posibles
	{
		UNSET($_SESSION['invpro']);
		UNSET($_SESSION['invenp']);
		if($this->UsuariosInvPro()==false)
		{
			return false;
		}
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de los provvedores
	function PrincipalInvPro()//Muestra los terceros proveedores de inventarios
	{
		if(empty($_SESSION['invpro']['empresa']))
		{
			$_SESSION['invpro']['empresa']=$_REQUEST['permisoinvpro']['empresa_id'];
			$_SESSION['invpro']['razonso']=$_REQUEST['permisoinvpro']['descripcion1'];
		}
		UNSET($_SESSION['invenp']);
		$this->salida  = ThemeAbrirTabla('INVENTARIOS PROVEEDORES');
		$accion=ModuloGetURL('app','InvProveedores','user','IngresaProveedorInvPro');
		$this->salida .= "  <form name=\"invprovee\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PROVEEDORES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['invpro']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"3%\" >No.</td>";
		$this->salida .= "      <td width=\"20%\">DOCUMENTO</td>";
		$this->salida .= "      <td width=\"48%\">NOMBRE</td>";
		$this->salida .= "      <td width=\"20%\">CENTRO DE UTILIDAD</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"4%\" >MENÚ</td>";
		$this->salida .= "      </tr>";
		$provempr=$this->BuscarProveedorInvPro($_SESSION['invpro']['empresa']);
		$i=$j=0;
		$ciclo=sizeof($provempr);
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
			$this->salida .= "".$provempr[$i]['tipo_id_tercero']."".' -- '."".$provempr[$i]['tercero_id']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$provempr[$i]['nombre_tercero']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			if($provempr[$i]['centro_utilidad']==NULL)
			{
				$this->salida .= "SIN CENTRO DE UTILIDAD";
			}
			else
			{
				$this->salida .= "".$provempr[$i]['descripcion']."";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($provempr[$i]['estado'] == 1)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','InvProveedores','user','CambiarEstadoInvPro',
				array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'descriinvp'=>$_REQUEST['descriinvp'],
				'codigoinvp'=>$_REQUEST['codigoinvp'],'provelegip'=>$provempr[$i]['codigo_proveedor_id'],'estado'=>$provempr[$i]['estado'])) ."\">
				<img src=\"".GetThemePath()."/images/proveedorac.png\" border=\"0\"></a>";
			}
			else if($provempr[$i]['estado'] == 0)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','InvProveedores','user','CambiarEstadoInvPro',
				array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],'descriinvp'=>$_REQUEST['descriinvp'],
				'codigoinvp'=>$_REQUEST['codigoinvp'],'provelegip'=>$provempr[$i]['codigo_proveedor_id'],'estado'=>$provempr[$i]['estado'])) ."\">
				<img src=\"".GetThemePath()."/images/proveedorinac.png\" border=\"0\"></a>";
			}
			else if($provempr[$i]['estado'] == 3)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivoip.gif\" border=\"0\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','InvProveedores','user','MenuProveedorInvPro',
			array('provelegip'=>$provempr[$i]['codigo_proveedor_id'])) ."\">
			<img src=\"".GetThemePath()."/images/tabla.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		if(empty($provempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"6\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PROVEEDOR RELACIONADO A LA EMPRESA'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\"><br>";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO PROVEEDOR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','InvProveedores','user','PrincipalInvPro2');
		$this->salida .= "  <form name=\"invprovee1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"EMPRESAS\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
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
		$accion=ModuloGetURL('app','InvProveedores','user','PrincipalInvPro',
		array('codigoinvp'=>$_REQUEST['codigoinvp'],'descriinvp'=>$_REQUEST['descriinvp']));
		$this->salida .= "  <form name=\"invprovee2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoinvp\" value=\"".$_REQUEST['codigoinvp']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descriinvp\" value=\"".$_REQUEST['descriinvp']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','InvProveedores','user','PrincipalInvPro');
		$this->salida .= "  <form name=\"invprovee3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraProvee()//Barra paginadora de los proveedores
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
		$accion=ModuloGetURL('app','InvProveedores','user','PrincipalInvPro',array('conteo'=>$this->conteo,
		'codigoinvp'=>$_REQUEST['codigoinvp'],'descriinvp'=>$_REQUEST['descriinvp']));
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

	//Función que captura los datos del proveedor tercero
	function IngresaProveedorInvPro()//Válida los datos y los guarda
	{
		$this->salida  = ThemeAbrirTabla('INVENTARIOS PROVEEDORES - DATOS DEL TERCERO');
		$mostrar=ReturnClassBuscador('proveedores','','','contratacion','');
		$this->salida .=$mostrar;
		$this->salida .="</script>\n";
		$accion=ModuloGetURL('app','InvProveedores','user','ValidarEleccionInvPro');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL PROVEEDOR</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['invpro']['razonso']."";
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
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"label\">CENTRO DE UTILIDAD:</label>";
		$this->salida .= "      </td>";
		$centro=$this->BuscarCentroUtilidad($_SESSION['invpro']['empresa']);
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"centroutil\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($centro);
		for($i=0;$i<$ciclo;$i++)
		{
			if($centro[$i]['centro_utilidad']==$_POST['centroutil'])
			{
				$this->salida .="<option value=\"".$centro[$i]['centro_utilidad']."\" selected>".$centro[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$centro[$i]['centro_utilidad']."\">".$centro[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
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
		$this->salida .= "      <label class=\"".$this->SetStyle("estado")."\">ESTADO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\">ACTIVO";
		if($_POST['estado']==1 OR empty($_POST['estado']))
		{
			$this->salida .= "      <input type=\"radio\" name=\"estado\" value=1 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"estado\" value=1>";
		}
		$this->salida .= "  INACTVO";
		if($_POST['estado']==2)
		{
			$this->salida .= "      <input type=\"radio\" name=\"estado\" value=2 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"estado\" value=2>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"label\">CUPO (VACÍO CON CUPO INDEFINIDO):</label>";//".$this->SetStyle("cupoprovip")."
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cupoprovip\" value=\"".$_POST['cupoprovip']."\" maxlength=\"17\" size=\"17\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("tiempoenip")."\">TIEMPO DE ENTREGA:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"tiempoenip\" value=\"".$_POST['tiempoenip']."\" maxlength=\"5\" size=\"17\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("diasgracip")."\">DÍAS DE GRACIA:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"diasgracip\" value=\"".$_POST['diasgracip']."\" maxlength=\"5\" size=\"17\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("diascredip")."\">DÍAS DE CRÉDITO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"diascredip\" value=\"".$_POST['diascredip']."\" maxlength=\"5\" size=\"17\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("desporcoip")."\">DESCUENTO POR CONTADO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"desporcoip\" value=\"".$_POST['desporcoip']."\" maxlength=\"6\" size=\"17\">"."%";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$formaspago=$this->BuscarFormasPagoInvPro();
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("formpagoip")."\">FORMAS DE PAGO ACEPTADAS<br>POR EL PROVEEDOR:</label>";
		$this->salida .= "      <input type=\"hidden\" name=\"formpagoip\" value=\"".sizeof($formaspago)."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "          <table border=\"1\" width=\"100%\" align=\"center\" class=\"modulo_list_claro\">";
		for($i=0;$i<sizeof($formaspago);$i++)
		{
			$this->salida .= "      <tr align=\"center\">";
			$this->salida .= "      <td width=\"80%\" class=\"label\">";
			$this->salida .= "".$formaspago[$i]['descripcion']."";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"20%\">";
			if($_POST['formpagoip'.$i]==$formaspago[$i]['formas_pago_id'])
			{
				$this->salida .= "  <input type=\"checkbox\" name=\"formpagoip".$i."\" value=".$formaspago[$i]['formas_pago_id']." checked>";
			}
			else
			{
				$this->salida .= "  <input type=\"checkbox\" name=\"formpagoip".$i."\" value=".$formaspago[$i]['formas_pago_id'].">";
			}
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		$this->salida .= "          </table>";
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
		$accion=ModuloGetURL('app','InvProveedores','user','PrincipalInvPro');
		$this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que establece si se van a guardar los datos o se va a editar o agregar un nuevo proveedor
	function ValidarEleccionInvPro()//Válida que operación debe hacer
	{
		if($_POST['guardar']=="GUARDAR")
		{
			$this->GuardarProveedorInvPro();
		}
		else if($_POST['guardar']=="EDITAR PROVEEDOR")
		{
			$this->GuardarTerceroInvPro();//1
		}
		return true;
	}

	//Función que permite realizar mantenimiento sobre el proveedor elegido
	function MenuProveedorInvPro()//Opciones del proveedor
	{
		if(empty($_SESSION['invenp']['provineleg']))
		{
			$_SESSION['invenp']['provineleg']=$_REQUEST['provelegip'];
			$_SESSION['invenp']['datos']=$this->BuscarDatosProveedorInvPro($_SESSION['invenp']['provineleg'],$_SESSION['invpro']['empresa']);
			if($_SESSION['invenp']['datos']['evaluacion']<>NULL)
			{
				$var=$this->BuscarDatosEvaluacionInvPro($_SESSION['invenp']['datos']['evaluacion']);
				$_SESSION['invenp']['datos']['fecha']=$var['fecha_evaluacion'];
				$_SESSION['invenp']['datos']['puntaje']=$var['puntaje_evaluacion'];
			}
		}
		UNSET($_SESSION['invenp']['criterioev']);
		UNSET($_SESSION['invenp']['evaluacion']);
		UNSET($_SESSION['invenp']['modcriteva']);
		$this->salida  = ThemeAbrirTabla('INVENTARIOS PROVEEDORES');
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">OPCIONES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['invpro']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['tipo_id_tercero']."".' --- '."".$_SESSION['invenp']['datos']['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['nombre_tercero']."";
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
		$this->salida .= "CONSULTAR INFORMACIÓN DEL PROVEEDOR";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','InvProveedores','user','MostrarDatosProveedorInvPro') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"70%\" align=\"center\" class=\"label\">";
		$this->salida .= "MODIFICAR INFORMACIÓN DEL PROVEEDOR";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"30%\" align=\"center\">";
		if(!($provempr[$i]['estado'] == 3))
		{
			$this->salida .= "<a href=\"". ModuloGetURL('app','InvProveedores','user','ModificarDatosProveedorInvPro') ."\">";
			$this->salida .= "<img src=\"".GetThemePath()."/images/proveedor.png\" border=\"0\"></a>";
		}
		else
		{
			$this->salida .= "<img src=\"".GetThemePath()."/images/proveedor.png\" border=\"0\">";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"70%\" align=\"center\" class=\"label\">";
		$this->salida .= "HISTORIAL DE EVALUACIONES DEL PROVEEDOR";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','InvProveedores','user','HistorialEvaluacionInvPro') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/historial.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"70%\" align=\"center\" class=\"label\">";
		$this->salida .= "EVALUAR AL PROVEEDOR";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','InvProveedores','user','CriteriosEvaluacionInvPro') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/proveedor_evaluar.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"70%\" align=\"center\" class=\"label\">";
		$this->salida .= "MODIFICAR LA ÚLTIMA EVALUACIÓN DEL PROVEEDOR";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','InvProveedores','user','ModificarEvaluacionInvPro') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','InvProveedores','user','PrincipalInvPro');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A PROVEEDORES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite modificar los datos del proveedor
	function ModificarDatosProveedorInvPro()//Válida los cambios a los datos del proveedor
	{
		if(!($this->uno == 1))
		{
			$_POST['tipoTerceroId']=$_SESSION['invenp']['datos']['tipo_id_tercero'];
			$_POST['codigo']=$_SESSION['invenp']['datos']['tercero_id'];
			$_POST['nombre']=$_SESSION['invenp']['datos']['nombre_tercero'];
			$_POST['estadoM']=$_SESSION['invenp']['datos']['estado'];
			$_POST['centroutilM']=$_SESSION['invenp']['datos']['centro_utilidad'];
			$_POST['cupoprovipM']=$_SESSION['invenp']['datos']['cupo'];
			$_POST['tiempoenipM']=$_SESSION['invenp']['datos']['tiempo_entrega'];
			$_POST['diasgracipM']=$_SESSION['invenp']['datos']['dias_gracia'];
			$_POST['diascredipM']=$_SESSION['invenp']['datos']['dias_credito'];
			$_POST['desporcoipM']=$_SESSION['invenp']['datos']['descuento_por_contado'];
			if($_SESSION['invenp']['datos']['estado']==0)
			{
				$_POST['estadoM']=2;
			}
		}
		$this->salida  = ThemeAbrirTabla('INVENTARIOS PROVEEDORES - DATOS DEL TERCERO - MODIFICAR');
		$accion=ModuloGetURL('app','InvProveedores','user','ValidarMEleccionInvPro');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL PROVEEDOR</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['invpro']['razonso']."";
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
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"label\">CENTRO DE UTILIDAD:</label>";
		$this->salida .= "      </td>";
		$centro=$this->BuscarCentroUtilidad($_SESSION['invpro']['empresa']);
		$this->salida .= "      <td>";
		$this->salida .= "      <select name=\"centroutilM\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		$ciclo=sizeof($centro);
		for($i=0;$i<$ciclo;$i++)
		{
			if($centro[$i]['centro_utilidad']==$_POST['centroutilM'])
			{
				$this->salida .="<option value=\"".$centro[$i]['centro_utilidad']."\" selected>".$centro[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$centro[$i]['centro_utilidad']."\">".$centro[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
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
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("codigo")."\">CÓDIGO:</label>";
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
		$this->salida .= "      <label class=\"".$this->SetStyle("estadoM")."\">ESTADO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\">ACTIVO";
		if($_POST['estadoM']==1)
		{
			$this->salida .= "      <input type=\"radio\" name=\"estadoM\" value=1 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"estadoM\" value=1>";
		}
		$this->salida .= "  INACTVO";
		if($_POST['estadoM']==2)
		{
			$this->salida .= "      <input type=\"radio\" name=\"estadoM\" value=2 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"estadoM\" value=2>";
		}
		$this->salida .= "  RETIRADO";
		if($_POST['estadoM']==3)
		{
			$this->salida .= "      <input type=\"radio\" name=\"estadoM\" value=3 checked>";
		}
		else
		{
			$this->salida .= "      <input type=\"radio\" name=\"estadoM\" value=3>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"label\">CUPO (VACÍO CON CUPO INDEFINIDO):</label>";//".$this->SetStyle("cupoprovipM")."
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"cupoprovipM\" value=\"".$_POST['cupoprovipM']."\" maxlength=\"17\" size=\"17\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("tiempoenipM")."\">TIEMPO DE ENTREGA:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"tiempoenipM\" value=\"".$_POST['tiempoenipM']."\" maxlength=\"5\" size=\"17\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("diasgracipM")."\">DÍAS DE GRACIA:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"diasgracipM\" value=\"".$_POST['diasgracipM']."\" maxlength=\"5\" size=\"17\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("diascredipM")."\">DÍAS DE CRÉDITO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"diascredipM\" value=\"".$_POST['diascredipM']."\" maxlength=\"5\" size=\"17\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("desporcoipM")."\">DESCUENTO POR CONTADO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"desporcoipM\" value=\"".$_POST['desporcoipM']."\" maxlength=\"6\" size=\"17\">"."%";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$formaspago=$this->BuscarFormasPagoInvPro();
		$mostrarfor=$this->MostrarFormasPagoInvPro($_SESSION['invenp']['provineleg']);
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td colspan=\"3\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("formpagoipM")."\">FORMAS DE PAGO ACEPTADAS<br>POR EL PROVEEDOR:</label>";
		$this->salida .= "      <input type=\"hidden\" name=\"formpagoipM\" value=\"".sizeof($formaspago)."\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "          <table border=\"1\" width=\"100%\" align=\"center\" class=\"modulo_list_claro\">";
		for($i=0;$i<sizeof($formaspago);$i++)
		{
			$this->salida .= "      <tr align=\"center\">";
			$this->salida .= "      <td width=\"80%\" class=\"label\">";
			$this->salida .= "".$formaspago[$i]['descripcion']."";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"20%\">";
			if($_POST['formpagoipM'.$i]==$formaspago[$i]['formas_pago_id'] OR $mostrarfor[$formaspago[$i]['formas_pago_id']]==1)
			{
				$this->salida .= "  <input type=\"checkbox\" name=\"formpagoipM".$i."\" value=".$formaspago[$i]['formas_pago_id']." checked>";
			}
			else
			{
				$this->salida .= "  <input type=\"checkbox\" name=\"formpagoipM".$i."\" value=".$formaspago[$i]['formas_pago_id'].">";
			}
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		$this->salida .= "          </table>";
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
		$accion=ModuloGetURL('app','InvProveedores','user','MenuProveedorInvPro');
		$this->salida .= "  <form name=\"contrata\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que establece si se van a guardar los datos o se va a editar o agregar un nuevo proveedor
	function ValidarMEleccionInvPro()//Válida que operación debe hacer
	{
		if($_POST['guardar']=="GUARDAR")
		{
			$this->ModificarProveedorInvPro();
		}
		else if($_POST['guardar']=="EDITAR PROVEEDOR")
		{
			$this->ModificarTerceroInvPro();//1
		}
		return true;
	}

	//Función que muestra los datos del proveedor
	function MostrarDatosProveedorInvPro()//Vuelve a la función de donde fue llamada
	{
		$this->salida  = ThemeAbrirTabla('INVENTARIOS PROVEEDORES - DATOS DEL TERCERO');
		$accion=ModuloGetURL('app','InvProveedores','user','MenuProveedorInvPro');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL PROVEEDOR</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"45%\">NOMBRE DE LA EMPRESA";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"55%\">";
		$this->salida .= "      ".$_SESSION['invpro']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>CENTRO DE UTILIDAD";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['invenp']['datos']['centro_utilidad']<>NULL)
		{
			$this->salida .= "      ".$_SESSION['invenp']['datos']['descripcion']."";
		}
		else
		{
			$this->salida .= "SIN CENTRO DE UTILIDAD";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>TIPO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['tipo_id_tercero']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>CÓDIGO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>NOMBRE";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['nombre_tercero']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      <td>PAIS";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>".$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_SESSION['invenp']['datos']['tipo_pais_id']))."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td>DEPARTAMENTO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>".$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_SESSION['invenp']['datos']['tipo_pais_id'],'Dpto'=>$_SESSION['invenp']['datos']['tipo_dpto_id']))."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      <td>MUNICIPIO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>".$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_SESSION['invenp']['datos']['tipo_pais_id'],'Dpto'=>$_SESSION['invenp']['datos']['tipo_dpto_id'],'Mpio'=>$_SESSION['invenp']['datos']['tipo_mpio_id']))."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>DIRECCIÓN";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['direccion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>TELÉFONO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['invenp']['datos']['telefono']<>NULL)
		{
			$this->salida .= "".$_SESSION['invenp']['datos']['telefono']."";
		}
		else
		{
			$this->salida .= "NO HAY REGISTRO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>FAX";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['invenp']['datos']['fax']<>NULL)
		{
			$this->salida .= "".$_SESSION['invenp']['datos']['fax']."";
		}
		else
		{
			$this->salida .= "NO HAY REGISTRO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>E - MAIL";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['invenp']['datos']['email']<>NULL)
		{
			$this->salida .= "".$_SESSION['invenp']['datos']['email']."";
		}
		else
		{
			$this->salida .= "NO HAY REGISTRO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>CELULAR";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['invenp']['datos']['celular']<>NULL)
		{
			$this->salida .= "".$_SESSION['invenp']['datos']['celular']."";
		}
		else
		{
			$this->salida .= "NO HAY REGISTRO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>BUSCA PERSONA";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['invenp']['datos']['busca_persona']<>NULL)
		{
			$this->salida .= "".$_SESSION['invenp']['datos']['busca_persona']."";
		}
		else
		{
			$this->salida .= "NO HAY REGISTRO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>ESTADO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['invenp']['datos']['estado']==1)
		{
			$this->salida .= "  ACTIVO";
		}
		else if($_SESSION['invenp']['datos']['estado']==0)
		{
			$this->salida .= "  INACTIVO";
		}
		else if($_SESSION['invenp']['datos']['estado']==3)
		{
			$this->salida .= "  RETIRADO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>CUPO (VACÍO CON CUPO INDEFINIDO)";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['cupo']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>TIEMPO DE ENTREGA";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['tiempo_entrega']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>DÍAS DE GRACIA";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['dias_gracia']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>DÍAS DE CRÉDITO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['dias_credito']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>DESCUENTO POR CONTADO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['descuento_por_contado']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$formaspago=$this->BuscarFormasPagoInvPro();
		$mostrarfor=$this->MostrarFormasPagoInvPro($_SESSION['invenp']['provineleg']);
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>FORMAS DE PAGO ACEPTADAS<br>POR EL PROVEEDOR";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\">";
		$this->salida .= "          <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_list_claro\">";
		for($i=0;$i<sizeof($formaspago);$i++)
		{
			if($mostrarfor[$formaspago[$i]['formas_pago_id']]==1)
			{
				$this->salida .= "      <tr>";
				$this->salida .= "      <td>";
				$this->salida .= "".$formaspago[$i]['descripcion']."";
				$this->salida .= "      </td>";
				$this->salida .= "      </tr>";
			}
		}
		$this->salida .= "          </table>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>FECHA DE LA ÚLTIMA EVALUACIÓN";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['invenp']['datos']['fecha']<>NULL)
		{
			$var=explode('-',$_SESSION['invenp']['datos']['fecha']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		}
		else
		{
			$this->salida .= "EL PROVEEDOR NO TIENE REGISTROS DE EVALUACIÓN";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>PUNTAJE DE LA ÚLTIMA EVALUACIÓN";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['invenp']['datos']['fecha']<>NULL)
		{
			$this->salida .= "".$_SESSION['invenp']['datos']['puntaje']."";
		}
		else
		{
			$this->salida .= "EL PROVEEDOR NO TIENE REGISTROS DE EVALUACIÓN";
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

	//Función que muestra la estructura de los criterios de evaluación para calificar al proveedor
	function CriteriosEvaluacionInvPro()//Válida los puntajes de evaluación
	{
		$this->salida  = ThemeAbrirTabla('INVENTARIOS PROVEEDORES - EVALUACIÓN');
		$accion=ModuloGetURL('app','InvProveedores','user','ValidarCriteriosEvaluacionInvPro');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['invpro']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['tipo_id_tercero']."".' --- '."".$_SESSION['invenp']['datos']['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['nombre_tercero']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CRITERIOS DE EVALUACIÓN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\"  class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("fecha")."\">FECHA DE EVALUACIÓN: </label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"10%\" align=\"center\">";
		if(empty($_POST['fecha']))
		{
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecha\" value=\"".date ("d/m/Y")."\" maxlength=\"10\" size=\"10\">";
		}
		else
		{
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecha\" value=\"".$_POST['fecha']."\" maxlength=\"10\" size=\"10\">";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      ".ReturnOpenCalendario('forma','fecha','/')."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"label\">ÚLTIMA FECHA DE EVALUACIÓN: </label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"30%\">";
		if($_SESSION['invenp']['datos']['fecha']<>NULL)
		{
			$var=explode('-',$_SESSION['invenp']['datos']['fecha']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		}
		else
		{
			$this->salida .= "NO HAY REGISTRO DE EVALUACIONES";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\"  class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"4%\" >¿?</td>";
		$this->salida .= "      <td width=\"4%\" >No.</td>";
		$this->salida .= "      <td width=\"78%\">DESCRIPCIÓN CRITERIO // SUBCRITERIO</td>";
		$this->salida .= "      <td width=\"7%\" >PUNTAJE</td>";
		$this->salida .= "      <td width=\"7%\" >CALIFICAR</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$l=1;
		$_SESSION['invenp']['criterioev']=$this->BuscarCriteriosEvaluacionInvPro($_SESSION['invpro']['empresa']);
		$ciclo=sizeof($_SESSION['invenp']['criterioev']);
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$l."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td class=\"label\" align=\"left\" colspan=\"2\">";
			$this->salida .= "".$_SESSION['invenp']['criterioev'][$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"left\"></td>";
			$this->salida .= "  <td align=\"left\"></td>";
			$this->salida .= "  </tr>";
			$k=$i;
			$m=1;
			while($_SESSION['invenp']['criterioev'][$i]['tipo_calificacion_id']==$_SESSION['invenp']['criterioev'][$k]['tipo_calificacion_id'])
			{
				$this->salida .= "  <tr $color>";
				$this->salida .= "  <td align=\"center\"></td>";
				$this->salida .= "  <td align=\"center\" width=\"5%\">";
				$this->salida .= "".$m."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"left\">";
				$this->salida .= "".$_SESSION['invenp']['criterioev'][$k]['des2']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"center\">";
				$this->salida .= "".$_SESSION['invenp']['criterioev'][$k]['puntaje']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"center\">";
				if(!empty($_POST['evaluacion'.$i]) AND $_SESSION['invenp']['evaluacion'][$i]['item_id']==$_SESSION['invenp']['criterioev'][$k]['item_id'])
				{
					$this->salida .= "<input type=\"radio\" name=\"evaluacion".$i."\" value=\"".$_SESSION['invenp']['criterioev'][$k]['item_id']."\" checked>";
				}
				else
				{
					$this->salida .= "<input type=\"radio\" name=\"evaluacion".$i."\" value=\"".$_SESSION['invenp']['criterioev'][$k]['item_id']."\">";
				}
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$m++;
				$k++;
			}
			$l++;
			$i=$k;
		}
		if(empty($_SESSION['invenp']['criterioev']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"5\">";
			$this->salida .= "'NO HAY CRITERIOS DE EVALUACIÓN O NO ESTÁN ACTIVOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\" width=\"50%\">";
		if(empty($_SESSION['invenp']['criterioev']))
		{
			$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"VALIDAR\" disabled=\"true\" >";
		}
		else
		{
			$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"VALIDAR\">";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <td align=\"center\" width=\"50%\">";
		$accion=ModuloGetURL('app','InvProveedores','user','MenuProveedorInvPro');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"VOLVER\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		UNSET($_SESSION['invenp']['evaluacion']);
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que muestra la evaluación y pide confirmación antes de guardar
	function MostrarCriteriosEvaluacionInvPro()//Muestra los items seleccionados, si confirma guarda sino permite modificar
	{
		$this->salida  = ThemeAbrirTabla('INVENTARIOS PROVEEDORES - GUARDAR LA EVALUACIÓN');
		$accion=ModuloGetURL('app','InvProveedores','user','GuardarCriteriosEvaluacionInvPro');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['invpro']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['tipo_id_tercero']."".' --- '."".$_SESSION['invenp']['datos']['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['nombre_tercero']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CRITERIOS DE EVALUACIÓN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\"  class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"20%\">";
		$this->salida .= "      <label class=\"label\">FECHA DE EVALUACIÓN: </label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"10%\" align=\"center\">";
		$this->salida .= "      ".$_SESSION['invenp']['evaluacion']['fecha']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\"></td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"4%\" >¿?</td>";
		$this->salida .= "      <td width=\"4%\" >No.</td>";
		$this->salida .= "      <td width=\"85%\">DESCRIPCIÓN CRITERIO // SUBCRITERIO</td>";
		$this->salida .= "      <td width=\"7%\" >PUNTAJE</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$l=1;
		$ciclo=sizeof($_SESSION['invenp']['criterioev']);
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$l."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td class=\"label\" align=\"left\" colspan=\"2\">";
			$this->salida .= "".$_SESSION['invenp']['criterioev'][$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"left\"></td>";
			$this->salida .= "  </tr>";
			$k=$i;
			$m=1;
			while($_SESSION['invenp']['criterioev'][$i]['tipo_calificacion_id']==$_SESSION['invenp']['criterioev'][$k]['tipo_calificacion_id'])
			{
				if($_SESSION['invenp']['evaluacion'][$i]['tipo_calificacion_id']==$_SESSION['invenp']['criterioev'][$k]['tipo_calificacion_id']
				AND $_SESSION['invenp']['evaluacion'][$i]['item_id']==$_SESSION['invenp']['criterioev'][$k]['item_id'])
				{
					$this->salida .= "  <tr $color>";
					$this->salida .= "  <td align=\"center\"></td>";
					$this->salida .= "  <td align=\"center\" width=\"5%\">";
					$this->salida .= "".$m."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"left\">";
					$this->salida .= "".$_SESSION['invenp']['criterioev'][$k]['des2']."";
					$this->salida .= "  </td>";
					$this->salida .= "  <td align=\"center\">";
					$this->salida .= "".$_SESSION['invenp']['criterioev'][$k]['puntaje']."";
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
				}
				$m++;
				$k++;
			}
			$l++;
			$i=$k;
		}
		if(!empty($_SESSION['invenp']['evaluacion']))
		{
			$this->salida .= "  <tr class=\"modulo_table_list_title\">";
			$this->salida .= "  <td align=\"right\" colspan=\"3\">";
			$this->salida .= "  PUNTAJE TOTAL";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$_SESSION['invenp']['evaluacion']['puntatotal']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['invenp']['evaluacion']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"4\">";
			$this->salida .= "'NO HAY CRITERIOS DE EVALUACIÓN SELECCIONADOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\" width=\"50%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <td align=\"center\" width=\"50%\">";
		$accion=ModuloGetURL('app','InvProveedores','user','CriteriosEvaluacionInvPro');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que busca el historial de las evaluaciones del proveedor
	function HistorialEvaluacionInvPro()//Vuelve desde donde fue llamada
	{
		$this->salida  = ThemeAbrirTabla('INVENTARIOS PROVEEDORES - HISTORIAL DE EVALUACIÓN');
		$accion=ModuloGetURL('app','InvProveedores','user','MenuProveedorInvPro');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['invpro']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['tipo_id_tercero']."".' --- '."".$_SESSION['invenp']['datos']['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['nombre_tercero']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CRITERIOS DE EVALUACIÓN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"4%\" >¿?</td>";
		$this->salida .= "      <td width=\"4%\" >No.</td>";
		$this->salida .= "      <td width=\"85%\">DESCRIPCIÓN CRITERIO /// SUBCRITERIO</td>";
		$this->salida .= "      <td width=\"7%\" >PUNTAJE</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$l=1;
		$historial=$this->BuscarHistorialEvaluacionInvPro($_SESSION['invenp']['provineleg']);
		$ciclo=sizeof($historial);
		for($i=0;$i<$ciclo;)
		{
			$this->salida .= "  <tr class=\"modulo_table_list_title\">";
			$this->salida .= "  <td align=\"right\" colspan=\"4\">";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td align=\"right\" width=\"56%\">";
			$this->salida .= "      FECHA DE LA EVALUACIÓN:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"left\" width=\"44%\">";
			$var=explode('-',$historial[$i]['fecha_evaluacion']);
			$this->salida .= "      ".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "      </td>";
			$this->salida .= "      </table>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
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
			$k=$i;
			$m=1;
			while($historial[$i]['evaluacion_id']==$historial[$k]['evaluacion_id'])
			{
				$this->salida .= "  <tr $color>";
				$this->salida .= "  <td align=\"center\">";
				$this->salida .= "".$l."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td class=\"label\" align=\"left\" colspan=\"2\">";
				$this->salida .= "".$historial[$k]['des1']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"left\"></td>";
				$this->salida .= "  </tr>";
				$this->salida .= "  <tr $color>";
				$this->salida .= "  <td align=\"center\"></td>";
				$this->salida .= "  <td align=\"center\" width=\"5%\">";
				$this->salida .= "".$m."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"left\">";
				$this->salida .= "".$historial[$k]['des2']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"center\">";
				$this->salida .= "".$historial[$k]['puntaje']."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$m++;
				$k++;
			}
			$this->salida .= "  <tr class=\"modulo_table_list_title\">";
			$this->salida .= "  <td align=\"right\" colspan=\"3\">";
			$this->salida .= "  PUNTAJE TOTAL";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$historial[$i]['puntaje_evaluacion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$l++;
			$i=$k;
		}
		if(empty($historial))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"4\">";
			$this->salida .= "'NO HAY CRITERIOS DE EVALUACIÓN SELECCIONADOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER AL MENÚ\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite modificar la última evaluación
	function ModificarEvaluacionInvPro()//Busca la evaluación, muestra los datos y válida los cambios
	{
		$this->salida  = ThemeAbrirTabla('INVENTARIOS PROVEEDORES - EVALUACIÓN - MODIFCAR');
		$accion=ModuloGetURL('app','InvProveedores','user','ValidarModificarEvaluacionInvPro');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['invpro']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['tipo_id_tercero']."".' --- '."".$_SESSION['invenp']['datos']['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['invenp']['datos']['nombre_tercero']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CRITERIOS DE EVALUACIÓN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"40%\">";
		$this->salida .= "      <label class=\"label\">ÚLTIMA FECHA DE EVALUACIÓN: </label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"60%\">";
		if($_SESSION['invenp']['datos']['fecha']<>NULL)
		{
			$var=explode('-',$_SESSION['invenp']['datos']['fecha']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		}
		else
		{
			$this->salida .= "NO HAY REGISTRO DE EVALUACIONES";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\"  class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"4%\" >¿?</td>";
		$this->salida .= "      <td width=\"4%\" >No.</td>";
		$this->salida .= "      <td width=\"78%\">DESCRIPCIÓN CRITERIO // SUBCRITERIO</td>";
		$this->salida .= "      <td width=\"7%\" >PUNTAJE</td>";
		$this->salida .= "      <td width=\"7%\" >CALIFICAR</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$l=1;
		if($_SESSION['invenp']['datos']['evaluacion']<>NULL)
		{
			$_SESSION['invenp']['modcriteva']=$this->BuscarModificarEvaluacionInvPro($_SESSION['invenp']['datos']['evaluacion']);
		}
		$ciclo=sizeof($_SESSION['invenp']['modcriteva']);
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
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$l."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td class=\"label\" align=\"left\" colspan=\"2\">";
			$this->salida .= "".$_SESSION['invenp']['modcriteva'][$i]['des1']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"left\"></td>";
			$this->salida .= "  <td align=\"left\"></td>";
			$this->salida .= "  </tr>";
			$k=$i;
			$m=1;
			while($_SESSION['invenp']['modcriteva'][$i]['tipo_calificacion_id']==$_SESSION['invenp']['modcriteva'][$k]['tipo_calificacion_id'])
			{
				$this->salida .= "  <tr $color>";
				$this->salida .= "  <td align=\"center\"></td>";
				$this->salida .= "  <td align=\"center\" width=\"5%\">";
				$this->salida .= "".$m."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"left\">";
				$this->salida .= "".$_SESSION['invenp']['modcriteva'][$k]['des2']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"center\">";
				$this->salida .= "".$_SESSION['invenp']['modcriteva'][$k]['puntaje']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td align=\"center\">";
				if($_SESSION['invenp']['modcriteva'][$k]['guardado']<>NULL)
				{
					$this->salida .= "<input type=\"radio\" name=\"evaluacionM".$i."\" value=\"".$_SESSION['invenp']['modcriteva'][$k]['item_id']."\" checked>";
				}
				else
				{
					$this->salida .= "<input type=\"radio\" name=\"evaluacionM".$i."\" value=\"".$_SESSION['invenp']['modcriteva'][$k]['item_id']."\">";
				}
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$m++;
				$k++;
			}
			$l++;
			$i=$k;
		}
		if(empty($_SESSION['invenp']['modcriteva']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"5\">";
			$this->salida .= "'EL PROVEEDOR NO HA SIDO EVALUADO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\" width=\"50%\">";
		if(empty($_SESSION['invenp']['modcriteva']))
		{
			$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\" disabled=\"true\" >";
		}
		else
		{
			$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <td align=\"center\" width=\"50%\">";
		$accion=ModuloGetURL('app','InvProveedores','user','MenuProveedorInvPro');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"VOLVER\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

}//fin de la clase
?>
