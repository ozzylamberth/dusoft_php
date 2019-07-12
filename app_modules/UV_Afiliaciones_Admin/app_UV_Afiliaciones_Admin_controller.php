<?php
  /**
  * @package IPSOFT-SIIS
  * @version * $Id: app_UV_Afiliaciones_Admin_controller.php,v 1.12 2008/06/12 13:29:20 jgomez Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

/**
  * Clase Control: app_UV_Afiliaciones_Admin_controller - Administracion del modulo de UV_Afiliaciones
  * Clase controladora del modulo modulo de administacion para UV_Afiliaciones 
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.12 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

	class app_UV_Afiliaciones_Admin_controller extends classModulo
	{
		/**
		* @var array $action Vector donde se almacenan los links de la aplicacion
		*/
		var $action = array();
		/**
		* @var array $request Vector donde se almacenan los datos pasados por request
		*/
		var $request = array();
		/**
		* Constructor de la clase
		*/
		function app_UV_Afiliaciones_Admin_controller(){}

        /**
		* Funcion principal del modulo esta es la que carga el menu de la aplicacion
        * @return boolean
        **/
		function Main()
		{
			$this->action['volver'] = ModuloGetURL('system','Menu');
            
            $menu_obj = AutoCarga::factory("Afiliaciones_Admin", "classes", "app","UV_Afiliaciones_Admin");
            $mdl = AutoCarga::factory("Menu_AdminHTML", "views", "app","UV_Afiliaciones_Admin");
            $validacion=$menu_obj->ValidarPermisoAdmin();
            //VAR_DUMP($validacion);
            if(!$validacion)
            {
                $this->salida = $mdl->FormaPermisoNegado($this->action);
                return true;
            }
            $this->action['instituciones_convenio'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','TercerosConvenios');
            $this->action['usuarios_per'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','Usuarios_Per');
            $this->action['admin_tablas_maestras'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','SubMenu_Admin');

            //ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"entidades_promotoras_de_salud"));
            $this->salida = $mdl->FormaMenuInicial($this->action);
            
			return true;
		}


        /**
        * Funcion que mostrara el gestor de ususrios perfiles
        * access public;
        * @return boolean
        **/
        function Usuarios_Per()
        {
            $this->action['volver'] = ModuloGetURL('system','Menu');
            $menu_obj = AutoCarga::factory("Afiliaciones_Admin", "classes", "app","UV_Afiliaciones_Admin");
            $mdl = AutoCarga::factory("Menu_AdminHTML", "views", "app","UV_Afiliaciones_Admin");
            $validacion=$menu_obj->ValidarPermisoAdmin();
            if(!$validacion)
            {
                $this->salida = $mdl->FormaPermisoNegado($this->action);
                return true;
            }
                    
            SessionSetVar("rutaImagenes",GetThemePath());
            $this->IncludeJS("CrossBrowser");
            $this->IncludeJS("CrossBrowserDrag");
            $this->IncludeJS("CrossBrowserEvent");
            $this->IncludeJS('RemoteXajax/Afiliaciones_Admin.js', $contenedor='app', $modulo='UV_Afiliaciones_Admin');
            $file = 'app_modules/UV_Afiliaciones_Admin/RemoteXajax/Afiliaciones_Admin.php';
            $this->SetXajax(array("EliminarUsuarioBD","ConfirmaEliminaUsu","BuscarUsu","CambiarPermisoAdminUsuario","ColocarPerfiles","Asignar_Perfil"),$file);
            $mdl = AutoCarga::factory("UsuariosPerHTML", "views", "app","UV_Afiliaciones_Admin");
            $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','Main');
            $this->action['registrar'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','AdicionarUsuarios');

            $afi = AutoCarga::factory("Afiliaciones_Admin", "", "app","UV_Afiliaciones_Admin");
            $perfiles = $afi->GetPerfiles();
           
            
            $this->salida .= $mdl->FormaListadoUsu($this->action,$perfiles);
            return true;
        }
         
         /**
        * Funcion que mostrara el menu de la administracuon de tablas maestras
        * access public;
        * @return boolean
        **/
        function SubMenu_Admin()
        {
            $menu_obj = AutoCarga::factory("Afiliaciones_Admin", "classes", "app","UV_Afiliaciones_Admin");
            $mdl = AutoCarga::factory("SubMenu_AdminHTML", "views", "app","UV_Afiliaciones_Admin");
            $validacion=$menu_obj->ValidarPermisoAdmin();
            if(!$validacion)
            {
                $this->salida = $mdl->FormaPermisoNegado($this->action);
                return true;
            }
                    
            SessionSetVar("rutaImagenes",GetThemePath());
            $this->IncludeJS("CrossBrowser");
            $this->IncludeJS("CrossBrowserDrag");
            $this->IncludeJS("CrossBrowserEvent");
            $this->IncludeJS('RemoteXajax/Afiliaciones_Admin.js', $contenedor='app', $modulo='UV_Afiliaciones_Admin');
            $file = 'app_modules/UV_Afiliaciones_Admin/RemoteXajax/Afiliaciones_Admin.php';
            $this->SetXajax(array(),$file);

            include "system_modules/Tablas/system_Tablas_controller.php";

            $tablas = new system_Tablas_controller();
            $tablas->SetActionVolver(ModuloGetURL('app','UV_Afiliaciones_Admin','controller','SubMenu_Admin'));
            $mdl = AutoCarga::factory("SubMenu_AdminHTML", "views", "app","UV_Afiliaciones_Admin");
            $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','Main');
            //////tablas maestros
            $this->action['eps'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"entidades_promotoras_de_salud"));
            $this->action['eafp'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"administradoras_de_fondos_de_pensiones"));
            $this->action['dependencias'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"uv_dependencias"));
            $this->action['tipos_afiliaciones'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"eps_tipos_afiliaciones"));
            $this->action['tipos_afiliados'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"eps_tipos_afiliados"));
            $this->action['tipos_aportantes'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"eps_tipos_aportantes"));
            $this->action['estamentos'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"eps_estamentos"));
            $this->action['afiliados_estados'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"eps_afiliados_estados"));
            $this->action['afiliados_subestados'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"eps_afiliados_subestados"));
            $this->action['parentescos_benef'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"eps_parentescos_beneficiarios"));
            $this->action['estado_civil'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"tipo_estado_civil"));
            $this->action['tipo_sexo'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"eps_tipos_sexo"));
            $this->action['actividades_economicas'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','ActividadesEconomicas');
            $this->action['ocupaciones'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','Ocupaciones');
            $this->salida .= $mdl->FormaSubMenuInicial($this->action);
            return true;
        }

         /**
        * Funcion que mostrara el menu de la administracuon de tablas maestras
        * access public;
        * @return boolean
        **/

        function ActividadesEconomicas()
        {
            $menu_obj = AutoCarga::factory("Afiliaciones_Admin", "classes", "app","UV_Afiliaciones_Admin");
            $mdl = AutoCarga::factory("ActividadesEconomicasHTML", "views", "app","UV_Afiliaciones_Admin");
            $validacion=$menu_obj->ValidarPermisoAdmin();
            if(!$validacion)
            {
                $this->salida = $mdl->FormaPermisoNegado($this->action);
                return true;
            }
                    
            SessionSetVar("rutaImagenes",GetThemePath());
            $this->IncludeJS("CrossBrowser");
            $this->IncludeJS("CrossBrowserDrag");
            $this->IncludeJS("CrossBrowserEvent");
            $this->IncludeJS('RemoteXajax/Afiliaciones_Admin.js', $contenedor='app', $modulo='UV_Afiliaciones_Admin');
            $file = 'app_modules/UV_Afiliaciones_Admin/RemoteXajax/Afiliaciones_Admin.php';
            $this->SetXajax(array(),$file);

            include "system_modules/Tablas/system_Tablas_controller.php";

            $tablas = new system_Tablas_controller();
            $tablas->SetActionVolver(ModuloGetURL('app','UV_Afiliaciones_Admin','controller','ActividadesEconomicas'));
            $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','SubMenu_Admin');
            //////tablas maestros
            $this->action['divisiones'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"ciiu_r3_divisiones"));
            $this->action['grupos'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"ciiu_r3_grupos"));
            $this->action['clases'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"ciiu_r3_clases"));
            $this->salida .= $mdl->FormaSubMenuActividadesEconomicas($this->action);
            return true;
        }

        /**
        * Funcion que mostrara el menu de la administracuon de tablas maestras
        * access public;
        * @return boolean
        **/
        function Ocupaciones()
        {
            $menu_obj = AutoCarga::factory("Afiliaciones_Admin", "classes", "app","UV_Afiliaciones_Admin");
            $mdl = AutoCarga::factory("OcupacionesHTML", "views", "app","UV_Afiliaciones_Admin");
            $validacion=$menu_obj->ValidarPermisoAdmin();
            if(!$validacion)
            {
                $this->salida = $mdl->FormaPermisoNegado($this->action);
                return true;
            }
                    
            SessionSetVar("rutaImagenes",GetThemePath());
            $this->IncludeJS("CrossBrowser");
            $this->IncludeJS("CrossBrowserDrag");
            $this->IncludeJS("CrossBrowserEvent");
            $this->IncludeJS('RemoteXajax/Afiliaciones_Admin.js', $contenedor='app', $modulo='UV_Afiliaciones_Admin');
            $file = 'app_modules/UV_Afiliaciones_Admin/RemoteXajax/Afiliaciones_Admin.php';
            $this->SetXajax(array(),$file);

            include "system_modules/Tablas/system_Tablas_controller.php";

            $tablas = new system_Tablas_controller();
            $tablas->SetActionVolver(ModuloGetURL('app','UV_Afiliaciones_Admin','controller','Ocupaciones'));
            $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','SubMenu_Admin');
            //////tablas maestros
            $this->action['grandes'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"ciuo_88_grandes_grupos"));
            $this->action['primarios'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"ciuo_88_grupos_primarios"));
            $this->action['principales'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"ciuo_88_subgrupos_principales"));
            $this->action['subgrupos'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"ciuo_88_subgrupos"));
            $this->salida .= $mdl->FormaSubMenuOcupaciones($this->action);
            return true;
        }
         /**
        * Funcion que mostrara el buscador de usuarios que no estan en el sistema EPS
        * access public;
        * @return boolean
        **/
        function AdicionarUsuarios()
        {
            $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','Usuarios_Per');
            $menu_obj = AutoCarga::factory("Afiliaciones_Admin", "classes", "app","UV_Afiliaciones_Admin");
            $mdl = AutoCarga::factory("Menu_AdminHTML", "views", "app","UV_Afiliaciones_Admin");
            $validacion=$menu_obj->ValidarPermisoAdmin();
            if(!$validacion)
            {
                $this->salida = $mdl->FormaPermisoNegado($this->action);
                return true;
            }
                    
            SessionSetVar("rutaImagenes",GetThemePath());
            $this->IncludeJS("CrossBrowser");
            $this->IncludeJS("CrossBrowserDrag");
            $this->IncludeJS("CrossBrowserEvent");
            $this->IncludeJS('RemoteXajax/Afiliaciones_Admin.js', $contenedor='app', $modulo='UV_Afiliaciones_Admin');
            $file = 'app_modules/UV_Afiliaciones_Admin/RemoteXajax/Afiliaciones_Admin.php';
            $this->SetXajax(array("AdicionarUsuarioConPerfilBD","AdicionarUser","BuscarUsuSys","ColocarPerfiles","Asignar_Perfil"),$file);
            $mdl = AutoCarga::factory("UsuariosPerHTML", "views", "app","UV_Afiliaciones_Admin");
            $afi = AutoCarga::factory("Afiliaciones_Admin", "", "app","UV_Afiliaciones_Admin");
            $perfiles = $afi->GetPerfiles();
            $this->salida .= $mdl->FormaListadoSystemUsu($this->action);
            return true;
        }

        /**
        * Funcion que mostrara el administrador de instituciones convenio
        * access public;
        * @return boolean
        **/
        function TercerosConvenios()
        {
             $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','Main');      
             $mdl = AutoCarga::factory("TercerosConveniosHTML", "views", "app","UV_Afiliaciones_Admin");
             $menu_obj = AutoCarga::factory("Afiliaciones_Admin", "classes", "app","UV_Afiliaciones_Admin");
             $validacion=$menu_obj->ValidarPermisoAdmin();
             if(!$validacion)
             {
                 $this->salida = $mdl->FormaPermisoNegado($this->action);
                 return true;
             }

            $combo_tipos_id=$menu_obj->ObtenerTercerosTiposId();
            SessionSetVar("rutaImagenes",GetThemePath());
            $this->IncludeJS("CrossBrowser");
            $this->IncludeJS("CrossBrowserDrag");
            $this->IncludeJS("CrossBrowserEvent");
            $this->IncludeJS('RemoteXajax/Afiliaciones_Admin.js', $contenedor='app', $modulo='UV_Afiliaciones_Admin');
            $file = 'app_modules/UV_Afiliaciones_Admin/RemoteXajax/Afiliaciones_Admin.php';
            $this->SetXajax(array("TipoBusqueda","BuscarTerceroConvenio","CambiarEstado"),$file);
            $this->action['volver']=ModuloGetURL('app','UV_Afiliaciones_Admin','controller','Main');
            $this->action['crear']=ModuloGetURL('app','UV_Afiliaciones_Admin','controller','CrearTercerosConvenios');
            
            $mdl = AutoCarga::factory("TercerosConveniosHTML", "views", "app","UV_Afiliaciones_Admin");
            
            $this->salida .= $mdl->FormaTercerosConvenios($this->action,$combo_tipos_id);
            return true;
        }

        /**
        * Funcion que mostrara el administrador de instituciones convenio
        * access public;
        * @return boolean
        **/
        function CrearTercerosConvenios()
        {
            
             $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','Main');   
             $mdl = AutoCarga::factory("CrearTerceroConvenioHTML", "views", "app","UV_Afiliaciones_Admin");
             $menu_obj = AutoCarga::factory("Afiliaciones_Admin", "classes", "app","UV_Afiliaciones_Admin");
             $validacion=$menu_obj->ValidarPermisoAdmin();
             if(!$validacion)
             {
                 $this->salida = $mdl->FormaPermisoNegado($this->action);
                 return true;
             }

            $combo_tipos_id=$menu_obj->ObtenerTercerosTiposId();
            SessionSetVar("rutaImagenes",GetThemePath());
            $this->IncludeJS("CrossBrowser");
            $this->IncludeJS("CrossBrowserDrag");
            $this->IncludeJS("CrossBrowserEvent");
            $this->IncludeJS('RemoteXajax/Afiliaciones_Admin.js', $contenedor='app', $modulo='UV_Afiliaciones_Admin');
            $file = 'app_modules/UV_Afiliaciones_Admin/RemoteXajax/Afiliaciones_Admin.php';
            $this->SetXajax(array("BuscarTerceroByRazonSocial"),$file);
            $this->action['volver']=ModuloGetURL('app','UV_Afiliaciones_Admin','controller','TercerosConvenios');
            $this->action['crear_forma']=ModuloGetURL('app','UV_Afiliaciones_Admin','controller','InsertarDatosTercerosConvenios');
            $mdl = AutoCarga::factory("CrearTerceroConvenioHTML", "views", "app","UV_Afiliaciones_Admin");
            
            $this->salida .= $mdl->FormaCrearTerceroConvenio($this->action,$combo_tipos_id);
            return true;
        }

        /**
        * Funcion que mostrara el administrador de instituciones convenio
        * access public;
        * @return boolean
        **/
        function InsertarDatosTercerosConvenios()
        {
            
             $this->action['volver'] = ModuloGetURL('app','UV_Afiliaciones_Admin','controller','CrearTercerosConvenios');   
             $mdl = AutoCarga::factory("InsetarDatosTerceroConvenioHTML", "views", "app","UV_Afiliaciones_Admin");
             $menu_obj = AutoCarga::factory("Afiliaciones_Admin", "classes", "app","UV_Afiliaciones_Admin");
             $validacion=$menu_obj->ValidarPermisoAdmin();
             if(!$validacion)
             {
                 $this->salida = $mdl->FormaPermisoNegado($this->action);
                 return true;
             }
            $datos_pais=array();

            $datos_pais['DefaultPais']=$_REQUEST['tipo_pais_id'];
            $datos_pais['DefaultDpto']=$_REQUEST['tipo_dpto_id'];
            $datos_pais['DefaultMpio']=$_REQUEST['tipo_mpio_id'];
            
            $nombre_pais=$menu_obj->ObtenerDatosLugarResidencia($datos_pais);
            $combo_tipos_id=$menu_obj->ObtenerTercerosTiposId();
            

            SessionSetVar("rutaImagenes",GetThemePath());
            $this->IncludeJS("CrossBrowser");
            $this->IncludeJS("CrossBrowserDrag");
            $this->IncludeJS("CrossBrowserEvent");
            $this->IncludeJS('RemoteXajax/Afiliaciones_Admin.js', $contenedor='app', $modulo='UV_Afiliaciones_Admin');
            $file = 'app_modules/UV_Afiliaciones_Admin/RemoteXajax/Afiliaciones_Admin.php';
            $this->SetXajax(array("CrearConvUsu","guardar_tercerosconvenios"),$file);
            $this->action['volver']=ModuloGetURL('app','UV_Afiliaciones_Admin','controller','CrearTercerosConvenios');
            $mdl = AutoCarga::factory("InsetarDatosTerceroConvenioHTML", "views", "app","UV_Afiliaciones_Admin");
            
            $this->salida .= $mdl->FormaInsertarDatos($this->action,$combo_tipos_id,$nombre_pais,$datos_pais);
            return true;
        }

        
	}
?>