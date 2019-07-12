<?php
  /**************************************************************************************
  * $Id: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  * 
  * $Revision: 1.7 $   
  * @author Manuel Ruiz Fernandez
  *
  ***************************************************************************************/
  IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");
  
  class DiagnosticosdeVIH_HTML extends DiagnosticosdeVIH
  {
    function DiagnosticosdeVIH_HTML()
    {
      $this->DiagnosticosdeVIH();
      return true;
    }
    
    function GetForma()
    {
      $pfj = $this->frmPrefijo;
      $evento = $_REQUEST['accion'.$pfj];
      
      switch($evento)
      {
        case 'IngresarNotificacion':
          $request = $_REQUEST;
          $action['ingresar_notificacion'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array());
          $datos_paciente = $this->datosPaciente;
          $evolucion = $this->evolucion;
          
          /*print_r("grupo_ficha_id ".$request['grupo_ficha_id']);
          print_r("tipo_diagnostico_id ".$request['tipo_diagnostico_id']);
          print_r("evolucion_id ".$request['evolucion_id']);
          print_r("tabla ".$request['tabla']);
          print_r("submodulo_origen ".$request['submodulo_origen']);*/ 
          
          $mdl = AutoCarga::factory('DiagnosticosdeVIHSQL', '', 'hc1', 'DiagnosticosdeVIH');
          $cod_ficha_noti = $mdl->IngresarFichasNotificacion($request, $datos_paciente, $evolucion);
          if(!$cod_ficha_noti)
          {
            $action['ingresar_ficha'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array());
            $mensaje = $mdl->mensajeDeError;
            $this->salida = $this->frmMensajeIngreso($action,$mensaje );
            return true;
          }
          
          $tendencias = $mdl->ConsultarTendenciasSexuales();
          $perinatal = $mdl->ConsultarPerinatal();
          $parenteral = $mdl->ConsultarParenteral();
          $otros = $mdl->ConsultarTransmisionOtros();
          $tiposPrueba = $mdl->ConsultarTiposPrueba();
          $estadosClinicos = $mdl->ConsultarEstadosClinicos();
          $enfermedades = $mdl->ConsultarEnfermedades();
          
          $this->salida = $this->frmFichaVIH($tendencias, $perinatal, $parenteral, $otros, $tiposPrueba, $estadosClinicos, $enfermedades, $datos_paciente, $cod_ficha_noti, $request);
        break;
        case 'IngresarFicha':
          $request = $_REQUEST;
          $action['ingresar_ficha'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array());
          
          $mdl = AutoCarga::factory('DiagnosticosdeVIHSQL', '', 'hc1', 'DiagnosticosdeVIH');
          $rst = $mdl->IngresarFichasVIH($request,$this->datosPaciente);
          
          $mensaje = "SE REALIZO EL INGRESO DE LA FICHA DE NOTIFICACION";
          
          if(!$rst) $mensaje = $mdl->mensajeDeError;
          $this->salida = $this->frmMensajeIngreso($action,$mensaje );
        break;
        default:
          $mdl = AutoCarga::factory('DiagnosticosdeVIHSQL', '', 'hc1', 'DiagnosticosdeVIH');
          $datos_paciente = $this->datosPaciente;
          $request = $_REQUEST;
          
          $areaProce = $mdl->ConsultarAreasProcedencia();
          $tiposRegimen = $mdl->ConsultarTiposRegimen();
          $pertEtnicas = $mdl->ConsultarPertenenciasEtnicas();
          $gruposPobla = $mdl->ConsultarGruposPoblacionales();
          $clasiSintomas = $mdl->ConsultarClasiSintomas();
          $diagnostico = $mdl->ConsultarDiagnostico($request['diagnostico_ingreso'][$request['grupo_ficha_id']]['diagnostico_id']);
          $empresa = $mdl->ConsultarEmpresa($this->empresa_id);
          
          $this->salida = $this->frmFichaNotificacion($areaProce, $tiposRegimen, $pertEtnicas, $gruposPobla, $clasiSintomas,$diagnostico,$empresa);
          //$this->salida = $this->frmFichaVIH($tendencias, $perinatal, $parenteral, $otros, $tiposPrueba, $estadosClinicos, $enfermedades, $datos_paciente);
        break;
        
      }
      
      return $this->salida;
    }
    /**
    * Funcion donde se crea la forma para ingresar la informacion de la ficha de 
    * notificacion de datos basicos(VIH)
    * 
    * @param array $areaProce vector que contiene la informacion de las areas de
    * procedencia del caso
    * @param array $tiposRegimen vector que contiene la informacion de los tipos de regimen
    * @param array $pertEtnicas vector que contiene la informacion de las pertenencias
    * etnicas
    * @param array $gruposPobla vector que contiene la informacion de los grupos 
    * poblacionales
    * @param array $clasiSintomas vector que contiene la informacion de la clasificacion 
    * inicial del caso
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function frmFichaNotificacion($areaProce, $tiposRegimen, $pertEtnicas, $gruposPobla, $clasiSintomas,$diagnostico,$empresa)
    {
      $pfj = $this->frmPrefijo;
      
      $request = $_REQUEST;
    
      $zona = GetVarConfigAplication('DefaultZona');
      $pais = GetVarConfigAplication('DefaultPais');
      $dpto = GetVarConfigAplication('DefaultDpto');
      $mpio = GetVarConfigAplication('DefaultMpio');
      
      $pct = AutoCarga::factory('Pacientes', '', 'app', 'DatosPaciente');
      $cut = new ClaseUtil();
      
      $NomPais = $pct->ObtenerNombrePais($pais);
      $NomDpto = $pct->ObtenerNombreDepartamento($pais, $dpto);
      $NomMpio = $pct->ObtenerNombreCiudad($pais, $dpto, $mpio);
    
      $action['ingresar_notificacion'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'IngresarNotificacion', "grupo_ficha_id"=>$request['grupo_ficha_id'], "tipo_diagnostico_id"=>$request['diagnostico_ingreso'][$request['grupo_ficha_id']]['tipo_diagnostico_id'], "evolucion_id"=>$request['diagnostico_ingreso'][$request['grupo_ficha_id']]['evolucion_id'], "tabla"=>$request['tabla'], "submodulo_origen"=>$request['submodulo_origen'])); 
    
      $html .= $cut->AcceptDate("/");
      $html .= $cut->IsDate();
      $html .= "<script>\n";
      $html .= "  function MostrarCapaDefuncion(valor)\n";
      $html .= "  {\n";
      $html .= "    if(valor == 'Muerto')\n";
      $html .= "      document.getElementById('capa_defuncion').style.display='block';\n";
      $html .= "    else\n";
      $html .= "      document.getElementById('capa_defuncion').style.display='none';\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeAbrirTablaSubmodulo('FICHA DE INFORMACION DE DATOS BASICOS');
      $html .= "<form id=\"formFichaNotificacion\" name=\"formFichaNotificacion\" action=\"".$action['ingresar_notificacion']."\" method=\"post\">\n";
      $html .= "  <input type=\"hidden\" name=\"pais\" value=\"".$empresa['tipo_pais_id']."\">\n";
      $html .= "  <input type=\"hidden\" name=\"dpto\" value=\"".$empresa['tipo_dpto_id']."\">\n";
      $html .= "  <input type=\"hidden\" name=\"mpio\" value=\"".$empresa['tipo_mpio_id']."\">\n";
      $html .= "  <table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"7\">INFORMACION GENERAL\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"25%\">Nombre del evento\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\" >\n";
      $html .= "        <label class=\"normal_10AN\">".$diagnostico['diagnostico_id']." ".$diagnostico['diagnostico_nombre']."</label>\n";
      $html .= "        <input type=\"hidden\" name=\"evento\" value=\"".$diagnostico['diagnostico_nombre']."\">\n";
      $html .= "        <input type=\"hidden\" name=\"codEvento\" value=\"".$diagnostico['diagnostico_id']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" >Fecha de Notificacion</td>\n";
      $fecha = date("d/m/Y");
      $html .= "        <input type=\"hidden\" name=\"fechaNotificacion\" value=\"".$fecha."\">\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"35%\" ><label class=\"normal_10AN\">".$fecha."</label></td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"10%\">Semana</td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"10%\">\n";
      $html .= "        <label class=\"normal_10AN\">".date("W")."</label>\n";
      $html .= "        <input type=\"hidden\" name=\"semanaEvento\" value=\"".date("W")."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"10%\">Año</td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"10%\">\n";
      $html .= "        <label class=\"normal_10AN\">".date("Y")."</label>\n";
      $html .= "        <input type=\"hidden\" name=\"anyoEvento\" value=\"".date("Y")."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Lugar de notificacion\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">\n";
      $html .= "        <label class=\"normal_10AN\">".$empresa['departamento']." - ".$empresa['municipio']."</label>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Razon social (UPGD)</td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <label class=\"normal_10AN\">".$empresa['razon_social']."</label>\n";
      $html .= "        <input type=\"hidden\" name=\"razonSocial\" value=\"".$empresa['razon_social']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Codigo</td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <label class=\"normal_10AN\">".$empresa['codigo_sgsss']."</label>\n";
      $html .= "        <input type=\"hidden\" name=\"codigosgss\" value=\"".$empresa['codigo_sgsss']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table><br>\n";
      $html .= "  <table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"4\">IDENTIFICACION DEL PACIENTE\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <input type=\"hidden\" name=\"paisM3\" value=\"".$pais."\">\n";
      $html .= "    <input type=\"hidden\" name=\"dptoM3\" value=\"".$dpto."\">\n";
      $html .= "    <input type=\"hidden\" name=\"mpioM3\" value=\"".$mpio."\">\n";
      $html .= "    <input type=\"hidden\" name=\"comunaM3\" value=\"\">\n";
      $url1 = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$pais."&dept=".$dpto."&mpio=".$mpio."&forma=formFichaNotificacion&nombre_campos[ubicacion]=ubicacion1 ";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"25%\">Lugar procedencia del caso\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\" width=\"10%\">\n";
      $html .= "        <label id=\"ubicacion1\">".$NomPais." - ".$NomDpto." - ".$NomMpio."</label>\n";
      $html .= "      - <label id=\"tipo_comunaM3\"></label>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"20%\" align=\"center\">\n";
      $html .= "        <input type=\"button\" class=\"input-submit\" name=\"cPrecedencia\" value=\"Ubicacion\" target=\"localidad\" onclick=\"window.open('".$url1."', 'localidad', 'toolbar=no,width=500,heigth=350,resizable=no,scrollbars=yes').focus(); return false;\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"25%\">Barrio/Localidad procedencia</td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"barrio\" size=\"30%\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" >Area procedencia</td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
      $html .= "        <select class=\"select\" name=\"areaProcedencia\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($areaProce as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['area_procedencia_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"20%\">Tipo de regimen en salud\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"tipoRegimen\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($tiposRegimen as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['tipo_regimen_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Pertenencia etnica\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"pertenenciaEtnica\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($pertEtnicas as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['pert_etnica_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Grupo Poblacional\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
      $html .= "        <select class=\"select\" name=\"grupoPoblacional\">\n";
      $html .= "          <option value=\"-1\">-- seleccionar --</option>\n";
      foreach($gruposPobla as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['grupo_poblacional_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      
      $html .= "		<tr>\n"; 
      $html .= "			<td class=\"formulacion_table_list\">\n";
      $html .= "        Ocupacion:\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"3\">\n";
      $nombre_ocupa = $pct->ObtenerNombreOcupacion($this->datosPaciente['ocupacion_id']);
      $html .= "      	<input type=\"hidden\" name=\"ocupacion_id\" value=\"".$dpaciente['ocupacion_id']."\">\n";
      $html .= "				<input type =\"text\" class=\"input-text\" name=\"descripcion_ocupacion\" readonly style=\"width:70%;background:#FFFFFF\"\" value=\"".$dpaciente['nombre_ocupa']."\">\n";
      //$html .= "				<input type =\"text\" class=\"textarea\"	rows=\"2\" name=\"descripcion_ocupacion\" readonly style=\"width:70%;background:#FFFFFF\"\">".$dpaciente['nombre_ocupa']."</textarea>\n";
      $html .= "				<input type=\"button\" name=\"ocupacion\" value=\"Ocupacion\" class=\"input-submit\" onClick=\"javascript:Ocupaciones('formFichaNotificacion','')\">\n";
      $html .= "			</td>\n";
      $html .= "		</tr>\n";
      
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Nombre Admin. servicios de salud\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"35%\">\n";
      $html .= "        <label class=\"normal_10AN\">".$this->datosResponsable['nombre_tercero']."</label>\n";
      $html .= "        <input type=\"hidden\" name=\"adminServicios\" value=\"".$this->datosResponsable['nombre_tercero']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"8%\">Codigo</td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"codAdmin\" style=\"width:90%\" maxlength=\"6\" value=\"".$this->datosResponsable['codigo_sgsss']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "<br>\n";
      $html .= "  <table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"4\">NOTIFICACION\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Fecha de consulta\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
      $html .= "        <label class=\"normal10_AN\">".date("d/m/Y")."</label>\n";
      $html .= "        <input type=\"hidden\" name=\"fechaConsulta\" value=\"".date("d/m/Y")."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Inicio de sintomas\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"fechaSintomas\" onkeypress=\"return acceptDate(event)\" size=\"12\">\n";
      $html .= "".ReturnOpenCalendario('formFichaNotificacion', 'fechaSintomas', '/')."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Clasificacion inicial de caso\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"clasiCaso\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($clasiSintomas as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['caso_sintoma_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Hospitalizado\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"hospitalizado\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      $html .= "          <option value=\"SI\">SI</option>\n";
      $html .= "          <option value=\"NO\">NO</option>\n";
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Fecha de Hospitalizacion\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"fechaHospitalizacion\" onkeypress=\"return acceptDate(event)\" size=\"10%\">\n";
      $html .= "".ReturnOpenCalendario('formFichaNotificacion', 'fechaHospitalizacion', '/');
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Condicion final\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"condicionFinal\" onChange=\"MostrarCapaDefuncion(this.value)\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      $html .= "          <option value=\"Vivo\">Vivo</option>";
      $html .= "          <option value=\"Muerto\">Muerto</option>\n";
      $html .= "        </select>";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <div id=\"capa_defuncion\" style=\"display:none\">\n";
      $html .= "    <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "      <tr>\n";
      $html .= "        <td class=\"formulacion_table_list\" width=\"22%\">Fecha de defuncion</td>\n";
      $html .= "        <td class=\"modulo_list_claro\">\n";
      $html .= "          <input type=\"text\" class=\"input-text\" name=\"fechaDefuncion\" onkeypress=\"return acceptDate(event)\" size=\"10%\">\n";
      $html .= "".ReturnOpenCalendario('formFichaNotificacion', 'fechaDefuncion', '/');
      $html .= "        </td>\n";
      $html .= "        <td class=\"formulacion_table_list\">No. certificado defuncion\n";
      $html .= "        </td>\n";
      $html .= "        <td class=\"modulo_list_claro\">\n";
      $html .= "          <input type=\"text\" class=\"input-text\" name=\"noCertificado\" size=\"10%\">\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr>\n";
      $html .= "        <td class=\"formulacion_table_list\">Causa basica de muerte\n";
      $html .= "        </td>\n";
      $html .= "        <td class=\"modulo_list_claro\" colspan=\"3\">\n";
      $html .= $cut->OpenDiagnostico("formFichaNotificacion","causaMuerteCIE10","causaMuerte","descripcion_id");
      $html .= "          <label id=\"descripcion_id\" class=\"normal_10AN\"></label>\n";
      $html .= "          <input type=\"hidden\" name=\"causaMuerte\" value=\"\">\n";
      $html .= "          <input type=\"hidden\" name=\"causaMuerteCIE10\" value=\"\">\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "    </table>\n";
      $html .= "  </div>\n";
      $html .= "  <br>\n";
      $html .= "  <table align=\"center\" width=\"100%\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <div id=\"error\" class=\"label_error\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input type=\"button\" class=\"input-submit\" name=\"guardar\" value=\"Guardar\" onclick=\"EvaluarDatos(document.formFichaNotificacion)\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "<script>\n";
      $html .= "  function EvaluarDatos()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formFichaNotificacion;\n";
      $html .= "    if(frm.evento.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el nombre del evento';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.codEvento.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el codigo del evento';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.semanaEvento.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el valor de la semana';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.semanaEvento.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor de la semana debe ser numerico';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.anyoEvento.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el valor del año';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.anyoEvento.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor del año debe ser numerico';";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.razonSocial.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la razon social';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.barrio.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el barrio de procedencia';\n";
      $html .= "      frm.barrio.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.areaProcedencia.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el area de procedencia';\n";
      $html .= "      frm.areaProcedencia.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.tipoRegimen.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el tipo de regimen en salud';\n";
      $html .= "      frm.tipoRegimen.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.pertenenciaEtnica.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar la pertenencia etnica';\n";
      $html .= "      frm.pertenenciaEtnica.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.grupoPoblacional.value==\"-1\")";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el grupo poblacional';\n";
      $html .= "      frm.grupoPoblacional.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.adminServicios.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el nombre de la administradora de servicios de salud';\n";
      $html .= "      frm.adminServicios.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.codAdmin.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el codigo de la administradora de servicios de salud';\n";
      $html .= "      frm.codAdmin.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.fechaConsulta.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la fecha de consulta';\n";
      $html .= "      frm.fechaConsulta.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsDate(frm.fechaConsulta.value))\n";
      $html .= "    {\n"; 
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de consulta posee un formato invalido';\n";
      $html .= "      frm.fechaConsulta.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    fn = frm.fechaNotificacion.value;\n";
      $html .= "    fc = frm.fechaConsulta.value;\n";
      $html .= "    var fecha_n = fn.split('/');\n";
      $html .= "    var fecha_c = fc.split('/');\n";
      $html .= "    ffn = new Date(fecha_n[2]+'/'+fecha_n[1]+'/'+fecha_n[0]);\n";
      $html .= "    ffc = new Date(fecha_c[2]+'/'+fecha_c[1]+'/'+fecha_c[0]);\n";
      $html .= "    if(ffc > ffn)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de consulta debe ser menor o igual a la fecha actual';\n";
      $html .= "      frm.fechaConsulta.focus();\n";
      $html .= "      return;\n"; 
      $html .= "    }\n";
      $html .= "    if(frm.fechaSintomas.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la fecha de inicio de los sintomas';\n";
      $html .= "      frm.fechaSintomas.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsDate(frm.fechaSintomas.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de inicio de los sintomas posee un formato invalido';\n";
      $html .= "      frm.fechaSintomas.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    fs = frm.fechaSintomas.value;\n";
      $html .= "    var fecha_s = fs.split('/');\n";
      $html .= "    ffs = new Date(fecha_s[2]+'/'+fecha_s[1]+'/'+fecha_s[0]);\n";
      $html .= "    if(ffs > ffn)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de inicio de los sintomas debe ser menor o igual a la fecha actual';\n";
      $html .= "      frm.fechaSintomas.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.clasiCaso.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar la clasificacion inicial del caso';\n";
      $html .= "      frm.clasiCaso.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.hospitalizado.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe indicar si el paciente se encuentra hospitalizado o no';\n";
      $html .= "      frm.hospitalizado.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.hospitalizado.value==\"SI\" && frm.fechaHospitalizacion.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la fecha de hospitalizacion';\n";
      $html .= "      frm.fechaHospitalizacion.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.hospitalizado.value==\"SI\" && !IsDate(frm.fechaHospitalizacion.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de Hospitalizacion posee un formato invalido';\n";
      $html .= "      frm.fechaHospitalizacion.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.hospitalizado.value==\"NO\" && frm.fechaHospitalizacion.value!=\"\")";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Al ingresar una fecha de hospitalizacion debe seleccionar la opcion SI en el campo Hospitalizado';\n";
      $html .= "      frm.hospitalizado.focus();\n"; 
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    fh = frm.fechaHospitalizacion.value;\n";
      $html .= "    var fecha_h = fh.split('/');\n";
      $html .= "    ffh = new Date(fecha_h[2]+'/'+fecha_h[1]+'/'+fecha_h[0]);\n";
      $html .= "    if(frm.hospitalizado.value==\"SI\" && (ffh > ffn))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de hospitalizacion debe ser menor o igual a la fecha actual';\n";
      $html .= "      frm.fechaHospitalizacion.value();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.condicionFinal.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar la condicion final del paciente';\n";
      $html .= "      frm.condicionFinal.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.condicionFinal.value==\"Vivo\" && frm.fechaDefuncion.value!=\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Al ingresar una fecha de defuncion debe seleccionar la opcion Muerto en el campo Condicion final';\n";
      $html .= "      frm.condicionFinal.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.condicionFinal.value==\"Muerto\" && frm.fechaDefuncion.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la fecha de defuncion';\n";
      $html .= "      frm.fechaDefuncion.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.condicionFinal.value==\"Muerto\" && !IsDate(frm.fechaDefuncion.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de defuncion posee un formato invalido';\n";
      $html .= "      frm.fechaDefuncion.focus();\n";
      $html .= "      return;";
      $html .= "    }\n";
      $html .= "    fd = frm.fechaDefuncion.value;\n";
      $html .= "    var fecha_d = fd.split('/');\n";
      $html .= "    ffd = new Date(fecha_d[2]+'/'+fecha_d[1]+'/'+fecha_d[0]);\n";
      $html .= "    if(frm.condicionFinal.value==\"Muerto\" && (ffd > ffn))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de defuncion debe ser menor o igual a la fecha actual';\n";
      $html .= "      frm.fechaDefuncion.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.condicionFinal.value==\"Vivo\" && frm.noCertificado.value!=\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Al ingresar un numero de certificado de defuncion debe seleccionar la opcion Muerto en el campo Condicion final';\n";
      $html .= "      frm.condicionFinal.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.condicionFinal.value==\"Muerto\" && frm.noCertificado.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el numero de certificado de defuncion';\n";
      $html .= "      frm.noCertificado.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.condicionFinal.value==\"Muerto\" && !IsNumeric(frm.noCertificado.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor del certificado de defuncion debe ser numerico';\n";
      $html .= "      frm.noCertificado.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.condicionFinal.value==\"Vivo\" && frm.causaMuerte.value!=\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Al ingresar la causa de muerte debe seleccionar la opcion Muerto en el campo Condicion final';\n";
      $html .= "      frm.condicionFinal.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.condicionFinal.value==\"Muerto\" && frm.causaMuerte.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la causa basica de muerte';\n";
      $html .= "      frm.causaMuerte.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "</form>\n";
      
      $html .= ThemeCerrarTablaSubmodulo();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma para ingresar la informacion de la ficha de 
    * notificacion de datos complementarios (VIH)
    * @param array $tendencias vector con la informacion de los tipos de tendencia sexual
    * @param array $perinatal vector con la informacion de los tipos perinatal
    * @param array $parenteral vector con la informacion de los tipos parenteral
    * @param array $otros vector con la informacion de otros probables mecanismos de 
    * transmision
    * @param array $tiposPrueba vector con la informacion de los tipos de prueba
    * @param array $estadosClinicos vector con la informacion de los estados clinicos
    * @param array $enfermedades vector con la informacion de las enfermedades asociadas
    * @param array $datos_paciente vector con la informacion del paciente
    * @param array $cod_ficha_noti numero de identificacion de la ficha de notificacion 
    * ingresada
    * @param array $request vector con la informacion del request
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function frmFichaVIH($tendencias, $perinatal, $parenteral, $otros, $tiposPrueba, $estadosClinicos, $enfermedades, $datos_paciente, $cod_ficha_noti, $request)
    {
      $pfj = $this->frmPrefijo;
    
      $request = $_REQUEST;
    
      $action['ingresar_ficha'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>'IngresarFicha', "cod_ficha_noti"=>$cod_ficha_noti, "grupo_ficha_id"=>$request['grupo_ficha_id'], "tipo_diagnostico_id"=>$request['tipo_diagnostico_id'], "evolucion_id"=>$request['evolucion_id'], "tabla"=>$request['tabla'], "submodulo_origen"=>$request['submodulo_origen'])); 
      
      $html  = ThemeAbrirTablaSubmodulo('FICHA VIH');
      $html .= "<form id=\"formFichaVIH\" name=\"formFichaVIH\" action=\"".$action['ingresar_ficha']."\" method=\"post\">\n";
      $html .= "  <table align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\">ANTECEDENTES EPIDEMIOLOGICOS\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"normal_10AN\">\n";
      $html .= "      <td align=\"left\" colspan=\"6\">Mecanismo probable de transmision\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"20%\">Sexual-Tendencia\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <select class=\"select\" name=\"dTendencia\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($tendencias as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['tendencia_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"20%\">Perinatal\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <select class=\"select\" name=\"dPerinatal\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($perinatal as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['perinatal_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Parenteral\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <select class=\"select\" name=\"dParenteral\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($parenteral as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['parenteral_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Otros\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <select class=\"select\" name=\"dOtros\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($otros as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['transmision_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\">DIAGNOSTICO DE LABORATORIO\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"25%\">5.1. Tipo de prueba\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"dTiposPrueba\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($tiposPrueba as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['tipo_prueba_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"25%\">5.2. Fecha de resultado\n";
      $html .= "      </td>\n";
      $fecha = date("d/m/Y");
      $html .= "      <input type=\"hidden\" name=\"fechaSistema\" value=\"".$fecha."\">\n";
      $cut = new ClaseUtil();
      $html .= $cut->AcceptDate("/");
      $html .= $cut->IsDate();
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"fechaResultado\" onkeypress=\"return acceptDate(event)\" size=\"10%\">\n";
      $html .= "".ReturnOpenCalendario('formFichaVIH', 'fechaResultado', '/')."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"25%\">5.3. Valor de la carga viral\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"cargaViral\" size=\"5%\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\" >INFORMACION CLINICA\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"normal_10AN\" colspan=\"6\">Estado clinico\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Estado clinico\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"dEstadoClinico\" onchange=\"MostrarEnfermedades(document.formFichaVIH)\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($estadosClinicos as $indice => $valor)
      {  
        $html .= "          <option value=\"".$valor['estado_clinico_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" colspan=\"2\">No. de hijos menores de 18 años\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">Hombres\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"noHombres\" size=\"5%\">";
      $html .= "          Mujeres ";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"noMujeres\" size=\"5%\">";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if($datos_paciente['sexo_id']=="F")
      {
        $html .= "    <tr>\n";
        $html .= "      <td class=\"normal_10AN\" colspan=\"6\">Situacion de embarazo\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\">¿Embarazo?\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <select class=\"select\" name=\"embarazo\">\n";
        $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
        $html .= "          <option value=\"SI\">SI</option>\n";
        $html .= "          <option value=\"NO\">NO</option>\n";
        $html .= "        </select>\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"formulacion_table_list\" colspan=\"3\">No. de semanas de embarazo al diagnostico\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input type=\"text\" class=\"input-text\" name=\"noSemanas\" size=\"5%\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
      }
      $html .= "    <tr>\n";
      $html .= "      <td class=\"normal_10AN\" colspan=\"6\">\n";
      $html .= "        <div id=\"divEnfermedades\" style=\"display:none\">Enfermedades asociadas\n";
      $html .= "          <table width=\"100%\">\n";
      $html .= "            <tr>\n";
      $html .= "              <td class=\"formulacion_table_list\" colspan=\"6\">Seleccione las enfermedades asociadas que presente el paciente (en caso de sida)\n";
      $html .= "              </td>\n";
      $html .= "            </tr>\n";
      
      $cont = 1;
      $cont1 = 0;
      
      foreach($enfermedades as $indice => $valor)
      {
        if($cont==1)
          $html .= "        <tr class=\"modulo_list_claro\">\n";

        $html .= "            <td colspan=\"2\">\n";
        $html .= "              <input type=\"checkbox\" name=\""."check".$cont1."\" value=\"".$valor['enfermedad_id']."\">\n";
        $html .= "        ".$valor['descripcion']."";
        $html .= "            </td>\n";

        if($cont==3)
          $html .= "        </tr>\n";
        if($cont==3)
          $cont=0;
        $cont = $cont + 1; 
        $cont1 = $cont1 + 1;
      }
      $html .= "  <input type=\"hidden\" name=\"cantCheck\" value=\"".$cont1."\">\n";
      $html .= "          </table>\n";
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <br>\n";
      $html .= "  <table align=\"center\" width=\"90%\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <div id=\"error\" class=\"label_error\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input type=\"button\" class=\"input-submit\" name=\"guardar\" value=\"Guardar\" onclick=\"EvaluarDatos(document.formFichaVIH)\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <script>\n";
      $html .= "    function MostrarEnfermedades(frm)\n";
      $html .= "    {\n";
      $html .= "      var indice = frm.dEstadoClinico.selectedIndex;";
      $html .= "      if(frm.dEstadoClinico.options[indice].text==\"SIDA\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('divEnfermedades').style.display = 'block';\n";
      $html .= "        return;\n";
      $html .= "      }else{\n";
      $html .= "        document.getElementById('divEnfermedades').style.display = 'none';\n";
      $html .= "        return;\n";
      $html .= "      }";
      $html .= "    }\n";
      $html .= "  </script>\n";
      
      $html .= "  <script>\n";
      $html .= "    function EvaluarDatos()\n";
      $html .= "    {\n";
      $html .= "      frm = document.formFichaVIH;\n";
      $html .= "      if(frm.dTendencia.value==\"-1\" && frm.dPerinatal.value==\"-1\" && frm.dParenteral.value==\"-1\" && frm.dOtros.value==\"-1\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'Debe seleccionar una opcion en el area antecedentes epidemiologicos';\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(frm.dTiposPrueba.value==\"-1\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'Debe seleccionar una opcion en el campo Tipo de prueba';\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(frm.fechaResultado.value==\"\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'Debe ingresar la Fecha de resultado';\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(!IsDate(frm.fechaResultado.value))\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'La Fecha de resultado posee un formato invalido';\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      fs = frm.fechaSistema.value;\n";
      $html .= "      fr = frm.fechaResultado.value;\n";
      $html .= "      var fecha_s = fs.split('/');\n";
      $html .= "      var fecha_r = fr.split('/');\n";
      $html .= "      ffs = new Date(fecha_s[2]+'/'+fecha_s[1]+'/'+fecha_s[0]);\n";
      $html .= "      ffr = new Date(fecha_r[2]+'/'+fecha_r[1]+'/'+fecha_r[0]);\n";      
      $html .= "      if(ffr > ffs)\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'La fecha de resultado debe ser menor o igual a la fecha actual';\n";
      $html .= "        return;\n";
      $html .= "      }\n";    
      $html .= "      var indice = frm.dTiposPrueba.selectedIndex;\n";
      $html .= "      if(frm.dTiposPrueba.options[indice].text==\"Carga Viral\" && frm.cargaViral.value==\"\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'Debe ingresar el valor de la Carga Viral';\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(frm.dTiposPrueba.options[indice].text==\"Carga Viral\" && !IsNumeric(frm.cargaViral.value))\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'El valor de la Carga viral debe ser numerico';\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(frm.cargaViral.value!=\"\" && frm.dTiposPrueba.options[indice].text!=\"Carga Viral\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'Al ingresar el valor de la carga viral debe seleccionar la opcion Carga Viral en el campo Tipo Prueba';\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(frm.dEstadoClinico.value==\"-1\")\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'Debe seleccionar una opcion en el campo Estado Clinico';\n";
      $html .= "        frm.dEstadoClinico.focus();\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(frm.noHombres.value!=\"\" && !IsNumeric(frm.noHombres.value))\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'La cantidad de hijos debe ser un valor numerico';\n";
      $html .= "        frm.noHombres.focus();\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      $html .= "      if(frm.noMujeres.value!=\"\" && !IsNumeric(frm.noMujeres.value))\n";
      $html .= "      {\n";
      $html .= "        document.getElementById('error').innerHTML = 'La cantidad de hijas debe ser un valor numerico';\n";
      $html .= "        frm.noMujeres.focus();\n";
      $html .= "        return;\n";
      $html .= "      }\n";
      if($datos_paciente['sexo_id']=="F")
      {
        $html .= "      if(frm.embarazo.value==\"SI\" && frm.noSemanas.value==\"\")\n";
        $html .= "      {\n";
        $html .= "        document.getElementById('error').innerHTML = 'Debe ingresar el numero de semanas de embarazo';\n";
        $html .= "        frm.noSemanas.focus();\n";
        $html .= "        return;\n";
        $html .= "      }\n";
        $html .= "      if(frm.embarazo.value==\"NO\" && frm.noSemanas.value!=\"\")\n";
        $html .= "      {\n";
        $html .= "        document.getElementById('error').innerHTML = 'Al ingresar el numero de semanas de embarazo debe seleccionar la opcion SI en el campo embarazo';\n";
        $html .= "        frm.embarazo.focus();\n";
        $html .= "        return;\n";
        $html .= "      }\n";
        $html .= "      if(frm.embarazo.value==\"SI\" && !IsNumeric(frm.noSemanas.value))\n";
        $html .= "      {\n";
        $html .= "        document.getElementById('error').innerHTML = 'La cantidad de semanas de embarazo debe ser un valor numerico';\n";
        $html .= "        frm.noSemanas.focus();\n";
        $html .= "        return;\n";
        $html .= "      }\n";
      }
      $html .= "      frm.submit();\n";
      $html .= "    }\n";
      $html .= "  </script>\n";
      $html .= "</form>\n";
      
      $html .= ThemeCerrarTablaSubmodulo();
      
      return $html;
    } 
    /**
    * Funcion donde se crea la forma para mostrar el mensaje indicando que la informacion 
    * ingresada para las fichas de notificacion fue almacenada
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param string $mensaje cadena con el mensaque se se va a mostrar
    * @return string $html cadena con el codigo html de la pagina
    */    
    function frmMensajeIngreso($action,$mensaje)
    {
      $pfj = $this->frmPrefijo;  
      $request = $_REQUEST;
      
      $action['volver'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array("submodulo_origen"=>"DiagnosticoI"));
      
      $html  = ThemeAbrirTabla('MENSAJE');
      
      $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <table width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "        <tr class=\"normal_10AN\">\n";
      $html .= "          <td align=\"center\">\n".$mensaje."</td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\"><br>\n";
      $html .= "      <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
      $html .= "      </form>";
      $html .= "    </td>";
      $html .= "  </tr>";
      $html .= "</table>";
      
      $html .= ThemeCerrarTabla();      
      return $html;
    }
  }
?>