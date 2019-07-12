<?php
	/**
  * @package DUANA & CIA LTDA.
  * @version $Id: app_Inv_Listas_Precios_controller.php,v 1.0 2012/07/25 roma $
  * @copyright (C) 2012 Duana & Cia Ltda
  * @author Ronald Marin - ROMA
  */
  /**
  * Clase Control: Inv_Listas_Precios
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package DUANA & CIA. LTDA.
  * @version $Revision: 1.0 $
  * @copyright (C) 2012 Duana & Cia Ltda
  * @author ROMA
  */

    class app_Inv_Listas_Precios_controller extends classModulo
    {      
        /**
        * @var array $action  Vector donde se almacenan los links de la aplicacion
        */
        var $action = array();
        /**
        * @var array $request Vector donde se almacenan los datos pasados por request
        */
        var $request = array();


		
		
        /************************************************
        * Constructor de la clase
        *************************************************/
        function app_Inv_Listas_Precios_controller(){}
		
        /*************************************************
        * Funcion principal del modulo
        *
        * @return boolean
        **************************************************/
        
		function main()
		{				
			$request = $_REQUEST;

      if(!isset($html)){ $html = ''; }
						
			$url[0]='app';                                //Tipo de Modulo
			$url[1]='Inv_Listas_Precios';          //Nombre del Modulo
			$url[2]='controller';                  	  //Si es User,controller...
			$url[3]='MenuEmpresas';              //Metodo.
			$url[4]='datos';						      //vector de $_request.
			$arreglo[0]='EMPRESA';			  //Sub Titulo de la Tabla
						
			$obj_perm=AutoCarga::factory("Permisos", "", "app","Inv_Listas_Precios");
			$datos=$obj_perm->BuscarPermisos();
			//echo "<pre>Session: ";
			//print_r($_SESSION);
            //echo "<pre>Get: ";
            //print_r($_GET);
            //echo '<pre>datos:';
            //print_r($arreglo);
            for($i = 0; $i < count($datos); $i++){
                //echo '<pre>datos:';
                //print_r($datos[$i]);
            }
		    //Generar el pantallazo inicial sobre las empresas. gui_theme_menu_acceso retorna codigo html.
			// Titulo de la Tabla, Subtitulo de la Tabla(campos),destino,Boton Volver
			$forma = gui_theme_menu_acceso("PARAMETRIZACION LISTAS PRECIOS",$arreglo,$datos,$url,ModuloGetURL('system','Menu'));
            //return $clase->Listado_Precios($EmpresaId,$CentroUtilidad,$Bodega);
            $countForeach = 0;

            foreach ($datos as $valor) {
                $countForeach++;
                $empresa_id = $valor['empresa_id'];
                $empresa_nombre = $valor['empresa'];


                $cantidadBodegasyCentros = $obj_perm->verificarCantidadBodegasyCentros($empresa_id);
                if($cantidadBodegasyCentros && isset($cantidadBodegasyCentros['cantidad_centros']) && isset($cantidadBodegasyCentros['cantidad_bodegas'])){
                    $cantidadBodegas = $cantidadBodegasyCentros['cantidad_bodegas'];
                    $cantidadCentros = $cantidadBodegasyCentros['cantidad_centros'];
                    //$forma .= "<script>console.log('bodegas: ".$cantidadBodegas." y Centros: ".$cantidadCentros."');</script>";
                    if($cantidadBodegas === 1 && $cantidadCentros === 1){
                        //Listado_Precios('".$empresa_id."','ZS','ZS');
                        $forma .= "<script>";
                        $forma .= "   var contenedor_empresas = document.getElementsByClassName('Contenido')[0].children[1].children[0].children[0].children[0];\n";
                        $forma .= "   var cantidad_empresas = (contenedor_empresas.children.length)-1;\n";
                        $forma .= "   for(var i = 1; i <= cantidad_empresas; i++){\n";
                        $forma .= "      var contenedor_empresa = contenedor_empresas.children[i].children[0].children[0].children[0].children[0].children[0].children[0].children[1].children[0];\n";
                        $forma .= "      var empresa_nombre = contenedor_empresa.innerHTML;\n";
                        $forma .= "      if(empresa_nombre === '".$empresa_nombre."'){\n";
                        //$forma .= "         contenedor_empresa.href = '#';\n";
                        //$forma .= "         contenedor_empresa.setAttribute(\"onclick\", \"xajax_Listado_Precios('".$empresa_id."','ZS','ZS')\");";

                        //$forma .= "         var url = '/APP/PRUEBAS_LINA_OSPINA/app_modules/Inv_Listas_Precios/RemoteXajax/RemotosDefinirCostosDeVentaProductos.php';\n";
                        //$forma .= "         var xhttp = '';\n";
                        //$forma .= "         if (window.XMLHttpRequest) {\n";
                        //$forma .= "             xhttp = new XMLHttpRequest();\n";
                        //$forma .= "         } else {\n";
                        //$forma .= "             xhttp = new ActiveXObject(\"Microsoft.XMLHTTP\");\n";
                        //$forma .= "         }\n";

                        //$forma .= "         xhttp.onreadystatechange = function() {\n";
                        //$forma .= "            if (this.readyState == 4 && this.status == 200) {\n";
                        //$forma .= "                 console.log('->'+this.responseText);";
                        //$forma .= "            }\n";
                        //$forma .= "         };\n";
                        //$forma .= "         xhttp.open('POST', url, true);\n";
                        //$forma .= "         xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');\n";
                        //$forma .= "         xhttp.send('accion=Listado_Precios&empresa=".$empresa_id."');\n";

                        //$forma .= "             ";
                        //$forma .= "             xajax_Listado_Precios('".$empresa_id."','ZS','ZS');";
                        $forma .= "      }\n";
                        //$forma .= "      console.log(empresa_nombre);\n";
                        $forma .= "   }\n";
                        $forma .= "   ";
                        $forma .= "</script>\n";
                    }
                }
            }
            $this->salida = $forma;
			return true;
		}
		
      /***************************************
      * FUNCION DE MENU PRINCIPAL
      ***************************************/
		function MenuEmpresas()
		{
  		 /*Crear el Men� de Opciones*/
         $request = $_REQUEST;
         //print_r($request);
  		 $Obj_Menu=AutoCarga::factory("Listas_Precios_MenuHTML", "views", "app","Inv_Listas_Precios");
  		 
  		 //Volver a Empresas.
  		 $action['volver'] = ModuloGetURL("app","Inv_Listas_Precios","controller","main");
  		 //$_SESSION['datos']['empresa_id']=$_REQUEST['datos']['empresa_id'];
         if($request['datos']['empresa_id'])
            SessionSetVar("empresa_id",$request['datos']['empresa_id']);      
         
  		 $this->salida=$Obj_Menu->Menu($action);
  		 
  		 return true;
		}


      /***************************************************
      * Funcion asignacion productos listas precios
      ***************************************************/		
        function DefinirCostosDeVentaProductos()
		{
          
         $request = $_REQUEST;
		 IncludeFileModulo("RemotosDefinirCostosDeVentaProductos","RemoteXajax","app","Inv_Listas_Precios");
		 $this->SetXajax(array("CentrosUtilidad","Bodegas","Esconder",
                          "Listado_Precios","RegistrarListaPrecios","CatalogoProductosFarmacia","SeleccionDeProductos","BuscarProductos",
                          "RegistrarItemListaPrecios","ListadoItemsListaPrecios","BuscarProductosListaPrecios","EliminarItemListaPrecios",
                          "Productos_CreadosBuscados","EmpresasT_2","Buscador","AsignarCostosXProducto","FormaDinamica2",
                          "GuardarDaticos2"));
           
         $this->IncludeJS("CrossBrowser");
		 $this->IncludeJS("CrossBrowserEvent");
		 $this->IncludeJS("CrossBrowserDrag");	
          
	      /*
	      * Para Manejo de Tabs
	      */
         $this->IncludeJS("TabPaneLayout");
		 $this->IncludeJS("TabPaneApi");
         $this->IncludeJS("TabPane");
         
         $Obj_Form=AutoCarga::factory("DefinirCostosDeVentaProductos_HTML", "views", "app","Inv_Listas_Precios");
		 $action['volver'] = ModuloGetURL("app","Inv_Listas_Precios","controller","MenuEmpresas");

     if(!isset($html)){ $html = ''; }
		 
		 $Html_Form=$Obj_Form->main($action,$request,$html);
		 
		 $this->salida = $Html_Form;
          
          return true;
		}		
		
		
  }
?>