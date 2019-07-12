<?php
	
    IncludeClass("LogicaCF",NULL,"hc","UV_CicloFamiliar");

    
	
	function SeleccionarSFR($td,$ingreso,$tipo_id_paciente,$paciente_id,$factor_riesgo_id,$act,$descripcion,$ciclo_vital_individual_id)
	{
		$path = SessionGetVar("rutaImagenes");
    $objResponse=new xajaxResponse();
    $registrar = new LogicaCF();
		
    if($act =='1')
    {
			$SeleccionarSFR=$registrar->RegistrarFactorRiesgoPaciente($ingreso,$tipo_id_paciente,$paciente_id,$factor_riesgo_id,$ciclo_vital_individual_id);
		  if($SeleccionarSFR===true)
      {
        $java = "javascript:SeleccionarFR('".$td."','".$ingreso."','".$tipo_id_paciente."','".$paciente_id."','".$factor_riesgo_id."','0','".$descripcion."');\"";
        $salida .= "                               <a title='SELECCIONAR ".$descripcion."' class=\"Normal_10AN\" href=\"".$java."\">\n";
        $salida .= "                                 <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"> ".$descripcion."</sub>\n";
        $salida .= "                               </a>\n";
      }
		}
		elseif($act =='0')
		{
			$SeleccionarSFR=$registrar->EliminarFactorRiesgo($ingreso,$tipo_id_paciente,$paciente_id,$factor_riesgo_id);
		  
      if($SeleccionarSFR===true)
      {
        $java = "javascript:SeleccionarFR('".$td."','".$ingreso."','".$tipo_id_paciente."','".$paciente_id."','".$factor_riesgo_id."','1','".$descripcion."');\"";
        $salida .= "                               <a title='SELECCIONAR ".$descripcion."' class=\"Normal_10AN\" href=\"".$java."\">\n";
        $salida .= "                                 <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"> ".$descripcion."</sub>\n";
        $salida .= "                               </a>\n";
      }
		}

    if($SeleccionarSFR===true)
    {
       $resultado1 = "DATOS ACTUALIZADO SATISFACTORIAMENTE";
       $objResponse->assign($td,"innerHTML",$salida);
    }
    else
    {
       $resultado1="INSERCION ESTA MAL".$SeleccionarSFR;
    }
        
    $objResponse->assign("mensaje","innerHTML",$resultado1);
        
    return $objResponse;
	}
	
	
	
	function GuardarFR($ingreso,$tip_pac,$id_pac,$cvi,$fr)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = new LogicaCF();

        $encontrar_registro_FR=$registrar->ConsultaCiclosFR($ingreso,$tip_pac,$id_pac);

        if(!empty($encontrar_registro_FR))
        {
           $resultado=$registrar->EliminarFR($ingreso,$tip_pac,$id_pac);
        }

        if($resultado==true || empty($encontrar_registro))
        {
           $resultado1 = $registrar->InsertarFR($ingreso,$tip_pac,$id_pac,$cvi,$fr);
        }
        else
        {
           $resultado1="INSERCION ESTA MAL";
        }
        
        $objResponse->assign("mensaje","innerHTML",$resultado1);
        
        return $objResponse;




    }


    function GuardarObsCvf($ingreso,$tip_pac,$id_pac,$observaciones)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $registrar = new LogicaCF();

        $encontrar_registro=$registrar->ConsultaCiclosObservaciones($ingreso,$tip_pac,$id_pac);

        if(!empty($encontrar_registro))
        {
           $resultado=$registrar->EliminarCFO($ingreso,$tip_pac,$id_pac);
        }

        if($resultado==true  || empty($encontrar_registro))
        {
           $resultado1=$registrar->InsertarCicloFamiliaresObservaciones($ingreso,$tip_pac,$id_pac,$observaciones);
        }
        else
        {
            $resultado1="INSERCION ESTA MAL";
        }
        
        $objResponse->assign("mensaje","innerHTML",$resultado1);
        
        return $objResponse;

    }



    function Prueba($td_id,$ingreso,$tip_id,$paciente_id,$cvf,$descripcion)
	{
        $path = SessionGetVar("rutaImagenes");
        $objResponse=new xajaxResponse();
        $consulta = new LogicaCF();
        $CCFS=$consulta->ConsultaCiclosFamiliaresPacienteSeleccionado($ingreso,$tip_id,$paciente_id,$cvf,$descripcion);
        if(!empty($CCFS))
        {
           // $objResponse->alert('SSIIII');
            $java = "javascript:SeleccionarCicloFamiliar('".$td_id."','".$ingreso."','".$tip_id."','".$paciente_id."','".$cvf."','".$descripcion."');\"";
            $salida = "<a title='SELECCIONAR ".$descripcion."' class=\"Normal_10AN\" href=\"".$java."\">\n";
            $salida .= "  <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"> ".$descripcion."</sub>\n";
            $salida .= "</a>\n";
            $resultado=$consulta->EliminarCicloFamiliar($ingreso,$tip_id,$paciente_id,$cvf);
        }
        else
        {
            //$objResponse->alert('NOOOOOOO'.$td_id);
            $java = "javascript:SeleccionarCicloFamiliar('".$td_id."','".$ingreso."','".$tip_id."','".$paciente_id."','".$cvf."','".$descripcion."');\"";
            $salida = "<a title='SELECCIONAR ".$descripcion."' class=\"Normal_10AN\" href=\"".$java."\">\n";
            $salida .= "  <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"> ".$descripcion."</sub>\n";
            $salida .= "</a>\n";
            $resultado=$consulta->InsertarCiclosFamiliares($ingreso,$tip_id,$paciente_id,$cvf);
        }

//      $objResponse->alert("solo es un mensaje de prueba");
        $objResponse->assign($td_id,"innerHTML",$salida);
        $objResponse->assign("mensaje","innerHTML",$resultado);
		
		return $objResponse;
	}
?>