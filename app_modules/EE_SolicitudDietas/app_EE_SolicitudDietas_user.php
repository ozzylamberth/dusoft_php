<?php

/**
 * $Id: app_EE_SolicitudDietas_user.php,v 1.11 2006/01/02 14:56:25 mauricio Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_SolicitudDietas_user extends classModulo
{

    /**
    * Metodo Inicial
    *
    * @return boolean
    */
    function main()
    {
        $this->FrmPanelEstacion();
        return true;
    }


    /**
    * Metodo para obtener los userpermisos de un usuario para el modulo
    *
    * @param string $estacion_id opcional valida si el usuario tiene permiso en una estacion
    * @return boolean
    */
    function GetUserPermisos($estacion_id='',$componente=null, $datos=false)
    {
        if(!UserGetUID()) return null;
        if(empty($_SESSION['EE_PanelEnfermeria'][UserGetUID()]['ESTACIONES']))
        {
            list($dbconn) = GetDBconn();
            global $ADODB_FETCH_MODE;

            $query="
                    SELECT
                        f.razon_social as empresa_descripcion,
                        e.descripcion as centro_utilidad_descripcion,
                        d.descripcion as unidad_funcional_descripcion,
                        c.descripcion as departamento_descripcion,
                        b.descripcion as estacion_descripcion,
                        b.titulo_atencion_pacientes,
                        c.empresa_id,
                        c.centro_utilidad,
                        c.unidad_funcional,
                        c.departamento,
                        a.estacion_id,
                        b.hc_modulo_medico,
                        b.hc_modulo_enfermera,
                        b.hc_modulo_consulta_urgencias,
                        a.estacion_componente_id

                    FROM
                        (
                            SELECT  a.*
                            FROM
                                (
                                    (
                                        SELECT a.estacion_id, b.estacion_componente_id

                                        FROM estaciones_enfermeria_usuarios a,
                                        estaciones_enfermeria_perfiles_componentes as b

                                        WHERE
                                        a.usuario_id = ".UserGetUID()."
                                        AND b.estacion_perfil_id = a.estacion_perfil_id
                                    )
                                    UNION
                                    (
                                        SELECT a.estacion_id, a.estacion_componente_id

                                        FROM estaciones_enfermeria_usuarios_componentes as a

                                        WHERE
                                        a.usuario_id = ".UserGetUID()."
                                        AND sw_permiso = '1'
                                    )
                                ) as a LEFT JOIN
                                (
                                    SELECT a.estacion_id, a.estacion_componente_id
                                    FROM estaciones_enfermeria_usuarios_componentes as a
                                    WHERE a.usuario_id = ".UserGetUID()."  AND sw_permiso = '0'
                                ) as b
                                ON (b.estacion_id = a.estacion_id AND b.estacion_componente_id = a.estacion_componente_id)

                            WHERE b.estacion_id IS NULL
                        ) as a,
                        estaciones_enfermeria b,
                        departamentos c,
                        unidades_funcionales d,
                        centros_utilidad e,
                        empresas f

                    WHERE
                        b.estacion_id = a.estacion_id
                        AND c.departamento = b.departamento
                        AND d.unidad_funcional = c.unidad_funcional
                        AND d.centro_utilidad = c.centro_utilidad
                        AND d.empresa_id = c.empresa_id
                        AND e.centro_utilidad = c.centro_utilidad
                        AND e.empresa_id = c.empresa_id
                        AND f.empresa_id = c.empresa_id

                    ORDER BY c.empresa_id, c.centro_utilidad, c.unidad_funcional, c.departamento, a.estacion_id";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $resultado = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if ($dbconn->ErrorNo() != 0) {
                $this->error = "EE_Admin - SQL ERROR 1";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }

            if(!$resultado->EOF)
            {
                while($fila = $resultado->FetchRow())
                {
                    $_SESSION['EE_PanelEnfermeria'][UserGetUID()]['ESTACIONES'][$fila['estacion_id']]['COMPONENTES'][$fila['estacion_componente_id']] = $fila['estacion_componente_id'];
                    if(!isset($_SESSION['EE_PanelEnfermeria'][UserGetUID()]['ESTACIONES'][$fila['estacion_id']]['DATOS']))
                    {
                        unset($fila['estacion_componente_id']);
                        $_SESSION['EE_PanelEnfermeria'][UserGetUID()]['ESTACIONES'][$fila['estacion_id']]['DATOS'] = $fila;
                    }
                }

                $resultado->Close();
            }
            else
            {
                return null;
            }
        }//fin creacion del vector



        if($estacion_id)
        {
            if($componente)
            {
                if($_SESSION['EE_PanelEnfermeria'][UserGetUID()]['ESTACIONES'][$estacion_id]['COMPONENTES'][$componente])
                {
                    return true;
                }
                else
                {
                    return null;
                }
            }

            if($_SESSION['EE_PanelEnfermeria'][UserGetUID()]['ESTACIONES'][$estacion_id]['DATOS'])
            {
                if($datos)
                {
                    return $_SESSION['EE_PanelEnfermeria'][UserGetUID()]['ESTACIONES'][$estacion_id]['DATOS'];
                }
                else
                {
                    return true;
                }
            }
            else
            {
                return null;
            }
        }
        else
        {
            if($_SESSION['EE_PanelEnfermeria'][UserGetUID()]['ESTACIONES'])
            {
                return $_SESSION['EE_PanelEnfermeria'][UserGetUID()]['ESTACIONES'];
            }
            else
            {
                return null;
            }
        }
    }

    /**
    * Metodo para obtener los pacientes internados en una estacion
    *
    * @param string $estacion_id
    * @return array
    * @access public
    */
    function GetPacientesInternados($estacion_id)
    {
        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query = "  SELECT 	A.*,
														B.descripcion AS descripcion_dieta,
														C.descripcion AS descripcion_caracteristica,
														C.descripcion_agrupamiento,
														C.codigo_agrupamiento
										FROM
												(
												SELECT 	A.*, 
																B.hc_dieta_id, 
																B.sw_fraccionada,
																B.sw_ayuno,
																B.observaciones,
																B.modificado_enfermeria,
																C.caracteristica_id
												
												FROM
														(
														SELECT
																a.movimiento_id,
																a.numerodecuenta,
																a.fecha_ingreso,
																b.pieza,
																a.cama,
																d.ingreso,
																d.fecha_ingreso,
																d.paciente_id,
																d.tipo_id_paciente,
																e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo
														
														FROM
																movimientos_habitacion a,
																camas b,
																cuentas c,
																ingresos d,
																pacientes e,
																planes f,
																terceros g
														WHERE
																a.fecha_egreso IS NULL
																AND a.estacion_id = '".$estacion_id."'
																AND b.cama = a.cama
																AND c.numerodecuenta = a.numerodecuenta
																AND d.ingreso = a.ingreso
																AND e.paciente_id = d.paciente_id
																AND e.tipo_id_paciente = d.tipo_id_paciente
																AND f.plan_id = c.plan_id
																AND g.tercero_id = f.tercero_id
																AND g.tipo_id_tercero = f.tipo_tercero_id
														) AS A LEFT JOIN hc_solicitudes_dietas AS B ON (A.ingreso = B.ingreso) 
														LEFT JOIN hc_solicitudes_dietas_detalle AS C ON (C.ingreso = B.ingreso AND C.evolucion_id = B.evolucion_id )
												) AS A LEFT JOIN hc_tipos_dieta AS B ON (A.hc_dieta_id = B.hc_dieta_id)
												LEFT JOIN hc_solicitudes_dietas_caracteristicas AS C ON (A.caracteristica_id = C.caracteristica_id)
												ORDER BY A.cama, A.pieza";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "EE_PanelEnfermeria - GetPacientesInternados";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            return null;
        }

				$I=0;
				while($fila = $resultado->FetchRow())
				{
					$filas[$fila['ingreso']]['DATOS_PACIENTE'] = $fila;
					//$filas[$fila['ingreso']]['DATOS_DIETAS'][$fila['hc_dieta_id']]=$fila['descripcion_dieta'];
					$filas[$fila['ingreso']]['DATOS_DIETAS']['dieta_id']=$fila['hc_dieta_id'];
					$filas[$fila['ingreso']]['DATOS_DIETAS']['descripcion']=$fila['descripcion_dieta'];
					$filas[$fila['ingreso']]['DATOS_CARACTERISTICAS'][$I]['caracteristica_id']=$fila['caracteristica_id'];
					$filas[$fila['ingreso']]['DATOS_CARACTERISTICAS'][$I]['descripcion_caracteristica']=TRIM($fila['descripcion_caracteristica'] . " " . $fila['descripcion_agrupamiento']);
					$filas[$fila['ingreso']]['DATOS_CARACTERISTICAS'][$I]['codigo_agrupamiento']=$fila['codigo_agrupamiento'];
					$I++;
				}
        $resultado->Close();
        return $filas;

    }//fin del metodo


    /**
    * Calcula los d�s que lleva hospitalizada una persona, basandose en la fecha de ingreso.
    *
    * @param timestamp fecha de ingreso del paciente
    * @return integer
    * @access Public
    */
    function GetDiasHospitalizacion($fecha_ingreso)
    {
        if(empty($fecha_ingreso)) return null;

        $date1 = date('Y-m-d H:i:s');

        $fecha_in=explode(".",$fecha_ingreso);
        $date2=$fecha_in[0];

        $s = strtotime($date1)-strtotime($date2);
        $d = intval($s/86400);
        $s -= $d*86400;
        $h = intval($s/3600);
        $s -= $h*3600;
        $m = intval($s/60);
        $s -= $m*60;

        if($d>0)
        {
            $dif= "$d  dias ";
        }
        else
        {
            $dif = "$h:$m horas ";
        }
        return $dif;
    }
		
		/**
		*
		*/
		function GetControlDietas()
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$dieta=array();
			$query = "SELECT * FROM hc_tipos_dieta ORDER BY hc_dieta_id";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado=$dbconn->Execute($query);
				if (!$resultado) {
					$this->error = "Error, la tabla hc_tipos_dieta no contiene registros";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
				}
			while ($data = $resultado->FetchRow()) {
				$dieta[]=$data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $dieta;
		}
		/**
		*
		*/
		function TraerInformacionAyuno($ingreso)
		{
			list($dbconn) = GetDBconn();
			$query="SELECT motivo,hora_fin_ayuno,hora_inicio_ayuno FROM hc_solicitudes_dietas_ayunos WHERE ingreso='".$ingreso."'
												AND fecha='".date("Y-m-d")."'";
			$resultado=$dbconn->Execute($query);
			if (!$resultado) {
				$this->error = "Error al buscar en la tabla hc_control_dietas ayuno<br>";
				$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
				return false;
			}

			if($resultado->EOF)
			{return '';}

			$var[0]=$resultado->fields[0];
			$var[1]=$resultado->fields[1];
			$var[2]=$resultado->fields[2];
			return $var;
		}
		/**
		*
		*/
		function GetDietas_Caracteristicas($valor,$sw)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
				if($sw==1)
				{
							 $query = "SELECT A.hc_dieta_id, A.caracteristica_id, B.*
												FROM hc_tipos_dieta_caracteristicas AS A, hc_solicitudes_dietas_caracteristicas AS B
												WHERE A.caracteristica_id=B.caracteristica_id
												AND B.sw_generica='0'
												AND B.sw_activo='1'
												AND A.hc_dieta_id=".$valor."
												ORDER BY B.indice_orden ASC;";
				}elseif($sw==2)
				{
							$query = "SELECT *
												FROM hc_solicitudes_dietas_caracteristicas
												WHERE sw_generica='1'
												AND sw_activo='1'
												ORDER BY codigo_agrupamiento ASC, indice_orden ASC;";
				}
				
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				
				if ($dbconn->ErrorNo() != 0)
				{
							$this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
							$this->mensajeDeError = "Ocurri�un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							return false;
				}
				while ($datos = $resultado->FetchRow())
				{
							$caracteristicas[] = $datos;
				}
				return $caracteristicas;
				
		}

		/**
		*
		*/
		function InsertaDieta()
		{
			 // 		print_r($_REQUEST);
			//  		exit;
            
            if(empty($_REQUEST['tipodieta']))
            {
              $_REQUEST['tipodieta']=0;
              $_REQUEST['nada_oral'] = 'nada';
            }
            //ECHO "LLEGUE".var_dump($_REQUEST['tipodieta']);
            $fecha_registro=$_SESSION['DIETAS']['FECHAREG'];
			
			if($_REQUEST['tipo_solicitud']=="desayuno")
            {
				$tipo_solicitud='1';
			}
            elseif($_REQUEST['tipo_solicitud']=="almuerzo")
            {
				$tipo_solicitud='2';
			}
            else
            {
				$tipo_solicitud='3';
			}
			$adicional='0';
			if($_REQUEST['cierre_adicional']=='1')
            {
				$adicional='1';
			}

            $usuario_activo=UserGetUID();
            
            list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
                $query="INSERT INTO dietas_solicitud(fecha_solicitud,
                                                    tipo_solicitud_dieta_id,
                                                    estacion_id,
                                                    ingreso_id,
                                                    usuario_id_confirmacion,
                                                    fecha_confirmacion

                                                )
							VALUES ('".$fecha_registro."',
                                    '$tipo_solicitud',
                                    '".$_REQUEST['datos_estacion']['estacion_id']."',
                                    '".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."',
                                    '".$usuario_activo."',
                                    'now()'
                                    )";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en dietas_solicitud";
				$this->mensajeDeError = "Error al insertar en dietas_solicitud .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				$dbconn->RollbackTrans();
				return false;
			}
			  $query="INSERT INTO dietas_solicitud_detalle( ingreso_id,
			     											fecha_solicitud,
														  	tipo_solicitud_dieta_id,
														    estacion_id,
                                                            tipo_cama_id,
                                                            hc_dieta_id,
                                                            sw_fraccionada,
                                                            sw_ayuno,
                                                            observacion,
                                                            fecha_registro,
                                                            usuario_id_registro,
                                                            sw_adicional,
                                                            sw_recibida
                                                            )
							VALUES ('".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."',
											'".$fecha_registro."',
											'$tipo_solicitud',
											'".$_REQUEST['datos_estacion']['estacion_id']."',
											'".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['cama']."',
											'".$_REQUEST['tipodieta']."',
											'".$_REQUEST['fraccionada']."',
											'".$_REQUEST['CtlAyuno']."',
											'".$_REQUEST['CtlDietasObs']."',
											'now()',
											'".UserGetUID()."',
											'".$adicional."',
                                            '0'
							         )";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en dietas_solicitud_detalle";
				$this->mensajeDeError = "Error al insertar en dietas_solicitud_detalle .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				$dbconn->RollbackTrans();
				return false;
			}
			foreach($_REQUEST['caracteristica_dieta'] AS $caract_dieta => $caract){
				$query="INSERT INTO dietas_solicitud_detalle_caracteristicas(ingreso_id,
																																		fecha_solicitud,
																																		tipo_solicitud_dieta_id,
																																		estacion_id,
																																		caracteristica_id)
								VALUES ('".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."',
												'".$fecha_registro."',
												'$tipo_solicitud',
												'".$_REQUEST['datos_estacion']['estacion_id']."',
												'".$caract."'
								)
				";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al insertar en dietas_solicitud_detalle_caracteristicas";
					$this->mensajeDeError = "Error al insertar en dietas_solicitud_detalle_caracteristicas .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
			}
 			$query = "UPDATE hc_solicitudes_dietas
								SET sw_fraccionada = '".$_REQUEST['fraccionada']."'
								WHERE ingreso = '".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."' AND
											hc_dieta_id 	= '".$_REQUEST['tipodieta']."'";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al actualizar hc_solicitudes_dietas";
				$this->mensajeDeError = "Error al actualizar hc_solicitudes_dietas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				$dbconn->RollbackTrans();
				return false;
			}

			if($_REQUEST['CtlAyuno'] == '1')
			{
				$inf_ayuno=$this->TraerInformacionAyuno($_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']);
				if(empty($inf_ayuno))
				{
					$query="INSERT INTO hc_solicitudes_dietas_ayunos
															(ingreso,fecha,motivo,usuario_id,fecha_registro,hora_fin_ayuno,hora_inicio_ayuno)
									VALUES ('".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."',
													'".$fecha_registro."',
													'".$_REQUEST['CtlDietasObsA']."',
													'".UserGetUID()."',
													'now()',
													'".$_REQUEST[horafin] ."',
													'".$_REQUEST[horainicio] ."')";
				}else{
					$query="UPDATE hc_solicitudes_dietas_ayunos
									SET fecha_registro = 'now()',
											hora_fin_ayuno = '".$_REQUEST[horafin] ."',
											hora_inicio_ayuno = '".$_REQUEST[horainicio] ."',
											motivo = '".$_REQUEST['CtlDietasObsA']."'
									WHERE ingreso = '".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."' AND
												fecha = '".$fecha_registro."'";
				}
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al actualizar/insertar ayunos";
					$this->mensajeDeError = "Error al actualizar ayunos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
			}
			$dbconn->CommitTrans();
			$this->FrmPanelDietas();
			return true;
		}
		/**
		*
		*/
		function GetNadaViaOral()
		{
			list($dbconn) = GetDBconn();
			$query = "SELECT hc_dieta_id FROM hc_tipos_dieta
               		WHERE abreviatura = 'NVO'";
			$resultado = $dbconn->Execute($query);
			if (!$resultado) {
					$this->error = "Error, la tabla hc_tipos_dieta no contiene registros";
					$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
					return false;
			}
			list($NVO)= $resultado->FetchRow();
			return $NVO;
		}


		/**
		*
		*/
		function ConsultaDietasEnfermeria($tipo,$datos_paciente,$estacion_id)
		{
			
				if($tipo=="desayuno"){
					$tipo_id='1';
				}elseif($tipo=="almuerzo"){
					$tipo_id='2';
				}else{
					$tipo_id='3';
				}
				$fecha_registro=$_SESSION['DIETAS']['FECHAREG'];
            $query = "SELECT     A.estado_dieta,
                                     A.motivo_cancelacion_dieta,
                                     A.usuario_id_cancelacion,
                                     C.fecha_solicitud,
									 C.hc_dieta_id,
									 C.sw_fraccionada,
									 C.sw_ayuno,
									 C.observacion,
									 D.caracteristica_id
                                     
									FROM dietas_solicitud AS A,
												dietas_tipos_solicitud AS B,
												dietas_solicitud_detalle AS C,
												dietas_solicitud_detalle_caracteristicas AS D
									WHERE A.tipo_solicitud_dieta_id = '$tipo_id' AND
												A.estacion_id = '".$estacion_id."'  AND
												A.tipo_solicitud_dieta_id = B.tipo_solicitud_dieta_id AND
												A.estacion_id = C.estacion_id AND
												A.tipo_solicitud_dieta_id = C.tipo_solicitud_dieta_id AND
												A.fecha_solicitud = C.fecha_solicitud AND
												C.ingreso_id = D.ingreso_id  AND
												C.fecha_solicitud = D.fecha_solicitud  AND
												C.ingreso_id = D.ingreso_id  AND
												C.ingreso_id = A.ingreso_id  AND
												C.tipo_solicitud_dieta_id = D.tipo_solicitud_dieta_id AND
												A.ingreso_id = '".$datos_paciente['DATOS_PACIENTE']['ingreso']."' AND
												A.fecha_solicitud = '".$fecha_registro."'";
				GLOBAL $ADODB_FETCH_MODE;
				list($dbconn) = GetDBconn();
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resultado = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                
                if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al consular en  dietas_solicitud......";
					$this->mensajeDeError = "Error al consular en  dietas_solicitud......<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}
				if (!$resultado) {
					$this->error = "Error, las tablas dieta enfermeria no contienen registros";
					$this->mensajeDeError = $query."Dietas_Solicitud...<br>".$dbconn->ErrorMsg();
					return false;
				}
				$I=0;
				unset($datos_paciente['DATOS_CARACTERISTICAS']);
                unset($datos_paciente['DATOS_DIETAS']);
				while($fila = $resultado->FetchRow())
				{
				    $datos_paciente['DATOS_PACIENTE']['sw_fraccionada']=$fila['sw_fraccionada'];
                    $datos_paciente['DATOS_PACIENTE']['sw_ayuno']=$fila['sw_ayuno'];
				    $datos_paciente['DATOS_PACIENTE']['observaciones']=$fila['observacion'];
				    $datos_paciente['DATOS_PACIENTE']['fecha_solicitud']=$fila['fecha_solicitud'];
                    $datos_paciente['DATOS_DIETAS']['dieta_id']=$fila['hc_dieta_id'];
                    $datos_paciente['DATOS_DIETAS']['estado_dieta']=$fila['estado_dieta'];
                    $datos_paciente['DATOS_DIETAS']['motivo_cancelacion_dieta']=$fila['motivo_cancelacion_dieta'];
                    $datos_paciente['DATOS_DIETAS']['usuario_id_cancelacion']=$fila['usuario_id_cancelacion'];
                    $datos_paciente['DATOS_CARACTERISTICAS'][$I]['caracteristica_id']=$fila['caracteristica_id'];
                    $I++;
				}
                $resultado->Close();
                // "<pre>".PRINT_R($datos_paciente,true)."</pre>";
				return $datos_paciente;
		}
		/**
		*
    */
		function UpdateDieta()
		{
  	//	echo "<br>";print_r($_REQUEST);
//  		exit;
			//$fecha_registro=date("Y-m-d");
            
            $fecha_registro=$_SESSION['DIETAS']['FECHAREG'];
			if($_REQUEST['tipo_solicitud']=="desayuno"){
					$tipo_solicitud='1';
				}elseif($_REQUEST['tipo_solicitud']=="almuerzo"){
					$tipo_solicitud='2';
				}else{
					$tipo_solicitud='3';
				}
			if(empty($_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['fecha_solicitud'] ))
            {
                $fechaSolicitud=$fecha_registro;
            }
            else
            {
                $fechaSolicitud=$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['fecha_solicitud'];
            }

            $adicional='0';
            //echo  "jukilandia".$_REQUEST['cierre_adicional'];
			if($_REQUEST['cierre_adicional']=='1')
            {
				$adicional='1';
			}

            list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
            //echo  "jukilandia".$_REQUEST['CtlDietasObs']."asii";
            IF($_REQUEST['tipodieta']=='' || $_REQUEST['tipodieta']=='-1')
            {
                $_REQUEST['tipodieta']=0;
                $_REQUEST['nada_oral'] = 'nada';
            }
            $query = "UPDATE	dietas_solicitud_detalle
								SET			hc_dieta_id 	= '".$_REQUEST['tipodieta']."',
												sw_fraccionada = '".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['sw_fraccionada']."',
												sw_ayuno 	= '".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['sw_ayuno']."',
												observacion 	= '".$_REQUEST['CtlDietasObs']."',
												fecha_registro 	= '".$fecha_registro."',
												usuario_id_registro 	= '".UserGetUID()."',
												sw_adicional = '".$adicional."'

    							WHERE		ingreso_id= '".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."' AND
												fecha_solicitud= '".$fechaSolicitud."' AND
												tipo_solicitud_dieta_id =  '$tipo_solicitud' AND
												estacion_id =  '".$_REQUEST['datos_estacion']['estacion_id']."'";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al actualizar dietas_solicitud_detalle";
				$this->mensajeDeError = "Error al actualizar dietas_solicitud_detalle.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			$query = "	DELETE FROM dietas_solicitud_detalle_caracteristicas
									WHERE		ingreso_id= '".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."' AND
													fecha_solicitud= '".$fechaSolicitud."' AND
													tipo_solicitud_dieta_id =  '$tipo_solicitud' AND
													estacion_id =  '".$_REQUEST['datos_estacion']['estacion_id']."'";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al  eliminar dietas_solicitud_detalle_caracteristicas";
				$this->mensajeDeError = "Error al actualizar dietas_solicitud_detalle_caracteristicas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			foreach($_REQUEST['caracteristica_dieta'] AS $caract_dieta => $caract){
				$query = "INSERT INTO dietas_solicitud_detalle_caracteristicas
															(ingreso_id,
															fecha_solicitud,
															tipo_solicitud_dieta_id,
															estacion_id,
															caracteristica_id)
								VALUES ('".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."',
												'".$fecha_registro."',
												'$tipo_solicitud',
												'".$_REQUEST['datos_estacion']['estacion_id']."',
												'".$caract."'
								)";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al actualizar dietas_solicitud_detalle_caracteristicas";
					$this->mensajeDeError = "Error al actualizar dietas_solicitud_detalle_caracteristicas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}
			}

            IF($_REQUEST['tipodieta']=='' || $_REQUEST['tipodieta']=='-1')
            {
                $query = "INSERT INTO dietas_solicitud_detalle_caracteristicas
                                                            (ingreso_id,
                                                            fecha_solicitud,
                                                            tipo_solicitud_dieta_id,
                                                            estacion_id,
                                                            caracteristica_id)
                                VALUES ('".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."',
                                                '".$fecha_registro."',
                                                '$tipo_solicitud',
                                                '".$_REQUEST['datos_estacion']['estacion_id']."',
                                                '9'
                                )";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al actualizar dietas_solicitud_detalle_caracteristicas";
                    $this->mensajeDeError = "Error al actualizar dietas_solicitud_detalle_caracteristicas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
                }
            }
			$query = "UPDATE hc_solicitudes_dietas
								SET sw_fraccionada = '".$_REQUEST['fraccionada']."'
								WHERE ingreso = '".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."' AND
											hc_dieta_id 	= '".$_REQUEST['tipodieta']."'";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al actualizar hc_solicitudes_dietas";
				$this->mensajeDeError = "Error al actualizar hc_solicitudes_dietas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			if($_REQUEST['CtlAyuno'] == '1')
			{
				$inf_ayuno=$this->TraerInformacionAyuno($_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']);
				if(empty($inf_ayuno))
				{
					$query="INSERT INTO hc_solicitudes_dietas_ayunos (ingreso,fecha,motivo,usuario_id,fecha_registro,hora_fin_ayuno,hora_inicio_ayuno)
									VALUES ('".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."',
													'".$fecha_registro."',
													'".$_REQUEST['CtlDietasObsA']."',
													'".UserGetUID()."',
													'now()',
													'".$_REQUEST[horafin] ."',
													'".$_REQUEST[horainicio] ."')";
				}else{
					$query="UPDATE hc_solicitudes_dietas_ayunos
									SET fecha_registro = 'now()',
											hora_fin_ayuno = '".$_REQUEST[horafin] ."',
											hora_inicio_ayuno = '".$_REQUEST[horainicio] ."',
											motivo = '".$_REQUEST['CtlDietasObsA']."'
									WHERE ingreso = '".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."' AND
												fecha = '".$fecha_registro."'";
				}
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al actualizar/insertar ayunos";
					$this->mensajeDeError = "Error al actualizar ayunos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				$query = "UPDATE	dietas_solicitud_detalle
									SET			sw_ayuno 	= '1'
									WHERE		ingreso_id= '".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."' AND
													fecha_solicitud= '".$fechaSolicitud."' AND
													tipo_solicitud_dieta_id =  '$tipo_solicitud' AND
													estacion_id =  '".$_REQUEST['datos_estacion']['estacion_id']."'";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al actualizar/insertar ayunos en dietas_solicitud_detalle";
					$this->mensajeDeError = "Error al actualizar ayunos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
// 				$query = "UPDATE	dietas_solicitud_detalle
// 									SET			sw_ayuno 	= '1'
// 									WHERE		ingreso_id= '".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."' AND
// 													fecha_solicitud= '".$fechaSolicitud."' AND
// 													tipo_solicitud_dieta_id =  '$tipo_solicitud' AND
// 													estacion_id =  '".$_REQUEST['datos_estacion']['estacion_id']."'";
// 				$dbconn->Execute($query);
// 				if ($dbconn->ErrorNo() != 0)
// 				{
// 					$this->error = "Error al actualizar/insertar ayunos en dietas_solicitud_detalle";
// 					$this->mensajeDeError = "Error al actualizar ayunos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
// 					$dbconn->RollbackTrans();
// 					return false;
// 				}
			}

            //echo "sssss".$_REQUEST['DESACTIVA']."sdfdff";
            if($_REQUEST['cancela_dieta']=='45' || $_REQUEST['DESACTIVA']==2)
            {
                if($_REQUEST['DESACTIVA']==0)
                {
                    $estado_dieta=0;
                    $motivo="motivo_cancelacion_dieta = '".$_REQUEST['Mot_Cancelacion_Diet']."  HORA CANCELACION :".date("Y-m-d  G:i:s")."',";
                }
                elseif($_REQUEST['DESACTIVA']==2)
                {
                    $estado_dieta=2;
                    $motivo='';
                }

                //echo "estado de la tarea".$estado_dieta;//fecha_confirmacion = NOW(),
                $query = "UPDATE    dietas_solicitud
                                    SET
                                    estado_dieta = '".$estado_dieta."',
                                    $motivo
                                    usuario_id_cancelacion = '".UserGetUID()."'
                                    WHERE       ingreso_id='".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."' AND
                                                fecha_solicitud= '".$fechaSolicitud."' AND
                                                tipo_solicitud_dieta_id =  '$tipo_solicitud' AND
                                                estacion_id =  '".$_REQUEST['datos_estacion']['estacion_id']."'";


                $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al cancelar dieta en dietas_solicitud";
                    $this->mensajeDeError = "Error al actualizar ayunos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
                }



            }

            $query = "UPDATE    dietas_solicitud_detalle
                                    SET
                                    sw_recibida = '0',
                                    usuario_recibe = NULL,
                                    fecha_recibida = NULL
                                    
                                    WHERE       ingreso_id='".$_REQUEST['DATOS_PACIENTE']['DATOS_PACIENTE']['ingreso']."' AND
                                                fecha_solicitud= '".$fechaSolicitud."' AND
                                                tipo_solicitud_dieta_id =  '$tipo_solicitud' AND
                                                estacion_id =  '".$_REQUEST['datos_estacion']['estacion_id']."'";


                $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al cancelar dieta en dietas_solicitud";
                    $this->mensajeDeError = "Error al actualizar ayunos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
                }
            
			$dbconn->CommitTrans();
			$this->FrmPanelDietas();
			unset($_REQUEST);
			return true;
		}
		/**
		*
		*/
		function ConsultaEstadodieta($tipo,$ingreso,$estacion_id)
		{
			if($tipo=="desayuno")
            {
				$tipo_solicitud='1';
			}
            elseif($tipo=="almuerzo")
            {
				$tipo_solicitud='2';
			}
            else
            {
				$tipo_solicitud='3';
			}
            
			$fecha_registro=$_SESSION['DIETAS']['FECHAREG'];

            list($dbconn) = GetDBconn();

            $query = "SELECT 	COUNT(A.fecha_solicitud)
								FROM		dietas_solicitud AS A,
											dietas_solicitud_detalle AS B
								WHERE		A.fecha_solicitud = '".$fecha_registro."' AND
											A.tipo_solicitud_dieta_id = '".$tipo_solicitud."' AND
											A.estacion_id = '".$estacion_id."' AND
											A.fecha_solicitud = B.fecha_solicitud AND
											A.tipo_solicitud_dieta_id = B.tipo_solicitud_dieta_id AND
											A.estacion_id = B.estacion_id AND
											A.ingreso_id = B.ingreso_id  AND
											A.ingreso_id = '".$ingreso."'";
			$resultado=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al consultar ConsultaEstadodieta";
				$this->mensajeDeError = "Error al consultar ConsultaEstadodieta.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			$res=  $resultado->FetchRow();
			if ($res[0]==0)
            {
				return '0';
			}
			return '1';
		}
		
		/**
		*	 Consulta si una dieta esta cerrada o sigue activa
		*/
		function ConsultaCierreDieta($ingreso, $tipodieta,$estacion_id)
		{
			if($tipodieta=="desayuno"){
				$tipo_solicitud='1';
			}elseif($tipodieta=="almuerzo"){
				$tipo_solicitud='2';
			}else{
				$tipo_solicitud='3';
			}
			$fecha_registro=$_SESSION['DIETAS']['FECHAREG'];
			 $query="SELECT	a.usuario_id_cierre
							FROM		dietas_solicitud a,
											dietas_tipos_solicitud b
							WHERE		a.fecha_solicitud = '".$fecha_registro."'  AND
											a.tipo_solicitud_dieta_id = '".$tipo_solicitud."'AND
											a.estacion_id = '".$estacion_id."' AND
											a.ingreso_id = '".$ingreso."' AND
											a.tipo_solicitud_dieta_id = b.tipo_solicitud_dieta_id";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al consultar ConsultaEstadodieta";
				$this->mensajeDeError = "Error al consultar ConsultaEstadodieta.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			$res=$resultado->FetchRow();
			if(empty($res ) || $res['usuario_id_cierre']== '' ) {
				$res = '0';
			}else{
				$res = '1';
			}
			
			return $res;
		}
		
		/**
		*	 Consulta si una dieta esta cerrada o sigue activa
		*/
		function ConsultaHorraCierre($tipodieta)
		{
			if($tipodieta=="desayuno"){
				$tipo_solicitud='1';
			}elseif($tipodieta=="almuerzo"){
				$tipo_solicitud='2';
			}else{
				$tipo_solicitud='3';
			}
			 $query="SELECT	hora_inicio,
                            hora_cierre,
											--TO_CHAR(hora_cierre,'HH24:MM') as hora_cierre,
											--TO_DATE(hora_cierre) as hora_cierre,
											hora_cierre_adicional
							FROM		dietas_tipos_solicitud 
							WHERE		tipo_solicitud_dieta_id = '".$tipo_solicitud."'";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al consultar ConsultaEstadodieta";
				$this->mensajeDeError = "Error al consultar ConsultaEstadodieta.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			$res=  $resultado->FetchRow();
            
            return $res;
		}
		/**
		* @param horacierre hora en que se cierra el tipo de solicitud de dieta
		* @param tipo normal o adicional. horario normal o horario adicional
		*	@return boolean true si esta en el rango de la hora y false si se sale del rango
		*/
		function AnalizaHorasCierre($horacierre, $tipo_hora)
		{
			$horasistema=date("H:i");
            $hinicio=$horacierre['hora_inicio'];
            if($tipo_hora=="normal")
            {
                $hcierre=$horacierre['hora_cierre'];
			}
            else
            {
				$hcierre=$horacierre['hora_cierre_adicional'];
			}

            $tmpi=explode(":",$hinicio);
            $hini_hora=$tmpi[0];
            $hini_min=$tmpi[1];
            
            $tmp=explode(":",$hcierre);
            $hcie_hora=$tmp[0];
            $hcie_min=$tmp[1];
            $horasistema=explode(":",$horasistema);
            $horasistema[0];
            $horasistema[1];
            $futuro=strtotime(date("Y-m-d ".$hcie_hora.":".$hcie_min.":00"));
            if($hini_hora>$hcie_hora && $hini_hora<=$horasistema[0])
            {
               $futuro=strtotime("+1 day",$futuro);
            }
            $feqia_inicio=strtotime(date("Y-m-d ".$hini_hora.":".$hini_min.":00"));
            $hoy1a=strtotime(date("Y-m-d G:i:s"));
           // echo "ahotra".$hoy1a=strtotime(date("Y-m-d 08:40:00"));
            
			

          //  if($feqia_inicio <= $hoy1a) 
          //  {
          //  }
            if($hoy1a < $futuro)
            {
                return '1';
            }
            else
            {
                 return '0';
            }

//             if(($horasistema[0]<$hcie_hora)&&($hcie_min=='0'))
//             {
// 				$hcie_min='60';
// 			}

// 			if($horasistema[0]<=$hcie_hora)
//             {//hora
// 				if($horasistema[1]<$hcie_min)
//                 {//minuto
// 					return '1';
// 				}
//                 else
//                 {
// 					return '0';	
// 				}
// 			}
//             else
//             {
// 				return '0';	
// 			}
		}
}//end of class

?>
