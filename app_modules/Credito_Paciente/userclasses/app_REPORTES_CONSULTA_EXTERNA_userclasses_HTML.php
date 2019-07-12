<?php

class app_REPORTES_CONSULTA_EXTERNA_userclasses_HTML extends app_REPORTES_CONSULTA_EXTERNA_user
{

	function app_REPORTES_CONSULTA_EXTERNA_user_HTML()
	{
	  $this->app_SREPORTES_CONSULTA_EXTERNA_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de CARTERA
	function PantallaInicial2()//Llama a todas las opciones posibles
	{
		UNSET($_SESSION['crepac']);
		UNSET($_SESSION['crpada']);
		if($this->UsuariosRepconsultaExterna()==false)
		{
			return false;
		}
		return true;
	}


	function PantallaInicial()
	{
		if(empty($_REQUEST['permisocredpaci']['empresa_id']) AND empty($_SESSION['crepac']['empresa']))
		{
			$this->frmError["MensajeError"]="SELECCIONE UNA EMPRESA";
			$this->uno=1;
			$this->PantallaInicial2();
			return true;
		}
		if(empty($_SESSION['crepac']['empresa']))
		{
			$_SESSION['crepac']['empresa']=$_REQUEST['permisocredpaci']['empresa_id'];
			$_SESSION['crepac']['razonso']=$_REQUEST['permisocredpaci']['descripcion1'];
			$_SESSION['crepac']['centroutil']=$_REQUEST['permisocredpaci']['centro_utilidad'];
			$_SESSION['crepac']['descentro']=$_REQUEST['permisocredpaci']['descripcion2'];
		}

		$this->salida = ThemeAbrirTabla('REPORTES CONSULTA EXTERNA.');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"40%\">";
		$this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"100%\" colspan=\"1\">";
		$this->salida .= "      MENÚ REPORTES";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','REPORTES_CONSULTA_EXTERNA','user','LlamaFormaSeleccion')."\">AGENDAS MÉDICAS</a>";//LlamaFormaPagare
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','REPORTES_CONSULTA_EXTERNA','user','')."\">REPORTE 2</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\"  align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','REPORTES_CONSULTA_EXTERNA','user','') ."\">REPORTE 3</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\"label\"  align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','REPORTES_CONSULTA_EXTERNA','user','') ."\">REPORTE 4</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$accion=ModuloGetURL('system','Menu');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"MENÚ\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

    function FormaSeleccion()
    {
 		UNSET($_SESSION['crpada']);
		$this->salida = ThemeAbrirTabla('SELECCIONAR DATOS PARA EL REPORTE.');
		$accion=ModuloGetURL('app','REPORTES_CONSULTA_EXTERNA','user','');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"45%\" align=\"center\">";
        $this->salida .= "  <input type=\"hidden\" name=\"pendiente\" value=\"PP\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INGRESO DE DATOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"label\" width=\"50%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <select name=\"TipoDocum\" class=\"select\">";
    	$this->salida .= "      <option value=\"\" selected>--  TODOS  --</option>";
		$dpto=$this->BuscarDepartamento();
		for($i=0;$i<sizeof($dpto);$i++)
		{
    		$this->salida .="<option value=\"".$dpto[$i]['departamento']."\" selected>".$dpto[$i]['descripcion']."</option>";
		}

		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";

		$this->salida .= "      <td class=\"label\" width=\"50%\">TIPOS CONSULTA/ESPECIALIDADES:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$consul=$this->BuscarTipoConsultas();
		$this->salida .= "      <select name=\"TipoDocum\" class=\"select\">";
		$this->salida .= "      <option value=\"1\">--  CUALQUIERA  --</option>";

		for($i=0;$i<sizeof($consul);$i++)
		{
				$this->salida .="<option value=\"".$consul[$i]['tipo_consulta_id']."\" selected>".$consul[$i]['descripcion']."</option>";
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"label\" width=\"50%\">PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$consul=$this->BuscarProf();
		$this->salida .= "      <select name=\"TipoDocum\" class=\"select\">";
    	$this->salida .= "      <option value=\"1\">--  SELECCIONE  --</option>";

		for($i=0;$i<sizeof($consul);$i++)
		{
				$this->salida .="<option value=\"".$consul[$i]['tipo_id_tercero'].",".$consul[$i]['tercero_id']."\" selected>".$consul[$i]['nombre']."</option>";
		}
		//$this->salida .= "      <option value=\"-1\">--  SELECCIONE  --</option>";
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$accion=ModuloGetURL('app','REPORTES _CONSULTA_EXTERNA','user','');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		if($this->uno == 1)
		{
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</table>";
		}
		$this->salida .= ThemeCerrarTabla();
		return true;

    }
}
?>
