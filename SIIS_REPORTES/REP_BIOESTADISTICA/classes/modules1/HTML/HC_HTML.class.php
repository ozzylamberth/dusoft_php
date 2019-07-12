<?php
// HC_HTML.class.php  23/02/2005
// ----------------------------------------------------------------------
// Copyright (C) 2005 IPSOFT SA.
// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: clase de vista para el manejo de HC
// $Id: HC_HTML.class.php,v 1.21 2007/01/09 16:52:46 tizziano Exp $
// ----------------------------------------------------------------------

class HC_HTML extends ManejadorDeHC
{
     /**
     * Constructor
     */
     var $ocultarmenu;
               
     function HC_HTML()
     {
          $this->ManejadorDeHC();
          return true;
     }//Fin Constructor
     
     /**
     * Metodo utilizado por ReturnModulo para mostrar la HC en modo Ingreso de
     */
     function Inicializar()
     {
          //Cargar el entorno de HC
          if(!($this->Enviroment())) return false;
     
          //Construir o Recuperar el Vector de SESSION de la HC
          if(!($this->ConstruirDatosHC())) return false;
          
          //Ubicar el paso en el que esta la HC
          if(!($this->GetPasoHC())) return false;
          
          $this->ocultarmenu = $this->GetParametrosAdicionales();
          
          //Revisar si hay un cambio de estado en el listado "Mostrar Ocultos"
          if(array_key_exists('HC_MOSTRAR_SUBMODULOS_OCULTOS',$_REQUEST))
          {
               $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['HC_MOSTRAR_SUBMODULOS_OCULTOS'] = $_REQUEST['HC_MOSTRAR_SUBMODULOS_OCULTOS'];
          }
                                             
          //Establecer el estado de mostrar modulos ocultos
          $this->mostrarSubmodulosOcultos = $_SESSION['HC_EVOLUCION'][$this->usuarioConsultante][$this->datosEvolucion['evolucion_id']]['HC_MOSTRAR_SUBMODULOS_OCULTOS'];
          $clase = "CapaHc";
          if($this->ocultarmenu == 'true') 
					{
						$clase = "CapaHc1";
						if(eregi("MSIE",$_SERVER["HTTP_USER_AGENT"])) $clase = "CapaHc1IE";
					}
          
					$salto = "";
					if(eregi("MSIE",$_SERVER["HTTP_USER_AGENT"])) $salto = "<br>";
					
          if($this->paso >= 1 && $this->paso <= ($this->numPasos[0]+$this->numPasos[1]))
          {
               $this->IncludeCabeceraHC1();
               $this->ArmarMenuDesplegableHC();
               $this->salida .= "	$salto\n";
               $this->salida .= "	<div class=\"$clase\" id=\"CapaHc\"> \n";
               $this->salida .= $this->IncludePasoHC();
               $this->salida .= "	</div> \n";
               return true;
          }
          else
          {
               switch($this->paso)
               {
                    case -1:
                    {
                         $this->IncludeCabeceraHC1();
                         $this->ArmarMenuDesplegableHC();
												 $this->salida .= "	$salto\n";
                         $this->salida .= "	<div class=\"$clase\" id=\"CapaHc\"> \n";
                         $this->salida .= $this->ListadoInicioHC();
	                    $this->salida .= "	</div> \n";
                         unset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);
					break;
                    }//Fin Caso -1
                    
                    case "historia":
                    {
                         $this->IncludeCabeceraHC1();
                         $this->ArmarMenuDesplegableHC();
												 $this->salida .= "	$salto\n";
                         $this->salida .= "	<div class=\"$clase\" id=\"CapaHc\"> \n";
                         $this->salida .= $this->HistoriaClinicaCompleta();
                         $this->salida .= "	</div> \n";
                         unset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);
                         break;
                    }//Fin caso historia
                    
                    case "apoyod":
                    {
                         $this->IncludeJS('RemoteScripting');
                         $this->IncludeJS('classes/ResumenAPD/RemoteScripting/misfunciones.js');
                         $dato=$this->HistoriaClinicaResumeApoyod($this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id'],$this->datosEvolucion['evolucion_id'],$this->hc_modulo);
                         if(!empty($dato))
                         {
                              $this->IncludeCabeceraHC1();
                              $this->ArmarMenuDesplegableHC();
															$this->salida .= "	$salto\n";
                              $this->salida .= "	<div class=\"$clase\" id=\"CapaHc\"> \n";
                              $this->salida .= $dato;
                              $this->salida .= "	</div> \n";
                         }
                         unset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);                         
                         break;
                    }//Fin caso apoyod
                    
                    case "pasohc":
                    {
                         $dato = $this->HistoriaClinicaResumeEvolucion($_REQUEST['evolucion_consulta']);
                         if(!empty($dato))
                         {
                              $this->IncludeCabeceraHC1();
                              $this->ArmarMenuDesplegableHC();
															$this->salida .= "	$salto\n";
                              $this->salida .= "	<div class=\"$clase\" id=\"CapaHc\"> \n";
                              $this->salida .= $dato;
                              $this->salida .= "	</div> \n";
                         }
                         unset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);                         
                         break;
                    }
                    
