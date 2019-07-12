<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_UV_AccidentesdeTrabajo_controller.php,v 1.32 2008/01/15 13:40:39 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */
  /**
  * Clase Control: UV_AccidentesdeTrabajo
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.32 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */
    class app_UV_AccidentesdeTrabajo_controller extends classModulo
    {
        /**
        * @var array $action  Vector donde se almacenan los links de la aplicacion
        */
        var $action = array();
        /**
        * @var array $request Vector donde se almacenan los datos pasados por request
        */
        var $request = array();
        /**
        * Constructor de la clase
        */
        function app_UV_AccidentesdeTrabajo_controller(){}
        /**
        * Funcion principal del modulo
        *
        * @return boolean
        */
        function Main()
        {
            include "system_modules/Tablas/system_Tablas_controller.php";

            $tablas = new system_Tablas_controller();
            $tablas->SetActionVolver(ModuloGetURL('app','UV_SaludOcupacionalAdmin','controller','Main'));
            //$this->action['espacios'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"UV_tipos_de_espacios"));
            //$this->action['ocupaciones'] = ModuloGetURL('system','Tablas','controller','Index',array("nombre_tabla"=>"UV_ocupaciones_SD"));
            

            $this->action['afiliados'] = ModuloGetURL('app','UV_AccidentesdeTrabajo','controller','Consulta_Afiliados');
            //$this->action['asignar'] = ModuloGetURL('app','UV_AccidentesdeTrabajo','controller','AsignarAgentesOcupacion');
            //$this->action['asignar_espacios'] = ModuloGetURL('app','UV_AccidentesdeTrabajo','controller','AsignarAgentesEspacios');
            //$this->action['man_cargos'] = ModuloGetURL('app','UV_AccidentesdeTrabajo','controller','ManejodeCargos');
            
            $this->action['volver'] = ModuloGetURL('system','Menu');
            $mdl = AutoCarga::factory("MensajesModuloHTML", "views", "app","UV_AccidentesdeTrabajo");
            $this->salida = $mdl->FormaMenuInicial($this->action);
            return true;
        }


    /**
    * Funcion que consulta a los diferentes afiliados por nombre o identificacion
    * access public;
    * @return boolean
    */
    function Consulta_Afiliados()
    {
        SessionSetVar("rutaImagenes",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS('RemoteXajax/AccidentesdeTrabajo.js', $contenedor='app', $modulo='UV_AccidentesdeTrabajo');
        $file = 'app_modules/UV_AccidentesdeTrabajo/RemoteXajax/AccidentesdeTrabajo.php';
        $this->SetXajax(array("BuscarDatos"),$file);
        $this->action['volver'] = ModuloGetURL('app','UV_AccidentesdeTrabajo','controller','Main');
        
    
        $afi = AutoCarga::factory("Afiliaciones1", "", "app","UV_AccidentesdeTrabajo");
        $tipo_identificacion = $afi->ObtenerTiposIdentificacion();
        $tipos_afiliados = $afi->ObtenerTiposAfiliados();
        $estados_afiliados = $afi->ObtenerTiposEstadosAfiliados();
        $dependencias = $afi->ObtenerDependenciasUV();
        $estamentos = $afi->ObtenerEstamentos();
        $tipos_aportantes = $afi->ObtenerTiposAportantes();
        $mdl = AutoCarga::factory("ConsultaAfiliadoHTML", "views", "app","UV_AccidentesdeTrabajo");
        $this->salida .= $mdl->FormaConsultaAfiliado($this->action,$tipo_identificacion,$tipos_afiliados,$estados_afiliados,$dependencias,$estamentos,$tipos_aportantes);
        if($_REQUEST['volver']=='Volver')
        {
            $pagina=SessionGetVar("PAGINA");
            $this->salida .="<script language=\"javaScript\">
                    function mOvr(src,clrOver) 
                    {
                    src.style.background = clrOver;
                    }
                        BuscarAfiliados2('".$pagina."',0);
                        
                   </script>";
        }
        else
        {
            $datos=SessionDelVar("BUSQUEDA");
            $pagina=SessionDelVar("PAGINA");
        }    
        return true;
    }


    /**
    * Funcion que registra accidentes de trabajo
    * access public;
    * @return boolean
    */
    function RegistrarAccidenteTrabajo()
    {
        
        SessionSetVar("rutaImagenes",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS('RemoteXajax/AccidentesdeTrabajo.js', $contenedor='app', $modulo='UV_AccidentesdeTrabajo');
        $file = 'app_modules/UV_AccidentesdeTrabajo/RemoteXajax/AccidentesdeTrabajo.php';
        $this->SetXajax(array("PintarAgentesdeRiesgoPorEspacio","Llamar_ciudades","Registro_Accidente"),$file);
        $this->action['volver'] = ModuloGetURL('app','UV_AccidentesdeTrabajo','controller','Consulta_Afiliados');
        $clase_sql = AutoCarga::factory("AccidentesdeTrabajoLogica", "", "app","UV_AccidentesdeTrabajo");
        $TIPOS_DE_ACCIDENTE=$clase_sql->ObtenerTipos_de_accidente();
        $partes_del_cuerpo_afectado = $clase_sql->ObtenerPartesdelCuerpoAfectado();
        $tipos_lesion = $clase_sql->ObtenerTiposLesion();
        $Agentes_Accidentes = $clase_sql->ObtenerAgentes_Accidentes();
        $Formas_Accidente = $clase_sql->ObtenerFormas_Accidente();
        $departamentos = $clase_sql->ObtenerDepartamentos();
        $sitios_accidente = $clase_sql->Obtener_Sitios_de_accidente();
        $espacios = $clase_sql->ObtenerEspacios();
        $Tipo_id_terceros = $clase_sql->ConsultarTipo_id_terceros();
        
        $mdl = AutoCarga::factory("AccidentesdeTrabajoHTML", "views", "app","UV_AccidentesdeTrabajo");
        $this->salida .= $mdl->FormaRegistroAccidenteTrabajo($this->action,$partes_del_cuerpo_afectado,$tipos_lesion,$Agentes_Accidentes,$Formas_Accidente,$TIPOS_DE_ACCIDENTE,$departamentos,$sitios_accidente,$espacios,$Tipo_id_terceros);
        return true;
    }


     /**
    * Funcion que consulta a el historial de accidentes de trabajo de un afiliado !!!!!!!ojo !!!!! afiliado
    * access public;
    * @return boolean
    */
    function HistorialAccidenteTrabajo()
    {
        
        SessionSetVar("rutaImagenes",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS('RemoteXajax/AccidentesdeTrabajo.js', $contenedor='app', $modulo='UV_AccidentesdeTrabajo');
        $file = 'app_modules/UV_AccidentesdeTrabajo/RemoteXajax/AccidentesdeTrabajo.php';
        $this->SetXajax(array("PintarLisAcc"),$file);
        $this->action['volver'] = ModuloGetURL('app','UV_AccidentesdeTrabajo','controller','Consulta_Afiliados');
        $clase_sql = AutoCarga::factory("AccidentesdeTrabajoLogica", "", "app","UV_AccidentesdeTrabajo");
        $LISTA_DE_ACCIDENTE=$clase_sql->ListarAccidentes($_REQUEST['afiliado_tipo_id'],$_REQUEST['afiliado_id']);
        //var_dump($LISTA_DE_ACCIDENTE);
        $this->action['historial'] = ModuloGetURL('app','UV_AccidentesdeTrabajo','controller','DetalleAccidenteTrabajo');
        $mdl = AutoCarga::factory("HistorialAccidentesdeTrabajoHTML", "views", "app","UV_AccidentesdeTrabajo");
        $this->salida .= $mdl->HistorialdeAccidenteTrabajo($this->action,$LISTA_DE_ACCIDENTE,$_REQUEST['nombre_afiliado']);
        return true;
    }


    function  DetalleAccidenteTrabajo()
    {

        SessionSetVar("rutaImagenes",GetThemePath());
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS('RemoteXajax/AccidentesdeTrabajo.js', $contenedor='app', $modulo='UV_AccidentesdeTrabajo');
        $file = 'app_modules/UV_AccidentesdeTrabajo/RemoteXajax/AccidentesdeTrabajo.php';
        $this->SetXajax(array("PintarLisAcc"),$file);
        $this->action['volver'] = ModuloGetURL('app','UV_AccidentesdeTrabajo','controller','HistorialAccidenteTrabajo',array('afiliado_tipo_id'=>$_REQUEST['tipo_id_trabajador'],'afiliado_id'=>$_REQUEST['trabajador_id'],'nombre_afiliado'=>$_REQUEST['nombre_afiliado']));
        $clase_sql = AutoCarga::factory("AccidentesdeTrabajoLogica", "", "app","UV_AccidentesdeTrabajo");
        $DETALLE_DE_ACCIDENTE=$clase_sql->DetalleAccidente($_REQUEST['accidente_id'],$_REQUEST['tipo_id_trabajador'],$_REQUEST['trabajador_id']);
        $mdl = AutoCarga::factory("DetalleAccidenteTrabajoHTML", "views", "app","UV_AccidentesdeTrabajo");
        $this->salida .= $mdl->DetalleAccidenteTrabajoInfo($this->action,$DETALLE_DE_ACCIDENTE,$_REQUEST['nombre_afiliado']);
        return true;





    }
}
?>