<?php
	/**************************************************************************************  
	* $Id: app_InterfacesContables_userclasses_HTML.php,v 1.6 2007/04/26 19:31:28 jgomez Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.6 $ 
	* 
	* @autor Jaime G�ez 
	***************************************************************************************/
	
  IncludeClass("ClaseHTML");
class app_InterfacesContables_userclasses_HTML extends app_InterfacesContables_user
{
        function app_InterfacesContables_userclasses_HTML(){	}
		
/***********************************************************************************
* Muestra el menu de los empresas y centros de utilidad 
* 
* @access public 
***********************************************************************************/
     function SelectEmpresa()
     { 
      
       $this->MostrarEmpresas();
       $this->CrearElementos();
       $titulo[0]='EMPRESA';
       $url[0]='app';//contenedor 
       $url[1]='InterfacesContables';//m�ulo 
       $url[2]='user';//clase 
       $url[3]='MenuInterfaces';//m�odo 
       $url[4]='Empresas';//indice del request
       $this->salida .= gui_theme_menu_acceso('SELECCIONE EMPRESA',$titulo,$this->TodasEmpresas,$url,ModuloGetURL('system','Menu'));
       return true;
     }
    /********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function main()
    {
      
      $this->SelectEmpresa();
      return true;
    }
		
    function MenuInterfaces()
    { 
    
      $this->CrearElementos();
      IncludeClass("ClaseHTML");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $path = SessionGetVar("rutaImagenes");
      $consulta= new InterfacesSQL();
      $this->salida .= ThemeAbrirTabla("INTERFACES CONTABLES"); 
      $GENINTER=ModuloGetURL('app','InterfacesContables','user','GenerarInterfacesContables');
      $this->salida .= "            <form name=\"menu_docu\" action=\"javascript:LlamarDocu(1);\" method=\"post\">\n";
      $this->salida .= "                 <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $this->salida .= "                   <tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                         MENU DE OPCIONES";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                   </tr>\n";         
      $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a  title=\"GENERAR INTERFACES\" class=\"label_error\" href=\"".$GENINTER."\">GENERAR INTERFACES</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      $this->salida .= "                   </table>";
      $this->salida .= "             </form>";        
      //$Exit = ModuloGetURL('system','Menu');
      $Exit = ModuloGetURL('app','InterfacesContables','user','SelectEmpresa');
    $this->salida .= " <form name=\"volver\" action=\"".$Exit."\" method=\"post\">\n";//".$this->action[0]."
    $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "       <td align=\"center\" colspan='7'>\n";
    $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $this->salida .= "       </td>\n";  
    $this->salida .= "    </tr>\n"; 
    $this->salida .= "  </table>\n"; 
    $this->salida .= " </form>\n"; 
    $this->salida .= ThemeCerrarTabla();
      return true;
 }       
    
    
/*********************************************************************************
 *funcion para la revision de documentos por lapso
 $REVISARDOCS=ModuloGetURL('app','Cg_Movimientos','user','RevisarDocs');
*********************************************************************************/ 
    function RevisarDocs()
    { 
      
      $file ='app_modules/Cg_Movimientos/RemoteXajax/definirMov.php';
      $this->SetXajax(array("Revisionxx","GenerarInterfaces"),$file); 
      $this->CrearElementos();
      IncludeClass("ClaseHTML");
      $this->SetJavaScripts('facporlap');
      //$this->SetJavaScripts('TotalPaciente');
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $path = SessionGetVar("rutaImagenes");
      $consulta= new MovimientosSQL();
      $this->IncludeJS('RemoteXajax/definirMov.js', $contenedor='app', $modulo='Cg_Movimientos');
      
           $javaC = "<script>\n";
           $javaC .= "   var contenedor1=''\n";
           $javaC .= "   var titulo1=''\n";
           $javaC .= "   var hiZ = 2;\n";
           $javaC .= "   var DatosFactor = new Array();\n";
           $javaC .= "   var EnvioFactor = new Array();\n";
           $javaC .= "   function Iniciar2(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'ContenedorVer';\n";
           $javaC .= "       titulo1 = 'tituloVer';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
           $javaC .= "       xResizeTo(Capa, 950, 400);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/40, xScrollTop()+30);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 930, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarVer');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 930, 0);\n";
           $javaC .= "   }\n";
           $javaC.= "</script>\n";
           $this->salida.= $javaC;
           $javaC1.= "<script>\n";
           $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
           $javaC1 .= "   {\n";
           $javaC1 .= "     window.status = '';\n";
           $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
           $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
           $javaC1 .= "     ele.myTotalMX = 0;\n";
           $javaC1 .= "     ele.myTotalMY = 0;\n";
           $javaC1 .= "   }\n";
           $javaC1 .= "function MostrarDCS(ban,documento_contable_id,total_debitos,creditos,prefijo,numero,fecha_documento,nombre,tipo_id_tercero,tercero_id)
                      { 
                        xajax_DetalleMov(ban,documento_contable_id,total_debitos,creditos,prefijo,numero,fecha_documento,nombre,tipo_id_tercero,tercero_id);
                      }";
          $javaC1 .= "function Aumentar(ban,prefijo,numero)
                      { 
                        var link = xGetElementById('Vector').innerHTML; 
                        
                        xajax_Vector(link,ban,prefijo,numero);
                      }";
           $javaC1 .= "function quitar(numero)
                      { 
                        var link = xGetElementById('Vector').innerHTML; 
                        
                        xajax_Quitare(link,numero);
                      }";
            
             $javaC1 .="    function limpiarM() 
                            { 
                              document.getElementById('Vector').innerHTML='';
                            }";
            $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";// 
           $javaC1 .= "   {\n";
           $javaC1 .= "     if (ele.id == titulo1) {\n";
           $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
           $javaC1 .= "     }\n";
           $javaC1 .= "     else {\n";
           $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
           $javaC1 .= "     }  \n";
           $javaC1 .= "     ele.myTotalMX += mdx;\n";
           $javaC1 .= "     ele.myTotalMY += mdy;\n";
           $javaC1 .= "   }\n";
                
           $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
           $javaC1 .= "   {\n";
           $javaC1 .= "   }\n";
           
           $javaC1.= "function MostrarCapa(Elemento)\n";
           $javaC1.= "{\n";
           $javaC1.= "    capita = xGetElementById(Elemento);\n";
           $javaC1.= "    capita.style.display = \"\";\n";
           $javaC1.= "}\n";
           
           $javaC1.= "function Cerrar(Elemento)\n";
           $javaC1.= "{\n";
           $javaC1.= "    capita = xGetElementById(Elemento);\n";          
           $javaC1.= "    capita.style.display = \"none\";\n";          
           $javaC1.= "}\n";                    
           
           $javaC1.= "function Traer(Elemento)\n";
           $javaC1.= "{\n";
           $javaC1.= "    document.getElementById('hcuenta').innerHTML=Elemento;\n";          
           $javaC1.= "    document.hijo_cuenta.padre.value=Elemento;\n";          
           $javaC1.= "}\n";   
            
           $javaC1.= "</script>\n";
           $this->salida.= $javaC1;        
           
      
 
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
    $this->salida.="<div id='ContenedorVer' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloVer' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarVer' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorVer');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoVer'>\n";
    $this->salida .= "    </div>\n";
    $this->salida .= "    <div id='Vector'>\n";
    
    $this->salida .= "    </div>\n";          
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/              
      
    
    $this->salida .= ThemeAbrirTabla("CONSULTAR DOCUMENTOS"); 
    $this->salida .= "            <form name=\"revi\" action=\"javascript:LlamarRevi('".SessionGetVar("EMPRESA")."',document.revi.tip_lapi.value);\" method=\"post\">\n";
    $this->salida .= "               <div id=\"ventana1\">\n";
    $this->salida .= "                 <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $this->salida .= "                       <td colspan=\"4\" align=\"center\">\n";
    $this->salida .= "                          BUSCADOR DE MOVIMIENTOS";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";         
    $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                       <td width=\"15%\" colspan=\"1\" align=\"left\" class=\"normal_10AN\">\n";
    $this->salida .= "                          EMPRESA";
    $this->salida .= "                       </td>";
    $vector=$consulta->ColocarEmpresa(SessionGetVar("EMPRESA"));
    if(count($vector)>0)
    {
      $this->salida .= "                       <td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
      $this->salida .= "                          ".$vector[0]['razon_social']."";
      $this->salida .= "                       </td>"; 
       
    }
    else
    $this->salida .= "                          <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"> PROBLEMAS CON ELECCION DE EMPRESA</div>\n";
    $this->salida .= "                       </td>";
    $this->salida .= "                     </tr>";
    $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                       <td width=\"15%\" align=\"left\" class=\"normal_10AN\">\n";
    $this->salida .= "                          LAPSO CONTABLE";
    $this->salida .= "                       </td>";
    $this->salida .= "                       <td  width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
    $vector=$consulta->BuscarLapsos();                                                             //onkeyup=\"xajax_reqObtenerLiteral(document.getElementById('datos').value)\"
    if(count($vector)>0)
    {
      $this->salida .= "                         <select name=\"tip_lapi\" class=\"select\" onchange=\"afinar(cons_docu.tip_bus.value);\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
      $this->salida .= "                           <option value=\"0\" selected>SELECCIONAR</option> \n";
      for($i=0;$i<count($vector);$i++)
      {
        $this->salida .= "                           <option value=\"".$vector[$i]['lapso']."\">".$vector[$i]['lapso']."</option> \n";
      }
      $this->salida .= "                         </select>\n";
    }
    else
    $this->salida .= "                          <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"> La tabla cg_lapsos_contables no contiene registros</div>\n";
    $this->salida .= "                       </td>";
    $this->salida .= "                     </tr>";
    $this->salida .= "                      <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                        <td colspan=\"4\" align=\"center\" class=\"normal_10AN\">\n";
    $this->salida .= "                          <input type=\"submit\" class=\"input-submit\" value=\"Buscar Documentos\" >\n";
    $this->salida .= "                        </td>";
    $this->salida .= "                      </tr>";
    $this->salida .= "                    </table>";
    $this->salida .= "              </form>";        
    $this->salida .= "            <br>";
    $this->salida .= "          </div>\n"; 
    $this->salida .= "    <div id=\"revisiones\">";
    $this->salida .= "    </div>\n"; 
    $this->salida .= "    </form\n"; 
   
    $this->salida.="<script language=\"javaScript\">
               function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }
    
