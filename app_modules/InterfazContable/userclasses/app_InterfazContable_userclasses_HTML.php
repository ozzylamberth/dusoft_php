<?php

/**
 * $Id: app_InterfazContable_userclasses_HTML.php,v 1.6 2006/02/07 20:14:07 alex Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

/**
*Contiene los metodos visuales para realizar las autorizaciones.
*/

class app_InterfazContable_userclasses_HTML extends app_InterfazContable_user
{
    /**
    *Constructor de la clase app_Autorizacion_user_HTML
    *El constructor de la clase app_Autorizacion_user_HTML se encarga de llamar
    *a la clase app_Autorizacion_user quien se encarga de el tratamiento
    * de la base de datos.
    */

  function app_InterfazContable_user_HTML()
    {
                $this->salida='';
                $this->app_InterfazContable_user();
                return true;
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

  function Todos()
  {
      $this->salida .= "<SCRIPT>";
      $this->salida .= "function Todos(frm,x){";
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
      $this->salida .= "</SCRIPT>";
  }


    function FormaPrincipal()
    {
            $this->salida.= ThemeAbrirTabla('MENU INTERFAZ CONTABLE CG1');
            $this->salida .= "<table border=\"0\" width=\"40%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\" class=\"modulo_table_list_title \">";
            $this->salida .= " <tr>";
            $this->salida .= "   <td align=\"center\">  OPTIONES INTERFAZ CONTABLE CG1 </td>";
            $this->salida .= " </tr>";
            $action=ModuloGetURL('app','InterfazContable','user','FormaBuscar');
            $this->salida .= " <tr>";
            $this->salida .= "   <td class=\"modulo_list_claro \"><a href=\"$action\">GENERACION DE INTERFAZ</a></td>";
            $this->salida .= " </tr>";
            $this->salida .= " <tr>";
            $action=ModuloGetURL('app','InterfazContable','user','LlamarManejoInterfaz');
            $this->salida .= "   <td class=\"modulo_list_claro \"><a href=\"$action\">REGISTRO DE INTERFACES REALIZADAS</a></td>";
            $this->salida .= " </tr>";
            $this->salida .= "</table>";
            //botones
            $this->salida .= "                <BR><table width=\"50%\" align=\"center\" border=0>";
            $this->salida .= "                     <tr>";
            $accion=ModuloGetURL('app','InterfazContable','user','main');
            $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "                     <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Continuar\" value=\"VOLVER\"></td>";
            $this->salida .= "               </form>";
            $this->salida .= "               </table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
    }

    function FormaBuscar($var,$todo)
    {
            IncludeLib('funciones_admision');
            $this->Todos();
            unset($_SESSION['INTERFAZCG1']['VECTOR']);
            unset($_SESSION['INTERFAZCG1']['DOCUMENTO']['ERROR']);

            if (!IncludeFile("classes/InterfaseCG1/InterfaseCG1.class.php"))
            {
                    die(MsgOut("Error al incluir archivo","El Archivo 'classes/InterfaseCG1/InterfaseCG1.class.php' NO SE ENCUENTRA"));
            }

            if(!class_exists('InterfaseCG1'))
            {
                    die(MsgOut("NO SE CARGO LA CLASE","InterfaseCG1 - NO EXISTE"));
            }

            $a= new InterfaseCG1;

            $this->salida.= ThemeAbrirTabla('INTERFAZ CONTABLE CG1');
      $action=ModuloGetURL('app','InterfazContable','user','Buscar');
            $this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "     </table>";
      $this->salida .= "<form name=\"formabuscar\" action=\"$action\" method=\"post\">";
            $this->salida .= "<table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .= "<tr>";
            $this->salida .= "<td colspan=\"2\">";
            $this->salida .= "  <table class=\"normal_10\" border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= "    <tr>";
            //----------------------AÑO
            $this->salida .= "      <td align=\"center\" class=\"".$this->SetStyle("ano")."\">AÑO:</td>";
            $this->salida .= "      <td align=\"center\" ><select name=\"ano\" class=\"select\">";
            $anobase=2003;
            $anoActual=date("Y");
            for($i=$anobase;$i<=($anoActual);$i++)
            {
                if($i==$_REQUEST[ano] OR $i==$_SESSION['INTERFAZCG1']['DETALLE']['ano'])
        {   $this->salida .="           <option value=\"$i\" selected>$i</option>"; }
                elseif(empty($_REQUEST[ano]) AND $i==$anoActual AND empty($_SESSION['INTERFAZCG1']['DETALLE']['ano']))
        {   $this->salida .="           <option value=\"$i\" selected>$i</option>"; }
                else
        {   $this->salida .="           <option value=\"$i\">$i</option>";  }
            }
            $this->salida .= "      </select></td>";

            //----------------------MES
            $vars[1]='ENERO';
            $vars[2]='FEBRERO';
            $vars[3]='MARZO';
            $vars[4]='ABRIL';
            $vars[5]='MAYO';
            $vars[6]='JUNIO';
            $vars[7]='JULIO';
            $vars[8]='AGOSTO';
            $vars[9]='SEPTIEMBRE';
            $vars[10]='OCTUBRE';
            $vars[11]='NOVIEMBRE';
            $vars[12]='DICIEMBRE';
            $this->salida .= "      <td align=\"center\" class=\"".$this->SetStyle("mes")."\">MES:</td>";
            $this->salida .= "      <td align=\"center\" ><select name=\"mes\" class=\"select\">";
            for($i=1; $i<=sizeof($vars); $i++)
            {
                if($i==$_REQUEST[mes] OR $i==$_SESSION['INTERFAZCG1']['DETALLE']['mes'])
        {  $this->salida .="            <option value=\"$i\" selected>".$vars[$i]."</option >";  }
                elseif(empty($_REQUEST[mes]) AND $i==floor(date('m')) AND empty($_SESSION['INTERFAZCG1']['DETALLE']['mes']))
        {  $this->salida .="            <option value=\"$i\" selected>".$vars[$i]."</option >";  }
                else
        {  $this->salida .="            <option value=\"$i\">".$vars[$i]."</option>";  }
            }
            $this->salida .= "      </select></td>";
            //----------------------DIA
            $this->salida .= "      <td align=\"center\" class=\"".$this->SetStyle("diaI")."\">DIA INICIAL:</td>";
            $this->salida .= "      <td align=\"center\" ><select name=\"diaI\" class=\"select\">";
      $this->salida .=" <option value=\"\">---</option>";
            for($i=1;$i<=31;$i++)
            {
                if($i==$_REQUEST[dia])
        {  $this->salida .="            <option value=\"$i\">$i</option selected>";  }
                else
        {  $this->salida .="            <option value=\"$i\">$i</option>";  }
            }
            $this->salida .= "      </select></td>";
            $this->salida .= "      <td align=\"center\" class=\"".$this->SetStyle("diaF")."\">DIA FINAL:</td>";
            $this->salida .= "      <td align=\"center\" ><select name=\"diaF\" class=\"select\">";
      $this->salida .=" <option value=\"\">---</option>";
            for($i=1;$i<=31;$i++)
            {
                if($i==$_REQUEST[dia])
        {  $this->salida .="            <option value=\"$i\">$i</option selected>";  }
                else
        {  $this->salida .="            <option value=\"$i\">$i</option>";  }
            }
            $this->salida .= "      </select></td>";
            $this->salida .= "    </tr>";
            $this->salida .= "  </table>";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= "<tr>";
            $this->salida .= "<td class=\"".$this->SetStyle("Tipo")."\">TIPO DOCUMENTO: </td>";
            //lISTADO CON LOS TIPOS DE DOCUMENTOS CONFIGURADOS PARA LA INTERFASE
            $tipo = $a->getTiposDeDocumentos($_SESSION['INTERFAZCG1']['EMPRESA']);
      $this->salida .= "<td><select name=\"Tipo\" class=\"select\">";
      $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
      for($i=0; $i<sizeof($tipo); $i++)
      {
                    $v = explode('||',$_REQUEST[Tipo]);
          if($tipo[$i][documento_id]==$v[0] OR $tipo[$i][documento_id]==$_SESSION['INTERFAZCG1']['DETALLE']['Tipo'])
          {  $this->salida .=" <option value=\"".$tipo[$i][documento_id]."||".$tipo[$i][descripcion]."\" selected>".$tipo[$i][descripcion]."</option>";  }
          else
          {  $this->salida .=" <option value=\"".$tipo[$i][documento_id]."||".$tipo[$i][descripcion]."\">".$tipo[$i][descripcion]."</option>";  }
      }
      $this->salida .= "                  </select></td>";
      $this->salida .= "</tr>";
      $this->salida .= "    <tr>";
      $this->salida .= "        <td colspan=\"4\" class=\"label\"><input type=\"checkbox\" name=\"paso\" value=\"1\">&nbsp; Incluir Documentos marcados con interfaz pasada.</td>";
      $this->salida .= "    </tr>";
      $this->salida .= "</table>";
      $this->salida .= "<table align =\"center\">";
      $this->salida .= "<tr>";
      $this->salida .= "<td align=\"center\" colspan=\"4\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
      $this->salida .= "</form>";
      $actionM=ModuloGetURL('app','InterfazContable','user','FormaPrincipal');
      $this->salida .= "<form name=\"formavolver\" action=\"$actionM\" method=\"post\">";
      $this->salida .= "<td align=\"center\">";
      $this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td>";
      $this->salida .= "</form>";
      if($this->rutaInterface)
      {
        $this->salida .= "<td align=\"center\">";
        $this->salida .= download($this->rutaInterface,$nombre="DESCARGAR INTERFASE",$link=false,$comprimir=true,$boton=true);
        $this->salida .= "</td>";
      }
      $this->salida .= "</tr>";
      $this->salida .= "</table>";
            //----------------fin busqueda--------------------------
            if(!empty($var))
            {       //forma con la accion principal
                    $actionM=ModuloGetURL('app','InterfazContable','user','Interfase');
                    $this->salida .= "<form name=\"formavolver\" action=\"$actionM\" method=\"post\">";

                    $this->salida .= "<table border=\"0\" width=\"30%\" align=\"center\">";
                    $this->salida .= "<tr>";
                    $this->salida .= "<td class=\"modulo_table_list_title\">DOCUMENTO</td>";
                    $this->salida .= "<td class=\"modulo_table_list_title\">PREFIJO</td>";
                    $this->salida .= "</tr>";
                    $this->salida .= "<tr class=\"modulo_list_claro\">";
                    /*foreach($var[DOCUMENTOS] as $k => $v)
                    {
                            if(empty($documento))
                            {  $documento=$v[documento_id];  }
                    }   */
                    $this->salida .= "<td class=\"label_mark\" align=\"center\">".$_SESSION['INTERFAZCG1']['DETALLE']['Descripcion']."</td>";
                    $this->salida .= "<td class=\"label_mark\" align=\"center\">".$var[PREFIJO]."</td>";
                    $this->salida .= "</tr>";
                    $this->salida .= "</table>";
                    unset($_SESSION['INTERFAZCG1']['VECTOR']);
                    $_SESSION['INTERFAZCG1']['VECTOR']=$var;
            }

            if(!empty($var[ERRORES]))
            {       //hay errorres
                    $this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
                    $this->salida .= "<tr>";
                    $this->salida .= "<td class=\"modulo_table_list_title\">ERRORES ENCONTRADOS</td>";
                    $this->salida .= "</tr>";
                    for($i=0; $i<sizeof($var[ERRORES]); $i++)
                    {
                            $this->salida .= "<tr class=\"modulo_list_claro\">";
                            $this->salida .= "<td class=\"label_mark\">".$var[ERRORES][$i]."</td>";
                            $this->salida .= "</tr>";
                    }
                    $this->salida .= "</table>";
                    //------------BOTON PARA IR ARREGLANDO LOS DOCUEMNTOS
                    $this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
                    $this->salida .= "<tr>";
                    $this->salida .= "<td align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Contabilizacion\" value=\"CONTABILIZAR\"><br></td>";
                    $this->salida .= "</tr>";
                    $this->salida .= "</table>";
            }
            elseif(!empty($var))
            {       //todas pasaron se puede hacer la interfase
                    $this->salida .= "<table border=\"0\" width=\"30%\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
                    $this->salida .= "<tr>";
                    $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"GenerarInterfase\" value=\"GENERAR INTERFASE\"><br></td>";
                    $this->salida .= "</tr>";
                    $this->salida .= "</table>";
            }

            if(!empty($var))
            {
                    $this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
                    $this->salida .= "<tr class=\"modulo_table_list_title\">";
                    $this->salida .= "<td>FACTURA</td>";
                    $this->salida .= "<td>FECHA</td>";
                    $this->salida .= "<td>ESTADO</td>";
                    if(!empty($var[ERRORES]))
                    {  $this->salida .= "<td><input type=\"checkbox\" name=\"Todo\" onClick=\"Todos(this.form,this.checked)\"></td>";  }
                    else
                    {  $this->salida .= "<td></td>";  }
                    $this->salida .= "</tr>";
                    $i=0;
                    $mostrar='';
                    foreach($var[DOCUMENTOS] as $k => $v)
                    {
                            if(empty($v[documento_contable_id]))
                            {       //no contabilizado porque tuvo un error
                                    if( $i % 2){ $estilo='modulo_list_claro';}
                                    else {$estilo='modulo_list_oscuro';}
                                    $this->salida .= "<tr class=\"$estilo\">";
                                    if(empty($v[documento_id]))
                                    {  $this->salida .= "<td align=\"center\">HUECO</td>";    }
                                    else
                                    {  $this->salida .= "<td align=\"center\">".$v[prefijo]." ".$v[factura_fiscal]."</td>";     }
                                    $this->salida .= "<td align=\"center\">".FechaStamp($v[fecha_registro])." ".HoraStamp($v[fecha_registro])."</td>";
                                    $this->salida .= "<td class=\"label_mark\" align=\"center\">NO CONTABILIZADO";
                                    if(!empty($v[cg_contabilizacion_estado_id]))
                                    {
                                            $href=ModuloGetURL('app','InterfazContable','user','DetalleDocumento',array('factura'=>$v[factura_fiscal]));
                                            //OBTENER EL DETALLE DE UN CODIGO (cg_contabilizacion_estado_id)
                                            $this->salida .= "<br><a href=\"$href\">".$a->GetDetalleError($v[cg_contabilizacion_estado_id])."</a></br>";
                                    }
                                    $this->salida .= "</td>";
                                    $this->salida .= "<td align=\"center\"><input type = checkbox name=\"Contabilizar".$v[prefijo].$v[factura_fiscal]."\" value =\"".$v[prefijo]."||".$v[factura_fiscal]."\"></td>";
                                    $this->salida .= "</tr>";
                                    $i++;
                            }
                            else
                            {       //paso sin errores
                                    if( $i % 2){ $estilo='modulo_list_claro';}
                                    else {$estilo='modulo_list_oscuro';}
                                    $mostrar .= "<tr class=\"$estilo\">";
                                    if(empty($v[documento_id]))
                                    {  $mostrar .= "<td align=\"center\">HUECO</td>";     }
                                    else
                                    {  $mostrar.= "<td align=\"center\">".$v[prefijo]." ".$v[factura_fiscal]."</td>";       }
                                    $mostrar .= "<td align=\"center\">".FechaStamp($v[fecha_registro])." ".HoraStamp($v[fecha_registro])."</td>";
                                    if($v[tipo_bloqueo_id]=='04')
                                    {  $x="<img src=\"".GetThemePath()."/images/egresook.png\" title=\"PASO LA INTERFAZ CONTABLE\"> ";  }
                                    $mostrar .= "<td class=\"label_mark\" align=\"center\" colspan=\"2\">$x &nbsp; CONTABILIZADO</td>";
                                    $mostrar .= "</tr>";
                                    $i++;
                            }
                    }
                    if($todo==1)
                    {       //mostrar todo
                            $this->salida .= $mostrar;
                            $this->salida .= "<tr class=\"$estilo\">";
                            $href=ModuloGetURL('app','InterfazContable','user','MostrarTodas',array('TODO'=>0));
                            $this->salida .= "<td colspan=\"4\" align=\"right\"><a href=\"$href\"> OCULTAR FACTURAS</a></td>";
                            $this->salida .= "</tr>";
                    }
                    elseif(!empty($mostrar))
                    {
                            $this->salida .= "<tr class=\"$estilo\">";
                            $_SESSION['INTERFAZCG1']['VECTOR']=$var;
                            $href=ModuloGetURL('app','InterfazContable','user','MostrarTodas',array('TODO'=>1));
                            $this->salida .= "<td colspan=\"4\" align=\"right\"><a href=\"$href\"> VER TODAS LAS FACTURAS</a></td>";
                            $this->salida .= "</tr>";
                    }
                    $this->salida .= "</table>";
                    $this->salida .= "</form>";
            }

            $this->salida .= ThemeCerrarTabla();
            return true;
    }

    function FormaDetalle($detalle)
    {
            if (!IncludeFile("classes/InterfaseCG1/InterfaseCG1.class.php"))
            {
                    die(MsgOut("Error al incluir archivo","El Archivo 'classes/InterfaseCG1/InterfaseCG1.class.php' NO SE ENCUENTRA"));
            }

            if(!class_exists('InterfaseCG1'))
            {
                    die(MsgOut("NO SE CARGO LA CLASE","InterfaseCG1 - NO EXISTE"));
            }

            $a= new InterfaseCG1;

            $this->salida.= ThemeAbrirTabla('INTERFAZ CONTABLE CG1');
            $var=$_SESSION['INTERFAZCG1']['VECTOR'];
            if(!empty($var))
            {       //forma con la accion principal
                    $this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
                    $this->salida .= "<tr>";
                    $this->salida .= "<td class=\"modulo_table_list_title\">DOCUMENTO</td>";
                    $this->salida .= "<td class=\"modulo_table_list_title\">PREFIJO</td>";
                    $this->salida .= "<td class=\"modulo_table_list_title\">NUMERO</td>";
                    $this->salida .= "<td class=\"modulo_table_list_title\">ESTADO</td>";
                    $this->salida .= "</tr>";
                    $this->salida .= "<tr class=\"modulo_list_claro\">";
                    /*foreach($var[DOCUMENTOS] as $k => $v)
                    {
                            if(empty($documento))
                            {  $documento=$v[documento_id];  }
                    }       */
                    $this->salida .= "<td class=\"label_mark\" align=\"center\">".$_SESSION['INTERFAZCG1']['DETALLE']['Descripcion']."</td>";
                    $this->salida .= "<td class=\"label_mark\" align=\"center\">".$var[PREFIJO]."</td>";
                    $this->salida .= "<td class=\"label_mark\" align=\"center\">".$detalle[REGISTROS][0][LLAVE][FACTURA]."</td>";
                    $this->salida .= "<td class=\"label_mark\" align=\"center\">".$a->GetDetalleError($detalle[REGISTROS][0][ESTADO])."</td>";
                    $this->salida .= "</tr>";
                    $this->salida .= "</table><br>";
            }

            $this->salida .= "<table border=\"0\" width=\"95%\" align=\"center\">";
            $this->salida .= "<tr class=\"modulo_table_list_title\">";
            $this->salida .= "<td colspan=\"4\">".$detalle[TITULO]."</td>";
            $this->salida .= "</tr>";
            $this->salida .= "<tr class=\"modulo_table_list_title\" align=\"center\">";
            $this->salida .= "<td width=\"10%\">TIPO</td>";
            $this->salida .= "<td width=\"10%\">NUMERO</td>";
            $this->salida .= "<td width=\"15%\">ESTADO</td>";
            $this->salida .= "<td width=\"75%\">DETALLE</td>";
            $this->salida .= "</tr>";
            $i=0;
            foreach($detalle[REGISTROS][0][DETALLE][REGISTROS] as $k1=> $v1)
            {
                if( $i % 2){ $estilo='modulo_list_claro';}
                else {$estilo='modulo_list_oscuro';}
                $this->salida .= "<tr class=\"$estilo\" align=\"center\">";
                $this->salida .= "<td>".$detalle[REGISTROS][0][DETALLE][TITULO]."</td>";
                $this->salida .= "<td>".$v1[LLAVE][$detalle[REGISTROS][0][DETALLE][TITULO]]."</td>";
                $this->salida .= "<td class=\"label_mark\">".$a->GetDetalleError($v1[ESTADO])."</td>";
                $this->salida .= "<td>";
                $this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\">";
                $this->salida .= "    <tr class=\"modulo_table_list_title\" align=\"center\">";
                $this->salida .= "      <td>TIPO</td>";
                $this->salida .= "      <td>NUMERO</td>";
                $this->salida .= "      <td>ESTADO</td>";
                $this->salida .= "          <td>DETALLE</td>";
                $this->salida .= "    </tr>";
                foreach($v1[DETALLE][REGISTROS] as $k2 => $v2)
                {
                        foreach($v2[DETALLE][REGISTROS] as $k3 => $v3)
                        {
                                $this->salida .= "    <tr class=\"modulo_list_claro\">";
                                $this->salida .= "      <td align=\"center\">".$v1[DETALLE][TITULO]."</td>";
                                $this->salida .= "      <td align=\"center\">".$v2[LLAVE][$v1[DETALLE][TITULO]]."</td>";
                                $this->salida .= "      <td>".$a->GetDetalleError($v2[ESTADO])."</td>";
                                $this->salida .= "          <td>";
                                $detalle='';
                                foreach($v3[LLAVE] as $k4 => $v4)
                                {
                                        if(!empty($v4))
                                        {  $detalle .= $k4."=>".$v4.' ';    }
                                }
                                $this->salida .= "$detalle";
                                $this->salida .= "          </td>";
                                $this->salida .= "    </tr>";
                        }
                }
                $this->salida .= "  </table>";
                $this->salida .= "</td>";
                $this->salida .= "</tr>";
                $i++;
            }
            $this->salida .= "</table>";

            $this->salida .= "<table border=\"0\" width=\"30%\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
            $this->salida .= "<tr>";
      $action=ModuloGetURL('app','InterfazContable','user','VolverBuscar');
      $this->salida .= "<form name=\"formavolver\" action=\"$action\" method=\"post\">";
            $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"><br></td>";
      $this->salida .= "</form>";
            $this->salida .= "</tr>";
            $this->salida .= "</table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
    }

    function FormaManejoInterfaz()
    {
            $this->salida.= ThemeAbrirTabla('INTERFAZ CONTABLE CG1');
            $datos = $this->BuscarBatch();
            $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "           </table>";
            if(empty($datos))
            {
                    $this->salida .= "                <table width=\"40%\" align=\"center\" border=0>";
                    $this->salida .= "                     <tr><td class=\"label_mark\" align=\"center\">NO HAY BATCH</td></tr>";
                    $this->salida .= "           </table>";
                    //botones
                    $this->salida .= "                <BR><table width=\"40%\" align=\"center\" border=0>";
                    $this->salida .= "                     <tr>";
            }
            else
            {
                    $this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
                    $this->salida .= " <tr class=\"modulo_table_list_title \">";
                    $this->salida .= "   <td>No. BATCH</td>";
                    $this->salida .= "   <td>DOCUMENTO</td>";
                    $this->salida .= "   <td>FECHA INICIAL</td>";
                    $this->salida .= "   <td>FECHA FINAL</td>";
                    $this->salida .= "   <td>PASO</td>";
                    $this->salida .= "   <td>NO PASO</td>";
                    $this->salida .= " </tr>";

                    $action=ModuloGetURL('app','InterfazContable','user','GuardarPasoInterfaz');
                    $this->salida .= "<form name=\"formavolver\" action=\"$action\" method=\"post\">";

                    for($i=0; $i<sizeof($datos); $i++)
                    {
                            if( $i % 2) $estilo='modulo_list_claro';
                            else $estilo='modulo_list_oscuro';
                            $this->salida .= " <tr class=\"$estilo\">";
                            $this->salida .= "   <td align=\"center\">".$datos[$i][interfase_cg1_batch_generado_id]."</td>";
                            $this->salida .= "   <td align=\"center\">".$datos[$i][descripcion]."</td>";
                            $this->salida .= "   <td align=\"center\">".$datos[$i][fecha_inicial]."</td>";
                            $this->salida .= "   <td align=\"center\">".$datos[$i][fecha_final]."</td>";
                            $this->salida .= "   <td align=\"center\"><input type=\"radio\" name=\"paso".$datos[$i][interfase_cg1_batch_generado_id]."\" value=\"2||".$datos[$i][fecha_inicial]."||".$datos[$i][fecha_final]."||".$datos[$i][empresa_id]."||".$datos[$i][documento_id]."||".$datos[$i][interfase_cg1_batch_generado_id]."\"></td>";
                            $this->salida .= "   <td align=\"center\"><input type=\"radio\" name=\"paso".$datos[$i][interfase_cg1_batch_generado_id]."\" value=\"3||".$datos[$i][fecha_inicial]."||".$datos[$i][fecha_final]."||".$datos[$i][empresa_id]."||".$datos[$i][documento_id]."||".$datos[$i][interfase_cg1_batch_generado_id]."\"></td>";
                            $this->salida .= " </tr>";
                    }
                    $this->salida .= "</table>";
                    //botones
                    $this->salida .= "                <BR><table width=\"40%\" align=\"center\" border=0>";
                    $this->salida .= "                     <tr>";
                    $this->salida .= "                     <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Continuar\" value=\"GUARDAR\"></td>";
                    $this->salida .= "</form>";
            }
            $accion=ModuloGetURL('app','InterfazContable','user','FormaPrincipal');
            $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "                     <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Continuar\" value=\"VOLVER\"></td>";
            $this->salida .= "               </form>";
            $this->salida .= "               </table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
    }

//----------------------------------------------------------------------------------------------------

}//fin clase

?>

