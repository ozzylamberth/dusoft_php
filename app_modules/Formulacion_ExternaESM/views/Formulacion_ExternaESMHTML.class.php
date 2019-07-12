<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: Formulacion_ExternaESMHTML.class.php,v 1.0 
	* @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	
	IncludeClass("ClaseHTML");
	IncludeClass("ClaseUtil");
    IncludeClass("CalendarioHtml");
	class Formulacion_ExternaESMHTML
	{
	/**
		* Constructor de la clase
	*/

	function  Formulacion_ExternaESMHTML()
	{
	return true;
	}
   /** Function para el Menu de dispensacion
	* @param array $action Vector de links de la aplicacion
	* @return String
		*/
		function FormaMenu($action,$permisos,$permisos2)
		{
          $html  = ThemeAbrirTabla('MENU FORMULACION EXTERNA -ESM');
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
          if(!empty($permisos))
          {
		
                $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
                $html .= "      <td  class=\"label\"  align=\"center\">\n";
                $html .= "       <a href=\"".$action['FormulaInt']."\">\n";
                $html .= "       DIGITALIZACION DE FORMULAS </a>\n";
                $html .= "      </td>\n";
                $html .= "  </tr>\n";
                $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
                $html .= "      <td  class=\"label\"  align=\"center\">\n";
                $html .= "       <a href=\"".$action['FormulaExt']."\">\n";
                $html .= "       TRANSCRIPCION  DE FORMULAS</a>\n";
                $html .= "      </td>\n";
                $html .= "  </tr>\n";
        }	
         if(!empty($permisos2))
        {

                $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
                $html .= "      <td  class=\"label\"  align=\"center\">\n";
                $html .= "       <a href=\"".$action['suministros']."\">\n";
                $html .= "       SUMINISTROS</a>\n";
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
        * Funcion donde se crea la forma para mostrar los pacientes y seleccionar al que se le va hacer el registro
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
        $html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR  LA INSTITUCION';\n";
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
        $html .= "			  <td width=\"25%\" >INSTITUCION:</td>\n";
        $html .= "			  <td  align=\"left\" class=\"modulo_list_claro\" colspan=\"6\">\n";
        $html .= "				  <select name=\"institucion\" class=\"select\">\n";
        $html .= "            <option value = '-1'>--  SELECCIONE --</option>\n";
        $csk = "";
        foreach($instuticion as $indice => $valor)
        {
            $html .= "  <option value=\"".$valor['tipo_id_tercero']."#".$valor['tercero_id']."\" ".$sel.">(".$valor['tipo_id_tercero']." - ".$valor['tercero_id'].") &nbsp ".$valor['nombre_tercero']."</option>\n";
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
    * Funcion donde se registran el detalle de los medicamentos -farmacovigilancia
    * @return string $html retorna la cadena con el codigo html de la pagina
   */
    
    function formaRegistrarPacientes_medicamentos($action,$instuticion,$request,$datos,$conteo,$pagina,$formula_id)  
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
                    $html .= "      <td ><b>".$dtl['codigo_producto_mini']." </b></td>\n";
                    $html .= "      <td ><b>".$dtl['descripcion_prod']." </b></td>\n";
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
                    $html .= "      <input type=\"hidden\" name=\"dosis".$i."\" value=\"".round($dtl['numero_unidades'])." / ".$dtl['tiempo_tratamiento']." ".$unidad_tiempo_tratamiento."\" >  <input type=\"hidden\" name=\"fecha_v".$i."\"  id=\"fecha_v".$i."\"  value=\"".$dtl['fecha_vencimiento']."\" >  
                    <input type=\"hidden\" name=\"lote".$i."\"  name=\"lote".$i."\" value=\"".$dtl['lote']." \" >  ";
                    $html .="        <td  aling=\"left\">";
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
                    $html .="        <td  aling=\"left\">";
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
            $html .= "      <td   colspan=\"13\"  align=\"left\" class=\"modulo_list_claro\"> <textarea  onkeypress=\"return max(event)\"  name=\"diagnostico\"  id=\"diagnostico\"   rows=\"2\"  style=\"width:100%\">".$valor['condiciones_entrega']."</textarea>\n";
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
    * Funcion donde se genera el reporte de farmacovigilancia 
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
          $mostrar = $reporte->GetJavaReport('app','Formulacion_ExternaESM','FormatoFarmacov',
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
    * Funcion donde se buscan los reportes de farmacovigilancia 
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
              $html .= "      <td   width=\"15%\"><b>DIAGNOTICO</b></td>\n";
              $html .= "      <td   width=\"45%\"><b>USUARIO REGISTRO</b></td>\n";
              $html .= "	      <td   colspan=\"2\">";
              $html .= "      <b>OP </b></td>\n";
              $html .= "  </tr>\n";
                    
              $est = "modulo_list_claro"; $back = "#DDDDDD";
              foreach($datos as $key => $dtl)
              {
                $html .= "	  <tr  align=\"CENTER\" class=\"".$est."\" >\n";
                //	$html .= "        <input type=\"hidden\" name=\"solicitud_prod_a_bod_ppal_id".$i."\" id=\"solicitud_prod_a_bod_ppal_id".$i."\" value=\"".$dtl['codigo_producto']."\">";
                $html .= "      <td align=\"center\"><B>".$dtl['esm_farmaco_id']."</B></td>\n";
                $html .= "      <td align=\"left\"><B>".$dtl['formula_papel']."</B></td>\n";
                $html .= "      <td align=\"left\"><B>".$dtl['tipo_id_paciente']."  ".$dtl['paciente_id']."</B></td>\n";
                $html .= "      <td align=\"left\"><B>".$dtl['apellidos']." ".$dtl['nombres']."</B></td>\n";
                $html .= "      <td align=\"left\"><B>".$dtl['fecha_notificacion']."</B></td>\n";
                $html .= "      <td align=\"left\"><B>".$dtl['diagnostico']."</B></td>\n";
                $html .= "      <td align=\"left\"><B>".$dtl['nombre']." - ".$dtl['descripcion']."</B></td>\n";
               
                $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
                $Formula_info=$obje->Consultar_Identificador_formula($dtl['formula_papel'],$dtl['tipo_id_paciente'],$dtl['paciente_id']);
                $formula_id=$Formula_info[0]['formula_id'];
          
          
          
                $mostrar = $reporte->GetJavaReport('app','Formulacion_ExternaESM','FormatoFarmacov',array("esm_farmaco_id"=>$dtl['esm_farmaco_id'],"formula_r"=>$formula_id),
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
    /* Forma para capturar los datos para buscar el paciente
	  * @return string $html retorna la cadena con el codigo html de la pagina
		*/
		function FormaBuscarPacientes($action,$Plan_P,$TipoIdent,$request,$datos,$conteo,$pagin,$opcion,$dias)
		{
          $html .=  "<script>\n";
          $html .= "  function Continuar()\n";
          $html .= "  {\n";

          $html .= "     document.formabuscar.action = '".$action['FormaDP']."';\n";
          $html .= "     document.formabuscar.submit();\n";
          $html .= "  }\n";
          $html .= "</script>\n";
          $ctl = AutoCarga::factory("ClaseUtil");
          $html .= $ctl->RollOverFilas();
          $direccion= ModuloGetURL("app", "Formulacion_ExternaESM", "controller", "Buscar_Pacientes_Formulas");
          $html .= "	<script>\n";
          $html .= "		function EvaluarDatos(objeto)\n";
          $html .= "		{\n";
          $html .= "      xajax_ValidarPaciente(xajax.getFormValues('formabuscar'));\n";
          $html .= "		}\n";
          $html .= "		function EvaluarDatos_d(objeto,tipo,documento)\n";
          $html .= "		{\n";
          $html .= "    if(objeto.TipoDocumento.value==\"-1\")\n";
          $html .= "    {\n";
          $html .= "      document.getElementById('errorA').innerHTML = 'SE DEBE SELECCIONAR EL TIPO DE DOCUMENTO';\n";
          $html .= "      return;\n";
          $html .= "    }\n";
          $html .= "    if(objeto.Documento.value==\"\")\n";
          $html .= "    {\n";
          $html .= "      document.getElementById('errorA').innerHTML = 'SE DEBE INGRESAR EL NUMERO DEL DOCUMENTO';\n";
          $html .= "      return;\n";
          $html .= "    }\n";
          $html .= " var direccion='".$direccion."'; ";
          $html .= " var opcion='".$opcion."'; ";
          $html .= " var url=direccion+'&tipo='+tipo+'&documento='+documento+'&opcion='+opcion; ";
          $html .= "window.location=url;";
          $html .= "		}\n";
          $html .= "	</script>\n";
        
          $html .= ThemeAbrirTabla('FORMULACION - BUSCAR PACIENTE');
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
        
        if($valo['tipo_id_tercero']==$request['tipo'])
				$sel = "selected";
				else   $sel = "";
				$html .= "  <option value=\"".$valo['tipo_id_tercero']."\" ".$sel.">".$valo['descripcion']."</option>\n";
			}
			
			$html .= "              </select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td style=\"text-align:left;text-indent:11pt\" >DOCUMENTO: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<input type=\"text\" class=\"input-text\" name=\"Documento\" size=\"20\"  maxlength=\"52\" value=\"".$request['documento']."\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
		
	    $html .="	</table>\n";
			$html .= "	<table border=\"0\" align=\"center\" width=\"20%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"><br>\n";
			$html .= "			</td>\n";

      $html .= "		</form>\n";
      $html .= "<form name=\"formabuscar_\" id=\"formabuscar_\"  action=\"".$action['consulta_producto']."\" method=\"post\" >\n";

      $html .= "			<td align=\"center\">\n";
      $html .= "				<br><input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"Consultar Productos\" onclick=\"EvaluarDatos_d(document.formabuscar,document.formabuscar.TipoDocumento.value,document.formabuscar.Documento.value);\" ><br>\n";
      $html .= "			</td>\n";
      $html .= "  </tr>\n";

      $html .= "		</form>\n";

      $html .= "</table><BR>\n";
			if(!empty($datos))
			{
			

          $html .= "			<fieldset class=\"fieldset\">\n";
          $html .= "				<legend class=\"normal_10AN\">MEDICAMENTOS ENTREGADOS EN MENOS DE ".$dias." DIA(S) IDENTIFICACION PACIENTE:  ".$request['tipo']."   ".$request['documento']."</legend>\n";
          $html .= "	<table  align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"100%\">";
          $html .= "	<tr  class=\"modulo_table_list_title\" >";
          $html .= "	<td width=\"10%\">FORMULA NO</td>";
          $html .= "	<td width=\"10%\">CODIGO</td>";
          $html .= "	<td width=\"45%\">DESCRIPCION</td>";
          $html .= "	<td width=\"10%\">CANTIDAD</td>";
          $html .= "	<td width=\"15%\">LOTE</td>";
          $html .= "	<td width=\"15%\">FECHA VENC</td>";
				
          $html .= "	</tr>";
          $i=0;
          foreach($datos as $key => $dtl)
          {
                    if( $i % 2){$estilo='modulo_list_claro';}
                      else {$estilo='modulo_list_oscuro';}
                    $html .= "<tr class=\"$estilo\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" title=".$dtl['codigo_producto']." >\n";
                    $html .= "<td align=\"left\" width=\"10%\">".$dtl['formula_papel']."</td>";
                    $html .= "<td align=\"left\" width=\"10%\">".$dtl['min_defensa']."</td>";
                    if($dtl['sw_pactado']=='1')
                    {
                      $html .= "<td align=\"left\" width=\"45%\">".$dtl['molecula']."</td>";
                    
                    }else
                    {
                    
                      $html .= "<td align=\"left\" width=\"45%\">".$dtl['descripcion']."</td>";
                    }
                  
                  $html .= "<td align=\"left\" width=\"10%\">".round($dtl['cantidad'])."</td>";
                  $html .= "<td align=\"left\" width=\"15%\">".$dtl['lote']."</td>";
                  $html .= "<td align=\"left\" width=\"15%\">".$dtl['fecha_vencimiento']."</td>";
                  
           }
            $html .= "</tr>";

            $html .= "	</table><br>";
            $html .= "			</fieldset>\n";
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
		
     /* Forma para la informacion basica de la formula
		  * @return string $html retorna la cadena con el codigo html de la pagina
		*/
    function FormaCabeceraFormula($action,$Tipo_Formula,$Tipo_Evento,$request,$Datos_Paciente,$Datos_Fueza,$Datos_Ad,$ESM_pac,$ESM_,$opcion,$IPS_,$validar_paciente,$edad_paciente,$dx_ingres)
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
          $html .= "      if(objeto.tipo_formula.value == \"-1\")\n";
          $html .= "        {\n"; 
          $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR EL TIPO DE FORMULA\";\n"; 
          $html .= "          return true;\n"; 
          $html .= "        }\n"; 
          $html .= "      if(objeto.tipo_evento.value == \"-1\")\n";
          $html .= "        {\n"; 
          $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR EL TIPO DE EVENTO\";\n"; 
          $html .= "          return true;\n"; 
          $html .= "        }\n"; 
          if($opcion=='0')
          {
                $html .= "      if(objeto.esm.value == \"-1\")\n";
                $html .= "        {\n"; 
                $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR El ESTABLECIMIENTO DE SANIDAD MILITAR\";\n"; 
                $html .= "          return true;\n"; 
                $html .= "        }\n";
                $html .= "      if(objeto.profesional.value == \"-1\")\n";
                $html .= "        {\n"; 
                $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR PROFESIONAL\";\n"; 
                $html .= "          return true;\n"; 
                $html .= "        }\n";
          }
          if($opcion=='1')
          {
                $html .= "      if(objeto.ips.value == \"-1\")\n";
                $html .= "        {\n"; 
                $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR LA INSTITUCION PRESTADORA DE SERVICIOS DE SALUD\";\n"; 
                $html .= "          return true;\n"; 
                $html .= "        }\n";
                $html .= "      if(objeto.profesional_ips.value == \"-1\")\n";
                $html .= "        {\n"; 
                $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR EL PROFESIONAL DE LA INSTITUCION PRESTADORA DE SERVICIOS DE SALUD\";\n"; 
                $html .= "          return true;\n"; 
                $html .= "        }\n";
                $html .= "      if(objeto.esm_ips.value == \"-1\")\n";
                $html .= "        {\n"; 
                $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR EL ESTABLECIMIENTO DE SANIDAD MILITAR\";\n"; 
                $html .= "          return true;\n"; 
                $html .= "        }\n";
                $html .= "      if(objeto.profesional_ips_esm.value == \"-1\")\n";
                $html .= "        {\n"; 
                $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR EL PROFESIONAL DEL ESTABLECIMIENTO DE SANIDAD MILITAR\";\n"; 
                $html .= "          return true;\n"; 
                $html .= "        }\n";
                $html .= "      if(objeto.profesional_aut_esm_ips.value == \"-1\")\n";
                $html .= "        {\n"; 
                $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR EL PROFESIONAL QUE AUTORIZA LA FORMULA \";\n"; 
                $html .= "          return true;\n"; 
                $html .= "        }\n";
                $html .= "      if(objeto.costo_formula.value == \"\")\n";
                $html .= "        {\n"; 
                $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE INGRESAR EL COSTO DE LA FORMULA\";\n"; 
                $html .= "          return true;\n"; 
                $html .= "        }\n";
        }
        $html .= "    objeto.submit();\n";
        $html .= "  }\n";
        $html .= "</script>\n"; 
        $html .= ThemeAbrirTabla('REGISTRO DE UNA FORMULA');
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
				if($opcion=='0')
        {
              $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
              $html .= "								<tr >\n";
              $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTOS DE SANIDAD MILITAR</td>\n";
              $html .= "									<td colspan=\"2\">\n";
              $html .= "										<select name=\"esm\" class=\"select\" onchange=\"xajax_MostrarProfesionales(xajax.getFormValues('registrar_cabecera'),'".$opcion."')\" >\n";
              if(empty($ESM_pac))
              {
				              $html .= "	<option value=\"-1\">NO TIENE UNA ESM ASOCIADA</option>\n";
			
              }else
              {
                      $html .= "										<option value=\"".$ESM_pac['tipo_id_tercero']."@".$ESM_pac['tercero_id']."\">".$ESM_pac['nombre_tercero']."</option>\n";
              }
              foreach($ESM_ as $key => $dtl)
              {
                      $html .= "											<option value=\"".$dtl['tipo_id_tercero']."@".$dtl['tercero_id']."\" >".$dtl['nombre_tercero']."</option>\n";
              }
              $html .= "										</select>\n";
              $html .= "									</td>\n";
              $html .= "								</tr>\n";
              
              $html .= "								<tr >\n";
              $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONALES</td>\n";
              $html .= "									<td colspan=\"2\">\n";
              $html .= "					           <select name=\"profesional\" id=\"profesional\" class=\"select\" >\n";
              $html .= "                     	<option value ='-1'>--  SELECCIONE --</option>\n";
              $csk = "";
              $html .= "                </select>\n";
              $html .= "						     </td>\n";
              $html .= "								</tr>\n";
              $html .= "							</table>\n";
          }
	
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
        $html .= "										<select name=\"tipo_formula\" class=\"select\"  >\n";
        foreach($Tipo_Formula as $key => $dtl)
        {
			  
            $html .= "  <option value=\"".$dtl['tipo_formula_id']."\" ".$sel.">".$dtl['descripcion_tipo_formula']."</option>\n";
        }
        $html .= "										</select>\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        $html .= "								<tr >\n";
        $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO EVENTO</td>\n";
        $html .= "									<td colspan=\"2\">\n";
        $html .= "										<select name=\"tipo_evento\" class=\"select\">\n";
				foreach($Tipo_Evento as $key => $dtl)
        {
            $html .= "  <option value=\"".$dtl['tipo_evento_id']."\" ".$sel.">".$dtl['descripcion_tipo_evento']."</option>\n";
        }
        $html .= "										</select>\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        $html .= "							</table>\n";
        if($opcion=='1')
        {
            $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >INSTITUCION PRESTADORA DE SERVICIOS DE SALUD </td>\n";
            $html .= "									<td colspan=\"2\">\n";
            $html .= "										<select name=\"ips\" class=\"select\" onchange=\"xajax_Mostrar_profesion_IPS(xajax.getFormValues('registrar_cabecera'),'".$opcion."')\" >\n";
            if(empty($IPS_))
            {
                $html .= "	<option value=\"-1\">NO EXISTEN CONVENIOS CON IPS</option>\n";
          	}
            $html .= "											<option value=\"-1\">-SELECCIONAR-</option>\n";
            foreach($IPS_ as $key => $dtl)
            {
                $html .= "											<option value=\"".$dtl['tipo_id_tercero']."@".$dtl['tercero_id']."\" >(".$dtl['tipo_id_tercero']." ".$dtl['tercero_id'].")  &nbsp;".$dtl['nombre_tercero']."</option>\n";

            }
            $html .= "										</select>\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONALES (IPS)</td>\n";
            $html .= "									<td colspan=\"2\">\n";
            $html .= "					           <select name=\"profesional_ips\" id=\"profesional_ips\" class=\"select\" >\n";
            $html .= "                     	<option value ='-1'>--  SELECCIONE --</option>\n";
            $csk = "";
            $html .= "                </select>\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >UBICACION (IPS)</td>\n";
            $html .= "									<td colspan=\"2\"   name=\"ubicacion_ips\" id=\"ubicacion_ips\"  >\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTOS DE SANIDAD MILITAR</td>\n";
            $html .= "									<td colspan=\"2\">\n";
            $html .= "										<select name=\"esm_ips\" id=\"esm_ips\" class=\"select\"  onchange=\"xajax_Mostrar_profesion_IPS_ESM(xajax.getFormValues('registrar_cabecera'),'".$opcion."')\">\n";
            $html .= "										</select>\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";

            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONALES</td>\n";
            $html .= "									<td colspan=\"2\">\n";
            $html .= "					           <select name=\"profesional_ips_esm\" id=\"profesional_ips_esm\" class=\"select\" >\n";
            $html .= "                     	<option value ='-1'>--  SELECCIONE --</option>\n";
            $csk = "";
            $html .= "                </select>\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
                    
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL AUTORIZA</td>\n";
            $html .= "									<td colspan=\"2\">\n";
            $html .= "					           <select name=\"profesional_aut_esm_ips\" id=\"profesional_aut_esm_ips\" class=\"select\" >\n";
            $html .= "                     	<option value ='-1'>--  SELECCIONE --</option>\n";
            $csk = "";
            $html .= "                </select>\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
				
            $html .= "								<tr >\n";			
            $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >COSTO FORMULA</td>\n";
            $html .= "									<td width=\"10%\" align=\"right\">\n";
            $html .= "										<input type=\"text\" class=\"input-text\" name=\"costo_formula\" style=\"width:90%\"  onkeypress=\"return acceptNum(event)\" maxlength=\"10\"  value=\"0\">\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";

            $html .= "							</table>\n";
				
        }
        
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
			$html .= " <input type=\"hidden\" name=\"tipo_fuerza\" id=\"tipo_fuerza\" value=\"".$Datos_Fueza['tipo_fuerza_id']."\">";
			if(empty($Datos_Fueza))
			{
			
          $fuerza .= "									<td align=\"left\" class=\"label_error\"> NO TIENE UNA FUERZA ASOCIADA\n";
          $fuerza .= "									</td>\n";			
			
			}
			else
			{
				$fuerza .= "									<td>".$Datos_Fueza['descripcion']."\n";
				$fuerza .= "									</td>\n";			
		
			}
		
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FUERZA</td>\n";
			$html .= "								".$fuerza;
			$html .= "								</tr>\n";	
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO PLAN</td>\n";
			$html .= "									<td >".$Datos_Ad['tipo_plan']."\n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO VINCULACION</td>\n";
			$html .= "									<td >".$Datos_Ad['vinculacion']."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";	
			if($opcion=='1')
			{
          $html .= "								<tr >\n";
          $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTOS DE SANIDAD MILITAR</td>\n";
          $html .= "									<td colspan=\"2\">\n";
          $html .= "									".$ESM_pac['tipo_id_tercero']." ".$ESM_pac['tercero_id']."  &nbsp; &nbsp;".$ESM_pac['nombre_tercero']."\n";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";

			}		
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
      $html .= " <script>";
      $html .=" xajax_MostrarProfesionales(xajax.getFormValues('registrar_cabecera'),'".$opcion."'); ";
      $html .= " </script>";
			$html .= "		</form>\n";
			$html .= "		<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">\n";
			$html .= "		  </td>";
			$html .= "		</form>\n";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();	
			return $html;
		}

    /**
    * Funcion donde se buscan los productos a formular
    * @return string $html retorna la cadena con el codigo html de la pagina
   */
    function FormaBuscarProductos_For($action,$request,$datos,$conteo,$pagina,$formula,$medicamento_Datos,$via_admon,$mensaje,$var_e,$medi_form,$opcion,$Cabecera_Formulacion,$DX_,$request,$Datos_Fueza,$Datos_Ad,$ESM_pac,$Cabecera_Formulacion_AESM,$Cabecera_Formulacion_AEM,$tmp_id,$insumos,$plan_id)
    {		
        $today = date("Y-m-d");
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');			
        $tope=$obje->Consultar_saldo_tope_($today,$Cabecera_Formulacion['esm_tipo_id_tercero'],$Cabecera_Formulacion['esm_tercero_id']);
        $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
        $estilo = "style=\"border-bottom-width:0px;border-left-width:2px;border-right-width:0px;border-top-width:0px;border-style: solid;\""; 

        $html .= ThemeAbrirTabla('REGISTRO DE UNA FORMULA -MEDICAMENTOS FORMULADOS ');
        $html .= "			<fieldset class=\"fieldset\">\n";
        $html .= "				<legend class=\"normal_10AN\">INFORMACION DEL TOPO DE LA ESM </legend>\n";
        $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
        $html .= "								<tr >\n";
        $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SALDO DE LA ESM </td>\n";
        $html .= "									<td width=\"10%\" align=\"right\" class=\"label_error\">$".FormatoValor($tope['saldo_tope'])."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        $html .= "							</table>\n";
        $html .= "						</td>\n";
        $html .= "					</tr>\n";
        $html .= "				</table>\n";
        $html .= "			</fieldset>\n";
		 
        
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
        $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_registro']."\n";
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
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        $html .= "								<tr >\n";
        $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO EVENTO</td>\n";
        $html .= "									<td colspan=\"2\">	".$Cabecera_Formulacion['descripcion_tipo_evento']."\n";
        $html .= "									</td>\n";
				$html .= "								</tr>\n";
        $html .= "							</table>\n";
				if($opcion=='1')
        {
            $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >INSTITUCION PRESTADORA DE SERVICIOS DE SALUD </td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion_AEM['ips_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion_AEM['ips_tercero_id']."  &nbsp; ".$Cabecera_Formulacion_AEM['ips_atendido']."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONALES (IPS)</td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion_AEM['ips_profesional_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion_AEM['ips_profesional_tercero_id']."  &nbsp; ".$Cabecera_Formulacion_AEM['profesional_ips']." ( ".$Cabecera_Formulacion_AEM['descripcion_profesional_ips'].")\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >UBICACION (IPS)</td>\n";
            $html .= "									<td colspan=\"2\"   name=\"ubicacion_ips\" id=\"ubicacion_ips\"  >".$Cabecera_Formulacion_AEM['ubicacion']."\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTO DE SANIDAD MILITAR</td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['esm_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['esm_tercero_id']."  &nbsp; ".$Cabecera_Formulacion['esm_atendio']."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";

            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;  ".$Cabecera_Formulacion['profesional_esm']." (".$Cabecera_Formulacion['descripcion_profesional_esm'].")\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
	            	
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL AUTORIZA</td>\n";
            $html .= "									<td colspan=\"2\"> ".$Cabecera_Formulacion_AESM['esm_autoriza_tipo_id_tercero]']."  &nbsp; ".$Cabecera_Formulacion_AESM['esm_autoriza_tercero_id']."  &nbsp;  ".$Cabecera_Formulacion_AESM['profesional_esm']." (".$Cabecera_Formulacion_AESM['descripcion_profesional_esm'].")\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";

            $html .= "								<tr >\n";			
            $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >COSTO FORMULA</td>\n";
            $html .= "									<td width=\"10%\" align=\"right\">".round($Cabecera_Formulacion_AESM['costo_formula'])."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            $html .= "							</table>\n";
			}
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
        $html .= " <input type=\"hidden\" name=\"tipo_fuerza\" id=\"tipo_fuerza\" value=\"".$Datos_Fueza['tipo_fuerza_id']."\">";

        if(empty($Datos_Fueza))
        {
			
                  $fuerza .= "									<td align=\"left\" class=\"label_error\"> NO TIENE UNA FUERZA ASOCIADA\n";
                  $fuerza .= "									</td>\n";			
				  
        }
        else
        {
                $fuerza .= "									<td>".$Datos_Fueza['descripcion']."\n";
                $fuerza .= "									</td>\n";			
        }
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FUERZA</td>\n";
			$html .= "								".$fuerza;
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO PLAN</td>\n";
			$html .= "									<td >".$Datos_Ad['tipo_plan']."\n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO VINCULACION</td>\n";
			$html .= "									<td >".$Datos_Ad['vinculacion']."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			$html .= "								<tr>\n";
			$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTO DE SANIDAD MILITAR  </td>\n";
			$html .= "									<td colspan=\"2\">\n";
			$html .= "									".$ESM_pac['tipo_id_tercero']." ".$ESM_pac['tercero_id']."  &nbsp; &nbsp;".$ESM_pac['nombre_tercero']."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			if($opcion=='0')
			{
			
				$html .= "								<tr >\n";
				$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PUNTO DE ATENCION  </td>\n";
				$html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['esm_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['esm_tercero_id']."  &nbsp; ".$Cabecera_Formulacion['esm_atendio']."\n";
				$html .= "									</td>\n";
				$html .= "								</tr>\n";
				$html .= "								<tr >\n";
				$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
				$html .= "									<td colspan=\"3\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;  ".$Cabecera_Formulacion['profesional_esm']." (".$Cabecera_Formulacion['descripcion_profesional_esm'].")\n";
				$html .= "						     </td>\n";
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
      if($formula!=1)
			{
			
          $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
          if(!empty($medi_form))
          {
              
              $html .= "<table border=\"0\" width=\"88%\" align=\"center\" >\n";
              $html .= "	<tr>\n";
              $html .= "		<td>\n";
              $html .= "			<fieldset class=\"fieldset\">\n";
              $html .= "				<legend class=\"normal_10AN\">MEDICAMENTOS FORMULADOS </legend>\n";
              $html .= "<table  align=\"center\" border=\"0\"   class=\"modulo_table_list\" width=\"100%\">";
              $html .= "	<tr class=\"formulacion_table_list\" >";
              $html .= "  <td align=\"center\" colspan=\"6\">MEDICAMENTOS  SOLICITADOS</td>";
              $html .= "</tr>";
              $html .= "<tr class=\"formulacion_table_list\">";
              $html .= "  <td width=\"7%\">CODIGO</td>";
              $html .= " <td width=\"30%\">PRODUCTO</td>";
              $html .= " <td colspan=\"2\" width=\"43%\">PRINCIPIO ACTIVO</td>";
              $html .= " <td width=\"43%\">MA</td>";
              $html .= " <td width=\"43%\">OP</td>";
              $html .= "</tr>";
        
              for($i=0;$i<sizeof($medi_form);$i++)
              {
                  $busqueda=$obje->Consultar_Medicamentos_Detalle($tmp_id,$medi_form[$i]['codigo_producto']);
                           
                  $today = date("Y-m-d"); 
                  $hoy=explode("-", $today);
                  $hoy_fecha= $hoy[2]."/".$hoy[1]."/".$hoy[0];
                  $dias_dipensados= ModuloGetVar('','','dispensacion_dias_ultima_entrega');

                  list($a,$m,$d) = split("-",$today);
                  $fecha_condias = date("Y-m-d",(mktime(0,0,0, $m,($d - $dias_dipensados),$a)));

                  $fecha_condias_d=explode("-", $fecha_condias);
                  $tiempo_tratamiento_=$busqueda[0]['tiempo_tratamiento'];
                  $unidad_tiempo_tratamiento_=$busqueda[0]['unidad_tiempo_tratamiento'];
                  if($unidad_tiempo_tratamiento_=='1')
                  {

                  $dias_se=$tiempo_tratamiento_ * 365;
                  }
                  if($unidad_tiempo_tratamiento_=='2')
                  {

                  $dias_se=$tiempo_tratamiento_ *  30;
                  }
                  if($unidad_tiempo_tratamiento_=='3')
                  {

                  $dias_se=$tiempo_tratamiento_ *  7;
                  }
                  if($unidad_tiempo_tratamiento_=='4')
                  {

                  $dias_se=$tiempo_tratamiento_ *  1;
                  }

                  $fecha_formulacion_de=$busqueda[0]['fecha_formula'];
                  list($a,$m,$d) = split("-",$fecha_formulacion_de);
                  $fecha_condias__ = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_se),$a)));
                  $hoy_d=date('Y-m-d');

                  $dosisA=$busqueda[0]['dosis'];

                $fecha_formulacion=$busqueda[0]['fecha_formula'];
                list($year, $month, $day) =explode("-", $fecha_formulacion);
                $fecha_formulacion= mktime(0, 0, 0, $month, $day, $year); 
                $tiempo_tratamiento=$busqueda[0]['tiempo_tratamiento'];
                $unidad_tiempo_tratamiento=$busqueda[0]['unidad_tiempo_tratamiento'];
                if($unidad_tiempo_tratamiento=='1')
                {
                    $dias_s=$tiempo_tratamiento * 365;
                }
                if($unidad_tiempo_tratamiento=='2')
                {

                    $dias_s=$tiempo_tratamiento *  30;
                }
                if($unidad_tiempo_tratamiento=='3')
                {

                    $dias_s=$tiempo_tratamiento *  7;
                }
                if($unidad_tiempo_tratamiento=='4')
                {

                    $dias_s=$tiempo_tratamiento *  1;
                }


                $fecha_formulacion_d=$busqueda[0]['fecha_formula'];

                list($a,$m,$d) = split("-",$fecha_formulacion_d);
                $fecha_condias = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_s),$a)));
                $fecha_condias_d=explode("-", $fecha_condias);
                $fecha_condias_t= $fecha_condias_d[2]."/".$fecha_condias_d[1]."/".$fecha_condias_d[0];

                list($year, $month, $day) =explode("/", $fecha_condias_t);
                $fecha_finalizacion= mktime(0, 0, 0, $month, $day, $year); 

                $totalDays = ($fecha_finalizacion - $fecha_formulacion)/(60 * 60 * 24) ;  

                $cantidad_Tota=$busqueda[0]['cantidad'];
                $TotalCantMe=$totalDays * $CantidadMedi;

                $periodicidad_entrega=$busqueda[0]['periodicidad_entrega'];
                $unidad_periodicidad_entrega=$busqueda[0]['unidad_periodicidad_entrega'];


                  if($unidad_periodicidad_entrega=='1')
                  {
                    $dias_e=$periodicidad_entrega * 365;
                  }
                  if($unidad_periodicidad_entrega=='2')
                  {

                    $dias_e=$periodicidad_entrega * 30;
                  }
                  if($unidad_periodicidad_entrega=='3')
                  {

                      $dias_e=$periodicidad_entrega  * 7;
                  }
                  if($unidad_periodicidad_entrega=='4')
                  {

                      $dias_e=$periodicidad_entrega * 1;
                  }
                  $cantidad_entrega_=$busqueda[0]['cantidad'];
                  $cantidad_entrega=($cantidad_entrega_/$dias_e)*10;
                  $Conversion=$obje->ConsultarFactorConversion($busqueda[0]['codigo_producto']);
                  $unidad_dosif=$Conversion['0']['unidad_dosificacion'];	
                  $factor_conversion=$Conversion['0']['factor_conversion'];	
            
                  $CantidaEntregar=($cantidad_entrega/$factor_conversion);
						
                  
                  if( $i % 2){ $estilo='modulo_list_claro';}
                  else {$estilo='modulo_list_oscuro';}
                  $html .= "<tr class=\"$estilo\">";
                  $html .= "  <td align=\"center\" width=\"30%\">".$medi_form[$i]['min_defensa']." </td>";

                  $html .= "  <td align=\"center\" width=\"30%\">".$medi_form[$i]['descripcion_prod']." ".$medi_form[$i]['concentracion_forma_farmacologica']." </td>";
                  $html .= "  <td colspan=\"2\" align=\"center\" width=\"43%\">".$medi_form[$i]['principio_activo']."</td>";
                 
                if($medi_form[$i]['sw_marcado']=='0')
                {
                    $html .= "				<td  width=\"10%\"  align=\"center\"  >\n";
                    $html .= "					<a href=\"".$action['marcado'].URLRequest(array("producto_eliminar"=>$medi_form[$i]['codigo_producto'],"marcado"=>'1',"fe_medicamento_id"=>$medi_form[$i]['fe_medicamento_id']))."\"  class=\"label_error\"  ><img src=\"".GetThemePath()."/images/si.png\" border='0' >\n";
                    $html .= "					</a></center>\n";
                    $html .= "			</td>\n";
                }
              else
              {
                  $html .= "				<td  width=\"10%\"  align=\"center\"  >\n";
                  $html .= "					<img src=\"".GetThemePath()."/images/delete.gif\" border='0' >\n";
                  $html .= "					</a></center>\n";
                  $html .= "			</td>\n";
              }
              $html .= "				<td  width=\"10%\"  align=\"center\"  >\n";
              $html .= "					<a href=\"".$action['eliminar_med'].URLRequest(array("producto_eliminar"=>$medi_form[$i]['codigo_producto'],"eliminar_medica"=>'1',"fe_medicamento_id"=>$medi_form[$i]['fe_medicamento_id']))."\"  class=\"label_error\"  ><img src=\"".GetThemePath()."/images/delete2.gif\" border='0' >\n";
              $html .= "					</a></center>\n";
              $html .= "			</td>\n";
              $html .= "</tr>";

              if($medi_form[$i]['principio_activo']!="")
              {
								 $html .= "  <tr class=\"$estilo\">";
								 $html .= "    <td colspan = 12>";
								 $html .= "      <table>";
                 $html .= "<tr class=\"$estilo\">";
								 $via_form=$obje->Consultar_Via_Admin($medi_form[$i]['via_administracion_id']);
								 $html .= "  <td colspan = 3 align=\"left\" width=\"9%\"><b>Via de Administracion: </b>".$via_form[0]['nombre']."</td>";
								 $html .= "</tr>";

                 $html .= "<tr class=\"$estilo\">";
                 $html .= "  <td align=\"left\" width=\"9%\"><b>Dosis:<b></td>";
                 $e=$medi_form[$i]['dosis']/floor($medi_form[$i]['dosis']);
								
                  if($e==1)
                  {
                        $html .= "  <td align=\"left\" width=\"14%\">".floor($medi_form[$i]['dosis'])."  ".$medi_form[$i]['unidad_dosificacion']."</td>";
                  }
                  else
                  {
                        $html .= "  <td align=\"left\" width=\"14%\">".$medi_form[$i]['dosis']."  ".$medi_form[$i]['unidad_dosificacion']."</td>";
                  }

                $opcion_d=$obje->Consulta_opc_Medicamentos_Posologia_tmp($medi_form[$i]['fe_medicamento_id']);
                $vector_posologia= $obje->Consulta_Solicitud_Medicamentos_Posologia_tmp($opcion_d['opcion'], $medi_form[$i]['fe_medicamento_id']);

								if($opcion_d['opcion']== 1)
								{
									 $html .= "   <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0]['periocidad_id']." ".$vector_posologia[0]['tiempo']."</td>";
								}
								if($opcion_d['opcion']== 2)
								{
									$html .= " <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
								}
								if($opcion_d['opcion']== 3)
								{
										$momento = '';
										if($vector_posologia[0]['sw_estado_momento']== '1')
										{
											$momento = 'antes de ';
										}
										else
										{
											if($vector_posologia[0]['sw_estado_momento']== '2')
											{
												$momento = 'durante ';
											}
											else
											{
												if($vector_posologia[0]['sw_estado_momento']== '3')
													{
														$momento = 'despues de ';
													}
											}
										}
										$Cen = $Alm = $Des= '';
										$cont= 0;
										$conector = '  ';
										$conector1 = '  ';
										if($vector_posologia[0]['sw_estado_desayuno']== '1')
										{
											$Des = $momento.'el Desayuno';
											$cont++;
										}
										if($vector_posologia[0]['sw_estado_almuerzo']== '1')
										{
											$Alm = $momento.'el Almuerzo';
											$cont++;
										}
										if($vector_posologia[0]['sw_estado_cena']== '1')
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
										$html .= "  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
								}
								if($opcion_d['opcion']== 4)
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
									$html .= "  <td align=\"left\" width=\"50%\"><b>a la(s): $frecuencia<b></td>";
								}

							if($medi_form[$i]['unidad_tiempo_tratamiento']=='1')
							{
							  
							  $unidad_tra='AÑO(S)';
							
							}
							if($medi_form[$i]['unidad_tiempo_tratamiento']=='2')
							{
							$unidad_tra='MES(ES)';
							
							
							}
							if($medi_form[$i]['unidad_tiempo_tratamiento']=='3')
							{
							
							
							$unidad_tra='SEMANA(S)';
							}
							
							if($medi_form[$i]['unidad_tiempo_tratamiento']=='4')
							{
							
								$unidad_tra='DIA(S)';
							
							}
							$html .= "</tr>";
					    $html .= "        <tr class=\"$estilo\">\n";
							$html .= "          <td ><b>Duracion:<b></td>";
							$html .= "          <td colspan = \"2\" >".$medi_form[$i]['tiempo_tratamiento']." ".$unidad_tra."</td>";
							$html .= "        </tr>"; 
					   if($medi_form[$i]['unidad_periodicidad_entrega']=='1')
							{
						 
							   $unidad_perio='AÑO(S)';
							
							}
							if($medi_form[$i]['unidad_periodicidad_entrega']=='2')
							{
							$unidad_perio='MES(ES)';
							}
							if($medi_form[$i]['unidad_periodicidad_entrega']=='3')
							{
							$unidad_perio='SEMANA(S)';
							
							}
							if($medi_form[$i]['unidad_periodicidad_entrega']=='4')
							{
							
							 $unidad_perio='DIA(S)';
							}
										
							$html .= "        <tr class=\"$estilo\">\n";
							$html .= "          <td ><b>Periodicidad de entrega:</b></td>";
							$html .= "          <td colspan = \"2\" >".$medi_form[$i]['periodicidad_entrega']." ".$unidad_perio." </td>";
							$html .= "        </tr>";
							$html .= "     </table>";
							$html .= "    </td>";
							$html .= "  </tr>";
							
							/*$html .= "  <tr class=\"$estilo\">";
							$html .= "    <td colspan =15 class=\"$estilo\">";
							$html .= "      <table>";
							$html .= "        <tr class=\"$estilo\">";
							$html .= "          <td align=\"left\" width=\"50%\"><b>cantidad segun la periodicidad:<b></td>";
							$html .= "          <td align=\"left\" width=\"69%\">".$CantidaEntregar."</td>";
							$html .= "        </tr>";
							$html .= "      </table>";
							$html .= "   </td>";
							$html .= "  </tr>";*/
							
							
							$html .= "  <tr class=\"$estilo\">";
							$html .= "    <td colspan = 12 class=\"$estilo\">";
							$html .= "      <table>";
							$html .= "        <tr class=\"$estilo\">";
							$html .= "          <td align=\"left\" width=\"4%\"><b>Observacion:<b></td>";
							$html .= "          <td align=\"left\" width=\"69%\">".$medi_form[$i]['observacion']."</td>";
							$html .= "        </tr>";
							$html .= "      </table>";
							$html .= "   </td>";
							$html .= "  </tr>";
     					$html .= " </tr>";
							
					}
					else
					{
                  $html .= "  <tr class=\"$estilo\">";
                  $html .= "    <td colspan = 8>";
                  $html .= "      <table>";

                  $html .= "<tr class=\"$estilo\">";
                  $html .= "  <td colspan = 3 align=\"left\" width=\"9%\"><b>Cantidad: </b>".round($medi_form[$i]['cantidad'])."</td>";
                  $html .= "</tr>";

                  $tiempo_tratamiento=$medi_form[$i]['tiempo_tratamiento'];
					        $unidad_tiempo_tratamiento=$medi_form[$i]['unidad_tiempo_tratamiento'];

                  $html .= "<tr class=\"$estilo\">";
                  $html .= "  <td align=\"left\" width=\"9%\"><b>Tiempo Entrega:<b>".$tiempo_tratamiento." DIA(S)</td>";
                  $html .= "</tr>";
                  $html .= "      </table>";
                  $html .= "    </td>";
                  $html .= "  </tr>";
					}
			}	
				$html .= "</table><br>\n";
				$html .= "			</fieldset>\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table><br>\n";
			
		
		}
          $html .= "<center>";
          $html .= "	<table width=\"100%\" align=\"center\">\n";
          $html .= "		<tr>\n";
          $html .= "			<td>\n";
          $html .= "				<table width=\"100%\" align=\"center\">\n";
          $html .= "					<tr>\n";
          $html .= "						<td>\n";
          $html .= "							<div class=\"tab-pane\" id=\"creacion_asociacion_estadosdocumentos\">\n";
          $html .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"creacion_asociacion_estadosdocumentos\" )); </script>\n";
          $html .= "								<div class=\"tab-page\" id=\"crear_estadosdocumentos\">\n";
          $html .= "									<h2 class=\"tab\">FORMULACIO DE MEDICAMENTOS</h2>\n";
          $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"crear_estadosdocumentos\")); </script>\n";
        
          $html .= "<form name=\"registrar_cabecera\" id=\"registrar_cabecera\"    action=\"".$action['buscador']."\"   method=\"post\">\n";
          $html .= "<input type=\"hidden\" name=\"tipo_afiliado\" value=\"".$Datos_Paciente['tipo_afiliado_atencion']."\">\n";
          $html .= "<input type=\"hidden\" name=\"rango\" value=\"".$Datos_Paciente['rango_afiliado_atencion']."\">\n";
          $html .= "		<center>\n";
          $html .= "			<label id=\"error_actividad\" class=\"label_error\"></label>\n";
          $html .= "	  </center>\n";	

          $html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
          $html .= "	<tr>\n";
          $html .= "		<td>\n";
          $html .= "			<fieldset class=\"fieldset\">\n";
          $html .= "				<legend class=\"normal_10AN\">PRODUCTOS PARA LA FORMULACION</legend>\n";

          $html .= "	<table  align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"100%\">";
          $html .= "	<tr class=\"formulacion_table_list\" >";
          $html .= "	<td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE PRODUCTOS </td>";
          $html .= "	</tr>";
          $html .= "	<tr class=\"formulacion_table_list\" >";
          $html .= "	<td  colspan=\"1\">CODIGO:</td>";   
          $html .= "	<td width=\"15%\" class=\"modulo_list_oscuro\"  align='center'><input type='text' class='input-text' size =\"25\" maxlength =60	name = \"buscador[codigo]\" ></td>" ;
          $html .= "	<td width=\"15%\">DESCRIPCION:</td>";
          $html .= "	<td  colspan=\"3\"  class=\"modulo_list_oscuro\" align='center'><input type='text' size =\"80\" class='input-text' 	name =\"buscador[descripcion]\"   value =\"".$request['descripcion']."\"></td>" ;
          $html .= "	</tr>";
          $html .= "	<tr class=\"formulacion_table_list\" >";
          $html .= "	<td  colspan=\"1\">PRINCIPIO ACTIVO:</td>";
          $html .= "	<td   colspan=\"3\" class=\"modulo_list_oscuro\" align='center'><input type='text' size =\"80\" class='input-text' 	name =\"buscador[principio_activo]\"   value =\"".$request['producto']."\"></td>" ;
          $html .= "	<td class=\"modulo_list_oscuro\"  align=\"center\"><input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"BUSQUEDA\"></td>";
          $html .= "	</tr>";
          $html .= "	</table><br>";
          $html .= "		</form>\n";
          $html .= "<form name=\"registrar_dx\" id=\"registrar_dx\"    action=\"".$action['guardar']."\"   method=\"post\">\n";
		      if ($datos)
          {
                  $pghtml = AutoCarga::factory('ClaseHTML');
                  $html .= "<table  align=\"center\" border=\"0\" width=\"100%\">";
                  $html .= "<tr class=\"formulacion_table_list\">";
                  $html .= "<td align=\"center\" colspan=\"7\">RESULTADO DE LA BUSQUEDA</td>";
                  $html .= "</tr>";
                  $html .= "<tr class=\"formulacion_table_list\">";
                  $html .= "  <td width=\"8%\">CODIGO</td>";
                  $html .= "  <td width=\"8%\">MOLECULA</td>";
                  $html .= " <td width=\"60%\">PRODUCTO</td>";
                  $html .= " <td width=\"60%\">EXISTENCIAS</td>";
                  $html .= " <td width=\"17%\">INFORMACION</td>";
                  $html .= " <td width=\"5%\">OP</td>";
                  $html .= "</tr>";
         
                  foreach($datos as $key => $dtl)
                  {
                        $codigo= $dtl['codigo_producto'];
                        $codigo2= $dtl['codigo_producto_mini'];
                        $producto= $dtl['descripcion'];
                        $molecula= $dtl['molecula'];
                        $existencia= $dtl['existencia'];
                        if( $i % 2){$estilo='modulo_list_claro';}
                        else {$estilo='modulo_list_oscuro';}
                        $html .= "<tr class=\"$estilo\">";
                        $html .= "<td align=\"center\" width=\"8%\">$codigo2</td>";
                        $html .= "<td align=\"left\" width=\"45%\">$molecula</td>";
                        $html .= "<td align=\"left\" width=\"55%\">$producto</td>";
                        $html .= "<td align=\"left\" width=\"55%\">".round($existencia)."</td>";	
                       if($dtl['resultado']==0)
                       {
                         $html .= "<td align=\"center\" class=\"label_error\" width=\"17%\">NO PACTADO ";
                       
                       }else
                       
                       {
                         $html .= "<td align=\"center\"  width=\"17%\">PACTADO ";
                       
                       }
		 
                    $html .= "</td>";
                    $html .= "				<td align=\"center\"  >\n";
                    $html .= "				".$mostrar."\n";
                    $html .= "					<a href=\"".$action['formulacion'].URLRequest(array("codigo_medicamento"=>$codigo))."\"  class=\"label_error\"  ><img src=\"".GetThemePath()."/images/producto.png\" border='0' >\n";
                    $html .= "					</a>\n";
                    $html .= "			</td>\n";			$html .= "</tr>";
                  }
        
              $html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$i."\">";
              $html .= "</table><br>";
              $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
            }
            $html .= "			</fieldset>\n";
            $html .= "		</td>\n";
            $html .= "	</tr>\n";
            $html .= "</table>\n";
            $html .= "		</form>\n";
            
            $html .= "								</div>\n"; 

            $html .= "								<div class=\"tab-page\" id=\"asociar_estadosdocumentos_\">\n";
            $html .= "									<h2 class=\"tab\">INSUMOS</h2>\n";
            $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"asociar_estadosdocumentos_\")); </script>\n";
            $html .= "<form name=\"registrar_cabecera\" id=\"registrar_cabecera\"    action=\"".$action['buscador_2']."\"   method=\"post\">\n";
            $html .= "<input type=\"hidden\" name=\"tipo_afiliado\" value=\"".$Datos_Paciente['tipo_afiliado_atencion']."\">\n";
            $html .= "<input type=\"hidden\" name=\"rango\" value=\"".$Datos_Paciente['rango_afiliado_atencion']."\">\n";
            $html .= "<input type=\"hidden\" name=\"insumo_s\" value=\"1\">\n";
            $html .= "		<center>\n";
            $html .= "			<label id=\"error_actividad\" class=\"label_error\"></label>\n";
            $html .= "	  </center>\n";	

            $html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
            $html .= "	<tr>\n";
            $html .= "		<td>\n";
            $html .= "			<fieldset class=\"fieldset\">\n";
            $html .= "				<legend class=\"normal_10AN\">SOLICITUD DE INSUMOS</legend>\n";


            $html .= "	<table  align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"100%\">";
            $html .= "	<tr class=\"formulacion_table_list\" >";
            $html .= "	<td align=\"center\" colspan=\"12\">BUSQUEDA AVANZADA DE INSUMOS </td>";
            $html .= "	</tr>";
            $html .= "	<tr class=\"formulacion_table_list\" >";
            $html .= "	<td  colspan=\"1\">CODIGO:</td>";   
            $html .= "	<td width=\"15%\" class=\"modulo_list_oscuro\"  align='center'><input type='text' class='input-text' size =\"25\" maxlength =60	name = \"buscador_2[codigo]\" ></td>" ;
            $html .= "	<td width=\"15%\">DESCRIPCION:</td>";
            $html .= "	<td  colspan=\"3\"  class=\"modulo_list_oscuro\" align='center'><input type='text' size =\"80\" class='input-text' 	name =\"buscador_2[descripcion]\"   value =\"".$request['descripcion']."\"></td>" ;

            $html .= "	</tr>";
            $html .= "	</table>";
            $html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
            $html .= "	<tr   >";
            $html .= "	<td   align=\"center\"><input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"BUSQUEDA\"></td>";
            $html .= "	</tr>";
            $html .= "	</table><br>";
            $html .= "	</form>\n";
            $html .= "<form name=\"registrar_dx\" id=\"registrar_dx\"    action=\"".$action['guardar']."\"   method=\"post\">\n";
        
            if ($insumos)
            {
                $pghtml = AutoCarga::factory('ClaseHTML');
                $html .= "<table  align=\"center\" border=\"0\" width=\"100%\">";
                $html .= "<tr class=\"formulacion_table_list\">";
                $html .= "<td align=\"center\" colspan=\"7\">RESULTADO DE LA BUSQUEDA</td>";
                $html .= "</tr>";
                $html .= "<tr class=\"formulacion_table_list\">";
                $html .= "  <td width=\"8%\">CODIGO</td>";
                $html .= " <td width=\"35%\">PRODUCTO</td>";
                
                $html .= " <td width=\"15%\">CANTIDAD</td>";
                $html .= " <td width=\"15%\">EXISTENCIA</td>";
                $html .= " <td width=\"65%\">TIEMPO ENTREGA</td>";
                $html .= " <td width=\"10%\">INFORMACION</td>";
                $html .= " <td width=\"5%\">OP</td>";
                $html .= "</tr>";
         
              $con=0;
              foreach($insumos as $key => $dtl)
              {
                      $codigo= $dtl['codigo_producto'];
                      $codigo2= $dtl['codigo_producto_mini'];

                      $producto= $dtl['descripcion'];
                      $existencia= $dtl['existencia'];

                      if( $i % 2){$estilo='modulo_list_claro';}
                      else {$estilo='modulo_list_oscuro';}
                      $html .= "<tr class=\"$estilo\">";
                      $html .= "<td align=\"center\" width=\"8%\">$codigo2</td>";
                      $html .= "<td align=\"left\" width=\"35%\">$producto</td>";
                      $html .= "     <td>";
                      $html .= "      <input style=\"width:100%\" type=\"text\" onkeypress=\"return acceptNum(event);\"   class=\"input-text\" value=\"\" name=\"cantidad_formulada".$codigo."\" id=\"cantidad_formulada".$codigo."\" >";
                      $html .= "      </td>";
                      $html .= "<td align=\"left\" width=\"15%\">".round($existencia)."</td>";
                      $html .= "  <td  width=\"65%\"align=\"left\" >";
                      $html .= "    <select name = \"tiempo_total".$codigo."\" id = \"tiempo_total".$codigo."\" class=\"select\">";

                      for($dia=1;$dia<=180;$dia++)
                      {
                          $html .= "        <option value = '$dia' >$dia</option>";
			
                      }
                      $html .= "   </select>";
                      $html .= "     <select name = 'tiempo_total2' id = 'tiempo_total2'class=\"select\">";
                      $html .= "        <option value = '4' >DIA(S)</option>";
                      $html .= "     </select>";
                      $html .= "   </td>";
                      if($dtl['resultado']==0)
                      {
                            $html .= "<td align=\"center\" class=\"label_error\" width=\"17%\">NO PACTADO ";
                      }else
		   
                    {
                            $html .= "<td align=\"center\"  width=\"17%\">PACTADO ";
                    }
                    $html .= "</td>";
                    $html .= "      <td align=\"center\">\n";
                    $html .= "         <a href=\"#\" onclick=\"xajax_Guardartmp_ins_hospita('".$codigo."', '".$tmp_id."',document.getElementById('cantidad_formulada".$codigo."').value,document.getElementById('tiempo_total".$codigo."').value,'4','".$request['tipo_id_paciente']."','".$request['paciente_id']."','".$plan_id."','".$opcion."')\"  class=\"label_error\" ><img src=\"".GetThemePath()."/images/producto.png\" border='0' ></a>\n";
                    $html .= "      </td>\n";
                    
                    $con++;
                    }
                    $html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$i."\">";
                    $html .= "</table><br>";
                    $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
              }
            $html .= "			</fieldset>\n";
            $html .= "		</td>\n";
            $html .= "	</tr>\n";

            $html .= "</table>\n";
            $html .= "		</form>\n";
            $html .= "								</div>\n";		//CIERRO SEGUNDO TAB
            $html .= "							</div>\n";
            $html .= "						</td>\n";
            $html .= "					</tr>\n";
            $html .= "				</table>\n";
            $html .= "			</td>\n";
            $html .= "		</tr>\n";
            $html .= "  </table>\n"; //CIERRO TODOS LOS TABS
            $html .= "		</form>\n";
            $html .= "</center>";

            if(!empty($medi_form))
            {
            
             $disabled= " ";
            
            }else
            {
              $disabled= " disabled=true ";
            }
			
            $html .= "		<form name=\"forma\" action=\"".$action['finaliza_formulacion_real']."\" method=\"post\">\n";
            $html .= "<table  align=\"center\" border=\"0\" width=\"85%\">";
            $html .= "		<tr>\n";
            $html .= "		  <td align=\"center\"><br>\n";
            $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" $disabled  value=\"FINALIZAR FORMULACION \">\n";
            $html .= "		  </td>";
            $html .= "		</tr>\n";
            $html .= "  </table>\n";
            $html .= "		</form>\n";
            
            $html .= "</table>";
            $html .= "<table align=\"center\">\n";
            $html .= "<br>";
            $html .= "  <tr>\n";
            $html .= "      <td align=\"center\" class=\"label_error\">\n";
            $html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
            $html .= "      </td>\n";
            $html .= "  </tr>\n";
            $html .= "</table>\n";
            if($tope['saldo_tope']<= $tope['saldo_minimo'])
            {
                $html .= "<script>";
                $html .= "alert('SALDO MINIMO PARA  EL ESTABLECIMIENTO DE SANIDAD MILITAR ');";
                $html .= "</script>";
            }
          $html .= ThemeCerrarTabla();	
        }else
        {  
		 	        $html .= "<form name=\"forma_med\" id=\"forma_med\" action=\"".$action['guardar_formula']."\" method=\"post\">";
              $html .= "<table  align=\"center\" border=\"0\"  class=\"modulo_table_list\" width=\"80%\">";
              $html .= "<tr class=\"formulacion_table_list\">";
              $html .= " <td align=\"center\" colspan=\"6\">FORMULACION DEL MEDICAMENTO</td>";
              $html .= "</tr>";
              $html .= "<tr class=\"formulacion_table_list\">";
              $html .= " <td align=\"center\" width=\"5%\">CODIGO</td>";
              $html .= " <td align=\"center\" width=\"23%\">PRODUCTO</td>";
              $html .= "  <td align=\"center\" width=\"23%\">PRINCIPIO ACTIVO</td>";
              $html .= "  <td align=\"center\" width=\"23%\">CONCENTRACION</td>";
              $html .= " <td align=\"center\" width=\"15%\">FORMA</td>";
              $html .= "</tr>";

              if( $i % 2){ $estilo='modulo_list_claro';}
              else {$estilo='modulo_list_oscuro';}
              $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
              $html .= "<tr class=\"modulo_list_claro\">";
              
              $html .= "<td align=\"center\" width=\"5%\">".$medicamento_Datos['codigo_producto']."</td>";
              $html .= "<td align=\"center\" width=\"23%\" >".$medicamento_Datos['descripcion_prod']."</td>";
              $html .= "<td align=\"center\" width=\"23%\" >".$medicamento_Datos['principio_activo']."</td>";
              $html .= "<td align=\"center\" width=\"15%\" >".$medicamento_Datos['concentracion_forma_farmacologica']." </td>";
              $html .= "<td align=\"center\" width=\"15%\" >".$medicamento_Datos['forma']."</td>";

              $html .= "</tr>";
              $html .= "</table><br>";
              $html .= "   <input type='hidden' name='codigo_medicamento' value=\"".$medicamento_Datos['codigo_producto']."\">";
              $html .= "   <input type='hidden' name='guardar_formula_' value=\"1\">";
	
              $s = array();
              $s[$_REQUEST['tiempo_total']] = "selected";
          
              $html .= "<table  align=\"center\" border=\"0\"   class=\"modulo_table_list\"  width=\"80%\">";
              $html .= "<tr class=\"formulacion_table_list\">";
              $html .= "  <td width=\"24%\"  align=\"left\" >TIEMPO TOTAL TRATAMIENTO</td>";
              $html .= "  <td class='modulo_list_claro' width=\"26%\"align=\"left\" >";
              $html .= "    <select name = 'tiempo_total' class=\"select\">";
              for($r=1;$r<=180;$r++)
              {
                  $html .= "        <option value = '$r' ".$s[$r].">$r</option>";
              }
              $html .= "   </select>";
              $s = array();
              $s[$_REQUEST['tiempo_total2']] = "selected";

            $html .= "     <select name = 'tiempo_total2' class=\"select\">";
            $html .= "        <option value = '4' ".$s[1].">DIA</option>";
            $html .= "     </select>";
            $html .= "   </td>";
            $a = "";

            $s = array();
            $s[$_REQUEST['perioricidad_entrega']] = "selected";

          $html .= "<tr class=\"formulacion_table_list\">";
          $html .= " <td align=\"left\" ><label href='#'>PERIORICIDAD DE ENTREGA DEL MEDICAMENTO</label></td>";
          $html .= " <td class='modulo_list_claro' width=\"26%\"align=\"left\" COLSPAN='3' >";
          $html .= "   <select name = 'perioricidad_entrega' class=\"select\">";
          
          for($k=1;$k<=180;$k++)
          {
              $html .= "      <option value = '$k' ".$s[$k].">$k</option>";
          }
          $html .= "   </select>";
          $html .= "    &nbsp;";
        
        $s = array();
        $s[$_REQUEST['perioricidad_entrega']] = "selected";
        $html .= "    <select name = 'perioricidad_entrega2' class=\"select\">";
        $html .= "      <option value = '4' ".$s[1].">DIA</option>";
        $html .= "   </select>";
        $html .= "  </td>";
        $html .= "</tr>";
        $html .= "</table>";
            
        $html .= "<table  align=\"center\" border=\"0\"   class=\"modulo_table_list\"  width=\"80%\">";
        $html .= "<tr class=\"formulacion_table_list\">";
        $html .= "<td class=\"formulacion_table_list\"  width=\"20%\"align=\"left\" >VIA DE ADMINISTRACION</td>";
        $html .= " <td  class='modulo_list_claro' width=\"60%\" align = left >";				
						
        if ((sizeof($via_admon)>0))
        {      
                $medicamento_Datos['unidad_dosificacion'];
			 
                  if	(empty($medicamento_Datos['unidad_dosificacion']))
                  {
                        $EventoOnclick="OnChange='UnidadPorVia(document.forma_med)'";
                  }
                  else
                  {
                      $EventoOnclick="";
                  }

                $html .= " \n\n<select name = 'via_administracion'  class =\"select\" $EventoOnclick>";
                $html .= " <option value = '-1' selected>-Seleccione-</option>";

                $javita.="<script>\n";
                $javita.="function UnidadPorVia(forma) {\n";
                $javita.="if (forma.via_administracion.value=='-1') {\n";
                $javita.="  document.forma_med.unidad_dosis.length=0;\n";
                $javita.="}\n\n";
              
              for($i=0;$i<sizeof($via_admon);$i++)
              {

                  $html .= "<option value = ".$via_admon[$i]['via_administracion_id']." selected >".$via_admon[$i]['nombre']."</option>";
              }
			
              if(empty($medicamento_Datos['unidad_dosificacion']))
              {
                  $javita.="if (forma.value=='".$via_admon[$i]['via_administracion_id']."') {\n";
                  $unidadesViaAdministracion = $obje->GetunidadesViaAdministracion($via_admon[$i]['via_administracion_id']);
                  $javita.="document.forma_med.unidad_dosis.length=".count($unidadesViaAdministracion)."\n";
                  for($cont=0;$cont<count($unidadesViaAdministracion);$cont++)
                  {
                      $javita.="document.forma_med.unidad_dosis.options[".$cont."]= new Option('".$unidadesViaAdministracion[$cont]['unidad_dosificacion']."','".$unidadesViaAdministracion[$cont]['unidad_dosificacion']."');\n";
                  }
                  $javita.="}\n\n";
              }
            }
              $javita.="}\n\n";
              $javita.="</script>\n";
              $html .= " </select>\n\n";
              $html .= " </td>";
              $html .= " </tr>";
              $ComboUnidadDosis ="<select size = 1 name = 'unidad_dosis'  class =\"select\">";
              if	(!empty($medicamento_Datos['unidad_dosificacion']))
              {
                       $unidadesViaAdministracion = $obje->Unidades_Dosificacion();
                    
                    $unidadesViaAdministracion = $obje->Unidades_Dosificacion();
                    $ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
                    for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
                    {
                      $ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i]['unidad_dosificacion']."'>".$unidadesViaAdministracion[$i]['unidad_dosificacion']."</option>";
                    
                    }  
              }
            else
            {
                if ((sizeof($via_admon)==1))
                {
                    $unidadesViaAdministracion = $obje->GetunidadesViaAdministracion($via_admon[$i]['via_administracion_id']);
                    $ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
                    for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
                    {
                        $ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i]['unidad_dosificacion']."'>".$unidadesViaAdministracion[$i]['unidad_dosificacion']."</option>";
                    }
                }
              if (empty($via_admon))
              {
                  $unidadesViaAdministracion = $obje->Unidades_Dosificacion();
                  $ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
                  for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
                  {
                    $ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i]['unidad_dosificacion']."'>".$unidadesViaAdministracion[$i]['unidad_dosificacion']."</option>";
                  
                  }
              }
          }
          $ComboUnidadDosis.="</select>";

          $html .= " <tr class=\"formulacion_table_list\" >";
          $html .= " <td  width=\"20%\" align = left >DOSIS</td>";
          $html .= " <td class='modulo_list_claro'  width=\"60%\" align = left >";
          $html .= " <table>";
          $html .= "<tr class=\"formulacion_table_list\"> ";
          $html .= "<td  class='modulo_list_claro' width=\"15%\" align='left' ><input type='text' class='input-text' size = 15 name = 'dosis'   value =\"1\"></td>" ;
          //unidades de dosificacion
          $html .= "<td  class='modulo_list_claro' width=\"35%\"  align = left >";
          //si no trae unidad de dosificacion segun la forma del producto pinta combo de vias interactivo
        if	(empty($medicamento_Datos['unidad_dosificacion']))
        {
            $html .= $javita;
      //este es el if nuevo que coloque para cargar unidades
            if ((sizeof($via_admon)>=1))
            {
              $ComboUnidadDosis ="<select size = 1 name = 'unidad_dosis'  class =\"select\">";
              $unidadesViaAdministracion = $obje->GetunidadesViaAdministracion($via_admon[$i]['via_administracion_id']);
       
              $ComboUnidadDosis.="<option value = '-1' selected >--Seleccione--</option>";
              for($i=0;$i<sizeof($unidadesViaAdministracion);$i++)
              {
                
                  $ComboUnidadDosis.="<option value = '".$unidadesViaAdministracion[$i][unidad_dosificacion]."'>".$unidadesViaAdministracion[$i][unidad_dosificacion]."</option>";
                
              }
              $ComboUnidadDosis.="</select>";
            }
          //fin del evento nuevo
        }
        $html .= "$ComboUnidadDosis";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table>";
        $html .= "</td>";
        $html .= "</tr>";
          
              //horario
        $html .= "<tr class=\"formulacion_table_list\">";
        $html .= "<td width=\"20%\"  class=\"formulacion_table_list\" align=\"left\" >FRECUENCIA</td>";
        $html .= "<td class=\"modulo_list_claro\" width=\"60%\" align = left >";
        $html .= "<table border = 0 >";

    //opcion 1
				$html .= "<tr class=\"modulo_list_claro\">";
        $html .= "<td width=\"10%\"  class=\"modulo_list_claro\"  align=\"left\" ><input type =\"radio\" checked=\"true\" name= 'opcion'   value = 1>OPCION 1</td>";
				$html .= "<td width=\"50%\"align=\"left\" >";
				$html .= "<table>";
				$html .= "<tr class=\"modulo_list_claro\">";
				$html .= "         <td width=\"10%\" align = left >CADA</td>";
			  $cada_periocidad = $obje->Cargar_Periocidad();
				$html .= "          <td width=\"10%\" align = left >";
				$html .= "         <select size = 1 name = 'periocidad'  class =\"select\">";
				//$html .= "           <option value = '-1' selected>-Seleccione-</option>\n";
				$s = "";
				for($i=0;$i<sizeof($cada_periocidad);$i++)
				{
				($_REQUEST['periocidad'] == $cada_periocidad[$i][periocidad_id])? $s = "selected":$s ="";
				$html .= "             <option value = ".$cada_periocidad[$i][periocidad_id]." ".$s.">".$cada_periocidad[$i][periocidad_id]."</option>";
				}
				$html .= "            </select>\n";
				$html .= "       </td>\n";
				$html .= "         <td width=\"30%\" align = 'left' >\n";
				$html .= "          <select size = 1 name = 'tiempo'  class =\"select\">\n";
				$html .= "             <option value = 'Hora(s)' >Hora(s)</option>\n";
				//opcion de minutos
				$html .= "             <option value = 'Min' >Min</option>\n";
				$html .= "             <option value = 'Dia(s)' >Dia(s)</option>\n";
				//opcion de semanas
				$html .= "             <option value = 'Semana(s)' >Semana(s)</option>\n";
				$html .= "           </select>\n";
				$html .= "          </td>\n";
				$html .= "       </tr>\n";
				$html .= "      </table>\n";
				$html .= "    </td>";
				$html .= "  </tr>";
        //OPCION 2
				$html .= "   <tr class=\"modulo_list_claro\">";
				$html .= "  <td width=\"10%\" class=\"modulo_list_claro\" align=\"left\" ><input type = radio  name= 'opcion' value = 2>OPCION 2</td>";
				$html .= "  <td width=\"50%\"align=\"left\" >";
				$html .= "  <table>";
				$html .= "  <tr class=\"modulo_list_claro\">";
				$horario = $obje->horario();
				$html .= "  <td class=\"modulo_list_claro\" width=\"20%\"align=\"left\" >&nbsp;</td>";
				$html .= "  <td width=\"60%\" align = left >";
				$html .= "  <select size = 1 name = 'duracion'  class =\"select\">";
				$html .= "  <option value = -1 selected>-Seleccione-</option>";

				for($i=0;$i<sizeof($horario);$i++)
				{
					
					$html .= "  <option value = ".$horario[$i][duracion_id].">".$horario[$i][descripcion]."</option>";
				}
				$html .= "  </select>";
				$html .= "  </td>";
				$html .= "  </tr>";
				$html .= "  </table>";
				$html .= "  </td>";
				$html .= "  </tr>";
				
				$html .= "  <tr class=\"modulo_list_claro\">";
				$html .= " <td width=\"10%\"class=\"modulo_list_claro\" align=\"left\" ><input type = radio  name= 'opcion' value = 3>OPCION 3</td>";
				$html .= " <td width=\"50%\"align=\"left\" >";
				$html .= " <table>";
				$html .= " <tr class=\"modulo_list_claro\">";
				$html .= "  <td width=\"60%\" align = left >";
				$html .= "  <select size = 1 name = 'durante_tratamiento_'  class =\"select\">";
				$html .= "  <option value =\"1\">DURANTE EL TRATAMIENTO</option>";
				
				$html .= "  </select>";
				$html .= "  </td>";
				$html .= " </tr>";
						
				$html .= " </table>";
				$html .= " </td>";
				$html .= " </tr>";
								
