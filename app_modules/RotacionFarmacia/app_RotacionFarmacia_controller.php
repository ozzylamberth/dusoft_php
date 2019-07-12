<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: app_RotacionFarmacia_controller.php,v 1.0
 * @copyright (C) 2010  IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torres 
 */

/**
 * Clase Control: RotacionFarmacia
 * Clase encargada del control de llamado de metodos en el modulo
 *
 * @package IPSOFT-SIIS
  / */
/* if (!IncludeClass('LogMenus')) {
  die(MsgOut("Error al incluir archivo", "LogMenus"));
  } */

class app_RotacionFarmacia_controller extends classModulo {

    /**
     * Constructor de la clase
     */
    function app_RotacionFarmacia_controller() {
        
    }

    /**
     *  Funcion principal del modulo
     *  @return boolean
     */
    function Main() {
        $request = $_REQUEST;
        /*         * *registro log x acceso en menus** */
        if ($request['module'] || $request['menuOp']) {
            $moduleLog = $request['module'];
            $menuLog = $request['menuOp'];
            $VarClass = new LogMenus();
            $LogApp = $VarClass->RegistrarAccesoAplicacion($moduleLog, $menuLog);
        }
        /*         * *************************************** */

        $action['rotacion_farmacias'] = ModuloGetURL("app", "RotacionFarmacia", "controller", "ConsultarFarmaciasSolicitudes");
        $action['rotacion_general_farmacias'] = ModuloGetURL("app", "RotacionFarmacia", "controller", "rotacion_general_farmacia");
        $action['volver'] = ModuloGetURL('system', 'Menu');
        $act = AutoCarga::factory("RotacionFarmaciaHTML", "views", "app", "RotacionFarmacia");
        $this->salida = $act->FormaMenu($action);
        return true;
    }

    /*     * Function Para Consultar Las Farmacias para realizar las solicitudes
     *  @return boolean
     */

    function ConsultarFarmaciasSolicitudes() {

        $request = $_REQUEST;
        $mdl = AutoCarga::factory("RotacionFarmaciaSQL", "classes", "app", "RotacionFarmacia");
        $Tipo = $mdl->ConsultarTipoId();
        $conteo = $pagina = 0;
        IncludeFileModulo("RotacionFarmacia", "RemoteXajax", "app", "RotacionFarmacia");
        $this->SetXajax(array("Datos_Farmacia"), "app_modules/RotacionFarmacia/RemoteXajax/RotacionFarmacia.php", "ISO-8859-1");


        if (!empty($request['buscador'])) {

            $datos = $mdl->FarmaciasRotacion($request['buscador'], $request['offset']);
            $action['buscador'] = ModuloGetURL('app', 'RotacionFarmacia', 'controller', 'ConsultarFarmaciasSolicitudes');
            $action['paginador'] = ModuloGetURL("app", "RotacionFarmacia", "controller", "ConsultarFarmaciasSolicitudes", array("buscador" => $request['buscador']));
            $conteo = $mdl->conteo;
            $pagina = $mdl->pagina;
        }
        $dat = $mdl->ConsultarEmpresas();
        $action['volver'] = ModuloGetURL("app", "RotacionFarmacia", "controller", "Main");
        $action['rotacionFarma'] = ModuloGetURL("app", "RotacionFarmacia", "controller", "Informacion_Farmacia");
        $act = AutoCarga::factory("RotacionFarmaciaHTML", "views", "app", "RotacionFarmacia");
        $this->salida = $act->FormaBuscarFarmaciasSolicitudes($action, $Tipo, $request['buscador'], $datos, $conteo, $pagina, $dat);
        return true;
    }

    /* Funcion para la informacion de la farmacia 
      @returb boolean */

