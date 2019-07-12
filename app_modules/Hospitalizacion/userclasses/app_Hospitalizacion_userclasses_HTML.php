<?php
// app_Triage_user_HTML.php  17/10/2003
// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------
// Autor: Darling Dorado - Lorena Aragon
// Proposito del Archivo: Manejo visual de triage de los pacientes.
// ----------------------------------------------------------------------

/**
*Contiene los metodos visuales para realizar el triage y admision de los pacientes
*/

class app_Hospitalizacion_userclasses_HTML extends app_Hospitalizacion_user
{


	/**
	*Constructor de la clase app_Triage_user_HTML
	*El constructor de la clase app_Triage_user_HTML se encarga de llamar
	*a la clase app_Triage_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function app_Hospitalizacion_user_HTML()
	{
				$this->salida='';
				$this->app_Hospitalizacion_user();
				return true;
	}

	/**
	* Muestra los tipos de admisiones a las que tiene permiso un usuario.
	* @access private
	* @return boolean
	*/
	function FormaElegirAdmision($var,$emp,$cu,$arreglo)
	{
			$this->salida .= ThemeAbrirTabla('ADMISIONES HOSPITALIZACION');
			$this->salida .= "<br><table border=\"1\" cellspacing=\"3\" cellpadding=\"3\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
			$i=0;
			$j=0;
			$d=0;
			$f=0;
			if($var)
			{
				$this->salida .= "<tr  class=\"modulo_table_list_title\">";
				$this->salida .= "  <td>EMPRESA</td>";
				$this->salida .= "  <td>CENTRO UTILIDAD</td>";
				$this->salida .= "  <td>UNIDAD FUNCIONAL</td>";
				$this->salida .= "  <td>DEPARTAMENTO</td>";
				$this->salida .= "  <td>ADMISIONES</td>";
				$this->salida .= "</tr>";
				foreach($var as $empresa => $centrou)
				{
						$this->salida .= "<tr>";
						$this->salida .= "<td  class=\"modulo_table_list_title\" rowspan=\"".$emp[$empresa]."\">$empresa</td>";
						foreach($centrou as $centroutilidad => $unidades)
						{
								if($j % 2) {  $estilo1="modulo_list_claro";  }
								else {  $estilo1="modulo_list_oscuro";   }
								$j++;
								if(!$sw)
								{  $this->salida .= "<td  align=\"center\" class=\"$estilo1\" rowspan=\"".$cu[$empresa][$centroutilidad]."\">$centroutilidad</td>";  }
								else
								{
										$this->salida .= "<td  align=\"center\" class=\"$estilo1\">$centroutilidad</td>";
										$sw=true;
								}
//-----------------------------------------------------------------
							foreach($unidades as  $uni => $departamentos)
							{
										if($d % 2) {  $estilo2="modulo_list_claro";  }
										else {  $estilo2="modulo_list_oscuro";   }
										$d++;
										$this->salida .= "<td  align=\"center\" class=\"$estilo2\" rowspan=\"".$unid[$empresa][$centroutilidad][$uni]."\">".$uni."</td>";
										foreach($departamentos as $deptos => $fcuentas)
										{
													if($f % 2) {  $estilo3="modulo_list_claro";  }
													else {  $estilo3="modulo_list_oscuro";   }
													$f++;
													$this->salida .= "<td  align=\"center\" class=\"$estilo3\" rowspan=\"".$dpto[$empresa][$centroutilidad][$deptos][$uni]."\">$deptos</td>";

													foreach($fcuentas as $filtro => $x)
													{
															$CU=$arreglo[$i][centro_utilidad];
															$Empresa=$arreglo[$i][empresa_id];
															$PtoAdmon=$arreglo[$i][punto_admision_id];
															$SwTriage=$arreglo[$i][sw_triage];
															$Dpto=$arreglo[$i][departamento];
															$accion=ModuloGetURL('app','Hospitalizacion','user','LlamaListado',array('Empresa'=>$Empresa,'CentroUtilidad'=>$CU));
															if($i % 2) {  $estilo="modulo_list_claro";  }
															else {  $estilo="modulo_list_oscuro";   }
															if(!$sw1)
															{
																	$this->salida .= "<td class=\"$estilo\"><a href=\"$accion\">$filtro</a></td></tr>";
																	$sw1=true;
															}
															else
															{
																	$this->salida .= "<td class=\"$estilo\"><a href=\"$accion\">$filtro</a></td></tr>";
																	$sw1=false;
															}
															$i++;
													}
													if($sw)
													{
															$this->salida .= "</tr>";
															$sw=false;
															$sw1=false;
													}
										}
							}
//-----------------------------------------------------------------
						}
				}
			}
			else
			{
					$this->salida .= "    <tr>";
					$this->salida .= "       <td  align=\"center\" class=\"label\">Usted no tiene permisos para ingresar al menu de Admisones.</td>";
					$this->salida .= "    </tr>";
			}
			$this->salida .= " </table><br>";

/*			$this->salida .= "<br><table border=\"1\" cellspacing=\"3\" cellpadding=\"3\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
			$i=0;
			$j=0;
			if($var)
			{
				foreach($var as $empresa => $centrou)
				{
						$this->salida .= "<tr>";
						$this->salida .= "<td  class=\"modulo_table_list_title\" rowspan=\"".$emp[$empresa]."\">$empresa</td>";
						foreach($centrou as $centroutilidad => $fcuentas)
						{
								if($j % 2) {  $estilo1="modulo_list_claro";  }
								else {  $estilo1="modulo_list_oscuro";   }
								if(!$sw)
								{  $this->salida .= "<td  class=\"$estilo1\" rowspan=\"".$cu[$empresa][$centroutilidad]."\">$centroutilidad</td>";  }
								else
								{
										$this->salida .= "<td  class=\"$estilo1\">$centroutilidad</td>";
										$sw=true;
								}
								$j++;
								foreach($fcuentas as $filtro => $x)
								{
										$CU=$arreglo[$i][centro_utilidad];
										$Empresa=$arreglo[$i][empresa_id];
										$accion=ModuloGetURL('app','Hospitalizacion','user','LlamaListado',array('Empresa'=>$Empresa,'CentroUtilidad'=>$CU));
										if($i % 2) {  $estilo="modulo_list_claro";  }
										else {  $estilo="modulo_list_oscuro";   }
										$d=$cu[$empresa][$centroutilidad];
										if(!$sw1)
										{
												$this->salida .= "<td class=\"$estilo\"><a href=\"$accion\">$filtro</a></tr></td>";
												$sw1=true;
										}
										else
										{
												$this->salida .= "<td class=\"$estilo\"><a href=\"$accion\">$filtro</a></td></tr>";
												$sw1=false;
										}
										$i++;
								}
								if($sw)
								{
										$this->salida .= "</tr>";
										$sw=false;
										$sw1=false;
								}
						}
				}
			}
			else
			{
					$this->salida .= "    <tr>";
					$this->salida .= "       <td  align=\"center\" class=\"label\">Usted no tiene permisos para accesar a las cuentas.</td>";
					$this->salida .= "    </tr>";
			}
			$this->salida .= " </table><br>";*/
			$this->salida .= ThemeCerrarTabla();
			return true;
	}


	/**
	*La funcion ListadoAdmisionHospitalizacion muestra el listado de los pacientes
	pendientes por por hospitalizacion*/

