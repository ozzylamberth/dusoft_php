<?

 /**
 * $Id: app_EstacionEnfermeriaCargos_userclasses_HTML.php,v 1.9 2005/08/12 15:33:56 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria modulo para la atencion del paciente 
 */




/**
*		class app_EstacionEnfermeria_userclasses_HTML
*
*		Clase que maneja todas las funciones de vistas y consultas a la base de datos
*		relacionadas a la estaci&oacute;n de Enfermer&iacute;a
*		ubicacion => app_modules/EstacionEnfermeria/userclasses/app_EstacionEnfermeria_userclasses_HTML.php
*		fecha creaci&oacute;n => 04/05/2004 10:35 am
*
*		@Author Darling Dorado
*		@version =>
*		@package SIIS
*/
class app_EstacionEnfermeriaCargos_userclasses_HTML extends app_EstacionEnfermeriaCargos_user
{

	/**
	*		app_EstacionEnfermeria_userclasses_HTML()
	*
	*		constructor
	*
	*		@Author jairo Duvan Diaz Martinez.
	*		@access Private
	*		@return boolean
	*/
		function app_EstacionEnfermeriaCargos_userclasses_HTML()
		{
			$this->app_EstacionEnfermeriaCargos_user(); //Constructor del padre 'modulo'
			$this->salida = "";
			return true;
		}

		function SetStyle($campo)
		{
					if ($this->frmError[$campo] || $campo=="MensajeError"){
						if ($campo=="MensajeError"){
							return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
						}
						return ("label_error");
					}
				return ("label");
		}

		function FormaListadoPacientes($datos_estacion,$tipo)
		{
			$datoscenso = $this->CallMetodoExterno('app','Censo','user','GetCensoTipo1',array('estacion'=>$datos_estacion['estacion_id'],'centinela'=>1));
			
               if($datoscenso === "ShowMensaje")
			{
				$mensaje = "LA ESTACI&Oacute;N [ ".$datos_estacion['descripcion5']." ] NO CUENTA CON PACIENTES.";
				$titulo = "MENSAJE";
				$boton = "REGRESAR";
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->FormaMensaje($mensaje,$titulo,$href,$boton);
				return true;
			}

			if(!empty($datoscenso))
			{
				$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
				$this->salida .= ThemeAbrirTabla("AGREGAR CARGOS - [ ".$datos_estacion['descripcion5']." ]");
				$x=0;
				$w=0;

				foreach($datoscenso as $key => $value)
				{
						$mostrar ="\n<script language='javascript'>\n";
						$mostrar.="function mOvr(src,clrOver) {;\n";
						$mostrar.="src.style.background = clrOver;\n";
						$mostrar.="}\n";

						$mostrar.="function mOut(src,clrIn) {\n";
						$mostrar.="src.style.background = clrIn;\n";
						$mostrar.="}\n";
						$mostrar.="</script>\n";
						$this->salida .="$mostrar";

						$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
						$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
						$this->salida .= "		<td></td>\n";
						$this->salida .= "		<td>HABITACION</td>\n";
						$this->salida .= "		<td>CAMA</td>\n";
						$this->salida .= "		<td>PACIENTE</td>\n";
						$this->salida .= "		<td>ID</td>\n";
						$this->salida .= "		<td>INGRESO</td>\n";
						$this->salida .= "		<td>CUENTA</td>\n";
						$this->salida .= "		<td>ACCI&Oacute;N</td>\n";
						$this->salida .= "	</tr>\n";


						//mostramos los pacientes pendientes por ingresar .. si hay
						if($w==0)
						{
							$pacientes = $this->GetPacientesPendientesXHospitalizar($datos_estacion);
							if(is_array($pacientes))
							{
								$this->Pacientes_X_Ingresar($datos_estacion,$pacientes,$tipo);
							}
							$w=1;
						}


						foreach($value as $A => $B)
						{
							if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
							$this->salida .= "<tr class='$estilo'  onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";


                                   $traslado=$this->Revisar_Si_esta_trasladado($B[ingreso]);
                                   $info=$this->RevisarSi_Es_Egresado($B[ingreso_dpto_id]);

							if($info[1]==2)//si es 2 egreso efectuado
							{
								$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/egresook.png\" border='0'></td>\n";
							}
							elseif($info[1]=='1' OR $info[1]=='0')//es 1 enfermera-0 medico
							{
								$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/egreso.png\" border='0'></td>\n";
							}
							else
							{
								if($traslado >0)
								{
									$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/uf.png\" border='0'></td>\n";
								}
								else
								{
										$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/honorarios.png\" border='0'></td>\n";
								}
							}

							$this->salida .= "	<td align=\"center\">".$B[pieza]."</td>\n";
							$this->salida .= "	<td align=\"center\">".$B[cama]."</td>\n";
							$this->salida .= "	<td>".$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido]."</td>\n";
							$this->salida .= "	<td align=\"left\">".$B[tipo_id_paciente]." ".$B[paciente_id]."</td>\n";
							$this->salida .= "	<td align=\"left\">".$B[fecha_ingreso]."</td>\n";
							$this->salida .= "	<td align=\"center\">".$B[numerodecuenta]."</td>\n";
							if($tipo==1)
							{
										$href=ModuloGetURL('app','EstacionEnfermeriaCargos','user','Cargos',array("estacion"=>$datos_estacion,"Cuenta"=>$B['numerodecuenta'],'TipoId'=>$B[tipo_id_paciente],'PacienteId'=>$B[paciente_id],'Nivel'=>$B[rango],'PlanId'=>$B[plan_id],'ingreso'=>$B[ingreso]));
										$this->salida .= "	<td align=\"center\"><a href=\"$href\">Agregar Cargos</a></td>\n";
							}
							else
							{
										$href=ModuloGetURL('app','EstacionEnfermeriaCargos','user','LlamarFormaBodegas',array("estacion"=>$datos_estacion,"Cuenta"=>$B['numerodecuenta'],'TipoId'=>$B[tipo_id_paciente],'PacienteId'=>$B[paciente_id],'Nivel'=>$B[rango],'PlanId'=>$B[plan_id],'ingreso'=>$B[ingreso]));
										$this->salida .= "	<td align=\"center\"><a href=\"$href\">Agregar Insumos</a></td>\n";
							}

							$this->salida .= "</tr>\n";
						}
						if($x==0)
						{
                                   $pac_consulta=$this->BuscarPacientesConsulta_Urgencias($datos_estacion,$tipo);
                                   if(is_array($pac_consulta))
                                   {
                                   	$this->Pacientes_X_Consulta_Urgencias($datos_estacion,$pac_consulta,$tipo);
                                   }
                                   $x=1;
						}
						$this->salida .= "</table><br>\n";
						//fin formato hospitalizacio

			}//fin foreach

				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
				$this->salida .= themeCerrarTabla();
				unset($ItemBusqueda);
				return true;
			}
			else
			{
							$this->salida .= ThemeAbrirTabla("AGREGAR CARGOS - [ ".$datos_estacion['descripcion5']." ]");
							$mostrar ="\n<script language='javascript'>\n";
							$mostrar.="function mOvr(src,clrOver) {;\n";
							$mostrar.="src.style.background = clrOver;\n";
							$mostrar.="}\n";

							$mostrar.="function mOut(src,clrIn) {\n";
							$mostrar.="src.style.background = clrIn;\n";
							$mostrar.="}\n";
							$mostrar.="</script>\n";
							$this->salida .="$mostrar";

							$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
							$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
							$this->salida .= "		<td></td>\n";
							$this->salida .= "		<td>HABITACION</td>\n";
							$this->salida .= "		<td>CAMA</td>\n";
							$this->salida .= "		<td>PACIENTE</td>\n";
							$this->salida .= "		<td>ID</td>\n";
							$this->salida .= "		<td>INGRESO</td>\n";
							$this->salida .= "		<td>CUENTA</td>\n";
							$this->salida .= "		<td>ACCI&Oacute;N</td>\n";
							$this->salida .= "	</tr>\n";
							$pacientes = $this->GetPacientesPendientesXHospitalizar($datos_estacion);
							if(is_array($pacientes))
							{
								$this->Pacientes_X_Ingresar($datos_estacion,$pacientes,$tipo);
							}

							$pac_consulta=$this->BuscarPacientesConsulta_Urgencias($datos_estacion,$tipo);
							if(is_array($pac_consulta))
							{$this->Pacientes_X_Consulta_Urgencias($datos_estacion,$pac_consulta,$tipo);}
							$this->salida .= "</table><br>\n";
                                   $href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
							$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
							$this->salida .= themeCerrarTabla();
							unset($ItemBusqueda);
							return true;
			}
		}




     /*
     * funcion que revisa los pacientes que esta en consulta de urgencias..
     */
     function Pacientes_X_Consulta_Urgencias($datos_estacion,$pacientes,$tipo)
     {

		if(is_array($pacientes))
		{
               for($i=0; $i<sizeof($pacientes); $i++)
               {
                    $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

                    if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $this->salida .= "<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                    $nombre=$pacientes[$i][0]." ".$pacientes[$i][1];
                    if($pacientes[$i][11]==1)
                    {
                              $this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/consulta_ur.png\" border='0' title='PACIENTE EN CONSULTA'></td>\n";
                              $this->salida .= "	<td  colspan='2' align=\"center\"><label class=label_mark>CONSULTA</label></td>\n";
                    }
                    elseif($pacientes[$i][11]==7)
                    {
                              $this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/egreso.png\" border='0' title='EGRESO ESTACION'></td>\n";
                              $this->salida .= "	<td  colspan='2' align=\"center\"><label class=label_mark>CONSULTA - ATENCION ENFERMERIA</label></td>\n";
                    }

                    $this->salida .= "	<td align=\"left\">".$pacientes[$i][0]." ".$pacientes[$i][1]."</td>\n";
                    $this->salida .= "	<td align=\"left\">".$pacientes[$i][3]." - ".$pacientes[$i][2]."</td>\n";
                    $this->salida .= "	<td align=\"left\"><label class=label_mark><SUB>NO ASIGNADO A CAMA</SUB></label></td>\n";
                    $this->salida .= "	<td align=\"center\">".$pacientes[$i][6]."</td>\n";
                    
                    if($tipo==1)
                    {
                                   $href=ModuloGetURL('app','EstacionEnfermeriaCargos','user','Cargos',array("estacion"=>$datos_estacion,"Cuenta"=>$pacientes[$i][6],'TipoId'=>$pacientes[$i][3],'PacienteId'=>$pacientes[$i][2],'Nivel'=>$pacientes[$i][12],'PlanId'=>$pacientes[$i][7],'ingreso'=>$pacientes[$i][4]));
                                   $this->salida .= "	<td align=\"center\"><a href=\"$href\">Agregar Cargos</a></td>\n";
                    }
                    else
                    {
                                   $href=ModuloGetURL('app','EstacionEnfermeriaCargos','user','LlamarFormaBodegas',array("estacion"=>$datos_estacion,"Cuenta"=>$pacientes[$i][6],'TipoId'=>$pacientes[$i][3],'PacienteId'=>$pacientes[$i][2],'Nivel'=>$pacientes[$i][12],'PlanId'=>$pacientes[$i][7],'ingreso'=>$pacientes[$i][4]));
                                   $this->salida .= "	<td align=\"center\"><a href=\"$href\">Agregar Insumos</a></td>\n";
                    }
				$this->salida .= "</tr>\n";
               }//fin for
		}//pacientes por ingresar
		return true;
	}




