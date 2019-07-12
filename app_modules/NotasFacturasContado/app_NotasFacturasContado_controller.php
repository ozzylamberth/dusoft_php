<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_NotasFacturasContado_controller.php,v 1.1 2010/03/09 13:40:54 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Control: NotasFacturasContado
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class app_NotasFacturasContado_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_NotasFacturasContado_controller(){}
    /**
    * Funcion principal del modulo
    *
    * @return boolean
    */
    function main()
    {
      $cls = AutoCarga::factory("NotasFacturas","classes","app","NotasFacturasContado");
      $permisos = $cls->ObtenerPermisos(UserGetUID());
      
      $titulo[0] = 'EMPRESAS';
			$url[0] = 'app';										//contenedor 
			$url[1] = 'NotasFacturasContado';	  //módulo 
			$url[2] = 'controller';							//clase 
			$url[3] = 'Menu';				            //método 
			$url[4] = 'notasfacturas';					//indice del request
			$this->salida .= gui_theme_menu_acceso('NOTAS FACTURAS XONTADO',$titulo,$permisos,$url,ModuloGetURL('system','Menu'));

      return true;
    }
    /**
    * Funcion de control, para la creacion del menu
    *
    * @return boolean
    */
    function Menu()
    {
      $request = $_REQUEST;
      if($request['notasfacturas'])
        SessionSetVar("PermisosNotasContado",$request['notasfacturas']);

      $action['crear'] = ModuloGetURL('app','NotasFacturasContado','controller','FacturasContado');
      $action['consultar'] = ModuloGetURL('app','NotasFacturasContado','controller','ListadoNotas');
      $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","NotasFacturasContado");
    
      $this->salida .= $mdl->FormaMenuInicial($action);
      return true;
    }
    /**
    * Funcion de control para la creacion de la forma donde se buscan las facturas
    *
    * @return boolean
    */
    function FacturasContado()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosNotasContado");
            
      $mdl = AutoCarga::factory("FacturasHTML","views","app","NotasFacturasContado");
      $cls = AutoCarga::factory("NotasFacturas","classes","app","NotasFacturasContado");

      $terceros = $cls->ObtenerTipoIdTerceros();
      $prefijos = $cls->ObtenerPrefijos($empresa['empresa']);
      
      $datosF = array();
      if($request['buscador'])
        $datosF = $cls->ObtenerFacturas($empresa['empresa'],$request['buscador'],$request['offset']);
      
      $action['volver'] = ModuloGetURL('app','NotasFacturasContado','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','NotasFacturasContado','controller','FacturasContado');
      $action['notas'] = ModuloGetURL('app','NotasFacturasContado','controller','CuerpoNota');
      $action['paginador'] = ModuloGetURL('app','NotasFacturasContado','controller','FacturasContado',array("buscador"=>$request['buscador']));

      $this->salida .= $mdl->FormaFacturas($action,$request['buscador'],$prefijos,$terceros,$datosF, $cls->conteo, $cls->pagina);
      return true;
    }
    /**
    * Funcion de control, pra el cuerpo de la nota
    *
    * @return boolean
    */
    function CuerpoNota()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosNotasContado");
      
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->IncludeJS("CrossBrowser");

      $mdl = AutoCarga::factory("FacturasHTML","views","app","NotasFacturasContado");
      $cls = AutoCarga::factory("NotasFacturas","classes","app","NotasFacturasContado");

      IncludeFileModulo("NotasContado","RemoteXajax","app","NotasFacturasContado");
      $this->SetXajax(array("ActivarSeleccion","AdicionarConcepto","EliminarConcepto","ListarConceptos","InformacionNota"),null,"ISO-8859-1");

      $action['volver'] = ModuloGetURL('app','NotasFacturasContado','controller','FacturasContado');
      $action['terceros'] = ModuloGetURL('app','NotasFacturasContado','controller','BuscarTerceros');
      $action['crear'] = ModuloGetURL('app','NotasFacturasContado','controller','CrearNota');
      $action['descartar'] = ModuloGetURL('app','NotasFacturasContado','controller','DescartarNota');
      
      $filtro['prefijo'] = $request['prefijo'];
      $filtro['factura_fiscal'] = $request['factura_fiscal'];
      $filtro['tipo_nota'] = $request['tipo_nota'];

      $datosF = $cls->ObtenerFacturas($empresa['empresa'],$filtro);
      $tipo_concepto = ($request['tipo_nota'] == "C")? "D":"C";
      
      $auditores = $cls->ObtenerAuditoresInternos($empresa['empresa']);
      $conceptos = $cls->ObtenerConceptos($tipo_concepto,$empresa['empresa']);
      $departamentos = $cls->ObtenerDepartamentos($empresa['empresa']);
      
      $this->salida .= $mdl->FormaCuerpoNota($action, $request['tipo_nota'],$datosF[0],$conceptos,$departamentos,$auditores);
      return true;
    }
    /**
    *
    * @return boolean
    */
    function BuscarTerceros()
    {
      $request = $_REQUEST;
      $mdl = AutoCarga::factory("FacturasHTML","views","app","NotasFacturasContado");
      $cls = AutoCarga::factory("NotasFacturas","classes","app","NotasFacturasContado");
      
      $tipos_terceros = $cls->ObtenerTipoIdTerceros();
      
      $terceros = array();
      if($request['buscador'])
        $terceros = $cls->ObtenerTerceros($request['buscador'],$request['offset']);
      
      $action['buscar'] = ModuloGetURL('app','NotasFacturasContado','controller','BuscarTerceros');
      $action['paginador'] = ModuloGetURL('app','NotasFacturasContado','controller','BuscarTerceros',array("buscador"=>$request['buscador']));

      $this->salida .= $mdl->FormaBuscarTerceros($action,$request['buscador'],$tipos_terceros,$terceros,$cls->conteo,$cls->pagina);
      return true;
    }    
    /**
    * Funcion de control, para eliminar las notas temporales
    *
    * @return boolean
    */
    function DescartarNota()
    {
      $request = $_REQUEST;
      $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","NotasFacturasContado");
      $cls = AutoCarga::factory("NotasFacturas","classes","app","NotasFacturasContado");
      
      $rst = $cls->EliminarNotaTemporal($request['tmp_nota_id']);
      $mensaje = "LA NOTA HA SIDO DESCARTADA, CORRECTAMENTE";
      if(!$rst)
        $mensaje = "HA OCURRIDO UN ERROR AL REALIZAR LA OPERACION ".$cls->mensajeDeError;
        
      $action['volver'] = ModuloGetURL('app','NotasFacturasContado','controller','FacturasContado');

      $this->salida .= $mdl->FormaMensajeModulo($action,$mensaje);
      return true;
    }    
    /**
    * Funcion de control, para crear la nota real
    *
    * @return boolean
    */
    function CrearNota()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosNotasContado");
      
      $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","NotasFacturasContado");
      $cls = AutoCarga::factory("NotasFacturas","classes","app","NotasFacturasContado");
      
      $vrl = "debito";
      if($request['tipo_nota'] == "C")
        $vrl = "credito";
      
      $documento = ModuloGetVar('app','NotasFacturasContado',"documento_".$vrl."_".$empresa['empresa']);
      $request['empresa_id'] = $empresa['empresa'];
      $request['usuario_id'] = UserGetUID();
      $request['documento_id'] = $documento;
      $request['tabla'] = $vrl;
      
      $rst = $cls->CrearNotaContado($request);
      
      $imprimir = array();
      $mensaje = "LA NOTA ".strtoupper($vrl)." HA SIDO CREADA CORRECTAMENTE";
      if(!$rst)
        $mensaje = "HA OCURRIDO UN ERROR AL REALIZAR LA OPERACION ".$cls->mensajeDeError;
      else
      {
        $imprimir['empresa_id'] = $empresa['empresa'];
        $imprimir['usuario_id'] = UserGetUID();
        $imprimir['tabla'] = $vrl;
        $imprimir['nombre_reporte'] = "notascredito";
        $imprimir['numero'] = $cls->numero;
        $imprimir['prefijo'] = $cls->prefijo;
      }
      $action['volver'] = ModuloGetURL('app','NotasFacturasContado','controller','FacturasContado');

      $this->salida .= $mdl->FormaMensajeModulo($action,$mensaje,$imprimir);
      return true;
    }
    /**
    * Funcion de control para la busqueda de notas
    *
    * @return boolean
    */
    function ListadoNotas()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosNotasContado");
            
      $mdl = AutoCarga::factory("NotasHTML","views","app","NotasFacturasContado");
      $cls = AutoCarga::factory("ListaNotas","classes","app","NotasFacturasContado");

      $prefijos = $cls->ObtenerPrefijos($empresa['empresa']);
      
      $datosN = array();
      if($request['buscador'])
      {
        $datosN = $cls->ObtenerNotas($empresa['empresa'],$request['buscador'],$request['offset']);
        $request['buscador']['usuario_id'] = UserGetUID();
        $request['buscador']['empresa_id'] = $empresa['empresa'];
      }
      $action['volver'] = ModuloGetURL('app','NotasFacturasContado','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','NotasFacturasContado','controller','ListadoNotas');
      $action['paginador'] = ModuloGetURL('app','NotasFacturasContado','controller','ListadoNotas',array("buscador"=>$request['buscador']));
      
      $this->salida .= $mdl->FormaBuscarNotas($action,$request['buscador'],$prefijos,$datosN, $cls->conteo, $cls->pagina);
      return true;
    }
  }
?>