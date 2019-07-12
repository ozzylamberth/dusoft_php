<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_ESM_ParametrosIniciales_controller.php,v 1.29 2010/02/17 14:46:54 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  /**
  * Clase Control: ESM_ParametrosIniciales
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.29 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
    class app_ESM_ParametrosIniciales_controller extends classModulo
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
        function app_ESM_ParametrosIniciales_controller(){}
        /**
        * Funcion principal del modulo
        *
        * @return boolean
        */
        
		function main()
		{	
			
			
			
			$request = $_REQUEST;
						
			$url[0]='app';                         //Tipo de Modulo
			$url[1]='ESM_ParametrosIniciales';   //Nombre del Modulo
			$url[2]='controller';                  //Si es User,controller...
			$url[3]='MenuParametrosIniciales';   //Metodo.
			$url[4]='datos';						//vector de $_request.
			$arreglo[0]='EMPRESA';					//Sub Titulo de la Tabla
						
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("Permisos", "", "app","ESM_ParametrosIniciales");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$datos=$obj_busqueda->BuscarPermisos(); 
		
		//Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
										// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
			$forma = gui_theme_menu_acceso("PARAMETROS INICIALES - INVENTARIOS - SISTEMA",$arreglo,$datos,$url,ModuloGetURL('system','Menu')); 
			$this->salida=$forma;
					
      /*			
			//(nombre de la Tabla Acceso,
			FormaMostrarMenuHospitalizacion($forma); //Invocar un view, para mostrar la informacion.
      */
			return true; 

		}
		
      /*
      * FUNCION DE MENU PRINCIPAL
      */
		function MenuParametrosIniciales()
		{
  		/*Crear el Menú de Opciones*/
      $request = $_REQUEST;
  		$Obj_Menu=AutoCarga::factory("ESM_ParametrosIniciales_MenuHTML", "views", "app","ESM_ParametrosIniciales");
  	
  		$action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","main");
  	
      if($request['datos']['empresa_id'])
        SessionSetVar("empresa_id",$request['datos']['empresa_id']);      
     
  		$this->salida=$Obj_Menu->Menu($action);
  		
  		return true;
		}
		
    
		/* FUNCION Parametrizar_TipoEvento
		*  Funcion que consiste en Parametrizar los tipos de eventos
		*  @param NULL
		*	return booleam.
		*/
		
		function Parametrizar_TipoEvento()
		{
		$request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_TipoEvento","RemoteXajax","app","ESM_ParametrosIniciales");
		$this->SetXajax(array("Listado_TiposEventos","Ingreso_TipoEvento","Insertar_TipoEvento",
                          "Modificacion_TipoEvento","Modificar_TipoEvento","CambioEstado"),null,"ISO-8859-1");

		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("Parametrizar_TipoEvento_HTML", "views", "app","ESM_ParametrosIniciales");
    	$action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","MenuParametrosIniciales");
	    $Html_Form=$Obj_Form->main($action,$request);
		$this->salida = $Html_Form;
		return true;
		}
    
    
      /* FUNCION CREAR ESTADOS DOCUMENTOS
      *  Funcion que consiste en Generar estados que pueden tener los documentos x empresa y usuario
      *  @param NULL
      *	return booleam.
      */

      function Parametrizar_Fuerzas()
      {
      $request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_TipoFuerza","RemoteXajax","app","ESM_ParametrosIniciales");
		$this->SetXajax(array("Listado_TiposFuerzas","Ingreso_TipoFuerza","Insertar_TipoFuerza",
                          "Modificacion_TipoFuerza","Modificar_TipoFuerza","CambioEstado"),null,"ISO-8859-1");

		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("Parametrizar_TiposFuerzas_HTML", "views", "app","ESM_ParametrosIniciales");
    	$action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","MenuParametrosIniciales");
	    $Html_Form=$Obj_Form->main($action,$request);
		$this->salida = $Html_Form;
		return true;
      }
    
    
    
     /* FUNCION CREAR ESTADOS DOCUMENTOS
		*  Funcion que consiste en Generar estados que pueden tener los documentos x empresa y usuario
		*  @param NULL
		*	return booleam.
		*/
		
		function Parametrizar_ESM()
		{
		$request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_ESM","RemoteXajax","app","ESM_ParametrosIniciales");
		$this->SetXajax(array("Listado_Terceros","Listado_ESM","Asignar_TerceroESM","Borrar_TerceroESM"),null,"ISO-8859-1");

		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
    $this->IncludeJS("TabPaneLayout");
		$this->IncludeJS("TabPaneApi");
    $this->IncludeJS("TabPane");
    
    $sql = AutoCarga::factory("Consultas_ESM","classes","app","ESM_ParametrosIniciales");
    $TipoIdTercero = $sql->Obtener_TiposId();
    
    $Obj_Form=AutoCarga::factory("Parametrizar_ESM_HTML", "views", "app","ESM_ParametrosIniciales");
    $action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","MenuParametrosIniciales");
    $Html_Form=$Obj_Form->main($action,$TipoIdTercero,$request);
    $this->salida = $Html_Form;
		return true;
		}
   		
		/* FUNCION PARAMETRIZAR MEDICOS CON ESM
		*  Funcion que consiste en ASOCIAR profesionales a una ESM
		*  @param NULL
		*	return booleam.
		*/
		
		function Parametrizar_Medico_ESM()
		{
		$request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_Medico_ESM","RemoteXajax","app","ESM_ParametrosIniciales");
		$this->SetXajax(array("Listado_ProfesionalesSinEsm","Asignar_ProfesionalESM","Insertar_TipoFuerza",
                          "Listado_ProfesionalesEnEsm","Borrar_ProfesionalESM","CambioEstado"),null,"ISO-8859-1");

		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
    
    $sql = AutoCarga::factory("Consultas_Medico_ESM","classes","app","ESM_ParametrosIniciales");
    $ESM = $sql->Obtener_ESMs();
    
    $this->IncludeJS("TabPaneLayout");
		$this->IncludeJS("TabPaneApi");
    $this->IncludeJS("TabPane");
		
		$Obj_Form=AutoCarga::factory("Parametrizar_Medico_ESM_HTML", "views", "app","ESM_ParametrosIniciales");
    $action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","MenuParametrosIniciales");
	  $Html_Form=$Obj_Form->main($action,$ESM,$request);
		$this->salida = $Html_Form;
		return true;
		}

		    
    /* FUNCION CREAR NOVEDADES DEVOLUCION PRODUCTOS
		*  Funcion que consiste en Generar la Interfaz para el Ingreso de NOVEDADES DEVOLUCION PRODUCTOS
    *  Para que en otros módulos, puedan ser extraidos y utilizados en devoluciones
		*  @param NULL
		*	return booleam.
		*/

		function Parametrizar_ProductoClasificacion()
		{
		$request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_ProductoClasificacion","RemoteXajax","app","ESM_ParametrosIniciales");
		$this->SetXajax(array("Listado_ProductosEmpresa","Asignar_ProductoClasificacion",
                          "Quitar_ProductoClasificacion"),null,"ISO-8859-1");

		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
    
    $sql = AutoCarga::factory("Consultas_ProductoClasificacion","classes","app","ESM_ParametrosIniciales");
    $empresas = $sql->Obtener_Empresas();
    
    $this->IncludeJS("TabPaneLayout");
		$this->IncludeJS("TabPaneApi");
    $this->IncludeJS("TabPane");
		
		$Obj_Form=AutoCarga::factory("Parametrizar_ProductoClasificacion_HTML", "views", "app","ESM_ParametrosIniciales");
    $action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","MenuParametrosIniciales");
	  $Html_Form=$Obj_Form->main($action,$empresas,$request);
		$this->salida = $Html_Form;
		return true;
		}
    
    /* FUNCION CREAR NOVEDADES DEVOLUCION PRODUCTOS
		*  Funcion que consiste en Generar la Interfaz para el Ingreso de NOVEDADES DEVOLUCION PRODUCTOS
    *  Para que en otros módulos, puedan ser extraidos y utilizados en devoluciones
		*  @param NULL
		*	return booleam.
		*/

		function Parametrizar_TiposFormulas()
		{
		$request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_TiposFormulas","RemoteXajax","app","ESM_ParametrosIniciales");
		$this->SetXajax(array("Listado_TiposFormulas","Ingreso_TipoFormula","Insertar_TipoFormula",
                          "Modificacion_TipoFormula","Modificar_TipoFormula","CambioEstado"),null,"ISO-8859-1");

		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("Parametrizar_TiposFormulas_HTML", "views", "app","ESM_ParametrosIniciales");
    $action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","MenuParametrosIniciales");
	  $Html_Form=$Obj_Form->main($action,$request);
		$this->salida = $Html_Form;
		return true;
		}
		
		
		/* FUNCION PARAMETRIZAR AFILIADOS  CON ESM
		*  Funcion que consiste en ASOCIAR AFILIADOS a una ESM
		*  @param NULL
		*	return booleam.
		*/
		
		function Parametrizar_Afiliados_ESM()
		{
		$request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_Afiliado_ESM","RemoteXajax","app","ESM_ParametrosIniciales");
		$this->SetXajax(array("Listado_ProfesionalesSinEsm","Asignar_ProfesionalESM","Insertar_TipoFuerza",
                          "Listado_ProfesionalesEnEsm","Borrar_ProfesionalESM","CambioEstado"),null,"ISO-8859-1");

		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
    
		$sql = AutoCarga::factory("Consultas_Afiliados_ESM","classes","app","ESM_ParametrosIniciales");
		$ESM = $sql->Obtener_ESMs();
		$TIPO = $sql->ConsultarTipoId();

		$this->IncludeJS("TabPaneLayout");
		$this->IncludeJS("TabPaneApi");
		$this->IncludeJS("TabPane");

		$Obj_Form=AutoCarga::factory("Parametrizar_Afiliados_ESM_HTML", "views", "app","ESM_ParametrosIniciales");
		$action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","MenuParametrosIniciales");
		$Html_Form=$Obj_Form->main($action,$ESM,$request,$TIPO);
		$this->salida = $Html_Form;
		return true;
		}
			/* FUNCION PARAMETRIZAR AFILIADOS  CON FUERZAS
		*  Funcion que consiste en ASOCIAR AFILIADOS a una FUERZAS
		*  @param NULL
		*	return booleam.
		*/
		
		function Parametrizar_Afiliados_Fuerzas()
		{
		$request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_Afiliado_FUERZAS","RemoteXajax","app","ESM_ParametrosIniciales");
		$this->SetXajax(array("Listado_ProfesionalesSinEsm","Asignar_ProfesionalESM","Insertar_TipoFuerza",
                          "Listado_ProfesionalesEnEsm","Borrar_ProfesionalESM","CambioEstado"),null,"ISO-8859-1");

		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
    
		$sql = AutoCarga::factory("Consultas_Afiliados_FUERZAS","classes","app","ESM_ParametrosIniciales");
		$Fuerzas = $sql->Buscar_tipo_evento();
		$TIPO = $sql->ConsultarTipoId();

		$this->IncludeJS("TabPaneLayout");
		$this->IncludeJS("TabPaneApi");
		$this->IncludeJS("TabPane");

		$Obj_Form=AutoCarga::factory("Parametrizar_Afiliados_FUERZAS_HTML", "views", "app","ESM_ParametrosIniciales");
		$action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","MenuParametrosIniciales");
		$Html_Form=$Obj_Form->main($action,$Fuerzas,$request,$TIPO);
		$this->salida = $Html_Form;
		return true;
		}
		
		
		
		/* FUNCION CREAR ESTADOS DOCUMENTOS
		*  Funcion que consiste en Generar estados que pueden tener los documentos x empresa y usuario
		*  @param NULL
		*	return booleam.
		*/

      function Parametrizar_TiposRequisicion()
      {
      $request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_TiposRequisicion","RemoteXajax","app","ESM_ParametrosIniciales");
		$this->SetXajax(array("Listado_TiposRequisicion","Ingreso_TipoRequisicion","Insertar_TipoRequision",
                          "Modificacion_TipoRequision","Modificar_TipoRequision","CambioEstado"),null,"ISO-8859-1");

		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
		$Obj_Form=AutoCarga::factory("Parametrizar_TiposRequisicion_HTML", "views", "app","ESM_ParametrosIniciales");
    	$action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","MenuParametrosIniciales");
	    $Html_Form=$Obj_Form->main($action,$request);
		$this->salida = $Html_Form;
		return true;
      }
	
	 /* FUNCION  PARAMETRIZAR IPS
	
		*  @param NULL
		*	return booleam.
		*/
		
		function Parametrizar_IPS()
		{
		$request = $_REQUEST;

		IncludeFileModulo("Remotos_IPS","RemoteXajax","app","ESM_ParametrosIniciales");
		$this->SetXajax(array("Listado_Terceros","Listado_IPS","Asignar_TerceroESM","Borrar_TerceroESM"),null,"ISO-8859-1");

		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	

		$this->IncludeJS("TabPaneLayout");
		$this->IncludeJS("TabPaneApi");
		$this->IncludeJS("TabPane");

		$sql = AutoCarga::factory("Consultas_IPS","classes","app","ESM_ParametrosIniciales");
		$TipoIdTercero = $sql->Obtener_TiposId();

    $Obj_Form=AutoCarga::factory("Parametrizar_IPS_HTML", "views", "app","ESM_ParametrosIniciales");
    $action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","MenuParametrosIniciales");
    $Html_Form=$Obj_Form->main($action,$TipoIdTercero,$request);
    $this->salida = $Html_Form;
		return true;
		}
		/* FUNCION PARAMETRIZAR  IPS  CON ESM
		*  Funcion que consiste en ASOCIAR IPS  a una ESM
		*  @param NULL
		*	return booleam.
		*/
		
		function Parametrizar_IPS_ESM()
		{
		$request = $_REQUEST;
		    
		IncludeFileModulo("Remotos_IPS_ESM","RemoteXajax","app","ESM_ParametrosIniciales");
		$this->SetXajax(array("Listado_ProfesionalesSinEsm","Asignar_ProfesionalESM","Insertar_TipoFuerza",
                          "Listado_ProfesionalesEnEsm","Borrar_ProfesionalESM","CambioEstado"),null,"ISO-8859-1");

		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
    
		$sql = AutoCarga::factory("Consultas_IPS_ESM","classes","app","ESM_ParametrosIniciales");
		$ESM = $sql->Obtener_ESMs();
		$TIPO = $sql->ConsultarTipoId();

		$this->IncludeJS("TabPaneLayout");
		$this->IncludeJS("TabPaneApi");
		$this->IncludeJS("TabPane");

		$Obj_Form=AutoCarga::factory("Parametrizar_IPS_ESM_HTML", "views", "app","ESM_ParametrosIniciales");
		$action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","MenuParametrosIniciales");
		$Html_Form=$Obj_Form->main($action,$ESM,$request,$TIPO);
		$this->salida = $Html_Form;
		return true;
		}
		
			/* FUNCION PARAMETRIZAR MEDICOS CON IPS
		*  Funcion que consiste en ASOCIAR profesionales a una IPS
		*  @param NULL
		*	return booleam.
		*/
		
		function Parametrizar_Medico_IPS()
		{
			$request = $_REQUEST;

			IncludeFileModulo("Remotos_Medico_IPS","RemoteXajax","app","ESM_ParametrosIniciales");
			$this->SetXajax(array("Listado_ProfesionalesSinEsm","Asignar_ProfesionalESM","Insertar_TipoFuerza",
			"Listado_ProfesionalesEnEsm","Borrar_ProfesionalESM","CambioEstado"),null,"ISO-8859-1");

			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");	

			$sql = AutoCarga::factory("Consultas_Medico_IPS","classes","app","ESM_ParametrosIniciales");
			$ESM = $sql->Obtener_IPS();
			

			$this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
			$this->IncludeJS("TabPane");

			$Obj_Form=AutoCarga::factory("Parametrizar_Medico_IPS_HTML", "views", "app","ESM_ParametrosIniciales");
			$action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","MenuParametrosIniciales");
			$Html_Form=$Obj_Form->main($action,$ESM,$request);
			$this->salida = $Html_Form;
		return true;
		}
		
		function Parametrizar_Topes()
		{
		$request = $_REQUEST;
		$sql = AutoCarga::factory("Consultas_Topes","classes","app","ESM_ParametrosIniciales");
		
		if($request['registros']>0)
			{
			for($i=0;$i<$request['registros'];$i++)
				{
				if($request['tipo_formula_id'.$i]!="")
						if($request['tope'.$i]!="")
							if($request['operacion'.$i]==='0')
								{
									$query .= " INSERT INTO esm_topes_dispensacion
										(
										empresa_id, 	
										centro_utilidad, 	
										tipo_formula_id, 	
										tope_mensual, 	
										usuario_id
										)
										VALUES
										(
										'".trim($request['empresa_id'.$i])."',
										'".trim($request['centro_utilidad'.$i])."',
										'".trim($request['tipo_formula_id'.$i])."',
										'".trim($request['tope'.$i])."',
										'".UserGetUID()."'
										); \n";
								}
								else
									if($request['operacion'.$i]==='1')
									{
									$query .= " UPDATE esm_topes_dispensacion
										SET tope_mensual = '".trim($request['tope'.$i])."',
										usuario_id = ".UserGetUID().",
										fecha_registro = NOW()
										WHERE TRUE
										AND empresa_id = '".trim($request['empresa_id'.$i])."'
										AND centro_utilidad =  '".trim($request['centro_utilidad'.$i])."'
										AND tipo_formula_id =  '".trim($request['tipo_formula_id'.$i])."'; \n";
									}
				}
		
		$sql ->Ejecutar($query);
			}
		/*print_r($query); */
		
		$datos = $sql->Listar_CentrosUtilidad($request['buscador']);
		$TiposFormulas = $sql->Listar_TiposFormulas();
		$html=AutoCarga::factory("ESM_ParametrosIniciales_MenuHTML", "views", "app","ESM_ParametrosIniciales");
		$action['guardar'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","Parametrizar_Topes",array("buscador"=>$request['buscador']));
		$action['volver'] = ModuloGetURL("app","ESM_ParametrosIniciales","controller","MenuParametrosIniciales");
		$this->salida = $html->Forma_Topes($request,$action,$datos,$TiposFormulas);
		return true;
		}
    
   
  }
?>