                function LlamarDocu(pag)
                {
                  if(document.cons_docu.dia_ini.value=='--')
                  {
                   document.cons_docu.dia_ini.value=0;
                  }
                  if(document.cons_docu.dia_fin.value=='--')
                  {
                   document.cons_docu.dia_fin.value=0;
                  }
          
                  xajax_VerMovimiento(pag,document.cons_docu.tip_bus.value,document.cons_docu.dia_ini.value,document.cons_docu.dia_fin.value,document.cons_docu.tip_doc.value,document.cons_docu.pref.value);
                }
                
                function LlamarDocus(pag,lapso,dia1,dia2,tipdoc,prefijo)
                {
                  xajax_VerMovimiento(pag,lapso,dia1,dia2,tipdoc,prefijo);
                }
                function LlamarDocus1(pag,lapso,dia1,dia2,tipdoc,prefijo)
                {
                  xajax_VerMovimiento2(pag,lapso,dia1,dia2,tipdoc,prefijo);
                }
                   
    </script>";
    $MENUMOV=ModuloGetURL('app','Cg_Movimientos','user','MenuMovimientos');
    $this->salida .= "    <div id=\"volverprincipal\">";
    $this->salida .= "     <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";//".$this->action[0]."
    $this->salida .= "      <table align=\"center\" width=\"50%\">\n";
    $this->salida .= "       <tr>\n";
    $this->salida .= "        <td align=\"center\" colspan='7'>\n";
    $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $this->salida .= "        </td>\n";  
    $this->salida .= "       </tr>\n"; 
    $this->salida .= "      </table>\n"; 
    $this->salida .= "     </form>";
    $this->salida .= "    </div>";
    $this->salida .= ThemeCerrarTabla();
    return true;
    }  

