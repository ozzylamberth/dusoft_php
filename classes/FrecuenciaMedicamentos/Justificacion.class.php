<?php
	/**
	* $Id: Justificacion.class.php,v 1.8 2011/03/04 14:13:03 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo F. Manrique
	*/	
	class Justificacion 
	{
		var $salida = "";
		var $frec = array("Minuto(s)","Hora(s)","Dia(s)","Semana(s)");
		
		function Justificacion(){}
		/********************************************************************************
		*
		*********************************************************************************/
		function EnviarDatos()
		{
      IncludeClass('ConexionBD');
      $cxn = new ConexionBD();
      
			$datos = $_REQUEST;
			$this->codigo = $_REQUEST['codigo'];
			$sw_ri = $datos['sw_riesgo_inminente'];
			if(!$sw_ri) $sw_ri = "0";
			SessionSetVar("JustificacionMedicamento",$_REQUEST);
			
			$sql .= "SELECT NEXTVAL('hc_justificaciones_no_pos_hospitala_justificacion_no_pos_id_seq') ";
			$rst = $cxn->ConexionBaseDatos($sql);
			
			$id = "";
			if (!$rst->EOF)
			{
				$id = $rst->fields[0];
				$rst->MoveNext();
			}
			
      $cxn->ConexionTransaccion();
      $arreglo = SessionGetVar("MedicamentoAJustificar");
      if(!empty($arreglo))
      {
   			$usuario = UserGetUID();
  			if($arreglo[8]) $usuario = $arreglo[8];

        $sql  = "INSERT INTO hc_formulacion_medicamentos_eventos( ";
  			$sql .= "				ingreso,";
  			$sql .= "				evolucion_id,";
  			$sql .= "				codigo_producto,";
  			$sql .= "				usuario_id,";
  			$sql .= "				fecha_registro,";
  			$sql .= "				observacion,";
  			$sql .= "				via_administracion_id,";
  			$sql .= "				unidad_dosificacion,";
  			$sql .= "				dosis,";
  			$sql .= "				frecuencia,";
  			$sql .= "				cantidad, ";
  			$sql .= "				usuario_registro, ";
  			$sql .= "				sw_no_pos_peticion_paciente, ";
  			$sql .= "				dias_tratamiento ";
  			$sql .= "				) ";
  			$sql .= "VALUES( ";
  			$sql .= "				 ".SessionGetVar("IngresoHc").", ";
  			$sql .= "				 ".SessionGetVar("EvolucionHc").", ";
  			$sql .= "				'".$arreglo[0]."', ";
  			$sql .= "				 ".$usuario.",";
  			$sql .= "				 NOW(),";
  			$sql .= "				'".$arreglo[5]."',";
  			$sql .= "				'".$arreglo[4]."',";
  			$sql .= "				'".$arreglo[1]."',";
  			$sql .= "				 ".$arreglo[3].",";
  			$sql .= "				'".$arreglo[6]."',";
  			$sql .= "				 ".$arreglo[2].", ";
  			$sql .= "				 ".UserGetUID().", ";
  			$sql .= "				 '".$arreglo[9]."', ";
  			$sql .= "				 '".$arreglo[10]."' ";
  			$sql .= "				) ";
  			
  			if(!$rst = $cxn->ConexionTransaccion($sql))
        {
          echo $cxn->mensajeDeError;
          return false;
        }
        
        $add_dias = 1;
        if($arreglo[12] == "Dia(s)")
          $add_dias = $arreglo[11];
        else if($arreglo[12] == "Semana(s)")
          $add_dias = 7*$arreglo[11];
        
        $fecha_fin = date("Y-m-d", mktime(0, 0, 0,date("m"),(date("d")+$arreglo[10]-1),date("Y")));
        
        $sql  = "INSERT INTO solicitudes_tratamiento ";
  			$sql .= "   ( ";
  			$sql .= "       solicitud_tratamiento_id, ";
  			$sql .= "       ingreso, ";
  			$sql .= "       codigo_medicamento, ";
  			$sql .= "       dias_tratamiento, ";
        $sql .= "				cantidad, ";
  			$sql .= "       intensidad_cantidad, ";
  			$sql .= "       intensidad, ";
  			$sql .= "       incremento_dias, ";
  			$sql .= "       fecha_inicio, ";
  			$sql .= "       fecha_siguiente_solictud, ";
  			$sql .= "       fecha_finalizacion ";
        $sql .= "		) ";
        $sql .= "VALUES ";
        $sql .= "		( ";
        $sql .= "         DEFAULT, ";
        $sql .= "				  ".SessionGetVar("IngresoHc").", ";
        $sql .= "			   '".$arreglo[0]."', ";
        $sql .= "				  ".$arreglo[10].", ";
        $sql .= "				  ".$arreglo[3].", ";
        $sql .= "				  ".$arreglo[11].", ";
        $sql .= "				 '".$arreglo[12]."', ";
        $sql .= "				  ".$add_dias.", ";
        $sql .= "				  NOW(), ";
        $sql .= "				  NOW(), ";
        $sql .= "				 '".$fecha_fin."' ";
        $sql .= "		); ";
        
   			if(!$rst = $cxn->ConexionTransaccion($sql))
        {
          echo $cxn->mensajeDeError;
          return false;
        }
      }
      
      $sql  = "INSERT INTO hc_justificaciones_no_pos_hospitalaria_medicamentos ( ";	
			$sql .= "			justificacion_no_pos_id,"; 	 	
			$sql .= "			ingreso,"; 	 	
			$sql .= "			codigo_producto,"; 	
			$sql .= "			usuario_id_autoriza,"; 	 	
			$sql .= "			duracion,";
			$sql .= "			dosis_dia,"; 	
			$sql .= "			justificacion,"; 		
			$sql .= "			ventajas_medicamento,";
			$sql .= "			ventajas_tratamiento,";
			$sql .= "			precauciones,";
			$sql .= "			controles_evaluacion_efectividad,";
			$sql .= "			tiempo_respuesta_esperado,";
			$sql .= "			riesgo_inminente,";
			$sql .= "			sw_riesgo_inminente,"; 	
			$sql .= "			sw_agotadas_posibilidades_existentes,";
			$sql .= "			sw_comercializacion_pais,";
			$sql .= "			sw_homologo_pos,";
			$sql .= "			descripcion_caso_clinico,";
			$sql .= "			sw_existe_alternativa_pos, ";
			$sql .= "			efecto, ";
			$sql .= "			fecha_registro, ";
			$sql .= "			indicacion_terapeutica, ";
			$sql .= "			tipo_solicitud ";
			$sql .= "			) ";
			$sql .= "VALUES (";
			$sql .= "			 ".$id.",";
			$sql .= "			 ".SessionGetVar("IngresoHc").",";
			$sql .= "			'".$this->codigo."',";
			$sql .= "			 ".UserGetUID().",";
			$sql .= "			'".$datos['duracion_tratamiento']."',";
			$sql .= "			'".$datos['dosis_dia']."',";
			$sql .= "			'".$datos['justificacion_solicitud']."',";
			$sql .= "			'".$datos['ventajas_medicamento']."',";
			$sql .= "			'".$datos['ventajas_tratamiento']."',";
			$sql .= "			'".$datos['precauciones']."',";
			$sql .= "			'".$datos['controles_evaluacion_efectividad']."',";
			$sql .= "			'".$datos['tiempo_respuesta_esperado']."',";
			$sql .= "			'".$datos['riesgo_inminente']."',";
			$sql .= "			'".$sw_ri."',";
			$sql .= "			'".$datos['sw_agotadas_posibilidades_existentes']."',";
			$sql .= "			'".$datos['sw_comercializacion_pais']."',";
			$sql .= "			'".$datos['sw_homologo_pos']."',";
			$sql .= "			'".$datos['descripcion_caso_clinico']."',";
			$sql .= "			'".$null."',";
			$sql .= "			'".$datos['efecto']."',";
			$sql .= "			'NOW()',";
			$sql .= "			'".$datos['indicacion_terapeutica']."',";
			$sql .= "			'".$datos['tipo_solicitud']."'";
			$sql .= "			)";
			
 			if(!$rst = $cxn->ConexionTransaccion($sql))
      {
        echo $cxn->mensajeDeError;
        return false;
      }
			
			$diag = SessionGetVar("diagnosticos");
			foreach($diag as $key => $diagnosticos)
			{
				$sql  = "INSERT INTO hc_justificaciones_no_pos_hospitalaria_medicamentos_diagnostico( ";
				$sql .= "				justificacion_no_pos_id,";
				$sql .= "				diagnostico_id ";
				$sql .= "				) ";
				$sql .= "VALUES ( ";
				$sql .= "			 ".$id.",";			
				$sql .= "			 '".$key."' ";			
				$sql .= "				) ";
				
   			if(!$rst = $cxn->ConexionTransaccion($sql))
        {
          echo $cxn->mensajeDeError;
          return false;
        }
			}
			
			if($datos['medicamento_pos1'] || $datos['principio_activo_pos1'])
			{
				$sm = $rs = $ci = "0";
				if($datos['sw_no_mejoria1']) $sm = "1";
				if($datos['sw_reaccion_secundaria1']) $rs = "1";
				if($datos['sw_contraindicacion1']) $ci = "1";
				
				$sql  = "INSERT INTO hc_justificaciones_no_pos_hospitalaria_medicamentos_alternativa (";
				$sql .= "				justificacion_no_pos_id,"; 	 	
				$sql .= "				alternativa_pos_id,";
				$sql .= "				medicamento_pos,";
				$sql .= "				principio_activo,";
				$sql .= "				dosis_dia_pos,";
				$sql .= "				duracion_pos,";
				$sql .= "				sw_no_mejoria,";
				$sql .= "				sw_reaccion_secundaria,";
				$sql .= "				reaccion_secundaria,";
				$sql .= "				sw_contraindicacion,";
				$sql .= "				contraindicacion,";
				$sql .= "				presentacion,";
				$sql .= "				frecuencia,";
				$sql .= "				cantidad,";
				$sql .= "				otras ";
				$sql .= ")";
				$sql .= "VALUES(";
				$sql .= "			 ".$id.",";
				$sql .= "			 1,";
				$sql .= "			 '".$datos['medicamento_pos1']."',";
				$sql .= "			 '".$datos['principio_activo_pos1']."',";
				$sql .= "			 '".$datos['dosis_dia_pos1']."',";
				$sql .= "			 '".$datos['duracion_tratamiento_pos1']."',";
				$sql .= "			 '".$sm."',";
				$sql .= "			 '".$rs."',";
				$sql .= "			 '".$datos['reaccion_secundaria1']."',";
				$sql .= "			 '".$ci."',";
				$sql .= "			 '".$datos['contraindicacion1']."',";
				$sql .= "			 '".$datos['presentacion_pos1']."', ";
				$sql .= "			 '".$datos['frecuencia_pos1']."', ";
				$sql .= "			 '".$datos['cantidad_pos_medica1']."', ";
				$sql .= "			 '".$datos['otras1']."' ";
				$sql .= ")";
				
   			if(!$rst = $cxn->ConexionTransaccion($sql))
        {
          echo $cxn->mensajeDeError;
          return false;
        }
			}
			
			if($datos['medicamento_pos2'] || $datos['principio_activo_pos2'])
			{
				$sm = $rs = $ci = "0";
				if($datos['sw_no_mejoria2']) $sm = $datos['sw_no_mejoria2'];
				if($datos['sw_reaccion_secundaria2']) $rs = $datos['sw_reaccion_secundaria2'];
				if($datos['sw_contraindicacion2']) $ci = $datos['sw_contraindicacion2'];
				
				$sql = "INSERT INTO hc_justificaciones_no_pos_hospitalaria_medicamentos_alternativa (";
				$sql .= "				justificacion_no_pos_id,"; 	 	
				$sql .= "				alternativa_pos_id,";
				$sql .= "				medicamento_pos,";
				$sql .= "				principio_activo,";
				$sql .= "				dosis_dia_pos,";
				$sql .= "				duracion_pos,";
				$sql .= "				sw_no_mejoria,";
				$sql .= "				sw_reaccion_secundaria,";
				$sql .= "				reaccion_secundaria,";
				$sql .= "				sw_contraindicacion,";
				$sql .= "				contraindicacion,";
				$sql .= "				presentacion,";
				$sql .= "				frecuencia,";
				$sql .= "				cantidad,";
				$sql .= "				otras ";
				$sql .= ")";
				$sql .= "VALUES(";
				$sql .= "			 ".$id.",";
				$sql .= "			 2,";
				$sql .= "			 '".$datos['medicamento_pos2']."',";
				$sql .= "			 '".$datos['principio_activo_pos2']."',";
				$sql .= "			 '".$datos['dosis_dia_pos2']."',";
				$sql .= "			 '".$datos['duracion_tratamiento_pos2']."',";
				$sql .= "			 '".$sm."',";
				$sql .= "			 '".$rs."',";
				$sql .= "			 '".$datos['reaccion_secundaria2']."',";
				$sql .= "			 '".$ci."',";
				$sql .= "			 '".$datos['contraindicacion2']."',";
				$sql .= "			 '".$datos['presentacion_pos2']."',";
				$sql .= "			 '".$datos['frecuencia_pos2']."',";
				$sql .= "			 '".$datos['cantidad_pos_medica2']."',";
				$sql .= "			 '".$datos['otras2']."' ";
				$sql .= ")";
				
   			if(!$rst = $cxn->ConexionTransaccion($sql))
        {
          echo $cxn->mensajeDeError;
          return false;
        }
			}
      
      $sql  = "INSERT INTO hc_justificaciones_no_pos_medicamento_sustituto ";
      $sql .= "     (";
      $sql .= "       hc_justificacion_no_pos_medicamento_sustituto_id,";
      $sql .= "       justificacion_no_pos_id,";
      $sql .= "       medicamento,";
      $sql .= "       principio_activo,";
      $sql .= "       presentacion,";
      $sql .= "       frecuencia,";
      $sql .= "       dosis,";
      $sql .= "       tiempo_tratamiento "; 
      $sql .= "     ) ";
      $sql .= "VALUES ";
      $sql .= "     ( ";
      $sql .= "       DEFAULT, ";
      $sql .= "       ".$id.", ";
      $sql .= "			 '".$datos['medicamentoS']."',";
      $sql .= "			 '".$datos['principio_activoS']."',";
      $sql .= "			 '".$datos['presentacionS']."',";
      $sql .= "			 '".$datos['frecuenciaS']."',";
      $sql .= "			 '".$datos['dosis_diaS']."',";
      $sql .= "			 '".$datos['duracion_tratamientoS']."' ";
      $sql .= "     ) ";
      
 			if(!$rst = $cxn->ConexionTransaccion($sql))
      {
        echo $cxn->mensajeDeError;
        return false;
      }
      
			$cxn->Commit();
      if(!empty($arreglo))
      {
        $this->RegistrarSubmoduloAlterno();

        $this->ConsultaMedicamento($arreglo[0]);
        $medicamentos = SessionGetVar("MedicamentosFormulados");
        
        $profesional = SessionGetVar("SolicitudAutorizacion");
        if($profesional == 1 || $profesional == 2)
        {
          $texto  = "MEDICAMENTO FORMULADO: ".$medicamentos[$arreglo[0]]['producto'];
          $texto .= "	".$arreglo[3]." ".$medicamentos[$arreglo[0]]['unidad_dosificacion']." ".$arreglo[6].", VIA: ".$medicamentos[$arreglo[0]]['nombre'];
          $texto .= ", DIAS DE TRATAMIENTO: ".$arreglo[10];
          if($arreglo[5] != "")
            $texto .= " \nOBSERVACIONES: ".$arreglo[5];
          
          $pt_texto = $this->PlanTerapeuticoActual(SessionGetVar("EvolucionHc"));
          if($pt_texto != "")
          {
            $sql  = "UPDATE hc_plan_terapeutico "; 
            $sql .= "SET    descripcion = '".$pt_texto." \n".$texto."' "; 
            $sql .= "WHERE  evolucion_id = ".SessionGetVar("EvolucionHc")." ";
          }
          else
          {
            $sql  = "INSERT INTO hc_plan_terapeutico ";
            $sql .= "     (";
            $sql .= "       descripcion, ";
            $sql .= "       evolucion_id";
            $sql .= "     ) ";
            $sql .= "VALUES ";
            $sql .= "     (";
            $sql .= "       '".$texto."', ";
            $sql .= "        ".SessionGetVar("EvolucionHc")."";
            $sql .= "     )";
          }
     			if(!$rst = $cxn->ConexionbaseDatos($sql))
          {
            echo $cxn->mensajeDeError;
            return false;
          }
          $this->RegistrarSubmoduloAlterno("PlanTerapeuticoTexto");
        }
        SessionDelVar("MedicamentoAJustificar");
      }
      
			$script .= "<script>\n";
			if(empty($arreglo))
        $script .= "	window.opener.document.getElementById('justificacion".$this->codigo."').innerHTML = \"<a href=\\\"javascript:Justificar('".$this->codigo."','".$id."')\\\" class='normal_10AN'>VER JUSTIFICACIÓN</a>\";";
			else
        $script .= "	window.opener.Continuar();";
			$script .= "	window.close();\n";
			$script .= "</script>\n";
			$html .= ReturnHeader('Frecuencias',$script);
			$html .= ReturnBody()."\n";	
			$html .= ReturnFooter();
		}
    /**
		* Metodo donde se consulta la informacion del medicamento
    *
    * @param string $codigo Codigo del medicamento
		*
    * @return boolean
    */
		function ConsultaMedicamento($codigo)
		{
			$sql  = "SELECT ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				HA.nombre, ";
			$sql .= "				FM.dosis, ";
			$sql .= "				FM.unidad_dosificacion, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.ingreso, ";
			$sql .= "				FM.observacion, ";
			$sql .= "				CASE WHEN FM.sw_estado = '8' THEN '0'";
			$sql .= "						ELSE FM.sw_estado END AS sw_estado, ";
			$sql .= "				FM.codigo_producto, ";
			$sql .= "				FM.via_administracion_id, ";
			$sql .= "				FM.frecuencia, ";
			$sql .= "				IF.descripcion AS forma, ";
			$sql .= "				ME.concentracion_forma_farmacologica AS cff, ";
			$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS' ";
			$sql .= "				ELSE 'NO POS' END AS item, ";
			$sql .= "				SU.nombre AS med_formula, ";
			$sql .= "				SD.nombre AS med_modifica, ";
			$sql .= "				SU.usuario_id, ";
			$sql .= "				FM.sw_confirmacion_formulacion, ";
			$sql .= "				FH.usuario_registro, ";
			$sql .= "				FM.sw_requiere_autorizacion_no_pos, ";
			$sql .= "				FM.justificacion_no_pos_id, ";
			$sql .= "				ID.sw_solicita_autorizacion, ";
			$sql .= "				FH.dias_tratamiento ";
			$sql .= "FROM 	inv_med_cod_principios_activos AS IA, ";
			$sql .= "				hc_formulacion_medicamentos FM,";
			$sql .= "				hc_formulacion_medicamentos_eventos FH,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				inv_med_cod_forma_farmacologica AS IF, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_vias_administracion HA, ";
			$sql .= "				system_usuarios SU, ";
			$sql .= "				system_usuarios SD ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND		FM.ingreso = ".SessionGetVar("IngresoHc")." ";
			$sql .= "AND		FM.codigo_producto = '".$codigo."' ";
			$sql .= "AND		FH.codigo_producto = '".$codigo."' ";
			$sql .= "AND		FH.num_reg = FM.num_reg ";
			$sql .= "AND		HA.via_administracion_id = FM.via_administracion_id ";
			$sql .= "AND 		IF.cod_forma_farmacologica = ME.cod_forma_farmacologica ";
			$sql .= "AND		SU.usuario_id = FH.usuario_id ";
			$sql .= "AND		SD.usuario_id = FH.usuario_registro ";
			$sql .= "ORDER BY FM.sw_estado ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();

			while (!$rst->EOF)
			{
				$datos[$codigo] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			$datos[$codigo]['activar'] = "1";

			$documentos = SessionGetVar("MedicamentosFormulados");
			foreach($documentos as $key=>$medica)
				$datos[$key] = $medica;

			SessionSetVar("MedicamentosFormulados",$datos);
		}
    /**
    * Metodo donde se obtiene el plan terapeutico actual
    *
    * @param integer $evolucion Identificador de la evolucion
    *
    * @return mixed
    */
  	function PlanTerapeuticoActual($evolucion)
  	{
  		$sql  = "SELECT descripcion ";
      $sql .= "FROM   hc_plan_terapeutico ";
      $sql .= "WHERE  evolucion_id = ".$evolucion." ";
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
  		return $datos['descripcion'];
  	}
		/**
		*
		*/
		function ActualizarDatos()
		{
			$datos = $_REQUEST;
			$this->codigo = $datos['codigo'];
			$sw_ri = $datos['sw_riesgo_inminente'];

			if(!$sw_ri) $sw_ri = "0";			
			
			$id = $datos['justifica'];
			
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
			$dbconn->BeginTrans();

			$sql  = "UPDATE hc_justificaciones_no_pos_hospitalaria_medicamentos  ";	
			$sql .= "SET		usuario_id_autoriza =	 ".UserGetUID().",";
			$sql .= "				duracion = '".$datos['duracion_tratamiento']."',";
			$sql .= "				dosis_dia = '".$datos['dosis_dia']."',";
			$sql .= "				justificacion = '".$datos['justificacion_solicitud']."',";
			$sql .= "				ventajas_medicamento = '".$datos['ventajas_medicamento']."',";
			$sql .= "				ventajas_tratamiento = '".$datos['ventajas_tratamiento']."',";
			$sql .= "				precauciones = '".$datos['precauciones']."',";
			$sql .= "				controles_evaluacion_efectividad = '".$datos['controles_evaluacion_efectividad']."',";
			$sql .= "				tiempo_respuesta_esperado = '".$datos['tiempo_respuesta_esperado']."',";
			$sql .= "				riesgo_inminente = '".$datos['riesgo_inminente']."',";
			$sql .= "				sw_riesgo_inminente = '".$sw_ri."',";
			$sql .= "				sw_agotadas_posibilidades_existentes = '".$datos['sw_agotadas_posibilidades_existentes']."',";
			$sql .= "				sw_comercializacion_pais = '".$datos['sw_comercializacion_pais']."',";
			$sql .= "				sw_homologo_pos = '".$datos['sw_homologo_pos']."',";
			$sql .= "				descripcion_caso_clinico = '".$datos['descripcion_caso_clinico']."',";
			$sql .= "				efecto = '".$datos['efecto']."',";
			$sql .= "				sw_existe_alternativa_pos =	'".$null."', ";
			$sql .= "				indicacion_terapeutica =	'".$datos['indicacion_terapeutica']."', ";
			$sql .= "				tipo_solicitud =	'".$datos['tipo_solicitud']."' ";			
			$sql .= "WHERE 	justificacion_no_pos_id = ".$id." "; 	 	
			$sql .= "AND		codigo_producto = '".$this->codigo."' "; 	
			$sql .= "AND		ingreso = ".SessionGetVar("IngresoHc")." "; 	 	

			$rst = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0) 
			{
				echo "<b class=\"label\">Error al iniciar la transaccion Error DB :A " . $dbconn->ErrorMsg()."</b>";
				$dbconn->RollbackTrans();
				return false;
			}
			
			$diag1 = SessionGetVar("diagnosticos");
			$diag2 = SessionGetVar("diagnosticosA");
			foreach($diag1 as $key => $daignosticos)
			{
				if($key == $diag2[$key]['diagnostico_id'])
				{
					unset($diag1[$key]);
					unset($diag2[$key]);
				}
			}
			
			foreach($diag1 as $key => $diagnosticos)
			{
				$sql  = "INSERT INTO hc_justificaciones_no_pos_hospitalaria_medicamentos_diagnostico( ";
				$sql .= "				justificacion_no_pos_id,";
				$sql .= "				diagnostico_id ";
				$sql .= "				) ";
				$sql .= "VALUES ( ";
				$sql .= "			 ".$id.",";			
				$sql .= "			 '".$key."' ";			
				$sql .= "				) ";
				
				$rst = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) 
				{
					echo "<b class=\"label\">Error al iniciar la transaccion Error DB :X " . $dbconn->ErrorMsg()."</b>";
					$dbconn->RollbackTrans();
					return false;
				}
			}
			
			foreach($diag2 as $key => $diagnosticos)
			{
				$sql  = "DELETE FROM hc_justificaciones_no_pos_hospitalaria_medicamentos_diagnostico ";
				$sql .= "WHERE	justificacion_no_pos_id = ".$id." ";
				$sql .= "AND		diagnostico_id = '".$key."' ";
				
				$rst = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) 
				{
					echo "<b class=\"label\">Error al iniciar la transaccion Error DB : Y " . $dbconn->ErrorMsg()."</b>";
					$dbconn->RollbackTrans();
					return false;
				}
			}
			
			$alternativas = SessionGetVar("Alternativas");
			for($i = 1; $i<3; $i++)
			{
				if(($datos['medicamento_pos'.$i] || $datos['principio_activo_pos'.$i]) && sizeof($alternativas[$i])== 0)
				{
					$sm = $rs = $ci = "0";
					if($datos['sw_no_mejoria'.$i]) $sm = $datos['sw_no_mejoria'.$i];
					if($datos['sw_reaccion_secundaria'.$i]) $rs = $datos['sw_reaccion_secundaria'.$i];
					if($datos['sw_contraindicacion'.$i]) $ci = $datos['sw_contraindicacion'.$i];
					
					$sql = "INSERT INTO hc_justificaciones_no_pos_hospitalaria_medicamentos_alternativa (";
					$sql .= "				justificacion_no_pos_id,"; 	 	
					$sql .= "				alternativa_pos_id,";
					$sql .= "				medicamento_pos,";
					$sql .= "				principio_activo,";
					$sql .= "				dosis_dia_pos,";
					$sql .= "				duracion_pos,";
					$sql .= "				sw_no_mejoria,";
					$sql .= "				sw_reaccion_secundaria,";
					$sql .= "				reaccion_secundaria,";
					$sql .= "				sw_contraindicacion,";
					$sql .= "				contraindicacion,";
					$sql .= "				presentacion,";
					$sql .= "				frecuencia,";
					$sql .= "				cantidad,";
					$sql .= "				otras ";
					$sql .= ")";
					$sql .= "VALUES(";
					$sql .= "			 ".$id.",";
					$sql .= "			 $i,";
					$sql .= "			 '".$datos['medicamento_pos'.$i]."',";
					$sql .= "			 '".$datos['principio_activo_pos'.$i]."',";
					$sql .= "			 '".$datos['dosis_dia_pos'.$i]."',";
					$sql .= "			 '".$datos['duracion_tratamiento_pos'.$i]."',";
					$sql .= "			 '".$sm."',";
					$sql .= "			 '".$rs."',";
					$sql .= "			 '".$datos['reaccion_secundaria'.$i]."',";
					$sql .= "			 '".$ci."',";
					$sql .= "			 '".$datos['contraindicacion'.$i]."',";
					$sql .= "			 '".$datos['presentacion_pos'.$i]."',";
					$sql .= "			 '".$datos['frecuencia_pos'.$i]."',";
					$sql .= "			 '".$datos['cantidad_pos_medica'.$i]."',";
					$sql .= "			 '".$datos['otras'.$i]."' ";
					$sql .= ")";
					
					$rst = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0) 
					{
						echo "<b class=\"label\">Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg()."</b>";
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}
			
			for($i = 1; $i<3; $i++)
			{
				if(($datos['medicamento_pos'.$i] || $datos['principio_activo_pos'.$i]) && sizeof($alternativas[$i]) > 0)
				{
					$sm = $rs = $ci = "0";
					if($datos['sw_no_mejoria'.$i]) $sm = $datos['sw_no_mejoria'.$i];
					if($datos['sw_reaccion_secundaria'.$i]) $rs = $datos['sw_reaccion_secundaria'.$i];
					if($datos['sw_contraindicacion'.$i]) $ci = $datos['sw_contraindicacion'.$i];
					
					$sql  = "UPDATE	hc_justificaciones_no_pos_hospitalaria_medicamentos_alternativa ";
					$sql .= "SET		medicamento_pos = '".$datos['medicamento_pos'.$i]."',";
					$sql .= "				principio_activo = '".$datos['principio_activo_pos'.$i]."',";
					$sql .= "				dosis_dia_pos = '".$datos['dosis_dia_pos'.$i]."' ,";
					$sql .= "				duracion_pos = '".$datos['duracion_tratamiento_pos'.$i]."',";
					$sql .= "				sw_no_mejoria =  '".$sm."',";
					$sql .= "				sw_reaccion_secundaria = '".$rs."',";
					$sql .= "				reaccion_secundaria = '".$datos['reaccion_secundaria'.$i]."' ,";
					$sql .= "				sw_contraindicacion = '".$ci."',";
					$sql .= "				contraindicacion =  '".$datos['contraindicacion'.$i]."',";
					$sql .= "				presentacion =  '".$datos['presentacion_pos'.$i]."',";
					$sql .= "				frecuencia =  '".$datos['frecuencia_pos'.$i]."',";
					$sql .= "				cantidad =  '".$datos['cantidad_pos_medica'.$i]."',";
					$sql .= "				otras = '".$datos['otras'.$i]."' ";
					$sql .= "WHERE	justificacion_no_pos_id = ".$id." ";
					$sql .= "AND		alternativa_pos_id = $i ";
					
					$rst = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0) 
					{
						echo "<b class=\"label\">Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg()."</b>";
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}
			
      if($datos['sustituto_id'])
      {
        $sql  = "UPDATE hc_justificaciones_no_pos_medicamento_sustituto ";
        $sql .= "SET    medicamento = '".$datos['medicamentoS']."',";
        $sql .= "       principio_activo = '".$datos['principio_activoS']."',";
        $sql .= "       presentacion = '".$datos['presentacionS']."',";
        $sql .= "       frecuencia = '".$datos['frecuenciaS']."',";
        $sql .= "       dosis = '".$datos['dosis_diaS']."',";
        $sql .= "       tiempo_tratamiento = '".$datos['duracion_tratamientoS']."' "; 
        $sql .= "WHERE  hc_justificacion_no_pos_medicamento_sustituto_id = ".$datos['sustituto_id']." ";
        $sql .= "AND    justificacion_no_pos_id = ".$id." ";
      }
      else
      {
        $sql  = "INSERT INTO hc_justificaciones_no_pos_medicamento_sustituto ";
        $sql .= "     (";
        $sql .= "       hc_justificacion_no_pos_medicamento_sustituto_id,";
        $sql .= "       justificacion_no_pos_id,";
        $sql .= "       medicamento,";
        $sql .= "       principio_activo,";
        $sql .= "       presentacion,";
        $sql .= "       frecuencia,";
        $sql .= "       dosis,";
        $sql .= "       tiempo_tratamiento "; 
        $sql .= "     ) ";
        $sql .= "VALUES ";
        $sql .= "     ( ";
        $sql .= "       DEFAULT, ";
        $sql .= "       ".$id.", ";
        $sql .= "			 '".$datos['medicamentoS']."',";
        $sql .= "			 '".$datos['principio_activoS']."',";
        $sql .= "			 '".$datos['presentacionS']."',";
        $sql .= "			 '".$datos['frecuenciaS']."',";
        $sql .= "			 '".$datos['dosis_diaS']."',";
        $sql .= "			 '".$datos['duracion_tratamientoS']."' ";
        $sql .= "     ) ";
      }
      $rst = $dbconn->Execute($sql);
      if ($dbconn->ErrorNo() != 0) 
      {
        echo "<b class=\"label\">Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg()."</b>";
        $dbconn->RollbackTrans();
        return false;
      }
      
			$dbconn->CommitTrans();
			
			$script .= "<script>\n";
			$script .= "	window.close();\n";
			$script .= "</script>\n";
			$html .= ReturnHeader('Frecuencias',$script);
			$html .= ReturnBody()."\n";	
			$html .= ReturnFooter();
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function Inicializar()
		{			
			$this->codigo = $_REQUEST['codigo'];
			$this->justifica = $_REQUEST['justifica'];
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/jsrsClient.js\"></script>\n";
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/Formulacion.js\"></script>\n";
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/cross-browser/x/x_core.js\"></script>\n";
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/cross-browser/x/x_drag.js\"></script>\n";
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/cross-browser/x/x_event.js\"></script>\n";

			$this->salida .= ReturnHeader('Frecuencias',$this->scripts);
			$this->salida .= ReturnBody()."\n";	
			$this->JustificacionMedicamentos();
			$this->salida .=ReturnFooter();
		}
		/*************************************************************************
		*
		**************************************************************************/
		function JustificacionMedicamentos()
		{
			SessionSetVar("rutaimag",GetThemePath());
			$codigos = SessionGetVar("MedicamentosSeleccionados");
			SessionDelVar("diagnosticos");
      $datosAdministrativos = SessionGetVar("DatosAdministrativos");

      $sustituto = array();
			if($this->justifica)
			{
				$datos = $this->ObtenerJustificacion($this->codigo,$this->justifica);
				$this->Diagnosticos($this->justifica);
				$alterna = $this->ObtenerAlternativas($this->justifica);
				$sustituto = $this->ObtenerMedicamentoSustituto($this->justifica);
        $medica = $this->MedicamentosJustificados(SessionGetVar("IngresoHc"),$this->codigo);
			}
			else
			{
				$datos = $this->ObtenerPredetermiado($this->codigo);
				$sustituto = $datos;
				$diag_ingreso = $this->DiagnosticosIngreso(SessionGetVar("IngresoHc"));
				foreach($diag_ingreso as $key => $resultado)
				{
					$diag[$key]['diagnostico_id'] = $diag_ingreso[$key]['diagnostico_id'];
					$diag[$key]['diagnostico_nombre'] = $diag_ingreso[$key]['diagnostico_nombre'];
					SessionSetVar("diagnosticos",$diag);
				}
        $arreglo = SessionGetVar("MedicamentoAJustificar");
        $medica = $codigos[$arreglo[0]];
        $medica['cantidad'] = $arreglo[2];
        $medica['dias_tratamiento'] = $arreglo[10];
			}
               
			$html .= "<div id=\"error\" class=\"label_error\" style=\"text-align:center\"></div>\n";
			$html .= "<form name=\"formajus\" action=\"javascript:EvaluarDatos(document.formajus)\" method=\"post\">\n";
			$html .= "	<table  align=\"center\" border=\"0\"  width=\"97%\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"formulacion_table_list\">\n";
      $html .= "  		<td align=\"center\" colspan=\"5\">JUSTIFICACION DE MEDICAMENTOS NO POS</td>\n";
      $html .= "		</tr>\n";
      $html .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
      $html .= "  		<td align=\"center\" class=\"normal_10AN\" width=\"35%\">PRODUCTO</td>\n";
      $html .= "  		<td align=\"center\" class=\"normal_10AN\" width=\"25%\">PRINCIPIO ACTIVO</td>\n";
      $html .= "  		<td align=\"center\" class=\"normal_10AN\" width=\"20%\">CONCENTRACION</td>\n";
      $html .= "  		<td align=\"center\" class=\"normal_10AN\" width=\"20%\">FORMA</td>\n";
      $html .= "		</tr>";
      $html .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
      $html .= "			<td align=\"center\" class=\"normal_10AN\" >".$medica['producto']."</td>\n";
      $html .= "			<td align=\"center\" class=\"normal_10AN\" >".$medica['principio_activo']."</td>\n";
      $html .= "			<td align=\"center\" class=\"normal_10AN\" >".$medica['cff']."</td>\n";
      $html .= "			<td align=\"center\" class=\"normal_10AN\" >".$medica['forma_farma']."</td>\n";
      $html .= "		</tr>";
      $html .= "		<tr class=\"modulo_list_claro\">\n";
      $html .= "			<td class=\"label\" align=\"left\" >DOSIS POR DIA</td>\n";
			$html .= "			<td class=\"label\" align=\"left\" >\n";
			$html .= "			  ".($medica['cantidad']*1)."\n";
			$html .= "				<input type='hidden' name = 'dosis_dia' value =\"".$medica['cantidad']."\">\n";
			$html .= "			</td>\n";
			$html .= "			<td class=\"label\" align=\"left\" >DIAS DE TRATAMIENTO</td>\n";
			$html .= "			<td class=\"label\" align=\"left\" >\n";
			$html .= "			  ".($medica['dias_tratamiento']*1)."\n";
			$html .= "			  <input type='hidden' name='duracion_tratamiento' value =\"".$medica['dias_tratamiento']."\">\n";
			$html .= "			</td>\n" ;
			$html .= "		</tr>\n";
      $html .= "		<tr class=\"modulo_list_claro\">\n";			
			$html .= "		  <td colspan=\"4\" class=\"label\">EFECTO DESEADO</td>\n";
      $html .= "		</tr>\n";
 			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "			<td align='center' colspan=\"4\" class=\"label\">\n";
			$html .= "				<textarea style = \"width:100%\" class='textarea' name = 'efecto' rows ='2'>".$datos['efecto']."</textarea>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
      $html .= "		<tr class=\"modulo_list_claro\">\n";
      $html .= "			<td colspan = \"4\">\n";
      $html .= "				<table class=\"label\" width=\"100%\">\n";
      $html .= "					<tr class=\"modulo_table_list_title\">\n";
      $html .= "						<td colspan=\"5\" >DIAGNOSTICO</td>\n";
      $html .= "					</tr>\n";
      $html .= "					<tr class=\"modulo_list_claro\" id=\"diagnosticos\">\n";
      $html .= "						<td colspan = 5 align=\"center\">\n";
      $html .= "						</td>\n";
      $html .= "					</tr>\n";
      $html .= "					<tr class=\"modulo_list_claro\">\n";
      $html .= "						<td colspan = '5' align=\"center\">\n";
      $html .= "							<a href=\"javascript:IniciarB();MostrarSpan('FacturasB')\" class=\"label_error\">AGREGAR DIAGNOSTICOS</a>\n";
      $html .= "						</td>\n";
      $html .= "					</tr>\n";
      $html .= "					<tr class=\"modulo_table_list_title\">\n";
      $html .= "						<td colspan=\"5\" align=\"left\" >DESCRIPCION DEL CASO CLINICO</td>";
      $html .= "					</tr>\n";
      $html .= "					<tr>\n";
      $html .= "						<td colspan = 5 align='center' >\n";
      $html .= "							<textarea style =\"width:100%\" class='textarea' name = 'descripcion_caso_clinico' rows = '2'>".$datos['descripcion_caso_clinico']."</textarea>\n";
      $html .= "						</td>\n";
      $html .= "					</tr>\n";
      $html .= "					<tr class=\"modulo_table_list_title\">";
      $html .= "						<td colspan=\"5\" >ALTERNATIVAS POS PREVIAMENTE UTILIZADAS</td>";
      $html .= "					</tr>\n";
			for ($j=1;$j<3;$j++)
			{
				if ($j==1)
					$ti = "PRIMERA";
				else
					$ti = "SEGUNDA";
				
				
				$html .= "					<tr class=\"modulo_table_title\">";
				$html .= "						<td colspan=\"5\" width=\"80%\"align=\"left\" >$ti POSIBILIDAD TERAPEUTICA POS</td>";
				$html .= "					</tr>";
				$html .= "					<tr>\n";
				$html .= "						<td colspan=\"5\" align=\"left\">\n";
				$html .= "							<table width=\"100%\">\n";
				$html .= "								<tr class=\"label\">\n";
				$html .= "									<td width=\"15%\" align=\"left\" >MEDICAMENTO</td>";
				$html .= "									<td width=\"35%\" align=\"left\" ><input type='text' class='input-text' style=\"width:100%\" name = 'medicamento_pos$j' value=\"".$alterna[$j]['medicamento_pos']."\"></td>" ;
				$html .= "									<td width=\"25%\" align=\"left\" >PRINCIPIO ACTIVO</td>";
				$html .= "									<td width=\"35%\" align=\"left\" ><input type='text' class='input-text' style=\"width:100%\" name = 'principio_activo_pos$j' value=\"".$alterna[$j]['principio_activo']."\" ></td>" ;
				$html .= "								</tr>\n";				
        $html .= "								<tr class=\"label\">\n";
				$html .= "									<td >PRESENTACION</td>";
				$html .= "									<td colspan=\"3\">\n";
        $html .= "									  <input type='text' class='input-text' style=\"width:100%\" name = 'presentacion_pos$j' value=\"".$alterna[$j]['presentacion']."\">\n";
        $html .= "                  </td>\n";	
				$html .= "								</tr>\n";				
        $html .= "								<tr class=\"label\">\n";        
        $html .= "									<td >FRECUENCIA</td>";
				$html .= "									<td >\n";
        $html .= "									  <input type='text' class='input-text' style=\"width:100%\" name = 'frecuencia_pos$j' value=\"".$alterna[$j]['frecuencia']."\">\n";
        $html .= "                  </td>\n";        
        $html .= "									<td >CANTIDAD</td>";
				$html .= "									<td >\n";
        $html .= "									  <input type='text' class='input-text' style=\"width:100%\" name = 'cantidad_pos_medica$j' value=\"".$alterna[$j]['cantidad']."\">\n";
        $html .= "                  </td>\n";
				$html .= "								</tr>\n";
        $html .= "								<tr class=\"label\">\n";
				$html .= "									<td >DOSIS /DIA</td>\n";
				$html .= "									<td ><input type='text' class='input-text' style=\"width:100%\" name = 'dosis_dia_pos$j' value=\"".$alterna[$j]['dosis_dia_pos']."\"></td>" ;
				$html .= "									<td >TIEMPO DE TRATAMIENTO</td>";
				$html .= "									<td ><input type='text' class='input-text' style=\"width:100%\" name = 'duracion_tratamiento_pos$j' value=\"".$alterna[$j]['duracion_pos']."\"  ></td>" ;
				$html .= "								</tr>\n";
        $html .= "								<tr class=\"label\">\n";
				$html .= "									<td colspan=\"4\">\n";
        $html .= "                    RESPUESTA CLINICA CON EL MEDICAMENTO POS\n";
        $html .= "                  </td>\n";
        $html .= "								</tr>\n";
        $html .= "								<tr class=\"label\">\n";
				$html .= "									<td colspan=\"4\" align='center'>\n";
				$html .= "										<textarea style = \"width:100%\" class='textarea' name = 'otras$j' rows = '2'>".$alterna[$j]['otras']."</textarea>\n";
				$html .= "									</td>\n" ;
				$html .= "								</tr>\n";
 				$html .= "								<tr class=\"label\">\n";			
				$html .= "									<td colspan=\"4\">\n";
				$html .= "									  REACCIONES ADVERSAS O INTOLERANCIA A LOS MEDICAMENTOS POS\n";
        $html .= "                  </td>\n";
        $html .= "								</tr>\n";
 				$html .= "								<tr class=\"label\">\n";
				$html .= "									<td align='center' colspan=\"4\">\n";
				$html .= "										<textarea style = \"width:100%\" class='textarea' name = 'reaccion_secundaria$j' rows ='2'>".$alterna[$j]['reaccion_secundaria']."</textarea>\n";
				$html .= "									</td>\n";
				$html .= "								</tr>\n";
				$html .= "							</table>\n";
				$html .= "						</td>\n";
				$html .= "					</tr>\n";
			}	
      
			
			$html .= "					<tr class=\"modulo_table_list_title\">\n";
			$html .= "						<td colspan=\"5\">INDICACION TERAPEUTICA</td>";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td colspan=\"5\" align=\"left\">\n";
			$html .= "							<table width=\"100%\">\n";
			$html .= "								<tr class=\"label\">\n";
			$html .= "									<td align='center' colspan=\"4\">\n";
			$html .= "										<textarea style = \"width:100%\" class='textarea' name = 'indicacion_terapeutica' rows ='2'>".$datos['indicacion_terapeutica']."</textarea>\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			$html .= "							</table>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
				
				
			$html .= "					<tr class=\"modulo_table_list_title\">\n";
      $html .= "						<td colspan=\"5\">CRITERIOS DE JUSTIFICACION</td>";
      $html .= "					</tr>\n";
      $html .= "					<tr >";
      $html .= "						<td colspan=\"5\" align=\"center\">";
      $html .= "							<table class=\"label\" align=\"center\" border=\"0\"  width=\"100%\">";
      $html .= "								<tr>\n";
      $html .= "									<td>JUSTIFICACION DE PARA EL USO DEL MEDICAMENTO NO POS</td>";
      $html .= "								</tr>\n";
      $html .= "								<tr>\n";
      $html .= "									<td>\n";
			$html .= "										<textarea style = \"width:100%\" class='textarea' name = 'justificacion_solicitud' rows = '2'>".$datos['justificacion']."</textarea>\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			
			
			if ($datos['tipo_solicitud'] != '1')
			{
				$checked1 .="						<input type=\"radio\" name=\"tipo_solicitud\" value=\"1\">";
			}
			else
			{
				$checked1 .="						<input type=\"radio\" checked name=\"tipo_solicitud\" value=\"1\">";
			}
			
			if ($datos['tipo_solicitud'] != '2' )
			{
				$checked2 .="						<input type=\"radio\" name=\"tipo_solicitud\" value=\"2\">";
			}
			else
			{
				$checked2 .="						<input type=\"radio\" checked name=\"tipo_solicitud\" value=\"2\">";
			}
			
			if ($datos['tipo_solicitud'] != '3' )
			{
				$checked3 .="						<input type=\"radio\" name=\"tipo_solicitud\" value=\"3\">";
			}
			else
			{
				$checked3 .="						<input type=\"radio\" checked name=\"tipo_solicitud\" value=\"3\">";
			}
			$html .= "							<tr>\n";
			$html .= "									<td>TIPO SOLICITUD &nbsp&nbsp&nbsp&nbsp&nbsp";
			$html .= "									PRIMERA VEZ".$checked1."";
			$html .= "									RENOVACION".$checked2."";
			$html .= "									FALLO DE TUTELA".$checked3."";
			$html .= "									</td>";
			$html .= "							</tr>\n";
			
			$html .= "							</table>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
      
      $html .= "					<tr class=\"modulo_table_list_title\">";
      $html .= "						<td colspan=\"5\" >MEDICAMENTO POS QUE SUSTITUYE O REEMPLAZA AL MEDICAMENTO NO POS SOLICITADO <font style=\"font-size: 9px;\">(DEBE SER DEL MISMO GRUPO TERAPEUTICO)</font></td>\n";
      $html .= "					</tr>\n";
      $html .= "					<tr>\n";
      $html .= "						<td colspan=\"5\" align=\"left\">\n";
      $html .= "							<table width=\"100%\">\n";
      $html .= "								<tr class=\"label\">\n";
      $html .= "									<td width=\"15%\" align=\"left\" >MEDICAMENTO</td>";
      $html .= "									<td width=\"35%\" align=\"left\" >\n";
      $html .= "                    <input type='hidden' name ='sustituto_id' value=\"".$sustituto['sustituto_id']."\">\n";
      $html .= "                    <input type='text' class='input-text' style=\"width:100%\" name = 'medicamentoS' value=\"".$sustituto['medicamento']."\">\n";
      $html .= "                  </td>\n" ;
      $html .= "									<td width=\"25%\" align=\"left\" >PRINCIPIO ACTIVO</td>";
      $html .= "									<td width=\"35%\" align=\"left\" >\n";
      $html .= "                    <input type='text' class='input-text' style=\"width:100%\" name = 'principio_activoS' value=\"".$sustituto['principio_activo']."\" >\n";
      $html .= "                  </td>\n" ;
      $html .= "								</tr>\n";				
      $html .= "								<tr class=\"label\">\n";
      $html .= "									<td >PRESENTACION</td>";
      $html .= "									<td colspan=\"3\">\n";
      $html .= "									  <input type='text' class='input-text' style=\"width:100%\" name = 'presentacionS' value=\"".$sustituto['presentacion']."\">\n";
      $html .= "                  </td>\n";	
      $html .= "								</tr>\n";				
      $html .= "								<tr class=\"label\">\n";        
      $html .= "									<td >FRECUENCIA</td>";
      $html .= "									<td >\n";
      $html .= "									  <input type='text' class='input-text' style=\"width:100%\" name = 'frecuenciaS' value=\"".$sustituto['frecuencia']."\">\n";
      $html .= "                  </td>\n";        
      $html .= "									<td >DOSIS /DIA</td>\n";
      $html .= "									<td ><input type='text' class='input-text' style=\"width:100%\" name = 'dosis_diaS' value=\"".$sustituto['dosis']."\"></td>" ;
      $html .= "								</tr>\n";
      $html .= "								<tr class=\"label\">\n";
      $html .= "									<td >TIEMPO DE TRATAMIENTO</td>";
      $html .= "									<td colspan=\"3\"><input type='text' class='input-text' style=\"width:100%\" name = 'duracion_tratamientoS' value=\"".$sustituto['tiempo_tratamiento']."\"  ></td>" ;
      $html .= "								</tr>\n";
      $html .= "							</table>\n";
      $html .= "						</td>\n";
      $html .= "					</tr>\n";
			$html .= "				</table><br>";
			$html .= "				<table align=\"center\" border=\"0\"  width=\"80%\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\"><input class=\"input-submit\" name= 'guardar_justificacion' type=\"submit\" value=\"Guardar Justificación\"></td>";
			$html .= "						<td align=\"left\">\n";
			$html .= "							<input class=\"input-submit\" name= 'cancelar' type=\"button\" value=\"Cerrar\" onclick=\"window.opener.Continuar();window.close()\">\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 2;\n";
			$html .= "	function IniciarB()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'FacturasB';\n";
			$html .= "		titulo = 'titulob';\n";
			$html .= "		ele = xGetElementById('ContenidoB');\n";
			$html .= "	  xResizeTo(ele,550,360);\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,550, 'auto');\n";
			$html .= "	  xMoveTo(ele, 10, xScrollTop()+10);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,530, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrarb');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele, 530, 0);\n";
			$html .= "	}\n";
			$html .= "	function IniciarM()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'MedicamentosB';\n";
			$html .= "		titulo = 'tituloM';\n";
			$html .= "		ele = xGetElementById('ContenidoM');\n";
			$html .= "	  xResizeTo(ele,600,360);\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,600, 'auto');\n";
			$html .= "	  xMoveTo(ele, 10, xScrollTop()+10);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,580, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrarM');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele, 580, 0);\n";
			$html .= "	}\n";
			$html .= "	function OcultarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";
			$html .= "	function myOnDragStart(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	  window.status = '';\n";
			$html .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "	  else xZIndex(ele, hiZ++);\n";
			$html .= "	  ele.myTotalMX = 0;\n";
			$html .= "	  ele.myTotalMY = 0;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag(ele, mdx, mdy)\n";
			$html .= "	{\n";
			$html .= "	  if (ele.id == titulo) {\n";
			$html .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "	  }\n";
			$html .= "	  else {\n";
			$html .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "	  }  \n";
			$html .= "	  ele.myTotalMX += mdx;\n";
			$html .= "	  ele.myTotalMY += mdy;\n";
			$html .= "	}\n";
			$html .= "	function myOnDragEnd(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	}\n";
			$html .= "	var dias = '';\n";
			$html .= "	function EvaluarDatos(objeto)\n";
			$html .= "	{\n";
			$html .= "		dias = objeto.duracion_tratamiento.value;\n";
			$html .= "		jsrsExecute(\"ScriptsRemotos/justificacion.php\",Evaluar,\"ObtenerLongitud\",new Array());\n";
			$html .= "	}\n";
			$html .= "	function Evaluar(longitud)\n";
			$html .= "	{\n";
			$html .= "		if(dias == \"\")\n";
			$html .= "			xGetElementById('error').innerHTML = 'FALTA INGRESAR LOS DIAS DE TRATAMIENTO';\n";
			$html .= "			else if(longitud == \"0\")\n";
			$html .= "				xGetElementById('error').innerHTML = 'FALTA INGRESAR EL DIAGNOSTICO';\n";
			$justi = $this->ObtenerCamposObligatoriosNoPos($datosAdministrativos['empresa_id'],$datosAdministrativos['centro_utilidad']);

      foreach($justi as $key => $dtl)
      {
        $html .= "  			else if(document.formajus.".$key.".value == \"\")\n";
        $html .= "				  xGetElementById('error').innerHTML = '".$dtl['descripcion']."  - ES OBLIGATORIO ';\n";
      }
      $html .= "				else\n";
			$html .= "				{\n";
			if($this->justifica)
				$html .= "					document.formajus.action = \"Justificacion.class.php?metodo=2&codigo=".$this->codigo."&justifica=".$this->justifica."\";";
			else
				$html .= "					document.formajus.action = \"Justificacion.class.php?metodo=1&codigo=".$this->codigo."\";";
			
			$html .= "					document.formajus.submit();";
			$html .= "				}\n";
			$html .= "	}\n";
			
			$html .= "	function Buscar(objeto,buscador)\n";
			$html .= "	{\n";
			$html .= "		datos = new Array();\n";
			$html .= "		datos[0] = objeto.producto\n";
			$html .= "		datos[1] = objeto.principio_activo\n";
			$html .= "		datos[2] = pagina;\n";
			$html .= "		jsrsExecute(\"ScriptsRemotos/justificacion.php\",Mostrar,\"BuscarMedicamentos\",new Array(),true);\n";
			$html .= "	}\n";
			$html .= "	function Mostrar(html)\n";
			$html .= "	{\n";
			$html .= "		xGetElementById('resultado').innerHTML = html;\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<div id='FacturasB' class='d2Container' style=\"display:none\">\n";
			$html .= "	<div id='titulob' class='draggable' style=\"	text-transform: uppercase;text-align:center\">BUSCADOR DE DIAGNOSTICOS</div>\n";
			$html .= "	<div id='cerrarb' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('FacturasB')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='ContenidoB' class='d2Content' style=\"background:#EFEFEF\"><br>\n";
			$html .= "		<form name=\"buscadorfacturas\" action=\"javascript:CrearVariables(document.buscadorfacturas,'1')\" >\n";
			$html .= "			<table width=\"100%\" align=\"center\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"label\">\n";
			$html .= "						<table class=\"modulo_table_list\" width=\"90%\">\n";
			$html .= "							<tr class=\"modulo_table_list_title\">\n";
			$html .= "								<td colspan=\"5\">BUSQUEDA DE DIAGNOSTICOS</td>\n";
			$html .= "							</tr>\n";
			$html .= "							<tr class=\"modulo_table_list_title\">\n";
			$html .= "								<td style=\"text-indent:8pt;text-align:left\">CODIGO</td>\n";
			$html .= "								<td class=\"modulo_list_claro\">\n";
			$html .= "									<input type=\"text\" class=\"input-text\" name=\"codigo\" size=\"10\" maxlength=\"10\" >\n";
			$html .= "								</td>\n";
			$html .= "								<td style=\"text-indent:8pt;text-align:left\">DIAGNOSTICO</td>\n";
			$html .= "								<td class=\"modulo_list_claro\">\n";
			$html .= "									<input type=\"text\" class=\"input-text\" name=\"diagnostico\" size=\"30\">\n";
			$html .= "								</td>\n";
			$html .= "								<td class=\"modulo_list_claro\">\n";
			$html .= "									<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$html .= "								</td>\n";
			$html .= "							</tr>\n";
			$html .= "						</table>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "				<tr>\n";
			$html .= "					<td>\n";
			$html .= "						<div id=\"resultado\">\n";
			$html .= "							<table align=\"center\">\n";
			$html .= "								<tr><td height=\"25\"><a href=\"javascript:OcultarSpan('FacturasB')\" class=\"label_error\">CERRAR</a></td></tr>\n";
			$html .= "							</table>\n";
			$html .= "						</div>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "			</table>\n";
			$html .= "		</form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			
			$html .= "<div id='MedicamentosB' class='d2Container' style=\"display:none\">\n";
			$html .= "	<div id='tituloM' class='draggable' style=\"	text-transform: uppercase;text-align:center\">BUSCADOR DE MEDICAMENTOS</div>\n";
			$html .= "	<div id='cerrarM' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('MedicamentosB')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$html .= "	<div id='ContenidoM' class='d2Content' style=\"background:#EFEFEF\"><br>\n";
			$html .= "	<form name=\"buscador\" action=\"javascript:Buscar(document.buscador,'1')\" method=\"post\">\n";
			$html .= "		<table align=\"center\" border=\"0\" width=\"95%\" class=\"modulo_table_list\">\n";
			$html .= "			<tr class=\"modulo_table_list_title\">\n";
			$html .= "				<td align=\"center\" colspan=\"7\">BUSCAR MEDICAMENTOS </td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr class=\"hc_table_submodulo_list_title\">\n";		
			$html .= "				<td width=\"%\" class=\"normal_10AN\">PRODUCTO:</td>\n";
			$html .= "				<td width=\"%\" align='center'>\n";
			$html .= "					<input type='text' class='input-text' style=\"width:100%\" name = 'producto' value =\"".$producto."\">\n";
			$html .= "				</td>\n";
			$html .= "				<td width=\"%\" class=\"normal_10AN\">PRINCIPIO ACTIVO:</td>";
			$html .= "				<td width=\"%\" align='center' >\n";
			$html .= "					<input type='text' class='input-text' style=\"width:100%\" name = 'principio_activo' value =\"".$principio_activo."\" >\n";
			$html .= "				</td>\n" ;
			$html .= "				<td width=\"%\" align=\"center\">\n";
			$html .= "					<input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"Buscar\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "		</table>\n";
			$html .= "	</form>\n";
			$html .= "	<div name=\"resultado\" id=\"resultado\" style=\"display:none;border:1px solid #AFAFAF;\"></div>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			
			if(sizeof(SessionGetVar("diagnosticos")) > 0)
			{
				$html .= "<script>";
				$html .= "	AgregarDiagnostico('-1',false);";
				$html .= "</script>";
			}
      
      $this->salida = $html;
		}
		/**
		* Funcion donde se obtiene la informacion de la justificacion del medicamento
    *
    * @param String $medicamento Identificador del medicamento
    * @param integer $justificacion Identificador de la justificacion realizada
    *
    * @return mixed
		*/
		function ObtenerJustificacion($medicamento,$justificacion)
		{
			$sql .= "SELECT	justificacion_no_pos_id,"; 	 	
			$sql .= "				ingreso,"; 	 	
			$sql .= "				codigo_producto,"; 	
			$sql .= "				usuario_id_autoriza,"; 	 	
			$sql .= "				duracion,";
			$sql .= "				dosis_dia,"; 	
			$sql .= "				justificacion,"; 		
			$sql .= "				ventajas_medicamento,";
			$sql .= "				ventajas_tratamiento,";
			$sql .= "				precauciones,";
			$sql .= "				controles_evaluacion_efectividad,";
			$sql .= "				tiempo_respuesta_esperado,";
			$sql .= "				riesgo_inminente,";
			$sql .= "				sw_riesgo_inminente,"; 	
			$sql .= "				sw_agotadas_posibilidades_existentes,";
			$sql .= "				sw_comercializacion_pais,";
			$sql .= "				sw_homologo_pos,";
			$sql .= "				descripcion_caso_clinico,";
			$sql .= "				sw_existe_alternativa_pos, ";
			$sql .= "				efecto, ";
			$sql .= "				indicacion_terapeutica, ";
			$sql .= "				tipo_solicitud ";
			$sql .= "FROM		hc_justificaciones_no_pos_hospitalaria_medicamentos  ";	
			$sql .= "WHERE	justificacion_no_pos_id = ".$justificacion." ";
			$sql .= "AND		codigo_producto = '".$medicamento."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}		
    /**
		* Funcion donde se obtiene la informacion del medicamento sustituto
    *
    * @param integer $justificacion Identificador de la justificacion realizada
    *
    * @return mixed
		*/
		function ObtenerMedicamentoSustituto($justificacion)
		{
      $sql  = "SELECT hc_justificacion_no_pos_medicamento_sustituto_id AS sustituto_id,";
      $sql .= "       medicamento,";
      $sql .= "       principio_activo,";
      $sql .= "       presentacion,";
      $sql .= "       frecuencia,";
      $sql .= "       cantidad,";
      $sql .= "       dosis,";
      $sql .= "       tiempo_tratamiento "; 
			$sql .= "FROM		hc_justificaciones_no_pos_medicamento_sustituto  ";	
			$sql .= "WHERE	justificacion_no_pos_id = ".$justificacion." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			if (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerPredetermiado($medicamento)
		{
			$sql .= "SELECT	justificacion,"; 		
			$sql .= "				medicamento,"; 	
			$sql .= "				principio_activo,"; 	
			$sql .= "				presentacion,"; 	
			$sql .= "				frecuencia,"; 	
			$sql .= "				dosis,"; 	
			$sql .= "				cantidad,"; 	
			$sql .= "				tiempo_tratamiento, ";
      $sql .= "				efecto ";
			$sql .= "FROM		hc_justificaciones_no_pos_plantillas  ";	
			$sql .= "WHERE	codigo_medicamento = '".$medicamento."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerAlternativas($justificacion)
		{
			$sql .= "SELECT	justificacion_no_pos_id,"; 	 	
			$sql .= "				alternativa_pos_id,";
			$sql .= "				medicamento_pos,";
			$sql .= "				principio_activo,";
			$sql .= "				dosis_dia_pos,";
			$sql .= "				duracion_pos,";
			$sql .= "				sw_no_mejoria,";
			$sql .= "				sw_reaccion_secundaria,";
			$sql .= "				reaccion_secundaria,";
			$sql .= "				sw_contraindicacion,";
			$sql .= "				contraindicacion,";
			$sql .= "				otras, ";
			$sql .= "				frecuencia, ";
			$sql .= "				cantidad, ";
			$sql .= "				presentacion ";
			$sql .= "FROM 	hc_justificaciones_no_pos_hospitalaria_medicamentos_alternativa ";
			$sql .= "WHERE  justificacion_no_pos_id = ".$justificacion." ";
			
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->MoveNext();
			SessionSetVar("Alternativas",$datos);
			return $datos;
		}
    /**
		*
		*/
    function MedicamentosJustificados($ingreso,$codigo)
		{
      $sql  = "SELECT ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				HA.nombre, ";
			$sql .= "				FM.dosis, ";
			$sql .= "				FM.unidad_dosificacion, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.observacion, ";
			$sql .= "				FM.codigo_producto, ";
			$sql .= "				FM.frecuencia, ";
			$sql .= "				FM.dias_tratamiento, ";
			$sql .= "				FM.via_administracion_id, ";
			$sql .= "				ME.concentracion_forma_farmacologica AS cff, ";
			$sql .= "				IF.descripcion AS forma_farma ";
			$sql .= "FROM 	inv_med_cod_principios_activos AS IA, ";
			$sql .= "				hc_formulacion_medicamentos FM,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_vias_administracion HA, ";
			$sql .= "				inv_med_cod_forma_farmacologica IF ";
			$sql .= "WHERE	ID.codigo_producto = '".$codigo."' ";
			$sql .= "AND  	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND		FM.ingreso = ".$ingreso." ";
			$sql .= "AND		HA.via_administracion_id = FM.via_administracion_id ";
			$sql .= "AND    IF.cod_forma_farmacologica = ME.cod_forma_farmacologica ";
			$sql .= "ORDER BY FM.sw_estado,producto ";
      /*$sql  = "SELECT A.descripcion, 
                      B.cod_principio_activo, 
                      B.concentracion_forma_farmacologica AS cff, 
                      B.cod_forma_farmacologica,
                      C.descripcion AS principio,
                      D.descripcion AS forma_farma
              FROM    inventarios_productos AS A,
                      medicamentos AS B,
                      inv_med_cod_principios_activos AS C,
                      inv_med_cod_forma_farmacologica AS D
              WHERE   A.codigo_producto = '".$codigo."'
              AND    A.codigo_producto = B.codigo_medicamento
              AND    B.cod_principio_activo = C.cod_principio_activo
              AND    B.cod_forma_farmacologica = D.cod_forma_farmacologica;";
			
			*/      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
			$datos = array();
			if (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $datos;
		}
    /********************************************************************************
		*
		*********************************************************************************/
		function Diagnosticos($justificacion)
		{
			$sql  = "SELECT DG.diagnostico_id,";
			$sql .= " 			DG.diagnostico_nombre ";
			$sql .= "FROM 	diagnosticos DG,";
			$sql .= "				hc_justificaciones_no_pos_hospitalaria_medicamentos_diagnostico HJ ";
			$sql .= "WHERE	HJ.justificacion_no_pos_id = ".$justificacion." ";
			$sql .= "AND		HJ.diagnostico_id = DG.diagnostico_id ";
					
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			SessionSetVar("diagnosticos",$datos);
			SessionSetVar("diagnosticosA",$datos);
			return $datos;
		}
		
		/********************************************************************************
		*
		*********************************************************************************/
		function DiagnosticosIngreso($ingreso)
		{
			$sql  = "SELECT 	a.diagnostico_id,";
			$sql .= " 			a.diagnostico_nombre ";
			$sql .= "FROM 		diagnosticos a, ";
			$sql .= "		 	hc_diagnosticos_ingreso b, ";
			$sql .= "		 	hc_evoluciones c ";
			$sql .= "WHERE 		c.ingreso = ".$ingreso." ";
			$sql .= "AND 		c.evolucion_id = b.evolucion_id ";
			$sql .= "AND 		b.tipo_diagnostico_id = a.diagnostico_id ";
			$sql .= "AND        b.sw_principal = '1' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
    /**
    * Metodo donde se registra la evolucion por submodulo, para hacer la impresion del
    * mismo
    *
    * @param string $submod Nombre del submodulo 
    *
    * @return boolean
    */
		function RegistrarSubmoduloAlterno($submod = "PlanTerapeuticoHospitalizacion")
    {
      $DatosVersion=array('version'=>'1','subversion'=>'0');
      $sql  = "DELETE FROM hc_evoluciones_submodulos ";
      $sql .= "WHERE  evolucion_id = ".SessionGetVar("EvolucionHc")." ";
      $sql .= "AND    submodulo = '".$submod."'; ";
      $sql .= "INSERT INTO hc_evoluciones_submodulos";
      $sql .= "    (";
      $sql .= "      ingreso,";
      $sql .= "      evolucion_id,";
      $sql .= "      submodulo,";
      $sql .= "      version,";
      $sql .= "      subversion";
      $sql .= "    )";
      $sql .= "VALUES";
      $sql .= "    (";
      $sql .= "      ".SessionGetVar("IngresoHc").",";
      $sql .= "      ".SessionGetVar("EvolucionHc").",";
      $sql .= "      '".$submod."',";
      $sql .= "      '".$DatosVersion[version]."',";
      $sql .= "      '".$DatosVersion[subversion]."')";

      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      return true;
    }
    /**
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la
    * consulta sql
    *
    * @param string $sql sentencia sql a ejecutar
    * @param boolean $asoc Indica el modo en el cual se hara la ejecucion del query,
    *                      por defecto es false
    * @return object $rst
    */
    function ConexionBaseDatos($sql,$asoc = false)
    {
      GLOBAL $ADODB_FETCH_MODE;
      list($dbconn)=GetDBConn();
      //$dbconn->debug = true;

      if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

      $rst = $dbconn->Execute($sql);

      if($asoc) $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
      
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        $this->mensajeDeError = $dbconn->ErrorMsg()." ".$sql;
        return false;
      }
      return $rst;
    }
        /**
    * Funcion donde se obtienen los campos obligatorios para
    * el diligenciamiento de la justificacion no pos
    *
    * @param string $empresa Identificador de la empresa
    * @param string $centro_utilidad Identificador del centro de utilidad 
    *
    * @return mixed
    */
    function ObtenerCamposObligatoriosNoPos($empresa,$centro_utilidad)
    {
      $sql  = "SELECT CF.nombre,";
      $sql .= "       CF.descripcion ";
      $sql .= "FROM   nopos_campos_obligatorios CO,   ";
      $sql .= "       campos_justificacion_nopos CF ";
      $sql .= "WHERE	CF.campo_id = CO.campo_id ";
      $sql .= "AND    CO.empresa_id = '".$empresa."' ";
      $sql .= "AND		CO.centro_utilidad = '".$centro_utilidad."' ";
      $sql .= "AND		CF.sw_hospitalario = '1' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }
	}

	$VISTA='HTML';
	$_ROOT='../../';
	include $_ROOT.'includes/enviroment.inc.php';
		
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	IncludeFile($fileName);

	$justifica = new Justificacion();
	if($_REQUEST['metodo'] == '1')
		$justifica->EnviarDatos();
	else if($_REQUEST['metodo'] == '2')
		$justifica->ActualizarDatos();
	else
		$justifica->Inicializar();
	echo $justifica->salida; 
?>