//opcion 3/*
/*				$html .= "  <tr class=\"modulo_list_claro\">";
				$html .= " <td width=\"10%\"class=\"modulo_list_claro\" align=\"left\" ><input type = radio  name= 'opcion' value = 3>OPCION 3</td>";
				$html .= " <td width=\"50%\"align=\"left\" >";
				$html .= " <table>";
				$html .= " <tr class=\"modulo_list_claro\">";
        $html .= " <td width=\"15%\" align = left ><input type = radio name= 'momento' checked value = '1'>ANTES</td>";
        $html .= " <td width=\"15%\" align = left ><input type = radio name= 'momento' checked value = '2'>DURANTE</td>";
        $html .= " <td width=\"20%\" align = left ><input type = radio name= 'momento' checked value = '3'>DESPUES</td>";
				$html .= " </tr>";
				$html .= " <tr class=\"modulo_list_claro\">";
				$html .= " <td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'desayuno'  value = '1'>DESAYUNO</td>";
				$html .= " </tr>";
				$html .= " <tr class=\"modulo_list_claro\">";
				$html .= " <td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'almuerzo'  value = '1'>ALMUERZO</td>";
				$html .= " </tr>";
				$html .= " <tr class=\"modulo_list_claro\">";
				$html .= " <td  colspan = 3 width=\"50%\" align = left ><input type = checkbox name= 'cena'  value = '1'>CENA</td>";
				$html .= " </tr>";
				$html .= " </table>";
				$html .= " </td>";
				$html .= " </tr>";*/

				//OPCION 4
				/*$html .= "<tr class=\"modulo_list_claro\">";
				
				$html .= "<td width=\"10%\" class=\"modulo_list_claro\" align=\"left\" ><input type = radio  name= 'opcion' value = 4>OPCION 4</td>";
				
				$html .= "<td width=\"50%\"align=\"left\" >";
				$html .= "<table>";
				$html .= "<tr class=\"modulo_list_claro\">";
				$html .= "<td colspan = 8 width=\"50%\" align = left >HORA ESPECIFICA</td>";
				$html .= "</tr>";
				$html .= "<tr class=\"modulo_list_claro\">";
				$html .= "<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  name= 'opH[6]' value = '06 am'>06</td>";
				
				$html .= "<td colspan = 1 width=\"5%\" align = left ><input type = checkbox name= 'opH[9]' value = '09 am'>09</td>";
				
				$html .= "<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  name= 'opH[12]' value = '12 pm'>12</td>";

				$html .= "<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  name= 'opH[15]' value = '03 pm'>15</td>";

				$html .= "<td colspan = 1 width=\"5%\" align = left ><input type = checkbox  name= 'opH[18]' value = '06 pm'>18</td>";

				$html .= "<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox  name= 'opH[21]' value = '09 pm'>21</td>";

				$html .= "<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[24]' value = '00 am'>24</td>";

				$html .= "<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox   name= 'opH[3]' value = '03 am'>03</td>";

     			$html .= "</tr>";

				$html .= "<tr class=\"modulo_list_claro\">";
			
				$html .= "<td colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[7]' value = '07 am'>07</td>";
				$html .= "<td colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[10]' value = '10 am'>10</td>";
				$html .= "<td colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[13]' value = '01 pm'>13</td>";
				$html .= "<td colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[16]' value = '04 pm'>16</td>";
				$html .= "<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[19]' value = '07 pm'>19</td>";
				$html .= "<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[22]' value = '10 pm'>22</td>";
				$html .= "<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[1]' value = '01 am'>01</td>";
				$html .= "<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox   name= 'opH[4]' value = '04 am'>04</td>";
				$html .= "</tr>";
				$html .= "<tr class=\"modulo_list_claro\">";
     			$html .= "<td colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[8]' value = '08 am'>08</td>";
				$html .= "<td colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[11]' value = '11 am'>11</td>";
				$html .= "<td colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[14]' value = '02 pm'>14</td>";
				$html .= "<td colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[17]' value = '05 pm'>17</td>";
				$html .= "<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[20]' value = '08 pm'>20</td>";
				$html .= "<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[23]' value = '11 pm'>23</td>";
				$html .= "<td class =label_error colspan = 1 width=\"5%\" align = left ><input type = checkbox   name= 'opH[2]' value = '02 am'>02</td>";
				$html .= "<td class =label_error colspan = 1 width=\"15%\" align = left ><input type = checkbox   name= 'opH[5]' value = '05 am'>05</td>";
				$html .= "</tr>";
				$html .= "</table>";
				$html .= "</td>";
				$html .= "</tr>";*/
	    	$html .= "</table>";
				$html .= "</td>";
				$html .= "</tr>";
				
				$html .= "<tr >";
				$html .= "<td class=\"formulacion_table_list\" width=\"5%\" align = left >CANTIDAD</td>";
				$html .= "<td  class=\"modulo_list_claro\" width=\"60%\" align = left >";
				$html .= "<table>";
				$html .= "<tr >";
				$html .= "<td  class=\"modulo_list_claro\"  width=\"5%\" align='left' >";
        $html .= "    <td  class=\"modulo_list_claro\" class =label name=\"cantidadp\" id=\"cantidadp\" align=\"center\" width=\"5%\"></td>";
				$html .= "   <input type='hidden' name='cantidad' value=\"".$_REQUEST['cantidad']."\">";

				$html .= "    <input type='hidden' name='cantidadperiocidad' value=\"".$_REQUEST['cantidadperiocidad']."\">";
				if(!($_REQUEST['cantidadperiocidad']))
				{
				SessionSetVar("periocidad",1); 
				}
				else
				{
				SessionSetVar("periocidad","");
				}
				$html .= " </td>" ;
				$unidad_venta = $obje->Unidad_Venta($medicamento_Datos['codigo_producto']);
				$frase = ' ';
				if ($unidad_venta['contenido_unidad_venta']!='')
				{
				$frase = ' por ';
				}
				$html .= " <td width=\"30%\" align='left' >";
				$html .= "   <input type='text' class='input-text' readonly size = 30 name = 'unidad'   value = \"".$unidad_venta[descripcion]."\">";
				$html .= " </td>" ;
				$html .= " <td  width=\"20%\" align='left' >";
				$html .= " <input class=\"input-submit\" type=\"button\" value=\"CALCULAR\" onclick=\"CalcularCantidad()\">\n";
				$html .= " </td>" ;
				$html .= " </tr>";
				$html .= " </table>";
				$html .= " </td>";
				$html .= " </tr>";
				
				$html .= "  <tr  >\n";
				$html .= "    <td  class=\"formulacion_table_list\" colspan=\"2\" >OBSERVACIONES E INDICACION DE SUMINISTRO</td>\n";
				$html .= "  </tr>\n";
				$html .= "  <tr >\n";
				$html .= "   <td colspan=\"2\">\n";
				$html .= "      <input type=\"hidden\" name = \"cantidad_dia\" value=\"".$_REQUEST['cantidad_dia']."\">";
				$html .= "     <textarea style = \"width:100%\" class=\"textarea\" name = \"observacion\" rows=\"3\">";
				if (($_REQUEST['observacion'])  == '')
				$html .=  $observacion;
				else
				$html .=  $_REQUEST['observacion'] ;

				$html .= "</textarea>\n";
				$html .= "    </td>\n";
				$html .= "  </tr>\n";
				
				$html .= "    </td>\n";
				$html .= "</tr>";
				$html .= "</table>";

        $conversion = $obje->ObtenerFactorConversion($unidad_venta['codigo_producto'],$unidad_venta['unidad_id']);
          
        $cl = AutoCarga::factory("ClaseUtil");
        $html .=  $cl->IsNumeric();

				$html .= "<script>";   
				$html .= "function CalcularCantidad()\n ";
				$html .= "{\n";
				$html .= "  var cantidadTotalp4 = 0;\n";
				$html .= "  var cantidadTotal4 = 0;\n";
				$html .= "  dosiscantidad=document.forma_med.dosis;\n ";
				$html .= " dosis=document.forma_med.unidad_dosis;\n";
				$html .= "  opcionva=document.forma_med.opcion;\n ";
				$html .= "  tiempototal=document.forma_med.tiempo_total;\n";
				$html .= "  tiempo=document.forma_med.tiempo_total2;\n";
				$html .= "  periocidad=document.forma_med.perioricidad_entrega.value;\n";
				$html .= "  periocidadtiempo=document.forma_med.perioricidad_entrega2.value;\n";
				//$html .= "  desayuno=document.forma_med.desayuno.checked;\n";
				//$html .= "  almuerzo=document.forma_med.almuerzo.checked;\n";
				//$html .= " cena=document.forma_med.cena.checked;\n";
				/*$html .= "  Am06=document.getElementsByName('opH[6]')[0].checked;\n";
				$html .= "  Am09=document.getElementsByName('opH[9]')[0].checked;\n";
				$html .= "  Pm12=document.getElementsByName('opH[12]')[0].checked;\n";
				$html .= "  Pm03=document.getElementsByName('opH[15]')[0].checked;\n";
				$html .= "  Pm06=document.getElementsByName('opH[18]')[0].checked;\n";
				$html .= " Pm09=document.getElementsByName('opH[21]')[0].checked;\n";
				$html .= "  Am00=document.getElementsByName('opH[24]')[0].checked;\n";
				$html .= " Am03=document.getElementsByName('opH[3]')[0].checked;\n";
				$html .= "  Am07=document.getElementsByName('opH[7]')[0].checked;\n";
				$html .= "  Am10=document.getElementsByName('opH[10]')[0].checked;\n";
				$html .= "  Pm01=document.getElementsByName('opH[13]')[0].checked;\n";
				$html .= "  Pm04=document.getElementsByName('opH[16]')[0].checked;\n";
				$html .= " Pm07=document.getElementsByName('opH[19]')[0].checked;\n";
				$html .= "  Pm10=document.getElementsByName('opH[22]')[0].checked;\n";
				$html .= "  Am01=document.getElementsByName('opH[1]')[0].checked;\n";
				$html .= " Am04=document.getElementsByName('opH[4]')[0].checked;\n";
				$html .= "  Am08=document.getElementsByName('opH[8]')[0].checked;\n";
				$html .= "  Am11=document.getElementsByName('opH[11]')[0].checked;\n";
				$html .= "  Pm02=document.getElementsByName('opH[14]')[0].checked;\n";
				$html .= "  Pm05=document.getElementsByName('opH[17]')[0].checked;\n";
				$html .= "  Pm08=document.getElementsByName('opH[20]')[0].checked;\n";
				$html .= "  Pm11=document.getElementsByName('opH[23]')[0].checked;\n";
				$html .= "  Am02=document.getElementsByName('opH[2]')[0].checked;\n";
				$html .= " Am05=document.getElementsByName('opH[5]')[0].checked;\n";*/
				$html .= "  numf=tiempototal.value;\n";
				$html .= "  var conversion = new Array();\n"; 
				$html .= "  totaldelT = 0;\n";
				$html .= "  if(tiempo.value==2)\n";
				$html .= "  {\n";
				$html .= "	    f1 = new Date('".date("Y/m/d")."');\n";
				$html .= "	    f2 = new Date(".date("Y")."+'/'+(".date("m")."*1+numf*1)+'/'+".date("d").");\n";
				$html .= "     ftotal=f2-f1;\n";
				$html .= "      fec=ftotal/86400000;\n";
				$html .= "     totaldelT = fec;\n";
				$html .= "  }\n";
				$html .= "  if(tiempo.value==1)\n";
				$html .= "  {\n";
				$html .= "	    f1 = new Date('".date("Y/m/d")."');\n";
				$html .= "	    f2 = new Date((".date("Y")."*1+numf*1)+'/'+".date("m")."+'/'+".date("d").");\n";
				$html .= "      ftotal=f2-f1;\n";
				$html .= "      fec=ftotal/86400000;\n";
				$html .= "     totaldelT = fec;\n";
				$html .= "  }\n";
				$html .= " if(tiempo.value==3)\n";
				$html .= "  {\n";
				$html .= "   totaldelT = tiempototal.value*7;\n";
				$html .= "  }\n";
				$html .= "  if(tiempo.value==4)\n";
				$html .= "  {\n";
				$html .= "    totaldelT = tiempototal.value*1;\n";
				$html .= "  }\n";
				$html .= "  periocidadT = 0;\n";
				$html .= " numf = periocidad;\n";
				$html .= "  if(periocidadtiempo==2)\n";
				$html .= "  {\n";
				$html .= "  f1 = new Date('".date("Y/m/d")."');\n";
				$html .= "	  f2 = new Date(".date("Y")."+'/'+(".date("m")."*1+numf*1)+'/'+".date("d").");\n";
				$html .= "    ftotal=f2-f1;\n";
				$html .= "    fec=ftotal/86400000;\n";
				$html .= "   periocidadT = fec;\n";
				$html .= " }\n";
				$html .= "  if(periocidadtiempo==1)\n";
				$html .= "  {\n";
				$html .= "	  f1 = new Date('".date("Y/m/d")."');\n";
				$html .= "	  f2 = new Date((".date("Y")."*1+numf*1)+'/'+".date("m")."+'/'+".date("d").");\n";
				$html .= "    ftotal=f2-f1;\n";
				$html .= "   fec=ftotal/86400000;\n";
				$html .= "    periocidadT = fec;\n";
				$html .= "  }\n";
				$html .= "  if(periocidadtiempo==3)\n";
				$html .= "  {\n";
				$html .= "    periocidadT = periocidad*7;\n";
				$html .= "  }\n";
				$html .= " if(periocidadtiempo==4)\n";
				$html .= "  {\n";
				$html .= "   periocidadT = periocidad*1;\n";
				$html .= "  }\n";
				foreach($conversion as $key => $dtl)
				{
				$html .= "  conversion[".$key."] = new Array();\n ";
				$html .= "  conversion[".$key."][0] = '".$dtl['unidad_dosificacion']."'; \n";
				$html .= "  conversion[".$key."][1] = '".$dtl['factor_conversion']."'; \n";
				}
				$html .= "    factor=1;\n ";
				$html .= "    for(i=0;i<conversion.length;i++)\n ";
				$html .= "    {\n ";
				$html .= "     if(conversion[i][0] == dosis.value)\n ";
				$html .= "       factor = conversion[i][1]*1;\n";
				$html .= "    }\n ";
				/*$html .= "  if(opcionva[2].checked==true)\n;";
				$html .= "  {\n";
				$html .= "    if(desayuno == true)\n";
				$html .= "    {\n";
				$html .= "       d=1;\n";
				$html .= "    }\n";
				$html .= "    else\n";
				$html .= "    {\n";
				$html .= "       d=0;\n";
				$html .= "   }\n";
				$html .= "    if(almuerzo == true)\n";
				$html .= "    {\n";
				$html .= "       a=1;\n";
				$html .= "    }\n";
				$html .= "   else\n";
				$html .= "   {\n";
				$html .= "     a=0;\n";
				$html .= "    }\n";
				$html .= "  if(cena == true)\n";
				$html .= "   {\n";
				$html .= "       c=1;\n";
				$html .= "    }\n";
				$html .= "   else\n";
				$html .= "   {\n";
				$html .= "      c=0;\n";
				$html .= "   }\n"; 
				$html .= "  totalop = d+a+c;\n";
				$html .= "     cantidadpr = dosiscantidad.value*totalop*totaldelT;\n";
				$html .= "     cantidad2 = cantidadpr;\n";
				$html .= "    cantidadTotal2 = (Math.round(cantidad2));\n";
				$html .= "     document.forma_med.cantidad.value = cantidadTotal2;\n ";
				$html .= "     document.getElementById('cantidadp').innerHTML=cantidadTotal2;\n";
				$html .= "   if(periocidadtiempo!='0' && periocidad!='0')\n";
				$html .= "   {\n";
				$html .= "     cantidadpre = dosiscantidad.value*totalop*periocidadT;\n";
				$html .= "      cantidadp2 = cantidadpre/factor;\n";
				$html .= "     cantidadTotalp2 = (Math.round(cantidadp2));\n";
				$html .= "      document.forma_med.cantidadperiocidad.value = cantidadTotalp2;\n ";
				$html .= "    }\n";
				$html .= "  }\n";
				$html .= "  if(opcionva[3].checked==true)\n";
				$html .= " {\n";
				$html .= "    totalhoras = 0;\n";
				$html .= "    if(Am06 == true)\n";
				$html .= "     totalhoras++;\n";
				$html .= "    if(Am09 == true)\n";
				$html .= "      totalhoras++;\n";
				$html .= "    if(Pm12 == true)\n";
				$html .= "    totalhoras++;\n";
				$html .= "  if(Pm03 == true)\n";
				$html .= "      totalhoras++;\n";
				$html .= "    if(Pm06 == true)\n";
				$html .= "     totalhoras++;\n";
				$html .= "   if(Pm09 == true)\n";
				$html .= "      totalhoras++;\n";
				$html .= "   if(Am00 == true)\n";
				$html .= "     totalhoras++;\n";
				$html .= "    if(Am03 == true)\n";
				$html .= "     totalhoras++;\n";
				$html .= "  if(Am07 == true)\n";
				$html .= "      totalhoras++;\n";
				$html .= "    if(Am10 == true)\n";
				$html .= "     totalhoras++;\n";
				$html .= "   if(Pm01 == true)\n";
				$html .= "      totalhoras++;\n";
				$html .= "    if(Pm04 == true)\n";
				$html .= "    totalhoras++;\n";
				$html .= " if(Pm07 == true)\n";
				$html .= "     totalhoras++;\n";
				$html .= "    if(Pm10 == true)\n";
				$html .= "     totalhoras++;\n";
				$html .= "   if(Am01 == true)\n";
				$html .= "    totalhoras++;\n";
				$html .= "    if(Am04 == true)\n";
				$html .= "     totalhoras++;\n";
				$html .= "   if(Am08 == true)\n";
				$html .= "      totalhoras++;\n";
				$html .= "   if(Am11 == true)\n";
				$html .= "      totalhoras++;\n";
				$html .= "  if(Pm02 == true)\n";
				$html .= "    totalhoras++;\n";
				$html .= "  if(Pm05 == true)\n";
				$html .= "     totalhoras++;\n";
				$html .= "   if(Pm08 == true)\n";
				$html .= "     totalhoras++;\n";
				$html .= "    if(Pm11 == true)\n";
				$html .= "     totalhoras++;\n";
				$html .= "    if(Am02 == true)\n";
				$html .= "     totalhoras++;\n";
				$html .= "   if(Am05 == true)\n";
				$html .= "      totalhoras++;\n";
				$html .= "    cantidadhoras = dosiscantidad.value*totalhoras*totaldelT;\n";
				$html .= "   cantidad3= cantidadhoras;\n";
				$html .= "    cantidadTotal3 = (Math.round(cantidad3));\n";
				$html .= "   document.forma_med.cantidad.value = cantidadTotal3;\n ";

				$html .= "    document.getElementById('cantidadp').innerHTML=cantidadTotal3;\n";
				$html .= "  if(periocidadtiempo!='0' && periocidad!='0')\n";
				$html .= "  {\n";
				$html .= "    cantidadhorasp = dosiscantidad.value*totalhoras*periocidadT;\n";
				$html .= "   cantidadp3= cantidadhorasp/factor;\n";
				$html .= "   cantidadTotalp3 = (Math.round(cantidadp3));\n";

				$html .= "   document.forma_med.cantidadperiocidad.value = cantidadTotalp3;\n ";
				$html .= "  }\n";
				$html .= " }\n";*/
				$html .= "  if(opcionva[2].checked==true)\n";
				$html .= " {\n";
				
				$html .= "    cantidadTotal3 = (Math.round(document.forma_med.dosis.value));\n";
				//$html .= " alert(cantidadTotal3);\n";
				$html .= "   document.forma_med.cantidad.value = cantidadTotal3;\n ";
				$html .= "    document.getElementById('cantidadp').innerHTML=cantidadTotal3;\n";
				/*$html .= "  if(periocidadtiempo!='0' && periocidad!='0')\n";
				$html .= "  {\n";
				$html .= "    cantidadhorasp = dosiscantidad.value*totalhoras*periocidadT;\n";
				$html .= "   cantidadp3= cantidadhorasp/factor;\n";
				$html .= "   cantidadTotalp3 = (Math.round(cantidadp3));\n";

				$html .= "   document.forma_med.cantidadperiocidad.value = cantidadTotalp3;\n ";
				$html .= "  }\n";9*/
				$html .= " }\n";
								
				$html .= "  elem = opcionva[0].checked;\n";  
				$html .= "  if(elem==true)\n";
				$html .= " {\n";
				$html .= "   fintensidad=document.forma_med.tiempo;\n ";
				
				$html .= "   fnumero=document.forma_med.periocidad;\n ";
				
				$html .= "   if(!IsNumeric(dosiscantidad))\n ";
				$html .= "     return 0;\n ";
				$html .= "    if(fnumero == '' || fintensidad == '')\n ";
				$html .= "      return 0;\n ";
				$html .= "    if(fintensidad.value == 'Hora(s)')\n ";
				$html .= "      cantidad = (dosiscantidad.value*1) * 24/(fnumero.value*1);\n ";
				$html .= "    else if(fintensidad.value == 'Min')\n ";
				$html .= "      cantidad = (dosiscantidad.value*1) * 24/((fnumero.value*1)/60);\n";
				$html .= "   else if(fintensidad.value == 'Dia(s)')\n ";
				$html .= "     cantidad = 1/(fnumero.value*1);\n";
				
				$html .= "    else if(fintensidad.value == 'Semana(s)')\n ";
				$html .= "      cantidad = (1/(fnumero.value*7))*(dosiscantidad.value*1);\n";
				$html .= "    else\n ";
				$html .= "    cantidad = dosiscantidad.value*1;\n";
				$html .= "      cantidad1 = cantidad*totaldelT;\n";
				
				$html .= "    if(cantidad1<1)\n";
				$html .= "  {\n ";
			
				$html .= "      cantidadTotal1=1;\n";
				$html .= "  }\n ";
				$html .= "    else\n ";
				$html .= "    {\n ";
				
				$html .= "     cantidadTotal1 = (Math.round(cantidad1));\n";
				$html .= "   }\n ";
				
				$html .= "    document.forma_med.cantidad.value = cantidadTotal1/factor;\n ";
				$html .= "      document.getElementById('cantidadp').innerHTML=cantidadTotal1/factor;\n";
				$html .= "   if(periocidadtiempo!='0' && periocidad!='0')\n";
				$html .= "    {\n";
				$html .= "      cantidadp1 = cantidad*periocidadT/factor;\n";
				$html .= "     cantidadTotalp1 = (Math.round(cantidadp1));\n";
				//$this->salida .= "   alert(factor);";
				//$this->salida .= "   alert(periocidadT);"; 

				//$this->salida .= "   alert(factor);";
				//$this->salida .= "   <input type=\"hidden\" name=\"cantidadpordia\" value=\"cantidad\">\n";
				$html .= "     document.forma_med.cantidadperiocidad.value = cantidadTotalp1/factor;\n ";
				//  $this->salida .= "   alert(document.forma_med$pfj.cantidadperiocidad$pfj.value );";
				// $html .= "    alert(cantidad);";
				$html .= "    }\n";
				$html .= " }\n";
				$html .= "  if(opcionva[1].checked==true)\n";
				$html .= "  {\n";
				$html .= "   cantidadunica = dosiscantidad.value*1;\n";
				$html .= "  cantidad4 = cantidadunica*totaldelT;\n";
				$html .= "   cantidadTotal4 = (Math.round(cantidad4));\n";
				$html .= "  document.forma_med.cantidad.value = cantidadTotal4/factor;\n ";
				$html .= "   document.getElementById('cantidadp').innerHTML=cantidadTotal4/factor;\n";
				$html .= "  if(periocidadtiempo!='0' && periocidad!='0')\n";
				$html .= "  {\n";
				$html .= "      cantidadp4 = cantidadunica*periocidadT;\n";
				$html .= "      cantidadTotalp4 = (Math.round(cantidadp4));\n";
				$html .= "     document.forma_med.cantidadperiocidad.value = cantidadTotalp4/factor;\n ";
				//$this->salida .= "      document.getElementById('cantidadp').innerHTML=cantidadTotal4;\n";
				$html .= "  }\n";
				$html .= "  }\n";
				$html .= "   document.forma_med.cantidad_dia.value = cantidad;";
				$html .= "}\n";

				$html .= "function PeriocidadTiempo()\n ";
				$html .= "{\n";
				$html .= "  tiempototal=document.forma_med.tiempo_total;\n";
				$html .= "  tiempo=document.forma_med.tiempo_total2;\n";
				$html .= "  periocidad=document.forma_med.perioricidad_entrega.value;\n";
				$html .= " periocidadtiempo=document.forma_med.perioricidad_entrega2.value;\n";
				$html .= " if(tiempototal.value==0&& tiempo.value==0)\n";
				$html .= "  {\n";
				$html .= "   document.getElementById('error').innerHTML='SELECCIONAR EL TIEMPO DE TRATAMIENTO';\n";
				$html .= "   return;\n";
				$html .= " }\n";
				$html .= " if(periocidad.value==\"-1\")\n";
				$html .= "  {\n";
				$html .= "   document.getElementById('error').innerHTML='SELECCIONAR LA PERIORICIDAD DE ENTREGA DEL MEDICAMENTO';\n";
				$html .= "   return;\n";
				$html .= " }\n";
				$html .= "  if(periocidadtiempo != 0 && periocidad != 0)\n";
				
				$html .= "  {\n";
				//$html .= "  alert(periocidadtiempo); \n";
				$html .= "   if(tiempo.value <= periocidadtiempo)\n";
				$html .= "    {\n";
				$html .= "    if(tiempo.value==1)\n";
				$html .= "     {\n";
				$html .= "       tiempoTot = tiempototal.value*365;\n";
				$html .= "     }\n";
				$html .= "     if(periocidadtiempo==1)\n";
				$html .= "     {\n";
				$html .= "     periocidadTot = periocidad*365;\n";
				$html .= "     }\n";
				$html .= "     if(tiempo.value==2)\n";
				$html .= "   {\n";
				$html .= "      tiempoTot = tiempototal.value*30;\n";
				$html .= "     }\n";
				$html .= "     if(periocidadtiempo==2)\n";
				$html .= "      {\n";
				$html .= "       periocidadTot = periocidad*30;\n";
				$html .= "      }\n";
				$html .= "     if(tiempo.value==3)\n";
				$html .= "      {\n";
				$html .= "       tiempoTot = tiempototal.value*7;\n";
				$html .= "     }\n";
				$html .= "      if(periocidadtiempo==3)\n";
				$html .= "      {\n";
				$html .= "     periocidadTot = periocidad*7;\n";
				$html .= "      }\n";
				
				$html .= "     if(tiempo.value==4)\n";
				$html .= "      {\n";
				$html .= "       tiempoTot = tiempototal.value*1;\n";
				$html .= "     }\n";
				$html .= "      if(periocidadtiempo==4)\n";
				$html .= "      {\n";
				$html .= "     periocidadTot = periocidad*1;\n";
				$html .= "      }\n";
				$html .= "      if(tiempoTot<periocidadTot)";
				$html .= "      {\n";
				$html .= "      document.getElementById('error').innerHTML='PERIOCIDAD NO PUEDE SER MAYOR AL TIEMPO DEL TRATAMIENTO';\n";
				$html .= "     }\n";
				$html .= "  }\n";
				$html .= " else\n";
				$html .= "  {\n"; 
				$html .= "    document.getElementById('error').innerHTML='PERIOCIDAD NO PUEDE SER MAYOR AL TIEMPO DEL TRATAMIENTO';\n";        
				$html .= "  }\n";
				$html .= "  }\n";
				$html .= " if(document.forma_med.perioricidad_entrega.value==\"\")\n";
				$html .= "  {\n";
				$html .= "   document.getElementById('error').innerHTML='SELECCIONAR LA PERIOCIDAD DE ENTREGA';\n";
				$html .= "   return;\n";
				$html .= " }\n";
				$html .= " if(document.forma_med.perioricidad_entrega2.value==\"\")\n";
				$html .= "  {\n";
				$html .= "   document.getElementById('error').innerHTML='SELECCIONAR LA PERIOCIDAD DE ENTREGA';\n";
				$html .= "   return;\n";
				$html .= " }\n";
        $html .= " if(document.forma_med.dosis.value==\"\")\n";
				$html .= "  {\n";
				$html .= "   document.getElementById('error').innerHTML='INGRESAR LA DOSIS DEL TRATAMIENTO';\n";
				$html .= "   return;\n";
				$html .= " }\n";
				$html .= " if(document.forma_med.cantidad.value==\"\")\n";
				$html .= "  {\n";
				$html .= "   document.getElementById('error').innerHTML='NO SE REALIZO EL CALCULO';\n";
				$html .= "   return;\n";
				$html .= " }\n";
				$html .= "    document.forma_med.submit();\n";
				$html .= "}\n";
				$html .= "</script>";
			
				$html .= "<BR><table  align=\"center\" border=\"0\"  width=\"0\"lass=\"modulo_table_list\" width=\"85%\">";
				$html .= "	<tr class=\"formulacion_table_list\" ";
				if($var_e=='1')
				{
            $html .="<td    align=\"center\">$mensaje</td> ";
            $disabled =" disabled=\"true\" ";
				}
				if($var_e=='0')
				{
            $html .="<td   align=\"center\">$mensaje</td> ";
            $disabled  ="  ";
			
				}
        $html .= "</tr></table>";
        $html .= "<BR><table  align=\"center\" border=\"0\"  width=\"0\"><tr>";
        $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
				$html .= "		  <input type=\"hidden\" name=\"opcion_\" id=\"opcion_\" value=\"".$opcion."\">";
   			$html .= "<td   align=\"center\"><input class=\"input-submit\" name= 'guardar_formula' type=\"button\" value=\"GUARDAR FORMULA\"  $disabled   onclick=\"PeriocidadTiempo()\"></td>";
				$html .= "</form>";
				$html .= "<form name=\"forma\" action=\"".$action['cancelar']."\" method=\"post\">";
				$html .= "		  <input type=\"hidden\" name=\"opcion_\" id=\"opcion_\" value=\"".$opcion."\">";
				$html .= " <td   align=\"center\"><input class=\"input-submit\" name= 'cancelar' type=\"submit\" value=\"VOLVER\"></form></td>";
				$html .= "</tr></table>";
       	$html .= ThemeCerrarTabla();
      }
			return $html;
	
		}
	  /**
    * Funcion donde se bloquea la pantalla en el caso de que la formula registrada ya exista
    * @return string $html retorna la cadena con el codigo html de la pagina
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
 	  /**
    * Funcion donde se muestra la formula real completa
    * @return string $html retorna la cadena con el codigo html de la pagina
   */
    function FormaCabeceraFormulaCompleta($action,$Cabecera_Formulacion,$Cabecera_Formulacion_AESM,$request,$Cabecera_Formulacion_AEM,$Datos_Fueza,$Datos_Ad,$ESM_pac,$ESM_,$opcion,$IPS_,$dix_r,$medi_form,$permisos)
    {		
        $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
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
        $html .= "								<tr >\n";
        $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO EVENTO</td>\n";
        $html .= "									<td colspan=\"2\">	".$Cabecera_Formulacion['descripcion_tipo_evento']."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        $html .= "							</table>\n";
			
        if($opcion=='1')
        {
            $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >INSTITUCION PRESTADORA DE SERVICIOS DE SALUD </td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion_AEM['ips_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion_AEM['ips_tercero_id']."  &nbsp; ".$Cabecera_Formulacion_AEM['ips_atendido']."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONALES (IPS)</td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion_AEM['ips_profesional_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion_AEM['ips_profesional_tercero_id']."  &nbsp; ".$Cabecera_Formulacion_AEM['profesional_ips']." ( ".$Cabecera_Formulacion_AEM['descripcion_profesional_ips'].")\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";

            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >UBICACION (IPS)</td>\n";
            $html .= "									<td colspan=\"2\"   name=\"ubicacion_ips\" id=\"ubicacion_ips\"  >".$Cabecera_Formulacion_AEM['ubicacion']."\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTO DE SANIDAD MILITAR</td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['esm_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['esm_tercero_id']."  &nbsp; ".$Cabecera_Formulacion['esm_atendio']."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;  ".$Cabecera_Formulacion['profesional_esm']." (".$Cabecera_Formulacion['descripcion_profesional_esm'].")\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL AUTORIZA</td>\n";
            $html .= "									<td colspan=\"2\"> ".$Cabecera_Formulacion_AESM['esm_autoriza_tipo_id_tercero]']."  &nbsp; ".$Cabecera_Formulacion_AESM['esm_autoriza_tercero_id']."  &nbsp;  ".$Cabecera_Formulacion_AESM['profesional_esm']." (".$Cabecera_Formulacion_AESM['descripcion_profesional_esm'].")\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";			
            $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >COSTO FORMULA</td>\n";
            $html .= "									<td width=\"10%\" align=\"right\">".round($Cabecera_Formulacion_AESM['costo_formula'])."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            $html .= "							</table>\n";
          
        }
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
          $html .= " <input type=\"hidden\" name=\"tipo_fuerza\" id=\"tipo_fuerza\" value=\"".$Datos_Fueza['tipo_fuerza_id']."\">";

          if(empty($Datos_Fueza))
          {
          
                    $fuerza .= "									<td align=\"left\" class=\"label_error\"> NO TIENE UNA FUERZA ASOCIADA\n";
                    $fuerza .= "									</td>\n";			
          }
          else
        {
            $fuerza .= "									<td>".$Datos_Fueza['descripcion']."\n";
            $fuerza .= "									</td>\n";			
    
        }
        $html .= "								<tr>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FUERZA</td>\n";
        $html .= "								".$fuerza;
        $html .= "								</tr>\n";	
        $html .= "								<tr>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO PLAN</td>\n";
        $html .= "									<td >".$Datos_Ad['tipo_plan']."\n";
        $html .= "									</td>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO VINCULACION</td>\n";
        $html .= "									<td >".$Datos_Ad['vinculacion']."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";	
        $html .= "								<tr >\n";
        $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTO DE SANIDAD MILITAR  </td>\n";
        $html .= "									<td colspan=\"2\">\n";
        $html .= "									".$ESM_pac['tipo_id_tercero']." ".$ESM_pac['tercero_id']."  &nbsp; &nbsp;".$ESM_pac['nombre_tercero']."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
		
        if($opcion=='0')
        {
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PUNTO DE ATENCION  </td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['esm_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['esm_tercero_id']."  &nbsp; ".$Cabecera_Formulacion['esm_atendio']."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
            $html .= "									<td colspan=\"3\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;  ".$Cabecera_Formulacion['profesional_esm']." (".$Cabecera_Formulacion['descripcion_profesional_esm'].")\n";
            $html .= "						     </td>\n";
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
      $html .= "									<td  colspan=\"8\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTOS  SOLICITADOS</td>\n";
      $html .= "								</tr>\n";
      $html .= "								<tr>\n";
      $html .= "									<td  colspan=\"2\"  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CODIGO</td>\n";
      $html .= "									<td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTO</td>\n";
      $html .= "									<td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">PRINCIPIO ACTIVO</td>\n";
      $html .= "									</td>\n";
      $html .= "</tr>";
        
			for($i=0;$i<sizeof($medi_form);$i++)
			{

            $html .= "<tr >";
            $html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['min_defensa']." </td>";
            $html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['descripcion_prod']." ".$medi_form[$i]['concentracion_forma_farmacologica']." </td>";
            $html .= "  <td colspan=\"2\" align=\"center\" width=\"43%\">".$medi_form[$i]['principio_activo']."</td>";
            $html .= "</tr>";
            if($medi_form[$i]['principio_activo']!="")
             {
								 $html .= "  <tr >";
								 $html .= "    <td colspan = 5>";
								 $html .= "      <table>";
								 $html .= "<tr class=\"$estilo\">";
								 $via_form=$obje->Consultar_Via_Admin($medi_form[$i]['via_administracion_id']);
                  $html .= "  <td  class=\"modulo_list_claro\"  colspan = 3 align=\"left\" width=\"9%\"><b>Via de Administracion: </b></td>";
                  $html .= "  <td  class=\"label\"   colspan = 3 align=\"left\" width=\"9%\">".$via_form[0]['nombre']."</td>";
                  $html .= "</tr>";
                  $html .= "<tr class=\"$estilo\">";
                  $html .= "  <td class=\"modulo_list_claro\" align=\"left\" width=\"9%\"><b>Dosis:<b></td>";
                  $e=$medi_form[$i]['dosis']/floor($medi_form[$i]['dosis']);

                  if($e==1)
                  {
                    $html .= "  <td  class=\"label\"  align=\"left\" width=\"14%\">".floor($medi_form[$i]['dosis'])."  ".$medi_form[$i]['unidad_dosificacion']."</td>";
                  }
                  else
                  {
                    $html .= "  <td  class=\"label\"  align=\"left\" width=\"14%\">".$medi_form[$i]['dosis']."  ".$medi_form[$i]['unidad_dosificacion']."</td>";
                  }
                  $opcion_d=$obje->Consulta_opc_Medicamentos_PosologiaR($medi_form[$i]['fe_medicamento_id']);
									$vector_posologia= $obje->Consulta_Solicitud_Medicamentos_Posologia($opcion_d['opcion'], $medi_form[$i]['fe_medicamento_id']);
//pintar formula para opcion 1
								if($opcion_d['opcion']== 1)
								{
									 $html .= "   <td   class=\"label\" align=\"left\" width=\"50%\">cada ".$vector_posologia[0]['periocidad_id']." ".$vector_posologia[0]['tiempo']."</td>";

								}

//pintar formula para opcion 2
								if($opcion_d['opcion']== 2)
								{
									$html .= " <td   class=\"label\" align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
								}

//pintar formula para opcion 3
								if($opcion_d['opcion']== 3)
								{
										$momento = '';
										if($vector_posologia[0]['sw_estado_momento']== '1')
										{
											$momento = 'antes de ';
										}
										else
										{
											if($vector_posologia[0]['sw_estado_momento']== '2')
											{
												$momento = 'durante ';
											}
											else
											{
												if($vector_posologia[0]['sw_estado_momento']== '3')
													{
														$momento = 'despues de ';
													}
											}
										}
										$Cen = $Alm = $Des= '';
										$cont= 0;
										$conector = '  ';
										$conector1 = '  ';
										if($vector_posologia[0]['sw_estado_desayuno']== '1')
										{
											$Des = $momento.'el Desayuno';
											$cont++;
										}
										if($vector_posologia[0]['sw_estado_almuerzo']== '1')
										{
											$Alm = $momento.'el Almuerzo';
											$cont++;
										}
										if($vector_posologia[0]['sw_estado_cena']== '1')
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
										$html .= "  <td   class=\"label\" align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
								}

//pintar formula para opcion 4
								if($opcion_d['opcion']== 4)
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
									$html .= "  <td   class=\"label\" align=\"left\" width=\"50%\"><b>a la(s): $frecuencia<b></td>";
								}

							if($medi_form[$i]['unidad_tiempo_tratamiento']=='1')
							{
							  
							  $unidad_tra='AÑO(S)';
							
							}
							if($medi_form[$i]['unidad_tiempo_tratamiento']=='2')
							{
							$unidad_tra='MES(ES)';
							
							
							}
							if($medi_form[$i]['unidad_tiempo_tratamiento']=='3')
							{
							
							
							$unidad_tra='SEMANA(S)';
							}
							
							if($medi_form[$i]['unidad_tiempo_tratamiento']=='4')
							{
							
								$unidad_tra='DIA(S)';
							
							}
								
								
							$html .= "</tr>";
						    $html .= "        <tr >\n";
							$html .= "          <td  class=\"modulo_list_claro\" ><b>Duracion:<b></td>";
							$html .= "          <td   class=\"label\" colspan = \"2\" >".$medi_form[$i]['tiempo_tratamiento']." ".$unidad_tra."</td>";
							$html .= "        </tr>"; 
						
						   if($medi_form[$i]['unidad_periodicidad_entrega']=='1')
							{
							 
							   $unidad_perio='AÑO(S)';
							
							}
							if($medi_form[$i]['unidad_periodicidad_entrega']=='2')
							{
							
							
							$unidad_perio='MES(ES)';
							}
							if($medi_form[$i]['unidad_periodicidad_entrega']=='3')
							{
							
							$unidad_perio='SEMANA(S)';
							
							}
							
							if($medi_form[$i]['unidad_periodicidad_entrega']=='4')
							{
							
							 $unidad_perio='DIA(S)';
							
							}
										
							$html .= "        <tr >\n";
							$html .= "          <td  class=\"modulo_list_claro\" ><b>Periodicidad de entrega:</b></td>";
							$html .= "          <td   class=\"label\"  colspan = \"2\" >".$medi_form[$i]['periodicidad_entrega']." ".$unidad_perio." </td>";
							$html .= "        </tr>";
							$html .= "     </table>";
							$html .= "    </td>";
							$html .= "  </tr>";
							$html .= "  <tr >";
							$html .= "    <td colspan = 5 >";
							$html .= "      <table>";
							$html .= "        <tr>";
							$html .= "          <td  class=\"modulo_list_claro\"  align=\"left\" width=\"4%\"><b>Observacion:<b></td>";
							$html .= "          <td  class=\"label\"  align=\"left\" width=\"69%\">".$medi_form[$i]['observacion']."</td>";
							$html .= "        </tr>";
							$html .= "      </table>";
							$html .= "   </td>";
							$html .= "  </tr>";
     						$html .= " </tr>";
                    }
                    else
                    {
                              $html .= "        <tr  $style>\n";
                              $html .= "    <td >";
                              $html .= "      <table>";

                              $html .= "        <tr >\n";
                              $html .= "  <td  class=\"label\"  align=\"left\" width=\"9%\"><b>Cantidad: </b>".round($medi_form[$i]['cantidad'])."</td>";
                              $html .= "</tr>";

                              $tiempo_tratamiento=$medi_form[$i]['tiempo_tratamiento'];
                              $unidad_tiempo_tratamiento=$medi_form[$i]['unidad_tiempo_tratamiento'];

                              $html .= "        <tr >\n";
                              $html .= "  <td class=\"label\"   colspan=\"2\ align=\"left\" ><b>Tiempo Entrega:<b>".$tiempo_tratamiento." DIA(S)</td>";
                              $html .= "</tr>";

                              $html .= "      </table>";
                              $html .= "    </td>";
                              $html .= "  </tr>";

                    }					
				
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
      /**
    * Funcion donde se buscan los productos para la formulacion ambulatoria
    * @return string $html retorna la cadena con el codigo html de la pagina
   */
   function FormaBuscarProductos_Ambulatoria($action,$request,$datos,$conteo,$pagina,$formula,$medicamento_Datos,$via_admon,$mensaje,$var_e,$medi_form,$opcion,$Cabecera_Formulacion,$DX_,$request,$Datos_Fueza,$Datos_Ad,$ESM_pac,$Cabecera_Formulacion_AESM,$Cabecera_Formulacion_AEM,$formula_id,$tipo_id_paciente,$paciente_id,$plan_id,$opcion)
    {		
        $ctl = AutoCarga::factory("ClaseUtil"); 
        $html .= $ctl->LimpiarCampos();
        $html .= $ctl->AcceptNum(false);
        $today = date("Y-m-d");
        
        $obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');			
        $tope=$obje->Consultar_saldo_tope_($today,$Cabecera_Formulacion['esm_tipo_id_tercero'],$Cabecera_Formulacion['esm_tercero_id']);
			  $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
			
      
        $estilo = "style=\"border-bottom-width:0px;border-left-width:2px;border-right-width:0px;border-top-width:0px;border-style: solid;\""; 
        $html .= ThemeAbrirTabla('REGISTRO DE UNA FORMULA AMBULATORIA -MEDICAMENTOS FORMULADOS ');
        $html .= "			<fieldset class=\"fieldset\">\n";
        $html .= "				<legend class=\"normal_10AN\">INFORMACION DEL TOPO DE LA ESM </legend>\n";
        $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
        $html .= "					<tr>\n";
        $html .= "						<td align=\"center\">\n";
        $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
        $html .= "								<tr >\n";
        $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SALDO DE LA ESM </td>\n";
        $html .= "									<td width=\"10%\" align=\"right\" class=\"label_error\">$".FormatoValor($tope['saldo_tope'])."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        $html .= "							</table>\n";
        $html .= "						</td>\n";
        $html .= "					</tr>\n";
        $html .= "				</table>\n";
        $html .= "			</fieldset>\n";
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
        $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_registro']."\n";
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
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        $html .= "								<tr >\n";
        $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO EVENTO</td>\n";
        $html .= "									<td colspan=\"2\">	".$Cabecera_Formulacion['descripcion_tipo_evento']."\n";
        $html .= "									</td>\n";
				$html .= "								</tr>\n";
        $html .= "							</table>\n";
				if($opcion=='1')
        {
          $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
          $html .= "								<tr >\n";
          $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >INSTITUCION PRESTADORA DE SERVICIOS DE SALUD </td>\n";
          $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion_AEM['ips_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion_AEM['ips_tercero_id']."  &nbsp; ".$Cabecera_Formulacion_AEM['ips_atendido']."\n";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          $html .= "								<tr >\n";
          $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONALES (IPS)</td>\n";
          $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion_AEM['ips_profesional_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion_AEM['ips_profesional_tercero_id']."  &nbsp; ".$Cabecera_Formulacion_AEM['profesional_ips']." ( ".$Cabecera_Formulacion_AEM['descripcion_profesional_ips'].")\n";
          $html .= "						     </td>\n";
          $html .= "								</tr>\n";
          $html .= "								<tr >\n";
          $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >UBICACION (IPS)</td>\n";
          $html .= "									<td colspan=\"2\"   name=\"ubicacion_ips\" id=\"ubicacion_ips\"  >".$Cabecera_Formulacion_AEM['ubicacion']."\n";
          $html .= "						     </td>\n";
          $html .= "								</tr>\n";
          $html .= "								<tr >\n";
          $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTO DE SANIDAD MILITAR</td>\n";
          $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['esm_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['esm_tercero_id']."  &nbsp; ".$Cabecera_Formulacion['esm_atendio']."\n";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          $html .= "								<tr >\n";
          $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
          $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;  ".$Cabecera_Formulacion['profesional_esm']." (".$Cabecera_Formulacion['descripcion_profesional_esm'].")\n";
          $html .= "						     </td>\n";
          $html .= "								</tr>\n";
          $html .= "								<tr >\n";
          $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL AUTORIZA</td>\n";
          $html .= "									<td colspan=\"2\"> ".$Cabecera_Formulacion_AESM['esm_autoriza_tipo_id_tercero]']."  &nbsp; ".$Cabecera_Formulacion_AESM['esm_autoriza_tercero_id']."  &nbsp;  ".$Cabecera_Formulacion_AESM['profesional_esm']." (".$Cabecera_Formulacion_AESM['descripcion_profesional_esm'].")\n";
          $html .= "						     </td>\n";
          $html .= "								</tr>\n";
          $html .= "								<tr >\n";			
          $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >COSTO FORMULA</td>\n";
          $html .= "									<td width=\"10%\" align=\"right\">".round($Cabecera_Formulacion_AESM['costo_formula'])."\n";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          $html .= "							</table>\n";
				
			}
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
        $html .= " <input type=\"hidden\" name=\"tipo_fuerza\" id=\"tipo_fuerza\" value=\"".$Datos_Fueza['tipo_fuerza_id']."\">";
        if(empty($Datos_Fueza))
        {
            $fuerza .= "									<td align=\"left\" class=\"label_error\"> NO TIENE UNA FUERZA ASOCIADA\n";
            $fuerza .= "									</td>\n";			
        }
        else
        {
            $fuerza .= "									<td>".$Datos_Fueza['descripcion']."\n";
            $fuerza .= "									</td>\n";			
        }
        $html .= "								<tr>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FUERZA</td>\n";
        $html .= "								".$fuerza;
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO PLAN</td>\n";
        $html .= "									<td >".$Datos_Ad['tipo_plan']."\n";
        $html .= "									</td>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO VINCULACION</td>\n";
        $html .= "									<td >".$Datos_Ad['vinculacion']."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        $html .= "								<tr>\n";
        $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTO DE SANIDAD MILITAR  </td>\n";
        $html .= "									<td colspan=\"2\">\n";
        $html .= "									".$ESM_pac['tipo_id_tercero']." ".$ESM_pac['tercero_id']."  &nbsp; &nbsp;".$ESM_pac['nombre_tercero']."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        if($opcion=='0')
        {
			
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PUNTO DE ATENCION  </td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['esm_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['esm_tercero_id']."  &nbsp; ".$Cabecera_Formulacion['esm_atendio']."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
            $html .= "									<td colspan=\"3\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;  ".$Cabecera_Formulacion['profesional_esm']." (".$Cabecera_Formulacion['descripcion_profesional_esm'].")\n";
            $html .= "						     </td>\n";
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
      if(!empty($medi_form))
      {
			  
          $html .= "<table border=\"0\" width=\"88%\" align=\"center\" >\n";
          $html .= "	<tr>\n";
          $html .= "		<td>\n";
          $html .= "			<fieldset class=\"fieldset\">\n";
          $html .= "				<legend class=\"normal_10AN\">MEDICAMENTOS FORMULADOS </legend>\n";
      
          $html .= "<table  align=\"center\" border=\"0\"   class=\"modulo_table_list\" width=\"100%\">";
          $html .= "	<tr class=\"formulacion_table_list\" >";
          $html .= "  <td align=\"center\" colspan=\"6\">MEDICAMENTOS  SOLICITADOS</td>";
          $html .= "</tr>";

          $html .= "<tr class=\"formulacion_table_list\">";
          $html .= "  <td width=\"7%\">CODIGO</td>";
          $html .= " <td width=\"30%\">PRODUCTO</td>";
          $html .= " <td colspan=\"2\" width=\"43%\">PRINCIPIO ACTIVO</td>";
          $html .= " <td width=\"43%\">MA</td>";
          $html .= " <td width=\"43%\">OP</td>";
          $html .= "</tr>";
          
          for($i=0;$i<sizeof($medi_form);$i++)
          {
					
              if( $i % 2){ $estilo='modulo_list_claro';}
              else {$estilo='modulo_list_oscuro';}
              $html .= "<tr class=\"$estilo\">";
              $html .= "  <td align=\"center\" width=\"30%\">".$medi_form[$i]['min_defensa']." </td>";
              $html .= "  <td align=\"center\" width=\"30%\">".$medi_form[$i]['descripcion_prod']." ".$medi_form[$i]['concentracion_forma_farmacologica']." </td>";
              $html .= "  <td colspan=\"2\" align=\"center\" width=\"43%\">".$medi_form[$i]['principio_activo']."</td>";
              
              if($medi_form[$i]['sw_marcado']=='0')
              {
                  $html .= "				<td  width=\"10%\"  align=\"center\"  >\n";
                  $html .= "					<a href=\"".$action['marcado'].URLRequest(array("producto_eliminar"=>$medi_form[$i]['codigo_producto'],"marcado"=>'1',"fe_medicamento_id"=>$medi_form[$i]['fe_medicamento_id']))."\"  class=\"label_error\"  ><img src=\"".GetThemePath()."/images/si.png\" border='0' >\n";
                  $html .= "					</a></center>\n";
                  $html .= "			</td>\n";
              }
              else
              {
                  $html .= "				<td  width=\"10%\"  align=\"center\"  >\n";
                  $html .= "					<img src=\"".GetThemePath()."/images/delete.gif\" border='0' >\n";
                  $html .= "					</a></center>\n";
                  $html .= "			</td>\n";
              }
              $html .= "				<td  width=\"10%\"  align=\"center\"  >\n";
              $html .= "					<a href=\"".$action['eliminar_med'].URLRequest(array("producto_eliminar"=>$medi_form[$i]['codigo_producto'],"eliminar_medica"=>'1',"fe_medicamento_id"=>$medi_form[$i]['fe_medicamento_id']))."\"  class=\"label_error\"  ><img src=\"".GetThemePath()."/images/delete2.gif\" border='0' >\n";
              $html .= "					</a></center>\n";
              $html .= "			</td>\n";

              $html .= "</tr>";
              $html .= "  <tr class=\"$estilo\">";
              $html .= "    <td colspan = 8>";
              $html .= "      <table>";

              $html .= "<tr class=\"$estilo\">";
              $html .= "  <td colspan = 3 align=\"left\" width=\"9%\"><b>Cantidad: </b>".round($medi_form[$i]['cantidad'])."</td>";
              $html .= "</tr>";
								 
              $tiempo_tratamiento=$medi_form[$i]['tiempo_tratamiento'];
              $unidad_tiempo_tratamiento=$medi_form[$i]['unidad_tiempo_tratamiento'];

              $html .= "<tr class=\"$estilo\">";
              $html .= "  <td align=\"left\" width=\"9%\"><b>Tiempo Entrega:<b>".$tiempo_tratamiento." DIA(S)</td>";
              $html .= "</tr>";

              $html .= "      </table>";
              $html .= "    </td>";
              $html .= "  </tr>";
						
					}	
				$html .= "</table><br>\n";
				$html .= "			</fieldset>\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table><br>\n";
	
		}

      $html .= "<form name=\"registrar_cabecera\" id=\"registrar_cabecera\"    action=\"".$action['buscador']."\"   method=\"post\">\n";
      $html .= "<input type=\"hidden\" name=\"tipo_afiliado\" value=\"".$Datos_Paciente['tipo_afiliado_atencion']."\">\n";
      $html .= "<input type=\"hidden\" name=\"rango\" value=\"".$Datos_Paciente['rango_afiliado_atencion']."\">\n";
      $html .= "		<center>\n";
      $html .= "			<label id=\"error_actividad\" class=\"label_error\"></label>\n";
      $html .= "	  </center>\n";	
			$html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend class=\"normal_10AN\">PRODUCTOS PARA LA FORMULACION</legend>\n";
      $html .= "	<table  align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"85%\">";
      $html .= "	<tr class=\"formulacion_table_list\" >";
      $html .= "	<td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE PRODUCTOS </td>";
      $html .= "	</tr>";
      $html .= "	<tr class=\"formulacion_table_list\" >";
      $html .= "	<td  colspan=\"1\">CODIGO:</td>";   
      $html .= "	<td width=\"15%\" class=\"modulo_list_oscuro\"  align='center'><input type='text' class='input-text' size =\"25\" maxlength =60	name = \"buscador[codigo]\" ></td>" ;
      $html .= "	<td width=\"15%\">DESCRIPCION:</td>";
      $html .= "	<td  colspan=\"3\"  class=\"modulo_list_oscuro\" align='center'><input type='text' size =\"80\" class='input-text' 	name =\"buscador[descripcion]\"   value =\"".$request['descripcion']."\"></td>" ;
      $html .= "	</tr>";
      $html .= "	<tr class=\"formulacion_table_list\" >";
      $html .= "	<td  colspan=\"1\">PRINCIPIO ACTIVO:</td>";
      $html .= "	<td   colspan=\"3\" class=\"modulo_list_oscuro\" align='center'><input type='text' size =\"80\" class='input-text' 	name =\"buscador[principio_activo]\"   value =\"".$request['producto']."\"></td>" ;
      $html .= "	<td class=\"modulo_list_oscuro\"  align=\"center\"><input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"BUSQUEDA\"></td>";
      $html .= "	</tr>";
      $html .= "	</table><br>";
			$html .= "		</form>\n";
			$html .= "<form name=\"registrar_dx\" id=\"registrar_dx\"    action=\"".$action['guardar']."\"   method=\"post\">\n";
		  
      if ($datos)
    	{
          $pghtml = AutoCarga::factory('ClaseHTML');
          $html .= "<table  align=\"center\" border=\"0\" width=\"100%\">";
          $html .= "<tr class=\"formulacion_table_list\">";
          $html .= "<td align=\"center\" colspan=\"8\">RESULTADO DE LA BUSQUEDA</td>";
          $html .= "</tr>";
          $html .= "<tr class=\"formulacion_table_list\">";
          $html .= "  <td width=\"8%\">CODIGO</td>";
          $html .= "  <td width=\"25%\">MOLECULA</td>";
          $html .= " <td width=\"35%\">PRODUCTO</td>";
          $html .= " <td width=\"10%\">CANTIDAD</td>";
          $html .= " <td width=\"10%\">EXISTENCIAS</td>";
          $html .= " <td width=\"75%\">TIEMPO ENTREGA</td>";
          $html .= " <td width=\"10%\">INFORMACION</td>";
          $html .= " <td width=\"5%\">OP</td>";
          $html .= "</tr>";
         
          $con=0;
          foreach($datos as $key => $dtl)
          {
                $codigo2= $dtl['codigo_producto_mini'];
                $codigo= $dtl['codigo_producto'];
                $producto= $dtl['descripcion'];
                $molecula=$dtl['molecula'];
                $existencia=$dtl['existencia'];
                if( $i % 2){$estilo='modulo_list_claro';}
                else {$estilo='modulo_list_oscuro';}
                $html .= "<tr class=\"$estilo\">";
                $html .= "<td align=\"center\" width=\"8%\">$codigo2</td>";
                $html .= "<td align=\"center\" width=\"25%\">$molecula</td>";
                $html .= "<td align=\"left\" width=\"35%\">$producto</td>";
                $html .= "     <td>";
                $html .= "      <input style=\"width:100%\" type=\"text\" onkeypress=\"return acceptNum(event);\"   class=\"input-text\" value=\"\" name=\"cantidad_formulada".$codigo."\" id=\"cantidad_formulada".$codigo."\" >";
                $html .= "      </td>";
                $html .= "<td align=\"left\" width=\"10%\" >".round($existencia)."</td>";
                $html .= "  <td  width=\"76%\"align=\"left\" >";
                $html .= "    <select name = \"tiempo_total".$codigo."\" id = \"tiempo_total".$codigo."\" class=\"select\">";

                for($dia=1;$dia<=180;$dia++)
                {
                    $html .= "        <option value = '$dia' >$dia</option>";
			
                 }
                $html .= "   </select>";
                $html .= "     <select name = 'tiempo_total2' id = 'tiempo_total2'class=\"select\">";
                $html .= "        <option value = '4' >DIA(S)</option>";
                $html .= "     </select>";
                $html .= "   </td>";
                if($dtl['resultado']==0)
                {
                      $html .= "<td align=\"center\" class=\"label_error\" width=\"17%\">NO PACTADO ";
                }else
                {
                      $html .= "<td align=\"center\"  width=\"15%\">PACTADO ";
                }
                $html .= "</td>";
                $html .= "      <td align=\"center\">\n";
                $html .= "         <a href=\"#\" onclick=\"xajax_Guardartmp_ambu('".$codigo."', '".$formula_id."',document.getElementById('cantidad_formulada".$codigo."').value,document.getElementById('tiempo_total".$codigo."').value,'4','".$tipo_id_paciente."','".$paciente_id."','".$plan_id."','".$opcion."')\"  class=\"label_error\" ><img src=\"".GetThemePath()."/images/producto.png\" border='0' ></a>\n";
                $html .= "      </td>\n";
                $con++;
        }
        
          $html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$i."\">";
          $html .= "</table><br>";
          $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
        $html .= "			</fieldset>\n";
        $html .= "		</td>\n";
        $html .= "	</tr>\n";
      
        $html .= "</table>\n";
        $html .= "		</form>\n";
        $html .= "  <table border=\"-1\" width=\"15%\" align=\"center\" >\n";
        $html .= "	  <tr>\n";
        $html .= "		</form>\n";
        if(!empty($medi_form))
        {
		
            $disabled= " ";
		
        }else
        {
            $disabled= " disabled=true ";
        }
		
        
        $html .= "		<form name=\"forma\" action=\"".$action['finaliza_formulacion_real']."\" method=\"post\">\n";
        $html .= "		  <td align=\"center\"><br>\n";
        $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"cancelar\" $disabled  value=\"FINALIZAR FORMULACION \">\n";
        $html .= "		  </td>";
        $html .= "		</form>\n";
        $html .= "</table>";
		
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
      if($tope['saldo_tope']<= $tope['saldo_minimo'])
			{
          $html .= "<script>";
          $html .= "alert('SALDO MINIMO PARA  EL ESTABLECIMIENTO DE SANIDAD MILITAR ');";
          $html .= "</script>";
			}
     	$html .= ThemeCerrarTabla();
			return $html;
	
  }
	  /**
    * Funcion donde se  crea la formulacion ambulatoria
    * @return string $html retorna la cadena con el codigo html de la pagina
   */
    function FormaCabeceraFormulaCompleta_ambu($action,$Cabecera_Formulacion,$Cabecera_Formulacion_AESM,$request,$Cabecera_Formulacion_AEM,$Datos_Fueza,$Datos_Ad,$ESM_pac,$ESM_,$opcion,$IPS_,$dix_r,$medi_form,$permisos)
    {		
        $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
				$obje = AutoCarga::factory('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
        $html .= ThemeAbrirTabla('FORMULA MEDICA  COMPLETA AMBULATORIA ');
        
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
        $html .= "								<tr >\n";
        $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO EVENTO</td>\n";
        $html .= "									<td colspan=\"2\">	".$Cabecera_Formulacion['descripcion_tipo_evento']."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        $html .= "							</table>\n";
			
        /* TRANSCRIPCION  DE FORMULAS*/
        if($opcion=='1')
        {
            $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >INSTITUCION PRESTADORA DE SERVICIOS DE SALUD </td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion_AEM['ips_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion_AEM['ips_tercero_id']."  &nbsp; ".$Cabecera_Formulacion_AEM['ips_atendido']."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONALES (IPS)</td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion_AEM['ips_profesional_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion_AEM['ips_profesional_tercero_id']."  &nbsp; ".$Cabecera_Formulacion_AEM['profesional_ips']." ( ".$Cabecera_Formulacion_AEM['descripcion_profesional_ips'].")\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >UBICACION (IPS)</td>\n";
            $html .= "									<td colspan=\"2\"   name=\"ubicacion_ips\" id=\"ubicacion_ips\"  >".$Cabecera_Formulacion_AEM['ubicacion']."\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
          /* ESM ASOCIADA A LA IPS */
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTO DE SANIDAD MILITAR</td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['esm_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['esm_tercero_id']."  &nbsp; ".$Cabecera_Formulacion['esm_atendio']."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;  ".$Cabecera_Formulacion['profesional_esm']." (".$Cabecera_Formulacion['descripcion_profesional_esm'].")\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL AUTORIZA</td>\n";
            $html .= "									<td colspan=\"2\"> ".$Cabecera_Formulacion_AESM['esm_autoriza_tipo_id_tercero]']."  &nbsp; ".$Cabecera_Formulacion_AESM['esm_autoriza_tercero_id']."  &nbsp;  ".$Cabecera_Formulacion_AESM['profesional_esm']." (".$Cabecera_Formulacion_AESM['descripcion_profesional_esm'].")\n";
            $html .= "						     </td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";			
            $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >COSTO FORMULA</td>\n";
            $html .= "									<td width=\"10%\" align=\"right\">".round($Cabecera_Formulacion_AESM['costo_formula'])."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            $html .= "							</table>\n";
				
          }
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
          $html .= " <input type=\"hidden\" name=\"tipo_fuerza\" id=\"tipo_fuerza\" value=\"".$Datos_Fueza['tipo_fuerza_id']."\">";
          if(empty($Datos_Fueza))
          {
              $fuerza .= "									<td align=\"left\" class=\"label_error\"> NO TIENE UNA FUERZA ASOCIADA\n";
              $fuerza .= "									</td>\n";			
          }
        else
        {
              $fuerza .= "									<td>".$Datos_Fueza['descripcion']."\n";
              $fuerza .= "									</td>\n";			
        }
        $html .= "								<tr>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FUERZA</td>\n";
        $html .= "								".$fuerza;
        $html .= "								</tr>\n";	
        $html .= "								<tr>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO PLAN</td>\n";
        $html .= "									<td >".$Datos_Ad['tipo_plan']."\n";
        $html .= "									</td>\n";
        $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO VINCULACION</td>\n";
        $html .= "									<td >".$Datos_Ad['vinculacion']."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";	
        $html .= "								<tr >\n";
        $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTO DE SANIDAD MILITAR  </td>\n";
        $html .= "									<td colspan=\"2\">\n";
        $html .= "									".$ESM_pac['tipo_id_tercero']." ".$ESM_pac['tercero_id']."  &nbsp; &nbsp;".$ESM_pac['nombre_tercero']."\n";
        $html .= "									</td>\n";
        $html .= "								</tr>\n";
        if($opcion=='0')
        {
          
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PUNTO DE ATENCION  </td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['esm_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['esm_tercero_id']."  &nbsp; ".$Cabecera_Formulacion['esm_atendio']."\n";
            $html .= "									</td>\n";
            $html .= "								</tr>\n";
            $html .= "								<tr >\n";
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
            $html .= "									<td colspan=\"3\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;  ".$Cabecera_Formulacion['profesional_esm']." (".$Cabecera_Formulacion['descripcion_profesional_esm'].")\n";
            $html .= "						     </td>\n";
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
            $html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['min_defensa']." </td>";
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
 	  /**
    * Funcion donde se  crea la forma para anular la formula  
    * @return string $html retorna la cadena con el codigo html de la pagina
   */
    function FormaFormulaAnulada($action,$formula_papel_,$tipo_id_paciente,$paciente_id)
		{
		
        $html  = ThemeAbrirTabla("INFORMACION DE LA FORMULA ANULADA ");
        $html .= " <form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
        $html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "    SE ANULO LA FORMULA No ".$formula_papel_['formula_papel']." PARA EL PACIENTE CON NUMERO DE IDENTIFICACION  ".$tipo_id_paciente." ".$paciente_id."  ";
        $html .= "      </td>\n";
        $html .= "	</table>\n";
        $html .= "  </form>\n";
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
    * Funcion donde se  crea la vista para la formulacion del documento de suministro  
    * @return string $html retorna la cadena con el codigo html de la pagina
   */
  	function Vista_FormularioNuevoDoc_Suministro($accion,$ESM,$permisos2,$TiposRequisiciones,$CentrosUtilidad)
		{
          $html .= " <script>";
          $html .= "  function Validar(Formulario)";
          $html .= "  {";
          $html .= "  if(Formulario.esm.value==\"\"){";
          $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR EL ESTABLECIMIENTO DE SANIDAD MILITAR\";\n"; 
          $html .= "          return true;\n"; 
          $html .= "        }\n";
          $html .= "      if(Formulario.bodega.value==\"\" ) {";
          $html .= "          document.getElementById(\"error_actividad\").innerHTML = \"SE DEBE SELECCIONAR LA BODEGA\";\n"; 
          $html .= "          return true;\n"; 
          $html .= "        }\n";
          $html .= " document.FormularioCabecera.submit();";
          $html .= " }";
          $html .= " </script>";
  
          $html .= ThemeAbrirTabla('SUMINISTROS',"70%");
          $html .= "		<center>\n";
          $html .= "			<label id=\"error_actividad\" class=\"label_error\"></label>\n";
          $html .= "	  </center>\n";	
          $select_esm = "<select name=\"esm\" id=\"esm\" class=\"select\" style=\"width:100%;\">";
          $select_esm .= "<option value=\"\">-- SELECCIONAR --</option>";
          foreach($ESM as $k => $v)
          {
              $select_esm .= "<option value=\"".$v['tipo_id_tercero']."@".$v['tercero_id']."\">".$v['identificacion']."-".$v['nombre_tercero']."</option>";
          }
          $select_esm .= "</select>";
          $select_bodega = "<select name=\"bodega\" id=\"bodega\" class=\"select\" style=\"width:100%;\">";
          $select_bodega .= "<option value=\"\">-- SELECCIONAR --</option>";
          foreach($permisos2 as $k => $v)
          {
              $select_bodega .= "<option value=\"".trim($v['bodega'])."\">".trim($v['bodegas'])."</option>";
          }
          $select_bodega .= "</select>";
          $html .= "<center>";
          $html .= "  <form name=\"FormularioCabecera\" id=\"FormularioCabecera\" action=\"".$accion['Guardar']."\" method=\"POST\">";
          $html .= "  <table border=\"0\" width=\"100%\"  class=\"modulo_table_list\" align=\"center\" >\n";
          $html .= "    <tr class=\"formulacion_table_list\" >";
          $html .= "      <td colspan=\"2\">";
          $html .= "     REALIZAR SUMINISTROS";
          $html .= "      </td>";
          $html .= "    </tr>";
          $html .= "    <tr class=\"modulo_list_claro\">";
          $html .= "      <td class=\"formulacion_table_list\">";
          $html .= "      ESTABLECIMIENTO DE SANIDAD MILITAR";
          $html .= "      </td>";
          $html .= "      <td align=\"center\">";
          $html .= "          ".$select_esm;
          $html .= "      </td>";
          $html .= "    </tr>";
          $html .= "    <tr class=\"modulo_list_claro\">";
          $html .= "      <td class=\"formulacion_table_list\">";
          $html .= "      BODEGA SATELITE";
          $html .= "      </td>";
          $html .= "      <td align=\"center\">";
          $html .= "          ".$select_bodega;
          $html .= "      </td>";
          $html .= "    </tr>";
          $html .= "    <tr class=\"modulo_list_claro\">";
          $html .= "      <td class=\"formulacion_table_list\" colspan=\"2\">";
          $html .= "      OBSERVACIONES";
          $html .= "      </td>";
          $html .= "    </tr>";

          $html .= "    <tr class=\"modulo_list_claro\">";
          $html .= "      <td class=\"formulacion_table_list\"  colspan=\"2\">";
          $html .= "      <textarea name=\"observacion\" id=\"observacion\" class=\"textarea\" style=\"width:100%\"></textarea>";
          $html .= "      </td>";
          $html .= "    </tr>";
          $html .= "  </table>";
          $html .= "  <table border=\"0\" width=\"100%\"   align=\"center\" >\n";
          $html .= "    <tr  align=\"center\" >";
          $html .= "      <td colspan=\"2\">";
          $html .= "      <input type=\"hidden\" value=\"".$permisos2[0]['empresa_id']."\" name=\"datos[empresa_id]\" id=\"datos[empresa_id]\">";
          $html .= "      <input type=\"hidden\" value=\"".$permisos2[0]['centro_utilidad']."\" name=\"datos[centro_utilidad]\" id=\"datos[centro_utilidad]\">";

          $html .= "      <input type=\"button\" value=\"CREAR SUMINISTROS\" class=\"input-submit\" onclick=\"Validar(document.FormularioCabecera);\">";
          $html .= "      </td>";
          $html .= "    </tr>";

          $html .= "  </table>\n";
          $html .= "</center>";
          $html .= " </form>";
          $html .= "<table align=\"center\">\n";
          $html .= "<br>";
          $html .= "  <tr>\n";
          $html .= "      <td align=\"center\" class=\"label_error\">\n";
          $html .= "        <a href=\"".$accion['volver']."\">VOLVER</a>\n";
          $html .= "      </td>\n";
          $html .= "  </tr>\n";
          $html .= "</table>\n";
          $html .= ThemeCerrarTabla();
          return $html;
      }
    /**
    * Funcion donde se  lista y se buscan los pacientes para realizar el suminstro  
    * @return string $html retorna la cadena con el codigo html de la pagina
   */
  	
	  function Vista_FormularioModificarDoc_suministro($accion,$DocTemporal,$BodegaSatelite,$Tipo,$bodega_doc_id)
    {
        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= "<script>";
        $html .= " function Imprimir(direccion,empresa_id,orden_requisicion_id)  ";
        $html .= "  { ";
        $html .= " var url=direccion+'?empresa_id='+empresa_id+'&orden_requisicion_id='+orden_requisicion_id; ";
        $html .= " window.open(url,'','width=800,height=600,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes'); ";
        $html .= "  }";
        $html .= "</script>";
        $html .= "<script>";
        $html .= " function Paginador(Formulario,offset)";
        $html .= " { ";
        $html .= "  xajax_Listado_Pacientes(Formulario,offset);";
        $html .= " } ";
        $html .= "</script>";
        $html .= " <script> \n";
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
        $html .=" </script>\n";
        $html .= "<script>\n";
        $html .= "  function mOvr(src,clrOver)\n";
        $html .= "  {\n";
        $html .= "    src.style.background = clrOver;\n";
        $html .= "  }\n";
        $html .= "  function mOut(src,clrIn)\n";
        $html .= "  {\n";
        $html .= "    src.style.background = clrIn;\n";
        $html .= "  }\n";
        $html .= "  function acceptDate(evt)\n";
        $html .= "  {\n";
        $html .= "    var nav4 = window.Event ? true : false;\n";
        $html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
        $html .= "    return (key <= 13 ||(key >= 47 && key <= 57));\n";
        $html .= "  }\n";
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
        $html .= "</script>\n";
        $html .= $ctl->AcceptNum(false);
        $html .= ThemeAbrirTabla('BUSCAR PACIENTE -RELIZAR SUMINISTRO');
        $html .= "  <center>";
        $html .= "  <table border=\"0\" width=\"80%\"  class=\"modulo_table_list\"  align=\"center\" rules=\"none\">\n";
        $html .= "    <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\">";
        $html .= "      TMP-ID";
        $html .= "      </td>";
        $html .= "      <td align=\"left\">";
        $html .= "          ".$DocTemporal['formula_suministro_id_tmp'];
        $html .= "      </td>";
        $html .= "      <td class=\"formulacion_table_list\">";
        $html .= "      FECHA";
        $html .= "      </td>";
        $html .= "      <td align=\"left\">";
        $html .= "          ".$DocTemporal['fecha_registro'];
        $html .= "      </td>";
        $html .= "      <td class=\"formulacion_table_list\">";
        $html .= "      USUARIO";
        $html .= "      </td>";
        $html .= "      <td align=\"left\">";
        $html .= "          ".$DocTemporal['nombre'];
        $html .= "      </td>";
        $html .= "    </tr>";
        $html .= "    <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\">";
        $html .= "      ESTABLECIMIENTO DE SANIDAD MILITAR";
        $html .= "      </td>";
        $html .= "      <td colspan=\"5\" align=\"left\">";
        $html .= "          ".$DocTemporal['tipo_id_tercero']." ".$DocTemporal['tercero_id']."-".$DocTemporal['nombre_tercero'];
        $html .= "      </td>";
        $html .= "    </tr>";
        $html .= "    <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" colspan=\"2\">";
        $html .= "      OBSERVACION ";
        $html .= "      </td>";
        $html .= "      <td colspan=\"5\" align=\"left\" colspan=\"4\">";
        $html .= "          ".$DocTemporal['observacion'];
        $html .= "      </td>";
        $html .= "    </tr>";
        $html .= "  </table>\n";
        $html .= "</center>";
        if(!empty($BodegaSatelite))
        {

            $html .= "<br>";
            $html .= "<center>";
            $html .= "  <table border=\"0\" width=\"70%\"   class=\"modulo_table_list\"  align=\"center\" rules=\"none\">\n";
            $html .= "    <tr class=\"modulo_list_claro\">";
            $html .= "          <td  class=\"formulacion_table_list\" colspan=\"4\">";
            $html .= "              EMPRESA -GENERA EL SUMINISTRO";
            $html .= "          </td>";
            $html .= "    </tr>";
            $html .= "    <tr class=\"modulo_list_claro\">";
            $html .= "          <td  class=\"formulacion_table_list\">";
            $html .= "              CENTRO UTILIDAD";
            $html .= "          </td>";
            $html .= "          <td >";
            $html .= "              ".$BodegaSatelite['centro'];
            $html .= "          </td>";
            $html .= "          <td class=\"formulacion_table_list\">";
            $html .= "              BODEGA";
            $html .= "          </td>";
            $html .= "          <td >";
            $html .= "              ".$BodegaSatelite['descripcion'];
            $html .= "          </td>";
            $html .= "    </tr>";
            $html .= "  </table>";
            $html .= "</center>";
        }
        $html .= "<br>";
        if(!empty($bodega_doc_id))
        {
            $html .= "  <form method=\"post\" name=\"FormularioBuscador\" id=\"FormularioBuscador\"> ";
            $html .= "	<table align=\"center\" border=\"0\" width=\"70%\" class=\"modulo_table_list\">\n";
            $html .= "		<tr class=\"formulacion_table_list\" >\n";
            $html .= "      <td>";
            $html .= "      TIPO DE IDENTIFICACION";
            $html .= "      </td  >";
            $html .= "      <td  align=\"left\" class=\"modulo_list_claro\">";
            $html .= "				  <select name=\"tipo_id_paciente\" class=\"select\">\n";
            $html .= "            <option value = '-1'>--  SELECCIONE --</option>\n";
            $csk = "";
            foreach($Tipo as $indice => $valor)
            {
                $sel = ($valor['tipo_id_tercero']==$request['tipo_id_paciente'])? "selected":"";
                $html .= "  <option value=\"".$valor['tipo_id_tercero']."\" ".$sel.">".$valor['descripcion']."</option>\n";
            }
            $html .= "				  </select>\n";
            $html .= "      </td>";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"formulacion_table_list\" >\n";
            $html .= "      <td >";
            $html .= "     IDENTIFICACION ";
            $html .= "      </td>";
            $html .= "      <td class=\"modulo_list_claro\" >";
            $html .= "      <input type=\"text\" class=\"input-text\" name=\"identificacion\" id=\"identificacion\" style=\"width:100%\">";
            $html .= "      </td>";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"formulacion_table_list\" >\n";
            $html .= "      <td class=\"formulacion_table_list\" >";
            $html .= "      NOMBRE COMPLETO";
            $html .= "      </td>";
            $html .= "      <td class=\"modulo_list_claro\" >";
            $html .= "      <input type=\"text\" class=\"input-text\" name=\"nombre\" id=\"nombre\" style=\"width:100%\">";
            $html .= "		</tr>\n";
            $html .= "  </table>\n";
            $html .= "	<table align=\"center\" border=\"0\" width=\"10%\" >\n";
            $html .= "		<tr align=\"center\" >\n";
            $html .= "      <td >";
            $html .= "      <input type=\"hidden\" name=\"suministro_id\" id=\"suministro_id\" value=\"".$_REQUEST['suministro_id']."\" >";
            $html .= "      <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$empresa."\" >";
            $html .= "      <input type=\"hidden\" name=\"centro_utilidad\" id=\"centro_utilidad\" value=\"".$centro_utilidad."\" >";
            $html .= "      <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"".$bodega."\" >";
            $html .= "      <input type=\"button\" class=\"input-submit\" value=\"buscar\" style=\"width:100%\" onclick=\"xajax_Listado_Pacientes(xajax.getFormValues('FormularioBuscador'),'".$DocTemporal."');\" >";
            $html .= "      </td>";
            $html .= "		</tr>\n";
            $html .= "  </table>\n";
            $html .= "  </form>";
            $html .= "<br>";
            $html .= "   <center>";
            $html .= "   <div id=\"paginador\"></div>";
            $html .= "   <form id=\"Productos\" name=\"Productos\"> ";
            $html .= "<div id=\"ListaProductos\" style=\"50%;width:95%;height:140px;overflow:scroll;display:none;\"></div>";
            $html .= "  </form>";
            $html .= "<div id=\"Boton_ListaProductos_s\"></div>";
            $html .= "   </center>";
            $html .= "<br>";
            
            $html .= "   <form id=\"FormularioProductosTemporal_\" name=\"FormularioProductosTemporal_\"> ";
            $html .= "<div id=\"ProductosEnTemporal_s\"></div>";
            $html .= "  </form>";    
            $html .= "  <script>";
            $html .= "  xajax_Listado_Productos_TMP_s('".$_REQUEST['suministro_id']."');";
            $html .= "  </script>";
          }else
          {
          $html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
          $html .= "      <td align=\"center\" class=\"label_error\">\n";
          $html .= "  NO EXISTE UN DOCUMENTO PARAMETRIZADO PARA REALIZAR EL SUMINISTRO ";
          $html .= "      </td>\n";
          $html .= "	</table>\n";

          }			
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$accion['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			$html .= $this->CrearVentana(800,"BUSCADOR DE PRODUCTOS");
			return $html;
    }
 /**
    * Funcion final para el suministro
    * @return string $html retorna la cadena con el codigo html de la pagina
   */
  	
	function Vista_suministros_Final($accion,$bodegas_doc_id,$numeracion)
    {
			$ctl = AutoCarga::factory("ClaseUtil");
			$html .= "<script>";
			$html .= " function Imprimir(direccion,bodegas_doc_id,numeracion)  ";
			$html .= "  { ";
			$html .= " var url=direccion+'?bodegas_doc_id='+bodegas_doc_id+'&numeracion='+numeracion; ";
			$html .= " window.open(url,'','width=800,height=600,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes'); ";
			$html .= "  }";
			$html .= "</script>";
			$html .= ThemeAbrirTabla('INFORMACION DE  SUMINISTRO');
			$html .= "<center>";
			$html .= "  <table border=\"0\" width=\"70%\"   class=\"modulo_table_list\"  align=\"center\" rules=\"none\">\n";
			$html .= "    <tr class=\"modulo_list_claro\">";
			$html .= "          <td  class=\"formulacion_table_list\" colspan=\"4\">";
			$html .= "             SE REALIZO EL SUMINISTRO CORRECTAMENTE ";
			$html .= "          </td>";
			$html .= "    </tr>";
		
			$html .= "  </table>";
			$html .= "</center>";
			
			
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$accion['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			
		
      $html .= "  <script>";
			$html .= "  xajax_imprimir_documento('".$bodegas_doc_id."','".$numeracion."');";
			$html .= "  </script>";
			$html .= ThemeCerrarTabla();
			$html .= $this->CrearVentana(800,"BUSCADOR DE PRODUCTOS");
			
			return $html;
    }
	
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
			//Mostrar Span
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
			//En ese espacio se visualiza la informacion extraida de la base de datos.
			$html .= "  </div>\n";
			$html .= "</div>\n";


    
      return $html;
    }    
    
	}
?>