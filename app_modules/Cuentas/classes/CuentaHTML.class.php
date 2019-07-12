<?php
  /******************************************************************************
  * $Id: CuentaHTML.class.php,v 1.10 2011/06/24 16:36:53 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.10 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class CuentaHTML
	{
		function CuentaHTML(){} 
		/**********************************************************************************
		*@acess public
		***********************************************************************************/
		function ComponentePrincipal($datos,$cuenta,$action,$caja,&$obj)
		{
			$obj->IncludeJS("CrossBrowserEvent"); 
			$obj->IncludeJS("CrossBrowserDrag");
			$obj->IncludeJS("CrossBrowser");
			IncludeLib("funciones_admision");

			//Opciones Facturar cuenta hospitalaria
			IncludeClass('app_Cuentas_user','','app','Cuentas');
			$fact = new app_Cuentas_user();
			//FIN Opciones Facturar cuenta hospitalaria

			//ADICIONAR CARGOS PENDIENTES POR CARGAR
			IncludeClass('CargosPendientesPorCargarHTML','','app','Cuentas');
			$CargosPendientes = new CargosPendientesPorCargarHTML();
			//FIN ADICIONAR CARGOS POR CARGAR

			IncludeClass('CuentaDetalleHTM','','app','Cuentas');
			//ADICIONAR CARGOS
			IncludeClass('AgregarCargosHTML','','app','Cuentas');
			$AgregarCargos = new AgregarCargosHTML();
			//FIN ADICIONAR CARGOS

			//ADICIONAR PROCEDIMIENTO QX
			//IncludeClass('AgregarCargosQXHTML','','app','Cuentas');
			//$QX = new AgregarCargosQXHTML();
			//FIN PROCEDIMIENTO QX

			//ADICIONAR IyM
			IncludeClass('BuscarCargoIYMHTML','','app','Cuentas');
			$Buscar = new BuscarCargoIYMHTML();
			//FIN ADICIONAR IyM

			//INFORMACION (Imprimir factura, hoja cargos)
			IncludeClass('InformacionCuentasHTML','','app','Cuentas');
			$info = new InformacionCuentasHTML();
			//FIN INFORMACION (Imprimir factura, hoja cargos)

			//OPCIONES (cambio reponsable, activar , inactivar)
			IncludeClass('OpcionesCuentasHTML','','app','Cuentas');
			$opciones = new OpcionesCuentasHTML();
			//FIN OPCIONES (cambio reponsable, activar , inactivar)
      
      //OPCIONES (cambio reponsable, activar , inactivar)
			IncludeClass('ImprimirHTML','','app','Cuentas');
			$imprimir_html = new ImprimirHTML();
			//FIN OPCIONES (cambio reponsable, activar , inactivar)

		//Opciones Facturar cuenta hospitalaria
			IncludeClass('Cuenta','','app','Cuentas');
			$classcuenta = new Cuenta();
			if(!SessionisSetVar("DatosEmpresaId") AND !SessionisSetVar("DatosCentroUtilidadId"))
			{
				$dat = $classcuenta->ObtenerDatosCuenta($cuenta[numerodecuenta]);
				$fact->SetDatosEmpresa($dat[empresa_id],$dat[centro_utilidad]);
			}
			//FIN Opciones Facturar cuenta hospitalaria
			
			$cntd = new CuentaDetalleHTM();
			IncludeClass('DetalleCtaHTML','','app','Cuentas');  
      $dcta = new DetalleCtaHTML();
      $html1 = $cntd->InformacionTotales($cuenta,$action);
			$html .= $html1;
			//SET DE VALORES
			$Cuenta = $cuenta[numerodecuenta];
			$Nombres = $cuenta[nombre];
			$Apellidos = '';
			$TipoId = $cuenta[tipo_id_paciente];
			$PacienteId = $cuenta[paciente_id];
			//$Nivel = $cuenta[];
			$Ingreso = $cuenta[ingreso];
			$Fecha = $cuenta[fecha];
			//$EmpresaId = $caja[empresa_id];
			$EmpresaId = SessionGetVar("DatosEmpresaId");
			//$CU = $caja[centro_utilidad] ;
			$CU = SessionGetVar("DatosCentroUtilidadId");
			//$Departamento = $_REQUEST['Departamento'];
			$PlanId = $cuenta[plan_id];
			//FIN SET DE VALORES
			
			//CUANDO LLEGAN VALORES DESDE LISTADO DE PECIENTES CON SALIDA
			if(empty($EmpresaId) AND SessionisSetVar("EmpresaIdListaPacientesConSalida"))
			{$EmpresaId = SessionGetVar("EmpresaIdListaPacientesConSalida");}
			if(empty($CU) AND SessionisSetVar("Cuenta_centro_utilidad"))
			{$CU = SessionGetVar("Cuenta_centro_utilidad");}
			//FIN CUANDO LLEGAN VALORES DESDE LISTADO DE PECIENTES CON SALIDA
			$html = "";
			$html .= "<br>\n";
			$html .= "<table width=\"100%\" align=\"center\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\">\n";
			$html .= "			<div class=\"tab-pane\" id=\"cuenta\" >\n";
			$html .= "				<script>	tabPane = new WebFXTabPane( document.getElementById( \"cuenta\" ), false); </script>\n";
			$html .= "				<div class=\"tab-page\" id=\"detalle\">\n";
			$html .= "					<h2 class=\"tab\">DETALLE</h2>\n";
			$html .= "					<script>	tabPane.addTabPage( document.getElementById(\"detalle\")); </script>\n";
			$html .= "					<div id=\"error\" ></div>\n";
			//$html .= $cntd->InformacionTotales($cuenta,$action);
			$html .= $html1;
			$html .= "					<br>\n";
			
			if($caja['prefijo_fac_credito'] && $caja['prefijo_fac_contado'] && $cuenta['sw_ambulatorio'] == '1')
			{
				$html .= "					<form name=\"factura\" action=\"".$action['facturar']."\" method=\"post\">\n";
				$html .= "						<input type=\"submit\" class=\"input-submit\" name=\"factura\" value=\"Facturar\">\n";
				$html .= "					</form>\n";
			}

			//CARGOS PENDIENTES POR CARGAR
			$BuscarPendientesCargar=BuscarPendientesCargar($Ingreso);
			if(!empty($BuscarPendientesCargar))
			{
				$PendientesCargar=PendientesCargar($Ingreso);
				$html .=$CargosPendientes->FormaPendientesCargar($PendientesCargar,$PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Cama,$Fecha,$Ingreso);
			}
			//FIN CARGOS PENDIENTES POR CARGAR

			//HABITACIONES
			unset($_SESSION['CUENTAS']['CAMA']['LIQ']);
			if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php"))
			{
							die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
			}
			
			$liqHab = new LiquidacionHabitaciones;
			$hab = $liqHab->LiquidarCargosInternacion($Cuenta,false);
			if(is_array($hab))
			{
							$_SESSION['CUENTAS']['CAMA']['LIQ']=$hab;
							IncludeClass('HabitacionesHTML','','app','Cuentas');  
							$habitaciones = new HabitacionesHTML();
							$obj->SetJavaScripts('DetalleCamas');
							$html .= $habitaciones->FormaHabitaciones($hab,$PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Cama,$Fecha,$Ingreso);
			}
			elseif(empty($hab))
			{       //ocurrio un error hay q mostrarlo
							$html .= "<p align=\"center\" class=\"label_error\">".$liqHab->Err()."<BR>".$liqHab->ErrMsg()."</p>";
			}
			//FIN HABITACIONES

      $html .= $dcta->CrearFormaDetalleCta($cuenta[numerodecuenta],$cuenta[cerrada],$cuenta['tipo_id_paciente'],$cuenta['paciente_id'],$cuenta['rango'],$cuenta['plan_id'],$cuenta['fecha'],$cuenta['ingreso'],$action['ModificarCargo'],$action['EliminarCargo'],$action['DevolverIYM'],$modificacionCargos=1,$EmpresaId,$CU);      
      $html .= "        </div>\n";
			if($cuenta[estado] <> '0')//LAS FACTURADAS NO MUESTRAN ADICIONAR CARGOS
			{
			$html .= "				<div class=\"tab-page\" id=\"add_cargos\">\n";
			$html .= "					<h2 class=\"tab\">ADICIONAR CARGOS</h2>\n";
			$html .= "					<script>	tabPane.addTabPage( document.getElementById(\"add_cargos\")); </script>\n";
			//ADICIONAR CARGOS
			$html .= "					<div id=\"error\" ></div>\n";
			$accion = ModuloGetURL('app','Cuentas','user','FormaMostrarCuenta',array("Cuenta"=>$cuenta[numerodecuenta]));
			SessionSetvar('AccionVolverCargos',$accion);
			SessionSetVar('EmpresaId',$EmpresaId);
			SessionSetVar('CentroUtilidad',$CU);
			$html .= $AgregarCargos->FormaAgregarCargos($EmpresaId,$CU,$PlanId,$Cuenta,'',$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha);
			$html .= "					<br>\n";
			//FIN ADICIONAR CARGOS

			$html .= "				</div>\n";
			}
//ADICIONAR QX
/*			$html .= "				<div class=\"tab-page\" id=\"qx\">\n";
			$html .= "					<h2 class=\"tab\">PROCEDIMIENTOS QX</h2>\n";
			$html .= "					<script>	tabPane.addTabPage( document.getElementById(\"qx\")); </script>\n";
			$html .= "					<div id=\"error\" ></div>\n";
			$html .= $html1;
			$UsuarioId = UserGetUID();
			$html .= $QX->FormaAgregarCargosQX($EmpresaId,$CU,$PlanId,$Cuenta,$mensaje,$TipoId,$PacienteId,$Nombres,$Nivel,$Ingreso,$Fecha);
			$html .= "					<br>\n";
			$html .= "				</div>\n";
*/
//FIN ADICIONAR QX

			if($cuenta[estado] <> '0')//LAS FACTURADAS NO MUESTRAN INSUMOS Y MEDICAMENTOS
			{
			$html .= "				<div class=\"tab-page\" id=\"insumos\">\n";
			$html .= "					<h2 class=\"tab\">ADICIONAR IMD</h2>\n";
			$html .= "					<script>	tabPane.addTabPage( document.getElementById(\"insumos\")); </script>\n";

			//ADICIONAR INSUMOS Y MEDICAMENTOS
			$html .= "					<div id=\"error\" ></div>\n";
			$html .= $html1;
			$UsuarioId = UserGetUID();
			//action volver
			$accion = ModuloGetURL('app','Cuentas','user','FormaMostrarCuenta',array("Cuenta"=>$cuenta[numerodecuenta]));
			SessionSetVar('AccionVolverCargosIYM',$accion);

			$html .= $Buscar->FormaBusquedaCargoIyM($EmpresaId,$CU,$UsuarioId,$Cuenta,$PlanId,$Ingreso,$TipoId,$PacienteId,$Nombres,$Apellidos);
			$html .= "					<br>\n";
			$html .= "				</div>\n";
			//FIN ADICIONAR INSUMOS Y MEDICAMENTOS
			}
			
			//REPORTES - Informacion CUENTAS
			$html .= "				<div class=\"tab-page\" id=\"info\">\n";
			$html .= "					<h2 class=\"tab\">INFORMACIÓN</h2>\n";
			$html .= "					<script>	tabPane.addTabPage( document.getElementById(\"info\")); </script>\n";
			$html .= $html1;
			$html .= "			<br>\n";
			$html .= $info->FormaInformacionCuentas($EmpresaId,$cuenta[ingreso],$cuenta[numerodecuenta],$cuenta[plan_id],&$obj,$cuenta[sw_tipo_plan]);
			$html .= "				</div>\n";
			//$html .= "				</div>\n";
			//FIN REPORTES - Informacion CUENTAS

 			if($datos['sw_cuentas'])
			{
			 SessionSetVar("sw_cuentas",$datos['sw_cuentas']);
			}
			elseif(SessionGetVar("sw_cuentas"))
			{
			 $datos['sw_cuentas'] = SessionGetVar("sw_cuentas");
			}
			
			if($cuenta['sw_ambulatorio'] == '0' || $datos['sw_cuentas'])
			{
				$html .= "				<div class=\"tab-page\" id=\"facturar\">\n";
				$html .= "					<h2 class=\"tab\">FACTURAR</h2>\n";
				$html .= "					<script>	tabPane.addTabPage( document.getElementById(\"facturar\")); </script>\n";
				$html .= "					<div id=\"error\" ></div>\n";
				$html .= $html1;
				$rutaVolver = ModuloGetURL('app','Cuentas','user','FormaMostrarCuenta',array("Cuenta"=>$cuenta[numerodecuenta]));
				$botones = $fact->ReturnModuloExterno('app','Facturar','user');
				
				//$pto_facturacion = ;Se maneja por docuemnto_id
        IncludeClass('Cuenta','','app','Cuentas');
        $cnt = new Cuenta();
          
        $SolicitudesNoAutorizadas = 0;
        $OrdenesSinResultado = 0;
        
        $SolicitudesNoAutorizadas = $cnt->ContarSolicitudesNoAutorizadasPaciente($cuenta[ingreso]);
        
        if($SolicitudesNoAutorizadas==0)
          $OrdenesSinResultado = $cnt->ContarOrdenesSinResultadoPaciente($cuenta[ingreso]);
        
				$pto_facturacion = SessionGetVar("Punto_facturacion_id");

        $html .= "<table width=\"50%\" align=\"center\">\n";
        
        if($SolicitudesNoAutorizadas==0 && $OrdenesSinResultado==0)
        {
          $html .= "		<tr align=\"center\">\n";
          $html .= $botones->FormaMostrarBotonesFacturar($Cuenta,$pto_facturacion);
          $html .= "		</tr>\n";
        }
        else
        {
          $accionT=ModuloGetURL('app','Cuentas','user','LlamaEstablecerOrdenNoEjecutada',array('tipo_id_paciente'=>$TipoId,'paciente_id'=>$PacienteId,'nombrespac'=>$Nombres,'apellidospac'=>$Apellidos,'cuenta'=>$cuenta[numerodecuenta],'ingreso'=>$cuenta[ingreso]));
        	$html .= "  <tr align=\"center\">\n";
          $html .= "    <td class=\"label_mark\"><label class=\"label_error\" >EL PACIENTE TIENE &Oacute;RDENES DE SERVICIO DE SIN AUTORIZAR O SIN RESULTADO.</label></td>";
          $html .= "  </tr>\n";
        	$html .= "  <tr align=\"center\">\n";
          $html .= "    <td class=\"label_mark\"><a href=\"$accionT\"><img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\" width=\"20\" height=\"20\">&nbsp;VER</a></td>";
          $html .= "  </tr>\n";
        }
        
        $html .= "</table>\n";
				$botones->SetActionVolver($rutaVolver);
				$html .= "						<br>\n";
				$html .= "				</div>\n";
			}
			
			if($cuenta[estado] <> '0')//LAS FACTURADAS NO MUESTRAN REPORTES - Informacion CUENTAS
			{
			$html .= "				<div class=\"tab-page\" id=\"opciones\">\n";
			$html .= "					<h2 class=\"tab\">OPCIONES</h2>\n";
			$html .= "					<script>	tabPane.addTabPage( document.getElementById(\"opciones\")); </script>\n";
			$html .= $html1;
			SessionSetVar('AccionVolverCargosIYM',$accion);
			$html .= $opciones->FormaOpcionesCuentas($cuenta[plan_id],$cuenta[numerodecuenta],$cuenta[ingreso],$cuenta[estado],$cuenta[tipo_id_paciente],$cuenta[paciente_id]);
			$html .= "				</div>\n";
			}
      $html .= "				<div class=\"tab-page\" id=\"impresion\">\n";
			$html .= "					<h2 class=\"tab\">IMPRESION</h2>\n";
			$html .= "					<script>	tabPane.addTabPage( document.getElementById(\"impresion\")); </script>\n";
     
			$html .= $html1;
			SessionSetVar('AccionVolverCargosIYM',$accion);
			$html .= $imprimir_html->FormaImprimir($cuenta[plan_id],$cuenta[numerodecuenta],$cuenta[ingreso],$cuenta[estado],$cuenta[tipo_id_paciente],$cuenta[paciente_id]);
			$html .= "				</div>\n";
			$html .= "			</div>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			return $html;
		}
    
   /*   Función que muestra las órdenes de servicio solicitadas al paciente que se encuentran sin autorizar, como también aquellas que no tienen resultado.
    *   return $html
    */
    function FormaEstablecerOrdenNoEjecutada($request, $mensaje)
    {
      IncludeClass('Cuenta','','app','Cuentas');
      $cnt = new Cuenta();
      
      $SolicitudesNoAutorizadas = $cnt->BuscarSolicitudesNoAutorizadasPaciente($request['ingreso']);
      $OrdenesSinResultado = $cnt->BuscarOrdenesSinResultadoPaciente($request['ingreso']);
      $motivosNoEjecutar = $cnt->BuscarMotivosNoEjecutar();
     
      $html = ThemeAbrirTabla("&Oacute;RDENES DE SERVICIO SIN AUTORIZAR Y SIN RESULTADOS DEL PACIENTE.");
      
      $html .= "<script>\n";
      
      $html .= "  function SeleccionarOrdenesSinAutorizar(x)\n";
      $html .= "  { \n";
      $html .="     cantChecks = document.getElementById('cantidadOrdenesSinAutorizar').value * 1 \n"; 
      $html .= "    if(x==true)\n";
      $html .= "    { \n";
      $html .= "      for(i=0; i<cantChecks; i++)\n";
      $html .= "      { \n";
      $html .="         document.getElementById('ordenSinAutorizar_'+i).checked = true; \n"; 
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "    else \n";
      $html .= "    { \n";
      $html .= "      for(i=0; i<cantChecks; i++)\n";
      $html .= "      { \n";
      $html .="         document.getElementById('ordenSinAutorizar_'+i).checked = false; \n"; 
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "  }\n";        
      
      $html .= "  function SeleccionarOrdenesSinResultado(x)\n";
      $html .= "  { \n";
      $html .="     cantChecks = document.getElementById('cantidadOrdenesSinResultados').value * 1 \n"; 
      $html .= "    if(x==true)\n";
      $html .= "    { \n";
      $html .= "      for(i=0; i<cantChecks; i++)\n";
      $html .= "      { \n";
      $html .="         document.getElementById('ordenSinResultado_'+i).checked = true; \n"; 
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "    else \n";
      $html .= "    { \n";
      $html .= "      for(i=0; i<cantChecks; i++)\n";
      $html .= "      { \n";
      $html .="         document.getElementById('ordenSinResultado_'+i).checked = false; \n"; 
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "  }\n";   
      
      $html .= "  function validarDatosFormulario()\n";
      $html .= "  { \n";
      $html .="     cantOrdenesSinAutorizar = document.getElementById('cantidadOrdenesSinAutorizar').value * 1; \n"; 
      $html .="     cantOrdenesSinResultado = document.getElementById('cantidadOrdenesSinResultados').value * 1; \n";
      $html .="     observacion = document.getElementById('observacion').value; \n"; 
      $html .="     if(observacion=='') \n"; 
      $html .= "    { \n";
      $html .="       document.getElementById('mensaje_error').style.display ='block'; \n";
      $html .="       return false; \n";
      $html .= "    } \n";
      $html .= "    else \n";
      $html .= "    { \n";
      $html .="       document.getElementById('mensaje_error').style.display ='none'; \n";
      $html .= "    } \n";
      $html .= "    for(i=0; i<cantOrdenesSinAutorizar; i++)\n";
      $html .= "    { \n";
      $html .="       orden = document.getElementById('ordenSinAutorizar_'+i).checked; \n"; 
      $html .="       if(orden == true) \n"; 
      $html .= "      {\n";
      $html .="         estadoSelectSolicitud = document.getElementById('motivo_solicitudes_no_autorizadas_'+i).value * 1 \n";
      $html .="         if(estadoSelectSolicitud == -1) \n";       
      $html .= "        {\n"; 
      $html .="           document.getElementById('mensaje_error_solicitudes').style.display ='block'; \n"; 
      $html .="           return false; \n";
      $html .= "        }\n"; 
      $html .= "      }\n"; 
      $html .= "    }\n"; 
      $html .="     document.getElementById('mensaje_error_solicitudes').style.display ='none'; \n"; 
      $html .= "    for(i=0; i<cantOrdenesSinResultado; i++)\n";
      $html .= "    { \n";
      $html .="       orden = document.getElementById('ordenSinResultado_'+i).checked; \n"; 
      $html .="       if(orden == true) \n"; 
      $html .= "      {\n";
      $html .="         estadoSelectOrden = document.getElementById('motivo_ordenes_sin_resultado_'+i).value * 1 \n";
      $html .="         if(estadoSelectOrden == -1) \n";       
      $html .= "        {\n"; 
      $html .="           document.getElementById('mensaje_error_ordenes').style.display ='block'; \n"; 
      $html .="           return false; \n";
      $html .= "        }\n"; 
      $html .= "      }\n"; 
      $html .= "    }\n"; 
      $html .="     document.getElementById('mensaje_error_ordenes').style.display ='none'; \n"; 
      $html .="     document.forms['forma_solicitudes_ordenes_no_ejecutadas'].submit(); \n"; 
      $html .= "  } \n";
      
      $html .= "</script>\n";
      
			$html .= "<table width=\"75%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
			$html .= "  <tr>";
			$html .= "    <td class=\"modulo_table_list_title\" align=\"left\" colspan=\"8\">DATOS PACIENTE </td>";
			$html .= "  </tr>";
      $html .= "  <tr>";
      $html .= "    <td class=\"modulo_table_list_title\" width=\"10%\" align=\"left\">IDENTIFICACI&Oacute;N:</td><td width=\"13%\" class=\"modulo_list_claro\">".$request['tipo_id_paciente']." ".$request['paciente_id']."</td>";
			$html .= "    <td class=\"modulo_table_list_title\" width=\"10%\" align=\"left\">PACIENTE:</td><td width=\"21%\" class=\"modulo_list_claro\">".$request['nombrespac']." ".$request['apellidospac']."</td>";
      $html .= "    <td class=\"modulo_table_list_title\" width=\"10%\" align=\"left\">INGRESO:</td><td width=\"13%\" class=\"modulo_list_claro\">".$request['ingreso']."</td>";
			$html .= "    <td class=\"modulo_table_list_title\" width=\"10%\" align=\"left\">CUENTA:</td><td width=\"13%\" class=\"modulo_list_claro\">".$request['cuenta']."</td>";
      $html .= "  </tr>";
			$html .= "</table>";
      $html .= "  <br>\n";
      
      if(!empty($mensaje))
      {
        $html .= "<div id=\"mensaje_error_ordenes\" align=\"center\" style=\"display:block;padding:4px\">\n";   
        $html .= "  <label class=\"label_error\">SU SOLICITUD FUE REALIZADA SATISFACTORIAMENTE.</label>\n";
        $html .= "</div>\n";      
        $html .= "<br>\n";      
      }
      
      $accion1 = ModuloGetURL('app','Cuentas','user','LlamaInsertarSolicitudesOrdenesNoEjecutadas',array('Cuenta'=>$request['cuenta']));
      
      $html .= "	<form name=\"forma_solicitudes_ordenes_no_ejecutadas\" id=\"forma_solicitudes_ordenes_no_ejecutadas\" action=\"$accion1\" method=\"post\">";    

      $html .= "             <input type=\"hidden\" name=\"ingreso\" value=\"".$request['ingreso']."\">\n";    
      $html .= "             <input type=\"hidden\" name=\"cuenta\" value=\"".$request['cuenta']."\">\n";    
      $html .= "             <input type=\"hidden\" name=\"nombrespac\" value=\"".$request['nombrespac']."\">\n";    
      $html .= "             <input type=\"hidden\" name=\"apellidospac\" value=\"".$request['apellidospac']."\">\n";    
      $html .= "             <input type=\"hidden\" name=\"tipo_id_paciente\" value=\"".$request['tipo_id_paciente']."\">\n";    
      $html .= "             <input type=\"hidden\" name=\"paciente_id\" value=\"".$request['paciente_id']."\">\n";    
      
      if(sizeof($SolicitudesNoAutorizadas) > 0)
      {
        $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";     
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td align=\"center\" colspan=\"8\">&Oacute;RDENES DE SERVICIO DE SIN AUTORIZAR</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td align=\"center\" width=\"13%\">FECHA</td>\n";
        $html .= "      <td align=\"center\" width=\"13%\">SOLICITUD</td>\n";
        $html .= "      <td align=\"center\" width=\"8%\">CARGO</td>\n";
        $html .= "      <td align=\"center\" width=\"32%\">DESCRIPCI&Oacute;N</td>\n";
        $html .= "      <td align=\"center\" width=\"8%\">CANTIDAD</td>\n";
        $html .= "      <td align=\"center\" width=\"13%\">TIPO</td>\n";
        $html .= "      <td align=\"center\" width=\"10%\">MOTIVO CAMBIO DE ESTADO</td>\n";
        $html .= "      <td align=\"center\" width=\"3%\"><input type=\"checkbox\" id=\"todosOrdenesSinAutorizar\" name=\"todosOrdenesSinAutorizar\" value=\"1\" onclick=\"SeleccionarOrdenesSinAutorizar(this.checked);\"></td>\n";
        $html .= "    </tr>\n";
        for($i=0; $i<sizeof($SolicitudesNoAutorizadas); $i++)
        {
          ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
          $html .= "    <tr class=\"".$estilo."\">\n";
          $html .= "      <td align=\"left\">".substr($SolicitudesNoAutorizadas[$i]['fecha_solicitud'], 0, 19)."</td>\n";
          $html .= "      <td align=\"left\">".$SolicitudesNoAutorizadas[$i]['hc_os_solicitud_id']."</td>\n";
          $html .= "      <td align=\"left\">".$SolicitudesNoAutorizadas[$i]['cargo']."</td>\n";
          $html .= "      <td align=\"left\">".$SolicitudesNoAutorizadas[$i]['descripcion_cargo_cups']."</td>\n";
          $html .= "      <td align=\"left\">".$SolicitudesNoAutorizadas[$i]['cantidad']."</td>\n";
          $html .= "      <td align=\"left\">".$SolicitudesNoAutorizadas[$i]['descripcion_tipo_solicitud']."</td>\n";
          
          $html .= "      <td align=\"left\">\n";
          $html .= "        <select name=\"motivo_solicitudes_no_autorizadas_".$i."\" id=\"motivo_solicitudes_no_autorizadas_".$i."\"  class='select'>\n";
          $html .= "          <option value=\"-1\" >--SELECCIONE--</option>\n";
          foreach($motivosNoEjecutar as $k => $vlr)
          {
            $html .= "          <option value=\"".$vlr['motivos_cambios_estado_solicitudes_ordenes_no_ejecutadas_id']."\" >".$vlr['descripcion']."</option>\n";
          } 
          $html .= "        </select>\n";
          $html .= "      </td>\n";
          
          $html .= "      <td align=\"center\"><input type=\"checkbox\" id=\"ordenSinAutorizar_".$i."\" name=\"ordenSinAutorizar_".$i."\" value=\"".$SolicitudesNoAutorizadas[$i]['hc_os_solicitud_id']."\"></td>\n";
          $html .= "    </tr>\n";                
        }
        $html .= "</table>\n";
            
        if(!empty($OrdenesSinResultado))
          $html .= "<br>";
      }
      
      if(sizeof($SolicitudesNoAutorizadas) > 0)
      {
        $html .= "<input type=\"hidden\" id=\"cantidadOrdenesSinAutorizar\" name=\"cantidadOrdenesSinAutorizar\" value=\"".$i."\">\n";     
      }
      else
      {
        $html .= "<input type=\"hidden\" id=\"cantidadOrdenesSinAutorizar\" name=\"cantidadOrdenesSinAutorizar\" value=\"0\">\n";  
      }
      
      if(sizeof($OrdenesSinResultado) > 0)
      {
        $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"80%\">\n";     
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td align=\"center\" colspan=\"8\">&Oacute;RDENES DE SERVICIO DE SIN RESULTADO</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td align=\"center\" width=\"13%\">FECHA</td>\n";
        $html .= "      <td align=\"center\" width=\"13%\">No. ORDEN</td>\n";
        $html .= "      <td align=\"center\" width=\"8%\">CARGO</td>\n";
        $html .= "      <td align=\"center\" width=\"32%\">DESCRIPCI&Oacute;N</td>\n";
        $html .= "      <td align=\"center\" width=\"8%\">CANTIDAD</td>\n";
        $html .= "      <td align=\"center\" width=\"13%\">TIPO</td>\n";
        $html .= "      <td align=\"center\" width=\"10%\">MOTIVO CAMBIO DE ESTADO</td>\n";
        $html .= "      <td align=\"center\" width=\"3%\"><input type=\"checkbox\" id=\"todosOrdenesSinResultado\" name=\"todosOrdenesSinResultado\" value=\"1\" onclick=\"SeleccionarOrdenesSinResultado(this.checked);\"></td>\n";
        $html .= "    </tr>\n";
        for($i=0; $i<sizeof($OrdenesSinResultado); $i++)
        {
          ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
          $html .= "    <tr class=\"".$estilo."\">\n";
          $html .= "      <td align=\"left\">".$OrdenesSinResultado[$i]['fecha_activacion']."</td>\n";
          $html .= "      <td align=\"left\">".$OrdenesSinResultado[$i]['numero_orden_id']."</td>\n";
          $html .= "      <td align=\"left\">".$OrdenesSinResultado[$i]['cargo']."</td>\n";
          $html .= "      <td align=\"left\">".$OrdenesSinResultado[$i]['descripcion_cargo_cups']."</td>\n";
          $html .= "      <td align=\"left\">".$OrdenesSinResultado[$i]['cantidad']."</td>\n";
          $html .= "      <td align=\"left\">".$OrdenesSinResultado[$i]['descripcion_tipo_solicitud']."</td>\n";
          
          $html .= "      <td align=\"left\">\n";
          $html .= "        <select name=\"motivo_ordenes_sin_resultado_".$i."\" id=\"motivo_ordenes_sin_resultado_".$i."\" class='select'>\n";
          $html .= "          <option value=\"-1\" >--SELECCIONE--</option>\n";
          foreach($motivosNoEjecutar as $k => $vlr)
          {
            $html .= "          <option value=\"".$vlr['motivos_cambios_estado_solicitudes_ordenes_no_ejecutadas_id']."\" >".$vlr['descripcion']."</option>\n";
          } 
          $html .= "        </select>\n";
          $html .= "      </td>\n";          
          
          $html .= "      <td align=\"center\"><input type=\"checkbox\" id=\"ordenSinResultado_".$i."\" name=\"ordenSinResultado_".$i."\" value=\"".$OrdenesSinResultado[$i]['numero_orden_id']."\"></td>\n";
          $html .= "    </tr>\n";                
        }
        $html .= "</table>\n";
        
      }
      
      if(sizeof($OrdenesSinResultado) > 0)
      {
        $html .= "<input type=\"hidden\" id=\"cantidadOrdenesSinResultados\" name=\"cantidadOrdenesSinResultados\" value=\"".$i."\">\n";     
      }
      else
      {
        $html .= "<input type=\"hidden\" id=\"cantidadOrdenesSinResultados\" name=\"cantidadOrdenesSinResultados\" value=\"0\">\n";
      }
      
      $html .= "<div id=\"mensaje_error\" align=\"center\" style=\"display:none;padding:4px\">\n";   
      $html .= "  <label class=\"label_error\">DEBE DE INGRESAR UNA OBSERVACI&Oacute;N DEL CAMBIO DE ESTADO.</label>\n";
      $html .= "</div>\n";      
      $html .= "<div id=\"mensaje_error_solicitudes\" align=\"center\" style=\"display:none;padding:4px\">\n";   
      $html .= "  <label class=\"label_error\">DEBE DE SELECCIONAR EL MOTIVO DE DE LAS SOLICITUDES SIN AUTORIZACI&Oacute;N QUE NO SER&Aacute;N EJECUTAR.</label>\n";
      $html .= "</div>\n"; 
      $html .= "<div id=\"mensaje_error_ordenes\" align=\"center\" style=\"display:none;padding:4px\">\n";   
      $html .= "  <label class=\"label_error\">DEBE DE SELECCIONAR EL MOTIVOS DE LAS &Oacute;RDENES SIN RESULTADOS QUE NO SER&Aacute;N EJECUTAR.</label>\n";
      $html .= "</div>\n";
      
      $html .= "	<br><table width=\"60%\" align=\"center\">";
      $html .= "		<tr align=\"center\" class=\"\">";
      $html .= "			<td class=\"label\">";
      $html .= "			<fieldset>";
      $html .= "				<legend>OBSERVACI&Oacute;N</legend>";
      $html .= "				  <textarea name=\"observacion\" id=\"observacion\" cols=\"80\" rows=\"4\" class=\"textarea\"></textarea>";
      $html .= "			</fieldset><br>";
      $html .= "			</td>";
      $html .= "		</tr>";
      $html .= "	</table>";    
      
			$html .= "	<br><table width=\"70%\" align=\"center\">";
			$html .= "		<tr>";
			$html .= "			<td align=\"center\"><input type=\"button\" name=\"RegistrarNoEjecutarSolicitudesOrdenes\" value=\"NO EJECUTAR ORDENES DE SERVICIO\" class=\"input-submit\"  onclick=\"validarDatosFormulario()\"></td>";
			$html .= "	</form>";   
      ////
      $datosVolver['buscador']['Cuenta'] = $request['cuenta'];
      $action['ver_cn']=ModuloGetURL('app','Cuentas','user','FormaMostrarCuenta',$datosVolver);
      $html .= "	<form name=\"forma_solicitudes_ordenes_no_ejecutadas\" id=\"forma_solicitudes_ordenes_no_ejecutadas\" action=\"".$action['ver_cn'].UrlRequest(array("Cuenta"=>$request['cuenta'],"sw_cuentas"=>"1"))."\" method=\"post\">";
      $html .= "			<td align=\"left\"><input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\"></td>";
			$html .= "		</tr>";
			$html .= "	</table>";      
      
      $html .= "	</form>";   
            
      $html .= ThemeCerrarTabla();
			return $html;
    }
    
		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function FormaMensaje($titulo,$align,$action,$mensaje,$script)
		{
			$html .= ThemeAbrirTabla($titulo);
			$html .= "<script>\n";
			$html .= "	function Aceptar()\n";
			$html .= "	{\n";
			$html .= "		document.forma.action = \"".$action['aceptar']."\";\n";
			$html .= "		document.forma.submit();\n";
			$html .= "	}\n";
			$html .= "	function Cancelar()\n";
			$html .= "	{\n";
			$html .= "		document.forma.action = \"".$action['cancelar']."\";\n";
			$html .= "		document.forma.submit();\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<form name=\"forma\" action=\"\" method=\"post\">\n";
			$html .= "	<table align=\"center\" width=\"90%\" class=\"modulo_table_list\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td class=\"label\" align=\"".$align."\" colspan=\"3\">\n";
			$html .= "				<br>".$mensaje."<br>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "	<table align=\"center\" width=\"60%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"button\" class=\"input-submit\" value=\"Aceptar\" onclick=\"Aceptar()\">\n";
			$html .= "			</td>\n";
						
			if($action['cancelar'])
			{
				$html .= "			<td align=\"center\">\n";
				$html .= "				<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"Cancelar()\">\n";
				$html .= "			</td>\n";
				
			}
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			$html .= $script;
			$html .= ThemeCerrarTabla();
			return $html;
		}
	}
?>