
<?php
/**
* Modulo de Credito Paciente (PHP).
*
//*
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Credito_Paciente_userclasses_HTML.php
*
//*
**/

class app_Credito_Paciente_userclasses_HTML extends app_Credito_Paciente_user
{
	function app_Credito_Paciente_user_HTML()
	{
		$this->app_Credito_Paciente_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos de CARTERA
	function PrincipalCreditoPaciente2()//Llama a todas las opciones posibles
	{
		UNSET($_SESSION['crepac']);
		UNSET($_SESSION['crpada']);
		if($this->UsuariosCreditoPaciente()==false)
		{
			return false;
		}
		return true;
	}

	//Función principal que da las opciones para tener acceso a los datos del PAGARË
	function PrincipalCreditoPaciente()//Llama a todas las opciones posibles
	{
		if(empty($_REQUEST['permisocredpaci']['empresa_id']) AND empty($_SESSION['crepac']['empresa']))
		{
			$this->frmError["MensajeError"]="SELECCIONE UNA EMPRESA";
			$this->uno=1;
			$this->PrincipalCreditoPaciente2();
			return true;
		}
		if(empty($_SESSION['crepac']['empresa']))
		{
			$_SESSION['crepac']['empresa']=$_REQUEST['permisocredpaci']['empresa_id'];
			$_SESSION['crepac']['razonso']=$_REQUEST['permisocredpaci']['descripcion1'];
			$_SESSION['crepac']['centroutil']=$_REQUEST['permisocredpaci']['centro_utilidad'];
			$_SESSION['crepac']['descentro']=$_REQUEST['permisocredpaci']['descripcion2'];
		}
		
		$this->salida  = ThemeAbrirTabla('CRÉDITO PACIENTE - OPCIONES');
		
		if($this->uno == 1)
		{  
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		
		$this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"40%\">";
		$this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"100%\" colspan=\"1\">";
		$this->salida .= "      MENÚ PAGARÉ";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		//$this->salida .= "      <a href=\"". ModuloGetURL('app','Credito_Paciente','user','') ."\">Nuevo Pagaré</a>";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Credito_Paciente','user','LlamaFormaBusqueda')."\">NUEVO PAGARÉ</a>";//LlamaFormaPagare
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Credito_Paciente','user','LlamaformaMenuAdmin')."\">ADMINISTRACIÓN</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\"  align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Credito_Paciente','user','LlamaFormaConsultas') ."\">CONSULTAS PAGARÉS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\"label\"  align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Credito_Paciente','user','FormaAdmin',array("pagos"=>'P')) ."\">PAGOS/ABONOS</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$accion=ModuloGetURL('app','Credito_Paciente','user','PrincipalCreditoPaciente2');
		$this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
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

	//Datos del Paciente - Toma los datos básico de la base de datos
	function LlamaFormaBusqueda()//Llama a Buscar Datos del paciente
	{
		UNSET($_SESSION['crpada']);
		$this->salida = ThemeAbrirTabla('CRÉDITO PAGARÉ - DATOS DEL PACIENTE');
		$accion=ModuloGetURL('app','Credito_Paciente','user','LlamaFormaPagare');
		//$accion=ModuloGetURL('app','Credito_Paciente','user','FormaAdmin',array("pendiente"=>$_REQUEST['pagare'], "tipo_id"=>$tipo_id, "doc"=>$TipoId));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"45%\" align=\"center\">";
	  $this->salida .= "  <input type=\"hidden\" name=\"pendiente\" value=\"PP\">";	
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">INFORMACIÓN DEL PACIENTE</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"label\" width=\"50%\">TIPO DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <select name=\"TipoDocum\" class=\"select\">";
		$tipo_id=$this->CallMetodoExterno('app','Facturacion','user','tipo_id_paciente',$argumentos);
		$this->BuscarIdPaciente($tipo_id,$TipoId=$_REQUEST['TipoDocum']);
		$this->salida .= "      </select>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"label\">DOCUMENTO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td>";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" size=\"26\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td colspan=\"2\" align=\"center\"><br>";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$accion=ModuloGetURL('app','Credito_Paciente','user','PrincipalCreditoPaciente');
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

	function FormaPagare()//
	{
		$this->salida  = ThemeAbrirTabla('ELABORAR NUEVO PAGARÉ');
		$accion=ModuloGetURL('app','Credito_Paciente','user','ValidarDatosInsertar');		
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		if ($this->uno == 1)
		{
	   $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
	   $this->salida .= $this->SetStyle("MensajeError");
	   $this->salida .= "      </table><br>";		
		}
		$this->salida .= "  <table border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">PAGARÉ NEGOCIABLE Nro:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['carter']['max_id'].""+1; //maximo número de los pagarés
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['crepac']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";

		$this->salida .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"100%\" colspan=\"3\">";
		$this->salida .= "      DETALLE DEL PAGARÉ";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">NOMBRE PACIENTE:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      ".$_SESSION['crpada']['datospacie']['primer_nombre']."".' '."".$_SESSION['crpada']['datospacie']['segundo_nombre']."".' '."".$_SESSION['crpada']['datospacie']['primer_apellido']."".' '."".$_SESSION['crpada']['datospacie']['segundo_apellido']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">IDENTIFICACIÓN:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      ".$_SESSION['crpada']['datospacie']['tipo_id_paciente']."".' - '."".$_SESSION['crpada']['datospacie']['paciente_id']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
	  $this->salida .= "      <input type=\"hidden\" name=\"tipo_id_paciente\" value=\"".$_SESSION['crpada']['datospacie']['tipo_id_paciente']."\">";	
	  $this->salida .= "      <input type=\"hidden\" name=\"paciente_id\" value=\"".$_SESSION['crpada']['datospacie']['paciente_id']."\">";	
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">DIRECCIÓN:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      ".$_SESSION['crpada']['datospacie']['residencia_direccion']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">TELEFONO:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      ".$_SESSION['crpada']['datospacie']['residencia_telefono']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">NRO INGRESO:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      ".$_SESSION['crpada']['datospacie']['ingreso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";		
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">NÚMERO DE CUENTA:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      ".$_SESSION['crpada']['datospacie']['numerodecuenta']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">TOTAL CUENTA:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      ".$_SESSION['crpada']['datospacie']['total_cuenta']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">TOTAL PACIENTE:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      ".$_SESSION['crpada']['datospacie']['valor_total_paciente']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">TOTAL EMPRESA:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      ".$_SESSION['crpada']['datospacie']['valor_total_empresa']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">VALOR:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"valor\" value=\"".$_POST['valor']."\" maxlength=\"20\" size=\"20\" >";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";

		if (!empty($_SESSION['crpada']['datospacie']['garante_id'])) 
		{
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td class=\"label\" width=\"30%\">GARANTE:</td>";
			$this->salida .= "      <td colspan=\"2\">";
			$this->salida .= "".$_SESSION['crpada']['datospacie']['primer_nombre_garante']."".' '."".$_SESSION['crpada']['datospacie']['segundo_nombre_garante']."".' '."".$_SESSION['crpada']['datospacie']['primer_apellido_garante']."".' '."".$_SESSION['crpada']['datospacie']['segundo_apellido_garante']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td class=\"label\" width=\"30%\">IDENTIFICACIÓN GARANTE:</td>";
			$this->salida .= "      <td colspan=\"2\">";
			$this->salida .= "      ".$_SESSION['crpada']['datospacie']['tipo_id_tercero']."".' - '."".$_SESSION['crpada']['datospacie']['garante_id']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";		
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td class=\"label\" width=\"30%\">DIRECCIÓN
			GARANTE:</td>";
			$this->salida .= "      <td colspan=\"2\">";
			$this->salida .= ""     .$_SESSION['crpada']['datospacie']['direccion_garante']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";		
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td class=\"label\" width=\"30%\">TELEFONO GARANTE:</td>";
			$this->salida .= "      <td colspan=\"2\">";
			$this->salida .= "      ".$_SESSION['crpada']['datospacie']['telefono_garante']."";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";		
		}	
		else
		{
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td class=\"label\" width=\"30%\">DEUDOR:</td>";
			$this->salida .= "      <td colspan=\"2\">";
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"deudor\" value=\"".$_POST['deudor']."\" maxlength=\"70\" size=\"70\" >";
			$this->salida .= "      </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td class=\"label\" width=\"30%\">TIPO DE IDENTIFICACIÓN DEUDOR:</td>";
			$this->salida .= "      <td colspan=\"2\">";		
			$tipo_id=$this->TraerTipoId();
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
		}		

		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td width=\"50%\">";
		$this->salida .= "      <label class=\"".$this->SetStyle("fecha")."\" width=\"50%\">VENCIMINETO:</label>";
		$this->salida .= "      </td>";		
		$this->salida .= "      <td colspan=\"2\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"fecha\" value=\"".$_POST['fecha']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "      ".ReturnOpenCalendario('contratacion','fecha','/')."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"30%\">FORMA DE PAGO:</td>";
		$this->salida .= "      <td colspan=\"2\">";
		$pagos=$this->TraerformasPago();
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
		$this->salida .= "      <td class=\"".$this->SetStyle("contactoctra")."\" width=\"30%\">OBSERVACIONES:</td>";
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
	
	//Administración de pagarés
 function FormaAdmin($tmp)
 { 
   $tmpPAGOS = $_REQUEST['pagos'];
 	 if($_SESSION['crepac']['empresa']==NULL)
		{
			$_SESSION['crepac']['empresa']=$_REQUEST['permisocredpaci']['empresa_id'];
			$_SESSION['crepac']['razonso']=$_REQUEST['permisocredpaci']['descripcion1'];
		}
		UNSET($_SESSION['ctrpla']);
		UNSET($_SESSION['ctrpl1']);
		if ($tmp == 'A' OR $_REQUEST['ANULAR']=='A')
		 {		
			 $this->salida  = ThemeAbrirTabla('ANULACIÓN DE PAGARÉS.');
		 }
		else
		  if ($_REQUEST['pagos']=='P')
		    $this->salida  = ThemeAbrirTabla('ADMINISTRACIÓN DE PAGOS Y ABONOS.');
			   else
            $this->salida  = ThemeAbrirTabla('ADMINISTRACIÓN DE CRÉDITO PACIENTES');
						 
		if ($this->uno == 1)
		{
	   $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
	   $this->salida .= $this->SetStyle("MensajeError");
	   $this->salida .= "      </table><br>";		
		}
		$accion=ModuloGetURL('app','Contratacion','user','PreguntaIngresaPlan');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['crepac']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"3%\">No.</td>";
		$this->salida .= "      <td width=\"5%\">No. - PAGARÉ</td>";
		$this->salida .= "      <td width=\"10%\">CUENTA</td>";
		$this->salida .= "      <td width=\"5%\">IDENTIFICACIÓN</td>";
		$this->salida .= "      <td width=\"30%\">NOMBRES</td>";
		$this->salida .= "      <td width=\"10%\">FECHA</td>";
		$this->salida .= "      <td width=\"8%\">FECHA VENCIMIENTO</td>";
		$this->salida .= "      <td width=\"10%\">VALOR</td>";
		$this->salida .= "      <td width=\"4%\">ABONO</td>";
		$this->salida .= "      <td width=\"5%\">SALDO</td>";
		$this->salida .= "      <td width=\"0%\">ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >OPCIÓN</td>";
		$this->salida .= "      </tr>";
		$pagempr=$this->BuscarEmpresasPagares();
		$i=0;
		$j=0;
		$ciclo=sizeof($pagempr);
		//$reporte= new GetReports();	
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
			$this->salida .= "".$pagempr[$i]['pagare_id']."";//plan_id
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['numerodecuenta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['paciente_id']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['primer_nombre']."".' '."".$pagempr[$i]['segundo_nombre']."".' '."".$pagempr[$i]['primer_apellido']."".' '."".$pagempr[$i]['segundo_apellido']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$pagempr[$i]['fecha_elaboracion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['fecha_vencimiento']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$pagempr[$i]['valor']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['abono']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['saldo']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
		  $this->salida .= "".$pagempr[$i]['descripcion']."";
			$this->salida .= "</td>";
  		//$this->salida.=$reporte->GetJavaReport_HC('3965',array());
	   	//$funcion=$reporte->GetJavaFunction();
			$this->salida .= "<td>";
			//$this->salida .= "<a href =\"javascript:$funcion\">OPCIÓN</a>";
			if ($tmp == 'A' OR $_REQUEST['ANULAR']=='A')
			{
			  if (empty($tmp))
						$tmp = $_REQUEST['ANULAR'];
	 	  	$funcion=ModuloGetURL('app','Credito_Paciente','user','LlamaFormaOpcionesPagare2',array("pagare_id"=>$pagempr[$i]['pagare_id'],"ANULAR"=>$tmp));
			}	
			else
		    if ($_REQUEST['pagos']=='P')	      		
    	 	  $funcion=ModuloGetURL('app','Credito_Paciente','user','LlamaFormaOpcionesPagare',array("pagare_id"=>$pagempr[$i]['pagare_id'],"pagos"=>$tmpPAGOS));
        	  else			
    	    	  $funcion=ModuloGetURL('app','Credito_Paciente','user','LlamaFormaOpcionesPagare',array("pagare_id"=>$pagempr[$i]['pagare_id']));
			$this->salida .= "<center><a href =$funcion>VER</a></center>";
			$this->salida .= "</td>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		if(empty($pagempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"9\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PAGARÉ'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
//		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"center\">";
//		$this->salida .= "      <tr>";
//		$this->salida .= "      <td align=\"center\"><br>";
//		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO PLAN\">";
//		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
//		$this->salida .= "      </tr>";
//		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Credito_Paciente','user','PrincipalCreditoPaciente');
		$this->salida .= "  <form name=\"contratacion1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		//$var=$this->RetornarBarraClientes();
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
		$accion=ModuloGetURL('app','Credito_Paciente','user','FormaAdmin',
		array('codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO PAGARÉ:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
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
		$accion=ModuloGetURL('app','Credito_Paciente','user','FormaAdmin');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
 }

 function FormaConsultas()
 {
 		if($_SESSION['crepac']['empresa']==NULL)
		{
			$_SESSION['crepac']['empresa']=$_REQUEST['permisocredpaci']['empresa_id'];
			$_SESSION['crepac']['razonso']=$_REQUEST['permisocredpaci']['descripcion1'];
		}
		UNSET($_SESSION['ctrpla']);
		UNSET($_SESSION['ctrpl1']);
		$this->salida  = ThemeAbrirTabla('CONSULTAS CRÉDITO PACIENTES');
		$accion=ModuloGetURL('app','Credito_Paciente','user','');
		$this->salida .= "  <form name=\"contratacion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['crepac']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"3%\">No.</td>";
		$this->salida .= "      <td width=\"5%\">No. - PAGARÉ</td>";
		$this->salida .= "      <td width=\"10%\">INGRESO</td>";
		$this->salida .= "      <td width=\"10%\">CUENTA</td>";
		$this->salida .= "      <td width=\"5%\">IDENTIFICACIÓN</td>";
		$this->salida .= "      <td width=\"30%\">NOMBRES</td>";
		$this->salida .= "      <td width=\"10%\">FECHA</td>";
		$this->salida .= "      <td width=\"8%\">FECHA VENCIMIENTO</td>";
		$this->salida .= "      <td width=\"10%\">VALOR</td>";
		$this->salida .= "      <td width=\"0%\">ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >OPCIÓN</td>";
		$this->salida .= "      </tr>";
		$pagempr=$this->BuscarEmpresasPagaresConsul();
		$i=0;
		$j=0;
		$ciclo=sizeof($pagempr);
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
			$this->salida .= "".$pagempr[$i]['pagare_id']."";//plan_id
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['ingreso']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['numerodecuenta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['paciente_id']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['primer_nombre']."".' '."".$pagempr[$i]['segundo_nombre']."".' '."".$pagempr[$i]['primer_apellido']."".' '."".$pagempr[$i]['segundo_apellido']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$pagempr[$i]['fecha_elaboracion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['fecha_vencimiento']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$pagempr[$i]['valor']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
		  $this->salida .= "".$pagempr[$i]['descripcion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
 		  $accion=ModuloGetURL('app','Credito_Paciente','user','LlamaFormaOpcionesPagare2',array("pagare_id"=>$pagempr[$i]['pagare_id']));			
			$this->salida .= "<a href =\"$accion\">OPCIÓN</a>";
			$this->salida .= "</td>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		if(empty($pagempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"9\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PAGARÉ'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";

		$this->salida .= "      </form>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Credito_Paciente','user','PrincipalCreditoPaciente');
		$this->salida .= "  <form name=\"contratacion1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		//$var=$this->RetornarBarraClientes();
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
		$accion=ModuloGetURL('app','Credito_Paciente','user','FormaConsultas',
		array('codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO PAGARÉ:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
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
		$accion=ModuloGetURL('app','Credito_Paciente','user','FormaConsultas');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
 }
 
function FormaOpcionesPagare($pag)
 { 
  $datos=$this->TraerDatosPagare($pag);
	$this->salida .= ThemeAbrirTabla('DETALLE DEL PAGARÉ NRO. '.$datos[0][pagare_id].' '.$datos[0][primer_nombre].' '.$datos[0][segundo_nombre].' '.$datos[0][primer_apellido].' '.$datos[0][segundo_apellido].'');
  $actionEditar=ModuloGetURL('app','Credito_Paciente','user','Autorizar', array("pagos"=>$_REQUEST['pagos'],"boton"=>$_REQUEST['boton'],"valor"=>$_REQUEST['valor'],"abono"=>$_REQUEST['abono'],"saldo"=>$_REQUEST['saldo'],"observ"=>$_REQUEST['observ'],"tipo_id_paciente"=>$_REQUEST['tipo_id_paciente'],"paciente_id"=>$_REQUEST['paciente_id'],"pagare_id"=>$_REQUEST['pagare_id']));
  $this->salida .= "     <form name=\"forma\" action=\"$actionEditar\" method=\"post\">";
	$this->salida .= "             <table border=\"0\" width=\"35%\" align=\"center\" class=\"normal_10\">";
	$this->salida .= "                <tr>";
	$this->salida .= "       	 	 	    <td class=\"modulo_table_list_title\" width=\"95%\" colspan=\"3\">";
	$this->salida .= "      				  DATOS DEL PACIENTE";
	$this->salida .= "      				  </td>";
	$this->salida .= "                </tr>";
	$this->salida .= "                <tr><td class=\"label\" width=\"35%\">PACIENTE:</td><td> ".$datos[0][primer_nombre].' '.$datos[0][segundo_nombre].' '.$datos[0][primer_apellido].' '.$datos[0][segundo_apellido]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\">IDENTIFICACION:</td><td> ".$datos[0][tipo_id_paciente].'-'.$datos[0][paciente_id]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\">No. INGRESO: </td><td>".$datos[0][ingreso]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\">CUENTA: </td><td>".$datos[0][numerodecuenta]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\">FECHA ELABORACIÓN:</td><td> ".$datos[0][fecha_elaboracion]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\">VENCIMIENTO:</td><td> ".$datos[0][fecha_vencimiento]."</td></tr>";
	$this->salida .= "                <input type=\"hidden\" name=\"tipo_id_paciente\" value=\"".$datos[0][tipo_id_paciente]."\">";
	$this->salida .= "                <input type=\"hidden\" name=\"paciente_id\" value=\"".$datos[0][paciente_id]."\">";	
	$this->salida .= "                <input type=\"hidden\" name=\"pagos\" value=\"".$_REQUEST['pagos']."\">";	
	$this->salida .= "                <input type=\"hidden\" name=\"pagare_id\" value=\"".$datos[0][pagare_id]."\">";	
	$this->salida .= "             </table>";
	$this->salida .= "             <table border=\"0\" width=\"35%\" align=\"center\" class=\"normal_10\">";
	$this->salida .= "                <tr>";
	$this->salida .= "      	  		  <td class=\"modulo_table_list_title\" width=\"95%\" colspan=\"3\">";
	$this->salida .= "      		  		RESPONSABLE";
	$this->salida .= "      			  	</td>";
	$this->salida .= "                </tr>";
	$this->salida .= "                <tr><td class=\"label\" width=\"35%\">RESPONSABLE: </td><td>".$datos[0][primer_nombre_garante]." ".$datos[0][segundo_nombre_garante]." ".$datos[0][primer_apellido_garante]." ".$datos[0][segundo_apellido_garante]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\" width=\"35%\">IDENTIFICACIÓN: </td><td>".$datos[0][tipo_id_tercero]."-".$datos[0][garante_id]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\" width=\"35%\">DIRECCIÓN:</td><td> ".$datos[0][direccion_garante]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\" width=\"35%\">TELEFONO: </td><td>".$datos[0][telefono_garante]."</td></tr>";
	$this->salida .= "             </table>";
	$this->salida .= "             <table border=\"0\" width=\"35%\" align=\"center\" class=\"normal_10\">";
	$this->salida .= "                <tr>";
	$this->salida .= "      	  		  <td class=\"modulo_table_list_title\" width=\"95%\" colspan=\"3\">";
	$this->salida .= "      		  		VALOR A AUTORIZAR";
	$this->salida .= "      			  	</td>";
	$this->salida .= "                </tr>";
	if ($_REQUEST['pagos']=='P')
	 {
     $this->salida .= "                <tr><td class=\"label\" width=\"35%\">VALOR DEL PAGARÉ: </td><td class=\"label\" width=\"35%\">$".$datos[0][valor]." </td></tr>";
	   $this->salida .= "                <tr><td class=\"label\" width=\"35%\">VALOR ABONO: </td><td><input type=\"text\" maxlength=\"80\" size=\"20\" name=\"abono\" value=\"".$datos[0][abono]."\" class=\"input-text\"></td></tr>";
	   $this->salida .= "                <tr><td class=\"label\" width=\"35%\">SALDO ACTUAL: </td><td class=\"label\" width=\"35%\">$".$datos[0][saldo]."</td></tr>";
     $this->salida .= "                <input type=\"hidden\" name=\"valor\" value=\"".$datos[0][valor]."\">";		
     $this->salida .= "                <input type=\"hidden\" name=\"saldo\" value=\"".$datos[0][saldo]."\">";		
	 }	
	else
	   $this->salida .= "                <tr><td class=\"label\" width=\"35%\">VALOR DEL PAGARÉ: </td><td><input type=\"text\" maxlength=\"80\" size=\"20\" name=\"valor\" value=\"".$datos[0][valor]."\" class=\"input-text\"></td></tr>";
	$this->salida .= "                <tr><td class=\"label\" width=\"35%\">OBSERVACIONES: </td>";
	$this->salida .= "                <td><textarea class=\"input-text\" name=\"observ\" cols=\"40\" rows=\"5\">".$datos[0][observacion]."</textarea></td></tr>";	
	$this->salida .= "             </table><br>";
	if ($_REQUEST['pagos']=='P')
	 { 
     //$actionAct=ModuloGetURL('app','Credito_Paciente','user','ActualizarPago', array("abono"=>$_REQUEST['abono'],"observ"=>$_REQUEST['observ'],"tipo_id_paciente"=>$_REQUEST['tipo_id_paciente'],"paciente_id"=>$_REQUEST['paciente_id'],"pagare_id"=>$_REQUEST['pagare_id']));
	   //$this->salida .= "     <form name=\"forma\" action=\"$actionAct\" method=\"post\">";
	   $this->salida .= "         <center><input class=\"input-submit\" type=\"submit\" name=\"boton\" value=\"GUARDAR\"></center>";
		 //$this->salida .= "     </form>";
	 }	
	else
	   $this->salida .= "         <center><input class=\"input-submit\" type=\"submit\" name=\"boton\" value=\"AUTORIZAR\"></center>";		
	//$this->salida .= "        </form>";
	$this->salida .= ThemeCerrarTabla();
	//$accion=ModuloGetURL('app','Credito_Paciente','user','LlamaformaAdmin');
	$this->salida .= "            <br>";
	//$this->salida .= "            <form name=\"formavolver\" action=\"$accion\" method=\"post\">";
  $this->salida .= "               <center><input class=\"input-submit\" type=\"submit\" name=\"boton\" value=\"VOLVER\"></center>";
  $this->salida .= "        </form>";
	return true;
 }

function FormaOpcionesPagare2($pag)
 { 
  $datos=$this->TraerDatosPagare($pag);
	$this->salida .= ThemeAbrirTabla('DETALLE DEL PAGARÉ NRO. '.$datos[0][pagare_id].' '.$datos[0][primer_nombre].' '.$datos[0][segundo_nombre].' '.$datos[0][primer_apellido].' '.$datos[0][segundo_apellido].'');
  if ($_REQUEST['ANULAR']=='A')
	{
	  $TEMP = $_REQUEST['ANULAR'];
    $actionEditar=ModuloGetURL('app','Credito_Paciente','user','FormaAdmin',array("ANULAR"=>$TEMP));
	}	
  else	
    $actionEditar=ModuloGetURL('app','Credito_Paciente','user','FormaConsultas');
  $this->salida .= "     <form name=\"forma\" action=\"$actionEditar\" method=\"post\">";
	$this->salida .= "             <table border=\"0\" width=\"35%\" align=\"center\" class=\"normal_10\">";
	$this->salida .= "                <tr>";
	$this->salida .= "       	 	 	    <td class=\"modulo_table_list_title\" width=\"95%\" colspan=\"3\">";
	$this->salida .= "      				  DATOS DEL PACIENTE";
	$this->salida .= "      				  </td>";
	$this->salida .= "                </tr>";
	$this->salida .= "                <tr><td class=\"label\" width=\"35%\">PACIENTE:</td><td> ".$datos[0][primer_nombre].' '.$datos[0][segundo_nombre].' '.$datos[0][primer_apellido].' '.$datos[0][segundo_apellido]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\">IDENTIFICACION:</td><td> ".$datos[0][tipo_id_paciente].'-'.$datos[0][paciente_id]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\">No. INGRESO: </td><td>".$datos[0][ingreso]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\">CUENTA: </td><td>".$datos[0][numerodecuenta]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\">FECHA ELABORACIÓN:</td><td> ".$datos[0][fecha_elaboracion]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\">VENCIMIENTO:</td><td> ".$datos[0][fecha_vencimiento]."</td></tr>";
	$this->salida .= "             </table>";
	$this->salida .= "             <table border=\"0\" width=\"35%\" align=\"center\" class=\"normal_10\">";
	$this->salida .= "                <tr>";
	$this->salida .= "      	  		  <td class=\"modulo_table_list_title\" width=\"95%\" colspan=\"3\">";
	$this->salida .= "      		  		RESPONSABLE";
	$this->salida .= "      			  	</td>";
	$this->salida .= "                </tr>";
	$this->salida .= "                <tr><td class=\"label\" width=\"35%\">RESPONSABLE: </td><td>".$datos[0][primer_nombre_garante]." ".$datos[0][segundo_nombre_garante]." ".$datos[0][primer_apellido_garante]." ".$datos[0][segundo_apellido_garante]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\" width=\"35%\">IDENTIFICACIÓN: </td><td>".$datos[0][tipo_id_tercero]."-".$datos[0][garante_id]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\" width=\"35%\">DIRECCIÓN:</td><td> ".$datos[0][direccion_garante]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\" width=\"35%\">TELEFONO: </td><td>".$datos[0][telefono_garante]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\" width=\"35%\">VALOR DEL PAGARÉ: </td><td>".$datos[0][valor]."</td></tr>";
	$this->salida .= "                <tr><td class=\"label\" width=\"35%\">OBSERVACIONES: </td>";
	$this->salida .= "                <td><textarea class=\"input-text\" name=\"observ\" cols=\"40\" rows=\"5\">".$datos[0][observacion]."</textarea></td></tr>";	
	$this->salida .= "          </table><br>";
  $this->salida .= "             <center>";
	$this->salida .= "              <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\">";
	$this->salida .= "     </form>";
	if ($_REQUEST['ANULAR'] == 'A')
 	 { 
		$msg='Esta seguro de anular el pagaré?.';
		$accionEstado=ModuloGetURL('app','Credito_Paciente','user','ConfirmarAccion',array('titulo'=>'ANULAR PAGARÉ Nro. '.$datos[0][pagare_id], 'mensaje'=>$msg,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR', 'pagare_id'=>$datos[0][pagare_id],'anular'=>$_REQUEST['anular']));
		$this->salida .= "			     <form name=\"forma\" action=\"$accionEstado\" method=\"post\">";	
		$this->salida .= "            <input class=\"input-submit\" type=\"submit\" name=\"anul\" value=\"ANULAR PAGARÉ\">";
    $this->salida .= "            <input type=\"hidden\" name=\"anular\" value=\"".$_REQUEST['ANULAR']."\">";		
		$this->salida .= "			     </form>";	
		$this->salida .= "			     </center>";	
	 }
	$this->salida .= "					   </center>";		
	$this->salida .= ThemeCerrarTabla();
	return true;
 }
 
//formaMenuAdmin
function formaMenuAdmin($user)
 {
		$this->salida  = ThemeAbrirTabla('ADMINISTRACIÓN - OPCIONES');
		
		if($this->uno == 1)
		{  
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
		}
		
		$this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"40%\">";
		$this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"100%\" colspan=\"1\">";
		$this->salida .= "      MENÚ ADMINISTRACIÓN";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_oscuro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Credito_Paciente','user','LlamaFormaAdmin')."\">AUTORIZAR</a>";//LlamaFormaPagare
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr>";
		$this->salida .= "      <td class=\"modulo_list_claro\"label\" align=\"center\">";
		$this->salida .= "      <a href=\"". ModuloGetURL('app','Credito_Paciente','user','LlamaFormaAdmin',array("ANULAR"=>'A'))."\">ANULAR</a>";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$accion=ModuloGetURL('app','Credito_Paciente','user','PrincipalCreditoPaciente');
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
 
//formaMenuAdminError
function formaMenuAdminError()
{ 
	$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
	$this->salida .= $this->SetStyle("MensajeError");
	$this->salida .= "      </table><br>";
	
	$accion=ModuloGetURL('app','Credito_Paciente','user','PrincipalCreditoPaciente');
  $this->salida .= "  <form name=\"form\" action=\"$accion\" method=\"post\">";
	$this->salida .= "  <tr>";
	$this->salida .= "  <td align=\"center\"><br>";
	$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
	$this->salida .= "  </td>";
	$this->salida .= "  </form>";
}

function ConfirmarAccion()
{ echo $_REQUEST['anular'];
  //echo 'e'.$_REQUEST['observ'].'p';
	$this->salida  = ThemeAbrirTabla($_REQUEST['titulo']);
	$this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
	$this->salida .= "  <tr>";
	$this->salida .= "    <td width=\"40%\">";
	$accion=ModuloGetURL('app','Credito_Paciente','user','Anular',array('anular'=>$_REQUEST['anular'],'aceptar'=>$_REQUEST['aceptar'], 'cancel'=>$_REQUEST['cancel'],'pagare_id'=>$_REQUEST['pagare_id'],'observ'=>$_REQUEST['observ']));
	$this->salida .= "     <form name=\"form\" action=\"$accion\" method=\"post\">";
	$this->salida .= "     <center>";
	$this->salida .= "".$_REQUEST['mensaje']."";
	$this->salida .= "     <br><br>";
  $this->salida .= "       <input type=\"hidden\" name=\"pagare_id\" value=\"".$_REQUEST['pagare_id']."\">";		
  $this->salida .= "       <input type=\"hidden\" name=\"anular\" value=\"".$_REQUEST['anular']."\">";
  $this->salida .= "       <input type=\"hidden\" name=\"observ\" value=\"".$_REQUEST['observ']."\">";
	$this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"".$_REQUEST['boton1']."\">";
	$this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"cancel\" value=\"".$_REQUEST['boton2']."\">";
	$this->salida .= "     </center>";
	$this->salida .= "    </td>";
	$this->salida .= "  </tr>";
  $this->salida .= "  </form>";
	$this->salida .= "  </table>";
	$this->salida .= ThemeCerrarTabla();
	return true;
}

//forma que visualiza los pagares pendientes
 function FormaPendiente()
 {
 	 if($_SESSION['crepac']['empresa']==NULL)
		{
			$_SESSION['crepac']['empresa']=$_REQUEST['permisocredpaci']['empresa_id'];
			$_SESSION['crepac']['razonso']=$_REQUEST['permisocredpaci']['descripcion1'];
		}
		UNSET($_SESSION['ctrpla']);
		UNSET($_SESSION['ctrpl1']);
    $pagempr=$this->BuscarNombrePacip();
    $this->salida  = ThemeAbrirTabla('PAGARÉS PENDIENTES DEL PACIENTE: '.$pagempr[0]['tipo_id_paciente'].' - '.$pagempr[0]['paciente_id'].' '.$pagempr[0]['primer_nombre'].' '.$pagempr[$i]['segundo_nombre'].' '.$pagempr[$i]['primer_apellido'].' '.$pagempr[$i]['segundo_apellido'].'');
		if ($this->uno == 1)
		{
	   $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
	   $this->salida .= $this->SetStyle("MensajeError");
	   $this->salida .= "      </table><br>";
		}
		$accion=ModuloGetURL('app','Credito_Paciente','user','');
		$this->salida .= "  <form name=\"credito\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=modulo_list_claro>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">NOMBRE DE LA EMPRESA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td align=\"center\" width=\"70%\">";
		$this->salida .= "      ".$_SESSION['crepac']['razonso']."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"3%\">No.</td>";
		$this->salida .= "      <td width=\"5%\">No. - PAGARÉ</td>";
		$this->salida .= "      <td width=\"10%\">CUENTA</td>";
		$this->salida .= "      <td width=\"10%\">FECHA</td>";
		$this->salida .= "      <td width=\"8%\">FECHA VENCIMIENTO</td>";
		$this->salida .= "      <td width=\"10%\">VALOR</td>";
		$this->salida .= "      <td width=\"4%\">ABONO</td>";
		$this->salida .= "      <td width=\"5%\">SALDO</td>";
		$this->salida .= "      <td width=\"4%\">ESTADO</td>";
		$this->salida .= "      <td width=\"5%\" >OPCIÓN</td>";
		$this->salida .= "      </tr>";

		$i=0;
		$j=0;
		$ciclo=sizeof($pagempr);
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
			$this->salida .= "".$pagempr[$i]['pagare_id']."";//plan_id
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['numerodecuenta']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$pagempr[$i]['fecha_elaboracion']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['fecha_vencimiento']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$pagempr[$i]['valor']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['abono']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$pagempr[$i]['saldo']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
		  $this->salida .= "".$pagempr[$i]['descripcion']."";
			$this->salida .= "</td>";
  		//$this->salida.=$reporte->GetJavaReport_HC('3965',array());
	   	//$funcion=$reporte->GetJavaFunction();
			$this->salida .= "<td>";
			//$this->salida .= "<a href =\"javascript:$funcion\">OPCIÓN</a>";
			if ($tmp == 'A' OR $_REQUEST['ANULAR']=='A')
			{
			  if (empty($tmp))
						$tmp = $_REQUEST['ANULAR'];
	 	  	$funcion=ModuloGetURL('app','Credito_Paciente','user','LlamaFormaOpcionesPagare2',array("pagare_id"=>$pagempr[$i]['pagare_id'],"ANULAR"=>$tmp));
			}
			else
		    if ($_REQUEST['pagos']=='P')
    	 	  $funcion=ModuloGetURL('app','Credito_Paciente','user','LlamaFormaOpcionesPagare',array("pagare_id"=>$pagempr[$i]['pagare_id'],"pagos"=>$tmpPAGOS));
        	  else
    	    	  $funcion=ModuloGetURL('app','Credito_Paciente','user','LlamaFormaOpcionesPagare',array("pagare_id"=>$pagempr[$i]['pagare_id']));
			$this->salida .= "<center><a href =$funcion>VER</a></center>";
			$this->salida .= "</td>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		if(empty($pagempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"9\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PAGARÉ'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table>";
//		$this->salida .= "      <table border=\"0\" width=\"20%\" align=\"center\">";
//		$this->salida .= "      <tr>";
//		$this->salida .= "      <td align=\"center\"><br>";
//		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"NUEVO PLAN\">";
//		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
//		$this->salida .= "      </tr>";
//		$this->salida .= "      </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('app','Credito_Paciente','user','PrincipalCreditoPaciente');
		$this->salida .= "  <form name=\"contratacion1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$nuevo=ModuloGetURL('app','Credito_Paciente','user','PrincipalCreditoPaciente');
		$this->salida .= "  <form name=\"contratacion1\" action=\"$nuevo\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"empresas\" value=\"NUEVO PAGARÉ\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";

		$this->salida .= "  </table><br>";
		//$var=$this->RetornarBarraClientes();
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
		$accion=ModuloGetURL('app','Credito_Paciente','user','FormaAdmin',
		array('codigoctra'=>$_REQUEST['codigoctra'],'ctradescri'=>$_REQUEST['ctradescri']));
		$this->salida .= "  <form name=\"contratacion2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO PAGARÉ:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigoctra\" value=\"".$_REQUEST['codigoctra']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
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
		$accion=ModuloGetURL('app','Credito_Paciente','user','FormaAdmin');
		$this->salida .= "  <form name=\"contratacion3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
 }

	
}//fin de la clase
?>
