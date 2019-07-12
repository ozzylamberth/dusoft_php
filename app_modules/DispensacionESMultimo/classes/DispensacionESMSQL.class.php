<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: 	DispensacionESMSQL.class.php,v 1.24 
	* @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/

	class DispensacionESMSQL extends ConexionBD
	{
	/*
	* Constructor de la clase
	*/
	function DispensacionESMSQL(){}
	
	/**
	* Funcion donde se verifica el permiso del usuario 
	** @return array $datos vector que contiene la informacion de la consulta
	*/
		
		function ObtenerPermisos()
		{
		
		 
		    $sql = " Select  a.empresa_id,
			                 b.razon_social AS razon_social,
							 a.centro_utilidad,
							 c.descripcion AS centro_utilidad_des,
							 a.bodega,
							 e.descripcion as Bodega_des
					from     userpermisos_DispensacionESM a,
					         bodegas e,
							 centros_utilidad c,
							 empresas b
					where    a.empresa_id=e.empresa_id
					and      a.centro_utilidad=e.centro_utilidad
					and      a.bodega=e.bodega
					and      e.empresa_id=c.empresa_id
					and      e.centro_utilidad=c.centro_utilidad
					and      c.empresa_id=b.empresa_id
				    and      a.sw_activo = '1'
				
					and      a.usuario_id = ".UserGetUID()."					";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[3]][$rst->fields[5]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}

			$rst->Close();
			return $datos;
		}
	/*
		* Funcion donde se Consultan los diferentes tipos de identificacion.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ConsultarTipoId()
		{
			$sql  = "SELECT    tipo_id_tercero, descripcion ";
			$sql .= "FROM      tipo_id_terceros ";
			$sql .= "ORDER BY  tipo_id_tercero ";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	/* CONSULTAR  Y BUSCAR FORMULAS */
	
		function Consulta_Formulacion_Activas($filtros,$offset)
		{
	
			$sql = "SELECT  FR.formula_id,
			                FR.formula_papel,    
							to_char(FR.fecha_formula,'yyyy-mm-dd') as fecha_formula,
							FR.sw_estado,
							
						
							FR.tipo_id_paciente,
							FR.paciente_id,
							FR.plan_id,
							PROF.nombre AS profesional_esm,
							TIPOPROF.descripcion as descripcion_profesional_esm,
						    TERC.nombre_tercero AS ESM_atendio,
							
							
							PAC.primer_apellido ||' '||PAC.segundo_apellido AS apellidos,
							PAC.primer_nombre||' '||PAC.segundo_nombre AS nombres
							
							
							
					FROM    esm_formula_externa FR,
					    	esm_profesionales_empresas ESM_PROF,
							profesionales PROF,
							tipos_profesionales TIPOPROF,
							esm_empresas ESM_EMPRESA,
							terceros TERC,
							pacientes  PAC
						
						
						
					WHERE   
			      
					         FR.tipo_id_tercero=ESM_PROF.tipo_id_tercero
					AND		FR.tercero_id=ESM_PROF.tercero_id
					AND     FR.esm_tipo_id_tercero=ESM_PROF.tipo_id_tercero_esm
					AND 	FR.esm_tercero_id=ESM_PROF.tercero_id_esm
					AND     FR.tipo_id_tercero=PROF.tipo_id_tercero
					AND		FR.tercero_id=PROF.tercero_id
					AND     PROF.tipo_profesional =TIPOPROF.tipo_profesional
					AND     ESM_PROF.tipo_id_tercero_esm=ESM_EMPRESA.tipo_id_tercero
					AND     ESM_PROF.tercero_id_esm=ESM_EMPRESA.tercero_id
					AND     ESM_EMPRESA.tipo_id_tercero=TERC.tipo_id_tercero
					AND     ESM_EMPRESA.tercero_id=TERC.tercero_id
					AND     FR.tipo_id_paciente=PAC.tipo_id_paciente
					AND		FR.paciente_id=PAC.paciente_id
					
					
					AND     FR.sw_estado!='2'
                    AND     FR.sw_corte='0'					";
			
			
			if($filtros)
			{
				if($filtros['tipo_id_paciente']!= '-1' && $filtros['tipo_id_paciente']!= '')
				$sql .= "AND   PAC.tipo_id_paciente = '".$filtros['tipo_id_paciente']."' ";
				if($filtros['paciente_id'])
				$sql .= "AND   PAC.paciente_id = '".$filtros['paciente_id']."' ";

				if($filtros['nombres'] || $filtros['apellidos'])
				{
					$util = AutoCarga::factory('ClaseUtil');
					$whr .= "AND      ".$util->FiltrarNombres($filtros['nombres'],$filtros['apellidos'],"PAC");
				}
             }			
			if($filtros['formula_papel'])
				{
					$sql .= "AND   FR.formula_papel ilike  '%".$filtros['formula_papel']."%' ";
				
				}
			 if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (select formula_id from  esm_formula_externa where  sw_estado!='2' and sw_corte='0'	) A",$offset))
			return false;

			$whr .= " ORDER BY FR.fecha_registro DESC,apellidos,nombres ";
			$whr .= " LIMIT 5 OFFSET ".$this->offset." ";

			if(!$rst = $this->ConexionBaseDatos($sql.$whr,null)) return false;
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;      
		
		}		
	/* CONSULTAR QUE TIPO DE FORMULA ES LA FORMULA REAL */
		

		 function Consulta_Formulacion_Real_I($formula)
		{
		
		
			$sql = "SELECT  FR.formula_id,    
							FR.empresa_id,
					        EMP.razon_social,
							FR.formula_papel,
							to_char(FR.fecha_formula,'dd-mm-yyyy') as fecha_formula,
							FR.hora_formula,
							FR.tipo_formula,
							FR.tipo_evento_id,
							FR.tipo_fuerza_id,
							FR.tipo_id_tercero,
							FR.tercero_id,
							FR.tipo_id_paciente,
							FR.paciente_id,
							FR.plan_id,
							FR.rango,
							FR.tipo_afiliado_id,
							FR.semanas_cotizadas,
							FR.esm_tipo_id_tercero,
							FR.esm_tercero_id,
							FR.usuario_id,
							to_char(FR.fecha_registro,'dd-mm-yyyy') as fecha_registro,
							TIPOF.descripcion_tipo_formula,
							TIPOV.descripcion_tipo_evento,
							PROF.nombre AS profesional_esm,
							TIPOPROF.descripcion as descripcion_profesional_esm,
						    TERC.nombre_tercero AS ESM_atendio,
							PAC.primer_nombre || ' ' ||  PAC.segundo_nombre|| ' ' || PAC.primer_apellido|| ' ' ||PAC.segundo_apellido AS nombre_paciente,
							PAC.sexo_id,
							edad(PAC.fecha_nacimiento) as edad,
							PLAN.plan_descripcion,
							FR.usuario_id,
							SYS.nombre,
							SYS.descripcion,
							TIPOF.sw_ambulatoria
							
							
							
					FROM    esm_formula_externa FR,
					        empresas EMP,
							esm_tipos_formulas TIPOF,
							esm_tipos_eventos  TIPOV,
							esm_profesionales_empresas ESM_PROF,
							profesionales PROF,
							tipos_profesionales TIPOPROF,
							esm_empresas ESM_EMPRESA,
							terceros TERC,
							pacientes  PAC,
							planes_rangos PLANR,
							planes PLAN,
							system_usuarios SYS
			
						
					WHERE   FR.formula_id = '".$formula."'
					AND     FR.empresa_id=EMP.empresa_id
					AND     FR.tipo_formula=TIPOF.tipo_formula_id
			        AND     FR.tipo_evento_id=TIPOV.tipo_evento_id
					AND     FR.tipo_id_tercero=ESM_PROF.tipo_id_tercero
					AND		FR.tercero_id=ESM_PROF.tercero_id
					AND     FR.esm_tipo_id_tercero=ESM_PROF.tipo_id_tercero_esm
					AND 	FR.esm_tercero_id=ESM_PROF.tercero_id_esm
					AND     FR.tipo_id_tercero=PROF.tipo_id_tercero
					AND		FR.tercero_id=PROF.tercero_id
					AND     PROF.tipo_profesional =TIPOPROF.tipo_profesional
					AND     ESM_PROF.tipo_id_tercero_esm=ESM_EMPRESA.tipo_id_tercero
					AND     ESM_PROF.tercero_id_esm=ESM_EMPRESA.tercero_id
					AND     ESM_EMPRESA.tipo_id_tercero=TERC.tipo_id_tercero
					AND     ESM_EMPRESA.tercero_id=TERC.tercero_id
					AND     FR.tipo_id_paciente=PAC.tipo_id_paciente
					AND		FR.paciente_id=PAC.paciente_id
					AND     FR.plan_id=PLANR.plan_id
					AND		FR.rango=PLANR.rango
					AND		FR.tipo_afiliado_id=PLANR.tipo_afiliado_id
					AND     PLANR.plan_id=PLAN.plan_id
					AND     FR.usuario_id=SYS.usuario_id ";
						
						
			
			
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		
		
		}	
		
		/*   INFORMACION ADICIONAL DE LA EMS PARA LAS FORMULAS DE TRANSCRI*/
	
		 function Consulta_Formulacion_Real_A($formula)
		{
			
			$sql = "SELECT  	FR.esm_tipo_id_tercero,
								FR.esm_tercero_id,
								FR.esm_autoriza_tipo_id_tercero,
								FR.esm_autoriza_tercero_id,
								PROF.nombre AS profesional_esm,
								TIPOPROF.descripcion as descripcion_profesional_esm,
								 TERC.nombre_tercero AS ESM_atendio,
								 FR.costo_formula
								
					FROM        esm_formula_externa FR,
								esm_profesionales_empresas ESM_PROF,
								profesionales PROF,
								esm_empresas ESM_EMPRESA,
								terceros TERC,
								tipos_profesionales TIPOPROF
						
						WHERE   FR.formula_id = '".$formula."'
						AND      	FR.esm_autoriza_tipo_id_tercero=ESM_PROF.tipo_id_tercero
						AND		    FR.esm_autoriza_tercero_id=ESM_PROF.tercero_id
						AND         FR.esm_tipo_id_tercero=ESM_PROF.tipo_id_tercero_esm
						AND 	    FR.esm_tercero_id=ESM_PROF.tercero_id_esm
						AND         ESM_PROF.tipo_id_tercero=PROF.tipo_id_tercero
						AND         ESM_PROF.tercero_id=PROF.tercero_id
						AND			ESM_PROF.tipo_id_tercero_esm=ESM_EMPRESA.tipo_id_tercero
						AND         ESM_PROF.tercero_id_esm=ESM_EMPRESA.tercero_id
						AND        ESM_EMPRESA.tipo_id_tercero=TERC.tipo_id_tercero
						AND        ESM_EMPRESA.tercero_id=TERC.tercero_id
						AND        PROF.tipo_profesional =TIPOPROF.tipo_profesional";
						
					
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		
		
		}
		 function Consulta_Formulacion_Real_AE($formula)
		{
			
			$sql = "
					SELECT  	FR.ips_tipo_id_tercero,
								FR.ips_tercero_id,
								FR.ips_profesional_tipo_id_tercero,
								FR.ips_profesional_tercero_id,
								TERC.nombre_tercero AS IPS_ATENDIDO,
								PROF.nombre AS profesional_ips,
								TIPOPROF.descripcion as descripcion_profesional_ips,
							   MP.municipio || ' ' || TD.departamento || ' ' || TP.pais AS ubicacion
								
					FROM        esm_formula_externa FR,
					            esm_ips_profesionales  IPS_P,
								esm_ips_terceros  TERCIP,
								terceros TERC,
								profesionales PROF,
								tipos_profesionales TIPOPROF,
							    tipo_mpios MP,
								tipo_dptos TD,
								tipo_pais TP
							
			
						WHERE   FR.formula_id = '".$formula."'
						and         FR.ips_tipo_id_tercero=IPS_P.tipo_id_tercero_ips
						and         FR.ips_tercero_id=IPS_P.tercero_id_ips
						and         FR.ips_profesional_tipo_id_tercero=IPS_P.tipo_id_tercero
						and         FR.ips_profesional_tercero_id=IPS_P.tercero_id
						and         IPS_P.tipo_id_tercero_ips=TERCIP.tipo_id_tercero
						and         IPS_P.tercero_id_ips=TERCIP.tercero_id
						and         TERCIP.tipo_id_tercero=TERC.tipo_id_tercero
						and         TERCIP.tercero_id=TERC.tercero_id
						AND         IPS_P.tipo_id_tercero=PROF.tipo_id_tercero
						AND         IPS_P.tercero_id=PROF.tercero_id
						and          PROF.tipo_profesional =TIPOPROF.tipo_profesional
						AND          TERC.tipo_pais_id=MP.tipo_pais_id
						AND     TERC.tipo_dpto_id=MP.tipo_dpto_id
						AND     TERC.tipo_mpio_id=MP.tipo_mpio_id
						AND     MP.tipo_pais_id=TD.tipo_pais_id
						AND     MP.tipo_dpto_id=TD.tipo_dpto_id
						AND     TD.tipo_pais_id=TP.tipo_pais_id 	 ; ";
						
		
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		
		
		}
		/* FUERZA DEL PACIENTE */
	function ObtenerFuezaPaciente($datos)
    {
	 
      $sql  = "	SELECT  PA.tipo_fuerza_id,
						PA.tipo_id_paciente,
						PA.paciente_id,
						FUE.descripcion
				FROM    esm_pacientes_fuerzas PA,
						esm_tipos_fuerzas FUE
				   			
 				WHERE    PA.tipo_fuerza_id=FUE.tipo_fuerza_id
				AND      FUE.sw_activo='1'
				AND      PA.tipo_id_paciente = '".$datos['tipo_id_paciente']."'
				AND      PA.paciente_id= '".$datos['paciente_id']."' ";


      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
	/* TIPO DE VINCULACION Y TIPO DE PLAN */
		Function Dato_Adionales_afiliacion($datos)
		{
			
			
			$sql = "	SELECT  EPS.eps_tipo_afiliado_id,
						        AFI.descripcion_eps_tipo_afiliado as vinculacion,
								TIPOP.descripcion AS tipo_plan
						FROM 	eps_afiliados EPS,
						        eps_tipos_afiliados AFI,
								planes_rangos  PLAR,
								planes PLA,
								tipos_planes TIPOP
						WHERE 	EPS.afiliado_id =  '".$datos['paciente_id']."' 
						AND     EPS.afiliado_tipo_id='".$datos['tipo_id_paciente']."'
						AND     EPS.plan_atencion= '".$datos['plan_id']."' 
						AND     EPS.eps_tipo_afiliado_id=AFI.eps_tipo_afiliado_id
						AND     EPS.plan_atencion=PLAR.plan_id
						AND     EPS.tipo_afiliado_atencion=PLAR.tipo_afiliado_id
						AND     EPS.rango_afiliado_atencion=PLAR.rango
						AND     PLAR.plan_id=PLA.plan_id
						AND     PLA.sw_tipo_plan=TIPOP.sw_tipo_plan ";
		
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

		      $datos = array();
		      while(!$rst->EOF)
		      {
		        $datos = $rst->GetRowAssoc($ToUpper = false);
		        $rst->MoveNext();
		      }
		      $rst->Close();
		      return $datos;
		
		}
			/*  ESM DEL PACIENTE */
	Function Consultar_ESM_P($datos)
	{
	
		$sql = " SELECT  	ESM.tipo_id_tercero,
							ESM.tercero_id,
							TER.nombre_tercero
					FROM    esm_pacientes ESM,
							esm_empresas ESME,
							terceros TER
					WHERE   ESM.tipo_id_paciente ='".$datos['tipo_id_paciente']."'
					AND     ESM.paciente_id ='".$datos['paciente_id']."' 
					AND     ESM.tipo_id_tercero=ESME.tipo_id_tercero
					AND     ESM.tercero_id=ESME.tercero_id
					AND     ESME.tipo_id_tercero=TER.tipo_id_tercero
					AND     ESME.tercero_id=TER.tercero_id ";
					if(!$rst = $this->ConexionBaseDatos($sql)) return false; 

					$datos = array();
					while(!$rst->EOF)
					{
					$datos = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
	
	
	}
		/*  DIAGNOSTICOS REALES */
	
	
	/*  consultar DIAGNOSTICOS */
	function Diagnostico_Real($tmp_id)
		{
		
		    $sql ="SELECT  DXT.diagnostico_id,
						   DX.diagnostico_nombre
					FROM   esm_formula_externa_diagnosticos DXT,
						   diagnosticos DX
					WHERE DXT.diagnostico_id=DX.diagnostico_id 
					and   DXT.formula_id='".$tmp_id."' ";
				


				if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					$datos = array();
					while (!$rst->EOF)
					{
					$datos []= $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
		}
			/* MEDICAMENYTOS FORMULADOS REALES */
	
	
	    function Medicamentos_Formulados_R($tmp_id)
		{
			
		
		    $sql = "SELECT  tmp.fe_medicamento_id,
							tmp.codigo_producto,
							tmp.cantidad,
							tmp.observacion,
							tmp.dosis,
							tmp.unidad_dosificacion,
							tmp.tiempo_tratamiento,
							tmp.unidad_tiempo_tratamiento,
							tmp.periodicidad_entrega,
							tmp.unidad_periodicidad_entrega,
							tmp.via_administracion_id,
						    fc_descripcion_producto_alterno(tmp.codigo_producto) as descripcion_prod,
							A.descripcion as producto,
							b.concentracion_forma_farmacologica,
							b.unidad_medida_medicamento_id,
							b.factor_conversion,
							b.factor_equivalente_mg,
							d.descripcion as forma,
							c.descripcion as principio_activo,
							d.cod_forma_farmacologica,
							tmp.sw_marcado
					 FROM   esm_formula_externa_medicamentos tmp,
							inventarios_productos A,
							medicamentos as b,
							inv_med_cod_principios_activos as c,
							inv_med_cod_forma_farmacologica as d
					 WHERE    tmp.formula_id='".$tmp_id."'
					 AND    tmp.codigo_producto= A.codigo_producto
					 AND 	 A.codigo_producto = b.codigo_medicamento 
					 AND   	 b.cod_principio_activo = c.cod_principio_activo
					 AND   	 b.cod_forma_farmacologica = d.cod_forma_farmacologica 		
			 ;  ";
		    	
				
		
				
				
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/*   PENDIENTES POR DISPENSAR */
		
		
		function Medicamentos_Formulados_P($tmp_id)
		{
		
		    $sql = "SELECT  tmp.codigo_medicamento,
							tmp.cantidad,
							fc_descripcion_producto_alterno(tmp.codigo_medicamento) as descripcion_prod
							
					 FROM   esm_pendientes_por_dispensar tmp
							
					 WHERE  tmp.formula_id='".$tmp_id."'
					
			 ;  ";
		    	
				
		
				
				
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/* CONSULTAR OPCION DE LA POSOLOGIA */
	
	 function Consulta_opc_Medicamentos_PosologiaR($formula_id)
     {
        $sql = " SELECT opcion
         		FROM    esm_formula_externa_posologia
				WHERE   fe_medicamento_id = '".$formula_id."'  ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
				
       
	}
	
	/* CONSULTAR POSOLOGIA DEL MEDICAMENTO  REAL */

     function Consulta_Solicitud_Medicamentos_Posologia($opcion, $formulacion_id)
     {
        
          $sql == '';
          if ($opcion == 1)
          {
          	$sql= "select periocidad_id, tiempo from esm_formula_externa_posologia where fe_medicamento_id = ".$formulacion_id." ";
          }
		  
		  
		  
          if ($opcion == 2)
          {
               $sql= "select a.duracion_id, b.descripcion from esm_formula_externa_posologia as a , hc_horario as b where a.fe_medicamento_id = ".$formulacion_id."  and a.duracion_id = b.duracion_id";
          
		 
		  }
          if ($opcion == 3)
          {
	          $sql= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from esm_formula_externa_posologia  where fe_medicamento_id = ".$formulacion_id." ";
          
	  
		  }
          if ($opcion == 4)
          {
     	     $sql= "select hora_especifica from esm_formula_externa_posologia where fe_medicamento_id = ".$formulacion_id."  ";
          }
            if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
	}
	
	/* CONSULTAR NOMBRE D ELA VIA DE ADMINISTRACION */

	Function Consultar_Via_Admin($via)
	{
			
		
			$sql = " 	SELECT nombre
						FROM   hc_vias_administracion
						WHERE  via_administracion_id = '".$via."'
			 ;  ";
		    	
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
	
	}
		
		
		
	/*
		* Funcion donde se Consultan la formulacion del paciente.
   		* @param array $datos vector que contiene la informacion de la consulta
    */
		function ObtenerFormulasMedicas($filtros,$offset,$plan_atencion)
		{
		
			$sql = " SELECT  DISTINCT HF.tipo_id_paciente,
							HF.paciente_id,
							TO_CHAR(HF.fecha_registro,'YYYY-MM-DD') AS fecha_registro,
							TO_CHAR(HF.fecha_finalizacion,'YYYY-MM-DD') AS fecha_finalizacion, 
							TO_CHAR(HF.fecha_formulacion,'YYYY-MM-DD') AS fecha_formulacion,
							PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos,
							PA.primer_nombre||' '||PA.segundo_nombre AS nombres,
							SU.nombre,
							HF.evolucion_id,
							BL.tipo_bloqueo_id,
							BL.descripcion AS bloqueo,
							PLA.plan_descripcion
							
              FROM    hc_formulacion_antecedentes HF,
                      pacientes PA LEFT JOIN eps_afiliados EPS ON (EPS.afiliado_tipo_id=PA.tipo_id_paciente AND EPS.afiliado_id=PA.paciente_id),
                      system_usuarios SU,
					  inv_tipos_bloqueos BL,
					  planes_rangos PR,
					  planes    PLA
              WHERE   HF.sw_formulado='1'
              AND     HF.fecha_finalizacion >= '".$filtros['fecha']."'
              AND     HF.sw_mostrar='1'
              AND     HF.tipo_id_paciente = PA.tipo_id_paciente 
              AND     HF.paciente_id = PA.paciente_id 
			  AND     PA.tipo_bloqueo_id=BL.tipo_bloqueo_id
			  AND     BL.estado='1'
              AND     SU.usuario_id = HF.medico_id
			  AND     EPS.plan_atencion=PR.plan_id
			  AND     EPS.tipo_afiliado_atencion=PR.tipo_afiliado_id
			  AND     EPS.rango_afiliado_atencion=PR.rango
			  AND     PR.plan_id=PLA.plan_id
            		  ";
			if($filtros)
			{
				if($filtros['tipo_id_paciente']!= '-1' && $filtros['tipo_id_paciente']!= '')
				$sql .= "AND   HF.tipo_id_paciente = '".$filtros['tipo_id_paciente']."' ";
				if($filtros['paciente_id'])
				$sql .= "AND   HF.paciente_id = '".$filtros['paciente_id']."' ";

				if($filtros['nombres'] || $filtros['apellidos'])
				{
					$util = AutoCarga::factory('ClaseUtil');
					$whr .= "AND      ".$util->FiltrarNombres($filtros['nombres'],$filtros['apellidos'],"PA");
				}
             }			
			if($filtros['plan']!='' && $filtros['plan']!='-1')
				{
					$sql .= "AND   EPS.plan_atencion = '".$filtros['plan']."' ";
				
				}
			
			if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",$offset))
			return false;

			$whr .= "ORDER BY apellidos,nombres ";
			$whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";

			if(!$rst = $this->ConexionBaseDatos($sql.$whr,null)) return false;
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;      
		}
	/*
		* Funcion donde se Consultan los datos de la formulacion que se le realizo al paciente.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
    function Hc_Formulacion_Antecedentes($fecha_actual,$filtros)
		{
			

			$sql= " SELECT  a.*,
                     d.maxi
                     
                FROM (
                      SELECT w.*,
                            p.primer_apellido,
                            p.segundo_apellido,
                            p.primer_nombre,
                            p.segundo_nombre,
                            b.descripcion as nombre_medicamento, 
                            b.contenido_unidad_venta, 
                            unida.descripcion as unidad,
                            s.descripcion as molecula,
                            clas.descripcion as laboratorio
                             
                         FROM ( SELECT Distinct h.codigo_medicamento,
                                                h.tipo_id_paciente,
                                                h.paciente_id,
                                                TO_CHAR(h.fecha_registro, 'DD-MM-YYYY') AS fecha_registro,
                                                TO_CHAR(h.fecha_finalizacion, 'DD-MM-YYYY') AS fecha_finalizacion,
                                                h.dosis,
                                                h.unidad_dosificacion,
                                                h.frecuencia,
                                                h.tiempo_total, 
                                                h.perioricidad_entrega,
                                                h.descripcion,
                                                h.cantidad,
                                                h.cantidad_entrega,
                                                h.evolucion_id,
                                                TO_CHAR(h.fecha_formulacion, 'DD-MM-YYYY') AS fecha_formulacion,
                                                h.tiempo_perioricidad_entrega,
                                                h.unidad_perioricidad_entrega
                                  FROM    hc_formulacion_antecedentes as h 
                                  WHERE   h.tipo_id_paciente = '".$filtros['tipo_id_paciente']."'
                                  AND     h.paciente_id = '".$filtros['paciente_id']."'
                                  AND     h.evolucion_id = '".$filtros['evolucion_id']."'
                                  AND     h.sw_formulado='1'
                                  AND     h.fecha_finalizacion >= '".$fecha_actual."'
                                  AND     h.sw_mostrar='1' AND h.cantidad>0
                                  AND h.evolucion_id=".$filtros['evolucion_id']."  
                                  
                                ) as w LEFT JOIN medicamentos med ON (w.codigo_medicamento=med.codigo_medicamento), pacientes p,
                                                                inventarios_productos b,
                                                                unidades unida,
                                                                inv_subclases_inventarios s,
                                                                inv_clases_inventarios clas                                                               
                              WHERE w.tipo_id_paciente=p.tipo_id_paciente
                              AND w.paciente_id=p.paciente_id
                              AND b.unidad_id=unida.unidad_id 
                              AND w.codigo_medicamento = b.codigo_producto
                              AND     b.clase_id = s.clase_id
                              AND     b.subclase_id = s.subclase_id
                              AND     b.grupo_id=s.grupo_id
                              AND     s.clase_id=clas.clase_id
                              AND     s.grupo_id=clas.grupo_id
                           )as a
                      LEFT JOIN 
                          ( 
                              SELECT  MAX(med.hc_formuladesp_medicamentos_id) AS maxi,
                              med.codigo_medicamento
							FROM    hc_formulacion_despachos_medicamentos as  med
							WHERE   med.tipo_id_paciente= '".$filtros['tipo_id_paciente']."'
							AND     med.paciente_id = '".$filtros['paciente_id']."' 
							AND    med.evolucion_id=".$filtros['evolucion_id']." 
							GROUP BY med.codigo_medicamento
                      ) d 
                      ON (  d.codigo_medicamento=a.codigo_medicamento)";

					  if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					  $datos = array();
					  while (!$rst->EOF)
					  {
						$datos[] = $rst->GetRowAssoc($ToUpper = false);
						$rst->MoveNext();
					  }
					  $rst->Close();
					  return $datos;         
		}
	/*
		* Funcion donde se ingresa inicialmente los datos de los medicamentos a despachar e una tabla temporal
		* @return boolean.
	*/
            
		function Medicamento_Farmacia_tmp($tipo_id_paciente,$paciente_id,$cantidad_entrega,$codigo_medicamento_forumulado,$dosis,$fecha_finalizacion,$fecha_formulacion,$fecha_proxima_entrega,$evolucion_id,$tiempo_perioricidad,$unidad_perioricidad)
		{
		
          $this->ConexionTransaccion();
		
			if($fecha_proxima_entrega=="")
			{
				$cade=$codigo_medicamento_forumulado." ".$paciente_id;
				$fecha_proxima_entrega=$fecha_formulacion;
			}
			else 
					
					$cade=$codigo_medicamento_forumulado." ".$paciente_id;
					$sql  = "INSERT INTO medicamento_farmacia_tmp( ";
					$sql .= "       medicafarma_id,
									tipo_id_paciente,
									paciente_id,
									evolucion_id,
									cantidad_entrega,
									tiempo_perioricidad_entrega,
									unidad_perioricidad_entrega,
									codigo_medicamento_formulado,
									dosis,
									fecha_finalizacion,
									fecha_formulacion,
									fecha_proxima_entrega
						)VALUES( 
                        '".$cade."', 
                        '".$tipo_id_paciente."', 
                        '".$paciente_id."' ,
                        ".$evolucion_id.",
                        ".$cantidad_entrega.", 
                        ".$tiempo_perioricidad.", 
                        '".$unidad_perioricidad."' ,
                        '".$codigo_medicamento_forumulado."', 
                        ".$dosis.", 
                        '".$fecha_finalizacion."', 
                        '".$fecha_formulacion."',
                        '".$fecha_proxima_entrega."'
                        ";
                $sql .= "       ); ";

					if(!$rst = $this->ConexionTransaccion($sql))
					{
					return false;
					}
					$this->Commit();
					return true;
		}
	/*
		* Funcion donde 	se Eliminan los datos de la tabla temporal.
		* @return array $datos vector que contiene la informacion de la consulta.
	  */
		function EliminarDatosFormula($tipo_id_paciente,$paciente_id,$codigo_medicamento_forumulado,$evolucion_id)
		{
			$sql = " DELETE FROM  medicamento_farmacia_tmp 
				   WHERE   tipo_id_paciente ='".$tipo_id_paciente."'
				   AND     paciente_id='".$paciente_id."'
				   AND    codigo_medicamento_forumulado = '".$codigo_medicamento_forumulado."' 
           and    evolucion_id=".$evolucion_id." ";
				   
				   
				   if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					$datos = array();
					while (!$rst->EOF)
					{
					$datos[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;       
		}
	 /*
		* Funcion donde se Consultan la informacion d e la tabla temporal.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
      
		function  ConsultarInformacion($tipo_id_paciente,$paciente_id,$evolucion_id)
		{
      
			$sql = " SELECT * 
					FROM      medicamento_farmacia_tmp
					WHERE     tipo_id_paciente='".$tipo_id_paciente."'
					AND       paciente_id='".$paciente_id."' 
					AND       	evolucion_id=".$evolucion_id." ";
                  
				if(!$rst = $this->ConexionBaseDatos($sql))	return false;
				$datos = array();
				while (!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;  

		}	
	/*
		* Funcion donde se Consultan los antecendetes de posologia del paciente.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/				
      
		function ConsultarantAntecedentes_posologia($tipo_id_paciente,$paciente_id,$codigo_medicamento,$evolucion_id)
		{

		
        $sql = " SELECT tipo_id_paciente,
                        paciente_id,
                        codigo_medicamento,
                        cantidad_veces

                FROM  hc_formulacion_antecedentes_posologia
                where tipo_id_paciente='".$tipo_id_paciente."'
                 AND    paciente_id='".$paciente_id."'
                 AND    codigo_medicamento='".$codigo_medicamento."'
                 AND    evolucion_id=".$evolucion_id." ; ";
      
               
                if(!$rst = $this->ConexionBaseDatos($sql))	return false;
                $datos = array();
                while (!$rst->EOF)
                {
                $datos[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
                }
                $rst->Close();
                return $datos;         
		}
	/*
		* Funcion donde se los medicamentos que se le han formulado al paciente .
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
      
		function ConsultarInformacionEntregaMedicamentos($tipo_id_paciente,$paciente_id)
		{
       

         $sql = "   SELECT    m.*,
								i.descripcion,
								i.cantidad,
								i.sw_generico,
								s.descripcion as molecula,
								c.descripcion as laboratorio,
								i.contenido_unidad_venta,
								s.molecula_id,
								u.abreviatura
						 
                           
                 FROM      medicamento_farmacia_tmp m,
                           inventarios_productos i,
                           inv_subclases_inventarios s,
                           unidades u,
                           inv_clases_inventarios c
                                       
                  WHERE   m.codigo_medicamento_formulado=i.codigo_producto
                  and     i.grupo_id=s.grupo_id
                  and     i.subclase_id=s.subclase_id
                  and     i.clase_id=s.clase_id
                  and     i.unidad_id=u.unidad_id
                  and     s.grupo_id=c.grupo_id
                  and     s.clase_id=c.clase_id
                  and     m.tipo_id_paciente='".$tipo_id_paciente."'
                  AND     m.paciente_id='".$paciente_id."' ";
                  
  
                    if(!$rst = $this->ConexionBaseDatos($sql))	return false;
                $datos = array();
                while (!$rst->EOF)
                {
                $datos[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
                }
                $rst->Close();
                return $datos;  
       }
  /*
		* Funcion donde se Consultan los productos que se pueden despachar de acuerdo al medicamento formulado
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ConsultarInforma($empresa,$centro,$bodega,$molecula)
		{
    
         $sql=" SELECT 			INVP.codigo_producto,
          						INVP.descripcion as nombre_medicamento,
          						INVP.contenido_unidad_venta,
          						INVP.sw_generico,
          						clas.descripcion as laboratorio,
          						INV.costo,
          						u.descripcion,
          						TO_CHAR(LF.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento,
          						LF.lote,
          						LF.existencia_actual As cantidad,
          						M.cod_concentracion

            FROM 				inventarios_productos INVP,
								inv_clases_inventarios clas,
								inv_subclases_inventarios s,
								inventarios INV,
								existencias_bodegas EB,
								unidades u,
								existencias_bodegas_lote_fv LF,
								medicamentos M
				WHERE 	INVP.subclase_id = '".$molecula."'
				AND   INVP.clase_id = s.clase_id
				AND   M.codigo_medicamento = INV.codigo_producto
				AND   INVP.subclase_id = s.subclase_id
				AND   INVP.grupo_id=s.grupo_id
				AND   s.clase_id=clas.clase_id
				AND   s.grupo_id=clas.grupo_id
				AND   INV.codigo_producto=INVP.codigo_producto
				AND   EB.codigo_producto=INV.codigo_producto
				AND   EB.empresa_id=INV.empresa_id
				AND   INVP.unidad_id=u.unidad_id
				AND   EB.empresa_id = LF.empresa_id
				AND   EB.centro_utilidad = LF.centro_utilidad
				AND   EB.bodega = LF.bodega
				AND   LF.empresa_id='".$empresa."'
				AND   LF.centro_utilidad='".$centro."'
				AND   LF.bodega='".$bodega."'
				AND   EB.codigo_producto=LF.codigo_producto
				AND   LF.existencia_actual > 0
				ORDER BY LF.fecha_vencimiento ";     
					
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }      
	 /*
		* Funcion donde se Consultan el factor de conversion de acuerdo al medicamento.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
    
	    function  ConsultarFactorConversion($medicamento)
		{
         
            $sql = "  SELECT  HF.codigo_producto,
                              HF.unidad_id,
                              HF.unidad_dosificacion,
                              HF.factor_conversion
                        FROM  hc_formulacion_factor_conversion HF,
                              unidades UN
                        WHERE HF.codigo_producto='".$medicamento."'
                        AND   HF.unidad_id = UN.unidad_id;";
    
                if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    /*
		* Funcion donde se Consultan la informacion temporal .
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
      
      function ConsultarTmp_Formulacion($tipo_id_paciente,$paciente_id)
      {
           
			$sql = "   SELECT  hc_formudespachos_id,
                          tipo_id_paciente,
                          paciente_id,
                          codigo_medicamento,
                          cantidad_entrega,
                          unidad_entrega,
                          codigo_medicamento_despachado,
                          persona_reclama,
                          persona_reclama_tipo_id,
                          persona_reclama_id,
                          observacion,
                          total_costo
                  FROM    hc_formulacion_despachos_medicamentos_tmp
                  WHERE    tipo_id_paciente= '".$tipo_id_paciente."'
                  AND paciente_id = '".$paciente_id."' ";
                
				if(!$rst = $this->ConexionBaseDatos($sql))	return false;
                $datos = array();
                while (!$rst->EOF)
                {
                $datos[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
                }
                $rst->Close();
                return $datos;
      
		}
	 /*
		* Funcion donde se elimina toda la informacion de las tablas temporales
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		Function EliminarTemporalTodo($tipo_id_paciente,$paciente_id)
		{
			$sql  = " DELETE FROM      medicamento_farmacia_tmp
                                      WHERE  tipo_id_paciente ='".$tipo_id_paciente."'
                                      AND     paciente_id='".$paciente_id."'; 

				      DELETE  FROM   hc_formulacion_despachos_medicamentos_tmp 
                                     WHERE   tipo_id_paciente ='".$tipo_id_paciente."'
                                     AND     paciente_id='".$paciente_id."' ;";
		
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	 /*
		* Funcion donde se Consultan los datos del paciente.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
    
		Function DatosPaciente($tipo_id_paciente,$paciente_id)
		{
       
		  $sql = " SELECT 	paciente_id,
							tipo_id_paciente,
							primer_apellido,
							segundo_apellido,
							primer_nombre,
							segundo_nombre,
							sexo_id,
							residencia_direccion, 
							residencia_telefono,
							to_char(fecha_nacimiento,'dd-mm-yyyy') as fecha_nacimiento,
							edad(fecha_nacimiento) as edad,
							primer_apellido ||' '||segundo_apellido AS apellidos,
							primer_nombre||' '||segundo_nombre AS nombres
				 FROM     pacientes
				 WHERE   paciente_id = '".$paciente_id."' AND tipo_id_paciente = '".$tipo_id_paciente."';
				 ";
		if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while(!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
		}
	 /*
		* Funcion donde se Consultan el ultimo registro que se ingreso a la tabla  hc_formulacion_despachos_medicamentos
		* @return array $datos vector que contiene la informacion de la consulta.
	*/

       function ConsultarUltimoResg($tipo_id_paciente,$paciente_id,$codigo_medicamento,$evolucion)
       {
	     
			$sql = "SELECT (COALESCE(MAX(hc_formuladesp_medicamentos_id),0)) AS maxi  FROM hc_formulacion_despachos_medicamentos
              where     tipo_id_paciente= '".$tipo_id_paciente."'
               AND      paciente_id = '".$paciente_id."'
               AND     codigo_medicamento='".$codigo_medicamento."' 
			   AND      evolucion_id='".$evolucion."' 
			  AND        sw_estado='0' ";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
        }
		 /*
		* Funcion donde se Consultan el ultimo registro que se ingreso a la tabla  hc_formulacion_despachos_medicamentos pero que no se le despacho
		* @return array $datos vector que contiene la informacion de la consulta.
	*/

       function ConsultarUltimoResgNoDespachado($tipo_id_paciente,$paciente_id,$codigo_medicamento,$evolucion)
       {
	     
			$sql = "SELECT (COALESCE(MAX(hc_formuladesp_medicamentos_id),0)) AS maxi  FROM hc_formulacion_despachos_medicamentos
              where     tipo_id_paciente= '".$tipo_id_paciente."'
               AND      paciente_id = '".$paciente_id."'
               AND     codigo_medicamento='".$codigo_medicamento."' 
			   AND      evolucion_id='".$evolucion."' 
			   AND      sw_estado='1'	";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
        }
		
	 /*
		* Funcion donde se Consultan  el detalle de los medicamentos despachados
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
    
        function Consultarhc_formuladesp_medicamentos_id($maxi,$tipo_id_paciente,$paciente_id,$codigo_medicamento,$evolucion)
        {
		  
          $sql="SELECT  d.*,
                        s.nombre,
                         p.descripcion as nombre_producto,
						 e.razon_social,
						 fc_descripcion_producto(d.codigo_medicamento_despachado) as producto
                        
               FROM     hc_formulacion_despachos_medicamentos d,
                        system_usuarios  s,
                        inventarios_productos p,
						empresas e
               
                        
               WHERE    d.usuario_id=s.usuario_id
               AND      d.codigo_medicamento_despachado=p.codigo_producto
			   AND      d.empresa_id=e.empresa_id
               
               AND      hc_formuladesp_medicamentos_id=".$maxi."
               AND      tipo_id_paciente= '".$tipo_id_paciente."'
               AND      paciente_id = '".$paciente_id."'
               AND     codigo_medicamento='".$codigo_medicamento."'
			   AND      evolucion_id=".$evolucion." ; ";
       
        if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    
        }
		
		
			
	 /*
		* Funcion donde se Consultan  el detalle de los medicamentos despachados
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
    
        function Consultarhc_formuladesp_medicamentos_idNR($maxi,$tipo_id_paciente,$paciente_id,$codigo_medicamento,$evolucion)
        {
		
          $sql="SELECT  d.*,
                        s.nombre,
                         p.descripcion as nombre_producto,
						 e.razon_social
                        
               FROM     hc_formulacion_despachos_medicamentos d,
                        system_usuarios  s,
                        inventarios_productos p,
						empresas e
               
                        
               WHERE    d.usuario_id=s.usuario_id
               AND      d.codigo_medicamento=p.codigo_producto
			   AND      d.empresa_id=e.empresa_id
               
               AND      hc_formuladesp_medicamentos_id=".$maxi."
               AND      tipo_id_paciente= '".$tipo_id_paciente."'
               AND      paciente_id = '".$paciente_id."'
               AND     codigo_medicamento='".$codigo_medicamento."'
			   AND      evolucion_id=".$evolucion." ; ";
       
        if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    
        }
     /*
		* Funcion donde se Consultan  las existencias por lote y fecha de vencimiento del medicamento a despachar
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		
		function ConsultarExistenciasfv($empresa,$centra,$medicamentodespcho,$bodega,$fechavenci,$lote)
		{
	
		  $sql = "   	SELECT  existencia_actual
						FROM    existencias_bodegas_lote_fv
						WHERE   empresa_id = '".$empresa."' AND 
								centro_utilidad = '".$centra."'
						AND     codigo_producto = '".$medicamentodespcho."'
						AND     bodega = '".$bodega."'
						AND     fecha_vencimiento = '".$fechavenci."'
						AND     lote = '".$lote."'  ";

					if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while(!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
	 
	 	 }
	 /*
		* Funcion donde se actualizan   las existencias por lote y fecha de vencimiento del medicamento a despachar
		* @return array $datos vector que contiene la informacion de la consulta.
	*/	 
		function UpdateExistenciasfv($empresa,$centra,$medicamentodespcho,$bodega,$fechavenci,$lote,$existencia_actual)
		{
			
			$sql = "   	 update existencias_bodegas_lote_fv
		                 set  existencia_actual=".$existencia_actual."
						WHERE   empresa_id = '".$empresa."' AND 
								centro_utilidad = '".$centra."'
						AND     codigo_producto = '".$medicamentodespcho."'
						AND     bodega = '".$bodega."'
						AND     fecha_vencimiento = '".$fechavenci."'
						AND     lote = '".$lote."'  ";
					
					if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while(!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
	 
	 	 }
      /*
		* Funcion donde se actualizan   las existencias  del medicamento a despachar
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
       function UpdateExistencias_Bodegas($empresa,$centra,$medicamentodespcho,$bodega,$existencia_actual)
	   {
		
		  $sql = "   	 update existencias_bodegas
		                 set  existencia=".$existencia_actual."
						WHERE   empresa_id = '".$empresa."' AND 
								centro_utilidad = '".$centra."'
						AND     codigo_producto = '".$medicamentodespcho."'
						AND     bodega = '".$bodega."'
					";
					
					if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while(!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
	 
	 	 }
      /*
		* Funcion donde las existencia total de las bodegas  de la farmacia del medicamento despachado
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function consultarInformacionBodega($empresa,$centro,$medicamento,$bod)
		{
     
       $sql= "SELECT existencia
              FROM   existencias_bodegas
              WHERE  empresa_id = '".$empresa."' 
              AND    centro_utilidad = '".$centro."'
              AND    codigo_producto = '".$medicamento."'
              AND    bodega = '".$bod."' ";
          	if(!$rst = $this->ConexionBaseDatos($sql))
          				return false;
          				$datos = array();
          				while(!$rst->EOF)
          				{
          				$datos[] = $rst->GetRowAssoc($ToUpper);
          				$rst->MoveNext();
          				}
          				$rst->Close();
          				return $datos;
	      
      }
   	   /*
		* Funcion donde se consulta los medicamentos que tienen la misma molecula  del medicamento formulado
		* @return array $datos vector que contiene la informacion de la consulta.
		*/
      function ConsultarIgualMolecula($medicamento)
      {
      
			 $sql=" SELECT cod_principio_activo
              FROM   medicamentos
              WHERE  codigo_medicamento = '".$medicamento."' ";
                 if(!$rst = $this->ConexionBaseDatos($sql))
          				return false;
          				$datos = array();
          				while(!$rst->EOF)
          				{
          				$datos[] = $rst->GetRowAssoc($ToUpper);
          				$rst->MoveNext();
          				}
          				$rst->Close();
          				return $datos;
      }
	   /*
		* Funcion donde se ingresan los medicamentos pendientes por entregar al paciente
		* @return boolean
		*/
		function Pendientes_X_DispensacionESM($empresa,$centro,$bodega,$pacienteid,$tipopaciente,$medicamento,$cantidad,$unidad,$evolucion,$medicamento_desp,$cantidad_des)
		{
			
			 $this->ConexionTransaccion();
		
			 $variable= " ,medicamento_despachado,cantidad_despachada ";
			 
			$sql  = " INSERT INTO pendiente_x_DispensacionESM(";
			$sql .= "            pendiente_DispensacionESM_id, ";
			$sql .= "			 empresa_id, ";
			$sql .= "			 centro_utilidad, ";
			$sql .= "			 bodega, ";
			$sql .= "			 paciente_id, ";
			$sql .= "            tipo_id_paciente, ";
			$sql .= "			 codigo_medicamento, ";
			$sql .= "            evolucion_id,";
			$sql .= "			 cantidad, ";
			$sql .= "			 usuario_id,";
			$sql .= "            fecha_registro, ";
			$sql .= "            unidad ";
		    if(!empty($medicamento_desp))
			{
				$sql .= $variable;
			
			}
			$sql .= "            )VALUES(";
			$sql .= "			 nextval('pendiente_x_DispensacionESM_pendiente_DispensacionESM_id_seq'), ";
			$sql .= "			 '".$empresa."', ";
			$sql .= "			 '".$centro."', ";
			$sql .= "             '".$bodega."', ";
			$sql .= "             '".$pacienteid."', ";
			$sql .= "             '".$tipopaciente."', ";
			$sql .= "             '".$medicamento."',  ";
			$sql .= "             ".$evolucion.",  ";
			$sql .= "             ".$cantidad.",  ";
			$sql .= "               ".UserGetUID().", ";
			$sql .= "               now(), ";
			$sql .= "             '".$unidad."' " ;
			if(!empty($medicamento_desp))
			{
				$sql .=" ,'".$medicamento_desp."', " ;
				$sql .="   ".$cantidad_des."  ";
			
			}
			$sql .= "             ) ; ";
			
		    if(!$rst = $this->ConexionTransaccion($sql))
            return false;
         
		   $this->Commit();
		    return true;
			
		}
	 /*
		* Funcion donde se consulta la descripcion de un producto
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ConsultarInformacion_Producto($Codigo)
		{
		   
			$sql = " SELECT b.descripcion,
							b.contenido_unidad_venta,
							u.descripcion as unidad
					FROM 	inventarios_productos as b, unidades as u
					WHERE 	b.unidad_id=u.unidad_id and codigo_producto= '".$Codigo."' ; ";
					
					 if(!$rst = $this->ConexionBaseDatos($sql))
          				return false;
          				$datos = array();
          				while(!$rst->EOF)
          				{
          				$datos[] = $rst->GetRowAssoc($ToUpper);
          				$rst->MoveNext();
          				}
          				$rst->Close();
          				return $datos;
					
		}
		   /*
		* Funcion donde se consulta los medicamentos pendientes por entregar
		* @return array $datos vector que contiene la informacion de la consulta.
		*/
	
		
	/*
		* Funcion donde se actualiza  los campos sw_entregado,usuario_entrega y Fecha_Entrega de  la tabla pendiente_x_DispensacionESM
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
     
		function ActualizarEstadoPendientes($tipo_id_paciente,$paciente_id,$medicamento)
		{
       
			$sql  = "UPDATE   pendiente_x_DispensacionESM ";
			$sql .= " set     sw_entregado='1' ";
			$sql .= " WHERE   tipo_id_paciente='".$tipo_id_paciente. "' ";
			$sql .= " AND     paciente_id='".$paciente_id."'  ";
			$sql .= " AND     codigo_medicamento='".$medicamento."' ;";
			
			$sql .= "UPDATE   pendiente_x_DispensacionESM ";
			$sql .= " set     usuario_entrega=".UserGetUID()." ";
			$sql .= " WHERE   tipo_id_paciente='".$tipo_id_paciente. "' ";
			$sql .= " AND     paciente_id='".$paciente_id."'  ";
			$sql .= " AND     codigo_medicamento='".$medicamento."'; ";
			
			$sql .= "UPDATE   pendiente_x_DispensacionESM ";
			$sql .= " set     Fecha_Entrega=now() ";
			$sql .= " WHERE   tipo_id_paciente='".$tipo_id_paciente. "' ";
			$sql .= " AND     paciente_id='".$paciente_id."'  ";
			$sql .= " AND     codigo_medicamento='".$medicamento."'; ";
			

           	if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
        }
	 /*
		* Funcion donde se consultan los datos de la persona logueada
		* @return array $datos vector que contiene la informacion de la consulta.
	*/	
		function GetNombreUsuarioImprime()
		{
			$sql="  SELECT nombre,descripcion
					FROM system_usuarios
					WHERE usuario_id=".UserGetUID()." ";
					
			if(!$resultado = $this->ConexionBaseDatos($sql))
			return false;

			$datos=Array();
			while(!$resultado->EOF)
			{
			$datos[] = $resultado->GetRowAssoc($ToUpper = false);
			$resultado->MoveNext();
			}

			$resultado->Close();
			return $datos;
		}
   /*
		* Funcion donde se consultan la fecha de entrega si existe
		* @return array $datos vector que contiene la informacion de la consulta.
	*/	
    function ConsultarFechaEntrega($maxi)
    {
   
      $sql = " SELECT  fecha_proxima_entrega
               FROM    hc_formulacion_despachos_medicamentos
               WHERE   hc_formuladesp_medicamentos_id = '".$maxi."' ";
    
              if(!$resultado = $this->ConexionBaseDatos($sql))
              return false;

              $datos=Array();
              while(!$resultado->EOF)
              {
              $datos[] = $resultado->GetRowAssoc($ToUpper = false);
              $resultado->MoveNext();
              }

              $resultado->Close();
              return $datos;
       
    }
/* * Funcion donde se consultas los planes asociados a la Farmacia 
* @return array $datos vector que contiene la informacion de la consulta.
	*/	
	function ConsultaPlanes_Bodega($farmacia)
	{
	     
	       $sql ="   SELECT a.plan_id,
		                    p.plan_descripcion
		             FROM   bodegas_farmacia_asoc_formulas a,
					        planes p
					 WHERE  p.plan_id=a.plan_id
					 and    farmacia_id = '".$farmacia."' 
					 order by  p.plan_descripcion  ";
					if(!$resultado = $this->ConexionBaseDatos($sql))
					return false;

					$datos=Array();
					while(!$resultado->EOF)
					{
					$datos[] = $resultado->GetRowAssoc($ToUpper = false);
					$resultado->MoveNext();
					}

					$resultado->Close();
					return $datos;
   }
   /**/
   Function Consultar_DatosA_Paciente($request)
   {
       
		$sql = "SELECT 	to_char(fecha_nacimiento,'dd-mm-yyyy') as fecha_nacimiento,
						residencia_direccion,
						residencia_telefono,
						sexo_id,
						edad(fecha_nacimiento) as edad
				FROM 	pacientes 
				WHERE 	paciente_id	= '".$request['paciente_id']."' 
				AND     tipo_id_paciente = '".$request['tipo_id_paciente']."' ";
				if(!$resultado = $this->ConexionBaseDatos($sql))
					return false;

					$datos=Array();
					while(!$resultado->EOF)
					{
					$datos[] = $resultado->GetRowAssoc($ToUpper = false);
					$resultado->MoveNext();
					}

					$resultado->Close();
					return $datos;
   
    }
	 /*
		* Funcion donde se ingresar el evento 
		* @return boolean
		*/
		function Registrar_Evento($datos,$request,$informacion)
		{
		
			$this->ConexionTransaccion();
			
			$sql  = " update hc_despacho_medicamentos_eventos ";
            $sql .= " set   sw_estado='0'  ";
			$sql .= " where paciente_id='".$datos['paciente_id']."' ";
			$sql .= " and   tipo_id_paciente='".$datos['tipo_id_paciente']."' ";
			$sql .= " and   evolucion_id =".$datos['evolucion_id']."   ";
			$sql .= " and   sw_estado ='1' ;  ";
			      	
			
			$sql .= " INSERT INTO hc_despacho_medicamentos_eventos(";
			$sql .= "            hc_despacho_evento, ";
			$sql .= "			 paciente_id, ";
			$sql .= "			 tipo_id_paciente, ";
			$sql .= "			 evolucion_id, ";
			$sql .= "			 observacion, ";
			$sql .= "            fecha_evento, ";
			$sql .= "			 Fecha_Registro, ";
			$sql .= "            Usuario_id ";
			$sql .= "            )VALUES(";
			$sql .= "			 nextval('hc_despacho_medicamentos_eventos_hc_despacho_evento_seq'), ";
			$sql .= "			 '".$datos['paciente_id']."', ";
			$sql .= "			 '".$datos['tipo_id_paciente']."', ";
			$sql .= "             ".$datos['evolucion_id'].", ";
			$sql .= "             '".$request['observar']."', ";
			$sql .= "             '".$request['fecha_inicio']."', ";
			$sql .= "             now(),  ";
			$sql .= "               ".UserGetUID()." ";
			$sql .= "          ) RETURNING hc_despacho_evento;   ";
			
			if(!$rst = $this->ConexionTransaccion($sql))
            return false;
            $info=Array();
			while(!$rst->EOF)
			{
				$info = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				}
			$sql =" " ;
		
		 
           foreach ($informacion as $indice => $valor)
		   {
				$sql .= "  insert into hc_despacho_medicamentos_eventos_d(   ";
				$sql .= "            hc_despacho_evento_d, ";
				$sql .= "			 hc_despacho_evento, ";
				$sql .= "			 codigo_medicamento, ";
				$sql .= "			 cantidad ";
				$sql .= "            )VALUES(";
				$sql .= "			 default, ";
				$sql .= "			 ".$info['hc_despacho_evento'].", ";
				$sql .= "			 '".$valor['codigo_medicamento']."', ";
			    $sql .= "             ".$valor['cantidad_acomulada']." ";
				$sql .= "             );  ";
		   	   
		   }

		 	 
			if(!$rst = $this->ConexionTransaccion($sql))
            return false;


		         $this->Commit();
				  return true;
			
		}
	/**/
	Function ConsultarEventoActivo($pacienteid,$tipopaciente,$evolucion)
	{
	
	  $sql =" 	SELECT 	EV.hc_despacho_evento,
						EV.evolucion_id,
						EV.observacion,
						to_char(EV.fecha_evento,'dd-mm-yyyy') as fecha_evento,
						PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos,
						PA.primer_nombre||' '||PA.segundo_nombre AS nombres,
						SU.nombre,
						EV.paciente_id,
						EV.tipo_id_paciente
						
				FROM    hc_despacho_medicamentos_eventos EV,
			            pacientes PA,
						system_usuarios SU
				WHERE   EV.sw_estado= '1'
				AND     SU.usuario_id = EV.usuario_id
				AND     EV.tipo_id_paciente = PA.tipo_id_paciente 
				AND     EV.paciente_id = PA.paciente_id  
				AND     EV.paciente_id='".$pacienteid."' 
				AND     EV.tipo_id_paciente='".$tipopaciente."'
                AND     EV.evolucion_id='".$evolucion."'			";
		
      		if(!$rst = $this->ConexionBaseDatos($sql.$whr,null)) return false;
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;       
		
	}
	/**/
	Function CerrarEventoPaciente($datos)
	{
	
			$this->ConexionTransaccion();
			
			$sql  = " update hc_despacho_medicamentos_eventos ";
            $sql .= " set   sw_estado='0'  ";
			$sql .= " where paciente_id='".$datos['paciente_id']."' ";
			$sql .= " and   tipo_id_paciente='".$datos['tipo_id_paciente']."' ";
			$sql .= " and   evolucion_id =".$datos['evolucion_id']."   ";
			$sql .= " and   sw_estado ='1' ;  ";
		    if(!$rst = $this->ConexionTransaccion($sql))
            return false;

			

		    $this->Commit();
			 return true;
		
	}
	
	Function PacienteNoReclamaActualizar($datos)
	{
	     
			$this->ConexionTransaccion();
			
			$sql  = " update pendiente_x_DispensacionESM ";
            $sql .= " set   sw_entregado='2'  ";
			$sql .= " where paciente_id='".$datos['paciente_id']."' ";
			$sql .= " and   tipo_id_paciente='".$datos['tipo_id_paciente']."' ";
			$sql .= " and   evolucion_id =".$datos['evolucion_id']."   ";
			$sql .= " and   sw_entregado ='0' ;  ";
		    if(!$rst = $this->ConexionTransaccion($sql))
            return false;

			

		    $this->Commit();
			 return true;
		
	}
	  /*
    * Funcion donde se selecciona la informacion de la tabla temporal  hc_formulacion_despachos_medicamentos_tmp
    * @param string $tipo_id_paciente  cadenael tipo de identificacion del paciente
    * @param string $paciente_id  cadena con el numero de identificacion del paciente
    * @return array $datos vector que contiene la informacion de la consulta.
    */ 
		function SelecrHc_formulacion_despachos_medicamentos($tipo_id_paciente,$paciente_id)
		{
		  
            $sql = " SELECT  m.*
                     FROM    medicamento_farmacia_tmp m
                     WHERE   m.tipo_id_paciente='".$tipo_id_paciente."'
                     AND     m.paciente_id='".$paciente_id."'
                    ; " ;
               
                if(!$rst = $this->ConexionBaseDatos($sql))
                            return false;
                            $datos = array();
                            while(!$rst->EOF)
                            {
                            $datos[] = $rst->GetRowAssoc($ToUpper);
                            $rst->MoveNext();
                            }
                            $rst->Close();
                            
                           
                            return $datos;
                            
		}
      /*
   * Funcion donde se inserta la informacion completa del despacho al paciente 
* @param string $tipo_id_paciente  cadenael tipo de identificacion del paciente
   * @param string $paciente_id  cadena con el numero de identificacion del paciente
    * @param string $empresa cadena con el tipo id de la empresa
      * @return boolean
    */ 
		function Insertarhc_formulacion_despachos_medicamentos($tipo_id_paciente,$paciente_id,$evolucion,$codigo_medicamento_formulado,$fecha_Entrega,$fecha_proxima,$empre,$observar,$cantidad_entrega)                   
		{
			
				$this->ConexionTransaccion();
		
				$sql = "INSERT INTO   	hc_formulacion_despachos_medicamentos
						(
										hc_formuladesp_medicamentos_id,
										tipo_id_paciente,
										paciente_id, 
										evolucion_id,
										codigo_medicamento, 
										fecha_entrega,
										fecha_proxima_entrega,
										empresa_id, 
										fecha_registro, 
										usuario_id, 
										observacion,
										cantidad_pendiente,
										sw_estado
						)VALUES 
						(				DEFAULT,
										'".$tipo_id_paciente."', 
										'".$paciente_id."',
										".$evolucion.",
										'".$codigo_medicamento_formulado."', 
										'".$fecha_Entrega."',
										'".$fecha_proxima."', 
										'".$empre."', 
										NOW(), 
										".UserGetUID().", 
										'".$observar."', 
										".$cantidad_entrega.",
										1
									
										); " ;
										
						if(!$rst = $this->ConexionTransaccion($sql))
						return false;

						$this->Commit();
						return true;
		}
   
      /*
		* Funcion donde se elimina todos los registros de las tablas temporales
		* @param string $tipo_id_paciente  cadenael tipo de identificacion del paciente
		* @param string $paciente_id  cadena con el numero de identificacion del paciente
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		           
		function EliminarTodoTemporal($tipo_id_paciente,$paciente_id)
		{
   
			$sql = "                 DELETE FROM medicamento_farmacia_tmp
                                      WHERE  tipo_id_paciente ='".$tipo_id_paciente."'
                                      AND     paciente_id='".$paciente_id."'; ";
                                     
                                      if(!$rst = $this->ConexionBaseDatos($sql))
                                      return false;
                                      $datos = array();
                                      while(!$rst->EOF)
                                      {
                                      $datos[] = $rst->GetRowAssoc($ToUpper);
                                      $rst->MoveNext();
                                      }
                                      $rst->Close();
                                      return $datos;
                                             
                                        
		}
   /**/
		function selccionarMax_noEntregado($evolucion)
		{
		
			$sql = "SELECT (COALESCE(MAX(hc_formuladesp_medicamentos_id),0)) AS hc_formuladesp_medicamentos_id FROM  hc_formulacion_despachos_medicamentos where evolucion_id='".$evolucion."' and sw_estado='1' ";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		
		
		}
	/* **** DISPENSACION EMS */
 
		function Consultar_Medicamentos_Detalle($Formulario,$formula_id)
		{
    
	
	  
        if($Formulario['codigo_barras']!="")
        {
        $filtro = " and A.codigo_barras = '".$Formulario['codigo_barras']."' ";
        }
			
		if($Formulario['descripcion']!="" || $Formulario['codigo_barras']!="")
        {
		    $sql = "SELECT  tmp.fe_medicamento_id,
							tmp.codigo_producto,
							tmp.cantidad,
							tmp.dosis,
							tmp.unidad_dosificacion,
							tmp.tiempo_tratamiento,
							tmp.unidad_tiempo_tratamiento,
							tmp.periodicidad_entrega,
							tmp.unidad_periodicidad_entrega,
							tmp.via_administracion_id,
						    fc_descripcion_producto_alterno(tmp.codigo_producto) as descripcion_prod,
							POS.*,
							EXT.*,
							MED.cod_principio_activo
							
					 FROM   esm_formula_externa_medicamentos tmp,
							inventarios_productos A,
							esm_formula_externa_posologia POS,
							esm_formula_externa EXT,
							medicamentos MED
							
							
					 WHERE    tmp.formula_id='".$formula_id."'
					 AND    tmp.codigo_producto= A.codigo_producto
					 AND    tmp.fe_medicamento_id=POS.fe_medicamento_id
					 AND    tmp.formula_id=EXT.formula_id
					 AND    A.codigo_producto=MED.codigo_medicamento
					 AND   	 A.descripcion ILIKE '%".$Formulario['descripcion']."%' 
					AND  	tmp.sw_marcado='0'	
					  ".$filtro;
			
		    	
		}	
  
    		if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }

                $resultado->Close();

                return $cuentas;   
    } 
 /* EXISTENCIAS POR PRODUCTO*/

	function Consultar_ExistenciasBodegas($principio_activo,$Formulario,$farmacia,$centrou,$bodega,$producto)
		{
   
        if($Formulario['lote']!="")
        {
        $filtro = " and fv.lote = '".$Formulario['lote']."' ";
        }
		
	 /* if($Formulario['descripcion']!="")
        {
        $filtro = " and invp.descripcion ilike '%".$Formulario['descripcion']."%' ";
        }
*/
		
    		$sql = "SELECT fc_descripcion_producto_alterno(invp.codigo_producto) as producto,
               fv.*
                FROM 
                existencias_bodegas_lote_fv AS fv,
                existencias_bodegas	ext,
				inventarios inv,
				inventarios_productos invp LEFT JOIN  medicamentos med ON(invp.codigo_producto=med.codigo_medicamento)
				 ";
								
    		$sql .= " where   fv.empresa_id=ext.empresa_id
			and               fv.centro_utilidad=ext.centro_utilidad
            and               fv.bodega=ext.bodega
			and               fv.codigo_producto=ext.codigo_producto
			and               ext.empresa_id=inv.empresa_id
			and               ext.codigo_producto=inv.codigo_producto
			and               inv.codigo_producto=invp.codigo_producto 
			 ";
		    if($principio_activo!="")
			{
				$sql .= "      and  med.cod_principio_activo = '".$principio_activo."'  ";
				
			}
			if($principio_activo=="")
			{
				$sql .= " and ext.codigo_producto='".$producto."'";
			
			
			}
    		
    		$sql .= "      and  ext.empresa_id = '".$Formulario['empresa_id']."' ";
    		$sql .= "      and  ext.centro_utilidad = '".$Formulario['centro_utilidad']."' ";
    		$sql .= "      and  ext.bodega = '".trim($Formulario['bodega'])."' and existencia_actual >0 ";
    		$sql .= "     ".$filtro;
			$sql .= " ORDER BY fv.fecha_vencimiento ASC,invp.codigo_producto ";
    	
  
    		if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    } 

	/* CONSULTAR  TMP */
	function Cantidad_ProductoTemporal($doc_tmp_id,$principio_activo)
    {
	
	
	
	  $sql = "  SELECT COALESCE(sum(total.cantidad_despachada),0) as total 
	            from   esm_dispensacion_medicamentos_tmp as total,
				       inventarios_productos invp
				where  total.codigo_producto=invp.codigo_producto
				and    total.formula_id = ".$doc_tmp_id."
				and    invp.subclase_id ilike '%".$principio_activo."%'  ";
					   
	
      
			if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }

                $resultado->Close();

                return $cuentas;   
    }
	
	/* GUARDAR TMP */
	function GuardarTemporal($formula_id,$codigo_producto, $cantidad, $fecha_venc,$lotec,$empresa,$bodega,$formulado)
     {
	 
       $this->ConexionTransaccion();
		
				$sql = "INSERT INTO   	esm_dispensacion_medicamentos_tmp
						(
										esm_dispen_tmp_id,
										formula_id,
										empresa_id, 
										centro_utilidad,
										bodega, 
										codigo_producto,
										cantidad_despachada,
										fecha_vencimiento, 
										lote,
										codigo_formulado
						)VALUES 
						(				DEFAULT,
										".$formula_id.", 
										'".$empresa['empresa_id']."',
										'".$empresa['centro_utilidad']."',
										'".$bodega."', 
										'".$codigo_producto."',
										".$cantidad.", 
										'".$fecha_venc."', 
										'".$lotec."',
										'".$formulado."'
						); " ;
										
									
						if(!$rst = $this->ConexionTransaccion($sql))
						return false;

						$this->Commit();
						return true;
	   
	   
	   
	   
     }
	 function Buscar_ProductoLote($doc_tmp_id,$codigo_producto,$lote)
    {
    

	   $sql  = "SELECT * ";
      $sql .= "FROM   esm_dispensacion_medicamentos_tmp ";
      $sql .= "WHERE  formula_id = ".$doc_tmp_id." ";
      $sql .= "and    codigo_producto = '".$codigo_producto."' ";
      $sql .= "and    lote = '".$lote."' ";
      
	  
	
	  
	  
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return $this->frmError['MensajeError'];

      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }

      $rst->Close();

      return $datos;  
    }
  /* consultar productos temporales */
  

	 function Buscar_producto_tmp_c($formula_id)
    {
	
      $sql  = " SELECT  esm_dispen_tmp_id,
				        formula_id,
						empresa_id,
						centro_utilidad,
						bodega,
						codigo_producto,
						cantidad_despachada,
						fecha_vencimiento,
						lote,
						fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod
				FROM    esm_dispensacion_medicamentos_tmp
				WHERE   formula_id = '".$formula_id."' ";
     	if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    }
  /* ELIMINAR PRODUCTOS */
        function EliminarDatosTMP_DISPENSACION($formula_id,$codigo_producto,$seria_id)
		{
		
		 $this->ConexionTransaccion();
			$sql = " DELETE FROM  esm_dispensacion_medicamentos_tmp 
				   WHERE   esm_dispen_tmp_id ='".$seria_id."'
				   AND     formula_id='".$formula_id."'
				   AND     codigo_producto = '".$codigo_producto."' ";
        
				   
			
				  if(!$rst = $this->ConexionTransaccion($sql))
						return false;
						$this->Commit();
						return true;   
		}
		
    /* CONSULTAR EXISTENCIAS EN OTRAS BODEGAS */
	 function Buscar_producto_EN_OTRA_FRM($empresa_id,$centro_utilidad,$bodega,$principio_activo)
    {
	
      $sql  = " SELECT   EX.bodega,
						 EX.existencia,
						 BOD.descripcion as bodega_des
				FROM     existencias_bodegas EX,
							bodegas BOD,
							inventarios inv,
				inventarios_productos invp
				WHERE    		  EX.empresa_id=inv.empresa_id
				and               EX.codigo_producto=inv.codigo_producto
				and               inv.codigo_producto=invp.codigo_producto 
				and               EX.empresa_id = '".$empresa_id."' 
				AND      EX.centro_utilidad = '".$centro_utilidad."' 
			    and  invp.subclase_id ilike '%".$principio_activo."%' 
				AND     EX.bodega!=  '".trim($bodega)."' 
				AND    EX.existencia > '0'
				AND    EX.empresa_id=BOD.empresa_id
				AND     EX.centro_utilidad=BOD.centro_utilidad
				AND     EX.bodega=BOD.bodega  and  BOD.sw_bodega_satelite = '0' ";

				
				
				
			
     	if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    }
	
	/*  CONSULTAR   POR AGRUPACION */
	
 function Buscar_producto_tmp_conc($formula_id)
    {
	
      $sql  = " SELECT  esm_dispen_tmp_id,
				        formula_id,
						empresa_id,
						centro_utilidad,
						bodega,
						codigo_producto,
						cantidad_despachada,
						to_char(fecha_vencimiento,'dd-mm-yyyy')as fecha_vencimiento,
						lote,
						fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod
				FROM    esm_dispensacion_medicamentos_tmp
				WHERE   formula_id = '".$formula_id."' ";
     	if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    }
	/* consultar INFORMACION PENDIENTES */
	
	
		function ConsultarInformacionPediente_ESM($formula_id)
		{
		
			$sql = " SELECT p.cantidad  as cantidad_acomulada,
							p.codigo_medicamento,
							fc_descripcion_producto_alterno(p.codigo_medicamento) as descripcion_prod
									
							
					FROM    esm_pendientes_por_dispensar as p
							
						
                    WHERE   formula_id = '".$formula_id."' 
					AND     sw_estado = '0'	
					
					
                   ";
			
				 if(!$rst = $this->ConexionBaseDatos($sql))
          				return false;
          				$datos = array();
          				while(!$rst->EOF)
          				{
          				$datos[] = $rst->GetRowAssoc($ToUpper);
          				$rst->MoveNext();
          				}
          				$rst->Close();
          				return $datos;
					
		}
	/* MEDICAMENTOS DISPENSADOS  TOTAL */
	
	
	
		function Medicamentos_Dispensados_Esm($formula_id)
		{
		
			$fecha_hoy=date('Y-m-d');
		
		
			$sql = " select codigo_producto,
					  SUM(numero_unidades) as total,
					  fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod
					  from
						  (
						  select
						  dd.codigo_producto,
						  SUM(dd.cantidad) as numero_unidades
						  FROM
							  esm_formulacion_despachos_medicamentos as dc,
							  esm_formula_externa_medicamentos esm,
							  bodegas_documentos as d,
							  bodegas_documentos_d AS dd
						  WHERE
									 dc.bodegas_doc_id = d.bodegas_doc_id
						  and        dc.numeracion = d.numeracion
						  and        dc.formula_id=esm.formula_id
						  and        dc.formula_id = ".$formula_id."
						
						  and        d.bodegas_doc_id = dd.bodegas_doc_id
						  and        esm.fecha_entrega='".$fecha_hoy."'
						  and        d.numeracion = dd.numeracion
						  group by(dd.codigo_producto)
						  ) as A
							group by (codigo_producto)
                   ";
			
				 if(!$rst = $this->ConexionBaseDatos($sql))
          				return false;
          				$datos = array();
          				while(!$rst->EOF)
          				{
          				$datos[] = $rst->GetRowAssoc($ToUpper);
          				$rst->MoveNext();
          				}
          				$rst->Close();
          				return $datos;
					
		}
		
			function Medicamentos_Dispensados_Esm_x_lote($formula_id)
		{
		
			$fecha_hoy=date('Y-m-d');
		
		
			$sql = " 	  select
						  dd.codigo_producto,
						  dd.cantidad as numero_unidades,
						  dd.fecha_vencimiento ,
						  dd.lote,
						  fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
						  d.usuario_id,
						  sys.nombre,
						  sys.descripcion
						  FROM
							  esm_formulacion_despachos_medicamentos as dc,
							
							  bodegas_documentos as d,
							  bodegas_documentos_d AS dd,
							  system_usuarios  sys
						  WHERE
									 dc.bodegas_doc_id = d.bodegas_doc_id
						  and        dc.numeracion = d.numeracion
						  
						 
						  and        dc.formula_id = ".$formula_id."
						  
						  and        d.bodegas_doc_id = dd.bodegas_doc_id
						  and        to_char(d.fecha_registro,'YYYY-mm-dd')='".$fecha_hoy."'
						  and        d.numeracion = dd.numeracion
						  and       d.usuario_id=sys.usuario_id
						
						
						
						
                   ";
			
				 if(!$rst = $this->ConexionBaseDatos($sql))
          				return false;
          				$datos = array();
          				while(!$rst->EOF)
          				{
          				$datos[] = $rst->GetRowAssoc($ToUpper);
          				$rst->MoveNext();
          				}
          				$rst->Close();
          				return $datos;
					
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	/*  TOTAL MEDICAMENTOS PENDIENTES POR FORMULA */
	
function Medicamentos_Pendientes_Esm($formula_id)
		{
		
			$fecha_hoy=date('Y-m-d');
		
	
						$sql = " select codigo_medicamento,
							      SUM(numero_unidades) as total,
								  fc_descripcion_producto_alterno(codigo_medicamento) as descripcion_prod
							      from
							          (
							          select
							          dc.codigo_medicamento,
							          SUM(dc.cantidad) as numero_unidades
							          FROM  esm_pendientes_por_dispensar as dc
									 WHERE      dc.formula_id = ".$formula_id."
									 and        dc.sw_estado = '0'
									group by(dc.codigo_medicamento)
									) as A
									group by (codigo_medicamento)
                   ";
			
				 if(!$rst = $this->ConexionBaseDatos($sql))
          				return false;
          				$datos = array();
          				while(!$rst->EOF)
          				{
          				$datos[] = $rst->GetRowAssoc($ToUpper);
          				$rst->MoveNext();
          				}
          				$rst->Close();
          				return $datos;
					
		}
	/*  PENDIENTES */
	function Consultar_Medicamentos_Detalle_P($Formulario,$formula_id)
		{
   
   
        if($Formulario['codigo_barras']!="")
        {
        $filtro = " and A.codigo_barras = '".$Formulario['codigo_barras']."' ";
        }
			
		if($Formulario['descripcion']!="" || $Formulario['codigo_barras']!="")
        {
		
				 $sql = "SELECT        tmp.codigo_medicamento as codigo_producto,
										SUM(tmp.cantidad) as cantidad,
										fc_descripcion_producto_alterno(tmp.codigo_medicamento) as descripcion_prod,
										MED.cod_principio_activo
							
							
					 FROM   esm_pendientes_por_dispensar tmp,
							inventarios_productos A,
							medicamentos MED
							
							
							
					 WHERE    tmp.formula_id='".$formula_id."'
					 AND    tmp.codigo_medicamento= A.codigo_producto and 	tmp.sw_estado='0'
					 AND    A.codigo_producto=MED.codigo_medicamento
					
					
					 AND   	 A.descripcion ILIKE '%".$Formulario['descripcion']."%' 
					  ".$filtro;
					  
					  $sql .= " group by  tmp.codigo_medicamento,MED.cod_principio_activo ";
			
		    	
			
		}	
  
    		if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }

                $resultado->Close();

                return $cuentas;   
    } 
	
	/* consultar  ULTIMO REGISTRO DE LO DISPENSADO */
	 function ConsultarUltimoResg_Dispens($formula_id,$fecha_actual,$fecha_menos_dias)
       {
	     
			$sql = "select codigo_producto,
							SUM(numero_unidades) as total,
							fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod
							from ( 
							select dd.codigo_producto, 
							SUM(dd.cantidad) as numero_unidades 
							FROM esm_formulacion_despachos_medicamentos as dc, 
							     bodegas_documentos as d, 
								 bodegas_documentos_d AS dd 
							WHERE dc.bodegas_doc_id = d.bodegas_doc_id 
							and dc.numeracion = d.numeracion 
							AND d.fecha_registro >= '$fecha_menos_dias 00:00:00' AND d.fecha_registro <= '$fecha_actual 24:00:00' 
						
							and d.bodegas_doc_id = dd.bodegas_doc_id 
							and d.numeracion = dd.numeracion 
							and dc.formula_id=".$formula_id."
							and dc.sw_estado='1'
							group by(dd.codigo_producto) ) as A group by (codigo_producto) ";

		
		
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[]= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
        }
	/* ACTUALIZAR  ESTADO DE LA FORMULA */
		 
		function UpdateEstad_Form($formula_id)
		{
			
			$sql = "   	 update esm_formula_externa
		                 set    sw_estado='0'
						WHERE   formula_id = '".$formula_id."'  ";
					
					if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while(!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
	 
	 	 }
	/* CONSULTAR EL UTLMO REGISTRO DISPENSADO POR  PRINCIPIO ACTIVO*/
	 function ConsultarUltimoResg_Dispens_($formula_id,$fecha_actual,$fecha_menos_dias,$principio_activo,$paciente_id,$tipo_id_paciente)
       {
	   
			$sql = "select codigo_producto,
							SUM(numero_unidades) as total,
							fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod
							from ( 
							select dd.codigo_producto, 
							SUM(dd.cantidad) as numero_unidades 
							FROM esm_formulacion_despachos_medicamentos as dc, 
							     bodegas_documentos as d, 
								 bodegas_documentos_d AS dd ,
								 inventarios_productos inve,
								 medicamentos mm,
								 esm_formula_externa EXT
							WHERE dc.bodegas_doc_id = d.bodegas_doc_id 
							and dc.numeracion = d.numeracion 
							AND d.fecha_registro >= '$fecha_menos_dias 00:00:00' AND d.fecha_registro <= '$fecha_actual 24:00:00' 
						
							and d.bodegas_doc_id = dd.bodegas_doc_id 
							and d.numeracion = dd.numeracion 
							and dd.codigo_producto=inve.codigo_producto
							and inve.codigo_producto=mm.codigo_medicamento
							and dc.formula_id=EXT.formula_id
							and mm.cod_principio_activo='".$principio_activo."'
							and EXT.tipo_id_paciente='".$tipo_id_paciente."'
							and EXT.paciente_id='".$paciente_id."'
							and dc.sw_estado='1'
							group by(dd.codigo_producto) ) as A group by (codigo_producto) ";

		
		
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[]= $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
        }
	/* SACAR LOS PENDIENTES DISPENSADOS */
	
	function pendientes_dispensados_ent($formula_id)
		{
		
			$fecha_hoy=date('Y-m-d');
		
			$sql = "    SELECT   dd.codigo_producto,
							dd.cantidad as numero_unidades,
							dd.fecha_vencimiento , 
							dd.lote,
							 fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod
				   FROM esm_formulacion_despachos_medicamentos_pendientes tmp,
						bodegas_documentos as d, 
						bodegas_documentos_d AS dd
				   WHERE tmp.bodegas_doc_id = d.bodegas_doc_id 
				   and tmp.numeracion = d.numeracion 
				   and d.bodegas_doc_id = dd.bodegas_doc_id 
				   and to_char(d.fecha_registro,'YYYY-mm-dd')='".$fecha_hoy."'
				   and d.numeracion = dd.numeracion 
				   
						
					and  tmp.formula_id = '".$formula_id."' ";
					
					
					
		
		
					
					
					if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while(!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
	 
	 	 }
	/*  CONSULTAR SI LA FORMULA YA FUE DISPENSADA */
	function Consultar_Formula_Dispensada($formula_id)
	{
	
					$sql = " SELECT  formula_id
								FROM   esm_formulacion_despachos_medicamentos_pendientes
								WHERE  formula_id = ".$formula_id."

								UNION 
								SELECT   formula_id
								FROM     esm_formulacion_despachos_medicamentos
								WHERE    formula_id =  ".$formula_id." ";
				
					
					if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while(!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
	}
	/* inactivar Formula */
	 
		function UpdateEstad_Form_d($formula_id)
		{
			
			$sql = "   	 update esm_formula_externa
		                 set    sw_estado='0'
						WHERE   formula_id = '".$formula_id."'  ";
					
					if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while(!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
	 
	 	 }
		/* CONSULTAR LOS FORMULAS  CON CADA PRODUCTO */
		
	
	
		function Consultar_Formula_Verificar_pro($formula_id)
	{
			
		    $sql = "SELECT  
							tmp.codigo_producto,
							tmp.unidad_dosificacion,
							tmp.tiempo_tratamiento,
							tmp.unidad_tiempo_tratamiento,
							tmp.periodicidad_entrega,
							tmp.unidad_periodicidad_entrega,
							tmp.via_administracion_id,
						    fc_descripcion_producto_alterno(tmp.codigo_producto) as descripcion_prod,
							POS.*,
							EXT.*,
							MED.cod_principio_activo
							
					 FROM   esm_formula_externa_medicamentos tmp,
							inventarios_productos A,
							esm_formula_externa_posologia POS,
							esm_formula_externa EXT,
							medicamentos MED
							
							
					 WHERE    tmp.formula_id='".$formula_id."'
					 AND    tmp.codigo_producto= A.codigo_producto
					 AND    tmp.fe_medicamento_id=POS.fe_medicamento_id
					 AND    tmp.formula_id=EXT.formula_id
					 AND    A.codigo_producto=MED.codigo_medicamento ";
					
	
					
					if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while(!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
	}
	
	/*  BUSCAR DETALLE DEL PRODUCTO AMBULATORIO */
	function Consultar_Medicamentos_Detalle_AMBU($Formulario,$formula_id)
	{
    
        if($Formulario['codigo_barras']!="")
        {
        $filtro = " and A.codigo_barras = '".$Formulario['codigo_barras']."' ";
        }
			
		if($Formulario['descripcion']!="" || $Formulario['codigo_barras']!="")
        {
		    $sql = "SELECT  tmp.fe_medicamento_id,
							tmp.codigo_producto,
							tmp.cantidad,
							tmp.dosis,
							tmp.unidad_dosificacion,
							tmp.tiempo_tratamiento,
							tmp.unidad_tiempo_tratamiento,
							tmp.periodicidad_entrega,
							tmp.unidad_periodicidad_entrega,
							tmp.via_administracion_id,
						    fc_descripcion_producto_alterno(tmp.codigo_producto) as descripcion_prod,
							EXT.*,
							MED.cod_principio_activo
							
					 FROM   esm_formula_externa_medicamentos tmp,
							inventarios_productos A left join medicamentos MED ON(A.codigo_producto=MED.codigo_medicamento),
							esm_formula_externa EXT,
							medicamentos MED
							
							
					 WHERE    tmp.formula_id='".$formula_id."'
					 AND    tmp.codigo_producto= A.codigo_producto
					 AND    tmp.formula_id=EXT.formula_id
					 AND   	 A.descripcion ILIKE '%".$Formulario['descripcion']."%'
                     AND  	tmp.sw_marcado='0'			 
					  ".$filtro;
			
		    	
		}	
  
    		if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }

                $resultado->Close();

                return $cuentas;   
    } 
	
	function ObtenerPermisos_Formula()
		{
		
			$sql  = "SELECT a.empresa_id, ";
			$sql .= "       b.razon_social AS razon, ";
		    $sql .= "       a.centro_utilidad, ";
			$sql .= "       c.descripcion AS centro, ";
			$sql .= "       a.usuario_id ";
			$sql .= "FROM 	userpermisos_Formulacion_Externa a, ";
			$sql .= "       empresas b, ";
			$sql .= "       centros_utilidad c ";
			$sql .= "WHERE  a.usuario_id = ".UserGetUID()."  ";
			$sql .= "AND 	  a.empresa_id = b.empresa_id ";
			$sql .= "AND 	  a.empresa_id = c.empresa_id ";
			$sql .= "AND 	  a.centro_utilidad = c.centro_utilidad ";
			$sql .= "AND      b.sw_activa = '1' ";
			$sql .= "AND      a.sw_activo = '1' ";
		
			
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}

			$rst->Close();
			return $datos;
		}
	
	}
?>