    Function Informacion_Farmacia() {
        $request = $_REQUEST;
        //print_r($request);
        

        $Farmacia_id = $request['Farmacia_id'];
        $mdl = AutoCarga::factory("RotacionFarmaciaSQL", "classes", "app", "RotacionFarmacia");
        IncludeFileModulo("RotacionFarmacia", "RemoteXajax", "app", "RotacionFarmacia");
        $this->SetXajax(array("MostrarBodegas", "buscar_famacia"), "app_modules/RotacionFarmacia/RemoteXajax/RotacionFarmacia.php", "ISO-8859-1");

        $datos = $mdl->ConsultarBodegasFarmacia($Farmacia_id);
        $dat = $mdl->ConsultarEmpresas();
        $action['continuar'] = ModuloGetURL('app', 'RotacionFarmacia', 'controller', 'GenerarRotacionEmpresa', array("Farmacia_id" => $Farmacia_id));
        $action['volver'] = ModuloGetURL("app", "RotacionFarmacia", "controller", "ConsultarFarmaciasSolicitudes");
        $act = AutoCarga::factory("RotacionFarmaciaHTML", "views", "app", "RotacionFarmacia");
        $this->salida = $act->FormaInformacionFarmacia($action, $datos, $dat);
        return true;
    }

    /* Funcion para la generar los diferentes tipos de rotacion 
      @returb boolean */

