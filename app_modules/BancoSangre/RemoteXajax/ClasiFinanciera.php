<?php
  /**
  * Funcion que permite mostrar los grados relacionados a una categoria
  *
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return string $objResponse objeto de respuesta al formulario
  */
  IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");
  function BuscarGrado($form)
  {
    $objResponse = new xajaxResponse();
    $categoria = $form['categoria'];

    //IncludeClass('ConexionBD');
    //IncludeClass('BancoSangreSQL','','app','BancoSangre');
    //$mdl = new BancoSangreSQL();
    
    $mdl = AutoCarga::factory('BancoSangreSQL','','app','BancoSangre');    
    $datos = $mdl->ConsultarGradoCategoria($categoria);

    $html .= "<td class=\"modulo_list_claro\">\n";
    $html .= "  <select class=\"select\" name=\"grado\" onchange=\"ValidarGrado()\">\n";
    $html .= "    <option value=\"-1\">-- Seleccionar --</option>\n";
    foreach($datos as $indice => $valor)
    {
      $html .= "  <option value=\"".$valor['grado_id']."\">".$valor['descripcion']."</option>\n";
    }
    $html .= "  </select>\n";
    $html .= "</td>\n";
    
    $objResponse->assign("descGrado", "innerHTML", $html);
    
    return $objResponse;
  }
  /**
  * Funcion que permite mostrar la clasificacion financiera
  *
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return string $objResponse objeto de respuesta al formulario
  */  
  function BuscarClasiFinanciera($form)
  {
    $objResponse = new xajaxResponse();
    $categoria = $form['categoria'];
    $grado = $form['grado'];
    
    $mdl = AutoCarga::factory('BancoSangreSQL','','app','BancoSangre');
    $datos = $mdl->ConsultarClasiFinanciera($categoria, $grado);
    
    if(count($datos)<2)
    {  
      $html .= "<td class=\"modulo_list_claro\">".$datos[0]['descripcion']."\n";
      $html .= "  <input type=\"hidden\" name=\"clasificacion\" id=\"clasificacion\" value=\"".$datos[0]['clasifi_finaci_id']."\">\n";
      $html .= "</td>\n";
    }else{
      $html .= "<td class=\"modulo_list_claro\">\n";
      $html .= "  <select class=\"select\" name=\"clasificacion\">\n";
      $html .= "    <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($datos as $indice => $valor)
      {
        $html .= "  <option value=\"".$valor['clasifi_finaci_id']."\">".$valor['descripcion']."</option>\n";
      }
      
      $html .= "  </select>\n";
      $html .= "</td>\n";
    }
    
    $objResponse->assign("clasiFinanciera", "innerHTML", $html);
    
    return $objResponse;
  }
  /**
  * Funcion que permite mostrar la edad calculada del donante
  *
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return string $objResponse objeto de respuesta al formulario
  */
  function CalcEdad($form)
  {
    $objResponse = new xajaxResponse();
    $fecha = $form['fechaNacimiento'];
    
    $fn = explode("/",$fecha);
    if(sizeof($fn)==3) $fNac=$fn[2]."-".$fn[1]."-".$fn[0];
    
    $mdl = AutoCarga::factory('BancoSangreSQL', '', 'app', 'BancoSangre');
    $edad = $mdl->CalcularEdad($fNac);    

    $objResponse->assign("edad_oc", "value", $edad['edad']);
    $objResponse->assign("edad_c", "innerHTML", $edad['edad']);
    
    return $objResponse;
  }
  /**
  * Funcion que permite validar la identificacion de un donante y mostrar un mensaje en 
  * caso de que no sea valida
  *
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return string $objResponse objeto de respuesta al formulario
  */
  function ValidarCedula($form)
  {
    $objResponse = new xajaxResponse();
    $no_id = $form['noId'];
    $tipo_id = $form['tipoId'];
    $tipo_ingreso = $form['tipoIngreso'];
    
    if($tipo_ingreso == "nuevo")
    {
      //$objResponse->alert($tipo_ingreso);
      $mdl = AutoCarga::factory('BancoSangreSQL', '', 'app', 'BancoSangre');
      $val_id = $mdl->ConsultarIdentificacion($no_id, $tipo_id);
      
      if(count($val_id)>=1)
      {
        $html .= "<td>La cedula de identidad y el tipo de identificacion ya existen\n";
        $html .= "</td>\n";
        $var_ci = "";
        
        //$objResponse->assign("error_bp", "innerHTML", $html);       
        //$objResponse->assign("infoPaciente", "innerHTML", $var_ci);
        $objResponse->assign("error", "innerHTML", $html);
        
        return $objResponse;
      }
    }
    
    //$objResponse->alert('VALIDA ID AUTENTICA');
    
    $val = ModuloGetVar('', '', 'validacion_identificacion');
    if($val == $tipo_id)
    {
      $cl = AutoCarga::factory('ClaseUtil');
      $valida = $cl->ValidarCedulaEC($no_id);

      if($valida==false)
      {
        $valor = "no_autentica";
        $msg = "Por favor verificar la cedula de identidad";
        $objResponse->assign("val_id", "value", $valor);
        $objResponse->assign("error", "innerHTML", $msg);
      }else{
        $valor = "autentica";
        $objResponse->assign("val_id", "value", $valor);
        $objResponse->call("continuar");
      }      
    }else{
      $objResponse->call("continuar");
    }
    
    return $objResponse;
  }
  /**
  * Funcion que permite mostrar la informacion del donante, dependiendo de la tabla 
  * donde encuentre los datos
  *
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return string $objResponse objeto de respuesta al formulario
  */  
  function BuscarDatosPaciente($form)
  {
    $objResponse = new xajaxResponse();
    $no_id = $form['noId'];
    $tipo_id = $form['tipoId'];
    
    $mdl = AutoCarga::factory('BancoSangreSQL','','app','BancoSangre');
    
    $tipos_donante = $mdl->ConsultarTiposDonante();
    $convenios = $mdl->ConsultarConvenios();
    $tipos_id = $mdl->ConsultarTipoId();
    $tipos_fuerza = $mdl->ConsultarTipoFuerzas();
    $estado_fuerza = $mdl->ConsultarCategorias();
    $tipos_sexo = $mdl->ConsultarTiposSexo();
    $estado_civil = $mdl->ConsultarTiposEstadoCivil();  
    
    $val = ModuloGetVar('', '', 'validacion_identificacion');
    if($val == $tipo_id)
    {
      $cl = AutoCarga::factory('ClaseUtil');
      $valida = $cl->ValidarCedulaEC($no_id);

      if($valida==false)
      {
        $html .= "<td>La cedula debe ser autentica\n";
        $html .= "</td>\n";
        $vac = "";
        $objResponse->assign("error_bp", "innerHTML", $html);
        $objResponse->assign("infoPaciente", "innerHTML", $vac);
        $objResponse->assign("error", "innerHTML", $vac);
        return $objResponse;
      }
    }
    
    $militar = $mdl->ConsultarMilitar($no_id, $tipo_id);
    $datos = $mdl->ConsultarDonante($no_id, $tipo_id, $militar);
    $estado_dc = $mdl->ConsultarEstadoDC($no_id, $tipo_id);
    
    if(count($datos)!=0)
    {
      $lugar_naci = $mdl->ConsultarLugarNacimiento($no_id, $tipo_id);      
      $lugar_domi = $mdl->ConsultarLugarDomicilio($no_id, $tipo_id);
      
      $fr = explode('-', $datos[0]['fecha_registro']);
      $cod_fr = $fr[1].$fr[2];
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"100%\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.6%\">Cod. Donante:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"16.6%\">".$cod_fr."-".$datos[0]['codigo_donante']."\n";
      $html .= "        <input type=\"hidden\" name=\"codDonante\" id=\"codDonante\" value=\"".$datos[0]['codigo_donante']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.6%\">Historia Clinica:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"16.6%\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.6%\">Apellido Paterno:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"16.6%\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"apellidoPaterno\" size=\"15%\" value=\"".$datos[0]['primer_apellido']."\">\n";
      $html .= "      </td>\n";     
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Apellido Materno:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"apellidoMaterno\" size=\"15%\" value=\"".$datos[0]['segundo_apellido']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Primer Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"primerNombre\" size=\"15%\" value=\"".$datos[0]['primer_nombre']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Segundo Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"segundoNombre\" size=\"15%\" value=\"".$datos[0]['segundo_nombre']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Fuerza:\n";
      $html .= "      </td>\n";
      if($militar[0]['clasifi_finaci_id']!="")
      {
        $html .= "    <td class=\"modulo_list_claro\">\n";
        $html .= "      <div id=\"descFuerza\">\n";
        $html .= "        <select class=\"select\" name=\"fuerza\">\n";
        $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
        foreach($tipos_fuerza as $indice => $valor)
        {
          if($valor['descripcion']==$datos[0]['desc_tipo_fuerza'])
            $sel_tf = "selected";
          else
            $sel_tf = "";
          $html .= "        <option value=\"".$valor['fuerza_id']."\" ".$sel_tf.">".$valor['descripcion']."</option>\n";
        }
        $html .= "        </select>\n";
        $html .= "      </div>\n";
        $html .= "    </td>\n";
      }else{
        $html .= "    <td class=\"modulo_list_claro\">\n";
        $html .= "      <div id=\"descFuerza\">\n";
        $html .= "        <select class=\"select\" name=\"fuerza\" disabled>\n";
        $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
        foreach($tipos_fuerza as $indice => $valor)
        {
          $html .= "        <option value=\"".$valor['fuerza_id']."\">".$valor['descripcion']."</option>\n";
        }
        $html .= "        </select>\n";
        $html .= "      </div>\n";
        $html .= "    </td>\n";
      }      
      $html .= "      <td class=\"formulacion_table_list\">Categoria:\n";
      $html .= "      </td>\n";
      if($militar[0]['clasifi_finaci_id']!="")
      {
        $html .= "    <td class=\"modulo_list_claro\">\n";
        $html .= "      <div id=\"descCate\">\n";
        $html .= "        <select class=\"select\" name=\"categoria\" onchange=\"ValidarCategoria()\">\n";
        $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
        foreach($estado_fuerza as $indice => $valor)
        {
          if($valor['categoria']==$datos[0]['categoria'])
            $sel_ef = "selected";
          else
            $sel_ef = "";
          $html .= "        <option value=\"".$valor['estado_fuerza_id']."\" ".$sel_ef.">".$valor['categoria']."</option>\n";
        }
        $html .= "        </select>\n";
        $html .= "      </div>\n";
        $html .= "    </td>\n";
      }else{
        $html .= "    <td class=\"modulo_list_claro\">\n";
        $html .= "      <div id=\"descCate\">\n";
        $html .= "        <select class=\"select\" name=\"categoria\" onchange=\"ValidarCategoria()\" disabled>\n";
        $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
        foreach($estado_fuerza as $indice => $valor)
        {
          $html .= "        <option value=\"".$valor['estado_fuerza_id']."\">".$valor['categoria']."</option>\n";
        }
        $html .= "        </select>\n";
        $html .= "      </div>\n";
        $html .= "    </td>\n";
      }
      
      $html .= "      <td class=\"formulacion_table_list\">Grado:\n";
      $html .= "      </td>\n";
      if($militar[0]['clasifi_finaci_id']!="")
      {
        $grado_categ = $mdl->ConsultarGradoCategoria($datos[0]['estado_fuerza_id']);
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <div id=\"descGrado\">\n";
        $html .= "          <select class=\"select\" name=\"grado\" id=\"grado\" onchange=\"ValidarGrado()\">\n";
        $html .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
        foreach($grado_categ as $indice => $valor)
        {
          if($valor['descripcion']==$datos[0]['desc_tipo_grado'])
            $sel_gc = "selected";
          else
            $sel_gc = "";
          $html .= "          <option value=\"".$valor['grado_id']."\" ".$sel_gc.">".$valor['descripcion']."</option>\n";
        }  
        $html .= "          </select>\n";
        $html .= "        </div>\n";
        $html .= "      </td>\n";
      }else{
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <div id=\"descGrado\"><input type=\"hidden\" name=\"grado\" id=\"grado\" value=\"-1\"></div>\n";
        $html .= "      </td>\n";
      }
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Clasif. Financiera:\n";
      $html .= "      </td>\n";
      if($militar[0]['clasifi_finaci_id']!="")
      {
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
        $html .= "        <div id=\"clasiFinanciera\">".$datos[0]['desc_clasi_finan']."</div>\n";
        $html .= "        <input type=\"hidden\" name=\"clasificacion\" id=\"clasificacion\" value=\"".$militar[0]['clasifi_finaci_id']."\">\n";
        $html .= "      </td>\n";
      }else{
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
        $html .= "        <div id=\"clasiFinanciera\">\n";
        $html .= "          <input type=\"hidden\" name=\"clasificacion\" id=\"clasificacion\" value=\"-1\">\n";
        $html .= "        </div>\n";
        $html .= "      </td>\n";
      }
      if($datos[0]['fecha_nacimiento'])
      {
        $fn = explode("-",$datos[0]['fecha_nacimiento']);
        if(sizeof($fn)==3) $fNac=$fn[2]."/".$fn[1]."/".$fn[0];
      }
      $html .= "      <td class=\"formulacion_table_list\">Fecha Nacimiento:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"fechaNacimiento\" value=\"".$fNac."\" maxlength=\"12\" onkeypress=\"return acceptDate(event)\" size=\"9%\">\n";
      $html .= "        <a title=\"Ver Calendario\" href=\"javascript:Mostrar_Campo(document.formFichaDonante.fechaNacimiento, 'fechaNacimiento')\" class=\"label_error\">\n";
      $html .= "          <img src=\"".GetThemePath()."/images/calendario/calendario.png\" border=\"0\">\n";
      $html .= "        </a>\n";
      $html .= "        <label class=\"label\">[dd/mm/aaaa]</label>\n";
      $html .= "        <div id=\"calendario_pxCampo\" class=\"calendario_px\"></div>\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"calcularEdad\" value=\"calcular\" onclick=\"CalcularEdad()\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $pct = AutoCarga::factory('Pacientes', '', 'app', 'DatosPaciente');
      $zona = GetVarConfigAplication('DefaultZona');
      $pais = GetVarConfigAplication('DefaultPais');
      $dpto = GetVarConfigAplication('DefaultDpto');
      $mpio = GetVarConfigAplication('DefaultMpio');
      $html .= "    <input type=\"hidden\" name=\"zona\" value=\"".$zona."\">\n";
      $html .= "    <input type=\"hidden\" name=\"pais\" value=\"".$pais."\">\n";
      $html .= "    <input type=\"hidden\" name=\"dpto\" value=\"".$dpto."\">\n";
      $html .= "    <input type=\"hidden\" name=\"mpio\" value=\"".$mpio."\">\n";
      $html .= "    <input type=\"hidden\" name=\"comuna\" value=\"\">\n";
      $NomPais = $pct->ObtenerNombrePais($pais);
      $NomDpto = $pct->ObtenerNombreDepartamento($pais, $dpto);
      $NomMpio = $pct->ObtenerNombreCiudad($pais, $dpto, $mpio);
      $url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$pais."&dept=".$dpto."&mpio=".$mpio."&forma=formFichaDonante ";
      $html .= "    <input type=\"hidden\" name=\"nomPais\" value=\"".$NomPais."\">\n";
      $html .= "    <input type=\"hidden\" name=\"nomDpto\" value=\"".$NomDpto."\">\n";
      $html .= "    <input type=\"hidden\" name=\"nomMpio\" value=\"".$NomMpio."\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Lugar de Nacimiento:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <div id=\"lugarNaci\"><label id=\"ubicacion\">".$lugar_naci[0]['desc_naci_pais']." - ".$lugar_naci[0]['desc_naci_dpto']." - ".$lugar_naci[0]['desc_naci_mpio']."</label></div>\n";
      $html .= "        <label id=\"tipo_comuna\"></label>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscarLocalidad\" value=\"Buscar Localidad\" target=\"localidad\" onclick=\"window.open('".$url."','localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Edad:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"hidden\" id=\"edad_oc\" name=\"edad_oc\" value=\"\">\n";
      $html .= "        <div id=\"edad_c\">".$datos[0]['edad']."</div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";     
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Sexo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"sexo\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";      
      foreach($tipos_sexo as $indice => $valor)
      {
        if($valor['descripcion']==$datos[0]['desc_sexo'])
          $sel_ts = "selected";
        else
          $sel_ts = "";
        $html .= "        <option value=\"".$valor['sexo_id']."\" ".$sel_ts.">".$valor['descripcion']."</option>\n";        
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Estado Civil:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"estadoCivil\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($estado_civil as $indice => $valor)
      {
        if($valor['descripcion']==$datos[0]['desc_est_civil'])
          $sel_ec = "selected";
        else
          $sel_ec = "";
        $html .= "      <option value=\"".$valor['tipo_estado_civil_id']."\" ".$sel_ec.">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">e-mail:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"email\" size=\"15%\" value=\"".$datos[0]['email']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      //$this->SetJavaScripts("Ocupaciones");
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Ocupacion:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"4\">\n";
      $html .= "        <input type=\"hidden\" name=\"ocupacion_id\" value=\"".$datos[0]['ocupacion_id']."\">\n";
      $html .= "        <textarea class=\"textarea\" rows=\"1\" name=\"descripcion_ocupacion\" readonly style=\"width:100%;background:#FFFFFF\">".$datos[0]['desc_ocupacion']." </textarea>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" value=\"Ocupacion\" name=\"ocupacion\" onclick=\"javascript:Ocupaciones('formFichaDonante','')\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <input type=\"hidden\" name=\"paisM3\" value=\"".$pais."\">\n";
      $html .= "    <input type=\"hidden\" name=\"dptoM3\" value=\"".$dpto."\">\n";
      $html .= "    <input type=\"hidden\" name=\"mpioM3\" value=\"".$mpio."\">\n";
      $html .= "    <input type=\"hidden\" name=\"comunaM3\" value=\"\">\n";
      $url1 = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$pais."&dept=".$dpto."&mpio=".$mpio."&forma=formFichaDonante&nombre_campos[ubicacion]=ubicacion1 ";
           
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Lugar Domicilio:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <div id=\"lugarDomi\"><label id=\"ubicacion1\">".$lugar_domi[0]['desc_domi_pais']." - ".$lugar_domi[0]['desc_domi_dpto']." - ".$lugar_domi[0]['desc_domi_mpio']."</label></div>\n";
      $html .= "        <label id=\"tipo_comunaM3\"></label>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscarDomicilio\" value=\"Buscar Domicilio\" target=\"localidad\" onclick=\"window.open('".$url1."', 'localidad', 'toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tel. Domicilio:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"telDomicilio\" size=\"15%\" value=\"".$datos[0]['tel_domicilio']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Dir. Domicilio:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"dirDomicilio\" size=\"50%\" value=\"".$datos[0]['dir_domicilio']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">No. Celular:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"noCelular\" size=\"15%\" value=\"".$datos[0]['no_celular']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Dir. Trabajo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"dirTrabajo\" size=\"50%\" value=\"".$datos[0]['dir_trabajo']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tel. Trabajo\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"telTrabajo\" size=\"15%\" value=\"".$datos[0]['tel_trabajo']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      
      if($estado_dc[0]['estado_donante_id']!="")
      {
        $html .= "  <tr>\n";
        $html .= "    <td class=\"formulacion_table_list\">Estado Donante:\n";      
        $html .= "    </td>\n";  
        $html .= "    <td class=\"modulo_list_claro\" colspan=\"2\">".$estado_dc[0]['desc_est_donante']."\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"formulacion_table_list\">Causas:\n";      
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\" colspan=\"2\">".$estado_dc[0]['desc_cau_donante']."\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }else{
        $html .= "  <tr>\n";
        $html .= "    <td class=\"formulacion_table_list\">Estado Donante:\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\" colspan=\"5\">PENDIENTE POR REALIZAR EL REGISTRO DE DONACIONES\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "    <tr>\n";      
      $html .= "      <td align=\"center\" colspan=\"6\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"ValidarDatos()\">\n";
      $html .= "      </td>\n";
      $html .= "      <input type=\"hidden\" name=\"tipoIngreso\" value=\"registrado\">\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $div_td  = "<select class=\"select\" name=\"tipoDonador\" onchange=\"ValidarTipoDonador()\">\n";
      $div_td .= "  <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($tipos_donante as $indice => $valor)
      { 
        if($valor['descripcion']==$datos[0]['desc_tipo_donante'])
          $sel_td="selected";
        else
          $sel_td="";
        $div_td .= "<option value=\"".$valor['tipo_donante_id']."\" ".$sel_td.">".$valor['descripcion']."</option>\n";
      }
      $div_td .= "</select>\n";
      if($datos[0]['desc_convenio']=="")
        $dis_co = "disabled";
      else
        $dis_co = "";
      $div_co  = "<select class=\"select\" name=\"convenio\" ".$dis_co.">\n";
      $div_co .= "  <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($convenios as $indice => $valor)
      { 
        if($valor['descripcion']==$datos[0]['desc_convenio'])
          $sel_co = "selected";
        else
          $sel_co = "";
        $div_co .= "<option value=\"".$valor['convenio_id']."\" ".$sel_co.">".$valor['descripcion']."</option>\n";
      }
      $div_co .= "</select>\n";
      //$objResponse->assign("noId", "disabled", true);
      //$objResponse->assign("tipoId", "disabled", true);
      $objResponse->assign("divTipoDonador", "innerHTML", $div_td);
      $objResponse->assign("divConvenio", "innerHTML", $div_co);
    }else{
      
      $hc = $mdl->ConsultarDonanteHC($no_id, $tipo_id);
      if(count($hc)!=0)
      {
        $militarP = $mdl->ConsultarMilitarPaci($no_id, $tipo_id);
        $don_pac = $mdl->ConsultarDonantePaciente($no_id, $tipo_id, $militarP);
        $lugar_domip = $mdl->ConsultarLugarDomicilioP($no_id, $tipo_id);
        $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"100%\">\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\" width=\"16.6%\">Historia Clinica:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" width=\"33.2%\" colspan=\"2\">".$hc[0]['historia_numero']."\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"formulacion_table_list\" width=\"16.6%\">Apellido Paterno:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" width=\"33.2%\" colspan=\"2\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"apellidoPaterno\" size=\"15%\" value=\"".$don_pac[0]['primer_apellido']."\">\n";
        $html .= "      </td>\n";     
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\">Apellido Materno:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"apellidoMaterno\" size=\"15%\" value=\"".$don_pac[0]['segundo_apellido']."\">\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"formulacion_table_list\">Primer Nombre:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"primerNombre\" size=\"15%\" value=\"".$don_pac[0]['primer_nombre']."\">\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"formulacion_table_list\">Segundo Nombre:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"segundoNombre\" size=\"15%\" value=\"".$don_pac[0]['segundo_nombre']."\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\">Fuerza:\n";
        $html .= "      </td>\n";
        if($militarP[0]['clasifi_finaci_id']!="")
        {
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <div id=\"descFuerza\">\n";
          $html .= "          <select class=\"select\" name=\"fuerza\">\n";
          $html .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($tipos_fuerza as $indice => $valor)
          {
            if($valor['descripcion']==$don_pac[0]['desc_tipo_fuerza'])
              $sel_tf = "selected";
            else
              $sel_tf = "";
            $html .= "          <option value=\"".$valor['fuerza_id']."\" ".$sel_tf.">".$valor['descripcion']."</option>\n";
          }
          $html .= "          </select>\n";
          $html .= "        </div>\n";
          $html .= "      </td>\n";
        }else{
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <div id=\"descFuerza\">\n";
          $html .= "          <select class=\"select\" name=\"fuerza\" disabled>\n";
          $html .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($tipos_fuerza as $indice => $valor)
          {
            $html .= "          <option value=\"".$valor['fuerza_id']."\">".$valor['descripcion']."</option>\n";
          }
          $html .= "          </select>\n";
          $html .= "        </div>\n";
          $html .= "      </td>\n";
        }      
        $html .= "      <td class=\"formulacion_table_list\">Categoria:\n";
        $html .= "      </td>\n";
        if($militarP[0]['clasifi_finaci_id']!="")
        {
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <div id=\"descCate\">\n";
          $html .= "          <select class=\"select\" name=\"categoria\" onchange=\"ValidarCategoria()\">\n";
          $html .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($estado_fuerza as $indice => $valor)
          {
            if($valor['categoria']==$don_pac[0]['categoria'])
              $sel_ef = "selected";
            else
              $sel_ef = "";
            $html .= "          <option value=\"".$valor['estado_fuerza_id']."\" ".$sel_ef.">".$valor['categoria']."</option>\n";
          }
          $html .= "          </select>\n";
          $html .= "        </div>\n";
          $html .= "      </td>\n";
        }else{
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <div id=\"descCate\">\n";
          $html .= "          <select class=\"select\" name=\"categoria\" onchange=\"ValidarCategoria()\" disabled>\n";
          $html .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($estado_fuerza as $indice => $valor)
          {
            $html .= "          <option value=\"".$valor['estado_fuerza_id']."\">".$valor['categoria']."</option>\n";
          }
          $html .= "          </select>\n";
          $html .= "        </div>\n";
          $html .= "      </td>\n";
        }
        
        $html .= "      <td class=\"formulacion_table_list\">Grado:\n";
        $html .= "      </td>\n";
        if($militarP[0]['clasifi_finaci_id']!="")
        {
          $grado_categ = $mdl->ConsultarGradoCategoria($don_pac[0]['estado_fuerza_id']);
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <div id=\"descGrado\">\n";  
          $html .= "          <select class=\"select\" name=\"grado\" onchange=\"ValidarGrado()\">\n";
          $html .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($grado_categ as $indice => $valor)
          {
            if($valor['descripcion']==$don_pac[0]['desc_tipo_grado'])
              $sel_gc = "selected";
            else
              $sel_gc = "";
            $html .= "          <option value=\"".$valor['grado_id']."\" ".$sel_gc.">".$valor['descripcion']."</option>\n";
          }  
          $html .= "          </select>\n";
          $html .= "        </div>\n";
          $html .= "      </td>\n";
        }else{
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <div id=\"descGrado\">\n";
          $html .= "          <input type=\"hidden\" name=\"grado\" id=\"grado\" value=\"-1\">\n";
          $html .= "        </div>\n";
          $html .= "      </td>\n";
        }
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\">Clasif. Financiera:\n";
        $html .= "      </td>\n";
        if($militarP[0]['clasifi_finaci_id']!="")
        {
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <div id=\"clasiFinanciera\">".$don_pac[0]['desc_clasi_finan']."</div>\n";
          $html .= "        <input type=\"hidden\" name=\"clasificacion\" id=\"clasificacion\" value=\"".$militarP[0]['clasifi_finaci_id']."\">\n";
          $html .= "      </td>\n";
        }else{
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <div id=\"clasiFinanciera\">\n";
          $html .= "          <input type=\"hidden\" name=\"clasificacion\" id=\"clasificacion\" value=\"-1\">\n";
          $html .= "        </div>\n";
          $html .= "      </td>\n";
        }
        if($don_pac[0]['fecha_nacimiento'])
        {
          $fn = explode("-",$don_pac[0]['fecha_nacimiento']);
          if(sizeof($fn)==3) $fNac=$fn[2]."/".$fn[1]."/".$fn[0];
        }
        $html .= "      <td class=\"formulacion_table_list\">Fecha Nacimiento:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"fechaNacimiento\" value=\"".$fNac."\" maxlength=\"12\" onkeypress=\"return acceptDate(event)\" size=\"9%\">\n";
        $html .= "        <a title=\"Ver Calendario\" href=\"javascript:Mostrar_Campo(document.formFichaDonante.fechaNacimiento, 'fechaNacimiento')\" class=\"label_error\">\n";
        $html .= "          <img src=\"".GetThemePath()."/images/calendario/calendario.png\" border=\"0\">\n";
        $html .= "        </a>\n";
        $html .= "        <label class=\"label\">[dd/mm/aaaa]</label>\n";
        $html .= "        <div id=\"calendario_pxCampo\" class=\"calendario_px\"></div>\n";
        $html .= "        <input class=\"input-submit\" type=\"button\" name=\"calcularEdad\" value=\"calcular\" onclick=\"CalcularEdad()\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $pct = AutoCarga::factory('Pacientes', '', 'app', 'DatosPaciente');
        $zona = GetVarConfigAplication('DefaultZona');
        $pais = GetVarConfigAplication('DefaultPais');
        $dpto = GetVarConfigAplication('DefaultDpto');
        $mpio = GetVarConfigAplication('DefaultMpio');
        $html .= "    <input type=\"hidden\" name=\"zona\" value=\"".$zona."\">\n";
        $html .= "    <input type=\"hidden\" name=\"pais\" value=\"".$pais."\">\n";
        $html .= "    <input type=\"hidden\" name=\"dpto\" value=\"".$dpto."\">\n";
        $html .= "    <input type=\"hidden\" name=\"mpio\" value=\"".$mpio."\">\n";
        $html .= "    <input type=\"hidden\" name=\"comuna\" value=\"\">\n";
        $NomPais = $pct->ObtenerNombrePais($pais);
        $NomDpto = $pct->ObtenerNombreDepartamento($pais, $dpto);
        $NomMpio = $pct->ObtenerNombreCiudad($pais, $dpto, $mpio);
        $url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$pais."&dept=".$dpto."&mpio=".$mpio."&forma=formFichaDonante ";
        $html .= "    <input type=\"hidden\" name=\"nomPais\" value=\"".$NomPais."\">\n";
        $html .= "    <input type=\"hidden\" name=\"nomDpto\" value=\"".$NomDpto."\">\n";
        $html .= "    <input type=\"hidden\" name=\"nomMpio\" value=\"".$NomMpio."\">\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\">Lugar de Nacimiento:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
        $html .= "        <div id=\"lugarNaci\"><label id=\"ubicacion\">".$NomPais." - ".$NomDpto." - ".$NomMpio."</label></div>\n";
        $html .= "        <label id=\"tipo_comuna\"></label>\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
        $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscarLocalidad\" value=\"Buscar Localidad\" target=\"localidad\" onclick=\"window.open('".$url."','localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\">\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"formulacion_table_list\">Edad:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input type=\"hidden\" id=\"edad_oc\" name=\"edad_oc\" value=\"\">\n";
        $html .= "        <div id=\"edad_c\"></div>\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";     
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\">Sexo:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <select class=\"select\" name=\"sexo\">\n";
        $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";      
        foreach($tipos_sexo as $indice => $valor)
        {
          if($valor['descripcion']==$don_pac[0]['desc_sexo'])
            $sel_ts = "selected";
          else
            $sel_ts = "";
          $html .= "        <option value=\"".$valor['sexo_id']."\" ".$sel_ts.">".$valor['descripcion']."</option>\n";        
        }
        $html .= "        </select>\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"formulacion_table_list\">Estado Civil:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <select class=\"select\" name=\"estadoCivil\">\n";
        $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
        foreach($estado_civil as $indice => $valor)
        {
          if($valor['descripcion']==$don_pac[0]['desc_est_civil'])
            $sel_ec = "selected";
          else
            $sel_ec = "";
          $html .= "      <option value=\"".$valor['tipo_estado_civil_id']."\" ".$sel_ec.">".$valor['descripcion']."</option>\n";
        }
        $html .= "        </select>\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"formulacion_table_list\">e-mail:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"email\" size=\"15%\" value=\"\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\">Ocupacion:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"4\">\n";
        $html .= "        <input type=\"hidden\" name=\"ocupacion_id\" value=\"".$don_pac[0]['ocupacion_id']."\">\n";
        $html .= "        <textarea class=\"textarea\" rows=\"1\" name=\"descripcion_ocupacion\" readonly style=\"width:100%;background:#FFFFFF\">".$don_pac[0]['desc_ocupacion']."</textarea>\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
        $html .= "        <input class=\"input-submit\" type=\"button\" value=\"Ocupacion\" name=\"ocupacion\" onclick=\"javascript:Ocupaciones('formFichaDonante','')\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <input type=\"hidden\" name=\"paisM3\" value=\"".$pais."\">\n";
        $html .= "    <input type=\"hidden\" name=\"dptoM3\" value=\"".$dpto."\">\n";
        $html .= "    <input type=\"hidden\" name=\"mpioM3\" value=\"".$mpio."\">\n";
        $html .= "    <input type=\"hidden\" name=\"comunaM3\" value=\"\">\n";
        $url1 = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$pais."&dept=".$dpto."&mpio=".$mpio."&forma=formFichaDonante&nombre_campos[ubicacion]=ubicacion1 ";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\">Lugar Domicilio:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
        $html .= "        <div id=\"lugarDomi\"><label id=\"ubicacion1\">".$don_pac[0]['desc_domi_pais']." - ".$don_pac[0]['desc_domi_dpto']." - ".$don_pac[0]['desc_domi_mpio']."</label></div>\n";
        $html .= "        <label id=\"tipo_comunaM3\"></label>\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
        $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscarDomicilio\" value=\"Buscar Domicilio\" target=\"localidad\" onclick=\"window.open('".$url1."', 'localidad', 'toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\">\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"formulacion_table_list\">Tel. Domicilio:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"telDomicilio\" size=\"15%\" value=\"".$don_pac[0]['residencia_telefono']."\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\">Dir. Domicilio:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"dirDomicilio\" size=\"50%\" value=\"".$don_pac[0]['residencia_direccion']."\">\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"formulacion_table_list\">No. Celular:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"noCelular\" size=\"15%\" value=\"\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td class=\"formulacion_table_list\">Dir. Trabajo:\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"dirTrabajo\" size=\"50%\" value=\"\">\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"formulacion_table_list\">Tel. Trabajo\n";
        $html .= "      </td>\n";
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input class=\"input-text\" type=\"text\" name=\"telTrabajo\" size=\"15%\" value=\"\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "      <td align=\"center\" colspan=\"6\">\n";
        $html .= "        <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"ValidarDatos()\">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <input type=\"hidden\" name=\"tipoIngreso\" value=\"nuevo\">\n";
        $html .= "  </table>\n";
        $div_td  = "          <select class=\"select\" name=\"tipoDonador\" onchange=\"ValidarTipoDonador()\">\n";
        $div_td .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
        foreach($tipos_donante as $indice => $valor)
        {
          $div_td .= "          <option value=\"".$valor['tipo_donante_id']."\">".$valor['descripcion']."</option>\n";
        }
        $div_td .= "          </select>\n";
        
        $div_c  = "          <select class=\"select\" name=\"convenio\" disabled>\n";
        $div_c .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
        foreach($convenios as $indice => $valor)
        {
          $div_c .= "          <option value=\"".$valor['convenio_id']."\">".$valor['descripcion']."</option>\n";
        }
        $div_c .= "          </select>\n";
        
        $objResponse->assign("divTipoDonador", "innerHTML", $div_td);
        $objResponse->assign("divConvenio", "innerHTML", $div_c);
      }else{
        $planId = 541;
        
        $request1['tipo_id_paciente'] = $tipo_id;
        $request1['paciente_id'] = $no_id;
        $request1['plan_id'] = $planId;
        
        $inp = AutoCarga::factory('InformacionPacientes');
        $afiliados = $inp->ValidarInformacion($request1, "","InformacionBaseDatos");
        if(!empty($afiliados))
        {
          $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"100%\">\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\" width=\"17%\">Apellido Paterno:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"apellidoPaterno\" size=\"25%\" value=\"".$afiliados['primer_apellido']."\">\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\" width=\"17%\">Apellido Materno:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"apellidoMaterno\" size=\"25%\" value=\"".$afiliados['segundo_apellido']."\">\n";
          $html .= "      </td>\n";     
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Primer Nombre:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"primerNombre\" size=\"25%\" value=\"".$afiliados['primer_nombre']."\">\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">Segundo Nombre:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"segundoNombre\" size=\"25%\" value=\"".$afiliados['segundo_nombre']."\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Fuerza:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <div id=\"descFuerza\">\n";
          $html .= "          <select class=\"select\" name=\"fuerza\" disabled>\n";
          $html .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($tipos_fuerza as $indice => $valor)
          {
            $html .= "          <option value=\"".$valor['fuerza_id']."\">".$valor['descripcion']."</option>\n";
          }
          $html .= "          </select>\n";
          $html .= "        </div>\n";
          $html .= "      </td>\n";               
          $html .= "      <td class=\"formulacion_table_list\">Categoria:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <div id=\"descCate\">\n";
          $html .= "          <select class=\"select\" name=\"categoria\" onchange=\"ValidarCategoria()\" disabled>\n";
          $html .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($estado_fuerza as $indice => $valor)
          {
            $html .= "          <option value=\"".$valor['estado_fuerza_id']."\">".$valor['categoria']."</option>\n";
          }
          $html .= "          </select>\n";
          $html .= "        </div>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">Grado:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <div id=\"descGrado\">\n";
          $html .= "          <input type=\"hidden\" name=\"grado\" id=\"grado\" value=\"-1\">\n";
          $html .= "        </div>";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Clasif. Financiera:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <div id=\"clasiFinanciera\">\n";
          $html .= "          <input type=\"hidden\" name=\"clasificacion\" id=\"clasificacion\" value=\"-1\">\n";
          $html .= "        </div>\n";
          $html .= "      </td>\n";
          
          if($afiliados['fecha_nacimiento'])
          {
            $fn = explode("-",$afiliados['fecha_nacimiento']);
            if(sizeof($fn)==3) $fNac=$fn[2]."/".$fn[1]."/".$fn[0];
          }
          $html .= "      <td class=\"formulacion_table_list\">Fecha Nacimiento:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"fechaNacimiento\" value=\"".$fNac."\" maxlength=\"12\" onkeypress=\"return acceptDate(event)\" size=\"9%\">\n";
          $html .= "        <a title=\"Ver Calendario\" href=\"javascript:Mostrar_Campo(document.formFichaDonante.fechaNacimiento, 'fechaNacimiento')\" class=\"label_error\">\n";
          $html .= "          <img src=\"".GetThemePath()."/images/calendario/calendario.png\" border=\"0\">\n";
          $html .= "        </a>\n";
          $html .= "        <label class=\"label\">[dd/mm/aaaa]</label>\n";
          $html .= "        <div id=\"calendario_pxCampo\" class=\"calendario_px\"></div>\n";
          $html .= "        <input class=\"input-submit\" type=\"button\" name=\"calcularEdad\" value=\"calcular\" onclick=\"CalcularEdad()\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $pct = AutoCarga::factory('Pacientes', '', 'app', 'DatosPaciente');
          $zona = GetVarConfigAplication('DefaultZona');
          $pais = GetVarConfigAplication('DefaultPais');
          $dpto = GetVarConfigAplication('DefaultDpto');
          $mpio = GetVarConfigAplication('DefaultMpio');
          $html .= "    <input type=\"hidden\" name=\"zona\" value=\"".$zona."\">\n";
          $html .= "    <input type=\"hidden\" name=\"pais\" value=\"".$pais."\">\n";
          $html .= "    <input type=\"hidden\" name=\"dpto\" value=\"".$dpto."\">\n";
          $html .= "    <input type=\"hidden\" name=\"mpio\" value=\"".$mpio."\">\n";
          $html .= "    <input type=\"hidden\" name=\"comuna\" value=\"\">\n";
          $NomPais = $pct->ObtenerNombrePais($pais);
          $NomDpto = $pct->ObtenerNombreDepartamento($pais, $dpto);
          $NomMpio = $pct->ObtenerNombreCiudad($pais, $dpto, $mpio);
          $url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$pais."&dept=".$dpto."&mpio=".$mpio."&forma=formFichaDonante ";
          $html .= "    <input type=\"hidden\" name=\"nomPais\" value=\"".$NomPais."\">\n";
          $html .= "    <input type=\"hidden\" name=\"nomDpto\" value=\"".$NomDpto."\">\n";
          $html .= "    <input type=\"hidden\" name=\"nomMpio\" value=\"".$NomMpio."\">\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Lugar de Nacimiento:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <div id=\"lugarNaci\"><label id=\"ubicacion\">".$NomPais." - ".$NomDpto." - ".$NomMpio."</label></div>\n";
          $html .= "        <label id=\"tipo_comuna\"></label>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
          $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscarLocalidad\" value=\"Buscar Localidad\" target=\"localidad\" onclick=\"window.open('".$url."','localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\">\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">Edad:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <input type=\"hidden\" id=\"edad_oc\" name=\"edad_oc\" value=\"\">\n";
          $html .= "        <div id=\"edad_c\"></div>\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";     
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Sexo:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <select class=\"select\" name=\"sexo\">\n";
          $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";      
          foreach($tipos_sexo as $indice => $valor)
          {
            if($valor['sexo_id']==$afiliados['sexo_id'])
              $sel_ts = "selected";
            else
              $sel_ts = "";
            $html .= "        <option value=\"".$valor['sexo_id']."\" ".$sel_ts.">".$valor['descripcion']."</option>\n";        
          }
          $html .= "        </select>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">Estado Civil:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <select class=\"select\" name=\"estadoCivil\">\n";
          $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($estado_civil as $indice => $valor)
          {
            if($valor['tipo_estado_civil_id']==$afiliados['tipo_estado_civil_id'])
              $sel_ec = "selected";
            else
              $sel_ec = "";
            $html .= "      <option value=\"".$valor['tipo_estado_civil_id']."\" ".$sel_ec.">".$valor['descripcion']."</option>\n";
          }
          $html .= "        </select>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">e-mail:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"email\" size=\"15%\" value=\"\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Ocupacion:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"4\">\n";
          $html .= "        <input type=\"hidden\" name=\"ocupacion_id\" value=\"\">\n";
          $html .= "        <textarea class=\"textarea\" rows=\"1\" name=\"descripcion_ocupacion\" readonly style=\"width:100%;background:#FFFFFF\"></textarea>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
          $html .= "        <input class=\"input-submit\" type=\"button\" value=\"Ocupacion\" name=\"ocupacion\" onclick=\"javascript:Ocupaciones('formFichaDonante','')\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <input type=\"hidden\" name=\"paisM3\" value=\"".$pais."\">\n";
          $html .= "    <input type=\"hidden\" name=\"dptoM3\" value=\"".$dpto."\">\n";
          $html .= "    <input type=\"hidden\" name=\"mpioM3\" value=\"".$mpio."\">\n";
          $html .= "    <input type=\"hidden\" name=\"comunaM3\" value=\"\">\n";
          $url1 = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$pais."&dept=".$dpto."&mpio=".$mpio."&forma=formFichaDonante&nombre_campos[ubicacion]=ubicacion1 ";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Lugar Domicilio:\n";
          $html .= "      </td>\n";
          $pct = AutoCarga::factory('Pacientes', '', 'app', 'DatosPaciente');
          $nomPais = $pct->ObtenerNombrePais($afiliados['domi_pais_id']);
          $nomDpto = $pct->ObtenerNombreDepartamento($afiliados['domi_pais_id'], $afiliados['domi_dpto_id']);
          $nomMpio = $pct->ObtenerNombreCiudad($afiliados['domi_pais_id'], $afiliados['domi_dpto_id'], $afiliados['domi_mpio_id']);
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <div id=\"lugarDomi\"><label id=\"ubicacion1\">".$nomPais." - ".$nomDpto." - ".$nomMpio."</label></div>\n";
          $html .= "        <label id=\"tipo_comunaM3\"></label>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
          $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscarDomicilio\" value=\"Buscar Domicilio\" target=\"localidad\" onclick=\"window.open('".$url1."', 'localidad', 'toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\">\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">Tel. Domicilio:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"telDomicilio\" size=\"15%\" value=\"".$afiliados['tel_domicilio']."\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Dir. Domicilio:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"dirDomicilio\" size=\"50%\" value=\"".$afiliados['dir_domicilio']."\">\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">No. Celular:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"noCelular\" size=\"15%\" value=\"".$afiliados['no_celular']."\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Dir. Trabajo:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"dirTrabajo\" size=\"50%\" value=\"\">\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">Tel. Trabajo\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"telTrabajo\" size=\"15%\" value=\"\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td align=\"center\" colspan=\"6\">\n";
          $html .= "        <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"ValidarDatos()\">\n";
          $html .= "      </td>\n";
          $html .= "      <input type=\"hidden\" name=\"tipoIngreso\" value=\"nuevo\">\n";
          $html .= "    </tr>\n";
          $html .= "  </table>\n";
          
          $div_td  = "          <select class=\"select\" name=\"tipoDonador\" onchange=\"ValidarTipoDonador()\">\n";
          $div_td .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($tipos_donante as $indice => $valor)
          {
            $div_td .= "          <option value=\"".$valor['tipo_donante_id']."\">".$valor['descripcion']."</option>\n";
          }
          $div_td .= "          </select>\n";
          
          $div_c  = "          <select class=\"select\" name=\"convenio\" disabled>\n";
          $div_c .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($convenios as $indice => $valor)
          {
            $div_c .= "          <option value=\"".$valor['convenio_id']."\">".$valor['descripcion']."</option>\n";
          }
          $div_c .= "          </select>\n";
          
          $objResponse->assign("divTipoDonador", "innerHTML", $div_td);
          $objResponse->assign("divConvenio", "innerHTML", $div_c);
        }else{
          $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"100%\">\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\" width=\"17%\">Apellido Paterno:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"apellidoPaterno\" size=\"25%\">\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\" width=\"17%\">Apellido Materno:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"apellidoMaterno\" size=\"25%\">\n";
          $html .= "      </td>\n";          
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\" width=\"17%\">Primer Nombre:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"primerNombre\" size=\"25%\">\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\" width=\"17%\">Segundo Nombre:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"segundoNombre\" size=\"25%\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $pct = AutoCarga::factory('Pacientes', '', 'app', 'DatosPaciente');
          $zona = GetVarConfigAplication('DefaultZona');
          $pais = GetVarConfigAplication('DefaultPais');
          $dpto = GetVarConfigAplication('DefaultDpto');
          $mpio = GetVarConfigAplication('DefaultMpio');
          $html .= "    <input type=\"hidden\" name=\"zona\" value=\"".$zona."\">\n";
          $html .= "    <input type=\"hidden\" name=\"pais\" value=\"".$pais."\">\n";
          $html .= "    <input type=\"hidden\" name=\"dpto\" value=\"".$dpto."\">\n";
          $html .= "    <input type=\"hidden\" name=\"mpio\" value=\"".$mpio."\">\n";
          $html .= "    <input type=\"hidden\" name=\"comuna\" value=\"\">\n";
          $NomPais = $pct->ObtenerNombrePais($pais);
          $NomDpto = $pct->ObtenerNombreDepartamento($pais, $dpto);
          $NomMpio = $pct->ObtenerNombreCiudad($pais, $dpto, $mpio);
          $url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$pais."&dept=".$dpto."&mpio=".$mpio."&forma=formFichaDonante ";
          $html .= "    <input type=\"hidden\" name=\"nomPais\" value=\"".$NomPais."\">\n";
          $html .= "    <input type=\"hidden\" name=\"nomDpto\" value=\"".$NomDpto."\">\n";
          $html .= "    <input type=\"hidden\" name=\"nomMpio\" value=\"".$NomMpio."\">\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Fuerza:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <select class=\"select\" name=\"fuerza\" disabled>\n";
          $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($tipos_fuerza as $indice => $valor)
          {
            $html .= "        <option value=\"".$valor['fuerza_id']."\">".$valor['descripcion']."</option>\n";
          }
          $html .= "        </select>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">Categoria:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <select class=\"select\" name=\"categoria\" onchange=\"ValidarCategoria()\" disabled>\n";
          $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($estado_fuerza as $indice => $valor)
          {
            $html .= "        <option value=\"".$valor['estado_fuerza_id']."\">".$valor['categoria']."</option>\n";
          }
          $html .= "        </select>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">Grado:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <div id=\"descGrado\">\n";
          $html .= "          <input type=\"hidden\" name=\"grado\" id=\"grado\" value=\"-1\">\n";
          $html .= "        </div>\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Clasif. Financiera:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <div id=\"clasiFinanciera\">\n";
          $html .= "          <input type=\"hidden\" name=\"clasificacion\" id=\"clasificacion\" value=\"-1\">\n";
          $html .= "        </div>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">Fecha Nacimiento:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"fechaNacimiento\" onkeyPress=\"return acceptDate(event)\" size=\"9%\" maxlength=\"12\">\n";
          $html .= "        <a title=\"Ver Calendario\" href=\"javascript:Mostrar_Campo(document.formFichaDonante.fechaNacimiento, 'fechaNacimiento')\" class=\"label_error\">\n";
          $html .= "          <img src=\"".GetThemePath()."/images/calendario/calendario.png\" border=\"0\">\n";
          $html .= "        </a>\n";
          $html .= "        <label class=\"label\">[dd/mm/aaaa]</label>\n";
          $html .= "        <div id=\"calendario_pxCampo\" class=\"calendario_px\"></div>\n";
          $html .= "        <input class=\"input-submit\" type=\"button\" name=\"calcularEdad\" value=\"calcular\" onclick=\"CalcularEdad()\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Lugar de Nacimiento:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <label id=\"ubicacion\">".$NomPais." - ".$NomDpto." - ".$NomMpio."  </label>\n";
          $html .= "      - <label id=\"tipo_comuna\"></label>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
          $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscarLocalidad\" value=\"Buscar Localidad\" target=\"localidad\" onclick=\"window.open('".$url."','localidad','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\">\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">Edad:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <input type=\"hidden\" id=\"edad_oc\" name=\"edad_oc\" value=\"\">\n";
          $html .= "        <div id=\"edad_c\"></div>\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Sexo:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <select class=\"select\" name=\"sexo\">\n";
          $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($tipos_sexo as $indice => $valor)
          {
            $html .= "        <option value=\"".$valor['sexo_id']."\">".$valor['descripcion']."</option>\n";
          }
          $html .= "        </select>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">Estado Civil:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <select class=\"select\" name=\"estadoCivil\">\n";
          $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($estado_civil as $indice => $valor)
          {
            $html .= "        <option value=\"".$valor['tipo_estado_civil_id']."\">".$valor['descripcion']."</option>\n";
          }
          $html .= "        </select>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">e-mail:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"email\" size=\"15%\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Ocupacion:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"4\">\n";
          $html .= "        <input type=\"hidden\" name=\"ocupacion_id\" value=\"\">\n";
          $html .= "        <textarea class=\"textarea\" rows=\"1\" name=\"descripcion_ocupacion\" readonly style=\"width:100%;background:#FFFFFF\"></textarea>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
          $html .= "        <input class=\"input-submit\" type=\"button\" value=\"Ocupacion\" name=\"ocupacion\" onclick=\"javascript:Ocupaciones('formFichaDonante','')\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <input type=\"hidden\" name=\"paisM3\" value=\"".$pais."\">\n";
          $html .= "    <input type=\"hidden\" name=\"dptoM3\" value=\"".$dpto."\">\n";
          $html .= "    <input type=\"hidden\" name=\"mpioM3\" value=\"".$mpio."\">\n";
          $html .= "    <input type=\"hidden\" name=\"comunaM3\" value=\"\">\n";          
          $url1 = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=".$pais."&dept=".$dpto."&mpio=".$mpio."&forma=formFichaDonante&nombre_campos[ubicacion]=ubicacion1 ";          
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Lugar Domicilio:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
          $html .= "        <label id=\"ubicacion1\">".$NomPais." - ".$NomDpto." - ".$NomMpio."</label>\n";
          $html .= "        - <label id=\"tipo_comunaM3\"></label>\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
          $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscarDomicilio\" value=\"Buscar Domicilio\" target=\"localidad\" onclick=\"window.open('".$url1."', 'localidad', 'toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\">\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">Tel. Domicilio:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"telDomicilio\" size=\"15%\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Dir. Domicilio:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"dirDomicilio\" size=\"50%\">\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">No. Celular:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"noCelular\" size=\"15%\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td class=\"formulacion_table_list\">Dir. Trabajo:\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"dirTrabajo\" size=\"50%\">\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"formulacion_table_list\">Tel. Trabajo\n";
          $html .= "      </td>\n";
          $html .= "      <td class=\"modulo_list_claro\">\n";
          $html .= "        <input class=\"input-text\" type=\"text\" name=\"telTrabajo\" size=\"15%\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr>\n";
          $html .= "      <td align=\"center\" colspan=\"6\">\n";
          $html .= "        <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"ValidarDatos()\">\n";
          $html .= "      </td>\n";          
          $html .= "    </tr>\n";
          $html .= "    <input type=\"hidden\" name=\"tipoIngreso\" value=\"nuevo\">\n";
          $html .= "  </table>\n";
          
          $div_td  = "          <select class=\"select\" name=\"tipoDonador\" onchange=\"ValidarTipoDonador()\">\n";
          $div_td .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($tipos_donante as $indice => $valor)
          {
            $div_td .= "          <option value=\"".$valor['tipo_donante_id']."\">".$valor['descripcion']."</option>\n";
          }
          $div_td .= "          </select>\n";
          
          $div_c  = "          <select class=\"select\" name=\"convenio\" disabled>\n";
          $div_c .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
          foreach($convenios as $indice => $valor)
          {
            $div_c .= "          <option value=\"".$valor['convenio_id']."\">".$valor['descripcion']."</option>\n";
          }
          $div_c .= "          </select>\n";
          
          $objResponse->assign("divTipoDonador", "innerHTML", $div_td);
          $objResponse->assign("divConvenio", "innerHTML", $div_c);
        }
      }
    }
    
    $div_m  = "        <select class=\"select\" name=\"militar\" onchange=\"ValidarMilitar()\">\n";
    $div_m .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
    $div_m .= "          <option value=\"1\">SI</option>\n";
    $div_m .= "          <option value=\"2\">NO</option>\n";
    $div_m .= "        </select>\n";
    $clr = "";
    $objResponse->assign("error", "innerHTML", $clr);
    $objResponse->assign("divMilitar", "innerHTML", $div_m);
    $objResponse->assign("infoPaciente", "innerHTML", $html);
    
    return $objResponse;    
  }
  /**
  * Funcion que permite consultar la informacion registrada en la ficha del donante
  *
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return string $objResponse objeto de respuesta al formulario
  */
  function BuscarFichaDonante($form)
  {
    $objResponse = new xajaxResponse();
    $no_id = $form['noId'];
    $tipo_id = $form['tipoId'];
    
    $mdl = AutoCarga::factory('BancoSangreSQL', '', 'app', 'BancoSangre');
    
    $val = ModuloGetVar('', '', 'validacion_identificacion');
    if($val == $tipo_id)
    {
      $cl = AutoCarga::factory('ClaseUtil');
      $valida = $cl->ValidarCedulaEC($no_id);

      if($valida==false)
      {
        $html .= "<td>La cedula debe ser autentica\n";
        $html .= "</td>\n";
        $vac = "";
        $objResponse->assign("error_bp", "innerHTML", $html);
        $objResponse->assign("infoPaciente", "innerHTML", $vac);
        $objResponse->assign("error", "innerHTML", $vac);
        return $objResponse;
      }
    }
    
    $militar = $mdl->ConsultarMilitar($no_id, $tipo_id);
    $datos = $mdl->ConsultarDonante($no_id, $tipo_id, $militar);
    $estado_dc = $mdl->ConsultarEstadoDC($no_id, $tipo_id);
    
    if(count($datos)!=0)
    {
      $lugar_naci = $mdl->ConsultarLugarNacimiento($no_id, $tipo_id);      
      $lugar_domi = $mdl->ConsultarLugarDomicilio($no_id, $tipo_id);
      
      $tipificacion = $mdl->ConsultarTipificacion($datos[0]['codigo_donante']);
      
      $fr = explode('-', $datos[0]['fecha_registro']);
      $cod_fr = $fr[1].$fr[2];
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"100%\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.6%\">Cod. Donante:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"33.2%\" colspan=\"2\">".$cod_fr."-".$datos[0]['codigo_donante']."\n";
      $html .= "        <input type=\"hidden\" name=\"codDonante\" id=\"codDonante\" value=\"".$datos[0]['codigo_donante']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.6%\">Apellido Paterno:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"33.2%\" colspan=\"2\">".$datos[0]['primer_apellido']."\n";
      $html .= "        <input type=\"hidden\" name=\"apellidoPaterno\" value=\"".$datos[0]['primer_apellido']."\">\n";
      $html .= "      </td>\n";     
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Apellido Materno:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['segundo_apellido']."\n";
      $html .= "        <input type=\"hidden\" name=\"apellidoMaterno\" value=\"".$datos[0]['segundo_apellido']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Primer Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['primer_nombre']."\n";
      $html .= "        <input type=\"hidden\" name=\"primerNombre\" value=\"".$datos[0]['primer_nombre']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Segundo Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['segundo_nombre']."\n";
      $html .= "        <input type=\"hidden\" name=\"segundoNombre\" value=\"".$datos[0]['segundo_nombre']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Fuerza:\n";
      $html .= "      </td>\n";
      if($militar[0]['clasifi_finaci_id']!="")
      {
        $html .= "    <td class=\"modulo_list_claro\">\n";
        $html .= "      <div id=\"descFuerza\">".$datos[0]['desc_tipo_fuerza']."\n";

        $html .= "      </div>\n";
        $html .= "    </td>\n";
      }else{
        $html .= "    <td class=\"modulo_list_claro\">\n";
        $html .= "      <div id=\"descFuerza\">\n";
        $html .= "      </div>\n";
        $html .= "    </td>\n";
      }      
      $html .= "      <td class=\"formulacion_table_list\">Categoria:\n";
      $html .= "      </td>\n";
      if($militar[0]['clasifi_finaci_id']!="")
      {
        $html .= "    <td class=\"modulo_list_claro\">\n";
        $html .= "      <div id=\"descCate\">".$datos[0]['categoria']."\n";
        $html .= "      </div>\n";
        $html .= "    </td>\n";
      }else{
        $html .= "    <td class=\"modulo_list_claro\">\n";
        $html .= "      <div id=\"descCate\">\n";
        $html .= "      </div>\n";
        $html .= "    </td>\n";
      }
      
      $html .= "      <td class=\"formulacion_table_list\">Grado:\n";
      $html .= "      </td>\n";
      if($militar[0]['clasifi_finaci_id']!="")
      {
        $grado_categ = $mdl->ConsultarGradoCategoria($datos[0]['estado_fuerza_id']);
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <div id=\"descGrado\">".$datos[0]['desc_tipo_grado']."\n";
        $html .= "        </div>\n";
        $html .= "      </td>\n";
      }else{
        $html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <div id=\"descGrado\"><input type=\"hidden\" name=\"grado\" id=\"grado\" value=\"-1\"></div>\n";
        $html .= "      </td>\n";
      }
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Clasif. Financiera:\n";
      $html .= "      </td>\n";
      if($militar[0]['clasifi_finaci_id']!="")
      {
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
        $html .= "        <div id=\"clasiFinanciera\">".$datos[0]['desc_clasi_finan']."</div>\n";
        $html .= "        <input type=\"hidden\" name=\"clasificacion\" id=\"clasificacion\" value=\"".$militar[0]['clasifi_finaci_id']."\">\n";
        $html .= "      </td>\n";
      }else{
        $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
        $html .= "        <div id=\"clasiFinanciera\">\n";
        $html .= "          <input type=\"hidden\" name=\"clasificacion\" id=\"clasificacion\" value=\"-1\">\n";
        $html .= "        </div>\n";
        $html .= "      </td>\n";
      }
      if($datos[0]['fecha_nacimiento'])
      {
        $fn = explode("-",$datos[0]['fecha_nacimiento']);
        if(sizeof($fn)==3) $fNac=$fn[2]."/".$fn[1]."/".$fn[0];
      }
      $html .= "      <td class=\"formulacion_table_list\">Fecha Nacimiento:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">".$fNac."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Lugar de Nacimiento:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
      $html .= "        <div id=\"lugarNaci\"><label id=\"ubicacion\">".$lugar_naci[0]['desc_naci_pais']." - ".$lugar_naci[0]['desc_naci_dpto']." - ".$lugar_naci[0]['desc_naci_mpio']."</label></div>\n";
      $html .= "        <label id=\"tipo_comuna\"></label>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Edad:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <div id=\"edad_c\">".$datos[0]['edad']."</div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";     
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Sexo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['desc_sexo']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Estado Civil:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['desc_est_civil']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">e-mail:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['email']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Ocupacion:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">\n";
      $html .= "        <input type=\"hidden\" name=\"ocupacion_id\" value=\"".$datos[0]['ocupacion_id']."\">\n";
      $html .= "        <textarea class=\"textarea\" rows=\"1\" name=\"descripcion_ocupacion\" readonly style=\"width:100%;background:#FFFFFF\">".$datos[0]['desc_ocupacion']." </textarea>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";          
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Lugar Domicilio:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
      $html .= "        <div id=\"lugarDomi\"><label id=\"ubicacion1\">".$lugar_domi[0]['desc_domi_pais']." - ".$lugar_domi[0]['desc_domi_dpto']." - ".$lugar_domi[0]['desc_domi_mpio']."</label></div>\n";
      $html .= "        <label id=\"tipo_comunaM3\"></label>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tel. Domicilio:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['tel_domicilio']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Dir. Domicilio:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">".$datos[0]['dir_domicilio']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">No. Celular:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['no_celular']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Dir. Trabajo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">".$datos[0]['dir_trabajo']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tel. Trabajo\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['tel_trabajo']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Grupo Sanguineo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$tipificacion['grupo_sanguineo']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Factor RH:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$tipificacion['rh_gs']."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Subgrupo RH-:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$tipificacion['subgrupo_rh'].$tipificacion['rh_sg']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if($estado_dc[0]['estado_donante_id']!="")
      {
        $html .= "  <tr>\n";
        $html .= "    <td class=\"formulacion_table_list\">Estado Donante:\n";      
        $html .= "    </td>\n";  
        $html .= "    <td class=\"modulo_list_claro\" colspan=\"2\">".$estado_dc[0]['desc_est_donante']."\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"formulacion_table_list\">Causas:\n";      
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\" colspan=\"2\">".$estado_dc[0]['desc_cau_donante']."\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }else{
        $html .= "  <tr>\n";
        $html .= "    <td class=\"formulacion_table_list\">Estado Donante:\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\" colspan=\"5\">PENDIENTE POR REALIZAR EL REGISTRO DE DONACIONES\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td  colspan=\"6\" align=\"center\">REGISTRO DE BOLSAS\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $tiposBolsa = $mdl->ConsultarTiposBolsa(); 
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipos de Bolsa:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <select class=\"select\" name=\"tipoBolsa\" onchange=\"ValidarTipoBolsa()\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($tiposBolsa as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['tipo_bolsa_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Otros:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"otros\" size=\"35%\" disabled>\n";
      $estadosDonante = $mdl->ConsultarEstadosDonante();
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">ESTADO DE DONACION\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Estado Donante:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <select class=\"select\" name=\"estadoDonacion\" onchange=\"ValidarEstadoDonacion()\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($estadosDonante as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['estado_donante_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Causas:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <div id=\"divCausas\">\n";
      $html .= "          <input type=\"hidden\" name=\"causas\" value=\"-1\">\n";
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Observaciones:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">\n";
      $html .= "        <textarea class=\"textarea\" rows=\"1\" name=\"observaciones\" style=\"width:100%;background:#FFFFFF\"></textarea>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tiempo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"cantTiempo\" size=\"5%\" disabled>\n";
      $html .= "        <select class=\"select\" name=\"unidTiempo\" disabled>\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      $html .= "          <option value=\"Dia(s)\">Dia(s)</option>\n";
      $html .= "          <option value=\"Mes(es)\">Mes(es)</option>\n";
      $html .= "          <option value=\"Año(s)\">Año(s)</option>\n";
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">OTROS DATOS DE INTERES\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">Aspecto general del donante sano\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"aspecto\" value=\"SI\">SI\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"aspecto\" value=\"NO\">NO\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">Brazos sin lesion de agujas\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"brazosLesion\" value=\"SI\">SI\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"brazosLesion\" value=\"NO\">NO\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">Actividad peligrosa post donacion\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"actividad\" value=\"SI\">SI\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"actividad\" value=\"NO\">NO\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">Flebotomia del brazo\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"flebotomia\" value=\"Izquierdo\">Izquierdo\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"flebotomia\" value=\"Derecho\">Derecho\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">Puncion\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"puncion\" value=\"Unica\">Unica\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"radio\" name=\"puncion\" value=\"Varias\">Varias\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"6\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"ValidarDatos()\">\n";
      $html .= "      </td>\n";
      $html .= "      <input type=\"hidden\" name=\"tipoIngreso\" value=\"registrado\">\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";

      $objResponse->assign("divTipoDonador", "innerHTML", $datos[0]['desc_tipo_donante']);
      $objResponse->assign("divConvenio", "innerHTML", $datos[0]['desc_convenio']);
    }else{
        $html .= "<div id=\"divMsj\" class=\"label_error\"><b>EL DONANTE NO SE ENCUENTRA, PRIMERO DEBE LLENAR LA FICHA DE DONANTE</b></div>\n";
    }
    
    $clr = "";
    $objResponse->assign("error", "innerHTML", $clr);
    $objResponse->assign("infoPaciente", "innerHTML", utf8_encode($html));
    
    return $objResponse;
  }
  
  /**
  * Funcion que permite validar los campos del formulario de respuestas
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return string $objResponse objeto de respuesta al formulario
  */
  function EvaluarRespuesta($form)
  {
    $objResponse = new xajaxresponse();
    //$objResponse->alert(print_r($form,true));
    
    for($i = 0; $i< $form['cantPreg']; $i++)
    {
      if(empty($form['respuesta'.$i]))
      {
        $mensaje = "Debe responder la pegunta ".($i+1);
        $objResponse->assign('error','innerHTML',$mensaje);
        return $objResponse;
      }
    }    
    return $objResponse;
  }
  /**
  * Funcion que permite mostrar las causas relacionadas a un estado de donacion
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return string $objResponse objeto de respuesta al formulario
  */
  function BuscarEstadoCausa($form)
  {
    $objResponse = new xajaxResponse();
    $estDon = $form['estadoDonacion'];
    //$objResponse->alert($estDon);
    $mdl = AutoCarga::factory('BancoSangreSQL','','app','BancoSangre');
    $causa_est = $mdl->ConsultarCausasEstados($estDon);
    
    if(count($causa_est)>0)
    {
      $html .= "<select class=\"select\" name=\"causas\">\n";
      $html .= "  <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($causa_est as $indice => $valor)
      {
        $html .= "<option value=\"".$valor['causa_donacion_id']."\">".$valor['desc_causa_donacion']."</option>\n";
      }
      $html .= "</select>\n";
    }else{
        $html .= "<input type=\"hidden\" name=\"causas\" value=\"0\">\n";
    }
    $objResponse->assign("divCausas", "innerHTML", $html);
    
    return $objResponse;
  }
  /**
  * Funcion que permite mostrar la forma de ingreso para el fraccionamiento de sangre
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return string $objResponse objeto de respuesta al formulario
  */
  function BuscarFichaFrac($form)
  {
    $objResponse = new xajaxResponse();
    $cod_don = $form['codDon'];
    
    $mdl = AutoCarga::factory('BancoSangreSQL', '', 'app', 'BancoSangre');
    
    $identificacion = $mdl->ConsultarIdCodDon($cod_don);
    //$objResponse->alert($identificacion['donante_id']);
    $militar = $mdl->ConsultarMilitar($identificacion['donante_id'], $identificacion['tipo_id_donante']);
    $datos = $mdl->ConsultarDonante($identificacion['donante_id'], $identificacion['tipo_id_donante'], $militar);
    $estado_dc = $mdl->ConsultarEstadoDC($identificacion['donante_id'], $identificacion['tipo_id_donante']);
    
    if(count($datos)!=0)
    {
      $lugar_naci = $mdl->ConsultarLugarNacimiento($identificacion['donante_id'], $identificacion['tipo_id_donante']);      
      $lugar_domi = $mdl->ConsultarLugarDomicilio($identificacion['donante_id'], $identificacion['tipo_id_donante']);
      
      $tipificacion = $mdl->ConsultarTipificacion($datos[0]['codigo_donante']);
      
      $fecha_ext = date("d/m/Y");
      $hora_ext = date("H:i:s");
      
      $objResponse->assign("div_fechaExtraccion", "innerHTML", $fecha_ext);
      
      $fr = explode('-', $datos[0]['fecha_registro']);
      $cod_fr = $fr[1].$fr[2];
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"100%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\">DATOS GENERALES\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.6%\">Cod. Donante:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"16.6%\">".$cod_fr."-".$datos[0]['codigo_donante']."\n";
      $html .= "        <input type=\"hidden\" name=\"codDonante\" id=\"codDonante\" value=\"".$datos[0]['codigo_donante']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.6%\">Identificacion:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"16.6%\">".$identificacion['tipo_id_donante']." - ".$identificacion['donante_id']."\n";
      $html .= "        <input type=\"hidden\" name=\"noId\" id=\"noId\" value=\"".$identificacion['donante_id']."\">\n";
      $html .= "        <input type=\"hidden\" name=\"tipoId\" id=\"tipoId\" value=\"".$identificacion['tipo_id_donante']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.6%\">Apellido Paterno:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"33.2%\">".$datos[0]['primer_apellido']."\n";
      $html .= "        <input type=\"hidden\" name=\"apellidoPaterno\" value=\"".$datos[0]['primer_apellido']."\">\n";
      $html .= "      </td>\n";     
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Apellido Materno:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['segundo_apellido']."\n";
      $html .= "        <input type=\"hidden\" name=\"apellidoMaterno\" value=\"".$datos[0]['segundo_apellido']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Primer Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['primer_nombre']."\n";
      $html .= "        <input type=\"hidden\" name=\"primerNombre\" value=\"".$datos[0]['primer_nombre']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Segundo Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$datos[0]['segundo_nombre']."\n";
      $html .= "        <input type=\"hidden\" name=\"segundoNombre\" value=\"".$datos[0]['segundo_nombre']."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Edad:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <div id=\"edad_c\">".$datos[0]['edad']."</div>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Sexo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">".$datos[0]['desc_sexo']."\n";
      $html .= "      </td>\n";
      if($tipificacion_d['grupo_sanguineo'])
      {
        $html .= "  </tr>\n";           
        $html .= "    <td class=\"formulacion_table_list\">Grupo Sanguineo:\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\">".$tipificacion['grupo_sanguineo']."\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"formulacion_table_list\">Factor RH:\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\">".$tipificacion['rh_gs']."\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"formulacion_table_list\">Subgrupo RH-:\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\">".$tipificacion['subgrupo_rh'].$tipificacion['rh_sg']."\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      if($estado_dc[0]['estado_donante_id']!="")
      {
        $html .= "  <tr>\n";
        $html .= "    <td class=\"formulacion_table_list\">Estado Donante:\n";      
        $html .= "    </td>\n";  
        $html .= "    <td class=\"modulo_list_claro\" colspan=\"2\">".$estado_dc[0]['desc_est_donante']."\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"formulacion_table_list\">Causas:\n";      
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\" colspan=\"2\">".$estado_dc[0]['desc_cau_donante']."\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";              
      }else{
        $html .= "  <tr>\n";
        $html .= "    <td class=\"formulacion_table_list\">Estado Donante:\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\" colspan=\"5\">PENDIENTE POR REALIZAR EL REGISTRO DE DONACIONES\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\">REGISTRO DE HEMOCOMPONENTES\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">Leucorreducidos:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
      $html .= "        <input type=\"radio\" name=\"leucorreducidos\" value=\"SI\">SI\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\" align=\"left\">\n";
      $html .= "        <input type=\"radio\" name=\"leucorreducidos\" value=\"NO\">NO\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">Irradiados:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
      $html .= "        <input type=\"radio\" name=\"irradiados\" value=\"SI\">SI\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\" align=\"left\">\n";
      $html .= "        <input type=\"radio\" name=\"irradiados\" value=\"NO\">NO\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Fecha Fraccionam.:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$fecha_ext."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Hora Fraccionam.:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">".$hora_ext."\n";
      $html .= "        <input class=\"input-text\" type=\"hidden\" name=\"fechaHoraFrac\" value=\"".$fecha_ext." ".$hora_ext."\">\n";
      $html .= "        <input class=\"input-text\" type=\"hidden\" name=\"fechaFrac\" value=\"".$fecha_ext."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Fecha Caducidad:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"fechaCaducidad\" maxlength=\"12\" onkeypress=\"return acceptDate(event)\" size=\"9%\">\n";
      $html .= "        <a title=\"Ver Calendario\" href=\"javascript:Mostrar_Campo(document.formFracSangre.fechaCaducidad, 'fechaCaducidad')\" class=\"label_error\">\n";
      $html .= "          <img src=\"".GetThemePath()."/images/calendario/calendario.png\" border=\"0\">\n";
      $html .= "        </a>\n";
      $html .= "        <label>[dd/mm/aaaa]</label>\n";
      $html .= "        <div id=\"calendario_pxCampo\" class=\"calendario_px\"></div>\n";
      $html .= "      </td>\n";
      $tipoProd = $mdl->ConsultarTipoProductoFrac();
      $html .= "      <td class=\"formulacion_table_list\">Tipo de Producto:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <select class=\"select\" name=\"tipoProducto\" >\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>";
      foreach($tipoProd as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['tipo_producto_frac_id']."\">".$valor['descripcion']."</option>";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cantidad:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"cantidad\" size=\"10%\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\"> ml\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Responsable:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <select class=\"select\" name=\"responsable\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      $html .= "          <option value=\"1\">Ejemplo</option>\n";
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Observacion:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">\n";
      $html .= "        <textarea class=\"textarea\" rows=\"1\" name=\"observacion\" style=\"width:100%;background:#FFFFFF\"></textarea>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"6\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"ValidarDatos()\">\n";
      $html .= "      </td>\n";
      $html .= "      <input type=\"hidden\" name=\"tipoIngreso\" value=\"registrado\">\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";  
      $objResponse->assign("divTipoDonador", "innerHTML", $datos[0]['desc_tipo_donante']);
      $objResponse->assign("divConvenio", "innerHTML", $datos[0]['desc_convenio']);
    }else{
      $html .= "<div id=\"divMsj\" class=\"label_error\"><b>EL DONANTE NO SE ENCUENTRA, PRIMERO DEBE LLENAR LA FICHA DE DONANTE</b></div>\n";
    }        
    $clr = "";
    $objResponse->assign("error", "innerHTML", $clr);
    $objResponse->assign("infoPaciente", "innerHTML", utf8_encode($html));
    
    return $objResponse;
  }
?>