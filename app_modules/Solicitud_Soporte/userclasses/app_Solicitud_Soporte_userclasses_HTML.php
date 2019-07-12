<?php

class app_Solicitud_Soporte_userclasses_HTML extends app_Solicitud_Soporte_user
{

	function app_Solicitud_Soporte_user_HTML()
	{
	  $this->app_Solicitud_Soporte_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	function PantallaInicial()
	{
		$this->salida = ThemeAbrirTabla('MANEJO DE SOLICITUDES DE SOPORTE Y MANTENIMIENTO.');
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      IPSoft S.A.";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";

		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"40%\">";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"100%\" colspan=\"1\">";
		$this->salida .= "      MENÚ DE SOLICITUDES";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Solicitud_Soporte','user','LlamaFormaNuevaSolicitud')."\">NUEVA SOLICITUD</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Solicitud_Soporte','user','LlamaFormaSelecciondepartamento')."\">CONSULTAS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\"  align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Solicitud_Soporte','user','') ."\">RESPUESTAS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\"label\"  align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Solicitud_Soporte','user','') ."\">QUE HACER</a>";
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

  function FormaNuevaSolicitud()
  {
  	$this->salida  = ThemeAbrirTabla('ELABORAR NUEVA SOLICITUD');
		$accion=ModuloGetURL('app','Credito_Paciente','user','ValidarDatosInsertar');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		if ($this->uno == 1)
		{
	   $this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\">";
	   $this->salida .= $this->SetStyle("MensajeError");
	   $this->salida .= "      </table><br>";
		}
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"100%\" colspan=\"3\">";
		$this->salida .= "      DETALLE DE LA SOLICITUD";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
  	$this->salida .= "      <tr class=\"modulo_list_claro\">";
	  $this->salida .= "      <td class=\"label\" width=\"30%\">NOMBRE DEL CLIENTE:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"tel\" value=\"".$_POST['cliente']."\" maxlength=\"70\" size=\"60\" >";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
  	$this->salida .= "      <tr class=\"modulo_list_claro\">";
	  $this->salida .= "      <td class=\"label\" width=\"30%\">NOMBRE DEL SOLICITANTE:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"tel\" value=\"".$_POST['solicitante']."\" maxlength=\"70\" size=\"60\" >";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";

		$this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
  	$this->salida .= "      <tr class=\"modulo_list_claro\">";
	  $this->salida .= "      <td class=\"label\" width=\"30%\">MÓDULO/OPCIÓN:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"tel\" value=\"".$_POST['cliente']."\" maxlength=\"70\" size=\"50\" >";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
	  $this->salida .= "      <td class=\"label\" width=\"30%\">RUTA DE ACCESO/OPCIÓN:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      <textarea class=\"input-text\" name=\"observ\" cols=\"90\" rows=\"5\">".$_POST['observ']."</textarea>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
	  $this->salida .= "      <input type=\"hidden\" name=\"tipo_id_paciente\" value=\"".$_SESSION['crpada']['datospacie']['tipo_id_paciente']."\">";
	  $this->salida .= "      <input type=\"hidden\" name=\"paciente_id\" value=\"".$_SESSION['crpada']['datospacie']['paciente_id']."\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
	  $this->salida .= "      <td class=\"label\" width=\"30%\">MENSAJE DE ERROR:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      <textarea class=\"input-text\" name=\"observ\" cols=\"90\" rows=\"5\">".$_POST['observ']."</textarea>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
	  $this->salida .= "      <td class=\"label\" width=\"30%\">OBSERVACIÓN:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      <textarea class=\"input-text\" name=\"observ\" cols=\"90\" rows=\"5\">".$_POST['observ']."</textarea>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";


    $this->salida .= "      <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td class=\"label\" width=\"30%\">TIPO DE IDENTIFICACIÓN DEUDOR:</td>";
    $this->salida .= "      <td colspan=\"2\">";
    //$tipo_id=$this->TraerTipoId();
    $this->salida .= "      <select name=\"id\" class=\"select\">";

    for($i=0;$i<sizeof($tipo_id);$i++)
    {
      $this->salida .="<option value=\"".$tipo_id[$i]['tipo_id_tercero']."\" selected>".$tipo_id[$i]['descripcion']."</option>";
    }
    $this->salida .="<option value=\"".$tipo_id[1]['tipo_id_tercero']."\" selected>".$tipo_id[1]['descripcion']."</option>";
    $this->salida .= "      </select>";
    $this->salida .= "      </td>";
    $this->salida .= "      </tr>";

    $this->salida .= "      <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td class=\"label\" width=\"30%\">NRO DE IDENTIFICACIÓN DEUDOR:</td>";
    $this->salida .= "      <td colspan=\"2\">";
    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"identif\" value=\"".$_POST['identif']."\" maxlength=\"40\" size=\"40\" >";
    $this->salida .= "      </td>";
    $this->salida .= "      </tr>";
    $this->salida .= "      <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td class=\"label\" width=\"30%\">DIRECCIÓN DEUDOR:</td>";
    $this->salida .= "      <td colspan=\"2\">";
    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"dir\" value=\"".$_POST['dir']."\" maxlength=\"40\" size=\"40\" >";
    $this->salida .= "      </td>";
    $this->salida .= "      </tr>";
    $this->salida .= "      <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td class=\"label\" width=\"30%\">TELÉFONO DEUDOR:</td>";
    $this->salida .= "      <td colspan=\"2\">";
    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"tel\" value=\"".$_POST['tel']."\" maxlength=\"40\" size=\"40\" >";
    $this->salida .= "      </td>";
    $this->salida .= "      </tr>";

		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"50%\">";
	//	$this->salida .= "      <label class=\"".$this->SetStyle("fecha")."\" width=\"50%\">VENCIMINETO:</label>";
		$this->salida .= "      </td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecha\" value=\"".$_POST['fecha']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      ".ReturnOpenCalendario('contratacion','fecha','/')."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">FORMA DE PAGO:</td>";
		$this->salida .= "      <td colspan=\"2\">";
//		$pagos=$this->TraerformasPago();
		$this->salida .= "      <select name=\"pagos\" class=\"select\">";
		for($i=0;$i<sizeof($pagos);$i++)
		{
				$this->salida .="<option value=\"".$pagos[$i]['formas_pago_id']."\" selected>".$pagos[$i]['descripcion']."</option>";
		}
		$this->salida .="<option value=\"".$pagos[1]['formas_pago_id']."\" selected>".$pagos[1]['descripcion']."</option>";
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"label\" width=\"30%\">VALOR DEL PAGARÉ EN BLANCO:</td>";
	  $this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "    SI  ";
		$this->salida .= "      <input type=\"radio\" name=\"pagareblanco\" value=0>";
		$this->salida .= "    NO  ";
   	$this->salida .= "      <input type=\"radio\" name=\"pagareblanco\" value=1 checked>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		//$this->salida .= "      <td class=\"".$this->SetStyle("contactoctra")."\" width=\"30%\">OBSERVACIONES:</td>";
    $this->salida .= "      <td class=\"\" width=\"30%\">OBSERVACIONES:</td>";
    $this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      <textarea class=\"input-text\" name=\"observ\" cols=\"90\" rows=\"4\">".$_POST['observ']."</textarea>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
    if ($_SESSION['crpada']['datospacie']['estado'] == '3')
		{
			$reporte= new GetReports();
			$mostrar=$reporte->GetJavaReport('app','Credito_Paciente','pagare',array("tipo_id_paciente"=>$_REQUEST['tipo_id_paciente'],"paciente_id"=>$_REQUEST['paciente_id']),array('rpt_name'=>'pagare','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion=$reporte->GetJavaFunction();
			$this->salida .=$mostrar;
 	  	$this->salida .= "  <td align=\"center\">";
			$this->salida .= "	<td align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Imprimir\" value=\"IMPRIMIR\" onclick=\"javascript:$funcion\"></form></td>";
			$this->salida .= "  <input type=\"hidden\" name=\"tipo_id_paciente\" value=\"".$_SESSION['crpada']['datospacie']['tipo_id_paciente']."\">";
			$this->salida .= "  <input type=\"hidden\" name=\"paciente_id\" value=\"".$_SESSION['crpada']['datospacie']['paciente_id']."\">";

			unset($reporte);
			//$this->salida .= "  <form name=\"pagare\" action=\"$imp\" method=\"post\">";
	  	//$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"imprimir\" value=\"IMPRIMIR\">";
			$this->salida .= "  </td>";
		  $this->salida .= "  </form>";
		}
		echo "<br>var".$_SESSION['crpada']['datospacie']['estado'];
	  $this->salida .= "  <td align=\"center\">";
		$cancel=ModuloGetURL('app','Credito_Paciente','user','LlamaFormaBusqueda');
		$this->salida .= "  <form name=\"pagare\" action=\"$cancel\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </form>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
}
?>
