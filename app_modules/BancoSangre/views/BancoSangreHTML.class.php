<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: BancoSangreHTML.class.php,v 1.1 2008/01/09 14:51:37 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez 
  */
  /**
  * Clase Vista: BancoSangreHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */ 
  IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");
  class BancoSangreHTML
  {
    /**
    *Constructor de la clase
    */    
    function BancoSangreHTML(){}
    /**
    * Funcion donde se crea la forma para el menu del Banco de Sangre
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaMenu($action)
    {
      $html  = ThemeAbrirTabla('BANCO DE SANGRE');
      $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
      $html .= "  <tr class=\"modulo_table_title\">\n";
      $html .= "    <td align=\"center\">MENU\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_oscuro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['ficha_donante']."\">LLENAR FICHA DE DONANTE</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_oscuro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['registrar_donaciones']."\">REGISTRAR DONACIONES</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_oscuro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['consulta_donantes']."\">CONSULTA DE DONANTES</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_oscuro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['fraccionamiento_sangre']."\">FRACCIONAMIENTO DE UNIDADES DE SANGRE</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_oscuro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['consulta_frac_sangre']."\">CONSULTA FRACCIONAMIENTO DE UNIDADES DE SANGRE</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_oscuro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['hemocomponentes_ob']."\">REGISTRO DE HEMOCOMPONENTES DE OTROS BANCOS</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </form>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= ThemeCerrarTabla();
            
      return $html;
    }
    /**
    * Funcion donde se crea la forma para registrar los datos personales del donante 
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $tipos_donante vector con la informacion de los tipos de donante
    * @param array $convenios vector con la informacion de los convenios del donante
    * @param array $tipos_id vector con la informacion de los tipos de identificacion
    * @return string $html retorna la cadena con el codigo html de la forma
    */
    function formaFichaDonante($action, $tipos_donante, $convenios, $tipos_id)
    {
      $html  = ThemeAbrirTabla('FICHA DONANTE');
      $html .= "<form name=\"formFichaDonante\" id=\"formFichaDonante\" method=\"post\" action=\"".$action['registrar_donante']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">FICHA DEL DONANTE\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $fecha = date("d/m/Y");
      $hora = date("H:i:s");
      $fe = explode("/",$fecha);
      if(sizeof($fe)==3) 
      {
        $fMes=$fe[1];
        $fDia=$fe[0];
      }
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"20%\">Fecha Actual:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$fecha."\n";
      $html .= "        <input type=\"hidden\" name=\"fechaActual\" value=\"".$fecha."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"20%\">Hora Actual:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">".$hora."\n";
      $html .= "        <input type=\"hidden\" name=\"horaActual\" value=\"".$hora."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"20%\">Tipo de Donador:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <div id=\"divTipoDonador\">\n";
      /*$html .= "          <select class=\"select\" name=\"tipoDonador\" onchange=\"ValidarTipoDonador()\">\n";
      $html .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($tipos_donante as $indice => $valor)
      {
        $html .= "          <option value=\"".$valor['tipo_donante_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "          </select>\n";*/
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Convenio:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <div id=\"divConvenio\">\n";
      /*$html .= "          <select class=\"select\" name=\"convenio\" disabled>\n";
      $html .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($convenios as $indice => $valor)
      {
        $html .= "          <option value=\"".$valor['convenio_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "          </select>\n";*/
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Militar?\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\">\n";
      $html .= "        <div id=\"divMilitar\">\n";
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"hc_table_submodulo_list_title\">\n";
      $html .= "      <td align=\"left\" colspan=\"6\">DATOS DEL PACIENTE\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cedula de Identidad:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <div id=\"divId\">\n";
      $html .= "          <input class=\"input-text\" type=\"text\" name=\"noId\" id=\"noId\" size=\"10%\">\n";
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipo Identificacion:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <div id=\"divTipoid\">\n";
      $html .= "          <select class=\"select\" name=\"tipoId\" id=\"tipoId\">\n";
      $html .= "            <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($tipos_id as $indice => $valor)
      {
        $html .= "          <option value=\"".$valor['tipo_id_paciente']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "          </select>\n";
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscarPaciente\" value=\"Buscar Paciente\" onclick=\"BuscarPaciente()\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"6\" align=\"center\">\n";
      $html .= "        <div id=\"error_bp\" class=\"label_error\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"6\" align=\"center\">\n";
      $html .= "        <div id=\"infoPaciente\"></div>\n";      
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"6\" align=\"center\">\n";
      $html .= "        <div id=\"error\" class=\"label_error\"></div>\n";
      $html .= "        <input type=\"hidden\" name=\"val_id\" id=\"val_id\" value=\"\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $cut = new ClaseUtil();
      $html .= $cut->AcceptDate("/");
      $html .= $cut->IsDate();
      $html .= "  </table>";
      $html .= "</form>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver_menu']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      //------------------------------------------------------------------------------------
      $html .= "<script>\n";
      $html .= "  function ValidarTipoDonador()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formFichaDonante;\n";
      $html .= "    var indice = frm.tipoDonador.selectedIndex;\n";
      $html .= "    if(frm.tipoDonador.options[indice].text==\"Convenio\")\n";
      $html .= "    {\n";
      $html .= "      frm.convenio.disabled = false;\n";      
      $html .= "    }else{\n";
      $html .= "      frm.convenio.disabled = true;\n";
      $html .= "      frm.convenio.value = -1;\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function ValidarMilitar()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formFichaDonante;\n";
      $html .= "    if(frm.militar.value==\"1\")\n";
      $html .= "    {\n";
      $html .= "      frm.fuerza.disabled = false;\n";
      $html .= "      frm.categoria.disabled = false;\n";
      $html .= "      frm.grado.disabled = false;\n";
      $html .= "    }else{\n";
      $html .= "      frm.fuerza.disabled = true;\n";
      $html .= "      frm.categoria.disabled = true;\n";
      $html .= "      frm.grado.disabled = true;\n";
      $html .= "      frm.clasificacion.value=-1;\n";
      $html .= "      document.getElementById('clasiFinanciera').innerHTML = '';\n";
      $html .= "      frm.fuerza.value = -1;\n";
      $html .= "      frm.categoria.value = -1;\n";
      $html .= "      frm.grado.value = -1;\n";
      //$html .= "      alert(frm.fuerza.value);\n";
      $html .= "      var_gr = '<input type=\"hidden\" name=\"grado\" id=\"grado\" value=\"-1\">';\n";
      $html .= "      document.getElementById('descGrado').innerHTML = var_gr;\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function BuscarPaciente()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formFichaDonante;\n";
      $html .= "    if(frm.noId.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('infoPaciente').innerHTML = '';\n";
      $html .= "      document.getElementById('error_bp').innerHTML = 'Debe ingresar la cedula de identidad';\n";
      $html .= "      return;\n";
      $html .= "    }\n";      
      $html .= "    if(frm.tipoId.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('infoPaciente').innerHTML = '';\n";
      $html .= "      document.getElementById('error_bp').innerHTML = 'Debe seleccionar el tipo de identificacion';\n";
      $html .= "      return;\n";
      $html .= "    }else{\n";
      $html .= "      document.getElementById('error_bp').innerHTML = '';\n";
      $html .= "      xajax_BuscarDatosPaciente(xajax.getFormValues(formFichaDonante));\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function continuar()\n";
      $html .= "  {\n";
      $html .= "    document.formFichaDonante.submit();\n";
      $html .= "  }\n";
      $html .= "  function ValidarCategoria()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formFichaDonante;\n";
      $html .= "    if(frm.categoria.value!=\"-1\")\n";
      $html .= "    {\n";
      $html .= "      xajax_BuscarGrado(xajax.getFormValues(formFichaDonante));\n";
      $html .= "    }else{\n";
      $html .= "      return;\n";
      $html .= "    }";
      $html .= "  }\n";
      $html .= "  function ValidarGrado()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formFichaDonante;\n";
      $html .= "    if(frm.grado.value!=\"-1\")\n"; 
      $html .= "    {\n";
      $html .= "      xajax_BuscarClasiFinanciera(xajax.getFormValues(formFichaDonante));\n";
      $html .= "    }else{\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function CalcularEdad()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formFichaDonante;\n";
      $html .= "    if(frm.fechaNacimiento.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('edad_c').innerHTML = 'Fecha invalida';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsDate(frm.fechaNacimiento.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('edad_c').innerHTML = 'Fecha invalida';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    fn = frm.fechaNacimiento.value;\n";
      $html .= "    fa = frm.fechaActual.value;\n";
      $html .= "    var fecha_n = fn.split('/');\n";
      $html .= "    var fecha_a = fa.split('/');\n";
      $html .= "    ffn = new Date(fecha_n[2]+'/'+fecha_n[1]+'/'+fecha_n[0]);\n";
      $html .= "    ffa = new Date(fecha_a[2]+'/'+fecha_a[1]+'/'+fecha_a[0]);\n";
      $html .= "    if(ffn >= ffa)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('edad_c').innerHTML = 'Fecha invalida';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    document.getElementById('edad_c').innerHTML = '';\n";
      $html .= "    xajax_CalcEdad(xajax.getFormValues(formFichaDonante));\n";      
      $html .= "  }\n";
      $html .= "  function ValidarDatos()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formFichaDonante;\n";
      $html .= "    if(frm.tipoDonador.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el tipo de donador';\n";
      $html .= "      return;";
      $html .= "    }\n";
      $html .= "    var indice_td = frm.tipoDonador.selectedIndex;\n";
      //$html .= "    alert('CONVENIO '+frm.convenio.value);\n";
      $html .= "    if((frm.tipoDonador.options[indice_td].text==\"Convenio\" && frm.convenio.value==\"-1\") || (frm.tipoDonador.options[indice_td].text==\"Convenio\" && frm.convenio.value==\"\"))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el Convenio';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.noId.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la cedula de identidad';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.tipoId.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el tipo de identificacion';\n";
      $html .= "      return;\n";      
      $html .= "    }\n";
      $html .= "    if(frm.apellidoPaterno.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el apellido paterno';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.primerNombre.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el primer nombre';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.militar.value==\"1\" && frm.fuerza.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar la fuerza';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.militar.value==\"1\" && frm.categoria.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar la categoria';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      //$html .= "    alert('GRADO '+frm.grado.value);\n";
      $html .= "    if((frm.militar.value==\"1\" && frm.grado.value==\"-1\") || (frm.militar.value==\"1\" && frm.grado.value==\"\"))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el grado';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.militar.value==\"1\" && frm.clasificacion.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar la clasificacion financiera';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.fechaNacimiento.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la fecha de nacimiento';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsDate(frm.fechaNacimiento.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de nacimiento posee un formato invalido';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    fn = frm.fechaNacimiento.value;\n";
      $html .= "    fa = frm.fechaActual.value;\n";
      $html .= "    var fecha_n = fn.split('/');\n";
      $html .= "    var fecha_a = fa.split('/');\n";
      $html .= "    ffn = new Date(fecha_n[2]+'/'+fecha_n[1]+'/'+fecha_n[0]);\n";
      $html .= "    ffa = new Date(fecha_a[2]+'/'+fecha_a[1]+'/'+fecha_a[0]);\n";
      $html .= "    if(ffn >= ffa)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La fecha de nacimiento debe ser menor a la fecha actual';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.sexo.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el sexo del donante';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.estadoCivil.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el estado civil del donante';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.ocupacion_id.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe consultar la ocupacion del donante';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.ocupacion_id.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe consultar la ocupacion del donante';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.dirDomicilio.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la direccion del domicilio';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    xajax_ValidarCedula(xajax.getFormValues(formFichaDonante));\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<script language=\"javascript\">\n";
      $html .= "  var vFormCamp;\n";
      $html .= "  function Mostrar_Campo(vFormaCampo, cadCampo)\n";
      $html .= "  {\n";
      $html .= "    var dia='';\n";
      $html .= "    var mes='';\n";
      $html .= "    var anyo='';\n";
      $html .= "    var valor='';\n";
      $html .= "    try{\n";
      $html .= "      vFormCamp=vFormaCampo;\n";
      $html .= "      valor=vFormaCampo.value;\n";
      $html .= "    }catch(error){}\n";
      $html .= "    if(valor.length==10)\n";
      $html .= "    {\n";
      $html .= "      dia=valor.split('/')[0];\n";
      $html .= "      mes=parseInt(valor.split('/')[1])-1;\n";
      $html .= "      anyo=valor.split('/')[2];\n";
      $html .= "    }\n";
      $html .= "    CrearCalendario('Campo','/',dia,mes,anyo);\n";
      $html .= "  }\n";
      $html .= "  function Ocultar_Campo(fecha)\n";
      $html .= "  {\n";
      $html .= "    if(fecha!='')\n";
      $html .= "    {\n";
      $html .= "      vFormCamp.value=fecha;\n";
      $html .= "    }\n";      
      $html .= "    document.getElementById('calendario_pxCampo').style.visibility = 'hidden';\n";
      $html .= "  }\n";
      $html .= "  function Ocupaciones(forma, prefijo)\n";
      $html .= "  {\n";
      $html .= "    var url='reports/HTML/ocupaciones.php?forma=' + forma +'&prefijo=' + prefijo;\n";
      $html .= "window.open(url,'','width=600,height=500,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no');\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma para registrar la informacion de los signos vitales y
    * la tipificacion del paciente
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $grupos_sang vector que contiene la informacion de los grupos sanguineos
    * @param array $rh vector que contiene la informacion de los tipos de RH
    * @param array $subgrupo_rh vector que contiene la informacion de los sub grupos 
    * sanguineos
    * @param array $request arreglo con la informacion del request
    * @param integer $cod_donante contiene el codigo del donante
    * @param array $tipificacion vector con la informacion de la tipificacion del donante
    * @return string $html retorna la cadena con el codigo html de la pagina 
    */
    function formaSignosVitales($action, $grupos_sang, $rh, $subgrupo_rh, $request, $cod_donante, $tipificacion)
    {
      $html  = ThemeAbrirTabla('SIGNOS VITALES Y TIPIFICACION');
      $html .= "<form name=\"formSignosVitales\" id=\"formSignosVitales\" action=\"".$action['registrar_signos'].URLRequest(array("codDonante"=>$request["codDonante"], "sexo"=>$request['sexo'], "noId"=>$request['noId'], "tipoId"=>$request['tipoId']))."\" method=\"post\">\n";
      $html .= "  <input type=\"hidden\" name=\"cod_don\" value=\"".$cod_donante['cod_don']."\">\n";
      $html .= "  <input type=\"hidden\" name=\"cod_det\" value=\"".$cod_donante['cod_det']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"77%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"7\">SIGNOS VITALES\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"11%\">T.A.\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"11%\">PULSO\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"11%\">F.R.\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"11%\">TEMPERATURA Cº\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"11%\">PESO KG\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"11%\">ALTURA CM\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"11%\">MASA CORPORAL\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $cut = new ClaseUtil();
      $html .= $cut->IsDate();
      $html .= $cut->AcceptNum(false);
      $html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"tensionArterial\" size=\"10%\" maxlength=\"5\" onkeypress=\"return acceptNum(event)\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"pulso\" size=\"10%\" maxlength=\"5\" onkeypress=\"return acceptNum(event)\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"frecRespiratoria\" size=\"10%\" maxlength=\"5\" onkeypress=\"return acceptNum(event)\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"temperatura\" size=\"10%\" maxlength=\"6\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"peso\" size=\"10%\" onchange=\"LimpiarMC()\" maxlength=\"7\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"altura\" size=\"10%\" onchange=\"LimpiarMC()\" maxlength=\"6\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "        <div id=\"div_mc\"></div>\n";
      $html .= "        <input type=\"hidden\" name=\"masaCorporal\" value=\"\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"7\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"calcularMC\" value=\"Calcular MC\" onclick=\"CalcularIMC()\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"7\">\n";
      $html .= "        <div id=\"error_mc\" class=\"label_error\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <table class=\"modulo_table_list\" width=\"77%\" align=\"center\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\">TIPIFICACION\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Grupo Sanguineo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"grupoSanguineo\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($grupos_sang as $indice => $valor)
      {
        if($valor['grupo_sanguineo']==$tipificacion['grupo_sanguineo'])
          $sel_gs = "selected";
        else
          $sel_gs = "";
        $html .= "        <option value=\"".$valor['grupo_sanguineo']."\" ".$sel_gs.">".$valor['grupo_sanguineo']."</option>\n";
      }
      $html .= "        </select>\n";      
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Factor RH:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"factorRH\" onchange=\"ValidarFactorRH()\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($rh as $indice => $valor)
      {
        if($valor['rh']==$tipificacion['rh_gs'])
          $sel_rh = "selected";
        else
          $sel_rh = "";
        $html .= "        <option value=\"".$valor['rh']."\" ".$sel_rh.">".$valor['rh']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Subgrupo RH-:\n";
      $html .= "      </td>\n";

      if($tipificacion['subgrupo_rh']!="" && $tipificacion['rh_sg']!="")
        $dis_sgrh = "";
      else
        $dis_sgrh = "disabled";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"subgrupoRH\" ".$dis_sgrh.">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($subgrupo_rh as $indice => $valor)
      {
        if($valor['subgrupo_rh']==$tipificacion['subgrupo_rh'] && $valor['rh']==$tipificacion['rh_sg'])
          $sel_sgrh = "selected";
        else
          $sel_sgrh = "";
        $html .= "        <option value=\"".$valor['subgrupo_rh']."/".$valor['rh']."\" ".$sel_sgrh.">".$valor['subgrupo_rh'].$valor['rh']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "        <input type=\"hidden\" name=\"rh_sg\" value=\"\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Observaciones:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">\n";
      $html .= "        <textarea class=\"textarea\" rows=\"1\" name=\"observaciones\" style=\"width:100%;background:#FFFFFF\">".$tipificacion['observaciones']."</textarea>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"6\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"ValidarDatos()\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"6\" align=\"center\">\n";
      $html .= "        <div id=\"error\" class=\"label_error\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver_ficha']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
            
      $html .= "<script>\n";
      $html .= "  function LimpiarMC()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formSignosVitales;\n";
      $html .= "    frm.masaCorporal.value='';\n";
      $html .= "    document.getElementById('div_mc').innerHTML = '';\n";
      $html .= "    return;\n";
      $html .= "  }\n";
      $html .= "  function ValidarFactorRH()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formSignosVitales;\n";
      $html .= "    if(frm.factorRH.value==\"-\")\n";
      $html .= "    {\n";
      $html .= "      frm.subgrupoRH.disabled = false;\n";
      $html .= "    }else{\n";
      $html .= "      frm.subgrupoRH.disabled = true;\n";
      $html .= "      frm.subgrupoRH.value = -1;\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function CalcularIMC()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formSignosVitales;\n";
      $html .= "    peso = frm.peso.value;\n";
      $html .= "    altura = frm.altura.value;\n";
      $html .= "    if(frm.peso.value==\"\")";
      $html .= "    {\n";
      $html .= "      document.getElementById('error_mc').innerHTML = 'Debe ingresar el peso';\n";
      $html .= "      frm.peso.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.peso.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error_mc').innerHTML = 'El valor del peso debe ser numerico';\n";
      $html .= "      frm.peso.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.altura.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error_mc').innerHTML = 'Debe ingresar la altura';\n";
      $html .= "      frm.altura.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.altura.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error_mc').innerHTML = 'El valor de la altura debe ser numerico';\n";
      $html .= "      frm.altura.focus();\n";
      $html .= "      return;\n";
      $html .= "    }else{\n";
      $html .= "        alt_m = altura/100;\n";
      $html .= "        imc = peso/(alt_m*alt_m)*1;\n";
      $html .= "        document.getElementById('error_mc').innerHTML = '';\n";
      $html .= "        frm.masaCorporal.value = imc.toFixed(2);\n";
      $html .= "        document.getElementById('div_mc').innerHTML = imc.toFixed(2);\n";
      $html .= "        return;\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function ValidarDatos()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formSignosVitales;\n";
      $html .= "    if(frm.tensionArterial.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la tension arterial';\n";
      $html .= "      frm.tensionArterial.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.tensionArterial.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor de la tencion arterial debe ser numerico';\n";
      $html .= "      frm.tensionArterial.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.pulso.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el pulso';\n";
      $html .= "      frm.pulso.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.pulso.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor del pulso debe ser numerico';\n";
      $html .= "      frm.pulso.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.frecRespiratoria.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la frecuencia respiratoria';\n";
      $html .= "      frm.frecRespiratoria.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.frecRespiratoria.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor de la frecuencia respiratoria debe ser numerico';\n";
      $html .= "      frm.frecRespiratoria.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.temperatura.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la temperatura';\n";
      $html .= "      frm.temperatura.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.temperatura.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor de la temperatura debe ser numerico';\n";
      $html .= "      frm.temperatura.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if((frm.temperatura.value*1)>100)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor de la temperatura debe ser menor o igual a 100 grados';\n";
      $html .= "      frm.temperatura.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.peso.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el peso';\n";
      $html .= "      frm.peso.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.peso.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor del peso debe ser numerico';\n";
      $html .= "      frm.peso.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if((frm.peso.value*1)>1000)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor del peso debe ser menor o igual a 1000 Kilogramos';\n";
      $html .= "      frm.peso.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.altura.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la altura';\n";
      $html .= "      frm.altura.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.altura.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor de la altura debe ser numerico';\n";
      $html .= "      frm.altura.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if((frm.altura.value*1)>300)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor de la altura debe ser menor o igual a 300 Centimetros';\n";
      $html .= "      frm.altura.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.masaCorporal.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe calcular la masa corporal';\n";
      $html .= "      frm.calcularMC.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.grupoSanguineo.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el grupo sanguineo';\n";
      $html .= "      frm.grupoSanguineo.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.factorRH.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el factor RH';\n";
      $html .= "      frm.factorRH.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.factorRH.value==\"-\" && frm.subgrupoRH.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el subgrupo RH -';\n";
      $html .= "      frm.subgrupoRH.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeCerrartabla();

      return $html;
    }
    /**
    * Funcion donde se crea la forma para registrar las respuestas del cuestionario al
    * donante
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $preg_c vector que contiene la informacion del cuestionario
    * @param array $request vector que contiene la informacion del request
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaCuestionario($action, $preg_c, $request)
    {
      $html  = ThemeAbrirTabla('CUESTIONARIO');
      $html .= "<form name=\"formCuestionario\" id=\"formCuestionario\" action=\"".$action['ingresar_respuestas'].URLRequest(array("codDonante"=>$request['codDonante'], "noId"=>$request['noId'], "tipoId"=>$request['tipoId'], "cod_don"=>$request['cod_don'], "cod_det"=>$request['cod_det']))."\" method=\"post\">\n";
      $html .= "  <input type=\"hidden\" name=\"cod_don\" value=\"".$request['cod_don']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"90%\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"5%\">No.\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"40%\">Preguntas\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"5%\">Si\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"5%\">No\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"35%\">Detalle\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $cont = 0;
      foreach($preg_c as $indice => $valor)
      {
        $html .= "  <tr>\n";
        $html .= "    <td class=\"modulo_list_claro\" align=\"center\">".$valor['cuestionario_id']."\n";
        $html .= "      <input type=\"hidden\" name=\"cuestionarioId".$cont."\" id=\"cuestionarioId".$cont."\" value=\"".$valor['cuestionario_id']."\">\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\" align=\"left\">".$valor['pregunta']."\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\" align=\"center\">\n";
        $html .= "      <input type=\"radio\" name=\"respuesta".$cont."\" id=\"respuesta".$cont."\" value=\"SI\"></input>\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\" align=\"center\">\n";
        $html .= "      <input type=\"radio\" name=\"respuesta".$cont."\" id=\"respuesta".$cont."\" value=\"NO\"></input>\n";
        $html .= "    </td>\n";
        $html .= "    <td class=\"modulo_list_claro\" align=\"center\">\n";
        $html .= "      <textarea class=\"textarea\" rows=\"1\" name=\"detalle".$cont."\" style=\"width:100%;background:#FFFFFF\"></textarea>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $cont = $cont + 1;
      }
      $html .= "    <input type=\"hidden\" name=\"cantPreg\" value=\"".$cont."\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"5\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"ValidarDatos()\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\" colspan=\"5\">\n";
      $html .= "        <div class=\"label_error\" id=\"error\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver_signos']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<script>\n";
      $html .= "  function ValidarDatos()\n";
      $html .= "  {\n";
      //$html .= "    xajax_EvaluarRespuesta(xajax.getFormValues('formCuestionario'));\n";
      $html .= "    frm = document.formCuestionario;\n";
      $html .= "    cp=frm.cantPreg.value;\n";
      $html .= "    j = 0;\n";
      $html .= "    for(i=0; i<frm.length; i++)\n";
      $html .= "    {\n";
      $html .= "      ";
      $html .= "      if(frm[i].type == 'radio')\n";
      $html .= "      {\n";
      //$html .= "        alert(frm[i].name);\n";
      $html .= "        if(!frm[i].checked && !frm[++i].checked)\n";
      $html .= "        {\n";
      $html .= "          document.getElementById('error').innerHTML = 'Debe responder la pegunta '+document.getElementById('cuestionarioId'+j).value;\n";
      $html .= "          return;\n";
      $html .= "        }\n";
      $html .= "        i++;\n";
      $html .= "        j++;\n";
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeCerrarTabla();

      return $html;
    }
    /**
    * Funcion donde se crea la forma para el mensaje de registro de datos
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param string $mensaje cadena con el mensaje que se va a mostrar
    * @param array $request vector con la informacion del request
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaMensaje($action, $mensaje, $request)
    {
      $rpt = new GetReports();
      $mst = $rpt->GetJavaReport('app', 'BancoSangre', 'imprimirdocumento',array("noId"=>$request['noId'], "tipoId"=>$request['tipoId'], "cod_don"=>$request['cod_don'], "cod_det"=>$request['cod_det']), array('rpt_name'=>'', 'rpt_dir'=>'cache', 'rpt_rewrite'=>TRUE));
      $fnc = $rpt->GetJavaFunction();     
    
      $html  = ThemeAbrirTabla('MENSAJE');

      $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"3\">\n";
      $html .= "      <table width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "        <tr class=\"normal_10AN\">\n";
      $html .= "          <td align=\"center\">\n".$mensaje."</td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\"><br>\n";
      $html .= "      ".$mst."\n";
      $html .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir_doc\" value=\"Imprimir Doc.\" onclick=\"javascript:".$fnc."\">\n";
      $html .= "    </td>\n";
      $html .= "<form name=\"formImprimirCod\" id=\"formImprimirCod\" action=\"".$action['imprimir_codigo']."\" method=\"post\">\n";      
      $html .= "    <td align=\"center\"><br>\n";
      $html .= "      <input class=\"input-submit\" type=\"submit\" name=\"imprimir_cod\" value=\"Imprimir Cod.\">\n";
      $html .= "    </td>\n";
      $html .= "</form>\n";
      $html .= "<form name=\"formSolPruebas\" id=\"formSolPruebas\" action=\"".$action['solicitar_pruebas'].URLRequest(array("codDonante"=>$request["codDonante"], "noId"=>$request['noId'], "tipoId"=>$request['tipoId']))."\" method=\"post\">\n";
      $html .= "    <td align=\"center\"><br>\n";
      $html .= "      <input class=\"input-submit\" type=\"submit\" name=\"sol_pruebas\" value=\"Solicitar Pruebas\">\n";
      $html .= "    </td>\n";
      $html .= "</form>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";

      $html .= "<br>";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $html .= ThemeCerrarTabla();
      return $html;
    }
    
    function formaSolicitarPruebas()
    {
      $html  = ThemeAbrirTabla('SOLICITUD DE EXAMENES Y PROCEDIMIENTOS');
      $html .= "<form>\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\">SOLICITUD DE EXAMENES\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma para registrar la informacion de la donacion
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $tipos_id vector con la informacion de los tipos de identificacion
    * @return string $html retorna la cadena con el codigo html de la forma 
    */
    function formaRegistrarDonaciones($action, $tipos_id)
    {
      $html  = ThemeAbrirTabla('REGISTRAR DONACIONES');
      $html .= "<form name=\"formRegistrarDonaciones\" id=\"formRegistrarDonaciones\" method=\"post\" action=\"".$action['registrar_donacion']."\">\n";
      $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "  <tr class=\"modulo_table_title\">\n";
      $html .= "    <td align=\"center\" colspan=\"6\">REGISTRO DE DONACIONES\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $fecha = date("d/m/Y");
      $hora = date("H:i:s");
      $html .= "  <tr>\n";
      $html .= "    <td class=\"formulacion_table_list\" width=\"10%\">Fecha Actual:\n";
      $html .= "    </td>\n";
      $html .= "    <td width=\"10%\" class=\"modulo_list_claro\">".$fecha."\n";
      $html .= "      <input type=\"hidden\" name=\"fechaActual\" value=\"".$fecha."\">\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"formulacion_table_list\" width=\"10%\">Hora Actual:\n";
      $html .= "    </td>\n";
      $html .= "    <td width=\"50%\" class=\"modulo_list_claro\" colspan=\"3\">".$hora."\n";
      $html .= "      <input type=\"hidden\" name=\"horaActual\" value=\"".$hora."\">\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "  <tr class=\"modulo_table_title\">\n";
      $html .= "    <td align=\"center\" colspan=\"6\">DATOS DEL DONANTE\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td class=\"formulacion_table_list\" width=\"15%\">Cedula de Identidad:\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"modulo_list_claro\" width=\"15%\">\n";
      $html .= "      <input type=\"text\" name=\"noId\" id=\"noId\" size=\"15%\">\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"formulacion_table_list\" width=\"15%\">Tipo Identificacion:\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"modulo_list_claro\" width=\"15%\">\n";
      $html .= "      <select class=\"select\" name=\"tipoId\" id=\"tipoId\">\n";
      $html .= "        <option value=\"-1\">-- Seleccionar --</option>";
      foreach($tipos_id as $indice => $valor)
      {
        $html .= "      <option value=\"".$valor['tipo_id_paciente']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "      </select>\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"modulo_list_claro\" colspan=\"2\" width=\"20%\" align=\"center\">";
      $html .= "      <input class=\"input-submit\" type=\"button\" name=\"buscarPaciente\" value=\"Buscar\" onclick=\"BuscarPaciente()\">\n";
      $html .= "    </td>";
      $html .= "  </tr>\n";      
      $html .= "  <tr>\n";
      $html .= "    <td class=\"formulacion_table_list\">Tipo de Donador:\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"modulo_list_claro\">\n";
      $html .= "      <div id=\"divTipoDonador\">\n";
      $html .= "      </div>\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"formulacion_table_list\">convenio:\n";
      $html .= "    </td>\n";
      $html .= "    <td class=\"modulo_list_claro\" colspan=\"3\">\n";
      $html .= "      <div id=\"divConvenio\">\n";
      $html .= "      </div>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"6\" align=\"center\">\n";
      $html .= "      <div id=\"error_bp\" class=\"label_error\"></div>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "      <td colspan=\"6\" align=\"center\">\n";
      $html .= "        <div id=\"infoPaciente\"></div>\n";      
      $html .= "      </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"6\" align=\"center\">\n";
      $html .= "      <div id=\"error\" class=\"label_error\"></div>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver_menu']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<script>\n";
      $html .= "  function BuscarPaciente()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formRegistrarDonaciones;\n";
      $html .= "    if(frm.noId.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('infoPaciente').innerHTML = '';\n";
      $html .= "      document.getElementById('error_bp').innerHTML = 'Debe ingresar la cedula de identidad';\n";
      $html .= "      return;\n";
      $html .= "    }\n";      
      $html .= "    if(frm.tipoId.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('infoPaciente').innerHTML = '';\n";
      $html .= "      document.getElementById('error_bp').innerHTML = 'Debe seleccionar el tipo de identificacion';\n";
      $html .= "      return;\n";
      $html .= "    }else{";
      $html .= "      document.getElementById('error_bp').innerHTML = '';\n";
      $html .= "      xajax_BuscarFichaDonante(xajax.getFormValues(formRegistrarDonaciones));\n";
      $html .= "    }\n";      
      $html .= "  }\n";
      $html .= "  function ValidarTipoBolsa()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formRegistrarDonaciones;\n";
      $html .= "    var indice = frm.tipoBolsa.selectedIndex;\n";
      $html .= "    if(frm.tipoBolsa.options[indice].text==\"OTROS\")\n";
      $html .= "    {\n";
      $html .= "      frm.otros.disabled = false;\n";
      $html .= "      return;\n";
      $html .= "    }else{\n";
      $html .= "      frm.otros.disabled = true;\n";
      $html .= "      frm.otros.value = '';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function ValidarEstadoDonacion()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formRegistrarDonaciones;\n";
      $html .= "    var indice = frm.estadoDonacion.selectedIndex;\n";
      $html .= "    if(frm.estadoDonacion.value!=\"-1\")\n";
      $html .= "    {\n";
      $html .= "      if(frm.estadoDonacion.options[indice].text==\"Diferido\")\n";
      $html .= "      {\n";
      $html .= "        frm.cantTiempo.disabled = false;\n";
      $html .= "        frm.unidTiempo.disabled = false;\n";
      $html .= "        xajax_BuscarEstadoCausa(xajax.getFormValues(formRegistrarDonaciones));\n";
      $html .= "      }else{\n";
      $html .= "        frm.cantTiempo.value = '';\n";
      $html .= "        frm.unidTiempo.value = '-1';\n";
      $html .= "        frm.cantTiempo.disabled = true;\n";
      $html .= "        frm.unidTiempo.disabled = true;\n";
      $html .= "    xajax_BuscarEstadoCausa(xajax.getFormValues(formRegistrarDonaciones));\n";
      $html .= "      }";  
      $html .= "    }else{\n";
      $html .= "      frm.cantTiempo.value = '';\n";
      $html .= "      frm.unidTiempo.value = '-1';\n";
      $html .= "      frm.cantTiempo.disabled = true;\n";
      $html .= "      frm.unidTiempo.disabled = true;\n";
      $html .= "      document.getElementById('divCausas').innerHTML = '<input type=\"hidden\" name=\"causas\" value=\"-1\">';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function ValidarDatos()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formRegistrarDonaciones;\n";
      $html .= "    if(frm.tipoBolsa.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el Tipo de Bolsa';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    var indice = frm.tipoBolsa.selectedIndex;\n";
      $html .= "    if(frm.tipoBolsa.options[indice].text==\"OTROS\" && frm.otros.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el campo Otros para el Tipo de Bolsa';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.estadoDonacion.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el Estado de la Donacion';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    var indice = frm.estadoDonacion.selectedIndex;\n";
      $html .= "    if(frm.causas.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar la causa del Estado de Donacion';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.estadoDonacion.options[indice].text==\"Diferido\" && frm.cantTiempo.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la Cantidad de Tiempo';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.estadoDonacion.options[indice].text==\"Diferido\" && frm.unidTiempo.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar la Unidad de Tiempo';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    i=0;\n";
      $html .= "    if(!frm.aspecto[i].checked && !frm.aspecto[++i].checked)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar una opcion en el area Aspecto general del donante sano';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    i=0;\n";
      $html .= "    if(!frm.brazosLesion[i].checked && !frm.brazosLesion[++i].checked)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar una opcion en el area Brazos sin lesion de agujas';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    i=0;\n";
      $html .= "    if(!frm.actividad[i].checked && !frm.actividad[++i].checked)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar una opcion en el area Actividad peligrosa post donacion';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    i=0;\n";
      $html .= "    if(!frm.flebotomia[i].checked && !frm.flebotomia[++i].checked)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar una opcion en el area Flebotomia del brazo';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    i=0;\n";
      $html .= "    if(!frm.puncion[i].checked && !frm.puncion[++i].checked)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar una opcion en el area puncion';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma para el mensaje de registro de datos
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param string $mensaje cadena con el mensaje que se va a mostrar
    * @param array $request vector con la informacion del request
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaMensajeIngresoRegistro($action, $mensaje, $request)
    {
      $rpt = new GetReports();
      $mst = $rpt->GetJavaReport('app', 'BancoSangre', 'docIngresoRegistro',array("noId"=>$request['noId'], "tipoId"=>$request['tipoId'], "cod_don"=>$request['codDonante'], "detRegDon"=>$request['detRegDon']), array('rpt_name'=>'', 'rpt_dir'=>'cache', 'rpt_rewrite'=>TRUE));
      $fnc = $rpt->GetJavaFunction();     
    
      $html  = ThemeAbrirTabla('MENSAJE');

      $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"2\">\n";
      $html .= "      <table width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "        <tr class=\"normal_10AN\">\n";
      $html .= "          <td align=\"center\">\n".$mensaje."</td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"2\"><br>\n";
      $html .= "      ".$mst."\n";
      $html .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir_doc\" value=\"Imprimir Doc.\" onclick=\"javascript:".$fnc."\">\n";
      $html .= "    </td>\n";
      /*$html .= "<form name=\"formImprimirCod\" id=\"formImprimirCod\" action=\"".$action['imprimir_codigo']."\" method=\"post\">\n";      
      $html .= "    <td align=\"center\"><br>\n";
      $html .= "      <input class=\"input-submit\" type=\"submit\" name=\"imprimir_cod\" value=\"Imprimir Cod.\">\n";
      $html .= "    </td>\n";
      $html .= "</form>\n";*/
      $html .= "  </tr>\n";
      $html .= "</table>\n";

      $html .= "<br>";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se crea la forma para consultar la informacion de los registro de
    * donacion
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $tipos_id vector con los datos de los tipos de identificacion
    * @param array $grupos_sang vector con los datos de los grupos sanguineos
    * @param array $rh vector con los datos del rh
    * @param array $subgrupo_rh vector con los datos del subgrupo y el rh
    * @param array $request vector con la informacion del request
    * @param array $datos_donacion vector con los datos del registro de donacion
    * consultado
    * @param stirng $pagina cadena con el numero de la pagina que se esta visualizando
    * @param string $conteo cadena con la cantidad de datos total
    * @return string $html retorna la cadena con el codigo html de la pagina 
    */
    function formaConsultaDonantes($action, $tipos_id, $grupos_sang, $rh, $subgrupo_rh, $request, $datos_donacion, $pagina, $conteo)
    {
      $rpt = new GetReports();      
    
      $html  = ThemeAbrirTabla('CONSULTA DE DONACIONES');
      $html .= "<form name=\"formConsultarDonantes\" id=\"formConsultarDonantes\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"4\">CRITERIOS DE BUSQUEDA\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"normal_10AN\" align=\"left\" width=\"15%\">Cedula de Identidad:\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"20%\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"cedula\" value=\"".$request['cedula']."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"normal_10AN\" width=\"15%\">Tipo Identificacion:\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"20%\">\n";
      $html .= "        <select class=\"select\" name=\"tipoId\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($tipos_id as $indice => $valor)
      {
        if($request['tipoId']==$valor['tipo_id_paciente'])
          $sel_ti = "selected";
        else
          $sel_ti = "";
        $html .= "        <option value=\"".$valor['tipo_id_paciente']."\" ".$sel_ti.">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"normal_10AN\" align=\"left\">Grupo Sanguineo:\n";
      $html .= "      </td>\n";
      $html .= "      <td>\n";
      $html .= "        <select class=\"select\" name=\"grupoSanguineo\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($grupos_sang as $indice => $valor)
      {
        if($request['grupoSanguineo']==$valor['grupo_sanguineo'])
          $sel_gs = "selected";
        else
          $sel_gs = "";
        $html .= "        <option value=\"".$valor['grupo_sanguineo']."\" ".$sel_gs.">".$valor['grupo_sanguineo']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"normal_10AN\" align=\"left\">Factor RH:\n";
      $html .= "      </td>\n";
      $html .= "      <td>\n";
      $html .= "        <select class=\"select\" name=\"factorRH\" onclick=\"ValidarFactorRH()\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($rh as $indice => $valor)
      {
        if($request['factorRH']==$valor['rh'])
          $sel_rh = "selected";
        else
          $sel_rh = "";
        $html .= "        <option value=\"".$valor['rh']."\" ".$sel_rh.">".$valor['rh']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"normal_10AN\" align=\"left\">Subgrupo RH-:\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"3\">\n";
      if(!$request['subgrupoRH'])
        $dis = "disabled";
      $html .= "        <select class=\"select\" name=\"subgrupoRH\" ".$dis.">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($subgrupo_rh as $indice => $valor)
      {
        if($request['subgrupoRH']==$valor['subgrupo_rh']."/".$valor['rh'])
          $sel_srh = "selected";
        else
          $sel_srh = "";
        $html .= "        <option value=\"".$valor['subgrupo_rh']."/".$valor['rh']."\" ".$sel_srh.">".$valor['subgrupo_rh'].$valor['rh']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $cut = new ClaseUtil();
      $html .= $cut->AcceptDate("/");
      $html .= $cut->IsDate();
      $html .= $cut->LimpiarCampos();
      $html .= "    <tr>\n";
      $html .= "      <td class=\"normal_10AN\" align=\"left\">Fecha Inicio:\n";
      $html .= "      </td>\n";
      $html .= "      <td>\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"fechaInicio\" value=\"".$request['fechaInicio']."\" onkeyPress=\"return acceptDate(event)\" size=\"10%\">\n";
      $html .= "".ReturnOpenCalendario('formConsultarDonantes', 'fechaInicio', '/')."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"normal_10AN\" align=\"left\">Fecha Fin:\n";
      $html .= "      </td>\n";
      $html .= "      <td>\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"fechaFin\" value=\"".$request['fechaFin']."\" onkeypress=\"return acceptDate(event)\" size=\"10%\">\n";
      $html .= "".ReturnOpenCalendario('formConsultarDonantes', 'fechaFin', '/')."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"2\" align=\"center\">\n";
      $html .= "        <input type=\"hidden\" name=\"sw_oculto\" id=\"sw_oculto\" value=\"consultar\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscar\" value=\"Buscar\" onclick=\"ValidarDatos()\">\n";
      $html .= "      </td>\n";      
      $html .= "      <td colspan=\"2\" align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"limpiar\" value=\"Limpiar\" onclick=\"LimpiarCamp()\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";     
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"4\" align=\"center\">\n";
      $html .= "        <div id=\"error\" class=\"label_error\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<br>\n";
      $path = GetThemePath();
      $html .= "<div id=\"div_busqueda\">\n";
      if(!empty($datos_donacion))
      {        
        $html .= "<table align=\"center\" class=\"modulo_table_list\" width=\"80%\">\n";
        $html .= "  <tr class=\"modulo_table_title\">\n";
        $html .= "    <td align=\"center\" colspan=\"9\">CONSULTA CUENTAS\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td align=\"center\">FECHA DONACION\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">NOMBRES Y APELLIDOS\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">EDAD\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">SEXO\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">GRUPO SANGUINEO\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">RH\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">SUBGRUPO RH-\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">ESTADO DONACION\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">DETALLE\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $est = "modulo_list_claro";
        foreach($datos_donacion as $indice => $valor)
        {
          $mst = $rpt->GetJavaReport('app', 'BancoSangre', 'docIngresoRegistro', array("noId"=>$valor['donante_id'], "tipoId"=>$valor['tipo_id_donante'], "cod_don"=>$valor['codigo_donante'], "detRegDon"=>$valor['det_registro_donacion_id']), array('rpt_name'=>'', 'rpt_dir'=>'cache', 'rpt_rewrite'=>TRUE));
          $fnc = $rpt->GetJavaFunction();
          if($valor['fecha_registro'])
          {
            $fr = explode('-',$valor['fecha_registro']);
            $fReg = $fr[2].'/'.$fr[1].'/'.$fr[0];
          }
          ($est == "modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
          $html .= "  <tr class=".$est.">\n";
          $html .= "    <td align=\"center\">".$fReg."\n";
          $html .= "    </td>\n";
          $html .= "    <td>".$valor['primer_nombre']." ".$valor['segundo_nombre']." ".$valor['primer_apellido']." ".$valor['segundo_apellido']."\n";
          $html .= "    </td>\n";
          $html .= "    <td align=\"center\">".$valor['edad']."\n";
          $html .= "    </td>\n";
          $html .= "    <td align=\"center\">".$valor['sexo_id']."\n";
          $html .= "    </td>\n";
          $html .= "    <td align=\"center\">".$valor['grupo_sanguineo']."\n";
          $html .= "    </td>\n";
          $html .= "    <td align=\"center\">".$valor['rh_gs']."\n";
          $html .= "    </td>\n";
          $html .= "    <td align=\"center\">".$valor['subgrupo_rh']." ".$valor['rh_sg']."\n";
          $html .= "    </td>\n";
          $html .= "    <td align=\"center\">".$valor['descripcion']."\n";
          $html .= "    </td>\n";
          $html .= "    <td>\n";
          $html .= "      <center>\n";
          $html .= "        ".$mst."\n";
          $html .= "        <a href=\"javascript:".$fnc."\" class=\"label_error\"><img src=\"".$path."/images/informacion.png\" border=\"0\" title=\"DETALLE REGISTRO DONACION\"></a>\n";
          $html .= "      </center>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "  <tr>\n";
        $html .= "    <td colspan=\"9\" align=\"center\">\n";
        $chtml = AutoCarga::factory('ClaseHTML');
        $html .= "    ".$chtml->ObtenerPaginado($conteo, $pagina, $action['paginador'], 50);
        $html .= "    </td>\n";
        $html .= "  </tr>\n";        
        $html .= "</table>\n";        
      }else if($request['sw_oculto']=="consultar"){
        $html .= "<p align=\"center\"><font color=\"red\">NO EXISTEN REGISTROS</font></p>\n";
      }
      $html .= "</div>\n";
      $html .= "<script>\n";
      $html .= "  function ValidarFactorRH()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formConsultarDonantes;\n";
      $html .= "    if(frm.factorRH.value==\"-\")\n";
      $html .= "    {\n";
      $html .= "      frm.subgrupoRH.disabled = false;\n";
      $html .= "    }else{\n";
      $html .= "      frm.subgrupoRH.disabled = true;";
      $html .= "      frm.subgrupoRH.value = -1;";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function ValidarDatos()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formConsultarDonantes;\n";
      $html .= "    fi = frm.fechaInicio.value;\n";
      $html .= "    ff = frm.fechaFin.value;\n";
      $html .= "    var fecha_i = fi.split('/');\n";
      $html .= "    var fecha_f = ff.split('/');\n";
      $html .= "    ffi = new Date(fecha_i[2]+'/'+fecha_i[1]+'/'+fecha_i[0]);\n";
      $html .= "    fff = new Date(fecha_f[2]+'/'+fecha_f[1]+'/'+fecha_f[0]);\n";
      $html .= "    ffs = new Date();\n";
      $html .= "    if(frm.cedula.value!=\"\" && !IsNumeric(frm.cedula.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La cedula debe ser un valor numerico';\n";
      $html .= "      document.getElementById('div_busqueda').innerHTML = '';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.fechaInicio.value!=\"\" && !IsDate(frm.fechaInicio.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El formato de la fecha inicial es invalido';\n";
      $html .= "      document.getElementById('div_busqueda').innerHTML = '';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.fechaFin.value!=\"\" && !IsDate(frm.fechaFin.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El formato de la fecha Final es invalido';\n";
      $html .= "      document.getElementById('div_busqueda').innerHTML = '';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "  function LimpiarCamp()\n";
      $html .= "  {\n";
      $html .= "    LimpiarCampos(document.formConsultarDonantes);\n";
      $html .= "    return;\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <a href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * Funcion donde se crea la forma para consutar la informacion del Fraccionamiento de 
    * sangre
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $tipos_id vector con la informacion de los tipos de identificacion
    * @return string $html retorna la cadena con el codigo html de la pagina
    */    
    function formaFracSangre($action, $tipos_id)
    {
      $html  = ThemeAbrirTabla('FRACCIONAMIENTO DE SANGRE');
      $html .= "<form name=\"formFracSangre\" id=\"formFracSangre\" action=\"".$action['registrar_frac']."\" method=\"post\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\">FRACCIONAMIENTO DE SANGRE\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.6%\">Cod. Donante:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"33.2%\">\n";
      $html .= "        <input type=\"text\" name=\"codDon\" id=\"codDon\" size=\"15%\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscarPaciente\" value=\"Buscar\" onclick=\"BuscarPaciente()\">\n";
      $html .= "      </td>\n";
      $cut = new ClaseUtil();
      $html .= $cut->AcceptDate("/");
      $html .= $cut->IsDate();
      $html .= $cut->AcceptNum(false);
      $html .= "      <td class=\"formulacion_table_list\" width=\"16.6%\">Fecha Extraccion:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"33.2%\" colspan=\"2\">\n";
      $html .= "        <div id=\"div_fechaExtraccion\">\n";
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"15%\">Tipo de Donador:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" width=\"25%\">\n";
      $html .= "        <div id=\"divTipoDonador\">\n";
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"15%\">convenio:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"3\" width=\"25%\">\n";
      $html .= "        <div id=\"divConvenio\">\n";
      $html .= "        </div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"6\" align=\"center\">\n";
      $html .= "        <div id=\"error_bp\" class=\"label_error\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "        <td colspan=\"6\" align=\"center\">\n";
      $html .= "          <div id=\"infoPaciente\"></div>\n";      
      $html .= "        </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"6\" align=\"center\">\n";
      $html .= "        <div id=\"error\" class=\"label_error\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "<br>\n";
      $html .= "  <table align=\"center\">\n";
      $html .= "    <tr align=\"center\">\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <a href=\"".$action['volver_menu']."\">VOLVER</a>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";

      $html .= "<script>\n";
      $html .= "  function BuscarPaciente()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formFracSangre;\n";
      $html .= "    if(frm.codDon.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('infoPaciente').innerHTML = '';\n";
      $html .= "      document.getElementById('error_bp').innerHTML = 'Debe ingresar el codigo del donante';\n";
      $html .= "      return;\n";
      $html .= "    }else{\n";
      $html .= "      document.getElementById('error_bp').innerHTML = '';\n";
      $html .= "      xajax_BuscarFichaFrac(xajax.getFormValues(formFracSangre));\n";
      $html .= "    }\n";      
      $html .= "  }\n";
      $html .= "  function ValidarDatos()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formFracSangre;\n";
      $html .= "    i=0;\n";
      $html .= "    if(!frm.leucorreducidos[i].checked && !frm.leucorreducidos[++i].checked)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar una opcion en el area Leucorreducidos';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    i=0;\n";
      $html .= "    if(!frm.irradiados[i].checked && !frm.irradiados[++i].checked)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar una opcion en el area Irradiados';\n";
      $html .= "      return;\n";
      $html .= "    }\n";      
      $html .= "    if(frm.fechaCaducidad.value==\"\")";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la Fecha de Caducidad';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsDate(frm.fechaCaducidad.value))";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El formato de la fecha es invalido';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    ff = frm.fechaFrac.value;\n";
      $html .= "    fc = frm.fechaCaducidad.value;\n";
      $html .= "    var fecha_f = ff.split('/');\n";
      $html .= "    var fecha_c = fc.split('/');\n";
      $html .= "    fff = new Date(fecha_f[2]+'/'+fecha_f[1]+'/'+fecha_f[0]);\n";
      $html .= "    ffc = new Date(fecha_c[2]+'/'+fecha_c[1]+'/'+fecha_c[0]);\n";
      $html .= "    if(ffc <= fff)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La Fecha de Caducidad debe ser mayor a la Fecha Actual';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.tipoProducto.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el tipo de producto';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.cantidad.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la cantidad';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.cantidad.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La cantidad debe ser numerica';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.responsable.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el responsable';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<script language=\"javascript\">\n";
      $html .= "  var vFormCamp;\n";
      $html .= "  function Mostrar_Campo(vFormaCampo, cadCampo)\n";
      $html .= "  {\n";
      $html .= "    var dia='';\n";
      $html .= "    var mes='';\n";
      $html .= "    var anyo='';\n";
      $html .= "    var valor='';\n";
      $html .= "    try{\n";
      $html .= "      vFormCamp=vFormaCampo;\n";
      $html .= "      valor=vFormaCampo.value;\n";
      $html .= "    }catch(error){}\n";
      $html .= "    if(valor.length==10)\n";
      $html .= "    {\n";
      $html .= "      dia=valor.split('/')[0];\n";
      $html .= "      mes=parseInt(valor.split('/')[1])-1;\n";
      $html .= "      anyo=valor.split('/')[2];\n";
      $html .= "    }\n";
      $html .= "    CrearCalendario('Campo','/',dia,mes,anyo);\n";
      $html .= "  }\n";
      $html .= "  function Ocultar_Campo(fecha)\n";
      $html .= "  {\n";
      $html .= "    if(fecha!='')\n";
      $html .= "    {\n";
      $html .= "      vFormCamp.value=fecha;\n";
      $html .= "    }\n";      
      $html .= "    document.getElementById('calendario_pxCampo').style.visibility = 'hidden';\n";
      $html .= "  }\n";
      $html .= "  function Ocupaciones(forma, prefijo)\n";
      $html .= "  {\n";
      $html .= "    var url='reports/HTML/ocupaciones.php?forma=' + forma +'&prefijo=' + prefijo;\n";
      $html .= "window.open(url,'','width=600,height=500,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no');\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    * @param array $action vector que contiene los link de la aplicacion
    * @param string $mensaje cadena con el mensaje que se va a mostrar
    * @param array $request vector con la informacion del request
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function formaMensajeIngresoFrac($action, $mensaje, $request)
    {
      $rpt = new GetReports();
      $mst = $rpt->GetJavaReport('app', 'BancoSangre', 'docIngresoFraccionamiento',array("noId"=>$request['noId'], "tipoId"=>$request['tipoId'], "cod_don"=>$request['codDonante'], "det_frac"=>$request['det_frac']), array('rpt_name'=>'', 'rpt_dir'=>'cache', 'rpt_rewrite'=>TRUE));
      $fnc = $rpt->GetJavaFunction();
    
      $html  = ThemeAbrirTabla('MENSAJE');

      $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"2\">\n";
      $html .= "      <table width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "        <tr class=\"normal_10AN\">\n";
      $html .= "          <td align=\"center\">\n".$mensaje."</td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"2\"><br>\n";
      $html .= "      ".$mst."\n";
      $html .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir_doc\" value=\"Imprimir Doc.\" onclick=\"javascript:".$fnc."\">\n";
      $html .= "    </td>\n";
      /*$html .= "<form name=\"formImprimirCod\" id=\"formImprimirCod\" action=\"".$action['imprimir_codigo']."\" method=\"post\">\n";      
      $html .= "    <td align=\"center\"><br>\n";
      $html .= "      <input class=\"input-submit\" type=\"submit\" name=\"imprimir_cod\" value=\"Imprimir Cod.\">\n";
      $html .= "    </td>\n";
      $html .= "</form>\n";*/
      $html .= "  </tr>\n";
      $html .= "</table>\n";

      $html .= "<br>";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    * Funcion donde se crea la forma para consultar la informacion de los fraccionamientos 
    * de sangre
    *
    * @param array $action vector que contiene los link de la aplicacion
    * @param array $request vector con la informacion del request
    * @param array $tipoProd vector con la informacion de los tipos de productos de 
    * fraccionamiento de sangre
    * @param array $datos_frac vector con la informacion de los fraccionamientos de sangre
    * @param string $pagina cadena con el numero de la pagina que se esta visualizando
    * @param string $conteo cadena con la cantidad de datos total
    * @return string $html retorna la cadena con el codigo html de la pagina 
    */
    function formaConsultaFracSangre($action, $request, $tipoProd, $datos_frac, $pagina, $conteo)
    {
      $rpt = new GetReports();
      $html  = ThemeAbrirTabla('CONSULTA FRACCIONAMIENTO DE SANGRE');
      $html .= "<form name=\"formConsultarFracSang\" id=\"formConsultarFracSang\" action=\"".$action['buscar_frac']."\" method=\"post\">\n";
      $html .= "  <table class=\"modulo_table_list\" width=\"70%\" align=\"center\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td align=\"center\" colspan=\"4\">CRITERIOS DE BUSQUEDA\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"normal_10AN\" align=\"15%\">Tipo de Producto:\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"3\">\n";
      $html .= "        <select class=\"select\" name=\"tipoProducto\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($tipoProd as $indice => $valor)
      {
        if($request['tipoProducto']==$valor['tipo_producto_frac_id'])
          $sel_tp = "selected";
        else
          $sel_tp = "";
        $html .= "        <option value=\"".$valor['tipo_producto_frac_id']."\" ".$sel_tp.">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $cut = new ClaseUtil();
      $html .= $cut->AcceptDate("/");
      $html .= $cut->IsDate();
      $html .= $cut->LimpiarCampos();       
      $html .= "    <tr>\n";
      $html .= "      <td class=\"normal_10AN\" align=\"left\">Fecha Inicio:\n";
      $html .= "      </td>\n";
      $html .= "      <td>\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"fechaInicio\" value=\"".$request['fechaInicio']."\" onkeyPress=\"return acceptDate(event)\" size=\"10%\">\n";
      $html .= "".ReturnOpenCalendario('formConsultarFracSang', 'fechaInicio', '/')."\n";   
      $html .= "      </td>\n";
      $html .= "      <td class=\"normal_10AN\" align=\"left\">Fecha Fin:\n";
      $html .= "      </td>\n";
      $html .= "      <td>\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"fechaFin\" value=\"".$request['fechaFin']."\" onkeyPress=\"return acceptDate(event)\" size=\"10%\">\n";
      $html .= "".ReturnOpenCalendario('formConsultarFracSang', 'fechaFin', '/')."\n";   
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"2\" align=\"center\">\n";
      $html .= "        <input type=\"hidden\" name=\"sw_oculto\" id=\"sw_oculto\" value=\"consultar\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"buscar\" value=\"Buscar\" onclick=\"ValidarDatos()\">\n";
      $html .= "      </td>\n";      
      $html .= "      <td colspan=\"2\" align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"limpiar\" value=\"Limpiar\" onclick=\"LimpiarCamp()\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"4\" align=\"center\">\n";
      $html .= "        <div id=\"error\" class=\"label_error\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<br>\n";
      $path = GetThemePath();
      $html .= "<div id=\"div_busqueda\">\n";
      if(!empty($datos_frac))
      {
        $html .= "<table align=\"center\" class=\"modulo_table_list\" width=\"80%\">\n";
        $html .= "  <tr class=\"modulo_table_title\">\n";
        $html .= "    <td align=\"center\" colspan=\"10\">CONSULTA FRACCIONAMIENTO DE SANGRE\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        //if($valor['fecha_hora_frac'])
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td align=\"center\">F. EXTR\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">F. FRACC\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">F. CADUC\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">COD.DONA\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">LEUCORREDUCIDOS\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">IRRADIADOS\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">CANTIDAD\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">OBSERVACION\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">RESPONSABLE\n";
        $html .= "    </td>\n";
        $html .= "    <td align=\"center\">OPCION\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $est = "modulo_list_claro";
        foreach($datos_frac as $indice => $valor)
        {
          $mst = $rpt->GetJavaReport('app', 'BancoSangre', 'docIngresoFraccionamiento', array("noId"=>$valor['donante_id'], "tipoId"=>$valor['tipo_id_donante'], "cod_don"=>$valor['codigo_donante'], "det_frac"=>$valor['det_frac_sangre_id']), array('rpt_name'=>'', 'rpt_dir'=>'cache', 'rpt_rewrite'=>TRUE));
          $fnc = $rpt->GetJavaFunction();
          
          ($est == "modulo_list_claro")? $est="modulo_list_oscuro":$est="modulo_list_claro";
          $html .= "<tr class=".$est.">\n";
          $html .= "  <td align=\"center\">".$valor['fecha_hf']."\n";
          $html .= "  </td>\n";
          $html .= "  <td align=\"center\">".$valor['fecha_hf']."\n";
          $html .= "  </td>\n";
          $html .= "  <td align=\"center\">".$valor['fecha_c']."-".$valor['det_frac_sangre_id']."\n";
          $html .= "  </td>\n";
          $html .= "  <td align=\"center\">".$valor['codigo_donante']."-".$valor['tipo_id_donante']."-".$valor['donante_id']."\n";
          $html .= "  </td>\n";
          $html .= "  <td align=\"center\">".$valor['leucorreducidos']."\n";
          $html .= "  </td>\n";
          $html .= "  <td align=\"center\">".$valor['irradiados']."\n";
          $html .= "  </td>\n";
          $html .= "  <td align=\"center\">".$valor['cantidad']."\n";
          $html .= "  </td>\n";
          $html .= "  <td align=\"center\">".$valor['observacion']."\n";
          $html .= "  </td>\n";
          $html .= "  <td align=\"center\">".$valor['responsable_id']."\n";
          $html .= "  </td>\n";
          $html .= "  <td align=\"center\">\n";
          $html .= "    <center>\n";
          $html .= "      ".$mst."\n";
          $html .= "      <a href=\"javascript:".$fnc."\" class=\"label_error\"><img src=\"".$path."/images/informacion.png\" border=\"0\" title=\"DETALLE FRACCIONAMIENTO\"></a>\n";
          $html .= "    </center>\n";
          $html .= "  </td>\n";
          $html .= "</tr>\n";
        }
        $html .= "  <tr>\n";
        $html .= "    <td colspan=\"10\" align=\"center\">\n";
        $chtml = AutoCarga::factory('ClaseHTML');
        $html .= "  ".$chtml->ObtenerPaginado($conteo, $pagina, $action['paginador'], 50);
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
      }else if($request['sw_oculto']=="consultar"){
        $html .= "<p align=\"center\"><font color=\"red\">NO EXISTEN REGISTROS</font></p>\n";
      }
      $html .= "</div>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <a href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<script>\n";
      $html .= "  function ValidarDatos()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formConsultarFracSang;\n";
      $html .= "    if(frm.fechaInicio.value!=\"\" && !IsDate(frm.fechaInicio.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El formato de la fecha inicial es invalido';\n";
      $html .= "      document.getElementById('div_busqueda').innerHTML = '';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.fechaFin.value!=\"\" && !IsDate(frm.fechaFin.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El formato de la fecha Final es invalido';\n";
      $html .= "      document.getElementById('div_busqueda').innerHTML = '';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "  function LimpiarCamp()\n";
      $html .= "  {\n";
      $html .= "    LimpiarCampos(document.formConsultarFracSang);\n";
      $html .= "    return;\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    function formaIngresoHemocomponentes($action, $procedencias, $grupos_sang, $rh, $subgrupo_rh, $tipoProd, $responsables)
    {
      $html  = ThemeAbrirTabla('REGISTRO DE HEMOCOMPONENTES QUE INGRESAN DE OTROS BANCOS');
      $html .= "<form name=\"formIngHemocomponentes\" id=\"formIngHemocomponentes\" method=\"post\" action=\"".$action['registrar_hemocom']."\">\n";
      $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">DATOS DE PROCEDENCIA\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $fecha = date("d/m/Y");
      $hora = date("H:i:s");
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"15%\">Fecha Actual:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\"  width=\"25%\" colspan=\"2\">".$fecha."\n";
      $html .= "        <input type=\"hidden\" name=\"fechaActual\" value=\"".$fecha."\">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"15%\">Hora Actual:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\"  width=\"25%\" colspan=\"2\">".$hora."\n";
      $html .= "        <input type=\"hidden\" name=\"horaActual\" value=\"".$hora."\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $cut = new ClaseUtil();
      $html .= $cut->AcceptDate("/");
      $html .= $cut->IsDate();
      $html .= $cut->LimpiarCampos();
      $html .= $cut->AcceptNum(false);
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Fecha de Extraccion:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"fechaExtraccion\" size=\"10%\" onkeyPress=\"return acceptDate(event)\">\n";
      $html .= "".ReturnOpenCalendario('formIngHemocomponentes', 'fechaExtraccion', '/')."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Procedencia:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <select class=\"select\" name=\"procedencia\" onchange=\"ValidarProcedencia()\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($procedencias as $indice => $valor)
      {  
        $html .= "        <option value=\"".$valor['procedencia_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";      
      $html .= "      <td class=\"formulacion_table_list\">Otros:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"otros\" size=\"30%\" maxlength=\"50\" disabled>\n";
      $html .= "      </td>\n";      
      $html .= "      <td class=\"formulacion_table_list\">Cod. Procedencia:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"codProcedencia\" size=\"15%\" maxlength=\"10\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_table_title\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">REGISTRO DE HEMOCOMPONENTES\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Grupo Sanguineo:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"grupoSanguineo\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($grupos_sang as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['grupo_sanguineo']."\">".$valor['grupo_sanguineo']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Factor RH:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"factorRH\" onchange=\"ValidarFactorRH()\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($rh as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['rh']."\">".$valor['rh']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Subgrupo RH-:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\">\n";
      $html .= "        <select class=\"select\" name=\"subgrupoRH\" disabled>\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($subgrupo_rh as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['subgrupo_rh']."/".$valor['rh']."\">".$valor['subgrupo_rh'].$valor['rh']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Fecha Caducidad:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"fechaCaducidad\" size=\"10%\" onkeyPress=\"return acceptDate(event)\">\n";
      $html .= "".ReturnOpenCalendario('formIngHemocomponentes', 'fechaCaducidad', '/')."\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Temperatura:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"temperatura\" size=\"10%\" maxlength=\"6\"> ºC\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td class=\"formulacion_table_list\">Tipo Producto:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <select class=\"select\" name=\"tipoProducto\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($tipoProd as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['tipo_producto_frac_id']."\">".$valor['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"formulacion_table_list\">Cantidad:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <input class=\"input-text\" type=\"text\" name=\"cantidad\" size=\"10%\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\"> ml\n";
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
      $html .= "      <td class=\"formulacion_table_list\">Profesional Responsable:\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" colspan=\"5\">\n";
      $html .= "        <select class=\"select\" name=\"responsable\">\n";
      $html .= "          <option value=\"-1\">-- Seleccionar --</option>\n";
      foreach($responsables as $indice => $valor)
      {
        $html .= "        <option value=\"".$valor['tercero_id']."/".$valor['tipo_id_tercero']."\">".$valor['nombre']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"3\" align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Aceptar\" onclick=\"ValidarDatos()\">\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"3\" align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"limpiar\" value=\"Limpiar\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"6\" align=\"center\">\n";
      $html .= "        <div id=\"error\" class=\"label_error\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver_menu']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $html .= "<script>\n";
      $html .= "  function ValidarProcedencia()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formIngHemocomponentes;\n";
      $html .= "    var indice = frm.procedencia.selectedIndex;\n";
      $html .= "    if(frm.procedencia.options[indice].text==\"Otros\")\n";
      $html .= "    {\n";
      $html .= "      frm.otros.disabled=false;\n";
      $html .= "      return;\n";
      $html .= "    }else{\n";
      $html .= "      frm.otros.disabled = true;\n";
      $html .= "      frm.otros.value = '';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function ValidarFactorRH()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formIngHemocomponentes;\n";
      $html .= "    if(frm.factorRH.value==\"-\")\n";
      $html .= "    {\n";
      $html .= "      frm.subgrupoRH.disabled = false;\n";
      $html .= "    }else{\n";
      $html .= "      frm.subgrupoRH.disabled = true;\n";
      $html .= "      frm.subgrupoRH.value = -1;\n";
      $html .= "    }\n";
      $html .= "  }\n";      
      $html .= "  function ValidarDatos()\n";
      $html .= "  {\n";
      $html .= "    frm = document.formIngHemocomponentes;\n";
      $html .= "    fe = frm.fechaExtraccion.value;\n";
      $html .= "    fa = frm.fechaActual.value;\n";
      $html .= "    fc = frm.fechaCaducidad.value;\n";
      $html .= "    var fecha_e = fe.split('/');\n";
      $html .= "    var fecha_a = fa.split('/');\n";
      $html .= "    var fecha_c = fc.split('/');\n";
      $html .= "    ffe = new Date(fecha_e[2]+'/'+fecha_e[1]+'/'+fecha_e[0]);\n";
      $html .= "    ffa = new Date(fecha_a[2]+'/'+fecha_a[1]+'/'+fecha_a[0]);\n";
      $html .= "    ffc = new Date(fecha_c[2]+'/'+fecha_c[1]+'/'+fecha_c[0]);\n";
      $html .= "    if(frm.fechaExtraccion.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la Fecha de Extraccion';\n";
      $html .= "      frm.fechaExtraccion.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.fechaExtraccion.value!=\"\" && !IsDate(frm.fechaExtraccion.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El formato de la Fecha de Extraccion es invalido';\n";
      $html .= "      frm.fechaExtraccion.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(fe > fa)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La Fecha de Extraccion debe ser menor o igual a la fecha Actual';\n";
      $html .= "      frm.fechaExtraccion.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.procedencia.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar la Procedencia';\n";
      $html .= "      frm.procedencia.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    var indice = frm.procedencia.selectedIndex;\n";
      $html .= "    if(frm.procedencia.options[indice].text==\"Otros\" && frm.otros.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el campo Otros';\n";
      $html .= "      frm.otros.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.codProcedencia.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar el Codigo de Procedencia';\n";
      $html .= "      frm.codProcedencia.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.grupoSanguineo.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el grupo sanguineo';\n";
      $html .= "      frm.grupoSanguineo.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.factorRH.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el Factor RH';\n";
      $html .= "      frm.factorRH.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.factorRH.value==\"-\" && frm.subgrupoRH.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el Subgrupo RH -';\n";
      $html .= "      frm.subgrupoRH.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.fechaCaducidad.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la Fecha de Caducidad';\n";
      $html .= "      frm.fechaCaducidad.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.fechaCaducidad.value!=\"\" && !IsDate(frm.fechaCaducidad.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El formato de la Fecha de Caducidad es invalido';\n";
      $html .= "      frm.fechaCaducidad.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(ffc <= ffa)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La Fecha de Caducidad debe ser mayor a la Fecha Actual';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.temperatura.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la Temperatura';\n";
      $html .= "      frm.temperatura.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.temperatura.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor de la Temperatura debe ser numerico';\n";
      $html .= "      frm.temperatura.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if((frm.temperatura.value*1)>\"100\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'El valor de la temperatura debe ser menor o igual a 100 grados';\n";
      $html .= "      frm.temperatura.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.tipoProducto.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el Tipo de Producto';\n";
      $html .= "      frm.tipoProducto.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.cantidad.value==\"\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe ingresar la Cantidad';\n";
      $html .= "      frm.cantidad.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(!IsNumeric(frm.cantidad.value))\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'La Cantidad debe ser numerica';\n";
      $html .= "      frm.cantidad.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    if(frm.responsable.value==\"-1\")\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'Debe seleccionar el Profesional Responsable';\n";
      $html .= "      frm.responsable.focus();\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    
    function formaMensajeHemocom($action, $mensaje, $request)
    {
      /*$rpt = new GetReports();
      $mst = $rpt->GetJavaReport('app', 'BancoSangre', 'docIngresoFraccionamiento',array("noId"=>$request['noId'], "tipoId"=>$request['tipoId'], "cod_don"=>$request['codDonante'], "det_frac"=>$request['det_frac']), array('rpt_name'=>'', 'rpt_dir'=>'cache', 'rpt_rewrite'=>TRUE));
      $fnc = $rpt->GetJavaFunction();*/
    
      $html  = ThemeAbrirTabla('MENSAJE');

      $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" colspan=\"2\">\n";
      $html .= "      <table width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "        <tr class=\"normal_10AN\">\n";
      $html .= "          <td align=\"center\">\n".$mensaje."</td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      /*$html .= "    <td align=\"center\" colspan=\"2\"><br>\n";
      $html .= "      ".$mst."\n";
      $html .= "      <input class=\"input-submit\" type=\"button\" name=\"imprimir_doc\" value=\"Imprimir Doc.\" onclick=\"javascript:".$fnc."\">\n";
      $html .= "    </td>\n";
      $html .= "<form name=\"formImprimirCod\" id=\"formImprimirCod\" action=\"".$action['imprimir_codigo']."\" method=\"post\">\n";      
      $html .= "    <td align=\"center\"><br>\n";
      $html .= "      <input class=\"input-submit\" type=\"submit\" name=\"imprimir_cod\" value=\"Imprimir Cod.\">\n";
      $html .= "    </td>\n";
      $html .= "</form>\n";*/
      $html .= "  </tr>\n";
      $html .= "</table>\n";

      $html .= "<br>";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver']."\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      $html .= ThemeCerrarTabla();
      return $html;
    }
  }
?>