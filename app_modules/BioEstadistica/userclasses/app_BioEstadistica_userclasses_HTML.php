<?php

/**
 * $Id: app_BioEstadistica_userclasses_HTML.php,v 1.25 2009/07/17 13:09:14 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos visuales para realizar las autorizaciones.
 */

class app_BioEstadistica_userclasses_HTML extends app_BioEstadistica_user
{

     function app_BioEstadistica_user_HTML()
	{
          $this->salida='';
          $this->app_BioEstadistica_user();
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
	{

          $this->salida = ThemeAbrirTabla('MENU BIOESTADISTICA');
          $GetSoloLectura = $this->GetSoloLectura();
          //print_r($GetSoloLectura);
	  if($GetSoloLectura[sw_solo_lectura] == '0' AND $GetSoloLectura[sw_modificar_datos] == '0')
	  {
	  $this->salida .= "<center><label class=\"label_error\">USUARIO SIN PERMISOS</label></center>";
	  
	  }
	  if($GetSoloLectura[sw_solo_lectura] == '1')

	  {
	  $this->salida .= "<center><label class=\"label_error\">USUARIO CON PERMISOS DE SOLO LECTURA</label></center>";
	  }
 	  if($GetSoloLectura[sw_modificar_datos] == '1')
	  {
	  $this->salida .= "<center><label class=\"label_error\">USUARION CON PERMISOS DE MODIFICACION DE DATOS DEL PACIENTE</label></center>";
	  }
          $this->salida .= "            <br>";
          $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
          $this->salida .= "               <tr>";
          $this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU BIOESTADISTICA</td>";
          $this->salida .= "               </tr>";
	  if($GetSoloLectura[sw_modificar_datos] == '1')
	  {
          $this->salida .= "               <tr>";
          $accionF=ModuloGetURL('app','BioEstadistica','user','LlamarFormaBuscarPaciente');
          $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a title='PERMITE ACTUALIZAR LA INFORMACI?N DEL PACIENTE' href=\"$accionF\">MODIFICAR INFORMACION PACIENTE</a></td>";
          $this->salida .= "               </tr>";
          }
	  if($GetSoloLectura[sw_solo_lectura] == '1')
	  {
          $this->salida .= "               <tr>";
          $accionF=ModuloGetURL('app','BioEstadistica','user','FormaMetodoBuscar',array("sw_copiar_pegar"=>$GetSoloLectura['sw_copiar_pegar']));
          $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a title='PERMITE BUSCAR LOS INGRESOS,EVOLUCIONES E IMPRIMIR LA HISTORIA CLINICA DEL PACIENTE' href=\"$accionF\">BUSQUEDA DE INFORMACION DEL PACIENTE</a></td>";
          $this->salida .= "               </tr>";
	  }
          $this->salida .= "           </table>";
          
          $accion=ModuloGetURL('app','BioEstadistica','user','main');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></p>";
          $this->salida .= "</form>";
          $this->salida .= ThemeCerrarTabla();
          return true;
	}

	function FormaBuscarPaciente($arr)
	{
          $this->SetJavaScripts('DatosPaciente');
          $this->salida .= ThemeAbrirTabla('BIOESTADISTICA - BUSCAR DATOS PACIENTE');
		//--------------------------
          $accion=ModuloGetURL('app','BioEstadistica','user','BuscarPaciente');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<table class=\"modulo_list_claro\" border=\"0\" width=\"40%\" align=\"center\">";
          $this->salida .= "<tr class=\"modulo_table_list_title\">";
          $this->salida .= "<td align = left colspan=\"2\">CRITERIOS DE BUSQUEDA:</td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr class=\"modulo_list_claro\" >";
          $this->salida .= "<td width=\"40%\" colspan=\"2\">";
          $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
          $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
          $tipo_id=$this->TiposIdPacientes();
          $this->salida .=" <option value=\"\">----SELECCIONE---</option>";
          for($i=0; $i<sizeof($tipo_id); $i++)
          {
               $this->salida .=" <option value=\"".$tipo_id[$i]['tipo_id_paciente']."\">".$tipo_id[$i]['descripcion']."</option>";
               if($_REQUEST['TipoDocumento']==$tipo_id[$i]['tipo_id_paciente'])
               {
                    $this->salida .=" <option value=\"".$tipo_id[$i]['tipo_id_paciente']."\" selected>".$tipo_id[$i]['descripcion']."</option>";
               }
          }
          $this->salida .= "                  </select></td></tr>";
          $this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
          $this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\"></td></tr>";
          $this->salida .= "  </table>";
          $this->salida .= "</td></tr>";
          $this->salida .= "<tr class=\"modulo_list_claro\">";
        	$this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
          $this->salida .= "            </form>";
        	$accion=ModuloGetURL('app','BioEstadistica','user','FormaMenus');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        	$this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></td>";
          $this->salida .= "            </form>";
          $this->salida .= "</tr>";
          $this->salida .= "  </table>";
          //mensaje
          $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= "  </table>";
          if($arr)
          {
               $this->salida .= "		   <br>";
               $this->salida .= "		<table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
               $this->salida .= "				<td>IDENTIFICACION</td>";
               $this->salida .= "				<td>NOMBRE</td>";
               $this->salida .= "				<td></td>";
               $this->salida .= "			</tr>";
               for($i=0;$i<sizeof($arr);$i++)
               {
                    if( $i % 2) $estilo='modulo_list_claro';
                    else $estilo='modulo_list_oscuro';
                    $this->salida .= "			<tr class=\"$estilo\">";
                    $this->salida .= "				<td width=\"20%\">".$arr[$i][tipo_id_paciente]."  ".$arr[$i][paciente_id]."</td>";
                    $dato=RetornarWinOpenDatosPaciente($arr[$i][tipo_id_paciente],$arr[$i][paciente_id],$arr[$i][nombre]);
                    $this->salida .= "				<td width=\"36%\">".$dato."</td>";
                    $accion1=ModuloGetURL('app','BioEstadistica','user','LlamarModificarDatosPaciente',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso]));
                    $this->salida .= "				<td align=\"center\" width=\"15%\"><a href=\"$accion1\">MODIFICAR DATOS</a></td>";
                    $this->salida .= "			</tr>";
               }//fin for
               $this->salida .= " </table>";
               $this->conteo=$_SESSION['SPY'];
               $this->salida .=$this->RetornarBarra();
          }
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
		$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['BIO']['NOM_EMP']."</td>";
		$this->salida .= " <td class=\"modulo_list_claro\">BIOESTADISTICA</td>";
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
                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
               }else{
                    $this->salida .=" <option value=\"$value\">$titulo</option>";
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
	* Esta funcion realiza la busqueda de las ordenes de servicio seg?n filtros como numero de orden
	* documento y plan
	* @return boolean
	*/
	function FormaMetodoBuscar($Busqueda,$arr,$f)
	{
  $request=$_REQUEST;
  
  //print_r($request);
          unset($_SESSION['SEGURIDAD']);

					$this->salida.= ThemeAbrirTabla('INFORMACI?N DEL PACIENTE');
          $this->Encabezado();
          
          if($_REQUEST['sw_copiar_pegar'])
            SessionSetVar("sw_copiar_pegar",$_REQUEST['sw_copiar_pegar']);
					
          $RUTA = "app_modules/BioEstadistica/buscador.php?sign=";
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="var rem=\"\";\n";
          $mostrar.="  function xxx(a){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"width=500,height=450,resizable=no,location=no, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA';\n";
          $mostrar.="    url2 +=a;\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";
			
          $mostrar.="  function limpiar(){\n";
          $mostrar.="  document.data.nombres.value='';\n";
          $mostrar.="  document.data.fechaini.value='';\n";
          $mostrar.="  document.data.fechafin.value='';\n";
          $mostrar.="  document.data.ing.value='';\n";
          $mostrar.="  document.data.egreso.value='';\n";
          $mostrar.="  document.data.finalidad.value='';\n";
          $mostrar.="  document.data.Documento.value='';\n";
          $mostrar.="  document.data.hc_evol.value='';\n";
          $mostrar.="  };\n";
          $mostrar.="</script>\n";
          $this->salida .="$mostrar";
          
          if(!$Busqueda){ $Busqueda=1; }
          $accion=ModuloGetURL('app','BioEstadistica','user','BuscarOrden');
          $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
          $this->salida .= "	<tr>";
          $this->salida .= "	   <td width=\"80%\" >";
          $this->salida .= "     <br><table border=\"0\" width=\"90%\" align=\"center\">";
          $this->salida .= "      <tr><td><fieldset><legend class=\"field\">BUSCADOR AVANZADO</legend>";
          $this->salida .= "	      <table width=\"95%\" align=\"center\" border=\"0\">";
          $this->salida .= "        <form name=\"data\" action=\"$accion\" method=\"post\">";
          $this->salida .= "        <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
          $this->salida .= "        <option value=-1 selected>--  SELECCIONE --</option>";

          if($_REQUEST['TipoDocumento']=='*')
          {$this->salida .= "       <option value=\"*\" selected>--  TODOS  --</option>";}
          else
          {$this->salida .= "       <option value=\"*\">--  TODOS  --</option>";}

          $tipo_id=$this->tipo_id_paciente();
          $this->BuscarIdPaciente($tipo_id,$_REQUEST['TipoDocumento']);
          $this->salida .= "                  </select></td></tr>";
          if($_REQUEST['meg']  ==on){$check_meg  ='checked';}else{$check_meg  ='';}
          if($_REQUEST['mevol']==on){$check_mevol='checked';}else{$check_mevol='';}
          if($_REQUEST['mfil'] ==on){$check_mfil ='checked';}else{$check_mfil ='';}
          if($_REQUEST['ming'] ==on){$check_ming ='checked';}else{$check_ming ='';}
          
          if($_REQUEST['nope']  ==on){$check_nope   ='checked';}else{$check_nope   ='';}
          if($_REQUEST['justi'] ==on){$check_justi  ='checked';}else{$check_justi  ='';} 
          if($_REQUEST['contra']==on){$check_contra ='checked';}else{$check_contra ='';}
          if($_REQUEST['noan']  ==on){$check_noan   ='checked';}else{$check_noan   ='';}
          if($_REQUEST['examen']==on){$check_examen ='checked';}else{$check_examen ='';}
          
          $this->salida .= "        <tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=".$_REQUEST['Documento']."></td></tr>";
          $this->salida .= "        <tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"nombres\" maxlength=\"32\" value=".$_REQUEST['nombres']."></td></tr>";
          $this->salida .= "        <tr><td class=\"label\">SERVICIO: </td><td><select name=\"servicio\" class=\"select\">";
          $this->salida .= "        <option value=-1 selected>--  SELECCIONE --</option>";

          if($_REQUEST['servicio']=='*')
          {$this->salida .= "       <option value=\"*\" selected>--  TODOS  --</option>";}
          else
          {$this->salida .= "       <option value=\"*\">--  TODOS  --</option>";}

          $vector=$this->Get_Servicios();
          $this->GetHtmlServicio($vector,$_REQUEST['servicio']);
          $this->salida .= "         </select></td></tr>";

          /*nuevo Tizziano Perea*/
          $this->salida .= "<tr><td class=\"label\">HISTORIA: </td><td><select name=\"tipo_historia\" class=\"select\">";
          $this->salida .= "<option value=-1 selected>--  SELECCIONE --</option>";

          if($_REQUEST['tipo_historia']=='*')
          {$this->salida .= "<option value=\"*\" selected>--  TODOS  --</option>";}
          else
          {$this->salida .= "<option value=\"*\">--  TODOS  --</option>";}

          $vector_hc=$this->Get_hc_modulos();
          $this->GetHtmlHistoria($vector_hc,$_REQUEST['tipo_historia']);
          $this->salida .= "</select></td></tr>";
          /*fin nuevo Tizzino Perea*/

          $this->salida .= "<tr><td class=\"label\">FECHA</td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"fechaini\" size='11' maxlength=\"10\" READONLY value=\"".$_REQUEST['fechaini']."\"><sub>".ReturnOpenCalendario('data','fechaini','-')."</sub>&nbsp;<input type=\"text\" class=\"input-text\" name=\"fechafin\" size='11' maxlength=\"10\" READONLY value=\"".$_REQUEST['fechafin']."\"><sub>".ReturnOpenCalendario('data','fechafin','-')."</sub></label></td></tr>";
          $this->salida .= "<tr><td class=\"label\">No EVOLUCION: </td><td><input type=\"text\" class=\"input-text\"  size='14' name=\"hc_evol\" maxlength=\"14\"  value=".$_REQUEST['hc_evol'].">&nbsp;&nbsp;<input type=checkbox name='mevol' $check_mevol>&nbsp;&nbsp;<label class='label_mark'><sub>MOSTRAR</sub></label></td>";
			
          $this->salida .= "<tr><td class=\"label\">DIAGNOSTICO INGRESO: </td><td><input type=\"text\" class=\"input-text\"  size='32' name=\"ing\" maxlength=\"32\" READONLY value=".$_REQUEST['ing'].">&nbsp;&nbsp;<a href='javascript:xxx(1)'><img title='buscar por diagnostico de ingreso' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></a>&nbsp; &nbsp;<input type=checkbox name='ming' $check_ming>&nbsp;<label class='label_mark'><sub>MOSTRAR </sub></label></td>";
          $this->salida .= "</td></tr>";
          $this->salida .= "<tr><td class=\"label\">DIAGNOSTICO EGRESO: </td><td><input type=\"text\" class=\"input-text\" size='32' name=\"egreso\" maxlength=\"32\" READONLY value=".$_REQUEST['egreso'].">&nbsp;&nbsp;<a href='javascript:xxx(2)'><img title='buscar por diagnostico de egreso' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></a>&nbsp; &nbsp;<input type=checkbox name='meg' $check_meg>&nbsp;<label class='label_mark'><sub>MOSTRAR </sub></label></td>";
          $this->salida .= "</td></tr>";
          
          $this->salida .= "<tr><td class=\"".$this->SetStyle("IngresoId")."\">FINALIDAD</td><td><input type=\"text\" size='32'  class=\"input-text\" name=\"finalidad\"  READONLY maxlength=\"32\" value=".$_REQUEST['finalidad'].">&nbsp;&nbsp;<a href='javascript:xxx(3)'><img title='buscar por diagnostico de finalidad' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></a>&nbsp; &nbsp;<input type=checkbox name='mfil' $check_mfil>&nbsp;<label class='label_mark'><sub>MOSTRAR </sub></label></td></tr>";
          
          $style =" style=\"border-left-width: 1px ;border-left: thin solid; border-color: #000000;\" ";

          $this->salida .= "<tr>\n";
          $this->salida .= "  <td colspan=\"2\">\n";
          $this->salida .= "    <table width=\"100%\">\n";
          $this->salida .= "      <tr class=\"label\">\n";
          $this->salida .= "        <td >Notas Operatorias</td>\n";
          $this->salida .= "        <td width=\"5\"><input type=checkbox name='nope' $check_nope></td>\n";
          $this->salida .= "        <td ".$style.">Justificaci?n Med. No Pos</td>\n";
          $this->salida .= "        <td width=\"5\"><input type=checkbox name='justi' $check_justi></td>\n";
          $this->salida .= "        <td ".$style.">Control Transfusiones</td>\n";
          $this->salida .= "        <td width=\"5\"><input type=checkbox name='contra' $check_contra></td>\n";
          $this->salida .= "        <td ".$style.">Notas Anestesicas</td>\n";
          $this->salida .= "        <td width=\"5\"><input type=checkbox name='noan' $check_noan></td>\n";
          $this->salida .= "        <td ".$style.">Examenes</td>\n";
          $this->salida .= "        <td width=\"5\"><input type=checkbox name='examen' $check_examen></td>\n";
          $this->salida .= "        <td ".$style.">Reserva Sangre</td>\n";
          $this->salida .= "        <td width=\"5\"><input type=checkbox name='reserva_sangre' $check_examen></td>\n";
          $this->salida .= "        <td ".$style.">Transfusion Sanguinea</td>\n";
          $this->salida .= "        <td width=\"5\"><input type=checkbox name='transfusion_sanguinea' $check_examen></td>\n";
          $this->salida .= "    </table>\n";
          $this->salida .= "  </td>\n";
          $this->salida .= "</tr>\n";
          
          $this->salida .= "<input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
          $this->salida .= "<input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";

          $this->salida .= "<tr><td align='center' colspan=\"$col\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
          $this->salida .= "</form>";
          $actionM=ModuloGetURL('app','BioEstadistica','user','FormaMenus');
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<td align=\"left\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
          
          $this->salida .= "<td align=\"left\"><br><input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"BORRAR CASILLAS\" onclick='limpiar();'></td>";

          $this->salida .= "</tr>";
          $this->salida .= "</fieldset></td></tr></table>";
          $this->salida .= "</table>";
          $this->salida .= "</td>";

          $this->salida .= "</tr>";
          $this->salida .= "</table>";
          if($mensaje){
               $accionT=ModuloGetURL('app','Facturacion','user','main',array('TipoCuenta'=>$TipoCuenta));
               $this->salida .= "			<p class=\"label_error\" align=\"center\">$mensaje</p>";
               $this->salida .= "           <form name=\"formar\" action=\"$accionT\" method=\"post\">";
          }
			
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

               $this->salida .= "		<table class=\"modulo_table_title\" width=\"80%\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" >";
               $vector=array();//reiniciamos el vector q va a comparar.

               $backgrounds=array('modulo_list_claro'=>'#F4F4F4','modulo_list_oscuro'=>'#F4F4F4');
               $reporte = new GetReports();

               for($i=0;$i<sizeof($arr);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_claro';}

                    if($arr[$i][tipo_id_paciente].$arr[$i][paciente_id]<> $_var)
                    {
                         $this->salida .= "			<tr align=\"center\" class=\"modulo_table_title\">";
                         $this->salida .= "				<td width=\"10%\">Identificacion</td>";
                         $this->salida .= "				<td width=\"50%\">Datos Paciente</td>";
                         $this->salida .= "				<td width=\"15%\" align=\"center\">HC&nbsp;&nbsp;|| &nbsp;&nbsp; Epicrisis </td>";
                         $this->salida .= "			</tr>";
                         $this->salida .= "     <tr  bgcolor='#F4F4F4' align='center'  onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#F4F4F4');>";
                         $this->salida .= "       <td><label class='label_mark'>".$arr[$i][tipo_id_paciente]."&nbsp; - &nbsp;".$arr[$i][paciente_id]."</label></td>";
                         $this->salida .= "       <td><label class='label_mark'>".$arr[$i][nombre]."</label></td>";
     
                         //Esta linea era la q funcionaba actualmente no borrar
                         //$this->salida .= "<td width=\"10%\"onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a href=".ModuloGetURL('app','Os_Atencion','user','FrmOrdenar',array('tipoid'=>$arr[$i]['tipo_id_paciente'],'idp'=>$arr[$i]['paciente_id'],'nombre'=>urlencode($arr[$i]['nombre'])))."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" border='0' width='10' height='10'>&nbsp;&nbsp;VER</a></td>";
                         $mostrar3=$reporte->GetJavaReport_HC($arr[$i][ingreso],array());
                         $funcion2=$reporte->GetJavaFunction();
                         $this->salida.=$mostrar3;
                         $this->salida.="  <td width=\"10%\" align=\"center\"><a href=\"javascript:$funcion2\"><img align=\"left\" src=\"". GetThemePath() ."/images/historial.png\" border='0' title='HISTORIA CLINICA'></a>";
                         
                         $mostrarEPI=$reporte->GetJavaReport_Epicrisis($arr[$i][ingreso],array());
                         $funcionEPI=$reporte->GetJavaFunction();
                         $this->salida .=$mostrarEPI;
                         $this->salida.="<a href=\"javascript:$funcionEPI\"><img align=\"right\" src=\"". GetThemePath() ."/images/folder_lleno.png\" border='0' title='RESUMEN EPICRISIS'></a>";

                         $this->salida.="</tr>";

                         $_var=$arr[$i][tipo_id_paciente].$arr[$i][paciente_id];
                         
                         $this->salida .= "<tr class='modulo_list_oscuro'>";
                         $this->salida .= "<td  colspan='3'>";
                         
                         if( $i % 2){ $estilo1='modulo_list_claro';}
                         else {$estilo1='modulo_list_claro';}
     
                         $this->salida .= "		<table class=\"hc_table_list\" width=\"100%\" border=\"1\" align=\"center\" >";
                         $this->salida .= "			<tr class=\"hc_table_submodulo_list_title\" align=\"center\" >";
                         $this->salida .= "				<td width=\"10%\">Ingreso</td>";
                         $this->salida .= "				<td width=\"10%\">Nueva Epicrisis</td>";
                         $this->salida .= "				<td width=\"10%\">Epicrisis</td>";
                         $this->salida .= "				<td width=\"25%\">Departamento</td>";
                         $this->salida .= "				<td width=\"25%\">Servicio</td>";
                         $this->salida .= "				<td width=\"25%\">Fecha</td>";
                         $this->salida .= "				<td width=\"5%\"></td>";
                         $this->salida .= "			</tr>";
                         $this->salida .= "			</tr>";
     
                         $this->salida.="<tr  class='$estilo1' align='center'>";
                         $mostrar3=$reporte->GetJavaReport_HC($arr[$i][ingreso],array());
                         $funcion2=$reporte->GetJavaFunction();
                         $this->salida.=$mostrar3;
                         $this->salida.="  <td><a href=\"javascript:$funcion2\">".$arr[$i][ingreso]."</a></td>";
                         
                         $epi = $this->GetDatosEpicrisis($arr[$i][ingreso]);
                         if($epi)
                         {
                              $mostrarT=$reporte->GetJavaReport('hc','Epicrisis','ReporteEpicrisis',array('ingreso'=>$arr[$i][ingreso],'evolucion'=>$arr[$i][evolucion_id]),array('rpt_name'=>'Epicrisis'.$arr[$i][ingreso],'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                              $funcionT=$reporte->GetJavaFunction();
                              $this->salida .= "<td align=\"center\"><a href=\"javascript:$funcionT\"><img src=\"". GetThemePath() ."/images/imprimir.png\" border='0' title=\"EPICRISIS\"></a></td>\n";
                              $this->salida .= "$mostrarT";
                         }
                         else
                              $this->salida .= "<td align=\"center\">&nbsp;</td>\n";
                         
                         //Impresion Epicrisis por Ingreso.
                         $mostrarEPI1=$reporte->GetJavaReport_Epicrisis($arr[$i][ingreso],array());
                         $funcionEPI1=$reporte->GetJavaFunction();
                         $this->salida .=$mostrarEPI1;
                         $this->salida .= "<td><a href=\"javascript:$funcionEPI1\"><img src=\"". GetThemePath() ."/images/imprimir.png\" border='0' title='RESUMEN EPICRISIS'></a></td>";
                         
                         //Impresion Epicrisis por Ingreso.
                         $this->salida.="  <td>".$arr[$i][desc]."</td>";
                         $this->salida.="  <td>".$arr[$i][descripcion]."</td>";
                         $fecha=explode(" ",$arr[$i][fecha_ingreso]);
                         $this->salida.="  <td>".$fecha[0]."</td>";
                         $a=$e="";
                         if($arr[$i][estado]==1)
                         {$a='activo.gif';$e='ingreso activo';}else{$a='inactivo.gif';$e='ingreso inactivo';}
                         $this->salida.="  <td><img src=\"". GetThemePath() ."/images/$a\" border='0' width='12' height='12'  title='$e'></td></tr>";
     
                         if(!empty($arr[$i][tipo_consulta_id])){
                            $this->salida.="<tr class='$estilo1' align='center'>";
                            $this->salida.="  <td colspan=\"4\"><b>Profesional:</b> ".$arr[$i][tipo_id_tercero]." ".$arr[$i][tercero_id]." ".$arr[$i][profesional]."</td>";
                            $this->salida.="  <td colspan=\"3\"><b>Tipo Consulta:</b> ".$arr[$i][tipo_consulta]."</td>";
                            $this->salida.="</tr>";
                         }
                         
                         
                         //revisamos si se selecciono diagnosticos de ingresos	
                         if($_REQUEST['ming']==on)
                         {	
                              unset($arr_diag);
                              $arr_diag=$this->Get_Info_Diagnosticos($arr[$i][ingreso],$_REQUEST['ing'],1);
                              if(is_array($arr_diag))
                              {
                                   for($f=0;$f<sizeof($arr_diag);$f++)
                                   {
                                        $this->salida .= "			<tr align=\"center\" >";
                                        $this->salida .= "				<td class=\"hc_table_submodulo_list_title\"  colspan='2'>Diagnosticos Ingreso</td>";
                                                                           
                                        $this->salida .= "				<td colspan='3' class='$estilo1' width=\"25%\">";
                                        $this->salida .= "		<sub>".$arr_diag[$f][diagnostico_id]."-".$arr_diag[$f][diagnostico_nombre]."</sub></td></tr>";
                                   }	
                              }
                         }	
                                                  
                         //revisamos si se selecciono diagnosticos de egresos	
                         if($_REQUEST['meg']==on)
                         {	
                              unset($arr_eg);
                              $arr_eg=$this->Get_Info_Diagnosticos($arr[$i][ingreso],$_REQUEST['egreso'],2);
                              if(is_array($arr_eg))
                              {
                                   for($f=0;$f<sizeof($arr_eg);$f++)
                                   {
                                        $this->salida .= "			<tr align=\"center\" >";
                                        $this->salida .= "				<td class=\"hc_table_submodulo_list_title\"  colspan='2'>Diagnosticos Egreso</td>";
                                                                           
                                        $this->salida .= "				<td colspan='3' class='$estilo1' width=\"25%\">";
                                        $this->salida .= "		<sub>".$arr_eg[$f][diagnostico_id]."-".$arr_eg[$f][diagnostico_nombre]."</sub></td></tr>";
                                   }	
                              }
                         }	
                                             
                         //revisamos si se selecciono diagnosticos de egresos	
                         if($_REQUEST['mfil']==on)
                         {	
                              unset($arr_fil);
                              $arr_fil=$this->Get_Finalidad($arr[$i][ingreso],$_REQUEST['finalidad']);
                              if(is_array($arr_fil))
                              {
                                   for($f=0;$f<sizeof($arr_fil);$f++)
                                   {
                                        $this->salida .= "			<tr align=\"center\" >";
                                        $this->salida .= "				<td class=\"hc_table_submodulo_list_title\"  colspan='2'>Finalidad</td>";
                                                                           
                                        $this->salida .= "				<td colspan='3' class='$estilo1' width=\"25%\">";
                                        $this->salida .= "		<sub>".$arr_fil[$f][detalle]."</sub></td></tr>";
                                   }	
                              }
                         }	
                                   
                         //si este switche de evoluciones esta en On,entonces haremos la busqueda.
                         unset($arr_evol);
                         if($_REQUEST['mevol']==on)
                         {
                              $arr_evol=$this->Get_Info_Evoluciones($arr[$i][ingreso]);
                              if(is_array($arr_evol))
                              {
                                   $this->salida.="<tr   class='$estilo1' align='center'>";
                                   $this->salida.="  <td colspan='5'>";
                                   $this->salida .= "		<table  width=\"100%\"  border=\"0\" class=\"hc_table_list\" align=\"center\" >";
                                   for($r=0;$r<sizeof($arr_evol);$r++)
                                   {
                                        $this->salida.="<tr bgcolor='#F3FFFA' align='center'>";
                                        $mostrar1=$reporte->GetJavaReport_HistoriaClinica($arr_evol[$r][evolucion_id],array());
                                        $funcionI=$reporte->GetJavaFunction();
                                        $this->salida.=$mostrar1;
                                        $this->salida.="  <td><a href=\"javascript:$funcionI\"><label>Evol:&nbsp;".$arr_evol[$r][evolucion_id]."</label></a></td>";
                                        //$this->salida.="  <td><label class='label_mark'>Evol:&nbsp;".$arr_evol[$r][evolucion_id]."</label></td>";
                                        $fecha=explode(" ",$arr_evol[$r][fecha_cierre]);
                                        $this->salida.="  <td>".$fecha[0]."</td>";
                                        $this->salida.="  <td>".$arr_evol[$r][descripcion]."</td>";
                                        $this->salida.="  <td>".$arr_evol[$r][nombre]."</td></tr>";
                                   }
                                   $this->salida .= "			</table>";//fin tabla de evoluciones
                                   $this->salida.="  </td>";
                                   $this->salida.="  </tr>";
                              }												
                         }
                         //print_r($_REQUEST);
                         unset($arr_reservsang);
                         if($_REQUEST['transfusion_sanguinea']==on)
                         {
                              $arr_transfusang=$this->Get_Info_TransfusionSangre($arr[$i][ingreso]);
                              
                              if(is_array($arr_transfusang))
                              {
                                 //$this->salida .= "<pre>".print_r($arr,true)."</pre>";
                                 $this->salida .= "  <tr align=\"center\" class=\"hc_table_submodulo_list_title\" >\n";
                                 $this->salida .= "    <td colspan='7'>Transfusion Sanguinea</td>";
                                 $this->salida .= "  </tr>\n";
                                 
      
                                //$this->salida .= "<pre>".print_r($_SESSION['HC_EVOLUCION'][$usuario][$_SESSION['IMPRESIONHC']['EVOLUCION']][datosProfesional],true)."</pre>";
                                $tipo_id_paciente= $arr[0]['tipo_id_paciente'];                     
                                $paciente_id= $arr[0]['paciente_id'];                     
                                $nombre= $arr[0]['nombre'];                     
                                $edad_pacientep=$this->GetFechaPaciente($tipo_id_paciente,$paciente_id);
                                $profesional=$this->GetProfesionales();
                                //$this->salida .= "<pre>".print_r($profesional,true)."</pre>";
                                $edad_paciente=$edad_pacientep['edad']." A?os";
                                $nombre_tercero=$profesional['nombre'];
                                $tipo_id_tercero=$profesional['tipo_id_tercero'];
                                $tercero_id=$profesional['tercero_id'];
                               foreach($arr_transfusang as $kR1 => $dtlR)
                               {
                                  $mostrarReserv = $reporte->GetJavaReport('hc','TransfusionSanguinea','TransfusionSanguinea', array("ingreso"=>$arr[$i][ingreso],
                                                                                                                                                         "tipoidpaciente"=>$tipo_id_paciente,
                                                                                                                                                         "paciente"=>$paciente_id,
                                                                                                                                                         "nombre_paciente"=>$nombre,
                                                                                                                                                         "edad_paciente"=>$edad_paciente,
                                                                                                                                                         "nombre_tercero"=>$nombre_tercero,
                                                                                                                                                         "tipo_id_tercero"=>$tipo_id_tercero,
                                                                                                                                                          "tercero_id"=>$tercero_id,
                                                                                                                                                          "fecha_trans"=>$dtlR['fecha']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                                  
                                  
                                  //$mostrar = $rep->GetJavaReport('app','ImpresionHC','PlanTerapeutico',$paciente,array('rpt_name'=>'planTerapeutico','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                                  $funcionReserv = $reporte->GetJavaFunction();
                                  $this->salida .= "      <td class=\"modulo_list_claro\" colspan=\"7\" align=\"center\">\n";
                                  $this->salida .= $mostrarReserv;
                                  $this->salida .= "        <a href=\"javascript:".$funcionReserv."\" class =\"label_error\">\n";
                                  $this->salida .= "          <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>TRANSFUSION: ".$dtlR['fecha']."\n";
                                  $this->salida .= "        </a>\n";
                                  $this->salida .= "      </td>\n";
                                  $this->salida .= "    </tr>\n";
                               }
                            }												
                         }
                         
                         if($_REQUEST['reserva_sangre']==on)
                         {
                              $arr_reservsang=$this->Get_Info_RerservaSangre($arr[$i][ingreso]);
                              
                              if(is_array($arr_reservsang))
                              {
                                 //$this->salida .= "<pre>".print_r($arr,true)."</pre>";
                                 $this->salida .= "  <tr align=\"center\" class=\"hc_table_submodulo_list_title\" >\n";
                                 $this->salida .= "    <td colspan='7'>Reserva de Sangre</td>";
                                 $this->salida .= "  </tr>\n";
                                 
      
                                //$this->salida .= "<pre>".print_r($_SESSION['HC_EVOLUCION'][$usuario][$_SESSION['IMPRESIONHC']['EVOLUCION']][datosProfesional],true)."</pre>";
                                $tipo_id_paciente= $arr[0]['tipo_id_paciente'];                     
                                $paciente_id= $arr[0]['paciente_id'];                     
                                $nombre= $arr[0]['nombre'];                     
                                $edad_pacientep=$this->GetFechaPaciente($tipo_id_paciente,$paciente_id);
                                $profesional=$this->GetProfesionales();
                                //$this->salida .= "<pre>".print_r($profesional,true)."</pre>";
                                $edad_paciente=$edad_pacientep['edad']." A?os";
                                $nombre_tercero=$profesional['nombre'];
                                $tipo_id_tercero=$profesional['tipo_id_tercero'];
                                $tercero_id=$profesional['tercero_id'];
                                foreach($arr_reservsang as $kR1 => $dtlR)
                               {
                                 $mostrarReserv = $reporte->GetJavaReport('hc','ReservaSangre','ReservaSangre', array("ingreso"=>$arr[$i][ingreso],
                                                                                                                                                       "tipoidpaciente"=>$tipo_id_paciente,
                                                                                                                                                       "paciente"=>$paciente_id,
                                                                                                                                                       "nombre_paciente"=>$nombre,
                                                                                                                                                       "edad_paciente"=>$edad_paciente,
                                                                                                                                                       "nombre_tercero"=>$nombre_tercero,
                                                                                                                                                       "tipo_id_tercero"=>$tipo_id_tercero,
                                                                                                                                                        "tercero_id"=>$tercero_id,
                                                                                                                                                        "solicitud_id"=>$dtlR['solicitud_reserva_sangre_id']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                                
                                
                                //$mostrar = $rep->GetJavaReport('app','ImpresionHC','PlanTerapeutico',$paciente,array('rpt_name'=>'planTerapeutico','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                                $funcionReserv = $reporte->GetJavaFunction();
                                $this->salida .= "      <td class=\"modulo_list_claro\" colspan=\"7\" align=\"center\">\n";
                                $this->salida .= $mostrarReserv;
                                $this->salida .= "        <a href=\"javascript:".$funcionReserv."\" class =\"label_error\">\n";
                                $this->salida .= "          <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>RESERVA: ".$dtlR['solicitud_reserva_sangre_id']."\n";
                                $this->salida .= "        </a>\n";
                                $this->salida .= "      </td>\n";
                                $this->salida .= "    </tr>\n";
                              }
                            }												
                         }
                         /**/
                         
                        // Switche en ON para Notas Operatorias
                        $notas_OP = $examenes = array();
                        if($_REQUEST['nope']==on)
                        {
                          $notas_OP = $this->Get_Info_NotasOperatorias($arr[$i][ingreso]);
                        }
                        if($_REQUEST['examen'] == "on")
                        {
                          $examenes = $this->GetResultados($arr[$i][ingreso]);
                        }
                        
                        if(!empty($notas_OP) || !empty($examenes))
                        {
                          if(empty($notas_OP))
                            $vectorM = $examenes;
                          else if(empty($examenes))
                            $vectorM = $notas_OP;
                          else
                            $vectorM = array_merge($examenes,$notas_OP);
                                                      
                          $this->salida .= "  <tr align=\"center\" class=\"hc_table_submodulo_list_title\" >\n";
                          $this->salida .= "    <td colspan='3'>Evolucion</td>";
                          $this->salida .= "    <td colspan='2'>Nota Operatoria</td>";
                          $this->salida .= "    <td colspan='2'>Examenes</td>";
                          $this->salida .= "  </tr>\n";
                          
                          foreach($vectorM as $kR1 => $dtlR)
                          {
                            
                            $this->salida .= "  <tr align=\"center\" class='$estilo1' >\n";
                            $this->salida .= "    <td colspan='3'>".(($dtlR['evolucion_id'])? $dtlR['evolucion_id']: $dtlR['evolucion_examenes'])."</td>";
                            $this->salida .= "    <td colspan='2'>\n";
                            if($dtlR['evolucion_id'])
                            {
                              $mostrar = $reporte->GetJavaReport('app','BioEstadistica','reporteNotaOperatoria_html',array('programacion'=>$dtlR['programacion_id'],'tipoidpaciente'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],"ingreso"=>$arr[$i][ingreso]),array('rpt_name'=>'notaoperatoria'.$kR1,'rpt_dir'=>'','rpt_rewrite'=>TRUE));
                              $nombre_funcionOP = $reporte->GetJavaFunction();
                              $this->salida .=$mostrar;
                              $this->salida .= "      <a href=\"javascript:$nombre_funcionOP\" class=\"label_error\">\n";
                              $this->salida .= "        <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR\n";
                              $this->salida .= "      </a>\n";
                            }
                            $this->salida .= "      &nbsp;\n";
                            $this->salida .= "    </td>\n";
                            $this->salida .= "    <td colspan='2'>\n";
                            if($dtlR['evolucion_examenes'])
                            {
                              $dat['tipo_id_paciente'] = $arr[$i]['tipo_id_paciente'];
                              $dat['paciente_id'] = $arr[$i]['paciente_id'];
                              $dat['evolucion_solicitud'] = $dtlR['evolucion_examenes'];
                              $mostrar = $reporte->GetJavaReport('app','ImpresionHC','examenesresultados',$dat,array('rpt_name'=>'examenesresultados'.$kR1,'rpt_dir'=>'','rpt_rewrite'=>TRUE));
                              $funcionEx = $reporte->GetJavaFunction();
                              
                              $this->salida .= $mostrar;
                              $this->salida .= "      <a href=\"javascript:".$funcionEx."\" class=\"label_error\">\n";
                              $this->salida .= "        <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR\n";
                              $this->salida .= "      </a>\n";
                            }
                            $this->salida .= "      &nbsp;\n";
                            $this->salida .= "    </td>\n";
                            $this->salida .= "  </tr>\n";
                          }	
                        }
                        
                         // Switche en ON para Justificacion de medicamentos No Pos
                         if($_REQUEST['justi']==on)
                         {
                              unset($J_NoPos);
                              $J_NoPos = $this->Get_Info_Justificacion_NOPOS($arr[$i][ingreso]);
                              if(is_array($J_NoPos))
                              {
                                   for($f=0;$f<sizeof($J_NoPos);$f++)
                                   {
                                        $this->salida .= "<tr align=\"center\" >";
                                        $this->salida .= "<td class=\"hc_table_submodulo_list_title\"  colspan='3'>Justificaci?n Med. NO POS</td>";

                                   	//Imprimir Justificaci?n NO POS
                                        $mostrar = $reporte->GetJavaReport('app','BioEstadistica','BIO_JustificacionMED_NO_POS_html',array('justificacion_id'=>$J_NoPos[$f][justificacion_no_pos_id]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                                        $nombre_funcionJ_NOPOS = $reporte->GetJavaFunction();
                                        $this->salida .=$mostrar;
                                                                           
                                        $this->salida .= "<td colspan='4' class='$estilo1' width=\"25%\" align=\"left\">";
                                        $this->salida .= "<a href=\"javascript:$nombre_funcionJ_NOPOS\">ID. Justificaci?n: ".$J_NoPos[$f][justificacion_no_pos_id]."&nbsp&nbsp&nbsp<img src=\"".GetThemePath()."/images/pparamed.png\" border='0'></a></td></tr>";
                                   }	
                              }
                              else
                              {
                               $Justificacion = $this->DatosJustificaciones_amb($arr[$i][ingreso]);
                                if(!empty($Justificacion))
                                {
                                
                                  //$Justificacion = $this->DatosJustificaciones_amb($arr[$i][ingreso]);
                                  if(is_array($Justificacion))
                                  {
                                      for($f=0;$f<sizeof($Justificacion);$f++)
                                      {
                                         $this->salida .= "<tr align=\"center\" >";
                                         $this->salida .= "<td class=\"hc_table_submodulo_list_title\"  colspan='3'>Justificaci?n Med. NO POS</td>";
                                         $mostrar = $reporte->GetJavaReport('app','BioEstadistica','Justificacion_NO_POS_html',array('evolucion_id'=>$Justificacion[$f][evolucion_id],'codigo_producto'=>$Justificacion[$f][codigo_producto]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                                         $nombre_funcionJ_NOPOS = $reporte->GetJavaFunction();
                                         $this->salida .=$mostrar;
                                         $this->salida .= "<td colspan='4' class='$estilo1' width=\"25%\" align=\"left\">";
                                         $this->salida .= "<a href=\"javascript:$nombre_funcionJ_NOPOS\">ID. Justificaci?n: ".$Justificacion[$f][hc_justificaciones_no_pos_amb]."&nbsp&nbsp&nbsp<img src=\"".GetThemePath()."/images/pparamed.png\" border='0'></a></td></tr>";
                                  
                                      
                                      }
                                  }
                                
                                }
                              
                              }
                         }

                         $this->salida .= "			</table>";//fin tabla de ingresos
                         $this->salida.="</td></tr>";
                    }
                    else
                    {
                         $this->salida.="<tr class='modulo_list_oscuro'>";
                         $this->salida .= "<td  colspan='3'>";
                         
                         if( $i % 2){ $estilo1='modulo_list_claro';}
                         else {$estilo1='modulo_list_claro';}
     
                         $this->salida .= "		<table  width=\"100%\"  border=\"1\" class=\"hc_table_list\" align=\"center\" >";
                         $this->salida .= "			<tr class=\"hc_table_submodulo_list_title\" align=\"center\" >";
                         $this->salida .= "				<td width=\"10%\">Ingreso</td>";
                         $this->salida .= "				<td width=\"10%\">Nueva Epicrisis</td>";
                                                                 $this->salida .= "				<td width=\"10%\">Epicrisis</td>";
                         $this->salida .= "				<td width=\"25%\">Departamento</td>";
                         $this->salida .= "				<td width=\"25%\">Servicio</td>";
                         $this->salida .= "				<td width=\"25%\">Fecha</td>";
                         $this->salida .= "				<td width=\"5%\"></td>";
                         $this->salida .= "			</tr>";
     
                         $this->salida.="<tr  class='$estilo1' align='center'>";
                         $mostrar3=$reporte->GetJavaReport_HC($arr[$i][ingreso],array());
                         $funcion2=$reporte->GetJavaFunction();
                         $this->salida.=$mostrar3;
                         $this->salida.="  <td><a href=\"javascript:$funcion2\">".$arr[$i][ingreso]."</a></td>";
                         
                         $epi = $this->GetDatosEpicrisis($arr[$i][ingreso]);
                         if($epi)
                         {
                              $mostrarT=$reporte->GetJavaReport('hc','Epicrisis','ReporteEpicrisis',array('ingreso'=>$arr[$i][ingreso],'evolucion'=>$arr[$i][evolucion_id]),array('rpt_name'=>'Epicrisis'.$arr[$i][ingreso],'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                              $funcionT=$reporte->GetJavaFunction();
                              $this->salida .= "<td align=\"center\"><a href=\"javascript:$funcionT\"><img src=\"". GetThemePath() ."/images/imprimir.png\" border='0' title=\"EPICRISIS\"></a></td>\n";
                              $this->salida .= "$mostrarT";
                         }
                         else
                              $this->salida .= "<td align=\"center\">&nbsp;</td>\n";
                         
                         //Impresion Epicrisis por Ingreso.
                         $mostrarEPI2=$reporte->GetJavaReport_Epicrisis($arr[$i][ingreso],array());
                         $funcionEPI2=$reporte->GetJavaFunction();
                         $this->salida .=$mostrarEPI2;
                         $this->salida .= "<td><a href=\"javascript:$funcionEPI2\"><img src=\"". GetThemePath() ."/images/imprimir.png\" border='0' title='RESUMEN EPICRISIS'></a></td>";
                              
                         //Impresion Epicrisis por Ingreso.
                         $this->salida.="  <td>".$arr[$i][desc]."</td>";
                         $this->salida.="  <td>".$arr[$i][descripcion]."</td>";
                         $fecha=explode(" ",$arr[$i][fecha_ingreso]);
                         $this->salida.="  <td>".$fecha[0]."</td>";
                         $a=$e="";
                         if($arr[$i][estado]==1)
                         {$a='activo.gif';$e='ingreso activo';}else{$a='inactivo.gif';$e='ingreso inactivo';}
                         $this->salida.="  <td><img src=\"". GetThemePath() ."/images/$a\" border='0' width='12' height='12' title='$e'></td></tr>";
                         
                         if(!empty($arr[$i][tipo_consulta_id])){
                            $this->salida.="<tr class='$estilo1' align='center'>";
                            $this->salida.="  <td colspan=\"4\"><b>Profesional:</b> ".$arr[$i][tipo_id_tercero]." ".$arr[$i][tercero_id]." ".$arr[$i][profesional]."</td>";
                            $this->salida.="  <td colspan=\"3\"><b>Tipo Consulta:</b> ".$arr[$i][tipo_consulta]."</td>";
                            $this->salida.="</tr>";
                         }
                         
                         
          
                         //revisamos si se selecciono diagnosticos de ingresos	
                         if($_REQUEST['ming']==on)
                         {	
                              unset($arr_diag);
                              $arr_diag=$this->Get_Info_Diagnosticos($arr[$i][ingreso],$_REQUEST['ing'],1);
                              if(is_array($arr_diag))
                              {
                                   for($f=0;$f<sizeof($arr_diag);$f++)
                                   {
                                        $this->salida .= "			<tr align=\"center\" >";
                                        $this->salida .= "				<td class=\"hc_table_submodulo_list_title\"  colspan='2'>Diagnosticos Ingreso</td>";
                                                                           
                                        $this->salida .= "				<td colspan='3' class='$estilo1' width=\"25%\">";
                                        $this->salida .= "		<sub>".$arr_diag[$f][diagnostico_id]."-".$arr_diag[$f][diagnostico_nombre]."</sub></td></tr>";
                                   }	
                              }
                         }	
                                                            
                         //revisamos si se selecciono diagnosticos de egresos	
                         if($_REQUEST['meg']==on)
                         {	
                              unset($arr_eg);
                              $arr_eg=$this->Get_Info_Diagnosticos($arr[$i][ingreso],$_REQUEST['egreso'],2);
                              if(is_array($arr_eg))
                              {
                                   for($f=0;$f<sizeof($arr_eg);$f++)
                                   {
                                        $this->salida .= "			<tr align=\"center\" >";
                                        $this->salida .= "				<td class=\"hc_table_submodulo_list_title\"  colspan='2'>Diagnosticos Egreso</td>";
                                                                           
                                        $this->salida .= "				<td colspan='3' class='$estilo1' width=\"25%\">";
                                        $this->salida .= "		<sub>".$arr_eg[$f][diagnostico_id]."-".$arr_eg[$f][diagnostico_nombre]."</sub></td></tr>";
                                   }	
                              }
                         }	
                                                                      
                         //revisamos si se selecciono diagnosticos de egresos	
                         if($_REQUEST['mfil']==on)
                         {	
                              unset($arr_fil);
                              $arr_fil=$this->Get_Finalidad($arr[$i][ingreso],$_REQUEST['finalidad']);
                              if(is_array($arr_fil))
                              {
                                   for($f=0;$f<sizeof($arr_fil);$f++)
                                   {
                                        $this->salida .= "			<tr align=\"center\" >";
                                        $this->salida .= "				<td class=\"hc_table_submodulo_list_title\"  colspan='2'>Finalidad</td>";
                                                                           
                                        $this->salida .= "				<td colspan='3' class='$estilo1' width=\"25%\">";
                                        $this->salida .= "		<sub>".$arr_fil[$f][detalle]."</sub></td></tr>";
                                   }	
                              }
                         }	
                                                            
                         //si este switche de evoluciones esta en On,entonces haremos la busqueda.
                         unset($arr_evol);
                         if($_REQUEST['mevol']==on)
                         {
                              $arr_evol=$this->Get_Info_Evoluciones($arr[$i][ingreso]);
                              if(is_array($arr_evol))
                              {
                                   $this->salida.="<tr   class='$estilo1' align='center'>";
                                   $this->salida.="  <td colspan='5'>";
                                   $this->salida .= "		<table  width=\"100%\"  border=\"0\" class=\"hc_table_list\" align=\"center\" >";
                                   for($r=0;$r<sizeof($arr_evol);$r++)
                                   {
                                        $this->salida.="<tr bgcolor='#F3FFFA' align='center'>";
                                        $mostrar1=$reporte->GetJavaReport_HistoriaClinica($arr_evol[$r][evolucion_id],array());
                                        $funcionI=$reporte->GetJavaFunction();
                                        $this->salida.=$mostrar1;
                                        $this->salida.="  <td><a href=\"javascript:$funcionI\"><label>Evol:&nbsp;".$arr_evol[$r][evolucion_id]."</label></a></td>";
     
                                        //$this->salida.="  <td><label class='label_mark'>Evol:&nbsp;".$arr_evol[$r][evolucion_id]."</label></td>";
                                        $fecha=explode(" ",$arr_evol[$r][fecha_cierre]);
                                        $this->salida.="  <td>".$fecha[0]."</td>";
                                        $this->salida.="  <td>".$arr_evol[$r][descripcion]."</td>";
                                        $this->salida.="  <td>".$arr_evol[$r][nombre]."</td></tr>";
                                        
                                   }
                                   $this->salida .= "			</table>";//fin tabla de evoluciones
                                   $this->salida.="  </td>";
                                   $this->salida.="  </tr>";
                              }												
                         }
                         /**/
                             
                         // Switche en ON para Notas Operatorias
                         /*if($_REQUEST['nope']==on)
                         {
                              unset($notas_OP);
                              $notas_OP = $this->Get_Info_NotasOperatorias($arr[$i][ingreso]);
                              if(is_array($notas_OP))
                              {
                                   for($f=0;$f<sizeof($notas_OP);$f++)
                                   {
                                        $this->salida .= "<tr align=\"center\" >";
                                        $this->salida .= "<td class=\"hc_table_submodulo_list_title\"  colspan='3'>Nota Operatoria</td>";

                                   	//imprimir nota operatoria
                                        $mostrar = $reporte->GetJavaReport('app','BioEstadistica','reporteNotaOperatoria_html',array('programacion'=>$notas_OP[$f][programacion_id],'tipoidpaciente'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],"ingreso"=>$arr[$i][ingreso]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                                        $nombre_funcionOP = $reporte->GetJavaFunction();
                                        $this->salida .=$mostrar;
                                                                           
                                        $this->salida .= "<td colspan='4' class='$estilo1' width=\"25%\" align=\"left\">";
                                        $this->salida .= "<a href=\"javascript:$nombre_funcionOP\">Evoluci?n: ".$notas_OP[$f][evolucion_id]."&nbsp&nbsp&nbsp<img src=\"".GetThemePath()."/images/traslado.png\" border='0'></a></td></tr>";
                                   }
                              }
                         }*/
                        $notas_OP = $examenes = array();
                        if($_REQUEST['nope']==on)
                        {
                          $notas_OP = $this->Get_Info_NotasOperatorias($arr[$i][ingreso]);
                        }
                        if($_REQUEST['examen'] == "on")
                        {
                          $examenes = $this->GetResultados($arr[$i][ingreso]);
                        }
                        
                        if(!empty($notas_OP) || !empty($examenes))
                        {
                          if(empty($notas_OP))
                            $vectorM = $examenes;
                          else if(empty($examenes))
                            $vectorM = $notas_OP;
                          else
                            $vectorM = array_merge($examenes,$notas_OP);
                          
                          $this->salida .= "  <tr align=\"center\" class=\"hc_table_submodulo_list_title\" >\n";
                          $this->salida .= "    <td colspan='3'>Evolucion</td>";
                          $this->salida .= "    <td colspan='2'>Nota Operatoria</td>";
                          $this->salida .= "    <td colspan='2'>Examenes</td>";
                          $this->salida .= "  </tr>\n";
                          
                          foreach($vectorM as $kR1 => $dtlR)
                          {
                            
                            $this->salida .= "  <tr align=\"center\" class='$estilo1' >\n";
                            $this->salida .= "    <td colspan='3'>".(($dtlR['evolucion_id'])? $dtlR['evolucion_id']: $dtlR['evolucion_examenes'])."</td>";
                            $this->salida .= "    <td colspan='2'>\n";
                            if($dtlR['evolucion_id'])
                            {
                              $mostrar = $reporte->GetJavaReport('app','BioEstadistica','reporteNotaOperatoria_html',array('programacion'=>$dtlR['programacion_id'],'tipoidpaciente'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],"ingreso"=>$arr[$i][ingreso]),array('rpt_name'=>'notaoperatoria'.$kR1,'rpt_dir'=>'','rpt_rewrite'=>TRUE));
                              $nombre_funcionOP = $reporte->GetJavaFunction();
                              $this->salida .=$mostrar;
                              $this->salida .= "      <a href=\"javascript:$nombre_funcionOP\" class=\"label_error\">\n";
                              $this->salida .= "        <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR\n";
                              $this->salida .= "      </a>\n";
                            }
                            $this->salida .= "      &nbsp;\n";
                            $this->salida .= "    </td>\n";
                            $this->salida .= "    <td colspan='2'>\n";
                            if($dtlR['evolucion_examenes'])
                            {
                              $dat['tipo_id_paciente'] = $arr[$i]['tipo_id_paciente'];
                              $dat['paciente_id'] = $arr[$i]['paciente_id'];
                              $dat['evolucion'] = $dtlR['evolucion_examenes'];
                              $mostrar = $reporte->GetJavaReport('app','ImpresionHC','examenesresultados',$dat,array('rpt_name'=>'examenesresultados'.$kR1,'rpt_dir'=>'','rpt_rewrite'=>TRUE));
                              $funcionEx = $reporte->GetJavaFunction();
                              
                              $this->salida .= $mostrar;
                              $this->salida .= "      <a href=\"javascript:".$funcionEx."\" class=\"label_error\">\n";
                              $this->salida .= "        <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR\n";
                              $this->salida .= "      </a>\n";
                            }
                            $this->salida .= "      &nbsp;\n";
                            $this->salida .= "    </td>\n";
                            $this->salida .= "  </tr>\n";
                          }	
                        }
                         // Switche en ON para Justificacion de medicamentos No Pos
                         if($_REQUEST['justi']==on)
                         {
                              unset($J_NoPos);
                              $J_NoPos = $this->Get_Info_Justificacion_NOPOS($arr[$i][ingreso]);
                             
                              if(is_array($J_NoPos))
                              {
                                   for($f=0;$f<sizeof($J_NoPos);$f++)
                                   {
                                        $this->salida .= "<tr align=\"center\" >";
                                        $this->salida .= "<td class=\"hc_table_submodulo_list_title\"  colspan='3'>Justificaci?n Med. NO POS</td>";

                                   	//Imprimir Justificaci?n NO POS
                                        $mostrar = $reporte->GetJavaReport('app','BioEstadistica','BIO_JustificacionMED_NO_POS_html',array('justificacion_id'=>$J_NoPos[$f][justificacion_no_pos_id]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                                        $nombre_funcionJ_NOPOS = $reporte->GetJavaFunction();
                                        $this->salida .=$mostrar;
                                                                           
                                        $this->salida .= "<td colspan='4' class='$estilo1' width=\"25%\" align=\"left\">";
                                        $this->salida .= "<a href=\"javascript:$nombre_funcionJ_NOPOS\">ID. Justificaci?n: ".$J_NoPos[$f][justificacion_no_pos_id]."&nbsp&nbsp&nbsp<img src=\"".GetThemePath()."/images/pparamed.png\" border='0'></a></td></tr>";
                                   }	
                              }
                              else
                              {
                                $Justificacion = $this->DatosJustificaciones_amb($arr[$i][ingreso]);
                                if(!empty($Justificacion))
                                {
                                  
                                 //$Justificacion = $this->DatosJustificaciones_amb($arr[$i][ingreso]);
                                   if(is_array($Justificacion))
                                   {
                                      for($f=0;$f<sizeof($Justificacion);$f++)
                                      {
                                         $this->salida .= "<tr align=\"center\" >";
                                         $this->salida .= "<td class=\"hc_table_submodulo_list_title\"  colspan='3'>Justificaci?n. NO POS</td>";
                                         $mostrar = $reporte->GetJavaReport('app','BioEstadistica','Justificacion_NO_POS_html',array('evolucion_id'=>$Justificacion[$f][evolucion_id],'Justificaci?n'=>$Justificacion[$f][hc_justificaciones_no_pos_amb],'ingreso'=>$arr[$i][ingreso]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                                         $nombre_funcionJ_NOPOS = $reporte->GetJavaFunction();
                                         $this->salida .=$mostrar;
                                         $this->salida .= "<td colspan='4' class='$estilo1' width=\"25%\" align=\"left\">";
                                         $this->salida .= "<a href=\"javascript:$nombre_funcionJ_NOPOS\">ID. Justificaci?n: ".$Justificacion[$f][hc_justificaciones_no_pos_amb]."&nbsp&nbsp&nbsp<img src=\"".GetThemePath()."/images/pparamed.png\" border='0'></a></td></tr>";
                                  
                                      }
                                  }
                                }
                              }
                         }
                          
                         $this->salida .= "			</table>";//fin tabla de ingresos
                         $this->salida.="</td></tr>";



                    }
               }
               $this->salida.="</table>";
               $this->conteo=$_SESSION['SPY'];
               $this->salida .=$this->RetornarBarra1();
          }
					//NOTA ADMINISTRATIVA - NOTA DE SEGUIMIENTO
/*					$tipo_id_paciente = $arr[0][tipo_id_paciente];
					$PacienteId = $arr[0][paciente_id];*/
					if($_REQUEST[TipoDocumento] AND $_REQUEST[Documento])
					{
						if($this->ConsultarNotasAdministrativas($_REQUEST[TipoDocumento], $_REQUEST[Documento]))
						{
							$tipo_id_paciente = $_REQUEST[TipoDocumento];
							$PacienteId = $_REQUEST[Documento];
							$reporteNota = new GetReports();
							$mostrar = $reporteNota->GetJavaReport('app','BioEstadistica','reporteNotaAdministrativa_html',array('tipoIdPaciente'=>$tipo_id_paciente,'PacienteId'=>$PacienteId),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
							$this->salida .=$mostrar;
							$this->salida .= "		<table class=\"hc_table_list\" width=\"80%\" border=\"1\" align=\"center\" >";
							$this->salida .= "<tr align=\"center\" >";
							$NotaAdministrativa = $reporteNota->GetJavaFunction();
							$this->salida .= "<td class=\"modulo_table_list\" width=\"50%\" ><a href=\"javascript:$NotaAdministrativa\" title=\"NOTA ADMINISTRATIVA - INASISTENCIAS\">NOTA ADMINISTRATIVA - INASISTENCIAS</a></td>";
			/*         $mostrar = $reporteNota->GetJavaReport('app','BioEstadistica','reporteNotaSeguimiento_html',array('justificacion_id'=>$J_NoPos[$f][justificacion_no_pos_id]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
							$this->salida .=$mostrar;
							$NotaSeguimiento = $reporteNota->GetJavaFunction();
							$this->salida .= "<td class=\"modulo_table_list\" width=\"50%\" ><a href=\"javascript:$NotaSeguimiento\">NOTA SEGUIMIENTO</a></td>";*/
							$this->salida .= "<td class=\"modulo_table_list\" width=\"50%\" >&nbsp;</td>";
							$this->salida.="</tr>";
							$this->salida.="</table>";
						}
					}
					//FIN NOTA ADMINISTRATIVA - NOTA DE SEGUIMIENTO

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
		
		$accion=ModuloGetURL('app','BioEstadistica','user','BuscarOrden',$vec);
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table width='22%' border='0'  align='center' cellspacing=\"5\"  cellpadding=\"1\"><tr><td width='20%' class='label' bgcolor=\"#D3DCE3\">P?ginas</td>";
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
			$this->salida .= "</tr><tr><td  class=\"label\"  colspan=".$valor." align='center'>P?gina&nbsp; $paso de $numpasos</td><tr></table>";
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
		$this->salida .= "</tr><tr><td   class=\"label\"  colspan=".$valor." align='center'>P?gina&nbsp; $paso de $numpasos</td><tr></table>";
		
		}
    
}
//fin de las funciones para la barra de segnentacion
	
	
	
	

	function RetornarBarra(){
    if($this->limit>=$this->conteo){
        return '';
    }
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

    $accion=ModuloGetURL('app','BioEstadistica','user','BuscarPaciente',$vec);

		$barra=$this->CalcularBarra($paso);
    $numpasos=$this->CalcularNumeroPasos($this->conteo);
    $colspan=1;

    $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
    if($paso > 1){
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
      $colspan+=1;
    }
    $barra ++;
    if(($barra+10)<=$numpasos){
      for($i=($barra);$i<($barra+10);$i++){
        if($paso==$i){
            $this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
        }else{
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
      $colspan+=2;
    }else{
      $diferencia=$numpasos-9;
      if($diferencia<=0){$diferencia=1;}
      for($i=($diferencia);$i<=$numpasos;$i++){
        if($paso==$i){
          $this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
        }else{
          $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      if($paso!=$numpasos){
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
        $colspan++;
      }else{
       // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
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
      $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>P?gina $paso de $numpasos</td><tr></table><br>";
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
    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>P?gina $paso de $numpasos</td><tr></table><br>";
    }
}

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

//----------------------------------------------------------------------------------------------------

}//fin clase

?>