    function GenerarRotacionEmpresa() {

        /* echo "<pre>";
          var_dump($_REQUEST);
          echo "</pre>";
          exit(); */

        function diferencia_meses_fecha($fecha_inicial, $fecha_final) {
            // separamos en partes las fechas
            $array_fecha_inicial = explode("-", $fecha_inicial);
            $array_fecha_final = explode("-", $fecha_final);

            $anos = $array_fecha_final[0] - $array_fecha_inicial[0]; // calculamos años
            $meses = $array_fecha_final[1] - $array_fecha_inicial[1]; // calculamos meses
            $dias = $array_fecha_final[2] - $array_fecha_inicial[2]; // calculamos días
            //ajuste de posible negativo en $días
            if ($dias < 0) {
                --$meses;

                //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual
                switch ($array_fecha_final[1]) {
                    case 1: $dias_mes_anterior = 31;
                        break;
                    case 2: $dias_mes_anterior = 31;
                        break;
                    case 3:
                        if (bisiesto($array_fecha_final[0])) {
                            $dias_mes_anterior = 29;
                            break;
                        } else {
                            $dias_mes_anterior = 28;
                            break;
                        }
                    case 4: $dias_mes_anterior = 31;
                        break;
                    case 5: $dias_mes_anterior = 30;
                        break;
                    case 6: $dias_mes_anterior = 31;
                        break;
                    case 7: $dias_mes_anterior = 30;
                        break;
                    case 8: $dias_mes_anterior = 31;
                        break;
                    case 9: $dias_mes_anterior = 31;
                        break;
                    case 10: $dias_mes_anterior = 30;
                        break;
                    case 11: $dias_mes_anterior = 31;
                        break;
                    case 12: $dias_mes_anterior = 30;
                        break;
                }

                $dias = $dias + $dias_mes_anterior;
            }

            //ajuste de posible negativo en $meses
            if ($meses < 0) {
                --$anos;
                $meses = $meses + 12;
            }

            //echo "<br>Tu edad es: $anos años con $meses meses y $dias días";
            if ($dias > 0) {
                $meses = $meses + 1;
            }

            return $meses;
        }

        function bisiesto($anio_actual) {
            $bisiesto = false;
            //probamos si el mes de febrero del año actual tiene 29 días
            if (checkdate(2, 29, $anio_actual)) {
                $bisiesto = true;
            }
            return $bisiesto;
        }

        $request = $_REQUEST;
        //print_r($request);
        $Farmacia_id = $request['Farmacia_id'];
        $completo = $request['bodega'];
        //echo "<br><br><br>".$completo;
        $infoB = explode("/", $completo);
        $bodega_id = $infoB[0];
        $centro = $infoB[1];

        $bodega_id = $_REQUEST['farmacia_id'];
        $centro = $_REQUEST['farmacia_id'];

        $Empresa_D = $request['destino'];
        $Bodega_D = $request['bodega_destino'];
        $fechai = $request['fecha_inicio'];
        $fechaf = $request['fecha_final'];
        $check = $request['check'];

        /*
          var_dump($Empresa_D);
          var_dump($Bodega_D);
          exit(); */

        $duni = explode("-", $fechai);
        $dati = $duni[2] . "-" . $duni[1] . "-" . $duni[0];
        $daf = explode("-", $fechaf);
        $datf = $daf[2] . "-" . $daf[1] . "-" . $daf[0];

        $diferencia_meses_fecha = diferencia_meses_fecha($dati, $datf);
        //echo $diferencia_meses_fecha." - - ";

        $variableDias = 30;
        $meses = $this->get_months($dati, $datf);
        $num = count($meses);

        $fechaabsolutaInicial = $fechai;
        $FechaInicialAb = explode("-", $fechaabsolutaInicial);
        $FormatoFechaI = $FechaInicialAb[2] . "-" . $FechaInicialAb[1] . "-" . $FechaInicialAb[0];

        $fechaabsolutaFinal = $fechaf;
        $FechaFinalAb = explode("-", $fechaabsolutaFinal);
        $FormatoFechaF = $FechaFinalAb[2] . "-" . $FechaFinalAb[1] . "-" . $FechaFinalAb[0];

        $FechaInicial_ = $fechai;
        $FeInicial_ = explode("-", $FechaInicial_);
        $FechaInicial_I = $FeInicial_[2] . "-" . $FeInicial_[1] . "-" . $FeInicial_[0];
        $FechaFinal = $fechaf;
        $FeFinal = explode("-", $FechaFinal);
        $FechaFinal_F = $FeFinal[2] . "-" . $FeFinal[1] . "-" . $FeFinal[0];

        $mdl = AutoCarga::factory("RotacionFarmaciaSQL", "classes", "app", "RotacionFarmacia");
        $act = AutoCarga::factory("RotacionFarmaciaHTML", "views", "app", "RotacionFarmacia");
        $action['volver'] = ModuloGetURL("app", "RotacionFarmacia", "controller", "Informacion_Farmacia", array("Farmacia_id" => $Farmacia_id, "bodega" => $completo, "destino" => $Empresa_D, "bodega_destino" => $Bodega_D));

        $medicamentos_d = array();

        for ($i = 0; $i < $diferencia_meses_fecha; $i++) {
            $fechaI = date("Y-m-d", strtotime($FormatoFechaI . " +" . $i . " month"));

            $j = $i + 1;
            $fechaF = date("Y-m-d", strtotime($FormatoFechaI . " +" . $j . " month"));
            if ($i < ($diferencia_meses_fecha - 1)) {
                $fechaF = strtotime('-1 day', strtotime($fechaF));
                $fechaF = date('Y-m-d', $fechaF);
            }

            $periodo = $i + 1;
            if ($periodo == $diferencia_meses_fecha) {
                $fechaF = $FormatoFechaF;
            }

            $medicamentos_d[] = $mdl->RotacionFinalProductos($fechaI, $fechaF, $bodega_id, $periodo, $Farmacia_id, $Empresa_D, $Bodega_D);
        }

        /* echo "<pre>";
          var_dump($medicamentos_d);
          echo "</pre>";
          exit(); */

        $matriz_final = array();
        foreach ($medicamentos_d as $key => $value) {
            foreach ($value as $medicamento => $detalle) {

                $matriz_final[$detalle['codigo_producto']]['codigo_producto'] = $detalle['codigo_producto'];
                $matriz_final[$detalle['codigo_producto']]['descripcion_producto'] = $detalle['descripcion_producto'];
                $matriz_final[$detalle['codigo_producto']]['molecula'] = $detalle['molecula'];
                $matriz_final[$detalle['codigo_producto']]['laboratorio'] = $detalle['laboratorio'];
                $matriz_final[$detalle['codigo_producto']]['tipo_producto'] = $detalle['tipo_producto'];
                $matriz_final[$detalle['codigo_producto']]['farmacia'] = $detalle['farmacia'];
                $matriz_final[$detalle['codigo_producto']]['stock_bodega'] = $detalle['stock_bodega'];
                $matriz_final[$detalle['codigo_producto']]['stock_farmacia'] = $detalle['stock_farmacia'];
                $matriz_final[$detalle['codigo_producto']]['nivel'] = $detalle['nivel'];

                for ($i = 1; $i <= $diferencia_meses_fecha; $i++) {
                    if ((!array_key_exists($i, $matriz_final[$detalle['codigo_producto']]['cantidad_total_despachada'])) || ($matriz_final[$detalle['codigo_producto']]['cantidad_total_despachada'][$i] == " - ")) {
                        if ($i == $detalle['periodo']) {
                            $matriz_final[$detalle['codigo_producto']]['cantidad_total_despachada'][$i] = $detalle['cantidad_total_despachada'];
                        } else {
                            $matriz_final[$detalle['codigo_producto']]['cantidad_total_despachada'][$i] = " - ";
                        }
                    }
                }
            }
        }
        $medicamentos_d = $matriz_final;

        $action['guardar'] = ModuloGetURL('app', 'RotacionFarmacia', 'controller', 'GenerarSolicitudes', array("ingreso" => $valor, "Farmacia_id" => $Farmacia_id, "bodega" => $bodega_id, "centro" => $centro, "destino" => $Empresa_D, "bodega_destino" => $Bodega_D));
        $this->salida = $act->FormaGenerarRotacionProducto($action, $medicamentos_d, $diferencia_meses_fecha);

        return true;
    }