                    case "pasoresumen":
                    {
                         $dato = $this->HistoriaClinicaResumeEvolucion($this->datosEvolucion['evolucion_id']);
                         if(!empty($dato))
                         {
                              $this->IncludeCabeceraHC2();
															$this->salida .= "	$salto\n";
															$this->salida .= "	<div class=\"CapaHc\" id=\"CapaHc\"> \n";
                              $this->salida .= $dato;
                              $this->salida .= "	</div> \n";
                         }
                         unset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);                         
                    	break;
                    }
                    
                    case "pasone":
                    {
                         if (!IncludeFile("classes/notas_enfermeria/notas_enfermeria.class.php"))
                         {
	                         echo "<br>ERROR AL INCLUIR LA CLASE DE \"notas_enfermeria\"<br>";
                         }
                         $fecha = "";
                         if (!empty($_REQUEST['select_fecha']))
                         {
                              $fecha=$_REQUEST['select_fecha'];
                         }
                         $url = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'pasone',0,$this->hc_modulo,$this->hc_modulo,array('ingreso_consulta'=>$_REQUEST['ingreso_consulta']));
                         $notas_e = new notas_enfermeria($_REQUEST['ingreso_consulta'],$fecha,$url);
                         $this->IncludeCabeceraHC1();
                         $this->ArmarMenuDesplegableHC();
												 $this->salida .= "	$salto\n";
                         $this->salida .= "	<div class=\"$clase\" id=\"CapaHc\"> \n";
                         $this->salida .= FormaDiasNE(&$notas_e);
                         $this->salida .= "	</div> \n";
                         unset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);                         
                         break;
                    }
                    
                    case "cerrar":
                    {
                         $this->IncludeCabeceraHC1();
                         $this->ArmarMenuDesplegableHC();
                         
                         if($_REQUEST['descargarVariable'])
                         {
                              unset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']]['CONDUCTA']);                         
                         }
                         $this->salida .= "	$salto\n";
                         $this->salida .= "	<div class=\"$clase\" id=\"CapaHc\"> \n";

                         if($_REQUEST['OBLIGAR_CIERRE_HC']=='SI')
                         {
                              if($this->CerrarHistoria())
                              {
                                   $this->salida = $this->VolverListado($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo'],$this->datosEvolucion['ingreso'],$this->datosEvolucion['evolucion_id']);
                                   unset($_SESSION['HC_DATOS_ADICIONALES'][$this->datosEvolucion['evolucion_id']]);
                                   unset($_SESSION['HC_DATOS_CONTROL'][$this->datosEvolucion['evolucion_id']]);
                                   return true;                       
                              }
                              else
                              {
                                   //todo pantalla con mensaje de error al cerrar la hc
                                   $this->salida = "todo pantalla con mensaje de error al cerrar la hc";
                                   return true;
                              }
                         }
                         
                         $conducta = $this->GetConducta();
                         $datos = $this->GetTiposConductas();
                         
                         if($conducta === false)//si todavia no se ha seleccionado una conducta
                         {
                              if($datos === false)
                              {
                                   if(empty($this->error))
                                   {
                                        $this->error = "ERROR en la Clase HC_HTML";
                                        $this->mensajeDeError = "Error retornado por el metodo GetTiposConductas";
                                        $this->fileError = __FILE__;
                                        $this->lineError = __LINE__;                
                                   }
                                   return false;
                              }
                              elseif(is_array($datos))
                              {
                                   if(sizeof($datos)==1)
                                   {
                                        $this->SetConducta(key($datos));
                                   }
                                   else
                                   {
                                        $this->salida .= $this->FrmSeleccionarConducta(&$datos);
                                        $this->salida .= "	</div> \n";
                                   }
                              }
                              else
                              {
                              	//Si no tiene configurada Conducta.
                                   $this->SetConducta(-1);
                              }
                         }

                         if($conducta != -1)
                         {
                              if($this->tiposConductas[$conducta]['sw_pedir_submodulo_obligatorios'] === "1")
                              {
                                   $salida_submodulo = $this->ExigirSubmodulosObligatorios();
                                   if($salida_submodulo!="OK")
                                   {
                                        $this->salida .= $salida_submodulo;
                                        $this->salida .= "	</div> \n";
                                        return true;
                                   }
                              }
                              
                              $salida_submodulo = $this->ExigirSubmodulosConducta($conducta);
                              if($salida_submodulo!="OK")
                              {
                                   $this->salida .= $salida_submodulo;
                                   $this->salida .= "	</div> \n";
                                   return true;
                              }
                         }

                         if(($salida_submodulo=$this->CerrarHistoria()) === false)
                         {
                              if(empty($this->error))
                              {
                                   $this->error = "ERROR en la Clase HC_HTML";
                                   $this->mensajeDeError = "Error retornado por el metodo CerrarHistoria";
                                   $this->fileError = __FILE__;
                                   $this->lineError = __LINE__;                
                              }
                              return false;          
                         }

                         $this->salida = $this->VolverListado($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo'],$this->datosEvolucion['ingreso'],$this->datosEvolucion['evolucion_id']);
                         unset($_SESSION['HC_DATOS_ADICIONALES'][$this->datosEvolucion['evolucion_id']]);
                         unset($_SESSION['HC_DATOS_CONTROL'][$this->datosEvolucion['evolucion_id']]);
                         return true;
                         break;
                    }//Fin caso cerrar
                    default :
                    {
                         $this->error = "ERROR en la Clase HC_HTML";
                         $this->mensajeDeError = "Paso no valido";
                         $this->fileError = __FILE__;
                         $this->lineError = __LINE__; 
                    } 
               }
          }
          return true;
     }
     
     /**
     * Funcion que me permite recorrer los submodulos que son obligatorios para 
     * el Cierre de la HC
     */
     function ExigirSubmodulosObligatorios()
     {    
          foreach($this->hc_submodulos_obligatorios as $k=>$submodulo)
          {            
               $submodulo_obj=IncluirSubModuloHC($submodulo['submodulo']);
               if(!is_object($submodulo_obj)){
                    $this->error = "No se Pudo cargar el submodulo";
                    $this->mensajeDeError = $submodulo_obj;
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return false;
               }
               
               $prefijo = 'frm_'.$submodulo['submodulo'];
               $titulo = $this->submodulos_info[$submodulo['submodulo']]['titulo_mostrar'];
               $parametros = $this->submodulos_info[$submodulo['submodulo']]['parametros'];
     
               $submodulo_obj->InicializarSubmodulo($this->datosEvolucion,$this->datosAdministrativos,$this->datosPaciente,$this->datosProfesional,$this->datosResponsable,$this->datosAdicionales,"cerrar",$prefijo,$submodulo['submodulo'],$this->datosEvolucion['hc_modulo'],$titulo,$parametros);
               
               if(!$submodulo_obj->GetEstado())
               {
                    $salida_submodulo=$submodulo_obj->GetSalida();
                    if($submodulo_obj->GetEstado())
                    {
                         $salida_submodulo.="<table align='center'>";
                         $salida_submodulo.="<tr>";
                         $salida_submodulo.="<td>";
                         $url=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'cerrar',0,$this->hc_modulo,$this->hc_modulo,array('DESMARCAR'=>1));
                         $salida_submodulo.="<form name='cerrarhistoria' method='post' action='$url'>";
                         $salida_submodulo.="<input type='submit' value='Continuar Cierre de la Atención' name='cerrar' class='input-submit'>";
                         $salida_submodulo.="</form>";
                         $salida_submodulo.="</td>";
                         $salida_submodulo.="</tr>";
                         $salida_submodulo.="</table>";
     
                         if(method_exists($submodulo_obj,'SubmoduloMensaje')){
                              if($submodulo_obj->SubmoduloMensaje()){
                                   $NoMarcar=true;
                              }
                         }
                    }
                    unset($submodulo_obj);
                    if(!$NoMarcar){
                         $_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']][$submodulo['submodulo']]=1;
                    }
                    return $salida_submodulo;
               }
     
               if(!empty($_REQUEST['DESMARCAR']))
               {
                    unset($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']][$submodulo['submodulo']]);
               }
     
               if(!empty($_SESSION['HC']['CIERRE'][$this->datosEvolucion['evolucion_id']][$submodulo['submodulo']]))
               {
                    $salida_submodulo=$submodulo_obj->GetSalida();
                    if($submodulo_obj->GetEstado())
                    {
                         $salida_submodulo.="<table align='center'>";
                         $salida_submodulo.="<tr>";
                         $salida_submodulo.="<td>";
                         $url=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'cerrar',0,$this->hc_modulo,$this->hc_modulo,array('DESMARCAR'=>1));
                         $salida_submodulo.="<form method='post' action='$url'>";
                         $salida_submodulo.="<input type='submit' value='Continuar Cierre de la Atención' name='cerrar' class='input-submit'>";
                         $salida_submodulo.="</form>";
                         $salida_submodulo.="</td>";
                         $salida_submodulo.="</tr>";
                         $salida_submodulo.="</table>";
                    }
                    unset($submodulo_obj);
                    return $salida_submodulo;
               }
               unset($submodulo_obj);
          }
          return "OK";
     }
     
     
     /**
     * Cabecera estatica de la HC
     */
     function IncludeCabeceraHC1()
     {   
					$this->directorioImagenesHC = GetThemePath().'/images/HistoriaClinica1/';
          $this->IncludeJS("CrossBrowser");
					$this->IncludeJS("CrossBrowserDrag");
					$this->IncludeJS("CrossBrowserEvent");
          $this->IncludeJS("RemoteScripting");
          $this->IncludeJS("javascripts/VisibilidadMenuHc.js");
          
					$posicion = "position:fixed;width:100%";
          if(eregi("MSIE",$_SERVER["HTTP_USER_AGENT"]))	
					{
						$posicion = "position:absolute;width:103%";
					}
          //Nueva Version
          $this->salida .= "<script>\n";
          $this->salida .= "	var enabled = !".$this->ocultarmenu.";\n";
          $this->salida .= "	var alphaVisible = ".$this->ocultarmenu.";\n";
          $this->salida .= "	function xWinOnLoad()\n";
          $this->salida .= "	{\n";
          $this->salida .= "		e = xGetElementById('SeccionesHC');\n";
          $this->salida .= "		enabled = ".$this->ocultarmenu.";\n";
          $this->salida .= "	}\n";
          $this->salida .= "	function toggleAlpha()\n";
          $this->salida .= "	{\n";
          $this->salida .= "		var d, m, e, a, i;\n";
          $this->salida .= "		e = xGetElementById('SeccionesHC');\n";
          $this->salida .= "		if (alphaVisible) \n";
          $this->salida .= "		{\n";
          $this->salida .= "			d = 'none';\n";
          $this->salida .= "			m = '0px';\n";
          $this->salida .= "		}\n";
          $this->salida .= "		else \n";
          $this->salida .= "		{\n";
          $this->salida .= "			d = 'block';\n";
          $this->salida .= "			m = '210px';\n";
          $this->salida .= "		}\n";
          $this->salida .= "		e.style.display = d;\n";
          $this->salida .= "		a = xGetElementById('CapaHc');\n";
          $this->salida .= "		a.style.marginLeft = m;\n";
          $this->salida .= "		alphaVisible = !alphaVisible;\n";
          $this->salida .= "		valores(".$this->usuarioConsultante.",".$this->datosEvolucion['evolucion_id'].");\n";
          $this->salida .= "	}\n";
          $this->salida .= "</script>\n";
          
          $acciones[0] = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],-1,0,$this->datosEvolucion['hc_modulo']);
          $acciones[1] = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'historia',0,$this->hc_modulo);
          $acciones[2] = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'cerrar',0,$this->hc_modulo,$this->hc_modulo,array('primera'=>1,'DESMARCAR'=>1, 'descargarVariable'=>true));
          if(!empty($_SESSION['HISTORIACLINICA']['RETORNO']))
          {
               if(!empty($_SESSION['HISTORIACLINICA']['RETORNO']['argumentos']))
               {
          	     $acciones[3]=ModuloGetURL($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo'],$_SESSION['HISTORIACLINICA']['RETORNO']['argumentos']);
               }
               else
               {
               	$acciones[3]=ModuloGetURL($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']);
               }
          }
          else
          {
               $acciones[3]=null;
          }
          $acciones[4] = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'apoyod',0,$this->hc_modulo);
          //Activo el javascript de DatosPaciente para las ventanas emergentes
          $this->SetJavaScripts('DatosPaciente');
          $inforpaciente[0] = RetornarWinOpenDatosPaciente($this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id'],$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido'],'hcPaciente');
          if($this->datosPaciente['edad_paciente']['anos']!="")
          {
               $edad = $this->datosPaciente['edad_paciente']['anos'] .' años, ';
          }
          if($this->datosPaciente['edad_paciente']['meses']!="")
          {
               $edad .= $this->datosPaciente['edad_paciente']['meses'].' meses, ';
          }
          if($this->datosPaciente['edad_paciente']['dias']!="")
          {
               $edad .= $this->datosPaciente['edad_paciente']['dias'].' dias.';
          }
          $this->CalcularPasosDeNavegacionHC();
                         
          $this->salida .= "<!--Barra principal de historia clinica-->\n";
          $this->salida .= "<div class=\"cabecera_hc\" style=\"$posicion\" $width>\n";
          $this->salida .= "	<table width=\"100%\" class=\"TablaHc\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
          $this->salida .= "    <tr>\n";
          $this->salida .= "    	<td height=\"40\" width=\"40\" align=\"center\" >\n";
          $this->salida .= "      	<a href=\"#\"  title=\"Historia Actual\">\n";
          $this->salida .= "        	<img name =\"ImgHistoriaActual\" src=\"".$this->directorioImagenesHC."historia_actual_osc.gif\"  border=\"0\" ";
          $this->salida .= "          	onclick=\"toggleAlpha()\" >\n";
          $this->salida .= "        </a>\n";
          $this->salida .= "      </td>\n";
          $ancho = 0;
          if($this->BotonAtras)
          {
               $this->salida .= "      <td height=\"40\" width=\"%\" align=\"center\">\n";
               $this->salida .= "      	<a href=\"".$this->accionBotonAtras."\" title=\"".$this->titleBotonAtras."\">\n";
               $this->salida .= "        	<img src=\"".$this->directorioImagenesHC."atras_oscuro2.gif\" name = \"ImgAtras\" border=\"0\">\n";
               $this->salida .= "        </a>\n";
               $this->salida .= "      </td>\n";
               $ancho += 5;
          }
          if($this->BotonSiguiente)
          {
               $this->salida .= "      <td height=\"40\" width=\"5%\" align=\"center\">\n";
               $this->salida .= "      	<a href=\"".$this->accionBotonSiguiente."\" title=\"".$this->titleBotonSiguiente."\"  >\n";
               $this->salida .= "        	<img src=\"".$this->directorioImagenesHC."adelante_oscuro2.gif\" name =\"ImgAdelante\"  border=\"0\">\n";
               $this->salida .= "        </a>\n";
               $this->salida .= "     	</td>\n";
               $ancho += 5;
          }
          $this->salida .= "			<td height=\"40\" width=\"5%\" align=\"center\">\n";
          $this->salida .= "      	<a href=\"{$acciones[1]}\" title=\"Historial\" >\n";
          $this->salida .= "        	<img src=\"".$this->directorioImagenesHC."historial_oscura2.gif\" name =\"ImgHistorial\" border=\"0\">\n";
          $this->salida .= "        </a>\n";
          $this->salida .= "      </td>\n";
          $this->salida .= "      <td height=\"40\" width=\"5%\" align=\"center\">\n";
          $this->salida .= "      	<a href=\"{$acciones[4]}\" title=\"Apoyos Diagnosticos\" >\n";
          $this->salida .= "        	<img src=\"".$this->directorioImagenesHC."diagnostico_oscuro2.gif\"  name=\"ImgApoyosDiagnosticos\" border=\"0\">\n";
          $this->salida .= "        <a>\n";
          $this->salida .= "      </td>\n";
          $width = 75 - $ancho;
          $this->salida .= "     	<td width=\"$width%\" align=\"center\">";
          $this->salida .= "      	<table align=\"center\" border=\"0\">\n";
          $this->salida .= "        	<tr>\n";
          $this->salida .= "          	<td class=\"hcPaciente\">".$inforpaciente[0]."</td>";
          $this->salida .= "            <td class=\"hc_normal_10\"><b>Edad: </b>$edad</td>";
          $this->salida .= "          </tr>\n";
          $this->salida .= "          <tr>\n";
          $this->salida .= "          	<td align=\"center\" class=\"hc_normal_10\" colspan=\"2\">";
          $this->salida .= "							<b>Responsable: </b>".$this->datosResponsable['nombre_tercero'].' - '.$this->datosResponsable['plan_descripcion'];
          $this->salida .= "            </td>";
          $this->salida .= "          </tr>\n";
          $this->salida .= "        </table>\n";
          $this->salida .= "			</td>\n";
          $this->salida .= "      <td height=\"40\" width=\"5%\" align=\"center\">\n";
          $this->salida .= "        <a href=\"{$acciones[3]}\" title=\"Volver\">\n";
          $this->salida .= "        	<img src=\"".$this->directorioImagenesHC."volver_claro.gif\" name=\"ImgVolver\" border=\"0\">\n";
          $this->salida .= "        </a>\n";
          $this->salida .= "      </td>\n";
          $this->salida .= "      <td height=\"40\" width=\"5%\" align=\"center\">\n";
          $this->salida .= "      	<a href=\"{$acciones[2]}\" title=\"Cerrar\">\n";
          $this->salida .= "        	<img src=\"".$this->directorioImagenesHC."cerrar_claro.gif\" name =\"ImgCerrar\" border=\"0\">\n";
          $this->salida .= "        </a>\n";
          $this->salida .= "     	</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "	</table>\n";
          $this->salida .= "</div>\n";
          $this->salida .= "<!--Fin Barra principal de historia clinica-->\n";
     }//Fin IncludeCabeceraHC1

     
     /**
     * Método que calcula cual es el paso anterior y cual es el paso siguiente de la HC
     * los atributos de la clase que modifica son:
     *
     * $this->BotonAtras = FALSE;
     * $this->BotonSiguiente = FALSE;
     * $this->accionBotonAtras = "";
     * $this->titleBotonAtras = "";
     * $this->accionBotonSiguiente = "";
     * $this->titleBotonSiguiente = "";
     *
     */
     function CalcularPasosDeNavegacionHC()
     {
          $this->BotonAtras = FALSE;
          $this->BotonSiguiente = FALSE;
          $this->accionBotonAtras = "";
          $this->titleBotonAtras = "";
          $this->accionBotonSiguiente = "";
          $this->titleBotonSiguiente = "";
          //FILTRAR ESTADOS Y PASOS QUE NO DEBEN MOSTRAR EL NAVEGADOR
          if($this->paso < -1 || $this->datosEvolucion['estado'] == 0)
          {
               $this->BotonAtras      = FALSE;
               $this->BotonSiguiente  = FALSE;
               return null;
          }
          if($this->mostrarSubmodulosOcultos)
          {
               $numPasos = $this->numPasos[0]+$this->numPasos[1];
          }
          else
          {
               $numPasos = $this->numPasos[1];
          }
          if($this->paso == -1)
          {
               $this->BotonAtras      = FALSE;
               $this->BotonSiguiente  = TRUE;
          }
          elseif($this->paso >= 1 && $this->paso < $numPasos)
          {
               $this->BotonAtras      = TRUE;
               $this->BotonSiguiente  = TRUE;
          }
          elseif($this->paso == $numPasos)
          {
               $this->BotonAtras      = TRUE;
               $this->BotonSiguiente  = FALSE;
          }
          if($this->BotonAtras)
          {
               $NuevoPaso = $this->paso-1;
               $this->titleBotonAtras = $this->hc_submodulos[$NuevoPaso][0]['TITULO'];
               if($NuevoPaso<1)
               {
                    $NuevoPaso = -1;
                    $this->titleBotonAtras = "LISTADO DE PASOS DE LA HISTORIA CLINICA";
               }
               $this->accionBotonAtras = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],$NuevoPaso);
          }
          else
          {
               $this->accionBotonAtras = "";
               $this->titleBotonAtras = "";
          }
          if($this->BotonSiguiente)
          {
               $NuevoPaso=$this->paso+1;
               if($NuevoPaso<1)
               {
                    $NuevoPaso = 1;
               }
               $this->titleBotonSiguiente = $this->hc_submodulos[$NuevoPaso][0]['TITULO'];
               $this->accionBotonSiguiente = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],$NuevoPaso);
          }
          else
          {
               $this->accionBotonSiguiente = "";
               $this->titleBotonSiguiente = "";
          }
     }//Fin CalcularPasosDeNavegacionHC
     
     /**
     * Metodo para generar el menu desplegable de HC
     * este menu es de 2 nivles
     */
     function ArmarMenuDesplegableHC()
     {	
          $this->directorioImagenesHC = GetThemePath().'/images/HistoriaClinica/';
          $SubmodulosMostrar[1] = &$this->hc_estructura[1];
          $NumeroSubmodulosOcultos = sizeof($this->hc_estructura[0]);
     
          $posicion = "position:fixed";
          if(eregi("MSIE",$_SERVER["HTTP_USER_AGENT"])) {$posicion = "position:absolute";}

          $capas = "var secc = new Array(";//Arreglo javascript
          $flagPrimeraSeccion = true;
                         
          $i=1;
          $llave = $this->paso_info[$this->paso]['hc_seccion_id'] ;
          if(!$llave) $llave = 1;
          
          $display = "display:none";
          if($this->ocultarmenu == 'true') $display = "display:block";
          
          $this->salida .= "<div name=\"SeccionesHC\" id=\"SeccionesHC\" class=\"MenuIzqhc\" style=\"$display;$posicion\">\n";
          foreach($this->hc_secciones as $key => $seccionHC)
          {
               $display = "display:none";
               
               if($llave == $key)
                    $display = "display:block";
               
               $this->salida .= "    	<p class=\"MenuHC\" onclick=\"showhide('Seccion$key');\">\n";
               $this->salida .= "    		<a href=\"#\">$seccionHC</a>\n";
               $this->salida .= "					<div name=\"Seccion$key\" id=\"Seccion$key\" style=\"position:relative;$display;width:180px;\">\n";
               $this->salida .= "						<ul class=\"ListaHc\">\n";
     
               $SubmodulosMostrar[1] = &$this->hc_estructura[1];
               foreach($SubmodulosMostrar as $KeyMostrar=>$VectorSeccion)
               {
                    foreach($VectorSeccion[$key] as $KeySubmodulo=>$DatosSubmodulo)
                    {
                         $clase = "SubMenuHC";
                         if($this->paso == $DatosSubmodulo['PASO']) $clase = "SubMenuHC1";
                         $accion = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],$DatosSubmodulo['PASO']);
                         $this->salida .= "						<li class=\"$clase\"><a class=\"SubMenuHC\" href=\"$accion\">".$DatosSubmodulo['TITULO']."</a></li>\n";
                         $i++;
                    }
               }
               
               $this->salida .= "						</ul>\n";
               $this->salida .= "					</div>\n";
               $this->salida .= "    	</p>\n";
          
               $flagPrimeraSeccion ?  $capas .= "\"Seccion$key\"" : $capas .= ",\"Seccion$key\"";
               $flagPrimeraSeccion = false;
          }
                         
          if($NumeroSubmodulosOcultos > 0)
          {
               $key++;
               $display = "display:none";
               if($llave >= $key)
                    $display = "display:block";

                    $capas .= ",\"Seccion$key\"";
                    $this->salida .= "    	<p class=\"MenuHC\" onclick=\"showhide('Seccion$key');\">\n";
                    $this->salida .= "    		<a href=\"#\">ADICIONALES</a>\n";
                    $this->salida .= "					<div name=\"Seccion$key\" id=\"Seccion$key\" style=\"position:relative;$display;width:180px;\">\n";
                    $this->salida .= "						<ul class=\"ListaHc\">\n";
     
               //Se hace una sola capa para los pasos adicionales(ocultos)
               $SubmodulosNoMostrar[0] = &$this->hc_estructura[0];
               foreach($SubmodulosNoMostrar as $KeyMostrar=>$VectorSeccion)
               {
                    foreach($VectorSeccion as $KeySeccion=>$VectorSubmodulos)
                    {
                    	foreach($VectorSubmodulos as $KeySubmodulo=>$DatosSubmodulo)
                         {
                              $accion = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],$DatosSubmodulo['PASO']);
                              $clase = "SubMenuHC";
                              if($this->paso == $i) $clase = "SubMenuHC1";
                              $this->salida .= "				<li class=\"$clase\"><a href=\"$accion\">".$DatosSubmodulo['TITULO']."</a></li>\n";
                              $i++;
                         }
                    }
               }
               $this->salida .= "						</ul>\n";
               $this->salida .= "					</div>\n";
               $this->salida .= "    	</p>\n";
               unset($SubmodulosNoMostrar);
          }
                         
                         
          $this->salida .= "</div>\n";
          $this->salida .= "<script language=\"javascript\">\n";
          $this->salida .= $capas.");\n";
					$this->salida .= "	var cliente = xClientHeight(); ";
					$this->salida .= "	var nuevosize = cliente; ";
          $this->salida .= "	function showhide(Seccion)\n";
          $this->salida .= "	{ \n";
          $this->salida .= "		for(i=0; i<secc.length; i++)\n";
          $this->salida .= "		{\n";
          $this->salida .= "			e = xGetElementById(secc[i]);\n";
          $this->salida .= "			if(secc[i] != Seccion)\n";
          $this->salida .= "			{\n";
          $this->salida .= "				e.style.display = \"none\";\n";
          $this->salida .= "				nuevosize = nuevosize - xHeight(e);\n";
          $this->salida .= "			}\n";
          $this->salida .= "			else\n";
          $this->salida .= "			{\n";
          $this->salida .= "				if(e.style.display == \"none\")\n";
          $this->salida .= "				{\n";
          $this->salida .= "					e.style.display = \"\";\n";
          $this->salida .= "				}\n";
          $this->salida .= "				else \n";
          $this->salida .= "				{\n";
          $this->salida .= "					e.style.display = \"none\";\n";
          $this->salida .= "				}\n";
          $this->salida .= "			}\n";
          $this->salida .= "		}\n";
					
					if(!eregi("MSIE",$_SERVER["HTTP_USER_AGENT"]))
						$this->salida .= "		Redimensionar();\n";
          
					$this->salida .= "	}\n";
          $this->salida .= "	function Redimensionar()\n";
					$this->salida .= "	{ \n";
					$this->salida .= "		ele = xGetElementById('SeccionesHC');";
					$this->salida .= "		if((xClientHeight()-10) <= xHeight(ele))\n";
					$this->salida .= "		{";
					$this->salida .= "	 		xResizeTo(ele,205,xClientHeight()-40);\n";
					$this->salida .= "		}";
					$this->salida .= "	}";
          $this->salida .= "</script>\n";
					
					if(!eregi("MSIE",$_SERVER["HTTP_USER_AGENT"]))
					{
						$this->salida .= "<script>\n";	
						$this->salida .= "	Redimensionar();\n";
						$this->salida .= "</script>\n";	
					}
     }//Fin ArmarMenuDesplegableHC
     
     /**
     * Metodo para generar la vista del listado de submodulos
     */
     function ListadoInicioHC()
     {
          $info_ingreso = $this->DatosIngresopaciente();
          $acudiente = $this->DatosAcudiente();
          $est = " style=\"text-align:left;text-indent:8pt\" ";
                    
          $salida .= ThemeAbrirTabla("INFORMACION PACIENTE","80%");
          $salida .= "<table width=\"80%\" border=\"0\" cellpadig=\"0\" cellspacing=\"1\" align=\"center\" class=\"modulo_table_list\">\n";
          $salida .= "	<tr class=\"modulo_table_list_title\">\n";
          $salida .= "		<td $est width=\"20%\">PACIENTE</td>";
          $salida .= "		<td $est colspan=\"4\" class=\"modulo_list_claro\" width=\"80%\">".$info_ingreso['nombres']." ".$info_ingreso['apellidos']."</td>";
          $salida .= "	</tr>\n";
          $salida .= "	<tr class=\"modulo_table_list_title\">\n";
          $salida .= "		<td $est width=\"20%\">IDENTIFICACION</td>\n";
          $salida .= "		<td $est width=\"5%\" class=\"modulo_list_claro\" >".$info_ingreso['tipo_id_paciente']."</td>\n";
          $salida .= "		<td $est class=\"modulo_list_claro\" >".$info_ingreso['paciente_id']."</td>\n";
          $salida .= "		<td $est >SEXO</td>\n";
          $salida .= "		<td $est class=\"modulo_list_claro\" >".strtoupper($info_ingreso['sexo'])."</td>\n";
          $salida .= "	</tr>\n";		
          $salida .= "	<tr class=\"modulo_table_list_title\">\n";
          $salida .= "		<td $est width=\"20%\">FECHA INGRESO</td>\n";
          $salida .= "		<td $est width=\"30%\" colspan=\"2\" class=\"modulo_list_claro\" >".$info_ingreso['fecha_ingreso']."</td>\n";
          if($info_ingreso['tabla'] == "CEXT")
          { $Tiempo = "TIEMPO CONSULTA"; }
          else
          { $Tiempo = "TIEMPO HOSP"; }
          $salida .= "		<td $est width=\"20%\">$Tiempo</td>\n";
          $salida .= "		<td $est width=\"30%\" class=\"modulo_list_claro\" >".$this->GetDiasHospitalizacion($info_ingreso['fechaingreso'])."</td>\n";
	     $salida .= "	</tr>\n";
          
          if($info_ingreso['ocupacion_descripcion'])
          {
               $salida .= "	<tr class=\"modulo_table_list_title\">\n";
               $salida .= "		<td $est width=\"20%\">OCUPACIÓN</td>";
               $salida .= "		<td $est colspan=\"4\" class=\"modulo_list_claro\" width=\"80%\">".$info_ingreso['ocupacion_descripcion']."</td>\n";
               $salida .= "	</tr>\n";
          }
          
          if(sizeof($acudiente) > 0)
          {
               $salida .= "	<tr class=\"TablaHc\">\n";
               $salida .= "		<td align=\"center\" style=\"color:#FFFFFF; font-weight : bold;\" colspan=\"5\">DATOS ACUDIENTE (S)</td>";
               $salida .= "	</tr>\n";
               
               for($i=0; $i<sizeof($acudiente);$i++)
               {
                    $salida .= "	<tr class=\"modulo_table_list_title\">\n";
                    $salida .= "		<td $est >NOMBRE</td>";
                    $salida .= "		<td $est colspan=\"2\" class=\"modulo_list_claro\">".$acudiente[$i]['nombre_completo']."</td>";
                    $salida .= "		<td $est >PARENTESCO</td>";
                    $salida .= "		<td $est class=\"modulo_list_claro\">".$acudiente[$i]['parentesco']."</td>";
                    $salida .= "	</tr>\n";
                    $salida .= "	<tr class=\"modulo_table_list_title\">\n";
                    $salida .= "		<td $est >DIRECCIÓN</td>";
                    $salida .= "		<td $est colspan=\"2\" class=\"modulo_list_claro\">".strtoupper($acudiente[$i]['direccion'])."</td>";
                    $salida .= "		<td $est >TELÉFONO</td>";
                    $salida .= "		<td $est class=\"modulo_list_claro\">".$acudiente[$i]['telefono']."</td>";
                    $salida .= "	</tr>\n";
               }
          }
          $salida .= "</table><br>\n";
          
          
          if($info_ingreso['tabla'] != "CEXT")
          {
               $salida .= "<table width=\"80%\" border=\"0\" cellpadig=\"0\" cellspacing=\"1\" align=\"center\" class=\"modulo_table_list\">\n";
               $salida .= "	<tr class=\"modulo_table_list_title\">\n";
               $salida .= "		<td colspan=\"4\">PACIENTE ";
               
               switch($info_ingreso['tabla'])
               {
                    case 'URG': 
                         $salida .= "			EN CONSULTA DE URGENCIAS </td>\n"; 
                         $salida .= "		<tr class=\"modulo_table_list_title\">\n";
                         $salida .= "			<td $est width=\"20%\">DEPARTAMENTO</td>\n";
                         $salida .= "			<td $est width=\"80%\" colspan=\"3\" class=\"modulo_list_claro\">".$info_ingreso['descripcion']."</td>\n";
                         $salida .= "		</tr>\n";
                         $salida .= "		<tr class=\"modulo_table_list_title\">\n";
                         $salida .= "			<td $est width=\"20%\">ESTACIÓN</td>\n";
                         $salida .= "			<td $est width=\"80%\" colspan=\"3\" class=\"modulo_list_claro\">".$info_ingreso['estacion']."</td>\n";
                         $salida .= "		</tr>\n";
                    break;
                    case 'MVH':	
                         $salida .= "			HOSPITALIZADO</td>\n"; 
                         $salida .= "		<tr class=\"modulo_table_list_title\">\n";
                         $salida .= "			<td $est width=\"20%\">DEPARTAMENTO</td>\n";
                         $salida .= "			<td $est width=\"80%\" colspan=\"3\" class=\"modulo_list_claro\">".$info_ingreso['descripcion']."</td>\n";
                         $salida .= "		</tr>\n";
                         $salida .= "		<tr class=\"modulo_table_list_title\">\n";
                         $salida .= "			<td $est width=\"20%\">UBICACIÓN</td>\n";
                         $salida .= "			<td $est width=\"80%\" colspan=\"3\" class=\"modulo_list_claro\">".$info_ingreso['estacion']."</td>\n";
                         $salida .= "		</tr>\n";
                         $salida .= "		<tr class=\"modulo_table_list_title\">\n";
                         $salida .= "			<td $est width=\"20%\">HABITACIÓN</td>\n";
                         $salida .= "			<td $est width=\"30%\" class=\"modulo_list_claro\">".$info_ingreso['pieza']."</td>\n";
                         $salida .= "			<td $est width=\"20%\">CAMA</td>\n";
                         $salida .= "			<td $est width=\"30%\" class=\"modulo_list_claro\">".$info_ingreso['cama']."</td>\n";
                         $salida .= "		</tr>\n";
                    break;
                    case 'EEC':	
                         $salida .= "			EN CIRUGIA</td>\n"; 
                         $salida .= "		<tr class=\"modulo_table_list_title\">\n";
                         $salida .= "			<td $est width=\"20%\">DEPARTAMENTO</td>\n";
                         $salida .= "			<td $est width=\"80%\" colspan=\"3\" class=\"modulo_list_claro\">".$info_ingreso['descripcion']."</td>\n";
                         $salida .= "		</tr>\n";
                         $salida .= "		<tr class=\"modulo_table_list_title\">\n";
                         $salida .= "			<td $est width=\"20%\">UBICACIÓN</td>\n";
                         $salida .= "			<td $est width=\"80%\" colspan=\"3\" class=\"modulo_list_claro\">".$info_ingreso['estacion']."</td>\n";
                         $salida .= "		</tr>\n";
                         $salida .= "		<tr class=\"modulo_table_list_title\">\n";
                         $salida .= "			<td $est width=\"20%\">QUIROFANO</td>\n";
                         $salida .= "			<td $est width=\"30%\" class=\"modulo_list_claro\">".$info_ingreso['abreviatura']."</td>\n";
                         $salida .= "			<td $est width=\"20%\">NOMBRE</td>\n";
                         $salida .= "			<td $est width=\"30%\" class=\"modulo_list_claro\">".$info_ingreso['quirofano']."</td>\n";
                         $salida .= "		</tr>\n";
                    break;
               }
               $salida .= "</table>\n";          
          }
          
          $salida .= ThemeCerrarTabla();
          return $salida;
     }//fin de ListadoInicioHC()
     
     /**
     * Metodo para generar cada seccion de la HC en el listado de submodulos
     */
     function GetFormaSeccionListadoInicioHC($VectorSeccion)
     {
          $separarSeccion=false;
          $directorioImagenes=GetThemePath().'/images/';
          $salida.="";
          $salida.="<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" >";
          foreach($VectorSeccion as $KeySeccion=>$VectorSubmodulos)
          {
               if($separarSeccion)
               {
                    $salida.= "<tr align=\"left\">\n";
                    $salida.= "    <td width=\"10\" colspan=\"3\">\n";
                    $salida.= "        &nbsp;\n";
                    $salida.= "    </td>\n";
                    $salida.= "</tr>\n";
               }
     
               $salida.="<tr align=\"left\">\n";
               $salida.="    <td width=\"10\" colspan=\"3\" class=\"normal_11N\">\n";
               $salida.="        <img src=\"".$directorioImagenes ."/flecha.png\" width='15' height='15'>&nbsp;&nbsp;".$this->hc_secciones[$KeySeccion]."\n";
               $salida.="    </td>\n";
               $salida.="</tr>\n";
     
               $separarSeccion=true;
     
               foreach($VectorSubmodulos as $KeySubmodulo=>$DatosSubmodulo)
               {
                    $accion = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],$DatosSubmodulo['PASO']);
                    $salida.= '<tr height="24" align="center" border="0" cellspacing="0" cellpadding="0">'."\n";
                    $salida.= '    <td width="10" align="right" valign="top">'."\n";
                    $salida.= '        <img src="'.$directorioImagenes.'/submenu/borde_izq.png" width="14" height="26" border="0">'."\n";
                    $salida.= '    </td>'."\n";
                    $salida.= "    <td  class='titulo_tabla_submenu' background=\"". $directorioImagenes ."/submenu/franja.png\">"."\n";
                    $salida.= '        <a href="'.$accion.'">'.$DatosSubmodulo['TITULO'].'</a>'."\n";
                    $salida.= '    </td>'."\n";
                    $salida.= '    <td width="10" align="left" valign="top">'."\n";
                    $salida.= '        <img src="'.$directorioImagenes.'/submenu/borde_der.png" width="14" height="26" border="0">'."\n";
                    $salida.= '    </td>'."\n";
                    $salida.= '</tr>'."\n";
               }
               
          }
          $salida .= "</table>";
          return $salida;
     }//fin de GetFormaSeccionListadoInicioHC()
     
     /**
     * Historia clinica de paciente
     */
     function HistoriaClinicaPaciente($historia,$evolucion,$modulo)
     {
          $salida .= themeAbrirTabla("RESUMEN DE HISTORIA CLINICA");
          if (!empty($historia))
          {
                         $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
               $salida .= "<tr>\n";
               $salida .= "<td align=\"center\"><B>HISTORIAL CRONOLOGICO DEL PACIENTE</B>";
               $salida .= "</td>\n";
               $salida .= "</tr>\n";
               $salida .= "</table>\n";
          }
          else
          {
               $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
               $salida .= "<tr>\n";
               $salida .= "<td align=\"center\"><B>EL PACIENTE AUN NO PRESENTA HISTORIAL CRONOLOGICO</B>";
               $salida .= "</td>\n";
               $salida .= "</tr>\n";
               $salida .= "</table>\n";
          }
          foreach($historia as $k=>$v)
          {
               $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
               $salida .= "<tr>\n";
               $salida .= "<td>\n";
               $salida .= '<b>Ingreso No.:</b>&nbsp;'.$k;
               $salida .= "</td>\n";
               $salida .= "</tr>\n";
               $salida .= "</table>\n";
               if ($centinela != $k)
               {
                    $y = 1;
               }
               foreach($v as $x=>$s)
               {
                    for($b=0; $b<sizeof($s); $b++)
                    {
                         for($l=0; $l<sizeof($s); $l++)
                         {
                              $triage[] = $s[$l]['triage_id'];
                              $motivo[] = $s[$l]['motivo_consulta'];
                              $enfermedad[] = $s[$l]['enfermedad_actual'];
                              $diagnostico[] = $s[$l]['diagnostico_nombre'];
                         }
     
                         $centinela = $s[$b]['ingreso'];
     
                         if($y != 2)
                         {
                              $y = 2;
                              $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
                              $salida .= "<tr>\n";
                              $salida .= "<td>\n";
                              if(!empty($s[$b]['fecha_ingreso']))
                              {
                                   $salida.='<b>Fecha de Ingreso :</b>&nbsp; '.$s[$b]['fecha_ingreso'].'<br>';
                              }
                              if(!empty($s[$b]['comentario']))
                              {
                                   $salida.='<b>Descripción Ingreso :</b>&nbsp;'.$s[$b]['comentario'].'<br>';
                              }
                              if(!empty($s[$b]['dpto']))
                              {
                                   $salida.='<b>Departamento :</b>&nbsp;'.$s[$b]['dpto'];//via_ingreso_nombre
                              }
                              $salida .= "</td>\n";
                              $salida .= "</tr>\n";
                              $salida .= "</table>\n";
                         }
     
                         if($s[$b]['evolucion_id'] != $s[$b-1]['evolucion_id'])
                         {
                              $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
                              $salida .= "<tr>\n";
                              $salida .= "<td>\n";
                              $accion=ModuloHCGetURL($evolucion,'pasohc',0,$modulo,$modulo,array('evolucion_consulta'=>$s[$b]['evolucion_id']));
                              $salida.="<a href='$accion'><b>Evolución No.:</b>&nbsp;".$s[$b]['evolucion_id']." - ".$s[$b]['fecha_evolucion']." - <b>Profesional:</b> ".$s[$b]['nombre_medico']." - ".$s[$b]['descipcion_medico']."</a>";
                              $salida .= "</td>\n";
                              $salida .= "</tr>\n";
                              $salida .= "</table>\n";
          
                              for($a=0; $a<sizeof($triage); $a++)
                              {
                                   if(!empty($s[$a]['triage_id']) AND $s[$a]['triage_id'] != $s[$a-1]['triage_id'])
                                   {
                                        $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
                                        $salida .= "<tr>\n";
                                        $salida .= "<td width=\"25%\">\n";
                                        $salida.="&nbsp;&nbsp;&nbsp;&nbsp;Triage - $a :";
                                        $salida .= "</td>\n";
                                        $salida .= "<td width=\"75%\">\n";
                                        $salida.=$s[$a]['triage_id'];
                                        $salida .= "</td>\n";
                                        $salida .= "</tr>\n";
                                        $salida .= "</table>\n";
                                   }
                              }
     
                              for($j=0; $j<sizeof($motivo); $j++)
	                         {
                                   if(!empty($s[$j]['motivo_consulta']) AND $s[$j]['motivo_consulta'] != $s[$j-1]['motivo_consulta'])// AND $s[$b]['evolucion_id'] == $s[$b]['evo_motivo']
                                   {
                                        $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
                                        $salida .= "<tr>\n";
                                        $salida .= "<td width=\"25%\">\n";
                                        $salida.="&nbsp;&nbsp;&nbsp;&nbsp;<b>Motivo - $j :</b>";
                                        $salida .= "</td>\n";
                                        $salida .= "<td width=\"75%\">\n";
                                        $salida.=$s[$j]['motivo_consulta'];
                                        $salida .= "</td>\n";
                                        $salida .= "</tr>\n";
                                        $salida .= "</table>\n";
                                   }
                              }
     
                              for($z=0; $z<sizeof($enfermedad); $z++)
                              {
                                   if(!empty($s[$z]['enfermedad_actual']) AND $s[$z]['enfermedad_actual'] != $s[$z-1]['enfermedad_actual'])// AND $s[$b]['evolucion_id'] == $s[$b]['evo_motivo']
                                   {
                                        $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
                                        $salida .= "<tr>\n";
                                        $salida .= "<td width=\"25%\">\n";
                                        $salida.="&nbsp;&nbsp;&nbsp;&nbsp;<b>Enfermedad Actual - $z :</b>";
                                        $salida .= "</td>\n";
                                        $salida .= "<td width=\"75%\">\n";
                                        $salida.=$s[$z]['enfermedad_actual'];
                                        $salida .= "</td>\n";
                                        $salida .= "</tr>\n";
                                        $salida .= "</table>\n";
                                   }
                              }
     
	                         $dx = array_unique($diagnostico);
     
                              for($w=0; $w<sizeof($dx); $w++)
                              {
     
                                   if(!empty($s[$w]['diagnostico_nombre']) AND $s[$w]['diagnostico_nombre'] != $s[$w-1]['diagnostico_nombre'])
                                   {
                                        $salida .= "<table align=\"center\" width=\"80%\" border=\"1\"  class=\"modulo_table\">\n";
                                        $salida .= "<tr>\n";
                                        $salida .= "<td width=\"25%\">\n";
                                        $salida.="&nbsp;&nbsp;&nbsp;&nbsp;<b>Diagnostico de Ingreso - $w :</b>";
                                        $salida .= "</td>\n";
                                        $salida .= "<td width=\"75%\">\n";
                                        $salida.=$s[$w]['diagnostico_nombre'];
                                        $salida .= "</b></td>\n";
                                        $salida .= "</tr>\n";
                                        $salida .= "</table>\n";
                                   }
                              }
                         }
                    }
               }
          }
          $salida .= themeCerrarTabla();
          return $salida;
     }//fin HistoriaClinicaPaciente
     
     /**
     *
     */
     function Mensaje($mensaje,$url,$modulo)
     {
          $salida .= themeAbrirTabla("Mensaje de Alerta");
          $salida .= "<table align=\"center\" width=\"100%\" border=\"0\">\n";
          $salida .= "    <tr align='center'>\n";
          $salida .= "        <td>\n";
          $salida .= "          <label class=\"label_error\">".strtoupper($mensaje)."</label>";
          $salida .= "        </td>\n";
          $salida .= "    </tr>\n";
          if(!empty($url))
          {
               $salida .= "    <tr align='center'>\n";
               $salida .= "        <td>\n";
               $salida .= "           <a href=\"".$url."\">$modulo</a>";
               $salida .= "        </td>\n";
               $salida .= "    </tr>\n";
          }
          $salida .= "    </table>\n";
          $salida .= themeCerrarTabla();
          return $salida;
     }
     
     /**
     */
     function FormaDiasNE($notas_e)
     {
          $salida .= "<form name=\"formaNotasECtrl\" method=\"post\">";
          $salida.= "        <script>\n";
          $salida .= "            function CargarPagina(href,valor) {\n";
          $salida.= "                var url=href;\n";
          $salida.= "                location.href=url+'&select_fecha='+valor;\n";
          $salida.= "            }\n\n";
          $salida.= "        </script>\n\n";
          $salida.=$notas_e->GetDiasControles();
          $salida.=$notas_e->GetSalida();
          $salida  .="</form>";
          return $salida;
     }
     
     /**
     */
     function FrmSeleccionarConducta($datos)
     {
		$datosevolucion = &$this->datosEvolucion;
          $modulo = $this->datosEvolucion['hc_modulo'];
          $salida .= themeAbrirTabla("Conducta Medica");
          $accion = ModuloHCGetURL($datosevolucion['evolucion_id'],'cerrar',0,$modulo,$modulo);
          $salida .= "<form name='cerrar' method='post' action='$accion'>\n";
          $salida .= "<table align=\"center\" width=\"50%\" border=\"0\"  >\n";
          $spy=0;
          foreach($datos as $k=>$v)
          {
               $salida .= "    <tr align='left'>\n";
               $salida .= "        <td>\n";
               if($spy==0)
               {
                    $salida .= "<input type='radio' name='conducta' value='$k' class='input-text' checked>\n";
                    $spy=1;
               }
               else
               {
                    $salida .= "<input type='radio' name='conducta' value='$k' class='input-text'>\n";
               }
               $salida .= "        </td>\n";
               $salida .= "        <td>\n";
               $salida .= "           <label class=\"label\">$v[titulo_mostrar]</label>\n";
               $salida .= "        </td>\n";
               $salida .= "    </tr>\n";
          }
     
          $salida .= "    <tr align='left'>\n";
          $salida .= "        <td colspan=\"2\">\n";
          $salida .= "            &nbsp;\n";
          $salida .= "        </td>\n";
          $salida .= "    </tr>\n";
     
          $salida .= "    <tr align='left'>\n";
          $salida .= "        <td colspan=\"2\">\n";
          $salida .= "            <label class=\"label\">OBSERVACIONES</label>\n";
          $salida .= "        </td>\n";
          $salida .= "    </tr>\n";
     
          $salida .= "    <tr align='left'>\n";
          $salida .= "        <td colspan=\"2\">\n";
          $salida .= "            <textarea cols='8' name='conducta_observacion' style='width:100%' class=\"textarea\"></textarea>\n";
          $salida .= "        </td>\n";
          $salida .= "    </tr>\n";
     
          $salida .= "    <tr align='left'>\n";
          $salida .= "        <td colspan=\"2\">\n";
          $salida .= "            &nbsp;\n";
          $salida .= "        </td>\n";
          $salida .= "    </tr>\n";
     
          $salida .= "    <tr align='left'>\n";
          $salida .= "        <td colspan=\"2\">\n";
          $salida .= "           <input type='submit' name='cerrar' value='Continuar' class='input-submit'>";
          $salida .= "        </td>\n";
          $salida .= "    </tr>\n";
          $salida .= "    </table>\n";
          $salida .= "    </form>\n";
          $salida .= themeCerrarTabla();
          return $salida;
     }
     
     /**
     */
     function VolverListado($contenedor,$modulo,$tipo,$metodo,$ingreso,$evolucion)
     {
          $accion1 = ModuloGetURL('app','ImpresionHC','user','main',array('ModuloRETORNO'=>array('contenedor'=>$contenedor,'modulo'=>$modulo,'tipo'=>$tipo,'metodo'=>$metodo),'ingreso'=>$ingreso,'evolucion'=>$evolucion));
          $accion2 = ModuloGetURL($contenedor,$modulo,$tipo,$metodo);
					$accion3 = ModuloGetURL('app','HonorariosMedicos','user','FormaConsultarPorFecha');
          $salida  = themeAbrirTabla("HISTORIA CLINICA");
          $salida .= "<DIV align='center' class='titulo3'>\n";
          $salida .= "La historia clinica fue cerrada satisfactoriamente. \n";
          $salida .= "</DIV>\n";
          $salida .= "\n";
          $salida .= "<FORM>\n";
          $salida .= "  <TABLE width='80%' cellspacing='2' border='0' cellpadding='2' align='center'>\n";
          $salida .= "    <tr>\n";
					$salida .= "    <td align='center'><A href='$accion3'>Honorarios Medicos</A></td>\n";
					$salida .= "    </tr>   \n";
					$salida .= "    <tr>\n";
          $salida .= "    <td align='center'><A href='$accion1'>Ir al modulo de Impresión</A></td>\n";
          $salida .= "    </tr>\n";
          $salida .= "    <tr>\n";
          $salida .= "    <td align='center'><A href='$accion2'>Listado de pacientes para atención</A></td>\n";
          $salida .= "    </tr>    \n";
          $salida .= "  </TABLE>\n";
          $salida .= "</FORM>\n";
          $salida .= themeCerrarTabla();
          return $salida;
     }
     
     /**
     */
     function MensajeErrorSubmodulo($submodulo='',$Err='',$ErrMsg='',$TituloVentana='')
     {
          if(empty($Err) && empty($ErrMsg)){
               $Err="El submodulo retorno \"FALSE\"";
               $ErrMsg="Reporte este evento al personal encargado de soporte.";
          }
          else
          {
               if(empty($Err))
               {
                    $Err="&nbsp;";
               }
     
               if(empty($ErrMsg))
               {
                    $ErrMsg="&nbsp;";
               }
          }
          if(!empty($TituloVentana))
          {
               $TituloVentana = "Mensaje retornado por el submodulo $submodulo";
          }
          $salida .= ThemeAbrirTablaSubModulo("Mensaje retornado por el submodulo $submodulo");
          $salida .= "<table align=\"center\" width=\"100%\" border=\"0\">\n";
          $salida .= "    <tr align='center'>\n";
          $salida .= "        <td>\n";
          $salida .= "          <label class=\"titulo3_error\">".strtoupper($Err)."</label>";
          $salida .= "        </td>\n";
          $salida .= "    </tr>\n";
          $salida .= "    <tr align='center'>\n";
          $salida .= "        <td>\n";
          $salida .= "          <label class=\"titulo3\">$ErrMsg</label>";
          $salida .= "        </td>\n";
          $salida .= "    </tr>\n";
          $salida .= "    </table>\n";
          $salida .= ThemeCerrarTablaSubModulo();
          return $salida;
     }
     
     /**
     * Función que pinta la flechas de navegación
     */
     function GetFlechasNavegacionDeHC()
     {
          static $contador;
          if(empty($contador))
               $contador = 1;
          $salida .= "    <table align=\"center\" width=\"70%\">\n";
          $salida .= "        <tr>\n";
          if($this->BotonAtras)
          {
               $salida .= "        <td height=\"40\" width=\"50%\" align=\"center\">\n";
               $salida .= "            <a href=\"".$this->accionBotonAtras."\" title=\"".$this->titleBotonAtras."\"  \n";
               //$salida .= "                onmouseover=\"window.document['ImgAtras$contador'].src = ImgAtrasClara.src\"\n";
               //$salida .= "                onmouseout=\"window.document['ImgAtras$contador'].src = ImgAtrasOscura.src\"\n";
               $salida .= "            >\n";
               //$salida .= "            <IMG src=\"".$this->directorioImagenesHC."atras_oscuro.gif\" name = \"ImgAtras$contador\" border=\"0\">\n";
               $salida .= $this->titleBotonAtras;
               $salida .= "            </a>\n";
               $salida .= "        </td>\n";
          }
          if($this->BotonSiguiente)
          {
               $salida .= "        <td height=\"40\" width=\"50%\" align=\"center\">\n";
               $salida .= "            <a href=\"".$this->accionBotonSiguiente."\" title=\"".$this->titleBotonSiguiente."\"  \n";
               //$salida .= "                onmouseover=\"window.document['ImgAdelante$contador'].src = ImgAdelanteClara.src\"\n";
               //$salida .= "                onmouseout=\"window.document['ImgAdelante$contador'].src = ImgAdelanteOscura.src\"\n";
               $salida .= "            >\n";
               //$salida .= "            <IMG src=\"".$this->directorioImagenesHC."adelante_oscuro.gif\" name =\"ImgAdelante$contador\"  border=\"0\">\n";
               $salida .= $this->titleBotonSiguiente;
               $salida .= "        </td>\n";
          }
          $salida .= "        </tr>\n";
          $salida .= "    </table>\n";
          $contador++;
          return $salida;
     }
     
     
     function IncludeCabeceraHC($datosPaciente,$datosEvolucion,$paso,$modulo,$numPasos,$datosResponsable)
     {
          
          if(empty($datosPaciente) or empty($datosEvolucion))
          {
               return false;
          }
          $acciones[0]=ModuloHCGetURL($datosEvolucion['evolucion_id'],-1,0,$modulo);
          $acciones[1]=ModuloHCGetURL($datosEvolucion['evolucion_id'],'historia',0,$modulo);
          $acciones[2]=ModuloHCGetURL($datosEvolucion['evolucion_id'],'cerrar',0,$modulo,$modulo,array('primera'=>1,'DESMARCAR'=>1));
          if(!empty($_SESSION['HISTORIACLINICA']['RETORNO']))
          {
               if(!empty($_SESSION['HISTORIACLINICA']['RETORNO']['argumentos'])){
	               $acciones[3]=ModuloGetURL($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo'],$_SESSION['HISTORIACLINICA']['RETORNO']['argumentos']);
               }else{
                    $acciones[3]=ModuloGetURL($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']);
               }
          }
          else
          {
               $acciones[3]=null;
          }
          
          $acciones[4]=ModuloHCGetURL($datosEvolucion['evolucion_id'],'apoyod',0,$modulo);
          $inforpaciente[0]=RetornarWinOpenDatosPaciente($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id'],$datosPaciente['primer_nombre'].' '.$datosPaciente['segundo_nombre'].' '.$datosPaciente['primer_apellido'].' '.$datosPaciente['segundo_apellido'],'hcPaciente');
          $edad=CalcularEdad($datosPaciente['fecha_nacimiento'],$datosEvolucion['fecha']);
          if($edad['anos']!=""){
               $datos.=$edad['anos'].' años, ';
          }
          if($edad['meses']!=""){
               $datos.=$edad['meses'].' meses, ';
          }
          if($edad['dias']!=""){
               $datos.=$edad['dias'].' dias.';
          }
          $inforpaciente[1]=$datos;
          $inforpaciente[2]=$datosResponsable['nombre_tercero'].' - '.$datosResponsable['plan_descripcion'];
          //si se quiere cambiar el color solo cambiar themeMenuAbrirTabla por ThemeAbrirTabla.
          $salida .= ThemeAbrirTablaHistoriaClinica($paso,$acciones,$inforpaciente,$datosEvolucion['estado']);
          $salida .= "<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
          $salida .= "    <tr class='modulo_table_list'><td>\n";
          return $salida;
     }
     
     
     function IncludePieDePaginaHC($datosEvolucion,$paso,$modulo,$numPasos)
     {
          $imagen=GetThemePath().'/images/HistoriaClinica/';
          $salida .= "        </td>\n";
          $salida .= "    </tr>\n";
          if($paso>=0 and $datosEvolucion['estado']==1)
          {
               $salida.='<tr align="center">';
               $salida.='<td>';
               $salida.='<table align="center" width="90%" border="0">';
               $salida.='<tr align="center">';
               $salida.='<td width="45%" align="right">';
               if($paso!=-1 AND $paso!=$numPasos)
               {
                    $accion=ModuloHCGetURL($datosEvolucion['evolucion_id'],$paso-1,0,$modulo,$modulo,array('devolver'=>1));
                    $salida .= "              <a href='$accion'><img src='".$imagen."adelante.png' width='22' height='22' align='middle' border='0' title=\"ANTERIOR\"></a>";
               }
               $salida.='</td>';
               $salida.='<td width="5%" align="left">';
               $salida.='</td>';
          
               $salida.='<td width="45%" align="left">';
               if($paso!=$numPasos-1 and $paso!=$numPasos)
               {
                    $accion=ModuloHCGetURL($datosEvolucion['evolucion_id'],$paso+1,0,$modulo);
                    $salida .= "              <a href='$accion'><img src='".$imagen."atras.png' width='22' height='22' align='middle' border='0' title=\"SIGUIENTE\"></a>";
               }
	          /***************/
               if($paso==$numPasos-1)
               {
                    $accion=ModuloHCGetURL($datosEvolucion['evolucion_id'],$paso+1,0,$modulo);
                    $salida .= "<a href='$accion'><img src='".$imagen."cerrar.png' width='22' height='22' align='middle' border='0'></a>";
               }
     	     /***************/
               $salida.='</td>';
               $salida.='</tr>';
               $salida .= "    </table>\n";
               $salida.='</td>';
               $salida.='</tr>';
          }
          $salida .= "    </table><br>\n";
          $salida.= ThemeCerrarTablaHistoriaClinica();
          return $salida;
     }
		/*********************************************************************
		*
		**********************************************************************/
		function IncludeCabeceraHC2()
    {   
      $this->directorioImagenesHC = GetThemePath().'/images/HistoriaClinica1/';
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("RemoteScripting");
			$this->IncludeJS("javascripts/VisibilidadMenuHc.js");

			$posicion = "position:fixed";
			if(eregi("MSIE",$_SERVER["HTTP_USER_AGENT"]))	$posicion = "position:absolute";

			//Nueva Version
			$this->salida .= "<script>\n";
			$this->salida .= "	var enabled = true;\n";
			$this->salida .= "	var alphaVisible = true;\n";
			$this->salida .= "	function xWinOnLoad()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		e = xGetElementById('SeccionesHC');\n";
			$this->salida .= "		enabled = true;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function toggleAlpha()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var d, m, e, a, i;\n";
			$this->salida .= "		e = xGetElementById('SeccionesHC');\n";
			$this->salida .= "		if (alphaVisible) \n";
			$this->salida .= "		{\n";
			$this->salida .= "			d = 'none';\n";
			$this->salida .= "			m = '0px';\n";
			$this->salida .= "		}\n";
			$this->salida .= "		else \n";
			$this->salida .= "		{\n";
			$this->salida .= "			d = 'block';\n";
			$this->salida .= "			m = '190px';\n";
			$this->salida .= "		}\n";
			$this->salida .= "		e.style.display = d;\n";
			$this->salida .= "		a = xGetElementById('CapaHc');\n";
			$this->salida .= "		a.style.marginLeft = m;\n";
			$this->salida .= "		alphaVisible = !alphaVisible;\n";
			$this->salida .= "		valores(".$this->usuarioConsultante.",".$this->datosEvolucion['evolucion_id'].");\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";

			$acciones[0] = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],-1,0,$this->datosEvolucion['hc_modulo']);
			$acciones[1] = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'historia',0,$this->hc_modulo);
			$acciones[2] = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'cerrar',0,$this->hc_modulo,$this->hc_modulo,array('primera'=>1,'DESMARCAR'=>1, 'descargarVariable'=>true));
			if(!empty($_SESSION['HISTORIACLINICA']['RETORNO']))
			{
			 if(!empty($_SESSION['HISTORIACLINICA']['RETORNO']['argumentos']))
			 {
				 $acciones[3]=ModuloGetURL($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo'],$_SESSION['HISTORIACLINICA']['RETORNO']['argumentos']);
			 }
			 else
			 {
				$acciones[3]=ModuloGetURL($_SESSION['HISTORIACLINICA']['RETORNO']['contenedor'], $_SESSION['HISTORIACLINICA']['RETORNO']['modulo'], $_SESSION['HISTORIACLINICA']['RETORNO']['tipo'], $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']);
			 }
			}
			else
			{
			 $acciones[3]=null;
			}
			$acciones[4] = ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'apoyod',0,$this->hc_modulo);
			//Activo el javascript de DatosPaciente para las ventanas emergentes
			$this->SetJavaScripts('DatosPaciente');
			$inforpaciente[0] = RetornarWinOpenDatosPaciente($this->datosPaciente['tipo_id_paciente'],$this->datosPaciente['paciente_id'],$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido'],'hcPaciente');
			if($this->datosPaciente['edad_paciente']['anos']!="")
			{
			 $edad = $this->datosPaciente['edad_paciente']['anos'] .' años, ';
			}
			if($this->datosPaciente['edad_paciente']['meses']!="")
			{
			 $edad .= $this->datosPaciente['edad_paciente']['meses'].' meses, ';
			}
			if($this->datosPaciente['edad_paciente']['dias']!="")
			{
			 $edad .= $this->datosPaciente['edad_paciente']['dias'].' dias.';
			}
			$this->CalcularPasosDeNavegacionHC();
								 
			$this->salida .= "<!--Barra principal de historia clinica-->\n";
			$this->salida .= "<div class=\"cabecera_hc\" style=\"$posicion\">\n";
			$this->salida .= "	<table width=\"100%\" class=\"TablaHc\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
			$this->salida .= "    <tr>\n";
			$this->salida .= "     	<td width=\"90%\" align=\"center\">";
			$this->salida .= "      	<table align=\"center\" border=\"0\">\n";
			$this->salida .= "        	<tr>\n";
			$this->salida .= "          	<td class=\"hcPaciente\">".$inforpaciente[0]."</td>";
			$this->salida .= "            <td class=\"hc_normal_10\"><b>Edad: </b>$edad</td>";
			$this->salida .= "          </tr>\n";
			$this->salida .= "          <tr>\n";
			$this->salida .= "          	<td align=\"center\" class=\"hc_normal_10\" colspan=\"2\">";
			$this->salida .= "							<b>Responsable: </b>".$this->datosResponsable['nombre_tercero'].' - '.$this->datosResponsable['plan_descripcion'];
			$this->salida .= "            </td>";
			$this->salida .= "          </tr>\n";
			$this->salida .= "        </table>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "      <td height=\"40\" width=\"10%\" align=\"center\">\n";
			$this->salida .= "        <a href=\"{$acciones[3]}\" title=\"Volver\">\n";
			$this->salida .= "        	<img src=\"".$this->directorioImagenesHC."volver_claro.gif\" name=\"ImgVolver\" border=\"0\">\n";
			$this->salida .= "        </a>\n";
			$this->salida .= "      </td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</div>\n";
			$this->salida .= "<!--Fin Barra principal de historia clinica-->\n";
		}//Fin IncludeCabeceraHC1
}
?>