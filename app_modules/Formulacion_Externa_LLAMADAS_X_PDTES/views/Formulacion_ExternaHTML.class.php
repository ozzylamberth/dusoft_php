<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: Formulacion_ExternaHTML.class.php,v 1.0 
	* @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	
	IncludeClass("ClaseHTML");
	IncludeClass("ClaseUtil");
    IncludeClass("CalendarioHtml");
	class Formulacion_ExternaHTML
	{
	/**
		* Constructor de la clase
	*/

	function  Formulacion_ExternaHTML()
	{
	return true;
	}
  
   /** Function para el Menu de dispensacion
    * @param array $action Vector de links de la aplicacion
    * @return String
		*/
		function FormaMenu($action,$datos_empresa)
		{
		$html  = ThemeAbrirTabla('MENU FORMULACION EXTERNA');
		$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
		$html .= "	<tr>\n";
		$html .= "		<td>\n";
		$html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
		$html .= "				<tr>\n";
		$html .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
		$html .= "				</tr>\n";
		$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
		$html .= "      <td  class=\"label\"  align=\"center\">\n";
		$html .= "       <a href=\"".$action['farmacovigilancia']."\">\n";
		$html .= "       FARMACOVIGILANCIA</a>\n";
		$html .= "      </td>\n";
		$html .= "  </tr>\n";

		$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
		$html .= "      <td  class=\"label\"  align=\"center\">\n";
		$html .= "       <a href=\"".$action['FormulaInt']."\">\n";
		$html .= "       FORMULACION DE MEDICAMENTOS </a>\n";
		$html .= "      </td>\n";
		$html .= "  </tr>\n";
		
		$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
		$html .= "      <td  class=\"label\"  align=\"center\">\n";
		$html .= "       <a href=\"".$action['tickets']."\">\n";
		$html .= "       TICKETS DE DISPENSACION</a>\n";
		$html .= "      </td>\n";
		$html .= "  </tr>\n";
		if($datos_empresa['sw_privilegios']=='1')
		{
		$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
		$html .= "      <td  class=\"label\"  align=\"center\">\n";
		$html .= "       <a href=\"".$action['reservas_pacientes']."\">\n";
		$html .= "       INACTIVACION - RESERVAS</a>\n";
		$html .= "      </td>\n";
		$html .= "  </tr>\n";
		}
		$html .= "			</table>\n";
		$html .= "		</td>\n";
		$html .= "	</tr>\n";
		$html .= "	<tr>\n";
		$html .= "		<td align=\"center\"><br>\n";
		$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
		$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
		$html .= "			</form>";
		$html .= "		</td>";
		$html .= "	</tr>";
		$html .= "</table>";
		$html .= ThemeCerrarTabla();
		return $html;
		}
    /** Function para el Menu de Farmacovigilancia
    * @param array $action Vector de links de la aplicacion
    * @return String
		*/
		function FormaMenuFarmacovigilancia($action)
		{
          $html  = ThemeAbrirTabla('MENU FARMACOVIGILANCIA');
          $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
          $html .= "	<tr>\n";
          $html .= "		<td>\n";
          $html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $html .= "				<tr>\n";
          $html .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
          $html .= "				</tr>\n";
          $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
          $html .= "      <td  class=\"label\"  align=\"center\">\n";
          $html .= "       <a href=\"".$action['registro_farmacovigilancia']."\">\n";
          $html .= "      REGISTRAR PACIENTE</a>\n";
          $html .= "      </td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
          $html .= "      <td  class=\"label\"  align=\"center\">\n";
          $html .= "       <a href=\"".$action['buscar_registro']."\">\n";
          $html .= "      BUSCAR REGISTROS</a>\n";
          $html .= "      </td>\n";
          $html .= "  </tr>\n";

          $html .= "			</table>\n";
          $html .= "		</td>\n";
          $html .= "	</tr>\n";
          $html .= "	<tr>\n";
          $html .= "		<td align=\"center\"><br>\n";
          $html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
          $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
          $html .= "			</form>";
          $html .= "		</td>";
          $html .= "	</tr>";
          $html .= "</table>";
          $html .= ThemeCerrarTabla();
          return $html;
		}

        /**
        * Funcion donde se crea la forma para mostrar los pacientes
        * @param array $action vector que contiene los link de la aplicacion
        * @param var   $tipo contiene el tipo de documento
        * @param array $request vector que contiene los datos
        * @return string $html retorna la cadena con el codigo html de la pagina
        */
     function formaRegistrarPacientes($action,$Tipo,$request,$datos,$conteo,$pagina)           
     {

          $ctl = AutoCarga::factory("ClaseUtil");
          $html  = $ctl->LimpiarCampos();
          $html .= ThemeAbrirTabla('BUSCAR -PACIENTE');
          
          $html .= "<center>\n";
          $html .= "<fieldset class=\"fieldset\" style=\"width:55%\">\n";
          $html .= "  <legend class=\"normal_10AN\" align=\"center\">BUSCAR PACIENTES</legend>\n";
          $html .= "	<form name=\"formabuscarE\" action=\"".$action['buscador']."\" method=\"post\">";
          $html .= "	  <table   width=\"100%\" align=\"center\" class=\"modulo_table_list\"  >";
          $html .= "      <tr class=\"formulacion_table_list\"> \n";
          $html .= "			  <td >TIPO DOCUMENTO:</td>\n";
          $html .= "			  <td align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
          $html .= "				  <select name=\"buscador[tipo_id_paciente]\" class=\"select\">\n";
          $html .= "            <option value = '-1'>--  SELECCIONE --</option>\n";
          $csk = "";
          foreach($Tipo as $indice => $valor)
          {
          $sel = ($valor['tipo_id_tercero']==$request['tipo_id_paciente'])? "selected":"";
          $html .= "  <option value=\"".$valor['tipo_id_tercero']."\" ".$sel.">".$valor['descripcion']."</option>\n";
          }
          $html .= "				  </select>\n";
          $html .= "				</td>\n";
          $html .= "	    </tr>\n";
          $html .= "		  <tr class=\"formulacion_table_list\">\n";
          $html .= "			  <td >DOCUMENTO:</td>\n";
          $html .= "	      <td align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
          $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[paciente_id]\" size=\"20\"  maxlength=\"32\" value=".$request['paciente_id'].">\n";
          $html .= "        </td>\n";
          $html .= "		  </tr>\n";
          $html .= "		  <tr class=\"formulacion_table_list\">\n";
          $html .= "			  <td >NOMBRES</td>\n";
          $html .= "			  <td class=\"modulo_list_claro\" >\n";
          $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[nombres]\" style=\"width:100%\" value=".$request['nombres'].">\n";
          $html .= "        </td>\n";
          $html .= "		  </tr>\n";
          $html .= "		  <tr class=\"formulacion_table_list\">\n";
          $html .= "			  <td >APELLIDOS</td>\n";
          $html .= "			  <td class=\"modulo_list_claro\" >\n";
          $html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[apellidos]\" style=\"width:100%\" value=".$request['apellidos'].">\n";
          $html .= "        </td>\n";
          $html .= "		  </tr>\n";
          $html .= "    </table><br>\n";
          $html .= "		<table   width=\"40%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
          $html .= "		  <tr>\n";
          $html .= "	   	  <td align='center'>\n";
          $html .= "			    <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
          $html .= "			  </td>\n";
          $html .= "			  <td align='center' colspan=\"1\">\n";
          $html .= "			    <input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formabuscarE)\" value=\"Limpiar Campos\">\n";
          $html .= "	  	  </td>\n";
          $html .= "			</tr>\n";
          $html .= "    </table><br>\n";
          $html .= "  </form>\n";
          $html .= "</fieldset><br>\n";
          $html .= "</center>\n";
          $html .= $ctl->RollOverFilas();
          
          if(!empty($datos))
            {
              $pghtml = AutoCarga::factory('ClaseHTML');
              $html .= "  <table width=\"55%\" class=\"modulo_table_list\"   align=\"center\">";
              $html .= "	  <tr class=\"formulacion_table_list\" >\n";
              $html .= "      <td width=\"15%\">IDENTIFICACION </td>\n";
              $html .= "      <td width=\"45%\">PACIENTE </td>\n";
              $html .= "      <td width=\"10%\">SEXO </td>\n";
                $html .= "      <td width=\"10%\">REGISTRAR</td>\n";
              $html .= "  </tr>\n";
              $est = "modulo_list_claro"; $back = "#DDDDDD";
              $i=0;
              foreach($datos as $key => $dtl)
              {
                $est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
                $html .= "  <tr class=\"".$est."\"  onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
                $html .= "      <td ><b>".$dtl['tipo_id_paciente']." ".$dtl['paciente_id']."</b></td>\n";
                $html .= "      <td ><b>".$dtl['apellidos']." ".$dtl['nombres']."</b></td>\n";
                
                if($dtl['sexo_id']=='F')
                {
                   $sexo='FEMENINO';
                }else
                {
                  $sexo='MASCULINO';
                
                }
                $html .= "      <td ><b>".$sexo."</b></td>\n";
                  $html .= "      <td  align=\"center\" class=\"label_error\">\n";
                $html .= "        <a href=\"".$action['registro'].URLRequest($dtl)."\">\n";
                $html .= "          <img border=\"0\" src=\"".GetThemePath()."/images/editar.png\">\n";
                $html .= "        </a>\n";
                $html .= "      </td>\n";
                $html .= "    </tr>\n";
              }
              $html .= "	</table><br>\n";
              $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
            }
            else
            {
              if($request)
              $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
            }
            
          $html .= "<table align=\"center\">\n";
          $html .= "<br>";
          $html .= "  <tr>\n";
          $html .= "      <td align=\"center\" class=\"label_error\">\n";
          $html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
          $html .= "      </td>\n";
          $html .= "  </tr>\n";
          $html .= "</table>\n";
          $html .= ThemeCerrarTabla();
          return $html;
      }
   
      /**
      * Funcion donde se registran los datos para el registro de farmacovigilancia
      * @return string $html retorna la cadena con el codigo html de la pagina
      */
    
    function formaRegistrarPacientes_Plantilla($action,$instuticion,$paciente_id,$tipo_id_paciente,$apellidos,$nombres,$sexo,$request)           
    {
	  
        $ctl = AutoCarga::factory("ClaseUtil");
        $html  = $ctl->LimpiarCampos();
        $html .= ThemeAbrirTabla('FORMATO DE SOSPECHA DE REACCION ADVERSA A MEDICAMENTOS');
        $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
        $html .="<script >\n";
        $html .= "  function ValidarDtos(frms)\n";
        $html .= "  {\n";
        $html .= "    if(frms.institucion.value==\"-1\")\n";
        $html .= "    {\n";
        $html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR  LA FARMACIA';\n";
        $html .= "      return;\n";
        $html .= "    }\n";
        $html .= "    if(frms.fecha_notifica.value==\"\")\n";
        $html .= "    {\n";
        $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DE NOTIFICACION';\n";
        $html .= "      return;\n";
        $html .= "    }\n";
        $html .= "    if(frms.formula.value==\"\")\n";
        $html .= "    {\n";
        $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR NUMERO DE FORMULA ';\n";
        $html .= "      return;\n";
        $html .= "    }\n";
        $html .= "    if(frms.fecha_sospecha.value==\"\")\n";
        $html .= "    {\n";
        $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DE LA SOSPECHA';\n";
        $html .= "      return;\n";
        $html .= "    }\n";
        $html .= "    frms.submit();\n";
        $html .= "    }\n";   
        $html .="</script>\n";
                  
        $html .= "<center>\n";
        $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
        $html .= "  <legend class=\"normal_10AN\" align=\"center\">FORMATO DE RESPORTE DE SOSPECHA DE REACCION</legend>\n";
        $html .= "	<form name=\"formaRegistro_sospecha\" action=\"".$action['registrar']."\" method=\"post\">";
        
        $html .= "<table  width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
        $html .= "  <tr align=\"center\" >\n";
        $html .= "      <td  class=\"formulacion_table_list\" colspan=\"13\" > <b>IDENTIFICACION</b> </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "		<td width=\"10%\" align=\"left\" > FECHA DE NOTIFICACION:</td>\n";
        $html .= "		<td width=\"15%\" class=\"modulo_list_claro\" align=\"center\">\n";
        $html .= "		  <input type=\"text\" class=\"input-text\" name=\"fecha_notifica\"  id=\"fecha_notifica\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$_REQUEST['fecha_notifica']."\"  >\n";
        $html .= "		</td>\n";
        $html .= "    <td  colspan=\"1\" class=\"modulo_list_claro\" >\n";
        $html .= "				".ReturnOpenCalendario('formaRegistro_sospecha','fecha_notifica','-')."\n";
        $html .= "		</td>\n";
        $html .= "			  <td width=\"25%\" >FARMACIA:</td>\n";
        $html .= "			  <td  align=\"left\" class=\"modulo_list_claro\" colspan=\"6\">\n";
        $html .= "				  <select name=\"institucion\" class=\"select\">\n";
        $html .= "            <option value = '-1'>--  SELECCIONE --</option>\n";
        $csk = "";
        foreach($instuticion as $indice => $valor)
        {
            $html .= "  <option value=\"".$valor['empresa_id']."\" ".$sel.">".$valor['razon_social']."</option>\n";
        }
        $html .= "				  </select>\n";
        $html .= "				</td>\n";
        
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "       <td   colspan=\"1\"   align=\"left\">IDENTIFICACION:</td>\n";
        $html .= "       <td    colspan=\"1\" class=\"modulo_list_claro\" align=\"left\">".$tipo_id_paciente." -".$paciente_id."\n";
        $html .= "       </td>\n";
        $html .= "       <td  colspan=\"1\"  align=\"left\">PACIENTE:</td>\n";
        $html .= "       <td  colspan=\"3\" class=\"modulo_list_claro\" align=\"left\">".$nombres." ".$apellidos."\n";
        $html .= "       </td>\n";
        $html .= "       <td   width=\"5%\"   align=\"left\">SEXO:</td>\n";
        $html .= "       <td  width=\"5%\" class=\"modulo_list_claro\" align=\"left\">".$sexo."\n";
        $html .= "       </td>\n";
        $html .= " </tr>\n";

        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "       <td    width=\"25%\"   align=\"left\">No. FORMULA:</td>\n";
        $html .= "      <td  colspan=\"2\"  align=\"left\"  class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\" name=\"formula\" id=\"formula\"   value=\"".$_REQUEST['formula']."\" size=\"20%\" maxlength=\"55\" >\n";
        $html .= "      </td>\n";
        $html .= "       </td>\n";
        $html .= "		<td width=\"25%\" align=\"left\" > FECHA  DE SOSPECHA:</td>\n";
        $html .= "		<td width=\"10%\" class=\"modulo_list_claro\" align=\"center\">\n";
        $html .= "		  <input type=\"text\" class=\"input-text\" name=\"fecha_sospecha\"  id=\"fecha_sospecha\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$_REQUEST['fecha_sospecha']."\"  >\n";
        $html .= "		</td>\n";
        $html .= "    <td colspan=\"3\"  class=\"modulo_list_claro\" >\n";
        $html .= "				".ReturnOpenCalendario('formaRegistro_sospecha','fecha_sospecha','-')."\n";
        $html .= "		</td>\n";
        $html .= " </tr>\n";
        
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td   colspan=\"15\" align=\"CENTER\">REACCION(ES) ADVERSAS A MEDICAMENTOS(RAMs) SOSPECHADA (S)</td>\n";
        $html .= "  </tr >\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td   colspan=\"13\"  align=\"left\" class=\"modulo_list_claro\"> <textarea  onkeypress=\"return max(event)\"  name=\"reacciones\"  id=\"reacciones\"   rows=\"2\"  style=\"width:100%\">".$_REQUEST['reacciones']."</textarea>\n";
        $html .= "       </td>\n";
        $html .= "  </tr >\n";
        $html .= "</table> <br>";
      
        $html .= "		<table   width=\"40%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
        $html .= "		  <tr>\n";
        $html .= "	   	  <td align='center'>\n";
        $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"MEDICAMENTOS\" onclick=\" ValidarDtos(document.formaRegistro_sospecha)\">\n";
        $html .= "			  </td>\n";
        $html .= "			  <td align='center' colspan=\"1\">\n";
        $html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formaRegistro_sospecha)\" value=\"LIMPIAR CAMPOS\">\n";
        $html .= "	  	  </td>\n";
        $html .= "			</tr>\n";
        $html .= "    </table><br>\n";
        
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }
   
		/**
    * Funcion donde se registran los datos para el registro de farmacovigilancia
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    
    function formaRegistrarPacientes_medicamentos($action,$instuticion,$request,$datos,$conteo,$pagina,$formula_id,$diagnostico)  
    {
	  
          $ctl = AutoCarga::factory("ClaseUtil");
          $html  = $ctl->LimpiarCampos();
          $num=count($datos);
          $html .= ThemeAbrirTabla('FORMATO DE SOSPECHA DE REACCION ADVERDA A MEDICAMENTOS');
          if(!empty($datos))
          {
          $html .= "<center>\n";
          $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
          $html .= "  <legend class=\"normal_10AN\" align=\"center\">FORMATO DE RESPORTE DE SOSPECHA DE REACCION</legend>\n";
          
          $html .= $ctl->RollOverFilas();
          $html .= "	<form name=\"formaRegistro_sospecha_medica\" id=\"formaRegistro_sospecha_medica\" action=\"".$action['registrar']."\" method=\"post\">";
              
        
              $pghtml = AutoCarga::factory('ClaseHTML');
              $html .= "  <table width=\"100%\" class=\"modulo_table_list\"   align=\"center\">";
              $html .= "	  <tr class=\"formulacion_table_list\" >\n";
              $html .= "      <td width=\"15%\">CODIGO </td>\n";
              $html .= "      <td width=\"25%\">MEDICAMENTO </td>\n";
              $html .= "      <td width=\"10%\">CANTIDAD </td>\n";
              $html .= "      <td width=\"10%\">FECHA VENC</td>\n";
              $html .= "      <td width=\"10%\">LOTE</td>\n";
              
              $html .= "      <td width=\"35%\">INDICACION O MOTIVO </td>\n";
                $html .= "      <td colspan=\"2\">FECHA INICIO</td>\n";
              $html .= "      <td colspan=\"2%\">FECHA FINALIZACION</td>\n";
              $html .= "      <td width=\"5%\">OP</td>\n";
              
              $html .= "  </tr>\n";
              $est = "modulo_list_claro"; $back = "#DDDDDD";
              $i=0;
              foreach($datos as $key => $dtl)
              {
                $est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
                $html .= "  <tr class=\"".$est."\"  onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
                $html .= "      <td ><b>".$dtl['codigo_producto']." </b></td>\n";
                if($dtl['sw_pactado']=='1')
                {
                    $html .= "      <td ><b>".$dtl['molecula']." </b></td>\n";
                
                }else
                {
                
                  $html .= "      <td ><b>".$dtl['descripcion_prod']." </b></td>\n";
                
                }
                        
                if($dtl['unidad_tiempo_tratamiento']=='1')
                {
                 $unidad_tiempo_tratamiento='AÑOS';
                 
                }
                if($dtl['unidad_tiempo_tratamiento']=='2')
                {
                 $unidad_tiempo_tratamiento='MESES';
                 
                }
                if($dtl['unidad_tiempo_tratamiento']=='3')
                {
                 $unidad_tiempo_tratamiento='SEMANAS';
                 
                }
                if($dtl['unidad_tiempo_tratamiento']=='4')
                {
                 $unidad_tiempo_tratamiento='DIAS';
                 
                }
                
                
                $html .= "      <td ><b>".round($dtl['numero_unidades'])." / ".$dtl['tiempo_tratamiento']." ".$unidad_tiempo_tratamiento."</b></td>\n";
                $html .= "      <td ><b>".$dtl['fecha_vencimiento']." </b></td>\n";
                $html .= "      <td ><b>".$dtl['lote']." </b></td>\n";
                $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
                $html .= "      <input type=\"hidden\" name=\"dosis".$i."\" value=\"".round($dtl['numero_unidades'])." / ".$dtl['tiempo_tratamiento']." ".$unidad_tiempo_tratamiento."\" >  <input type=\"hidden\" name=\"fecha_v".$i."\"  id=\"fecha_v".$i."\"  value=\"".$dtl['fecha_vencimiento']."\" >  
                  <input type=\"hidden\" name=\"lote".$i."\"  name=\"lote".$i."\" value=\"".$dtl['lote']." \" >  ";
                
                $html .="        <td  align=\"left\">";
                $html .="<textarea   style=\"width:100%\"  class=\"input-text\" name=\"observa".$i."\" cols=\"5\" row=\"3\" ></textarea> ";
                $html .= "      </td>\n";
                
                
                $html .= "      <td align=\"left\"  class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\" name=\"fecha_in".$i."\" id=\"fecha_in".$i."\"   value=\"\" size=\"20%\" maxlength=\"55\" >\n";
                $html .= "      </td>\n";
                $html .= "    <td  class=\"modulo_list_claro\" >\n";
                $html .= "				".ReturnOpenCalendario('formaRegistro_sospecha_medica','fecha_in'.$i,'-')."\n";
                $html .= "		</td>\n";
                
                
                $html .= "		<td  class=\"modulo_list_claro\" align=\"center\">\n";
                $html .= "		  <input type=\"text\" class=\"input-text\" name=\"fecha_fin".$i."\"  id=\"fecha_fin".$i."\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$_REQUEST['fecha_inicio']."\"  >\n";
                $html .= "		</td>\n";
                $html .= "    <td   class=\"modulo_list_claro\" >\n";
                $html .= "				".ReturnOpenCalendario('formaRegistro_sospecha_medica','fecha_fin'.$i,'-')."\n";
                $html .= "		</td>\n";
                $html .="        <td  align=\"left\">";
                $html .=" <input type=\"checkbox\" name=\"".$i."\" id=\"".$i."\"   value=\"".$dtl['codigo_producto']."\" > </th>  ";       
                $html .= "      </td>\n";				
                
                $html .= "    </tr>\n";
                $i++;
              }
              $html .= "	</table><br>\n";
            
            
        
                     
              $html .= "<table  width=\"70%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
              $html .= "  <tr class=\"formulacion_table_list\">\n";
              $html .= "      <td   colspan=\"15\" align=\"CENTER\">DIAGNOSTICOS: </td>\n";
              $html .= "  </tr >\n";
              $html .= "  <tr class=\"modulo_table_list_title\">\n";
              $html .= "      <td   colspan=\"13\"  align=\"left\" class=\"modulo_list_claro\"> <textarea  onkeypress=\"return max(event)\"  name=\"diagnostico\"  id=\"diagnostico\"  readonly=\"true\" rows=\"2\"  style=\"width:100%\">";
              
              foreach($diagnostico as $dxx => $dx)
              {
            
              $html .= "".$dx['diagnostico_id']." -".$dx['diagnostico_nombre']." \n";
                    
              }
              
              
              $html .= " </textarea>\n";
              $html .= "       </td>\n";
              $html .= "  </tr >\n";
              $html .= "  <tr class=\"formulacion_table_list\">\n";
              $html .= "      <td   colspan=\"15\" align=\"CENTER\">OBSERVACIONES:</td>\n";
              $html .= "  </tr >\n";
              $html .= "  <tr class=\"modulo_table_list_title\">\n";
              $html .= "      <td   colspan=\"13\"  align=\"left\" class=\"modulo_list_claro\"> <textarea  onkeypress=\"return max(event)\"  name=\"observaciones\"  id=\"observaciones\"   rows=\"2\"  style=\"width:100%\">".$valor['condiciones_entrega']."</textarea>\n";
              $html .= "       </td>\n";
              $html .= "  </tr >\n";
              
              $html .= "</table> <br>";
              $html .= "		<table   width=\"40%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
              $html .= "		  <tr>\n";
              $html .= "	   	  <td align='center'>\n";
              $html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$i."\">";
                
              $html .= "        <input class=\"input-submit\" type=\"submit\" class=\"input-text\" name=\"btnCrear\"   value=\"GUARDAR\"  >\n";
              $html .= "			  </td>\n";
              $html .= "			</tr>\n";
              $html .= "    </table><br>\n";
              $html .= "    </form><br>\n";
          
          }
          else
          {
            
              $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA ESTA FORMULA</center><br>\n";
            

          }
            $html .= "<table align=\"center\">\n";
              $html .= "<br>";
              $html .= "  <tr>\n";
              $html .= "      <td align=\"center\" class=\"label_error\">\n";
              $html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
              $html .= "      </td>\n";
              $html .= "  </tr>\n";
              $html .= "</table>\n";
          
          $html .= ThemeCerrarTabla();
          return $html;
        }
      /**
      * Funcion donde se muestra el mensaje generado cuando se registra un reporte en farmacovigilancia
      * @return string $html retorna la cadena con el codigo html de la pagina
      */
        function FormaMensajegenerado($action,$esm_farmaco_id,$formula)
        {
            
            $ctl  = AutoCarga::factory("ClaseUtil"); 
            $html .= $ctl->IsDate("-");
            $html .= $ctl->AcceptDate("-");
            $html .= $ctl->IsNumeric();
            $html .= $ctl->AcceptNum(false);
            $html .= ThemeAbrirTabla('MENSAJE');
            $html .= "<center> ";
            $var['esm_farmaco_id']=$esm_farmaco_id;
          
            $reporte = new GetReports();
            $mostrar = $reporte->GetJavaReport('app','Formulacion_Externa','FormatoFarmacov',
                                                    array("esm_farmaco_id"=>$esm_farmaco_id,"formula_r"=>$formula,"bodega"=>$bod,"centroU"=>$Centrid),
                                                    array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
            $funcion = $reporte->GetJavaFunction();
            $html .= "<fieldset class=\"fieldset\" style=\"width:80%\" >\n";
            $html .= " <table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "       <tr class=\"formulacion_table_list\">\n";
            $html .= "             <td align=\"center\">\n";
            $html .= "               SE GENERO CORRECTAMENTE</td> ";
            
            $html .= "      </tr>\n";
            $html .= "      <tr class=\"formulacion_table_list\"> \n";
            $html .= "				<td align=\"center\"  class=\"modulo_list_claro\">\n";
            $html .= "				".$mostrar."\n";
            $html .= "					<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE DEL PEDIDO\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' >\n";
            $html .= "					[ IMPRIMIR FORMATO]</a></center>\n";
            $html .= "			</td>\n";
            $html .= "      </tr>\n";
            $html .= "</table><br>\n";
            $html .= "</fieldset><br>\n";
            $html .= "</center>\n";		
            $html .= "<table align=\"center\">\n";
            $html .= "<br>";
            $html .= "  <tr>\n";
            $html .= "      <td align=\"center\" class=\"label_error\">\n";
            $html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
            $html .= "      </td>\n";
            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= ThemeCerrarTabla();
            return $html;
      }
      /**
      * Funcion donde se busca un reporte en farmacovigilancia
      * @return string $html retorna la cadena con el codigo html de la pagina
      */
      
    function FormaBuscarPlantillas($action,$datos,$conteo,$pagina)
		{
            $ctl = AutoCarga::factory("ClaseUtil"); 
            $html .= $ctl->IsDate("-");
            $html .= $ctl->AcceptDate("-");
            $html .= $ctl->LimpiarCampos();
            $bodegades = SessionGetVar("bodegaDesc");
            $html  ="  <script>\n";
            $html .= "	  function LimpiarCampos(frm)\n";
            $html .= "	  {\n";
            $html .= "		  for(i=0; i<frm.length; i++)\n";
            $html .= "		  {\n";
            $html .= "			  switch(frm[i].type)\n";
            $html .= "			  {\n";
            $html .= "				  case 'text': frm[i].value = ''; break;\n";
            $html .= "				  case 'select-one': frm[i].selectedIndex = 0; break;\n";
            $html .= "			  }\n";
            $html .= "		  }\n";
            $html .= "	  }\n";
            $html .= "		function mOvr(src,clrOver)\n";
            $html .= "		{\n";
            $html .= "			src.style.background = clrOver;\n";
            $html .= "		}\n";
            $html .= "		function mOut(src,clrIn)\n";
            $html .= "		{\n";
            $html .= "			src.style.background = clrIn;\n";
            $html .= "		}\n";
            $html .="  </script>\n";
            $html  .= ThemeAbrirTabla('CONSULTAR FORMATOS');
            $html .= "<form name=\"FormaConsultar\" id=\"FormaConsultar\" action=\"".$action['buscador']."\"  method=\"post\" >\n";
            $html .= "<table  width=\"45%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
            $html .= "      <tr class=\"formulacion_table_list\"> \n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "		<td width=\"30%\" align=\"CENTER\" >FECHA INICIO:</td>\n";
            $html .= "		<td width=\"15%\" class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "		  <input type=\"text\" class=\"input-text\" name=\"buscador[fecha_inicio]\"   id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\"  >\n";
            $html .= "		</td>\n";
            $html .= "    <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
            $html .= "				".ReturnOpenCalendario('FormaConsultar','fecha_inicio','-')."\n";
            $html .= "		</td>\n";
            $html .= "  </tr >\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "		<td align=\"CENTER\" >FECHA FINAL:</td>\n";
            $html .= "		<td class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "		    <input type=\"text\" class=\"input-text\"  name=\"buscador[fecha_final]\"  id=\"fecha_final\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" READONLY value=\"\"> \n";
            $html .= "		</td>\n";
            $html .= "    <td  class=\"modulo_list_claro\" >\n";
            $html .= "				".ReturnOpenCalendario('FormaConsultar','fecha_final','-')."\n";
            $html .= "		</td>\n";
            $html .= "  </tr >\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "  <td>NUMERO DE FARMACOVIGILANCIA</td>";
            $html .= "		<td class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "		    <input type=\"text\" class=\"input-text\"  name=\"buscador[esm_farmaco_id]\"  id=\"esm_farmaco_id\" size=\"20\" maxlength=\"10\" value=\"\"> \n";
            $html .= "		</td>\n";
            $html .= "		<td class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "    </td>";
            $html .= "  </tr >\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "  <td>NUMERO DE IDENTIFICACION</td>";
            $html .= "		<td class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "		    Tipo/Id<input type=\"text\" class=\"input-text\"  name=\"buscador[tipo_id_paciente]\"  id=\"tipo_id_paciente\" size=\"10\" maxlength=\"3\" value=\"\"> \n";
            $html .= "		</td>\n";
            $html .= "		<td class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "		    #<input type=\"text\" class=\"input-text\"  name=\"buscador[paciente_id]\"  id=\"paciente_id\" size=\"20\" maxlength=\"32\" value=\"\"> \n";
            $html .= "    </td>";
            $html .= "  </tr >\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "  <td>NOMBRE</td>";
            $html .= "		<td class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "		    <input type=\"text\" class=\"input-text\"  name=\"buscador[nombre]\"  id=\"nombre\" size=\"20\" maxlength=\"20\" value=\"\"> \n";
            $html .= "    </td>";
            $html .= "		<td class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "    </td>";
            $html .= "  </tr >\n";
            $html .= "</table>\n";
            $html .= "			<table   width=\"30%\" align=\"center\" border=\"0\"   >";
            $html .= "  <tr>\n";
            $html .= "	             	<td  colspan=\"10\"  align='center'>\n";
            $html .= "			         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"  >\n";
            $html .= "		          	</td>\n";
            $html .= "			<td  colspan=\"10\" align='center' >\n";
            $html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.FormaConsultar)\" value=\"Limpiar Campos\">\n";
            $html .= "	  	</td>\n";
            $html .= "		</tr>\n";
            $html .= "</table><br>\n";
            $reporte = new GetReports();
            if(!empty($datos))
            {
              $pghtml = AutoCarga::factory('ClaseHTML');
              $html .= "  <table width=\"100%\"   class=\"modulo_table_list\"  align=\"center\">";
                  
              $html .= "	  <tr align=\"CENTER\"    class=\"formulacion_table_list\" >\n";
              $html .= "      <td    width=\"5%\"><b>#</b></td>\n";
              $html .= "      <td    width=\"10%\"><b>NO FORMULA.</b></td>\n";
              $html .= "      <td    width=\"%\"><b>IDENTIFICACION.</b></td>\n";
              $html .= "      <td   width=\"30%\"><b>PACIENTE</b></td>\n";
              $html .= "      <td  width=\"10%\"><b>FECHA NOTIFICACION</b></td>\n";
              $html .= "      <td   width=\"15%\"><b>DIAGNOSTICO</b></td>\n";
              $html .= "      <td   width=\"45%\"><b>USUARIO REGISTRO</b></td>\n";
              $html .= "	      <td   colspan=\"2\">";
              $html .= "      <b>OP </b></td>\n";
              $html .= "  </tr>\n";
                    
              $est = "modulo_list_claro"; $back = "#DDDDDD";
              foreach($datos as $key => $dtl)
              {
                $html .= "	  <tr  align=\"CENTER\" class=\"".$est."\" >\n";
                $html .= "      <td align=\"center\"><B>".$dtl['esm_farmaco_id']."</B></td>\n";
                $html .= "      <td align=\"left\"><B>".$dtl['formula_papel']."</B></td>\n";
                $html .= "      <td align=\"left\"><B>".$dtl['tipo_id_paciente']."  ".$dtl['paciente_id']."</B></td>\n";
                $html .= "      <td align=\"left\"><B>".$dtl['apellidos']." ".$dtl['nombres']."</B></td>\n";
                $html .= "      <td align=\"left\"><B>".$dtl['fecha_notificacion']."</B></td>\n";
                $html .= "      <td align=\"left\"><B>".$dtl['diagnostico']."</B></td>\n";
                $html .= "      <td align=\"left\"><B>".$dtl['nombre']." - ".$dtl['descripcion']."</B></td>\n";
               
                $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
                $Formula_info=$obje->Consultar_Identificador_formula($dtl['formula_papel'],$dtl['tipo_id_paciente'],$dtl['paciente_id']);
                $formula_id=$Formula_info[0]['formula_id'];
          
          
          
                $mostrar = $reporte->GetJavaReport('app','Formulacion_Externa','FormatoFarmacov',array("esm_farmaco_id"=>$dtl['esm_farmaco_id'],"formula_r"=>$formula_id),
                                                    array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                
                $funcion = $reporte->GetJavaFunction();
                $html .= "				<td align=\"center\"  class=\"modulo_list_claro\">\n";
                $html .= "				".$mostrar."\n";
                $html .= "					<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE DEL PEDIDO\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' >\n";
                $html .= "			</a></center>\n";
                $html .= "			</td>\n";
                $html .= "			</tr>\n";
                            
              }
              $html .= "	</table><br>\n";
              $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
            }
            else
            {
              
              $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
            }
            $html .= "</form>";
            $html .= " <br>";
            $html .= "<table align=\"center\" width=\"50%\">\n";
            $html .= "  <tr>\n";
            $html .= "    <td align=\"center\">\n";
            $html .= "      <a href=\"".$action['volver'].URLRequest(array( "bodegades"=>$bodegades))."\"  class=\"label_error\">\n";
            $html .= "        Volver\n";
            $html .= "      </a>\n";
            $html .= "    </td>\n";
            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= ThemeCerrarTabla();
            return $html;
		}
	  /*
		* Forma para capturar los datos para buscar el paciente
		* @access private
		* @return boolean
		*/
      function FormaBuscarPacientes($action,$Plan_P,$TipoIdent,$bodegas_doc_id)
      {
          $html .=  "<script>\n";
          $html .= "  function Continuar()\n";
          $html .= "  {\n";
          $html .= "     document.formabuscar.action = '".$action['FormaDP']."';\n";
          $html .= "     document.formabuscar.submit();\n";
          $html .= "  }\n";
          $html .= "</script>\n";
          $html .= "	<script>\n";
          $html .= "		function EvaluarDatos(objeto)\n";
          $html .= "		{\n";
          $html .= "      xajax_ValidarPaciente(xajax.getFormValues('formabuscar'));\n";
          $html .= "		}\n";
          $html .= "	</script>\n";
          $html .= ThemeAbrirTabla('FORMULACION - BUSCAR PACIENTE');
          $html .= " <table align=\"center\" width=\"50%\">\n";
          $html .= "</table>\n";				
          $html .= "<center>\n";				
          $html .= "	<div id=\"errorA\" class=\"label_error\"></div>\n";				
          $html .= "</center>\n";
          $html .= "<form name=\"formabuscar\" id=\"formabuscar\"  action=\"javascript:EvaluarDatos(document.formabuscar)\" method=\"post\" >\n";
          $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
          $html .= "	<table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
          $html .= "		<input type='hidden' name='NoAutorizacion' value=''>\n";
          $html .="		<tr class=\"modulo_table_list_title\">\n";
          $html .= "			<td style=\"text-align:left;text-indent:11pt\">PLAN:</td>\n";
          $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "				<select name=\"Responsable\" class=\"select\">";
          foreach($Plan_P as $indice => $valor)
          {

              $html .= "  <option value=\"".$valor['plan_id']."\" ".$sel.">".$valor['plan_descripcion']."</option>\n";
          }
          $html .="              </select>\n";
          $html .= "			</td>\n";
          $html .= "		</tr>\n";
          $html .= "		<tr class=\"modulo_table_list_title\">\n";
          $html .= "			<td style=\"text-align:left;text-indent:11pt\">TIPO DOCUMENTO: </td>\n";
          $html .= "          <td align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "              <select name=\"TipoDocumento\" class=\"select\">\n";
          $html .= "            <option value = '-1'>--  SELECCIONE --</option>\n";
          foreach($TipoIdent as $indice => $valo)
          {

          $html .= "  <option value=\"".$valo['tipo_id_tercero']."\" ".$sel.">".$valo['descripcion']."</option>\n";
          }
          $html .= "              </select>\n";
          $html .= "			</td>\n";
          $html .= "		</tr>\n";
          $html .= "		<tr class=\"modulo_table_list_title\" >\n";
          $html .= "			<td style=\"text-align:left;text-indent:11pt\" >DOCUMENTO: </td>\n";
          $html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
          $html .= "				<input type=\"text\" class=\"input-text\" name=\"Documento\" size=\"20\"  maxlength=\"52\" value=\"\">\n";
          $html .= "			</td>\n";
          $html .= "		</tr>\n";
          $html .= "		<tr>\n";
          $html .="		</tr>\n";
          $html .="	</table>\n";
          if(!empty($bodegas_doc_id))
          {

                $html .= "	<table border=\"0\" align=\"center\" width=\"50%\">\n";
                $html .= "		<tr>\n";
                $html .= "			<td align=\"center\">\n";
                $html .= "				<br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"><br>\n";
                $html .= "			</td>\n";
                $html .= "  </tr>\n";
                $html .= "		</form>\n";
                $html .= "</table><BR><BR>\n";
          }else
          {
                $html .= "	<table border=\"0\" align=\"center\" width=\"50%\">\n";
                $html .= "		<tr>\n";
                $html .= "			<td align=\"center\" class=\"label_error\">\n";
                $html .= "			 	NO EXISTE UN DOCUMENTO PARAMETRIZADO PARA REALIZAR LA DISPENSACION  \n";
                $html .= "			</td>\n";
                $html .= "  </tr>\n";
                $html .= "		</form>\n";
                $html .= "</table><BR> ";
          
          }
          $html .= "<table align=\"center\" width=\"50%\">\n";
          $html .= "  <tr>\n";
          $html .= "    <td align=\"center\">\n";
          $html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
          $html .= "        VOLVER\n";
          $html .= "      </a>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "</table>\n";
          $html .= ThemeCerrarTabla();
          return $html;
      }
  /*
		* Forma para Digitalizar la cabecera de la FOrmula
		* @access private
		* @return boolean
		*/
      function FormaCabeceraFormula($action,$request,$Datos_Paciente,$Datos_Ad,$validar_paciente,$edad_paciente,$dx_ingres,$profesionales,$Tipo_Formula)
      {	
            $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
            $ctl = AutoCarga::factory("ClaseUtil"); 
            $html .= $ctl->LimpiarCampos();
            $html  = $ctl->IsNumeric();
            $html .= $ctl->AcceptNum(false);
            $html  .= "<script>\n"; 
            $html .= "	function acceptNum(evt)\n";
            $html .= "	{\n";
            $html .= "		var nav4 = window.Event ? true : false;\n";
            $html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
            $html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
            $html .= "	}\n";
            $html .= "  function evaluarDatosObligatorios(objeto)\n"; 
            $html .= "  {\n"; 
            $html .= "    if(objeto.fecha_recepcion.value == \"\")\n";
            $html .= "    {\n"; 
            $html .= "        document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE INGRESAR LA FECHA DE FORMULACION\";\n"; 
            $html .= "        return true;\n"; 
            $html .= "      }\n"; 
            $html .= "    if(objeto.formula_papel.value == \"\")\n";
            $html .= "    {\n"; 
            $html .= "        document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE INGRESAR EL NUMERO DE FORMULA\";\n"; 
            $html .= "        return true;\n"; 
            $html .= "      }\n";  
            $html .= "      if(objeto.Horas.value == \"-1\")\n";
            $html .= "        {\n"; 
            $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR LA HORA DE LA FORMULA\";\n"; 
            $html .= "          return true;\n"; 
            $html .= "        }\n";
            $html .= "      if(objeto.minuto.value == \"-1\")\n";
            $html .= "        {\n"; 
            $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR LA HORA DE LA FORMULA\";\n"; 
            $html .= "          return true;\n"; 
            $html .= "        }\n";
            $html .= "      if(objeto.profesional.value == \"-1\")\n";
            $html .= "        {\n"; 
            $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR PROFESIONAL\";\n"; 
            $html .= "          return true;\n"; 
            $html .= "        }\n";
            $html .= "    objeto.submit();\n";
            $html .= "  }\n";
       /*     $html .= "  function A()  ";
            $html .= "  {  ";
            $html .= "     i=setInterval(\"B()\",1000);  ";
            $html .= "  }  ";
            $html .= "  function B()  ";
            $html .= "  {  ";
            $html .= "    clearInterval(i); ";
            $html .= "  xajax_ValidarFechas(xajax.getFormValues('registrar_cabecera')); ";
            $html .= "    A(); ";
            $html .= "  } ";*/
            $html .= "</script>\n"; 
 
          $html .= ThemeAbrirTabla('REGISTRO DE UNA FORMULA');
          $html .= "<div id=\"Mensaje_Fecha\" ></div>";
          $html .= "<body onload=\"A()\"> 	";
          $html .= "<form name=\"registrar_cabecera\" id=\"registrar_cabecera\"   action=\"".$action['continuar']."\"   method=\"post\">\n";
          $html .= "<input type=\"hidden\" name=\"tipo_afiliado\" value=\"".$Datos_Paciente['tipo_afiliado_atencion']."\">\n";
          $html .= "<input type=\"hidden\" name=\"rango\" value=\"".$Datos_Paciente['rango_afiliado_atencion']."\">\n";
          $html .= "		<center>\n";
          $html .= "			<label id=\"error_actividad\" class=\"label_error\"></label>\n";
          $html .= "	  </center>\n";	
          $today=date('d-M-Y');
          $html .= "							<table  align=\"right\" width=\"30%\" class=\"label\" $style >\n";
          $html .= "								<tr >\n";
          $html .= "									<td   class=\"formulacion_table_list\" >FECHA ACTUAL</td>\n";
          $html .= "									<td  align=\"right\" > $today";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          $html .= "							</table> <br>\n";

          $html .= "<table border=\"0\" width=\"98%\" align=\"center\" >\n";
          $html .= "	<tr>\n";
          $html .= "		<td>\n";
          $html .= "			<fieldset class=\"fieldset\">\n";
          $html .= "				<legend class=\"normal_10AN\">INFORMACION DE LA FORMULA</legend>\n";
          $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
          $html .= "					<tr>\n";
          $html .= "						<td align=\"center\">\n";

            
          $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
          $html .= "								<tr >\n";
          $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE FORMULA</td>\n";
          $html .= "									<td width=\"10%\" align=\"right\">\n";
          $html .= "										<input type=\"text\" class=\"input-text\" name=\"fecha_recepcion\" value=\"\" style=\"width:90%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" >\n";
          $hora_actual=date('H:i');
          list($Hora,$min) = split(":",$hora_actual);
          $html .= "									</td>\n";
          $html .= "									<td align=\"left\" >".ReturnOpenCalendario('registrar_cabecera','fecha_recepcion','/')."</td>\n";
          $html .= "								  <td colspan=\"3\">&nbsp;</td>\n";
          $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FORMULA No</td>\n";
          $html .= "									<td width=\"10%\" align=\"right\">\n";
          $html .= "										<input type=\"text\" class=\"input-text\" name=\"formula_papel\" style=\"width:90%\" maxlength=\"30\"  value=\"".$request['formula_papel']."\">\n";
          $html .= "									</td>\n";
          $html .= "									<td  class=\"formulacion_table_list\" >HORA</td>\n";
          $html .= "									<td >\n";
          $html .= "										<select name=\"Horas\" class=\"select\">\n";
          for($hor=0;$hor<=24;$hor++)
            {
   
              if($hor==$Hora)
              $sel = "selected";
              else
              $sel = "";
              $html .= "											<option value=\"".$hor."\" ".$sel." >".$hor."</option>\n";
           
            }
            $html .= "										</select>\n";
            $html .= "									</td> \n";
            $html .= "									<td  class=\"formulacion_table_list\" >MINUTOS</td>\n";
            $html .= "									<td >\n";
            $html .= "										<select name=\"minuto\" class=\"select\">\n";
            for($minu=0;$minu<=60;$minu++)
            {
                        
              if($minu==$min)
              $sel = "selected";
              else
              $sel = "";
              $html .= "											<option value=\"".$minu."\" ".$sel." >".$minu."</option>\n";
                
            }
                                                                  
            $html .= "										</select>\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            $html .= "							</table>\n";
          
            $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO FORMULA</td>\n";
            $html .= "									<td colspan=\"2\">\n";
            $html .= "										<select name=\"tipo_formula\" class=\"select\" style=\"width:60%\">\n";
            foreach($Tipo_Formula as $key => $dtl)
            {
			$html .= "  										<option value=\"".$dtl['tipo_formula_id']."\" ".$sel.">";
			$html .= "												".$dtl['descripcion_tipo_formula']."".(($dtl['tope']!="")? " (SALDO TOPE MENSUAL: $".FormatoValor($dtl['tope']).")":"")."";
			$html .= "											</option>\n";
            }
            $html .= "										</select>\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            $html .= "								</tr>\n";
            $html .= "							</table>\n";
          
          
          $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
          $html .= "								<tr >\n";
          $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONALES</td>\n";
          $html .= "									<td >\n";
          $html .= "										<select name=\"profesional\" class=\"select\">\n";
          foreach ($profesionales as $key=>$dt)
          {
              $html .= "											<option value=\"".$dt['tipo_id_tercero']."@".$dt['tercero_id']."\" ".$sel." >".$dt['nombre']." &nbsp;  - ".$dt['descripcion']."</option>\n";
           
          }
            $html .= "										</select>\n";
            $html .= "									</td> \n";
          
            $html .= "								</tr>\n";
            $html .= "							</table>\n";
            
            
            $html .= "					<tr>\n";
            $html .= "						<td align=\"center\">\n";
            
            
            $html .= "						<td>\n";
            $html .= "					</tr>\n";
            $html .= "					<tr>\n";
            $html .= "						<td align=\"center\">\n";
            $html .= "							<table  width=\"98%\" class=\"label\" $style>\n";
            $html .= "								<tr>\n";
            $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
            $html .= "									<td colspan=\"3\">\n";
            $html .= "										".$request['tipo_id_paciente']." ".$request['paciente_id']."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";			
            $html .= "								<tr class=\"formulacion_table_list\">\n";
            $html .= "									<td width=\"25%\">PRIMER APELLIDO</td>\n";
            $html .= "									<td width=\"25%\">SEGUNDO APELLIDO</td>\n";
            $html .= "									<td width=\"25%\">PRIMER NOMBRE</td>\n";
            $html .= "									<td width=\"25%\">SEGUNDO NOMBRE</td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr align=\"center\">\n";
            $html .= "									<td> ".$Datos_Paciente['primer_apellido']." &nbsp; ".$Datos_Paciente['segundo_apellido']."\n";

            $html .= "									</td>\n";
            $html .= "									<td>".$Datos_Paciente['segundo_apellido']."\n";
            $html .= "									</td>\n";
            $html .= "									<td>".$Datos_Paciente['primer_nombre']."\n";
            $html .= "									</td>\n";
            $html .= "									<td>".$Datos_Paciente['segundo_nombre']."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            
            if($Datos_Paciente['tipo_sexo_id']=='M')
            {
             $sexo='MASCULINO';
            
            }else
            {
            $sexo='FEMENINO';
            
            }
            list($anio,$mes,$dias) = explode(":",$Datos_Paciente['edad']);
            if($anio!=0)
            {
            
             $edad_t='AÑOS';
             $edad=$anio;
            }
            if($anio==0 and $mes!=0)
            {
              $edad_t='MES';
               $edad=$mes;
            }
            else
            {
                  if($anio==0 and $mes==0)
              {
                  $edad_t='DIAS';
                  $edad=$dias;
              }
            
            }	
            $html .= "								<tr>\n";
            $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">EDAD</td>\n";
            $html .= "									<td >".$edad." &nbsp; $edad_t \n";
            $html .= "									</td>\n";
            $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
            $html .= "									<td align=\"left\">".$sexo."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";	
                       
            $html .= "								<tr>\n";
            $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO PLAN</td>\n";
            $html .= "									<td >".$Datos_Ad['tipo_plan']."\n";
            $html .= "									</td>\n";
            $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO VINCULACION</td>\n";
            $html .= "									<td >".$Datos_Ad['vinculacion']."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";	
            
            $html .= "							</table>\n";
            $html .= "						</td>\n";
            $html .= "					</tr>\n";
            
            $html .= "					<tr colspan=\"10\"  >\n";
            $html .= "						<td  colspan=\"10\"  align=\"center\"  id=\"dx_registrados\"  name=\"dx_registrados\">\n";
            $html .= "						<td>\n";
            $html .= "					</tr>\n";
                 
            
            $html .= "				</table>\n";
            $html .= "			</fieldset>\n";
            $html .= "		</td>\n";
            $html .= "	</tr>\n";
            $html .= "</table>\n";
            
            if(empty($validar_paciente))
            {
            
              $html .= "	<table  align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"55%\">";
              $html .= "	<tr class=\"formulacion_table_list\" >";
              $html .= "	<td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
              $html .= "	</tr>";
              $html .= "	<tr class=\"formulacion_table_list\" >";
              $html .= "	<td width=\"10%\">CODIGO:</td>";
              $html .= "	<td width=\"25%\"  class=\"modulo_list_oscuro\"  align='LEFT'><input type='text' class='input-text' size =\"35\" maxlength = 10	name = \"codigo_dx\" ></td>" ;
              $html .= "	<td  width=\"7%\"   class=\"modulo_list_oscuro\" align=\"center\"><input class=\"input-submit\" name=\"buscar\" type=\"button\" value=\"ASIGNAR\"  onclick=\"xajax_MostrarDX(xajax.getFormValues('registrar_cabecera'),'".$request['tipo_id_paciente']."','".$request['paciente_id']."')\"></td>";
              $html .= "	</tr>";
              $html .= "								<tr >\n";
              $html .= "									<td colspan=\"5\"  class=\"label_error\"  align=\"center\" name=\"no_informacion\" id=\"no_informacion\"  >\n";
              $html .= "						     </td>\n";
              $html .= "								</tr>\n";
              $html .= "	</table><br>";
            }
            
          if(!empty($validar_paciente))
          {
              $html .= "  <table border=\"-1\" width=\"35%\" align=\"center\" >\n";
              $html .= "	  <tr>\n";
              $html .= "		  <td align=\"center\" class=\"label_error\">EL PACIENTE ACTUALMENTE ESTA EN PROCESO DE FORMULACION\n";
              $html .= "	  </tr>\n";
              $html .= "	  <tr>\n";
              $html .= "		  <td align=\"center\" class=\"label_error\">USUARIO: ".$validar_paciente['nombre']."  USUARIO ID:".$validar_paciente['usuario']." \n";
              $html .= "	  </tr>\n";
              $html .= "</table><BR>";
            }
            
            $html .= "  <table border=\"-1\" width=\"15%\" align=\"center\" >\n";
            $html .= "	  <tr>\n";
            if(!empty($validar_paciente))
            {
            
                $disabled="disabled=true";
            }
            else
            {
                $disabled="  ";
            
            }
            $html .= "		  <td align=\"center\"><br>\n";
            $html .= "			  <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"GUARDAR\"  $disabled onclick=\"evaluarDatosObligatorios(document.registrar_cabecera)\" >\n";
            $html .= "		  </td>";
            $html .= "		</form>\n";
            $html .= "		<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
            $html .= "		  <td align=\"center\"><br>\n";
            $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">\n";
            $html .= "		  </td>";
            $html .= "		</form>\n";
            $html .= "	</tr>";
            $html .= "</table> </body>";
            $html .= ThemeCerrarTabla();	
            return $html;
          }
    /*
		* Forma para Buscar los productos a formular
		* @access private
		* @return boolean
		*/
	 function FormaBuscarProductos_Ambulatoria($action,$request,$datos,$conteo,$pagina,$medi_form,$Cabecera_Formulacion,$DX_,$request,$Datos_Ad,$formula_id,$tipo_id_paciente,$paciente_id,$plan_id,$tiempo_entrega,$datos_empresa,$privilegios)
	 {		
  
			$ctl = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->LimpiarCampos();
			$html .= $ctl->AcceptNum(false);
			$html .= $ctl->RollOverFilas();
			$today = date("Y-m-d");

			$style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
			$estilo = "style=\"border-bottom-width:0px;border-left-width:2px;border-right-width:0px;border-top-width:0px;border-style: solid;\""; 
			$html .= ThemeAbrirTabla('REGISTRO DE UNA FORMULA  -MEDICAMENTOS FORMULADOS ');
			
			$html .= "	<script>";
			$html .= "		function ValidarCantidades_Formuladas(cantidad_max_formulacion,cantidad,descripcion)";
			$html .= "		{";
			$html .= "			var x = parseInt(cantidad_max_formulacion);";
			$html .= "			var y = parseInt(cantidad);";
			$html .= "				if(x>0 && y>0)";
			$html .= "					if(y>=x)";
			$html .= "						alert('Advertencia: Las Cantidades a Formular de '+descripcion+' Superan Las Cantidades Estandar de Formulacion. Max Unidades Parametrizadas '+x);";
			$html .= "		return true;";
			$html .= "		}";
			$html .= "	</script>";
			
			$html .= " <script> \n";
			$html .= " function Recoger_Datos(descripcion,codigo_producto,cantidad_total,principio_activo,formula_id) ";
			$html .= " {";
			$html .= "   xajax_BuscarProducto1(xajax.getFormValues('registrar_dx'),descripcion,codigo_producto,cantidad_total,principio_activo,formula_id); ";
			$html .= " }";
			$html .= " function Recoger_Datos2(descripcion,codigo_producto,cantidad_total,principio_activo,formula_id) ";
			$html .= " {";

			$html .= " if(cantidad_total==\"\"){ ";
			$html .= " alert('Ingrese la Cantidad a Formular'); ";
			$html .= " } else{ ";  

			$html .= "   xajax_Marcar_Producto(xajax.getFormValues('registrar_dx'),descripcion,codigo_producto,cantidad_total,principio_activo,formula_id); ";
			$html .= "}";
			$html .= "}";
			$html .= "	function ValidarCantidad(campo,valor,cant_sol,capa)\n";
			$html .= "	{\n";
			$html .= "		document.getElementById(campo).style.background='';\n";
			$html .= "		document.getElementById('error').innerHTML='';\n";
			$html .= "		if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
			$html .= "		{\n";
			$html .= "			document.getElementById(campo).value='';\n";
			$html .= "			document.getElementById(campo).style.background='#ff9595';\n";
			$html .= "			document.getElementById('error').innerHTML='<center>CANTIDAD NO VALIDA</center>';\n";
			$html .= "			document.getElementById(capa).style.display=\"none\"\n";
			$html .= "		}\n";
			$html .= "		else{\n";
			$html .= "			document.getElementById(capa).style.display=\"\"\n";
			$html .= "		}\n";
			$html .= "	}\n";
			$html .= "	function Recargar_informacion(descripcion,codigo_producto,cantidad_total,principio_activo,formula_id,bodega_otra)\n";
			$html .= "	{\n";

			$html .= " document.registrar_dx.bodega.value=bodega_otra; ";
			$html .= "   xajax_BuscarProducto1(xajax.getFormValues('registrar_dx'),descripcion,codigo_producto,cantidad_total,principio_activo,formula_id); ";
			$html .= " }";

			$html .=" </script>\n";

			$html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend class=\"normal_10AN\">INFORMACION DE LA FORMULA</legend>\n";
			$html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE REGISTRO</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_formula']."\n";
			$html .= "									</td>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE FORMULA</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_formula']."\n";
			$html .= "									</td>\n";

			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FORMULA No</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['tmp_formula_papel']."\n";
			$html .= "									</td>\n";


			$html .= "									<td  class=\"formulacion_table_list\" >HORA</td>\n";
			$html .= "									<td >".$Cabecera_Formulacion['hora_formula']."\n";
			$html .= "									</td> \n";

			$html .= "								</tr>\n";
			$html .= "							</table>\n";


			$html .= "							<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO FORMULA</td>\n";
			$html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['descripcion_tipo_formula']."\n";
			if($Cabecera_Formulacion['tope']!="")
				{
			$html .= "										<b title=\"SALDO DEL TIPO DE DISPENSACION\" class=\"label_error\" style=\"font-size:150%;cursor:help\"> :: SALDO TOPE: $<i><u>".FormatoValor($Cabecera_Formulacion['tope'],2)."</u></i></b>";
				}
			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			$html .= "								</tr>\n";
			$html .= "							</table>\n";




			$html .= "							<table width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
			$html .= "									<td >".$Cabecera_Formulacion['tipo_id_tercero']." ".$Cabecera_Formulacion['tercero_id']." &nbsp;&nbsp; ".$Cabecera_Formulacion['profesional']." - ".$Cabecera_Formulacion['descripcion_profesional']."  \n";
			$html .= "									</td> \n";
			$html .= "								</tr>\n";
			$html .= "							</table>\n";

			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";


			$html .= "						<td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table  width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
			$html .= "									<td colspan=\"3\">\n";
			$html .= "										".$request['tipo_id_paciente']." ".$request['paciente_id']."\n";

			$html .= "									</td>\n";
			$html .= "									<td  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">NOMBRE COMPLETO</td>\n";
			$html .= "									<td  > ".$Cabecera_Formulacion['nombre_paciente']."\n";

			$html .= "									</td>\n";

			$html .= "								</tr>\n";
			
			if($Cabecera_Formulacion['sexo_id']=='M')
			{
			 $sexo='MASCULINO';
			
			}else
			{
			$sexo='FEMENINO';
			
			}
			list($anio,$mes,$dias) = explode(":",$Cabecera_Formulacion['edad']);
						
			if($anio!=0)
			{
			
			 $edad_t='AÑOS';
			 $edad=$anio;
			}
			if($anio==0 and $mes!=0)
			{
			  $edad_t='MES';
			   $edad=$mes;
			}
			else
			{
		        if($anio==0 and $mes==0)
				{
				$edad_t='DIAS';
				    $edad=$dias;
				}
			
			}	
			
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">EDAD</td>\n";
			$html .= "									<td >".$edad." &nbsp; $edad_t \n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
			$html .= "									<td align=\"left\">".$sexo."\n";
			$html .= "									</td>\n";
		
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO PLAN</td>\n";
			$html .= "									<td >".$Datos_Ad['tipo_plan']."\n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO VINCULACION</td>\n";
			$html .= "									<td >".$Datos_Ad['vinculacion']."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";
		  $html .= "							</table>\n";
		  $html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			
			
			$html .= "						<td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table   width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr>\n";
			$html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">DIAGNOSTICO</td>\n";
			$html .= "									<td  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">DESCRIPCION</td>\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			
			foreach($DX_ as $key => $dtl)
			{
			$html .= "					<tr>\n";
			$html .= "									<td  colspan=\"1\" >".$dtl['diagnostico_id']."</td>\n";
			$html .= "									<td  colspan=\"3\" >".$dtl['diagnostico_nombre']."</td>\n";
			$html .= "								</tr>\n";
			}
			
			$html .= "							</table>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
      $html .= "						<td>\n";
			$html .= "					</tr>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";

			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";
      
       $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
   
        $vencida='';
        $today = date("Y-m-d"); 
        $dias_vigencia_formula= ModuloGetVar('','','dispensacion_dias_vigencia_formula');
        list($d,$m,$a) = split("-",$Cabecera_Formulacion['fecha_formula']);
        $fecha_condias = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_vigencia_formula),$a)));
        if($fecha_condias < $today )
        {
            $vencida='VENCIDA';
        }
		print_r($fecha_condias."<".$today);
			
              if($vencida=='' ||  $Cabecera_Formulacion['sw_autorizado']=='1')
              {
                       if(!empty($tiempo_entrega))
                     {
                            $html .= "<form name=\"registrar_cabecera\" id=\"registrar_cabecera\"    action=\"".$action['buscador']."\"   method=\"post\">\n";
                            $html .= "<input type=\"hidden\" name=\"tipo_afiliado\" value=\"".$Datos_Paciente['tipo_afiliado_atencion']."\">\n";
                            $html .= "<input type=\"hidden\" name=\"rango\" value=\"".$Datos_Paciente['rango_afiliado_atencion']."\">\n";
                            $html .= "		<center>\n";
                            $html .= "			<label id=\"error_actividad\" class=\"label_error\"></label>\n";
                            $html .= "	  </center>\n";	
                            $html .= "<table border=\"0\" width=\"65%\" align=\"center\" >\n";
                            $html .= "	<tr>\n";
                            $html .= "		<td>\n";
                            $html .= "			<fieldset class=\"fieldset\">\n";
                            $html .= "				<legend class=\"normal_10AN\">BUSCADOR DE PRODUCTOS PARA LA FORMULACION</legend>\n";
                         
                            $html .= "	<table  align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"100%\">";
                            $html .= "	<tr class=\"formulacion_table_list\" >";
                            $html .= "	<td  colspan=\"2\" >CODIGO DE BARRAS:</td>";   
                            $html .= "	<td  width=\"20%\"  class=\"modulo_list_oscuro\"  align='left'><input type='text' class='input-text' size =\"10\"  style=\"width:100%\"  maxlength =60	name = \"buscador[codigo_barras]\" ></td>" ;
                        
                            $html .= "	<td  colspan=\"2\" >DESCRIPCION:</td>";
                            $html .= "	<td  width=\20%\"  class=\"modulo_list_oscuro\" align='left'><input type='text' size =\"15\" class='input-text' 	  style=\"width:100%\" name =\"buscador[descripcion]\"   value =\"".$request['descripcion']."\"></td>" ;

                            $html .= "	</tr>";
                            $html .= "	<tr class=\"formulacion_table_list\" >";
                            $html .= "	<td   colspan=\"2\"  >PRINCIPIO ACTIVO:</td>";
                            $html .= "	<td width=\"20%\" class=\"modulo_list_oscuro\" align='center'><input type='text' size =\"10\"  style=\"width:100%\" class='input-text' 	name =\"buscador[principio_activo]\"   value =\"".$request['producto']."\"></td>" ;

                            $html .= "	<td  colspan=\"4\"   class=\"modulo_list_oscuro\"  align=\"center\"><input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"BUSQUEDA\"></td>";
                            $html .= "			</fieldset>\n";
                            $html .= "		</td>\n";
                            $html .= "	</tr>\n";
                            
                              $html .= "</table>\n";
                          
                            $html .= "	</tr>";
                            $html .= "	</table><br>";
							
							
							$html .= "	<table width=\"50%\" class=\"modulo_table_list\" align=\"center\">";
							$html .= "		<tr>";
							$html .= "			<td style=\"width:100%;background:#00FF40;\" align=\"center\">";
							$html .= "				PRODUCTO CON EXISTENCIAS RESERVADAS";
							$html .= "			</td>";
							$html .= "		</tr>";
							$html .= "	</table>";
													
							
                            $html .= "		</form>\n";
                            $html .= "<form name=\"registrar_dx\" id=\"registrar_dx\"    action=\"".$action['guardar']."\"   method=\"post\">\n";
                            
                            if ($datos)
                            {
                                $pghtml = AutoCarga::factory('ClaseHTML');
								$html .= "<table   class=\"modulo_table_list\"  align=\"center\" border=\"0\" width=\"100%\" rules=\"all\">";
								$html .= "<tr class=\"formulacion_table_list\">";
								$html .= "<td align=\"center\" colspan=\"8\">RESULTADO DE LA BUSQUEDA</td>";
								$html .= "</tr>";
								$html .= "<tr class=\"formulacion_table_list\">";
								$html .= "  <td width=\"8%\">CODIGO</td>";
								$html .= "  <td width=\"25%\">MOLECULA</td>";
								$html .= " <td width=\"35%\">PRODUCTO</td>";
								$html .= " <td width=\"10%\">CANTIDAD TOTAL</td>";
								$html .= " <td width=\"10%\">EXISTENCIAS</td>";
								$html .= " <td width=\"80%\">TIEMPO ENTREGA</td>";
								$html .= " <td width=\"5%\">OP</td>";
								$html .= " <td width=\"5%\">MAR</td>";
								$html .= "                        <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$datos_empresa['empresa_id']."\">";
								$html .= "                        <input type=\"hidden\" name=\"centro_utilidad\" id=\"centro_utilidad\" value=\"".$datos_empresa['centro_utilidad']."\">";
								$html .= "                        <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"".$datos_empresa['bodega']."\">";
								$html .= "                        <input type=\"hidden\" name=\"tipo_id_paciente\" id=\"tipo_id_paciente\" value=\"".$request['tipo_id_paciente']."\">";
								$html .= "                        <input type=\"hidden\" name=\"paciente_id\" id=\"paciente_id\" value=\"".$request['paciente_id']."\">";
								$html .= "                        <input type=\"hidden\" name=\"plan_id\" id=\"plan_id\" value=\"".$request['plan_id']."\">";
								$html .= "                        <input type=\"hidden\" name=\"tiempo_entrega\" id=\"tiempo_entrega\" value=\"".$tiempo_entrega."\">";
								$html .= "                        <input type=\"hidden\" name=\"fecha_formula\" id=\"fecha_formula\" value=\"".$Cabecera_Formulacion['fecha_formula']."\">";
								$html .= "</tr>";
                               
                              $con=0;
                              foreach($datos as $key => $dtl)
                              {
                                  $codigo= $dtl['codigo_producto'];
                                  $producto= $dtl['descripcion'];
                                  $molecula=$dtl['molecula'];
                                  $existencia=$dtl['existencia'];
								($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
								($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
								$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
                                  $html .= "<td align=\"left\" width=\"8%\">$codigo</td>";
                                  $html .= "<td align=\"left\" width=\"25%\">$molecula</td>";
                                  $html .= "<td align=\"left\" width=\"35%\">$producto</td>";
                                  $html .= "     <td>";
                                  $html .= "      <input style=\"width:100%\" type=\"text\" onkeypress=\"return acceptNum(event);\"   class=\"input-text\" value=\"\" name=\"cantidad_formulada".$codigo."\" id=\"cantidad_formulada".$codigo."\" >";
                                  $html .= "      </td>";
                                  $html .= "<td align=\"center\" width=\"10%\" >".round($existencia)."</td>";
                                  $html .= "  <td  width=\"10%\"align=\"center\" >".$tiempo_entrega." DIA(S) ";
                                  $html .= "   </td>";
                                  
                                  $marcado=$obje->ConsultaMarcado_por_medicamento($formula_id,$codigo);
                                     
                                  if($dtl['sw_requiereautorizacion_despachospedidos']=='1')
                                  {
                                
                                                $html .= "      <td align=\"center\">\n";
                                                $html .= "         <img src=\"".GetThemePath()."/images/producto.png\" border='0' ></a>\n";
                                                $html .= "      </td>\n";
                                                
                                                $html .= "      <td align=\"center\">\n";
                                                $html .= "         <img src=\"".GetThemePath()."/images/checkno.png\" border='0' ></a>\n";
                                                $html .= "      </td>\n";
                                   } 
                                   else
                                  {
                               
                                              if(empty($marcado))
                                              {
                                                      
                                                  
                                                        $html .= "      <td align=\"center\">\n";
                                                        $html .= "         <a  onclick=\"Recoger_Datos('".$dtl['descripcion']."','".$dtl['codigo_producto']."',document.registrar_dx.cantidad_formulada".$codigo.".value,'".$dtl['cod_principio_activo']."','".$formula_id."')\"  class=\"label_error\" ><img onclick=\"ValidarCantidades_Formuladas('".$dtl['cantidad_max_formulacion']."',document.registrar_dx.cantidad_formulada".$codigo.".value,'".$dtl['molecula']."');\" src=\"".GetThemePath()."/images/producto.png\" border='0' ></a>\n";
                                                        $html .= "      </td>\n";
                                          
                                                        $html .= "      <td align=\"center\">\n";
                                                        $html .= "         <a onclick=\"Recoger_Datos2('".$dtl['descripcion']."','".$dtl['codigo_producto']."',document.registrar_dx.cantidad_formulada".$codigo.".value,'".$dtl['cod_principio_activo']."','".$formula_id."')\"  class=\"label_error\" ><img onclick=\"ValidarCantidades_Formuladas('".$dtl['cantidad_max_formulacion']."',document.registrar_dx.cantidad_formulada".$codigo.".value,'".$dtl['molecula']."');\" src=\"".GetThemePath()."/images/checkno.png\" border='0' ></a>\n";
                                                        $html .= "      </td>\n";
                                              }else
                                              {
                                                      $html .= "      <td align=\"center\">\n";
                                                      $html .= "         <img src=\"".GetThemePath()."/images/producto.png\" border='0' ></a>\n";
                                                      $html .= "      </td>\n";

                                                      $html .= "      <td align=\"center\">\n";
                                                      $html .= "         <img src=\"".GetThemePath()."/images/checksi.png\" border='0' ></a>\n";
                                                      $html .= "      </td>\n";
                                                }
                                  
                                            
                                  }
                                  $con++;
                              } 
                               $html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$i."\">";
                                $html .= "</table><br>";
                                $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
                       
                          }    
                              $html .= "		</form>\n";
                              
                              $html .= "  <div id='productostmp'></div>\n";

                              $html.= " <script>" ;
                              $html.= " xajax_MostrarProductox('".$formula_id."'); ";
                              $html.= " </script>" ;
                  
                        }else
                       {
                              $html .= "<table  align=\"center\" border=\"0\"   class=\"modulo_table_list\" width=\"100%\">";
                              $html .= "	<tr class=\"label_error\" >";
                              $html .= "  <td align=\"center\" colspan=\"6\">NO SE EXISTE PARAMETRIZADO EL TIEMPO DE ENTREGA PARA ESTA FARMACIA</td>";
                              $html .= "</tr>";
                              $html .= "</table>";
                  
                       }
              }else
              {
                                    $html .= "               <table width=\"60%\" align=\"center\">";
                                    $html .= "	<tr>\n";
                                    $html .= "      <td   align=\"center\" class=\"label_error\"><U>FORMULA VENCIDA  - ¡ NO SE PUEDE DISPENSAR SIN AUTORIZACION!</U>\n";
                                    $html .= "          <img border=\"0\"  title=\"FORMULA VENCIDA\" src=\"".GetThemePath()."/images/alarma.gif\">\n";
                                    $html .= "<embed src=\"".GetBaseURL()."/Sonido_Alertas/formula_vencida.mid\" hidden=\"true\" type=\"midi\" loop=\"true\"></embed > ";
                                    $html .= "      </td>\n";
                                    $html .= "	</tr>\n";
                                    $html .= "                 </table>";
                                 
                                    if($privilegios['sw_privilegios']=='1')
                                          {
                                           $html .= "<form name=\"registrar_cabecera\" id=\"registrar_cabecera\"    action=\"".$action['autorizar']."\"   method=\"post\">\n";
                                          $html .= "	<table   class=\"modulo_table_list\" align=\"center\" border=\"0\" width=\"30%\" >\n";
                                          $autorizacion='1';
                                          $html .= "  <tr class=\"formulacion_table_list\">\n";
                                          $html .= "      <td   colspan=\"15\" align=\"CENTER\">OBSERVACIONES:</td>\n";
                                          $html .= "  </tr >\n";
                                          $html .= "  <tr class=\"modulo_table_list_title\">\n";
                                          $html .= "      <td   colspan=\"13\"  align=\"left\" class=\"modulo_list_claro\"> <textarea  onkeypress=\"return max(event)\"  name=\"observaciones\"  id=\"observaciones\"   rows=\"2\"  style=\"width:100%\"></textarea>\n";
                                          $html .= "       </td>\n";
                                          $html .= "  </tr >\n";
                                          $html .= "		<tr  align=\"center\">";
                                          $html .= "      <td  >";
                                          $html .= "      <input type=\"submit\" class=\"input-submit\" value=\"AUTORIZAR DESPACHO DE LA FORMULA\" style=\"width:100%\" >";
                                          $html .= "      </td>";
                                          $html .= "		</tr>\n";
                                          $html .= "    </table>";
                                          $html .= "		</form>\n";
                               
                                    }
              }
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
       $html .= $this->CrearVentana(750,"BUSCADOR DE PRODUCTOS");
       	$html .= ThemeCerrarTabla();
        return $html;
	
		}
    
       /**
    * Funcion donde se crea la forma para visualizar que medicamentos fueron seleccionados para despachar
    * @param array $action Vector de links de la aplicaion
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
  	function Forma_Preparar_Documento_Dispensar_($action,$empresa,$Cabecera_Formulacion,$temporales,$formula_id,$todo_pendiente)
    {
            
            $html  = ThemeAbrirTabla('ENTREGA MEDICAMENTOS ');
            $html .= "<form name=\"FormaPintarEntrega\" id=\"FormaPintarEntrega\"  method=\"post\" >\n";
            $html .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                   <tr class=\"formulacion_table_list\">\n";
            $html .= "                     <td align=\"center\">\n";
            $html .= "                        <a title='farmacia'>FARMACIA:<a>";
            $html .= "                      </td>\n";
            $html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
            $html .= "                          ".$empresa['razon']." -".$empresa['centro'];
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                         BODEGA";
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "                       ".$empresa['descripcion'];
            $html .= "                       </td>\n";
            $html .= "  </tr>\n";
            $html .= "                   <tr class=\"formulacion_table_list\">\n";
            $html .= "                     <td align=\"center\">\n";
            $html .= "                        <a title='Identificacion'>IDENTIFICACION:<a>";
            $html .= "                      </td>\n";
            $html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
            $html .= "                          ".$Cabecera_Formulacion['tipo_id_paciente']." ".$Cabecera_Formulacion['paciente_id'];
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                         PACIENTE ";
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "                        ".$Cabecera_Formulacion['nombre_paciente'];
            $html .= "                       </td>\n";
            $html .= "  </tr>\n";
            $html .= "	</table><br>\n";
           
            if(!empty($temporales))
            {
              $html .= "  <table width=\"85%\" class=\"modulo_table_list\"   align=\"center\">";
              $html .= "	  <tr class=\"formulacion_table_list\" >\n";
              $html .= "      <td width=\"10%\" >CODIGO</td>\n";
              $html .= "      <td width=\"65%\">PRODUCTO</td>\n";
              $html .= "      <td width=\"10%\">FECHA VEC</td>\n";
              $html .= "      <td width=\"10%\">LOTE</td>\n";
              $html .= "      <td width=\"7%\">ENTREGA</td>\n";
              $html .= "  </tr>\n";
              foreach($temporales as $k1 => $dt1)
              {
                      
                  $html .= "  <tr class=\"modulo_list_claro\" >\n";
                  $html .= "      <td align=\"left\"><b>".$dt1['codigo_producto']."</b></td>\n";
                  $html .= "      <td align=\"left\"><b>".$dt1['descripcion_prod']."</b></td>\n";
                  $html .= "      <td align=\"left\"><b>".$dt1['fecha_vencimiento']."</b></td>\n";
                  $html .= "      <td align=\"left\"><b>".$dt1['lote']."</b></td>\n";
                  $html .= "      <td align=\"left\"><b>".$dt1['cantidad_despachada']." </b></td>\n";
                  $html .= "  </tr>\n" ;
                            
              }
              $html .= "	</table><br>\n";
            }else
            {
                    $html .= "  <table width=\"85%\" class=\"modulo_table_list\"   align=\"center\">";
                    $html .= "	  <tr  align=\"center\" class=\"label_error\" >\n";
                    $html .= "      <td width=\"20%\" ><U><I>TODOS LOS PRODUCTOS DE LA FORMULA PENDIENTES POR DESPACHAR </I></U></td>\n";
                    $html .= "  </tr>\n";
                    $html .= "	</table><br>\n";
                   
            }
            $html .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                     <tr >\n";
            $html .= "                        <td rowspan='1' colspan='10' align=\"center\" class=\"modulo_list_claro\"> \n";
            $html .= "                          <fieldset>";
            $html .= "                           <legend>OBSERVACIONES</legend>";
            $html .= "                              <TEXTAREA id='observar' name='observar' ROWS='3' COLS=100></TEXTAREA>\n";
            $html .= " <input type=\"hidden\" name=\"observacion2\" value=\" Formula No: ".$Cabecera_Formulacion['tmp_formula_papel']."  Paciente:".$Cabecera_Formulacion['tipo_id_paciente']." ".$Cabecera_Formulacion['paciente_id']." ".$Cabecera_Formulacion['nombre_paciente']."\" > ";
            $html .= "                        </td>\n";
            $html .= "                     </tr>\n";
            $html .= "</table>\n";
            $html .= "<table width=\"85%\" align=\"center\">\n";
            $html .= "  <tr>\n";
            $html .= "      <td align=\"center\"class=\"modulo_list_claro\">\n";
            $html .= "         <input class=\"input-submit\" type=\"button\" value=\"RECLAMA PACIENTE\" onclick=\"xajax_PacienteReclama(document.FormaPintarEntrega.observar.value,'".$formula_id."',document.FormaPintarEntrega.observacion2.value,'".$todo_pendiente."')\" class=\"label_error\">\n";
            $html .= "      </td>\n";
          
            $html .= "  </tr>\n";
            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= "<table align=\"center\">\n";
            $html .= "<br>";
            $html .= "  <tr>\n";
            $html .= "      <td align=\"center\" class=\"label_error\">\n";
            $html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
            $html .= "      </td>\n";
            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= "</form> ";
            $html .= ThemeCerrarTabla();
            return $html;
    	}
      
      
      
        /**
    * Funcion donde se crea la forma para visualizar que medicamentos que han estado pendientes y se van a despachar
    * @param array $action Vector de links de la aplicaion
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
  	function Forma_Preparar_Documento_DispensarPendientes($action,$empresa,$Cabecera_Formulacion,$temporales,$formula_id,$todo_pendiente)
    {
            
            $html  = ThemeAbrirTabla('ENTREGA MEDICAMENTOS ');
            $html .= "<form name=\"FormaPintarEntrega\" id=\"FormaPintarEntrega\"  method=\"post\" >\n";
            $html .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                   <tr class=\"formulacion_table_list\">\n";
            $html .= "                     <td align=\"center\">\n";
            $html .= "                        <a title='farmacia'>FARMACIA:<a>";
            $html .= "                      </td>\n";
            $html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
            $html .= "                          ".$empresa['razon']." -".$empresa['centro'];
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                         BODEGA";
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "                       ".$empresa['descripcion'];
            $html .= "                       </td>\n";
            $html .= "  </tr>\n";
            $html .= "                   <tr class=\"formulacion_table_list\">\n";
            $html .= "                     <td align=\"center\">\n";
            $html .= "                        <a title='Identificacion'>IDENTIFICACION:<a>";
            $html .= "                      </td>\n";
            $html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
            $html .= "                          ".$Cabecera_Formulacion['tipo_id_paciente']." ".$Cabecera_Formulacion['paciente_id'];
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                         PACIENTE ";
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "                        ".$Cabecera_Formulacion['nombre_paciente'];
            $html .= "                       </td>\n";
            $html .= "  </tr>\n";
            $html .= "	</table><br>\n";
           
            if(!empty($temporales))
            {
              $html .= "  <table width=\"85%\" class=\"modulo_table_list\"   align=\"center\">";
              $html .= "	  <tr class=\"formulacion_table_list\" >\n";
              $html .= "      <td width=\"10%\" >CODIGO</td>\n";
              $html .= "      <td width=\"65%\">PRODUCTO</td>\n";
              $html .= "      <td width=\"10%\">FECHA VEC</td>\n";
              $html .= "      <td width=\"10%\">LOTE</td>\n";
              $html .= "      <td width=\"7%\">ENTREGA</td>\n";
              $html .= "  </tr>\n";
              foreach($temporales as $k1 => $dt1)
              {
                      
                  $html .= "  <tr class=\"modulo_list_claro\" >\n";
                  $html .= "      <td align=\"left\"><b>".$dt1['codigo_producto']."</b></td>\n";
                  $html .= "      <td align=\"left\"><b>".$dt1['descripcion_prod']."</b></td>\n";
                  $html .= "      <td align=\"left\"><b>".$dt1['fecha_vencimiento']."</b></td>\n";
                  $html .= "      <td align=\"left\"><b>".$dt1['lote']."</b></td>\n";
                  $html .= "      <td align=\"left\"><b>".$dt1['cantidad_despachada']." </b></td>\n";
                  $html .= "  </tr>\n" ;
                            
              }
              $html .= "	</table><br>\n";
            }else
            {
                    $html .= "  <table width=\"85%\" class=\"modulo_table_list\"   align=\"center\">";
                    $html .= "	  <tr  align=\"center\" class=\"label_error\" >\n";
                    $html .= "      <td width=\"20%\" ><U><I>TODOS LOS PRODUCTOS DE LA FORMULA PENDIENTES POR DESPACHAR </I></U></td>\n";
                    $html .= "  </tr>\n";
                    $html .= "	</table><br>\n";
                   
            }
            $html .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                     <tr >\n";
            $html .= "                        <td rowspan='1' colspan='10' align=\"center\" class=\"modulo_list_claro\"> \n";
            $html .= "                          <fieldset>";
            $html .= "                           <legend>OBSERVACIONES</legend>";
            $html .= "                              <TEXTAREA id='observar' name='observar' ROWS='3' COLS=100></TEXTAREA>\n";
            $html .= " <input type=\"hidden\" name=\"observacion2\" value=\" Formula No: ".$Cabecera_Formulacion['tmp_formula_papel']."  Paciente:".$Cabecera_Formulacion['tipo_id_paciente']." ".$Cabecera_Formulacion['paciente_id']." ".$Cabecera_Formulacion['nombre_paciente']."\" > ";
            $html .= "                        </td>\n";
            $html .= "                     </tr>\n";
            $html .= "</table>\n";
            $html .= "<table width=\"85%\" align=\"center\">\n";
            $html .= "  <tr>\n";
            $html .= "      <td align=\"center\"class=\"modulo_list_claro\">\n";
            $html .= "         <input class=\"input-submit\" type=\"button\" value=\"RECLAMA PACIENTE\" onclick=\"xajax_PacienteReclama_P(document.FormaPintarEntrega.observar.value,'".$formula_id."',document.FormaPintarEntrega.observacion2.value,'".$todo_pendiente."')\" class=\"label_error\">\n";
            $html .= "      </td>\n";
          
            $html .= "  </tr>\n";
            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= "<table align=\"center\">\n";
            $html .= "<br>";
            $html .= "  <tr>\n";
            $html .= "      <td align=\"center\" class=\"label_error\">\n";
            $html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
            $html .= "      </td>\n";
            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= "</form> ";
            $html .= ThemeCerrarTabla();
            return $html;
    	}
      /**
		* Funcion donde se crea  el ultimo proceso de entregar los medicamentos    
		* @param array $action Vector de links de la aplicaion
		* @return string $html retorna la cadena con el codigo html de la pagina
    */
		
		function FormaPintarUltimoPaso($action,$formula_id,$pendientes,$todo_pendiente,$opcionP,$medi_form,$Cabecera_Formulacion,$dix_r)
   	{
            $html  .= ThemeAbrirTabla('MENSAJE DE ENTREGA DE MEDICAMENTO');
           if($opcionP=='1')
           {
        $html .= "<table border=\"0\" width=\"98%\" align=\"center\" >\n";
        $html .= "	<tr>\n";
        $html .= "		<td>\n";
        $html .= "			<fieldset class=\"fieldset\">\n";
        $html .= "				<legend class=\"normal_10AN\">INFORMACION DE LA FORMULA</legend>\n";
        $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
        $html .= "								<tr >\n";
        $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE REGISTRO</td>\n";
        $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_registro']."\n";
        $html .= "									</td>\n";
        $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE FORMULA</td>\n";
        $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_formula']."\n";
        $html .= "									</td>\n";
        $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FORMULA No</td>\n";
        $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['formula_papel']."\n";
        $html .= "									</td>\n";
        $html .= "									<td  class=\"formulacion_table_list\" >HORA</td>\n";
        $html .= "									<td >".$Cabecera_Formulacion['hora_formula']."\n";
        $html .= "									</td> \n";
				$html .= "								</tr>\n";
        $html .= "							</table>\n";
        
           $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
      $html .= "								<tr >\n";
      $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO FORMULA</td>\n";
      $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['descripcion_tipo_formula']."\n";
      $html .= "									</td>\n";
      $html .= "								</tr>\n";
      $html .= "								</tr>\n";
      $html .= "							</table>\n";
        
				$html .= "							<table width=\"98%\" class=\"label\" $style>\n";
				$html .= "								<tr >\n";
				$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
				$html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;&nbsp; ".$Cabecera_Formulacion['profesional']."&nbsp;&nbsp; ".$Cabecera_Formulacion['descripcion_profesional']."&nbsp;&nbsp;\n";
				$html .= "									</td>\n";
				$html .= "								</tr>\n";
				$html .= "							</table>\n";
					
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "						<td>\n";
        $html .= "					</tr>\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "							<table  width=\"98%\" class=\"label\" $style>\n";
        $html .= "								<tr>\n";
        $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
        $html .= "									<td colspan=\"3\">\n";
        $html .= "										".$request['tipo_id_paciente']." ".$request['paciente_id']."\n";
        $html .= "									</td>\n";
        $html .= "									<td  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">NOMBRE COMPLETO</td>\n";
        $html .= "									<td  > ".$Cabecera_Formulacion['nombre_paciente']."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
			
        if($Cabecera_Formulacion['sexo_id']=='M')
        {
            $sexo='MASCULINO';
			
        }else
        {
            $sexo='FEMENINO';
        }
        list($anio,$mes,$dias) = explode(":",$Cabecera_Formulacion['edad']);
				if($anio!=0)
        {
          $edad_t='AÑOS';
          $edad=$anio;
        }
        if($anio==0 and $mes!=0)
        {
          $edad_t='MES';
          $edad=$mes;
        }
        else
        {
		        if($anio==0 and $mes==0)
				{
          $edad_t='DIAS';
				  $edad=$dias;
				}
			
			}	
        
        $html .= "								<tr>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">EDAD</td>\n";
        $html .= "									<td >".$edad." &nbsp; $edad_t \n";
        $html .= "									</td>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
        $html .= "									<td align=\"left\">".$sexo."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";	
   
        $html .= "							</table>\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "						<td>\n";
        $html .= "					</tr>\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "							<table   width=\"98%\" class=\"label\" $style>\n";
        $html .= "								<tr>\n";
        $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">DIAGNOSTICO</td>\n";
        $html .= "									<td  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">DESCRIPCION</td>\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        foreach($dix_r as $key => $dtl)
        {
              $html .= "					<tr>\n";
              $html .= "									<td  colspan=\"1\" >".$dtl['diagnostico_id']."</td>\n";
              $html .= "									<td  colspan=\"3\" >".$dtl['diagnostico_nombre']."</td>\n";
              $html .= "								</tr>\n";
        }
        $html .= "							</table>\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "						<td>\n";
        $html .= "					</tr>\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "							<table   width=\"98%\" class=\"label\" $style>\n";
        $html .= "								<tr>\n";
        $html .= "									<td  colspan=\"8\"  class=\"formulacion_table_list\">MEDICAMENTOS  SOLICITADOS</td>\n";
        $html .= "								</tr>\n";
        $html .= "								<tr>\n";
        $html .= "									<td  colspan=\"2\"  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CODIGO</td>\n";
        $html .= "									<td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTO</td>\n";
        $html .= "									<td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">PRINCIPIO ACTIVO</td>\n";
        $html .= "									<td    width=\"10%\"  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">OP</td>\n";
				$html .= "									</td>\n";
				$html .= "</tr>";
				for($i=0;$i<sizeof($medi_form);$i++)
				{
						
						$html .= "<tr >";
						$html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['codigo_producto']." </td>";
						$html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['descripcion_prod']." ".$medi_form[$i]['concentracion_forma_farmacologica']." </td>";
						$html .= "  <td colspan=\"2\" align=\"center\" width=\"43%\">".$medi_form[$i]['principio_activo']."</td>";
					  if($medi_form[$i]['sw_marcado']=='1')
						{
								$html .= "		<td  width=\"10%\"  align=\"center\" class=\"label_error\" >MARCADO\n";
								$html .= "		</td>\n";
						}else
						{
              $html .= "		<td  width=\"10%\"  align=\"center\"  >\n";
              $html .= "		</td>\n";
            }
              $html .= "</tr>";
              $html .= "  <tr >";
              $html .= "    <td colspan =\"8\">";
              $html .= "      <table class=\"label\" $style >";
              $html .= "<tr >";
              $html .= "  <td   >Cantidad: ".round($medi_form[$i]['cantidad'])."</td>";
              $html .= "</tr>";
              $tiempo_tratamiento=$medi_form[$i]['tiempo_tratamiento'];
						  $unidad_tiempo_tratamiento=$medi_form[$i]['unidad_tiempo_tratamiento'];
              $html .= "<tr >";
							$html .= "  <td align=\"left\" width=\"9%\">Tiempo Entrega:".$tiempo_tratamiento." DIA(S)</td>";
							$html .= "</tr>";
							$html .= "      </table>";
							$html .= "    </td>";
							$html .= "  </tr>";
				}	
          $html .= "							</table>\n";
          $html .= "						</td>\n";
          $html .= "					</tr>\n";
          $html .= "				</table>\n";
          $html .= "			</fieldset>\n";
          $html .= "		</td>\n";
          $html .= "	</tr>\n";
          $html .= "</table>\n";
            
     }
            
           
            $html .= "<form name=\"FormaPintarEntrega2\" id=\"FormaPintarEntrega2\"  method=\"post\" >\n";
            $html .= "                 <table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                     <tr class=\"modulo_table_list_title\"  >\n";
            $html .= "                        <td  class=\"formulacion_table_list\"  colspan='10' align=\"center\"><b> \n";
            $html .= "                           SE REALIZO LA ENTREGA DE LOS MEDICAMENTOS  ";
            $html .= "                        </b></td>\n";
            $html .= "                     </tr>\n";
            $html .= "	</table><br>\n";
            $html .= " <script>
                        document.oncontextmenu = function(){return false}
                       </script> ";
            $html .= "<table align=\"center\" width=\"50%\">\n";
            $html .= "  <tr class=\"modulo_table_list\">\n";
            
            $reporte2 = new GetReports();
            $mostrar_P = $reporte2->GetJavaReport('app','Formulacion_Externa','MedicamentoDispensadosFormulacionExterna',
                                array("formula_id"=>$formula_id,"paciente_id"=>$paciente_id),
                                array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
            $funcion = $reporte2->GetJavaFunction();
            $html .= "				".$mostrar_P."\n";
            if($todo_pendiente!='1')
            {
                  $html .= " <td align=\"center\" width=\"33%\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> <b>IMPRIMIR PDF DISPENSADOS</b></a></td>";
            }
            if(!empty($pendientes))
            {
              $mostrar_ = $reporte2->GetJavaReport('app','Formulacion_Externa','MedicamentoPendienteFormulacionExterna',
                                array("formula_id"=>$formula_id),
                                array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          
                $funcion2 = $reporte2->GetJavaFunction();
                $html .= "				".$mostrar_."\n";
                $html .= " <td align=\"center\" width=\"33%\"><a href=\"javascript:$funcion2\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> <b>IMPRIMIR PDF PENDIENTES</b></a></td>";
             }
            $html .= "  </tr>\n";
            $html .= "</table>\n";
       
            $html .= "<br>";
            $html .= "<table align=\"center\">\n";
            $html .= "<br>";
            $html .= "  <tr>\n";
            $html .= "      <td align=\"center\" class=\"label_error\">\n";
            $html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
            $html .= "      </td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> \n";
            $html .= "</table>\n";
            $html .= "</form> ";
            $html .= ThemeCerrarTabla();
            return $html;
      }
      
      /**  
		* Funcion donde se muestran los productos pendientes por dispensar
   	* @param array $action Vector de links de la aplicaion
		* @return string $html retorna la cadena con el codigo html de la pagina
		*/
    function FormaFomulaPaciente_P($action,$request,$datos,$paciente,$Cabecera_Formulacion,$request,$Datos_Ad,$opcion,$dix_r,$medi_form,$formula_id,$empresa)                           
		{			
          $ctl = AutoCarga::factory("ClaseUtil");
      
            $html .= $ctl->IsDate("-");
            $html .= $ctl->AcceptDate("-");
            $html .= $ctl->IsNumeric();
            $html .= $ctl->AcceptNum(false);
         
          $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
          $html  = $ctl->RollOverFilas();
          $html .= " <script> \n";
              $html .= "	function acceptNum(evt)\n";
            $html .= "	{\n";
            $html .= "		var nav4 = window.Event ? true : false;\n";
            $html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
            $html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
            $html .= "	}\n";
          $html .= " function recogerTeclaBus(evt) ";
          $html .= " {";
          $html .= "var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   ";
          $html .= "var keyChar = String.fromCharCode(keyCode);";
          $html .= "if(keyCode==13)";
          $html .= "{   ";
          $html .= "   xajax_BuscarProducto2(xajax.getFormValues('buscador'),'".$formula_id."',1); ";
          $html .= "}";
          $html .= " }   ";
          $html .= "	function ValidarCantidad(campo,valor,cant_sol,capa)\n";
          $html .= "	{\n";
       
          $html .= "		document.getElementById(campo).style.background='';\n";
          $html .= "		document.getElementById('error').innerHTML='';\n";
          $html .= "		if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
          $html .= "		{\n";
          $html .= "			document.getElementById(campo).value='';\n";
          $html .= "			document.getElementById(campo).style.background='#ff9595';\n";
          $html .= "			document.getElementById('error').innerHTML='<center>CANTIDAD NO VALIDA</center>';\n";
          $html .= "			document.getElementById(capa).style.display=\"none\"\n";
          $html .= "		}\n";
          $html .= "		else{\n";
          $html .= "			document.getElementById(capa).style.display=\"\"\n";
          $html .= "		}\n";
          $html .= "	}\n";
          $html .= "	function Recargar_informacion(bodega_otra)\n";
          $html .= "	{\n";
          $html .= " document.buscador.bodega.value=bodega_otra; ";
          $html .= "  xajax_BuscarProducto2(xajax.getFormValues('buscador'),'".$formula_id."',1);  ";
          $html .= " }";
          $html .=" </script>\n";

          $html .= ThemeAbrirTabla('FORMULA MEDICA  COMPLETA ');
          $html .= "<table border=\"0\" width=\"98%\" align=\"center\" >\n";
          $html .= "	<tr>\n";
          $html .= "		<td>\n";
          $html .= "			<fieldset class=\"fieldset\">\n";
          $html .= "				<legend class=\"normal_10AN\">INFORMACION DE LA FORMULA</legend>\n";
          $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
          $html .= "					<tr>\n";
          $html .= "						<td align=\"center\">\n";
          $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
          $html .= "								<tr >\n";
          $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE REGISTRO</td>\n";
          $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_registro']."\n";
          $html .= "									</td>\n";
          $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE FORMULA</td>\n";
          $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_formula']."\n";
          $html .= "									</td>\n";
          $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FORMULA No</td>\n";
          $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['formula_papel']."\n";
          $html .= "									</td>\n";
          $html .= "									<td  class=\"formulacion_table_list\" >HORA</td>\n";
          $html .= "									<td >".$Cabecera_Formulacion['hora_formula']."\n";
          $html .= "									</td> \n";
          $html .= "								</tr>\n";
          $html .= "							</table>\n";
          
          $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
          $html .= "								<tr >\n";
          $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO FORMULA</td>\n";
          $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['descripcion_tipo_formula']."\n";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          $html .= "								</tr>\n";
          $html .= "							</table>\n";
        
          
          
          
          
          $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
          $html .= "								<tr >\n";
          $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
          $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['tipo_id_tercero']."".$Cabecera_Formulacion['tercero_id']."&nbsp;&nbsp;".$Cabecera_Formulacion['profesional']." &nbsp;&nbsp;-".$Cabecera_Formulacion['descripcion_profesional']."\n";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          $html .= "							</table>\n";
		
          $html .= "					<tr>\n";
          $html .= "						<td align=\"center\">\n";
          $html .= "							<table  width=\"98%\" class=\"label\" $style>\n";
          $html .= "								<tr>\n";
          $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
          $html .= "									<td colspan=\"3\">\n";
          $html .= "										".$request['tipo_id_paciente']." ".$request['paciente_id']."\n";
          $html .= "									</td>\n";
          $html .= "									<td  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">NOMBRE COMPLETO</td>\n";
          $html .= "									<td  > ".$Cabecera_Formulacion['nombre_paciente']."\n";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          if($Cabecera_Formulacion['sexo_id']=='M')
          {
              $sexo='MASCULINO';
          }else
          {
              $sexo='FEMENINO';
          }
          list($anio,$mes,$dias) = explode(":",$Cabecera_Formulacion['edad']);
					if($anio!=0)
          {
               $edad_t='AÑOS';
               $edad=$anio;
          }
          if($anio==0 and $mes!=0)
          {
            $edad_t='MES';
             $edad=$mes;
          }
          else
          {
              if($anio==0 and $mes==0)
              {
                $edad_t='DIAS';
                $edad=$dias;
              }
        
          }	
			
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">EDAD</td>\n";
			$html .= "									<td >".$edad." &nbsp; $edad_t \n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
			$html .= "									<td align=\"left\">".$sexo."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";	
			
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO PLAN</td>\n";
			$html .= "									<td >".$Datos_Ad['tipo_plan']."\n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO VINCULACION</td>\n";
			$html .= "									<td >".$Datos_Ad['vinculacion']."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";	
      $html .= "							</table>\n";
      $html .= "					<tr>\n";
      $html .= "						<td align=\"center\">\n";
      $html .= "						<td>\n";
      $html .= "					</tr>\n";
      $html .= "					<tr>\n";
      $html .= "						<td align=\"center\">\n";
      $html .= "						<td>\n";
      $html .= "					</tr>\n";
      $html .= "					<tr>\n";
      $html .= "						<td align=\"center\">\n";
      $html .= "							<table   width=\"98%\" class=\"label\" $style>\n";
      $html .= "								<tr>\n";
      $html .= "									<td  colspan=\"8\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTOS  SOLICITADOS</td>\n";
      $html .= "								</tr>\n";
      $html .= "								<tr>\n";
      $html .= "									<td  colspan=\"2\"  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CODIGO</td>\n";
      $html .= "									<td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTO</td>\n";
      $html .= "									<td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CANTIDAD</td>\n";
	    $html .= "									</td>\n";
      $html .= "</tr>";
      $est = " "; $back = " ";
			for($i=0;$i<sizeof($medi_form);$i++)
      {
            $html .= "  <tr   ".$est." onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
            $html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['codigo_medicamento']." </td>";
            $html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['descripcion_prod']." </td>";
            $html .= "  <td colspan=\"2\" align=\"center\" width=\"43%\">".$medi_form[$i]['total']."</td>";
            $html .= "</tr>";
        }	
				$html .= "							</table>\n";
				$html .= "					</tr>\n";
				$html .= "					<tr>\n";
				$html .= "						<td align=\"center\">\n";
				$html .= "						</td>\n";
				$html .= "					</tr>\n";
				$html .= "				</table>\n";
				$html .= "			</fieldset>\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table>\n";
        $html .=  "<form name=\"buscador\" id=\"buscador\" action=\"\" method=\"post\">\n";
        $html .= "  <div id='error_doc' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $html .= "  <div id=\"ventana1\">\n";
        $html .= "   <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "     <tr >\n";
        $html .= "        <td class=\"formulacion_table_list\" colspan=\"6\">BUSCADOR</td>";
        $html .= "     </tr>\n";
        $html .= "     <tr class=\"formulacion_table_list\">\n";
        $html .= "        <td  align=\"left\">CODIGO BARRAS</td>\n";
        $html .= "       <td class=\"modulo_list_claro\" >";
   
         $html .= "                      <input type=\"hidden\" id=\"empresa_id\" name=\"empresa_id\" value=\"".$empresa['empresa_id']."\">\n";
        $html .= "                      <input type=\"hidden\" id=\"centro_utilidad\" name=\"centro_utilidad\" value=\"".$empresa['centro_utilidad']."\">\n";
        $html .= "                       <input type=\"hidden\" id=\"bodega\" name=\"bodega\" value=\"".$empresa['bodega']."\">\n";
        $html .= "        <input type=\"text\" name=\"codigo_barras\" id=\"codigo_barras\" class=\"input-text\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event)\">";
        $html .= "        </td>\n";
        $html .= "       <td  align=\"left\">DESCRIPCION</td>\n";
        $html .= "        <td class=\"modulo_list_claro\" >";
        $html .= "       <input type=\"text\" name=\"descripcion\" id=\"descripcion\" readonly=\"true\" class=\"input-text\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event)\">";
        $html .= "      </td>\n";
        $html .= "       <td  align=\"left\">LOTE</td>\n";
        $html .= "       <td class=\"modulo_list_claro\" >";
        $html .= "        <input type=\"text\" name=\"lote\" id=\"lote\" class=\"input-text\"  readonly=\"true\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event)\">";
        $html .= "       </td>\n";
        $html .= "     </tr>\n";
        $html .= "   </table>";
        $html .= "  </div>";
        $html .= "</form>\n";
        $colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
        $colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');

        $html .= "               <table class=\"modulo_table_list\" width=\"35%\" align=\"center\">";
        $html .= "               <td  class=\"label\" style=\"background:".$colores['PV']."\" width=\"50%\" align=\"center\">";
        $html .= "                  PROD. PROXIMO A VENCER";
        $html .= "                  </td>";
        $html .= "                 <td  class=\"label\" style=\"background:".$colores['VN']."\" width=\"50%\" align=\"center\">";
        $html .= "                  PROD. VENCIDO";
        $html .= "                  </td>";
        $html .= "                 </table>";
        $html .= "               <br>";
        $html .= "  <div id=\"BuscadorProductos\"></div><br>\n";
        $html .= "  <div id='productostmp'></div>\n";
        $html.= " <script>" ;
     $html.= " xajax_MostrarProductox2('".$formula_id."'); ";
        $html.= " </script>" ;
        $html.= " <br>";
        
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
		}
      
      
     /*
		* Forma para visualizar la formula real 
		* @return boolean
		*/
    function FormaCabeceraFormulaCompleta_ambu($action,$Cabecera_Formulacion,$Cabecera_Formulacion_AESM,$request,$Cabecera_Formulacion_AEM,$Datos_Fueza,$Datos_Ad,$ESM_pac,$ESM_,$opcion,$IPS_,$dix_r,$medi_form,$permisos)
    {		
        $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
				$obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
		    $html .= ThemeAbrirTabla('FORMULA MEDICA  COMPLETA ');
        $html .= "<table border=\"0\" width=\"98%\" align=\"center\" >\n";
        $html .= "	<tr>\n";
        $html .= "		<td>\n";
        $html .= "			<fieldset class=\"fieldset\">\n";
        $html .= "				<legend class=\"normal_10AN\">INFORMACION DE LA FORMULA</legend>\n";
        $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
        $html .= "								<tr >\n";
        $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE REGISTRO</td>\n";
        $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_registro']."\n";
        $html .= "									</td>\n";
        $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE FORMULA</td>\n";
        $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_formula']."\n";
        $html .= "									</td>\n";
        $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FORMULA No</td>\n";
        $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['formula_papel']."\n";
        $html .= "									</td>\n";
        $html .= "									<td  class=\"formulacion_table_list\" >HORA</td>\n";
        $html .= "									<td >".$Cabecera_Formulacion['hora_formula']."\n";
        $html .= "									</td> \n";
				$html .= "								</tr>\n";
        $html .= "							</table>\n";
				$html .= "							<table width=\"98%\" class=\"label\" $style>\n";
				$html .= "								<tr >\n";
				$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
				$html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;&nbsp; ".$Cabecera_Formulacion['profesional']."&nbsp;&nbsp; ".$Cabecera_Formulacion['descripcion_profesional']."&nbsp;&nbsp;\n";
				$html .= "									</td>\n";
				$html .= "								</tr>\n";
				$html .= "							</table>\n";
					
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "						<td>\n";
        $html .= "					</tr>\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "							<table  width=\"98%\" class=\"label\" $style>\n";
        $html .= "								<tr>\n";
        $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
        $html .= "									<td colspan=\"3\">\n";
        $html .= "										".$request['tipo_id_paciente']." ".$request['paciente_id']."\n";
        $html .= "									</td>\n";
        $html .= "									<td  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">NOMBRE COMPLETO</td>\n";
        $html .= "									<td  > ".$Cabecera_Formulacion['nombre_paciente']."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
			
        if($Cabecera_Formulacion['sexo_id']=='M')
        {
            $sexo='MASCULINO';
			
        }else
        {
            $sexo='FEMENINO';
        }
        list($anio,$mes,$dias) = explode(":",$Cabecera_Formulacion['edad']);
				if($anio!=0)
        {
          $edad_t='AÑOS';
          $edad=$anio;
        }
        if($anio==0 and $mes!=0)
        {
          $edad_t='MES';
          $edad=$mes;
        }
        else
        {
		        if($anio==0 and $mes==0)
				{
          $edad_t='DIAS';
				  $edad=$dias;
				}
			
			}	
        
        $html .= "								<tr>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">EDAD</td>\n";
        $html .= "									<td >".$edad." &nbsp; $edad_t \n";
        $html .= "									</td>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
        $html .= "									<td align=\"left\">".$sexo."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";	
        $html .= "								<tr>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO PLAN</td>\n";
        $html .= "									<td >".$Datos_Ad['tipo_plan']."\n";
        $html .= "									</td>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO VINCULACION</td>\n";
        $html .= "									<td >".$Datos_Ad['vinculacion']."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";	
        $html .= "							</table>\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "						<td>\n";
        $html .= "					</tr>\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "							<table   width=\"98%\" class=\"label\" $style>\n";
        $html .= "								<tr>\n";
        $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">DIAGNOSTICO</td>\n";
        $html .= "									<td  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">DESCRIPCION</td>\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        foreach($dix_r as $key => $dtl)
        {
              $html .= "					<tr>\n";
              $html .= "									<td  colspan=\"1\" >".$dtl['diagnostico_id']."</td>\n";
              $html .= "									<td  colspan=\"3\" >".$dtl['diagnostico_nombre']."</td>\n";
              $html .= "								</tr>\n";
        }
        $html .= "							</table>\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "						<td>\n";
        $html .= "					</tr>\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "							<table   width=\"98%\" class=\"label\" $style>\n";
        $html .= "								<tr>\n";
        $html .= "									<td  colspan=\"8\"  class=\"formulacion_table_list\">MEDICAMENTOS  SOLICITADOS</td>\n";
        $html .= "								</tr>\n";
        $html .= "								<tr>\n";
        $html .= "									<td  colspan=\"2\"  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CODIGO</td>\n";
        $html .= "									<td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTO</td>\n";
        $html .= "									<td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">PRINCIPIO ACTIVO</td>\n";
        $html .= "									<td    width=\"10%\"  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">OP</td>\n";
				$html .= "									</td>\n";
				$html .= "</tr>";
				for($i=0;$i<sizeof($medi_form);$i++)
				{
						
						$html .= "<tr >";
						$html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['codigo_producto']." </td>";
						$html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['descripcion_prod']." ".$medi_form[$i]['concentracion_forma_farmacologica']." </td>";
						$html .= "  <td colspan=\"2\" align=\"center\" width=\"43%\">".$medi_form[$i]['principio_activo']."</td>";
					  if($medi_form[$i]['sw_marcado']=='1')
						{
								$html .= "		<td  width=\"10%\"  align=\"center\" class=\"label_error\" >MARCADO\n";
								$html .= "		</td>\n";
						}else
						{
              $html .= "		<td  width=\"10%\"  align=\"center\"  >\n";
              $html .= "		</td>\n";
            }
              $html .= "</tr>";
              $html .= "  <tr >";
              $html .= "    <td colspan =\"8\">";
              $html .= "      <table class=\"label\" $style >";
              $html .= "<tr >";
              $html .= "  <td   >Cantidad: ".round($medi_form[$i]['cantidad'])."</td>";
              $html .= "</tr>";
              $tiempo_tratamiento=$medi_form[$i]['tiempo_tratamiento'];
						  $unidad_tiempo_tratamiento=$medi_form[$i]['unidad_tiempo_tratamiento'];
              $html .= "<tr >";
							$html .= "  <td align=\"left\" width=\"9%\">Tiempo Entrega:".$tiempo_tratamiento." DIA(S)</td>";
							$html .= "</tr>";
							$html .= "      </table>";
							$html .= "    </td>";
							$html .= "  </tr>";
				}	
          $html .= "							</table>\n";
          $html .= "						</td>\n";
          $html .= "					</tr>\n";
          $html .= "				</table>\n";
          $html .= "			</fieldset>\n";
          $html .= "		</td>\n";
          $html .= "	</tr>\n";
          $html .= "</table>\n";
          $html .= "  <table border=\"-1\" width=\"15%\" align=\"center\" >\n";
          $html .= "	  <tr>\n";
          $html .= "		<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
          $html .= "		  <td align=\"center\"><br>\n";
          $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"VOLVER\">\n";
          $html .= "		  </td>";
          $html .= "		</form>\n";
          $html .= "		<form name=\"forma\" action=\"".$action['anular']."\" method=\"post\">\n";
          $html .= "		  <td align=\"center\"><br>\n";
          $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"ANULAR FORMULA\">\n";
          $html .= "		  </td>";
          $html .= "		</form>\n";
          if(!empty($permisos))
          {
              $html .= "		<form name=\"forma\" action=\"".$action['cambiar_modulo']."\" method=\"post\">\n";
              $html .= "		  <td align=\"center\"><br>\n";
              $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"DISPENSACION\">\n";
              $html .= "		  </td>";
              $html .= "		</form>\n";
      
            }
              $html .= "	</tr>";
              $html .= "</table>";
              $html .= ThemeCerrarTabla();	
			return $html;
		}
		
    /*
		* Forma para Anular la formula real 
		* @return boolean
		*/
    function FormaFormulaAnulada($action,$formula_papel_,$msn,$request)
		{
    
          $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
     			$html  = ThemeAbrirTabla("FORMULA  A  ANULAR  ");
          $html  .= "<script>\n"; 
          $html .= "  function evaluarDatosObligatorios(objeto)\n"; 
          $html .= "  {\n"; 
          $html .= "    if(objeto.observacion.value == \"\")\n";
          $html .= "    {\n"; 
          $html .= "        document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE INGRESAR UNA OBSERVACION \";\n"; 
          $html .= "        return true;\n"; 
          $html .= "      }\n"; 
          $html .= "    objeto.submit();\n";
          $html .= "  }\n";
          $html .= "</script>\n"; 
          $html .= "<form name=\"anular_formula\" id=\"anular_formula\"   action=\"".$action['continuar']."\"   method=\"post\">\n";
          $html .= "		<center>\n";
          $html .= "			<label id=\"error_actividad\" class=\"label_error\"></label>\n";
          $html .= "	  </center>\n";	
          $today=date('d-M-Y');
          $html .= "							<table  align=\"right\" width=\"30%\" class=\"label\" $style >\n";
          $html .= "								<tr >\n";
          $html .= "									<td   class=\"formulacion_table_list\" >FECHA ACTUAL</td>\n";
          $html .= "									<td  align=\"right\" > $today";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          $html .= "							</table> <br>\n";

          $html .= "<table border=\"0\" width=\"98%\" align=\"center\" >\n";
          $html .= "	<tr>\n";
          $html .= "		<td>\n";
          $html .= "			<fieldset class=\"fieldset\">\n";
          $html .= "				<legend class=\"normal_10AN\">INFORMACION DE LA FORMULA</legend>\n";
          $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
          $html .= "					<tr>\n";
          $html .= "						<td align=\"center\">\n";
          $html .= "							<table width=\"60%\" class=\"label\" $style>\n";
          $html .= "								<tr >\n";
          $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FORMULA NO</td>\n";
          $html .= "									<td width=\"50%\" align=\"center\">\n";
          $html .= "									".$formula_papel_['formula_papel']."	 ";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          $html .= "							</table>\n";
          $html .= "							<table width=\"80%\" class=\"label\" $style>\n";
          $html .= "								<tr >\n";
          $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >OBSERVACION</td>\n";
          $html .= "									<td >\n";
          $html .= "     <textarea style = \"width:100%\" class=\"textarea\" name = \"observacion\" rows=\"3\">".$request['observacion']."";       
          $html .= "</textarea></td> \n";
          $html .= "								</tr>\n";
          $html .= "							</table>\n";
          $html .= "		  </td>";
          $html .= "								</tr>\n";
          $html .= "							</table></fieldset>\n";
           $html .= "		  </td>";
          $html .= "								</tr>\n";
          $html .= "							</table>\n";
          
          $html .= "  <table border=\"-1\" width=\"25%\" align=\"center\" >\n";
          $html .= "	  <tr>\n";
          if($msn!="")
          {
              $html .= "	  <tr>\n";
              $html .= "		  <td class=\"formulacion_table_list\"  align=\"center\">\n";
              $html .= "		  ".$msn."</td>";
              $html .= "	</tr>";
          }
          $html .= "	  <tr>\n";
          $html .= "		  <td align=\"center\"><br>\n";
          $html .= "			  <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"ANULAR FORMULA\"   onclick=\"evaluarDatosObligatorios(document.anular_formula)\" >\n";
          $html .= "		  </td>";
          $html .= "		</form>\n";
          $html .= "	</tr>";
          $html .= "</table> </body>";
          $html .= "<table align=\"center\">\n";
          $html .= "<br>";
          $html .= "  <tr>\n";
          $html .= "      <td align=\"center\" class=\"label_error\">\n";
          $html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
          $html .= "      </td>\n";
          $html .= "  </tr>\n";
          $html .= "</table>\n";
          $html .= ThemeCerrarTabla();
          return $html;
		}


 		// CREAR LA CAPITA
	  function CrearVentana($tmn,$Titulo)
    {
			$html .= "<script>\n";
			$html .= "  var contenedor = 'Contenedor';\n";
			$html .= "  var titulo = 'titulo';\n";
			$html .= "  var hiZ = 4;\n";
			$html .= "  function OcultarSpan()\n";
			$html .= "  { \n";
			$html .= "    try\n";
			$html .= "    {\n";
			$html .= "      e = xGetElementById('Contenedor');\n";
			$html .= "      e.style.display = \"none\";\n";
			$html .= "    }\n";
			$html .= "    catch(error){}\n";
			$html .= "  }\n";
			$html .= "  function MostrarSpan()\n";
			$html .= "  { \n";
			$html .= "    try\n";
			$html .= "    {\n";
			$html .= "      e = xGetElementById('Contenedor');\n";
			$html .= "      e.style.display = \"\";\n";
			$html .= "      Iniciar();\n";
			$html .= "    }\n";
			$html .= "    catch(error){alert(error)}\n";
			$html .= "  }\n";

			$html .= "  function MostrarTitle(Seccion)\n";
			$html .= "  {\n";
			$html .= "    xShow(Seccion);\n";
			$html .= "  }\n";
			$html .= "  function OcultarTitle(Seccion)\n";
			$html .= "  {\n";
			$html .= "    xHide(Seccion);\n";
			$html .= "  }\n";

			$html .= "  function Iniciar()\n";
			$html .= "  {\n";
			$html .= "    contenedor = 'Contenedor';\n";
			$html .= "    titulo = 'titulo';\n";
			$html .= "    ele = xGetElementById('Contenido');\n";
			$html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "    ele = xGetElementById(contenedor);\n";
			$html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+10);\n";
			$html .= "    ele = xGetElementById(titulo);\n";
			$html .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
			$html .= "    xMoveTo(ele, 0, 0);\n";
			$html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "    ele = xGetElementById('cerrar');\n";
			$html .= "    xResizeTo(ele,20, 20);\n";
			$html .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
			$html .= "  }\n";

			$html .= "  function myOnDragStart(ele, mx, my)\n";
			$html .= "  {\n";
			$html .= "    window.status = '';\n";
			$html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "    else xZIndex(ele, hiZ++);\n";
			$html .= "    ele.myTotalMX = 0;\n";
			$html .= "    ele.myTotalMY = 0;\n";
			$html .= "  }\n";
			$html .= "  function myOnDrag(ele, mdx, mdy)\n";
			$html .= "  {\n";
			$html .= "    if (ele.id == titulo) {\n";
			$html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "    }\n";
			$html .= "    else {\n";
			$html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "    }  \n";
			$html .= "    ele.myTotalMX += mdx;\n";
			$html .= "    ele.myTotalMY += mdy;\n";
			$html .= "  }\n";
			$html .= "  function myOnDragEnd(ele, mx, my)\n";
			$html .= "  {\n";
			$html .= "  }\n";
			$html.= "function Cerrar(Elemento)\n";
			$html.= "{\n";
			$html.= "    capita = xGetElementById(Elemento);\n";
			$html.= "    capita.style.display = \"none\";\n";
			$html.= "}\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
			$html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "  <div id='Contenido' class='d2Content'>\n";
			$html .= "  </div>\n";
			$html .= "</div>\n";
      return $html;
    }    
     	
    /*
		* Forma para Bloquear La formula en el caso de que el numero de formula que se este digitando ya exista 
		* @return boolean
		*/
  
    function FormaBloqueo($action)
		{
			$html  = ThemeAbrirTabla("FORMULA ENCONTRADA");
			$html .= " <form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
			$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "    PARA ESTE PACIENTE YA EXISTE ESTE NUMERO DE FORMULA ";
			$html .= "      </td>\n";

			$html .= "	</table>\n";
			$html .= "  </form>\n";

			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver_a']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
		
		function Forma_PedientesReservas($action,$pendientes,$request,$conteo, $pagina)
		{
		$html  = ThemeAbrirTabla("PENDIENTES - DISPENSACION");
		$ctl = AutoCarga::factory("ClaseUtil");
		$pgn = AutoCarga::factory("ClaseHTML");
		$html .= $ctl->LimpiarCampos();
		$html .= $ctl->RollOverFilas();
		$html .= $ctl->AcceptDate('/');
		$html .= $ctl->AcceptNum(false);
		
		$html .= "	<center>";
		$html .= "		<fieldset style=\"width:50%\">";
		$html .= "			<legend class=\"normal_10AN\">BUSCADOR PENDIENTES</legend>";
		$html .= "		<form name=\"FormaBuscador\" id=\"FormaBuscador\" action=\"".$action['buscar']."\" method=\"POST\">";
		$html .= "			<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
		$html .= "				<tr  class=\"normal_10AN\">";
		$html .= "					<td width=\"50%\">";
		$html .= "						NUMERO DE FORMULA";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<input type= \"text\" name=\"buscador[formula_papel]\" id=\"formula_papel\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['formula_papel']."\">";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "				<tr  class=\"normal_10AN\">";
		$html .= "					<td width=\"50%\">";
		$html .= "						PACIENTE";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<input type= \"text\" name=\"buscador[paciente]\" id=\"paciente\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['paciente']."\">";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "				<tr  class=\"normal_10AN\">";
		$html .= "					<td>";
		$html .= "						PRODUCTO";
		$html .= "					</td>";
		$html .= "					<td>";
		$html .= "						<input type= \"text\" name=\"buscador[producto]\" id=\"producto\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['producto']."\">";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "				<tr  class=\"normal_10AN\">";
		$html .= "					<td align=\"center\">";
		$html .= "						<input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\">";
		$html .= "					</td>";
		$html .= "					<td align=\"center\">";
		$html .= "						<input type=\"reset\" value=\"LIMPIAR\" class=\"input-submit\">";
		$html .= "					</td>";
		$html .= "				</tr>";
		$html .= "			</table>";
		$html .= "		</form>";
		$html .= "		</fieldset>";
		$html .= "	</center>";
		
		$html .= "	<table width=\"50%\" class=\"modulo_table_list\" align=\"center\">";
		$html .= "		<tr>";
		$html .= "			<td style=\"width:100%;background:#00FF40;\" align=\"center\">";
		$html .= "				PRODUCTO CON EXISTENCIAS RESERVADAS";
		$html .= "			</td>";
		$html .= "		</tr>";
		$html .= "	</table>";
		
		$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
		$html .= "		<table width=\"100%\" class=\"modulo_table_list\" align=\"center\">";
		$html .= "			<tr class=\"formulacion_table_list\">";
		$html .= "				<td colspan=\"2\">";
		$html .= "					PRODUCTOS PENDIENTES POR DISPENSAR";
		$html .= "				</td>";
		$html .= "			</tr>";
		foreach($pendientes as $key=>$valor)
		{
		$html .= "			<tr class=\"modulo_table_list_title\"  >";
		$html .= "				<td width=\"10%\" align=\"left\">";
		$html .= "					PRODUCTO:";
		$html .= "				</td>";
		$html .= "				<td class=\"modulo_list_oscuro\" align=\"left\" ".$valor[0]['color'].">";
		$html .= "					".$key;
		$html .= "				</td>";
		$html .= "			</tr>";
		$html .= "			<tr class=\"modulo_table_list_title\">";
		$html .= "				<td colspan=\"2\">";
		$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
		$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
        $html .= "					<table width=\"100%\" class=\"modulo_table_list\" align=\"center\" rules=\"all\">";
		$html .= "						<tr class=\"formulacion_table_list\">";
		$html .= "							<td width=\"10%\">";
		$html .= "								FORMULA";
		$html .= "							</td>";
		$html .= "							<td width=\"50%\">";
		$html .= "								PACIENTE";
		$html .= "							</td>";
		$html .= "							<td width=\"20%\">";
		$html .= "								FECHA PENDIENTE";
		$html .= "							</td>";
		$html .= "							<td width=\"10%\">";
		$html .= "								CANTIDAD";
		$html .= "							</td>";
		$html .= "							<td width=\"5%\">";
		$html .= "								OP";
		$html .= "							</td>";
		$html .= "						</tr>";
		for($i=0;$i<count($valor);$i++)
			{
		$html .= "						<tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
		$html .= "							<td align=\"center\">";
		$html .= "								".$valor[$i]['formula_papel'];
		$html .= "							</td>";
		$html .= "							<td >";
		$html .= "								".$valor[$i]['paciente'];
		$html .= "							</td>";
		$html .= "							<td align=\"center\">";
		$html .= "								".$valor[$i]['fecha_registro'];
		$html .= "							</td>";
		$html .= "							<td align=\"center\">";
		$html .= "								".FormatoValor($valor[$i]['cantidad']);
		$html .= "							</td>";
		$html .= "							<td align=\"center\">";
		$html .= "								<a href=\"".$action['inactivar'].URLRequest(array("esm_pendiente_dispensacion_id"=>$valor[$i]['esm_pendiente_dispensacion_id'],"inactivar"=>"1"))."\" >";
		$html .= "									<img onClick=\"return confirm('Desea  Inactivar El Pendiente De : ".$key.", Del Paciente ".$valor[$i]['paciente']."?');\" src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\">\n";
		$html .= "								</a>";
		$html .= "							</td>";
		$html .= "						</tr>";
			}
		$html .= "					</table>";
		$html .= "				</td>";
		$html .= "			</tr>";
		$html .= "			<tr>";
		$html .= "				<td colspan=\"2\">";
		$html .= "					&nbsp;";
		$html .= "				</td>";
		$html .= "			</tr>";
		}
		$html .= "		</table>";
		
		$html .= "<br>";
		
		$html .= "<table width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "	<tr>\n";
		$html .= "		<td align=\"center\"><br>\n";
		$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
		$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
		$html .= "			</form>";
		$html .= "		</td>";
		$html .= "	</tr>";
		$html .= "</table>";
		$html .= ThemeCerrarTabla();
		return $html;
		}
		
		
 		/******************************************************************
		* Crea un menu principal para el modulo
		*
		* @param array $action Vector que continen los link de la aplicacion
		* @param array $permiso Vector con los datos de los permisos del usuario
		* @return string
		*******************************************************************/
		function FormaMenuInicial($action,$f_papel)
		{
			$html  = ThemeAbrirTabla('INFORMACION');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" height=\"40%\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">TICKETS</td>\n";
			$html .= "				</tr>\n";      
  			$html .= "				<tr>\n";
  			$html .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
  			$html .= "						<b>MENSAJE DE PRUEBA</b>\n";
  			$html .= "					</td>\n";
  			$html .= "				</tr>\n";
			$html .= "			</table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$volver = $action['iraPendientes']."&buscador[formula_papel]=".$f_papel;
			//$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "			<form name=\"form\" action=\"".$volver."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}		


     function Forma($action,$request,$lista,$Tipo_Id_paciente,$conteo, $pagina,$bodegas_doc_id)
     {
          $ctl = AutoCarga::factory("ClaseUtil");
       
          $html  = $ctl->LimpiarCampos();
          $html .= $ctl->RollOverFilas();
          $html .= $ctl->AcceptDate('/');
        $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
          $select = "<select style=\"width:40%\" name=\"buscador[tipo_id_paciente]\" id=\"tipo_id_paciente\" class=\"select\">";
          $select .= " <option value=\"\">--- TODOS ---</option>";
          foreach($Tipo_Id_paciente as $key=>$valor)
          {
                if($_REQUEST['buscador']['tipo_id_paciente']==$valor['tipo_id_paciente'])
                {
                    $selected =" selected ";
                }
              else
              {
                      $selected =" ";
              }
              $select .= "<option $selected value=\"".$valor['tipo_id_paciente']."\">".$valor['tipo_id_paciente']."-".$valor['descripcion']."</option>";
          }
          $select .= "</select>";
          $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:center\"";
        
          $html .= ThemeAbrirTabla('TICKETS DE DISPENSACION');
          $html .= "<form name=\"productos\" action=\"".$action['buscar']."\" method=\"post\">\n";
          $html .= "  <table width=\"65%\" align=\"center\">\n";
          $html .= "    <tr>\n";
          $html .= "      <td>\n";
          $html .= "	      <fieldset class=\"fieldset\">\n";
          $html .= "          <legend class=\"normal_10AN\">BUSQUEDA DE FORMULAS</legend>\n";
          $html .= "		      <table width=\"100%\" class=\"label\" $style>\n";
          $html .= "            <tr>";
          $html .= "              <td class=\"formulacion_table_list\"\">No- FORMULA</td>\n";
          $html .= "                <td align=\"left\" ><input type=\"text\" name=\"buscador[formula_papel]\" id=\"formula_papel\" class=\"input-text\" value=\"".$request['formula_papel']."\" style=\"width:60%\"></td>\n";
          $html .= "            </tr>";
          $html .= "            <tr>";
          $html .= "              <td class=\"formulacion_table_list\">IDENTIFICACION DEL PACIENTE</td>\n";
          $html .= "                <td align=\"left\">".$select."<input type=\"text\" name=\"buscador[paciente_id]\" id=\"paciente_id\" class=\"input-text\" value=\"".$request['paciente_id']."\" style=\"width:60%\"></td>\n";
          $html .= "            </tr>";
          $html .= "            <tr>";
          $html .= "              <td class=\"formulacion_table_list\">NOMBRE DEL PACIENTE</td>\n";
          $html .= "                <td align=\"left\" ><input type=\"text\" name=\"buscador[nombre_paciente]\" id=\"nombre_paciente\" class=\"input-text\" value=\"".$request['nombre_paciente']."\" style=\"width:60%\"></td>\n";
          $html .= "            </tr>";
          $html .= "			      <tr>\n";
          
          if(!empty($bodegas_doc_id))
          {
              $html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
              $html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
              $html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.productos)\">\n";
              $html .= "				      </td>\n";
           }else
           {
                  $html .= "				      <td class=\"label_error\" align=\"center\" colspan=\"3\">\n";
                  $html .= "				      NO EXISTE UN DOCUMENTO PARAMETRIZADO PARA REALIZAR LA DISPENSACION </td>\n";


           }
         $html .= "			      </tr>\n";
          $html .= "		      </table>\n";
          $html .= "	      </fieldset>\n";
          $html .= "	    </td>\n";
          $html .= "	  </tr>\n";
          $html .= "	</table>\n";
          $html .= "</form>\n";
          if(!empty($lista))
          {
        
              $html .= "	<table align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">\n";
              $html .= "		<tr class=\"formulacion_table_list\" >\n";
              $html .= "			<td width=\"5%\">#FORMULA</td>\n";
              $html .= "			<td width=\"5%\">FECHA FORMULA</td>\n";
              $html .= "			<td width=\"15%\">PACIENTE</td>\n";
              $html .= "			<td width=\"4%\">TICKET DISPENSADOS</td>\n";
              $html .= "			<td width=\"4%\">  TICKET PENDIENTES</td>\n";
               $html .= "			<td width=\"2%\"> PENDIENTES</td>\n";
              
              $html .= "		</tr>\n";
              $reporte = new GetReports();
              foreach($lista as $k1 => $dtl)
              {
                        $mostrar = $reporte->GetJavaReport('app','Formulacion_Externa','MedicamentoDispensadosFormulacionExterna',
                        array("formula_id"=>$dtl['formula_id'],"paciente_id"=>$dtl['paciente_id'],"opc"=>"1"),
                        array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                        $funcion = $reporte->GetJavaFunction();

                        $mostrar_ = $reporte->GetJavaReport('app','Formulacion_Externa','MedicamentoPendienteFormulacionExterna',
                        array("formula_id"=>$dtl['formula_id']),
                        array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                        $funcion2 = $reporte->GetJavaFunction();

                    	$pendientes=$obje->Medicamentos_Pendientes_Esm($dtl['formula_id']);
             
                    $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
                    $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";

                    $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
                    $html .= "			<td ><u><b>".$dtl['formula_papel']."</b></u></td>\n";
                    $html .= "			<td >".$dtl['fecha_formula']."</td>\n";
                    $html .= "			<td>(".$dtl['tipo_id_paciente']."-".$dtl['paciente_id'].")-".$dtl['nombre_paciente']."</td>\n";
                    $html .= "				<td align=\"center\" >\n";
                    $html .= "				".$mostrar."\n";
                    $html .= "					<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"TICKET DE DISPENSADOS\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' >\n";
                    $html .= "					</a></center>\n";
                    $html .= "			</td>\n";	
                    if(!empty($pendientes))
                    {
                          $html .= "				<td align=\"center\" >\n";
                          $html .= "				".$mostrar_."\n";
                          $html .= "					<a href=\"javascript:$funcion2\" class=\"label_error\"  title=\"PENDIENTES\"><img src=\"".GetThemePath()."/images/cargosin.png\" border='0' >\n";
                          $html .= "					</a></center>\n";
                          $html .= "			</td>\n";	

                          $html .= "      <td  align=\"center\" class=\"label_error\">\n";
                          $html .= "        <a href=\"".$action['pendiente'].URLRequest($dtl)."\">\n";
                          $html .= "          <img border=\"0\"  title=\"Pendientes\" src=\"".GetThemePath()."/images/pparamedin.png\">\n";
                          $html .= "        </a>\n";
						  //added 01082012 : llamadas a pacientes
						  $html .= "        <a href=\"".$action['entregas_farmacia'].URLRequest($dtl)."\">\n";
						  $html .= "          <img border=\"0\"  title=\"Entrega en Farmacia:datos adicionales\" src=\"".GetThemePath()."/images/banco.png\">\n";
                          $html .= "        </a>\n";
						  //
                          $html .= "      </td>\n";
                    }else
                    {
                          $html .= "				<td align=\"center\" >\n";
                          $html .= "					<img src=\"".GetThemePath()."/images/cargos.png\" border='0' >\n";
                          $html .= "					</center>\n";
                          $html .= "			</td>\n";
                          
                          $html .= "      <td  align=\"center\" >\n";
                          $html .= "          <img border=\"0\"  title=\"Pendientes\" src=\"".GetThemePath()."/images/pparamed.png\">\n";
                          $html .= "      </td>\n";

                    
                    
                    }
                    }
                    $html .= "		</tr>\n";
        
                    $html .= "		</table>\n";
                    $html .= "		<br>\n";
                    $pgn = AutoCarga::factory("ClaseHTML");
                    $html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
      else if(!empty($request))
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
        $html .= "</center>\n";
      }
      $html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr><td align=\"center\">\n";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
      $html .= ThemeCerrarTabla();

      return $html;
     }		
		
		                           
	 function FormaPendientesEntregaFarmacia($action,$formula_id,$Cabecera_Formulacion,$f_papel,$request,$opcion,$medi_form,$Datos_Ad,$dix_r)                        
     {
            $ctl = AutoCarga::factory("ClaseUtil");
            
            $obje = AutoCarga::factory("Formulacion_ExternaSQL", "classes", "app", "Formulacion_Externa");
            $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
            $html  = $ctl->RollOverFilas();

                $html .= " <script> \n";
                $html .= " function mOvr(src,clrOver)
                            {
                                    src.style.background = clrOver;
                            }
                            function mOut(src,clrIn)
                            {
                                    src.style.background = clrIn;
                            }";
                $html .= " function max(e){  ";
                $html .= "      tecla = (document.all) ? e.keyCode : e.which; ";
                $html .= "      if (tecla==8) return true;";
                $html .= "      if (tecla==13) return false;";
                $html .= " }";
                $html .= " function ValidarDatos(frm){\n";
                $html .= "    if(frm.nomcontacto.value==\"\")\n";
                $html .= "    {\n";
                $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NOMBRE DE LA PERSONA CON QUIEN HABLO TELEFONICAMENTE';\n";
                $html .= "      frm.nomcontacto.focus();";
                $html .= "      return;\n";
                $html .= "    }\n";
                $html .= "    if(frm.parentezcocontacto.value==\"\")\n";
                $html .= "    {\n";
                $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL PARENTEZCO DE LA PERSONA CON EL PACIENTE';\n";
                $html .= "      frm.parentezcocontacto.focus();";
                $html .= "      return;\n";
                $html .= "    }\n";
                $html .= "    if(frm.observaciones.value==\"\")\n";
                $html .= "    {\n";
                $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR UN RESUMEN DE LA LLAMADA';\n";
                $html .= "      frm.observaciones.focus();";
                $html .= "      return;\n";
                $html .= "    }\n";
                $html .= "    frm.submit();\n";
                $html .= " }\n";
                $html .=" </script>\n";

                $html .= ThemeAbrirTabla('REGISTRO DE LLAMADAS A PACIENTES POR MEDICAMENTOS PENDIENTES');

                $html .= "<table border=\"0\" width=\"98%\" align=\"center\" >\n";
                $html .= "	<tr>\n";
                $html .= "		<td>\n";
                $html .= "			<fieldset class=\"fieldset\">\n";
                $html .= "				<legend class=\"normal_10AN\">INFORMACION DE LA FORMULA</legend>\n";
                $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
                $html .= "					<tr>\n";
                $html .= "						<td align=\"center\">\n";
                $html .= "							<table width=\"98%\" class=\"label\" $style>\n";

                $html .= "								<tr >\n";

                $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE REGISTRO</td>\n";
                $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_registro']."\n";
                $html .= "									</td>\n";

                $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE FORMULA</td>\n";
                $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_formula']."\n";
                $html .= "									</td>\n";

                $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FORMULA No</td>\n";
                $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['formula_papel']."\n";
                $html .= "									</td>\n";

                $html .= "									<td  class=\"formulacion_table_list\" >HORA</td>\n";
                $html .= "									<td >".$Cabecera_Formulacion['hora_formula']."\n";
                $html .= "									</td> \n";

                $html .= "								</tr>\n";
                $html .= "							</table>\n";

                $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
				
                $html .= "								<tr>\n";
                $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO FORMULA</td>\n";
                $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['descripcion_tipo_formula']."\n";
                $html .= "									</td>\n";
                $html .= "								</tr>\n";
                $html .= "								<tr>\n";
                $html .= "								</tr>\n";

                $html .= "							</table>\n";

                $html .= "					<tr>\n";
                $html .= "						<td align=\"center\">\n";
                $html .= "							<table  width=\"98%\" class=\"label\" $style>\n";
                $html .= "								<tr>\n";
                $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
                $html .= "									<td colspan=\"3\">\n";
                $html .= "										".$request['tipo_id_paciente']." ".$request['paciente_id']."\n";
                $html .= "									</td>\n";
                $html .= "									<td  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">NOMBRE COMPLETO</td>\n";
                $html .= "									<td > ".$Cabecera_Formulacion['nombre_paciente']."\n";
                $html .= "									</td>\n";
                $html .= "								</tr>\n";

                if($Cabecera_Formulacion['sexo_id']=='M')
                {
                 $sexo='MASCULINO';
                }else
                {
                $sexo='FEMENINO';
                }
                list($anio,$mes,$dias) = explode(":",$Cabecera_Formulacion['edad']);

                if($anio!=0)
                {
                 $edad_t='AÑOS';
                 $edad=$anio;
                }
                if($anio==0 and $mes!=0)
                {
                  $edad_t='MES';
                  $edad=$mes;
                }
                else
                {
                    if($anio==0 and $mes==0)
                    {
                        $edad_t='DIAS';
                        $edad=$dias;
                    }
                }	

                $html .= "								<tr>\n";
                $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">EDAD</td>\n";
                $html .= "									<td >".$edad." &nbsp; $edad_t \n";
                $html .= "									</td>\n";
                $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
                $html .= "									<td align=\"left\">".$sexo."\n";
                $html .= "									</td>\n";
                $html .= "								</tr>\n";	

                $html .= "								<tr>\n";
                $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO PLAN</td>\n";
                $html .= "									<td>".$Datos_Ad['tipo_plan']."\n";
                $html .= "									</td>\n";
                $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO VINCULACION</td>\n";
                $html .= "									<td>".$Datos_Ad['vinculacion']."\n";
                $html .= "									</td>\n";
                $html .= "								</tr>\n";	

                if($opcion=='0')
                {
                        $html .= "								<tr >\n";
                        $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
                        $html .= "									<td colspan=\"3\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;  ".$Cabecera_Formulacion['profesional']." (".$Cabecera_Formulacion['descripcion_profesional'].")\n";
                        $html .= "						            </td>\n";
                        $html .= "								</tr>\n";
                }
                        $html .= "							</table>\n";
                        $html .= "					<tr>\n";
                        $html .= "						<td align=\"center\">\n";
                        $html .= "						<td>\n";
                        $html .= "					</tr>\n";
                        $html .= "					<tr>\n";
                        $html .= "						<td align=\"center\">\n";
                        $html .= "						<td>\n";
                        $html .= "					</tr>\n";
                        $html .= "					<tr>\n";
                        $html .= "						<td align=\"center\">\n";
                        $html .= "							<table   width=\"98%\" class=\"label\" $style>\n";
                        $html .= "								<tr>\n";
                        $html .= "									<td  colspan=\"8\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTOS SOLICITADOS</td>\n";
                        $html .= "								</tr>\n";
                        $html .= "								<tr>\n";
                        $html .= "									<td  colspan=\"2\"  style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">CODIGO</td>\n";
                        $html .= "									<td   colspan=\"2\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTO</td>\n";
                        $html .= "									<td   colspan=\"2\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">CANTIDAD PENDIENTE</td>\n";			
                        $html .= "									</td>\n";
                        $html .= "                              </tr>";
                        $est = " "; $back = " ";

                        $html .= "             <form name=\"formaPendientesResueltos\" id=\"formPendienteResuelto\" method=\"post\">\n";
                        for($i=0;$i<sizeof($medi_form);$i++)
                        {
                                    $html .= "  <tr   ".$est." onmouseout=mOut(this,\"#E6E6E6\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
                                    $html .= "   <td align=\"center\" colspan=\"2\">".$medi_form[$i]['codigo_medicamento']." </td>";
                                    $html .= "   <td align=\"center\" colspan=\"2\">".$medi_form[$i]['descripcion_prod']." </td>";
                                    $html .= "   <td colspan=\"2\" align=\"center\" width=\"23%\">".$medi_form[$i]['total']."</td>";                                  
                                    $html .= "  </tr>";
                        }	
                        $html .= "		        </form>";
                        $html .= "							</table>\n";
                        $html .= "					</tr>\n";
                        $html .= "					<tr>\n";
                        $html .= "						<td align=\"center\">\n";
                        $html .= "						</td>\n";
                        $html .= "					</tr>\n";
                                         
                        $html .= "      <tr>";
                        $html .= "          <td>";                       
                        $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
                        
                        $html .= "<form name=\"llamadapaciente\" id=\"llamadapaciente\" action=\"".$action['guardarllamada']."\" method=\"post\">\n";
                        $html .= "  <div id='error_doc' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
                        $html .= "  <div id=\"ventana1\">\n";
                        $html .= "   <table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
                        $html .= "     <tr>\n";
                        $html .= "        <td class=\"formulacion_table_list\" colspan=\"6\">REGISTRO DE DATOS DE LA LLAMADA</td>";

                        $empresa = SessionGetVar("DatosEmpresaAF");
                        $html .= "          <input type=\"hidden\" id=\"orden_requisicion_id\" name=\"orden_requisicion_id\" value=\"".$Cabecera_Formulacion['formula_id']."\">\n";
                        $html .= "          <input type=\"hidden\" id=\"empresa_id\" name=\"empresa_id\" value=\"".$empresa['empresa_id']."\">\n";
                        $html .= "          <input type=\"hidden\" id=\"centro_utilidad\" name=\"centro_utilidad\" value=\"".$empresa['centro_utilidad']."\">\n";
                        $html .= "          <input type=\"hidden\" id=\"bodega\" name=\"bodega\" value=\"".$empresa['bodega']."\">\n";
                        $html .= "          <input type=\"hidden\" id=\"formula_id\" name=\"formula_id\" value=\"".$Cabecera_Formulacion['formula_id']."\">\n";
                        $html .= "          <input type=\"hidden\" id=\"formula_papel\" name=\"formula_papel\" value=\"".$f_papel."\">\n";
                        $html .= "     </tr>\n";
                        
                        $html .= "      <tr>\n";
                        $html .= "          <td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">NOMBRE CONTACTO</td>\n";
                        $html .= "          <td >\n";
                        $html .= "              <input type='text' name='nomcontacto' id='nomcontacto' class=\"input-text\" size='51' maxlength='50' value=''>\n";
                        $html .= "          </td>\n";
                        $html .= "          <td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">PARENTEZCO CON EL PACIENTE</td>\n";
                        $html .= "          <td >\n";
                        $html .= "              <input type='text' name='parentezcocontacto' id='parentezcocontacto' class=\"input-text\" size='26' maxlength='25' value=''>\n";
                        $html .= "          </td>\n";
                        $html .= "      </tr>\n";
                        
                        $html .= "      <tr>\n";
                        $html .= "          <td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">OBSERVACIONES</td>\n";
                        $html .= "          <td colspan=\"3\" align=\"left\" class=\"modulo_list_claro\">";
                        $html .= "              <textarea  onkeypress=\"return max(event)\" name=\"observaciones\" id=\"observaciones\" rows=\"2\" style=\"width:100%\"></textarea>\n";
                        $html .= "      </tr>\n";
                        
                        $html .= "      <tr>\n";
                        $html .= "          <td align='center' colspan=4>\n";
                        $html .= "              <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnGuardar\" value=\"GUARDAR\" onclick=\"ValidarDatos(document.llamadapaciente)\">\n";
                        $html .= "          </td>\n";
                        $html .= "      </tr>\n";

                        $html .= "   </table>";
                        $html .= "  </div>";
                        $html .= "</form>\n";
                        
                        $html .= "          </td>";
                        $html .= "      </tr>";

                        $html .= "  <tr>\n";
                        $html .= "      <td align=\"center\">\n";
                        $html .= "          <table width=\"98%\" class=\"label\" $style>\n";
                        $html .= "              <tr>\n";
                        $html .= "                  <td colspan=\"6\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">HISTORIAL DE LLAMADAS</td>\n";
                        $html .= "              </tr>\n";
                        $html .= "		        <tr>\n";
                        $html .= "                  <td style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">ITEM</td>\n";
                        $html .= "                  <td style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">NOMBRE CONTACTO</td>\n";
                        $html .= "                  <td style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">PARENTEZCO CONTACTO</td>\n";
                        $html .= "                  <td style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">OBSERVACION</td>\n";
                        $html .= "                  <td style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">FECHA</td>\n";
                        $html .= "                  <td style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">USUARIO</td>\n";
                        $html .= "                  </td>\n";
                        $html .= "              </tr>\n";
                       
                        $historial=$obje->Consultar_Historial_Llamadas($Cabecera_Formulacion['formula_id']);
                        
                        $html .= "  <form name=\"formaPendientesResueltos\" id=\"formPendienteResuelto\" method=\"post\">\n";
                        for($i=0;$i<sizeof($historial);$i++)
                        {
                            $html .= "  <tr   ".$est." onmouseout=mOut(this,\"#E6E6E6\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
                            $html .= "  <td align=\"center\" >".$historial[$i]['llamada_id']." </td>";
                            $html .= "  <td align=\"center\" >".$historial[$i]['contacto_nombre']." </td>";
                            $html .= "  <td align=\"center\" >".$historial[$i]['contacto_parentezco']."</td>";
                            $html .= "  <td align=\"center\" ><textarea name='obs' id='obs' rows=\"2\" style=\"width:100%\" readOnly>".$historial[$i]['observacion']."</textarea></td>";
                            $html .= "  <td align=\"center\" >".$historial[$i]['fecha']." </td>";
                            $html .= "  <td align=\"center\" >".$historial[$i]['usuario']."</td>";
                            $html .= "  </tr>";
                        }	
                        $html .= "  </form>";                        
                        $html .= "          </table>\n";
                        $html .= "      </td>\n";
                        $html .= "  </tr>\n";
                        $html .= "				</table>\n";
                        $html .= "			</fieldset>\n";
                        $html .= "		</td>\n";
                        $html .= "	</tr>\n";                       
                        $html .= "</table>\n";
       
                $html.= " <br>";
                $volver = $action['iraPendientes']."&buscador[formula_papel]=".$f_papel;
                $html .= "<table align=\"center\">\n";
                $html .= "<br>";
                $html .= "  <tr>\n";
                $html .= "      <td align=\"center\" class=\"label_error\">\n";
                $html .= "        <a href=\"".$volver."\">VOLVER</a>\n";
                $html .= "      </td>\n";
                $html .= "  </tr>\n";
                $html .= "</table>\n";
                $html .= ThemeCerrarTabla();
				
                return $html;
     }		
    
	}
?>