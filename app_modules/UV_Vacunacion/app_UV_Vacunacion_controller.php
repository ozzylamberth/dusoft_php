<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: app_UV_Vacunacion_controller.php,v 1.2 2008/05/28 15:18:54 gerardo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Gerardo Amador Vidal
  */
  /**
  * Clase Control: UV_Vacunacion
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Gerardo Amador Vidal
  */

  class app_UV_Vacunacion_controller extends classModulo{
    
    /*
    *Contructor de la clase
    */
    function app_UV_Vacunacion_controller(){}
    
    function eliminarSessiones(){
    }
    
    
    /**
    *Funcion Main()
    *@return boolean
    */
    function Main()
    {
      $cxp = AutoCarga::factory('Vacunacion','','app','UV_Vacunacion');
      $permisos = $cxp->ObtenerPermisos();
      
      //SessionSetVar("rutaImagenes",GetThemePath());      
      
      $action['volver'] = ModuloGetURL("system", "Menu"); 
      
      if(empty($permisos))
      {
        $mensaje = "El usuario no tiene permisos para ingresar a este modulo";
        
        $msgMod = AutoCarga::factory('MensajesModuloHTML', 'views', 'app', 'UV_Vacunacion');
        
        $this->salida = $msgMod->FormaMensajeModulo($action,$mensaje);
      }
      else
      {
        $ttl_gral = "VACUNACION";
        $titulo[0]='EMPRESAS';
        $url[0]='app';              
        $url[1]='UV_Vacunacion'; 
        $url[2]='controller'; 
        $url[3]='Menu';       
        $url[4]='permiso_ss';
        $this->salida = gui_theme_menu_acceso($ttl_gral,$titulo,$permisos,$url,$action['volver']);
               
      }
      
      return true;
    
    }
    
    
    /**
    *Funcion que muestra el menu principal
    *@return boolean
    */
    function Menu()
    {
      $request = $_REQUEST;
      
      //if()     
 
      //$action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controler', 'Main');
    
       $action['crearVac'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'EncontrarVacuna'); 
       //$action['param'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'Parametrizar');
       $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'Main');         
      
      $html = AutoCarga::factory('MensajesModuloHTML', 'views', 'app', 'UV_Vacunacion');
      $this->salida = $html->FormaMenuInicial($action);
       //$mensaje = "Hummm"
      
       //$mnu = AutoCarga::factory('MensajesModuloHTML', 'views', 'app', 'UV_Vacunacion');
       //$this->salida = $mnu->FormaMensajeModulo($action,$mensaje);   
       
          
         
      
      return true;//*/
    }
    
    /**
    *Funcion que permite arranca el buscador de vacunas y da la opcion de crearlas
    *@return boolean
    */
    function CrearVacunas()
    {
    
      $action['crearNueVac'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'CrearNuevaVacuna');
      $action['btnBuscar'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'EncontrarVacuna');
      $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'Menu');
      //$action['elimVac'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'BorrarVacuna');
      
           
      $html = AutoCarga::factory('BuscadorModuloHTML', 'views', 'app', 'UV_Vacunacion');
      $this->salida = $html->FormaBuscador($action);
      
      /*$action['crearNueVac'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'CrearNuevaVacuna');
      $action['btnBuscar'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'EncontrarVacuna');      
      
      $html = AutoCarga::factory('BuscadorModuloHTML', 'views', 'app', 'UV_Vacunacion');
      $this->salida = $html->FormaBuscador($action);*/      
      
      return true;
    }
    
    /**
    *Funcion que se utiliza para crear una nueva vacuna, llamando a la funcion Insertar()
    *@return boolean
    */
    function CrearNuevaVacuna()
    {
      $request = $_REQUEST;

      $action['aceptar'] = ModuloGetURL('app','UV_Vacunacion','controller','Insertar');
      $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'EncontrarVacuna');            
      
      //$sm = AutoCarga::factory('Vacunacion', '', 'app', 'UV_Vacunacion');
      $html = AutoCarga::factory('BuscadorModuloHTML', 'views', 'app', 'UV_Vacunacion');
      
      $this->salida = $html->FormaNuevaVacuna($action);
      
      return true;
    }

    /**
    *Funcion que inserta una nueva vacuna 
    *@return boolean
    */
    function Insertar()
    {
    
      $request = $_REQUEST;
      
      $sm = AutoCarga::factory('Vacunacion', '', 'app', 'UV_Vacunacion');     
      
      $rst = $sm->InsertarVacuna($request);
      
      $msg0 = "INGRESO DE VACUNA";
      
      if(!$rst)
      {
        $msg1 = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$sm->mensajeDeError;
      } 
      else
      {
        $msg1 = "El INGRESO DE LA VACUNA No: ".$rst.", SE HA REALIZADO SATISFACTORIAMENTE";
      }
      
      $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'EncontrarVacuna');      
      
      $msgMod = AutoCarga::factory('MensajesModuloHTML', 'views', 'app', 'UV_Vacunacion');
      $this->salida = $msgMod->FormaMensajeModulo($action, $msg0, $msg1);
      
      return true;
    
    }
     
    
    /**
    *Funcion encargada de hacer la busqueda de una vacuna
    *@return boolean
    */
    function EncontrarVacuna()
    {
      $request = $_REQUEST;
      
      $slc = AutoCarga::factory('Vacunacion', '', 'app', 'UV_Vacunacion');
      $rst = $slc->BuscarVacuna($request, $request['offset']);
      
      $action['crearNueVac'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'CrearNuevaVacuna');
      $action['btnBuscar'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'EncontrarVacuna');
      $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'Menu'); 

      //$action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'Parametrizar');      
                 
      $html = AutoCarga::factory('BuscadorModuloHTML', 'views', 'app', 'UV_Vacunacion');
      
      $conteo = 0; 
      $pagina = 0;
      
      $action['paginador'] = ModuloGetURL('app', 'UV_Vacunacion','controller','EncontrarVacuna',array("nombre_vacuna"=>$request['nombre_vacuna']));     
      
      $conteo = $slc->conteo;
      $pagina = $slc->pagina;
      
      $this->salida = $html->FormaBuscador($action, $rst, $conteo, $pagina);
            
      return true;
    }
    
    /**
    *Funcion que se utliza para borrar una vacuna
    *@return boolean
    */
    function BorrarVacuna()
    {
      
      $request = $_REQUEST;
      
      $slc = AutoCarga::factory('Vacunacion', '', 'app', 'UV_Vacunacion');
      $rst = $slc->EliminarVacuna($request['vt_vacuna_id']);
      //$rst = $slc->DesabilitaVacuna($request['vt_vacuna_id']);
      
      $vacuna_id = $request['vt_vacuna_id']; 
      
      $msg0 = "ELIMINACION DE VACUNA";
     
      if(!$rst)
      {
        $msg1 = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$sm->mensajeDeError;
      } 
      else
      {
        //$mensaje = "LA MODIFICACION DE LA VACUNA No: ".$rst.", SE HA REALIZADO SATISFACTORIAMENTE";
        
        $msg1 = "LA ELIMINACION DE LA VACUNA No: ".$vacuna_id.", SE HA REALIZADO SATISFACTORIAMENTE";
      }        
      
       
      $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'EncontrarVacuna');      
      
      $msgMod = AutoCarga::factory('MensajesModuloHTML', 'views', 'app', 'UV_Vacunacion');
      $this->salida = $msgMod->FormaMensajeModulo($action, $msg0, $msg1);
      
      return true;            
    
    }    
    
    /**
    *Funcion que se permite modificar una vacuna, llamando a la funcion CambiarVacuna()
    *@return boolean
    */
    function ModificarVacuna(){
    
      $request = $_REQUEST;
            
      $action['aceptar'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'CambiarVacuna');           
      
      $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'EncontrarVacuna'); 
            
      $html = AutoCarga::factory('BuscadorModuloHTML', 'views', 'app', 'UV_Vacunacion');
      
      $this->salida = $html->FormaEditarVacuna($action, $request);      
    
      return true;
    }
    
    /**
    *Funcion que se utilza para editar una vacuna
    *@return boolean
    */
    function CambiarVacuna(){
      
      $request = $_REQUEST;
      
      $slc = AutoCarga::factory('Vacunacion', '', 'app', 'UV_Vacunacion');      
      $rst = $slc->EditarVacuna($request);
      
      $msg0 = "MODIFICACION DE VACUNA";
      
      if(!$rst)
      {
        $msg1 = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$sm->mensajeDeError;
      } 
      else
      {
        //$mensaje = "LA MODIFICACION DE LA VACUNA No: ".$rst.", SE HA REALIZADO SATISFACTORIAMENTE";
        
        $msg1 = "LA MODIFICACION DE LA VACUNA No: ".$request['vacuna_id'].", SE HA REALIZADO SATISFACTORIAMENTE";
      }      
      
      $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'EncontrarVacuna');      
      
      $msgMod = AutoCarga::factory('MensajesModuloHTML', 'views', 'app', 'UV_Vacunacion');
      $this->salida = $msgMod->FormaMensajeModulo($action,$msg0, $msg1);
      
      return true;            
    
    }    
    
        
    /**
    *Funcion que permite parametrizar una vacuna
    *@return boolean
    */
    function Parametrizar()
    {
      $request = $_REQUEST;
      
      //$action['crearNueParam'] = ModuloGetURL('app','UV_Vacunacion','controller','Menu');
      
//       $html = AutoCarga::factory('ParametrizarModuloHTML', 'views', 'app', 'UV_Vacunacion');
//       $this->salida = $html->ListarParametros();      

      //$action['crearNueParam'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'CrearParametro');
      
      $slc = AutoCarga::factory('Vacunacion', '', 'app', 'UV_Vacunacion');
      $rst = $slc->BuscarParametros($request['vt_vacuna_id']);        
      
      
      $action['crearNueParam'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'CrearParametro', array('vt_vacuna_id'=>$request['vt_vacuna_id'], 'vt_nombre_vacuna'=>$request['vt_nombre_vacuna'])); 
           
      $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'EncontrarVacuna'); 

      
      $html = AutoCarga::factory('ParametrizarModuloHTML', 'views', 'app', 'UV_Vacunacion');
      
      $this->salida = $html->FormaListarParametros($action, $request, $rst);
          
      return true;
    }
    
    
    /**
    *Funcion que permite crear un parametro nuevo a una vacuna, por medio de la invocacion de InsertarParam()
    *@return boolean
    */
    function CrearParametro()
    {
      $request = $_REQUEST;  
      //$action['crearNueParam'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'CrearParametro'); 
      $action['aceptar'] = ModuloGetURL('app','UV_Vacunacion','controller','InsertarParam');          
      
      $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'Parametrizar', array('vt_vacuna_id'=>$request['vt_vacuna_id'], 'vt_nombre_vacuna'=>$request['vt_nombre_vacuna'])); 
      
      $html = AutoCarga::factory('ParametrizarModuloHTML', 'views', 'app', 'UV_Vacunacion');
      
      $this->salida = $html->FormaNuevoParametro($action, $request);
          
      return true;    
    }
    
    
    /**
    *Funcion que permite insertar un nuevo parametro en una vacuna
    *@return boolean
    */
    function InsertarParam()
    {
    
      $request = $_REQUEST;
      
      $sm = AutoCarga::factory('Vacunacion', '', 'app', 'UV_Vacunacion');     
      
      $rst = $sm->InsertarParametro($request);
      
      $msg0 = "INGRESO DE PARAMETRO";
      
      if(!$rst)
      {
        $msg1 = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$sm->mensajeDeError;
      } 
      else
      {
        $msg1 = "El INGRESO DEL PARAMETRO No: ".$rst.", SE HA REALIZADO EXITOSAMENTE";
      }
      
      //$action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'Parametrizar');
      
      $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'Parametrizar', array('vt_vacuna_id'=>$request['vt_vacuna_id'], 'vt_nombre_vacuna'=>$request['vt_nombre_vacuna']));            
      
      $msgMod = AutoCarga::factory('MensajesModuloHTML', 'views', 'app', 'UV_Vacunacion');
      $this->salida = $msgMod->FormaMensajeModulo($action, $msg0, $msg1);
      return true;
    
    }    
    
    
    
    /**
    *Funcion permite encontrar parametros
    *@return boolean
    */
     //Este metodo no se esta usando POR AHORAAAAA!!!!!!!!
    function EncontrarParametros(){
    
      $request = $_REQUEST;
      
      $slc = AutoCarga::factory('Vacunacion', '', 'app', 'UV_Vacunacion');
      $rst = $slc->BuscarParametros($request['vt_vacuna_id']);    
    
      
      $html = AutoCarga::factory('ParametrizarModuloHTML', 'views', 'app', 'UV_Vacunacion');      
    
      $this->salida = $html->FormaListarParametros($action, $request, $rst); 
      
      return true;        
    
    }
    
    
    /**
    *Funcion que inicia la modificacion de un parametro, por medio del llamado al metodo CambiarParametro()
    *@return boolean
    */
    function ModificarParametro(){
    
      $request = $_REQUEST;  
      
      
      $action['aceptar'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'CambiarParametro');           
      
      $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'Parametrizar', array('vt_vacuna_id'=>$request['vt_vacuna_id'], 'vt_nombre_vacuna'=>$request['vt_nombre_vacuna']));
            
      $html = AutoCarga::factory('ParametrizarModuloHTML', 'views', 'app', 'UV_Vacunacion');      
      
      
      $this->salida = $html->FormaEditarParametro($action, $request);      
    
      return true;
    }
    
    
    /**
    *Funcion que se utiliza para editar un parametro de una vacuna
    *@return boolean
    */
    function CambiarParametro(){
      $request = $_REQUEST;
      
      $slc = AutoCarga::factory('Vacunacion', '', 'app', 'UV_Vacunacion');      
      $rst = $slc->EditarParametro($request);
      
      $msg0 = "MODIFICACION DE PARAMETRO";
      
      if(!$rst)
      {
        $msg1 = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$sm->mensajeDeError;
      } 
      else
      {        
        $msg1 = "LA MODIFICACION DEL PARAMETRO No: ".$request['vp_vacuna_param_id'].", SE HA REALIZADO EXITOSAMENTE";
      }      
      
      $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'Parametrizar', array('vt_vacuna_id'=>$request['vt_vacuna_id'], 'vt_nombre_vacuna'=>$request['vt_nombre_vacuna']));
      
      $msgMod = AutoCarga::factory('MensajesModuloHTML', 'views', 'app', 'UV_Vacunacion');
      $this->salida = $msgMod->FormaMensajeModulo($action, $msg0, $msg1);    
    
      return true;
    }
    
    
    /**
    *Funcion que permiter borrar o desahabilitar un parametro de una vacuna
    *@return boolean
    */
    function BorrarParametro(){
      $request = $_REQUEST;
      
      $slc = AutoCarga::factory('Vacunacion', '', 'app', 'UV_Vacunacion');
      //$rst = $slc->EliminarParametro($request);
      $rst = $slc->DesabilitaParametro($request);
      
      
      $vacuna_param_id = $request['vp_vacuna_param_id']; 
      
      //$msg0 = "ELIMINACION DE PARAMETRO";
      $msg0 = "DESHABILITACION DE PARAMETRO";
      
            
      if(!$rst)
      {
        $msg1 = "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>".$sm->mensajeDeError;
      } 
      else
      {
        
        //$msg1 = "LA ELIMINACION DEL PARAMETRO No: ".$vacuna_param_id.", SE HA REALIZADO SATISFACTORIAMENTE";
        
        $msg1 = "El PARAMETRO No: ".$vacuna_param_id.", SE HA DESHABILITADO SATISFACTORIAMENTE";        
      }      
      
      $action['volver'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'Parametrizar', array('vt_vacuna_id'=>$request['vt_vacuna_id'], 'vt_nombre_vacuna'=>$request['vt_nombre_vacuna']));
      
      $msgMod = AutoCarga::factory('MensajesModuloHTML', 'views', 'app', 'UV_Vacunacion');
      $this->salida = $msgMod->FormaMensajeModulo($action, $msg0, $msg1);    
    
      return true;
    }
    
    
}
?>