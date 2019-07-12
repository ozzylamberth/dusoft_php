<?php

   /*********************************************************
  * @package DUANA & CIA
  * @version 1.0 $Id: ESM_AdminPqrs_MenuHTML.class
  * @copyright DUANA & CIA JUN-2012
  * @author R.O.M.A
  **********************************************************/

  /***********************************************************
  * Clase Vista: ESM_AdminPqrs_MenuHTML
  * Clase Contiene menus de modulo 
  ************************************************************/

	class ESM_AdminPqrs_MenuHTML
	{
		/********************************************************
		* Constructor de la clase
		********************************************************/
		function ESM_AdminPqrs_MenuHTML(){}


		
		/********************************************************
		* SubMenu de opciones de PQRS
		********************************************************/
		function MenuOpciones($action,$empresa)
		{
			$html  = ThemeAbrirTabla('MENU DE OPCIONES PQRS','50%');
			
			$html .= "<table width=\"50%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";

			$html .= "</table>\n";
			$html .= "<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\" colspan=\"3\">M E N U\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$link1 = ModuloGetURL("app","Pqrs","controller","Crear_caso")."&datos[empresa_id]=".$empresa;
			$link2 = ModuloGetURL("app","Pqrs","controller","Actualizacion_Pqrs")."&datos[empresa_id]=".$empresa;
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td   class=\"label\" align=\"center\">\n";
			$html .= "        <a href=\"".$link1."\">CREAR CASOS</a>\n";
			$html .= "      </td>\n";												
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td   class=\"label\" align=\"center\">\n";
			$html .= "        <a href=\"".$link2."\">ESTADO/ACTUALIZACION CASOS </a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			//$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			// $html .= "      <td   class=\"label\" align=\"center\">\n";
			// $html .= "        <a href=\"".$link2."\">CONSULTA ESTADO PQRS</a>\n";
			// $html .= "      </td>\n";
			//$html .= "  </tr>\n ";
			$html .= "</table>\n";
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

		/***********************************************************************
		* Funcion : forma para el registro de un caso pqrs
		*@ return string html
		************************************************************************/		
		function FormaCrearCaso($action,$farmacias,$empresa,$razonS,$categoria,$estadoCaso,$fuerzas,$consec)
		{
		    
		    $html  = "<script>\n"; 
			$html .= "	function ValidarCampos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.farmacia.value == \"0\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR LA FARMACIA\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.resp_caso.value == \"0\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL RESPONSABLE DEL CASO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.prioridad.value == \"0\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR LA PRIORIDAD\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";  
			$html .= "		if(forma.estado_caso.value == \"0\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE AL MENOS MARCAR ESTADO CASO: ABIERTO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";   
			$html .= "		if(forma.fuerza.value == \"0\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR LA FUERZA\";\n";
			$html .= "			return;\n";
			$html .= "		}\n"; 
			$html .= "		if(forma.cedula.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE INGRESAR NUMERO IDENTIFICACION\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";  
			$html .= "		if(forma.nombres.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE INGRESAR NOMBRE PACIENTE\";\n";
			$html .= "			return;\n";
			$html .= "		}\n"; 
			$html .= "		if(forma.apellidos.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE INGRESAR APELLIDOS PACIENTE\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.fecha_naci.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE INGRESAR LA FECHA DE NACIMIENTO DEL PACIENTE\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(!(isNaN(forma.fecha_naci.value)))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE INGRESAR UNA FECHA DE NACIMIENTO VALIDA\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.telefono.value  == \"\" )\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE INGRESAR UN NUMERO TELEFONICO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.categoria.value  == \"0\" )\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE SELECCIONAR LA CATEGORIA DE LA QUEJA O SOLICITUD\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";	
			$html .= "    document.registrar_caso.action =\"".$action['crea_caso']."\"; \n";  //llamado a metodo de insercion (controller)
			$html .= "    document.registrar_caso.submit();\n";
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
			
		 $html .= ThemeAbrirTabla('REGISTRO DE CASO - PQRS');
		 $html .= "<form name=\"registrar_caso\" id=\"registrar_caso\" action=\"javascript:ValidarCampos(document.registrar_caso)\" method=\"post\">\n";
		 $html .= "<input type=\"hidden\" name=\"empresa\" id=\"empresa\" value=\"".$empresa."\">\n";
		 $html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";         
         $html .= "<table border=\"0\" width=\"90%\" align=\"center\" >\n";
		 $html .= "	<tr>\n";
		 $html .= "		<td>\n";
		 //$html .= "			<fieldset class=\"fieldset\">\n";//
		 //$html .= "				<legend class=\"normal_10AN\"></legend>\n";
		 $html .= "				<table border=\"0\" width=\"100%\" cellspacing=\"2\">\n";
		 $html .= "					<tr>\n";
		 $html .= "					 <td align=\"center\">\n";
         $html .= "						 <table border=\"1\" width=\"98%\" class=\"label\">\n"; 
 
		 $html .= "								<tr>\n";
		 $html .= "									<td width=\"50%\" colspan=\"4\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" >CREAR CASO </td>\n";
		 $html .= "								</tr>\n";
 		 $html .= "								<tr>\n";
		 $html .= "									<td width=\"30%\" colspan=\"3\" style=\"background-color:#C0C0C0;\" class=\"\" >No. CASO: ".$consec['consecutivo']."</td>\n";
		 $html .= "									<td width=\"70%\" align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0;\" class=\"\" >EMPRESA:&nbsp;".$razonS['razon_social']."</td>\n";
		 $html .= "								</tr>\n";
      
		 $html .= "								<tr>\n";
		 $html .= "									<td width=\"50%\" colspan=\"4\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" >DATOS DEL PUNTO DE ATENCION</td>\n";
		 $html .= "								</tr>\n";
		 
		 $html .= "								<tr>\n";
		 $html .= "									<td width=\"50%\" colspan=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">FARMACIA</td>\n";
		 $html .= "									<td width=\"100%\" colspan=\"\">";
		 $html .= "									<select name=\"farmacia\" id=\"farmacia\" class=\"select\" onchange=\"xajax_GetUserFarm(this.value,'".$empresa."')\">  " ;
		 //$html .= "									<select name=\"farmacia\" id=\"farmacia\" class=\"select\" onchange=\"\">\n" ;
		 $html .= "									 <option value=\"0\">--SELECCIONAR--</option>" ;
		 foreach($farmacias as $key=>$value)
		 {
		 $html .= "									 <option value=\"".$value['bodega']."\">".$value['descripcion']."</option>" ;		                                                 
		 }
		 $html .= "									</select>" ;
		 $html .= "                                 </td>\n";
		 $html .= "									<td width=\"50%\" colspan=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">\n";
         $html .= "                   				RESPONSABLE DE RESOLUCION DEL CASO";
		 $html .= "                					</td>\n";
		 $html .= "                					<td>\n";
		 $html .= "                					<div id=\"resp_farm\">\n";
		 //$html .= "                		            <input type=\"text\" class=\"input-text\" name=\"resp_caso\" id=\"resp_caso\" style=\"width:100%\" maxlength=\"40\" value=\"\">\n";
		 $html .= "									<select name=\"resp_caso\" id=\"resp_caso\" class=\"select\">\n" ;
		 $html .= "									 <option value=\"0\">--SELECCIONAR--</option>" ;
		 $html .= "									</select>" ;		 
		 $html .= "									</div>" ;		 
		 $html .= "									</td>" ;		 
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
		 $html .= "									  <select name=\"prioridad\" id=\"prioridad\" class=\"select\">  " ;
		 $html .= "									    <option value=\"0\">--SELECCIONAR--</option>" ;
		 $html .= "									    <option value=\"1\">ALTA</option>" ;
		 $html .= "									    <option value=\"2\">MEDIA</option>" ;
		 $html .= "									    <option value=\"3\">BAJA</option>" ;
		 $html .= "									   </select>" ;		 
		 $html .= "                					</td>\n";
		 $html .= "									<td colspan=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">\n";
		 $html .= "									 ESTADO DEL CASO";
		 $html .= "                					</td>\n";
		 $html .= "									<td colspan=\"\">\n";
		 $html .= "									  <select name=\"estado_caso\" id=\"estado_caso\" class=\"select\">  " ;
		 $html .= "									    <option value=\"0\">--SELECCIONAR--</option>" ;
         foreach($estadoCaso as $key=>$value)
		 {
		 $html .= "									    <option value=\"".$value['estado_caso_id']."\">".$value['estado']."</option>";		 
		 }
		 $html .= "									   </select>" ;		 
		 $html .= "                					</td>\n";		 
		 $html .= "								 </tr>\n";	 
 		 
 		 $html .= "								<tr>\n";
 		 $html .= "								  <td colspan=\"4\" style=\"width:100%; background-color:#C0C0C0\"><br>\n";
 		 $html .= "								  </td>\n"; 		 
		 $html .= "								</tr>\n";		 
	 
		 $html .= "								<tr>\n";
		 $html .= "									<td width=\"50%\" colspan=\"4\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" >DATOS DEL CLIENTE</td>\n";
		 $html .= "								</tr>\n";
      
		 $html .= "               				<tr>\n";
		 $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >NUMERO DOCUMENTO IDENTIFICACION*</td>\n";
		 $html .= "									<td width=\"10%\" align=\"right\">\n";
		 $html .= "										<input type=\"text\" class=\"input-text\" name=\"cedula\" id=\"cedula\" style=\"width:100%;\" maxlength=\"12\" value=\"\">\n";
		 $html .= "									</td>\n";
		 $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FUERZA</td>\n";	
		 $html .= "									<td colspan=\"\">\n";
		 $html .= "									  <select name=\"fuerza\" id=\"fuerza\" class=\"select\">  " ;
		 $html .= "									    <option value=\"0\">--SELECCIONAR--</option>" ;
         foreach($fuerzas as $clave=>$valor)
		 {
		 $html .= "									    <option value=\"".$valor['codigo_fuerza']."\">".$valor['descripcion']."</option>";		 
		 }
		 $html .= "									   </select>" ;		 
		 $html .= "                					</td>\n";		 
		 $html .= "               				</tr>\n";

		 $html .= "               			 <tr>\n"; 
		 // $html .= "                 	 	 	  <td width=\"30%\">\n";     
		 // $html .= "                  				<table border=\"1\" width=\"100%\" cellspacing=\"2\">\n";

		 // $html .= "                                  </table>\n";
		 // $html .= "                             </td>\n";
     
		 $html .= "                 			  <td width=\"\">\n";
		 $html .= "				           			 <table border=\"1\" width=\"100%\" cellspacing=\"2\">\n"; 
		 $html .= "                    				  <tr>\n"; 
		 $html .= "									     <td width=\"50%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >NOMBRES&nbsp;</td>\n";
		 $html .= "									     <td width=\"50%\" align=\"right\">\n";
		 $html .= "										    <input type=\"text\" class=\"input-text\" name=\"nombres\" id=\"nombres\" style=\"width:100%\" maxlength=\"40\" value=\"\">\n";
		 $html .= "									     </td>\n";     
		 $html .= "                     			  </tr>\n";  
		 $html .= "                     			  <tr>\n"; 
		 $html .= "									       <td width=\"50%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >APELLIDOS</td>\n";
		 $html .= "									       <td width=\"50%\" align=\"right\">\n";
		 $html .= "										    <input type=\"text\" class=\"input-text\" name=\"apellidos\" id=\"apellidos\" style=\"width:100%\" maxlength=\"40\" value=\"\">\n";
		 $html .= "									      </td>\n";     
		 $html .= "                     			   </tr>\n";     
		 $html .= "                   				    <tr>\n"; 
		 $html .= "                      				 <td width=\"20%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >SEXO</td>\n";     
		 $html .= "									      <td colspan=\"2\">\n";
		 $html .= "										     <select name=\"sexo\" id=\"sexo\" class=\"select\" onchange=\"\">\n";
		 $html .= "											    <option value=\"0\">-SELECCIONAR-</option>\n";
		 $html .= "											    <option value=\"1\">MASCULINO</option>\n";
		 $html .= "											    <option value=\"2\">FEMENINO</option>\n";
		 $html .= "                      				    </select>\n";
		 $html .= "                      				   </td>\n";     
		 $html .= "                     				</tr>\n";      
		 $html .= "				            		</table>\n";     
		 $html .= "                 			 </td>\n";

         $html .= "                 			 <td width=\"50%\">\n";
		 $html .= "				           		  <table border=\"1\" width=\"100%\" cellspacing=\"2\">\n"; 
     
		 $html .= "                     		    <tr>\n";  
  		 $html .= "									      <td width=\"40%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" ><a title=\"FECHA NACIMIENTO\">FEC. NAC.</a>&nbsp;</td>\n";
		 $html .= "									      <td width=\"25%\" align=\"right\">\n";
		 $html .= "										     <input type=\"text\" align=\"left\" class=\"input-text\" name=\"fecha_naci\" id=\"fecha_naci\" style=\"width:100%\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\">\n";
		 $html .= "									      </td>\n"; 
		 $html .= "									      <td align=\"left\" >".ReturnOpenCalendario('registrar_caso','fecha_naci','-')."</td>\n";		 
		 $html .= "                       	        </tr>\n";
		 $html .= "                     		    <tr>\n";  
		 $html .= "				                      <td width=\"35%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >DIRECCION RESIDENCIA&nbsp;</td>\n"; 
	     $html .= "									  <td colspan=\"2\">\n"; 
		 $html .= "					                   <input type=\"text\" class=\"input-text\" name=\"direccion\" id=\"direccion\" style=\"width:100%\" maxlength=\"60\" value=\"\">\n";		 
		 $html .= "									  </td>\n"; 
		 $html .= "                       	        </tr>\n";
		 $html .= "                       	        <tr>\n";
		 $html .= "                       	        </tr>\n";
		 
		 $html .= "				            	 </table>\n";      
		 $html .= "                             </td>\n";
		 
		 $html .= "                             <td width=\"100%\" colspan=\"2\">\n";
		 $html .= "				           			<table border=\"1\" width=\"98%\" cellspacing=\"2\">\n"; 
		 $html .= "                       	          <tr>\n";
  		 $html .= "					                   <td width=\"8%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TEL. RESIDENCIA</td>\n";
		 $html .= "				                       <td width=\"8%\" align=\"right\">\n";
		 $html .= "					                     <input type=\"text\" class=\"input-text\" name=\"telefono\" id=\"telefono\" onkeypress=\"return acceptNum(event)\" style=\"width:100%\" maxlength=\"30\" value=\"\">\n";
		 $html .= "					                   </td>\n";		 
		 $html .= "					                   </tr>\n";		 
		 $html .= "					                   <tr>\n";		 
  		 $html .= "					                   <td width=\"8%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TEL. CELULAR</td>\n";
		 $html .= "				                       <td width=\"8%\" align=\"right\">\n";
		 $html .= "					                     <input type=\"text\" class=\"input-text\" name=\"celular\" id=\"celular\" onkeypress=\"return acceptNum(event)\" style=\"width:100%\" maxlength=\"30\" value=\"\">\n";
		 $html .= "					                   </td>\n";
		 $html .= "                       	          </tr>\n";	
		 $html .= "					                   <tr>\n";		 
  		 $html .= "					                   <td width=\"8%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >EMAIL</td>\n";
		 $html .= "				                       <td width=\"8%\" align=\"right\">\n";
		 $html .= "					                     <input type=\"text\" class=\"input-text\" name=\"email\" id=\"email\" style=\"width:100%\" maxlength=\"80\" value=\"\">\n";
		 $html .= "					                   </td>\n";
		 $html .= "                       	          </tr>\n";			 
		 $html .= "				           			</table>\n"; 
		 $html .= "                             </td>\n";
     
		 $html .= "               </tr>\n";
		 
 		 $html .= "				   <tr>\n";
 		 $html .= "						<td colspan=\"4\" style=\"width:100%; background-color:#C0C0C0\"><br>\n";
 		 $html .= "						</td>\n"; 		 
		 $html .= "				  </tr>\n";

		 $html .= "				  <tr>\n";
		 $html .= "						<td width=\"50%\" colspan=\"4\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">INFORMACION DEL CASO</td>\n";
		 $html .= "				  </tr>\n";		 
 
	     $html .= "               <tr>\n";
		 $html .= "				   <table border=\"1\" width=\"98%\" cellspacing=\"2\">\n"; 
		 $html .= "                 <tr>\n";
		 $html .= "                   <td width=\"10%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >CATEGORIA CASO</td>\n";
		 $html .= "				      <td width=\"10%\" align=\"left\" colspan=\"\">\n";
		 $html .= "					    <select name=\"categoria\" id=\"categoria\" class=\"select\">\n";
		 $html .= "                      <option value=\"0\">-SELECCIONAR-</option>\n";
         foreach($categoria as $k=>$v)
		 {
		  $html .= "                      <option value=\"".$v['categoria_id']."\">".$v['tipo_categoria']." => ".$v['descripcion']."</option>\n";
		 }
         $html .= "                     </select>\n"; 		 
		 $html .= "					  </td>\n";	
		 $html .= "                 </tr>\n";

         $html .= "                 <tr>\n";	 

  		 $html .= "					  <td width=\"10%\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >OBSERVACION Y/O SEGUIMIENTO CASO</td>\n";
		 $html .= "				      <td width=\"10%\" align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0\">\n"; 
         $html .= "                   <textarea name=\"observacion\" id=\"observacion\" cols=\"85\" rows=\"3\"></textarea>";		 
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
		 $html .= "<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
         $html .= "	  <td align=\"center\"><br>\n";	
         $html .= "	  <input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"Volver\">\n";		 
		 $html .= "	      </td>";	
         $html .= "</form>\n";
		 $html .= "</table>\n";
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
			$html  = ThemeAbrirTabla('GESTION PQRS','70%');
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
		
		/*****************************************************************************
		* Funcion vista: Forma listado casos pqrs
		* @param array $action Vector que continen los link de la aplicacion
		* @param array 
		* @return 
		******************************************************************************/		
		function Listado_pqrsAct($action,$request,$datosPqrsAct,$conteo,$pagina)
		{
		 $html  = ThemeAbrirTabla(' ACTUALIZACION/SEGUIMIENTO CASOS PQRS fff  ');	
         
		 $vigencia_caso = ModuloGetVar("app","Pqrs","Vigencia_Pqrs");
		 
		 /*obtener mktime de fecha actual*/
		 $dated = date("d");
		 $datem = date("m");
		 $datey = date("Y");
		 $timestamp1 = mktime(0,0,0,$datem,$dated,$datey);
		 /***************************************/
		 
		 //$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
		 $ctl = AutoCarga::factory("ClaseUtil"); 	
                 $html .= $ctl->RollOverFilas();		 
		 $html .= "<script>\n";
		 $html .= "	function acceptNum(evt)\n";
		 $html .= "	{\n";
		 $html .= "		var nav4 = window.Event ? true : false;\n";
		 $html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
		 
		 if(!$puntos)
                    $html .= "		return (key <= 13 ||(key >= 48 && key <= 57));\n";
                   else
                    $html .= "		return (key <= 13 || key == 46 || (key >= 48 && key <= 57));\n";
		 
		 $html .= "	}\n";
		 $html .= "</script>\n";		 
		 
		 $html .= "<br>";
		 $html .= "<form name=\"Buscador\" id=\"Buscador\" action=\"".$action['buscador']."\" method=\"post\">";
		 $html .= "<table  width=\"25%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
		 $html .= "  <tr align=\"center\">\n";
		 $html .= "      <td class=\"formulacion_table_list\">NUMERO DE CASO</td>\n";
		 $html .= "      <td class=\"formulacion_table_list\"><input type=\"text\" name=\"buscador[caso]\" maxlength=\"8\" id=\"buscador[caso]\" onkeypress=\"return acceptNum(event)\" class=\"input-text\" value=\"".$request['caso']."\"></td>\n";

                 $html .= "      <td class=\"\">";
                 $html .= "        <table  width=\"10%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">";
                 $html .= "        <tr align=\"center\">";
		 $html .= "            <td>Fecha Inicial</td>";
                 $html .= "            <td class=\"\">";
                 $html .= "             <input type=\"text\" name=\"buscador[fecha_ini]\" id=\"fecha_ini\" class=\"input-text\">    ";
		 $html .= "            </td>";
                 $html .= "		       <td align=\"left\" class=\"label\">".ReturnOpenCalendario('Buscador','fecha_ini','/',1)."</td>\n";		 
		 $html .= "        </tr>";
                 $html .= "        <tr align=\"center\">";
		 $html .= "            <td>Fecha Final</td>";
                 $html .= "            <td class=\"\">";
                 $html .= "             <input type=\"text\" name=\"buscador[fecha_fin]\" id=\"fecha_fin\" class=\"input-text\">    ";
		 $html .= "            </td>";
 	         $html .= "		       <td align=\"left\" class=\"label\">".ReturnOpenCalendario('Buscador','fecha_fin','/',1)."</td>\n";				 
		 $html .= "        </tr>";		 
		 $html .= "        </table>";
		 $html .= "      </td>";		 
		 
		 $html .= "	</tr>";
		 $html .= "	<tr>";
		 $html .= "	<td  class=\"formulacion_table_list\"  colspan=\"4\" align=\"center\"><input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\"></td>\n";
		 $html .= "	</tr>";
		 $html .= "</table>";
		 $html .= "</form>";		 

		  if(!empty($datosPqrsAct))
           {		  
			$pgn = AutoCarga::factory("ClaseHTML");
			$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
			$html .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "                    <tr class=\"modulo_table_list_title\">\n";
			$html .= "                      <td align=\"center\" width=\"2%\">\n";
			$html .= "                        <a title=''># CASO</a>";
			$html .= "                      </td>\n";			
			$html .= "                      <td align=\"center\" width=\"2%\">\n";
			$html .= "                        <a title=''>EMPRESA</a>";
			$html .= "                      </td>\n";
			$html .= "                      <td align=\"center\" >\n";
			$html .= "                        <a title='FARMACIA RELACIONADA AL CASO'>ESM/FARM.</a>";
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
			$html .= "                        <a title='NUMERO DE IDENTIFICACION'>#ID PACIENTE</a>";
			$html .= "                      </td>\n";
			$html .= "                      <td align=\"center\">\n";
			$html .= "                        <a title='CLASIFICACION MOTIVO QUEJA O SOLICITUD'>CATEGORIA CASO</a>";
			$html .= "                      </td>\n";
			$html .= "                      <td align=\"center\">\n";
			$html .= "                        <a title='SEGUIMIENTO CASO'>OBSERV. SEGUIMIENTO</a>";
			$html .= "                      </td>\n";
			$html .= "                      <td align=\"center\">\n";
			$html .= "                        <a title=''>FECHA CASO</a>";
			$html .= "                      </td>\n";
			$html .= "                      <td align=\"center\">\n";
			$html .= "                        <a title=''>FECHA SEGUIM.</a>";
			$html .= "                      </td>\n";			
			$html .= "                      <td align=\"center\" >\n";
			$html .= "                        <a title='ACCIONES: ACTUALIZAR CASO'>ACCIONES</a>";
			$html .= "                      </td>\n";
			$html .= "                      <td align=\"center\" >\n";
			$html .= "                        <a title='FACTOR DE COMPARACION ".$vigencia_caso."- dias'>VIGENCIA</a>";
			$html .= "                      </td>\n";			
			$html .= "                    </tr>\n";
			
			foreach($datosPqrsAct as $key => $valor)
			{
			    /*armar mktime de la fecha del caso y obtener dif.*/
				$fechadato = explode(" ",$valor['fecha_caso']);
				$dateSplited = explode("-",$fechadato[0]);
				$casoAnio = $dateSplited[0];
				$casoMes = $dateSplited[1];
				$casoDia = $dateSplited[2];
				$timestamp2 = mktime(0,0,0,$casoMes,$casoDia,$casoAnio);
				
				$segundos_diferencia = $timestamp1 - $timestamp2;
				//convertir segundos en dias
                $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
				$dias_diferencia = abs($dias_diferencia);
				//quitar los decimales a los dias de diferencia
                $dias_diferencia = floor($dias_diferencia); 
				/*******************************************/
				
                $html .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";			
				$html .= "						<td>".$valor['caso']."</td>";
				$html .= "						<td>".$valor['empresa_id']."</td>";
				$html .= "						<td>".$valor['bodega']."</td>";
				$html .= "						<td>".$valor['responsable_caso']."</td>";
				$html .= "						<td>".$valor['prioridad']."</td>";
				$html .= "						<td>".$valor['estado_caso']."</td>";
				$html .= "						<td>".$valor['paciente_id']."</td>";
				$html .= "						<td>".$valor['categoria_caso']."</td>";
				$html .= "						<td>".$valor['observacion']."</td>";
				$html .= "						<td>".$valor['fecha_caso']."</td>";
				$html .= "						<td>".$valor['fecha_seguim']."</td>";
				$html .= "						<td>";	

                $link = ModuloGetURL("app","Pqrs","controller","ActualizarCasos")."&datos[empresa_id]=".$valor['empresa_id']."&caso=".$valor['caso']."&bodega=".$valor['bodega']."&responsable=".$valor['responsable_caso']."&categoria=".$valor['categoria_caso'];
				
				//if($valor['estado_caso'] == 'CERRADO' || $dias_diferencia >=3)
				if($valor['estado_caso'] == 'CERRADO')
				{
				 $html .= "					   <center><a class=\"label_error\" href=\"#\"  title=\"CASO RESUELTO / � VENCIDO\"><img src=\"".GetThemePath()."/images/si.png\" border='0'></a></center>\n";
				}
				else 
				 {
				  $html .= "				  <center><a class=\"label_error\" href=\"".$link."\"  title=\"ACTUALIZAR CASO\"><img src=\"".GetThemePath()."/images/resumen.gif\" border='0'></a></center>\n";				 
				 }
				$html .= "						</td>";
				
				$html .= "						<td align=\"center\">";	
				if($dias_diferencia >=3)
                  {
				     $html .= "                 <a title=\"CASO VENCIDO � CERRADO\"><img src=\"".GetThemePath()."/images/Redlab.png\" border='0'></a>";			
                  }
                  else  
                    {
                     if($dias_diferencia == 2)
                      {
					   $html .= "               <a title=\"CASO PROXIMO A VENCER\"><img src=\"".GetThemePath()."/images/Oranlab.png\" border='0'></a>	";
					  }
                      else
                           {$html .= "          <a title=\"CASO ACTIVO O TRAMITADO\"><img src=\"".GetThemePath()."/images/Greenlab.png\" border='0'></a>"; }	
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
		 $html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
		 $html .= "        VOLVER\n";
		 $html .= "      </a>\n";
		 $html .= "    </td>\n";
		 $html .= "  </tr>\n";
		 $html .= "</table>\n";		 
		 
		 $html .= ThemeCerrarTabla();
			
		 return $html;		 
		 
		}
		
		
		/***********************************************************************
		* Funcion : forma para la actualizacion de casos Pqrs
		*@ return string html
		************************************************************************/		
		function FormaActCaso($action,$datos_caso,$empresa,$caso,$bodega,$resp,$categoria)
		{	
         
		 $sql = Autocarga::factory("Permisos", "", "app","Pqrs");
		 $razon = $sql->ListarEmpresa($empresa);
		 
		 $html  = ThemeAbrirTabla('ACTUALIZACION DE CASO - PQRS','80%');

                 $html .= "				<center><table border=\"1\" width=\"100%\" class=\"label\">\n"; 
 
		 $html .= "								<tr>\n";
		 $html .= "									<td width=\"50%\" colspan=\"4\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" >ACTUALIZAR CASO </td>\n";
		 $html .= "								</tr>\n";
 		 $html .= "								<tr>\n";
		 $html .= "									<td width=\"30%\" colspan=\"3\" style=\"background-color:#C0C0C0;\" class=\"\" >No. CASO: ".$caso."</td>\n";
		 $html .= "									<td width=\"70%\" align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0;\" class=\"\" >CODIGO EMPRESA:&nbsp;  ".$empresa."</td>\n";
		 $html .= "								</tr>\n";
      
		 $html .= "								<tr>\n";
		 $html .= "									<td width=\"50%\" colspan=\"4\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" >DATOS DEL PUNTO DE ATENCION</td>\n";
		 $html .= "								</tr>\n";
		 
		 $html .= "								<tr>\n";
		 $html .= "									<td width=\"\" colspan=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">FARMACIA</td>\n";
		 $html .= "									<td width=\"\" colspan=\"\">";
         $html .= "                                  <input type=\"text\" name=\"farmacia\" id=\"farmacia\" size=\"40\" readonly=\"readonly\"  value=\"".$bodega."\">  ";
		 $html .= "                                 </td>\n";
		 $html .= "									<td width=\"100%\" colspan=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">\n";
         $html .= "                   				 RESPONSABLE DE RESOLUCION DEL CASO";
		 $html .= "                					</td>\n";
		 $html .= "                					<td width=\"\">\n";
         $html .= "                                  <input type=\"text\" name=\"responsable\" id=\"responsable\" maxlength=\"30\" size=\"35\" readonly=\"readonly\" value=\"".$resp."\">  ";	 
		 $html .= "									</td>" ;		 
		 $html .= "								 </tr>\n";

		 $html .= "								 <tr>\n";
		 $html .= "									<td width=\"\" colspan=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CATEGORIA</td>\n";
		 $html .= "									<td colspan=\"\">\n";
         $html .= "                                  <input type=\"text\" name=\"categoria\" id=\"categoria\" size=\"40\" readonly=\"readonly\" value=\"".$categoria."\">  ";		 
		 $html .= "                					</td>\n";
		 $html .= "									<td colspan=\"2\" style=\"text-align:center;text-indent:8pt\" class=\"\">\n";
		 $html .= "									 ".$razon['razon_social']."  ";
		 $html .= "                					</td>\n";
		 // $html .= "									<td colspan=\"\">\n";
         // $html .= "                                  <input type=\"text\" name=\"prioridad\" id=\"prioridad\" readonly=\"readonly\"  value=\"\">  ";		 
		 // $html .= "                					</td>\n";		 
		 $html .= "								 </tr>\n";	 
		 $html .= "						</table></center>\n";	 
		 $html .= "						<br>\n";	 
         
		 $link = ModuloGetURL("app","Pqrs","controller","UpdateCaso");
		 
		 $html .= "<br>";
		 $html .= "<form name=\"actualizar_caso\" id=\"actualizar_caso\" action=\"".$link."\" method=\"post\">";
		 $html .= " <input type=\"hidden\" name=\"caso\" id=\"caso\" value=\"".$caso."\">";
		 $html .= " <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$empresa."\">";
		 $html .= "<table  width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
		 $html .= "  <tr align=\"center\">\n";
		 $html .= "      <td class=\"formulacion_table_list\" colspan=\"4\">SEGUIMIENTO / OBSERVACION</td>\n";
		 $html .= "	 </tr>";

		 $i=0;
		 foreach($datos_caso as $k=>$v)
		 {
		    $html .= "  <tr align=\"center\">\n";
			$html .= "	<td width=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >DETALLE</td>\n";
			$html .= "	<td width=\"\" align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0\">\n"; 
			$html .= "      <textarea name=\"observacion".$i."\" readonly=\"readonly\" id=\"observacion".$i."\" cols=\"58\" rows=\"3\">".$v['observacion']."</textarea>";		 
			$html .= "	</td>\n";
			$html .= "	<td width=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA OBSERV.</td>\n";
			$html .= "	<td width=\"\" align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0\">\n"; 
			$html .= "      <input type=\"text\" name=\"fecha_observ".$i."\" id=\"fecha_observ".$i."\" maxlength=\"\" size=\"\" readonly=\"readonly\" value=\"".$v['fecha_registro']."\">  ";	 
			$html .= "	</td>\n";
		    $html .= "	 </tr>";
		  $i++;	
		 }

		 $html .= "	 <tr>\n";
 		 $html .= "		<td colspan=\"4\" style=\"width:100%; background-color:#C0C0C0\"><br>\n";
 		 $html .= "		</td>\n"; 		 
		 $html .= "	 </tr>\n";
		 
		 $html .= " <tr align=\"center\">\n";
		 $html .= "	<td width=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >NUEVA RESPUESTA</td>\n";
		 $html .= "	<td width=\"\" align=\"left\" colspan=\"\" style=\"background-color:#C0C0C0\">\n"; 
		 $html .= "      <textarea name=\"observacionAct\" id=\"observacionAct\" cols=\"58\" rows=\"3\"></textarea>";		 
		 $html .= "	</td>\n";
		 $html .= "	<td width=\"\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">CERRAR CASO: ?</td>\n";
		 $html .= "	<td width=\"\" align=\"center\" colspan=\"\" style=\"background-color:#C0C0C0\">\n"; 
		 $html .= "   <input type=\"checkbox\" name=\"cerrar_caso\" id=\"cerrar_caso\" value=\"1\"> CERRAR ";	 
		 $html .= "	</td>\n";
		 $html .= "	 </tr>";
		 
		 $html .= "	 <tr>";
		 $html .= "	    <td class=\"formulacion_table_list\" colspan=\"4\" align=\"center\"><input type=\"submit\" value=\"ACTUALIZAR\" class=\"input-submit\"></td>\n";
		 $html .= "	 </tr>";
		 $html .= "</table>";
		 $html .= "</form>";	
		 
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
		
		
		
		
		
		
	
	}
?>