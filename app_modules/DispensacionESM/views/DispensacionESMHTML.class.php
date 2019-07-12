<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: DispensacionESMHTML.class.php,v 1.0 
	* @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	IncludeClass("ClaseHTML");
	IncludeClass("ClaseUtil");

	class DispensacionESMHTML
	{
	/**
		* Constructor de la clase
	*/

	function  DispensacionESMHTML()
	{}
   /** Function para el Menu de DispensacionESM
	* @param array $action Vector de links de la aplicacion
	* @return String
		*/
		function FormaMenu($action,$permisos,$menu_dispensacion)
		{
			$html  = ThemeAbrirTabla('DISPENSACION DE MEDICAMENTOS ');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
			$html .= "				</tr>\n";
			if(!empty($permisos))
      {
          $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
          $html .= "      <td  class=\"label\"  align=\"center\">\n";
          $html .= "       <a href=\"".$action['formulacion']."\">\n";
          $html .= "       FORMULACION DE MEDICAMENTOS</a>\n";
          $html .= "      </td>\n";
          $html .= "  </tr>\n";
      }
     
          $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
          $html .= "      <td  class=\"label\"  align=\"center\">\n";
          $html .= "       <a href=\"".$action['Formulas']."\">\n";
          $html .= "       BUSCAR - DISPENSAR FORMULAS</a>\n";
          $html .= "      </td>\n";
          $html .= "  </tr>\n";

      $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td  class=\"label\"  align=\"center\">\n";
			$html .= "       <a href=\"".$action['tickets']."\">\n";
			$html .= "       TICKETS DE DISPENSACION</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
       if(!empty($menu_dispensacion))
      {
         
          if($menu_dispensacion['menu_formulacion_externa']=='1')
          {
        
              $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
              $html .= "      <td  class=\"label\"  align=\"center\">\n";
              $html .= "       <a href=\"".$action['formulacion_externa_esm']."\">\n";
              $html .= "      FORMULACION EXTERNA - ESM</a>\n";
              $html .= "      </td>\n";
              $html .= "  </tr>\n";
          }
           if($menu_dispensacion['menu_dispensacion_esm']=='1')
          {
            $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
            $html .= "      <td  class=\"label\"  align=\"center\">\n";
            $html .= "       <a href=\"".$action['formula_esm']."\">\n";
            $html .= "       BUSCAR -DISPENSAR FORMULAS ESM</a>\n";
            $html .= "      </td>\n";
            $html .= "  </tr>\n";
            
          }
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
    /**
    * Funcion donde se crea la forma que permite realizar la busqueda de las formulas del paciente
    * @param array $action Vector de links de la aplicaion
    * @param array $Tipo  Vector de tipos de identificacion
    * @param array $request vector que contiene la informacion del request
    * @param array $datos vector que contiene la informacion del paciente
    * @param string $pagina cadena con el numero de la pagina que se esta visualizando
    * @param string $conteo cadena con la cantidad de los datos que se muestran
    * 
    * @return String
		*/
		function FormaBuscarFomula($action,$Tipo,$request,$datos,$empresa,$conteo,$pagina)                             
		{
			$ctl = AutoCarga::factory("ClaseUtil");
			$html  = $ctl->LimpiarCampos();
			$html .= ThemeAbrirTabla('BUSCAR FORMULA - PACIENTE');
			$html .= "<center>\n";
			$html .= "<fieldset class=\"fieldset\" style=\"width:60%\">\n";
			$html .= "  <legend class=\"normal_10AN\" align=\"center\">PACIENTE</legend>\n";
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
			$html .= "      <tr class=\"formulacion_table_list\"> \n";
			$html .= "			  <td >FORMULA No:</td>\n";
			$html .= "			  <td class=\"modulo_list_claro\" >\n";
			$html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[formula_papel]\" style=\"width:100%\" value=".$request['formula_papel'].">\n";
			$html .= "        </td>\n";
			$html .= "	    </tr>\n";
			$html .= "    </table><br>\n";
			$html .= "		<table   width=\"40%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
			$html .= "		  <tr>\n";
			$html .= "	   	  <td align='center'>\n";
			$html .= "			    <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\">\n";
			$html .= "			  </td>\n";
			$html .= "			  <td align='center' colspan=\"1\">\n";
			$html .= "			    <input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formabuscarE)\" value=\"LIMPIAR CAMPOS\">\n";
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
            $html .= "  <table width=\"100%\" class=\"modulo_table_list\"   align=\"center\">";
            $html .= "	  <tr class=\"formulacion_table_list\" >\n";
            $html .= "      <td width=\"25%\">GENERO FORMULA</td>\n";
            $html .= "      <td width=\"5%\"> FORMULA No</td>\n";
            $html .= "      <td width=\"8%\">IDENTIFICACION </td>\n";
            $html .= "      <td width=\"25%\">PACIENTE </td>\n";
            $html .= "      <td width=\"10%\">FECHA FORMULA </td>\n";
            $html .= "      <td width=\"55%\">MEDICO</td>\n";
            $html .= "	    <td colspan=\"2\">OP</td>";
            $html .= "	    <td width=\"5%\">INF</td>";
            $html .= "  </tr>\n";
            $est = "modulo_list_claro"; $back = "#DDDDDD";
            $i=0;
            $mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
					 /*variable*/
            $dias_vigencia_formula= ModuloGetVar('','','dispensacion_dias_vigencia_formula');
            $coun=0;
            foreach($datos as $key => $dtl)
            {
                  $est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
                  $html .= "  <tr class=\"".$est."\"  onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
                  $html .= "      <td ><b>".$dtl['razon_social']."</b></td>\n";
                  $html .= "      <td ><b>".$dtl['formula_papel']."</b></td>\n";
                  $html .= "      <td ><b>".$dtl['tipo_id_paciente']." ".$dtl['paciente_id']."</b></td>\n";
                  $html .= "      <td ><b>".$dtl['apellidos']." ".$dtl['nombres']."</b></td>\n";
                  $html .= "      <td ><b>".$dtl['fecha_formula']."</b></td>\n";
                  $html .= "      <td ><b>".$dtl['tipo_id_tercero']." ".$dtl['tercero_id']." ".$dtl['profesional']." -".$dtl['descripcion_profesional']."</b></td>\n";
                                    
					        $today = date("Y-m-d"); 
                  $hoy=explode("-", $today);
                  
                  $informacion=$mdl->ConsultarInformacionPediente_ESM($dtl['formula_id']);
                  $si_despacho=$mdl->Consultar_Formula_Dispensada($dtl['formula_id']);
						      if(!empty($si_despacho) || (!empty($informacion)) )
                  {
                      $actualizacion=$mdl->UpdateEstad_Form($dtl['formula_id']);
                      $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
                      $html .= "        <a href=\"".$action['consulta_informacion'].URLRequest($dtl)."\">\n";
                      $html .= "          <img border=\"0\" title=\"FORMULA DESPACHADA\"  src=\"".GetThemePath()."/images/cargos.png\">\n";
                      $html .= "        </a>\n";
                      $html .= "      </td>\n";
                  }else
                  {
                      list($a,$m,$d) = split("-",$dtl['fecha_formula']);
                      $fecha_condias = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_vigencia_formula),$a)));
                      if($fecha_condias > $today)
                      {
                        $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
                        $html .= "        <a href=\"".$action['consul'].URLRequest($dtl)."\">\n";
                        $html .= "          <img border=\"0\" title=\"FORMULA ACTIVA\"  src=\"".GetThemePath()."/images/editar.png\">\n";
                        $html .= "        </a>\n";
                        $html .= "      </td>\n";
                    }else
                    {
                        $coun++;
                        $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
                        $html .= "        <a href=\"".$action['consul'].URLRequest($dtl)."\">\n";
                        $html .= "          <img border=\"0\"  title=\"FORMULA VENCIDA\" src=\"".GetThemePath()."/images/alarma.gif\">\n";
                        $html .= "      </td>\n";
                    }
					       }
							if(!empty($informacion))
							{
                    $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
                    $html .= "        <a href=\"".$action['pendiente'].URLRequest($dtl)."\">\n";
                    $html .= "          <img border=\"0\"  title=\"Pendientes\" src=\"".GetThemePath()."/images/pparamedin.png\">\n";
                    $html .= "        </a>\n";
                    $html .= "      </td>\n";
							}else
							{
                    $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
                    $html .= "          <img border=\"0\"  title=\"Pendientes\" src=\"".GetThemePath()."/images/pparamed.png\">\n";
                    $html .= "      </td>\n";
						
							}
							if(empty($si_despacho) && (empty($informacion)))
							{
							
                    $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
                    $html .= "        <a href=\"".$action['anular'].URLRequest($dtl)."\">\n";
                    $html .= "          <img border=\"0\"  title=\"Anular Formula\" src=\"".GetThemePath()."/images/pactivo.png\">\n";
                    $html .= "        </a>\n";
                    $html .= "      </td>\n";
							}
							else
							{
                    $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
                    $html .= "          <img border=\"0\"  title=\"Formula Dispensada\" src=\"".GetThemePath()."/images/panulado.png\">\n";
                    $html .= "      </td>\n";
						
							}
						
              $html .= "    </tr>\n";
				
          }
          $html .= "	</table><br>\n";
          $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
          if($coun > 0)
          {
				  	  $html .= "<embed src=\"".GetBaseURL()."/1.mid\" hidden=\"true\" type=\"midi\" loop=\"true\"></embed > ";
				  }
			}else
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
    * Funcion donde se crea la forma para ver los medicamentos que han sido formulados segun la formula seleccionada 
    * @param array $action Vector de links de la aplicaion
    * @param array $request vector que contiene la informacion del request
    * @param array $datos vector que contiene la informacion de los medicamentos formulados que van hacer despachados
    * @param array $paciente vector que contiene la informacion del paciente
    * @return string $html retorna la cadena con el codigo html de la pagina
    * @return String
    */
   function FormaFomulaPaciente($action,$request,$datos,$paciente,$Cabecera_Formulacion,$request,$Datos_Ad,$dix_r,$medi_form,$formula_id,$datos_ex,$dias_dipensados,$existe_f,$dusuario_id)                           
		{			
            $ctl = AutoCarga::factory("ClaseUtil");
            $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
            $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
            $html  = $ctl->RollOverFilas();
            $html .= " <script> \n";
            $html .= " function recogerTeclaBus(evt) ";
            $html .= " {";
            $html .= "var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   ";
            $html .= "var keyChar = String.fromCharCode(keyCode);";
            $html .= "if(keyCode==13)";
            $html .= "{   ";
            $html .= "   xajax_BuscarProducto1(xajax.getFormValues('buscador'),'".$formula_id."',''); ";
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
            $html .= "  xajax_BuscarProducto1(xajax.getFormValues('buscador'),'".$formula_id."','');  ";
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
            $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
            $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['tipo_id_tercero']." ".$Cabecera_Formulacion['tercero_id']." &nbsp;&nbsp; ".$Cabecera_Formulacion['profesional']." &nbsp;&nbsp;- ".$Cabecera_Formulacion['descripcion_profesional']." \n";
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
            $html .= "              <table   width=\"98%\" class=\"label\" $style>\n";
            $html .= "								<tr>\n";
            $html .= "									<td  colspan=\"12\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTOS  SOLICITADOS</td>\n";
            $html .= "                </tr>\n";
            $html .= "								<tr>\n";
            $html .= "									<td  colspan=\"2\"  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CODIGO</td>\n";
            $html .= "									<td   colspan=\"4\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTO</td>\n";
            $html .= "									<td   colspan=\"3\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">PRINCIPIO ACTIVO</td>\n";
            $html .= "									<td   style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">OP</td>\n";
            $html .= "									</td>\n";
            $html .= "</tr>";
		
            $est = " "; $back = " ";
            for($i=0;$i<sizeof($medi_form);$i++)
            {
						
                $html .= "  <tr   ".$est." onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
                $html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['codigo_producto']." </td>";
                $html .= "  <td align=\"center\" colspan=\"4\">".$medi_form[$i]['descripcion_prod']." ".$medi_form[$i]['concentracion_forma_farmacologica']." </td>";
                $html .= "  <td colspan=\"3\" align=\"center\" width=\"43%\">".$medi_form[$i]['principio_activo']."</td>";
              
                if($medi_form[$i]['sw_marcado']=='1')
                {
                  $html .= "  <td   class=\"label_error\" align=\"center\" >MARCADO</td>";
                }else
                {
                  $html .= "  <td   class=\"label_error\" align=\"center\" ></td>";
                    }
                    $html .= "</tr>";
                    $html .= "    <td colspan = 5>";
                    $html .= "      <table>";
                    $html .= "<tr class=\"$estilo\">";
                    $html .= "</tr>";
                    $html .= "<tr class=\"$estilo\">";
                    $html .= "  <td class=\"label\" align=\"left\" width=\"9%\"><b>Cantidad:<b>".round($medi_form[$i]['cantidad'])."</td>";
                    $html .= "  </tr>";
                    $html .= "<tr class=\"$estilo\">";
                    $html .= "  <td class=\"label\" align=\"left\" width=\"9%\"><b>Tiempo Entrega:<b>".round($medi_form[$i]['tiempo_tratamiento'])." DIA(S)</td>";
                    $html .= "  </tr>";
                    $html .= "     </table>";
                    $html .= "    </td>";
                    $html .= "  </tr>";
                    $html .= " </tr>";
              }
                $html .= "							</table>\n";
                $html .= "					<tr>\n";
                $html .= "						<td align=\"center\">\n";
                $html .= "					<tr>\n";
                $html .= "						<td align=\"center\">\n";
                $html .= "						<td>\n";
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
                $empresa = SessionGetVar("DatosEmpresaAF");
                if($existe_f['usuario_id']==UserGetUID() || (empty($existe_f)))
                {
                    $vencida='';
                    $today = date("Y-m-d"); 
                    $dias_vigencia_formula= ModuloGetVar('','','dispensacion_dias_vigencia_formula');
                    list($d,$m,$a) = split("-",$Cabecera_Formulacion['fecha_formula']);
                    $fecha_condias = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_vigencia_formula),$a)));
                    if($fecha_condias < $today )
                    {
                        $vencida='VENCIDA';
                    }
			
                    if($vencida=='' ||  $Cabecera_Formulacion['sw_autorizado']=='1')
                    {
				                $html .=  "<form name=\"buscador\" id=\"buscador\"  method=\"post\">\n";
                        $html .= "  <div id='error_doc' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
                        $html .= "  <div id=\"ventana1\">\n";
                        $html .= "   <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
                        $html .= "     <tr >\n";
                        $html .= "        <td class=\"formulacion_table_list\" colspan=\"6\">BUSCADOR</td>";
                        $html .= "     </tr>\n";
                        $html .= "     <tr class=\"modulo_table_list_title\">\n";
                        $html .= "        <td  align=\"left\">CODIGO BARRAS</td>\n";
                        $html .= "       <td class=\"modulo_list_claro\" >";
                        $html .= "                          <input type=\"hidden\" id=\"orden_requisicion_id\" name=\"orden_requisicion_id\" value=\"".$Cabecera_Formulacion['formula_id']."\">\n";
                        $html .= "                      <input type=\"hidden\" id=\"empresa_id\" name=\"empresa_id\" value=\"".$empresa['empresa_id']."\">\n";
                        $html .= "                      <input type=\"hidden\" id=\"centro_utilidad\" name=\"centro_utilidad\" value=\"".$empresa['centro_utilidad']."\">\n";
                        $html .= "                       <input type=\"hidden\" id=\"bodega\" name=\"bodega\" value=\"".$empresa['bodega']."\">\n";
                        $html .= "                       <input type=\"hidden\" id=\"sw_ambulatoria\" name=\"sw_ambulatoria\" value=\"".$Cabecera_Formulacion['sw_ambulatoria']."\">\n";
                        $html .= "        <input type=\"text\" name=\"codigo_barras\" id=\"codigo_barras\" class=\"input-text\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event);\">";
                        $html .= "       <input type=\"hidden\" name=\"descripcion\" id=\"descripcion\" value=\"\" >";
                        $html .= "        <input type=\"hidden\" name=\"lote\" id=\"lote\" value=\"\">";
                        $html .= "        </td>\n";
                        $html .= "       <td  align=\"left\">DESCRIPCION</td>\n";
                        $html .= "        <td class=\"modulo_list_claro\" >";
                        $html .= "       <input type=\"text\" name=\"descripcion\" readonly=\"true\" id=\"descripcion\" class=\"input-text\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event)\">";
                        $html .= "      </td>\n";
                        $html .= "       <td  align=\"left\">LOTE</td>\n";
                        $html .= "       <td class=\"modulo_list_claro\" >";
                        $html .= "        <input type=\"text\" name=\"lote\" id=\"lote\" readonly=\"true\"  class=\"input-text\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event)\">";
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
                         $html.= " xajax_MostrarProductox('".$formula_id."'); ";
                        $html.= " </script>" ;
                      }else
                      {
                          $html .= "               <table width=\"60%\" align=\"center\">";
                          $html .= "	<tr>\n";
                          $html .= "      <td   align=\"center\" class=\"label_error\"><U>FORMULA VENCIDA  - ¡ NO SE PUEDE DISPENSAR SIN AUTORIZACION!</U>\n";
                          $html .= "          <img border=\"0\"  title=\"FORMULA VENCIDA\" src=\"".GetThemePath()."/images/alarma.gif\">\n";
                          $html .= "<embed src=\"".GetBaseURL()."/1.mid\" hidden=\"true\" type=\"midi\" loop=\"true\"></embed > ";
                          $html .= "      </td>\n";
                          $html .= "	</tr>\n";
                          $html .= "                 </table>";
                          $privilegios=$obje->Usuario_Privilegios_($empresa);
                          if($privilegios['sw_privilegios']=='1')
                          {
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
                                  $html .= "      <input type=\"button\" class=\"input-submit\" value=\"AUTORIZAR DESPACHO DE LA FORMULA\" style=\"width:100%\" onclick=\"xajax_Autorizacion_Formula('".$formula_id."','".$bodega_otra."',document.getElementById('observaciones').value,'".$request['tipo_id_paciente']."','".$request['paciente_id']."','".$request['plan_id']."','".$request['ips_tercero_id']."','".$request['dispensar']."');\" >";
                                  $html .= "      </td>";
                                  $html .= "		</tr>\n";
                                  $html .= "    </table>";
                          }
							
                      }
                }else
                {
                        $html .= "               <table class=\"modulo_table_list\" width=\"60%\" align=\"center\">";
                        $html .= "	<tr>\n";
                        $html .= "      <td   align=\"center\" class=\"label_error\"><U>FORMULA EN PROCESO DE DISPENSACION   - ¡ NO SE PUEDE DISPENSAR!</U>\n";
                        $html .= "          <img border=\"0\"  title=\"FORMULA EN PROCESO\" src=\"".GetThemePath()."/images/alarma.gif\">\n";
                        //$html .= "<embed src=\"".GetBaseURL()."/1.mid\" hidden=\"true\" type=\"midi\" loop=\"true\"></embed > ";
                        $html .= "      </td>\n";
                        $html .= "      <td   align=\"center\" class=\"label_error\"><U>LUGAR : ".$existe_f['razon_social']."</U>\n";
                        $html .= "      </td>\n";
                        $html .= "      <td   align=\"center\" class=\"label_error\"><U>USUARIO : ".$existe_f['nombre_creador']."</U>\n";
                        $html .= "      </td>\n";
                        $html .= "	</tr>\n";
                        $html .= "                 </table>";
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
    * Funcion donde se crea la forma para visualizar que medicamentos fueron seleccionados para despachar
    * @param array $action Vector de links de la aplicaion
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
  	function Forma_Preparar_Documento_Dispensar_($action,$empresa,$Cabecera_Formulacion,$temporales,$formula_id,$pendiente,$todo_pendiente)
    {
            
            $html  = ThemeAbrirTabla('ENTREGA MEDICAMENTOS ');
            $html .= "<form name=\"FormaPintarEntrega\" id=\"FormaPintarEntrega\"  method=\"post\" >\n";
            $html .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                   <tr class=\"formulacion_table_list\">\n";
            $html .= "                     <td align=\"center\">\n";
            $html .= "                        <a title='farmacia'>FARMACIA:<a>";
            $html .= "                      </td>\n";
            $html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
            $html .= "                          ".$empresa['razon_social']." -".$empresa['centro_utilidad_des'];
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                         BODEGA";
            $html .= "                       </td>\n";
            $html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "                       ".$empresa['bodega_des'];
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
            $html .= "	<br>\n";
            if(!empty($temporales))
            {
              $html .= "  <table width=\"85%\" class=\"modulo_table_list\"   align=\"center\">";
              $html .= "	  <tr class=\"formulacion_table_list\" >\n";
              $html .= "      <td width=\"20%\" >CODIGO PRODUCTO</td>\n";
              $html .= "      <td width=\"35%\">PRODUCTO</td>\n";
              $html .= "      <td width=\"15%\">FECHA VEC</td>\n";
              $html .= "      <td width=\"15%\">LOTE</td>\n";
              $html .= "      <td width=\"30%\">ENTREGA</td>\n";
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
            $html .= "	<br>\n";
            $html .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                     <tr class=\"formulacion_table_list\" >\n";
            $html .= "                        <td rowspan='1' colspan='10' align=\"center\" class=\"modulo_list_claro\"> \n";
            $html .= "                          <fieldset>";
            $html .= "                           <legend>OBSERVACIONES</legend>";
            $html .= "                              <TEXTAREA id='observar' name='observar' ROWS='3' COLS=100 ></TEXTAREA>\n";
            $html .= " <input type=\"hidden\" name=\"observacion2\" value=\"Formula No: ".$Cabecera_Formulacion['formula_papel']."  Paciente:".$Cabecera_Formulacion['tipo_id_paciente']." ".$Cabecera_Formulacion['paciente_id']." ".$Cabecera_Formulacion['nombre_paciente']." > ";
            $html .= "                        </td>\n";
            $html .= "                     </tr>\n";
            $html .= "</table>\n";
            $html .= "<table width=\"85%\" align=\"center\">\n";
            $html .= "  <tr class=\"modulo_list_claro\">\n";
            $html .= "      <td align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "         <input class=\"input-submit\" type=\"button\" value=\"RECLAMA PACIENTE\" onclick=\"xajax_PacienteReclama(document.FormaPintarEntrega.observar.value,'".$formula_id."','".$pendiente."',document.FormaPintarEntrega.observacion2.value,'".$todo_pendiente."')\" class=\"label_error\">\n";
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
		
		function FormaPintarUltimoPaso($action,$formula_id,$pendientes,$todo_pendiente)
   	{
				$html  .= ThemeAbrirTabla('MENSAJE DE ENTREGA DE MEDICAMENTO');
				$html .= "<form name=\"FormaPintarEntrega2\" id=\"FormaPintarEntrega2\"  method=\"post\" >\n";
				$html .= "                 <table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "                     <tr class=\"modulo_table_list_title\"  >\n";
				$html .= "                        <td  class=\"modulo_list_claro\"  colspan='10' align=\"center\"><b> \n";
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
				$mostrar_P = $reporte2->GetJavaReport('app','DispensacionESM','MedicamentoDispensadosFormulacionExterna',
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
          $mostrar_ = $reporte2->GetJavaReport('app','DispensacionESM','MedicamentoPendienteFormulacionExterna',
                            array("formula_id"=>$formula_id),
														array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			
            $funcion2 = $reporte2->GetJavaFunction();
            $html .= "				".$mostrar_."\n";
            $html .= " <td align=\"center\" width=\"33%\"><a href=\"javascript:$funcion2\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> <b>IMPRIMIR PDF PENDIENTES</b></a></td>";
         }
        $html .= "  </tr>\n";
        $html .= "</table>\n";
				$html .= "<table align=\"center\">\n";
				$html .= "<br>";
				$html .= "  <tr>\n";
				$html .= "      <td align=\"center\" class=\"label_error\">\n";
				$html .= "        <a href=\"".$action['modulo_formularcion']."\">[SEGUIR FORMULANDO]</a>\n";
				$html .= "      </td>\n";
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
		* Funcion donde se crea la forma mostrar el detalle del despacho
   	* @param array $action Vector de links de la aplicaion
		* @return string $html retorna la cadena con el codigo html de la pagina
		*/
    
		function FormaPintarDetalle($action,$Cabecera_Formulacion,$Datos_Ad,$medicamentos,$pendientes_dis)
		{
       
			$html .= ThemeAbrirTabla('DESPACHO DE MEDICAMENTOS DETALLE');
			$html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend class=\"normal_10AN\">INFORMACION DE LA FORMULA</legend>\n";
			$html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"100%\" class=\"label\" $style>\n";
			
			$html .= "								<tr >\n";
			
			$html .= "									<td style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA DE REGISTRO</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_registro']."\n";
			$html .= "									</td>\n";
			$html .= "									<td style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA DE FORMULA</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_formula']."\n";
			$html .= "									</td>\n";
      $html .= "									<td style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FORMULA No</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['formula_papel']."\n";
			$html .= "									</td>\n";
			$html .= "									<td  class=\"formulacion_table_list\" >HORA</td>\n";
			$html .= "									<td >".$Cabecera_Formulacion['hora_formula']."\n";
			$html .= "									</td> \n";
			$html .= "								</tr>\n";
			$html .= "							</table>\n";
			$html .= "							<table width=\"100%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			$html .= "									<td width=\"50%\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
			$html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['tipo_id_tercero']."".$Cabecera_Formulacion['tercero_id']."&nbsp;&nbsp; ".$Cabecera_Formulacion['profesional']."&nbsp;&nbsp;-".$Cabecera_Formulacion['descripcion_profesional']."\n";
			$html .= "									</td>\n";
			$html .= "							</table>\n";
	
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table  width=\"100%\" class=\"label\" $style>\n";
			$html .= "								<tr>\n";
			$html .= "									<td style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
			$html .= "									<td colspan=\"1\">\n";
			$html .= "										".$Cabecera_Formulacion['tipo_id_paciente']."  ".$Cabecera_Formulacion['paciente_id']."\n";
			$html .= "									</td>\n";
			$html .= "									<td  style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\">NOMBRE COMPLETO</td>\n";
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
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:3pt\">EDAD</td>\n";
			$html .= "									<td >".$edad." &nbsp; $edad_t \n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:3pt\">SEXO</td>\n";
			$html .= "									<td align=\"left\">".$sexo."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";	
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:3pt\">TIPO PLAN</td>\n";
			$html .= "									<td >".$Datos_Ad['tipo_plan']."\n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:3pt\">TIPO VINCULACION</td>\n";
			$html .= "									<td >".$Datos_Ad['vinculacion']."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";	
			$html .= "							</table>\n";
		 	$html .= "						<td>\n";
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
			if(!empty($medicamentos))
			{
				$html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
				$html .= "	<tr>\n";
				$html .= "		<td>\n";
				$html .= "			<fieldset class=\"fieldset\">\n";
				$html .= "				<legend class=\"normal_10AN\">MEDICAMENTOS DISPENSADOS</legend>\n";
				$html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
				$html .= "					<tr>\n";
				$html .= "						<td align=\"center\">\n";
				$html .= "							<table width=\"100%\" class=\"label\" $style>\n";
				$html .= "								<tr >\n";
				
				$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CODIGO</td>\n";
				$html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >MEDICAMENTO</td>\n";
				$html .= "									<td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA VENC</td>\n";
				$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >LOTE</td>\n";
				$html .= "									<td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CANTIDAD</td>\n";
				$html .= "									</td>\n";
				$html .= "								</tr>\n";
				
			
				foreach($medicamentos as $item=>$fila)
				{
				  
						$est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
						$html .= "  <tr class=\"".$est."\"  onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
					
						$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >".$fila['codigo_producto_mini']."</td>\n";
						$html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:3pt\"  >".$fila['descripcion_prod']."</td>\n";
						$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >".$fila['fecha_vencimiento']."</td>\n";
						$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >".$fila['lote']."</td>\n";
						$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" >".round($fila['numero_unidades'])."</td>\n";
            $html .= "									</td>\n";
						$html .= "								</tr>\n";
				}
          $html .= "							</table>\n";
          $html .= "						<td>\n";
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
			}
			if(!empty($pendientes_dis))
      {
          $html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
          $html .= "	<tr>\n";
          $html .= "		<td>\n";
          $html .= "			<fieldset class=\"fieldset\">\n";
          $html .= "				<legend class=\"normal_10AN\">MEDICAMENTOS PENDIENTES-DISPENSADOS </legend>\n";
          $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
          $html .= "					<tr>\n";
          $html .= "						<td align=\"center\">\n";
          $html .= "							<table width=\"100%\" class=\"label\" $style>\n";
          $html .= "								<tr >\n";
          $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CODIGO</td>\n";
          $html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >MEDICAMENTO</td>\n";
          $html .= "									<td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA VENC</td>\n";
          $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >LOTE</td>\n";
          $html .= "									<td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CANTIDAD</td>\n";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          foreach($pendientes_dis as $item=>$fila)
          {
			   
                $est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
                $html .= "  <tr class=\"".$est."\"  onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
                $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" >".$fila['codigo_producto_mini']."</td>\n";
                $html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:3pt\"  >".$fila['descripcion_prod']."</td>\n";
                $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >".$fila['fecha_vencimiento']."</td>\n";
                $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >".$fila['lote']."</td>\n";
                $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >".round($fila['numero_unidades'])."</td>\n";

                $html .= "									</td>\n";
                $html .= "								</tr>\n";
        }
				$html .= "							</table>\n";
				$html .= "						<td>\n";
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

		}	
		
		if(empty($medicamentos) &&  empty($pendientes_dis))
			{
              $html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
              $html .= "	<tr>\n";
              $html .= "		<td>\n";
              $html .= "			<fieldset class=\"fieldset\">\n";

              $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
              $html .= "					<tr> \n";
              $html .= "						<td  class=\"label_error\" align=\"center\"><I>TODO LOS PRODUCTOS QUEDARON PENDIENTES POR DISPENSAR</I>\n";
              $html .= "						<td>\n";
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
		* Funcion donde se muestran los productos pendientes por dispensar
   	* @param array $action Vector de links de la aplicaion
		* @return string $html retorna la cadena con el codigo html de la pagina
		*/
    function FormaFomulaPaciente_P($action,$request,$datos,$paciente,$Cabecera_Formulacion,$request,$Datos_Ad,$opcion,$dix_r,$medi_form,$formula_id)                           
		{			
          $ctl = AutoCarga::factory("ClaseUtil");
          $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
          $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
          $html  = $ctl->RollOverFilas();
          $html .= " <script> \n";
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
        $html .= "     <tr class=\"modulo_table_list_title\">\n";
        $html .= "        <td  align=\"left\">CODIGO BARRAS</td>\n";
        $html .= "       <td class=\"modulo_list_claro\" >";
        $empresa = SessionGetVar("DatosEmpresaAF");
        $html .= "                          <input type=\"hidden\" id=\"orden_requisicion_id\" name=\"orden_requisicion_id\" value=\"".$Cabecera_Formulacion['formula_id']."\">\n";
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
		* Funcion donde se crea una Forma con una Ventana con capas para mostrar informacion
		* en pantalla
		* @param int $tmn Tamaño que tendra la ventana
		* @return string
          */
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
			$html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
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
			$html .= "</script>\n";
			$html .= "<div id='Contenedor2' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "  <div id='titulo2' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
			$html .= "  <div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "  <div id='Contenido2' class='d2Content'>\n";
			$html .= "  </div>\n";
			$html .= "</div>\n";
            return $html;
        }    
	
	
    /* 	Funcion donde se crea la forma que permite mostrar  mensaje donde no se encuentre un documento de bodega para la DispensacionESM de los pacientes
           	* @param array $action Vector de links de la aplicaion
		* @return string $html retorna la cadena con el codigo html de la pagina
		* @return String
		*/
		function FormaMenuMensaje($action)
		{
			$html  = ThemeAbrirTabla('DISPENSACION DE MEDICAMENTOS ');
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td  align=\"center\" class=\"label_error\">\n";
			$html .= "		NO SE ENCONTRO UN DOCUMENTO PARAMETRIZADO PARA LA DISPENSACION DE MEDICAMENTOS</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>";
			$html .= "</fieldset><br>\n";
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
		/**********************DISPENSACION ESM *////////
    
    /**
    * Funcion donde se crea la forma que permite realizar la busqueda de las formulas del paciente
    * @param array $action Vector de links de la aplicaion
    * @param array $Tipo  Vector de tipos de identificacion
    * @param array $request vector que contiene la informacion del request
    * @param array $datos vector que contiene la informacion del paciente
    * @param string $pagina cadena con el numero de la pagina que se esta visualizando
    * @param string $conteo cadena con la cantidad de los datos que se muestran
    * 
    * @return String
		*/
		function FormaBuscarFomulaESM($action,$Tipo,$request,$datos,$empresa,$conteo,$pagina)                             
		{
			$ctl = AutoCarga::factory("ClaseUtil");
			$html  = $ctl->LimpiarCampos();
			$html .= ThemeAbrirTabla('BUSCAR FORMULA - PACIENTE');
			$html .= "<center>\n";
			$html .= "<fieldset class=\"fieldset\" style=\"width:60%\">\n";
			$html .= "  <legend class=\"normal_10AN\" align=\"center\">PACIENTE</legend>\n";
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
			$html .= "      <tr class=\"formulacion_table_list\"> \n";
			$html .= "			  <td >FORMULA No:</td>\n";
			$html .= "			  <td class=\"modulo_list_claro\" >\n";
			$html .= "          <input type=\"text\" class=\"input-text\" name=\"buscador[formula_papel]\" style=\"width:100%\" value=".$request['formula_papel'].">\n";
			$html .= "        </td>\n";
			$html .= "	    </tr>\n";
			$html .= "    </table><br>\n";
			$html .= "		<table   width=\"40%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
			$html .= "		  <tr>\n";
			$html .= "	   	  <td align='center'>\n";
			$html .= "			    <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\">\n";
			$html .= "			  </td>\n";
			$html .= "			  <td align='center' colspan=\"1\">\n";
			$html .= "			    <input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formabuscarE)\" value=\"LIMPIAR CAMPOS\">\n";
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
				$html .= "  <table width=\"100%\" class=\"modulo_table_list\"   align=\"center\">";
				$html .= "	  <tr class=\"formulacion_table_list\" >\n";
				
				$html .= "      <td width=\"10%\">GENERO FORMULA</td>\n";
				$html .= "      <td width=\"5%\"> FORMULA No</td>\n";
				$html .= "      <td width=\"8%\">IDENTIFICACION </td>\n";
				$html .= "      <td width=\"25%\">PACIENTE </td>\n";
				$html .= "      <td width=\"10%\">FECHA FORMULA </td>\n";
				$html .= "      <td width=\"30%\">PUNTO DE ATENCION</td>\n";
			 	$html .= "      <td width=\"55%\">MEDICO</td>\n";
				$html .= "	    <td colspan=\"2\">OP</td>";
				$html .= "	    <td colspan=\"2\">INF</td>";
				$html .= "  </tr>\n";
				$est = "modulo_list_claro"; $back = "#DDDDDD";
				$i=0;
				$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
		
			
			  $dias_vigencia_formula= ModuloGetVar('','','dispensacion_dias_vigencia_formula');
	
          $coun=0;
				foreach($datos as $key => $dtl)
				{
					$est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
					$html .= "  <tr class=\"".$est."\"  onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
					$html .= "      <td ><b>".$dtl['razon_social']."</b></td>\n";
					$html .= "      <td ><b>".$dtl['formula_papel']."</b></td>\n";
					$html .= "      <td ><b>".$dtl['tipo_id_paciente']." ".$dtl['paciente_id']."</b></td>\n";
					$html .= "      <td ><b>".$dtl['apellidos']." ".$dtl['nombres']."</b></td>\n";
					
					$html .= "      <td ><b>".$dtl['fecha_formula']."</b></td>\n";
					$html .= "      <td ><b>".$dtl['esm_tipo_id_tercero']." ".$dtl['esm_tercero_id']." ".$dtl['esm_atendio']." </b></td>\n";
				
					$html .= "      <td ><b>".$dtl['tipo_id_tercero']." ".$dtl['tercero_id']." ".$dtl['profesional_esm']." -".$dtl['descripcion_profesional_esm']."</b></td>\n";
					
						
					
					$today = date("Y-m-d"); 
					$hoy=explode("-", $today);
					$informacion=$mdl->ConsultarInformacionPediente_ESM($dtl['formula_id']);
					
					if($dtl['sw_ambulatoria']=='1')
					{

					   $si_despacho=$mdl->Consultar_Formula_Dispensada($dtl['formula_id']);
						
						if($dtl['sw_estado']!='2')
						{
							if(!empty($si_despacho) || (!empty($informacion)) )
							{
								$actualizacion=$mdl->UpdateEstad_Form($dtl['formula_id']);
								$html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
								$html .= "        <a href=\"".$action['consulta_informacion'].URLRequest($dtl)."\">\n";
								$html .= "          <img border=\"0\" title=\"FORMULA DESPACHADA\"  src=\"".GetThemePath()."/images/cargos.png\">\n";
								$html .= "        </a>\n";
								$html .= "      </td>\n";
							
							}
														
							else
							{
								list($a,$m,$d) = split("-",$dtl['fecha_formula']);
				 			 	$fecha_condias = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_vigencia_formula),$a)));
							    if($fecha_condias > $today)
								{
									$html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
									$html .= "        <a href=\"".$action['consul'].URLRequest($dtl)."\">\n";
									$html .= "          <img border=\"0\" title=\"FORMULA ACTIVA\"  src=\"".GetThemePath()."/images/editar.png\">\n";
									$html .= "        </a>\n";
									$html .= "      </td>\n";
								}else
								{
									$coun++;
									$html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
									$html .= "        <a href=\"".$action['consul'].URLRequest($dtl)."\">\n";
									$html .= "          <img border=\"0\"  title=\"FORMULA VENCIDA\" src=\"".GetThemePath()."/images/alarma.gif\">\n";
									$html .= "      </td>\n";


								}
					
					        }
						}
					}else
					{
						if($dtl['sw_estado']=='1')
						{
										
							list($a,$m,$d) = split("-",$dtl['fecha_formula']);
							$fecha_condias = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_vigencia_formula),$a)));
								if($fecha_condias > $today)
								{
									$html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
									$html .= "        <a href=\"".$action['consul'].URLRequest($dtl)."\">\n";
									$html .= "          <img border=\"0\" title=\"FORMULA ACTIVA\"  src=\"".GetThemePath()."/images/editar.png\">\n";
									$html .= "        </a>\n";
									$html .= "      </td>\n";
								}else
								{
									$coun++;
									$html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
									$html .= "        <a href=\"".$action['consul'].URLRequest($dtl)."\">\n";
									$html .= "          <img border=\"0\"  title=\"FORMULA VENCIDA\" src=\"".GetThemePath()."/images/alarma.gif\">\n";
									$html .= "      </td>\n";
								}
							
					    }else
						{
					
					            $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
								$html .= "        <a href=\"".$action['consulta_informacion'].URLRequest($dtl)."\">\n";
								$html .= "          <img border=\"0\" title=\"FORMULA DISPENSADA\"  src=\"".GetThemePath()."/images/cargos.png\">\n";
								$html .= "        </a>\n";
								$html .= "      </td>\n";
						}
					
			  }				
							if(!empty($informacion))
							{
								$html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
								$html .= "        <a href=\"".$action['pendiente'].URLRequest($dtl)."\">\n";
								$html .= "          <img border=\"0\"  title=\"Pendientes\" src=\"".GetThemePath()."/images/pparamedin.png\">\n";
								$html .= "        </a>\n";
								$html .= "      </td>\n";
							}else
							{
								$html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
								$html .= "          <img border=\"0\"  title=\"Pendientes\" src=\"".GetThemePath()."/images/pparamed.png\">\n";
								$html .= "      </td>\n";
						
							}
							if(empty($si_despacho) && (empty($informacion)))
							{
							
						        $html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
								$html .= "        <a href=\"".$action['anular'].URLRequest($dtl)."\">\n";
								$html .= "          <img border=\"0\"  title=\"Anular Formula\" src=\"".GetThemePath()."/images/pactivo.png\">\n";
								$html .= "        </a>\n";
								$html .= "      </td>\n";
							}
							else
							{
							
							$html .= "      <td  width=\"50%\" align=\"center\" class=\"label_error\">\n";
							$html .= "          <img border=\"0\"  title=\"Formula Dispensada\" src=\"".GetThemePath()."/images/panulado.png\">\n";
							$html .= "      </td>\n";

							
							}
						
					$html .= "    </tr>\n";
				
				}
				$html .= "	</table><br>\n";
				$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
				if($coun > 0)
				{
				  	  $html .= "<embed src=\"".GetBaseURL()."/1.mid\" hidden=\"true\" type=\"midi\" loop=\"true\"></embed > ";
				
				
				}
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
    * Funcion donde se crea la forma para ver los medicamentos que han sido formulados segun la formula seleccionada 
    * @param array $action Vector de links de la aplicaion
    * @param array $request vector que contiene la informacion del request
    * @param array $datos vector que contiene la informacion de los medicamentos formulados que van hacer despachados
    * @param array $paciente vector que contiene la informacion del paciente
    * @return string $html retorna la cadena con el codigo html de la pagina
   
    */
		function FormaFomulaPaciente_ESM($action,$request,$datos,$paciente,$Cabecera_Formulacion,$Cabecera_Formulacion_AESM,$request,$Cabecera_Formulacion_AEM,$Datos_Fueza,$Datos_Ad,$ESM_pac,$opcion,$dix_r,$medi_form,$formula_id,$datos_ex,$dias_dipensados,$existe_f,$dusuario_id)                           
		{			
          $ctl = AutoCarga::factory("ClaseUtil");
          $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
          $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
          $html  = $ctl->RollOverFilas();
          $html .= " <script> \n";
          $html .= " function recogerTeclaBus(evt) ";
          $html .= " {";

          $html .= "var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   ";
          $html .= "var keyChar = String.fromCharCode(keyCode);";
          $html .= "if(keyCode==13)";
          $html .= "{   ";
          $html .= "   xajax_BuscarProductoESM(xajax.getFormValues('buscador'),'".$formula_id."',''); ";
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
          $html .= "  xajax_BuscarProductoESM(xajax.getFormValues('buscador'),'".$formula_id."','');  ";
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
          $html .= "								<tr >\n";
          $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO EVENTO</td>\n";
          $html .= "									<td colspan=\"2\">	".$Cabecera_Formulacion['descripcion_tipo_evento']."\n";
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
			$html .= "						<td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table   width=\"98%\" class=\"label\" $style>\n";
			$html .= "								<tr>\n";
			$html .= "									<td  colspan=\"12\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTOS  SOLICITADOS</td>\n";
			$html .= "								</tr>\n";

      if($Cabecera_Formulacion['sw_ambulatoria']=='0')
			{ 
          $html .= "								<tr>\n";
          $html .= "									<td  colspan=\"2\"  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CODIGO</td>\n";
          $html .= "									<td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTO</td>\n";
          $html .= "									<td   colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">PRINCIPIO ACTIVO</td>\n";
          $html .= "									<td   style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">OP</td>\n";
          $html .= "									</td>\n";
          $html .= "</tr>";
		 	    $est = " "; $back = " ";
			
          for($i=0;$i<sizeof($medi_form);$i++)
          {
						$html .= "  <tr   ".$est." onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
            $html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['codigo_producto_mini']." </td>";
						$html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['descripcion_prod']." ".$medi_form[$i]['concentracion_forma_farmacologica']." </td>";
						$html .= "  <td colspan=\"2\" align=\"center\" width=\"43%\">".$medi_form[$i]['principio_activo']."</td>";
						if($medi_form[$i]['sw_marcado']=='1')
						{
                $html .= "  <td   class=\"label_error\" align=\"center\" >MARCADO</td>";
						}else
						{
                $html .= "  <td   class=\"label_error\" align=\"center\" ></td>";
						}

						$html .= "</tr>";
            if($medi_form[$i]['principio_activo']!="")
            {
								 $html .= "  <tr >";
								 $html .= "    <td colspan = 5>";
								 $html .= "      <table>";
								 $html .= "<tr class=\"$estilo\">";
								 $via_form=$obje->Consultar_Via_Admin($medi_form[$i]['via_administracion_id']);
                 $html .= "</tr>";
   							 $html .= "<tr class=\"$estilo\">";
								 $html .= "  <td class=\"label\" align=\"left\" width=\"9%\"><b>Dosis:<b></td>";
                 $e=$medi_form[$i]['dosis']/floor($medi_form[$i]['dosis']);
								if($e==1)
								{
									 $html .= "  <td  class=\"label\"  align=\"left\" width=\"14%\">".floor($medi_form[$i]['dosis'])."  ".$medi_form[$i]['unidad_dosificacion']."</td>";
								}
								else
								{
									 $html .= "  <td class=\"label\" align=\"left\" width=\"14%\">".$medi_form[$i]['dosis']."  ".$medi_form[$i]['unidad_dosificacion']."</td>";
								}
                $opcion_d=$obje->Consulta_opc_Medicamentos_PosologiaR($medi_form[$i]['fe_medicamento_id']);
                $vector_posologia= $obje->Consulta_Solicitud_Medicamentos_Posologia($opcion_d['opcion'], $medi_form[$i]['fe_medicamento_id']);
//pintar formula para opcion 1
								if($opcion_d['opcion']== 1)
								{
									 $html .= "   <td  class=\"label\" align=\"left\" width=\"50%\">cada ".$vector_posologia[0]['periocidad_id']." ".$vector_posologia[0]['tiempo']."</td>";

								}

//pintar formula para opcion 2
								if($opcion_d['opcion']== 2)
								{
									$html .= " <td  class=\"label\" align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
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
									$html .= "  <td    class=\"label\" align=\"left\" width=\"50%\"><b>a la(s): $frecuencia<b></td>";
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
							$html .= "     </table>";
							$html .= "    </td>";
							$html .= "  </tr>";
						
     						$html .= " </tr>";
          }else
				 {
					 
							$html .= "        <tr >\n";
							$html .= "    <td >";
							$html .= "      <table >";
							$html .= "        <tr >\n";
							$html .= "  <td  class=\"label\"  align=\"left\" width=\"35%\"><b>Cantidad: </b>".round($medi_form[$i]['cantidad'])."</td>";
							$html .= "</tr>";

							$tiempo_tratamiento=$medi_form[$i]['tiempo_tratamiento'];
							$unidad_tiempo_tratamiento=$medi_form[$i]['unidad_tiempo_tratamiento'];

							$html .= "        <tr >\n";
							$html .= "  <td class=\"label\"  align=\"left\" width=\"14%\" ><b>Tiempo Entrega:<b>".$tiempo_tratamiento." DIA(S)</td>";
							$html .= "</tr>";

							$html .= "      </table>";
							$html .= "    </td>";
							$html .= "  </tr>";
            }
					}	
        }else
        {
        
        
        $html .= "								<tr>\n";
				$html .= "									<td  colspan=\"2\"  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CODIGO</td>\n";
				$html .= "									<td   colspan=\"4\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTO</td>\n";
				$html .= "									<td   colspan=\"3\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">PRINCIPIO ACTIVO</td>\n";
				$html .= "									<td   style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">OP</td>\n";
				$html .= "									</td>\n";
        $html .= "</tr>";
		
				$est = " "; $back = " ";
				for($i=0;$i<sizeof($medi_form);$i++)
				{
						
						$html .= "  <tr   ".$est." onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
						$html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['codigo_producto_mini']." </td>";
						$html .= "  <td align=\"center\" colspan=\"4\">".$medi_form[$i]['descripcion_prod']." ".$medi_form[$i]['concentracion_forma_farmacologica']." </td>";
						$html .= "  <td colspan=\"3\" align=\"center\" width=\"43%\">".$medi_form[$i]['principio_activo']."</td>";
						if($medi_form[$i]['sw_marcado']=='1')
						{
							$html .= "  <td   class=\"label_error\" align=\"center\" >MARCADO</td>";
            }else
						{
						  $html .= "  <td   class=\"label_error\" align=\"center\" ></td>";
						}
						$html .= "</tr>";
            $html .= "    <td colspan = 5>";
						$html .= "      <table>";
        		$html .= "<tr class=\"$estilo\">";
								
						$html .= "</tr>";
 
     				$html .= "<tr class=\"$estilo\">";
						$html .= "  <td class=\"label\" align=\"left\" width=\"9%\"><b>Cantidad:<b>".round($medi_form[$i]['cantidad'])."</td>";
						$html .= "  </tr>";
						
            $html .= "<tr class=\"$estilo\">";
            $html .= "  <td class=\"label\" align=\"left\" width=\"9%\"><b>Tiempo Entrega:<b>".round($medi_form[$i]['tiempo_tratamiento'])." DIA(S)</td>";
            $html .= "  </tr>";
							
            $html .= "     </table>";
            $html .= "    </td>";
            $html .= "  </tr>";
 						$html .= " </tr>";
		
			    }
			
            }			
			$html .= "							</table>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "						<td>\n";
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
			$empresa = SessionGetVar("DatosEmpresaAF");
			if($existe_f['usuario_id']==UserGetUID() || (empty($existe_f)))
			{
					
					$vencida='';
					if($Cabecera_Formulacion['sw_ambulatoria']=='1' || $Cabecera_Formulacion['sw_ambulatoria']=='0' )
					{
                  $today = date("Y-m-d"); 
                  $dias_vigencia_formula= ModuloGetVar('','','dispensacion_dias_vigencia_formula');
                  list($d,$m,$a) = split("-",$Cabecera_Formulacion['fecha_formula']);
                  $fecha_condias = date("Y-m-d",(mktime(0,0,0, $m,($d + $dias_vigencia_formula),$a)));
                  if($fecha_condias < $today )
                  {
                    $vencida='VENCIDA';
                  }
										            
					}
				 if($vencida=='' || $Cabecera_Formulacion['sw_autorizado']=='1')
				 {
				 
              $html .=  "<form name=\"buscador\" id=\"buscador\"  method=\"post\">\n";
							$html .= "  <div id='error_doc' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
							$html .= "  <div id=\"ventana1\">\n";
							$html .= "   <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
							$html .= "     <tr >\n";
							$html .= "        <td class=\"formulacion_table_list\" colspan=\"6\">BUSCADOR</td>";
							$html .= "     </tr>\n";
							$html .= "     <tr class=\"modulo_table_list_title\">\n";
							$html .= "        <td  align=\"left\">CODIGO BARRAS</td>\n";
							$html .= "       <td class=\"modulo_list_claro\" >";
							$html .= "                          <input type=\"hidden\" id=\"orden_requisicion_id\" name=\"orden_requisicion_id\" value=\"".$Cabecera_Formulacion['formula_id']."\">\n";
							$html .= "                      <input type=\"hidden\" id=\"empresa_id\" name=\"empresa_id\" value=\"".$empresa['empresa_id']."\">\n";
							$html .= "                      <input type=\"hidden\" id=\"centro_utilidad\" name=\"centro_utilidad\" value=\"".$empresa['centro_utilidad']."\">\n";
							$html .= "                       <input type=\"hidden\" id=\"bodega\" name=\"bodega\" value=\"".$empresa['bodega']."\">\n";
							
							$html .= "                       <input type=\"hidden\" id=\"sw_ambulatoria\" name=\"sw_ambulatoria\" value=\"".$Cabecera_Formulacion['sw_ambulatoria']."\">\n";
              $html .= "        <input type=\"text\" name=\"codigo_barras\" id=\"codigo_barras\" class=\"input-text\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event);\">";
							$html .= "       <input type=\"hidden\" name=\"descripcion\" id=\"descripcion\" value=\"\" >";
							$html .= "        <input type=\"hidden\" name=\"lote\" id=\"lote\" value=\"\">";
							$html .= "        </td>\n";
							$html .= "       <td  align=\"left\">DESCRIPCION</td>\n";
							$html .= "        <td class=\"modulo_list_claro\" >";
							$html .= "       <input type=\"text\" name=\"descripcion\" readonly=\"true\" id=\"descripcion\" class=\"input-text\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event)\">";
              $html .= "      </td>\n";
              $html .= "       <td  align=\"left\">LOTE</td>\n";
              $html .= "       <td class=\"modulo_list_claro\" >";
              $html .= "        <input type=\"text\" name=\"lote\" id=\"lote\" readonly=\"true\"  class=\"input-text\" style=\"width:100%\" onkeydown=\"recogerTeclaBus(event)\">";
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
							$html.= " xajax_MostrarProductoxESM('".$formula_id."'); ";
							$html.= " </script>" ;
					}else
					{
							$html .= "               <table width=\"60%\" align=\"center\">";
							$html .= "	<tr>\n";
							$html .= "      <td   align=\"center\" class=\"label_error\"><U>FORMULA VENCIDA  - ¡ NO SE PUEDE DISPENSAR SIN AUTORIZACION!</U>\n";
							$html .= "          <img border=\"0\"  title=\"FORMULA VENCIDA\" src=\"".GetThemePath()."/images/alarma.gif\">\n";
              $html .= "<embed src=\"".GetBaseURL()."/1.mid\" hidden=\"true\" type=\"midi\" loop=\"true\"></embed > ";
              $html .= "      </td>\n";
							$html .= "	</tr>\n";
							$html .= "                 </table>";
							$privilegios=$obje->Usuario_Privilegios_($empresa);
							if($privilegios['sw_privilegios']=='1')
							{
										
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
												$html .= "      <input type=\"button\" class=\"input-submit\" value=\"AUTORIZAR DESPACHO DE LA FORMULA\" style=\"width:100%\" onclick=\"xajax_Autorizacion_FormulaESM('".$formula_id."','".$bodega_otra."',document.getElementById('observaciones').value,'".$request['tipo_id_paciente']."','".$request['paciente_id']."','".$request['plan_id']."','".$request['ips_tercero_id']."','".$request['dispensar']."');\" >";
												$html .= "      </td>";
												$html .= "		</tr>\n";
												$html .= "    </table>";
								}
						}
        }
        else
        {
		
              $html .= "               <table class=\"modulo_table_list\" width=\"60%\" align=\"center\">";
              $html .= "	<tr>\n";
              $html .= "      <td   align=\"center\" class=\"label_error\"><U>FORMULA EN PROCESO DE DISPENSACION   - ¡ NO SE PUEDE DISPENSAR!</U>\n";
              $html .= "          <img border=\"0\"  title=\"FORMULA EN PROCESO\" src=\"".GetThemePath()."/images/alarma.gif\">\n";
							//$html .= "<embed src=\"".GetBaseURL()."/1.mid\" hidden=\"true\" type=\"midi\" loop=\"true\"></embed > ";
							
							$html .= "      </td>\n";
							$html .= "      <td   align=\"center\" class=\"label_error\"><U>LUGAR : ".$existe_f['razon_social']."</U>\n";
							$html .= "      </td>\n";
							$html .= "      <td   align=\"center\" class=\"label_error\"><U>USUARIO : ".$existe_f['nombre_creador']."</U>\n";
							$html .= "      </td>\n";
							$html .= "	</tr>\n";
							$html .= "                 </table>";
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
    * Funcion donde se crea la forma para visualizar que medicamentos fueron seleccionados para despachar
    * @param array $action Vector de links de la aplicaion
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
		
		function Forma_Preparar_Documento_Dispensar_ESM($action,$empresa,$Cabecera_Formulacion,$temporales,$formula_id,$pendiente,$todo_pendiente)
    {
			
			$html  = ThemeAbrirTabla('ENTREGA MEDICAMENTOS ');
			$html .= "<form name=\"FormaPintarEntrega\" id=\"FormaPintarEntrega\"  method=\"post\" >\n";
			$html .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "                   <tr class=\"formulacion_table_list\">\n";
			$html .= "                     <td align=\"center\">\n";
			$html .= "                        <a title='farmacia'>FARMACIA:<a>";
			$html .= "                      </td>\n";
			$html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
			$html .= "                          ".$empresa['razon_social']." -".$empresa['centro_utilidad_des'];
			$html .= "                       </td>\n";
			$html .= "                      <td align=\"center\">\n";
			$html .= "                         BODEGA";
			$html .= "                       </td>\n";
			$html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "                       ".$empresa['bodega_des'];
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
			$html .= "	<br>\n";
			$html .= "  <table width=\"85%\" class=\"modulo_table_list\"   align=\"center\">";
			$html .= "	  <tr class=\"formulacion_table_list\" >\n";
			$html .= "      <td width=\"20%\" >CODIGO PRODUCTO</td>\n";
			$html .= "      <td width=\"35%\">PRODUCTO</td>\n";
			$html .= "      <td width=\"15%\">FECHA VEC</td>\n";
			$html .= "      <td width=\"15%\">LOTE</td>\n";
			$html .= "      <td width=\"30%\">ENTREGA</td>\n";
			$html .= "  </tr>\n";
			$mdl = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");

			foreach($temporales as $k1 => $dt1)
      {
              
            $html .= "  <tr class=\"modulo_list_claro\" >\n";
            $html .= "      <td align=\"left\"><b>".$dt1['codigo_producto_mini']."</b></td>\n";
            $html .= "      <td align=\"left\"><b>".$dt1['descripcion_prod']."</b></td>\n";
            $html .= "      <td align=\"left\"><b>".$dt1['fecha_vencimiento']."</b></td>\n";
            $html .= "      <td align=\"left\"><b>".$dt1['lote']."</b></td>\n";
            $html .= "      <td align=\"left\"><b>".$dt1['cantidad_despachada']." </b></td>\n";
            $html .= "  </tr>\n" ;
			}
      $html .= "	</table><br>\n";
      $html .= "	<br>\n";
		
			$html .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "                     <tr class=\"formulacion_table_list\" >\n";
			$html .= "                        <td rowspan='1' colspan='10' align=\"center\" class=\"modulo_list_claro\"> \n";
			$html .= "                          <fieldset>";
			$html .= "                           <legend>OBSERVACIONES</legend>";
			$html .= "                              <TEXTAREA id='observar' name='observar' ROWS='3' COLS=100 ></TEXTAREA>\n";
			$html .= " <input type=\"hidden\" name=\"observacion2\" value=\"Formula No: ".$Cabecera_Formulacion['formula_papel']."  Paciente:".$Cabecera_Formulacion['tipo_id_paciente']." ".$Cabecera_Formulacion['paciente_id']." ".$Cabecera_Formulacion['nombre_paciente']." ESM:".$Cabecera_Formulacion['esm_atendio']."\" > ";
			$html .= "                        </td>\n";
			$html .= "                     </tr>\n";
			$html .= "</table>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "  <tr>\n";
			
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "         <input class=\"input-submit\" type=\"button\" value=\"RECLAMA PACIENTE\" onclick=\"xajax_PacienteReclamaESM(document.FormaPintarEntrega.observar.value,'".$formula_id."','".$pendiente."',document.FormaPintarEntrega.observacion2.value,'".$todo_pendiente."')\" class=\"label_error\">\n";
			$html .= "      </td>\n";
			$html .= "      <td>\n";
			$html .= "      </td>\n";
			$html .= "      <td>\n";
			$html .= "      </td>\n";
			$html .= "      <td>\n";
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
		* Funcion donde se crea la forma que permite Mostrar el mensaje de entrega del paciente    
		* @param array $action Vector de links de la aplicaion
    * @return string $html retorna la cadena con el codigo html de la pagina
	*/
		
		function FormaPintarUltimoPaso_ESM($action,$formula_id,$pendientes,$todo_pendiente)
    	{
				$html  .= ThemeAbrirTabla('MENSAJE DE ENTREGA DE MEDICAMENTO');
				$html .= "<form name=\"FormaPintarEntrega2\" id=\"FormaPintarEntrega2\"  method=\"post\" >\n";
				$html .= "                 <table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
			
				$html .= "                     <tr class=\"modulo_table_list_title\"  >\n";
				$html .= "                        <td  class=\"modulo_list_claro\"  colspan='10' align=\"center\"><b> \n";
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
					$mostrar_P = $reporte2->GetJavaReport('app','DispensacionESM','MedicamentoDispensadosESM',
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
				    
					$mostrar_ = $reporte2->GetJavaReport('app','DispensacionESM','MedicamentoPendiente_ESM',
														array("formula_id"=>$formula_id),
														array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			
					$funcion2 = $reporte2->GetJavaFunction();
					$html .= "				".$mostrar_."\n";
					$html .= " <td align=\"center\" width=\"33%\"><a href=\"javascript:$funcion2\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> <b>IMPRIMIR PDF PENDIENTES</b></a></td>";
                    }
				
				
				
					
					$html .= "  </tr>\n";
					$html .= "</table>\n";
				

				$html .= "<table align=\"center\">\n";
				$html .= "<br>";
				$html .= "  <tr>\n";
				$html .= "      <td align=\"center\" class=\"label_error\">\n";
				$html .= "        <a href=\"".$action['modulo_formularcion']."\">[SEGUIR FORMULANDO]</a>\n";
				$html .= "      </td>\n";
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
    	function FormaFomulaPaciente_P_ESM($action,$request,$datos,$paciente,$Cabecera_Formulacion,$Cabecera_Formulacion_AESM,$request,$Cabecera_Formulacion_AEM,$Datos_Fueza,$Datos_Ad,$ESM_pac,$opcion,$dix_r,$medi_form,$formula_id)                           
		{			
        $ctl = AutoCarga::factory("ClaseUtil");
        $obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
        $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
        $html  = $ctl->RollOverFilas();
        $html .= " <script> \n";
        $html .= " function recogerTeclaBus(evt) ";
        $html .= " {";
        $html .= "var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   ";
        $html .= "var keyChar = String.fromCharCode(keyCode);";
        $html .= "if(keyCode==13)";
        $html .= "{   ";
        $html .= "   xajax_BuscarProducto2ESM(xajax.getFormValues('buscador'),'".$formula_id."',1); ";
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
        $html .= "  xajax_BuscarProducto2ESM(xajax.getFormValues('buscador'),'".$formula_id."',1);  ";
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
        $html .= "								<tr >\n";
        $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO EVENTO</td>\n";
        $html .= "									<td colspan=\"2\">	".$Cabecera_Formulacion['descripcion_tipo_evento']."\n";
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
						$html .= "  <td align=\"center\" colspan=\"2\">".$medi_form[$i]['codigo_producto_mini']." </td>";
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
			$html .= "     <tr class=\"modulo_table_list_title\">\n";
			$html .= "        <td  align=\"left\">CODIGO BARRAS</td>\n";
		
			$html .= "       <td class=\"modulo_list_claro\" >";
			$empresa = SessionGetVar("DatosEmpresaAF");
			$html .= "                          <input type=\"hidden\" id=\"orden_requisicion_id\" name=\"orden_requisicion_id\" value=\"".$Cabecera_Formulacion['formula_id']."\">\n";
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
			$html.= " xajax_MostrarProductox2ESM('".$formula_id."'); ";
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
	   
		 	/**  
		* Funcion donde se crea la forma mostrar el detalle del despacho
    * @return string $html retorna la cadena con el codigo html de la pagina
		*/
    
		function FormaPintarDetalle_ESM($action,$Cabecera_Formulacion,$Datos_Fueza,$Datos_Ad,$ESM_pac,$medicamentos,$pendientes_dis)
		{
       
          $html .= ThemeAbrirTabla('DESPACHO DE MEDICAMENTOS DETALLE');
          $html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
          $html .= "	<tr>\n";
          $html .= "		<td>\n";
          $html .= "			<fieldset class=\"fieldset\">\n";
          $html .= "				<legend class=\"normal_10AN\">INFORMACION DE LA FORMULA</legend>\n";
          $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
          $html .= "					<tr>\n";
          $html .= "						<td align=\"center\">\n";
          $html .= "							<table width=\"100%\" class=\"label\" $style>\n";
          $html .= "								<tr >\n";
          $html .= "									<td style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA DE REGISTRO</td>\n";
          $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_registro']."\n";
          $html .= "									</td>\n";
          $html .= "									<td style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA DE FORMULA</td>\n";
          $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_formula']."\n";
          $html .= "									</td>\n";
          $html .= "									<td style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FORMULA No</td>\n";
          $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['formula_papel']."\n";
          $html .= "									</td>\n";
          $html .= "									<td  class=\"formulacion_table_list\" >HORA</td>\n";
          $html .= "									<td >".$Cabecera_Formulacion['hora_formula']."\n";
          $html .= "									</td> \n";
          $html .= "								</tr>\n";
          $html .= "							</table>\n";
          $html .= "							<table width=\"100%\" class=\"label\" $style>\n";
          $html .= "								<tr >\n";
          $html .= "									<td width=\"50%\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >TIPO FORMULA</td>\n";
          $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['descripcion_tipo_formula']."\n";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          $html .= "								<tr >\n";
          $html .= "									<td width=\"50%\"style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >TIPO EVENTO</td>\n";
          $html .= "									<td colspan=\"2\">	".$Cabecera_Formulacion['descripcion_tipo_evento']."\n";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          $html .= "							</table>\n";
          $html .= "					<tr>\n";
          $html .= "						<td align=\"center\">\n";
          $html .= "							<table  width=\"100%\" class=\"label\" $style>\n";
          $html .= "								<tr>\n";
          $html .= "									<td style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
          $html .= "									<td colspan=\"1\">\n";
          $html .= "										".$Cabecera_Formulacion['tipo_id_paciente']."  ".$Cabecera_Formulacion['paciente_id']."\n";
          $html .= "									</td>\n";
          $html .= "									<td  style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\">NOMBRE COMPLETO</td>\n";
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
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:3pt\">EDAD</td>\n";
			$html .= "									<td >".$edad." &nbsp; $edad_t \n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:3pt\">SEXO</td>\n";
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
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:3pt\">TIPO PLAN</td>\n";
			$html .= "									<td >".$Datos_Ad['tipo_plan']."\n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:3pt\">TIPO VINCULACION</td>\n";
			$html .= "									<td >".$Datos_Ad['vinculacion']."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";	
			$html .= "								<tr >\n";
			$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTO DE SANIDAD MILITAR  </td>\n";
			$html .= "									<td colspan=\"1\">\n";
			$html .= "									".$ESM_pac['tipo_id_tercero']." ".$ESM_pac['tercero_id']."  &nbsp; &nbsp;".$ESM_pac['nombre_tercero']."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";

      $html .= "								<tr >\n";
      $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >PUNTO DE ATENCION  </td>\n";
      $html .= "									<td colspan=\"1\">".$Cabecera_Formulacion['esm_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['esm_tercero_id']."  &nbsp; ".$Cabecera_Formulacion['esm_atendio']."\n";
      $html .= "									</td>\n";
      $html .= "								</tr>\n";
      $html .= "								<tr >\n";
      $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
      $html .= "									<td colspan=\"1\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;  ".$Cabecera_Formulacion['profesional_esm']." (".$Cabecera_Formulacion['descripcion_profesional_esm'].")\n";
      $html .= "						     </td>\n";
      $html .= "								</tr>\n";
      $html .= "							</table>\n";
			$html .= "						<td>\n";
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
			if(!empty($medicamentos))
			{
          $html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
          $html .= "	<tr>\n";
          $html .= "		<td>\n";
          $html .= "			<fieldset class=\"fieldset\">\n";
          $html .= "				<legend class=\"normal_10AN\">MEDICAMENTOS DISPENSADOS</legend>\n";
          $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
          $html .= "					<tr>\n";
          $html .= "						<td align=\"center\">\n";
          $html .= "							<table width=\"100%\" class=\"label\" $style>\n";
          $html .= "								<tr >\n";
          
          $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CODIGO</td>\n";
          $html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >MEDICAMENTO</td>\n";
          $html .= "									<td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA VENC</td>\n";
          $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >LOTE</td>\n";
          $html .= "									<td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CANTIDAD</td>\n";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          foreach($medicamentos as $item=>$fila)
          {
				  	$est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
						$html .= "  <tr class=\"".$est."\"  onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
						$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >".$fila['codigo_producto_mini']."</td>\n";
						$html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:3pt\"  >".$fila['descripcion_prod']."</td>\n";
						$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >".$fila['fecha_vencimiento']."</td>\n";
						$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >".$fila['lote']."</td>\n";
						$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" >".round($fila['numero_unidades'])."</td>\n";
            $html .= "									</td>\n";
						$html .= "								</tr>\n";
				}
          $html .= "							</table>\n";
          $html .= "						<td>\n";
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
			}
			if(!empty($pendientes_dis))
      {
          $html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
          $html .= "	<tr>\n";
          $html .= "		<td>\n";
          $html .= "			<fieldset class=\"fieldset\">\n";
          $html .= "				<legend class=\"normal_10AN\">MEDICAMENTOS PENDIENTES-DISPENSADOS </legend>\n";
          $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
          $html .= "					<tr>\n";
          $html .= "						<td align=\"center\">\n";
          $html .= "							<table width=\"100%\" class=\"label\" $style>\n";
          $html .= "								<tr >\n";
          
          $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CODIGO</td>\n";
          $html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >MEDICAMENTO</td>\n";
          $html .= "									<td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA VENC</td>\n";
          $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >LOTE</td>\n";
          $html .= "									<td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CANTIDAD</td>\n";
          $html .= "									</td>\n";
          $html .= "								</tr>\n";
          foreach($pendientes_dis as $item=>$fila)
          {
			   
              $est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
              $html .= "  <tr class=\"".$est."\"  onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";

              $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" >".$fila['codigo_producto_mini']."</td>\n";
              $html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:3pt\"  >".$fila['descripcion_prod']."</td>\n";
              $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >".$fila['fecha_vencimiento']."</td>\n";
              $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >".$fila['lote']."</td>\n";
              $html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\"  >".round($fila['numero_unidades'])."</td>\n";

              $html .= "									</td>\n";
              $html .= "								</tr>\n";
          }
			
                $html .= "							</table>\n";

                $html .= "						<td>\n";
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

        }	
		
      if(empty($medicamentos) &&  empty($pendientes_dis))
			{
				$html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
				$html .= "	<tr>\n";
				$html .= "		<td>\n";
				$html .= "			<fieldset class=\"fieldset\">\n";
				$html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
				$html .= "					<tr> \n";
				$html .= "						<td  class=\"label_error\" align=\"center\"><I>TODO LOS PRODUCTOS QUEDARON PENDIENTES POR DISPENSAR</I>\n";
        $html .= "						<td>\n";
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
  }
?>