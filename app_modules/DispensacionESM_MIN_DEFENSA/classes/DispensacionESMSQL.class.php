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
	
	    
			$sql = "SELECT        fr.formula_id,
                    fr.formula_papel,   
                            to_char(fr.fecha_formula,'yyyy-mm-dd') as fecha_formula,
                            fr.sw_estado,
                            FORM.sw_ambulatoria,
                          fr.tipo_id_paciente,
                            fr.paciente_id,
                            fr.plan_id,
                            prof.nombre AS profesional_esm,
                            tprof.descripcion as descripcion_profesional_esm,
                          terc.nombre_tercero AS ESM_atendio,
                            pac.primer_apellido ||' '||pac.segundo_apellido AS apellidos,
                            pac.primer_nombre||' '||pac.segundo_nombre AS nombres
                    FROM esm_formula_externa as fr
              JOIN terceros as terc ON (fr.esm_tipo_id_tercero = terc.tipo_id_tercero) and (fr.esm_tercero_id = terc.tercero_id)
              LEFT JOIN profesionales as prof ON (fr.tipo_id_tercero = prof.tipo_id_tercero) and (fr.tercero_id = prof.tercero_id)
                            JOIN tipos_profesionales as tprof ON (prof.tipo_profesional = tprof.tipo_profesional)
                            LEFT JOIN pacientes as pac ON (fr.tipo_id_paciente = pac.tipo_id_paciente) and (fr.paciente_id = pac.paciente_id)
                            LEFT JOIN esm_tipos_formulas as form ON (fr.tipo_formula = form.tipo_formula_id)
                       
                    WHERE  
                            fr.sw_estado!='2'
                    AND     fr.sw_corte='0'			";
			
			
			if($filtros)
			{
				if($filtros['tipo_id_paciente']!= '-1' && $filtros['tipo_id_paciente']!= '')
				$sql .= "AND   pac.tipo_id_paciente = '".$filtros['tipo_id_paciente']."' ";
				if($filtros['paciente_id'])
				$sql .= "AND   pac.paciente_id = '".$filtros['paciente_id']."' ";

				if($filtros['nombres'] || $filtros['apellidos'])
				{
					$util = AutoCarga::factory('ClaseUtil');
					$whr .= "AND      ".$util->FiltrarNombres($filtros['nombres'],$filtros['apellidos'],"pac");
				}
             }			
			if($filtros['formula_papel'])
				{
					$sql .= "AND   fr.formula_papel ilike  '%".$filtros['formula_papel']."%' ";
				
				}
			 if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",$offset))
			return false;

			$whr .= " ORDER BY fr.fecha_registro DESC,apellidos,nombres ";
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
		//$this->debug=true;
		    $sql ="SELECT  DXT.diagnostico_id,
						   DX.diagnostico_nombre
					FROM   esm_formula_externa_diagnosticos DXT,
						   diagnosticos DX
					WHERE DXT.diagnostico_id=DX.diagnostico_id 
					and   DXT.formula_id=".$tmp_id." ";
				


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
							fc_codigo_mindefensa(tmp.codigo_producto) as codigo_producto_mini,
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
							inventarios_productos A LEFT JOIN medicamentos b ON (A.codigo_producto = b.codigo_medicamento) LEFT JOIN inv_med_cod_principios_activos c on(b.cod_principio_activo = c.cod_principio_activo) LEFT JOIN inv_med_cod_forma_farmacologica  d ON(b.cod_forma_farmacologica = d.cod_forma_farmacologica)
							
						
					 WHERE    tmp.formula_id='".$tmp_id."'
					 AND    tmp.codigo_producto= A.codigo_producto
					
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
							tmp.fecha_entrega,
							tmp.proxima_fecha_entrega,
						    fc_descripcion_producto_alterno(tmp.codigo_producto) as descripcion_prod,
							fc_codigo_mindefensa(tmp.codigo_producto) as codigo_producto_mini,
							EXT.*,
							MED.cod_principio_activo,
							MED.unidad_medida_medicamento_id,
                            MED.concentracion_forma_farmacologica,
							POS.*,
							tmp.sw_autorizado
					 FROM   esm_formula_externa_medicamentos tmp,
							inventarios_productos A left join medicamentos MED ON(A.codigo_producto=MED.codigo_medicamento),
							esm_formula_externa EXT,
							esm_formula_externa_posologia POS
							
							
							
					 WHERE    tmp.formula_id='".$formula_id."'
					 AND    tmp.codigo_producto= A.codigo_producto
					 AND    tmp.formula_id=EXT.formula_id
					 AND    tmp.fe_medicamento_id=POS.fe_medicamento_id
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

	function Consultar_ExistenciasBodegas($principio_activo,$Formulario,$farmacia,$centrou,$bodega,$producto,$unidad_medida_medicamento_id,$concentracion_forma_farmacologica)
		{
  
        if($Formulario['lote']!="")
        {
        $filtro = " and fv.lote = '".$Formulario['lote']."' ";
        }
/*and (fv.codigo_producto||''||fv.lote NOT IN 
                                          (
                                              select 
                                              codigo_producto||''||lote as producto
                                               from
                                               esm_dispensacion_medicamentos_tmp
                                               where
                                                    empresa_id = '".trim($Formulario['empresa_id'])."'
                                               and  centro_utilidad = '".trim($Formulario['centro_utilidad'])."'
                                               and  bodega = '".trim($Formulario['bodega'])."'
                                          ))
    	
  */ //$this->debug=true;
      $sql = "
              select
              fc_descripcion_producto_alterno(fv.codigo_producto) as producto,
			  fc_codigo_mindefensa(fv.codigo_producto) as codigo_producto_mini,
              fv.*
              from
              existencias_bodegas_lote_fv AS fv
              JOIN existencias_bodegas as ext ON (fv.empresa_id = ext.empresa_id) 
              and (fv.centro_utilidad = ext.centro_utilidad) 
              and (fv.bodega = ext.bodega) 
              and (fv.codigo_producto = ext.codigo_producto)
          
              JOIN inventarios as inv ON (ext.empresa_id = inv.empresa_id) 
              and (ext.codigo_producto = inv.codigo_producto)
              JOIN inventarios_productos as invp ON (inv.codigo_producto = invp.codigo_producto)
              where
              fv.empresa_id = '".trim($Formulario['empresa_id'])."'
              and fv.centro_utilidad = '".trim($Formulario['centro_utilidad'])."'
              and fv.bodega = '".trim($Formulario['bodega'])."'
              and fv.existencia_actual > 0
              and fv.codigo_producto = '".$producto."'
              $filtro
              ORDER BY invp.descripcion ASC,fv.fecha_vencimiento ASC      
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

	/* CONSULTAR  TMP */
	function Cantidad_ProductoTemporal($doc_tmp_id,$principio_activo,$codigo_producto)
    {
	
	
	  $sql = "  SELECT COALESCE(sum(cantidad_despachada),0) as total,codigo_formulado
	            from   esm_dispensacion_medicamentos_tmp 
				     	where codigo_formulado='".$codigo_producto."'
				and    formula_id = ".$doc_tmp_id." ";
				
				/*if($principio_activo!="")
				{
				
				    $sql .="   and    med.cod_principio_activo = '".$principio_activo."'  ";
					   
	            }else
				{
				
				
				$sql .="   and    invp.codigo_producto = '".$codigo_producto."'  ";
				
				}*/
				$sql .= "  GROUP BY 	codigo_formulado ";
      
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
	 function Buscar_ProductoLote($doc_tmp_id,$codigo_producto,$lote,$codigo_productoD)
    {
    

	   $sql  = "SELECT * ";
      $sql .= "FROM   esm_dispensacion_medicamentos_tmp ";
      $sql .= "WHERE  formula_id = ".$doc_tmp_id." ";
      $sql .= "and    codigo_producto = '".$codigo_productoD."' ";
      $sql .= "and    lote = '".$lote."'   
	           and codigo_formulado= '".$codigo_producto."' ";
      
	
	
	  
	  
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return $this->frmError['MensajeError'];

      if(!$rst->EOF)
      {
        $datos[]= $rst->GetRowAssoc($ToUpper = false);
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
						fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod,
						fc_codigo_mindefensa(codigo_producto) as codigo_producto_mini
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
	 function Buscar_producto_EN_OTRA_FRM($empresa_id,$centro_utilidad,$bodega,$principio_activo,$codigo_producto)
    {
	
      $sql  = " SELECT   EX.bodega,
						 EX.existencia,
						 BOD.descripcion as bodega_des
				FROM     existencias_bodegas  EX,
							bodegas BOD,
							inventarios inv,
				inventarios_productos invp
				WHERE    		  EX.empresa_id=inv.empresa_id
				and               EX.codigo_producto=inv.codigo_producto
				and               inv.codigo_producto=invp.codigo_producto 
				and               EX.empresa_id = '".$empresa_id."' 
				AND      EX.centro_utilidad = '".$centro_utilidad."'  ";
				
				if(!empty($principio_activo))
				{
				   $sql .= "  and  invp.subclase_id = '".$principio_activo."'  ";
				
				}else
				
				{
				
				     $sql .= "  and  EX.codigo_producto = '".$codigo_producto."'  ";
				
				}
				
				
				$sql .="   
			    
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
						fc_codigo_mindefensa(codigo_producto) as codigo_producto_mini,
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
						  fc_descripcion_producto_molecula(dd.codigo_producto) as molecula,
						  d.usuario_id,
						  sys.nombre,
						  sys.descripcion,
						  fc_codigo_mindefensa(dd.codigo_producto) as codigo_producto_mini,
						  dd.sw_pactado,
						  dd.total_costo
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
								  fc_descripcion_producto_alterno(codigo_medicamento) as descripcion_prod,
								  fc_codigo_mindefensa(codigo_medicamento) as codigo_producto_mini
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
										fc_codigo_mindefensa(tmp.codigo_medicamento) as codigo_producto_mini,
										MED.cod_principio_activo
							
							
					 FROM   esm_pendientes_por_dispensar tmp,
							inventarios_productos A left join medicamentos MED ON (A.codigo_producto=MED.codigo_medicamento)
							
							
							
							
					 WHERE    tmp.formula_id='".$formula_id."'
					 AND    tmp.codigo_medicamento= A.codigo_producto and 	tmp.sw_estado='0'
								
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
	/* ACTUALIZAR  ESTADO DE LA FORMULA cuando se despacha */
		 
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
		 /* ACTUALIZAR  ESTADO DE LA FORMULA cuando  esta vencida  */
		 function UpdateEstad_Form_venci($formula_id)
		{
			
			$sql = "   	 update esm_formula_externa
		                 set    sw_estado='2'
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
		 
		 
		 		 
	/* CONSULTAR EL UTLMO REGISTRO DISPENSADO POR  PRINCIPIO ACTIVO  SI LO HAY SI NO POR CODIGO DE PRODUCTO */
	 function ConsultarUltimoResg_Dispens_($formula_id,$fecha_actual,$fecha_menos_dias,$principio_activo,$paciente_id,$tipo_id_paciente,$producto)
       {
	  
	
	   $sql = " SELECT                A.resultado,
			                          A.fecha_registro,
									  A.unidades,
									  A.formula_papel,
									  A.nombre,
									  A.razon_social
									  
		    FROM ( 

						SELECT      to_char(d.fecha_registro,'YYYY-mm-dd') AS fecha_registro,
						            '1' as resultado,
									SUM(dd.cantidad) as unidades,
									EXT.formula_papel,
									SYS.nombre,
									EMPRE.razon_social
						FROM         esm_formulacion_despachos_medicamentos as dc, 
								     bodegas_documentos as d, 
									 bodegas_documentos_d AS dd ,
									 inventarios_productos inve  left join medicamentos mm ON (inve.codigo_producto=mm.codigo_medicamento) ,
							    	 esm_formula_externa EXT,
									system_usuarios  SYS,
									bodegas_doc_numeraciones  NUME,
									empresas EMPRE
						WHERE dc.bodegas_doc_id = d.bodegas_doc_id 
						and dc.numeracion = d.numeracion 
						and d.bodegas_doc_id = dd.bodegas_doc_id 
						and d.numeracion = dd.numeracion 
						and dd.codigo_producto=inve.codigo_producto
						and dc.formula_id=EXT.formula_id 
                        and d.usuario_id=SYS.usuario_id	
                        and d.bodegas_doc_id=NUME.bodegas_doc_id	
                        and NUME.empresa_id=EMPRE.empresa_id						";
						if($principio_activo!="")
							{
							   $sql .= " and mm.cod_principio_activo='".$principio_activo."' ";
							}
						
						else
							{
							  $sql .= " and inve.codigo_producto='".$producto."' ";
							
							}
						
						$sql .= "  	and EXT.tipo_id_paciente='".$tipo_id_paciente."'
									and EXT.paciente_id='".$paciente_id."'
									and dc.sw_estado='1'
								and EXT.sw_estado!='2'
									GROUP BY d.fecha_registro,resultado,EXT.formula_papel,SYS.nombre,razon_social
					
					
					UNION
					
						SELECT      to_char(d.fecha_registro,'YYYY-mm-dd') AS fecha_registro,
								    '0' as resultado,
									SUM(dd.cantidad) as unidades,
									EXT.formula_papel,
									SYS.nombre,
									EMPRE.razon_social
						FROM         esm_formulacion_despachos_medicamentos_pendientes as dc, 
								     bodegas_documentos as d, 
									 bodegas_documentos_d AS dd ,
									 inventarios_productos inve  left join medicamentos mm ON (inve.codigo_producto=mm.codigo_medicamento) ,
							    	 esm_formula_externa EXT,
									system_usuarios  SYS,
									bodegas_doc_numeraciones  NUME,
									empresas EMPRE
						WHERE dc.bodegas_doc_id = d.bodegas_doc_id 
						and dc.numeracion = d.numeracion 
						and d.bodegas_doc_id = dd.bodegas_doc_id 
						and d.numeracion = dd.numeracion 
						and dd.codigo_producto=inve.codigo_producto
						and dc.formula_id=EXT.formula_id  and d.usuario_id=SYS.usuario_id
                         and d.bodegas_doc_id=NUME.bodegas_doc_id	
                        and NUME.empresa_id=EMPRE.empresa_id							";
						if($principio_activo!="")
							{
							   $sql .= " and mm.cod_principio_activo='".$principio_activo."' ";
							}

							else
							{
							  $sql .= " and inve.codigo_producto='".$producto."' ";
							
							}							
						$sql .= "				
										and EXT.tipo_id_paciente='".$tipo_id_paciente."'
										and EXT.paciente_id='".$paciente_id."'
										and EXT.sw_estado!='2'
										GROUP BY  d.fecha_registro,EXT.formula_papel,SYS.nombre,razon_social
						
				
				   ) AS A    ORDER BY  A.resultado ASC ";
	  
	  		
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
	/* SACAR LOS PENDIENTES DISPENSADOS */
	
	function pendientes_dispensados_ent($formula_id)
		{
		
			$fecha_hoy=date('Y-m-d');
				
			$sql = "    SELECT   dd.codigo_producto,
							dd.cantidad as numero_unidades,
							dd.fecha_vencimiento , 
							dd.lote,
							 fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
							 fc_codigo_mindefensa(dd.codigo_producto) as codigo_producto_mini,
							 dd.sw_pactado,
							 fc_descripcion_producto_molecula(dd.codigo_producto) as molecula,
							   dd.total_costo
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
								WHERE    formula_id =  ".$formula_id." and sw_estado='1' ";
				
					
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
							EXT.*
							
							
					 FROM   esm_formula_externa_medicamentos tmp,
							inventarios_productos A,
							esm_formula_externa_posologia POS,
							esm_formula_externa EXT
						
							
							
					 WHERE    tmp.formula_id='".$formula_id."'
					 AND    tmp.codigo_producto= A.codigo_producto
					 AND    tmp.fe_medicamento_id=POS.fe_medicamento_id
					 AND    tmp.formula_id=EXT.formula_id
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
	
	/*  BUSCAR DETALLE DEL PRODUCTO AMBULATORIO */
	function Consultar_Medicamentos_Detalle_AMBU($Formulario,$formula_id)
	{
    
        if($Formulario['codigo_barras']!="")
        {
        $filtro = " and A.codigo_barras = '".$Formulario['codigo_barras']."' ";
        }
			
		//if($Formulario['descripcion']!="" || $Formulario['codigo_barras']!="")
      //  {
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
							fc_codigo_mindefensa(tmp.codigo_producto) as codigo_producto_mini,
							EXT.*,
							MED.cod_principio_activo,
							MED.unidad_medida_medicamento_id,
                            MED.concentracion_forma_farmacologica,
							tmp.sw_autorizado
					 FROM   esm_formula_externa_medicamentos tmp,
							inventarios_productos A left join medicamentos MED ON(A.codigo_producto=MED.codigo_medicamento),
							esm_formula_externa EXT
							
							
							
					 WHERE    tmp.formula_id='".$formula_id."'
					 AND    tmp.codigo_producto= A.codigo_producto
					 AND    tmp.formula_id=EXT.formula_id
					 AND   	 A.descripcion ILIKE '%".$Formulario['descripcion']."%'
                     AND  	tmp.sw_marcado='0'			 
					  ".$filtro;
			
		    	
		//}	
  
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
		/*  CONSULTAR EL ULTIMO REGISTRO DIS HOSP*/
		
		 function ConsultarUltimoResg_Dispens_H($formula_id,$fecha_actual,$fecha_menos_dias,$principio_activo,$paciente_id,$tipo_id_paciente,$producto)
       { 
	   
	 
	  
			$sql = " SELECT dc.esm_formulacion_despacho_id,
                           	to_char(d.fecha_registro,'yyyy-mm-dd') as fecha_registro
					FROM    esm_formulacion_despachos_medicamentos as dc, 
							bodegas_documentos as d, 
							esm_formula_externa EXT
					WHERE   dc.bodegas_doc_id = d.bodegas_doc_id 
							and dc.numeracion = d.numeracion 
												
							and EXT.tipo_id_paciente='".$tipo_id_paciente."'
							and EXT.paciente_id='".$paciente_id."'
							
							and dc.sw_estado='1'
							ORDER BY  d.fecha_registro  DESC  LIMIT 1 ";

		
		
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
		
		 function Buscar_ProductoLote_($doc_tmp_id,$codigo_producto,$lote)
    {
    

	   $sql  = "SELECT * ";
      $sql .= "FROM   esm_dispensacion_medicamentos_tmp ";
      $sql .= "WHERE  formula_id = ".$doc_tmp_id." ";
     $sql .= "and    codigo_producto = '".$codigo_producto."' ";
      $sql .= "and    lote = '".$lote."'   ";
      
	  
	
	  
	  
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return $this->frmError['MensajeError'];

      if(!$rst->EOF)
      {
        $datos[]= $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }

      $rst->Close();

      return $datos;  
    }
	/*  USUARIO CON PROVILEGIOS */
	
	
	function Usuario_Privilegios_($Formulario)
    {

		$sql = "	SELECT  sw_privilegios
                	FROM     userpermisos_dispensacionesm
					WHERE    empresa_id= '".$Formulario['empresa_id']."' 
					AND     centro_utilidad = '".$Formulario['centro_utilidad']."'
					AND     bodega = '".trim($Formulario['bodega'])."' 
					AND    usuario_id =  ".UserGetUID()."
					AND    sw_activo = '1' ";
	
  
		      $datos = array();
		      if(!$rst = $this->ConexionBaseDatos($sql))
		        return $this->frmError['MensajeError'];

		      if(!$rst->EOF)
		      {
		        $datos= $rst->GetRowAssoc($ToUpper = false);
		        $rst->MoveNext();
		      }

		      $rst->Close();

		      return $datos;  
    }
	/* AUTORIZACION PARA LOS MEDICAMENTOS A DESPACHAR */
	
	   function UpdateAutorizacion_por_medicamento($formula_id,$observacion,$producto)
		{
			$this->ConexionTransaccion();
			$sql = "   	 update  esm_formula_externa_medicamentos
		                 set  sw_autorizado='1', 
						      usuario_autoriza_id= ".UserGetUID().",
						      observacion_autorizacion='".$observacion."',
							  fecha_registro_autorizacion=now()
						 
						WHERE   formula_id = ".$formula_id."
						AND 	codigo_producto = '".$producto."'
						  ";
				if(!$rst = $this->ConexionTransaccion($sql))
						return false;

						$this->Commit();
						return true;
	   
	 	 }
	/**  cantidad TOTAL DESPACHADA */
	
	 function ConsultarUltimoResg_Dispens_CANTIDAD($formula_id,$fecha_actual,$fecha_menos_dias,$principio_activo,$paciente_id,$tipo_id_paciente,$producto)
       {
	   //$this->debug=true;
	  
	   $sql = " SELECT         (COALESCE(A.unidades_a,0) +  COALESCE(B.unidades_b,0)) AS total
		    FROM (

						SELECT      SUM(dd.cantidad) as unidades_a
						          

						FROM         esm_formulacion_despachos_medicamentos as dc, 
								     bodegas_documentos as d, 
									 bodegas_documentos_d AS dd ,
									 inventarios_productos inve  left join medicamentos mm ON (inve.codigo_producto=mm.codigo_medicamento) ,
							    	 esm_formula_externa EXT

						WHERE dc.bodegas_doc_id = d.bodegas_doc_id 
						and dc.numeracion = d.numeracion 
						and d.bodegas_doc_id = dd.bodegas_doc_id 
						and d.numeracion = dd.numeracion 
						and dd.codigo_producto=inve.codigo_producto
						and dc.formula_id=EXT.formula_id ";
						if($principio_activo!="")
							{
							   $sql .= " and mm.cod_principio_activo='".$principio_activo."' ";
							}
						
						else
							{
							  $sql .= " and inve.codigo_producto='".$producto."' ";
							
							}
						
						$sql .= "  	and EXT.tipo_id_paciente='".$tipo_id_paciente."'
									and EXT.paciente_id='".$paciente_id."'
									and dc.sw_estado='1'
									and EXT.sw_estado!='2'
									
					) AS A, (
					
						SELECT    		SUM(dd.cantidad) as unidades_b
					

						FROM         esm_formulacion_despachos_medicamentos_pendientes as dc, 
								     bodegas_documentos as d, 
									 bodegas_documentos_d AS dd ,
									 inventarios_productos inve  left join medicamentos mm ON (inve.codigo_producto=mm.codigo_medicamento) ,
							    	 esm_formula_externa EXT
						WHERE dc.bodegas_doc_id = d.bodegas_doc_id 
						and dc.numeracion = d.numeracion 
						and d.bodegas_doc_id = dd.bodegas_doc_id 
						and d.numeracion = dd.numeracion 
						and dd.codigo_producto=inve.codigo_producto
						and dc.formula_id=EXT.formula_id  ";
						if($principio_activo!="")
							{
							   $sql .= " and mm.cod_principio_activo='".$principio_activo."' ";
							}

							else
							{
							  $sql .= " and inve.codigo_producto='".$producto."' ";
							
							}							
						$sql .= "				
										and EXT.tipo_id_paciente='".$tipo_id_paciente."'
										and EXT.paciente_id='".$paciente_id."'
										and EXT.sw_estado!='2'
									
										
						
				
				   ) AS B ";
	  
	  
		
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
	/* consultar FECHA DE DISPENSACION DE UNA FORMULA */
	function ConsultarUltimoResg_Dispens_Hospitalizacion($formula_id,$paciente_id,$tipo_id_paciente)
       {
	   
	  
	   $sql = " SELECT                A.resultado,
			                          A.fecha_registro
									 
		    FROM (

						SELECT      to_char(d.fecha_registro,'YYYY-mm-dd') AS fecha_registro,
						            '1' as resultado
									
						FROM         esm_formulacion_despachos_medicamentos as dc, 
								     bodegas_documentos as d, 
								 	 esm_formula_externa EXT
						WHERE dc.bodegas_doc_id = d.bodegas_doc_id 
						and dc.numeracion = d.numeracion 
						and dc.formula_id=EXT.formula_id 
                        and dc.formula_id='".$formula_id."'
						";
						
						
						$sql .= "  	and EXT.tipo_id_paciente='".$tipo_id_paciente."'
									and EXT.paciente_id='".$paciente_id."'
									and dc.sw_estado='1'
									and EXT.sw_estado='1'
									GROUP BY d.fecha_registro,resultado
					
					
					UNION
					
						SELECT      to_char(d.fecha_registro,'YYYY-mm-dd') AS fecha_registro,
								    '0' as resultado
								
						FROM         esm_formulacion_despachos_medicamentos_pendientes as dc, 
								     bodegas_documentos as d, 
									 esm_formula_externa EXT
						WHERE dc.bodegas_doc_id = d.bodegas_doc_id 
						and dc.numeracion = d.numeracion 
						and dc.formula_id=EXT.formula_id
                        and dc.formula_id='".$formula_id."'						";
									
						$sql .= "				
										and EXT.tipo_id_paciente='".$tipo_id_paciente."'
										and EXT.paciente_id='".$paciente_id."'
										and EXT.sw_estado='1'
										GROUP BY  d.fecha_registro
						
				
				   ) AS A    ORDER BY  A.resultado ASC ";
	  
	  
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
		/* CONSULTAR DESPACHO DE LA FORMULA */
		
		function Consultar_Despacho_formula_ambula($formula_id)
       {
	      $sql = "  	SELECT esm_formulacion_despacho_id
						 FROM  esm_formulacion_despachos_medicamentos
						 WHERE formula_id= '".$formula_id."' and sw_estado='1' ";
	
	  
	  
	  
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
		
		/* medicamentos dispensados por lote */
		
			function Medicamentos_Dispensados_Esm_x_lote_total($formula_id)
		{
		
	
			$fecha_hoy=date('Y-m-d');
		
		
			$sql = " 	  select
						  dd.codigo_producto,
						  dd.cantidad as numero_unidades,
						  dd.fecha_vencimiento ,
						  dd.lote,
						  fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
						  fc_codigo_mindefensa(codigo_producto) as codigo_producto_mini,
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
		/* MEDICAMENTOS PENDIENTES DISPENSADOS TOTAL */
		function pendientes_dispensados_ent_TOTAL($formula_id)
		{
		
		
			$fecha_hoy=date('Y-m-d');
		
			$sql = "    SELECT   dd.codigo_producto,
							dd.cantidad as numero_unidades,
							dd.fecha_vencimiento , 
							dd.lote,
							 fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
							 fc_codigo_mindefensa(codigo_producto) as codigo_producto_mini
				   FROM esm_formulacion_despachos_medicamentos_pendientes tmp,
						bodegas_documentos as d, 
						bodegas_documentos_d AS dd
				   WHERE tmp.bodegas_doc_id = d.bodegas_doc_id 
				   and tmp.numeracion = d.numeracion 
				   and d.bodegas_doc_id = dd.bodegas_doc_id 
				
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
		 
		 
		 Function Actulizar_Estado_Formula_($formula)
	{
		  
			$this->ConexionTransaccion();
				$sql = " update esm_formula_externa 
				set 	 sw_estado='2'
				where   formula_id = ".$formula."
				 ";



		      if(!$rst1 = $this->ConexionTransaccion($sql))
		      {
		      return false;
		      }
	
				
				
				$this->Commit();
				return true;
	}
			
	}
?>