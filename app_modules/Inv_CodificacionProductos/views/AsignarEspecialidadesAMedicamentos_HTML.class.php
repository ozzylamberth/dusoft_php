<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: AsignarEspecialidadesAMedicamentos_HTML.class.php,v 1.1 2010/01/19 13:23:00 mauricio Exp $ 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: AsignarEspecialidadesAMedicamentos_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class AsignarEspecialidadesAMedicamentos_HTML
	{
		/**
		* Constructor de la clase
		*/
		function AsignarEspecialidadesAMedicamentos_HTML(){}
	



function main($request,$Empresas,$accion)
    {
   // $accion=$action['volver'];
			  
    
    
    $html .='
  
  
  
  <script languaje="javascript">
  
  
  function ConfirmaBorrar(tabla,id,campo_id,descripcion)
  {
        var entrar = confirm("Confirmar Borrar "+descripcion+"?")

        if (entrar) 
              {
                  xajax_BorrarGrupo(tabla,id,campo_id,descripcion);
              }
                else
                {
                  return(false);
                }
  
  
  
  }
  
  function ConfirmaBorrarClase(grupo_id,laboratorio_id,descripcion,nombregrupo,sw_medicamento)
  {
        var entrar = confirm("Confirmar Borrar "+descripcion+" de "+nombregrupo+"?")

        if (entrar) 
              {
                  xajax_BorrarClases(grupo_id,laboratorio_id,descripcion,nombregrupo,sw_medicamento);
              }
                else
                {
                  return(false);
                }
  
  
  
  }
  
  
  
  
  function acceptNum(evt)
{ 
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
}
  
  
  </script>
  ';
    
	
   
    $html .= ThemeAbrirTabla('ASIGNAR ESPECIALIDADES A MEDICAMENTOS POR EMPRESA Y DEPARTAMENTO');
    
    //FIN URL    
    	  
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
        
        $html .= "<center>";
	      $html .= "<fieldset class=\"fieldset\" style=\"width:40%\" align=\"center\">\n";
        $html .= "  <legend class=\"normal_10AN\">SELECCIONES EMPRESA</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		    $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($Empresas as $key => $emp)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
//$EmpresaId,$Div,$url,$NombreEmpresa
          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td width=\"60\">".$emp['empresa']." </td>\n";
          $html .= "      <td width=\"40\">";
          $html .= "      <a onclick=\"xajax_CentrosDeUtilidad('".$emp['empresa_id']."','Centro".$emp['empresa_id']."','','".$emp['razon_social']."');\">";
          $html .= "      <img title=\"Continuar...\" src=\"".GetThemePath()."/images/flecha_der.gif\" border=\"0\"> ";
          $html .= "      </a>";
          $html .= "      <div id=\"Centro".$emp['empresa_id']."\"></div>";
          $html .= "      </td>";
          $html .= "       </tr>";
          
        }
        
        
        
        $html .= "    </table>\n";
        $html .= "</fieldset><br>\n";
        $html .= "</center>";
        
		$html .= "</div>"; //CIERRA DIV DE LISTADO DE GRUPOS
      $html .= "<form name=\"forma\" action=\"".$accion['volver']."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\"><br>\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
	  
	  $html .= "                   <div id='error_ter' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
       
    $html .= $this->CrearVentana(600,"CLASIFICACION DE PRODUCTOS");
    $html .= ThemeCerrarTabla();
    
    
    
    return($html);
    }








	/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		function main2($request,$Departamentos,$Grupos,$action)
    {
    $accion=$action['volver'];
	  
    $html .="<script>";
	$html .="function ConfirmaBorrar(EmpresaId,CentroUtilidad,UnidadFuncional,Departamento,CodigoMedicamento,Especialidad)
			{
        var entrar = confirm('Confirmar Borrar?');

        if (entrar) 
              {
                  xajax_BorrarEspecialidades(EmpresaId,CentroUtilidad,UnidadFuncional,Departamento,CodigoMedicamento,Especialidad);
              }
                else
                {
                  return(false);
                }
				}";
				
				
	$html .="</script>";
	
	
    $html .="<script>";
	$html .="
				function acceptNum(evt)
					{ 
					  var nav4 = window.Event ? true : false;
					  var key = nav4 ? evt.which : evt.keyCode;
					  return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
					}
	
	
	function Paginador(EmpresaId,CentroUtilidad,UnidadFuncional,Departamento,CodigoMedicamento,offset) ";
	$html .=" { ";
	$html .="xajax_EspecialidadesAsignadas(EmpresaId,CentroUtilidad,UnidadFuncional,Departamento,CodigoMedicamento,offset);";
	$html .=" } ";
	
	
	$html .=" function paginador(EmpresaId,CentroUtilidad,UnidadFuncional,Departamento,DescripcionMedicamento,Molecula,offset) ";
	$html .=" { ";
	$html .="xajax_ListarMedicamentos(EmpresaId,CentroUtilidad,UnidadFuncional,Departamento,DescripcionMedicamento,Molecula,offset);";
	$html .=" } ";
	
	$html .="</script>";
    
    
    
    
    
    
    $html .= ThemeAbrirTabla('ASIGNAR ESPECIALIDADES A MEDICAMENTOS POR EMPRESA Y DEPARTAMENTO');
    
    
    
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

	  /*
		   * Primer Tab Para
		   */
			$html .= "	<table width=\"100%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<table width=\"100%\" align=\"center\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td>\n";
			$html .= "							<div class=\"tab-pane\" id=\"especialidades\">\n";
			$html .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"especialidades\" )); </script>\n";
			$html .= "								<div class=\"tab-page\" id=\"departamentos\">\n";
			$html .= "									<h2 class=\"tab\">DEPARTAMENTOS</h2>\n";
			$html .= "									<script>	tabPane.addTabPage( document.getElementById(\"departamentos\")); </script>\n";
       $html .= "<center>";
        $html .= "<fieldset class=\"fieldset\" style=\"width:60%\">\n";
        $html .= "  <legend class=\"normal_10AN\">DEPARTAMENTOS</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"40%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">UBICACION</td>\n";
        $html .= "      <td width=\"10%\"></td>\n";
        
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($Departamentos as $key => $depto)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$depto['descripcion']."</td><td>".$depto['ubicacion']." </td>\n";
          $html .= "      </td>";
          
              
          $html .= "      <td align=\"center\">\n";
          $html .= "        <a href=\"#\" onclick=\"xajax_ListadoMedicamentos('".$request['empresa_id']."','".$request['centro_utilidad']."','".$request['unidad_funcional']."','".$depto['departamento']."','','');\">\n";
          $html .= "          <img title=\"Listado Productos\" src=\"".GetThemePath()."/images/producto_consultar.png\" border=\"0\">\n";
                                         // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
          $html .= "        </a>\n";
          $html .= "      </td>\n";
    			  
        }
        $html .= "    </table>\n";
        $html .= "</fieldset><br>\n";
        $html .= "</center>";
    $html .= "								</div>";
	
	/*APERTURA DEL SEGUNDO TAB*/
	$html .= "								<div class=\"tab-page\" id=\"productos\">\n";
	$html .= "									<h2 class=\"tab\">PRODUCTOS - ESPECIALIDADES</h2>\n";
	$html .= "									<script>	tabPane.addTabPage( document.getElementById(\"productos\")); </script>\n";
	 $html .= "<div id =\"ListadoMedicamentos\"></div>";
   /*CIERRE DE TABS*/
	$html .= "								</div>\n";
	$html .= "							</div>\n";
	$html .= "						</td>\n";
	$html .= "					</tr>\n";
	$html .= "				</table>\n";
	$html .= "			</td>\n";
	$html .= "		</tr>\n";
	$html .= "  </table>\n";
    
      $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\"><br>\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
	  
	  $html .= "                   <div id='error_ter' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
       
    $html .= $this->CrearVentana(800,"CLASIFICACION DE PRODUCTOS");
    $html .= ThemeCerrarTabla();
   $html .= "<script>";
   $html .= "tabPane.setSelectedIndex(0);";
   $html .= "</script>";
  
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