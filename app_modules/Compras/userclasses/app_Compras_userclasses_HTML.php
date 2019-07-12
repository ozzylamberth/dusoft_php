
<?php

/**
* Modulo de Compras (PHP).
*
* Modulo que establece los procesos y métodos, para realizar las compras
* de los medicamentos e insumos de la empresa, considerando el respectivo
* registro en contabilidad y actualizando el inventario de la misma.
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Compras_userclasses_HTML.php
*
* Clase que permite el acceso a los datos de compras, establece los mecanismos
* para llevar a cabo una compra, controlando todos los requerimientos de
* seguridad y actualizando el inventario; relacionado con el modulo Compras
**/

class app_Compras_userclasses_HTML extends app_Compras_user
{
	function app_Compras_user_HTML()
	{
		$this->app_Compras_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de COMPRAS
	function PrincipalCompra2()//Llama a todas las opciones posibles
	{
		UNSET($_SESSION['compra']);
		UNSET($_SESSION['compr1']);
		UNSET($_SESSION['compr2']);
		if($this->UsuariosCompra()==false)
		{
			return false;
		}
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de Compras
	function PrincipalCompra()//Llama a todas las opciones posibles
	{
		if($_SESSION['compra']['empresa']==NULL)
		{
			$_SESSION['compra']['empresa']=$_REQUEST['permisocompras']['empresa_id'];
			$_SESSION['compra']['razonso']=$_REQUEST['permisocompras']['descripcion1'];
			$_SESSION['compra']['centroutil']=$_REQUEST['permisocompras']['centro_utilidad'];
			$_SESSION['compra']['descentro']=$_REQUEST['permisocompras']['descripcion2'];
		}
		UNSET($_SESSION['compr1']);
		UNSET($_SESSION['compr2']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - OPCIONES');
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
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','ProveedoresProductosCompra') ."\">PRODUCTOS DE LA EMRPESA OFRECIDOS POR LOS PROVEEDORES</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','ParametrosEvaluacionCompra') ."\">PARÁMETROS DE EVALUACIÓN DE LOS PROVEEDORES</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','RequisicionesCompra') ."\">REQUISICIONES (PRODUCTOS PARA COTIZAR)</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','CotizacionesCompra') ."\">SOLICITAR COTIZACIONES</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','ProductosCompararCompra') ."\">CUADROS COMPARATIVOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','EnviarOrdenPedidoCompra') ."\">ENVIAR ORDENES DE PEDIDOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";/*
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','RecibirOrdenPedidoCompra') ."\">RECIBIR ORDENES DE PEDIDOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";*/
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','ConsultarOrdenPedidoCompra') ."\">CONSULTAR ORDENES DE PEDIDOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$accion=ModuloGetURL('app','Compras','user','PrincipalCompra2');
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

	//Función que permite elaborar los criterios de evaluación, según la empresa
	function ParametrosEvaluacionCompra()//Busca y válida los criterios a evaluar
	{
		UNSET($_SESSION['compr1']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - PARÁMETROS DE EVALUACIÓN');
		$accion=ModuloGetURL('app','Compras','user','IngresaCriterioEvaluacionCompra');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"99%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['descentro']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">CRITERIOS DE EVALUACIÓN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\"  class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"4%\" >No.</td>";
		$this->salida .= "      <td width=\"86%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >MENÚ</td>";
		$this->salida .= "      </tr>";
		$i=0;
		$j=0;
		$paraeval=$this->BuscarParametrosEvaluacionCompra($_SESSION['compra']['empresa']);
		$ciclo=sizeof($paraeval);
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
			$this->salida .= "".$paraeval[$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($paraeval[$i]['estado']==1)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','CambiarEstadoCompra',
				array('critereleg'=>$paraeval[$i]['tipo_calificacion_id'],'estado'=>$paraeval[$i]['estado'])) ."\">
				<img src=\"".GetThemePath()."/images/preguntaac.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','CambiarEstadoCompra',
				array('critereleg'=>$paraeval[$i]['tipo_calificacion_id'],'estado'=>$paraeval[$i]['estado'])) ."\">
				<img src=\"".GetThemePath()."/images/preguntainac.png\" border=\"0\"></a>";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','MenuParametrosCompra',
			array('critereleg'=>$paraeval[$i]['tipo_calificacion_id'],'descrieleg'=>$paraeval[$i]['descripcion'])) ."\">
			<img src=\"".GetThemePath()."/images/tabla.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		if(empty($paraeval))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"4\">";
			$this->salida .= "'NO HAY CRITERIOS DE EVALUACIÓN'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO CRITERIO\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$accion=ModuloGetURL('app','Compras','user','PrincipalCompra');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"principal\" value=\"COMPRAS - OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite crear nuevos criterios de evaluación
	function IngresaCriterioEvaluacionCompra()//Válida los criterios nuevos de evaluación
	{
		$this->salida  = ThemeAbrirTabla('COMPRAS - NUEVO CRITERIO DE EVALUACIÓN');
		$accion=ModuloGetURL('app','Compras','user','ValidarCriterioEvaluacionCompra');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['descentro']."";
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
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("descripcion")."\">DESCRIPCIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descripcion\" value=\"".$_POST['descripcion']."\" maxlength=\"255\" size=\"80\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("estado")."\">ESTADO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\" class=\"label\">ACTIVO";
		if($_POST['estado']==1 OR empty($_POST['estado']))
		{
			$this->salida .= "<input type=\"radio\" name=\"estado\" value=1 checked>";
		}
		else
		{
			$this->salida .= "<input type=\"radio\" name=\"estado\" value=1>";
		}
		$this->salida .= "  INACTVO";
		if($_POST['estado']==2)
		{
			$this->salida .= "<input type=\"radio\" name=\"estado\" value=2 checked>";
		}
		else
		{
			$this->salida .= "<input type=\"radio\" name=\"estado\" value=2>";
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
		$accion=ModuloGetURL('app','Compras','user','ParametrosEvaluacionCompra');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite modificar las características de los criterios, así como relacionar sus subitems
	function MenuParametrosCompra()//Válida los cambios y permite la creación de las subpreguntas
	{
		if($_SESSION['compr1']['critipeleg']==NULL)
		{
			$_SESSION['compr1']['critipeleg']=$_REQUEST['critereleg'];
			$_SESSION['compr1']['descrieleg']=$_REQUEST['descrieleg'];
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - OPCIONES DEL CRITERIO DE EVALUACIÓN');
		$accion=ModuloGetURL('app','Compras','user','IngresaSubCriterioEvaluacionCompra');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">OPCIONES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['descentro']."";
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
		$this->salida .= "CONSULTAR INFORMACIÓN DEL CRITERIO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','ConsultarCriterioEvaluacionCompra') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"70%\" align=\"center\" class=\"label\">";
		$this->salida .= "MODIFICAR INFORMACIÓN DEL CRITERIO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','ModificarCriterioEvaluacionCompra') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td><br>";
		$this->salida .= "  <fieldset><legend class=\"field\">SUB - CRITERIOS DE EVALUACIÓN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\"  class=\"modulo_table_list\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"10%\">CRITERIO</td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" width=\"90%\">".$_SESSION['compr1']['descrieleg']."</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\"  class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"4%\" >No.</td>";
		$this->salida .= "      <td width=\"72%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">PUNTAJE</td>";
		$this->salida .= "      <td width=\"7%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"7%\" >MODIFICAR</td>";
		$this->salida .= "      </tr>";
		$i=0;
		$j=0;
		$subceval=$this->BuscarSubCriterioEvaluacionCompra($_SESSION['compr1']['critipeleg']);
		$ciclo=sizeof($subceval);
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
			$this->salida .= "".$subceval[$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$subceval[$i]['puntaje']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($subceval[$i]['estado']==1)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','CambiarEstadoSubCompra',
				array('itemideleg'=>$subceval[$i]['item_id'],'estado'=>$subceval[$i]['estado'])) ."\">
				<img src=\"".GetThemePath()."/images/preguntaac.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','CambiarEstadoSubCompra',
				array('itemideleg'=>$subceval[$i]['item_id'],'estado'=>$subceval[$i]['estado'])) ."\">
				<img src=\"".GetThemePath()."/images/preguntainac.png\" border=\"0\"></a>";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','ModificarSubCriterioEvaluacionCompra',array(
			'itemideleg'=>$subceval[$i]['item_id'],'estadoeleg'=>$subceval[$i]['estado'],
			'dessubeleg'=>$subceval[$i]['descripcion'],'puntajeleg'=>$subceval[$i]['puntaje'])) ."\">
			<img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		if(empty($subceval))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"5\">";
			$this->salida .= "'NO HAY SUB - CRITERIOS DE EVALUACIÓN PARA ESTE CRITERIO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO SUB - CRITERIO\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','Compras','user','ParametrosEvaluacionCompra');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LOS CRITERIOS\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que muestra los datos del criterio elegido
	function ConsultarCriterioEvaluacionCompra()//Vuelve a la función que la llamo
	{
		$critever=$this->BuscarCriterioEvaluacionCompra($_SESSION['compr1']['critipeleg']);
		$subcritever=$this->BuscarSubCriterioEvaluacionCompra($_SESSION['compr1']['critipeleg']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - DATOS DEL CRITERIO DE EVALUACIÓN');
		$accion=ModuloGetURL('app','Compras','user','MenuParametrosCompra');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"40%\">NOMBRE DE LA EMPRESA";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"60%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">NOMBRE DEL CENTRO DE UTILIDAD";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['compra']['descentro']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">ESTADO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($critever['estado']==1)
		{
			$this->salida .= "  ACTIVO";
		}
		else if($critever['estado']==0)
		{
			$this->salida .= "  INACTIVO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">DESCRIPCIÓN DEL CRITERIO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$critever['descripcion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"4%\" >No.</td>";
		$this->salida .= "      <td width=\"76%\">DESCRIPCIÓN DE LOS SUBCRITERIOS</td>";
		$this->salida .= "      <td width=\"10%\">PUNTAJE</td>";
		$this->salida .= "      <td width=\"10%\">ESTADO</td>";
		$this->salida .= "      </tr>";
		$i=0;
		$j=0;
		$ciclo=sizeof($subcritever);
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
			$this->salida .= "".$subcritever[$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$subcritever[$i]['puntaje']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($subcritever[$i]['estado']==1)
			{
				$this->salida .= "ACTIVO";
			}
			else if($subcritever[$i]['estado']==0)
			{
				$this->salida .= "INACTIVO";
			}
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		if(empty($subcritever))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td align=\"center\" colspan=\"3\">";
			$this->salida .= "'NO HAY SUB - CRITERIOS DE EVALUACIÓN PARA ESTE CRITERIO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
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

	//Función que permite modificar los datos del criterio de evaluación
	function ModificarCriterioEvaluacionCompra()//Válida los cambios a los datos del criterio de evaluación
	{
		if(!($this->uno == 1))
		{
			$critever=$this->BuscarCriterioEvaluacionCompra($_SESSION['compr1']['critipeleg']);
			$_POST['descripcionM']=$critever['descripcion'];
			$_POST['estadoM']=$critever['estado'];
			if($critever['estado']==0)
			{
				$_POST['estadoM']=2;
			}
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - CRITERIO DE EVALUACIÓN - MODIFICAR');
		$accion=ModuloGetURL('app','Compras','user','Validar2CriterioEvaluacionCompra');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['descentro']."";
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
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("descripcionM")."\">DESCRIPCIÓN:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descripcionM\" value=\"".$_POST['descripcionM']."\" maxlength=\"255\" size=\"80\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("estadoM")."\">ESTADO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\" class=\"label\">ACTIVO";
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
		$accion=ModuloGetURL('app','Compras','user','MenuParametrosCompra');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite crear nuevos subcriterios de evaluación
	function IngresaSubCriterioEvaluacionCompra()//Válida los datos del nuevo sub criterio
	{
		$this->salida  = ThemeAbrirTabla('COMPRAS - NUEVO SUB CRITERIO DE EVALUACIÓN');
		$accion=ModuloGetURL('app','Compras','user','ValidarSubCriterioEvaluacionCompra');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['descentro']."";
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
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\" width=\"30%\">DESCRIPCIÓN DEL CRITERIO</td>";
		$this->salida .= "      <td class=\"label\" width=\"70%\">".$_SESSION['compr1']['descrieleg']."</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("descripcion")."\">DESCRIPCIÓN DEL SUB CRITERIO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descripcion\" value=\"".$_POST['descripcion']."\" maxlength=\"255\" size=\"80\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("puntaje")."\">PUNTAJE:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"puntaje\" value=\"".$_POST['puntaje']."\" maxlength=\"5\" size=\"80\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("estado")."\">ESTADO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\" class=\"label\">ACTIVO";
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
		$accion=ModuloGetURL('app','Compras','user','MenuParametrosCompra');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite modificar los datos de un sub criterio
	function ModificarSubCriterioEvaluacionCompra()//Válida y guarda los cambios en el sub criterio
	{
		if(!($this->uno == 1))
		{
			$_POST['itemM']=$_REQUEST['itemideleg'];
			$_POST['descripcionM']=$_REQUEST['dessubeleg'];
			$_POST['puntajeM']=$_REQUEST['puntajeleg'];
			$_POST['estadoM']=$_REQUEST['estadoeleg'];
			if($_REQUEST['estadoeleg']==0)
			{
				$_POST['estadoM']=2;
			}
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - SUB CRITERIO DE EVALUACIÓN - MODIFICAR');
		$accion=ModuloGetURL('app','Compras','user','Validar2SubCriterioEvaluacionCompra');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['descentro']."";
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
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\" width=\"30%\">DESCRIPCIÓN DEL CRITERIO</td>";
		$this->salida .= "      <td class=\"label\" width=\"70%\">".$_SESSION['compr1']['descrieleg']."</td>";
		$this->salida .= "      <input type=\"hidden\" name=\"itemM\" value=\"".$_POST['itemM']."\">";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("descripcionM")."\">DESCRIPCIÓN DEL SUB CRITERIO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"descripcionM\" value=\"".$_POST['descripcionM']."\" maxlength=\"255\" size=\"80\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("puntajeM")."\">PUNTAJE:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"puntajeM\" value=\"".$_POST['puntajeM']."\" maxlength=\"5\" size=\"80\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td width=\"30%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("estadoM")."\">ESTADO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"70%\" class=\"label\">ACTIVO";
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
		$accion=ModuloGetURL('app','Compras','user','MenuParametrosCompra');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite relacionar los proveedores con los productos que ofrecen a la empresa
	function ProveedoresProductosCompra()//A los proveedores activos
	{
		UNSET($_SESSION['compr1']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - PROVEEDORES');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PROVEEDORES DE LA EMPRESA</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['descentro']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"3%\" >No.</td>";
		$this->salida .= "      <td width=\"20%\">DOCUMENTO</td>";
		$this->salida .= "      <td width=\"53%\">NOMBRE</td>";
		$this->salida .= "      <td width=\"20%\">CENTRO DE UTILIDAD</td>";
		$this->salida .= "      <td width=\"4%\" >MENÚ</td>";
		$this->salida .= "      </tr>";
		$provempr=$this->BuscarProveedoresProductosCompra($_SESSION['compra']['empresa']);
		$i=0;
		$j=0;
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
			$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','MenuProveProduCompra',
			array('provelegip'=>$provempr[$i]['codigo_proveedor_id'])) ."\">
			<img src=\"".GetThemePath()."/images/proveedor.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$i++;
		}
		if(empty($provempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PROVEEDOR RELACIONADO A LA EMPRESA O QUE ESTE EN ESTADO ACTIVO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Compras','user','PrincipalCompra');
		$this->salida .= "  <form name=\"compravee1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"principal\" value=\"COMPRAS - OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var1=$this->RetornarBarraProvee();
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
		$accion=ModuloGetURL('app','Compras','user','ProveedoresProductosCompra',
		array('codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp']));
		$this->salida .= "  <form name=\"compravee2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descricomp\" value=\"".$_REQUEST['descricomp']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Compras','user','ProveedoresProductosCompra');
		$this->salida .= "  <form name=\"compravee3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraProvee()//Barra paginadora de los proveedores activos
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
		$accion=ModuloGetURL('app','Compras','user','ProveedoresProductosCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp']));
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

	//Función que muestra todas las opciones sobre los productos ofrecidos por el proveedor
	function MenuProveProduCompra()//Busca datos genéricos del proveedor, así como establecer las relaciones con los productos
	{
		if($_SESSION['compr1']['provineleg']==NULL)
		{
			$_SESSION['compr1']['provineleg']=$_REQUEST['provelegip'];
			$_SESSION['compr1']['datos']=$this->BuscarMenuProveProduCompra($_SESSION['compra']['empresa'],$_SESSION['compr1']['provineleg']);
			if($_SESSION['compr1']['datos']['evaluacion']<>NULL)
			{
				$var=$this->BuscarDatosEvaluacionCompra($_SESSION['compr1']['datos']['evaluacion']);
				$_SESSION['compr1']['datos']['fecha']=$var['fecha_evaluacion'];
				$_SESSION['compr1']['datos']['puntaje']=$var['puntaje_evaluacion'];
			}
		}
		UNSET($_SESSION['compr1']['codigospro']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - INFORMACIÓN DEL PROVEEDOR');
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">OPCIONES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['descentro']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr1']['datos']['tipo_id_tercero']."".' --- '."".$_SESSION['compr1']['datos']['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr1']['datos']['nombre_tercero']."";
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
		$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','ConsultarProveProduCompra') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"70%\" align=\"center\" class=\"label\">";
		$this->salida .= "CONSULTAR PRODUCTOS OFRECIDOS POR EL PROVEEDOR";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','ConsultarProveConProduCompra') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/producto_consultar.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"70%\" align=\"center\" class=\"label\">";
		$this->salida .= "PRECIOS DE LOS PRODUCTOS OFRECIDOS POR EL PROVEEDOR";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','PreciosProveConProduCompra') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/producto_precio.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"70%\" align=\"center\" class=\"label\">";
		$this->salida .= "RELACIONAR PRODUCTOS CON EL PROVEEDOR";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"30%\" align=\"center\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','RelacionarProveConProduCompra') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/producto_proveedor.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\" width=\"100%\">";
		$accion=ModuloGetURL('app','Compras','user','ProveedoresProductosCompra');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A PROVEEDORES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que muestra los del proveedor
	function ConsultarProveProduCompra()//Vuelva a la función de donde fue llamada
	{
		$this->salida  = ThemeAbrirTabla('COMPRAS - DATOS DEL PROVEEDOR');
		$accion=ModuloGetURL('app','Compras','user','MenuProveProduCompra');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"75%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL PROVEEDOR</legend>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"45%\">NOMBRE DE LA EMPRESA";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"55%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>CENTRO DE UTILIDAD";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['compr1']['datos']['centro_utilidad']<>NULL)
		{
			$this->salida .= "      ".$_SESSION['compr1']['datos']['descripcion']."";
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
		$this->salida .= "      ".$_SESSION['compr1']['datos']['tipo_id_tercero']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>CÓDIGO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['compr1']['datos']['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>NOMBRE";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['compr1']['datos']['nombre_tercero']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>ESTADO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['compr1']['datos']['estado']==1)
		{
			$this->salida .= "ACTIVO";
		}
		else if($_SESSION['compr1']['datos']['estado']==0)
		{
			$this->salida .= "INACTIVO";
		}
		else if($_SESSION['compr1']['datos']['estado']==3)
		{
			$this->salida .= "RETIRADO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td>PAIS";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>".$this->CallMetodoExterno('app','Triage','user','nombre_pais',$argumentos=array('Pais'=>$_SESSION['compr1']['datos']['tipo_pais_id']))."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      <td>DEPARTAMENTO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>".$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array('Pais'=>$_SESSION['compr1']['datos']['tipo_pais_id'],'Dpto'=>$_SESSION['compr1']['datos']['tipo_dpto_id']))."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td>MUNICIPIO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>".$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array('Pais'=>$_SESSION['compr1']['datos']['tipo_pais_id'],'Dpto'=>$_SESSION['compr1']['datos']['tipo_dpto_id'],'Mpio'=>$_SESSION['compr1']['datos']['tipo_mpio_id']))."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>DIRECCIÓN";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".$_SESSION['compr1']['datos']['direccion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>TELÉFONO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['compr1']['datos']['telefono']<>NULL)
		{
			$this->salida .= "".$_SESSION['compr1']['datos']['telefono']."";
		}
		else
		{
			$this->salida .= "NO HAY REGISTRO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>FAX";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['compr1']['datos']['fax']<>NULL)
		{
			$this->salida .= "".$_SESSION['compr1']['datos']['fax']."";
		}
		else
		{
			$this->salida .= "NO HAY REGISTRO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>E - MAIL";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['compr1']['datos']['email']<>NULL)
		{
			$this->salida .= "".$_SESSION['compr1']['datos']['email']."";
		}
		else
		{
			$this->salida .= "NO HAY REGISTRO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>CELULAR";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['compr1']['datos']['celular']<>NULL)
		{
			$this->salida .= "".$_SESSION['compr1']['datos']['celular']."";
		}
		else
		{
			$this->salida .= "NO HAY REGISTRO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td>BUSCA PERSONA";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['compr1']['datos']['busca_persona']<>NULL)
		{
			$this->salida .= "".$_SESSION['compr1']['datos']['busca_persona']."";
		}
		else
		{
			$this->salida .= "NO HAY REGISTRO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_oscuro>";
		$this->salida .= "      <td>FECHA DE LA ÚLTIMA EVALUACIÓN";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		if($_SESSION['compr1']['datos']['fecha']<>NULL)
		{
			$var=explode('-',$_SESSION['compr1']['datos']['fecha']);
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
		if($_SESSION['compr1']['datos']['fecha']<>NULL)
		{
			$this->salida .= "".$_SESSION['compr1']['datos']['puntaje']."";
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

	//Función que permite consultar los productos que son prestados por el proveedr
	function ConsultarProveConProduCompra()//Busca los productos ofrecidos por el proveedor
	{
		$this->salida  = ThemeAbrirTabla('COMPRAS - PRODUCTOS OFRECIDOS POR EL PROVEEDOR - CONSULTAR');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DEL PROVEEDOR ACTUAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['descentro']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr1']['datos']['tipo_id_tercero']."".' --- '."".$_SESSION['compr1']['datos']['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr1']['datos']['nombre_tercero']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"50%\">DESCRIPCIÓN/ UNIDAD / CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">CÓDIGO PROV.</td>";
		$this->salida .= "      <td width=\"12%\">VALOR</td>";
		$this->salida .= "      <td width=\"10%\">FECHA LISTA</td>";
		$this->salida .= "      <td width=\"10%\">FECHA VIGENCIA</td>";
		$this->salida .= "      </tr>";
		$codigospro=$this->BuscarConsultarProveConProduCompra($_SESSION['compra']['empresa'],$_SESSION['compr1']['provineleg']);
		$j=0;
		$ciclo=sizeof($codigospro);
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
			$this->salida .= "".$codigospro[$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$codigospro[$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$codigospro[$i]['desunidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$codigospro[$i]['contenido_unidad_venta']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$codigospro[$i]['codigo_producto_proveedor']."";
			$this->salida .= "</td>";
			if($codigospro[$i]['valor']<>NULL)
			{
				$this->salida .= "<td align=\"right\">";
				$valorver=$codigospro[$i]['valor'];
				$this->salida .= "".number_format(($valorver), 2, ',', '.')."";
				$this->salida .= "</td>";
				$this->salida .= "<td align=\"center\">";
				$var=explode('-',$codigospro[$i]['fecha_lista']);
				$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
				$this->salida .= "</td>";
				$this->salida .= "<td align=\"center\">";
				if($codigospro[$i]['fecha_vigencia']<>NULL)
				{
					$var=explode('-',$codigospro[$i]['fecha_vigencia']);
					$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
				}
				else
				{
					$this->salida .= "'NO HAY FECHA DE VIGENCIA'";
				}
				$this->salida .= "</td>";
			}
			else
			{
				$this->salida .= "<td colspan=\"3\" align=\"center\">";
				$this->salida .= "'EL PRODUCTO NO ESTÁ EN LA LISTA DE PRECIOS'";
				$this->salida .= "</td>";
			}
			$this->salida .= "</tr>";
		}
		if(empty($codigospro))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"6\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PRODUCTO O PROVEEDOR RELACIONADO A LA EMPRESA'";
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
		$accion=ModuloGetURL('app','Compras','user','MenuProveProduCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var1=$this->RetornarBarraProvProdCo();
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
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .= "f=frm;\n";
		$this->salida .= "var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=1;";
		$this->salida .= "var url2 = url+'?bandera='+ban;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('app','Compras','user','ConsultarProveConProduCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descricomp\" value=\"".$_REQUEST['descricomp']."\" maxlength=\"50\" size=\"35\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">GRUPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomGrupo\" value=\"".$_REQUEST['NomGrupo']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"grupo\" value=\"".$_REQUEST['grupo']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomClase\" value=\"".$_REQUEST['NomClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"clasePr\" value=\"".$_REQUEST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">SUBCLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomSubClase\" value=\"".$_REQUEST['NomSubClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"subclase\" value=\"".$_REQUEST['subclase']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$ruta='app_modules/Compras/ventanaClasificacion.php';
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
		$accion=ModuloGetURL('app','Compras','user','ConsultarProveConProduCompra');
		$this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraProvProdCo()//Barra paginadora de los productos que se relacionan con el proveedor
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
		$accion=ModuloGetURL('app','Compras','user','ConsultarProveConProduCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
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

	//Función que permite establecer los precios de los prodcutos que presta el proveedor
	function PreciosProveConProduCompra()//Válida y guarda los precios de los productos
	{
		UNSET($_SESSION['compr1']['codigospro']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - PRECIOS DE LOS PRODUCTOS OFRECIDOS POR EL PROVEEDOR');
		$accion=ModuloGetURL('app','Compras','user','ValidarPreciosProveConProduCompra',array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','MenuProveProduCompra') ."\">
		<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DE LA EMPRESA ACTUAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['descentro']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr1']['datos']['tipo_id_tercero']."".' --- '."".$_SESSION['compr1']['datos']['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr1']['datos']['nombre_tercero']."";
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
		$this->salida .= "      <td width=\"80%\">AYUDA</td>";
		$this->salida .= "      <td width=\"10%\">FECHA LISTA</td>";
		$this->salida .= "      <td width=\"10%\">FECHA VIGENCIA</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"center\">FECHAS APLICAN PARA TODOS LOS PRODUCTOS DE LA LISTA";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "          <table border=\"0\" width=\"100%\" align=\"center\" $color>";
		$this->salida .= "          <tr>";
		$this->salida .= "          <td align=\"center\">";
		if($_POST['fechalista']<>NULL)
		{
			$var=explode('-',$_POST['fechalista']);
			$_POST['fechalista']= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		}
		$this->salida .= "          <input type=\"text\" class=\"input-text\" name=\"fechalista\" value=\"".$_POST['fechalista']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "          </td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          <tr>";
		$this->salida .= "          <td align=\"center\" class=\"label\">";
		$this->salida .= "          ".ReturnOpenCalendario('forma1','fechalista','/')."";
		$this->salida .= "          </td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          </table>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "          <table border=\"0\" width=\"100%\" align=\"center\" $color>";
		$this->salida .= "          <tr>";
		$this->salida .= "          <td align=\"center\">";
		if($_POST['fechavigen']<>NULL)
		{
			$var=explode('-',$_POST['fechavigen']);
			$_POST['fechavigen']= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		}
		$this->salida .= "          <input type=\"text\" class=\"input-text\" name=\"fechavigen\" value=\"".$_POST['fechavigen']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "          </td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          <tr>";
		$this->salida .= "          <td align=\"center\" class=\"label\">";
		$this->salida .= "          ".ReturnOpenCalendario('forma1','fechavigen','/')."";
		$this->salida .= "          </td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          </table>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"3%\" class=\"modulo_list_claro\">";
		$this->salida .= "      <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"7%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"38%\">DESCRIPCIÓN / UNIDAD / CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">CÓDIGO PROV.</td>";
		$this->salida .= "      <td width=\"10%\">No. COTIZ.</td>";
		$this->salida .= "      <td width=\"12%\">VALOR</td>";
		$this->salida .= "      <td width=\"10%\">FECHA LISTA</td>";
		$this->salida .= "      <td width=\"10%\">FECHA VIGENCIA</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr1']['codigospro']=$this->BuscarPreciosProveConProduCompra($_SESSION['compra']['empresa'],$_SESSION['compr1']['provineleg']);
		$j=0;
		$ciclo=sizeof($_SESSION['compr1']['codigospro']);
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
			$this->salida .= "<input type=\"checkbox\" name=\"eliminar".$i."\" value=\"1\">";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr1']['codigospro'][$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$_SESSION['compr1']['codigospro'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$_SESSION['compr1']['codigospro'][$i]['desunidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$_SESSION['compr1']['codigospro'][$i]['contenido_unidad_venta']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"codiprdprv".$i."\" value=\"".$_SESSION['compr1']['codigospro'][$i]['codigo_producto_proveedor']."\" maxlength=\"20\" size=\"12\">";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"cotizacprv".$i."\" value=\"".$_SESSION['compr1']['codigospro'][$i]['numero_cotizacion']."\" maxlength=\"20\" size=\"12\">";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"valor".$i."\" value=\"".$_SESSION['compr1']['codigospro'][$i]['valor']."\" maxlength=\"16\" size=\"16\">";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td align=\"center\">";
			if($_SESSION['compr1']['codigospro'][$i]['fecha_lista']<>NULL)
			{
				$var=explode('-',$_SESSION['compr1']['codigospro'][$i]['fecha_lista']);
				$_POST['fechalista'.$i]= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			}
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechalista".$i."\" value=\"".$_POST['fechalista'.$i]."\" maxlength=\"10\" size=\"10\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "      ".ReturnOpenCalendario('forma1','fechalista'.$i,'/')."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td align=\"center\">";
			if($_SESSION['compr1']['codigospro'][$i]['fecha_vigencia']<>NULL)
			{
				$var=explode('-',$_SESSION['compr1']['codigospro'][$i]['fecha_vigencia']);
				$_POST['fechavigen'.$i]= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			}
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechavigen".$i."\" value=\"".$_POST['fechavigen'.$i]."\" maxlength=\"10\" size=\"10\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "      ".ReturnOpenCalendario('forma1','fechavigen'.$i,'/')."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['compr1']['codigospro']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"8\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PRODUCTO O PROVEEDOR RELACIONADO A LA EMPRESA'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','MenuProveProduCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var1=$this->RetornarBarraPrecios();
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
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .= "f=frm;\n";
		$this->salida .= "var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=1;";
		$this->salida .= "var url2 = url+'?bandera='+ban;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('app','Compras','user','PreciosProveConProduCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descricomp\" value=\"".$_REQUEST['descricomp']."\" maxlength=\"50\" size=\"35\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">GRUPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomGrupo\" value=\"".$_REQUEST['NomGrupo']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"grupo\" value=\"".$_REQUEST['grupo']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomClase\" value=\"".$_REQUEST['NomClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"clasePr\" value=\"".$_REQUEST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">SUBCLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomSubClase\" value=\"".$_REQUEST['NomSubClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"subclase\" value=\"".$_REQUEST['subclase']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$ruta='app_modules/Compras/ventanaClasificacion.php';
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
		$accion=ModuloGetURL('app','Compras','user','PreciosProveConProduCompra');
		$this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraPrecios()//Barra paginadora de los productos que se relacionan con el proveedor
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
		$accion=ModuloGetURL('app','Compras','user','PreciosProveConProduCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
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

	//Función que permite establecer que productos son ofrecidos por el proveedor seleccionado
	function RelacionarProveConProduCompra()//Válida que productos deben ser guardados, modificados o eliminados
	{
		UNSET($_SESSION['compr1']['codigospro']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - PRODUCTOS OFRECIDOS POR EL PROVEEDOR');
		$accion=ModuloGetURL('app','Compras','user','ValidarRelacionarProveConProduCompra',array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','MenuProveProduCompra') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DE LA EMPRESA ACTUAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL CENTRO DE UTILIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['descentro']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr1']['datos']['tipo_id_tercero']."".' --- '."".$_SESSION['compr1']['datos']['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr1']['datos']['nombre_tercero']."";
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
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"58%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">UNIDAD</td>";
		$this->salida .= "      <td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"8%\" >ADIC/ELIM</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr1']['codigospro']=$this->BuscarRelacionarProveConProduCompra($_SESSION['compra']['empresa'],$_SESSION['compr1']['provineleg']);
		$j=0;
		$ciclo=sizeof($_SESSION['compr1']['codigospro']);
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
			$this->salida .= "".$_SESSION['compr1']['codigospro'][$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr1']['codigospro'][$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr1']['codigospro'][$i]['desunidad']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr1']['codigospro'][$i]['contenido_unidad_venta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['compr1']['codigospro'][$i]['provee']<>NULL)
			{
				$this->salida .= "<input type=\"checkbox\" name=\"eliminar".$i."\" value=\"1\" checked>";
			}
			else
			{
				$this->salida .= "<input type=\"checkbox\" name=\"eliminar".$i."\" value=\"1\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['compr1']['codigospro']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PRODUCTO O PROVEEDOR RELACIONADO A LA EMPRESA'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','MenuProveProduCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var1=$this->RetornarBarraProvProd();
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
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .= "f=frm;\n";
		$this->salida .= "var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=1;";
		$this->salida .= "var url2 = url+'?bandera='+ban;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('app','Compras','user','RelacionarProveConProduCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descricomp\" value=\"".$_REQUEST['descricomp']."\" maxlength=\"50\" size=\"35\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">GRUPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomGrupo\" value=\"".$_REQUEST['NomGrupo']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"grupo\" value=\"".$_REQUEST['grupo']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomClase\" value=\"".$_REQUEST['NomClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"clasePr\" value=\"".$_REQUEST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">SUBCLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomSubClase\" value=\"".$_REQUEST['NomSubClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"subclase\" value=\"".$_REQUEST['subclase']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$ruta='app_modules/Compras/ventanaClasificacion.php';
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
		$accion=ModuloGetURL('app','Compras','user','RelacionarProveConProduCompra');
		$this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraProvProd()//Barra paginadora de los productos que se relacionan con el proveedor
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
		$accion=ModuloGetURL('app','Compras','user','RelacionarProveConProduCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
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

	//Función que permite controlar las requisiciones de compras
	function RequisicionesCompra()//Válida las solicitudes de requisiciones
	{
		UNSET($_SESSION['compr2']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - REQUISICIONES');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      MENÚ";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','CrearRequisicionCompra') ."\">CREAR UNA REQUISICIÓN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','ModifRequisicionCompra') ."\">MODIFICAR UNA REQUISICIÓN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','CancelarRequisicionCompra') ."\">CANCELAR UNA REQUISICIÓN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','ConsultarRequisicionCompra') ."\">CONSULTAR REQUISICIÓN(ES)</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Compras','user','PrincipalCompra');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"principal\" value=\"COMPRAS - OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que crea una requisición para uno o varios productos
	function CrearRequisicionCompra()//Válida los datos de quien solicita, y despues los productos y las cantidades
	{
		$this->salida  = ThemeAbrirTabla('COMPRAS - NUEVA REQUISICIÓN');
		$accion=ModuloGetURL('app','Compras','user','ValidarCrearRequisicionCompra');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRECIO DEL PRODUCTO ACTUAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$usuario=$this->NombreUsuarioCompra();
		$_SESSION['compr2']['usuarioide']=$usuario['usuario_id'];
		$_SESSION['compr2']['usuariodes']=$usuario['nombre'];
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"".$this->SetStyle("usuario")."\" width=\"40%\">";
		$this->salida .= "      <input type=\"hidden\" name=\"usuario\" value=\"".$_POST['usuario']."\">";
		$this->salida .= "NOMBRE DEL USUARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"60%\">";
		$this->salida .= "".$_SESSION['compr2']['usuariodes']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"".$this->SetStyle("departamen")."\" width=\"40%\">";
		$this->salida .= "DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$departamento=$this->BuscarDepartamentosCompra($_SESSION['compra']['empresa']);
		$this->salida .= "      <select name=\"departamen\" class=\"select\">";
		$this->salida .= "      <option value=\"\">-- SELECCIONE --</option>";
		$ciclo=sizeof($departamento);
		$departam=explode(',',$_POST['departamen']);
		for($i=0;$i<$ciclo;$i++)
		{
			if($departamento[$i]['departamento']==$departam[0])
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
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\">";
		$this->salida .= "FECHA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      ".date("d/m/Y")."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"".$this->SetStyle("razonsolic")."\">";
		$this->salida .= "OBSERVACIÓN SOBRE LA SOLICITUD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <textarea class=\"input-text\" name=\"razonsolic\" cols=\"60\" rows=\"4\">".$_POST['razonsolic']."</textarea>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','RequisicionesCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que relaciona los productos con la orden
	function CrearRequisicionProCompra()//Hasta que no guarde no deja cancelar la transacción
	{
		$this->salida  = ThemeAbrirTabla('COMPRAS - LISTA DE PRODUCTOS PARA SOLICITAR');
		$accion=ModuloGetURL('app','Compras','user','ValidarCrearRequisicionProCompra',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DE LA EMPRESA ACTUAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL USUARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['usuariodes']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['departades']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$var=explode('/',$_SESSION['compr2']['fecharequi']);
		$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">OBSERVACIÓN SOBRE LA SOLICITUD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['razonsolco']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['requisicio']."";
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
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"56%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">UNIDAD</td>";
		$this->salida .= "      <td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">CANTIDAD</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr2']['listaprodu']=$this->BuscarListaCotizarCompra($_SESSION['compra']['empresa'],$_SESSION['compr2']['requisicio']);
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['listaprodu']);
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
			$this->salida .= "".$_SESSION['compr2']['listaprodu'][$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['listaprodu'][$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['listaprodu'][$i]['desunidad']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['listaprodu'][$i]['contenido_unidad_venta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<input type=\"text\" name=\"uncantidad".$i."\" value=\"".$_SESSION['compr2']['listaprodu'][$i]['cantidad']."\" maxlength=\"10\" size=\"10\" class=\"input-text\">";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['compr2']['listaprodu']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PRODUCTO RELACIONADO A LA EMPRESA'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','RequisicionesCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		if($_SESSION['compr2']['sicancelar']==1)
		{
			$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		}
		else
		{
			$this->salida .= "  <input disabled=\"true\" class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		}
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var1=$this->RetornarBarraLisCot();
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
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .= "f=frm;\n";
		$this->salida .= "var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=1;";
		$this->salida .= "var url2 = url+'?bandera='+ban;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('app','Compras','user','CrearRequisicionProCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descricomp\" value=\"".$_REQUEST['descricomp']."\" maxlength=\"50\" size=\"35\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">GRUPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomGrupo\" value=\"".$_REQUEST['NomGrupo']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"grupo\" value=\"".$_REQUEST['grupo']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomClase\" value=\"".$_REQUEST['NomClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"clasePr\" value=\"".$_REQUEST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">SUBCLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomSubClase\" value=\"".$_REQUEST['NomSubClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"subclase\" value=\"".$_REQUEST['subclase']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$ruta='app_modules/Compras/ventanaClasificacion.php';
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
		$accion=ModuloGetURL('app','Compras','user','CrearRequisicionProCompra');
		$this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraLisCot()//Barra paginadora de los productos que se relacionan con el proveedor
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
		$accion=ModuloGetURL('app','Compras','user','CrearRequisicionProCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
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

	//Función que permite modificar el detalle de una requisición
	function ModifRequisicionCompra()//Busca la requisición a modificar
	{
		UNSET($_SESSION['compr2']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - MODIFICAR UNA REQUISICIÓN');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">REQUISICIONES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$_SESSION['compr2']['modrequisi']=$this->BuscarModifRequisicionCompra($_SESSION['compra']['empresa']);
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">REQUISICIÓN</td>";
		$this->salida .= "      <td width=\"10%\">FECHA</td>";
		$this->salida .= "      <td width=\"20%\">DEPARTAMENTO</td>";
		$this->salida .= "      <td width=\"40%\">NOMBRE DEL USUARIO</td>";
		$this->salida .= "      <td width=\"10%\">NÚMERO DE PRODUCTOS</td>";
		$this->salida .= "      <td width=\"10%\">MODIFICAR PRODUCTOS</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['modrequisi']);
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
			$this->salida .= "".$_SESSION['compr2']['modrequisi'][$i]['requisicion_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$var=explode('-',$_SESSION['compr2']['modrequisi'][$i]['fecha_requisicion']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['compr2']['modrequisi'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['compr2']['modrequisi'][$i]['nombre']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['modrequisi'][$i]['cantidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"".ModuloGetURL('app','Compras','user','ModifRequisicionProCompra',
			array('indicerequ'=>$i))."\"><img src=\"".GetThemePath()."/images/producto.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['compr2']['modrequisi']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"6\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚNA REQUISICIÓN'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraModRequi();
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
		$accion=ModuloGetURL('app','Compras','user','RequisicionesCompra');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"COMPRAS - REQUISICIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','Compras','user','ModifRequisicionCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
		$this->salida .= "      <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">FECHA (dd/mm/AAAA):";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecharcomp\" value=\"".$_REQUEST['fecharcomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">DEPARTAMENTO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$departamento=$this->BuscarDepartamentosCompra($_SESSION['compra']['empresa']);
		$this->salida .= "      <select name=\"descricomp\" class=\"select\">";
		$this->salida .= "      <option value=\"\">-- SELECCIONE --</option>";
		$ciclo=sizeof($departamento);
		for($i=0;$i<$ciclo;$i++)
		{
			if($departamento[$i]['departamento']==$_REQUEST['descricomp'])
			{
				$this->salida .="<option value=\"".$departamento[$i]['departamento']."\" selected>".$departamento[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$departamento[$i]['departamento']."\">".$departamento[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">NOMBRE DEL USUARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecomp\" value=\"".$_REQUEST['nombrecomp']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Compras','user','ModifRequisicionCompra');
		$this->salida .= "      <form name=\"forma3\" action=\"$accion\" method=\"post\">";
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

	function RetornarBarraModRequi()//Barra paginadora de las requisiciones
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
		$accion=ModuloGetURL('app','Compras','user','ModifRequisicionCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
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

	//Función que permite modificar los productos de una requisición
	function ModifRequisicionProCompra()//Busca la lista actual y permite modificar
	{
		if($_SESSION['compr2']['usuarioide']==NULL)
		{
			$_SESSION['compr2']['usuarioide']=$_SESSION['compr2']['modrequisi'][$_REQUEST['indicerequ']]['usuario_id'];
			$_SESSION['compr2']['usuariodes']=$_SESSION['compr2']['modrequisi'][$_REQUEST['indicerequ']]['nombre'];
			$_SESSION['compr2']['departaide']=$_SESSION['compr2']['modrequisi'][$_REQUEST['indicerequ']]['departamento'];
			$_SESSION['compr2']['departades']=$_SESSION['compr2']['modrequisi'][$_REQUEST['indicerequ']]['descripcion'];
			$_SESSION['compr2']['fecharequi']=$_SESSION['compr2']['modrequisi'][$_REQUEST['indicerequ']]['fecha_requisicion'];
			$_SESSION['compr2']['razonsolco']=$_SESSION['compr2']['modrequisi'][$_REQUEST['indicerequ']]['razon_solicitud'];
			$_SESSION['compr2']['requisicio']=$_SESSION['compr2']['modrequisi'][$_REQUEST['indicerequ']]['requisicion_id'];
			UNSET($_SESSION['compr2']['modrequisi']);
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - LISTA DE PRODUCTOS PARA SOLICITAR - MODIFICAR');
		$accion=ModuloGetURL('app','Compras','user','ValidarModifRequisicionProCompra',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DE LA EMPRESA ACTUAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL USUARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['usuariodes']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['departades']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$var=explode('-',$_SESSION['compr2']['fecharequi']);
		$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">OBSERVACIÓN SOBRE LA SOLICITUD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['razonsolco']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['requisicio']."";
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
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"56%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">UNIDAD</td>";
		$this->salida .= "      <td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">CANTIDAD</td>";
		$this->salida .= "      </tr>";
		$guardados=$this->BuscarRazonCancelarRequiCompra($_SESSION['compra']['empresa'],$_SESSION['compr2']['requisicio']);
		$j=0;
		$_SESSION['compr2']['totalguare']=$ciclo=sizeof($guardados);
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
			$this->salida .= "".$guardados[$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['desunidad']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['contenido_unidad_venta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$this->salida .= "".$guardados[$i]['cantidad']."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"56%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">UNIDAD</td>";
		$this->salida .= "      <td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">CANTIDAD</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr2']['listaprodu']=$this->BuscarListaCotizarCompra($_SESSION['compra']['empresa'],$_SESSION['compr2']['requisicio']);
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['listaprodu']);
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
			$this->salida .= "".$_SESSION['compr2']['listaprodu'][$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['listaprodu'][$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['listaprodu'][$i]['desunidad']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['listaprodu'][$i]['contenido_unidad_venta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<input type=\"text\" name=\"uncantidad".$i."\" value=\"".$_SESSION['compr2']['listaprodu'][$i]['cantidad']."\" maxlength=\"10\" size=\"10\" class=\"input-text\">";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['compr2']['listaprodu']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PRODUCTO RELACIONADO A LA EMPRESA'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','ModifRequisicionCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var1=$this->RetornarBarraModRequiPro();
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
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .= "f=frm;\n";
		$this->salida .= "var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=1;";
		$this->salida .= "var url2 = url+'?bandera='+ban;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('app','Compras','user','ModifRequisicionProCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descricomp\" value=\"".$_REQUEST['descricomp']."\" maxlength=\"50\" size=\"35\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">GRUPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomGrupo\" value=\"".$_REQUEST['NomGrupo']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"grupo\" value=\"".$_REQUEST['grupo']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomClase\" value=\"".$_REQUEST['NomClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"clasePr\" value=\"".$_REQUEST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">SUBCLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomSubClase\" value=\"".$_REQUEST['NomSubClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"subclase\" value=\"".$_REQUEST['subclase']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$ruta='app_modules/Compras/ventanaClasificacion.php';
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
		$accion=ModuloGetURL('app','Compras','user','ModifRequisicionProCompra');
		$this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraModRequiPro()//Barra paginadora de los productos que se relacionan con el proveedor
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
		$accion=ModuloGetURL('app','Compras','user','ModifRequisicionProCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
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

	//Función que permite cancelar o anular una requisición
	function CancelarRequisicionCompra()//Exige una explicación del por que se cancela
	{
		UNSET($_SESSION['compr2']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - CANCELAR UNA REQUISICIÓN');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">REQUISICIONES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
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
		$_SESSION['compr2']['cancelareq']=$this->BuscarModifRequisicionCompra($_SESSION['compra']['empresa']);
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">REQUISICIÓN</td>";
		$this->salida .= "      <td width=\"10%\">FECHA</td>";
		$this->salida .= "      <td width=\"20%\">DEPARTAMENTO</td>";
		$this->salida .= "      <td width=\"40%\">NOMBRE DEL USUARIO</td>";
		$this->salida .= "      <td width=\"10%\">NÚMERO DE PRODUCTOS</td>";
		$this->salida .= "      <td width=\"10%\">DETALLE PRODUCTOS</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['cancelareq']);
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
			$this->salida .= "".$_SESSION['compr2']['cancelareq'][$i]['requisicion_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$var=explode('-',$_SESSION['compr2']['cancelareq'][$i]['fecha_requisicion']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['compr2']['cancelareq'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['compr2']['cancelareq'][$i]['nombre']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['cancelareq'][$i]['cantidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"".ModuloGetURL('app','Compras','user','RazonCancelarRequiCompra',
			array('indicecare'=>$i))."\"><img src=\"".GetThemePath()."/images/producto.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['compr2']['cancelareq']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"6\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚNA REQUISICIÓN'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraCanRequi();
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
		$accion=ModuloGetURL('app','Compras','user','RequisicionesCompra');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"COMPRAS - REQUISICIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','Compras','user','CancelarRequisicionCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
		$this->salida .= "      <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">FECHA (dd/mm/AAAA):";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecharcomp\" value=\"".$_REQUEST['fecharcomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">DEPARTAMENTO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$departamento=$this->BuscarDepartamentosCompra($_SESSION['compra']['empresa']);
		$this->salida .= "      <select name=\"descricomp\" class=\"select\">";
		$this->salida .= "      <option value=\"\">-- SELECCIONE --</option>";
		$ciclo=sizeof($departamento);
		for($i=0;$i<$ciclo;$i++)
		{
			if($departamento[$i]['departamento']==$_REQUEST['descricomp'])
			{
				$this->salida .="<option value=\"".$departamento[$i]['departamento']."\" selected>".$departamento[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$departamento[$i]['departamento']."\">".$departamento[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">NOMBRE DEL USUARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecomp\" value=\"".$_REQUEST['nombrecomp']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Compras','user','CancelarRequisicionCompra');
		$this->salida .= "      <form name=\"forma3\" action=\"$accion\" method=\"post\">";
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

	function RetornarBarraCanRequi()//Barra paginadora de las requisiciones
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
		$accion=ModuloGetURL('app','Compras','user','CancelarRequisicionCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
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

	//Función que muestra la requisición que se va a cancelar
	function RazonCancelarRequiCompra()//Válida que se justifique el motivo para cancelar la requisición
	{
		if($_SESSION['compr2']['usuarioide']==NULL)
		{
			$_SESSION['compr2']['usuarioide']=$_SESSION['compr2']['cancelareq'][$_REQUEST['indicecare']]['usuario_id'];
			$_SESSION['compr2']['usuariodes']=$_SESSION['compr2']['cancelareq'][$_REQUEST['indicecare']]['nombre'];
			$_SESSION['compr2']['departaide']=$_SESSION['compr2']['cancelareq'][$_REQUEST['indicecare']]['departamento'];
			$_SESSION['compr2']['departades']=$_SESSION['compr2']['cancelareq'][$_REQUEST['indicecare']]['descripcion'];
			$_SESSION['compr2']['fecharequi']=$_SESSION['compr2']['cancelareq'][$_REQUEST['indicecare']]['fecha_requisicion'];
			$_SESSION['compr2']['razonsolco']=$_SESSION['compr2']['cancelareq'][$_REQUEST['indicecare']]['razon_solicitud'];
			$_SESSION['compr2']['requisicio']=$_SESSION['compr2']['cancelareq'][$_REQUEST['indicecare']]['requisicion_id'];
			UNSET($_SESSION['compr2']['cancelareq']);
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - CANCELAR UNA REQUISICIÓN - DETALLE');
		$accion=ModuloGetURL('app','Compras','user','ValidarRazonCancelarRequiCompra',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DE LA EMPRESA ACTUAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL USUARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['usuariodes']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['departades']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$var=explode('-',$_SESSION['compr2']['fecharequi']);
		$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">OBSERVACIÓN SOBRE LA SOLICITUD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['razonsolco']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['requisicio']."";
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
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("justifican")."\" width=\"30%\">RAZÓN PARA ANULAR LA REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      <textarea class=\"input-text\" name=\"justifican\" cols=\"60\" rows=\"4\">".$_POST['justifican']."</textarea>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"56%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">UNIDAD</td>";
		$this->salida .= "      <td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">CANTIDAD</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr2']['listaprdca']=$this->BuscarRazonCancelarRequiCompra($_SESSION['compra']['empresa'],$_SESSION['compr2']['requisicio']);
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['listaprdca']);
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
			$this->salida .= "".$_SESSION['compr2']['listaprdca'][$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['listaprdca'][$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['listaprdca'][$i]['desunidad']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['listaprdca'][$i]['contenido_unidad_venta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$this->salida .= "".$_SESSION['compr2']['listaprdca'][$i]['cantidad']."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','CancelarRequisicionCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite consultar todas las requisiciones
	function ConsultarRequisicionCompra()//Permite ver el estado y el detalle de cada requisición
	{
		UNSET($_SESSION['compr2']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - CONSULTAR REQUISICIÓN(ES)');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">REQUISICIONES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
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
		$_SESSION['compr2']['consultare']=$this->BuscarConsultarRequisicionCompra($_SESSION['compra']['empresa']);
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">REQUISICIÓN</td>";
		$this->salida .= "      <td width=\"8%\" >FECHA</td>";
		$this->salida .= "      <td width=\"20%\">DEPARTAMENTO</td>";
		$this->salida .= "      <td width=\"36%\">NOMBRE DEL USUARIO</td>";
		$this->salida .= "      <td width=\"8%\" >NÚMERO DE PRODUCTOS</td>";
		$this->salida .= "      <td width=\"8%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"10%\">DETALLE PRODUCTOS</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['consultare']);
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
			$this->salida .= "".$_SESSION['compr2']['consultare'][$i]['requisicion_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$var=explode('-',$_SESSION['compr2']['consultare'][$i]['fecha_requisicion']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['compr2']['consultare'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['compr2']['consultare'][$i]['nombre']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['consultare'][$i]['cantidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['compr2']['consultare'][$i]['estado']==0)
			{
				$this->salida .= "ASIGNADA";
			}
			else if($_SESSION['compr2']['consultare'][$i]['estado']==1)
			{
				$this->salida .= "PENDIENTE";
			}
			else if($_SESSION['compr2']['consultare'][$i]['estado']==2)
			{
				$this->salida .= "ANULADA";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"".ModuloGetURL('app','Compras','user','ConsultarRequisicionProCompra',
			array('indicecons'=>$i))."\"><img src=\"".GetThemePath()."/images/producto_consultar.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['compr2']['consultare']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"7\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚNA REQUISICIÓN'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraConsulRequi();
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
		$accion=ModuloGetURL('app','Compras','user','RequisicionesCompra');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"COMPRAS - REQUISICIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','Compras','user','ConsultarRequisicionCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
		$this->salida .= "      <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">FECHA (dd/mm/AAAA):";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecharcomp\" value=\"".$_REQUEST['fecharcomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">DEPARTAMENTO";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$departamento=$this->BuscarDepartamentosCompra($_SESSION['compra']['empresa']);
		$this->salida .= "      <select name=\"descricomp\" class=\"select\">";
		$this->salida .= "      <option value=\"\">-- SELECCIONE --</option>";
		$ciclo=sizeof($departamento);
		for($i=0;$i<$ciclo;$i++)
		{
			if($departamento[$i]['departamento']==$_REQUEST['descricomp'])
			{
				$this->salida .="<option value=\"".$departamento[$i]['departamento']."\" selected>".$departamento[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$departamento[$i]['departamento']."\">".$departamento[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">NOMBRE DEL USUARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecomp\" value=\"".$_REQUEST['nombrecomp']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Compras','user','ConsultarRequisicionCompra');
		$this->salida .= "      <form name=\"forma3\" action=\"$accion\" method=\"post\">";
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

	function RetornarBarraConsulRequi()//Barra paginadora de las requisiciones
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
		$accion=ModuloGetURL('app','Compras','user','ConsultarRequisicionCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
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

	//Función que muestra el detalle de los productos de la requisición
	function ConsultarRequisicionProCompra()//Vuelve a la función desde donde fue llamada
	{
		if($_SESSION['compr2']['usuarioide']==NULL)
		{
			$_SESSION['compr2']['usuarioide']=$_SESSION['compr2']['consultare'][$_REQUEST['indicecons']]['usuario_id'];
			$_SESSION['compr2']['usuariodes']=$_SESSION['compr2']['consultare'][$_REQUEST['indicecons']]['nombre'];
			$_SESSION['compr2']['departaide']=$_SESSION['compr2']['consultare'][$_REQUEST['indicecons']]['departamento'];
			$_SESSION['compr2']['departades']=$_SESSION['compr2']['consultare'][$_REQUEST['indicecons']]['descripcion'];
			$_SESSION['compr2']['fecharequi']=$_SESSION['compr2']['consultare'][$_REQUEST['indicecons']]['fecha_requisicion'];
			$_SESSION['compr2']['razonsolco']=$_SESSION['compr2']['consultare'][$_REQUEST['indicecons']]['razon_solicitud'];
			$_SESSION['compr2']['requisicio']=$_SESSION['compr2']['consultare'][$_REQUEST['indicecons']]['requisicion_id'];
			$_SESSION['compr2']['observacio']=$_SESSION['compr2']['consultare'][$_REQUEST['indicecons']]['observacion'];
			$_SESSION['compr2']['estadorequ']=$_SESSION['compr2']['consultare'][$_REQUEST['indicecons']]['estado'];
			UNSET($_SESSION['compr2']['consultare']);
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - DETALLE DE PRODUCTOS - REQUISICIONES');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DE LA EMPRESA ACTUAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL USUARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['usuariodes']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['departades']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$var=explode('-',$_SESSION['compr2']['fecharequi']);
		$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">OBSERVACIÓN SOBRE LA SOLICITUD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['razonsolco']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">RAZÓN PARA ANULAR LA REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['observacio']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">ESTADO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		if($_SESSION['compr2']['estadorequ']==0)
		{
			$this->salida .= "ASIGNADA";
		}
		else if($_SESSION['compr2']['estadorequ']==1)
		{
			$this->salida .= "PENDIENTE";
		}
		else if($_SESSION['compr2']['estadorequ']==2)
		{
			$this->salida .= "ANULADA";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE REQUISICIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['requisicio']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"56%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">UNIDAD</td>";
		$this->salida .= "      <td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">CANTIDAD</td>";
		$this->salida .= "      </tr>";
		$productos=$this->BuscarRazonCancelarRequiCompra($_SESSION['compra']['empresa'],$_SESSION['compr2']['requisicio']);
		$j=0;
		$ciclo=sizeof($productos);
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
			$this->salida .= "".$productos[$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$productos[$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$productos[$i]['desunidad']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$productos[$i]['contenido_unidad_venta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$this->salida .= "".$productos[$i]['cantidad']."";
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
		$accion=ModuloGetURL('app','Compras','user','ConsultarRequisicionCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite controlar las solicitudes de cotizaciones
	function CotizacionesCompra()//Válida las solicitudes de cotizaciones
	{
		UNSET($_SESSION['compr2']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - COTIZACIONES');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "      <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      MENÚ";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','CrearCotizacionCompra') ."\">CREAR UNA SOLICITUD DE COTIZACIÓN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','ModifCotizacionCompra') ."\">MODIFICAR UNA SOLICITUD DE COTIZACIÓN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','CancelarCotizacionCompra') ."\">CANCELAR UNA SOLICITUD DE COTIZACIÓN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','ConsultarCotizacionCompra') ."\">CONSULTAR SOLICITUD(ES) DE COTIZACIÓN(ES)</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Compras','user','RecibirCotizacionCompra') ."\">RECIBIR SOLICITUD(ES) DE COTIZACIÓN(ES)</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Compras','user','PrincipalCompra');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"principal\" value=\"COMPRAS - OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite crear solicitudes de cotizaciones según las requisiciones
	function CrearCotizacionCompra()//Determina los proveedores a los que se le solicitarán cotizaciones
	{
		$this->salida  = ThemeAbrirTabla('COMPRAS - NUEVA(S) SOLICITUD(ES) DE COTIZACIÓN(ES)');
		$accion=ModuloGetURL('app','Compras','user','ValidarCrearCotizacionCompra');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS RELACIONADOS CON LOS PROVEEDORES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"30%\">PROVEEDOR</td>";
		$this->salida .= "      <td width=\"10%\">CÓDIGO</td>";
		$this->salida .= "      <td width=\"40%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"13%\">CANTIDAD</td>";
		$this->salida .= "      <td width=\"7%\" >SOLICITAR</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr2']['bsolicitud']=$this->BuscarCrearCotizacionCompra($_SESSION['compra']['empresa']);
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['bsolicitud']);
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
			$this->salida .= "<tr $color>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td colspan=\"2\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['bsolicitud'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td colspan=\"2\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['bsolicitud'][$i]['tipo_id_tercero']."".' -- '."".$_SESSION['compr2']['bsolicitud'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"50%\" align=\"center\">Días gracia:".' ';
			$this->salida .= "".$_SESSION['compr2']['bsolicitud'][$i]['dias_gracia']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"50%\" align=\"center\">Tiempo entrega:".' ';
			$this->salida .= "".$_SESSION['compr2']['bsolicitud'][$i]['tiempo_entrega']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table>";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['compr2']['bsolicitud'][$i]['codigo_proveedor_id']==$_SESSION['compr2']['bsolicitud'][$k]['codigo_proveedor_id'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"18\" class=\"label_mark\" align=\"center\">";
				$this->salida .= "".$_SESSION['compr2']['bsolicitud'][$k]['codigo_producto']."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "  </table>";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['compr2']['bsolicitud'][$i]['codigo_proveedor_id']==$_SESSION['compr2']['bsolicitud'][$k]['codigo_proveedor_id'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"18\" class=\"label_mark\" align=\"left\">";
				$this->salida .= "".$_SESSION['compr2']['bsolicitud'][$k]['descripcion']."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "  </table>";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['compr2']['bsolicitud'][$i]['codigo_proveedor_id']==$_SESSION['compr2']['bsolicitud'][$k]['codigo_proveedor_id'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"18\" class=\"label_mark\" align=\"right\">";
				$this->salida .= "".$_SESSION['compr2']['bsolicitud'][$k]['cantotal']."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "  </table>";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$k=$i;
			while($_SESSION['compr2']['bsolicitud'][$i]['codigo_proveedor_id']==$_SESSION['compr2']['bsolicitud'][$k]['codigo_proveedor_id'])
			{
				$this->salida .= "  <tr>";
				$this->salida .= "  <td height=\"18\" align=\"center\" >";
				$this->salida .= "  <input type=\"checkbox\" name=\"solicitar".$k."\" value=\"1\">";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$k++;
			}
			$this->salida .= "  </table>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i=$k;
		}
		if(empty($_SESSION['compr2']['bsolicitud']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PRODUCTO CON REQUISICIÓN PENDIENTE'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  <br><fieldset><legend class=\"field\">PRODUCTOS SIN RELACIONES CON LOS PROVEEDORES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"37%\">PROVEEDOR</td>";
		$this->salida .= "      <td width=\"10%\">CÓDIGO</td>";
		$this->salida .= "      <td width=\"40%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"13%\">CANTIDAD</td>";
		$this->salida .= "      </tr>";
		$sinproveed=$this->BuscarCrearCotizacionCompra2($_SESSION['compra']['empresa']);
		$j=0;
		$ciclo=sizeof($sinproveed);
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
			$this->salida .= "'SIN PROVEEDOR QUE VENDA EL PRODUCTO'";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$sinproveed[$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$sinproveed[$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$this->salida .= "".$sinproveed[$i]['cantotal']."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($sinproveed))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"4\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PRODUCTO SIN RELACIONES CON LOS PROVEEDORES'";
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
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR - COTIZACIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','CotizacionesCompra');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"COMPRAS - COTIZACIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite agregar, eliminar o modificar las cotizaciones
	function ModifCotizacionCompra()//Establece los productos para ser cotizados por el proveedor
	{
		UNSET($_SESSION['compr2']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - MODIFICAR UNA SOLICITUD DE COTIZACIÓN');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">COTIZACIONES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">COTIZACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">FECHA</td>";
		$this->salida .= "      <td width=\"42%\">NOMBRE</td>";
		$this->salida .= "      <td colspan=\"2\">DOCUMENTO</td>";//width=\"18%\"
		$this->salida .= "      <td width=\"10%\">NÚMERO DE PRODUCTOS</td>";
		$this->salida .= "      <td width=\"10%\">MODIFICAR PRODUCTOS</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr2']['modcotizac']=$this->BuscarModifCotizacionCompra($_SESSION['compra']['empresa']);
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['modcotizac']);
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
			$this->salida .= "".$_SESSION['compr2']['modcotizac'][$i]['cotizacion_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$var=explode('-',$_SESSION['compr2']['modcotizac'][$i]['fecha_cotizacion']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['compr2']['modcotizac'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"5%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['modcotizac'][$i]['tipo_id_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"13%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['modcotizac'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['modcotizac'][$i]['cantidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"".ModuloGetURL('app','Compras','user','ModifCotizacionProCompra',
			array('indicecoti'=>$i))."\"><img src=\"".GetThemePath()."/images/producto.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['compr2']['modcotizac']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"7\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚNA COTIZACIÓN'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraModCotiz();
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
		$accion=ModuloGetURL('app','Compras','user','CotizacionesCompra');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"COMPRAS - COTIZACIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','Compras','user','ModifCotizacionCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
		$this->salida .= "      <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">FECHA (dd/mm/AAAA):";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecharcomp\" value=\"".$_REQUEST['fecharcomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">NOMBRE DEL PROVEEDOR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecomp\" value=\"".$_REQUEST['nombrecomp']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Compras','user','ModifCotizacionCompra');
		$this->salida .= "      <form name=\"forma3\" action=\"$accion\" method=\"post\">";
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

	function RetornarBarraModCotiz()//Barra paginadora de las requisiciones
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
		$accion=ModuloGetURL('app','Compras','user','ModifCotizacionCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
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

	//Función que permite agregar o eliminar a la lista de productos para cotizar
	function ModifCotizacionProCompra()//Válida y guarda los productos
	{
		if($_SESSION['compr2']['cotizacion']==NULL)
		{
			$_SESSION['compr2']['cotizacion']=$_SESSION['compr2']['modcotizac'][$_REQUEST['indicecoti']]['cotizacion_id'];
			$_SESSION['compr2']['fechacotiz']=$_SESSION['compr2']['modcotizac'][$_REQUEST['indicecoti']]['fecha_cotizacion'];
			$_SESSION['compr2']['nombrecoti']=$_SESSION['compr2']['modcotizac'][$_REQUEST['indicecoti']]['nombre_tercero'];
			$_SESSION['compr2']['tipoidcoti']=$_SESSION['compr2']['modcotizac'][$_REQUEST['indicecoti']]['tipo_id_tercero'];
			$_SESSION['compr2']['tercercoti']=$_SESSION['compr2']['modcotizac'][$_REQUEST['indicecoti']]['tercero_id'];
			$_SESSION['compr2']['cantidcoti']=$_SESSION['compr2']['modcotizac'][$_REQUEST['indicecoti']]['cantidad'];
			UNSET($_SESSION['compr2']['modcotizac']);
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - LISTA DE PRODUCTOS PARA COTIZAR - MODIFICAR');
		$accion=ModuloGetURL('app','Compras','user','ValidarModifCotizacionProCompra',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DE LA EMPRESA ACTUAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['nombrecoti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['tipoidcoti']."".' -- '."".$_SESSION['compr2']['tercercoti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$var=explode('-',$_SESSION['compr2']['fechacotiz']);
		$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE PRODUCTOS:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['cantidcoti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['cotizacion']."";
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
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"56%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">UNIDAD</td>";
		$this->salida .= "      <td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">CANTIDAD</td>";
		$this->salida .= "      </tr>";
		$guardados=$this->BuscarRazonCancelarCotizCompra($_SESSION['compra']['empresa'],$_SESSION['compr2']['cotizacion']);
		$j=0;
		$_SESSION['compr2']['totalguaco']=$ciclo=sizeof($guardados);
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
			$this->salida .= "".$guardados[$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['desunidad']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['contenido_unidad_venta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['cantidad']."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"56%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">UNIDAD</td>";
		$this->salida .= "      <td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">CANTIDAD</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr2']['cotiproduc']=$this->BuscarModifCotizacionProCompra($_SESSION['compra']['empresa'],$_SESSION['compr2']['cotizacion']);
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['cotiproduc']);
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
			$this->salida .= "".$_SESSION['compr2']['cotiproduc'][$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['cotiproduc'][$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['cotiproduc'][$i]['desunidad']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['cotiproduc'][$i]['contenido_unidad_venta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<input type=\"text\" name=\"uncantidad".$i."\" value=\"".$_SESSION['compr2']['cotiproduc'][$i]['cantidad']."\" maxlength=\"10\" size=\"10\" class=\"input-text\">";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['compr2']['cotiproduc']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PRODUCTO RELACIONADO A LA EMPRESA'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','ModifCotizacionCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var1=$this->RetornarBarraModCotizPro();
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
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .= "f=frm;\n";
		$this->salida .= "var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=1;";
		$this->salida .= "var url2 = url+'?bandera='+ban;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('app','Compras','user','ModifCotizacionProCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descricomp\" value=\"".$_REQUEST['descricomp']."\" maxlength=\"50\" size=\"35\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">GRUPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomGrupo\" value=\"".$_REQUEST['NomGrupo']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"grupo\" value=\"".$_REQUEST['grupo']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomClase\" value=\"".$_REQUEST['NomClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"clasePr\" value=\"".$_REQUEST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">SUBCLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomSubClase\" value=\"".$_REQUEST['NomSubClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"subclase\" value=\"".$_REQUEST['subclase']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$ruta='app_modules/Compras/ventanaClasificacion.php';
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
		$accion=ModuloGetURL('app','Compras','user','ModifCotizacionProCompra');
		$this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraModCotizPro()//Barra paginadora de las requisiciones
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
		$accion=ModuloGetURL('app','Compras','user','ModifCotizacionProCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
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

	//Función que permite cancelar una cotización activa
	function CancelarCotizacionCompra()//Válida la anulación de la cotización
	{
		UNSET($_SESSION['compr2']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - CANCELAR UNA SOLICITUD DE COTIZACIÓN');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">COTIZACIONES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
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
		$_SESSION['compr2']['cancelacot']=$this->BuscarModifCotizacionCompra($_SESSION['compra']['empresa']);
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">COTIZACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">FECHA</td>";
		$this->salida .= "      <td width=\"42%\">NOMBRE</td>";
		$this->salida .= "      <td colspan=\"2\">DOCUMENTO</td>";//width=\"18%\"
		$this->salida .= "      <td width=\"10%\">NÚMERO DE PRODUCTOS</td>";
		$this->salida .= "      <td width=\"10%\">MODIFICAR PRODUCTOS</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['cancelacot']);
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
			$this->salida .= "".$_SESSION['compr2']['cancelacot'][$i]['cotizacion_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$var=explode('-',$_SESSION['compr2']['cancelacot'][$i]['fecha_cotizacion']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['compr2']['cancelacot'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"5%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['cancelacot'][$i]['tipo_id_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"13%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['cancelacot'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['cancelacot'][$i]['cantidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"".ModuloGetURL('app','Compras','user','RazonCancelarCotizCompra',
			array('indicecoti'=>$i))."\"><img src=\"".GetThemePath()."/images/producto.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['compr2']['cancelacot']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"7\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚNA COTIZACIÓN'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraCanCotiz();
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
		$accion=ModuloGetURL('app','Compras','user','CotizacionesCompra');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"COMPRAS - COTIZACIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','Compras','user','CancelarCotizacionCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
		$this->salida .= "      <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">FECHA (dd/mm/AAAA):";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecharcomp\" value=\"".$_REQUEST['fecharcomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">NOMBRE DEL PROVEEDOR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecomp\" value=\"".$_REQUEST['nombrecomp']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Compras','user','CancelarCotizacionCompra');
		$this->salida .= "      <form name=\"forma3\" action=\"$accion\" method=\"post\">";
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

	function RetornarBarraCanCotiz()//Barra paginadora de las requisiciones
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
		$accion=ModuloGetURL('app','Compras','user','CancelarCotizacionCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
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

	//Función que muestra la solicitud de cotización que se va a cancelar
	function RazonCancelarCotizCompra()//Válida que se justifique el motivo para cancelar la solicitud de cotización
	{
		if($_SESSION['compr2']['cotizacion']==NULL)
		{
			$_SESSION['compr2']['cotizacion']=$_SESSION['compr2']['cancelacot'][$_REQUEST['indicecoti']]['cotizacion_id'];
			$_SESSION['compr2']['fechacotiz']=$_SESSION['compr2']['cancelacot'][$_REQUEST['indicecoti']]['fecha_cotizacion'];
			$_SESSION['compr2']['nombrecoti']=$_SESSION['compr2']['cancelacot'][$_REQUEST['indicecoti']]['nombre_tercero'];
			$_SESSION['compr2']['tipoidcoti']=$_SESSION['compr2']['cancelacot'][$_REQUEST['indicecoti']]['tipo_id_tercero'];
			$_SESSION['compr2']['tercercoti']=$_SESSION['compr2']['cancelacot'][$_REQUEST['indicecoti']]['tercero_id'];
			$_SESSION['compr2']['cantidcoti']=$_SESSION['compr2']['cancelacot'][$_REQUEST['indicecoti']]['cantidad'];
			UNSET($_SESSION['compr2']['cancelacot']);
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - CANCELAR UNA SOLICITUD DE COTIZACIÓN - DETALLE');
		$accion=ModuloGetURL('app','Compras','user','ValidarRazonCancelarCotizCompra',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DE LA EMPRESA ACTUAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['nombrecoti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['tipoidcoti']."".' -- '."".$_SESSION['compr2']['tercercoti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$var=explode('-',$_SESSION['compr2']['fechacotiz']);
		$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE PRODUCTOS:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['cantidcoti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['cotizacion']."";
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
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"".$this->SetStyle("justifican")."\" width=\"30%\">RAZÓN PARA ANULAR LA COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      <textarea class=\"input-text\" name=\"justifican\" cols=\"60\" rows=\"4\">".$_POST['justifican']."</textarea>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"56%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">UNIDAD</td>";
		$this->salida .= "      <td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">CANTIDAD</td>";
		$this->salida .= "      </tr>";
		$guardados=$this->BuscarRazonCancelarCotizCompra($_SESSION['compra']['empresa'],$_SESSION['compr2']['cotizacion']);
		$j=0;
		$ciclo=sizeof($guardados);
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
			$this->salida .= "".$guardados[$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['desunidad']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['contenido_unidad_venta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['cantidad']."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','CancelarCotizacionCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite consultar todas las solicitudes de cotizaciones
	function ConsultarCotizacionCompra()//Permite ver el estado y el detalle de cada solicitud de cotización
	{
		UNSET($_SESSION['compr2']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - CONSULTAR SOLICITUD(ES) DE COTIZACIÓN(ES)');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">COTIZACIONES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
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
		$_SESSION['compr2']['consultaco']=$this->BuscarConsultarCotizacionCompra($_SESSION['compra']['empresa']);
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">COTIZACIÓN</td>";
		$this->salida .= "      <td width=\"8%\" >FECHA</td>";
		$this->salida .= "      <td width=\"38%\">NOMBRE</td>";
		$this->salida .= "      <td colspan=\"2\">DOCUMENTO</td>";//width=\"18%\"
		$this->salida .= "      <td width=\"8%\" >NÚMERO DE PRODUCTOS</td>";
		$this->salida .= "      <td width=\"8%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"10%\">DETALLE PRODUCTOS</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['consultaco']);
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
			$this->salida .= "".$_SESSION['compr2']['consultaco'][$i]['cotizacion_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$var=explode('-',$_SESSION['compr2']['consultaco'][$i]['fecha_cotizacion']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['compr2']['consultaco'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"5%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['consultaco'][$i]['tipo_id_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"13%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['consultaco'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['consultaco'][$i]['cantidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['compr2']['consultaco'][$i]['estado']==0)
			{
				$this->salida .= "RECIBIDA";
			}
			else if($_SESSION['compr2']['consultaco'][$i]['estado']==1)
			{
				$this->salida .= "PENDIENTE";
			}
			else if($_SESSION['compr2']['consultaco'][$i]['estado']==2)
			{
				$this->salida .= "ANULADA";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"".ModuloGetURL('app','Compras','user','ConsultarCotizacionProCompra',
			array('indicecons'=>$i))."\"><img src=\"".GetThemePath()."/images/producto_consultar.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['compr2']['consultaco']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"8\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚNA SOLICITUD DE COTIZACIÓN'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraConsulCotiz();
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
		$accion=ModuloGetURL('app','Compras','user','CotizacionesCompra');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"COMPRAS - COTIZACIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','Compras','user','ConsultarCotizacionCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
		$this->salida .= "      <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">FECHA (dd/mm/AAAA):";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecharcomp\" value=\"".$_REQUEST['fecharcomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">NOMBRE DEL PROVEEDOR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecomp\" value=\"".$_REQUEST['nombrecomp']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Compras','user','ConsultarCotizacionCompra');
		$this->salida .= "      <form name=\"forma3\" action=\"$accion\" method=\"post\">";
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

	function RetornarBarraConsulCotiz()//Barra paginadora de las requisiciones
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
		$accion=ModuloGetURL('app','Compras','user','ConsultarCotizacionCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
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

	//Función que muestra el detalle de los productos de la solicitud de cotización
	function ConsultarCotizacionProCompra()//Vuelve a la función desde donde fue llamada
	{
		if($_SESSION['compr2']['cotizacion']==NULL)
		{
			$_SESSION['compr2']['cotizacion']=$_SESSION['compr2']['consultaco'][$_REQUEST['indicecons']]['cotizacion_id'];
			$_SESSION['compr2']['fechacotiz']=$_SESSION['compr2']['consultaco'][$_REQUEST['indicecons']]['fecha_cotizacion'];
			$_SESSION['compr2']['nombrecoti']=$_SESSION['compr2']['consultaco'][$_REQUEST['indicecons']]['nombre_tercero'];
			$_SESSION['compr2']['tipoidcoti']=$_SESSION['compr2']['consultaco'][$_REQUEST['indicecons']]['tipo_id_tercero'];
			$_SESSION['compr2']['tercercoti']=$_SESSION['compr2']['consultaco'][$_REQUEST['indicecons']]['tercero_id'];
			$_SESSION['compr2']['cantidcoti']=$_SESSION['compr2']['consultaco'][$_REQUEST['indicecons']]['cantidad'];
			$_SESSION['compr2']['observcoti']=$_SESSION['compr2']['consultaco'][$_REQUEST['indicecons']]['observacion'];
			$_SESSION['compr2']['estadocoti']=$_SESSION['compr2']['consultaco'][$_REQUEST['indicecons']]['estado'];
			$_SESSION['compr2']['fecharecco']=$_SESSION['compr2']['consultaco'][$_REQUEST['indicecons']]['fecha_recibido'];
			UNSET($_SESSION['compr2']['consultaco']);
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - DETALLE DE PRODUCTOS - SOLICITUDES DE COTIZACIONES');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DE LA EMPRESA ACTUAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['nombrecoti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['tipoidcoti']."".' -- '."".$_SESSION['compr2']['tercercoti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$var=explode('-',$_SESSION['compr2']['fechacotiz']);
		$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE PRODUCTOS:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['cantidcoti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">OBSERVACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['observcoti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">ESTADO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		if($_SESSION['compr2']['estadocoti']==0)
		{
			$this->salida .= "RECIBIDA";
		}
		else if($_SESSION['compr2']['estadocoti']==1)
		{
			$this->salida .= "PENDIENTE";
		}
		else if($_SESSION['compr2']['estadocoti']==2)
		{
			$this->salida .= "ANULADA";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA DE RECIBIDA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		if($_SESSION['compr2']['estadocoti']==0)
		{
			$var=explode('-',$_SESSION['compr2']['fecharecco']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		}
		else if($_SESSION['compr2']['estadocoti']==1 OR $_SESSION['compr2']['estadocoti']==2)
		{
			$this->salida .= "SIN REGISTRO";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['cotizacion']."";
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
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"56%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"10%\">UNIDAD</td>";
		$this->salida .= "      <td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">CANTIDAD</td>";
		$this->salida .= "      </tr>";
		$guardados=$this->BuscarRazonCancelarCotizCompra($_SESSION['compra']['empresa'],$_SESSION['compr2']['cotizacion']);
		$j=0;
		$ciclo=sizeof($guardados);
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
			$this->salida .= "".$guardados[$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['desunidad']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['contenido_unidad_venta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$guardados[$i]['cantidad']."";
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
		$accion=ModuloGetURL('app','Compras','user','ConsultarCotizacionCompra');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite ingresar la lista de precios de los productos enviados por el proveedor
	function RecibirCotizacionCompra()//Busca las solicitudes pendientes y permite ingresar los valores cotizados por el proveedor
	{
		UNSET($_SESSION['compr2']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - RECIBIR SOLICITUD(ES) DE COTIZACIÓN(ES)');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">COTIZACIONES PENDIENTES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
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
		$_SESSION['compr2']['recibircot']=$this->BuscarModifCotizacionCompra($_SESSION['compra']['empresa']);
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"10%\">COTIZACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">FECHA</td>";
		$this->salida .= "      <td width=\"42%\">NOMBRE</td>";
		$this->salida .= "      <td colspan=\"2\">DOCUMENTO</td>";//width=\"18%\"
		$this->salida .= "      <td width=\"10%\">NÚMERO DE PRODUCTOS</td>";
		$this->salida .= "      <td width=\"10%\">LISTA DE PRECIOS</td>";
		$this->salida .= "      </tr>";
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['recibircot']);
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
			$this->salida .= "".$_SESSION['compr2']['recibircot'][$i]['cotizacion_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$var=explode('-',$_SESSION['compr2']['recibircot'][$i]['fecha_cotizacion']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['compr2']['recibircot'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"5%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['recibircot'][$i]['tipo_id_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"13%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['recibircot'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['recibircot'][$i]['cantidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"".ModuloGetURL('app','Compras','user','RecibirPreciosCotizacionCompra',
			array('indicereci'=>$i))."\"><img src=\"".GetThemePath()."/images/producto_precio.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['compr2']['recibircot']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"7\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚNA COTIZACIÓN PENDIENTE'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraRecibCotiz();
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
		$accion=ModuloGetURL('app','Compras','user','CotizacionesCompra');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"COMPRAS - COTIZACIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','Compras','user','RecibirCotizacionCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
		$this->salida .= "      <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">FECHA (dd/mm/AAAA):";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecharcomp\" value=\"".$_REQUEST['fecharcomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">NOMBRE DEL PROVEEDOR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecomp\" value=\"".$_REQUEST['nombrecomp']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Compras','user','RecibirCotizacionCompra');
		$this->salida .= "      <form name=\"forma3\" action=\"$accion\" method=\"post\">";
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

	function RetornarBarraRecibCotiz()//Barra paginadora de las solicitudes de cotizaciones recibidas
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
		$accion=ModuloGetURL('app','Compras','user','RecibirCotizacionCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
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

	//Función que permite ingresar los precios enviados, por los proveedores que se les solicito una cotización
	function RecibirPreciosCotizacionCompra()//Válida los precios de los productos cotizados
	{
		if($_SESSION['compr2']['cotizacion']==NULL)
		{
			$_SESSION['compr2']['cotizacion']=$_SESSION['compr2']['recibircot'][$_REQUEST['indicereci']]['cotizacion_id'];
			$_SESSION['compr2']['fechacotiz']=$_SESSION['compr2']['recibircot'][$_REQUEST['indicereci']]['fecha_cotizacion'];
			$_SESSION['compr2']['nombrecoti']=$_SESSION['compr2']['recibircot'][$_REQUEST['indicereci']]['nombre_tercero'];
			$_SESSION['compr2']['tipoidcoti']=$_SESSION['compr2']['recibircot'][$_REQUEST['indicereci']]['tipo_id_tercero'];
			$_SESSION['compr2']['tercercoti']=$_SESSION['compr2']['recibircot'][$_REQUEST['indicereci']]['tercero_id'];
			$_SESSION['compr2']['cantidcoti']=$_SESSION['compr2']['recibircot'][$_REQUEST['indicereci']]['cantidad'];
			$_SESSION['compr2']['diasgracia']=$_SESSION['compr2']['recibircot'][$_REQUEST['indicereci']]['dias_gracia'];
			$_SESSION['compr2']['tiempoentr']=$_SESSION['compr2']['recibircot'][$_REQUEST['indicereci']]['tiempo_entrega'];
			$_SESSION['compr2']['codigoprov']=$_SESSION['compr2']['recibircot'][$_REQUEST['indicereci']]['codigo_proveedor_id'];
			UNSET($_SESSION['compr2']['recibircot']);
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - LISTA DE PRECIOS - DETALLE');
		$accion=ModuloGetURL('app','Compras','user','ValidarRecibirPreciosCotizacionCompra',
		array('Of'=>$_REQUEST['Of'],'paso'=>$_REQUEST['paso'],
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS SOLICITADOS EN LA COTIZACIÓN</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['nombrecoti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['tipoidcoti']."".' -- '."".$_SESSION['compr2']['tercercoti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DÍAS DE GRACIA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['diasgracia']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">TIEMPO DE ENTREGA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['tiempoentr']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$var=explode('-',$_SESSION['compr2']['fechacotiz']);
		$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE PRODUCTOS:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['cantidcoti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['cotizacion']."";
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
		$this->salida .= "      <td width=\"80%\">AYUDA / NÚMERO DE COTIZACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">FECHA LISTA</td>";
		$this->salida .= "      <td width=\"10%\">FECHA VIGENCIA</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "          <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_list_claro\">";
		$this->salida .= "          <tr>";
		$this->salida .= "          <td width=\"50%\" align=\"center\" class=\"label\">FECHAS APLICAN PARA TODOS LOS PRODUCTOS DE LA LISTA";
		$this->salida .= "          </td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          <tr>";
		$this->salida .= "          <td width=\"50%\" align=\"center\"><label class=\"".$this->SetStyle("cotizacprv")."\">NÚMERO DE COTIZACIÓN:</label>";
		$this->salida .= "          ".' '."<input type=\"text\" class=\"input-text\" name=\"cotizacprv\" value=\"".$_POST['cotizacprv']."\" maxlength=\"20\" size=\"12\">";
		$this->salida .= "          </td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          </table>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "          <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_list_claro\">";
		$this->salida .= "          <tr>";
		$this->salida .= "          <td align=\"center\">";
		if($_POST['fechalista']<>NULL)
		{
			$var=explode('-',$_POST['fechalista']);
			$_POST['fechalista']= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		}
		$this->salida .= "          <input type=\"text\" class=\"input-text\" name=\"fechalista\" value=\"".$_POST['fechalista']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "          </td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          <tr>";
		$this->salida .= "          <td align=\"center\" class=\"label\">";
		$this->salida .= "          ".ReturnOpenCalendario('forma1','fechalista','/')."";
		$this->salida .= "          </td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          </table>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "          <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_list_claro\">";
		$this->salida .= "          <tr>";
		$this->salida .= "          <td align=\"center\">";
		if($_POST['fechavigen']<>NULL)
		{
			$var=explode('-',$_POST['fechavigen']);
			$_POST['fechavigen']= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		}
		$this->salida .= "          <input type=\"text\" class=\"input-text\" name=\"fechavigen\" value=\"".$_POST['fechavigen']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "          </td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          <tr>";
		$this->salida .= "          <td align=\"center\" class=\"label\">";
		$this->salida .= "          ".ReturnOpenCalendario('forma1','fechavigen','/')."";
		$this->salida .= "          </td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          </table>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"7%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"38%\">DESCRIPCIÓN / UNIDAD / CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"10%\">CÓDIGO PROV.</td>";
		$this->salida .= "      <td width=\"10%\">CANTIDAD</td>";
		$this->salida .= "      <td width=\"12%\">VALOR UNITARIO</td>";
		$this->salida .= "      <td width=\"10%\">FECHA LISTA</td>";
		$this->salida .= "      <td width=\"10%\">FECHA VIGENCIA</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr2']['preciospro']=$this->BuscarRecibirPreciosCotizacionCompra(
		$_SESSION['compra']['empresa'],$_SESSION['compr2']['cotizacion'],$_SESSION['compr2']['codigoprov']);
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['preciospro']);
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
			$this->salida .= "".$_SESSION['compr2']['preciospro'][$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$_SESSION['compr2']['preciospro'][$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$_SESSION['compr2']['preciospro'][$i]['desunidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$_SESSION['compr2']['preciospro'][$i]['contenido_unidad_venta']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"codiprdprv".$i."\" value=\"".$_SESSION['compr2']['preciospro'][$i]['codigo_producto_proveedor']."\" maxlength=\"20\" size=\"12\">";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$this->salida .= "".$_SESSION['compr2']['preciospro'][$i]['cantidad']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"valor".$i."\" value=\"".$_POST['valor'.$i]."\" maxlength=\"16\" size=\"16\">";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td align=\"center\">";
			if($_POST['fechalista'.$i]<>NULL)
			{
				$var=explode('-',$_POST['fechalista'.$i]);
				$_POST['fechalista'.$i]= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			}
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechalista".$i."\" value=\"".$_POST['fechalista'.$i]."\" maxlength=\"10\" size=\"10\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "      ".ReturnOpenCalendario('forma1','fechalista'.$i,'/')."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td align=\"center\">";
			if($_POST['fechavigen'.$i]<>NULL)
			{
				$var=explode('-',$_POST['fechavigen'.$i]);
				$_POST['fechavigen'.$i]= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			}
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fechavigen".$i."\" value=\"".$_POST['fechavigen'.$i]."\" maxlength=\"10\" size=\"10\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr>";
			$this->salida .= "      <td align=\"center\">";
			$this->salida .= "      ".ReturnOpenCalendario('forma1','fechavigen'.$i,'/')."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      </table>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','RecibirCotizacionCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que se encarga de elaborar el cuadro comparativo de los proveedores
	function ProductosCompararCompra()//Lista los productos y permite el enlace para elaborar el cuadro comparativo
	{
		UNSET($_SESSION['compr2']['cuadrocomp']);
		UNSET($_SESSION['compr2']['datosprodu']);
		UNSET($_SESSION['compr2']['compaprove']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - CUADROS COMPARATIVOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','PrincipalCompra') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DE LA EMPRESA ACTUAL</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"4%\" >TOTAL</td>";
		$this->salida .= "      <td width=\"5%\" >CUADRO</td>";
		$this->salida .= "      <td width=\"8%\" >CANTIDAD</td>";
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"38%\">DESCRIPCIÓN</td>";
		$this->salida .= "      <td width=\"12%\">UNIDAD</td>";
		$this->salida .= "      <td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"9%\" >AUTORIZACIÓN</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr2']['cuadrocomp']=$this->BuscarProductosCompararCompra($_SESSION['compra']['empresa']);
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['cuadrocomp']);
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
			$this->salida .= "".$_SESSION['compr2']['cuadrocomp'][$i]['totalcotiz']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','CuadroComparativoCompra',
			array('indicecuad'=>$i)) ."\"><img src=\"".GetThemePath()."/images/cuadros_comparativos.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['cuadrocomp'][$i]['cantidad_comprar']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['cuadrocomp'][$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['cuadrocomp'][$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['cuadrocomp'][$i]['desunidad']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['cuadrocomp'][$i]['contenido_unidad_venta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['cuadrocomp'][$i]['autorizacion']."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		if(empty($_SESSION['compr2']['cuadrocomp']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"7\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PRODUCTO CON COTIZACIÓN'";
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
		$accion=ModuloGetURL('app','Compras','user','PrincipalCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"principal\" value=\"COMPRAS - OPCIONES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var1=$this->RetornarBarraProdComp();
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
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .= "f=frm;\n";
		$this->salida .= "var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .= "var ban=1;";
		$this->salida .= "var url2 = url+'?bandera='+ban;";
		$this->salida .= "var rems = window.open(url2, nombre, str);\n";
		$this->salida .= "if (rems != null) {\n";
		$this->salida .= "   if (rems.opener == null) {\n";
		$this->salida .= "       rems.opener = self;\n";
		$this->salida .= "   }\n";
		$this->salida .= "}\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>";
		$accion=ModuloGetURL('app','Compras','user','ProductosCompararCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CÓDIGO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">DESCRIPCIÓN:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descricomp\" value=\"".$_REQUEST['descricomp']."\" maxlength=\"50\" size=\"35\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">GRUPO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomGrupo\" value=\"".$_REQUEST['NomGrupo']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"grupo\" value=\"".$_REQUEST['grupo']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">CLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomClase\" value=\"".$_REQUEST['NomClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"clasePr\" value=\"".$_REQUEST['clasePr']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"30%\" class=\"label\">SUBCLASE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"70%\">";
		$this->salida .= "  <input type=\"text\" name=\"NomSubClase\" value=\"".$_REQUEST['NomSubClase']."\" size=\"35\" class=\"input-text\" readonly>";
		$this->salida .= "  <input type=\"hidden\" name=\"subclase\" value=\"".$_REQUEST['subclase']."\" class=\"input-text\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$ruta='app_modules/Compras/ventanaClasificacion.php';
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
		$accion=ModuloGetURL('app','Compras','user','ProductosCompararCompra');
		$this->salida .= "  <form name=\"contrata3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraProdComp()//Barra paginadora de los productos que tienen cotizaciones
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
		$accion=ModuloGetURL('app','Compras','user','ProductosCompararCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp'],
		'NomGrupo'=>$_REQUEST['NomGrupo'],'grupo'=>$_REQUEST['grupo'],
		'NomClase'=>$_REQUEST['NomClase'],'clasePr'=>$_REQUEST['clasePr'],
		'NomSubClase'=>$_REQUEST['NomSubClase'],'subclase'=>$_REQUEST['subclase']));
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

	//Función que hace la comparación de las cotizaciones que tenga el producto
	function CuadroComparativoCompra()//Busca y válida la asignación de las cotizaciones
	{
		if($_SESSION['compr2']['datosprodu']['codigoprod']==NULL)
		{
			$_SESSION['compr2']['datosprodu']['descripcio']=$_SESSION['compr2']['cuadrocomp'][$_REQUEST['indicecuad']]['descripcion'];
			$_SESSION['compr2']['datosprodu']['codigoprod']=$_SESSION['compr2']['cuadrocomp'][$_REQUEST['indicecuad']]['codigo_producto'];
			$_SESSION['compr2']['datosprodu']['descunidad']=$_SESSION['compr2']['cuadrocomp'][$_REQUEST['indicecuad']]['desunidad'];
			$_SESSION['compr2']['datosprodu']['contenidop']=$_SESSION['compr2']['cuadrocomp'][$_REQUEST['indicecuad']]['contenido_unidad_venta'];
			$_SESSION['compr2']['datosprodu']['autorizaci']=$_SESSION['compr2']['cuadrocomp'][$_REQUEST['indicecuad']]['autorizacion'];
			$_SESSION['compr2']['datosprodu']['procentiva']=$_SESSION['compr2']['cuadrocomp'][$_REQUEST['indicecuad']]['porc_iva'];
			$_SESSION['compr2']['datosprodu']['nivelautor']=$_SESSION['compr2']['cuadrocomp'][$_REQUEST['indicecuad']]['nivel_autorizacion_id'];
			$_SESSION['compr2']['datosprodu']['canticompr']=$_SESSION['compr2']['cuadrocomp'][$_REQUEST['indicecuad']]['cantidad_comprar'];
			UNSET($_SESSION['compr2']['cuadrocomp']);
		}
		UNSET($_SESSION['compr2']['datosprove']);
		UNSET($_SESSION['compr2']['compaprove']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - CUADRO COMPARATIVO');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRECIOS DE LOS PROVEEDORES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">DESCRIPCIÓN DEL PRODUCTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['descripcio']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CÓDIGO DEL PRODUCTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['codigoprod']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">UNIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['descunidad']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CONTENIDO PRESENTACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['contenidop']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">AUTORIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['autorizaci']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">PROCENTAJE IVA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['procentiva']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">UNIDADES REQUERIDAS:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\">";
		if($_SESSION['compr2']['datosprodu']['canticompr']<>NULL)
		{
			$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['canticompr']."";
		}
		else
		{
			$this->salida .= "<label class=\"label_error\">NO SE REQUIEREN UNIDADES DE ESTE PRODUCTO<label>";
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
		$this->salida .= "      <td width=\"30%\">NOMBRE</td>";
		$this->salida .= "      <td colspan=\"2\">DOCUMENTO</td>";//width=\"18%\"
		$this->salida .= "      <td width=\"5%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"10%\">No. COTIZ.</td>";
		$this->salida .= "      <td width=\"12%\">VALOR</td>";
		$this->salida .= "      <td width=\"7%\" >FECHA LISTA</td>";
		$this->salida .= "      <td width=\"7%\" >FECHA VIGENCIA</td>";
		$this->salida .= "      <td width=\"6%\" >PEDIDO VIGENTE</td>";
		$this->salida .= "      <td width=\"5%\" >PEDIDO</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr2']['compaprove']=$this->BuscarCuadroComparativoCompra
		($_SESSION['compra']['empresa'],$_SESSION['compr2']['datosprodu']['codigoprod']);
		$valormenor=$indice='';
		$ciclo=sizeof($_SESSION['compr2']['compaprove']);
		$fech=date("Y-m-d");
		for($i=0;$i<$ciclo;$i++)
		{
			if($_SESSION['compr2']['compaprove'][$i]['valor']<=$valormenor OR $valormenor==NULL)
			{
				if($_SESSION['compr2']['compaprove'][$i]['valor']==$valormenor)
				{
					$indice[$i]=1;
				}
				else
				{
					$indice='';
					$indice[$i]=1;
				}
				$valormenor=$_SESSION['compr2']['compaprove'][$i]['valor'];
			}
		}
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
			$this->salida .= "<tr $color>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['compaprove'][$i]['nombre_tercero']."";
			$this->salida .= "</td>";
			$this->salida .= "<td width=\"5%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['compaprove'][$i]['tipo_id_tercero']."";
			$this->salida .= "</td>";
			$this->salida .= "<td width=\"13%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['compaprove'][$i]['tercero_id']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['compr2']['compaprove'][$i]['estado'] == 1)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/activo.gif\" border=\"0\"></a>";
			}
			else if($_SESSION['compr2']['compaprove'][$i]['estado'] == 0)
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/inactivo.gif\" border=\"0\"></a>";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['compr2']['compaprove'][$i]['numero_cotizacion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$valorver=$_SESSION['compr2']['compaprove'][$i]['valor'];
			if(!empty($indice[$i]))
			{
				$this->salida .= "<label class=\"label_mark\">".number_format(($valorver), 2, ',', '.')."</label>";
			}
			else
			{
				$this->salida .= "<label class=\"label\">".number_format(($valorver), 2, ',', '.')."</label>";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$var=explode('-',$_SESSION['compr2']['compaprove'][$i]['fecha_lista']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['compr2']['compaprove'][$i]['fecha_vigencia']<>NULL)
			{
				$var=explode('-',$_SESSION['compr2']['compaprove'][$i]['fecha_vigencia']);
				if($fech <= date("Y-m-d", mktime(1,1,1,$var[1],$var[2],$var[0])))
				{
					$this->salida .= "<label class=\"label\">".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."</label>";
				}
				else
				{
					$this->salida .= "<label class=\"label_error\">".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."</label>";
				}
			}
			else
			{
				$this->salida .= "'NO HAY FECHA'";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['compr2']['compaprove'][$i]['orden'] > 0)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','ConsultarSolicitarPedidoCompra',
				array('indiconsul'=>$i)) ."\"><img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
			}
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['compr2']['compaprove'][$i]['estado'] == 1)
			{
				$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','SolicitarPedidoCompra',
				array('indipedido'=>$i)) ."\"><img src=\"".GetThemePath()."/images/especialidad.png\" border=\"0\"></a>";
			}
			else
			{
				$this->salida .= "<img src=\"".GetThemePath()."/images/especialidadin.png\" border=\"0\">";
			}
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
		$accion=ModuloGetURL('app','Compras','user','ProductosCompararCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LOS PRODUCTOS\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que muestra las ordenes de pedido que tenga un producto, en relación con el proveedor
	function ConsultarSolicitarPedidoCompra()//Consulta todos los pedidos pendientes
	{
		$this->salida  = ThemeAbrirTabla('COMPRAS - ORDENES PENDIENTES');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRECIOS DE LOS PROVEEDORES</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">DESCRIPCIÓN DEL PRODUCTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['descripcio']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CÓDIGO DEL PRODUCTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['codigoprod']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">UNIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['descunidad']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CONTENIDO PRESENTACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['contenidop']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">AUTORIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['autorizaci']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" colspan=\"3\">";
		$this->salida .= "      ".$_SESSION['compr2']['compaprove'][$_REQUEST['indiconsul']]['nombre_tercero']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" colspan=\"3\">";
		$this->salida .= "      ".$_SESSION['compr2']['compaprove'][$_REQUEST['indiconsul']]['tipo_id_tercero']."".' -- '."".$_SESSION['compr2']['compaprove'][$_REQUEST['indiconsul']]['tercero_id']."";
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
		$this->salida .= "      <td width=\"15%\">No. ORDEN</td>";
		$this->salida .= "      <td width=\"10%\">FECHA ORDEN</td>";
		$this->salida .= "      <td width=\"10%\">No. UNIDADES</td>";
		$this->salida .= "      <td width=\"15%\">VALOR $</td>";
		$this->salida .= "      <td width=\"20%\">TOTAL NETO $</td>";
		$this->salida .= "      <td width=\"10%\">IVA %</td>";
		$this->salida .= "      <td width=\"20%\">TOTAL $</td>";
		$this->salida .= "      </tr>";
		$pedidos=$this->BuscarConsultarSolicitarPedidoCompra($_SESSION['compra']['empresa'],$_SESSION['compr2']['datosprodu']['codigoprod'],$_SESSION['compr2']['compaprove'][$_REQUEST['indiconsul']]['codigo_proveedor_id']);
		$ciclo=sizeof($pedidos);
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
			$this->salida .= "<tr $color>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$pedidos[$i]['orden_pedido_id']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$var=explode('-',$pedidos[$i]['fecha_orden']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$pedidos[$i]['numero_unidades']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$valorver=$pedidos[$i]['valor'];
			$this->salida .= "".number_format(($valorver), 2, ',', '.')."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$valor1=($pedidos[$i]['numero_unidades']*$pedidos[$i]['valor']);
			$valor2=(($valor1*$pedidos[$i]['porc_iva'])/100);
			$valor2=$valor2+$valor1;
			$valorver=$valor1;
			$this->salida .= "".number_format(($valorver), 2, ',', '.')."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$pedidos[$i]['porc_iva']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$valorver=$valor2;
			$this->salida .= "".number_format(($valorver), 2, ',', '.')."";
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
		$accion=ModuloGetURL('app','Compras','user','CuadroComparativoCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LOS PROVEEDORES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que realiza las ordenes de compra a los proveedores
	function SolicitarPedidoCompra()//Válida los datos a solicitar y a quien solicita
	{
		if($_SESSION['compr2']['datosprove']==NULL)
		{
			$_SESSION['compr2']['datosprove']=$this->BuscarDatosProveedorCompra($_SESSION['compra']['empresa'],
			$_SESSION['compr2']['datosprodu']['codigoprod'],$_SESSION['compr2']['compaprove'][$_REQUEST['indipedido']]['codigo_proveedor_id']);
			if($_SESSION['compr2']['datosprove']['evaluacion']<>NULL)
			{
				$var=$this->BuscarDatosEvaluacionCompra($_SESSION['compr2']['datosprove']['evaluacion']);
				$_SESSION['compr2']['datosprove']['fecha']=$var['fecha_evaluacion'];
				$_SESSION['compr2']['datosprove']['puntaje']=$var['puntaje_evaluacion'];
			}
			else
			{
				$_SESSION['compr2']['datosprove']['fecha']='';
				$_SESSION['compr2']['datosprove']['puntaje']='';
			}
			UNSET($_SESSION['compr2']['compaprove']);
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - ORDEN DE PEDIDO');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL PRODUCTO</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"80%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['descripcio']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CÓDIGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['codigoprod']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">UNIDAD:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['descunidad']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">CONTENIDO PRESENTACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['contenidop']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">AUTORIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['autorizaci']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">PROCENTAJE IVA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['procentiva']."".' %'."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset><br>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN GENERAL DEL PROVEEDOR</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" colspan=\"3\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['nombre_tercero']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" colspan=\"3\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['tipo_id_tercero']."".' -- '."".$_SESSION['compr2']['datosprove']['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">DIRECCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['direccion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">TELÉFONO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['telefono']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">FAX:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['fax']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">E - MAIL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['email']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">CELULAR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['celular']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">BUSCA PERSONA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['busca_persona']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">ÚLTIMA EVALUACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		if($_SESSION['compr2']['datosprove']['fecha']<>NULL)
		{
			$var=explode('-',$_SESSION['compr2']['datosprove']['fecha']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		}
		else
		{
			$this->salida .= "'EL PROVEEDOR NO HA SIDO EVALUADO'";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">PUNTAJE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		if($_SESSION['compr2']['datosprove']['evaluacion']<>NULL)
		{
			$this->salida .= "".$_SESSION['compr2']['datosprove']['evaluacion']."";
		}
		else
		{
			$this->salida .= "'EL PROVEEDOR NO HA SIDO EVALUADO'";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset><br>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN FINANCIERA DEL PROVEEDOR</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">DÍAS DE GRACIA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['dias_gracia']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">DÍAS DE CRÉDITO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['dias_credito']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">TIEMPO DE ENTREGA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['tiempo_entrega']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">CUPO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		if($_SESSION['compr2']['datosprove']['codigo_producto_proveedor']<>NULL)
		{
			$this->salida .= "".$_SESSION['compr2']['datosprove']['cupo']."";
		}
		else
		{
			$this->salida .= "'NO HAY REGISTRO'";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">DESCUENTO POR PAGO DE CONTADO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['descuento_por_contado']."".' %'."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">CÓDIGO DEL PRODUCTO<br>(PARA EL PROVEEDOR):";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		if($_SESSION['compr2']['datosprove']['codigo_producto_proveedor']<>NULL)
		{
			$this->salida .= "".$_SESSION['compr2']['datosprove']['codigo_producto_proveedor']."";
		}
		else
		{
			$this->salida .= "'NO HAY REGISTRO'";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset><br>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">ORDEN DE PEDIDO</legend>";
		if($_SESSION['compr2']['datosprove']['orden']>0)
		{
			if($this->frmError["MensajeError"]==NULL)
			{
				$this->frmError["MensajeError"]="NOTA: EL PROVEEDOR TIENE ".$_SESSION['compr2']['datosprove']['orden']."
				ORDEN(ES) DE<br>PEDIDO(S) VIGENTE(S) PARA ESTE PRODUCTO";
			}
			else
			{
				$this->frmError["MensajeError"].="<br>NOTA: EL PROVEEDOR TIENE ".$_SESSION['compr2']['datosprove']['orden']."
				ORDEN(ES) DE<br>PEDIDO(S) VIGENTE(S) PARA ESTE PRODUCTO";
			}
			$this->uno = 1;
		}
		if($this->uno == 1)
		{
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		$this->salida .= "  <SCRIPT>";
		$this->salida .= "  function calcularTotalCosto(frm,valor,iva)";
		$this->salida .= "  {";
		$this->salida .= "      resultado=(frm.numUnidades.value*valor);";
		$this->salida .= "      frm.TotalNeto.value=resultado;";
		$this->salida .= "      resultado1=((resultado*iva)/100);";
		$this->salida .= "      resultado=(resultado+resultado1);";
		$this->salida .= "      frm.Total.value=resultado;";
		$this->salida .= "  }";
		$this->salida .= "  </SCRIPT>";
		$accion=ModuloGetURL('app','Compras','user','GuardarSolicitarPedidoCompra');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		if($_SESSION['compr2']['datosprodu']['nivelautor']>0)
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"label\" width=\"30%\">ACTA DE AUTORIZACIÓN:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      <input type=\"text\" name=\"actautoriz\" value=\"".$_POST['actautoriz']."\" maxlength=\"40\" size=\"20\" class=\"input-text\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\" width=\"30%\">NÚEMRO DE COTIZACIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['numero_cotizacion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\" width=\"30%\">FECHA DE LISTA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$var=explode('-',$_SESSION['compr2']['datosprove']['fecha_lista']);
		$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\" width=\"30%\">FECHA DE VIGENCIA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		if($_SESSION['compr2']['datosprove']['fecha_vigencia']<>NULL)
		{
			$var=explode('-',$_SESSION['compr2']['datosprove']['fecha_vigencia']);
			if(date("Y-m-d") <= date("Y-m-d", mktime(1,1,1,$var[1],$var[2],$var[0])))
			{
				$this->salida .= "<label class=\"label\">".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."</label>";
				$swerror=0;
			}
			else
			{
				$this->salida .= "<label class=\"label_error\">".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."</label>";
				$swerror=1;
			}
		}
		else
		{
			$this->salida .= "'NO HAY FECHA'";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\" width=\"30%\">VALOR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "".$_SESSION['compr2']['datosprove']['valor']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\" width=\"30%\">UNIDADES REQUERIDAS:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		if($_SESSION['compr2']['datosprodu']['canticompr']<>NULL)
		{
			$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['canticompr']."";
		}
		else
		{
			$this->salida .= "<label class=\"label_error\">NO SE REQUIEREN UNIDADES DE ESTE PRODUCTO<label>";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\" width=\"30%\">UNIDADES:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      <input type=\"text\" name=\"numUnidades\" value=\"".$_POST['numUnidades']."\" maxlength=\"10\" size=\"20\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\" width=\"30%\">TOTAL NETO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      <input type=\"text\" name=\"TotalNeto\" value=\"".$_POST['TotalNeto']."\" size=\"20\" class=\"input-text\" readonly>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\" width=\"30%\">PROCENTAJE IVA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['procentiva']."".' %'."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\" width=\"30%\">TOTAL CON IVA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      <input type=\"text\" name=\"Total\" value=\"".$_POST['Total']."\" size=\"20\" class=\"input-text\" readonly>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td align=\"center\" colspan=\"2\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"calcular\" value=\"CALCULAR\" onclick=\"calcularTotalCosto(this.form,'".$_SESSION['compr2']['datosprove']['valor']."','".$_SESSION['compr2']['datosprodu']['procentiva']."')\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR ORDEN DE PEDIDO\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','CuadroComparativoCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LOS PROVEEDORES\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que confirma que los datos de la orden de compra sean los correctos
	function GuardarSolicitarPedidoCompra()//Confirma la orden y guarda o retorna a la orden de pedido
	{
		if($_SESSION['compr2']['datosprodu']['nivelautor']>0 AND $_POST['actautoriz']==NULL)
		{
			$this->frmError["MensajeError"]="FALTA EL NÚMERO DEL ACTA DE AUTORIZACIÓN";
			$this->uno = 1;
			$this->SolicitarPedidoCompra();
			return true;
		}
		if($_POST['numUnidades']==NULL OR is_numeric($_POST['numUnidades'])==0)
		{
			$this->frmError["MensajeError"]="EL NÚMERO DE UNIDADES NO ES VÁLIDO";
			$this->uno = 1;
			$this->SolicitarPedidoCompra();
			return true;
		}
		else if(is_numeric($_POST['numUnidades'])==1 AND $_POST['numUnidades'] >= 10000000000)
		{
			$this->frmError["MensajeError"]="EL NÚMERO ES MAYOR A 9.999'999.999";
			$this->uno = 1;
			$this->SolicitarPedidoCompra();
			return true;
		}
		if($_SESSION['compr2']['datosprodu']['canticompr']<$_POST['numUnidades'])
		{
			$this->frmError["MensajeError"]="EL NÚMERO DE UNIDADES ES MAYOR AL SOLICITADO";
			$this->uno = 1;
			$this->SolicitarPedidoCompra();
			return true;
		}
		if(empty($_POST['TotalNeto']) OR empty($_POST['Total']))
		{
			$valor=($_POST['numUnidades']*$_SESSION['compr2']['datosprove']['valor']);
			$_POST['TotalNeto']=$valor;
			$valor1=(($valor*$_SESSION['compr2']['datosprodu']['procentiva'])/100);
			$_POST['Total']=$valor1+$valor;
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - CONFIRMAR ORDEN DE PEDIDO');
		$accion1=ModuloGetURL('app','Compras','user','ValidarSolicitarPedidoCompra');
		$this->salida .= "  <form name=\"formabuscar\" action=\"$accion1\" method=\"post\">";
		$this->salida .= "  <input type=\"hidden\" name=\"numUnidades\" value=\"".$_POST['numUnidades']."\" class=\"input-text\">";
		$this->salida .= "  <input type=\"hidden\" name=\"TotalNeto\" value=\"".$_POST['TotalNeto']."\" class=\"input-text\">";
		$this->salida .= "  <input type=\"hidden\" name=\"Total\" value=\"".$_POST['Total']."\" class=\"input-text\">";
		$this->salida .= "  <input type=\"hidden\" name=\"actautoriz\" value=\"".$_POST['actautoriz']."\" class=\"input-text\">";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset>";
		$this->salida .= "      <table width=\"100%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td colspan=\"2\" class=\"titulo1\" align=\"center\">CONFIRMAR OPERACIÓN";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td colspan=\"2\" class=\"label\" align=\"center\"><br>¿DESEA GUARDAR LA ORDEN DE PEDIDO?<br><br>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"right\" class=\"label\" width=\"40%\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" class=\"label_mark\" width=\"60%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['nombre_tercero']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"right\" class=\"label\" width=\"40%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" class=\"label_mark\" width=\"60%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprove']['tipo_id_tercero']."".' -- '."".$_SESSION['compr2']['datosprove']['tercero_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"right\" class=\"label\" width=\"40%\">DESCRIPCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" class=\"label_mark\" width=\"60%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['descripcio']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"right\" class=\"label\" width=\"40%\">CÓDIGO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" class=\"label_mark\" width=\"60%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['codigoprod']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"right\" class=\"label\" width=\"40%\">FECHA DE ORDEN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" class=\"label_mark\" width=\"60%\">";
		$this->salida .= "      ".date("d/m/Y")."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"right\" class=\"label\" width=\"40%\">NÚMERO DE UNIDADES:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" class=\"label_mark\" width=\"60%\">";
		$valorver=$_POST['numUnidades'];
		$this->salida .= "      ".number_format(($valorver), 2, ',', '.')."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"right\" class=\"label\" width=\"40%\">VALOR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" class=\"label_mark\" width=\"60%\">";
		$valorver=$_SESSION['compr2']['datosprove']['valor'];
		$this->salida .= "      ".number_format(($valorver), 2, ',', '.')."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"right\" class=\"label\" width=\"40%\">TOTAL NETO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" class=\"label_mark\" width=\"60%\">";
		$valorver=$_POST['TotalNeto'];
		$this->salida .= "      ".number_format(($valorver), 2, ',', '.')."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"right\" class=\"label\" width=\"40%\">IVA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" class=\"label_mark\" width=\"60%\">";
		$this->salida .= "      ".$_SESSION['compr2']['datosprodu']['procentiva']."".' %'."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td align=\"right\" class=\"label\" width=\"40%\">TOTAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" class=\"label_mark\" width=\"60%\">";
		$valorver=$_POST['Total'];
		$this->salida .= "      ".number_format(($valorver), 2, ',', '.')."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table width=\"100%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"50%\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Boton1\" value=\"GUARDAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      <td width=\"50%\" align=\"center\">";
		$accion2=ModuloGetURL('app','Compras','user','SolicitarPedidoCompra');
		$this->salida .= "      <form name=\"formabuscar\" action=\"$accion2\" method=\"post\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Boton2\" value=\"CANCELAR\">";
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

	//Función que busca las ordenes de servicios pendientes y las envia al proveedor
	function EnviarOrdenPedidoCompra()//Válida el correcto envio de los datos al proveedor
	{
		UNSET($_SESSION['compr2']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - ENVIAR ORDENES DE PEDIDOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','PrincipalCompra') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">ORDENES DE PEDIDO PENDIENTES POR ENVIAR AL PROVEEDOR</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
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
		$this->salida .= "      <td width=\"10%\">ORDÉN</td>";
		$this->salida .= "      <td width=\"10%\">FECHA</td>";
		$this->salida .= "      <td width=\"42%\">NOMBRE</td>";
		$this->salida .= "      <td colspan=\"2\">DOCUMENTO</td>";//width=\"18%\"
		$this->salida .= "      <td width=\"10%\">NÚMERO DE PRODUCTOS</td>";
		$this->salida .= "      <td width=\"10%\">ENVIAR ORDÉN</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr2']['ordenservi']=$this->BuscarEnviarOrdenPedidoCompra($_SESSION['compra']['empresa']);
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['ordenservi']);
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
			$this->salida .= "".$_SESSION['compr2']['ordenservi'][$i]['orden_pedido_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$var=explode('-',$_SESSION['compr2']['ordenservi'][$i]['fecha_orden']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['compr2']['ordenservi'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"5%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['ordenservi'][$i]['tipo_id_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"13%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['ordenservi'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['ordenservi'][$i]['cantidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"".ModuloGetURL('app','Compras','user','EnviarOrdenPedidoProCompra',
			array('indienvipe'=>$i))."\"><img src=\"".GetThemePath()."/images/producto.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['compr2']['ordenservi']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"7\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚNA ORDÉN DE PEDIDO POR ENVIAR'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraEnviPedi();
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
		$accion=ModuloGetURL('app','Compras','user','PrincipalCompra');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"principal\" value=\"COMPRAS - OPCIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','Compras','user','EnviarOrdenPedidoCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
		$this->salida .= "      <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">ORDÉN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">FECHA (dd/mm/AAAA):";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecharcomp\" value=\"".$_REQUEST['fecharcomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">NOMBRE DEL PROVEEDOR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecomp\" value=\"".$_REQUEST['nombrecomp']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Compras','user','EnviarOrdenPedidoCompra');
		$this->salida .= "      <form name=\"forma3\" action=\"$accion\" method=\"post\">";
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

	function RetornarBarraEnviPedi()//Barra paginadora de las requisiciones
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
		$accion=ModuloGetURL('app','Compras','user','EnviarOrdenPedidoCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
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

	//Función que confirma el envio de la orden de pedido al proveedor
	function EnviarOrdenPedidoProCompra()//Válida el cambio de estado a la orden
	{
		if($_SESSION['compr2']['ordenpedid']==NULL)
		{
			$_SESSION['compr2']['ordenpedid']=$_SESSION['compr2']['ordenservi'][$_REQUEST['indienvipe']]['orden_pedido_id'];
			$_SESSION['compr2']['fechaorden']=$_SESSION['compr2']['ordenservi'][$_REQUEST['indienvipe']]['fecha_orden'];
			$_SESSION['compr2']['nombreorde']=$_SESSION['compr2']['ordenservi'][$_REQUEST['indienvipe']]['nombre_tercero'];
			$_SESSION['compr2']['tipoidorde']=$_SESSION['compr2']['ordenservi'][$_REQUEST['indienvipe']]['tipo_id_tercero'];
			$_SESSION['compr2']['tercerorde']=$_SESSION['compr2']['ordenservi'][$_REQUEST['indienvipe']]['tercero_id'];
			$_SESSION['compr2']['cantidorde']=$_SESSION['compr2']['ordenservi'][$_REQUEST['indienvipe']]['cantidad'];
			$_SESSION['compr2']['direccorde']=$_SESSION['compr2']['ordenservi'][$_REQUEST['indienvipe']]['direccion'];
			$_SESSION['compr2']['telefnorde']=$_SESSION['compr2']['ordenservi'][$_REQUEST['indienvipe']]['telefono'];
			$_SESSION['compr2']['faxtelorde']=$_SESSION['compr2']['ordenservi'][$_REQUEST['indienvipe']]['fax'];
			$_SESSION['compr2']['e-mailorde']=$_SESSION['compr2']['ordenservi'][$_REQUEST['indienvipe']]['email'];
			$_SESSION['compr2']['celulaorde']=$_SESSION['compr2']['ordenservi'][$_REQUEST['indienvipe']]['celular'];
			$_SESSION['compr2']['buscaporde']=$_SESSION['compr2']['ordenservi'][$_REQUEST['indienvipe']]['busca_persona'];
			$_SESSION['compr2']['proveeorde']=$_SESSION['compr2']['ordenservi'][$_REQUEST['indienvipe']]['codigo_proveedor_id'];
			UNSET($_SESSION['compr2']['ordenservi']);
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - CONFIRMAR ORDEN COMO ENVIADA');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DE LA ORDEN DE PEDIDO</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['nombreorde']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['tipoidorde']."".' -- '."".$_SESSION['compr2']['tercerorde']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA ORDÉN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$var=explode('-',$_SESSION['compr2']['fechaorden']);
		$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE PRODUCTOS:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['cantidorde']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE ORDÉN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['ordenpedid']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">DIRECCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['direccorde']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">TELÉFONO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['telefncorde']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">FAX:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['faxtelorde']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">E - MAIL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['e-mailorde']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">CELULAR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['celulaorde']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">BUSCA PERSONA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['buscaporde']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"46%\">DESCRIPCIÓN/ UNIDAD / CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"8%\" >CANTIDAD</td>";
		$this->salida .= "      <td width=\"9%\" >VALOR</td>";
		$this->salida .= "      <td width=\"12%\">VALOR NETO</td>";
		$this->salida .= "      <td width=\"5%\" >IVA %</td>";
		$this->salida .= "      <td width=\"12%\">TOTAL</td>";
		$this->salida .= "      </tr>";
		$guardados=$this->BuscarEnviarOrdenPedidoProCompra($_SESSION['compra']['empresa'],$_SESSION['compr2']['ordenpedid']);
		$j=0;
		$ciclo=sizeof($guardados);
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
			$this->salida .= "".$guardados[$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$guardados[$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$guardados[$i]['desunidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$guardados[$i]['contenido_unidad_venta']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$this->salida .= "".$guardados[$i]['numero_unidades']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$valorver=$guardados[$i]['valor'];
			$this->salida .= "".number_format(($valorver), 2, ',', '.')."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$valor1=($guardados[$i]['numero_unidades']*$guardados[$i]['valor']);
			$valor2=(($valor1*$guardados[$i]['porc_iva'])/100);
			$valor2=$valor2+$valor1;
			$valorver=$valor1;
			$this->salida .= "".number_format(($valorver), 2, ',', '.')."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$guardados[$i]['porc_iva']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$valorver=$valor2;
			$this->salida .= "".number_format(($valorver), 2, ',', '.')."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','ValidarEnviarOrdenPedidoProCompra');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','EnviarOrdenPedidoCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que toma el pedido recibido, según las ordenes de pedido en espera
	function RecibirOrdenPedidoCompra()//Válida los productos pedidos, contra lo recibido por el proveedor
	{
		UNSET($_SESSION['compr2']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - RECIBIR ORDENES DE PEDIDOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','PrincipalCompra') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">ORDENES DE PEDIDO PENDIENTES POR RECIBIR</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
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
		$this->salida .= "      <td width=\"10%\">ORDÉN</td>";
		$this->salida .= "      <td width=\"10%\">FECHA</td>";
		$this->salida .= "      <td width=\"42%\">NOMBRE</td>";
		$this->salida .= "      <td colspan=\"2\">DOCUMENTO</td>";//width=\"18%\"
		$this->salida .= "      <td width=\"10%\">NÚMERO DE PRODUCTOS</td>";
		$this->salida .= "      <td width=\"10%\">RECIBIR ORDÉN</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr2']['ordenrecib']=$this->BuscarRecibirOrdenPedidoCompra($_SESSION['compra']['empresa']);
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['ordenrecib']);
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
			$this->salida .= "".$_SESSION['compr2']['ordenrecib'][$i]['orden_pedido_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$var=explode('-',$_SESSION['compr2']['ordenrecib'][$i]['fecha_orden']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['compr2']['ordenrecib'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"5%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['ordenrecib'][$i]['tipo_id_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"13%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['ordenrecib'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['ordenrecib'][$i]['cantidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"".ModuloGetURL('app','Compras','user','RecibirOrdenPedidoProCompra',
			array('indirecipe'=>$i))."\"><img src=\"".GetThemePath()."/images/producto.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['compr2']['ordenrecib']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"7\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚNA ORDÉN DE PEDIDO POR RECIBIR'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraReciPedi();
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
		$accion=ModuloGetURL('app','Compras','user','PrincipalCompra');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"principal\" value=\"COMPRAS - OPCIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','Compras','user','RecibirOrdenPedidoCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
		$this->salida .= "      <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">ORDÉN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">FECHA (dd/mm/AAAA):";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecharcomp\" value=\"".$_REQUEST['fecharcomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">NOMBRE DEL PROVEEDOR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecomp\" value=\"".$_REQUEST['nombrecomp']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Compras','user','RecibirOrdenPedidoCompra');
		$this->salida .= "      <form name=\"forma3\" action=\"$accion\" method=\"post\">";
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

	function RetornarBarraReciPedi()//Barra paginadora de las requisiciones
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
		$accion=ModuloGetURL('app','Compras','user','RecibirOrdenPedidoCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
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

	//Función que confirma la recepción de los productos según la orden de pedido del proveedor
	function RecibirOrdenPedidoProCompra()//Válida el cambio de estado a la orden, según los productos que llegan
	{
		if($_SESSION['compr2']['ordenpedid']==NULL)
		{
			$_SESSION['compr2']['reordenped']=$_SESSION['compr2']['ordenrecib'][$_REQUEST['indirecipe']]['orden_pedido_id'];
			$_SESSION['compr2']['refechaord']=$_SESSION['compr2']['ordenrecib'][$_REQUEST['indirecipe']]['fecha_orden'];
			$_SESSION['compr2']['renombreor']=$_SESSION['compr2']['ordenrecib'][$_REQUEST['indirecipe']]['nombre_tercero'];
			$_SESSION['compr2']['retipoidor']=$_SESSION['compr2']['ordenrecib'][$_REQUEST['indirecipe']]['tipo_id_tercero'];
			$_SESSION['compr2']['reterceror']=$_SESSION['compr2']['ordenrecib'][$_REQUEST['indirecipe']]['tercero_id'];
			$_SESSION['compr2']['recantidor']=$_SESSION['compr2']['ordenrecib'][$_REQUEST['indirecipe']]['cantidad'];
			$_SESSION['compr2']['redireccor']=$_SESSION['compr2']['ordenrecib'][$_REQUEST['indirecipe']]['direccion'];
			$_SESSION['compr2']['retelefnor']=$_SESSION['compr2']['ordenrecib'][$_REQUEST['indirecipe']]['telefono'];
			$_SESSION['compr2']['refaxtelor']=$_SESSION['compr2']['ordenrecib'][$_REQUEST['indirecipe']]['fax'];
			$_SESSION['compr2']['ree-mailor']=$_SESSION['compr2']['ordenrecib'][$_REQUEST['indirecipe']]['email'];
			$_SESSION['compr2']['recelulaor']=$_SESSION['compr2']['ordenrecib'][$_REQUEST['indirecipe']]['celular'];
			$_SESSION['compr2']['rebuscapor']=$_SESSION['compr2']['ordenrecib'][$_REQUEST['indirecipe']]['busca_persona'];
			$_SESSION['compr2']['reproveeor']=$_SESSION['compr2']['ordenrecib'][$_REQUEST['indirecipe']]['codigo_proveedor_id'];
			UNSET($_SESSION['compr2']['ordenrecib']);
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - ORDEN DE PEDIDO RECIBIDA');
		//$accion=ModuloGetURL('app','Compras','user','ValidarRecibirOrdenPedidoProCompra');
		//$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DE LA ORDEN DE PEDIDO</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['renombreor']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['retipoidor']."".' -- '."".$_SESSION['compr2']['reterceror']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA ORDÉN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$var=explode('-',$_SESSION['compr2']['refechaord']);
		$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE PRODUCTOS:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['recantidor']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE ORDÉN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['reordenped']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">DIRECCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['redireccor']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">TELÉFONO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['retelefncor']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">FAX:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['refaxtelor']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">E - MAIL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['ree-mailor']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">CELULAR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['recelulaor']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">BUSCA PERSONA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['rebuscapor']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"46%\">DESCRIPCIÓN/ UNIDAD / CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"8%\" >CANTIDAD</td>";
		$this->salida .= "      <td width=\"9%\" >VALOR</td>";
		$this->salida .= "      <td width=\"12%\">VALOR NETO</td>";
		$this->salida .= "      <td width=\"5%\" >IVA %</td>";
		$this->salida .= "      <td width=\"12%\">TOTAL</td>";
		$this->salida .= "      </tr>";
		$guardados=$this->BuscarEnviarOrdenPedidoProCompra($_SESSION['compra']['empresa'],$_SESSION['compr2']['reordenped']);
		$j=0;
		$ciclo=sizeof($guardados);
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
			$this->salida .= "".$guardados[$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$guardados[$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$guardados[$i]['desunidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$guardados[$i]['contenido_unidad_venta']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$this->salida .= "".$guardados[$i]['numero_unidades']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$valorver=$guardados[$i]['valor'];
			$this->salida .= "".number_format(($valorver), 2, ',', '.')."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$valor1=($guardados[$i]['numero_unidades']*$guardados[$i]['valor']);
			$valor2=(($valor1*$guardados[$i]['porc_iva'])/100);
			$valor2=$valor2+$valor1;
			$valorver=$valor1;
			$this->salida .= "".number_format(($valorver), 2, ',', '.')."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$guardados[$i]['porc_iva']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$valorver=$valor2;
			$this->salida .= "".number_format(($valorver), 2, ',', '.')."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		//$this->salida .= "  <td width=\"50%\" align=\"center\">";
		//$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		//$this->salida .= "  </td>";
		//$this->salida .= "  </form>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','RecibirOrdenPedidoCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	//Función que permite consultar todas las ordenes de pedido
	function ConsultarOrdenPedidoCompra()//Busca el estado de las ordenes de pedidos
	{
		UNSET($_SESSION['compr2']);
		$this->salida  = ThemeAbrirTabla('COMPRAS - CONSULTAR ORDENES DE PEDIDOS');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Compras','user','PrincipalCompra') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">ORDENES DE PEDIDOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
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
		$this->salida .= "      <td width=\"10%\">ORDÉN</td>";
		$this->salida .= "      <td width=\"8%\" >FECHA</td>";
		$this->salida .= "      <td width=\"40%\">NOMBRE</td>";
		$this->salida .= "      <td colspan=\"2\">DOCUMENTO</td>";//width=\"18%\"
		$this->salida .= "      <td width=\"8%\" >ESTADO</td>";
		$this->salida .= "      <td width=\"10%\">NÚMERO DE PRODUCTOS</td>";
		$this->salida .= "      <td width=\"6%\" >DETALLE ORDÉN</td>";
		$this->salida .= "      </tr>";
		$_SESSION['compr2']['ordenconsu']=$this->BuscarConsultarOrdenPedidoCompra($_SESSION['compra']['empresa']);
		$j=0;
		$ciclo=sizeof($_SESSION['compr2']['ordenconsu']);
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
			$this->salida .= "".$_SESSION['compr2']['ordenconsu'][$i]['orden_pedido_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$var=explode('-',$_SESSION['compr2']['ordenconsu'][$i]['fecha_orden']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td>";
			$this->salida .= "".$_SESSION['compr2']['ordenconsu'][$i]['nombre_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"5%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['ordenconsu'][$i]['tipo_id_tercero']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td width=\"13%\" align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['ordenconsu'][$i]['tercero_id']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			if($_SESSION['compr2']['ordenconsu'][$i]['estado']==0)
			{
				$this->salida .= "RECIBIDA";
			}
			else if($_SESSION['compr2']['ordenconsu'][$i]['estado']==1)
			{
				$this->salida .= "PENDIENTE";
			}
			else if($_SESSION['compr2']['ordenconsu'][$i]['estado']==2)
			{
				$this->salida .= "ANULADA";
			}
			else if($_SESSION['compr2']['ordenconsu'][$i]['estado']==3)
			{
				$this->salida .= "ENVIADA";
			}
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "".$_SESSION['compr2']['ordenconsu'][$i]['cantidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  <td align=\"center\">";
			$this->salida .= "<a href=\"".ModuloGetURL('app','Compras','user','ConsultarOrdenPedidoProCompra',
			array('indiconsre'=>$i))."\"><img src=\"".GetThemePath()."/images/producto_consultar.png\" border=\"0\"></a>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
		}
		if(empty($_SESSION['compr2']['ordenconsu']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"8\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚNA ORDÉN DE PEDIDO'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\"><br>";
		$var1=$this->RetornarBarraConsPedi();
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
		$accion=ModuloGetURL('app','Compras','user','PrincipalCompra');
		$this->salida .= "      <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <td align=\"center\" width=\"100%\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"principal\" value=\"COMPRAS - OPCIONES\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$accion=ModuloGetURL('app','Compras','user','ConsultarOrdenPedidoCompra',array(
		'codigocomp'=>$_REQUEST['codigocomp'],'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
		$this->salida .= "      <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"27%\" class=\"label\">ORDÉN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"73%\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">FECHA (dd/mm/AAAA):";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecharcomp\" value=\"".$_REQUEST['fecharcomp']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\">NOMBRE DEL PROVEEDOR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"nombrecomp\" value=\"".$_REQUEST['nombrecomp']."\" maxlength=\"60\" size=\"40\">";
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
		$accion=ModuloGetURL('app','Compras','user','ConsultarOrdenPedidoCompra');
		$this->salida .= "      <form name=\"forma3\" action=\"$accion\" method=\"post\">";
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

	function RetornarBarraConsPedi()//Barra paginadora de las requisiciones
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
		$accion=ModuloGetURL('app','Compras','user','ConsultarOrdenPedidoCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'nombrecomp'=>$_REQUEST['nombrecomp'],'fecharcomp'=>$_REQUEST['fecharcomp']));
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

	//Función que confirma la recepción de los productos según la orden de pedido del proveedor
	function ConsultarOrdenPedidoProCompra()//Válida el cambio de estado a la orden, según los productos que llegan
	{
		if($_SESSION['compr2']['ordeconsul']==NULL)
		{
			$_SESSION['compr2']['ordeconsul']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['orden_pedido_id'];
			$_SESSION['compr2']['ordenfecha']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['fecha_orden'];
			$_SESSION['compr2']['ordennombr']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['nombre_tercero'];
			$_SESSION['compr2']['ordentipoi']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['tipo_id_tercero'];
			$_SESSION['compr2']['ordenterce']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['tercero_id'];
			$_SESSION['compr2']['ordencanti']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['cantidad'];
			$_SESSION['compr2']['ordendirec']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['direccion'];
			$_SESSION['compr2']['ordentelef']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['telefono'];
			$_SESSION['compr2']['ordenfaxte']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['fax'];
			$_SESSION['compr2']['ordenemail']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['email'];
			$_SESSION['compr2']['ordencelul']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['celular'];
			$_SESSION['compr2']['ordenbusca']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['busca_persona'];
			$_SESSION['compr2']['ordenestad']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['estado'];
			$_SESSION['compr2']['ordenenvio']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['fecha_envio'];
			$_SESSION['compr2']['ordenrecib']=$_SESSION['compr2']['ordenconsu'][$_REQUEST['indiconsre']]['fecha_recibido'];
			UNSET($_SESSION['compr2']['ordenconsu']);
		}
		$this->salida  = ThemeAbrirTabla('COMPRAS - ORDEN DE PEDIDO');
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">PRODUCTOS DE LA ORDEN DE PEDIDO</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compra']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['ordennombr']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['ordentipoi']."".' -- '."".$_SESSION['compr2']['ordenterce']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA ORDÉN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$var=explode('-',$_SESSION['compr2']['ordenfecha']);
		$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA ENVIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		if($_SESSION['compr2']['ordenenvio']<>NULL)
		{
			$var=explode('-',$_SESSION['compr2']['ordenenvio']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		}
		else
		{
			$this->salida .= "LA ORDEN DE PEDIDO, NO HA SIDO ENVIADA AL PROVEEDOR";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA RECIBIDO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		if($_SESSION['compr2']['ordenrecib']<>NULL)
		{
			$var=explode('-',$_SESSION['compr2']['ordenrecib']);
			$this->salida .= "".$var[2]."".'/'."".$var[1]."".'/'."".$var[0]."";
		}
		else
		{
			$this->salida .= "LA ORDEN DE PEDIDO, NO HA SIDO RECIBIDA DESDE EL PROVEEDOR";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE PRODUCTOS:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['ordencanti']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">ESTADO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		if($_SESSION['compr2']['ordenestad']==0)
		{
			$this->salida .= "RECIBIDA";
		}
		else if($_SESSION['compr2']['ordenestad']==1)
		{
			$this->salida .= "PENDIENTE";
		}
		else if($_SESSION['compr2']['ordenestad']==2)
		{
			$this->salida .= "ANULADA";
		}
		else if($_SESSION['compr2']['ordenestad']==3)
		{
			$this->salida .= "ENVIADA";
		}
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NÚMERO DE ORDÉN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"left\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['compr2']['ordeconsul']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">DIRECCIÓN:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['ordendirec']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">TELÉFONO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['ordentelef']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">FAX:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['ordenfaxte']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">E - MAIL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['ordenemail']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">CELULAR:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['ordencelul']."";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"20%\">BUSCA PERSONA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"30%\">";
		$this->salida .= "      ".$_SESSION['compr2']['ordenbusca']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"8%\" >CÓDIGO</td>";
		$this->salida .= "      <td width=\"46%\">DESCRIPCIÓN/ UNIDAD / CONTENIDO PRESENTACIÓN</td>";
		$this->salida .= "      <td width=\"8%\" >CANTIDAD</td>";
		$this->salida .= "      <td width=\"9%\" >VALOR</td>";
		$this->salida .= "      <td width=\"12%\">VALOR NETO</td>";
		$this->salida .= "      <td width=\"5%\" >IVA %</td>";
		$this->salida .= "      <td width=\"12%\">TOTAL</td>";
		$this->salida .= "      </tr>";
		$guardados=$this->BuscarEnviarOrdenPedidoProCompra($_SESSION['compra']['empresa'],$_SESSION['compr2']['ordeconsul']);
		$j=0;
		$ciclo=sizeof($guardados);
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
			$this->salida .= "".$guardados[$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$guardados[$i]['descripcion']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$guardados[$i]['desunidad']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td height=\"18\">";
			$this->salida .= "".$guardados[$i]['contenido_unidad_venta']."";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table>";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$this->salida .= "".$guardados[$i]['numero_unidades']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$valorver=$guardados[$i]['valor'];
			$this->salida .= "".number_format(($valorver), 2, ',', '.')."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$valor1=($guardados[$i]['numero_unidades']*$guardados[$i]['valor']);
			$valor2=(($valor1*$guardados[$i]['porc_iva'])/100);
			$valor2=$valor2+$valor1;
			$valorver=$valor1;
			$this->salida .= "".number_format(($valorver), 2, ',', '.')."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$guardados[$i]['porc_iva']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"right\">";
			$valorver=$valor2;
			$this->salida .= "".number_format(($valorver), 2, ',', '.')."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\" align=\"center\">";
		$accion=ModuloGetURL('app','Compras','user','ConsultarOrdenPedidoCompra');
		$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

}//fin de la clase
?>
