<?php

/* * ************************************************************************************  
 * $Id: app_Cg_Documentos_userclasses_HTML.php,v 1.4 2007/02/06 20:42:47 jgomez Exp $ 
 * 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS-FI
 * 
 * $Revision: 1.4 $ 
 * 
 * @autor Jaime G?mez 
 * ************************************************************************************* */
IncludeClass("ClaseHTML");

class app_Cg_Documentos_userclasses_HTML extends app_Cg_Documentos_user {

    function app_Cg_Documentos_userclasses_HTML() {
        
    }

    /*     * ******************************************************************************** 
     * Funci?n principal del m?dulo 
     * 
     * @return boolean
     * ********************************************************************************* */

    function main() {
        SessionDelVar("EMPRESA");
        $request = $_REQUEST;

        $url[0] = 'app';                         //Tipo de Modulo
        $url[1] = 'Cg_Documentos';   //Nombre del Modulo
        $url[2] = 'user';                  //Si es User,controller...
        $url[3] = 'main2';   //Metodo.
        $url[4] = 'datos';      //vector de $_request.
        $arreglo[0] = 'EMPRESA';     //Sub Titulo de la Tabla
        //Generar de Busqueda de Permisos SQL
        $obj_busqueda = AutoCarga::factory("DocumentosSQL", "", "app", "Cg_Documentos");
        //Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
        $datos = $obj_busqueda->Empresas();

        //Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
        // Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
        $forma = gui_theme_menu_acceso("CREACION DE DOCUMENTOS - CG_DOCUMENTOS", $arreglo, $datos, $url, ModuloGetURL('system', 'Menu'));
        $this->salida = $forma;

        /* 			
          //(nombre de la Tabla Acceso,
          FormaMostrarMenuHospitalizacion($forma); //Invocar un view, para mostrar la informacion.
         */
        return true;
    }

    function main2() {
        //print_r($_REQUEST);
        $this->FormaMostrarDocumentos();
        return true;
    }

    /*     * *********************************************************************************
     * Muestra el menu de los empresas y centros de utilidad 
     * 
     * @access public 
     * ********************************************************************************* */

    function FormaMostrarDocumentos() {

        $this->CrearElementos();
        $this->MostrarDocus();
        $titulo[0] = 'TIPOS DE DOCUMENTOS';
        $url[0] = 'app'; //contenedor 
        $url[1] = 'Cg_Documentos'; //m?dulo 
        $url[2] = 'user'; //clase 
        $url[3] = 'ListaDocumentos'; //m?todo 
        $url[4] = 'Docus'; //indice del request
        $this->salida .= gui_theme_menu_acceso('CREACION DE DOCUMENTOS PARA ' . SessionGetVar("NOMBRE_EMPRESA") . '', $titulo, $this->TipsDocumentos, $url, ModuloGetURL('system', 'Menu'));
        return true;
    }

