<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: CrearRutasDeViajes_HTML.class.php,
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: CrearRutasDeViajes_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class CrearRutasDeViajes_HTML
	{
		/**
		* Constructor de la clase
		*/
		function CrearRutasDeViajes_HTML(){}
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		function main($action,$request)
    {
    $accion=$action['volver'];
			  
    //print_r($request);
    
    $CodigoPais=$request['datos']['tipo_pais_id'];
    $CodigoDepartamento=$request['datos']['tipo_dpto_id'];
    
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
    
          
          if(Formulario.zona_id=="")
          {
          cadena.push("No Haz Ingresado un Codigo de Zona\n");
          band=1;
          }
          
           if(Formulario.descripcion=="")
          {
          cadena.push("No Haz Ingresado Una Descripcion\n");
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
                               xajax_InsertarZonas(Formulario);
                               }
                                  else
                                      {
                                      //alert("Modificacion");
                                      xajax_ModZonas(Formulario);
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
    $html .='function Confirmar2(Formulario)
    {
    var band=0;
    var cadena = [];
    var temp;
    
          
          if(Formulario.rutaviaje_origen_id=="")
          {
          cadena.push("No Haz Ingresado un Codigo de Ruta de Viaje\n");
          band=1;
          }
          
           if(Formulario.descripcion=="")
          {
          cadena.push("No Haz Ingresado Una Descripcion\n");
          band=1;
          }
          
          if(Formulario.empresa_id=="")
          {
          cadena.push("No Haz Seleccionado una Empresa como Origen de la Ruta \n");
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
                               xajax_InsertarRutaViaje(Formulario);
                               }
                                  else
                                      {
                                      //alert("Modificacion");
                                      xajax_ModRutaViaje(Formulario);
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
    $html .= "  function ValidarIngresoZona(formulario)\n";
    $html .= "  {";
    $html .= '    
                  var band=0;
                  var cadena = [];
             
          if(formulario.Zonas=="")
          {
          cadena.push("No Haz Seleccionado una Zona\n ");
          band=1;
          }
          
          if(formulario.tipo_dpto_id=="")
          {
          cadena.push("No Haz Seleccionado un Departamento\n ");
          band=1;
          }
          
          if(formulario.tipo_mpio_id=="")
          {
          cadena.push("No Haz Seleccionado un Municipio\n ");
          band=1;
          }
    
              if(band==1)
                  {
                  alert(cadena);
                  alert("¡Por favor, Diligenciar todos los Datos!");
                   } 
                      else
                      {
                               xajax_InsertarConfigurarZonas(formulario);
                      }';
    $html .= "  }";   
    $html .="</script>";
    
    
    $html .="<script>";
    $html .= "  function ValidarIngresoRuta(formulario)\n";
    $html .= "  {";
    $html .= '    
                  var band=0;
                  var cadena = [];
             
          if(formulario.Zonas=="")
          {
          cadena.push("No Haz Seleccionado una Zona\n ");
          band=1;
          }
          
          if(formulario.empresa_id=="")
          {
          cadena.push("No Haz Seleccionado la Empresa Destino\n ");
          band=1;
          }
    
              if(band==1)
                  {
                  alert(cadena);
                  alert("¡Por favor, Diligenciar todos los Datos!");
                   } 
                      else
                      {
                               xajax_InsertarConfigurarRuta(formulario);
                      }';
    $html .= "  }";   
    $html .="</script>";
    
    $html .="<script>";
    $html .="function Paginador(pais,offset)
              {
              xajax_RutasViajesT(pais,offset);
              }
    
    ";
    $html .="</script>";
  
    $html .= ThemeAbrirTabla('CREACION DE RUTAS DE VIAJE');
    
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
      $html .= "	<table width=\"98%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<table width=\"90%\" align=\"center\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td>\n";
			$html .= "							<div class=\"tab-pane\" id=\"creacion_rutas_viajes\">\n";
			$html .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"creacion_rutas_viajes\" )); </script>\n";
      
      //PRIMER TAB
			$html .= "								<div class=\"tab-page\" id=\"crear_zonas\">\n";
			$html .= "									<h2 class=\"tab\">CREAR ZONAS</h2>\n";
      $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"crear_zonas\")); </script>\n";
      
      $html .="         <center>";
      $html .="                 <a class=\"label_error\" href=\"#\" onclick=\"xajax_IngresoZonas('".$CodigoPais."')\">";
      $html .="                 [::INGRESAR NUEVA ZONA::]";
      $html .="                 </a>";
      $html .="         </center><br>";
      
      $html .="<div id=\"ListadoZonas\">";
      $html .="</div>";
         
        $html .= "								</div>\n";
        
        //SEGUNDO TAB.
        $html .= "								<div class=\"tab-page\" id=\"configurarzonasgeograficas\">\n";
        $html .= "									<h2 class=\"tab\">CONFIGURAR ZONAS GEOGRAFICAS</h2>\n";
        $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"configurarzonasgeograficas\")); </script>\n";

        
    
        $html .="<div id=\"ZonasConfiguradas\">";
        $html .="</div>";
                  
        $html .= "								</div>\n";
        
   
   
   
    //TERCER TAB
			$html .= "								<div class=\"tab-page\" id=\"crear_rutas_viajes\">\n";
			$html .= "									<h2 class=\"tab\">CREAR RUTAS DE VIAJES</h2>\n";
      $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"crear_rutas_viajes\")); </script>\n";
      
      $html .="         <center>";
      $html .="                 <a class=\"label_error\" href=\"#\" onclick=\"xajax_IngresoRutaViaje('".$CodigoPais."')\">";
      $html .="                 [::CREAR NUEVA RUTA DE VIAJE::]";
      $html .="                 </a>";
      $html .="         </center><br>";
      
      
      $html .="         <center>";
      $html .= "        <div id=\"CrearRutasViajes\">\n"; 
      $html .= "								 </div>\n";
      $html .="         </center><br>";
     $html .= "								</div>\n";
   
   
     
    $html .="<script>";
    $html .= "xajax_ZonasT('".$CodigoPais."');";
    $html .="</script>";
        
    $html .="<script>";
    $html .= "xajax_AsignarDepartamentosZonas('".$CodigoPais."');";
    $html .="</script>";
    
    $html .="<script>";
    $html .= "xajax_CrearRutasDeViaje('".$CodigoPais."');";
    $html .="</script>";
    
 
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
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
    $html .= $this->CrearVentana(600,"CREACION DE RUTAS DE VIAJE");
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