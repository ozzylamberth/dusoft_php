<?php

/**
 * $Id: app_Pacientes_userclasses_HTML.php,v 1.30 2006/12/22 15:06:28 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo visual de los datos del paciente.
 */

/**
* Clase app_Pacientes_userclasses_HTML
*
* Contiene los metodos visuales para el tratamiento de los datos del paciente
*/

class app_Pacientes_userclasses_HTML extends app_Pacientes_user
{

  /**
  *Constructor de la clase app_Pacientes_user_HTML
  *El constructor de la clase app_Triage_user_HTML se encarga de llamar
  *a la clase app_Pacientes_user quien se encarga de el tratamiento
  * de la base de datos.
  */

  /**
  * Es el contructor de la clase.
  * Se encarga de llamar a la clase app_Pacientes_user_HTML  quien se encarga del tratamiento de la base de datos.
  * @return boolean
  */
  function app_Pacientes_user_HTML()
  {
        $this->salida='';
        $this->app_Pacientes_user();
        return true;
  }


  /**
  * Se encarga de mostrar los errores.
  * @access private
  * @return string
  */
  function SetStyle($campo)
  {
        if ($this->frmError[$campo] || $campo=="MensajeError"){
          if ($campo=="MensajeError"){
            return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
          }
          return ("label_error");
        }
      return ("label");
  }

