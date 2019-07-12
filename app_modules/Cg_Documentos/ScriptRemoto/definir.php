<?php

/* * ************************************************************************************
 * $Id: definir.php,v 1.4 2007/02/06 20:42:43 jgomez Exp $ 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 * 
 * @author Jaime gomez
 * ************************************************************************************ */

$VISTA = "HTML";
$_ROOT = "../../../";

include "../../../classes/rs_server/rs_server.class.php";
include "../../../includes/enviroment.inc.php";
include "../../../app_modules/Cg_Documentos/classes/DocumentosSQL.class.php";
include "../../../classes/ClaseHTML/ClaseHTML.class.php";

class procesos_admin extends rs_server {
    /*     * ******************************************************************************
     * MUESTAR DATOS DEL DOCUMENTO
     * ******************************************************************************* */

    function InfoDocu($datos) {
        $path = SessionGetVar("rutaImagenes");
        $consulta = new DocumentosSQL();
        $Documento = $consulta->BuscarDocumento($datos[0], $datos[1], $datos[2]);
        $salida .= "                 <form name=\"crear\" action=\"" . $accion1 . "\" method=\"post\">\n";
        $salida .= "                   <table align=\"center\" width=\"80%\" class=\"modulo_table_list\">\n";
        /*$salida .= "                     <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\" colspan=\"2\">\n";
        $salida .= "                         CREAR DOCUMENTO";
        $salida .= "                       </td>\n";
        $salida .= "                     </tr>\n";*/
        $salida .= "                     <tr >\n";
        $salida .= "                       <td width=\"6%\"align=\"center\" class=\"modulo_table_list_title\">\n";
        $salida .= "                         PREFIJO";
        $salida .= "                       </td>\n";
        $salida .= "                       <td width=\"8%\"align=\"rigth\" class=\"modulo_list_claro\">\n";
        $salida .= "                        " . $Documento[0]['prefijo'] . "";
        $salida .= "                       </td>\n";
        $salida .= "                       <td width=\"28%\" align=\"center\" class=\"modulo_table_list_title\">\n";
        $salida .= "                         NUMERO DE DIGITOS";
        $salida .= "                       </td>\n";
        $salida .= "                       <td width=\"10%\" align=\"center\"class=\"modulo_list_claro\">\n";
        $salida .= "                         " . $Documento[0]['numero_digitos'] . "";
        $salida .= "                       </td>\n";
        $salida .= "                       <td width=\"35%\" align=\"left\" class=\"modulo_table_list_title\" >\n";
        $salida .= "                         PERMITE CONTABILIZAR ?";
        $salida .= "                       </td>\n";
        $salida .= "                       <td width=\"13%\" align=\"center\"  class=\"modulo_list_claro\" class=\"label\">\n";
        if ($Documento[0]['sw_contabiliza'] == '1') {
            $salida .= "                       SI";
        } elseif ($Documento[0]['sw_contabiliza'] == '0') {
            $salida .= "                       NO";
        }
        $salida .= "                       </td>\n";
        $salida .= "                      </tr>\n";
        $salida .= "                     <tr>\n";
        $salida .= "                       <td align=\"left\" colspan=\"1\" class=\"modulo_table_list_title\">\n";
        $salida .= "                         DESCRIPCION:";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">\n";
        $salida .= "                       " . $Documento[0]['descripcion'] . "";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        //echo "aquiii".$datos[2];
        if ($datos[2] == 'FV' && $Documento[0]['texto1'] != 'NULL') {
            $ban = 'R';
            $salida .= "                     <tr>\n";
            $salida .= "                       <td align=\"left\" colspan=\"1\" class=\"modulo_table_list_title\">\n";
            $salida .= "                         RESOLUCION :";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">\n";
            $salida .= "                        " . $Documento[0]['texto1'] . "";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
        } elseif ($Documento[0]['texto1'] != 'NULL') {
            $ban = 'T';
            $salida .= "                     <tr>\n";
            $salida .= "                       <td align=\"left\" colspan=\"1\" class=\"modulo_table_list_title\">\n";
            $salida .= "                         TEXTO1 :";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\" >\n";
            $salida .= "                        " . $Documento[0]['texto1'] . "";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
        }

        if ($Documento[0]['texto2'] != 'NULL') {
            $salida .= "                     <tr>\n";
            $salida .= "                       <td align=\"left\"colspan=\"1\" class=\"modulo_table_list_title\">\n";
            $salida .= "                         TEXTO2 :";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"left\" colspan=\"5\"  class=\"modulo_list_claro\" >\n";
            $salida .= "                       " . $Documento[0]['texto2'] . "";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
        }

        if ($Documento[0]['texto3'] != 'NULL') {
            $salida .= "                     <tr>\n";
            $salida .= "                       <td align=\"left\"colspan=\"1\" class=\"modulo_table_list_title\">\n";
            $salida .= "                         TEXTO3 :";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"left\"colspan=\"5\"  class=\"modulo_list_claro\" >\n";
            $salida .= "                         " . $Documento[0]['texto3'] . "";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
        }


        if ($Documento[0]['mensaje'] != 'NULL') {
            $salida .= "                     <tr>\n";
            $salida .= "                       <td align=\"left\" colspan=\"1\" class=\"modulo_table_list_title\">\n";
            $salida .= "                         MENSAJE :";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"left\" colspan=\"5\"  class=\"modulo_list_claro\" >\n";
            $salida .= "                         " . $Documento[0]['mensaje'] . "";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
        }

        $salida .= "                    <tr>\n";
        $salida .= "                       <td width=\"6%\"align=\"center\" class=\"modulo_table_list_title\">\n";
        $salida .= "                         PREFIJO FI";
        $salida .= "                       </td>\n";
        $salida .= "                       <td width=\"8%\"align=\"rigth\" class=\"modulo_list_claro\">\n";
        $salida .= "                        " . $Documento[0]['prefijo_fi'] . "";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";

        $salida .= "                     <tr>\n";
        $salida .= "                       <td align=\"center\" colspan=\"6\" class=\"modulo_list_claro\"\n";
        $salida .= "                           <input type=\"button\" name=\"cerrardia\" class=\"input-submit\" value=\"Cerrar\" onclick=\"javascript:Cerrar('ContenedorVer');\">\n";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                  </table>\n";
        $salida .= "                <div id=\"ventanacrear\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:13px;\">\n";
        $salida .= "                </div>\n";
        $salida .= "              </form>";


        return $salida;
    }

    /*     * ******************************************************************************
     * CambiarImagen del usuario 
     * ******************************************************************************* */