    function GenerarSolicitudes() {

        $request = $_REQUEST;
        //print_r($request);echo "<br><br><br>";

        $Farmacia_id = $request['Farmacia_id'];
        $Empresa_D = $request['destino'];
        $bodega_id = $request['bodega'];
        $centro = $request['centro'];
        $mdl = AutoCarga::factory("RotacionFarmaciaSQL", "classes", "app", "RotacionFarmacia");
        $act = AutoCarga::factory("RotacionFarmaciaHTML", "views", "app", "RotacionFarmacia");
        /* $valor=$request['ingreso'];
          $valor2=$request['valor2'];
          $valor3=$request['valor3']; */
        $Bodega_D = $request['bodega_destino'];

        $cantidad_registros = $request['cantidad_registros'];
        /* $cantidad_registrosP=$request['cantidad_registrosP'];
          $cantidad_registrosde=$request['cantidad_registrosde']; */

        for ($i = 0; $i < $cantidad_registros; $i++) {
            if (!empty($request[$i])) {
                //$cantidad=$request['txtcantidad'.$cont];
                $cantidad = $request['txtpedido' . $i];
                $producto = $request[$i];
                if ($cantidad > 0) {
                    $mdl->solcitud_Gerencia_($request, $producto, $cantidad);

                    $cantidad = $request['txtpedido' . $i];
                    $producto = $request[$i];
                    $tipo_prod = $request['tipo_producto' . $i];
                    $observa = $request['observa' . $i];

                    $mdl->Ingresar_DatosSolicitudTMP($request, $producto, $cantidad, $tipo_prod, $observa);
                }
            }
        }



        $datos = $mdl->Solicitudes_Generadas_x_Rotacion($request);
        $datos_s = $mdl->Solicitud_Temporal_Pedidos($request);
        $datos_d = $mdl->ConsultarInformacion_Devolucion($request);

        $action['guardar'] = ModuloGetURL('app', 'RotacionFarmacia', 'controller', 'GenerarSolicitudes', array("Farmacia_id" => $Farmacia_id, "bodega" => $bodega_id, "centro" => $centro, "destino" => $Empresa_D, "bodega_destino" => $Bodega_D));
        $action['Crear_s'] = ModuloGetURL('app', 'RotacionFarmacia', 'controller', 'GenerarSolicitudBodega_pp', array("Farmacia_id" => $Farmacia_id, "bodega" => $bodega_id, "centro" => $centro, "destino" => $Empresa_D, "valor" => 1, "bodega_destino" => $Bodega_D));
        $action['guardar_D'] = ModuloGetURL('app', 'RotacionFarmacia', 'controller', 'GenerarSolicitudes', array("Farmacia_id" => $Farmacia_id, "bodega" => $bodega_id, "centro" => $centro, "destino" => $Empresa_D, "bodega_destino" => $Bodega_D));

        $action['volver'] = ModuloGetURL("app", "RotacionFarmacia", "controller", "ConsultarFarmaciasSolicitudes");
        $this->salida = $act->FormaMensajeSolcitud_($action, /* $datos_empresa, */ $datos, $datos_s, $Empresa_D, $datos_d, $Bodega_D);
        return true;
    }

    /* Funcion que permite generar los pedidos de la farmacia a bodega principal
      @returb boolean */

