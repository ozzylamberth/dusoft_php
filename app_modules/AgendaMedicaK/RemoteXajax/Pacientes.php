<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Pacientes.php,v 1.18 2010/03/16 18:41:57 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */  
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.18 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  
  /**
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function ValidarPaciente($form)
  {
    $objResponse = new xajaxResponse();
    
    $mensaje = "";
    $cargos_add = SessionGetvar("Cagos_Adicionados".UserGetUID());
    if($form['TipoDocumento'] == "-1")
      $mensaje = "SE DEBE SELECCIONAR EL TIPO DE DOCUMENTO";
    else if($form['Documento'] == "")
      $mensaje = "SE DEBE INGRESAR EL NUMERO DEL DOCUMENTO";
      else if($form['Responsable'] == '-1')
        $mensaje = "SE DEBE SELECCIONAR EL PLAN";
        else if($form['TipoConsulta'] == "-1")
          $mensaje = "SE DEBE SELECCIONAR EL TIPO DE CONSULTA";
          else if($form['rango'] == '-1' || $form['tipoafiliado']  == '-1' || $form['rango'] === null || $form['tipoafiliado'] === null)
            $mensaje = "SE DEBE SELECCIONAR EL RANGO Y EL TIPO DE AFILIADO";
            else if($form['cargos_adicionales'] == '1' && empty($cargos_add))
              $mensaje = "NO SE HA HECHO LA SELECCION DE CARGOS ADICIONALES";
              else if($form['anestesiologia'] == '1' && $form['anestesiologo'] != '1' && $form['anestesiologo'] != '2')
                $mensaje = "NO SE HA INDICADO SI EL PACIENTE REQUIERE O NO ANESTESIOLOGO";
    
    if($mensaje == "")
    {
      $form['tipo_id_paciente'] = $form['TipoDocumento'];
      $form['paciente_id'] = $form['Documento'];
      $form['plan_id'] = $form['Responsable'];    
            
      
      $inp = AutoCarga::factory('InformacionPacientes');
      $datos = $inp->ValidarInformacion($form);
      
      $sla = AutoCarga::factory("InformacionAfiliados","","app","AgendaMedica");
      $tcita = $sla->TiempoCita($form);
      SessionSetVar("tiempoxplan",$tcita['tiempo_cita']);

      if(is_array($datos))
      {
        if(empty($datos))
        {
          $html = "AL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id']." NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO ";
          $objResponse->assign("error","innerHTML",$html);
        }
        else
        {
          SessionSetVar("DatosPaciente_CE",$datos);
          $objResponse->call("Continuar");
        }
      }
      else
      {
        if(is_numeric($datos))
          $objResponse->assign("error","innerHTML",$inp->ObtenerClasificacionErrores($datos));
        
        if($datos == 3)
        {
          $datos = $sla->ObtenerDatosAfiliados($form);
          if($datos === false)
            $objResponse->assign("error","innerHTML",$sla->ErrMsg());
          else
          {
            $validacion = $sla->ObtenerInformacionPlan($form['plan_id']);
            if(!empty($datos) && $datos['plan_atencion'] != $form['plan_id'])
            {
              if($validacion['sw_afiliados'] == '2' OR $validacion['sw_tipo_plan'] == '1' OR $validacion['sw_tipo_plan'] == '3')
              {
                SessionSetVar("DatosPaciente_CE",$datos);
                $objResponse->call("Continuar");
              }
              else
              {
                $html  = "EL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id']." NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO ";
                $html .= "<br>PLAN DE AFILIACION ".$datos['plan_descripcion']." ";
                $objResponse->assign("error","innerHTML",$html);
              }
            }
            else
            {
              //$validacion = $sla->ObtenerDatosPlanAfiliado($form);
              if(empty($datos) && $validacion['sw_afiliados'] == '1')
              {
                $mensaje = "EL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id'].", NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO";
                $objResponse->assign("error","innerHTML",$mensaje);
              }
              else
              {
                SessionSetVar("DatosPaciente_CE",$datos);
                $objResponse->call("Continuar");
              }
            }
          }
        }
      }
    }
    else
    {
      $objResponse->assign("error","innerHTML",$mensaje);
    }
    return $objResponse;
  }
  /**
  *
  */
  function TiposAfiliados($form)
  {
    $objResponse = new xajaxResponse();
    $display = "none";
    $plan = $form['Responsable'];
    if($plan != '-1')
    {
      $display = "block";
      $sla = AutoCarga::factory('InformacionAfiliados','','app','AgendaMedica');
      
      $datos_plan = $sla->ObtenerInformacionPlan($plan);
      
      if($datos_plan['sw_afiliados'] == '1')
      {
        $objResponse->assign("validar","style.display","block");
        $objResponse->assign("div_tipos","innerHTML","");
      }
      else
      {
        $mdl = AutoCarga::factory('PacientesHTML','views','app','AgendaMedica');
        $tipos = $sla->ObtenerTiposAfiliados($plan);
        $rangos = $sla->ObtenerRangosNiveles($plan);
        $UltimaCita = $sla->ObtenerUltimaCita($_SESSION['AsignacionCitas']['cita'],$form['TipoDocumento'],$form['Documento']);
        $semanas = $cotizante['semanas_cotizadas'];
    
        $html = $mdl->FormaDatosAfiliado($tipos,$rangos,$UltimaCita);
    
        $objResponse->assign("div_tipos","innerHTML",$html);
        $objResponse->assign("validar","style.display","none");
      }
    }
    $objResponse->assign("div_tipos","style.display",$display);
    
    return $objResponse;
  }
  /**
  * Funcion para hacer la validacion del tipo de afiliado y el rango
  * para un afiliado
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function ValidarPlanAtencion($form)
  {
    $objResponse = new xajaxResponse();
    $mensaje = "";
    if($form['TipoDocumento'] == "-1")
      $mensaje = "SE DEBE SELECCIONAR EL TIPO DE DOCUMENTO";
    else if($form['Documento'] == "")
      $mensaje = "SE DEBE INGRESAR EL NUMERO DEL DOCUMENTO";

    if($mensaje == "")
    {
      $form['tipo_id_paciente'] = $form['TipoDocumento'];
      $form['paciente_id'] = $form['Documento'];
      $form['plan_id'] = $form['Responsable'];       
      
      $mdl = AutoCarga::factory('PacientesHTML','views','app','AgendaMedica');
      $sla = AutoCarga::factory('InformacionAfiliados','','app','AgendaMedica');
      
      $datos = $sla->ObtenerDatosPlanAfiliado($form);
      
      $UltimaCita = $sla->ObtenerUltimaCita($_SESSION['AsignacionCitas']['cita'],$form['TipoDocumento'],$form['Documento']);
      $validacion = $sla->ObtenerInformacionPlan($form['plan_id']);
      $datos1 = $sla->ObtenerDatosAfiliados($form);
      if(empty($datos))
      {
        if($validacion['sw_afiliados'] == '2')
        {
          $mensaje = "EL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id'].", NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO1";
        }
        else
        {
          $mensaje  = "EL AFILIADO IDENTIFICADO CON ".$form['tipo_id_paciente']." ".$form['paciente_id']." NO ESTA AUTORIZADO PARA EL PLAN SELECCIONADO ";
          $mensaje .= "<br>PLAN DE AFILIACION ".$datos1['plan_descripcion']." ";
        }
      } 
      else
      {
        $tipos[] = $datos;
        $rangos[] = $datos;
      
        $html = $mdl->FormaDatosAfiliado($tipos,$rangos,$UltimaCita);
        $objResponse->assign("div_tipos","innerHTML",$html);
      }
    }
    
    $objResponse->assign("error","innerHTML",$mensaje);
    return $objResponse;
  }
  /**
  * Funcion para hacer la validacion del tiempo de las citas (Prioridades)
  * 
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function ValidarCitasPrioridades($form)
  {
    $objResponse = new xajaxResponse();
    
    $clas = AutoCarga ::factory("ClaseUtil");
    $mdl = AutoCarga::factory('PacientesHTML','views','app','AgendaMedica');
    $sla = AutoCarga::factory('InformacionAfiliados','','app','AgendaMedica');
    
    $datos = $sla->PrioridadCitas($form['Documento'],$form['TipoDocumento']);
    $tiempo_cargos = $sla->Tiempocargos($_SESSION['AsignacionCitas']['cita'],$_SESSION['AsignacionCitas']['empresa']);
    $fechaAhora =date("d/m/Y");
    
    $numero_consultas = $datos['numerocitas'];
    $total_cargos=sizeof($tiempo_cargos);
    $f=explode("-",$datos['fecha_turno']);
    
    $fecha=date("d/m/Y", mktime(0,0,0,$f[1],$f[2],$f[0]));
    $d = $clas->CompararFechas($fechaAhora,$fecha);
    
    $diferencia = ($d/3600)/24;
    
    if($total_cargos > $numero_consultas)
    {
      $cargos = $tiempo_cargos[$numero_consultas-1];
    }
    else
    {
      $i = $total_cargos - 1;
      $cargos = $tiempo_cargos[$i];
    }
    
    if($diferencia >= $cargos['tiempo_cargo'])
    {
      $cargos = $tiempo_cargos[0];
    }
   
    $scp  = "for (var i=0; i < document.formabuscar.TipoConsulta.length; i++)\n";
    $scp .= "{\n";
    $scp .= " if(document.formabuscar.TipoConsulta[i].value == '".$cargos['cargo_cups']."')\n";
    $scp .= "   document.formabuscar.TipoConsulta.selectedIndex = i;\n";
    $scp .= "}\n";
    
    $objResponse->script($scp);
    
    return $objResponse;
  }
  /**
  * Funcion donde se crea el buscador de cargos
  *
  * @param array $form datos del buscador
  *
  * @return object
  */
  function BuscarCargos($form,$offset)
  {
    $objResponse = new xajaxResponse();
    $cargos_add = SessionGetvar("Cagos_Adicionados".UserGetUID());
    
    $html = "<br>\n";	
    if(!empty($form))
    {
      $form['offset'] = $offset;
      $form['num_reg'] = 20;
      $sla = AutoCarga::factory("Citas","classes","app","AgendaMedica");
      $cargos = $sla->ObtenerListadoCargos($form);
      if(!empty($cargos))
      {
        $pgh = AutoCarga::factory("ClaseHTML");
        $est = "modulo_list_claro";
        $action = "BuscarCargos('0'";
        		
        $html .= $pgh->ObtenerPaginadoXajax($sla->conteo,$sla->pagina,$action,false,$form['num_reg']);
			
  			$html .= "<table align=\"center\" class=\"modulo_table_list\" width=\"98%\">";
  			$html .= "	<tr class=\"modulo_table_list_title\">\n";
  			$html .= "  	<td width=\"10%\">CARGO</td>\n";
  			$html .= "  	<td width=\"%\">DESCRIPCION</td>\n";
  			$html .= "  	<td width=\"2%\" ></td>\n";
  			$html .= "	</tr>\n";
  			foreach($cargos as $key => $rst)
  			{
  				$est = ($est == "modulo_list_claro")?  "modulo_list_oscuro": "modulo_list_claro";
  				
  				$html .= "	<tr class=\"".$est."\">\n";
  				$html .= "  	<td align=\"center\" class=\"normal_10AN\">".$rst['cargo']."</td>\n";
  				$html .= "		<td class=\"label\">".$rst['descripcion']."</td>\n";
  				$html .= "		<td>\n";
          if(!empty($cargos_add[$rst['cargo']]))
            $html .= "				<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">\n";
          else
          {
            $html .= "			<a href=\"javascript:AdicionarCargo('".$rst['cargo']."','".$rst['descripcion']."')\">\n";
            $html .= "				<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
            $html .= "			</a>\n";
          }          
  				$html .= "		</td>\n";
  				$html .= "	</tr>\n";
  			}
  			$html .= "</table>\n";
  			$html .= $pgh->ObtenerPaginadoXajax($sla->conteo,$sla->pagina,$action,true,$form['num_reg']);
      }
      else
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
        $html .= "</center>\n";
      }
    }
    $html .= "<table align=\"center\">\n";
    $html .= "	<tr>\n";
    $html .= "    <td height=\"25\">\n";
    $html .= "      <a href=\"javascript:Cerrar('ContenedorI')\" class=\"label_error\">CERRAR</a>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    $objResponse->assign("buscador_resultados","innerHTML",$html);
    $objResponse->script("MostrarSpan('ContenedorI');");
    return $objResponse;
  }
  /**
  * Funcion donde se agregan los cargos
  *
  * @param string $cargo Identificador del cargo
  * @param string $descripcion Descripcion del cargo
  *
  * @return object
  */
  function AdicionarCargo($cargo,$descripcion)
  {
    $objResponse = new xajaxResponse();
    $cargos_add = SessionGetvar("Cagos_Adicionados".UserGetUID());
    
    if(empty($cargos_add[$cargo]))
      $cargos_add[$cargo] = $descripcion;
    else
      unset($cargos_add[$cargo]);
    
    $html = "";
    if(!empty($cargos_add))
    {
      $html .= "<table align=\"center\" class=\"modulo_table_list\" width=\"75%\">";
      $html .= "	<tr class=\"formulacion_table_list\">\n";
      $html .= "  	<td width=\"10%\">CARGO</td>\n";
      $html .= "  	<td width=\"%\">DESCRIPCION</td>\n";
      $html .= "  	<td width=\"2%\" ></td>\n";
      $html .= "	</tr>\n";
      foreach($cargos_add as $key =>$dt)
      {
        $est = ($est == "modulo_list_claro")?  "modulo_list_oscuro": "modulo_list_claro";
        
        $html .= "	<tr class=\"".$est."\">\n";
        $html .= "  	<td align=\"center\" class=\"normal_10AN\">".$key."</td>\n";
        $html .= "		<td class=\"label\">".$dt."</td>\n";
        $html .= "		<td>\n";
        $html .= "			<a href=\"javascript:AdicionarCargo('".$key."','".$dt."')\" title=\"ELIMINAR CARGO\">\n";
        $html .= "				<img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
        $html .= "			</a>\n";
        $html .= "		</td>\n";
        $html .= "	</tr>\n";
      }
      $html .= "</table>\n";
    }
    
    SessionSetVar("Cagos_Adicionados".UserGetUID(), $cargos_add);
    $objResponse->assign("cargos_add","innerHTML",utf8_decode($html));
    
    return $objResponse;
  }
?>