    function CambiarImagen($datos) {
        $path = SessionGetVar("rutaImagenes");
        $consulta = new PermisosSQL();
        $registra = new PermisosSQL();
        $estado = $consulta->ConsultarEstadoUsuario($datos[0]);

        //ECHO "AQUIYA".$estado[0]['sw_estado']."AQUIYA".$datos[0];
        if (count($estado) == 0) {
            return $salida = 1;
//            $salida = "                         <a title=\"CAMBIAR DE ESTADO\" href=\"javascript:MarcarUsuario('".$datos[0]."',usuarios1.numero".$datos[0].".value);\">\n";
//            $salida .= "                          <sub><img src=\"".$path."/images/activo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
//            $salida .= "                          <input type=\"hidden\" name=\"numero".$datos[0]."\" value=\"1\">";
//            $salida .= "                         <a>\n";
//            $InserEstado=$registra->ConsultarEstadoUsuario($datos[0]); 
        }

        if ($estado[0]['sw_estado'] == 0) {
            $salida .= "                         <a title=\"CAMBIAR DE ESTADO\" href=\"javascript:MarcarUsuario('" . $datos[0] . "',usuarios1.numero" . $datos[0] . ".value);\">\n";
            $salida .= "                          <sub><img src=\"" . $path . "/images/activo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
            $salida .= "                          <input type=\"hidden\" name=\"numero" . $datos[0] . "\" value=\"1\">";
            $salida .= "                         <a>\n";
            $ActuaEstado = $registra->ActuaEstadoUsuario($datos[0], 1);  //ConsultarEstadoUsuario($datos[0]); 
        }

        if ($estado[0]['sw_estado'] == 1) {
            $salida .= "                         <a title=\"CAMBIAR DE ESTADO\" href=\"javascript:MarcarUsuario('" . $datos[0] . "',usuarios1.numero" . $datos[0] . ".value);\">\n";
            $salida .= "                          <sub><img src=\"" . $path . "/images/inactivo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
            $salida .= "                          <input type=\"hidden\" name=\"numero" . $datos[0] . "\" value=\"0\">";
            $salida .= "                         <a>\n";
            $ActuaEstado = $registra->ActuaEstadoUsuario($datos[0], 0);
        }


        return $salida;
    }

    /*     * ******************************************************************************
      Inserta nuevos documentos
     * ******************************************************************************* */

    function GuarPar($datos) {
        $consulta = new DocumentosSQL();
        $registrar = new DocumentosSQL();
        $buscar = $consulta->buscar($datos[0]);
//       if(count($buscar)>0)
//        {
//          $borrar=$registrar->borrarpara($datos[0]); 
//        } 
        $ia = $consulta->NuevoIA();
        $resultado = $registrar->Parametros($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5], $datos[6], $ia[0]['nextval']);

