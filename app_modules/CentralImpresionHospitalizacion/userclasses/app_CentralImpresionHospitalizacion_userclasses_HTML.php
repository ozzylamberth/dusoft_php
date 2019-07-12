<?php

/**
 * $Id: app_CentralImpresionHospitalizacion_userclasses_HTML.php,v 1.4 2010/06/15 14:19:33 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de autorizaciones
 */

class app_CentralImpresionHospitalizacion_userclasses_HTML extends app_CentralImpresionHospitalizacion_user
{
    //Constructor de la clase app_Os_ListaTrabajo_userclasses_HTML
		function app_CentralImpresionHospitalizacion_userclasses_HTML()
		{
								$this->salida='';
								$this->app_CentralImpresionHospitalizacion_user();
								return true;
		}


		function SetStyle($campo)
		{
				if ($this->frmError[$campo] || $campo=="MensajeError")
				{
						if ($campo=="MensajeError")
						{
								$arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
								return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
						}
						return ("label_error");
				}
				return ("label");
		}

		/**
		*
		*/
		function FormaBuscar($arr)
		{
      $this->salida.= ThemeAbrirTabla('BUSQUEDA DE PACIENTES - [ '.$_SESSION['CENTRALHOSP']['NOM_EST'].' ]');
      $accion=ModuloGetURL('app','CentralImpresionHospitalizacion','user','Buscar');
      $this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
      $this->salida .= "<tr class=\"modulo_table_list_title\">";
      $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
      $this->salida .= "</tr>";
      $this->salida .= "<tr class=\"modulo_list_claro\" >";
      $this->salida .= "<td width=\"40%\" >";
      $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
      $this->salida .= "<tr><td>";
      $this->salida .= "<table width=\"60%\" align=\"center\" border=\"0\">";
      $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
      $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
      $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
      $tipos=$this->TiposId();
			for($i=0; $i<sizeof($tipos); $i++)
			{
					$this->salida .=" <option value=\"".$tipos[$i][tipo_id_paciente]."\">".$tipos[$i][descripcion]."</option>";
			}
      $this->salida .= "</select></td></tr>";
      $this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
      $this->salida .= "<tr><td class=\"label\">NOMBRES: </td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\"></td></tr>";
      $this->salida .= "<tr><td class=\"label\">No. INGRESO: </td><td><input type=\"text\" class=\"input-text\" name=\"Ingreso\"></td></tr>";

			//$this->PacienteUrgencias();

      $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
      $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
      $this->salida .= "</form>";
			 $accion=ModuloGetURL('app','CentralImpresionHospitalizacion','user','main');
      $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
      $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
      $this->salida .= "</tr>";
      $this->salida .= "</table></td></tr>";
      $this->salida .= "</td></tr></table>";
      $this->salida .= "</table>";
      $this->salida .= "</td>";
      $this->salida .= "    </tr>";
      $this->salida .= "  </table>";
      //mensaje
      $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "  </table>";
			if(!empty($arr))
			{
					$this->salida .= "<br><table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
					$this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
					$this->salida .= "        <td>IDENTIFICACION</td>";
					$this->salida .= "        <td>PACIENTE</td>";
					$this->salida .= "        <td></td>";
					$this->salida .= "      </tr>";
					for($i=0; $i<sizeof($arr); $i++)
					{
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida .= "      <tr class=\"$estilo\">";
								$this->salida .= "        <td align=\"center\">".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>";
								$this->salida .= "        <td>".$arr[$i][nombre]."</td>";
								$accionHRef=ModuloGetURL('app','CentralImpresionHospitalizacion','user','DetalleImpresion',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso],'nombre'=>$arr[$i][nombre]));
								$this->salida .= "        <td align=\"center\"><a href=\"$accionHRef\">VER</a></td>";
								$this->salida .= "      </tr>";
					}
					$this->salida .= "      </table>";
          $this->salida .=$this->RetornarBarra();
			}
      $this->salida .= ThemeCerrarTabla();
      return true;

		}


  	/**
  	*
  	*/
  	function FormaDetalleImpresion($datos='',$control='')
  	{
  		IncludeLib("funciones_central_impresion");

  		if(!empty($datos))
  		{
  			if($control==1)
  			{
  				$RUTA = $_ROOT ."cache/incapacidad_medica.pdf";
  			}
  			else if($control==2)
  			{
  				$RUTA = $_ROOT ."cache/solicitudes".UserGetUID().".pdf";
  			}
  			else if($control==3)
  			{
  				$RUTA = $_ROOT ."cache/ordenservicio".$datos['orden'].".pdf";
  			}
  			else
  			{
  				$RUTA = $_ROOT ."cache/formula_medica_amb".UserGetUID().".pdf";
  			}
  			$mostrar ="\n<script language='javascript'>\n";
  			$mostrar.="var rem=\"\";\n";
  			$mostrar.="  function abreVentana(){\n";
  			$mostrar.="    var nombre=\"\"\n";
  			$mostrar.="    var url2=\"\"\n";
  			$mostrar.="    var str=\"\"\n";
  			$mostrar.="    var ALTO=screen.height\n";
  			$mostrar.="    var ANCHO=screen.width\n";
  			$mostrar.="    var nombre=\"REPORTE\";\n";
  			$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
  			$mostrar.="    var url2 ='$RUTA';\n";
  			$mostrar.="    rem = window.open(url2, nombre, str)};\n";
  			$mostrar.="</script>\n";
  			$this->salida.="$mostrar";
  			$this->salida.="<BODY onload=abreVentana();>";
  		}
		 
			$this->salida.= ThemeAbrirTabla('DETALLE SOLICITUDES - [ '.$_SESSION['CENTRALHOSP']['NOM_EST'].' ]');
			$reporte= new GetReports();
			$this->EncabezadoPac();

        //formula medica hospitalizacion claudia
//			$vector1=GetMedicamentosIngreso($_SESSION['CENTRALHOSP']['PACIENTE']['ingreso']);
			$vector1= GetMedicamentosHospitalariosAmbulatorios($_SESSION['CENTRALHOSP']['PACIENTE']['ingreso']);
                        if (!empty($vector1))
			{
				$this->FrmMedicamentos($vector1,&$reporte);
			}

				//incapacidad de claudia
			$vec=Consulta_Incapacidades_GeneradasIngreso($_SESSION['CENTRALHOSP']['PACIENTE']['ingreso']);
			if(!empty($vec))
			{
				$this->FrmIncapacidad($vec,&$reporte);
			}

			$arr=BuscarSolicitudesIngreso($_SESSION['CENTRALHOSP']['PACIENTE']['ingreso']);
				//$arr=$this->BuscarDetalleSolcitudes($_SESSION['CENTRALHOSP']['PACIENTE']['ingreso']);
			if(!empty($arr))
			{
				$this->FormaSolicitudes($arr,&$reporte);
			}

                        $reserva_sangre= $this->Get_Info_RerservaSangre($_SESSION['CENTRALHOSP']['ingreso']);
			//print_r($_SESSION['CENTRALHOSP']);
                        $EdadPaciente= $this->EdadPaciente($_SESSION['CENTRALHOSP']['paciente_id'],$_SESSION['CENTRALHOSP']['tipo_id_paciente']);
                        $edad_paci=explode(":",$EdadPaciente);
	                //$this->salida .= "<pre>".print_r($EdadPaciente,true)."</pre>";
	                if(is_array($reserva_sangre))
                        {
                           $this->FormaReservaSangreT($reserva_sangre, $_SESSION['CENTRALHOSP']['ingreso'], $_SESSION['CENTRALHOSP']['paciente_id'], $_SESSION['CENTRALHOSP']['tipo_id_paciente'],$edad_paci[0],&$reporte);
                        }
                        $transfusion_sangre= $this->Get_Info_TransfusionSangre($_SESSION['CENTRALHOSP']['ingreso']);
	 	        if(is_array($transfusion_sangre))
     			{
                          $this->FormaTransfusionSanguinea($transfusion_sangre, $_SESSION['CENTRALHOSP']['ingreso'], $_SESSION['CENTRALHOSP']['paciente_id'], $_SESSION['CENTRALHOSP']['tipo_id_paciente'],$edad_paci[0],&$reporte);
                        }
                        $var='';
			$var=BuscarOrdenesIngreso($_SESSION['CENTRALHOSP']['PACIENTE']['ingreso']);
				//$var=$this->BuscarOrdenes($_SESSION['CENTRALHOSP']['PACIENTE']['ingreso']);
			if(!empty($var))
			{
				$_SESSION['CENTRALHOSP']['ARREGLO']['DETALLE2']=$var;
				$this->ListadoOsAuto('FormaDetalleImpresion',&$reporte);
			}
				
  		if(!empty($_SESSION['CENTRALHOSP']['RETORNO']))
  		{
  			$contenedor=$_SESSION['CENTRALHOSP']['RETORNO']['contenedor'];
  			$modulo=$_SESSION['CENTRALHOSP']['RETORNO']['modulo'];
  			$tipo=$_SESSION['CENTRALHOSP']['RETORNO']['tipo'];
  			$metodo=$_SESSION['CENTRALHOSP']['RETORNO']['metodo'];
  			$argumentos=$_SESSION['CENTRALHOSP']['RETORNO']['argumentos'];
 			}	
      else
 			{
 				$contenedor='app';
 				$modulo='CentralImpresionHospitalizacion';
 				$tipo='user';
 				$metodo='FormaBuscar';
 				$argumentos=array();
 			}
      
      $actionM = ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
      $this->salida .= "    <br> <table width=\"50%\" border=\"0\" align=\"center\">";
      $this->salida .= "               <tr>";
      $this->salida .= "             <form name=\"forma1\" action=\"$actionM\" method=\"post\">";
      $this->salida .= "                       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></td>";
      $this->salida .= "                       </form>";
      $this->salida .= "               </tr>";
      $this->salida .= "  </table>";
      unset($reporte);
      $this->salida .= ThemeCerrarTabla();
    
      return true;
    }
   /**
   * Funcion donde se crea un link para el reporte de la reserva de sangre
   *
   * @param array $ReserSangre los datos de la reserva de sangre
   * @param integer $ingreso Referencia al ingreso
   * @param character $paciente_id el id del paciente
   * @param character $tipo_id_paciente el tipo de documento de un paciente
   * @param character $edad_pac la edad del paciente
   * @param object $rep Objeto de la clase GetReports
   *
   * @return boolean
   */
    function FormaReservaSangreT($ReserSangre, $ingreso, $paciente_id, $tipo_id_paciente,$edad_pac,$rep)
    {
      $paciente['ingreso'] = $ingreso;
      $usuario=UserGetUID();
   
      $this->salida .= "  <br>\n";
      $this->salida .= "  <table align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"80%\" class=\"modulo_list_claro\">\n";
      $this->salida .= "    <tr align=\"center\" class=\"modulo_table_title\">\n";
      $this->salida .= "      <td width=\"50%\" colspan=\"2\"align=\"center\">RESERVA DE SANGRE</td>\n";
      //$this->salida .= "<pre>".print_r($ReserSangre,true)."</pre>";
      $nombre=$_SESSION['CENTRALHOSP']['nombre_paciente'];
      
      for($f=0;$f<sizeof($ReserSangre);$f++)
      {
         if( $i % 2) $estilo='modulo_list_claro';
            else $estilo='modulo_list_oscuro';

            $this->salida .= "<tr align=\"center\">";
            $this->salida .= "<td class=\"modulo_list_claro\"><b>IMPRIMIR RESERVA DE SANGRE ".$ReserSangre[$f]['solicitud_reserva_sangre_id']."</b></td>";
            $mostrar_resa = $rep->GetJavaReport('hc','ReservaSangre','ReservaSangre', array("ingreso"=>$ingreso,
										"tipoidpaciente"=>$tipo_id_paciente,
										"paciente"=>$paciente_id,
										"nombre_paciente"=>$nombre,
										"edad_paciente"=>$edad_pac,
										"nombre_tercero"=>$ReserSangre[$f]['nombre_tercero'],
										"tipo_id_tercero"=>$ReserSangre[$f]['tipo_id_tercero'],
										"tercero_id"=>$ReserSangre[$f]['tercero_id'],
										"solicitud_id"=>$ReserSangre[$f]['solicitud_reserva_sangre_id']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
            
            $funcion_resa = $rep->GetJavaFunction();
            $this->salida .= "      <td class=\"modulo_list_claro\">\n";
            $this->salida .= $mostrar_resa;
            $this->salida .= "        <a href=\"javascript:".$funcion_resa."\" class =\"label_error\">\n";
            $this->salida .= "          <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>INGRESO: ".$ingreso."\n";
            $this->salida .= "        </a>\n";
            $this->salida .= "      </td>\n";
            $this->salida .= "    </tr>\n";
       }
      $this->salida .= "  </table>\n";
      return true;
    }
    
   /**
   * Funcion donde se crea un link para el reporte de la transfusion de sangre
   *
   * @param array $Trans_San los datos de la transfusion de sangre
   * @param integer $ingreso Referencia al ingreso
   * @param character $paciente_id el id del paciente
   * @param character $tipo_id_paciente el tipo de documento de un paciente
   * @param character $edad_pac la edad del paciente
   * @param object $rep Objeto de la clase GetReports
   *
   * @return boolean
   */
    function FormaTransfusionSanguinea($Trans_San, $ingreso, $paciente_id, $tipo_id_paciente,$edad_pac,$rep)
    {
      $paciente['ingreso'] = $ingreso;
      $usuario=UserGetUID();
      $this->salida .= "  <br>\n";
      $this->salida .= "  <table align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"80%\" class=\"modulo_list_claro\">\n";
      $this->salida .= "    <tr align=\"center\" class=\"modulo_table_title\">\n";
      $this->salida .= "      <td width=\"50%\" colspan=\"2\"> TRANSFUSION SANGUINEA</td>\n";
      
      //$this->salida .= "<pre>".print_r($_SESSION['HC_EVOLUCION'][$usuario][$_SESSION['IMPRESIONHC']['EVOLUCION']][datosProfesional],true)."</pre>";  
      $nombre=$_SESSION['CENTRALHOSP']['nombre_paciente']; 
      for($f=0;$f<sizeof($Trans_San);$f++)
      {
         if( $i % 2) $estilo='modulo_list_claro';
            else $estilo='modulo_list_oscuro';

          //$this->salida .= "<pre>".print_r($Trans_San,true)."</pre>";
          $this->salida .= "<tr align=\"center\">";
          $this->salida .= "<td class=\"modulo_list_claro\"><b>IMPRIMIR TRANSFUSION SANGUINEA ".$Trans_San[$f]['fecha']."</b></td>";
          $mostrar1 = $rep->GetJavaReport('hc','TransfusionSanguinea','TransfusionSanguinea', array("ingreso"=>$ingreso,
												"tipoidpaciente"=>$tipo_id_paciente,
												"paciente"=>$paciente_id,
												"nombre_paciente"=>$nombre,
												"edad_paciente"=>$edad_pac,
												"nombre_tercero"=>$Trans_San[$f]['nombre_tercero'],
												"tipo_id_tercero"=>$Trans_San[$f]['tipo_id_tercero'],
												"tercero_id"=>$Trans_San[$f]['tercero_id'],
												"fecha_trans"=>$Trans_San[$f]['fecha']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      
          $funcion1 = $rep->GetJavaFunction();
          $this->salida .= "      <td class=\"modulo_list_claro\">\n";
          $this->salida .= $mostrar1;
          $this->salida .= "        <a href=\"javascript:".$funcion1."\" class =\"label_error\">\n";
          $this->salida .= "          <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>INGRESO: ".$ingreso."\n";
          $this->salida .= "        </a>\n";
          $this->salida .= "      </td>\n";
          $this->salida .= "    </tr>\n";
      }
      $this->salida .= "  </table>\n";
      return true;
    }


		function FormaSolicitudes($arr,$reporte)
		{
						unset($_SESSION['CENTRALHOSP']['ARR_SOLICITUDES']);
						IncludeLib("malla_validadora");
						$this->salida .= "         <br><table width=\"80%\" border=\"0\" align=\"center\">";
						$this->salida .= "            <tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\">SOLICITUDES</td></tr>";
						for($i=0; $i<sizeof($arr);)
						{
										$d=$i;
										if($arr[$i][plan_id]==$arr[$d][plan_id]
											AND $arr[$i][servicio]==$arr[$d][servicio])
										{
													$this->salida .= "            <tr><td colspan=\"5\" class=\"modulo_table_title\">PLAN:".$arr[$i][plan_descripcion]."</td></tr>";
													$this->salida .= "            <tr>";
													$this->salida .= "                <td class=\"modulo_table_title\" width=\"12%\">SERVICIO: </td>";
													$this->salida .= "                <td class=\"modulo_list_claro\" width=\"13%\">".$arr[$i][desserv]."</td>";
													$this->salida .= "                <td class=\"modulo_table_title\" width=\"11%\">DEPARTAMENTO: </td>";
													$this->salida .= "                <td class=\"modulo_list_claro\" align=\"left\" colspan=\"2\">".$arr[$i][despto]."</td>";
													$this->salida .= "            </tr>";
													$this->salida .= "            <tr class=\"modulo_table_title\">";
													$this->salida .= "                <td>FECHA</td>";
													$this->salida .= "                <td>CARGO</td>";
													$this->salida .= "                <td colspan=\"2\" width=\"50%\">DESCRIPCION</td>";
													$this->salida .= "                <td width=\"10%\">TIPO</td>";
													//$this->salida .= "                <td width=\"11%\">JUSTIF.</td>";
													$this->salida .= "            </tr>";
										}
										while($arr[$i][plan_id]==$arr[$d][plan_id]
										AND $arr[$i][servicio]==$arr[$d][servicio])
										{
														if($d % 2) {  $estilo="modulo_list_claro";  }
														else {  $estilo="modulo_list_oscuro";   }
														$this->salida .= "            <tr class=\"$estilo\">";
														$this->salida .= "                <td>".$this->FechaStamp($arr[$i][fecha])." ".$this->HoraStamp($arr[$i][fecha])."</td>";
														$this->salida .= "                <td align=\"center\">".$arr[$d][cargos]."</td>";
														$this->salida .= "                <td colspan=\"2\">".$arr[$d][descar]."</td>";
														$this->salida .= "                <td align=\"center\">".$arr[$d][desos]."</td>";
														$this->salida .= "            </tr>";

														$this->salida .= "            <tr class=\"$estilo\">";
														$this->salida .= "                <td width=\"11%\" class=\"modulo_table_title\" >JUSTIFICACION:</td>";
														$x=MallaValidadoraValidarCargo($arr[$d][cargos],$arr[$d][plan_id],$arr[$d][servicio],$arr[$d][hc_os_solicitud_id],$arr[$d][cantidad]);
														if(is_array($x))
														{  $this->salida .= "                <td align=\"center\" colspan=\"4\">CARGO VALIDADO POR LA MALLA</td>";  }
														else
														{  $this->salida .= "                <td align=\"center\" colspan=\"4\">$x</td>";  }
														$this->salida .= "            </tr>";
														$d++;
										}
										$i=$d;
						}
						//Variable de session que contiene el arreglo de las solicitudes para cuando se vayan a imprimir
						$_SESSION['CENTRALHOSP']['ARR_SOLICITUDES']=$arr;
						$go_to_url=ModuloGetURL('app','CentralImpresionHospitalizacion','user','Reportesolicitudes',array('pos'=>1));
						$this->salida .= "                <tr><td class=$estilo colspan=\"2\" align=\"center\" width=\"7%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"$go_to_url\"> IMPRIMIR POS</a></td>";
						//$reporte= new GetReports();
						$mostrar=$reporte->GetJavaReport('app','CentralImpresionHospitalizacion','solicitudesHTM',array('evolucion'=>'','ingreso'=>$_SESSION['CENTRALHOSP']['PACIENTE']['ingreso'],'TipoDocumento'=>$_SESSION['CENTRALHOSP']['PACIENTE']['tipo_id_paciente'],'Documento'=>$_SESSION['CENTRALHOSP']['PACIENTE']['paciente_id']),array('rpt_name'=>'solicitudesHTM','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
						$funcion=$reporte->GetJavaFunction();
						$this->salida .=$mostrar;
						$this->salida .= "                <td class=$estilo colspan=\"2\" align=\"center\" width=\"7%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"javascript:$funcion\"> IMPRIMIR </a></td>";

						$go_to_url=ModuloGetURL('app','CentralImpresionHospitalizacion','user','Reportesolicitudes',array('pos'=>0));
						$this->salida .= "                <td class=$estilo align=\"center\" width=\"7%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"$go_to_url\"> IMPRIMIR MEDIA CARTA</a></td></tr>";

						//unset($reporte);
						//$go_to_url=ModuloGetURL('app','CentralImpresionHospitalizacion','user','Reportesolicitudes',array('pos'=>0));
						//$this->salida .= "                <td class=$estilo colspan=\"2\" align=\"center\" width=\"7%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"$go_to_url\"> IMPRIMIR PDF</a></td></tr>";
						$this->salida .= " </table>";
		}


		function EncabezadoPac()
		{
				if(empty($_SESSION['CENTRALHOSP']['PACIENTE']))
				{
					$tipo_id_paciente=$_SESSION['CENTRALHOSP']['tipo_id_paciente'];
					$id_paciente=$_SESSION['CENTRALHOSP']['paciente_id'];
					$nom_paciente=$_SESSION['CENTRALHOSP']['nombre_paciente'];
				}else
				{
					$tipo_id_paciente=$_SESSION['CENTRALHOSP']['PACIENTE']['tipo_id_paciente'];
					$id_paciente=$_SESSION['CENTRALHOSP']['PACIENTE']['paciente_id'];
					$nom_paciente=$_SESSION['CENTRALHOSP']['PACIENTE']['nombre_paciente'];
				}
				$this->salida .= "<br><table  class=\"modulo_table_list_title\" border=\"0\"  width=\"80%\" align=\"center\" >";
				$this->salida .= " <tr class=\"modulo_table_list_title\">";
				$this->salida .= " <td  width=\"18%\">IDENTIFICACION</td>";
				$this->salida .= " <td>PACIENTE</td>";
				$this->salida .= " </tr>";
				$this->salida .= " <tr align=\"center\">";
				$this->salida .= " <td class=\"modulo_list_claro\" >".$tipo_id_paciente."&nbsp;".$id_paciente."</td>";
				$this->salida .= " <td class=\"modulo_list_claro\">".$nom_paciente."</td>";
				$this->salida .= " </tr>";
				$this->salida .= " </table>";
				return true;
		}
//------------------------NUEVO--------------------------------------------


  /**
  *
  */
  function FormaDetalleSolicitud($reporte)
  {
			$arr=$_SESSION['CENTRALHOSP']['ARREGLO']['DETALLE'];
			
			IncludeLib("malla_validadora");
			$reporte= new GetReports();
			$this->salida .= ThemeAbrirTabla('DETALLE SOLICITUDES - [ '.$_SESSION['CENTRALHOSP']['ESTACION'].' ]');
			$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "  </table>";
			$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "      <tr align=\"center\">";
			$this->salida .= "        <td colspan=\"8\">";
			$this->salida .= "     <table width=\"70%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr>";
			$this->salida .= "        <td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr>";
			if(!empty($arr))
			{
				$iden=$arr[0][tipo_id_paciente]." ".$arr[0][paciente_id];
				$nombre=$arr[0][nombres];
			}
			else
			{
				$iden=$_SESSION['CENTRALHOSP']['ARREGLO']['DETALLE2'][0][tipo_id_paciente]." ".$_SESSION['CENTRALHOSP']['ARREGLO']['DETALLE2'][0][paciente_id];
				$nombre=$_SESSION['CENTRALHOSP']['ARREGLO']['DETALLE2'][0][nombre];
				//Se asigna si es llamado desde Os_Atencion
				if($_SESSION['CENTRALHOSP']['RETORNO']['modulo']=='Os_Atencion')
				{
					$iden=$_SESSION['CENTRALHOSP']['RETORNO']['argumentos']['tipo_id']." ".$_SESSION['CENTRALHOSP']['RETORNO']['argumentos']['id'];
					$nombre=$_SESSION['CENTRALHOSP']['RETORNO']['argumentos']['nom'];
				}
			}
			$_SESSION['CENTRALHOSP']['nombre_paciente']=$nombre;
			$this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: </td><td width=\"20%\" class=\"modulo_list_claro\">".$iden."</td>";
			$this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">PACIENTE:</td><td width=\"40%\" class=\"modulo_list_claro\" colspan=\"3\">".$nombre."</td>";
			//$this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">INGRESO:</td><td width=\"60%\" class=\"modulo_list_claro\">".$arr[0][ingreso]."</td>";
			$this->salida .= "      </tr>";
			$this->salida .= "       </table>";
			$this->salida .= "        </td>";
			$this->salida .= "      </tr>";
      //links bd
      $plan=$this->Planes($_SESSION['CENTRALHOSP']['ingreso']);
					
      for($i=0; $i<sizeof($plan); $i++)
      {
          $p=$this->ClasificarPlan($plan[$i][plan_id]);
          if(($p[sw_tipo_plan]==0 AND $p[sw_afiliacion]==1) OR ($p[sw_tipo_plan]==3))
          {
              $bd='';
              $bd=$this->DatosBD($arr[0][tipo_id_paciente],$arr[0][paciente_id],$plan[$i][plan_id]);
              if(!empty($bd))
              {
                  $this->salida .= "      <tr><td colspan=\"8\">";
                  $this->SetJavaScripts('DatosBD');
                  $this->SetJavaScripts('DatosBDAnteriores');
                  $this->SetJavaScripts('DatosEvolucionInactiva');
                  $this->salida .= "<br><table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"50%\" align=\"center\" class=\"normal_10\">";
                  $this->salida .= "  <tr class=\"modulo_list_claro\">";
                  $this->salida .= "   <td align=\"center\" colspan=\"2\" class=\"label\">".$plan[$i][plan_descripcion]."</td>";
                  $this->salida .= "  </tr>";
                  $this->salida .= "  <tr class=\"modulo_list_claro\">";
                  $this->salida .= "   <td align=\"center\">".RetornarWinOpenDatosBD($arr[0][tipo_id_paciente],$arr[0][paciente_id],$plan[$i][plan_id])."</td>";
                  $x=$this->CantidadMeses($plan[$i][plan_id]);
                  if($x>1)
                  {
                      $this->salida .= "   <td align=\"center\">".RetornarWinOpenDatosBDAnteriores($arr[0][tipo_id_paciente],$arr[0][paciente_id],$plan[$i][plan_id],$x)."</td>";
                  }
                  $this->salida .= "  </tr>";
                  $this->salida .= "</table>";
                  $sw=$this->BuscarSwHc();
                  if(!empty($sw))
                  {
                      $dat=$this->BuscarEvolucion($_SESSION['CENTRALHOSP']['ingreso']);
                      if($dat)
                      {
                          $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"30%\" align=\"center\" class=\"normal_10\">";
                          $this->salida .= "  <tr class=\"modulo_list_claro\">";
                          $_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='CentralImpresionHospitalizacion';
                          $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='FormaDetalleSolicitud';
                          $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
                          $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';
                          $accion=ModuloHCGetURL($dat,'','','','');
                          $this->salida .= "   <td align=\"center\"><a href=\"$accion\">HISTORIA CLINICA</a></td>";
                          $this->salida .= "  </tr>";
                          $this->salida .= "</table><BR>";
                      }
                  }
                  $this->salida .= "      </td></tr>";
              }
          }
      }
      //fin links bd
    

      for($i=0; $i<sizeof($arr);)
      {
          $f=0;
          $accion=ModuloGetURL('app','CentralImpresionHospitalizacion','user','PedirAutorizacion',array('plan'=>$arr[$i][plan_id],'empresa'=>$arr[$i][empresa_id],'servicio'=>$arr[$i][servicio]));
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $d=$i;
          if($arr[$i][plan_id]==$arr[$d][plan_id]
            AND $arr[$i][servicio]==$arr[$d][servicio])
          {
                  $this->salida .= "      <tr><td colspan=\"8\"><br></td></tr>";
                  $this->salida .= "      <tr><td colspan=\"8\" class=\"modulo_table_list_title\">PLAN:".$arr[$i][plan_descripcion]."</td></tr>";
                  $this->salida .= "      <tr>";
                  $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"12%\">SERVICIO: </td>";
                  $this->salida .= "        <td class=\"modulo_list_claro\" width=\"13%\" colspan=\"2\">".$arr[$i][desserv]."</td>";
                  $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"11%\">DEPARTAMENTO: </td>";
                  $this->salida .= "        <td class=\"modulo_list_claro\" align=\"left\" colspan=\"4\">".$arr[$i][despto]."</td>";
                  $this->salida .= "      </tr>";
                  $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                  $this->salida .= "        <td>FECHA</td>";
                  $this->salida .= "        <td width=\"10%\">SOLICITUD</td>";
                  $this->salida .= "        <td width=\"10%\">CARGO</td>";
                  $this->salida .= "        <td colspan=\"2\" width=\"50%\">DESCRIPCION</td>";
                  $this->salida .= "        <td width=\"7%\">CANTIDAD</td>";
                  $this->salida .= "        <td width=\"10%\">TIPO</td>";
                  $this->salida .= "        <td width=\"10%\"></td>";
                  $this->salida .= "      </tr>";
          }

          while($arr[$i][plan_id]==$arr[$d][plan_id]
           AND $arr[$i][servicio]==$arr[$d][servicio])
          {
              if($d % 2) {  $estilo="modulo_list_claro";  }
              else {  $estilo="modulo_list_oscuro";   }
              $this->salida .= "      <tr class=\"$estilo\">";
              $this->salida .= "        <td>".$this->FechaStamp($arr[$d][fecha])." ".$this->HoraStamp($arr[$d][fecha])."</td>";
              $this->salida .= "        <td align=\"center\">".$arr[$d][hc_os_solicitud_id]."</td>";
              $this->salida .= "        <td align=\"center\">".$arr[$d][cargos]."</td>";
              $this->salida .= "        <td colspan=\"2\">".$arr[$d][descripcion]."</td>";
              $this->salida .= "        <td align=\"center\">".$arr[$d][cantidad]."</td>";
              $this->salida .= "        <td align=\"center\">".$arr[$d][desos]."</td>";
              $equi=$this->ValidarEquivalencias($arr[$d][cargos]);
              $cont=$this->ValidarContrato($arr[$d][cargos],$arr[$d][plan_id]);
              if( $arr[$d][nivel_autorizador_id]<$arr[$d][x])
              {    $this->salida .= "        <td align=\"center\" width=\"7%\">Necesita Nivel ".$arr[$d][x]."";  }
              //elseif($equi>=1 AND $equi==$cont
              elseif($equi >= 1 AND $cont > 0
                  AND $arr[$d][nivel_autorizador_id]>=$arr[$d][nivel])
              {
                    $s='';
                    $de=$this->ComboDepartamento($arr[$d][cargos]);
                    if(empty($de))
                    {
                        $p=$this->ComboProveedor($arr[$d][cargos]);
                        if(empty($p))
                        { $s='NO PROVEEDOR <BR>';  }
                    }
                    /*if(empty($arr[$d][departamento])
                       AND empty($arr[$d][tipo_id_tercero]))
                    {  $s='NO PROVEEDOR <BR>';  }*/
                    $this->salida .= "        <td align=\"center\" class=\"label_error\">$s<input type=\"checkbox\" value=\"".$arr[$d][cargos].",".$arr[$d][tarifario_id].",".$arr[$d][ingreso].",".$arr[$d][servicio].",".$arr[$d][hc_os_solicitud_id]."\" name=\"Auto".$arr[$d][hc_os_solicitud_id]."\">";
                    $f++;
              }
              elseif($cont==0)
              {
                  $this->salida .= "        <td align=\"center\" class=\"label_error\" width=\"7%\">NO ESTA CONTRATADO";
              }
              elseif($equi==0)
              {
                  $this->salida .= "        <td align=\"center\" class=\"label_error\" width=\"7%\">NO TIENE EQUIVALENCIAS";
              }
              $this->salida .= "      </td>";
              $this->salida .= "      </tr>";
							$x=MallaValidadoraValidarCargo($arr[$d][cargos],$arr[$d][plan_id],$arr[$d][servicio],$arr[$d][hc_os_solicitud_id],$arr[$d][cantidad]);
							if(is_array($x)){$x='&nbsp;';}
							$this->salida .= "      <tr class=\"$estilo\">";
							$this->salida .= "                <td align=\"center\" colspan=\"8\">$x</td>";
							$this->salida .= "      </tr>";
              $d++;
          }
          $i=$d;
          if($f == 0)
          {
              $this->salida .= "      <tr class=\"$estilo\">";
              $this->salida .= "        <td class=\"label_error\" align=\"center\" colspan=\"8\">NINGUN CARGO PUEDER SER AUTORIZADO</td>";
              $this->salida .= "      </tr>";
          }
          if($f > 0)
          {
              $this->salida .= "      <tr class=\"$estilo\">";
              $this->salida .= "        <td align=\"right\" colspan=\"8\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"AUTORIZAR\"></td>";
              $this->salida .= "      </tr>";
          }
          $this->salida .= "                       </form>";
      }
	$this->salida .= " </table>";
     
      //$this->salida .= "<pre>".print_r($_SESSION['CENTRALHOSP'],true)."</pre>";
      $this->salida .= "<br>";
      $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
	  $reserva_sangre= $this->Get_Info_RerservaSangre($_SESSION['CENTRALHOSP']['ingreso']);
	  $EdadPaciente= $this->EdadPaciente($_SESSION['CENTRALHOSP']['paciente_id'],$_SESSION['CENTRALHOSP']['tipo_id_paciente']);
      $edad_paci=explode(":",$EdadPaciente);
	  //$this->salida .= "<pre>".print_r($EdadPaciente,true)."</pre>";
	  if(is_array($reserva_sangre))
      {
		$this->salida .= "  <tr class=\"modulo_table_list_title\">";
		$this->salida .= "   <td align=\"center\" colspan=\"2\" >RESERVA DE SANGRE</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$reporte = new GetReports();
	    for($f=0;$f<sizeof($reserva_sangre);$f++)
        {
			$this->salida .= "<td class=\"modulo_list_claro\"><b>IMPRIMIR RESERVA DE SANGRE </b></td>";
			$mostrar_resa = $reporte->GetJavaReport('hc','ReservaSangre','ReservaSangre', array("ingreso"=>$_SESSION['CENTRALHOSP']['ingreso'],
																							  "tipoidpaciente"=>$_SESSION['CENTRALHOSP']['tipo_id_paciente'],
																							  "paciente"=>$_SESSION['CENTRALHOSP']['paciente_id'],
																							  "nombre_paciente"=>$_SESSION['CENTRALHOSP']['nombre_paciente'],
																							  "edad_paciente"=>$edad_paci[0],
																							  "nombre_tercero"=>$reserva_sangre[$f]['nombre_tercero'],
																							  "tipo_id_tercero"=>$reserva_sangre[$f]['tercero_id'],
																							  "tercero_id"=>$reserva_sangre[$f]['tercero_id'],
																							  "solicitud_id"=>$reserva_sangre[$f]['solicitud_reserva_sangre_id']),
														array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				
				$funcion_resa = $reporte->GetJavaFunction();
				$this->salida .= "      <td class=\"modulo_list_claro\">\n";
				$this->salida .= $mostrar_resa;
				$this->salida .= "        <a href=\"javascript:".$funcion_resa."\" class =\"label_error\">\n";
				$this->salida .= "          <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>INGRESO: ".$_SESSION['CENTRALHOSP']['ingreso']."\n";
				$this->salida .= "        </a>\n";
				$this->salida .= "      </td>\n";
      
                $this->salida .= "  </tr>";
		 }		
	 }
	 $transfusion_sangre= $this->Get_Info_TransfusionSangre($_SESSION['CENTRALHOSP']['ingreso']);
	 //$this->salida .= "<pre>".print_r($_SESSION,true)."</pre>";
	 //$this->salida .= "<pre>".print_r($transfusion_sangre,true)."</pre>";
	 if(is_array($transfusion_sangre))
     {
		$this->salida .= "  <tr class=\"modulo_table_list_title\">";
		$this->salida .= "   <td align=\"center\" colspan=\"2\" >TRANSFUSION SANGUINEA</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		//$reporte1 = new GetReports();
	    for($f=0;$f<sizeof($transfusion_sangre);$f++)
        {
			$this->salida .= "<td class=\"modulo_list_claro\"><b>IMPRIMIR TRANSFUSION SANGUINEA </b></td>";
			$mostrar_trans = $reporte->GetJavaReport('hc','TransfusionSanguinea','TransfusionSanguinea', array("ingreso"=>$_SESSION['CENTRALHOSP']['ingreso'],
																										 "tipoidpaciente"=>$_SESSION['CENTRALHOSP']['tipo_id_paciente'],
																										 "paciente"=>$_SESSION['CENTRALHOSP']['paciente_id'],
																										 "nombre_paciente"=>$_SESSION['CENTRALHOSP']['nombre_paciente'],
																										 "edad_paciente"=>$edad_paciente,
																										 "nombre_tercero"=>$transfusion_sangre[$f]['nombre_tercero'],
																										 "tipo_id_tercero"=>$transfusion_sangre[$f]['tercero_id'],
																										  "tercero_id"=>$transfusion_sangre[$f]['tercero_id'],
																										  "fecha_trans"=>$transfusion_sangre[$f]['fecha']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
																										  
			$funcion_trans = $reporte->GetJavaFunction();
			$this->salida .= "      <td class=\"modulo_list_claro\">\n";
			$this->salida .= $mostrar_trans;
			$this->salida .= "        <a href=\"javascript:".$funcion_trans."\" class =\"label_error\">\n";
			$this->salida .= "          <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>INGRESO: ".$_SESSION['CENTRALHOSP']['ingreso']."\n";
			$this->salida .= "        </a>\n";
			$this->salida .= "      </td>\n";
  
			$this->salida .= "  </tr>";
		}		
	 }
     $this->salida .= "      <tr><td colspan=\"7\"><br></td></tr>";
     $this->salida .= "</table>"; 
    
 
	if(!empty($_SESSION['CENTRALHOSP']['ARREGLO']['DETALLE2']))
	{  $this->ListadoOsAuto('FormaDetalleSolicitud',&$reporte);  }
	$this->salida .= "     <table width=\"50%\" border=\"0\" align=\"center\">";
	$this->salida .= "               <tr>";
	
	unset($reporte);
	$contenedor=$_SESSION['CENTRALHOSP']['RETORNO']['contenedor'];
	$modulo=$_SESSION['CENTRALHOSP']['RETORNO']['modulo'];
	$tipo=$_SESSION['CENTRALHOSP']['RETORNO']['tipo'];
	$metodo=$_SESSION['CENTRALHOSP']['RETORNO']['metodo'];
	$argumentos=$_SESSION['CENTRALHOSP']['RETORNO']['argumentos'];
	$actionM=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
	$this->salida .= "             <form name=\"forma1\" action=\"$actionM\" method=\"post\">";
	$this->salida .= "                       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></td>";
	$this->salida .= "                       </form>";
	$this->salida .= "               </tr>";
	$this->salida .= "  </table>";
	$this->salida .= ThemeCerrarTabla();
	return true;
  }


  /**
  *
  */
  function ListadoOsAuto($regreso,$reporte)
  {
			IncludeLib('funciones_central_impresion');
      $var=$_SESSION['CENTRALHOSP']['ARREGLO']['DETALLE2'];
      if(!empty($var))
      {   $this->salida .="<br>";
          $this->salida .= ThemeAbrirTabla('ORDENES SERVICIO AUTORIZADAS',850);
          for($i=0; $i<sizeof($var);)
          {
                $d=$i;
                $this->salida .= "<table width=\"95%\" border=\"1\" align=\"center\" >";
                $this->salida .= "      <tr class=\"modulo_table_title\">";
                $this->salida .= "        <td colspan=\"5\" align=\"left\">NUMERO DE ORDEN DE SERVICIO ".$var[$i][orden_servicio_id]."</td>";
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr>";
                $this->salida .= "        <td colspan=\"5\" class=\"modulo_list_claro\">";
                $this->salida .= "            <table width=\"100%\" border=\"1\" align=\"center\" class=\"\">";
                $this->salida .= "                <tr>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">TIPO AFILIADO: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][tipo_afiliado_nombre]."</td>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">RANGO: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][rango]."</td>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">SEMANAS COT.: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][semanas_cotizadas]."</td>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">SERVICIO: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][desserv]."</td>";
                $this->salida .= "                </tr>";
                $this->salida .= "                <tr>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">AUT. INT.: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][autorizacion_int]."</td>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">AUT. EXT: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\">".$var[$d][autorizacion_ext]."</td>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">AUTORIZADOR: </td>";
								$dat=BuscarAutorizador($var[$d][autorizacion_int],$var[$d][autorizacion_ext]);
                $this->salida .= "                    <td width=\"5%\" colspan=\"3\" class=\"hc_table_submodulo_list_title\">".$dat."</td>";
                $this->salida .= "                </tr>";
                $this->salida .= "                <tr>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">PLAN: </td>";
                $this->salida .= "                    <td width=\"5%\" class=\"hc_table_submodulo_list_title\" colspan=\"7\" align=\"left\">".$var[$d][plan_descripcion]."</td>";
                $this->salida .= "                </tr>";
                $this->salida .= "                <tr>";
                $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">OBSERVACIONES: </td>";
                $this->salida .= "                    <td width=\"5%\" colspan=\"7\" class=\"hc_table_submodulo_list_title\" align=\"left\">".$var[$d][observacion]."</td>";
                $this->salida .= "                </tr>";
                $this->salida .= "             </table>";
                $this->salida .= "        </td>";
                $this->salida .= "      </tr>";
                while($var[$i][orden_servicio_id]==$var[$d][orden_servicio_id])
                {
                    $this->salida .= "      <tr>";
                    $this->salida .= "        <td colspan=\"5\">";
                    $this->salida .= "        <table width=\"99%\" border=\"0\" align=\"center\">";
                    $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                    $this->salida .= "        <td width=\"6%\">ITEM</td>";
                    $this->salida .= "        <td width=\"6%\">CANT.</td>";
                    $this->salida .= "        <td width=\"10%\">CARGO</td>";
                    $this->salida .= "        <td width=\"45%\">DESCRICPION</td>";
                    $this->salida .= "        <td width=\"20%\">PROVEEDOR</td>";
                    $this->salida .= "      </tr>";
                    if($d % 2) {  $estilo="modulo_list_claro";  }
                    else {  $estilo="modulo_list_oscuro";   }
                    $this->salida .= "      <tr class=\"$estilo\">";
                    $this->salida .= "        <td align=\"center\">".$var[$d][numero_orden_id]."</td>";
                    $this->salida .= "        <td align=\"center\">".$var[$d][cantidad]."</td>";
                    /*if(!empty($var[$d][cargo])){  $cargo=$var[$d][cargo];  }
                    else {  $cargo=$var[$d][cargoext];   }*/
                    $cargo=$var[$d][cargo_cups];
                    $this->salida .= "        <td align=\"center\">".$cargo."</td>";
                    $this->salida .= "        <td>".$var[$d][descripcion]."</td>";
                    $p='';
                    if(!empty($var[$d][departamento]))
                    {  $p='DPTO. '.$var[$d][desdpto];  $id=$var[$d][departamento]; }
                    else
                    {  $p=$var[$d][planpro];  $id=$var[$d][plan_proveedor_id];}
                    $this->salida .= "        <td align=\"center\">".$p."</td>";
                    $this->salida .= "      </tr>";
                    $this->salida .= "      <tr class=\"modulo_list_oscuro\">";
                    $this->salida .= "        <td colspan=\"5\">";
                    $this->salida .= "            <table width=\"100%\" border=\"0\" align=\"center\">";
                    $this->salida .= "                <tr class=\"modulo_list_claro\">";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">ACTIVACION: </td>";
                    $this->salida .= "                    <td width=\"5%\" colspan=\"2\">".$this->FechaStamp($var[$d][fecha_activacion])."</td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">VENC.: </td>";
                    $x='';
                    if(strtotime(date("Y-m-d")) > strtotime($var[$d][fecha_vencimiento])) $x='VENCIDA';
                    if(strtotime(date("Y-m-d")) == strtotime($var[$d][fecha_vencimiento])) $x='';
                    $this->salida .= "                    <td width=\"5%\" >".$this->FechaStamp($var[$d][fecha_vencimiento])."</td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"label_error\" align=\"center\">".$x."</td>";
                    $this->salida .= "                    <td width=\"5%\" class=\"modulo_table_list_title\">REFRENDAR HASTA: </td>";
                    $this->salida .= "                    <td width=\"5%\">".$this->FechaStamp($var[$d][fecha_refrendar])."</td>";
                    $this->salida .= "                </tr>";
                    $this->salida .= "             </table>";
                    $this->salida .= "    <table width=\"100%\" border=\"0\" align=\"center\">";
                    $this->salida .= "      <tr class=\"modulo_list_claro\" align=\"center\">";
                    $this->salida .= "                    <td width=\"7%\" class=\"modulo_table_list_title\">ESTADO: </td>";
                    $this->salida .= "                    <td width=\"9%\" class=\"hc_table_submodulo_list_title\" colspan=\"2\">".$var[$d][estado]."</td>";
                    $this->salida .= "        <td width=\"20%\"></td>";
                    $accion=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteOrdenServicio',array('regreso'=>$regreso,'orden'=>$var[$d][orden_servicio_id],'plan'=>$var[$d][plan_id],'tipoid'=>$var[$d][tipo_id_paciente],'paciente'=>$var[$d][paciente_id],'afiliado'=>$var[$d][tipo_afiliado_id]));
                    /*echo "estado=>".$var[$d][estado];
                    if($x!='VENCIDA' AND ($var[$d][estado]=='PAGADO' OR $var[$d][estado]=='ACTIVO' OR $var[$d][estado]=='TRASCRIPCION'))
                    {   $this->salida .= "        <td width=\"10%\"><a href=\"$accion\">IMPRIMIR</a></td>";  }
                    else
                    {   $this->salida .= "        <td width=\"10%\"></td>";  }*/
                    $this->salida .= "        <td width=\"10%\"></td>";
                    $this->salida .= "      </tr>";
                    $this->salida .= "       </table>";
                    $this->salida .= "        </td>";
                    $this->salida .= "      </tr>";
                    $this->salida .= "       </table>";
                    $this->salida .= "        </td>";
                    $this->salida .= "      </tr>";            
                    $d++;
                }
                $i=$d;
                $accion=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteOrdenServicio',array('regreso'=>$regreso,'orden'=>$var[$d-1][orden_servicio_id],'plan'=>$var[$d-1][plan_id],'tipoid'=>$var[$d-1][tipo_id_paciente],'paciente'=>$var[$d-1][paciente_id],'afiliado'=>$var[$d-1][tipo_afiliado_id],'pos'=>1));
                if($x!='VENCIDA' AND ($var[$d-1][estado]=='PAGADO' OR $var[$d-1][estado]=='ACTIVO' OR $var[$d-1][estado]=='TRASCRIPCION'))
                {
                	$nombreArchivo = 'ordenservicioHTM'.$var[$d-1][orden_servicio_id];
                    $this->salida .= "      <tr class=\"modulo_list_claro\">";
                    $this->salida .= "        <td align=\"center\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";
                    $mostrar=$reporte->GetJavaReport('app','CentralImpresionHospitalizacion','ordenservicioHTM',array('orden'=>$var[$d-1][orden_servicio_id]),array('rpt_name'=>$nombreArchivo,'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                    $funcion=$reporte->GetJavaFunction();
                    $this->salida .=$mostrar;
                    $this->salida.="  				 <td align=\"center\" width=\"43%\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a></td>";
                    $accion=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteOrdenServicio',array('regreso'=>$regreso,'orden'=>$var[$d-1][orden_servicio_id],'plan'=>$var[$d-1][plan_id],'tipoid'=>$var[$d-1][tipo_id_paciente],'paciente'=>$var[$d-1][paciente_id],'afiliado'=>$var[$d-1][tipo_afiliado_id],'pos'=>0));
                    $this->salida .= "                <td class=$estilo align=\"center\" width=\"7%\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;<a href=\"$accion\"> IMPRIMIR MEDIA CARTA</a></td></tr>";
                    $this->salida .= "      </tr>";
                }                  
                $this->salida .= "       </table><br>";
          }//fin for
          $this->salida .= ThemeCerrarTabla();
      }
  }



  /**
  *
  */
  function FormaListadoCargos($arr)
  {
      IncludeLib("tarifario_cargos");
      $this->salida .= ThemeAbrirTabla('CARGOS ORDENES SERVICIO');
      //mensaje
      $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "  </table>";
      $this->salida .= "     <table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "      <tr>";
			$this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\">IDENTIFICACION: </td><td width=\"20%\" class=\"modulo_list_claro\">".$_SESSION['CENTRALHOSP']['tipo_id_paciente']." ".$_SESSION['CENTRALHOSP']['paciente_id']."</td>";
			$this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\">PACIENTE:</td><td width=\"60%\" class=\"modulo_list_claro\">".$_SESSION['CENTRALHOSP']['nombre_paciente']."</td>";
      $this->salida .= "      </tr>";
      $this->salida .= "       </table><br>";
      $_SESSION['CENTRAL_IMPRESION_HOSPITALIZACION']['ARREGLO_ORDENES']=$arr;
      $accion=ModuloGetURL('app','CentralImpresionHospitalizacion','user','GenerarOS');
      $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
      for($i=0; $i<sizeof($arr);)
      {
          $this->salida .= "     <table width=\"98%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
          $this->salida .= "        <td>CARGO</td>";
          $this->salida .= "        <td>DESCRICPION</td>";
          $this->salida .= "        <td width=\"5%\" nowrap>CANT</td>";
          $this->salida .= "        <td width=\"20%\" nowrap>PROVEEDOR</td>";
          $this->salida .= "      </tr>";
          $d=$i;
          if($i % 2) {  $estilo="modulo_list_claro";  }
          else {  $estilo="modulo_list_oscuro";   }
          //para la cantidad(suma los mismos)
          $this->salida .= "      <tr class=\"$estilo\">";
          //$this->salida .= "        <td align=\"center\" width=\"10%\">".$arr[$i][tarifario_id]."</td>";
          $this->salida .= "        <td align=\"center\" width=\"10%\">".$arr[$i][cargos]."</td>";
          $this->salida .= "        <td>".$arr[$i][descar]."</td>";
          $this->salida .= "        <td align=\"center\">".$arr[$i][cantidad]."</td>";
          $dpto=$this->ComboDepartamento($arr[$i][cargos]);
          $pro=$this->ComboProveedor($arr[$i][cargos]);
          if(!empty($dpto) OR !empty($pro))
          {
              $this->salida .= "        <td align=\"center\"><select name=\"Combo".$arr[$i][hc_os_solicitud_id]."\" class=\"select\">";
              $this->salida .=" <option value=\"-1\">------SELECCIONE------</option>";
              //departamentos
              for($j=0; $j<sizeof($dpto); $j++)
              {
                  $x=$arr[$i][hc_os_solicitud_id].",".$dpto[$j][departamento].",dpto,".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$arr[$i][fecha].",".$arr[$i][cantidad].",".$arr[$i][evento_soat];
                  if($_REQUEST['Combo'.$arr[$i][hc_os_solicitud_id]]==$x)
                  {  $this->salida .=" <option value=\"".$arr[$i][hc_os_solicitud_id].",".$dpto[$j][departamento].",dpto,".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$arr[$i][fecha].",".$arr[$i][cantidad].",".$arr[$i][evento_soat]."\" selected>".$dpto[$j][descripcion]."</option>";  }
                  else
                  {  $this->salida .=" <option value=\"".$arr[$i][hc_os_solicitud_id].",".$dpto[$j][departamento].",dpto,".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$arr[$i][fecha].",".$arr[$i][cantidad].",".$arr[$i][evento_soat]."\">".$dpto[$j][descripcion]."</option>";  }
              }							
              //proveedores
              for($j=0; $j<sizeof($pro); $j++)
              {
                  $x=$arr[$i][hc_os_solicitud_id].",".$pro[$j][tercero_id].",".$pro[$j][tipo_id_tercero].",".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$pro[$j][  plan_proveedor_id].",".$arr[$i][cantidad].",".$arr[$i][evento_soat];
                  if($_REQUEST['Combo'.$arr[$i][hc_os_solicitud_id]]==$x)
                  {  $this->salida .=" <option value=\"".$arr[$i][hc_os_solicitud_id].",".$pro[$j][tercero_id].",".$pro[$j][tipo_id_tercero].",".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$pro[$j][  plan_proveedor_id].",".$arr[$i][cantidad].",".$arr[$i][evento_soat]."\" selected>".$pro[$j][plan_descripcion]."</option>";  }
                  else
                  {  $this->salida .=" <option value=\"".$arr[$i][hc_os_solicitud_id].",".$pro[$j][tercero_id].",".$pro[$j][tipo_id_tercero].",".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$pro[$j][  plan_proveedor_id].",".$arr[$i][cantidad].",".$arr[$i][evento_soat]."\">".$pro[$j][plan_descripcion]."</option>";  }
              }
              $this->salida .= "              </select></td>";
          }
          else
          {
              $this->salida .= "       <input type=\"hidden\" name=\"Trans\" value=\"1\">";
              $this->salida .= "       <input type=\"hidden\" name=\"dat\" value=\"".$arr[$i][hc_os_solicitud_id].",dpto,dpto,".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha].",".$arr[$i][fecha].",".$arr[$i][cantidad]."\">";
              $this->salida .= "       <input type=\"hidden\" name=\"solicitud\" value=\"".$arr[$i][hc_os_solicitud_id]."\">";
              $trans=true;
              //$accion=ModuloGetURL('app','CentroAutorizacion','user','CrearTranscripcion',array('datos'=>$arr,'solicitud'=>$arr[$i][hc_os_solicitud_id]));
              //$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
              $this->salida .= "        <td class=\"label_error\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Transcripcion\" value=\"TRANSCRIPCION\"></td>";
        //  $this->salida .= "       <input type=\"hidden\" name=\"Combo".$arr[$d][hc_os_solicitud_id]."\" value=\"".$arr[$i][hc_os_solicitud_id].",".$_SESSION['CAJARAPIDA']['DPTO'].",dpto,".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha]."\">";
          //    $this->salida .= "        <td class=\"label_error\" align=\"center\"><a href=\"$accion\">TRANSCRIPCION</a></td>";
          }
          $this->salida .= "       <input type=\"hidden\" name=\"trans\" value=\"$j\">";
          $this->salida .= "      </tr>";
          $this->salida .= "      <tr>";
          $this->salida .= "      <td colspan=\"4\">";
          $this->salida .= "     <table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
          $this->salida .= "        <td>CARGO</td>";
          $this->salida .= "        <td>TARIFARIO</td>";
          $this->salida .= "        <td>DESCRICPION</td>";
          $this->salida .= "        <td>PRECIO</td>";
          $this->salida .= "        <td></td>";
          $this->salida .= "      </tr>";
          $x=0;
          while($arr[$i][cargos]==$arr[$d][cargos]
            AND $arr[$i][hc_os_solicitud_id]==$arr[$d][hc_os_solicitud_id])
          {
							$cont=$this->ValidarContratoEqui($arr[$d][tarifario_id],$arr[$d][cargo],$arr[$d][plan_id]);
							if($cont > 0)
							{
									$this->salida .= "      <tr class=\"$estilo\">";
									$this->salida .= "      <td align=\"center\" width=\"10%\">".$arr[$d][cargo]."</td>";
									$this->salida .= "      <td align=\"center\" width=\"10%\">".$arr[$d][tarifario_id]."</td>";
									$this->salida .= "      <td width=\"70%\">".$arr[$d][descripcion]."</td>";
									$cargos[]=array('tarifario_id'=>$arr[$d][tarifario_id],'cargo'=>$arr[$d][cargo],'cantidad'=>1,'autorizacion_int'=>$_SESSION['CENTROAUTORIZACION']['TODO']['NumAutorizacion'],'autorizacion_ext'=>'');
									if(!empty($_SESSION['CENTROAUTORIZACION']['TODO']))
									{  $liq=LiquidarCargosCuentaVirtual($cargos,'','','',$_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] ,$_SESSION['CENTROAUTORIZACION']['TODO']['tipo_afiliado_id'] ,$_SESSION['CENTROAUTORIZACION']['TODO']['rango'] ,$_SESSION['CENTROAUTORIZACION']['TODO']['semanas'],$arr[$d][servicio]);   }
									else
									{  $liq=LiquidarCargosCuentaVirtual($cargos,'','','',$_SESSION['CENTROAUTORIZACION']['PLAN'] ,$_SESSION['CENTROAUTORIZACION']['tipo_afiliado_id'] ,$_SESSION['CENTROAUTORIZACION']['rango'] ,$_SESSION['CENTROAUTORIZACION']['semanas'],$arr[$d][servicio]);   }
									$this->salida .= "      <td align=\"center\" width=\"15%\">".FormatoValor($liq[0][valor_cargo])."</td>";
									if($_REQUEST['Op'.$arr[$d][hc_os_solicitud_id].$arr[$d][cargo].$arr[$d][tarifario_id]]==$arr[$d][hc_os_solicitud_id].",".$arr[$d][cargo].",".$arr[$d][tarifario_id])
									{  $this->salida .= "      <td width=\"5%\" align=\"center\"><input type=\"checkbox\" value=\"".$arr[$d][hc_os_solicitud_id].",".$arr[$d][cargo].",".$arr[$d][tarifario_id]."\" name=\"Op".$arr[$d][hc_os_solicitud_id].$arr[$d][cargo].$arr[$d][tarifario_id]."\" checked></td>";  }
									else
									{  $this->salida .= "      <td width=\"5%\" align=\"center\"><input type=\"checkbox\" value=\"".$arr[$d][hc_os_solicitud_id].",".$arr[$d][cargo].",".$arr[$d][tarifario_id]."\" name=\"Op".$arr[$d][hc_os_solicitud_id].$arr[$d][cargo].$arr[$d][tarifario_id]."\"></td>";  }
									$this->salida .= "      </tr>";
							}
              $d++;
              $x++;
          }
          $i=$d;
          if(!empty($trans))
          {  $this->salida .= "</form>";  }
          $this->salida .= " </table>";
          $this->salida .= "      </td>";
          $this->salida .= "      </tr>";
          $this->salida .= " </table><br>";
      }
      //if($j!=0)
      //{
        $this->salida .= "    <table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar1\" value=\"ACEPTAR\"></td>";
        $this->salida .= "                       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\"></td>";
        $this->salida .= "                       </form>";
        $this->salida .= "               </tr>";
        $this->salida .= "  </table>";
      //}
      $this->salida .= ThemeCerrarTabla();
      return true;
  }


//-------------------------------------------------------------------------


            /*
    * Funcion donde se visualiza el encabezado de la empresa.
    * @return boolean
    */
  function Encabezado($mirar='')
    {
        $this->salida .= "<br><table  class=\"modulo_table_list_title\" border=\"0\"  width=\"80%\" align=\"center\" >";
        $this->salida .= " <tr class=\"modulo_table_list_title\">";
        $this->salida .= " <td>EMPRESA</td>";
        $this->salida .= " <td>CENTRO UTILIDAD</td>";
        $this->salida .= " <td>DEPARTAMENTO</td>";
        $this->salida .= " </tr>";
        $this->salida .= " <tr align=\"center\">";
        $this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['CENTRO']['NOM_EMP']."</td>";
        $this->salida .= " <td class=\"modulo_list_claro\">".$_SESSION['CENTRO']['NOM_CENTRO']."</td>";
        $this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['CENTRO']['NOM_DPTO']."</td>";
        $this->salida .= " </tr>";
		if($mirar=='1')
		{
			if (!IncludeFile("classes/ResumenHC/ResumenHC.class.php"))
			{
					$this->error = "Error";
					$this->mensajeDeError = "No se pudo incluir : classes/ResumenHC/ResumenHC.class.php";
			}
			global $VISTA;
			if (!IncludeFile("classes/ResumenHC/$VISTA/ResumenHC.$VISTA.php"))
			{
					$this->error = "Error";
					$this->mensajeDeError = "No se pudo incluir : classes/ResumenHC/$VISTA/ResumenHC.$VISTA.php";
			}
			$temp="ResumenHC_$VISTA";
			$resumenhc = new $temp($_SESSION['CENTRAL']['PACIENTE']['evolucion_id']);
			if (!$resumenhc->IniciarImprimir())
			{
					$this->error = $resumenhc->Error();
					$this->mensajeDeError = $resumenhc->ErrorMsg();
					return false;
			}
			$resumenhc->GetImpresion();
			unset($resumenhc);
			$this->salida .= " <tr align=\"center\">";
			$this->salida .= " <td class=\"modulo_list_claro\" colspan=\"3\"><input type=\"button\" name=\"EVOLUCION\" value=\"EVOLUCION\" onclick=\"window.open('cache/historia".$_SESSION['CENTRAL']['PACIENTE']['evolucion_id'].".pdf')\" class=\"input-submit\"></td>";
			$this->salida .= " </tr>";
		}
        $this->salida .= " </table>";
        return true;
    }


/**
    * Separa la fecha del formato timestamp
    * @access private
    * @return string
    * @param date fecha
    */
     function FechaStamp($fecha)
     {
            if($fecha){
                    $fech = strtok ($fecha,"-");
                    for($l=0;$l<3;$l++)
                    {
                        $date[$l]=$fech;
                        $fech = strtok ("-");
                    }
                    return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
//                    return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
            }
    }


    /**
    * Separa la hora del formato timestamp
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

            $x = explode (".",$time[3]);
            return  $time[1].":".$time[2].":".$x[0];
    }

   /**
  * Forma para los mansajes
  * @access private
  * @return void
  */
  function FormaMensaje($mensaje,$titulo,$accion,$boton)
  {
        $this->salida .= ThemeAbrirTabla($titulo);
        $this->salida .= "            <table width=\"60%\" align=\"center\" >";
        $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "               <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
        if($boton){
           $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
        }
       else{
           $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
       }
        $this->salida .= "           </form>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

	/**
	*
	*/
	function RetornarBarra(){
    if($this->limit>=$this->conteo){
        return '';
    }
    $paso=$_REQUEST['paso'];
    if(empty($paso)){
      $paso=1;
    }
    $vec='';
    foreach($_REQUEST as $v=>$v1)
    {
      if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
      {   $vec[$v]=$v1;   }
    }

    $accion=ModuloGetURL('app','CentralImpresionHospitalizacion','user','Buscar',$vec);
    $barra=$this->CalcularBarra($paso);
    $numpasos=$this->CalcularNumeroPasos($this->conteo);
    $colspan=1;

    $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
    if($paso > 1){
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
      $colspan+=1;
    }
    $barra ++;
    if(($barra+10)<=$numpasos){
      for($i=($barra);$i<($barra+10);$i++){
        if($paso==$i){
            $this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
        }else{
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
      $colspan+=2;
    }else{
      $diferencia=$numpasos-9;
      if($diferencia<=0){$diferencia=1;}
      for($i=($diferencia);$i<=$numpasos;$i++){
        if($paso==$i){
          $this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
        }else{
          $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      if($paso!=$numpasos){
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
        $colspan++;
      }else{
       // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
    }
    if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
    {
      if($numpasos>10)
      {
        $valor=10+3;
      }
      else
      {
        $valor=$numpasos+3;
      }
      $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>P�gina $paso de $numpasos</td><tr></table><br>";
    }
    else
    {
      if($numpasos>10)
      {
        $valor=10+5;
      }
      else
      {
        $valor=$numpasos+5;
      }
    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>P�gina $paso de $numpasos</td><tr></table><br>";
    }
}
 /**
  *
  */
  function CalcularNumeroPasos($conteo){
    $numpaso=ceil($conteo/$this->limit);
    return $numpaso;
  }

  function CalcularBarra($paso){
    $barra=floor($paso/10)*10;
    if(($paso%10)==0){
      $barra=$barra-10;
    }
    return $barra;
  }

  function CalcularOffset($paso){
    $offset=($paso*$this->limit)-$this->limit;
    return $offset;
  }


//***********************FUNCIONES CLAUDIA

    function FrmMedicamentos($vector1,$rep)
    {
      if($vector1)
      {
				$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida.="</table>";
				$espia=1;
				$total_medicamentos_uso_controlado = 0;

				for($i=0;$i<sizeof($vector1);$i++)
				{
						if($vector1[$i][sw_uso_controlado]== 1)
						{
								$total_medicamentos_uso_controlado= $total_medicamentos_uso_controlado + 1;
						}
						if ($espia==1)
						{
								$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
								$this->salida.="<tr class=\"modulo_table_title\">";

								if ($vector1[$i][item]== 'NO POS' AND $vector1[$i][sw_paciente_no_pos]== '0')
								{
										$this->salida.="  <td align=\"center\" colspan=\"5\">MEDICAMENTOS NO POS JUSTIFICADOS</td>";
								}
								else
								{
									if($vector1[$i][item]== 'NO POS' AND $vector1[$i][sw_paciente_no_pos]== '1')
									{
											$this->salida.="  <td align=\"center\" colspan=\"5\">MEDICAMENTOS NO POS SOLICITADOS A PETICION DEL PACIENTE</td>";
									}
									else
									{
											$this->salida.="  <td align=\"center\" colspan=\"5\">MEDICAMENTOS POS FORMULADOS</td>";
									}
								}
								$this->salida.="</tr>";

								$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
								$this->salida.="  <td width=\"7%\">CODIGO</td>";
								$this->salida.="  <td width=\"30%\">PRODUCTO</td>";
								$this->salida.="  <td colspan=\"3\" width=\"43%\">PRINCIPIO ACTIVO</td>";
								$this->salida.="</tr>";
						}
						if ($vector1[$i][item]== $vector1[$i+1][item] AND $vector1[$i][sw_paciente_no_pos]== $vector1[$i+1][sw_paciente_no_pos])
						{
								$espia=0;
						}
						else
						{
								$espia=1;
						}

						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$this->salida.="<tr class=\"$estilo\">";
						if($vector1[$i][item] == 'NO POS')
						{
								$this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
						}
						else
						{
								$this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
						}
						$this->salida.="  <td align=\"center\" width=\"30%\">".$vector1[$i][producto]."</td>";
						$this->salida.="  <td colspan=\"3\" align=\"center\" width=\"43%\">".$vector1[$i][principio_activo]."</td>";
						$this->salida.="</tr>";

						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td colspan = 4>";
						$this->salida.="<table>";

						$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vector1[$i][via]."</td>";
						$this->salida.="</tr>";

						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
						$e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
						if($e==1)
						{
								$this->salida.="  <td align=\"left\" width=\"14%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
						}
						else
						{
								$this->salida.="  <td align=\"left\" width=\"14%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
						}

						//$vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);
						$vector_posologia= Consulta_Solicitud_Medicamentos_Posologia_Hosp($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);

//pintar formula para opcion 1
						if($vector1[$i][tipo_opcion_posologia_id]== 1)
						{
								$this->salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
						}

//pintar formula para opcion 2
						if($vector1[$i][tipo_opcion_posologia_id]== 2)
						{
								$this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
						}

//pintar formula para opcion 3
						if($vector1[$i][tipo_opcion_posologia_id]== 3)
						{
								$momento = '';
								if($vector_posologia[0][sw_estado_momento]== '1')
								{
										$momento = 'antes de ';
								}
								else
								{
										if($vector_posologia[0][sw_estado_momento]== '2')
										{
												$momento = 'durante ';
										}
										else
										{
											if($vector_posologia[0][sw_estado_momento]== '3')
											{
													$momento = 'despues de ';
											}
										}
								}
								$Cen = $Alm = $Des= '';
								$cont= 0;
								$conector = '  ';
								$conector1 = '  ';
								if($vector_posologia[0][sw_estado_desayuno]== '1')
								{
										$Des = $momento.'el Desayuno';
										$cont++;
								}
								if($vector_posologia[0][sw_estado_almuerzo]== '1')
								{
										$Alm = $momento.'el Almuerzo';
										$cont++;
								}
								if($vector_posologia[0][sw_estado_cena]== '1')
								{
										$Cen = $momento.'la Cena';
										$cont++;
								}
								if ($cont== 2)
								{
										$conector = ' y ';
										$conector1 = '  ';
								}
								if ($cont== 1)
								{
										$conector = '  ';
										$conector1 = '  ';
								}
								if ($cont== 3)
								{
										$conector = ' , ';
										$conector1 = ' y ';
								}
								$this->salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
						}

//pintar formula para opcion 4
						if($vector1[$i][tipo_opcion_posologia_id]== 4)
						{
								$conector = '  ';
								$frecuencia='';
								$j=0;
								foreach ($vector_posologia as $k => $v)
								{
										if ($j+1 ==sizeof($vector_posologia))
										{
												$conector = '  ';
										}
										else
										{
												if ($j+2 ==sizeof($vector_posologia))
												{
														$conector = ' y ';
												}
												else
												{
														$conector = ' - ';
												}
										}
										$frecuencia = $frecuencia.$k.$conector;
										$j++;
								}
								$this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
						}

//pintar formula para opcion 5
						if($vector1[$i][tipo_opcion_posologia_id]== 5)
						{
								$this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
						}
						$this->salida.="</tr>";

						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
						$e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
						if ($vector1[$i][contenido_unidad_venta])
						{
								if($e==1)
								{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
								}
								else
								{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
								}
						}
						else
						{
								if($e==1)
								{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]."</td>";
								}
								else
								{
										$this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
								}
						}
						$this->salida.="</tr>";

						$this->salida.="</table>";
						$this->salida.="</td>";
						$this->salida.="</tr>";

						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td colspan = 4 class=\"$estilo\">";
						$this->salida.="<table>";
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";
						$this->salida.="  <td align=\"left\" width=\"69%\">".$vector1[$i][observacion]."</td>";


						//IMPRESION DE LA JUSTIFICACION
						if ($vector1[$i][item]== 'NO POS' AND $vector1[$i][sw_paciente_no_pos]== '0')
						{
							//reporte en pdf de la justificacion
							$mostrar=$rep->GetJavaReport('system','reportes','justificacion_nopos_med_html',array('codigo_producto'=>$vector1[$i][codigo_producto], 'evolucion'=>$vector1[$i][evolucion_id], 'invocado'=>2),array('rpt_name'=>'justificacion_nopos_med_html','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
							$nombre_funcion=$rep->GetJavaFunction();
							$this->salida .=$mostrar;
							$this->salida.="<td align=\"left\" width=\"14%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>JUSTIFICACION</a></td>";
						}
						//FIN DE IMPRESION JUSTIFICACION

						$this->salida.="<tr class=\"$estilo\">";
						if($vector1[$i][sw_uso_controlado]==1)
						{
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td align=\"left\" colspan = 2 width=\"73%\">MEDICAMENTO DE USO CONTROLADO</td>";
								$this->salida.="<tr class=\"$estilo\">";
						}
						$this->salida.="</table>";
						$this->salida.="</td>";
						$this->salida.="</tr>";

						if($espia==1)
						{
                $opcion = ModuloGetVar("","","formato_formula");
								$this->salida.="<tr class=\"$estilo\">";
								if ($vector1[$i][item]== 'NO POS' AND $vector1[$i][sw_paciente_no_pos]== '0')
								{
                        //reporte en impresora pos
												$accion1=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteFormulaMedica',array('sw_pos'=>'0','sw_paciente_no_pos'=>'0', 'impresion_pos'=>'1'));
												$this->salida.="<td colspan = 2 align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";

                        //reporte en pdf
                        switch($opcion)
                        {
                          case '1': $nombre_reporte = "formula_medica_html_oms"; break;
                          default: $nombre_reporte = "formula_medica_html"; break;
                        }
                        //$mostrar=$rep->GetJavaReport('app',$modulo,                    $nombre_reporte,array('tipo_id_paciente'=>$_SESSION['IMPRESIONHC']['PACIENTE']['tipo_id_paciente'],                                         'paciente_id'=>$_SESSION['IMPRESIONHC']['PACIENTE']['paciente_id'], 'ingreso'=>$ingreso, 'evolucion_id'=>$evolucion, 'modulo_invoca'=>'impresionhc'),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));

												$mostrar=$rep->GetJavaReport('app','Central_de_Autorizaciones',$nombre_reporte,array('sw_pos'=>'0','sw_paciente_no_pos'=>'0', 'tipo_id_paciente'=>$_SESSION['CENTRALHOSP']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['CENTRALHOSP']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['CENTRALHOSP']['PACIENTE']['ingreso'],"evolucion_id"=>$vector1[$i]['evolucion_id'],'modulo_invoca'=>'CentralImpresionHospitalizacion'),array('rpt_name'=>'formula_medica_hosp_html','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
												$nombre_funcion=$rep->GetJavaFunction();
												$this->salida .=$mostrar;
												$this->salida.="<td colspan = 2 align=\"center\" width=\"43%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";

                        //reporte en impresora media carta
												$accion1=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteFormulaMedica',array('sw_pos'=>'0','sw_paciente_no_pos'=>'0'));
												$this->salida.="<td align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR MEDIA CARTA</a></td>";

								}
								else
								{
										if($vector1[$i][item]== 'NO POS' AND $vector1[$i][sw_paciente_no_pos]== '1')
										{
                        //reporte en impresora pos
												$accion1=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteFormulaMedica',array('sw_pos'=>'0','sw_paciente_no_pos'=>'1', 'impresion_pos'=>'1'));
												$this->salida.="  <td colspan = 2 align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";

												//reporte en pdf
                        switch($opcion)
                        {
                          case '1': $nombre_reporte = "formula_medica_html_oms"; break;
                          default: $nombre_reporte = "formula_medica_html"; break;
                        }
												$mostrar=$rep->GetJavaReport('app','Central_de_Autorizaciones',$nombre_reporte,array('sw_pos'=>'0','sw_paciente_no_pos'=>'0', 'tipo_id_paciente'=>$_SESSION['CENTRALHOSP']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['CENTRALHOSP']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['CENTRALHOSP']['PACIENTE']['ingreso'],"evolucion_id"=>$vector1[$i]['evolucion_id']),array('rpt_name'=>'formula_medica_hosp_html','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));

                        //$mostrar=$rep->GetJavaReport('app','CentralImpresionHospitalizacion','formula_medica_hosp_html',array('sw_pos'=>'0','sw_paciente_no_pos'=>'1', 'tipo_id_paciente'=>$_SESSION['CENTRALHOSP']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['CENTRALHOSP']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['CENTRALHOSP']['PACIENTE']['ingreso']),array('rpt_name'=>'formula_medica_hosp_html','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
												$nombre_funcion=$rep->GetJavaFunction();
												$this->salida .=$mostrar;
												$this->salida.="<td colspan = 2 align=\"center\" width=\"43%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";

                        //reporte en impresora media carta
												$accion1=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteFormulaMedica',array('sw_pos'=>'0','sw_paciente_no_pos'=>'1'));
												$this->salida.="<td align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR MEDIA CARTA</a></td>";

										}
										else
										{
                        //reporte en impresora pos
												$accion1=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteFormulaMedica',array('sw_pos'=>'1', 'impresion_pos'=>'1'));
												$this->salida.="  <td colspan = 2 align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";

												//reporte en pdf
                        switch($opcion)
                        {
                          case '1': $nombre_reporte = "formula_medica_html_oms"; break;
                          default: $nombre_reporte = "formula_medica_html"; break;
                        }
												$mostrar=$rep->GetJavaReport('app','Central_de_Autorizaciones',$nombre_reporte,array('tipo_id_paciente'=>$_SESSION['CENTRALHOSP']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['CENTRALHOSP']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['CENTRALHOSP']['PACIENTE']['ingreso'],"evolucion_id"=>$vector1[$i]['evolucion_id']),array('rpt_name'=>'formula_medica_hosp_html','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));

												//$mostrar=$rep->GetJavaReport('app','CentralImpresionHospitalizacion','formula_medica_hosp_html',array('sw_pos'=>'1', 'tipo_id_paciente'=>$_SESSION['CENTRALHOSP']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['CENTRALHOSP']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['CENTRALHOSP']['PACIENTE']['ingreso']),array('rpt_name'=>'formula_medica_hosp_html','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
												$nombre_funcion=$rep->GetJavaFunction();
												$this->salida .=$mostrar;
                        $this->salida.="<td colspan = 2 align=\"center\" width=\"43%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";

                        //reporte en impresora media carta
												$accion1=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteFormulaMedica',array('sw_pos'=>'1'));
												$this->salida.="<td align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR MEDIA CARTA</a></td>";

										}
								}
								$this->salida.="</tr>";
								$this->salida.="</table><br>";
						}
				}
//opcion para imprimir medicamentos de uso controlado
				if($total_medicamentos_uso_controlado>0)
				{
						$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
						$this->salida.="<tr class=\"modulo_table_title\">";
						$this->salida.="<td COLSPAN = 4 align=\"center\" >MEDICAMENTOS DE USO CONTROLADO</td>";
						$this->salida.="</tr>";

						$this->salida.="<tr class=\"modulo_list_claro\">";
						//reporte en impresora pos
						$accion1=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteFormulaMedica',array('sw_uso_controlado'=>'1', 'impresion_pos'=>'1'));
						$this->salida.="<td align=\"center\" width=\"40%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR POS</a></td>";
						//impresion de pdf
						//$this->salida.="<td align=\"center\" width=\"40%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";

            //reporte en pdf
						//alex
						$mostrar=$rep->GetJavaReport('app','CentralImpresionHospitalizacion','formula_medica_hosp_html',array('sw_uso_controlado'=>'1', 'tipo_id_paciente'=>$_SESSION['CENTRALHOSP']['PACIENTE']['tipo_id_paciente'],'paciente_id'=>$_SESSION['CENTRALHOSP']['PACIENTE']['paciente_id'], 'ingreso'=>$_SESSION['CENTRALHOSP']['PACIENTE']['ingreso']),array('rpt_name'=>'formula_medica_hosp_html','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
						$nombre_funcion=$rep->GetJavaFunction();
						$this->salida .=$mostrar;
  					$this->salida.="<td colspan = 2 align=\"center\" width=\"43%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";
						//fin de alex

						//reporte en impresora media carta
						$accion1=ModuloGetURL('app','CentralImpresionHospitalizacion','user','ReporteFormulaMedica',array('sw_uso_controlado'=>'1'));
						$this->salida.="<td align=\"center\" width=\"37%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR MEDIA CARTA</a></td>";

						$this->salida.="</tr>";
						$this->salida.="</table><br>";
				}
		}
}


function FrmIncapacidad($vector1,$rep)
{
		$this->salida .= "<form name=\"formades\" action=\"$accion\" method=\"post\">";
		if($vector1)
		{
			$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"5\">INCAPACIDADES MEDICAS GENERADAS</td>";

			for($i=0;$i<sizeof($vector1);$i++)
			{
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td width=\"5%\">No. EVOLUCION</td>";
				$this->salida.="  <td width=\"45%\">OBSERVACION DE LA INCAPACIDAD</td>";
				$this->salida.="  <td width=\"10%\">TIPO DE INCAPACIDAD</td>";
				$this->salida.="  <td width=\"10%\">DIAS DE INCAPACIDAD</td>";
				$this->salida.="  <td width=\"10%\">FECHA DE EMISION</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"5%\">".$vector1[$i][evolucion_id]."</td>";
				$this->salida.="  <td align=\"left\" width=\"45%\">".$vector1[$i][observacion_incapacidad]."</td>";
				$this->salida.="  <td align=\"center\" width=\"10%\">".$vector1[$i][tipo_incapacidad_descripcion]."</td>";
				$this->salida.="  <td align=\"center\" width=\"10%\">".$vector1[$i][dias_de_incapacidad]."</td>";
				$a = $this->FechaStamp($vector1[$i][fecha]);
				$b = $this->HoraStamp($vector1[$i][fecha]);
				$fecha = $a.' - '.$b;
				$this->salida.="  <td align=\"left\" width=\"10%\">".$fecha."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";

				//reporte en impresora pos
        $accion1=ModuloGetURL('app','Central_de_Autorizaciones','user','ReporteIncapacidadMedica', array('evolucion_id'=>$vector1[$i][evolucion_id], 'modulo_invoca'=>'impresion_hospitalizacion', 'parametro_retorno'=>'1', 'impresion_pos'=>'1'));
        $this->salida.="  <td colspan = 2 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR POS</a></td>";

        //reporte en pdf
				//alex
				$mostrar=$rep->GetJavaReport('app','CentralImpresionHospitalizacion','incapacidad_html',array('evolucion_id'=>$vector1[$i][evolucion_id]),array('rpt_name'=>'incapacidad_html','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$nombre_funcion=$rep->GetJavaFunction();
				$this->salida .=$mostrar;
				$this->salida.="<td colspan = 2 align=\"center\" width=\"23%\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR PDF</a></td>";
				//fin de alex

				//reporte en impresora media carta
        $accion2=ModuloGetURL('app','Central_de_Autorizaciones','user','ReporteIncapacidadMedica', array('evolucion_id'=>$vector1[$i][evolucion_id], 'modulo_invoca'=>'impresion_hospitalizacion', 'parametro_retorno'=>'1'));
        $this->salida.="  <td colspan = 1 align=\"center\" width=\"20%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>IMPRIMIR MEDIA CARTA</a></td>";


			  $this->salida.="</tr>";
			}
			$this->salida.="</table><br>";
		}
		$this->salida .= "</form>";
		return true;
}
//***********************FIN FUNCIONES CLAUDIA
}//fin clase

?>