/*********************************************************************************
  *FUNCION PRA LA CONSULTA DE DOCUMENTOS
  **********************************************************************************/	
function GenerarInterfacesContables()
{ 
      
      $file ='app_modules/InterfacesContables/RemoteXajax/definirF.php';
      $this->SetXajax(array("ContabilizarDocsPorLapso","GenerarInterfaces","Revisionxx"),$file); 
      $this->CrearElementos();
      IncludeClass("ClaseHTML");
      $this->SetJavaScripts('facporlap');
      //$this->SetJavaScripts('TotalPaciente');
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $path = SessionGetVar("rutaImagenes");
//       $this->SubMenu();
      //echo "que".$this->Datos['tipo_doc_general_id'];
      //ECHO "que11".SessionGetVar("EMPRESA");
      $consulta= new InterfacesSQL();
      //$this->IncludeJS('RemoteScripting');
      $this->IncludeJS('RemoteXajax/definirF.js', $contenedor='app', $modulo='InterfacesContables');
      
           $javaC = "<script>\n";
           $javaC .= "   var contenedor1=''\n";
           $javaC .= "   var titulo1=''\n";
           $javaC .= "   var hiZ = 2;\n";
           $javaC .= "   var DatosFactor = new Array();\n";
           $javaC .= "   var EnvioFactor = new Array();\n";
           $javaC .= "   function Iniciar2(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'ContenedorVer';\n";
           $javaC .= "       titulo1 = 'tituloVer';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
           $javaC .= "       xResizeTo(Capa, 950, 400);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/40, xScrollTop()+30);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 930, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarVer');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 930, 0);\n";
           $javaC .= "   }\n";
           $javaC.= "</script>\n";
           $this->salida.= $javaC;
           $javaC1.= "<script>\n";
           $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
           $javaC1 .= "   {\n";
           $javaC1 .= "     window.status = '';\n";
           $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
           $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
           $javaC1 .= "     ele.myTotalMX = 0;\n";
           $javaC1 .= "     ele.myTotalMY = 0;\n";
           $javaC1 .= "   }\n";
           $javaC1 .= "function MostrarDCS(ban,documento_contable_id,total_debitos,creditos,prefijo,numero,fecha_documento,nombre,tipo_id_tercero,tercero_id)
                      { 
                        xajax_DetalleMov(ban,documento_contable_id,total_debitos,creditos,prefijo,numero,fecha_documento,nombre,tipo_id_tercero,tercero_id);
                      }";//alert('doc_cont'+documento_contable_id+'debitos'+total_debitos+'creditos'+creditos+'prefijo'+prefijo+'numero'+numero+'fecha'+fecha_documento+'nombre'+nombre+'tipo_idter'+tipo_id_tercero+'tercero'+tercero_id);
          $javaC1 .= "function Aumentar(ban,prefijo,numero)
                      { 
                        var link = xGetElementById('Vector').innerHTML; 
                        
                        xajax_Vector(link,ban,prefijo,numero);
                      }";//alert(prefijo+numero)
           $javaC1 .= "function quitar(numero)
                      { 
                        var link = xGetElementById('Vector').innerHTML; 
                        
                        xajax_Quitare(link,numero);
                      }";           //document.getElementById('Vector').innerHTML=link;alert('quitar'+numero);
            
             $javaC1 .="    function limpiarM() 
                            { 
                              document.getElementById('Vector').innerHTML='';
                            }";
            $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";// 
           $javaC1 .= "   {\n";
           $javaC1 .= "     if (ele.id == titulo1) {\n";
           $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
           $javaC1 .= "     }\n";
           $javaC1 .= "     else {\n";
           $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
           $javaC1 .= "     }  \n";
           $javaC1 .= "     ele.myTotalMX += mdx;\n";
           $javaC1 .= "     ele.myTotalMY += mdy;\n";
           $javaC1 .= "   }\n";
                
           $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
           $javaC1 .= "   {\n";
           $javaC1 .= "   }\n";
           
           $javaC1.= "function MostrarCapa(Elemento)\n";
           $javaC1.= "{\n";
           $javaC1.= "    capita = xGetElementById(Elemento);\n";
           $javaC1.= "    capita.style.display = \"\";\n";
           $javaC1.= "}\n";
           
           $javaC1.= "function Cerrar(Elemento)\n";
           $javaC1.= "{\n";
           $javaC1.= "    capita = xGetElementById(Elemento);\n";          
           $javaC1.= "    capita.style.display = \"none\";\n";          
           $javaC1.= "}\n";                    
           
           $javaC1.= "function Traer(Elemento)\n";
           $javaC1.= "{\n";
           $javaC1.= "    document.getElementById('hcuenta').innerHTML=Elemento;\n";          
           $javaC1.= "    document.hijo_cuenta.padre.value=Elemento;\n";          
           $javaC1.= "}\n";   
            
           $javaC1.= "</script>\n";
           $this->salida.= $javaC1;        
           
      
 
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
          $this->salida.="<div id='ContenedorVer' class='d2Container' style=\"display:none\">";
          $this->salida .= "    <div id='tituloVer' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarVer' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorVer');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
          $this->salida .= "    <div id='ContenidoVer'>\n";
          $this->salida .= "    </div>\n";
          $this->salida .= "    <div id='Vector'>\n";
          $this->salida .= "    </div>\n";          
          $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/              
            $this->salida .= "                 <table width=\"80%\" align=\"center\">\n";         
            $this->salida .= "                    <tr>\n";
            $this->salida .= "                       <td colspan=\"4\" align=\"center\">\n";
            if (!IncludeClass('InterfacesContables'))
              {
              $this->salida .="ERROR AL INSTANCIAR LA CLASE InterfacesContables NO PUDO SER INCLUIDA";
              }
              
          
              if(!class_exists('InterfacesContables'))
              {
                  $this->salida .= "NO SE CARGO LA CLASE InterfacesContables - NO EXISTE";
              }
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";   
          
          $a = new InterfacesContables;
          
          $empresa_id=SessionGetVar("EMPRESA");
//--------------------------------------------------------------
//PRUEBA DEL METODO GetListadoInterfaces($empresa_id) ----------
//--------------------------------------------------------------
            $resultado = $a->GetListadoInterfaces(SessionGetVar("EMPRESA"));   
            if($resultado===false)
            {
                $this->salida .= "ERRORES RETORNADOS POR EL METODO GetListadoInterfaces($empresa_id): <br>" . $a->Err() . "<br>" . $a->ErrMsg() . "<br><br>";
                return false;
            }
            if(count($resultado)==0)
            {
              $this->salida .= "NO HAY CONFIGURADAS INTERFAXCS CONTABLES PARA LA EMPRESA $empresa_id<br><br>";
                return false;
            }

    
    $this->salida .= ThemeAbrirTabla("GENERAR INTERFACES CONTABLES"); 
    $this->salida .= "            <form name=\"interface\" action=\"javascript:LlamarDocu(1);\" method=\"post\">\n";
    $this->salida .= "               <div id=\"ventana1\">\n";
    $this->salida .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $this->salida .= "                       <td colspan=\"5\" align=\"center\">\n";
    $this->salida .= "                          GENERADOR DE INTERFACES CONTABLES";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";         
    $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                       <td  colspan=\"1\" align=\"left\" class=\"normal_10AN\">\n";
    $this->salida .= "                         TIPO DE INTERFAZ";
    $this->salida .= "                       </td>";
    $this->salida .= "                       <td colspan=\"4\" align=\"left\" class=\"normal_10AN\">\n";
    global $xajax;
    $this->salida .= "                         <select name=\"tip_int\" class=\"select\" onchange=\"limpiar600();\">";
    $this->salida .= "                           <option value=\"-1\" selected>SELECCIONAR</option> \n";
    foreach($resultado as $k=>$vector)
    {
      $this->salida .= "                           <option value=\"".$vector['interface_id']."\">".$vector['descripcion']."</option> \n";
    }
    $this->salida .= "                         </select>\n";
    $this->salida .= "                       </td>";
    $this->salida .= "                     </tr>";
    $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                       <td colspan=\"1\" align=\"left\" class=\"normal_10AN\">\n";
    $this->salida .= "                          LAPSO CONTABLE";
    $this->salida .= "                       </td>";
    $this->salida .= "                       <td  align=\"left\" class=\"normal_10AN\">\n";
    $vector=$consulta->BuscarLapsos();                                                             //onkeyup=\"xajax_reqObtenerLiteral(document.getElementById('datos').value)\"
    //$this->salida .=$vector;
    if(count($vector)>0)
    {
      $this->salida .= "                         <select name=\"tip_lap\" class=\"select\" onchange=\"afinardias(this.value);limpiar600();activar(this.value);\">";//
      $this->salida .= "                           <option value=\"0\" selected>SELECCIONAR</option> \n";
      for($i=0;$i<count($vector);$i++)
      {
        $this->salida .= "                           <option value=\"".$vector[$i]['lapso']."\">".$vector[$i]['lapso']."</option> \n";
      }
      $this->salida .= "                         </select>\n";
    }
    else
    $this->salida .= "                          <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"> La tabla cg_lapsos_contables no contiene registros</div>\n";
    $this->salida .= "                       </td>";
    $this->salida .= "                        <td id=\"diauno\" align=\"LEFT\" class=\"normal_10AN\">\n";
    //if(count($vector)>0)
    //{
      $this->salida .= "                         DIA INICIAL <select name=\"dia_primario\" class=\"select\" onchange=\"\" disabled>";
      $this->salida .= "                           <option value=\"0\" selected>--</option> \n";
      //for($i=0;$i<count($vector);$i++)
      //{
       // $this->salida .= "                           <option value=\"".$vector[$i]['lapso']."\">".$vector[$i]['lapso']."</option> \n";
      //}
      $this->salida .= "                         </select>\n";
    //}
    $this->salida .= "                        </td>";
    $this->salida .= "                        <td id=\"diados\" align=\"center\" class=\"normal_10AN\">\n";
    //if(count($vector)>0)
    //{
      $this->salida .= "                         DIA FINAL <select name=\"dia_segundario\" class=\"select\" onchange=\"\" disabled>";
      $this->salida .= "                           <option value=\"0\" selected>--</option> \n";
      //for($i=0;$i<count($vector);$i++)
      //{
       // $this->salida .= "                           <option value=\"".$vector[$i]['lapso']."\">".$vector[$i]['lapso']."</option> \n";
      //}
      $this->salida .= "                         </select>\n";
    //}
    $this->salida .= "                        </td>";
    $this->salida .= "                     </tr>";
    $this->salida .= "                      <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                        <td align=\"center\" COLSPAN='5'>\n";
    $this->salida .= "                          <input type=\"button\" name=\"generainter\" class=\"input-submit\" value=\"CONSULTAR DOCUMENTOS\" disabled onclick=\"LlamarRevi('".SessionGetVar("EMPRESA")."',document.interface.tip_lap.value,document.interface.dia_primario.value,document.interface.dia_segundario.value);\">\n";
    $this->salida .= "                        </td>";
    $this->salida .= "                      </tr>";
//     $this->salida .= "                      <tr class=\"modulo_list_claro\">\n";
//     $this->salida .= "                        <td colspan=\"4\" align=\"center\" class=\"button\">\n";
//     $this->salida .= "                          <input type=\"button\" name=\"generac\" class=\"input-submit\" value=\"GENERAR INTERFAZ\" disabled onclick=\"genint(document.interface.tip_int.value,document.interface.tip_lap.value);\">\n";
//     $this->salida .= "                        </td>";
//     $this->salida .= "                      </tr>";
    $this->salida .= "                    </table>";
    $this->salida .= "              </form>";        
    $this->salida .= "               <br>";
    $this->salida .= " </div>\n"; 
    $this->salida .= "    <div id='error_interface' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id=\"interfaches\">";
    $this->salida .= "    </div>\n"; 
    $this->salida .= "               <br>";
    $this->salida .= "    </form\n"; 
   
    $this->salida.="<script language=\"javaScript\">
                function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }
    
                function LlamarDocu(pag)
                {
                  if(document.cons_docu.dia_ini.value=='--')
                  {
                   document.cons_docu.dia_ini.value=0;
                  }
                  if(document.cons_docu.dia_fin.value=='--')
                  {
                   document.cons_docu.dia_fin.value=0;
                  }
          
                  xajax_VerMovimiento(pag,document.cons_docu.tip_bus.value,document.cons_docu.dia_ini.value,document.cons_docu.dia_fin.value,document.cons_docu.tip_doc.value,document.cons_docu.pref.value);
                }
                
                function LlamarDocus(pag,lapso,dia1,dia2,tipdoc,prefijo)
                {
                  xajax_VerMovimiento(pag,lapso,dia1,dia2,tipdoc,prefijo);
                }
                function LlamarDocus1(pag,lapso,dia1,dia2,tipdoc,prefijo)
                {
                  xajax_VerMovimiento2(pag,lapso,dia1,dia2,tipdoc,prefijo);
                }
               function Lookb(b) 
                { 
                  if(b=='1')
                  { 
		    document.unocreate.ter_id.disabled=true;
                    document.unocreate.ter_id.value='';
                  }
                  else
                  {
                   document.unocreate.ter_id.disabled=false;
                  }
                }
    
    </script>";
    $MENUMOV=ModuloGetURL('app','InterfacesContables','user','MenuInterfaces');
    $this->salida .= "    <div id=\"volverterf\">";
    $this->salida .= "     <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";//".$this->action[0]."
    $this->salida .= "      <table align=\"center\" width=\"50%\">\n";
    $this->salida .= "       <tr>\n";
    $this->salida .= "        <td align=\"center\" colspan='7'>\n";
    $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $this->salida .= "        </td>\n";  
    $this->salida .= "       </tr>\n"; 
    $this->salida .= "      </table>\n"; 
    $this->salida .= "     </form>";
    $this->salida .= "    </div>";
    $this->salida .= ThemeCerrarTabla();
    return true;
    }
}  
?>