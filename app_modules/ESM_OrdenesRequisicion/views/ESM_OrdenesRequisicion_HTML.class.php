<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: ESM_OrdenesRequisicion_HTML.class.php
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra  Viviana Pantoja
  */

  /**
  * Clase Vista: Parametrizar_Medico_ESM_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra  Viviana Pantoja
  */

	class ESM_OrdenesRequisicion_HTML
	{
		/**
		* Constructor de la clase
		*/
		function ESM_OrdenesRequisicion_HTML(){}
		
    function Vista_OrdenesRequisicionTemporales($accion)
    {
    $ctl = AutoCarga::factory("ClaseUtil");
    $html .= "<script>";
      $html .= " function Paginador(offset)";
      $html .= " { ";
      $html .= "  xajax_Listado_Temporales(offset);";
      $html .= " } ";
      $html .= "</script>";
      
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
      $html .= "</script>\n";
    $html .= $ctl->AcceptNum(false);
    $html .= ThemeAbrirTabla('CREACION DE ORDENES DE REQUISICION');
    if($_REQUEST['datos']['ssiid']=='0')
    {
    $html .= "<center>";
    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr class=\"modulo_table_list_title\">";
    $html .= "      <td colspan=\"2\">";
    $html .= "      CREACION DE ORDENES DE REQUISICION";
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td width=\"80%\" >";
    $html .= "      CREAR ORDEN DE REQUISICION";
    $html .= "      </td>";
    $html .= "      <td align=\"center\">";
    $html .= "      <a title=\"Crear Orden De Requisicion\" href=\"".$accion['opcion1']."\">";
    $html .= "          <img title=\"CREAR ORDEN DE REQUISICION TEMPORAL\" src=\"".GetThemePath()."/images/pplan.png\" border=\"0\">\n";
    $html .= "      </a>";
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "  </table>\n";
    $html .= "</center>";
    }
    $html .= "<br>";
    if($_REQUEST['datos']['ssiid']=='1')
    {
    $html .= " <div id=\"Temporales\"></div>";
    $html .= "";
    $html .= "";
    $html .= "<script>";
    $html .= " xajax_Listado_Temporales();";
    $html .= "</script>";
    }
    
    $html .= "<form name=\"forma\" action=\"".$accion['volver']."\" method=\"post\">\n";
    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    $html .= "</form>\n";
    $html .= ThemeCerrarTabla();
    return $html;
    }
    
     function Vista_FormularioNuevoDoc($accion,$ESM,$TiposFuerzas,$TiposRequisiciones,$CentrosUtilidad)
    {
    $html .= " <script>";
    $html .= "  function Validar(Formulario)";
    $html .= "  {";
    $html .= "  if(Formulario.esm.value==\"\")";
    $html .= "   return false;";
    
    $html .= "  if(Formulario.tipo_fuerza_id.value==\"\")";
    $html .= "   return false;";
    
    $html .= "  if(Formulario.tipo_orden_requisicion.value==\"\")";
    $html .= "   return false;";
    
    $html .= "  if(Formulario.esm.value==\"\")";
    $html .= "   return false;";
    
    $html .= "  var variable =new String(Formulario.tipo_orden_requisicion.value).split(\"@\");";
    $html .= "  ";
    $html .= "  if(\"T\"==variable[1])";
    $html .= "      if(Formulario.centro_utilidad.value == \"\" || Formulario.bodega.value==\"\" ) ";
    $html .= "      {";
    $html .= "      alert(\"Debido a que el Tipo de Requisicion es traslado, es necesario seleccionar El centro de Utilidad y Bodega Satelite\");";
    $html .= "      return false;";
    $html .= "      }";

    $html .= " document.FormularioCabecera.submit();";
    $html .= " }";
    $html .= " </script>";
    
    $html .= ThemeAbrirTabla('CREACION DE ORDENES DE REQUISICION',"50%");
    
    $select_esm = "<select name=\"esm\" id=\"esm\" class=\"select\" style=\"width:100%;\">";
    $select_esm .= "<option value=\"\">-- SELECCIONAR --</option>";
    foreach($ESM as $k => $v)
    {
    $select_esm .= "<option value=\"".$v['tipo_id_tercero']."@".$v['tercero_id']."\">".$v['identificacion']."-".$v['nombre_tercero']."</option>";
    }
    $select_esm .= "</select>";
    
    $select_fuerza = "<select name=\"tipo_fuerza_id\" id=\"tipo_fuerza_id\" class=\"select\" style=\"width:100%;\">";
    $select_fuerza .= "<option value=\"\">-- SELECCIONAR --</option>";
    foreach($TiposFuerzas as $k => $v)
    {
    $select_fuerza .= "<option value=\"".$v['tipo_fuerza_id']."\">".$v['descripcion']."</option>";
    }
    $select_fuerza .= "</select>";
    
    $select_orequisicion = "<select name=\"tipo_orden_requisicion\" id=\"tipo_orden_requisicion\" class=\"select\" style=\"width:100%;\">";
    $select_orequisicion .= "<option value=\"\">-- SELECCIONAR --</option>";
    foreach($TiposRequisiciones as $k => $v)
    {
    $select_orequisicion .= "<option value=\"".$v['tipo_orden_requisicion']."@".$v['movimiento']."\">".$v['descripcion_orden_requisicion']."</option>";
    }
    $select_orequisicion .= "</select>";
    
    $select_centroutilidad = "<select onchange=\"xajax_Listado_Bodegas('".$_REQUEST['datos']['empresa_id']."',this.value)\" name=\"centro_utilidad\" id=\"centro_utilidad\" class=\"select\" style=\"width:100%;\">";
    $select_centroutilidad .= "<option value=\"\">-- SELECCIONAR --</option>";
    foreach($CentrosUtilidad as $k => $v)
    {
    $select_centroutilidad .= "<option value=\"".$v['centro_utilidad']."\">".$v['descripcion']."</option>";
    }
    $select_centroutilidad .= "</select>";
    
    $select_bodega = "<select name=\"bodega\" id=\"bodega\" class=\"select\" style=\"width:100%;\">";
    $select_bodega .= "<option value=\"\">-- SELECCIONAR --</option>";
    $select_bodega .= "</select>";
    $html .= "<center>";
    $html .= "  <form name=\"FormularioCabecera\" id=\"FormularioCabecera\" action=\"".$accion['Guardar']."\" method=\"POST\">";
    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" >\n";
    $html .= "    <tr class=\"modulo_table_list_title\">";
    $html .= "      <td colspan=\"2\">";
    $html .= "      CREACION DE ORDENES DE REQUISICION";
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      ESM SOLICITANTE";
    $html .= "      </td>";
    $html .= "      <td align=\"center\">";
    $html .= "          ".$select_esm;
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FUERZA";
    $html .= "      </td>";
    $html .= "      <td align=\"center\">";
    $html .= "          ".$select_fuerza;
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      TIPO DE REQUISICION ";
    $html .= "      </td>";
    $html .= "      <td align=\"center\">";
    $html .= "          ".$select_orequisicion;
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      CENTROS DE UTILIDAD";
    $html .= "      </td>";
    $html .= "      <td align=\"center\">";
    $html .= "          ".$select_centroutilidad;
    $html .= "      </td>";
    $html .= "    </tr>"; 
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      BODEGA SATELITE";
    $html .= "      </td>";
    $html .= "      <td align=\"center\">";
    $html .= "          ".$select_bodega;
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"2\">";
    $html .= "      OBSERVACIONES";
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"2\">";
    $html .= "      <textarea name=\"observacion\" id=\"observacion\" class=\"textarea\" style=\"width:100%\"></textarea>";
    $html .= "      </td>";
    $html .= "    </tr>";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"2\">";
    $html .= "      <input type=\"hidden\" value=\"".$_REQUEST['datos']['empresa_id']."\" name=\"datos[empresa_id]\" id=\"datos[empresa_id]\">";
    $html .= "      <input type=\"hidden\" value=\"".$_REQUEST['datos']['ssiid']."\" name=\"datos[ssiid]\" id=\"datos[ssiid]\">";
    $html .= "      <input type=\"button\" value=\"CREAR DOCUMENTO\" class=\"input-submit\" onclick=\"Validar(document.FormularioCabecera);\">";
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "  </table>\n";
    $html .= "</center>";
    $html .= " </form>";
    
    $html .= "<form name=\"forma\" action=\"".$accion['volver']."\" method=\"post\">\n";
    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    $html .= "</form>\n";
    $html .= ThemeCerrarTabla();
    return $html;
    }
    
    function Vista_FormularioModificarDoc($accion,$DocTemporal,$BodegaSatelite)
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
      $html .= "  xajax_Listado_Productos(Formulario,offset);";
      $html .= " } ";
      $html .= "</script>";
      
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
      $html .= "</script>\n";
    $html .= $ctl->AcceptNum(false);
    $html .= ThemeAbrirTabla('CREACION DE ORDENES DE REQUISICION');
    $html .= "  <center>";
    $html .= "  <table border=\"1\" width=\"100%\" align=\"center\" rules=\"all\">\n";
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      TMP-ID";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DocTemporal['orden_requisicion_tmp_id'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FECHA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DocTemporal['fecha_registro'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      USUARIO";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DocTemporal['nombre'];
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      ESM SOLICITANTE";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DocTemporal['tipo_id_tercero']." ".$DocTemporal['tercero_id']."-".$DocTemporal['nombre_tercero'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      FUERZA";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DocTemporal['tipo_fuerza'];
    $html .= "      </td>";
    $html .= "      <td class=\"modulo_table_list_title\">";
    $html .= "      TIPO DE REQUISICION";
    $html .= "      </td>";
    $html .= "      <td align=\"left\">";
    $html .= "          ".$DocTemporal['descripcion_orden_requisicion'];
    $html .= "      </td>";
    $html .= "    </tr>";
    
    $html .= "    <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" colspan=\"2\">";
    $html .= "      OBSERVACION ";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" colspan=\"4\">";
    $html .= "          ".$DocTemporal['observacion'];
    $html .= "      </td>";
    $html .= "    </tr>";
       
    $html .= "  </table>\n";
    $html .= "</center>";
    if(!empty($BodegaSatelite))
    {
        
        $html .= "<br>";
        $html .= "<center>";
        $html .= "  <table border=\"1\" width=\"60%\" align=\"center\" rules=\"all\">\n";
        $html .= "    <tr class=\"modulo_list_claro\">";
        $html .= "          <td  class=\"formulacion_table_list\" colspan=\"4\">";
        $html .= "              TRASLADO";
        $html .= "          </td>";
        $html .= "    </tr>";
         $html .= "    <tr class=\"modulo_list_claro\">";
        $html .= "          <td  class=\"modulo_table_list_title\">";
        $html .= "              CENTRO UTILIDAD";
        $html .= "          </td>";
        $html .= "          <td >";
        $html .= "              ".$BodegaSatelite['centro'];
        $html .= "          </td>";
        $html .= "          <td class=\"modulo_table_list_title\">";
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
    if($_REQUEST['datos']['ssiid']=='0')
    {
    $html .= "  <form method=\"post\" name=\"FormularioBuscador\" id=\"FormularioBuscador\"> ";
    $html .= "	<table align=\"center\" border=\"0\" width=\"60%\" class=\"modulo_table_list\">\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "      <td>";
      $html .= "      CODIGO PRODUCTO";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"codigo\" id=\"codigo\" style=\"width:100%\">";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      DESCRIPCION";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"descripcion\" id=\"descripcion\" style=\"width:100%\">";
      $html .= "      </td>";
      $html .= "      <td colspan=\"2\">";
      $html .= "      <input type=\"hidden\" name=\"orden_requisicion_tmp_id\" id=\"orden_requisicion_tmp_id\" value=\"".$_REQUEST['orden_requisicion_tmp_id']."\" >";
      $html .= "      <input type=\"button\" class=\"input-submit\" value=\"buscar\" style=\"width:100%\" onclick=\"xajax_Listado_Productos(xajax.getFormValues('FormularioBuscador'));\" >";
      $html .= "      </td>";
      $html .= "		</tr>\n";
      $html .= "  </table>\n";
      $html .= "  </form>";
      $html .= "<br>";
      $html .= "   <center>";
     $html .= "   <div id=\"paginador\"></div>";
     $html .= "   <form id=\"Productos\" name=\"Productos\"> ";
     $html .= "<div id=\"ListaProductos\" style=\"100%;width:95%;height:300px;overflow:scroll;display:none;\"></div>";
     $html .= "  </form>";
     $html .= "<div id=\"Boton_ListaProductos\"></div>";
     $html .= "   </center>";
    $html .= "<br>";
    }
    $html .= "   <form id=\"FormularioProductosTemporal\" name=\"FormularioProductosTemporal\"> ";
    $html .= "<div id=\"ProductosEnTemporal\"></div>";
    $html .= "  </form>";    
    $html .= "<form name=\"forma\" action=\"".$accion['volver']."\" method=\"post\">\n";
    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "    <tr>\n";
     if($_REQUEST['datos']['ssiid']=='1')
    {
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"button\" value=\"CREAR ORDEN DE REQUISICION\" onclick=\"xajax_CrearDocumento('".$_REQUEST['orden_requisicion_tmp_id']."');\">\n";
    $html .= "      </td>\n";
    }
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
    $html .= "      </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table>\n";
    $html .= "</form>\n";
    $html .= "  <script>";
    $html .= "  xajax_Listado_Productos_TMP('".$_REQUEST['orden_requisicion_tmp_id']."');";
    $html .= "  </script>";
    $html .= ThemeCerrarTabla();
    return $html;
    }
    
    
    /**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		function main($action,$ESM,$request,$TIPO)
    {
			$accion=$action['volver'];
					  

			$html .='



			<script languaje="javascript">



			function acceptNum(evt)
			{ 
			var nav4 = window.Event ? true : false;
			var key = nav4 ? evt.which : evt.keyCode;
			return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
			}

			</script>
			';

			//Confirmar 2 es para validar el formulario de los medicamento

			$html .="<script>";
			$html .='function Confirmar(Formulario)
			{
			var band=0;
			var cadena = [];
			var temp;
					  
				   if(Formulario.descripcion=="")
				  {
				  cadena.push("No Haz Ingresado Una Descripcion\n");
				  band=1;
				  }
				  
				  if(Formulario.codigo_fuerza=="")
				  {
				  cadena.push("No Haz Ingresado Un Codigo FF.MM\n");
				  band=1;
				  }
				  
					
				var entrar = confirm("Continuar?")

				if (entrar) 
					  {
						  
						  
						 if(band==1)
						  {
						  alert(cadena);
						  alert("¡Por favor, Diligenciar todos los Datos!");
						   } 
							  else
							  {
									   if(Formulario.token=="1")
									   {
									   //alert("Ingreso");
									   xajax_Insertar_TipoFuerza(Formulario);
									   }
										  else
											  {
											  //alert("Modificacion");
											  xajax_Modificar_TipoFuerza(Formulario);
											  }
							  }
						  
					  
					  }
						else
						{
						  return(false);
						}
						   
			}';
			$html .="</script>";




			$html .="<script>";
			$html .= "  function Paginador(esm_empresas,nombre,identificacion,tipo,offset)\n";
			$html .= "  {";
			$html .= "    xajax_Listado_ProfesionalesSinEsm(esm_empresas,nombre,identificacion,tipo,offset);\n";
			$html .= "  }\n";   
			$html .= "  function Paginador_(esm_empresas,nombre_,identificacion_,tipo_,offset)\n";
			$html .= "  {";
			$html .= "    xajax_Listado_ProfesionalesEnEsm(esm_empresas,nombre_,identificacion_,tipo_,offset);\n";
			$html .= "  }\n"; 
			$html .="</script>";

  
    $html .= ThemeAbrirTabla('ASOCIAR IPS A ESTABLECIMIENTOS DE SANIDAD MILITAR');
    
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
      $html .= "</script>\n";
      
      //$html .= "<BR><BR><CENTER><a class=\"label_error\" href=\"#\" onclick=\"xajax_Ingreso_TipoFuerza()\">[::CREAR - NUEVO TIPO DE FUERZA MILITAR::]</a><BR></CENTER>";
       
      $html .= "<center>";
      $html .= "	<table width=\"98%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<table width=\"90%\" align=\"center\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td>\n";
			$html .= "							<div class=\"tab-pane\" id=\"creacion_asociacion_estadosdocumentos\">\n";
			$html .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"creacion_asociacion_estadosdocumentos\" )); </script>\n";
      
      //PRIMER TAB
			$html .= "								<div class=\"tab-page\" id=\"crear_estadosdocumentos\">\n";
			$html .= "									<h2 class=\"tab\">ASIGNAR IPS A ESM</h2>\n";
      $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"crear_estadosdocumentos\")); </script>\n";
       
      $select .= "<select class=\"select\" style=\"width:100%;\" name=\"esm_empresas\" id=\"esm_empresas\">";      
      $select .= "<option value=\"\">SELECCIONAR</option>";
      foreach($ESM as $key=>$esm)
      {
      $select .= "<option value=\"".$esm['tipo_id_tercero']."@".$esm['tercero_id']."\">".$esm['identificacion']."-".$esm['nombre_tercero']."</option>";
      }
      $select .= "</select>";
      
      $html .= "<center>";
      $html .= "  <table border=\"0\" width=\"70%\" align=\"center\" >\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
      $html .= "    <td colspan=\"7\" align=\"center\">";
      $html .= "     BUSCADOR";
      $html .= "    </td>";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "          <td class=\"modulo_table_list_title\">";
      $html .= "          ESTABLECIMIENTO DE SANIDAD MILITAR";
      $html .= "          </td>";
      $html .= "          <td align=\"center\">\n";
      $html .= "          ".$select;
      $html .= "          </td>\n";
      $html .= "    </tr>\n";
		$html .= "    <tr class=\"modulo_list_claro\">";
		$html .= "          <td class=\"modulo_table_list_title\">";
		$html .= "         TIPO IDENTIFICACION";
		$html .= "          </td>";
		$html .= "			  <td align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
		$html .= "				  <select name=\"tipo\"   id=\"tipo\"  class=\"select\">\n";
		//$html .= "            <option value = '-1'>--  SELECCIONE --</option>\n";
		$csk = "";
		foreach($TIPO as $indice => $valor)
		{
		$sel = ($valor['tipo_id_tercero']==$request['tipo_id_paciente'])? "selected":"";
		$html .= "  <option value=\"".$valor['tipo_id_tercero']."\" ".$sel.">".$valor['descripcion']."</option>\n";
		}
		$html .= "				  </select>\n";
		$html .= "				</td>\n";
	    $html .= "    </tr>\n";
		$html .= "    <tr class=\"modulo_list_claro\">";
	  
		$html .= "          <td class=\"modulo_table_list_title\">";
		$html .= "          IDENTIFICACION";
		$html .= "          </td>";
		$html .= "          <td class=\"modulo_table_list_title\">";
		$html .= "          <input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"identificacion\" id=\"identificacion\">";
		$html .= "          </td>";
		$html .= "    </tr>\n";
		$html .= "    <tr class=\"modulo_list_claro\">";
		$html .= "          <td class=\"modulo_table_list_title\">";
		$html .= "          NOMBRE DE IPS";
		$html .= "          </td>";
		$html .= "          <td class=\"modulo_table_list_title\">";
		$html .= "          <input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"nombre\" id=\"nombre\">";
		$html .= "          </td>";
		$html .= "    </tr>\n";
		$html .= "  </table>";
		$html .= "  <table border=\"0\" width=\"15%\" align=\"center\" >\n";
		$html .= "    <tr   colspan=\"1\" align=\"center\" class=\"modulo_list_claro\">";
		$html .= "          <td>";
		$html .= "        <input type=\"button\" value=\"BUSCAR\" onclick=\"xajax_Listado_ProfesionalesSinEsm(document.getElementById('esm_empresas').value,document.getElementById('nombre').value,document.getElementById('identificacion').value,document.getElementById('tipo').value,'1');\" style=\"width:100%\" class=\"input-submit\">";
		$html .= "    </td>";
		$html .= "    </tr>\n";
      
      $html .= "  </table>";
      $html .= "</center>";
      $html .= "<br>";
      
      $html .= "  <div id=\"Listado_Profesionales\">";
      $html .= "  </div>";
      $html .= "								</div>\n"; //CIERRA PRIMER TAB
       
       $html .= "								<div class=\"tab-page\" id=\"asociar_estadosdocumentos\">\n";
       $html .= "									<h2 class=\"tab\">CONSULTAR: IPS ASIGNADOS A ESM </h2>\n";
       $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"asociar_estadosdocumentos\")); </script>\n";

      $select = "<select class=\"select\" style=\"width:100%;\" name=\"esm_empresas_\" id=\"esm_empresas_\">";      
      $select .= "<option value=\"\">SELECCIONAR</option>";
      foreach($ESM as $key=>$esm)
      {
      $select .= "<option value=\"".$esm['tipo_id_tercero']."@".$esm['tercero_id']."\">".$esm['identificacion']."-".$esm['nombre_tercero']."</option>";
      }
      $select .= "</select>";
      
      $html .= "<center>";
      $html .= "  <table border=\"0\" width=\"70%\" align=\"center\" >\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
      $html .= "    <td colspan=\"7\" align=\"center\">";
      $html .= "     BUSCADOR";
      $html .= "    </td>";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "          <td class=\"modulo_table_list_title\">";
      $html .= "          ESTABLECIMIENTO DE SANIDAD MILITAR";
      $html .= "          </td>";
      $html .= "          <td align=\"center\">\n";
      $html .= "          ".$select;
      $html .= "          </td>\n";
      $html .= "    </tr>\n";
     	$html .= "    <tr class=\"modulo_list_claro\">";
		$html .= "          <td class=\"modulo_table_list_title\">";
		$html .= "         TIPO IDENTIFICACION";
		$html .= "          </td>";
		$html .= "			  <td align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
		$html .= "				  <select name=\"tipo_\"   id=\"tipo_\"  class=\"select\">\n";
		//$html .= "            <option value = '-1'>--  SELECCIONE --</option>\n";
		$csk = "";
		foreach($TIPO as $indice => $valor)
		{
		$sel = ($valor['tipo_id_tercero']==$request['tipo_id_paciente'])? "selected":"";
		$html .= "  <option value=\"".$valor['tipo_id_tercero']."\" ".$sel.">".$valor['descripcion']."</option>\n";
		}
		$html .= "				  </select>\n";
		$html .= "				</td>\n";
	    $html .= "    </tr>\n";
		$html .= "    <tr class=\"modulo_list_claro\">";
	  
		$html .= "          <td class=\"modulo_table_list_title\">";
		$html .= "          IDENTIFICACION";
		$html .= "          </td>";
		$html .= "          <td class=\"modulo_table_list_title\">";
		$html .= "          <input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"identificacion_\" id=\"identificacion_\">";
		$html .= "          </td>";
		$html .= "    </tr>\n";
		$html .= "    <tr class=\"modulo_list_claro\">";
		$html .= "          <td class=\"modulo_table_list_title\">";
		$html .= "          NOMBRE DEL IPS";
		$html .= "          </td>";
		$html .= "          <td class=\"modulo_table_list_title\">";
		$html .= "          <input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"nombre_\" id=\"nombre_\">";
		$html .= "          </td>";
		$html .= "    </tr>\n";
		
		
		$html .= "  </table>";
		$html .= "  <table border=\"0\" width=\"15%\" align=\"center\" >\n";
		$html .= "          <td>";
		$html .= "        <input type=\"button\" value=\"BUSCAR\" onclick=\"xajax_Listado_ProfesionalesEnEsm(document.getElementById('esm_empresas_').value,document.getElementById('nombre_').value,document.getElementById('identificacion_').value,document.getElementById('tipo_').value,'1');\" style=\"width:100%\" class=\"input-submit\">";
		$html .= "    </td>";
		$html .= "    </tr>\n";

      $html .= "  </table>";
      $html .= "</center>";
      $html .= "<br>";
      
      $html .= "  <div id=\"Listado_ProfesionalesEnEsm\">";
      $html .= "  </div>";
      $html .= "								</div>\n"; //CIERRO SEGUNDO TAB
      $html .= "							</div>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "  </table>\n"; //CIERRO TODOS LOS TABS
      
      
      $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      
      /*$html .= "   <script>";
      $html .= "    xajax_Listado_Terceros('','','','1');";
      $html .= "    xajax_Listado_ESM('','','','1');";
      $html .= "   </script>";*/
      
    //$html .= $this->CrearVentana(600,"CREACION DE TIPOS DE FUERZAS");
    $html .= ThemeCerrarTabla();
    
    
    
    
    
    return($html);
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
      //En ese espacio se visualiza la informacion extraida de la base de datos.
      $html .= "  </div>\n";
      $html .= "</div>\n";


    
      return $html;
    }    
    
    
  
  }
?>