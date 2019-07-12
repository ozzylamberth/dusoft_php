<?php
	/**************************************************************************************  
	* $Id: app_Cg_PlanesCuentas_userclasses_HTML.php,v 1.5 2007/02/01 20:29:40 jgomez Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.5 $ 
	* 
	* @autor Jaime Gómez 
	***************************************************************************************/
	IncludeClass("ClaseHTML");
	class app_Cg_PlanesCuentas_userclasses_HTML extends app_Cg_PlanesCuentas_user
	{
		function app_Cg_PlanesCuentas_userclasses_HTML(){	}
		/********************************************************************************** 
		* Función principal del módulo 
		* 
		* @return boolean
		***********************************************************************************/
		function main()
		{
			$this->FormaMenu();
			
      return true;
		}
		/************************************************************************************
		* Muestra el menu principal
		* 
		* @return boolean
		*************************************************************************************/
		
      
    
      
    /************************************************************************************/
    
    
    
    function FormaMenu()
    { 
      $estilo = "class=\"modulo_table_list_title\" style=\"text-align:left;text-indent: 6pt\" ";
      $this->IncludeJS('RemoteScripting');
      $this->IncludeJS('ScriptRemoto/definir.js', $contenedor='app', $modulo='Cg_PlanesCuentas');
      $this->IncludeJS("TabPaneLayout");
      $this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
      $this->IncludeJS("CrossBrowser");
      $this->SubMenu();
      $this->salida .= "<script>\n";
      $this->salida .= "  function Volver(objeto)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    objeto.action=\"".$this->actionOption1."\";\n";
      $this->salida .= "    objeto.submit();\n";
      $this->salida .= "  }\n";
      $this->salida .= "</script>\n";
      $this->salida .= ThemeAbrirTabla("PLAN DE CUENTAS"); 
      $this->salida .= "                 <div id=\"asig\" >\n";
      $this->salida .= "                 <table width=\"35%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                        MENU ";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->actionOption3 = ModuloGetURL('app','Cg_PlanesCuentas','user','CrearPlanCuentas');
      $this->salida .= "                       <a title=\"Crear Nueva Cuenta\" href=\"".$this->actionOption3."\">CREAR PLAN DE CUENTAS</a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";         
      $this->salida .= "                    <tr class=\"modulo_list_oscuro\">\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->actionOption2 = ModuloGetURL('app','Cg_PlanesCuentas','user','VerCuentas');
      $this->salida .= "                       <a title=\"Consultar Plan de Cuentas\" href=\"".$this->actionOption2."\">CONSULTAR PLAN DE CUENTAS</a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";         
      $this->salida .= "                    <tr class=\"modulo_list_claro\" >\n";
      $this->actionOption1 = ModuloGetURL('system','Menu');
      $this->salida .= "                      <form name=\"volver\" action=\"".$this->actionOption1."\" method=\"post\">\n";//".$this->action[0]."
      $this->salida .= "                         <td align=\"center\">\n";
      $this->salida .= "                          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
      $this->salida .= "                         </td>\n";
      $this->salida .= "                      </form>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                  </table>\n";
      $this->salida .= "                 </div>\n";
      $this->salida .= ThemeCerrarTabla();
      return true;
    
    
    
    
    
    }
    /************************************************************************************
    *tabla de cuentas
    *
    *************************************************************************************/
    
    
		
		function CrearPlanCuentas()
		{ $this->CrearElementos();
      IncludeClass("ClaseHTML");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      
      $path = SessionGetVar("rutaImagenes");
      //$this->Datos = SessionGetVar("PermisosModulos");
      $consulta= new CuentasSQL();
      
      
			$this->IncludeJS('RemoteScripting');
      $this->IncludeJS('ScriptRemoto/definir.js', $contenedor='app', $modulo='Cg_PlanesCuentas');
      $this->SubMenu();//".$accion."
      //$this->actionOption5 = ModuloGetURL('app','ModuloPermisos','user','FormaPerfiles',);
      $accion1=ModuloGetURL('app','Cg_PlanesCuentas','user','CrearPlanCuentas');
      $this->salida .= ThemeAbrirTabla("CREAR PLAN DE CUENTAS"); 
      //////////////////////////////
       $javaC = "<script>\n";
          $javaC .= "   var contenedor1=''\n";
          $javaC .= "   var titulo1=''\n";
          $javaC .= "   var hiZ = 2;\n";
          $javaC .= "   var DatosFactor = new Array();\n";
          $javaC .= "   var EnvioFactor = new Array();\n";
         
          $javaC .= "   function Iniciar(tit)\n";
          $javaC .= "   {\n";
          $javaC .= "       contenedor1 = 'ContenedorCapaAnular';\n";
          $javaC .= "       titulo1 = 'tituloAnul';\n";
          $javaC.= "       Capa = xGetElementById(contenedor1);\n";
          $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
          $javaC .= "       ele = xGetElementById(titulo1);\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
          $javaC .= "       ele = xGetElementById('cerrarAnul');\n";
          $javaC .= "       xResizeTo(ele, 20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function Iniciar2(tit)\n";
          $javaC .= "   {\n";
          $javaC .= "       contenedor1 = 'ContenedorMod';\n";
          $javaC .= "       titulo1 = 'tituloMod';\n";
          $javaC.= "       Capa = xGetElementById(contenedor1);\n";
          $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
          $javaC .= "       ele = xGetElementById(titulo1);\n";
          $javaC .= "       xResizeTo(ele, 280, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
          $javaC .= "       ele = xGetElementById('cerrarMod');\n";
          $javaC .= "       xResizeTo(ele, 20, 20);\n";
          $javaC .= "       xMoveTo(ele, 280, 0);\n";
          $javaC .= "   }\n";
          $javaC.= "</script>\n";
          $this->salida.= $javaC;
  //////////////////////////
    $javaC1 = "<script>\n";
         
          $javaC1 .= "   function Iniciar1(tit,cuenta)\n";
          $javaC1 .= "   {\n";
          $javaC1 .= "       contenedor1 = 'ContenedorTotal';\n";
          $javaC1 .= "       titulo1 = 'tituloTotal';\n";
          $javaC1 .= "       Capa = xGetElementById(contenedor1);\n";
          $javaC1 .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
          $javaC1 .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()+120);\n";
          $javaC1 .= "       ele = xGetElementById(titulo1);\n";
          $javaC1 .= "       xResizeTo(ele, 280, 20);\n";
          $javaC1 .= "       xMoveTo(ele, 0, 0);\n";
          $javaC1 .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
          $javaC1 .= "       ele = xGetElementById('cerrarTotal');\n";
          $javaC1 .= "       xResizeTo(ele, 20, 20);\n";
          $javaC1 .= "       xMoveTo(ele, 280, 0);\n";
          $javaC1 .= "   }\n";
          
          
          
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
          $javaC1.= "{\n;";
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
          $javaC1.= "function NextLevel(Elemento)\n";
          $javaC1.= "{\n";
          $javaC1.= "    document.hijo_cuenta.niv_hijo.value=Elemento;\n";          
          $javaC1.= "}\n";   
          
          $javaC1.= "</script>\n";
          $this->salida.= $javaC1;        
  /////////////////////////
          $accion500=ModuloGetURL('app','Cg_PlanesCuentas','user','CrearPlanCuentas');
      
           
 /**********************************************************************
 *VENTANA ENMERGENTE 1 CREAR CUENTAS DESDE CERO
 ***********************************************************************/          
          $this->salida.="<div id='ContenedorCapaAnular' class='d2Container' style=\"display:none\">";
          $this->salida .= "    <div id='tituloAnul' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarAnul' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCapaAnular');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorAnul' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
          $this->salida .= "    <div id='ContenidoCapaAnular'>\n";
          $this->salida .= "      <form name=\"nue_cuenta\"  action=\"".$accion500."\" method=\"post\">\n";
          $empresaid="01";
          $vec_nivel=array();
          $vec_nivel=$consulta->ConsultarNivelesSegunEmpresa($empresaid);
          $posiciones=count($vec_nivel);
          $tds=round((100/$posiciones));          
          $total=$tds*$posiciones;
          $this->salida .= "            <table width=\"'".$total."'%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "                    <td colspan=\"".$posiciones."\">NIVEL DE LA CUENTA</td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          for($i=0;$i<count($vec_nivel);$i++)
           { 
              
              $this->salida .= "                    <td width=\"'".$tds."'%\">\n";
              $this->salida .= "                       <B> ".$vec_nivel[$i]['nivel']."</B><input type=\"radio\" name=\"nivel\" value=\"".$vec_nivel[$i]['digitos']."\" ";
              if($vec_nivel[$i]['nivel']==1)         
              { 
                 $this->salida .= "onClick=\"Setar(this.value);ExigirTitulo();Limpiar();\">";
              }   
              if($vec_nivel[$i]['nivel']==count($vec_nivel))         
               { 
                 $this->salida .= "onClick=\"Setar(this.value);NoExigirTitulo();Limpiar(); ExigirMov();\">";
               }
              if($vec_nivel[$i]['nivel']!=count($vec_nivel) && $vec_nivel[$i]['nivel']!=1)         
               {
                 $this->salida .= "onClick=\"Setar(this.value);NoExigirTitulo();Limpiar(); NoExigirMov();\">"; 
               }   
                    
             $this->salida .= "                    </td>\n";
           }
          $this->salida .= "                </tr>\n";
          $this->salida .= "                </table>\n";
          $this->salida .= "            <table width=\"'".$total."'%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "                    <td colspan=\"6\">ATRIBUTOS DE LA CUENTA</td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                      <b> NUMERO CUENTA</b> \n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"4\" align=\"center\">\n";
          $this->salida .= "                      <input type=\"text\" class=\"input-text\" class=\"label_error\" name=\"numero\" id=\"numero\"  size=\"35\" onkeypress=\"return acceptNum(event)\" onClick=\"ExigirNivel()\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                      <b> DESCRIPCION</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"4\" align=\"center\">\n";
          $this->salida .= "                       <input type=\"text\" class=\"input-text\" name=\"descri\" size=\"35\" onclick=\"Limpiar()\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                      <b> TIPO</b>";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"tipos\" value=\"0\" onClick=\"Apagar();Limpiar();\"><b>TITULO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"tipos\" value=\"1\" onClick=\"Encender();Limpiar();\"><b>MOVIMIENTO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                      <b>NATURALEZA</b> \n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"nat\"value=\"D\"onClick=\"Limpiar()\"><b>DEBITO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"nat\" value=\"C\" onClick=\"Limpiar()\"><b>CREDITO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <b>CENTRO DE COSTO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\" style='text_indent:10pt'>\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc\" value=\"1\" onClick=\"Limpiar()\"><b>SI</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc\" value=\"0\" onClick=\"Limpiar()\"><b>NO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                      <b>TERCEROS</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter\"value=\"1\" onClick=\"Limpiar()\"><b>SI</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter\"value=\"0\" onClick=\"Limpiar()\"><b>NO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                      <b>DOCUMENTO CRUCE</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc\" value=\"1\" onClick=\"Limpiar()\"><b>SI</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc\" value=\"0\" onClick=\"Limpiar()\"><b>NO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                      <b>EXIGE RTF</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"rtf\" value=\"1\" onClick=\"Limpiar()\"><b>SI</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"rtf\" value=\"0\" onClick=\"Limpiar()\"><b>NO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"3\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\" onclick=\"Validar('".$empresaid."')\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"3\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"cancelar\" value=\"Cancelar\" onclick=\"javascript:Cerrar('ContenedorCapaAnular');\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "            </table>\n";
          $this->salida .= "        </form>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";
       
/*******************************************************************************
*Ventana emergente 2 aqui cuando crea una cuenta ya apartir de otra. 
**********************************************************************************/
      $this->salida.="<div id='ContenedorTotal' class='d2Container' style=\"display:none\">";
          $this->salida .= "    <div id='tituloTotal' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarTotal' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorTotal');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorTotal' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
          $this->salida .= "    <div id='ContenidoTotal'>\n";
          $this->salida .= "      <form name=\"hijo_cuenta\"  action=\"".$accion500."\" method=\"post\">\n";
          $empresaid="01";
          $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "                    <td colspan=\"6\">ATRIBUTOS DE LA CUENTA</td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" width=\"36%\"align=\"left\">\n";
          $this->salida .= "                      <b> NUMERO CUENTA</b> \n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                      <input type=\"hidden\" name=\"padre\">";
          $this->salida .= "                      <input type=\"hidden\" name=\"niv_hijo\">";
          $this->salida .= "                    <td colspan=\"2\" width=\"30%\" id=\"hcuenta\" align=\"right\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" width=\"34%\" align=\"left\">\n";
          $this->salida .= "                      <input type=\"text\" class=\"input-text\" class=\"label_error\" name=\"numero1\" id=\"numero1\"  size=\"15\" onkeypress=\"return acceptNum(event)\"onclick=\"Limpiar1()\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "            </table>\n";
          $this->salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                      <b> DESCRIPCION</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"4\" align=\"center\">\n";
          $this->salida .= "                       <input type=\"text\" class=\"input-text\" name=\"descri1\" size=\"35\" onclick=\"Limpiar1()\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                      <b> TIPO</b>";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"tipos1\" value=\"0\" onClick=\"Apagar1();Limpiar1();\"><b>TITULO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"tipos1\" value=\"1\" onClick=\"Encender1();Limpiar1();\"><b>MOVIMIENTO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                      <b>NATURALEZA</b> \n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"nat1\"value=\"D\"onClick=\"Limpiar1()\"><b>DEBITO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"nat1\" value=\"C\" onClick=\"Limpiar1()\"><b>CREDITO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <b>CENTRO DE COSTO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\" style='text_indent:10pt'>\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"1\" onClick=\"Limpiar1()\"><b>SI</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"0\" onClick=\"Limpiar1()\"><b>NO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                      <b>TERCEROS</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"1\" onClick=\"Limpiar1()\"><b>SI</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"0\" onClick=\"Limpiar1()\"><b>NO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                      <b>DOCUMENTO CRUCE</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"1\" onClick=\"Limpiar1()\"><b>SI</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"0\" onClick=\"Limpiar1()\"><b>NO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                      <b>EXIGE RTF</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"rtf1\" value=\"1\" onClick=\"Limpiar()\"><b>SI</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $this->salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"rtf1\" value=\"0\" onClick=\"Limpiar()\"><b>NO</b>\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "                <tr class=\"modulo_list_claro\">\n";
          $this->salida .= "                    <td colspan=\"3\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"aceptar1\" value=\"Aceptar\" onclick=\"Validar1('".$empresaid."',document.getElementById('numero1').maxLength,document.hijo_cuenta.niv_hijo.value)\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                    <td colspan=\"3\" align=\"center\">\n";
          $this->salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"cancelar1\" value=\"Cancelar\" onclick=\"javascript:Ok();Cerrar('ContenedorTotal');\">\n";
          $this->salida .= "                    </td>\n";
          $this->salida .= "                </tr>\n";
          $this->salida .= "            </table>\n";
          $this->salida .= "        </form>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="</div>";
/**************************************************************************************
*final de la ventana2
***********************************************************************************/      
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
          $this->salida.="<div id='ContenedorMod' class='d2Container' style=\"display:none\">";
          $this->salida .= "    <div id='tituloMod' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida .= "    <div id='cerrarMod' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorMod');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida .= "    <div id='errorMod' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
          $this->salida .= "    <div id='ContenidoMod'>\n";
          $this->salida .= "    </div>\n";     
          $this->salida.="      </div>";
/**************************************************************************************
*final de la ventana3
***********************************************************************************/            
      /////////////////////////////////
      $this->salida .= "                 <form name=\"cuentas\" action=\"".$accion1."\" method=\"post\">\n";
      $this->salida .= "                  <table width=\"80%\"  align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "                   <tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "                      <td align=\"center\" colspan='3'>\n";
      $this->salida .= "                         BUSCADOR DE CUENTAS ";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td align=\"center\">\n";
      $this->salida .= "                        TIPO DE BUSQUEDA <select name=\"tip_bus\" class=\"select\" id='tip_bus' onchange=\"Buscar(cuentas.tip_bus.value)\" >";
      if($_REQUEST['tip_bus']==1)
       $this->salida .= "                       <option value=\"1\" selected>Empiece Por</option> \n";
      else
       $this->salida .= "                       <option value=\"1\">Empiece Por</option> \n";
      if($_REQUEST['tip_bus']==2)
      $this->salida .= "                       <option value=\"2\" selected>Sea igual a</option> \n";
      else
      $this->salida .= "                       <option value=\"2\">Sea igual a</option> \n";
      if($_REQUEST['tip_bus']==3)
      $this->salida .= "                       <option value=\"3\" selected>Entre</option> \n";
      else
      $this->salida .= "                       <option value=\"3\">Entre</option> \n";
      if($_REQUEST['tip_bus']==4)
      $this->salida .= "                       <option value=\"4\" selected>TODAS</option> \n";
      else
      $this->salida .= "                       <option value=\"4\">TODAS</option> \n";
      $this->salida .= "                       </select>\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"center\" id=\"dos\"=>\n";
      if(!empty($_REQUEST['buscar']))
      { $this->salida .= "                         CUENTA <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" value=\"".$_REQUEST['buscar']."\" onkeypress=\"return acceptNum(event)\""; 
        
      }
      elseif(!empty($_REQUEST['buscar1']) && !empty($_REQUEST['buscar2']))
      {
        $this->salida .= "                         RANGO1 <input type=\"text\" class=\"input_text\" name=\"buscar1\"maxlength=\"52\" size\"52\" value=\"".$_REQUEST['buscar1']."\" onkeypress=\"return acceptNum(event)\""; 
        $this->salida .= "                   <BR>  RANGO2 <input type=\"text\" class=\"input_text\" name=\"buscar2\"maxlength=\"52\" size\"52\" value=\"".$_REQUEST['buscar2']."\" onkeypress=\"return acceptNum(event)\""; 
      }
      else
      { 
        $this->salida .= "                         CUENTA <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" onkeypress=\"return acceptNum(event)\""; 
      }   
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"center\">\n";
      $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"boton_bus\" value=\"BUSCAR\" onclick=\"Verificar()\">\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";         
      $this->salida .= "                 </table>\n";         
      $this->salida .= "                </form>\n";         
      $this->salida .= "                <br>\n";         
      $this->salida .= "                    <table width=\"100%\">\n";
      $this->salida .= "                    <tr colspan=\"8\">\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $javaAccionAnular ="javascript:MostrarCapa('ContenedorCapaAnular');Iniciar('CREAR NUEVA CUENTA');";
      $this->salida .= "                          <a  title=\"Crear nueva cuenta\" class=\"label_error\" href=\"".$javaAccionAnular."\"> CREAR NUEVA CUENTA </a>\n";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    </table>\n";
      $this->salida .= "                <br>\n";         
      $this->salida .= "                <div id=\"ventana0\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:13px;\">\n";
      $this->salida .= "                </div>\n";
     $tipo=$_REQUEST['tip_bus'];
     if($tipo==3 && $_POST['boton_bus'])
      { 
       $cuenta=$_REQUEST['buscar1'];
       $cuenta.="-".$_REQUEST['buscar2'];
      }      
     else
      {
        $cuenta=$_REQUEST['buscar'];
      }    
     $vector=$consulta->BuscarCuentasStip($_REQUEST['offset'],$tipo,$cuenta,$empresaid);
  if(count($vector)>0)
  { 
      $this->salida .= "                <div id=\"ventana1\">\n";
      $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "                      <td align=\"center\" width=\"13%\">\n";
      $this->salida .= "                        CUENTA Nº";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\"width=\"46%\">\n";
      $this->salida .= "                        DESCRIPCION";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='TIPO'>TP<a> ";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='NATURALEZA'>NAT<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='CENTRO DE COSTO'>CC<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='TERCEROS'>TER<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='ESTADO ACTIVO'>ACT<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='DOCUMENTO CRUCE'>DC<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='RETENCION EN LA FUENTE'>RTF<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"6%\">\n";
      $this->salida .= "                        <a title='Modificar'>MODIFICAR<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";         
   
   
   
   for($i=0;$i<sizeof($vector);$i++)
   {   
    
     
      $this->salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
      //$this->salida .= "                    <tr class=\"\" >\n";
     if($vector[$i]['sw_cuenta_movimiento']==0)
     {
      $this->salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
      $this->salida .= "                     ".$vector[$i]['cuenta']."";
      $nivel_h=$vector[$i]['nivel']+1;
      $nivel_hijito=$consulta->ConsultarNivelDigitos($empresaid,$nivel_h);                                                                                      
      $javaAccionAnular1 = "javascript:MostrarCapa('ContenedorTotal');Iniciar1('CREAR NUEVA CUENTA'); Traer(".$vector[$i]['cuenta'].");BuscarNivel('".$vector[$i]['empresa_id']."','".$vector[$i]['cuenta']."');NextLevel('".$nivel_hijito[0]['digitos']."')";
      $this->salida .= "                  <a title=\"Crear nueva cuenta\" href=\"".$javaAccionAnular1."\">(+)</a>\n";
      }
     else
     {
      $this->salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
      $this->salida .= "                     ".$vector[$i]['cuenta']."";
     } 
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"left\">\n";
      $this->salida .= "                        ".$vector[$i]['descripcion'];
      $this->salida .= "                      </td>\n";
      
      if($vector[$i]['sw_cuenta_movimiento']==1)
        { 
            $this->salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
            $this->salida .= "                         M";
        } 
       else
        {
            $this->salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
            $this->salida .= "                         T";
        }
      
      $this->salida .= "                      </td>\n";
      
       
       
       if($vector[$i]['sw_naturaleza']=='C')
        
        { 
            $this->salida .= "                      <td align=\"center\">\n";
            $this->salida .= "                         C";


        } 
       else
        {
           if($vector[$i]['sw_naturaleza']=='D')
           { 
            $this->salida .= "                      <td align=\"center\">\n";
            $this->salida .= "                         D";
           }
           else
           {
            $this->salida .= "                      <td align=\"center\">\n";
            $this->salida .= "                         ";
           }
           
        }

      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\">\n";
        
        if($vector[$i]['sw_centro_costo']==1)
        {
         $this->salida .= "                         <a>\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                         <a>\n";
        
        }
      
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\">\n";
        
        if($vector[$i]['sw_tercero']!='0')
        {
         $this->salida .= "                         <a>\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                         <a>\n";
        
        }

       $this->salida .= "                      </td>\n";
       $this->salida .= "                      <td align=\"center\">\n";
       
       if($vector[$i]['sw_estado']==0)
        
        { 
         $this->salida .= "                         <a>\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/delete.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                         <a>\n";
        } 
       elseif($vector[$i]['sw_estado']>=1)
        {
         $this->salida .= "                         <a>\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                         <a>\n";
        }
        
        
        

       $this->salida .= "                      </td>\n";
       $this->salida .= "                      <td align=\"center\">\n";
        
        if($vector[$i]['sw_documento_cruce']>=1)
        {
         $this->salida .= "                         <a>\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                         <a>\n";
        
        }
       
         $this->salida .= "                      </td>\n";
         $this->salida .= "                      <td align=\"center\">\n";
        
        if($vector[$i]['sw_impuesto_rtf']>=1)
        {
         $this->salida .= "                         <a>\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                         <a>\n";
        }
               
         $this->salida .= "                      </td>\n";
         
         $this->salida .= "                      <td align=\"center\">\n";
         $this->salida .= "                         <a title=\"Modificar\" href=\"javascript:ModificarCuenta('".$vector[$i]['cuenta']."','".$vector[$i]['empresa_id']."');MostrarCapa('ContenedorMod');Iniciar2('MODIFICAR CUENTA');\">\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                         <a>\n";
         $this->salida .= "                      </td>\n";
         $this->salida .= "                    </tr>\n";
     }   
      $this->salida .= "</table>\n";
      //$this->salida .= "  </form>\n"; 
      
      
      // "conteo".$consulta->conteo."pagact".$consulta->paginaActual."limite".$consulta->limit;
      
            
      $accion2=ModuloGetURL('app','Cg_PlanesCuentas','user','CrearPlanCuentas',array('tip_bus'=>$tipo,'buscar'=>$cuenta, 'offset'=>$_REQUEST['offset']));      
      $accionJ=ModuloGetURL('app','Cg_PlanesCuentas','user','CrearPlanCuentas',array('tip_bus'=>$tipo,'buscar'=>$cuenta));      
      $accion3=ModuloGetURL('app','Cg_PlanesCuentas','user','FormaMenu');
      
      
      if($_REQUEST['boton_bus']=="BUSCAR")
      { 
        
        $accion3=ModuloGetURL('app','Cg_PlanesCuentas','user','CrearPlanCuentas');      
        $accionJ=$accion3;
      }
      $Paginador=new ClaseHTML();
      $this->salida .= "".$Paginador->ObtenerPaginado($consulta->conteo,$consulta->paginaActual,$accion2,$consulta->limit);
      //$this->salida .= "               </div>\n";
      $this->salida .= "              <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $this->salida .= "                   <tr>\n";
      $this->salida .= "                      <td colspan='22' align=\"center\">\n";
      $this->salida .= "                        <B>CONVENCIONES</B>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                   </tr>\n";
      $this->salida .= "                   <tr>\n";
      $this->salida .= "                   <td>TP:</td><td>TIPO</td>";
      $this->salida .= "                   <td>NAT:</td><td>NATURALEZA</td>";
      $this->salida .= "                   <td>CC:</td><td>CENTRO DE COSTO</td>";
      $this->salida .= "                   <td>TER:</td><td>TERCEROS</td>";
      $this->salida .= "                   <td>ACT:</td><td>ACTIVA</td>";
      $this->salida .= "                   <td>DC:</td><td>DOCUMENTO CRUCE</td>";
      $this->salida .= "                   <td>T:</td><td>TITULO</td>";
      $this->salida .= "                   <td>M:</td><td>MOVIMIENTO</td>";
      $this->salida .= "                   <td>D:</td><td>DEBITO</td>";
      $this->salida .= "                   <td>C:</td><td>CREDITO</td>";
      $this->salida .= "                   <td>RTF:</td><td>RETENCION</td>";
      $this->salida .= "                   </tr>\n";
      $this->salida .= "              </table>\n";       
  
  }
  else
  { 
      $this->salida .= "              <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $this->salida .= "                   <tr class=\"label_error\">\n";
      $this->salida .= "                      <td colspan='2' align=\"center\">\n";
      $this->salida .= "                        NO HAY CUENTAS CON ESAS CARACTERISTICAS";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                   </tr>\n";
      $this->salida .= "              <table>";
  }     
              
      
      $this->salida .= "                 <table width='100%'>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                         <td  align=\"center\">\n";
      $this->salida .= "                          <form name=\"volverx\" action=\"".$accion3."\" method=\"post\">\n";//".$this->action[0]."
      $this->salida .= "                           <input type=\"hidden\" name=\"porfinnue\" value=\"".$accionJ."\">\n";
      $this->salida .= "                           <input type=\"hidden\" name=\"porfinvol\" value=\"".$accionJ."\">\n";
      $this->salida .= "                           <input type=\"submit\" class=\"input-submit\" value=\"Volver\" onclick=\"Colocar('".$tipo."','".$elemento1."','".$elemento2."')\">\n";
      $this->salida .= "                           </form>\n";
      $this->salida .= "                         </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                  </table>\n";
      $this->salida .= "               </div>\n";
      $this->salida .= "                <div id=\"ventana2\">\n";
      $this->salida .= "                </div>\n";
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
    
    
    
    
    
    
    
    
    
    
    
    
    
/*****************************************************************************/    
/***************************************************************************
*Funcion VerCuentas
***************************************************************************/
   function VerCuentas()
    { 
      $this->IncludeJS('RemoteScripting');
      $this->IncludeJS('ScriptRemoto/definir.js', $contenedor='app', $modulo='Cg_PlanesCuentas');
      $path = SessionGetVar("rutaImagenes");
      $empresaid="01";
      $this->CrearElementos();
      IncludeClass("ClaseHTML");
      $consulta= new CuentasSQL();
      $accion1=ModuloGetURL('app','Cg_PlanesCuentas','user','VerCuentas');
      $this->salida .= ThemeAbrirTabla("CONSULTAR PLAN DE CUENTAS");  
      $this->salida .= "                 <form name=\"cuentas_b\" action=\"".$accion1."\" method=\"post\">\n";
      $this->salida .= "                  <table width=\"80%\"  align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "                   <tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "                      <td align=\"center\" colspan='3'>\n";
      $this->salida .= "                         BUSCADOR DE CUENTAS ";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td align=\"center\">\n";
      $this->salida .= "                        TIPO DE BUSQUEDA <select name=\"tip_bus\" class=\"select\" id='tip_bus' onchange=\"Buscar_b(cuentas_b.tip_bus.value)\" >";
      if($_REQUEST['tip_bus']==1)
       $this->salida .= "                       <option value=\"1\" selected>Empiece Por</option> \n";
      else
       $this->salida .= "                       <option value=\"1\">Empiece Por</option> \n";
      if($_REQUEST['tip_bus']==2)
      $this->salida .= "                       <option value=\"2\" selected>Sea igual a</option> \n";
      else
      $this->salida .= "                       <option value=\"2\">Sea igual a</option> \n";
      if($_REQUEST['tip_bus']==3)
      $this->salida .= "                       <option value=\"3\" selected>Entre</option> \n";
      else
      $this->salida .= "                       <option value=\"3\">Entre</option> \n";
      if($_REQUEST['tip_bus']==4)
      $this->salida .= "                       <option value=\"4\" selected>TODAS</option> \n";
      else
      $this->salida .= "                       <option value=\"4\">TODAS</option> \n";
      $this->salida .= "                       </select>\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"center\" id=\"dos\"=>\n";
      if(!empty($_REQUEST['buscar']))
      { $this->salida .= "                         CUENTA <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" value=\"".$_REQUEST['buscar']."\""; 
      }
      elseif(!empty($_REQUEST['buscar1']) && !empty($_REQUEST['buscar2']))
      {
        $this->salida .= "                         RANGO1 <input type=\"text\" class=\"input_text\" name=\"buscar1\"maxlength=\"52\" size\"52\" value=\"".$_REQUEST['buscar1']."\""; 
        $this->salida .= "                   <BR>  RANGO2 <input type=\"text\" class=\"input_text\" name=\"buscar2\"maxlength=\"52\" size\"52\" value=\"".$_REQUEST['buscar2']."\""; 
      }
      else
      { 
        $this->salida .= "                         CUENTA <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\""; 
      }   
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"center\">\n";
      $this->salida .= "                        <input type=\"submit\" class=\"input-submit\" name=\"boton_bus\" value=\"BUSCAR\">\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";         
      $this->salida .= "                 </table>\n";         
      $this->salida .= "                </form>\n";         
      $this->salida .= "                <br>\n";         
      
      $tipo=$_REQUEST['tip_bus'];
     if($tipo==3 && $_POST['boton_bus'])
      { 
       $cuenta=$_REQUEST['buscar1'];
       $cuenta.="-".$_REQUEST['buscar2'];
      }      
     else
      {
        $cuenta=$_REQUEST['buscar'];
      }    
     
      $vector=$consulta->BuscarCuentasStip($_REQUEST['offset'],$tipo,$cuenta,$empresaid);
      $vector1=$consulta->BuscarCuentass($tipo,$cuenta,$empresaid);
      //SessionSetVar("vector",$vector1);
      //$reporte = new GetReports();
      //$mostrar = $reporte->GetJavaReport('app','Cg_PlanesCuentas','cuentas',
        //                          array('empresa'=>$empresaid,'tipo'=>$tipo,'cuenta'=>$cuenta),
        //                          array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      //$funcion =$reporte->GetJavaFunction();//aa../../../
      $this->salida .= "                    <table width=\"100%\">\n";
      $this->salida .= "                    <tr colspan=\"8\">\n";
      //$this->salida.="                         <td align=\"center\" class='label_error'><a href=\"javascript:$funcion\"> GENERAR REPORTE </a> <IMG src=\"".GetThemePath()."/images/imprimir.png\"> </td>";
      if($tipo=="")
       {
        $tipo=0;
       }
      
       if($cuenta=="")
       {
         $cuenta=0;
       }  
      $direccion="app_modules/Cg_PlanesCuentas/reports/html/reportecuentas.php";
      $this->salida.="                         <td align=\"center\" class='label_error'><a href=\"javascript:sreporte('".$direccion."','".$tipo."','".$cuenta."','".$empresaid."');\"> GENERAR REPORTE </a> <IMG src=\"".GetThemePath()."/images/imprimir.png\"> </td>";
      //$this->salida .= "$mostrar";
      $this->salida .= "                    <td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    </table>\n";
      $this->salida .= "                <br>\n";         
      
      $this->salida .= "                <div id=\"ventana0\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:13px;\">\n";
      $this->salida .= "                </div>\n";
     
     
  if(count($vector)>0)
  { 
      $this->salida .= "                <div id=\"ventana1\">\n";
      $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "                      <td align=\"center\" width=\"13%\">\n";
      $this->salida .= "                        CUENTA Nº";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\"width=\"52%\">\n";
      $this->salida .= "                        DESCRIPCION";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='TIPO'>TP<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='NATURALEZA'>NAT<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='CENTRO DE COSTO'>CC<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='TERCEROS'>TER<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='ESTADO ACTIVO'>ACT<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='DOCUMENTO CRUCE'>DC<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $this->salida .= "                        <a title='RETENCION EN LA FUENTE'>RTF<a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";         
   
   
   
   for($i=0;$i<sizeof($vector);$i++)
   {   
       
      $this->salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
      $this->salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
      $this->salida .= "                     ".$vector[$i]['cuenta']."";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"left\">\n";
      $this->salida .= "                        ".$vector[$i]['descripcion'];
      $this->salida .= "                      </td>\n";
      if($vector[$i]['sw_cuenta_movimiento']==1)
        { 
            $this->salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
            $this->salida .= "                         M";
        } 
       else
        {
            $this->salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
            $this->salida .= "                         T";
        }
      $this->salida .= "                      </td>\n";
      if($vector[$i]['sw_naturaleza']=='C')
       { 
            $this->salida .= "                      <td align=\"center\">\n";
            $this->salida .= "                         C";
       } 
      else
       {
         if($vector[$i]['sw_naturaleza']=='D')
           { 
             $this->salida .= "                      <td align=\"center\">\n";
             $this->salida .= "                         D";
           }
         else
           {
             $this->salida .= "                      <td align=\"center\">\n";
             $this->salida .= "                         ";
           }
       }
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\">\n";
        if($vector[$i]['sw_centro_costo']==1)
        {
          $this->salida .= "                         <a>\n";
          $this->salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
          $this->salida .= "                         <a>\n";
        }
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\">\n";
        if($vector[$i]['sw_tercero']==1)
        {
          $this->salida .= "                         <a>\n";
          $this->salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
          $this->salida .= "                         <a>\n";
        }
       $this->salida .= "                      </td>\n";
       $this->salida .= "                      <td align=\"center\">\n";
       if($vector[$i]['sw_estado']==0)
        { 
          $this->salida .= "                         <a>\n";
          $this->salida .= "                          <sub><img src=\"".$path."/images/delete.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
          $this->salida .= "                         <a>\n";
        } 
       elseif($vector[$i]['sw_estado']==1)
        {
         $this->salida .= "                         <a>\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                         <a>\n";
        }
       $this->salida .= "                      </td>\n";
       $this->salida .= "                      <td align=\"center\">\n";
       if($vector[$i]['sw_documento_cruce']==1)
        {
         $this->salida .= "                         <a>\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                         <a>\n";
        
        }
        $this->salida .= "                      <td align=\"center\">\n";
        if($vector[$i]['sw_impuesto_rtf']==1)
        {
         $this->salida .= "                         <a>\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                         <a>\n";
        
        }
        $this->salida .= "                      </td>\n";
       $this->salida .= "                    </tr>\n";
     }   
      $this->salida .= "</table>\n";
     // $this->salida .= "  </form>\n"; 
      
      $accion2=ModuloGetURL('app','Cg_PlanesCuentas','user','VerCuentas',array('tip_bus'=>$tipo,'buscar'=>$cuenta));      
      $accion3=ModuloGetURL('app','Cg_PlanesCuentas','user','FormaMenu');
      $tipos=$tipo;
      $Paginador=new ClaseHTML();
      $this->salida .= "".$Paginador->ObtenerPaginado($consulta->conteo,$consulta->paginaActual,$accion2,$consulta->limit);
      $this->salida .= "               </div>\n";
      $this->salida .= "              <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $this->salida .= "                   <tr>\n";
      $this->salida .= "                      <td colspan='22' align=\"center\">\n";
      $this->salida .= "                        <B>CONVENCIONES</B>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                   </tr>\n";
      $this->salida .= "                   <tr>\n";
      $this->salida .= "                   <td>TP:</td><td>TIPO</td>";
      $this->salida .= "                   <td>NAT:</td><td>NATURALEZA</td>";
      $this->salida .= "                   <td>CC:</td><td>CENTRO DE COSTO</td>";
      $this->salida .= "                   <td>TER:</td><td>TERCEROS</td>";
      $this->salida .= "                   <td>ACT:</td><td>ACTIVA</td>";
      $this->salida .= "                   <td>DC:</td><td>DOCUMENTO CRUCE</td>";
      $this->salida .= "                   <td>T:</td><td>TITULO</td>";
      $this->salida .= "                   <td>M:</td><td>MOVIMIENTO</td>";
      $this->salida .= "                   <td>D:</td><td>DEBITO</td>";
      $this->salida .= "                   <td>C:</td><td>CREDITO</td>";
      $this->salida .= "                   <td>RTF:</td><td>RETENCION</td>";
      $this->salida .= "                   </tr>\n";
      $this->salida .= "              </table>\n";       
  
  }
  else
  { 
      $this->salida .= "              <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $this->salida .= "                   <tr class=\"label_error\">\n";
      $this->salida .= "                      <td colspan='2' align=\"center\">\n";
      $this->salida .= "                        NO HAY CUENTAS CON ESAS CARACTERISTICAS";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                   </tr>\n";
      $this->salida .= "              <table>";
  }     
              
      $accion3=ModuloGetURL('app','Cg_PlanesCuentas','user','FormaMenu');
      $this->salida .= "                 <table width='100%'>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                         <td  align=\"center\">\n";
      $this->salida .= "                          <form name=\"volver\" action=\"".$accion3."\" method=\"post\">\n";//".$this->action[0]."
      $this->salida .= "                           <input type=\"submit\" class=\"input-submit\" value=\"Volver\" onclick=\"Colocar1('".$tipo."','".$elemento1."','".$elemento2."')\">\n";
      $this->salida .= "                           </form>\n";
      $this->salida .= "                         </td>\n";
      $this->salida .= "                    </tr>\n";
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
      
//       $this->salida .= "                    <tr>\n";
//       $this->salida.="                         <td colspan=\"6\" align=\"right\"><a href=\"javascript:$funcion\"> IMPRIMIR TODAS </a> <IMG src=\"".GetThemePath()."/images/imprimir.png\"> </td>";
//       
//       $this->salida .= "$mostrar";
//      $this->salida .= "                    </tr>\n";
      $this->salida .= "                  </table>\n";
      $this->salida .= ThemeCerrarTabla();//onsubmit=\"return Validar();\"
      
      return true;
    
    
    }
                                                                                                                                                                                                                                                                                                                                     //onsubmit=\"return Validar();\"
    
	}
?>