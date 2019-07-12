
<?php

/**
*MODULO para el Manejo de Programacion de cirugias del sistema
*
* @author Lorena Aragon
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos visuales para realizar la programacion de cirugias
*/
class app_Procedimientos_Cadaveres_userclasses_HTML extends app_Procedimientos_Cadaveres_user
{
	/**
	*Constructor de la clase app_ProgramacionQX_user_HTML
	*El constructor de la clase app_ProgramacionQX_user_HTML se encarga de llamar
	*a la clase app_ProgramacionQX_user quien se encarga de el tratamiento
	*de la base de datos.
	*/

  function app_Procedimientos_Cadaveres_user_HTML()
	{
		$this->salida='';
		$this->app_Procedimientos_Cadaveres_user();
		return true;
	}

/**
* Funcion que muestra las distintas opciones del menu para el usuario
* @return boolean
*/
	function MenuConsultas(){
    $this->salida .= ThemeAbrirTabla('MENU PROCESOS CADAVERES');		//$this->salida .= "			      <br><br>";
    $action=ModuloGetURL('system','Menu','user','main');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "			<table width=\"35%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
		$action1=ModuloGetURL('app','Procedimientos_Cadaveres','user','LlamaRecibirCadaver');
		$action2=ModuloGetURL('app','Procedimientos_Cadaveres','user','LlamaConsultaCadaver');
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action1\" class=\"link\"><b>RECEPCION CADAVERES</b></a></td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action2\" class=\"link\"><b>CONSULTA CADAVERES</b></a></td></tr>";
    $this->salida .= "			     </table><BR>";
		$this->salida .= "     <table width=\"40%\" border=\"0\" align=\"center\"class=\"normal_10\" >";
    $this->salida .= "     <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"salir\" value=\"SALIR\"></td></tr>";
    $this->salida .= "     </table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

		/**
* Funcion que muestra las distintas opciones del menu para el usuario
* @return boolean
*/
	function SinPermisosUsuarios(){
    $this->salida .= ThemeAbrirTabla('PATOLOGIA');		//$this->salida .= "			      <br><br>";
    $action=ModuloGetURL('system','Menu','user','main');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "			<table width=\"50%\" align=\"center\" class=\"norma_10\">";
    $this->salida .= "      <tr><td align=\"center\" class=\"label_error\">NO TIENE PERMISOS PARA TRABAJAR EN ESTE MODULO</td></tr>";
		$this->salida .= "      <tr><td align=\"center\"><BR><input type=\"submit\" class=\"input-submit\" name=\"salir\" value=\"SALIR\"></td></tr>";
    $this->salida .= "     </table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

	function PedirRegistroPaciente(){

		$this->salida .= ThemeAbrirTabla('IDENTIFICACION DEL CADAVER');
    $action=ModuloGetURL('app','Procedimientos_Cadaveres','user','PedirDatosPaciente');
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "<table width=\"60%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "<tr><td colspan=\"4\" align=\"center\">";
		$this->salida .=  $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "   <BR><table width=\"90%\" border=\"0\" align=\"center\" class=\"normal_10\" >";
    $this->salida .= "		<tr class=\"modulo_table_list_title\"><td colspan=\"4\" class=\"modulo_table_list_title\">DATOS DEL PACIENTE</td></tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\"><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->Mostrar($tipo_id,'False',$TipoId);
		$this->salida .= "    </select></td>";
		$this->salida .= "		 </tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		 <td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\"></td>";
		$this->salida .= "		 </tr>";
    $this->salida .= "    </table><BR>";
    $this->salida .= "</td></tr>";
		$this->salida .= "</table>";
    $this->salida .= "   <table width=\"90%\" border=\"0\" align=\"center\"class=\"normal_10\" >";
    $this->salida .= "		<tr><td align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
    $this->salida .= "</form>";
		$action1=ModuloGetURL('app','Procedimientos_Cadaveres','user','MenuConsultas');
		$this->salida .= "  <form name=\"forma\" action=\"$action1\" method=\"post\">";
		//$this->salida .= "   <table width=\"90%\" border=\"0\" align=\"center\"class=\"normal_10\" >";
		$this->salida .= "	  <td align=\"left\"><input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\"></td></tr>";
    $this->salida .= "   </table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
* Funcion que se encarga de visualizar un error en un campo
* @return string
*/
	function SetStyle($campo){
		if ($this->frmError[$campo] || $campo=="MensajeError"){
		  if ($campo=="MensajeError"){
				return ("<tr><td colspan=\"3\" class='label_error' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}

/**
* Funcion que se encarga de listar los elementos pasados por parametros
* @return array
* @param array codigos y valores que vienen en el arreglo
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los valores del arreglo
* @param string elemento seleccionado en el objeto donde se imprimen los valores
*/
	function Mostrar($arreglo,$Seleccionado='False',$Defecto=''){

	  switch($Seleccionado){
			case 'False':{
			  foreach($arreglo as $value=>$titulo){
					if($value==$Defecto){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
		  }
			case 'True':{
				foreach($arreglo as $value=>$titulo){
					if($value==$Defecto){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
			}
		}
	}

	function FormaDatosCadaver($Documento,$TipoDocumento){

    $this->salida .= ThemeAbrirTabla('DATOS DEL CADAVER');
    $action=ModuloGetURL('app','Procedimientos_Cadaveres','user','GuardarRecepcionCadaver',array("Documento"=>$Documento,"TipoDocumento"=>$TipoDocumento));
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "<table width=\"50%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS PACIENTE</legend>";
		$this->salida .= "  <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$nombre=$this->nombrePaciente($TipoDocumento,$Documento);
		$EdadArr=CalcularEdad($nombre['fecha_nacimiento'],$FechaFin);
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"25%\"  class=\"label\">IDENTIFICACION</td>";
		$this->salida .= "  <td>$TipoDocumento $Documento</td>";
		$this->salida .= "  <td width=\"25%\"  class=\"label\">EDAD</td>";
		$this->salida .= "  <td>".$EdadArr['edad_aprox']."</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"25%\" class=\"label\">NOMBRE</td>";
		$this->salida .= "  <td colspan=\"3\">".$nombre['nombre']."</td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </table>";
		$this->salida .= "  </fieldset></td></tr>";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
    $this->salida .= "	  <table width=\"80%\" border=\"0\" class=\"normal_10\" align=\"center\">";
    $this->salida .= "	  <tr class=\"modulo_list_oscuro\"><td>";
    $this->salida .= "	    <BR><table width=\"95%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "	    <tr class=\"modulo_table_list_title\"><td colspan=\"3\">DATOS CADAVER</td></tr>";
    $this->salida .= "	    <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td class=\"".$this->SetStyle("fecha")."\" align=\"left\">FECHA Y HORA RECEPCION</td>";
		if(!$_REQUEST['fecha']){
      $_REQUEST['fecha']=date("d-m-Y");
		}
		$this->salida .= "      <td align=\"left\"><input type=\"text\" class=\"input-text\" size=\"10\" READONLY maxlength=\"10\" value=\"".$_REQUEST['fecha']."\" name=\"fecha\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('forma','fecha','-')."</td>" ;
		$this->salida.="        <td>";
    $this->salida.="          <table>";
    $this->salida.="              <tr>";
	  $this->salida.="              <td>";
		$this->salida.="              <select size=\"1\" name=\"hora\" class=\"select\" $desabilitado>";
		$this->salida.="              <option value = -1>Seleccione Hora </option>";
		if(!$_REQUEST['hora']){
      $_REQUEST['hora']=date('H');
		}
	  for ($j=0;$j<=23; $j++){
      if (($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($_REQUEST['hora']==$hora){
				  $this->salida.="        <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="        <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($_REQUEST['hora']==$j){
					$this->salida.="        <option selected value = $j>$j</option>";
				}else{
					$this->salida.="        <option value = $j>$j</option>";
				}
			}
    }
    $this->salida.="              </select>";
    $this->salida.="              </td>";
		$this->salida.="              <td>";
    $this->salida.="              <select size=\"1\"  name=\"minutos\" class=\"select\" $desabilitado>";
	  $this->salida.="              <option value = -1>Seleccione Minutos</option>";
		if(!$_REQUEST['minutos']){
      $_REQUEST['minutos']=date('i');
		}
		for ($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($_REQUEST['minutos']==$min){
					$this->salida.="         <option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="        <option value=\"$min\">0$j</option>";
				}
			}else{
			  if($_REQUEST['minutos']==$j){
					$this->salida.="        <option selected value=$j>$j</option>";
				}else{
					$this->salida.="        <option value=$j>$j</option>";
				}
			}
    }
    $this->salida.="              </select>";
    $this->salida.="              </td>";
    $this->salida.="              </tr>";
		$this->salida.="            </table>";
		$this->salida.="       </td>";
		$this->salida .= "	    </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("responsableCadaver")."\">FUNCIONARIO QUE<br>ENTREGA CADAVER<br>A PATOLOGIA</td>";
		$this->salida .= "	    <td colspan=\"2\">";
		$this->salida .= "     <select name=\"responsableCadaver\" class=\"select\">";
		$profesionales=$this->TotalProfesionalesPermitidos();
		$this->BuscarProfesionlesEspecialistas($profesionales,'False',$_REQUEST['responsableCadaver']);
		$this->salida .= "      </select>";
    $this->salida .= "	    </td></tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"".$this->SetStyle("otroresponsableCadaver")."\">OTRO FUNCIONARIO QUE<br>ENTREGA CADAVER<br>A PATOLOGIA</td>";
    $this->salida .= "      <td colspan=\"2\"><input type=\"text\" name=\"otroresponsableCadaver\" maxlength=\"100\" size=\"40\" value=\"".$_REQUEST['otroresponsableCadaver']."\" class=\"input-text\"></td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"label\">DEPARTAMENTO PROCEDENCIA</td><td colspan=\"2\"><select name=\"departamento\" class=\"select\">";
		$departamentos=$this->tiposdepartamentos();
		$this->MostrasSelect($departamentos,'False',$_REQUEST['departamento']);
		$this->salida .= "      </select></td></tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("origenSolicitud")."\">OTRO ORIGEN PROCEDENCIA</td><td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"origenSolicitud\" maxlength=\"100\" value=\"".$_REQUEST['origenSolicitud']."\"></td></tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("responsableSolicitud")."\">RESPONSABLE SOLICITUD </td><td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"responsableSolicitud\" maxlength=\"150\" size=\"40\" value=\"".$_REQUEST['responsableSolicitud']."\"></td></tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("Solicitud")."\">SOLICITUD PROCEDIMIENTO</td><td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Solicitud\" size=\"60\" maxlength=\"150\" value=\"".$_REQUEST['Solicitud']."\"></td></tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td colspan=\"3\" align=\"left\"><label class=\"label\">CAUSA MUERTE</label><BR><BR><textarea style=\"width:100%\" name=\"causaMuerte\" class=\"textarea\" rows=\"3\" cols=\"60\">".$_REQUEST['causaMuerte']."</textarea></td>";
		$this->salida.="        </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("certificadoDefuncion")."\">CERTIFICADO DEFUNCION / <br> ACTA LEVANTAMIENTO </td><td colspan=\"2\"><input type=\"text\" maxlength=\"80\" class=\"input-text\" name=\"certificadoDefuncion\" size=\"60\" value=\"".$_REQUEST['certificadoDefuncion']."\"></td></tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td colspan=\"3\" align=\"left\"><label class=\"label\">OBSERVACIONES</label><BR><BR><textarea style=\"width:100%\" name=\"observaciones\" class=\"textarea\" rows=\"3\" cols=\"60\">".$_REQUEST['observaciones']."</textarea></td>";
		$this->salida.="        </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td colspan=\"3\" align=\"center\">";
    $this->salida .= "        <BR><table width=\"90%\" border=\"0\" class=\"normal_10\" align=\"center\">";
    $this->salida .= "	        <tr class=\"modulo_table_list_title\"><td colspan=\"2\">PROCEDIMIENTOS</td></tr>";
		$procedimientos=$_SESSION['CADAVERES']['PROCEDIMIENTOSINICIAL'];
		foreach($_REQUEST as $v=>$datos){
			if($v!='SIIS_SID' AND $v!='modulo' AND $v!='metodo' AND $v!='procedimientoBus' AND $v!='codigoBus' AND $v!='Salir'){
        $vec[$v]=$datos;
			}
		}
		foreach($procedimientos as $codigo=>$vector){
      foreach($vector as $indice=>$nombrepro){
        $this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
				$this->salida .= "	        <td align=\"left\">$nombrepro</td>";
				$vec['codigoProcedimiento']=$codigo;
				$actionElimina=ModuloGetURL('app','Procedimientos_Cadaveres','user','EliminaProcedimientoInicial',$vec);
				$this->salida .= "          <td width=\"5%\" nowrap align=\"center\"><a href=\"$actionElimina\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
        $this->salida .= "	        </tr>";
			}
		}
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\"><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"BuscarPro\" value=\"BUSCAR\" class=\"input-submit\"></td></tr>";
    $this->salida .= "        </table><BR>";
    $this->salida.="        </td></tr>";

		$this->salida .= "	    </table><BR>";
		$this->salida .= "	  </td></tr>";
    $this->salida .= "   <tr><td align=\"center\">";
		$this->salida .= "   <input type=\"submit\" name=\"Guardar\" value=\"GUARDAR\" class=\"input-submit\">";
		$this->salida .= "   <input type=\"submit\" name=\"Regresar\" value=\"REGRESAR\" class=\"input-submit\">";
		$this->salida .= "   </td></tr>";
    $this->salida .= "	  </table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
* Funcion que se encarga de listar los nombres de los tipos de origen de una cirugia
* @return array
* @param array codigos y valores de los tipos de origen de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los tipos de origen
* @param string elemento seleccionado en el objeto donde se imprimen los tipo de origen
*/
	function MostrasSelect($arreglo,$Seleccionado='False',$valor=''){
		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($arreglo as $value=>$titulo){
					if($value==$valor){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  foreach($arreglo as $value=>$titulo){
				  if($value==$valor){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}

	/**
	* La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
	* @return boolean
	* @param string mensaje a retornar para el usuario
	* @param string titulo de la ventana a mostrar
	* @param string lugar a donde debe retornar la ventana
	* @param boolean tipo boton de la ventana
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton){

		$this->salida .= ThemeAbrirTabla($titulo);
		$this->salida .= "			      <table class=\"normal_10\" width=\"60%\" align=\"center\">";
		$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "				       <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
		if($boton){
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
		}
	  else{
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
	  }
		$this->salida .= "			     </form>";
		$this->salida .= "			     </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ConsultaCadaver($TipoDocumento,$Documento,$departamento,$fecha,$certificado,$entregado,$noinforme,$todasFechas){

		$this->salida .= ThemeAbrirTabla("CONSULTA CADAVERES");
		$accion=ModuloGetURL('app','Procedimientos_Cadaveres','user','LlamaConsultaCadaver');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table width=\"80%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">FILTRO DE BUSQUEDA</legend>";
		$this->salida .= "  <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\">TIPO DOCUMENTO </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->Mostrar($tipo_id,'False',$TipoDocumento);
		$this->salida .= "  </select></td>";
		$this->salida .= "  <td class=\"label\">DOCUMENTO </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" value=\"$Documento\" maxlength=\"32\"></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\">DEPARTAMENTO </td>";
		$this->salida .= "  <td><select name=\"departamento\" class=\"select\">";
		$departamentos=$this->tiposdepartamentos();
		$this->MostrasSelect($departamentos,'False',$departamento);
		$this->salida .= "  </select></td>";
		$this->salida .= "  <td class=\"".$this->SetStyle("fecha")."\" align=\"left\">FECHA</td>";
		$this->salida .= "  <td align=\"left\">";
    $this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
		if($todasFechas==1){
      $var='checked';
		}
		$this->salida .= "    <td align=\"left\" class=\"label\">TODAS LAS FECHAS<input type=\"checkbox\" value=\"1\" name=\"todasFechas\" $var>";
    $this->salida .= "    </td>";
		$this->salida .= "    </tr>";
		$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "    <td align=\"left\">";
		$this->salida .= "    <input type=\"text\" class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"$fecha\" name=\"fecha\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('forma','fecha','-')."";
		$this->salida .= "    </td>";
		$this->salida .= "    </tr>";
		$this->salida .= "    </table>";
    $this->salida .= "    </td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\">CERTIFICADO DEFUNCION /<br>ACTA LEVANTAMIENTO</td>";
		$this->salida .= "  <td class=\"label\"><input type=\"text\" class=\"text-input\" maxlength=\"80\" size=\"25\" name=\"certificado\" value=\"$certificado\"></td>";
		$this->salida .= "  <td class=\"label\">No. INFORME </td>";
		$this->salida .= "  <td><input type=\"text\" class=\"input-submit\" value=\"$noinforme\" name=\"noinforme\"></td>";
    $this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
    $this->salida .= "  <td class=\"label\">ENTREGADO</td>";
		$this->salida .= "  <td class=\"label\" colspan=\"3\">";
		if(!$entregado || $entregado==-1){
      $var='checked';
		}else{
      $var1='checked';
		}
		$this->salida .= "  No&nbsp&nbsp&nbsp;<input type=\"radio\" value=\"-1\" name=\"entregado\" $var>";
		$this->salida .= "  Si&nbsp&nbsp&nbsp;<input type=\"radio\" value=\"1\" name=\"entregado\" $var1>";
		$this->salida .= "  </td>";
    $this->salida .= "  </tr>";
    $this->salida .= "  <tr class=\"modulo_list_claro\"><td colspan=\"4\" align=\"center\">";
		$this->salida .= "  <input type=\"submit\" name=\"Filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
    $this->salida .= "  <input type=\"submit\" name=\"Menu\" value=\"MENU\" class=\"input-submit\">";
    $this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  </fieldset></td></tr>";
    $this->salida .= "  </table>";
		$cadaveres=$this->ConsultaCadaveres($TipoDocumento,$Documento,$departamento,$fecha,$certificado,$entregado,$noinforme,$todasFechas);
		if($cadaveres){
      $this->salida .= "    <BR><table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "    <tr class=\"modulo_table_list_title\">";
			$this->salida .= "    <td>No. CADAVER</td>";
			$this->salida .= "    <td>PACIENTE</td>";
			$this->salida .= "    <td>FECHA RECEPCION</td>";
			$this->salida .= "    <td>DEPARTAMETO</td>";
			$this->salida .= "    <td>CERTIFICADO DEFUNCION /<br>ACTA LEVANTAMIENTO</td>";
			//$tipoUser=$this->Tipo_Usuario_Log();
			//if($tipoUser==1){
			$this->salida .= "    <td>INFORME DE <br>RESULTADOS</td>";
			$this->salida .= "    <td>ENTREGA<BR>CADAVER</td>";
			$this->salida .= "    </tr>";
			//IMPRESION
			$rep= new GetReports();
      //FIN IMPRESION
			for($i=0;$i<sizeof($cadaveres);$i++){
        if($y % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
        $this->salida .= "    <tr class=\"$estilo\">";
				$this->salida .= "    <td>".$cadaveres[$i]['cadaver_id']."</td>";
				$this->salida .= "    <td>".$cadaveres[$i]['tipo_id_paciente']." ".$cadaveres[$i]['paciente_id']."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp; ".$cadaveres[$i]['nombre']."</td>";
				$this->salida .= "    <td>".$cadaveres[$i]['fecha']."</td>";
				$this->salida .= "    <td>".$cadaveres[$i]['nom_departamento']."</td>";
				$this->salida .= "    <td>".$cadaveres[$i]['certificado_defuncion']."</td>";
				if($cadaveres[$i]['resultado_informe_id']){
          if($cadaveres[$i]['examen_firmado']=='1'){
					  //CONSULTA
						$actionConsulta=ModuloGetURL('app','Procedimientos_Cadaveres','user','LlamaInformeCadaver',array("cadaverId"=>$cadaveres[$i]['cadaver_id'],"informe"=>$cadaveres[$i]['resultado_informe_id'],"prefijo"=>$cadaveres[$i]['prefijo'],
						"TipoId"=>$cadaveres[$i]['tipo_id_paciente'],"pacienteId"=>$cadaveres[$i]['paciente_id'],"nombre"=>$cadaveres[$i]['nombre'],"fechaNac"=>$cadaveres[$i]['fecha_nacimiento'],"Destino"=>1));
						$this->salida .= "    <td align=\"center\">";
						if($cadaveres[$i]['prefijo']){
              $this->salida .= "    <a href=\"$actionConsulta\" class=\"Menu\">INFORME No. ".$cadaveres[$i]['prefijo']." ".$cadaveres[$i]['resultado_informe_id']."<BR>";
						}else{
              $this->salida .= "    <a href=\"$actionConsulta\" class=\"Menu\">INFORME No. ".$cadaveres[$i]['resultado_informe_id']."<BR>";
						}
						if(!empty($cadaveres[$i]['nomprofesionalinfor'])){
							$this->salida .= "    ".$cadaveres[$i]['nomprofesionalinfor']."</a><BR>";
						}
						//IMPRESION
						$mostrar=$rep->GetJavaReport('app','Procedimientos_Cadaveres','examenes_html',array("cadaverId"=>$cadaveres[$i]['cadaver_id'],"informe_id"=>$cadaveres[$i]['resultado_informe_id'],"prefijo"=>$cadaveres[$i]['prefijo']),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
						$nombre_funcion=$rep->GetJavaFunction();
						$this->salida .=$mostrar;
						$this->salida .= "	  <a class=\"Menu\" href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a>";
						//FIN IMPRESION
						$this->salida .= "    </td>";
            if($cadaveres[$i]['estadocadaver']=='1'){
							$actionEntrega=ModuloGetURL('app','Procedimientos_Cadaveres','user','LlamaEntregaCadaver',array("cadaverId"=>$cadaveres[$i]['cadaver_id'],"informe"=>$cadaveres[$i]['resultado_informe_id'],
							"TipoId"=>$cadaveres[$i]['tipo_id_paciente'],"pacienteId"=>$cadaveres[$i]['paciente_id'],"nombre"=>$cadaveres[$i]['nombre'],"fechaNac"=>$cadaveres[$i]['fecha_nacimiento'],
							"nomprofesional"=>$cadaveres[$i]['nomprofesional'],"certificado_defuncion"=>$cadaveres[$i]['certificado_defuncion'],"otroResponsable"=>$cadaveres[$i]['otro_responsable_cadaver']));
							$this->salida .= "    <td align=\"center\"><a href=\"$actionEntrega\"><img border=\"0\" src=\"".GetThemePath()."/images/accidente.png\"></a></td>";
						}else{
              $actionEntrega=ModuloGetURL('app','Procedimientos_Cadaveres','user','LlamaConsultaEntregaCadaver',array("cadaverId"=>$cadaveres[$i]['cadaver_id'],"informe"=>$cadaveres[$i]['resultado_informe_id'],
							"TipoId"=>$cadaveres[$i]['tipo_id_paciente'],"pacienteId"=>$cadaveres[$i]['paciente_id'],"nombre"=>$cadaveres[$i]['nombre'],"fechaNac"=>$cadaveres[$i]['fecha_nacimiento'],
							"nomprofesional"=>$cadaveres[$i]['nomprofesional'],"certificado_defuncion"=>$cadaveres[$i]['certificado_defuncion'],"otroResponsable"=>$cadaveres[$i]['otro_responsable_cadaver']));
							$this->salida .= "    <td align=\"center\"><a href=\"$actionEntrega\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a></td>";
						}
					}else{
					  //MODIFICAR
						$actionModificar=ModuloGetURL('app','Procedimientos_Cadaveres','user','LlamaInformeCadaver',array("cadaverId"=>$cadaveres[$i]['cadaver_id'],"informe"=>$cadaveres[$i]['resultado_informe_id'],"prefijo"=>$cadaveres[$i]['prefijo'],
						"TipoId"=>$cadaveres[$i]['tipo_id_paciente'],"pacienteId"=>$cadaveres[$i]['paciente_id'],"nombre"=>$cadaveres[$i]['nombre'],"fechaNac"=>$cadaveres[$i]['fecha_nacimiento'],"Destino"=>2));
						if($cadaveres[$i]['prefijo']){
              $this->salida .= "    <td align=\"center\"><a href=\"$actionModificar\" class=\"TurnoActivo\">INFORME No. ".$cadaveres[$i]['prefijo']." ".$cadaveres[$i]['resultado_informe_id']."<BR>".$cadaveres[$i]['nomprofesionalinfor']."</a></td>";
						}else{
              $this->salida .= "    <td align=\"center\"><a href=\"$actionModificar\" class=\"TurnoActivo\">INFORME No. ".$cadaveres[$i]['resultado_informe_id']."";
							if($cadaveres[$i]['nomprofesionalinfor']){
							$this->salida .= "    <BR>".$cadaveres[$i]['nomprofesionalinfor']."</a></td>";
							}
						}
            $this->salida .= "    <td>&nbsp;</td>";
					}
				}else{
				  if($cadaveres[$i]['estadocadaver']=='1'){
            $actionNuevo=ModuloGetURL('app','Procedimientos_Cadaveres','user','LlamaInformeCadaver',array("cadaverId"=>$cadaveres[$i]['cadaver_id'],"prefijo"=>$cadaveres[$i]['prefijo'],
						"TipoId"=>$cadaveres[$i]['tipo_id_paciente'],"pacienteId"=>$cadaveres[$i]['paciente_id'],"nombre"=>$cadaveres[$i]['nombre'],"fechaNac"=>$cadaveres[$i]['fecha_nacimiento'],"Destino"=>3));
						$this->salida .= "    <td align=\"center\"><a href=\"$actionNuevo\">CREAR NUEVO INFORME</a></td>";
						$actionEntrega=ModuloGetURL('app','Procedimientos_Cadaveres','user','LlamaEntregaCadaver',array("cadaverId"=>$cadaveres[$i]['cadaver_id'],"informe"=>$cadaveres[$i]['resultado_informe_id'],
						"TipoId"=>$cadaveres[$i]['tipo_id_paciente'],"pacienteId"=>$cadaveres[$i]['paciente_id'],"nombre"=>$cadaveres[$i]['nombre'],"fechaNac"=>$cadaveres[$i]['fecha_nacimiento'],
						"nomprofesional"=>$cadaveres[$i]['nomprofesional'],"certificado_defuncion"=>$cadaveres[$i]['certificado_defuncion'],"otroResponsable"=>$cadaveres[$i]['otro_responsable_cadaver']));
						$this->salida .= "    <td align=\"center\"><a href=\"$actionEntrega\"><img border=\"0\" src=\"".GetThemePath()."/images/accidente.png\"></a></td>";
					}else{
					  $this->salida .= "    <td align=\"center\">&nbsp;</td>";
						$actionEntrega=ModuloGetURL('app','Procedimientos_Cadaveres','user','LlamaConsultaEntregaCadaver',array("cadaverId"=>$cadaveres[$i]['cadaver_id'],"informe"=>$cadaveres[$i]['resultado_informe_id'],
						"TipoId"=>$cadaveres[$i]['tipo_id_paciente'],"pacienteId"=>$cadaveres[$i]['paciente_id'],"nombre"=>$cadaveres[$i]['nombre'],"fechaNac"=>$cadaveres[$i]['fecha_nacimiento'],
						"nomprofesional"=>$cadaveres[$i]['nomprofesional'],"certificado_defuncion"=>$cadaveres[$i]['certificado_defuncion'],"otroResponsable"=>$cadaveres[$i]['otro_responsable_cadaver']));
						$this->salida .= "    <td align=\"center\"><a href=\"$actionEntrega\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a></td>";
					}
				}
        $this->salida .= "    </tr>";
			}
			$this->salida .= "  </table>";
			$this->salida .=$this->RetornarBarra(1);
		}

    $this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function InformeCadaver($cadaverId,$informe,$TipoId,$pacienteId,$nombre,$fechaNac,$Destino,$observacionesInforme,$firma,$nombreProfesional,$usuariofirma,$observacionesAdicionales,$patologoProfe,$Observas){

    if($Destino==1){
      $sololectura='readonly';
			$disabled='disabled';
		}
    $this->salida .= ThemeAbrirTabla("INFORME CADAVERES");
		$accion=ModuloGetURL('app','Procedimientos_Cadaveres','user','GuardarResultadoCadaver',array("cadaverId"=>$cadaverId,"informe"=>$informe,"prefijo"=>$prefijo,"TipoId"=>$TipoId,"pacienteId"=>$pacienteId,
		"nombre"=>$nombre,"fechaNac"=>$fechaNac,"Destino"=>$Destino));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input type=\"hidden\" name=\"patologoProfe\" value=\"$patologoProfe\">";
		$this->salida .= "  <table width=\"50%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS PACIENTE</legend>";
		$this->salida .= "  <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$EdadArr=CalcularEdad($fechaNac,$FechaFin);
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"25%\"  class=\"label\">IDENTIFICACION</td>";
		$this->salida .= "  <td>$TipoId $pacienteId</td>";
		$this->salida .= "  <td width=\"25%\"  class=\"label\">EDAD</td>";
		$this->salida .= "  <td>".$EdadArr['edad_aprox']."</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"25%\" class=\"label\">NOMBRE</td>";
		$this->salida .= "  <td colspan=\"3\">$nombre</td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </table>";
		$this->salida .= "  </fieldset></td></tr>";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "    <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"2\">INFORME DE CADAVERES No. $informe $prefijo</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "    <td class=\"label\">OBSERVACIONES</td>";
		$this->salida .= "    <td><textarea name=\"observacionesInforme\" class =\"textarea\" rows =\"12\" cols =\"100\" $sololectura>$observacionesInforme</textarea></td>";
    $this->salida .= "    </tr>";
    if($Destino!=1){
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$this->salida .= "    <td class=\"label\">TIPOS PLANTILLAS</td>";
		$this->salida .= "    <td><select name=\"Plantilla\" class=\"select\">";
		$plantillas=$this->TotalPlantillasPatologia();
		$this->MostrasSelect($plantillas,'False',$Plantilla);
		$this->salida .= "    </select>&nbsp&nbsp&nbsp;";
    $this->salida .= "    <input class=\"input-submit\" type=\"submit\" name=\"AdicionarPlan\" value=\"ADICIONAR PLANTILLA\">";
		$this->salida .= "    </td>";
		$this->salida .= "    </tr>";
		}

		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$procedimientos=$_SESSION['CADAVERES']['PROCEDIMIENTOS'];
    $this->salida .= "    <td class=\"label\">PROCEDIMIENTOS</td>";
    $this->salida .= "    <td>";
    $this->salida .= "        <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		foreach($procedimientos as $codigo=>$vector){
		  foreach($vector as $indice=>$nombrePro){
		  if($codigo){
      $this->salida .= "        <tr class=\"modulo_list_oscuro\">";
			$this->salida .= "        <td>$nombrePro</td>";
			if($Destino!=1){
			$actionElimina=ModuloGetURL('app','Procedimientos_Cadaveres','user','EliminaProcPatologiaResultado',array("codigo"=>$codigo,"cadaverId"=>$cadaverId,"informe"=>$informe,
			"TipoId"=>$TipoId,"pacienteId"=>$pacienteId,"nombre"=>$nombre,"fechaNac"=>$fechaNac,"Destino"=>$Destino,"observacionesInforme"=>$observacionesInforme,"firma"=>$firma,"patologoProfe"=>$patologoProfe));
			$this->salida .= "        <td width=\"5%\" nowrap align=\"center\"><a href=\"$actionElimina\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
			}
			$this->salida .= "        </tr>";
			}
			}
		}
		if($Destino!=1){
		$this->salida .= "          <tr class=\"modulo_list_oscuro\"><td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"buscarPro\" value=\"BUSCAR\"></td></tr>";
		}
		$this->salida .= "        </table><BR>";
    $this->salida .= "    </td>";
    $this->salida .= "    </tr>";

		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$diagnosticos=$_SESSION['CADAVERES']['DIAGNOSTICOS'];
    $this->salida .= "    <td class=\"label\">DIAGNOSTICOS</td>";
    $this->salida .= "    <td>";
    $this->salida .= "        <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		foreach($diagnosticos as $codigo=>$nombreDiag){
		  if($codigo){
      $this->salida .= "        <tr class=\"modulo_list_oscuro\">";
			$this->salida .= "        <td>$nombreDiag</td>";
			if($Destino!=1){
			$actionElimina=ModuloGetURL('app','Procedimientos_Cadaveres','user','EliminaDiagResultadoCadaveres',array("codigo"=>$codigo,"cadaverId"=>$cadaverId,"informe"=>$informe,
			"TipoId"=>$TipoId,"pacienteId"=>$pacienteId,"nombre"=>$nombre,"fechaNac"=>$fechaNac,"Destino"=>$Destino,"observacionesInforme"=>$observacionesInforme,"firma"=>$firma,"patologoProfe"=>$patologoProfe));
			$this->salida .= "        <td width=\"5%\" nowrap align=\"center\"><a href=\"$actionElimina\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
			}
			$this->salida .= "        </tr>";
			}
		}
		if($Destino!=1){
		$this->salida .= "          <tr class=\"modulo_list_oscuro\"><td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"BuscarDiag\" value=\"BUSCAR\"></td></tr>";
		}
		$this->salida .= "        </table><BR>";
    $this->salida .= "    </td>";
    $this->salida .= "    </tr>";
		$tipoUser=$this->Tipo_Usuario_Log();
		if($Destino==1){
		  if($Observas){
			  $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
				$this->salida .= "    <td colspan=\"2\">";
        $this->salida .= "    <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida .= "    <tr class=\"modulo_table_title\">";
				$this->salida .= "    <td>FECHA</td>";
				$this->salida .= "    <td>PROFESIONAL</td>";
				$this->salida .= "    <td>OBSERVACIONES ADICIONALES</td>";
        $this->salida .= "    </tr>";
        for($i=0;$i<sizeof($Observas);$i++){
          $this->salida .= "    <tr class=\"modulo_list_claro\">";
          $this->salida .= "    <td width=\"15%\">".$Observas[$i]['fecha_registro']."</td>";
					$this->salida .= "    <td width=\"25%\">".$Observas[$i]['nombre']."</td>";
					$this->salida .= "    <td>".$Observas[$i]['observaciones_adicionales']."</td>";
					$this->salida .= "    </tr>";
				}
        $this->salida .= "    </table><BR>";
				$this->salida .= "    </td></tr>";
			}
		  if($tipoUser==1 || ($tipoUser!=1 && !empty($observacionesAdicionales))){
        if($tipoUser==1){
				$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
				$this->salida .= "    <td class=\"label\">OBSERVACIONES ADICIONALES</td>";
				$this->salida .= "    <td><textarea name=\"observacionesAdicionales\" class =\"textarea\" rows =\"12\" cols =\"100\">$observacionesAdicionales</textarea></td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr class=\"modulo_list_claro\">";
				$this->salida .= "    <td class=\"label\">TIPOS PLANTILLAS</td>";
				$this->salida .= "    <td><select name=\"PlantillaAdicional\" class=\"select\">";
				$plantillas=$this->TotalPlantillasPatologia();
				$this->MostrasSelect($plantillas,'False',$PlantillaAdicional);
				$this->salida .= "    </select>&nbsp&nbsp&nbsp;";
				$this->salida .= "    <input class=\"input-submit\" type=\"submit\" name=\"AdicionarPlanAdicional\" value=\"ADICIONAR PLANTILLA\">";
				$this->salida .= "    </td>";
				}
				$this->salida .= "    </tr>";
			}
		}
		if($tipoUser==1){
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		if($firma==1){
		  $varc='checked';
    //$this->salida .= "    <td colspan=\"2\" align=\"center\" class=\"label\">FIRMADO POR:&nbsp&nbsp&nbsp&nbsp;$nombreProfesional</td>";
		}
		if($Destino!=1){
    $this->salida .= "    <td colspan=\"2\" align=\"center\" class=\"label\"><input type=\"checkbox\" name=\"firma\" value=\"1\" $inabilitado $varc>&nbsp;APROBACION DEL RESULTADO AQUI REGISTRADO</td>";
		}else{
		$this->salida .= "    <td colspan=\"2\" align=\"center\" class=\"label\">FIRMADO POR:&nbsp&nbsp&nbsp&nbsp;$nombreProfesional</td>";
		$this->salida .= "    <input type=\"hidden\" name=\"nombreProfesional\" value=\"$nombreProfesional\">";
		}
    $this->salida .= "    </tr>";
		}else{
		  $this->salida .= "    <tr class=\"modulo_list_claro\">";
		  if($Destino==1){
        $this->salida .= "    <td colspan=\"2\" align=\"center\" class=\"label\">FIRMADO POR:&nbsp&nbsp&nbsp&nbsp;$nombreProfesional</td>";
        $this->salida .= "    <input type=\"hidden\" name=\"nombreProfesional\" value=\"$nombreProfesional\">";
			}else{
				$this->salida .= "    <td class=\"label\">PROFESIONAL PATOLOGO</td>";
				$this->salida .= "    <td>";
				$this->salida .= "          <select name=\"patologoProfe\" class=\"select\" $disabled>";
				$patologos=$this->TotalPatologos();
				$this->BuscarProfesionlesEspecialistas($patologos,'False',$patologoProfe);
				$this->salida .= "            </select>";
				$this->salida .= "    </td>";
				$this->salida .= "    </tr>";
			}
		}
		$this->salida .= "    <tr><td align=\"center\" colspan=\"2\">";
    if(($tipoUser!=1 && $Destino!=1)||($tipoUser==1)){
		$this->salida .= "    <input type=\"submit\" value=\"GUARDAR\" name=\"Guardar\" class=\"input-submit\">";
		}

		$this->salida .= "    <input type=\"submit\" value=\"REGRESAR\" name=\"Regresar\" class=\"input-submit\">";
		$this->salida .= "    </td></tr>";
    $this->salida .= "		</table>";
		$this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaBuscadorDiagnosticoResultado($cadaverId,$informe,$TipoId,$pacienteId,$nombre,
		$fechaNac,$Destino,$observacionesInforme,$firma,$patologoProfe){

    $this->salida .= ThemeAbrirTabla('BUSCADOR DIAGNOSTICOS');
		$action=ModuloGetURL('app','Procedimientos_Cadaveres','user','SeleccionDiagnostico',array("cadaverId"=>$cadaverId,"informe"=>$informe,"TipoId"=>$TipoId,"pacienteId"=>$pacienteId,"nombre"=>$nombre,
		"fechaNac"=>$fechaNac,"Destino"=>$Destino,"observacionesInforme"=>$observacionesInforme,"firma"=>$firma,"patologoProfe"=>$patologoProfe));
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "	  <table width=\"85%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"5\">PARAMENTROS DE BUSQUEDA</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td class=\"label\">DIAGNOSTICO</td>";
    $this->salida .= "    <td><input size=\"80\" type=\"text\" name=\"procedimientoBus\" value=\"".$_REQUEST['procedimientoBus']."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td class=\"label\">CODIGO</td>";
		$this->salida .= "    <td><input type=\"text\" size=\"10\" name=\"codigoBus\" value=\"".$_REQUEST['codigoBus']."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td><input type=\"submit\" name=\"buscar\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
		$this->salida .= "	  </table><BR>";
		$diags=$this->HallarDiagnosticosPatologia($_REQUEST['codigoBus'],$_REQUEST['procedimientoBus']);
		if($diags){
      $this->salida .= "	   <table width=\"80%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
			$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td>CODIGO</td>";
			$this->salida .= "	   <td>DESCRIPCION</td>";
			$this->salida .= "	   <td>&nbsp;</td>";
			$y=0;
			for($i=0;$i<sizeof($diags);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "    <tr class=\"$estilo\">";
				$this->salida .= "    <td width=\"10%\" nowrap>".$diags[$i]['diagnostico_id']."</td>";
				$this->salida .= "    <td>".$diags[$i]['diagnostico_nombre']."</td>";
				$action=ModuloGetURL('app','Procedimientos_Cadaveres','user','SeleccionarDiagnosticoPatologia',array("codigoDiagnostico"=>$diags[$i]['diagnostico_id'],"nombreDiagnostico"=>$diags[$i]['diagnostico_nombre'],
				"cadaverId"=>$cadaverId,"informe"=>$informe,"TipoId"=>$TipoId,"pacienteId"=>$pacienteId,"nombre"=>$nombre,"fechaNac"=>$fechaNac,"Destino"=>$Destino,"observacionesInforme"=>$observacionesInforme,
				"firma"=>$firma,"patologoProfe"=>$patologoProfe));
				$this->salida .= "    <td width=\"5%\" nowrap align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
        $this->salida .= "    </tr>";
			}
      $this->salida .= "	   </table>";
			$this->salida .=$this->RetornarBarra(2);
		}else{
      $this->salida .= "	   <BR><table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida .= "     <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "	   </table><br>";
		}
		$this->salida .= "	   <BR><table width=\"80%\" border=\"0\" align=\"center\">";
    $this->salida .= "     <tr><td align=\"center\"><input type=\"submit\" name=\"Salir\" value=\"SALIR\" class=\"input-submit\"></td></tr>";
    $this->salida .= "	   </table><br>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function CalcularNumeroPasos($conteo){
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	function CalcularBarra($paso){
		$barra=floor($paso/10)*10;
		if(($paso%10)==0){
			$barra=$barra-10;
		}
		return $barra;
	}

	function CalcularOffset($paso){
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	 function RetornarBarra($origen){

		if($this->limit>=$this->conteo){
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
		if($origen==1){
      $accion=ModuloGetURL('app','Procedimientos_Cadaveres','user','LlamaConsultaCadaver',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
			"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"departamento"=>$_REQUEST['departamento'],
		  "fecha"=>$_REQUEST['fecha'],"certificado"=>$_REQUEST['certificado']));
		}elseif($origen==2){
      $accion=ModuloGetURL('app','Procedimientos_Cadaveres','user','SeleccionDiagnostico',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
			"cadaverId"=>$_REQUEST['cadaverId'],"informe"=>$_REQUEST['informe'],"TipoId"=>$_REQUEST['TipoId'],"pacienteId"=>$_REQUEST['pacienteId'],"nombre"=>$_REQUEST['nombre'],
		  "fechaNac"=>$_REQUEST['fechaNac'],"Destino"=>$_REQUEST['Destino'],"observacionesInforme"=>$_REQUEST['observacionesInforme'],"firma"=>$_REQUEST['firma'],
			"codigoBus"=>$_REQUEST['codigoBus'],"procedimientoBus"=>$_REQUEST['procedimientoBus'],"patologoProfe"=>$_REQUEST['patologoProfe']));
		}elseif($origen==3){
      $accion=ModuloGetURL('app','Procedimientos_Cadaveres','user','SeleccionProcedimiento',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
			"cadaverId"=>$_REQUEST['cadaverId'],"informe"=>$_REQUEST['informe'],"TipoId"=>$_REQUEST['TipoId'],"pacienteId"=>$_REQUEST['pacienteId'],"nombre"=>$_REQUEST['nombre'],
		  "fechaNac"=>$_REQUEST['fechaNac'],"Destino"=>$_REQUEST['Destino'],"observacionesInforme"=>$_REQUEST['observacionesInforme'],"firma"=>$_REQUEST['firma'],"patologoProfe"=>$_REQUEST['patologoProfe'],
			"codigoBus"=>$_REQUEST['codigoBus'],"procedimientoBus"=>$_REQUEST['procedimientoBus']));
		}elseif($origen==4){
			foreach($_REQUEST as $v=>$datos){
				if($v!='SIIS_SID' AND $v!='modulo' AND $v!='metodo' AND $v!='BuscarPro'){
					$vec[$v]=$datos;
				}
			}
			$vec['codigoBus']=$_REQUEST['codigoBus'];
			$vec['procedimientoBus']=$_REQUEST['procedimientoBus'];
      $vec['conteo']=$this->conteo;
			$vec['paso']=$_REQUEST['paso'];
			$vec['Of']=$_REQUEST['Of'];
      $accion=ModuloGetURL('app','Procedimientos_Cadaveres','user','SeleccionProcedimientoInicial',$vec);
		}
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}else{
     // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
    }
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<=0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}else{
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
		}
		$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan='15' align='center'>Página $paso de $numpasos</td><tr></table>";
	}

	function EntregaCadaver($cadaverId,$TipoId,$pacienteId,$nombre,$fechaNac,$nomprofesional,$certificado_defuncion,$otroResponsable,
	$responsable,$nombre_recibe,$telefono,$parentesco,$observacion,$tipo_id_funcionario,$funcionario_id){

		$this->salida .= ThemeAbrirTabla("ENTREGA CADAVERES");
		$this->salida.="<script>";
		$this->salida.="function desabilitar(frm,valor){";
		$this->salida.="  if(valor==1){";
		$this->salida.="    frm.parentesco.disabled=true;";
		$this->salida.="    frm.tipo_id_funcionario.disabled=true;";
		$this->salida.="    frm.funcionario_id.disabled=true;";
		$this->salida.="    frm.nombre_recibe.disabled=true;";
		$this->salida.="    frm.telefono.disabled=true;";
		$this->salida.="    frm.observacion.disabled=true;";
		$this->salida.="  }else{";
		$this->salida.="  if(valor==2){";
		$this->salida.="    frm.parentesco.disabled=false;";
		$this->salida.="    frm.tipo_id_funcionario.disabled=true;";
		$this->salida.="    frm.funcionario_id.disabled=true;";
		$this->salida.="    frm.nombre_recibe.disabled=false;";
		$this->salida.="    frm.telefono.disabled=false;";
		$this->salida.="    frm.observacion.disabled=false;";
		$this->salida.="  }else{";
		$this->salida.="    frm.parentesco.disabled=true;";
		$this->salida.="    frm.tipo_id_funcionario.disabled=false;";
		$this->salida.="    frm.funcionario_id.disabled=false;";
		$this->salida.="    frm.nombre_recibe.disabled=false;";
		$this->salida.="    frm.telefono.disabled=true;";
		$this->salida.="    frm.observacion.disabled=true;";
		$this->salida.="  }";
		$this->salida.="  }";
		$this->salida.="}";
		$this->salida.="</script>";
		$accion=ModuloGetURL('app','Procedimientos_Cadaveres','user','GuardarEntregaCadaver',array("cadaverId"=>$cadaverId,"TipoId"=>$TipoId,"pacienteId"=>$pacienteId,
		"nombre"=>$nombre,"fechaNac"=>$fechaNac,"nomprofesional"=>$nomprofesional,"certificado_defuncion"=>$certificado_defuncion,"otroResponsable"=>$otroResponsable));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table width=\"70%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS PACIENTE</legend>";
		$this->salida .= "  <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$EdadArr=CalcularEdad($fechaNac,$FechaFin);
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"25%\"  class=\"label\">IDENTIFICACION</td>";
		$this->salida .= "  <td>$TipoId $pacienteId</td>";
		$this->salida .= "  <td width=\"25%\"  class=\"label\">EDAD</td>";
		$this->salida .= "  <td>".$EdadArr['edad_aprox']."</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"25%\" class=\"label\">NOMBRE</td>";
		$this->salida .= "  <td colspan=\"3\">$nombre</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"25%\" class=\"label\">ACTA LEVANTAMEINTO/ CERTIFICADO DEFUNCION</td>";
		$this->salida .= "  <td colspan=\"3\">$certificado_defuncion</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"25%\" class=\"label\">PROFESIONAL QUE <br>ENTREGO CADAVER <br>A PATOLOGIA</td>";
		if(!empty($nomprofesional)){
		$this->salida .= "  <td colspan=\"3\">$nomprofesional</td>";
		}else{
    $this->salida .= "  <td colspan=\"3\">$otroResponsable</td>";
		}
		$this->salida .= "  </tr>";
    $this->salida .= "  </table>";
		$this->salida .= "  </fieldset></td></tr>";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "	  <table width=\"80%\" border=\"0\" class=\"normal_10\" align=\"center\">";
    $this->salida .= "	  <tr class=\"modulo_list_oscuro\"><td>";
    $this->salida .= "	    <BR><table width=\"95%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "	    <tr class=\"modulo_table_list_title\"><td colspan=\"3\">DATOS ENTREGA</td></tr>";
    $this->salida .= "	    <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td class=\"".$this->SetStyle("fecha")."\" align=\"left\">FECHA Y HORA ENTREGA</td>";
		if(!$_REQUEST['fecha']){
      $_REQUEST['fecha']=date("d-m-Y");
		}
		$this->salida .= "      <td align=\"left\"><input type=\"text\" class=\"input-text\" size=\"10\" READONLY maxlength=\"10\" value=\"".$_REQUEST['fecha']."\" name=\"fecha\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('forma','fecha','-')."</td>" ;
		$this->salida.="        <td>";
    $this->salida.="          <table>";
    $this->salida.="              <tr>";
	  $this->salida.="              <td>";
		$this->salida.="              <select size=\"1\" name=\"hora\" class=\"select\" $desabilitado>";
		$this->salida.="              <option value = -1>Seleccione Hora </option>";
		if(!$_REQUEST['hora']){
      $_REQUEST['hora']=date('H');
		}
	  for ($j=0;$j<=23; $j++){
      if (($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($_REQUEST['hora']==$hora){
				  $this->salida.="        <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="        <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($_REQUEST['hora']==$j){
					$this->salida.="        <option selected value = $j>$j</option>";
				}else{
					$this->salida.="        <option value = $j>$j</option>";
				}
			}
    }
    $this->salida.="              </select>";
    $this->salida.="              </td>";
		$this->salida.="              <td>";
    $this->salida.="              <select size=\"1\"  name=\"minutos\" class=\"select\" $desabilitado>";
	  $this->salida.="              <option value = -1>Seleccione Minutos</option>";
		if(!$_REQUEST['minutos']){
      $_REQUEST['minutos']=date('i');
		}
		for ($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($_REQUEST['minutos']==$min){
					$this->salida.="         <option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="        <option value=\"$min\">0$j</option>";
				}
			}else{
			  if($_REQUEST['minutos']==$j){
					$this->salida.="        <option selected value=$j>$j</option>";
				}else{
					$this->salida.="        <option value=$j>$j</option>";
				}
			}
    }
    $this->salida.="              </select>";
    $this->salida.="              </td>";
    $this->salida.="              </tr>";
		$this->salida.="            </table>";
		$this->salida.="       </td>";
		$this->salida .= "	    </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("responsableEntregaCad")."\">FUNCIONARIO QUE<br>ENTREGA CADAVER</td>";
		$this->salida .= "	    <td colspan=\"2\">";
		$this->salida .= "     <select name=\"responsableEntregaCad\" class=\"select\">";
		$profesionales=$this->TotalProfesionalesFuncionarios();
		$this->BuscarProfesionlesEspecialistas($profesionales,'False',$_REQUEST['responsableEntregaCad']);
		$this->salida .= "      </select>";
    $this->salida .= "	    </td></tr>";
    $this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("otroFuncionario")."\">OTRO FUNCIONARIO QUE<br>ENTREGA CADAVER</td>";
    $this->salida .= "      <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"otroFuncionario\" maxlength=\"100\" value=\"".$_REQUEST['otroFuncionario']."\"></td></tr>";

		//$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("responsableReclamaCadaver")."\">PERSONA RECLAMA CADAVER </td><td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"responsableReclamaCadaver\" maxlength=\"60\" size=\"60\" value=\"".$_REQUEST['responsableReclamaCadaver']."\"></td></tr>";
    $this->salida .= "      <tr class=\"modulo_list_claro\"><td colspan=\"3\" align=\"left\"><label class=\"label\">OBSERVACIONES</label><BR><BR><textarea style=\"width:100%\" name=\"observaciones\" class=\"textarea\" rows=\"3\" cols=\"60\">".$_REQUEST['observaciones']."</textarea></td></tr>";
		$this->salida .= "	    </table><BR>";
    $this->salida .= "	  </td></tr>";

		$this->salida .= "   <tr><td align=\"center\" class=\"modulo_list_claro\">";
		$this->salida.="          <BR><table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida.="          <tr class=\"modulo_table_title\">";
		$this->salida.="          <td class=\"".$this->SetStyle("responsable")."\" class=\"label\" colspan=\"2\" width=\"80%\" valign=\"top\">PERSONA QUE RECLAMA EL CADAVER:</td>";
		$this->salida.="          </tr>";
		//$this->salida.="          <tr class=\"modulo_list_oscuro\">";
		//$this->salida.="          <td class=\"label\" valign=\"top\">PACIENTE</td>";

		if(!$responsable || $responsable==2){
      $v1='checked';
			$var2='disabled';
		}elseif($responsable==3){
      $v2='checked';
			$var3='disabled';
		}
		//$this->salida.="          <td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"1\" $v onclick=\"desabilitar(this.form,this.value)\"></td>";
		//$this->salida.="          </tr>";
		$this->salida.="          <tr class=\"modulo_list_oscuro\">";
		$this->salida.="          <td class=\"label\" valign=\"top\">OTRO RESPONSABLE</td>";
		$this->salida.="          <td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"2\" $v1 onclick=\"desabilitar(this.form,this.value)\"></td>";
		$this->salida.="          </tr>";
		$this->salida.="          <tr class=\"modulo_list_oscuro\">";
		$this->salida.="          <td class=\"label\" valign=\"top\">FUNCIONARIO DE LA INSTITUCION</td>";
		$this->salida.="          <td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"3\" $v2 onclick=\"desabilitar(this.form,this.value)\"></td>";
		$this->salida.="          </tr>";
		$this->salida.="          <tr class=\"modulo_list_oscuro\">";
		$this->salida.="          <td colspan=\"2\">";
		$this->salida.="          <BR><table width=\"80%\" border=\"0\" align=\"center\">";
		$this->salida.="          <tr class=\"modulo_table_list_title\">";
		$this->salida.="          <td colspan=\"3\">DATOS PERSONA </td>";
		$this->salida.="          </tr>";
		$this->salida.="          <tr class=\"modulo_list_claro\">";
		$this->salida.="          <td colspan=\"1\" width=\"25%\" class=\"".$this->SetStyle("parentesco")."\">PARENTESCO</td>";
		$this->salida.="          <td colspan=\"2\" width=\"55%\"><select name=\"parentesco\" class=\"select\" $var1 $var3>";
		$parentescos=$this->tiposParentescosPaciente();
		$this->MostrasSelect($parentescos,'False',$parentesco);
		$this->salida.="          </select></td>";
		$this->salida.="          </tr>";
		$this->salida.="          <tr class=\"modulo_list_claro\">";
		$this->salida.="          <td colspan=\"1\" class=\"".$this->SetStyle("identificacion")."\">No. IDENTIFICACION</td>";
		$this->salida .= "        <td colspan=\"1\" ><select name=\"tipo_id_funcionario\" class=\"select\" $var1 $var2>";
		$tipo_id=$this->tipo_id_paciente();
		$this->BuscarIdPaciente($tipo_id,$tipo_id_funcionario);
		$this->salida .= "        </select></td>";
		$this->salida.="          <td colspan=\"1\"><input type=\"text\" class=\"input-text\" name=\"funcionario_id\" size =\"20\" maxlength=\"20\" value=\"$funcionario_id\" $var1 $var2></td>";
		$this->salida.="          </tr>";
		$this->salida.="          <tr class=\"modulo_list_claro\">";
		$this->salida.="          <td colspan=\"1\" class=\"".$this->SetStyle("nombre_recibe")."\">NOMBRE</td>";
		$this->salida.="          <td colspan=\"2\" ><input type=\"text\" class=\"input-text\" name=\"nombre_recibe\" size =\"32\" maxlength=\"32\" value=\"$nombre_recibe\" $var1></td>";
		$this->salida.="          </tr>";
		$this->salida.="          <tr class=\"modulo_list_claro\">";
		$this->salida.="          <td colspan=\"1\" class=\"".$this->SetStyle("telefono")."\">TELEFONO</td>";
		$this->salida.="          <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"telefono\" size =\"20\" maxlength=\"20\" value=\"$telefono\" $var1 $var3></td>";
		$this->salida.="          </tr>";
		$this->salida.="          <tr class=\"modulo_list_claro\">";
		$this->salida.="          <td colspan=\"1\" class=\"".$this->SetStyle("observacion")."\">OBSERVACION</td>";
		$this->salida.="          <td colspan=\"2\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name=\"observacion\" cols = 60 rows = 3 $var1>".$observacion."</textarea></td>" ;
		//$this->salida.="<td><input type=\"text\" class=\"input-text\" name=\"observacion\" maxlength=\"32\" value=\"$observacion\" $var1></td>";
		$this->salida.="          </tr>";
		$this->salida.="          </table><BR>";
		$this->salida.="          </td>";
		$this->salida.="          </tr>";
		$this->salida.="          </table><BR>";
    $this->salida.="        </td></tr>";

    $this->salida .= "   <tr><td align=\"center\">";
		$this->salida .= "   <input type=\"submit\" name=\"Guardar\" value=\"GUARDAR\" class=\"input-submit\">";
		$this->salida .= "   <input type=\"submit\" name=\"Regresar\" value=\"REGRESAR\" class=\"input-submit\">";
		$this->salida .= "   </td></tr>";
    $this->salida .= "	  </table>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	 //aoltu
  /**
		* Se utilizada listar en el combo los diferentes tipo de identificacion de los pacientes
		* @access private
		* @return void
	*/
	function BuscarIdPaciente($tipo_id,$TipoId='')
	{
			foreach($tipo_id as $value=>$titulo)
			{
					if($value==$TipoId)
					{
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
					else
					{
							$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
			}
	}

	/**
* Funcion que se encarga de listar los nombres de los profesionales especialistas y visualizarlos
* @return array
* @param array codigos y valores de los profesionales de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los profesionales
* @param string elemento seleccionado en el objeto donde se imprimen los profesionales
*/
	function BuscarProfesionlesEspecialistas($profesionales,$Seleccionado='False',$Profesionales=''){

		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0;$i<sizeof($profesionales);$i++){
				  $value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
					$titulo=$profesionales[$i]['nombre'];
					if($value==$Profesionales){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  for($i=0;$i<sizeof($profesionales);$i++){
			    $value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
					$titulo=$profesionales[$i]['nombre'];
				  if($value==$Profesionales){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}

	function ConsultaEntregaCadaver($cadaverId,$TipoId,$pacienteId,$nombre,$fechaNac,$nomprofesional,$certificado_defuncion,
	$fecha,$Hora,$Minutos,$tipoIdEntregaCadaver,$EntregaCadaver,$NombreProf,$ReclamaCadaver,$Observaciones,$otro_funcionario,$otroResponsable,
	$parentesco,$nomresponsable,$telefono,$observacion,$sw_tipo_persona,$tipo_id_funcionario,$funcionario_id){

    $accion=ModuloGetURL('app','Procedimientos_Cadaveres','user','RegresaConsultaEntregaCadaver');
		$this->salida .= ThemeAbrirTabla("CONSULTA ENTREGA CADAVERES");
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table width=\"70%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS PACIENTE</legend>";
		$this->salida .= "  <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$EdadArr=CalcularEdad($fechaNac,$FechaFin);
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"25%\"  class=\"label\">IDENTIFICACION</td>";
		$this->salida .= "  <td>$TipoId $pacienteId</td>";
		$this->salida .= "  <td width=\"25%\"  class=\"label\">EDAD</td>";
		$this->salida .= "  <td>".$EdadArr['edad_aprox']."</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"25%\" class=\"label\">NOMBRE</td>";
		$this->salida .= "  <td colspan=\"3\">$nombre</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"25%\" class=\"label\">ACTA LEVANTAMEINTO/ CERTIFICADO DEFUNCION</td>";
		$this->salida .= "  <td colspan=\"3\">$certificado_defuncion</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"25%\" class=\"label\">PROFESIONAL QUE <br>ENTREGO CADAVER<br>A PATOLOGIA</td>";
		if(!empty($nomprofesional)){
      $this->salida .= "  <td colspan=\"3\">$nomprofesional</td>";
		}else{
      $this->salida .= "  <td colspan=\"3\">$otroResponsable</td>";
		}
		$this->salida .= "  </tr>";
    $this->salida .= "  </table>";
		$this->salida .= "  </fieldset></td></tr>";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "	  <table width=\"70%\" border=\"0\" class=\"normal_10\" align=\"center\">";
    $this->salida .= "	  <tr class=\"modulo_list_oscuro\"><td>";
    $this->salida .= "	    <BR><table width=\"95%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "	    <tr class=\"modulo_table_list_title\"><td colspan=\"2\">DATOS ENTREGA</td></tr>";
    $this->salida .= "	    <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td width=\"40%\" align=\"left\" class=\"label\">FECHA Y HORA ENTREGA</td>";
    $this->salida .= "      <td align=\"left\">$fecha $Hora:$Minutos</td>";
		$this->salida .= "	    </tr>";
		$this->salida .= "	    <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td align=\"left\" class=\"label\">PROFESIONAL QUE ENTREGO EL CADAVER</td>";
		if(!empty($tipoIdEntregaCadaver) && !empty($EntregaCadaver) && !empty($NombreProf)){
		  $this->salida .= "      <td align=\"left\">$tipoIdEntregaCadaver $EntregaCadaver&nbsp&nbsp&nbsp;$NombreProf</td>";
		}else{
      $this->salida .= "      <td align=\"left\">$otro_funcionario</td>";
		}
		$this->salida .= "	    </tr>";
    $this->salida .= "	    <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td align=\"left\" class=\"label\">OBSERVACIONES</td>";
    $this->salida .= "      <td align=\"left\">$Observaciones</td>";
		$this->salida .= "	    </tr>";
    $this->salida .= "	    </table><br>";
		$this->salida .= "	   <tr class=\"modulo_list_claro\"><td>";
    $this->salida .= "	      <BR><table width=\"95%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "	      <tr class=\"modulo_table_list_title\"><td colspan=\"2\">RESPONSABLE CADAVER</td></tr>";
		if($sw_tipo_persona==2){
      $this->salida .= "	      <tr class=\"modulo_list_oscuro\"><td width=\"35%\" nowrap class=\"label\">PARENTESCO</td><td>$parentesco</td></tr>";
			$this->salida .= "	      <tr class=\"modulo_list_oscuro\"><td width=\"35%\" nowrap class=\"label\">NOMBRE</td><td>$nomresponsable</td></tr>";
			$this->salida .= "	      <tr class=\"modulo_list_oscuro\"><td width=\"35%\" nowrap class=\"label\">TELEFONO</td><td>$telefono</td></tr>";
			$this->salida .= "	      <tr class=\"modulo_list_oscuro\"><td width=\"35%\" nowrap class=\"label\">OBSERVACIONES</td><td>$observacion</td></tr>";
		}elseif($sw_tipo_persona==3){
      $this->salida .= "	      <tr class=\"modulo_list_oscuro\"><td width=\"35%\" nowrap class=\"label\">FUNCIONARIO</td><td>$tipo_id_funcionario - $funcionario_id</td></tr>";
			$this->salida .= "	      <tr class=\"modulo_list_oscuro\"><td width=\"35%\" nowrap class=\"label\">NOMBRE</td><td>$nomresponsable</td></tr>";
			$this->salida .= "	      <tr class=\"modulo_list_oscuro\"><td width=\"35%\" nowrap class=\"label\">OBSERVACIONES</td><td>$observacion</td></tr>";
		}
    $this->salida .= "	      </table><br>";
    $this->salida .= "	   </td></tr>";
    $this->salida .= "     <tr><td align=\"center\">";
		$this->salida .= "     <input type=\"submit\" name=\"Regresar\" value=\"REGRESAR\" class=\"input-submit\">";
		$this->salida .= "     </td></tr>";
    $this->salida .= "	  </table>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function InformeResultadoPatologico($observacionesInforme,$firma,$consulta,$profesional){

    if($consulta==1){
      $sololectura='readonly';
			$inabilitado='disabled';
		}
		$this->salida .= ThemeAbrirTabla('INFORME DE PATOLOGIA');
		$action=ModuloGetURL('app','Patologia','user','GuardarInformePatologico',array("Modify"=>$Modify));
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$datos=$_SESSION['INFORME_PATOLOGICO'];
		foreach($datos as $solicitud =>$vector){
      foreach($vector as $DatoSolicitud =>$vectorTejidos){
			(list($grupoTipoCargo,$TipoCargo,$sw_prefijo,$prefijo,$nombreInfo)=explode('||//',$DatoSolicitud));
      $this->salida .= "    <input type=\"hidden\" name=\"filtrogrupoTipoCargo\" value=\"$grupoTipoCargo\">";
			$this->salida .= "    <input type=\"hidden\" name=\"filtroTipoCargo\" value=\"$TipoCargo\">";
			$this->salida .= "	  <table width=\"85%\" border=\"0\" class=\"normal_10\" align=\"center\">";
			$this->salida .= "    <tr>";
			$this->salida .= "      <td width=\"50%\" valign=\"top\">";
			$this->salida .= "      <fieldset><legend class=\"field\">DATOS PACIENTE Y SOLICITUD</legend>";
			$this->salida .= "      <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$datos=$this->DatosSolicitud($solicitud);
			$this->salida .= "		   <td class=\"label\">IDENTIFICACION</td>";
			$this->salida .= "		   <td>".$datos['tipo_id_paciente']." ".$datos['paciente_id']."</td>";
			$this->salida .= "		   <td class=\"label\">EDAD</td>";
			$EdadArr=CalcularEdad($datos['fecha_nacimiento'],$FechaFin);
			$this->salida .= "		   <td>".$EdadArr['edad_aprox']."</td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "		   <td class=\"label\">NOMBRES</td>";
			$this->salida .= "		   <td colspan=\"3\">".$datos['nombrepac']."</td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "		  <td class=\"label\">No. SOLICITUD</td>";
			$this->salida .= "		  <td>$solicitud</td>";
			$this->salida .= "		  <td class=\"label\">FECHA</td>";
			$this->salida .= "		  <td>".$datos['fecha']."</td>";
			$this->salida .= "      </tr>";
			$this->salida .= "	    </table>";
			$this->salida .= "		  </fieldset></td>";
			$this->salida .= "      <td width=\"35%\" valign=\"top\">";
			$this->salida .= "      <fieldset><legend class=\"field\">TEJIDOS</legend>";
			$this->salida .= "      <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
      foreach($vectorTejidos as $tej=>$valor){
			  (list($codigo,$nombre)=explode('||//',$tej));
				$this->salida .= "      <tr class=\"modulo_list_claro\">";
				$this->salida .= "      <td>".$nombre."</td>";
				$this->salida .= "      </tr>";
			}
			$this->salida .= "	    </table>";
			$this->salida .= "		  </fieldset></td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		</table>";
		}
		}
    $this->salida .= "    <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"2\">INFORME DE $nombreInfo</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "    <td class=\"label\">OBSERVACIONES</td>";
		$this->salida .= "    <td><textarea name=\"observacionesInforme\" class =\"textarea\" rows =\"12\" cols =\"100\" $sololectura>$observacionesInforme</textarea></td>";
    $this->salida .= "    </tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$procedimientos=$_SESSION['PATOLOGIA']['PROCEDIMIENTOS'];
    $this->salida .= "    <td class=\"label\">PROCEDIMIENTOS</td>";
    $this->salida .= "    <td>";
    $this->salida .= "        <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		foreach($procedimientos as $codigo=>$nombre){
		  if($codigo){
      $this->salida .= "        <tr class=\"modulo_list_oscuro\">";
			$this->salida .= "        <td>$nombre</td>";
			if(!$consulta){
			$actionElimina=ModuloGetURL('app','Patologia','user','EliminaProcPatologiaResultado',array("codigo"=>$codigo,"observacionesInforme"=>$observacionesInforme,"firma"=>$firma));
			$this->salida .= "        <td width=\"5%\" nowrap align=\"center\"><a href=\"$actionElimina\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
			}
			$this->salida .= "        </tr>";
			}
		}
		if(!$consulta){
		$this->salida .= "          <tr class=\"modulo_list_oscuro\"><td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"buscarPro\" value=\"BUSCAR\"></td></tr>";
		}
		$this->salida .= "        </table><BR>";
    $this->salida .= "    </td>";
    $this->salida .= "    </tr>";

		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$diagnosticos=$_SESSION['PATOLOGIA']['RESULTADO'];
    $this->salida .= "    <td class=\"label\">DIAGNOSTICOS</td>";
    $this->salida .= "    <td>";
    $this->salida .= "        <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		foreach($diagnosticos as $codigo=>$nombre){
		  if($codigo){
      $this->salida .= "        <tr class=\"modulo_list_oscuro\">";
			$this->salida .= "        <td>$nombre</td>";
			if(!$consulta){
			$actionElimina=ModuloGetURL('app','Patologia','user','EliminaDiagPatologiaResultado',array("codigo"=>$codigo,"observacionesInforme"=>$observacionesInforme,"firma"=>$firma));
			$this->salida .= "        <td width=\"5%\" nowrap align=\"center\"><a href=\"$actionElimina\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
			}
			$this->salida .= "        </tr>";
			}
		}
		if(!$consulta){
		$this->salida .= "          <tr class=\"modulo_list_oscuro\"><td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"BuscarDiag\" value=\"BUSCAR\"></td></tr>";
		}
		$this->salida .= "        </table><BR>";
    $this->salida .= "    </td>";
    $this->salida .= "    </tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		if($firma==1){
		  $varc='checked';
    //$this->salida .= "    <td colspan=\"2\" align=\"center\" class=\"label\">FIRMADO POR:&nbsp&nbsp&nbsp&nbsp;$nombreProfesional</td>";
		}
		if(!$consulta){
    $this->salida .= "    <td colspan=\"2\" align=\"center\" class=\"label\"><input type=\"checkbox\" name=\"firma\" value=\"1\" $inabilitado $varc>&nbsp;APROBACION DEL RESULTADO AQUI REGISTRADO</td>";
		}else{
		$this->salida .= "    <td colspan=\"2\" align=\"center\" class=\"label\">FIRMADO POR:&nbsp&nbsp&nbsp&nbsp;$profesional</td>";
		}
    $this->salida .= "    </tr>";
		$this->salida .= "    <tr><td align=\"center\" colspan=\"2\">";
		if(!$consulta){
		$this->salida .= "    <input type=\"submit\" value=\"GUARDAR\" name=\"Guardar\" class=\"input-submit\">";
		}
		$this->salida .= "    <input type=\"submit\" value=\"REGRESAR\" name=\"Regresar\" class=\"input-submit\">";
		$this->salida .= "    </td></tr>";
    $this->salida .= "		</table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaBusquedaProcedimientos($cadaverId,$informe,$TipoId,$pacienteId,$nombre,
		$fechaNac,$Destino,$observacionesInforme,$firma,$patologoProfe){

    $this->salida .= ThemeAbrirTabla('BUSCADOR PROCEDIMIENTO PATOLOGICO');
    $action=ModuloGetURL('app','Procedimientos_Cadaveres','user','SeleccionProcedimiento',array("cadaverId"=>$cadaverId,"informe"=>$informe,"TipoId"=>$TipoId,"pacienteId"=>$pacienteId,"nombre"=>$nombre,
		"fechaNac"=>$fechaNac,"Destino"=>$Destino,"observacionesInforme"=>$observacionesInforme,"firma"=>$firma,"patologoProfe"=>$patologoProfe));
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "	  <table width=\"85%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"5\">PARAMENTROS DE BUSQUEDA</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td class=\"label\">PROCEDIMIENTO</td>";
    $this->salida .= "    <td><input size=\"80\" type=\"text\" name=\"procedimientoBus\" value=\"".$_REQUEST['procedimientoBus']."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td class=\"label\">CODIGO</td>";
		$this->salida .= "    <td><input type=\"text\" size=\"10\" name=\"codigoBus\" value=\"".$_REQUEST['codigoBus']."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td><input type=\"submit\" name=\"buscar\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
		$this->salida .= "	  </table><BR>";
		$cups=$this->HallarCupsPatologia($_REQUEST['codigoBus'],$_REQUEST['procedimientoBus']);
		if($cups){
      $this->salida .= "	   <table width=\"80%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
			$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td>CODIGO</td>";
			$this->salida .= "	   <td>DESCRIPCION</td>";
			$this->salida .= "	   <td>&nbsp;</td>";
			$y=0;
			for($i=0;$i<sizeof($cups);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "    <tr class=\"$estilo\">";
				$this->salida .= "    <td>".$cups[$i]['cargo']."</td>";
				$this->salida .= "    <td>".$cups[$i]['descripcion']."</td>";
				$action=ModuloGetURL('app','Procedimientos_Cadaveres','user','SeleccionarProcedimientoPatologia',array("codigoProcedimiento"=>$cups[$i]['cargo'],"nombreProcedimiento"=>$cups[$i]['descripcion'],
				"cadaverId"=>$cadaverId,"informe"=>$informe,"TipoId"=>$TipoId,"pacienteId"=>$pacienteId,"nombre"=>$nombre,"fechaNac"=>$fechaNac,"Destino"=>$Destino,"observacionesInforme"=>$observacionesInforme,
				"firma"=>$firma,"patologoProfe"=>$patologoProfe));
				$this->salida .= "    <td width=\"5%\" nowrap align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
        $this->salida .= "    </tr>";
			}
      $this->salida .= "	   </table>";
			$this->salida .=$this->RetornarBarra(3);
		}else{
      $this->salida .= "	   <BR><table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida .= "     <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "	   </table><br>";
		}
		$this->salida .= "	   <BR><table width=\"80%\" border=\"0\" align=\"center\">";
    $this->salida .= "     <tr><td align=\"center\"><input type=\"submit\" name=\"Salir\" value=\"SALIR\" class=\"input-submit\"></td></tr>";
    $this->salida .= "	   </table>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
* Funcion que se encarga de listar los nombres de los profesionales especialistas y visualizarlos
* @return array
* @param array codigos y valores de los profesionales de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los profesionales
* @param string elemento seleccionado en el objeto donde se imprimen los profesionales
*/
	function BuscarProfesionlesPatologos($profesionales,$Seleccionado='False',$Profesionales=''){

		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0;$i<sizeof($profesionales);$i++){
				  $value=$profesionales[$i]['usuario_id'];
					$titulo=$profesionales[$i]['nombre'];
					if($value==$Profesionales){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  for($i=0;$i<sizeof($profesionales);$i++){
			    $value=$profesionales[$i]['usuario_id'];
					$titulo=$profesionales[$i]['nombre'];
				  if($value==$Profesionales){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}

	function FormaBusquedaProcedimientosInicial($Documento,$TipoDocumento,$vec){

    $this->salida .= ThemeAbrirTabla('BUSCADOR PROCEDIMIENTO PATOLOGICO');
    $action=ModuloGetURL('app','Procedimientos_Cadaveres','user','SeleccionProcedimientoInicial',$vec);
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "	  <table width=\"85%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"5\">PARAMENTROS DE BUSQUEDA</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td class=\"label\">PROCEDIMIENTO</td>";
    $this->salida .= "    <td><input size=\"80\" type=\"text\" name=\"procedimientoBus\" value=\"".$_REQUEST['procedimientoBus']."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td class=\"label\">CODIGO</td>";
		$this->salida .= "    <td><input type=\"text\" size=\"10\" name=\"codigoBus\" value=\"".$_REQUEST['codigoBus']."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td><input type=\"submit\" name=\"buscar\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
		$this->salida .= "	  </table><BR>";
		$cups=$this->HallarCupsPatologia($_REQUEST['codigoBus'],$_REQUEST['procedimientoBus']);
		if($cups){
      $this->salida .= "	   <table width=\"80%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
			$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td>CODIGO</td>";
			$this->salida .= "	   <td>DESCRIPCION</td>";
			$this->salida .= "	   <td>&nbsp;</td>";
			$y=0;
			for($i=0;$i<sizeof($cups);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "    <tr class=\"$estilo\">";
				$this->salida .= "    <td>".$cups[$i]['cargo']."</td>";
				$this->salida .= "    <td>".$cups[$i]['descripcion']."</td>";
				$vec['codigoProcedimiento']=$cups[$i]['cargo'];
				$vec['nombreProcedimiento']=$cups[$i]['descripcion'];
				$action=ModuloGetURL('app','Procedimientos_Cadaveres','user','SeleccionarProcedimientoPatologiaInicial',$vec);
				$this->salida .= "    <td width=\"5%\" nowrap align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
        $this->salida .= "    </tr>";
			}
      $this->salida .= "	   </table>";
			$this->salida .=$this->RetornarBarra(4);
		}else{
      $this->salida .= "	   <BR><table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida .= "     <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "	   </table><br>";
		}
		$this->salida .= "	   <BR><table width=\"80%\" border=\"0\" align=\"center\">";
    $this->salida .= "     <tr><td align=\"center\"><input type=\"submit\" name=\"Salir\" value=\"SALIR\" class=\"input-submit\"></td></tr>";
    $this->salida .= "	   </table>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}



//pg_dump -u -s SIIS2 > /home/lorena/Desktop/alex.sql
}//fin clase user
?>

