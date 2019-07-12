<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: app_RotacionGerencia_controller.php,v 1.0
	* @copyright (C) 2010  IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	/**
	* Clase Control: RotacionGerencia
	* Clase encargada del control de llamado de metodos en el modulo
	*
	* @package IPSOFT-SIIS
	/*/
	class app_RotacionGerencia_controller  extends classModulo
	{
	/**
		* Constructor de la clase
	*/
	function app_RotacionGerencia_controller()
	{}
	/**
        *  Funcion principal del modulo
        *  @return boolean
    */
		function Main()
		{
			$request = $_REQUEST;
			$rotacion= AutoCarga::factory('RotacionGerenciaSQL', '', 'app', 'RotacionGerencia');
			$permisos = $rotacion->ObtenerPermisos();
      
			$ttl_gral = "ROTACION";
			$mtz[0] = 'EMPRESA';
			$mtz[1] = 'CENTRO DE UTILIDAD';
			$mtz[2] = 'DEPARTAMENTO';
			$mtz[3] = 'BODEGA';
			$url[0] = 'app';
			$url[1] = 'RotacionGerencia'; 
			$url[2] = 'controller';
			$url[3] = 'Menu'; 
			$url[4] = 'RotacionGerencia'; 
			$action['volver'] = ModuloGetURL('system', 'Menu');
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
			return true;
		}    
/*
	   * Funcion que permite Seleccionar Una Opcion del Menu 
	   *  @return boolean
	   */
		
		function Menu()
		{
            $request = $_REQUEST;
            if($request['RotacionGerencia']) SessionSetVar("DatosEmpresaAF",$request['RotacionGerencia']);
            $datos_empresa = SessionGetVar("DatosEmpresaAF");

            $obj = AutoCarga::factory('RotacionGerenciaSQL', '', 'app', 'RotacionGerencia');

            $action['rotacion'] = ModuloGetURL("app", "RotacionGerencia", "controller", "PeridoDeTiempoRotacionFechas");
            $action['volver'] = ModuloGetURL("app", "RotacionGerencia", "controller", "Main");
            $act = AutoCarga::factory("RotacionGerenciaHTML", "views", "app", "RotacionGerencia");
            $this->salida = $act->FormaMenu($action);
			return true;
		}
    /*
	  * Funcion Fechas de Rotacion
	  *  @return boolean
    */
		function PeridoDeTiempoRotacionFechas()
		{
      $request = $_REQUEST;
      IncludeFileModulo("Rotacion","RemoteXajax","app","RotacionGerencia");
      $this->SetXajax(array("ListaFarmacias"),"app_modules/RotacionGerencia/RemoteXajax/Rotacion.php","ISO-8859-1");
    
      $datos_empresa = SessionGetVar("DatosEmpresaAF");
      $action['generar']=ModuloGetURL('app','RotacionGerencia','controller','GenerarRotacionEmpresa');
      $action['generar_general']=ModuloGetURL('app','RotacionGerencia','controller','GenerarRotacionGeneralEmpresa');
      $action['volver'] = ModuloGetURL("app", "RotacionGerencia", "controller", "Main");
      $act = AutoCarga::factory("RotacionGerenciaHTML", "views", "app", "RotacionGerencia");
      $fnc = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");
      
      $farmacias = $fnc->Consulta_Farmacias();
      
      $this->salida = $act->FormaGenerarRotacion($action,$empresa,$farmacias);
      return true;
		}
    /*
	  * Funcion Rotacion segun El tipo Laboratorio,Molecula,Medicamentos,general
	  *  @return boolean
    */
		function GenerarRotacionEmpresa()
		{
            $request = $_REQUEST;
			
            $datos_empresa = SessionGetVar("DatosEmpresaAF");

            $empresa_id=$datos_empresa['empresa_id'];
            $bodega_id =$datos_empresa['bodega'];
            $centro_utilidad=$datos_empresa['centro_utilidad'];

            $fechai=$request['fecha_inicio'];
            $fechaf=$request['fecha_final'];
            $check=$request['check'];

            $duni = explode("-", $fechai);
            $dati= $duni[2]."-".$duni[1]."-".$duni[0];
            $daf=explode("-", $fechaf);
            $datf= $daf[2]."-".$daf[1]."-".$daf[0];

            $variableDias=30;
            $meses=$this->get_months($dati,$datf);
            $num=count($meses);

            $fechaabsolutaInicial=$fechai;
            $FechaInicialAb = explode("-", $fechaabsolutaInicial);
            $FormatoFechaI= $FechaInicialAb[2]."-".$FechaInicialAb[1]."-".$FechaInicialAb[0];

            $fechaabsolutaFinal=$fechaf;
            $FechaFinalAb = explode("-", $fechaabsolutaFinal);
            $FormatoFechaF= $FechaFinalAb[2]."-".$FechaFinalAb[1]."-".$FechaFinalAb[0];

            $FechaInicial_=$fechai;
            $FeInicial_ = explode("-", $FechaInicial_);
            $FechaInicial_I= $FeInicial_[2]."-".$FeInicial_[1]."-".$FeInicial_[0];


            $FechaFinal=$fechaf;
            $FeFinal= explode("-", $FechaFinal);
            $FechaFinal_F= $FeFinal[2]."-".$FeFinal[1]."-".$FeFinal[0];

            $mdl = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");
            $act = AutoCarga::factory("RotacionGerenciaHTML", "views", "app", "RotacionGerencia");
            $action['volver'] = ModuloGetURL("app", "RotacionGerencia", "controller", "PeridoDeTiempoRotacionFechas");
           if($check==1)
           {
              $valor=1;
            //	$Productos=$mdl->Productos_Con_Mto($FormatoFechaI,$FormatoFechaF);
            //	$Farmacias=$mdl->Consulta_Farmacias();
             //  $Productos=$mdl->Productos_por_empresa_conMTO($empresa_id);
                $action['guardar']=ModuloGetURL('app','RotacionGerencia','controller','GenerarSolicitudLaborario',array("ingreso"=>$valor));
              $this->salida = $act->FormaGenerarRotacionGeneral($action,$empresa_id,$num,$fechai,$fechaf,$Productos,$FechaInicial_,$FechaFinal,$Farmacias,$FormatoFechaI,$FormatoFechaF,$clase_id,$bodega_id,$request);
            }
			if($check==2)
			{
				$valor=1;
				$Laboratorio_f=$mdl->Consultar_Laboratorios($datos_empresa['sw_tipo_empresa']);
				if(!empty($request['clase_id']))
				{
				  $clase_id=$request['clase_id'];
          //$medicamentos_d=$mdl->RotacionFinal($fechai,$fechaf,$empresa_id,$clase_id,$bodega_id,$centro_utilidad);
          $medicamentos_d=$mdl->ObtenerRotacionXLaboratorio($clase_id,$centro_utilidad,$bodega_id,$empresa_id,$fechai,$fechaf);
				}
				
				$action['consulta']=ModuloGetURL('app','RotacionGerencia','controller','GenerarRotacionEmpresa',array("fecha_inicio"=>$fechai,"fecha_final"=>$fechaf,"check"=>$check));
				$action['guardar']=ModuloGetURL('app','RotacionGerencia','controller','GenerarSolicitudLaborario',array("ingreso"=>$valor));
				$this->salida = $act->FormaGenerarRotacionLaboratorio($action,$empresa_id,$num,$fechai,$fechaf,$FechaInicial_,$FechaFinal,$medicamentos_d,$Laboratorio_f,$clase_id);
			}
			if($check==3)
			{
          $valor=1;
		  $subclase_id = $request['cod_principio_activo'];
          $medicamentos_d=$mdl->RotacionMoleculas($fechai,$fechaf,$empresa_id,$bodega_id,$centro_utilidad,$subclase_id);
          $moleculas=$mdl->Consultar_Moleculas();
		  $action['consulta']=ModuloGetURL('app','RotacionGerencia','controller','GenerarRotacionEmpresa',array("fecha_inicio"=>$fechai,"fecha_final"=>$fechaf,"check"=>$check));
		  $action['guardar']=ModuloGetURL('app','RotacionGerencia','controller','RealizarSolicitud_Moleculas',array("ingreso"=>$valor));
          $this->salida = $act->FormaGenerarRotacionMolecula($action,$empresa_id,$num,$fechai,$fechaf,$FechaInicial_,$FechaFinal,$medicamentos_d,$FormatoFechaI,$FormatoFechaF,$bodega_id,$moleculas);
			}
			if($check==4)
			{
				$valor=1;
				$medicamentos_d=$mdl->RotacionFinalProducto($fechai,$fechaf,$empresa_id,$bodega_id,$centro_utilidad);
			    $action['guardar']=ModuloGetURL('app','RotacionGerencia','controller','GenerarSolicitudLaborario',array("ingreso"=>$valor));
				$this->salida = $act->FormaGenerarRotacionProducto($action,$empresa_id,$num,$fechai,$fechaf,$FechaInicial_,$FechaFinal,$medicamentos_d);
			}
			if($check==5)
			{
			  	$valor=1;
				if(!empty($request['grupo_id']) && ($request['grupo_id']!='-1'))
				{
				     $grupo_id=$request['grupo_id'];
				}
				$t_insumos=$mdl->RotacionInsumos_x_Tipo_();
				$insumos=$mdl->RotacionInsumos($fechai,$fechaf,$empresa_id,$bodega_id,$centro_utilidad,$grupo_id);
		    	$action['consulta']=ModuloGetURL('app','RotacionGerencia','controller','GenerarRotacionEmpresa',array("fecha_inicio"=>$fechai,"fecha_final"=>$fechaf,"check"=>$check));
				$action['guardar']=ModuloGetURL('app','RotacionGerencia','controller','GenerarSolicitudLaborario',array("ingreso"=>$valor));
				$this->salida = $act->FormaGenerarRotacionInsumos($action,$empresa_id,$num,$fechai,$fechaf,$FechaInicial_,$FechaFinal,$insumos,$t_insumos);
			}
			return true;
		}
	  /*
    * Funcion Rotacion segun El tipo Laboratorio,Molecula,Medicamentos,general
    *  @return boolean
    */
		function GenerarRotacionGeneralEmpresa()
		{
      $request = $_REQUEST;
      
			$datos_empresa = SessionGetVar("DatosEmpresaAF");
      $mdl = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");
      $act = AutoCarga::factory("RotacionGerenciaHTML", "views", "app", "RotacionGerencia");
      
      $datosR = array("fecha_inicio"=>$request['fecha_inicio'],
                      "fecha_final"=>$request['fecha_final'],
                      "empresa_id"=>$request['empresa_id'],
                      "centros"=>$request['centros'],
                      "descripcion"=>$request['descripcion']);
                      
      $action['generar_xls']=ModuloGetURL('app','RotacionGerencia','controller','GenerarRotacionGeneralEmpresaXLS',$datosR);

      $productos = $mdl->ObtenerProductosExistencias($datos_empresa,$request['fecha_inicio'],$request['fecha_final'],$request['empresa_id'],$request['centros']);
      foreach($request['centros'] as $key => $dtl)
      {
        $aux = $mdl->ObtenerRotacionXBodega($key,$request['empresa_id'],$request['fecha_inicio'],$request['fecha_final']);
        if(!empty($aux))
          $rotaciones[$key] = $aux;
      }
			
      $empresa_id=$datos_empresa['empresa_id'];
      $bodega_id =$datos_empresa['bodega'];
      $centro_utilidad=$datos_empresa['centro_utilidad'];

      $fechai=$request['fecha_inicio'];
      $fechaf=$request['fecha_final'];
      $check=$request['check'];

      $duni = explode("-", $fechai);
      $dati= $duni[2]."-".$duni[1]."-".$duni[0];
      $daf=explode("-", $fechaf);
      $datf= $daf[2]."-".$daf[1]."-".$daf[0];

      $variableDias=30;
      $meses = $this->get_monthsII($dati,$datf);
      $num=count($meses);

      $fechaabsolutaInicial=$fechai;
      $FechaInicialAb = explode("-", $fechaabsolutaInicial);
      $FormatoFechaI= $FechaInicialAb[2]."-".$FechaInicialAb[1]."-".$FechaInicialAb[0];

      $fechaabsolutaFinal=$fechaf;
      $FechaFinalAb = explode("-", $fechaabsolutaFinal);
      $FormatoFechaF= $FechaFinalAb[2]."-".$FechaFinalAb[1]."-".$FechaFinalAb[0];

      $FechaInicial_=$fechai;
      $FeInicial_ = explode("-", $FechaInicial_);
      $FechaInicial_I= $FeInicial_[2]."-".$FeInicial_[1]."-".$FeInicial_[0];


      $FechaFinal=$fechaf;
      $FeFinal= explode("-", $FechaFinal);
      $FechaFinal_F= $FeFinal[2]."-".$FeFinal[1]."-".$FeFinal[0];

      $action['volver'] = ModuloGetURL("app", "RotacionGerencia", "controller", "PeridoDeTiempoRotacionFechas");

      $valor=1;
      $action['guardar']=ModuloGetURL('app','RotacionGerencia','controller','GenerarPedido');
      $this->salida = $act->FormaMostrarRotacion($action,$productos,$empresa_id,$meses,$fechai,$fechaf,$rotaciones,$FechaInicial_,$FechaFinal,$Farmacias,$FormatoFechaI,$FormatoFechaF,$clase_id,$bodega_id,$request);

			return true;
		}
  /*
	        * Funcion Generar Rotacion segun las Fechas
	        *  @return boolean
	 */
	 
	function GenerarSolicitudLaborario()
    {

		$request = $_REQUEST;
		
		$datos_empresa = SessionGetVar("DatosEmpresaAF");
		$mdl = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");
        $valor=$request['ingreso'];
		$valor2=$request['valor2'];
		$cantidad_registro=$request['cantidad_registros'];
	
		if($valor==1)
		{
			for($cont=0;$cont<$cantidad_registro;$cont++)
			{
		
					if(!empty($request[$cont]))
					{
						$cantidad=$request['txtcantidad'.$cont];
						$producto=$request[$cont];
						if($cantidad >0)
					    {
							$mdl->solcitud_Gerencia_($datos_empresa,$producto,$cantidad);
						}
				    }
				
			}
		}
        if($valor2==1)
		{
			for($cont=0;$cont<$cantidad_registro;$cont++)
			{

				if(!empty($request[$cont]))
				{
					$cantidad=$request['txtcantidad'.$cont];
					$producto=$request[$cont];
					$mdl->Eliminar_Cantidad_Solicitudes($datos_empresa,$producto);
				}
	
			}
			
			for($cont=0;$cont<$cantidad_registro;$cont++)
			{			
					if(!empty($request[$cont]))
					{
						$cantidad=$request['txtcantidad'.$cont];
						$producto=$request[$cont];
						if($cantidad >0)
					    {
							$mdl->solcitud_Gerencia_($datos_empresa,$producto,$cantidad);
						}
						
			       }
				
			}
		}
	
		$datos=$mdl->Solicitudes_Generadas_x_Rotacion($datos_empresa);
		$act = AutoCarga::factory("RotacionGerenciaHTML", "views", "app", "RotacionGerencia");
		$action['guardar']=ModuloGetURL('app','RotacionGerencia','controller','GenerarSolicitudLaborario');
		$action['volver'] = "javascript:history.go(-1);";
		$this->salida = $act->FormaMensajeSolcitud_($action,$datos_empresa,$datos);
        return true;
    }
	
	
    /*Funcion para obtener los meses que hay entre una fecha Inicial y Una Fecha Final
		return cantidad de meses 
	*/
		function get_months($date1, $date2) 
		{
			$time1  = strtotime($date1);
			$time2  = strtotime($date2);
			$my     = date('mY', $time2);
			$months = array(date('F', $time1));
			
			$FeInicial = explode("-", $date1);
			$FechaInicialIn= $FeInicial[0]."-".$FeInicial[1];
			$FeFinalL = explode("-", $date2);
			$FechaFinalF= $FeFinalL[0]."-".$FeFinalL[1];
			
		
			if($FechaInicialIn!=$FechaFinalF)
			{			
			while($time1 < $time2)
		    	{
				$time1 = strtotime(date('Y-m-d', $time1).' +1 month');
				if(date('mY', $time1) != $my && ($time1 < $time2))
				$months[] = date('F', $time1);
			  }
			  $months[] = date('F', $time2);
			 } 
			  return $months;
		}

		function get_monthsII($date1, $date2) 
		{
			$time1  = strtotime($date1);
			$time2  = strtotime($date2);
			$my     = date('mY', $time2);
			$months = array(date('Y-m', $time1));
			
			$FeInicial = explode("-", $date1);
			$FechaInicialIn= $FeInicial[0]."-".$FeInicial[1];
			$FeFinalL = explode("-", $date2);
			$FechaFinalF= $FeFinalL[0]."-".$FeFinalL[1];
			
			if($FechaInicialIn!=$FechaFinalF)
      {			
        while($time1 < $time2)
		    {
          $time1 = strtotime(date('Y-m-d', $time1).' +1 month');
          if(date('mY', $time1) != $my && ($time1 < $time2))
            $months[] = date('Y-m', $time1);
			  }
			  $months[] = date('Y-m', $time2);
			} 
			  return $months;
		}     
		
		/* Funcion que permite realizar  la distribucion por producto que contienen  la molecula */
		
		function RealizarSolicitud_Moleculas()
		{
	
			$request = $_REQUEST;
			$datos_empresa = SessionGetVar("DatosEmpresaAF");
			$sql = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");
			
			$valor=1;
			/*print_r($_REQUEST);
			print_r("<br>................<br>");*/
			
			for($i=0;$i<$request['cantidad_registros'];$i++)
			{
			if($request[$i]!="")
				{
				$sql->Insertar_SolicitudGerencia($datos_empresa,$request['molecula'.$i],$request['descripcion'.$i],$request['unidad_id'.$i],$request['sw_generico'.$i],$request['txtcantidad'.$i]);
				$query_mod .= $sql->Modificar_SolicitudGerencia($datos_empresa,$request['molecula'.$i],$request['descripcion'.$i],$request['unidad_id'.$i],$request['sw_generico'.$i],$request['txtcantidad'.$i]);
				}
			}
			/*$sql->Ejecutar_Consultas($query);*/
			$sql->Ejecutar_Consultas($query_mod);
			/*print_r($query);*/
			$datos = $sql->Solicitud_GerenciaMoleculas($datos_empresa);
			$act = AutoCarga::factory("RotacionGerenciaHTML", "views", "app", "RotacionGerencia");
			$action['guardar']=ModuloGetURL('app','RotacionGerencia','controller','RealizarSolicitud_Moleculas');
			
			$action['volver'] = "javascript:history.go(-1);";
			$this->salida = $act->FormaRealizarSolicitud_Moleculas($action,$request,$datos_empresa,$datos);
			return true;
		
		} 
    /*
    * Funcion Rotacion segun El tipo Laboratorio,Molecula,Medicamentos,general
    *  @return boolean
    */
		function GenerarRotacionGeneralEmpresaXLS()
		{
      $request = $_REQUEST;
      
			$datos_empresa = SessionGetVar("DatosEmpresaAF");
      $mdl = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");
      $act = AutoCarga::factory("RotacionGerenciaHTML", "views", "app", "RotacionGerencia");
      
      $productos = $mdl->ObtenerProductosExistencias($datos_empresa,$request['fecha_inicio'],$request['fecha_final'],$request['empresa_id'],$request['centros']);
      foreach($request['centros'] as $key => $dtl)
      {
        $aux = $mdl->ObtenerRotacionXBodega($key,$request['empresa_id'],$request['fecha_inicio'],$request['fecha_final']);
        if(!empty($aux))
          $rotaciones[$key] = $aux;
      }
			
      $empresa_id=$datos_empresa['empresa_id'];
      $bodega_id =$datos_empresa['bodega'];
      $centro_utilidad=$datos_empresa['centro_utilidad'];

      $fechai=$request['fecha_inicio'];
      $fechaf=$request['fecha_final'];

      $duni = explode("-", $fechai);
      $dati= $duni[2]."-".$duni[1]."-".$duni[0];
      $daf=explode("-", $fechaf);
      $datf= $daf[2]."-".$daf[1]."-".$daf[0];

      $variableDias=30;
      $meses = $this->get_monthsII($dati,$datf);
      $num=count($meses);

      $fechaabsolutaInicial=$fechai;
      $FechaInicialAb = explode("-", $fechaabsolutaInicial);
      $FormatoFechaI= $FechaInicialAb[2]."-".$FechaInicialAb[1]."-".$FechaInicialAb[0];

      $fechaabsolutaFinal=$fechaf;
      $FechaFinalAb = explode("-", $fechaabsolutaFinal);
      $FormatoFechaF= $FechaFinalAb[2]."-".$FechaFinalAb[1]."-".$FechaFinalAb[0];

      $FechaInicial_=$fechai;
      $FeInicial_ = explode("-", $FechaInicial_);
      $FechaInicial_I= $FeInicial_[2]."-".$FeInicial_[1]."-".$FeInicial_[0];

      $FechaFinal=$fechaf;
      $FeFinal= explode("-", $FechaFinal);
      $FechaFinal_F= $FeFinal[2]."-".$FeFinal[1]."-".$FeFinal[0];

      $html = $act->FormaMostrarRotacionXLS($action,$productos,$empresa_id,$meses,$fechai,$fechaf,$rotaciones,$FechaInicial_,$FechaFinal,$Farmacias,$FormatoFechaI,$FormatoFechaF,$clase_id,$bodega_id,$request);
      
      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=DetalleCuenta_".$request['cuenta'].".xls");
      header("Pragma: no-cache");
      header("Expires: 0");
      echo $html;
      exit;
      
			return true;
		}
  }
?>