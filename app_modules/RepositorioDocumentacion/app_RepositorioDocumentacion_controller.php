<?php

/**
 * @package DUANA & CIA
 * @version 1.0 $Id: app_RepositorioDocumentacion_controller.php,v 1.0 $
 * @copyright DUANA & CIA
 * @author R.O.M.A
 */

/**
 * Clase Control: RepositorioDocumentacion
 * Responsabilidad: Clase encargada del control de llamado de metodos en el modulo
 * */
/*
  if (!IncludeClass('LogMenus'))
  {
  die(MsgOut("Error al incluir archivo","LogMenus"));
  }

 */

class app_RepositorioDocumentacion_controller extends classModulo {

    /**
     * @var array $action  Vector donde se almacenan los links de la aplicacion
     */
    var $action = array();

    /**
     * @var array $request Vector donde se almacenan los datos pasados por request
     */
    var $request = array();

    /*     * **********************************************************
     * Constructor de la clase
     * ********************************************************** */

    function app_RepositorioDocumentacion_controller() {
        
    }

    /*     * ********************************************************** 
      Funcion principal del modulo
      @return boolean
     * ********************************************************** */

    function main() {

        $request = $_REQUEST;

        /*         * *registro log x acceso en menus** */
        /*if ($request['module'] || $request['menuOp']) {
            $moduleLog = $request['module'];
            $menuLog = $request['menuOp'];
            $VarClass = new LogMenus();
            $LogApp = $VarClass->RegistrarAccesoAplicacion($moduleLog, $menuLog);
        }*/
        /*         * *************************************** */

        $url[0] = 'app';                                      //Tipo de Modulo
        $url[1] = 'RepositorioDocumentacion';    //Nombre del Modulo
        $url[2] = 'controller';                     //tipo controller...
        $url[3] = 'MenuFarmacias';              //Metodo.
        $url[4] = 'datos';         //vector de $_request.
        $arreglo[0] = 'EMPRESAS';     //Sub Titulo de la Tabla
        //Generar busqueda de Permisos SQL
        $obj_perm = AutoCarga::factory("Permisos", "classes", "app", "RepositorioDocumentacion");
        $datos = $obj_perm->BuscarPermisos(UserGetUID());
        
        //var_dump($datos);
        // Menu de empresas con permiso 
        $forma = gui_theme_menu_acceso("REPOSITORIO DE DOCUMENTACION ", $arreglo, $datos, $url, ModuloGetURL('system', 'Menu'));
        $this->salida = $forma;

        return true;
    }

    /*     * *************************************************************
     * Funcion de menu bodegas
     * ************************************************************* */

    function MenuFarmacias() {
        /* Crear el Menu de farmacias empresa */
        $request = $_REQUEST;
        if ($request['datos']['empresa_id'])
            SessionSetVar("empresa_id", $request['datos']['empresa_id']);

        $obj_perm = AutoCarga::factory("Permisos", "", "app", "RepositorioDocumentacion");
        $datos_bod = $obj_perm->BuscarBodegas($request['datos']['empresa_id']);

        $Obj_Menu = AutoCarga::factory("Repositorio_MenuHTML", "views", "app", "RepositorioDocumentacion");

        $action['volver'] = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "main") . "&datos[empresa_id]=" . $request['datos']['empresa_id'] . "";

        //<a href=\"".$action['aceptar'].URLRequest(array("archivo_subir"=>"MD"))

        $this->salida = $Obj_Menu->MenuBod($action, $datos_bod);

