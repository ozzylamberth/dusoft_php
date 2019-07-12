<?php

/**
 * $Id: app_EE_SolicitudDietas_userclasses_HTML.php,v 1.12 2006/01/02 14:56:25 mauricio Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_SolicitudDietas_userclasses_HTML extends app_EE_SolicitudDietas_user
{

    function app_EE_SolicitudDietas_user_HTML()
    {
        $this->app_EE_SolicitudDietas_user();
        $this->salida='';
        return true;
    }


    function forma1()
    {

        return true;
    }
		/**
		*		function SetStyle => Muestra mensajes
		*
		*		crea una fila para poner el mensaje de "Faltan campos por llenar" cambiando a color rojo
		*		el label del campo "obligatorio" sin llenar
		*
		*		@Author Alexander Giraldo
		*		@access Private
		*		@return string
		*		@param string => nombre del input y estilo que qued&oacute; vacio
		*/
			function SetStyle($campo,$campo2,$colum)
			{
				if ($this->frmError[$campo] || $this->frmError[$campo2] || $campo=="MensajeError")
				{
					if ($campo=="MensajeError")   return ("<tr><td colspan='".$colum."' class='label_error'>".$this->frmError["MensajeError"]."</td></tr>");
					return ("label_error");
				}
				return ("label");
			}//End function

   /**
    * Forma para mostrar el Panel de la Estacion de Enfermeria
    *
    * @return boolean True si se ejecuto correctamente
    * @access public
    */
    function FrmPanelEstacion()
    {
			if(empty($datos_estacion))
						{
							$datos_estacion = $_REQUEST['datos_estacion'];
						}
						else
						{
							$datos_estacion = &$this->GetdatosEstacion();          
						}
	
						//VALIDACION DE PERMISOS
						if(!is_array($datos_estacion))
						{
								$url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
								$titulo = "VALIDACION DE PERMISOS";
								$this->frmMSG($url,$titulo);
								return true;
						}
				
					
					//$_SESSION['EE_PanelEnfermeria'][UserGetUID()]['ESTACION_SELECCIONADA']=4;
					//$estacion_id = $_SESSION['EE_PanelEnfermeria'][UserGetUID()]['ESTACION_SELECCIONADA'];
					//$datos_estacion = &$this->GetUserPermisos($estacion_id, $componente=null, $datos=true);
					//CABECERA - DATOS DE LA ESTACION DE ENFERMERIA
					//$this->FrmDatosEstacion(&$datos_estacion);
					$this->FrmDatosEstacion($datos_estacion);
					$this->FrmListadoPacientesEstacion($datos_estacion);
					if(empty($datos_estacion['empresa_descripcion']))
					{
						$this->FrmPieDePaginaPanelestacion2();        
					}else
					{
						$this->FrmPieDePaginaPanelestacion();        
					}
        
        return true;
    }
		/**
    * Forma para mostrar la cabecera de la Estacion de Enfermeria
    *
    * @return boolean True si se ejecuto correctamente
    * @access private
    */
    function FrmDatosEstacion($datos)
    {     //var_dump($datos);
          $this->salida .= ThemeAbrirTabla("ESTACI&Oacute;N DE ENFERMERIA : ".$datos['estacion_descripcion']);
          $this->salida .= "<center>\n";
          $this->salida .= "    <table class='modulo_table_title' border='0' width='80%'>\n";
          $this->salida .= "        <tr class='modulo_table_title'>\n";
          $this->salida .= "            <td>Empresa</td>\n";
          $this->salida .= "            <td>Centro Utilidad</td>\n";
          $this->salida .= "            <td>Unidad Funcional</td>\n";
          $this->salida .= "            <td>Departamento</td>\n";
          $this->salida .= "        </tr>\n";
          $this->salida .= "        <tr class='modulo_list_oscuro'>\n";
          if(empty($datos['empresa_descripcion']))
          {
               $this->salida .= "            <td>".$datos['descripcion1']."</td>\n";
               $this->salida .= "            <td>".$datos['descripcion2']."</td>\n";
               $this->salida .= "            <td>".$datos['descripcion3']."</td>\n";
               $this->salida .= "            <td>".$datos['descripcion4']."</td>\n";
          }else
          {
               $this->salida .= "            <td>".$datos['empresa_descripcion']."</td>\n";
               $this->salida .= "            <td>".$datos['centro_utilidad_descripcion']."</td>\n";
               $this->salida .= "            <td>".$datos['unidad_funcional_descripcion']."</td>\n";
               $this->salida .= "            <td>".$datos['departamento_descripcion']."</td>\n";
          }
          $this->salida .= "        </tr>\n";
          $this->salida .= "    </table>\n";
          return true;
    }
    
      
      /**
     * Forma para mostrar mensaje
     *
     * @param string $url opcional url de retorno
     * @param string $titulo opcional titulo de la ventana
     * @param string $mensaje opcional mensaje a mostrar
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function frmMSG($url='', $titulo='', $mensaje='', $link='')
     {
          if(empty($titulo))  $titulo  = $this->titulo;
          if(empty($mensaje)) $mensaje = "EL USUARIO NO TIENE PERMISOS EN ESTE MODULO.";
          if(empty($link)) $link = "VOLVER";
          $this->salida  = themeAbrirTabla($titulo);
          $this->salida .= "<div class='titulo3' align='center'><br><br><b>$mensaje</b>";
          if($url)
          {
               $this->salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
               $this->salida.="    <tr>\n";
               $this->salida.="        <td align='center' class=\"label_error\">\n";
               $this->salida.="            <a href='$url'>$link</a>\n";
               $this->salida.="        </td>\n";
               $this->salida.="    </tr>\n";
               $this->salida.="  </table>\n";
     
          }
          $this->salida .= "<br><br></div>";
          $this->salida .= themeCerrarTabla();
          return true;
     }
   

    function FrmPieDePaginaPanelestacion()
    {
        $refresh = ModuloGetURL('app','EE_SolicitudDietas','user','FrmPanelEstacion');
        //$href    = ModuloGetURL('app','EE_SolicitudDietas','user','FrmLogueoEstacion');
				$href    = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
        
        $this->salida .= "  <div class='normal_10' align='center'><br>\n";
        $this->salida .= "    <a href='$href'>VOLVER</a>\n";
        $this->salida .= "    &nbsp;&nbsp;-&nbsp;&nbsp;\n";
        $this->salida .= "   <a href='$refresh'>Refrescar</a><br>\n";
        $this->salida .= "  </div>\n";
        $this->salida .= "</center>\n";
        $this->salida .= themeCerrarTabla();
        return true;
    }

     function FrmPieDePaginaPanelestacion2()
     {
          //$refresh = ModuloGetURL('app','EstacionEnfermeria','user','FrmPanelEstacion');
          $href    = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu');
     
          $this->salida .= "  <div class='normal_10' align='center'><br>\n";
          $this->salida .= "    <a href='$href'>VOLVER</a>\n";
          //$this->salida .= "    &nbsp;&nbsp;-&nbsp;&nbsp;\n";
          //$this->salida .= "   <a href='$refresh'>Refrescar</a><br>\n";
          $this->salida .= "  </div>\n";
          $this->salida .= "</center>\n";
          $this->salida .= themeCerrarTabla();
          return true;
	}

		
   /**
    * Forma para mostrar el listado de pacientes en la Estacion de Enfermeria
    *
    * @return boolean True si se ejecuto correctamente
    * @access private
    */
    function FrmListadoPacientesEstacion($datos_estacion)
    {
    		if(!$datos_estacion)
          {
          	$datos_estacion = $_REQUEST['datos_estacion'];
          }
        //$estacion_id = $_SESSION['EE_PanelEnfermeria'][UserGetUID()]['ESTACION_SELECCIONADA'];
        $listadoPacientes = $this->GetPacientesInternados($datos_estacion[estacion_id]);
        //VAR_DUMP($listadoPacientes);
        if($listadoPacientes===false)
        {
            if(empty($this->error))
            {
                $this->error = "EE_PanelEnfermeria - FrmListadoPacientesEstacion";
                $this->mensajeDeError = "El metodo GetPacientesInternados() retorno false.";
            }
            return false;
        }
        

				if($listadoPacientes===null)
        {
            $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
				$this->salida .= "  <tr class=\"label_error\" align=\"center\">\n";
				$this->salida .= "      <td colspan='8' height='30'>VERIFIQUE QUE EL PACIENTE TENGA HABITACION Y CAMA ASIGNADA!!!</td>\n";
				$this->salida .= "  </tr>\n";
				$this->salida .= "</table>\n";
				return true;
        }
        $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');

        $this->salida .= "<br>\n";
        $this->salida .= "<table align=\"center\" width=\"80%\"  border=\"0\" >\n";
        $this->salida .= "  <tr class=\"modulo_table_title\">\n";
        $this->salida .= "      <td colspan='8' height='30'>PACIENTES INTERNADOS EN LA ESTACION</td>\n";
        $this->salida .= "  </tr>\n";
        $this->salida .= "  <tr class=\"modulo_table_title\">\n";
        $this->salida .= "      <td align=\"center\" width=\"5%\">HAB.</td>\n";
        $this->salida .= "      <td align=\"center\" width=\"5%\">CAMA</sub></td>\n";
        $this->salida .= "      <td align=\"center\" width=\"20%\">NOMBRE DEL PACIENTE</td>\n";
        $this->salida .= "      <td align=\"center\" width=\"5%\">TIEMPO<BR>HOSP.</td>\n";
        $this->salida .= "      <td align=\"center\" width=\"5%\">POR</td>\n";
        $this->salida .= "      <td align=\"center\" width=\"50%\">DIETA SUMINISTRADA POR EL MEDICO</td>\n";
        $this->salida .= "      <td align=\"center\" width=\"5%\">FRAC&nbsp;</td>\n";
        $this->salida .= "      <td align=\"center\" width=\"5%\">ACTUALIZAR<BR>&nbsp;DIETA</td>\n";
        $this->salida .= "  </tr>\n";

        $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
				
        foreach($listadoPacientes as $k => $v)
        {
						$accion=ModuloGetURL('app','EE_SolicitudDietas','user','FrmPanelDietas',array('DATOS_PACIENTE' =>$v,'datos_estacion'=>$datos_estacion));
            if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
						$filaPaciente = $v['DATOS_PACIENTE'];
						$imagen = "checkno.png";
						unset($caracteristica);
						if(empty($v['DATOS_DIETAS']['dieta_id']))
						{
							$filaDieta = "&nbsp;";
						}
						else
						{
							$filaDieta	 = $v['DATOS_DIETAS']['descripcion'];
							foreach($v['DATOS_CARACTERISTICAS'] as $fcaract => $caract){
								$caracteristica .= ", ".$caract['descripcion_caracteristica'];
							}
							if($v['DATOS_PACIENTE']['sw_fraccionada']=='1')
							{
								$imagen = "checksi.png";
							}
						}
						$filaDieta .= $caracteristica;
						if($filaDieta=="&nbsp;"){
							$filaDieta="Dieta no Asignada";
						}
						$this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
            $this->salida .= "      <td >$filaPaciente[pieza]</td>\n";
            $this->salida .= "      <td >$filaPaciente[cama]</td>\n";
            $this->salida .= "      <td >$filaPaciente[nombre_completo]</td>\n";
            $this->salida .= "      <td align=\"right\" >" . $this->GetDiasHospitalizacion($filaPaciente['fecha_ingreso']) . "</td>\n";
            $this->salida .= "      <td >&nbsp;</td>\n";
            $this->salida .= "      <td >$filaDieta </td>\n";
            $this->salida .= "      <td align=\"center\"><img src=\"". GetThemePath()."/images/".$imagen."\" width='15' height='15' valign=\"center\">&nbsp;</td>\n";
            $this->salida .= "			<td align=\"center\"><a href=".$accion."><img src=\"". GetThemePath()."/images/editar.png\" width='15' height='15' valign=\"center\">&nbsp;Editar</a></td>";
            $this->salida .= "  </tr>\n";

        }

        $this->salida .= "  </table>\n";
		}
   
		/**
		*	Froma para mostrar el panel de las Dietas
		* @return boolean True si se ejecuto correctamente
    * @access public
		*/
		
		function FrmPanelDietas()
		{
			//$estacion_id = $_SESSION['EE_PanelEnfermeria'][UserGetUID()]['ESTACION_SELECCIONADA'];
			//$datos_estacion = &$this->GetUserPermisos($estacion_id, $componente=null, $datos=true);
			//CABECERA - DATOS DE LA ESTACION DE ENFERMERIA
			if(empty($_REQUEST['datos_estacion']))
               {
                    $this->FrmDatosEstacion(&$datos_estacion);
                    $this->FrmDietasPaciente($_REQUEST['DATOS_PACIENTE']);
                    $this->FrmPieDePaginaPanelDietas();
               }
               else
               {
                    $this->FrmDatosEstacion($_REQUEST['datos_estacion']);
                    $this->FrmDietasPaciente($_REQUEST['DATOS_PACIENTE'],$_REQUEST['datos_estacion']);
                    $this->FrmPieDePaginaPanelDietas2($_REQUEST['datos_estacion']);
               }
//                if(empty($datos_estacion['empresa_descripcion']))
//                {
//                     $this->FrmPieDePaginaPanelestacion2();        
//                }else
//                {
//                     $this->FrmPieDePaginaPanelestacion();        
//                }
			return true;
		}
		
		 function FrmPieDePaginaPanelDietas()
    {
       	$href    = ModuloGetURL('app','EE_SolicitudDietas','user','FrmPanelEstacion');

        $this->salida .= "  <div class='normal_10' align='center'><br>\n";
        $this->salida .= "    <a href='$href'>VOLVER</a>\n";
        $this->salida .= "    &nbsp;&nbsp;-&nbsp;&nbsp;\n";
        $this->salida .= "  </div>\n";
        $this->salida .= "</center>\n";
        $this->salida .= themeCerrarTabla();
        return true;
    }
    
     function FrmPieDePaginaPanelDietas2($datos_estacion)
    {
	     $href    = ModuloGetURL('app','EE_SolicitudDietas','user','FrmPanelEstacion',array('datos_estacion'=>$datos_estacion));

        $this->salida .= "  <div class='normal_10' align='center'><br>\n";
        $this->salida .= "    <a href='$href'>VOLVER</a>\n";
        $this->salida .= "    &nbsp;&nbsp;-&nbsp;&nbsp;\n";
        $this->salida .= "  </div>\n";
        $this->salida .= "</center>\n";
        $this->salida .= themeCerrarTabla();
        return true;
    }
   

		/**
		*
		*/
		function GetHtmlDietas($vect,$TipoId)
		{
                    //echo "aaa'".$TipoId."'wwsw";
                    foreach($vect as $value=>$titulo)
					{
							if($titulo[hc_dieta_id]==$TipoId){
										$this->salida .=" <option align=\"center\" value=\"$titulo[hc_dieta_id]\" selected>$titulo[descripcion]</option>";
							}else{
										$this->salida .=" <option align=\"center\" value=\"$titulo[hc_dieta_id]\">$titulo[descripcion]</option>";
							}
					}
					return $titulo[hc_dieta_id];
		}
		/**
		*
		*/
		function FrmDietasPaciente($datos_paciente,$datos_estacion)
		{       
                //print_r($datos_estacion);
				unset($_SESSION['DIETAS']['FECHAREG']);
				$_SESSION['DIETAS']['FECHAREG']=date("Y-m-d");
				$sw_cierreXhoraAdicional='0';
				$sw_cierreXhora='0';
				$tipo = $_REQUEST['tipo_solicitud'];
				$solicitud_sgte_dia_desayuno = '';
				$solicitud_sgte_dia_almuerzo = '';
				$solicitud_sgte_dia_comida = '';
				
				if (empty($tipo))
				{
					$tipo='desayuno';
				}
				
				$horacierre=$this->ConsultaHorraCierre($tipo);
				$cierreXhora=$this->AnalizaHorasCierre($horacierre,"normal");
				$dietacerrada=$this->ConsultaCierreDieta($datos_paciente['DATOS_PACIENTE']['ingreso'],$tipo,$datos_estacion['estacion_id']);
				if($cierreXhora=='0')
				{
					$sw_cierreXhora='1';
					$mensaje_cierre="AGOTADO HORARIO NORMAL";
					$cierreXhoraAdicional=$this->AnalizaHorasCierre($horacierre,"adicional");
					if($cierreXhoraAdicional=='0')
					{
                        
                        $sw_cierreXhoraAdicional='1';
						$mensaje_cierre.=" Y ADICIONAL";
						$_SESSION['DIETAS']['FECHAREG']=date("Y-m-d",strtotime("+1 day"));
					}
                    elseif($cierreXhoraAdicional=='1')
                    {
                        $verdadero_adiciional="1";
                        $mensaje_cierre="HORARIO ADICIONAL";
                    }
				}
                elseif($cierreXhora=='1')
                {
                    $mensaje_cierre="HORARIO NORMAL";
                }
				$mensaje_cierre.=" PARA ASIGNAR UNA DIETA";
				
				if($tipo == 'desayuno')
				{
					$color_desayuno="#FFDDDD";
					$color_almuerzo="#D6DFF7";
					$color_comida="#D6DFF7";
					if($sw_cierreXhoraAdicional=='1'){
						$solicitud_sgte_dia_desayuno = ' (siguiente dia)';
					}
				}
				elseif($tipo == 'almuerzo')
				{
					$color_desayuno="#D6DFF7";
					$color_almuerzo="#FFDDDD";
					$color_comida="#D6DFF7";
					if($sw_cierreXhoraAdicional=='1'){
						$solicitud_sgte_dia_almuerzo = ' (siguiente dia)';
					}
				}
				else
				{
					$color_desayuno="#D6DFF7";
					$color_almuerzo="#D6DFF7";
					$color_comida="#FFDDDD";
					if($sw_cierreXhoraAdicional=='1'){
						$solicitud_sgte_dia_comida = ' (siguiente dia)';
					}
				}
				$estado=$this->ConsultaEstadodieta($tipo,$datos_paciente['DATOS_PACIENTE']['ingreso'],$datos_estacion['estacion_id']);
				if($estado=='1')
				{
					$mensaje_dieta="DIETA ASIGNADA EN LA ESTACION";
					$datos_paciente=$this->ConsultaDietasEnfermeria($tipo,$datos_paciente,$datos_estacion['estacion_id']);

                    //print_r($datos_paciente);
                     $href=ModuloGetURL('app','EE_SolicitudDietas','user','UpdateDieta',array('DATOS_PACIENTE'=>$datos_paciente,'tipo_solicitud'=>$tipo,'cierre_adicional'=>$verdadero_adiciional,'datos_estacion'=>$datos_estacion));
				}
				else
				{//datos del medico
					$mensaje_dieta="DIETA SUGERIDA POR EL MEDICO - NO ASIGNADA -";
					$href=ModuloGetURL('app','EE_SolicitudDietas','user','InsertaDieta',array('DATOS_PACIENTE'=>$datos_paciente,'tipo_solicitud'=>$tipo,'cierre_adicional'=>$verdadero_adiciional,'datos_estacion'=>$datos_estacion));
				}
				//echo $href;
				if(($_REQUEST['tipodieta']!=''))
				{
					$tipo_dieta=$_REQUEST['tipodieta'];//echo "2004";
                }
				else
                {
					$tipo_dieta=$datos_paciente['DATOS_DIETAS']['dieta_id'];//echo "2005";
				}

				if(!empty($_REQUEST['fraccionada']))
                {
					$fraccionada=$_REQUEST['fraccionada'];
                }
				else
                {
					$fraccionada = $datos_paciente['DATOS_PACIENTE']['sw_fraccionada'];
				}
				
				//cargamos variables de validacion
				$caracteristicas_vec = $datos_paciente['DATOS_CARACTERISTICAS'];
				$dietas=$this->GetControlDietas();
				$informacion=$this->TraerInformacionAyuno($datos_paciente['DATOS_PACIENTE']['ingreso']);//horario del ayuno
				$NVO=$this->GetNadaViaOral();
				
				if($informacion!='')
				{
                    $check='checked';
                }
                else
                {
                    $check='';
                }

                $observacion1=$informacion[0];
				$hora=$informacion[1];
				$hora_inicio=$informacion[2];
				$observacion=$datos_paciente['DATOS_PACIENTE']['observaciones'];
		

                /////aqui van los datos de paciente/////////////

                $this->salida .= "      <br>\n";
                $this->salida .= "      <table class=\"modulo_table_list\" align='center' width=\"80%\">\n";
                $this->salida .= "          <tr class='modulo_table_list_title' align=\"center\">";
                $this->salida .= "              <td>";
                $this->salida .= "                  IDENTIFICACION PACIENTE";
                $this->salida .= "              </td>";
                $this->salida .= "              <td>";
                $this->salida .= "                  NOMBRE DEL PACIENTE";
                $this->salida .= "              </td>";
                $this->salida .= "              <td>";
                $this->salida .= "                  INGRESO";
                $this->salida .= "              </td>";
                $this->salida .= "              <td>";
                $this->salida .= "                  PIEZA";
                $this->salida .= "              </td>";
                $this->salida .= "              <td>";
                $this->salida .= "                  CAMA";
                $this->salida .= "              </td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          <tr class=\"modulo_list_claro\" align=\"center\">";
                $this->salida .= "              <td class=\"Normal_10AN\">";
                $this->salida .= "              ".$datos_paciente['DATOS_PACIENTE']['tipo_id_paciente']."-".$datos_paciente['DATOS_PACIENTE']['paciente_id']."";
                $this->salida .= "              </td>";
                $this->salida .= "              <td class=\"Normal_10AN\" >";
                $this->salida .= "              ".$datos_paciente['DATOS_PACIENTE']['nombre_completo'];
                $this->salida .= "              </td>";
                $this->salida .= "              <td class=\"Normal_10AN\" >";
                $this->salida .= "              ".$datos_paciente['DATOS_PACIENTE']['ingreso'];
                $this->salida .= "              </td>";
                $this->salida .= "              <td class=\"Normal_10AN\" >";
                $this->salida .= "              ".$datos_paciente['DATOS_PACIENTE']['pieza'];
                $this->salida .= "              </td>";
                $this->salida .= "              <td class=\"Normal_10AN\" >";
                $this->salida .= "              ".$datos_paciente['DATOS_PACIENTE']['cama'];
                $this->salida .= "              </td>";
                $this->salida .= "          </tr>";
                $this->salida .= "       </table>";
                ///////////////////////////////////////////////



                $this->salida .= "		<table align='center' width=\"80%\">\n";
				$this->salida .= "			<tr class=\"label_error\" align=\"center\">";
				$this->salida .= "				<td >$mensaje_dieta";
				$this->salida .= "				</td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr class=\"label_mark\" align=\"center\">";
				$this->salida .= "				<td >LA HORA DE CIERRE PARA EL(LA):  ".strtoupper($tipo)."  ES A LAS : ".$horacierre['hora_cierre']."";
				$this->salida .= "				</td>";
				$this->salida .= "			</tr>";
				if($dietacerrada=='1')
                {
					$this->salida .= "			<tr class=\"label_error\" align=\"center\">";
					$this->salida .= "				<td >SOLICITUD DE DIETA CERRADA";
					$this->salida .= "				</td>";
					$this->salida .= "			</tr>";
				}
                if($sw_cierreXhora=='1')
                {
					$this->salida .= "			<tr class=\"label_error\" align=\"center\">";
					$this->salida .= "				<td > $mensaje_cierre ";
					$this->salida .= "				</td>";
					$this->salida .= "			</tr>";
                }
                else
                {
                    $this->salida .= "          <tr class=\"normal_10AN\" align=\"center\">";
                    $this->salida .= "              <td> $mensaje_cierre ";
                    $this->salida .= "              </td>";
                    $this->salida .= "          </tr>";
                }

                $this->salida .= "		</table>\n";
				
				
				$this->salida .="<form name='Dietas' action=\"".$href."\" method='POST'>";
				$this->salida .= "		<table align='center'>\n";
				$this->salida .= $this->SetStyle("MensajeError",'',1);
				$this->salida .= "		</table>\n";
				
				$href_desalluno=ModuloGetURL('app','EE_SolicitudDietas','user','FrmPanelDietas',array('DATOS_PACIENTE'=>$datos_paciente,'tipo_solicitud'=>'desayuno','datos_estacion'=>$datos_estacion));
				$href_almuerzo=ModuloGetURL('app','EE_SolicitudDietas','user','FrmPanelDietas',array('DATOS_PACIENTE'=>$datos_paciente,'tipo_solicitud'=>'almuerzo','datos_estacion'=>$datos_estacion));
				$href_comida=ModuloGetURL('app','EE_SolicitudDietas','user','FrmPanelDietas',array('DATOS_PACIENTE'=>$datos_paciente,'tipo_solicitud'=>'comida','datos_estacion'=>$datos_estacion));
					
				$this->salida .= "<table width='60%' align='center' border='0' class='modulo_table_list'>";
				$this->salida .= "	<tr>";
				$this->salida .= "		<td width='20%'bgcolor=$color_desayuno class='titulo3'>";
				$this->salida .= "    	<a href='$href_desalluno'>Desayuno $solicitud_sgte_dia_desayuno</a>\n";
				$this->salida .= "		</td>";
				$this->salida .= "		<td width='20%'bgcolor=$color_almuerzo class='titulo3'>";
				$this->salida .= "    	<a href='$href_almuerzo'>Almuerzo $solicitud_sgte_dia_almuerzo</a>\n";
				$this->salida .= "		</td>";
				$this->salida .= "		<td width='20%'bgcolor=$color_comida class='titulo3'>";
				$this->salida .= "    	<a href='$href_comida'>Comida $solicitud_sgte_dia_comida</a>\n";
				$this->salida .= "		</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "</table>";
				
				$java = "\n<script language='javascript'>\n";
				$java .="function Caracteristica(valor){\n";
				$java .="window.location.href='".ModuloGetURL('app','EE_SolicitudDietas','user','FrmPanelDietas',array("DATOS_PACIENTE"=>$datos_paciente,"tipo_solicitud"=>$tipo,'datos_estacion'=>$datos_estacion))."&tipodieta='+valor;\n";
				$java .="}\n";
				
				$java .="function Desabilitar(obj_valor){\n";
				$java .="window.location.href='".ModuloGetURL('app','EE_SolicitudDietas','user','FrmPanelDietas',array("DATOS_PACIENTE"=>$datos_paciente,"tipo_solicitud"=>$tipo,'datos_estacion'=>$datos_estacion))."&nada_oral='+obj_valor;\n";
				$java .="}\n";
				 
				$java .="function Desabilitar2(formaH,i){\n";
				$java .="for(j=0;j<=i;j++){ \n";
				$java .="if(formaH.radio[j].disabled == false){\n";
				$java .="formaH.radio[j].disabled = true;  }else{\n";
				$java .="formaH.radio[j].disabled = false}}\n";
				$java .="}\n";
				
				$java .="function RecargaTipo(valor){\n";
				$java .="window.location.href='".ModuloGetURL('app','EE_SolicitudDietas','user','FrmPanelDietas',array("datos_paciente"=>$datos_paciente,"tipo_solicitud"=>$tipo))."&tipo='+valor;\n";
				$java .="}\n";
				
				$java .="</script>\n";
				$this->salida .= $java;
				$this->salida .= "<table width='60%' align='center' border='1' class='modulo_table_list'>";
				$this->salida .= "   <tr>\n";
				$this->salida .= "			<td width='100%' colspan=\"2\" class='modulo_table_list_title'>DIETAS DEL PACIENTE PARA EL DIA ".date("d-m-Y")."</td>\n";
				//ECHO "NADA ORAL".$_REQUEST['nada_oral']."TIPO DE DIETA".$tipo_dieta;
                if($_REQUEST['nada_oral'] == 'nada1')
                {
                         $tipo_dieta = -1;
                        $enabled = '';
                        $checked = '';
                        
                }
                elseif($_REQUEST['nada_oral'] == 'nada' OR ($tipo_dieta == $NVO AND $NVO != $_REQUEST['nada_oral']))
				{ 
						$enabled = 'disabled';
						$checked = 'checked';
				}
				else
                {
                    $enabled = 'enabled';
                }
				$this->salida .= "<tr class=\"hc_list_oscuro\">\n";
				if($_REQUEST['nada_oral'] == 'nada' OR ($tipo_dieta == $NVO AND $NVO != $_REQUEST['nada_oral']))
                {
                    $this->salida .= "<td width='50%' class=\"label\"><input type=\"checkbox\" name=\"nada_oral\" value=\"nada1\" onclick=\"Desabilitar(this.value)\" $checked>&nbsp;NADA VIA ORAL</td>\n";
                }
                else
                {
 					$this->salida .= "<td width='50%' class=\"label\"><input type=\"checkbox\" name=\"nada_oral\" value=\"nada\" onclick=\"Desabilitar(this.value);\" >&nbsp;NADA VIA ORAL</td>\n";
 				}


																								
				if($fraccionada=='1')
				{
                    $this->salida .= "<td class=\"label\"><input type=\"checkbox\" name=\"fraccionada\" value=\"1\" checked $enabled>&nbsp;FRACCIONADA (6 Porciones)";
                }
                else
				{
                    $this->salida .= "<td class=\"label\"><input type=\"checkbox\" name=\"fraccionada\" value=\"1\" $enabled>&nbsp;FRACCIONADA (6 Porciones)";
                }
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
                $this->salida .= "<tr class=\"hc_list_oscuro\">\n";
				$this->salida .= "<td COLSPAN='2' width='50%' class=\"label\">TIPOS DE DIETAS:&nbsp;&nbsp;&nbsp;";
				$this->salida .= "&nbsp;&nbsp;&nbsp;<select name=\"tipodieta\" class=\"select\" onchange=\"Caracteristica(this.value)\" $enabled>";
                if($_REQUEST['nada_oral'] == 'nada' OR ($tipo_dieta == $NVO AND $NVO != $_REQUEST['nada_oral']))
                {
                   $this->GetHtmlDietas($dietas,$NVO);
                }
                ELSE
                {
                    $this->GetHtmlDietas($dietas,$tipo_dieta);
                    //$this->salida .="   <option value= \"-1\" >NINGUNA</option>";
                }
                
				
				$this->salida .= "</select>";
				$this->salida .= "</td>\n";
                

				$this->salida .= "</tr>\n";
	
				
				//if(empty($tipo_dieta))
				if(($tipo_dieta=='-1')||($tipo_dieta==''))
				{
                    $valor = 0;
                    $tipo_dieta=0;
                }
				else
				{
                    $valor = $tipo_dieta;
                }
                $dietas_Caracteristicas = $this->GetDietas_Caracteristicas($valor,1);

                

                if(($tipo_dieta!='')&&($tipo_dieta!='-1'))
 				{
                    $this->salida .= "<tr class=\"hc_list_oscuro\">\n";
                    $this->salida .= "<td width='50%' class=\"label\">";
                    $this->salida .= "  <label class='label'>CARACTERISTOCAS DE LA DIETA</label>";
// 					if(!empty($dietas_Caracteristicas))
// 					{
// 							$this->salida .= "<table class=\"hc_list_oscuro\" width='100%' border=\"1\">";
// 							for($j=0;$j<sizeof($dietas_Caracteristicas);$j++)
// 							{    
// 										$checked = '';
// 										if(!empty($caracteristicas_vec))
// 										{
// 												for($count=0;$count<sizeof($caracteristicas_vec); $count++)
// 												{ 
// 															if($caracteristicas_vec[$count][caracteristica_id] == $dietas_Caracteristicas[$j][caracteristica_id])
// 															{
//                                                                 $checked = 'checked';
//                                                             }
// 												}
// 										}
// 										$this->salida .= "<tr class=\"hc_list_oscuro\">";
// 										$this->salida .= "<td class=\"label\"><input type=\"checkbox\" name=\"caracteristica_dieta[]\" value=\"".$dietas_Caracteristicas[$j][caracteristica_id]."\" $checked $enabled>&nbsp;".$dietas_Caracteristicas[$j][descripcion]."";
// 										$this->salida .= "</td>";
// 										$this->salida .= "</tr>";
// 							}
// 							$this->salida .= "</table>";
// 					}
					$this->salida .= "</td>\n";
	
					$only_Caracteristicas = $this->GetDietas_Caracteristicas($valor,1);
                    //var_dump($only_Caracteristicas);
                    //var_dump($caracteristicas_vec);
                    $this->salida .= "<td width='50%' class=\"label\">";
					if(!empty($only_Caracteristicas))
					{
					   $aa = 0;
							$this->salida .= "<table class=\"hc_list_oscuro\">";
							for($x=0;$x<sizeof($only_Caracteristicas);$x++)
							{
								$checked = '';
										if(!empty($caracteristicas_vec))
										{
                                                
                                                $habilitar = '';
												//for($count=0;$count<sizeof($caracteristicas_vec); $count++)
												foreach($caracteristicas_vec AS $carac_vec => $vec)
												{ 
															//if($caracteristicas_vec[$count][caracteristica_id] == $only_Caracteristicas[$x][caracteristica_id])
															if($vec[caracteristica_id] == $only_Caracteristicas[$x][caracteristica_id])
															{
                                                                $checked = 'checked';
                                                            }
															if(empty($vec[caracteristica_id]))
                                                            {
                                                                $habilitar = 'disabled';
                                                            }
												}
                                                
										}
                                        else
										{
                                            $habilitar = 'disabled';
                                        }
										if(!empty($only_Caracteristicas[$x][codigo_agrupamiento]))
										{
										  $aa++;
                                          //echo $only_Caracteristicas[$x][codigo_agrupamiento]."----".$only_Caracteristicas[$x -1][codigo_agrupamiento];
                                          
												if($only_Caracteristicas[$x][codigo_agrupamiento] != $only_Caracteristicas[$x -1][codigo_agrupamiento])
												{
                                                        $this->salida .= "<tr class=\"hc_list_oscuro\">";
                                                        $this->salida .= "<td class=\"label\"><input type=\"checkbox\" name=\"sel[]\" onclick=\"Desabilitar2(document.Dietas,$aa)\" $checked $enabled>".$only_Caracteristicas[$x][descripcion]."";
                                                        $this->salida .= "</td>";
                                                        $this->salida .= "</tr>";
												}
			                                 
												$this->salida .= "<tr class=\"hc_list_oscuro\">";
												$this->salida .= "<td class=\"label\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" id=\"radio\" name=\"caracteristica_dieta[]\" value=\"".$only_Caracteristicas[$x][caracteristica_id]."\" $habilitar $checked onclick=\"\" $enabled>&nbsp;".$only_Caracteristicas[$x][descripcion_agrupamiento]."";
												$this->salida .= "</td>";
												$this->salida .= "</tr>";
										}
										else
										{

                                                if($x==0)
                                                {
                                                    $this->salida .= "<tr class=\"hc_list_oscuro\">";
                                                    $this->salida .= "<td class=\"label\"><input type=\"checkbox\" name=\"caracteristica_dieta[]\" value=\"".$only_Caracteristicas[$x][caracteristica_id]."\" checked $enabled>&nbsp;".$only_Caracteristicas[$x][descripcion]."";
                                                    $this->salida .= "</td>";
                                                    $this->salida .= "</tr>";

                                                }

                                                else
                                                {
                                                    $this->salida .= "<tr class=\"hc_list_oscuro\">";
                                                    $this->salida .= "<td class=\"label\"><input type=\"checkbox\" name=\"caracteristica_dieta[]\" value=\"".$only_Caracteristicas[$x][caracteristica_id]."\" $checked $enabled>&nbsp;".$only_Caracteristicas[$x][descripcion]."";
                                                    $this->salida .= "</td>";
                                                    $this->salida .= "</tr>";
                                                }    
										}
                            }
							$this->salida .= "</table>";
					}
                    $this->salida .= "</td>\n";
                    $this->salida .= "</tr>\n";
                }

				
				$this->salida .= "<tr class=\"hc_list_claro\">\n";
				$this->salida .= "<td class=\"label\" align=\"center\" colspan=\"2\">OSERVACION GENERAL DE LA DIETA";
				$this->salida .= "</td>\n";
				$this->salida .= "</tr>\n";
				$this->salida .= "<tr>\n";
				$this->salida .= "<td class=\"hc_list_claro\" colspan=\"2\" align=\"center\">";
				$this->salida .= "<textarea class='textarea' name='CtlDietasObs' cols='55' style=\"width:100%\"rows='3' $enabled>".$observacion."</textarea>";
				$this->salida .= "</td>\n";
				$this->salida .= "</tr>\n";
				
				/********** AYUNO*******/
				$this->salida .= "<tr>\n";
				$this->salida .= "<td colspan=\"2\" class=\"hc_list_claro\">\n";
                                   
				$this->salida .= "<table width='100%' border='2' >\n";
				$this->salida .= "<tr><td width='10%' align='center'>";
				$this->salida .= "<label width='50%' class='label'>Ayuno</label>&nbsp;&nbsp;<input type='checkbox' value=\"1\" $check name='CtlAyuno' $enabled>";
				$this->salida .= "</td>";
                $this->salida .= "<td align='center'>";
                $this->salida .= " <LABEL class='label'>Motivo Ayuno</LABEL> <br> <textarea class='textarea' name='CtlDietasObsA' cols='50' rows='2' $enabled>$observacion1</textarea>";
                $this->salida .= "</td>\n";
				/*****************************************************/
				$this->salida .= "<td width='20%' align='center'><label class='label'>Hora Inicial&nbsp;&nbsp;</label><select name='horainicio' class=\"select\" $enabled>";
				for($i=6;$i<24;$i++)
				{
					if($i<10){$s=0;}else{$s='';}
					$a=$s.$i;
					$a.=":30";
					if($a!=$hora_inicio)
					{
						$this->salida .= "<option value=\"$a\">$a</option>";
					}
					else
					{
						$this->salida .= "<option value=\"$a\" selected>$a</option>";
					}
					$a='';
				}
				$this->salida .= "</select></td>";
				/*****************************************************/
				$this->salida .= "<td width='20%' align='center'><label class='label'>Hora final&nbsp;&nbsp;</label><select name='horafin' class=\"select\" $enabled>";
				for($i=6;$i<24;$i++)
				{
					if($i<10){$s=0;}else{$s='';}
					$a=$s.$i;
					$a.=":30";
					if($a!=$hora)
					{
						$this->salida .= "<option value=\"$a\">$a</option>";
					}
					else
					{
						$this->salida .= "<option value=\"$a\" selected>$a</option>";
					}
					$a='';
				}
				$this->salida .= "</select></td></tr></TABLE>\n";
                $this->salida .= "<TABLE border='1'>\n";//class=\"label\"
                //$this->salida .= "<td  align=\"center\" colspan=\"3\"><LABEL class='label'>MOTIVO AYUNO</LABEL>";
                //$this->salida .= "</td>\n";
                //$this->salida .= "</tr>\n";
                //$this->salida .= "<tr><td colspan='' width='100%' align='center'>";
                //$this->salida .= "<LABEL class='label_error'>MOTIVO AYUNO</LABEL> ";
                //$this->salida .= "</td>\n";
                //$this->salida .= "<tr> <td align='center'>";
                //$this->salida .= " <textarea class='textarea' name='CtlDietasObsA' cols='55' rows='6' $enabled>$observacion1</textarea>";
                //$this->salida .= "</td></tr>\n";
                if($estado=='1')
                {
                    $this->salida .= "<tr><td width='25%' align='left'>";
                    
                    
                    //VAR_DUMP($datos_paciente['DATOS_DIETAS']['estado_dieta']);
                    if($datos_paciente['DATOS_DIETAS']['estado_dieta']=='1' || $datos_paciente['DATOS_DIETAS']['estado_dieta']=='2' )
                    {
                        $this->salida .= "<input type='hidden' name='valorx' value='0'>";
                        $this->salida .= "<input type='hidden' name='DESACTIVA' value='1'>";
                        $this->salida .= "<label class='label_error'>CANCELAR DIETA</label>&nbsp;&nbsp;<input type='checkbox' value=\"45\" name='cancela_dieta' onclick='CancelarDieta(document.Dietas.valorx.value);'>";
                    }
                    elseif($datos_paciente['DATOS_DIETAS']['estado_dieta']=='0')
                    {
                        $this->salida .= "<input type='hidden' name='valorx' value='1'>";
                        $this->salida .= "<input type='hidden' name='DESACTIVA' value='0'>";
                        $this->salida .= "<label class='label_error'>CANCELADA</label>&nbsp;&nbsp;<input type='checkbox' value=\"45\" name='cancela_dieta' onclick='CancelarDieta(document.Dietas.valorx.value);' checked >";
                    }
                    
                    $this->salida .= "</td>";
                    $this->salida .= "<td width='75%' align='left'>";
                    if($datos_paciente['DATOS_DIETAS']['estado_dieta']=='0')
                    {
                        $this->salida .= " <LABEL class='label'>MOTIVO CANCELACION DIETA</LABEL> <textarea class='textarea' name='Mot_Cancelacion_Diet' cols='50' rows='2' disabled>".$datos_paciente['DATOS_DIETAS']['motivo_cancelacion_dieta']."&nbsp;&nbsp;&nbsp;USUARIO: ".$datos_paciente['DATOS_DIETAS']['usuario_id_cancelacion']."</textarea>";
                    }
                    else
                    {
                        $this->salida .= " <LABEL class='label'>MOTIVO CANCELACION DIETA</LABEL> <textarea class='textarea' name='Mot_Cancelacion_Diet' cols='50' rows='2' disabled></textarea>";

                    }
                    $this->salida .= "</td></tr>";
                }
				$this->salida .= "</table>\n";
				/********** FIN AYUNO*****/
				$this->salida .= "</td>\n";
				$this->salida .= "</tr>\n";

				//if(($dietacerrada=='0')&&($sw_cierreXhoraAdicional=='0')){
				if(($dietacerrada=='0')){
					$this->salida .= "<tr>\n";
					$this->salida .= "<td class=\"hc_list_claro\" colspan=\"2\" align=\"center\">";
					$this->salida .= "<input class='input-submit' type='button' name='Save' value='GUARDAR' onclick='GaurdarDieta();'>";
					$this->salida .= "</td>\n";
					$this->salida .= "</tr>\n";
				}
				$this->salida .= "	</table>\n";
															
				$this->salida .= "	</form>\n";
                $this->salida .= " <script>\n";
                $this->salida .= "   function CancelarDieta(a)\n";
                $this->salida .= "   { \n";
                $this->salida .= "     if(a==0)";
                $this->salida .= "      {";
                $this->salida .= "          document.Dietas.Mot_Cancelacion_Diet.disabled=false;";
                $this->salida .= "          document.Dietas.Save.value='CANCELAR DIETA';";
                $this->salida .= "          document.Dietas.valorx.value='1';";
                $this->salida .= "          document.Dietas.DESACTIVA.value='0';";
                $this->salida .= "      } ";
                $this->salida .= "      else";
                $this->salida .= "      { ";
                $this->salida .= "          if(a==1)";
                $this->salida .= "          {";
                $this->salida .= "              document.Dietas.Mot_Cancelacion_Diet.disabled=true;";
                $this->salida .= "              document.Dietas.Save.value='GUARDAR';";
                $this->salida .= "              document.Dietas.valorx.value='0';";
                $this->salida .= "              document.Dietas.DESACTIVA.value='2';";
                $this->salida .= "          } ";
                $this->salida .= "      } ";
                $this->salida .= "   }\n";
                $this->salida .= "  ";
                $this->salida .= "   function GaurdarDieta()\n";
                $this->salida .= "   {\n";
                $this->salida .= "     if(document.Dietas.tipodieta.value==-1)";
                $this->salida .= "      {";
                $this->salida .= "          alert('ANTES DE GUARDAR DEBE ELEGIR UN TIPO DE DIETA');";
                $this->salida .= "      } ";
                $this->salida .= "      else";
                $this->salida .= "      {";
                $this->salida .= "          document.Dietas.submit();";
                $this->salida .= "      } ";
                $this->salida .= "   }\n";
                $this->salida .= " </script>\n";
        return true;
		}
}//fin de la clase

?>

