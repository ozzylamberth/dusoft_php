<?php
/**
  * @package -SIIS
  * @version $Id$
  * @copyright 
  * @author 
  */
  /**
  * Clase Vista: MensajesModuloHTML
  * Clase encargada de crear las formas para mostrar el menu principal del modulo 
  * y los mensajes al usuario
  *
  * @package -SIIS
  * @version
  * @copyright 
  * @author 
  */
	class MensajesModuloHTML	
	{
		/****************************************************************
		* Constructor de la clase
		*****************************************************************/
		function MensajesModuloHTML(){}
		
		
		/******************************************************************
		* Crea un menu principal para el modulo
		*
		* @param array $action Vector que continen los link de la aplicacion
		* @param array $permiso Vector con los datos de los permisos del usuario
		* @return string
		*******************************************************************/
		function FormaMenuInicial($action)
		{
			$html  = ThemeAbrirTabla('AFILIACION CLIENTES FARMACIAS');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" height=\"40%\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL DE OPCIONES</td>\n";
			$html .= "				</tr>\n";      
  			$html .= "				<tr>\n";
  			$html .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
  			$html .= "						<a href=\"".$action['buscarCliente']."\"><b>REGISTRO DE CLIENTES FARMACIAS</b></a>\n";
  			$html .= "					</td>\n";
  			$html .= "				</tr>\n";
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
    
		/***********************************************************************
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		* @param array $action vector que contine los link de la aplicacion
		* @param string $mensaje Cadena con el texto del mensaje a mostrar en pantalla
		* @return string
		************************************************************************/
		function FormaMensajeModulo($action,$mensaje)
		{
			$html  = ThemeAbrirTabla('DUANA & CIA. LTDA');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$mensaje."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
		
		/***********************************************************************
		* Funcion : forma para busqueda de clientes
		************************************************************************/	
		
		function FormaBuscarCliente($action,$tipo_identificacion,$planes)
		{
			$html  = ThemeAbrirTabla('BUSCAR CLIENTE');
			$html .= "<script>\n";
			$html .= "	function ValidarDatos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.tipo_id_paciente.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL TIPO DE DOCUMENTO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.tipo_plan.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL TIPO DE PLAN DEL CLIENTE\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";			
			$html .= "		if(forma.documento.value  == \"\" )\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE INGRESAR EL NRO. DE DOCUMENTO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		xajax_BuscarCliente(xajax.getFormValues('registrar_afiliacion'));\n";
			$html .= "	}\n";
			$html .= "	function continuarAfiliacion()\n";
			$html .= "	{\n";
			$html .= "		document.registrar_afiliacion.action =\"".$action['registrar']."\"; \n";
			$html .= "		document.registrar_afiliacion.submit();\n";
			$html .= "	}\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key == 8 || key <=13 || (key >= 48 && key <= 57));\n"; 
			$html .= "	}\n";			
			$html .= "</script>\n";
			$html .= "<form name=\"registrar_afiliacion\" id=\"registrar_afiliacion\" action=\"javascript:ValidarDatos(document.registrar_afiliacion)\" method=\"post\">";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
			$html .= "	<table border=\"-1\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td colspan=\"2\">VERIFICACION CLIENTE</td>\n";
			$html .= "		</tr>\n";
			
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">PLAN DE AFILIACION DEL CLIENTE: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"tipo_plan\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
			
			foreach($planes as $key => $datos)
			$html .= "					<option value=\"".$datos['nombre_tbl']."\" >".$datos['descripcion']."</option>\n";
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";			
			
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"35%\" style=\"text-align:left;text-indent:8pt\">TIPO DOCUMENTO: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"tipo_id_paciente\" class=\"select\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
			
			foreach($tipo_identificacion as $key => $datos)
			$html .= "					<option value=\"".$datos['tipo_id_paciente']."\" >".$datos['descripcion']."</option>\n";
			$html .= "				</select>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td style=\"text-align:left;text-indent:8pt\" >DOCUMENTO: </td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<input type=\"text\" maxlength=\"16\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" name=\"documento\" value=\"\" style=\"width:50%\" >\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "	<table border=\"-1\" width=\"50%\" align=\"center\" >\n";
			$html .= "		<tr>\n";
			$html .= "				<td align=\"center\"><br>\n";
			$html .= "					<input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"Buscar\">";
			$html .= "				</td>";
			$html .= "			</form>";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<td align=\"center\"><br>\n";
			$html .= "					<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "				</td>";
			$html .= "			</form>";
			$html .= "		</tr>";
			$html .= "	</table>";
			$html .= ThemeCerrarTabla();
			return $html;
		}	
		
		/***********************************************************************
		* Funcion : forma para registro de datos cliente
		*@ return string html
		************************************************************************/		
		function FormaRegistrarCliente($action,$planes,$tiposId,$dptos,$psis_id,$request,$sex,$zone,$estr,$ecivil,$tipoaf)
		{
		    $cls = Autocarga::factory("InterfacesPlanesDuana","classes","app","Clientes_Farmacias_Duana");
			$subplanes = $cls->GetSubplan($request['tipo_plan']);
			//print_r($request['tipo_plan']);
		    $html  = "<script>\n"; 
			$html .= "	function ValidarCampos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.dpto.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL DEPARTAMENTO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.municipio.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL MUNICIPIO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";  
			$html .= "		if(forma.zona.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR LA ZONA DE RESIDENCIA\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";   
			$html .= "		if(forma.estrato.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL ESTRATO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n"; 
			$html .= "		if(forma.estado_civil.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL ESTADO CIVIL\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";  
			$html .= "		if(forma.sexo.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL GENERO (SEXO)\";\n";
			$html .= "			return;\n";
			$html .= "		}\n"; 
			if($request['tipo_plan'] == 'hosp_ablanque')
			{
			 $html .= "		if(forma.subplan.value == \"-1\")\n";
			 $html .= "		{\n";
			 $html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL PLAN DEL CONVENIO\";\n";
			 $html .= "			return;\n";
			 $html .= "		}\n"; 
			}
			$html .= "		if(forma.tipo_afiliado.value == \"-1\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL TIPO DE AFILIADO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";      
			$html .= "		if(forma.primer_nombre.value  == \"\" )\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE INGRESAR EL PRIMER NOMBRE DEL CLIENTE\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.pmer_apellido.value  == \"\" )\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE INGRESAR EL PRIMER APELLIDO DEL CLIENTE\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.direccion.value  == \"\" )\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE INGRESAR LA DIRECCION DEL CLIENTE\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";	
			$html .= "		if(forma.telefono.value  == \"\" )\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE INGRESAR EL TELEFONO FIJO DEL CLIENTE\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.fecha_naci.value  == \"\" )\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE INGRESAR LA FECHA DE NACIMIENTO DEL CLIENTE\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";  
			$html .= "    document.registrar_afiliacion.action =\"".$action['crear']."\"; \n";  //llamado a metodo de insercion (controller)
			$html .= "    document.registrar_afiliacion.submit();\n";
			$html .= "	}\n";		 
			$html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || key==45 || (key >= 48 && key <= 57));\n";
			$html .= "	}\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
		    $html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
		    $html .= "	}\n";		 
		    $html .= "</script>\n";
		 $html .= ThemeAbrirTabla('REGISTRAR CLIENTES FARMACIAS');
		 $html .= "<form name=\"registrar_afiliacion\" id=\"registrar_afiliacion\" action=\"javascript:ValidarCampos(document.registrar_afiliacion)\" method=\"post\">\n";
		 $html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";         
		 $html .= "<input type=\"hidden\" name=\"tipo_pais_id\" id=\"tipo_pais_id\" value=\"".$psis_id."\">\n";
         $html .= "<table border=\"0\" width=\"98%\" align=\"center\" >\n";
		 $html .= "	<tr>\n";
		 $html .= "		<td>\n";
		 $html .= "			<fieldset class=\"fieldset\">\n";
		 $html .= "				<legend class=\"normal_10AN\">INFORMACION DEL CLIENTE (Campos con * son requeridos)</legend>\n";
		 $html .= "				<table border=\"0\" width=\"100%\" cellspacing=\"2\">\n";
		 $html .= "					<tr>\n";
		 $html .= "					 <td align=\"center\">\n";
         $html .= "						 <table border=\"1\" width=\"98%\" class=\"label\" >\n"; //$style
     
		 $html .= "								<tr >\n";
		 $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PLAN DE AFILIACION</td>\n";
		 $html .= "									<td colspan=\"2\">\n";
         $html .= "                   				<input type=\"text\" class=\"input-text\" name=\"plan\" readonly=\"readonly\" style=\"width:50%; background-color:#C0C0C0\" maxlength=\"30\" value=\"".$request['tipo_plan']."\">\n ";
		 $html .= "                					</td>\n";
		 $html .= "								  </tr>\n";
     
		 $html .= "								   <tr >\n";
		 $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO IDENTIFICACION</td>\n";
		 $html .= "									<td colspan=\"2\">\n";
         $html .= "                                       <input type=\"text\" class=\"input-text\" name=\"tipo_id\" readonly=\"readonly\" style=\"width:10%; background-color:#C0C0C0\" maxlength=\"2\" value=\"".$request['tipo_id_paciente']."\">\n ";     
		 $html .= "                  				</td>\n";
		 $html .= "									</tr>\n";
      
		 $html .= "               				   <tr>\n";
		 $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >NUMERO DOCUMENTO</td>\n";
		 $html .= "									<td width=\"10%\" align=\"right\">\n";
		 $html .= "										<input type=\"text\" class=\"input-text\" name=\"documento\" readonly=\"readonly\" style=\"width:100%; background-color:#C0C0C0\" maxlength=\"16\" value=\"".$request['documento']."\">\n";
		 $html .= "									</td>\n";
		 
		 if($request['tipo_plan'] == 'hosp_ablanque' || $request['tipo_plan'] == 'facturacion_eventos')
		 {
		  $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PLAN DE ATENCION";
		  $html .= "									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
		  $html .= "                                    <select name=\"subplan\" class=\"select\">\n";
		  $html .= "                                      <option value=\"-1\">--SELECCIONAR--</option>\n";
		  foreach($subplanes as $k=>$v)
		  {
		    $html .= "                                    <option value=\"".$v['subplan']."\">".$v['subplan']."</option>\n";
		  }
		  $html .= "                                    </select>\n";
		  $html .= "									</td>\n";
		 }
		 
		 $html .= "               				   </tr>\n";

		 $html .= "               			 <tr>\n"; 
		 $html .= "                 	 	 	    <td width=\"30%\">\n";     
		 $html .= "                  				 <table border=\"1\" width=\"100%\" cellspacing=\"2\">\n";
		 $html .= "                   					<tr>\n";
		 $html .= "									      <td style=\"text-align:left;text-indent:8pt\" width=\"50%\" class=\"formulacion_table_list\" >PAIS</td>\n";
		 $html .= "									       <td width=\"100%\" align=\"right\">\n";
		 $html .= "										     <input type=\"text\" class=\"input-text\" readonly=\"readonly\" name=\"pais\" style=\"width:100%; background-color:#C0C0C0\" maxlength=\"16\" value=\"COLOMBIA\">\n";
		 $html .= "									       </td>\n";
		 $html .= "                   			        </tr>\n";
     
		 $html .= "                				     <tr>\n";
		 $html .= "									   <td width=\"70%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >DEPARTAMENTO</td>\n";
		 $html .= "									   <td colspan=\"2\">\n";
		 $html .= "										 <select name=\"dpto\" class=\"select\" onchange=\"xajax_BuscarMunicipios(xajax.getFormValues('registrar_afiliacion'))\">\n";
		 $html .= "											 <option value=\"-1\">-SELECCIONAR-</option>\n"; 
         foreach($dptos as $key => $value)
		{
         $html .= "											 <option value=\"".$value['tipo_dpto_id']."\" >".$value['departamento']."</option>\n";
		 }
		 $html .= "                   					 </select>\n";
		 $html .= "                    					</td>\n";
		 $html .= "                 				 </tr>\n";
 
		 $html .= "          				        <tr>\n";
		 $html .= "									   <td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >MUNICIPIO</td>\n";
		 $html .= "									   <td colspan=\"2\">\n";
		 $html .= "									     <div id=\"capa_mpios\">\n";
		 $html .= "										    <select name=\"municipio\" class=\"select\" onchange=\"#\">\n";
		 $html .= "											    <option value=\"-1\">-SELECCIONAR-</option>\n"; 
		 $html .= "                       	                </select>\n";
		 $html .= "                                      </div>\n";
		 $html .= "                                     </td>\n";
		 $html .= "                  			    </tr>\n";

		 $html .= "                                </table>\n";
		 $html .= "                               </td>\n";
     
		 $html .= "                 			  <td width=\"30%\">\n";
		 $html .= "				           			 <table border=\"1\" width=\"100%\" cellspacing=\"2\">\n"; 
		 $html .= "                    				  <tr>\n"; 
		 $html .= "									     <td width=\"50%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PRIMER NOMBRE&nbsp;*</td>\n";
		 $html .= "									     <td width=\"50%\" align=\"right\">\n";
		 $html .= "										    <input type=\"text\" class=\"input-text\" name=\"primer_nombre\" style=\"width:100%\" maxlength=\"20\" value=\"\">\n";
		 $html .= "									     </td>\n";     
		 $html .= "                     				 </tr>\n";  
		 $html .= "                     				  <tr>\n"; 
		 $html .= "									       <td width=\"50%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SEGUNDO NOMBRE</td>\n";
		 $html .= "									       <td width=\"50%\" align=\"right\">\n";
		 $html .= "										    <input type=\"text\" class=\"input-text\" name=\"sgdo_nombre\" style=\"width:100%\" maxlength=\"30\" value=\"\">\n";
		 $html .= "									      </td>\n";     
		 $html .= "                     				</tr>\n";     
		 $html .= "                   				    <tr>\n"; 
		 $html .= "									      <td width=\"50%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PRIMER APELLIDO&nbsp;*</td>\n";
		 $html .= "									      <td width=\"50%\" align=\"right\">\n";
		 $html .= "										    <input type=\"text\" class=\"input-text\" name=\"pmer_apellido\" style=\"width:100%\" maxlength=\"20\" value=\"\">\n";
		 $html .= "									      </td>\n";     
		 $html .= "                     				</tr>\n";      
		 $html .= "				            		</table>\n";     
		 $html .= "                 			 </td>\n";

         $html .= "                 			 <td width=\"50%\">\n";
		 $html .= "				           		  <table border=\"0\" width=\"100%\" cellspacing=\"2\">\n"; 
         $html .= "                     		    <tr>\n"; 
 		 $html .= "									     <td width=\"25%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SEGUNDO APELLIDO</td>\n";
		 $html .= "									     <td width=\"30%\" align=\"right\">\n";
		 $html .= "										    <input type=\"text\" class=\"input-text\" name=\"sgdo_apellido\" style=\"width:100%\" maxlength=\"30\" value=\"\">\n";
		 $html .= "									     </td>\n";
		 $html .= "                    			    </tr>\n";
     
		 $html .= "                     		    <tr>\n";  
  		 $html .= "									      <td width=\"30%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA NACIMIENTO&nbsp;*</td>\n";
		 $html .= "									      <td width=\"10%\" align=\"right\">\n";
		 $html .= "										     <input type=\"text\" align=\"left\" class=\"input-text\" name=\"fecha_naci\" style=\"width:100%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
		 $html .= "									      </td>\n"; 
		 $html .= "									      <td align=\"left\" >".ReturnOpenCalendario('registrar_afiliacion','fecha_naci','-')."</td>\n";		 
		 $html .= "                       	        </tr>\n";

		 $html .= "                     		    <tr>\n";  
		 $html .= "                      				 <td width=\"20%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SEXO</td>\n";     
		 $html .= "									     <td colspan=\"2\">\n";
		 $html .= "										    <select name=\"sexo\" class=\"select\" onchange=\"#\">\n";
		 $html .= "											    <option value=\"-1\">-SELECCIONAR-</option>\n";
         foreach($sex as $key => $value)
         {
		 $html .= "											    <option value=\"".$value['sexo_id']."\" >".$value['descripcion']."</option>\n";
		 }
		 $html .= "                      				   </select>\n";
		 $html .= "                      				</td>\n"; 
		 $html .= "                       	        </tr>\n";
		 
		 $html .= "				            	 </table>\n";      
		 $html .= "                             </td>\n";
     
		 $html .= "               </tr>\n";
 
	     $html .= "               <tr>\n";
		 $html .= "				   <table border=\"1\" width=\"98%\" cellspacing=\"2\">\n"; 
		 $html .= "                 <tr>\n";
  		 $html .= "					  <td width=\"15%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ZONA RESIDENCIA</td>\n";
		 $html .= "				      <td width=\"1%\" align=\"center\" colspan=\"2\">\n";
		 
		 $html .= "					    <select name=\"zona\" class=\"select\">\n";
		 $html .= "                      <option value=\"-1\">-SELECCIONAR-</option>\n";
         foreach($zone as $key => $value)
		 {
		  $html .= "                     <option value=\"".$value['zona_residencia']."\">".$value['descripcion']."</option>\n";	
		 }
         $html .= "                     </select>\n"; 		 
		 $html .= "					  </td>\n";	
		 
  		 $html .= "					  <td width=\"15%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >DIRECCION RESIDENCIA&nbsp;*</td>\n";
		 $html .= "				      <td width=\"15%\" align=\"right\">\n";
		 $html .= "					     <input type=\"text\" class=\"input-text\" name=\"direccion\" style=\"width:100%\" maxlength=\"60\" value=\"\">\n";
		 $html .= "					  </td>\n";		

  		 $html .= "					  <td width=\"15%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TELEFONO RESIDENCIA&nbsp;*</td>\n";
		 $html .= "				      <td width=\"10%\" align=\"right\">\n";
		 $html .= "					     <input type=\"text\" class=\"input-text\" name=\"telefono\" onkeypress=\"return acceptNum(event)\" style=\"width:100%\" maxlength=\"30\" value=\"\">\n";
		 $html .= "					  </td>\n";

  		 $html .= "					  <td width=\"8%\" align=\"center\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" >TELEFONO MOVIL</td>\n";
		 $html .= "				      <td width=\"10%\" align=\"right\">\n";
		 $html .= "					     <input type=\"text\" class=\"input-text\" name=\"movil\" onkeypress=\"return acceptNum(event)\" style=\"width:100%\" maxlength=\"30\" value=\"\">\n";
		 $html .= "					  </td>\n";
		 
		 $html .= "                 </tr>\n";

         $html .= "                 <tr>\n";
  		 $html .= "					  <td width=\"10%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTRATO</td>\n";
		 $html .= "				      <td width=\"1%\" align=\"center\" colspan=\"2\">\n";
		 $html .= "					    <select name=\"estrato\" class=\"select\">\n";
		 $html .= "                      <option value=\"-1\">-SELECCIONAR-</option>\n";
         foreach($estr as $key => $value)
		 {
		  $html .= "                     <option value=\"".$value['tipo_estrato_id']."\">".$value['descripcion']."</option>\n";	
		 }
         $html .= "                     </select>\n"; 		 
		 $html .= "					  </td>\n";

  		 $html .= "					  <td width=\"10%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTADO CIVIL</td>\n";
		 $html .= "				      <td width=\"\" align=\"left\" colspan=\"2\">\n";
		 $html .= "					    <select name=\"estado_civil\" class=\"select\">\n";
		 $html .= "                      <option value=\"-1\">-SELECCIONAR-</option>\n";
         foreach($ecivil as $key => $value)
		 {
		  $html .= "                     <option value=\"".$value['tipo_estado_civil_id']."\">".$value['descripcion']."</option>\n";	
		 }
         $html .= "                     </select>\n"; 		 
		 $html .= "					  </td>\n";		 

  		 $html .= "					  <td width=\"8%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >RANGO</td>\n";
		 $html .= "				      <td width=\"10%\" align=\"right\">\n";
		 $html .= "					     <input type=\"text\" class=\"input-text\" name=\"rango\" readonly=\"readonly\" style=\"width:100%; background-color:#C0C0C0\" maxlength=\"\" value=\"UNICO\">\n";
		 $html .= "					  </td>\n";

  		 $html .= "					  <td width=\"10%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO AFILIADO</td>\n";
		 $html .= "				      <td width=\"2%\" align=\"left\" colspan=\"2\">\n";
		 $html .= "					    <select name=\"tipo_afiliado\" class=\"select\">\n";
		 $html .= "                      <option value=\"-1\">-SELECCIONAR-</option>\n";
         foreach($tipoaf as $key => $value)
		 {
		  $html .= "                     <option value=\"".$value['tipo_afiliado_id']."\">".$value['tipo_afiliado_nombre']."</option>\n";	
		 }
         $html .= "                     </select>\n"; 		 
		 $html .= "					  </td>\n";	
		 
		 $html .= "                 </tr>\n";
		 
		 $html .= "				   </table>\n";		 
		 $html .= "               </tr>\n";
 
		 //$html .= "				</table>\n";
		 $html .= "           </td>\n";
		 	 
		 $html .= "          </tr>\n";

		 
		 $html .= "         </table>\n";
		 $html .= "       </fieldset>\n";
		 $html .= "      </td>\n";
		 $html .= "  </tr>\n"; 
		 $html .= "</table>\n";
		 
		 $html .= "<table border=\"-1\" width=\"50%\" align=\"center\" >\n";
		 $html .= " <tr>\n";
         $html .= "	  <td align=\"center\"><br>\n";
         $html .= "		<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
		 $html .= "	  </td>\n";
		 
		 $html .= "</form>\n";
		 $html .= "<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
         $html .= "	  <td align=\"center\"><br>\n";	
         $html .= "	  <input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"Cancelar\">\n";		 
		 $html .= "	      </td>";	
     $html .= "</form>\n";
		 $html .= "</table>\n";
		 $html .= ThemeCerrarTabla();
		 return $html;
		}
		
		
		
		
		
	}
?>