/*
* funcion que revisa los pacientes que esta en consulta de urgencias..
*/
     function Pacientes_X_Ingresar($datos_estacion,$pacientes,$tipo)
     {

		if(is_array($pacientes))
		{
               for($i=0; $i<sizeof($pacientes); $i++)
               {
                    $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

                    if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $this->salida .= "<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                    $nombre=$pacientes[$i][0]." ".$pacientes[$i][1];

                    $this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/ingresar.png\" border='0' title='EGRESO ESTACION'></td>\n";
                    $this->salida .= "	<td  colspan='2' align=\"center\"><label class=label_mark>PENDIENTE ASIGNACIÓN DE CAMA</label></td>\n";

                    $this->salida .= "	<td align=\"left\">".$pacientes[$i][0]." ".$pacientes[$i][1]."</td>\n";
                    $this->salida .= "	<td align=\"left\">".$pacientes[$i][3]." - ".$pacientes[$i][2]."</td>\n";
                    $this->salida .= "	<td align=\"left\"><label class=label_mark><SUB>NO ASIGNADO A CAMA</SUB></label></td>\n";
                    $this->salida .= "	<td align=\"center\">".$pacientes[$i][6]."</td>\n";

                   if($tipo==1)
                    {
                                   $href=ModuloGetURL('app','EstacionEnfermeriaCargos','user','Cargos',array("estacion"=>$datos_estacion,"Cuenta"=>$pacientes[$i][6],'TipoId'=>$pacientes[$i][3],'PacienteId'=>$pacientes[$i][2],'Nivel'=>$pacientes[$i][12],'PlanId'=>$pacientes[$i][7],'ingreso'=>$pacientes[$i][4]));
                                   $this->salida .= "	<td align=\"center\"><a href=\"$href\">Agregar Cargos</a></td>\n";
                    }
                    else
                    {
                                   $href=ModuloGetURL('app','EstacionEnfermeriaCargos','user','LlamarFormaBodegas',array("estacion"=>$datos_estacion,"Cuenta"=>$pacientes[$i][6],'TipoId'=>$pacientes[$i][3],'PacienteId'=>$pacientes[$i][2],'Nivel'=>$pacientes[$i][12],'PlanId'=>$pacientes[$i][7],'ingreso'=>$pacientes[$i][4]));
                                   $this->salida .= "	<td align=\"center\"><a href=\"$href\">Agregar Insumos</a></td>\n";
                    }

				$this->salida .= "</tr>\n";
      		}//fin for
		}//pacientes por ingresar
		return true;
	}








	function	FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$estacion,$D,$var,$Ayudas,$Cobertura)
	{
          IncludeLib("tarifario");
          $dpto=$_SESSION['CUENTAS']['E']['DEPTO'];
          $Ingreso=$_SESSION['CUENTAS']['E']['INGRESO'];
          $TipoId=$_SESSION['CUENTAS']['E']['tipo_id_paciente'];
          $PacienteId=$_SESSION['CUENTAS']['E']['paciente_id'];
          $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
          $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
          $Var=$this->CoutaPaciente($PlanId,$Nivel);
          $Copago=$Var[copago];
          $PorPaciente=$Var[cuota_moderadora];
          $Maximo=$Var[copago_maximo];
          $Minimo=$Var[copago_minimo];
          $this->salida .= ThemeAbrirTabla('AGREGAR CARGO A LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);
          //$this->EncabezadoEmpresa($Caja);
          $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
          $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
          $datos=$this->DatosTmpCuentas($Cuenta);
          $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= "	</table>";
          $Apoyo=$this->DatosTmpAyudas($Cuenta);
          $Ayudas=$this->DatosAyudasPasa($Cuenta);//cuando ya existe un cargo de agrupamiento

          if(!$Apoyo && sizeof($datos)==1 && !$D) $f=true;
          if(sizeof($Apoyo)==1 && sizeof($datos)==1 && !$D) $f=true;

          if($datos)
          { //$D existe si va a modificar
               //	if(sizeof($datos)==1 || sizeof($Apoyo)>1 && !$D) $Paso=1;
                    if(sizeof($datos)==1 && !$D) $Paso=1;
                    if(sizeof($datos)>1 || $Paso==1)
                    {
                                   $this->salida .= " <table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"93%\" align=\"center\" >";
                                   $this->salida .= "	  <tr align=\"center\" class=\"modulo_table_list_title\">";
                                   $this->salida .= "	      <td>DEPARTAMENTO</td>";
                                   $this->salida .= "	      <td>CARGO</td>";
                                   $this->salida .= "	      <td>DESCRIPCION</td>";
                                   $this->salida .= "	      <td>PRECIO</td>";
                                   $this->salida .= "	      <td>CANT.</td>";
                                   $this->salida .= "	      <td>VALOR</td>";
                                   $this->salida .= "	      <td>VAL. NO CUBIERTO</td>";
                                   $this->salida .= "	      <td>VAL. PACIENTE</td>";
                                   $this->salida .= "	      <td>VAL. EMPRESA</td>";
                                   $this->salida .= "	      <td></td>";
                                   $this->salida .= "	      <td></td>";
                                   $this->salida .= "	  </tr>";
                                   $ValTotal=$TotalNo=$TotalCopago=$TotalEmpresa=$ValTotalPaciente=0;
                                   $k=0;
                                   for($i=0; $i<sizeof($datos);$i++)
                                   {
                                             $Datos=$datos;
                                             $C=$Datos[$i][cargo];
                                             $Agrupado=$this->BuscarCagoAgrupado($C);
                                             if($D[transaccion]!=$Datos[$i][transaccion] && empty($Agrupado[cargo_agrupamiento_sistema]))
                                             {
                                                       $x=1;
                                                       $Valor=$Datos[$i][valor_cargo];
                                                       $ValTotal+=$Valor;
                                                       $ValorNo=$Datos[$i][valor_nocubierto];
                                                       $TotalNo+=$ValorNo;
                                                       $ValPac=$Datos[$i][valor_cuota_paciente];
                                                       $TotalCopago+=$ValPac;
                                                       $ValTotalPaciente+=$ValPac;
                                                       $ValEmpresa=$Datos[$i][valor_cubierto]-$Datos[$i][valor_cuota_paciente];
                                                       $TotalEmpresa+=$ValEmpresa;
                                                       $Descripcion=$this->BuscarNombreCargo($Datos[$i][tarifario_id],$Datos[$i][cargo]);
                                                       $Dpto=$this->BuscarNombreDpto($Datos[$i][departamento]);
                                                       $c=round($Datos[$i][cantidad]);
                                                       if( $k % 2) $estilo='modulo_list_claro';
                                                       else $estilo='modulo_list_oscuro';
                                                       $this->salida .= "	  <tr class=\"$estilo\" align=\"center\">";
                                                       $this->salida .= "	      <td>$Dpto</td>";
                                                       $this->salida .= "	      <td>$C</td>";
                                                       $this->salida .= "	      <td>$Descripcion[0]</td>";
                                                       $this->salida .= "	      <td>".FormatoValor($Datos[$i][precio])."</td>";
                                                       $this->salida .= "	      <td>$c</td>";
                                                       $this->salida .= "	      <td>".FormatoValor($Valor)."</td>";
                                                       $this->salida .= "	      <td>".FormatoValor($ValorNo)."</td>";
                                                       $this->salida .= "	      <td>".FormatoValor($ValPac)."</td>";
                                                       $this->salida .= "	      <td>".FormatoValor($ValEmpresa)."</td>";
                                                       $accionModificar=ModuloGetURL('app','EstacionEnfermeriaCargos','user','LlamaFormaModificarCargoTmp',array('Transaccion'=>$Datos[$i][transaccion],'Datos'=>$datos[$i],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                                                       $this->salida .= "	      <td><a href=\"$accionModificar\" alt=\"Modificar los cargos del paciente\">MODI</a></td>";
                                                       $accionEliminar=ModuloGetURL('app','EstacionEnfermeriaCargos','user','EliminarCargoTmp',array('Transaccion'=>$Datos[$i][transaccion],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                                                       $this->salida .= "	      <td><a href=\"$accionEliminar\">ELIM</a></td>";
                                                       $this->salida .= "	  </tr>";
                                                       $k++;
                                             }
                                   }
                    }

                    $j=$k;
                    if(sizeof($Apoyo)==1 && !$D) $PasoA=1;
                    if(sizeof($Apoyo)==1 && $D && $D[1]!='Si') $PasoA=1;
                    if(sizeof($Apoyo)>1 || $PasoA==1)
                    {
                              for($d=0; sizeof($Apoyo[$d]); $d++)
                              {
                                   if($D[consecutivo]!=$Apoyo[$d][consecutivo])
                                        {
                                                  $x=1;
                                                  if( $j % 2) $estilo='modulo_list_claro';
                                                  else $estilo='modulo_list_oscuro';
                                                  $j++;
                                                  $TransaccionA=$Apoyo[$d][transaccion];
                                                  //$CodDpto=$this->BuscarDpto($TransaccionA,$Cuenta);
                                                  $Dpto=$this->BuscarNombreDpto($Apoyo[$d][departamento]);
                                                  $CargoA=$Apoyo[$d][cargo];
                                                  $PrecioA=$Apoyo[$d][precio];
                                                  $CantidadA=round($Apoyo[$d][cantidad]);
                                                  $ValorCargoA=$Apoyo[$d][valor_cargo];
                                                  $ValorPacA=$Apoyo[$d][valor_cuota_paciente];
                                                  $ValorNoA=$Apoyo[$d][valor_nocubierto];
                                                  $ValEmpresaA=$Apoyo[$d][valor_cubierto];
                                                  $TotalEmpresa+=$ValEmpresaA;
                                                  $TotalCopago+=$ValorPacA;
                                                  $TotalNo+=$ValorNoA;
                                                  $ValTotal+=$ValorCargoA;
                                                  $Consecutivo=$Apoyo[$d][consecutivo];
                                                  $Descripcion=$this->BuscarNombreCargo($Apoyo[$d][tarifario_id],$CargoA);
                                                  $this->salida .= "	  <tr class=\"$estilo\" align=\"center\">";
                                                  $this->salida .= "	      <td>$Dpto</td>";
                                                  $this->salida .= "	      <td>$CargoA</td>";
                                                  $this->salida .= "	      <td>$Descripcion[0]</td>";
                                                  $this->salida .= "	      <td>".FormatoValor($PrecioA)."</td>";
                                                  $this->salida .= "	      <td>$CantidadA</td>";
                                                  $this->salida .= "	      <td>".FormatoValor($ValorCargoA)."</td>";
                                                  $this->salida .= "	      <td>".FormatoValor($ValorNoA)."</td>";
                                                  $this->salida .= "	      <td>".FormatoValor($ValorPacA)."</td>";
                                                  $this->salida .= "	      <td>".FormatoValor($ValEmpresaA)."</td>";
                                                  $accionModificar=ModuloGetURL('app','EstacionEnfermeriaCargos','user','LlamaFormaModificarCargoTmp',array('Transaccion'=>$TransaccionA,'Datos'=>$Apoyo[$d],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Apoyo'=>true,'Consecutivo'=>$Consecutivo));
                                                  $this->salida .= "	      <td><a href=\"$accionModificar\" alt=\"Modificar los cargos del paciente\">MODI</a></td>";
                                                  $accionEliminar=ModuloGetURL('app','EstacionEnfermeriaCargos','user','EliminarCargoTmp',array('Transaccion'=>$TransaccionA,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Apoyo'=>true,'Consecutivo'=>$Consecutivo));
                                                  $this->salida .= "	      <td><a href=\"$accionEliminar\">ELIM</a></td>";
                                                  $this->salida .= "	  </tr>";
                                        }
                              }
                    }
          }
          if($Ayudas)
          {
                    $paso=false;
                    if(!empty($D) AND sizeof(sizeof($Ayudas)) > 1){  $paso=true; }
                    if(empty($D)){ $paso=true; }
                    if(empty($datos) AND !empty($paso))
                    {
                              $this->salida .= " <table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"93%\" align=\"center\" >";
                              if(empty($datos) AND !empty($paso))
                              {
                                        $this->salida .= "	  <tr align=\"center\" class=\"modulo_table_list_title\">";
                                        $this->salida .= "	      <td>DEPARTAMENTO</td>";
                                        $this->salida .= "	      <td>CARGO</td>";
                                        $this->salida .= "	      <td>DESCRIPCION</td>";
                                        $this->salida .= "	      <td>PRECIO</td>";
                                        $this->salida .= "	      <td>CANT.</td>";
                                        $this->salida .= "	      <td>VALOR</td>";
                                        $this->salida .= "	      <td>VAL. NO CUBIERTO</td>";
                                        $this->salida .= "	      <td>VAL. PACIENTE</td>";
                                        $this->salida .= "	      <td>VAL. EMPRESA</td>";
                                        $this->salida .= "	      <td></td>";
                                        $this->salida .= "	      <td></td>";
                                        $this->salida .= "	  </tr>";
                              }
                    }
                    for($d=0; $d<sizeof($Ayudas); $d++)
                    { 
                         if($D[consecutivo]!=$Ayudas[$d][consecutivo])
                         {
                                   $x=1;
                                   if( $j % 2) $estilo='modulo_list_claro';
                                   else $estilo='modulo_list_oscuro';
                                   $j++;
                                   $TransaccionA=$Ayudas[$d][transaccion];
                                   $CodDpto=$this->BuscarDpto($TransaccionA,$Cuenta);
                                   $Dpto=$this->BuscarNombreDpto($Ayudas[$d][departamento]);
                                   $CargoA=$Ayudas[$d][cargo];
                                   $PrecioA=$Ayudas[$d][precio];
                                   $CantidadA=round($Ayudas[$d][cantidad]);
                                   $ValorCargoA=$Ayudas[$d][valor_cargo];
                                   $ValorPacA=$Ayudas[$d][valor_cuota_paciente];
                                   $ValorNoA=$Ayudas[$d][valor_nocubierto];
                                   $ValEmpresaA=$Ayudas[$d][valor_cubierto];
                                   $TotalEmpresa+=$ValEmpresaA;
                                   $TotalCopago+=$ValorPacA;
                                   $TotalNo+=$ValorNoA;
                                   $ValTotal+=$ValorCargoA;
                                   $Consecutivo=$Ayudas[$d][consecutivo];
                                   $Descripcion=$this->BuscarNombreCargo($Ayudas[$d][tarifario_id],$CargoA);
                                   $this->salida .= "	  <tr class=\"$estilo\" align=\"center\">";
                                   $this->salida .= "	      <td>$Dpto</td>";
                                   $this->salida .= "	      <td>$CargoA</td>";
                                   $this->salida .= "	      <td>$Descripcion[0]</td>";
                                   $this->salida .= "	      <td>".FormatoValor($PrecioA)."</td>";
                                   $this->salida .= "	      <td>$CantidadA</td>";
                                   $this->salida .= "	      <td>".FormatoValor($ValorCargoA)."</td>";
                                   $this->salida .= "	      <td>".FormatoValor($ValorNoA)."</td>";
                                   $this->salida .= "	      <td>".FormatoValor($ValorPacA)."</td>";
                                   $this->salida .= "	      <td>".FormatoValor($ValEmpresaA)."</td>";
                                   $accionModificar=ModuloGetURL('app','EstacionEnfermeriaCargos','user','LlamaFormaModificarCargoTmp',array('Transaccion'=>$TransaccionA,'Datos'=>$Ayudas[$d],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Apoyo'=>true,'Consecutivo'=>$Consecutivo));
                                   $this->salida .= "	      <td><a href=\"$accionModificar\" alt=\"Modificar los cargos del paciente\">MODI</a></td>";
                                   $accionEliminar=ModuloGetURL('app','EstacionEnfermeriaCargos','user','EliminarCargoTmp',array('Transaccion'=>$TransaccionA,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Apoyo'=>true,'Consecutivo'=>$Consecutivo));
                                   $this->salida .= "	      <td><a href=\"$accionEliminar\">ELIM</a></td>";
                                   $this->salida .= "	  </tr>";
                         }
                    }
          }
          if($datos OR $Ayudas)
          {
                    if($x==1)
                    {
                              if( $j % 2) $estilo='modulo_list_claro';
                              else $estilo='modulo_list_oscuro';
                              $this->salida .= "	  <tr class=\"$estilo\" align=\"center\">";
                              $this->salida .= "	      <td colspan=\"5\"><b>TOTALES: </b></td>";
                              $this->salida .= "	      <td><b>".FormatoValor($ValTotal)."</b></td>";
                              $this->salida .= "	      <td><b>".FormatoValor($TotalNo)."</b></td>";
                              $this->salida .= "	      <td><b>".FormatoValor($TotalCopago)."</b></td>";
                              $this->salida .= "	      <td><b>".FormatoValor($TotalEmpresa)."</b></td>";
                              $this->salida .= "	      <td colspan=\"2\"></td>";
                              $this->salida .= "	  </tr>";
                              $this->salida .= "	  </table><br>";
                    }
          }
          global $_ROOT;
          $sw=ModuloGetVar('app','Facturacion','sw_gravar_cuota_paciente');
          $this->salida .= "\n<script>\n";
          $this->salida .= "var rem=\"\";\n";
          $this->salida .= "  function abrirVentana(){\n";
          $this->salida .= "    var car='';\n";
          $this->salida .= "    car=document.newcargo.TipoCargo.value;\n";
          $this->salida .= "    if(car==-1){\n";
          $this->salida .= "      alert('Debe elegir el tipo del Cargo.');\n";
          $this->salida .= "    }\n";
          $this->salida .= "    else{\n";
          $this->salida .= "      document.newcargo.TipoCargo.value=car;\n";
          $this->salida .= "    	var nombre='';\n";
          $this->salida .= "      var url2='';\n";
          $this->salida .= "      var str='';\n";
          $this->salida .= "      var ALTO=screen.height;\n";
          $this->salida .= "      var ANCHO=screen.width;\n";
          $this->salida .= "      nombre=\"buscador_General\";\n";
          $this->salida .= "      str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
          $this->salida .= "      url2 ='".$_ROOT."classes/classbuscador/buscador.php?tipo=InsertarCargo&forma=newcargo&sql='+car+'&plan='+'$PlanId';\n";
          $this->salida .= "      rem = window.open(url2, nombre, str);\n";
          $this->salida .= "    }\n";
          $this->salida .= "  }\n";
          $this->salida .= " function buscadorcuenta(){\n";
          $this->salida .= "  setTimeout('buscadorcuenta()', 1000);";
          $this->salida .= "  document.newcargo.ValorNo.value=0;\n";
          $this->salida .= "  document.newcargo.ValorCubierto.value=0;\n";
          $this->salida .= "  document.newcargo.ValorPac.value=0;\n";
          $this->salida .= "  document.newcargo.ValorEmp.value=0;\n";
          $this->salida .= "  var PorPaciente=$PorPaciente;";
          $this->salida .= "  var Maximo=$Maximo;";
          $this->salida .= "  var Minimo=$Minimo;";
          $this->salida .= "  var SW=$sw;";
          $this->salida .= "  Precio=document.newcargo.Precio.value;\n";
          $this->salida .= "  Cobertura=document.newcargo.Cobertura.value;\n";
          $this->salida .= "  Gravamen=document.newcargo.Gravamen.value;\n";
          $this->salida .= "  Porcentaje=document.newcargo.Porcentaje.value;\n";
          $this->salida .= "  Por=Number(Precio)*Number(Porcentaje/100);\n";
          $this->salida .= "  Precio=Number(Precio)+Number(Por);\n";
          $this->salida .= "  ValCubierto=Number(Precio) * Number(Cobertura/100);\n";
          $this->salida .= "  ValPaciente=Number(Precio) * Number(PorPaciente/100);\n";
          $this->salida .= "  if(eval(ValPaciente) > eval(Maximo) && eval(Precio)){\n";
          $this->salida .= "     ValPaciente=Maximo;\n";
          $this->salida .= "  }\n";
          $this->salida .= "  if(eval(ValPaciente) < eval(Minimo) && eval(Precio)){\n";
          $this->salida .= "     ValPaciente=Minimo;\n";
          $this->salida .= "  }\n";
          $this->salida .= "  ValNo=0;\n";
          $this->salida .= "  ValNo=Number(Precio)-Number(ValCubierto);\n";
          $this->salida .= "  if(Cobertura==100){\n";
          $this->salida .= "     ValNo=0;\n";
          $this->salida .= "     ValPaciente=0;\n";
          $this->salida .= "  }\n";
          $this->salida .= "  var c=Number(ValCubierto)-Number(ValPaciente);\n";
          $this->salida .= "  if(SW==0){\n";
          $this->salida .= "    GravEmp=Number(ValCubierto)*Number(Gravamen/100);\n";
          $this->salida .= "    GravPac=Number(ValNo)*Number(Gravamen/100);\n";
          $this->salida .= "  }\n";
          $this->salida .= "  else{\n";
          $this->salida .= "    GravEmp=Number(c)*Number(Gravamen/100);\n";
          $this->salida .= "    GravPac=(Number(ValCubierto)+Number(ValPaciente))*Number(Gravamen/100);\n";
          $this->salida .= "  }\n";
          $this->salida .= "  Grav=GravEmp+GravPac;\n";
          $this->salida .= "  document.newcargo.ValorCubierto.value=ValCubierto;\n";
          $this->salida .= "  document.newcargo.ValorNo.value=ValNo;\n";
          $this->salida .= "  document.newcargo.ValorPac.value=ValPaciente;\n";
          $this->salida .= "  document.newcargo.ValorEmp.value=c;\n";
          $this->salida .= "  document.newcargo.Gravamen.value=Gravamen;\n";
          $this->salida .= "  document.newcargo.ValorGrav.value=Grav;\n";
          $this->salida .= "  ValNo='';\n";
          $this->salida .= "  ValPaciente='';\n";
          $this->salida .= "  ValCubierto='';\n";
          $this->salida .= "  Cargo='';\n";
          $this->salida .= "  Gravamen=0;\n";
          $this->salida .= "  Cobertura='';\n";
          $this->salida .= "  Porcentaje='';\n";
          $this->salida .= "  c='';\n";
          $this->salida .= "  Grav='';\n";
          $this->salida .= "  SubGrupo='';\n";
          $this->salida .= "  Grupo='';\n";
          $this->salida .= "  Tarifario='';\n";
          $this->salida .= "  Descripcion='';\n";
		$this->salida .= " }\n";
          $this->salida .= " setTimeout('buscadorcuenta()',1000);\n";
          $this->salida .= "</script>\n";
          if($D){
                    $accion=ModuloGetURL('app','EstacionEnfermeriaCargos','user','ModificarCargoTmp',array('Transaccion'=>$D[transaccion],'Ayuda'=>$Ayuda));
                    $Boton='MODIFICAR CARGO';
                    $Modi=true;
          }
          else {
                    $accion=ModuloGetURL('app','EstacionEnfermeriaCargos','user','InsertarCargoTmp', array('PorPaciente'=>$PorPaciente,'Maximo'=>$Maximo,'Minimo'=>$Minimo,'Departamento'=>$dpto,'estacion'=>$estacion));
                    $Boton='AGREGAR CARGO';
          }
          $this->salida .= " <form name=\"newcargo\" action=\"$accion\" method=\"post\">";

          $FechaCargo=date("d/m/Y");
          if($D)
          {
                    $Cobertura=($D[valor_cubierto]/$D[valor_cargo])*100;
                    $Dpto=$D[departamento];
                    $x=$this->BuscarNombreCargo($D[tarifario_id],$D[cargo]);
                    $Descripcion=$x[0];
                    $FechaCargo=$this->FechaStamp($D[fecha_cargo]);
                    if(!$FechaCargo)
                    {   $FechaCargo=$this->FechaStamp($D[fecha_registro]);   }
                    $Cant=round($D[cantidad]);
                    $Gravamen=$D[gravamen_valor_nocubierto]+$D[gravamen_valor_cubierto];
                    $ValEmp=$D[valor_cubierto];
          }
          else
          {
                    $Dpto=$dpto;
                    $Descripcion='';
                    $Cant=1;
          }
          $this->salida .= " <table border=\"0\" width=\"90%\" align=\"center\"  class=\"normal_10\">";
          $this->salida .= "   <tr><td><fieldset><legend class=\"field\">AGREGAR CARGO</legend>";
          $this->salida .= "     <table height=\"74\" border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"TarifarioId\" value=\"".$D[tarifario_id]."\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"GrupoTarifario\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"SubGrupoTarifario\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Gravamen\" value=\"$Gravamen\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"PlanId\" value=\"$PlanId\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Nivel\" value=\"$Nivel\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Ingreso\" value=\"$Ingreso\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Fecha\" value=\"$Fecha\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Cuenta\" value=\"$Cuenta\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Cobertura\" value=\"$Cobertura\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"ValorCubierto\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Consecutivo\" value=\"".$D[consecutivo]."\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"ValorCargo\" value=\"".$D[valor_cargo]."\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Cons\" value=\"".$D[1]."\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Porcentaje\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Swcantidad\">";
          $this->salida .= "       <tr>";
          $this->salida .= "         <td class=\"label\" width=\"18%\" >DEPARTAMENTO: </td>";
          $x=$this->BuscarNombreDpto($dpto);
          $this->salida .= "	       <td>$x</td>";
          $this->salida .= "       <td>&nbsp;</td>";
          $this->salida .= "       <td class=\"".$this->SetStyle("Cargo")."\">CARGO: </td>";
          if($Modi){
                    $this->salida .= "   <td><input type=\"text\" class=\"input-text\" name=\"Cargo\" size=\"10\" value=\"".$D[cargo]."\" readonly></td>";
                    $this->salida .= "   <td colspan=\"2\"></td>";
          }else
          {
                    $this->salida .= "   <td><input type=\"text\" class=\"input-text\" name=\"Cargo\" size=\"10\" value=\"".$D[cargo]."\"></td>";
                    $this->salida .= "       <td>&nbsp;</td>";
                    $this->salida .= "             		<td colspan=\"2\"><select name=\"TipoCargo\" class=\"select\">";
                    $this->salida .= " 										<option value=\"-1\">----------SELECCIONE----------</option>";
                    $this->salida .= " 										<option value=\"\">TODOS LOS CARGOS</option>";
                    $tipo=$this->TiposSolicitud();
                    for($i=0; $i<sizeof($tipo); $i++)
                    {
                              $this->salida .= " 										<option value=\"".$tipo[$i][grupo_tipo_cargo]."\">".$tipo[$i][descripcion]."</option>";
                    }
                    $this->salida .= "             		</select></td>";
                    $this->salida .= "   <td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrirVentana()></td>";
          }
          $this->salida .= "              </tr>";
          $this->salida .= "              <tr>";
          $this->salida .= "                <td class=\"label\">DESCRIPCION: </td>";
          $this->salida .= "                <td><textarea cols=\"34\" rows=\"3\" class=\"textarea\"name=\"Descripcion\" readonly>$Descripcion</textarea></td>";
          $this->salida .= "                <td>&nbsp;</td>";
          $this->salida .= "                <td class=\"label\">PRECIO: </td>";
          $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Precio\" size=\"10\" value=\"".$D[precio]."\" readonly></td>";
          $this->salida .= "                <td>&nbsp;</td>";
          $this->salida .= "                <td class=\"".$this->SetStyle("Cantidad")."\">CANTIDAD: </td>";
          $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Cantidad\" size=\"5\" value=\"$Cant\"></td>";
          $this->salida .= "              </tr>";
          $this->salida .= "              <tr>";
          $this->salida .= "                <td class=\"label\">VAL. NO CUBIERTO: </td>";
          $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"ValorNo\" size=\"10\" value=\"".FormatoValor($D[valor_nocubierto])."\" readonly></td>";
          $this->salida .= "                <td>&nbsp;</td>";
          $this->salida .= "                <td class=\"label\">VAL. PACIENTE: </td>";
          $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"ValorPac\" size=\"10\" value=\"".FormatoValor($D[valor_cuota_paciente])."\" readonly></td>";
          $this->salida .= "                <td>&nbsp;</td>";
          $this->salida .= "                <td class=\"label\">VAL EMPRESA: </td>";
          $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"ValorEmp\" size=\"10\" value=\"".FormatoValor($ValEmp)."\" readonly></td>";
          $this->salida .= "              </tr>";
          $this->salida .= "              <tr>";
          $this->salida .= "                <td class=\"label\">GRAVAMEN: </td>";
          $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"ValorGrav\" size=\"10\" value=\"".FormatoValor($Gravamen)."\" readonly></td>";
          $this->salida .= "                <td>&nbsp;</td>";
          $this->salida .= "                <td class=\"".$this->SetStyle("FechaCargo")."\">FECHA CARGO: </td>";
          $this->salida .= "	  	          <td colspan=\"4\"><input type=\"text\" name=\"FechaCargo\" value=\"$FechaCargo\" size=\"10\" class=\"input-text\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">&nbsp;&nbsp;";
          $this->salida .= 	ReturnOpenCalendario('newcargo','FechaCargo','/')."</td>";
          $this->salida .= "		          </tr>";
          $this->salida .= "			       </table>";
          $this->salida .= "		      </fieldset></td></tr></table>";
          $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
          $this->salida .= "	  <tr align=\"center\">";
          $this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"$Boton\"></td>";
          $this->salida .= "    </form>";
          $accionEliminarTodos=ModuloGetURL('app','EstacionEnfermeriaCargos','user','EliminarTodosCargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $this->salida .= "    <form name=\"formaborrar\" action=\"$accionEliminarTodos\" method=\"post\">";
          $this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ELIMINAR TODOS LOS CARGOS\"></td>";
          $this->salida .= "    </form>";
          $accionGuardarTodos=ModuloGetURL('app','EstacionEnfermeriaCargos','user','GuardarTodosCargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $this->salida .= "    <form name=\"formaguardar\" action=\"$accionGuardarTodos\" method=\"post\">";
          $this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"GUARDAR TODOS LOS CARGOS\"></td>";
          $this->salida .= "    </form>";
          $accionCancelar=ModuloGetURL('app','EstacionEnfermeriaCargos','user','EliminarTodosCargos',array('Cuenta'=>$Cuenta,'estacion'=>$_SESSION['CUENTAS']['E']['ESTACION']));
          $this->salida .= "    <form name=\"formaguardar\" action=\"$accionCancelar\" method=\"post\">";
          $this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td>";
          $this->salida .= "    </form>";
          $this->salida .= "	  </tr>";
          $this->salida .= "	  </table><br>";
          $this->salida .= ThemeCerrarTabla();
          return true;
	}

	function FormaMensaje($mensaje,$titulo,$accion,$boton)
	{
          $this->salida .= ThemeAbrirTabla($titulo);
          $this->salida .= "			      <table width=\"60%\" align=\"center\" >";
          $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "				       <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
          if($boton){
               $this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
          }
       	else{
               $this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
       	}
          $this->salida .= "			     </form>";
          $this->salida .= "			     </table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }


     function Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta)
     {
          $datos=$this->CuentaParticular($Cuenta,$PlanId); 
          if(!$datos)
          {
                    $datos=$this->BuscarPlanes($PlanId,$Ingreso);
                    $Responsable=$datos[nombre_tercero];
                    $ident=$datos[tipo_id_tercero].' '.$datos[tercero_id];
          }
          $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
          $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
          $Fecha1=$this->FechaStamp($Fecha);
          $Hora=$this->HoraStamp($Fecha);
          $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\" >";
          $this->salida .= "		<tr>";
          $this->salida .= "		   <td width=\"45%\">";
          $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\" >";
          $this->salida .= "            <tr><td><fieldset><legend class=\"field\">RESPONSABLE</legend>";
          $this->salida .= "              <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "                <tr><td class=\"label\" width=\"24%\">RESPONSABLE: </td><td>$Responsable</td></tr>";
          $this->salida .= "                <tr><td class=\"label\" width=\"24%\">IDENTIFICACION: </td><td>".$ident."</td></tr>";
          $this->salida .= "                <tr><td class=\"label\" width=\"24%\">PLAN: </td><td>".$datos[plan_descripcion]."</td></tr>";
          $this->salida .= "                <tr><td class=\"label\" width=\"24%\">NIVEL: </td><td>$Nivel</td></tr>";
          if(!empty($datos[protocolos]))
          {
                    if(file_exists("protocolos/".$datos[protocolos].""))
                    {
                              $Protocolo=$datos[protocolos];
                              $this->salida .= "<script>";
                              $this->salida .= "function Protocolo(valor){";
                              $this->salida .= "window.open('protocolos/'+valor,'PROTOCOLO','');";
                              $this->salida .= "}";
                              $this->salida .= "</script>";
                              $accion="javascript:Protocolo('$datos[protocolos]')";
                              $this->salida .= "                <tr><td class=\"label\" width=\"24%\">PROTOCOLO: </td><td><a href=\"$accion\">$Protocolo</a></td></tr>";
                    }
          }
          /*if(!empty($argu))
          {
                    $accion=ModuloGetURL('app','Facturacion','user','VerAutorizaciones',$argu);
                    $this->salida .= "<tr><td class=\"label\">AUTORIZACIONES: </td> ";
                    $this->salida .= "<td align=\"left\"><a href=\"$accion\">Ver Autorizaciones Plan</a></td></tr> ";
          }*/

          $this->salida .= "			       </table>";
          $this->salida .= "		      </fieldset></td></tr></table>";
          $this->salida .= "		   </td>";
          $this->salida .= "		   <td>";
          $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida .= "            <tr><td><fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>";
          $this->salida .= "              <table border=\"0\" width=\"97%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "                <tr><td class=\"label\" width=\"35%\">PACIENTE: </td><td>$Nombres $Apellidos</td></tr>";
          $this->salida .= "                <tr><td class=\"label\">IDENTIFICACION: </td><td>$TipoId  $PacienteId</td></tr>";
          $this->salida .= "                <tr><td class=\"label\">No. INGRESO: </td><td>$Ingreso</td></tr>";
          //$this->salida .= "                <tr><td class=\"label\">FECHA APERTURA: </td><td>$Fecha1</td></tr>";
          //$this->salida .= "                <tr><td class=\"label\">HORA APERTURA: </td><td>$Hora</td></tr>";
          $this->salida .= "			        </table>";
          $this->salida .= "		      </fieldset></td></tr></table>";
          $this->salida .= "		   </td>";
          $this->salida .= "		</tr>";
          $this->salida .= "	</table>";
     }

     function FechaStamp($fecha)
     {
	     if($fecha){
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
          //	return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
               return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
          }
     }


 /**
  * Se encarga de separar la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/
	function HoraStamp($hora)
	{
		$hor = strtok ($hora," ");
		for($l=0;$l<4;$l++)
		{
			$time[$l]=$hor;
			$hor = strtok (":");
		}
		return  $time[1].":".$time[2].":".$time[3];
	}


	/**
	*
	*/
	function FormaBodegas($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId)
	{ 
          $this->salida .= ThemeAbrirTabla('ELEGIR BODEGAS DE INSUMOS O MEDICAMENTOS');
          $this->salida .= "			      <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= "				       <tr>";
          $tipo=$this->Bodegas();
          if(empty($tipo))
          {	$this->salida .= "       <td class=\"label_error\" colspan=\"2\" align=\"center\">LA ESTACION NO TIENE BODEGAS ASOCIADAS</td>";  }
          else
          {
               $accion=ModuloGetURL('app','EstacionEnfermeriaCargos','user','BodegaInsumos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId));
               $this->salida .= "    <form name=\"formaborrar\" action=\"$accion\" method=\"post\">";
               $this->salida .= "       <td class=\"label\">BODEGAS: </td>";
               $this->salida .= "             		<td colspan=\"2\"><select name=\"Bodegas\" class=\"select\">";
               $this->salida .= " 										<option value=\"-1\">----------BODEGAS----------</option>";
               for($i=0; $i<sizeof($tipo); $i++)
               {
                         $this->salida .= " 										<option value=\"".$tipo[$i][bodega].",".$tipo[$i][empresa_id].",".$tipo[$i][centro_utilidad]."\">".$tipo[$i][descripcion]."</option>";
               }
               $this->salida .= "             		</select></td>";
          }
          $this->salida .= "				       </tr>";
          $this->salida .= "			     </table>";
          $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
          $this->salida .= "	  <tr align=\"center\">";
          if(!empty($tipo))
          {
               $this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"ACEPTAR\"></td>";
               $this->salida .= "    </form>";
          }
          $accionCancelar = ModuloGetURL('app','EstacionEnfermeriaCargos','user','main',array("estacion"=>$_SESSION['CUENTAS']['E']['DATOS'],'tipoa'=>2));
          $this->salida .= "    <form name=\"formaborrar\" action=\"$accionCancelar\" method=\"post\">";
          $this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CANCELAR\"></td>";
          $this->salida .= "    </form>";
          $this->salida .= "	  </tr>";
          $this->salida .= " </table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
	}


/**
  * Muestra los cargos que inserto con sus totales y la opcion de insertar un nuevo cargo.
	* @access private
	* @return boolean
	* @param int numero de la cuenta
	* @param string tipo documento
	* @param int numero documento
	* @param string nivel
	* @param string plan_id
	* @param int ingreso
	* @param date fecha de la cuenta
	*/
	function	FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D)
	{
     IncludeLib("tarifario");
     $dpto=$_SESSION['CUENTAS']['E']['DEPTO'];
     $Ingreso=$_SESSION['CUENTAS']['E']['INGRESO'];
     $TipoId=$_SESSION['CUENTAS']['E']['tipo_id_paciente'];
     $PacienteId=$_SESSION['CUENTAS']['E']['paciente_id'];
     $Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
     $Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
     $this->salida .= ThemeAbrirTabla('INSUMOS - AGREGAR CARGO A LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);
     //$this->EncabezadoEmpresa($Caja);
     $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
     $this->Encabezado($PlanId,$TipoId,$PacienteId,$_SESSION['CUENTAS']['E']['INGRESO'],$Nivel,$Fecha,$argu,$Cuenta);
     $datos=$this->DatosTmpInsumos($Cuenta);
     $this->salida .= "			      <table width=\"50%\" align=\"center\" border=\"0\">";
     $this->salida .= $this->SetStyle("MensajeError");
     $this->salida .= "			     </table>";
     if(!empty($datos) AND empty($D))
     {
          $this->salida .= " <table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" >";
          $this->salida .= "	  <tr align=\"center\" class=\"modulo_table_list_title\">";
          $this->salida .= "	      <td>DEPARTAMENTO</td>";
          $this->salida .= "	      <td>COD. PRODUCTO</td>";
          $this->salida .= "	      <td>DESCRIPCION</td>";
          $this->salida .= "	      <td>BODEGA</td>";
          $this->salida .= "	      <td>PRECIO</td>";
          $this->salida .= "	      <td>CANT.</td>";
          $this->salida .= "	      <td></td>";
          $this->salida .= "	      <td></td>";
          $this->salida .= "	  </tr>";
          for($i=0; $i<sizeof($datos);$i++)
          {
               if( $i % 2) $estilo='modulo_list_claro';
               else $estilo='modulo_list_oscuro';

                    $this->salida .= "	  <tr class=\"$estilo\" align=\"center\">";
                    $this->salida .= "	      <td>".$datos[$i][desdpto]."</td>";
                    $this->salida .= "	      <td>".$datos[$i][codigo_producto]."</td>";
                    $this->salida .= "	      <td>".$datos[$i][descripcion]."</td>";
                    $this->salida .= "	      <td>".$datos[$i][desbodega]."</td>";
                    $this->salida .= "	      <td>".$datos[$i][precio]."</td>";
                    $this->salida .= "	      <td>".FormatoValor($datos[$i][cantidad])."</td>";
                    $accionModificar=ModuloGetURL('app','EstacionEnfermeriaCargos','user','LlamaFormaModificarCargoTmpIyM',array('ID'=>$Datos[$i][tmp_cuenta_insumos_id],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Datos'=>$datos[$i]));
                    $this->salida .= "	      <td><a href=\"$accionModificar\" alt=\"Modificar los cargos del paciente\">MODI</a></td>";
                    $accionEliminar=ModuloGetURL('app','EstacionEnfermeriaCargos','user','EliminarCargoTmpIyM',array('ID'=>$datos[$i][tmp_cuenta_insumos_id],'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
                    $this->salida .= "	      <td><a href=\"$accionEliminar\">ELIM</a></td>";
                    $this->salida .= "	  </tr>";
	          }
	          $this->salida .= " </table>";
          }
          if(!empty($D))
          {
               $bod[0]=$d[bodega];
          }
          $bod=explode(',',$_SESSION['CUENTA']['E']['BODEGA']);
          global $_ROOT;
          $sw=ModuloGetVar('app','Facturacion','sw_gravar_cuota_paciente');
          $this->salida .= "\n<script>\n";
          $this->salida .= "var rem=\"\";\n";
          $this->salida .= "  function abrirVentana(){\n";
          $this->salida .= "    var dpto='';\n";
		//$this->salida .= "    dpto=document.newcargo.Departamento.value;\n";
          $this->salida .= "    var bodega='';\n";
    		$this->salida .= "    bodega=document.newcargo.Bodegas.value;\n";
       	$this->salida .= "    if(bodega==-1){\n";
          $this->salida .= "      alert('Debe elegir la Bodega.');\n";
          $this->salida .= "    }\n";
       	$this->salida .= "    else{\n";
          $this->salida .= "    	var nombre='';\n";
          $this->salida .= "      var url2='';\n";
          $this->salida .= "      var str='';\n";
          $this->salida .= "      var ALTO=screen.height;\n";
          $this->salida .= "      var ANCHO=screen.width;\n";
          $this->salida .= "      nombre=\"buscador_General\";\n";
          $this->salida .= "      str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
          $this->salida .= "      url2 ='".$_ROOT."classes/classbuscador/buscador.php?tipo=InsertarInsumos&forma=newcargo&plan='+'$PlanId'+'&Empresa='+'$bod[1]'+'&CU='+'$bod[2]'+'&Bodega='+bodega;\n";
          $this->salida .= "      rem = window.open(url2, nombre, str);\n";
          $this->salida .= "    }\n";
          $this->salida .= "  }\n";
          $this->salida .= "</script>\n";
          if($D){
                    $accion=ModuloGetURL('app','EstacionEnfermeriaCargos','user','ModificarCargoTmpIyM',array('id'=>$D[tmp_cuenta_insumos_id],'Datos'=>$D));
                    $Boton='MODIFICAR CARGO';
                    $Modi=true;
          }
          else {
                    $accion=ModuloGetURL('app','EstacionEnfermeriaCargos','user','InsertarInsumos');
                    $Boton='AGREGAR CARGO';
          }
          $this->salida .= " <form name=\"newcargo\" action=\"$accion\" method=\"post\">";
          $FechaCargo=date("d/m/Y");
          $Dpto=$this->Departamento;
          $Descripcion='';
          $Cant=1;
          $this->salida .= " <table border=\"0\" width=\"90%\" align=\"center\"  class=\"normal_10\">";
          $this->salida .= "   <tr><td><fieldset><legend class=\"field\">AGREGAR CARGO</legend>";
          $this->salida .= "     <table height=\"74\" border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"PlanId\" value=\"$PlanId\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Nivel\" value=\"$Nivel\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Ingreso\" value=\"$Ingreso\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Fecha\" value=\"$Fecha\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Cuenta\" value=\"$Cuenta\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Cobertura\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"EmpresaId\" value=\"$bod[1]\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"CU\" value=\"$bod[2]\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"Bodegas\" value=\"$bod[0]\">";
          $this->salida .= "	            <input type=\"hidden\" name=\"CantMax\">";
          $this->salida .= "       <tr>";
          $this->salida .= "         <td class=\"label\" width=\"18%\" >DEPARTAMENTO: </td>";
          $x=$this->BuscarNombreDpto($dpto);
          $this->salida .= "	       <td>$x</td>";
          $this->salida .= "       <td>&nbsp;</td>";
          $this->salida .= "       <td class=\"".$this->SetStyle("Codigo")."\">COD. PROD: </td>";
          $this->salida .= "   <td><input type=\"text\" class=\"input-text\" name=\"Codigo\" size=\"12\" value=\"".$D[codigo_producto]."\" ></td>";
          $this->salida .= "       <td>&nbsp;</td>";
          $bode=$this->NombreBodega($bod[0]);
          $this->salida .= "             		<td colspan=\"2\" class=\"label\">BODEGA:  ".$bode[descripcion]."</td>";
          $this->salida .= "   <td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrirVentana()></td>";
          $this->salida .= "              </tr>";
          $this->salida .= "              <tr>";
          $this->salida .= "                <td class=\"label\">DESCRIPCION: </td>";
          $this->salida .= "                <td><textarea cols=\"35\" rows=\"3\" class=\"textarea\"name=\"Descripcion\" readonly>".$D[descripcion]."</textarea></td>";
          $this->salida .= "                <td>&nbsp;</td>";
          $this->salida .= "                <td class=\"label\">PRECIO: </td>";
          $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Precio\" size=\"10\" value=\"".$D[precio]."\" readonly></td>";
          $this->salida .= "                <td>&nbsp;</td>";
          $this->salida .= "                <td class=\"".$this->SetStyle("Cantidad")."\">CANTIDAD: </td>";
          $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Cantidad\" size=\"5\" value=\"$Cant\"></td>";
          $this->salida .= "              </tr>";
          $this->salida .= "              <tr>";
          $this->salida .= "                <td class=\"label\">GRAVAMEN %: </td>";
          $this->salida .= "                <td><input type=\"text\" class=\"input-text\" name=\"Gravamen\" size=\"10\" value=\"".FormatoValor($Gravamen)."\" readonly></td>";
          $this->salida .= "                <td>&nbsp;</td>";
          $this->salida .= "                <td class=\"".$this->SetStyle("FechaCargo")."\">FECHA CARGO: </td>";
          $this->salida .= "	  	          <td colspan=\"4\"><input type=\"text\" name=\"FechaCargo\" value=\"$FechaCargo\" size=\"10\" class=\"input-text\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">&nbsp;&nbsp;";
          $this->salida .= 	ReturnOpenCalendario('newcargo','FechaCargo','/')."</td>";
          $this->salida .= "		          </tr>";
          $this->salida .= "			       </table>";
          $this->salida .= "		      </fieldset></td></tr></table>";
          $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
          $this->salida .= "	  <tr align=\"center\">";
          $this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"$Boton\"></td>";
          $this->salida .= "    </form>";
          $accionEliminarTodos=ModuloGetURL('app','EstacionEnfermeriaCargos','user','EliminarTodosCargosIyM',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $this->salida .= "    <form name=\"formaborrar\" action=\"$accionEliminarTodos\" method=\"post\">";
          $this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ELIMINAR TODOS LOS CARGOS\"></td>";
          $this->salida .= "    </form>";
          $accionGuardarTodos=ModuloGetURL('app','EstacionEnfermeriaCargos','user','GuardarTodosCargosIyM',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $this->salida .= "    <form name=\"formaguardar\" action=\"$accionGuardarTodos\" method=\"post\">";
          $this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"GUARDAR TODOS LOS CARGOS\"></td>";
          $this->salida .= "    </form>";
          $accionCancelar=ModuloGetURL('app','EstacionEnfermeriaCargos','user','EliminarTodosCargosIyM',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $this->salida .= "    <form name=\"formaguardar\" action=\"$accionCancelar\" method=\"post\">";
          $this->salida .= "	  	<td><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td>";
          $this->salida .= "    </form>";
          $this->salida .= "	  </tr>";
          $this->salida .= "	  </table><br>";
          $this->salida .= ThemeCerrarTabla();
          return true;
	}

 /**
	* Se utilizada listar en el combo los diferentes tipo de departamentos de la clinica.
	* @access private
	* @return void
	*/
	function BuscarDepartamento($departamento,$d=false,$Dpto)
	{
          if(!$d){
               $this->salida .=" <option value=\"-1\" selected>--TODOS--</option>";
          }
          foreach($departamento as $value=>$titulo)
          {
               if($value==$Dpto){
                         $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
               }
               else {
                         $this->salida .=" <option value=\"$value\" >$titulo</option>";
               }
          }
	}

//----------------------------------------------------------------------------------

}//fin class
?>
