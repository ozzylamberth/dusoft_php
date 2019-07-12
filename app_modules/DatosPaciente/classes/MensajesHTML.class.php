<?
class MensajesHTML{
  /**
  * Constructor de la Clase 
  */  
  function MensajesHTML(){
  }

  /**
  *	
  */
  function ProbarCosas(){
  
    $html .= "<script>\n";
    $html .= "  function metodoPrueba(){
                
                  try{
                    alert('METODO PRUEBA!!!!!!');
                  }
                  catch(error){
                    alert(error);
                  }\n
                  
                  
                }; \n";
    $html .= "</script>\n";    
       
    return $html;
  
  }
  
  /**
  *Metodo que permite ajustar el nombre del paciente  
  */
  function ValidarCosas(){

    $html .= "<script>\n";
    $html .= "  function Validacion(){

				//Primera Fila de CheckBox_0
				if(document.formNombre.ajNomb_0[0].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_0[1].checked = false;
					document.formNombre.ajNomb_0[2].checked = false;
					document.formNombre.ajNomb_0[3].checked = false;
				}
				
				if(document.formNombre.ajNomb_0[1].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_0[0].checked = false;
					document.formNombre.ajNomb_0[2].checked = false;
					document.formNombre.ajNomb_0[3].checked = false;
				}
				
				if(document.formNombre.ajNomb_0[2].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_0[0].checked = false;
					document.formNombre.ajNomb_0[1].checked = false;
					document.formNombre.ajNomb_0[3].checked = false;
				}
				
				if(document.formNombre.ajNomb_0[3].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_0[0].checked = false;
					document.formNombre.ajNomb_0[1].checked = false;
					document.formNombre.ajNomb_0[2].checked = false;
				}


				//Segunda Fila de CheckBox_1
				if(document.formNombre.ajNomb_1[0].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_1[1].checked = false;
					document.formNombre.ajNomb_1[2].checked = false;
					document.formNombre.ajNomb_1[3].checked = false;
				}
				
				if(document.formNombre.ajNomb_1[1].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_1[0].checked = false;
					document.formNombre.ajNomb_1[2].checked = false;
					document.formNombre.ajNomb_1[3].checked = false;
				}
				
				if(document.formNombre.ajNomb_1[2].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_1[0].checked = false;
					document.formNombre.ajNomb_1[1].checked = false;
					document.formNombre.ajNomb_1[3].checked = false;
				}
				
				if(document.formNombre.ajNomb_1[3].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_1[0].checked = false;
					document.formNombre.ajNomb_1[1].checked = false;
					document.formNombre.ajNomb_1[2].checked = false;
				}


				//Tercera Fila de CheckBox_2          
				if(document.formNombre.ajNomb_2[0].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_2[1].checked = false;
					document.formNombre.ajNomb_2[2].checked = false;
					document.formNombre.ajNomb_2[3].checked = false;
				}
				
				if(document.formNombre.ajNomb_2[1].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_2[0].checked = false;
					document.formNombre.ajNomb_2[2].checked = false;
					document.formNombre.ajNomb_2[3].checked = false;
				}
				
				if(document.formNombre.ajNomb_2[2].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_2[0].checked = false;
					document.formNombre.ajNomb_2[1].checked = false;
					document.formNombre.ajNomb_2[3].checked = false;
				}
				
				if(document.formNombre.ajNomb_2[3].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_2[0].checked = false;
					document.formNombre.ajNomb_2[1].checked = false;
					document.formNombre.ajNomb_2[2].checked = false;
				}
				
				//Tercera Fila de CheckBox_3
				if(document.formNombre.ajNomb_3[0].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_3[1].checked = false;
					document.formNombre.ajNomb_3[2].checked = false;
					document.formNombre.ajNomb_3[3].checked = false;
				}
				
				if(document.formNombre.ajNomb_3[1].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_3[0].checked = false;
					document.formNombre.ajNomb_3[2].checked = false;
					document.formNombre.ajNomb_3[3].checked = false;
				}	
				
				if(document.formNombre.ajNomb_3[2].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_3[0].checked = false;
					document.formNombre.ajNomb_3[1].checked = false;
					document.formNombre.ajNomb_3[3].checked = false;
				}

				if(document.formNombre.ajNomb_3[3].checked == true){
					//alert('METODO PRUEBA!!!!!!');
					document.formNombre.ajNomb_3[0].checked = false;
					document.formNombre.ajNomb_3[1].checked = false;
					document.formNombre.ajNomb_3[2].checked = false;
				}


				}; \n";
    $html .= "</script>\n";  
  
  	return $html;
  }
  
  
  /**
  * Funcion que crea la ventana en la cual se ajusta el nombre completo del paciente
  */
  function CrearVentana($tmn = 350, $titulo="Cambiar nombre")
  {
    $html .= "<script>\n";
    $html .= "  var contenedor = 'Contenedor';\n";
    $html .= "  var titulo = 'titulo';\n";
    $html .= "  var hiZ = 5;\n";
    $html .= "  function OcultarSpan()\n";
    $html .= "  { \n";
    $html .= "    try\n";
    $html .= "    {\n";
    //$html .= "      xGetElementById('capaFondo1').style.display = \"none\";\n";
    $html .= "      e = xGetElementById('Contenedor');\n";
    $html .= "      e.style.display = \"none\";\n";
    $html .= "    }\n";
    $html .= "    catch(error){}\n";
    $html .= "  }\n";
    $html .= "  function MostrarSpan()\n";
    $html .= "  { \n";
    $html .= "    try\n";
    $html .= "    {\n";
    //$html .= "      xGetElementById('capaFondo1').style.display = \"block\";\n";
    $html .= "      e = xGetElementById('Contenedor');\n";
    $html .= "      e.style.display = \"block\";\n";
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
    $html .= "    ele = xGetElementById(contenedor);\n";
    $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
    $html .= "    xMoveTo(ele, xClientWidth()/3, xScrollTop()+20);\n";
    $html .= "    ele = xGetElementById(titulo);\n";
    $html .= "    ele.innerHTML = '$titulo';\n";
    $html .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
    $html .= "    xMoveTo(ele, 0, 0);\n";
    $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $html .= "    ele = xGetElementById('cerrar');\n";
    $html .= "    xResizeTo(ele,20, 20);\n";
    $html .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
    $html .= "  }\n";

    $html .= "  function IniciarGrande()\n";
    $html .= "  {\n";
    $html .= "    contenedor = 'Contenedor';\n";
    $html .= "    titulo = 'titulo';\n";
    $html .= "    ele = xGetElementById(contenedor);\n";
    $html .= "    xResizeTo(ele,800, 'auto');\n";
    $html .= "    xMoveTo(ele, xClientWidth()/8, xScrollTop()+20);\n";
    $html .= "    ele = xGetElementById(titulo);\n";
    $html .= "    ele.innerHTML = 'LISTADO DE CARGOS';\n";
    $html .= "    xResizeTo(ele,780, 20);\n";
    $html .= "    xMoveTo(ele, 0, 0);\n";
    $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $html .= "    ele = xGetElementById('cerrar');\n";
    $html .= "    xResizeTo(ele,20, 20);\n";
    $html .= "    xMoveTo(ele,780, 0);\n";
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
    $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:5\">\n";
    $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">CONFIRMACI�</div>\n";
    $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
    $html .= "  <div id='Contenido' class='d2Content'>\n";
    $html .= "  <div id=\"ventana\" ></div>\n";
    $html .= "  <div id=\"erroro\" class=\"label_error\" style=\"text-align:center\"></div>\n";
    $html .= "  </div>\n";
    $html .= "</div>\n";

    return $html;
  }   
}
?>



