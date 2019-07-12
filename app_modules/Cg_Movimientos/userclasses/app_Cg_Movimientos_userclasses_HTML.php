<?php
	/**************************************************************************************  
	* $Id: app_Cg_Movimientos_userclasses_HTML.php,v 1.20 2008/03/28 23:03:20 cahenao Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.20 $ 
	* 
	* @autor Jaime G�ez 
	***************************************************************************************/
	
  IncludeClass("ClaseHTML");
	class app_Cg_Movimientos_userclasses_HTML extends app_Cg_Movimientos_user
	{
		function app_Cg_Movimientos_userclasses_HTML(){	}
		
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
       $url[1]='Cg_Movimientos';//m�ulo 
       $url[2]='user';//clase 
       $url[3]='MenuMovimientos';//m�odo 
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
		
    function MenuMovimientos()
    { 
    
      $this->CrearElementos();
      IncludeClass("ClaseHTML");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $path = SessionGetVar("rutaImagenes");
      $consulta= new MovimientosSQL();
      $this->salida .= ThemeAbrirTabla("MOVIMIENTOS"); 
      $CONSULTARDOC=ModuloGetURL('app','Cg_Movimientos','user','ConsultaDocumentos');
      $CREARDOC=ModuloGetURL('app','Cg_Movimientos','user','CreateDocumentos');
      $REVISARDOCS=ModuloGetURL('app','Cg_Movimientos','user','RevisarDocs');
      $javaAccionAnular = "javascript:MostrarCapa('ContenedorVer');";
      $this->salida .= "            <form name=\"menu_docu\" action=\"javascript:LlamarDocu(1);\" method=\"post\">\n";
      $this->salida .= "                 <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $this->salida .= "                   <tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                         MENU DE OPCIONES";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                   </tr>\n";         
      $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a  title=\"CONSULTAR DOCUMENTOS\" class=\"label_error\" href=\"".$CONSULTARDOC."\">CONSULTAR DOCUMENTOS</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a  title=\"CREAR DOCUMENTO MANUAL\" class=\"label_error\" href=\"".$CREARDOC."\">CREAR DOCUMENTO MANUAL</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a  title=\"REVISION DE DOCUMENTOS POR LAPSO CONTABLE\" class=\"label_error\" href=\"".$REVISARDOCS."\">REVISION DE DOCUMENTOS POR LAPSO CONTABLE</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      $this->salida .= "                   </table>";
      $this->salida .= "             </form>";        
      //$Exit = ModuloGetURL('system','Menu');
      $Exit = ModuloGetURL('app','Cg_Movimientos','user','SelectEmpresa');
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
  *FUNCION PRA LA CONSULTA DE DOCUMENTOS
  **********************************************************************************/	
		function ConsultaDocumentos()
		{ 
      $vectora=1;
      $file ='app_modules/Cg_Movimientos/RemoteXajax/definirMov.php';
      $this->SetXajax(array("Posicion_prefijo","Poner_prefijo","Poner_nume","Poner_mov_lap","VerMovimiento","VerMovimientox","DetalleMov","Vector","Quitare","VentanaOpciones","VerMovimiento2","ContabilizarDocsPorLapso","contasolo","contasuna","DteConta"),$file); 
      $this->CrearElementos();
      IncludeClass("ClaseHTML");
      //$this->SetJavaScripts('facporlap');
      //$this->SetJavaScripts('TotalPaciente');
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $path = SessionGetVar("rutaImagenes");
//       $this->SubMenu();
      //echo "que".$this->Datos['tipo_doc_general_id'];
      //ECHO "que11".SessionGetVar("EMPRESA");
      $consulta=new MovimientosSQL();
      //$this->IncludeJS('RemoteScripting');
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
           $javaC .= "       xResizeTo(Capa, 950, 'auto');\n";
           $javaC.= "        Capx = xGetElementById('ContenidoVer');\n"; 
           $javaC .= "       xResizeTo(Capx, 950, 400);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/40, xScrollTop()+30);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 930, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarVer');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 930, 0);\n";
           $javaC .= "   }\n";
           $javaC .= "   function IniciarCota(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'ContenedorCota';\n";
           $javaC .= "       titulo1 = 'tituloCota';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
           $javaC .= "       xResizeTo(Capa, 260, 150);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 240, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarCota');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 240, 0);\n";
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
    $this->salida.="<div id='ContenedorCota' class='d2Container' style=\"display:none;\">";
    $this->salida .= "    <div id='tituloCota' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarCota' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCota');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorCota' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoCota' class='d2Content'>\n";
    $this->salida .= "    </div>\n";
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/              
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
    $this->salida.="<div id='ContenedorVer' class='d2Container' style=\"display:none;\">";
    $this->salida .= "    <div id='tituloVer' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarVer' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorVer');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoVer' class='d2Content'>\n";
    $this->salida .= "    </div>\n";
    $this->salida .= "    <div id='Vector'>\n";
    
    $this->salida .= "    </div>\n";          
//     $this->salida .= "    <div id='puto'>\n";
//     
//     $this->salida .= "    </div>\n";       
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/              
      
    
    $this->salida .= ThemeAbrirTabla("CONSULTAR DOCUMENTOS"); 
    $this->salida .= "            <form name=\"cons_docu\" action=\"javascript:LlamarDocu(1);\" method=\"post\">\n";
    $this->salida .= "               <div id=\"ventana1\">\n";
    $this->salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $this->salida .= "                       <td colspan=\"4\" align=\"center\">\n";
    $this->salida .= "                          BUSCADOR DE MOVIMIENTOS";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";         
    $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                       <td colspan=\"1\" align=\"left\" class=\"normal_10AN\">\n";
    $this->salida .= "                          LAPSO CONTABLE";
    $this->salida .= "                       </td>";
    $this->salida .= "                       <td  align=\"left\" class=\"normal_10AN\">\n";
    $vector=$consulta->BuscarLapsos();                                                             //onkeyup=\"xajax_reqObtenerLiteral(document.getElementById('datos').value)\"
    //$this->salida .=$vector;
    if(count($vector)>0)
    {//ColocarDias($lapso,$div)
      $this->salida .= "                         <select name=\"tip_bus\" class=\"select\" onchange=\"afinar(cons_docu.tip_bus.value);\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
      $this->salida .= "                           <option value=\"0\" selected>SELECCIONAR</option> \n";
      
      
      //$fecha1=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia1;
      $a�=date("Ym");
      //$mes=date("m");
      
      for($i=0;$i<count($vector);$i++)
      { 
        $a�1=$vector[$i]['lapso'];
        //$mes1=substr($vector[$i]['lapso'],4,2);
        if($a�1<=$a�)
        {
//            if($mes1<=$mes)
//            {
             $this->salida .= "<option value=\"".$vector[$i]['lapso']."\">".$vector[$i]['lapso']."</option>\n";
           //}
          // elseif($a�1<$a� )
        }
      }
      $this->salida .= "                         </select>\n";
    }
    else
    $this->salida .= "                          <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"> La tabla cg_lapsos_contables no contiene registros</div>\n";
    $this->salida .= "                       </td>";
    $this->salida .= "                       <td align=\"left\" class=\"normal_10AN\" id=\"dias\">";
    $this->salida .= "                       DIA INICIAL";
    $this->salida .= "                         <select name=\"dia_ini\" class=\"select\" disabled onchange=\"afinarfinal(cons_docu.dia_ini.value)\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
    $this->salida .= "                           <option value=\"0\" selected>--</option> \n";
    for($i=1;$i<=31;$i++)
    {
     $this->salida .="                           <option value=\"".$i."\">".$i."</option> \n";
    }
     $this->salida .= "                         </select>\n";
    $this->salida .= "                       </td>";
    $this->salida .= "                       <td align=\"left\" class=\"normal_10AN\" id=\"dias1\">";
    $this->salida .= "                       DIA FINAL";
    $this->salida .= "                         <select name=\"dia_fin\" class=\"select\" disabled>";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
    $this->salida .= "                           <option value=\"0\" selected>--</option> \n";
    for($i=1;$i<=31;$i++)
    {
     $this->salida .="                           <option value=\"".$i."\">".$i."</option> \n";
    }
     $this->salida .= "                         </select>\n";
    $this->salida .= "                       </td>";
    $this->salida .= "                     </tr>";
    $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                       <td  colspan=\"1\" align=\"left\" class=\"normal_10AN\">\n";
    $this->salida .= "                         TIPO DOCUMENTO";
    $this->salida .= "                       </td>";
    $this->salida .= "                       <td colspan=\"3\" align=\"left\" class=\"normal_10AN\">\n";
    global $xajax;
    $vector=$consulta->TiposDocumento();
    if(count($vector)>0)
    {
      $this->salida .= "                         <select name=\"tip_doc\" class=\"select\" id='tip_doc' onchange=\"xajax_Poner_prefijo(document.cons_docu.tip_doc.value)\">";
      $this->salida .= "                           <option value=\"-1\" selected>SELECCIONAR</option> \n";
      for($i=0;$i<count($vector);$i++)
      {
        $this->salida .= "                           <option value=\"".$vector[$i]['tipo_doc_general_id']."\">".$vector[$i]['descripcion']."</option> \n";
      }
      $this->salida .= "                         </select>\n";
    }  
    else
    $this->salida .= "    <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"> La tabla tipos_doc_generales no contiene registros</div>\n"; 
    $this->salida .= "                       </td>";
    $this->salida .= "                     </tr>";
    $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                        <td  align=\"left\" class=\"normal_10AN\">\n";
    $this->salida .= "                           DOCUMENTO";
    $this->salida .= "                        </td>";
    $this->salida .= "                        <td  COLSPAN=3 align=\"left\" class=\"normal_10AN\" id=\"pre\">\n";
    $this->salida .= "                             <select name=\"pref\" class=\"select\">";//onchange=\"Poner_num(cons_docu.tip_doc.value)\"
    $this->salida .= "                                <option value=\"-1\" selected>SELECCIONAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
    $this->salida .= "                             </select>\n";
   // $this->salida .= "                        </td>";
    //$this->salida .= "                        <td  align=\"left\" class=\"normal_10AN\" id=\"pre\">\n";
    $this->salida .= "                           &nbsp;&nbsp;&nbsp;";
    $this->salida .= "                             <select name=\"prefixo\" class=\"select\">";//onchange=\"Poner_num(cons_docu.tip_doc.value)\"
    $this->salida .= "                                <option value=\"-1\" selected>&nbsp;-&nbsp;-&nbsp;</option> \n";
    $this->salida .= "                             </select>\n";
    $this->salida .= "                        </td>";
//     $this->salida .= "                        <td  align=\"center\" class=\"normal_10AN\">\n";
//     $this->salida .= "                          NUMERO";
//     $this->salida .= "                        </td>";
//     $this->salida .= "                        <td  align=\"center\" class=\"normal_10AN\" id=\"nume\">\n";
//     $this->salida .= "                                <input type=\"text\" class=\"input-text\" name=\"num\" id=\"nume\"  size=\"12\" onkeypress=\"return acceptNum(event)\" disabled >\n";
//     $this->salida .= "                        </td>";
    $this->salida .= "                      </tr>";
    $this->salida .= "                      <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                        <td colspan=\"4\" align=\"center\" class=\"normal_10AN\">\n";
    $this->salida .= "                          <input type=\"submit\" class=\"input-submit\" value=\"Buscar Documento\" >\n";
    $this->salida .= "                        </td>";
    $this->salida .= "                      </tr>";
    $this->salida .= "                    </table>";
    $this->salida .= "              </form>";        
    $this->salida .= "               <br>";
    $this->salida .= "            <form name=\"cons1_docu\" method=\"post\">\n";
    $this->salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $this->salida .= "                        <tr class=\"modulo_list_claro\">"; 
    $this->salida .= "                         <td  align=\"center\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                           BUSCAR DOCUMENTO";
    $this->salida .= "                        </td>";
    $this->salida .= "                         <td  align=\"center\" class=\"normal_10AN\">\n";
    $this->salida .= "                           PREFIJO";
    $vector=$consulta->Prefixo1();
    if(count($vector)>0)
    {
      $this->salida .= "                         <select name=\"prefijos\" class=\"select\">";
      $this->salida .= "                           <option value=\"-1\" selected>SELECCIONAR</option> \n";
      for($i=0;$i<count($vector);$i++)
      {
        $this->salida .= "                           <option value=\"".$vector[$i]['prefijo']."\">".$vector[$i]['prefijo']."</option> \n";
      }
      $this->salida .= "                         </select>\n";
    }  
    else
    $this->salida .= "             <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"> no hay prefijos</div>\n";
    $this->salida .= "                        </td>";
    
    $this->salida .= "                        <td  align=\"center\" class=\"normal_10AN\">\n";
    $this->salida .= "                          NUMERO";
    $this->salida .= "                          <input type=\"text\" class=\"input-text\" name=\"numeroy\" id=\"nume\"  size=\"12\" onkeypress=\"return acceptNum(event)\">\n";
    $this->salida .= "                        </td>";
    $this->salida .= "                        <td colspan=\"4\" align=\"center\" class=\"normal_10AN\">\n";
    $this->salida .= "                          <input type=\"button\" class=\"input-submit\" value=\"Buscar Documento\" onclick=\"xajax_VerMovimientox('1',document.cons1_docu.prefijos.value,document.cons1_docu.numeroy.value)\">\n";
    $this->salida .= "                        </td>";
    $this->salida .= "                      </tr>";
    $this->salida .= "                    </table>";
    //$this->salida .= "              </form>";        
    $this->salida .= " </div>\n"; 
    $this->salida .= "    <div id=\"movimientos\">";
    $this->salida .= "    </div>\n"; 
    $this->salida .= "    </form>\n"; 

    $this->salida.="<script language=\"javaScript\">
            var ban=0;
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
                     //alert(pag+document.cons_docu.tip_bus.value+document.cons_docu.dia_ini.value+document.cons_docu.dia_fin.value+document.cons_docu.tip_doc.value+document.cons_docu.pref.value);
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
                  { document.unocreate.ter_id.disabled=true;
                    document.unocreate.ter_id.value='';
                  }
                  else
                  {
                   document.unocreate.ter_id.disabled=false;
                  }
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
 *funcion para la revision de documentos por lapso
 $REVISARDOCS=ModuloGetURL('app','Cg_Movimientos','user','RevisarDocs');
*********************************************************************************/ 
    function RevisarDocs()
    { 
      
      $file ='app_modules/Cg_Movimientos/RemoteXajax/definirMov.php';
      $this->SetXajax(array("Revisionxx"),$file); 
      $this->CrearElementos();
      IncludeClass("ClaseHTML");
      //$this->SetJavaScripts('facporlap1');
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
    $a�=date("Ym");
    if(count($vector)>0)
    {
      $this->salida .= "                         <select name=\"tip_lapi\" class=\"select\" onchange=\"LlamarRevi('".SessionGetVar("EMPRESA")."',document.revi.tip_lapi.value);\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
      $this->salida .= "                           <option value=\"0\" selected>SELECCIONAR</option> \n";
      for($i=0;$i<count($vector);$i++)
      {
        $a�1=$vector[$i]['lapso'];
        if($a�1<=$a�)
        {
          $this->salida .= "                           <option value=\"".$vector[$i]['lapso']."\">".$vector[$i]['lapso']."</option> \n";
        }  
      }
      $this->salida .= "                         </select>\n";
    }
    else
    $this->salida .= "                          <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"> La tabla cg_lapsos_contables no contiene registros</div>\n";
    $this->salida .= "                       </td>";
    $this->salida .= "                     </tr>";
//     $this->salida .= "                      <tr class=\"modulo_list_claro\">\n";
//     $this->salida .= "                        <td colspan=\"4\" align=\"center\" class=\"normal_10AN\">\n";
//     $this->salida .= "                          <input type=\"submit\" class=\"input-submit\" value=\"Buscar Documentos\" >\n";
//     $this->salida .= "                        </td>";
//     $this->salida .= "                      </tr>";
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
    /***********************************************************************************
    * Muestra el menu de los empresas y centros de utilidad 
    * 
    * @access public 
    ***********************************************************************************/
     function CreateDocumentos()
     { 
      
       $this->CrearElementos();
       $this->MostrarDocus();
       $titulo[0]='TIPOS DE DOCUMENTOS';
       $url[0]='app';//contenedor 
       $url[1]='Cg_Movimientos';//m�ulo 
       $url[2]='user';//clase 
       $url[3]='FormaCrearDocumentos';//m�odo 
       $url[4]='Docus';//indice del request
       $this->salida .= gui_theme_menu_acceso('CREACION DOCUMENTO MANUAL',$titulo,$this->TipsDocumentos,$url,ModuloGetURL('app','Cg_Movimientos','user','MenuMovimientos'));
       return true;
     }

     
/******************************************************************************************
*Forma para crear documentos
*******************************************************************************************/    

 function FormaCrearDocumentos()
 { 
    $path = SessionGetVar("rutaImagenes");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");
    $consulta= new MovimientosSQL();
    //$this->IncludeJS('RemoteScripting');
    $this->IncludeJS('RemoteXajax/definirMov.js', $contenedor='app', $modulo='Cg_Movimientos');
    $file = 'app_modules/Cg_Movimientos/RemoteXajax/definirMov.php';
    $this->SetXajax(array("Nue_Movimiento","ColocarDescri","GuardarDocumento","Buscadorter","Mostrar_Ter","BorrarDocumentoDet","BorrarDocumentoDOC","CopiarCgDocs","CopiarDocumentoDOC","FechaStamp","ColocarDias","VentanaOpciones","BusUnTer","Cuadrar_ids_terceros","CrearUSA","Departamento2","Municipios","GuardarPersona","Guardar_Municipio","Guardar_Departamento","Guardar_DYM"),$file);    global $xajax;
    //$xajax->setFlag("debug",true);
      
           $javaC = "<script>\n";
           $javaC .= "   var contenedor1=''\n";
           $javaC .= "   var titulo1=''\n";
           $javaC .= "   var hiZ = 2;\n";
           $javaC .= "   var DatosFactor = new Array();\n";
           $javaC .= "   var EnvioFactor = new Array();\n";
           $javaC .= "   function Iniciar2(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'ContenedorMov1';\n";
           $javaC .= "       titulo1 = 'tituloMov1';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
           $javaC .= "       xResizeTo(Capa, 600, 430);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 580, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarMov1');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 580, 0);\n";
           $javaC .= "   }\n";
           $javaC .= "   function Iniciar200(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'Contenedorelid';\n";
           $javaC .= "       titulo1 = 'tituloelid';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
           $javaC .= "       xResizeTo(Capa, 260, 150);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 240, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarelid');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 240, 0);\n";
           $javaC .= "   }\n";
           $javaC .= "   function Iniciar250(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'Contenedorx';\n";
           $javaC .= "       titulo1 = 'titulox';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC .= "        Capa = xGetElementById(contenedor1);\n"; 
           $javaC .= "       xResizeTo(Capa, 260, 150);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 240, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarx');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 240, 0);\n";
           $javaC .= "   }\n";
           $javaC .= "   function IniciarUsu(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'ContenedorCre';\n";
           $javaC .= "       titulo1 = 'tituloCre';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
           $javaC .= "       xResizeTo(Capa, 500, 380);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+65);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 480, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarCre');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 480, 0);\n";
           $javaC .= "   }\n";
           $javaC .="</script>\n";
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
           $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";
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
           $javaC1.= "</script>\n";
           $this->salida.= $javaC1;        
                            
      
 
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
    $this->salida.="<div id='ContenedorMov1' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloMov1' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarMov1' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorMov1');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorMov1' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoMov1'>\n";
    //$this->salida .= "jaime ";
    $this->salida .= "    </div>\n";     
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/  
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
    $this->salida.="<div id='Contenedorelid' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloelid' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarelid' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('Contenedorelid');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorelid' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='Contenidoelid'>\n";
    $this->salida .= "    </div>\n";     
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/    
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
    $this->salida.="<div id='Contenedorx' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='titulox' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarx' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('Contenedorx');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorx' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='Contenidox'>\n";
    $this->salida .= "    </div>\n";     
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/    
/*******************************************************************************
*Ventana para crear tercero
**********************************************************************************/
    $this->salida.="<div id='ContenedorCre' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloCre' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarCre' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCre');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorCre' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoCre'>\n";
    $this->salida .= "    </div>\n";     
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/   
      $this->salida .= ThemeAbrirTabla("LISTA DE DOCUMENTOS"); 
      //$accion1=ModuloGetURL('app','Cg_Movimientos','user','FormaCrearDocumentos');
      
      $this->SubMenu();
      $Documento=$consulta->PrefijoWTip_doc($this->Datos['tipo_doc_general_id']);
      $accion1=ModuloGetURL('app','Cg_Movimientos','user','AdicionarMovimiento');
      $this->salida .= "    <div id=\"resultado_error1\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
      $this->salida .= "    </div>\n";
      if(count($Documento)==0)
      {
        $this->salida .= "    <div id=\"resultado_error1\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
        $this->salida .= "     NO HAY PREFIJOS CREADOS CON ESTE TIPO DE DOCUMENTOS"; 
        $this->salida .= "    </div>\n"; 
      }
      else
      {
          $this->salida .= "            <form name=\"unocreate\" action=\"".$accion1."\" method=\"post\">\n";
          $this->salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";         
          $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "                       <td  align=\"center\" colspan='7'>\n";
          $this->salida .= "                          NUEVO DOCUMENTO";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                    </tr>\n";
          $this->salida .= "                    <tr>\n";
          $this->salida .= "                       <td  width=\"13%\" align=\"left\" class=\"modulo_table_list_title\">\n";
          $this->salida .= "                       PREFIJO";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td width=\"20%\" align=\"left\" class=\"modulo_list_claro\">\n";
          if(count($Documento)==1)
          {
            $this->salida .= "                        ".$Documento[0]['prefijo']."";
            $this->salida .= "                          <input type=\"hidden\" name=\"prefijo\" value=\"".$Documento[0]['prefijo']."\">\n";
          }  
          if(count($Documento)>1)  
          {
              $this->salida .= "                         <select name=\"prefijo\" class=\"select\" onchange=\"xajax_ColocarDescri(unocreate.prefijo.value);limpiar();\">";
              $this->salida .= "                           <option value=\"1\" selected>SELECCIONAR</option> \n";
              for($i=0;$i<count($Documento);$i++)
                {
                  $this->salida .= "                           <option value=\"".$Documento[$i]['prefijo']."-".$Documento[$i]['documento_id']."\">".$Documento[$i]['prefijo']."</option> \n";
                }
                $this->salida .= "                         </select>\n";
          }  
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td width=\"30%\" align=\"left\" id=\"doc_descri\" class=\"modulo_list_claro\">\n";
          if(count($Documento)==1)
          {
            $this->salida .= "                         ".$this->Datos['tipo_doc_general_id']."";
          }
          else
          {
            $this->salida .= "                         &nbsp";
          }
          $this->salida .= "                      </td>\n";
          $this->salida .= "                       <td width=\"10%\" align=\"left\" COLSPAN='1' class=\"modulo_table_list_title\">\n";
          $this->salida .="                        LAPSO";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td width=\"13%\" align=\"left\" COLSPAN='1' class=\"modulo_list_claro\">\n";
          /////////////////////
           $today = getdate();
           $hoy=$today[mday]."-".$today[mon]."-".$today[year]; 
           $elapso=date("Ym");
           $this->salida .= "".$elapso."";  
            /*  [seconds] => 40
                [minutes] => 58
                [hours]   => 21
                [mday]    => 17
                [wday]    => 2
                [mon]     => 6
                [year]    => 2003
                [yday]    => 167
                [weekday] => Tuesday
                [month]   => June
                */
    ///////////////////////
          //$Granlapso=$consulta->GranLapso(SessionGetVar("EMPRESA"));
          //if(count($Granlapso)>0)  
          //{
         //     $this->salida .= "                         <select name=\"superlapso\" class=\"select\" onchange=\"xajax_ColocarDias(unocreate.superlapso.value);limpiar();\">";//
          //    $this->salida .= "                           <option value=\"1\" selected>SELECCIONAR</option> \n";
            //  for($i=0;$i<count($Granlapso);$i++)
              //  {
                //  $this->salida .="                           <option value=\"".$Granlapso[$i]['lapso']."\">".$Granlapso[$i]['lapso']."</option> \n";
               // }
             // $this->salida .="                         </select>\n";
          //}
          //$this->salida .="                        <input type=\"text\" class=\"input-text\" name=\"fecha_reg\" value=\"".$hoy."\" size=\"10\"><sub>".ReturnOpenCalendario("unocreate","fecha_reg","-")."</sub>";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td width=\"7%\" align=\"left\" COLSPAN='1' class=\"modulo_table_list_title\">\n";
          $this->salida .="                        DIA";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td width=\"7%\" align=\"left\" COLSPAN='1' class=\"modulo_list_claro\" id=\"diames\">\n";
          $diad=date("d");
          $this->salida .= "".$diad."";

//           if(count($Granlapso)>0)  
//           {
//               $this->salida .= "                         <select name=\"mesito\" class=\"select\" onchange=\"limpiar()\">";
//               $this->salida .= "                           <option value=\"0\" selected>---</option>\n";
//               for($i=1;$i<30;$i++)
//                 {
//                   $this->salida .="                           <option value=\"".$i."\">".$i."</option> \n";
//                 }
//               $this->salida .="                         </select>\n";
//           }
          $this->salida .= "                       </td>\n";
          $this->salida .= "                    </tr>\n";
          $this->salida .= "                  </table>\n";
          $this->salida .= "                  <table width=\"90%\" align=\"center\" class=\"modulo_table_list\"> \n";
          $this->salida .= "                    <tr>\n";
          $this->salida .= "                       <td  width='13%' align=\"left\" class=\"modulo_table_list_title\">\n";
          $this->salida .= "                        TERCERO ID";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                          <input type=\"hidden\" id=\"tercerito_tip\" name=\"tercerito_tip\" value=\"0\">\n";
          $this->salida .= "                          <input type=\"hidden\" id=\"tercerito\" name=\"tercerito\" value=\"0\">\n";
          $this->salida .= "                          <input type=\"hidden\" id=\"htmp_id\" name=\"htmp_id\" value=\"0\">\n";
          
          $this->salida .= "                       <td  width='5%'  align=\"left\" class=\"modulo_list_claro\" id=\"tipos_ids_terceroxa\"> \n";
          $this->salida .= "                         <select name=\"tipox_id\" class=\"select\" onchange=\"\">";
          
          $TiposTercerosId=$consulta->Terceros_id();    
              
              for($i=0;$i<count($TiposTercerosId);$i++)
                {
                  $this->salida .="                           <option value=\"".$TiposTercerosId[$i]['tipo_id_tercero']."\">".$TiposTercerosId[$i]['tipo_id_tercero']."</option> \n";
                }
           $this->salida .="                         </select>\n";
          $this->salida .= "                       </td>\n";
          
          $this->salida .= "                       <td  width='14%'  align=\"left\" class=\"modulo_list_claro\"> \n";
          $this->salida .= "                          <input type=\"text\" id=\"id_tercerox\" name=\"id_tercerox\" class=\"input-text\" onkeydown=\"recogerTeclab(event);\" onclick=\"CambiarAction();\" value=\"\">\n";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td  width='8%' align=\"left\" class=\"modulo_table_list_title\">\n";
          $this->salida .= "                        NOMBRE";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td  width='30%'  align=\"left\" class=\"modulo_list_claro\" id=\"td_terceros_nue_mov\"> \n";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                        <td width='30%' align=\"center\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
          $java = "javascript:limpiar();limpiar2(); MostrarCapa('ContenedorMov1');Bus_ter('1','0','0','0','ContenidoMov1','unocreate');Iniciar2('BUSCAR TERCERO');\"";
          $this->salida .= "                          <a  title=\"SELECIONAR TERCERO\" class=\"label_error\" href=\"".$java."\"> BUSCAR TERCERO</a>\n";
          //$this->salida .= "                          <input type=\"button\" class=\"input-submit\" name=\"tercero\" value=\"SELECCIONAR TERCERO\" onclick=\"limpiar2(); MostrarCapa('ContenedorMov1');xajax_Buscadorter('1','0','0','ContenidoMov1','unocreate');Iniciar2('CREAR DOCUMENTO NUEVO');\">\n";
          $this->salida .= "                        </td>";
          $this->salida .= "                      </tr>\n";
          $this->salida .= "                      <tr class=\"modulo_list_claro\" >\n";
          $this->salida .= "                        <td  colspan=\"6\" align=\"center\" class=\"normal_10AN\">\n";
          if(count($Documento)==1)
          {
            $this->salida .= "                          <input type=\"button\" class=\"input-submit\" name=\"tercero\" value=\"CREAR DOCUMENTO\" onclick=\"CambiarAction1('".$accion1."');limpiar();Validar2006('".$Documento[0]['prefijo']."-".$Documento[0]['documento_id']."',create.ter_id_nuedoc.value,'".$this->Datos['tipo_doc_general_id']."','".$elapso."','".$diad."');\">\n";            //unocreate.superlapso.value,unocreate.mesito.value
          }
          else
          {
            $this->salida .= "                          <input type=\"button\" class=\"input-submit\" name=\"tercero\" value=\"CREAR DOCUMENTO\" onclick=\"CambiarAction1('".$accion1."');limpiar();Validar2006(unocreate.prefijo.value,create.ter_id_nuedoc.value,'".$this->Datos['tipo_doc_general_id']."','".$elapso."','".$diad."');\">\n";//unocreate.superlapso.value,unocreate.mesito.value
          }
          $this->salida .= "                        </td>";
          $this->salida .= "                      </tr>";
          $this->salida .= "                    </table>";
          $this->salida .= "                  </form>";         
          $this->salida .= "                    <br>";
    }  
      
      $this->salida .= "    <div id=\"resultado_error\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
      $this->salida .= "    </div>\n";
      
      
    /////
    $Movimientos=$consulta->SacarCgMovcontable($this->Datos['tipo_doc_general_id']);    
    $this->salida .= "    <form name=\"create\" action=\"".$accion1."\" method=\"post\">\n";
    $this->salida .= "    <div id=\"formanueva\">";
    
   if(count($Movimientos)>0)
   { 
    
    $this->salida .= "                 <table width=\"92%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $this->salida .= "                       <td width=\"4%\" align=\"center\">\n";
    $this->salida .= "                          DOCUMENTO";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"13%\" align=\"center\">\n";
    $this->salida .= "                          FECHA DOCUMENTO";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"11%\" align=\"center\">\n";
    $this->salida .= "                          TOTAL DEBITO";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"11%\" align=\"center\">\n";
    $this->salida .= "                          TOTAL CREDITO";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"7%\" align=\"center\">\n";
    $this->salida .= "                          PREFIJO";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"14%\" align=\"center\">\n";
    $this->salida .= "                          TERCERO_ID";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"17%\" align=\"center\">\n";
    $this->salida .= "                          NOMBRE";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"5%\" align=\"center\">\n";
    $this->salida .= "                          ADICIONAR";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"5%\" align=\"center\">\n";
    $this->salida .= "                          ELIMINAR";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"5%\" align=\"center\">\n";
    $this->salida .= "                          CERRAR";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";         
    for($i=0;$i<count($Movimientos);$i++)
    {  
      $this->salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
      $this->salida .= "                       <td align=\"center\">\n";
      $this->salida .= "                        DC".$Movimientos[$i]['tmp_id']."";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"center\">\n";
      $this->salida .= "                          ".$Movimientos[$i]['fecha_documento']."";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"right\">\n";
      $this->salida .= "                          ".FormatoValor($Movimientos[$i]['total_debitos'])."";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"right\">\n";
      $this->salida .= "                          ".FormatoValor($Movimientos[$i]['total_creditos'])."";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"center\">\n";
      $this->salida .= "                          ".$Movimientos[$i]['prefijo']."";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"left\">\n";
      $this->salida .= "                          ".$Movimientos[$i]['tipo_id_tercero']."-".$Movimientos[$i]['tercero_id']."";
      $this->salida .= "                       </td>\n";
      $nombre=$consulta->Nombre($Movimientos[$i]['tercero_id']);
      $this->salida .= "                      <td align=\"left\">\n";
      $this->salida .= "                        <a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,18)."";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                       <td  align=\"center\">\n";
      $AdicionarMovimiento=ModuloGetURL('app','Cg_Movimientos','user','AdicionarMovimiento',array('Prefijo1'=>$Movimientos[$i]['prefijo'],'tmp_id'=>$Movimientos[$i]['tmp_id'],'tip_id_ter'=>$Movimientos[$i]['tipo_id_tercero'],'ter_id'=>$Movimientos[$i]['tercero_id']));
      $this->salida .= "                         <a title='ADICIONAR MOVIMIENTO' href=\"".$AdicionarMovimiento."\">\n";
      $this->salida .= "                          <sub><img src=\"".$path."/images/news.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      $this->salida .= "                         </a>\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"center\">\n";
      $javit = "javascript:MostrarCapa('Contenedorelid');BorrarDoc_d('".$Movimientos[$i]['tmp_id']."','".$this->Datos['tipo_doc_general_id']."');Iniciar200('ELIMINAR DOCUMENTO');";
      $this->salida .= "                         <a title='ELIMINAR DOCUMENTO' class=\"label_error\" href=\"".$javit."\">\n";
      $this->salida .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      $this->salida .= "                         </a>\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"center\">\n";
      if($Movimientos[$i]['total_debitos']==$Movimientos[$i]['total_creditos'] && $Movimientos[$i]['total_debitos']>0 && $Movimientos[$i]['total_creditos']>0)
      {  
         $javitu = "javascript:MostrarCapa('Contenedorx');CerrarDoc_d('".$Movimientos[$i]['tmp_id']."','".$this->Datos['tipo_doc_general_id']."','".$Movimientos[$i]['prefijo']."');Iniciar250('CERRAR DOCUMENTO');";
         $this->salida .= "                         <a title='CERRAR DOCUMENTO' class=\"label_error\" href=\"".$javitu."\">\n";
         $this->salida .= "                           <sub><img src=\"".$path."/images/pcopiar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
         $this->salida .= "                         </a>\n";
      }
      else
      $this->salida .= "                       &nbsp";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
    }
      $this->salida .= "                 </table>";        
      
   } 
   else
   {
     $this->salida .= "    <div id=\"tabla_error\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
     $this->salida .= "      NO HAY DOCUMENTOS CREADOS";
     $this->salida .= "    </div>\n";
   }
    
    $this->salida .= "            </div>\n";
    $this->salida .= "                   <input type=\"hidden\" id=\"ter_id_nuedoc\" name=\"ter_id_nuedoc\" value=\"0\">\n";
    $this->salida .= "                   <input type=\"hidden\" id=\"ter_nom_nue_doc\" name=\"ter_nom_nue_doc\" value=\"0\">";
    $this->salida .= "            </form>";        
    $this->salida .= "               <br>";
    $this->salida .= "               <br>";
    $MENUMOV=ModuloGetURL('app','Cg_Movimientos','user','CreateDocumentos');
    $this->salida .= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";//".$this->action[0]."
    $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "       <td align=\"center\" colspan='7'>\n";
    $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $this->salida .= "       </td>\n";  
    $this->salida .= "    </tr>\n"; 
    $this->salida .= "  </table>\n"; 
    $this->salida .= " </form>\n"; 
    $this->salida.="<script language=\"javaScript\">
                function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }
                </script>";
    $this->salida.="<script language=\"javaScript\">";
    $this->salida.= "function Lookb(b) 
                      { 
                        if(b=='1')
                        { document.unocreate.ter_id.disabled=true;
                          document.unocreate.ter_id.value='';
                        }
                        else
                        {
                         document.unocreate.ter_id.disabled=false;
                        }
                       } 
                      </script>";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }                                                                                                                                                                                                                                                                    

/***************************************************************************************
*funcion que sirve para adicionar movimientos tabla tmp_cg_mov_contable_d
***************************************************************************************/  



 function AdicionarMovimiento()
 { 
    $path = SessionGetVar("rutaImagenes");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");
    
    $consulta= new MovimientosSQL();
    //$this->IncludeJS('RemoteScripting');
    $this->IncludeJS('RemoteXajax/definirMov.js', $contenedor='app', $modulo='Cg_Movimientos');
    $file = 'app_modules/Cg_Movimientos/RemoteXajax/definirMov.php';
    $this->SetXajax(array("Nue_Movimiento","ColocarDescri","GuardarDocumento","BuscarCuenta","Buscadorter","BuscadorDC","Guardar_Mov","RefrescarTablaCgMov_d","BorrarMovimientoDetalle","EliminarMovx","Lapsus","Prefi","TablaxCuenta","Cuadrar_ids_terceros","BusUnTer","CrearUSA","Departamento2","Municipios","GuardarPersona","Guardar_Municipio","Guardar_Departamento","Guardar_DYM"),$file);  
    //$AdicionarMovimiento=ModuloGetURL('app','Cg_Movimientos','user','AdicionarMovimiento',array('tmp_id'=>$Movimientos[$i]['tmp_id'],'tip_id_ter'=>$Movimientos[$i]['prefijo'],'ter_id'=>$Movimientos[$i]['tercero_id']));
    
//     $tmp_id=$_REQUEST['tmp_id'];
//     $tip_id=$_REQUEST['tip_id_ter'];
//     $ter_id=$_REQUEST['ter_id'];
//  $this->salida.=$tmp_id."-".$tip_id."-".$ter_id;
    $javaC = "<script>\n";
    $javaC .= "   var contenedor1=''\n";
    $javaC .= "   var titulo1=''\n";
    $javaC .= "   var hiZ = 2;\n";
    $javaC .= "   var DatosFactor = new Array();\n";
    $javaC .= "   var EnvioFactor = new Array();\n";
    $javaC .= "   function Iniciar4(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorAddMov1';\n";
    $javaC .= "       titulo1 = 'tituloAddMov1';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 600, 400);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/11, xScrollTop()+100);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 580, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarAddMov1');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 580, 0);\n";
    $javaC .= "   }\n";
    $javaC .= "   function Iniciar1(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorTer';\n";
    $javaC .= "       titulo1 = 'tituloTer';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 600, 430);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 580, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarTer');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 580, 0);\n";
    $javaC .= "   }\n";
    $javaC .= "   function Iniciar3(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorDC';\n";
    $javaC .= "       titulo1 = 'tituloDC';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 600, 430);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 580, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarDC');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 580, 0);\n";
    $javaC .= "   }\n";
    $javaC .= "   function Iniciarbu(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'BuscarCuenta';\n";
    $javaC .= "       titulo1 = 'titulobu';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 600, 430);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 580, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarbu');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 580, 0);\n";
    $javaC .= "   }\n";
    $javaC .= "   function Iniciar45(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'Contenedoreli';\n";
    $javaC .= "       titulo1 = 'tituloeli';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 260, 150);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 240, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrareli');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 240, 0);\n";
    $javaC .= "   }\n";
    $javaC .= "   function IniciarUsu(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorCre';\n";
    $javaC .= "       titulo1 = 'tituloCre';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 500, 380);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+65);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 480, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarCre');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 480, 0);\n";
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
    $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";
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
    $javaC1.= "</script>\n";
    $this->salida.= $javaC1;        
                    
    
 
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
    $this->salida.="<div id='ContenedorAddMov1' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloAddMov1' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarAddMov1' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorAddMov1');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorAddMov1' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoAddMov1'>\n";
    $this->salida .= "    </div>\n";     
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/   
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
    $this->salida.="<div id='ContenedorTer' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloTer' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarTer' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorTer');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorTer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoTer'>\n";
    $this->salida .= "    </div>\n";     
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/   
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
    $this->salida.="<div id='ContenedorDC' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloDC' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarDC' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorDC');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorDC' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoDC'>\n";
    $this->salida .= "    </div>\n";     
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/   
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
    $this->salida.="<div id='Contenedoreli' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloeli' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrareli' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('Contenedoreli');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='erroreli' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='Contenidoeli'>\n";
    $this->salida .= "    </div>\n";     
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/   
/*******************************************************************************
*Ventana para crear tercero
**********************************************************************************/
    $this->salida.="<div id='ContenedorCre' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloCre' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarCre' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCre');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorCre' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoCre'>\n";
    $this->salida .= "    </div>\n";     
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/   

    
/*******************************************************************************
*Ventana emergente PARA LA BUSQUEDA DE CUENTAS. 
**********************************************************************************/
    $this->salida.="<div id='BuscarCuenta' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='titulobu' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarbu' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('BuscarCuenta');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorbu' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='Contenidobu'>\n";
    $accion1=ModuloGetURL('app','Cg_Movimientos','user','AdicionarMovimiento');
    $salida = "                 <form name=\"jukilo\" action=\"".$accion1."\" method=\"post\">\n";         
    $salida .= "                 <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $salida .= "                       <td COLSPAN='2' align=\"center\">\n";
    $salida .= "                          BUSCADOR DE CUENTAS";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $salida .= "                       <td width=\"35%\" align=\"center\">\n";
    $salida .= "                          TIPO DE BUSQUEDA";
    $salida .= "                       <select name=\"tip_busq\" class=\"select\" onchange=\"Aplicar(document.jukilo.tip_busq.value)\">";
    $salida .= "                           <option value=\"1\" SELECTED># CUENTA</option> \n";
    $salida .= "                           <option value=\"2\">DESCRIPCION</option> \n";
    $salida .= "                       </select>\n";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"55%\" align=\"left\" id=\"ventanatabla\">\n";
    $salida .= "                          DESCRIPCION";                                                                                                             
    $salida .= "                          <input type=\"text\" class=\"input-text\" name=\"cuentaups\" size=\"30\" onkeypress=\"return acceptNum(event);\" onkeydown=\"recogerTeclas(event)\" value=\"".$cuenta."\">\n";//
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    $salida .= "                </table>\n";
    $salida .= "                 </form>\n";
    $salida .= "                 <br>\n";
    $this->salida .=$salida;
    $this->salida .="        <div id=\"tabelos\">";
    $this->salida .="        </div>\n";
    $this->salida .= "    </div>\n";     
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/   

    
    $this->salida .= ThemeAbrirTabla("ADICIONAR MOVIMIENTO"); 
    $accion1=ModuloGetURL('app','Cg_Movimientos','user','AdicionarMovimiento');
    
    $this->salida .= "                 <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $this->salida .= "                       <td width=\"17%\" align=\"center\">\n";
    $this->salida .= "                          PREFIJO";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"17%\" align=\"center\">\n";
    $this->salida .= "                          TERCERO_ID";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"31%\" align=\"center\">\n";
    $this->salida .= "                          NOMBRE";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";         
    
      $this->salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
      $this->salida .= "                       <td align=\"center\">\n";
      $this->salida .= "                        ".$_REQUEST['Prefijo1']."";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"center\">\n";
      $this->salida .= "                          ".$_REQUEST['tip_id_ter']."-".$_REQUEST['ter_id'];
      $this->salida .= "                       </td>\n";
      $nombre=$consulta->Nombre($_REQUEST['ter_id']);
      $this->salida .= "                      <td align=\"left\">\n";
      $this->salida .= "                        <a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,40)."";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";
    
      $this->salida .= "                 </table>";    
   //$this->salida .= "                 <br>";    
    //$this->salida .= "                 <br>";    
    $this->salida .= "            <form name=\"add_movimiento\" action=\"".$accion1."\" method=\"post\">\n";
    $this->salida .= "                          <input type=\"hidden\"  name=\"alf_tipo_id\"  value=\"".$_REQUEST['tip_id_ter']."\">\n";//
    $this->salida .= "                          <input type=\"hidden\"  name=\"alf_ter_id\" value=\"".$_REQUEST['ter_id']."\" >\n";//
    $this->salida .= "                          <input type=\"hidden\"  name=\"alf_nom\" value=\"".$nombre[0]['nombre_tercero']."\" >\n";//
    $this->salida .= "                 <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td COLSPAN='4' align=\"center\" class=\"modulo_table_list_title\">\n"; 
    $this->salida .= "                          CREAR NUEVO MOVIMIENTO";
    $this->salida .= "                      </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td width=\"7%\" align=\"center\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          CUENTA";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"10%\" align=\"center\" class=\"modulo_list_claro\">\n";                                                         
    $this->salida .= "                          <input type=\"text\" class=\"input-text\" name=\"cuenta\" size=\"18\" onkeypress=\"return acceptNum(event);\" onkeydown=\"recogerTecla(event)\" onclick=\"limpiar500();\">\n";//
    $buscuenta = "javascript:MostrarCapa('BuscarCuenta');TablaCuentas('0','0','1');Iniciarbu('BUSCAR CUENTA');";
    $this->salida .= "                         <a title='BUSCAR CUENTA' class=\"label_error\" href=\"".$buscuenta."\">\n";
    $this->salida .= "                           <sub><img src=\"".$path."/images/auditoria.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
    $this->salida .= "                       </td>\n";
    //$this->salida .= "                       ";
    $this->salida .= "                       <td width=\"18%\" align=\"center\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          DESCRIPCION";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"30%\" align=\"center\" class=\"modulo_list_claro\" id=\"des_cuenta\">\n";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";         
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          VALOR $";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                          <input type=\"text\" class=\"input-text\" name=\"valor\" size=\"21\" onkeypress=\"porcen(); return acceptNum(event)\" onclick=\"limpiar500();\" disabled>\n";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td  align=\"left\" COLSPAN='2' class=\"modulo_list_claro\" id=\"radio_dc\">\n";
    $this->salida .= "                          DEBITO";
    $this->salida .= "                          <input type=\"radio\" class=\"input-text\" name=\"dc\" value=\"D\">\n";
    $this->salida .= "                          CREDITO";
    $this->salida .= "                          <input type=\"radio\" class=\"input-text\" name=\"dc\" value=\"C\">\n";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";         
    $this->salida .= "                   </table>\n";         
    $this->salida .= "                   <input type=\"hidden\" id=\"tipo_id_tercero_sel\" name=\"tipo_id_tercero_sel\" value=\"0\">\n";
    $this->salida .= "                   <input type=\"hidden\" id=\"id_tercero_sel\" name=\"id_tercero_sel\" value=\"0\">\n";
    $this->salida .= "                   <input type=\"hidden\" id=\"nombre_tercero_sel\" name=\"nombre_tercero_sel\" value=\"0\">\n";
    $this->salida .= "                   <input type=\"hidden\" name=\"fecha_doc_h\" value=\"0\">\n";
    $this->salida .= "                   <input type=\"hidden\" name=\"prefijo_h\" value=\"0\">\n";
    $this->salida .= "                   <input type=\"hidden\" name=\"numero_h\" value=\"0\">\n";
    $this->salida .= "                   <input type=\"hidden\" name=\"tip_ter_id_dc_h\" value=\"0\">\n";
    $this->salida .= "                   <input type=\"hidden\" name=\"tercero_id_dc_h\" value=\"0\">\n";
    $this->salida .= "                   <input type=\"hidden\" name=\"dc_id_h\" value=\"0\">\n";
    $this->salida .= "                   <input type=\"hidden\" name=\"porcentaje_rtf\" value=\"0\">\n";
    $this->salida .= "                   <input type=\"hidden\" name=\"base_rtf\" value=\"0\">\n";
    $this->salida .= "           <div id=\"cen_cost\">";
    $xsalida .= "                   <input type=\"hidden\" name=\"ban_cc\" value=\"0\">\n";
    $xsalida .= "                   <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $xsalida .= "                    <tr>\n";
    $xsalida .= "                       <td WIDTH=\"13%\" align=\"center\" style=\" background-color: #bbbbbb;font-family: sans_serif, sans_serif, Verdana, helvetica, Arial;    text-align: center; font-size: 10px; font-weight: bold; color: #FFFFFF\">\n";
    $xsalida .= "                                CENTRO DE COSTO";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                       <td  WIDTH=\"52%\" align=\"left\" colspan='2' class=\"modulo_list_claro\">\n";
    $Departamentos=$consulta->Departamentos();
     //echo "aaa".$Departamentos[0]['centro_de_costo'];
     if(!empty($Departamentos[0]['centro_de_costo_id']))  
     {
        $xsalida .= "                       <select name=\"departamentos\" class=\"select\" disabled onchange=\"\">";
        $xsalida .= "                           <option value=\"0\" selected>SELECCIONAR</option> \n";
//         for($i=0;$i<count($Departamentos);$i++)
//         { 
//           $xsalida .= "                           <option value=\"".$Departamentos[$i]['departamento']."\">".$Departamentos[$i]['descripcion']."</option> \n";
//         }
        $xsalida .= "                       </select>\n";
        $xsalida .= "                    </td>\n";
     } 
    $xsalida .= "                     </tr>\n";
    $xsalida .= "                   </table>\n";     
    $this->salida .=$xsalida;
    $this->salida .= "           </div>\n";
    $this->salida .= "           <div id=\"exi_ter\">";
                
               
                
                $zalida .= "                   <input type=\"hidden\" name=\"ban_ter\" value=\"0\">\n";
                $zalida .= "                   <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";         
                $zalida .= "                    <tr>\n";
                $zalida .= "                       <td width=\"15%\" align=\"center\" class=\"modulo_list_claro\" >\n";
                $zalida .= "                          <a  style=\"font-family: sans_serif, Verdana,helvetica, Arial;font-size: 10px;color: #100000;font-weight: bold\" href=\"#\">TERCERO</a>\n";
                $zalida .= "                       </td>\n";
                $zalida .= "                       <td width=\"15%\" align=\"center\" style=\" background-color: #bbbbbb;font-family: sans_serif, sans_serif, Verdana, helvetica, Arial;text-align: center;font-size: 10px;font-weight: bold;color: #FFFFFF\">\n";
                $zalida .= "                          TERCERO ID";
                $zalida .= "                       </td>\n";
                $zalida .= "                       <td width=\"5%\" align=\"center\" class=\"modulo_list_claro\" id=\"tercero_identic\">\n";
                $zalida .= "                       <select name=\"tipos_idx2\" class=\"select\" disabled onchange=\"\">";
                $zalida .= "                           <option value=\"0\" selected>NIT</option> \n";
                $zalida .= "                       </select>\n";
                $zalida .= "                       </td>\n";
                $zalida .= "                       <td width=\"25%\" align=\"center\" class=\"modulo_list_claro\" id=\"tercero_identi\">\n";
                $zalida .= "                          <input type=\"text\" class=\"input-text\" id=\"nom_terc\" name=\"nom_terc\" value=\"\" disabled>\n"; 
                $zalida .= "                       </td>\n";
                $zalida .= "                       <td width=\"40%\" align=\"center\" class=\"modulo_list_claro\" id=\"nombre_tercero\">\n";
                $zalida .= "                       </td>\n";
                $zalida .= "                   </tr>\n";
                $zalida .= "                   </table>\n";         
    $this->salida .=$zalida;
    $this->salida .= "           </div>\n";
    $this->salida .= "           <div id=\"doc_cruz\">";
                  $dalida .= "                   <input type=\"hidden\" name=\"ban_dc\" value=\"0\">\n";
                  $dalida .= "            <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";         
                  $dalida .= "               <tr>\n";
                  $dalida .= "                 <td width=\"15%\" align=\"center\" class=\"modulo_list_claro\">\n";
                  $dalida .= "                    <a  style=\"font-family: sans_serif, Verdana,helvetica, Arial;font-size: 10px;color: #100000;font-weight: bold\" href=\"#\"> DOCUMENTO</a>\n";
                  $dalida .= "                 </td>\n";
                  $dalida .= "                 <td width=\"14%\" align=\"center\" style=\" background-color: #bbbbbb;font-family: sans_serif, sans_serif, Verdana, helvetica, Arial;text-align: center;font-size: 10px;font-weight: bold;color: #FFFFFF\" >\n";
                  $dalida .= "                  FECHA ";
                  $dalida .= "                 </td>\n";
                  $dalida .= "                 <td width=\"11%\" align=\"center\" class=\"modulo_list_claro\" id=\"td_fecha_doc\">\n";
                  $dalida .= "                 </td>\n";
                  $dalida .= "                 <td width=\"10%\" align=\"center\" style=\" background-color: #bbbbbb;font-family: sans_serif, sans_serif, Verdana, helvetica, Arial;text-align: center;font-size: 10px;font-weight: bold;color: #FFFFFF\">\n";
                  $dalida .= "                  NUMERO";
                  $dalida .= "                 </td>\n";
                  $dalida .= "                 <td width=\"10%\" align=\"center\" class=\"modulo_list_claro\" id=\"td_prefijo\">\n";
                  $dalida .= "                 </td>\n";
                  $dalida .= "                 <td width=\"15%\" align=\"center\" style=\" background-color: #bbbbbb;font-family: sans_serif, sans_serif, Verdana, helvetica, Arial;text-align: center;font-size: 10px;font-weight: bold;color: #FFFFFF\" >\n";
                  $dalida .= "                  TERCERO ID";
                  $dalida .= "                 </td>\n";
                  $dalida .= "                 <td width=\"25%\" align=\"center\" class=\"modulo_list_claro\" id=\"td_tercero_id\">\n";
                  $dalida .= "                 </td>\n";
                  $dalida .= "               </tr>\n";
                  $dalida .= "           </table>\n";  
    $this->salida .=$dalida;
    $this->salida .="           </div>\n";
    $this->salida .="           <div id=\"sw_rtf\">";
    $this->salida .="              <input type=\"hidden\" name=\"s_rtf\" value=\"0\">\n";
    $this->salida .="           </div>\n";
    $this->salida .="                   <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $this->salida .="                       <tr>";   
    $this->salida .="                         <td width=\"9%\" align=\"center\" class=\"modulo_table_list_title\">";
    $this->salida .="                          DETALLE";
    $this->salida .="                         </td>";
    $this->salida .="                         <td width=\"56%\" align=\"LEFT\" class=\"modulo_list_claro\">";
    $this->salida .= "                          <input type=\"text\" class=\"input-text\" name=\"detalle\" size=\"105\" value=\"\" onclick=\"limpiar500();\">\n"; 
    $this->salida .="                         </td>";
    $this->salida .="                       </tr>";
    $this->salida .="                       <tr>";
    $this->salida .="                         <td colspan='2' align=\"center\" class=\"modulo_list_claro\">";
    $this->salida .= "                          <input type=\"button\" class=\"input-submit\" value=\"Crear Movimiento\" name=\"guardar_mov\" onclick=\"limpiar500();Verificarbdmov('".$_REQUEST['tmp_id']."','".SessionGetVar('EMPRESA')."');\">\n"; //Refrescar('".$_REQUEST['tmp_id']."');
    $this->salida .="                         </td>";
    $this->salida .="                       </tr>";
    $this->salida .="                     </table>";
    $this->salida .="                  </form>";
    $this->salida .="    <div id='error_en_mov' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $Movimientos_d=$consulta->Sacartmp_CgMovcontable_d($_REQUEST['tmp_id']);    
    $TOTALES=$consulta->Sacartmp_Cg_Mov_deb_cre($_REQUEST['tmp_id']);    
    $this->salida .="                  <div id='refresh'>";  
    //$this->salida .="ya".$Movimientos_d;
    if(!empty($Movimientos_d))
    {
    
    $this->salida .="                  <form name=menu_mov>";
    $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $this->salida .= "                       <td width=\"7%\" align=\"center\">\n";
    $this->salida .= "                          CUENTA";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"15%\" align=\"center\">\n";
    $this->salida .= "                          DETALLE";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"8%\" align=\"center\">\n";
    $this->salida .= "                         <a title='DOCUMENTO CRUCE'>\n";
    $this->salida .= "                          DC";
    $this->salida .= "                         <a>";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"14%\" align=\"center\">\n";
    $this->salida .= "                          TERCERO";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"15%\" align=\"center\">\n";
    $this->salida .= "                         <a title='NOMBRE TERCERO'>NOMBRE\n";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"14%\" align=\"center\">\n";
    $this->salida .= "                          CENTRO DE COSTO";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
    $this->salida .= "                          DEBITO";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
    $this->salida .= "                          CREDITO";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"5%\" align=\"center\">\n";
    $this->salida .= "                          <a title='INFORMACION RETENCION EN LA FUENTE'>RTF";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"2%\" align=\"center\">\n";
    $this->salida .= "                          X";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";         
    for($i=0;$i<count($Movimientos_d);$i++)
     { 
      $this->salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
      $this->salida .= "                       <td align=\"center\">\n";
      $nombreCuenta=$consulta->NombreCuenta($Movimientos_d[$i]['cuenta']);
      $this->salida .= "                        <a title='".$nombreCuenta[0]['descripcion']."'>".$Movimientos_d[$i]['cuenta']."</a>";
      $this->salida .= "                       </td>\n";
      //tmp_movimiento_id  documento_cruce_id  cuenta  tipo_id_tercero   tercero_id  debito  credito   detalle   departamento  
      $this->salida .= "                       <td align=\"left\">\n";
      if($Movimientos_d[$i]['detalle']=='0')
      $this->salida .= "                        &nbsp;";
      else
      $this->salida .= "                        ".$Movimientos_d[$i]['detalle']."";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"center\">\n";
      $prefijo=$consulta->Sacarprefi($Movimientos_d[$i]['documento_cruce_id']);    
      if(isset($prefijo[0]['prefijo']))
      $this->salida .= "                        ".$prefijo[0]['prefijo']."-".$prefijo[0]['numero']."";
      else
      $this->salida .= "                        &nbsp;";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"left\">\n";
      if($Movimientos_d[$i]['tipo_id_tercero']==NULL)
      $this->salida .= "                        &nbsp;";//
      else
      $this->salida .= "                        ".$Movimientos_d[$i]['tipo_id_tercero']."-".$Movimientos_d[$i]['tercero_id']."";
      $this->salida .= "                       </td>\n";
      $nombre=$consulta->Nombre($Movimientos_d[$i]['tercero_id']);
      $this->salida .= "                      <td align=\"left\">\n";
      $this->salida .= "                        <a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,18)."";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                       <td align=\"left\">\n";
      if($Movimientos_d[$i]['centro_de_costo_id']=='NULL')
      $this->salida .= "                        &nbsp;";
      else
      { 
       $depto=$consulta->Departamentos_d($Movimientos_d[$i]['centro_de_costo_id']);    
       
       $this->salida .="                        <a title='".$depto[0]['descripcion']."'>".$Movimientos_d[$i]['centro_de_costo_id']."";
       //$this->salida .= "                        ".$depto[0]['descripcion']."";
       //$this->salida .= "                        ".$Movimientos_d[$i]['centro_de_costo_id']."";
      }
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"right\">\n";
      $this->salida .= "                          ".FormatoValor($Movimientos_d[$i]['debito'])."";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"right\">\n";
      $this->salida .= "                          ".FormatoValor($Movimientos_d[$i]['credito'])."";
      $this->salida .= "                       </td>\n";
      
      $this->salida .= "                       <td  align=\"center\">\n";
     // $javita = "javascript:MostrarCapa('Contenedoreli');BorrarMov_d('".$Movimientos_d[$i]['tmp_movimiento_id']."','".$_REQUEST['tmp_id']."');Iniciar45('ELIMINAR MOVIMIENTO');";
      $this->salida .= "                         <a title='BASE &nbsp;".$Movimientos_d[$i]['base_rtf']."&nbsp; - &nbsp;PORCENTAJE &nbsp;".$Movimientos_d[$i]['porcentaje_rtf']."' class=\"label_error\">\n";
      $this->salida .= "                          <sub><img src=\"".$path."/images/informacion.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      $this->salida .= "                         </a>\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"center\">\n";
      $javita = "javascript:MostrarCapa('Contenedoreli');BorrarMov_d('".$Movimientos_d[$i]['tmp_movimiento_id']."','".$_REQUEST['tmp_id']."');Iniciar45('ELIMINAR MOVIMIENTO');";
      $this->salida .= "                         <a title='ELIMINAR MOVIMIENTO' class=\"label_error\" href=\"".$javita."\">\n";
      $this->salida .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      $this->salida .= "                         </a>\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      }
      $this->salida .= "                    <tr class=\"modulo_list_oscuro\">\n";
      $this->salida .= "                       <td  align=\"right\" colspan='6' class=\"normal_10AN\">\n";
      $this->salida .= "                          TOTAL";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"right\">\n";//class=\"normal_10AN\"
      $this->salida .= "                          ".FormatoValor($TOTALES[0]['total_debitos'])."";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"right\">\n";
      $this->salida .= "                          ".FormatoValor($TOTALES[0]['total_creditos'])."";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"right\">\n";
      $this->salida .= "                          &nbsp;";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"right\">\n";
      $this->salida .= "                          &nbsp;";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      
//       if($TOTALES[0]['total_debitos']-$TOTALES[0]['total_creditos']==0)
//       {
//         $this->salida .= "                       <td align=\"right\">\n";//class=\"normal_10AN\"
//         $this->salida .= "                         0";
//         $this->salida .= "                       </td>\n";
//         $this->salida .= "                       <td align=\"right\">\n";//class=\"normal_10AN\"
//         $this->salida .= "                         0";
//         $this->salida .= "                       </td>\n";
      
//       }
      if($TOTALES[0]['total_debitos']-$TOTALES[0]['total_creditos']<0)
      {
          $this->salida .= "                    <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "                       <td  align=\"right\" colspan='6' class=\"label_error\">\n";
          $this->salida .= "                          DIFERENCIA";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td align=\"right\">\n";//class=\"normal_10AN\"
          $this->salida .= "                          ".FormatoValor(abs($TOTALES[0]['total_debitos']-$TOTALES[0]['total_creditos']))."";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td align=\"right\">\n";//class=\"normal_10AN\"
          $this->salida .= "                          0";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td  align=\"right\">\n";
          $this->salida .= "                          &nbsp;";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td  align=\"right\">\n";
          $this->salida .= "                          &nbsp;";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                    </tr>\n";
      }
      elseif($TOTALES[0]['total_debitos']-$TOTALES[0]['total_creditos']>0)
      {
          $this->salida .= "                    <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "                       <td  align=\"right\" colspan='6' class=\"label_error\">\n";
          $this->salida .= "                          DIFERENCIA";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td align=\"right\">\n";//class=\"normal_10AN\"
          $this->salida .= "                          0";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td align=\"right\">\n";//class=\"normal_10AN\"
          $this->salida .= "                          ".FormatoValor(abs($TOTALES[0]['total_debitos']-$TOTALES[0]['total_creditos']))."";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td  align=\"right\">\n";
          $this->salida .= "                          &nbsp;";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td  align=\"right\">\n";
          $this->salida .= "                          &nbsp;";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                    </tr>\n";
      
      }
      
      $this->salida .= "                 </table>";        
      $this->salida .= "               </form>";        
      
    }
        else
        {
          $this->salida .= "    <div id=\"tabla_error\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
          $this->salida .= "      NO HAY MOVIMIENTOS CREADOS CON ESTE DOCUMENTO";
          $this->salida .= "    </div>\n";
        }  
   
   
    $this->salida .= "            </div>\n";
    $this->salida .= "               <br>";
     
    
    $MENUMOV=ModuloGetURL('app','Cg_Movimientos','user','FormaCrearDocumentos');
    $this->salida .= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
    $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "       <td align=\"center\" colspan='7'>\n";
    $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $this->salida .= "       </td>\n";  
    $this->salida .= "    </tr>\n"; 
    $this->salida .= "  </table>\n"; 
    $this->salida .= " </form>\n"; 
    $this->salida.="<script language=\"javaScript\">
      function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }
      
           
      </script>";
    $this->salida.="<script language=\"javaScript\">";
    $this->salida.= "function Lookb(b) 
                      { 
                        if(b=='1')
                        { document.unocreate.ter_id.disabled=true;
                          document.unocreate.ter_id.value='';
                        }
                        else
                        {
                        document.unocreate.ter_id.disabled=false;
                        }
                       } 
                      </script>";
    $this->salida .= ThemeCerrarTabla();
 
 
 
 
 return true;
 }    
 
 
 
}
?>