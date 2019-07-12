<?php

/* * *******************************************************
 * @package DUANA & CIA
 * @version 1.0 $Id: ESM_AdminPqrs_MenuHTML.class
 * @copyright DUANA & CIA JUN-2012
 * @author R.O.M.A
 * ******************************************************** */

/* * *********************************************************
 * Clase Vista: ESM_AdminPqrs_MenuHTML
 * Clase Contiene menus de modulo 
 * ********************************************************** */

class ESM_AdminPqrs_MenuHTML {
    /*     * ******************************************************
     * Constructor de la clase
     * ****************************************************** */

    function ESM_AdminPqrs_MenuHTML()
    {
        
    }

    /*     * ******************************************************
     * SubMenu de opciones de PQRS
     * ****************************************************** */

    function MenuOpciones($action, $empresa)
    {
        IncludeClass("CalendarioHtml");
        $html = ThemeAbrirTabla('MENU DE OPCIONES PQRS', '50%');

        $html .= "<table width=\"50%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";

        $html .= "</table>\n";
        $html .= "<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "     <td align=\"center\" colspan=\"3\">M E N U\n";
        $html .= "     </td>\n";
        $html .= "  </tr>\n";

        $link1 = ModuloGetURL("app", "ESM_AdminPqrs", "controller", "Crear_caso") . "&datos[empresa_id]=" . $empresa;
        $link2 = ModuloGetURL("app", "ESM_AdminPqrs", "controller", "Actualizacion_Pqrs") . "&datos[empresa_id]=" . $empresa . "&datos[consulta_propios]=1";


        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td   class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $link1 . "\">CREAR CASOS</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td   class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $link2 . "\">CASOS CREADOS</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";

        if (!is_null(SessionGetVar("responsable")))
        {
            $responsable = SessionGetVar("responsable");
            $link2 = ModuloGetURL("app", "ESM_AdminPqrs", "controller", "Actualizacion_Pqrs") . "&datos[empresa_id]=" . $empresa . "&datos[responsable]=" . $responsable["id"];
            $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
            $html .= "      <td   class=\"label\" align=\"center\">\n";
            $html .= "        <a href=\"" . $link2 . "\">CASOS ASIGNADOS</a>\n";
            $html .= "      </td>\n";
            $html .= "  </tr>\n";
        }



        $html .= "</table>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*     * *********************************************************************
     * Funcion : forma para el registro de un caso pqrs
     * @ return string html
     * ********************************************************************** */

    function FormaCrearCaso($action, $farmacias, $empresa, $razonS, $categoria, $estadoCaso, $fuerzas, $consec, $areasPorEmpresa)
    {

        IncludeClass("CalendarioHtml");
        $sql = Autocarga::factory("DMLs_pqrs", "", "app", "ESM_AdminPqrs");
        $html = "<script>\n";
        $html .= "	function ValidarCampos(forma)\n";
        $html .= "	{\n";

        $html .= "		objeto = document.getElementById('error');\n";

        $html .= "		if(forma.resp_caso.value == \"0\")\n";
        $html .= "		{\n";
        $html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR UN RESPONSABLE DEL CASO\";\n";
        $html .= "			return;\n";
        $html .= "		}\n";
        $html .= "		if(forma.prioridad.value == \"0\")\n";
        $html .= "		{\n";
        $html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR LA PRIORIDAD\";\n";
        $html .= "			return;\n";
        $html .= "		}\n";
//        $html .= "		if(forma.categoria.value  == \"0\" )\n";
//        $html .= "		{\n";
//        $html .= "			objeto.innerHTML = \"DEBE SELECCIONAR LA CATEGORIA DEl CASO\";\n";
//        $html .= "			return;\n";
//        $html .= "		}\n";


        $html .= "  
            if(!validarFormularioCaso(forma)){
                // console.log('formulario invalido');
                   return;
            }
        
        ";
        $html .= "		if(forma.observacion.value.length === 0 )\n";
        $html .= "		{\n";
        $html .= "			objeto.innerHTML = \"DEBE INGRESAR UNA DESCRIPCION PARA EL CASO\";\n";
        $html .= "			return;\n";
        $html .= "		}\n";


        $html .= "    document.registrar_caso.action =\"" . $action['crea_caso'] . "\"; \n";  //llamado a metodo de insercion (controller)
        $html .= "   if(buscador && buscador.seleccion && buscador.seleccion.codigo_producto){ document.registrar_caso.productoid.value = buscador.seleccion.codigo_producto; }";
        $html .= "    document.registrar_caso.submit();\n";
        $html .= "	} \n";

        $html .= "
                    function onTipoClienteCambiado(obj){
                    
                       if(obj.value !== 'FM' && obj.value !== 'CL'){
                            return;
                       }
                       
                       var responsable = document.getElementById('resp_caso');
                       
                       if(responsable.options[responsable.selectedIndex].getAttribute('rel') === 'SC002' && obj.value === 'CL' ){
                           return;
                       }
                       
                       var form_cliente = document.getElementById('seleccioncliente');
                       var form_farmacia = document.getElementById('seleccionfarmacia');
                       var columna_cliente_seleccionado = document.getElementById('columna_tercero_seleccionado');
                       
                       form_cliente.style.display = 'none';
                       form_farmacia.style.display = 'none';
                       columna_cliente_seleccionado.style.display = 'none';
                       
                       if(obj.value === 'FM'){
                          form_farmacia.style.display = 'table-row';
                          
                       } else {
                          columna_cliente_seleccionado.style.display = 'table-row';
                          form_cliente.style.display = 'table-row';
                       }


                    }
                    
                    function onBuscarTercero(obj){
                        document.getElementById('tercero_id_seleccionado').value = '';
                        document.getElementById('tipo_tercero_id_seleccionado').value = '';
                        buscarClientePorIdLogistica('tercero_id', 'tipo_id_tercero', 'asignarDatosClienteTipo');
                    }
                    


        ";

        $html .= "</script>\n";
        $html .= ReturnOpenCalendarioScript("registrar_caso", "fecharecepcion", '-') . "\n";

        $html .= ThemeAbrirTabla('REGISTRO DE CASO - PQRS');
        $html .= "<form name=\"registrar_caso\" id=\"registrar_caso\" action=\"javascript:ValidarCampos(document.registrar_caso)\" method=\"post\" enctype=\"multipart/form-data\">\n";
        $html .= "<input type=\"hidden\" name=\"empresa\" id=\"empresa\" value=\"" . $empresa . "\">\n";
        $html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
        $html .= "<table border=\"0\" width=\"90%\" align=\"center\" >\n";
        $html .= "	<tr>\n";
        $html .= "		<td>\n";
        //$html .= "			<fieldset class=\"fieldset\">\n";//
        //$html .= "				<legend class=\"normal_10AN\"></legend>\n";
        $html .= "				<table border=\"0\" width=\"100%\" cellspacing=\"2\">\n";
        $html .= "					<tr>\n";
        $html .= "					 <td align=\"center\">\n";
        $html .= "						 <table border=\"0\" width=\"98%\" class=\"label\">\n";  

        $html .= "								<tr>\n";
        $html .= "									<td width=\"50%\" colspan=\"4\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" >CREAR CASO </td>\n";
        $html .= "								</tr>\n";
        $html .= "								<tr>\n";
        $html .= "									<td width=\"30%\" colspan=\"3\" style=\"background-color:#C0C0C0;\" class=\"\" >No. CASO: " . $consec['consecutivo'] . "</td>\n";
        $html .= "									<td width=\"70%\" align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0;\" class=\"\" >EMPRESA:&nbsp;" . $razonS['razon_social'] . "</td>\n";
        $html .= "								</tr>\n";

        $html .= "								<tr>\n";
        $html .= "									<td width=\"50%\" colspan=\"4\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" >DATOS DEL PUNTO DE ATENCION</td>\n";
        $html .= "								</tr>\n";



        $html .= "								<tr>\n";
        $html .= "									<td width=\"50%\" colspan=\"1\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >
                                                                                              
RESPONSABLE DE RESOLUCION DEL CASO</td>\n";


        $html .= "                					<td colspan='3'>\n";
        $html .= "                					<div id=\"resp_farm\">\n";
        // $html .= "                		            <input type=\"text\" class=\"input-text\" name=\"resp_caso\" id=\"resp_caso\" style=\"width:100%\" maxlength=\"40\" value=\"\">\n";
        $html .= "									<select name=\"resp_caso\" id=\"resp_caso\" class=\"select\" onchange=\"respansableCambia(this)\" autocomplete=\"off\">\n";
        $html .= "<option value='0' selected=\"selected\">-- SELECCIONAR --</option>";

        foreach ($areasPorEmpresa as $datos)
        {
            $html .= "<option value=\"{$datos["id"]}\" rel=\"{$datos["codigo"]}\">{$datos['descripcion']}</option>";
        }

        $html .= "									</select>";
        $html .= "									</div>";
        $html .= "									</td>";




        $html .= "								</tr>\n";



        $html .= "								<tr>\n";
        $html .= "									<td width=\"50%\" colspan=\"1\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO CLIENTE</td>\n";
        $html .= "                                                                       <td width=\"50%\" colspan=\"1\" style=\"text-align:left\" >
                                                                                                <select class='select' id='tipo_cliente' name='tipo_cliente' onchange='onTipoClienteCambiado(this)'>
                                                                                                    <option selected>-- SELECCIONAR --</option>
                                                                                                    <option value='CL'>Cliente</option>
                                                                                                    <option value='FM'>Farmacia</option>
                                                                                                </select>
                                                                                          </td>";
        $html .= "								</tr>\n";





        $html .= "								<tr id='seleccionfarmacia' style='display:none;'>\n";
        $html .= "									<td width=\"50%\" colspan=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">FARMACIA</td>\n";
        $html .= "									<td width=\"100%\" colspan=\"\">";
        $html .= "									<input type=\"hidden\" name=\"centro_utilidad\" id=\"centro_utilidad\" >  ";
        $html .= "									<input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" >  ";
        $html .= "									<select name=\"farmacia\" id=\"farmacia\" class=\"select\"   autocomplete='off'>\n";
        if (count($farmacias) > 1)
        {

            $html .= "									 <option value=\"0\">--SELECCIONAR--</option>";
        }

        foreach ($farmacias as $key => $value)
        {
            $html .= "									 <option value=\"" . $value['bodega'] . "\" rel='{$value["empresa_id"]}_{$value["centro_utilidad"]}'>" . $value['descripcion'] . "</option>";
        }
        $html .= "									</select>";


        $html .= "                                 </td>\n";

        $html .= "								 </tr>\n";

        $idtipos = $sql->obtenerTiposIndentificacion();

        $html .= "								<tr id='seleccioncliente' style='display:none; width:100'>\n";
        $html .= "									<td width=\"50%\" colspan=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CLIENTE</td>\n";
        $html .= "									<td width=\"100%\" colspan=\"\">";
        $html .= "                                                                        TIPO IDENTIFICACION &nbsp; &nbsp; <select name=\"tipo_id_tercero\" id=\"tipo_id_tercero\" class=\"select\">  ";
        $html .= "                                                                              <option value=\"\">--SELECCIONAR--</option>";

        foreach ($idtipos as $d)
        {
            $html .= "<option value=\"{$d["tipo_id_paciente"]}\">{$d["tipo_id_paciente"]}</option>";
        }


        $html .= "                                                                          </select> ";
        $html .= "                                                                         &nbsp; &nbsp;NUMERO IDENTIFICACION&nbsp; <input type='text' id='tercero_id' class='input-text' />&nbsp;<a href='javascript:void(0);' onclick='onBuscarTercero()'>BUSCAR</a>   ";

        $html .= "                                                                      </td>\n";

        $html .= "								 </tr>\n";


        $html .= "								<tr id='columna_tercero_seleccionado' style='display:none;'>\n";
        $html .= "									<td width=\"50%\" colspan=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">NOMBRE CLIENTE</td>\n";
        $html .= "									<td width=\"100%\" colspan=\"\">";
        $html .= "									<input type=\"hidden\" name=\"tercero_id_seleccionado\" id=\"tercero_id_seleccionado\" >  ";
        $html .= "									<input type=\"hidden\" name=\"tipo_tercero_id_seleccionado\" id=\"tipo_tercero_id_seleccionado\" >  ";
        $html .= "                                                                         <span type='text' id='nombre_tercero_seleccionado'  style='width:100%;' ></span>      ";

        $html .= "                                                                      </td>\n";

        $html .= "								 </tr>\n";



        // $html .= "								<tr >\n";
        // $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PLAN DE AFILIACION</td>\n";
        // $html .= "									<td colspan=\"2\">\n";
        // $html .= "                   				<input type=\"text\" class=\"input-text\" name=\"plan\" readonly=\"readonly\" style=\"width:50%; background-color:#C0C0C0\" maxlength=\"30\" value=\"\">\n ";
        // $html .= "                					</td>\n";
        // $html .= "								  </tr>\n";

        $html .= "								 <tr>\n";
        $html .= "									<td width=\"\" colspan=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">PRIORIDAD CASO REPORTADO</td>\n";
        $html .= "									<td colspan=\"\">\n";
        $html .= "									  <select name=\"prioridad\" id=\"prioridad\" class=\"select\">  ";
        $html .= "									    <option value=\"0\">--SELECCIONAR--</option>";
        $html .= "									   </select>";
        $html .= "                					</td>\n";


        $html .= "								 </tr>\n";

        $html .= "								<tr>\n";
        $html .= "								  <td colspan=\"4\" style=\"width:100%; background-color:#C0C0C0\"><br> <input type='hidden' id='responsable_area' name='responsable_area' />\n";
        $html .= "								  </td>\n";
        $html .= "								</tr>\n";

        $html .= "                                                             <td COLSPAN='7' id=\"contenedorFormularioCaso\"></br></br></td>";

        $html .= "				  <tr>\n";
        $html .= "						<td width=\"50%\" colspan=\"4\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">INFORMACION DEL CASO</td>\n";
        $html .= "				  </tr>\n";

        $html .= "               <tr>\n";
        $html .= "				   <table border=\"1\" width=\"98%\" cellspacing=\"2\">\n";
        $html .= "                 <tr>\n";
        $html .= "                   <td width=\"3%\" colspan='2' style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" ></td>\n";
        $html .= "                   <td width=\"3%\"  align=\"left\" colspan=\"\"  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >\n";
        $html .= "                      <div name=\"categoria\" id=\"categoria\" > </div>\n";
        $html .= "                   </td>\n";

        $html .= "                   <td width=\"2%\" colspan='1' style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ADJUNTAR ARCHIVO</td>\n";
        $html .= "				      <td width=\"10%\" align=\"left\" colspan=\"\">\n";

        //$html .= "                                          <form name=\"subir\" enctype=\"multipart/form-data\" method = \"post\">\n";
        $html .= "                                              <input type=\"file\" size=\"45\" class=\"input-text\" name=\"archivo_pqrs\" id=\"archivo_pqrs\">\n";
        //$html .= "                                          </form>";


        $html .= "                   </td>\n";

        $html .= "                 </tr>\n";

        $html .= "                 <tr id='filaobservacion'>\n";

        $html .= "					  <td width=\"3%\"  colspan='1'  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" id=\"observaciontitulo\" >OBSERVACION Y/O SEGUIMIENTO CASO</td>\n";
        $html .= "				      <td width=\"\" colspan='4' align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0\">\n";
        $html .= "                   <textarea name=\"observacion\" id=\"observacion\" style='width:100%' rows=\"3\"></textarea>";
        $html .= "					  </td>\n";

        $html .= "                 </tr>\n";

        $html .= "				   </table>\n";
        $html .= "               </tr>\n";

        //$html .= "				</table>\n";
        $html .= "           </td>\n";

        $html .= "          </tr>\n";

        $html .= "         </table>\n";
        //$html .= "       </fieldset>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";

        $html .= "<table border=\"-1\" width=\"50%\" align=\"center\" >\n";
        $html .= " <tr>\n";
        $html .= "	  <td align=\"center\"><br>\n";
        $html .= "		<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
        $html .= "	  </td>\n";

        $html .= "</form>\n";
        $html .= "<form name=\"forma\" action=\"" . $action['volver'] . "\" method=\"post\">\n";
        $html .= "	  <td align=\"center\"><br>\n";
        $html .= "	  <input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"Volver\">\n";
        $html .= "	      </td>";
        $html .= "</form>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*     * *********************************************************************
     * Crea una forma, para mostrar mensajes informativos con un solo boton
     * @param array $action vector que contine los link de la aplicacion
     * @param string $mensaje Cadena con el texto del mensaje a mostrar en pantalla
     * @return string
     * ********************************************************************** */

    function FormaMensajeModulo($action, $mensaje)
    {
        $html = ThemeAbrirTabla('GESTION PQRS', '70%');
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "	<tr>\n";
        $html .= "		<td>\n";
        $html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "		    <tr class=\"normal_10AN\">\n";
        $html .= "		      <td align=\"center\">\n" . $mensaje . "</td>\n";
        $html .= "		    </tr>\n";
        $html .= "		  </table>\n";
        $html .= "		</td>\n";
        $html .= "	</tr>\n";
        $html .= "	<tr>\n";
        $html .= "		<td align=\"center\"><br>\n";
        $html .= "			<form name=\"form\" action=\"" . $action['volver'] . "\" method=\"post\">";
        $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
        $html .= "			</form>";
        $html .= "		</td>";
        $html .= "	</tr>";
        $html .= "</table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*     * ***************************************************************************
     * Funcion vista: Forma listado casos pqrs
     * @param array $action Vector que continen los link de la aplicacion
     * @param array 
     * @return 
     * **************************************************************************** */

    function Listado_pqrsAct($action, $request, $datosPqrsAct, $conteo, $pagina)
    {
        $html = ThemeAbrirTabla(' ACTUALIZACION/SEGUIMIENTO CASOS PQRS ');

        $vigencia_caso = ModuloGetVar("app", "ESM_AdminPqrs", "Vigencia_Pqrs");

        $request = $_REQUEST;


        /* obtener mktime de fecha actual */
        $dated = date("d");
        $datem = date("m");
        $datey = date("Y");
        $timestamp1 = mktime(0, 0, 0, $datem, $dated, $datey);
        /*         * ************************************ */

        //$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->RollOverFilas();
        $html .= "<script>\n";
        $html .= "	function acceptNum(evt)\n";
        $html .= "	{\n";
        $html .= "		var nav4 = window.Event ? true : false;\n";
        $html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";

        if (!$puntos)
            $html .= "		return (key <= 13 ||(key >= 48 && key <= 57));\n";
        else
            $html .= "		return (key <= 13 || key == 46 || (key >= 48 && key <= 57));\n";

        $html .= "	}\n";
        $html .= "</script>\n";

        $html .= "<br>";
        $html .= "<form name=\"Buscador\" id=\"Buscador\" action=\"" . $action['buscador'] . "\" method=\"post\">";
        $html .= "<table  width=\"40%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
        $html .= "  <tr align=\"center\">\n";
        $html .= "      <td class=\"formulacion_table_list\" width=\"25%\">CODIGO DEL CASO:<div style='margin-top:25px;'>DESCRIPCI&Oacute;N DEL PRODUCTO:</div><div style='margin-top:25px;'>ESTADO:</div</td>\n";
        $html .= "      <td class=\"formulacion_table_list\" width=\"25%\">
                            <input type=\"text\" name=\"buscador[caso]\" maxlength=\"8\" id=\"buscador[caso]\"  class=\"input-text\" value=\"" . $request['buscador']['caso'] . "\">
                                </br></br>
                            <input type=\"text\" name=\"buscador[descripcion_producto]\"  id=\"buscador[descripcion_producto]\"  class=\"input-text\" value=\"" . $request['buscador']['descripcion_producto'] . "\">
                                </br></br> 
                            <select id='buscador[estado]' name='buscador[estado]' class=\"select\" style='margin:5px 0 0 -15px;'><option value=''>SELECCIONAR</option><option value='A001'>ABIERTO</option><option value='C002'>CERRADO</option></select></div></td>\n";

        $html .= "      <td class=\"\" width=\"50%\" >";
        $html .= "        <table  width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">";
        $html .= "        <tr align=\"center\">";
        $html .= "            <td>Fecha Inicial</td>";
        $html .= "            <td class=\"\">";
        $html .= "             <input type=\"text\" name=\"buscador[fecha_ini]\" id=\"fecha_ini\" class=\"input-text\" value='{$request['buscador']['fecha_ini']}'>    ";
        $html .= "            </td>";
        $html .= "		       <td align=\"left\" class=\"label\">" . ReturnOpenCalendario('Buscador', 'fecha_ini', '/', 1) . "</td>\n";
        $html .= "        </tr>";
        $html .= "        <tr align=\"center\">";
        $html .= "            <td>Fecha Final</td>";
        $html .= "            <td class=\"\">";
        $html .= "             <input type=\"text\" name=\"buscador[fecha_fin]\" id=\"fecha_fin\" class=\"input-text\" value='{$request['buscador']['fecha_fin']}'>    ";
        $html .= "            </td>";
        $html .= "		       <td align=\"left\" class=\"label\">" . ReturnOpenCalendario('Buscador', 'fecha_fin', '/', 1) . "</td>\n";
        $html .= "        </tr>";
        $html .= "        </table>";
        $html .= "      </td>";

        $html .= "	</tr>";
        $html .= "	<tr>";
        $html .= "	<td  class=\"formulacion_table_list\"  colspan=\"4\" align=\"center\"><input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\"></td>\n";
        $html .= "	</tr>";
        $html .= "</table>";
        $html .= "</form>";

        if (!empty($datosPqrsAct))
        {
            $pgn = AutoCarga::factory("ClaseHTML");
            $html .= "		" . $pgn->ObtenerPaginado($conteo, $pagina, $action['paginador']);
            $html .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                    <tr class=\"modulo_table_list_title\">\n";
            $html .= "                      <td align=\"center\" width=\"2%\">\n";
            $html .= "                        <a title=''>CODIGO</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\" >\n";
            $html .= "                        <a title='FARMACIA RELACIONADA AL CASO'>CLIENTE/FARM.</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\" >\n";
            $html .= "                        <a title='PERSONA A QUIEN SE ESCALO #CASO'>ASIGNADO A</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='ALTA-MEDIA-BAJA'>PRIORIDAD</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title=''>ESTADO CASO</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='CLASIFICACION MOTIVO QUEJA O SOLICITUD'>CATEGORIA CASO</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='SEGUIMIENTO CASO'>OBSERVACION</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title=''>FECHA CASO</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title=''>CREADO POR</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\" >\n";
            $html .= "                        <a title='ACCIONES: ACTUALIZAR CASO'>ACCIONES</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\" >\n";
            $html .= "                        <a title='VER ADJUNTO'>ADJUNTO</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\" >\n";
            $html .= "                        <a title='FACTOR DE COMPARACION " . $vigencia_caso . "- dias'>VIGENCIA</a>";
            $html .= "                      </td>\n";
            $html .= "                    </tr>\n";

            foreach ($datosPqrsAct as $key => $valor)
            {
                /* armar mktime de la fecha del caso y obtener dif. */
                $fechadato = explode(" ", $valor['fecha_registro']);
                $dateSplited = explode("-", $fechadato[0]);
                $casoAnio = $dateSplited[0];
                $casoMes = $dateSplited[1];
                $casoDia = $dateSplited[2];
                $timestamp2 = mktime(0, 0, 0, $casoMes, $casoDia, $casoAnio);

                $segundos_diferencia = $timestamp1 - $timestamp2;
                //convertir segundos en dias
                $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
                $dias_diferencia = abs($dias_diferencia);
                //quitar los decimales a los dias de diferencia
                $dias_diferencia = floor($dias_diferencia);
                /*                 * **************************************** */

                $cliente = (is_null($valor['farmacia']) ? $valor['nombre_tercero'] : $valor['farmacia']);


                $html .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $html .= "						<td>" . $valor['codigo'] . "</td>";
                $html .= "						<td>" . $cliente . "</td>";
                $html .= "						<td>" . $valor['area_empresa'] . "</td>";
                $html .= "						<td>" . $valor['prioridad'] . "</td>";
                $html .= "						<td>" . $valor['estado_caso'] . "</td>";
                $html .= "						<td>" . $valor['categoria'] . "</td>";
                $html .= "						<td>" . $valor['observacion'] . "</td>";
                $html .= "						<td>" . $valor['fecha_registro'] . "</td>";
                $html .= "						<td>" . $valor['usuario'] . "</td>";
                $html .= "						<td>";

                $link = ModuloGetURL("app", "ESM_AdminPqrs", "controller", "ActualizarCasos") . "&datos[empresa_id]=" .
                        $valor['empresa_id'] . "&caso=" . $valor['codigo'] . "&bodega=" . $valor['bodega'] .
                        "&responsable=" . $valor['area_empresa'] . "&categoria=" . $valor['categoria_caso'] .
                        "&usuario= " . $valor['usuario_id'] . "&estado_caso= " . $valor['estado_caso'] . "&cliente= " . $cliente . "&categoria= " . $valor['categoria'] .
                        "&calificacion= " . $valor['calificacion'];
                
                
                $adjunto = "";
                
                if(!is_null($valor['archivo']) || $valor['archivo'] != ''){
                     $adjunto = "<center><a class=\"label_error\" href='pqrs/".$valor['archivo']."' target='blank'  title=\"ACTUALIZAR CASO\"><img src=\"" . GetThemePath() . "/images/fileopen.png\" border='0'></a></center>\n";
                }

                //if($valor['estado_caso'] == 'CERRADO' || $dias_diferencia >=3)
                /* if ($valor['estado_caso'] == 'Cerrado')
                  {
                  $html .= "					   <center><a class=\"label_error\" href=\"#\"  title=\"CASO RESUELTO / � VENCIDO\"><img src=\"" . GetThemePath() . "/images/si.png\" border='0'></a></center>\n";
                  }
                  else
                  {
                  $html .= "				  <center><a class=\"label_error\" href=\"" . $link . "\"  title=\"ACTUALIZAR CASO\"><img src=\"" . GetThemePath() . "/images/resumen.gif\" border='0'></a></center>\n";
                  }

                 */
                $html .= "				  <center><a class=\"label_error\" href=\"" . $link . "\"  title=\"ACTUALIZAR CASO\"><img src=\"" . GetThemePath() . "/images/resumen.gif\" border='0'></a></center>\n";
                $html .= "						</td>";
                
                $html .= "                  <td align=\"center\">";
                $html .=                        $adjunto;
                $html .= "                  </td>";

                $html .= "						<td align=\"center\">";


                if ($valor['estado_caso'] == 'Cerrado')
                {
                    $html .= "                 <a title=\"CERRADO\"><img src=\"" . GetThemePath() . "/images/candadocasos.png\" border='0'></a>";
                }
                else
                {
                    if ($dias_diferencia >= $valor["dias_vigencia"])
                    {
                        $html .= "                 <a title=\"CASO VENCIDO\"><img src=\"" . GetThemePath() . "/images/Redlab.png\" border='0'></a>";
                    }
                    else
                    {
                        if ($dias_diferencia == ($valor["dias_vigencia"] - 1))
                        {
                            $html .= "               <a title=\"CASO PROXIMO A VENCER\"><img src=\"" . GetThemePath() . "/images/vencimientocaso.png\" border='0'></a>	";
                        }
                        else
                        {
                            $html .= "          <a title=\"CASO ACTIVO O TRAMITADO\"><img src=\"" . GetThemePath() . "/images/Greenlab.png\" border='0'></a>";
                        }
                    }
                }


                $html .= "						</td>";

                $html .= "                   </tr>\n";
            }
            $html .= "				</table>";
            $html .= "<br>";
        }

        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
        $html .= "        VOLVER\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";

        $html .= ThemeCerrarTabla();

        return $html;
    }

    /*     * *********************************************************************
     * Funcion : forma para la actualizacion de casos Pqrs
     * @ return string html
     * ********************************************************************** */

    function FormaActCaso($action, $datos_caso, $encabezado,$paciente)
    {

        $sql = Autocarga::factory("Permisos", "", "app", "ESM_AdminPqrs");
        $razon = $sql->ListarEmpresa($encabezado["empresa"]);
        $rpt = new GetReports();

        $html = "<style>
					@media print
					{    
						.no-print, .no-print *
						{
							display: none !important;
						}
					}
				</style>";

        $datosimprimir = array();
        $datosimprimir["detalle"] = $datos_caso;
        $datosimprimir["encabezado"] = $encabezado;

        $html .= $rpt->GetJavaReport('app', 'ESM_AdminPqrs', 'Caso', $datosimprimir, array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));

        $fnc = $rpt->GetJavaFunction();

        $html .= ThemeAbrirTabla('ACTUALIZACION DE CASO - PQRS', '80%');
        
        if(!preg_match_all("/SC/",$encabezado['caso'], $matches)){
            $html .= " <a href=\"javascript:" . $fnc . "\"      style='margin-left:75px;' class='no-print'><b>IMPRIMIR</b></a>";
        }
        
        $html .= "				<center><table border=\"1\" width=\"90%\" class=\"label\">\n";

        $html .= "								<tr>\n";
        $html .= "									<td width=\"50%\" colspan=\"4\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" >ACTUALIZAR CASO </td>\n";
        $html .= "								</tr>\n";
        $html .= "								<tr>\n";
        $html .= "									<td width=\"30%\" colspan=\"3\" style=\"background-color:#C0C0C0;\" class=\"\" >No. CASO: " . $encabezado["caso"] . "</td>\n";
        $html .= "									<td width=\"70%\" align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0;\" class=\"\" >CODIGO EMPRESA:&nbsp;  " . $encabezado["empresa"] . "</td>\n";
        $html .= "								</tr>\n";
        $html .= "								<tr>\n";
        $html .= "									<td width=\"30%\" colspan=\"3\" style=\"background-color:#C0C0C0;\" class=\"\" > <h3>Paciente :&nbsp;" . $paciente[0]["nombre_paciente"] . "</h3></td>\n";
        $html .= "									<td width=\"70%\" align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0;\" class=\"\" ><h3>Documento: " . $paciente[0]["tipo_id_paciente"].". ".$paciente[0]["paciente_id"] . "</h3></td>\n";
        $html .= "								</tr>\n";
        
        $html .= "								<tr>\n";
        $html .= "									<td width=\"50%\" colspan=\"4\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" >DATOS DEL PUNTO DE ATENCION</td>\n";
        $html .= "								</tr>\n";

        $html .= "								<tr>\n";
        $html .= "									<td width=\"\" colspan=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CLIENTE/FARMACIA " . "</td>\n";
        $html .= "									<td width=\"\" colspan=\"\">";
        $html .= "                                  <input type=\"text\" name=\"farmacia\" id=\"farmacia\" size=\"40\" readonly=\"readonly\"  value=\"" . $encabezado["cliente"] . "\">  ";
        $html .= "                                 </td>\n";
        $html .= "									<td width=\"100%\" colspan=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">\n";
        $html .= "                   				 RESPONSABLE DE RESOLUCION DEL CASO";
        $html .= "                					</td>\n";
        $html .= "                					<td width=\"\">\n";
        $html .= "                                  <input type=\"text\" name=\"responsable\" id=\"responsable\" maxlength=\"30\" size=\"35\" readonly=\"readonly\" value=\"" . $encabezado["resp"] . "\">  ";
        $html .= "									</td>";
        $html .= "								 </tr>\n";

        $html .= "								 <tr>\n";
        $html .= "									<td width=\"\" colspan=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CATEGORIA</td>\n";
        $html .= "									<td colspan=\"\">\n";
        $html .= "                                  <input type=\"text\" name=\"categoria\" id=\"categoria\" size=\"40\" readonly=\"readonly\" value=\"" . $encabezado["categoria"] . "\">  ";
        $html .= "                					</td>\n";
        $html .= "									<td colspan=\"2\" style=\"text-align:center;text-indent:8pt\" class=\"\">\n";
        $html .= "									 " . $razon['razon_social'] . "  ";
        $html .= "                					</td>\n";
        // $html .= "									<td colspan=\"\">\n";
        // $html .= "                                  <input type=\"text\" name=\"prioridad\" id=\"prioridad\" readonly=\"readonly\"  value=\"\">  ";		 
        // $html .= "                					</td>\n";		 
        $html .= "								 </tr>\n";
        $html .= "						</table></center>\n";
        $html .= "						<br>\n";

        $link = ModuloGetURL("app", "ESM_AdminPqrs", "controller", "UpdateCaso");

        $html .= "<br>";
        $html .= "<form name=\"actualizar_caso\" id=\"actualizar_caso\" action=\"" . $link . "\" method=\"post\">";
        $html .= " <input type=\"hidden\" name=\"caso\" id=\"caso\" value=\"" . $encabezado["caso"] . "\">";
        $html .= " <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"" . $encabezado["empresa"] . "\">";
        $html .= "<table  width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
        $html .= "  <tr align=\"center\">\n";
        $html .= "      <td class=\"formulacion_table_list\" colspan=\"6\">SEGUIMIENTO / OBSERVACION</td>\n";
        $html .= "	 </tr>";

        $i = 0;
        foreach ($datos_caso as $k => $v)
        {
            $html .= "  <tr align=\"center\">\n";
            $html .= "	<td width=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >DETALLE</td>\n";
            $html .= "	<td width=\"\" align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0\">\n";
            $html .= "      <textarea name=\"observacion" . $i . "\" readonly=\"readonly\" id=\"observacion" . $i . "\" cols=\"58\" rows=\"3\">" . $v['observacion'] . "</textarea>";
            $html .= "	</td>\n";

            $html .= "	<td width=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >USUARIO</td>\n";
            $html .= "	<td width=\"\" align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0\">\n";
            $html .= "      <input type=\"text\" name=\"usuario" . $i . "\" id=\"usuario" . $i . "\" maxlength=\"\" size=\"\" readonly=\"readonly\" value=\"" . $v['usuario'] . "\">  ";
            $html .= "	</td>\n";

            $html .= "	<td width=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA OBSERV.</td>\n";
            $html .= "	<td width=\"\" align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0\">\n";
            $html .= "      <input type=\"text\" name=\"fecha_observ" . $i . "\" id=\"fecha_observ" . $i . "\" maxlength=\"\" size=\"\" readonly=\"readonly\" value=\"" . $v['fecha_registro'] . "\">  ";
            $html .= "	</td>\n";
            $html .= "	 </tr>";
            $i++;
        }

        if (trim($encabezado["estadoCaso"]) == "Abierto")
        {


            $html .= "	 <tr>\n";
            $html .= "		<td colspan=\"6\" style=\"width:100%; background-color:#C0C0C0\"><br>\n";
            $html .= "		</td>\n";
            $html .= "	 </tr>\n";

            $html .= " <tr align=\"center\">\n";
            $html .= "	<td width=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >NUEVA RESPUESTA</td>\n";
            $html .= "	<td width=\"\" align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0\">\n";
            $html .= "      <textarea name=\"observacionAct\" id=\"observacionAct\" cols=\"58\" rows=\"3\"></textarea>";
            $html .= "	</td>\n";

            $cerrarstring = "";

            if ($encabezado["usuario"] == UserGetUID())
            {
                $cerrarstring = "CERRAR CASO: ?";
            }

            $html .= "	<td width=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">{$cerrarstring}</td>\n";
            $html .= "	<td width=\"\" align=\"center\" colspan=\"4\" style=\"background-color:#C0C0C0\">\n";


            if ($encabezado["usuario"] == UserGetUID())
            {



                $html .= "   <input type=\"checkbox\" name=\"cerrar_caso\" id=\"cerrar_caso\" value=\"1\"> CERRAR ";


                $html .= "   <select style='margin-left:10px;' name=\"calificacion\" id=\"calificacion\"> 
				
						<option value='0'>CALIFICAR</option>
						<option value='4'>Excelente</option>
						<option value='3'>Bueno</option>
						<option value='2'>Aceptable</option>
						<option value='1'>Malo</option>
				
			   ";
            }

            $html .= "	</td>\n";

            $html .= "	 </tr>";

            $html .= "	 <tr>";
            $html .= "	    <td class=\"formulacion_table_list\" colspan=\"6\" align=\"center\"><input type=\"button\" onclick='actualizarcaso();' value=\"ACTUALIZAR\" class=\"input-submit\"><input type='hidden' name='codigocaso' id='codigocaso' value='{$caso}' /></td>\n";
            $html .= "	 </tr>";
            $html .= "</table>";
            $html .= "</form>";
        }

        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
        $html .= "        VOLVER\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";

        $html .= ThemeCerrarTabla();

        return $html;
    }

}

?>
