<?php
	/**************************************************************************************  
	* $Id: app_Cg_LapsosContables_userclasses_HTML.php,v 1.3 2007/04/17 15:03:02 jgomez Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.3 $ 
	* 
	* @autor Jaime G�ez 
	***************************************************************************************/
	
  IncludeClass("ClaseHTML");
	
  class app_Cg_LapsosContables_userclasses_HTML extends app_Cg_LapsosContables_user
	{
		function app_Cg_LapsosContables_userclasses_HTML(){}
		
    /***********************************************************************************
    * Muestra el menu de los empresas y centros de utilidad 
    * 
    * @access public 
    ***********************************************************************************/
     function SelectEmpresa()
     { 
      
       $this->MostrarEmpresas();
       $cuantas=$this->TodasEmpresas;
//        echo "<pre>";
//        var_dump($cuantas);
       if(count($cuantas)>1)
       {
         SessionSetVar("CUANTOCC",count($cuantas));
         $titulo[0]='EMPRESA';
         $url[0]='app';//contenedor 
         $url[1]='Cg_LapsosContables';//m�ulo 
         $url[2]='user';//clase 
         $url[3]='CrearLapso';//m�odo 
         $url[4]='Empresas';//indice del request
         $this->salida .= gui_theme_menu_acceso('SELECCIONE EMPRESA',$titulo,$this->TodasEmpresas,$url,ModuloGetURL('system','Menu'));
         return true;
       }
       else
       {
         SessionSetVar("CUANTOCC",count($cuantas));
         foreach($cuantas as $datos => $v)
          { 
            SessionSetVar("EMPRESA",$v['empresa_id']);
          }
          // $jukilo= SessionGetVar("EMPRESA");
         
         $this->CrearLapso();
       }
       
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
		
  

	/*********************************************************************************
  *FUNCION PRA LA CONSULTA DE DOCUMENTOS
  **********************************************************************************/	
  function CrearLapso()
  { 
      $descision=SessionGetVar("CUANTOCC");
      if($descision>1)
      {
       $this->CrearElementos();
      }
      $file ='app_modules/Cg_LapsosContables/RemoteXajax/definirLap.php';
      $this->SetXajax(array("Buscar_Lap","ChangeSwl","CreateLapso","Cambiar_ipc","CambiarBD_ipc"),$file); 
      IncludeClass("ClaseHTML");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $path = SessionGetVar("rutaImagenes");
      $consulta=new LapsosSQL();
      $this->IncludeJS('RemoteXajax/definirLap.js', $contenedor='app', $modulo='Cg_LapsosContables');
      
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
           $javaC .= "   function IniciarLapso(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'ContenedorLapso';\n";
           $javaC .= "       titulo1 = 'tituloLapso';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
           $javaC .= "       xResizeTo(Capa, 280, 125);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 260, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarLapso');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 260, 0);\n";
           $javaC .= "   }\n";
           $javaC .= "   function IniciarIPC(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'ContenedorIPC';\n";
           $javaC .= "       titulo1 = 'tituloIPC';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
           $javaC .= "       xResizeTo(Capa, 250, 140);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 230, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarIPC');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 230, 0);\n";
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
           $javaC1.= "xajax_Buscar_Lap('0','".SessionGetVar("EMPRESA")."','1','0','','');"; 
           $javaC1.= "</script>\n";
           $this->salida.= $javaC1;        
           
      
 
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se crea una cuenta. 
**********************************************************************************/
    $this->salida.="<div id='ContenedorLapso' class='d2Container' style=\"display:none;\">";
    $this->salida .= "    <div id='tituloLapso' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarLapso' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorLapso');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorLapso' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoLapso' class='d2Content'>\n";
    $this->salida .= "    </div>\n";
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/              

/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica el ipc
**********************************************************************************/
    $this->salida.="<div id='ContenedorIPC' class='d2Container' style=\"display:none;\">";
    $this->salida .= "    <div id='tituloIPC' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarIPC' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorIPC');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorIPC' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoIPC' class='d2Content'>\n";
    $this->salida .= "    </div>\n";
    $this->salida.="</div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/              
     
    
    $this->salida .= ThemeAbrirTabla("GESTOR DE LAPSOS CONTABLES"); 
    $this->salida .= "            <form name=\"cost_cen\" action=\"javascript:LlamarDocu(1);\" method=\"post\">\n";
    $this->salida .= "               <div id=\"ventana1\">\n";
    $this->salida .= "                <br>\n";         
    $this->salida .= "                   <table align=\"center\" BORDER='0' width=\"60%\">\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                      <td align=\"center\">\n";
    $nuevocen="javascript:MostrarCapa('ContenedorLapso');CreateLapso();IniciarLapso('CREAR NUEVO LAPSO');limpiarIndy();";
    $this->salida .= "                          <a  title=\"CREAR NUEVO LAPSO CONTABLE\" class=\"label_error\" href=\"".$nuevocen."\">CREAR NUEVO LAPSO CONTABLE</a>\n";
    $this->salida .= "                      </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                   </table>\n";
    $this->salida .= "                <br>\n";        
    $this->salida .= "    </div>\n"; 
    $this->salida .= "    <div id='errorpole' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id=\"lapxus\">";
    $this->salida .= "    </div>\n"; 
    $this->salida .= "</form>";       
   
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
                
                  xajax_VerMovimiento(pag,document.cons_docu.tip_bus.value,document.cons_docu.dia_ini.value,document.cons_docu.dia_fin.value,document.cons_docu.tip_doc.value,document.cons_docu.pref.value);
                }
                
                function LlamarDocus(pag,lapso,dia1,dia2,tipdoc,prefijo)
                {
                  xajax_VerMovimiento(pag,lapso,dia1,dia2,tipdoc,prefijo);
                }
                
               
    
    </script>";
    $descision=SessionGetVar("CUANTOCC");
    if($descision==1)
    {
     $MENUMOV=ModuloGetURL('system','Menu'); 
    }
    elseif($descision>1)
    {
     $MENUMOV=ModuloGetURL('app','Cg_Centros_de_Costo','user','main');
    }
    $this->salida .= "    <div id=\"volvercen_cos\">";
    $this->salida .= "     <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
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

/**************************************************************************************
*asignar a departamento
*************************************************************************************/
function AsignarDepto()
{
    $path = SessionGetVar("rutaImagenes");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");
    $consulta= new CentrosSQL();
    $this->IncludeJS('RemoteXajax/definirCen.js', $contenedor='app', $modulo='Cg_Centros_de_Costo');
    $file = 'app_modules/Cg_Centros_de_Costo/RemoteXajax/definirCen.php';
    $this->SetXajax(array("Buscar_los_depar","PonerCDC"),$file);    global $xajax;
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
           $javaC1.= "xajax_Buscar_los_depar('".SessionGetVar("EMPRESA")."','1','0','')";
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
      $this->salida .= ThemeAbrirTabla("ASIGNAR CENTROS DE COSTO A DEPARTAMENTO"); 
//       $this->SubMenu();
      
      $this->IncludeJS('RemoteXajax/definirCen.js', $contenedor='app', $modulo='Cg_Centros_de_Costo');
      $accion1=ModuloGetURL('app','Cg_Centros_de_Costo','user','AsignarDepto');
      $this->salida .= "            <form name=\"bus_dep\">\n";
    $this->salida .= "               <div id=\"ventana1\">\n";
    $this->salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $this->salida .= "                       <td colspan=\"5\" align=\"center\">\n";
    $this->salida .= "                          BUSCADOR DE DEPARTAMENTOS";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";         
    $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                       <td width=\"20%\" align=\"center\">\n";
    $this->salida .= "                          TIPO DE BUSQUEDA";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"25%\" align=\"center\">\n";
    $this->salida .= "                         <select name=\"tip_bus\" class=\"select\">";
    $this->salida .= "                           <option value=\"0\" selected>SELECCIONAR</option>\n";
    $this->salida .= "                           <option value=\"1\">DEPARTAMENTO ID</option> \n";
    $this->salida .= "                           <option value=\"2\">NOMBRE DEPARTAMENTO</option> \n";
    $this->salida .= "                         </select>\n";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
    $this->salida .= "                          DESCRIPCION";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"40%\" align=\"center\">\n";
    $this->salida .= "                          <input type=\"text\" class=\"input-text\" name=\"des_depto\" id=\"des_depto\"  size=\"50\" >\n";//onkeypress=\"return acceptNum(event)\"
    $this->salida .= "                       </td>\n";
    $this->salida .= "                        <td width=\"5%\" align=\"center\" class=\"normal_10AN\">\n";
    $this->salida .= "                          <input type=\"button\" class=\"input-submit\" value=\"BUSCAR\" onclick=\"xajax_Buscar_los_depar('".SessionGetVar("EMPRESA")."','1',document.bus_dep.tip_bus.value,document.bus_dep.des_depto.value);\">\n";
    $this->salida .= "                        </td>";
    $this->salida .= "                    </tr>\n";         
    $this->salida .= "                    </table>";
    $this->salida .= "              </form>";         
    $this->salida .= "              <br>";     
    
      $this->salida .= "    <div id=\"resultado_error1\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
      $this->salida .= "    </div>\n";
      $this->salida .= "    <div id=\"resal\">";
      $this->salida .= "    </div>\n";
     
    $this->salida .= "               <br>";
    $this->salida .= "               <br>";
    
    $accion179=ModuloGetURL('app','Cg_Centros_de_Costo','user','CrearCentro');
    
    $this->salida .= " <form name=\"volver\" action=\"".$accion179."\" method=\"post\">\n";//".$this->action[0]."
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
    $this->salida .= ThemeCerrarTabla();
    return true;
}



}
?>