        return $resultado;
    }

    /*     * ******************************************************************************
      Inserta nuevos documentos
     * ******************************************************************************* */

    function ModPar($datos) {
        $consulta = new DocumentosSQL();
        $registrar = new DocumentosSQL();
        $buscar = $consulta->BuscarParametroDocumentoi($datos[0], $datos[1], $datos[7]);
        //                                            doc_id,empresa_id,cuenta,naturaleza,agrupar,tipo_cliente,plan_id,ia
//        if(count($buscar)>0)
//         {
//           $borrar=$registrar->borrarpara($datos[2]); 
//         } 


        $resultado = $registrar->UpParametros($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5], $datos[6], $datos[7]);

        return $resultado;
    }

    /*     * ******************************************************************************
      Cambia de imagen
     * ******************************************************************************* */

    function check() {
        $path = SessionGetVar("rutaImagenes");
        $salida = "<sub><img src=\"" . $path . "/images/checksi.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
        $salida.="~" . "<sub><img src=\"" . $path . "/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
        $salida.="~" . "<sub><img src=\"" . $path . "/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
        return $salida;
    }

    /*     * ******************************************************************************
      Inserta nuevos documentos
     * ******************************************************************************* */

    function CrearDocumentosBD($datos) {

        $path = SessionGetVar("rutaImagenes");
        $consulta = new DocumentosSQL();
        $registrar = new DocumentosSQL();
        echo $datos[1];
        echo SessionGetVar('EMPRESA');
        $PREFIJO = $consulta->ConsultarPrefijo($datos[1], SessionGetVar('EMPRESA'));
        var_dump($PREFIJO);
        if (EMPTY($PREFIJO)) {
            $id = $consulta->NuevoId();
            $registrar = new DocumentosSQL();
            $resultado = $registrar->NueDocumento($id[0]['nextval'], SessionGetVar("EMPRESA"), $datos[0], $datos[1], "1", "1", $datos[2], $datos[3], $datos[4], $datos[5], $datos[6], $datos[7], $datos[8], $datos[9]);
            $audi = $consulta->AudiId();
            //date('j/n/Y')
            $Regaudi = $registrar->Auditoria($audi[0]['nextval'], $id[0]['nextval'], SessionGetVar("EMPRESA"), 'NOW()', UserGetUID());
            return $resultado;
        } else {
            echo "aa" . $PREFIJO[0]['empresa_id'];
            $conempresa = $consulta->Consultaempresa($PREFIJO[0]['empresa_id']);
            var_dump($conempresa);
            $cadena = "el prefijo '" . $datos[1] . "' Ya existe en la empresa '" . $conempresa[0]['razon_social'] . "'";
            return $cadena;
        }
    }

    function BorrarParra($datos) {
        $salida .="              <div id=\"ventana_iparra\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">\n";
        $salida .="                ESTA SEGURO DE BORRAR ESTE PARAMETRO ";
        $salida .="              </div>\n";
        $salida .="              <br>\n";
        $salida .="                   <table width=\"30%\" align=\"center\" class=\"modulo_list_claro\">\n";
        $salida .="                     <tr>\n";
        $salida .="                       <td align=\"center\">\n";
        $da = $datos[0] . "-" . $datos[1] . "-" . $datos[2];
        $salida .="                           <input type=\"button\" name=\"confi_si\" class=\"input-submit\" value=\"SI\" onclick=\"javascript:conf('" . $da . "');Cerrar('ContenedorPre');\">\n";
        $salida .="                       </td>\n";
        $salida .="                       <td align=\"center\">\n";
        $salida .="                           <input type=\"button\" name=\"confi_no\" class=\"input-submit\" value=\"NO\" onclick=\"Cerrar('ContenedorPre');\">\n";
        $salida .="                       </td>\n";
        $salida .="                     </tr>\n";
        $salida .="                  </table>\n";
        return $salida;
    }

    function ConPi($datos) {
        $registrar = new DocumentosSQL();
        $borrar = $registrar->borrarpara($datos[0]);
        return $borrar;
    }

    /*     * ******************************************************************************
      actualiza cuentas
     * ******************************************************************************* */

    function ActDocumentosBD($datos) {  //echo "actua";
        $path = SessionGetVar("rutaImagenes");
        $consulta = new DocumentosSQL();
        $Registrar = new DocumentosSQL();
        $resultado = $consulta->UpDocumento($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5], $datos[6], $datos[7], $datos[8], $datos[9]);
        $audi = $consulta->AudiId();
        //date('j/n/Y')
        $Regaudi = $Registrar->Auditoria($audi[0]['nextval'], $datos[1], $datos[0], 'NOW()', UserGetUID());
        return $resultado;
    }

    /*     * ******************************************************************************
      vINCULAR CUENTA
     * ******************************************************************************* */

    function MostrarCuentas($datos) { //echo "actua";
        $path = SessionGetVar("rutaImagenes");
        $consulta = new DocumentosSQL();
        //echo "jejejea".$datos[0]."aaaa".$datos[1];
        $vector = $consulta->BuscarCuentasStip($datos[0], $datos[1], $datos[2], SessionGetVar("EMPRESA"));
        //echo "oyea".count($vector);
        $salida .= "               <div id=\"ventana_cuentasx\">\n";
        $salida .= "                <form name=\"cuentasx\" action =\"\">\n";
        $salida .= "                  <table width=\"90%\"  align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td align=\"center\" colspan='3'>\n";
        $salida .= "                         BUSCADOR DE CUENTAS ";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $salida .= "                       <td width=\"35%\" align=\"center\">\n";
        $salida .= "                        TIPO DE BUSQUEDA <select name=\"tip_bus\" class=\"select\" id='tip_bus' onchange=\"Ponercuentas(cuentasx.tip_bus.value)\" >";
        if ($datos[0] == '1' || $datos[0] == '0') {
            $salida .= "                       <option value=\"1\" selected>Sea igual a</option> \n";
        } else {
            $salida .= "                       <option value=\"1\">Sea igual a</option> \n";
        }

        if ($datos[0] == '2') {
            $salida .= "                       <option value=\"2\" selected>Empiece Por</option> \n";
        } else {
            $salida .= "                       <option value=\"2\">Empiece Por</option> \n";
        }

        if ($datos[0] == '3') {
            $salida .= "                       <option value=\"3\" selected>Entre</option> \n";
        } else {
            $salida .= "                       <option value=\"3\">Entre</option> \n";
        }

        if ($datos[0] == '4') {
            $salida .= "                       <option value=\"4\" selected>TODAS</option> \n";
        } else {
            $salida .= "                       <option value=\"4\">TODAS</option> \n";
        }


        $salida .= "                       </select>\n";
        $salida .= "                       </td>\n";
        $salida .= "                       <td width=\"40%\" align=\"center\" id=\"dos\"=>\n";
        if ($datos[1] != '0' && $datos[0] != '3') {
            $salida .= "                         CUENTA <input type=\"text\" class=\"input_text\" name=\"buscar\" id=\"buscar\" maxlength=\"52\" size\"52\" value=\"" . $datos[1] . "\" onkeypress=\"return acceptNum(event)\"";
        } elseif ($datos[1] != '0' && $datos[0] == '3') {
            list($elemento1, $elemento2) = explode("-", $datos[1]);
            $salida .="RANGO1 <input type=\"text\" class=\"input_text\" name=\"buscar\" maxlength=\"10\" size\"10\" value=\"" . $elemento1 . "\" onkeypress=\"return acceptNum(event)\"> \n";
            $salida .="RANGO2 <input type=\"text\" class=\"input_text\" name=\"buscar1\"maxlength=\"40\" size\"30\" value=\"" . $elemento2 . "\" onkeypress=\"return acceptNum(event)\">";
            //$salida .= "                       <input type=\"hidden\" name=\"buscar\"value=\"0\""; 
        } elseif ($datos[1] == '4') {
            $salida .= "                       <input type=\"hidden\" name=\"buscar\"value=\"0\"";
        } else {
            $salida .= "                         CUENTA <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" value=\"\" onkeypress=\"return acceptNum(event)\"";
        }
        $salida .= "                       </td>\n";
        $salida .= "                       <td width=\"15%\" align=\"center\">\n";
        $salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"boton_bus\" value=\"BUSCAR\" onclick=\"empresaboton(cuentasx.tip_bus.value,cuentasx.buscar.value,'1')\">\n";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                 </table>\n";
        $salida .= "                </form>\n";
        $salida .= "                <br>\n";
        if (count($vector) == 0) {
            $salida .= "               <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            $salida .= "                No se encontraron resultados con ese tipo de descripción";
            $salida .= "                </div>\n";
        } else {
            $salida .= "               <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            $salida .= "                </div>\n";
            $salida .= "                 <form name=\"adicionar\">\n";
            $salida .= "                 <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        <a title='SELECCIONAR'>SL<a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"13%\">\n";
            $salida .= "                        CUENTA Nº";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\"width=\"51%\">\n";
            $salida .= "                        DESCRIPCION";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        <a title='TIPO'>TP<a> ";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        <a title='NATURALEZA'>NAT<a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        <a title='CENTRO DE COSTO'>CC<a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        <a title='TERCEROS'>TER<a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        <a title='ESTADO ACTIVO'>ACT<a>";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        <a title='DOCUMENTO CRUCE'>DC<a>";
            $salida .= "                      </td>\n";

            $salida .= "                    </tr>\n";
            for ($i = 0; $i < count($vector); $i++) {
                $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); Establece('" . $vector[$i]['cuenta'] . "-" . $vector[$i]['sw_naturaleza'] . "');\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                //$this->salida .= "                    <tr class=\"\" >\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                         <a title='SELECCIONAR'>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/ok.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                $salida .= "                         </a>\n";
                $salida .= "                      </td>\n";
                if ($vector[$i]['sw_cuenta_movimiento'] == 0) {
                    $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                    $salida .= "                     " . $vector[$i]['cuenta'] . "";
                } else {
                    $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                    $salida .= "                     " . $vector[$i]['cuenta'] . "";
                }
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                        " . $vector[$i]['descripcion'];
                $salida .= "                      </td>\n";
                if ($vector[$i]['sw_cuenta_movimiento'] == 1) {
                    $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
                    $salida .= "                         M";
                } else {
                    $salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
                    $salida .= "                         T";
                }
                $salida .= "                      </td>\n";
                if ($vector[$i]['sw_naturaleza'] == 'C') {
                    $salida .= "                      <td align=\"center\">\n";
                    $salida .= "                         C";
                } else {
                    if ($vector[$i]['sw_naturaleza'] == 'D') {
                        $salida .= "                      <td align=\"center\">\n";
                        $salida .= "                         D";
                    } else {
                        $salida .= "                      <td align=\"center\">\n";
                        $salida .= "                         ";
                    }
                }
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                if ($vector[$i]['sw_centro_costo'] == 1) {
                    $salida .= "                         <a>\n";
                    $salida .= "                          <sub><img src=\"" . $path . "/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                    $salida .= "                         <a>\n";
                }
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                if ($vector[$i]['sw_tercero'] != '0') {
                    $salida .= "                         <a>\n";
                    $salida .= "                          <sub><img src=\"" . $path . "/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                    $salida .= "                         <a>\n";
                }
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                if ($vector[$i]['sw_estado'] == 0) {
                    $salida .= "                         <a>\n";
                    $salida .= "                          <sub><img src=\"" . $path . "/images/delete.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                    $salida .= "                         <a>\n";
                } elseif ($vector[$i]['sw_estado'] >= 1) {
                    $salida .= "                         <a>\n";
                    $salida .= "                          <sub><img src=\"" . $path . "/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                    $salida .= "                         <a>\n";
                }
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                if ($vector[$i]['sw_documento_cruce'] >= 1) {
                    $salida .= "                         <a>\n";
                    $salida .= "                          <sub><img src=\"" . $path . "/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                    $salida .= "                         <a>\n";
                }
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
            }
            $salida .= "</table>\n";
            $salida .= "</form>\n";
            // $accion3=ModuloGetURL('app','Cg_PlanesCuentas','user','FormaMenu');
            $op = "1";
            $slc = $consulta->ContarCuentasStip($datos[0], $datos[1], SessionGetVar("EMPRESA"));
            //echo "yo".$slc[0]['count']."yo";
            $salida .= "" . $this->ObtenerPaginado($datos[2], $path, $slc, $op, $datos[0], $datos[1]);
        }
        $salida .= "      </div>\n";
        return $salida;
    }

    /*     * ******************************************************************************
      LISTA DE CLIENTES
     * ******************************************************************************* */

    function Buscadorcli($datos) { //echo "si";
        $path = SessionGetVar("rutaImagenes");
        $consulta = new DocumentosSQL();
        $vector = $consulta->BuscarClientesStip($datos[0], $datos[1], $datos[2]);

        $salida .= "                  <div id=\"ventana_clientesx\">\n";
        $salida .= "                  <form name=\"buscarcliente\">\n";
        $salida .= "                   <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td align=\"center\" colspan='3'>\n";
        $salida .= "                         BUSCADOR DE CLIENTES";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        TIPO DE BUSQUEDA <select name=\"tip_bus\" class=\"select\" id='tip_bus' onchange=\"Ponercli(buscarcliente.tip_bus.value)\">";
        if ($datos[0] == '1' || $datos[0] == '0') {
            $salida .= "                       <option value=\"1\" selected >Tipo cliente</option> \n";
        } else {
            $salida .= "                       <option value=\"1\" >Tipo cliente</option> \n";
        }
        if ($datos[0] == '2') {
            $salida .= "                       <option value=\"2\" selected>Descripcion</option> \n";
        } else {
            $salida .= "                       <option value=\"2\">Descripcion</option> \n";
        }
        if ($datos[0] == '3') {
            $salida .= "                       <option value=\"3\" selected>regimen_id</option> \n";
        } else {
            $salida .= "                       <option value=\"3\" >regimen_id</option> \n";
        }
        if ($datos[0] == '4') {
            $salida .= "                       <option value=\"4\"selected>TODOS</option> \n";
        } else {
            $salida .= "                       <option value=\"4\">TODOS</option> \n";
        }
        $salida .= "                       </select>\n";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" id=\"dos\"=>\n";
        if ($datos[1] != '0') {
            $salida .= "                         DESCRIPCION <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" value=\"" . $datos[1] . "\" onkeypress=\"return acceptm(event)\">";
        } elseif ($datos[0] == '4') {
            $salida .= "  <input type=\"hidden\" name=\"buscar\" value=\"0\"";
        } else {
            $salida .= "                         DESCRIPCION <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" value=\"\" onkeypress=\"return acceptm(event)\"";
        }
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"boton_bus\" value=\"BUSCAR\" onclick=\"Buscadorclie(buscarcliente.tip_bus.value,buscarcliente.buscar.value,'1')\">\n";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                 </table>\n";
        $salida .= "                </form>\n";
        $salida .= "                <br>\n";
        if (count($vector) == 0) {
            $salida .= "               <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            $salida .= "                No se encontraron resultados con ese tipo de descripción";
            $salida .= "                </div>\n";
        } else {

            $salida .= "                 <form name=\"clientes\">\n";
            $salida .= "                 <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td align=\"center\" width=\"5%\">\n";
            $salida .= "                         SL";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width=\"13%\">\n";
            $salida .= "                         TIPO CLIENTE";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\"width=\"51%\">\n";
            $salida .= "                         DESCRIPCION";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width=\"5%\">\n";
            $salida .= "                         REGIMEN_ID";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width=\"5%\">\n";
            $salida .= "                         CUENTA";
            $salida .= "                       </td>\n";

            $salida .= "                    </tr>\n";
            for ($i = 0; $i < count($vector); $i++) {
                $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"Establececli('" . $vector[$i]['tipo_cliente'] . "');\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                         <a title='SELECCIONAR'>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/ok.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                $salida .= "                         </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                $salida .= "                     " . $vector[$i]['tipo_cliente'] . "";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                        " . strtoupper($vector[$i]['descripcion']);
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
                $salida .= "                        " . $vector[$i]['regimen_id'] . "";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                        " . $vector[$i]['cuenta'] . "";
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
            }
            $salida .= "                </table>\n";
            $salida .= "              </form>\n";
            $op = "1";
            $slc = $consulta->ContarClientesStip($datos[0], strtoupper($datos[1]));
            //echo "yo".$slc[0]['count']."yo";
            $salida .= "" . $this->ObtenerPaginadocli($datos[2], $path, $slc, $op, $datos[0], $datos[1]);
        }
        $salida .= "           </div>\n";
        return $salida;
    }

    /*     * ******************************************************************************
      buscador de planes
     * ******************************************************************************* */

    function Buscadorplan($datos) {
        //echo "si";
        $path = SessionGetVar("rutaImagenes");
        $consulta = new DocumentosSQL();
        $vector = $consulta->BuscarPlanesStip($datos[0], strtoupper($datos[1]), $datos[2], SessionGetVar("EMPRESA"));
        //echo $datos[0]."ha".$datos[1]."ha".$datos[2]."ha"; 
        //echo count($vector);
        $salida .= "                  <div id=\"ventana_planesx\">\n";
        $salida .= "                <form name=\"buscarplan\">\n";
        $salida .= "                  <table width=\"101%\"  align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td align=\"center\" colspan='3'>\n";
        $salida .= "                         BUSCADOR DE PLANES";
        $salida .= "                      </td>\n";
        $salida .= "                   </tr>\n";
        $salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $salida .= "                       <td width=\"30%\" align=\"center\">\n";
        $salida .= "                        TIPO DE BUSQUEDA <select name=\"tip_bus\" class=\"select\" id=\"tip_bus\" onchange=\"Ponerlo(buscarplan.tip_bus.value)\">";
        if ($datos[0] == '0' || $datos[0] == '1') {
            $salida .= "                       <option value=\"1\" selected>Plan id</option> \n";
        } else {
            $salida .= "                       <option value=\"1\">Plan id</option> \n";
        }
        if ($datos[0] == '2') {
            $salida .= "                       <option value=\"2\" selected>Descripcion</option> \n";
        } else {
            $salida .= "                       <option value=\"2\">Descripcion</option> \n";
        }
        if ($datos[0] == '3') {
            $salida .= "                       <option value=\"3\" selected>Nº Contrato</option> \n";
        } else {
            $salida .= "                       <option value=\"3\">Nº Contrato</option> \n";
        }
        if ($datos[0] == '4') {
            $salida .= "                       <option value=\"4\" selected>Tercero id</option> \n";
        } else {
            $salida .= "                       <option value=\"4\">Tercero id</option> \n";
        }
        if ($datos[0] == '5') {
            $salida .= "                       <option value=\"5\" selected>Tipo Plan</option> \n";
        } else {
            $salida .= "                       <option value=\"5\">Tipo Plan</option> \n";
        }
        if ($datos[0] == '6') {
            $salida .= "                       <option value=\"6\" selected>TODOS</option> \n";
        } else {
            $salida .= "                       <option value=\"6\">TODOS</option> \n";
        }

        $salida .= "                       </select>\n";
        $salida .= "                       </td>\n";
        $salida .= "                       <td width=\"46%\" align=\"left\" id=\"dos\">\n";
        if ($datos[1] != '0') {
            $salida .= "                          DESCRIPCION <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" value=\"" . $datos[1] . "\"onkeypress=\"return acceptm(event)\">";
        } elseif ($datos[0] == '6') {
            $salida .= "                      <input type=\"hidden\" name=\"buscar\"value=\"0\"";
        } else {
            $salida .= "                          DESCRIPCION <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" value=\"\"onkeypress=\"return acceptm(event)\">";
        }
        $salida .= "                       </td>\n";
        $salida .= "                       <td width=\"20%\"align=\"center\">\n";
        $salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"boton_bus\" value=\"BUSCAR\" onclick=\"SalirBuscarp(buscarplan.tip_bus.value,buscarplan.buscar.value,'1')\">\n";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                 </table>\n";
        $salida .= "                </form>\n";
        $salida .= "                <br>\n";
        // echo "ej".$datos[0]."ej".$datos[1]."ej".$datos[2]."ej".SessionGetVar("EMPRESA")."ej"; 
        if (count($vector) == 0) {
            $salida .= "               <div id=\"errornocli\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            $salida .= "                No se encontraron resultados con ese tipo de descripción";
            $salida .= "                </div>\n";
        } else {
            $salida .= "               <div id=\"errornocli\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            $salida .= "                </div>\n";
            $salida .= "                 <form name=\"Planes\">\n";
            $salida .= "                 <table width=\"101%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td align=\"center\" width=\"5%\">\n";
            $salida .= "                         SL";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width=\"10%\">\n";
            $salida .= "                         PLAN ID";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\"width=\"47%\">\n";
            $salida .= "                         DESCRIPCION";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width=\"5%\">\n";
            $salida .= "                         IDENTIFICACION";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width=\"5%\">\n";
            $salida .= "                         TERCERO ID";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width=\"16%\">\n";
            $salida .= "                         Nº CONTRATO";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width=\"13%\">\n";
            $salida .= "                         TIPO PLAN";
            $salida .= "                       </td>\n";

            $salida .= "                    </tr>\n";
            for ($i = 0; $i < count($vector); $i++) {
                $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"Estableceplan('" . $vector[$i]['plan_id'] . "')\"  onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                         <a title='SELECCIONAR'>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/ok.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                $salida .= "                         </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                $salida .= "                     " . $vector[$i]['plan_id'] . "";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                        " . strtoupper($vector[$i]['plan_descripcion']);
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
                $salida .= "                        " . $vector[$i]['tipo_tercero_id'] . "";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                        " . $vector[$i]['tercero_id'] . "";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                        " . $vector[$i]['num_contrato'] . "";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";

                if ($vector[$i]['sw_tipo_plan'] == 0) {
                    $salida .= "CLIENTE";
                } elseif ($vector[$i]['sw_tipo_plan'] == 1) {
                    $salida .= "SOAT";
                } elseif ($vector[$i]['sw_tipo_plan'] == 2) {
                    $salida .= "PARTICULAR";
                } elseif ($vector[$i]['sw_tipo_plan'] == 3) {
                    $salida .= "CAPITADO";
                }

                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
            }
            $salida .= "                   </table>\n";
            $op = "1";
            $slc = $consulta->ContarPlanesStip($datos[0], strtoupper($datos[1]), SessionGetVar("EMPRESA"));
            //echo "yo".$slc[0]['count']."yo";

            $salida .= "" . $this->ObtenerPaginadoplan($datos[2], $path, $slc, $op, $datos[0], $datos[1]);
        }
        $salida .= "                  </div>\n";
        return $salida;
    }

    /*     * ******************************************************************************
     * para mostrar la tabla de clientes
     * ******************************************************************************* */

    function ObtenerPaginadocli($pagina, $path, $slc, $op, $tipo, $plan) {

        //echo "io";
        $TotalRegistros = $slc[0]['count'];
        $TablaPaginado = "";

        if ($limite == null) {
            $uid = UserGetUID();
            $LimitRow = intval(GetLimitBrowser());
        } else {
            $LimitRow = $limite;
        }
        if ($TotalRegistros > 0) {
            $columnas = 1;
            $NumeroPaginas = intval($TotalRegistros / $LimitRow);

            if ($TotalRegistros % $LimitRow > 0) {
                $NumeroPaginas++;
            }

            $Inicio = $pagina;
            if ($NumeroPaginas - $pagina < 9) {
                $Inicio = $NumeroPaginas - 9;
            } elseif ($pagina > 1) {
                $Inicio = $pagina - 1;
            }

            if ($Inicio <= 0) {
                $Inicio = 1;
            }

            $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

            $TablaPaginado .= "<tr>\n";
            if ($NumeroPaginas > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Páginas:</td>\n";
                if ($pagina > 1) {
                    $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                    $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:CrearVariablescli('" . $tipo . "','" . $plan . "','1')\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                    $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                    $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:CrearVariablescli('" . $tipo . "','" . $plan . "','" . ($pagina - 1) . "')\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                    $TablaPaginado .= "   </td>\n";
                    $columnas +=2;
                }
                $Fin = $NumeroPaginas + 1;
                if ($NumeroPaginas > 10) {
                    $Fin = 10 + $Inicio;
                }

                for ($i = $Inicio; $i < $Fin; $i++) {
                    if ($i == $pagina) {
                        $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                    } else {
                        $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:CrearVariablescli('" . $tipo . "','" . $plan . "','" . $i . "')\">" . $i . "</a></td>\n";
                    }
                    $columnas++;
                }
            }
            if ($pagina < $NumeroPaginas) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:CrearVariablescli('" . $tipo . "','" . $plan . "','" . ($pagina + 1) . "')\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:CrearVariablescli('" . $tipo . "','" . $plan . "','" . $NumeroPaginas . "')\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
            $aviso .= "     Página&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
            $aviso .= "   </tr>\n";

            if ($op == 2) {
                $TablaPaginado .= $aviso;
            } else {
                $TablaPaginado = $aviso . $TablaPaginado;
            }
        }

        $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
        $Tabla .= $TablaPaginado;
        $Tabla .= "</table>";

        return $Tabla;
    }

    /*     * ******************************************************************************
     * para mostrar la tabla de vinculacion de cuentas con paginador incluido
     * ******************************************************************************* */

    function ObtenerPaginadoplan($pagina, $path, $slc, $op, $tipo, $plan) {


        $TotalRegistros = $slc[0]['count'];
        ;
        $TablaPaginado = "";

        if ($limite == null) {
            $uid = UserGetUID();
            $LimitRow = intval(GetLimitBrowser());
        } else {
            $LimitRow = $limite;
        }
        if ($TotalRegistros > 0) {
            $columnas = 1;
            $NumeroPaginas = intval($TotalRegistros / $LimitRow);

            if ($TotalRegistros % $LimitRow > 0) {
                $NumeroPaginas++;
            }

            $Inicio = $pagina;
            if ($NumeroPaginas - $pagina < 9) {
                $Inicio = $NumeroPaginas - 9;
            } elseif ($pagina > 1) {
                $Inicio = $pagina - 1;
            }

            if ($Inicio <= 0) {
                $Inicio = 1;
            }

            $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

            $TablaPaginado .= "<tr>\n";
            if ($NumeroPaginas > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Páginas:</td>\n";
                if ($pagina > 1) {
                    $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                    $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:CrearVariablesplan('" . $tipo . "','" . $plan . "','1')\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                    $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                    $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:CrearVariablesplan('" . $tipo . "','" . $plan . "','" . ($pagina - 1) . "')\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                    $TablaPaginado .= "   </td>\n";
                    $columnas +=2;
                }
                $Fin = $NumeroPaginas + 1;
                if ($NumeroPaginas > 10) {
                    $Fin = 10 + $Inicio;
                }

                for ($i = $Inicio; $i < $Fin; $i++) {
                    if ($i == $pagina) {
                        $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                    } else {
                        $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:CrearVariablesplan('" . $tipo . "','" . $plan . "','" . $i . "')\">" . $i . "</a></td>\n";
                    }
                    $columnas++;
                }
            }
            if ($pagina < $NumeroPaginas) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:CrearVariablesplan('" . $tipo . "','" . $plan . "','" . ($pagina + 1) . "')\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:CrearVariablesplan('" . $tipo . "','" . $plan . "','" . $NumeroPaginas . "')\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
            $aviso .= "     Página&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
            $aviso .= "   </tr>\n";

            if ($op == 2) {
                $TablaPaginado .= $aviso;
            } else {
                $TablaPaginado = $aviso . $TablaPaginado;
            }
        }

        $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
        $Tabla .= $TablaPaginado;
        $Tabla .= "</table>";

        return $Tabla;
    }

    /*     * ******************************************************************************
     * para mostrar la tabla de vinculacion de cuentas con paginador incluido
     * ******************************************************************************* */

    function ObtenerPaginado($pagina, $path, $slc, $op, $tip_bus, $elemento) {


        $TotalRegistros = $slc[0]['count'];
        $TablaPaginado = "";

        if ($limite == null) {
            $uid = UserGetUID();
            $LimitRow = intval(GetLimitBrowser());
        } else {
            $LimitRow = $limite;
        }
        if ($TotalRegistros > 0) {
            $columnas = 1;
            $NumeroPaginas = intval($TotalRegistros / $LimitRow);

            if ($TotalRegistros % $LimitRow > 0) {
                $NumeroPaginas++;
            }

            $Inicio = $pagina;
            if ($NumeroPaginas - $pagina < 9) {
                $Inicio = $NumeroPaginas - 9;
            } elseif ($pagina > 1) {
                $Inicio = $pagina - 1;
            }

            if ($Inicio <= 0) {
                $Inicio = 1;
            }

            $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" ";

            $TablaPaginado .= "<tr>\n";
            if ($NumeroPaginas > 1) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Páginas:</td>\n";
                if ($pagina > 1) {
                    $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                    $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:CrearVariables('" . $tip_bus . "','" . $elemento . "','1')\" title=\"primero\"><img src=\"" . $path . "/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                    $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                    $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:CrearVariables('" . $tip_bus . "','" . $elemento . "','" . ($pagina - 1) . "')\" title=\"anterior\"><img src=\"" . $path . "/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                    $TablaPaginado .= "   </td>\n";
                    $columnas +=2;
                }
                $Fin = $NumeroPaginas + 1;
                if ($NumeroPaginas > 10) {
                    $Fin = 10 + $Inicio;
                }

                for ($i = $Inicio; $i < $Fin; $i++) {
                    if ($i == $pagina) {
                        $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>" . $i . "</b></td>\n";
                    } else {
                        $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:CrearVariables('" . $tip_bus . "','" . $elemento . "','" . $i . "')\">" . $i . "</a></td>\n";
                    }
                    $columnas++;
                }
            }
            if ($pagina < $NumeroPaginas) {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:CrearVariables('" . $tip_bus . "','" . $elemento . "','" . ($pagina + 1) . "')\" title=\"siguiente\"><img src=\"" . $path . "/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:CrearVariables('" . $tip_bus . "','" . $elemento . "','" . $NumeroPaginas . "')\" title=\"ultimo\"><img src=\"" . $path . "/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
            }
            $aviso .= "   <tr><td class=\"label\"  colspan=" . $columnas . " align=\"center\">\n";
            $aviso .= "     Página&nbsp;" . $pagina . " de " . $NumeroPaginas . "</td>\n";
            $aviso .= "   </tr>\n";

            if ($op == 2) {
                $TablaPaginado .= $aviso;
            } else {
                $TablaPaginado = $aviso . $TablaPaginado;
            }
        }

        $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
        $Tabla .= $TablaPaginado;
        $Tabla .= "</table>";

        return $Tabla;
    }

    /*     * *******************************************************************************
     *
     * MUESTRA LA CADENA SI SE INSETO UN NUEVO REGISTRO. 
     *
     * ********************************************************************************* */

    function FormaCuenta($datos) {
        $empresaid = "01";
        $path = SessionGetVar("rutaImagenes");
        $cad = "Operacion Hecha Satisfactoriamente";
        $a = strcmp($datos[0], $cad);

        $cad1 = "Actualización Hecha Satisfactoriamente";
        $b = strcmp($datos[0], $cad1);
        if ($a == 0 || $b == 0) {
            $consulta = new DocumentosSQL();

            $vector = $consulta->ExisteCuenta($datos[1]);
            $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\" width=\"13%\">\n";
            $salida .= "                        CUENTA Nº";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\"width=\"51%\">\n";
            $salida .= "                        DESCRIPCION";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        TP";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        NAT";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        CC";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        TER";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        ACT";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        DC";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"6%\">\n";
            $salida .= "                        MODIFICAR";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";

            $vector = $consulta->ConCuenta($datos[1], $datos[2]);
            //var_dump($vector);

            $nivel_h = $vector[0]['empresa_id'];
            $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";

            if ($vector[0]['sw_cuenta_movimiento'] == 0) {
                $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                $salida .= "                     " . $vector[0]['cuenta'] . "";
                $nivel_h = $vector[0]['nivel'] + 1;
                $vector[0]['empresa_id'];
                $nivel_hijito = $consulta->ConsultarNivelDigitos($vector[0]['empresa_id'], $nivel_h);
                $nivel_hijito[0]['digitos'];
                $javaAccionAnular1 = "javascript:MostrarCapa('ContenedorTotal');Iniciar1('CREAR NUEVA CUENTA'); Traer(" . $vector[0]['cuenta'] . ");BuscarNivel1('" . $vector[0]['empresa_id'] . "','" . $vector[0]['cuenta'] . "');NextLevel('" . $nivel_hijito[0]['digitos'] . "')";
                $salida .= "                  <a title=\"Crear nueva cuenta\" href=\"" . $javaAccionAnular1 . "\">(+)</a>\n";
            } else {
                $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                $salida .= "                     " . $vector[0]['cuenta'] . "";
            }
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                        " . $vector[0]['descripcion'];
            $salida .= "                      </td>\n";

            if ($vector[0]['sw_cuenta_movimiento'] == 1) {
                $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
                $salida .= "                         M";
            } else {
                $salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
                $salida .= "                         T";
            }

            $salida .= "                      </td>\n";



            if ($vector[0]['sw_naturaleza'] == 'C') {
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                         C";
            } else {
                if ($vector[0]['sw_naturaleza'] == 'D') {
                    $salida .= "                      <td align=\"center\">\n";
                    $salida .= "                         D";
                } else {
                    $salida .= "                      <td align=\"center\">\n";
                    $salida .= "                         ";
                }
            }

            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\">\n";

            if ($vector[0]['sw_centro_costo'] == 1) {
                $salida .= "                         <a>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                $salida .= "                         <a>\n";
            }

            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\">\n";

            if ($vector[0]['sw_tercero'] == 1) {
                $salida .= "                         <a>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                $salida .= "                         <a>\n";
            }

            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\">\n";

            if ($vector[0]['sw_estado'] == 0) {
                $salida .= "                         <a>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/delete.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                $salida .= "                         <a>\n";
            } elseif ($vector[0]['sw_estado'] == 1) {
                $salida .= "                         <a>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                $salida .= "                         <a>\n";
            }




            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\">\n";

            if ($vector[0]['sw_documento_cruce'] == 1) {
                $salida .= "                         <a>\n";
                $salida .= "                          <sub><img src=\"" . $path . "/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                $salida .= "                         <a>\n";
            }

            $salida .= "                      </td>\n";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\">\n";
            $salida .= "                         <a title=\"Modificar\" href=\"javascript:ModificarCuenta('" . $vector[0]['cuenta'] . "','" . $vector[0]['empresa_id'] . "');MostrarCapa('ContenedorMod'); Iniciar2('MODIFICAR CUENTA');\">\n";
            $salida .= "                          <sub><img src=\"" . $path . "/images/modificar.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
            $salida .= "                         <a>\n";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";






            $salida .= "</table>\n";
            $salida .= "<br>\n";
            $salida .= "<br>\n";




            return $salida;
        }
    }

    /*     * ******************************************************************************
      Datos complementarios ventana emergente 2
     * ******************************************************************************* */

    function Longitud($datos) {
        //echo "d0".$datos[0];
        //echo "d1".$datos[1];
        $consulta = new CuentasSQL();
        $vec_niv = array();
        $Resultado = array();
        $vec_niv = $consulta->ConsultarNivelCuenta($datos[0], $datos[1]);
        $resultado = $consulta->ConsultarNivelDigitos($datos[0], $vec_niv[0]['nivel']);
        echo "datos" . $resultado[0]['digitos'] . $resultado[1]['digitos'];
        echo "digitos" . $cad = $resultado[1]['digitos'] - $resultado[0]['digitos'];
        echo "nivel" . $resultado[1]['nivel'];
        //SessionSetVar("Nivel",$resultado[1]['nivel']);

        return $cad;
    }

    /*     * ****************************************************************************************
     * funcion q sirve para modificar cuentas
     * ***************************************************************************************** */

    function ModifCuenta($cuenta) {
        $path = SessionGetVar("rutaImagenes");
        $consulta = new CuentasSQL();
        $vector = $consulta->BuscarCuentasStip(0, 2, $cuenta[0], $cuenta[1]);
        echo "si" . $vector[0]['sw_documento_cruce'] . $vector[0]['sw_cuenta_movimiento'];
        $accion500 = ModuloGetURL('app', 'Cg_PlanesCuentas', 'user', 'CrearPlanCuentas');
        $salida .= "      <form name=\"mod_cuenta\"  action=\"" . $accion500 . "\" method=\"post\">\n";
        $salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                    <td colspan=\"4\">ATRIBUTOS DE LA CUENTA</td>\n";
        $salida .= "                </tr>\n";
        $salida .= "                <tr class=\"modulo_list_claro\">\n";
        $salida .= "                    <td colspan=\"2\" width=\"35%\"align=\"left\">\n";
        $salida .= "                      <b> NUMERO CUENTA</b> \n";
        $salida .= "                    </td>\n";
        $salida .= "                      <input type=\"hidden\" name=\"padre\">";
        $salida .= "                      <input type=\"hidden\" name=\"niv_hijo\">";
        $salida .= "                    <td colspan=\"2\" width=\"65%\" id=\"hcuenta\" align=\"left\">\n";
        $salida .= "                    " . $vector[0]['cuenta'] . "";
        $salida .= "                    </td>\n";
        $salida .= "                </tr>\n";
        $salida .= "            </table>\n";
        $salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                <tr class=\"modulo_list_claro\">\n";
        $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
        $salida .= "                      <b> DESCRIPCION</b>\n";
        $salida .= "                    </td>\n";
        $salida .= "                    <td colspan=\"4\" align=\"center\">\n";
        $salida .= "                       <input type=\"text\" class=\"input-text\" name=\"descri1\" size=\"35\" value=\"" . $vector[0]['descripcion'] . "\" onclick=\"Limpiar2()\">\n";
        $salida .= "                    </td>\n";
        $salida .= "                </tr>\n";
        /* $salida .= "                <tr class=\"modulo_list_claro\">\n";
          $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $salida .= "                      <b> TIPO</b>";
          $salida .= "                    </td>\n";
         */ if ($vector[0]['sw_cuenta_movimiento'] == 0) {
            $salida .= "                       <input type=\"hidden\" class=\"input-text\" name=\"tipos1\" value=\"0\">\n";
        } else {
            $salida .= "                       <input type=\"hidden\" class=\"input-text\" name=\"tipos1\" value=\"1\">\n";
        }

        $salida .= "                <tr class=\"modulo_list_claro\">\n";
        $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
        $salida .= "                      <b>NATURALEZA</b> \n";
        $salida .= "                    </td>\n";

        if ($vector[0]['sw_naturaleza'] == 'D') {
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"nat1\"value=\"D\"onClick=\"Limpiar2()\" checked><b>DEBITO</b>\n";
            $salida .= "                    </td>\n";
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"nat1\" value=\"C\" onClick=\"Limpiar2()\"><b>CREDITO</b>\n";
            $salida .= "                    </td>\n";
        } else {
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"nat1\"value=\"D\"onClick=\"Limpiar2()\"><b>DEBITO</b>\n";
            $salida .= "                    </td>\n";
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"nat1\" value=\"C\" onClick=\"Limpiar2()\" checked><b>CREDITO</b>\n";
            $salida .= "                    </td>\n";
        }
        $salida .= "                </tr>\n";
        $salida .= "                <tr class=\"modulo_list_claro\">\n";
        $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
        $salida .= "                       <b>CENTRO DE COSTO</b>\n";
        $salida .= "                    </td>\n";

        if ($vector[0]['sw_centro_costo'] == 0 && $vector[0]['sw_cuenta_movimiento'] == 0) {
            $salida .= "                    <td colspan=\"2\" align=\"left\" style='text_indent:10pt'>\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"1\" onClick=\"Limpiar2()\" disabled><b>SI</b>\n";
            $salida .= "                    </td>\n";
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"0\" onClick=\"Limpiar2()\" disabled><b>NO</b>\n";
            $salida .= "                    </td>\n";
        } elseif ($vector[0]['sw_centro_costo'] == 1 && $vector[0]['sw_cuenta_movimiento'] == 1) {
            $salida .= "                    <td colspan=\"2\" align=\"left\" style='text_indent:10pt'>\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"1\" onClick=\"Limpiar2()\" checked><b>SI</b>\n";
            $salida .= "                    </td>\n";
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"0\" onClick=\"Limpiar2()\" ><b>NO</b>\n";
            $salida .= "                    </td>\n";
        } elseif ($vector[0]['sw_centro_costo'] == 0 && $vector[0]['sw_cuenta_movimiento'] == 1) {
            $salida .= "                    <td colspan=\"2\" align=\"left\" style='text_indent:10pt'>\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"1\" onClick=\"Limpiar2()\"><b>SI</b>\n";
            $salida .= "                    </td>\n";
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"0\" onClick=\"Limpiar2()\" checked><b>NO</b>\n";
            $salida .= "                    </td>\n";
        }
        $salida .= "                </tr>\n";
        $salida .= "                <tr class=\"modulo_list_claro\">\n";
        $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
        $salida .= "                      <b>TERCEROS</b>\n";
        $salida .= "                    </td>\n";
        if ($vector[0]['sw_tercero'] == 0 && $vector[0]['sw_cuenta_movimiento'] == 0) {
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"1\" onClick=\"Limpiar2()\" disabled><b>SI</b>\n";
            $salida .= "                    </td>\n";
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"0\" onClick=\"Limpiar2()\" disabled><b>NO</b>\n";
            $salida .= "                    </td>\n";
        } elseif ($vector[0]['sw_tercero'] == 1 && $vector[0]['sw_cuenta_movimiento'] == 1) {
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"1\" onClick=\"Limpiar2()\" checked><b>SI</b>\n";
            $salida .= "                    </td>\n";
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"0\" onClick=\"Limpiar2()\"><b>NO</b>\n";
            $salida .= "                    </td>\n";
        } elseif ($vector[0]['sw_tercero'] == 0 && $vector[0]['sw_cuenta_movimiento'] == 1) {
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"1\" onClick=\"Limpiar2()\"><b>SI</b>\n";
            $salida .= "                    </td>\n";
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"0\" onClick=\"Limpiar2()\"checked><b>NO</b>\n";
            $salida .= "                    </td>\n";
        }

        $salida .= "                </tr>\n";
        $salida .= "                <tr class=\"modulo_list_claro\">\n";
        $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
        $salida .= "                      <b>ESTADO ACTIVO</b>\n";
        $salida .= "                    </td>\n";
        if ($vector[0]['sw_estado'] == 1) {
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"act\" value=\"1\" onClick=\"Limpiar2()\"checked><b>SI</b>\n";
            $salida .= "                    </td>\n";
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"act\" value=\"0\" onClick=\"Limpiar2()\"><b>NO</b>\n";
            $salida .= "                    </td>\n";
        } else {
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"act\" value=\"1\" onClick=\"Limpiar2()\"><b>SI</b>\n";
            $salida .= "                    </td>\n";
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"act\" value=\"0\" onClick=\"Limpiar2()\" checked><b>NO</b>\n";
            $salida .= "                    </td>\n";
        }

        $salida .= "                </tr>\n";
        $salida .= "                <tr class=\"modulo_list_claro\">\n";
        $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
        $salida .= "                      <b>DOCUMENTO CRUCE</b>\n";
        $salida .= "                    </td>\n";

        if ($vector[0]['sw_documento_cruce'] == 0 && $vector[0]['sw_cuenta_movimiento'] == 0) {
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"1\" onClick=\"Limpiar2()\"disabled><b>SI</b>\n";
            $salida .= "                    </td>\n";
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"0\" onClick=\"Limpiar2()\"disabled><b>NO</b>\n";
            $salida .= "                    </td>\n";
        } elseif ($vector[0]['sw_documento_cruce'] == 1 && $vector[0]['sw_cuenta_movimiento'] == 1) {
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"1\" onClick=\"Limpiar2()\" checked><b>SI</b>\n";
            $salida .= "                    </td>\n";
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"0\" onClick=\"Limpiar2()\"><b>NO</b>\n";
            $salida .= "                    </td>\n";
        } elseif ($vector[0]['sw_documento_cruce'] == 0 && $vector[0]['sw_cuenta_movimiento'] == 1) {
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"1\" onClick=\"Limpiar2()\"><b>SI</b>\n";
            $salida .= "                    </td>\n";
            $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
            $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"0\" onClick=\"Limpiar2()\" checked><b>NO</b>\n";
            $salida .= "                    </td>\n";
        }

        $salida .= "                </tr>\n";
        $salida .= "                <tr class=\"modulo_list_claro\">\n";
        $salida .= "                    <td colspan=\"3\" align=\"center\">\n";
        $salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"aceptar1\" value=\"Actualizar\" onclick=\"Validar2('" . $vector[0]['empresa_id'] . "','" . $vector[0]['cuenta'] . "','" . $vector[0]['nivel'] . "')\">\n";
        $salida .= "                    </td>\n";
        $salida .= "                    <td colspan=\"3\" align=\"center\">\n";
        $salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"cancelar1\" value=\"Cancelar\" onclick=\"javascript:Ok1();Cerrar('ContenedorMod');\">\n";
        $salida .= "                    </td>\n";
        $salida .= "                </tr>\n";
        $salida .= "            </table>\n";
        $salida .= "        </form>\n";

        return $salida;
    }

}

$oRS = new procesos_admin(array('ActivarMenu'));
$oRS->action();
?>