        return true;
    }

    /*     * *************************************************************
     * Funcion de submenu de opciones de repositorio
     * ************************************************************* */

    function SubMenuOp() {
        //Crear el SubMenu de Opciones
        $request = $_REQUEST;
        $empresa = $_REQUEST['datos']['empresa_id'];
        $bodega = $_REQUEST['datos']['bodega'];
        $bodname = $_REQUEST['datos']['bodname'];

        $Obj_Menu = AutoCarga::factory("Repositorio_MenuHTML", "views", "app", "RepositorioDocumentacion");

        //Volver a Empresas.
        $action['volver'] = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "main");

        $this->salida = $Obj_Menu->FormaSubMenu($action, $empresa, $bodega, $bodname);

        return true;
    }

    /*     * *************************************************************
     * Funcion : control tipo de archivos a subir
     * ************************************************************* */

    function Control_tipoArchivos() {
        IncludeFileModulo("Remotos_repositorio", "RemoteXajax", "app", "RepositorioDocumentacion");

		$this->SetXajax(array("Campos_tipoArch","GetCentroU","GetBodega","GetBodegaAll","BuscarProducto1","guardarProdsTemp","EliminaProductoTmp","guardarProdsTempCTC","EliminaProductoTmpCTC",),null,"ISO-8859-1");		

        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $request = $_REQUEST;
        $empresa = $_REQUEST['datos']['empresa_id'];
        $bodega = $_REQUEST['datos']['bodega'];
        $bodname = $_REQUEST['datos']['bodname'];

        $vista = AutoCarga::factory("Repositorio_MenuHTML", "views", "app", "RepositorioDocumentacion");
        $cls = AutoCarga::factory("DMLs_repositorio", "", "app", "RepositorioDocumentacion");
		
		$datosTipo = $cls->Listar_TipoDocumento(1);
        //$datosTipo = $cls->Listar_TipoArch(1);

        $action['volver'] = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "SubMenuOp") . "&datos[empresa_id]=" . $empresa . "&datos[bodega]=" . $bodega . "&datos[bodname]=" . $bodname;
        $this->salida = $vista->FormaTipoArchivoRep($action, $datosTipo, $empresa, $bodega);

        return true;
    }

    /*     * *************************************************************
     * Funcion : parametros previos para el cargue de 
     * archivos al repositorio 
     * ************************************************************* */

    function Subirimagen() {
        $request = $_REQUEST; //datos del form
        //print_r($request);
		
		/**
		* +Descripcion Se valida que se seleccione el tipo de informe
		* @fecha 14/01/2016
		* 
		**/
		if($request[tipo_infor] == -1){
			
			$html = "
                    <script>
                        alert('Debe seleccionar el tipo de informe');
                        history.go(-1);
                    </script>
                ";
				
				$this->salida = $html;
				return true;
		
		}
		
		/**
		* +Descripcion Se valida que se seleccione el tipo de formulario (Selectivo)
		* @fecha 14/01/2016
		* 
		**/
		if($request[tipo_infor] == 11){
			/**
			* +Descripcion Se valida si se envian campos vacios retornando una respuesta
			*              al usuario de que los campos son obligatorios
			* @fecha 14/01/2016
			* 
			**/
			if($request[selectivoEstado] == null || $request[selectivoEstado] == "" || $request[selectivoEstado] == 0
			|| $request[fecha_infor]     == null || $request[fecha_infor]     == "" 
			|| $request[tipoSelectivo]   == null || $request[tipoSelectivo]   == ""
			|| $request[tipo_pro]        == null || $request[tipo_pro]        == "" || $request[tipo_pro] == 0){
			 $html = "
                    <script>
                        alert('Debe diligenciar todo el formulario');
                        history.go(-1);
                    </script>
                ";
				
				$this->salida = $html;
				return true;
			}
				
		}
		
		
		
		/**
		* +Descripcion Se valida que se seleccione el tipo de formulario (FORMULACION DIARIA)
		* @fecha 14/01/2016
		* 
		**/
		if($request[tipo_infor] == 3){
			/**
			* +Descripcion Se valida si se envian campos vacios retornando una respuesta
			*              al usuario de que los campos son obligatorios
			* @fecha 14/01/2016
			* 
			**/
			if($request[nom_infor]			 == null || $request[nom_infor] 	     == "" 
			|| $request[fecha_infor]    	 == null || $request[fecha_infor]        == "" 
			|| $request[nro_idenficacion]    == null || $request[nro_idenficacion]   == ""
			|| $request[tipoIdPaciente]  	 == null || $request[tipoIdPaciente]     == "" || $request[tipoIdPaciente]   == -1
			|| $request[tipo_pro]        	 == null || $request[tipo_pro]           == "" || $request[tipo_pro] == 0){
			 $html = "
                    <script>
                        alert('Debe diligenciar todo el formulario');
                        history.go(-1);
                    </script>
                ";
				
				$this->salida = $html;
				return true;
			}
				
		}
		
		/**
		* +Descripcion Se valida que se seleccione el tipo de formulario (FORMULACION DIARIA COSMITET)
		* @fecha 14/01/2016
		* 
		**/
		if($request[tipo_infor] == 5){
			/**
			* +Descripcion Se valida si se envian campos vacios retornando una respuesta
			*              al usuario de que los campos son obligatorios
			* @fecha 14/01/2016
			* 
			**/
			if($request[tipo_pro]			 == null || $request[tipo_pro] 	        == "" 
			|| $request[fecha_infor]    	 == null || $request[fecha_infor]       == "" 
			|| $request[valorFacturado]    	 == null || $request[valorFacturado]    == ""
			|| $request[nomQuienEntrega]     == null || $request[nomQuienEntrega]   == ""
			|| $request[nomQuienRecibe]      == null || $request[nomQuienRecibe]    == ""
			|| $request[nom_infor]            == null || $request[nom_infor]          == ""){
			 $html = "
                    <script>
                        alert('Debe diligenciar todo el formulario (FORMULACION DIARIA COSMITET)');
                       // history.go(-1);
                    </script>
                ";
				
				$this->salida = $html;
				return true;
			}
				
		}
		
        $act = AutoCarga::factory("Repositorio_MenuHTML", "views", "app", "RepositorioDocumentacion");
        $cls = AutoCarga::factory("Permisos", "", "app", "RepositorioDocumentacion");
        $tipoDoc = $cls->GetTipoArch_upload($request['tipo_arch']); //Nombre tipo doc

        $action['volver'] = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "MenuFarmacias") . "&datos[empresa_id]=" . $_REQUEST['datos']['empresa_id'];
        $action['imagenCtrler'] = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "imagenUpload");

        $this->salida = $act->FormaSubir($action, $request, $tipoDoc);

        return true;
    }

    /*     * *************************************************************************
     * Funcion : Cargar archivo a repositorio
     * Registrar en la tabla tipos_campos_tbl_repositorio los campos
     * para cada tipo de doc (inputs del form)
     * ************************************************************************** */

    function imagenUpload() {
        $request = $_REQUEST; //datos del form      
        $vista = AutoCarga::factory("Repositorio_MenuHTML", "views", "app", "RepositorioDocumentacion");
        $cls = AutoCarga::factory("DMLs_repositorio", "", "app", "RepositorioDocumentacion");

        $action['volver'] = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "main");




        if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {
            // archivo temporal (ruta y nombre temp.tmp).
            $tmp_name = $_FILES["archivo"]["tmp_name"];

            //Obtener del array FILES (superglobal) los datos del binario, nombre, tamano y tipo.//
            $user = UserGetUID();
            $random = rand(0, 1000);                                                   //obtiene un numero randomico con valor semilla 1000 
            $type = $_FILES["archivo"]["type"];                                   //Tipo de archivo
            $size = $_FILES["archivo"]["size"];                                    //Tamano de archivo en bytes
            $nombre = basename($_FILES["archivo"]["name"]);            //nombre de archivo (basename devuelve el nombre base en el path.)
            $dataimg = explode(".", $nombre);                                      //separa la extension del archivo sin el punto (genera un arreglo)
            $fp = fopen($tmp_name, "rb");                                           //fopen : abrir el archivo, [ 'r' Apertura para solo lectura], [ 'b' para forzar el modo binario, traduccion a este modo]
            $buffer = fread($fp, filesize($tmp_name));                           //Devolver cadena leida, Lectura del fichero en modo binario seguro con el tamano especificado
            fclose($fp);                                                                       //cerrar el archivo abierto
            $buffer = addslashes($buffer);                                              //pg_escape_bytea($buffer); [Devuelve la cadena con barras invertidas delante de los caracteres esp. que necesitan escaparse] 
            $path_upload = 'escaneos/';                                               //carpeta destino del directorio remoto (si se quiere usar variable, por defecto esta 'repositorio')
            $path_app = basename(GetVarConfigaplication('DIR_SIIS')); //nombre aplicacion

            /* ------Validacion variables del formulario segun tipo archivo para indentacion doc------- */
            // se pueden usar las variables de cada tipo para cambiar o reorganizar la nomenclatura
            //de cada documento a cargar en el repositorio
            switch ($request['tipo_arch']) {
                case 1: //Ordenes requisicion
                    $emp_orden = $request['empresa_arch'];
                    $Cu_orden = $request['centro_utilidad_arch'];
                    $bod_orden = $request['bodega_arch'];
                    $dpto_orden = $request['dpto_arch'];
                    $num_orden = $request['num_requisicion'];
                    $renombrar = 'OR_' . $emp_orden . $bod_orden . $dpto_orden . "_" . $num_orden . "_" . $random . "." . $dataimg[1];
                    break;

                case 2: //Ordenes suministro
                    $emp_sum = $request['empresa_arch'];
                    $Cu_sum = $request['centro_utilidad_arch'];
                    $bod_sum = $request['bodega_arch'];
                    $num_sum = $request['num_suministro'];
                    $renombrar = 'OS_' . $emp_sum . $bod_sum . "_" . $num_sum . "_" . $random . "." . $dataimg[1];
                    break;

                case 3: //Formulas
                    $emp_form = $request['empresa_arch'];
                    $bod_form = $request['bodega_arch'];
                    $num_form = $request['num_formula'];
                    $tipo_id = $request['tipo_id'];
                    $num_id = $request['num_id'];
                    $renombrar = 'FR_' . $emp_form . $bod_form . "_" . $num_form . "_" . $random . "." . $dataimg[1];
                    break;

                case 4: //CTC
                    $emp_ctc = $request['empresa_id'];
                    $bod_ctc = $request['bodega'];
                    $form_ctc = $request['num_formula'];
                    $tipo_id = $request['tipo_id'];
                    $num_id = $request['num_id'];
                    $renombrar = 'CTC_' . $emp_ctc . $bod_ctc . "_" . $form_ctc . "_" . $random . "." . $dataimg[1];
                    break;

                case 5: //Facturas
                    $emp_fac = $request['empresa_arch'];
                    $bod_fac = $request['bodega_arch'];
                    $num_fac = $request['num_factura'];
                    $tipo_fac = $request['tipo_fac'];
                    $fecha_fac = $request['fecha_fac'];
                    $renombrar = 'FAC_' . $emp_fac . $bod_fac . "_" . $num_fac . "_" . $random . "." . $dataimg[1];
                    break;

                case 6: //Glosas
                    $emp_glo = $request['empresa_arch'];
                    $bod_glo = $request['bodega_arch'];
                    $num_glo = $request['num_glosa'];
                    $fac_glo = $request['num_fac_glo'];
                    $val_glo = $request['val_glosa'];
                    $renombrar = 'GL_' . $bod_glo . $num_glo . "-" . $fac_glo . "_" . $random . "." . $dataimg[1];
                    break;

                case 7: //Informes
                    $emp_inf = $request['empresa_id'];
                    $bod_inf = $request['bodega'];
                    $tipoInfo = $request['tipo_infor'];
                    $nomInfo = $request['nom_infor'];
					$selectivoEstado = $request['selectivoEstado'];
					$tipoProdocuto = $request['tipo_pro'];
					
					$tipoIdPaciente= $request['tipoIdPaciente'];
					$nro_idenficacion= $request['nro_idenficacion'];
					
					$nomQuienEntrega = $request['nomQuienEntrega'];
					$nomQuienRecibe  = $request['nomQuienRecibe'];
					$tipoSelectivo = $request['tipoSelectivo'];
					
                    $FechaInfo = explode("/", $request['fecha_infor']);
                    $fecInf = $FechaInfo[0] . "-" . $FechaInfo[1] . "-" . $FechaInfo[2];
                    $renombrar = 'INF_' . $emp_inf . $bod_inf . "_" . $tipoInfo . "_" . $fecInf . "_" . $random . "." . $dataimg[1];
                    break;

                case 8: //Pendientes dispensados
                    $emp_form = $request['empresa_arch'];
                    $bod_form = $request['bodega_arch'];
                    $num_form = $request['num_formula'];
                    $tipo_id = $request['tipo_id'];
                    $num_id = $request['num_id'];
                    $renombrar = 'PD_' . $emp_form . $bod_form . "_" . $num_form . "_" . $random . "." . $dataimg[1];
                    break;

                case 9: //Cortes
                    $emp_cort = $request['empresa_arch'];
                    $bod_cort = $request['bodega_arch'];
                    $num_cort = $request['num_corte'];
                    $cant_form = $request['cant_form'];
                    $val_corte = $request['val_corte'];
                    $fecIni = $request['fecha_ini_corte'];
                    $fecFin = $request['fecha_fin_corte'];
                    $entrega = $request['entrega'];
                    $audita = $request['audita'];
                    $renombrar = 'CR_' . $emp_cort . $bod_cort . "_" . $num_cort . "_" . $random . "." . $dataimg[1];
                    break;

                case 10: //Tutelas
                    $emp_tut = $request['empresa_arch'];
                    $cu_tut = $request['centro_utilidad_arch'];
                    $bod_tut = $request['bodega_arch'];
                    $radicado = $request['nradicado'];
                    $renombrar = 'TUT_' . $emp_tut . $bod_tut . "_" . $radicado . "_" . $random . "." . $dataimg['1'];
                    break;
                case 11: //alto costo
                    $emp_ctc = $request['empresa_id'];
                    $bod_ctc = $request['bodega'];
                    $form_ac = $request['num_formula'];
                    $tipo_id = $request['tipo_id'];
                    $num_id = $request['num_id'];
                    $renombrar = 'AC_' . $emp_ctc . $bod_ctc . "_" . $form_ac . "_" . $random . "." . $dataimg[1];
                    break;
                case 12: //CODIGO_2000
                    $emp_ctc = $request['empresa_id'];
                    $bod_ctc = $request['bodega'];
                    $form_ac = $request['num_formula'];
                    $tipo_id = $request['tipo_id'];
                    $num_id = $request['num_id'];
                    $renombrar = 'C2000_' . $emp_ctc . $bod_ctc . "_" . $form_ac . "_" . $random . "." . $dataimg[1];
                    break;
                case 13: //RECOBRO_MAGISTERIO
                    $emp_ctc = $request['empresa_id'];
                    $bod_ctc = $request['bodega'];
                    $form_ac = $request['num_formula'];
                    $tipo_id = $request['tipo_id'];
                    $num_id = $request['num_id'];
                    $renombrar = 'RM_' . $emp_ctc . $bod_ctc . "_" . $form_ac . "_" . $random . "." . $dataimg[1];
                    break;
                case 14: //RECOBRO_PASIVO
                    $emp_ctc = $request['empresa_id'];
                    $bod_ctc = $request['bodega'];
                    $form_ac = $request['num_formula'];
                    $tipo_id = $request['tipo_id'];
                    $num_id = $request['num_id'];
                    $renombrar = 'RP_' . $emp_ctc . $bod_ctc . "_" . $form_ac . "_" . $random . "." . $dataimg[1];
                    break;
            }
            /* ------------------------------------------------------------------------------------------ */

//ini_set('display_errors',1); error_reporting(E_ALL); 
            /*echo "<pre>";
            var_dump($request);
            echo "</pre>";
            exit();*/

            $ruta_archivo = $_SERVER['DOCUMENT_ROOT'] . "/APP/" . $path_app . "/repositorio/" . $renombrar;
            
            
            if(move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_archivo)) {
                $msg1 ="EL ARCHIVO FUE CARGADO CORRECTAMENTE <br><br>";
            } else {
                $msg1 ="ERROR AL CARGAR EL ARCHIVO <br>".$ruta_archivo."<br>";
            }
            
            
            $lines = file($ruta_archivo); //Devuelve el fichero a un array
            //registrar el cargue del documento en tabla esm_documentos_repositorio	
            $dat = $cls->InsertarTransRepositorio($renombrar, $size, $type, $user, $request);
		
            if (!$dat) {
                $msg1 .= "HA OCURRIDO UN ERROR MIENTRAS SE REALIZABA LA OPERACION<br>";
            } else {
                $msg1 .= "EL INGRESO SE HA REALIZADO SATISFACTORIAMENTE - ARCHIVO: " . $renombrar . "";
                $numero = 0;
				
                if($request['tipo_arch']=="5")
                    $numero = $dat['num_factura'];
                
                if($request['tipo_arch']=="10" )
                    $numero = $dat['radicado'];
               
		if($request['tipo_arch']=="4")
                    $numero = $dat['num_formula'];
		
                if($request['tipo_arch']=="11" ||$request['tipo_arch']=="12"||$request['tipo_arch']=="13"||$request['tipo_arch']=="14")
                    $numero = $dat['num_formula'];
					
                $listaTemp = $cls->GetlistaTmp($numero, $request['tipo_arch']);
                
              /*  echo "<pre>";                
                var_dump($request['tipo_arch']);
                var_dump($numero);
                var_dump($listaTemp);
				
                echo "</pre>";
                exit();*/

                $SaveProd = $cls->SaveProds($dat['documentos_repositorio_id'], $listaTemp, $request['tipo_arch'], $numero);
            }
        } else {
            $msg1 = "OCURRIO UN PROBLEMA CON LA CARGA DEL FICHERO";
        }

        // Para tener en cuenta
        // Cuando se usa SSL, Microsoft IIS violara el protocolo, cerrando la conexion sin mandar un indicador close_notify. 
        // PHP avisara de esto con este mensaje "SSL: Fatal Protocol Error", cuando llegue al final de los datos. 
        // Una solucion a este problema es bajar el nivel de aviso de errores del sistema para que no incluya advertencias. 
        // PHP 4.3.7 y versiones posteriores detectan servidores IIS con este problema cuando se hace streaming usando 
        // https:// y suprime la advertencia. Si se usa la funcion fsockopen() para crear un socket ssl://, 
        // tendra que suprimir la advertencia explicitamente.

        $this->salida = $vista->FormaMensajeModulo($action, $msg1);

        return true;
    }

    /*     * *************************************************************************
     * Funcion : Gestionar consultas datos del repositorio
     * 
     * ************************************************************************** */

    function DocsRepositorio() {
        $request = $_REQUEST;
		

        IncludeFileModulo("Remotos_repositorio", "RemoteXajax", "app", "RepositorioDocumentacion");
        $this->SetXajax(array("AllBodegas"), null, "ISO-8859-1");

        $path_app = basename(GetVarConfigaplication('DIR_SIIS')); //nombre aplicacion
        $ip = $_SERVER['SERVER_ADDR'];
        $ruta_archivo = "http://" . $ip . "/APP/" . $path_app . "/repositorio/";

        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $action['volver'] = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "main");
        $action['paginador'] = ModuloGetURL('app', 'RepositorioDocumentacion', 'controller', 'DocsRepositorio', array("buscador" => $request['buscador']));
        $action['buscador'] = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "DocsRepositorio");

        $vista = AutoCarga::factory("Repositorio_MenuHTML", "views", "app", "RepositorioDocumentacion");
        $sql = AutoCarga::factory("DMLs_repositorio", "classes", "app", "RepositorioDocumentacion");
        $perm = AutoCarga::factory("Permisos", "classes", "app", "RepositorioDocumentacion");


        $datosTipo = $sql->Listar_TipoArch(2);
        $Dwnld = $perm->DownloadRule();
        $todasEmpresas = $perm->AllEmpresas();
		
        $DatosRep = $sql->Listar_datosRep($request['buscador'], $request['offset']);
	
        $this->salida = $vista->Listado_DocsRep($action, $todasEmpresas, $Dwnld, $ruta_archivo, $datosTipo, $DatosRep, $sql->conteo, $sql->pagina);
        return true;
    }

    /*     * *************************************************************************
     * Funcion : Consultar medicamentos relacionados en documentos
     * especificos cargados en el repositorio (tutelas, ctc)
     * ************************************************************************** */

    function DocsRepositorioProd() {
        $request = $_REQUEST;
		

        IncludeFileModulo("Remotos_repositorio", "RemoteXajax", "app", "RepositorioDocumentacion");
        $this->SetXajax(array("AllBodegas"), null, "ISO-8859-1");

        $path_app = basename(GetVarConfigaplication('DIR_SIIS')); //nombre aplicacion
        $ip = $_SERVER['SERVER_ADDR'];
        $ruta_archivo = "http://" . $ip . "/APP/" . $path_app . "/repositorio/";
		
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");

        $action['volver'] = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "main");
        $action['paginador'] = ModuloGetURL('app', 'RepositorioDocumentacion', 'controller', 'DocsRepositorioProd', array("buscador" => $request['buscador']));
        $action['buscador'] = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "DocsRepositorioProd");
		
        $vista = AutoCarga::factory("Repositorio_MenuHTML", "views", "app", "RepositorioDocumentacion");
        $sql = AutoCarga::factory("DMLs_repositorio", "classes", "app", "RepositorioDocumentacion");
        $perm = AutoCarga::factory("Permisos", "classes", "app", "RepositorioDocumentacion");


        $datosTipo = $sql->Listar_TipoArchExc();
        $Dwnld = $perm->DownloadRule();
        $todasEmpresas = $perm->AllEmpresas();
		//print_r($request['buscador']);
        if ($request['buscador']) {
            $DatosRepProd = $sql->Listar_datosRepProd($request['buscador'], $request['offset']);
			 
        }

        $this->salida = $vista->Listado_DocsRepProd($action, $todasEmpresas, $Dwnld, $ruta_archivo, $datosTipo, $DatosRepProd, $sql->conteo, $sql->pagina);
        return true;
    }

}