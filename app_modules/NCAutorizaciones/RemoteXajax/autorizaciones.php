<?php
		function reqEvaluarAutorizacion($tipo,$clase,$fecha,$hora,$afiliado,$rango,$validez)
		{
			$mensje = "";
			$fec = explode('/',$fecha);
			$fecha = $fec[2].'/'.$fec[1].'/'.$fec[0];
			if($rango == 0) $rango = true;
			
			if($afiliado == '-1')
				$mensaje = "SE DEBE ESPECIFICAR EL TIPO DE AFILIADO";
				else if(!$rango)
					$mensaje = "SE DEBE ESPECIFICAR EL RANGO AL CUAL PERTENECE EL PACIENTE";
					else if($tipo != 'I' && $tipo != 'E')
						$mensaje = "SE DEBE ESPECIFICAR EL TIPO DE AUTORIZACION - INTERNA O EXTERNA";
						else if($clase == '0')
							$mensaje = "SE DEBE SELECCIONAR LA CLASE DE AUTORIZACION PARA EL TIPO DE AUTORIZACION ESPECIFICADO";
							else if(!ValidarFecha($fecha,'/'))
								$mensaje = "EL FORMATO DE FECHA DE AUTORIZACIÓN, ES INCORRECTO";
								else if(!ValidarHora($hora,&$mensaje))
									$mensaje .= "LA HORA INGRESADA ES INCORRECTA";
									else if($fecha > date("Y/m/d") || $hora > date("H:i"))
										$mensaje .= "LA FECHA DE LA AUTORIZACIÓN NO DEBE SER MAYOR A LA FECHA HORA ACTUAL: ".date("d/m/Y")." ".date("H:i");
										else if($validez)
										{
											$fec = explode('/',$validez);
											$validez = $fec[2].'/'.$fec[1].'/'.$fec[0];
											
											if(!ValidarFecha($validez,'/'))
												$mensaje = "EL FORMATO DE LA FECHA DE VALIDEZ, ES INCORRECTO";
											else if($validez < date("Y/m/d") )
												$mensaje = "LA FECHA DE VALIDEZ, DEBE SER MAYOR O IGUAL A LA FECHA ACTUAL: ".date("d/m/Y");
										}

			$objResponse = new xajaxResponse();
			if($mensaje != "")
			{
				$mensaje = $objResponse->setTildes($mensaje);
				$objResponse->assign("error","innerHTML",$mensaje);
			}
			else
				$objResponse->call("ContinuarAutorizacion");
			
			return $objResponse;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function reqMostrarAutorizacion($ingreso,$fnc)
		{
			IncludeClass('ConsultaAutorizaciones','','app','NCAutorizaciones');
			IncludeClass('ConsultaAutorizacionesHTML','','app','NCAutorizaciones');

			$caut = new ConsultaAutorizaciones();
			$chtml = new ConsultaAutorizacionesHTML();
			$objResponse = new xajaxResponse();
			
			SessionSetVar("IngresoAutorizacion",$ingreso);
			
			$datos = $caut->ObtenerAutorizaciones(array("ingreso"=>$ingreso),"'OS'");
			$datosOS = $caut->ObtenerAutorizaciones(array("ingreso"=>$ingreso),"'AD','**'");

			$paciente = $caut->ObtenerDatosPaciente($ingreso);
			
			$vista = $chtml->FormaCrearVistaAutorizacion($datos,$paciente,$datosOS);
			$url = UrlRequest($chtml->envio);
			
			$html  = "<center>";
			$html .= "	<table width=\"70%\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<a href=\"javascript:VerLista()\" class=\"label_error\">LISTA DE AUTORIZACIONES</a>\n";
			$html .= "			</td>\n";
			$html .= "			<td>\n";
			$html .= "				<a href=\"javascript:CrearAutorizaciones('".$url."')\" class=\"label_error\">NUEVA AUTORIZACION</a>\n";
			$html .= "			</td>\n";
			$html .= "			<td>\n";
			$html .= "				<a href=\"javascript:$fnc\" class=\"label_error\">REPORTE</a>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</center><br>\n";
			$html .= $vista;
			
			$html .= "<br>\n";
			$html .= "<center>";
			$html .= "	<a href=\"javascript:VerLista()\" class=\"label_error\">LISTA DE AUTORIZACIONES</a>\n";
			$html .= "</center><br>\n";
			
			$html = $objResponse->setTildes($html);
			$objResponse->assign("lista_autorizaciones","style.display","none");
			$objResponse->assign("autorizacion","style.display","block");
			$objResponse->assign("autorizacion","innerHTML",$html);
			$objResponse->script("tabPane = new WebFXTabPane( document.getElementById( \"anteriores\" ), false);");
			$objResponse->script("tabPane.addTabPage( document.getElementById(\"servicios\"));");
			$objResponse->script("tabPane.addTabPage( document.getElementById(\"ordenes\"));");
			$objResponse->script("setupAllTabs();");

			return $objResponse;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function reqMostrarLista()
		{
			$objResponse = new xajaxResponse();
			$objResponse->assign("autorizacion","innerHTML","");
			$objResponse->assign("autorizacion","style.display","none");
			$objResponse->assign("lista_autorizaciones","style.display","block");
			return $objResponse;
		}
		/*************************************************************************************
		* Funcion donde se evalua si un afecha es correcta o no 
		* 
		* @param $fecha dato a evaluar
		* @return boolean 
		**************************************************************************************/
		function ValidarFecha($fecha,$marca)
		{			
			$f = explode($marca,$fecha); 
			
			$resultado = checkdate($f[1],$f[2],$f[0]);
			if($resultado != 1 || sizeof($f) != 3)
				return false;
						
			return true;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function ValidarHora($hora,&$mensaje)
		{			
			$h = explode(":",$hora); 
			
			$hora = intval($h[0]);
			$minuto = intval($h[1]);
			
			if($hora > 23 || $hora < 0  || $minuto > 59 || $minuto < 0)
				return false;
						
			return true;
		}
?>