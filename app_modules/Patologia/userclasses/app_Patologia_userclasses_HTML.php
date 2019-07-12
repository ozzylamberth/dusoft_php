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
class app_Patologia_userclasses_HTML extends app_Patologia_user
{
	/**
	*Constructor de la clase app_ProgramacionQX_user_HTML
	*El constructor de la clase app_ProgramacionQX_user_HTML se encarga de llamar
	*a la clase app_ProgramacionQX_user quien se encarga de el tratamiento
	*de la base de datos.
	*/

  function app_Patologia_user_HTML()
	{
		$this->salida='';
		$this->app_Patologia_user();
		return true;
	}

/**
* Funcion que muestra las distintas opciones del menu para el usuario
* @return boolean
*/
	function MenuPrincipal(){
    $this->salida .= ThemeAbrirTabla('PATOLOGIA');		//$this->salida .= "			      <br><br>";
    $action=ModuloGetURL('system','Menu','user','main');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "			<table width=\"35%\" align=\"center\" class=\"modulo_table_list\">";
		$action1=ModuloGetURL('app','Patologia','user','PedirIdentificacionPaciente');
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action1\" class=\"link\"><b>SOLICITUD PATOLOGICA</b></a></td></tr>";
		$action2=ModuloGetURL('app','Patologia','user','RecepcionTejidoPatologico');
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action2\" class=\"link\"><b>RECEPCION TEJIDO PATOLOGICO</b></a></td></tr>";
    //$tipoUser=$this->Tipo_Usuario_Log();
		//if($tipoUser==1){
		$action3=ModuloGetURL('app','Patologia','user','LlamaCreacionInformesPatologia');
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action3\" class=\"link\"><b>INFORMES RESULTADOS PATOLOGIAS</b></a></td></tr>";
		//}
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

	function PedirIdentificacionPaciente(){

		$this->salida .= ThemeAbrirTabla('IDENTIFICACION DEL PACIENTE');
    $action=ModuloGetURL('app','Patologia','user','PedirDatosPaciente');
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
		$this->salida .= "		 <td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"$Documento\"></td>";
		$this->salida .= "		 </tr>";
    $this->salida .= "    </table><BR>";
    $this->salida .= "</td></tr>";
		$this->salida .= "</table>";
		$this->salida .= "   <table width=\"90%\" border=\"0\" align=\"center\"class=\"normal_10\" >";
    $this->salida .= "		<tr><td align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
		$this->salida .= "</form>";
		$action1=ModuloGetURL('app','Patologia','user','MenuPrincipal');
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

	function SolicitudPatologia($TipoDocumento,$Documento,$departamento,$ubicacionPaciente,$responsableSolicitud,$tratamientos,$Solicitud,$procemiento,$codigoPro,$observaciones,$nombreDiagnostico,$codigoDiag,$nombreTejido,$codigoTejido,$origenSolicitud){

    $this->salida .= ThemeAbrirTabla('SOLICITUD DE PATOLOGIA');
    $action=ModuloGetURL('app','Patologia','user','GuardarSolicitudPatologia');
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "	  <table width=\"50%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">PACIENTE</legend>";
		$this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td width=\"25%\"  class=\"label\">IDENTIFICACION</td>";
		$this->salida .= "		<td>$TipoDocumento $Documento</td>";
    $this->salida .= "		<input type=\"hidden\" name=\"TipoDocumento\" value=\"$TipoDocumento\">";
		$this->salida .= "		<input type=\"hidden\" name=\"Documento\" value=\"$Documento\">";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td width=\"25%\" class=\"label\">NOMBRE</td>";
		$nombre=$this->nombrePaciente($TipoDocumento,$Documento);
		$this->salida .= "		<td>".$nombre['nombre']."</td>";
		$this->salida .= "		</tr>";
    $this->salida .= "		</table>";
		$this->salida .= "		</fieldset></td></tr>";
		$this->salida .= "      <tr><td align=\"center\">";
		$this->salida .=        $this->SetStyle("MensajeError");
		$this->salida .= "      </td></tr>";
		$this->salida .= "		</table><br>";
		$this->salida .= "<table width=\"85%\" align=\"center\" border=\"0\">\n";
    $this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
    $this->salida .= "      <BR><table width=\"95%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"4\">DATOS DE LA SOLICITUD</td>";
    $this->salida .= "      </tr>";
    $this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"label\">DEPARTAMENTO PROCEDENCIA</td><td><select name=\"departamento\" class=\"select\">";
		$departamentos=$this->tiposdepartamentos();
		$this->MostrasSelect($departamentos,'False',$departamento);
		$this->salida .= "      </select></td>";
    $this->salida .= "      <td class=\"".$this->SetStyle("origenSolicitud")."\">OTRO ORIGEN PROCEDENCIA</td><td><input type=\"text\" class=\"input-text\" name=\"origenSolicitud\" maxlength=\"100\" value=\"$origenSolicitud\"></td>";
    $this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("ubicacionPaciente")."\">UBICACION PACIENTE </td><td colspan=\"3\"><input type=\"text\" class=\"input-text\" name=\"ubicacionPaciente\" maxlength=\"32\" value=\"$ubicacionPaciente\"></td></tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("responsableSolicitud")."\">RESPONSABLE SOLICITUD </td><td colspan=\"3\"><input type=\"text\" class=\"input-text\" name=\"responsableSolicitud\" size=\"40\" value=\"$responsableSolicitud\"></td></tr>";

		$this->salida .= "      <tr class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("Solicitud")."\">SOLICITUD </td><td colspan=\"3\"><input type=\"text\" class=\"input-text\" name=\"Solicitud\" size=\"85\" maxlength=\"150\" value=\"$Solicitud\"></td></tr>";
		/*$this->salida .= "      <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td class=\"".$this->SetStyle("procedimiento")."\">PROCEDIMIENTO</td>";
    $this->salida .= "      <td colspan=\"3\"><input readonly type=\"text\" size=\"85\" name=\"procedimiento\" value=\"$procemiento\">";
    $this->salida .= "      <input type=\"hidden\" name=\"codigoPro\" value=\"$codigoPro\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"buscarPro\" value=\"BUSCAR\">";
    $this->salida .= "      </tr>";*/
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td colspan=\"4\">";
    $this->salida .= "          <table width=\"85%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida .= "          <td align=\"left\"><label class=\"label\">TRATAMIENTOS EFECTUADOS Y TIEMPO DE DURACION</label><BR><BR><textarea style=\"width:100%\" name=\"tratamientos\" class=\"textarea\" rows=\"3\" cols=\"60\">$tratamientos</textarea></td>";
		$this->salida .= "          </tr>";
    $this->salida .= "          </table>";
    $this->salida .= "      </td></tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\"><td colspan=\"4\">";
    $this->salida .= "          <table width=\"85%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida .= "          <td align=\"left\"><label class=\"label\">OBSERVACIONES</label><BR><BR><textarea style=\"width:100%\" name=\"observaciones\" class=\"textarea\" rows=\"3\" cols=\"60\">$observaciones</textarea></td>";
		$this->salida.="            </tr>";
    $this->salida .= "          </table>";
		$this->salida .= "      </td></tr>";
    $this->salida .= "      </table>";
		$this->salida .= "</td></tr>";
	  $this->salida .= "<tr><td class=\"modulo_list_claro\">";
		$this->salida .= "      <BR><table width=\"95%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"2\">DIAGNOSTICOS</td>";
		$this->salida .= "      </tr>";
    $diagnosticos=$_SESSION['PATOLOGIA']['DIAGNOSTICOS'];
		if($diagnosticos){
			$this->salida .= "    <tr class=\"modulo_list_oscuro\"><td align=\"center\" colspan=\"2\">";
			$this->salida .= "      <BR><table width=\"95%\" align=\"center\" border=\"0\">\n";
			$this->salida .= "      <tr class=\"modulo_table_title\">";
			$this->salida .= "      <td>DIAGNOSTICOS INSERTADOS</td>";
			$this->salida .= "      <td>&nbsp;</td>";
			$this->salida .= "      </tr>";
			foreach($diagnosticos as $codigo=>$nombre){
				$this->salida .= "      <tr class=\"modulo_list_claro\">";
				$this->salida .= "      <td>$nombre</td>";
				$actionElimina=ModuloGetURL('app','Patologia','user','EliminaDiagnosticoPatologia',array("codigoDiagnostico"=>$codigo,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,
				"departamento"=>$departamento,"ubicacionPaciente"=>$ubicacionPaciente,"responsableSolicitud"=>$responsableSolicitud,"tratamientos"=>$tratamientos,"Solicitud"=>$Solicitud,"procemiento"=>$procemiento,"codigoPro"=>$codigoPro,
				"observaciones"=>$observaciones,"origenSolicitud"=>$origenSolicitud));
				$this->salida .= "      <td width=\"5%\" nowrap align=\"center\"><a href=\"$actionElimina\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
				$this->salida .= "      </tr>";
			}
			$this->salida .= "      </table><br>";
			$this->salida .= "    </td></tr>";
		}
		$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      <td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"buscarDiagn\" value=\"BUSCAR\"></td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "      <BR><table width=\"95%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td colspan=\"2\">TEJIDOS</td>";
		$this->salida .= "      </tr>";
		$tejidos=$_SESSION['PATOLOGIA']['TEJIDOS'];
		if($tejidos){
			$this->salida .= "    <tr class=\"modulo_list_claro\"><td align=\"center\" colspan=\"2\">";
			$this->salida .= "      <BR><table width=\"95%\" align=\"center\" border=\"0\">\n";
			$this->salida .= "      <tr class=\"modulo_table_title\">";
			$this->salida .= "      <td>TEJIDOS INSERTADOS</td>";
			$this->salida .= "      <td>&nbsp;</td>";
			$this->salida .= "      </tr>";
			foreach($tejidos as $codigo=>$nombre){
				$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
				$this->salida .= "      <td>$nombre</td>";
				$actionEliminaT=ModuloGetURL('app','Patologia','user','EliminaTejidoPatologia',array("codigoTejido"=>$codigo,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,
				"departamento"=>$departamento,"ubicacionPaciente"=>$ubicacionPaciente,"responsableSolicitud"=>$responsableSolicitud,"tratamientos"=>$tratamientos,"Solicitud"=>$Solicitud,"procemiento"=>$procemiento,"codigoPro"=>$codigoPro,
				"observaciones"=>$observaciones,"origenSolicitud"=>$origenSolicitud));
				$this->salida .= "      <td width=\"5%\" nowrap align=\"center\"><a href=\"$actionEliminaT\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
				$this->salida .= "      </tr>";
			}
			$this->salida .= "      </table><br>";
			$this->salida .= "    </td></tr>";
		}
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"buscarTejido\" value=\"BUSCAR\"></td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr><td>";
		$this->salida .= "      <BR><table width=\"95%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "      <tr><td align=\"center\" colspan=\"4\">";
		$this->salida .= "      <input type=\"submit\" name=\"Guardar\" value=\"GUARDAR\" class=\"input-submit\">";
		$this->salida .= "      <input type=\"submit\" name=\"Regresar\" value=\"MENU\" class=\"input-submit\">";
    $this->salida .= "      </td></tr>";
		$this->salida .= "      </table>";
		$this->salida .= "</td></tr>";
		$this->salida .= "</table>";
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


	function FormaBusquedaProcedimientos($observacionesInforme,$firma,$filtrogrupoTipoCargo,$filtroTipoCargo,$patologoProfe){

    $this->salida .= ThemeAbrirTabla('BUSCADOR PROCEDIMIENTO PATOLOGICO');
    $action=ModuloGetURL('app','Patologia','user','SeleccionProcedimientoBusqueda',array("observacionesInforme"=>$observacionesInforme,"firma"=>$firma,
		"filtrogrupoTipoCargo"=>$filtrogrupoTipoCargo,"filtroTipoCargo"=>$filtroTipoCargo,"patologoProfe"=>$patologoProfe));
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
				$action=ModuloGetURL('app','Patologia','user','SeleccionarProcedimiento',array("cargoSelect"=>$cups[$i]['cargo'],"descripcionSelect"=>$cups[$i]['descripcion'],
				"observacionesInforme"=>$observacionesInforme,"firma"=>$firma,"patologoProfe"=>$patologoProfe));
				$this->salida .= "    <td width=\"5%\" nowrap align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
        $this->salida .= "    </tr>";
			}
      $this->salida .= "	   </table>";
			$this->salida .=$this->RetornarBarra(1);
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
      $accion=ModuloGetURL('app','Patologia','user','LlamaFormaBusquedaProcedimientos',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
			"observacionesInforme"=>$_REQUEST['observacionesInforme'],"firma"=>$_REQUEST['firma'],"filtrogrupoTipoCargo"=>$_REQUEST['filtrogrupoTipoCargo'],"filtroTipoCargo"=>$_REQUEST['filtroTipoCargo'],"patologoProfe"=>$_REQUEST['patologoProfe'],
			"procedimientoBus"=>$_REQUEST['procedimientoBus'],"codigoBus"=>$_REQUEST['codigoBus']));
		}elseif($origen==2){
		  $accion=ModuloGetURL('app','Patologia','user','LlamaFormaBuscadorDiagnostico',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"departamento"=>$_REQUEST['departamento'],"ubicacionPaciente"=>$_REQUEST['ubicacionPaciente'],
			"responsableSolicitud"=>$_REQUEST['responsableSolicitud'],"procedimiento"=>$_REQUEST['procedimiento'],"codigoPro"=>$_REQUEST['codigoPro'],"tratamientos"=>$_REQUEST['tratamientos'],"Solicitud"=>$_REQUEST['Solicitud'],"observaciones"=>$_REQUEST['observaciones'],"origenSolicitud"=>$_REQUEST['origenSolicitud'],"procedimientoBus"=>$_REQUEST['procedimientoBus'],
			"codigoBus"=>$_REQUEST['codigoBus']));
		}elseif($origen==7){
      $accion=ModuloGetURL('app','Patologia','user','LlamaFormaBuscadorDiagnosticoResultado',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
			"observacionesInforme"=>$_REQUEST['observacionesInforme'],"firma"=>$_REQUEST['firma'],"patologoProfe"=>$_REQUEST['patologoProfe'],
			"procedimientoBus"=>$_REQUEST['procedimientoBus'],"codigoBus"=>$_REQUEST['codigoBus']));
		}elseif($origen==3){
      $accion=ModuloGetURL('app','Patologia','user','LlamaBucadorTejidosPatologicos',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
			"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"departamento"=>$_REQUEST['departamento'],"ubicacionPaciente"=>$_REQUEST['ubicacionPaciente'],
	    "responsableSolicitud"=>$_REQUEST['responsableSolicitud'],"tratamientos"=>$_REQUEST['tratamientos'],"Solicitud"=>$_REQUEST['Solicitud'],"procedimiento"=>$_REQUEST['procedimiento'],"codigoPro"=>$_REQUEST['codigoPro'],"observaciones"=>$_REQUEST['observaciones'],"origenSolicitud"=>$_REQUEST['origenSolicitud'],
			"procedimientoBus"=>$_REQUEST['procedimientoBus'],"codigoBus"=>$_REQUEST['codigoBus']));
		}elseif($origen==6){
      $accion=ModuloGetURL('app','Patologia','user','LlamaBucadorTejidosPatologicosBuscador',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
			"destino"=>$_REQUEST['destino'],"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"departamento"=>$_REQUEST['departamento'],"fecha"=>$_REQUEST['fecha'],"procedimiento"=>$_REQUEST['procedimiento'],
			"codigoPro"=>$_REQUEST['codigoPro'],"tejido"=>$_REQUEST['tejido'],"codigoTejido"=>$_REQUEST['codigoTejido']));
		}elseif($origen==4){
      $accion=ModuloGetURL('app','Patologia','user','SeleccionProcedimientoBuscador',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
			"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"departamento"=>$_REQUEST['departamento'],"fecha"=>$_REQUEST['fecha'],
			"procedimiento"=>$_REQUEST['procedimiento'],"codigoPro"=>$_REQUEST['codigoPro'],"tejido"=>$_REQUEST['tejido'],"codigoTejido"=>$_REQUEST['codigoTejido'],
			"codigoBus"=>$_REQUEST['codigoBus'],"procedimientoBus"=>$_REQUEST['procedimientoBus']));
		}elseif($origen==5){
      $accion=ModuloGetURL('app','Patologia','user','SeleccionarProcedimiento',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
			"bandera"=>1,"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"departamento"=>$_REQUEST['departamento'],"fecha"=>$_REQUEST['fecha'],
		  "codigoPro"=>$_REQUEST['codigoPro'],"procedimiento"=>$_REQUEST['procedimiento'],"tejido"=>$_REQUEST['tejido'],"codigoTejido"=>$_REQUEST['codigoTejido']));
		}elseif($origen==8){
      $accion=ModuloGetURL('app','Patologia','user','LlamaCreacionInformesPatologia',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
			"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"noSolicitud"=>$_REQUEST['noSolicitud'],"fecha"=>$_REQUESt['fecha']));
		}elseif($origen==9){
      $accion=ModuloGetURL('app','Patologia','user','SeleccionProcedimientosBusqueda',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],
			"solicitud"=>$_REQUEST['solicitud'],"tejidoId"=>$_REQUEST['tejidoId'],"nomTejido"=>$_REQUEST['nomTejido'],"tipoId"=>$_REQUEST['tipoId'],
		  "PacienteId"=>$_REQUEST['PacienteId'],"nombre"=>$_REQUEST['nombre'],"fecha"=>$_REQUEST['fecha'],"modificacion"=>$_REQUEST['modificacion'],"inadecuada"=>$_REQUEST['inadecuada'],"observaciones"=>$_REQUEST['observaciones'],"buscar"=>1,
			"procedimientoBus"=>$_REQUEST['procedimientoBus'],"codigoBus"=>$_REQUEST['codigoBus']));
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

	function FormaBuscadorDiagnostico($TipoDocumento,$Documento,$departamento,$ubicacionPaciente,
		$responsableSolicitud,$procedimiento,$codigoPro,$tratamientos,$Solicitud,$observaciones,$origenSolicitud){

    $this->salida .= ThemeAbrirTabla('BUSCADOR DIAGNOSTICOS');
		$action=ModuloGetURL('app','Patologia','user','SeleccionDiagnostico',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"departamento"=>$departamento,"ubicacionPaciente"=>$ubicacionPaciente,
		"responsableSolicitud"=>$responsableSolicitud,"procedimiento"=>$procedimiento,"codigoPro"=>$codigoPro,"tratamientos"=>$tratamientos,"Solicitud"=>$Solicitud,"observaciones"=>$observaciones,"origenSolicitud"=>$origenSolicitud));
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
				$action=ModuloGetURL('app','Patologia','user','SeleccionarDiagnosticoPatologia',array("codigoDiagnostico"=>$diags[$i]['diagnostico_id'],"nombreDiagnostico"=>$diags[$i]['diagnostico_nombre'],
				"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"departamento"=>$departamento,"ubicacionPaciente"=>$ubicacionPaciente,
		    "responsableSolicitud"=>$responsableSolicitud,"procedimiento"=>$procedimiento,"codigoPro"=>$codigoPro,"tratamientos"=>$tratamientos,"Solicitud"=>$Solicitud,"observaciones"=>$observaciones,"origenSolicitud"=>$origenSolicitud));
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

	function BucadorTejidosPatologicos($TipoDocumento,$Documento,$departamento,$ubicacionPaciente,
	$responsableSolicitud,$tratamientos,$Solicitud,$procedimiento,$codigoPro,$observaciones,$origenSolicitud){
    $this->salida .= ThemeAbrirTabla('BUSCADOR TEJIDOS PATOLOGICOS');
		$action=ModuloGetURL('app','Patologia','user','SeleccionTejidos',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"departamento"=>$departamento,"ubicacionPaciente"=>$ubicacionPaciente,
		"responsableSolicitud"=>$responsableSolicitud,"procedimiento"=>$procedimiento,"codigoPro"=>$codigoPro,"tratamientos"=>$tratamientos,"Solicitud"=>$Solicitud,"observaciones"=>$observaciones,"origenSolicitud"=>$origenSolicitud));
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "	  <table width=\"85%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"5\">PARAMENTROS DE BUSQUEDA</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td class=\"label\">TEJIDO</td>";
    $this->salida .= "    <td><input size=\"80\" type=\"text\" name=\"procedimientoBus\" value=\"".$_REQUEST['procedimientoBus']."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td class=\"label\">CODIGO</td>";
		$this->salida .= "    <td><input type=\"text\" size=\"10\" name=\"codigoBus\" value=\"".$_REQUEST['codigoBus']."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td><input type=\"submit\" name=\"buscar\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
		$this->salida .= "	  </table><BR>";
		$tejidos=$this->HallarTejidosPatologia($_REQUEST['codigoBus'],$_REQUEST['procedimientoBus']);
		if($tejidos){
      $this->salida .= "	   <table width=\"80%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
			$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td>CODIGO</td>";
			$this->salida .= "	   <td>DESCRIPCION</td>";
			$this->salida .= "	   <td>&nbsp;</td>";
			$y=0;
			for($i=0;$i<sizeof($tejidos);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "    <tr class=\"$estilo\">";
				$this->salida .= "    <td width=\"10%\" nowrap>".$tejidos[$i]['tejido_id']."</td>";
				$this->salida .= "    <td>".$tejidos[$i]['descripcion']."</td>";
				$action=ModuloGetURL('app','Patologia','user','SeleccionarTejidoPatologia',array("codigoTejido"=>$tejidos[$i]['tejido_id'],"nombreTejido"=>$tejidos[$i]['descripcion'],
				"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"departamento"=>$departamento,"ubicacionPaciente"=>$ubicacionPaciente,
			  "responsableSolicitud"=>$responsableSolicitud,"tratamientos"=>$tratamientos,"Solicitud"=>$Solicitud,"procedimiento"=>$procedimiento,"codigoPro"=>$codigoPro,"observaciones"=>$observaciones,"origenSolicitud"=>$origenSolicitud));
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
    $this->salida .= "	   </table><br>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaRecepcionTejidoPatologico($TipoDocumento,$Documento,$departamento,$fecha,
		$procedimiento,$codigoPro,$tejido,$codigoTejido,$estadosolicitudes){
    $this->salida .= ThemeAbrirTabla('SOLICITUDES PATOLOGICAS');
		$action=ModuloGetURL('app','Patologia','user','FiltroBusquedaSolicitudes');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "	  <table width=\"80%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">FILTRO DE BUSQUEDA</legend>";
		$this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">TIPO DOCUMENTO </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->Mostrar($tipo_id,'False',$TipoId);
		$this->salida .= "        </select></td>";
		$this->salida .= "        <td class=\"label\">DOCUMENTO </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" value=\"$Documento\" maxlength=\"32\"></td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">DEPARTAMENTO </td>";
		$this->salida .= "        <td><select name=\"departamento\" class=\"select\">";
		$departamentos=$this->tiposdepartamentos();
		$this->MostrasSelect($departamentos,'False',$departamento);
		$this->salida .= "        </select></td>";
		$this->salida .= "        <td class=\"".$this->SetStyle("fecha")."\" align=\"left\">FECHA</td>";
		$this->salida .= "        <td align=\"left\"><input type=\"text\" class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"$fecha\" name=\"fecha\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('forma','fecha','-')."</td>" ;
		$this->salida .= "        </tr>";
   /* $this->salida .= "        <tr class=\"modulo_list_claro\"><td colspan=\"4\">";
    $this->salida .= "          <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		/*$this->salida .= "          <tr class=\"modulo_list_claro\">";
    $this->salida .= "          <td class=\"label\" width=\"20%\" nowrap>PROCEDIMIENTO</td>";
    $this->salida .= "          <td><input size=\"80\" type=\"text\" readonly name=\"procedimiento\" value=\"$procedimiento\" class=\"input-submit\"></td>";
    //$this->salida .= "          <td class=\"label\">CODIGO</td>";
		$this->salida .= "          <td><input type=\"hidden\" size=\"10\" name=\"codigoPro\" value=\"$codigoPro\" class=\"input-submit\"></td>";
    $this->salida .= "          <td><input type=\"submit\" name=\"buscarPro\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida .= "          </tr>";
    $this->salida .= "          </table>";
    $this->salida .= "        </td></tr>";*/
		$this->salida .= "        <tr class=\"modulo_list_claro\"><td colspan=\"4\">";
    $this->salida .= "          <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
    $this->salida .= "          <td class=\"label\" width=\"20%\" nowrap>TEJIDO</td>";
    $this->salida .= "          <td><input size=\"80\" type=\"text\" readonly name=\"tejido\" value=\"$tejido\" class=\"input-submit\"></td>";
    //$this->salida .= "          <td class=\"label\">CODIGO</td>";
		$this->salida .= "          <td><input type=\"hidden\" size=\"10\" name=\"codigoTejido\" value=\"$codigoTejido\" class=\"input-submit\"></td>";
    $this->salida .= "          <td><input type=\"submit\" name=\"buscarTejido\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida .= "          </tr>";
    $this->salida .= "          </table>";
    $this->salida .= "        </td></tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\"><td colspan=\"4\">";
    $this->salida .= "          <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		if(empty($estadosolicitudes)){$estadosolicitudes=1;}
		if($estadosolicitudes==1){$v='checked';}
    $this->salida .= "          <td class=\"label\"><input type=\"radio\" value=\"1\" name=\"estadosolicitudes\" $v>&nbsp&nbsp&nbsp;SIN CONFIRMAR</td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		if($estadosolicitudes==2){$v1='checked';}
    $this->salida .= "          <td class=\"label\"><input type=\"radio\" value=\"2\" name=\"estadosolicitudes\" $v1>&nbsp&nbsp&nbsp;CONFIRMADAS SIN ASIGNAR CONSECUTIVO</td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		if($estadosolicitudes==3){$v2='checked';}
    $this->salida .= "          <td class=\"label\"><input type=\"radio\" value=\"3\" name=\"estadosolicitudes\" $v2>&nbsp&nbsp&nbsp;ASIGNADAS</td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		if($estadosolicitudes==4){$v3='checked';}
    $this->salida .= "          <td class=\"label\"><input type=\"radio\" value=\"4\" name=\"estadosolicitudes\" $v3>&nbsp&nbsp&nbsp;TODAS</td>";
		$this->salida .= "          </tr>";
    $this->salida .= "          </table>";
    $this->salida .= "        </td></tr>";
    $this->salida .= "        <tr class=\"modulo_list_claro\"><td colspan=\"4\" align=\"center\">";
		$this->salida .= "        <input type=\"submit\" name=\"Filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
    $this->salida .= "        <input type=\"submit\" name=\"Menu\" value=\"MENU\" class=\"input-submit\">";
    $this->salida .= "        </td></tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		</fieldset></td></tr>";
    $this->salida .= "	  </table>";
		$solicitudesPatologicas=$this->SolicitudesPatologicas($TipoDocumento,$Documento,$departamento,$fecha,$codigoPro,$codigoTejido,$estadosolicitudes);
    if($solicitudesPatologicas){
      $this->salida .= "    <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "    <tr class=\"modulo_table_list_title\">";
			$this->salida .= "    <td width=\"10%\">No. SOLICITUD</td>";
			$this->salida .= "    <td width=\"30%\">PACIENTE</td>";
			$this->salida .= "    <td>ORIGEN SOLCITUD</td>";
			$this->salida .= "    <td>FECHA</td>";
			$this->salida .= "    <td>TEJIDOS</td>";
			$this->salida .= "    <td>ESTADO TEJIDO</td>";
			$this->salida .= "    <td>&nbsp;</td>";
			$this->salida .= "    </tr>";
			//print_r($solicitudesPatologicas);
			$solicitudAnt='';
			foreach($solicitudesPatologicas as $solicitud=>$vector){
			   foreach($vector as $TejidoId=>$datos){
					if($solicitud!=$solicitudAnt){
					if($y % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
					$this->salida .= "    <tr class=\"$estilo\">";
					$this->salida .= "    <td rowspan=\"".sizeof($vector)."\">$solicitud</td>";
					$this->salida .= "    <td rowspan=\"".sizeof($vector)."\">".$datos['tipo_id_paciente']." ".$datos['paciente_id']." - ".$datos['nombre']."</td>";
					if($datos['departamento']){
          $this->salida .= "    <td rowspan=\"".sizeof($vector)."\">".$datos['departamento']."</td>";
					}else{
          $this->salida .= "    <td rowspan=\"".sizeof($vector)."\">".$datos['origen_solicitud']."</td>";
					}
					$this->salida .= "    <td rowspan=\"".sizeof($vector)."\">".$datos['fecha']."</td>";
					$this->salida .= "    <td>".$datos['tejido']."</td>";
					$solicitudAnt=$solicitud;
					}else{
						$this->salida .= "    <tr class=\"$estilo\">";
						$this->salida .= "    <td>".$datos['tejido']."</td>";
					}
					if($datos['estado']=='1'){
						$actionC=ModuloGetURL('app','Patologia','user','ConfirmacionLlegadaTejido',array("solicitud"=>$solicitud,"tejidoId"=>$datos['tejido_id'],
						"tipoId"=>$datos['tipo_id_paciente'],"PacienteId"=>$datos['paciente_id'],"nombre"=>$datos['nombre'],"fecha"=>$datos['fecha'],"nomTejido"=>$datos['tejido']));
						$this->salida .= "    <td align=\"center\"><a href=\"$actionC\">SIN CONFIRMAR<a></td>";
					}elseif($datos['estado']=='2' || $datos['estado']=='3'){
            $tiposProce=$this->TiposProcedimientosTejidos($solicitud,$TejidoId);
						$this->salida .= "    <td>";
            $this->salida .= "    <table border=\"0\" width=\"95%\" align=\"center\">";
						$existe=0;
						for($i=0;$i<sizeof($tiposProce);$i++){
						$this->salida .= "    <tr class=\"$estilo1\">";
            $this->salida .= "    <td>".$tiposProce[$i]['descripcion']."</td>";
						if(empty($tiposProce[$i]['resultado_informe_id'])){
            $action=ModuloGetURL('app','Patologia','user','AsignarNumeracionInforme',array("solicitud"=>$solicitud,"tipoId"=>$datos['tipo_id_paciente'],"PacienteId"=>$datos['paciente_id'],"nombre"=>$datos['nombre'],
						"fecha"=>$datos['fecha'],"tipoCargo"=>$tiposProce[$i]['tipo_cargo'],"grupoTipoCargo"=>$tiposProce[$i]['grupo_tipo_cargo'],"tipoInformeNombre"=>$tiposProce[$i]['descripcion']));
						$this->salida .= "    <td align=\"center\"><a href=\"$action\" class=\"link\">ASIGNAR</a></td>";
						}else{
            $this->salida .= "    <td align=\"center\">";
						if($tiposProce[$i]['prefijo']){
            $this->salida .= "    ".$tiposProce[$i]['prefijo']."";
						}
						$this->salida .= "    ".$tiposProce[$i]['resultado_informe_id']."";
						$this->salida .= "    </td>";
						}
						$this->salida .= "    </tr>";
						if(!empty($tiposProce[$i]['existe'])){
              $existe=1;
						}
						}
						$this->salida .= "    </table>";
						$this->salida .= "    </td>";
					}

					if($existe==1){
            $this->salida .= "    <td align=\"center\"><a class=\"Menu\">INCIERADA<a></td>";
					}elseif(($existe!=1) && (sizeof($tiposProce))>0){
            $actionIncinera=ModuloGetURL('app','Patologia','user','IncinerarTejidoMuestra',array("solicitud"=>$solicitud,"tejidoId"=>$datos['tejido_id'],
						"tipoId"=>$datos['tipo_id_paciente'],"PacienteId"=>$datos['paciente_id'],"nombre"=>$datos['nombre'],"fecha"=>$datos['fecha'],"nomTejido"=>$datos['tejido']));
						$this->salida .= "    <td align=\"center\"><a href=\"$actionIncinera\">INCINERAR<a></td>";
					}else{
            $this->salida .= "    <td align=\"center\">&nbsp;</td>";
					}
					$this->salida .= "    </tr>";
					//$actionM=ModuloGetURL('app','Patologia','user','ConfirmacionLlegadaTejido',array("modificacion"=>1,"solicitud"=>$solicitud,"tejidoId"=>$datos['tejido_id'],
					//"tipoId"=>$datos['tipo_id_paciente'],"PacienteId"=>$datos['paciente_id'],"nombre"=>$datos['nombre'],"fecha"=>$datos['fecha'],"nomTejido"=>$datos['tejido']));
					//$this->salida .= "    <td align=\"center\"><a  href=\"$actionM\" class=\"TurnoActivo\">CONFIRMADO <br>SIN RESULTADO<a></td>";
					/*if($datos['estado']=='2' && !empty($datos['resultado_informe_id'])){
						if($datos['sw_cadaver']!=1){
						  $actionIncinera=ModuloGetURL('app','Patologia','user','IncinerarTejidoMuestra',array("solicitud"=>$solicitud,"tejidoId"=>$datos['tejido_id'],
						  "tipoId"=>$datos['tipo_id_paciente'],"PacienteId"=>$datos['paciente_id'],"nombre"=>$datos['nombre'],"fecha"=>$datos['fecha'],"nomTejido"=>$datos['tejido']));
						  $this->salida .= "    <td><a href=\"$actionIncinera\">INCINERAR<a></td>";
						}else{
						  $actionEntrega=ModuloGetURL('app','Patologia','user','LlamaEntregaCadaver',array("solicitud"=>$solicitud,"tejidoId"=>$datos['tejido_id'],
						  "tipoId"=>$datos['tipo_id_paciente'],"PacienteId"=>$datos['paciente_id'],"nombre"=>$datos['nombre'],"fecha"=>$datos['fecha'],"nomTejido"=>$datos['tejido']));
						  $this->salida .= "    <td><a href=\"$actionEntrega\">ENTREGA<a></td>";
						}
					}elseif($datos['estado']=='3' && !empty($datos['resultado_informe_id'])){
					  if($datos['sw_cadaver']!=1){
              $this->salida .= "    <td><a class=\"Menu\">INCIERADO<a></td>";
						}else{
              $this->salida .= "    <td><a class=\"Menu\">ENTREGADO<a></td>";
						}
					}else{
            $this->salida .= "    <td>&nbsp;</td>";
					}
					$this->salida .= "     </tr>";
          $solicitudAnt=$solicitud;
				}else{
          $this->salida .= "    <tr class=\"$estilo\">";
          $this->salida .= "    <td>".$datos['tejido']."</td>";
					if($datos['estado']=='1'){
						$actionC=ModuloGetURL('app','Patologia','user','ConfirmacionLlegadaTejido',array("solicitud"=>$solicitud,"tejidoId"=>$datos['tejido_id'],
						"tipoId"=>$datos['tipo_id_paciente'],"PacienteId"=>$datos['paciente_id'],"nombre"=>$datos['nombre'],"fecha"=>$datos['fecha'],"nomTejido"=>$datos['tejido']));
						$this->salida .= "    <td align=\"center\"><a href=\"$actionC\">SIN CONFIRMAR<a></td>";
					}else{
					  if($datos['resultado_informe_id']){
              $this->salida .= "    <td align=\"center\"><a class=\"Menu\">CONFIRMADO <br>CON RESULTADO<a></td>";
						}else{
						  $actionM=ModuloGetURL('app','Patologia','user','ConfirmacionLlegadaTejido',array("modificacion"=>1,"solicitud"=>$solicitud,"tejidoId"=>$datos['tejido_id'],
						  "tipoId"=>$datos['tipo_id_paciente'],"PacienteId"=>$datos['paciente_id'],"nombre"=>$datos['nombre'],"fecha"=>$datos['fecha'],"nomTejido"=>$datos['tejido']));
              $this->salida .= "    <td align=\"center\"><a  href=\"$actionM\" class=\"TurnoActivo\">CONFIRMADO <br>SIN RESULTADO<a></td>";
						}
					}
					if($datos['estado']=='2' && !empty($datos['resultado_informe_id'])){
						if($datos['sw_cadaver']!=1){
						  $actionIncinera=ModuloGetURL('app','Patologia','user','IncinerarTejidoMuestra',array("solicitud"=>$solicitud,"tejidoId"=>$datos['tejido_id'],
						  "tipoId"=>$datos['tipo_id_paciente'],"PacienteId"=>$datos['paciente_id'],"nombre"=>$datos['nombre'],"fecha"=>$datos['fecha'],"nomTejido"=>$datos['tejido']));
						  $this->salida .= "    <td><a href=\"$actionIncinera\">INCINERAR<a></td>";
						}else{
						  $this->salida .= "    <td><a href=\"$actionEntrega\">ENTREGA<a></td>";
						}
					}elseif($datos['estado']=='3'){
            $this->salida .= "    <td><a class=\"Menu\">INCIERADA<a></td>";
					}else{
            $this->salida .= "    <td>&nbsp;</td>";
					}*/
				}
				$y++;
			}
			$this->salida .= "    </table>";
			$this->salida .=$this->RetornarBarra(5);
		}
    $this->salida .= "    </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaBusquedaProcedimientosSolicitud($TipoDocumento,$Documento,$departamento,$fecha,$procedimiento,$codigoPro,$tejido,$codigoTejido){

    $this->salida .= ThemeAbrirTabla('BUSCADOR PROCEDIMIENTO PATOLOGICO');
		$action=ModuloGetURL('app','Patologia','user','SeleccionProcedimientoBuscador',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"departamento"=>$departamento,"fecha"=>$fecha,"procedimiento"=>$procedimiento,"codigoPro"=>$codigoPro,"tejido"=>$tejido,"codigoTejido"=>$codigoTejido));
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
				$action=ModuloGetURL('app','Patologia','user','SeleccionarProcedimiento',array("cargoSelect"=>$cups[$i]['cargo'],"descripcionSelect"=>$cups[$i]['descripcion'],
				"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"departamento"=>$departamento,"fecha"=>$fecha,"tejido"=>$tejido,"codigoTejido"=>$codigoTejido,
        "bandera"=>1));
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

	function FormaConfirmacionLlegadaTejido($solicitud,$tejidoId,$nomTejido,$tipoId,
		$PacienteId,$nombre,$fecha,$modificacion){
    $this->salida .= ThemeAbrirTabla('CONFIRMACION RECEPCION DEL TEJIDO'.'  '.$nomTejido);
		$action=ModuloGetURL('app','Patologia','user','GuardaConfirmacionTejido',array("solicitud"=>$solicitud,"tejidoId"=>$tejidoId,"nomTejido"=>$nomTejido,"tipoId"=>$tipoId,
		"PacienteId"=>$PacienteId,"nombre"=>$nombre,"fecha"=>$fecha,"modificacion"=>$modificacion));
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "	  <table width=\"50%\" border=\"0\" class=\"normal_10\" align=\"center\">";
    $this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">DATOS DE LA SOLICITUD</legend>";
		$this->salida .= "    <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">PACIENTE</td>";
		$this->salida .= "		<td>$tipoId $PacienteId $nombre</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">FECHA</td>";
		$this->salida .= "		<td>$fecha</td>";
    $this->salida .= "		</tr>";
    $this->salida .= "		</table><BR>";
		$this->salida .= "		</fieldset></td></tr>";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "		</table><br>";
		$this->salida .= "	  <table width=\"90%\" border=\"0\" class=\"normal_10\" align=\"center\">";
    $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td align=\"center\">";
		$this->salida .= "	      <br><table width=\"95%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		if($_REQUEST['inadecuada']==1){
      $var='checked';
		}
		$this->salida.="          <tr class=\"modulo_list_claro\"><td width=\"25%\" nowrap class=\"label\"><BR>TEJIDO INADECUADO</td><td><BR><input type=\"checkbox\" name=\"inadecuada\" value=\"1\" $var></td></tr>";
		$this->salida.="          <tr class=\"modulo_list_claro\"><td colspan=\"2\" align=\"left\"><label class=\"label\">OBSERVACIONES DEL TEJIDO</label><br><textarea style=\"width:100%\" name=\"observaciones\" class=\"textarea\" rows=\"3\" cols=\"60\">".$_REQUEST['observaciones']."</textarea></td></tr>";

    $this->salida .= "        <tr><td colspan=\"2\" class=\"modulo_list_claro\">";
		$this->salida .= "            <BR><table width=\"98%\" align=\"center\" border=\"0\">\n";
		$this->salida .= "            <tr class=\"modulo_table_list_title\">";
		$this->salida .= "            <td colspan=\"2\" align=\"center\">PROCEDIMIENTOS INSERTADOS</td>";
		$this->salida .= "            </tr>";
		$procedimientos=$_SESSION['PATOLOGIA_CLASIFICACION']['PROCEDIMIENTOS'];
		if($procedimientos){
			$this->salida .= "    <tr class=\"modulo_list_oscuro\"><td align=\"center\" colspan=\"2\">";
			$this->salida .= "      <BR><table width=\"98%\" align=\"center\" border=\"0\">\n";
			foreach($procedimientos as $codigo=>$vector){
			  foreach($vector as $indice=>$nombrePro){
					$this->salida .= "      <tr class=\"modulo_list_claro\">";
					$this->salida .= "      <td>$nombrePro</td>";
					$actionEliminaT=ModuloGetURL('app','Patologia','user','EliminaProcedimientoMuestra',array("codigoTejido"=>$codigo,"solicitud"=>$solicitud,"tejidoId"=>$tejidoId,"nomTejido"=>$nomTejido,"tipoId"=>$tipoId,
					"PacienteId"=>$PacienteId,"nombre"=>$nombre,"fecha"=>$fecha,"modificacion"=>$modificacion,"inadecuada"=>$_REQUEST['inadecuada'],"observaciones"=>$_REQUEST['observaciones']));
					$this->salida .= "      <td width=\"5%\" nowrap align=\"center\"><a href=\"$actionEliminaT\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
					$this->salida .= "      </tr>";
				}
			}
			$this->salida .= "           </table><br>";
			$this->salida .= "    </td></tr>";
		}
	  $this->salida .= "           <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "           <td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"buscarProc\" value=\"BUSCAR PROCEDIMIENTO\"></td>";
		$this->salida .= "           </tr>";
		$this->salida .= "           </table><BR><BR>";
		$this->salida .= "        </td></tr>";
		$this->salida.="          <tr><td align=\"center\" colspan=\"2\">";
    $this->salida.="          <input type=\"submit\" name=\"confirmar\" value=\"CONFIRMAR\" class=\"input-submit\">";
		$this->salida.="          <input type=\"submit\" name=\"cancelar\" value=\"CANCELAR\" class=\"input-submit\">";
		$this->salida.="          </td></tr>";
		$this->salida .= "	      </table>";
		$this->salida.="     </td></tr>";
    $this->salida .= "	  </table>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	* La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
	* @return boolean
	* @param string mensaje a retornar para el usuario
	* @param string titulo de la ventana a mostrar
	* @param string lugar a donde debe retornar la ventana
	* @param boolean tipo boton de la ventana
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton,$bandera,$prefijo,$informe,$firma){

		$this->salida .= ThemeAbrirTabla($titulo);
		$this->salida .= "			      <table border=\"0\" class=\"normal_10\" width=\"60%\" align=\"center\">";
		$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "				       <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
		if($bandera){
		  if($firma==1){
				$this->salida .= "				       <tr><td align=\"center\">";
				$rep= new GetReports();
				$mostrar=$rep->GetJavaReport('app','Patologia','examenes_html',array('resultado_id'=>$informe,"prefijo"=>$prefijo),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
				$nombre_funcion=$rep->GetJavaFunction();
				$this->salida .=$mostrar;
				$this->salida .= "	            <BR><a class=\"Menu\" href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a>";
				$this->salida .= "				       </td></tr>";
			}
		}
		if($boton){
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
		}else{
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
	  }
		$this->salida .= "			     </form>";
		$this->salida .= "			     </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function BucadorTejidosPatologicosBuscador($destino,$TipoDocumento,$Documento,$departamento,$fecha,$procedimiento,$codigoPro,$tejido,$codigoTejido){
    if(!$_REQUEST['destino']){
      $_REQUEST['destino']=$destino;
		}
    $this->salida .= ThemeAbrirTabla('BUSCADOR TEJIDOS PATOLOGICOS');
		$action=ModuloGetURL('app','Patologia','user','SeleccionTejidos',array("destino"=>$destino,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,
		"departamento"=>$departamento,"fecha"=>$fecha,"procedimiento"=>$procedimiento,"codigoPro"=>$codigoPro,"tejido"=>$tejido,"codigoTejido"=>$codigoTejido));
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "	  <table width=\"85%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"5\">PARAMENTROS DE BUSQUEDA</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td class=\"label\">TEJIDO</td>";
    $this->salida .= "    <td><input size=\"80\" type=\"text\" name=\"procedimientoBus\" value=\"".$_REQUEST['procedimientoBus']."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td class=\"label\">CODIGO</td>";
		$this->salida .= "    <td><input type=\"text\" size=\"10\" name=\"codigoBus\" value=\"".$_REQUEST['codigoBus']."\" class=\"input-submit\"></td>";
    $this->salida .= "    <td><input type=\"submit\" name=\"buscar\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida .= "    </tr>";
		$this->salida .= "	  </table><BR>";
		$tejidos=$this->HallarTejidosPatologia($_REQUEST['codigoBus'],$_REQUEST['procedimientoBus']);
		if($tejidos){
      $this->salida .= "	   <table width=\"80%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
			$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td>CODIGO</td>";
			$this->salida .= "	   <td>DESCRIPCION</td>";
			$this->salida .= "	   <td>&nbsp;</td>";
			$y=0;
			for($i=0;$i<sizeof($tejidos);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "    <tr class=\"$estilo\">";
				$this->salida .= "    <td width=\"10%\" nowrap>".$tejidos[$i]['tejido_id']."</td>";
				$this->salida .= "    <td>".$tejidos[$i]['descripcion']."</td>";
				$action=ModuloGetURL('app','Patologia','user','SeleccionarTejidoPatologia',	array("codigoTejido"=>$tejidos[$i]['tejido_id'],"nombreTejido"=>$tejidos[$i]['descripcion'],
				"destino"=>$destino,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"departamento"=>$departamento,"fecha"=>$fecha,"procedimiento"=>$procedimiento,
				"codigoPro"=>$codigoPro));
				$this->salida .= "    <td width=\"5%\" nowrap align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
        $this->salida .= "    </tr>";
			}
      $this->salida .= "	   </table>";
			$this->salida .=$this->RetornarBarra(6);
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

	function InsercionResultadoPat($solicitud,$nomgrupo,$grupo,$tipoId,$PacienteId,$nombre,$fecha,$descripcion_macro,$descripcion_micro,$firma,$NoInforme,$consulta,$nombreProfesional){

		$this->salida .= ThemeAbrirTabla('INFORME DE PATOLOGIA GRUPO'.' '.$nomgrupo);
		$action=ModuloGetURL('app','Patologia','user','GuardarResultadoPatologia',array("solicitud"=>$solicitud,
		"nomgrupo"=>$nomgrupo,"grupo"=>$grupo,"tipoId"=>$tipoId,"PacienteId"=>$PacienteId,"nombre"=>$nombre,"fecha"=>$fecha,"NoInforme"=>$NoInforme));
		if($consulta){
      $desabilitado='disabled';
			$soloLectura='readonly';
		}
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "	  <table width=\"80%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr>";
		$this->salida .= "      <td width=\"45%\" valign=\"top\">";
	  $this->salida .= "      <fieldset><legend class=\"field\">DATOS SOLICITUD</legend>";
	  $this->salida .= "      <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "		   <td class=\"label\">PACIENTE</td>";
    $this->salida .= "		   <td>$tipoId $PacienteId $nombre</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "		  <td class=\"label\">FECHA</td>";
		$this->salida .= "		  <td>$fecha</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "	    </table>";
		$this->salida .= "		  </fieldset></td>";
    $this->salida .= "      <td width=\"45%\" valign=\"top\">";
	  $this->salida .= "      <fieldset><legend class=\"field\">TEJIDOS</legend>";
	  $this->salida .= "      <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$tejidos=$this->TejidosSolicitud($solicitud,$grupo);
		for($i=0;$i<sizeof($tejidos);$i++){
      $this->salida .= "      <tr class=\"modulo_list_claro\">";
      $this->salida .= "      <td>".$tejidos[$i]['nomtejido']."</td>";
			$this->salida .= "      </tr>";
		}
		$this->salida .= "	    </table>";
		$this->salida .= "		  </fieldset></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		</table>";
    $this->salida .= "    <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\">";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td class=\"label\">DESCRIPCION MACROSCOPICA</td>";
		$this->salida .= "    <td><textarea name=\"descripcion_macro\" class =\"textarea\" rows =\"12\" cols =\"100\" $soloLectura>$descripcion_macro</textarea></td>";
    $this->salida .= "    </tr>";
		$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "    <td class=\"label\">DESCRIPCION MICROSCOPICA</td>";
		$this->salida .= "    <td><textarea name=\"descripcion_micro\" class =\"textarea\" rows =\"12\" cols =\"100\" $soloLectura>$descripcion_micro</textarea></td>";
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
			$actionElimina=ModuloGetURL('app','Patologia','user','EliminaDiagPatologiaResultado',array("codigo"=>$codigo,"solicitud"=>$solicitud,"nomgrupo"=>$nomgrupo,"grupo"=>$grupo,
			"tipoId"=>$tipoId,"PacienteId"=>$PacienteId,"nombre"=>$nombre,"fecha"=>$fecha,"descripcion_macro"=>$descripcion_macro,"descripcion_micro"=>$descripcion_micro,"firma"=>$firma,"NoInforme"=>$NoInforme));
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
			$this->salida .= "    <td colspan=\"2\" align=\"center\" class=\"label\">FIRMADO POR:&nbsp&nbsp&nbsp&nbsp;$nombreProfesional</td>";
		}else{
			$this->salida .= "    <td colspan=\"2\" align=\"center\" class=\"label\"><input type=\"checkbox\" name=\"firma\" value=\"1\" $desabilitado $var>&nbsp;APROBACION DEL RESULTADO AQUI REGISTRADO</td>";
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

	function FormaBuscadorDiagnosticoResultado($observacionesInforme,$firma,$patologoProfe){

    $this->salida .= ThemeAbrirTabla('BUSCADOR DIAGNOSTICOS');
		$action=ModuloGetURL('app','Patologia','user','SeleccionDiagnostico',array("origen"=>1,"observacionesInforme"=>$observacionesInforme,"firma"=>$firma,"patologoProfe"=>$patologoProfe));
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
				$action=ModuloGetURL('app','Patologia','user','SeleccionarDiagnosticoPatologia',array("codigoDiagnostico"=>$diags[$i]['diagnostico_id'],"nombreDiagnostico"=>$diags[$i]['diagnostico_nombre'],"origen"=>1,
				"observacionesInforme"=>$observacionesInforme,"firma"=>$firma,"patologoProfe"=>$patologoProfe));
				$this->salida .= "    <td width=\"5%\" nowrap align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
        $this->salida .= "    </tr>";
			}
      $this->salida .= "	   </table>";
			$this->salida .=$this->RetornarBarra(7);
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

	function FormaIncinerarTejidoMuestra($solicitud,$tejidoId,$tipoId,$PacienteId,$nombre,$fecha,$nomTejido){
    $this->salida .= ThemeAbrirTabla('INCINERACION DE TEJIDOS');
		$action=ModuloGetURL('app','Patologia','user','GuardarIncineracionTejido',array("solicitud"=>$solicitud,"tejidoId"=>$tejidoId,
		"tipoId"=>$tipoId,"PacienteId"=>$PacienteId,"nombre"=>$nombre,"fecha"=>$fecha,"nomTejido"=>$nomTejido));
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= "	  <table width=\"50%\" border=\"0\" class=\"normal_10\" align=\"center\">";
    $this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">DATOS DE LA SOLICITUD</legend>";
		$this->salida .= "    <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td width=\"20%\" nowrap class=\"label\">PACIENTE</td>";
		$this->salida .= "		<td colspan=\"3\">$tipoId $PacienteId $nombre</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td width=\"20%\" nowrap class=\"label\">No. SOLICITUD</td>";
		$this->salida .= "		<td>$solicitud</td>";
		$this->salida .= "		<td width=\"15%\" nowrap class=\"label\">FECHA</td>";
		$this->salida .= "		<td width=\"20%\" nowrap>$fecha</td>";
    $this->salida .= "		</tr>";
    $this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td width=\"20%\" nowrap class=\"label\">TEJIDO</td>";
		$this->salida .= "		<td colspan=\"3\">$nomTejido</td>";
    $this->salida .= "		</tr>";
    $this->salida .= "		</table><BR>";
		$this->salida .= "		</fieldset></td></tr>";
		$this->salida .= "		</table><br>";
		$this->salida .= "    <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"50%\" align=\"center\">";
    $this->salida .= "    <tr><td><label class=\"label\">OBSERVACIONES</label>";
    $this->salida .= "		<textarea style=\"width:100%\" name=\"observaciones\" class=\"textarea\" rows=\"3\" cols=\"60\"></textarea>";
		$this->salida .= "    </td></tr>";
    $this->salida .= "    <tr><td align=\"center\">";
    $this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"Guardar\" value=\"INCINERAR\">";
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"Regresar\" value=\"REGRESAR\">";
    $this->salida .= "    </td></tr>";
		$this->salida .= "		</table><br>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function CreacionInformesPatologia($TipoDocumento,$Documento,$noSolicitud,$fecha,$noinforme,$prefijo,$todasFechas){

		$this->salida .= ThemeAbrirTabla('SOLICITUDES PATOLOGICAS CONFIRMADAS');
		$action=ModuloGetURL('app','Patologia','user','FiltroSolicitudesConfirmadas');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "	  <table width=\"80%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">FILTRO DE BUSQUEDA</legend>";
		$this->salida .= "    <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">TIPO DOCUMENTO </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->Mostrar($tipo_id,'False',$TipoId);
		$this->salida .= "        </select></td>";
		$this->salida .= "        <td class=\"label\">DOCUMENTO </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" value=\"$Documento\" maxlength=\"32\"></td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">No. SOLICITUD </td>";
		$this->salida .= "        <td><input type=\"text\" class=\"input_submit\" name=\"noSolicitud\" value=\"$noSolicitud\" size=\"30\"></td>";
		$this->salida .= "        <td class=\"".$this->SetStyle("fecha")."\" align=\"left\">FECHA SOLICITUD</td>";
		$this->salida .= "        <td align=\"left\">";
    $this->salida .= "            <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		if($todasFechas==1){
      $var='checked';
		}
		$this->salida .= "            <td align=\"left\" class=\"label\">TODAS LAS FECHAS<input type=\"checkbox\" value=\"1\" name=\"todasFechas\" $var>";
    $this->salida .= "            </td>";
		$this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td align=\"left\">";
		$this->salida .= "            <input type=\"text\" class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"$fecha\" name=\"fecha\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('forma','fecha','-')."";
		$this->salida .= "            </td>";
		$this->salida .= "            </tr>";
		$this->salida .= "            </table>";
    $this->salida .= "        </td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">No. INFORME </td>";
		$this->salida .= "        <td colspan=\"3\"><select name=\"prefijo\" class=\"select\">";
		$prefijos=$this->TotalPrefijos();
		$this->MostrasSelect($prefijos,'False',$prefijo);
		$this->salida .= "        </select>&nbsp&nbsp&nbsp;<input type=\"text\" class=\"input-submit\" value=\"$noinforme\" name=\"noinforme\"></td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\"><td colspan=\"4\" align=\"center\">";
		$this->salida .= "        <input type=\"submit\" name=\"Filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
    $this->salida .= "        <input type=\"submit\" name=\"Menu\" value=\"MENU\" class=\"input-submit\">";
    $this->salida .= "        </td></tr>";
    $this->salida .= "        </table>";
    $this->salida .= "        </td></tr>";
		$this->salida .= "		    </fieldset></td></tr>";
		$this->salida .= "      <tr><td align=\"center\">";
		$this->salida .=        $this->SetStyle("MensajeError");
		$this->salida .= "      </td></tr>";
    $this->salida .= "	  </table><br>";
		$solicitudes=$this->SolicitudesConfirmadas($TipoDocumento,$Documento,$noSolicitud,$fecha,$noinforme,$prefijo,$todasFechas);
		if($solicitudes){
      $this->salida .= "	   <table width=\"98%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
			$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
			$this->salida .= "	   <td>No. SOLICITUD</td>";
			$this->salida .= "	   <td>FECHA</td>";
			$this->salida .= "	   <td>PACIENTE</td>";
			$this->salida .= "	   <td>INFORMES RELIZADOS <br>X PROFESIONALES</td>";
			$this->salida .= "	   <td>TIPO INFORME</td>";
			//$this->salida .= "	   <td colspan=\"2\">TEJIDOS</td>";
			$y=0;
			//IMPRESION
			$rep= new GetReports();
      //FIN IMPRESION
			foreach($solicitudes as $solicitud=>$vector){
			$solicitudAnt='';
				if($y % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
				$this->salida .= "    <tr class=\"$estilo\">";
				$this->salida .= "    <td rowspan=\"".sizeof($vector)."\">$solicitud</td>";
        foreach($vector as $tejido=>$datos){
          if($solicitudAnt!=$solicitud){
            $this->salida .= "    <td rowspan=\"".sizeof($vector)."\">".$datos['date']."</td>";
            $this->salida .= "    <td rowspan=\"".sizeof($vector)."\">".$datos['tipo_id_paciente']." ".$datos['paciente_id']."&nbsp&nbsp&nbsp&nbsp;".$datos['nompaciente']."</td>";
						$this->salida .= "	  <td rowspan=\"".sizeof($vector)."\">";
						$informesRealizados=$this->ListaDeInformesRelizados($solicitud);
						if($informesRealizados){
            $this->salida .= "	  <table width=\"95%\" border=\"0\" class=\"normal_10\" align=\"center\">";
						for($i=0;$i<sizeof($informesRealizados);$i++){
						  if($informesRealizados[$i]['examen_firmado']==1){
							  $actionConsulta=ModuloGetURL('app','Patologia','user','LlamaConsultaInforme',array("solicitud"=>$solicitud,"prefijo"=>$informesRealizados[$i]['prefijo'],"informeId"=>$informesRealizados[$i]['resultado_informe_id'],
								"profesional"=>$informesRealizados[$i]['nomprofesional'],"consul"=>1,"usuariofirma"=>$informesRealizados[$i]['usuario_id_firma']));
								$this->salida .= "	  <tr class=\"$estilo1\"><td align=\"center\"><a href=\"$actionConsulta\" class=\"Menu\">";
								if($informesRealizados[$i]['prefijo']!='NULL'){
									$this->salida .= "	  ".$informesRealizados[$i]['prefijo']."";
								}
								$this->salida .= "	  ".$informesRealizados[$i]['resultado_informe_id']."";
								//$this->salida .= "	  <BR>".$informesRealizados[$i]['cargo']."";
								if(!empty($informesRealizados[$i]['nomprofesional'])){
								$this->salida .= "	  <BR>".$informesRealizados[$i]['nomprofesional']."";
								}
								//IMPRESION
								$mostrar=$rep->GetJavaReport('app','Patologia','examenes_html',array('resultado_id'=>$informesRealizados[$i]['resultado_informe_id'],"prefijo"=>$informesRealizados[$i]['prefijo']),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
								$nombre_funcion=$rep->GetJavaFunction();
								$this->salida .=$mostrar;
								$this->salida .= "	  <BR><a class=\"Menu\" href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a>";
								//FIN IMPRESION
								$this->salida .= "	  </a></td></tr>";
							}else{
                $actionModificacion=ModuloGetURL('app','Patologia','user','LlamaConsultaInforme',array("solicitud"=>$solicitud,"prefijo"=>$informesRealizados[$i]['prefijo'],"informeId"=>$informesRealizados[$i]['resultado_informe_id'],
								"profesional"=>$informesRealizados[$i]['nomprofesional'],"Modify"=>1,"usuariofirma"=>$informesRealizados[$i]['usuario_id_firma']));
								$this->salida .= "	  <tr class=\"$estilo1\"><td align=\"center\"><a href=\"$actionModificacion\">";
								if($informesRealizados[$i]['prefijo']!='NULL'){
									$this->salida .= "	  ".$informesRealizados[$i]['prefijo']."";
								}
								$this->salida .= "	  ".$informesRealizados[$i]['resultado_informe_id']."";
								//$this->salida .= "	  <BR>".$informesRealizados[$i]['cargo']."";
								if(!empty($informesRealizados[$i]['nomprofesional'])){
								  $this->salida .= "	  <BR>".$informesRealizados[$i]['nomprofesional']."";
								}
								$this->salida .= "	  </a></td></tr>";
							}
						}
            $this->salida .= "	  </table>";
						}
						$this->salida .= "	  </td>";
						$grupos=$this->tiposGruposPatologicos($solicitud);
						if($grupos){
            $this->salida .= "    <td rowspan=\"".sizeof($vector)."\"><select name=\"TipoInforme[$solicitud]\" class=\"select\">";
						$this->MostrarSelectGrupos($grupos,'False','');
						$this->salida .= "     </select></td>";
						}else{
            $this->salida .= "    <td align=\"center\" rowspan=\"".sizeof($vector)."\" class=\"label\">NO EXISTEN<br>TIPOS DE INFORMES</td>";
						}
						//$this->salida .= "    <td>".$datos['nomtejido']."</td>";
						//$this->salida .= "    <td align=\"center\" width=\"3%\"><input type=\"checkbox\" name=\"seleccion[$solicitud][]\" value=\"".$datos['tejido_id']."||//".$datos['nomtejido']."\"></td>";
						$solicitudAnt=$solicitud;
					}else{
					  $this->salida .= "    <tr class=\"$estilo\">";
						//$this->salida .= "    <td>".$datos['nomtejido']."</td>";
						//$this->salida .= "    <td align=\"center\" width=\"3%\"><input type=\"checkbox\" name=\"seleccion[$solicitud][]\" value=\"".$datos['tejido_id']."||//".$datos['nomtejido']."\"></td>";
						$this->salida .= "    </tr>";
					}
			  }
				$this->salida .= "    </tr>";
				$y++;
			}
			/*for($i=0;$i<sizeof($solicitudes);$i++){

				$this->salida .= "    <td>".$diags[$i]['diagnostico_nombre']."</td>";
				$action=ModuloGetURL('app','Patologia','user','SeleccionarDiagnosticoPatologia',array("codigoDiagnostico"=>$diags[$i]['diagnostico_id'],"nombreDiagnostico"=>$diags[$i]['diagnostico_nombre'],"origen"=>1,"solicitud"=>$solicitud,"nomgrupo"=>$nomgrupo,"grupo"=>$grupo,
				"tipoId"=>$tipoId,"PacienteId"=>$PacienteId,"nombre"=>$nombre,"fecha"=>$fecha,"descripcion_macro"=>$descripcion_macro,"descripcion_micro"=>$descripcion_micro,"firma"=>$firma,"NoInforme"=>$NoInforme));
				$this->salida .= "    <td width=\"5%\" nowrap align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
        $this->salida .= "    </tr>";
				$y++;
			}*/
      $this->salida .= "	   </table>";
			$this->salida .= "	   <table width=\"98%\" border=\"0\" align=\"center\">";
			$this->salida .= "     <tr><td align=\"right\"><input type=\"submit\" value=\"CREAR INFORME\" name=\"crearInforme\" class=\"input-submit\"></td></tr>";
			$this->salida .= "	   </table><br>";
			$this->salida .=$this->RetornarBarra(8);
		}else{
      $this->salida .= "	   <BR><table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida .= "     <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "	   </table><br>";
		}
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
	function MostrarSelectGrupos($arreglo,$Seleccionado='False',$valor=''){
		switch($Seleccionado){
			case 'False':{
			  if(empty($valor)){
				  $value=$arreglo[0]['grupo_tipo_cargo'].'||//'.$arreglo[0]['tipo_cargo'].'||//'.$arreglo[0]['prefijo'].'||//'.$arreglo[0]['resultado_informe_id'].'||//'.$arreglo[0]['descripcion'];
          $this->salida .=" <option value=\"$value\">".$arreglo[0]['prefijo']." ".$arreglo[0]['resultado_informe_id']."</option>";
				}
				for($i=1;$i<sizeof($arreglo);$i++){
				  $value=$arreglo[$i]['grupo_tipo_cargo'].'||//'.$arreglo[$i]['tipo_cargo'].'||//'.$arreglo[$i]['prefijo'].'||//'.$arreglo[$i]['resultado_informe_id'].'||//'.$arreglo[$i]['descripcion'];
					if($value==$valor){
					$this->salida .=" <option value=\"$value\" selected>".$arreglo[$i]['prefijo']." ".$arreglo[$i]['resultado_informe_id']."</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">".$arreglo[$i]['prefijo']." ".$arreglo[$i]['resultado_informe_id']."</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  for($i=0;$i<sizeof($arreglo);$i++){
				  $value=$arreglo[$i]['grupo_tipo_cargo'].'||//'.$arreglo[$i]['tipo_cargo'].'||//'.$arreglo[$i]['prefijo'].'||//'.$arreglo[$i]['resultado_informe_id'].'||//'.$arreglo[$i]['descripcion'];
				  if($value==$valor){
				    $this->salida .=" <option value=\"$value\" selected>".$arreglo[$i]['prefijo']." ".$arreglo[$i]['resultado_informe_id']."</option>";
				  }
				  $this->salida .=" <option value=\"$value\">".$arreglo[$i]['prefijo']." ".$arreglo[$i]['resultado_informe_id']."</option>";
			  }
			  break;
		  }
	  }
	}

	function InformeResultadoPatologico($observacionesInforme,$firma,$consulta,$profesional,$usuariofirma,$observacionesAdicionales,$patologoProfe,$Observas){

    if($consulta==1){
      $sololectura='readonly';
			$inabilitado='disabled';
		}
		$this->salida .= ThemeAbrirTabla('INFORME DE PATOLOGIA');
		$action=ModuloGetURL('app','Patologia','user','GuardarInformePatologico',array("Modify"=>$Modify,"consulta"=>$consulta));
		$this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "	  <input type=\"hidden\" name=\"patologoProfe\" value=\"$patologoProfe\">";
		$this->salida .= "	  <table width=\"85%\" border=\"0\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "    <tr>";
		$this->salida .= "      <td width=\"50%\" valign=\"top\">";
		$this->salida .= "      <fieldset><legend class=\"field\">DATOS PACIENTE Y SOLICITUD</legend>";
		$this->salida .= "      <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$datos=$this->DatosSolicitud();
		$this->salida .= "		   <td class=\"label\">IDENTIFICACION</td>";
		$this->salida .= "		   <td>".$datos[0]['tipo_id_paciente']." ".$datos[0]['paciente_id']."</td>";
		$this->salida .= "		   <td class=\"label\">EDAD</td>";
		$EdadArr=CalcularEdad($datos[0]['fecha_nacimiento'],$FechaFin);
		$this->salida .= "		   <td>".$EdadArr['edad_aprox']."</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "		   <td class=\"label\">NOMBRES</td>";
		$this->salida .= "		   <td colspan=\"3\">".$datos[0]['nombrepac']."</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "		  <td class=\"label\">No. SOLICITUD</td>";
		$this->salida .= "		  <td>".$_SESSION['DATOS_PATOLOGIA']['SOLICITUD']."</td>";
		$this->salida .= "		  <td class=\"label\">FECHA</td>";
		$this->salida .= "		  <td>".$datos[0]['fecha']."</td>";
		$this->salida .= "      </tr>";
		$this->salida .= "	    </table>";
		$this->salida .= "		  </fieldset></td>";
		$this->salida .= "      <td width=\"35%\" valign=\"top\">";
		$this->salida .= "      <fieldset><legend class=\"field\">TEJIDOS</legend>";
		$this->salida .= "      <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		for($i=0;$i<sizeof($datos);$i++){
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td>".$datos[$i]['nomtejido']."</td>";
			$this->salida .= "      </tr>";
		}
		$this->salida .= "	    </table>";
		$this->salida .= "		  </fieldset></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		</table>";

    $this->salida .= "    <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"2\">INFORME DE ".$_SESSION['DATOS_PATOLOGIA']['NOMBREINFO']." <BR>No. ".$_SESSION['DATOS_PATOLOGIA']['PREFIJO']." ".$_SESSION['DATOS_PATOLOGIA']['INFORME']."</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "    <td class=\"label\">OBSERVACIONES</td>";
		$this->salida .= "    <td><textarea name=\"observacionesInforme\" class =\"textarea\" rows =\"40\" cols =\"110\" $sololectura>$observacionesInforme</textarea></td>";
    $this->salida .= "    </tr>";
    if(!$consulta){
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
    //print_r($_SESSION['PATOLOGIA']['PROCEDIMIENTOS']);
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		$procedimientos=$_SESSION['PATOLOGIA']['PROCEDIMIENTOS'];
    $this->salida .= "    <td class=\"label\">PROCEDIMIENTOS</td>";
    $this->salida .= "    <td>";
    $this->salida .= "        <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		foreach($procedimientos as $codigo=>$vector){
      foreach($vector as $indice=>$nombre){
		  if($codigo){
      $this->salida .= "        <tr class=\"modulo_list_oscuro\">";
			$this->salida .= "        <td>$nombre</td>";
			if(!$consulta){
			$actionElimina=ModuloGetURL('app','Patologia','user','EliminaProcPatologiaResultado',array("codigo"=>$codigo,"observacionesInforme"=>$observacionesInforme,"firma"=>$firma,"patologoProfe"=>$patologoProfe));
			$this->salida .= "        <td width=\"5%\" nowrap align=\"center\"><a href=\"$actionElimina\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
			}
			$this->salida .= "        </tr>";
			}
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
			$actionElimina=ModuloGetURL('app','Patologia','user','EliminaDiagPatologiaResultado',array("codigo"=>$codigo,"observacionesInforme"=>$observacionesInforme,"firma"=>$firma,"patologoProfe"=>$patologoProfe));
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
		$tipoUser=$this->Tipo_Usuario_Log();
		if($consulta==1){
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
				$this->salida .= "    </tr>";
				}
			}
		}
		if($tipoUser==1){
		$this->salida .= "    <tr class=\"modulo_list_claro\">";
		if($firma==1){
		  $varc='checked';
    //$this->salida .= "    <td colspan=\"2\" align=\"center\" class=\"label\">FIRMADO POR:&nbsp&nbsp&nbsp&nbsp;$nombreProfesional</td>";
		}
		if(!$consulta){
    $this->salida .= "    <td colspan=\"2\" align=\"center\" class=\"label\"><input type=\"checkbox\" name=\"firma\" value=\"1\" $inabilitado $varc>&nbsp;APROBACION DEL RESULTADO AQUI REGISTRADO</td>";
		}else{
		$this->salida .= "    <td colspan=\"2\" align=\"center\" class=\"label\">FIRMADO POR:&nbsp&nbsp&nbsp&nbsp;$profesional</td>";
		$this->salida .= "    <input type=\"hidden\" name=\"profesional\" value=\"$profesional\">";
		}
    $this->salida .= "    </tr>";
    }else{
      $this->salida .= "    <tr class=\"modulo_list_claro\">";
			if($consulta){
      $this->salida .= "    <td colspan=\"2\" align=\"center\" class=\"label\">FIRMADO POR:&nbsp&nbsp&nbsp&nbsp;$profesional</td>";
      $this->salida .= "    <input type=\"hidden\" name=\"profesional\" value=\"$profesional\">";
			}else{
			$this->salida .= "    <td class=\"label\">PROFESIONAL PATOLOGO</td>";
      $this->salida .= "    <td>";
			$this->salida .= "          <select name=\"patologoProfe\" class=\"select\" $inabilitado>";
			$patologos=$this->TotalPatologos();
			$this->BuscarProfesionlesEspecialistas($patologos,'False',$patologoProfe);
			$this->salida .= "            </select>";
			$this->salida .= "    </td>";
			}
			$this->salida .= "    </tr>";
		}
		$this->salida .= "    <tr><td align=\"center\" colspan=\"2\">";
		if(($tipoUser!=1 && $consulta!=1)||($tipoUser==1)){
		$this->salida .= "    <input type=\"submit\" value=\"GUARDAR\" name=\"Guardar\" class=\"input-submit\">";
		}
		$this->salida .= "    <input type=\"submit\" value=\"REGRESAR\" name=\"Regresar\" class=\"input-submit\">";
		$this->salida .= "    </td></tr>";
    $this->salida .= "		</table>";
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
	function BuscarProfesionlesEspecialistas($profesionales,$Seleccionado='False',$Profesionales=''){

		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0;$i<sizeof($profesionales);$i++){
				  $value=$profesionales[$i]['tipo_id_tercero'].'||//'.$profesionales[$i]['tercero_id'];
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
			    $value=$profesionales[$i]['tipo_id_tercero'].'||//'.$profesionales[$i]['tercero_id'];
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

	function FormaBusquedaProcedimientosMuestras($solicitud,$tejidoId,$nomTejido,$tipoId,
		$PacienteId,$nombre,$fecha,$modificacion,$inadecuada,$observaciones){
    
    $this->salida .= ThemeAbrirTabla('BUSCADOR PROCEDIMIENTO PATOLOGICO');
		$action=ModuloGetURL('app','Patologia','user','SeleccionProcedimientosBusqueda',array("solicitud"=>$solicitud,"tejidoId"=>$tejidoId,"nomTejido"=>$nomTejido,"tipoId"=>$tipoId,
		"PacienteId"=>$PacienteId,"nombre"=>$nombre,"fecha"=>$fecha,"modificacion"=>$modificacion,"inadecuada"=>$inadecuada,"observaciones"=>$observaciones));
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
		$cups=$this->HallarTodosCupsPatologia($_REQUEST['codigoBus'],$_REQUEST['procedimientoBus']);
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
				$action=ModuloGetURL('app','Patologia','user','SeleccionarProcedimientodeMuestra',array("cargoSelect"=>$cups[$i]['cargo'],"descripcionSelect"=>$cups[$i]['descripcion'],
				"solicitud"=>$solicitud,"tejidoId"=>$tejidoId,"nomTejido"=>$nomTejido,"tipoId"=>$tipoId,
		    "PacienteId"=>$PacienteId,"nombre"=>$nombre,"fecha"=>$fecha,"modificacion"=>$modificacion,"inadecuada"=>$inadecuada,"observaciones"=>$observaciones));
				$this->salida .= "    <td width=\"5%\" nowrap align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
        $this->salida .= "    </tr>";
			}
      $this->salida .= "	   </table>";
			$this->salida .=$this->RetornarBarra(9);
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

	function FormaAsignarNumeracionInforme($solicitud,$tipoId,$PacienteId,
		$nombre,$fecha,$tipoCargo,$tipoInformeNombre,$grupoTipoCargo){
		
    $this->salida .= ThemeAbrirTabla('ASIGNACION DE CONSECUTIVO TEJIDOS PATOLOGICOS');
    $action=ModuloGetURL('app','Patologia','user','AsignacionConsecutivoTejidos',array("solicitud"=>$solicitud,"tipoId"=>$tipoId,
		"PacienteId"=>$PacienteId,"nombre"=>$nombre,"fecha"=>$fecha,"tipoCargo"=>$tipoCargo,"tipoInformeNombre"=>$tipoInformeNombre,"grupoTipoCargo"=>$grupoTipoCargo));
	  $this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "	  <table width=\"50%\" border=\"0\" class=\"normal_10\" align=\"center\">";
    $this->salida .= "    <tr><td>";
		$this->salida .= "    <fieldset><legend class=\"field\">DATOS DE LA SOLICITUD</legend>";
		$this->salida .= "    <BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">PACIENTE</td>";
		$this->salida .= "		<td colspan=\"3\">$tipoId $PacienteId $nombre</td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
		$this->salida .= "		<td class=\"label\">SOLICITUD</td>";
		$this->salida .= "		<td>$solicitud</td>";
		$this->salida .= "		<td class=\"label\">FECHA</td>";
		$this->salida .= "		<td>$fecha</td>";
    $this->salida .= "		</tr>";
    $this->salida .= "		</table><BR>";
		$this->salida .= "		</fieldset></td></tr>";
		$this->salida .= "		</table><br>";
		$tejidos=$this->TejidosProcedimientosxGrupo($solicitud,$tipoCargo,$grupoTipoCargo);
    $this->salida .= "	   <table width=\"50%\" border=\"0\" class=\"modulo_table_list\" align=\"center\">";
		$this->salida .= "	   <tr class=\"modulo_table_list_title\">";
		$this->salida .= "	   <td colspan=\"2\">TEJIDOS DE LA SOLICITUD CON PROCEDIMIENTOS TIPO $tipoInformeNombre</td>";
		$y=0;
		for($i=0;$i<sizeof($tejidos);$i++){
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "    <tr class=\"$estilo\">";
			$this->salida .= "    <td>".$tejidos[$i]['descripcion']."</td>";
			$this->salida .= "    <td width=\"5%\"><input type=\"checkbox\" checked name=\"tejidosLista[]\" value=\"".$tejidos[$i]['tejido_id']."\"></td>";
			$this->salida .= "    </tr>";
		}
    $this->salida .= "	   </table>";
		$this->salida .= "	   <table width=\"50%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\">";
		$this->salida .= "    <input type=\"submit\" name=\"asignar\" value=\"ASIGNAR\" class=\"input-submit\">";
		$this->salida .= "    <input type=\"submit\" name=\"cancelar\" value=\"CANCELAR\" class=\"input-submit\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "	   </table>";
    $this->salida .= "    </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	* La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
	* @return boolean
	* @param string mensaje a retornar para el usuario
	* @param string titulo de la ventana a mostrar
	* @param string lugar a donde debe retornar la ventana
	* @param boolean tipo boton de la ventana
	*/
	function FormaConfirmacionDatos($mensaje,$titulo,$accionAceptar,$accionCancelar){

		$this->salida .= ThemeAbrirTabla($titulo);
		$this->salida .= "			    <table border=\"0\" class=\"normal_10\" width=\"60%\" align=\"center\">";
		$this->salida .= "				  <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
		$this->salida .= "				  <tr>";
		$this->salida .= "          <form name=\"formabuscar\" action=\"$accionAceptar\" method=\"post\">";
		$this->salida .= "				       <td align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td>";
		$this->salida .= "			     </form>";
		$this->salida .= "          <form name=\"formabuscar1\" action=\"$accionCancelar\" method=\"post\">";
		$this->salida .= "				       <td align=\"left\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"Volver\"></td>";
		$this->salida .= "			     </form>";
		$this->salida .= "				   </tr>";
		$this->salida .= "			     </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




//pg_dump -u -s SIIS2 > /home/lorena/Desktop/alex.sql
}//fin clase user
?>

