<?php

/**
 * $Id: app_AuditoriaMedica_userclasses_HTML.php,v 1.20 2006/04/26 23:12:06 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos visuales para realizar las autorizaciones.
 */

class app_AuditoriaMedica_userclasses_HTML extends app_AuditoriaMedica_user
{

     function app_AuditoriaMedica_user_HTML()
    {
          $this->salida='';
          $this->app_AuditoriaMedica_user();
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


    /**
    *
    */
    function FormaMenus()
    {     unset($_SESSION['DATOS_BUSQUEDA']);
          if(empty($_SESSION['AUDITORIA']['EMPRESA']))
          {
               $_SESSION['AUDITORIA']['EMPRESA_ID']=$_REQUEST['DatosAuditoria']['empresa_id'];
               $_SESSION['AUDITORIA']['EMPRESA']=$_REQUEST['DatosAuditoria']['razon_social'];
          }

          $this->salida .= ThemeAbrirTabla('MENU AUDITORIA MEDICA');
          $buscar_planes = $this->BuscarPlan_Auditor();
          $this->salida .= "<br>";
          $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
          $this->salida .= "<tr>";
          $this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\">PLANES</td>";
          $this->salida .= "</tr>";
          unset($_SESSION['AUDITORIA']['PLAN']);
          unset($_SESSION['AUDITORIA']['NOM_PLAN']);
          unset($_SESSION['AUDITORIA']['TIPO_PLAN']);
          unset($_SESSION['AUDITORIA']['TIPO_AUDITORIA']);
          foreach ($buscar_planes as $k => $v)
          {
            $this->salida .= "<tr>";
               $accionF=ModuloGetURL('app','AuditoriaMedica','user','FormaMetodoBuscar',array('plan_id'=>$v[plan_id],'desc_plan'=>$v[plan_descripcion],'tipo_plan'=>$v[tipo],'tipo_auditoria'=>$v[sw_tipo_auditoria]));
               $this->salida .= "<td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionF\">".strtoupper($v[plan_descripcion])."</a></td>";
              $this->salida .= "</tr>";
          }
          $this->salida .= "           </table>";
          $accion=ModuloGetURL('app','AuditoriaMedica','user','main');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
          $this->salida .= "</form>";
          $this->salida .= ThemeCerrarTabla();
          return true;
    }


     /*
    * Funcion donde se visualiza el encabezado de la empresa.
    * @return boolean
    */
     function Encabezado()
    {
        $this->salida .= "<br><table  class=\"modulo_table_title\" border=\"0\" width=\"80%\" align=\"center\" >";
        $this->salida .= " <tr class=\"modulo_table_title\">";
        $this->salida .= " <td>EMPRESA</td>";
        $this->salida .= " <td>MODULO</td>";
        $this->salida .= " <td>FECHA</td>";
        $this->salida .= " </tr>";
        $this->salida .= " <tr align=\"center\">";
        $this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['AUDITORIA']['EMPRESA']."</td>";
        $this->salida .= " <td class=\"modulo_list_claro\">AUDITORIA MEDICA</td>";
        $this->salida .= " <td class=\"modulo_list_claro\" >".$this->FormateoFechaLocal(date("Y-m-d"))."</td>";
        $this->salida .= " </tr>";
        $this->salida .= " </table>";
        return true;
    }


     function GetHtmlServicio($vect,$TipoId)
     {
          foreach($vect as $value=>$titulo)
          {
               if($value==$TipoId){
                    $this->salida .=" <option align=\"center\" value=\"$value\" selected>$titulo</option>";
               }else{
                    $this->salida .=" <option align=\"center\" value=\"$value\">$titulo</option>";
               }
          }
     }


     function GetHtmlTipoAuditoria($vect,$TipoId,$confirmar)
     {
          foreach($vect as $value=>$titulo)
          {
               if($titulo[nota_auditoria_tipo_id]==$TipoId AND $confirmar != '1'){
                    $this->salida .=" <option align=\"center\" value=\"".$titulo[nota_auditoria_tipo_id]."||".$titulo[descripcion]."\" selected>$titulo[descripcion]</option>";
               }else{
                    $this->salida .=" <option align=\"center\" value=\"".$titulo[nota_auditoria_tipo_id]."||".$titulo[descripcion]."\">$titulo[descripcion]</option>";
               }
          }
     }

     function GetHtmlEvoluciones($vect,$TipoId)
     {
          foreach($vect as $value=>$titulo)
          {
               if($titulo[evolucion_id]==$TipoId){
                    $this->salida .=" <option value=\"".$titulo[evolucion_id]."\" selected>".$titulo[evolucion_id]."</option>";
               }else{
                    $this->salida .=" <option value=\"".$titulo[evolucion_id]."\">".$titulo[evolucion_id]."</option>";
               }
          }
     }

     function GetHtmlHistoria($vect,$TipoId)
     {
          foreach($vect as $value=>$titulo)
          {
               if($titulo[hc_modulo]==$TipoId){
                    $this->salida .=" <option value=\"$titulo[hc_modulo]\" selected>".strtoupper($titulo[descripcion])."</option>";
               }else{
                    $this->salida .=" <option value=\"$titulo[hc_modulo]\">".strtoupper($titulo[descripcion])."</option>";
               }
          }
     }


     function GetHtmlProfesional($vect,$TipoId)
     {
          foreach($vect as $value=>$titulo)
          {
               if($titulo[usuario_id]==$TipoId){
                    $this->salida .=" <option value=\"$titulo[usuario_id]\" selected>".strtoupper($titulo[nombre])."</option>";
               }else{
                    $this->salida .=" <option value=\"$titulo[usuario_id]\">".strtoupper($titulo[nombre])."</option>";
               }
          }
     }


/**
    * Se utilizada listar en el combo los diferentes tipo de identifiacion de los pacientes
    * @access private
    * @return void
    */
    function BuscarIdPaciente($tipo_id,$TipoId)
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


    /*
    * Esta funcion realiza la busqueda de las ordenes de servicio según filtros como numero de orden
    * documento y plan
    * @return boolean
    */
    function FormaMetodoBuscar($Busqueda,$arr,$f)
    {
          $this->salida.= ThemeAbrirTabla('INFORMACIÓN DEL PACIENTE');
          $this->Encabezado();

          if($_SESSION['AUDITORIA']['PLAN'] =='')
          {
               $_SESSION['AUDITORIA']['PLAN'] = $_REQUEST['plan_id'];
               $_SESSION['AUDITORIA']['NOM_PLAN'] = $_REQUEST['desc_plan'];
               $_SESSION['AUDITORIA']['TIPO_PLAN']= $_REQUEST['tipo_plan'];
               $_SESSION['AUDITORIA']['TIPO_AUDITORIA']= $_REQUEST['tipo_auditoria'];
          }
          
          
              
              
                  
           
          $RUTA = "app_modules/AuditoriaMedica/buscador.php?sign=";
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="var rem=\"\";\n";
          $mostrar.="  function xxx(a){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA';\n";
          $mostrar.="    url2 +=a;\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";

          $mostrar.="  function limpiar(){\n";
          $mostrar.="  document.data.nombres.value='';\n";
          $mostrar.="  document.data.fechaini.value='';\n";
          $mostrar.="  document.data.fechafin.value='';\n";
          $mostrar.="  document.data.Documento.value='';\n";
          $mostrar.="  document.data.centroutilidad.value='';\n";
          $mostrar.="  document.data.unidadfunc.value='';\n";
          $mostrar.="  document.data.departamento.value='';\n";

          $mostrar.="  document.data.centroU.value='';\n";
          $mostrar.="  document.data.unidadF.value='';\n";
          $mostrar.="  document.data.DptoSel.value='';\n";

          $mostrar.="  document.data.evolucion.value='';\n";
          $mostrar.="  document.data.ingreso.value='';\n";
          $mostrar.="  document.data.cuenta.value='';\n";
          $mostrar.="  document.data.prefijo.value='';\n";
          $mostrar.="  document.data.factura.value='';\n";

          $mostrar.="  };\n";

                    //-------------nuevo dar
                    $mostrar .= "  function LimpiarFechas(){\n";
                    $mostrar .= "  document.data.fechaini.value='';\n";
                    $mostrar .= "  document.data.fechafin.value='';\n";
                    $mostrar.="  };\n";
                    //--------------fin nuevo dar

          $mostrar.="</script>\n";
          $this->salida .="$mostrar";

          if(!$Busqueda){ $Busqueda=1; }
          $accion=ModuloGetURL('app','AuditoriaMedica','user','BuscarOrden');
          $this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
          $this->salida .= "<tr>";
          $this->salida .= "<td width=\"80%\" >";
          $this->salida .= "<br><table border=\"0\" width=\"90%\" align=\"center\">";
          $this->salida .= "<tr><td align=\"center\" class=\"modulo_table_title\" width=\"90%\"> PLAN:  ";
          $this->salida .= strtoupper($_SESSION['AUDITORIA']['NOM_PLAN'])." -  ( TIPO AUDITOR: ".strtoupper($_SESSION['AUDITORIA']['TIPO_PLAN'])." )";
          $this->salida .= "</td></tr>";
          $this->salida .= "<tr><td><fieldset><legend class=\"field\">BUSCADOR AVANZADO</legend>";
          $this->salida .= "<table width=\"95%\" align=\"center\" border=\"0\">";
          $this->salida .= "<form name=\"data\" action=\"$accion\" method=\"post\">";

          $this->salida .= "<tr><td width=\"15%\" class=\"label\">CENTRO UTILIDAD: </td>";
          $this->salida .= "<td width=\"15%\" colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"centroutilidad\" value=\"".$_REQUEST['centroutilidad']."\" maxlength=\"100\" size=\"50\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"20%\" class=\"label\">UNIDAD FUNCIONAL: </td>";
          $this->salida .= "<td width=\"15%\" colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"unidadfunc\" value=\"".$_REQUEST['unidadfunc']."\" maxlength=\"100\" size=\"50\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"15%\" class=\"label\">DEPARTAMENTO: </td>";
          $this->salida .= "<td width=\"15%\" colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$_REQUEST['departamento']."\" maxlength=\"100\" size=\"50\" readonly>";
          $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:xxx(1)\"><img title='Busqueda del Departamento' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></td></tr>";

          $this->salida .= "<input type=\"hidden\" name=\"centroU\" class=\"input-text\">";
          $this->salida .= "<input type=\"hidden\" name=\"unidadF\" class=\"input-text\">";
          $this->salida .= "<input type=\"hidden\" name=\"DptoSel\" class=\"input-text\">";

          $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td colspan=\"2\"><select name=\"TipoDocumento\" class=\"select\">";
          $this->salida .= "<option value=-1 selected>--  SELECCIONE --</option>";

          if($_REQUEST['TipoDocumento']=='*')
          {$this->salida .= "<option value=\"*\" selected>--  TODOS  --</option>";}
          //else
          //{$this->salida .= "<option value=\"*\">--  TODOS  --</option>";}
          $tipo_id=$this->tipo_id_paciente();
          $this->BuscarIdPaciente($tipo_id,$_REQUEST['TipoDocumento']);
          $this->salida .= "</select></td></tr>";

          $this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=".$_REQUEST['Documento']."></td></tr>";
          $this->salida .= "<tr><td class=\"label\">NOMBRE: </td><td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"nombres\" maxlength=\"32\" value=\"".$_REQUEST['nombres']."\"></td></tr>";

          $this->salida .= "<tr><td class=\"label\">SERVICIO: </td><td colspan=\"2\"><select name=\"servicio\" class=\"select\">";
          $this->salida .= "<option value=-1 selected>--  SELECCIONE --</option>";
          if($_REQUEST['servicio']=='*')
          {$this->salida .= "<option value=\"*\" selected>--  TODOS  --</option>";}
          //else
          //{$this->salida .= "<option value=\"*\">--  TODOS  --</option>";}
          $vector=$this->Get_Servicios();
          $this->GetHtmlServicio($vector,$_REQUEST['servicio']);
          $this->salida .= "                  </select></td></tr>";

          /*nuevo Tizziano Perea*/
          $this->salida .= "<tr><td class=\"label\">TIPO DE HISTORIA: </td><td colspan=\"2\"><select name=\"tipo_historia\" class=\"select\">";
          $this->salida .= "<option value=-1 selected>--  SELECCIONE --</option>";
          if($_REQUEST['tipo_historia']=='*')
          {$this->salida .= "<option value=\"*\" selected>--  TODOS  --</option>";}
          //else
          //{$this->salida .= "<option value=\"*\">--  TODOS  --</option>";}
          $vector_hc=$this->Get_hc_modulos();
          $this->GetHtmlHistoria($vector_hc,$_REQUEST['tipo_historia']);
          $this->salida .= "</select></td></tr>";
          /*fin nuevo Tizzino Perea*/

          /*nuevo Tizziano Perea*/
          $this->salida .= "<tr><td class=\"label\">PROFESIONAL: </td><td colspan=\"2\"><select name=\"profesional_escojer\" class=\"select\">";
          $this->salida .= "<option value=-1 selected>--  SELECCIONE --</option>";
          if($_REQUEST['profesional_escojer']=='*')
          {$this->salida .= "<option value=\"*\" selected>--  TODOS  --</option>";}
          //else
          //{$this->salida .= "<option value=\"*\">--  TODOS  --</option>";}
          $vector_P=$this->Get_Profesionales();
          $this->GetHtmlProfesional($vector_P,$_REQUEST['profesional_escojer']);
          $this->salida .= "</select></td></tr>";
          /*fin nuevo Tizzino Perea*/

          $this->salida .= "<tr><td class=\"label\">FECHA</td>";
          $this->salida .= "<td align=\"left\" class=\"label\" width=\"50%\" colspan=\"2\">DESDE &nbsp;<input type=\"text\" class=\"input-text\" name=\"fechaini\" size='11' maxlength=\"10\" READONLY value=\"".$_REQUEST['fechaini']."\"><sub>".ReturnOpenCalendario('data','fechaini','-')."</sub>&nbsp;&nbsp;HASTA &nbsp;<input type=\"text\" class=\"input-text\" name=\"fechafin\" size='11' maxlength=\"10\" READONLY value=\"".$_REQUEST['fechafin']."\"><sub>".ReturnOpenCalendario('data','fechafin','-')."</sub></label></td></tr>";

          $mostrar2 ="\n<script language='javascript'>\n";
          $mostrar2.="  function cambioHTML(obj){\n";
          $mostrar2.="  if(obj.selectedIndex==1)\n";
          $mostrar2.="  {document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.evo_oculto.value=this.value' name='evolucion' value=''>\";}\n";

          $mostrar2.="  else if(obj.selectedIndex==2)\n";
          $mostrar2.="  {document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.ing_oculto.value=this.value' name='ingreso' value=''>\"}\n";

          $mostrar2.="  else if(obj.selectedIndex==3)\n";
          $mostrar2.="  {document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.cuenta_oculto.value=this.value' name='cuenta' value=''>\"}\n";

          $mostrar2.="  else if(obj.selectedIndex==4)\n";
          $mostrar2.="  {document.getElementById('cambio').innerHTML=\"<input type='text' class='input-text' OnChange='document.data.pre_oculto.value=this.value' size='4' name=prefijo' value=''> -- <input type='text' class='input-text' OnChange='document.data.fac_oculto.value=this.value' name='factura' value=''>\"}\n";
          $mostrar2.="  };\n";

          $mostrar2.="</script>\n";
          $this->salida .="$mostrar2";

          //".$_REQUEST['evo_oculto']." ".$_REQUEST['ing_oculto']." ".$_REQUEST['cuenta_oculto']." ".$_REQUEST['pre_oculto']." ".$_REQUEST['fac_oculto']."

          $this->salida .= "<input type=\"hidden\" name=\"evo_oculto\" value=\"\" class=\"input-text\">";
          $this->salida .= "<input type=\"hidden\" name=\"ing_oculto\" value=\"\" class=\"input-text\">";
          $this->salida .= "<input type=\"hidden\" name=\"cuenta_oculto\" value=\"\" class=\"input-text\">";
          $this->salida .= "<input type=\"hidden\" name=\"pre_oculto\" value=\"\" class=\"input-text\">";
                    $this->salida .= "<input type=\"hidden\" name=\"fac_oculto\" value=\"\" class=\"input-text\">";

          $this->salida .= "<tr><td class=\"label\">OPCION BUSQUEDA: </td><td colspan=\"2\"><select name=\"parametros\" class=\"select\" OnChange=\"cambioHTML(this);\">";
          $this->salida .= "<option value=-1 selected>--  SELECCIONE --</option>";
          $this->salida .= "<option value=\"1\"> EVOLUCION</option>";
          $this->salida .= "<option value=\"2\"> INGRESO</option>";
          $this->salida .= "<option value=\"3\"> CUENTA</option>";
          $this->salida .= "<option value=\"4\"> FACTURA</option>";
          $this->salida .= "</select></td></tr>";
          $this->salida .= "<tr><td>&nbsp;</td><td class=\"label\"><div id=\"cambio\" name=\"valor\"></div></td>";
          $this->salida .= "</tr>";

                    $this->salida .= "</table>";
                    $this->salida .= "<table width=\"95%\" align=\"center\" border=\"0\">";
          $this->salida .= "<tr><td align='center' colspan=\"$col\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSQUEDA\"></td>";
          $this->salida .= "</form>";
          $actionM=ModuloGetURL('app','AuditoriaMedica','user','FormaMenus');
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<td align=\"left\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
          $this->salida .= "<td align=\"left\"><br><input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"BORRAR CASILLAS\" onclick='limpiar();'></td>";
                $this->salida .= "<td align=\"left\"><br><input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"BORRAR FECHAS\" onclick='LimpiarFechas();'></td>";
                    $this->salida .= "</tr>";
                    //$this->salida .= "</table>";

          $this->salida .= "</fieldset></td></tr></table>";
          $this->salida .= "</table>";
          $this->salida .= "</td>";

          $this->salida .= "</tr>";
          $this->salida .= "</table>";
          /*if($mensaje){
               $accionT=ModuloGetURL('app','Facturacion','user','main',array('TipoCuenta'=>$TipoCuenta));
               $this->salida .= "           <p class=\"label_error\" align=\"center\">$mensaje</p>";
               $this->salida .= "           <form name=\"formar\" action=\"$accionT\" method=\"post\">";
          }*/


          if (empty($this->dos)){
            $this->salida.="<table border=\"0\" align=\"center\"  width=\"100%\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida.="</table>";
          }
            if(!empty($arr) AND !empty($f))
            {
                    $mostrar ="\n<script language='javascript'>\n";
                    $mostrar.="function mOvr(src,clrOver) {;\n";
                    $mostrar.="src.style.background = clrOver;\n";
                    $mostrar.="}\n";

                    $mostrar.="function mOut(src,clrIn) {\n";
                    $mostrar.="src.style.background = clrIn;\n";
                    $mostrar.="}\n";
                    $mostrar.="</script>\n";
                    $this->salida .="$mostrar";

                    $this->salida .= "      <table class=\"modulo_table_title\" width=\"80%\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" >";
                    $vector=array();//reiniciamos el vector q va a comparar.

                    $backgrounds=array('modulo_list_claro'=>'#F4F4F4','modulo_list_oscuro'=>'#F4F4F4');
                    $reporte= new GetReports();
                    for($i=0;$i<sizeof($arr);$i++)
                    {
                         if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_claro';}

                         if($arr[$i][tipo_id_paciente].$arr[$i][paciente_id]<> $_var)
                         {
                              $this->salida .= "            <tr align=\"center\" class=\"modulo_table_title\">";
                              $this->salida .= "                <td width=\"10%\">Identificacion</td>";
                              $this->salida .= "                <td width=\"50%\">Datos Paciente</td>";
                              $this->salida .= "                <td width=\"10%\">Historia C</td>";
                              $this->salida .= "            </tr>";

                              $this->salida.="<tr  bgcolor='#F4F4F4' align='center'  onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#F4F4F4');>";
                              $this->salida.="  <td><label class='label_mark'>".$arr[$i][tipo_id_paciente]."&nbsp; - &nbsp;".$arr[$i][paciente_id]."</label></td>";
                              $this->salida.="  <td><label class='label_mark'>".$arr[$i][nombre]."</label></td>";

                              //Esta linea era la q funcionaba actualmente no borrar
                              //$this->salida .= "<td width=\"10%\"onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a href=".ModuloGetURL('app','Os_Atencion','user','FrmOrdenar',array('tipoid'=>$arr[$i]['tipo_id_paciente'],'idp'=>$arr[$i]['paciente_id'],'nombre'=>urlencode($arr[$i]['nombre'])))."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" border='0' width='10' height='10'>&nbsp;&nbsp;VER</a></td>";
                              $mostrar3=$reporte->GetJavaReport_HC($arr[$i][ingreso],array());
                              $funcion2=$reporte->GetJavaFunction();
                              $this->salida.=$mostrar3;
                              $this->salida.="  <td width=\"10%\" ><a href=\"javascript:$funcion2\"><img src=\"". GetThemePath() ."/images/historial.png\" border='0' title='HISTORIA CLINICA'></a></td>";
                              $this->salida.="</tr>";
                              $_var=$arr[$i][tipo_id_paciente].$arr[$i][paciente_id];

                              $this->salida.="<tr class='modulo_list_oscuro'>";
                              $this->salida .= "<td  colspan='3'>";

                              if( $i % 2){ $estilo1='modulo_list_claro';}
                              else {$estilo1='modulo_list_claro';}


                              $this->salida .= "        <table class=\"hc_table_list\" width=\"100%\" border=\"1\" align=\"center\" >";
                              $this->salida .= "            <tr class=\"hc_table_submodulo_list_title\" align=\"center\" >";
                              $this->salida .= "                <td width=\"9%\">Ingreso</td>";
                              $this->salida .= "                <td width=\"10%\">Fecha Ingreso</td>";
                              $this->salida .= "                <td width=\"9%\">Evolucion</td>";
                              $this->salida .= "                <td width=\"9%\">Fecha Evol.</td>";
                              $this->salida .= "                <td width=\"20%\">Departamento</td>";
                              $this->salida .= "                <td width=\"20%\">Servicio</td>";
                              $this->salida .= "                <td width=\"5%\"></td>";
                              $this->salida .= "            </tr>";

                              $this->salida.="<tr  class='$estilo1' align='center'>";

                              $USR = $this->TraerUsuario($arr[$i][usuario_id]);

                              //Cambio: Antes estaba el mecanismo de Impresion.
                              $ActionIngreso = ModuloGetURL('app','AuditoriaMedica','user','FormaAdicion_NotaAuditoria',array('desc'=>$arr[$i][desc],
                              'descripcion'=>$arr[$i][descripcion],'fecha_ingreso'=>$arr[$i][fecha_ingreso],'estado'=>$arr[$i][estado],'evolucion_busqueda'=>$arr[$i][evolucion_id],
                              'ingreso'=>$arr[$i][ingreso],'nombre'=>$arr[$i][nombre],'paciente_id'=>$arr[$i][paciente_id],'tipo_id_paciente'=>$arr[$i][tipo_id_paciente],
                              'profesional'=>$USR, 'nueva_evolucion'=>$arr[$i][evolucion_id], 'fecha_nuevaEvo'=>$arr[$i][fecha], 'AC'=>'si',
                              "centroutilidad"=>$_REQUEST['centroutilidad'],"unidadfunc"=>$_REQUEST['unidadfunc'],
                              "departamento"=>$_REQUEST['departamento'],"centroU"=>$_REQUEST['centroU'],
                              "unidadF"=>$_REQUEST['unidadF'],"DptoSel"=>$_REQUEST['DptoSel'],
                              "TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],
                              "nombres"=>$_REQUEST['nombres'],"servicio"=>$_REQUEST['servicio'],
                              "tipo_historia"=>$_REQUEST['tipo_historia'],
                              "profesional_escojer"=>$_REQUEST['profesional_escojer'],"fechaini"=>$_REQUEST['fechaini'],
                              "fechafin"=>$_REQUEST['fechafin'],"parametros"=>$_REQUEST['parametros']));

                              $this->salida.="  <td><a href=\"$ActionIngreso\">".$arr[$i][ingreso]."</a></td>";

                              $fecha=explode(" ",$arr[$i][fecha_ingreso]);
                              $this->salida.="  <td>".$fecha[0]."</td>";

                              //Cambio: Antes estaba el mecanismo de Impresion.
                              $ActionEvo = ModuloGetURL('app','AuditoriaMedica','user','FormaAdicion_NotaAuditoria',array('desc'=>$arr[$i][desc],
                              'descripcion'=>$arr[$i][descripcion],'fecha_ingreso'=>$arr[$i][fecha_ingreso],'estado'=>$arr[$i][estado],'evolucion_busqueda'=>$arr[$i][evolucion_id],
                              'ingreso'=>$arr[$i][ingreso],'nombre'=>$arr[$i][nombre],'paciente_id'=>$arr[$i][paciente_id],'tipo_id_paciente'=>$arr[$i][tipo_id_paciente],
                              'profesional'=>$USR, 'nueva_evolucion'=>$arr[$i][evolucion_id], 'fecha_nuevaEvo'=>$arr[$i][fecha],
                              "centroutilidad"=>$_REQUEST['centroutilidad'],"unidadfunc"=>$_REQUEST['unidadfunc'],
                              "departamento"=>$_REQUEST['departamento'],"centroU"=>$_REQUEST['centroU'],
                              "unidadF"=>$_REQUEST['unidadF'],"DptoSel"=>$_REQUEST['DptoSel'],
                              "TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],
                              "nombres"=>$_REQUEST['nombres'],"servicio"=>$_REQUEST['servicio'],
                              "tipo_historia"=>$_REQUEST['tipo_historia'],
                              "profesional_escojer"=>$_REQUEST['profesional_escojer'],"fechaini"=>$_REQUEST['fechaini'],
                              "fechafin"=>$_REQUEST['fechafin'],"parametros"=>$_REQUEST['parametros']));

                              $this->salida.="  <td><a href=\"$ActionEvo\">".$arr[$i][evolucion_id]."</a></td>";

                              $fechaevo=explode(" ",$arr[$i][fecha]);
                              $this->salida.="  <td>".$fechaevo[0]."</td>";

                              $this->salida.="  <td>".$arr[$i][desc]."</td>";
                              $this->salida.="  <td>".$arr[$i][descripcion]."</td>";

                              $a=$e="";
                              if($arr[$i][estado]==1)
                              {$a='activo.gif';$e='ingreso activo';}else{$a='inactivo.gif';$e='ingreso inactivo';}
                              $this->salida.="  <td><img src=\"". GetThemePath() ."/images/$a\" border='0' width='12' height='12'  title='$e'></td></tr>";

                              $this->salida .= "<tr class='$estilo1'>";
                              $this->salida .= "<td class=\"hc_table_submodulo_list_title\">Profesional:</td>";
                              $this->salida .= "<td colspan=\"6\" class='$estilo1'>".$USR[nombre]."&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;".strtoupper($USR[usuario])."</td>";
                              $this->salida .= "</tr>";

                              $this->salida .= "            </table>";//fin tabla de ingresos
                              $this->salida.="</td></tr>";

                         }
                         else
                         {
                              $USR = $this->TraerUsuario($arr[$i][usuario_id]);

                              $this->salida.="<tr class='modulo_list_oscuro'>";
                              $this->salida .= "<td  colspan='3'>";

                              if( $i % 2){ $estilo1='modulo_list_claro';}
                              else {$estilo1='modulo_list_claro';}

                              $this->salida .= "        <table  width=\"100%\"  border=\"1\" class=\"hc_table_list\" align=\"center\" >";
                              $this->salida .= "            <tr class=\"hc_table_submodulo_list_title\" align=\"center\" >";
                              $this->salida .= "                <td width=\"9%\">Ingreso</td>";
                              $this->salida .= "                <td width=\"10%\">Fecha Ingreso</td>";
                              $this->salida .= "                <td width=\"9%\">Evolucion</td>";
                              $this->salida .= "                <td width=\"9%\">Fecha Evol.</td>";
                              $this->salida .= "                <td width=\"20%\">Departamento</td>";
                              $this->salida .= "                <td width=\"20%\">Servicio</td>";
                              $this->salida .= "                <td width=\"5%\"></td>";
                              $this->salida .= "            </tr>";

                              $this->salida.="<tr  class='$estilo1' align='center'>";

                              //Cambio: Antes estaba el mecanismo de Impresion.
                              $ActionIngreso = ModuloGetURL('app','AuditoriaMedica','user','FormaAdicion_NotaAuditoria',array('desc'=>$arr[$i][desc],
                              'descripcion'=>$arr[$i][descripcion],'fecha_ingreso'=>$arr[$i][fecha_ingreso],'estado'=>$arr[$i][estado],'evolucion_busqueda'=>$arr[$i][evolucion_id],
                              'ingreso'=>$arr[$i][ingreso],'nombre'=>$arr[$i][nombre],'paciente_id'=>$arr[$i][paciente_id],'tipo_id_paciente'=>$arr[$i][tipo_id_paciente],
                              'profesional'=>$USR, 'nueva_evolucion'=>$arr[$i][evolucion_id], 'fecha_nuevaEvo'=>$arr[$i][fecha], 'AC'=>'si',
                              "centroutilidad"=>$_REQUEST['centroutilidad'],"unidadfunc"=>$_REQUEST['unidadfunc'],
                              "departamento"=>$_REQUEST['departamento'],"centroU"=>$_REQUEST['centroU'],
                              "unidadF"=>$_REQUEST['unidadF'],"DptoSel"=>$_REQUEST['DptoSel'],
                              "TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],
                              "nombres"=>$_REQUEST['nombres'],"servicio"=>$_REQUEST['servicio'],
                              "tipo_historia"=>$_REQUEST['tipo_historia'],
                              "profesional_escojer"=>$_REQUEST['profesional_escojer'],"fechaini"=>$_REQUEST['fechaini'],
                              "fechafin"=>$_REQUEST['fechafin'],"parametros"=>$_REQUEST['parametros']));

                              $this->salida.="  <td><a href=\"$ActionIngreso\">".$arr[$i][ingreso]."</a></td>";

                              $fecha=explode(" ",$arr[$i][fecha_ingreso]);
                              $this->salida.="  <td>".$fecha[0]."</td>";

                              //Cambio: Antes estaba el mecanismo de Impresion.
                              $ActionEvo = ModuloGetURL('app','AuditoriaMedica','user','FormaAdicion_NotaAuditoria',array('desc'=>$arr[$i][desc],
                              'descripcion'=>$arr[$i][descripcion],'fecha_ingreso'=>$arr[$i][fecha_ingreso],'estado'=>$arr[$i][estado],'evolucion_busqueda'=>$arr[$i][evolucion_id],
                              'ingreso'=>$arr[$i][ingreso],'nombre'=>$arr[$i][nombre],'paciente_id'=>$arr[$i][paciente_id],'tipo_id_paciente'=>$arr[$i][tipo_id_paciente],
                              'profesional'=>$USR, 'nueva_evolucion'=>$arr[$i][evolucion_id], 'fecha_nuevaEvo'=>$arr[$i][fecha],
                              "centroutilidad"=>$_REQUEST['centroutilidad'],"unidadfunc"=>$_REQUEST['unidadfunc'],
                              "departamento"=>$_REQUEST['departamento'],"centroU"=>$_REQUEST['centroU'],
                              "unidadF"=>$_REQUEST['unidadF'],"DptoSel"=>$_REQUEST['DptoSel'],
                              "TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],
                              "nombres"=>$_REQUEST['nombres'],"servicio"=>$_REQUEST['servicio'],
                              "tipo_historia"=>$_REQUEST['tipo_historia'],
                              "profesional_escojer"=>$_REQUEST['profesional_escojer'],"fechaini"=>$_REQUEST['fechaini'],
                              "fechafin"=>$_REQUEST['fechafin'],"parametros"=>$_REQUEST['parametros']));

                              $this->salida.="  <td><a href=\"$ActionEvo\">".$arr[$i][evolucion_id]."</a></td>";

                              $fechaevo=explode(" ",$arr[$i][fecha]);
                              $this->salida.="  <td>".$fechaevo[0]."</td>";

                              $this->salida.="  <td>".$arr[$i][desc]."</td>";
                              $this->salida.="  <td>".$arr[$i][descripcion]."</td>";
                              $a=$e="";
                              if($arr[$i][estado]==1)
                              {$a='activo.gif';$e='ingreso activo';}else{$a='inactivo.gif';$e='ingreso inactivo';}
                              $this->salida.="  <td><img src=\"". GetThemePath() ."/images/$a\" border='0' width='12' height='12' title='$e'></td></tr>";

                              $this->salida .= "<tr class='$estilo1'>";
                              $this->salida .= "<td class=\"hc_table_submodulo_list_title\">Profesional:</td>";
                              $this->salida .= "<td colspan=\"6\" class='$estilo1'>".$USR[nombre]."&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;".strtoupper($USR[usuario])."</td>";
                              $this->salida .= "</tr>";

                              $this->salida .= "            </table>";//fin tabla de ingresos
                              $this->salida.="</td></tr>";

                         }
                   }
               $this->salida.="</table>";
               $this->conteo=$_SESSION['SPY'];
               $this->salida .=$this->RetornarBarra1();
          }
          $this->salida .= ThemeCerrarTabla();
          return true;
    }
        //nuevo dar
        function LlenaVector()
        {
            $this->salida .= "<script>\n";
            $this->salida .= "      function LlenaVector(forma)\n";
            $this->salida .= "      {\n";
            $this->salida .= "        var v;\n";
            $this->salida .= "        alert('entro');\n";
            //$this->salida .= "              v=forma.sel_tipoauditoria.value;\n";
            //$this->salida .= "              a=v.split('||');\n";

            //$this->salida .= "       ".$_SESSION['AUDITORIA']['VECTOR']."=a[0];\n";
            $this->salida .= "        \n";
            $this->salida .= "      }\n";
            $this->salida .= "</script>\n";
        }


     function FormaAdicion_NotaAuditoria()
     {
          $desc = $_REQUEST['desc'];
          $descripcion = $_REQUEST['descripcion'];
          $fecha_ingreso = $this->FechaStamp($_REQUEST['fecha_ingreso']);
          $fecha_evo = $this->FechaStamp($_REQUEST['fecha_nuevaEvo']);
          $estado = $_REQUEST['estado'];
          $evolucion_busqueda = $_REQUEST['evolucion_busqueda'];
          $ingreso = $_REQUEST['ingreso'];
          $nueva_evolucion = $_REQUEST['nueva_evolucion'];
          $profesional = $_REQUEST['profesional'];
          $nombre = $_REQUEST['nombre'];
          $paciente_id = $_REQUEST['paciente_id'];
          $tipo_id_paciente = $_REQUEST['tipo_id_paciente'];
          //variables para el filtro
          if($_SESSION['DATOS_BUSQUEDA']['lleno']!=1){
            $_SESSION['DATOS_BUSQUEDA']['centroutilidad']=$_REQUEST['centroutilidad'];
            $_SESSION['DATOS_BUSQUEDA']['unidadfunc']=$_REQUEST['unidadfunc'];
            $_SESSION['DATOS_BUSQUEDA']['departamento']=$_REQUEST['departamento'];
            $_SESSION['DATOS_BUSQUEDA']['centroU']=$_REQUEST['centroU'];
            $_SESSION['DATOS_BUSQUEDA']['unidadF']=$_REQUEST['unidadF'];
            $_SESSION['DATOS_BUSQUEDA']['DptoSel']=$_REQUEST['DptoSel'];
            $_SESSION['DATOS_BUSQUEDA']['TipoDocumento']=$_REQUEST['TipoDocumento'];
            $_SESSION['DATOS_BUSQUEDA']['Documento']=$_REQUEST['Documento'];
            $_SESSION['DATOS_BUSQUEDA']['nombres']=$_REQUEST['nombres'];
            $_SESSION['DATOS_BUSQUEDA']['servicio']=$_REQUEST['servicio'];
            $_SESSION['DATOS_BUSQUEDA']['tipo_historia']=$_REQUEST['tipo_historia'];
            $_SESSION['DATOS_BUSQUEDA']['profesional_escojer']=$_REQUEST['profesional_escojer'];
            $_SESSION['DATOS_BUSQUEDA']['fechaini']=$_REQUEST['fechaini'];
            $_SESSION['DATOS_BUSQUEDA']['fechafin']=$_REQUEST['fechafin'];
            $_SESSION['DATOS_BUSQUEDA']['parametros']=$_REQUEST['parametros'];
            $_SESSION['DATOS_BUSQUEDA']['lleno']=1;
          }
          
          if($_REQUEST['AC'] == 'si')
          {
            $evolucion_print = '-1';
          }
          else
          {
            $evolucion_print = $nueva_evolucion;
          }


          $this->salida = ThemeAbrirTabla('AUDITORIA');
                    $this->LlenaVector();
//           $AccionCerrar = ModuloGetURL('app','AuditoriaMedica','user','cerrarCasoAuditoria',array('desc'=>$desc,
//                'descripcion'=>$descripcion,'fecha_ingreso'=>$fecha_ingreso,'estado'=>$estado,'evolucion_busqueda'=>$evolucion_busqueda,
//                'ingreso'=>$ingreso,'nombre'=>$nombre,'paciente_id'=>$paciente_id,'tipo_id_paciente'=>$tipo_id_paciente));

          $actionInsert=ModuloGetURL('app','AuditoriaMedica','user','Insertar_NotaAuditoria',array('desc'=>$desc,
                         'descripcion'=>$descripcion,'fecha_ingreso'=>$fecha_ingreso,'estado'=>$estado,'evolucion_busqueda'=>$evolucion_busqueda,
                         'ingreso'=>$ingreso,'nombre'=>$nombre,'paciente_id'=>$paciente_id,'tipo_id_paciente'=>$tipo_id_paciente, 'evolucion_print'=>$evolucion_print,
                         'profesional'=>$profesional, 'nueva_evolucion'=>$nueva_evolucion, 'fecha_nuevaEvo'=>$fecha_evo));
          $this->salida .= "<form name=\"forma\" action=\"$actionInsert\" method=\"post\">";

          $this->salida .="<table class=\"modulo_table_title\" width=\"80%\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\">";

          $backgrounds=array('modulo_list_claro'=>'#F4F4F4','modulo_list_oscuro'=>'#F4F4F4');
          $reporte= new GetReports();

          $this->salida .= "<tr align=\"center\" class=\"modulo_table_title\">";
          $this->salida .= "<td width=\"10%\">Identificacion</td>";
          $this->salida .= "<td width=\"50%\">Datos Paciente</td>";
          $this->salida .= "<td width=\"10%\">Historia C</td>";
          $this->salida .= "</tr>";

          $this->salida.="<tr bgcolor='#F4F4F4' align='center'  onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#F4F4F4');>";
          $this->salida.="<td><label class='label_mark'>".$tipo_id_paciente."&nbsp; - &nbsp;".$paciente_id."</label></td>";
          $this->salida.="<td><label class='label_mark'>".$nombre."</label></td>";

          $mostrar3=$reporte->GetJavaReport_HC($ingreso,array());
          $funcion2=$reporte->GetJavaFunction();
          $this->salida.=$mostrar3;
          $this->salida.="  <td width=\"10%\" ><a href=\"javascript:$funcion2\"><img src=\"". GetThemePath() ."/images/historial.png\" border='0' title='HISTORIA CLINICA'></a></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr>";
          $this->salida .= "<td  colspan='3'>";
          $estilo1='modulo_list_claro';
          $this->salida .= "<table class=\"hc_table_list\" width=\"100%\" border=\"1\" align=\"center\" >";
          $this->salida .= "<tr class=\"hc_table_submodulo_list_title\" align=\"center\" >";
          $this->salida .= "<td width=\"9%\">Ingreso</td>";
          $this->salida .= "<td width=\"9%\">Fecha Ingreso</td>";
          $this->salida .= "<td width=\"9%\">Evolucion</td>";
          $this->salida .= "<td width=\"9%\">Fecha Evol.</td>";
          $this->salida .= "<td width=\"20%\">Departamento</td>";
          $this->salida .= "<td width=\"20%\">Servicio</td>";
          $this->salida .= "<td width=\"5%\"></td>";
          $this->salida .= "</tr>";

          $this->salida.="<tr class='$estilo1' align='center'>";
          $this->salida.="  <td>".$ingreso."</td>";
          $this->salida.="  <td>".$fecha_ingreso."</td>";
          $this->salida.="  <td>".$nueva_evolucion."</td>";
          $this->salida.="  <td>".$fecha_evo."</td>";
          $this->salida.="  <td>".$desc."</td>";
          $this->salida.="  <td>".$descripcion."</td>";
          $a=$e="";
          if($estado==1)
          {$a='activo.gif';$e='ingreso activo';}else{$a='inactivo.gif';$e='ingreso inactivo';}
          $this->salida.=" <td><img src=\"". GetThemePath() ."/images/$a\" border='0' width='12' height='12'  title='$e'></td></tr>";

          $this->salida .= "<tr class='$estilo1'>";
          $this->salida .= "<td class=\"hc_table_submodulo_list_title\">Profesional:</td>";
          $this->salida .= "<td colspan=\"6\" class='$estilo1'>".$profesional[nombre]."&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;".strtoupper($profesional[usuario])."</td>";
          $this->salida .= "</tr>";

          $this->salida.="</table>";//fin tabla de ingresos
          $this->salida.="</td>";

          /*$this->salida .= "<td><select name=\"evolucion_print\" class=\"select\">";
          $this->salida .= "<option align=\"center\" value=\"-1\" selected>--  EVOLUCIONES --</option>";
          $vector_Evo=$this->Get_Evoluciones($ingreso);
          $this->GetHtmlEvoluciones($vector_Evo,$_REQUEST['evolucion_print']);
          $this->salida .= "</select></td>";
          $this->salida .= "</tr>";*/
          $this->salida.="</table><br>";

//           $mostrar2 ="\n<script language='javascript'>\n";
//           $mostrar2.="  function Cancelar(){\n";
//           $mostrar2.="  var evojava2;";
//           $mostrar2.="  evojava2 = '$AccionCerrar&EvolucionSelect='+document.formainsert.evolucion_print.options[document.formainsert.evolucion_print.selectedIndex].value;\n";
//           $mostrar2.="  window.location.href=evojava2;\n";
//           $mostrar2.="  };\n";
//           $mostrar2.="</script>\n";
//           $this->salida .="$mostrar2";

          $this->salida.="<table border=\"0\" align=\"center\"  width=\"100%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";

          $accion=ModuloHCGetURL($evolucion_busqueda,'','','','');//esto  es el llamado de consulta a la hc 500 es el nuemro de evolucion
          $this->salida .= "<table align=\"center\" width=\"100%\">";
          $this->salida .= "<tr>";
          $this->salida .= "<td align=\"center\" width=\"100%\">";
          $this->salida .= "<IFRAME border=\"0\" SRC='$accion' TITLE='HISTORIA CLINICA' width=\"80%\" height=\"600\">";
          $this->salida .= "</IFRAME>";
          $this->salida .= "</td>";
          $this->salida .= "</tr>";
          $this->salida .= "</table><br>";
          $this->salida .= "<table width=\"80%\" border=\"1\" align=\"center\" class=\"modulo_list_oscuro\" cellspacing=\"3\" cellpadding=\"3\">";
          $this->salida .= "<tr>";
          $this->salida .= "<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" align=\"center\" class=\"label\">NOTA DE AUDITORIA</td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr>";
          if ($_REQUEST['confirmar_insert'] != '1')
          {
            $this->salida .= "<td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\"><textarea cols=\"90\" rows=\"5\" class=\"textarea\" style=\"width:100%\" name=\"nota_auditoria\">".$_REQUEST['nota_auditoria']."</textarea></td>";
          }
          else
          {
            $this->salida .= "<td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\"><textarea cols=\"90\" rows=\"5\" class=\"textarea\" style=\"width:100%\" name=\"nota_auditoria\"></textarea></td>";
          }
          $this->salida .= "</tr>";
          $this->salida .= "<tr>";
					//CAMBIO DAR
          $this->IncludeJS("RemoteScripting");
          $this->IncludeJS("RemoteScripting/funciones.js","app","AuditoriaMedica");
          $this->salida .= "<td class=\"modulo_list_claro\" align=\"center\"><label class=\"".$this->SetStyle("nota_auditoria_tipo_id")."\">TIPO DE AUDITORIA: </label></td><td align=\"left\" class=\"modulo_list_claro\"><select name=\"sel_tipoauditoria\" class=\"select\" onChange=\"LlenarVector(this.value)\">";
          $this->salida .= "<option align=\"center\" value=\"-1\" selected>-- SELECCIONE --</option>";
          $vector_tipo=$this->Get_TipoAuditoria();
          $this->GetHtmlTipoAuditoria($vector_tipo,$_REQUEST['sel_tipoauditoria'],$_REQUEST['confirmar_insert']);
          $this->salida .= "</select></td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr class=\"modulo_list_oscuro\">";
          $this->salida .= "<td colspan=\"2\">";			
          $this->salida .= "<div id='tipo_auditoria'>";
					if(!empty($_SESSION['AUDITORIA']['VECTOR']))
					{
								$this->salida .= "<table width=100%  cellspacing=\"3\" cellpadding=\"3\">";
								foreach($_SESSION['AUDITORIA']['VECTOR'] as $k => $v)
								{
											$this->salida .= "<tr class=modulo_list_claro>";
											$this->salida .= "<td width=90%><li class=modulo_list_claro>";
											$this->salida .= $v['descripcion'];
											$this->salida .= "</li></td>";
											$this->salida .= "<td width=10% class=\"label\"><a href=\"javascript:Eliminar('".$v['id']."')\">ELIMINAR</a></td>";
											$this->salida .= "</tr>";
								}
								$this->salida .= "</table>";
					}					
					$this->salida .= "</div>";
          $this->salida .= "</td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr>";
          $this->salida .= "<td colspan=\"2\" class=\"modulo_list_claro\" align=\"left\">";
          $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
          $this->salida .= "<label class=\"label\">PRIVACIDAD: </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
          $this->salida .= "<select name=\"privacidad\" class=\"select\">";
          $this->salida .= "<option align=\"center\" value=\"-1\" selected>-- SELECCIONE --</option>";

          if ($_REQUEST['privacidad']=='0' AND $_REQUEST['confirmar_insert'] != '1')
          {$this->salida .= "<option align=\"center\" value=\"0\" selected>AUDITOR</option>";}
          else
          {$this->salida .= "<option align=\"center\" value=\"0\">AUDITOR</option>";}

          if ($_REQUEST['privacidad']=='1' AND $_REQUEST['confirmar_insert'] != '1')
          {$this->salida .= "<option align=\"center\" value=\"1\" selected>AUDITORES Y PROFESIONALES</option>";}
          else
          {$this->salida .= "<option align=\"center\" value=\"1\">AUDITORES Y PROFESIONALES</option>";}
          if ($_REQUEST['privacidad']=='2' AND $_REQUEST['confirmar_insert'] != '1')
          {$this->salida .= "<option align=\"center\" value=\"2\" selected>AUDITOR EXTERNO</option>";}
          else
          {$this->salida .= "<option align=\"center\" value=\"2\">AUDITOR EXTERNO</option>";}
          if ($_REQUEST['privacidad']=='3' AND $_REQUEST['confirmar_insert'] != '1')
          {$this->salida .= "<option align=\"center\" value=\"3\" selected>AUDITOR INTERNO</option>";}
          else
          {$this->salida .= "<option align=\"center\" value=\"3\">AUDITOR INTERNO</option>";}

          $this->salida .= "</select>";
          $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
          $this->salida .= "<label class=\"label\">PRIORIDAD: </label>&nbsp;&nbsp;&nbsp;&nbsp;";
          $this->salida .= "<select name=\"prioridad\" class=\"select\">";
          //$this->salida .= "<option align=\"center\" value=\"-1\" selected>-- SELECCIONE --</option>";

          $this->salida .= "<input type=\"hidden\" name=\"confirmar_insert\" value=\"0\" class=\"input-text\">";

          if ($_REQUEST['prioridad']=='2' AND $_REQUEST['confirmar_insert'] != '1')
          {$this->salida .= "<option align=\"center\" value=\"2\" selected>ALTA</option>";}
          else
          {$this->salida .= "<option align=\"center\" value=\"2\">ALTA</option>";}
          if ($_REQUEST['prioridad']=='1' AND $_REQUEST['confirmar_insert'] != '1')
          {$this->salida .= "<option align=\"center\" value=\"1\" selected>MEDIA</option>";}
          else
          {$this->salida .= "<option align=\"center\" value=\"1\">MEDIA</option>";}
          if ($_REQUEST['prioridad']=='0' AND $_REQUEST['confirmar_insert'] != '1')
          {$this->salida .= "<option align=\"center\" value=\"0\" selected>BAJA</option>";}
          else
          {$this->salida .= "<option align=\"center\" value=\"0\">BAJA</option>";}
					//por defecto es esta
					if($_REQUEST['prioridad']==-1 OR empty($_REQUEST['prioridad']))
					{$this->salida .= "<option align=\"center\" value=\"2\" selected>ALTA</option>";}

          $this->salida .= "</select></td>";
          $this->salida .= "</tr>";

          $this->salida .= "<tr>";
          $this->salida .= "<td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\">";
          $this->salida .= "<label class=\"label\">REQUIER RESPUESTA </label>";
					$this->salida .= "<input type=\"radio\" name=\"responder\" value=\"1\">&nbsp;&nbsp;&nbsp;&nbsp;";
          $this->salida .= "<label class=\"label\">VER PROFESIONAL </label>";
					$this->salida .= "<input type=\"radio\" name=\"responder\" value=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;";
          $this->salida .= "<label class=\"label\">NINGUNA </label>";
					$this->salida .= "<input type=\"radio\" name=\"responder\" value=\"0\">";
					

         /* if($_REQUEST['responder']=='1' AND $_REQUEST['confirmar_insert'] != '1')
          {$this->salida .= "<input type=\"checkbox\" name=\"responder\" checked value=\"1\">";}
          else
          {$this->salida .= "<input type=\"checkbox\" name=\"responder\" value=\"1\">";}

          $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
          $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
          $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

          $this->salida .= "<label class=\"label\">VER PROFESIONAL?: </label>";
          if($_REQUEST['ver_profesional']=='1' AND $_REQUEST['confirmar_insert'] != '1')
          {$this->salida .= "<input type=\"checkbox\" name=\"ver_profesional\" checked value=\"1\">";}
          else
          {$this->salida .= "<input type=\"checkbox\" name=\"ver_profesional\" value=\"1\">";}
					*/
	        $this->salida .= "</td>";
          $this->salida .= "</tr>";					
					
         /* $this->salida .= "<tr>";
          $this->salida .= "<td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\">";
          $this->salida .= "<label class=\"label\">REQUIER RESPUESTA?: </label>";

          if($_REQUEST['responder']=='1' AND $_REQUEST['confirmar_insert'] != '1')
          {$this->salida .= "<input type=\"checkbox\" name=\"responder\" checked value=\"1\">";}
          else
          {$this->salida .= "<input type=\"checkbox\" name=\"responder\" value=\"1\">";}

          $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
          $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
          $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

          $this->salida .= "<label class=\"label\">VER PROFESIONAL?: </label>";
          if($_REQUEST['ver_profesional']=='1' AND $_REQUEST['confirmar_insert'] != '1')
          {$this->salida .= "<input type=\"checkbox\" name=\"ver_profesional\" checked value=\"1\">";}
          else
          {$this->salida .= "<input type=\"checkbox\" name=\"ver_profesional\" value=\"1\">";}
	        $this->salida .= "</td>";
          $this->salida .= "</tr>";*/					

          $this->salida .= "<tr>";
          $this->salida .= "<td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"INSERTAR\"></td>";
          $this->salida .= "</tr>";
          $Datos = $this->BusquedaNota($ingreso);
          if(!empty($Datos))
          {
               $this->salida .= "<tr>";
               $this->salida .= "<td colspan=\"2\" class=\"hc_table_submodulo_list_title\" align=\"center\">NOTAS DE AUDITORIA</td>";
               $this->salida .= "</tr>";
               for($xx=0; $xx<sizeof($Datos); $xx++)
               {
                    $this->salida .= "<tr>";
                    $this->salida .= "<td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"left\">";
                    $this->salida .= "<b>Ingreso: </b>".$Datos[$xx][ingreso]."";
                    if(!empty($Datos[$xx][evolucion_id]))
                    {
                         $this->salida .= "<b> - Evolucion: </b>".$Datos[$xx][evolucion_id]."";
                    }

                    if (!empty($Datos[$xx][fecha_registro]))
                    {
                        $fechaN = $this->FechaStamp($Datos[$xx][fecha_registro]);
                         $this->salida .= "<b> - Fecha: </b>".$fechaN;
                    }

                    if(!empty($Datos[$xx][evolucion_id]))
                    {
                         $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                         $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    }else
                    {
                         $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                      $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    }

                    if($Datos[$xx][estado] != 0)
                    {
                         if($Datos[$xx][sw_prioridad] == 0)
                         { $tipo_prioridad = "BAJA";}
                         elseif($Datos[$xx][sw_prioridad] == 1)
                         { $tipo_prioridad = "MEDIA";}
                         else
                         { $tipo_prioridad = "ALTA";}

                         $this->salida .= "<B>PRIORIDAD:</b> $tipo_prioridad";
                    }

                    if (!empty($Datos[$xx][usuario_id]))
                    {
                        if($Datos[$xx][sw_tipo_auditor] == 1)
                         { $tipo_auditor = "<b>AUDITOR INTERNO: </b>";}
                         else{$tipo_auditor = "<b>AUDITOR EXTERNO: </b>";}
                         $this->salida .= "<br>$tipo_auditor".strtoupper($Datos[$xx][nombre]);
                         $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                      	$this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

                         if($Datos[$xx][estado] != 0)
                         {
                              if($Datos[$xx][sw_responder] == 1)
                              { $tipo_answer = "SI";}
                              else{$tipo_answer = "NO";}

                              $this->salida .= "<B>RESPONDER NOTA</B>: $tipo_answer";
                         }
                    }

                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
                    $this->salida .= "<tr>";
                    $this->salida .= "<td colspan=\"2\" class=\"modulo_list_claro\" align=\"justify\">";
                    $this->salida .= "<b>Nota: </b>".$Datos[$xx][nota]."";
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
               }
          }

          $this->salida .= "</form>";
          $this->salida .= "</table>";

          $actionM=ModuloGetURL('app','AuditoriaMedica','user','BuscarOrden');
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<table align=\"center\" width=\"100%\">";
          $this->salida .= "<tr>";
          $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
          $this->salida .= "</tr>";
          $this->salida .= "</table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }




     function RetornarBarra1(){
          if($this->limit>=$this->conteo){
                return '';
        }
        //if($filtro){$_SESSION['USUARIOS']['FILTRO']=$filtro;}//esto guarda el filtro...
        //de busqueda...
          $paso=$_REQUEST['paso'];
        if(empty($paso)){
            $paso=1;
        }
        $vec='';
        foreach($_REQUEST as $v=>$v1)
        {
            if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
            {   $vec[$v]=$v1;   }
        }

        $accion=ModuloGetURL('app','AuditoriaMedica','user','BuscarOrden',$vec);
        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;

        $this->salida .= "<br><table width='22%' border='0'  align='center' cellspacing=\"5\"  cellpadding=\"1\"><tr><td width='20%' class='label' bgcolor=\"#D3DCE3\">Páginas</td>";
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
            $this->salida .= "</tr><tr><td  class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td><tr></table>";
            //$this->salida.="</table>";
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
        $this->salida .= "</tr><tr><td   class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td><tr></table>";

        }

    }
    //fin de las funciones para la barra de segnentacion


     /**
     *
     */
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

     function FechaStamp($fecha)
    {
        if($fecha){
                $fech = strtok ($fecha,"-");
                for($l=0;$l<3;$l++)
                {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
                }
                return  ceil($date[2])." - ".ceil($date[1])." - ".ceil($date[0]);
        }
    }


//----------------------------------------------------------------------------------------------------

}//fin clase

?>

