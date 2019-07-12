<?php
	/**************************************************************************************  
	* $Id: app_ModuloPermisos_userclasses_HTML.php,v 1.1 2006/10/10 14:27:44 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.1 $ 
	* 
	* @autor Jaime Gómez 
	***************************************************************************************/
	IncludeClass("ClaseHTML");
	class app_ModuloPermisos_userclasses_HTML extends app_ModuloPermisos_user
	{
		function app_ModuloPermisos_userclasses_HTML(){	}
		/********************************************************************************** 
		* Función principal del módulo 
		* 
		* @return boolean
		***********************************************************************************/
		function main()
		{
			//$this->FormaMenu();
			
      $this->FormaMostrarModulos();
      
      return true;
		}
		/************************************************************************************
		* Muestra el menu principal
		* 
		* @return boolean
		*************************************************************************************/
		
      
    /***********************************************************************************
    * Muestra el menu de los empresas y centros de utilidad 
    * 
    * @access public 
    ***********************************************************************************/
    function FormaMostrarModulos()
    { $this->CrearElementos();
      $this->MostrarModulos();
      $titulo[0]='MODULOS';
      $url[0]='app';//contenedor 
      $url[1]='ModuloPermisos';//módulo 
      $url[2]='user';//clase 
      $url[3]='FormaSubMenu';//método 
      $url[4]='PerModulos';//indice del request
      $this->salida .= gui_theme_menu_acceso('MODULOS - PERMISOS',$titulo,$this->Modulos,$url,ModuloGetURL('system','Menu'));
      return true;
    }
      
    /************************************************************************************/
    
    
    
    function FormaSubMenu()
    { 
      $estilo = "class=\"modulo_table_list_title\" style=\"text-align:left;text-indent: 6pt\" ";
      $this->IncludeJS('RemoteScripting');
      $this->IncludeJS('ScriptRemoto/definir.js', $contenedor='app', $modulo='ModuloPermisos');
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
      $this->salida .= ThemeAbrirTabla("OPCIONES MANEJO DE MODULOS"); 
      $this->salida .= "                 <div id=\"asig\" >\n";
      $this->salida .= "                 <table width=\"35%\" align=\"center\" class=\"modulo_table_title\">\n";
      $this->salida .= "                    <tr class=\"modulo_table_title\">\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                        MODULO-".strtoupper ($this->Datos['modulo'])." ";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                       <a title=\"Ir a Mantenimiento\" href=\"javascript:AsignarComponentes('".$this->Datos['modulo']."');\">MANTENIMIENTO</a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";         
      $this->salida .= "                    <tr class=\"modulo_list_oscuro\">\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->actionOption3 = ModuloGetURL('app','ModuloPermisos','user','AsignarPermisos');
      $this->salida .= "                       <a title=\"Asignar Permisos\" href=\"".$this->actionOption3."\">ASIGNAR PERMISOS</a>";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";         
      $this->salida .= "                    <tr class=\"modulo_list_claro\" >\n";
      $this->actionOption1 = ModuloGetURL('app','ModuloPermisos','user','FormaMostrarModulos');
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
    /************************************************************************************/
    
    
		
		function AsignarPermisos()
		{
      IncludeClass("ClaseHTML");
      $path = SessionGetVar("rutaImagenes");
      $this->Datos = SessionGetVar("PermisosModulos");
      $consulta= new PermisosSQL();
      $vector=$consulta->MostrarUsuariosPer($this->Datos['modulo'],$_REQUEST['offset']);
      //$this->CrearElementos();
			$this->IncludeJS('RemoteScripting');
      $this->IncludeJS('ScriptRemoto/definir.js', $contenedor='app', $modulo='ModuloPermisos');
      $this->IncludeJS("TabPaneLayout");
      $this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
      $this->IncludeJS("CrossBrowser");
      $this->SubMenu();
      $this->salida .= ThemeAbrirTabla("ASIGNACION DE PERMISOS"); 
      $this->salida .= "                 <form name=\"usuarios\">\n";
      $this->salida .= "                  <table width=\"80%\" align=\"center\" class=\"modulo_table_title\">\n";
      $this->salida .= "                   <tr class=\"modulo_table_title\">\n";
      $this->salida .= "                      <td align=\"center\" colspan='3'>\n";
      $this->salida .= "                         BUSCADOR DE USUARIOS ";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td align=\"center\">\n";
      $this->salida .= "                        TIPO <select name=\"tip_bus\" class=\"select\" id='tip_bus'>";
      $this->salida .= "                       <option value=\"1\">Uid</option> \n";
      $this->salida .= "                       <option value=\"2\">Login</option> \n";
      $this->salida .= "                       <option value=\"3\">Nombre Usuario</option> \n";
      $this->salida .= "                       </select>\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"center\">\n";
      $this->salida .= "                         DESCRPCION <input type=\"text\" name=\"buscar\"maxlength=\"52\" size\"52\""; 
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"center\">\n";
      $this->salida .= "                        <input type=\"button\" class=\"input-submit\" value=\"BUSCAR\" onclick=\"BuscarUsu('".$this->Datos['modulo']."')\">\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";         
      $this->salida .= "                    <tr class=\"class=\"modulo_table_title\"\">\n";
      $this->salida .= "                      <td align=\"center\" colspan='3'>\n";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                 </table>\n";         
      $this->salida .= "                </form>\n";         
      $this->salida .= "                <br>\n";         
      $this->salida .= "                <br>\n";         
      $this->salida .= "                <div id=\"asignacion\">\n";
      $this->salida .= "                <form name=\"usuarios1\">\n";         
      $this->salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_title\">\n";         
      $this->salida .= "                    <tr class=\"modulo_table_title\">\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                        USUARIO";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                        NOMBRE DEL USUARIO";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                        UID";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                        ESTADO";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                        PERFIL";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                        PERMISOS";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";         
   for($i=0;$i<count($vector);$i++)
   {   
    if($i%2==0) 
    { 
      $this->salida .= "                    <tr class=\"modulo_list_claro\" >\n";
      $this->salida .= "                      <td align=\"left\">\n";
      $this->salida .= "                     ".$vector[$i]['usuario']."";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"left\">\n";
      $this->salida .= "                        ".$vector[$i]['nombre'];
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"right\">\n";
      $this->salida .= "                        ".$vector[$i]['usuario_id'];
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" id=\"".$vector[$i]['usuario_id']."\">\n";
       if($vector[$i]['sw_estado']==0)
        { 
         $this->salida .= "                         <a title=\"CAMBIAR DE ESTADO\" href=\"javascript:MarcarUsuario('".$vector[$i]['usuario_id']."',usuarios1.numero".$vector[$i]['usuario_id'].".value);\">\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/inactivo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                          <input type=\"hidden\" name=\"numero".$vector[$i]['usuario_id']."\" value=\"0\">";
         $this->salida .= "                         <a>\n";
        } 
       else
        {
         $this->salida .= "                         <a title=\"CAMBIAR DE ESTADO\" href=\"javascript:MarcarUsuario('".$vector[$i]['usuario_id']."',usuarios1.numero".$vector[$i]['usuario_id'].".value);\">\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/activo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                          <input type=\"hidden\" name=\"numero".$vector[$i]['usuario_id']."\" value=\"".$vector[$i]['sw_estado']."\">";
         $this->salida .= "                         <a>\n";
        
        }
      
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"left\">\n";
      $this->salida .= "                        ".$vector[$i]['descripcion_perfil'];
      $this->salida .= "                      </td>\n";
      $this->actionOption5 = ModuloGetURL('app','ModuloPermisos','user','FormaPerfiles',array('UID'=>$vector[$i]['usuario_id'],"NOMBRE"=>$vector[$i]['nombre'],"USUARIO"=>$vector[$i]['usuario']));
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                         <a title=\"ASIGNAR PERFIL\" href=\"".$this->actionOption5."\">\n";
      $this->salida .= "                           PERMISOS";       
      $this->salida .= "                         <a>\n";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";
   }   
    if($i%2==1)  
    { $this->salida .= "                    <tr class=\"modulo_list_oscuro\" >\n";
      $this->salida .= "                      <td align=\"left\">\n";
      $this->salida .= "                       ".$vector[$i]['usuario'];
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"left\">\n";
      $this->salida .= "                        ".$vector[$i]['nombre'];
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"right\">\n";
      $this->salida .= "                        ".$vector[$i]['usuario_id'];
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"center\" id=\"".$vector[$i]['usuario_id']."\">\n";
       if($vector[$i]['sw_estado']==0)
        { 
         $this->salida .= "                         <a title=\"CAMBIAR DE ESTADO\" href=\"javascript:MarcarUsuario('".$vector[$i]['usuario_id']."',usuarios1.numero".$vector[$i]['usuario_id'].".value);\">\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/inactivo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                          <input type=\"hidden\" name=\"numero".$vector[$i]['usuario_id']."\" value=\"0\">";
         $this->salida .= "                         <a>\n";
        } 
       else
        {
         $this->salida .= "                         <a title=\"CAMBIAR DE ESTADO\" href=\"javascript:MarcarUsuario('".$vector[$i]['usuario_id']."',usuarios1.numero".$vector[$i]['usuario_id'].".value);\">\n";
         $this->salida .= "                          <sub><img src=\"".$path."/images/activo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $this->salida .= "                          <input type=\"hidden\" name=\"numero".$vector[$i]['usuario_id']."\" value=\"".$vector[$i]['sw_estado']."\">";
         $this->salida .= "                         <a>\n";
        
        }
      
      $this->salida .= "                      </td>\n";
      $this->salida .= "                      <td align=\"left\">\n";
      $this->salida .= "                        ".$vector[$i]['descripcion_perfil'];
      $this->salida .= "                      </td>\n";
      $this->actionOption5 = ModuloGetURL('app','ModuloPermisos','user','FormaPerfiles',array('UID'=>$vector[$i]['usuario_id'],"NOMBRE"=>$vector[$i]['nombre'],"USUARIO"=>$vector[$i]['usuario']));
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                         <a title=\"ASIGNAR PERFIL\" href=\"".$this->actionOption5."\">\n";
      $this->salida .= "                           PERMISOS";       
      $this->salida .= "                         <a>\n";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                    </tr>\n";
    }  
   }   
      
           
      $this->salida .= "                </table>\n";
      $accion=ModuloGetURL('app','ModuloPermisos','user','AsignarPermisos');
      $Paginador=new ClaseHTML();
      
      $this->salida .= "".$Paginador->ObtenerPaginado($consulta->conteo,$consulta->paginaActual,$accion,$consulta->limit);
      $this->salida .= "               </form>\n";
      $this->salida .= "               </div>\n";
      $this->salida .= "                 <table width='100%' >\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                         <td  align=\"center\">\n";
      $this->salida .= "                          <form name=\"volver\" action=\"".$this->actionOption1."\" method=\"post\">\n";//".$this->action[0]."
      $this->salida .= "                           <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
      $this->salida .= "                           </form>\n";
      $this->salida .= "                         </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                  </table>\n";
      $this->salida .= ThemeCerrarTabla();
      return true;
			
		}

    
    
    function FormaPerfiles()
    {
      IncludeClass("ClaseHTML");
      
      $this->Datos = SessionGetVar("PermisosModulos");
      $path = SessionGetVar("rutaImagenes");
      //echo "aqui".$this->Datos['modulo'];
      $consulta=new PermisosSQL();
      $vector=$consulta->ListarGruposModulo($this->Datos['modulo']); 
      $vector1=$consulta->ListarPerfilesModulo($this->Datos['modulo']);
      //$this->CrearElementos();
      $this->IncludeJS('RemoteScripting');
      $this->IncludeJS('ScriptRemoto/definir.js', $contenedor='app', $modulo='ModuloPermisos');
      $this->IncludeJS("TabPaneLayout");
      $this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
      $this->IncludeJS("CrossBrowser");
      $this->actionOption1 = ModuloGetURL('app','ModuloPermisos','user','AsignarPermisos');
      $this->salida .= "<script>\n";
      $this->salida .= "  function Volver(objeto)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    objeto.action=\"".$this->actionOption1."\";\n";
      $this->salida .= "    objeto.submit();\n";
      $this->salida .= "  }\n";
      $this->salida .= "</script>\n";
      $this->SubMenu();
      $this->salida .= ThemeAbrirTabla(" ASIGNACION DE PERFILES "); 
      $this->salida.="<form name=\"componentes1\">\n";              
       $this->salida.=" <table width=\"90%\" align=\"center\" class=\"modulo_table_title\">\n";
       $this->salida.= "                    <tr class=\"modulo_table_title\">\n";
       $this->salida.= "                      <td align=\"center\">\n";
       $this->salida.= "                         ASIGNACION DE PERFIL - ".$_REQUEST['NOMBRE']."";
       $this->salida.= "                      </td>\n";
       $this->salida.= "                    </tr>\n";
       $this->salida.= "                    <tr class=\"modulo_list_claro\">\n";
       $this->salida.= "                      <td colspan=\"3\" align=\"center\">\n";
       $this->salida.= "                         Perfil <select name=\"perfiles\" class=\"select\" id='perfiles' onChange=\"Llenarhidden();Llenarchecks(componentes1.perfiles.value);\">";//
       $perfilU=$consulta->Consultarperfil_idUsuario($_REQUEST['UID']);
       
       if(count($vector1)==0)
        { 
          $this->salida.="                         <option selected>No hay Perfiles en ese modulo</option>";
        }  
       else
        {
         if(empty($perfilU[0]['perfil_id']))
          {
            $this->salida.="                         <option selected><b>Seleccionar</b></option>";
          }
           
          for($i=0;$i<count($vector1);$i++) 
           {
             if($perfilU[0]['perfil_id']==$vector1[$i]['perfil_id'])
             {
               $this->salida.="                      <option selected value='".$vector1[$i]['perfil_id']."'>".$vector1[$i]['descripcion_perfil']."</option>"; // 
             } 
             else
             { 
              $this->salida.="                         <option value='".$vector1[$i]['perfil_id']."'>".$vector1[$i]['descripcion_perfil']."</option>"; //
             }            
           }
        }  
       $this->salida .= "                         </select>";
       $this->salida .= "                      </td>\n";
       $this->salida .= "                      </tr>\n";
       
       $vectorEx=$consulta->ConsultarExepciones($this->Datos['modulo'],$_REQUEST['UID']);
       $vectorPer=$consulta->MarcarChecks($perfilU[0]['perfil_id']);
       $vectorU=Array();
       $vectorUEspejo=Array();
       $k=0;
       for($i=0;$i<count($vectorPer);$i++)
       {
          for($j=0;$j<count($vectorEx);$j++)
          {
            
            if($vectorPer[$i]['componente_id']==$vectorEx[$j]['componente_id']
               && $vectorEx[$j]['sw_permiso']=='I' )
            {
              break;
            }

          }
          if($j==count($vectorEx))
          { 
            $vectorU[$k++]=$vectorPer[$i]['componente_id'];
            
          }
           
       }
       
       for($i=0;$i<count($vectorEx);$i++)
       {
         if($vectorEx[$i]['sw_permiso']=='A')
         {
           $vectorU[$k++]=$vectorEx[$i]['componente_id'];
         }
       }
       
       for($i=0;$i<count($vectorU);$i++)
       {
         $vectorUEspejo[$i]=0;
       }
       //echo "aqui".$this->Datos['modulo'];
       $vector=$consulta->ListarComponentesSegunGrupo($this->Datos['modulo']);
       $path = SessionGetVar("rutaImagenes");
       $this->salida .= "                    <table width=\"90%\" id=\"componentesAB\" align=\"center\" class=\"modulo_table_title\">\n";
       $this->salida .= "                    <tr class=\"modulo_table_title\">\n";
       $this->salida .= "                      <td align=\"center\">\n";
       $this->salida .= "                        GRUPO";
       $this->salida .= "                      </td>\n";
       $this->salida .= "                      <td COLSPAN=\"2\" align=\"center\">\n";
       $this->salida .= "                        COMPONENTES";
       $this->salida .= "                      </td>\n";
       $this->salida .= "                    </tr>\n";
        
        foreach($vector as $valor=>$valor1)
        {
              
              $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
              $this->salida .= "                      <td rowspan='".sizeof($valor1)."' align=\"center\" class=\"label_error\">\n";
              $this->salida .= "                        ".strtoupper ($valor)."";
              $this->salida .= "                      </td>\n";
            for($i=0;$i<sizeof($valor1);$i++)
            {  
              $this->salida .= "                      <td class=\"modulo_list_claro\">\n";
              $this->salida .= "                        ".strtoupper ($valor1[$i]['descripcion_componente'])."";
              $this->salida .= "                      </td>\n";
              for($z=0;$z<count($vectorU);$z++)
                {
                   if($vectorU[$z]==$valor1[$i]['componente_id'])
                   { $this->salida .= "                     <td class=\"modulo_list_claro\" width=\"1%\" id=\"".$valor1[$i]['componente_id']."\">\n";
                     $this->salida .= "                        <input type=\"hidden\" name=\"numero".$valor1[$i]['componente_id']."\" value=\"".$valor1[$i]['componente_id']."\">";   
                     $this->salida .= "                         <a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcar('".$valor1[$i]['componente_id']."',componentes1.numero".$valor1[$i]['componente_id'].".value,componentes1.perfiles.value,'".$valor1[$i]['descripcion_grupo']."','".$this->Datos['modulo']."','".$this->Datos['modulo_tipo']."')\">\n";     
                     $this->salida .= "                           <img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
                     $this->salida .= "                         <a>\n";
                     $this->salida .= "                      </td>\n";
                     $this->salida .= "                    </tr>\n";
                     break;
                   }   
                
                }
            
             if($z==count($vectorU))   
              {    
                $this->salida .= "                     <td class=\"modulo_list_claro\" width=\"1%\" id=\"".$valor1[$i]['componente_id']."\">\n";
                $this->salida .= "                        <input type=\"hidden\" name=\"numero".$valor1[$i]['componente_id']."\" value=\"0\">";
                $this->salida .= "                         <a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcar('".$valor1[$i]['componente_id']."',componentes1.numero".$valor1[$i]['componente_id'].".value,componentes1.perfiles.value,'".$valor1[$i]['descripcion_grupo']."','".$this->Datos['modulo']."','".$this->Datos['modulo_tipo']."')\">\n";
                $this->salida .= "                           <img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
                $this->salida .= "                         <a>\n";
                $this->salida .= "                      </td>\n";
                $this->salida .= "                    </tr>\n";
              }
            }
            
        }     
            $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
            $this->salida .= "                      <td align=\"center\" colspan='3'>\n";
            $this->salida .= "                        <input type=\"button\" class=\"input-submit\" value=\"Asignar a Perfil\" onClick=\"AsignarComponente_a_Perfil('".$_REQUEST['UID']."','".$this->Datos['modulo']."','".$this->Datos['modulo_tipo']."',componentes1.perfiles.value);\" >\n"; 
            $this->salida .= "                      </td>\n"; 
            $this->salida .= "                    </tr>\n";
            $this->salida .= "                   </table>\n";
            //$this->salida .= "                  </table>\n";
            $this->salida .= "                 </form>\n"; 
            $this->salida .= "                 <form name=\"volver1\" action=\"javascript:Volver(document.volver1)\" method=\"post\">\n";//".$this->action[0]."
            $this->salida .= "                   <table align=\"center\">\n";
            $this->salida .= "                    <tr>\n";
            $this->salida .= "                      <td>\n";
            $this->salida .= "                         <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
            $this->salida .= "                      </td>\n";
            $this->salida .= "                    </tr>\n";
            $this->salida .= "                   </table>\n";
            $this->salida .= "                 </form>\n";                  
            $this->salida .= ThemeCerrarTabla();
       return $this->salida;     
      
      
      
    } 
    
    
    
    
	}
?>