    function GenerarSolicitudBodega_pp() {
        $request = $_REQUEST;
        //print_r($request);

        $Farmacia_id = $request['Farmacia_id'];
        $Empresa_D = $request['destino'];
        $bodega_id = $request['bodega'];
        $centro = $request['centro'];
        $valor = $request['valor'];
        $Bodega_D = $request['bodega_destino'];
        $cantidad_registro = $request['cantidad_registrosP'];
        $observacion = $request['observar'];

        $mdl = AutoCarga::factory("RotacionFarmaciaSQL", "classes", "app", "RotacionFarmacia");
        $act = AutoCarga::factory("RotacionFarmaciaHTML", "views", "app", "RotacionFarmacia");

        $solicitud = $mdl->IngresoSolicitudBP($request, $observacion, $Empresa_D);
        $solicitud_id = $solicitud['solictud_id'];

        for ($k = 0; $k < $cantidad_registro; $k++) {

            if (!empty($request[$k])) {
                $cantidad = $request['cantidad' . $k];
                $disponible = $request['txtdisponi' . $k];
                $observa = $request['observacionp' . $k];
                $tipo_producto = $request['tipo_p' . $k];

                /* if($cantidad < $disponible)
                  { */
                $producto = $request[$k];
                $mdl->IngresoDetallSolicitud($solicitud_id, $request, $producto, $cantidad, $observa, $tipo_producto);
                //  }
            }
        }


        $action['volver'] = ModuloGetURL("app", "RotacionFarmacia", "controller", "GenerarSolicitudes", array("Farmacia_id" => $Farmacia_id, "bodega" => $bodega_id, "centro" => $centro, "destino" => $Empresa_D, "bodega_destino" => $Bodega_D));
        $this->salida = $act->FormaMensajeSolcitud_P($action, /* $datos_empresa,$datos,$datos_s,$Empresa_D, */ $solicitud_id);
        return true;
    }

    /* Funcion para obtener los meses que hay entre una fecha Inicial y Una Fecha Final
      return cantidad de meses
     */

    function get_months($date1, $date2) {
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        $my = date('mY', $time2);
        $months = array(date('F', $time1));

        $FeInicial = explode("-", $date1);
        $FechaInicialIn = $FeInicial[0] . "-" . $FeInicial[1];
        $FeFinalL = explode("-", $date2);
        $FechaFinalF = $FeFinalL[0] . "-" . $FeFinalL[1];


        if ($FechaInicialIn != $FechaFinalF) {
            while ($time1 < $time2) {
                $time1 = strtotime(date('Y-m-d', $time1) . ' +1 month');
                if (date('mY', $time1) != $my && ($time1 < $time2))
                    $months[] = date('F', $time1);
            }
            $months[] = date('F', $time2);
        }
        return $months;
    }

    function rotacion_producto() {

        $request = $_REQUEST;

        $sql = AutoCarga::factory("RotacionFarmaciaSQL", "classes", "app", "RotacionFarmacia");
        $view = AutoCarga::factory("RotacionFarmaciaHTML", "views", "app", "RotacionFarmacia");
        $this->SetXajax(array("rotacion_producto"), "app_modules/RotacionFarmacia/RemoteXajax/RotacionFarmacia.php", "ISO-8859-1");

        $lista_empresas = $sql->obtener_listas_empresas();
        $lista_zonas = $sql->obtener_zonas();

        //var_dump($lista_empresas);
        //var_dump($lista_zonas);

        $this->salida = $view->rotacion_producto($lista_empresas, $lista_zonas);
        return true;
    }
    
    function rotacion_general_farmacia() {

        $request = $_REQUEST;

        $sql = AutoCarga::factory("RotacionFarmaciaSQL", "classes", "app", "RotacionFarmacia");
        $view = AutoCarga::factory("RotacionFarmaciaHTML", "views", "app", "RotacionFarmacia");
        $this->SetXajax(array("rotacion_producto", "rotacion_general_farmacias"), "app_modules/RotacionFarmacia/RemoteXajax/RotacionFarmacia.php", "ISO-8859-1");

        $lista_empresas = $sql->obtener_listas_empresas();
        $lista_zonas = $sql->obtener_zonas();

        //var_dump($lista_empresas);
        //var_dump($lista_zonas);

        $this->salida = $view->rotacion_general_farmacias($lista_empresas, $lista_zonas);
        return true;
    }

}

?>