<?php

/**
 * $Id: app_Reportes_Consulta_Externa_userclasses_HTML.php,v 1.2 2005/06/03 18:46:59 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Reportes_Consulta_Externa_userclasses_HTML extends app_Reportes_Consulta_Externa_user
{

	function app_Reportes_Consulta_Externa_user_HTML()
	{
		$this->app_Reportes_Consulta_Externa_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de CARTERA
	function PantallaInicial2()//Llama a todas las opciones posibles
	{
		UNSET($_SESSION['recoex']);
		UNSET($_SESSION['recone']);
		UNSET($_SESSION['recon1']);
		if($this->UsuariosRepconsultaExterna()==false)
		{
			return false;
		}
		return true;
	}

	function PantallaInicial()
	{
		if(empty($_REQUEST['permisoreconex']['empresa_id']) AND empty($_SESSION['recoex']['empresa']))
		{
			$this->frmError["MensajeError"]="SELECCIONE UNA EMPRESA";
			$this->PantallaInicial2();
			return true;
		}
		if(empty($_SESSION['recoex']['empresa']))
		{
			$_SESSION['recoex']['empresa']=$_REQUEST['permisoreconex']['empresa_id'];
			$_SESSION['recoex']['razonso']=$_REQUEST['permisoreconex']['descripcion1'];
			$_SESSION['recoex']['centroutil']=$_REQUEST['permisoreconex']['centro_utilidad'];
			$_SESSION['recoex']['descentro']=$_REQUEST['permisoreconex']['descripcion2'];
		}
		UNSET($_SESSION['recone']);
		UNSET($_SESSION['recon1']);
		$this->salida = ThemeAbrirTabla('REPORTES CONSULTA EXTERNA.');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['recoex']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"100%\" colspan=\"1\">";
		$this->salida .= "      MENÚ REPORTES";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaFormaSeleccion')."\">AGENDAS MÉDICAS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','PantallaInicial2');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

  function FormaSeleccion()
  {
		UNSET($_SESSION['recone']);
		UNSET($_SESSION['recon1']);
		$this->salida = ThemeAbrirTabla('SELECCIONAR DATOS PARA EL REPORTE');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['recoex']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "  		</table><br>";
		if($this->frmError["MensajeError"]<>NULL)//
		{
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "</table><br>";
		}
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaFormaAgendaMedica');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  		<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  		<tr>";
		$this->salida .= "  		<td>";
		$this->salida .= "  		<fieldset><legend class=\"field\">INGRESO DE DATOS</legend>";
		$this->salida .= "    	<table border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"label\" width=\"50%\">DEPARTAMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <select name=\"depto\" class=\"select\">";
  	$this->salida .= "      <option value=\"\" selected>--  TODOS  --</option>";
		$dpto=$this->BuscarDepartamento();
		for($i=0;$i<sizeof($dpto);$i++)
		{
 			if($dpto[$i]['departamento']==$_POST['depto'])
			{
				$this->salida .="<option value=\"".$dpto[$i]['departamento']."".','."".$dpto[$i]['descripcion']."\" selected>".$dpto[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$dpto[$i]['departamento']."".','."".$dpto[$i]['descripcion']."\">".$dpto[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"label\" width=\"50%\">TIPOS CONSULTA/ESPECIALIDADES:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$consul=$this->BuscarTipoConsultas();
		$this->salida .= "      <select name=\"tipoconsul\" class=\"select\">";
		$this->salida .= "      <option value=\"\">--  CUALQUIERA  --</option>";
		for($i=0;$i<sizeof($consul);$i++)
		{
			if($consul[$i]['departamento']==$_POST['tipoconsul'])
			{
				$this->salida .="<option value=\"".$consul[$i]['tipo_consulta_id']."".','."".$consul[$i]['descripcion']."\" selected>".$consul[$i]['descripcion']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$consul[$i]['tipo_consulta_id']."".','."".$consul[$i]['descripcion']."\">".$consul[$i]['descripcion']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"label\" width=\"50%\">PROFESIONAL:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$consul=$this->BuscarProf();
		$this->salida .= "      <select name=\"profesional\" class=\"select\">";
  	$this->salida .= "      <option value=\"\">--  SELECCIONE  --</option>";
		for($i=0;$i<sizeof($consul);$i++)
		{
 			if($consul[$i]['tipo_id_tercero']==$_POST['profesional'])
			{
				$this->salida .="<option value=\"".$consul[$i]['tipo_id_tercero']."".','."".$consul[$i]['tercero_id']."".','."".$consul[$i]['nombre_tercero']."\" selected>".$consul[$i]['nombre_tercero']."</option>";
			}
			else
			{
				$this->salida .="<option value=\"".$consul[$i]['tipo_id_tercero']."".','."".$consul[$i]['tercero_id']."".','."".$consul[$i]['nombre_tercero']."\">".$consul[$i]['nombre_tercero']."</option>";
			}
		}
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"label\">FECHA INICIAL:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".date('d/m/Y')."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      ".ReturnOpenCalendario('forma','feinictra','/')."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"label\">FECHA FINAL:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td class=\"label\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".date('d/m/Y')."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      ".ReturnOpenCalendario('forma','fefinctra','/')."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GENERAR REPORTE\">";
		$this->salida .= "      </td>";
		$this->salida .= "  		</form>";
		$this->salida .= "      </tr>";
		$this->salida .= "  		</fieldset>";
		$this->salida .= "  		</table><br>";
		$this->salida .= "  		</td>";
		$this->salida .= "  		</tr>";
		$this->salida .= "  		</table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','PantallaInicial');
		$this->salida .= "  <form name=\"forma1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "	</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaAgendaMedica()
	{
		$this->salida  = ThemeAbrirTabla('REPORTE AGENDA MÉDICA');
		$reporte= new GetReports();//FALSE
		$mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteConsultaExterna',array('var'=>$_SESSION['recone']),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$funcion=$reporte->GetJavaFunction();
		$this->salida .= "$mostrar";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"right\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td width=\"100%\" align=\"right\">";
		$this->salida .= "<a href=\"". ModuloGetURL('app','Reportes_Consulta_Externa','user','FormaSeleccion') ."\">";
		$this->salida .= "<img src=\"".GetThemePath()."/images/boton.png\" border=\"0\"></a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['recoex']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		if($_SESSION['recone']['codigodepa']<>NULL)
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">DEPARTAMENTO";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['recone']['descridepa']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['recone']['codigotico']<>NULL)
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">TIPO DE CONSULTA";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['recone']['descritico']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['recone']['documentos']<>NULL)
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DEL PROFESIONAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['recone']['nombreprof']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['recone']['fechadesde']<>NULL)
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA INICIAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['recone']['fechadesde']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if($_SESSION['recone']['fechahasta']<>NULL)
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA FINAL";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "      ".$_SESSION['recone']['fechahasta']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		if(!($_SESSION['recone']['codigodepa']<>NULL OR
		$_SESSION['recone']['codigotico']<>NULL OR
		$_SESSION['recone']['documentos']<>NULL OR
		$_SESSION['recone']['fechadesde']<>NULL OR
		$_SESSION['recone']['fechahasta']<>NULL))
		{
			$this->salida .= "      <tr class=modulo_list_claro>";
			$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PARAMETROS DE BUSQUEDA:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td align=\"center\" width=\"70%\">";
			$this->salida .= "NINGUNO";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
		}
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"4%\" >No.</td>";
		$this->salida .= "      <td width=\"7%\" >TIPO ID</td>";
		$this->salida .= "      <td width=\"15%\">IDENTIFICACIÓN</td>";
		$this->salida .= "      <td width=\"65%\">NOMBRE</td>";
		$this->salida .= "      <td width=\"9%\" >ESTADO</td>";
		$this->salida .= "      </tr>";
		$i=0;
		$j=0;
		$ciclo=sizeof($_SESSION['recon1']['datos']);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$color="class=modulo_list_claro";
				$j=1;
			}
			else
			{
				$color="class=modulo_list_oscuro";
				$j=0;
			}
			$this->salida .= "<tr $color>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".($i+1)."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$_SESSION['recon1']['datos'][$i]['tipo_id_tercero']."";//plan_id
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['recon1']['datos'][$i]['tercero_id']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$_SESSION['recon1']['datos'][$i]['nombre_tercero']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			if($_SESSION['recon1']['datos'][$i]['estado']==0)
			{
				$this->salida .= "INACTIVO";
			}
			elseif($_SESSION['recon1']['datos'][$i]['estado']==1)
			{
				$this->salida .= "ACTIVO";
			}
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr $color>";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" $color>";
			$this->salida .= "<tr class=\"modulo_table_list_title\">";
			$this->salida .= "<td width=\"5%\" >Nro.</td>";
			$this->salida .= "<td width=\"10%\">FECHA TURNO</td>";
			$this->salida .= "<td width=\"4%\" >HORA</td>";
			$this->salida .= "<td width=\"10%\">DURACIÓN</td>";
			$this->salida .= "<td width=\"10%\">CONSULTORIO</td>";
			$this->salida .= "<td width=\"20%\">DESCRIPCIÓN</td>";
			$this->salida .= "<td width=\"28%\">NOMBRE PACIENTE</td>";
			$this->salida .= "<td width=\"13%\">IDENT. PACIENTE</td>";
			$this->salida .= "</tr>";
			$datos=$this->BuscarFormaDetalleAgenda($_SESSION['recon1']['datos'][$i]['tipo_id_tercero'],$_SESSION['recon1']['datos'][$i]['tercero_id']);
			$k=0;
			$ciclo1=sizeof($datos);
			while($k<$ciclo1)
			{
				$this->salida .= "<tr>";
				$this->salida .= "<td align=\"center\">";
				$this->salida .= "".($k+1)."";
				$this->salida .= "</td>";
				$this->salida .= "<td align=\"center\">";
				$this->salida .= "".$datos[$k]['fecha_turno']."";//plan_id
				$this->salida .= "</td>";
				$this->salida .= "<td align=\"center\">";
				$this->salida .= "".$datos[$k]['hora']."";
				$this->salida .= "</td>";
				$this->salida .= "<td align=\"center\">";
				$this->salida .= "".$datos[$k]['duracion']."";
				$this->salida .= "</td>";
				$this->salida .= "<td>";
				$this->salida .= "".$datos[$k]['consultorio_id']."";
				$this->salida .= "</td>";
				$this->salida .= "<td>";
				$this->salida .= "".$datos[$k]['descripcion']."";
				$this->salida .= "</td>";
				$this->salida .= "<td align=\"center\">";
				$this->salida .= "".$datos[$k]['nombre']."";
				$this->salida .= "</td>";
				$this->salida .= "<td>";
				$this->salida .= "".$datos[$k]['tipo_id_paciente']."".' - '."".$datos[$k]['paciente_id']."";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$k++;
			}
			$this->salida .= "</table>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		if(empty($_SESSION['recon1']['datos']))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRARON DATOS'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td align=\"center\" colspan=\"5\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR\" onclick=\"javascript:$funcion\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "      </form>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','FormaSeleccion');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

}
?>
