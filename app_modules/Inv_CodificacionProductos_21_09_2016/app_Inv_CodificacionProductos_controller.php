<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: app_Inv_CodificacionProductos_controller.php,v 1.18 2010/01/19 13:23:00 mauricio Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Medina Santacruz
 */

/**
 * Clase Control: Inv_CodificacionProductos
 * Clase encargada del control de llamado de metodos en el modulo
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.18 $
 * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Medina Santacruz
 */
class app_Inv_CodificacionProductos_controller extends classModulo {

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
    function app_Inv_CodificacionProductos_controller()
    {
        
    }

    /**
     * Funcion principal del modulo
     *
     * @return boolean
     */
    function main()
    {



        $request = $_REQUEST;

        $url[0] = 'app';                         //Tipo de Modulo
        $url[1] = 'Inv_CodificacionProductos';   //Nombre del Modulo
        $url[2] = 'controller';                  //Si es User,controller...
        $url[3] = 'MenuCodificacionProductos';   //Metodo.
        $url[4] = 'datos';      //vector de $_request.
        $arreglo[0] = 'EMPRESA';     //Sub Titulo de la Tabla
        //Generar de Busqueda de Permisos SQL
        $obj_busqueda = AutoCarga::factory("Permisos", "", "app", "Inv_CodificacionProductos");
        //Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
        $datos = $obj_busqueda->BuscarPermisos();

        //Generamos el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
        // Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
        $forma = gui_theme_menu_acceso("CODIFICACION PRODUCTOS", $arreglo, $datos, $url, ModuloGetURL('system', 'Menu'));
        $this->salida = $forma;

        /* 			
          //(nombre de la Tabla Acceso,
          FormaMostrarMenuHospitalizacion($forma); //Invocar un view, para mostrar la informacion.
         */
        return true;
    }

    /**
     * Funcion Crear Modulo del modulo
     * Con esta Funcion, procedemos a crear el Menú de opciones como:
     * 1- Crear Moleculas.
     * 2- Crear Laboratorios.
     * 3- Clasificacion General de los Productos.
     * @return boolean
     */
    function MenuCodificacionProductos()
    {
        $sw = $_REQUEST['datos']['sw_tipo_empresa'];
        $EmpresaId = $_REQUEST['datos']['empresa_id'];
        SessionSetVar("empresa_id", $EmpresaId);

        /* Crear el Menú de Opciones */
        $Obj_Menu = AutoCarga::factory("CodificacionProductos_MenuHTML", "views", "app", "Inv_CodificacionProductos");

        //Volver a Empresas.

        $action['volver'] = ModuloGetURL("app", "Inv_CodificacionProductos", "controller", "main");

        /* if($_REQUEST['datos']['sw_tipo_empresa']!="")
          { */
        //print_r($_REQUEST['datos']);
        /* */
        //}
        //echo SessionGetVar("sw_tipo_empresa");
        //print_r($_REQUEST);
        //print_r($action['volver']);
        //Mostramos el Objeto Creado.
        $this->salida = $Obj_Menu->Menu($action, $sw, $EmpresaId);

        return true;
    }

    /* FUNCION CREAR MOLÉCULAS
     *  Funcion que consiste en Generar la Interfaz para el Ingreso de Moléculas al sistema.
     *  @param NULL
     * 	return booleam.
     */

