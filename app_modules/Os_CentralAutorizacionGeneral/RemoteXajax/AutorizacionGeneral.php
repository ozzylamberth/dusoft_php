<?php
		
		function reqSetValoresCargo($tipo_id_paciente,$paciente_id,$plan_id,$numero_autorizacion)
		{
			$aos = new AutorizacionGeneralOS();
			
			$Cargos = $aos->ObtenerCargosSolicitudes($tipo_id_paciente,$paciente_id,$plan_id,$numero_autorizacion,'0');
			$Solicitud = $aos->ObtenerSolicitudesAutorizadas($tipo_id_paciente,$paciente_id,$plan_id,$numero_autorizacion,'0');
						
			SessionDelVar("CargosSolicitud");
			SessionSetVar("CargosSolicitud",$Cargos);
			
			SessionDelVar("Solicitud");
			SessionSetVar("Solicitud",$Solicitud);
			
			$objResponse = new xajaxResponse();
			return $objResponse;
		}
		
		function reqActualizarCargos($key,$keyI,$keyII,$keyIII,$dptno)
		{			
			$objResponse = new xajaxResponse();
			$Solicitud = SessionGetVar("Solicitud");
			$Solicitud[$dptno][$keyI][$keyII] = $Solicitud[$key][$keyI][$keyII];
				
			unset($Solicitud[$key][$keyI][$keyII]);
				
			if(empty($Solicitud[$key][$keyI]))
			{
				unset($Solicitud[$key][$keyI]);
				if(empty($Solicitud[$key]))
					unset($Solicitud[$key]);
			}
			
			ksort($Solicitud);
			SessionSetVar("Solicitud",$Solicitud);
				
			$datos = SessionGetVar("DatosPaciente");
			$Cargos = SessionGetVar("CargosSolicitud");
			$Selecc = SessionGetVar("CargosOSSeleccionados");
			
			$aos = new AutorizacionGeneralOS();
			$aoh = new AutorizacionGeneralHTML();
				
			$html = $aoh->CrearTablas(&$aos,$datos,$Solicitud,$Cargos,$Selecc);
			$html = $objResponse->setTildes($html);
		
			$objResponse->assign("seleccionados","innerHTML",$html);

			return $objResponse;
		}
		
		function reqCombinarDatos($datos)
		{
			$nc = array();
			$Solicitud = SessionGetVar("Solicitud");
			$Selecc = SessionGetVar("CargosOSSeleccionados");
			$SCargos = SessionGetVar("CargosSolicitud");
			
			$aos = new AutorizacionGeneralOS();
			
			$cargo_cantidad = explode(";",$datos);
			
			for($j=0; $j<sizeof($cargo_cantidad); $j++ )
			{
				$cc = explode(":",$cargo_cantidad[$j]);
				foreach($Solicitud as $key => $departamento)
				{		
					foreach($departamento as $key0 => $tipoorden)
					{			
						foreach($tipoorden as $keyI => $solicitud)
						{
							foreach($solicitud as $keyII => $Cargos)
							{	
								$i = 0;
								foreach($SCargos[$Cargos['plan_id']][$Cargos['cargo']] as $keyIV => $equival)
								{
									if($equival['cargo'] == $cc[0])
									{
										$cantidad = $cc[1];
										/*if($seleccion['cargos1'][$Cargos['plan_id']][$Cargos['hc_os_solicitud_id']][$Cargos['cargo']][$equival['cargo']])
											$cantidad = $seleccion['cargos1'][$Cargos['plan_id']][$Cargos['hc_os_solicitud_id']][$Cargos['cargo']][$equival['cargo']];
										else
											$cantidad = $Cargos['cantidad'];*/

										$evento = $aos->ObtenerEvento($Cargos['hc_os_solicitud_id']);
											
										$nc[$key][$evento][$Cargos['servicio']][$Cargos['hc_os_solicitud_id']]['cargo_cup'][$keyII]['cargo'][$i]['cargo'] = $equival['cargo'];
										$nc[$key][$evento][$Cargos['servicio']][$Cargos['hc_os_solicitud_id']]['cargo_cup'][$keyII]['cargo'][$i]['cantidad'] = $cantidad;
										$nc[$key][$evento][$Cargos['servicio']][$Cargos['hc_os_solicitud_id']]['cargo_cup'][$keyII]['cargo'][$i]['tarifario_id'] = $equival['tarifario_id'];
										$nc[$key][$evento][$Cargos['servicio']][$Cargos['hc_os_solicitud_id']]['cargo_cup'][$keyII]['cantidad'] = $cantidad;
										$i++;
									}
								}
							}
						}
					}
				}
			}
			//SessionDelVar("Solicitud");
			//SessionDelVar("CargosOSSeleccionados");
			//SessionDelVar("CargosSolicitud");
			//print_r($nc);
			SessionSetVar("OrdenesServicio",$nc);
			
			$objResponse = new xajaxResponse();
			$objResponse->call("ContinuarOrdenServicio");
			return $objResponse;
		}
?>