	function ListadoAdmisionHospitalizacion($arreglo,$bandera,$TipoId,$PacienteId,$Busqueda)
	{
		$action=ModuloGetURL('app','Hospitalizacion','user','TraerPersonaParaAdmision');
		$this->salida  = ThemeAbrirTabla('LISTADO ADMISION HOSPITALIZACION');
		$this->salida .= "			      <br><br>";
		$this->salida .= "            <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
    $this->salida .= "			      <table width=\"95%\" border=\"0\" align=\"center\">";
		if(!$Busqueda){
    $Busqueda='1';
		$this->salida .= "            <tr><td width=\"100%\">";
		$this->salida .= "              <fieldset><legend class=\"field\">BUSCAR PACIENTE</legend>";
		$this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "	      		   <tr>";
		$this->salida .= "				          <td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->BuscarIdPaciente($tipo_id,'False','');
		$this->salida .= "                  </select></td></tr>";
		$this->salida .= "				        <tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
		$this->salida .= "	  	            <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
		$this->salida .= "	      		   </tr>";
		$this->salida .= "               <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
    $this->salida .= "               <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"BuscarCompleto\" value=\"BUSQUEDA COMLETA\"></td></tr>";
		$this->salida .= "			         </table>";
		$this->salida .= "		           </fieldset></td>";
    $this->salida .= "             <td></td>";
		$this->salida .= "             <td></td>";
    $this->salida .= "             <td>";
		$this->salida .= "              <fieldset><legend class=\"field\">BUSQUEDA AVANZADA</legend>";
		$this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "	      		   <tr>";
    $this->salida .= "				          <td class=\"label\">TIPO<br>BUSQUEDA: </td><td><select name=\"TipoBusqueda\" class=\"select\">";
		$this->salida .="                   <option value=\"1\" selected>Documento</option>";
    $this->salida .="                   <option value=\"2\" selected>Nombres</option>";
		$this->salida .="                   <option value=\"3\" selected>No. Cuenta</option>";
    $this->salida .="                   <option value=\"4\" selected>No. Historia</option>";
		$this->salida .= "                  </select></td></tr>";
		$this->salida .= "				          <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br></td></tr>";
    $this->salida .= "	      		   </tr>";
		$this->salida .= "			         </table>";
		$this->salida .= "		           </fieldset></td></tr>";
		}else{
      $this->salida .= "			      <table width=\"95%\" border=\"0\" align=\"center\">";
			$this->salida .= "              <tr><td width=\"100%\">";
		  $this->salida .= "              <fieldset><legend class=\"field\">BUSCAR PACIENTE</legend>";
		  $this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\">";
			if($Busqueda=='1'){
         $this->salida .= "				          <td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
		     $tipo_id=$this->tipo_id_paciente();
		     $this->BuscarIdPaciente($tipo_id,'False','');
		     $this->salida .= "                  </select></td></tr>";
		     $this->salida .= "				        <tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
				 $this->salida .= "	  	            <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
			}elseif($Busqueda=='2'){
		    $this->salida .= "				        <tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"nombres\" maxlength=\"32\"></td></tr>";
        $this->salida .= "				        <tr><td class=\"label\">APELLIDOS</td><td><input type=\"text\" class=\"input-text\" name=\"apellidos\" maxlength=\"32\"></td></tr>";
				$this->salida .= "	  	           <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
      }elseif($Busqueda=='3'){
        $this->salida .= "				        <tr><td class=\"label\">No. CUENTA</td><td><input type=\"text\" class=\"input-text\" name=\"NumCuenta\" maxlength=\"32\"></td></tr>";
        $this->salida .= "                <tr><td><BR></td></tr>";
				$this->salida .= "                <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
			}elseif($Busqueda=='4'){
        $this->salida .= "				        <tr><td class=\"label\">PREFIJO</td><td><input type=\"text\" class=\"input-text\" name=\"prefijo\" maxlength=\"32\"></td></tr>";
				$this->salida .= "				        <tr><td class=\"label\">NUMERO HISTORIA</td><td><input type=\"text\" class=\"input-text\" name=\"numerohistoria\" maxlength=\"32\"></td></tr>";
				$this->salida .= "                <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
			}
		  $this->salida .= "               <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
      $this->salida .= "               <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"BuscarCompleto\" value=\"LISTADO ORDENES\"></td>";
		  $this->salida .= "			         </table>";
		  $this->salida .= "		           </fieldset></td>";
			$this->salida .= "              <td><fieldset><legend class=\"field\">BUSQUEDA AVANZADA</legend>";
			$this->salida .= "             <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "	      		   <tr>";
			$this->salida .= "				          <td class=\"label\">TIPO<br>BUSQUEDA: </td><td><select name=\"TipoBusqueda\" class=\"select\">";
			$this->salida .="                   <option value=\"1\" selected>Documento</option>";
			$this->salida .="                   <option value=\"2\" selected>Nombres</option>";
			$this->salida .="                   <option value=\"3\" selected>No. Cuenta</option>";
			$this->salida .="                   <option value=\"4\" selected>No. Historia</option>";
			$this->salida .= "                  </select></td></tr>";
			$this->salida .= "				          <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br></td></tr>";
			$this->salida .= "	      		   </tr>";
			$this->salida .= "			         </table>";
			$this->salida .= "		           </fieldset></td></tr>";
		}
    if($bandera=='1'){
      if($Busqueda=='1'){
			  $this->salida .= "          <tr><td align=\"center\" class=\"label_error\">El Paciente $TipoId $PacienteId no se Encuentra Con una orden de Hopitalizacion</td></tr>";
        $this->salida .= "				        <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Crear\" value=\"CREAR ORDEN\">";
        $this->salida .= "	  	          <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
			  $this->salida .= "	  	          <input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
			  $this->salida .= "				        <input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
			  $this->salida .= "			     </table><BR><BR>";
			  $this->salida .= "			      </form>";
		    $this->salida .= ThemeCerrarTabla();
		    return true;
			}elseif($Busqueda=='2'){
        $this->salida .= "          <tr><td align=\"center\" class=\"label_error\">No existe un Paciente con este nombre en el Listado de Admision de Hospitalizacion</td></tr>";
			  $this->salida .= "				   <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
			  $this->salida .= "			     </table><BR><BR>";
			  $this->salida .= "			      </form>";
		    $this->salida .= ThemeCerrarTabla();
		    return true;
			}elseif($Busqueda=='3'){
        $this->salida .= "          <tr><td align=\"center\" class=\"label_error\">El Paciente no Tiene una cuenta Activa</td></tr>";
        $this->salida .= "				   <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
			  $this->salida .= "			     </table><BR><BR>";
			  $this->salida .= "			      </form>";
		    $this->salida .= ThemeCerrarTabla();
		    return true;
			}elseif($Busqueda=='4'){
        $this->salida .= "          <tr><td align=\"center\" class=\"label_error\">El Paciente no Tiene este numero de Historia Clinica</td></tr>";
        $this->salida .= "				   <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
			  $this->salida .= "			     </table><BR><BR>";
			  $this->salida .= "			      </form>";
		    $this->salida .= ThemeCerrarTabla();
		    return true;
			}
		}elseif($bandera=='2'){
				if($Busqueda=='1'){
			  $this->salida .= "          <tr><td align=\"center\" class=\"label_error\">Favor Rectifique los Datos Estan Incompletos</td></tr>";
        $this->salida .= "	  	          <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
			  $this->salida .= "	  	          <input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
			  $this->salida .= "				        <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
			  $this->salida .= "			     </table><BR><BR>";
			  $this->salida .= "			      </form>";
		    $this->salida .= ThemeCerrarTabla();
		    return true;
			}elseif($Busqueda=='2'){
        $this->salida .= "          <tr><td align=\"center\" class=\"label_error\">Debe Llenar Algunos Datos</td></tr>";
			  $this->salida .= "				   <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
			  $this->salida .= "			     </table><BR><BR>";
			  $this->salida .= "			      </form>";
		    $this->salida .= ThemeCerrarTabla();
		    return true;
			}elseif($Busqueda=='3'){
        $this->salida .= "          <tr><td align=\"center\" class=\"label_error\">Debe especificar el numero de Cuenta</td></tr>";
        $this->salida .= "				   <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
			  $this->salida .= "			     </table><BR><BR>";
			  $this->salida .= "			      </form>";
		    $this->salida .= ThemeCerrarTabla();
		    return true;
			}elseif($Busqueda=='4'){
        $this->salida .= "          <tr><td align=\"center\" class=\"label_error\">Debe Especificar un numero de Historia Clinica</td></tr>";
        $this->salida .= "				   <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
			  $this->salida .= "			     </table><BR><BR>";
			  $this->salida .= "			      </form>";
		    $this->salida .= ThemeCerrarTabla();
		    return true;
			}
		}

		if($arreglo==""){
			$arreglo=$this->BuscarPacientesOrdenes();
			if($arreglo==""){
         $this->salida .= "          <tr><td align=\"center\" class=\"label_error\">No Hay Pacientes Para Realizar La Admision de Hospitalizacion</td></tr>";
				 $this->salida .= "			     </table><BR><BR>";
				 $this->salida .= "			      </form>";
		     $this->salida .= ThemeCerrarTabla();
		     return true;
			}
    }
		$this->salida .= "			     </table><BR><BR>";
		$this->salida .= "			      <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"99%\" align=\"center\" class=\"modulo_table_list\">";
    $this->salida .= "            <tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "				       <td width=\"9%\">No. ORDEN</td>";
		$this->salida .= "              <td width=\"10%\">TIPO ORDEN</td>";
		$this->salida .= "	  	         <td width=\"8%\">FECHA</td>";
		$this->salida .= "              <td width=\"10%\">FECHA PRO.</td>";
		$this->salida .= "              <td width=\"10%\">No.DOCUMENTO</td>";
    $this->salida .= "              <td width=\"10%\">No.HISTORIA</td>";
		$this->salida .= "              <td width=\"20%\">NOMBRE COMPLETO</td>";
		$this->salida .= "              <td>EVOLUCION</td>";
		$this->salida .= "            </tr>";
		$y=1;
		$contador=sizeof($arreglo);
		for($i=0;$i<$contador;$i++){
		  if($y % 2){
			  $estilo='modulo_list_claro';
			}else{
			  $estilo='modulo_list_oscuro';
			}
			$infoArreglo = explode ("*", $arreglo[$i]);
			$OrdenHospitalizacion=$infoArreglo[0];
			$TipoOrden=$infoArreglo[1];
			$FechaOrden=$infoArreglo[2];
			$FechaProgramacion=$infoArreglo[3];
      $TipoId=$infoArreglo[4];
      $PacienteId=$infoArreglo[5];
			$accionAdmitirInterna=ModuloGetURL('app','Hospitalizacion','user','AdmitirHospitalizacionInterna',array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'OrdenHospitalizacion'=>$OrdenHospitalizacion));
      $accionAdmitirExterna=ModuloGetURL('app','Hospitalizacion','user','AdmitirHospitalizacionExterna',array('TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'OrdenHospitalizacion'=>$OrdenHospitalizacion));
			if($TipoOrden==0){
			  $tipoOrden='Externa';
        $accionAdmitir=$accionAdmitirExterna;
			}else{
        $tipoOrden='Interna';
				$accionAdmitir=$accionAdmitirInterna;
			}
			$this->salida .= "              <tr class=\"$estilo\"><td align=\"center\">$OrdenHospitalizacion</td>";
      $this->salida .= "              <td align=\"center\">$tipoOrden</td>";
			$Fechaorden=$this->FechaStamp($FechaOrden);
      $Fechaprogramacion=$this->FechaStamp($FechaProgramacion);
      $this->salida .= "              <td align=\"center\">$Fechaorden</td>";
			$this->salida .= "              <td align=\"center\">$Fechaprogramacion</td>";
			$this->salida .= "              <td align=\"center\">$TipoId&nbsp&nbsp&nbsp&nbsp&nbsp;$PacienteId</td>";
      $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
			$Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
      if($this->Historia_clinica_externa){
        $cadena=$this->BuscarNumeroHistoria($TipoId,$PacienteId);
				$Historia = explode ("-", $cadena);
		    $Prefijo=$Historia[0];
		    $NoHistoria=$Historia[1];
			}else{
			  $Prefijo='';
        $NoHistoria='';
			}
			$this->salida .= "              <td align=\"center\">$Prefijo $NoHistoria</td>";
			$this->salida .= "              <td>$Nombres<BR>$Apellidos</td>";
			if($TipoOrden!=0){
        $NumeroEvolucion=$this->BuscarEvolucion($OrdenHospitalizacion);
      }else{
        $NumeroEvolucion="";

			}
      $this->salida .= "              <td align=\"center\">$NumeroEvolucion</td>";
			$this->salida .= "				       <td align=\"center\"><a href=\"$accionAdmitir\">ADMITIR</a></td></tr>";
			$y++;
		}
    $this->salida .= "            </table>";
		$this->salida .= "			      </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

  /**
	*La funcion FormaMensaje es llamada en la clase app_Triage_user.
	*Se encarga de mostrar el mensaje cuando se ingresa un paciente
	*/

	function FormaMensaje($mensaje,$titulo,$action){

				$this->salida  = ThemeAbrirTabla('$titulo');
				$this->salida .= "			      <table width=\"60%\" align=\"center\" class=\"modulo_table\">";
				$this->salida .= "            <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .= "				       <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
				$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
				$this->salida .= "			     </form>";
				$this->salida .= "			     </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*La funcion BuscarIdPaciente es utilizada en esta clase para listar
	*en el combo los diferentes tipo de identifiacion de los pacientes
	*utilizando como parametros los enviados por la funcion tipo_id_paciente
	*que se encarga de obtenerlos de la base de datos
	*/

	function BuscarIdPaciente($tipo_id,$Seleccionado='False',$TipoId=''){

		switch($Seleccionado){

			case 'False':{

				foreach($tipo_id as $value=>$titulo){
					if($value==$TipoId){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				}else{
          $this->salida .=" <option value=\"$value\">$titulo</option>";
				}
			}
				break;
		}

			case 'True':{

				foreach($tipo_id as $value=>$titulo){

					if($value==$TipoId){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
						$this->salida .=" <option value=\"$value\">$titulo</option>";
				}
				break;
			}
		}
	}

/**
*La funcion FechaStamp se encarga de separar la fecha del formato timestamp
*/

 function FechaStamp($fecha){

    if($fecha){
		  $fech = strtok ($fecha,"-");
		  for($l=0;$l<3;$l++){
			  $date[$l]=$fech;
			  $fech = strtok ("-");
		  }
		  return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
    }
  }

/**
*La funcion HoraStamp se encarga de separar la hora del formato timestamp
*/

 function HoraStamp($hora){

   $hor = strtok ($hora," ");
   for($l=0;$l<4;$l++)
   {
	   $time[$l]=$hor;
     $hor = strtok (":");
   }
   return  $time[1].":".$time[2].":".$time[3];
 }

/*
Esta forma retorma el formulario donde se piden el responsable de la cuenta del paciente
*/

  function FormaPedirDatosNuevo($PacienteId,$TipoId,$mensage,$OrdenId)
	{
		$this->salida  = ThemeAbrirTabla('DATOS PACIENTE');
		$this->salida .= "	<br>";
	  /************HOMONIMOS************/
		$homonimos=$this->verificarDocumentosHomonimos($TipoId,$PacienteId);
		if($homonimos != ""){
			$this->salida .= "      <BR><table border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "          <form name=\"formaDatosU\" action=\"$action1\" method=\"post\">";
			$this->salida .= "          <tr><td><fieldset><legend class=\"field\">HOMONIMOS ENCONTRADOS</legend>";
			$this->salida .= "            <table border=\"1\" width=\"95%\" align=\"left\" class=\"modulo_table_list\">";
			$this->salida .= "				          <tr class=\"modulo_table_list_title\"><td align=\"left\">Tipo ID</td>";
			$this->salida .= "				          <td align=\"left\">Numero Documento</td>";
			$this->salida .= "	  	            <td width=\"100%\" align=\"center\" colspan=\"3\">Nombres y Apellidos</td></tr>";
			$y=1;
			foreach($homonimos as $tipo=>$documento){
				if($y % 2){
					$estilo='modulo_list_claro';
				}else{
					$estilo='modulo_list_oscuro';
				}
				$cadena=$this->nombreHomonimo($documento,$tipo);
				$infoCadena = explode ('-', $cadena);
				$Pnombre=$infoCadena[0];
				$Snombre=$infoCadena[1];
				$Papellido=$infoCadena[2];
				$Sapellido=$infoCadena[3];
				$this->salida .= "                <tr class=\"$estilo\"><td align=\"left\">$tipo</td>";
				$this->salida .= "                <td align=\"left\">$documento</td>";
				$this->salida .= "                <td align=\"left\" width=\"100%\">$Pnombre&nbsp&nbsp&nbsp;$Snombre&nbsp&nbsp&nbsp;$Papellido&nbsp&nbsp&nbsp;$Sapellido</td>";
				$this->salida .= "				        <td align=\"center\"><a href=\"\" class=\"link\"><b>CAMBIAR</b></a></td>";
				$this->salida .= "				        <td align=\"center\"><a href=\"\" class=\"link\"><b>CONSULTAR</b></a></td></tr>";
				$y++;
			}
			$this->salida .= "			      </table>";
			$this->salida .= "		      </fieldset></td></tr>";
			$this->salida .= "          </form>";
			$this->salida .= "          </table><BR>";
		}
	/**********************************/
		$this->salida .= "	<table width=\"60%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" class=\"modulo_table\">";
		$action=ModuloGetURL('app','Hospitalizacion','user','VerificarPedirNivel');
		$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$action\" method=\"post\">";
		$this->salida .= "		<tr>";
		$this->salida .= "	  	<td class=\"label_error\" colspan=\"3\" align=\"center\">$mensage<br><br></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\"><td class=\"label\">RESPONSABLE: </td><td><select name=\"Responsable\"  class=\"select\">";
		$responsables=$this->responsables();
		$this->MostrarResponsable($responsables);
		$this->salida .= "       </select></td></tr>";
		$this->salida .= "		<tr height=\"20\"><td class=\"label\">TIPO DOCUMENTO: </td><td>";
		$Tipo=$this->mostrar_id_paciente($TipoId);
		$this->salida .= "		<input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\" readonly>$Tipo</td></tr>";
		$this->salida .= "		<input type=\"hidden\" name=\"OrdenId\" value=\"$OrdenId\">";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"label\">DOCUMENTO: </td>";
		$this->salida .= "	  	<td><input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\" readonly>$PacienteId</td>";
		$this->salida .= "	 		<td>  </td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	    <td  colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br><br></td>";
		$this->salida .= "	 </tr>";
		$this->salida .= "  </form>";
		$this->salida .= "</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/*
La forma de pedir nivel es la que se encarga de presentar la eleccion del nivel segun los
niveles que el responsabel requiere
*/
	function FormaPedirNivel($TipoId,$PacienteId,$Responsable,$OrdenId)
	{
		$this->salida  = ThemeAbrirTabla('NIVELES EPS');
		$this->salida .= "	<br>";
		$this->salida .= "	<table width=\"30%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" class=\"modulo_table\">";
		$action=ModuloGetURL('app','Hospitalizacion','user','LlamarValidarDerechos');
		$this->salida .= "  <form name=\"formapedirnuevo\" action=\"$action\" method=\"post\">";
		$this->salida .= "		<input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
		$this->salida .= "    <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
		$this->salida .= "    <input type=\"hidden\" name=\"Responsable\" value=\"$Responsable\">";
    $this->salida .= "    <input type=\"hidden\" name=\"OrdenId\" value=\"$OrdenId\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$Nombre=$this-> Responsable($Responsable);
		$this->salida .= "		<tr height=\"20\"><td class=\"label\">RESPONSABLE: </td><td>$Nombre</td></tr>";
		$this->salida .= "		<tr height=\"20\"><td class=\"".$this->SetStyle("Nivel")."\">NIVEL: </td><td><select name=\"nivel\"  class=\"select\">";
    $niveles=$this->nivelesEps($Responsable);
		for( $i=0;$i<sizeof($niveles);$i++){
				$this->salida .=" <option value=\"$niveles[$i]\">$niveles[$i]</option>";
		}
		$this->salida .= "       </select></td></tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	    <td  colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br><br></td>";
		$this->salida .= "	 </tr>";
		$this->salida .= "  </form>";
		$this->salida .= "</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
	*La funcion MostrarResponsable se encarga de mostrar los responsables
	*que se estan en la tabal convenio
	*/

 function MostrarResponsable($responsables,$Responsable)
 {
  $i=0;
  $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
	while( $i < sizeof($responsables)){
	  $concate=strtok($responsables[$i],'-');
		for($l=0;$l<4;$l++){
			$var[$l]=$concate;
			$concate = strtok('-');
		}
		$NomTercero=$this->BuscarNombreTercero($var[2],$var[3]);
		if($Responsable==$var[0]){
      $this->salida .=" <option value=\"$var[0]\" selected>$NomTercero $var[1]</option>";
		}else{
		  $this->salida .=" <option value=\"$var[0]\">$NomTercero $var[1]</option>";
		}
		$i++;
  }
 }

/*
Esta funcion presenta el formulario con los datos que son requeridos para insertar
en la tabla de ingresos y cuentas
*/
function FormaIngreso($TipoId,$PacienteId,$Responsable,$nivel,$OrdenId,$CausaExterna,$ViaIngreso,$TipoAfiliado,$Estado,$Poliza){
    $ru='classes/BuscadorDestino/selectorCiudad.js';
		$rus='classes/BuscadorDestino/selector.php';
		$this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";

		if($Responsable=='8'){
      $TipoForma='Soat';
		}
		$Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
		$Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
		$this->salida  = ThemeAbrirTabla('DATOS DEL INGRESO DEL PACIENTE');
		$this->salida .= "      <BR><table border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "          <tr><td align=\"center\"><fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>";
    $datos=$this->BuscarDatosPacienteModificar($TipoId,$PacienteId);
		$Pais=$datos[14];
		$Dpto=$datos[15];
		$Mpio=$datos[16];
		$this->salida .= "	<table width=\"95%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" class=\"modulo_table\">";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	 		<td width=\"20%\"  class=\"label\">FECHA REGISTRO: </td>";
		$Fecha=$this->FechaStamp($datos[10]);
		$this->salida .= "	  	<td width=\"13%\" >$Fecha</td>";
		$this->salida .= "	  	<td width=\"5%\"></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"13\">";
		$this->salida .= "	  	<td class=\"label\">HORA REGSITRO: </td>";
		$Hora=$this->HoraStamp($datos[10]);
		$this->salida .= "	 		<td>$Hora</td>";
		$this->salida .= "	  	<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	  <tr height=\"5\"><td colspan=\"3\">&nbsp;</td></tr>";
    $accionModi=ModuloGetURL('app','Hospitalizacion','user','ModficarDatosPaciente');
		$this->salida .= "  <form name=\"formaadmisionmofi\" action=\"$accionModi\" method=\"post\">";
		$this->salida.= "            <input type=\"hidden\" name=\"TipoId1\" value=\"$TipoId\">";
		$this->salida.= "            <input type=\"hidden\" name=\"TipoForma\" value=\"$TipoForma\">";
    $this->salida.= "            <input type=\"hidden\" name=\"PacienteId1\" value=\"$PacienteId\">";
		$this->salida.= "            <input type=\"hidden\" name=\"accion\" value=\"$accion\">";
    $this->salida.= "            <input type=\"hidden\" name=\"FechaRegistro\" value=\"$datos[10]\">";
		$this->salida.= "            <input type=\"hidden\" name=\"Responsable\" value=\"$Responsable\">";
		$this->salida .= "		<tr height=\"15\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("pais")."\">PAIS: </td>";
		$NomPais=$this->nombre_pais($Pais);
		$this->salida .= "	  	<td><input type=\"text\" name=\"npais\" value=\"$NomPais\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"$Pais\" class=\"input-text\"></td>";
		$this->salida .= "	 		<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"15\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("dpto")."\">DEPARTAMENTO: </td>";
		$NomDpto=$this->nombre_dpto($Pais,$Dpto);
		$this->salida .= "	  	<td><input type=\"text\" name=\"ndpto\" value=\"$NomDpto\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"$Dpto\" class=\"input-text\"></td>";
		$this->salida .= "	 		<td>  </td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"15\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("mpio")."\">CIUDAD: </td>";
		$NomCiudad=$this->nombre_ciudad($Pais,$Dpto,$Mpio);
		$this->salida .= "	  	<td><input type=\"text\" name=\"nmpio\"  value=\"$NomCiudad\" class=\"input-text\" readonly>";
		$this->salida .= "       <input type=\"hidden\" name=\"mpio\" value=\"$Mpio\" class=\"input-text\" ></td>";
		$this->salida .= "	 		<td align=\"left\"><input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form)\"></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td></td>";
		$this->salida .= "	  	<td>&nbsp;</td>";
		$this->salida .= "	 		<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "				       <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoId\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->BuscarIdPaciente($tipo_id,'False',$TipoId);
		$this->salida .= "              </select></td></tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"label\">DOCUMENTO: </td>";
		$this->salida .= "	  	<td><input type=\"text\" name=\"PacienteId\" value=\"$PacienteId\" class=\"input-text\"></td>";
		$this->salida .= "	 		<td>  </td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("PrimerNombre")."\">PRIMER NOMBRE: </td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"20\" name=\"PrimerNombre\" value=\"$datos[2]\" class=\"input-text\"></td>";
		$this->salida .= "	  	<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"label\">SEGUNDO NOMBRE: </td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"20\" name=\"SegundoNombre\" value=\"$datos[3]\" class=\"input-text\"></td>";
		$this->salida .= "	 		<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("PrimerApellido")."\">PRIMER APELLIDO: </td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"30\" name=\"PrimerApellido\" value=\"$datos[0]\" class=\"input-text\"></td>";
		$this->salida .= "	  	<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"label\">SEGUNDO APELLIDO: </td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"30\" name=\"SegundoApellido\" value=\"$datos[1]\" class=\"input-text\"></td>";
		$this->salida .= "	  	<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("FechaNacimiento")."\">FECHA NACIMIENTO: </td>";
		$Fecha=$this->FechaStamp($datos[4]);
		$this->salida .= "	  	<td><input type=\"text\" name=\"FechaNacimiento\" value=\"$Fecha\" class=\"input-text\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\"></td>";
		$this->salida .= "	  	<td width=\"15%\" align=\"left\">"."<a href=\"javascript:show_Calendario('formapedir.FechaNacimiento');\"><img src=\"themes/HTML/default/images/calendario/calendario.png\" border=\"0\" alt=\"ver calendario\"></a> [ dd/mm/aaaa ]</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"label\">DIRECCION: </td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"60\" name=\"Direccion\" value=\"$datos[6]\" class=\"input-text\"></td>";
		$this->salida .= "	  	<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"label\">TELEFONO: </td>";
		$this->salida .= "	  	<td ><input type=\"text\" maxlength=\"30\" name=\"Telefono\" value=\"$datos[7]\" class=\"input-text\"></td>";
		$this->salida .= "	  	<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\"><td class=\"label\">OCUPACION: </td><td><select name=\"Ocupacion\"  class=\"select\">";
		$ocupacion_id=$this->ocupacion();
		$this->BuscarOcupacion($ocupacion_id,'True',$datos[9]);
		$this->salida .= "       </select></td></tr>";
		$this->salida .= "		<tr height=\"20\"><td class=\"label\">SEXO: </td><td><select name=\"Sexo\"  class=\"select\">";
		$sexo_id=$this->sexo();
		$this->BuscarSexo($sexo_id,'True',$datos[11]);
		$this->salida .= "       </select></td></tr>";
		$this->salida .= "		<tr height=\"20\"><td class=\"label\">ESTADO CIVIL: </td><td><select name=\"EstadoCivil\"  class=\"select\">";
		$estado_civil_id=$this->estadocivil();
		$this->BuscarEstadoCivil($estado_civil_id,'True',$datos[12]);
		$this->salida .= "		</select></td></tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  <td  colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Modificar\" value=\"MODIFICAR\"><br></td>";
		$this->salida .= "  </form>";
		$this->salida .= "	</tr>";
		$this->salida .= "</table>";
    $accion=ModuloGetURL('app','Hospitalizacion','user','InsertarDatosIngreso');
		$this->salida .= "         <form name=\"formaingreso\" action=\"$accion\" method=\"post\">";
		$this->salida.= "            <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
		$this->salida.= "            <input type=\"hidden\" name=\"TipoForma\" value=\"$TipoForma\">";
		$this->salida.= "            <input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
		$this->salida.= "            <input type=\"hidden\" name=\"nivel\" value=\"$nivel\">";
    $this->salida.= "            <input type=\"hidden\" name=\"OrdenId\" value=\"$OrdenId\">";
		$this->salida.= "            <input type=\"hidden\" name=\"EmpresaId\" value=\"$EmpresaId\">";
		//$this->salida.= "            <input type=\"hidden\" name=\"TerceroId\" value=\"$TerceroId\">";
		$this->salida.= "            <input type=\"hidden\" name=\"Responsable\" value=\"$Responsable\">";
		$this->salida .= "		      </fieldset></td></tr></table>";
		$this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "		      <tr>";
		$this->salida .= "	 		      <td class=\"label\">FECHA INGRESO: </td>";
		$fechaSistema=date("d/m/Y");
		$this->salida .= "	  	      <td><input type=\"text\"  class=\"input-text\" name=\"fechaIngreso\" value=\"$fechaSistema\"></td>";
		$this->salida .= "	  	      <td></td>";
		$this->salida .= "		      </tr>";
		if($TipoForma=='Soat'){
		$this->salida .= "		      <tr>";
		$this->salida .= "	 		      <td class=\"".$this->SetStyle("poliza")."\">POLIZA: </td>";
		$this->salida .= "	  	      <td><input type=\"text\" class=\"input-text\" name=\"poliza\" value=\"$Poliza\"></td>";
		$this->salida .= "	  	      <td></td>";
		$this->salida .= "		      </tr>"; }
    $this->salida .= "				       <tr><td class=\"".$this->SetStyle("CausaExterna")."\">CAUSA EXTERNA: </td><td><select name=\"CausaExterna\" class=\"select\">";
		$causa_externa=$this->Causa_Externa();
		$this->BuscarIdCausaExterna($causa_externa,'False',$CausaExterna,$TipoForma);
		$this->salida .= "              </select></td></tr>";
    $this->salida .= "				       <tr><td class=\"".$this->SetStyle("ViaIngreso")."\">VIA INGRESO: </td><td><select name=\"ViaIngreso\" class=\"select\">";
		$via_ingreso=$this->Via_Ingreso();
		$this->BuscarIdViaIngreso($via_ingreso,'False',$ViaIngreso,$TipoForma);
		$this->salida .= "              </select></td></tr>";
		if($TipoForma!='Soat'){
		$this->salida .= "				       <tr><td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
		$tipo_afiliado=$this->Tipo_Afiliado();
		$this->BuscarIdTipoAfiliado($tipo_afiliado,'False',$TipoAfiliado);
		$this->salida .= "              </select></td></tr>"; }
		$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Estado")."\">ESTADO AFILIADO: </td><td><select name=\"Estado\" class=\"select\">";
		$estado_afiliado=$this->Estado_Afiliado();
		$this->BuscarIdEstadoAfiliado($estado_afiliado,$Estado);
		$this->salida .= "   </select></td></tr><BR><BR>";
		$this->salida .= "    <table border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "      <tr><td><fieldset><legend class=\"field\">COMENTARIOS</legend>";
		$this->salida .= "        <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "          <tr  align=\"center\"><td class=\"label\" width=\"30%\"><textarea name=\"Comentarios\" cols=\"65\" rows=\"3\" class=\"textarea\"></textarea></td></tr>";
		if($TipoForma=='Autorizar'){
		$this->salida .= "        <td align=\"center\" colspan=\"4\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"AUTORIZAR\"></td>";
		}else{
		$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br></td></tr>";
		}
		$this->salida .= "		   	 </table>";
		$this->salida .= "		  </fieldset></td></tr></table><br>";
		$this->salida .= "    </table>";
		$this->salida .= "	</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}



/**
	*La funcion BuscarIdCausaExterna es utilizada en esta clase para listar
	*en el combo los difer      entes tipo de causas externas
	*/

	function BuscarIdCausaExterna($causa_externa,$Seleccionado='False',$CausaExterna='',$TipoForma)
	{
			 switch($Seleccionado){

					case 'False':{
            $this->salida .=" <option value=\"-1\">------Seleccione------</option>";
						foreach($causa_externa as $value=>$titulo){
							if($TipoForma=='Soat' && $value=='02'){
								$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
							}
							if($value==$CausaExterna){
								$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
							}
							else{
								$this->salida .=" <option value=\"$value\">$titulo</option>";
							}
						}
						break;
					}

					case 'True':{
						foreach($tipo_id as $value=>$titulo){
							if($value==$Causa_externa){
								$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
							}
							else{
									$this->salida .=" <option value=\"$value\">$titulo</option>";
							}
						}
						break;
					}
			}
	}

	/**
	*La funcion BuscarIdViaIngreso es utilizada en esta clase para listar
	*en el combo los diferentes tipos de via de ingreso de los pacientes
	*/
  function BuscarIdViaIngreso($via_ingreso,$Seleccionado='False',$ViaIngreso='',$TipoForma)
	{
			switch($Seleccionado){
					case 'False':{
               $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
							foreach($via_ingreso as $value=>$titulo){
									if($TipoForma=='Soat' && $value==1){
										$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
									}
									if( $value==$ViaIngreso){
										$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
									}
									else{
										$this->salida .=" <option value=\"$value\">$titulo</option>";
									}

							}
							break;
					}

				case 'True':{
							foreach($via_ingreso as $value=>$titulo){
							if($value==$Via_ingreso){
									$this->salida .=" <option value=\"$value\" selected>$titulo</option>"; }
							$this->salida .=" <option value=\"$value\">$titulo</option>";
							}
						break;
				}
			}
	}



	/**
	*La funcion BuscarIdTipoAfiliado es utilizada para listar
	*en el combo los diferentes tipos de afiliados
	*/

	function BuscarIdTipoAfiliado($tipo_afiliado,$Seleccionado='False',$TipoAfiliado=''){

		switch($Seleccionado){

			case 'False':{
          $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($tipo_afiliado as $value=>$titulo){
					if($value==$TipoAfiliado){
					 $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
					else{
					 $this->salida .=" <option value=\"$value\">$titulo</option>";
					}

		  }
				break;
		}

			case 'True':{

				foreach($tipo_afiliado as $value=>$titulo){

					if($value==$Tipo_afiliado){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
						$this->salida .=" <option value=\"$value\">$titulo</option>";
				}
				break;
			}
		}
	}

	/**
	*La funcion BuscarIdEstadoAfiliado es utilizada para listar
	*en el combo los diferentes tipos de estados de los afiliados
	*/

		function BuscarIdEstadoAfiliado($estado_afiliado,$Estado)
	{
			$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
			foreach($estado_afiliado as $value=>$titulo){
					if($value==$Estado){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
					else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
		}
	}

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
	*La funcion BuscarSexo es utilizada en esta clase para listar
	*en el combo los diferentes tipos de sexo, utilizando como
	*parametros los enviados por la funcion sexo que se encarga
	*de obtenerlos de la base de datos
	*/

	function BuscarSexo($sexo_id,$Seleccionado='False',$Sexo='')
	{
		switch($Seleccionado){
			case 'False':{
          $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
					foreach($sexo_id as $value=>$titulo){
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				break;
			}

			case 'True':{
					foreach($sexo_id as $value=>$titulo){
						if($value==$Sexo){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}
						else{
								$this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				break;
			}
		}
	}
	/**
	*La funcion BuscarOcupacion es utilizada en esta clase para listar
	*en el combo los diferentes tipos de ocupaciones, utilizando como
	*parametros los enviados por la funcion ocupacion que se encarga
	*de obtenerlos de la base de datos
	*/

	function BuscarOcupacion($ocupacion_id,$Seleccionado='False',$Ocupacion=''){

		switch($Seleccionado){

			case 'False':{

					foreach($ocupacion_id as $value=>$titulo){
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				break;
			}

			case 'True':{

					foreach($ocupacion_id as $value=>$titulo){
						if($value==$Ocupacion){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}
						else{
						   $this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				break;
			}
		}
	}

	/**
	*La funcion BuscarEstadoCivil es utilizada en esta clase para listar
	*en el combo los diferentes tipos de estado civil, utilizando como
	*parametros los enviados por la funcion estadocivil que se encarga
	*de obtenerlos de la base de datos
	*/

	function BuscarEstadoCivil($estado_civil_id,$Seleccionado='False',$EstadoCivil=''){


		switch($Seleccionado){

			case 'False':{

					foreach($estado_civil_id as $value=>$titulo){
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				break;
			}

			case 'True':{

					foreach($estado_civil_id as $value=>$titulo){
						if($value==$EstadoCivil){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}
						else{
								$this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				break;
			}
		}
	}

/*
Muestra los tipo de departamentos con los que cuenta la ips
*/

function BuscarDepartamento($dpto,$Seleccionado='False',$Dpto=''){

		switch($Seleccionado){

			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($dpto as $value=>$titulo){
					if($value==$Dpto){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				}else{
          $this->salida .=" <option value=\"$value\">$titulo</option>";
				}
			}
				break;
		}

			case 'True':{

				foreach($dpto as $value=>$titulo){

					if($value==$Dpto){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
						$this->salida .=" <option value=\"$value\">$titulo</option>";
				}
				break;
			}
		}
	}

	/*
La funcion BuscarEstaciones muestra las diferentes estaciones de enfermeria que extisten
en la ips
*/
	function BuscarEstaciones($estacion,$Seleccionado='False',$Estacion=''){

		switch($Seleccionado){

			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($estacion as $value=>$titulo){
					if($value==$Estacion){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				}else{
          $this->salida .=" <option value=\"$value\">$titulo</option>";
				}
			}
				break;
		}

			case 'True':{

				foreach($estacion as $value=>$titulo){

					if($value==$Estacion){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
						$this->salida .=" <option value=\"$value\">$titulo</option>";
				}
				break;
			}
		}
	}
/*
La funcion BuscarTipoOrdenHospi muestra los tipo de ordenes de hospitalizacion
*/
	function BuscarTipoOrdenHospi($orden,$Seleccionado='False',$Orden=''){

		switch($Seleccionado){

			case 'False':{

				foreach($orden as $value=>$titulo){
					if($value==$Orden){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				}else{
          $this->salida .=" <option value=\"$value\">$titulo</option>";
				}
			}
				break;
		}

			case 'True':{

				foreach($orden as $value=>$titulo){

					if($value==$Orden){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
						$this->salida .=" <option value=\"$value\">$titulo</option>";
				}
				break;
			}
		}
	}

/*
La funcion BuscarEntidadOrigen muestra la entidades que hacen parte del sistema
de gestion de seguridad social de salud que existen en la base de datos
*/
	function BuscarEntidadOrigen($entidades,$Seleccionado='False',$Entidades=''){

		switch($Seleccionado){

			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($entidades as $value=>$titulo){
					if($value==$Entidades){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				}else{
          $this->salida .=" <option value=\"$value\">$titulo</option>";
				}
			}
				break;
		}

			case 'True':{

				foreach($entidades as $value=>$titulo){

					if($value==$Entidades){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
						$this->salida .=" <option value=\"$value\">$titulo</option>";
				}
				break;
			}
		}
	}


/*
La funcion BuscarDiagnosticos muestra los diferentes diagnosticos que existen en la base de datos
*/
	function BuscarDiagnosticos($diagnosticos,$Seleccionado='False',$Diagnosticos=''){

		switch($Seleccionado){

			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($diagnosticos as $value=>$titulo){
					if($value==$Diagnosticos){
					$this->salida .=" <option value=\"$value\" selected>$value&nbsp&nbsp;/&nbsp&nbsp;$titulo</option>";
				}else{
          $this->salida .=" <option value=\"$value\">$value&nbsp&nbsp;/&nbsp&nbsp;$titulo</option>";
				}
			}
				break;
		}

			case 'True':{

				foreach($diagnosticos as $value=>$titulo){

					if($value==$Diagnosticos){
						$this->salida .=" <option value=\"$value\" selected>$value&nbsp&nbsp;/&nbsp&nbsp;$titulo</option>";
					}
						$this->salida .=" <option value=\"$value\">$value&nbsp&nbsp;/&nbsp&nbsp;$titulo</option>";
				}
				break;
			}
		}
	}

/*
Forma que pide los datos de la asignacion de enfermeria para un paciente teniendo en cuenta el departamento
*/
  function AsignacionEstacionEnfermeria($OrdenId,$Ingreso,$TipoId,$PacienteId,$PlanId,$departamento,$estacion_destino){


		$Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
		$Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
	  $action=ModuloGetURL('app','Hospitalizacion','user','InsertarAsignacionEnfermeria');
    $this->salida  = ThemeAbrirTabla('ASIGNAR ESTACION');
	  $this->salida .= "        <br><br>";
		$this->salida .= "        <BR><table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table\">";
    $this->salida .= "          <tr><td class=\"label_error\" align=\"center\">$mensaje<BR></td></tr>";
		$this->salida .= "          <tr><td></td></tr>";
		$this->salida .= "          <tr><td><fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>";
		$this->salida .= "          <table border=\"0\" width=\"95%\" align=\"center\"  class=\"modulo_table\">";
		$this->salida .= "          <tr><td class=\"label\" width=\"35%\">PACIENTE: </td><td>$Nombres $Apellidos</td></tr>";
		$this->salida .= "          <tr><td class=\"label\" width=\"35%\">IDENTIFICACION: </td><td>$TipoId  $PacienteId</td></tr>";
    $FechaNac=$this->Edad($TipoId,$PacienteId);
    $EdadArr=CalcularEdad($FechaNac,$FechaFin);
		$i=0;
		foreach($EdadArr as $tipo=>$value){
      $EdadA[$i]=$value;
      $i++;
		}
		if($EdadA[3]==1){ $Edad=((12*$EdadA[0])+$EdadA[1]).' meses'; }
		if($EdadA[3]==0){ $Edad=$EdadA[2].' días';  }
		if($EdadA[3]>=2){ $Edad=$EdadA[3].' años';  }
		$this->salida .= "          <tr><td class=\"label\" width=\"35%\">EDAD: </td><td>$Edad</td></tr>";
		$sexo=$this->NombreSexo($TipoId,$PacienteId);
		$this->salida .= "          <tr><td class=\"label\" width=\"35%\">SEXO: </td><td>$sexo</td></tr>";
		$this->salida .= "			     </table>";
		$this->salida .= "           </fieldset></td></tr></table><BR>";
    $this->salida .= "    <BR><table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table\">";
		$this->salida .= "        <tr><td><fieldset><legend class=\"field\">DATOS DEL RESPONSABLE</legend>";
		$this->salida .= "        <table border=\"0\" width=\"95%\" align=\"center\"  class=\"modulo_table\">";
    $responsable=$this->BuscarResponsableServicios($PlanId);
    $infoArreglo = explode ("-", $responsable);
		$numPlan=$infoArreglo[0];
		$nombrePlan=$infoArreglo[1];
		$numTipoTercero=$infoArreglo[2];
    $nombreTipoTercero=$infoArreglo[3];
		$numTercero=$infoArreglo[4];
		$nombreTercero=$infoArreglo[5];
		$this->salida .= "          <tr><td class=\"label\" width=\"35%\">RESPONSABLE SERVICIOS: </td><td>$nombreTipoTercero&nbsp&nbsp&nbsp&nbsp&nbsp;$nombreTercero</td></tr>";
    $this->salida .= "          <tr><td class=\"label\" width=\"35%\">TELEFONOS: </td><td>xxx-xxx</td></tr>";
		$this->salida .= "          <tr><td class=\"label\" width=\"35%\">PLAN: </td><td>$nombrePlan</td></tr>";
		$this->salida .= "			     </table>";
		$this->salida .= "		       </fieldset></td></tr></table><BR>";
	  $this->salida .= "			      <table width=\"40%\" align=\"center\" border=\"0\">";
	  $this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
    if($departamento=='-1'){
		  $this->salida .= "				      <tr><td colspan=\"2\" class=\"label_error\" align=\"center\">Realice una Seleccion<BR><BR></td></tr>";
			$this->salida .= "				       <tr><td class=\"label\">DEPARTAMENTOS: </td><td><select name=\"departamento\" class=\"select\">";
	    $dpto=$this->departamentos_destinos();
	    $this->BuscarDepartamento($dpto,'False','');
	    $this->salida .= "              </select></td></tr>";
		}elseif(!$departamento){
      $this->salida .= "				       <tr><td class=\"label\">DEPARTAMENTOS: </td><td><select name=\"departamento\" class=\"select\">";
	    $dpto=$this->departamentos_destinos();
	    $this->BuscarDepartamento($dpto,'False','');
	    $this->salida .= "              </select></td></tr>";
		}elseif($estacion_destino=='-1'){
      $this->salida .= "				      <tr><td colspan=\"2\" class=\"label_error\" align=\"center\">Realice una Seleccion><BR><BR></td></tr>";
			$this->salida.= "               <input type=\"hidden\" name=\"departamento\" value=\"$departamento\">";
			$this->salida .= "				       <tr><td class=\"label\">ESTACIONES:</td><td><select name=\"estacion\" class=\"select\">";
	    $estacion=$this->estaciones_destinos($departamento);
	    $this->BuscarEstaciones($estacion,'False','');
	    $this->salida .= "              </select></td></tr>";
		}else{
			$this->salida.= "               <input type=\"hidden\" name=\"departamento\" value=\"$departamento\">";
			$this->salida .= "				       <tr><td class=\"label\">ESTACIONES:</td><td><select name=\"estacion\" class=\"select\">";
	    $estacion=$this->estaciones_destinos($departamento);
	    $this->BuscarEstaciones($estacion,'False','');
	    $this->salida .= "              </select></td></tr>";
		}
		$this->salida.= "            <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
		$this->salida.= "            <input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
    $this->salida.= "            <input type=\"hidden\" name=\"OrdenId\" value=\"$OrdenId\">";
		$this->salida.= "            <input type=\"hidden\" name=\"Ingreso\" value=\"$Ingreso\">";
		$this->salida.= "            <input type=\"hidden\" name=\"PlanId\" value=\"$PlanId\">";
		$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Elegir\" value=\"ELEGIR\"><br></td></tr>";
	  $this->salida .= "			      </form>";
	  $this->salida .= "			     </table>";
	  $this->salida .= ThemeCerrarTabla();
		return true;
	}
/*
La funcion OrdenHospitalizacion muestra los datso que son requeridos para realizar un orden
de hopsitalizacion a un paciente
*/
	function OrdenHospitalizacion($PacienteId,$TipoId,$Responsable,$IngresoId,$FechaProgramacion,$HoraOrden,$MinutosOrden,$departamento,$nombreMedico,$diagnostico,$entOrigen){


		$Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
		$Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
	  $action=ModuloGetURL('app','Hospitalizacion','user','InsertarOrdenHospitalizacion');
    $this->salida  = ThemeAbrirTabla('ORDEN HOSPITALIZACION');
		$this->salida .= "        <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table\">";
    $this->salida .= "          <tr><td class=\"label_error\" align=\"center\">$mensaje<BR></td></tr>";
		$this->salida .= "          <tr><td></td></tr>";
		$this->salida .= "          <tr><td><fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>";
		$this->salida .= "          <table border=\"0\" width=\"95%\" align=\"center\"  class=\"modulo_table\">";
		$this->salida .= "          <tr><td class=\"label\" width=\"35%\">PACIENTE: </td><td>$Nombres $Apellidos</td></tr>";
		$this->salida .= "          <tr><td class=\"label\" width=\"35%\">IDENTIFICACION: </td><td>$TipoId  $PacienteId</td></tr>";
    $FechaNac=$this->Edad($TipoId,$PacienteId);
    $EdadArr=CalcularEdad($FechaNac,$FechaFin);
		$i=0;
		foreach($EdadArr as $tipo=>$value){
      $EdadA[$i]=$value;
      $i++;
		}
		if($EdadA[3]==1){ $Edad=((12*$EdadA[0])+$EdadA[1]).' meses'; }
		if($EdadA[3]==0){ $Edad=$EdadA[2].' días';  }
		if($EdadA[3]>=2){ $Edad=$EdadA[3].' años';  }
		$this->salida .= "          <tr><td class=\"label\" width=\"35%\">EDAD: </td><td>$Edad</td></tr>";
		$sexo=$this->NombreSexo($TipoId,$PacienteId);
		$this->salida .= "          <tr><td class=\"label\" width=\"35%\">SEXO: </td><td>$sexo</td></tr>";
		$this->salida .= "			     </table>";
		$this->salida .= "           </fieldset></td></tr></table>";
    $this->salida .= "    <BR><table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table\">";
		$this->salida .= "        <tr><td><fieldset><legend class=\"field\">DATOS DEL RESPONSABLE</legend>";
		$this->salida .= "        <table border=\"0\" width=\"95%\" align=\"center\"  class=\"modulo_table\">";
    $responsable=$this->BuscarResponsableServicios($Responsable);
    $infoArreglo = explode ("-", $responsable);
		$numPlan=$infoArreglo[0];
		$nombrePlan=$infoArreglo[1];
		$numTipoTercero=$infoArreglo[2];
    $nombreTipoTercero=$infoArreglo[3];
		$numTercero=$infoArreglo[4];
		$nombreTercero=$infoArreglo[5];
		$this->salida .= "          <tr><td class=\"label\" width=\"35%\">RESPONSABLE SERVICIOS: </td><td>$nombreTipoTercero&nbsp&nbsp&nbsp&nbsp&nbsp;$nombreTercero</td></tr>";
    $this->salida .= "          <tr><td class=\"label\" width=\"35%\">TELEFONOS: </td><td>xxx-xxx</td></tr>";
		$this->salida .= "          <tr><td class=\"label\" width=\"35%\">PLAN: </td><td>$nombrePlan</td></tr>";
		$this->salida .= "			     </table>";
		$this->salida .= "		       </fieldset></td></tr></table><BR>";
		$this->salida .= "			     <table width=\"55%\" align=\"center\" border=\"0\" class=\"modulo_table\">";
	  $this->salida .= "           <form name=\"formaOrden\" action=\"$action\" method=\"post\">";

		$this->salida .= "		        <tr>";
		$this->salida .= "	  	        <td width=\"35%\" class=\"".$this->SetStyle("FechaProgramacion")."\">FECHA PROGRAMACION : </td>";
		$Fecha=$this->FechaStamp($FechaProgramacion);
		$this->salida .= "	  	        <td><input size=\"17\" type=\"text\" name=\"FechaProgramacion\" value=\"$FechaProgramacion\" class=\"input-text\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\"></td>";
		$this->salida .= "	  	        <td width=\"25%\">"."<a href=\"javascript:show_Calendario('formaOrden.FechaProgramacion');\"><img src=\"themes/HTML/default/images/calendario/calendario.png\" border=\"0\" alt=\"ver calendario\"></a> [ dd/mm/aaaa ]</td>";
		$this->salida .= "		        </tr>";
		$this->salida .= "            <tr><td class=\"".$this->SetStyle("HoraOrden")."\">HORA ORDEN  (HH : mm):</td><td><input size=\"2\" maxlength=\"2\" class=\"input-text\" value=\"$HoraOrden\" name=\"HoraOrden\" type=\"text\"><b>  :  </b>";
    $this->salida .= "            <input size=\"2\" maxlength=\"2\" class=\"input-text\" value=\"$MinutosOrden\" name=\"MinutosOrden\" type=\"text\"></td></tr>";
		$this->salida.= "            <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
		$this->salida.= "            <input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
    $this->salida.= "            <input type=\"hidden\" name=\"Responsable\" value=\"$Responsable\">";
    $this->salida.= "            <input type=\"hidden\" name=\"IngresoId\" value=\"$IngresoId\">";
		$this->salida .= "				    <tr><td class=\"".$this->SetStyle("departamento")."\">DEPARTAMENTO: </td><td><select name=\"departamento\" class=\"select\">";
	  $dpto=$this->departamentos_destinos();
	  $this->BuscarDepartamento($dpto,'False',$departamento);
	  $this->salida .= "              </select></td></tr>";
    $this->salida .= "              <tr><td width=\"35%\" class=\"".$this->SetStyle("nombreMedico")."\">NOMBRE MEDICO</td>";
		$this->salida .= "              <td><input maxlength=\"30\" class=\"input-text\" name=\"nombreMedico\" type=\"text\" value=\"$nombreMedico\"></td></tr>";
		$this->salida .= "				       <tr><td class=\"".$this->SetStyle("diagnostico")."\">DIAGNOSTICO:</td><td><select name=\"diagnostico\" class=\"select\">";
	  $diagnosticos=$this->diagnosticos();
	  $this->BuscarDiagnosticos($diagnosticos,'False',$diagnostico);
	  $this->salida .= "              </select></td></tr>";

    $this->salida .= "				       <tr><td class=\"".$this->SetStyle("entOrigen")."\">ENTIDAD ORIGEN:</td><td><select name=\"entOrigen\" class=\"select\">";
	  $entidades=$this->entidades_Origen();
	  $this->BuscarEntidadOrigen($entidades,'False',$entOrigen);
	  $this->salida .= "              </select></td></tr>";

		$this->salida .= "              <tr><td class=\"label\" width=\"35%\">OBSERVACIONES</td></tr>";
		$this->salida .= "              <tr><td colspan=\"3\"><textarea class=\"textarea\" cols=\"40\" name=\"observaciones\"></textarea></td></tr>";

		$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Elegir\" value=\"ELEGIR\"><br></td></tr>";
	  $this->salida .= "			      </form>";
	  $this->salida .= "			     </table>";
	  $this->salida .= ThemeCerrarTabla();
		return true;
	}
/*
La funcion FormaPedirDatos muestra el fromulario que pide los datos principales como identificacion
y nombres de un paceinte los datos
*/
	function FormaPedirDatos($TipoId,$PacienteId,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Direccion,$Telefono,$ZonaResidencia,$Ocupacion,$FechaRegistro,$Sexo,$EstadoCivil,$Foto,$Pais,$Dpto,$Mpio,$mensage,$nivel,$Responsable)
	{
	 //$TipoId,$PacienteId,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$FechaNacimiento,$FechaNacimientoCalculada,$Direccion,$Telefono,$ZonaResidencia,$Ocupacion,$FechaRegistro,$Sexo,$EstadoCivil,$Foto,$Pais,$Dpto,$Mpio,$mensage,$accion,$Existe,$Responsable,$Afiliado
    $ru='classes/BuscadorDestino/selectorCiudad.js';
		$rus='classes/BuscadorDestino/selector.php';
		$this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";
		$this->javaScripts=ReturnJScalendario();
    $this->salida .= ThemeAbrirTabla('DATOS PACIENTE');
		//HOMONIMOS--//
		$homonimos1=$this->verificarDocumentosHomonimos($TipoId,$PacienteId);
		//$homonimos=$this->verificarNombresHomonimos($TipoId,$PacienteId,$PrimerNombre,$SegundoNombre,$PrimerApellido,$SegundoApellido);
		if($homonimos1!="" ||$homonimos!=""){
			$this->salida .= "      <br><br><table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "          <form name=\"formaDatosU\" action=\"#\"  method=\"post\">";
			$this->salida .= "          <tr><td><fieldset><legend class=\"field\">HOMONIMOS ENCONTRADOS</legend>";
			$this->salida .= "            <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "				        <tr class=\"modulo_table_list_title\"  align=\"center\">";
			$this->salida .= "				          <td width=\"15%\">IDENTIFICACION</td>";
			$this->salida .= "	  	            <td align=\"center\">PACIENTE</td>";
			$this->salida .= "                  <td  width=\"1%\"></td>";
			$this->salida .= "                  <td  width=\"1%\"></td></tr>";
			$y=1;
			$this->consultarUsuarios();
			$actionUsuario=ModuloGetURL('app','Triage','user','mostrarDatosUsuario');
			foreach($homonimos1 as $tipo1=>$documento1){
				if($y % 2){
					$estilo='modulo_list_claro';
				}else{
					$estilo='modulo_list_oscuro';
				}
				$cadena=$this->nombreHomonimo($documento1,$tipo1);
				$infoCadena = explode ('-', $cadena);
				$Pnombre=$infoCadena[0];
				$Snombre=$infoCadena[1];
				$Papellido=$infoCadena[2];
				$Sapellido=$infoCadena[3];
				$this->salida .= "              <tr class=\"$estilo\"><td>$tipo1  $documento1</td>";
				$this->salida .= "		            <input type=\"hidden\" name=\"TipoId\" value=\"$tipo1\" >";
				$this->salida .= "	  	          <input type=\"hidden\" name=\"PacienteId\" value=\"$documento1\" >";
				$this->salida .= "                <td>$Pnombre&nbsp&nbsp&nbsp;$Snombre&nbsp&nbsp&nbsp;$Papellido&nbsp&nbsp&nbsp;$Sapellido</td>";
				$this->salida .= "	  	          <td><b><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"CAMBIAR\"></b></td>";
				$this->salida .= "	  	          <td><b><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CONSULTAR\" onClick=\"consultar('DATOS USUARIO', '$actionUsuario', 500, 300,this.form)\"></b></td></tr>";
				$y++;
			}
			foreach($homonimos as $tipo=>$documento){
				if($y % 2){
					$estilo='modulo_list_claro';
				}else{
					$estilo='modulo_list_oscuro';
				}
				$cadena=$this->nombreHomonimo($documento,$tipo);
				$infoCadena = explode ('-', $cadena);
				$Pnombre=$infoCadena[0];
				$Snombre=$infoCadena[1];
				$Papellido=$infoCadena[2];
				$Sapellido=$infoCadena[3];
				$this->salida .= "              <tr class=\"$estilo\"><td align=\"left\">$tipo $documento</td>";
				$this->salida .= "		            <input type=\"hidden\" name=\"TipoId\" value=\"$tipo\" >";
				$this->salida .= "	  	          <input type=\"hidden\" name=\"PacienteId\" value=\"$documento\" >";
				$this->salida .= "                <td align=\"left\">$Pnombre&nbsp&nbsp&nbsp;$Snombre&nbsp&nbsp&nbsp;$Papellido&nbsp&nbsp&nbsp;$Sapellido</td>";
				$this->salida .= "	  	          <td  width=\"0%\"><b><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"CAMBIAR\"></b></td>";
				$this->salida .= "	  	          <td  width=\"0%\"><b><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CONSULTAR\" onClick=\"consultar('DATOS USUARIO', '$actionUsuario', 500, 300,this.form)\"></b></td></tr>";
				$y++;
			}
			$this->salida .= "			      </table>";
			$this->salida .= "		      </fieldset></td></tr>";
			$this->salida .= "          </form>";
			$this->salida .= "          </table><BR>";
		}
    //----------------------------/
		$this->salida .= "	  <table width=\"60%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" class=\"modulo_table\">";
		$this->salida .=      $this->SetStyle("MensajeError");
		$this->salida .= "		<tr>";
    $action=ModuloGetURL('app','Hospitalizacion','user','InsertarDatosPaciente');
		$this->salida .= "    <form name=\"formapedir\" action=\"$action\" method=\"post\">";
		$this->salida .= "    <input type=\"hidden\" name=\"Responsable\" value=\"$Responsable\">";
		$this->salida .= "	   <input type=\"hidden\" name=\"nivel\" value=\"$nivel\">";
		if($Afiliado){
			$this->Afiliados();
			$accionAfiliado=ModuloGetURL('app','Triage','user','BuscarDatosAfiliado');
			$this->salida .= "	  	<td class=\"label_error\" colspan=\"3\" align=\"center\">$mensage <a href=\"javascript:Afiliados('DATOS DEL AFILIADO','$accionAfiliado',500,300,this.form)\">Ver</a><br><br></td>";
		}else{
		  $this->salida .= "	  	<td class=\"label_error\" colspan=\"3\" align=\"center\">$mensage<br><br></td>";
		}
		$this->salida .= "		  </tr>";
		if($Existe){
			$this->salida .= "		<tr height=\"20\">";
			$this->salida .= "	 	<td width=\"20%\"  class=\"label\">FECHA REGISTRO: </td>";
			$Fecha=$this->FechaStamp($FechaRegistro);
			$this->salida .= "	  <td width=\"13%\" >$Fecha</td>";
			$this->salida .= "	  <td width=\"5%\"></td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr height=\"13\">";
			$this->salida .= "	  <td class=\"label\">HORA REGSITRO: </td>";
			$Hora=$this->HoraStamp($FechaRegistro);
			$this->salida .= "	 	<td>$Hora</td>";
			$this->salida .= "	  <td></td>";
			$this->salida .= "		</tr>";
		}
		$this->salida .= "	  <tr height=\"5\"><td colspan=\"3\">&nbsp;</td></tr>";
		//$this->salida .= "  <form name=\"formapedir\" action=\"$accion\" method=\"post\">";
		$this->salida .= "    <input type=\"hidden\" name=\"Responsable\" value=\"$Responsable\">";
		$this->salida .= "    <input type=\"hidden\" name=\"FechaRegistro\" value=\"$FechaRegistro\">";
		$this->salida .= "    <input type=\"hidden\" name=\"FechaNacimiento1\" value=\"$FechaNacimiento\">";
		$this->salida .= "		<tr height=\"15\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("pais")."\">PAIS: </td>";
		$NomPais=$this->nombre_pais($Pais);
		$this->salida .= "	  	<td><input type=\"text\" name=\"npais\" value=\"$NomPais\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"$Pais\" class=\"input-text\"></td>";
		$this->salida .= "	 		<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"15\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("dpto")."\">DEPARTAMENTO: </td>";
		$NomDpto=$this->nombre_dpto($Pais,$Dpto);
		$this->salida .= "	  	<td><input type=\"text\" name=\"ndpto\" value=\"$NomDpto\" class=\"input-text\" readonly>";
		$this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"$Dpto\" class=\"input-text\"></td>";
		$this->salida .= "	 		<td>  </td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"15\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("mpio")."\">CIUDAD: </td>";
		$NomCiudad=$this->nombre_ciudad($Pais,$Dpto,$Mpio);
		$this->salida .= "	  	<td><input type=\"text\" name=\"nmpio\"  value=\"$NomCiudad\" class=\"input-text\" readonly>";
		$this->salida .= "       <input type=\"hidden\" name=\"mpio\" value=\"$Mpio\" class=\"input-text\" ></td>";
		$this->salida .= "	 		<td align=\"left\"><input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form)\"></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td></td>";
		$this->salida .= "	  	<td>&nbsp;</td>";
		$this->salida .= "	 		<td></td>";
		$this->salida .= "		</tr>";
		if($Existe){
			$this->salida .= "		<tr height=\"20\"><td class=\"".$this->SetStyle("Responsable")."\">RESPONSABLE: </td><td><select name=\"Responsable\"  class=\"select\">";
			$responsables=$this->responsables();
			$this->MostrarResponsable($responsables,$Responsable);
			$this->salida .= "       </select></td></tr>";
		}
		$this->salida .= "		<tr height=\"20\"><td class=\"label\">TIPO DOCUMENTO: </td><td>";
		$Tipo=$this->mostrar_id_paciente($TipoId);
		$this->salida .= "		<input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\" readonly>$Tipo</td></tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"label\">DOCUMENTO: </td>";
		$this->salida .= "	  	<td><input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\" readonly>$PacienteId</td>";
		$this->salida .= "	 		<td>  </td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("PrimerNombre")."\">PRIMER NOMBRE: </td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"20\" name=\"PrimerNombre\" value=\"$PrimerNombre\" class=\"input-text\"></td>";
		$this->salida .= "	  	<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"label\">SEGUNDO NOMBRE: </td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"20\" name=\"SegundoNombre\" value=\"$SegundoNombre\" class=\"input-text\"></td>";
		$this->salida .= "	 		<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("PrimerApellido")."\">PRIMER APELLIDO: </td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"30\" name=\"PrimerApellido\" value=\"$PrimerApellido\" class=\"input-text\"></td>";
		$this->salida .= "	  	<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"label\">SEGUNDO APELLIDO: </td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"30\" name=\"SegundoApellido\" value=\"$SegundoApellido\" class=\"input-text\"></td>";
		$this->salida .= "	  	<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("FechaNacimiento")."\">FECHA NACIMIENTO: </td>";
		$Fecha=$this->FechaStamp($FechaNacimiento);
		$this->salida .= "	  	<td><input type=\"text\" name=\"FechaNacimiento\" value=\"$Fecha\" class=\"input-text\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\"></td>";
		$this->salida .= "	  	<td width=\"25%\">"."<a href=\"javascript:show_Calendario('formapedir.FechaNacimiento');\"><img src=\"themes/HTML/default/images/calendario/calendario.png\" border=\"0\" alt=\"ver calendario\"></a> [ dd/mm/aaaa ]</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"label\">DIRECCION: </td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"60\" name=\"Direccion\" value=\"$Direccion\" class=\"input-text\"></td>";
		$this->salida .= "	  	<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\">";
		$this->salida .= "	  	<td class=\"label\">TELEFONO: </td>";
		$this->salida .= "	  	<td ><input type=\"text\" maxlength=\"30\" name=\"Telefono\" value=\"$Telefono\" class=\"input-text\"></td>";
		$this->salida .= "	  	<td></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr height=\"20\"><td class=\"label\">OCUPACION: </td><td><select name=\"Ocupacion\"  class=\"select\">";
		$ocupacion_id=$this->ocupacion();
		$this->BuscarOcupacion($ocupacion_id,'True',$Ocupacion);
		$this->salida .= "       </select></td></tr>";
		$this->salida .= "		<tr height=\"20\"><td class=\"".$this->SetStyle("Sexo")."\">SEXO: </td><td><select name=\"Sexo\"  class=\"select\">";
		$sexo_id=$this->sexo();
		$this->BuscarSexo($sexo_id,'True',$Sexo);
		$this->salida .= "       </select></td></tr>";
		$this->salida .= "		<tr height=\"20\"><td class=\"label\">ESTADO CIVIL: </td><td><select name=\"EstadoCivil\"  class=\"select\">";
		$estado_civil_id=$this->estadocivil();
		$this->BuscarEstadoCivil($estado_civil_id,'True',$EstadoCivil);
		$this->salida .= "		</select></td></tr>";
		$this->salida .= "		<tr height=\"20\">";
		if($Existe){
			$actionCancelar=ModuloGetURL('app','Triage','user','Buscar');
			$this->salida .= "    <td align=\"right\"><br>";
			$this->salida .= "	  	 <input class=\"input-submit\" type=\"submit\" name=\"Siguiente\" value=\"SIGUIENTE\">&nbsp;&nbsp;";
			$this->salida .= "    </form><br></td>";
			$this->salida .= "    <td align=\"center\"><form name=\"formacancelar\" action=\"$actionCancelar\" method=\"post\">";
			$this->salida .= "	  	<br><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\">";
			$this->salida .= "    </form>";
			$this->salida .= "	  </td>";
		}
		else{
			$this->salida .= "	  <td  colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br><br></td>";
			$this->salida .= "  </form>";
		}
		$this->salida .= "	</tr>";
		$this->salida .= "</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	*La funcion FormaNN es llamada en la clase app_Triage_user.
	*Se encarga de mostrar la forma para capturar los datos de un paciente NN
	*/

	function FormaNN($TipoId,$PacienteId,$mensage,$Responsable,$Sexo,$FechaNacimiento)
	{
    $ru='classes/BuscadorDestino/selectorCiudad.js';
		$rus='classes/BuscadorDestino/selector.php';
		$this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";

		$PrimerNombre='NN';
    $PrimerApellido='NN';
		//if(!$PacienteId){ $PacienteId=rand(); }
		if(!$accion) $accion=ModuloGetURL('app','Hospitalizacion','user','InsertarDatosPacienteNN');
			$this->salida .= ThemeAbrirTabla('DATOS PACIENTE NN');
			$this->salida .= "	<br>";
			$this->salida .= "	<table width=\"60%\" cellspacing=\"0\" border=\"0\" cellpadding=\"0\" align=\"center\" class=\"modulo_table\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "		<tr>";
			$this->salida .= "	  	<td class=\"label_error\" colspan=\"3\" align=\"center\">$mensage<br><br></td>";
			$this->salida .= "		</tr>";
			$this->salida .= "  <form name=\"formapedir\" action=\"$accion\" method=\"post\">";
			$this->salida .= "		<tr height=\"15\">";
			$this->salida .= "	  	<td class=\"".$this->SetStyle("pais")."\">PAIS: </td>";
			$NomPais=$this->nombre_pais($Pais);
			$this->salida .= "	  	<td><input type=\"text\" name=\"npais\" value=\"$NomPais\" class=\"input-text\" readonly>";
			$this->salida .= "      <input type=\"hidden\" name=\"pais\" value=\"$Pais\" class=\"input-text\"></td>";
			$this->salida .= "	 		<td></td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr height=\"15\">";
			$this->salida .= "	  	<td class=\"".$this->SetStyle("dpto")."\">DEPARTAMENTO: </td>";
			$NomDpto=$this->nombre_dpto($Pais,$Dpto);
			$this->salida .= "	  	<td><input type=\"text\" name=\"ndpto\" value=\"$NomDpto\" class=\"input-text\" readonly>";
			$this->salida .= "      <input type=\"hidden\" name=\"dpto\" value=\"$Dpto\" class=\"input-text\"></td>";
			$this->salida .= "	 		<td>  </td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr height=\"15\">";
			$this->salida .= "	  	<td class=\"".$this->SetStyle("mpio")."\">CIUDAD: </td>";
			$NomCiudad=$this->nombre_ciudad($Pais,$Dpto,$Mpio);
			$this->salida .= "	  	<td><input type=\"text\" name=\"nmpio\"  value=\"$NomCiudad\" class=\"input-text\" readonly>";
			$this->salida .= "       <input type=\"hidden\" name=\"mpio\" value=\"$Mpio\" class=\"input-text\" ></td>";
			$this->salida .= "	 		<td align=\"left\"><input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form)\"></td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr height=\"20\">";
			$this->salida .= "	  	<td></td>";
			$this->salida .= "	  	<td>&nbsp;</td>";
			$this->salida .= "	 		<td></td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr height=\"20\"><td class=\"".$this->SetStyle("Responsable")."\">RESPONSABLE: </td><td><select name=\"Responsable\"  class=\"select\">";
			$responsables=$this->responsables();
			$this->MostrarResponsable($responsables,$Responsable);
			$this->salida .= "       </select></td></tr>";
			$this->salida .= "		<tr height=\"20\"><td class=\"label\">TIPO DOCUMENTO: </td><td>";
			$Tipo=$this->mostrar_id_paciente($TipoId);
			$this->salida .= "		<input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\" readonly>$Tipo</td></tr>";
			$this->salida .= "		<tr height=\"20\">";
			$this->salida .= "	  	<td class=\"label\">DOCUMENTO: </td>";
			$this->salida .= "	  	<td><input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\" readonly>$PacienteId</td>";
			$this->salida .= "	 		<td>  </td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr height=\"20\">";
			$this->salida .= "	  	<td class=\"label\">PRIMER NOMBRE: </td>";
			$this->salida .= "	  	<td><input type=\"text\" maxlength=\"20\" name=\"PrimerNombre\" value=\"$PrimerNombre\" class=\"input-text\" readonly></td>";
			$this->salida .= "	  	<td></td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr height=\"20\">";
			$this->salida .= "	  	<td class=\"label\">PRIMER APELLIDO: </td>";
			$this->salida .= "	  	<td><input type=\"text\" maxlength=\"30\" name=\"PrimerApellido\" value=\"$PrimerApellido\" class=\"input-text\" readonly></td>";
			$this->salida .= "	  	<td></td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr>";
			$this->salida .= "	  	<td class=\"".$this->SetStyle("FechaNacimiento")."\">FECHA NACIMIENTO: </td>";
			$this->salida .= "	  	<td><input type=\"text\" name=\"FechaNacimiento\" value=\"$FechaNacimiento\" class=\"input-text\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\"><input type=\"hidden\" value=\"true\" name=\"FechaNacimientoCalculada\"></td>";
			$this->salida .= "	  	<td width=\"25%\" valign=\"top\">"."<a href=\"javascript:show_Calendario('formapedir.FechaNacimiento');\"><img src=\"themes/HTML/default/images/calendario/calendario.png\" border=\"0\" alt=\"ver calendario\"></a> [ dd/mm/aaaa ]</td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr height=\"20\"><td class=\"label\">SEXO: </td><td><select name=\"Sexo\"  class=\"select\">";
			$sexo_id=$this->sexo();
			$this->BuscarSexo($sexo_id,'True',$Sexo);
			$this->salida .= "       </select></td></tr>";
			$this->salida .= "		<tr height=\"20\">";
			$this->salida .= "	  <td  colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br><br></td>";
			$this->salida .= "  </form>";
			$this->salida .= "	</tr>";
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
  }

  function ListadoPendientes()
	{
		$this->salida .= ThemeAbrirTabla('LISTADO DE PACIENTES ADMITIDOS PARA HOSPITALIZACION');
		$this->salida .= "		   <br>";
		$this->salida .= "		<table width=\"99%\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
		$this->salida .= "				<td>PACIENTE</td>";
		$this->salida .= "				<td>DOCUMENTO</td>";
		$this->salida .= "				<td>FECHA INGRESO</td>";
		$this->salida .= "				<td>HORA INGRESO</td>";
		$this->salida .= "				<td>ESTADO</td>";
		$this->salida .= "				<td>TIPO AFILIADO</td>";
		$this->salida .= "				<td>OBSERVACION</td>";
		$this->salida .= "				<td></td>";
		$this->salida .= "				<td></td>";
		$this->salida .= "			</tr>";

    $arreglo=$this->BuscarPacientesAdmitidos();
		$y=1;
		$contador=sizeof($arreglo);
		for($i=0;$i<$contador;$i++){
			$concate=strtok($arreglo[$i],'/');
				for($l=0;$l<7;$l++)	{
					$res[$l]=$concate;
					$concate = strtok('/');
				}
			$i++;
			$datos=$this->BuscarDatosPaciente($res[1],$res[2]);
			if( $y % 2) $estilo='modulo_list_claro';
			else $estilo='modulo_list_oscuro';
      $accionI=ModuloGetURL('app','Hospitalizacion','user','Imprimir',array('TipoId'=>$res[1],'PacienteId'=>$res[2],'Ingreso'=>$res[0],'FechaIngreso'=>$res[3],'Estado'=>$res[4]));
      //$accionG=ModuloGetURL('app','Triage','user','Garantes',array('TipoId'=>$res[1],'PacienteId'=>$res[2],'Ingreso'=>$res[0]));
			foreach($datos as $Fecha=>$NomApellido){
				$Fechas=$this->FechaStamp($res[3]);
				$Horas=$this->HoraStamp($res[3]);
				if($res[4]==1){ $Estado='Activo'; }
				else{ $Estado='Innactivo';}
				$TipoAfialido=$this->Tipo_Afiliado($res[5]);
				$this->salida .= "			<tr class=\"$estilo\">";
				$this->salida .= "				<td>$NomApellido</td>";
				$this->salida .= "				<td><input type=\"hidden\" name=\"TipoId\" value=\"$res[1]\"><input type=\"hidden\" name=\"PacienteId\" value=\"$res[2]\">$res[1]  $res[2]</td>";
				$this->salida .= "				<td align=\"center\"><input type=\"hidden\" name=\"Fecha\" value=\"$Fechas\">$Fechas</td>";
				$this->salida .= "				<td align=\"center\">$Horas</td>";
				$this->salida .= "				<td align=\"center\">$Estado</td>";
				$this->salida .= "				<td align=\"center\">$TipoAfialido[1]</td>";
				$this->salida .= "				<td align=\"center\">$res[6]</td>";
				$this->salida .= "				<td align=\"center\"><a href=\"$accionI\">IMPRIMIR</a></td>";
        //$this->salida .= "				<td align=\"center\"><a href=\"$accionG\">GARANTE</a></td>";
				$this->salida .= "			</tr>";
				$y++;
			}
		}
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaImpresion($TipoId,$PacienteId,$Ingreso,$FechaIngreso,$Estado)
	{
		$this->salida .= ThemeAbrirTabla('IMPRESION');
		$datos=$this->DatosPaciente($TipoId,$PacienteId);
		$NomCiudad=$this->nombre_ciudad($datos[7],$datos[8],$datos[9]);
		$this->salida .= "		<table width=\"45%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table\">";
		$this->salida .= "			<tr>";
		$this->salida .= "			   <td>Fecha </td>";
		$this->salida .= "			   <td>:</td>";
		$this->salida .= "			   <td>$FechaIngreso</td>";
		$this->salida .= "			   <td></td>";
		$this->salida .= "			   <td></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr>";
		$this->salida .= "			   <td>Paciente</td>";
		$this->salida .= "			   <td>:</td>";
		$this->salida .= "			   <td>$datos[5] $datos[6]</td>";
		$this->salida .= "			   <td>Doc. Paciente : </td>";
		$this->salida .= "			   <td>$TipoId $PacienteId</td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr>";
		$this->salida .= "			   <td>F/Nacto</td>";
		$this->salida .= "			   <td>:</td>";
		$this->salida .= "			   <td>$datos[0]</td>";
		$this->salida .= "			   <td></td>";
		$this->salida .= "			   <td></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr>";
		$this->salida .= "			   <td>Dirección</td>";
		$this->salida .= "			   <td>:</td>";
		$this->salida .= "			   <td>$datos[1]</td>";
		$this->salida .= "			   <td></td>";
		$this->salida .= "			   <td></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr>";
		$this->salida .= "			   <td>Est/Civil</td>";
		$this->salida .= "			   <td>:</td>";
		$this->salida .= "			   <td>$datos[3]</td>";
		$this->salida .= "			   <td>Sexo          : </td>";
		$this->salida .= "			   <td>$datos[4]</td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr>";
		$this->salida .= "			   <td>Tipo Afiliado</td>";
		$this->salida .= "			   <td>:</td>";
		$this->salida .= "			   <td></td>";
		$this->salida .= "			   <td>Ciudad:</td>";
		$this->salida .= "			   <td>$NomCiudad</td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr>";
		$this->salida .= "			   <td>Estado/Afil</td>";
		$this->salida .= "			   <td>:</td>";
		$this->salida .= "			   <td></td>";
		$this->salida .= "			   <td></td>";
		$this->salida .= "			   <td></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr>";
		$this->salida .= "			   <td>Observación</td>";
		$this->salida .= "			   <td>:</td>";
		$this->salida .= "			   <td></td>";
		$this->salida .= "			   <td></td>";
		$this->salida .= "			   <td></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr>";
		$this->salida .= "			   <td  align=\"center\" colspan=\"5\"><input class=\"input-submit\" type=\"submit\" name=\"Imprimir\" value=\"IMPRIMIR\"></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "  </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
		}
}//fin clase user
?>

