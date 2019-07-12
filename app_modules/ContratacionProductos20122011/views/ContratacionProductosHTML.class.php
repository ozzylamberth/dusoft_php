<?php
   /**
  * @package IPSOFT-SIIS
  * @version $Id: ContratacionProductosHTML.class.php,v 1.14 2010/01/26 22:40:56 sandra Exp $Revision: 1.14 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja Torres
  */

  IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");
  IncludeClass("CalendarioHtml");
  
  class ContratacionProductosHTML
  {
     /**
     * Constructor de la clase
     */
     
    function  ContratacionProductosHTML(){}
      /**
      * Funcion donde se crea la Forma  del Menu para Todas Las Opciones Principales del Modulo
      * @param array $action vector que contiene los link de la aplicacion
      * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function FormaMenu($action)
     {
       $html  = ThemeAbrirTabla('CONTRATACION PRODUCTOS - PROVEEDOR');
       $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
       $html .= "  <tr class=\"modulo_table_list_title\">\n";
       $html .= "     <td align=\"center\">MENU\n";
       $html .= "     </td>\n";
       $html .= "  </tr>\n";
       $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
       $html .= "      <td   class=\"label\" align=\"center\">\n";
       $html .= "        <a href=\"".$action['contratacion']."\">CONTRATACIÒN</a>\n";
       $html .= "      </td>\n";
       $html .= "  </tr>\n";
	     $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
       $html .= "      <td   class=\"label\" align=\"center\">\n";
       $html .= "        <a href=\"".$action['asociar']."\">PARAMETRIZACION FARMACIA-PLAN</a>\n";
       $html .= "      </td>\n";
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
       $html .= ThemeCerrarTabla();
      return $html;
    }
     /**
      * Funcion forma que permite realizar  la busqueda de los proveedores
        * @param array $action vector que contiene los link de la aplicacion
	* @return string $html retorna la cadena con el codigo html de la pagina
    */ 
		function Formabusquedaproveedores($action,$tipodocumento,$request,$datos,$conteo,$pagina,$empresita)                             
		{

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
			$html .="  </script>\n";
			$html .= ThemeAbrirTabla('SELECCION - PROVEEDOR');
			$html .= "		<form name=\"formabuscar\" action=\"".$action['buscador']."\" method=\"post\">";
			$html .= "<table   width=\"40%\" align=\"center\" border=\"0\"  >";
            $html .= "	</tr>\n";
			$html .= "   <tr> \n";
			$html .= "		<td class=\"modulo_table_list_title\" width=\"40%\">TIPO DOCUMENTO: </td>\n";
			$html .= "		<td  class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "			<select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
			$html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($tipodocumento as $indice => $valor)
			{
	            if($valor['tipo_id_tercero']==$request['tipo_id_tercero'])
				$sel = "selected";
				else   $sel = "";
				$html .= " 			<option value=\"".$valor['tipo_id_tercero']."\" ".$sel.">".$valor['descripcion']."</option>\n";
			}
			$html .= "            </select>\n";
			$html .= "	   </td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td  width=\"40%\" class=\"modulo_table_list_title\">DOCUMENTO:</td>\n";
			$html .= "	    <td class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "  	   <input type=\"text\" class=\"input-text\" name=\"buscador[tercero_id]\" maxlength=\"32\" value=".$request['tercero_id']."></td>\n";
			$html .= "	</tr>\n";
			$html .= "		<tr>\n";
			$html .= "			<td class=\"modulo_table_list_title\">NOMBRE:</td>\n";
			$html .= "			<td  colspan=\"4\" class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\" name=\"buscador[nombre_tercero]\" maxlength=\"32\" value=".$request['nombre_tercero']."></td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "	   	<td align='center'>\n";
			$html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "		</td>\n";
			$html .= "		<td align='center' colspan=\"1\">\n";
			$html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formabuscar)\" value=\"Limpiar Campos\">\n";
			$html .= "	  	</td>\n";
			$html .= "</form>\n";
         	$html .= "<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "		  <td align='center' >\n";
			$html .= "		      <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\">\n";
			$html .= "		  </td>\n";
			$html .= " </form>\n";
			$html .= "	 </tr>\n";
			$html .= "</table><br>\n";
       
			if(!empty($datos))
			{
				$pghtml = AutoCarga::factory('ClaseHTML');
				$html .= "<table width=\"95%\" class=\"modulo_table_list_title\" align=\"center\">";
				$html .= "  <tr align=\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td width=\"15%\">IDENTIFICACION</td>\n";
				$html .= "      <td width=\"25%\">PROVEEDOR</td>\n";
				$html .= "      <td width=\"10%\">TELEFONOS</td>\n";
				$html .= "      <td width=\"18%\">DIRECCIÒN</td>\n";
				$html .= "      <td  width=\"25%\" >REPRESENTANTE</td>\n";
				$html .= "      <td width=\"3%\">MENU.</td>\n";
				$html .= "  </tr>\n";
				$est = "modulo_list_claro"; $back = "#DDDDDD";
				foreach($datos as $key => $dtl)
				{
		            $html .= "  <tr class=\"modulo_list_claro\">\n";
		            $html .= "      <td align=\"center\">".$dtl['tipo_id_tercero']." ".$dtl['tercero_id']."</td>\n";
		            $html .= "      <td align=\"left\">".$dtl['nombre_tercero']."</td>\n";
		            $html .= "      <td align=\"left\">".$dtl['telefono']."</td>\n";
		            $html .= "      <td align=\"left\">".$dtl['direccion']."</td>\n";
		            $html .= "      <td align=\"left\">".$dtl['representante_ventas']."</td>\n";
		            $html .= "      <td align=\"center\">\n";
					$html .= "      <a href=\"".$action['nuevo'].URLRequest(array("tipoid"=>$dtl['tipo_id_tercero'],"noid"=>$dtl['tercero_id'],"codprov"=>$dtl['codigo_proveedor_id']))."\">\n";
					$html .= "      <img src=\"".GetThemePath()."/images/ingresar.png\" border=\"0\" title=\"parametrizacion\">";
					$html .= "      </a>\n";
					$html .= "      </td>\n";
                    $html .= "  </tr>\n";
				}
             
	        $html .= "	</table><br>\n";
	        $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
	        $html .= "	<br>\n";
	        }
	        else
	        {
	           if($request)
	           $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
	          }
          $html .= $this->CrearVentana();
          $html .= ThemeCerrarTabla();
          return $html;
        }
	   /**
          * Funcion forma que acceder al menu de contratacion
           * @param array $action vector que contiene los link de la aplicacion
	* @return string $html retorna la cadena con el codigo html de la pagina
    */ 
		
		function FormaMenu2($action,$tipoid,$noid,$empresita)
		{
			$html  = ThemeAbrirTabla('CONTRATACION PRODUCTOS - PROVEEDOR');
			$html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\">MENU\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td   class=\"label\" align=\"center\">\n";
			$html .= "        <a href=\"".$action['nuevo']."\">NUEVO CONTRATO</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      </td>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "         <a href=\"#\" onclick=\"xajax_SelecNroContrato('".$noid."', '".$tipoid."','".$empresita."')\" class=\"label_error\">MODIFICAR CONTRATO</a>\n";
      $html .= "     </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "         <a href=\"#\" onclick=\"xajax_SeleccionarContratoCarta('".$noid."', '".$tipoid."','".$empresita."')\"  class=\"label_error\" >SUBIR CARTAS PROVEEDORES</a>\n";
      $html .= "      </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "         <a href=\"#\" onclick=\"xajax_SeContratoConsulta('".$noid."', '".$tipoid."','".$empresita."')\" class=\"label_error\">CONSULTAR CONTRATO</a>\n";
      $html .= "      </td>\n";
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
      $html .= $this->CrearVentana();
      $html .= ThemeCerrarTabla();
      return $html;
		}
    /**
      * Funcion donde se crea una Forma con una Ventana con capas para mostrar informacion
           * en pantalla
           * @param array $action vector que contiene los link de la aplicacion      
	 * @param int $tmn Tamaño que tendra la ventana
     * @return string
    */
    function CrearVentana($tmn = 690)
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
      $html .= "</script>\n";
      $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
      $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">CONFIRMACIÓN</div>\n";
      $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
      $html .= "  <div id='Contenido' class='d2Content'>\n";
      $html .= "  </div>\n";
      $html .= "</div>\n";
      return $html;
    }
	/**
      * Funcion forma Menu para crear el nuevo contrato o hacer copia de una contrato siempre y cuando este este inactivo
         * @param array $action vector que contiene los link de la aplicacion        
	* @return string $html retorna la cadena con el codigo html de la pagina
    */   
		function FormaMenuNuevoContrato($datos,$action,$empresa,$tipoid,$noid)
		{
			$html  = ThemeAbrirTabla('CONTRATO - PROVEEDOR');
			if(!empty($datos))
			{
				$html .= "<table class=\"modulo_table_list\" width=\"60%\" align=\"center\" border=\"0\" >\n";
				$html .= "  <tr >\n";
				$html .= "      <td colspan=\"4\" class=\"modulo_table_title\" width=\"20%\">PROVEEDOR:</td>\n";
				foreach($datos as $indice => $valor)
				{
					$html .= "      <td class=\"modulo_list_claro\" align=\"center\">".$valor['tipo_id_tercero']." ".$valor['tercero_id']."\n";
					$html .= "      </td>\n";
					$html .= "      <td class=\"modulo_list_claro\" colspan=\"4\" align=\"center\">".$valor['nombre_tercero']."\n";
					$html .= "      </td>\n";
					$html .= "  </tr>\n";
				}
				$html .= "</table>\n";
				$html .= " <br>";
				$html .= "<form name=\"formadato\" id=\"formadato\" method=\"post\" >\n";
				$html .= "<table class=\"modulo_table_list\" width=\"40%\" align=\"center\">\n";
				$html .= "  <tr class=\"formulacion_table_list\">\n";
				$html .= "      <td  align=\"center\">MENÙ\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"center\">OP\n";
				$html .= "      </td>\n";
				$html .= "  </tr>\n";
				$html .= "  <tr class=\"modulo_list_oscuro\">\n";
				$html .= "    <td align=\"LEFT\" > <b>REALIZAR NUEVO CONTRATO</b> \n";
				$html .= "    </td>\n";
				$html .= "    <td align=\"center\">\n";
				$html .= "      <a href=\"".$action['Contrato']."\">\n";
				$html .= "        <img src=\"".GetThemePath()."/images/pplan.png\" border=\"0\">\n";
				$html .= "      </a>\n";
				$html .= "    </td>\n";
				$html .= "  </tr>\n";
				$html .= "  <tr class=\"modulo_list_oscuro\">\n";
				$html .= "      <td align=\"LEFT\" > <b>COPIA DEL CONTRATO</b>\n";
				$html .= "      </td>\n";
				$html .= "      <td align=\"center\">\n";
				$html .= "         <a href=\"#\" onclick=\"xajax_SelecUniNroContrato('".$noid."','".$tipoid."','".$empresa."')\"  class=\"label_error\"><img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\" title=\"Nro.Contrato\"></a>\n";
				$html .= "      </td>\n";
				$html .= "  </tr>\n";
				$html .= "</form>\n";
				$html .= "</table>\n";
			}
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= $this->CrearVentana();
			$html .= ThemeCerrarTabla();
			return $html;
		}
	   /**
      * Funcion forma que permite mostrar mensaje si existe un contrato activo de un proveedor
        * @param array $action vector que contiene los link de la aplicacion
	* @return string $html retorna la cadena con el codigo html de la pagina
    */ 
		
		function FormaMensajeExisteContratoActivo($action)
		{
		
			$html  = ThemeAbrirTabla('MENSAJE ');
			$html .= "<table  width=\"85%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
			$html .= "  <tr align=\"center\" >\n";
			$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> 	EXISTE UN CONTRATO ACTIVO PARA ESTE PROVEEDOR:</b> </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table> ";
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
      		$html .= ThemeCerrarTabla();
			return $html;
		}
  
   /**
      * Funcion donde se crea la Forma del Contrato a llenar  
      * @param array $datos vector que contiene la informacion de Los Proveedores
      * @param array $action vector que contiene los link de la aplicacion
      * @param array $datos_empresa vector que contiene la informacion de Empresa que contrata al proveedor
      * @param string $fecha contiene la informacion de la fecha actual.
      * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
		function FormaLlenarContrato($datos,$action,$empresa)
		{
			$html  = ThemeAbrirTabla(' NUEVO CONTRATO - PROVEEDOR');
			$ctl = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->IsDate("-");
			$html .= $ctl->AcceptDate("-");
			$html .= $ctl->LimpiarCampos();
			$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
			$html .="<script >\n";
			$html .= "  function max(e){  ";
			$html .= "  tecla = (document.all) ? e.keyCode : e.which; ";
			$html .= "  if (tecla==8) return true;";
			$html .= "  if (tecla==13) return false;";
			$html .= " }";
   		    $html .= "  function ValidarDtos(frms)\n";
			$html .= "  {\n";
			$html .= "    if(frms.txtncontrato.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NUMERO DEL CONTRATO';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.desc.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR UNA BREVE DESCRIPCIÒN DEL CONTRATO.. ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(!IsDate(frms.fecha_inicio.value))\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DE INICIO DEL CONTRATO ';\n";
			$html .= "      return;\n";
			$html .= "    } \n";
			$html .= "    if(!IsDate(frms.fecha_final.value))\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DE VENCIMIENTO  DEL CONTRATO ';\n";
			$html .= "      return;\n";
			$html .= "    } \n";
			$html .= "	    f = frms.fecha_inicio.value.split('-')\n";
			$html .= "	    f1 = new Date(f[2]+'/'+ f[1]+'/'+ f[0]); \n";
			$html .= "	    f = frms.fecha_final.value.split('-')\n";
			$html .= "	    f2 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
			$html .= "	    if(f1 >= f2 )\n";
			$html .= "	    {\n";
			$html .= "        document.getElementById('error').innerHTML = 'LA FECHA INICIAL NO PUEDE SER MAYOR O IGUAL A LA FECHA DE VENCIMIENTO  DEL CONTRATO ';\n";
			$html .= "        return;\n";
			$html .= "      } \n";
			$html .= "    if(frms.condtiemp.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LAS CONDICIONES DEL TIEMPO DE ENTREGA';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frms.submit();\n";
			$html .= "    }\n";   
			$html .="</script>\n";
			if(!empty($datos))
			{
		        $html .= "<form name=\"FormallenarContrato\" id=\"FormallenarContrato\" action=\"".$action['guardar']."\"  method=\"post\" >\n";
		        $html .= "<table  width=\"85%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
		        $html .= "  <tr align=\"center\" >\n";
		        $html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> INFORMACIÒN DE LA EMPRESA:</b> </td>\n";
		        $html .= "  </tr>\n";
		        $html .= "  <tr class=\"modulo_table_list_title\" >\n";
		        $html .= "     <input type=\"hidden\" name=\"empresa\" id=\"empresa\" value=\"".$empresa['empresa_id']."\">\n";
		        $html .= "      </td>\n";
		        $html .= "      <td   width=\"10%\"  align=\"left\"> IDENTIFICACION: </td>\n";
		        $html .= "      <td   width=\"20%\"  class=\"modulo_list_claro\"  align=\"center\">".$empresa['tipo_id_tercero']." ".$empresa['id']."\n";
		        $html .= "      </td>\n";
		        $html .= "      <td   width=\"20%\"  align=\"left\">RAZON SOCIAL :</td>\n";
		        $html .= "      <td    colspan=\"5\" class=\"modulo_list_claro\" align=\"center\">".$empresa['razon_social']."\n";
		        $html .= "      </td>\n";
		        $html .= " </tr>\n";
		        $html .= " <tr class=\"modulo_table_list_title\" >\n";
		        $html .= "      <td align=\"left\">CODIGO:\n";
		        $html .= "      </td>\n";
		        $html .= "      <td class=\"modulo_list_claro\" colspan=\"1\" align=\"left\">".$empresa['codigo_sgsss']."\n";
		        $html .= "      </td>\n";
		        $html .= "      <td align=\"left\" >DEPARTAMENTO:\n";
		        $html .= "      </td>\n";
		        $html .= "      <td class=\"modulo_list_claro\" colspan=\"1\" align=\"left\">".$empresa['departamento']."\n";
		        $html .= "      </td>\n";
		        $html .= "      <td align=\"left\" >MUNICIPIO:\n";
		        $html .= "      </td>\n";
		        $html .= "      <td class=\"modulo_list_claro\" colspan=\"2\" align=\"left\" >".$empresa['municipio']."\n";
		        $html .= "      </td>\n";
		        $html .= " </tr>\n";
		        $html .= " <tr  class=\"modulo_table_list_title\" >\n";
		        $html .= "      <td  width=\"10%\" align=\"left\"> DIRECCIÒN: </td>\n";
		        $html .= "      <td   width=\"15%\" class=\"modulo_list_claro\"  align=\"left\">".$empresa['direccion']." \n";
		        $html .= "      </td>\n";
		        $html .= "      <td  width=\"10%\"  align=\"left\"> TELEFONOS: </td>\n";
		        $html .= "      <td    width=\"20%\" class=\"modulo_list_claro \"  align=\"left\">".$empresa['telefonos']." \n";
		        $html .= "      </td>\n";
		        $html .= "      <td  width=\"10%\"  align=\"left\"> FAX: </td>\n";
		        $html .= "      <td    colspan=\"4\" class=\"modulo_list_claro\"  align=\"left\">".$empresa['fax']." \n";
		        $html .= "      </td>\n";
		        $html .= " </tr>\n";
		        $html .= "  <tr align=\"center\" >\n";
		        $html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> INFORMACIÒN DEL PROVEEDOR:</b> </td>\n";
		        $html .= "  </tr>\n";
		        $html .= "  <tr class=\"modulo_table_list_title\" >\n";
				foreach($datos as $indice => $valor)
				{
		           $html .= "       <td width=\"10%\"  align=\"left\"> IDENTIFICACION: </td>\n";
		           $html .= "       <td width=\"20%\"  class=\"modulo_list_claro\"  align=\"center\">".$valor['tipo_id_tercero']." ".$valor['tercero_id']."\n";
		           $html .= "         <input type=\"hidden\" name=\"tipo_id_tercero\" id=\"tipo_id_tercero\" value=\"".$valor['tipo_id_tercero']."\">\n";
		           $html .= "         <input type=\"hidden\" name=\"tercero_id\" id=\"tercero_id\" value=\"".$valor['tercero_id']."\">\n";
		           $html .= "       </td>\n";
		           $html .= "       <td width=\"20%\"  align=\"left\">NOMBRE:</td>\n";
		           $html .= "       <td colspan=\"3\" class=\"modulo_list_claro\" align=\"center\">".$valor['nombre_tercero']."\n";
		           $html .= "       </td>\n";
		           $html .= "     </tr>\n";
		           $html .= "     <tr class=\"modulo_table_list_title\" >\n";
		           $html .= "       <td width=\"20%\" align=\"left\" > DIRECCIÒN: </td>\n";
		           $html .= "       <td colspan=\"5\" class=\"modulo_list_claro\"  >".$valor['direccion']." \n";
		           $html .= "       </td>\n";
		           $html .= "     </tr>\n";
		           $html .= "     <tr   class=\"modulo_table_list_title\" >\n";
		           $html .= "       <td width=\"35%\" align=\"left\"> TELEFONOS: </td>\n";
		           $html .= "       <td class=\"modulo_list_claro\" colspan=\"2\">".$valor['telefonos']." ".$valor['celular']." \n";
		           $html .= "       </td>\n";
		           $html .= "       <td width=\"5%\" align=\"left\" > FAX: </td>\n";
		           $html .= "       <td colspan=\"2\" class=\"modulo_list_claro\" >".$valor['fax']." \n";
		           $html .= "       </td>\n";
		           $html .= "  </tr>\n";
		           $html .= "  <tr class=\"modulo_table_list_title\">\n";
		           $html .= "      <td width=\"40%\"   align=\"left\"> GERENTE: </td>\n";
		           $html .= "       <td  colspan=\"3\" class=\"modulo_list_claro\"  align=\"center\">".$valor['nombre_gerente']." \n";
		           $html .= "       </td>\n";
		           $html .= "       <td width=\"25%\"  align=\"left\"> TELEFONO: </td>\n";
		           $html .= "       <td class=\"modulo_list_claro\"  align=\"center\">".$valor['telefono_gerente']." \n";
		           $html .= "       </td>\n";
		           $html .= "  </tr>\n";
				}
			}
	        $html .= "  <tr align=\"center\" >\n";
	        $html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> INFORMACIÒN DEL CONTRATO:</b> </td>\n";
	        $html .= "  </tr>\n";
	        $html .= "  <tr class=\"modulo_table_list_title\" >\n";
	        $html .= "      <td align=\"left\">* No CONTRATO:</td>\n";
	        $html .= "      <td colspan=\"5\" align=\"left\"  class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\" name=\"txtncontrato\" id=\"txtncontrato\"   value=\"".$_REQUEST['txtncontrato']."\" size=\"30%\" maxlength=\"30\" >\n";
	        $html .= "      </td>\n";
	        $html .= "  </tr>\n";
	        $html .= "  <tr class=\"modulo_table_list_title\">\n";
	        $html .= "		<td width=\"30%\" align=\"left\" > * FECHA INICIO:</td>\n";
			$html .= "		<td width=\"15%\" class=\"modulo_list_claro\" align=\"center\">\n";
			$html .= "		  <input type=\"text\" class=\"input-text\" name=\"fecha_inicio\"  id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$_REQUEST['fecha_inicio']."\"  >\n";
			$html .= "		</td>\n";
			$html .= "    <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
			$html .= "				".ReturnOpenCalendario('FormallenarContrato','fecha_inicio','-')."\n";
			$html .= "		</td>\n";
			$html .= "		<td align=\"left\" > * FECHA VENC.:</td>\n";
			$html .= "		<td class=\"modulo_list_claro\" align=\"center\">\n";
			$html .= "		    <input type=\"text\" class=\"input-text\" name=\"fecha_final\" id=\"fecha_final\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" READONLY value=\"".$_REQUEST['fecha_final']."\" \n";
			$html .= "		</td>\n";
			$html .= "    <td width=\"80%\"class=\"modulo_list_claro\" >\n";
			$html .= "				".ReturnOpenCalendario('FormallenarContrato','fecha_final','-')."\n";
			$html .= "		</td>\n";
			$html .= "  </tr >\n";
      $html .= "  <tr class=\"modulo_table_list_title\">\n";
      $html .= "      <td align=\"left\"> * DESCRIPCIÒN DEL CONTRATO:</td>\n";
      $html .= "      <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
      $html .= "        <textarea  onkeypress=\"return max(event)\"  name=\"desc\"  id=\"desc\" rows=\"2\" style=\"width:100%\">".$_REQUEST['desc']."</textarea>\n";
      $html .= "       </td>\n";
      $html .= "  </tr >\n";
      $html .= "  <tr class=\"modulo_table_list_title\">\n";
      $html .= "    <td align=\"left\" > * CONDICIONES TIEMPO DE ENTREGA:</td>\n";
      $html .= "    <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
      $html .= "      <textarea onkeypress=\"return max(event)\"  name=\"condtiemp\"  id=\"condtiemp\" rows=\"2\" style=\"width:100%\">".$_REQUEST['condtiemp']."</textarea>\n";
      $html .= "    </td>\n";
      $html .= "  </tr >\n";
      $html .= "  <tr class=\"modulo_table_list_title\">\n";
      $html .= "      <td align=\"left\" >OBSERVACIÒN:</td>\n";
      $html .= "      <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
      $html .= "        <textarea onkeypress=\"return max(event)\"  name=\"observar\" rows=\"2\" style=\"width:100%\">".$_REQUEST['observar']."</textarea>\n";
      $html .= "      </td>\n";
      $html .= "  </tr >\n";
      $html .= "</table>\n";
      $html .= "</form>";
      $html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "						<tr>\n";
			$html .= "							<td align='center'>\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CREAR\" onclick=\" ValidarDtos(document.FormallenarContrato)\">\n";
			$html .= "							</td>\n";
			$html .= "							<td align='center' colspan=\"1\">\n";
			$html .= "								<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.FormallenarContrato)\" value=\"Limpiar Campos\">\n";
			$html .= "							</td>\n";
			$html .= "  </tr >\n";
			$html .= "</form>";
			$html .= "</table>";
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver'].URLRequest(array( "noid"=>$valor['tercero_id'],"tipoid"=>$valor['tipo_id_tercero']))."\"  class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
        }
    
    /**
      * Funcion donde se crea la Forma del Mensaje de aviso si se ha ingresado correctamente o no los datos del contrato.
      * @param array $action vector que contiene los link de la aplicacion.
      * @param string $msg1 Cadena con el texto del mensaje a mostrar  en pantalla.
      * @return string $html retorna la cadena con el codigo html de la pagina.
    */ 
		function FormaMensajeIngresoContrato($action, $msg1=null,$msg1=null,$datos,$opcion)
		{
			$html  = ThemeAbrirTabla("INGRESO DEL CONTRATO");
			$html .= " <form name=\"form8\" method=\"post\" enctype=\"multipart/form-data\" action=\"".$action['productosco']."\"  >";
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$msg1."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>";
			$html .= " <br>";
			$html .= " <table width=\"50\" border=\"0\" align=\"center\" class=\"modulo_list_title\" >";
			$html .= "		<tr>\n";
      if($opcion==1)
      {
      $campo = "      <a href=\"".$action['volver'].URLRequest(array( "empresa"=>$_REQUEST['empresa'],"fecha_inicio"=>$_REQUEST['fecha_inicio'],"fecha_final"=>$_REQUEST['fecha_final'],"desc"=>$_REQUEST['desc'],"condtiemp"=>$_REQUEST['condtiemp'],"txtncontrato"=>$_REQUEST['txtncontrato'],"observar"=>$_REQUEST['observar']))."\"  class=\"label_error\">\n";
      $campo.=	"        Volver\n";
			 $campo.= "      </a>\n";
      }
      else
      $campo =  "			<input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"PRODUCTOS A CONTRATAR\">\n";
      
			$html .= "			<td align=\center\" >\n";
      $html .= $campo;
			$html .= "	  	</td>\n";
			$html .= "		</tr>\n";  
			$html .= "</table>\n";
			$html .= "</form>";
			$html .= ThemeCerrarTabla();
			return $html;
		}
	   /**
      * Funcion forma que  permite llenar el por que del cambio de estado de un contrato
        * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
     
		function FormaObservacionEstado($request, $action)
		{
			$html  = ThemeAbrirTabla("MODIFICAR ESTADO ");
			$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
			$html .="<script >\n";
			$html .= "  function max(e){  ";
			$html .= "  tecla = (document.all) ? e.keyCode : e.which; ";
			$html .= "  if (tecla==8) return true;";
			$html .= "  if (tecla==13) return false;";
			$html .= " }";
			$html .= "  function ValidarDtos(frms)\n";
			$html .= "  {\n";
			$html .= "    if(frms.observacion.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR UNA OBSERVACION DEL PORQUE SE REQUIERE HACER EL CAMBIO DE ESTADO';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frms.submit();\n";
			$html .= "    }\n";   
			$html .="</script>\n";
			$html .= "		<form name=\"formacambioestado\"   action=\"".$action['estadito']."\"    method=\"post\" >";
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend  align=\"center\" class=\"normal_10AN\">CAMBIO DE ESTADO </legend>\n";   
			$html .= "			<table   width=\"10%\" align=\"center\" border=\"0\" >";
			$html .= "	 <tr class=\"formulacion_table_list\">\n";
			$html .= "		  <td colspan=\"1\" align=\"center\">No Contrato\n"; 
			$html .= "      <td   colspan=\"3\"  align=\"center\" class=\"modulo_list_claro\"> ".$request['sncontrato']."\n";
			$html .= "     <input type=\"hidden\" name=\"sncontrato\" id=\"sncontrato\" value=\"".$request['sncontrato']."\">\n";
			$html .= "     <input type=\"hidden\" name=\"estadocam\" id=\"estadocam\" value=\"".$request['estado']."\">\n";
			$html .= "     <input type=\"hidden\" name=\"noidi\" id=\"noidi\" value=\"".$request['noid']."\">\n";
			$html .= "     <input type=\"hidden\" name=\"tipoidi\" id=\"tipoidi\" value=\"".$request['tipoid']."\">\n";
			$html .= "     <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$request['empresa_id']."\">\n";
			$html .= "     <input type=\"hidden\" name=\"contratacion_prod\" id=\"contratacion_prod\" value=\"".$request['contratacion_prod_id']."\">\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$htnl .= " <br>";  
			$html .= "	 <tr class=\"formulacion_table_list\">\n";
			$html .= "		  <td class=\"modulo_table_title\ colspan=\"1\" align=\"center\">*OBSERVACION\n";                                                  
			$html .= "      </td>\n";
			$html .= "      <td   colspan=\"3\"  align=\"center\" class=\"modulo_list_claro\"> <textarea onkeypress=\"return max(event)\"   name=\"observacion\" value=\"\" rows=\"1\" cols=\"65\"></textarea>\n";
			$html .= "      </td>\n";
			$html .= "	 </tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td colspan=\"3\" align='center'>\n";
			$html .= "        <input class=\"input-submit\" type=\"button\"   class=\"input-text\" name=\"btnCrear\"   value=\"CAMBIAR ESTADO\" onclick=\" ValidarDtos(document.formacambioestado)\">\n";
			$html .= "							</td>\n";
			$html .= "</table><br>\n";
			$html .= "</fieldset><br>\n";
			$html .= "            </form>\n";
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "         <a href=\"".$action['volver']."\"  class=\"label_error\">volver</a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table><br>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
   
  /**
      * Funcion donde se crea la Forma para Modificar el Contrato del  Proveedor. 
      * @param array $dtoscont vector que contiene la informacion del contrato.
      * @param array $datos_empresa vector que contiene la informacion de la empresa que contrata al proveedor.
      * @param array $action vector que contiene los link de la aplicacion.
      * @param string $scontrato que contiene  el numero de contrato  
      * @param string $fecha contiene la informacion de la fecha actual.
      * @return string $html retorna la cadena con el codigo html de la pagina.
    */ 
		function FormaModificarConsultarContrato($datos,$action,$empresa, $scontrato)
		{
   
			$html  = ThemeAbrirTabla("INFORMACIÒN DEL CONTRATO - MODIFICAR");  
			$ctl = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->IsDate("-");
			$html .= $ctl->AcceptDate("-");
			$html .= $ctl->LimpiarCampos();
			$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
			$html .="<script >\n";
			$html .= "  function max(e){  ";
			$html .= "  tecla = (document.all) ? e.keyCode : e.which; ";
			$html .= "  if (tecla==8) return true;";
			$html .= "  if (tecla==13) return false;";
			$html .= " }";
			$html .= "  function ValidarDtos(frms)\n";
			$html .= "  {\n";
			$html .= "    if(frms.txtncontrato.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NUMERO DEL CONTRATO';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.desc.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR UNA BREVE DESCRIPCIÒN DEL CONTRATO.. ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(!IsDate(frms.fecha_inicio.value))\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DE INICIO DEL CONTRATO ';\n";
			$html .= "      return;\n";
			$html .= "    } \n";
			$html .= "    if(!IsDate(frms.fecha_final.value))\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DE VENCIMIENTO  DEL CONTRATO ';\n";
			$html .= "      return;\n";
			$html .= "    } \n";
			$html .= "	    f = frms.fecha_inicio.value.split('-')\n";
			$html .= "	    f1 = new Date(f[2]+'/'+ f[1]+'/'+ f[0]); \n";
			$html .= "	    f = frms.fecha_final.value.split('-')\n";
			$html .= "	    f2 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
			$html .= "	    if(f1 >= f2 )\n";
			$html .= "	    {\n";
			$html .= "        document.getElementById('error').innerHTML = 'LA FECHA INICIAL NO PUEDE SER MAYOR O IGUAL A LA FECHA DE VENCIMIENTO  DEL CONTRATO ';\n";
			$html .= "        return;\n";
			$html .= "      } \n";
			$html .= "    if(frms.condtiemp.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LAS CONDICIONES DEL TIEMPO DE ENTREGA';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frms.submit();\n";
			$html .= "    }\n";   
			$html .="</script>\n";
			if(!empty($datos))
			{

				$html .= "<form name=\"FormaModificarContrato\" id=\"FormaModificarContrato\" action=\"".$action['actualizar']."\"  method=\"post\" >\n";
				$html .= "<table  width=\"85%\" align=\"center\" border=\"0\"  class=\"modulo_table_list\" >\n";
				$html .= "  <tr align=\"center\" >\n";
				$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> INFORMACIÒN DE LA EMPRESA (contratista):</b> </td>\n";
				$html .= "  </tr>\n";
				$html .= "  <tr  class=\"modulo_table_list_title\" >\n";
				$html .= "     <input type=\"hidden\" name=\"contratosusti\" id=\"contratosusti\" value=\"".$scontrato."\">\n";
				$html .= "     <input type=\"hidden\" name=\"empresa\" id=\"empresa\" value=\"".$empresa['empresa_id']."\">\n";
				$html .= "      </td>\n";
				$html .= "      <td   width=\"10%\"   align=\"left\"> IDENTIFICACION: </td>\n";
				$html .= "      <td   width=\"20%\"  class=\"modulo_list_claro\"  align=\"left\">".$empresa['tipo_id_tercero']." ".$empresa['id']."\n";
				$html .= "      </td>\n";
				$html .= "      <td   width=\"20%\"   align=\"left\">RAZON SOCIAL :</td>\n";
				$html .= "      <td   colspan=\"5\" class=\"modulo_list_claro\" align=\"left\">".$empresa['razon_social']."\n";
				$html .= "      </td>\n";
				$html .= " </tr>\n";

				$html .= " <tr class=\"modulo_table_list_title\" >\n";
				$html .= "      <td  align=\"left\">CODIGO:\n";
				$html .= "      </td>\n";
				$html .= "      <td class=\"modulo_list_claro\" colspan=\"1\" align=\"left\">".$empresa['codigo_sgsss']."\n";
				$html .= "      </td>\n";
				$html .= "      <td align=\"left\" >DEPARTAMENTO:\n";
				$html .= "      </td>\n";
				$html .= "      <td class=\"modulo_list_claro\" colspan=\"1\" align=\"left\">".$empresa['departamento']."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"left\" >MUNICIPIO:\n";
				$html .= "      </td>\n";
				$html .= "      <td class=\"modulo_list_claro\" colspan=\"2\" align=\"center\" >".$empresa['municipio']."\n";
				$html .= "      </td>\n";
				$html .= " </tr>\n";
				$html .= " <tr  class=\"modulo_table_list_title\" >\n";
				$html .= "      <td  width=\"10%\"  align=\"left\"> DIRECCIÒN: </td>\n";
				$html .= "      <td   width=\"15%\" class=\"modulo_list_claro\"  align=\"left\">".$empresa['direccion']." \n";
				$html .= "      </td>\n";
				$html .= "      <td  width=\"10%\" align=\"left\"> TELEFONOS: </td>\n";
				$html .= "      <td    width=\"20%\" class=\"modulo_list_claro \"  align=\"left\">".$empresa['telefonos']." \n";
				$html .= "      </td>\n";
				$html .= "      <td  width=\"10%\"  align=\"left\"> FAX: </td>\n";
				$html .= "      <td    colspan=\"4\" class=\"modulo_list_claro\"  align=\"left\">".$empresa['fax']." \n";
				$html .= "      </td>\n";
				$html .= " </tr>\n";
				$html .= "  <tr align=\"center\" >\n";
				$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> INFORMACIÒN DEL PROVEEDOR:</b> </td>\n";
				$html .= "  </tr>\n";
				$html .= " <tr  class=\"modulo_table_list_title\" >\n";
				   
				foreach($datos as $indice => $valor)
				{
					$html .= "       <td   width=\"10%\"  align=\"left\"> IDENTIFICACION: </td>\n";
					$html .= "       <td   width=\"20%\"  class=\"modulo_list_claro\"  align=\"left\">".$valor['tipo_id_tercero']." ".$valor['tercero_id']."\n";
					$html .= "    <input type=\"hidden\" name=\"tipo_id_tercero\" id=\"tipo_id_tercero\" value=\"".$valor['tipo_id_tercero']."\">\n";
					$html .= "    <input type=\"hidden\" name=\"tercero_id\" id=\"tercero_id\" value=\"".$valor['tercero_id']."\">\n";
					$html .= "       </td>\n";
					$html .= "       <td   width=\"20%\"   align=\"left\">NOMBRE:</td>\n";
					$html .= "       <td    colspan=\"3\" class=\"modulo_list_claro\" align=\"left\">".$valor['nombre_tercero']."\n";
					$html .= "       </td>\n";
					$html .= " </tr>\n";
					$html .= "     <tr class=\"modulo_table_list_title\" >\n";
					$html .= "       <td width=\"20%\" align=\"left\" > DIRECCIÒN: </td>\n";
					$html .= "       <td colspan=\"5\" class=\"modulo_list_claro\"  >".$valor['direccion']." \n";
					$html .= "       </td>\n";   
					$html .= "     </tr>\n";
					$html .= "     <tr   class=\"modulo_table_list_title\" >\n";
					$html .= "       <td width=\"35%\" align=\"left\"> TELEFONOS: </td>\n";
					$html .= "       <td class=\"modulo_list_claro\" colspan=\"2\">".$valor['telefonos']." ".$valor['celular']." \n";
					$html .= "       </td>\n";
					$html .= "       <td width=\"5%\" align=\"left\" > FAX: </td>\n";
					$html .= "       <td colspan=\"2\" class=\"modulo_list_claro\" >".$valor['fax']." \n";
					$html .= "       </td>\n";
					$html .= "  </tr>\n";
					$html .= "  <tr class=\"modulo_table_list_title\">\n";
					$html .= "      <td width=\"40%\"   align=\"left\"> GERENTE: </td>\n";
					$html .= "       <td  colspan=\"3\" class=\"modulo_list_claro\"  align=\"left\">".$valor['nombre_gerente']." \n";
					$html .= "       </td>\n";
					$html .= "       <td width=\"25%\"  align=\"left\"> TELEFONO: </td>\n";
					$html .= "       <td class=\"modulo_list_claro\"  align=\"left\">".$valor['telefono_gerente']." \n";
					$html .= "       </td>\n";
					$html .= "  </tr>\n";
					$html .= "  <tr align=\"center\" >\n";
					$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> INFORMACIÒN DEL CONTRATO:</b> </td>\n";
					$html .= "  </tr>\n";
					$html .= "  <tr class=\"modulo_table_list_title\">\n";
					$html .= "      <td  align=\"left\">*No CONTRATO:</td>\n";
					$html .= "      <td colspan=\"5\" align=\"left\"  class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\" name=\"txtncontrato\" id=\"txtncontrato\"   value=\"".$valor['no_contrato']."\" size=\"30%\" maxlength=\"30\" >\n";
					$html .= "      </td>\n";
					$html .= "  </tr>\n";
					$html .= "  <tr class=\"modulo_table_list_title\">\n";
					$html .= "				<td   width=\"30%\"  align=\"left\" >*FECHA INICIO:</td>\n";
					$html .= "		  	<td  width=\"15%\" class=\"modulo_list_claro\" align=\"left\">\n";
					$html .= "		    <input type=\"text\" class=\"input-text\" name=\"fecha_inicio\"  id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$valor['fecha_inicio']."\"  >\n";
					$html .= "				</td><td  width=\"15%\" class=\"modulo_list_claro\" >\n";
					$html .= "				".ReturnOpenCalendario('FormaModificarContrato','fecha_inicio','-')."\n";
					$html .= "			  </td>\n";
					$html .= "				<td  align=\"left\" >*FECHA VENC.:</td>\n";
					$html .= "			  <td  class=\"modulo_list_claro\" align=\"left\">\n";
					$html .= "		    <input type=\"text\" class=\"input-text\" name=\"fecha_final\" id=\"fecha_final\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" READONLY value=\"".$valor['fecha_vencimiento']."\" \n";
					$html .= "				</td><td   width=\"80%\"class=\"modulo_list_claro\" >\n";
					$html .= "				".ReturnOpenCalendario('FormaModificarContrato','fecha_final','-')."\n";
					$html .= "		  	</td>\n";
					$html .= "  </tr >\n";

					$html .= "  <tr class=\"modulo_table_list_title\">\n";
					$html .= "      <td  align=\"left\"> *DESCRIPCIÒN DEL CONTRATO:</td>\n";
					$html .= "      <td   colspan=\"5\"  align=\"left\" class=\"modulo_list_claro\"><textarea onkeypress=\"return max(event)\"  name=\"desc\"  id=\"desc\" rows=\"2\"   style=\"width:100%\">".$valor['descripcion']."</textarea>\n";
					$html .= "       </td>\n";
					$html .= "  </tr >\n";

					$html .= "  <tr class=\"modulo_table_list_title\">\n";
					$html .= "      <td  align=\"left\"> *CONDICIONES TIEMPO DE ENTREGA:</td>\n";
					$html .= "      <td   colspan=\"5\"  align=\"left\" class=\"modulo_list_claro\"> <textarea  onkeypress=\"return max(event)\"  name=\"condtiemp\"  id=\"condtiemp\"   rows=\"2\"  style=\"width:100%\">".$valor['condiciones_entrega']."</textarea>\n";
					$html .= "       </td>\n";
					$html .= "  </tr >\n";
					$html .= "  <tr class=\"modulo_table_list_title\">\n";
					$html .= "      <td  align=\"left\" >OBSERVACIÒN:</td>\n";
					$html .= "      <td   colspan=\"5\"  align=\"left\" class=\"modulo_list_claro\"> <textarea onkeypress=\"return max(event)\"  name=\"observar\"  rows=\"2\" style=\"width:100%\">".$valor['observaciones']."</textarea>\n";
					$html .= "      </td>\n";
					$html .= "  </tr >\n";
				}
			}
			$html .= "</table>\n";
			$html .= "</form>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "						<tr>\n";
			$html .= "							<td align='center'>\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"MODIFICAR\" onclick=\" ValidarDtos(document.FormaModificarContrato)\">\n";
			$html .= "							</td>\n";
			$html .= "							<td align='center' colspan=\"1\">\n";
			$html .= "								<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.FormaModificarContrato)\" value=\"Limpiar Campos\">\n";
			$html .= "							</td>\n";
			$html .= "  </tr >\n";
			$html .= "</form>";
			$html .= "</table>";
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver'].URLRequest(array( "tipoid"=>$valor['tipo_id_tercero'],"noid"=>$valor['tercero_id']))."\"  class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
    /**
      * Funcion donde se crea la Forma para Actualizar los datos del Contrato del  Proveedor. 
      * @param array $action vector que contiene los link de la aplicacion.
      * @param string $msg1 Cadena con el texto del mensaje a mostrar  en pantalla.
      * @return string $html retorna la cadena con el codigo html de la pagina.
    */ 
		function formamensajeactualizarcontrato($action, $msg1=null,$msg1=null,$contrav,$contratosus,$noidcontrato,$request)
		{
			$html  = ThemeAbrirTabla("ACTUALIZACIÒN DEL CONTRATO");
			$html .= "<table border=\"1\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$msg1."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>";
			$html .= "<br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
			$html .= "        volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= $this->CrearVentana();
			$html .= ThemeCerrarTabla();
			return $html;
		}
 /**
      * Funcion Forma que permite mostrar todos los productos para asociarlos a un contrato  y a un proveedor
	  * @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
        
        function FormaMenuProducto($action,$request,$datos,$conteo,$pagina,$grupo,$contrato,$empresa,$noidcontrato,$proveid,$tipoid,$noid,$codprov)                             
		{
         
				$html  ="  <script>\n";
				$html .= "	function Todos(frm,x){";
				$html .= "	  if(x==true ){";
				$html .= "	    for(i=0;i<frm.elements.length;i++){";
				$html .= "	      if(frm.elements[i].type=='checkbox' || frm.elements[i].type=='text' ){";
				$html .= "              if(frm.elements[i].name=='checkporcentaje'){";
				$html .= "	                  frm.elements[i].checked=true;";
				$html .= "	      }";
				$html .= "	      }";
				$html .= "	                           if(frm.elements[i].name=='txtporcen' ){";
				$html .= "	                                frm.elements[i].disabled=false;";
				$html .= "	    }";
				$html .= "	  }";
				$html .= "	 }";
				$html .= "	  else{";
				$html .= "	    for(i=0;i<frm.elements.length;i++){";
				$html .= "	         if(frm.elements[i].type=='checkbox' || frm.elements[i].type=='text' ){";
				$html .= "              if(frm.elements[i].name=='checkporcentaje'){";
				$html .= "	              frm.elements[i].checked=false;";
				$html .= "	      }";
				$html .= "	      }";
				$html .= "	      }";
				$html .= "	                 for(i=0;i<frm.elements.length;i++){";
				$html .= "	                           if(frm.elements[i].name=='txtporcen' ){";
				$html .= "	                                frm.elements[i].disabled=true;";
				$html .= "	  }";
				$html .= "	 }";
				$html .= "	}";
				$html .= "	}";
				$html .= "	function ValorPesos(frm,x){";
				$html .= "	  if(x==true ){";
				$html .= "	    for(i=0;i<frm.elements.length;i++){";
				$html .= "	      if(frm.elements[i].type=='checkbox' || frm.elements[i].type=='text' ){";
				$html .= "              if(frm.elements[i].name=='checkpesos'){";
				$html .= "	                  frm.elements[i].checked=true;";
				$html .= "	      }";
				$html .= "	      }";
				$html .= "	                           if(frm.elements[i].name=='txtpesos' ){";
				$html .= "	                                frm.elements[i].disabled=false;";
				$html .= "	    }";
				$html .= "	  }";
				$html .= "	 }";
				$html .= "	  else{";
				$html .= "	    for(i=0;i<frm.elements.length;i++){";
				$html .= "	         if(frm.elements[i].type=='checkbox' || frm.elements[i].type=='text' ){";
				$html .= "              if(frm.elements[i].name=='checkpesos'){";
				$html .= "	              frm.elements[i].checked=false;";
				$html .= "	      }";
				$html .= "	      }";
				$html .= "	      }";
				$html .= "	                 for(i=0;i<frm.elements.length;i++){";
				$html .= "	                           if(frm.elements[i].name=='txtpesos' ){";
				$html .= "	                                frm.elements[i].disabled=true;";
				$html .= "	  }";
				$html .= "	 }";
				$html .= "	}";
				$html .= "	}";
				$html .= "	function TodoMismoValor(frm,x){";
				$html .= "	  if(x==true ){";
				$html .= "	    for(i=0;i<frm.elements.length;i++){";
				$html .= "	      if(frm.elements[i].type=='checkbox' || frm.elements[i].type=='text' ){";
				$html .= "              if(frm.elements[i].name=='checkporcentaje'){";
				$html .= "	                  frm.elements[i].checked=true;";
				$html .= "	      }";
				$html .= "	      }";
				$html .= "	                           if(frm.elements[i].name=='txtporcen' ){";
				$html .= "	                                frm.elements[i].disabled=false;";
				$html .= "	                                frm.elements[i].value=frm.valorporcentaje.value;";
				$html .= "	    }";
				$html .= "	  }";
				$html .= "	 }";
				$html .= "	  else{";
				$html .= "	    for(i=0;i<frm.elements.length;i++){";
				$html .= "	         if(frm.elements[i].type=='checkbox' || frm.elements[i].type=='text' ){";
				$html .= "              if(frm.elements[i].name=='checkporcentaje'){";
				$html .= "	              frm.elements[i].checked=false;";
				$html .= "	      }";
				$html .= "	      }";
				$html .= "	      }";
				$html .= "	                 for(i=0;i<frm.elements.length;i++){";
				$html .= "	                           if(frm.elements[i].name=='txtporcen' ){";
				$html .= "	                                frm.elements[i].disabled=true;";
				$html .= "	  }";
				$html .= "	 }";
				$html .= "	}";
				$html .= "	}";
				$html .= "	function Todoselec(frm,x){";
				$html .= "	  if(x==true ){";
				$html .= "	    for(i=0;i<frm.elements.length;i++){";
				$html .= "	      if(frm.elements[i].type=='checkbox'){";
				$html .= "              if(frm.elements[i].name=='checkseleccionar' ){";
				$html .= "	                  frm.elements[i].checked=true;";
				$html .= "	      }";
				$html .= "	      }";
				$html .= "	 }";
				$html .= "	 }";
				$html .= "	 ";
				$html .= "	  else{";
				$html .= "	    for(i=0;i<frm.elements.length;i++){";
				$html .= "	         if(frm.elements[i].type=='checkbox'){";
				$html .= "              if(frm.elements[i].name=='checkseleccionar'){";
				$html .= "	              frm.elements[i].checked=false;";
				$html .= "	      }";
				$html .= "	      }";
				$html .= "	      }";
				$html .= "	}";
				$html .= "	}";
				$html .= "	function   SelInd(value,frm){";
				$html .= "alert(frm.elements.length);";
				$html .= "	      }";
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
				$html .="  </script>\n";
				
        $html .= ThemeAbrirTabla('SELECCION - PRODUCTO');
				$html .="<div id=\"pantalla\">";
        $html .= "		<form name=\"formita\" id=\"formita\" action=\"".$action['buscador']."\" method=\"post\"     >";
				$html .= "			<table   width=\"40%\" align=\"center\" border=\"0\"   >";
				$html .= "         <tr class=\"modulo_table_list_title\">\n";
				$html .= "		          	<td  width=\"40%\" >GRUPO:</td>\n";
				$html .= "			            <td  class=\"modulo_list_claro\" >\n";
				$html .= "					            <select name=\"grupo\" class=\"select\" onchange=\"xajax_MostrarLaboratorios(xajax.getFormValues('formita'))\">\n";
				$html .= "                        	<option value = '-1'>--  SELECCIONE --</option>\n";
				$csk = "";
				foreach($grupo as $indice => $valor)
				{
				if($valor['grupo_id']==$request['grupo_id'])
					$sel = "selected";
					else   $sel = "";
					$html .= "  <option value=\"".$valor['grupo_id']."\" ".$sel.">".$valor['descripcion']."</option>\n";
				}
				$html .= "                </select>\n";
				$html .= "					  	  </td>\n";
				$html .= "		          	<td  width=\"40%\" class=\"modulo_table_list_title\">CODIGO:</td>\n";
				$html .= "	               <td class=\"modulo_list_claro\" colspan=\"4\">\n";
				$html .= "                        <input type=\"text\" class=\"input-text\" name=\"buscador[codigo_producto]\" maxlength=\"32\" value=".$request['codigo_producto']."></td>\n";
				$html .= "	 </tr>\n";
				$html .= "  <tr class=\"modulo_table_list_title\">\n";
				$html .= "		           	<td  width=\"40%\" >LABORATORIO:</td>\n";
				$html .= "		            	<td  class=\"modulo_list_claro\" align=\"left\">\n";
				$html .= "					           <select name=\"laboratorio\" class=\"select\" onChange=\"xajax_SeleccionarMolecula(xajax.getFormValues('formita'))\">\n";
				$html .= "                     	<option value ='-1'>--  SELECCIONE --</option>\n";
				$csk = "";
				$html .= "                </select>\n";
				$html .= "						     </td>\n";
				$html .= "			          <td class=\"modulo_table_list_title\">CODIGO ALTERNO:</td>\n";
				$html .= "		           	<td  colspan=\"4\" class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\" name=\"buscador[codigo_alterno]\" maxlength=\"32\" value=".$request['codigo_alterno']."></td>\n";
				$html .= "		</tr>\n";
				$html .= "  <tr class=\"modulo_table_list_title\">\n";
				$html .= "			         	<td width=\"40%\"  >MOLECULA:</td>\n";
				$html .= "			        	<td class=\"modulo_list_claro\" align=\"left\">\n";
				$html .= "			         		<select name=\"molecula\" class=\"select\"  >\n";
				$html .= "					       	<option value=\"-1\">-SELECCIONAR-</option>\n";
				$html .= "				        	</select>\n";			
				$html .= "			          	</td>\n";		
				$html .= "		           	<td class=\"modulo_table_list_title\">DESCRIPCION:</td>\n";
				$html .= "		           <td  colspan=\"4\" class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\" name=\"buscador[descripcion]\" maxlength=\"32\" value=".$request['descripcion']."></td>\n";
				$html .= "		</tr>\n";
				$html .= "		<tr>\n";
				$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
				$html .= "			         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"  >\n";
				$html .= "		          	</td>\n";
				$html .= "		<tr>\n";
				$html .= "			<td  colspan=\"10\" align='center' >\n";
				$html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formita)\" value=\"Limpiar Campos\">\n";
				$html .= "	  	</td>\n";
				$html .= "		</tr>\n";
				$html .= "</table><br>\n";
				$html .= "			<table   width=\"40%\" align=\"center\" border=\"0\"   >";
				$html .= "    <tr class=\"modulo_table_list_title\">\n";
				$html .= "			<td  width=\"10\" class=\"modulo_table_list_title\">PORCENTAJE A TODOS LOS PRODUCTOS </td>\n";
				$html .= "	    <td  align=\"center\"  width=\"15%\" class=\"modulo_list_claro\" > VALOR%:\n";
				$html .= "   <input type=\"text\" class=\"input-text\" name=\"valorporcentaje\" maxlength=\"5\" size=\"5\"  value=\"".$request['valorporcentaje']."\" >";
				$html .= "	     </td>";
				$html .= "    <td  width=\"10%\" class=\"modulo_list_claro\"   >\n";
				$html .= "	     <input type=\"checkbox\" name=\"Todo\" onClick=\"TodoMismoValor(this.form,this.checked)\">";
				$html .= "	    </td>";
        $html .= "</table><br>\n";
        $html .= "<br> ";
        $html .= "<table  width=\"95%\"   align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "      <td colspan=\"15\"><div id=\"productos\"></div></td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<br> ";
        $html .= "      <script>\n";
        $html .= "        xajax_ProductosSeleccionados('".$empresa."','".$noidcontrato."');\n";
        $html .= "      </script>\n";
        $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
				if(!empty($datos))
				{
					$pghtml = AutoCarga::factory('ClaseHTML');
					 $html .= "<fieldset class=\"fieldset\">\n";
           $html .= "  <legend    align=\"left\"><b>PRODUCTOS</b></legend>\n";
          $html .= "  <table width=\"100%\" class=\"modulo_table_list_title\" align=\"center\">";
					$html .= "	  <tr align=\" class=\"modulo_table_list_title\" >\n";
					$html .= "      <td width=\"15%\">MOLECULA</td>\n";
          $html .= "      <td width=\"15%\">CODIGO</td>\n";
					$html .= "      <td width=\"25%\">DESCRIPCION</td>\n";
					$html .= "      <td width=\"10%\">$ VALOR </td>\n";
					$html .= "	      <td  width=\"15%\" aling=\"center\" class=\"modulo_table_list_title\" >";
					$html .= "	      $ VALOR  PACT.<input type=\"checkbox\" name=\"Todo\" onClick=\"ValorPesos(this.form,this.checked)\">";
					$html .= "	         </td>";
					$html .= "	      <td  width=\"5%\" class=\"modulo_table_list_title\" >";
					$html .= "	       VALOR % 	<input type=\"checkbox\" name=\"Todo\" onClick=\"Todos(this.form,this.checked)\">";
          $html .= "     </td>";
					$html .= "	      <td  width=\"2%\" class=\"modulo_table_list_title\" >";
					$html .= "	     OP ";
					$html .= "	      </td>";
					$html .= "  </tr>\n";
					$est = "modulo_list_claro"; $back = "#DDDDDD";
				
					$i=0;
					foreach($datos as $key => $dtl)
					{

						$html .= "  <tr class=\"modulo_list_claro\">\n";
						$html .= "      <td align=\"center\">".$dtl['molecula']."</td>\n";
            $html .= "      <td align=\"center\">".$dtl['codigo_producto']."</td>\n";
						$html .= "        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto".$i."\" value=\"".$dtl['codigo_producto']."\">";
						$html .= "        <input type=\"hidden\" name=\"empresa_id\" id=\"codigo_producto".$i."\" value=\"".$empresa['empresa_id']."\">";
						$html .= "      <td align=\"left\">".$dtl['producto']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']." - ".$dtl['laboratorio']."</td>\n";
						$html .= "      <td align=\"left\">".$dtl['costo']."</td>\n";
						$html .= "        <input type=\"hidden\" name=\"costo\" id=\"costo".$i."\" value=\"".$dtl['costo']."\">";
            $html .=" <td width=\"13%\" aling=\"center\"> ";
						$html .=" $ <input type=\"text\" name=\"txtpesos\" id=\"txtpesos".$i."\" value=\"\"  size=\"10\" disabled ;\"> ";
						$html .=" <input type=\"checkbox\" name=\"checkpesos\" id=\"checkpesos".$i."\" value=\"".$i."\" onClick=\"xajax_Activar(this.value)\"> ";
						$html .="</td>";
						$html .=" <td width=\"10%\">";
						$html .="  <input type=\"text\" name=\"txtporcen\" id=\"txtporcen".$i."\" value=\"\"  size=\"3\" disabled ;\"> %";
						$html .="<input type=\"checkbox\" name=\"checkporcentaje\" id=\"checkporcentaje".$i."\" value=\"".$i."\"  onClick=\"xajax_Activar2(this.value)\">  ";
            $html .="	</td> ";
    			  $html .=" <td> <input type=\"checkbox\" name=\"checkseleccionar".$i."\" id=\"checkseleccionar".$dtl['codigo_producto']."\" value=\"".$i."\" ".$checked." onClick=\"xajax_ValidarDatosProducto(this.value,'".$empresa."','".$noidcontrato."');\" > ";       
	          $html .= " </td>\n";

						$i=$i+1; 
					}
     
					$html .= "	</table><br>\n";
          $html .= "</fieldset><br>\n";		
					$html .= "  <table width=\"17%\" align=\"Right\">";
					$html .= "		<tr>\n";
					$html .= "			<td    >\n";
					$html .= "			<input class=\"input-submit\" type=\"button\" name=\"Guardar\" value=\"GUARDAR\"  onclick=\"xajax_ValidarTodosLosDatosProducto('".$empresa."','".$noidcontrato."')\">\n";
					$html .= "	  	</td>\n";
					$html .= "		</tr>\n";  
					$html .= "	</table><br>\n";  
				
          $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
					$html .= "	<br>\n";
				} else
				{
				if($request)
					$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
				}
				$html .= "		   </form>\n";
				$html .="</div>";
        $html .= ThemeCerrarTabla();
				return $html;
		}
	   /**
      * Funcion forma que permite realizar  la copia de un contrato
	  * @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
  		function FormaCopiarContrato($datos,$action,$empresa, $scontrato,$datosproductos)
		{
			$html  = ThemeAbrirTabla("INFORMACIÒN DEL CONTRATO - COPIAR -MODIFICAR");  
			$ctl = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->IsDate("-");
			$html .= $ctl->AcceptDate("-");
			$html .= $ctl->LimpiarCampos();
			$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
			$html .="<script >\n";
			$html .= "  function max(e){  ";
			$html .= "  tecla = (document.all) ? e.keyCode : e.which; ";
			$html .= "  if (tecla==8) return true;";
			$html .= "  if (tecla==13) return false;";
			$html .= " }";
			$html .= "  function ValidarDtos(frms)\n";
			$html .= "  {\n";
			$html .= "    if(frms.txtncontrato.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NUMERO DEL CONTRATO';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.desc.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR UNA BREVE DESCRIPCIÒN DEL CONTRATO.. ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(!IsDate(frms.fecha_inicio.value))\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DE INICIO DEL CONTRATO ';\n";
			$html .= "      return;\n";
			$html .= "    } \n";
			$html .= "    if(!IsDate(frms.fecha_final.value))\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DE VENCIMIENTO  DEL CONTRATO ';\n";
			$html .= "      return;\n";
			$html .= "    } \n";
			$html .= "	    f = frms.fecha_inicio.value.split('-')\n";
			$html .= "	    f1 = new Date(f[2]+'/'+ f[1]+'/'+ f[0]); \n";
			$html .= "	    f = frms.fecha_final.value.split('-')\n";
			$html .= "	    f2 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
			$html .= "	    if(f1 >= f2 )\n";
			$html .= "	    {\n";
			$html .= "        document.getElementById('error').innerHTML = 'LA FECHA INICIAL NO PUEDE SER MAYOR O IGUAL A LA FECHA DE VENCIMIENTO  DEL CONTRATO ';\n";
			$html .= "        return;\n";
			$html .= "      } \n";
			$html .= "    if(frms.condtiemp.value==\"\")\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LAS CONDICIONES DEL TIEMPO DE ENTREGA';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frms.submit();\n";
			$html .= "    }\n";   
			$html .="</script>\n";

			if(!empty($datos))
			{

				$html .= "<form name=\"FormaModificarContrato\" id=\"FormaModificarContrato\" action=\"".$action['actualiza']."\"  method=\"post\" >\n";
				$html .= "<table  width=\"85%\" align=\"center\" border=\"0\"  class=\"modulo_table_list\" >\n";
				$html .= "  <tr align=\"center\" >\n";
				$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> INFORMACIÒN DE LA EMPRESA (contratista):</b> </td>\n";
				$html .= "  </tr>\n";
				$html .= "  <tr  class=\"modulo_table_list_title\" >\n";
				$html .= "     <input type=\"hidden\" name=\"contratosusti\" id=\"contratosusti\" value=\"".$scontrato."\">\n";
				$html .= "     <input type=\"hidden\" name=\"empresa\" id=\"empresa\" value=\"".$empresa['empresa_id']."\">\n";
				$html .= "      </td>\n";
				$html .= "      <td   width=\"10%\"   align=\"left\"> IDENTIFICACION: </td>\n";
				$html .= "      <td   width=\"20%\"  class=\"modulo_list_claro\"  align=\"left\">".$empresa['tipo_id_tercero']." ".$empresa['id']."\n";
				$html .= "      </td>\n";
				$html .= "      <td   width=\"20%\"   align=\"left\">RAZON SOCIAL :</td>\n";
				$html .= "      <td   colspan=\"5\" class=\"modulo_list_claro\" align=\"left\">".$empresa['razon_social']."\n";
				$html .= "      </td>\n";
				$html .= " </tr>\n";

				$html .= " <tr class=\"modulo_table_list_title\" >\n";
				$html .= "      <td  align=\"left\">CODIGO:\n";
				$html .= "      </td>\n";
				$html .= "      <td class=\"modulo_list_claro\" colspan=\"1\" align=\"left\">".$empresa['codigo_sgsss']."\n";
				$html .= "      </td>\n";
				$html .= "      <td align=\"left\" >DEPARTAMENTO:\n";
				$html .= "      </td>\n";
				$html .= "      <td class=\"modulo_list_claro\" colspan=\"1\" align=\"left\">".$empresa['departamento']."\n";
				$html .= "      </td>\n";
				$html .= "      <td  align=\"left\" >MUNICIPIO:\n";
				$html .= "      </td>\n";
				$html .= "      <td class=\"modulo_list_claro\" colspan=\"2\" align=\"center\" >".$empresa['municipio']."\n";
				$html .= "      </td>\n";
				$html .= " </tr>\n";
				$html .= " <tr  class=\"modulo_table_list_title\" >\n";
				$html .= "      <td  width=\"10%\"  align=\"left\"> DIRECCIÒN: </td>\n";
				$html .= "      <td   width=\"15%\" class=\"modulo_list_claro\"  align=\"left\">".$empresa['direccion']." \n";
				$html .= "      </td>\n";
				$html .= "      <td  width=\"10%\" align=\"left\"> TELEFONOS: </td>\n";
				$html .= "      <td    width=\"20%\" class=\"modulo_list_claro \"  align=\"left\">".$empresa['telefonos']." \n";
				$html .= "      </td>\n";
				$html .= "      <td  width=\"10%\"  align=\"left\"> FAX: </td>\n";
				$html .= "      <td    colspan=\"4\" class=\"modulo_list_claro\"  align=\"left\">".$empresa['fax']." \n";
				$html .= "      </td>\n";
				$html .= " </tr>\n";
				$html .= "  <tr align=\"center\" >\n";
				$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> INFORMACIÒN DEL PROVEEDOR:</b> </td>\n";
				$html .= "  </tr>\n";
				$html .= " <tr  class=\"modulo_table_list_title\" >\n";
				foreach($datos as $indice => $valor)
				{
					$html .= "       <td   width=\"10%\"  align=\"left\"> IDENTIFICACION: </td>\n";
					$html .= "       <td   width=\"20%\"  class=\"modulo_list_claro\"  align=\"left\">".$valor['tipo_id_tercero']." ".$valor['tercero_id']."\n";
					$html .= "    <input type=\"hidden\" name=\"tipo_id_tercero\" id=\"tipo_id_tercero\" value=\"".$valor['tipo_id_tercero']."\">\n";
					$html .= "    <input type=\"hidden\" name=\"tercero_id\" id=\"tercero_id\" value=\"".$valor['tercero_id']."\">\n";
					$html .= "       </td>\n";
					$html .= "       <td   width=\"20%\"   align=\"left\">NOMBRE:</td>\n";
					$html .= "       <td    colspan=\"3\" class=\"modulo_list_claro\" align=\"left\">".$valor['nombre_tercero']."\n";
					$html .= "       </td>\n";
					$html .= " </tr>\n";
					$html .= "     <tr class=\"modulo_table_list_title\" >\n";
					$html .= "       <td width=\"20%\" align=\"left\" > DIRECCIÒN: </td>\n";
					$html .= "       <td colspan=\"5\" class=\"modulo_list_claro\"  >".$valor['direccion']." \n";
					$html .= "       </td>\n";   
					$html .= "     </tr>\n";
					$html .= "     <tr   class=\"modulo_table_list_title\" >\n";
					$html .= "       <td width=\"35%\" align=\"left\"> TELEFONOS: </td>\n";
					$html .= "       <td class=\"modulo_list_claro\" colspan=\"2\">".$valor['telefonos']." ".$valor['celular']." \n";
					$html .= "       </td>\n";
					$html .= "       <td width=\"5%\" align=\"left\" > FAX: </td>\n";
					$html .= "       <td colspan=\"2\" class=\"modulo_list_claro\" >".$valor['fax']." \n";
					$html .= "       </td>\n";
					$html .= "  </tr>\n";
					$html .= "  <tr class=\"modulo_table_list_title\">\n";
					$html .= "      <td width=\"40%\"   align=\"left\"> GERENTE: </td>\n";
					$html .= "       <td  colspan=\"3\" class=\"modulo_list_claro\"  align=\"left\">".$valor['nombre_gerente']." \n";
					$html .= "       </td>\n";
					$html .= "       <td width=\"25%\"  align=\"left\"> TELEFONO: </td>\n";
					$html .= "       <td class=\"modulo_list_claro\"  align=\"left\">".$valor['telefono_gerente']." \n";
					$html .= "       </td>\n";
					$html .= "  </tr>\n";
					$html .= "  <tr align=\"center\" >\n";
					$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> INFORMACIÒN DEL CONTRATO:</b> </td>\n";
					$html .= "  </tr>\n";
					$html .= "  <tr class=\"modulo_table_list_title\">\n";
					$html .= "      <td  align=\"left\">No CONTRATO:</td>\n";
					$html .= "      <td colspan=\"5\" align=\"left\"  class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\" name=\"txtncontrato\" id=\"txtncontrato\"   value=\"".$valor['no_contrato']."\" size=\"30%\" maxlength=\"30\" >\n";
					$html .= "      </td>\n";
					$html .= "  </tr>\n";
					$html .= "  <tr class=\"modulo_table_list_title\">\n";
					$html .= "				<td   width=\"30%\"  align=\"left\" >FECHA INICIO:</td>\n";
					$html .= "		  	<td  width=\"15%\" class=\"modulo_list_claro\" align=\"left\">\n";
					$html .= "		    <input type=\"text\" class=\"input-text\" name=\"fecha_inicio\"  id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$valor['fecha_inicio']."\"  >\n";
					$html .= "				</td><td  width=\"15%\" class=\"modulo_list_claro\" >\n";
					$html .= "				".ReturnOpenCalendario('FormaModificarContrato','fecha_inicio','-')."\n";
					$html .= "			  </td>\n";
					$html .= "				<td  align=\"left\" >FECHA VENC.:</td>\n";
					$html .= "			  <td  class=\"modulo_list_claro\" align=\"left\">\n";
					$html .= "		    <input type=\"text\" class=\"input-text\" name=\"fecha_final\" id=\"fecha_final\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" READONLY value=\"".$valor['fecha_vencimiento']."\" \n";
					$html .= "				</td><td   width=\"80%\"class=\"modulo_list_claro\" >\n";
					$html .= "				".ReturnOpenCalendario('FormaModificarContrato','fecha_final','-')."\n";
					$html .= "		  	</td>\n";
					$html .= "  </tr >\n";
					$html .= "  <tr class=\"modulo_table_list_title\">\n";
					$html .= "      <td  align=\"left\"> DESCRIPCIÒN DEL CONTRATO:</td>\n";
					$html .= "      <td   colspan=\"5\"  align=\"left\" class=\"modulo_list_claro\"><textarea onkeypress=\"return max(event)\"  name=\"desc\"  id=\"desc\" rows=\"2\"   style=\"width:100%\">".$valor['descripcion']."</textarea>\n";
					$html .= "       </td>\n";
					$html .= "  </tr >\n";
					$html .= "  <tr class=\"modulo_table_list_title\">\n";
					$html .= "      <td  align=\"left\"> CONDICIONES TIEMPO DE ENTREGA:</td>\n";
					$html .= "      <td   colspan=\"5\"  align=\"left\" class=\"modulo_list_claro\"> <textarea  onkeypress=\"return max(event)\"  name=\"condtiemp\"  id=\"condtiemp\"   rows=\"2\"  style=\"width:100%\">".$valor['condiciones_entrega']."</textarea>\n";
					$html .= "       </td>\n";
					$html .= "  </tr >\n";
					$html .= "  <tr class=\"modulo_table_list_title\">\n";
					$html .= "      <td  align=\"left\" >OBSERVACIÒN:</td>\n";
					$html .= "      <td   colspan=\"5\"  align=\"left\" class=\"modulo_list_claro\"> <textarea  onkeypress=\"return max(event)\"  name=\"observar\"  rows=\"2\" style=\"width:100%\">".$valor['observaciones']."</textarea>\n";
					$html .= "      </td>\n";
					$html .= "  </tr >\n";
				}
			}
			$html .= "</table>\n";
			$html .= "</form>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "						<tr>\n";
			$html .= "							<td align='center'>\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"PRODUCTOS\" onclick=\" ValidarDtos(document.FormaModificarContrato)\">\n";
			$html .= "							</td>\n";
			$html .= "							<td align='center' colspan=\"1\">\n";
			$html .= "								<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.FormaModificarContrato)\" value=\"Limpiar Campos\">\n";
			$html .= "							</td>\n";
			$html .= "  </tr >\n";
			$html .= "</form>";
			$html .= "</table>";
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
			}
	
	   /**
      * Funcion forma que permite mostrar los productos asociados a un contrato y a un proveedor
	  * @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
             
		function FormaMostarProductos($action,$datospr,$contratov,$empresa,$contratacion_prod_id)
		{
			$html  = ThemeAbrirTabla("INFORMACIÒN DEL CONTRATO - PRODUCTOS");  

			$html .= "<form name=\"FormaModificarContrato\" id=\"FormaModificarContrato\" action=\"".$action['act']."\"  method=\"post\" >\n";
			$html .= "<table  width=\"90%\" align=\"center\" border=\"0\"  class=\"modulo_table_list\" >\n";
			$html .= "  <tr align=\"center\" >\n";
			$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> PRODUCTOS - CONTRATO:</b> </td>\n";
			$html .= "  </tr>\n";

			if(!empty($datospr))
			{  
				$html .= "	  <tr align=\"CENTER\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td width=\"15%\">CODIGO</td>\n";
				$html .= "      <td width=\"25%\">DESCRIPCION</td>\n";
				$html .= "      <td width=\"10%\"> PRECIO </td>\n";
				$html .= "      <td width=\"10%\">VALOR PACTADO</td>\n";
				$html .= "      <td width=\"10%\">PORCENTAJE</td>\n";
				$html .= "      <td width=\"10%\">VALOR TOTAL PACTADO</td>\n";
				$html .= "	      <td  width=\"2%\"  >";
				$html .= "	     OP. </td>";
				$html .= "  </tr>\n";
				$i=0;
				foreach($datospr as $indice => $dtl)
				{
					$html .= "  <tr class=\"modulo_list_claro\">\n";
					$html .= "      <td align=\"center\">".$dtl['codigo_producto']."</td>\n";
					$html .= "        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto".$i."\" value=\"".$dtl['codigo_producto']."\">";
					$html .= "        <input type=\"hidden\" name=\"empresa_id\" id=\"codigo_producto".$i."\" value=\"".$empresa['empresa_id']."\">";
					$html .= "      <td align=\"left\">".$dtl['descripcion']."</td>\n";
					$html .= "      <td align=\"center\">".$dtl['precio']."</td>\n";
					$html .= "        <input type=\"hidden\" name=\"precio\" id=\"precio".$i."\" value=\"".$dtl['precio']."\">";
					$html .= "      <td align=\"left\">".$dtl['valor_pactado']."</td>\n";
					$html .= "        <input type=\"hidden\" name=\"valor_pactado\" id=\"valor_pactado".$i."\" value=\"".$dtl['valor_pactado']."\">";
					$html .= "      <td align=\"left\">".$dtl['valor_porcentaje']."</td>\n";
					$html .= "        <input type=\"hidden\" name=\"valor_porcentaje\" id=\"valor_porcentaje".$i."\" value=\"".$dtl['valor_porcentaje']."\">";
					$html .= "      <td align=\"left\">".$dtl['valor_total_pactado']."</td>\n";
					$html .= "        <input type=\"hidden\" name=\"valor_total_pactado\" id=\"valor_total_pactado".$i."\" value=\"".$dtl['valor_total_pactado']."\">";
					$html .=" <td width=\"2%\"> <input type=\"checkbox\" name=\"checkseleccionar\" id=\"checkseleccionar".$i."\" value=\"".$i."\"  checked  disabled )\">";       
					$html .= " </td>\n";
					$i=$i+1; 
			    }
			}

			$html .= "</table>\n";
			$html .= "</form>";
			$html .= "  <table  align=\"CENTER\">";
			$html .= "		<tr>\n";
			$html .= "			<td    >\n";
			$html .= "			<input class=\"input-submit\" type=\"button\" name=\"Guardar\" value=\"OK\"     onClick=\"xajax_ValidarTodosLosDatosProductos(".$i.",'".$contratov."','".$empresa."','".$contratacion_prod_id."')\">\n";
			$html .= "	  	</td>\n";
			$html .= "		</tr>\n";  
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
        }
	 
	   /**
      * Funcion forma que permite consultar el contrato final
	  * @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
       
        function FormaConsultarContrato($dcontrato,$datosproductos,$action,$empresa,$datospolitica,$datoenv)
		{
   
				$html  = ThemeAbrirTabla("INFORMACIÒN DEL CONTRATO - CONSULTAR");  
				$ctl = AutoCarga::factory("ClaseUtil"); 
				$html .= $ctl->IsDate("-");
				$html .= $ctl->AcceptDate("-");
				$html .= $ctl->LimpiarCampos();
				           
				if(!empty($empresa))
				{
					$html .= "<form name=\"FormaModificarContrato\" id=\"FormaModificarContrato\" action=\"".$action['actualiza']."\"  method=\"post\" >\n";
					$html .= "<table  width=\"85%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
					$html .= "  <tr align=\"center\" >\n";
					$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> INFORMACIÒN DE LA EMPRESA (contratista):</b> </td>\n";
					$html .= "  </tr>\n";
					$html .= "  <tr  class=\"modulo_table_list_title\" >\n";
					$html .= "     <input type=\"hidden\" name=\"contratosusti\" id=\"contratosusti\" value=\"".$scontrato."\">\n";
					$html .= "     <input type=\"hidden\" name=\"empresa\" id=\"empresa\" value=\"".$empresa['empresa_id']."\">\n";
					$html .= "      </td>\n";
					$html .= "      <td   width=\"10%\"   align=\"left\"> IDENTIFICACION: </td>\n";
					$html .= "      <td   width=\"20%\"  class=\"modulo_list_claro\"  align=\"left\">".$empresa['tipo_id_tercero']." ".$empresa['id']."\n";
					$html .= "      </td>\n";
					$html .= "      <td   width=\"20%\"   align=\"left\">RAZON SOCIAL :</td>\n";
					$html .= "      <td   colspan=\"5\" class=\"modulo_list_claro\" align=\"left\">".$empresa['razon_social']."\n";
					$html .= "      </td>\n";
					$html .= " </tr>\n";

					$html .= " <tr class=\"modulo_table_list_title\" >\n";
					$html .= "      <td  align=\"left\">CODIGO:\n";
					$html .= "      </td>\n";
					$html .= "      <td class=\"modulo_list_claro\" colspan=\"1\" align=\"left\">".$empresa['codigo_sgsss']."\n";
					$html .= "      </td>\n";
					$html .= "      <td align=\"left\" >DEPARTAMENTO:\n";
					$html .= "      </td>\n";
					$html .= "      <td class=\"modulo_list_claro\" colspan=\"1\" align=\"left\">".$empresa['departamento']."\n";
					$html .= "      </td>\n";
					$html .= "      <td  align=\"left\" >MUNICIPIO:\n";
					$html .= "      </td>\n";
					$html .= "      <td class=\"modulo_list_claro\" colspan=\"2\" align=\"center\" >".$empresa['municipio']."\n";
					$html .= "      </td>\n";
					$html .= " </tr>\n";
					$html .= " <tr  class=\"modulo_table_list_title\" >\n";
					$html .= "      <td  width=\"10%\"  align=\"left\"> DIRECCIÒN: </td>\n";
					$html .= "      <td   width=\"15%\" class=\"modulo_list_claro\"  align=\"left\">".$empresa['direccion']." \n";
					$html .= "      </td>\n";
					$html .= "      <td  width=\"10%\" align=\"left\"> TELEFONOS: </td>\n";
					$html .= "      <td    width=\"20%\" class=\"modulo_list_claro \"  align=\"left\">".$empresa['telefonos']." \n";
					$html .= "      </td>\n";
					$html .= "      <td  width=\"10%\"  align=\"left\"> FAX: </td>\n";
					$html .= "      <td    colspan=\"4\" class=\"modulo_list_claro\"  align=\"left\">".$empresa['fax']." \n";
					$html .= "      </td>\n";
					$html .= " </tr>\n";
                }
               if(!empty($dcontrato))
                {
                   foreach($dcontrato as $indice => $valor)
                   {
          
						$html .= "  <tr align=\"center\" >\n";
						$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> INFORMACIÒN DEL PROVEEDOR:</b> </td>\n";
						$html .= "  </tr>\n";
						$html .= " <tr  class=\"modulo_table_list_title\" >\n";
						$html .= "       <td   width=\"10%\"  align=\"left\"> IDENTIFICACION: </td>\n";
						$html .= "       <td   width=\"20%\"  class=\"modulo_list_claro\"  align=\"left\">".$valor['tipo_id_tercero']." ".$valor['tercero_id']."\n";
						$html .= "    <input type=\"hidden\" name=\"tipo_id_tercero\" id=\"tipo_id_tercero\" value=\"".$valor['tipo_id_tercero']."\">\n";
						$html .= "    <input type=\"hidden\" name=\"tercero_id\" id=\"tercero_id\" value=\"".$valor['tercero_id']."\">\n";
						$html .= "       </td>\n";
						$html .= "       <td   width=\"20%\"   align=\"left\">NOMBRE:</td>\n";
						$html .= "       <td    colspan=\"3\" class=\"modulo_list_claro\" align=\"left\">".$valor['nombre_tercero']."\n";
						$html .= "       </td>\n";
						$html .= " </tr>\n";
						$html .= "     <tr class=\"modulo_table_list_title\" >\n";
						$html .= "       <td width=\"20%\" align=\"left\" > DIRECCIÒN: </td>\n";
						$html .= "       <td colspan=\"5\" class=\"modulo_list_claro\"  >".$valor['direccion']." \n";
						$html .= "       </td>\n";   
						$html .= "     </tr>\n";
						$html .= "     <tr   class=\"modulo_table_list_title\" >\n";
						$html .= "       <td width=\"35%\" align=\"left\"> TELEFONOS: </td>\n";
						$html .= "       <td class=\"modulo_list_claro\" colspan=\"2\">".$valor['telefonos']." ".$valor['celular']." \n";
						$html .= "       </td>\n";
						$html .= "       <td width=\"5%\" align=\"left\" > FAX: </td>\n";
						$html .= "       <td colspan=\"2\" class=\"modulo_list_claro\" >".$valor['fax']." \n";
						$html .= "       </td>\n";
						$html .= "  </tr>\n";
						$html .= "  <tr class=\"modulo_table_list_title\">\n";
						$html .= "      <td width=\"40%\"   align=\"left\"> GERENTE: </td>\n";
						$html .= "       <td  colspan=\"3\" class=\"modulo_list_claro\"  align=\"left\">".$valor['nombre_gerente']." \n";
						$html .= "       </td>\n";
						$html .= "       <td width=\"25%\"  align=\"left\"> TELEFONO: </td>\n";
						$html .= "       <td class=\"modulo_list_claro\"  align=\"left\">".$valor['telefono_gerente']." \n";
						$html .= "       </td>\n";
						$html .= "  </tr>\n";
						$html .= "  <tr align=\"center\" >\n";
						$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> INFORMACIÒN DEL CONTRATO:</b> </td>\n";
						$html .= "  </tr>\n";
						$html .= "  <tr class=\"modulo_table_list_title\">\n";
						$html .= "      <td  align=\"left\">No CONTRATO:</td>\n";
						$html .= "      <td colspan=\"5\" align=\"left\"  class=\"modulo_list_claro\"> ".$valor['no_contrato']."\n";
						$html .= "      </td>\n";
						$html .= "  </tr>\n";
						$html .= "  <tr class=\"modulo_table_list_title\">\n";
						$html .= "				<td   width=\"30%\"  align=\"left\" >FECHA INICIO :</td>\n";
						$html .= "		  	<td  width=\"15%\" class=\"modulo_list_claro\" align=\"left\">".$valor['fecha_inicio']."\n";
						$html .= "			  </td>\n";
						$html .= "				<td  align=\"left\" >FECHA VENC. :</td>\n";
						$html .= "		  	<td  colspan=\"3\" class=\"modulo_list_claro\" align=\"left\">".$valor['fecha_vencimiento']."	</td>\n";
						$html .= "  </tr >\n";

						$html .= "  <tr class=\"modulo_table_list_title\">\n";
						$html .= "      <td  align=\"left\"> DESCRIPCIÒN DEL CONTRATO:</td>\n";
						$html .= "      <td   colspan=\"5\"  align=\"left\" class=\"modulo_list_claro\">".$valor['descripcion']."\n";
						$html .= "       </td>\n";
						$html .= "  </tr >\n";

						$html .= "  <tr class=\"modulo_table_list_title\">\n";
						$html .= "      <td  align=\"left\"> CONDICIONES TIEMPO DE ENTREGA:</td>\n";
						$html .= "      <td   colspan=\"5\"  align=\"left\" class=\"modulo_list_claro\">".$valor['condiciones_entrega']."\n";
						$html .= "       </td>\n";
						$html .= "  </tr >\n";
						$html .= "  <tr class=\"modulo_table_list_title\">\n";
						$html .= "      <td  align=\"left\" >OBSERVACIÒN:</td>\n";
						$html .= "      <td   colspan=\"5\"  align=\"left\" class=\"modulo_list_claro\"> ".$valor['observaciones']."\n";
						$html .= "      </td>\n";
						$html .= "  </tr >\n";
					} 
				}
     
			$html .= "  <tr align=\"center\" >\n";
			$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> DIAS DE ENVIO SEGUN SU CLASIFICACIÒN:</b> </td>\n";
			$html .= "  </tr>\n";
            if(!empty($datoenv))
			{  
				$html .= "	  <tr align=\"CENTER\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td colspan=\"2\">PRODUCTOS CLASIFICACION </td>\n";
				$html .= "      <td colspan=\"4\" >DIAS DE ENVIO </td>\n";
				$html .= "  </tr>\n";
           
				foreach($datoenv as $indice => $d)
				{
				  
				   $html .= "	  <tr align=\"CENTER\">\n";   
				   $html .= "      <td   class=\"modulo_list_claro\" colspan=\"2\"  align=\"center\">".$d['descripcion']."</td>\n";
				   $html .= "      <td   class=\"modulo_list_claro\" colspan=\"4\" align=\"center\">".$d['dias_envio']."</td>\n";
				   $html .= "  </tr>\n";
				}
          
			}
         
			$html .= "  <tr align=\"center\" >\n";
			$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> POLITICAS DE VENCIMIENTO:</b> </td>\n";
			$html .= "  </tr>\n";
			if(!empty($datospolitica))
			{  
				$html .= "	  <tr align=\"CENTER\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td colspan=\"2\">CLASIFICACION PRODUCTOS </td>\n";
				$html .= "      <td  colspan=\"4\">POLITICA</td>\n";

				$html .= "  </tr>\n";
		  
				foreach($datospolitica as $indice => $dt)
				{
				  $html .= "  <tr class=\"modulo_list_claro\">\n";
				  $html .= "      <td  colspan=\"2\" align=\"center\"><B>".$dt['descripcion']."</B></td>\n";
				  $html .= "        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto".$i."\" value=\"".$dt['politica_descripcion']."\">";
				  $html .= "      <td colspan=\"4\"  align=\"left\">".$dt['politica_descripcion']."</td>\n";
						  
				}
			}
			$html .= "  <tr align=\"center\" >\n";
			$html .= "      <td  class=\"formulacion_table_list\" colspan=\"8\" > <b> PRODUCTOS-CONTRATO:</b> </td>\n";
			$html .= "  </tr>\n";
            if(!empty($datosproductos))
			{  
		
				$html .= "	  <tr align=\"CENTER\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td >CODIGO</td>\n";
				$html .= "      <td colspan=\"2\">DESCRIPCION</td>\n";
				$html .= "      <td >CLASIFICACIÒN</td>\n";
				$html .= "      <td colspan=\"3\">VALOR TOTAL PACTADO</td>\n";
				$html .= "  </tr>\n";
      
				foreach($datosproductos as $indice => $dtl)
				{
				  $html .= "  <tr class=\"modulo_list_claro\">\n";
				  $html .= "      <td  align=\"center\">".$dtl['codigo_producto']."</td>\n";
				  $html .= "        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto".$i."\" value=\"".$dtl['codigo_producto']."\">";
				  $html .= "        <input type=\"hidden\" name=\"empresa_id\" id=\"codigo_producto".$i."\" value=\"".$empresa['empresa_id']."\">";
				  $html .= "      <td colspan=\"2\"  align=\"left\">".$dtl['descripcion']."</td>\n";
				  $html .= "      <td  align=\"left\">".$dtl['tipodescripcion']."</td>\n";
				  $html .= "      <td colspan=\"3\" align=\"left\">".$dtl['valor_total_pactado']."</td>\n";
						 
				}
			}
              
			$html .= "</table>\n";
			$html .= "</form>";
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
	/**
      * Funcion forma que listar todos los productos contratados con un proveedor
        * @param array $action vector que contiene los link de la aplicacion
       * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
  
		function FormaBuscaryListarProductoContrato($contrato_id,$contratosus,$action,$contratacion_prod_id,$empresa,$conteo,$pagina)
		{
			$html  = ThemeAbrirTabla("LISTA DE PRODUCTOS ASOCIADOS AL CONTRATO");  
			$ctl = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->LimpiarCampos();
			$html .= "<form name=\"Forma11\" id=\"Forma11\"  action=\"".$action['buscador']."\"   method=\"post\" >\n";
			$html .= "			<table   width=\"40%\" align=\"center\" border=\"0\"  >";
			$html .= "		<tr>\n";
			$html .= "			<td  width=\"40%\" class=\"modulo_table_list_title\">CODIGO:</td>\n";
			$html .= "	    <td class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "     <input type=\"text\" class=\"input-text\" name=\"buscador[codigo_producto]\" maxlength=\"32\" value=".$request['codigo_producto']."></td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr>\n";
			$html .= "			<td class=\"modulo_table_list_title\">CODIGO ALTERNO:</td>\n";
			$html .= "			<td  colspan=\"4\" class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\" name=\"buscador[codigo_alterno]\" maxlength=\"32\" value=".$request['codigo_alterno']."></td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr>\n";
			$html .= "			<td class=\"modulo_table_list_title\">DESCRIPCIÒN:</td>\n";
			$html .= "			<td  colspan=\"4\" class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\" name=\"buscador[descripcion]\" maxlength=\"32\" value=".$request['descripcion']."></td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr>\n";
			$html .= "	   	<td align='center'>\n";
			$html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "			</td>\n";
			$html .= "			<td align='center' colspan=\"1\">\n";
			$html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.Forma11)\" value=\"Limpiar Campos\">\n";
			$html .= "	  	</td>\n";
			$html .= "		</tr>\n";
			$html .= "</table><br>\n";

			if(!empty($contrato_id))
			{  
        $pghtml = AutoCarga::factory('ClaseHTML');
				$html .= "<table  width=\"100%\" align=\"center\" border=\"0\"  class=\"modulo_table_list\" >\n";
				$html .= "  <tr class=\"modulo_table_list_title\" align=\"center\" >\n";
				$html .= "      <td   colspan=\"8\" > <b> PRODUCTOS - CONTRATO:</b> </td>\n";
				$html .= "  </tr>\n";

				$html .= "	  <tr align=\"CENTER\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td width=\"15%\">MOLECULA</td>\n";
				$html .= "      <td width=\"15%\">CODIGO</td>\n";
				$html .= "      <td width=\"45%\">DESCRIPCION</td>\n";
				$html .= "      <td width=\"10%\">VALOR</td>\n";
				$html .= "	      <td  width=\"5%\"  >";
				$html .= "	     ELI. </td>";
				$html .= "  </tr>\n";
				$i=0;
				foreach($contrato_id as $indice => $dtl)
				{
					$html .= "  <tr class=\"modulo_list_claro\">\n";
					$html .= "      <td align=\"center\"><b>".$dtl['molecula']."</b></td>\n";
					$html .= "      <td align=\"center\"><b>".$dtl['codigo_producto']."</b></td>\n";
					$html .= "        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto".$i."\" value=\"".$dtl['codigo_producto']."\">";
					$html .= "        <input type=\"hidden\" name=\"empresa_id\" id=\"codigo_producto".$i."\" value=\"".$empresa['empresa_id']."\">";
					$html .= "      <td align=\"left\"><b>".$dtl['descripcion']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']." -".$dtl['laboratorio']."</b></td>\n";
					$html .= "      <td align=\"left\"><b>".round($dtl['valor_total_pactado'])."</b></td>\n";
					$html .= "        <input type=\"hidden\" name=\"valor_total_pactado\" id=\"valor_total_pactado".$i."\" value=\"".$dtl['valor_total_pactado']."\">";
					$html .= "      <td align=\"center\">\n";
					$html .= "         <a href=\"#\" onclick=\"xajax_eliminar('".$dtl['codigo_producto']."', '".$contratacion_prod_id."','".$empresa."')\" class=\"label_error\"><img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\" title=\"Nro.Contrato\"></a>\n";
					$html .= "      </td>\n";
					$i=$i+1; 
					$html .= "		</tr>\n";
				}
        $html .= "	</table><br>\n";  
        $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
        $html .= "	<br>\n";
			} else
			{
				if($request)
					$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['otroproducto']."\"  class=\"label_error\">\n";
			$html .= "        CONTRATAR OTRO PRODUCTOS\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			
			
			$html .= "</form>";
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
    /**
      * Funcion forma que permite listar las farmacias
        * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
	
		function FormaBuscarEmpresas($action,$TipoIdE,$request,$datos,$conteo,$pagina)                             
		{
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
			$html .= " function RedireccionarInformacion(frm,farmacia_id) ";
			$html .= " { ";
			$html .= "  if(frm.empresas.selectedIndex== 0)";
			$html .= "   {";
			$html .= "  alert('SELECCIONE LA BODEGA PRINCIPAL');";
			$html .= "      return;\n";
			$html .= "   }";
			$html .= "   if(frm.empresas.selectedIndex!=0)";
			$html .= "   {";
			$html .= " 	xajax_TranDatosEmpresa(frm.empresas.value,farmacia_id);";
			$html .= "      return;\n";
			$html .= "  }";
			$html .= "  }";
			$html .="  </script>\n";
			$html .= ThemeAbrirTabla('SELECCION - FARMACIA');
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "  <legend class=\"normal_10AN\" align=\"center\">BUSCAR  FARMACIAS </legend>\n";
			$html .= "		<form name=\"formabuscarE\" action=\"".$action['buscador']."\" method=\"post\">";
			$html .= "			<table   width=\"45%\" align=\"center\"  class=\"modulo_table_list\" border=\"0\"  >";
			$html .= "   <tr class=\"modulo_table_list_title\"> \n";
			$html .= "			<td align=\"center\"  ><b> TIPO DOCUMENTO:</B></td>\n";
			$html .= "			<td  align=\"LEFT\"  class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "					<select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
			$html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
				foreach($TipoIdE as $indice => $valor)
				{
					if($valor['tipo_id_tercero']==$request['tipo_id_tercero'])
					$sel = "selected";
					else   $sel = "";
					$html .= "  <option value=\"".$valor['tipo_id_tercero']."\" ".$sel.">".$valor['descripcion']."</option>\n";
				}
		    $html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html .= "	 </tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td  align=\"center\" ><b>DOCUMENTO:</B></td>\n";
			$html .= "	    <td align=\"LEFT\" class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "     <input type=\"text\" class=\"input-text\" name=\"buscador[id]\" size=\"45\"  maxlength=\"32\" value=".$request['id']."></td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td  align=\"center\"  ><b>CODIGO:</B></td>\n";
			$html .= "	    <td  align=\"LEFT\" class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "     <input type=\"text\" class=\"input-text\" name=\"buscador[codigo]\" size=\"45\"  maxlength=\"32\" value=".$request['id']."></td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td align=\"center\" ><b> RAZON SOCIAL:</B></td>\n";
			$html .= "			<td  align=\"LEFT\"  colspan=\"4\" class=\"modulo_list_claro\" ><input type=\"text\"  size=\"45\" class=\"input-text\" name=\"buscador[razon_social]\" maxlength=\"32\" value=".$request['razon_social']."></td>\n";
			$html .= "		</tr>\n";
			$html .= "</table><br>\n";
			$html .= "			<table   width=\"40%\"  class=\"normal_10AN\" align=\"center\" border=\"0\"  >";
			$html .= "		<tr>\n";
			$html .= "	   	<td align='center'>\n";
			$html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "			</td>\n";
			$html .= "			<td align='center' colspan=\"1\">\n";
			$html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formabuscarE)\" value=\"Limpiar Campos\">\n";
			$html .= "	  	</td>\n";
			$html .= "				    </form>\n";
			$html .= "			</tr>\n";
			$html .= "</table><br>\n";
			if(!empty($datos))
			{
				$pghtml = AutoCarga::factory('ClaseHTML');
				$html .= "  <table width=\"95%\"  class=\"modulo_table_list\"  border=\"0\"  align=\"center\">";
				$html .= "	  <tr align='center'  class=\"modulo_table_list_title\" >\n";
				$html .= "      <td width=\"15%\">ID</td>\n";
				$html .= "      <td width=\"45%\">RAZON SOCIAL</td>\n";
				$html .= "      <td  width=\"25%\" >REPRESENTANTE</td>\n";
				$html .= "      <td width=\"18%\">DIRECCIÒN</td>\n";
				$html .= "      <td width=\"10%\">TELEFONOS</td>\n";
				$html .= "      <td width=\"5%\">OP</td>\n";
				$html .= "  </tr>\n";
				$est = "modulo_list_claro"; $back = "#DDDDDD";
				foreach($datos as $key => $dtl)
				{
					$html .= "  <tr class=\"modulo_list_claro\">\n";
					$html .= "      <td align=\"center\"><b>".$dtl['tipo_id_tercero']." ".$dtl['id']."</b></td>\n";
					$html .= "      <td align=\"left\"><b>".$dtl['razon_social']."</b></td>\n";
					$html .= "     <input type=\"hidden\" name=\"empresa\" id=\"empresa\" value=\"".$dtl['empresa_id']."\">\n";
					$html .= "      <td align=\"left\"><b>".$dtl['representante_legal']."</b></td>\n";
					$html .= "      <td align=\"left\"><b>".$dtl['direccion']."</b></td>\n";
					$html .= "      <td align=\"left\"><b>".$dtl['telefonos']."</b></td>\n";
					$html .= "      <td align=\"center\">\n";
                    $html .= " <a href=\"#\" onclick=\"xajax_Bodegas('".$dtl['empresa_id']."')\"  class=\"label_error\"> ";
			        $html .= "         <img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\" title=\"Informacion\"></a>\n";
			        $html .= "      </td>\n";
					$html .= "  </tr>\n";
					
				}
				$html .= "	</table><br>\n";
				$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
				$html .= "	<br>\n";
			}
			else
			{
			if($request)
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			$html .= "</fieldset><br>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= $this->CrearVentana(560,"EMPRESA");
			$html .= ThemeCerrarTabla();
			return $html;
		}
	   /**
      * Funcion forma que permite realizar  la asociacion
        * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
		
		function  FormaRealizarAsociacion($action,$datos,$planes,$empresa_plan)
		{
		
			$html .= ThemeAbrirTabla('PARAMETRIZACION DE LOS PLANES ASOCIADOS A LA BODEGA DE LA FARMACIA');
			$html .= "<fieldset class=\"fieldset\">\n";
			$html .= "		<form name=\"formaSeleccPlan\"  method=\"post\">";
			$html .= "<table align=\"center\" width=\"55%\" class=\"modulo_table_list\" border=\"0\">\n";
			$html .= "  <tr  class=\"modulo_table_list_title\" align=\"center\" >\n";
			$html .= "      <td  colspan=\"10\" > <b>FARMACIA:</b> ";
			$html .= " <td class=\"modulo_list_claro\"> ".$datos[0]['razon_social']." </b> </td>\n";
			$html .= " <br>";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_table_list_title\" align=\"center\" >\n";
			$html .= "      <td  colspan=\"10\" > <b>CENTRO UTILIDAD:</td> ";
            $html .= "      <td class=\"modulo_list_claro\"> ".$datos[0]['centro']."</b> </td>\n";
			$html .= " <br>";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_table_list_title\" align=\"center\" >\n";
			$html .= "      <td colspan=\"10\" ><b>BODEGA:</td>";
            $html .= " <td class=\"modulo_list_claro\" >  ".$datos[0]['descripcion']." </b> </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= " <br>";
			$html .= "			<table  class=\"modulo_table_list\" width=\"35%\" align=\"center\" border=\"0\"  >";
			$html .= "   <tr> \n";
			$html .= "			<td class=\"modulo_table_list_title\" align=\"center\"  class=\"normal_10AN\"><b> PLANES:</B></td>\n";
			$html .= "			<td  class=\"modulo_list_claro\" colspan=\"4\">\n";
			$html .= "					<select name=\"plan\" class=\"select\" onchange=\"xajax_AsociarPlan(document.formaSeleccPlan.plan.value,'".$datos[0]['empresa_id']."','".$datos[0]['centro_utilidad']."','".$datos[0]['bodega']."','".$empresa_plan."');\">\n";
			$html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
				foreach($planes as $indice => $valor)
				{
					if($valor['plan_id']==$request['plan_id'])
					$sel = "selected";
					else   $sel = "";
					$html .= "  <option value=\"".$valor['plan_id']."\" ".$sel.">".$valor['plan_descripcion']."</option>\n";
				}
        $html .= "                </select>\n";
        $html .= "						  </td>\n";
        $html .= "	 </tr>\n";
        $html .= "</table><br>\n";

		$html .= "      <script>\n";
		$html .= "        xajax_ConsultarPlanesAsociados('".$datos[0]['empresa_id']."','".$datos[0]['centro_utilidad']."','".$datos[0]['bodega']."','".$empresa_plan."');\n";
		$html .= "      </script>\n";
		
        $html .= "  <table width=\"60%\"  border=\"0\"  align=\"center\">";
        $html .= "  <tr   class=\"modulo_list_claro\">\n";
        $html .= "      <td colspan=\"12\"><a> <div id=\"plan_id_c\"></div></td>\n";
        $html .= "  </tr>\n";
        $html .= "	</table>\n";
        $html .= "	  </form>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "</fieldset><br>\n";

        $html .= ThemeCerrarTabla();
			return $html;
		}
	/**
      * Funcion forma que permite acceder a las politicas de vencimiento del contrato
	 * @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
    	function FormaMenuPolticas($action,$datos,$datos2)
	    {
			$html  = ThemeAbrirTabla('POLITICAS DE VENCIMIENTO');
			$html .= "<table width=\"60%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\">MENU\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
	        foreach($datos as $key => $dtl)
			{
		        $html .= "  <tr class=\"modulo_list_claro\">\n";
		        $html .= "      <td align=\"center\">\n";
		        $html .= "        <a href=\"#".$dtl['tipo_producto_id']."\" onclick=\"xajax_parametrizarInformacion('".$dtl['tipo_producto_id']."','".$dtl['descripcion']."')\" class=\"label_error\">".$dtl['descripcion']."</a>\n";
		        $html .= "     </td>\n";
		        $html .= "  </tr>\n";
		        $html .= "  <tr class=\"modulo_list_claro\">\n";
		        $html .= "      <td colspan=\"25\"><a name=\"#".$dtl['tipo_producto_id']."\"> <div id=\"Polticas".$dtl['tipo_producto_id']."\"></div></td>\n";
		        $html .= "  </tr>\n";
	                
	        }
         $html .= "</table>\n";
	       $html .= "<table align=\"center\">\n";
	       $html .= "<br>";
	       $html .= "  <tr>\n";
	       $html .= "      <td align=\"center\" class=\"label_error\">\n";
	       $html .= "        <a href=\"".$action['continuar']."\">CONTINUAR</a>\n";
	       $html .= "      </td>\n";
	       $html .= "  </tr>\n";
	       $html .= "</table>\n";
	       $html .= $this->CrearVentana();
	       $html .= ThemeCerrarTabla();
	       return $html;
	    }
	  /**
      * Funcion forma que permite  acceder al menu de dias de envio del contrato
	  * @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
      
		function FormaMenuDias($action,$datos)
        {
			$html  = ThemeAbrirTabla('DIAS DE ENVIO');
			$html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\">MENU\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			foreach($datos as $key => $dtl)
			{
				$html .= "  <tr class=\"modulo_list_claro\">\n";
				$html .= "      <td align=\"center\">\n";
				$html .= "        <a href=\"#".$dtl['tipo_producto_id']."\" onclick=\"xajax_ParametrizarDiasEnvio('".$dtl['tipo_producto_id']."','".$dtl['descripcion']."')\" class=\"label_error\">".$dtl['descripcion']."</a>\n";
				$html .= "     </td>\n";
				$html .= "  </tr>\n";
				$html .= "  <tr class=\"modulo_list_claro\">\n";
				$html .= "      <td colspan=\"25\"><a name=\"#".$dtl['tipo_producto_id']."\"> <div id=\"dia".$dtl['tipo_producto_id']."\"></div></td>\n";
				$html .= "  </tr>\n";
			}
			$html .= "</table>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['continuar']."\">MENU PRINCIPAL</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= $this->CrearVentana();
			$html .= ThemeCerrarTabla();
			return $html;
		}
		   /**
      * Funcion que permite acceder a diferentes menus  para realizar modificacion a un contrato activo
	  * @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
      		
	function FormaMenuModificar($action)
	{
		$html  = ThemeAbrirTabla('MENU MODIFICACION');
		$html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
		$html .= "  <tr class=\"modulo_table_list_title\">\n";
		$html .= "     <td align=\"center\">MENU\n";
		$html .= "     </td>\n";
		$html .= "  </tr>\n";
		$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
		$html .= "      <td   class=\"label\" align=\"center\">\n";
		$html .= "        <a href=\"".$action['basico']."\">DATOS BASICOS CONTRATO</a>\n";
		$html .= "      </td>\n";
		$html .= "  </tr>\n";
		$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
		$html .= "      <td   class=\"label\" align=\"center\">\n";
		$html .= "        <a href=\"".$action['productos']."\">PRODUCTOS ASOCIADOS AL CONTRATO</a>\n";
		$html .= "      </td>\n";
		$html .= "  </tr>\n";
		$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
		$html .= "      <td   class=\"label\" align=\"center\">\n";
		$html .= "        <a href=\"".$action['politicas']."\">PARAMETROS POLITICA DE VENCIMIENTO</a>\n";
		$html .= "      </td>\n";
		$html .= "  </tr>\n";
		$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
		$html .= "      <td   class=\"label\" align=\"center\">\n";
		$html .= "        <a href=\"".$action['dias']."\">PARAMETROS DIAS DE VENCIMIENTO</a>\n";
		$html .= "      </td>\n";
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
	
	/**
      * Funcion donde se crea la Forma del Mensaje de aviso si se ha actualizado correctamente o no los datos del contrato.
      * @param array $action vector que contiene los link de la aplicacion.
      * @param string $msg1 Cadena con el texto del mensaje a mostrar  en pantalla.
      * @return string $html retorna la cadena con el codigo html de la pagina.
    */ 
		function FormaMensajeActatrato($action, $msg1=null,$msg1=null,$datos)
		{
			$html  = ThemeAbrirTabla("ACTUALIZACION  DEL CONTRATO");
			$html .= " <form name=\"form8\" method=\"post\" enctype=\"multipart/form-data\" action=\"".$action['productosco']."\"  >";
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$msg1."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>";
			$html .= " <br>";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= "</form>";
			$html .= ThemeCerrarTabla();
			return $html;
		}
		   /**
      * Funcion forma que permite consultar el contrato final y completo
	  * @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
    */ 
      
		function FormaConsultarContratoCompleto($dcontrato,$datosproductos,$action,$empresa,$datospolitica,$datoenv)
		{
   
			$html  = ThemeAbrirTabla("INFORMACIÒN DEL CONTRATO - CONSULTAR");  
			if(!empty($empresa))
			{
					$html .= "<form name=\"FormaModificarContrato\" id=\"FormaModificarContrato\" action=\"".$action['actualiza']."\"  method=\"post\" >\n";
					$html .= "<table  width=\"85%\" align=\"center\" border=\"0\"  class=\"modulo_table_list\" >\n";
					$html .= "  <tr align=\"center\" >\n";
					$html .= "      <td  class=\"modulo_table_list_title\" colspan=\"8\" > <b> INFORMACIÒN DE LA EMPRESA :</b> </td>\n";
					$html .= "  </tr>\n";
					$html .= "  <tr  class=\"modulo_table_list_title\" >\n";
					$html .= "      </td>\n";
					$html .= "      <td   width=\"10%\"   align=\"left\"> IDENTIFICACION: </td>\n";
					$html .= "      <td   width=\"20%\"  class=\"modulo_list_claro\"  align=\"left\">".$empresa['tipo_id_tercero']." ".$empresa['id']."\n";
					$html .= "      </td>\n";
					$html .= "      <td   width=\"20%\"   align=\"left\">RAZON SOCIAL :</td>\n";
					$html .= "      <td   colspan=\"5\" class=\"modulo_list_claro\" align=\"left\">".$empresa['razon_social']."\n";
					$html .= "      </td>\n";
					$html .= " </tr>\n";
					$html .= " <tr class=\"modulo_table_list_title\" >\n";
					$html .= "      <td  align=\"left\">CODIGO:\n";
					$html .= "      </td>\n";
					$html .= "      <td class=\"modulo_list_claro\" colspan=\"1\" align=\"left\">".$empresa['codigo_sgsss']."\n";
					$html .= "      </td>\n";
					$html .= "      <td align=\"left\" >DEPARTAMENTO:\n";
					$html .= "      </td>\n";
					$html .= "      <td class=\"modulo_list_claro\" colspan=\"1\" align=\"left\">".$empresa['departamento']."\n";
					$html .= "      </td>\n";
					$html .= "      <td  align=\"left\" >MUNICIPIO:\n";
					$html .= "      </td>\n";
					$html .= "      <td class=\"modulo_list_claro\" colspan=\"2\" align=\"center\" >".$empresa['municipio']."\n";
					$html .= "      </td>\n";
					$html .= " </tr>\n";
					$html .= " <tr  class=\"modulo_table_list_title\" >\n";
					$html .= "      <td  width=\"10%\"  align=\"left\"> DIRECCIÒN: </td>\n";
					$html .= "      <td   width=\"15%\" class=\"modulo_list_claro\"  align=\"left\">".$empresa['direccion']." \n";
					$html .= "      </td>\n";
					$html .= "      <td  width=\"10%\" align=\"left\"> TELEFONOS: </td>\n";
					$html .= "      <td    width=\"20%\" class=\"modulo_list_claro \"  align=\"left\">".$empresa['telefonos']." \n";
					$html .= "      </td>\n";
					$html .= "      <td  width=\"10%\"  align=\"left\"> FAX: </td>\n";
					$html .= "      <td    colspan=\"4\" class=\"modulo_list_claro\"  align=\"left\">".$empresa['fax']." \n";
					$html .= "      </td>\n";
					$html .= " </tr>\n";
                }
               if(!empty($dcontrato))
                {
                   foreach($dcontrato as $indice => $valor)
                   {
          
						$html .= "  <tr align=\"center\" >\n";
						$html .= "      <td  class=\"modulo_table_list_title\" colspan=\"8\" > <b> INFORMACIÒN DEL PROVEEDOR:</b> </td>\n";
						$html .= "  </tr>\n";
						$html .= " <tr  class=\"modulo_table_list_title\" >\n";
						$html .= "       <td   width=\"10%\"  align=\"left\"> IDENTIFICACION: </td>\n";
						$html .= "       <td   width=\"20%\"  class=\"modulo_list_claro\"  align=\"left\">".$valor['tipo_id_tercero']." ".$valor['tercero_id']."\n";
						$html .= "    <input type=\"hidden\" name=\"tipo_id_tercero\" id=\"tipo_id_tercero\" value=\"".$valor['tipo_id_tercero']."\">\n";
						$html .= "    <input type=\"hidden\" name=\"tercero_id\" id=\"tercero_id\" value=\"".$valor['tercero_id']."\">\n";
						$html .= "       </td>\n";
						$html .= "       <td   width=\"20%\"   align=\"left\">NOMBRE:</td>\n";
						$html .= "       <td    colspan=\"3\" class=\"modulo_list_claro\" align=\"left\">".$valor['nombre_tercero']."\n";
						$html .= "       </td>\n";
						$html .= " </tr>\n";
						$html .= "     <tr class=\"modulo_table_list_title\" >\n";
						$html .= "       <td width=\"20%\" align=\"left\" > DIRECCIÒN: </td>\n";
						$html .= "       <td colspan=\"5\" class=\"modulo_list_claro\"  >".$valor['direccion']." \n";
						$html .= "       </td>\n";   
						$html .= "     </tr>\n";
						$html .= "     <tr   class=\"modulo_table_list_title\" >\n";
						$html .= "       <td width=\"35%\" align=\"left\"> TELEFONOS: </td>\n";
						$html .= "       <td class=\"modulo_list_claro\" colspan=\"2\">".$valor['telefonos']." ".$valor['celular']." \n";
						$html .= "       </td>\n";
						$html .= "       <td width=\"5%\" align=\"left\" > FAX: </td>\n";
						$html .= "       <td colspan=\"2\" class=\"modulo_list_claro\" >".$valor['fax']." \n";
						$html .= "       </td>\n";
						$html .= "  </tr>\n";
						$html .= "  <tr class=\"modulo_table_list_title\">\n";
						$html .= "      <td width=\"40%\"   align=\"left\"> GERENTE: </td>\n";
						$html .= "       <td  colspan=\"3\" class=\"modulo_list_claro\"  align=\"left\">".$valor['nombre_gerente']." \n";
						$html .= "       </td>\n";
						$html .= "       <td width=\"25%\"  align=\"left\"> TELEFONO: </td>\n";
						$html .= "       <td class=\"modulo_list_claro\"  align=\"left\">".$valor['telefono_gerente']." \n";
						$html .= "       </td>\n";
						$html .= "  </tr>\n";
						$html .= "  <tr align=\"center\" >\n";
						$html .= "      <td  class=\"modulo_table_list_title\" colspan=\"8\" > <b> INFORMACIÒN DEL CONTRATO:</b> </td>\n";
						$html .= "  </tr>\n";
						$html .= "  <tr class=\"modulo_table_list_title\">\n";
						$html .= "      <td  align=\"left\">No CONTRATO:</td>\n";
						$html .= "      <td colspan=\"5\" align=\"left\"  class=\"modulo_list_claro\"> ".$valor['no_contrato']."\n";
						$html .= "      </td>\n";
						$html .= "  </tr>\n";
						$html .= "  <tr class=\"modulo_table_list_title\">\n";
						$html .= "				<td   width=\"30%\"  align=\"left\" >FECHA INICIO :</td>\n";
						$html .= "		  	<td  width=\"15%\" class=\"modulo_list_claro\" align=\"left\">".$valor['fecha_inicio']."\n";
						$html .= "			  </td>\n";
						$html .= "				<td  align=\"left\" >FECHA VENC. :</td>\n";
						$html .= "		  	<td  colspan=\"3\" class=\"modulo_list_claro\" align=\"left\">".$valor['fecha_vencimiento']."	</td>\n";
						$html .= "  </tr >\n";

						$html .= "  <tr class=\"modulo_table_list_title\">\n";
						$html .= "      <td  align=\"left\"> DESCRIPCIÒN DEL CONTRATO:</td>\n";
						$html .= "      <td   colspan=\"5\"  align=\"left\" class=\"modulo_list_claro\">".$valor['descripcion']."\n";
						$html .= "       </td>\n";
						$html .= "  </tr >\n";

						$html .= "  <tr class=\"modulo_table_list_title\">\n";
						$html .= "      <td  align=\"left\"> CONDICIONES TIEMPO DE ENTREGA:</td>\n";
						$html .= "      <td   colspan=\"5\"  align=\"left\" class=\"modulo_list_claro\">".$valor['condiciones_entrega']."\n";
						$html .= "       </td>\n";
						$html .= "  </tr >\n";
						$html .= "  <tr class=\"modulo_table_list_title\">\n";
						$html .= "      <td  align=\"left\" >OBSERVACIÒN:</td>\n";
						$html .= "      <td   colspan=\"5\"  align=\"left\" class=\"modulo_list_claro\"> ".$valor['observaciones']."\n";
						$html .= "      </td>\n";
						$html .= "  </tr >\n";
					} 
				}
     
			$html .= "  <tr align=\"center\" >\n";
			$html .= "      <td  class=\"modulo_table_list_title\" colspan=\"8\" > <b> DIAS DE ENVIO SEGUN SU CLASIFICACIÒN:</b> </td>\n";
			$html .= "  </tr>\n";
            if(!empty($datoenv))
			{  
				$html .= "	  <tr align=\"CENTER\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td colspan=\"2\">PRODUCTOS CLASIFICACION </td>\n";
				$html .= "      <td colspan=\"4\" >DIAS DE ENVIO </td>\n";
				$html .= "  </tr>\n";
           
				foreach($datoenv as $indice => $d)
				{
				  
				   $html .= "	  <tr align=\"CENTER\">\n";   
				   $html .= "      <td   class=\"modulo_list_claro\" colspan=\"2\"  align=\"center\">".$d['descripcion']."</td>\n";
				   $html .= "      <td   class=\"modulo_list_claro\" colspan=\"4\" align=\"center\">".$d['dias_envio']."</td>\n";
				   $html .= "  </tr>\n";
				}
          
			}
         
			$html .= "  <tr align=\"center\" >\n";
			$html .= "      <td  class=\"modulo_table_list_title\" colspan=\"8\" > <b> POLITICAS DE VENCIMIENTO:</b> </td>\n";
			$html .= "  </tr>\n";
			if(!empty($datospolitica))
			{  
				$html .= "	  <tr align=\"CENTER\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td colspan=\"2\">CLASIFICACION PRODUCTOS </td>\n";
				$html .= "      <td  colspan=\"4\">POLITICA</td>\n";

				$html .= "  </tr>\n";
		  
				foreach($datospolitica as $indice => $dt)
				{
				  $html .= "  <tr class=\"modulo_list_claro\">\n";
				  $html .= "      <td  colspan=\"2\" align=\"center\"><B>".$dt['descripcion']."</B></td>\n";
				  $html .= "        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto".$i."\" value=\"".$dt['politica_descripcion']."\">";
				  $html .= "      <td colspan=\"4\"  align=\"left\">".$dt['politica_descripcion']."</td>\n";
						  
				}
			}
			$html .= "  <tr align=\"center\" >\n";
			$html .= "      <td  class=\"modulo_table_list_title\" colspan=\"8\" > <b> PRODUCTOS-CONTRATO:</b> </td>\n";
			$html .= "  </tr>\n";
            if(!empty($datosproductos))
			{  
		
				$html .= "	  <tr align=\"CENTER\" class=\"modulo_table_list_title\" >\n";
       	$html .= "      <td >CODIGO</td>\n";
				$html .= "      <td colspan=\"2\">DESCRIPCION</td>\n";
				$html .= "      <td >CLASIFICACIÒN</td>\n";
				$html .= "      <td colspan=\"3\">VALOR TOTAL PACTADO</td>\n";
				$html .= "  </tr>\n";
      
				foreach($datosproductos as $indice => $dtl)
				{
				  $html .= "  <tr class=\"modulo_list_claro\">\n";
				  $html .= "      <td  align=\"center\"><b>".$dtl['codigo_producto']."</b></td>\n";
				  $html .= "        <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto".$i."\" value=\"".$dtl['codigo_producto']."\">";
				  $html .= "        <input type=\"hidden\" name=\"empresa_id\" id=\"codigo_producto".$i."\" value=\"".$empresa['empresa_id']."\">";
				  $html .= "      <td colspan=\"2\"  align=\"left\"><b>".$dtl['descripcion']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']."</b> </td>\n";
				  $html .= "      <td  align=\"left\"><b>".$dtl['tipodescripcion']."</b></td>\n";
				  $html .= "      <td colspan=\"3\" align=\"left\"><b>".$dtl['valor_total_pactado']."</b></td>\n";
						 
				}
			}
              
			$html .= "</table>\n";
			$html .= "</form>";
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
    /* Function donde se realiza la  forma para las empresas que tienen planes
           * @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
		
		*/
      function  FormaEmpresas_Planes($action,$datos)
	  {

		$html  = ThemeAbrirTabla('EMPRESA CON PLANES');
		$html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
		$html .= "  <tr class=\"modulo_table_list_title\">\n";
		$html .= "     <td align=\"center\">EMPRESAS\n";
		$html .= "     </td>\n";
		$html .= "  </tr>\n";
			foreach($datos as $key => $dtl)
			{
			
				$html .= "  <tr class=\"modulo_list_claro\">\n";
				$html .= "      <td align=\"center\">\n";
	            $html .= "        <a href=\"".$action['empresa_planes'].URLRequest(array("empresa_plane"=>$dtl['empresa_id']))."\">".$dtl['razon_social']."</a>\n";	
				$html .= "     </td>\n";
				$html .= "  </tr>\n";
				
			}
			$html .= "</table>\n";
		$html .= " <br>\n";
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