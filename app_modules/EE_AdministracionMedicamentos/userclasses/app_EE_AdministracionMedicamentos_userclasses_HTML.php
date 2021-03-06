<?php
  /**
  * $Id: app_EE_AdministracionMedicamentos_userclasses_HTML.php,v 1.10 2011/05/02 12:51:02 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
  * @package IPSOFT-SIIS
  */
  class app_EE_AdministracionMedicamentos_userclasses_HTML extends app_EE_AdministracionMedicamentos_user
  {
    /**
    * Constructor
    *
    * @return boolean
    */
    function app_EE_AdministracionMedicamentos_user_HTML()
    {
      $this->app_EE_AdministracionMedicamentos_user();
      $this->salida='';
      return true;
    }
    /**
    * Metodo Default
    *
    * @return boolean
    */
    function main()
    {
      $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
      $titulo='FALTA METODO EN EL LLAMADO';
      $mensaje='Este modulo requiere un METODO especifico y debe ser llamado desde la Estacion De Enfermeria.';
      $this->frmMSG($url, $titulo, $mensaje);
      return true;
    }
    /**
    * Forma para mostrar mensaje
    *
    * @param string $url opcional url de retorno
    * @param string $titulo opcional titulo de la ventana
    * @param string $mensaje opcional mensaje a mostrar
    * @return boolean True si se ejecuto correctamente
    * @access private
    */
    function frmMSG($url='', $titulo='', $mensaje='', $link='')
    {
      
      if(empty($titulo))  $titulo  = $this->titulo;
      if(empty($mensaje)) $mensaje = "EL USUARIO NO TIENE PERMISOS EN ESTE MODULO.";
      if(empty($link)) $link = "VOLVER";
      $this->salida  = themeAbrirTabla($titulo);
      $this->salida .= "<div class='titulo3' align='center'><br><br><b>$mensaje</b>";
      if($url)
      {
        $this->salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
        $this->salida.="    <tr>\n";
        $this->salida.="        <td align='center' class=\"label_error\">\n";
        $this->salida.="            <a href='$url'>$link</a>\n";
        $this->salida.="        </td>\n";
        $this->salida.="    </tr>\n";
        $this->salida.="  </table>\n";
      }
      $this->salida .= "<br><br></div>";
      $this->salida .= themeCerrarTabla();
      return true;
    }
    /**
    * Forma para mostrar la cabecera de la Estacion de Enfermeria
    *
    * @return boolean True si se ejecuto correctamente
    * @access private
    */
    function FrmDatosEstacion($datos)
    {
      $this->salida .= ThemeAbrirTabla("ESTACI&Oacute;N DE ENFERMERIA : ".$datos['estacion_descripcion']);
      $this->salida .= "<center>\n";
      $this->salida .= "    <table class='modulo_table_title' border='0' width='80%'>\n";
      $this->salida .= "        <tr class='modulo_table_title'>\n";
      $this->salida .= "            <td>Empresa</td>\n";
      $this->salida .= "            <td>Centro Utilidad</td>\n";
      $this->salida .= "            <td>Unidad Funcional</td>\n";
      $this->salida .= "            <td>Departamento</td>\n";
      $this->salida .= "        </tr>\n";
      $this->salida .= "        <tr class='modulo_list_oscuro'>\n";
      $this->salida .= "            <td>".$datos['empresa_descripcion']."</td>\n";
      $this->salida .= "            <td>".$datos['centro_utilidad_descripcion']."</td>\n";
      $this->salida .= "            <td>".$datos['unidad_funcional_descripcion']."</td>\n";
      $this->salida .= "            <td>".$datos['departamento_descripcion']."</td>\n";
      $this->salida .= "        </tr>\n";
      $this->salida .= "    </table>\n";
      $this->salida .= "</center>\n";
      return true;
    }
    /**
    * Forma para mostrar el pie de pagina de la Estacion de Enfermeria
    *
    * @return boolean True si se ejecuto correctamente
    * @access private
    */
    function FrmPieDePagina()
    {
      $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');

      $this->salida .= "<center>\n";
      $this->salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
      $this->salida.="    <tr>\n";
      $this->salida.="        <td align='center' class=\"label_error\">\n";
      $this->salida.="            <a href='$url'>VOLVER</a>\n";
      $this->salida.="        </td>\n";
      $this->salida.="    </tr>\n";
      $this->salida.="  </table>\n";
      $this->salida .= "</center>\n";
      $this->salida .= themeCerrarTabla();
      return true;
    }
    /**
    *
    */
    function SetStyle($campo)
    {
      if ($this->frmError[$campo] || $campo=="MensajeError")
      {
        if ($campo=="MensajeError")
        {
          $arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
          return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
        }
        return ("label_error");
      }
      return ("label");
    }
    /**
    * Forma para el ingreso de un paciente a la estacion.
    *
    * @return boolean True si se ejecuto correctamente
    * @access public
    */
    function CallFrmMedicamentos($datosPaciente,$datos_estacion)
    {
      
      //Validar si el usuario esta logueado y si tiene permisos.
      if(!$this->GetUserPermisos('52') AND !$this->GetUserPermisos('53') AND !$this->GetUserPermisos('54'))
      {
        $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
        $titulo='VALIDACION DE PERMISOS';
        $mensaje='El usuario no tiene permiso para : Solicitud de Insumos y Medicamentos (Pacientes) [52][53][54]';
        $this->frmMSG($url, $titulo, $mensaje);
        return true;
      }
          
      //Vector que contiene los datos del paciente internado.
      if(empty($datosPaciente))
        $datosPaciente = $_REQUEST['datosPaciente'];

      if($datosPaciente===false)
      {
        if(empty($this->error))
        {
          $this->error = "EE_AdministracionMedicamentos - FrmMedicamentos - 52";
          $this->mensajeDeError = "El metodo FrmMedicamentos() retorno false.";
        }
        return false;
      }
      elseif(!is_array($datosPaciente))
      {
        $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
        $titulo='VALIDACION DE PACIENTE';
        if(empty($datosPaciente))
        {
          $mensaje = "No se pudo obtener los datos del paciente a INGRESAR.";
        }
        else
        {
          $mensaje = $datosPaciente;
        }
        $this->frmMSG($url, $titulo, $mensaje);
        return true;
      }
          
      unset($_SESSION['codigos_I']);
      unset($_SESSION['cantidad_a_perdi_sol_I']);
      unset($_SESSION['PaquetesSeleccionados']);
      unset($_SESSION['Interna']);

      if(empty($datos_estacion))
        $datos_estacion = &$this->GetdatosEstacion();

      //VALIDACION DE PERMISOS
      if(!is_array($datos_estacion))
      {
        $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
        $titulo = "VALIDACION DE PERMISOS";
        $this->frmMSG($url,$titulo);
        return true;
      }
     
      //CABECERA - DATOS DE LA ESTACION DE ENFERMERIA
      $this->FrmDatosEstacion($datos_estacion);

      $vistoOk = $this->ObtenerVistoBuenoPaciente($datosPaciente['ingreso']);
      $this->FrmMedicamentos($datos_estacion,$datosPaciente,$vistoOk);
      $this->FrmAciones_Medicamentos($datos_estacion,$datosPaciente,$vistoOk);   
      
      //DATOS DEL PIE DE PAGINA
      $this->FrmPieDePagina();
       $this->CrearVentana(630,"INFORMACION MEDICAMENTOS DESPACHOS");
      return true;
    }
    /*
    * Forma que muestra informacion acerca de los medicamentos
    * pendientes por suministrar al paciente.
    *
    * Adaptacion Tizziano Perea.
    */
    function FrmMedicamentos($datos_estacion,$datosPaciente,$vistoOk)
    {
      $_SESSION['url']=$_SERVER['HTTP_REFERER'];
      $this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
      $this->SetXajax(array("Medicamentos_Despacho","GeneraReporte","Suministro_Rapido","SuministroRapidoInsumos"),"app_modules/EE_AdministracionMedicamentos/RemoteXajax/DespachoMedicamentos.php");
      //session q tiene el vector de seleccion de insumos          
      unset($_SESSION['EXISTENCIA']);
      //session q guarda las observaciones y el nombre al cual le solicitaron los insumos del paciente.
      unset($_SESSION['MEDICA_DATOS_SOL_PAC']);
      //vector de productos seleccionados(control suministro)
      unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']);
      // vector de medicamentos y soluciones para confirmar
      unset($_SESSION['VECTOR_MEDICAMENTOS_&_SOLUCIONES']);
      $this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
      $this->salida .= "<SCRIPT>";
      $this->salida .= "function chequeoTotal(frm,x){";
      $this->salida .= "  if(x==true){";
      $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
      $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
      $this->salida .= "      if(frm.elements[i].disabled==false){";
      $this->salida .= "        frm.elements[i].checked=true";
      $this->salida .= "      }";
      $this->salida .= "      }";
      $this->salida .= "    }";
      $this->salida .= "  }else{";
      $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
      $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
      $this->salida .= "        frm.elements[i].checked=false";
      $this->salida .= "      }";
      $this->salida .= "    }";
      $this->salida .= "  }";
      $this->salida .= "}";
      $this->salida .= "</script><br>\n";

      $this->salida.="<script>";
      $this->salida.="	function acceptNum(evt)\n";
      $this->salida.="	{\n";
      $this->salida.="		var nav4 = window.Event ? true : false;\n";
      $this->salida.="		var key = nav4 ? evt.which : evt.keyCode;\n";
      $this->salida.="		return (key <= 13 ||(key >= 48 && key <= 57));\n";
      $this->salida.="	}\n";
      $this->salida.="</script>\n";     

      $this->salida .= "<SCRIPT>";
      $this->salida .= "function ValidarEntregaM(CanDes, CanAsi, CanEnSol, namea){";
      $this->salida .= "  var res;";
//      $this->salida .= "        alert(CanDes + ' - ' + CanAsi + ' - ' + CanEnSol);";
      $this->salida .= "  res = eval(CanDes) + eval(CanEnSol);";
      $this->salida .= "  if(CanDes == 0){";
      $this->salida .= "        brake;";
      $this->salida .= "  }";
      $this->salida .= "  if(CanDes < 0){";
      $this->salida .= "        document.getElementById(namea).value = CanDes;";
      $this->salida .= "        brake;";
      $this->salida .= "  }";
      $this->salida .= "  if(res < 0){";
      $this->salida .= "        document.getElementById(namea).value = CanDes;";
      $this->salida .= "        brake;";
      $this->salida .= "  }";
      $this->salida .= "  if((CanDes + CanEnSol) > CanAsi){";
      $this->salida .= "        alert('La cantidad a despachar no pude ser superior a la formulada');";
      $this->salida .= "        res = CanAsi - CanEnSol;";
      $this->salida .= "        if (res <= 0)";
      $this->salida .= "        	document.getElementById(namea).value = CanDes;";
      $this->salida .= "        else";
      $this->salida .= "        	document.getElementById(namea).value = res;";
      $this->salida .= "  }";
      $this->salida .= "}";
      $this->salida .= "</script><br>\n";
      $this->salida .= "<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
      $this->salida .= "  <tr>\n";
      $this->salida .= "    <td>HABITACI???N</td>\n";
      $this->salida .= "    <td>CAMA</td>\n";
      $this->salida .= "    <td>PACIENTE</td>\n";
      $this->salida .= "    <td>IDENTIFICACI???N</td>\n";
      $this->salida .= "    <td>CUENTA</td>\n";
      $this->salida .= "    <td>INGRESO</td>\n";
      $this->salida .= "  </tr>\n";
      $this->salida .= "  <tr align='center' class='modulo_list_oscuro'>\n";
      $this->salida .= "    <td>".$datosPaciente[pieza]."</td>\n";
      $this->salida .= "    <td>".$datosPaciente[cama]."</td>\n";
      $this->salida .= "    <td>".$datosPaciente[nombre_completo]."</td>\n";
      $this->salida .= "    <td>".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</td>\n";
      $this->salida .= "    <td>".$datosPaciente[numerodecuenta]."</td>\n";
      $this->salida .= "    <td>".$datosPaciente[ingreso]."</td>\n";
      $this->salida .= "  </tr>\n";
      $this->salida .= "</table>\n";

      $this->salida .= "<table  align=\"center\" border=\"0\"  width=\"80%\">\n";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "</table>\n";
      
	  $this->salida .= "<table width=\"98%\" align=\"center\">\n";
	  $this->salida .= "	<tr>\n";
	  $this->salida .= "		<td>\n";
	  $this->salida .= "			<div class=\"tab-pane\" id=\"medicamentos_paciente\">\n";
	  $this->salida .= "				<script>	tabPane = new WebFXTabPane( document.getElementById( \"medicamentos_paciente\" )); </script>\n";
	  $this->salida .= "				<div class=\"tab-page\" id=\"medica_solu_formulados\">\n";
	  $this->salida .= "					<h2 class=\"tab\">PLAN TERAPEUTICO</h2>\n";
	  $this->salida .= "					<script>	tabPane.addTabPage( document.getElementById(\"medica_solu_formulados\")); </script>\n";

      /**************************** CONSULTA DE PRODUCTOS******************/
      $reporte = new GetReports();

      $datos = $this->GetEstacionBodega($datos_estacion,1);

      $slt = AutoCarga::factory("SolicitudesAutomaticas","classes","app","EE_AdministracionMedicamentos");

      $slt_pendientes = $slt->ObtenerMedicamentosSolicitud($datosPaciente);
      $slt = AutoCarga::factory("SolicitudesAutomaticas","classes","app","EE_AdministracionMedicamentos");
      $fac_conversion = $slt->ObtenerFactorConversion($slt_pendientes);
		
      $vector1 = $this->Consulta_Solicitud_Medicamentos($datosPaciente[ingreso]);//BODEGA PACIENTE
      $vectorS = $this->Consulta_Solicitud_Soluciones($datosPaciente[ingreso]);
      $vectorI = $this->Consulta_Solicitud_Insumos($datosPaciente[ingreso]); //BODEGA PACIENTE
      $m = 0;
      $this->salida .= "			    <div class=\"tab-pane\" id=\"terapeutico\">\n";
			$this->salida .= "				    <script>	tabPane1 = new WebFXTabPane( document.getElementById( \"terapeutico\" )); </script>\n";
			$this->salida .= "				    <div class=\"tab-page\" id=\"medicamentos_sol\">\n";
			$this->salida .= "					    <h2 class=\"tab\">MEDICAMENTOS</h2>\n";
			$this->salida .= "					    <script>	tabPane1.addTabPage( document.getElementById(\"medicamentos_sol\")); </script>\n";

      if($vector1 OR $vectorS)
      {
        $vectorOriginal = array();
        array_push($vectorOriginal, $vector1);
        array_push($vectorOriginal, $vectorS);

        $this->salida .= "<script>\n";
        $this->salida .= "  function compare(frm,x)\n";
        $this->salida .= "  {\n";
        $this->salida .= "    var cadena = new String();\n";
        $this->salida .= "    var bandera=new Boolean(true);\n";
        $this->salida .= "    for(i=0;i<$contador_sys+1;i++)\n";
        $this->salida .= "    {\n";
        $this->salida .= "      cadena='';\n";
        $this->salida .= "      cadena=document.getElementById(i).value;\n";
        $this->salida .= "      arrayofstring=new Array();\n";
        $this->salida .= "      arrayofstring=cadena.split(',');\n";
        $this->salida .= "      for (var n=0; n < arrayofstring.length ; n++)\n";
        $this->salida .= "      {\n";
        $this->salida .= "        if(arrayofstring[n]==x)\n";
        $this->salida .= "        {\n";
        $this->salida .= "          bandera=false;";
        $this->salida .= "          break;\n";
        $this->salida .= "        }";//fin if
        $this->salida .= "      }\n";//fin 2do for
        $this->salida .= "      if(x=='*/*')\n";
        $this->salida .= "      {\n";
        $this->salida .= "        document.getElementById('op'+i).disabled=false;\n";
        $this->salida .= "      }\n";
        $this->salida .= "      else\n";
        $this->salida .= "      {\n";
        $this->salida .= "        if(bandera==true)\n";
        $this->salida .= "        {\n";
        $this->salida .= "          document.getElementById('op'+i).checked=false;\n";
        $this->salida .= "        }";
        $this->salida .= "        document.getElementById('op'+i).disabled=bandera;\n";
        $this->salida .= "      }\n";//fin else
        $this->salida .= "    }\n";//fin 1er for
        $this->salida .= "  }\n";//fin funcion
        $this->salida .= "</script>\n";
        
        $this->salida .= "<table  align=\"center\" border=\"0\"  width=\"90%\" class=\"modulo_table_list\">\n";
        /*$this->salida .= "  <tr class=\"modulo_table_title\">\n";
        $this->salida .= "    <td align=\"center\" colspan=\"6\">PLAN TERAPEUTICO</td>";
        $this->salida .= "    <td align=\"center\" colspan=\"1\">SEL.</td>";
        $this->salida .= "  </tr>"; */
	$this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
        $this->salida .= "    <td  colspan=\"7\"><a onclick=\"xajax_Suministro_Rapido('".$datosPaciente['ingreso']."','".$datos_estacion['estacion_id']."','".$datos_estacion['centro_utilidad']."','".$datos_estacion['empresa_id']."');\"><font color='red'>SUMINISTRO RAPIDO</font></a></td>";
        $this->salida .= "  </tr>\n";
	$this->salida .= "  <tr >\n";
        $this->salida .= "    <td  colspan=\"7\">&nbsp;</td>";
        $this->salida .= "  </tr>\n";
        $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
        $this->salida .= "    <td width=\"40%\" colspan=\"3\">MEDICAMENTOS & SOLUCIONES</td>";
        $this->salida .= "    <td width=\"20%\" colspan=\"3\">OPCIONES</td>";
        $this->salida .= "    <td width=\"5%\">&nbsp;</td>\n";
        //<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)>
        $this->salida .= "  </tr>\n";
		
        $this->salida .= $this->Pintar_FormulacionConsultada($vectorOriginal, $datos, $datosPaciente, $datos_estacion, &$reporte);

        $_SESSION['VECTOR_MEDICAMENTOS_&_SOLUCIONES'] = $vectorOriginal;

        //aca colocamos la bodega........
        $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\" align=\"center\">\n";
        $this->salida .= "    <td colspan=\"7\">\n";
        $this->salida .= "      <table width=\"100%\">\n";
        $this->salida .= "        <tr class=\"modulo_table_title\">\n";
        $this->salida .= "          <td align=\"center\" width=\"70%\">SELECCION DE BODEGA :</td>\n";
        $this->salida .= "          <td align=\"right\" width=\"30%\">\n";
        $this->salida .= "            <select name=bodega  onchange=compare(this.form,this.options[selectedIndex].value) class='select'>\n";
        $this->salida .= "              <option value=-1 SELECTED>--SELECCIONE--</option>\n";

        if(is_array($datos))
        {
          for($i=0;$i<sizeof($datos);$i++)
          {
            $this->salida .= "              <option value=".$datos[$i][bodega].">".$datos[$i][descripcion]."</option>\n";
          }
        }
        $this->salida .= "              <option value=*/* >SOLICITUD PACIENTE</option>\n";
        $this->salida .= "            </select>\n";
        $this->salida .= "          </td>\n";
        $this->salida .= "        </tr>\n";
        $this->salida .= "        <tr class=\"hc_table_submodulo_list_title\" align=\"center\">\n";

        //reporte pdf y html
        $mostrar=$reporte->GetJavaReport('app','EE_AdministracionMedicamentos','formula_medica_estacion_html',array('tipo_id_paciente'=>$datosPaciente['tipo_id_paciente'],'paciente_id'=>$datosPaciente['paciente_id'], 'ingreso'=>$datosPaciente['ingreso']),array('rpt_name'=>'formulamedica','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $nombre_funcion=$reporte->GetJavaFunction();
        $this->salida .=$mostrar;
        //reporte pdf y html
        $Accion_pos = ModuloGetURL('app','EE_AdministracionMedicamentos','user','ReporteFormulaMedica',array('datosPaciente'=>$datosPaciente, 'datos_estacion'=>$datos_estacion, 'impresion_pos'=>'1'));
        $this->salida .= "          <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "            <a href=\"javascript:$nombre_funcion\">\n";
        $this->salida .= "              <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR PDF\n";
        $this->salida .= "            </a>|| ";
        $this->salida .= "            <a href=\"$Accion_pos\">\n";
        $this->salida .= "              <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR POS\n";
        $this->salida .= "            </a>\n";
        $this->salida .= "          </td>\n";
        $this->salida .= "        </tr>\n";
        //<duvan>  --> el link de solicitud de mediamentos.
        $this->salida .= "        <tr class=\"modulo_table_title\">";
        if(UserGetUID()==0)
        {
          $this->salida .= "          <td colspan=\"7\" align=\"center\">\n";
          $this->salida .= "            <font color='white'>LA ESTACION ".$datos_estacion['estacion_descripcion']." &nbsp;ESTA EN MODO DE LECTURA</font>\n";
          $this->salida .= "          </td>\n";
        }
        else
        {
          if(is_array($datos))
          {
            $this->salida .= "          <td colspan=\"7\" align=\"right\">\n";
            if(empty($vistoOk))
              $this->salida .= "            <input type=submit class='input-submit' name='mandar' value='SOLICITAR'>\n";
            else
              $this->salida .= "  EL PACIENTE TIENE VISTO BUENO DE ENFERMERIA, NO SE PUEDE REALIZAR LA SOLICTUD DE MEDICAMENTOS";
            $this->salida .= "          </td>\n";
          }
          else
          {
            $this->salida .= "          <td colspan=\"7\" align=\"center\">\n";
            $this->salida .= "            <font color='white'>LA ESTACION ".$datos_estacion['estacion_descripcion']." &nbsp;NO TIENE BODEGAS ASOCIADAS</font>\n";
            $this->salida .= "          </td>\n";
          }
        }
	 $this->salida .= " <tr class=\"hc_table_submodulo_list_title\">";	
	$this->salida .= " <td align=\"center\" colspan=\"2\"><a onclick=\"xajax_Suministro_Rapido('".$datosPaciente['ingreso']."','".$datos_estacion['estacion_id']."','".$datos_estacion['centro_utilidad']."','".$datos_estacion['empresa_id']."');\"><font color='red'>SUMINISTRO RAPIDO</font></a></td>";
        $this->salida .= "          </form>\n";
        $this->salida .= "        </tr>\n";
        $this->salida .= "      </table>\n";
        $this->salida .= "    </td>\n";
        $this->salida .= "  </tr>\n";
        $this->salida .= "</table><br>\n";
                      
        //parte de las solicitudes de medicmanetos por parte d e los pacientes
        unset($medic);
        $medic=$this->Get_Medicamentos_Solicitados_Para_Pacientes($datosPaciente['ingreso'],$datos_estacion[empresa_id]);

        if(!empty($medic))
        {
            $_SESSION['ESTACION']['VECTOR_SOL_MED_PAC'][$datosPaciente['ingreso']]=$medic;
            
            $this->salida .= "  <table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";
            $this->salida .= "      <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "          <td colspan='8'  align=\"center\">SOLICITUDES REALIZADAS PARA EL PACIENTE (pendiente despacho)</td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "      <tr class=\"hc_table_submodulo_list_title\">\n";
            $this->salida .= "          <td width=\"10%\" >CODIGO</td>\n";
            $this->salida .= "          <td width=\"42%\" >PRODUCTO</td>\n";
            $this->salida .= "          <td width=\"5%\" >CANT SOL</td>\n";
            $this->salida .= "          <td width=\"5%\" >CANT REC</td>\n";
            $this->salida .= "          <td width=\"35%\" colspan='3'>ACCION</td>\n";
            $this->salida .= "          <td width=\"2%\" ></td>\n";
            $this->salida .= "      </tr>\n";

            for($k=0;$k<sizeof($medic);$k++)
            {
              if($k % 2)  
                $estilo = "class=modulo_list_claro";  
              else  
                $estilo = "class=modulo_list_oscuro";

              $this->salida .= "<tr $estilo>\n";
              $this->salida .= "<td $estilo width=\"12%\">".$medic[$k][codigo_producto]."</td>\n";
              $this->salida .= "<td $estilo width=\"40%\">".$medic[$k][producto]."</td>\n";
              $this->salida .= "<td $estilo align=\"center\" width=\"9%\">".floor($medic[$k][cantidad])."</td>\n";
              //aca colocar el query de las cantidades recibisdas...........
              $recepcion=$this->Recepcion_Med_Ins_Para_Pacientes($datosPaciente['ingreso'],$medic[$k][codigo_producto],$datos_estacion[estacion_id]);
              $this->salida .= "<td $estilo align=\"center\" width=\"9%\">".floor($recepcion)."</td>\n";
              $this->salida .= "<td $estilo width=\"2%\"><a href='".ModuloGetURL('app','EE_AdministracionMedicamentos','user','Recibir_X_Para_Pacientes',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"codigo_producto"=>$medic[$k][codigo_producto],"solicitud"=>$medic[$k][solicitud_id]))."'><DIV TITLE='RECIBIR EL MEDICAMENTO/INSUMO  &nbsp;".$medic[$k][producto]."'><img src=\"". GetThemePath() ."/images/resultado.png\" border='0' ></DIV></a></td>\n";
              $this->salida .= "<td $estilo width=\"2%\"><DIV TITLE='VER EL MEDICAMENTO/INSUMO &nbsp;".$medic[$k][producto]."'><img src=\"". GetThemePath() ."/images/auditoria.png\" border='0'></DIV></td>\n";
              $this->salida .= "<td $estilo width=\"2%\"><a href='".ModuloGetURL('app','EE_AdministracionMedicamentos','user','Cancelar_Sol_X_Med_Pacientes',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"codigo_producto"=>$medic[$k][codigo_producto],"solicitud"=>$medic[$k][solicitud_id]))."'><DIV TITLE='CANCELAR EL MEDICAMENTO/INSUMO &nbsp;".$medic[$k][producto]."'><img src=\"". GetThemePath() ."/images/error_digitacion.png\" border='0'></DIV></a></td>\n";
              $this->salida .= "  <td $estilo width=\"2%\" align=\"center\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'></td>";
              $this->salida .= " </tr>";
            }
            $this->salida.="</table></form>";
        }
        //fin de solicitudes por parte de los pacientes.     
      }        
      else
      {
        $this->salida .= "				  <table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
        $this->salida .= "  				  <tr align=\"center\">\n";
        $this->salida .= "    				  <td>\n";
        $this->salida .= "      				  <label class='label_mark'>EL PACIENTE NO TIENE MEDICAMENTOS SOLICITADOS</label>\n";
        $this->salida .= "    				  </td>\n";
        $this->salida .= "  				  </tr>\n";
        $this->salida .= "				  </table>\n";
      }
      $this->salida .= "				    </div>\n";
      $this->salida .= "				    <div class=\"tab-page\" id=\"insumos_sol\">\n";
			$this->salida .= "					    <h2 class=\"tab\">INSUMOS</h2>\n";
			$this->salida .= "					    <script>	tabPane1.addTabPage( document.getElementById(\"insumos_sol\")); </script>\n";

      if($vectorI)
      {
        $this->salida .= "<table  align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"80%\">\n";
	$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
        $this->salida.="  <td colspan=\"6\"><a onclick=\"xajax_SuministroRapidoInsumos('".$datosPaciente['ingreso']."','".$datos_estacion['estacion_id']."','".$datos_estacion['centro_utilidad']."','".$datos_estacion['empresa_id']."');\"><font color='red'>SUMINISTRO RAPIDO</font></a></td>";
        $this->salida.="</tr>";
	$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
        $this->salida.="  <td colspan=\"6\">&nbsp;</td>";
        $this->salida.="</tr>";    
        $this->salida .= "  <tr class=\"modulo_table_title\">";
        $this->salida .= "    <td align=\"center\" colspan=\"3\">PLAN TERAPEUTICO</td>";
        $this->salida .= "    <td align=\"center\" colspan=\"3\">SEL.</td>";
        $this->salida .= "  </tr>";

        $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
        $this->salida.="  <td width=\"40%\" colspan=\"3\">INSUMOS</td>";
        $this->salida.="  <td colspan=\"3\">OPCIONES</td>";
        $this->salida.="</tr>";

        $vectorOriginal = array();
        array_push($vectorOriginal, $vectorI);
        $this->salida.= $this->Pintar_FormulacionConsultadaInsumos($vectorOriginal, $datos, $datosPaciente, $datos_estacion, &$reporte);

        $_SESSION['VECTOR_INSUMOS'] = $vectorOriginal;
	$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
        $this->salida.="  <td width=\"40%\" colspan=\"6\">&nbsp;</td>";
        $this->salida.="</tr>";
	$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
        $this->salida.="  <td width=\"40%\" colspan=\"6\"><a onclick=\"xajax_SuministroRapidoInsumos('".$datosPaciente['ingreso']."','".$datos_estacion['estacion_id']."','".$datos_estacion['centro_utilidad']."','".$datos_estacion['empresa_id']."');\"><font color='red'>SUMINISTRO RAPIDO</font></a></td>";
        $this->salida.="</tr>";
        $this->salida .= "</table><br>";
        $this->salida .= "<SCRIPT>\n";
        $this->salida .= "function compare(frm,x){\n";
        $this->salida .= "var cadena = new String();\n";
        $this->salida .= "var bandera=new Boolean(true);\n";
        $this->salida .= "    for(i=0;i<$contador_sys+1;i++){\n";
        $this->salida .= "cadena='';\n";
        $this->salida .= "cadena=document.getElementById(i).value;\n";
        $this->salida .= "arrayofstring=new Array();\n";
        $this->salida .= "arrayofstring=cadena.split(',');\n";
        $this->salida .= "for (var n=0; n < arrayofstring.length ; n++) {\n";
        $this->salida .= "if(arrayofstring[n]==x){\n";
        $this->salida .= "bandera=false;";
        $this->salida .= "break;\n";
        $this->salida .= "}";//fin if
        $this->salida .= "}\n";//fin 2do for
        $this->salida .= "if(x=='*/*'){";
        $this->salida .= "document.getElementById('op'+i).disabled=false;\n";
        $this->salida .= "}else{";
        $this->salida .= "if(bandera==true){";
        $this->salida .= "document.getElementById('op'+i).checked=false;\n";
        $this->salida .= "}";
        $this->salida .= "document.getElementById('op'+i).disabled=bandera;\n";
        $this->salida .= "}\n";//fin else
        $this->salida .= "}\n";//fin 1er for
        $this->salida .= "}\n";//fin funcion
        $this->salida .= "</SCRIPT>\n";

        //parte de las solicitudes de medicmanetos por parte d e los pacientes
        /*unset($medic);
        $medic=$this->Get_Medicamentos_Solicitados_Para_Pacientes($datosPaciente['ingreso'],$datos_estacion[empresa_id]);

        if(!empty($medic))
        {
          $_SESSION['ESTACION']['VECTOR_SOL_MED_PAC'][$datosPaciente['ingreso']]=$medic;

          $this->salida .= "  <table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";
          $this->salida .= "      <tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "          <td colspan='8'  align=\"center\">SOLICITUDES REALIZADAS PARA EL PACIENTE (pendiente despacho)</td>\n";
          $this->salida .= "      </tr>\n";
          $this->salida .= "      <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "          <td width=\"10%\" >CODIGO</td>\n";
          $this->salida .= "          <td width=\"42%\" >PRODUCTO</td>\n";
          $this->salida .= "          <td width=\"5%\" >CANT SOL</td>\n";
          $this->salida .= "          <td width=\"5%\" >CANT REC</td>\n";
          $this->salida .= "          <td width=\"35%\" colspan='3'>ACCION</td>\n";
          $this->salida .= "          <td width=\"2%\" ></td>\n";
          $this->salida .= "      </tr>\n";

          for($k=0;$k<sizeof($medic);$k++)
          {
            if($k % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";

            $this->salida .= "<tr $estilo>\n";
            $this->salida .= "<td $estilo width=\"12%\">".$medic[$k][codigo_producto]."</td>\n";
            $this->salida .= "<td $estilo width=\"40%\">".$medic[$k][producto]."</td>\n";
            $this->salida .= "<td $estilo align=\"center\" width=\"9%\">".floor($medic[$k][cantidad])."</td>\n";
            //aca colocar el query de las cantidades recibisdas...........
            $recepcion=$this->Recepcion_Med_Ins_Para_Pacientes($datosPaciente['ingreso'],$medic[$k][codigo_producto],$datos_estacion[estacion_id]);
            $this->salida .= "<td $estilo align=\"center\" width=\"9%\">".floor($recepcion)."</td>\n";
            $this->salida .= "<td $estilo width=\"2%\"><a href='".ModuloGetURL('app','EE_AdministracionMedicamentos','user','Recibir_X_Para_Pacientes',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"codigo_producto"=>$medic[$k][codigo_producto],"solicitud"=>$medic[$k][solicitud_id]))."'><DIV TITLE='RECIBIR EL MEDICAMENTO/INSUMO  &nbsp;".$medic[$k][producto]."'><img src=\"". GetThemePath() ."/images/resultado.png\" border='0' ></DIV></a></td>\n";
            $this->salida .= "<td $estilo width=\"2%\"><DIV TITLE='VER EL MEDICAMENTO/INSUMO &nbsp;".$medic[$k][producto]."'><img src=\"". GetThemePath() ."/images/auditoria.png\" border='0'></DIV></td>\n";
            $this->salida .= "<td $estilo width=\"2%\"><a href='".ModuloGetURL('app','EE_AdministracionMedicamentos','user','Cancelar_Sol_X_Med_Pacientes',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"codigo_producto"=>$medic[$k][codigo_producto],"solicitud"=>$medic[$k][solicitud_id]))."'><DIV TITLE='CANCELAR EL MEDICAMENTO/INSUMO &nbsp;".$medic[$k][producto]."'><img src=\"". GetThemePath() ."/images/error_digitacion.png\" border='0'></DIV></a></td>\n";
            $this->salida.="  <td $estilo width=\"2%\" align=\"center\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'></td>";
            $this->salida.=" </tr>";
          }
          $this->salida.="</table></form>";
        }//fin de solicitudes por parte de los pacientes.*/   
      }
          
      $this->salida .= "				    </div>\n";
      $this->salida .= "				  </div>\n";
      $this->salida .= "				</div>\n";
      $this->salida .= "				<div class=\"tab-page\" id=\"solicitudes\">\n";
			$this->salida .= "					<h2 class=\"tab\">SOLICITUDES REALIZADAS</h2>\n";
			$this->salida .= "					<script>	tabPane.addTabPage( document.getElementById(\"solicitudes\")); </script>\n";

      // BUSQUEDA DE SOLICITUDES DE MEDICAMENTOS E INSUMOS
      //consulta de medicamentos solicitados
      unset($medic);
      $medic=$this->GetMedicamentosSolicitadosControlPacientes($datosPaciente['ingreso'],$datos_estacion['empresa_id']);
 
      if(sizeof($medic))
      {
        $_SESSION['ESTACION']['VECTOR_SOL'][$datosPaciente['ingreso']]=$medic;
        $f = ModuloGetURL('app','EE_AdministracionMedicamentos','user','ConfirmarCancelSolicitudMed',array("ingreso"=>$datosPaciente['ingreso'],"datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
        $this->salida .= "  				<form name='conf' action='".$f."' method='POST'><br>\n";
        $this->salida .= "   				  <table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";
        $this->salida .= "       				<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "           		  <td colspan='7'  align=\"center\">SOLICITUDES REALIZADAS DE MEDICAMENTOS (pendiente despacho)</td>\n";
        $this->salida .= "              </tr>\n";
        $this->salida .= "              <tr class=\"hc_table_submodulo_list_title\">\n";
        $this->salida .= "                <td width=\"5%\" >SOLICITUD</td>\n";
        $this->salida .= "                <td width=\"17%\" >BODEGA</td>\n";
        $this->salida .= "                <td width=\"10%\" >CODIGO</td>\n";
        $this->salida .= "                <td width=\"25%\" >PRODUCTO</td>\n";
        $this->salida .= "                <td width=\"25%\" >PRINCIPIO ACTIVO</td>\n";
        $this->salida .= "                <td width=\"5%\" >CANT</td>\n";
        $this->salida .= "                <td width=\"2%\" ></td>\n";
        $this->salida .= "              </tr>\n";

        for($k=0;$k<sizeof($medic);$k++)
        {
          if($k % 2)  
            $estilo = "class=modulo_list_claro";  
          else  
            $estilo = "class=modulo_list_oscuro";
          
          if($medic[$k][solicitud_id]!= $medic[$k-1][solicitud_id])
          {
            $this->salida .= "              <tr $estilo>\n";
            $this->salida .= "               <td colspan = 1 width=\"5%\" align=\"center\" width=\"10%\">".$medic[$k][solicitud_id]."</td>\n";
            $solicitud=$medic[$k][solicitud_id];
            $this->salida .= "               <td colspan = 5 width=\"65%\">";
            $this->salida .= "                 <table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'>\n";
          }

          $nom_bodega=$this->TraerNombreBodega($datos_estacion,$medic[$k][bodega]);
          $this->salida .= "                  <tr $estilo>\n";
          $this->salida .= "                    <td width=\"20%\"><label class='label_mark'>$nom_bodega</label></td>\n";
          $this->salida .= "                    <td width=\"12%\">".$medic[$k][codigo_producto]."</td>\n";
          $this->salida .= "                    <td width=\"30%\">".$medic[$k][producto]."</td>\n";
          $this->salida .= "                    <td width=\"28%\">".$medic[$k][principio_activo]."</td>\n";
          $this->salida .= "                    <td align=\"center\" width=\"7%\">".floor($medic[$k][cant_solicitada])."</td>\n";
          $this->salida .= "                  </tr>\n";
          if($medic[$k][solicitud_id] != $medic[$k+1][solicitud_id])
          {
            $this->salida .= "                 </table>";
            $this->salida .= "              </td>";
            $this->salida .= "              <td colspan = 1 $estilo width=\"2%\" align=\"center\"><input type=checkbox name=opcion[] value=".$medic[$k][solicitud_id].",".$medic[$k][consecutivo_d]."></td>";
            $this->salida .= "            </tr>\n";
          }
        }
        $this->salida .= "   				  <tr align='right' class=\"hc_table_submodulo_list_title\">\n";
        $this->salida .= "     				  <td colspan=\"7\" align=\"right\">\n";
        $this->salida .= "       				  <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CANCELAR\">\n";
        $this->salida .= "     				  </td>\n";
        $this->salida .= "   				  </tr>\n";
        $this->salida .= " 				  </table>\n";
        $this->salida .= "				</form>\n";
      }
      
      //consulta de insumos solicitados
      unset($medic);
      $medic=$this->GetInsumosSolicitadosControlPacientes($datosPaciente['ingreso'],$datos_estacion[empresa_id]);
      
      if(sizeof($medic))
      {
        $_SESSION['ESTACION']['VECTOR_SOL_INS'][$datosPaciente['ingreso']]=$medic;
        $f = ModuloGetURL('app','EE_AdministracionMedicamentos','user','ConfirmarCancelSolicitudIns',array("ingreso"=>$datosPaciente['ingreso'],"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
        
        $this->salida .= "				<form name='conf' action='".$f."' method='POST'>\n";
        $this->salida .= "  				<table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";
        $this->salida .= "    				<tr class=\"formulacion_table_list\">\n";
        $this->salida .= "      				<td colspan='7' align=\"center\">SOLICITUDES REALIZADAS DE INSUMOS (pendiente despacho)</td>\n";
        $this->salida .= "    				</tr>\n";
        $this->salida .= "    				<tr class=\"hc_table_submodulo_list_title\">\n";
        $this->salida .= "      				<td width=\"5%\" >SOLICITUD</td>\n";
        $this->salida .= "      				<td width=\"17%\" >BODEGA</td>\n";
        $this->salida .= "      				<td width=\"10%\" >CODIGO</td>\n";
        $this->salida .= "      				<td width=\"25%\" >INSUMO</td>\n";
        $this->salida .= "      				<td width=\"25%\"  >ABREVIACION</td>\n";
        $this->salida .= "      				<td width=\"5%\" >CANT</td>\n";
        $this->salida .= "      				<td width=\"2%\" ></td>\n";
        $this->salida .= "    				</tr>\n";

        for($k=0;$k<sizeof($medic);$k++)
        {
          if($k % 2)  
            $estilo = "class=\"modulo_list_claro\"";  
          else  
            $estilo = "class=\"modulo_list_oscuro\"";
            
          if($medic[$k][solicitud_id]!= $medic[$k-1][solicitud_id])
          {
            $this->salida .= "    				<tr $estilo>\n";
            $this->salida .= "      				<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$k][solicitud_id]."</td>\n";
            $solicitud=$medic[$k][solicitud_id];
            $this->salida .= "      				<td colspan = 5 width=\"65%\">";
            $this->salida .= "        				<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
          }

          $nom_bodega=$this->TraerNombreBodega($datos_estacion,$medic[$k][bodega]);
          $this->salida .= "          				<tr $estilo>\n";
          $this->salida .= "            				<td width=\"20%\"><label class='label_mark'>$nom_bodega</label></td>\n";
          $this->salida .= "            				<td width=\"12%\">".$medic[$k][codigo_producto]."</td>\n";
          $this->salida .= "            				<td width=\"30%\">".$medic[$k][producto]."</td>\n";
          $this->salida .= "            				<td width=\"28%\">".$medic[$k][descripcion_abreviada]."</td>\n";
          $this->salida .= "            				<td align=\"center\" width=\"7%\">".floor($medic[$k][cantidad])."</td>\n";
          $this->salida .= "          				</tr>\n";
          if($medic[$k][solicitud_id] != $medic[$k+1][solicitud_id])
          {
            $this->salida .= "        				</table>\n";
            $this->salida .= "      				</td>\n";
            $this->salida .= "      				<td colspan = 1 $estilo width=\"2%\" align=\"center\"><input type=checkbox name=opcion[] value=".$medic[$k][solicitud_id].",".$medic[$k][consecutivo_d]."></td>";
            $this->salida .= "    				</tr>\n";
          }
        }
        $this->salida .= "    				<tr align='right' class=\"hc_table_submodulo_list_title\">\n";
        $this->salida .= "      				<td colspan=\"7\" align=\"right\">\n";
        $this->salida .= "        				<input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CANCELAR\">";
        $this->salida .= "      				</td>\n";
        $this->salida .= "    				</tr>\n";
        $this->salida .= "  				</table>\n";
        $this->salida .= "				</form>\n";
      }
      
      $this->salida .= "				</div>\n";
      
      $slt = AutoCarga::factory("SolicitudesAutomaticas","classes","app","EE_AdministracionMedicamentos");
      $slt_pendientes = $slt->ObtenerMedicamentosSolicitud($datosPaciente);
      
      if(!empty($slt_pendientes) && empty($vistoOk))
      {

        $coninv = $this->BodegaInventario($datos[0][bodega]);
        if(count($coninv) == 0)
          $coninv1 = '0';
        else
          $coninv1 = $coninv['sw_inventario'];
      
        $bodega_default = ModuloGetVar('app','EE_AdministracionMedicamentos','bodega_default');
        $action['crear'] = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CrearSolicitud',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
        $action['cancelar'] = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CancelarPreSolicitud',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
        $presolicitud = $slt->ObtenerInsumosAsociados($slt_pendientes,$datos_estacion['departamento']);
        $fac_conversion = $slt->ObtenerFactorConversion($slt_pendientes);
        $mdl = AutoCarga::factory("PreSolicitudesHTML","views","app","EE_AdministracionMedicamentos");
        $this->salida .= "			  <div class=\"tab-page\" id=\"pre_solicitudes\">\n";
  			$this->salida .= "					<h2 class=\"tab\">PRE SOLICITUDES</h2>\n";
  			$this->salida .= "					<script>	tabPane.addTabPage( document.getElementById(\"pre_solicitudes\")); </script>\n";
  			$this->salida .= "          ".$mdl->FormaListaPresolicitudes($action,$slt_pendientes,$presolicitud,$datos,$bodega_default,$fac_conversion,$coninv1);
        $this->salida .= "			  </div>\n";
  			$this->salida .= "		  </div>\n";
      }
      
      $this->salida .= "		</td>\n";
      $this->salida .= "	</tr>\n";
      $this->salida .= "</table>\n";

      if($_REQUEST['grupo_tab'])
        $grupo = $_REQUEST['grupo_tab']-1;
      else if(!empty($slt_pendientes) && empty($vistoOk))
        $grupo = 2;
      else
        $grupo = 0;
			$this->salida .= "<script type=\"text/javascript\">\n";
			$this->salida .= "	setupAllTabs();\n";
			$this->salida .= "	tabPane.setSelectedIndex(".$grupo.");";
			$this->salida .= "</script>\n";
 //	
      return true;
    }
    /**
    *
    */
    function CrearSolicitud()
    {
        $request = $_REQUEST;
     
        if(!$this->GetUserPermisos('52'))
        {
            $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("grupo_tab"=>"3","datosPaciente"=>$request['datosPaciente'],"datos_estacion"=>$request['datos_estacion']));
            $titulo='VALIDACION DE PERMISOS';
            $mensaje='El usuario no tiene permiso para : 	Solicitud de Insumos y Medicamentos (Pacientes) [52]';
            $this->frmMSG($url, $titulo, $mensaje);
            return true;
        }
	  
	  
      $slt = AutoCarga::factory("SolicitudesAutomaticas","classes","app","EE_AdministracionMedicamentos");
      $rst = $slt->CrearSolicitudMedicamentos($request);
      $action['volver'] = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("grupo_tab"=>"2","datosPaciente"=>$request['datosPaciente'],"datos_estacion"=>$request['datos_estacion']));

      $html = "";
      if($rst === false)
			{
				$html .= ThemeAbrirTabla('MENSAJE DE ERROR');
				$html .= "<form name=\"formabuscar\" action=\"".$action['volver']."\" method=\"post\">\n";   
				$html .= "	<table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "		<tr class=\"normal_10AN\">\n";
				$html .= "			<td>\n";
				$html .= "				".$slt->mensajeDeError."\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table><br>\n";
				$html .= "	<table width=\"60%\" border=\"0\" align=\"center\">\n";
				$html .= "		<tr>\n";
				$html .= "			<td align=\"center\">\n";
				$html .= "				<input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Aceptar\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
				$html .= "</form>\n";
				$html .= ThemeCerrarTabla(); 
			}
			else
			{
				$html .= "<script>\n";
				$html .= "	location.href = \"".$action['volver']."\"\n";
				$html .= "</script>\n";
			}
      
      $this->salida .= $html;      
      return true;
    }    
    /**
    *
    */
    function CancelarPreSolicitud()
    {
      $request = $_REQUEST;
      $action['volver'] = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datosPaciente"=>$request['datosPaciente'],"datos_estacion"=>$request['datos_estacion']));
      $slt = AutoCarga::factory("SolicitudesAutomaticas","classes","app","EE_AdministracionMedicamentos");
      $rst = $slt->CancelarPreSolicitud($request);
      
      $html = "";
      if($rst === false)
			{
				$html .= ThemeAbrirTabla('MENSAJE DE ERROR');
				$html .= "<form name=\"formabuscar\" action=\"".$action['volver']."\" method=\"post\">\n";   
				$html .= "	<table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "		<tr class=\"normal_10AN\">\n";
				$html .= "			<td>\n";
				$html .= "				".$slt->mensajeDeError."\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table><br>\n";
				$html .= "	<table width=\"60%\" border=\"0\" align=\"center\">\n";
				$html .= "		<tr>\n";
				$html .= "			<td align=\"center\">\n";
				$html .= "				<input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Aceptar\">\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
				$html .= "</form>\n";
				$html .= ThemeCerrarTabla(); 
			}
			else
			{
				$html .= "<script>\n";
				$html .= "	location.href = \"".$action['volver']."\"\n";
				$html .= "</script>\n";
			}
      
      $this->salida .= $html;
      return true;
    }
    /*
    * Forma que permite dibujar la consulta de los medicamentos.
    *
    * @autor Tizziano Perea
    */
    function Pintar_FormulacionConsultada($vectorOriginal, $datos, $datosPaciente, $datos_estacion, $reporte)
    {
        $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','ConfirmarSolicitud',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
        $salida .= "<form name='med' action='".$href."' method='POST'><br>\n";
//        $salida .= $vectorOriginal['stockbodega'];
        $suministro_med_suspendido = ModuloGetVar('app','EE_AdministracionMedicamentos','SuministroMedicamentoSuspendido');
//		$salida .= print_r($vectorOriginal);

        $med_paciente = $this->ObtenerInformacionBodegaPaciente($datosPaciente['ingreso']);

        $coninv = $this->BodegaInventario($datos[0][bodega]);
        if(count($coninv) == 0)
          $coninv1 = '0';
        else
          $coninv1 = $coninv['sw_inventario'];
        
        $coninv = $this->BodegaInventario($datos[0][bodega]);
        if(count($coninv) == 0)
          $coninv1 = '0';
        else
          $coninv1 = $coninv['sw_inventario'];

          
        foreach($vectorOriginal as $k => $vector1)
        {
            for($i=0;$i<sizeof($vector1);$i++)
            {
                $stocpacientea = $this->ObtenerBodegaPaciente($vector1[$i][ingreso], $vector1[$i][codigo_producto]);
                if (count($stocpacientea) > 0) 
                    $stocpaciente = $stocpacientea[0][stock];
                else
                    $stocpaciente = 0;

                if($i % 2)  
                    $estilo = "modulo_list_claro";  
                else  
                    $estilo = "modulo_list_oscuro";

                if($vector1[$i]['tipo_solicitud'] == "M")
                {    
                    if($i % 2)  
                        $estilo = "modulo_list_claro";  
                    else  
                        $estilo = "modulo_list_oscuro";

                }else{    
                    if($i % 2)  
                        $estilo = "modulo_list_claro";  
                    else  
                        $estilo = "modulo_list_oscuro"; }

                    $salida.="<tr class=\"$estilo\">";
                    $factor = array();
                    if($vector1[$i]['tipo_solicitud'] == "M")
                    { 
                        
                        $factor = $this->SeleccionFactorConversion($vector1[$i][codigo_producto], $vector1[$i][unidad_id], $vector1[$i][unidad_dosificacion]);

                        $salida.="<td width=\"40%\" colspan=\"3\"><B><font color=\"#990000\">".$vector1[$i]['producto']."</B> - ( ".$vector1[$i]['codigo_producto']." - ".$vector1[$i]['codigo_pos']." )</font>";



                        if(!empty($vector1[$i]['justificacion_no_pos_id']))
                        {
			    
                          $entidades=$this->ObtenerEntidad($datos_estacion['empresa_id'],$datos_estacion['centro_utilidad']);
                          $salida.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name=\"entidad\" id=\"entidad$i\">";
                          $salida.="<option value=\"\">--SELECCIONE--</option>";
                          foreach($entidades as $k => $ent)
                          {
                                
                            $salida.="<option value=\"".$ent['entidad_id']."\">".$ent['nombre_entidad']."</option>";
                          }
                          $salida.="</select>";
                            //Imprimir Justificaci???n No Pos
                            $mostrar = $reporte->GetJavaReport('app','EE_AdministracionMedicamentos','JustificacionMEDPACIENTES_NO_POS_html',array('codigo_producto'=>$vector1[$i]['codigo_producto'],'justificacion_id'=>$vector1[$i]['justificacion_no_pos_id']),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                            $nombre_funcionOP = $reporte->GetJavaFunction();
                            $salida.=$mostrar;
                            $salida.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label align=\"right\"><a href=\"javascript:$nombre_funcionOP\" onclick=\"ent=document.getElementById('entidad$i').value;xajax_GeneraReporte(ent);\"><img src=\"".GetThemePath()."/images/historial.png\" border='0' width=\"15\" title=\"Imprimir Justificaci???n\"></label></a>";
                        }


                        $salida.="</td>"; 

                        $vectorSUM[0]['codigo_producto'] =  $vector1[$i]['codigo_producto'];
                        $vectorSUM[0]['producto'] = $vector1[$i]['producto'];
                        $vectorSUM[0]['cantidad'] = floor($vector1[$i]['cantidad']);
                        $vectorSUM[0]['dosis'] = round($vector1[$i]['dosis'],2);
                        $vectorSUM[0]['unidad_dosificacion'] = $vector1[$i]['unidad_dosificacion'];
                        $vectorSUM[0]['ingreso'] = $vector1[$i]['ingreso'];
                        $vectorSUM[0]['num_formulacion'] = $vector1[$i]['num_reg_formulacion'];
                        $vectorSUM[0]['presentacion'] = $vector1[$i]['unidad'];
                        $vectorSUM[0]['cod_presentacion'] = $vector1[$i]['unidad_id'];

                        if($vector1[$i]['sw_estado'] == '1')
                        {
                            $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Control_Suministro',array("vect"=>$vectorSUM,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"tipo_solicitud"=>"M"));
                            $salida .= "<td align='center' colspan=\"3\" width=\"15%\"><a href='".$url."'><font color=\"#077325\">Registro Administraci???n Medicamentos</font></a><br>";
                            $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Detalle_Suministro',array('codigo_producto'=>$vector1[$i]['codigo_producto'], 'producto'=>$vector1[$i]['producto'], 'ingreso'=>$datosPaciente['ingreso'], 'datosPaciente'=>$datosPaciente, 'datos_estacion'=>$datos_estacion));
                            $salida .= "<a href='".$url."'><font color=\"#990000\">Notas del Medicamento</font></a>";
                            $salida .= "</td>\n";
                        }
                        else
                        {    
                            $fecha_suspendido1=explode(' ',$vector1[$i]['fecha_registro']);
                            $fecha_suspendido=explode('-',$fecha_suspendido1[0]);
                            $hora_suspendido=explode(':',$fecha_suspendido1[1]);

                            $suspencion = mktime($hora_suspendido[0],$hora_suspendido[1],$hora_suspendido[2],$fecha_suspendido[1],$fecha_suspendido[2],$fecha_suspendido[0]);
                            $hora_actual = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
                            $diferencia_horas = (($hora_actual - $suspencion) / 3600);

                            if($diferencia_horas <= $suministro_med_suspendido)
                            {
                                $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Control_Suministro',array("vect"=>$vectorSUM,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"tipo_solicitud"=>"M"));
                                $salida .= "<td align='center' colspan=\"3\" width=\"15%\"><a href='".$url."'><font color=\"#990000\"><b>Registro Administraci???n Medicamentos Suspendido</b></font></a><br>";
                                $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Detalle_Suministro',array('codigo_producto'=>$vector1[$i]['codigo_producto'], 'producto'=>$vector1[$i]['producto'], 'ingreso'=>$datosPaciente['ingreso'], 'datosPaciente'=>$datosPaciente, 'datos_estacion'=>$datos_estacion));
                                $salida .= "<font color=\"#990000\">Fecha Suspendido: ".$vector1[$i]['fecha_registro']."</font><br>";
                                $salida .= "<a href='".$url."'><font color=\"#990000\">Notas del Medicamento</font></a>";
                                $salida .= "</td>\n";
                            }
                            else
                            {
                                $salida .= "<td align='center' colspan=\"3\" width=\"15%\"><lable class=\"label_mark\">Medicamento Suspendido</label><br>";
                                $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Detalle_Suministro',array('codigo_producto'=>$vector1[$i]['codigo_producto'], 'producto'=>$vector1[$i]['producto'], 'ingreso'=>$datosPaciente['ingreso'], 'datosPaciente'=>$datosPaciente, 'datos_estacion'=>$datos_estacion));
                                $salida .= "<font color=\"#990000\">Fecha Suspendido: ".$vector1[$i]['fecha_registro']."</font><br>";
                                $salida .= "<a href='".$url."'><font color=\"#990000\">Notas del Medicamento</font></a>";
                                $salida .= "</td>\n";
                            }
                        }
                    }
                    else
                    {
                        if($vector1[$i]['num_mezcla'] != $vector1[$i-1]['num_mezcla'])
                        {
                            $salida.="<td width=\"40%\" colspan=\"3\">";
                            $w = 0;
                            for($j=0; $j<sizeof($vector1); $j++)
                            {
                                if($vector1[$i]['num_mezcla'] == $vector1[$j]['num_mezcla'])
                                {
                                    $salida.="<B>".$vector1[$j]['producto']."</B> - ( ".$vector1[$j]['codigo_producto']." - <label class=\"label_mark\">".$vector1[$j]['dosis']." ".$vector1[$j]['unidad_suministro']."</label>)<br>";
                                    $vectorSUM[$w]['codigo_producto'] =  $vector1[$j]['codigo_producto'];
                                    $vectorSUM[$w]['producto'] = $vector1[$j]['producto'];
                                    $vectorSUM[$w]['cantidad'] = round($vector1[$j]['cantidad'],2);
                                    $vectorSUM[$w]['dosis'] = round($vector1[$j]['dosis'],2)." ".$vector1[$j]['unidad_suministro'];
                                    $vectorSUM[$w]['unidad_dosificacion'] = "".round($vector1[$j]['volumen_infusion'],2)." ".strtoupper($vector1[$j]['unidad_volumen'])."";
                                    $vectorSUM[$w]['ingreso'] = $vector1[$j]['ingreso'];
                                    $vectorSUM[$w]['num_formulacion'] = $vector1[$j]['num_mezcla'];
                                    $vectorSUM[$w]['presentacion'] = $vector1[$j]['unidad'];
                                    $vectorSUM[$w]['cod_presentacion'] = $vector1[$j]['unidad_id'];
                                    $w++;
                                }
                            }
                            $salida.="</td>";

                            if($vector1[$i]['sw_estado'] == '1')
                            {
                              $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Control_Suministro',array("vect"=>$vectorSUM,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"tipo_solicitud"=>"S"));
                              $salida .= "<td align='center' colspan=\"3\" width=\"15%\"><a href='".$url."'><font color=\"#077325\">Registro Administraci???n Medicamentos</font></a><br>";
                              $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Detalle_Suministro',array('codigo_producto'=>$vector1[$i]['codigo_producto'], 'producto'=>$vector1[$i]['producto'], 'ingreso'=>$datosPaciente['ingreso'], 'datosPaciente'=>$datosPaciente, 'datos_estacion'=>$datos_estacion));
                              $salida .= "<a href='".$url."'><font color=\"#990000\">Notas del Medicamento</font></a>";
                              $salida .= "</td>\n";
                            }
                            else
                            {
                              $salida .= "<td align='center' colspan=\"3\" width=\"15%\"><lable class=\"label_mark\">Medicamento Suspendido</label><br>";
                              $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Detalle_Suministro',array('codigo_producto'=>$vector1[$i]['codigo_producto'], 'producto'=>$vector1[$i]['producto'], 'ingreso'=>$datosPaciente['ingreso'], 'datosPaciente'=>$datosPaciente, 'datos_estacion'=>$datos_estacion));
                              $salida .= "<a href='".$url."'><font color=\"#990000\">Notas del Medicamento</font></a>";
                              $salida .= "</td>\n";
                            }
                        }
                    }
                    
                    if($vector1[$i]['sw_estado'] == '1')
                    {
                        $cadeen = "";
                        if ($stocpaciente > 0 and $coninv1 == '0'){
                            $cadeen = " disabled=true ";
                        }
                        
                        if($vector1[$i]['tipo_solicitud'] == "M")
                        { 
						
//                            $salida.="  <td width=\"5%\" align=\"center\"><input id=op$i type=checkbox name=op[] value=".$vector1[$i][num_reg].",".$vector1[$i][tipo_solicitud]." ".$cadeen." onclick = \" ValidarEntregaM(eval(cantidad[$i].value),eval(cantidadval$i.value),eval(solicitadaval$i.value), cantidad[$i].id);\" ></td>"; 
//                            $salida.="  <td width=\"5%\" align=\"center\"><input id=op$i type=checkbox name=op[] value=".$vector1[$i][num_reg].",".$vector1[$i][tipo_solicitud]." ".$cadeen." onclick = \" ValidarEntregaM(eval(cantidadA$i.value),eval(cantidadval$i.value),eval(solicitadaval$i.value), cantidadA$i.id);\" ></td>"; 
                          if ($coninv1 == '0'){
                            $salida.="  <td width=\"5%\" align=\"center\"><input id=op$i type=checkbox name=op[] value=".$vector1[$i][num_reg].",".$vector1[$i][tipo_solicitud]." ".$cadeen." onclick = \" ValidarEntregaM(eval(cantidadA$i.value),eval(cantidadval$i.value),eval(solicitadaval$i.value), cantidadA$i.id);\" ></td>"; 
                          }else{
                            $salida.="  <td width=\"5%\" align=\"center\"><input id=op$i type=checkbox name=op[] value=".$vector1[$i][num_reg].",".$vector1[$i][tipo_solicitud]." ".$cadeen." ></td>"; 
                          }
                        }
                        else
                        { 
                            if($vector1[$i]['num_mezcla'] != $vector1[$i-1]['num_mezcla'] AND $vector1[$i]['sw_estado'] == 1)
                            {
                                $salida.="  <td width=\"5%\" align=\"center\"><input id=op$i type=checkbox name=op[] value=".$vector1[$i][num_mezcla].",".$vector1[$i][tipo_solicitud]." ".$cadeen."></td>"; 
                            }
                        }
                    }
                    else
                    {
                        if($vector1[$i]['tipo_solicitud'] == "M")
                        { 
                            $salida.="  <td align=\"center\" width=\"5%\">&nbsp;</td>";
                        }else
                        {
                            if($vector1[$i]['num_mezcla'] != $vector1[$i-1]['num_mezcla'])
                            {
                                $salida.="  <td width=\"5%\" align=\"center\">&nbsp;</td>"; 
                            }
                        }
                    }

                    if($vector1[$i]['tipo_solicitud'] == "M")
                    {
                        $salida.="<tr class=\"$estilo\">";
                        $salida.="<td colspan=\"6\">";
                        $salida.="<table>";

                        $salida.="<tr class=\"$estilo\">";
                        $salida.="<td colspan = 3 align=\"left\" width=\"9%\"><b>Via de Administracion:</b> ".$vector1[$i][via_administracion]."</td>";
                        $salida.="</tr>";

                        $salida.="<tr class=\"$estilo\">";
                        $salida.="<td align=\"left\" width=\"9%\"><b>Dosis:</b></td>";
                        $e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
                        if($e==1)
                        {
                          $salida.="  <td align=\"left\" width=\"10%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
                        }
                        else
                        {
                          $salida.="  <td align=\"left\" width=\"10%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                        }

                        $salida.="<td align=\"left\" width=\"20%\">".$vector1[$i][frecuencia]."</td>";
                        $salida.="</tr>";
                        if($vector1[$i]['sw_estado'] == '1')
                        {
                            $_cantidad = $this->BusquedaProducto_Soluciones($datosPaciente['ingreso'], $vector1[$i]['codigo_producto']);

                            $undos = explode(" ", $vector1[$i][frecuencia]);
                            if (count($undos) == 3){
							  
                              $unidaddos = $undos[2];
                              $frecuenci = $undos[1];
                              $cantidad = $vector1[$i][cantidad];

                              $homi = 0;
                              if($unidaddos == "Hora(s)"){
                                $cantidad = $vector1[$i][dosis] * 24/$frecuenci; 
                                $homi = 1;
                              }else{
                                if($unidaddos == "Minuto(s)"){
                                  $cantidad = $vector1[$i][dosis] * 24/($frecuenci/60);
                                  $homi = 1;
                                }else{
                                  $homi = 0;
                                  $cantidad = floor(($vector1[$i][cantidad]/$vector1[$i][dias_tratamiento]));
                                }
                              }
                              $factor_conversion = $this->ObtenerFactorConv($vector1[$i]['codigo_producto'], $vector1[$i][unidad_dosificacion]);
                              if (count($fac_conversion[$vector1[$i]['codigo_producto']]['factor_conversion']) > 0){
                                if ($homi == 1){
//									$salida.= $factor_conversion[0]['factor_conversion']." - ".$cantidad." - ".$vector1[$i][unidad_dosificacion]." - ".$vector1[$i]['unidad_id'];
								
                                  $cantidad = $cantidad/$fac_conversion[$vector1[$i]['codigo_producto']]['factor_conversion'];

                                  $valor = round($cantidad);
                                  if($valor < $cantidad) $valor++;

                                  $cantidad = $valor;
                                  $CanTot = $cantidad;
								  
                                }else{
                                  $CanTot = $cantidad;
                                }
                              }else{
                                $CanTot = floor(($vector1[$i][cantidad]/$vector1[$i][dias_tratamiento]));
                              }
/*
							  
                              if (count($factor_conversion) > 0){
                                if ($homi == 1){
//									              $salida.= $factor_conversion[0]['factor_conversion']." - ".$cantidad." - ".$vector1[$i][unidad_dosificacion]." - ".$vector1[$i]['unidad_id'];
								
                                  $cantidad = $cantidad/$factor_conversion[0]['factor_conversion'];

                                  $valor = round($cantidad);
                                  if($valor < $cantidad) $valor++;

                                  $cantidad = $valor;
                                  $CanTot = $cantidad;
								  
                                }else{
                                  $CanTot = $cantidad;
                                }
                              }else{
                                $CanTot = floor(($vector1[$i][cantidad]/$vector1[$i][dias_tratamiento]));
                              }

*/							  
                            }else{
                              $CanTot = floor(($vector1[$i][cantidad]/$vector1[$i][dias_tratamiento]));
                            }
                        }

                        $salida.="<tr class=\"$estilo\">";
                        $salida.="  <td align=\"left\" width=\"9%\"><b>Cantidad Diaria: </b></td>";
                        $salida.="  <td align=\"left\" width=\"9%\"><b>".$CanTot." ".$vector1[$i][unidad]." por ".$vector1[$i][contenido_unidad_venta]."</b></td>";
                        $salida.="</tr>";

                        $salida.="<tr class=\"$estilo\">";
                        $salida.="  <td align=\"left\" width=\"9%\"><b>Cantidad Total Tratamiento(".$vector1[$i][dias_tratamiento]." dias):</b></td>";
                        $e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
                        //dias_tratamiento
                        if($vector1[$i][contenido_unidad_venta])
                        {
                            if($e==1)
                            {
//                                $salida.="  <td colspan=\"2\" align=\"left\">".floor($vector1[$i][cantidad])." ".$vector1[$i][unidad]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                                $salida.="  <td colspan=\"2\" align=\"left\">".($CanTot*$vector1[$i][dias_tratamiento])." ".$vector1[$i][unidad]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                            }
                            else
                            {
                                $salida.="  <td colspan=\"2\" align=\"left\">".$vector1[$i][cantidad]." ".$vector1[$i][unidad]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                            }
                        }
                        else
                        {
                            if($e==1)
                            {
                            $salida.="  <td colspan=\"2\" align=\"left\">".floor($vector1[$i][cantidad])." ".$vector1[$i][unidad]."</td>";
                            }
                            else
                            {
                                $salida.="  <td colspan=\"2\" align=\"left\">".$vector1[$i][cantidad]." ".$vector1[$i][unidad]."</td>";
                            }
                        }
                        $salida.="</tr>";
                        if($vector1[$i][observacion] != "")
                        {
                            $salida.="<tr class=\"$estilo\">";
                            $salida.="  <td align=\"left\" width=\"9%\"><b>Observaci???n: </b></td>";
                            $salida.="  <td align=\"left\" colspan=\"2\">".$vector1[$i][observacion]."</td>";
                            $salida.="</tr>";
                        }

                        $Profesional = $this->ProfesionalFormulacion_Medicamento($vector1[$i][usuario_id]);
                        $salida.="<tr class=\"$estilo\">";
                        $salida.="<td align=\"left\" width=\"9%\"><b>Formul???:</b></td>";
                        $salida.="<td align=\"left\" colspan=\"2\">".$Profesional."</td>";
                        $salida.="</tr>";
                        $salida.="</table>";
                        $salida.="</td>";
                        
                        if($vector1[$i]['sw_estado'] == '1')
                        {

//                            $salida.="<td align=\"left\" width=\"20%\">".$CanTota."-".floor($vector1[$i][cantidad]/$vector1[$i][dias_tratamiento])." | ".$vector1[$i][dosis]." - ".$frecuenci." - ".$factor_conversion[0]['factor_conversion']." - ".$CanTot."</td>";
/*                            $cantidad = $vector1[$i][cantidad];  
                            if ($vector1[$i][dias_tratamiento]>0)
                                $CanTot = floor(($vector1[$i][cantidad]/$vector1[$i][dias_tratamiento]));
                            else
                                $CanTot = 0;
*/
//Actualizacion Noviembre 17 de 2011
//                            $salida.="  <td align=\"center\" width=\"5%\"><input type='text' class='input-text' size='4' maxlength='4' name=cantidad[$i] value='".$CanTot."' readonly><input type=\"hidden\" name=\"cantidadFormulada[]\" value='".$CanTot."'></td>";
//                            $salida.="  <td align=\"center\" width=\"5%\"><input type='text' class='input-text' size='4' maxlength='4' name=cantidad[$i] value='".$CanTot."' onblur = \"if(eval(this.value) > eval(cantidadval$i.value)) alert('La cantidad no puede ser superior a la solicitada'); if(eval(this.value) > eval(cantidadval$i.value)) this.value = cantidadval$i.value;\"><input type=\"text\" id=\"cantidadval$i\" value='".$CanTot."'><input type=\"text\" id=\"solicitadaval$i\" value='".$vector1[$i][solicitadoval]."'><input type=\"text\" name=\"cantidadFormulada[]\" value='".$CanTot."'></td>";
//                            $salida.="  <td align=\"center\" width=\"5%\"><input type='text' class='input-text' size='4' maxlength='4' name=cantidad[$i] value='".$CanTot."' onblur = \" alert((eval(cantidadval$i.value) + eval(solicitadaval$i.value))) \"><input type=\"text\" id=\"cantidadval$i\" value='".$CanTot."'><input type=\"text\" id=\"solicitadaval$i\" value='".$vector1[$i][solicitadoval]."'><input type=\"text\" name=\"cantidadFormulada[]\" value='".$CanTot."'></td>";
//                            $salida.="  <td align=\"center\" width=\"5%\"><input type='text' onkeypress=\"return acceptNum(event);\" onkeyup=\"return acceptNum(event);\" class='input-text' size='4' maxlength='4' name=cantidad[$i] id=cantidadA$i value='".$CanTot."' onblur = \" ValidarEntregaM(eval(this.value),eval(cantidadval$i.value),eval(solicitadaval$i.value), this.id);\"><input type=\"hidden\" id=\"cantidadval$i\" value='".$CanTot."'><input type=\"hidden\" id=\"solicitadaval$i\" value='".$vector1[$i][solicitadoval]."'><input type=\"hidden\" name=\"cantidadFormulada[]\" value='".$CanTot."'></td>";
                          if ($coninv1 == '0'){
                            $salida.="  <td align=\"center\" width=\"5%\"><input type='text' onkeypress=\"return acceptNum(event);\" onkeyup=\"return acceptNum(event);\" class='input-text' size='4' maxlength='4' name=cantidad[$i] id=cantidadA$i value='".$CanTot."' onblur = \" ValidarEntregaM(eval(this.value),eval(cantidadval$i.value),eval(solicitadaval$i.value), this.id);\"><input type=\"hidden\" id=\"cantidadval$i\" value='".$CanTot."'><input type=\"hidden\" id=\"solicitadaval$i\" value='".$vector1[$i][solicitadoval]."'><input type=\"hidden\" name=\"cantidadFormulada[]\" value='".$CanTot."'></td>";
                          }else{
                            $salida.="  <td align=\"center\" width=\"5%\"><input type='text' onkeypress=\"return acceptNum(event);\" onkeyup=\"return acceptNum(event);\" class='input-text' size='4' maxlength='4' name=cantidad[$i] id=cantidadA$i value='".$CanTot."' ><input type=\"hidden\" id=\"cantidadval$i\" value='".$CanTot."'><input type=\"hidden\" id=\"solicitadaval$i\" value='".$vector1[$i][solicitadoval]."'><input type=\"hidden\" name=\"cantidadFormulada[]\" value='".$CanTot."'></td>";
                          }
                            
                        }
                        else
                        {
                            $salida.="  <td align=\"center\" width=\"5%\">&nbsp;</td>";
                        }
                        $salida.="</tr>";
                    }
                    else
                    {
                        if($vector1[$i]['num_mezcla'] != $vector1[$i-1]['num_mezcla'])
                        {
                            $salida.="<tr class=\"$estilo\">";
                            $salida.="<td colspan=\"6\">";
                            $salida.="<table>";

                            $salida.="<tr class=\"$estilo\">";
                            $salida.="  <td align=\"left\" width=\"42%\"><b>Cantidad Total:</b></td>";
                            $salida.="  <td align=\"left\" colspan=\"2\">".floor($vector1[$i][cantidad])." SOLUCION(ES)</td>";
                            $salida.="</tr>";

                            $salida.="<tr class=\"$estilo\">";
                            $salida.="  <td align=\"left\" width=\"42%\"><b>Volumen de Infusi???n:</b></td>";
                            $salida.="  <td align=\"left\" colspan=\"2\">".floor($vector1[$i][volumen_infusion])." ".strtoupper($vector1[$i][unidad_volumen])."</td>";
                            $salida.="</tr>";

                            if($vector1[$i][observacion] != "")
                            {
                                $salida.="<tr class=\"$estilo\">";
                                $salida.="  <td align=\"left\" width=\"9%\"><b>Observaci???n:</b></td>";
                                $salida.="  <td align=\"left\" colspan=\"2\">".$vector1[$i][observacion]."</td>";
                                $salida.="</tr>";
                            }

                            $Profesional = $this->ProfesionalFormulacion_Medicamento($vector1[$i][usuario_id]);
                            $salida.="<tr class=\"$estilo\">";
                            $salida.="<td align=\"left\" width=\"9%\"><b>Formul???:</b></td>";
                            $salida.="<td align=\"left\" colspan=\"2\">".$Profesional."</td>";
                            $salida.="</tr>";
                            $salida.="</table>";
                            $salida.="</td>";

                            if($vector1[$i]['sw_estado'] == '1')
                            {
                                $salida.="  <td align=\"center\" width=\"5%\"><input type='text' class='input-text' size='4' maxlength='4' name=cantidadSol[$i] value='".floor($vector1[$i][cantidad])."'><input type=\"hidden\" name=\"cantidadFormuladaS[$i]\" value='".floor($vector1[$i][cantidad])."'></td>";
                            }
                            else
                            {
                                $salida.="  <td align=\"center\" width=\"5%\">&nbsp;</td>";
                            }
                            $salida.="</tr>";
                        }
                    }

                    if($vector1[$i]['tipo_solicitud'] == "M")
                    {
                        /**************************************************************************************
                        IMPLEMENTACION NUEVA VERSION BODEGA PACIENTE 
                        **************************************************************************************/
                        // Informacion de Conteo de medicamentos Solicitados para validaciones.
                        //$_BodegaPaciente = $this->GetCantidades_BodegaPaciente($datosPaciente['ingreso'],$vector1[$i]['codigo_producto']);

                        
                        
                        $_BodegaPaciente = $med_paciente[$vector1[$i]['codigo_producto']];
                        $_StockPatient = $_BodegaPaciente[stockbodega];
                        // 1. Sumatoria de las Cantidades Confirmadas.                    
                        $_StockGeneral = round($_BodegaPaciente[stock],5);

                        // 2. Sumatoria despues de Devolucion.
                        $_Cantidades_Devolucion = round($_BodegaPaciente[cantidad_en_devolucion],5);
                        $_StockGeneral = $_StockGeneral - $_Cantidades_Devolucion;

                        $_StockVisual = round($_BodegaPaciente[total_recibido],5);
                        $_StockVisual = $_StockVisual - ($_Cantidades_Devolucion + round($_BodegaPaciente[total_devuelto],5));

                        // 3. Sumatoria de Cantidades pendientes por Confirmar.
                        $_CantidadenSolicitud = round($_BodegaPaciente[cantidad_en_solicitud],5);

                        // 4. Sumatoria de Cantidades Suministradas.
                        $_SuministrosGrales = round($_BodegaPaciente[total_suministrado],5);

                        // 5. Sumatoria de Cantidades Suministradas.
                        $_SuministrosPciales = $this->SUMTotal_Suministro($vector1[$i]['codigo_producto'], $datosPaciente['ingreso']);
                        $_SuministrosPciales = round($_SuministrosPciales,5);

                        // 6. Cantidades reales en la Bodega del Paciente.
                        $_StockPaciente = round($_BodegaPaciente[stock_almacen],5);
                        if($_StockPaciente == 0)
                        { $_StockPaciente = round($_BodegaPaciente[stock_paciente],5); }

                        $_StockPaciente = $_StockPaciente - round($_BodegaPaciente[cantidad_en_devolucion],5);
                        $_Desechada = round($_BodegaPaciente['total_perdidas'],5);

                        /**************************************************************************************
                        IMPLEMENTACION NUEVA VERSION BODEGA PACIENTE 
                        **************************************************************************************/
                        //REEMPLAZADO POR Jonier Murillo Hurtado
/*
                        if($factor['sw_unidad_minima'] == null or $factor['sw_unidad_minima'] == 0){
                            $_PorSuministar = round($_StockPaciente*$factor['factor_conversion'],5);
                        }else{
                            $_PorSuministar = round($_StockPaciente*$factor['sw_unidad_minima'],5);
                        }
*/

                        $_PorSuministar = round($_StockPaciente*$factor['factor_conversion'],5);
                        $salida.="<tr align=\"center\" class=\"$estilo\">";
                        $salida.="<td colspan=\"7\" class=\"$estilo\" align=\"center\" width=\"100%\"><b>CANTIDADES CONFIRMADAS:</b><input type=\"hidden\" name=\"sumatoriaSol[$i]\" value=\"".$_StockGeneral."\">";
                        $salida.="&nbsp;&nbsp;<b><font class=\"label_mark\">".$_StockVisual." Unds.</font></b>";
                        $salida.="&nbsp;&nbsp;&nbsp;&nbsp;";
                        $salida.="<b>CANTIDADES EN BODEGA PACIENTE: &nbsp;&nbsp;<input type=\"hidden\" name=\"Pendientes[$i]\" value=\"".$_CantidadenSolicitud."\"></b>";
                        $salida.="<font class=\"label_mark\">".$_StockPaciente." Unds.</font>";
                        $salida.="&nbsp;&nbsp;&nbsp;&nbsp;";
                        $salida.="<b>CANTIDADES SUMINISTRADAS: &nbsp;&nbsp;<input type=\"hidden\" name=\"Suministradas[$i]\" value=\"".$_SuministrosPciales."\"></b>";
                        $salida.="<font class=\"label_mark\">".$_SuministrosGrales." Unds.</font>";
                        $salida.="&nbsp;&nbsp;&nbsp;&nbsp;";
                        $salida.="<b>CANTIDADES DESECHADAS: &nbsp;&nbsp;</b>";
                        $salida.="<font class=\"label_mark\">".$_Desechada." Unds.</font>";
                        $salida.="&nbsp;&nbsp;&nbsp;&nbsp;";
                        $salida.="<b>FALTANTE POR SUMINISTRAR: &nbsp;&nbsp;</b>";
                        $salida.="<font class=\"label_mark\">".$_PorSuministar." ".$vector1[$i]['unidad_dosificacion']."</font>";
                        $salida.="</td>";
                        $salida.="</tr>";
                    }
                    $salida.="<tr align=\"left\" class=\"$estilo\">";
                    $salida .= "<td align='left' colspan=\"7\" width=\"15%\"><img src=\"".GetThemePath()."/images/pconsultar.png\" border=0 width=15 heigth=15><a   onclick=\"xajax_Medicamentos_Despacho('".$vector1[$i]['ingreso']."', '".$vector1[$i]['tipo_solicitud']."','".$vector1[$i]['codigo_producto']."')\" ><font color=\"#077325\">MEDICAMENTOS DESPACHADOS</font></a><br>";
                    $salida .= "</td>\n";
                    $salida.="</tr>";

            } //fin del for muy importante
        }
        return $salida;
    }

    /**
    * Funcion donde se crea la forma para el suministro de insumos
    *
    * @param array $vectorOriginal
    * @param array $datos
    * @param array $datosPaciente
    * @param array $datos_estacion
    * @param object $reporte
    *
    * @return string 
    */
    function Pintar_FormulacionConsultadaInsumos($vectorOriginal, $datos, $datosPaciente, $datos_estacion, $reporte)
    {
      $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','ConfirmarSolicitud',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
      $salida .= "<form name='med' action='".$href."' method='POST'><br>\n";
      
      foreach($vectorOriginal as $k => $vector1)
      {
        for($i=0;$i<sizeof($vector1);$i++)
        {
          $estilo = ($i%2)? 'modulo_list_oscuro':'modulo_list_claro';
                    
          $salida .= "<tr class=\"$estilo\">";
          $salida .= "  <td width=\"40%\" colspan=\"3\">\n";
          $w = 0;

          $salida .= "<b><font class=\"normal_10AN\">".$vector1[$i]['descripcion']."</font></b> - ( ".$vector1[$i]['codigo_producto']." - <label class=\"label_mark\">".$vector1[$i]['dosis']." ".$vector1[$i]['unidad_suministro']."</label>)<br>";
					$vectorSUM[$w]['codigo_producto'] =  $vector1[$i]['codigo_producto'];
          $vectorSUM[$w]['producto'] = $vector1[$i]['producto'];
          $vectorSUM[$w]['cantidad'] = round($vector1[$i]['cantidad'],2);
          $vectorSUM[$w]['dosis'] = round($vector1[$i]['dosis'],2)." ".$vector1[$i]['unidad_suministro'];
          $vectorSUM[$w]['unidad_dosificacion'] = "".round($vector1[$i]['volumen_infusion'],2)." ".strtoupper($vector1[$i]['unidad_volumen'])."";
          $vectorSUM[$w]['ingreso'] = $vector1[$i]['ingreso'];
          $vectorSUM[$w]['num_formulacion'] = $vector1[$i]['num_mezcla'];
          $vectorSUM[$w]['presentacion'] = $vector1[$i]['unidad'];
          $vectorSUM[$w]['cod_presentacion'] = $vector1[$i]['unidad_id'];
          $salida.="</td>";
     
    			$url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Control_SuministroInsumos',array('datos_producto[0]'=>$vector1[$i],"vect"=>$vectorSUM,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"tipo_solicitud"=>"I"));
    			$salida .= "  <td align='center' colspan=\"3\" width=\"15%\" height=\"16\">\n";
          $salida .= "    <a href='".$url."'>\n";
          $salida .= "      <font color=\"#077325\">Registro Administraci???n Insumos</font>\n";
          $salida .= "    </a>\n";
    			$salida .= "  </td>\n";
    			$salida .= "</tr>\n";

          /**************************************************************************************
          IMPLEMENTACION NUEVA VERSION BODEGA PACIENTE 
          **************************************************************************************/
          // Informacion de Conteo de medicamentos Solicitados para validaciones.
          $_BodegaPaciente = $this->GetCantidades_BodegaPaciente($datosPaciente['ingreso'],$vector1[$i]['codigo_producto']);

          // 1. Sumatoria de las Cantidades Confirmadas.                    
          $_StockGeneral = round($_BodegaPaciente[stock],5);

          // 2. Sumatoria despues de Devolucion.
          $_Cantidades_Devolucion = round($_BodegaPaciente[cantidad_en_devolucion],5);
          $_StockGeneral = $_StockGeneral - $_Cantidades_Devolucion;

          $_StockVisual = round($_BodegaPaciente[total_recibido],5);
          $_StockVisual = $_StockVisual - ($_Cantidades_Devolucion + round($_BodegaPaciente[total_devuelto],5));

          // 3. Sumatoria de Cantidades pendientes por Confirmar.
          $_CantidadenSolicitud = round($_BodegaPaciente[cantidad_en_solicitud],5);

          // 4. Sumatoria de Cantidades Suministradas.
          $_SuministrosGrales = round($_BodegaPaciente[total_suministrado],5);

          // 5. Sumatoria de Cantidades Suministradas.
          $_SuministrosPciales = $this->SUMTotal_Suministro($vector1[$i]['codigo_producto'], $datosPaciente['ingreso']);
          $_SuministrosPciales = round($_SuministrosPciales,5);

          // 6. Cantidades reales en la Bodega del Paciente.
          $_StockPaciente = round($_BodegaPaciente[stock_almacen],5);
          if($_StockPaciente == 0)
          { $_StockPaciente = round($_BodegaPaciente[stock_paciente],5); }

          $_StockPaciente = $_StockPaciente - round($_BodegaPaciente[cantidad_en_devolucion],5);

          /**************************************************************************************
          IMPLEMENTACION NUEVA VERSION BODEGA PACIENTE 
          **************************************************************************************/
          
          $salida .= "<tr align=\"center\" class=\"$estilo\">";
          $salida .= "  <td colspan=\"6\" class=\"$estilo\" align=\"center\" width=\"100%\">\n";
          $salida .= "    <b>CANTIDADES CONFIRMADAS:</b><input type=\"hidden\" name=\"sumatoriaSol[$i]\" value=\"".$_StockGeneral."\">&nbsp;&nbsp;";
          $salida .= "    <b><font class=\"label_mark\">".$_StockVisual." Unds.</font></b>&nbsp;&nbsp;";
          $salida .= "    <b>CANTIDADES EN BODEGA PACIENTE: &nbsp;&nbsp;<input type=\"hidden\" name=\"Pendientes[$i]\" value=\"".$_CantidadenSolicitud."\"></b>";
          $salida .= "    <font class=\"label_mark\">".$_StockPaciente." Unds.</font>&nbsp;&nbsp;";
          $salida .= "    <b>CANTIDADES SUMINISTRADAS: &nbsp;&nbsp;<input type=\"hidden\" name=\"Suministradas[$i]\" value=\"".$_SuministrosPciales."\"></b>";
          $salida .= "    <font class=\"label_mark\">".$_SuministrosGrales." Unds.</font>&nbsp;&nbsp;&nbsp;&nbsp;";
          $salida .= "  </td>";
          $salida .= "</tr>";
        } //fin del for muy importante
      }
      $salida.="</form>";
     	return $salida;
    }  
     /*
     * Forma que permite seleccionar alguna de las transacciones referentes a los
     * medicamentos.
     *
     * @autor Tizziano Perea
     */
     function FrmAciones_Medicamentos($datos_estacion,$datosPaciente,$vistoOk)
     {
          $datosBodega = $this->GetEstacionBodega($datos_estacion,1);
          
          $ConteoO = $this->GetInformacionProductosBodegaPaciente_X_Recibir($datosPaciente['ingreso']);
          
          if($ConteoO == 0)
          {
            $enlacepend = "Confirmaci???n de Despacho";
            $imgpend='';
            $sw=0;
          }
          else
          {
            $_SESSION['Interna'] = true;
            $enlacepend = "<a href=\"".ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$datos_estacion,'switche'=>'despacho',"datosPaciente"=>$datosPaciente,"cargar"=>'admin')) ."\" target=\"Contenido\">Confirmacion Despacho: Insumos y Medicamentos Pendientes</a>";
            $imgpend = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
          }
		      $insumos = "Agregar Insumos";
          $paquetes = "Agregar Paquetes";   

          if(empty($vistoOk))
          {
            $insumos = "<a href=\"".ModuloGetURL('app','EE_AdministracionMedicamentos','user','AgregarInsumos_A_Paciente',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente))."\" target=\"Contenido\">".$insumos."</a>";
            $paquetes = "<a href=\"".ModuloGetURL('app','EE_AdministracionMedicamentos','user','FormaAgregarPaquetesPaciente',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente))."\" target=\"Contenido\">".$paquetes."</a>";
          }
          
          $imgism = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
          $imgpq = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
              
          $this->salida .= "<br><table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
          $this->salida .= "	<tr class=\"modulo_table_title\">\n";
          $this->salida .= "		<td width=\"50%\">Insumos Y Medicamentos</td>\n";
          $this->salida .= "		<td width=\"50%\">Devoluciones</td>\n";
          $this->salida .= "		</tr>\n";          
          $this->salida .= "	<tr class=\"modulo_list_claro\">\n";
          $this->salida .= "		<td width=\"50%\">$imgpend&nbsp;$enlacepend</td>\n";
          
          unset($_SESSION['ESTACION']['VECTOR_DEV_INS']['BODEGA_ESTACION']);
		$conteoI=$this->GetInformacionProductos_BodegaPaciente($datosPaciente['ingreso'],'I');
          if($conteoI==1)
          {
               $devo_i = "<a href=\"".ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionInsumos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$datosBodega))."\" target=\"Contenido\">Devoluci???n Insumos</a>";
          }else{ $devo_i = "Devoluci???n Insumos";}

          $this->salida .= "		<td width=\"50%\">$img&nbsp;$devo_i</td>\n";
          $this->salida .= "		</tr>\n";     
          $this->salida .= "	<tr class=\"modulo_list_oscuro\">\n";
          
          $productosPen = "<a href=\"".ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','Frm_SelectOption',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente)) ."\" target=\"Contenido\">Productos Pacientes</a>";
          $imgProductos = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
          if(!$_SESSION['Interna'])
          { $_SESSION['Interna'] = true; }
          $this->salida .= "		<td width=\"50%\">$imgProductos&nbsp;$productosPen</td>\n";
          
      $conteo=$this->GetInformacionProductos_BodegaPaciente($datosPaciente['ingreso'],'M');
      if($conteo==1)
      {
           $devo_m = "<a href=\"".ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$datosBodega))."\" target=\"Contenido\">Devoluci???n Medicamentos</a>";
      }else{ $devo_m = "Devoluci???n Medicamentos";}
      $this->salida .= "		<td width=\"50%\">$img&nbsp;$devo_m</td>\n";
      $this->salida .= "		</tr>\n";     
      $this->salida .= "	<tr class=\"modulo_table_title\">\n";
      $this->salida .= "		<td width=\"50%\">Solicitar Insumos Para Pacientes</td>\n";
      $this->salida .= "		<td rowspan=\"3\" align=\"center\" class=\"modulo_list_claro\" width=\"50%\"><b>ESTACI&Oacute;N DE ENFERMERIA : ".$datos_estacion['estacion_descripcion']."</b></td>\n";
      $this->salida .= "		</tr>\n";     
      $this->salida .= "	<tr class=\"modulo_list_claro\">\n";
      $this->salida .= "		<td width=\"50%\">$imgism&nbsp;$insumos</td>\n";
      $this->salida .= "		</tr>\n";     
      $this->salida .= "	<tr class=\"modulo_list_oscuro\">\n";
      $this->salida .= "		<td width=\"50%\">$imgpq&nbsp;$paquetes</td>\n";
      $this->salida .= "		</tr>\n";     
      $this->salida .= "	</table><br>\n";
     	return true;
    }
     /*
     * Funcion que solicita los medicamentos enviados al
     * paciente, para estos ser despachados desde bodega
     *
     * Adaptacion Tizziano Perea
     */
     function ConfirmarSolicitud()
     {
          // Vector que contiene el detalle de los productos de la solicitud
          unset($_SESSION['VECTOR_DETALLE_PRODUCTOS']);
//          print_r($_REQUEST);
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $bodega = $_REQUEST['bodega'];
          
          if(!$this->GetUserPermisos('52'))
          {
              $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"datosPaciente"=>$datosPaciente));
              $titulo='VALIDACION DE PERMISOS';
              $mensaje='El usuario no tiene permiso para : 	Solicitud de Insumos y Medicamentos (Pacientes) [52]';
               $this->frmMSG($url, $titulo, $mensaje);
              return true;
          }
		  
		  // Opciones de la solicitud
          $op = $_REQUEST['op'];
          // Cantidades de la solicitud
          $cant = $_REQUEST['cantidad'];
          $cantidadFormulada = $_REQUEST['cantidadFormulada'];
          $sumatoriaSol = $_REQUEST['sumatoriaSol'];
          $CanPendientes = $_REQUEST['Pendientes'];
          $CanSuministradas = $_REQUEST['Suministradas'];
          
          // Cantidad Soluciones
          $cantidadSol = $_REQUEST['cantidadSol'];
          $cantidadFormuladaS = $_REQUEST['cantidadFormuladaS'];
          
          // Vector de productos que seran solicitados (Viene de Formulacion)
          $productos = $_SESSION['VECTOR_MEDICAMENTOS_&_SOLUCIONES'];
          // Reestablesco la variable que contiene los medicamentos.
          unset($_SESSION['VECTOR_MEDICAMENTOS_&_SOLUCIONES']);
//          $this->salida .= print_r($_REQUEST);
          for($i=0;$i<sizeof($op);$i++)
          {
               if($op[$i])
               {
                    foreach($productos as $k => $vector)
                    {
                         for($x=0;$x<sizeof($vector);$x++)
                         {
                              $datos_op = explode(",",$op[$i]);
                              // Validaciones para los Medicamentos
                              if($datos_op[1] == "M")
                              {
                                   // Establecer $CanSuministradas
                                   if($datos_op[0] == $vector[$x][num_reg] AND $vector[$x][tipo_solicitud] == "M")
                                   {
                                        if($cantidadFormulada[$x] < $cantidadesGlobales)
                                        {
                                             $this->frmError["MensajeError"]="LAS CANTIDADES SOLICITADAS SON MAYORES QUE LAS CANTIDADES FORMULADAS.";
                                             $this->CallFrmMedicamentos($datosPaciente,$datos_estacion);
                                             return true;
                                        }
                                   }
                              }
                         }
                         
                         for($z=0;$z<sizeof($vector);$z++)
                         {
                              $datos_op = explode(",",$op[$i]);
                              // Validaciones para las Mezclas.
                              if($datos_op[1] == "S")
                              {
                                   if($datos_op[0] == $vector[$z][num_mezcla] AND $vector[$z][tipo_solicitud] == "S")
                                   {
                                        if($cantidadFormuladaS[$z] < $cantidadSol[$z])
                                        {
                                             $this->frmError["MensajeError"]="LAS CANTIDADES SOLICITADAS SON MAYORES QUE LAS CANTIDADES FORMULADAS.";
                                             $this->CallFrmMedicamentos($datosPaciente,$datos_estacion);
                                             return true;
                                        }
                              	}	
	                         }
                         }
                    }
               }
          }
    
          if($bodega=='-1')//por si entramos con el combo "SELECCIONE"
          {unset($op);}
  
          if(is_array($op))
          {
               $nom_bodega=$this->TraerNombreBodega($datos_estacion,$bodega);
               if($bodega=='*/*')
               {
                    $this->salida .= ThemeAbrirTabla("CONFIRMACION DE SOLICITUDES PARA EL PACIENTE");
               }
               else
               {
                    $this->salida .= ThemeAbrirTabla("CONFIRMACION DE SOLICITUDES DE MEDICAMENTOS A LA BODEGA &nbsp;".$nom_bodega."");
               }    
                    
               if($bodega=='*/*')
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','InsertSolicitudMed_Para_Paciente',array("cantidad"=>$cant,"datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega));
               }
               else
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','InsertSolicitudMed',array("cantidad"=>$cant,"datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega));
               }
                    
               $this->salida .= "<form name='med' action='".$href."' method='POST'><br>\n";

               $w = 0;
               for($i=0;$i<sizeof($op);$i++)
               {
                    if($op[$i])
                    {
                         $datos_op = explode(",",$op[$i]);
                         if($datos_op[1] == "M")
                         {
/*                         
                              $this->salida .= "   <table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
                              $this->salida .= "       <tr class=\"modulo_table_title\">\n";
                              $this->salida .= "           <td width=\"10%\">CODIGO</td>\n";
                              $this->salida .= "           <td width=\"25%\">PRODUCTO</td>\n";
                              $this->salida .= "           <td width=\"5%\">CANT</td>\n";
                              $this->salida .= "           <td width=\"5%\">EXIST</td>\n";
                              $this->salida .= "           <td width=\"4%\"></td>\n";
                              $this->salida .= "       </tr>\n";

                              $java ="<script>";
                              $java.="function CambioValor(valor,frm,identi){";
                              $java.=" vector=valor.split(',');";               
                              $java.=" frm.codigo_producto_S[identi].value=vector[0];";
                              $java.=" frm.cantidad[identi].value=vector[2];"; 
                              $java.=" frm.checo[identi].value=vector[0]+','+vector[2];";  
                              $java.="};";
                              $java.="</script>";
                              $this->salida.= $java;
*/                              
                              unset($vect);

                              foreach($productos as $k => $vector)
                              {
                                   for($x=0;$x<sizeof($vector);$x++)
                                   {
                                        $cantidad_solicitada_medicamento = floor($cant[$x]);
                                        if($datos_op[0] == $vector[$x][num_reg] AND $vector[$x][tipo_solicitud] == "M" AND $cantidad_solicitada_medicamento > 0)
                                        {
                                            $this->salida .= "   <table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
                                            $this->salida .= "       <tr class=\"modulo_table_title\">\n";
                                            $this->salida .= "           <td width=\"10%\">CODIGO</td>\n";
                                            $this->salida .= "           <td width=\"25%\">PRODUCTO</td>\n";
                                            $this->salida .= "           <td width=\"5%\">CANT</td>\n";
                                            $this->salida .= "           <td width=\"5%\">EXIST</td>\n";
                                            $this->salida .= "           <td width=\"4%\"></td>\n";
                                            $this->salida .= "       </tr>\n";

                                            $java ="<script>";
                                            $java.="function CambioValor(valor,frm,identi){";
                                            $java.=" vector=valor.split(',');";               
                                            $java.=" frm.codigo_producto_S[identi].value=vector[0];";
                                            $java.=" frm.cantidad[identi].value=vector[2];"; 
                                            $java.=" frm.checo[identi].value=vector[0]+','+vector[2];";  
                                            $java.="};";
                                            $java.="</script>";
                                            $this->salida.= $java;
                                        
                                             $this->salida .= "     <tr align='center' class='modulo_list_oscuro'>\n";
                                             $this->salida .= "         <td width=\"10%\">".$vector[$x][codigo_producto]."</td>\n";
                                             $this->salida .= "         <td width=\"25%\">".$vector[$x][producto]."</td>\n";
                                             $cantidad_solicitada_medicamento = floor($cant[$x]);
                                             //TRAE LA CANTIDAD A SOLICITAR
                                             $this->salida .= "         <td width=\"5%\">".$cantidad_solicitada_medicamento."</td>\n";
                                             $existencia=$this->RevisarExistenciaBodega($datos_estacion,$bodega,$vector[$x][codigo_producto]);
                    
                                             if($existencia > 0)
                                             {
                                                  $this->salida .= "            <td width=\"5%\">".FormatoValor($existencia)."</td>\n";
                                             }else
                                             {
                                                  $this->salida .= "            <td width=\"5%\"><label class=label_mark>No aplica</label></td>\n";
                                             }
                    
                                             $this->salida .= "         <td width=\"4%\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>\n";
                                             
                                             $vectorOP[$w]['codigo'] = $vector[$x][codigo_producto];
                                             $vectorOP[$w]['evolucion_id'] = $vector[$x][evolucion_id];
                                             $vectorOP[$w]['cantidad'] = $cant[$x];
                                             $vectorOP[$w]['ingreso'] = $vector[$x][ingreso];
                                             $w++;
                                             
                                             $a = 0;
                                             $arr_rel=$this->Revisar_Relacion_Medicamento_Bodegas($vector[$x][codigo_producto],$bodega);
                                             if(is_array($arr_rel))
                                             {
                                                  //parte de los insumos relacionados con los suministros q se hacen al paciente.
                                                  $this->salida.= "<tr class=\"$estilo\">";
                                                  $this->salida.= "<td colspan='5' width=\"10%\">\n";
                                                  $this->salida.= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_title\"\">\n";
                                                  for($y=0;$y<sizeof($arr_rel);$y++)
                                                  {
                                                       if($y==0)
                                                       {
                                                            $this->salida .= "<tr class=\"modulo_list_table_title\">\n";
                                                            $this->salida .= "<td colspan='4'>SOLICITUD DE INSUMOS RELACIONADOS CON MEDICAMENTOS</td>\n";
                                                            $this->salida .= "</tr>\n";
                                                            $this->salida .= "<tr class=\"modulo_list_table_title\">\n";
                                                            $this->salida .= "<td width=\"40%\" align=\"center\">DESCRIPCION INSUMO</td>\n";
                                                            $this->salida .= "<td width=\"13%\" align=\"center\">CODIGO</td>\n";
                                                            $this->salida .= "<td width=\"13%\" align=\"center\">CANTIDAD</td>\n";
                                                            $this->salida .= "<td width=\"4%\" align=\"center\">&nbsp;</td>\n";                                   
                                                            $this->salida .= "</tr>\n";
                                                       }    
                                                       
                                                       if($arr_rel[$y][codigo_agrupamiento] != $arr_rel[$y-1][codigo_agrupamiento])
                                                       {    
                                                            $this->salida .= "<tr align='center' class='modulo_list_claro'>\n";
                                                            $this->salida .= "<td width=\"40%\" align=\"left\">";
                                                            $this->salida .= "<select name=\"insumo_rel$y\" class=\"select\" Onchange=\"CambioValor(this.value,document.med,$a)\">";
                                                            $relacion=$this->Revisar_Relacion_Medicamento_Bodegas($arr_rel[$y][medicamento_id],$bodega,'',$arr_rel[$y][codigo_agrupamiento]);
                                                            for ($jj=0; $jj<sizeof($relacion); $jj++)
                                                            {
                                                                 $this->salida .= "<option value=\"".$relacion[$jj][codigo_producto].",".$relacion[$jj][descripcion].",".$relacion[$jj][cantidad]."\">".$relacion[$jj][descripcion]."</option>";
                                                                 $codigo = $relacion[0][codigo_producto];
                                                                 $cantidad = $relacion[0][cantidad];
                                                            }                                   
                                                            $this->salida .= "</select>";
                                                            $this->salida .= "</td>";
                                                            
                                                            $this->salida .= "<td width=\"13%\" align=\"center\">";
                                                            $this->salida .= "<input type=\"input-text\" id=\"codigo_producto_S\" name=\"codigo_producto_S$y\" size=\"10\" maxlength=\"12\" value=\"$codigo\" readonly>";
                                                            $this->salida .= "</td>";
               
                                                            $this->salida .= "<td width=\"13%\" align=\"center\">";
                                                            $this->salida .= "<input type=\"input-text\" id=\"cantidad\" name=\"cantidad$y\" size=\"5\" maxlength=\"4\" value=\"$cantidad\" readonly>";
                                                            $this->salida .= "</td>";
                              
                                                            $this->salida .= "<td width=\"4%\" align=\"center\"><input type='checkbox'$checked id=\"checo\" name='checo[]' value=\"".$codigo.",".$cantidad."\" checked></td>\n";
                                                            $this->salida .= "</tr>";
                                                            
                                                            $factor = floor($cant[$x]);
                                                            $this->salida .= "<input type=\"hidden\" name=\"Factor[]\" value=\"$factor\">";
                                                            $a++;
                                                       }
                                                  }
                                                  $this->salida .="</table>\n";
                                                  $this->salida .="</td>\n";
                                                  $this->salida .="</tr>";
                                             }
                                        }
                                   }
                              }
                              $this->salida .="</table>\n";
                              $this->salida .="<br>\n";
                         }
                         else
                         {
                              $this->salida .= "<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
                              $this->salida .= "	<tr class=\"modulo_table_title\">\n";
                              $this->salida .= "		<td width=\"8%\">MEZCLA</td>\n";
                              $this->salida .= "       <td width=\"82%\" align=\"center\">DETALLE DE LAS SOLUCIONES SOLICITADAS</td>\n";
                              $this->salida .= "	</tr>\n";

                              $this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">\n";
                              $this->salida .= "		<td width=\"8%\" align=\"center\">".$datos_op[0]."</td>\n";
                              $this->salida .= "       <td width=\"82%\">";
                              
                              $this->salida .= "<table width=\"100%\">";
                              $this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">\n";
                              $this->salida .= "		<td width=\"10%\">CODIGO</td>\n";
                              $this->salida .= "       <td width=\"25%\">PRODUCTO</td>\n";
                              $this->salida .= "       <td width=\"5%\">CANTIDAD</td>\n";
                              $this->salida .= "       <td width=\"5%\">SOLUCIONES</td>\n";                    
                              $this->salida .= "       <td width=\"5%\">EXIST</td>\n";
                              $this->salida .= "       <td width=\"4%\"></td>\n";
                              $this->salida .= "	</tr>\n";
                              $V = 0;
                              foreach($productos as $k => $vector)
                              {
                                   $tama???o[$datos_op[0]] = 0;
                                   for($x=0;$x<sizeof($vector);$x++)
                                   {
                                        if($vector[$x][num_mezcla] == $datos_op[0])
                                        { $tama???o[$datos_op[0]] = $tama???o[$datos_op[0]] + 1; }
                                   }
							
                                   for($x=0;$x<sizeof($vector);$x++)
                                   {
                                        if($datos_op[0] == $vector[$x][num_mezcla] AND $vector[$x][tipo_solicitud] == "S")
                                        {
                                             $this->salida .= "	<tr class=\"modulo_list_oscuro\">\n";
                                             $this->salida .= "		<td width=\"10%\">".$vector[$x]['codigo_producto']."</td>\n";
                                             $this->salida .= "       <td width=\"25%\">".$vector[$x]['producto']."</td>\n";
                                             
                                             if($vector[$x][num_mezcla] != $vector[$x - 1][num_mezcla])
                                             {
                                                $cantidad_solicitada_medicamento = floor($cantidadSol[$x]);
                                             }
                                             
                                             $Cantidad_I = $cantidad_solicitada_medicamento * $vector[$x]['cantidad_producto'];
                                             
                                             $this->salida .= "       <td width=\"5%\" align=\"center\">".$Cantidad_I."</td>\n";
                                             if($V == 0)
                                             {

                                                  $this->salida .= "       <td width=\"5%\" align=\"center\" rowspan=\"".$tama???o[$datos_op[0]]."\">".$cantidad_solicitada_medicamento."</td>\n";
                                                  $V = 1;
                                             }
                                             $existencia=$this->RevisarExistenciaBodega($datos_estacion,$bodega,$vector[$x]['codigo_producto']);
                                             if($existencia > 0)
                                             {
                                                  $this->salida .= "            <td width=\"5%\" align=\"center\">".FormatoValor($existencia)."</td>\n";
                                             }else
                                             {
                                                  $this->salida .= "            <td width=\"5%\"><label class=label_mark>No aplica</label></td>\n";
                                             }
                                             $this->salida .= "       <td width=\"4%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>\n";
                                             $this->salida .= "	</tr>\n";
                                             
                                             $vectorOP[$w]['codigo'] = $vector[$x][codigo_producto];
                                             $vectorOP[$w]['evolucion_id'] = "";
                                             $vectorOP[$w]['cantidad'] = $Cantidad_I;
                                             $vectorOP[$w]['ingreso'] = $vector[$x][ingreso];
                                             $w++;
                          
                                        }
                                   }
                              }

                              $this->salida .= "</table>";
                              
                              $this->salida .= "</td>\n";
                              $this->salida .= "</tr>\n";
                              $this->salida .= "</table>";
                         }
                    }
               }
               
               //Vetor que contienen el detalle de la solicitud.
               $_SESSION['VECTOR_DETALLE_PRODUCTOS'] = $vectorOP;
               
               if($_REQUEST['bodega']=='*/*')
               {
                    $this->salida .= "<br><table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_title\">\n";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td ><label class='label_mark'>NOMBRE SOLICITANTE</label></td><td align=\"left\"><input type='text' name='nom' size='55' maxlength='60' value='$nom'></td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td ><label class='label_mark'>observaciones :</label></td><td align=\"left\"><TEXTAREA name='area' rows='4' cols='60'>$area</TEXTAREA></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
               }
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
               $this->salida.=" <tr>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\"></form>";
               $this->salida.=" </td>";
               $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"CANCELAR\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" <br></table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla('SOLICITUD DE MEDICAMENTOS',"50%");
               $this->salida .= "   <table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "       <tr >\n";
               $this->salida .= "           <td align=\"center\"><label class='label_mark'>NO SE SOLICITO NINGUN MEDICAMENTO AL PACIENTE !</label></td>\n";
               $this->salida.="</tr></table>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"VOLVER\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     }
     
     //funcion que confirma si se va a cancelar la solicitud de medicamentos para el paciente
     //esta pantalla muestra para confirmar la cancelaci???n de los insumos 
     function ConfirmarCancelSolicitud_Medicamentos_Para_Pacientes()
     {
          $bodega = $_REQUEST['bodega'];
          $SWITCHE = $_REQUEST['switche'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $op = $_REQUEST['opcion'];
          $spy = $_REQUEST['spia']; //variable q determina a donde me dirigo cuando se cancele una solicitud.
          $ingreso = $_REQUEST['ingreso'];
          $medic = $_SESSION['ESTACION']['VECTOR_SOL_MED_PAC'][$ingreso];
          
          if(sizeof($medic) AND sizeof($op))
          {
               unset($matriz);
               for($h=0;$h<sizeof($op);$h++)
               {
                    $dat_op=explode(",",$op[$h]);
                    $matriz[$h]=$dat_op[0];
               }
               $this->salida .= ThemeAbrirTabla('CANCELAR SOLICITUD DE MEDICAMENTOS PARA EL PACIENTE');
               $f = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CancelSolicitud_Medicamentos_Para_Paciente',array("spia"=>$spy,"datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"matriz"=>$matriz,"switche"=>$SWITCHE));
               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
     
               $this->salida .= "	<table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";
     
               $this->salida .= "		<tr class=\"modulo_table_title\">\n";
               $this->salida .= "			<td colspan='6'  align=\"center\">MEDICAMENTOS SOLICITADOS</td>\n";
               $this->salida .= "		</tr>\n";
     
               $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
               $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
               $this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
               $this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
               $this->salida .= "			<td width=\"5%\" >CANTIDAD</td>\n";
               $this->salida .= "			<td width=\"5%\" ></td>\n";
               $this->salida .= "		</tr>\n";
     
               for($i=0;$i<sizeof($medic);$i++)
               {
                    if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                    if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
                    {
                         if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                         {
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                              $solicitud=$medic[$i][solicitud_id];
                              $this->salida .= "<td colspan = 4 width=\"65%\">";
                              $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         }

                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                         $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$i][cantidad])."</td>\n";
                         $this->salida.=" </tr>";
                         if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                         {
                              $this->salida .= "</table>";
                              $this->salida .= "</td>";
                              $this->salida.="  <td colspan = 1 $estilo width=\"5%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>";
                              $this->salida .= "</tr>";
                         }
                    }
               }
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"modulo_list_claro\">";
               $this->salida .= "<td  colspan='2' align='center' width=\"35%\">JUSTIFICACION :</td>";
               $this->salida .= "<td colspan='4'  align=\"left\"><TEXTAREA name=obs cols=60 rows=6>".$_REQUEST['obs']."</TEXTAREA></td>";

               $this->salida.="</tr></table><br>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
               $this->salida.=" <tr>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\"></form>";
               $this->salida.=" </td>";

               if($spy==1)
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla("CONTROL DE SOLICITUD DE MEDICAMENTOS PARA EL PACIENTE","50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
	          $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               if($spy==1)
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
     
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     
     }

     
     //funcion que recibe los medicamentos / insumos por parte de la enfermera o el auxiliar.
     function Recibir_X_Para_Pacientes($datos_estacion,$datosPaciente,$codigo,$solicitud,$data)
     {
		if(empty($datos_estacion))
		{
			$datos_estacion = $_REQUEST['datos_estacion'];
			$datosPaciente = $_REQUEST['datosPaciente'];
			$codigo=$_REQUEST['codigo_producto'];
			$data[0]='';
		}

		$this->salida .= ThemeAbrirTabla("RECIBIR MEDIAMENTOS / INSUMOS");
			
		$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>HABITACION</td>\n";
		$this->salida .= "			<td>CAMA</td>\n";
		$this->salida .= "			<td>PISO</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		$this->salida .= "			<td>".$datosPaciente[nombre_completo]."</td>\n";
		$this->salida .= "			<td>".$datosPaciente[pieza]."</td>\n";
		$this->salida .= "			<td>".$datosPaciente[cama]."</td>\n";
		$this->salida .= "			<td>".$datos_estacion[estacion_descripcion]."</td>\n";
		$this->salida.="</tr></table><br>";
		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
          //parte de las solicitudes de medicmanetos por parte d e los pacientes
		$cont=0;
		for($w=0;$w<sizeof($data);$w++)
		{
			$e=explode(",",$data[$w]);
			if(!empty($e[0]))
			{
				$ingreso=$e[0];
				$codigo=$e[1];unset($e);
			}
			
               unset($medic);
			$medic=$this->Get_Medicamentos_Solicitados_Para_Pacientes($datosPaciente[ingreso],$datos_estacion[empresa_id],$solicitud,$codigo);

			if(is_array($medic))
			{$cont=1;}
			else
			{
				if($cont==0)
				{
					$cont=0;
				}
			}
			if(sizeof($medic))
			{
				$f = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Insertar_Recibido_Para_Pacientes',array("ingreso"=>$datosPaciente['ingreso'],"datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"solicitud"=>$solicitud,"codigo"=>$codigo,"data"=>$data));
				$this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
				$this->salida .= "	<table align=\"center\" width=\"80%\"  border=\"1\" class='modulo_list_table'\n>";
				if($w==0)
				{
					$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "			<td colspan='7'  align=\"center\">MEDICAMENTOS E INSUMOS POR RECIBIR</td>\n";
					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
				$this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
				$this->salida .= "			<td width=\"40%\" >DESCRIPCION PRODUCTO</td>\n";
				$this->salida .= "			<td width=\"13%\" >CANT SOL</td>\n";
				$this->salida .= "			<td width=\"12%\" >CANT REC</td>\n";
				$this->salida .= "			<td width=\"12%\" >CANT FALT</td>\n";
				$this->salida .= "			<td width=\"12%\" ></td>\n";
				$this->salida .= "		</tr>\n";


				for($k=0;$k<sizeof($medic);$k++)
				{
					if($k % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
					$this->salida .= "<tr $estilo>\n";
					$this->salida .= "<td $estilo width=\"12%\">".$medic[$k][codigo_producto]."</td>\n";
					$this->salida .= "<td $estilo align='center' width=\"44%\"><label class='label_mark'>".$medic[$k][producto]."</label></td>\n";
					$this->salida .= "<td $estilo align=\"center\" width=\"15%\"><label class='label_mark'>".floor($medic[$k][cantidad])."</label></td>\n";
					
                         //aca colocar el query de las cantidades recibisdas...........
					$recepcion=$this->Recepcion_Med_Ins_Para_Pacientes($datosPaciente[ingreso],$medic[$k][codigo_producto],$datos_estacion[estacion_id]);
					$faltante=$medic[$k][cantidad]-$recepcion;
					$this->salida .= "<td $estilo width=\"13%\">".FormatoValor($recepcion)."</td>\n";
					$this->salida .= "<td $estilo width=\"13%\">".FormatoValor($faltante)."</td>\n";unset($faltante);
				
					$this->salida .= "<td $estilo width=\"18%\"><input type='text' name='cantidad[][".$medic[$k][codigo_producto]."]' size='5' maxlength='10' ></td>\n";unset($faltante);
					$this->salida .= "<input type='hidden' name='cant_sol[][".$medic[$k][codigo_producto]."]' value='".floor($medic[$k][cantidad])."'>\n";
					$this->salida .= "<input type='hidden' name='cant_rec[][".$medic[$k][codigo_producto]."]' value='".floor($recepcion)."'>\n";
				
					$this->salida.=" </tr>";
				}
                    $this->salida.="</table>";
			}
			//fin de solicitudes por parte de los pacientes.		
	  	}//fin for primero
			
		if($cont >0)
		{
			$this->salida .= "<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_title\">\n";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td ><label class='label_mark'>NOMBRE DE LA PERSONA QUE ENTREGA</label></td><td><input type='text' name='nom' size='55' maxlength='60' value='$nom'></td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td ><label class='label_mark'>OBSERVACIONES :</label></td><td><TEXTAREA name='area' rows='5' cols='80'>$area</TEXTAREA></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			
			$this->salida .= '<br><br><table align="center" width="40%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center">';
			$this->salida .= '<input type="submit" name="GUARDAR" value="GUARDAR" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
		
			$o = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
			$this->salida .= '<form name="volver" method="post" action="'.$o.'">';
		
			$this->salida .= '<td align="center">';
			$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '</table>';
		}
		else
		{
               $this->salida .= "<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= '<tr>';
               $this->salida .= '<td align="center"><label class=label_mark>NO HAY MAS MEDICAMENTOS/INSUMOS POR RECIBIR</label>';
               $this->salida .= '</td>';
               $this->salida.="</tr>";
               $this->salida .= '</table>';
               $o = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$o."'>VOLVER</a><br>";
		}	
		
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

     //funcion que confirma si se va a cancelar la solicitud
     function ConfirmarCancelSolicitudMed()
     {
          $bodega = $_REQUEST['bodega'];
          $SWITCHE = $_REQUEST['switche'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $op = $_REQUEST['opcion'];
          $spy = $_REQUEST['spia']; //variable q determina a donde me dirigo cuando se cancele una solicitud.
          $ingreso = $_REQUEST['ingreso'];
          $medic=$_SESSION['ESTACION']['VECTOR_SOL'][$ingreso];
          if(sizeof($medic) AND sizeof($op))
          {
               unset($matriz);
               for($h=0;$h<sizeof($op);$h++)
               {
                    $dat_op=explode(",",$op[$h]);
                    $matriz[$h]=$dat_op[0];
               }
               $this->salida .= ThemeAbrirTabla('CANCELAR SOLICITUD DE MEDICAMENTOS');
               $f = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CancelSolicitudMedicametos',array("spia"=>$spy,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$bodega,"matriz"=>$matriz,"switche"=>$SWITCHE));
               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
     
               $this->salida .= "	<table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";
     
               $this->salida .= "		<tr class=\"modulo_table_title\">\n";
               $this->salida .= "			<td colspan='7'  align=\"center\">MEDICAMENTOS SOLICITADOS</td>\n";
               $this->salida .= "		</tr>\n";
     
               $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
               $this->salida .= "			<td width=\"17%\" >BODEGA</td>\n";
               $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
               $this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
               $this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
               $this->salida .= "			<td width=\"5%\" >CANT</td>\n";
               $this->salida .= "			<td width=\"5%\" ></td>\n";
               $this->salida .= "		</tr>\n";
     
               for($i=0;$i<sizeof($medic);$i++)
               {
                    if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                    if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
                    {
                         if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                         {
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                              $solicitud=$medic[$i][solicitud_id];
                              $this->salida .= "<td colspan = 5 width=\"65%\">";
                              $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         }

                         $nom_bodega=$this->TraerNombreBodega($estacion,$medic[$i][bodega]);
                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"20%\"><label class='label_mark'>$nom_bodega</label></td>\n";
                         $this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][principio_activo]."</td>\n";
                         $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$i][cant_solicitada])."</td>\n";
                         $this->salida.=" </tr>";
                         if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                         {
                              $this->salida .= "</table>";
                              $this->salida .= "</td>";
                              $this->salida.="  <td colspan = 1 $estilo width=\"5%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>";
                              $this->salida .= "</tr>";
                         }
                    }
               }
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"modulo_list_claro\">";
               $this->salida.= "<td  colspan='2' align='center' width=\"35%\"  >JUSTIFICACION :</td>";
               $this->salida.= "<td colspan='5' align=\"left\"><TEXTAREA name=obs cols=60 rows=6>".$_REQUEST['obs']."</TEXTAREA></td>";

               $this->salida.="</tr></table><br>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
               $this->salida.=" <tr>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\"></form>";
               $this->salida.=" </td>";

               if($spy==1)
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"CANCELAR\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla('CANCELACION DE MEDICAMENTOS SOLICITADOS A BODEGA',"50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
	          $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               if($spy==1)
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
     
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     }

     
     /*
     * Control_Suministro - Funcion la cual me permite realizar el suministro de 
     * los medicamentos recetados.
     *
     * Adaptacion: Tizziano Perea
     */
    function Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud)
    {
          $this->IncludeJS("CrossBrowser");
          $this->IncludeJS("CrossBrowserDrag");
          $this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("RemoteScripting");
     	$this->IncludeJS("ScriptsRemotos/misfunciones.js",'app','EE_AdministracionMedicamentos');
          SessionSetVar("Usuario",UserGetUID());
          SessionSetVar("V_estacion",$_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA']);
          SessionSetVar("U_estacion",$_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO']);
          
          //vector de insumos 
          unset($_SESSION['ESTACION_ENF_MED_VECT']['DATA']);
          
          if(!$datos_estacion)
          {
               $datosPaciente = $_REQUEST['datosPaciente'];
               $datos_estacion = $_REQUEST['datos_estacion'];
               //arreglo q contiene los productos seleccionados para suministrar.
               $vect = $_REQUEST['vect'];
               $tipo_solicitud = $_REQUEST['tipo_solicitud'];
          }
          
          // Array para Mostrar Suministros.
          $_Suministros = array();
          $_NomProducto = array();
          
          if(empty($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']))
          {
               $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR'] = $vect;
          }
          else
          {
               $vect = $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR'];
          }
          
          $this->salida = ThemeAbrirTabla('CONTROL DE SUMINISTRO DEL MEDICAMENTO');
     
          $this->salida .= "  <table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
          $this->salida .= "      <tr class=\"modulo_table_title\">\n";
          $this->salida .= "          <td>PACIENTE</td>\n";
          $this->salida .= "          <td>HABITACION</td>\n";
          $this->salida .= "          <td>CAMA</td>\n";
          $this->salida .= "          <td>PISO</td>\n";
          $this->salida .= "      </tr>\n";
          $this->salida .= "      <tr align='center' class='modulo_list_oscuro'>\n";
          $this->salida .= "          <td>".$datosPaciente[nombre_completo]."</td>\n";
          $this->salida .= "          <td>".$datosPaciente[pieza]."</td>\n";
          $this->salida .= "          <td>".$datosPaciente[cama]."</td>\n";
          $this->salida .= "          <td>".$datos_estacion[estacion_descripcion]."</td>\n";
          $this->salida .= "</tr></table><br>";
     
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";
     
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
          $accion = ModuloGetURL('app','EE_AdministracionMedicamentos','user','ConfirmarSuministros',array("tipo_solicitud"=>$tipo_solicitud,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
          $this->salida .= "<form name=\"formades\" action=\"$accion\" method=\"post\">";
     
          //Programa de Relacion Insumos - Medicamentos.
          $java ="<script>";
          $java.="function CambioValor(valor,frm,identi){";
          $java.=" vector=valor.split(',');";               
          $java.=" frm.codigo_producto_S[identi].value=vector[0];";
          $java.=" frm.cantidad[identi].value=vector[2];"; 
          $java.=" frm.checo[identi].value=vector[0]+','+vector[2];";  
          $java.="};";
          $java.="</script>";
          $this->salida.= $java;
     
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td align=\"left\" colspan=\"5\">CONTROL DEL MEDICAMENTO:</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="  <td align=\"center\" width=\"7%\">CODIGO</td>";
          $this->salida.="  <td align=\"center\" width=\"30%\">PRODUCTO</td>";
          $this->salida.="  <td align=\"center\" width=\"14%\">DOSIS</td>";
          $this->salida.="  <td align=\"center\" width=\"14%\">BODEGA PACIENTE</td>";
          $this->salida.="  <td align=\"center\" width=\"14%\">SUMINISTRAR</td>";
          $this->salida.="</tr>";
          
          for($x=0;$x<sizeof($vect);$x++)
          {
               unset($_BodegaPaciente);
               $datos = $this->GetEstacionBodega_Existencias($datos_estacion,2,$vect[$x][codigo_producto]);
               
               // Informacion de Conteo de medicamentos Solicitados para validaciones.
               $_BodegaPaciente = $this->GetCantidades_BodegaPaciente($datosPaciente[ingreso],$vect[$x][codigo_producto]);
               
               //1 Cantidades reales en la Bodega del Paciente.
               $_StockPaciente = $_BodegaPaciente[stock_almacen];
               if($_StockPaciente == 0)
               { $_StockPaciente = $_BodegaPaciente[stock_paciente]; }
               
               $_StockPaciente = $_StockPaciente - $_BodegaPaciente[cantidad_en_devolucion];
               
               // Vectores de suministros
               $_Ubodega = explode(" ",$vect[$x][dosis]);
               $control = $this->Consultar_Control_Suministro($vect[$x][codigo_producto], $datosPaciente[ingreso], $tipo_solicitud);
               array_push($_Suministros,$control);
               array_push($_NomProducto,$vect[$x][producto]);
               $catidadBodega[$x] = $_StockPaciente;
     
               $this->salida.="<tr class='modulo_list_claro'>";
               $this->salida.="  <td align=\"center\" width=\"7%\">".$vect[$x][codigo_producto]."</td>";
               
               $this->salida.="<input type=\"hidden\" name=\"datos_SUM[]\" value=\"".$vect[$x][codigo_producto]."\">";
               $this->salida.="<input type=\"hidden\" name=\"ingreso_F[]\" value=\"".$vect[$x][ingreso]."\">";
               $this->salida.="<input type=\"hidden\" name=\"num_F[]\" value=\"".$vect[$x][num_formulacion]."\">";
               
               $this->salida.="  <td align=\"center\" width=\"30%\">".$vect[$x][producto]."</td>";
               if($tipo_solicitud == "M")
               {
                    $this->salida.="  <td align=\"right\" width=\"14%\">".$vect[$x][dosis]." ".$vect[$x][unidad_dosificacion]."</td>";
               }else{
                    $this->salida.="  <td align=\"right\" width=\"14%\">".$vect[$x][dosis]."</td>";
               }
     
               // Explode de unidad de bodega paciente.               
               if($tipo_solicitud == "M")
               {
                    $this->salida.="  <td align=\"right\" width=\"14%\">".$_StockPaciente."&nbsp;".$vect[$x][presentacion]."</td>";
               }else{
                    $this->salida.="  <td align=\"right\" width=\"14%\">".$_StockPaciente."&nbsp;".$vect[$x][presentacion]."</td>";
               }
               
               // SUMINISTROS
               if($x == 0)
               {
                    $javaAccionSuministros = "javascript:MostrarCapa('ContenedorSuministrosParciales');IniciarCapaSum('SUMINISTROS PARCIALES','ContenedorSuministrosParciales');CargarContenedor('ContenedorSuministrosParciales');";
               	$this->salida.="  <td align=\"center\" width=\"14%\" rowspan=\"".sizeof($vect)."\"><a href=\"$javaAccionSuministros\"><img src=\"". GetThemePath() ."/images/pparamedin.png\" border='0'></a></td>";
               }
               $this->salida.="</tr>";
     
               // Relacion de Medicamento es insumos               
               if($tipo_solicitud == "M")
               {
                    $a = 0;
                    $arr_rel=$this->Revisar_Relacion_Medicamento_Bodegas($vect[$x][codigo_producto],$bodega);
                    if(is_array($arr_rel))
                    {
                         //parte de los insumos relacionados con los suministros q se hacen al paciente.
                         $this->salida.= "<tr class=\"$estilo\">";
                         $this->salida.= "<td colspan=\"5\" width=\"10%\">\n";
                         $this->salida.= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_title\"\">\n";
                         for($y=0;$y<sizeof($arr_rel);$y++)
                         {
                              if($y==0)
                              {
                                   $this->salida .= "<tr class=\"modulo_list_table_title\">\n";
                                   $this->salida .= "<td colspan='4'>SOLICITUD DE INSUMOS RELACIONADOS CON MEDICAMENTOS</td>\n";
                                   $this->salida .= "</tr>\n";
                                   $this->salida .= "<tr class=\"modulo_list_table_title\">\n";
                                   $this->salida .= "<td width=\"40%\" align=\"center\">DESCRIPCION INSUMO</td>\n";
                                   $this->salida .= "<td width=\"13%\" align=\"center\">CODIGO</td>\n";
                                   $this->salida .= "<td width=\"13%\" align=\"center\">CANTIDAD</td>\n";
                                   $this->salida .= "<td width=\"4%\" align=\"center\">&nbsp;</td>\n";                                   
                                   $this->salida .= "</tr>\n";
                              } 
                              
                              if($arr_rel[$y][codigo_agrupamiento] != $arr_rel[$y-1][codigo_agrupamiento])
                              { 
                                   $this->salida .= "<tr align='center' class='modulo_list_claro'>\n";
                                   $this->salida .= "<td width=\"40%\" align=\"left\">";
                                   $this->salida .= "<select name=\"insumo_rel$y\" class=\"select\" Onchange=\"CambioValor(this.value,document.formades,$a)\">";
                                   $relacion=$this->Revisar_Relacion_Medicamento_Bodegas($arr_rel[$y][medicamento_id],$bodega,'',$arr_rel[$y][codigo_agrupamiento]);
                                   for ($jj=0; $jj<sizeof($relacion); $jj++)
                                   {
                                        $this->salida .= "<option value=\"".$relacion[$jj][codigo_producto].",".$relacion[$jj][descripcion].",".$relacion[$jj][cantidad]."\">".$relacion[$jj][descripcion]."</option>";
                                        $codigo = $relacion[0][codigo_producto];
                                        $cantidad = $relacion[0][cantidad];
                                   }                                   
                                   $this->salida .= "</select>";
                                   $this->salida .= "</td>";
                                   
                                   $this->salida .= "<td width=\"13%\" align=\"center\">";
                                   $this->salida .= "<input type=\"input-text\" id=\"codigo_producto_S\" name=\"codigo_producto_S$y\" size=\"10\" maxlength=\"12\" value=\"$codigo\" readonly>";
                                   $this->salida .= "</td>";
                                   
                                   $this->salida .= "<td width=\"13%\" align=\"center\">";
                                   $this->salida .= "<input type=\"input-text\" id=\"cantidad\" name=\"cantidad$y\" size=\"5\" maxlength=\"4\" value=\"$cantidad\" readonly>";
                                   $this->salida .= "</td>";
     
                                   $this->salida .= "<td width=\"4%\" align=\"center\"><input type='checkbox'$checked  name='checo$y' value=\"".$codigo.",".$cantidad."\" checked></td>\n";
                                   $this->salida .= "</tr>";
                                   
                                   $factor = floor($vect[$x][dosis]);
                                   $this->salida .= "<input type=\"hidden\" name=\"Factor[]\" value=\"$factor\">";
                                   $a++;
                              }
                         }
                         $this->salida .="</table>\n";
                         $this->salida .="</td>\n";
                         $this->salida .="</tr>";
                    }
                    $this->salida.="<input type=\"hidden\" name=\"arr_rel\" value=\"".sizeof($arr_rel)."\">";
               }
     
               // Controles               
               if($tipo_solicitud == "M")
               {
                    // Cantidad Recetada. Las unidades totales q recomendo el profesional
                    $cantidad_recetada = $vect[$x][cantidad];
                    $this->salida.="<input type='hidden' name='cantidad_recetada[]' value='".$cantidad_recetada."'>";
                    // Dosis
                    $dosis = $vect[$x][dosis];
                    $this->salida.="<input type='hidden' name='dosis[]' value='".$dosis."'>";                    
                    // BodegaPaciente
                    $this->salida.="<input type='hidden' name='BodegaPaciente[]' value='".$_StockPaciente."'>";
               }else{
                    // Cantidad Recetada. Las unidades totales q recomendo el profesional
                    $cantidad_recetada = $vect[$x][cantidad] * $_Ubodega[0];
                    $this->salida.="<input type='hidden' name='cantidad_recetada[]' value='".$cantidad_recetada."'>";
                    // Dosis
                    $dosis = $_Ubodega[0];
                    $this->salida.="<input type='hidden' name='dosis[]' value='".$dosis."'>";                    
                    // BodegaPaciente
                    $this->salida.="<input type='hidden' name='BodegaPaciente[]' value='".$_StockPaciente."'>";
               }
          }
          if($tipo_solicitud == "S")
          {
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td align=\"right\" colspan=\"5\">CANTIDAD SOLUCION:&nbsp;&nbsp;";
               $this->salida.="<label class=\"label_mark\">".$vect[0][cantidad]."&nbsp; SOLUCION(ES)</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
               $this->salida.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VOLUMEN DE INFUSION:&nbsp;&nbsp;";
               $this->salida.="&nbsp;&nbsp; <label class=\"label_mark\">".$vect[0][unidad_dosificacion]."</label>";
               $this->salida.="</td></tr>";               
          }
          $this->salida.="</table><br>";
          
          
          $this->salida.="<div id='ContenedorSuministrosParciales' class='d2Container' style=\"display:none\"><br>";
          $this->salida .= "    <div id='titulo' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorSuministrosParciales');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoSuministrosParciales' class='d2Content' style=\"height:300\">\n";
          for($i=0;$i<sizeof($vect);$i++)
          {
          	$existeFac = 0;
               $_Ubodega = explode(" ",$vect[$i][dosis]);
               if($tipo_solicitud == "M")
               {
               	$dosificacion = $this->SeleccionUnidadSuministro($vect[$i][unidad_dosificacion], $vect[$i][cod_presentacion]);
               }else{
               	$_UnidadSum = $_Ubodega[1]." ".$_Ubodega[2];
               	$dosificacion = $this->SeleccionUnidadSuministro($_UnidadSum, $vect[$i][cod_presentacion]);               
               }
               
               $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
               $this->salida .= "                <tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "                    <td align=\"left\">".$vect[$i][producto]."</td>\n";

               if($dosificacion == 0)
               {
                    if($tipo_solicitud == "M")
                    {
                      $factor = $this->SeleccionFactorConversion($vect[$i][codigo_producto], $vect[$i][cod_presentacion], $vect[$i][unidad_dosificacion]);
                    }else{
                      $factor = $this->SeleccionFactorConversion($vect[$i][codigo_producto], $vect[$i][cod_presentacion], $_UnidadSum);
                    }
                    
                    if(!empty($factor))
                    { $existeFac = 1; }
                    
                    $this->salida .= "                    <td align=\"right\">FACTOR CONVERSION:&nbsp;";
                    
                    if($tipo_solicitud == "M")
                    { $unidad_suministro = $vect[$i][unidad_dosificacion]; }
                    else
                    { $unidad_suministro = $_Ubodega[1]." ".$_Ubodega[2]; }

                    //REEMPLAZADO POR Jonier Murillo Hurtado
/*
                    if($factor[sw_unidad_minima] == null or $factor[sw_unidad_minima] == 0){
                      if($existeFac == 1)
                      {
                           $this->salida .= "                <label class=\"label_error\">".$factor[factor_conversion]." / ".$unidad_suministro."</label>&nbsp;&nbsp;";
                      }else{
                           $this->salida .= "                <label class=\"label_error\"> -- </label>&nbsp;&nbsp;";
                      }
                      $javaAccionFactor = "javascript:MostrarCapa('ContenedorCambioFactor');IniciarCambioFac('CAMBIAR FACTOR DE CONVERSION', 'ContenedorCambioFactor', '".$existeFac."', '".$vect[$i][codigo_producto]."', '".$vect[$i][cod_presentacion]."', '".$unidad_suministro."');CargarContenedor('ContenedorCambioFactor');";
                      $this->salida .= "                     <label><a href=\"$javaAccionFactor\"><img src=\"". GetThemePath() ."/images/infor.png\" border='0'></a></label></td>\n";
                      
                      if(!$factor[factor_conversion])
                      {
                        $factor[factor_conversion] = 0;
                      }
                      $this->salida .= "                    <input type=\"hidden\" name=\"FactorC[]\" value=\"".$factor[factor_conversion]."\">";
                    }else{
                      if($existeFac == 1)
                      {
                           $this->salida .= "                <label class=\"label_error\">".$factor[sw_unidad_minima]." / ".$unidad_suministro."</label>&nbsp;&nbsp;";
                      }else{
                           $this->salida .= "                <label class=\"label_error\"> -- </label>&nbsp;&nbsp;";
                      }
                      $javaAccionFactor = "javascript:MostrarCapa('ContenedorCambioFactor');IniciarCambioFac('CAMBIAR FACTOR DE CONVERSION', 'ContenedorCambioFactor', '".$existeFac."', '".$vect[$i][codigo_producto]."', '".$vect[$i][cod_presentacion]."', '".$unidad_suministro."');CargarContenedor('ContenedorCambioFactor');";
                      $this->salida .= "                     <label><a href=\"$javaAccionFactor\"><img src=\"". GetThemePath() ."/images/infor.png\" border='0'></a></label></td>\n";
                      
                      if(!$factor[sw_unidad_minima])
                      {
                        $factor[sw_unidad_minima] = 0;
                      }
                      $this->salida .= "                    <input type=\"hidden\" name=\"FactorC[]\" value=\"".$factor[sw_unidad_minima]."\">";
                    }
*/
                    
                    if($existeFac == 1)
                    {
                         $this->salida .= "                <label class=\"label_error\">".$factor[factor_conversion]." / ".$unidad_suministro."</label>&nbsp;&nbsp;";
                    }else{
                         $this->salida .= "                <label class=\"label_error\"> -- </label>&nbsp;&nbsp;";
                    }

                    $javaAccionFactor = "javascript:MostrarCapa('ContenedorCambioFactor');IniciarCambioFac('CAMBIAR FACTOR DE CONVERSION', 'ContenedorCambioFactor', '".$existeFac."', '".$vect[$i][codigo_producto]."', '".$vect[$i][cod_presentacion]."', '".$unidad_suministro."');CargarContenedor('ContenedorCambioFactor');";
                    $this->salida .= "                     <label><a href=\"$javaAccionFactor\"><img src=\"". GetThemePath() ."/images/infor.png\" border='0'></a></label></td>\n";
                    
                    if(!$factor[factor_conversion])
                    {
                    	$factor[factor_conversion] = 0;
                    }

                    $this->salida .= "                    <input type=\"hidden\" name=\"FactorC[]\" value=\"".$factor[factor_conversion]."\">";
          	}else{
				$this->salida .= "                    <td align=\"right\">&nbsp;</td>";
	               $this->salida .= "                    <input type=\"hidden\" name=\"FactorC[]\" value=\"\">";
               }
               $this->salida .= "                </tr>\n";
               $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "                    <td align=\"center\" width=\"50%\">SUMINISTRAR</td>\n";
               $this->salida .= "                    <td align=\"center\" width=\"50%\">DESECHOS</td>\n";
               $this->salida .= "                </tr>\n";
               $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
               $this->salida .= "                    <td align=\"center\" width=\"50%\">\n";
               if($tipo_solicitud == "M")
               {
                    $descripcionU = $vect[$i][unidad_dosificacion];
               }else{
                    $descripcionU = $_UnidadSum;
               }
               
               if($_REQUEST['cantidad_suministrada'][$i] == '')
               {
                    $this->salida.="<input type=\"text\" class=\"input-text\" size=\"5\" name=\"cantidad_suministrada[]\">&nbsp; ".$descripcionU."";
               }else{
                    $this->salida.="<input type=\"text\" class=\"input-text\" size=\"5\" name=\"cantidad_suministrada[]\" value =\"".$_REQUEST['cantidad_suministrada'][$i]."\">&nbsp; ".$descripcionU."";               
               }
               $this->salida.="</td>";
               
               // PERDIDAS
               $this->salida.="  <td align=\"center\" width=\"50%\">";
               
               if($tipo_solicitud == "M")
               {
                    $descripcionU = $vect[$i][unidad_dosificacion];
               }else{
                    $descripcionU = $_UnidadSum;
               }
               
               if($_REQUEST['perdidas'][$i] == '')
               {
                    $this->salida.="<input type=\"text\" class=\"input-text\" size=\"5\" name=\"perdidas[]\">&nbsp; ".$descripcionU."";
               }else{
                    $this->salida.="<input type=\"text\" class=\"input-text\" size=\"5\" name=\"perdidas[]\" value =\"".$_REQUEST['perdidas'][$i]."\">&nbsp; ".$descripcionU."";               
               }
               $this->salida.="</td>";
               
               $this->salida .= "            </table>\n";
          }
          $this->salida .= "            <br><table width=\"100%\" align=\"center\">\n";
          $this->salida .= "                <tr>\n";
          $this->salida .= "                    <td align=\"center\">\n";
          $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"Registrar Suministro\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
	     $this->salida .= "            </table>\n";

          if( $i % 2){ $estilo='modulo_list_claro';}
          else {$estilo='modulo_list_oscuro';}
     
          if($tipo_solicitud == "S")
          {
               for($z=0;$z<sizeof($catidadBodega);$z++)
               {
                    if($catidadBodega[$z] > 0)
                    {
                         $_StockPaciente = $catidadBodega[$z];
                         break;
                    }
               }
          }
          
          // Validacion de restriccion Cuando no hay cantidades en Bodega Paciente, ni en Bodegas de consumo directo          
          if(!is_array($datos) && $_StockPaciente <= 0)
          {
               $title="NO HAY EXISTENCIAS PARA LA BODEGA DEL PACIENTE NI HAY EXISTENCIAS EN LAS OTRAS BODEGAS PARA ESTE PRODUCTO";
               $this->salida.="<br><br><DIV ALIGN='CENTER'><LABEL CLASS='label_mark'>$title</LABEL></DIV><br><br>";
               $this->salida.="<input type=\"hidden\" name=\"bodega\" value=\"*/*\">";
          }
          else
          {
               $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"100%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td align=\"left\" colspan=\"4\">INGRESAR SUMINISTRO</td>";
               $this->salida.="</tr>";
               
               $this->salida.="<tr class='modulo_list_claro'>";
               
               // Seleccion Hora               
               $this->salida.="<td colspan=\"1\" align=\"center\" width=\"30%\"><b>HORA:</b></p>";
               
               $rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
               $hora_inicio_turno = "00:00:00";
               $rango_turno = date("H");
               if(date("H:i:s") <= $hora_inicio_turno)
               {
                    list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
                    list($h,$m,$s)=explode(":",$hora_control);
               }
               else
               {//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
                    list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
                    list($h,$m,$s)=explode(":",$hora_control);
               }
     
               $i = 0;
               $rangomin = $rango_turno - 24;
               $this->salida.= "<select name='selectHora' class='select'>\n";
               for($j = $rangomin; $j<=$rango_turno; $j++)
               {
                    list($anno, $mes, $dia)=explode("-",$fecha_control);
                    if ($i==23)
                    {
                         list($h,$m,$s)=explode(":",$hora_inicio_turno);
                         $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                         $fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
                         $fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
                    }
                    else
                    {
                         list($h,$m,$s)=explode(":",$hora_inicio_turno);
                         $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                         $fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
                         $fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
                    }
                    if(empty($selectHora)){
                         if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
                    }
                    else
                    {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                         list($A,$B) = explode(" ",$selectHora);
                         if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
                    }
                    #################################################
                    list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
                    if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
                         $show = "Hoy a las";
                    }
                    elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
                         $show = "Ma???ana a las";
                    }
                    elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
                         $show = "Ayer a las";
                    }
                    else{
                         $show = $fecha_control;
                    }
                    ###########################
                    //$this->salida .= "<option value='".date("Y-m-d")." ".$i."' selected $selected>".$i."</option>\n";
                    list($yy,$mm,$dd)=explode(" ",$fecha_c);
                    if (-23<=$j AND $j<=-1){
                    $fecha_c = (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))));
                    }
                    else
                    {
                    $fecha_c = (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))));
                    }
                    $this->salida .= "<option value='".$fecha_c." ".$i."' selected $selected>".$i."</option>\n";
               }//fin for
               
               if(!empty($_REQUEST['selectHora']))
               {
                    $horas_R = explode(" ", $_REQUEST['selectHora']);
                    //$this->salida .= "<option value='".date("Y-m-d")." ".$horas_R[1]."' selected='true'>".$horas_R[1]."</option>\n";
               }
               $this->salida.= "</select>:&nbsp;\n";
               $this->salida.= "<select name='selectMinutos' class='select'>\n";
               for($j=0; $j<=59; $j++)
               {
                    if(empty($selectMinutos)){
                         if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
                    }
                    else
                    {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                         list($A,$B) = explode(" ",$selectMinutos);
                         if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
                    }
                    if ($j<10){
                         $this->salida .= "         <option value='0$j:00' $selected>0$j</option>\n";
                    }
                    else{
                         $this->salida .= "         <option value='$j:00' $selected>$j</option>\n";
                    }
               }
               $this->salida .= "</select>\n";
               $this->salida .= "</td>\n";
               
               // Seleccion bodega
               $this->salida.="<td  align=\"center\" width=\"30%\"><b>BODEGA:</b><p>";
               if(!is_array($datos) && $_StockPaciente <= 0)
               {
                    $title="NO HAY EXISTENCIAS PARA LA BODEGA DEL PACIENTE NI HAY EXISTENCIAS EN LAS OTRAS BODEGAS PARA ESTE PRODUCTO";
                    $this->salida.="<img src=\"". GetThemePath() ."/images/preguntaac.png\" title='$title' border='0'>";
               }
               else
               {
                    $this->salida.="<select name=bodega class='select'>";
                    if(is_array($datos))
                    {
                         $this->salida.="<option value='*/*' SELECTED>BODEGA PACIENTE</option>";
                         for($i=0;$i<sizeof($datos);$i++)
                         {
                              $this->salida.="<option value=".FormatoValor($datos[$i][existencia]).",".$datos[$i][bodega].">".$datos[$i][descripcion]."</option>";
                         }
                    }
                    elseif(!is_array($datos) AND ($_StockPaciente > 0))
                    {$this->salida.="<option value='*/*' SELECTED>BODEGA PACIENTE</option>";}
                    $this->salida.="</select>";
               }
               $this->salida.="</td>\n";
               
               // Observacion
               $this->salida.="<input type=\"hidden\" name=\"totalitario\" value=\"$totalitario\">";
               $this->salida.="<td align=\"center\" width=\"10%\"><b>OBSERVACION</b></td>";
               $this->salida.="<td width=\"40%\" align='center'><textarea class='textarea' name = 'observacion_suministro' cols = 40 rows = 3>".$_REQUEST['observacion_suministro']."</textarea></td>" ;
               $this->salida.="</tr>";
               $this->salida.="</table>";
          
          }//fin if duvan
          $this->salida.="</form>";
          $this->salida.="</div>\n";     
          $this->salida.="</div>";

          $ctl = Autocarga::factory("ClaseUtil");
          $this->salida .= $ctl->AcceptNum();
          $this->salida.="<div id='ContenedorCambioFactor' class='d2Container' style=\"display:none\">";
          $CambioFactor = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CambiarFactorConversion',array("tipo_solicitud"=>$tipo_solicitud,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));          
          $this->salida .= "    <form name=\"CambioFact\" action=\"\" method=\"post\">\n";
          $this->salida .= "    <div id='tituloFac' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarFac' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCambioFactor');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorFac' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoCambioFactor'>\n";
          $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\">FACTORES DE CONVERSION</td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td align=\"left\" width=\"50%\"> VALOR FACTOR DE CONVERSION\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td align=\"center\" width=\"50%\">\n";
          $this->salida .= "                        <input type=\"text\" class=\"input-text\" size=\"15\" id=\"valorFactor\" name=\"valorFactor\" value=\"\" onkeypress=\"return acceptNum(event)\"><div id='Unidad_Dos'></div>\n";
          $this->salida .= "                        <input type=\"hidden\" id=\"vectorFactor\" name=\"vectorFactor\" value=\"\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"guardarFac\" value=\"Guardar\" onclick=\"FuncionFactorEnvio(document.CambioFact)\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "            </table>\n";
          $this->salida .= "        </form>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";
     
          $javaC = "<script>\n";
          $javaC .= "   var contenedor\n";
          
          $javaC .= "   function CargarContenedor(Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "        contenedor = Elemento;\n";
          $javaC .= "   }\n";

          $javaC .= "   var titulo = 'titulo';\n";
          $javaC .= "   var hiZ = 2;\n";
          $javaC .= "   var DatosFactor = new Array();\n";
          $javaC .= "   var EnvioFactor = new Array();\n";
          
          $javaC .= "   function Iniciar(tit, suministro_id, Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "       Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
          
          $javaC .= "       document.getElementById('tituloAnul').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('errorAnul').innerHTML = '';\n";
          $javaC .= "       document.oculta.observacion.value = '';\n";
          $javaC .= "       document.oculta.suministro.value = suministro_id;\n";
          
          $javaC .= "       ele = xGetElementById('tituloAnul');\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          $javaC .= "       ele = xGetElementById('cerrarAnul');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function IniciarCapaSum(tit, Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "	   xResizeTo(Capa, 620, 'auto');\n";
          $javaC .= "       document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('error').innerHTML = '';\n";
          $javaC .= "       Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/4, xScrollTop()+24);\n";
          $javaC .= "       ele = xGetElementById('titulo');\n";
          $javaC .= "       xResizeTo(ele, 600, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
          $javaC .= "       ele = xGetElementById('cerrar');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 600, 0);\n";
          $javaC .= "   }\n";         

          $javaC .= "   function FuncionFactorEnvio(forma)\n";
          $javaC .= "   {\n";
          $javaC .= "	   ValidacionPremisos();\n";
          $javaC .= "   }\n";  
                  
          $javaC .= "   function IniciarCambioFac(tit, Elemento, ExistenciaFac, Codigo, Unidad, Dosificacion)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+60);\n";

          $javaC .= "       DatosFactor[0] = ExistenciaFac;\n";
          $javaC .= "       DatosFactor[1] = Codigo;\n";
          $javaC .= "       DatosFactor[2] = Unidad;\n";
          $javaC .= "       DatosFactor[3] = Dosificacion;\n";
          
          $javaC .= "       document.CambioFact.vectorFactor.value = DatosFactor;\n";
          $javaC .= "       document.getElementById('tituloFac').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       document.getElementById('Unidad_Dos').innerHTML = '<center>'+Dosificacion+'</center>';\n";
          $javaC .= "       document.getElementById('errorFac').innerHTML = '';\n";
          
          $javaC .= "       ele = xGetElementById('tituloFac');\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          
          $javaC .= "       ele = xGetElementById('cerrarFac');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";       
          
          $javaC .= "   function myOnDragStart(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "     window.status = '';\n";
          $javaC .= "     if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
          $javaC .= "     else xZIndex(ele, hiZ++);\n";
          $javaC .= "     ele.myTotalMX = 0;\n";
          $javaC .= "     ele.myTotalMY = 0;\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDrag(ele, mdx, mdy)\n";
          $javaC .= "   {\n";
          $javaC .= "     if (ele.id == titulo) {\n";
          $javaC .= "       xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
          $javaC .= "     }\n";
          $javaC .= "     else {\n";
          $javaC .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
          $javaC .= "     }  \n";
          $javaC .= "     ele.myTotalMX += mdx;\n";
          $javaC .= "     ele.myTotalMY += mdy;\n";
          $javaC .= "   }\n";
               
          $javaC .= "   function myOnDragEnd(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "   }\n";
          
          $javaC.= "function MostrarCapa(Elemento)\n";
          $javaC.= "{\n;";
          $javaC.= "    capita = xGetElementById(Elemento);\n";
          $javaC.= "    capita.style.display = \"\";\n";
          $javaC.= "}\n";
          
          $javaC.= "function Cerrar(Elemento)\n";
          $javaC.= "{\n";
          $javaC.= "    capita = xGetElementById(Elemento);\n";          
          $javaC.= "    capita.style.display = \"none\";\n";          
          $javaC.= "}\n";                    
          
          $javaC.= "function load_page()\n";
          $javaC.= "{\n";
          $javaC.= "    location.reload();\n";
          $javaC.= "}\n";
          
          $javaC.= "</script>\n";
          $this->salida.= $javaC;
     
          $this->salida.="<div id='ContenedorCapaAnular' class='d2Container' style=\"display:none\">";
          $AnularSum = ModuloGetURL('app','EE_AdministracionMedicamentos','user','AnularSuminitrosPaciente',array("tipo_solicitud"=>$tipo_solicitud,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));          
          $this->salida .= "    <form name=\"oculta\" action=\"$AnularSum\" method=\"post\">\n";
          $this->salida .= "    <div id='tituloAnul' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarAnul' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCapaAnular');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorAnul' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='ContenidoCapaAnular'>\n";
          $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "                    <td colspan=\"3\">JUSTIFICACION</td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"3\">\n";
          $this->salida .= "                        <textarea class=\"textarea\" name=\"observacion\" rows=\"3\" style=\"width:100%\"></textarea>\n";
          $this->salida .= "                        <input type=\"hidden\" name=\"suministro\" value=\"\">";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"3\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "            </table>\n";
          $this->salida .= "        </form>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";

          if($_REQUEST['bandera'] == '')
          { $bandera = 0; }
          else
          { $bandera = $_REQUEST['bandera']; }
          
          if($bandera == 1)
          {
               foreach($_Suministros as $k => $control)
               {
                    if($control)
                    {
                         $this->salida.="<table  align=\"center\" border=\"0\" width=\"85%\">";
                         $this->salida.="<tr class=\"modulo_table_title\">";
                         $this->salida.="  <td align=\"left\" colspan=\"5\">ULTIMA DOSIS SUMINISTRADA: ".$_NomProducto[$k]."</td>";
                         $this->salida.="</tr>";
                         $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida.="  <td width=\"15%\">FECHA SUM.</td>";
                         $this->salida.="  <td width=\"15%\">HORA SUM.</td>";
                         $this->salida.="  <td width=\"15%\">SUMINISTRADO</td>";
                         //$this->salida.="  <td width=\"15%\">ENTREGAS PAC.</td>";
                         $this->salida.="  <td width=\"15%\">DESECHOS</td>";
                         $this->salida.="  <td width=\"50%\">USUARIO</td>";
                         $this->salida.="</tr>";
               
                         $estilo='modulo_list_claro';
                         $this->salida.="<tr class=\"$estilo\">";
                         
                         
                         if($tipo_solicitud == "M")
                         { 
                              $factor = $this->SeleccionFactorConversion($vect[0][codigo_producto], $vect[0][cod_presentacion], $vect[0][unidad_dosificacion]);
                              $UnidadS = $vect[0][unidad_dosificacion]; 
                         }
                         else
                         { 
                              $exp = explode(" ",$vect[$k][dosis]);
                              $UnidadS = $exp[1]." ".$exp[2];
                              $factor = $this->SeleccionFactorConversion($vect[$k][codigo_producto], $vect[$k][cod_presentacion], $UnidadS);
                         }
                         
                        //REEMPLAZADO POR Jonier Murillo Hurtado
/*                        
                        if($factor[sw_unidad_minima] == null or $factor[sw_unidad_minima] == 0){
                            $factorC = $factor[factor_conversion];
                            
                        }else{
                            $factorC = $factor[sw_unidad_minima];
                        }
*/                         
                         $factorC = $factor[factor_conversion];
                         
                         if($factorC)
                         {
                              // Conversiones de Suministros.
                              // Suministros
                              $CS = (($control[0][cantidad_suministrada] * $factorC) / 100);
                              $CS = $CS * 100;
                              
                              // Desechos
                              $CP = (($control[0][cantidad_perdidas] * $factorC) / 100);
                              $CP = $CP * 100;
                         }else{
                              $CS = $control[0][cantidad_suministrada];
                              $CP = $control[0][cantidad_perdidas];
                         }
                         
                         $this->salida.="  <td align=\"center\" width=\"15%\">".$this->FechaStamp($control[0][fecha_realizado])."</td>";
                         $this->salida.="  <td align=\"center\" width=\"15%\">".$this->HoraStamp($control[0][fecha_realizado])."</td>";
                         $this->salida.="  <td align=\"center\" width=\"20%\">".$CS."&nbsp;".$UnidadS."</td>";
                         //$this->salida.="  <td align=\"center\" width=\"15%\">".$control[0][cantidad_aprovechada]."</td>";
                         $this->salida.="  <td align=\"center\" width=\"20%\">".$CP."&nbsp;".$UnidadS."</td>";
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$control[0][nombre]."</td>";
                         $this->salida.="</tr>";
                         
                         $total_suministro = $CS;
                         
                         //$total_EP =  $control[0][cantidad_aprovechada];
                         
                         $total_desecho = $CP;
                         
                         $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida.="  <td colspan=\"2\">TOTAL SUMINISTRADO</td>";
                         
                         $this->salida.="  <td>".$total_suministro."&nbsp;".$UnidadS."</td>";
                         //$this->salida.="  <td>".$total_EP."&nbsp;".$control[0][unidad_dosificacion]."</td>";
                         $this->salida.="  <td>".$total_desecho."&nbsp;".$UnidadS."</td>";
                         
                         $href1 = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Control_Suministro',array("tipo_solicitud"=>$tipo_solicitud,"vect"=>$vect,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bandera"=>0));
                         $this->salida.="  <td width=\"55%\"><a href=\"$href1\">Ver Todos los Suministros</a></td>";
                         $this->salida.="</tr>";
                         $this->salida.="</table><br>";
                    }
               }
          }
          elseif($bandera == 0)
          {
               foreach($_Suministros as $k => $control)
               {
                    if($control)
                    {
                         $this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
                         $this->salida.="<tr class=\"modulo_table_title\">";
                         $this->salida.="  <td align=\"left\" colspan=\"6\">TOTALIDAD DE DOSIS SUMINISTRADAS: ".$_NomProducto[$k]."</td>";
                         $this->salida.="</tr>";
                         $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida.="  <td width=\"10%\">FECHA SUM.</td>";
                         $this->salida.="  <td width=\"10%\">HORA SUM.</td>";
                         $this->salida.="  <td width=\"15%\">SUMINISTRADO</td>";
                         //$this->salida.="  <td width=\"15%\">ENTREGAS PAC.</td>";
                         $this->salida.="  <td width=\"15%\">DESECHOS</td>";
                         $this->salida.="  <td width=\"50%\">USUARIO</td>";
                         $this->salida.="  <td width=\"5%\">ANULAR</td>";
                         $this->salida.="</tr>";
                         $total_suministro=0;
                         for($i=0;$i<sizeof($control);$i++)
                         {
                              if( $i % 2){ $estilo='modulo_list_claro';}
                              else {$estilo='modulo_list_oscuro';}
                              
                              if($tipo_solicitud == "M")
                              { 
                                   $factor = $this->SeleccionFactorConversion($vect[0][codigo_producto], $vect[0][cod_presentacion], $vect[0][unidad_dosificacion]);
                                   $UnidadS = $vect[0][unidad_dosificacion]; 
                              }
                              else
                              { 
                                   $exp = explode(" ",$vect[$k][dosis]);
                                   $UnidadS = $exp[1]." ".$exp[2];
                                   $factor = $this->SeleccionFactorConversion($vect[$k][codigo_producto], $vect[$k][cod_presentacion], $UnidadS);
                              }

                              //REEMPLAZADO POR Jonier Murillo Hurtado
/*                              
                              if($factor[sw_unidad_minima] == null or $factor[sw_unidad_minima] == 0){
                                  $factorC = $factor[factor_conversion];
                                  
                              }else{
                                  $factorC = $factor[sw_unidad_minima];
                              }
*/                              
                              $factorC = $factor[factor_conversion];

                              if($factorC)
                              {
                                   // Conversiones de Suministros.
                                   // Suministros
                                   $CS = (($control[$i][cantidad_suministrada] * $factorC) / 100);
                                   $CS = $CS * 100;
                                   
                                   // Desechos
                                   $CP = (($control[$i][cantidad_perdidas] * $factorC) / 100);
                                   $CP = $CP * 100;
                              }else{
                                   $CS = $control[$i][cantidad_suministrada];
                                   $CP = $control[$i][cantidad_perdidas];
                              }
                              

                              
                              
                              $this->salida.="<tr class=\"$estilo\">";
                              $this->salida.="  <td align=\"center\" width=\"10%\">".$this->FechaStamp($control[$i][fecha_realizado])."</td>";
                              $this->salida.="  <td align=\"center\" width=\"10%\">".$this->HoraStamp($control[$i][fecha_realizado])."</td>";
                              $this->salida.="  <td align=\"center\" width=\"20%\">".$CS."&nbsp;".$UnidadS."</td>";
                              //$this->salida.="  <td align=\"center\" width=\"15%\">".$control[$i][cantidad_aprovechada]."</td>";
                              $this->salida.="  <td align=\"center\" width=\"20%\">".$CP."&nbsp;".$UnidadS."</td>";
                              $this->salida.="  <td align=\"left\" width=\"50%\">".$control[$i][nombre]."</td>";
                              $javaAccionAnular = "javascript:MostrarCapa('ContenedorCapaAnular');Iniciar('ANULAR SUMINISTRO','".$control[$i][suministro_id]."','ContenedorCapaAnular');CargarContenedor('ContenedorCapaAnular');";
                              $this->salida.="  <td width=\"5%\" align=\"center\"><a href=\"$javaAccionAnular\"><img src=\"". GetThemePath() ."/images/delete.gif\" title='$title' border='0'></a></td>";
                              $this->salida.="</tr>";

                              $total_suministro = $total_suministro + $CS;
                              
                              //$total_EP = $total_EP + $control[$i][cantidad_aprovechada];
                              
                              $total_desecho = $total_desecho + $CP;
                         }
     
                         $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida.="  <td colspan=\"2\">TOTAL SUMINISTRADO</td>";
                         
                         $this->salida.="  <td>".$total_suministro."&nbsp;".$UnidadS."</td>";
                         //$this->salida.="  <td>".$total_EP."&nbsp;".$control[$i][unidad_dosificacion]."</td>";
                         $this->salida.="  <td>".$total_desecho."&nbsp;".$UnidadS."</td>";
                         
                         $href1 = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Control_Suministro',array("tipo_solicitud"=>$tipo_solicitud,"vect"=>$vect,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bandera"=>1));
                         $this->salida.="  <td colspan=\"2\"><a href=\"$href1\">Ver Ultimo Suministro</a></td>";
                         $this->salida.="</tr>";
                         $this->salida.="</table><br>";
                    }
               }
          }
     
          //BOTON DEVOLVER
          $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
          $this->salida .= "<form name=\"forma\" action=\"$href\" method=\"post\">";
          $this->salida .= "<tr><td  colspan = 6 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td></tr><br>";
          $this->salida .= themeCerrarTabla();
          return true;
     }
    /*
    * Control_SuministroInsumos - Funcion la cual me permite realizar el suministro de 
    * los insumos
    */
    function Control_SuministroInsumos()
    {
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $this->IncludeJS("RemoteScripting");
      $this->IncludeJS("ScriptsRemotos/misfunciones.js",'app','EE_AdministracionMedicamentos');
      SessionSetVar("Usuario",UserGetUID());
      SessionSetVar("V_estacion",$_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA']);
      SessionSetVar("U_estacion",$_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO']);
      
      //vector de insumos 
      unset($_SESSION['ESTACION_ENF_MED_VECT']['DATA']);

      $datosPaciente = $_REQUEST['datosPaciente'];
      $datos_estacion = $_REQUEST['datos_estacion'];
      $tipo_solicitud = $_REQUEST['tipo_solicitud'];
      $vect = $_REQUEST['datos_producto'];
      // Array para Mostrar Suministros.
      $_Suministros = array();
      $_NomProducto = array();
      
      $this->salida = ThemeAbrirTabla('CONTROL DE SUMINISTRO INSUMOS');
      $this->salida .= "<table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "  <tr class=\"modulo_table_title\">\n";
      $this->salida .= "    <td>PACIENTE</td>\n";
      $this->salida .= "    <td>HABITACION</td>\n";
      $this->salida .= "    <td>CAMA</td>\n";
      $this->salida .= "    <td>PISO</td>\n";
      $this->salida .= "  </tr>\n";
      $this->salida .= "  <tr align='center' class='modulo_list_oscuro'>\n";
      $this->salida .= "    <td>".$datosPaciente[nombre_completo]."</td>\n";
      $this->salida .= "    <td>".$datosPaciente[pieza]."</td>\n";
      $this->salida .= "    <td>".$datosPaciente[cama]."</td>\n";
      $this->salida .= "    <td>".$datos_estacion[estacion_descripcion]."</td>\n";
      $this->salida .= "  </tr>\n";
      $this->salida .= "</table>\n";
      $this->salida .= "<br>\n";
      $this->salida .= "<table  align=\"center\" border=\"0\"  width=\"80%\">";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "</table>";
 
      $this->salida .= "<table  align=\"center\" border=\"0\"  width=\"85%\">";
      $accion = ModuloGetURL('app','EE_AdministracionMedicamentos','user','ConfirmarSuministrosInsumos',array("datos_producto"=>$vect,"tipo_solicitud"=>$tipo_solicitud,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
      $this->salida .= "<form name=\"formades\" action=\"$accion\" method=\"post\">";
 
      //Programa de Relacion Insumos - Medicamentos.
      $java ="<script>";
      $java.="function CambioValor(valor,frm,identi){";
      $java.=" vector=valor.split(',');";               
      $java.=" frm.codigo_producto_S[identi].value=vector[0];";
      $java.=" frm.cantidad[identi].value=vector[2];"; 
      $java.=" frm.checo[identi].value=vector[0]+','+vector[2];";  
      $java.="};";
      $java.="</script>";
      $this->salida.= $java;
 
      $this->salida .= "<tr class=\"modulo_table_title\">";
      $this->salida .= "  <td align=\"left\" colspan=\"4\">CONTROL DEL MEDICAMENTO:</td>";
      $this->salida .= "</tr>";
      $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
      $this->salida .= "  <td align=\"center\" width=\"7%\">CODIGO</td>";
      $this->salida .= "  <td align=\"center\" width=\"30%\">PRODUCTO</td>";
      $this->salida .= "  <td align=\"center\" width=\"14%\">BODEGA PACIENTE</td>";
      $this->salida .= "  <td align=\"center\" width=\"14%\">SUMINISTRAR</td>";
      $this->salida .= "</tr>";
      //$this->salida.=print_r($_REQUEST);
      for($x=0;$x<sizeof($vect);$x++)
      {
        unset($_BodegaPaciente);
        $datos = $this->GetEstacionBodega_Existencias($datos_estacion,2,$vect[$x][codigo_producto]);

        // Informacion de Conteo de medicamentos Solicitados para validaciones.
        $_BodegaPaciente = $this->GetCantidades_BodegaPaciente($datosPaciente[ingreso],$vect[$x][codigo_producto]);

        //1 Cantidades reales en la Bodega del Paciente.
        $_StockPaciente = $_BodegaPaciente[stock_almacen];
        if($_StockPaciente == 0)
        { $_StockPaciente = $_BodegaPaciente[stock_paciente]; }

        $_StockPaciente = $_StockPaciente - $_BodegaPaciente[cantidad_en_devolucion];

        // Vectores de suministros
        $_Ubodega = explode(" ",$vect[$x][dosis]);
        $control = $this->Consultar_Control_SuministroInsumos($vect[$x][codigo_producto], $datosPaciente[ingreso], $tipo_solicitud);
        array_push($_Suministros,$control);
        array_push($_NomProducto,$vect[$x][producto]);
        $catidadBodega[$x] = $_StockPaciente;

        $this->salida .= "  <tr class='modulo_list_claro'>";
        $this->salida .= "    <td align=\"center\" width=\"7%\">".$vect[$x][codigo_producto]."</td>";
        $this->salida .= "    <input type=\"hidden\" name=\"datos_SUM[]\" value=\"".$vect[$x][codigo_producto]."\">";
        $this->salida .= "    <input type=\"hidden\" name=\"ingreso_F[]\" value=\"".$vect[$x][ingreso]."\">";
        $this->salida .= "    <input type=\"hidden\" name=\"num_F[]\" value=\"".$vect[$x][num_formulacion]."\">";
        $this->salida .= "    <td align=\"center\" width=\"30%\">".$vect[$x][descripcion]."</td>";
        $this->salida .= "    <td align=\"right\" width=\"14%\">";
        $this->salida .= $_StockPaciente."&nbsp;".$vect[$x][presentacion];
        $this->salida .= "    </td>";
        $this->salida .= "  <td align=\"center\" width=\"14%\" rowspan=\"".sizeof($vect)."\">\n";
           
        // SUMINISTROS
        if($x == 0)
        {
          $javaAccionSuministros = "javascript:MostrarCapa('ContenedorSuministrosParciales');IniciarCapaSum('SUMINISTROS PARCIALES','ContenedorSuministrosParciales');CargarContenedor('ContenedorSuministrosParciales');";
          if($_StockPaciente <> 0)
          {
            $this->salida .= "   <a href=\"$javaAccionSuministros\"><img src=\"". GetThemePath() ."/images/pparamedin.png\" border='0'></a>";
          }
          else
          {
            $this->salida .= "  <a ><img src=\"". GetThemePath() ."/images/pparamedin.png\" border='0'></a>";
          }
        }
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
 

           // Controles               
           if($tipo_solicitud == "M")
           {
                // Cantidad Recetada. Las unidades totales q recomendo el profesional
                $cantidad_recetada = $vect[$x][cantidad];
                $this->salida.="<input type='hidden' name='cantidad_recetada[]' value='".$cantidad_recetada."'>";
                // Dosis
                $dosis = $vect[$x][dosis];
                $this->salida.="<input type='hidden' name='dosis[]' value='".$dosis."'>";                    
                // BodegaPaciente
                $this->salida.="<input type='hidden' name='BodegaPaciente[]' value='".$_StockPaciente."'>";
           }else{
                // Cantidad Recetada. Las unidades totales q recomendo el profesional
                $cantidad_recetada = $vect[$x][cantidad] * $_Ubodega[0];
                $this->salida.="<input type='hidden' name='cantidad_recetada[]' value='".$cantidad_recetada."'>";
                // Dosis
                $dosis = $_Ubodega[0];
                $this->salida.="<input type='hidden' name='dosis[]' value='".$dosis."'>";                    
                // BodegaPaciente
                $this->salida.="<input type='hidden' name='BodegaPaciente[]' value='".$_StockPaciente."'>";
           }
      }

      $this->salida.="</table><br>";
      
      
      $this->salida.="<div id='ContenedorSuministrosParciales' class='d2Container' style=\"display:none\"><br>";
      $this->salida .= "    <div id='titulo' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorSuministrosParciales');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $this->salida .= "    <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='ContenidoSuministrosParciales' class='d2Content' style=\"height:300\">\n";
      for($i=0;$i<sizeof($vect);$i++)
      {
        $existeFac = 0;
           $_Ubodega = explode(" ",$vect[$i][dosis]);
           if($tipo_solicitud == "M")
           {
            $dosificacion = $this->SeleccionUnidadSuministro($vect[$i][unidad_dosificacion], $vect[$i][cod_presentacion]);
           }else{
            $_UnidadSum = $_Ubodega[1]." ".$_Ubodega[2];
            $dosificacion = $this->SeleccionUnidadSuministro($_UnidadSum, $vect[$i][cod_presentacion]);               
           }
           
           $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
           $this->salida .= "                <tr class=\"modulo_table_list_title\">\n";
           $this->salida .= "                    <td align=\"left\">".$vect[$i][descripcion]."</td>\n";
           $this->salida .= "                    <td align=\"right\">&nbsp;</td>";
           $this->salida .= "                </tr>\n";
           $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
           $this->salida .= "                    <td align=\"center\" width=\"50%\">SUMINISTRAR</td>\n";
           $this->salida .= "                    <td align=\"center\" width=\"50%\">DESECHOS</td>\n";
           $this->salida .= "                </tr>\n";
           $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
           $this->salida .= "                    <td align=\"center\" width=\"50%\">\n";
           if($tipo_solicitud == "M")
           {
                $descripcionU = $vect[$i][unidad_dosificacion];
           }else{
                $descripcionU = $_UnidadSum;
           }
           
           if($_REQUEST['cantidad_suministrada'][$i] == '')
           {
                $this->salida.="<input type=\"text\" class=\"input-text\" size=\"5\" name=\"cantidad_suministrada[]\">&nbsp; ".$descripcionU."";
           }else{
                $this->salida.="<input type=\"text\" class=\"input-text\" size=\"5\" name=\"cantidad_suministrada[]\" value =\"".$_REQUEST['cantidad_suministrada'][$i]."\">&nbsp; ".$descripcionU."";               
           }
           $this->salida.="</td>";
           
           //PERDIDAS
           $this->salida.="  <td align=\"center\" width=\"50%\">";
           
           if($tipo_solicitud == "M")
           {
                $descripcionU = $vect[$i][unidad_dosificacion];
           }else{
                $descripcionU = $_UnidadSum;
           }
           
           if($_REQUEST['perdidas'][$i] == '')
           {
                $this->salida.="<input type=\"text\" class=\"input-text\" size=\"5\" name=\"perdidas[]\">&nbsp; ".$descripcionU."";
           }else{
                $this->salida.="<input type=\"text\" class=\"input-text\" size=\"5\" name=\"perdidas[]\" value =\"".$_REQUEST['perdidas'][$i]."\">&nbsp; ".$descripcionU."";               
           }
           $this->salida.="</td>";
           
           $this->salida .= "            </table>\n";
      }

      $this->salida .= "            <br><table width=\"100%\" align=\"center\">\n";
      $this->salida .= "                <tr>\n";
      $this->salida .= "                    <td align=\"center\">\n";
      $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"Registrar Suministro Insumos\">\n";
      $this->salida .= "                    </td>\n";
      $this->salida .= "                </tr>\n";
      $this->salida .= "            </table>\n";

      if( $i % 2){ $estilo='modulo_list_claro';}
      else {$estilo='modulo_list_oscuro';}
      
      // Validacion de restriccion Cuando no hay cantidades en Bodega Paciente, ni en Bodegas de consumo directo          
      if(!is_array($datos) && $_StockPaciente <= 0)
      {
           $title="NO HAY EXISTENCIAS PARA LA BODEGA DEL PACIENTE NI HAY EXISTENCIAS EN LAS OTRAS BODEGAS PARA ESTE PRODUCTO";
           $this->salida.="<br><br><DIV ALIGN='CENTER'><LABEL CLASS='label_mark'>$title</LABEL></DIV><br><br>";
           $this->salida.="<input type=\"hidden\" name=\"bodega\" value=\"*/*\">";
      }
      else
      {
           $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"100%\">";
           $this->salida.="<tr class=\"modulo_table_title\">";
           $this->salida.="<td align=\"left\" colspan=\"4\">INGRESAR SUMINISTRO</td>";
           $this->salida.="</tr>";
           
           $this->salida.="<tr class='modulo_list_claro'>";
           
           // Seleccion Hora               
           $this->salida.="<td colspan=\"1\" align=\"center\" width=\"30%\"><b>HORA:</b></p>";
           
           $rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
           $hora_inicio_turno = "00:00:00";
           $rango_turno = date("H");
           if(date("H:i:s") <= $hora_inicio_turno)
           {
                list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
                list($h,$m,$s)=explode(":",$hora_control);
           }
           else
           {//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
                list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
                list($h,$m,$s)=explode(":",$hora_control);
           }
 
           $i = 0;
           $rangomin = $rango_turno - 6;
           $this->salida.= "<select name='selectHora' class='select'>\n";
           for($j = $rangomin; $j<=$rango_turno; $j++)
           {
                list($anno, $mes, $dia)=explode("-",$fecha_control);
                if ($i==23)
                {
                     list($h,$m,$s)=explode(":",$hora_inicio_turno);
                     $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                     $fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
                     $fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
                }
                else
                {
                     list($h,$m,$s)=explode(":",$hora_inicio_turno);
                     $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                     $fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
                     $fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
                }
                if(empty($selectHora)){
                     if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
                }
                else
                {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                     list($A,$B) = explode(" ",$selectHora);
                     if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
                }
                #################################################
                list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
                if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
                     $show = "Hoy a las";
                }
                elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
                     $show = "Ma???ana a las";
                }
                elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
                     $show = "Ayer a las";
                }
                else{
                     $show = $fecha_control;
                }
                ###########################
                $this->salida .= "<option value='".date("Y-m-d")." ".$i."' selected $selected>".$i."</option>\n";
           }//fin for
           
           if(!empty($_REQUEST['selectHora']))
           {
                $horas_R = explode(" ", $_REQUEST['selectHora']);
                $this->salida .= "<option value='".date("Y-m-d")." ".$horas_R[1]."' selected='true'>".$horas_R[1]."</option>\n";
           }
           $this->salida.= "</select>:&nbsp;\n";
           $this->salida.= "<select name='selectMinutos' class='select'>\n";
           for($j=0; $j<=59; $j++)
           {
                if(empty($selectMinutos)){
                     if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
                }
                else
                {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                     list($A,$B) = explode(" ",$selectMinutos);
                     if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
                }
                if ($j<10){
                     $this->salida .= "         <option value='0$j:00' $selected>0$j</option>\n";
                }
                else{
                     $this->salida .= "         <option value='$j:00' $selected>$j</option>\n";
                }
           }
           $this->salida .= "</select>\n";
           $this->salida .= "</td>\n";
           
           // Seleccion bodega
           $this->salida.="<td  align=\"center\" width=\"30%\"><b>BODEGA:</b><p>";
           if(!is_array($datos) && $_StockPaciente <= 0)
           {
                $title="NO HAY EXISTENCIAS PARA LA BODEGA DEL PACIENTE NI HAY EXISTENCIAS EN LAS OTRAS BODEGAS PARA ESTE PRODUCTO";
                $this->salida.="<img src=\"". GetThemePath() ."/images/preguntaac.png\" title='$title' border='0'>";
           }
           else
           {
                $this->salida.="<select name=bodega class='select'>";
                if(is_array($datos))
                {
                     $this->salida.="<option value='*/*' SELECTED>BODEGA PACIENTE</option>";
                     //$this->salida.="<option value='*/*' SELECTED>---SELECCIONE---</option>";
                     for($i=0;$i<sizeof($datos);$i++)
                     {
                          $this->salida.="<option value=".FormatoValor($datos[$i][existencia]).",".$datos[$i][bodega].">".$datos[$i][descripcion]."</option>";
                     }
                }
                elseif(!is_array($datos) AND ($_StockPaciente > 0))
                {$this->salida.="<option value='*/*' SELECTED>BODEGA PACIENTE</option>";}
    //{$this->salida.="<option value='*/*' SELECTED>---SELECCIONE---</option>";}
                $this->salida.="</select>";
           }
           $this->salida.="</td>\n";
           
           // Observacion
           $this->salida.="<input type=\"hidden\" name=\"totalitario\" value=\"$totalitario\">";
           $this->salida.="<td align=\"center\" width=\"10%\"><b>OBSERVACION</b></td>";
           $this->salida.="<td width=\"40%\" align='center'><textarea class='textarea' name = 'observacion_suministro' cols = 40 rows = 3>".$_REQUEST['observacion_suministro']."</textarea></td>" ;
           $this->salida.="</tr>";
           $this->salida.="</table>";
      
      }//fin if duvan
      $this->salida.="</form>";
      $this->salida.="</div>\n";     
      $this->salida.="</div>";

      $this->salida.="<div id='ContenedorCambioFactor' class='d2Container' style=\"display:none\">";
      $CambioFactor = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CambiarFactorConversion',array("tipo_solicitud"=>$tipo_solicitud,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));          
      $this->salida .= "    <form name=\"CambioFact\" action=\"\" method=\"post\">\n";
      $this->salida .= "    <div id='tituloFac' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='cerrarFac' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCambioFactor');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $this->salida .= "    <div id='errorFac' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='ContenidoCambioFactor'>\n";
      $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
      $this->salida .= "                    <td colspan=\"2\">FACTORES DE CONVERSION</td>\n";
      $this->salida .= "                </tr>\n";
      $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
      $this->salida .= "                    <td align=\"left\" width=\"50%\"> VALOR FACTOR DE CONVERSION\n";
      $this->salida .= "                    </td>\n";
      $this->salida .= "                    <td align=\"center\" width=\"50%\">\n";
      $this->salida .= "                        <input type=\"text\" class=\"input-text\" size=\"15\" id=\"valorFactor\" name=\"valorFactor\" value=\"\"><div id='Unidad_Dos'></div>\n";
      $this->salida .= "                        <input type=\"hidden\" id=\"vectorFactor\" name=\"vectorFactor\" value=\"\">\n";
      $this->salida .= "                    </td>\n";
      $this->salida .= "                </tr>\n";
      $this->salida .= "                <tr class=\"hc_table_submodulo_list_title\">\n";
      $this->salida .= "                    <td colspan=\"2\" align=\"center\">\n";
      $this->salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"guardarFac\" value=\"Guardar\" onclick=\"FuncionFactorEnvio(document.CambioFact)\">\n";
      $this->salida .= "                    </td>\n";
      $this->salida .= "                </tr>\n";
      $this->salida .= "            </table>\n";
      $this->salida .= "        </form>\n";
      $this->salida .= "    </div>\n";     
      $this->salida.="</div>";
 
      $javaC = "<script>\n";
      $javaC .= "   var contenedor\n";
      
      $javaC .= "   function CargarContenedor(Elemento)\n";
      $javaC .= "   {\n";
      $javaC .= "        contenedor = Elemento;\n";
      $javaC .= "   }\n";

      $javaC .= "   var titulo = 'titulo';\n";
      $javaC .= "   var hiZ = 2;\n";
      $javaC .= "   var DatosFactor = new Array();\n";
      $javaC .= "   var EnvioFactor = new Array();\n";
      
      $javaC .= "   function Iniciar(tit, suministro_id, Elemento)\n";
      $javaC .= "   {\n";
      $javaC .= "       Capa = xGetElementById(Elemento);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
      
      $javaC .= "       document.getElementById('tituloAnul').innerHTML = '<center>'+tit+'</center>';\n";
      $javaC .= "       document.getElementById('errorAnul').innerHTML = '';\n";
      $javaC .= "       document.oculta.observacion.value = '';\n";
      $javaC .= "       document.oculta.suministro.value = suministro_id;\n";
      
      $javaC .= "       ele = xGetElementById('tituloAnul');\n";
      $javaC .= "       xResizeTo(ele, 280, 20);\n";
      $javaC .= "       xMoveTo(ele, 0, 0);\n";
      $javaC .= "       ele = xGetElementById('cerrarAnul');\n";
      $javaC .= "       xResizeTo(ele,20, 20);\n";
      $javaC .= "       xMoveTo(ele, 280, 0);\n";
      $javaC .= "   }\n";
      
      $javaC .= "   function IniciarCapaSum(tit, Elemento)\n";
      $javaC .= "   {\n";
      $javaC .= "	   Capa = xGetElementById(Elemento);\n";
      $javaC .= "	   xResizeTo(Capa, 620, 'auto');\n";
      $javaC .= "       document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
      $javaC .= "       document.getElementById('error').innerHTML = '';\n";
      $javaC .= "       Capa = xGetElementById(Elemento);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/4, xScrollTop()+24);\n";
      $javaC .= "       ele = xGetElementById('titulo');\n";
      $javaC .= "       xResizeTo(ele, 600, 20);\n";
      $javaC .= "       xMoveTo(ele, 0, 0);\n";
      $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $javaC .= "       ele = xGetElementById('cerrar');\n";
      $javaC .= "       xResizeTo(ele,20, 20);\n";
      $javaC .= "       xMoveTo(ele, 600, 0);\n";
      $javaC .= "   }\n";         

      $javaC .= "   function FuncionFactorEnvio(forma)\n";
      $javaC .= "   {\n";
      $javaC .= "	   ValidacionPremisos();\n";
      $javaC .= "   }\n";  
              
      $javaC .= "   function IniciarCambioFac(tit, Elemento, ExistenciaFac, Codigo, Unidad, Dosificacion)\n";
      $javaC .= "   {\n";
      $javaC .= "	   Capa = xGetElementById(Elemento);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+60);\n";

      $javaC .= "       DatosFactor[0] = ExistenciaFac;\n";
      $javaC .= "       DatosFactor[1] = Codigo;\n";
      $javaC .= "       DatosFactor[2] = Unidad;\n";
      $javaC .= "       DatosFactor[3] = Dosificacion;\n";
      
      $javaC .= "       document.CambioFact.vectorFactor.value = DatosFactor;\n";
      $javaC .= "       document.getElementById('tituloFac').innerHTML = '<center>'+tit+'</center>';\n";
      $javaC .= "       document.getElementById('Unidad_Dos').innerHTML = '<center>'+Dosificacion+'</center>';\n";
      $javaC .= "       document.getElementById('errorFac').innerHTML = '';\n";
      
      $javaC .= "       ele = xGetElementById('tituloFac');\n";
      $javaC .= "       xResizeTo(ele, 280, 20);\n";
      $javaC .= "       xMoveTo(ele, 0, 0);\n";
      
      $javaC .= "       ele = xGetElementById('cerrarFac');\n";
      $javaC .= "       xResizeTo(ele,20, 20);\n";
      $javaC .= "       xMoveTo(ele, 280, 0);\n";
      $javaC .= "   }\n";       
      
      $javaC .= "   function myOnDragStart(ele, mx, my)\n";
      $javaC .= "   {\n";
      $javaC .= "     window.status = '';\n";
      $javaC .= "     if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
      $javaC .= "     else xZIndex(ele, hiZ++);\n";
      $javaC .= "     ele.myTotalMX = 0;\n";
      $javaC .= "     ele.myTotalMY = 0;\n";
      $javaC .= "   }\n";
      
      $javaC .= "   function myOnDrag(ele, mdx, mdy)\n";
      $javaC .= "   {\n";
      $javaC .= "     if (ele.id == titulo) {\n";
      $javaC .= "       xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
      $javaC .= "     }\n";
      $javaC .= "     else {\n";
      $javaC .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
      $javaC .= "     }  \n";
      $javaC .= "     ele.myTotalMX += mdx;\n";
      $javaC .= "     ele.myTotalMY += mdy;\n";
      $javaC .= "   }\n";
           
      $javaC .= "   function myOnDragEnd(ele, mx, my)\n";
      $javaC .= "   {\n";
      $javaC .= "   }\n";
      
      $javaC.= "function MostrarCapa(Elemento)\n";
      $javaC.= "{\n;";
      $javaC.= "    capita = xGetElementById(Elemento);\n";
      $javaC.= "    capita.style.display = \"\";\n";
      $javaC.= "}\n";
      
      $javaC.= "function Cerrar(Elemento)\n";
      $javaC.= "{\n";
      $javaC.= "    capita = xGetElementById(Elemento);\n";          
      $javaC.= "    capita.style.display = \"none\";\n";          
      $javaC.= "}\n";                    
      
      $javaC.= "function load_page()\n";
      $javaC.= "{\n";
      $javaC.= "    location.reload();\n";
      $javaC.= "}\n";
      
      $javaC.= "</script>\n";
      $this->salida.= $javaC;
 
      $this->salida.="<div id='ContenedorCapaAnular' class='d2Container' style=\"display:none\">";
      $AnularSum = ModuloGetURL('app','EE_AdministracionMedicamentos','user','AnularSuminitrosInsumosPaciente',array("tipo_solicitud"=>$tipo_solicitud,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"datos_producto"=>$vect));          
      $this->salida .= "    <form name=\"oculta\" action=\"$AnularSum\" method=\"post\">\n";
      $this->salida .= "    <div id='tituloAnul' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='cerrarAnul' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCapaAnular');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $this->salida .= "    <div id='errorAnul' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='ContenidoCapaAnular'>\n";
      $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "                <tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "                    <td colspan=\"3\">JUSTIFICACION</td>\n";
      $this->salida .= "                </tr>\n";
      $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                    <td colspan=\"3\">\n";
      $this->salida .= "                        <textarea class=\"textarea\" name=\"observacion\" rows=\"3\" style=\"width:100%\"></textarea>\n";
      $this->salida .= "                        <input type=\"hidden\" name=\"suministro\" value=\"\">";
      $this->salida .= "                    </td>\n";
      $this->salida .= "                </tr>\n";
      $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                    <td colspan=\"3\" align=\"center\">\n";
      $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\">\n";
      $this->salida .= "                    </td>\n";
      $this->salida .= "                </tr>\n";
      $this->salida .= "            </table>\n";
      $this->salida .= "        </form>\n";
      $this->salida .= "    </div>\n";     
      $this->salida.="</div>";

      if($_REQUEST['bandera'] == '')
      { $bandera = 0; }
      else
      { $bandera = $_REQUEST['bandera']; }
      
      if($bandera == 1) 
      {
           foreach($_Suministros as $k => $control)
           {
                if($control)
                {
                     $this->salida.="<table  align=\"center\" border=\"0\" width=\"85%\">";
                     $this->salida.="<tr class=\"modulo_table_title\">";
                     $this->salida.="  <td align=\"left\" colspan=\"5\">ULTIMA DOSIS SUMINISTRADA: ".$_NomProducto[$k]."</td>";
                     $this->salida.="</tr>";
                     $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                     $this->salida.="  <td width=\"15%\">FECHA SUM.</td>";
                     $this->salida.="  <td width=\"15%\">HORA SUM.</td>";
                     $this->salida.="  <td width=\"15%\">SUMINISTRADO</td>";
                     //$this->salida.="  <td width=\"15%\">ENTREGAS PAC.</td>";
                     //$this->salida.="  <td width=\"15%\">DESECHOS</td>";
                     $this->salida.="  <td width=\"50%\">USUARIO</td>";
                     $this->salida.="</tr>";
           
                     $estilo='modulo_list_claro';
                     $this->salida.="<tr class=\"$estilo\">";
                     
                     
                     if($tipo_solicitud == "M")
                     { 
                          $factor = $this->SeleccionFactorConversion($vect[0][codigo_producto], $vect[0][cod_presentacion], $vect[0][unidad_dosificacion]);
                          $UnidadS = $vect[0][unidad_dosificacion]; 
                     }
                     else
                     { 
                          $exp = explode(" ",$vect[$k][dosis]);
                          $UnidadS = $exp[1]." ".$exp[2];
                          $factor = $this->SeleccionFactorConversion($vect[$k][codigo_producto], $vect[$k][cod_presentacion], $UnidadS);
                     }
                     
                     //REEMPLAZADO POR Jonier Murillo Hurtado
/*                     
                      if($factor[sw_unidad_minima] == null or $factor[sw_unidad_minima] == 0){
                          $factorC = $factor[factor_conversion];
                          
                      }else{
                          $factorC = $factor[sw_unidad_minima];
                      }
*/                     
                     $factorC = $factor[factor_conversion];
                     
                     if($factorC)
                     {
                          // Conversiones de Suministros.
                          // Suministros
                          $CS = (($control[0][cantidad_suministrada] * $factorC) / 100);
                          $CS = $CS * 100;
                          
                          // Desechos
                          $CP = (($control[0][cantidad_perdidas] * $factorC) / 100);
                          $CP = $CP * 100;
                     }else{
                          $CS = $control[0][cantidad_suministrada];
                          $CP = $control[0][cantidad_perdidas];
                     }
                     
                     $this->salida.="  <td align=\"center\" width=\"15%\">".$this->FechaStamp($control[0][fecha_realizado])."</td>";
                     $this->salida.="  <td align=\"center\" width=\"15%\">".$this->HoraStamp($control[0][fecha_realizado])."</td>";
                     $this->salida.="  <td align=\"center\" width=\"20%\">".$CS."&nbsp;".$UnidadS."</td>";
                     //$this->salida.="  <td align=\"center\" width=\"15%\">".$control[0][cantidad_aprovechada]."</td>";
                     //$this->salida.="  <td align=\"center\" width=\"20%\">".$CP."&nbsp;".$UnidadS."</td>";
                     $this->salida.="  <td align=\"left\" width=\"50%\">".$control[0][nombre]."</td>";
                     $this->salida.="</tr>";
                     
                     $total_suministro = $CS;
                     
                     //$total_EP =  $control[0][cantidad_aprovechada];
                     
                     $total_desecho = $CP;
                     
                     $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                     $this->salida.="  <td colspan=\"2\">TOTAL SUMINISTRADO</td>";
                     
                     $this->salida.="  <td>".$total_suministro."&nbsp;".$UnidadS."</td>";
                     //$this->salida.="  <td>".$total_EP."&nbsp;".$control[0][unidad_dosificacion]."</td>";
                     //$this->salida.="  <td>".$total_desecho."&nbsp;".$UnidadS."</td>";
                     
                     $href1 = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Control_SuministroInsumos',array("datos_producto"=>$vect,'vect'=>$VectorOriginal,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"tipo_solicitud"=>$tipo_solicitud,"bandera"=>0));;
                     $this->salida.="  <td width=\"55%\"><a href=\"$href1\">Ver Todos los Suministros</a></td>";
                     $this->salida.="</tr>";
                     $this->salida.="</table><br>";
                }
           }
      }
      elseif($bandera == 0)//echo "<pre>";
      {
           foreach($_Suministros as $k => $control)
           {
                if($control)
                {
                     $this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
                     $this->salida.="<tr class=\"modulo_table_title\">";
                     $this->salida.="  <td align=\"left\" colspan=\"6\">TOTALIDAD DE INSUMOS SUMINISTRADAS: ".$_NomProducto[$k]."</td>";
                     $this->salida.="</tr>";
                     $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                     $this->salida.="  <td width=\"10%\">FECHA SUM.</td>";
                     $this->salida.="  <td width=\"10%\">HORA SUM.</td>";
                     $this->salida.="  <td width=\"20%\">OBSERVACI???N</td>";
                     $this->salida.="  <td width=\"15%\">SUMINISTRADO</td>";
                     $this->salida.="  <td width=\"50%\">USUARIO</td>";
					 $this->salida.="  <td width=\"5%\">ANULAR</td>";
                     $this->salida.="</tr>";
                     $total_suministro=0;
                     for($i=0;$i<sizeof($control);$i++)
                     {
                          if( $i % 2){ $estilo='modulo_list_claro';}
                          else {$estilo='modulo_list_oscuro';}
                          
                          if($tipo_solicitud == "M")
                          { 
                               $factor = $this->SeleccionFactorConversion($vect[0][codigo_producto], $vect[0][cod_presentacion], $vect[0][unidad_dosificacion]);
                               $UnidadS = $vect[0][unidad_dosificacion]; 
                          }
                          else
                          { 
                               $exp = explode(" ",$vect[$k][dosis]);
                               $UnidadS = $exp[1]." ".$exp[2];
                               $factor = $this->SeleccionFactorConversion($vect[$k][codigo_producto], $vect[$k][cod_presentacion], $UnidadS);
                          }

                          //REEMPLAZADO POR Jonier Murillo Hurtado
/*                          
                          if($factor[sw_unidad_minima] == null or $factor[sw_unidad_minima] == 0){
                              $factorC = $factor[factor_conversion];
                              
                          }else{
                              $factorC = $factor[sw_unidad_minima];
                          }
*/
                          $factorC = $factor[factor_conversion];

                          if($factorC)
                          {
                               // Conversiones de Suministros.
                               // Suministros
                               $CS = (($control[$i][cantidad_suministrada] * $factorC) / 100);
                               $CS = $CS * 100;
                               
                               // Desechos
                               $CP = (($control[$i][cantidad_perdidas] * $factorC) / 100);
                               $CP = $CP * 100;
                          }else{
                               $CS = $control[$i][cantidad_suministrada];
                               $CP = $control[$i][cantidad_perdidas];
                          }                              
                          
                          $this->salida.="<tr class=\"$estilo\">";
                          $this->salida.="  <td align=\"center\" width=\"10%\">".$this->FechaStamp($control[$i][fecha_realizado])."</td>";
                          $this->salida.="  <td align=\"center\" width=\"10%\">".$this->HoraStamp($control[$i][fecha_realizado])."</td>";
                          $this->salida.="  <td align=\"center\" width=\"10%\">".$control[$i][observacion]."</td>";
                          $this->salida.="  <td align=\"center\" width=\"20%\">".$CS."&nbsp;".$UnidadS."</td>";
                          $this->salida.="  <td align=\"left\" width=\"50%\">".$control[$i][nombre]."</td>";
						  $javaAccionAnular = "javascript:MostrarCapa('ContenedorCapaAnular');Iniciar('ANULAR SUMINISTRO','".$control[$i][suministro_id]."','ContenedorCapaAnular');CargarContenedor('ContenedorCapaAnular');";
						  //$javaAccionAnular = "javascript:MostrarCapa('ContenedorCapaAnularInsumo');Iniciar('ANULAR SUMINISTRO','".$control[$i][suministro_id]."','ContenedorCapaAnularInsumo');CargarContenedor('ContenedorCapaAnularInsumo');";
                          $this->salida.="  <td width=\"5%\" align=\"center\"><a href=\"$javaAccionAnular\"><img src=\"". GetThemePath() ."/images/delete.gif\" title='$title' border='0'></a></td>";
                          $this->salida.="</tr>";

                          $total_suministro = $total_suministro + $CS;
                          $total_desecho = $total_desecho + $CP;
                     }
 
                     $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                     $this->salida.="  <td colspan=\"3\">TOTAL SUMINISTRADO</td>";
                     $this->salida.="  <td>".$total_suministro."&nbsp;".$UnidadS."</td>";
					 //nuevo
					 $href1 = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Control_SuministroInsumos',array("datos_producto"=>$vect,'vect'=>$VectorOriginal,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"tipo_solicitud"=>$tipo_solicitud,"bandera"=>1));
                     $this->salida.="  <td colspan=\"2\"><a href=\"$href1\">Ver Ultimo Suministro</a></td>";	
					 //hasta aqui                 
                     $this->salida.="</tr>";
                     $this->salida.="</table><br>";
                }
           }
      }
 
      //BOTON DEVOLVER
      $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
      $this->salida .= "<form name=\"forma\" action=\"$href\" method=\"post\">";
      $this->salida .= "<tr><td  colspan = 6 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td></tr><br>";
      $this->salida .= themeCerrarTabla();
      return true;
    }
     //funcion que sirve para confirmar los suministros del paciente.
     function ConfirmarSuministros()
     {
          $datosPaciente = $_REQUEST['datosPaciente'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $tipo_solicitud = $_REQUEST['tipo_solicitud'];
          
          $vect = $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR'];
          
          //aca va el id de la bodega solamente
          $bodega = $_REQUEST['bodega'];
          // Datos medicos
          $tipo_solicitud = $_REQUEST['tipo_solicitud'];
          $fecha_realizado[0] = $_REQUEST['selectHora'].":".$_REQUEST['selectMinutos'];
          $ProductosSUM = $_REQUEST['datos_SUM'];
          
          $CantidadesSUM = $_REQUEST['cantidad_suministrada'];
          $aprovechamiento = $_REQUEST['aprovechamiento'];
          $perdidas = $_REQUEST['perdidas'];
          $factorC = $_REQUEST['FactorC'];
          
          $ingreso_F = $_REQUEST['ingreso_F'];
          $num_F = $_REQUEST['num_F'];
          $cantidad_recetada = $_REQUEST['cantidad_recetada'];
          $checo = $_REQUEST['checo'];
          
          for($i=0; $i<sizeof($ProductosSUM); $i++)
          {
			//Controles referentes a la administracion del medicamento.
               if(($_REQUEST['cantidad_suministrada'][$i] == '') AND ($_REQUEST['perdidas'][$i] == '')){
                    $this->frmError["cantidad_suministrada"]=1;
                    $this->frmError["MensajeError"]="POR FAVOR DIGITE LA CANTIDAD A SUMINISTRAR O LOS DESECHOS!!.";
                    $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
                    return true;
               }
               
               if(($_REQUEST['cantidad_suministrada'][$i] != '') OR ($_REQUEST['perdidas'][$i] != '')){
                    
               	if(empty($_REQUEST['cantidad_suministrada'][$i]))
                    {
                    	$_REQUEST['cantidad_suministrada'][$i] = 0;
                    }

                    if(empty($_REQUEST['perdidas'][$i]))
                    {
                    	$_REQUEST['perdidas'][$i] = 0;
                    }
               	
                    if ((is_numeric($_REQUEST['cantidad_suministrada'][$i]) == 0) OR (is_numeric($_REQUEST['perdidas'][$i]) == 0)){
                         $this->frmError["cantidad_suministrada"]=1;
                         $this->frmError["MensajeError"]="CANTIDAD INVALIDA, DIGITE SOLO NUMEROS.";
                         $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
                         return true;
                    }
               }

               if($_REQUEST['cantidad_suministrada'][$i] > $_REQUEST['dosis'][$i])
               {
                    $this->frmError["cantidad_suministrada"]=1;
                    $this->frmError["MensajeError"]="LA CANTIDAD SUMINISTRADA NO PUEDE SER MAYOR A LA DOSIS FORMULADA.";
                    $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
                    return true;
			}

			//Controles referentes a las bodegas.
               if($bodega == '*/*')
               {
               	if($factorC[$i] != "")
                    {
                         if($factorC[$i] == 0)
                         {
                              $this->frmError["MensajeError"]="POR FAVOR, DEBE ESTABLECER EL FACTOR DE CONVERSION!!.";
                              $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
                              return true;
                              
                    	}else{

                              $ConversionBodega = ($_REQUEST['BodegaPaciente'][$i] * $factorC[$i]);
                         }
		   }else{
                    	
                    	$ConversionBodega = $_REQUEST['BodegaPaciente'][$i];
                    }
                         
                    if($_REQUEST['cantidad_suministrada'][$i] > $ConversionBodega){
                         $this->frmError["cantidad_suministrada"]=1;
                         $this->frmError["MensajeError"]="LA BODEGA DEL PACIENTE.<BR> NO CUENTA CON LAS EXISTENCIAS SUFICIENTES.";
                         $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
                         return true;
                    }
               	
                    // Suministros + Desechos
                    $SumDes = ($_REQUEST['cantidad_suministrada'][$i] + $_REQUEST['perdidas'][$i]);
                    if($SumDes > $ConversionBodega){
                         $this->frmError["cantidad_suministrada"]=1;
                         $this->frmError["MensajeError"]="LA BODEGA DEL PACIENTE.<BR> NO CUENTA CON LAS EXISTENCIAS SUFICIENTES.";
                         $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
                         return true;
                    }
               }else
               {
                    // Verificacion de Bodegas de Consumo Directo.
                    $datos = $this->GetEstacionBodega_Existencias($datos_estacion, 2, $ProductosSUM[$i]);
                    
                    if($factorC[$i] != "")
                    {
                         if($factorC[$i] == 0)
                         {
                              $this->frmError["MensajeError"]="POR FAVOR, DEBE ESTABLECER EL FACTOR DE CONVERSION!!.";
                              $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
                              return true;
                              
                    	}else{

                              $ConversionBodega = ($datos[0][existencia] * $factorC[$i]);
                         }
				}else{
                    	
                    	$ConversionBodega = $datos[0][existencia];
                    }
                    
                    if($_REQUEST['cantidad_suministrada'][$i] > $ConversionBodega){
                         $this->frmError["cantidad_suministrada"]=1;
                         $this->frmError["MensajeError"]="LA BODEGA DE CONSUMO DIRECTO.<BR> NO CUENTA CON LAS EXISTENCIAS SUFICIENTES PARA EL PRODUCTO ".$ProductosSUM[$i].".";
                         $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
                         return true;
                    }
               	
                    // Suministros + Desechos
                    $SumDes = ($_REQUEST['cantidad_suministrada'][$i] + $_REQUEST['perdidas'][$i]);
                    if($SumDes > $ConversionBodega){
                         $this->frmError["cantidad_suministrada"]=1;
                         $this->frmError["MensajeError"]="LA BODEGA DE CONSUMO DIRECTO.<BR> NO CUENTA CON LAS EXISTENCIAS SUFICIENTES PARA EL PRODUCTO ".$ProductosSUM[$i].".";
                         $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
                         return true;
                    }
               }
          }
          
          $this->InsertarSuministroPaciente($datosPaciente, $datos_estacion, $vect, $bodega, $tipo_solicitud, $fecha_realizado, $ProductosSUM, $CantidadesSUM, $ingreso_F, $checo, $num_F, $perdidas, $cantidad_recetada, $factorC,$ing=true);
          return true;
    }
    /**
    * Funcion que sirve para confirmar los suministros insumos del paciente.
    *
    * @return boolean
    */
    function ConfirmarSuministrosInsumos()
    {
      $datosPaciente = $_REQUEST['datosPaciente'];
      $datos_estacion = $_REQUEST['datos_estacion'];
      $tipo_solicitud = $_REQUEST['tipo_solicitud'];
      
      $vect = $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR'];
      
      //aca va el id de la bodega solamente
      $bodega = $_REQUEST['bodega'];
      // Datos medicos
      $tipo_solicitud = $_REQUEST['tipo_solicitud'];
      $fecha_realizado = $_REQUEST['selectHora'].":".$_REQUEST['selectMinutos'];
      $ProductosSUM = $_REQUEST['datos_SUM'];
      
      $CantidadesSUM = $_REQUEST['cantidad_suministrada'];
      $aprovechamiento = $_REQUEST['aprovechamiento'];
      $perdidas = $_REQUEST['perdidas'];
      $factorC = $_REQUEST['FactorC'];
      
      $ingreso_F = $_REQUEST['ingreso_F'];
      $num_F = $_REQUEST['num_F'];
      $cantidad_recetada = $_REQUEST['cantidad_recetada'];
      $checo = $_REQUEST['checo'];
      
      for($i=0; $i<sizeof($ProductosSUM); $i++)
      {
        //Controles referentes a la administracion del medicamento.
        if(($_REQUEST['cantidad_suministrada'][$i] == '') AND ($_REQUEST['perdidas'][$i] == ''))
        {
          $this->frmError["cantidad_suministrada"]=1;
          $this->frmError["MensajeError"]="POR FAVOR DIGITE LA CANTIDAD A SUMINISTRAR O LOS DESECHOS!!.";
          $this->Control_SuministroInsumos($datos_estacion,$datosPaciente,$tipo_solicitud);
          return true;
        }
        if(($_REQUEST['cantidad_suministrada'][$i] != '') OR ($_REQUEST['perdidas'][$i] != ''))
        {
          if(empty($_REQUEST['cantidad_suministrada'][$i]))
          {
            $_REQUEST['cantidad_suministrada'][$i] = 0;
          }

          if(empty($_REQUEST['perdidas'][$i]))
          {
            $_REQUEST['perdidas'][$i] = 0;
          }
          
          if ((is_numeric($_REQUEST['cantidad_suministrada'][$i]) == 0) OR (is_numeric($_REQUEST['perdidas'][$i]) == 0))
          {
            $this->frmError["cantidad_suministrada"]=1;
            $this->frmError["MensajeError"]="CANTIDAD INVALIDA, DIGITE SOLO NUMEROS.";
            $this->Control_SuministroInsumos($datos_estacion,$datosPaciente,$tipo_solicitud);
            return true;
          }
        }
        // Verificacion de Bodegas de Consumo Directo.
        $datos = $this->GetEstacionBodega_Existencias($datos_estacion, 2, $ProductosSUM[$i]);
      }
      $this->InsertarSuministroInsumosPaciente($datosPaciente, $datos_estacion, $vect, $bodega, $tipo_solicitud, $fecha_realizado, $ProductosSUM, $CantidadesSUM, $ingreso_F, $checo, $num_F, $perdidas, $cantidad_recetada, $factorC,$ing=true);
      return true;
    }
     /*
     * Funcion que permite Cargar insumos a la cuenta del paciente.
     * La solicitud se realiza a una bodega de la Estacion.
     */
     function AgregarInsumos_A_Paciente($datos_estacion,$datosPaciente)
     {
          if(!$datos_estacion)
          {
               $datos_estacion = $_REQUEST["datos_estacion"];
               $datosPaciente = $_REQUEST["datosPaciente"];
          }	
          
          $this->salida .= "<SCRIPT>";
          $this->salida .= "function chequeoTotal(frm,x){";
          $this->salida .= "  if(x==true){";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "        frm.elements[i].checked=true";
          $this->salida .= "      }";
          $this->salida .= "    }";
          $this->salida .= "  }else{";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "        frm.elements[i].checked=false";
          $this->salida .= "      }";
          $this->salida .= "    }";
          $this->salida .= "  }";
          $this->salida .= "}";
          $cadena .= "	function CargarPagina(href,valor) {\n";
          $cadena .= "		var url=href;\n";
          $cadena .= "		location.href=url+'&bodega='+valor;\n";
          $cadena .= "	}\n\n";
          $this->salida .=$cadena;
          $this->salida .= "</SCRIPT>";
          $datos1=$this->GetEstacionBodega($datos_estacion,1);
          $this->salida .= ThemeAbrirTabla("AGREGAR INSUMOS");
          
          $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
          $this->salida .= "		<tr class=\"modulo_table_title\">\n";
          $this->salida .= "			<td>PACIENTE</td>\n";
          $this->salida .= "			<td>HABITACION</td>\n";
          $this->salida .= "			<td>CAMA</td>\n";
          $this->salida .= "			<td>PISO</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
          $this->salida .= "			<td>".$datosPaciente[nombre_completo]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[pieza]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[cama]."</td>\n";
          $this->salida .= "			<td>".$datos_estacion[estacion_descripcion]."</td>\n";
          $this->salida.="</tr></table><br>";
          
          $accion = ModuloGetURL('app','EE_AdministracionMedicamentos','user','AgregarInsumos_A_Paciente',array("conteo"=>$_REQUEST['conteo'],"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso'],"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));

          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";
          
          $this->salida .= "<form name=\"mmm\" action=\"$accion\" method=\"post\">";
          $this->salida.="<br><table  align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list_title\">";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="  <td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO DE INSUMOS</td>";
          $this->salida.="</tr>";
     
          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="<td width=\"5%\">BODEGA</td>";
          $this->salida.="<td width=\"10%\">";
                    
          $this->salida.="<select name=bodega class='select'>";
                              
          for($i=0;$i<sizeof($datos1);$i++)
          {
               if($datos1[$i][bodega]==$_REQUEST['bodega'])
               {
                    $this->salida.="<option value=".$datos1[$i][bodega]." selected>".$datos1[$i][descripcion]."</option>";
               }
               else
               {
                    $this->salida.="<option value=".$datos1[$i][bodega].">".$datos1[$i][descripcion]."</option>";
               }	
          }
          if($_REQUEST['bodega'] == '*/*'){$selected="selected";}else{$selected="";}
          $this->salida.="<option value=*/* $selected>SOLICITUD PACIENTE</option>";
          $this->salida.="</select>";
          $this->salida.="</td>";
     
               
          $this->salida.="<td width=\"10%\" align = left >";
          $this->salida.="<select size = 1 name = 'criterio'  class =\"select\">";
          if($_REQUEST['criterio']=='1')
          {$sel1="selected";$sel2="";}else{$sel2="selected";$sel1="";}
          $this->salida.="<option value = '1' $sel1>Codigo</option>";
          $this->salida.="<option value = '2' $sel2>Insumo</option>";
          $this->salida.="</select>";
          $this->salida.="</td>";
          $this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'busqueda'  size=\"40\" maxlength=\"40\"  value =\"$buscar\"></td>" ;
     
          $this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSCAR\"></td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          if($_REQUEST['busqueda'])
          {
               $cadena="El Buscador Avanzado: realiz??? la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
          }
          else
          {
               $cadena="Buscador Avanzado: Busqueda de todos los insumos";
          }
          $this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
     
          if($_REQUEST['buscar'] OR $_REQUEST['ADD'])
          {
               $filtro=$this->GetFiltro($_REQUEST['criterio'],$_REQUEST['busqueda']);
          }
          
          //estos if de aqui en adelante,es importante ya que si hemos presionado el boton aicionar temp
          if(empty($_REQUEST['paso']))
          {$pas=1;}else{$pas=$_REQUEST['paso'];}
		
		//si presionamos quitar.
		//cabe decir que segun el paso quitamos todos los items q esten en variable de 
		//session.
          if($_REQUEST['DEL'])
          {
               if($_SESSION['EXISTENCIA'][$pas])
               {
                    foreach($_SESSION['EXISTENCIA'][$pas] as $k => $v)
                    {
                    	unset($_SESSION['codigos_I'][$k]);
                         unset($_SESSION['cantidad_a_perdi_sol_I'][$k]);
                    }
                    unset($_SESSION['EXISTENCIA'][$pas]);
               }
               $variable="SE QUITO TODOS LOS INSUMOS ADICIONADOS DE LA PAGINA &nbsp; $pas";
          }
          else
          {
               $variable='';
          }
               
          //si presionamos adicionar........
          if($_REQUEST['ADD'])
          {	
               foreach($_REQUEST['op'] as $index=>$valor)
               {
                    if(is_numeric($_REQUEST['cant'.$valor]) && $_REQUEST['cant'.$valor] > 0)
                    {$_SESSION['EXISTENCIA'][$pas][$valor]=$valor."*".$_REQUEST['cant'.$valor];}
                    $_SESSION['codigos_I'][$valor] = $valor;
                    $_SESSION['cantidad_a_perdi_sol_I'][$valor] = $_REQUEST['cant'.$valor];
               }				
               
               if($_REQUEST['bodega']=='*/*')
               {
                    $_SESSION['MEDICA_DATOS_SOL_PAC']['SOL_PAC_NOM']=$_REQUEST['nom'];
                    $_SESSION['MEDICA_DATOS_SOL_PAC']['SOL_PAC_AREA']=$_REQUEST['area'];
               }
               else
               {
                    unset($_SESSION['MEDICA_DATOS_SOL_PAC']);
               }	
               
          }
             
          if($_SESSION['codigos_I'])
          {
          	unset($salida);
          	foreach ($_SESSION['codigos_I'] as $k => $info)
               {
               	$codiguitos[] = $k;
               }

               for($jj=0; $jj<sizeof($codiguitos); $jj++)
               {
                    $arr_temp[] = $this->GetInsumos($_REQUEST['bodega'],'',$codiguitos[$jj],1,$datos_estacion);
                    $salida="<br><table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"hc_table_submodulo_list_title\">\n";
                    $salida.="<tr class=\"hc_table_submodulo_list_title\"><td colspan=\"4\">MEDICAMENTOS ADICIONADOS</td></tr>";
                    $salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $salida.="<td width=\"10%\">CODIGO</td>";
                    $salida.="<td width=\"75%\" colspan='2'>PRODUCTO - UNIDAD DE MEDIDA</td>";
                    $salida.="<td width=\"20%\">CANT</td>";
                    $salida.="</tr>";               
                    foreach($arr_temp as  $V => $vector)
                    {
                    	foreach($vector as $V2 => $vector)
                         { 
                              if( $i % 2){ $estilo='modulo_list_claro';}
                              else {$estilo='modulo_list_oscuro';}
                              $salida.="<tr class=\"$estilo\">";
                              $salida.="<td align=\"center\" width=\"10%\">".$vector[codigo_producto]."</td>";
                              $salida.="<td align=\"left\" width=\"75%\" colspan='2'>".$vector[descripcion]."</td>";
                              $salida.="<td align=\"center\" width=\"20%\">".$_SESSION['cantidad_a_perdi_sol_I'][$vector[codigo_producto]]."</td>";
                              $salida.="</tr>";               
                         }
                    }
                    $salida.="</table>";
               }
          }
          
          $this->salida.= $salida;
          
          $nom=$_SESSION['MEDICA_DATOS_SOL_PAC']['SOL_PAC_NOM'];
          $area=$_SESSION['MEDICA_DATOS_SOL_PAC']['SOL_PAC_AREA'];
     
          $arr_vect=$this->GetInsumos($_REQUEST['bodega'],$filtro,0,0,$datos_estacion);
		  if(is_array($arr_vect))
          {
               $this->salida .= "<br><div align='center'><label class='label_mark'>$variable</label></div>";
               $this->salida .= "<br><table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td width=\"10%\">ID</td>";
               $this->salida.="  <td width=\"70%\" colspan='2'>PRODUCTO - ABREVIACION</td>";
			   $this->salida.="  <td width=\"10%\">EXISTENCIA</td>";
               $this->salida.="  <td width=\"20%\">CANT</td>";
               $this->salida .= '<form name="vv" method="post" action="'.$o.'">';
               $this->salida.="  <td width=\"5%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
               $this->salida.="</tr>";
               for($i=0;$i<sizeof($arr_vect);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class='$estilo' align='left'>";
                    $this->salida.="  <td>".$arr_vect[$i][codigo_producto]."</td>";
                    $this->salida.="  <td width=\"32%\">".$arr_vect[$i][descripcion]."</td>";
                    $this->salida.="  <td width=\"32%\">".$arr_vect[$i][descripcion_abreviada]."</td>";
					$this->salida.="  <td width=\"32%\">".$arr_vect[$i][existencia]."</td>";
                    $info=explode("*",$_SESSION['EXISTENCIA'][$pas][$arr_vect[$i][codigo_producto]]);
                    $this->salida.="  <td width=\"20%\" align=\"center\"><label class='label_mark'>Cant &nbsp;</label><input type='text' class='input-text' name=cant".$arr_vect[$i][codigo_producto]." value='".$info[1]."' size='8' maxlength='8'></td>";
                    if($info[0]== $arr_vect[$i][codigo_producto])
                    {$check="checked";}else{$check="";}
                    $this->salida.="  <td width=\"5%\" align=\"center\"><input type=checkbox name=op[$i] value=".$arr_vect[$i][codigo_producto]." $check></td>";unset($check);
                    $this->salida.="</tr>";
               }
     
               if($_REQUEST['bodega']=='*/*')
               {
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td colspan='5'>";
                    
                    $this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_title\">\n";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td ><label class='label_mark'>NOMBRE SOLICITANTE</label></td><td><input type='text' name='nom' size='55' maxlength='60' value='$nom'></td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td ><label class='label_mark'>observaciones :</label></td><td><TEXTAREA name='area' rows='5' cols='80'>$area</TEXTAREA></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
                    
                    $this->salida.="</td>";
                    $this->salida.="</tr>";
               }	
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td colspan='5'><input type=submit name=DEL value='QUITAR ITEMS SELECCIONADOS DE ESTA PAGINA' class=input-submit></td>";
               $this->salida.="  <td><input type=submit name=ADD value=ADICIONAR class=input-submit></form></td>";
          
               $this->salida.="</tr>";
               $this->salida.="</table>";
          
               $this->salida.=$this->RetornarBarra($filtro);
          }
          else
          {
               $this->salida .= "<br><br><div align='center'><label class='label_mark'>SELECCIONE LA BODEGA</label></div>";
          }
          
          if($_REQUEST['bodega']=='*/*')//esto quiere decir q es SOLICITUD PARA PACIENTE
          {
               $XYS = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Insertar_Solicitud_Insumos_Para_Paciente',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"criterio"=>$_REQUEST['criterio'],"busqueda"=>$_REQUEST['busqueda'],"bodega"=>$_REQUEST['bodega']));
          }
          else
          {
               $XYS = ModuloGetURL('app','EE_AdministracionMedicamentos','user','InsertarInsumosPaciente',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"criterio"=>$_REQUEST['criterio'],"busqueda"=>$_REQUEST['busqueda'],"bodega"=>$_REQUEST['bodega']));	
          }	

          $this->salida .= "            <form name=\"formainsert\" action=\"$XYS\" method=\"post\">";
          $this->salida .= '<br><br><table align="center" width="40%" border="0">';
          $this->salida .= '<tr>';
          $this->salida .= '<td align="center">';
          $this->salida .= '<input type="submit" name="GUARDAR" value="GUARDAR" class="input-submit">';
          $this->salida .= '</form>';
          $this->salida .= '</td>';
     
          $o = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
          $this->salida .= '<form name="volver" method="post" action="'.$o.'">';
     
          $this->salida .= '<td align="center">';
          $this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
          $this->salida .= '</form>';
          $this->salida .= '</td>';
          $this->salida .= '</tr>';
          $this->salida .= '</table>';
          $this->salida .= ThemeCerrarTablaSubModulo();
          return true;		
     }
     
     //funcion que confirma si se va a cancelar la solicitud
     //esta pantalla muestra para confirmar la cancelaci???n de los insumos 
     function ConfirmarCancelSolicitudIns()
     {
          $bodega = $_REQUEST['bodega'];
          $SWITCHE = $_REQUEST['switche'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $op = $_REQUEST['opcion'];
          $spy = $_REQUEST['spia']; //variable q determina a donde me dirigo cuando se cancele una solicitud.
          $ingreso = $_REQUEST['ingreso'];
          $medic = $_SESSION['ESTACION']['VECTOR_SOL_INS'][$ingreso];
          if(sizeof($medic) AND sizeof($op))
          {
               unset($matriz);
               for($h=0;$h<sizeof($op);$h++)
               {
                    $dat_op=explode(",",$op[$h]);
                    $matriz[$h]=$dat_op[0];
               }
               $this->salida .= ThemeAbrirTabla('CANCELAR SOLICITUD DE INSUMOS');
               $f = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CancelSolicitudInsumos',array("spia"=>$spy,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$bodega,"matriz"=>$matriz,"switche"=>$SWITCHE));
               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
     
               $this->salida .= "	<table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";
     
               $this->salida .= "		<tr class=\"modulo_table_title\">\n";
               $this->salida .= "			<td colspan='7'  align=\"center\">MEDICAMENTOS SOLICITADOS</td>\n";
               $this->salida .= "		</tr>\n";
     
               $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
               $this->salida .= "			<td width=\"17%\" >BODEGA</td>\n";
               $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
               $this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
               $this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
               $this->salida .= "			<td width=\"5%\" >CANTIDAD</td>\n";
               $this->salida .= "			<td width=\"5%\" ></td>\n";
               $this->salida .= "		</tr>\n";
     
               for($i=0;$i<sizeof($medic);$i++)
               {
                    if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                    if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
                    {
                         if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                         {
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                              $solicitud=$medic[$i][solicitud_id];
                              $this->salida .= "<td colspan = 5 width=\"65%\">";
                              $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         }
     
                         $nom_bodega=$this->TraerNombreBodega($datos_estacion,$medic[$i][bodega]);
                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"20%\"><label class='label_mark'>$nom_bodega</label></td>\n";
                         $this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][principio_activo]."</td>\n";
                         $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$i][cantidad])."</td>\n";
                         $this->salida.=" </tr>";
                         if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                         {
                              $this->salida .= "</table>";
                              $this->salida .= "</td>";
                              $this->salida.="  <td colspan = 1 $estilo width=\"5%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>";
                              $this->salida .= "</tr>";
                         }
                    }
               }
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"modulo_list_claro\">";
               $this->salida .= "<td  colspan='2' align='center' width=\"35%\">JUSTIFICACION :</td>";
               $this->salida .= "<td colspan='5'  align=\"left\"><TEXTAREA name=obs cols=60 rows=6>".$_REQUEST['obs']."</TEXTAREA></td>";

               $this->salida.="</tr></table><br>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
               $this->salida.=" <tr>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\"></form>";
               $this->salida.=" </td>";

               if($spy==1)
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"CANCELAR\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla("CONTROL INSUMOS PACIENTE","50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
	          $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               if($spy==1)
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
     
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"VOLVER\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     }


     /**
  	*	FrmDevolucionMedicamentos
  	*
  	*	Muestra un listado de los pacientes que tienen medicamentos por devolver a bodega:
  	*	Medicamentos que pueden ser devueltos => Alex me di??? esta formula:
  	*	a la suma de medicamentos solicitados le resto la suma de los medicamentos devueltos
  	*	ya sea que est???n en espera de aceptacion de devoluciion o que ya hayan sido procesados
  	*	(osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
  	*	es mayor a 0)
  	*
  	*	@Author Rosa Maria Angel
  	*	@access Private
  	*	@param array datos de la estacion
  	*	@return boolean
  	*/
  	function FrmDevolucionMedicamentos($datos_estacion,$bodega,$datosPaciente)
  	{
  		if(!$this->GetUserPermisos('54'))
      {
        $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"datosPaciente"=>$datosPaciente));
        $titulo='VALIDACION DE PERMISOS';
        $mensaje='El usuario no tiene permiso para : Realizar Devoluciones de Insumos y Medicamentos (Pacientes) [54]';
        $this->frmMSG($url, $titulo, $mensaje);
        return true;
      }
		
  		if(!$datos_estacion)
      {
  			$datos_estacion = $_REQUEST['datos_estacion'];
  			$bodega = $_REQUEST['bodega'];
  			$datosPaciente = $_REQUEST['datosPaciente'];
  		}

      $bottom = 0;
  		$this->salida .= ThemeAbrirTabla("LISTADO DE MEDICAMENTOS PARA DEVOLUCION");
  		$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
  		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
  		$this->salida .= "			<td>PACIENTE</td>\n";
  		$this->salida .= "			<td>HABITACION</td>\n";
  		$this->salida .= "			<td>CAMA</td>\n";
  		$this->salida .= "			<td>PISO</td>\n";
  		$this->salida .= "		</tr>\n";
  		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
  		$this->salida .= "			<td>".$datosPaciente[nombre_completo]."</td>\n";
  		$this->salida .= "			<td>".$datosPaciente[pieza]."</td>\n";
  		$this->salida .= "			<td>".$datosPaciente[cama]."</td>\n";
  		$this->salida .= "			<td>".$datos_estacion[estacion_descripcion]."</td>\n";
  		$this->salida.="</tr></table><br>";
  		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
  		$this->salida .= $this->SetStyle("MensajeError");
  		$this->salida.="</table>";
  		$this->salida.="<script>";
  		$this->salida.="	function acceptNum(evt)\n";
  		$this->salida.="	{\n";
  		$this->salida.="		var nav4 = window.Event ? true : false;\n";
  		$this->salida.="		var key = nav4 ? evt.which : evt.keyCode;\n";
      $this->salida.="		return (key <= 13 ||(key >= 48 && key <= 57));\n";
  		$this->salida.="	}\n";
      $this->salida .= "	function ValidarCantidades(medicamento_id,total,cantidad_dev,j,cant)\n";
  		$this->salida .= "	{\n";
  		$this->salida .= "	  cnt_sel = 0;\n";
      $this->salida .= "	  valor = document.getElementById('med_'+medicamento_id+'_'+j).value*1;\n";
   		$this->salida .= "		if(valor > cant*1)\n";
   		$this->salida .= "		{\n";
  		$this->salida .= "			document.getElementById('med_'+medicamento_id+'_'+j).value='';\n";
  		$this->salida .= "			document.getElementById('med_'+medicamento_id+'_'+j).style.background='#ff9595';\n";
  		$this->salida .= "			alert('CANTIDAD NO VALIDA, PARA HACER LA DEVOLUCION');\n";
  		$this->salida .= "		  return;\n";
  		$this->salida .= "		}\n";
  		$this->salida .= "	  for(i =0; i<total; i++)\n";
  		$this->salida .= "	  {\n";
  		$this->salida .= "	    if(document.getElementById('med_'+medicamento_id+'_'+i).value != '')\n";
  		$this->salida .= "	      cnt_sel += document.getElementById('med_'+medicamento_id+'_'+i).value*1\n";
  		$this->salida .= "	  }\n";
  		$this->salida .= "		document.getElementById('med_'+medicamento_id+'_'+j).style.background='';\n";
  		$this->salida .= "		if(cnt_sel > cantidad_dev*1)\n";
  		$this->salida .= "		{\n";
  		$this->salida .= "			document.getElementById('med_'+medicamento_id+'_'+j).value='';\n";
  		$this->salida .= "			document.getElementById('med_'+medicamento_id+'_'+j).style.background='#ff9595';\n";
  		$this->salida .= "			alert('CANTIDAD NO VALIDA, PARA HACER LA DEVOLUCION');\n";
  		$this->salida .= "		}\n";
  		$this->salida .= "	}\n";
  		$this->salida.="</script>\n";
    
      //variable de session q contiene las bodegas de las estaciones
      if(empty($_SESSION['ESTACION']['VECTOR_DEV']['BODEGA_ESTACION']))
      {
        $_SESSION['ESTACION']['VECTOR_DEV']['BODEGA_ESTACION'] = $bodega;
      }
      else
      {
         $bodega = $_SESSION['ESTACION']['VECTOR_DEV']['BODEGA_ESTACION'];
      }
           
      for($s=0; $s<sizeof($bodega);$s++)
      {
        $nom_bodega=$this->TraerNombreBodega($datos_estacion,$bodega[$s][bodega]);
        $sumatoria=0;
        if($l % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
           
        //Consulta los medicamentos que han sido despachado para consumo del paciente.
        //$retorno = $this->GetDevolucionMedicamentos('M', $datosPaciente[ingreso]);
        $medic = $this->ObtenerMedicamentosDevolucion('M', $datosPaciente[ingreso]);

        if(!empty($medic))
        {
          $contador = 4;
          //creamos una variable de session con el ingreso y la bodega... para guardar el arreglo de confirmacion.
          $_SESSION['ESTACION']['VECTOR_DEV'][$datosPaciente[ingreso]][$bodega[$s][bodega]] = $medic;
                
          $this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
          $f = ModuloGetURL('app','EE_AdministracionMedicamentos','user','ConfirmarDevMed',array("ingreso"=>$datosPaciente[ingreso],"plan"=>$datosPaciente[plan_id],"cuenta"=>$datosPaciente[numerodecuenta],"datos_estacion"=>$datos_estacion,"bodega"=>$bodega[$s][bodega],"datosPaciente"=>$datosPaciente));
          $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
          $this->salida .= "	<tr class='modulo_table_title'>\n";
          $this->salida .= "		<td colspan='4'>BODEGA ".strtoupper($nom_bodega)."</td>\n";
          $this->salida .= "	</tr>\n";
          $this->salida .= "	<tr>\n";
          $this->salida .= "    <td width=\"95%\" colspan='4'>\n";
          $this->salida .= "      <table align=\"center\" width=\"100%\" class=\"modulo_table_list\">\n";
          $this->salida .= "		    <tr class=\"formulacion_table_list\">\n";
          $this->salida .= "			    <td align=\"center\" rowspan=\"2\" width=\"15%\" >BODEGA</td>\n";
          $this->salida .= "			    <td align=\"center\" colspan=\"5\" width=\"58%\" >PRODUCTO</td>\n";
          $this->salida .= "			    <td align=\"center\" rowspan=\"2\" width=\"4%\" >CANT</td>\n";
          $this->salida .= "			    <td align=\"center\" rowspan=\"2\" width=\"16%\" colspan='4'>Acci???n Devoluci???n</td>\n";
          $this->salida .= "		    </tr>\n";
          $this->salida .= "		    <tr class=\"formulacion_table_list\">\n";
          $this->salida .= "			    <td align=\"center\" width=\"7%\">CODIGO</td>\n";
          $this->salida .= "			    <td align=\"center\" width=\"20%\">SOLICITADO</td>\n";
          $this->salida .= "			    <td align=\"center\" width=\"7%\">CODIGO</td>\n";
          $this->salida .= "			    <td align=\"center\" width=\"20%\">DESPACHADO</td>\n";
          $this->salida .= "			    <td align=\"center\" width=\"4%\">DES</td>\n";
          $this->salida .= "		    </tr>\n";
          $cont=0;
          foreach($medic as $key => $dtl)
          {
            $flag = true;
            $j = 0;
            foreach($dtl as $k1 => $d1)
            {
              $est = ($est == "modulo_list_oscuro")? "modulo_list_claro":"modulo_list_oscuro";
              $this->salida .= "		    <tr class=\"".$est."\">\n";
              if($flag)
              {
                $StockPaciente = $d1['stock'];
                $row = "rowspan=\"".sizeof($dtl)."\"";
                $this->salida .= "		      <td ".$row.">".$nom_bodega."</td>\n";
                $this->salida .= "          <td ".$row." class=\"normal_10AN\">".$d1['medicamento_id']."</td>\n";
                $this->salida .= "          <td ".$row." class=\"normal_10AN\">".$d1['solicitado']."</td>\n";
                $flag = false;
              }
              $dev =($d1['pendiente']< $StockPaciente)? $d1['pendiente']: $StockPaciente;
              
              $this->salida .= "          <td >".$d1['codigo_producto']."</td>\n";
              $this->salida .= "          <td >".$d1['descripcion']."</td>\n";
              $this->salida .= "          <td align=\"right\">".($d1['cantidad']*1)."</td>\n";
              $this->salida .= "          <td align=\"right\">".($dev*1)."</td>\n";
              $this->salida .= "          <td align=\"center\" width=\"3%\">\n";
              $this->salida .= "            <input class=\"input-text\" size='5' maxlength='5' type=text name=opt[] value='' id=\"med_".$d1['medicamento_id']."_".$j."\" onkeypress=\"return acceptNum(event)\" onkeyup=\"ValidarCantidades('".$d1['medicamento_id']."','".sizeof($dtl)."','".$d1['stock']."','".$j."','".$dev."')\">\n";
              $this->salida .= "            <input type=\"hidden\" name =\"despachos[".$key."][".$d1['codigo_producto']."]\" value=\"".$d1['documento_despacho_id']."\">\n";
              $this->salida .= "            <input type=\"hidden\" name =\"solicitado[".$key."][".$d1['codigo_producto']."]\" value=\"".$d1['medicamento_id']."\">\n";
              $this->salida .= "          </td>\n";
              $this->salida .= "          <td align=\"center\" width=\"20%\" colspan='3'>\n";
              $this->salida .= "            <label class='label_mark'>&nbsp;Devolver (<b>-</b> de) o &nbsp;".$dev."</label>\n";
              $this->salida .= "          </td>\n";
              $this->salida .= "        </tr>\n";
              $StockPaciente -= $d1['pendiente'];
              $bottom = 1;
              $j++;
            }
          }
          if(sizeof($medic) == $cont)
          {
            $this->salida .= "      <td $estilo width=\"20%\" colspan='11' align='center'><label class='label_mark'>YA SE REALIZO LA DEVOLUCIONES DE ESTE PACIENTE</label></td>\n";
            $sw=1;
          }
          if($sw !=1)
          {
            if($bottom == 0)
            {
              $this->salida .= "        <tr class=\"hc_table_submodulo_list_title\">\n";
              $this->salida .= "          <td colspan='11' align=\"center\">\n";
              $this->salida .= "            <label class=\"label_error\">PUEDE QUE HAYA DEVOLUCIONES PENDIENTES POR CONFIRMAR!!!</label>";
              $this->salida .= "          </td>";
              $this->salida .= "        </tr>";
            }
            else
            {
              $this->salida .= "        <tr align='right' class=\"modulo_table_button\">\n";
              $this->salida .= "          <td colspan='11'>";
              $this->salida .= "            <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\" value=\"CONFIRMAR\">";
              $this->salida .= "          </td>";
              $this->salida .= "        </tr>";
            }
          }
                unset($sw);
                $this->salida.="</table><br>";
                $this->salida .= "</td></tr>\n";
                $this->salida.="</table></form>";
        }
               if($contador !=4)
               {$contador=1;}
		
               if($bodega[$s][bodega] != $bodega[$s-1][bodega])
               {
                    $devo_impresiones = $this->BusquedaDevoluciones_Pendientes($datos_estacion,$bodega[$s][bodega],$datosPaciente,"M");
               	if(is_array($devo_impresiones))
                    {
                         $vector_devo = array();
                         array_push($vector_devo, $devo_impresiones);
                    }
               }
          }
          if($contador==1)
          {
               $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
               $this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY MEDICAMENTOS PARA DEVOLUCION EN ESTA BODEGA</label>";
               $this->salida .= "</td></tr>";
               $this->salida.="</table><br>";
          }
          else
          {
               $href2 = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionMedicamentos',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"datosPaciente"=>$datosPaciente));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>REFRESCAR</a><br>";
          }
          
          if($vector_devo)
          {
               $reporte = new GetReports();
               $mostrar = $reporte->GetJavaReport('app','EE_AdministracionMedicamentos','reporte_solicitudes_devolucion_html',array('datos_estacion'=>$datos_estacion, 'bodega'=>$bodega, 'datosPaciente'=>$datosPaciente,'letra'=>"M"),array('rpt_name'=>'Solicitud_Devolucion'.$datosPaciente['ingreso'],'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
               $nombre_funcion = $reporte->GetJavaFunction();
               $this->salida.=$mostrar;

               $this->salida .= "<div class='normal_10' align='center'><br><a href=\"javascript:$nombre_funcion\"><B>IMPRESION DE SOLICITUDES DE DEVOLUCION</B></a><br>";
          }
          
          $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"datosPaciente"=>$datosPaciente));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>VOLVER</a><br>";
          $this->salida .= themeCerrarTabla();
          unset($ItemBusqueda);
          unset($_SESSION['CANTIDAD_PRODUCTOS']);
          return true;
	}//fin FrmDevolucionMedicamentos() 
    /**
  	*	FrmDevolucionMedicamentosExterno
  	*
  	*	Muestra un listado de los pacientes que tienen medicamentos por devolver a bodega:
  	*	Medicamentos que pueden ser devueltos => Alex me di??? esta formula:
  	*	a la suma de medicamentos solicitados le resto la suma de los medicamentos devueltos
  	*	ya sea que est???n en espera de aceptacion de devoluciion o que ya hayan sido procesados
  	*	(osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
  	*	es mayor a 0)
  	*
  	*	@Author Rosa Maria Angel
  	*	@access Private
  	*	@param array datos de la estacion
  	*	@return boolean
  	*/
  	function FrmDevolucionMedicamentosExterno($datos_estacion,$bodega,$datosPaciente)
  	{
  		if(!$datos_estacion)
      {
  			$datos_estacion = $_REQUEST['datos_estacion'];
  			$bodega = $_REQUEST['bodega'];
  			$datosPaciente = $_REQUEST['datosPaciente'];
  		}
          
  		$this->salida .= ThemeAbrirTabla("LISTADO DE MEDICAMENTOS PARA DEVOLUCION");
  		$this->salida.="<script>";
  		$this->salida.="	function acceptNum(evt)\n";
  		$this->salida.="	{\n";
  		$this->salida.="		var nav4 = window.Event ? true : false;\n";
  		$this->salida.="		var key = nav4 ? evt.which : evt.keyCode;\n";
      $this->salida.="		return (key <= 13 ||(key >= 48 && key <= 57));\n";
  		$this->salida.="	}\n";
      $this->salida .= "	function ValidarCantidades(medicamento_id,total,cantidad_dev,j,ingreso,cant)\n";
  		$this->salida .= "	{\n";
  		$this->salida .= "	  cnt_sel = 0;\n";
  		$this->salida .= "	  valor = document.getElementById('med_'+medicamento_id+'_'+j+'_'+ingreso).value*1;\n";
   		$this->salida .= "		if(valor > cant*1)\n";
   		$this->salida .= "		{\n";
  		$this->salida .= "			document.getElementById('med_'+medicamento_id+'_'+j+'_'+ingreso).value='';\n";
  		$this->salida .= "			document.getElementById('med_'+medicamento_id+'_'+j+'_'+ingreso).style.background='#ff9595';\n";
  		$this->salida .= "			alert('CANTIDAD NO VALIDA, PARA HACER LA DEVOLUCION DEL INGRESO '+ingreso);\n";
  		$this->salida .= "		  return;\n";
  		$this->salida .= "		}\n";
  		$this->salida .= "	  for(i =0; i<total; i++)\n";
  		$this->salida .= "	  {\n";
  		$this->salida .= "	    if(document.getElementById('med_'+medicamento_id+'_'+i+'_'+ingreso).value != '')\n";
  		$this->salida .= "	      cnt_sel += document.getElementById('med_'+medicamento_id+'_'+i+'_'+ingreso).value*1\n";
  		$this->salida .= "	  }\n";
  		$this->salida .= "		document.getElementById('med_'+medicamento_id+'_'+j+'_'+ingreso).style.background='';\n";
  		$this->salida .= "		if(cnt_sel > cantidad_dev*1)\n";
  		$this->salida .= "		{\n";
  		$this->salida .= "			document.getElementById('med_'+medicamento_id+'_'+j+'_'+ingreso).value='';\n";
  		$this->salida .= "			document.getElementById('med_'+medicamento_id+'_'+j+'_'+ingreso).style.background='#ff9595';\n";
  		$this->salida .= "			alert('CANTIDAD NO VALIDA, PARA HACER LA DEVOLUCION DEL INGRESO '+ingreso);\n";
  		$this->salida .= "		}\n";
  		$this->salida .= "	}\n";
  		$this->salida.="</script>\n";
      //variable de session q contiene las bodegas de las estaciones
      if(empty($_SESSION['ESTACION']['VECTOR_DEV']['BODEGA_ESTACION']))
        $_SESSION['ESTACION']['VECTOR_DEV']['BODEGA_ESTACION']=$bodega;
      else
        $bodega = $_SESSION['ESTACION']['VECTOR_DEV']['BODEGA_ESTACION'];
      
      for($s=0; $s<sizeof($bodega);$s++)
      {
        //Aterrizo las variables de SESSION para formar un solo vector de pacientes.
        $a = 0;
         
        foreach($_SESSION['EE_PanelEnfermeria']['listadoPacientes'] as $k => $Pacientes)
        {
          $datosPacienteEstacion[$a] = $Pacientes;
          $a++;
          $i = $a;						
        }

        unset($Pacientes);
        foreach($_SESSION['EE_PanelEnfermeria']['listadoPacientes_Urgencias'] as $k => $Pacientes)
        {
          $datosPacienteEstacion[$i] = $Pacientes;
          $i++;
        }

        $nom_bodega=$this->TraerNombreBodega($datos_estacion,$bodega[$s][bodega]);
        $sumatoria=0;
         
        if($l % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
         
        foreach($datosPacienteEstacion as $k => $datosPaciente)
        {
          //Consulta los medicamentos que han sido despachado para consumo del paciente.
          //$retorno=$this->GetDevolucionMedicamentos('M', $datosPaciente[ingreso]);
          //$medic = $retorno['medicamebtos'];
          //$despachos = $retorno['despachos'];
          $medic = $this->ObtenerMedicamentosDevolucion('M', $datosPaciente[ingreso]);
          $bottom = 0;
          
          if(!empty($medic))
          {
            $contador = 4;
                         
            //creamos una variable de session con el ingreso y la bodega... para guardar el arreglo de confirmacion.
            $_SESSION['ESTACION']['VECTOR_DEV'][$datosPaciente[ingreso]][$bodega[$s][bodega]] = $medic;
                         
            $f = ModuloGetURL('app','EE_AdministracionMedicamentos','user','ConfirmarDevMed',array("ingreso"=>$datosPaciente[ingreso],"plan"=>$datosPaciente[plan_id],"cuenta"=>$datosPaciente[numerodecuenta],"datos_estacion"=>$datos_estacion,"bodega"=>$bodega[$s][bodega],"datosPaciente"=>$datosPaciente,'externo'=>true));
            $this->salida .= "<form name='conf' action='".$f."' method='POST'>\n";                         
            $this->salida .= "  <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
            $this->salida .= "	  <tr class='modulo_table_title'>\n";
            $this->salida .= "		  <td colspan='4'>BODEGA ".strtoupper($nom_bodega)."</td>\n";
            $this->salida .= "	  </tr>\n";
            $this->salida .= "	  <tr class=hc_table_submodulo_list_title>\n";
            $this->salida .= "      <td colspan='4'>\n";
            $this->salida .= "      <table align=\"center\" width=\"100%\" class=\"modulo_table_list\">\n";
            $this->salida .= "		    <tr  class=\"formulacion_table_list\">\n";
            $this->salida .= "			    <td width=\"23%\" colspan=\"2\" >NOMBRE PACIENTE</td>\n";
            $this->salida .= "			    <td align=\"left\" class=\"modulo_list_claro\" width=\"47%\" colspan=\"3\">".$datosPaciente['nombre_completo']."</td>\n";
            $this->salida .= "			    <td width=\"8%\" colspan=\"2\">INGRESO</td>\n";
            $this->salida .= "			    <td align=\"left\" class=\"modulo_list_claro\" width=\"16%\" colspan=\"4\">".$datosPaciente['ingreso']."</td>\n";
            $this->salida .= "		    </tr>\n";
            $this->salida .= "		    <tr class=\"formulacion_table_list\">\n";
            $this->salida .= "			    <td align=\"center\" rowspan=\"2\" width=\"15%\" >BODEGA</td>\n";
            $this->salida .= "			    <td align=\"center\" colspan=\"5\" width=\"58%\" >PRODUCTO</td>\n";
            $this->salida .= "			    <td align=\"center\" rowspan=\"2\" width=\"4%\" >CANT</td>\n";
            $this->salida .= "			    <td align=\"center\" rowspan=\"2\" width=\"16%\" colspan='4'>Acci???n Devoluci???n</td>\n";
            $this->salida .= "		    </tr>\n";
            $this->salida .= "		    <tr class=\"formulacion_table_list\">\n";
            $this->salida .= "			    <td align=\"center\" width=\"7%\">CODIGO</td>\n";
            $this->salida .= "			    <td align=\"center\" width=\"20%\">SOLICITADO</td>\n";
            $this->salida .= "			    <td align=\"center\" width=\"7%\">CODIGO</td>\n";
            $this->salida .= "			    <td align=\"center\" width=\"20%\">DESPACHADO</td>\n";
            $this->salida .= "			    <td align=\"center\" width=\"4%\">DES</td>\n";
            $this->salida .= "		    </tr>\n";
            $cont = 0;
            
            foreach($medic as $key => $dtl)
            {
              $flag = true;
              $j = 0;
              foreach($dtl as $k1 => $d1)
              {
                $est = ($est == "modulo_list_oscuro")? "modulo_list_claro":"modulo_list_oscuro";
                $this->salida .= "		    <tr class=\"".$est."\">\n";
                if($flag)
                {
                  $StockPaciente = $d1['stock'];
                  $row = "rowspan=\"".sizeof($dtl)."\"";
                  $this->salida .= "		      <td ".$row.">".$nom_bodega."</td>\n";
                  $this->salida .= "          <td ".$row." class=\"normal_10AN\">".$d1['medicamento_id']."</td>\n";
                  $this->salida .= "          <td ".$row." class=\"normal_10AN\">".$d1['solicitado']."</td>\n";
                  $flag = false;
                }
                $dev =($d1['pendiente']< $StockPaciente)? $d1['pendiente']: $StockPaciente;
                
                $this->salida .= "          <td >".$d1['codigo_producto']."</td>\n";
                $this->salida .= "          <td >".$d1['descripcion']."</td>\n";
                $this->salida .= "          <td align=\"right\">".($d1['cantidad']*1)."</td>\n";
                $this->salida .= "          <td align=\"right\">".($dev*1)."</td>\n";
                $this->salida .= "          <td align=\"center\" width=\"3%\">\n";
                $this->salida .= "            <input class=\"input-text\" size='5' maxlength='5' type=text name=opt[] value='' id=\"med_".$d1['medicamento_id']."_".$j."_".$datosPaciente[ingreso]."\" onkeypress=\"return acceptNum(event)\" onkeyup=\"ValidarCantidades('".$d1['medicamento_id']."','".sizeof($dtl)."','".$d1['stock']."','".$j."','".$datosPaciente[ingreso]."','".$dev."')\">\n";
                $this->salida .= "            <input type=\"hidden\" name =\"despachos[".$key."][".$d1['codigo_producto']."]\" value=\"".$d1['documento_despacho_id']."\">\n";
                $this->salida .= "            <input type=\"hidden\" name =\"solicitado[".$key."][".$d1['codigo_producto']."]\" value=\"".$d1['medicamento_id']."\">\n";
                $this->salida .= "          </td>\n";
                $this->salida .= "          <td align=\"center\" width=\"20%\" colspan='3'>\n";
                $this->salida .= "            <label class='label_mark'>&nbsp;Devolver (<b>-</b> de) o &nbsp;".$dev."</label>\n";
                $this->salida .= "          </td>\n";
                $this->salida .= "        </tr>\n";
                $StockPaciente -= $d1['pendiente'];
                $bottom = 1;
                $j++;
              }
            }
            if(sizeof($medic) ==$cont )
            {
              $this->salida .= "<td $estilo colspan='11' align='center'><label class='label_mark'>YA SE REALIZO LA DEVOLUCIONES DE ESTE PACIENTE</label></td>\n";
              $sw=1;
            }
            if($sw !=1)
            {
              if($bottom == 0)
              {
                $this->salida.=" <tr class=\"hc_table_submodulo_list_title\"><td colspan='11' align=\"center\">";
                $this->salida.=" <label class=\"label_error\">PUEDE QUE HAYA DEVOLUCIONES PENDIENTES POR CONFIRMAR!!!</label>";
                $this->salida.=" </td>";
                $this->salida .= "</tr>";
              }
              else
              {
                $this->salida.=" <tr align='right' class=\"modulo_table_button\"><td colspan='11'>";
                $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\" value=\"CONFIRMAR\">";
                $this->salida.=" </td>";
                $this->salida.= "</tr>";
              }
            }
            unset($sw);
            $this->salida .= "        </table>\n";
            $this->salida .= "      </td>\n";
            $this->salida .= "    </tr>\n";
            $this->salida .= "  </table>";
            $this->salida .= "</form>\n";
          }
        }
        if($contador !=4)
          $contador=1;
      }
          if($contador==1)
          {
               $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
               $this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY MEDICAMENTOS PARA DEVOLUCION EN ESTA BODEGA</label>";
               $this->salida .= "</td></tr>";
               $this->salida.="</table><br>";
          }
          else
          {
               $href2 = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionMedicamentosExterno',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"datosPaciente"=>$datosPaciente));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>REFRESCAR</a><br>";
          }
          
          unset($ItemBusqueda);
          unset($_SESSION['CANTIDAD_PRODUCTOS']);
          //DATOS DEL PIE DE PAGINA
          $this->FrmPieDePagina();
          return true;
	}//fin FrmDevolucionMedicamentosExterno()

     
     //funcion que confirma si se va a cancelar la solicitud
    function ConfirmarDevMed()
    {
      $bodega = $_REQUEST['bodega'];
      $arreglo_bodega_estacion = $_SESSION['ESTACION']['VECTOR_DEV']['BODEGA_ESTACION'];
      $datos_estacion = $_SESSION['datos_estacion_ConfDev'] = $_REQUEST['datos_estacion'];
      $datosPaciente = $_SESSION['datosPaciente_ConfDev'] = $_REQUEST['datosPaciente'];
      //$op = $_REQUEST['opt'];
      SessionDelVar("op");
      SessionSetVar("op",$_REQUEST['opt']);          
      $op = SessionGetVar("op");          
      $plan = $_REQUEST['plan'];
      $despachos = $_REQUEST['despachos'];
      $solicitado = $_REQUEST['solicitado'];
      $cuenta = $_REQUEST['cuenta'];
      $medic = $_SESSION['ESTACION']['VECTOR_DEV'][$_REQUEST['ingreso']][$bodega];

      unset($contador);
      for($h=0;$h<sizeof($op);$h++)
      {
        if(empty($op[$h]) or $op[$h]==0)
        {
          $contador=$contador + 1;
        }
      }

      if($contador ==sizeof($op))
      {$sw_spy=1;}

      if(!empty($medic) and $sw_spy !=1)
      {
        $this->salida .= ThemeAbrirTabla('CONFIRMACION DEVOLUCION DE MEDICAMENTOS');
        $this->salida .= "<table  align=\"center\" border=\"0\"  width=\"100%\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table>";
        $this->salida .= "	<br>\n";
        $this->salida .= "<table align=\"center\" width=\"100%\" class=\"modulo_table_list\">\n";
        $this->salida .= " <tr  class=\"formulacion_table_list\">\n";
        $this->salida .= "	  <td rowspan=\"2\" width=\"20%\" >BODEGA</td>\n";
        $this->salida .= "		<td colspan=\"4\" width=\"54%\" >PRODUCTO</td>\n";
        $this->salida .= "		<td rowspan=\"2\" width=\"6%\" >CANT</td>\n";
        $this->salida .= "		<td rowspan=\"2\" width=\"20%\" colspan='5'>Cantidad a delvolver</td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= " <tr  class=\"formulacion_table_list\">\n";
        $this->salida .= "		<td width=\"7%\" >CODIGO</td>\n";
        $this->salida .= "		<td width=\"20%\" >SOLICITADO</td>\n";
        $this->salida .= "		<td width=\"7%\" >CODIGO</td>\n";
        $this->salida .= "		<td width=\"20%\" >DESPACHADO</td>\n";
        $this->salida .= "	</tr>\n";

        unset($ERROR_HADLING);
        //$href1 = ModuloGetURL('app','EE_AdministracionMedicamentos','user','InsertDevolucionMedicamento',array("ingreso"=>$_REQUEST['ingreso'],"bodega"=>$bodega,'opt'=>$op,'plan'=>$plan,'cuenta'=>$cuenta,'sw_spy'=>$sw_spy,'externo'=>$_REQUEST['externo']));
        $href1 = ModuloGetURL('app','EE_AdministracionMedicamentos','user','InsertDevolucionMedicamento',array("ingreso"=>$_REQUEST['ingreso'],"bodega"=>$bodega,'plan'=>$plan,'cuenta'=>$cuenta,'sw_spy'=>$sw_spy,'externo'=>$_REQUEST['externo']));

        $this->salida .="<form name=forma action=".$href1." method=post>";

        $a = 0;
        foreach($medic as $key => $dtl)
        {
          $flag = true;
          $j = 0;
          foreach($dtl as $k1 => $d1)
          {
            $nom_bodega = $this->TraerNombreBodega($datos_estacion,$d1['bodega']);
            $est = ($est == "modulo_list_oscuro")? "modulo_list_claro":"modulo_list_oscuro";
            $this->salida .= "  <tr class=\"".$est."\">\n";
            if($flag)
            {
              $StockPaciente = $d1['stock'];
              $row = "rowspan=\"".sizeof($dtl)."\"";
              $this->salida .= "		      <td ".$row.">".$nom_bodega."</td>\n";
              $this->salida .= "          <td ".$row." class=\"normal_10AN\">".$d1['medicamento_id']."</td>\n";
              $this->salida .= "          <td ".$row." class=\"normal_10AN\">".$d1['solicitado']."</td>\n";
              $flag = false;
            }
                  
            $dev =($d1['pendiente']< $StockPaciente)? $d1['pendiente']: $StockPaciente;

            $this->salida .= "    <td >".$d1['codigo_producto']."</td>\n";
            $this->salida .= "    <td >".$d1['descripcion']."</td>\n";
            $this->salida .= "    <td align=\"center\" width=\"3%\">".($dev*1)."</td>\n";
            $this->salida .= "    <td align=\"center\" width=\"5%\">\n";
            $this->salida .= "      <input class=\"input-text\" size='5' maxlength='5'  type=text name=opt[] value='".$op[$a]."' READONLY>\n";
            $this->salida .= "    </td>\n";
            $this->salida .= "    <td align=\"center\" width=\"5%\" colspan='4'>\n";
            if($dev < $op[$a])
            {
              $this->salida .= "      <label class='label_error'>&nbsp;Excede la cantidad</label>\n";
              $this->salida .= "      <input type=hidden name=medica[] value='".$d1['codigo_producto']."'>\n";
              $ERROR_HADLING=1;
            }
            else
            {
              $this->salida .= "      <label class='label_mark'>&nbsp;Menor o igual a &nbsp;".$dev."</label>\n";
              $this->salida .= "      <input type=hidden name=medica[] value='".$d1[codigo_producto]."'>\n";
            }
            if($op[$a])
            {
              $this->salida .= "      <input type=\"hidden\" name=\"despachos[".$key."][".$d1['codigo_producto']."]\" value=\"".$despachos[$key][$d1['codigo_producto']]."\">\n";
              $this->salida .= "      <input type=\"hidden\" name =\"solicitado[".$a."]\" value=\"".$d1['medicamento_id']."\">\n";
            }
            $this->salida .= "    </td>\n";
            $StockPaciente -= $d1['pendiente'];
            $a++;
          }
        }
  //NUEVO, TEXTO DE JUSTIFICACION
        $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
        $this->salida .= "    <td>MOTIVO DEVOLUCION</td>\n";              
        $this->salida .= "    <td colspan=\"10\" align=\"left\" width=\"40%\">\n";
        $this->salida .= "      <select name=\"parametro\" class=\"select\">";
        $this->salida .= "        <option align=\"center\" value=\"-1\" selected>-- SELECCIONE --</option>";
        $vector_tipo=$this->Get_ParametrosDevolucion();
        $this->GetHtmlParametrosDevolucion($vector_tipo,$_REQUEST['parametro']);
        $this->salida .= "      </select>\n";
        $this->salida .= "    </td>";
        $this->salida .= "  </tr>\n";
        $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
        $this->salida .= "    <td align=\"center\">JUSTIFICACION DEVOLUCION</td>\n";              
        $this->salida .= "    <td colspan=\"10\" align=\"left\" width=\"90%\"><textarea cols=\"20\" rows=\"3\" style=\"width:100%\" class=\"textarea\" name=\"justificacion_devo\"></textarea></td>\n";              
        $this->salida .= "  </tr>\n";
        $this->salida .= "</table><br>";
           //NUEVO, TEXTO DE JUSTIFICACION
        if($ERROR_HADLING !=1)
           {
                $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
                $this->salida.=" <tr>";
                $this->salida.=" <td align=\"center\">";
                $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\"></form>";
                $this->salida.=" </td>";
           }
           else
           {
                $this->salida.="<br><table border=\"0\" align=\"center\" width=\"15%\">";
                $this->salida.=" <tr>";
                $this->salida.=" </form>";
           }
           
           if($_REQUEST['externo'] == true)
           {
            $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionMedicamentosExterno',array("datos_estacion"=>$datos_estacion,"bodega"=>$arreglo_bodega_estacion,"datosPaciente"=>$datosPaciente));
           }
           else
           {
            $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionMedicamentos',array("datos_estacion"=>$datos_estacion,"bodega"=>$arreglo_bodega_estacion,"datosPaciente"=>$datosPaciente));               
           }
           
           $this->salida .="<form name=forma action=".$href." method=post>";
           $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"CANCELAR\" class=\"input-submit\"></form></td>";
           $this->salida.=" </tr>";
           $this->salida.=" </table>";
           $this->salida .= ThemeCerrarTabla();
      }
      else
      {
           $this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion'],"50%");
           $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
           $this->salida .= "		<tr >\n";
           $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO DIGITO EN NINGUNA CASILLA !</label></td>\n";
           $this->salida.="</tr></table>";
           $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
           $this->salida.=" <tr>";
           
           if($_REQUEST['externo'] == true)
           {
             $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionMedicamentosExterno',array("datos_estacion"=>$datos_estacion,"bodega"=>$arreglo_bodega_estacion,"datosPaciente"=>$datosPaciente));               
           }
           else
           {
            $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionMedicamentos',array("datos_estacion"=>$datos_estacion,"bodega"=>$arreglo_bodega_estacion,"datosPaciente"=>$datosPaciente));
           }
           
           $this->salida .="<form name=forma action=".$href." method=post>";
           $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"VOLVER\" class=\"input-submit\"></form></td>";
           $this->salida.=" </tr>";
           $this->salida.=" </table>";
           $this->salida .= ThemeCerrarTabla();
      }
      unset($_SESSION['CANTIDAD_PRODUCTOS']);
      return true;
    }

     
     function GetHtmlParametrosDevolucion($vect,$TipoId)
     {
          foreach($vect as $value=>$titulo)
          {
               if($titulo[parametro_devolucion_id]==$TipoId){
                    $this->salida .=" <option align=\"center\" value=\"$titulo[parametro_devolucion_id]\" selected>$titulo[descripcion]</option>";
               }else{
                    $this->salida .=" <option align=\"center\" value=\"$titulo[parametro_devolucion_id]\">$titulo[descripcion]</option>";
               }
          }
     }
     
     
	/**
	*	FrmDevolucionInsumos
	*
	*	Muestra un listado de los pacientes que tienen medicamentos por devolver a bodega:
	*	Medicamentos que pueden ser devueltos => Alex me di??? esta formula:
	*	a la suma de medicamentos solicitados le resto la suma de los medicamentos devueltos
	*	ya sea que est???n en espera de aceptacion de devoluciion o que ya hayan sido procesados
	*	(osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
	*	es mayor a 0)
	*
	*	@Author Rosa Maria Angel
	*	@access Private
	*	@param array datos de la estacion
	*	@return boolean
	*/
	function FrmDevolucionInsumos($datos_estacion,$bodega,$datosPaciente)
	{
		
		if(!$this->GetUserPermisos('54'))
        {
            $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"datosPaciente"=>$datosPaciente));
			$titulo='VALIDACION DE PERMISOS';
            $mensaje='El usuario no tiene permiso para : Realizar Devoluciones de Insumos y Medicamentos (Pacientes) [54]';
             $this->frmMSG($url, $titulo, $mensaje);
            return true;
        }
		
		if(!$datos_estacion){
			$datos_estacion = $_REQUEST['datos_estacion'];
			$bodega = $_REQUEST['bodega'];
			$datosPaciente = $_REQUEST['datosPaciente'];
		}

		$this->salida .= ThemeAbrirTabla("LISTADO DE INSUMOS PARA DEVOLUCION");
		$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>HABITACION</td>\n";
		$this->salida .= "			<td>CAMA</td>\n";
		$this->salida .= "			<td>PISO</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		$this->salida .= "			<td>".$datosPaciente[nombre_completo]."</td>\n";
		$this->salida .= "			<td>".$datosPaciente[pieza]."</td>\n";
		$this->salida .= "			<td>".$datosPaciente[cama]."</td>\n";
		$this->salida .= "			<td>".$datos_estacion[estacion_descripcion]."</td>\n";
		$this->salida.="</tr></table><br>";
    $this->salida.="<script>";
		$this->salida.="	function acceptNum(evt)\n";
		$this->salida.="	{\n";
		$this->salida.="		var nav4 = window.Event ? true : false;\n";
		$this->salida.="		var key = nav4 ? evt.which : evt.keyCode;\n";
    $this->salida.="		return (key <= 13 ||(key >= 48 && key <= 57));\n";
		$this->salida.="	}\n";
		$this->salida.="</script>\n";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
          
          //variable de session q contiene las bodegas de las estaciones
          if(empty($_SESSION['ESTACION']['VECTOR_DEV_INS']['BODEGA_ESTACION']))
          {
          	$_SESSION['ESTACION']['VECTOR_DEV_INS']['BODEGA_ESTACION']=$bodega;
          }
          else
          {
          	$bodega = $_SESSION['ESTACION']['VECTOR_DEV_INS']['BODEGA_ESTACION'];          
          }
		
          for($s=0; $s<sizeof($bodega);$s++)
          {
              $nom_bodega=$this->TraerNombreBodega($datos_estacion,$bodega[$s][bodega]);
              $sumatoria = 0;
               
              if($l % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
               
               //Consulta los insumos que han sido despachado para consumo del paciente.
              $retorno = $this->GetDevolucionInsumos('I', $datosPaciente[ingreso]);
              $medic = $retorno['medicamebtos'];
              $despachos = $retorno['despachos'];
 
              if(!empty($medic))
              {
                    $contador = 4;
                    
                    //creamos una variable de session con el ingreso y la bodega... para guardar el arreglo de confirmacion.
                    $_SESSION['ESTACION']['VECTOR_DEV_INS'][$datosPaciente[ingreso]][$bodega[$s][bodega]]=$medic;
                    
                    $this->salida .= "<table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
                    $f = ModuloGetURL('app','EE_AdministracionMedicamentos','user','ConfirmarDevIns',array("ingreso"=>$datosPaciente[ingreso],"plan"=>$datosPaciente[plan_id],"cuenta"=>$datosPaciente[numerodecuenta],"datos_estacion"=>$datos_estacion,"bodega"=>$bodega[$s][bodega],"datosPaciente"=>$datosPaciente));
                    $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
                    $this->salida .= "<tr class='modulo_table_title'>\n";
                    $this->salida .= "<td colspan='4'>BODEGA ".strtoupper($nom_bodega)."</td>\n";
                    $this->salida .= "</tr>\n";

                    $this->salida .= "	<tr class=hc_table_submodulo_list_title><td colspan='4'>\n";
                    $this->salida .= "	<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";

                    $this->salida .= "		<tr  class='modulo_table_title'>\n";
                    $this->salida .= "			<td align=\"center\" width=\"20%\" >BODEGA</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"10%\" >CODIGO</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"40%\" >PRODUCTO</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"3%\" >CANT</td>\n";
                    $this->salida .= "			<td align=\"center\" width=\"20%\" colspan='5'>Acci???n Devoluci???n</td>\n";
                    $this->salida .= "		</tr>\n";

                    $cont = 0;                         
                    
                    for($i=0;$i<sizeof($medic);$i++)
                    {
                      if($i % 2)  $estilo = "class=modulo_list_oscuro";  else  $estilo = "class=modulo_list_oscuro";
                       
                      if($medic[$i][codigo_producto] != $medic[$i+1][codigo_producto])
                      {
                        // Informacion de Conteo de medicamentos Solicitados para validaciones.
                        $_BodegaPaciente = $this->GetCantidades_BodegaPaciente($datosPaciente['ingreso'],$medic[$i]['codigo_producto']);
                        
                        //1 Cantidades reales en la Bodega del Paciente.
                        $_StockPaciente = round($_BodegaPaciente[stock_almacen],2) - round($_BodegaPaciente[cantidad_en_devolucion],2);
   
                        if($_StockPaciente > 0)
                        {
                          $this->salida .= "<tr $estilo>\n";
                          $this->salida .= "<td $estilo width=\"20%\">$nom_bodega</td>\n";
                          $this->salida .= "<td $estilo width=\"10%\">".$medic[$i][codigo_producto]."</td>\n";
                          $this->salida .= "<td $estilo width=\"40%\">".$medic[$i][descripcion]."</td>\n";
                          $this->salida .= "<td $estilo align=\"center\" width=\"3%\">".$_StockPaciente."</td>\n";
                          $this->salida .= "<td $estilo align=\"center\" width=\"3%\">\n";
                          $this->salida .= " <input class='input-submit' size='5' maxlength='5' type=text name=opt[] value='' onkeypress=\"return acceptNum(event)\">\n";
                          $this->salida .= " <input type=\"hidden\" name =\"despachos[".$medic[$i]['codigo_producto']."]\" value=\"".$despachos[$medic[$i]['codigo_producto']]."\">\n";
                          $this->salida .= "</td>\n";
                          $this->salida .= "<td $estilo align=\"center\" width=\"20%\" colspan='4'><label class='label_mark'>&nbsp;Devolver (<b>-</b> de) o &nbsp;".$_StockPaciente."</label></td>\n";
                          $this->salida.=" </tr>";
                        }
                            
                      }
                      else
                      {
                        $this->salida .= "<input class='input-submit' size='5' maxlength='5'  type=hidden name=opt[] value=''>\n";
                        $cont=$cont+1;
                      }
                    }
     
                    if(sizeof($medic) ==$cont )
                    {
                         $this->salida .= "<td $estilo width=\"20%\" colspan='9' align='center'><label class='label_mark'>YA SE REALIZO LA DEVOLUCIONES DE ESTE PACIENTE</label></td>\n";
                         $sw=1;
                    }
                    if($sw !=1)
                    {
                         $this->salida.=" <tr align='right' class=\"modulo_table_button\"><td colspan='9'>";
                         $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\">";
                         $this->salida.=" </td>";
                         $this->salida .= "</tr>";
                    }
                    unset($sw);
                    $this->salida.="</table><br>";
                    $this->salida .= "</td></tr>\n";
                    $this->salida.="</table></form>";
               }
               if($contador !=4)
               {$contador=1;}
               
               if($bodega[$s][bodega] != $bodega[$s-1][bodega])
               {
                    $devo_impresiones = $this->BusquedaDevoluciones_Pendientes($datos_estacion,$bodega[$s][bodega],$datosPaciente,"I");
               	if(is_array($devo_impresiones))
                    {
                         $vector_devo = array();
                         array_push($vector_devo, $devo_impresiones);
                    }
               }
          }
          if($contador==1)
          {
               $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
               $this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY INSUMOS PARA DEVOLUCION EN ESTA BODEGA</label>";
               $this->salida .= "</td></tr>";
               $this->salida.="</table><br>";
          }
          else
          {
               $href2 = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionInsumos',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"datosPaciente"=>$datosPaciente));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>REFRESCAR</a><br>";
          }
          
          if($vector_devo)
          {
               $reporte = new GetReports();
               $mostrar = $reporte->GetJavaReport('app','EE_AdministracionMedicamentos','reporte_solicitudes_devolucion_html',array('datos_estacion'=>$datos_estacion, 'bodega'=>$bodega, 'datosPaciente'=>$datosPaciente,'letra'=>"I"),array('rpt_name'=>'Solicitud_Devolucion'.$datosPaciente['ingreso'],'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
               $nombre_funcion = $reporte->GetJavaFunction();
               $this->salida.=$mostrar;

               $this->salida .= "<div class='normal_10' align='center'><br><a href=\"javascript:$nombre_funcion\"><B>IMPRESION DE SOLICITUDES DE DEVOLUCION</B></a><br>";
          }
          
          $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>VOLVER</a><br>";
          $this->salida .= themeCerrarTabla();
          unset($ItemBusqueda);
          return true;
     }//fin FrmDevolucionMedicamentos()


     /**
	*	FrmDevolucionInsumosExterno
	*
	*	Muestra un listado de los pacientes que tienen medicamentos por devolver a bodega:
	*	Medicamentos que pueden ser devueltos => Alex me di??? esta formula:
	*	a la suma de medicamentos solicitados le resto la suma de los medicamentos devueltos
	*	ya sea que est???n en espera de aceptacion de devoluciion o que ya hayan sido procesados
	*	(osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
	*	es mayor a 0)
	*
	*	@Author Rosa Maria Angel
	*	@access Private
	*	@param array datos de la estacion
	*	@return boolean
	*/
	function FrmDevolucionInsumosExterno($datos_estacion,$bodega,$datosPaciente)
	{
		if(!$datos_estacion){
			$datos_estacion = $_REQUEST['datos_estacion'];
			$bodega = $_REQUEST['bodega'];
			$datosPaciente = $_REQUEST['datosPaciente'];
		}
          
		$this->salida .= ThemeAbrirTabla("LISTADO DE INSUMOS PARA DEVOLUCION");
          //variable de session q contiene las bodegas de las estaciones
          if(empty($_SESSION['ESTACION']['VECTOR_DEV_INS']['BODEGA_ESTACION']))
          {
          	$_SESSION['ESTACION']['VECTOR_DEV_INS']['BODEGA_ESTACION'] = $bodega;
          }
          else
          {
          	$bodega = $_SESSION['ESTACION']['VECTOR_DEV_INS']['BODEGA_ESTACION'];
          }

          for($s=0; $s<sizeof($bodega);$s++)
		{
               //Aterrizo las variables de SESSION para formar un solo vector de pacientes.
               $a = 0;
               
               foreach($_SESSION['EE_PanelEnfermeria']['listadoPacientes'] as $k => $Pacientes)
               {
                    $datosPacienteEstacion[$a] = $Pacientes;
                    $a++;
                    $i = $a;						
               }

               unset($Pacientes);
               foreach($_SESSION['EE_PanelEnfermeria']['listadoPacientes_Urgencias'] as $k => $Pacientes)
               {
                    $datosPacienteEstacion[$i] = $Pacientes;
                    $i++;
               }

               //consulto nombre de la bodegas asociada a la EE.
               $nom_bodega=$this->TraerNombreBodega($datos_estacion,$bodega[$s][bodega]);
               $sumatoria=0;
               
               if($l % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
               
                foreach($datosPacienteEstacion as $k => $datosPaciente)
                {
                    
                    //Consulta los insumos que han sido despachado para consumo del paciente.
                    $retorno = $this->GetDevolucionInsumos('I', $datosPaciente[ingreso]);
                    $medic = $retorno['medicamebtos'];
                    $despachos = $retorno['despachos'];
                    if(!empty($medic))
                    {
                         $contador = 4;
                         
                         //creamos una variable de session con el ingreso y la bodega... para guardar el arreglo de confirmacion.
                         $_SESSION['ESTACION']['VECTOR_DEV_INS'][$datosPaciente[ingreso]][$bodega[$s][bodega]] = $medic;
                         
                         $f = ModuloGetURL('app','EE_AdministracionMedicamentos','user','ConfirmarDevIns',array("ingreso"=>$datosPaciente[ingreso],"plan"=>$datosPaciente[plan_id],"cuenta"=>$datosPaciente[numerodecuenta],"datos_estacion"=>$datos_estacion,"bodega"=>$bodega[$s][bodega],"datosPaciente"=>$datosPaciente,"externo"=>true));
    
                         $this->salida .= "<table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
                         $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
                         $this->salida .= "<tr class='modulo_table_title'>\n";
                         $this->salida .= "<td colspan='4'>BODEGA ".strtoupper($nom_bodega)."</td>\n";
                         $this->salida .= "</tr>\n";
     
                         $this->salida .= "	<tr class=hc_table_submodulo_list_title><td colspan='4'>\n";
                         $this->salida .= "	<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
     
                         $this->salida .= "		<tr  class=\"modulo_table_button\">\n";
                         $this->salida .= "			<td align=\"center\" width=\"20%\">NOMBRE PACIENTE</td>\n";
                         $this->salida .= "			<td align=\"left\" width=\"35%\" colspan=\"2\">".$datosPaciente['nombre_completo']."</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"10%\">INGRESO</td>\n";
                         $this->salida .= "			<td align=\"left\" width=\"8%\" colspan=\"4\">".$datosPaciente['ingreso']."</td>\n";
                         $this->salida .= "		</tr>\n";
    
                         
                         $this->salida .= "		<tr  class=\"modulo_table_title\">\n";
                         $this->salida .= "			<td align=\"center\" width=\"20%\" >BODEGA</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"10%\" >CODIGO</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"40%\" >PRODUCTO</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"5%\" >CANTIDAD</td>\n";
                         $this->salida .= "			<td align=\"center\" width=\"20%\" colspan='5'>Acci???n Devoluci???n</td>\n";
                         $this->salida .= "		</tr>\n";
     
                         $cont = 0;                         
                         for($i=0;$i<sizeof($medic);$i++)
                         {
                              if($i % 2)  $estilo = "class=modulo_list_oscuro";  else  $estilo = "class=modulo_list_oscuro";
                              
                              if($medic[$i][codigo_producto] != $medic[$i+1][codigo_producto])
                              {
                         
                                   // Informacion de Conteo de medicamentos Solicitados para validaciones.
                                   $_BodegaPaciente = $this->GetCantidades_BodegaPaciente($datosPaciente['ingreso'],$medic[$i]['codigo_producto']);
                                   
                                   //1 Cantidades reales en la Bodega del Paciente.
                                   $_StockPaciente = round($_BodegaPaciente[stock_almacen],2) - round($_BodegaPaciente[cantidad_en_devolucion],2);
                                   
                                   if($_StockPaciente > 0)
                                   {
                                        $this->salida .= "<tr $estilo>\n";
                                        $this->salida .= "<td width=\"20%\">$nom_bodega</td>\n";
                                        $this->salida .= "<td width=\"10%\">".$medic[$i][codigo_producto]."</td>\n";
                                        $this->salida .= "<td width=\"40%\">".$medic[$i][descripcion]."</td>\n";
                                        $this->salida .= "<td align=\"center\" width=\"3%\">".$_StockPaciente."</td>\n";
                                        $this->salida .= "  <td align=\"center\" width=\"3%\">\n";
                                        $this->salida .= "    <input class='input-submit' size='5' maxlength='5' type=text name=opt[] value='' onkeypress=\"return acceptNum(event)\">\n";
                                        $this->salida .= "    <input type=\"hidden\" name =\"despachos[".$medic[$i]['codigo_producto']."]\" value=\"".$despachos[$medic[$i]['codigo_producto']]."\">\n";
                                        $this->salida .= "  </td>\n";
                                        $this->salida .= "<td align=\"center\" width=\"20%\" colspan='4'><label class='label_mark'>Devolver (<b>-</b> de) o &nbsp;".$_StockPaciente."</label></td>\n";
                                        $this->salida.=" </tr>";
                                   }
                              }
                              else
                              {
                                   $this->salida .= "<input class='input-submit' size='5' maxlength='5'  type=hidden name=opt[] value=''>\n";
                                   $cont=$cont+1;
                              }
                              
                         }
          
                         if(sizeof($medic) ==$cont )
                         {
                              $this->salida .= "<td $estilo width=\"20%\" colspan='9' align='center'><label class='label_mark'>YA SE REALIZO LA DEVOLUCIONES DE ESTE PACIENTE</label></td>\n";
                              $sw=1;
                         }
                         if($sw !=1)
                         {
                              $this->salida.=" <tr align='right' class=\"modulo_table_button\"><td colspan='9'>";
                              $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\">";
                              $this->salida.=" </td>";
                              $this->salida .= "</tr>";
                         }
                         unset($sw);
                         $this->salida.="</table><br>";
                         $this->salida .= "</td></tr>\n";
                         $this->salida.="</form></table>";
                    }
                    unset($medic);
               }
               if($contador !=4)
               {$contador=1;}
          }
          if($contador==1)
          {
               $this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" \n>";
               $this->salida .= "<tr><td align=\"center\" ><label class='label_mark'>NO HAY INSUMOS PARA DEVOLUCION EN ESTA BODEGA</label>";
               $this->salida .= "</td></tr>";
               $this->salida.="</table><br>";
          }
          else
          {
               $href2 = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionInsumosExterno',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"datosPaciente"=>$datosPaciente));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>REFRESCAR</a><br>";
          }
          
          unset($ItemBusqueda);

          //DATOS DEL PIE DE PAGINA
          $this->FrmPieDePagina();
          return true;
     }//fin FrmDevolucionInsumosExterno()

     	
     //funcion que confirma si se va a cancelar la solicitud
     function ConfirmarDevIns()
     {
        $bodega = $_REQUEST['bodega'];
        $arreglo_bodega_estacion = $_SESSION['ESTACION']['VECTOR_DEV_INS']['BODEGA_ESTACION'];
        $datos_estacion = $_SESSION['datos_estacion_ConfDev'] = $_REQUEST['datos_estacion'];
        $datosPaciente = $_SESSION['datosPaciente_ConfDev'] = $_REQUEST['datosPaciente'];
        $op = $_REQUEST['opt'];
        $despachos = $_REQUEST['despachos'];
        $plan = $_REQUEST['plan'];
        $cuenta = $_REQUEST['cuenta'];
        $medic = $_SESSION['ESTACION']['VECTOR_DEV_INS'][$_REQUEST['ingreso']][$bodega];

        unset($contador);
          
        for($h=0;$h<sizeof($op);$h++)
        {
          if(empty($op[$h]) or $op[$h]==0)
          {
            $contador=$contador + 1;
          }
        }

    		if($contador ==sizeof($op))
    		{$sw_spy=1;}

        if(!empty($medic) and $sw_spy !=1)
        {
          $this->salida .= ThemeAbrirTabla('CONFIRMACION DEVOLUCION DE INSUMOS');
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";
          $this->salida .= "	<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
          $this->salida .= "		<tr  class='modulo_table_title'>\n";
          $this->salida .= "			<td align=\"center\" width=\"20%\" >BODEGA</td>\n";
          $this->salida .= "			<td align=\"center\" width=\"10%\" >CODIGO</td>\n";
          $this->salida .= "			<td align=\"center\" width=\"40%\" >PRODUCTO</td>\n";
          $this->salida .= "			<td align=\"center\" width=\"5%\" >CANT</td>\n";
          $this->salida .= "			<td align=\"center\" width=\"20%\" colspan='5'>Acci???n Devoluci???n</td>\n";
          $this->salida .= "		</tr>\n";

          unset($ERROR_HADLING);
          $href1 = ModuloGetURL('app','EE_AdministracionMedicamentos','user','InsertDevolucionMedicamento',array("ingreso"=>$_REQUEST['ingreso'],"bodega"=>$bodega,'accion'=>'1','externo'=>$_REQUEST['externo']));
          $this->salida .="<form name=forma action=".$href1." method=post>";
  
          $a = 0;
          for($i=0;$i<sizeof($medic);$i++)
          {
            if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_claro";
                    
            if($medic[$i][codigo_producto] != $medic[$i+1][codigo_producto])
            {
              // Informacion de Conteo de medicamentos Solicitados para validaciones.
              $_BodegaPaciente = $this->GetCantidades_BodegaPaciente($datosPaciente['ingreso'],$medic[$i]['codigo_producto']);
               
              //1 Cantidades reales en la Bodega del Paciente.
              $_StockPaciente = round($_BodegaPaciente[stock_almacen],2) - round($_BodegaPaciente[cantidad_en_devolucion],2);

              if($_StockPaciente > 0)
              {
                $nom_bodega = $this->TraerNombreBodega($datos_estacion,$medic[$i][bodega]);
                $this->salida .= "<tr $estilo>\n";
                $this->salida .= "<td >$nom_bodega</td>\n";
                $this->salida .= "<td >".$medic[$i][codigo_producto]."</td>\n";
                $this->salida .= "<td >".$medic[$i][descripcion]."</td>\n";
                $this->salida .= "<td align=\"center\" >".$_StockPaciente."</td>\n";
                
                if($_StockPaciente < $op[$a])
                {
                     $this->salida .= "<td align=\"center\" ><input class='input-submit' size='5' maxlength='5'  type=text name=opt[] value='".$op[$a]."' READONLY></td>\n";
                     $this->salida .= "<td align=\"center\" colspan='4'><label class='label_error'>&nbsp;Excede la cantidad</label></td>\n";
                     $this->salida .= "<input type=hidden name=medica[] value='".$medic[$i][codigo_producto]."'>\n";
                     $ERROR_HADLING=1;
                }
                else
                {
                     $this->salida .= "<td align=\"center\" ><input class='input-submit' size='5' maxlength='5'  type=text name=opt[] value='".$op[$a]."' READONLY></td>\n";
                     $this->salida .= "<td align=\"center\" colspan='4'><label class='label_mark'>&nbsp;Menor o igual a &nbsp;".$_StockPaciente."</label></td>\n";
                     $this->salida .= "<input type=hidden name=medica[] value='".$medic[$i][codigo_producto]."'>\n";
                }
                
                if($op[$a])
                  $this->salida .= "<input type=\"hidden\" name=\"despachos[".$medic[$i]['codigo_producto']."]\" value=\"".$despachos[$medic[$i]['codigo_producto']]."\">\n";
                
                $a++;
                $this->salida.=" </tr>";
              }
            }
            else
            {
              $this->salida .= "<input class='input-submit' size='5' maxlength='5'  type=hidden name=opt[] value=''>\n";
              $this->salida .= "<input type=hidden name=medica[] value='".$medic[$i][codigo_producto]."'>\n";
              $a++;
            }
          }
  			//NUEVO, TEXTO DE JUSTIFICACION
          $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "<td>MOTIVO DEVOLUCION</td>\n";              
          $this->salida .= "<td colspan=\"8\" align=\"left\" width=\"40%\"><select name=\"parametro\" class=\"select\">";
          $this->salida .= "<option align=\"center\" value=\"-1\" selected>-- SELECCIONE --</option>";
          $vector_tipo=$this->Get_ParametrosDevolucion();
          $this->GetHtmlParametrosDevolucion($vector_tipo,$_REQUEST['parametro']);
          $this->salida .= "</select></td>";
          $this->salida .= "</tr>\n";

          $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "<td align=\"center\">JUSTIFICACION DEVOLUCION</td>\n";              
          $this->salida .= "<td colspan=\"8\" align=\"left\" width=\"90%\"><textarea cols=\"20\" rows=\"3\" style=\"width:100%\" class=\"textarea\" name=\"justificacion_devo\"></textarea></td>\n";              
          $this->salida .= "</tr>\n";
          //NUEVO, TEXTO DE JUSTIFICACION
          $this->salida.="</table><br>";
    			if($ERROR_HADLING !=1)
    			{
    				$this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
    				$this->salida.=" <tr>";
    				$this->salida.=" <td align=\"center\">";
    				$this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\"></form>";
    				$this->salida.=" </td>";
    			}
    			else
    			{
    				$this->salida.="<br><table border=\"0\" align=\"center\" width=\"15%\">";
    				$this->salida.=" <tr>";
    				$this->salida.=" </form>";
    			}
  			
          if($_REQUEST['externo'] == true)
          {
            $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionInsumosExterno',array("datos_estacion"=>$datos_estacion,"bodega"=>$arreglo_bodega_estacion,"datosPaciente"=>$datosPaciente));               
          }
          else
          {
            $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionInsumos',array("datos_estacion"=>$datos_estacion,"bodega"=>$arreglo_bodega_estacion,"datosPaciente"=>$datosPaciente));               
          }
    			$this->salida .="<form name=forma action=".$href." method=post>";
    			$this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"CANCELAR\" class=\"input-submit\"></form></td>";
    			$this->salida.=" </tr>";
    			$this->salida.=" </table>";
    			$this->salida .= ThemeCerrarTabla();
        }
          else
          {
               $this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion'],"50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO DIGITO EN NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
	          $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
			
               if($_REQUEST['externo'] == true)
               {
	               $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionInsumosExterno',array("datos_estacion"=>$datos_estacion,"bodega"=>$arreglo_bodega_estacion,"datosPaciente"=>$datosPaciente));               
               }
               else
               {
	               $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionInsumos',array("datos_estacion"=>$datos_estacion,"bodega"=>$arreglo_bodega_estacion,"datosPaciente"=>$datosPaciente));               
               }

               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"VOLVER\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
		return true;
	}
     
     
     /**
     * Funcion que muestra todos los movimientos del medicamento
     * (Notas de Suspension y Notas de Finalizacion)
     *
     * Detalle_Suministro()
     */
     function Detalle_Suministro()
     {
          $this->salida= ThemeAbrirTabla('NOTAS DEL MEDICAMENTO');
          
          $ingreso = $_REQUEST['ingreso'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          
          $this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

          if( $i % 2){ $estilo='modulo_list_claro';}
          else {$estilo='modulo_list_oscuro';}
	    
		//DOSIFICACIONES ANTERIORES EN EL DETALLE DEL SUMINISTRO
		$this->salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"3\">MEDICAMENTO REFERIDO</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td width=\"10%\">CODIGO</td>";
		$this->salida.="  <td width=\"35%\">PRODUCTO</td>";
		$this->salida.="  <td width=\"35%\">PRINCIPIO ACTIVO</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="  <td width=\"10%\">".$_REQUEST['codigo_producto']."</td>";
		$this->salida.="  <td width=\"35%\">".$_REQUEST['producto']."</td>";
		$this->salida.="  <td width=\"35%\">".$_REQUEST['principio_activo']."</td>";
		$this->salida.="</tr>";
     	
          $vectorMSH = $this->Consulta_Solicitud_Medicamentos_Historial($_REQUEST['codigo_producto'],$ingreso);
		if ($vectorMSH)
		{
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td colspan = 3 align=\"center\" width=\"63%\">";
			$this->salida.="<table>";

			$this->salida.="<tr>";
			$this->salida.="<td>";
			$this->pintar_historial($vectorMSH);
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
		}
          $this->salida.="</table><br>";
		//FIN DOSIFICACIONES ANTERIORES EN EL DETALLE DEL SUMINISTRO

		$notas = $this->Consultar_Notas_Suministro($_REQUEST['codigo_producto'],$ingreso);
		$this->salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">NOTAS DE ESTADO DE MEDICAMENTOS</td>";
		$this->salida.="</tr>";
		if($notas)
		{
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"5%\">NOTA_ID</td>";
			$this->salida.="  <td width=\"10%\">TIPO DE NOTA</td>";
			$this->salida.="  <td width=\"10%\">FECHA</td>";
			$this->salida.="  <td width=\"15%\">USUARIO</td>";
			$this->salida.="  <td width=\"40%\">NOTA DE ESTADO</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($notas);$i++)
			{
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"5%\">".$notas[$i][hc_nota_suministro_id]."</td>";
				if($notas[$i][tipo_observacion]=='1')
				{
					$this->salida.="  <td align=\"center\" width=\"10%\">NOTA DE ACTIVACION</td>";
				}
				elseif($notas[$i][tipo_observacion]=='2')
				{
					$this->salida.="  <td align=\"center\" width=\"10%\">NOTA DE SUSPENSION</td>";
				}
                    elseif($notas[$i][tipo_observacion]=='3')
				{
					$this->salida.="  <td align=\"center\" width=\"10%\">NOTA DE PROCEDIMIENTO</td>";
				}

				$this->salida.="  <td align=\"left\" width=\"10%\">".$this->FechaStamp($notas[$i][fecha_registro_nota])." - ".$this->HoraStamp($notas[$i][fecha_registro_nota])."</td>";
                    if ($notas[$i][nombre]!=NULL)
				{
                         $this->salida.="  <td align=\"left\" width=\"15%\">".$notas[$i][nombre]."</td>";
				}
				else
				{
			          $this->salida.="  <td align=\"left\" width=\"15%\">".$notas[$i][nombre_usuario]."</td>";
				}

				$this->salida.="  <td align=\"left\" width=\"40%\">".$notas[$i][observacion]."</td>";
				$this->salida.="</tr>";
			}
		}
		else
		{
               $this->salida.="<tr class=\"modulo_list_claro\">";
               $this->salida.="<td align=\"center\" colspan=\"5\" class='label_mark'>ESTE MEDICAMENTO AUN NO PRESENTA NOTAS</td>";
               $this->salida.="</tr>";
		}
		$this->salida.="</table><br>";

          $this->salida .= "</form>";
	
          //BOTON DEVOLVER
		$accionV=ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array('datos_estacion'=>$datos_estacion,'datosPaciente'=>$datosPaciente));
          $this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
          $this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";

		$this->salida .= ThemeCerrarTabla();
		return true;
	}
     
     /*
     * Funcion que pinta las caracteristicas de formulacion del medicamento.
     */
     function pintar_historial($vector1)
     {
          if ($vector1)
          {
			for($i=0;$i<sizeof($vector1);$i++)
			{
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"18%\">EVOLUCION: ".$vector1[$i][evolucion_id]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"28%\">FECHA: ".$this->FechaStamp($vector1[$i][fecha])." - ".$this->HoraStamp($vector1[$i][fecha])."</td>";
                    $this->salida.="  <td align=\"center\" colspan = 4 width=\"34%\">MED. ".$vector1[$i][nombre]."</td>";
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td colspan = 6>";
                    $this->salida.="<table>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
                    $e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
                    if($e==1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
                    }
                    else
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                    }

                    $vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);

			//pintar formula para opcion 1
                    if($vector1[$i][tipo_opcion_posologia_id]== 1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                    }

			//pintar formula para opcion 2
                    if($vector1[$i][tipo_opcion_posologia_id]== 2)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
                    }

			//pintar formula para opcion 3
                    if($vector1[$i][tipo_opcion_posologia_id]== 3)
                    {
                         $momento = '';
                         if($vector_posologia[0][sw_estado_momento]== '1')
                         {
                              $momento = 'antes de ';
                         }
                         else
                         {
                              if($vector_posologia[0][sw_estado_momento]== '2')
                              {
                                   $momento = 'durante ';
                              }
                              else
                              {
                                   if($vector_posologia[0][sw_estado_momento]== '3')
                                        {
                                             $momento = 'despues de ';
                                        }
                              }
                         }
                         $Cen = $Alm = $Des= '';
                         $cont= 0;
                         $conector = '  ';
                         $conector1 = '  ';
                         if($vector_posologia[0][sw_estado_desayuno]== '1')
                         {
                              $Des = $momento.'el Desayuno';
                              $cont++;
                         }
                         if($vector_posologia[0][sw_estado_almuerzo]== '1')
                         {
                              $Alm = $momento.'el Almuerzo';
                              $cont++;
                         }
                         if($vector_posologia[0][sw_estado_cena]== '1')
                         {
                              $Cen = $momento.'la Cena';
                              $cont++;
                         }
                         if ($cont== 2)
                         {
                              $conector = ' y ';
                              $conector1 = '  ';
                         }
                         if ($cont== 1)
                         {
                              $conector = '  ';
                              $conector1 = '  ';
                         }
                         if ($cont== 3)
                         {
                              $conector = ' , ';
                              $conector1 = ' y ';
                         }
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                    }
			
               //pintar formula para opcion 4
                    if($vector1[$i][tipo_opcion_posologia_id]== 4)
                    {
                         $conector = '  ';
                         $frecuencia='';
                         $j=0;
                         foreach ($vector_posologia as $k => $v)
                         {
                              if ($j+1 ==sizeof($vector_posologia))
                              {
                                   $conector = '  ';
                              }
                              else
                              {
                                        if ($j+2 ==sizeof($vector_posologia))
                                             {
                                                  $conector = ' y ';
                                             }
                                        else
                                             {
                                                  $conector = ' - ';
                                             }
                              }
                              $frecuencia = $frecuencia.$k.$conector;
                              $j++;
                         }
                         $this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
                    }

			//pintar formula para opcion 5
                    if($vector1[$i][tipo_opcion_posologia_id]== 5)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                    }
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
                    $e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
                    if ($vector1[$i][contenido_unidad_venta])
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                         }
                    }
                    else
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
                         }
                    }
                    $this->salida.="</tr>";

                    $this->salida.="</table>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";

                    if($vector1[$i][item] == 'NO POS')
                    {
                         $this->salida.="<tr class=\"$estilo\">";
                         if($vector1[$i][sw_paciente_no_pos] != '1')
                         {
                              if($vector1[$i][evolucion_id] == $this->evolucion)
                              {
                                        $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Consultar_Justificacion', 'codigo_p'.$pfj => $vector1[$i][codigo_producto], 'product'.$pfj => $vector1[$i][producto], 'principio_a'.$pfj => $vector1[$i][principio_activo], 'via'.$pfj => $vector1[$i][via],'dosis'.$pfj => $vector1[$i][dosis], 'unidad'.$pfj => $vector1[$i][unidad_dosificacion], 'canti'.$pfj => $vector1[$i][cantidad],'desc'.$pfj => $vector1[$i][descripcion],'contenido_u_v'.$pfj => $vector1[$i][contenido_unidad_venta], 'obs'.$pfj => $vector1[$i][observacion], 'evolucion'.$pfj => $vector1[$i][evolucion_id]));
                                        $this->salida.="  <td colspan = 6 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/auditoria.png\" border='0'> VER JUSTIFICACION</a></td>";
                              }
                              else
                              {
                                        $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Consultar_Justificacion', 'codigo_p'.$pfj => $vector1[$i][codigo_producto], 'product'.$pfj => $vector1[$i][producto], 'principio_a'.$pfj => $vector1[$i][principio_activo], 'via'.$pfj => $vector1[$i][via],'dosis'.$pfj => $vector1[$i][dosis], 'unidad'.$pfj => $vector1[$i][unidad_dosificacion], 'canti'.$pfj => $vector1[$i][cantidad],'desc'.$pfj => $vector1[$i][descripcion],'contenido_u_v'.$pfj => $vector1[$i][contenido_unidad_venta], 'obs'.$pfj => $vector1[$i][observacion],'evolucion'.$pfj => $vector1[$i][evolucion_id],'consultar_just'.$pfj => 1));
                                        $this->salida.="  <td colspan = 6 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/auditoria.png\" border='0'> CONSULTAR JUSTIFICACION</a></td>";
                              }
                         }
                         else
                         {
                              $this->salida.="  <td class = label_error colspan = 6 align=\"center\" width=\"63%\">MEDICAMENTO NO POS FORMULADO A PETICION DEL PACIENTE</td>";
                         }
                         $this->salida.="</tr>";
                    }
                    //fin del if alterado
               }
               //fin del for muy importante
		}
	}
     
          
     //funciones para generar la barra de segmentos en el buscador
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

	 function RetornarBarra($filtro,$uno){
          if($this->limit>=$this->conteo){
               return '';
		}
		//if($filtro){$_SESSION['USUARIOS']['FILTRO']=$filtro;}//esto guarda el filtro...
		//de busqueda...
	  	$paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
		
          $datos_estacion = $_REQUEST["datos_estacion"];
		$datosPaciente = $_REQUEST["datosPaciente"];
          if($uno == 1)
          {
			$accion=ModuloGetURL('app','EE_AdministracionMedicamentos','user','SolSuministros_x_estacion',array('conteo'=>$this->conteo,'busqueda'=>$_REQUEST['busqueda'],"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$_REQUEST['bodega']));
          }
          else
          {
               $accion=ModuloGetURL('app','EE_AdministracionMedicamentos','user','AgregarInsumos_A_Paciente',array('conteo'=>$this->conteo,'busqueda'=>$_REQUEST['busqueda'],"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$_REQUEST['bodega']));
          }
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$this->salida .= "<br><table width='22%' border='0'  align='center' cellspacing=\"5\"  cellpadding=\"1\"><tr><td width='20%' class='label' bgcolor=\"#D3DCE3\">P???ginas</td>";
		if($paso > 1){
			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'><img src=\"".GetThemePath()."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'><img src=\"".GetThemePath()."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td width='7%' bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}else{
               $diferencia=$numpasos-9;
               if($diferencia<0){$diferencia=1;}
               for($i=($diferencia);$i<=$numpasos;$i++){
                    if($paso==$i){
                         $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\" >$i</td>";
                    }else{
                         $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                    }
                    $colspan++;
               }
               if($paso!=$numpasos){
                    $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
                    $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
                    $colspan++;
               }

          }
     	if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
				
			$this->salida .= "</tr><tr><td  class=\"label\"  colspan=".$valor." align='center'>P???gina&nbsp; $paso de $numpasos</td></tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$this->salida .= "</tr><tr><td   class=\"label\"  colspan=".$valor." align='center'>P???gina&nbsp; $paso de $numpasos</td></tr></table>";
		}
	}
    /*
    * Funcion que permite Cargar insumos de un paquete a la cuenta del paciente.
    * La solicitud se realiza a una bodega de la Estacion.
    */
    function FormaAgregarPaquetesPaciente()
    {
      $request = $_REQUEST;
      if($request['offset'])
        SessionSetVar("offset_guardar",$request['offset']);
      
      $datos_estacion = $request["datos_estacion"];
      $datosPaciente = $request["datosPaciente"];
      
      $datos1 = $this->GetEstacionBodega($datos_estacion,1);
      $datos1['*/*']['bodega'] = "*/*";
      $datos1['*/*']['descripcion'] = "SOLICITUD PACIENTE";
      
      $parametros = array();
      $parametros["paso"] = $request['paso'];
      $parametros["datos_estacion"] = $datos_estacion;
      $parametros["datosPaciente"] = $datosPaciente;
      
      if(empty($request['paso']))
        $pas = 1;
      else
        $pas = $request['paso'];
      
      $variable = "";
      $paquetes_add = SessionGetVar('PaquetesSeleccionados');
      $insumos_add = SessionGetVar('EXISTENCIA');
      $codigos_I = SessionGetVar('codigos_I');
      $medica_datos_sol_pac = SessionGetVar('MEDICA_DATOS_SOL_PAC');
      $cantidad_a_perdi_sol_I = SessionGetVar('cantidad_a_perdi_sol_I');
      
      $action['buscar'] = ModuloGetURL('app','EE_AdministracionMedicamentos','user','FormaAgregarPaquetesPaciente',$parametros);
      $action['volver'] = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',$parametros);
      $parametros['busqueda'] = $request['busqueda'];
      $parametros['bodega'] = $request['bodega'];
      $action['aceptar'] = ModuloGetURL('app','EE_AdministracionMedicamentos','user','FormaAgregarPaquetesPaciente',$parametros);
      $action['paginador'] = ModuloGetURL('app','EE_AdministracionMedicamentos','user','FormaAgregarPaquetesPaciente',$parametros);
      
      if($request['bodega'] == '*/*')
        $action['guardar'] = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Insertar_Solicitud_Insumos_Para_Paciente',$parametros);
      else
        $action['guardar'] = ModuloGetURL('app','EE_AdministracionMedicamentos','user','InsertarInsumosPaciente',$parametros);	
      

      $this->salida .= "<script>\n";
      $this->salida .= "  function chequeoTotal(frm,x)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    if(x==true)\n";
      $this->salida .= "    {\n";
      $this->salida .= "      for(i=0;i<frm.elements.length;i++)\n";
      $this->salida .= "      {\n";
      $this->salida .= "        if(frm.elements[i].type=='checkbox')\n";
      $this->salida .= "        {\n";
      $this->salida .= "          frm.elements[i].checked=true;\n";
      $this->salida .= "        }";
      $this->salida .= "      }";
      $this->salida .= "    }\n";
      $this->salida .= "    else\n";
      $this->salida .= "    {\n";
      $this->salida .= "      for(i=0;i<frm.elements.length;i++)\n";
      $this->salida .= "      {\n";
      $this->salida .= "        if(frm.elements[i].type=='checkbox')\n";
      $this->salida .= "        {\n";
      $this->salida .= "          frm.elements[i].checked=false;\n";
      $this->salida .= "        }\n";
      $this->salida .= "      }\n";
      $this->salida .= "    }\n";
      $this->salida .= "  }\n";
      $this->salida .= "	function CargarPagina(href,valor)\n";
      $this->salida .= "  {\n";
      $this->salida .= "		var url=href;\n";
      $this->salida .= "		location.href=url+'&bodega='+valor;\n";
      $this->salida .= "	}\n";
      $this->salida .= "</script>\n";
      $this->salida .= ThemeAbrirTabla("SELECCIONAR PAQUETES DE INSUMOS");
      $this->salida .= "<table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "  <tr class=\"modulo_table_title\">\n";
      $this->salida .= "	  <td>PACIENTE</td>\n";
      $this->salida .= "		<td>HABITACION</td>\n";
      $this->salida .= "		<td>CAMA</td>\n";
      $this->salida .= "		<td>PISO</td>\n";
      $this->salida .= "	</tr>\n";
      $this->salida .= "	<tr align='center' class='modulo_list_oscuro'>\n";
      $this->salida .= "		<td>".$datosPaciente['nombre_completo']."</td>\n";
      $this->salida .= "		<td>".$datosPaciente['pieza']."</td>\n";
      $this->salida .= "		<td>".$datosPaciente['cama']."</td>\n";
      $this->salida .= "		<td>".$datos_estacion['estacion_descripcion']."</td>\n";
      $this->salida .= "  </tr>\n";
      $this->salida .= "</table>\n";
      $this->salida .= "<br>\n";
      $this->salida .= "<div id=\"error\" style=\"text-align:center\" class=\"label_error\"></div>\n";
      $this->salida .= "<form name=\"mmm\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $this->salida .= "  <table align=\"center\" width=\"90%\" class=\"modulo_table_list\">\n";
      $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
      $this->salida .= "      <td align=\"center\" colspan=\"4\">BUSCADOR AVANZADO DE INSUMOS</td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "    <tr class=\"hc_table_submodulo_list_title\">";
      $this->salida .= "      <td width=\"5%\">BODEGA</td>\n";
      $this->salida .= "      <td width=\"10%\">\n";
      $this->salida .= "        <select name=\"bodega\" class=\"select\">\n";
      $chk = "";                        
      foreach($datos1 as $key => $dtl)
      {
        ($key == $request['bodega'])? $chk = "selected":$chk = "";
        $this->salida .= "          <option value=".$dtl['bodega']." ".$chk.">".$dtl['descripcion']."</option>\n";
      }
      $this->salida .= "        </select>\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "      <td width=\"25%\" align='center'>\n";
      $this->salida .= "        <input type=\"text\" class=\"input-text\" 	name=\"busqueda\" style=\"width:100%\" maxlength=\"40\"  value =\"".$request['busqueda']."\">\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "      <td width=\"6%\" align=\"center\">\n";
      $this->salida .= "        <input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"Buscar\">\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "  </table>\n";
     
      if($request['DEL'])
      {
        if($insumos_add[$pas])
        {
          foreach($insumos_add[$pas] as $k => $v)
          {
            unset($insumos_add[$k]);
            unset($cantidad_a_perdi_sol_I[$k]);
          }
          unset($insumos_add[$pas]);
        }
        $variable="SE QUITO TODOS LOS INSUMOS ADICIONADOS DE LA PAGINA &nbsp; $pas";
      }
      
      if($request['ADD'])
      {
        $request['offset'] = SessionGetVar("offset_guardar");
        
        foreach($request['op'] as $index=>$valor)
        {
          $cantidad = 0;          
          if(is_numeric($request['cant_'.$index.'_'.$valor]) && $request['cant_'.$index.'_'.$valor] > 0)
          {
            $paquetes_add[$valor][$request['paquete'][$index]]['cantidad'] = $request['cant_'.$index.'_'.$valor]; 
            
            foreach($paquetes_add[$valor] as $contador => $cnt)
              $cantidad += $cnt['cantidad'];
            
            $insumos_add[$pas][$valor]=$valor."*".$cantidad;
          
            $codigos_I[$valor] = $valor;
            $cantidad_a_perdi_sol_I[$valor] = $cantidad;
          }
        }				
        
        if($request['bodega']=='*/*')
        {
          $medica_datos_sol_pac['SOL_PAC_NOM']=$_REQUEST['nom'];
          $medica_datos_sol_pac['SOL_PAC_AREA']=$_REQUEST['area'];
        }
        else
        {
          unset($medica_datos_sol_pac);
        }	   
      }
      
      if($codigos_I)
      {
       	unset($salida);
       	foreach ($codigos_I as $k => $info)
        {
         	$codiguitos[] = $k;
        }

        for($jj=0; $jj<sizeof($codiguitos); $jj++)
        {
          $arr_temp[] = $this->GetInsumos($request['bodega'],'',$codiguitos[$jj],1,$datos_estacion);
          $salida  = "<br>\n";
          $salida .= "<table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $salida .= "    <td colspan=\"4\">MEDICAMENTOS ADICIONADOS</td>\n";
          $salida .= "  </tr>\n";
          $salida .= "  <tr class=\"hc_table_submodulo_list_title\">";
          $salida .= "    <td width=\"10%\">CODIGO</td>";
          $salida .= "    <td width=\"75%\" colspan='2'>PRODUCTO - UNIDAD DE MEDIDA</td>";
          $salida .= "    <td width=\"20%\">CANT</td>";
          $salida .= "  </tr>";
          $est = "";
          foreach($arr_temp as  $V => $vector)
          {
            foreach($vector as $V2 => $vector)
            { 
              ($est == "modulo_list_oscuro")? $est='modulo_list_claro':$est = 'modulo_list_oscuro';
              
              $salida .= "  <tr class=\"".$est."\">\n";
              $salida .= "    <td align=\"center\" width=\"10%\">".$vector[codigo_producto]."</td>";
              $salida .= "    <td align=\"left\" width=\"75%\" colspan='2'>".$vector[descripcion]."</td>";
              $salida .= "    <td align=\"center\" width=\"20%\">".$cantidad_a_perdi_sol_I[$vector[codigo_producto]]."</td>";
              $salida .= "  </tr>";               
            }
          }
          $salida.="</table>";
        }
      }
          
      $this->salida.= $salida;
          
      $nom=$medica_datos_sol_pac['SOL_PAC_NOM'];
      $area=$medica_datos_sol_pac['SOL_PAC_AREA'];
      
      SessionSetVar('EXISTENCIA',$insumos_add);
      SessionSetVar('PaquetesSeleccionados',$paquetes_add);
      SessionSetVar('codigos_I',$codigos_I);
      SessionSetVar('MEDICA_DATOS_SOL_PAC',$medica_datos_sol_pac);
      SessionSetVar('cantidad_a_perdi_sol_I',$cantidad_a_perdi_sol_I);

      $arr_vect = array();
      $slt = AutoCarga::factory("SolicitudesAutomaticas","classes","app","EE_AdministracionMedicamentos");
      if($request['bodega'])
        $arr_vect = $slt->ObtenerInsumosPaquetes($request,$datos_estacion);
            
      if(!empty($arr_vect))
      {
        $est = $check = "";
        $this->salida .= "<form name=\"vv\" method=\"post\" action=\"".$action['aceptar']."\">\n";
        $this->salida .= "  <div align='center'><label class='label_mark'>".$variable."</label></div>\n";
        $this->salida .= "  <br>\n";
        $this->salida .= "  <table align=\"center\" width=\"90%\" class=\"modulo_table_list\">\n";
        $this->salida .= "    <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "      <td width=\"10%\">ID</td>\n";
        $this->salida .= "      <td width=\"60%\" colspan='2'>PRODUCTO - ABREVIACION</td>\n";
        $this->salida .= "      <td width=\"20%\">PAQUETE</td>\n";
        $this->salida .= "      <td width=\"20%\" colspan=\"2\">CANT</td>\n";
        $this->salida .= "      <td width=\"5%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>\n";
        $this->salida .= "    </tr>\n";
        foreach($arr_vect as $i => $dtl)
        {
          $check="";
          $info=explode("*",$insumos_add[$pas][$dtl['codigo_producto']]);
          if($info[0] == $dtl['codigo_producto'] && $paquetes_add[$dtl['codigo_producto']][$dtl['insumo_paquete_id']]['cantidad'])
            $check="checked";
            
          ($est == "modulo_list_oscuro")? $est='modulo_list_claro':$est = 'modulo_list_oscuro';
          
          if(!($info[1] && $paquetes_add[$dtl['codigo_producto']][$dtl['insumo_paquete_id']]['cantidad'])) 
            $info[1] = $dtl['cantidad'];
          if($paquetes_add[$dtl['codigo_producto']][$dtl['insumo_paquete_id']]['cantidad'])
            $info[1] = $paquetes_add[$dtl['codigo_producto']][$dtl['insumo_paquete_id']]['cantidad'];
            
          $this->salida .= "    <tr class='".$est."' align='left'>\n";
          $this->salida .= "      <td>".$dtl['codigo_producto']."</td>\n";
          $this->salida .= "      <td width=\"30%\">".$dtl['descripcion']."</td>\n";
          $this->salida .= "      <td width=\"30%\">".$dtl['descripcion_abreviada']."</td>\n";
          $this->salida .= "      <td >".$dtl['descripcion_paquete']."</td>\n";
          $this->salida .= "      <td align=\"center\" class='label_mark'><b>Cant</b></td>\n";
          $this->salida .= "      <td align=\"center\" class='label_mark'>\n";
          $this->salida .= "        <input type='text' class='input-text' name=cant_".$i."_".$dtl['codigo_producto']." value='".$info[1]."' size='8' maxlength='8'>\n";
          $this->salida .= "      </td>\n";
          $this->salida .= "      <td width=\"5%\" align=\"center\">\n";
          $this->salida .= "        <input type=hidden name=paquete[$i] value=".$dtl['insumo_paquete_id']." >\n";
          $this->salida .= "        <input type=checkbox name=op[$i] value=".$dtl['codigo_producto']." ".$check.">\n";
          $this->salida .= "      </td>\n";
          $this->salida .= "    </tr>\n";
        }

        if($request['bodega']=='*/*')
        {
          $this->salida .= "    <tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "      <td colspan='7'>\n";
          $this->salida .= "        <br>\n";
          $this->salida .= "        <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_list\">\n";
          $this->salida .= "          <tr class=\"hc_table_submodulo_list_title\">";
          $this->salida .= "            <td ><label class='label_mark'>NOMBRE SOLICITANTE</label></td><td><input type='text' name='nom' size='55' maxlength='60' value='$nom'></td>";
          $this->salida .= "          </tr>\n";
          $this->salida .= "          <tr class=\"hc_table_submodulo_list_title\">";
          $this->salida .= "            <td ><label class='label_mark'>observaciones :</label></td><td><TEXTAREA name='area' rows='5' cols='80'>$area</TEXTAREA></td>";
          $this->salida .= "          </tr>\n";
          $this->salida .= "        </table>\n";
          $this->salida .= "      </td>\n";
          $this->salida .= "    </tr>\n";
        }	
        $this->salida .= "    <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "      <td colspan='5'><input type=submit name=DEL value='QUITAR ITEMS SELECCIONADOS DE ESTA PAGINA' class=input-submit></td>";
        $this->salida .= "      <td colspan='2'><input type=submit name=ADD value=ADICIONAR class=input-submit></td>";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= "</form>\n";
        $pg = AutoCarga::factory("ClaseHTML");
        $this->salida .= $pg->ObtenerPaginado($slt->conteo,$slt->pagina,$action['paginador']);
      }
      else
      {
        $this->salida .= "<br><br><div align='center'><label class='label_mark'>SELECCIONE LA BODEGA</label></div>";
      }
      
      $this->salida .= "<br><br>\n";
      $this->salida .= "<form name=\"formainsert\" action=\"".$action['guardar']."\" method=\"post\">\n";
      $this->salida .= "  <table align=\"center\" width=\"40%\" border=\"0\">\n";
      $this->salida .= "    <tr>\n";
      $this->salida .= "      <td align=\"center\">\n";
      $this->salida .= "        <input type=\"submit\" name=\"Guardar\" value=\"Guardar\" class=\"input-submit\">\n";
      $this->salida .= "      </form>\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "      <form name=\"volver\" method=\"post\" action=\"".$action['volver']."\">\n";
      $this->salida .= "      <td align=\"center\">\n";
      $this->salida .= "        <input type=\"submit\" name=\"volver\" value=\"Volver\" class=\"input-submit\">\n";
      $this->salida .= "      </form>\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "  </table>\n";
      $this->salida .= ThemeCerrarTablaSubModulo();
      return true;		
    }
    
 
     /**
      * Funcion donde se crea una Forma con una Ventana con capas para mostrar informacion
           * en pantalla
           * @param array $action vector que contiene los link de la aplicacion      
	 * @param int $tmn Tama???o que tendra la ventana
     * @return string
    */
    function CrearVentana($tmn = 950)
    {
            $this->salida .= "<script>\n";
            $this->salida .= "  var contenedor = 'Contenedor';\n";
            $this->salida .= "  var titulo = 'titulo';\n";
            $this->salida .= "  var hiZ = 4;\n";
            $this->salida .= "  function OcultarSpan()\n";
            $this->salida .= " { \n";
            $this->salida .= "   try\n";
            $this->salida .= "    {\n";
            $this->salida .= "      e = xGetElementById('Contenedor');\n";
            $this->salida .= "      e.style.display = \"none\";\n";
            $this->salida .= "  }\n";
            $this->salida .= "    catch(error){}\n";
            $this->salida .= "  }\n";
            $this->salida .= "  function MostrarSpan()\n";
            $this->salida .= " { \n";
            $this->salida .= "    try\n";
            $this->salida .= "    {\n";
            $this->salida .= "     e = xGetElementById('Contenedor');\n";
            $this->salida .= "     e.style.display = \"\";\n";
            $this->salida .= "    Iniciar();\n";
            $this->salida .= "   }\n";
            $this->salida .= "  catch(error){alert(error)}\n";
            $this->salida .= " }\n";     

            $this->salida .= " function MostrarTitle(Seccion)\n";
            $this->salida .= "  {\n";
            $this->salida .= "  xShow(Seccion);\n";
            $this->salida .= "  }\n";
            $this->salida .= "  function OcultarTitle(Seccion)\n";
            $this->salida .= "  {\n";
            $this->salida .= "    xHide(Seccion);\n";
            $this->salida .= "  }\n";

            $this->salida .= "  function Iniciar()\n";
            $this->salida .= "  {\n";
            $this->salida .= "    contenedor = 'Contenedor';\n";
            $this->salida .= "    titulo = 'titulo';\n";
            $this->salida .= "   ele = xGetElementById('Contenido');\n";
            $this->salida .= "   xResizeTo(ele,950, 'auto');\n";  
            $this->salida .= "    ele = xGetElementById(contenedor);\n";
            $this->salida .= "   xResizeTo(ele,950, 'auto');\n";
            $this->salida .= "    xMoveTo(ele, xClientWidth()/10, xScrollTop()+30);\n";
            $this->salida .= "   ele = xGetElementById(titulo);\n";
            $this->salida .= "   xResizeTo(ele,".(950 - 20).", 20);\n";
            $this->salida .= "    xMoveTo(ele, 0, 0);\n";
            $this->salida .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
            $this->salida .= "  ele = xGetElementById('cerrar');\n";
            $this->salida .= "    xResizeTo(ele,20, 20);\n";
            $this->salida .= "    xMoveTo(ele,".(950 - 20).", 0);\n";
            $this->salida .= "  }\n";

	    $this->salida .= "  function valida_campos()\n";
            $this->salida .= " { \n";
            $this->salida .= "     perdidas = document.getElementsByName('perdidas[]');\n";
	    $this->salida .= "     cantidad_suministrada = document.getElementsByName('cantidad_suministrada[]');\n";
	    $this->salida .= "     producto = document.getElementsByName('nom_prod[]');\n";
	    $this->salida .= "     dosis = document.getElementsByName('dosis[]');\n";
            $this->salida .= "	   tam=perdidas.length;";
            $this->salida .= "	   sw=0;";
	    $this->salida .= "  for(var i=0;i<tam;i++)";
	    $this->salida .= " { \n";
	    $this->salida .= "     if(perdidas[i].value=='' && cantidad_suministrada[i].value!=''){\n";
	    $this->salida .= "        perdidas[i].value=0;\n";
	    $this->salida .= "     }\n";
	    $this->salida .= "     if(perdidas[i].value!='' && cantidad_suministrada[i].value==''){\n";
	    $this->salida .= "        cantidad_suministrada[i].value=0;\n";
	    $this->salida .= "     }\n";
	    $this->salida .= "     if((perdidas[i].value=='' && cantidad_suministrada[i].value!='') || (perdidas[i].value!='' && cantidad_suministrada[i].value=='')){\n";
	    $this->salida .= "        alert('Llenar Todos Los Campos De '+producto[i].value);\n";
	    $this->salida .= "        cantidad_suministrada[i].focus();\n";
            $this->salida .= "	      sw=1;";
	    $this->salida .= "     }\n";
	    $this->salida .= "     else\n";		      
	    $this->salida .= "     { \n";
	    $this->salida .= "      	 if(isNaN(perdidas[i].value) || isNaN(cantidad_suministrada[i].value)){\n";
	    $this->salida .= "        		  alert('Los Campos Deben Ser Numericos En '+producto[i].value);\n";
            $this->salida .= "	   		  sw=1;";
	    $this->salida .= "      	 }\n";
	    $this->salida .= "     	else\n";		      
	    $this->salida .= "       	{ \n";
	    $this->salida .= "      		if(cantidad_suministrada[i].value > parseInt(dosis[i].value)){\n";
	    $this->salida .= "       			alert('La Cantidad Suministrada No Puede Ser Mayor A La Dosis Formulada En '+producto[i].value);\n";
            $this->salida .= "	  			sw=1;";
	    $this->salida .= "    		}\n";
	    $this->salida .= "       	}\n";
	    $this->salida .= "    }\n";
	    $this->salida .= " }\n";
	    $this->salida .= " if(sw==0)";
	    $this->salida .= " { \n";
	    $this->salida .= "   document.formadesr.submit(); \n";
	    $this->salida .= " }\n";    
	    $this->salida .= " 	} \n";

	    $this->salida .= "  function valida_camposI()\n";
            $this->salida .= " { \n";
            $this->salida .= "     perdidas = document.getElementsByName('perdidas[]');\n";
	    $this->salida .= "     cantidad_suministrada = document.getElementsByName('cantidad_suministrada[]');\n";
	    $this->salida .= "     producto = document.getElementsByName('nom_prod[]');\n";
	    $this->salida .= "     BodegaPaciente = document.getElementsByName('BodegaPaciente[]');\n";
            $this->salida .= "	   tam=perdidas.length;";
            $this->salida .= "	   sw=0;";
	    $this->salida .= "  for(var i=0;i<tam;i++)";
	    $this->salida .= " { \n";
	    $this->salida .= "     if(perdidas[i].value=='' && cantidad_suministrada[i].value!=''){\n";
	    $this->salida .= "        perdidas[i].value=0;\n";
	    $this->salida .= "     }\n";
	    $this->salida .= "     if(perdidas[i].value!='' && cantidad_suministrada[i].value==''){\n";
	    $this->salida .= "        cantidad_suministrada[i].value=0;\n";
	    $this->salida .= "     }\n";
	    $this->salida .= "     if((perdidas[i].value=='' && cantidad_suministrada[i].value!='') || (perdidas[i].value!='' && cantidad_suministrada[i].value=='')){\n";
	    $this->salida .= "        alert('Llenar Todos Los Campos De '+producto[i].value);\n";
	    $this->salida .= "        cantidad_suministrada[i].focus();\n";
            $this->salida .= "	     sw=1;";
	    $this->salida .= "     }\n";
	    $this->salida .= "     else\n";		      
	    $this->salida .= "     { \n";
	    $this->salida .= "      	 if(isNaN(perdidas[i].value) || isNaN(cantidad_suministrada[i].value)){\n";
	    $this->salida .= "        		  alert('Los Campos Deben Ser Numericos En '+producto[i].value);\n";
	    $this->salida .= "        		  cantidad_suministrada[i].focus();\n";
	    $this->salida .= "	     		  sw=1;";
	    $this->salida .= "      	 }\n";
	    $this->salida .= "     	else\n";		      
	    $this->salida .= "       	{ \n";
	    $this->salida .= "      		if(cantidad_suministrada[i].value > parseInt(BodegaPaciente[i].value)){\n";
	    $this->salida .= "       			alert('La Cantidad A Suministrar Es Mayor Al Stock En '+producto[i].value);\n";
	    $this->salida .= "        			cantidad_suministrada[i].focus();\n";
            $this->salida .= "	  			sw=1;";
	    $this->salida .= "    		}\n";
	    $this->salida .= "       	}\n";
	    $this->salida .= "    }\n";
	    $this->salida .= " }\n";
	    $this->salida .= "      	  if(sw==0){\n";
	    $this->salida .= "        	      document.formadesr.submit(); \n";
	    $this->salida .= " 	 	  }\n";    
	    $this->salida .= " }\n";

            $this->salida .= "  function myOnDragStart(ele, mx, my)\n";
            $this->salida .= " {\n";
            $this->salida .= "    window.status = '';\n";
            $this->salida .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
            $this->salida .= "   else xZIndex(ele, hiZ++);\n";
            $this->salida .= "   ele.myTotalMX = 0;\n";
            $this->salida .= "    ele.myTotalMY = 0;\n";
            $this->salida .= " }\n";
            $this->salida .= "  function myOnDrag(ele, mdx, mdy)\n";
            $this->salida .= "  {\n";
            $this->salida .= "    if (ele.id == titulo) {\n";
            $this->salida .= "     xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
            $this->salida .= "    }\n";
            $this->salida .= "   else {\n";
            $this->salida .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
            $this->salida .= "   }  \n";
            $this->salida .= "  ele.myTotalMX += mdx;\n";
            $this->salida .= "   ele.myTotalMY += mdy;\n";
            $this->salida .= " }\n";
            $this->salida .= " function myOnDragEnd(ele, mx, my)\n";
            $this->salida .= " {\n";
            $this->salida .= "  }\n";
            $this->salida .= "</script>\n";
            $this->salida .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
            $this->salida .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">CONFIRMACI???N</div>\n";
            $this->salida .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
            $this->salida .= "  <div id='Contenido' class='d2Content'>\n";
            $this->salida .= " </div>\n";
            $this->salida .= "</div>\n";
            return  $this->salida;
    }
    function ConfirmarSuministrosRapidos()
     {	
	  $this->salida .= "<script>\n";
	   $this->salida .= "function mensajeError(msj)\n";
	   $this->salida .= "{\n";
	   $this->salida .= "alert(''+msj);\n";
	   $this->salida .= "}\n";
	  $this->salida .= "</script>\n";
	  
          $datosPaciente = $_REQUEST['datosPaciente'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $tipo_solicitud = $_REQUEST['tipo_solicitud'];
          
          $vect = $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR'];
         
          //aca va el id de la bodega solamente
          $bodega = $_REQUEST['bodega'];
          // Datos medicos
          $tipo_solicitud = $_REQUEST['tipo_solicitud'];
         
          $ProductosSUM = $_REQUEST['datos_SUM'];
          
          $CantidadesSUM = $_REQUEST['cantidad_suministrada'];
          $aprovechamiento = $_REQUEST['aprovechamiento'];
          $perdidas = $_REQUEST['perdidas'];
          $factorC = $_REQUEST['FactorC'];
          
          $ingreso_F = $_SESSION['ingreso_F'];
          $num_F = $_REQUEST['num_F'];
          $cantidad_recetada = $_REQUEST['cantidad_recetada'];
          $checo = $_REQUEST['checo'];
	  
          
         for($i=0; $i<sizeof($ProductosSUM); $i++)
         {
			$fecha_realizado[$i] = $_REQUEST['selectHora'][$i].":".$_REQUEST['selectMinutos'][$i]; 
				//Controles referentes a la administracion del medicamento.
							
				   if($bodega == '*/*')
				   {
					if($factorC[$i] != "")
						{
							 
								  $ConversionBodega = ($_REQUEST['BodegaPaciente'][$i] * $factorC[$i]);
						 }
				else{
							
							$ConversionBodega = $_REQUEST['BodegaPaciente'][$i];
						}
							 
						if($_REQUEST['cantidad_suministrada'][$i] > $ConversionBodega){
				  $this->salida .= "<script>";
				  $this->salida .= "mensajeError('La Bodega De Paciente No Cuenta Con Existencias Suficientes Para El Codigo ".$ProductosSUM[$i].". ')\n ";
				   $this->salida .= "history.back();\n";
				  $this->salida .= "</script>";
				  return $this->salida;
							 /*$this->frmError["cantidad_suministrada"]=1;
							 $this->frmError["MensajeError"]="".$_REQUEST['cantidad_suministrada'][$i].$ConversionBodega.$factorC[$i]."LA BODEGA DEL PACIENTE.<BR> NO CUENTA CON LAS EXISTENCIAS SUFICIENTES.";
							 $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
							 return true;*/
						}
					
						// Suministros + Desechos
						$SumDes = ($_REQUEST['cantidad_suministrada'][$i] + $_REQUEST['perdidas'][$i]);
						if($SumDes > $ConversionBodega){
				  $this->salida .= "<script>";
				  $this->salida .= "mensajeError('La Bodega De Paciente No Cuenta Con Existencias Suficientes Para Para El Codigo ".$ProductosSUM[$i].". ');\n";
				   $this->salida .= "history.back();\n";
				  $this->salida .= "</script>"; 
				  return $this->salida;
							 /*$this->frmError["cantidad_suministrada"]=1;
							 $this->frmError["MensajeError"]="LA BODEGA DEL PACIENTE.<BR> NO CUENTA CON LAS EXISTENCIAS SUFICIENTES.";
							 $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
							 return true;*/
						}
				   }else
				   {
						// Verificacion de Bodegas de Consumo Directo.
						$datos = $this->GetEstacionBodega_Existencias($datos_estacion, 2, $ProductosSUM[$i]);
						
						if($factorC[$i] != "")
						{
							$ConversionBodega = ($datos[0][existencia] * $factorC[$i]);
						 }
				 else{
							
							$ConversionBodega = $datos[0][existencia];
						}
						
						if($_REQUEST['cantidad_suministrada'][$i] > $ConversionBodega){
				   $this->salida .= "<script>";
				  $this->salida .= "mensajeError('La Bodega De Consumo Directo No Cuenta Con Las Existencias Suficientes Para El Producto ".$ProductosSUM[$i].". ');\n";
				   $this->salida .= "history.back();\n";
				  $this->salida .= "</script>"; 
				  return $this->salida;
							 /*$this->frmError["cantidad_suministrada"]=1;
							 $this->frmError["MensajeError"]="LA BODEGA DE CONSUMO DIRECTO.<BR> NO CUENTA CON LAS EXISTENCIAS SUFICIENTES PARA EL PRODUCTO ".$ProductosSUM[$i].".";
							 $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
							 return true;*/
						}
					
						// Suministros + Desechos
						$SumDes = ($_REQUEST['cantidad_suministrada'][$i] + $_REQUEST['perdidas'][$i]);
						if($SumDes > $ConversionBodega){
				   $this->salida .= "<script>";
				  $this->salida .= "mensajeError('La Bodega De Consumo Directo No Cuenta Con Las Existencias Suficientes Para El Producto ".$ProductosSUM[$i].". ');\n";
				   $this->salida .= "history.back();\n";
				  $this->salida .= "</script>"; 
				  return $this->salida;
							 /*$this->frmError["cantidad_suministrada"]=1;
							 $this->frmError["MensajeError"]="LA BODEGA DE CONSUMO DIRECTO.<BR> NO CUENTA CON LAS EXISTENCIAS SUFICIENTES PARA EL PRODUCTO ".$ProductosSUM[$i].".";
							 $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
							 return true;*/
						}
				   }
          
		}

          
          $this->InsertarSuministroPaciente($datosPaciente, $datos_estacion, $vect, $bodega, $tipo_solicitud, $fecha_realizado, $ProductosSUM, $CantidadesSUM, $ingreso_F, $checo, $num_F, $perdidas, $cantidad_recetada, $factorC);
	   $this->salida .= "<script>";
       $this->salida .= "document.location.href='".$_SESSION['url']."';\n";
       $this->salida .= "</script>"; 
       return $this->salida;  
          return $this->salida;
    }
    function ConfirmarSuministrosRapidosInsumos(){

      $datosPaciente = $_REQUEST['datosPaciente'];
      $datos_estacion = $_REQUEST['datos_estacion'];
      $tipo_solicitud = $_REQUEST['tipo_solicitud'];
      
      $vect = $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR'];
      
      //aca va el id de la bodega solamente
      $bodega = $_REQUEST['bodega'];
      // Datos medicos
      $tipo_solicitud = $_REQUEST['tipo_solicitud'];
      
      $ProductosSUM = $_REQUEST['datos_SUM'];
      
      $CantidadesSUM = $_REQUEST['cantidad_suministrada'];
      $aprovechamiento = $_REQUEST['aprovechamiento'];
      $perdidas = $_REQUEST['perdidas'];
      $factorC = $_REQUEST['FactorC'];
      
      $ingreso_F = $_REQUEST['ingreso_F'];
      $num_F = $_REQUEST['num_F'];
      $cantidad_recetada = $_REQUEST['cantidad_recetada'];
      $checo = $_REQUEST['checo'];
      
      for($i=0; $i<sizeof($ProductosSUM); $i++)
      {
        //Controles referentes a la administracion del medicamento.
	 $fecha_realizado = $_REQUEST['selectHora'][$i].":".$_REQUEST['selectMinutos'][$i]; 
        if(($_REQUEST['cantidad_suministrada'][$i] != '') OR ($_REQUEST['perdidas'][$i] != ''))
        {
          if(empty($_REQUEST['cantidad_suministrada'][$i]))
          {
            $_REQUEST['cantidad_suministrada'][$i] = 0;
          }

          if(empty($_REQUEST['perdidas'][$i]))
          {
            $_REQUEST['perdidas'][$i] = 0;
          }
          
         
        }
        // Verificacion de Bodegas de Consumo Directo.
        $datos = $this->GetEstacionBodega_Existencias($datos_estacion, 2, $ProductosSUM[$i]);
      }
      $this->InsertarSuministroInsumosPaciente($datosPaciente, $datos_estacion, $vect, $bodega, $tipo_solicitud, $fecha_realizado, $ProductosSUM, $CantidadesSUM, $ingreso_F, $checo, $num_F, $perdidas, $cantidad_recetada, $factorC);
      //return true;
	
       $this->salida .= "<script>";
       $this->salida .= "document.location.href='".$_SESSION['url']."';\n";
       $this->salida .= "</script>"; 
       return $this->salida;
    }

    
  }//fin de la clase
?>