    function CrearMoleculas()
    {
		
        $request = $_REQUEST;
        //Incluye al script controller el archivo remotos de XAJAX.
        IncludeFileModulo("Remotos", "RemoteXajax", "app", "Inv_CodificacionProductos");
        $this->SetXajax(array("IngresoMolecula", "InsertarMolecula", "ModificarMolecula", "CambioEstadoMolecula", "MoleculasT", "GuardarModMolecula", "BusquedaMolecula_Nombre"), null, "ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $Obj_Form = AutoCarga::factory("Formularios_HTML", "views", "app", "Inv_CodificacionProductos");
        //Antes de crear el formulario para crear laboratorios, incluir informacion extraída de la base de datos como los Paises.

        $Obj_Consultas = AutoCarga::factory("Consultas", "", "app", "Inv_CodificacionProductos");
        $UnidadMedidaMedicamentos = $Obj_Consultas->Listar_Unidades_Medida_Medicamento();
        //Boton Volver==>Ruta
        $action['volver'] = ModuloGetURL("app", "Inv_CodificacionProductos", "controller", "MenuCodificacionProductos") . "&datos[sw_tipo_empresa]=" . $_REQUEST['datos']['sw_tipo_empresa'] . "&datos[empresa_id]=" . $_REQUEST['datos']['empresa_id'] . "";
        //$action['paginador'] = ModuloGetURL("app","Inv_CodificacionProductos","controller","CrearLaboratorios");


        $datos = $Obj_Consultas->BuscarPaises("NULL");
        $sw_medicamento = "1";
        $Moleculas = $Obj_Consultas->Listar_Moleculas($sw_medicamento);


        //Genero Formulario para la creacion de moléculas.
        $Html_FormMolecula = $Obj_Form->Form_CrearMolecula($action, $Moleculas, $request);

        //Imprimo Formulario.		
        $this->salida = $Html_FormMolecula;


        return true;
    }

    /* FUNCION CREAR TIPOS INSUMOS
     *  Funcion que consiste en Generar la Interfaz para el Ingreso de TIPOS DE INSUMOS en la misma tabla de moléculas
     *  Debido a que no son solo medicamentos los que estarán ingresando al sistema, tambien Insumos.
     *  @param NULL
     * 	return booleam.
     */

    function CrearTiposInsumos()
    {
        $request = $_REQUEST;
        //Incluye al script controller el archivo remotos de XAJAX.
        IncludeFileModulo("RemotosTipoInsumo", "RemoteXajax", "app", "Inv_CodificacionProductos");
        $this->SetXajax(array("IngresoMolecula", "InsertarMolecula", "ModificarMolecula",
            "CambioEstadoMolecula", "MoleculasT", "GuardarModMolecula",
            "BusquedaMolecula_Nombre"), null, "ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $Obj_Form = AutoCarga::factory("Formularios_HTML", "views", "app", "Inv_CodificacionProductos");
        //Antes de crear el formulario para crear laboratorios, incluir informacion extraída de la base de datos como los Paises.

        $Obj_Consultas = AutoCarga::factory("Consultas", "", "app", "Inv_CodificacionProductos");
        $UnidadMedidaMedicamentos = $Obj_Consultas->Listar_Unidades_Medida_Medicamento();
        //Boton Volver==>Ruta
        $action['volver'] = ModuloGetURL("app", "Inv_CodificacionProductos", "controller", "MenuCodificacionProductos") . "&datos[sw_tipo_empresa]=" . $_REQUEST['datos']['sw_tipo_empresa'] . "&datos[empresa_id]=" . $_REQUEST['datos']['empresa_id'] . "";
        //$action['paginador'] = ModuloGetURL("app","Inv_CodificacionProductos","controller","CrearLaboratorios");


        $datos = $Obj_Consultas->BuscarPaises("NULL");
        $sw_medicamento = "0";
        $TiposInsumos = $Obj_Consultas->Listar_Moleculas($sw_medicamento);


        //Genero Formulario para la creacion de moléculas.
        $Html_FormTipoInsumo = $Obj_Form->Form_CrearTipoInsumo($action, $TiposInsumos, $request);

        //Imprimo Formulario.		
        $this->salida = $Html_FormTipoInsumo;


        return true;
    }

    /* FUNCION CREAR LABORATORIOS
     * 	Funcion que consiste en Generar la Interfaz para el Ingreso de Moléculas al sistema.
     * 	@param NULL
     * 	return booleam.
     */

    function CrearLaboratorios()
    {
        $request = $_REQUEST;
        //Incluye al script controller el archivo remotos de XAJAX.
        IncludeFileModulo("Remotos", "RemoteXajax", "app", "Inv_CodificacionProductos");
        $this->SetXajax(array("IngresoLaboratorio", "InsertarLaboratorio", "ModificarLaboratorio", "CambioEstado", "LaboratoriosT", "GuardarModLaboratorio", "LaboratorioProveedor", "Actividades_sgrupo", "GuardarProveedor", "IngresoTitular", "IngresoLaboratorioFabricante", "GuardarLaboratorioFabricante", "BusquedaLaboratorio_Codigo", "BusquedaLaboratorio_Nombre"), null, "ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $Obj_Form = AutoCarga::factory("Formularios_HTML", "views", "app", "Inv_CodificacionProductos");
        //Antes de crear el formulario para crear laboratorios, incluir informacion extraída de la base de datos como los Paises.

        $Obj_Consultas = AutoCarga::factory("Consultas", "", "app", "Inv_CodificacionProductos");
        //Boton Volver==>Ruta
        $action['volver'] = ModuloGetURL("app", "Inv_CodificacionProductos", "controller", "MenuCodificacionProductos") . "&datos[sw_tipo_empresa]=" . $_REQUEST['datos']['sw_tipo_empresa'] . "&datos[empresa_id]=" . $_REQUEST['datos']['empresa_id'] . "";
        //$action['paginador'] = ModuloGetURL("app","Inv_CodificacionProductos","controller","CrearLaboratorios");


        $datos = $Obj_Consultas->BuscarPaises("NULL");
        $Laboratorios = $Obj_Consultas->Listar_Laboratorios(NULL);


        //Genero Formulario para la creacion de laboratorios.
        $Html_FormLaboratorio = $Obj_Form->Form_CrearLaboratorio($action, $datos, $Laboratorios, $request);

        //Imprimo Formulario.		
        //$this->salida = $datos;
        $this->salida = $Html_FormLaboratorio;


        return true;
    }

    /* FUNCION CLASIFICACION_PRODUCTOS
     * 	Funcion que consiste en Generar la Interfaz para la clasificacion de productos
     * Grupo - Clase y subclase con un submenú Indicando si primero se va  a hacer la clasificacion
     * o creacion del producto, partiendo de una clasificacion previamente hecha.
     * 	@param NULL
     * 	return booleam.
     */

    function Clasificacion_Productos()
    {

        $request = $_REQUEST;
        //Incluye al script controller el archivo remotos de XAJAX.
        IncludeFileModulo("CodificacionRemotos", "RemoteXajax", "app", "Inv_CodificacionProductos");
        $this->SetXajax(array("SubclasesAsignadas", "SubclasesNoAsignadas", "AsignarClases", "IngresoGrupo", "InsertarGrupo", "ModificarGrupo", "BorrarGrupo", "GruposT",
            "GuardarModGrupo", "ClasesSubclases", "AsignarClaseAGrupo", "BorrarClases", "Subclases",
            "BusquedaClasesAsignadas", "BusquedaClasesNoAsignadas", "AsignarSubclaseAClase", "BorrarSubClases",
            "BusquedaSubClasesAsignadas", "BusquedaSubClasesNoAsignadas"), null, "ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $Obj_Form = AutoCarga::factory("CodificacionProductos_HTML", "views", "app", "Inv_CodificacionProductos");
        //Antes de crear el formulario para crear laboratorios, incluir informacion extraída de la base de datos como los Paises.
        //print_r($request);
        $Obj_Consultas = AutoCarga::factory("ConsultasClasificacion", "", "app", "Inv_CodificacionProductos");
        //Boton Volver==>Ruta
        $action['volver'] = ModuloGetURL("app", "Inv_CodificacionProductos", "controller", "MenuCodificacionProductos") . "&datos[sw_tipo_empresa]=" . $_REQUEST['datos']['sw_tipo_empresa'] . "&datos[empresa_id]=" . $_REQUEST['datos']['empresa_id'] . "";

        //$action['volver'] = ModuloGetURL("app","Inv_CodificacionProductos","controller","MenuCodificacionProductos")."&datos[sw_tipo_empresa]=".$_REQUEST['datos']['sw_tipo_empresa']."";
        //$action['paginador'] = ModuloGetURL("app","Inv_CodificacionProductos","controller","CrearLaboratorios");
        //SessionSetVar("sw_tipo_empresa",$request['datos']['sw_tipo_empresa']);
        $Grupos = $Obj_Consultas->ListadoGrupos();


        //$datos=$Obj_Consultas->BuscarPaises("NULL");
        //Genero Formulario para la creacion de laboratorios.
        $Html_Clasificacion_Productos = $Obj_Form->main($request, $Grupos, $action);

        //Imprimo Formulario.		
        //$this->salida = $datos;
        $this->salida = $Html_Clasificacion_Productos;

        return true;
    }

    /* FUNCION CREACION DE PRODUCTOS
     * 	Funcion que consiste en Generar la Interfaz para la clasificacion de productos
     * Grupo - Clase y subclase con un submenú Indicando si primero se va  a hacer la clasificacion
     * o creacion del producto, partiendo de una clasificacion previamente hecha.
     * 	@param NULL
     * 	return booleam.
	 * @actualizacion: Se añade la funcion GuardarNivelesDeAtencion
     */

    function Crear_Productos()
    {

        $request = $_REQUEST;

        /* echo "ses:<pre>";
          print_r($_SESSION);
          echo "</pre>"; */

        //Incluye al script controller el archivo remotos de XAJAX.
        IncludeFileModulo("CrearProductosRemotos", "RemoteXajax", "app", "Inv_CodificacionProductos");
        $this->IncludeJS("TabPaneLayout");
        $this->IncludeJS("TabPaneApi");
        $this->IncludeJS("TabPane");
        $this->SetXajax(array("Continuar_Con_Clases", "Continuar_Con_SubClases", "IngresoProducto", "GruposT",
            "BusquedaClasesAsignadas", "BusquedaSubClasesAsignadas", "BusquedaAcuerdo228", "BusquedaMensajeProducto",
            "BusquedaTitular", "BusquedaFabricante", "IngresoProductoMedicamento", "ListaEspecialidades", "InsertarEspxProd",
            "ListaEspecialidadxProducto", "BorrarEspxProd", "InsertarProductoInsumo", "InsertarProductoMedicamento", "Productos_Creados",
            "ModProducto", "ModificarProductoInsumo", "ModProductoMedicamento", "ModificarProductoMedicamento", "asignar", "borrar", "CambioEstadoProducto",
            "Buscar_ProductoConCodigoBarras", "buscar_clases_grupo", "buscar_subclases_clase_grupo", "Productos_CreadosBuscados", "ListaViasAdministracion",
            "ListaViasAdministracionxProducto", "InsertarViadxProd", "BorrarViadxProd", "Volver", "Modificar_ClasificacionProducto",
            "Guardar_NuevaClasificacion", "Clase", "SubClase", "MostrarMedicamentosF", "InsertarFacCon", "EliminarFacCon", "GuardarNivelesDeAtencion"), null, "ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $Obj_Form = AutoCarga::factory("CrearProductos_HTML", "views", "app", "Inv_CodificacionProductos");
        //Antes de crear el formulario para crear laboratorios, incluir informacion extraída de la base de datos como los Paises.

        $Obj_Consultas = AutoCarga::factory("ConsultasClasificacion", "", "app", "Inv_CodificacionProductos");
        $Grupos = $Obj_Consultas->ListadoGrupos();
        //Boton Volver==>Ruta
        $action['volver'] = ModuloGetURL("app", "Inv_CodificacionProductos", "controller", "Clasificacion_Productos") . "&datos[sw_tipo_empresa]=" . $_REQUEST['datos']['sw_tipo_empresa'] . "&datos[empresa_id]=" . $_REQUEST['datos']['empresa_id'] . "";

        $PerfilesTerapeuticos = $Obj_Consultas->Listar_Perfiles_Terapeuticos();
        //$datos=$Obj_Consultas->BuscarPaises("NULL");
        //Genero Formulario para la creacion de laboratorios.
        $Html_Crear_Productos = $Obj_Form->main($request, $Grupos, $action, $PerfilesTerapeuticos);

        //Imprimo Formulario.		
        //$this->salida = $datos;
        $this->salida .= $Html_Crear_Productos;
        return true;
    }

    function AsignarEspecialidadesAMedicamentos()
    {

        $request = $_REQUEST;
        //Incluye al script controller el archivo remotos de XAJAX.
        IncludeFileModulo("RemotosAsignarEspecialidadesAMedicamentos", "RemoteXajax", "app", "Inv_CodificacionProductos");

        $this->SetXajax(array("CentrosDeUtilidad", "UnidadesFuncionales"), null, "ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $sql = AutoCarga::factory("ConsultasAsignarEspecialidadesAMedicamentos", "", "app", "Inv_CodificacionProductos");

        $Empresas = $sql->Listar_Empresas();

        $Obj_Form = AutoCarga::factory("AsignarEspecialidadesAMedicamentos_HTML", "views", "app", "Inv_CodificacionProductos");


        //Boton Volver==>Ruta
        $action['volver'] = ModuloGetURL("app", "Inv_CodificacionProductos", "controller", "main");
        //$action['volver'] = ModuloGetURL("app","Inv_CodificacionProductos","controller","MenuCodificacionProductos");


        $Html_Crear_Productos = $Obj_Form->main($request, $Empresas, $action);

        //Imprimo Formulario.		
        //$this->salida = $datos;
        $this->salida .= $Html_Crear_Productos;

        return true;
    }

    /*
     * Funcion que permite consultar las auditorias de los productos
     *  @return boolean
     */

    function ConsultarAuditoriaProductos()
    {
        $request = $_REQUEST;
        
        $mdl = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");
        $act = AutoCarga::factory("CodificacionProductos_HTML", "views", "app", "Inv_CodificacionProductos");
        
        if (!empty($request['buscador']))
        {
            //$request['buscador']['empresa_id'] = $session['DatosEmpresaAF']['empresa_id'];
            $datosProducto = $mdl->Buscar_DetalleProducto($request['buscador']['codigo_producto']);

            $datosAuditoriaProducto = $mdl->Buscar_AuditoriaProducto($request['buscador']['codigo_producto']);
            
            $datosMedicamento = $mdl->Buscar_DetalleMedicamento($request['buscador']['codigo_producto']);
            
            $datosAuditoriaMedicamento = $mdl->Buscar_AuditoriaMedicamento($request['buscador']['codigo_producto']);
            
            $datosAuditoriaEstadoProducto = $mdl->Buscar_AuditoriaEstadoProducto($request['buscador']['codigo_producto']);
        }
        
        $action['volver'] = ModuloGetURL("app", "Inv_CodificacionProductos", "controller", "MenuCodificacionProductos") . "&datos[sw_tipo_empresa]=" . $_REQUEST['datos']['sw_tipo_empresa'] . "&datos[empresa_id]=" . $_REQUEST['datos']['empresa_id'] . "";
        $this->salida = $act->FormaConsultarAuditoriasProductos($action, $request['buscador'], $datosProducto, $datosAuditoriaProducto, $datosMedicamento, $datosAuditoriaMedicamento, $datosAuditoriaEstadoProducto);
        return true;
    }

    function AsignarEspecialidadesAMedicamentos2()
    {

        $request = $_REQUEST;
        //Incluye al script controller el archivo remotos de XAJAX.
        IncludeFileModulo("RemotosAsignarEspecialidadesAMedicamentos", "RemoteXajax", "app", "Inv_CodificacionProductos");

        $this->SetXajax(array("ListadoMedicamentos", "Especialidades", "EspecialidadesSinAsignar",
            "EspecialidadesAsignadas",
            "InsertarEspecialidades", "BorrarEspecialidades", "ListarMedicamentos"), null, "ISO-8859-1"); //Registrando las funciones que hay en Php "remotos"
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $this->IncludeJS("TabPaneLayout");
        $this->IncludeJS("TabPaneApi");
        $this->IncludeJS("TabPane");

        $sql = AutoCarga::factory("ConsultasAsignarEspecialidadesAMedicamentos", "", "app", "Inv_CodificacionProductos");
        $Departamentos = $sql->Listar_Departamentos($request['empresa_id'], $request['centro_utilidad'], $request['unidad_funcional']);

        $Obj_Form = AutoCarga::factory("AsignarEspecialidadesAMedicamentos_HTML", "views", "app", "Inv_CodificacionProductos");


        //Boton Volver==>Ruta
        $action['volver'] = ModuloGetURL("app", "Inv_CodificacionProductos", "controller", "AsignarEspecialidadesAMedicamentos");
//            $action['volver'] = ModuloGetURL("app","Inv_CodificacionProductos","controller","AsignarEspecialidadesAMedicamentos");


        $Html = $Obj_Form->main2($request, $Departamentos, $Grupos, $action);

        //Imprimo Formulario.		
        //$this->salida = $datos;*/
        $this->salida .= $Html;
        return true;
    }

}