  /**
  * Metodos para realizar la busqueda de un paciente para modificar sus datos
  * @access private
  * @return boolean
  * @param array arreglo con los datos del paciente fallecido
  * @param array accion de la forma
  */
  function FormaMensajePacienteFallecido($vars,$accion)
  {
        $this->salida .= ThemeAbrirTabla('PACIENTES - BUSCAR PACIENTE');
        $this->salida .= "            <br><br>";
        $this->salida .= "            <table width=\"50%\" align=\"center\"  border=\"0\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "           <tr>";
        $this->salida .= "           <td>El paciente $vars[tipo_id_paciente] $vars[paciente_id] : $vars[primer_nombre] $vars[segundo_nombre] $vars[primer_apellido] $vars[segundo_apellido] es registrado como fallecido en el sistema.</td>";
        $this->salida .= "           </tr>";
        if(!empty($accion))
        {
            $this->salida .= "               <tr>";
            $actionCancelar=ModuloGetURL($accion['contenedor'],$accion['modulo'],$accion['tipo'],$accion['metodo']);
            $this->salida .= "                   <form name=\"formabuscar\" action=\"$actionCancelar\" method=\"post\">";
            $this->salida .= "                   <td colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"CANCELAR\"></form></td>";
            $this->salida .= "               </tr>";
        }
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

  /**
  * Metodos para realizar la busqueda de un paciente para modificar sus datos
  * @access private
  * @return boolean
  * @param array arreglo con los datos de un ingreso existente de un paciente
  * @param int numero de la cuenta
  * @param array con la accion  de la forma
  */
  function FormaExisteIngreso($vars,$cuenta,$accion,$msg)
  {
        $this->salida .= ThemeAbrirTabla('PACIENTES - BUSCAR PACIENTE');
        $this->salida .= "            <br><br>";
        $this->salida .= "            <table width=\"35%\" align=\"center\"  border=\"0\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                   <td colspan=\"3\" class=\"label_error\">EL PACIENTE YA TIENE UNA CUENTA ABIERTA</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $this->salida .= "                   <td class=\"label\" width=\"42%\">PACIENTE: </td>";
        $this->salida .= "                   <td colspan=\"2\">$vars[primer_nombre] $vars[segundo_nombre] $vars[primer_apellido] $vars[segundo_apellido]</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $this->salida .= "                   <td class=\"label\">IDENTIFICACION: </td>";
        $this->salida .= "                   <td colspan=\"2\">$vars[tipo_id_paciente] $vars[paciente_id]</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $this->salida .= "                   <td class=\"label\">CUENTA No. : </td>";
        $this->salida .= "                   <td colspan=\"2\">".$cuenta[numerodecuenta]."</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $this->salida .= "                   <td colspan=\"3\" class=\"label_mark\" align=\"center\">".$msg."</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $actionCancelar=ModuloGetURL($accion['contenedor'],$accion['modulo'],$accion['tipo'],$accion['metodo']);
        $this->salida .= "                   <form name=\"formabuscar\" action=\"$actionCancelar\" method=\"post\">";
        $this->salida .= "                   <td colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"CANCELAR\"></form></td>";
        $this->salida .= "               </tr>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }



  function ConsultaHomo()
  {
    $this->salida .= "<SCRIPT>";
    $this->salida .= "function ConsultaHomo(nombre, url, ancho, altura,Tipo,Paciente,Tabla){";
    $this->salida .= " var str = 'width='+ancho+',height='+altura+',X=300,Y=800,resizable=no,status=no,scrollbars=yes';";
    $this->salida .= " var url2 = url+'?TipoId='+Tipo+'&PacienteId='+Paciente+'&Tabla='+Tabla;";
    $this->salida .= " rem = window.open(url2, nombre, str);";
    $this->salida .= "  if (rem != null) {";
    $this->salida .= "     if (rem.opener == null) {";
    $this->salida .= "       rem.opener = self;";
    $this->salida .= "     }";
    $this->salida .= "  }";
    $this->salida .= "}";
    $this->salida .=  "</SCRIPT>";
  }

  /**
  * Muestra los homonimos por documento encontrados
  * @access private
  * @return boolean
  * @param array arreglo con los datos de los homonimos
  * @param array con la accion  de la forma
  * @param int plan_id
  * @param string tipo de documento
  * @param array con la accion  de continuar
  */
  function FormaHomonimosDocumento($Homonimos,$accion,$Plan,$TipoDocumento,$Documento)
  {
        global $VISTA;
        $this->ConsultaHomo();
        $this->salida .= ThemeAbrirTabla('PACIENTES - HOMONIMOS DEL DOCUMENTO');
        $this->salida .= " <br><table cellspacing=\"3\"  cellpadding=\"3\" border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr><td colspan=\"4\" class=\"modulo_table_title\">DATOS PACIENTE ACTUAL</td></tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "       <td class=\"modulo_table_list_title\" width=\"10%\">TIPO:</td><td class=\"modulo_list_claro\">$TipoDocumento</td>";
        $this->salida .= "       <td class=\"modulo_table_list_title\" width=\"10%\">IDENTIFICACION:</td><td class=\"modulo_list_claro\">$Documento</td>";
        $this->salida .= "     </tr>";
        $this->salida .= "    </table><br>";
        $this->salida .= "         <br><table cellspacing=\"3\"  cellpadding=\"3\" border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "                <tr class=\"modulo_table_list_title\"  align=\"center\">";
        $this->salida .= "                  <td width=\"15%\">IDENTIFICACION</td>";
        $this->salida .= "                  <td  width=\"100%\">PACIENTE</td>";
        $this->salida .= "                  <td></td>";
        $this->salida .= "                  <td></td></tr>";
        for($i=0; $i<sizeof($Homonimos); $i++)
        {
          if($i % 2){  $estilo='modulo_list_claro';  }
          else{  $estilo='modulo_list_oscuro';  }
        $this->salida .= "                  <tr class=\"$estilo\">";
        $this->salida .= "                  <td>". $Homonimos[$i][tipo_id_paciente]." ".$Homonimos[$i][paciente_id]."</td>";
        $this->salida .= "                  <td>". $Homonimos[$i][primer_nombre]." ".$Homonimos[$i][segundo_nombre]." ".$Homonimos[$i][primer_apellido]." ".$Homonimos[$i][segundo_apellido]."</td>";
        $action=ModuloGetURL($accion['contenedor'],$accion['modulo'],$accion['tipo'],$accion['metodo'],array('TipoId'=>$TipoDocumento,'PacienteId'=>$Homonimos[$i][paciente_id],'PlanId'=>$Plan));
        $this->salida .= "                  <td><a href=\"$action\">Atras</a></td>";
        $this->salida .= "                  <td><a href=\"javascript:ConsultaHomo('DATOS DEL HOMONIMO','reports/$VISTA/datospaciente.php',500,400,'".$Homonimos[$i][tipo_id_paciente]."',".$Homonimos[$i][paciente_id].",'pacientes')\">Consultar</a></td>";
        $this->salida .= "                  </tr>";
        }
				$argu=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
				$argu['HOMONIMO'] =true;
				$argu['tipoafiliado']=$_REQUEST['tipoafiliado'];
				$argu['rango']=$_REQUEST['rango'];
				$Contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
				$Modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
				$Tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
				$Metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
				$accionC=ModuloGetURL($Contenedor,$Modulo,$Tipo,$Metodo,$argu);
				//$accionC=ModuloGetURL($Contenedor,$Modulo,$Tipo,$Metodo,array('HOMONIMO'=>true));
        //$accionC=ModuloGetURL('app','Pacientes','user','PedirDatos');
        $this->salida .= "                  <tr>";
        $this->salida .= "                   <form name=\"formabuscar\" action=\"$accionC\" method=\"post\">";
        $this->salida .= "                    <td colspan=\"4\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"CONTINUAR\"></td>";
        $this->salida .= "                    </form>";
        $this->salida .= "                  </tr>";
        $this->salida .= "    </table><br>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

  /**
  * Muestra los homonimos por nombre encontrados
  * @access private
  * @return boolean
  * @param array arreglo con los datos de los homonimos
  * @param array con la accion  de la forma
  * @param int plan_id
  */
  function FormaHomonimosNombres($Homonimos,$accion,$Plan,$TipoId,$PacienteId,$nom)
  {
        global $VISTA;
        $this->ConsultaHomo();
        $this->salida .= ThemeAbrirTabla('PACIENTES - HOMONIMOS NOMBRE');


        $this->salida .= " <br><table cellspacing=\"3\"  cellpadding=\"3\" border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr><td colspan=\"4\" class=\"modulo_table_title\">DATOS PACIENTE ACTUAL</td></tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "       <td class=\"modulo_table_list_title\" width=\"10%\">TIPO:</td><td class=\"modulo_list_claro\">$TipoId</td>";
        $this->salida .= "       <td class=\"modulo_table_list_title\" width=\"10%\">IDENTIFICACION:</td><td class=\"modulo_list_claro\">$PacienteId</td>";
        $this->salida .= "     </tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "       <td class=\"modulo_table_list_title\" width=\"10%\">NOMBRE:</td><td class=\"modulo_list_claro\" colspan=\"3\">$nom</td>";
        $this->salida .= "     </tr>";
        $this->salida .= "    </table><br>";

        $this->salida .= "         <br><table cellspacing=\"3\"  cellpadding=\"3\" border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "                <tr class=\"modulo_table_list_title\"  align=\"center\">";
        $this->salida .= "                  <td width=\"15%\">IDENTIFICACION</td>";
        $this->salida .= "                  <td  width=\"100%\">PACIENTE</td>";
        $this->salida .= "                  <td></td>";
        $this->salida .= "                  <td></td></tr>";
        for($i=0; $i<sizeof($Homonimos); $i++)
        {
          if($i % 2){  $estilo='modulo_list_claro';  }
          else{  $estilo='modulo_list_oscuro';  }
        $this->salida .= "                  <tr class=\"$estilo\">";
        $this->salida .= "                  <td>". $Homonimos[$i][tipo_id_paciente]." ".$Homonimos[$i][paciente_id]."</td>";
        $this->salida .= "                  <td>". $Homonimos[$i][primer_nombre]." ".$Homonimos[$i][segundo_nombre]." ".$Homonimos[$i][primer_apellido]." ".$Homonimos[$i][segundo_apellido]."</td>";
        //$action=ModuloGetURL($accion['contenedor'],$accion['modulo'],$accion['tipo'],$accion['metodo'],array('TipoId'=>$Homonimos[$i][tipo_id_paciente],'PacienteId'=>$Homonimos[$i][paciente_id],'PlanId'=>$Plan));
        $action=ModuloGetURL('app','Pacientes','user','PedirDatos',array('TipoId'=>$Homonimos[$i][tipo_id_paciente],'PacienteId'=>$Homonimos[$i][paciente_id],'PlanId'=>$Plan));
        $this->salida .= "                  <td><a href=\"$action\">Atras</a></td>";
        $this->salida .= "                  <td><a href=\"javascript:ConsultaHomo('DATOS DEL HOMONIMO','reports/$VISTA/datospaciente.php',500,400,'".$Homonimos[$i][tipo_id_paciente]."',".$Homonimos[$i][paciente_id].",'pacientes')\">Consultar</a></td>";
        $this->salida .= "                  </tr>";
        }
        $this->salida .= "                  <tr>";
        $this->salida .= "                   <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "                    <td colspan=\"4\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"CONTINUAR\"></td>";
        $this->salida .= "                    </form>";
        $this->salida .= "                  </tr>";
        $this->salida .= "    </table><br>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }


  /**
  * Forma para la captura de los datos del acudiente
  * @access private
  * @return boolean
  * @param int ingreso
  */
  function FormaDatosAcudiente()
  {
        $accion=ModuloGetURL('app','Pacientes','user','CapturaDatosAcudiente',array('Ingreso'=>$IngresoId));
        $this->salida .= ThemeAbrirTabla('PACIENTES - DATOS ACUDIENTE PACIENTE');
				$acu=$this->BuscarAcudientes();
				if($acu)
				{
						$this->salida .= "		   <br>";
						$this->salida .= "		<table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
						$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
						$this->salida .= "				<td>ACUDIENTE</td>";
						$this->salida .= "				<td>PARENTESCO</td>";
						$this->salida .= "				<td>DIRECCION</td>";
						$this->salida .= "				<td>TELEFONO</td>";
						$this->salida .= "				<td width=\"3%\"></td>";
						$this->salida .= "			</tr>";
						for($i=0; $i<sizeof($acu); $i++)
						{
										if( $i % 2) $estilo='modulo_list_claro';
										else $estilo='modulo_list_oscuro';
										$this->salida .= "			<tr class=\"$estilo\">";
										$this->salida .= "				<td>".$acu[$i][nombre_completo]."</td>";
										$this->salida .= "				<td>".$acu[$i][descripcion]."</td>";
										$this->salida .= "				<td align=\"center\">".$acu[$i][direccion]."</td>";
										$this->salida .= "				<td align=\"center\">".$acu[$i][telefono]."</td>";
										$accion2=ModuloGetURL('app','Pacientes','user','EliminarAcudiente',array('idAcudiente'=>$acu[$i][contacto]));
										$this->salida .= " 				<td align=\"center\"><a href='$accion2'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0' alt='ELIMINAR'></a></td>";
										$this->salida .= "			</tr>";
						}
						$this->salida .= "  </table>";
				}
        $this->salida .= "  <br>";
        $this->salida .= "  <table width=\"60%\" cellspacing=\"2\" border=\"0\" cellpadding=\"0\" align=\"center\" >";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "    <tr>";
        $this->salida .= "      <td class=\"label_error\" colspan=\"3\" align=\"center\">$mensaje<br><br></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "    <tr height=\"20\">";
        $this->salida .= "      <td class=\"".$this->SetStyle("Nombre")."\">NOMBRE COMPLETO: </td>";
        $this->salida .= "      <td><input type=\"text\" size=\"35\" name=\"Nombre\" value=\"".$_REQUEST['Nombre']."\" class=\"input-text\"></td>";
        $this->salida .= "      <td></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr height=\"20\">";
        $this->salida .= "      <td class=\"label\">DIRECCION: </td>";
        $this->salida .= "      <td><input type=\"text\" size=\"35\" name=\"Direccion\" value=\"".$_REQUEST['Direccion']."\" class=\"input-text\"></td>";
        $this->salida .= "      <td></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr height=\"20\">";
        $this->salida .= "      <td class=\"label\">TELEFONO: </td>";
        $this->salida .= "      <td><input type=\"text\" size=\"35\" name=\"Telefono\" value=\"".$_REQUEST['Telefono']."\" class=\"input-text\"></td>";
        $this->salida .= "      <td></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr height=\"20\"><td class=\"".$this->SetStyle("Parentesco")."\">PARENTESCO: </td><td><select name=\"Parentesco\"  class=\"select\">";
        $TiposParentesco=$this->TiposParentescos();
        $this->BuscarParentesco($TiposParentesco,$_REQUEST['Parentesco']);
        $this->salida .= "       </select></td></tr>";
        $this->salida .= "    <tr height=\"20\">";
        $this->salida .= "      <td class=\"label\">OBSERVACIONES: </td>";
        $this->salida .= "      <td colspan=\"2\"><textarea name=\"Observaciones\" cols=\"55\" rows=\"3\" class=\"textarea\">".$_REQUEST['Observaciones']."</textarea>";
        $this->salida .= "      </td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr height=\"20\">";
        $this->salida .= "    <td  colspan=\"1\" align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
        $this->salida .= "  </form>";
				$argu=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
				$Contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
				$Modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
				$Tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
				$Metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
				$accionC=ModuloGetURL($Contenedor,$Modulo,$Tipo,$Metodo,$argu);
        $this->salida .= "  <form name=\"forma\" action=\"$accionC\" method=\"post\">";
        $this->salida .= "    <td  colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"></td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "</table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

  /**
  * Muestra la forma para capturar los datos de un paciente NN.
  * @access private
  * @return boolean
  * @param string tipo documento
  * @param int numero documento
  * @param string mensaje
  * @param string accion de la forma
  * @param int plan_id
  * @param date fecha nacimiento
  * @param string codigo del pais
  * @param string codigo del dpto
  * @param string codigo del mpio
  */
  function FormaNN($TipoId,$PacienteId,$Responsable,$Nivel)
  {
				$datos=$this->BuscarPaciente($TipoId,$PacienteId);
				if(is_array($datos))
        {
							$PrimerApellido=$datos['primer_apellido'];
							$SegundoApellido=$datos['segundo_apellido'];
							$PrimerNombre=$datos['primer_nombre'];
							$SegundoNombre=$datos['segundo_nombre'];
							$FechaNacimiento=$datos['fecha_nacimiento'];
							if(empty($FechaNacimiento))
							{
									$FechaNacimiento=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_fecha_nacimiento'];
							}
							$FechaRegistro=$datos['fecha_registro'];
							$Direccion=$datos['residencia_direccion'];
							$Telefono=$datos['residencia_telefono'];
							$Ocupacion=$datos['ocupacion_id'];
							$Sexo=$datos['sexo_id'];
							$EstadoCivil=$datos['tipo_estado_civil_id'];
							$Pais=$datos['tipo_pais_id'];
							$Dpto=$datos['tipo_dpto_id'];
							$Mpio=$datos['tipo_mpio_id'];
							$Mama=$datos['nombre_madre'];
							$hc=$datos['historia_numero'];
							$prefijo=$datos['historia_prefijo'];
							$accion=ModuloGetURL('app','Pacientes','user','ActualizarDatosPaciente');
							$ZonaResidencia=$datos['zona_residencia'];
							$FechaNacimientoCalculada=$datos['fecha_nacimiento_es_calculada'];
							$Observaciones=$datos['Observaciones'];
        }
				else
				{
            $PrimerApellido=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_Primer_apellido'];
            $SegundoApellido=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_Segundo_apellido'];
            $PrimerNombre=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_Primer_nombre'];
            $SegundoNombre=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_Segundo_nombre'];
            $FechaNacimiento=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_fecha_nacimiento'];
		        $Sexo=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_sexo'];
            $accion=$_REQUEST['accion'];
            $Edad=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_edad'];
						$Direccion=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_direccion_afiliado'];
						$Telefonocion=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_telefono_afiliado'];
						if(!empty($_REQUEST['SegundoApellido']))
						{  $SegundoApellido=$_REQUEST['SegundoApellido'];  }
						if(!empty($_REQUEST['SegundoNombre']))
						{  $SegundoNombre=$_REQUEST['SegundoNombre'];  }
						if(!empty($_REQUEST['Direccion']))
						{  $Direccion=$_REQUEST['Direccion'];  }
						if(!empty($_REQUEST['Telefono']))
						{  $Telefono=$_REQUEST['Telefono'];  }
						if(!empty($_REQUEST['ocupacion_id']))
						{  $Ocupacion=$_REQUEST['ocupacion_id'];  }
						if(!empty($_REQUEST['Sexo']))
						{  $Sexo=$_REQUEST['Sexo'];  }
						if(!empty($_REQUEST['EstadoCivil']))
						{  $EstadoCivil=$_REQUEST['EstadoCivil'];  }
						if(!empty($_REQUEST['Mama']))
						{  $Mama=$_REQUEST['Mama'];  }

						$accion=ModuloGetURL('app','Pacientes','user','ValidarDatosPacienteNew');
				}

        if(!$PrimerApellido && !$PrimerNombre)
        {
            $PrimerNombre='NN';
            $PrimerApellido='NN';
        }
				//los campos obligatorios
				$campo=$this->BuscarCamposObligatorios();
				//$accion=ModuloGetURL('app','Pacientes','user','ValidarDatosPacienteNew');
				$ru='classes/BuscadorDestino/selectorCiudad.js';
				$rus='classes/BuscadorDestino/selector.php';
				$this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
				$this->salida .= ThemeAbrirTabla('PACIENTES - DATOS PACIENTE NN');
        if(!empty($_SESSION['PACIENTES']['PACIENTE']['ARREGLO']))
        {
            $this->salida .= "           <br><table width=\"50%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .= "               <tr>";
            $this->salida .= "                  <td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\">DATOS AFILIADO</td>";
            $this->salida .= "               </tr>";
            $a=ImplodeArrayAssoc($_SESSION['PACIENTES']['PACIENTE']['ARREGLO']);
            $arreglon=ExplodeArrayAssoc($a);
						$plantilla=$this->PlantilaBD($_SESSION['PACIENTES']['PACIENTE']['plan_id']);
            $i=0;
            foreach($arreglon as $k => $v)
            {
									$mostrar='';
									$mostrar=$this->CamposMostrarBD($k,$plantilla);
									if(!empty($mostrar[sw_mostrar]))
									{
											if($i % 2) {  $estilo="modulo_list_claro";  }
											else {  $estilo="modulo_list_oscuro";   }
											$this->salida .= "         <tr class=\"$estilo\">";
											if(!empty($mostrar[nombre_mostrar]))
											{  $k=$mostrar[nombre_mostrar];}
											$this->salida .= "            <td align=\"center\">$k</td>";
											$this->salida .= "            <td align=\"center\">$v</td>";
											$this->salida .= "        </tr>";
											$i++;
									}
            }
            $this->salida .= "           </table>";
        }
				$this->salida .= "  <br>";
				$this->salida .= "  <table width=\"60%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" class=\"normal_10\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "    <tr>";
				$this->salida .= "      <td class=\"label_error\" colspan=\"3\" align=\"center\">$mensaje<br><br></td>";
				$this->salida .= "    </tr>";
				$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
				$this->salida .= "       <input type=\"hidden\" name=\"Forma\" value=\"FormaNN\" class=\"input-text\" ></td>";
				$this->salida .= "       <input type=\"hidden\" name=\"Responsable\" value=\"$Responsable\" class=\"input-text\" ></td>";
				$Zona=GetVarConfigAplication('DefaultZona');					
        $this->salida .= "<input type=\"hidden\" name=\"Zona\" value=\"$Zona\">";				
				if($campo[historia_prefijo][sw_mostrar]==1 AND $campo[historia_numero][sw_mostrar]==1 AND $campo[historia_numero][sw_obligatorio]==1
						AND !is_array($datos))
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_prefijo][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">* PREFIJO: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">PREFIJO: </td>";  }
						$this->salida .= "      <td><input type=\"text\" maxlength=\"4\" name=\"prefijo\" value=\"$prefijo\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				elseif($campo[historia_prefijo][sw_mostrar]==1 AND $campo[historia_numero][sw_mostrar]==1 AND $campo[historia_numero][sw_obligatorio]==1
						AND is_array($datos) AND !empty($prefijo))
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_prefijo][sw_obligatorio]==1)
						{  $this->salida .= "      <td  height=\"20\" class=\"".$this->SetStyle("prefijo")."\">* PREFIJO: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">PREFIJO: </td>";  }
						$this->salida .= "      <td  height=\"25\"><input type=\"hidden\" name=\"prefijo\" value=\"$prefijo\">$prefijo</td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				elseif($campo[historia_prefijo][sw_mostrar]==1 AND $campo[historia_numero][sw_mostrar]==1 AND $campo[historia_numero][sw_obligatorio]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_prefijo][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">* PREFIJO: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">PREFIJO: </td>";  }
						$this->salida .= "      <td><input type=\"text\" maxlength=\"4\" name=\"prefijo\" value=\"$prefijo\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				if($campo[historia_numero][sw_mostrar]==1 AND !is_array($datos))
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_numero][sw_obligatorio]==1)
						{  $this->salida .= "      <td  height=\"20\" class=\"".$this->SetStyle("historia")."\">* No. HISTORIA: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">No. HISTORIA: </td>";  }
						$this->salida .= "      <td  height=\"25\"><input type=\"text\" maxlength=\"50\" name=\"historia\" value=\"$hc\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				elseif($campo[historia_numero][sw_mostrar]==1 AND is_array($datos) AND !empty($hc))
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_numero][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">* No. HISTORIA: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">No. HISTORIA: </td>";  }
						$this->salida .= "      <td height=\"20\"><input type=\"hidden\" name=\"historia\" value=\"$hc\">$hc</td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				elseif($campo[historia_numero][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_numero][sw_obligatorio]==1)
						{  $this->salida .= "      <td  height=\"20\" class=\"".$this->SetStyle("historia")."\">* No. HISTORIA: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">No. HISTORIA: </td>";  }
						$this->salida .= "      <td  height=\"25\"><input type=\"text\" maxlength=\"50\" name=\"historia\" value=\"$hc\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td></td>";
				$this->salida .= "      <td>&nbsp;</td>";
				$this->salida .= "       <td></td>";
				$this->salida .= "    </tr>";
				if(!empty($Responsable))
				{
						$NombrePlan=$this->NombrePlan($Responsable);
						$this->salida .= "    <tr height=\"20\">";
						$this->salida .= "      <td class=\"label\"  height=\"20\">RESPONSABLE: </td>";
						$this->salida .= "      <td>$NombrePlan[plan_descripcion]</td>";
						$this->salida .= "       <td></td>";
						$this->salida .= "    </tr>";
				}
				$this->salida .= "    <tr height=\"20\"><td class=\"label\"  height=\"20\">TIPO DOCUMENTO: </td><td>";
				$Tipo=$this->mostrar_id_paciente($TipoId);
				$this->salida .= "    <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\" readonly>$Tipo</td></tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"label\">DOCUMENTO: </td>";
				$this->salida .= "      <td  height=\"20\"><input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\" readonly>$PacienteId</td>";
				$this->salida .= "       <td>  </td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"label\">PRIMER NOMBRE: </td>";
				$this->salida .= "      <td><input type=\"text\" maxlength=\"20\" name=\"PrimerNombre\" value=\"$PrimerNombre\" class=\"input-text\"></td>";
				$this->salida .= "      <td></td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"label\">PRIMER APELLIDO: </td>";
				$this->salida .= "      <td><input type=\"text\" maxlength=\"30\" name=\"PrimerApellido\" value=\"$PrimerApellido\" class=\"input-text\"></td>";
				$this->salida .= "      <td></td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"".$this->SetStyle("FechaNacimiento")."\">* EDAD CALCULADA: </td>";
				$this->salida .= "      <td colspan=\"2\"><input type=\"text\" name=\"FechaNacimientoCalculada\" value=\"".$_REQUEST['FechaNacimientoCalculada']."\" class=\"input-text\" size=\"6\">";
				$this->salida .= "      <select name=\"Edad\"  class=\"select\">";
				if($_REQUEST['Edad']==3)
				{  $this->salida .= "       <option value=\"3\" selected>A?os</option>";  }
				else
				{  $this->salida .= "       <option value=\"3\">A?os</option>";  }
				if($_REQUEST['Edad']==1)
				{  $this->salida .= "       <option value=\"1\" selected>D?as</option>";  }
				else
				{  $this->salida .= "       <option value=\"1\">D?as</option>";  }
				if($_REQUEST['Edad']==2)
				{  $this->salida .= "       <option value=\"2\" selected>Meses</option>";  }
				else
				{  $this->salida .= "       <option value=\"2\">Meses</option>";  }
				$this->salida .= "     </select></td>";
				$this->salida .= "    </tr>";
				//---------------------------------------------------------------
        if(!$Pais)
        {
						$Pais=GetVarConfigAplication('DefaultPais');
						$Dpto=GetVarConfigAplication('DefaultDpto');
						$Mpio=GetVarConfigAplication('DefaultMpio');
        }
				//si mostrar el pais depto y mpio
				if($campo[lugar_residencia][sw_mostrar]==1 AND $campo[lugar_residencia][sw_obligatorio]==1)
				{
						$this->salida .= "    <tr height=\"15\">";
						$this->salida .= "      <td class=\"".$this->SetStyle("pais")."\">* PAIS: </td>";
						$NomPais=$this->nombre_pais($Pais);
						$this->salida .= "      <td><input type=\"text\" name=\"npais\" value=\"$NomPais\" class=\"input-text\" readonly>";
						$this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"$Pais\" class=\"input-text\"></td>";
						$this->salida .= "       <td>  </td>";
						$this->salida .= "    </tr>";
						$this->salida .= "    <tr height=\"15\">";
						$this->salida .= "      <td class=\"".$this->SetStyle("dpto")."\">* DEPARTAMENTO: </td>";
						$NomDpto=$this->nombre_dpto($Pais,$Dpto);
						$this->salida .= "      <td><input type=\"text\" name=\"ndpto\" value=\"$NomDpto\" class=\"input-text\" readonly>";
						$this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"$Dpto\" class=\"input-text\"></td>";
						$this->salida .= "       <td>  </td>";
						$this->salida .= "    </tr>";
						$this->salida .= "    <tr height=\"15\">";
						$this->salida .= "      <td class=\"".$this->SetStyle("mpio")."\">* CIUDAD: </td>";
						$NomCiudad=$this->nombre_ciudad($Pais,$Dpto,$Mpio);
						$this->salida .= "      <td><input type=\"text\" name=\"nmpio\"  value=\"$NomCiudad\" class=\"input-text\" readonly>";
						$this->salida .= "       <input type=\"hidden\" name=\"mpio\" value=\"$Mpio\" class=\"input-text\" ></td>";
						$this->salida .= "       <td align=\"left\"><input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\"></td>";
						$this->salida .= "    </tr>";
						if($campo[tipo_comuna_id][sw_mostrar]==1)
						{
								$this->salida .= "    <tr height=\"15\">";
								if($campo[tipo_comuna_id][sw_obligatorio]==1)
								{  $this->salida .= "      <td class=\"".$this->SetStyle("comuna")."\">*  ".ModuloGetVar('app','Pacientes','NombreComuna').": </td>";   }
								else
								{  $this->salida .= "      <td class=\"".$this->SetStyle("comuna")."\">&nbsp;  ".ModuloGetVar('app','Pacientes','NombreComuna').": </td>";   }
								$NomComuna=$this->nombre_comuna($Pais,$Dpto,$Mpio,$comuna);
								$this->salida .= "      <td><input type=\"text\" name=\"ncomuna\" value=\"$NomComuna\" class=\"input-text\" readonly>";
								$this->salida .= "      <input type=\"hidden\" name=\"comuna\" value=\"$comuna\" class=\"input-text\"></td>";
								$this->salida .= "       <td>  </td>";
								$this->salida .= "    </tr>";
						}
						else
						{  	$this->salida .= "      <input type=\"hidden\" name=\"comuna\" value=\"\" class=\"input-text\">";  }

						if($campo[tipo_barrio_id][sw_mostrar]==1 AND $campo[tipo_comuna_id][sw_obligatorio]==1)
						{
								$this->salida .= "    <tr height=\"15\">";
								if($campo[tipo_barrio_id][sw_obligatorio]==1)
								{  $this->salida .= "      <td class=\"".$this->SetStyle("barrio")."\">* BARRIO: </td>";  }
									else
								{  $this->salida .= "      <td class=\"".$this->SetStyle("barrio")."\">&nbsp;  BARRIO: </td>";  }
								$NomBarrio=$this->nombre_barrio($Pais,$Dpto,$Mpio,$comuna,$barrio);
								$this->salida .= "      <td><input type=\"text\" name=\"nbarrio\" value=\"$NomBarrio\" class=\"input-text\" readonly>";
								$this->salida .= "      <input type=\"hidden\" name=\"barrio\" value=\"$barrio\" class=\"input-text\"></td>";
								$this->salida .= "       <td>  </td>";
								$this->salida .= "    </tr>";
						}
						else
						{  	$this->salida .= "      <input type=\"hidden\" name=\"barrio\" value=\"\" class=\"input-text\">";  }
				}
				else
				{		//cuando no se va ha mostrar el pais dpto y ciudad
						$Pais=GetVarConfigAplication('DefaultPais');
						$Dpto=GetVarConfigAplication('DefaultDpto');
						$Mpio=GetVarConfigAplication('DefaultMpio');
						$this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"$Pais\" class=\"input-text\"></td>";
						$this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"$Dpto\" class=\"input-text\"></td>";
						$this->salida .= "       <input type=\"hidden\" name=\"mpio\" value=\"$Mpio\" class=\"input-text\" ></td>";
				}
				//---------------------------------------------------------------
				if($campo[tipo_estrato_id][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[tipo_estrato_id][sw_obligatorio]==1)
						{  $this->salida .= "    <td class=\"".$this->SetStyle("estrato")."\">* ESTRATO: </td>";  }
						else
						{  $this->salida .= "    <td class=\"".$this->SetStyle("estrato")."\">ESTRATO: </td>";  }
						$this->salida .= "      <td><input type=\"text\" name=\"estrato\" value=\"".trim($estrato)."\" class=\"input-text\" maxlength=\"1\" size=\"7\">";
						$this->salida .= "       <td></td>";
						$this->salida .= "    </tr>";
				}
				else
				{  	$this->salida .= "      <input type=\"hidden\" name=\"estrato\" value=\"\" class=\"input-text\">";  }
				/*$this->salida .= "    <tr height=\"15\">";
				if(!$Pais && !$Dpto && !$Mpio)
				{
						$Pais=GetVarConfigAplication('DefaultPais');
						$Dpto=GetVarConfigAplication('DefaultDpto');
						$Mpio=GetVarConfigAplication('DefaultMpio');
				}
				$this->salida .= "      <td class=\"".$this->SetStyle("pais")."\">* PAIS: </td>";
				$NomPais=$this->nombre_pais($Pais);
				$this->salida .= "      <td><input type=\"text\" name=\"npais\" value=\"$NomPais\" class=\"input-text\" readonly>";
				$this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"$Pais\" class=\"input-text\"></td>";
				$this->salida .= "       <td>  </td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"15\">";
				$this->salida .= "      <td class=\"".$this->SetStyle("dpto")."\">* DEPARTAMENTO: </td>";
				$NomDpto=$this->nombre_dpto($Pais,$Dpto);
				$this->salida .= "      <td><input type=\"text\" name=\"ndpto\" value=\"$NomDpto\" class=\"input-text\" readonly>";
				$this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"$Dpto\" class=\"input-text\"></td>";
				$this->salida .= "       <td>  </td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"15\">";
				$this->salida .= "      <td class=\"".$this->SetStyle("mpio")."\">* CIUDAD: </td>";
				$NomCiudad=$this->nombre_ciudad($Pais,$Dpto,$Mpio);
				$this->salida .= "      <td><input type=\"text\" name=\"nmpio\"  value=\"$NomCiudad\" class=\"input-text\" readonly>";
				$this->salida .= "       <input type=\"hidden\" name=\"mpio\" value=\"$Mpio\" class=\"input-text\" ></td>";
				$this->salida .= "       <td align=\"left\"><input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\"></td>";
				$this->salida .= "    </tr>";
				if($campo[tipo_comuna_id][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"15\">";
						$this->salida .= "      <td class=\"".$this->SetStyle("comuna")."\">&nbsp;  ".ModuloGetVar('app','Pacientes','NombreComuna').": </td>";
						$NomComuna=$this->nombre_comuna($Pais,$Dpto,$Mpio,$_REQUEST['comuna']);
						$this->salida .= "      <td><input type=\"text\" name=\"ncomuna\" value=\"$NomComuna\" class=\"input-text\" readonly>";
						$this->salida .= "      <input type=\"hidden\" name=\"comuna\" value=\"$comuna\" class=\"input-text\"></td>";
						$this->salida .= "       <td>  </td>";
						$this->salida .= "    </tr>";
				}
				if($campo[tipo_barrio_id][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"15\">";
						$this->salida .= "      <td class=\"".$this->SetStyle("barrio")."\">&nbsp;  BARRIO: </td>";
						$NomBarrio=$this->nombre_barrio($Pais,$Dpto,$Mpio,$_REQUEST['comuna'],$_REQUEST['barrio']);
						$this->salida .= "      <td><input type=\"text\" name=\"nbarrio\" value=\"$NomBarrio\" class=\"input-text\" readonly>";
						$this->salida .= "      <input type=\"hidden\" name=\"barrio\" value=\"$barrio\" class=\"input-text\"></td>";
						$this->salida .= "       <td>  </td>";
						$this->salida .= "    </tr>";
				}
				if($campo[tipo_estrato_id][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\"><td class=\"".$this->SetStyle("estrato")."\">ESTRATO: </td>";
						$this->salida .= "      <td><input type=\"text\" name=\"estrato\" value=\"$estrato\" class=\"input-text\" maxlength=\"1\" size=\"7\">";
						$this->salida .= "       <td></td>";
						$this->salida .= "    </tr>";
				}*/
				$this->salida .= "    <tr height=\"20\"><td class=\"".$this->SetStyle("Sexo")."\">* SEXO: </td><td><select name=\"Sexo\"  class=\"select\">";
				$sexo_id=$this->sexo();
				$this->BuscarSexo($sexo_id,'True',$Sexo);
				$this->salida .= "       </select></td></tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"label\">DATOS ADICIONALES: </td>";
				$this->salida .= "      <td colspan=\"2\"><textarea name=\"Observaciones\" cols=\"55\" rows=\"3\" class=\"textarea\">".$_REQUEST['Observaciones']."</textarea>";
				$this->salida .= "      </td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "    <td  align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br><br></td>";
				$this->salida .= "  </form>";
				$actionCancelar=ModuloGetURL('app','Pacientes','user','Cancelar');
				$this->salida .= "  <form name=\"formacancelar\" action=\"$actionCancelar\" method=\"post\">";
				$this->salida .= "    <td  colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"><br><br></td>";
				$this->salida .= "  </form>";
				$this->salida .= "  </tr>";
				$this->salida .= "</table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
  }

    function FormaPagares($pagare)
    {
				IncludeLib('funciones_admision');
        $this->salida .= "<br><table width=\"65%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "        <td colspan=\"6\">PAGARES PENDIENTES</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\" align=\"center\">";
				$this->salida .= "        <td width=\"10%\">PAGARE</td>";
        $this->salida .= "        <td width=\"15%\">FECHA</td>";
        $this->salida .= "        <td width=\"15%\">VENCIMIENTO</td>";
				$this->salida .= "        <td>FORMA PAGO</td>";
        $this->salida .= "        <td width=\"15%\">VALOR</td>";
        $this->salida .= "        <td width=\"15%\">SALDO</td>";
        $this->salida .= "      </tr>";
        $vect='';
        for($i=0; $i<sizeof($pagare); $i++)
        {
            if($i % 2) {  $estilo="modulo_list_claro";  }
            else {  $estilo="modulo_list_oscuro";   }
            if(date("Y/m/d") > FechaStamp($pagare[$i][fecha_vencimiento]))
            {  $est='label_error';  }
            $this->salida .= "  <tr class=\"$estilo\" class=\"$estilo\" align=\"center\">";
						$this->salida .= "    <td>".$pagare[$i][prefijo]."".$pagare[$i][numero]."</td>";
            $this->salida .= "    <td>".FechaStamp($pagare[$i][fecha_registro])."</td>";
            $this->salida .= "    <td class=\"$est\">".FechaStamp($pagare[$i][vencimiento])."</td>";
        		$this->salida .= "    <td>".$pagare[$i][formapago]."</td>";
            $this->salida .= "    <td>$ ".FormatoValor($pagare[$i][valor])."</td>";
						$abono = AbonosPagares($pagare[$i][empresa_id],$pagare[$i][prefijo],$pagare[$i][numero]);
						$saldo=$pagare[$i][valor]-$abono;
						$this->salida .= "    <td>$ ".FormatoValor($saldo)."</td>";		
            $this->salida .= "  </tr>";
        }
        $this->salida .= "  </table><br>";
        return true;
    }	


  /**
  * Encabezado de la forma de pedir datos contiene las cuentas por cobrar y informacion de
  * cuentas inactivas
  * @access private
  * @return boolean
  * @param string tipo documento
  * @param int numero documento
  * @param int plan_id
  */
  function FormaPedirDatos($TipoId,$PacienteId,$PlanId)
  {
        $this->salida .= ThemeAbrirTabla('PACIENTES - DATOS PACIENTE');
        //----consulta cuentasXcobrar-------
        $CXC=$this->CuentasxCobrar($TipoId,$PacienteId);
        if($CXC)
        {   $this->FormaCuentasxCobrar($CXC);  }
        //------------------------------------
        //----consulta pagares activos-------
				IncludeLib('funciones_pagares');
				$pagare=BuscarPagaresPaciente($TipoId,$PacienteId);
        if($pagare)
        {   $this->FormaPagares($pagare);  }
        //------------------------------------				
        $this->FormaCuentasInactivas($TipoId,$PacienteId);
        $this->FormaDatos($TipoId,$PacienteId,$PlanId,$this->BuscarPaciente($TipoId,$PacienteId),'60');
        $this->salida .= "  <table width=\"35%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\">";
        $this->salida .= "    <tr height=\"20\">";
				
				$contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
        $modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
        $tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
        $metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
        $argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
				//echo "-".$contenedor."-".$modulo."-".$tipo."-".$metodo."-".$argumentos;
				
        if($Existe){
        $contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
        $modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
        $tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
        $metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
        $argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
				echo "-".$contenedor."-".$modulo."-".$tipo."-".$metodo."-".$argumentos;
        $actionCancelar=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
        $this->salida .= "    <td align=\"left\"><br>";
        $this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"Siguiente\" value=\"SIGUIENTE\">";
        $this->salida .= "    </form><br></td>";
        $this->salida .= "    <td align=\"left\"><form name=\"formacancelar\" action=\"$actionCancelar\" method=\"post\">";
        $this->salida .= "      <br><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\">";
        $this->salida .= "    </form>";
        $this->salida .= "    </td>";
        }
        else{
        $this->salida .= "    <td  colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br><br></td>";
        $this->salida .= "  </form>";
        $actionCancelar=ModuloGetURL('app','Pacientes','user','Cancelar');
        $this->salida .= "  <form name=\"formacancelar\" action=\"$actionCancelar\" method=\"post\">";
        $this->salida .= "    <td  colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"><br><br></td>";
        $this->salida .= "  </form>";
        }
        $this->salida .= "  </tr>";
        $this->salida .= "</table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }


  /**
  * Encabezado de la forma de pedir datos contiene las cuentas por cobrar y informacion de
  * @access private
  * @return boolean
  * @param string tipo documento
  * @param int numero documento
  * @param int plan_id
  * @param array arreglo con los datos del paciente
  * @param int ancho de la tabla
  */
  function FormaDatos($TipoId,$PacienteId,$Responsable,$datos='',$ancho)
  {
				$this->salida.="	<SCRIPT language='javascript'>";
				$this->salida.="		function acceptNum(evt)\n";
				$this->salida.="		{\n";
				$this->salida.="			var nav4 = window.Event ? true : false;\n";
				$this->salida.="			var key = nav4 ? evt.which : evt.keyCode;\n";
				$this->salida.="			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
				$this->salida.="		}\n";
				$this->salida.="	</SCRIPT>";
				
				if(empty($Responsable))
				{  $Responsable=$_REQUEST['responsable'];  }

        if(is_array($datos))
        {
            $PrimerApellido=$datos['primer_apellido'];
            $SegundoApellido=$datos['segundo_apellido'];
            $PrimerNombre=$datos['primer_nombre'];
            $SegundoNombre=$datos['segundo_nombre'];
						$FechaNacimiento=$datos['fecha_nacimiento'];
						$FechaRegistro=$datos['fecha_registro'];
            $Direccion=$datos['residencia_direccion'];
            $Telefono=$datos['residencia_telefono'];
            $Ocupacion=$datos['ocupacion_id'];
            $Sexo=$datos['sexo_id'];
            $EstadoCivil=$datos['tipo_estado_civil_id'];
            $Pais=$datos['tipo_pais_id'];
            $Dpto=$datos['tipo_dpto_id'];
            $Mpio=$datos['tipo_mpio_id'];
            $barrio=$datos['tipo_barrio_id'];
            $comuna=$datos['tipo_comuna_id'];
            $Mama=$datos['nombre_madre'];
						$hc=$datos['historia_numero'];
						$prefijo=$datos['historia_prefijo'];
            $estrato=trim($datos['tipo_estrato_id']);
            $ZonaResidencia=$datos['zona_residencia'];
            $FechaNacimientoCalculada=$datos['fecha_nacimiento_es_calculada'];
						$peso=$datos['peso'];
						$talla=$datos['talla'];
						$LugarExpedicion=$datos['lugar_expedicion_documento'];
						$accion=ModuloGetURL('app','Pacientes','user','ActualizarDatosPaciente');
            $Observaciones=$datos['observaciones'];
						if(!empty($_REQUEST['SegundoApellido']))
						{  $SegundoApellido=$_REQUEST['SegundoApellido'];  }
						if(!empty($_REQUEST['SegundoNombre']))
						{  $SegundoNombre=$_REQUEST['SegundoNombre'];  }
						if(!empty($_REQUEST['Direccion']))
						{  $Direccion=$_REQUEST['Direccion'];  }
						if(!empty($_REQUEST['Telefono']))
						{  $Telefono=$_REQUEST['Telefono'];  }
						if(!empty($_REQUEST['ocupacion_id']))
						{  $Ocupacion=$_REQUEST['ocupacion_id'];  }
						if(!empty($_REQUEST['Sexo']))
						{  $Sexo=$_REQUEST['Sexo'];  }
						if(!empty($_REQUEST['EstadoCivil']))
						{  $EstadoCivil=$_REQUEST['EstadoCivil'];  }
						if(!empty($_REQUEST['Mama']))
						{  $Mama=$_REQUEST['Mama'];  }
        }
        elseif(!empty($_SESSION['PACIENTES']['PACIENTE']['ARREGLO']))
        {
            $PrimerApellido=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_Primer_apellido'];
            $SegundoApellido=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_Segundo_apellido'];
            $PrimerNombre=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_Primer_nombre'];
            $SegundoNombre=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_Segundo_nombre'];
            $FechaNacimiento=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_fecha_nacimiento'];
		        $Sexo=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_sexo'];
            $accion=$_REQUEST['accion'];
            $Edad=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_edad'];
						$Direccion=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_direccion_afiliado'];
						$Telefonocion=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_telefono_afiliado'];
						if(!empty($_REQUEST['SegundoApellido']))
						{  $SegundoApellido=$_REQUEST['SegundoApellido'];  }
						if(!empty($_REQUEST['SegundoNombre']))
						{  $SegundoNombre=$_REQUEST['SegundoNombre'];  }
						if(!empty($_REQUEST['Direccion']))
						{  $Direccion=$_REQUEST['Direccion'];  }
						if(!empty($_REQUEST['Telefono']))
						{  $Telefono=$_REQUEST['Telefono'];  }
						if(!empty($_REQUEST['ocupacion_id']))
						{  $Ocupacion=$_REQUEST['ocupacion_id'];  }
						if(!empty($_REQUEST['Sexo']))
						{  $Sexo=$_REQUEST['Sexo'];  }
						if(!empty($_REQUEST['EstadoCivil']))
						{  $EstadoCivil=$_REQUEST['EstadoCivil'];  }
						if(!empty($_REQUEST['Mama']))
						{  $Mama=$_REQUEST['Mama'];  }
        }
        else
        {
            if(!empty($_SESSION['PACIENTES']['REQUEST']))
            { $_REQUEST=$_SESSION['PACIENTES']['REQUEST'];  }
            $PrimerApellido=$_REQUEST['PrimerApellido'];
            $SegundoApellido=$_REQUEST['SegundoApellido'];
            $PrimerNombre=$_REQUEST['PrimerNombre'];
            $SegundoNombre=$_REQUEST['SegundoNombre'];
            $FechaNacimiento=$_REQUEST['FechaNacimiento'];
            $Direccion=$_REQUEST['Direccion'];
            $Telefono=$_REQUEST['Telefono'];
            $Ocupacion=$_REQUEST['ocupacion_id'];
            $Sexo=$_REQUEST['Sexo'];
            $EstadoCivil=$_REQUEST['EstadoCivil'];
            $Pais=$_REQUEST['pais'];
            $Dpto=$_REQUEST['dpto'];
            $Mpio=$_REQUEST['mpio'];
            $barrio=$_REQUEST['barrio'];
            $comuna=$_REQUEST['comuna'];
            $Mama=$_REQUEST['Mama'];
            $estrato=trim($_REQUEST['estrato']);
            $accion=$_REQUEST['accion'];
						$hc=$_REQUEST['historia'];
						$prefijo=$_REQUEST['prefijo'];
            $ZonaResidencia=$_REQUEST['Zona'];
            //$FechaNacimientoCalculada=$_REQUEST['FechaNacimientoCalculada'];
            $Observaciones=$_REQUEST['Observaciones'];
            $Edad=$_REQUEST['FechaNacimientoCalculada'];
						$peso=$_REQUEST['Peso'];
						$talla=$_REQUEST['Talla'];
						$LugarExpedicion=$_REQUEST['LugarExpedicion'];
        }

        if(empty($accion))
        {
            $accion=ModuloGetURL('app','Pacientes','user','ValidarDatosPacienteNew');
        }
        $this->SetJavaScripts('Ocupaciones');
        global $VISTA;
        $ru='classes/BuscadorDestino/selectorCiudad.js';
        $rus='classes/BuscadorDestino/selector.php';
        $this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
        if(!empty($_SESSION['PACIENTES']['PACIENTE']['ARREGLO']))
        {
            $this->salida .= "           <table width=\"50%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .= "               <tr>";
            $this->salida .= "                  <td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\">DATOS AFILIADO</td>";
            $this->salida .= "               </tr>";
            $a=ImplodeArrayAssoc($_SESSION['PACIENTES']['PACIENTE']['ARREGLO']);
            $arreglon=ExplodeArrayAssoc($a);
						$plantilla=$this->PlantilaBD($_SESSION['PACIENTES']['PACIENTE']['plan_id']);
            $i=0;
            foreach($arreglon as $k => $v)
            {
									$mostrar='';
									$mostrar=$this->CamposMostrarBD($k,$plantilla);
									if(!empty($mostrar[sw_mostrar]))
									{
											if($i % 2) {  $estilo="modulo_list_claro";  }
											else {  $estilo="modulo_list_oscuro";   }
											$this->salida .= "         <tr class=\"$estilo\">";
											if(!empty($mostrar[nombre_mostrar]))
											{  $k=$mostrar[nombre_mostrar];}
											$this->salida .= "            <td align=\"center\">$k</td>";
											$this->salida .= "            <td align=\"center\">$v</td>";
											$this->salida .= "        </tr>";
											$i++;
									}
            }
            $this->salida .= "           </table>";
        }
				if($_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_activo']==0 AND !empty($_SESSION['PACIENTES']['PACIENTE']['ARREGLO']))
				{
						$this->salida .= "<p class=\"label_error\" align=\"center\">EL PACIENTE ESTA ".$_SESSION['PACIENTES']['PACIENTE']['ARREGLO']['campo_estado_bd']."</p>";
				}

				//los campos obligatorios
				$campo=$this->BuscarCamposObligatorios();
        $this->salida .= "  <table width=\"".$ancho."%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" class=\"normal_10\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "    <tr>";
				if(!empty($FechaNacimientoCalculada))
				{
        		$this->salida .= "<p class=\"label_mark\" align=\"center\">LA EDAD DEL PACIENTE ES CALCULADA</p>";
				}
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<input type=\"hidden\" name=\"accion\" value=\"$accion\">";
          if($Afiliado){
         $this->Afiliados();
         $Fecha1=$this->FechaStamp($FechaNacimiento);
         $this->salida .= "      <td class=\"label_error\" colspan=\"3\" align=\"center\">$mensaje <a href=\"javascript:Afiliados('DATOS DEL AFILIADO','reports/$VISTA/datospaciente.php',500,400,'$TipoId',$PacienteId,'afiliados')\">Ver</a><br><br></td>";
        }
        else{
         $this->salida .= "      <td class=\"label_error\" colspan=\"3\" align=\"center\">$mensaje<br><br></td>";
        }
        $this->salida .= "    </tr>";
        if(is_array($datos)){
        $this->salida .= "    <tr height=\"25\">";
        $this->salida .= "       <td width=\"20%\"  class=\"label\" nowrap>FECHA REGISTRO: </td>";
        $Fecha=$this->FechaStamp($FechaRegistro);
        $Hora=$this->HoraStamp($FechaRegistro);
        $this->salida .= "      <td width=\"10%\" >$Fecha $Hora</td>";
        $this->salida .= "      <td width=\"5%\"></td>";
        $this->salida .= "    </tr>";
       /* $this->salida .= "    <tr height=\"13\">";
        $this->salida .= "      <td class=\"label\">HORA REGISTRO: </td>";
        $this->salida .= "       <td>$Hora</td>";
        $this->salida .= "      <td></td>";
        $this->salida .= "    </tr>";*/}
				if(!empty($Responsable))
				{		
        $NombrePlan=$this->NombrePlan($Responsable);
        $this->salida .= "    <tr height=\"25\">";
        $this->salida .= "      <td  height=\"20\"  class=\"label\" width=\"24%\">RESPONSABLE: </td>";
        $this->salida .= "      <td colspan=\"2\">$NombrePlan[plan_descripcion] </td>";
        $this->salida .= "      <input type=\"hidden\" name=\"Responsable\" value=\"$Responsable\">";
        $this->salida .= "    </tr>";
				}
        $this->salida .= "    <tr height=\"20\"><td height=\"20\" class=\"label\">TIPO DOCUMENTO: </td><td>";
        $Tipo=$this->mostrar_id_paciente($TipoId);
        $this->salida .= "    <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\" readonly>$Tipo</td></tr>";
        $this->salida .= "    <tr height=\"20\">";
        $this->salida .= "      <td class=\"label\"  height=\"20\" width=\"24%\">DOCUMENTO: </td>";
        $this->salida .= "      <td width=\"10%\"><input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\" readonly>$PacienteId</td>";
        $this->salida .= "       <td>  </td>";
        $this->salida .= "    </tr>";
//LUGAR EXPEDICION
				if($NombrePlan[sw_tipo_plan]=='1')
				{ 
						$this->salida .= "    <tr height=\"20\"><td height=\"20\" class=\"".$this->SetStyle("LugarExpedicion")."\">* LUGAR EXPEDICION: </td><td>";
						$this->salida .= "    <input type=\"text\" name=\"LugarExpedicion\" value=\"$LugarExpedicion\" class=\"input-text\" size=\"25\" maxlength=\"60\"></td></tr>";
				}
				else
				{
					if($campo[lugar_expedicion_documento][sw_mostrar]==1)
					{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[lugar_expedicion_documento][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("LugarExpedicion")."\">* LUGAR EXPEDICION: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("LugarExpedicion")."\">LUGAR EXPEDICION: </td>";  }
						$this->salida .= "      <td><input type=\"text\" name=\"LugarExpedicion\" value=\"$LugarExpedicion\" class=\"input-text\" size=\"25\" maxlength=\"60\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
					}
				}
//FIN LUGAR EXPEDICION
				if($campo[historia_prefijo][sw_mostrar]==1 AND $campo[historia_numero][sw_mostrar]==1 AND $campo[historia_numero][sw_obligatorio]==1
						AND !is_array($datos))
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_prefijo][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">* PREFIJO: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">PREFIJO: </td>";  }
						$this->salida .= "      <td><input type=\"text\" maxlength=\"4\" name=\"prefijo\" value=\"$prefijo\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				elseif($campo[historia_prefijo][sw_mostrar]==1 AND $campo[historia_numero][sw_mostrar]==1 AND $campo[historia_numero][sw_obligatorio]==1
						AND is_array($datos) AND !empty($prefijo))
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_prefijo][sw_obligatorio]==1)
						{  $this->salida .= "      <td  height=\"20\" class=\"".$this->SetStyle("prefijo")."\">* PREFIJO: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">PREFIJO: </td>";  }
						$this->salida .= "      <td  height=\"25\"><input type=\"hidden\" name=\"prefijo\" value=\"$prefijo\">$prefijo</td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				elseif($campo[historia_prefijo][sw_mostrar]==1 AND $campo[historia_numero][sw_mostrar]==1 AND $campo[historia_numero][sw_obligatorio]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_prefijo][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">* PREFIJO: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">PREFIJO: </td>";  }
						$this->salida .= "      <td><input type=\"text\" maxlength=\"4\" name=\"prefijo\" value=\"$prefijo\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				if($campo[historia_numero][sw_mostrar]==1 AND !is_array($datos))
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_numero][sw_obligatorio]==1)
						{  $this->salida .= "      <td  height=\"20\" class=\"".$this->SetStyle("historia")."\">* No. HISTORIA: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">No. HISTORIA: </td>";  }
						$this->salida .= "      <td  height=\"25\"><input type=\"text\" maxlength=\"50\" name=\"historia\" value=\"$hc\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				elseif($campo[historia_numero][sw_mostrar]==1 AND is_array($datos) AND !empty($hc))
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_numero][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">* No. HISTORIA: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">No. HISTORIA: </td>";  }
						$this->salida .= "      <td height=\"20\"><input type=\"hidden\" name=\"historia\" value=\"$hc\">$hc</td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				elseif($campo[historia_numero][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_numero][sw_obligatorio]==1)
						{  $this->salida .= "      <td  height=\"20\" class=\"".$this->SetStyle("historia")."\">* No. HISTORIA: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">No. HISTORIA: </td>";  }
						$this->salida .= "      <td  height=\"25\"><input type=\"text\" maxlength=\"50\" name=\"historia\" value=\"$hc\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
        $this->salida .= "    <tr height=\"20\">";
        $this->salida .= "      <td class=\"".$this->SetStyle("PrimerNombre")."\">* PRIMER NOMBRE: </td>";
        $this->salida .= "      <td><input type=\"text\" maxlength=\"20\" name=\"PrimerNombre\" value=\"$PrimerNombre\" class=\"input-text\" size=\"30\"></td>";
        $this->salida .= "      <td></td>";
        $this->salida .= "    </tr>";
				if($campo[segundo_nombre][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[segundo_nombre][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("SegundoNombre")."\">* SEGUNDO NOMBRE: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("SegundoNombre")."\">SEGUNDO NOMBRE: </td>";  }
						$this->salida .= "      <td><input type=\"text\" maxlength=\"20\" name=\"SegundoNombre\" value=\"$SegundoNombre\" class=\"input-text\" size=\"30\"></td>";
						$this->salida .= "       <td></td>";
						$this->salida .= "    </tr>";
				}
        $this->salida .= "    <tr height=\"20\">";
        $this->salida .= "      <td class=\"".$this->SetStyle("PrimerApellido")."\">* PRIMER APELLIDO: </td>";
        $this->salida .= "      <td><input type=\"text\" maxlength=\"30\" name=\"PrimerApellido\" value=\"$PrimerApellido\" class=\"input-text\" size=\"30\"></td>";
        $this->salida .= "      <td></td>";
        $this->salida .= "    </tr>";
				if($campo[segundo_apellido][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[segundo_apellido][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("SegundoApellido")."\">* SEGUNDO APELLIDO: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("SegundoApellido")."\">SEGUNDO APELLIDO: </td>";  }
						$this->salida .= "      <td><input type=\"text\" maxlength=\"30\" name=\"SegundoApellido\" value=\"$SegundoApellido\" class=\"input-text\" size=\"30\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
        $this->salida .= "    <tr>";
        $this->salida .= "      <td class=\"".$this->SetStyle("FechaNacimiento")."\">* FECHA NACIMIENTO: </td>";
        $dat = strtok ($FechaNacimiento,"-");
        if(strlen($dat)<5){   $Fecha=$this->FechaStamp($FechaNacimiento); }
        else { $Fecha=$FechaNacimiento; }
        $this->salida .= "      <td><input type=\"text\" name=\"FechaNacimiento\" value=\"$Fecha\" class=\"input-text\"></td>";
        $this->salida .= "      <td width=\"25%\">".ReturnOpenCalendario('forma','FechaNacimiento','/')."</td>";
        $this->salida .= "    </tr>";
				if(empty($Fecha))
				{
						if($campo[fecha_nacimiento_es_calculada][sw_mostrar]==1)
						{
								$this->salida .= "    <tr height=\"20\">";
								if($campo[fecha_nacimiento_es_calculada][sw_obligatorio]==1)
								{  $this->salida .= "      <td class=\"".$this->SetStyle("FechaNacimientoCalculada")."\">* EDAD CALCULADA: </td>"; }
								else
								{  $this->salida .= "      <td class=\"".$this->SetStyle("FechaNacimientoCalculada")."\">EDAD CALCULADA: </td>"; }
								$this->salida .= "      <td colspan=\"2\"><input type=\"text\" name=\"FechaNacimientoCalculada\" value=\"$Edad\" class=\"input-text\" size=\"6\">";
								//$this->salida .= "      <input type=\"hidden\" name=\"FechaNacimientoCalculada\" value=\"$FechaNacimientoCalculada\">";
								$this->salida .= "      <select name=\"Edad\"  class=\"select\">";
								$this->salida .= "       <option value=\"1\">D?as</option>";
								$this->salida .= "       <option value=\"2\">Meses</option>";
								$this->salida .= "       <option value=\"3\" selected>A?os</option>";
								$this->salida .= "     </select></td>";
								$this->salida .= "    </tr>";
						}
				}
				if($campo[residencia_direccion][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[residencia_direccion][sw_obligatorio]==1)
						{  $this->salida .= "      <td  class=\"".$this->SetStyle("Direccion")."\">* DIRECCION: </td>";  }
						else
						{  $this->salida .= "      <td class=\"label\">DIRECCION: </td>";  }
						$this->salida .= "      <td><input type=\"text\" maxlength=\"60\" name=\"Direccion\" value=\"$Direccion\" class=\"input-text\" size=\"30\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				//---------------------------------------------------------------
        if(!$Pais)
        {
						$Pais=GetVarConfigAplication('DefaultPais');
						$Dpto=GetVarConfigAplication('DefaultDpto');
						$Mpio=GetVarConfigAplication('DefaultMpio');
        }
				//si mostrar el pais depto y mpio
				if($campo[lugar_residencia][sw_mostrar]==1 AND $campo[lugar_residencia][sw_obligatorio]==1)
				{
						$this->salida .= "    <tr height=\"15\">";
						$this->salida .= "      <td class=\"".$this->SetStyle("pais")."\">* PAIS: </td>";
						$NomPais=$this->nombre_pais($Pais);
						$this->salida .= "      <td><input type=\"text\" name=\"npais\" value=\"$NomPais\" class=\"input-text\" readonly size=\"30\">";
						$this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"$Pais\" class=\"input-text\"></td>";
						$this->salida .= "       <td>  </td>";
						$this->salida .= "    </tr>";
						$this->salida .= "    <tr height=\"15\">";
						$this->salida .= "      <td class=\"".$this->SetStyle("dpto")."\">* DEPARTAMENTO: </td>";
						$NomDpto=$this->nombre_dpto($Pais,$Dpto);
						$this->salida .= "      <td><input type=\"text\" name=\"ndpto\" value=\"$NomDpto\" class=\"input-text\" readonly size=\"30\">";
						$this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"$Dpto\" class=\"input-text\"></td>";
						$this->salida .= "       <td>  </td>";
						$this->salida .= "    </tr>";
						$this->salida .= "    <tr height=\"15\">";
						$this->salida .= "      <td class=\"".$this->SetStyle("mpio")."\">* CIUDAD: </td>";
						$NomCiudad=$this->nombre_ciudad($Pais,$Dpto,$Mpio);
						$this->salida .= "      <td><input type=\"text\" name=\"nmpio\"  value=\"$NomCiudad\" class=\"input-text\" readonly size=\"30\">";
						$this->salida .= "       <input type=\"hidden\" name=\"mpio\" value=\"$Mpio\" class=\"input-text\" ></td>";
						$this->salida .= "       <td align=\"left\"><input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\"></td>";
						$this->salida .= "    </tr>";
						if($campo[tipo_comuna_id][sw_mostrar]==1)
						{
								$this->salida .= "    <tr height=\"15\">";
								if($campo[tipo_comuna_id][sw_obligatorio]==1)
								{  $this->salida .= "      <td class=\"".$this->SetStyle("comuna")."\">*  ".ModuloGetVar('app','Pacientes','NombreComuna').": </td>";   }
								else
								{  $this->salida .= "      <td class=\"".$this->SetStyle("comuna")."\">&nbsp;  ".ModuloGetVar('app','Pacientes','NombreComuna').": </td>";   }
								$NomComuna=$this->nombre_comuna($Pais,$Dpto,$Mpio,$comuna);
								$this->salida .= "      <td><input type=\"text\" name=\"ncomuna\" value=\"$NomComuna\" class=\"input-text\" readonly size=\"30\">";
								$this->salida .= "      <input type=\"hidden\" name=\"comuna\" value=\"$comuna\" class=\"input-text\"></td>";
								$this->salida .= "       <td>  </td>";
								$this->salida .= "    </tr>";
						}
						else
						{  	$this->salida .= "      <input type=\"hidden\" name=\"comuna\" value=\"\" class=\"input-text\">";  }

						if($campo[tipo_barrio_id][sw_mostrar]==1 AND $campo[tipo_comuna_id][sw_obligatorio]==1)
						{
								$this->salida .= "    <tr height=\"15\">";
								if($campo[tipo_barrio_id][sw_obligatorio]==1)
								{  $this->salida .= "      <td class=\"".$this->SetStyle("barrio")."\">* BARRIO: </td>";  }
									else
								{  $this->salida .= "      <td class=\"".$this->SetStyle("barrio")."\">&nbsp;  BARRIO: </td>";  }
								$NomBarrio=$this->nombre_barrio($Pais,$Dpto,$Mpio,$comuna,$barrio);
								$this->salida .= "      <td><input type=\"text\" name=\"nbarrio\" value=\"$NomBarrio\" class=\"input-text\" readonly size=\"30\">";
								$this->salida .= "      <input type=\"hidden\" name=\"barrio\" value=\"$barrio\" class=\"input-text\"></td>";
								$this->salida .= "       <td>  </td>";
								$this->salida .= "    </tr>";
						}
						else
						{  	$this->salida .= "      <input type=\"hidden\" name=\"barrio\" value=\"\" class=\"input-text\">";  }
				}
				else
				{		//cuando no se va ha mostrar el pais dpto y ciudad
						$Pais=GetVarConfigAplication('DefaultPais');
						$Dpto=GetVarConfigAplication('DefaultDpto');
						$Mpio=GetVarConfigAplication('DefaultMpio');
						$this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"$Pais\" class=\"input-text\"></td>";
						$this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"$Dpto\" class=\"input-text\"></td>";
						$this->salida .= "       <input type=\"hidden\" name=\"mpio\" value=\"$Mpio\" class=\"input-text\" ></td>";
				}

				//---------------------------------------------------------------
				if($campo[tipo_estrato_id][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[tipo_estrato_id][sw_obligatorio]==1)
						{  $this->salida .= "    <td class=\"".$this->SetStyle("estrato")."\">* ESTRATO: </td>";  }
						else
						{  $this->salida .= "    <td class=\"".$this->SetStyle("estrato")."\">ESTRATO: </td>";  }
						$this->salida .= "      <td><input type=\"text\" name=\"estrato\" value=\"".trim($estrato)."\" class=\"input-text\" maxlength=\"1\" size=\"7\">";
						$this->salida .= "       <td></td>";
						$this->salida .= "    </tr>";
				}
				else
				{  	$this->salida .= "      <input type=\"hidden\" name=\"estrato\" value=\"\" class=\"input-text\">";  }
				if($campo[residencia_telefono][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[residencia_telefono][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("Telefono")."\">* TELEFONO: </td>";  }
						else
						{  $this->salida .= "      <td class=\"label\">TELEFONO: </td>";  }
						$this->salida .= "      <td ><input type=\"text\" maxlength=\"30\" name=\"Telefono\" value=\"$Telefono\" class=\"input-text\" size=\"30\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				if($campo[nombre_madre][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[nombre_madre][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("Mama")."\">* NOMBRE MADRE: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("Mama")."\">NOMBRE MADRE: </td>";  }
						$this->salida .= "      <td ><input type=\"text\" maxlength=\"60\" name=\"Mama\" value=\"$Mama\" class=\"input-text\" size=\"30\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"label\">ZONA RESIDENCIA: </td>";
				$this->salida .= "      <td>";
				$this->MostrarZonas();
				$this->salida .= "      </td>";
				$this->salida .= "    </tr>";
				if($campo[ocupacion_id][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[ocupacion_id][sw_obligatorio]==1)
						{  $this->salida .= "    <td class=\"".$this->SetStyle("Ocupacion")."\">* OCUPACION: </td>";  }
						else
						{  $this->salida .= "    <td class=\"".$this->SetStyle("Ocupacion")."\">OCUPACION: </td>";  }
						$this->salida .= "      <td colspan=\"2\">";
						if(!empty($Ocupacion))
						{  $nomocu=$this->NombreOcupacion($Ocupacion);  }
						$this->salida.=RetornarWinOpenBuscadorOcupaciones('forma','',$nomocu,$Ocupacion);
						$this->salida.='</td>';
						$this->salida .= "  </tr>";
				}
				$this->salida .= "    <tr height=\"20\"><td class=\"".$this->SetStyle("Sexo")."\">* SEXO: </td>";
				$this->salida .= "    	<td><select name=\"Sexo\"  class=\"select\">";
				$sexo_id=$this->sexo();
				$this->BuscarSexo($sexo_id,'True',$Sexo);
				$this->salida .= "       </select></td></tr>";
				
				
				if($campo[talla][sw_mostrar]==1)
				{
						$uni_talla=$this->ConsultaUnidad('talla');				
						$this->salida .= "    <tr height=\"20\">";
						if($campo[talla][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("Talla")."\">* TALLA: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("Talla")."\">TALLA: </td>";  }
						$this->salida .= "    	<td><input type=\"text\" name=\"Talla\" value=\"".trim($talla)."\" class=\"input-text\" maxlength=\"5\" size=\"7\" onKeyPress='return acceptNum(event)'> Altura: ".$uni_talla['unidad']."</td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}				
				if($campo[peso][sw_mostrar]==1)
				{
						$uni_peso=$this->ConsultaUnidad('peso');			
						$this->salida .= "    <tr height=\"20\">";
						if($campo[peso][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("Peso")."\">* PESO: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("Peso")."\">PESO: </td>";  }
						$this->salida .= "    	<td><input type=\"text\" name=\"Peso\" value=\"".trim($peso)."\" class=\"input-text\" maxlength=\"5\" size=\"7\" onKeyPress='return acceptNum(event)'> ".$uni_peso['unidad']."</td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
			/*	$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "    	<td class=\"".$this->SetStyle("Talla")."\">* TALLA: </td>";
				$this->salida .= "    	<td><input type=\"text\" name=\"Talla\" value=\"".trim($talla)."\" class=\"input-text\" maxlength=\"5\" size=\"7\" onKeyPress='return acceptNum(event)'> Altura: ".$uni_talla['unidad']."</td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "    	<td class=\"".$this->SetStyle("Peso")."\">* PESO: </td>";
				$this->salida .= "    	<td><input type=\"text\" name=\"Peso\" value=\"".trim($peso)."\" class=\"input-text\" maxlength=\"5\" size=\"7\" onKeyPress='return acceptNum(event)'> ".$uni_peso['unidad']."</td>";
				$this->salida .= "    </tr>";*/
				
				if($campo[tipo_estado_civil_id][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[tipo_estado_civil_id][sw_obligatorio]==1)
						{  $this->salida .= "    <td class=\"".$this->SetStyle("EstadoCivil")."\">* ESTADO CIVIL: </td><td><select name=\"EstadoCivil\"  class=\"select\">";  }
						else
						{  $this->salida .= "    <td class=\"".$this->SetStyle("EstadoCivil")."\">ESTADO CIVIL: </td><td><select name=\"EstadoCivil\"  class=\"select\">";  }
						$estado_civil_id=$this->estadocivil();
						$this->BuscarEstadoCivil($estado_civil_id,'True',$EstadoCivil);
						$this->salida .= "    </select></td></tr>";
				}
				if($campo[observaciones][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[observaciones][sw_obligatorio]==1)
						{		$this->salida .= "      <td class=\"".$this->SetStyle("Observaciones")."\">* DATOS ADICIONALES: </td>";  }
						else
						{		$this->salida .= "      <td class=\"".$this->SetStyle("Observaciones")."\">DATOS ADICIONALES: </td>";  }
						$this->salida .= "      <td colspan=\"2\"><textarea name=\"Observaciones\" cols=\"40\" rows=\"3\" class=\"textarea\">$Observaciones</textarea>";
						$this->salida .= "      </td>";
						$this->salida .= "    </tr>";
				}
				$this->salida .= "</table>";

        //url protocolo
        if(!empty($_SESSION['PACIENTES']['protocolo']) AND !empty($Responsable))
        {
            if(file_exists("protocolos/".$_SESSION['PACIENTES']['protocolo'].""))
            {
                $Protocolo=$_SESSION['PACIENTES']['protocolo'];
                $this->salida .= "<script>";
                $this->salida .= "function Protocolo(valor){";
                $this->salida .= "window.open('protocolos/'+valor,'PROTOCOLO','');";
                $this->salida .= "}";
                $this->salida .= "</script>";
                $accion="javascript:Protocolo('$Protocolo')";
								$this->salida .= "          <br><table width=\"60%\" align=\"center\" border=\"0\" class=\"normal_10\" cellpadding=\"3\">";
								$this->salida .= "             <tr>";
								$this->salida .= "                 <td width=\"20%\" class=\"modulo_list_claro\">PROTOCOLO</td>";
								$this->salida .= "                 <td align=\"left\" class=\"modulo_list_claro\"><a href=\"$accion\">$Protocolo</a></td>";
								$this->salida .= "                 <td align=\"left\" width=\"40%\">&nbsp;</td>";
								$this->salida .= "             </tr>";
								$this->salida .= "            </table><br>";
						}
        }
				return true;
  }

  /**
  * Forma que muestra los datos de un paciente que ya existe
  * @access private
  * @return boolean
  * @param array arreglo con los datos del paciente
  * @param int plan_id
  */
  function FormaDatosPacienteCreado($datos,$Responsable)
  {
				$classMpio=$classDpto=$classZona=$classSexo=$classNom='modulo_table_list_title';
        if(is_array($datos))
        {
            $PrimerApellido=$datos['primer_apellido'];
            $SegundoApellido=$datos['segundo_apellido'];
            $PrimerNombre=$datos['primer_nombre'];
            $SegundoNombre=$datos['segundo_nombre'];
            $FechaNacimiento=$datos['fecha_nacimiento'];
            $FechaRegistro=$datos['fecha_registro'];
            $Direccion=$datos['residencia_direccion'];
            $Telefono=$datos['residencia_telefono'];
            $Ocupacion=$datos['ocupacion_id'];
            $Sexo=$datos['sexo_id'];
            $EstadoCivil=$datos['tipo_estado_civil_id'];
            $Pais=$datos['tipo_pais_id'];
            $Dpto=$datos['tipo_dpto_id'];
            $Mpio=$datos['tipo_mpio_id'];
            $Mama=$datos['nombre_madre'];
            $accion=ModuloGetURL('app','Pacientes','user','ActualizarDatosPaciente');
            $ZonaResidencia=$datos['zona_residencia'];
            $FechaNacimientoCalculada=$datos['fecha_nacimiento_es_calculada'];
            $Observaciones=$datos['observaciones'];
            $PacienteId=$datos['paciente_id'];
            $TipoId=$datos['tipo_id_paciente'];
        }
        $this->salida .= "  <table width=\"68%\" cellspacing=\"3\" border=\"0\" cellpadding=\"3\" align=\"center\" class=\"modulo_list_oscuro\" >";
        $this->salida .= "    <tr class=\"modulo_table_list_title\">";
        $this->salida .= "    <td colspan=\"4\">DATOS DEL PACIENTE</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
				if(empty($PrimerNombre) OR empty($PrimerApellido))
				{  $classNom='label_error';  }
        $nombre=$PrimerNombre.' '.$SegundoNombre.' '.$PrimerApellido.' '.$SegundoApellido;
        $this->salida .= "    <td class=\"$classNom\" width=\"20%\">Nombre:</td>";
        $this->salida .= "    <td colspan=\"3\" width=\"80%\" class=\"modulo_list_claro\">$nombre</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "    <td class=\"modulo_table_list_title\">Identificaci?n:</td>";
        $this->salida .= "    <td class=\"modulo_list_claro\">$TipoId $PacienteId</td>";
				if(empty($Sexo))
				{  $classSexo='label_error';  }
        $this->salida .= "    <td class=\"$classSexo\" width=\"15%\">Sexo:</td>";
        $NomSexo=$this->NombreSexoPac($Sexo);
        $this->salida .= "    <td class=\"modulo_list_claro\"width=\"22%\">$NomSexo</td>";
        $this->salida .= "    </tr>";
if(!empty($Responsable))
{
        $this->salida .= "    <tr>";
        $this->salida .= "    <td class=\"modulo_table_list_title\">Responsable:</td>";
        $NombrePlan=$this->NombrePlan($Responsable);
        $this->salida .= "    <td class=\"modulo_list_claro\"  colspan=\"3\" >$NombrePlan[plan_descripcion] </td>";
        $this->salida .= "    </tr>";
}
        $this->salida .= "    <tr>";
        $this->salida .= "    <td class=\"modulo_table_list_title\">Direcci?n:</td>";
        $this->salida .= "    <td  class=\"modulo_list_claro\">$Direccion</td>";
        $NombreZona=$this->NombreZona($ZonaResidencia);
				if(empty($ZonaResidencia))
				{  $classZona='label_error';  }
				$this->salida .= "    <td class=\"$classZona\">Zona:</td>";
        $this->salida .= "    <td class=\"modulo_list_claro\">$NombreZona</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $NomCiudad=$this->nombre_ciudad($Pais,$Dpto,$Mpio);
				if(empty($Mpio))
				{  $classMpio='label_error';  }
        $this->salida .= "    <td class=\"$classMpio\">Ciudad:</td>";
        $this->salida .= "    <td class=\"modulo_list_claro\">$NomCiudad</td>";
        $NombreDpto=$this->nombre_dpto($Pais,$Dpto);
				if(empty($Dpto))
				{  $classDpto='label_error';  }
        $this->salida .= "    <td class=\"$classDpto\">Departamento:</td>";
        $this->salida .= "    <td class=\"modulo_list_claro\">$NombreDpto</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "    <td class=\"modulo_table_list_title\">Tel?fonos:</td>";
        $this->salida .= "    <td class=\"modulo_list_claro\">$Telefono</td>";
        $this->salida .= "    <td class=\"modulo_table_list_title\">Estado Civil:</td>";
        $Estado=$this->NombreEstadoCivil($EstadoCivil);
        $this->salida .= "    <td class=\"modulo_list_claro\">$Estado</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "    <td class=\"modulo_table_list_title\">Ocupaci?n:</td>";
        $NomOcupacion=$this->NombreOcupacion($Ocupacion);
        $this->salida .= "    <td colspan=\"3\"  class=\"modulo_list_claro\">$NomOcupacion</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "    <td class=\"modulo_table_list_title\">Nombre Madre:</td>";
        $this->salida .= "    <td colspan=\"3\" class=\"modulo_list_claro\">$Mama</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "    <td class=\"modulo_table_list_title\">Observaciones:</td>";
        $this->salida .= "    <td colspan=\"3\"  class=\"modulo_list_claro\">$Observaciones</td>";
        $this->salida .= "    </tr>";
        $this->salida .= "   <tr class=\"modulo_table_list_title\">";
        $accion=ModuloGetURL('app','Pacientes','user','LlamarFormaPedirDatos',array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Responsable'=>$Responsable,'ModificarDatos'=>true));
        $this->salida .= "     <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <td colspan=\"4\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"MODIFICAR\"></td>";
        $this->salida .= "      </form>";
        $this->salida .= "    </tr>";
        $this->salida .= "  </table><br>";
        return true;
  }


  function FormaModificarDatos($TipoId,$PacienteId)
  {
				$datos=$this->BuscarPaciente($TipoId,$PacienteId);
        if(is_array($datos))
        {
            $PrimerApellido=$datos['primer_apellido'];
            $SegundoApellido=$datos['segundo_apellido'];
            $PrimerNombre=$datos['primer_nombre'];
            $SegundoNombre=$datos['segundo_nombre'];
						$FechaNacimiento=$datos['fecha_nacimiento'];
						$FechaRegistro=$datos['fecha_registro'];
            $Direccion=$datos['residencia_direccion'];
            $Telefono=$datos['residencia_telefono'];
            $Ocupacion=$datos['ocupacion_id'];
            $Sexo=$datos['sexo_id'];
            $EstadoCivil=$datos['tipo_estado_civil_id'];
            $Pais=$datos['tipo_pais_id'];
            $Dpto=$datos['tipo_dpto_id'];
            $Mpio=$datos['tipo_mpio_id'];
            $barrio=$datos['tipo_barrio_id'];
            $comuna=$datos['tipo_comuna_id'];
            $Mama=$datos['nombre_madre'];
						$hc=$datos['historia_numero'];
						$prefijo=$datos['historia_prefijo'];
            $estrato=trim($datos['tipo_estrato_id']);
            $ZonaResidencia=$datos['zona_residencia'];
            $FechaNacimientoCalculada=$datos['fecha_nacimiento_es_calculada'];
            $Observaciones=$datos['observaciones'];
            $LugarExpedicion=$datos['lugar_expedicion_documento'];
        }

        $this->salida .= ThemeAbrirTabla('MODIFICAR DATOS PACIENTES - DATOS PACIENTE');
        $this->SetJavaScripts('Ocupaciones');
        global $VISTA;
        $ru='classes/BuscadorDestino/selectorCiudad.js';
        $rus='classes/BuscadorDestino/selector.php';
        $this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";

				//los campos obligatorios
				$campo=$this->BuscarCamposObligatorios();
        $this->salida .= "  <BR><table width=\"60%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" class=\"normal_10\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "    <tr>";
				if(!empty($FechaNacimientoCalculada))
				{
        		$this->salida .= "<p class=\"label_mark\" align=\"center\">LA EDAD DEL PACIENTE ES CALCULADA</p>";
				}
				$accion=ModuloGetURL('app','Pacientes','user','ModificarDatosPaciente');
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "    <tr height=\"20\"><td height=\"20\" class=\"label\">TIPO DOCUMENTO: </td><td>";
        $Tipo=$this->mostrar_id_paciente($TipoId);
        $this->salida .= "    <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\" readonly>$Tipo</td></tr>";
        $this->salida .= "    <tr height=\"20\">";
        $this->salida .= "      <td class=\"label\"  height=\"20\" width=\"24%\">DOCUMENTO: </td>";
        $this->salida .= "      <td width=\"10%\"><input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\" readonly>$PacienteId</td>";
        $this->salida .= "       <td>  </td>";
        $this->salida .= "    </tr>";
//LUGAR EXPEDICION
				if($campo[lugar_expedicion_documento][sw_mostrar]==1)
				{
					$this->salida .= "    <tr height=\"20\">";
					if($campo[lugar_expedicion_documento][sw_obligatorio]==1)
					{  $this->salida .= "      <td class=\"".$this->SetStyle("LugarExpedicion")."\">* LUGAR EXPEDICION: </td>";  }
					else
					{  $this->salida .= "      <td class=\"".$this->SetStyle("LugarExpedicion")."\">LUGAR EXPEDICION: </td>";  }
					$this->salida .= "      <td><input type=\"text\" name=\"LugarExpedicion\" value=\"$LugarExpedicion\" class=\"input-text\" size=\"25\" maxlength=\"60\"></td>";
					$this->salida .= "      <td></td>";
					$this->salida .= "    </tr>";
				}
//FIN LUGAR EXPEDICION
				if($campo[historia_prefijo][sw_mostrar]==1 AND $campo[historia_numero][sw_mostrar]==1 AND $campo[historia_numero][sw_obligatorio]==1
						AND !is_array($datos))
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_prefijo][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">* PREFIJO: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">PREFIJO: </td>";  }
						$this->salida .= "      <td><input type=\"text\" maxlength=\"4\" name=\"prefijo\" value=\"$prefijo\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				elseif($campo[historia_prefijo][sw_mostrar]==1 AND $campo[historia_numero][sw_mostrar]==1 AND $campo[historia_numero][sw_obligatorio]==1
						AND is_array($datos) AND !empty($prefijo))
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_prefijo][sw_obligatorio]==1)
						{  $this->salida .= "      <td  height=\"20\" class=\"".$this->SetStyle("prefijo")."\">* PREFIJO: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">PREFIJO: </td>";  }
						$this->salida .= "      <td  height=\"25\"><input type=\"hidden\" name=\"prefijo\" value=\"$prefijo\">$prefijo</td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				elseif($campo[historia_prefijo][sw_mostrar]==1 AND $campo[historia_numero][sw_mostrar]==1 AND $campo[historia_numero][sw_obligatorio]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_prefijo][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">* PREFIJO: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">PREFIJO: </td>";  }
						$this->salida .= "      <td><input type=\"text\" maxlength=\"4\" name=\"prefijo\" value=\"$prefijo\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				if($campo[historia_numero][sw_mostrar]==1 AND !is_array($datos))
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_numero][sw_obligatorio]==1)
						{  $this->salida .= "      <td  height=\"20\" class=\"".$this->SetStyle("historia")."\">* No. HISTORIA: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">No. HISTORIA: </td>";  }
						$this->salida .= "      <td  height=\"25\"><input type=\"text\" maxlength=\"50\" name=\"historia\" value=\"$hc\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				elseif($campo[historia_numero][sw_mostrar]==1 AND is_array($datos) AND !empty($hc))
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_numero][sw_obligatorio]==1)
						{  $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">* No. HISTORIA: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">No. HISTORIA: </td>";  }
						$this->salida .= "      <td height=\"20\"><input type=\"hidden\" name=\"historia\" value=\"$hc\">$hc</td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				elseif($campo[historia_numero][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[historia_numero][sw_obligatorio]==1)
						{  $this->salida .= "      <td  height=\"20\" class=\"".$this->SetStyle("historia")."\">* No. HISTORIA: </td>";  }
						else
						{  $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">No. HISTORIA: </td>";  }
						$this->salida .= "      <td  height=\"25\"><input type=\"text\" maxlength=\"50\" name=\"historia\" value=\"$hc\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
        $this->salida .= "    <tr height=\"20\">";
        $this->salida .= "      <td class=\"".$this->SetStyle("PrimerNombre")."\">* PRIMER NOMBRE: </td>";
        $this->salida .= "      <td><input type=\"text\" maxlength=\"20\" name=\"PrimerNombre\" value=\"$PrimerNombre\" class=\"input-text\" size=\"30\"></td>";
        $this->salida .= "      <td></td>";
        $this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"".$this->SetStyle("SegundoNombre")."\">SEGUNDO NOMBRE: </td>";
				$this->salida .= "      <td><input type=\"text\" maxlength=\"20\" name=\"SegundoNombre\" value=\"$SegundoNombre\" class=\"input-text\" size=\"30\"></td>";
				$this->salida .= "       <td></td>";
				$this->salida .= "    </tr>";
        $this->salida .= "    <tr height=\"20\">";
        $this->salida .= "      <td class=\"".$this->SetStyle("PrimerApellido")."\">* PRIMER APELLIDO: </td>";
        $this->salida .= "      <td><input type=\"text\" maxlength=\"30\" name=\"PrimerApellido\" value=\"$PrimerApellido\" class=\"input-text\" size=\"30\"></td>";
        $this->salida .= "      <td></td>";
        $this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"".$this->SetStyle("SegundoApellido")."\">SEGUNDO APELLIDO: </td>";
				$this->salida .= "      <td><input type=\"text\" maxlength=\"30\" name=\"SegundoApellido\" value=\"$SegundoApellido\" class=\"input-text\" size=\"30\"></td>";
				$this->salida .= "      <td></td>";
				$this->salida .= "    </tr>";
        $this->salida .= "    <tr>";
        $this->salida .= "      <td class=\"".$this->SetStyle("FechaNacimiento")."\">* FECHA NACIMIENTO: </td>";
        $dat = strtok ($FechaNacimiento,"-");
        if(strlen($dat)<5){   $Fecha=$this->FechaStamp($FechaNacimiento); }
        else { $Fecha=$FechaNacimiento; }
        $this->salida .= "      <td><input type=\"text\" name=\"FechaNacimiento\" value=\"$Fecha\" class=\"input-text\"></td>";
        $this->salida .= "      <td width=\"25%\">".ReturnOpenCalendario('forma','FechaNacimiento','/')."</td>";
        $this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"".$this->SetStyle("FechaNacimientoCalculada")."\">EDAD CALCULADA: </td>";
				$this->salida .= "      <td colspan=\"2\"><input type=\"text\" name=\"FechaNacimientoCalculada\" value=\"$Edad\" class=\"input-text\" size=\"6\">";
				//$this->salida .= "      <input type=\"hidden\" name=\"FechaNacimientoCalculada\" value=\"$FechaNacimientoCalculada\">";
				$this->salida .= "      <select name=\"Edad\"  class=\"select\">";
				$this->salida .= "       <option value=\"1\">D?as</option>";
				$this->salida .= "       <option value=\"2\">Meses</option>";
				$this->salida .= "       <option value=\"3\" selected>A?os</option>";
				$this->salida .= "     </select></td>";
				$this->salida .= "    </tr>";
  			$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"label\">DIRECCION: </td>";
				$this->salida .= "      <td><input type=\"text\" maxlength=\"60\" name=\"Direccion\" value=\"$Direccion\" class=\"input-text\" size=\"30\"></td>";
				$this->salida .= "      <td></td>";
				$this->salida .= "    </tr>";
				//---------------------------------------------------------------
        if(!$Pais)
        {
						$Pais=GetVarConfigAplication('DefaultPais');
						$Dpto=GetVarConfigAplication('DefaultDpto');
						$Mpio=GetVarConfigAplication('DefaultMpio');
        }
				$this->salida .= "    <tr height=\"15\">";
				$this->salida .= "      <td class=\"".$this->SetStyle("pais")."\">* PAIS: </td>";
				$NomPais=$this->nombre_pais($Pais);
				$this->salida .= "      <td><input type=\"text\" name=\"npais\" value=\"$NomPais\" class=\"input-text\" readonly size=\"30\">";
				$this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"$Pais\" class=\"input-text\"></td>";
				$this->salida .= "       <td>  </td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"15\">";
				$this->salida .= "      <td class=\"".$this->SetStyle("dpto")."\">* DEPARTAMENTO: </td>";
				$NomDpto=$this->nombre_dpto($Pais,$Dpto);
				$this->salida .= "      <td><input type=\"text\" name=\"ndpto\" value=\"$NomDpto\" class=\"input-text\" readonly size=\"30\">";
				$this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"$Dpto\" class=\"input-text\"></td>";
				$this->salida .= "       <td>  </td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"15\">";
				$this->salida .= "      <td class=\"".$this->SetStyle("mpio")."\">* CIUDAD: </td>";
				$NomCiudad=$this->nombre_ciudad($Pais,$Dpto,$Mpio);
				$this->salida .= "      <td><input type=\"text\" name=\"nmpio\"  value=\"$NomCiudad\" class=\"input-text\" readonly size=\"30\">";
				$this->salida .= "       <input type=\"hidden\" name=\"mpio\" value=\"$Mpio\" class=\"input-text\" ></td>";
				$this->salida .= "       <td align=\"left\"><input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\"></td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"15\">";
				$this->salida .= "      <td class=\"".$this->SetStyle("comuna")."\">&nbsp;  ".ModuloGetVar('app','Pacientes','NombreComuna').": </td>";
				$NomComuna=$this->nombre_comuna($Pais,$Dpto,$Mpio,$comuna);
				$this->salida .= "      <td><input type=\"text\" name=\"ncomuna\" value=\"$NomComuna\" class=\"input-text\" readonly size=\"30\">";
				$this->salida .= "      <input type=\"hidden\" name=\"comuna\" value=\"$comuna\" class=\"input-text\"></td>";
				$this->salida .= "       <td>  </td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"15\">";
				$this->salida .= "      <td class=\"".$this->SetStyle("barrio")."\">&nbsp;  BARRIO: </td>";
				$NomBarrio=$this->nombre_barrio($Pais,$Dpto,$Mpio,$comuna,$barrio);
				$this->salida .= "      <td><input type=\"text\" name=\"nbarrio\" value=\"$NomBarrio\" class=\"input-text\" readonly size=\"30\">";
				$this->salida .= "      <input type=\"hidden\" name=\"barrio\" value=\"$barrio\" class=\"input-text\"></td>";
				$this->salida .= "       <td>  </td>";
				$this->salida .= "    </tr>";
				//---------------------------------------------------------------
				if($campo[tipo_estrato_id][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						if($campo[tipo_estrato_id][sw_obligatorio]==1)
						{  $this->salida .= "    <td class=\"".$this->SetStyle("estrato")."\">* ESTRATO: </td>";  }
						else
						{  $this->salida .= "    <td class=\"".$this->SetStyle("estrato")."\">ESTRATO: </td>";  }
						$this->salida .= "      <td><input type=\"text\" name=\"estrato\" value=\"".trim($estrato)."\" class=\"input-text\" maxlength=\"1\" size=\"7\">";
						$this->salida .= "       <td></td>";
						$this->salida .= "    </tr>";
				}
				else
				{  	$this->salida .= "      <input type=\"hidden\" name=\"estrato\" value=\"\" class=\"input-text\">";  }

				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"label\">TELEFONO: </td>";
				$this->salida .= "      <td ><input type=\"text\" maxlength=\"30\" name=\"Telefono\" value=\"$Telefono\" class=\"input-text\" size=\"30\"></td>";
				$this->salida .= "      <td></td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"".$this->SetStyle("Mama")."\">NOMBRE MADRE: </td>";
				$this->salida .= "      <td ><input type=\"text\" maxlength=\"60\" name=\"Mama\" value=\"$Mama\" class=\"input-text\" size=\"30\"></td>";
				$this->salida .= "      <td></td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"label\">ZONA RESIDENCIA: </td>";
				$this->salida .= "      <td>";
				$this->MostrarZonas();
				$this->salida .= "      </td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "    <td class=\"".$this->SetStyle("Ocupacion")."\">OCUPACION: </td>";
				$this->salida .= "      <td colspan=\"2\">";
				if(!empty($Ocupacion))
				{  $nomocu=$this->NombreOcupacion($Ocupacion);  }
				$this->salida.=RetornarWinOpenBuscadorOcupaciones('forma','',$nomocu);
				$this->salida.='</td>';
				$this->salida .= "  </tr>";
				$this->salida .= "    <tr height=\"20\"><td class=\"".$this->SetStyle("Sexo")."\">* SEXO: </td><td><select name=\"Sexo\"  class=\"select\">";
				$sexo_id=$this->sexo();
				$this->BuscarSexo($sexo_id,'True',$Sexo);
				$this->salida .= "       </select></td></tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "    <td class=\"".$this->SetStyle("EstadoCivil")."\">ESTADO CIVIL: </td><td><select name=\"EstadoCivil\"  class=\"select\">";
				$estado_civil_id=$this->estadocivil();
				$this->BuscarEstadoCivil($estado_civil_id,'True',$EstadoCivil);
				$this->salida .= "    </select></td></tr>";
				$this->salida .= "    <tr height=\"20\">";
				$this->salida .= "      <td class=\"".$this->SetStyle("Observaciones")."\">DATOS ADICIONALES: </td>";
				$this->salida .= "      <td colspan=\"2\"><textarea name=\"Observaciones\" cols=\"40\" rows=\"3\" class=\"textarea\">$Observaciones</textarea>";
				$this->salida .= "      </td>";
				$this->salida .= "    </tr>";
				$this->salida .= "    </table><BR>";
        $this->salida .= "  <table width=\"35%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\">";
        $this->salida .= "    <tr height=\"20\">";
        $this->salida .= "    <td  colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br><br></td>";
        $this->salida .= "  </form>";
        $actionCancelar=ModuloGetURL('app','Pacientes','user','Cancelar');
        $this->salida .= "  <form name=\"formacancelar\" action=\"$actionCancelar\" method=\"post\">";
        $this->salida .= "    <td  colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"><br><br></td>";
        $this->salida .= "  </form>";
        $this->salida .= "  </tr>";
        $this->salida .= "</table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
	}

  /**
  * Forma que muestra informacion de las cuentas inactivas
  * @access private
  * @return boolean
  * @param string tipo documento
  * @param int numero documento
  */
  function FormaCuentasInactivas($TipoId,$PacienteId)
  {
        $var=$this->BuscarCuentasInactivas($TipoId,$PacienteId);
        if($var)
        {
            $this->salida .= "  <br><table width=\"35%\" cellspacing=\"2\" border=\"1\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "<tr class=\"modulo_table_list_title\">";
            $this->salida .= "<td>EL PACIENTE TIENE CUENTAS INACTIVAS</td>";
            $this->salida .= "</tr>";
            for($i=0; $i<sizeof($var); $i++)
            {
                if($i % 2) {  $estilo="modulo_list_claro";  }
                else {  $estilo="modulo_list_oscuro";   }
                $this->salida .= "<tr align=\"center\" >";
                $this->salida .= "<td class=\"$estilo\">CUENTA No. ".$var[$i][numerodecuenta]."</td>";

								$this->salida .= "</tr>";
            }
            $this->salida .= "</table><br>";
        }
  }

    /**
    * Forma que muestra informacion de las cuentas por cobrar de un paciente
    * @access private
    * @return boolean
    * @param array arreglo con los datos de las cuentas por cobrar de un paciente
    */
    function FormaCuentasxCobrar($CXC)
    {
        if(!$CXC){ $CXC=$_REQUEST['CXC']; }
        IncludeLib("tarifario");
        $this->salida .= "<br><table width=\"85%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "        <td colspan=\"5\">CARTERA PENDIENTE</td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "        <td>EMPRESA</td>";
        $this->salida .= "        <td>CENTRO UTILIDAD</td>";
        $this->salida .= "        <td>FECHA VENCIMIENTO</td>";
        $this->salida .= "        <td>VALOR</td>";
        $this->salida .= "        <td>SALDO</td>";
        $this->salida .= "      </tr>";
        $vect='';
        for($i=0; $i<sizeof($CXC); $i++)
        {
            if($i % 2) {  $estilo="modulo_list_claro";  }
            else {  $estilo="modulo_list_oscuro";   }
            if(date("Y/m/d") > $CXC[$i][fecha_vencimiento])
            {  $est='label_error';  }
              $this->salida .= "  <tr class=\"$estilo\" class=\"$estilo\" align=\"center\">";
            $this->salida .= "    <td>".$CXC[$i][razon_social]."</td>";
            $this->salida .= "    <td>".$CXC[$i][descripcion]."</td>";
            $this->salida .= "    <td class=\"$est\">".$CXC[$i][fecha_vence]."</td>";
            $this->salida .= "    <td class=\"$est\">".FormatoValor($CXC[$i][valor])."</td>";
            $this->salida .= "    <td class=\"$est\">".FormatoValor($CXC[$i][saldo])."</td>";
            $this->salida .= "  </tr>";
        }
        $this->salida .= "  </table><br>";
        return true;
    }

  /**
  * Forma que muestra informacion de las cuentas inactivas
  * @access private
  * @return boolean
  * @param string tipo documento
  * @param int numero documento
  */
  function FormaCambioIdentificacion($TipoId,$PacienteId)
  {
      $this->salida .= ThemeAbrirTabla('PACIENTES - CAMBIO DE IDENTIFICACION');
      //consulta cuentasXcobrar------------
      $CXC=$this->CuentasxCobrar($TipoId,$PacienteId);
      if($CXC)
      {
          $this->FormaCuentasxCobrar($CXC);
          $this->salida .= "  <BR><table border=\"0\" width=\"70%\" align=\"center\">";
          $this->salida .= "    <tr>";
          $this->salida .= "       <td  align=\"center\" class=\"label\">No se pueden modificar la identificaci?n del paciente, Tiene cuentas por cobrar.</td>";
          $this->salida .= "    </tr>";
          $contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
          $modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
          $tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
          $metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
          $argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
          $accion=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
          $this->salida .= "  <form name=\"formac\" action=\"$accion\" method=\"post\">";
          $this->salida .= "    <tr>";
          $this->salida .= "    <td align=\"center\" class=\"label_error\"><br><input class=\"input-submit\" type=\"submit\" name=\"Modificar\" value=\"ACEPTAR\"><br></td>";
          $this->salida .= "  </form>";
          $this->salida .= "  </tr>";
          $this->salida .= " </table><br>";
      }
      //-------------------------------------
      else
      {
            $this->salida .= "      <BR><table border=\"0\" width=\"70%\" align=\"center\">";
            $this->salida .= "          <tr><td><fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>";
            $this->salida .= "  <table width=\"95%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" >";
            $accion=ModuloGetURL('app','Pacientes','user','ModificarIdentificacionPaciente');
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "  <form name=\"formac\" action=\"$accion\" method=\"post\">";
            $this->salida .= "      <input type=\"hidden\" name=\"TipoId1\" value=\"$TipoId\">";
            $this->salida .= "      <input type=\"hidden\" name=\"PacienteId1\" value=\"$PacienteId\">";
            $this->salida .= "               <tr><td class=\"".$this->SetStyle("TipoId")."\">TIPO DOCUMENTO: </td><td><select name=\"TipoId\" class=\"select\">";
            $tipo_id=$this->tipo_id_paciente();
            $this->BuscarIdPaciente($tipo_id,'False',$TipoId);
            $this->salida .= "              </select></td></tr>";
            $this->salida .= "    <tr height=\"20\">";
            $this->salida .= "      <td class=\"label\">DOCUMENTO ACTUAL: </td>";
            $this->salida .= "      <td>$PacienteId</td>";
            $this->salida .= "       <td>  </td>";
            $this->salida .= "    </tr>";
            $this->salida .= "    <tr height=\"20\">";
            $this->salida .= "      <td class=\"".$this->SetStyle("PacienteId")."\">DOCUMENTO: </td>";
            $this->salida .= "      <td><input type=\"text\" name=\"PacienteId\" value=\"$PacienteId\" class=\"input-text\"></td>";
            $this->salida .= "       <td>  </td>";
            $this->salida .= "    </tr>";
            $this->salida .= "    <tr height=\"20\">";
            $this->salida .= "    <td  colspan=\"1\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Modificar\" value=\"MODIFICAR\"><br></td>";
            $this->salida .= "  </form>";
						$contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
						$modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
						$tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
						$metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
						$argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
						$accion=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
            //$accion=ModuloGetURL('app','Triage','user','MetodoModificarAdmision');
            $this->salida .= "  <form name=\"formac\" action=\"$accion\" method=\"post\">";
            $this->salida .= "    <td  colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Modificar\" value=\"CANCELAR\"><br></td>";
            $this->salida .= "  </form>";
            $this->salida .= "  </tr>";
            $this->salida .= "</table>";
            $this->salida .= "      </fieldset></td></tr></table><br>";
      }
      $this->salida .= ThemeCerrarTabla();
      return true;
  }

  /**
  * Cambia la identificacion del paciente y unifica su historia clinica
  * @access private
  * @return boolean
  * @param string tipo documento
  * @param int numero documento
  */
  function FormaUnificacion($TipoId,$PacienteId)
  {
      $this->salida .= ThemeAbrirTabla('PACIENTES - UNIFICACION DE HISTORIA CLINICA');
      $this->salida .= "      <BR><table border=\"0\" width=\"70%\" align=\"center\">";
      $this->salida .= "          <tr><td><fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>";
      $this->salida .= "  <table width=\"90%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" >";
      $accion=ModuloGetURL('app','Pacientes','user','UnificarHistoriasClinicas');
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "  <form name=\"formac\" action=\"$accion\" method=\"post\">";
      $this->salida .= "      <input type=\"hidden\" name=\"TipoId1\" value=\"$TipoId\">";
      $this->salida .= "      <input type=\"hidden\" name=\"PacienteId1\" value=\"$PacienteId\">";
      $this->salida .= "               <tr><td class=\"".$this->SetStyle("TipoId")."\">TIPO DOCUMENTO: </td><td><select name=\"TipoId\" class=\"select\">";
      $tipo_id=$this->tipo_id_paciente();
      $this->BuscarIdPaciente($tipo_id,'False',$TipoId);
      $this->salida .= "              </select></td></tr>";
      $this->salida .= "    <tr height=\"20\">";
      $this->salida .= "      <td class=\"label\">DOCUMENTO ACTUAL: </td>";
      $this->salida .= "      <td>$PacienteId</td>";
      $this->salida .= "       <td>  </td>";
      $this->salida .= "    </tr>";
      $this->salida .= "    <tr height=\"20\">";
      $this->salida .= "      <td class=\"".$this->SetStyle("PacienteId")."\">DOCUMENTO: </td>";
      $this->salida .= "      <td><input type=\"text\" name=\"PacienteId\" value=\"$PacienteId\" class=\"input-text\"></td>";
      $this->salida .= "       <td>  </td>";
      $this->salida .= "    </tr>";
      $this->salida .= "    <tr height=\"20\">";
      $this->salida .= "    <td  colspan=\"1\" align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Modificar\" value=\"UNIFICAR\"><br></td>";
      $this->salida .= "  </form>";
			$contenedor=$_SESSION['PACIENTES']['RETORNO']['contenedor'];
			$modulo=$_SESSION['PACIENTES']['RETORNO']['modulo'];
			$tipo=$_SESSION['PACIENTES']['RETORNO']['tipo'];
			$metodo=$_SESSION['PACIENTES']['RETORNO']['metodo'];
			$argumentos=$_SESSION['PACIENTES']['RETORNO']['argumentos'];
			$accion=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
      //$accion=ModuloGetURL('app','Triage','user','MetodoModificarAdmision');
      $this->salida .= "  <form name=\"formac\" action=\"$accion\" method=\"post\">";
      $this->salida .= "    <td  colspan=\"1\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Modificar\" value=\"CANCELAR\"><br></td>";
      $this->salida .= "    <td  colspan=\"1\" align=\"center\"></td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "</table>";
      $this->salida .= "      </fieldset></td></tr></table><br>";
      $this->salida .= ThemeCerrarTabla();
      return true;
  }



  /**
  * Separa la fecha del formato timestamp
  * @access private
  * @return string
  * @param date fecha
  */
   function FechaStamp($fecha)
   {
      if($fecha){
          $fech = strtok ($fecha,"-");
          for($l=0;$l<3;$l++)
          {
            $date[$l]=$fech;
            $fech = strtok ("-");
          }
          return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
      }
  }


  /**
  * Separa la hora del formato timestamp
  * @access private
  * @return string
  * @param date hora
  */
  function HoraStamp($hora)
  {
      $hor = strtok ($hora," ");
      for($l=0;$l<4;$l++)
      {
        $time[$l]=$hor;
        $hor = strtok (":");
      }
      return  $time[1].":".$time[2].":".$time[3];
  }


  /**
  * Muestra en el combo los tipo de identificacion
  * @access private
  * @return string
  * @param array con los tipos de idnetificacion
  * @param boolean indica si el combo ya esta seleccionado
  * @param int el tipo de documento que viene por defecto
  */
  function BuscarIdPaciente($tipo_id,$Seleccionado='False',$TipoId='')
  {
        foreach($tipo_id as $value=>$titulo)
        {
          if($value==$TipoId){
            $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
          }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
          }
        }
  }

  /**
  * Muestra en el combo los tipo de sexo
  * @access private
  * @return string
  * @param array con los tipos de sexo
  * @param boolean indica si el combo ya esta seleccionado
  * @param string el sexo que viene por defecto
  */
  function BuscarSexo($sexo_id,$Seleccionado='False',$Sexo='')
  {
          $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
          foreach($sexo_id as $value=>$titulo)
          {
            if($value==$Sexo){
              $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
            }
            else{
                $this->salida .=" <option value=\"$value\">$titulo</option>";
            }
          }
  }


  /**
  * Muestra en el combo los tipos de ocupaciones
  * @access private
  * @return string
  * @param array con los tipos de ocupacion
  * @param boolean indica si el combo ya esta seleccionado
  * @param int el tipo de ocupacion que viene por defecto
  */
  function BuscarOcupacion($ocupacion_id,$Seleccionado='False',$Ocupacion='')
  {
          $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
          foreach($ocupacion_id as $value=>$titulo)
          {
            if($value==$Ocupacion){
              $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
            }
            else{
               $this->salida .=" <option value=\"$value\">$titulo</option>";
            }
          }
  }

  /**
  * Muestra en el combo los tipos de estado civil
  * @access private
  * @return string
  * @param array con los tipos de estados civil
  * @param boolean indica si el combo ya esta seleccionado
  * @param int el tipo de estado civil que viene por defecto
  */
  function BuscarEstadoCivil($estado_civil_id,$Seleccionado='False',$EstadoCivil='')
  {
          $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
          foreach($estado_civil_id as $value=>$titulo)
          {
            if($value==$EstadoCivil){
              $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
            }
            else{
                $this->salida .=" <option value=\"$value\">$titulo</option>";
            }
          }
  }

	/**
	*
	*/
  function ComboEstratos($estratosV,$estrato='')
  {
          $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
          for($i=0; $i<sizeof($estratosV); $i++)
          {
            if($estratosV[$i][tipo_estrato_id]==$estrato){
              $this->salida .=" <option value=\"".$estratosV[$i][tipo_estrato_id]."\" selected>".$estratosV[$i][descripcion]."</option>";
            }
            else{
                $this->salida .=" <option value=\"".$estratosV[$i][tipo_estrato_id]."\">".$estratosV[$i][descripcion]."</option>";
            }
          }
  }

  /**
  * Muestra en el combo los diferentes tipos de vias de ingreso
  * @access private
  * @return string
  * @param array arreglo con las vias de ingreso
  * @param boolean indica si el combo ya esta seleccionado
  * @param int la via de ingreso que viene por defecto
  * @param string tipo de forma
  */
  function  MostrarZonas()
  {
        $zonas=$this->ZonasResidencia();
				$ZonaDefault=GetVarConfigAplication('DefaultZona');						
        for($i=0; $i<sizeof($zonas); $i++)
        {
           $Zona=$zonas[$i][descripcion];
           $ZonaId=$zonas[$i][zona_residencia];
           if($ZonaId==$ZonaDefault){
              $this->salida .= "   $Zona<input type=\"radio\" name=\"Zona\" value=\"$ZonaId\" checked>";
           }
           else{
              $this->salida .= "   $Zona<input type=\"radio\" name=\"Zona\" value=\"$ZonaId\">";
           }
        }
  }

  /**
  * Dibuja el combo de parentescos
  * @access private
  * @return boolean
  * @param array arreglo con los tipos de parentescos
  * @param int parentesco si ya se ha seleccionado
  */
  function BuscarParentesco($TiposParentesco,$Parentesco)
  {
          $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
          for($i=0; $i<sizeof($TiposParentesco); $i++)
          {
            $value=$TiposParentesco[$i][tipo_parentesco_id];
            if($value==$Parentesco){
              $this->salida .=" <option value=\"$value\" selected>".$TiposParentesco[$i][descripcion]."</option>";
            }
            else{
                $this->salida .=" <option value=\"$value\">".$TiposParentesco[$i][descripcion]."</option>";
            }
          }
   }

  /**
  * Forma para mensajes.
  * @access private
  * @return boolean
  * @param string mensaje
  * @param string nombre de la ventana
  * @param string accion de la forma
  * @param string nombre del boton
  */
  function FormaMensaje($mensaje,$titulo,$accion,$boton)
  {
        unset($_SESSION['PACIENTES']);
        $this->salida .= ThemeAbrirTabla($titulo);
        $this->salida .= "            <table width=\"60%\" align=\"center\" >";
        $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "               <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
        if($boton){
           $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
        }
       else{
           $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
       }
        $this->salida .= "           </form>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }


//----------------------------------------------------------------------------------

}//FIN CLASE

?>