    function ListaDocumentos() {
        // echo "helloaaaaaaaa";
        $this->CrearElementos();
        IncludeClass("ClaseHTML");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $path = SessionGetVar("rutaImagenes");
        $this->SubMenu();
        //echo "que".$this->Datos['tipo_doc_general_id'];
        //ECHO "que11".SessionGetVar("EMPRESA");
        $consulta = new DocumentosSQL();
        $this->IncludeJS('RemoteScripting');
        $this->IncludeJS('ScriptRemoto/definir.js', $contenedor = 'app', $modulo = 'Cg_Documentos');

        $javaC = "<script>\n";
        $javaC .= "   var contenedor1=''\n";
        $javaC .= "   var titulo1=''\n";
        $javaC .= "   var hiZ = 2;\n";
        $javaC .= "   var DatosFactor = new Array();\n";
        $javaC .= "   var EnvioFactor = new Array();\n";
        $javaC .= "   function Iniciar2(tit)\n";
        $javaC .= "   {\n";
        $javaC .= "       contenedor1 = 'ContenedorVer';\n";
        $javaC .= "       titulo1 = 'tituloVer';\n";

        $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $javaC.= "        Capa = xGetElementById(contenedor1);\n";
        $javaC .= "       xResizeTo(Capa, 600, 'auto');\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/6, xScrollTop()+100);\n";
        $javaC .= "       ele = xGetElementById(titulo1);\n";
        $javaC .= "       xResizeTo(ele, 580, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC .= "       ele = xGetElementById('cerrarVer');\n";
        $javaC .= "       xResizeTo(ele, 20, 20);\n";
        $javaC .= "       xMoveTo(ele, 580, 0);\n";
        $javaC .= "   }\n";
        $javaC.= "</script>\n";
        $this->salida.= $javaC;
        $javaC1.= "<script>\n";
        $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     window.status = '';\n";
        $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
        $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
        $javaC1 .= "     ele.myTotalMX = 0;\n";
        $javaC1 .= "     ele.myTotalMY = 0;\n";
        $javaC1 .= "   }\n";

        $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     if (ele.id == titulo1) {\n";
        $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
        $javaC1 .= "     }\n";
        $javaC1 .= "     else {\n";
        $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $javaC1 .= "     }  \n";
        $javaC1 .= "     ele.myTotalMX += mdx;\n";
        $javaC1 .= "     ele.myTotalMY += mdy;\n";
        $javaC1 .= "   }\n";

        $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "   }\n";

        $javaC1.= "function MostrarCapa(Elemento)\n";
        $javaC1.= "{\n";
        $javaC1.= "    capita = xGetElementById(Elemento);\n";
        $javaC1.= "    capita.style.display = \"\";\n";
        $javaC1.= "}\n";

        $javaC1.= "function Cerrar(Elemento)\n";
        $javaC1.= "{\n";
        $javaC1.= "    capita = xGetElementById(Elemento);\n";
        $javaC1.= "    capita.style.display = \"none\";\n";
        $javaC1.= "}\n";

        $javaC1.= "function Traer(Elemento)\n";
        $javaC1.= "{\n";
        $javaC1.= "    document.getElementById('hcuenta').innerHTML=Elemento;\n";
        $javaC1.= "    document.hijo_cuenta.padre.value=Elemento;\n";
        $javaC1.= "}\n";

        $javaC1.= "</script>\n";
        $this->salida.= $javaC1;



        /*         * *****************************************************************************
         * Ventana emergente 3 aqui es cuando se modifica una cuenta. 
         * ******************************************************************************** */
        $this->salida.="<div id='ContenedorVer' class='d2Container' style=\"display:none\">";
        $this->salida .= "    <div id='tituloVer' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $this->salida .= "   <div id='cerrarVer' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorVer');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $this->salida .= "    <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    <div id='ContenidoVer'>\n";
        $this->salida .= "    </div>\n";
        $this->salida.="</div>";
        /*         * ************************************************************************************
         * final de la ventana3
         * ********************************************************************************* */

        $this->salida .= ThemeAbrirTabla("CREAR DOCUMENTOS");
        $accion1 = ModuloGetURL('app', 'Cg_Documentos', 'user', 'ListaDocumentos');
        if ($_REQUEST['sec'] == 1) {
            $this->salida .= "                <div id=\"ventana0\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            $this->salida .= "                Documento Creado Satisfactoriamente";
            $this->salida .= "                </div>\n";
        }
        if ($_REQUEST['secact'] == 1) {
            $this->salida .= "                <div id=\"ventana0\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            $this->salida .= "                Documento Actualizado Satisfactoriamente";
            $this->salida .= "                </div>\n";
        }
        if ($_REQUEST['addx'] == 1) {
            $this->salida .= "                <div id=\"ventana0\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            $this->salida .= "                Parametros Adicionados Satisfactoriamente";
            $this->salida .= "                </div>\n";
        }
        $this->salida .= "                 <form name=\"docu\" action=\"" . $accion1 . "\" method=\"post\">\n";
        $this->salida .= "                    <table width=\"100%\">\n";
        $this->salida .= "                    <tr colspan=\"8\">\n";
        $this->salida .= "                      <td align=\"center\">\n";


        if ($_REQUEST['sali_pa'] == 1 || $_REQUEST['add_canceli'] == 'Volver' || $_REQUEST['addx'] == 1 || $_REQUEST['add_cancel'] == 'Cancelar' || $_REQUEST['sec'] == 1 || $_REQUEST['cancel'] == 'Cancelar' || $_REQUEST['secact'] == 1 || $_REQUEST['calact'] == 'Cancelar') {
            //echo "nuev";
            //"empresa".$_REQUEST['empresa']."tipo".$_REQUEST['tipo_dc'];
            //$ADICIONAR=ModuloGetURL('app','Cg_Documentos','user','MenuParametros',array('doc_id'=>$vector[$i]['documento_id'],'tip_doc'=>$_REQUEST['tipo_dc']));

            $vector = $consulta->ListarDocumentos($_REQUEST['tipo_dc']['tipo_doc_general_id'], $_REQUEST['empresa']);
            $this->actionOption = ModuloGetURL('app', 'Cg_Documentos', 'user', 'CrearDocumento', array('tip_doc' => $_REQUEST['tipo_dc']));
        } else {
            //echo "viejo";
            $vector = $consulta->ListarDocumentos($this->Datos['tipo_doc_general_id'], SessionGetVar("EMPRESA"));
            $this->actionOption = ModuloGetURL('app', 'Cg_Documentos', 'user', 'CrearDocumento', array('tip_doc' => $this->Datos));
        }
        $this->salida .= "                          <a  title=\"CREAR DOCUMENTO\" class=\"label_error\" href=\"" . $this->actionOption . "\"> CREAR DOCUMENTO </a>\n";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                    </table>\n";




        if (count($vector) > 0) {
            $this->salida .= "                <div id=\"ventana1\">\n";
            $this->salida .= "                 <table width=\"78%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $this->salida .= "                        PREFIJO";
            $this->salida .= "                      </td>\n";
            $this->salida .= "                      <td align=\"center\"width=\"5%\">\n";
            $this->salida .= "                       NUMERACI?N";
            $this->salida .= "                      </td>\n";
            $this->salida .= "                      <td align=\"center\" width=\"45%\">\n";
            $this->salida .= "                        DESCRIPCION";
            $this->salida .= "                      </td>\n";
            $this->salida .= "                      <td align=\"center\" width=\"9%\">\n";
            $this->salida .= "                        N? DIGITOS";
            $this->salida .= "                      </td>\n";
            $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $this->salida .= "                        ESTADO";
            $this->salida .= "                      </td>\n";
            $this->salida .= "                      <td align=\"center\" width=\"7%\">\n";
            $this->salida .= "                        PREFIJO FI";
            $this->salida .= "                      </td>\n";
            $this->salida .= "                      <td colspan=\"3\"align=\"center\" width=\"10%\">\n";
            $this->salida .= "                        OPCIONES";
            $this->salida .= "                      </td>\n";
            $this->salida .= "                    </tr>\n";



            for ($i = 0; $i < sizeof($vector); $i++) {
                $this->salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                $this->salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
                $this->salida .= "                     " . strtoupper($vector[$i]['prefijo']) . "";
                $this->salida .= "                      </td>";
                $this->salida .= "                      <td align=\"center\" >\n";
                $this->salida .= "                        " . $vector[$i]['numeracion'];
                $this->salida .= "                      </td>\n";
                $this->salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                $this->salida .= "                        " . strtoupper($vector[$i]['descripcion']);
                $this->salida .= "                      </td>\n";
                $this->salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
                $this->salida .= "                        " . $vector[$i]['numero_digitos'];
                $this->salida .= "                      </td>\n";
                $this->salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
                $this->salida .= "                        " . $vector[$i]['sw_estado'];
                $this->salida .= "                      </td>\n";
                $this->salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
                $this->salida .= "                        " . $vector[$i]['prefijo_fi'];
                $this->salida .= "                      </td>\n";
                $this->salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";



                if ($_REQUEST['sali_pa'] == 1 || $_REQUEST['add_canceli'] == 'Volver' || $_REQUEST['addx'] == 1 || $_REQUEST['add_cancel'] == 'Cancelar' || $_REQUEST['sec'] == 1 || $_REQUEST['cancel'] == 'Cancelar' || $_REQUEST['secact'] == 1 || $_REQUEST['calact'] == 'Cancelar') {

                    $MODIFICAR = ModuloGetURL('app', 'Cg_Documentos', 'user', 'ModificarDocumento', Array('doc_id' => $vector[$i]['documento_id'], 'tip_doc' => $_REQUEST['tipo_dc']));
                    $ADICIONAR = ModuloGetURL('app', 'Cg_Documentos', 'user', 'MenuParametros', array('doc_id' => $vector[$i]['documento_id'], 'tip_doc' => $_REQUEST['tipo_dc']));
                    $javaAccionAnular = "javascript:MostrarCapa('ContenedorVer'); VerDocu('" . $vector[$i]['documento_id'] . "','" . SessionGetVar("EMPRESA") . "','" . $_REQUEST['tipo_dc']['tipo_movimiento_id'] . "'); Iniciar2('Datos Documento');";
                } else {
                    $MODIFICAR = ModuloGetURL('app', 'Cg_Documentos', 'user', 'ModificarDocumento', Array('doc_id' => $vector[$i]['documento_id'], 'tip_doc' => $this->Datos));
                    $ADICIONAR = ModuloGetURL('app', 'Cg_Documentos', 'user', 'MenuParametros', array('doc_id' => $vector[$i]['documento_id'], 'tip_doc' => $this->Datos));
                    $javaAccionAnular = "javascript:MostrarCapa('ContenedorVer'); VerDocu('" . $vector[$i]['documento_id'] . "','" . SessionGetVar("EMPRESA") . "','" . $this->Datos['tipo_movimiento_id'] . "'); Iniciar2('Datos Documento');";
                }

                $this->salida .= "                        <a\n";
                $this->salida .= "                        title=\"Ver Datos Completos\" href=\"" . $javaAccionAnular . "\">";
                $this->salida .= "                         <sub><img src=\"" . $path . "/images/auditoria.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $this->salida .= "                        </a>\n";
                $this->salida .= "                      </td>\n";
                $this->salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
                $this->salida .= "                        <a\n";
                $this->salida .= "                        title=\"Modificar\" href=\"" . $MODIFICAR . "\"";
                $this->salida .= "                          <sub><img src=\"" . $path . "/images/modificar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $this->salida .= "                        <a>\n";
                $this->salida .= "                      </td>\n";
                $this->salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
                if ($vector[$i]['sw_contabiliza'] == '1') {
                    $this->salida .= "                        <a\n";
                    $this->salida .= "                        title=\"Adicionar Parametro\" href=\"" . $ADICIONAR . "\"";
                    $this->salida .= "                         <sub><img src=\"" . $path . "/images/news.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $this->salida .= "                        <a>\n";
                } elseif ($vector[$i]['sw_contabiliza'] == '0') {
                    $this->salida .= "                         <sub><img src=\"" . $path . "/images/news2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                }

                $this->salida .= "                      </td>\n";
                $this->salida .= "                      </td>\n";
                $this->salida .= "                    </tr>\n";
            }
            $this->salida .= "                </table>\n";
            $this->salida .= "               </div>\n";
        } else {
            $this->salida .= "              <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "                   <tr class=\"label_error\">\n";
            $this->salida .= "                      <td colspan='2' align=\"center\">\n";
            $this->salida .= "                        NO HAY DOCUMENTOS CON ESAS CARACTERISTICAS";
            $this->salida .= "                      </td>\n";
            $this->salida .= "                   </tr>\n";
            $this->salida .= "              <table>";
        }
        $this->salida .= "              </form>";
        $accion3 = ModuloGetURL('app', 'Cg_Documentos', 'user', 'FormaMostrarDocumentos');
        $this->salida .= "                 <table width='100%'>\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                         <td  align=\"center\">\n";
        $this->salida .= "                          <form name=\"volver\" action=\"" . $accion3 . "\" method=\"post\">\n";
        $this->salida .= "                           <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "                           </form>\n";
        $this->salida .= "                         </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                  </table>\n";
        $this->salida.="<script language=\"javaScript\">
      function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }
      
           
      </script>";


        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /*     * *****************************************************************************
     * Funcion en la cuial se crean elementos desde cero
     *
     * ********************************************************************************* */

    function CrearDocumento() {

        //$this->SubMenu();
        $this->CrearElementos();
        IncludeClass("ClaseHTML");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $path = SessionGetVar("rutaImagenes");

        //ECHO "que11".SessionGetVar("EMPRESA");
        $consulta = new DocumentosSQL();
        $this->IncludeJS('RemoteScripting');
        $this->IncludeJS('ScriptRemoto/definir.js', $contenedor = 'app', $modulo = 'Cg_Documentos');

        $accion1 = ModuloGetURL('app', 'Cg_Documentos', 'user', 'CrearDocumento');
        $this->salida .= ThemeAbrirTabla("DOCUMENTOS");
        $datos = $_REQUEST['tip_doc'];
        
        
        $lista_prefijo_fi = $consulta->obtener_prefijo_fi();
        
        //var_dump($datos);
        //echo "aquiyocomo".$datos["tipo_doc_general_id"];
        $this->salida .= "                 <form name=\"crear\" action=\"" . $accion1 . "\" method=\"post\">\n";
        $this->salida .= "                   <table align=\"center\" width=\"40%\" class=\"modulo_table_list\">\n";
        $this->salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                         CREAR DOCUMENTO";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                     </tr>\n";
        $this->salida .= "                     <tr>\n";
        $this->salida .= "                       <td align=\"center\"class=\"modulo_table_list_title\">\n";
        $this->salida .= "                         PREFIJO";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"left\" class=\"modulo_list_claro\">\n";
        $this->salida .= "                        <input type=\"text\" class=\"input-text\" class=\"label\" name=\"prefijo\" size=\"7\" maxlength=\"4\" onclick=\"Limpiar()\">\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr>\n";
        $this->salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "                         NUMERO DE DIGITOS";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"left\"class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <select name=\"num_dig\" class=\"select\">";
        for ($i = 4; $i < 17; $i++) {
            $this->salida .="                       <option value=\"" . $i . "\">" . $i . "</option> \n";
        }
        $this->salida.= "                       </select>\n";
        //$this->salida .= "                         <input type=\"text\" class=\"input-text\" class=\"label\" name=\"num_dig\" size=\"7\" maxlength=\"4\" onkeypress=\"return acceptNum(event)\" onclick=\"Limpiar()\">\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
//       $this->salida .= "                    <tr>\n";
//       $this->salida .= "                       <td align=\"left\" class=\"modulo_table_list_title\" >\n";
//       $this->salida .= "                         PERMITE CONTABILIZAR ?";
//       $this->salida .= "                       </td>\n";
//       $this->salida .= "                       <td align=\"center\"  class=\"modulo_list_claro\" class=\"label\">\n";
//       $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"conta\" value=\"1\" onclick=\"Limpiar()\"><b>SI</b>\n";
//       $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"conta\" value=\"0\" onclick=\"Limpiar()\"><b>NO</b>\n";
//       $this->salida .= "                       </td>\n";
//       $this->salida .= "                      </tr>\n";
        
        
        $this->salida .= "                      <tr>\n";
        $this->salida .= "                          <td class=\"modulo_table_list_title\" align=\"center\" > PREFIJO FI </td>\n";
        $this->salida .= "                          <td align=\"left\" class=\"modulo_list_claro\" >\n";
        $this->salida .= "                              <select name=\"prefijos_financiero_id\" class=\"select\">";
        $this->salida .= "                                  <option value='' >-- Seleccionar --</option>";

        foreach ($lista_prefijo_fi as $key => $value) {
            $selected = ($vector[0]['prefijos_financiero_id']==$value['id'])? " selected ": ' ';
            $this->salida .= "                              <option value='{$value['id']}' {$selected} >{$value['descripcion_completa']}</option>";
        }

        $this->salida .= "                              </select>";
        $this->salida .= "                          </td>\n";
        $this->salida .= "                      </tr>\n";
        
        
        $this->salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                         DESCRIPCION";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\" >\n";
        $this->salida .= "                       <td align=\"cemter\" colspan=\"2\">\n";
        $this->salida .= "                          <TEXTAREA NAME=descri ROWS=2  COLS=40 Style=\"width:100%\" class=\"textarea\" onclick=\"Limpiar()\"></TEXTAREA></td>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        if ($datos['tipo_movimiento_id'] == 'FV') {
            $ban = 'R';
            $this->salida .= "                     <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
            $this->salida .= "                         RESOLUCION";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
            $this->salida .= "                       <td align=\"left\" colspan=\"2\">\n";
            $this->salida .= "                          <TEXTAREA NAME=reso ROWS=2  COLS=40 class=\"textarea\" Style=\"width:100%\" onclick=\"Limpiar()\"></TEXTAREA></td>\n";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
        } else {
            $ban = 'T';
            $this->salida .= "                     <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
            $this->salida .= "                         TEXTO1";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            $this->salida .= "                     <tr class=\"modulo_list_claro\" >\n";
            $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
            $this->salida .= "                          <TEXTAREA NAME=texto1 ROWS=2  COLS=40 class=\"textarea\" Style=\"width:100%\" onclick=\"Limpiar()\"></TEXTAREA></td>\n";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
        }

        $this->salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                       <td align=\"center\"colspan=\"2\">\n";
        $this->salida .= "                         TEXTO2";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\" >\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                          <TEXTAREA NAME=texto2 ROWS=2  COLS=40 Style=\"width:100%\" class=\"textarea\" onclick=\"Limpiar()\"></TEXTAREA></td>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_table_list_title\" >\n";
        $this->salida .= "                       <td align=\"center\"colspan=\"2\">\n";
        $this->salida .= "                         TEXTO3";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\" >\n";
        $this->salida .= "                       <td align=\"center\"colspan=\"2\">\n";
        $this->salida .= "                          <TEXTAREA NAME=texto3 ROWS=2  COLS=40 Style=\"width:100%\" class=\"textarea\" onclick=\"Limpiar()\"></TEXTAREA></td>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_table_list_title\" >\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                         MENSAJE";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\" >\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                          <TEXTAREA NAME=msj ROWS=2  COLS=40 Style=\"width:100%\" class=\"textarea\" onclick=\"Limpiar()\"></TEXTAREA></td>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";

        $this->salida .= "                  </table>\n";
        $this->salida .= "                <div id=\"ventanacrear\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">\n";
        $this->salida .= "                </div>\n";
        //   $vector=$consulta->ListarDocumentos($this->Datos['tipo_doc_general_id'],SessionGetVar("EMPRESA"));
        $this->salida .= "              </form>";
        $accion30 = ModuloGetURL('app', 'Cg_Documentos', 'user', 'ListaDocumentos', Array('empresa' => SessionGetVar("EMPRESA"), 'tipo_dc' => $datos));
        $this->salida .= "                 <table width=\"30%\" align=\"center\">\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                         <td  align=\"center\">\n";
        $this->salida .= "                          <form name=\"aceptar\" action=\"" . $accion30 . "\" method=\"post\">\n";
        $this->salida .= "                           <input type=\"button\" class=\"input-submit\" name=\"Accept\" value=\"Aceptar\" onclick=\"Validar('" . $ban . "','" . $datos['tipo_doc_general_id'] . "')\">\n";
        $this->salida .= "                           <input type=\"hidden\" name=\"sec\" value=\"1\">\n";
        $this->salida .= "                         </form>\n";
        $this->salida .= "                         </td>\n";
        $this->salida .= "                         <td  align=\"center\">\n";
        $this->salida .= "                          <form name=\"volver\" action=\"" . $accion30 . "\" method=\"post\">\n";
        $this->salida .= "                           <input type=\"submit\" name=\"cancel\" class=\"input-submit\" value=\"Cancelar\">\n";
        $this->salida .= "                           </form>\n";
        $this->salida .= "                         </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                  </table>\n";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /*     * *******************************************************************************
     *
     *
     * ********************************************************************************** */

    function ModificarDocumento() {
        $this->CrearElementos();
        IncludeClass("ClaseHTML");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $path = SessionGetVar("rutaImagenes");
        $consulta = new DocumentosSQL();
        //echo "doc_id".$_REQUEST['doc_id']."empresa".SessionGetVar("EMPRESA");
        $vector = $consulta->BuscarDocumento($_REQUEST['doc_id'], SessionGetVar("EMPRESA"));

        $lista_prefijo_fi = $consulta->obtener_prefijo_fi();


        //var_dump($vector);
        $this->IncludeJS('RemoteScripting');
        $this->IncludeJS('ScriptRemoto/definir.js', $contenedor = 'app', $modulo = 'Cg_Documentos');
        $accion1 = ModuloGetURL('app', 'Cg_Documentos', 'user', 'ModificarDocumento');
        $this->salida .= ThemeAbrirTabla("DOCUMENTOS");
        $this->salida .= "                 <form name=\"modificar\" action=\"" . $accion1 . "\" method=\"post\">\n";
        $this->salida .= "                   <table width=\"40%\" class=\"modulo_table_list\" align=\"center\">\n";
        $this->salida .= "                     <tr class=\"modulo_table_list_title\" >\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"4\">\n";
        $this->salida .= "                         MODIFICAR DOCUMENTO";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                     </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td align=\"left\" colspan=\"2\">\n";
        $this->salida .= "                         PREFIJO";
        $this->salida .= "                       <big>" . $vector[0]['prefijo'] . "</big>";
        $this->salida .= "                         N?";
        $this->salida .= "                       <big>" . $vector[0]['numeracion'] . "</big>";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td align=\"left\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "                         NUMERO DE DIGITOS";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"left\">\n";
        $this->salida .= "                       <select name=\"num_dig\" class=\"select\">";
        for ($i = 4; $i < 17; $i++) {
            if ($vector[0]['numero_digitos'] == $i) {
                $this->salida .="                       <option value=\"" . $i . "\" selected>" . $i . "</option> \n";
            } else {
                $this->salida .="                       <option value=\"" . $i . "\">" . $i . "</option> \n";
            }
        }
        $this->salida.= "                       </select>\n";
        // $this->salida .= "                       <input type=\"text\" class=\"input-text\" name=\"num_dig\" size=\"7\" maxlength=\"4\"";
        //$this->salida .= "                       value=\"".$vector[0]['numero_digitos']."\">\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr>\n";
        $this->salida .= "                       <td class=\"modulo_table_list_title\" align=\"center\">\n";
        $this->salida .= "                         PERMITE CONTABILIZAR ?";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"left\" class=\"modulo_list_claro\">\n";
        if ($vector[0]['sw_contabiliza'] == '1') {
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"conta\" value=\"1\" checked><b>SI</b>\n";
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"conta\" value=\"0\" ><b>NO</b>\n";
        }
        if ($vector[0]['sw_contabiliza'] == '0') {
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"conta\" value=\"1\" ><b>SI</b>\n";
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"conta\" value=\"0\" checked><b>NO</b>\n";
        }
        $this->salida .= "                       </td>\n";
        $this->salida .= "                      </tr>\n";

        $this->salida .= "                      <tr>\n";
        $this->salida .= "                          <td class=\"modulo_table_list_title\" align=\"center\" > PREFIJO FI </td>\n";
        $this->salida .= "                          <td align=\"left\" class=\"modulo_list_claro\" >\n";
        $this->salida .= "                              <select name=\"prefijos_financiero_id\" class=\"select\">";
        $this->salida .= "                                  <option value='' >-- Seleccionar --</option>";

        foreach ($lista_prefijo_fi as $key => $value) {
            $selected = ($vector[0]['prefijos_financiero_id']==$value['id'])? " selected ": ' ';
            $this->salida .= "                              <option value='{$value['id']}' {$selected} >{$value['descripcion_completa']}</option>";
        }

        $this->salida .= "                              </select>";
        $this->salida .= "                          </td>\n";
        $this->salida .= "                      </tr>\n";

        $this->salida .= "                     <tr class=\"modulo_table_list_title\" >\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                         DESCRIPCION";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                          <TEXTAREA NAME=descri ROWS=2  COLS=40 Style=\"width:100%\" class=\"textarea\" value=\"\">" . $vector[0]['descripcion'] . "</TEXTAREA></td>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                        &nbsp;";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_table_list_title\"\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        //ECHO "jejeje".var_dump($_REQUEST['tip_doc']['tipo_doc_general_id']);
        if ($_REQUEST['tip_doc']['tipo_movimiento_id'] == 'FV') {
            $ban = "R";
            $this->salida .= "                         RESOLUCION";
        } else {
            $ban = "T";
            $this->salida .= "                         TEXTO1";
        }
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_oscuro\">\n";
        $this->salida .= "                       <td align=\"left\" colspan=\"2\">\n";
        if ($vector[0]['texto1'] != 'NULL')
            $this->salida .= "                          <TEXTAREA NAME=txt1 ROWS=3  COLS=50 Style=\"width:100%\" class=\"textarea\" value=\"\">" . $vector[0]['texto1'] . "</TEXTAREA>\n";
        else
            $this->salida .= "                          <TEXTAREA NAME=txt1 ROWS=3  COLS=50 Style=\"width:100%\" class=\"textarea\" value=\"\"></TEXTAREA>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                        &nbsp;";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                         TEXTO2";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td align=\"left\" colspan=\"2\">\n";
        if ($vector[0]['texto2'] != 'NULL')
            $this->salida .= "                          <TEXTAREA NAME=txt2 ROWS=3  COLS=50 Style=\"width:100%\" class=\"textarea\" value=\"\">" . $vector[0]['texto2'] . "</TEXTAREA></td>\n";
        else
            $this->salida .= "                          <TEXTAREA NAME=txt2 ROWS=3  COLS=50 Style=\"width:100%\" class=\"textarea\" value=\"\"></TEXTAREA></td>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                        &nbsp;";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_table_list_title\" >\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                         TEXTO3";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                     </tr>\n";
        $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td align=\"left\" colspan=\"2\">\n";
        if ($vector[0]['texto3'] != 'NULL')
            $this->salida .= "                          <TEXTAREA NAME=txt3 ROWS=3  COLS=50 Style=\"width:100%\" class=\"textarea\">" . $vector[0]['texto3'] . "</TEXTAREA></td>\n";
        else
            $this->salida .= "                          <TEXTAREA NAME=txt3 ROWS=3  COLS=50 Style=\"width:100%\" class=\"textarea\"></TEXTAREA></td>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $this->salida .= "                         MENSAJE";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        if ($vector[0]['mensaje'] != 'NULL')
            $this->salida .= "                          <TEXTAREA NAME=msj ROWS=2  COLS=40 Style=\"width:100%\" class=\"textarea\" >" . $vector[0]['mensaje'] . "</TEXTAREA></td>\n";
        else
            $this->salida .= "                          <TEXTAREA NAME=msj ROWS=2  COLS=40 Style=\"width:100%\" class=\"textarea\" ></TEXTAREA></td>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                  </table>\n";
        $this->salida .= "                <div id=\"ventanamodi\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">\n";
        $this->salida .= "                </div>\n";
        //   $vector=$consulta->ListarDocumentos($this->Datos['tipo_doc_general_id'],SessionGetVar("EMPRESA"));
        $this->salida .= "              </form>";
        //$MODIFICAR=ModuloGetURL('app','Cg_Documentos','user','ModificarDocumento',Array('doc_id'=>$vector[$i]['documento_id'],'tip_doc'=>$this->Datos));
        $accion35 = ModuloGetURL('app', 'Cg_Documentos', 'user', 'ListaDocumentos', Array('empresa' => SessionGetVar("EMPRESA"), 'tipo_dc' => $_REQUEST['tip_doc']));
        $this->salida .= "                 <table width=\"30%\" align=\"center\">\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                         <td  align=\"center\">\n";
        $this->salida .= "                          <form name=\"aceptar1\" action=\"" . $accion35 . "\" method=\"post\">\n";
        $this->salida .= "                           <input type=\"button\" class=\"input-submit\" name=\"actua\" value=\"Actualizar\" onclick=\"Validar1('" . $ban . "','" . $vector[0]['empresa_id'] . "','" . $vector[0]['documento_id'] . "')\">\n";
        $this->salida .= "                           <input type=\"hidden\" name=\"secact\" value=\"1\">\n";
        $this->salida .= "                           </form>\n";
        $this->salida .= "                         </td>\n";
        $this->salida .= "                         <td  align=\"center\">\n";
        $this->salida .= "                          <form name=\"volver\" action=\"" . $accion35 . "\" method=\"post\">\n";
        $this->salida .= "                           <input type=\"submit\" class=\"input-submit\" name=\"calact\" value=\"Cancelar\">\n";
        $this->salida .= "                           </form>\n";
        $this->salida .= "                         </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                  </table>\n";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /*     * ************************************************************************************
     * FUNCION QUE LISTA LAS OPCIONES DE PARAMETROS
     *
     * ************************************************************************************ */

    function MenuParametros() {
        $javaC = "<script>\n";
        $javaC .= "   var contenedor1=''\n";
        $javaC .= "   var titulo1=''\n";
        $javaC .= "   var hiZ = 2;\n";
        $javaC .= "   var DatosFactor = new Array();\n";
        $javaC .= "   var EnvioFactor = new Array();\n";
        $javaC .= "   function Iniciar2(tit)\n";
        $javaC .= "   {\n";
        //$javaC .= "       alert('hello');\n";
        $javaC .= "       contenedor1 = 'ContenedorPre';\n";
        $javaC .= "       titulo1 = 'tituloPre';\n";

        $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $javaC.= "        Capa = xGetElementById(contenedor1);\n";
        $javaC .= "       xResizeTo(Capa, 200, 'auto');\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
        $javaC .= "       ele = xGetElementById(titulo1);\n";
        $javaC .= "       xResizeTo(ele, 180, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC .= "       ele = xGetElementById('cerrarPre');\n";
        $javaC .= "       xResizeTo(ele, 20, 20);\n";
        $javaC .= "       xMoveTo(ele, 180, 0);\n";
        $javaC .= "   }\n";
        $javaC.= "</script>\n";
        $this->salida.= $javaC;
        $javaC1.= "<script>\n";
        $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     window.status = '';\n";
        $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
        $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
        $javaC1 .= "     ele.myTotalMX = 0;\n";
        $javaC1 .= "     ele.myTotalMY = 0;\n";
        $javaC1 .= "   }\n";

        $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     if (ele.id == titulo1) {\n";
        $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
        $javaC1 .= "     }\n";
        $javaC1 .= "     else {\n";
        $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $javaC1 .= "     }  \n";
        $javaC1 .= "     ele.myTotalMX += mdx;\n";
        $javaC1 .= "     ele.myTotalMY += mdy;\n";
        $javaC1 .= "   }\n";

        $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "   }\n";

        $javaC1.= "function MostrarCapa(Elemento)\n";
        $javaC1.= "{\n";
        $javaC1.= "    capita = xGetElementById(Elemento);\n";
        $javaC1.= "    capita.style.display = \"\";\n";
        $javaC1.= "}\n";

        $javaC1.= "function Cerrar(Elemento)\n";
        $javaC1.= "{\n";
        $javaC1.= "    capita = xGetElementById(Elemento);\n";
        $javaC1.= "    capita.style.display = \"none\";\n";
        $javaC1.= "}\n";

        $javaC1.= "</script>\n";
        $this->salida.= $javaC1;



        /*         * *****************************************************************************
         * Ventana emergente 3 aqui es cuando se modifica una cuenta. 
         * ******************************************************************************** */
        //conf('".$da."');

        /*         * *****************************************************************************
         * final ventana emergente 3 aqui es cuando se modifica una cuenta. 
         * ******************************************************************************** */
        $consulta = new DocumentosSQL();
        $this->CrearElementos();
        IncludeClass("ClaseHTML");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $path = SessionGetVar("rutaImagenes");
        $this->IncludeJS('RemoteScripting');
        $this->IncludeJS('ScriptRemoto/definir.js', $contenedor = 'app', $modulo = 'Cg_Documentos');
        $path = SessionGetVar("rutaImagenes");
        $ADICIONAR = ModuloGetURL('app', 'Cg_Documentos', 'user', 'AdicionarParametros', array('doc_id' => $_REQUEST['doc_id'], 'tip_doc' => $_REQUEST['tip_doc']));
        $ADICIONAR1 = ModuloGetURL('app', 'Cg_Documentos', 'user', 'MenuParametros', array('doc_id' => $_REQUEST['doc_id'], 'tip_doc' => $_REQUEST['tip_doc']));
        //$accion1=ModuloGetURL('app','Cg_Documentos','user','MenuParametros');
        $this->salida .= ThemeAbrirTabla("ADICIONAR PARAMETROS");
        //$vector=$consulta->BuscarDocumento($_REQUEST['doc_id'],SessionGetVar('EMPRESA'));
        $vector = $consulta->BuscarParametroDocumento($_REQUEST['doc_id'], SessionGetVar('EMPRESA'));
        $this->salida .= " <div id=\"ventana_parraf\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">\n";

        if ($_REQUEST['addup'] == 1 && $_REQUEST['agregado'] == 1) {
            $this->salida .= "              <div id=\"ventana_men_parra\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">\n";
            $this->salida .= "                Parametro Actualizado Correctamente";
            $this->salida .= "              </div>\n";
        }

        if ($_REQUEST['addx'] == 1 && $_REQUEST['agregado'] == 1) {
            $this->salida .= "              <div id=\"ventana_men_parra\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">\n";
            $this->salida .= "                Parametro Agregado Correctamente";
            $this->salida .= "              </div>\n";
        }

        if (count($vector) == 0) {
            $this->salida .= "              <div id=\"ventana_men_parra\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">\n";
            $this->salida .= "                ESTE DOCUMENTO NO TIENE PARAMETROS";
            $this->salida .= "              </div>\n";
        }
        $this->salida .= "              </div>\n";
        $this->salida .= "                   <table width=\"90%\" align=\"center\" >\n";
        $this->salida .= "                     <tr>\n";
        $this->salida .= "                       <td align=\"center\">\n";
        $this->salida .= "                        <a  title=\"Adicionar Parametro\" class=\"label_error\" href=\"" . $ADICIONAR . "\">ADICIONAR PARAMETROS</a>\n";
        //$this->salida .= "                       ADICIONAR PARAMETROS";   
        $this->salida .= "                       </td>\n";
        $this->salida .= "                     </tr>\n";
        $this->salida .= "                   </table>\n";
        if (count($vector) > 0) {
            $this->salida .= "                   <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "                     <tr id=\"intro\">\n";
            $this->salida .= "                       <td width=\"8%\" align=\"center\" class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       PARAMETROS";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"10%\" align=\"center\" class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       CUENTA";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"7%\" align=\"center\" class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       NATURALEZA";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"5%\" align=\"center\" class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       AGRUP CUENTA";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"25%\" align=\"center\" class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       TIPO CLIENTE";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"25%\" align=\"center\"class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       PLAN ID";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"10%\" align=\"center\" COLSPAN='2' class=\"modulo_table_list_title\">\n";
            $this->salida .= "                        ACCIONES";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                     </tr>\n";


            for ($i = 0; $i < count($vector); $i++) {

                $this->salida .= "                   <tr id=\"" . $i . "\"class=\"modulo_list_claro\">\n";
                $this->salida .= "                     <td width=\"10%\" align=\"center\">\n";
                $Q = $i + 1;
                $this->salida .= "                      PARAMETRO" . $Q . "";
                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td width=\"10%\" align=\"center\">\n";
                $this->salida .= "                       " . $vector[$i]['cuenta'] . ""; //$this->salida .= "                       PARAMETRO".$i."";   
                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td width=\"10%\" align=\"center\">\n";
                if ($vector[$i]['naturaleza'] == 'C') {
                    $this->salida .= "                       CREDITO";
                }
                if ($vector[$i]['naturaleza'] == 'D') {
                    $this->salida .= "                       DEBITO";
                }
                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td width=\"10%\" align=\"center\">\n";
                if ($vector[$i]['sw_agrupar_cuentas'] == '0') {
                    $this->salida .= "                       NO";
                }
                if ($vector[$i]['sw_agrupar_cuentas'] == '1') {
                    $this->salida .= "                       SI";
                }
                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td width=\"15%\" align=\"center\">\n";
                if ($vector[$i]['tipo_cliente'] != NULL) {
                    $vec1 = $consulta->BuscarNomCliente($vector[$i]['tipo_cliente']);
                    $this->salida .= "                      " . $vec1[0]['descripcion'] . "";
                }

                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td width=\"15%\" align=\"center\">\n";
                if ($vector[$i]['plan_id'] != NULL) {
                    $vec2 = $consulta->BuscarNomPLAN($vector[$i]['plan_id']);
                    $this->salida .= "                       " . $vec2[0]['plan_descripcion'] . "";
                }
                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td width=\"5%\" align=\"center\">\n";
                //$accion900=ModuloGetURL('app','Cg_Documentos','user','ListaDocumentos',array('tipo_dc'=>$_REQUEST['tip_doc'],'empresa'=>SessionGetVar("EMPRESA")));
                $MODIFICAR = ModuloGetURL('app', 'Cg_Documentos', 'user', 'AdicionarParametros', array('doc_id' => $_REQUEST['doc_id'], 'tip_doc' => $_REQUEST['tip_doc'], 'indy' => $vector[$i]['indice_automatico']));
                $this->salida .= "                        <a  title=\"Modificar Parametro\" class=\"label_error\" href=\"" . $MODIFICAR . "\">";
                $this->salida .= "                          <sub><img src=\"" . $path . "/images/edita.png\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
                $this->salida .= "                        </a>\n";
                $this->salida .= "                     </td>\n";
                $this->salida .= "                     <td width=\"5%\" align=\"center\">\n";
                // nAnular = "javascript:MostrarCapa('ContenedorVer'); VerDocu('".$vector[$i]['documento_id']."','".SessionGetVar("EMPRESA")."','".$_REQUEST['tipo_dc']['tipo_movimiento_id']."'); Iniciar2('Datos Documento');";
                $ELIMINAR = "javascript:Iniciar2('BORRAR PARAMETRO');MostrarCapa('ContenedorPre');"; // BorrarParametrodTabla('".$vector[$i]['indice_automatico']."','".$i."','".count($vector)."'); Iniciar2('CONFIRMACION');";
                //$ELIMINAR="javascript:BorrarParametrodTabla('".$vector[$i]['indice_automatico']."','".$i."');";
                $this->salida .= "                        <a  title=\"Eliminar Parametro\" class=\"label_error\" href=\"" . $ELIMINAR . "\">";
                $this->salida .= "                          <sub><img src=\"" . $path . "/images/delete2.gif\" border=\"0\" width=\"18\" height=\"18\"></sub>\n";
                $this->salida .= "                         </a>\n";
///////////////////////////
                $this->salida.="<div id='ContenedorPre' class='d2Container' style=\"display:none\">";
                $this->salida .="    <div id='tituloPre' class='draggable' style=\"text-transform: uppercase;\">hola</div>\n";
                $this->salida .="    <div id='cerrarPre' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorPre');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
                $this->salida .="    <div id='errorPre' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
                $this->salida .="    <div id='ContenidoPre'>\n";
                $this->salida .="              <div id=\"ventana_iparra\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">\n";
                $this->salida .="                ESTA SEGURO DE BORRAR ESTE PARAMETRO ";
                $this->salida .="              </div>\n";
                $this->salida .="              <br>\n";
                $this->salida .="                   <table width=\"30%\" align=\"center\" class=\"modulo_list_claro\">\n";
                $this->salida .="                     <tr>\n";
                $this->salida .="                       <td align=\"center\">\n";
                //$da=$datos[0]."-".$datos[1]."-".$datos[2];
                $this->salida .="                           <input type=\"button\" name=\"confi_si\" class=\"input-submit\" value=\"SI\" onclick=\"javascript:BorrarParametrodTabla('" . $vector[$i]['indice_automatico'] . "','" . $i . "','" . count($vector) . "');Cerrar('ContenedorPre');\">\n";
                $this->salida .="                       </td>\n";
                $this->salida .="                       <td align=\"center\">\n";
                //ECHO $vector[$i]['indice_automatico'];
                $this->salida .="                           <input type=\"button\" name=\"confi_no\" class=\"input-submit\" value=\"NO\" onclick=\"javascript: Cerrar('ContenedorPre');\">\n";
                $this->salida .="                       </td>\n";
                $this->salida .="                     </tr>\n";
                $this->salida .="                  </table>\n";
                $this->salida .="    </div>\n";
                $this->salida.="</div>";


///////////////////////////          
                $this->salida .= "                     </td>\n";
                $this->salida .= "                   </tr>\n";
            }

            $this->salida .= "                   </table>\n";
        }

        $this->salida .= "                    <table width=\"30%\" align=\"center\">\n";
        $this->salida .= "                     <tr>\n";
        $this->salida .= "                       <td align=\"center\">\n";
        //$accion900=ModuloGetURL('app','Cg_Documentos','user','ListaDocumentos',array('doc_id'=>$_REQUEST['doc_id'],'tip_doc'=>$_REQUEST['tip_doc']));
        $accion900 = ModuloGetURL('app', 'Cg_Documentos', 'user', 'ListaDocumentos', array('tipo_dc' => $_REQUEST['tip_doc'], 'empresa' => SessionGetVar("EMPRESA")));
        $this->salida .= "                          <form name=\"volver600\" action=\"" . $accion900 . "\" method=\"post\">\n";
        //$this->salida .= "                        <a  title=\"Eliminar Parametro\" class=\"label_error\" href=\"".$accion900."\">Volver</a>\n";
        $this->salida .= "                           <input type=\"submit\" name=\"add_canceli\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "                           <input type=\"hidden\" name=\"sali_pa\" value=\"1\">\n";
        $this->salida .= "                         </form>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                      </tr>\n";
        $this->salida .= "                    </table>\n";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /*     * ********************************************************************************
     * funcion que adiciona parametros contables  
     * *********************************************************************************** */

    function AdicionarParametros() {

        $this->CrearElementos();
        IncludeClass("ClaseHTML");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $path = SessionGetVar("rutaImagenes");
        //$this->SubMenu();
        //echo "que".$this->Datos['tipo_doc_general_id'];
        //ECHO "que11".SessionGetVar("EMPRESA");
        $consulta = new DocumentosSQL();
        $this->IncludeJS('RemoteScripting');
        $this->IncludeJS('ScriptRemoto/definir.js', $contenedor = 'app', $modulo = 'Cg_Documentos');
        $javaC = "<script>\n";
        $javaC .= "   var contenedor1=''\n";
        $javaC .= "   var titulo1=''\n";
        $javaC .= "   var hiZ = 2;\n";
        $javaC .= "   var DatosFactor = new Array();\n";
        $javaC .= "   var EnvioFactor = new Array();\n";

        $javaC .= "   function Iniciar(tit)\n";
        $javaC .= "   {\n";
        $javaC .= "       contenedor1 = 'ContenedorCapaAnular';\n";
        $javaC .= "       titulo1 = 'tituloAnul';\n";
        $javaC .= "       Capa = xGetElementById(contenedor1);\n";
        $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+79);\n";
        $javaC .= "       ele = xGetElementById(titulo1);\n";
        $javaC .= "       xResizeTo(ele, 280, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC .= "       ele = xGetElementById('cerrarAnul');\n";
        $javaC .= "       xResizeTo(ele, 20, 20);\n";
        $javaC .= "       xMoveTo(ele, 280, 0);\n";
        $javaC .= "   }\n";

        $javaC .= "   function Iniciar2(tit)\n";
        $javaC .= "   {\n";
        $javaC .= "       contenedor1 = 'ContenedorMod';\n";
        $javaC .= "       titulo1 = 'tituloMod';\n";
        $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $javaC.= "        Capa = xGetElementById(contenedor1);\n";
        $javaC .= "       xResizeTo(Capa, 600, 410);\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+79);\n";
        $javaC .= "       ele = xGetElementById(titulo1);\n";
        $javaC .= "       xResizeTo(ele, 580, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC .= "       ele = xGetElementById('cerrarMod');\n";
        $javaC .= "       xResizeTo(ele, 20, 20);\n";
        $javaC .= "       xMoveTo(ele, 580, 0);\n";
        $javaC .= "   }\n";
        $javaC .= "   function Iniciar3(tit)\n";
        $javaC .= "   {\n";
        $javaC .= "       contenedor1 = 'ContenedorCli';\n";
        $javaC .= "       titulo1 = 'tituloCli';\n";
        $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $javaC.= "        Capa = xGetElementById(contenedor1);\n";
        $javaC .= "       xResizeTo(Capa, 600, 410);\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+79);\n";
        $javaC .= "       ele = xGetElementById(titulo1);\n";
        $javaC .= "       xResizeTo(ele, 580, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC .= "       ele = xGetElementById('cerrarCli');\n";
        $javaC .= "       xResizeTo(ele, 20, 20);\n";
        $javaC .= "       xMoveTo(ele, 580, 0);\n";
        $javaC .= "   }\n";
        $javaC .= "   function Iniciar4(tit)\n";
        $javaC .= "   {\n";
        $javaC .= "       contenedor1 = 'ContenedorPlan';\n";
        $javaC .= "       titulo1 = 'tituloPlan';\n";
        $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $javaC.= "        Capa = xGetElementById(contenedor1);\n";
        $javaC .= "       xResizeTo(Capa, 700, 410);\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/8, xScrollTop()+55);\n";
        $javaC .= "       ele = xGetElementById(titulo1);\n";
        $javaC .= "       xResizeTo(ele, 680, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC .= "       ele = xGetElementById('cerrarPlan');\n";
        $javaC .= "       xResizeTo(ele, 20, 20);\n";
        $javaC .= "       xMoveTo(ele, 680, 0);\n";
        $javaC .= "   }\n";
        $javaC.= "</script>\n";
        $this->salida.= $javaC;
        //////////////////////////
        /*       $javaC .= "       contenedor1 = 'ContenedorVer';\n";
          $javaC .= "       titulo1 = 'tituloVer';\n";
          $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
          $javaC.= "        Capa = xGetElementById(contenedor1);\n";
          $javaC .= "       xResizeTo(Capa, 600, 'auto');\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
          $javaC .= "       ele = xGetElementById(titulo1);\n";
          $javaC .= "       xResizeTo(ele, 580, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
          $javaC .= "       ele = xGetElementById('cerrarVer');\n";
          $javaC .= "       xResizeTo(ele, 20, 20);\n";
          $javaC .= "       xMoveTo(ele, 580, 0);\n";
         */

        /////////////////////////   



        $javaC1 = "<script>\n";

        $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     window.status = '';\n";
        $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
        $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
        $javaC1 .= "     ele.myTotalMX = 0;\n";
        $javaC1 .= "     ele.myTotalMY = 0;\n";
        $javaC1 .= "   }\n";

        $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     if (ele.id == titulo1) {\n";
        $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
        $javaC1 .= "     }\n";
        $javaC1 .= "     else {\n";
        $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $javaC1 .= "     }  \n";
        $javaC1 .= "     ele.myTotalMX += mdx;\n";
        $javaC1 .= "     ele.myTotalMY += mdy;\n";
        $javaC1 .= "   }\n";

        $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "   }\n";

        $javaC1.= "function MostrarCapa(Elemento)\n";
        $javaC1.= "{\n;";
        $javaC1.= "    capita = xGetElementById(Elemento);\n";
        $javaC1.= "    capita.style.display = \"\";\n";
        $javaC1.= "}\n";

        $javaC1.= "function Cerrar(Elemento)\n";
        $javaC1.= "{\n";
        $javaC1.= "    capita = xGetElementById(Elemento);\n";
        $javaC1.= "    capita.style.display = \"none\";\n";
        $javaC1.= "}\n";



        $javaC1.= "</script>\n";
        $this->salida.= $javaC1;



        /*         * *****************************************************************************
         * Ventana emergente 3 aqui es cuando se modifica una cuenta. 
         * ******************************************************************************** */
        $this->salida.="<div id='ContenedorMod' class='d2Container' style=\"display:none\">";
        $this->salida .= "    <div id='tituloMod' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $this->salida .= "    <div id='cerrarMod' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorMod');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $this->salida .= "    <div id='errorMod' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    <div id='ContenidoMod'>\n";
        $this->salida .= "    </div>\n";
        $this->salida.="</div>";
        $this->salida.="<script language=\"javaScript\">
      function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }
      
           
      </script>";

        /*         * *****************************************************************************
         * Ventana emergente 4 aqui se muestra clientes. 
         * ******************************************************************************** */
        $this->salida.="<div id='ContenedorCli' class='d2Container' style=\"display:none\">";
        $this->salida .= "    <div id='tituloCli' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $this->salida .= "    <div id='cerrarCli' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCli');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $this->salida .= "    <div id='errorCli' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    <div id='ContenidoCli'>\n";
        $this->salida .= "    </div>\n";
        $this->salida.="</div>";
        $this->salida.="<script language=\"javaScript\">
      function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }
      
           
      </script>";
        /*         * *****************************************************************************
         * Ventana emergente 5 aqui se muestra clientes. 
         * ******************************************************************************** */
        $this->salida.="<div id='ContenedorPlan' class='d2Container' style=\"display:none\">";
        $this->salida .= "    <div id='tituloPlan' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $this->salida .= "    <div id='cerrarPlan' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorPlan');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $this->salida .= "    <div id='errorPlan' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "    <div id='ContenidoPlan'>\n";
        $this->salida .= "    </div>\n";
        $this->salida.="</div>";
        $this->salida.="<script language=\"javaScript\">
      function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }
      
           
      </script>";

        /*         * ************************************************************************************
         * final de la ventana3
         * ********************************************************************************* */

        $path = SessionGetVar("rutaImagenes");
        $accion1 = ModuloGetURL('app', 'Cg_Documentos', 'user', 'CrearDocumento');
        $this->salida .= ThemeAbrirTabla("DOCUMENTOS");
        $vector = $consulta->BuscarDocumento($_REQUEST['doc_id'], SessionGetVar('EMPRESA'));
        $this->salida .= "                 <form name=\"adicion\" action=\"" . $accion1 . "\" method=\"post\">\n";

        if (!empty($_REQUEST['indy'])) {
            $vector2 = $consulta->BuscarParametroDocumentoi($_REQUEST['doc_id'], SessionGetVar('EMPRESA'), $_REQUEST['indy']);
        }
//       else
//        {
//         $vector2=0;
//         echo "jaimeaa";
//        } 
        if (count($vector2) > 0) {
            $this->salida .= "                   <input type=\"hidden\" name=\"cuentax\" value=\"" . $vector2[0]['cuenta'] . "\">";

            if ($vector2[0]['tipo_cliente'] != NULL) {//echo "tim";
                $this->salida .= "                   <input type=\"hidden\" name=\"clientex\" value=\"" . $vector2[0]['tipo_cliente'] . "\">";
            } else {//echo "tum";
                $this->salida .= "                   <input type=\"hidden\" name=\"clientex\" value=\"0\">";
            }
            //echo "ram".$vector2[0]['plan_id']."ram";
            if ($vector2[0]['plan_id'] != NULL) {//echo "rum";
                $this->salida .= "                   <input type=\"hidden\" name=\"planx\" value=\"" . $vector2[0]['plan_id'] . "\">";
            } else {//echo "rim";
                $this->salida .= "                   <input type=\"hidden\" name=\"planx\" value=\"0\">";
            }
        } else {
            $this->salida .= "                   <input type=\"hidden\" name=\"cuentax\" value=\"0\">";
            $this->salida .= "                   <input type=\"hidden\" name=\"clientex\" value=\"0\">";
            $this->salida .= "                   <input type=\"hidden\" name=\"planx\" value=\"0\">";
        }
        $this->salida .= "                   <table width=\"45%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                       <td align=\"center\" colspan=\"4\">\n";
        $this->salida .= "                         ADICIONAR PARAMETROS CONTABLES";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                     </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td align=\"left\"colspan=\"2\" class=\"modulo_table_list_title\" Style=\"text-align:left\">\n";
        $this->salida .= "                        TIPO DOCUMENTO";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"left\" colspan=\"2\">\n";
        $vector1 = $consulta->BuscarDescripcion($vector[0]['tipo_doc_general_id']);
        $this->salida .= "                        " . strtoupper($vector1[0]['descripcion']) . "";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td align=\"left\"colspan=\"2\" class=\"modulo_table_list_title\" Style=\"text-align:left\">\n";
        $this->salida .= "                        DOCUMENTO ID";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"left\" colspan=\"2\">\n";
        $this->salida .= "                        " . strtoupper($vector[0]['documento_id']) . "";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td align=\"left\"colspan=\"2\" class=\"modulo_table_list_title\" Style=\"text-align:left\">\n";
        $this->salida .= "                         PREFIJO";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"left\" colspan=\"2\">\n";
        $this->salida .= "                        " . strtoupper($vector[0]['prefijo']) . "";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td align=\"left\" colspan=\"2\" class=\"modulo_table_list_title\" Style=\"text-align:left\">\n";
        $this->salida .= "                         DESCRIPCION";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"left\" colspan=\"2\">\n";
        $this->salida .= "                        " . strtoupper($vector[0]['descripcion']) . "";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td align=\"left\" colspan=\"2\" class=\"modulo_table_list_title\" Style=\"text-align:left\">\n";
        $this->salida .= "                         AGRUPAR CUENTAS";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"left\" colspan=\"2\">\n";
        if (count($vector2) > 0 && $vector2[0]['sw_agrupar_cuentas'] == '1') {
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"agrupa\" value=\"1\" checked ><b>SI</b>\n";
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"agrupa\" value=\"0\"><b>NO</b>\n";
        } elseif (count($vector2) > 0 && $vector2[0]['sw_agrupar_cuentas'] == '0') {
            $this->salida .= "                         <input type=\"radio\" name=\"agrupa\" value=\"1\"><b>SI</b>\n";
            $this->salida .= "                         <input type=\"radio\" name=\"agrupa\" value=\"0\" checked><b>NO</b>\n";
        } elseif (count($vector2) > 0 && $vector2[0]['sw_agrupar_cuentas'] == NULL) {
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"agrupa\" value=\"1\"><b>SI</b>\n";
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"agrupa\" value=\"0\"><b>NO</b>\n";
        } elseif (isset($vector2) == false || count($vector2) == 0) {
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"agrupa\" value=\"1\"><b>SI</b>\n";
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"agrupa\" value=\"0\"><b>NO</b>\n";
        }


        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td width=\"10%\" align=\"left\" colspan=\"2\"class=\"modulo_table_list_title\" Style=\"text-align:left\">\n";
        $this->salida .= "                         N? CUENTA";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"18%\" id=\"vin_cuen\" align=\"center\" class=\"normal_10AN\">\n";
        if (count($vector2) > 0)
            $this->salida .= "                " . $vector2[0]['cuenta'] . "";
        else
            $this->salida .= "                        &nbsp; &nbsp;&nbsp; ";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"17%\" align=\"center\">\n";
        // $this->salida .= "                        <input type=\"hidden\" name=\"secact\" value=\"1\">\n";
        $javaAccionAnular1 = "javascript:MostrarCapa('ContenedorMod'); cuentasm('0','0','1');Iniciar2('SELECCIONE UNA CUENTA');";
        $this->salida .= "                          <a  title=\"Vincular Cuenta\" class=\"label_error\" href=\"" . $javaAccionAnular1 . "\">VINCULAR CUENTA</a>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td align=\"left\" colspan=\"2\" class=\"modulo_table_list_title\" Style=\"text-align:left\">\n";
        $this->salida .= "                         NATURALEZA";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"left\" colspan='2' id=\"natur\">\n";
        if (count($vector2) > 0 && $vector2[0]['naturaleza'] == 'D') {
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"nat\" value=\"D\" checked><b>DEBITO</b>\n";
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"nat\" value=\"C\"><b>CREDITO</b>\n";
        } elseif (count($vector2) > 0 && $vector2[0]['naturaleza'] == 'C') {
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"nat\" value=\"D\" ><b>DEBITO</b>\n";
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"nat\" value=\"C\" checked><b>CREDITO</b>\n";
        } elseif (count($vector2) == 0) {
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"nat\" value=\"D\"><b>DEBITO</b>\n";
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"nat\" value=\"C\"><b>CREDITO</b>\n";
        }

        $this->salida .= "                       </td>\n";

        $this->salida .= "                    </tr>\n";
        $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                      <td align=\"center\" colspan=\"4\">\n";
        $this->salida .= "                         <table width=\"75%\" border='0' celspacing='0' celpadding='0' align=\"center\">\n";
        $this->salida .= "                           <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                              <td id=\"vin_cli\" align=\"center\">\n";
        $Cliente = "javascript:MostrarCapa('ContenedorCli'); VerCliente('0','0','1'); Iniciar3('SELECCIONE UN CLIENTE');";
        if ($vector2[0]['tipo_cliente'] != NULL) {
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"clint\" checked  onclick=\"" . $Cliente . "\">\n";
            //$this->salida .= "                                <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
        }
        else
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"clint\"  onclick=\"" . $Cliente . "\">\n";
        //$this->salida .= "                                <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
        $this->salida .= "                               </td>\n";
        $this->salida .= "                               <td  align=\"center\">\n";

        $this->salida .= "                                  <a  title=\"Seleccionar Cliente\" class=\"label_error\" href=\"" . $Cliente . "\">TIPO CLIENTE</a>\n";
        $this->salida .= "                                </td>\n";
        $this->salida .= "                                <td id=\"vin_plan\" align=\"center\">\n";
        $Plan = "javascript:MostrarCapa('ContenedorPlan'); VerPlan('0','0','1'); Iniciar4('SELECCIONE UN PLAN');";
        if ($vector2[0]['plan_id'] != NULL) {
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"clint\" checked onclick=\"" . $Plan . "\">\n";
            //$this->salida .= "                                 <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
        }
        else
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"clint\" onclick=\"" . $Plan . "\">\n";
        //$this->salida .= "                                 <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
        $this->salida .= "                                </td>\n";
        $this->salida .= "                                <td  align=\"center\">\n";

        $this->salida .= "                                  <a  title=\"Seleccionar Plan\" class=\"label_error\" href=\"" . $Plan . "\">TIPO PLAN</a>\n";
        $this->salida .= "                                </td>\n";
        $this->salida .= "                              <td id=\"vin_n\" align=\"center\">\n";
        if ($vector2[0]['plan_id'] != NULL || $vector2[0]['tipo_cliente'] != NULL)
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"clint\" onclick=\"javascript:Quitar()\">\n";
        //$this->salida .= "                                <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
        else
            $this->salida .= "                         <input type=\"radio\" class=\"input-text\" name=\"clint\" checked onclick=\"javascript:Quitar()\">\n";
        //$this->salida .= "                                <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
        $this->salida .= "                               </td>\n";
        $this->salida .= "                               <td  align=\"center\">\n";
        $this->salida .= "                                  <a  title=\"Anular seleccion\" class=\"label_error\" href=\"javascript:Quitar()\">NINGUNO</a>\n";
        $this->salida .= "                                </td>\n";
        $this->salida .= "                             </tr>\n";
        $this->salida .= "                         </table>\n";
        $this->salida .= "                      </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                   </table>\n";
        $this->salida .= "                  </form>";
        $accion3 = ModuloGetURL('app', 'Cg_Documentos', 'user', 'MenuParametros', Array('tip_doc' => $_REQUEST['tip_doc'], 'doc_id' => $_REQUEST['doc_id'], 'empresa' => SessionGetVar("EMPRESA")));
        $accion4 = ModuloGetURL('app', 'Cg_Documentos', 'user', 'MenuParametros', Array('tip_doc' => $_REQUEST['tip_doc'], 'doc_id' => $_REQUEST['doc_id'], 'empresa' => SessionGetVar("EMPRESA"), 'agregado' => 1));
        $this->salida .= "                    <table width=\"30%\" align=\"center\">\n";
        $this->salida .= "                     <tr>\n";
        $this->salida .= "                       <td align=\"center\">\n";
        $this->salida .= "                          <form name=\"Save\" action=\"" . $accion4 . "\" method=\"post\">\n";
        if (count($vector2) > 0) {
            $this->salida .= "                           <input type=\"button\" class=\"input-submit\" name=\"up\" value=\"Modificar\" onclick=\"UpParametro('" . $vector[0]['documento_id'] . "','" . SessionGetVar("EMPRESA") . "','" . $vector2[0]['indice_automatico'] . "')\">\n";
            $this->salida .= "                            <input type=\"hidden\" name=\"addup\" value=\"1\">";
        } else {
            $this->salida .= "                           <input type=\"button\" class=\"input-submit\" name=\"sv\" value=\"Guardar\" onclick=\"SaveParametro('" . $vector[0]['documento_id'] . "','" . SessionGetVar("EMPRESA") . "')\">\n";
            $this->salida .= "                            <input type=\"hidden\" name=\"addx\" value=\"1\">";
        }
        $this->salida .= "                          </form>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"center\">\n";
        //$ADICIONAR=ModuloGetURL('app','Cg_Documentos','user','AdicionarParametros',);
        $this->salida .= "                          <form name=\"volver500\" action=\"" . $accion3 . "\" method=\"post\">\n";
        $this->salida .= "                           <input type=\"submit\" name=\"add_cancel\" class=\"input-submit\" value=\"Cancelar\">\n";
        $this->salida .= "                         </form>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                      </tr>\n";
        $this->salida .= "                    </table>\n";
        $this->salida .= "                  <div id=\"adi\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">\n";
        $this->salida .= "                </div>\n";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

}

?>